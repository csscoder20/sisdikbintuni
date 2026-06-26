<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use App\Models\Laporan;
use App\Filament\Traits\HasLaporanBulananLogic;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;
use App\Filament\Traits\HasBrowsershot;
use Illuminate\Support\Facades\Log;

class CetakLaporanController extends Controller
{
    use HasLaporanBulananLogic;
    use HasBrowsershot;

    public function downloadPdf(Sekolah $sekolah)
    {
        try {
            Log::info('Step 1: PDF Download started', [
                'sekolah_id' => $sekolah->id,
                'user_id' => auth()->id(),
                'laporan_id' => request('laporan_id')
            ]);

            $this->schoolId = $sekolah->id;
            $this->isPreview = true;

            $laporanId = request('laporan_id');
            if ($laporanId) {
                $laporan = Laporan::where('sekolah_id', $sekolah->id)->find($laporanId);
            } else {
                $laporan = Laporan::where('sekolah_id', $sekolah->id)
                    ->orderBy('tahun', 'desc')
                    ->orderBy('bulan', 'desc')
                    ->first();
            }

            Log::info('Step 2: Laporan found', [
                'found' => $laporan ? 'yes' : 'no',
                'laporan_id' => $laporan?->id
            ]);

            if (!$laporan) {
                return back()->with('error', 'Laporan tidak ditemukan');
            }

            $this->selectedLaporanId = $laporan->id;

            Log::info('Step 3: Initializing laporan bulanan');
            $this->initializeLaporanBulanan();

            // Resize gambar sebelum base64 untuk memperkecil HTML
            $dinasLogoBase64 = $this->imageToBase64(public_path('assets/logo/logo-bintuni.png'));

            $sekolahLogoBase64 = '';
            if ($sekolah->logo) {
                $sekolahLogoPath = storage_path('app/public/' . $sekolah->logo);
                $sekolahLogoBase64 = $this->imageToBase64($sekolahLogoPath);
            }

            if (empty($sekolahLogoBase64)) {
                $sekolahLogoBase64 = $this->imageToBase64(public_path('assets/logo/tut-wuri-handayani.png'));
            }

            $periode = \Carbon\Carbon::now()->translatedFormat('F Y');
            if ($laporan) {
                $periode = \Carbon\Carbon::createFromDate($laporan->tahun, $laporan->bulan, 1)->translatedFormat('F Y');
            }

            $data = [
                'sekolah' => $sekolah,
                'checklist' => $this->checklist,
                'checklistStatus' => $this->checklistStatus,
                'reportData' => [],
                'dinasLogo' => $dinasLogoBase64,
                'sekolahLogo' => $sekolahLogoBase64,
                'periode' => $periode,
                'laporan' => $laporan,
            ];

            foreach ($this->checklist as $key => $label) {
                if ($this->checklistStatus[$key]) {
                    $data['reportData'][$key] = $this->getChecklistPreviewData($key);
                }
            }

            Log::info('Step 4: Rendering HTML view');
            $html = view('pdf.report-pdf', $data)->render();

            Log::info('Step 5: HTML rendered successfully', [
                'html_length' => strlen($html)
            ]);

            Log::info('Step 6: Generating PDF with Browsershot');
            $pdfContent = $this->makeBrowsershot($html)->pdf();

            Log::info('Step 7: PDF generated successfully', [
                'pdf_size' => strlen($pdfContent)
            ]);

            $filename = "Laporan Bulanan - {$sekolah->nama} Periode {$periode}.pdf";

            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . str_replace('"', '\"', $filename) . '"');
        } catch (\Exception $e) {
            Log::error('PDF Generation FAILED at step', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Resize gambar menggunakan GD dan encode ke base64.
     * Mengurangi ukuran logo secara signifikan sebelum di-embed ke HTML.
     */
    private function imageToBase64(string $path, int $maxDimension = 300): string
    {
        if (!file_exists($path)) {
            return '';
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mimeType = match ($ext) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png'         => 'image/png',
            default       => 'image/png',
        };

        // Jika file sudah kecil (<= 50KB), langsung encode tanpa resize
        if (filesize($path) <= 50 * 1024) {
            return 'data:' . $mimeType . ';base64,' . base64_encode(file_get_contents($path));
        }

        $info = @getimagesize($path);
        if (!$info) {
            return 'data:' . $mimeType . ';base64,' . base64_encode(file_get_contents($path));
        }

        [$origWidth, $origHeight] = $info;

        // Jika sudah dalam batas, langsung encode
        if ($origWidth <= $maxDimension && $origHeight <= $maxDimension) {
            return 'data:' . $mimeType . ';base64,' . base64_encode(file_get_contents($path));
        }

        // Hitung dimensi baru dengan mempertahankan rasio aspek
        $scale     = min($maxDimension / $origWidth, $maxDimension / $origHeight);
        $newWidth  = (int) ($origWidth * $scale);
        $newHeight = (int) ($origHeight * $scale);

        $src = match ($ext) {
            'png'        => @imagecreatefrompng($path),
            'jpg','jpeg' => @imagecreatefromjpeg($path),
            default      => null,
        };

        if (!$src) {
            return 'data:' . $mimeType . ';base64,' . base64_encode(file_get_contents($path));
        }

        $dst = imagecreatetruecolor($newWidth, $newHeight);

        // Pertahankan transparansi untuk PNG
        if ($ext === 'png') {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
            imagefill($dst, 0, 0, $transparent);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        ob_start();
        if ($ext === 'png') {
            imagepng($dst, null, 6);
        } else {
            imagejpeg($dst, null, 80);
        }
        $data = ob_get_clean();

        imagedestroy($src);
        imagedestroy($dst);

        return 'data:' . $mimeType . ';base64,' . base64_encode($data);
    }
}
