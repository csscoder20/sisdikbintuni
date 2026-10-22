<?php

namespace App\Filament\Pages;

use App\Models\DatabaseBackup as DatabaseBackupModel;
use App\Services\DatabaseBackupService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class DatabaseBackup extends Page
{
    protected static ?string $navigationLabel = 'Backup Database';
    protected static ?string $title = 'Backup Database';
    protected static ?int $navigationSort = 99;
    protected static string|\UnitEnum|null $navigationGroup = 'Sistem';
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCircleStack;
    protected string $view = 'filament.pages.database-backup';

    public static function canAccess(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'dinas'
            && auth()->user()?->hasRole('super_admin');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('confirmBackup')
                ->label('Buat Backup Sekarang')
                ->icon(Heroicon::OutlinedCircleStack)
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Buat backup database?')
                ->modalDescription('Pilih format yang sesuai dengan kebutuhan pemulihan database.')
                ->form([
                    Select::make('format')
                        ->label('Format backup')
                        ->options([
                            'custom' => '.dump — Direkomendasikan untuk restore PostgreSQL',
                            'plain' => '.sql — Skrip SQL yang dapat dibaca',
                        ])
                        ->default('custom')
                        ->required()
                        ->helperText('.dump dipulihkan dengan pg_restore; .sql dapat dijalankan melalui psql.'),
                ])
                ->modalSubmitActionLabel('Buat Backup')
                ->action(fn (array $data): mixed => $this->createBackup(
                    app(DatabaseBackupService::class),
                    $data['format'],
                ))
                ->extraAttributes(['style' => 'display: none !important;']),
        ];
    }

    public function createBackup(DatabaseBackupService $backupService, string $format = 'custom'): void
    {
        try {
            $backup = $backupService->create(auth()->user(), $format);

            Notification::make()
                ->title('Backup database selesai dibuat')
                ->body("File {$backup->file_name} tersimpan dengan aman.")
                ->success()
                ->send();
        } catch (Throwable $exception) {
            report($exception);

            Notification::make()
                ->title('Backup database gagal')
                ->body($exception->getMessage())
                ->danger()
                ->send();
        }
    }

    public function downloadBackup(int $backupId): mixed
    {
        $backup = DatabaseBackupModel::query()
            ->where('status', 'completed')
            ->find($backupId);

        if (! $backup) {
            Notification::make()
                ->title('Backup tidak tersedia')
                ->body('Backup yang dipilih belum selesai atau tidak ditemukan.')
                ->warning()
                ->send();

            return null;
        }

        if (! $backup->path || ! Storage::disk('local')->exists($backup->path)) {
            Notification::make()
                ->title('File backup tidak ditemukan')
                ->body('File mungkin telah dipindahkan atau dihapus dari penyimpanan privat aplikasi.')
                ->danger()
                ->send();

            return null;
        }

        return Storage::disk('local')->download(
            $backup->path,
            $backup->file_name ?? 'database-backup.dump',
        );
    }

    public function getDatabaseInfo(): array
    {
        try {
            $size = DB::selectOne('SELECT pg_size_pretty(pg_database_size(current_database())) AS size');

            return [
                'connection' => config('database.default'),
                'database' => config('database.connections.pgsql.database'),
                'host' => config('database.connections.pgsql.host'),
                'size' => $size->size ?? 'Tidak tersedia',
                'status' => 'Terhubung',
            ];
        } catch (Throwable) {
            return [
                'connection' => config('database.default'),
                'database' => config('database.connections.pgsql.database'),
                'host' => config('database.connections.pgsql.host'),
                'size' => 'Tidak tersedia',
                'status' => 'Tidak terhubung',
            ];
        }
    }

    public function getBackups(): iterable
    {
        return DatabaseBackupModel::query()
            ->with('user:id,name')
            ->latest()
            ->limit(20)
            ->get();
    }
}
