<?php

namespace App\Filament\Traits;

use Spatie\Browsershot\Browsershot;

trait HasBrowsershot
{
    /**
     * Buat instance Browsershot dengan konfigurasi yang sesuai lingkungan.
     * Semua path dibaca dari .env agar bisa berbeda antara localhost (Windows)
     * dan VPS (Linux) tanpa mengubah kode.
     *
     * Konfigurasi .env lokal (Windows):
     *   NODE_BINARY="C:\Program Files\nodejs\node.exe"
     *   NPM_BINARY="C:\Program Files\nodejs\npm.cmd"
     *   CHROME_BINARY=   (kosong — Puppeteer otomatis mengelola)
     *
     * Konfigurasi .env VPS (Linux):
     *   NODE_BINARY=/usr/bin/node
     *   NPM_BINARY=/usr/bin/npm
     *   CHROME_BINARY=/usr/bin/chromium-browser
     *   (atau: /usr/bin/chromium / /usr/bin/google-chrome)
     */
    protected function makeBrowsershot(string $html): Browsershot
    {
        $nodeBinary   = env('NODE_BINARY');
        $npmBinary    = env('NPM_BINARY');
        $chromeBinary = env('CHROME_BINARY');

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

        // Gunakan Chrome/Chromium sistem jika path dikonfigurasi di .env
        // Ini diperlukan di VPS agar tidak mengandalkan Puppeteer auto-download
        if (!empty($chromeBinary)) {
            $browsershot->setChromePath($chromeBinary);
        }

        return $browsershot;
    }
}

