<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use App\Models\Laporan;
use App\Filament\Traits\HasLaporanBulananLogic;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;

class CetakLaporanController extends Controller
{
    use HasLaporanBulananLogic;

    public function downloadPdf(Sekolah $sekolah)
    {
        $this->schoolId = $sekolah->id;
        $this->isPreview = true;
        
        $laporanId = request('laporan_id');
        if ($laporanId) {
            $laporan = Laporan::where('sekolah_id', $sekolah->id)->find($laporanId);
        } else {
            // Find latest report to get validated status
            $laporan = Laporan::where('sekolah_id', $sekolah->id)
                ->orderBy('tahun', 'desc')
                ->orderBy('bulan', 'desc')
                ->first();
        }
            
        $this->selectedLaporanId = $laporan?->id;
        
        // Initialize status
        $this->initializeLaporanBulanan();

        // Generate base64 Dinas Logo
        $dinasLogoPath = public_path('assets/logo/logo-bintuni.png');
        $dinasLogoBase64 = '';
        if (file_exists($dinasLogoPath)) {
            $dinasLogoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($dinasLogoPath));
        }

        // Generate base64 School Logo (falls back to Tut Wuri Handayani)
        $sekolahLogoBase64 = '';
        if ($sekolah->logo) {
            $sekolahLogoPath = storage_path('app/public/' . $sekolah->logo);
            if (file_exists($sekolahLogoPath)) {
                $sekolahLogoBase64 = 'data:image/' . pathinfo($sekolahLogoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($sekolahLogoPath));
            }
        }
        
        if (empty($sekolahLogoBase64)) {
            $fallbackLogoPath = public_path('assets/logo/tut-wuri-handayani.png');
            if (file_exists($fallbackLogoPath)) {
                $sekolahLogoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($fallbackLogoPath));
            }
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
        ];

        foreach ($this->checklist as $key => $label) {
            if ($this->checklistStatus[$key]) {
                $data['reportData'][$key] = $this->getChecklistPreviewData($key);
            }
        }

        $html = view('pdf.report-pdf', $data)->render();

        $pdfContent = Browsershot::html($html)
            ->setNodeBinary('C:\Program Files\nodejs\node.exe')
            ->setNpmBinary('C:\Program Files\nodejs\npm.cmd')
            ->preferCssPageSize()
            ->format('A4')
            ->showBackground()
            ->noSandbox()
            ->pdf();

        $filename = "Laporan Bulanan - {$sekolah->nama} Periode {$periode}.pdf";

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . str_replace('"', '\"', $filename) . '"');
    }
}
