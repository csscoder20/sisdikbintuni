@php
    $lat = $get('latitude') ?? -2.12; 
    $lng = $get('longitude') ?? 133.33;
@endphp

<div 
    x-data="{
        lat: @js($lat),
        lng: @js($lng),
        map: null,
        marker: null,
        isSearching: false,
        init() {
            this.map = L.map($refs.map).setView([this.lat, this.lng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(this.map);

            this.marker = L.marker([this.lat, this.lng], {
                draggable: true
            }).addTo(this.map);

            this.marker.on('dragend', (event) => {
                let position = event.target.getLatLng();
                this.updateCoordinates(position.lat, position.lng);
            });

            // Watch for Livewire state changes
            this.$watch('$wire.data.latitude', (value) => {
                if (value && value != this.lat) {
                    this.lat = value;
                    this.updateMap();
                }
            });
            this.$watch('$wire.data.longitude', (value) => {
                if (value && value != this.lng) {
                    this.lng = value;
                    this.updateMap();
                }
            });
        },
        updateCoordinates(lat, lng) {
            this.lat = lat;
            this.lng = lng;
            $wire.set('data.latitude', lat);
            $wire.set('data.longitude', lng);
            this.updateMap();
        },
        updateMap() {
            if (this.map && this.marker) {
                const newPos = [this.lat, this.lng];
                this.marker.setLatLng(newPos);
                this.map.panTo(newPos);
            }
        },
        async searchAddress() {
            const alamat = $wire.get('data.alamat') || '';
            const desa = $wire.get('data.desa') || '';
            const kec = $wire.get('data.kecamatan') || '';
            
            if (!alamat && !desa && !kec) {
                alert('Silakan isi alamat, desa, atau kecamatan terlebih dahulu.');
                return;
            }

            this.isSearching = true;
            
            // Urutan pencarian: 
            // 1. Alamat Lengkap
            // 2. Desa + Kecamatan
            // 3. Kecamatan saja
            const queries = [];
            if (alamat && desa && kec) queries.push(`${alamat}, ${desa}, ${kec}, Teluk Bintuni, Papua Barat`);
            if (desa && kec) queries.push(`${desa}, ${kec}, Teluk Bintuni, Papua Barat`);
            if (kec) queries.push(`${kec}, Teluk Bintuni, Papua Barat`);
            
            let found = false;

            for (const query of queries) {
                try {
                    const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`);
                    const data = await response.json();
                    
                    if (data && data.length > 0) {
                        const result = data[0];
                        this.updateCoordinates(parseFloat(result.lat), parseFloat(result.lon));
                        found = true;
                        break; // Berhenti jika sudah ditemukan
                    }
                } catch (error) {
                    console.error('Geocoding attempt failed for:', query, error);
                }
            }

            if (!found) {
                alert('Lokasi tidak ditemukan secara otomatis. Silakan geser pin pada peta secara manual ke lokasi sekolah.');
            }
            
            this.isSearching = false;
        }
    }"
    wire:ignore
    class="w-full mt-4"
>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <div style="display: flex; flex-direction: column; gap: 4px; margin-bottom: 12px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end;">
            <div style="display: flex; flex-direction: column;">
                <label style="font-size: 0.875rem; font-weight: 700; color: #111827; text-transform: uppercase; letter-spacing: 0.025em;">Geotag Lokasi Sekolah</label>
                <span style="font-size: 10px; color: #6b7280;">Gunakan tombol di samping untuk sinkronisasi otomatis</span>
            </div>
            <button 
                type="button" 
                x-on:click="searchAddress"
                style="display: inline-flex; align-items: center; gap: 8px; px: 16px; py: 8px; background-color: #ea580c; color: white; font-size: 0.75rem; font-weight: 600; border-radius: 8px; border: none; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s; padding: 8px 16px;"
                onmouseover="this.style.backgroundColor='#c2410c'"
                onmouseout="this.style.backgroundColor='#ea580c'"
                :disabled="isSearching"
            >
                <template x-if="!isSearching">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span>Cari dari Alamat</span>
                    </div>
                </template>
                <template x-if="isSearching">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <svg class="animate-spin" style="width: 12px; height: 12px; color: white;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Mencari...</span>
                    </div>
                </template>
            </button>
        </div>
    </div>

    <div x-ref="map" style="height: 400px; width: 100%; border-radius: 0.75rem; border: 1px solid #e5e7eb;" class="shadow-sm"></div>
    <div class="mt-2 text-xs text-gray-500 italic flex justify-between">
        <span>* Anda dapat menggeser pin pada peta untuk menyesuaikan koordinat secara otomatis.</span>
        <span x-text="'Lat: ' + lat + ', Lng: ' + lng"></span>
    </div>
</div>
