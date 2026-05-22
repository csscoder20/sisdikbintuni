<?php

namespace Database\Seeders;

use App\Models\Laporan;
use App\Models\LaporanKeuangan;
use Illuminate\Database\Seeder;

class LaporanKeuanganSeeder extends Seeder
{
    public function run(): void
    {
        $penerimaanItems = [
            'Penyaluran BOSP tahap berjalan',
            'Bantuan operasional pemerintah daerah',
            'Pendapatan jasa giro rekening sekolah',
        ];

        $pengeluaranItems = [
            'Pembelian ATK dan bahan pembelajaran',
            'Pembayaran honor kegiatan sekolah',
            'Pemeliharaan ringan ruang kelas',
            'Belanja langganan internet',
            'Pengadaan bahan praktik peserta didik',
            'Transport kegiatan pembinaan siswa',
        ];

        foreach (Laporan::with('sekolah')->get() as $laporan) {
            $saldo = 0;
            $tanggalAwal = now()->setDate($laporan->tahun, $laporan->bulan, 1);

            foreach ($penerimaanItems as $index => $keterangan) {
                $nominal = 3500000 + (($laporan->sekolah_id + $laporan->bulan + $index) % 8) * 750000;
                $saldo += $nominal;
                $this->write($laporan->id, $tanggalAwal->copy()->addDays($index * 3), 'debit', $keterangan, $nominal, $saldo);
            }

            foreach ($pengeluaranItems as $index => $keterangan) {
                $nominal = 450000 + (($laporan->sekolah_id * 13 + $laporan->bulan + $index) % 9) * 125000;
                $saldo -= $nominal;
                $this->write($laporan->id, $tanggalAwal->copy()->addDays(8 + $index * 3), 'kredit', $keterangan, $nominal, $saldo);
            }
        }
    }

    private function write(int $laporanId, mixed $tanggal, string $jenisTransaksi, string $keterangan, int $nominal, int $saldo): void
    {
        LaporanKeuangan::updateOrCreate(
            [
                'laporan_id' => $laporanId,
                'tanggal' => $tanggal->toDateString(),
                'keterangan' => $keterangan,
            ],
            [
                'jenis_transaksi' => $jenisTransaksi,
                'nominal' => $nominal,
                'saldo' => $saldo,
            ]
        );
    }
}
