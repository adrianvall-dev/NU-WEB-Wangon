@extends('layouts.admin')

@section('title', 'Tambah Agenda Kegiatan Baru')

@section('content')
<!-- Leaflet CSS for Map Picker -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

<style>
    #kegiatanMapPicker {
        height: 360px;
        width: 100%;
        border-radius: 14px;
        z-index: 1;
    }
    .leaflet-container {
        font-family: 'Plus Jakarta Sans', system-ui, sans-serif !important;
    }
    .search-results-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1050;
        background: #FFFFFF;
        border: 2px solid var(--brand-forest);
        border-radius: 12px;
        box-shadow: 4px 4px 0px var(--brand-forest);
        max-height: 220px;
        overflow-y: auto;
    }
    .search-result-item {
        padding: 9px 14px;
        border-bottom: 1.5px solid var(--brand-sand);
        cursor: pointer;
        transition: background 0.15s ease;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--brand-forest);
    }
    .search-result-item:last-child {
        border-bottom: none;
    }
    .search-result-item:hover {
        background: var(--brand-cream);
    }
</style>

<!-- HEADER BAR -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-2">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge-solid badge-solid-forest">
                <i class="bi bi-shield-check"></i> ADMIN PORTAL
            </span>
            <span class="text-muted fw-bold" style="font-size: 0.75rem;">•</span>
            <span class="text-muted fw-bold" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Agenda &amp; Jadwal</span>
        </div>
        <h1 class="fw-black font-serif text-safe mb-1" style="font-size: 1.85rem; color: var(--brand-forest); letter-spacing: -0.015em;">
            Tambah Kegiatan Baru
        </h1>
        <p class="text-muted text-safe mb-0" style="font-size: 0.86rem; font-weight: 500;">
            Jadwalkan agenda dakwah jam'iyyah, pengajian selapanan, atau rapat kerja.
        </p>
    </div>
    <div>
        <a href="{{ route('admin.kegiatan.index') }}" class="btn-solid-light btn-solid-press">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Daftar</span>
        </a>
    </div>
</div>

<div class="card-solid p-4 p-md-5 mb-5">
    @if ($errors->any())
        <div class="alert-solid-danger mb-4">
            <div class="d-flex align-items-center gap-2 fw-bold mb-2">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <span>Terdapat kesalahan pada formulir kegiatan:</span>
            </div>
            <ul class="mb-0 ps-4 fs-8">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.kegiatan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <div class="col-12">
                <label class="form-label-solid">
                    Judul Kegiatan / Nama Agenda <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       name="title" 
                       class="form-control-solid @error('title') is-invalid @enderror" 
                       placeholder="Contoh: Pengajian Akbar Peringatan Maulid Nabi..." 
                       value="{{ old('title') }}" 
                       required>
            </div>

            <div class="col-md-6">
                <label class="form-label-solid">
                    Penyelenggara / Kategori Banom <span class="text-danger">*</span>
                </label>
                <select name="kategori" class="form-select-solid @error('kategori') is-invalid @enderror" required>
                    @foreach(\App\Support\Organisasi::badgeLabelsWithUmum() as $kategoriKey => $kategoriName)
                        <option value="{{ $kategoriKey }}" {{ old('kategori', 'muslimat') === $kategoriKey ? 'selected' : '' }}>
                            {{ $kategoriName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label-solid">
                    Status Publikasi <span class="text-danger">*</span>
                </label>
                <select name="status" class="form-select-solid @error('status') is-invalid @enderror" required>
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft (Simpan Konsep)</option>
                    <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Published (Terbitkan di Jadwal Publik)</option>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label-solid">
                    Deskripsi Lengkap Kegiatan <span class="text-danger">*</span>
                </label>
                <textarea name="description" 
                          rows="5" 
                          class="form-control-solid @error('description') is-invalid @enderror" 
                          placeholder="Jelaskan susunan acara, pemateri, sasaran jamaah, atau petunjuk kehadiran..." 
                          required>{{ old('description') }}</textarea>
            </div>

            <div class="col-md-4">
                <label class="form-label-solid">
                    Tanggal Pelaksanaan <span class="text-danger">*</span>
                </label>
                <input type="date" 
                       name="event_date" 
                       class="form-control-solid @error('event_date') is-invalid @enderror" 
                       value="{{ old('event_date') }}" 
                       required>
            </div>

            <div class="col-md-4">
                <label class="form-label-solid">
                    Waktu Mulai <span class="text-danger">*</span>
                </label>
                <input type="time" 
                       name="start_time" 
                       class="form-control-solid @error('start_time') is-invalid @enderror" 
                       value="{{ old('start_time') }}" 
                       required>
            </div>

            <div class="col-md-4">
                <label class="form-label-solid">
                    Waktu Selesai <span class="text-muted fw-normal">(Opsional)</span>
                </label>
                <input type="time" 
                       name="end_time" 
                       class="form-control-solid @error('end_time') is-invalid @enderror" 
                       value="{{ old('end_time') }}">
            </div>

            <!-- MAP LOCATION PICKER (LEAFLET INTERACTIVE SELECTION) -->
            <div class="col-12">
                <div class="card-solid-cream p-3 p-md-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-3">
                        <div>
                            <label class="form-label-solid mb-1">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i> Titik Lokasi Peta &amp; Alamat Kegiatan <span class="text-danger">*</span>
                            </label>
                            <p class="text-muted fs-8 mb-0">Klik pada peta atau geser pin 📍 untuk menetapkan lokasi. Alamat akan terisi otomatis.</p>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <button type="button" class="btn-solid-light btn-solid-sm btn-solid-press" id="btnRecenterWangon">
                                <i class="bi bi-crosshair"></i> Pusat Wangon
                            </button>
                            <button type="button" class="btn-solid-light btn-solid-sm btn-solid-press" id="btnCurrentLocation">
                                <i class="bi bi-geo"></i> Lokasi Saya
                            </button>
                        </div>
                    </div>

                    <!-- Geocode Search Input -->
                    <div class="position-relative mb-3">
                        <div class="d-flex gap-2">
                            <div class="position-relative flex-grow-1">
                                <i class="bi bi-search position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                                <input type="text" id="mapSearchInput" class="form-control-solid ps-5" placeholder="Cari nama masjid, gedung, desa di Wangon...">
                            </div>
                            <button type="button" class="btn-solid-primary btn-solid-press text-nowrap" id="btnSearchLocation">
                                <span id="searchSpinner" class="spinner-border spinner-border-sm me-1 d-none"></span>
                                <span>Cari Titik</span>
                            </button>
                        </div>
                        <div id="searchResultsList" class="search-results-dropdown d-none"></div>
                    </div>

                    <!-- Interactive Map Viewport -->
                    <div class="position-relative rounded-3 overflow-hidden border-2 border-dark hard-shadow-sm mb-3" style="height: 360px;">
                        <div id="kegiatanMapPicker"></div>
                        <div class="position-absolute bottom-0 start-0 m-3 px-3 py-1.5 rounded-pill bg-dark text-white fw-bold" style="z-index: 1000; font-size: 0.72rem; pointer-events: none; opacity: 0.88;">
                            <i class="bi bi-hand-index-thumb me-1"></i> Klik peta untuk memindahkan pin lokasi
                        </div>
                    </div>

                    <!-- Address & Coordinates Box -->
                    <div class="p-3 card-solid bg-white">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-2">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="badge-solid badge-solid-forest">
                                    <i class="bi bi-pin-map-fill"></i> Koordinat Terpilih
                                </span>
                                <span id="reverseGeocodeLoading" class="text-muted fs-8 d-none">
                                    <span class="spinner-border spinner-border-sm text-forest me-1"></span> Mendeteksi alamat...
                                </span>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span id="coordBadge" class="badge-solid badge-solid-sand font-monospace" style="font-size: 0.72rem;">
                                    Lat: <span id="latDisplay">{{ old('latitude', '-') }}</span>, Lng: <span id="lngDisplay">{{ old('longitude', '-') }}</span>
                                </span>
                                <button type="button" class="btn-solid-yellow btn-solid-sm btn-solid-press" id="btnConfirmLocation">
                                    <i class="bi bi-check2-circle"></i> Kunci Lokasi
                                </button>
                            </div>
                        </div>

                        <!-- Location Textarea -->
                        <div class="mt-2">
                            <label class="form-label-solid" style="font-size: 0.74rem;">
                                Teks Alamat Lengkap / Patokan Gedung <span class="text-danger">*</span>
                            </label>
                            <textarea name="location" 
                                      id="inputLocation" 
                                      rows="2" 
                                      class="form-control-solid @error('location') is-invalid @enderror" 
                                      placeholder="Pilih lokasi pada peta atau ketik alamat spesifik di sini..." 
                                      required>{{ old('location') }}</textarea>
                            @error('location')
                                <div class="text-danger fs-8 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <input type="hidden" name="latitude" id="inputLatitude" value="{{ old('latitude') }}">
                        <input type="hidden" name="longitude" id="inputLongitude" value="{{ old('longitude') }}">
                    </div>
                </div>
            </div>

            <!-- POSTER IMAGE UPLOAD -->
            <div class="col-12">
                <label class="form-label-solid">
                    Gambar Poster / Pamflet Kegiatan (Opsional)
                </label>
                <div class="card-solid-cream p-4 text-center position-relative" style="border-style: dashed;">
                    <input type="file" 
                           name="image" 
                           id="imageInput" 
                           class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" 
                           accept="image/*">
                    <div id="uploadPlaceholder">
                        <div class="sidebar-user-avatar-solid mx-auto mb-2" style="width: 48px; height: 48px; background: #FFFFFF; color: var(--brand-forest);">
                            <i class="bi bi-image fs-4"></i>
                        </div>
                        <h6 class="fw-bold mb-1" style="color: var(--brand-forest); font-size: 0.92rem;">Pilih poster pamflet atau seret ke sini</h6>
                        <p class="text-muted fs-8 mb-0">Format JPG, PNG, WEBP maksimal 2MB</p>
                    </div>
                    <div id="imagePreviewContainer" class="d-none mt-2">
                        <img id="imagePreview" src="#" alt="Preview" class="rounded-3 border-2 border-dark" style="max-height: 180px; max-width: 100%; object-fit: cover;">
                        <div class="mt-2">
                            <span class="badge-solid badge-solid-forest" id="fileNameBadge">Poster Terpilih</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 pt-3 border-top border-2 border-dark d-flex flex-wrap align-items-center justify-content-between gap-3">
                <a href="{{ route('admin.kegiatan.index') }}" class="btn-solid-light btn-solid-press">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
                <button type="submit" class="btn-solid-primary btn-solid-press">
                    <i class="bi bi-check-circle-fill"></i> Simpan Agenda Kegiatan
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Image preview handler
        const imgInput = document.getElementById('imageInput');
        const imgPreviewContainer = document.getElementById('imagePreviewContainer');
        const imgPreview = document.getElementById('imagePreview');
        const imgPlaceholder = document.getElementById('uploadPlaceholder');
        const imgBadge = document.getElementById('fileNameBadge');

        if (imgInput) {
            imgInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imgPreview.src = e.target.result;
                        imgPreviewContainer.classList.remove('d-none');
                        imgPlaceholder.classList.add('d-none');
                        imgBadge.textContent = file.name;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        // ==========================================
        // LEAFLET MAP LOCATION PICKER ENGINE
        // ==========================================
        const defaultLat = -7.5186; // Wangon, Banyumas
        const defaultLng = 109.0521;

        const inputLat = document.getElementById('inputLatitude');
        const inputLng = document.getElementById('inputLongitude');
        const inputLoc = document.getElementById('inputLocation');
        const latDisplay = document.getElementById('latDisplay');
        const lngDisplay = document.getElementById('lngDisplay');
        const reverseLoading = document.getElementById('reverseGeocodeLoading');
        const btnConfirm = document.getElementById('btnConfirmLocation');
        const btnRecenter = document.getElementById('btnRecenterWangon');
        const btnCurrentLoc = document.getElementById('btnCurrentLocation');
        const searchInput = document.getElementById('mapSearchInput');
        const btnSearch = document.getElementById('btnSearchLocation');
        const searchSpinner = document.getElementById('searchSpinner');
        const searchResultsList = document.getElementById('searchResultsList');

        const initialLat = parseFloat(inputLat.value) || defaultLat;
        const initialLng = parseFloat(inputLng.value) || defaultLng;
        const hasInitialCoord = Boolean(inputLat.value && inputLng.value);

        // Initialize Map
        const map = L.map('kegiatanMapPicker').setView([initialLat, initialLng], hasInitialCoord ? 16 : 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        setTimeout(() => { map.invalidateSize(); }, 300);

        // Draggable Marker
        let marker = L.marker([initialLat, initialLng], {
            draggable: true
        }).addTo(map);

        function updateMarkerPosition(lat, lng, doReverseGeocode = true) {
            marker.setLatLng([lat, lng]);
            inputLat.value = lat.toFixed(6);
            inputLng.value = lng.toFixed(6);
            latDisplay.textContent = lat.toFixed(5);
            lngDisplay.textContent = lng.toFixed(5);

            if (doReverseGeocode) {
                reverseGeocode(lat, lng);
            }
        }

        function reverseGeocode(lat, lng) {
            if (reverseLoading) reverseLoading.classList.remove('d-none');

            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                .then(res => res.json())
                .then(data => {
                    if (data && data.display_name) {
                        inputLoc.value = data.display_name;
                    }
                })
                .catch(err => console.log('Geocoding error:', err))
                .finally(() => {
                    if (reverseLoading) reverseLoading.classList.add('d-none');
                });
        }

        map.on('click', function(e) {
            updateMarkerPosition(e.latlng.lat, e.latlng.lng, true);
        });

        marker.on('dragend', function() {
            const pos = marker.getLatLng();
            updateMarkerPosition(pos.lat, pos.lng, true);
        });

        if (btnRecenter) {
            btnRecenter.addEventListener('click', function() {
                map.setView([defaultLat, defaultLng], 15);
                updateMarkerPosition(defaultLat, defaultLng, true);
            });
        }

        if (btnCurrentLoc) {
            btnCurrentLoc.addEventListener('click', function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(pos) {
                        const userLat = pos.coords.latitude;
                        const userLng = pos.coords.longitude;
                        map.setView([userLat, userLng], 16);
                        updateMarkerPosition(userLat, userLng, true);
                    }, function() {
                        alert('Tidak dapat mendeteksi lokasi saat ini.');
                    });
                }
            });
        }

        if (btnConfirm) {
            btnConfirm.addEventListener('click', function() {
                const pos = marker.getLatLng();
                updateMarkerPosition(pos.lat, pos.lng, true);
                this.innerHTML = '<i class="bi bi-check-all"></i> Terkunci!';
                setTimeout(() => {
                    this.innerHTML = '<i class="bi bi-check2-circle"></i> Kunci Lokasi';
                }, 1500);
            });
        }

        // Search location via Nominatim API
        function doSearch() {
            const query = searchInput.value.trim();
            if (!query) return;

            searchSpinner.classList.remove('d-none');
            const searchParam = query.toLowerCase().includes('wangon') ? query : `${query}, Wangon, Banyumas`;

            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchParam)}&limit=5`)
                .then(res => res.json())
                .then(results => {
                    searchResultsList.innerHTML = '';
                    if (results && results.length > 0) {
                        results.forEach(item => {
                            const div = document.createElement('div');
                            div.className = 'search-result-item';
                            div.textContent = item.display_name;
                            div.addEventListener('click', function() {
                                const newLat = parseFloat(item.lat);
                                const newLng = parseFloat(item.lon);
                                map.setView([newLat, newLng], 16);
                                updateMarkerPosition(newLat, newLng, false);
                                inputLoc.value = item.display_name;
                                searchResultsList.classList.add('d-none');
                            });
                            searchResultsList.appendChild(div);
                        });
                        searchResultsList.classList.remove('d-none');
                    } else {
                        searchResultsList.innerHTML = '<div class="p-3 text-muted fs-8">Lokasi tidak ditemukan.</div>';
                        searchResultsList.classList.remove('d-none');
                    }
                })
                .catch(err => console.log('Search error:', err))
                .finally(() => {
                    searchSpinner.classList.add('d-none');
                });
        }

        if (btnSearch) btnSearch.addEventListener('click', doSearch);
        if (searchInput) {
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    doSearch();
                }
            });
        }
    });
</script>
@endsection