<?php

namespace App\Filament\Traits;

use Spatie\Browsershot\Browsershot;

trait HasBrowsershot
{
    /**
     * Buat instance Browsershot dengan konfigurasi yang sesuai lingkungan.
     * Path Node/NPM dibaca dari .env agar bisa berbeda antara localhost (Windows)
     * dan VPS (Linux) tanpa mengubah kode.
     *
     * Di .env lokal (Windows):
     *   NODE_BINARY="C:\Program Files\nodejs\node.exe"
     *   NPM_BINARY="C:\Program Files\nodejs\npm.cmd"
     *
     * Di .env VPS (Linux):
     *   NODE_BINARY=/usr/bin/node
     *   NPM_BINARY=/usr/bin/npm
     *   (atau kosongkan agar menggunakan deteksi otomatis)
     */
    protected function makeBrowsershot(string $html): Browsershot
    {
        $nodeBinary = env('NODE_BINARY');
        $npmBinary  = env('NPM_BINARY');

        // Fallback otomatis berdasarkan OS jika .env tidak dikonfigurasi
        if (empty($nodeBinary)) {
            $nodeBinary = PHP_OS_FAMILY === 'Windows'
                ? 'C:\\Program Files\\nodejs\\node.exe'
                : '/usr/bin/node';
        }

        if (empty($npmBinary)) {
            $npmBinary = PHP_OS_FAMILY === 'Windows'
                ? 'C:\\Program Files\\nodejs\\npm.cmd'
                : '/usr/bin/npm';
        }

        $browsershot = Browsershot::html($html)
            ->setNodeBinary($nodeBinary)
            ->setNpmBinary($npmBinary)
            ->preferCssPageSize()
            ->format('A4')
            ->showBackground()
            ->noSandbox();

        return $browsershot;
    }
}
