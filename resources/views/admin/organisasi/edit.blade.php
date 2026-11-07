@extends('layouts.admin')

@section('title', 'Edit Organisasi - ' . $organisasi->nama)

@section('content')
<div class="space-y-4">

    <!-- BREADCRUMB & BACK ACTION -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('admin.organisasi.index') }}" class="badge-solid badge-solid-forest text-[11px] text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i>ORGANISASI
                </a>
                <span class="text-xs text-[#2D4736] font-bold uppercase tracking-wider">• EDIT PROFIL & STRUKTUR</span>
            </div>
            <h1 class="h2 fw-extrabold text-[#142E25] tracking-tight mb-1">Edit {{ $organisasi->nama }}</h1>
            <p class="text-[#2D4736] text-sm mb-0">Perbarui identitas banom, kepengurusan pimpinan, serta visi dan misi.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.organisasi.show', $organisasi->id) }}" class="btn-solid-light btn-solid-press">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Detail
            </a>
        </div>
    </div>

    <!-- ERROR MESSAGES -->
    @if ($errors->any())
        <div class="card-solid p-3 mb-4" style="background-color: #FEE2E2; border-color: #EF4444;">
            <div class="d-flex align-items-center gap-2 font-bold text-[#991B1B] mb-2">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <span>Periksa kembali isian formulir:</span>
            </div>
            <ul class="mb-0 text-xs font-semibold text-[#991B1B] ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- MAIN FORM CARD -->
    <div class="card-solid p-4 p-md-5 mb-5">
        <form action="{{ route('admin.organisasi.update', $organisasi->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- SECTION 1: IDENTITAS ORGANISASI -->
            <div class="mb-5">
                <div class="d-flex align-items-center gap-2 pb-2 mb-4 border-bottom-solid">
                    <span class="badge-solid badge-solid-forest"><i class="bi bi-building"></i></span>
                    <h5 class="fw-extrabold text-[#142E25] uppercase tracking-wider mb-0 text-sm">1. Identitas Organisasi / Banom</h5>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-8">
                        <label for="nama" class="form-label-solid">
                            Nama Lengkap Organisasi <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nama" id="nama" class="form-control form-control-solid" 
                               value="{{ old('nama', $organisasi->nama) }}" required placeholder="Contoh: Ikatan Pelajar Nahdlatul Ulama">
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="singkatan" class="form-label-solid">
                            Singkatan / Nama Populer
                        </label>
                        <input type="text" name="singkatan" id="singkatan" class="form-control form-control-solid" 
                               value="{{ old('singkatan', $organisasi->singkatan) }}" placeholder="Contoh: IPNU">
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="slug" class="form-label-solid">
                            Kode Slug Sistem <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="slug" id="slug" class="form-control form-control-solid font-mono" 
                               value="{{ old('slug', $organisasi->slug) }}" required placeholder="contoh: ipnu">
                        <div class="text-xs text-[#2D4736] mt-1 font-semibold">Huruf kecil tanpa spasi. Pengenal unik dalam sistem data anggota.</div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="logo" class="form-label-solid">
                            Logo Organisasi (Opsional)
                        </label>
                        <div class="d-flex align-items-center gap-3">
                            @if($organisasi->logo_url)
                                <div class="table-avatar-thumb" style="width: 52px; height: 52px; min-width: 52px; max-width: 52px; min-height: 52px; max-height: 52px; border-radius: 12px;">
                                    <img src="{{ $organisasi->logo_url }}" alt="Logo Saat Ini">
                                </div>
                            @endif
                            <input type="file" name="logo" id="logo" class="form-control form-control-solid" accept="image/*">
                        </div>
                        <div class="text-xs text-[#2D4736] mt-1 font-semibold">Format JPG, PNG, atau WEBP. Maksimal 2MB. Kosongkan jika tidak ingin mengubah.</div>
                    </div>

                    <div class="col-12">
                        <label for="deskripsi" class="form-label-solid">
                            Deskripsi Singkat Organisasi
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="2" class="form-control form-control-solid" 
                                  placeholder="Deskripsi ringkas profil dan ranah khidmah organisasi...">{{ old('deskripsi', $organisasi->deskripsi) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: STRUKTUR PIMPINAN (KETUA & WAKIL KETUA) -->
            <div class="mb-5">
                <div class="d-flex align-items-center gap-2 pb-2 mb-4 border-bottom-solid">
                    <span class="badge-solid badge-solid-forest"><i class="bi bi-people"></i></span>
                    <h5 class="fw-extrabold text-[#142E25] uppercase tracking-wider mb-0 text-sm">2. Struktur Pimpinan (Ketua & Wakil Ketua)</h5>
                </div>

                @php
                    $banomAnggotas = $kandidatAnggota->filter(fn($k) => $k->organisasi === $organisasi->slug);
                    $otherAnggotas = $kandidatAnggota->filter(fn($k) => $k->organisasi !== $organisasi->slug);
                @endphp

                <div class="row g-3">
                    <!-- KETUA SELECT -->
                    <div class="col-12 col-md-6">
                        <label for="ketua_id" class="form-label-solid">
                            Ketua Organisasi
                        </label>
                        <select name="ketua_id" id="ketua_id" class="form-select form-select-solid">
                            <option value="">-- Belum Ditentukan --</option>
                            @if($banomAnggotas->isNotEmpty())
                                <optgroup label="Anggota {{ $organisasi->singkatan ?: $organisasi->nama }}">
                                    @foreach($banomAnggotas as $kandidat)
                                        <option value="{{ $kandidat->id }}" {{ old('ketua_id', $organisasi->ketua_id) == $kandidat->id ? 'selected' : '' }}>
                                            {{ $kandidat->nama }} ({{ $kandidat->nomor_anggota ?? 'Anggota' }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                            @if($otherAnggotas->isNotEmpty())
                                <optgroup label="Anggota Banom Lain">
                                    @foreach($otherAnggotas as $kandidat)
                                        <option value="{{ $kandidat->id }}" {{ old('ketua_id', $organisasi->ketua_id) == $kandidat->id ? 'selected' : '' }}>
                                            {{ $kandidat->nama }} ({{ $kandidat->nomor_anggota ?? 'Anggota' }} - {{ strtoupper($kandidat->organisasi) }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>
                        <div class="text-xs text-[#2D4736] mt-1 font-semibold">Pilih anggota aktif yang sah menjabat sebagai Ketua.</div>
                    </div>

                    <!-- WAKIL KETUA SELECT -->
                    <div class="col-12 col-md-6">
                        <label for="wakil_ketua_id" class="form-label-solid">
                            Wakil Ketua Organisasi
                        </label>
                        <select name="wakil_ketua_id" id="wakil_ketua_id" class="form-select form-select-solid">
                            <option value="">-- Belum Ditentukan --</option>
                            @if($banomAnggotas->isNotEmpty())
                                <optgroup label="Anggota {{ $organisasi->singkatan ?: $organisasi->nama }}">
                                    @foreach($banomAnggotas as $kandidat)
                                        <option value="{{ $kandidat->id }}" {{ old('wakil_ketua_id', $organisasi->wakil_ketua_id) == $kandidat->id ? 'selected' : '' }}>
                                            {{ $kandidat->nama }} ({{ $kandidat->nomor_anggota ?? 'Anggota' }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                            @if($otherAnggotas->isNotEmpty())
                                <optgroup label="Anggota Banom Lain">
                                    @foreach($otherAnggotas as $kandidat)
                                        <option value="{{ $kandidat->id }}" {{ old('wakil_ketua_id', $organisasi->wakil_ketua_id) == $kandidat->id ? 'selected' : '' }}>
                                            {{ $kandidat->nama }} ({{ $kandidat->nomor_anggota ?? 'Anggota' }} - {{ strtoupper($kandidat->organisasi) }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>
                        <div class="text-xs text-[#2D4736] mt-1 font-semibold">Pilih anggota aktif yang sah menjabat sebagai Wakil Ketua.</div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: PENGATURAN VISI & MISI -->
            <div class="mb-5">
                <div class="d-flex align-items-center gap-2 pb-2 mb-4 border-bottom-solid">
                    <span class="badge-solid badge-solid-forest"><i class="bi bi-compass"></i></span>
                    <h5 class="fw-extrabold text-[#142E25] uppercase tracking-wider mb-0 text-sm">3. Rumusan Visi & Misi</h5>
                </div>

                <!-- VISI -->
                <div class="mb-4">
                    <label for="visi" class="form-label-solid">
                        Visi Organisasi
                    </label>
                    <textarea name="visi" id="visi" rows="3" class="form-control form-control-solid" 
                              placeholder="Tuliskan pernyataan visi organisasi secara lengkap dan inspiratif...">{{ old('visi', $organisasi->visi) }}</textarea>
                </div>

                <!-- MISI DYNAMIC BUILDER -->
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <label class="form-label-solid mb-0">
                            Daftar Butir Misi
                        </label>
                        <button type="button" id="btnAddMisi" class="btn-solid-light btn-solid-press text-xs py-1 px-3">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Butir Misi
                        </button>
                    </div>

                    @php
                        $misiList = old('misi', $organisasi->getMisiList());
                        if (empty($misiList)) {
                            $misiList = [''];
                        }
                    @endphp

                    <div id="misiContainer" class="space-y-2">
                        @foreach($misiList as $index => $item)
                            <div class="d-flex align-items-center gap-2 misi-item-row mb-2" id="misi_row_{{ $index }}">
                                <div style="width: 38px; height: 38px; min-width: 38px; max-width: 38px; min-height: 38px; max-height: 38px; border-radius: 10px; border: 2px solid var(--brand-forest); background-color: var(--brand-matcha); color: var(--brand-forest); font-weight: 800; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;" class="misi-num-indicator">
                                    {{ $index + 1 }}
                                </div>
                                <input type="text" name="misi[]" class="form-control form-control-solid" 
                                       value="{{ $item }}" placeholder="Tuliskan butir misi / program kerja utama...">
                                <button type="button" class="btn-solid-danger btn-solid-press py-2 px-3" onclick="removeMisiRow({{ $index }})" title="Hapus butir ini">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-xs text-[#2D4736] mt-2 font-semibold">
                        Setiap butir misi akan otomatis ditampilkan rapi bernomor urut pada halaman profil publik.
                    </div>
                </div>
            </div>

            <!-- BUTTONS ACTION BAR -->
            <div class="pt-4 border-top-solid d-flex align-items-center justify-content-end gap-3">
                <a href="{{ route('admin.organisasi.show', $organisasi->id) }}" class="btn-solid-light btn-solid-press text-decoration-none">
                    Batal
                </a>
                <button type="submit" class="btn-solid-primary btn-solid-press px-4">
                    <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    let misiIndex = {{ count($misiList) }};

    document.getElementById('btnAddMisi').addEventListener('click', function() {
        const container = document.getElementById('misiContainer');
        const rowsCount = container.querySelectorAll('.misi-item-row').length;
        const newRow = document.createElement('div');
        newRow.className = 'd-flex align-items-center gap-2 misi-item-row mb-2';
        newRow.id = 'misi_row_' + misiIndex;
        newRow.innerHTML = `
            <div style="width: 38px; height: 38px; min-width: 38px; max-width: 38px; min-height: 38px; max-height: 38px; border-radius: 10px; border: 2px solid var(--brand-forest); background-color: var(--brand-matcha); color: var(--brand-forest); font-weight: 800; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;" class="misi-num-indicator">
                ${rowsCount + 1}
            </div>
            <input type="text" name="misi[]" class="form-control form-control-solid" placeholder="Tuliskan butir misi / program kerja utama...">
            <button type="button" class="btn-solid-danger btn-solid-press py-2 px-3" onclick="removeMisiRow(${misiIndex})" title="Hapus butir ini">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(newRow);
        misiIndex++;
        renumberMisi();
    });

    function removeMisiRow(index) {
        const row = document.getElementById('misi_row_' + index);
        if (row) {
            row.remove();
            renumberMisi();
        }
    }

    function renumberMisi() {
        const container = document.getElementById('misiContainer');
        const rows = container.querySelectorAll('.misi-item-row');
        rows.forEach((row, idx) => {
            const indicator = row.querySelector('.misi-num-indicator');
            if (indicator) {
                indicator.textContent = idx + 1;
            }
        });
    }
</script>
@endsection
