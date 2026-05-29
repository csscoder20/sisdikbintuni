<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class GenerateImportTemplates extends Command
{
    protected $signature   = 'import:generate-templates {--only=* : Nama importer spesifik (gtk-pendidikan|laporan-keuangan|rombel)}';
    protected $description = 'Generate static Excel import templates for importers that do not have one yet';

    // ─── Definisi template ──────────────────────────────────────────────────

    private function getTemplates(): array
    {
        return [

            // ── GtkPendidikan ───────────────────────────────────────────────
            'gtk-pendidikan' => [
                'filename'   => 'gtk-pendidikan_import_template.xlsx',
                'sheetTitle' => 'RIWAYAT PENDIDIKAN GTK',
                'groupTitle' => 'DATA RIWAYAT PENDIDIKAN GTK',
                'color'      => '3C8DBC',
                'fontColor'  => 'FFFFFF',
                'columns'    => [
                    ['name' => 'nik_gtk',             'label' => 'NIK GTK',              'example' => '3201010101010005', 'instruction' => 'NIK GTK yang sudah terdaftar di sistem (16 digit)'],
                    ['name' => 'gelar_belakang',       'label' => 'Gelar Belakang',       'example' => 'S.Pd',            'instruction' => 'gelar akademik di belakang nama, kosongkan jika tidak ada'],
                    ['name' => 'thn_tamat_sd',         'label' => 'Thn Tamat SD',         'example' => '1997',            'instruction' => 'tahun tamat SD (4 digit), kosongkan jika tidak ada'],
                    ['name' => 'thn_tamat_smp',        'label' => 'Thn Tamat SMP',        'example' => '2000',            'instruction' => 'tahun tamat SMP (4 digit), kosongkan jika tidak ada'],
                    ['name' => 'thn_tamat_sma',        'label' => 'Thn Tamat SMA',        'example' => '2003',            'instruction' => 'tahun tamat SMA/SMK/sederajat (4 digit), kosongkan jika tidak ada'],
                    ['name' => 'thn_tamat_s1',         'label' => 'Thn Tamat S1',         'example' => '2007',            'instruction' => 'tahun tamat S1/D3/D4 (4 digit), kosongkan jika tidak ada'],
                    ['name' => 'jurusan_s1',           'label' => 'Jurusan S1',           'example' => 'Pendidikan Matematika',  'instruction' => 'nama jurusan/program studi, kosongkan jika tidak ada'],
                    ['name' => 'perguruan_tinggi_s1',  'label' => 'Perguruan Tinggi S1',  'example' => 'Universitas Cenderawasih', 'instruction' => 'nama perguruan tinggi, kosongkan jika tidak ada'],
                ],
            ],

            // ── LaporanKeuangan ─────────────────────────────────────────────
            'laporan-keuangan' => [
                'filename'   => 'laporan-keuangan_import_template.xlsx',
                'sheetTitle' => 'TRANSAKSI KEUANGAN',
                'groupTitle' => 'DATA TRANSAKSI KEUANGAN',
                'color'      => '6366F1',
                'fontColor'  => 'FFFFFF',
                'columns'    => [
                    ['name' => 'tanggal',          'label' => 'Tanggal',          'example' => '01/05/2026',       'instruction' => 'diisi dengan format dd/mm/yyyy, contoh: 01/05/2026'],
                    ['name' => 'jenis_transaksi',  'label' => 'Jenis Transaksi',  'example' => 'Debit',            'instruction' => 'pilihan: Debit (uang masuk) / Kredit (uang keluar)'],
                    ['name' => 'keterangan',       'label' => 'Keterangan',       'example' => 'Pembayaran listrik', 'instruction' => 'keterangan atau deskripsi transaksi'],
                    ['name' => 'nominal',          'label' => 'Nominal',          'example' => '500000',           'instruction' => 'jumlah nominal dalam angka (tanpa titik/koma), contoh: 500000'],
                ],
            ],

            // ── Rombel ──────────────────────────────────────────────────────
            'rombel' => [
                'filename'   => 'rombel_import_template.xlsx',
                'sheetTitle' => 'DATA ROMBEL',
                'groupTitle' => 'DATA ROMBONGAN BELAJAR (ROMBEL)',
                'color'      => 'F59E0B',
                'fontColor'  => 'FFFFFF',
                'columns'    => [
                    ['name' => 'nama',    'label' => 'Nama Rombel', 'example' => 'X-IPA-1', 'instruction' => 'nama rombel/kelas, contoh: X-IPA-1, XI IPS 2, XII-MIPA-3'],
                    ['name' => 'tingkat', 'label' => 'Tingkat',     'example' => '10',       'instruction' => 'diisi dengan angka: 10 (kelas X), 11 (kelas XI), atau 12 (kelas XII)'],
                ],
            ],

        ];
    }

    // ─── Handle ─────────────────────────────────────────────────────────────

    public function handle(): int
    {
        $only      = $this->option('only');
        $templates = $this->getTemplates();

        if (!empty($only)) {
            $templates = array_intersect_key($templates, array_flip($only));
            if (empty($templates)) {
                $this->error('Tidak ada template yang cocok dengan pilihan: ' . implode(', ', $only));
                return self::FAILURE;
            }
        }

        $outputDir = public_path('assets/import-excel');
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        foreach ($templates as $key => $config) {
            $this->info("Generating: {$key} ...");

            $spreadsheet = new Spreadsheet();
            $spreadsheet->getDefaultStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getDefaultStyle()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle($config['sheetTitle']);

            $this->setupSheet(
                $sheet,
                $config['groupTitle'],
                $config['columns'],
                $config['color'],
                $config['fontColor'],
            );

            $writer   = new Xlsx($spreadsheet);
            $savePath = $outputDir . DIRECTORY_SEPARATOR . $config['filename'];
            $writer->save($savePath);

            $this->line("  ✔  Disimpan ke: public/assets/import-excel/{$config['filename']}");
        }

        $this->newLine();
        $this->info('Semua template berhasil di-generate.');
        return self::SUCCESS;
    }

    // ─── Helper: setupSheet ─────────────────────────────────────────────────

    private function setupSheet($sheet, ?string $groupTitle, array $columns, string $color = '3C8DBC', string $fontColor = 'FFFFFF'): void
    {
        $headerRow     = 1;
        $instructionRow = 2;
        $exampleRow    = 3;

        // Baris judul grup (merge semua kolom)
        if ($groupTitle) {
            $lastCol = Coordinate::stringFromColumnIndex(count($columns));
            $sheet->setCellValue('A1', $groupTitle);
            $sheet->mergeCells("A1:{$lastCol}1");
            $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
                'font'      => ['bold' => true, 'size' => 12, 'color' => ['rgb' => $fontColor]],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $color]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ]);
            $sheet->getRowDimension(1)->setRowHeight(22);
            $headerRow      = 2;
            $instructionRow = 3;
            $exampleRow     = 4;
        }

        // Per-kolom
        foreach ($columns as $i => $col) {
            $colLetter = Coordinate::stringFromColumnIndex($i + 1);

            // Header
            $sheet->setCellValue($colLetter . $headerRow, strtoupper($col['label']));

            // Instruksi
            $sheet->setCellValue($colLetter . $instructionRow, $col['instruction']);
            $sheet->getStyle($colLetter . $instructionRow)->applyFromArray([
                'font'      => ['italic' => true, 'color' => ['rgb' => 'CC0000'], 'size' => 9],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true],
            ]);

            // Contoh
            $sheet->setCellValueExplicit(
                $colLetter . $exampleRow,
                (string) $col['example'],
                DataType::TYPE_STRING
            );

            // Format text untuk seluruh kolom agar angka tidak diubah Excel
            $sheet->getStyle($colLetter . '1:' . $colLetter . '1000')
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_TEXT);

            $sheet->getStyle($colLetter . '1:' . $colLetter . '1000')->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        // Styling baris header
        $lastColLetter = Coordinate::stringFromColumnIndex(count($columns));
        $sheet->getStyle('A' . $headerRow . ':' . $lastColLetter . $headerRow)->applyFromArray([
            'font'    => ['bold' => true, 'color' => ['rgb' => $fontColor]],
            'fill'    => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $color]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        $sheet->getRowDimension($headerRow)->setRowHeight(20);

        // Styling baris instruksi
        $sheet->getStyle('A' . $instructionRow . ':' . $lastColLetter . $instructionRow)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'fill'    => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFF9C4']],
        ]);
        $sheet->getRowDimension($instructionRow)->setRowHeight(36);

        // Styling baris contoh
        $sheet->getStyle('A' . $exampleRow . ':' . $lastColLetter . $exampleRow)->applyFromArray([
            'font'    => ['color' => ['rgb' => '555555']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'fill'    => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0F0F0']],
        ]);
        $sheet->getRowDimension($exampleRow)->setRowHeight(18);
    }
}
