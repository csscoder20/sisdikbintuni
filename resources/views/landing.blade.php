<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISTEM PELAPORAN BULANAN SATUAN PENDIDIKAN - KABUPATEN TELUK BINTUNI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Sistem Pelaporan Bulanan Satuan Pendidikan</h1>
            <p class="text-xl mb-8 opacity-90">Dinas Pendidikan, Kebudayaan, Pemuda dan Olahraga Kabupaten Teluk Bintuni</p>
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
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
        </div>

        <!-- Area Grafik -->

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <h3 class="text-lg font-bold text-gray-700 mb-4 border-l-4 border-blue-500 pl-3">Jumlah GTK Berdasarkan Status Kepegawaian</h3>
                <div id="chart-gtk-status" class="w-full flex justify-center"></div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <h3 class="text-lg font-bold text-gray-700 mb-4 border-l-4 border-green-500 pl-3">Jumlah Guru Berdasarkan Pendidikan Terakhir</h3>
                <div id="chart-gtk-pendidikan" class="w-full flex justify-center"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-16" id="statistik">
            <!-- Tabel Sebaran Sekolah -->
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <h3 class="text-lg font-bold text-gray-700 mb-4 border-l-4 border-blue-600 pl-3">Sebaran Sekolah per Distrik</h3>
                <div class="overflow-x-auto h-96">
                    <table class="w-full text-left border-collapse">
                        <thead class="sticky top-0 bg-white shadow-sm">
                            <tr class="bg-gray-50 text-gray-600 text-sm uppercase">
                                <th class="py-3 px-4 border-b">No</th>
                                <th class="py-3 px-4 border-b">Distrik / Kecamatan</th>
                                <th class="py-3 px-4 border-b text-center">SMA</th>
                                <th class="py-3 px-4 border-b text-center">SMK</th>
                                <th class="py-3 px-4 border-b text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm">
                            @forelse($sebaranSekolah as $index => $sebaran)
                            <tr class="hover:bg-gray-50 border-b last:border-b-0">
                                <td class="py-3 px-4">{{ $index + 1 }}</td>
                                <td class="py-3 px-4">{{ $sebaran->kecamatan ?: 'Tidak Diketahui' }}</td>
                                <td class="py-3 px-4 text-center">
                                    <span class="text-blue-600 font-bold">{{ $sebaran->sma }}</span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="text-purple-600 font-bold">{{ $sebaran->smk }}</span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="bg-blue-100 text-blue-800 py-1 px-3 rounded-full font-bold">{{ $sebaran->total }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center text-gray-500">Belum ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabel Daftar Sekolah -->
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <h3 class="text-lg font-bold text-gray-700 mb-4 border-l-4 border-green-500 pl-3">Daftar Sekolah SMA/SMK</h3>
                <div class="overflow-x-auto h-96">
                    <table class="w-full text-left border-collapse">
                        <thead class="sticky top-0 bg-white shadow-sm">
                            <tr class="bg-gray-50 text-gray-600 text-sm uppercase">
                                <th class="py-3 px-4 border-b">No</th>
                                <th class="py-3 px-4 border-b">Nama Sekolah</th>
                                <th class="py-3 px-4 border-b">Status</th>
                                <th class="py-3 px-4 border-b">Kecamatan</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm">
                            @forelse($daftarSekolah as $index => $sekolah)
                            <tr class="hover:bg-gray-50 border-b last:border-b-0">
                                <td class="py-3 px-4">{{ $index + 1 }}</td>
                                <td class="py-3 px-4 font-semibold text-gray-800">{{ $sekolah->nama }}</td>
                                <td class="py-3 px-4">
                                    @if(strtolower($sekolah->status_sekolah) == 'negeri')
                                        <span class="bg-green-100 text-green-800 py-1 px-3 rounded-full text-xs font-bold uppercase">Negeri</span>
                                    @elseif(strtolower($sekolah->status_sekolah) == 'swasta')
                                        <span class="bg-orange-100 text-orange-800 py-1 px-3 rounded-full text-xs font-bold uppercase">Swasta</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-3 px-4">{{ $sekolah->kecamatan ?: '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-500">Belum ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Peta Sebaran Sekolah -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 mb-16">
            <h3 class="text-lg font-bold text-gray-700 mb-4 border-l-4 border-purple-500 pl-3">Peta Sebaran Sekolah</h3>
            <div id="map" class="w-full h-[500px] rounded-lg z-0 relative"></div>
        </div>

    </main>

    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-6 text-center">
            <p class="opacity-60 text-sm mb-2">&copy; 2026 Dinas Pendidikan Pemuda dan Olahraga Kabupaten Teluk Bintuni</p>
            <p class="text-xs opacity-40">Dikembangkan untuk kemajuan Pendidikan diatas Tanah Sisar Matiti</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi Peta - Titik Tengah diatur di sekitar Kabupaten Teluk Bintuni
            var map = L.map('map').setView([-1.8841, 133.3283], 8);

            // Tambahkan Tile Layer dari OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Definisi Custom Icon Marker Leaflet
            var greenIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            var orangeIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-orange.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            // Render Data sekolah dari backend ke dalam JS
            var schools = @json($daftarSekolah);

            schools.forEach(function(school) {
                // Asumsikan di tabel schools ada field 'latitude' dan 'longitude'
                if (school.latitude && school.longitude) {
                    var status = school.status_sekolah ? school.status_sekolah.toLowerCase() : '';
                    var markerIcon = greenIcon; // Default icon
                    
                    if (status === 'negeri') {
                        markerIcon = greenIcon;
                    } else if (status === 'swasta') {
                        markerIcon = orangeIcon;
                    }

                    var marker = L.marker([school.latitude, school.longitude], {icon: markerIcon}).addTo(map);
                    
                    var popupContent = `
                        <div class="p-1 min-w-[220px]">
                            <h4 class="font-bold text-blue-800 text-[15px] mb-2 border-b pb-1 leading-tight">${school.nama}</h4>
                            <div class="space-y-1 mb-3">
                                <p class="text-[13px] text-gray-700 m-0"><strong>NPSN:</strong> ${school.npsn || '-'}</p>
                                <p class="text-[13px] text-gray-700 m-0"><strong>Status:</strong> ${school.status_sekolah ? school.status_sekolah.toUpperCase() : '-'}</p>
                                <p class="text-[13px] text-gray-700 m-0"><strong>Akreditasi:</strong> ${school.akreditasi || '-'}</p>
                                <p class="text-[13px] text-gray-700 m-0 mt-2 pt-1 border-t"><strong>Alamat:</strong> ${school.alamat || '-'}</p>
                                <p class="text-[13px] text-gray-700 m-0"><strong>Desa:</strong> ${school.desa || '-'}</p>
                                <p class="text-[13px] text-gray-700 m-0"><strong>Kecamatan:</strong> ${school.kecamatan || '-'}</p>
                            </div>
                            <a href="https://www.google.com/maps/dir/?api=1&destination=${school.latitude},${school.longitude}" target="_blank" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white text-[13px] font-bold py-2 px-3 rounded-md transition duration-200 !text-white !no-underline shadow-sm">
                                <i class="fas fa-directions mr-1"></i> Petunjuk Arah
                            </a>
                        </div>
                    `;
                    marker.bindPopup(popupContent);
                }
            });

            // --- Konfigurasi Grafik GTK berdasarkan Status ---
            var grafikGtkStatusData = @json($grafikGtkStatus);
            var optionsGtkStatus = {
                series: grafikGtkStatusData.map(function(item) { return item.total; }),
                labels: grafikGtkStatusData.map(function(item) { return item.status; }),
                chart: {
                    type: 'pie',
                    height: 350
                },
                colors: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#64748B'],
                legend: {
                    position: 'bottom'
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val, opts) {
                        return opts.w.config.series[opts.seriesIndex] + " (" + val.toFixed(1) + "%)";
                    }
                }
            };
            var chartGtkStatus = new ApexCharts(document.querySelector("#chart-gtk-status"), optionsGtkStatus);
            chartGtkStatus.render();

            // --- Konfigurasi Grafik GTK berdasarkan Pendidikan ---
            var grafikGtkPendidikanData = @json($grafikGtkPendidikan);
            var optionsGtkPendidikan = {
                series: grafikGtkPendidikanData.map(function(item) { return item.total; }),
                labels: grafikGtkPendidikanData.map(function(item) { return item.pendidikan; }),
                chart: {
                    type: 'pie',
                    height: 350
                },
                colors: ['#F59E0B', '#3B82F6', '#10B981', '#8B5CF6'],
                legend: {
                    position: 'bottom'
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val, opts) {
                        return opts.w.config.series[opts.seriesIndex] + " (" + val.toFixed(1) + "%)";
                    }
                }
            };
            var chartGtkPendidikan = new ApexCharts(document.querySelector("#chart-gtk-pendidikan"), optionsGtkPendidikan);
            chartGtkPendidikan.render();
        });
    </script>
</body>
</html>
