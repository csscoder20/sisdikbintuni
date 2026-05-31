<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Traits\HasBrowsershot;
use Illuminate\Support\Facades\View;

class ListUsers extends ListRecords
{
    use HasBrowsershot;

    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('export')
                ->label('Ekspor Data')
                ->color('success')
                // ->icon('heroicon-o-arrow-down-tray')
                ->modalHeading('Preview Ekspor Data Pengguna')
                ->modalWidth(\Filament\Support\Enums\Width::FourExtraLarge)
                ->modalContent(fn() => view('users.preview', ['records' => $this->getFilteredTableQuery()->get()]))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Batal')
                ->modalFooterActions(fn(\Filament\Actions\Action $action) => [
                    \Filament\Actions\Action::make('export_excel')
                        ->label('Ekspor Excel')
                        ->color('success')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function () {
                            $query = $this->getFilteredTableQuery();

                            $roleMap = [
                                'operator' => 'Operator Sekolah',
                                'admin_dinas' => 'Admin Dinas',
                                'super_admin' => 'Administrator',
                                'pengawas' => 'Pengawas',
                            ];

                            $records = $query->get()->map(function ($user) use ($roleMap) {
                                return (object) [
                                    'name' => $user->name,
                                    'email' => $user->email,
                                    'nohp' => $user->nohp,
                                    'roles' => $user->roles->pluck('name')->map(fn($r) => $roleMap[$r] ?? $r)->join(', '),
                                    'sekolah' => $user->sekolah?->nama ?? '-',
                                    'status' => $user->status,
                                ];
                            });

                            $columns = [
                                'name' => 'Nama',
                                'email' => 'Email',
                                'nohp' => 'Nomor WA',
                                'roles' => 'Peran',
                                'sekolah' => 'Sekolah',
                                'status' => 'Status',
                            ];

                            return \Maatwebsite\Excel\Facades\Excel::download(
                                new \App\Exports\DynamicExport($records, $columns, null, 'DATA PENGGUNA SISTEM'),
                                'Data Pengguna.xlsx'
                            );
                        }),
                    \Filament\Actions\Action::make('export_pdf')
                        ->label('Ekspor PDF')
                        ->color('danger')
                        ->icon('heroicon-o-document-text')
                        ->action(function () {
                            $records = $this->getFilteredTableQuery()->get();

                            $html = view('pdf.users-export', [
                                'records' => $records,
                            ])->render();

                            $browsershot = $this->makeBrowsershot($html)->landscape();
                            $pdfContent = $browsershot->pdf();

                            return response()->streamDownload(
                                fn() => print($pdfContent),
                                'Data Pengguna.pdf'
                            );
                        }),
                    $action->getModalCancelAction()->label('Batal'),
                ]),
            CreateAction::make()
                ->label('Tambah Pengguna')
                ->modalHeading('Tambah Pengguna')
                ->modalSubmitActionLabel('Simpan Pengguna')
                ->createAnother(false),
        ];
    }
}
