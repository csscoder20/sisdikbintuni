<?php

namespace App\Services;

use App\Models\DatabaseBackup;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use RuntimeException;

class DatabaseBackupService
{
    public function create(User $user, string $format = 'custom'): DatabaseBackup
    {
        $connection = config('database.default');
        $database = config("database.connections.{$connection}");

        if ($connection !== 'pgsql' || ! is_array($database)) {
            throw new RuntimeException('Backup manual saat ini hanya tersedia untuk koneksi PostgreSQL.');
        }

        if (! in_array($format, ['custom', 'plain'], true)) {
            throw new RuntimeException('Format backup yang dipilih tidak valid.');
        }

        $name = (string) ($database['database'] ?? 'database');
        $timestamp = now()->format('Ymd_His');
        $extension = $format === 'plain' ? 'sql' : 'dump';
        $fileName = sprintf('%s_%s.%s', str($name)->slug('_'), $timestamp, $extension);
        $relativePath = "database-backups/{$fileName}";
        $absolutePath = storage_path("app/private/{$relativePath}");

        File::ensureDirectoryExists(dirname($absolutePath));

        $backup = DatabaseBackup::create([
            'user_id' => $user->id,
            'database' => $name,
            'format' => $format,
            'file_name' => $fileName,
            'path' => $relativePath,
            'status' => 'processing',
            'started_at' => now(),
        ]);

        $command = [
            config('database.backup.pg_dump_path', 'pg_dump'),
            '--host=' . ($database['host'] ?? '127.0.0.1'),
            '--port=' . ($database['port'] ?? '5432'),
            '--username=' . ($database['username'] ?? ''),
            '--format=' . ($format === 'plain' ? 'plain' : 'custom'),
            '--file=' . $absolutePath,
            $name,
        ];

        // Symfony Process replaces the process environment when an env array is passed.
        // On Windows, PostgreSQL also needs system variables (especially SystemRoot)
        // to initialise Winsock. Preserve them while passing the database password.
        $process = new Process($command, null, $this->processEnvironment(
            (string) ($database['password'] ?? ''),
        ));
        $process->setTimeout(900);
        $process->run();

        if (! $process->isSuccessful() || ! File::exists($absolutePath)) {
            File::delete($absolutePath);
            $backup->update([
                'status' => 'failed',
                'error_message' => str($process->getErrorOutput() ?: $process->getOutput())->limit(1000),
                'completed_at' => now(),
            ]);

            throw new RuntimeException('Proses pg_dump gagal. Periksa konfigurasi PG_DUMP_PATH dan akses PostgreSQL.');
        }

        $backup->update([
            'status' => 'completed',
            'size' => File::size($absolutePath),
            'completed_at' => now(),
        ]);

        return $backup;
    }

    private function processEnvironment(string $password): array
    {
        $names = ['PATH', 'SystemRoot', 'SystemDrive', 'WINDIR', 'TEMP', 'TMP'];
        $environment = [];

        foreach ($names as $name) {
            $value = getenv($name);

            if ($value !== false) {
                $environment[$name] = $value;
            }
        }

        $environment['PGPASSWORD'] = $password;

        return $environment;
    }
}
