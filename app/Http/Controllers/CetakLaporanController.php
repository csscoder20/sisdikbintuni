<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use App\Models\Laporan;
use App\Filament\Traits\HasLaporanBulananLogic;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CetakLaporanController extends Controller
{
    use HasLaporanBulananLogic;

    public function downloadPdf(Sekolah $sekolah)
    {
        $this->schoolId = $sekolah->id;
        $this->isPreview = true;
        
        // Find latest report to get validated status
        $laporan = Laporan::where('sekolah_id', $sekolah->id)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->first();
            
        $this->selectedLaporanId = $laporan?->id;
        
        // Initialize status
        $this->initializeLaporanBulanan();

        $data = [
            'sekolah' => $sekolah,
            'checklist' => $this->checklist,
            'checklistStatus' => $this->checklistStatus,
            'reportData' => [],
        ];

        foreach ($this->checklist as $key => $label) {
            if ($this->checklistStatus[$key]) {
                $data['reportData'][$key] = $this->getChecklistPreviewData($key);
            }
        }

        $pdf = Pdf::loadView('pdf.report-pdf', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download("Laporan_Bulanan_{$sekolah->npsn}.pdf");
    }
}
