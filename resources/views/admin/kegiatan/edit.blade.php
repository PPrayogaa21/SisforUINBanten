@extends('layouts.app')
@section('title', 'Edit Kegiatan')
@section('page-title', 'Edit Kegiatan')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<style>
    #map-picker { height: 350px; border-radius: 0.75rem; z-index: 10; }
    .leaflet-control-geocoder { box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1); border-radius: 0.5rem; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto">
    <form method="POST" action="{{ route('admin.kegiatan.update', $kegiatan) }}" class="space-y-6">
        @csrf @method('PUT')

        <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm space-y-5">
            <h3 class="font-semibold text-slate-800 text-lg flex items-center gap-2"><i class="fas fa-info-circle text-emerald-500"></i> Informasi Kegiatan</h3>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Kegiatan *</label>
                <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm">
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Jenis *</label>
                    <select name="jenis" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm">
                        @foreach(['rapat','seminar','pelatihan','workshop','lainnya'] as $j)
                        <option value="{{ $j }}" {{ $kegiatan->jenis == $j ? 'selected' : '' }}>{{ ucfirst($j) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Status *</label>
                    <select name="status" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm">
                        @foreach(['draft','published','ongoing','completed','cancelled'] as $s)
                        <option value="{{ $s }}" {{ $kegiatan->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi</label>
                <textarea name="deskripsi" rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm resize-none">{{ old('deskripsi', $kegiatan->deskripsi) }}</textarea>
            </div>
        </div>

        <div class="p-6 rounded-2xl bg-white border border-slate-200/50 shadow-sm space-y-5">
            <h3 class="font-semibold text-slate-800 text-lg flex items-center gap-2"><i class="fas fa-clock text-blue-500"></i> Waktu & Tempat</h3>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Waktu Mulai *</label>
                    <input type="datetime-local" name="waktu_mulai" value="{{ old('waktu_mulai', $kegiatan->waktu_mulai->format('Y-m-d\TH:i')) }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Waktu Selesai *</label>
                    <input type="datetime-local" name="waktu_selesai" value="{{ old('waktu_selesai', $kegiatan->waktu_selesai->format('Y-m-d\TH:i')) }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Tempat *</label>
                <input type="text" name="tempat" value="{{ old('tempat', $kegiatan->tempat) }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm">
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Alamat Lengkap</label>
                    <textarea name="alamat_lengkap" id="alamat_lengkap" rows="2" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-400/50 text-sm resize-none" placeholder="Alamat lengkap lokasi kegiatan" readonly>{{ old('alamat_lengkap', $kegiatan->alamat_lengkap) }}</textarea>
                    <p class="text-xs text-slate-500 mt-1">Alamat otomatis terisi berdasarkan titik lokasi di peta.</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Latitude</label>
                        <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude', $kegiatan->latitude) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-500 text-sm focus:outline-none" placeholder="-6.9175" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Longitude</label>
                        <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude', $kegiatan->longitude) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-500 text-sm focus:outline-none" placeholder="107.6191" readonly>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-slate-700 mb-1.5 flex items-center gap-2">
                    <i class="fas fa-map-location-dot text-emerald-500"></i> Cari Titik Lokasi
                </label>
                <div class="border border-slate-200 rounded-xl p-1 bg-white">
                    <div id="map-picker"></div>
                </div>
                <p class="text-xs text-slate-500 mt-2"><i class="fas fa-info-circle"></i> Gunakan kotak pencarian pada peta atau geser marker untuk menentukan lokasi secara presisi.</p>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.kegiatan.show', $kegiatan) }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-medium text-sm hover:bg-slate-50 transition-colors">Batal</a>
            <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-medium text-sm shadow-lg shadow-emerald-500/20 transition-all">
                <i class="fas fa-save mr-2"></i> Update Kegiatan
            </button>
        </div>
    </form>
</div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Default to Jakarta
        const defaultLat = -6.2088;
        const defaultLng = 106.8456;
        
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        const addressInput = document.getElementById('alamat_lengkap');

        let initLat = latInput.value ? parseFloat(latInput.value) : defaultLat;
        let initLng = lngInput.value ? parseFloat(lngInput.value) : defaultLng;

        const map = L.map('map-picker').setView([initLat, initLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        const marker = L.marker([initLat, initLng], { draggable: true }).addTo(map);

        // Geocoder Control for Searching
        const geocoder = L.Control.geocoder({
            defaultMarkGeocode: false,
            placeholder: "Cari lokasi / alamat..."
        })
        .on('markgeocode', function(e) {
            const bbox = e.geocode.bbox;
            const center = e.geocode.center;
            map.fitBounds(bbox);
            marker.setLatLng(center);
            updateLocationInfo(center.lat, center.lng, e.geocode.name);
        })
        .addTo(map);

        // Update when marker is dragged
        marker.on('dragend', function(e) {
            const pos = marker.getLatLng();
            reverseGeocode(pos.lat, pos.lng);
        });

        // Update when map is clicked
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            reverseGeocode(e.latlng.lat, e.latlng.lng);
        });

        function reverseGeocode(lat, lng) {
            latInput.value = lat;
            lngInput.value = lng;
            
            // Use Nominatim API for reverse geocoding
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.display_name) {
                        addressInput.value = data.display_name;
                    }
                })
                .catch(err => console.error("Geocoding failed:", err));
        }

        function updateLocationInfo(lat, lng, address) {
            latInput.value = lat;
            lngInput.value = lng;
            addressInput.value = address;
        }

        // If there's no initial value, try to get user's location
        if (!latInput.value && navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                map.setView(pos, 15);
                marker.setLatLng(pos);
                reverseGeocode(pos.lat, pos.lng);
            });
        }
    });
</script>
@endpush
@endsection
