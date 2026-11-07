@extends('layouts.admin')

@section('title', 'Pusat Layanan & Sekretariat - Admin Portal')

@push('styles')
<style>
    /* RESPONSIVE CARD PADDING */
    .card-solid-responsive {
        background: #FFFFFF;
        border: 2px solid var(--brand-forest);
        box-shadow: 5px 5px 0px var(--brand-forest);
        box-sizing: border-box;
    }
    @media (max-width: 767.98px) {
        .card-solid-responsive {
            padding: 16px !important;
            border-radius: 16px !important;
            box-shadow: 3px 3px 0px var(--brand-forest) !important;
        }
        .form-control-solid,
        .form-select-solid {
            font-size: 16px !important; /* Prevents auto-zoom on iOS */
            padding: 10px 12px !important;
            min-height: 44px; /* Accessible touch target */
        }
        .btn-solid-primary,
        .btn-solid-light,
        .btn-solid-danger {
            min-height: 44px;
        }
        .btn-solid-sm {
            min-height: 38px;
        }
    }
    @media (min-width: 768px) {
        .card-solid-responsive {
            padding: 24px !important;
            border-radius: 20px !important;
        }
    }

    /* QUICK HORIZONTAL SCROLL NAV ON MOBILE */
    .quick-nav-scroll-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
        max-width: 100%;
    }
    .quick-nav-scroll-wrapper::-webkit-scrollbar {
        display: none;
    }

    /* RESPONSIVE TABLE FOR SECTION 05 */
    @media (max-width: 767.98px) {
        .table-cards-responsive,
        .table-cards-responsive tbody {
            display: block !important;
            width: 100% !important;
        }
        .table-cards-responsive thead {
            display: none !important;
        }
        .table-cards-responsive tr.card-row-item {
            display: grid !important;
            grid-template-columns: 1fr 1fr auto !important;
            gap: 10px !important;
            background-color: var(--brand-cream) !important;
            border: 2px solid var(--brand-forest) !important;
            border-radius: 14px !important;
            padding: 14px !important;
            margin-bottom: 12px !important;
            box-shadow: 3px 3px 0px var(--brand-forest) !important;
        }
        .table-cards-responsive tr.card-row-item .card-col-judul {
            grid-column: 1 / -1 !important;
            padding: 0 !important;
            border: none !important;
            background: transparent !important;
        }
        .table-cards-responsive tr.card-row-item .card-col-deskripsi {
            grid-column: 1 / -1 !important;
            padding: 0 !important;
            border: none !important;
            background: transparent !important;
        }
        .table-cards-responsive tr.card-row-item .card-col-urutan {
            grid-column: 1 !important;
            padding: 0 !important;
            border: none !important;
            background: transparent !important;
            text-align: left !important;
        }
        .table-cards-responsive tr.card-row-item .card-col-status {
            grid-column: 2 !important;
            padding: 0 !important;
            border: none !important;
            background: transparent !important;
            text-align: center !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: flex-end !important;
        }
        .table-cards-responsive tr.card-row-item .card-col-aksi {
            grid-column: 3 !important;
            padding: 0 !important;
            border: none !important;
            background: transparent !important;
            text-align: right !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: flex-end !important;
            justify-content: flex-end !important;
        }
    }
</style>
@endpush

@section('content')
<div class="space-y-4">

    <!-- HEADER TITLE & ACTIONS -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-3">
        <div class="min-w-0 w-100 w-md-auto">
            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                <span class="badge-solid badge-solid-forest text-[11px]">PENGATURAN WEBSITE</span>
                <span class="text-xs text-[#2D4736] font-bold uppercase tracking-wider">• SEKRETARIAT &amp; LAYANAN</span>
            </div>
            <h1 class="h2 fw-extrabold text-[#142E25] tracking-tight mb-1 text-safe" style="font-size: clamp(1.4rem, 4vw, 1.85rem);">
                Pusat Layanan &amp; Sekretariat
            </h1>
            <p class="text-[#2D4736] text-sm mb-0 text-safe">
                Kelola identitas kantor, kontak resmi, tautan navigasi maps, jam kerja, dan kartu layanan.
            </p>
        </div>
        <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-2 w-100 w-md-auto mt-2 mt-md-0">
            <button type="button" class="btn-solid-light btn-solid-press justify-content-center" data-bs-toggle="collapse" data-bs-target="#livePreviewCollapse" aria-expanded="false" aria-controls="livePreviewCollapse">
                <i class="bi bi-eye me-1"></i>
                <span>Pratinjau Tampilan</span>
            </button>
            <a href="{{ route('home') }}#lokasi" target="_blank" class="btn-solid-light btn-solid-press justify-content-center">
                <i class="bi bi-box-arrow-up-right me-1"></i>
                <span>Lihat di Website</span>
            </a>
            <button type="submit" form="formPengaturanSekretariat" class="btn-solid-primary btn-solid-press justify-content-center">
                <i class="bi bi-check2-circle fs-6 me-1"></i>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </div>

    <!-- QUICK SECTION NAVIGATION (SWIPEABLE HORIZONTALLY ON MOBILE) -->
    <div class="quick-nav-scroll-wrapper mb-3">
        <div class="d-flex align-items-center gap-2 pb-1" style="min-width: max-content;">
            <span class="text-xs text-[#2D4736] font-bold text-uppercase tracking-wider me-1 d-none d-md-inline">Lompat:</span>
            <a href="#kelompok-01" class="badge-solid badge-solid-light text-decoration-none">01. Informasi Kantor</a>
            <a href="#kelompok-02" class="badge-solid badge-solid-light text-decoration-none">02. Kontak Sekretariat</a>
            <a href="#kelompok-03" class="badge-solid badge-solid-light text-decoration-none">03. Petunjuk Arah</a>
            <a href="#kelompok-04" class="badge-solid badge-solid-light text-decoration-none">04. Jam Operasional</a>
            <a href="#kelompok-05" class="badge-solid badge-solid-light text-decoration-none">05. Kartu Layanan</a>
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    @if(session('success'))
        <div class="card-solid-responsive p-3 mb-4 d-flex align-items-center justify-content-between" style="background-color: var(--brand-matcha);">
            <div class="d-flex align-items-center gap-2 font-bold text-[#142E25]">
                <i class="bi bi-check-circle-fill fs-5 text-[#16A34A] flex-shrink-0"></i>
                <span class="text-safe">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close flex-shrink-0" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="card-solid-responsive p-3 mb-4" style="background-color: #FEE2E2; border-color: #EF4444;">
            <div class="d-flex align-items-center gap-2 font-bold text-[#991B1B] mb-2">
                <i class="bi bi-exclamation-triangle-fill fs-5 flex-shrink-0"></i>
                <span class="text-safe">Terdapat kesalahan pada pengisian formulir:</span>
            </div>
            <ul class="mb-0 text-xs font-semibold text-[#991B1B] ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- OPTIONAL COLLAPSIBLE LIVE PREVIEW -->
    <div class="collapse mb-4" id="livePreviewCollapse">
        <div class="card-solid-responsive p-3 p-md-4">
            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge-solid badge-solid-forest text-xs">PRATINJAU</span>
                    <h5 class="fw-extrabold text-[#142E25] uppercase tracking-wider mb-0 text-sm">Pratinjau Tampilan Publik</h5>
                </div>
                <button type="button" class="btn-close" data-bs-toggle="collapse" data-bs-target="#livePreviewCollapse" aria-label="Close"></button>
            </div>

            <!-- SIMULATED PUBLIC CARD -->
            <div class="p-3 p-md-4 rounded-xl" style="background-color: var(--brand-cream); border: 2px solid var(--brand-forest); box-shadow: 3px 3px 0px var(--brand-forest); border-radius: 16px; overflow: hidden; max-width: 100%;">
                <div class="row g-4 align-items-center">
                    <div class="col-12 col-lg-8 min-w-0">
                        <span id="previewLabelSection" class="badge-solid badge-solid-matcha text-xs mb-2">
                            {{ $pengaturan->label_section ?? 'PUSAT LAYANAN & SEKRETARIAT' }}
                        </span>
                        <h3 id="previewNamaKantor" class="fw-extrabold text-[#142E25] tracking-tight mb-2 text-safe" style="font-size: clamp(1.2rem, 3.5vw, 1.45rem);">
                            {{ $pengaturan->nama_kantor ?? 'Kantor MWC Nahdlatul Ulama Wangon' }}
                        </h3>
                        <p id="previewAlamatLengkap" class="text-sm font-semibold mb-3 leading-relaxed text-[#2D4736] text-safe" style="max-width: 650px;">
                            {{ $pengaturan->alamat_lengkap_formatted }}
                        </p>

                        <!-- PREVIEW SERVICE CARDS -->
                        <div class="row g-2 pt-1" id="previewCardsContainer">
                            @forelse($kartuLayanans->where('is_active', true) as $k)
                                <div class="col-12 col-sm-4">
                                    <div class="p-2 p-md-3 bg-white" style="border: 1.5px solid var(--brand-forest); border-radius: 12px; box-shadow: 2px 2px 0px var(--brand-forest);">
                                        <span class="d-block font-extrabold text-uppercase mb-1 text-[#2D4736]" style="font-size: 10px; letter-spacing: 0.05em;">{{ $k->judul }}</span>
                                        <span class="d-block fw-bold text-[#142E25] text-truncate" style="font-size: 12px;">{{ $k->deskripsi }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 col-sm-4">
                                    <div class="p-2 p-md-3 bg-white" style="border: 1.5px solid var(--brand-forest); border-radius: 12px; box-shadow: 2px 2px 0px var(--brand-forest);">
                                        <span class="d-block font-extrabold text-uppercase mb-1 text-[#2D4736]" style="font-size: 10px; letter-spacing: 0.05em;">Jam Operasional</span>
                                        <span class="d-block fw-bold text-[#142E25] text-truncate" style="font-size: 12px;">{{ $pengaturan->jam_operasional_formatted }}</span>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="col-12 col-lg-4">
                        <div class="d-flex flex-column gap-2">
                            <div class="btn-solid-primary justify-content-center text-center py-2" style="cursor: default; pointer-events: none;">
                                <i class="bi bi-geo-alt-fill me-1"></i>
                                <span>Buka Petunjuk Arah Maps</span>
                            </div>
                            <div class="btn-solid-light justify-content-center text-center py-2" style="cursor: default; pointer-events: none;">
                                <i class="bi bi-whatsapp me-1"></i>
                                <span>Hubungi Sekretariat</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN FORM WRAPPER -->
    <form action="{{ route('admin.pengaturan.sekretariat.update') }}" method="POST" id="formPengaturanSekretariat">
        @csrf
        @method('PUT')

        <!-- ========================================================
             01 — INFORMASI KANTOR
             ======================================================== -->
        <div class="card-solid-responsive mb-4" id="kelompok-01">
            <div class="pb-3 mb-3 border-bottom" style="border-bottom: 1.5px solid var(--brand-sand) !important;">
                <h2 class="h5 fw-extrabold text-[#142E25] mb-1 tracking-tight">01 — Informasi Kantor</h2>
                <p class="text-xs text-[#2D4736] mb-0">Nama gedung, identitas section, dan rincian alamat wilayah administratif.</p>
            </div>

            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label for="inputLabelSection" class="form-label-solid">
                        Label Section <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="label_section" id="inputLabelSection" 
                           class="form-control form-control-solid @error('label_section') is-invalid @enderror" 
                           value="{{ old('label_section', $pengaturan->label_section ?? 'PUSAT LAYANAN & SEKRETARIAT') }}" 
                           placeholder="Contoh: PUSAT LAYANAN &amp; SEKRETARIAT" required>
                    @error('label_section')
                        <div class="text-xs text-danger font-semibold mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-8">
                    <label for="inputNamaKantor" class="form-label-solid">
                        Nama Kantor / Gedung <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="nama_kantor" id="inputNamaKantor" 
                           class="form-control form-control-solid @error('nama_kantor') is-invalid @enderror" 
                           value="{{ old('nama_kantor', $pengaturan->nama_kantor ?? 'Kantor MWC Nahdlatul Ulama Wangon') }}" 
                           placeholder="Contoh: Kantor MWC Nahdlatul Ulama Wangon" required>
                    @error('nama_kantor')
                        <div class="text-xs text-danger font-semibold mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="inputAlamat" class="form-label-solid">
                        Alamat Lengkap <span class="text-danger">*</span>
                    </label>
                    <textarea name="alamat" id="inputAlamat" rows="2" 
                              class="form-control form-control-solid @error('alamat') is-invalid @enderror" 
                              placeholder="Nama jalan, nomor bangunan, dusun atau RT / RW" required>{{ old('alamat', $pengaturan->alamat ?? 'Jl. Raya Wangon - Ajibarang, Dusun Sindurjan') }}</textarea>
                    @error('alamat')
                        <div class="text-xs text-danger font-semibold mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                    <label for="inputDesa" class="form-label-solid">Desa / Kelurahan</label>
                    <input type="text" name="desa" id="inputDesa" 
                           class="form-control form-control-solid @error('desa') is-invalid @enderror" 
                           value="{{ old('desa', $pengaturan->desa ?? 'Wangon') }}" 
                           placeholder="Contoh: Wangon">
                    @error('desa')
                        <div class="text-xs text-danger font-semibold mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                    <label for="inputKecamatan" class="form-label-solid">Kecamatan</label>
                    <input type="text" name="kecamatan" id="inputKecamatan" 
                           class="form-control form-control-solid @error('kecamatan') is-invalid @enderror" 
                           value="{{ old('kecamatan', $pengaturan->kecamatan ?? 'Wangon') }}" 
                           placeholder="Contoh: Wangon">
                    @error('kecamatan')
                        <div class="text-xs text-danger font-semibold mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                    <label for="inputKabupaten" class="form-label-solid">Kabupaten / Kota</label>
                    <input type="text" name="kabupaten" id="inputKabupaten" 
                           class="form-control form-control-solid @error('kabupaten') is-invalid @enderror" 
                           value="{{ old('kabupaten', $pengaturan->kabupaten ?? 'Banyumas') }}" 
                           placeholder="Contoh: Banyumas">
                    @error('kabupaten')
                        <div class="text-xs text-danger font-semibold mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-sm-6 col-md-8">
                    <label for="inputProvinsi" class="form-label-solid">Provinsi</label>
                    <input type="text" name="provinsi" id="inputProvinsi" 
                           class="form-control form-control-solid @error('provinsi') is-invalid @enderror" 
                           value="{{ old('provinsi', $pengaturan->provinsi ?? 'Jawa Tengah') }}" 
                           placeholder="Contoh: Jawa Tengah">
                    @error('provinsi')
                        <div class="text-xs text-danger font-semibold mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                    <label for="inputKodePos" class="form-label-solid">Kode Pos</label>
                    <input type="text" name="kode_pos" id="inputKodePos" 
                           class="form-control form-control-solid @error('kode_pos') is-invalid @enderror" 
                           value="{{ old('kode_pos', $pengaturan->kode_pos ?? '53176') }}" 
                           placeholder="Contoh: 53176">
                    @error('kode_pos')
                        <div class="text-xs text-danger font-semibold mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- ========================================================
             02 — KONTAK SEKRETARIAT
             ======================================================== -->
        <div class="card-solid-responsive mb-4" id="kelompok-02">
            <div class="pb-3 mb-3 border-bottom" style="border-bottom: 1.5px solid var(--brand-sand) !important;">
                <h2 class="h5 fw-extrabold text-[#142E25] mb-1 tracking-tight">02 — Kontak Sekretariat</h2>
                <p class="text-xs text-[#2D4736] mb-0">Nomor WhatsApp resmi, saluran telepon kantor, dan alamat email surat.</p>
            </div>

            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label for="inputWhatsapp" class="form-label-solid">
                        Nomor WhatsApp Resmi (Tombol Publik) <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="whatsapp" id="inputWhatsapp" 
                           class="form-control form-control-solid @error('whatsapp') is-invalid @enderror" 
                           placeholder="Contoh: 081234567890" 
                           value="{{ old('whatsapp', $pengaturan->whatsapp ?? '081234567890') }}" required>
                    <div class="text-xs text-[#2D4736] mt-1 font-semibold">Tautan tombol "Hubungi Sekretariat" di website publik (otomatis format wa.me).</div>
                    @error('whatsapp')
                        <div class="text-xs text-danger font-semibold mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6">
                    <label for="inputEmail" class="form-label-solid">Email Resmi Sekretariat</label>
                    <input type="email" name="email" id="inputEmail" 
                           class="form-control form-control-solid @error('email') is-invalid @enderror" 
                           placeholder="Contoh: sekretariat@nuwangon.or.id" 
                           value="{{ old('email', $pengaturan->email ?? 'sekretariat@nuwangon.or.id') }}">
                    @error('email')
                        <div class="text-xs text-danger font-semibold mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6">
                    <label for="inputTelepon" class="form-label-solid">Nomor HP / Telepon Utama</label>
                    <input type="text" name="telepon" id="inputTelepon" 
                           class="form-control form-control-solid @error('telepon') is-invalid @enderror" 
                           placeholder="Contoh: 081234567890" 
                           value="{{ old('telepon', $pengaturan->telepon ?? '081234567890') }}">
                    @error('telepon')
                        <div class="text-xs text-danger font-semibold mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6">
                    <label for="inputTeleponAlt" class="form-label-solid">Nomor Telepon Alternatif (Opsional)</label>
                    <input type="text" name="telepon_alt" id="inputTeleponAlt" 
                           class="form-control form-control-solid @error('telepon_alt') is-invalid @enderror" 
                           placeholder="Contoh: (0281) 123456" 
                           value="{{ old('telepon_alt', $pengaturan->telepon_alt) }}">
                    @error('telepon_alt')
                        <div class="text-xs text-danger font-semibold mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- ========================================================
             03 — PETUNJUK ARAH
             ======================================================== -->
        <div class="card-solid-responsive mb-4" id="kelompok-03">
            <div class="pb-3 mb-3 border-bottom" style="border-bottom: 1.5px solid var(--brand-sand) !important;">
                <h2 class="h5 fw-extrabold text-[#142E25] mb-1 tracking-tight">03 — Petunjuk Arah</h2>
                <p class="text-xs text-[#2D4736] mb-0">Tautan navigasi Google Maps dan koordinat titik lokasi kantor.</p>
            </div>

            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label for="inputGoogleMaps" class="form-label-solid">
                        Link Google Maps (Tombol Publik) <span class="text-danger">*</span>
                    </label>
                    <input type="url" name="google_maps_url" id="inputGoogleMaps" 
                           class="form-control form-control-solid @error('google_maps_url') is-invalid @enderror" 
                           placeholder="https://maps.google.com/?q=..." 
                           value="{{ old('google_maps_url', $pengaturan->google_maps_url ?? 'https://maps.google.com/?q=Wangon+Banyumas') }}" required>
                    <div class="text-xs text-[#2D4736] mt-1 font-semibold">Tautan yang dibuka saat pengunjung mengklik tombol "Buka Petunjuk Arah Maps".</div>
                    @error('google_maps_url')
                        <div class="text-xs text-danger font-semibold mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6">
                    <label for="inputPetunjukArah" class="form-label-solid">Link Petunjuk Arah Langsung (Opsional)</label>
                    <input type="url" name="petunjuk_arah_url" id="inputPetunjukArah" 
                           class="form-control form-control-solid @error('petunjuk_arah_url') is-invalid @enderror" 
                           placeholder="https://www.google.com/maps/dir/?api=1&..." 
                           value="{{ old('petunjuk_arah_url', $pengaturan->petunjuk_arah_url) }}">
                    @error('petunjuk_arah_url')
                        <div class="text-xs text-danger font-semibold mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-sm-6">
                    <label for="inputLatitude" class="form-label-solid">Latitude (Opsional)</label>
                    <input type="text" name="latitude" id="inputLatitude" 
                           class="form-control form-control-solid @error('latitude') is-invalid @enderror" 
                           placeholder="Contoh: -7.525547" 
                           value="{{ old('latitude', $pengaturan->latitude) }}">
                    @error('latitude')
                        <div class="text-xs text-danger font-semibold mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-sm-6">
                    <label for="inputLongitude" class="form-label-solid">Longitude (Opsional)</label>
                    <input type="text" name="longitude" id="inputLongitude" 
                           class="form-control form-control-solid @error('longitude') is-invalid @enderror" 
                           placeholder="Contoh: 109.056488" 
                           value="{{ old('longitude', $pengaturan->longitude) }}">
                    @error('longitude')
                        <div class="text-xs text-danger font-semibold mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- ========================================================
             04 — JAM OPERASIONAL
             ======================================================== -->
        <div class="card-solid-responsive mb-4" id="kelompok-04">
            <div class="pb-3 mb-3 border-bottom" style="border-bottom: 1.5px solid var(--brand-sand) !important;">
                <h2 class="h5 fw-extrabold text-[#142E25] mb-1 tracking-tight">04 — Jam Operasional</h2>
                <p class="text-xs text-[#2D4736] mb-0">Waktu pelayanan kantor sekretariat bagi pengurus dan warga.</p>
            </div>

            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label for="inputJamHari" class="form-label-solid">Hari Operasional</label>
                    <input type="text" name="jam_operasional_hari" id="inputJamHari" 
                           class="form-control form-control-solid @error('jam_operasional_hari') is-invalid @enderror" 
                           placeholder="Contoh: Senin - Ahad" 
                           value="{{ old('jam_operasional_hari', $pengaturan->jam_operasional_hari ?? 'Senin - Ahad') }}">
                    @error('jam_operasional_hari')
                        <div class="text-xs text-danger font-semibold mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-6 col-md-3 min-w-0">
                    <label for="inputJamBuka" class="form-label-solid">Jam Buka</label>
                    <input type="text" name="jam_buka" id="inputJamBuka" 
                           class="form-control form-control-solid @error('jam_buka') is-invalid @enderror" 
                           placeholder="Contoh: 08.00" 
                           value="{{ old('jam_buka', $pengaturan->jam_buka ?? '08.00') }}">
                    @error('jam_buka')
                        <div class="text-xs text-danger font-semibold mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-6 col-md-3 min-w-0">
                    <label for="inputJamTutup" class="form-label-solid">Jam Tutup</label>
                    <input type="text" name="jam_tutup" id="inputJamTutup" 
                           class="form-control form-control-solid @error('jam_tutup') is-invalid @enderror" 
                           placeholder="Contoh: 16.00" 
                           value="{{ old('jam_tutup', $pengaturan->jam_tutup ?? '16.00') }}">
                    @error('jam_tutup')
                        <div class="text-xs text-danger font-semibold mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6">
                    <label for="inputJamStatus" class="form-label-solid">Status Pelayanan</label>
                    <input type="text" name="jam_operasional_status" id="inputJamStatus" 
                           class="form-control form-control-solid @error('jam_operasional_status') is-invalid @enderror" 
                           placeholder="Contoh: Setiap Hari Kerja" 
                           value="{{ old('jam_operasional_status', $pengaturan->jam_operasional_status ?? 'Setiap Hari Kerja') }}">
                    @error('jam_operasional_status')
                        <div class="text-xs text-danger font-semibold mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-md-6">
                    <label for="inputJamKeterangan" class="form-label-solid">Keterangan Tambahan (Opsional)</label>
                    <input type="text" name="jam_operasional_keterangan" id="inputJamKeterangan" 
                           class="form-control form-control-solid @error('jam_operasional_keterangan') is-invalid @enderror" 
                           placeholder="Contoh: Istirahat sholat dhuhur 12.00 - 13.00 WIB" 
                           value="{{ old('jam_operasional_keterangan', $pengaturan->jam_operasional_keterangan) }}">
                    @error('jam_operasional_keterangan')
                        <div class="text-xs text-danger font-semibold mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- ========================================================
             05 — KARTU INFORMASI LAYANAN
             ======================================================== -->
        <div class="card-solid-responsive mb-4" id="kelompok-05">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 pb-3 mb-3 border-bottom" style="border-bottom: 1.5px solid var(--brand-sand) !important;">
                <div class="min-w-0">
                    <h2 class="h5 fw-extrabold text-[#142E25] mb-1 tracking-tight">05 — Kartu Informasi Layanan</h2>
                    <p class="text-xs text-[#2D4736] mb-0 text-safe">Kelola kartu ringkasan informasi yang tampil di bawah alamat pada website publik.</p>
                </div>
                <button type="button" class="btn-solid-light btn-solid-press btn-solid-sm flex-shrink-0 mt-2 mt-sm-0" onclick="addNewCardRow()">
                    <i class="bi bi-plus-lg me-1"></i>
                    <span>Tambah Kartu Baru</span>
                </button>
            </div>

            <div class="table-solid-wrapper mb-2" style="max-width: 100%;">
                <table class="table-solid table-cards-responsive" id="cardsTable">
                    <thead>
                        <tr>
                            <th style="width: 30%;">Judul Kartu</th>
                            <th style="width: 44%;">Deskripsi Informasi</th>
                            <th style="width: 10%;" class="text-center">Urutan</th>
                            <th style="width: 8%;" class="text-center">Tampilkan</th>
                            <th style="width: 8%;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="cardRowsTbody">
                        @foreach($kartuLayanans as $kartu)
                            <tr id="cardRow-{{ $kartu->id }}" class="card-row-item">
                                <td class="card-col-judul">
                                    <label class="d-md-none form-label-solid text-[11px] mb-1">Judul Kartu <span class="text-danger">*</span></label>
                                    <input type="text" name="cards[{{ $kartu->id }}][judul]" 
                                           class="form-control form-control-sm form-control-solid" 
                                           value="{{ old('cards.'.$kartu->id.'.judul', $kartu->judul) }}" 
                                           placeholder="Judul kartu" required>
                                </td>
                                <td class="card-col-deskripsi">
                                    <label class="d-md-none form-label-solid text-[11px] mb-1">Deskripsi Informasi <span class="text-danger">*</span></label>
                                    <input type="text" name="cards[{{ $kartu->id }}][deskripsi]" 
                                           class="form-control form-control-sm form-control-solid" 
                                           value="{{ old('cards.'.$kartu->id.'.deskripsi', $kartu->deskripsi) }}" 
                                           placeholder="Deskripsi atau jam kerja" required>
                                </td>
                                <td class="card-col-urutan">
                                    <label class="d-md-none form-label-solid text-[11px] mb-1">Urutan</label>
                                    <input type="number" name="cards[{{ $kartu->id }}][urutan]" 
                                           class="form-control form-control-sm form-control-solid text-center mx-auto" 
                                           style="max-width: 75px;" 
                                           value="{{ old('cards.'.$kartu->id.'.urutan', $kartu->urutan) }}">
                                </td>
                                <td class="card-col-status">
                                    <label class="d-md-none form-label-solid text-[11px] mb-1">Aktif</label>
                                    <div class="form-check form-switch d-inline-block mb-0">
                                        <input class="form-check-input" type="checkbox" role="switch" 
                                               name="cards[{{ $kartu->id }}][is_active]" value="1" 
                                               {{ old('cards.'.$kartu->id.'.is_active', $kartu->is_active) ? 'checked' : '' }}
                                               style="cursor: pointer;">
                                    </div>
                                </td>
                                <td class="card-col-aksi">
                                    <label class="d-md-none form-label-solid text-[11px] mb-1 invisible">Aksi</label>
                                    <button type="button" class="btn-solid-danger btn-solid-sm btn-solid-press" 
                                            onclick="deleteCardAjax({{ $kartu->id }})" title="Hapus Kartu">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- BOTTOM ACTION BAR -->
        <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between gap-3 p-3 card-solid-responsive mb-4 bg-white">
            <div class="text-xs text-[#2D4736] font-semibold text-center text-sm-start text-safe">
                <i class="bi bi-info-circle me-1"></i>Perubahan akan langsung diterapkan pada section Pusat Layanan &amp; Sekretariat di website publik.
            </div>
            <button type="submit" class="btn-solid-primary btn-solid-press px-4 py-2 w-100 w-sm-auto justify-content-center">
                <i class="bi bi-check2-circle fs-5 me-1"></i>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </form>

</div>

<!-- HIDDEN FORM FOR DELETING CARDS -->
<form id="deleteCardForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    let newCardIndex = 0;

    function addNewCardRow() {
        newCardIndex++;
        const tbody = document.getElementById('cardRowsTbody');
        const tr = document.createElement('tr');
        tr.id = `newCardRow-${newCardIndex}`;
        tr.className = 'card-row-item';
        tr.innerHTML = `
            <td class="card-col-judul">
                <label class="d-md-none form-label-solid text-[11px] mb-1">Judul Kartu <span class="text-danger">*</span></label>
                <input type="text" name="new_cards[${newCardIndex}][judul]" 
                       class="form-control form-control-sm form-control-solid" 
                       placeholder="Contoh: Layanan ZISWAF" required>
            </td>
            <td class="card-col-deskripsi">
                <label class="d-md-none form-label-solid text-[11px] mb-1">Deskripsi Informasi <span class="text-danger">*</span></label>
                <input type="text" name="new_cards[${newCardIndex}][deskripsi]" 
                       class="form-control form-control-sm form-control-solid" 
                       placeholder="Contoh: Kantor LAZISNU Lt. 1" required>
            </td>
            <td class="card-col-urutan">
                <label class="d-md-none form-label-solid text-[11px] mb-1">Urutan</label>
                <input type="number" name="new_cards[${newCardIndex}][urutan]" 
                       class="form-control form-control-sm form-control-solid text-center mx-auto" 
                       style="max-width: 75px;"
                       value="${newCardIndex + 3}">
            </td>
            <td class="card-col-status">
                <label class="d-md-none form-label-solid text-[11px] mb-1">Aktif</label>
                <span class="badge-solid badge-solid-forest text-xs">Aktif</span>
            </td>
            <td class="card-col-aksi">
                <label class="d-md-none form-label-solid text-[11px] mb-1 invisible">Aksi</label>
                <button type="button" class="btn-solid-danger btn-solid-sm btn-solid-press" onclick="document.getElementById('newCardRow-${newCardIndex}').remove()" title="Batal Tambah">
                    <i class="bi bi-x-lg"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    }

    function deleteCardAjax(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus kartu informasi layanan ini?')) {
            return;
        }

        const form = document.getElementById('deleteCardForm');
        form.action = `/admin/pengaturan/sekretariat/kartu/${id}`;
        form.submit();
    }

    // Dynamic Live Preview updates
    document.addEventListener('DOMContentLoaded', function() {
        const inputLabel = document.getElementById('inputLabelSection');
        const inputNama = document.getElementById('inputNamaKantor');
        const inputAlamat = document.getElementById('inputAlamat');
        const inputDesa = document.getElementById('inputDesa');
        const inputKec = document.getElementById('inputKecamatan');
        const inputKab = document.getElementById('inputKabupaten');
        const inputProv = document.getElementById('inputProvinsi');
        const inputKode = document.getElementById('inputKodePos');

        const prevLabel = document.getElementById('previewLabelSection');
        const prevNama = document.getElementById('previewNamaKantor');
        const prevAlamat = document.getElementById('previewAlamatLengkap');

        function updatePreview() {
            if (prevLabel && inputLabel) {
                prevLabel.textContent = inputLabel.value.trim() || 'PUSAT LAYANAN & SEKRETARIAT';
            }
            if (prevNama && inputNama) {
                prevNama.textContent = inputNama.value.trim() || 'Kantor MWC Nahdlatul Ulama Wangon';
            }
            if (prevAlamat && inputAlamat) {
                const parts = [
                    inputAlamat.value.trim(),
                    inputDesa && inputDesa.value.trim() ? 'Desa ' + inputDesa.value.trim() : null,
                    inputKec && inputKec.value.trim() ? 'Kecamatan ' + inputKec.value.trim() : null,
                    inputKab && inputKab.value.trim() ? 'Kabupaten ' + inputKab.value.trim() : null,
                    inputProv && inputProv.value.trim() ? inputProv.value.trim() : null,
                    inputKode && inputKode.value.trim() ? inputKode.value.trim() : null
                ].filter(Boolean);

                prevAlamat.textContent = parts.join(', ') + '.';
            }
        }

        [inputLabel, inputNama, inputAlamat, inputDesa, inputKec, inputKab, inputProv, inputKode].forEach(el => {
            if (el) el.addEventListener('input', updatePreview);
        });
    });
</script>
@endsection
