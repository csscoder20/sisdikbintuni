<?php

namespace App\Filament\Traits;

use Spatie\Browsershot\Browsershot;

trait HasBrowsershot
{
    /**
     * Buat instance Browsershot dengan konfigurasi yang sesuai lingkungan.
     * - Windows (local): menggunakan env() atau fallback Windows
     * - Linux (VPS): menggunakan hardcode path yang sudah terbukti berhasil
     */
    protected function makeBrowsershot(string $html): Browsershot
    {
        // Deteksi environment
        $isWindows = PHP_OS_FAMILY === 'Windows';

        if ($isWindows) {
            // === KONFIGURASI WINDOWS (Local Development) ===
            $nodeBinary = env('NODE_BINARY', 'C:\\Program Files\\nodejs\\node.exe');
            $npmBinary = env('NPM_BINARY', 'C:\\Program Files\\nodejs\\npm.cmd');
            $chromeBinary = env('CHROME_BINARY', '');

            $browsershot = Browsershot::html($html)
                ->setNodeBinary($nodeBinary)
                ->setNpmBinary($npmBinary)
                ->preferCssPageSize()
                ->format('A4')
                ->showBackground();

            if (!empty($chromeBinary)) {
                $browsershot->setChromePath($chromeBinary);
            }

            return $browsershot;
        }

        // === KONFIGURASI LINUX (VPS Production) ===
        // HARDCODE PATH YANG SUDAH TERBUKTI BERHASIL DI TINKER
        $chromePath = '/home/deploy/.cache/puppeteer/chrome-headless-shell/linux-149.0.7827.22/chrome-headless-shell-linux64/chrome-headless-shell';

        return Browsershot::html($html)
            ->setNodeBinary('/usr/bin/node')
            ->setChromePath($chromePath)
            ->addChromiumArguments([
                'no-sandbox',
                'disable-setuid-sandbox',
                'disable-dev-shm-usage',
                'disable-gpu',
                'disable-crash-reporter',
                'no-first-run',
                'disable-extensions',
                'user-data-dir=/tmp/chrome-browsershot-' . getmypid(),
            ])
            ->format('A4')
            ->showBackground();
    }
}
