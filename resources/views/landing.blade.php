<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAPBUL SMA/SMK - Kabupaten Teluk Bintuni</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .hero-gradient { background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); }
    </style>
    <meta property="og:title" content="Lapbul Dikpora Bintuni" />
    <meta property="og:description" content="Aplikasi laporan bulanan Dikpora Bintuni" />
    <meta property="og:image" content="https://lapbul.dikporabintuni.com/assets/logo/logo-bintuni.png" />
    <meta property="og:url" content="https://lapbul.dikporabintuni.com" />
    <meta property="og:type" content="website" />
</head>
<body class="bg-gray-50">

    <header class="hero-gradient text-white">
        <div class="container mx-auto px-6 py-16 text-center">
            <img src="/assets/logo/logo-bintuni.png" alt="Logo Bintuni" class="h-24 mx-auto mb-6">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Sistem Pelaporan Bulanan SMA/SMK</h1>
            <p class="text-xl mb-8 opacity-90">Kabupaten Teluk Bintuni - Portal Data Pendidikan Terpadu</p>
            <div class="flex justify-center gap-4">
                <a target="_blank" href="/login" class="bg-yellow-500 hover:bg-yellow-600 text-blue-900 font-bold py-3 px-8 rounded-full transition duration-300 shadow-lg">
                    <i class="fas fa-sign-in-alt mr-2"></i> LOGIN OPERATOR
                </a>
                <a href="#statistik" class="bg-white/20 hover:bg-white/30 text-white font-semibold py-3 px-8 rounded-full transition duration-300 backdrop-blur-sm">
                    Lihat Statistik
                </a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-6 -mt-10">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-12">
            <div class="bg-white p-6 rounded-xl shadow-md border-b-4 border-red-500">
                <div class="text-gray-500 text-sm font-bold uppercase">Total Sekolah</div>
                <div class="text-3xl font-bold text-gray-800">{{ number_format($totalSekolah) }}</div>
                <div class="text-red-500 text-xs font-semibold mt-2"><i class="fas fa-building mr-1"></i> SMA & SMK</div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md border-b-4 border-blue-600">
                <div class="text-gray-500 text-sm font-bold uppercase">Total Siswa</div>
                <div class="text-3xl font-bold text-gray-800">{{ number_format($totalSiswa) }}</div>
                <div class="text-green-500 text-xs font-semibold mt-2"><i class="fas fa-user-grad mr-1"></i> Tersebar di {{ number_format($totalSekolah) }} Sekolah</div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md border-b-4 border-green-500">
                <div class="text-gray-500 text-sm font-bold uppercase">Total GTK</div>
                <div class="text-3xl font-bold text-gray-800">{{ number_format($totalGtk) }}</div>
                <div class="text-gray-400 text-xs mt-2">Guru & Tenaga Kependidikan</div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md border-b-4 border-purple-500">
                @php
                    $kondisi = $kondisiRuang ?? ['Baik' => 0, 'Rusak Ringan' => 0, 'Rusak Sedang' => 0, 'Rusak Berat' => 0];
                    $totalRuang = array_sum($kondisi);
                    $layak = ($kondisi['Baik'] ?? 0) + ($kondisi['Rusak Ringan'] ?? 0);
                    $persentaseLayak = $totalRuang > 0 ? round(($layak / $totalRuang) * 100) : 0;
                @endphp
                <div class="text-gray-500 text-sm font-bold uppercase">Kondisi Ruang/Gedung</div>
                <div class="text-3xl font-bold text-gray-800">{{ $persentaseLayak }}%</div>
                <div class="text-blue-500 text-xs font-semibold mt-2">Kategori Layak ({{ $layak }} dari total {{ $totalRuang }} ruang/gedung)</div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md border-b-4 border-yellow-500">
                <div class="text-gray-500 text-sm font-bold uppercase">Laporan Masuk</div>
                <div class="text-3xl font-bold text-gray-800">{{ $laporanMasuk }}/{{ $totalSekolah }}</div>
                <div class="text-orange-500 text-xs font-semibold mt-2">Update: {{ $periodeLaporan }}</div>
            </div>
        </div>

        <div id="statistik" class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-16">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-700 mb-6 border-l-4 border-blue-600 pl-3">Kondisi Ruang Kelas (Kabupaten)</h3>
                <div class="h-64">
                    <canvas id="sarprasChart"></canvas>
                </div>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-700 mb-6 border-l-4 border-green-500 pl-3">Tren Kehadiran GTK (6 Bulan)</h3>
                <div class="h-64">
                    <canvas id="kehadiranChart"></canvas>
                </div>
            </div>
        </div>

        
    </main>

    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-6 text-center">
            <p class="opacity-60 text-sm mb-2">&copy; 2026 Dinas Pendidikan Pemuda dan Olahraga Kabupaten Teluk Bintuni</p>
            <p class="text-xs opacity-40">Dikembangkan untuk kemajuan Pendidikan diatas Tanah Sisar Matiti</p>
        </div>
    </footer>

    <script>
        // Data Dummy untuk Chart Sarpras
        const sarprasCtx = document.getElementById('sarprasChart').getContext('2d');
        new Chart(sarprasCtx, {
            type: 'doughnut',
            data: {
                labels: ['Baik', 'Rusak Ringan', 'Rusak Sedang', 'Rusak Berat'],
                datasets: [{
                    data: [
                        {{ $kondisi['Baik'] ?? 0 }},
                        {{ $kondisi['Rusak Ringan'] ?? 0 }},
                        {{ $kondisi['Rusak Sedang'] ?? 0 }},
                        {{ $kondisi['Rusak Berat'] ?? 0 }}
                    ],
                    backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: { maintainAspectRatio: false }
        });

        // Data Dummy untuk Chart Kehadiran
        const kehadiranCtx = document.getElementById('kehadiranChart').getContext('2d');
        new Chart(kehadiranCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                    label: '% Kehadiran Rata-rata',
                    data: [92, 88, 95, 93, 90, 96],
                    borderColor: '#3b82f6',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(59, 130, 246, 0.1)'
                }]
            },
            options: { maintainAspectRatio: false }
        });
    </script>
</body>
</html>
