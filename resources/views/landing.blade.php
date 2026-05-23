<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISTEM PELAPORAN BULANAN SATUAN PENDIDIKAN - KABUPATEN TELUK BINTUNI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.49.1/dist/apexcharts.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        }

        .leaflet-control.legend {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            padding: 12px 14px;
            font-size: 12px;
            line-height: 1.4;
            max-height: 280px;
            overflow-y: auto;
            width: 220px;
        }

        .leaflet-control.legend .legend-title {
            font-weight: 700;
            margin-bottom: 8px;
            color: #111827;
        }

        .leaflet-control.legend .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 6px;
        }

        .leaflet-control.legend .legend-color-box {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            margin-right: 8px;
            border: 1px solid rgba(0, 0, 0, 0.12);
            flex-shrink: 0;
        }

        .leaflet-control.legend .legend-name {
            color: #374151;
            font-weight: 600;
        }

        /* Perbaikan untuk container grafik */
        #chart-gtk-status,
        #chart-gtk-pendidikan {
            min-height: 400px;
            width: 100%;
        }

        #map {
            background-color: #f8fafc;
        }

        .apexcharts-canvas {
            margin: 0 auto;
        }

        .h-87\.5 {
            height: 350px;
        }

        .h-175 {
            height: 800px;
        }

        div#legend-kecamatan {
            height: 370px;
            overflow-y: auto;
        }
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
            <p class="text-xl mb-8 opacity-90">Dinas Pendidikan, Kebudayaan, Pemuda dan Olahraga Kabupaten Teluk Bintuni
            </p>
            <div class="flex justify-center gap-4">
                <a target="_blank" href="/login"
                    class="bg-yellow-500 hover:bg-yellow-600 text-blue-900 font-bold py-3 px-8 rounded-full transition duration-300 shadow-lg">
                    <i class="fas fa-sign-in-alt mr-2"></i> LOGIN OPERATOR
                </a>
                <a href="#statistik"
                    class="bg-white/20 hover:bg-white/30 text-white font-semibold py-3 px-8 rounded-full transition duration-300 backdrop-blur-sm">
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
                <div class="text-red-500 text-xs font-semibold mt-2"><i class="fas fa-building mr-1"></i> SMA & SMK
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md border-b-4 border-blue-600">
                <div class="text-gray-500 text-xm font-bold uppercase">Total Siswa</div>
                <div class="text-3xl font-bold text-gray-800">{{ number_format($totalSiswa) }}</div>
                <div class="text-green-500 text-xs font-semibold mt-2"><i class="fas fa-user-grad mr-1"></i> Tersebar di
                    {{ number_format($totalSekolah) }} Sekolah</div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md border-b-4 border-green-500">
                <div class="text-gray-500 text-sm font-bold uppercase">Total GTK</div>
                <div class="text-3xl font-bold text-gray-800">{{ number_format($totalGtk) }}</div>
                <div class="text-gray-400 text-xs mt-2">Guru & Tenaga Kependidikan</div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md border-b-4 border-purple-500">
                @php
                    $kondisi = $kondisiRuang ?? [
                        'Baik' => 0,
                        'Rusak Ringan' => 0,
                        'Rusak Sedang' => 0,
                        'Rusak Berat' => 0,
                    ];
                    $totalRuang = array_sum($kondisi);
                    $layak = ($kondisi['Baik'] ?? 0) + ($kondisi['Rusak Ringan'] ?? 0);
                    $persentaseLayak = $totalRuang > 0 ? round(($layak / $totalRuang) * 100) : 0;
                @endphp
                <div class="text-gray-500 text-sm font-bold uppercase">Kondisi Ruang/Gedung</div>
                <div class="text-3xl font-bold text-gray-800">{{ $persentaseLayak }}%</div>
                <div class="text-blue-500 text-xs font-semibold mt-2">Kategori Layak ({{ $layak }} dari total
                    {{ $totalRuang }} ruang/gedung)</div>
            </div>
        </div>

        <!-- Area Grafik -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <h3 class="text-lg font-bold text-gray-700 mb-4 border-l-4 border-blue-500 pl-3">Jumlah GTK Berdasarkan
                    Status Kepegawaian</h3>
                <div id="chart-gtk-status"></div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <h3 class="text-lg font-bold text-gray-700 mb-4 border-l-4 border-green-500 pl-3">Jumlah Guru
                    Berdasarkan Pendidikan Terakhir</h3>
                <div id="chart-gtk-pendidikan"></div>
            </div>
        </div>

        <!-- Peta Sebaran Sekolah dengan Layout Sidebar -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 mb-16">
            <h3 class="text-lg font-bold text-gray-700 mb-6 border-l-4 border-purple-500 pl-3">Peta Sebaran Sekolah
            </h3>

            <div class="grid grid-cols-12 gap-6">
                <!-- Sidebar Kiri: Info Box & Legenda -->
                <div class="col-span-12 lg:col-span-2 flex flex-col gap-6">
                    <!-- Grid Info Box 2x2 -->
                    <div class="grid grid-cols-2 gap-3">
                        <div
                            class="bg-gradient from-blue-50 to-blue-100 p-4 rounded-lg border-l-4 border-blue-600 shadow-sm">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-building text-blue-600 text-base"></i>
                            </div>
                            <div class="text-lg font-bold text-blue-600">{{ number_format($totalSekolah) }}</div>
                            <div class="text-xs font-bold text-gray-700 uppercase mt-1">Total Sekolah</div>
                        </div>

                        <div
                            class="bg-gradient from-green-50 to-green-100 p-4 rounded-lg border-l-4 border-green-600 shadow-sm">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-users text-green-600 text-base"></i>
                            </div>
                            <div class="text-lg font-bold text-green-600">{{ number_format($totalSiswa) }}</div>
                            <div class="text-xs font-bold text-gray-700 uppercase mt-1">Total Siswa</div>
                        </div>

                        <div
                            class="bg-gradient from-purple-50 to-purple-100 p-4 rounded-lg border-l-4 border-purple-600 shadow-sm">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-chalkboard-user text-purple-600 text-base"></i>
                            </div>
                            <div class="text-lg font-bold text-purple-600">{{ number_format($totalGtk) }}</div>
                            <div class="text-xs font-bold text-gray-700 uppercase mt-1">Total GTK</div>
                        </div>

                        <div
                            class="bg-gradient from-orange-50 to-orange-100 p-4 rounded-lg border-l-4 border-orange-600 shadow-sm">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-map text-orange-600 text-base"></i>
                            </div>
                            <div class="text-lg font-bold text-orange-600">{{ count($sebaranSekolah) }}</div>
                            <div class="text-xs font-bold text-gray-700 uppercase mt-1">Distrik</div>
                        </div>
                    </div>

                    <!-- Legenda Peta -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-300 grow">
                        <div class="text-xs font-bold text-gray-800 mb-3 uppercase">LEGENDA</div>
                        <div class="space-y-2 mb-3 pb-3 border-b">
                            <div class="flex items-center gap-2">
                                <img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png"
                                    class="w-5 h-5" alt="Negeri">
                                <span class="text-xs text-gray-700">SMA Negeri</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-orange.png"
                                    class="w-5 h-5" alt="Swasta">
                                <span class="text-xs text-gray-700">Sekolah Swasta</span>
                            </div>
                        </div>
                        <div class="text-xs font-bold text-gray-800 mb-3 uppercase">Legenda Kecamatan</div>
                        <div id="legend-kecamatan" class="space-y-2 mb-3 text-xs text-gray-700"></div>
                    </div>
                </div>

                <!-- Peta Tengah -->
                <div class="col-span-12 lg:col-span-7">
                    <div class="relative bg-gray-100 rounded-lg overflow-hidden shadow-md" id="map-container">
                        <div id="map" class="w-full h-175 rounded-lg z-0 relative"></div>
                    </div>
                </div>

                <!-- Sidebar Kanan: Tabel Daftar Sekolah -->
                <div class="col-span-12 lg:col-span-3">
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                        <div class="bg-blue-600 text-white p-3">
                            <h4 class="text-sm font-bold uppercase">Daftar SMA/SMK</h4>
                        </div>
                        <div class="h-87.5 overflow-y-auto">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead class="sticky top-0 bg-gray-50 shadow-sm">
                                    <tr class="border-b">
                                        <th class="py-2 px-3 font-bold text-gray-700">No</th>
                                        <th class="py-2 px-3 font-bold text-gray-700">Nama Sekolah</th>
                                        <th class="py-2 px-3 font-bold text-gray-700">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700">
                                    @forelse($daftarSekolah as $index => $sekolah)
                                        <tr class="hover:bg-gray-50 border-b">
                                            <td class="py-2 px-3 font-semibold">{{ $index + 1 }}</td>
                                            <td class="py-2 px-3">{{ Str::limit($sekolah->nama, 20, '...') }}</td>
                                            <td class="py-2 px-3">
                                                @if (strtolower($sekolah->status_sekolah) == 'negeri')
                                                    <span class="text-green-700 font-bold text-xs">Negeri</span>
                                                @elseif(strtolower($sekolah->status_sekolah) == 'swasta')
                                                    <span class="text-orange-700 font-bold text-xs">Swasta</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-4 text-center text-gray-500">Belum ada data
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Sebaran Per Distrik -->
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm mt-6">
                        <div class="bg-blue-600 text-white p-3">
                            <h4 class="text-sm font-bold uppercase">Sebaran Per Distrik</h4>
                        </div>
                        <div class="h-80 overflow-y-auto">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead class="sticky top-0 bg-gray-50 shadow-sm">
                                    <tr class="border-b">
                                        <th class="py-2 px-3 font-bold text-gray-700">Distrik</th>
                                        <th class="py-2 px-3 font-bold text-gray-700 text-center">SMA</th>
                                        <th class="py-2 px-3 font-bold text-gray-700 text-center">SMK</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700">
                                    @forelse($sebaranSekolah as $sebaran)
                                        <tr class="hover:bg-gray-50 border-b">
                                            <td class="py-2 px-3">{{ Str::limit($sebaran->kecamatan, 15, '...') }}
                                            </td>
                                            <td class="py-2 px-3 text-center"><span
                                                    class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-bold">{{ $sebaran->sma }}</span>
                                            </td>
                                            <td class="py-2 px-3 text-center"><span
                                                    class="bg-orange-100 text-orange-800 px-2 py-1 rounded text-xs font-bold">{{ $sebaran->smk }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-4 text-center text-gray-500 text-xs">Belum
                                                ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-6 text-center">
            <p class="opacity-60 text-sm mb-2">&copy; 2026 Dinas Pendidikan Pemuda dan Olahraga Kabupaten Teluk Bintuni
            </p>
            <p class="text-xs opacity-40">Dikembangkan untuk kemajuan Pendidikan diatas Tanah Sisar Matiti</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi Peta - Titik Tengah diatur di sekitar Kabupaten Teluk Bintuni
            var map = L.map('map').setView([-1.8841, 133.3283], 8);

            // Fetch GeoJSON Kecamatan Teluk Bintuni untuk menampilkan setiap kecamatan dengan warna berbeda
            fetch('{{ asset('assets/geo/92.06_kecamatan.geojson') }}')
                .then(res => res.json())
                .then(geojson => {
                    var kecamatanColors = {};
                    var features = geojson.features || [];
                    var distinctNames = [];
                    features.forEach(function(feature) {
                        var name = feature.properties?.nm_kecamatan || feature.properties?.nama ||
                            feature.properties?.name || 'Unknown';
                        if (!distinctNames.includes(name)) {
                            distinctNames.push(name);
                        }
                    });
                    distinctNames.sort();

                    function getColorByKecamatan(name) {
                        if (!name) {
                            return '#6B7280';
                        }
                        if (!kecamatanColors[name]) {
                            var index = distinctNames.indexOf(name);
                            var hue = Math.round((index * 360) / Math.max(distinctNames.length, 1));
                            kecamatanColors[name] = 'hsl(' + hue + ', 66%, 60%)';
                        }
                        return kecamatanColors[name];
                    }

                    var geoLayer = L.geoJSON(geojson, {
                        style: function(feature) {
                            var kecamatanName = feature.properties?.nm_kecamatan || feature
                                .properties?.nama || feature.properties?.name;
                            var fillColor = getColorByKecamatan(kecamatanName);
                            return {
                                color: '#111827',
                                weight: 2,
                                opacity: 0.8,
                                fillColor: fillColor,
                                fillOpacity: 0.45
                            };
                        },
                        onEachFeature: function(feature, layer) {
                            var kecamatanName = feature.properties?.nm_kecamatan || feature
                                .properties?.nama || feature.properties?.name;
                            if (kecamatanName) {
                                layer.bindPopup(`<strong>${kecamatanName}</strong>`);
                            }
                        }
                    }).addTo(map);

                    var legendContainer = document.querySelector('#legend-kecamatan');
                    if (legendContainer) {
                        legendContainer.innerHTML = '';
                        distinctNames.forEach(function(kecamatanName) {
                            var color = getColorByKecamatan(kecamatanName);
                            var item = document.createElement('div');
                            item.className = 'flex items-center gap-2';
                            item.innerHTML =
                                '<span class="w-4 h-4 rounded-sm border border-slate-300" style="background:' +
                                color +
                                '; display: inline-block; min-width: 1rem; min-height: 1rem;"></span>' +
                                '<span class="text-xs text-gray-700">' + kecamatanName + '</span>';
                            legendContainer.appendChild(item);
                        });
                    }

                    var bounds = geoLayer.getBounds();
                    if (bounds.isValid()) {
                        map.fitBounds(bounds.pad(0.05));
                        map.setMaxBounds(bounds.pad(0.05));
                        map.setMinZoom(9);
                    }
                })
                .catch(error => {
                    console.error('Gagal memuat GeoJSON untuk bounds:', error);
                });

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
                if (school.latitude && school.longitude) {
                    var status = school.status_sekolah ? school.status_sekolah.toLowerCase() : '';
                    var markerIcon = greenIcon;

                    if (status === 'negeri') {
                        markerIcon = greenIcon;
                    } else if (status === 'swasta') {
                        markerIcon = orangeIcon;
                    }

                    var marker = L.marker([school.latitude, school.longitude], {
                        icon: markerIcon
                    }).addTo(map);

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

            // ========== PERBAIKAN GRAFIK PIE ==========
            // Render Chart Status Kepegawaian
            var statusData = @json($grafikGtkStatus);
            var statusLabels = [];
            var statusValues = [];

            if (statusData && statusData.length > 0) {
                for (var i = 0; i < statusData.length; i++) {
                    if (statusData[i].total > 0) {
                        statusLabels.push(statusData[i].status);
                        statusValues.push(statusData[i].total);
                    }
                }
            }

            if (statusLabels.length === 0) {
                statusLabels = ['Tidak ada data'];
                statusValues = [1];
            }

            var optionsStatus = {
                series: statusValues,
                chart: {
                    type: 'pie',
                    height: 380,
                    width: '100%'
                },
                labels: statusLabels,
                colors: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#06B6D4'],
                legend: {
                    position: 'bottom',
                    fontSize: '13px'
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val, opts) {
                        var name = opts.w.globals.labels[opts.seriesIndex];
                        var count = opts.w.globals.series[opts.seriesIndex];
                        return name + ' ' + count + ' (' + val.toFixed(1) + '%)';
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + ' orang';
                        }
                    }
                },
                plotOptions: {
                    pie: {
                        expandOnClick: true,
                        dataLabels: {
                            offset: -20
                        }
                    }
                }
            };

            // Render Chart Pendidikan
            var pendidikanData = @json($grafikGtkPendidikan);
            var pendidikanLabels = [];
            var pendidikanValues = [];

            if (pendidikanData && pendidikanData.length > 0) {
                for (var i = 0; i < pendidikanData.length; i++) {
                    if (pendidikanData[i].total > 0) {
                        pendidikanLabels.push(pendidikanData[i].pendidikan);
                        pendidikanValues.push(pendidikanData[i].total);
                    }
                }
            }

            if (pendidikanLabels.length === 0) {
                pendidikanLabels = ['Tidak ada data'];
                pendidikanValues = [1];
            }

            var optionsPendidikan = {
                series: pendidikanValues,
                chart: {
                    type: 'pie',
                    height: 380,
                    width: '100%'
                },
                labels: pendidikanLabels,
                colors: ['#06B6D4', '#8B5CF6', '#EC4899', '#F59E0B', '#10B981'],
                legend: {
                    position: 'bottom',
                    fontSize: '13px'
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val, opts) {
                        var name = opts.w.globals.labels[opts.seriesIndex];
                        var count = opts.w.globals.series[opts.seriesIndex];
                        return name + ' ' + count + ' (' + val.toFixed(1) + '%)';
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + ' orang';
                        }
                    }
                },
                plotOptions: {
                    pie: {
                        expandOnClick: true,
                        dataLabels: {
                            offset: -20
                        }
                    }
                }
            };

            // Render grafik
            try {
                var chartStatus = new ApexCharts(document.querySelector('#chart-gtk-status'), optionsStatus);
                chartStatus.render();
                console.log('Chart Status berhasil dirender');
            } catch (e) {
                console.error('Error chart status:', e);
            }

            try {
                var chartPendidikan = new ApexCharts(document.querySelector('#chart-gtk-pendidikan'),
                    optionsPendidikan);
                chartPendidikan.render();
                console.log('Chart Pendidikan berhasil dirender');
            } catch (e) {
                console.error('Error chart pendidikan:', e);
            }

            // --- Konfigurasi Grafik Sarpras per Sekolah ---
            var grafikSarprasData = @json($grafikSarprasSekolah);
            if (grafikSarprasData && grafikSarprasData.length > 0) {
                var categoriesSarpras = grafikSarprasData.map(function(item) {
                    return item.sekolah_nama;
                });
                var seriesSarpras = [{
                        name: 'Kondisi Baik',
                        data: grafikSarprasData.map(function(item) {
                            return item.baik;
                        })
                    },
                    {
                        name: 'Kondisi Rusak',
                        data: grafikSarprasData.map(function(item) {
                            return item.rusak;
                        })
                    }
                ];

                var chartHeightSarpras = Math.max(400, categoriesSarpras.length * 30);
                var sarprasContainer = document.querySelector("#chart-sarpras-sekolah");
                if (sarprasContainer) {
                    sarprasContainer.style.height = chartHeightSarpras + 'px';

                    var optionsSarpras = {
                        series: seriesSarpras,
                        chart: {
                            type: 'bar',
                            height: chartHeightSarpras,
                            stacked: true,
                            toolbar: {
                                show: true
                            }
                        },
                        plotOptions: {
                            bar: {
                                horizontal: true,
                                borderRadius: 2,
                                dataLabels: {
                                    total: {
                                        enabled: true,
                                        style: {
                                            fontSize: '13px',
                                            fontWeight: 900
                                        }
                                    }
                                }
                            },
                        },
                        colors: ['#10B981', '#EF4444'],
                        stroke: {
                            width: 1,
                            colors: ['#fff']
                        },
                        xaxis: {
                            categories: categoriesSarpras,
                            title: {
                                text: 'Jumlah Ruang/Gedung'
                            },
                            labels: {
                                formatter: function(val) {
                                    return val.toFixed(0);
                                }
                            }
                        },
                        yaxis: {
                            title: {
                                text: undefined
                            }
                        },
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            y: {
                                formatter: function(val) {
                                    return val + " ruang";
                                }
                            }
                        }
                    };
                    var chartSarpras = new ApexCharts(sarprasContainer, optionsSarpras);
                    chartSarpras.render();
                }
            }

            // --- Konfigurasi Grafik Siswa per Sekolah ---
            var grafikSiswaData = @json($grafikSiswaSekolah);
            if (grafikSiswaData && grafikSiswaData.length > 0) {
                var categoriesSiswa = grafikSiswaData.map(function(item) {
                    return item.sekolah_nama;
                });
                var seriesSiswa = [{
                        name: 'Laki-laki',
                        data: grafikSiswaData.map(function(item) {
                            return item.laki_laki;
                        })
                    },
                    {
                        name: 'Perempuan',
                        data: grafikSiswaData.map(function(item) {
                            return item.perempuan;
                        })
                    }
                ];

                var chartHeightSiswa = Math.max(400, categoriesSiswa.length * 30);
                var siswaContainer = document.querySelector("#chart-siswa-sekolah");
                if (siswaContainer) {
                    siswaContainer.style.height = chartHeightSiswa + 'px';

                    var optionsSiswa = {
                        series: seriesSiswa,
                        chart: {
                            type: 'bar',
                            height: chartHeightSiswa,
                            stacked: true,
                            toolbar: {
                                show: true
                            }
                        },
                        plotOptions: {
                            bar: {
                                horizontal: true,
                                borderRadius: 2,
                                dataLabels: {
                                    total: {
                                        enabled: true,
                                        style: {
                                            fontSize: '13px',
                                            fontWeight: 900
                                        }
                                    }
                                }
                            },
                        },
                        colors: ['#3B82F6', '#EC4899'],
                        stroke: {
                            width: 1,
                            colors: ['#fff']
                        },
                        xaxis: {
                            categories: categoriesSiswa,
                            title: {
                                text: 'Jumlah Siswa'
                            },
                            labels: {
                                formatter: function(val) {
                                    return val.toFixed(0);
                                }
                            }
                        },
                        yaxis: {
                            title: {
                                text: undefined
                            }
                        },
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            y: {
                                formatter: function(val) {
                                    return val + " orang";
                                }
                            }
                        }
                    };
                    var chartSiswa = new ApexCharts(siswaContainer, optionsSiswa);
                    chartSiswa.render();
                }
            }

            // Tambahkan GeoJSON Batas Kabupaten Teluk Bintuni
            fetch('/assets/geo/92.06_Teluk_Bintuni.geojson')
                .then(res => res.json())
                .then(geojson => {
                    L.geoJSON(geojson, {
                        interactive: false,
                        style: {
                            color: '#ecf0f6',
                            weight: 5,
                            fillOpacity: 0
                        }
                    }).addTo(map);
                    console.log('GeoJSON Bintuni berhasil dimuat');
                })
                .catch(error => {
                    console.error('Gagal memuat GeoJSON Bintuni:', error);
                });
        });
    </script>
</body>

</html>
