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
            const alamat = $wire.get('data.alamat');
            const desa = $wire.get('data.desa');
            const kec = $wire.get('data.kecamatan');
            
            if (!alamat && !desa && !kec) {
                alert('Silakan isi alamat, desa, atau kecamatan terlebih dahulu.');
                return;
            }

            this.isSearching = true;
            const query = `${alamat}, ${desa}, ${kec}, Teluk Bintuni, Papua Barat, Indonesia`;
            
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`);
                const data = await response.json();
                
                if (data && data.length > 0) {
                    const result = data[0];
                    this.updateCoordinates(parseFloat(result.lat), parseFloat(result.lon));
                } else {
                    alert('Lokasi tidak ditemukan. Silakan masukkan koordinat secara manual atau sesuaikan alamat.');
                }
            } catch (error) {
                console.error('Geocoding error:', error);
                alert('Terjadi kesalahan saat mencari lokasi.');
            } finally {
                this.isSearching = false;
            }
        }
    }"
    wire:ignore
    class="w-full mt-4"
>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <div class="flex justify-between items-center mb-2">
        <label class="text-sm font-medium text-gray-700">Geotag Lokasi</label>
        <button 
            type="button" 
            x-on:click="searchAddress"
            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50"
            :disabled="isSearching"
        >
            <template x-if="!isSearching">
                <span>Cari dari Alamat</span>
            </template>
            <template x-if="isSearching">
                <span>Mencari...</span>
            </template>
        </button>
    </div>

    <div x-ref="map" style="height: 400px; width: 100%; border-radius: 0.75rem; border: 1px solid #e5e7eb;" class="shadow-sm"></div>
    <div class="mt-2 text-xs text-gray-500 italic flex justify-between">
        <span>* Anda dapat menggeser pin pada peta untuk menyesuaikan koordinat secara otomatis.</span>
        <span x-text="'Lat: ' + lat + ', Lng: ' + lng"></span>
    </div>
</div>
