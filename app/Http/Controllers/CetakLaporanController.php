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

            // Gunakan file:// path langsung (tanpa base64) untuk mempercepat PDF
            $dinasLogoPath = public_path('assets/logo/logo-bintuni.png');
            $dinasLogoBase64 = file_exists($dinasLogoPath) ? 'file://' . $dinasLogoPath : '';

            // School Logo
            $sekolahLogoBase64 = '';
            if ($sekolah->logo) {
                $sekolahLogoPath = storage_path('app/public/' . $sekolah->logo);
                if (file_exists($sekolahLogoPath)) {
                    $sekolahLogoBase64 = 'file://' . $sekolahLogoPath;
                }
            }

            if (empty($sekolahLogoBase64)) {
                $fallbackLogoPath = public_path('assets/logo/tut-wuri-handayani.png');
                $sekolahLogoBase64 = file_exists($fallbackLogoPath) ? 'file://' . $fallbackLogoPath : '';
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
}
