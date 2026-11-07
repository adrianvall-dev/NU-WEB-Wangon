@extends('layouts.admin')

@section('title', 'Detail Organisasi - ' . $organisasi->nama)

@section('content')
@php
    $ketua = $organisasi->getActiveKetua();
    $wakil = $organisasi->getActiveWakilKetua();
    $misiList = $organisasi->getMisiList();
@endphp

<div class="space-y-4">

    <!-- BREADCRUMB & TOP ACTIONS -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('admin.organisasi.index') }}" class="badge-solid badge-solid-forest text-[11px] text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i>ORGANISASI
                </a>
                <span class="text-xs text-[#2D4736] font-bold uppercase tracking-wider">• DETAIL BADAN OTONOM</span>
            </div>
            <h1 class="h2 fw-extrabold text-[#142E25] tracking-tight mb-1">{{ $organisasi->nama }}</h1>
            <p class="text-[#2D4736] text-sm mb-0">Struktur pimpinan, visi, misi, dan anggota terdaftar.</p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('admin.organisasi.index') }}" class="btn-solid-light btn-solid-press">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
            <a href="{{ route('anggota.index', ['organisasi' => $organisasi->slug]) }}" target="_blank" class="btn-solid-light btn-solid-press text-decoration-none">
                <i class="bi bi-box-arrow-up-right me-1"></i>Lihat Publik
            </a>
            <a href="{{ route('admin.organisasi.edit', $organisasi->id) }}" class="btn-solid-primary btn-solid-press">
                <i class="bi bi-pencil-square me-1"></i>Edit
            </a>
            <form action="{{ route('admin.organisasi.destroy', $organisasi->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus organisasi {{ $organisasi->nama }}? Tindakan ini tidak dapat dibatalkan.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-solid-danger btn-solid-press">
                    <i class="bi bi-trash me-1"></i>Hapus
                </button>
            </form>
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    @if(session('success'))
        <div class="card-solid p-3 mb-4 d-flex align-items-center justify-content-between" style="background-color: var(--brand-matcha);">
            <div class="d-flex align-items-center gap-2 font-bold text-[#142E25]">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- MAIN PROFILE HEADER CARD -->
    <div class="card-solid p-4 p-md-5 mb-4">
        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-4">
            <div class="organisasi-logo-thumb hard-shadow-sm">
                @if($organisasi->logo_url)
                    <img src="{{ $organisasi->logo_url }}" alt="{{ $organisasi->nama }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; font-weight: 800; font-size: 1.6rem; color: var(--brand-forest);">
                        {{ $organisasi->inisial }}
                    </div>
                @else
                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.6rem; color: var(--brand-forest);">
                        {{ $organisasi->inisial }}
                    </div>
                @endif
            </div>
            <div class="flex-grow-1 min-w-0">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <span class="badge-solid badge-solid-forest text-xs">
                        Badan Otonom NU Sindang
                    </span>
                    @if($organisasi->singkatan)
                        <span class="badge-solid badge-solid-matcha text-xs">
                            {{ $organisasi->singkatan }}
                        </span>
                    @endif
                    <code class="text-xs font-mono bg-white border border-[#142E25] px-2 py-0.5 rounded text-[#142E25] font-bold">
                        slug: {{ $organisasi->slug }}
                    </code>
                </div>
                <h2 class="h3 fw-extrabold text-[#142E25] mb-2 text-safe">{{ $organisasi->nama }}</h2>
                <p class="text-[#2D4736] text-sm mb-0 text-safe leading-relaxed">
                    {{ $organisasi->deskripsi ?: 'Belum ada deskripsi profil untuk organisasi ini. Anda dapat melengkapinya melalui tombol Edit Organisasi.' }}
                </p>
            </div>
        </div>
    </div>

    <!-- STRUKTUR PIMPINAN (KETUA & WAKIL KETUA) -->
    <div class="row g-4 mb-4">
        <!-- KETUA CARD -->
        <div class="col-12 col-md-6">
            <div class="card-solid h-100 p-4 d-flex flex-column">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom-solid">
                    <span class="badge-solid badge-solid-forest text-xs">
                        <i class="bi bi-star-fill me-1 text-[#FFD56C]"></i>Ketua Organisasi
                    </span>
                    <span class="text-xs font-bold text-[#2D4736] uppercase tracking-wider">Pimpinan Utama</span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="card-pimpinan-avatar">
                        @if($ketua && $ketua->foto)
                            <img src="{{ asset('storage/' . $ketua->foto) }}" alt="{{ $ketua->nama }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; font-weight: 800; font-size: 1.5rem; color: var(--brand-forest); background-color: var(--brand-matcha);">
                                {{ strtoupper(substr($ketua->nama, 0, 1)) }}
                            </div>
                        @elseif($ketua)
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.5rem; color: var(--brand-forest); background-color: var(--brand-matcha);">
                                {{ strtoupper(substr($ketua->nama, 0, 1)) }}
                            </div>
                        @else
                            <i class="bi bi-person-x text-[#2D4736] fs-2"></i>
                        @endif
                    </div>
                    <div class="min-w-0 flex-grow-1">
                        @if($ketua)
                            <h5 class="fw-extrabold text-[#142E25] mb-1 text-truncate" title="{{ $ketua->nama }}">{{ $ketua->nama }}</h5>
                            <div class="text-xs text-[#2D4736] font-mono mt-0.5">
                                <span class="badge-solid badge-solid-forest text-[10px] me-1">KETUA</span>
                                {{ $ketua->nomor_anggota ? 'NIA: ' . $ketua->nomor_anggota : 'Ketua Aktif' }}
                            </div>
                            @if($ketua->no_hp)
                                <div class="text-xs text-[#2D4736] mt-1 font-semibold">
                                    <i class="bi bi-whatsapp me-1 text-success"></i>{{ $ketua->no_hp }}
                                </div>
                            @endif
                        @else
                            <h6 class="text-[#2D4736] fst-italic mb-1 font-bold">Ketua belum ditentukan</h6>
                            <span class="text-xs text-[#2D4736] d-block mb-2">Pilih anggota sebagai ketua melalui menu edit.</span>
                            <a href="{{ route('admin.organisasi.edit', $organisasi->id) }}" class="btn-solid-light btn-solid-sm btn-solid-press py-1 px-2 text-xs text-decoration-none">
                                <i class="bi bi-person-plus me-1"></i>Pilih Ketua
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- WAKIL KETUA CARD -->
        <div class="col-12 col-md-6">
            <div class="card-solid h-100 p-4 d-flex flex-column">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom-solid">
                    <span class="badge-solid badge-solid-matcha text-xs">
                        Wakil Ketua
                    </span>
                    <span class="text-xs font-bold text-[#2D4736] uppercase tracking-wider">Pimpinan Pendamping</span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="card-pimpinan-avatar" style="background-color: #FAF8F5;">
                        @if($wakil && $wakil->foto)
                            <img src="{{ asset('storage/' . $wakil->foto) }}" alt="{{ $wakil->nama }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; font-weight: 800; font-size: 1.5rem; color: var(--brand-forest); background-color: #FAF8F5;">
                                {{ strtoupper(substr($wakil->nama, 0, 1)) }}
                            </div>
                        @elseif($wakil)
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.5rem; color: var(--brand-forest); background-color: #FAF8F5;">
                                {{ strtoupper(substr($wakil->nama, 0, 1)) }}
                            </div>
                        @else
                            <i class="bi bi-person-x text-[#2D4736] fs-2"></i>
                        @endif
                    </div>
                    <div class="min-w-0 flex-grow-1">
                        @if($wakil)
                            <h5 class="fw-extrabold text-[#142E25] mb-1 text-truncate" title="{{ $wakil->nama }}">{{ $wakil->nama }}</h5>
                            <div class="text-xs text-[#2D4736] font-mono mt-0.5">
                                <span class="badge-solid badge-solid-matcha text-[10px] me-1">WAKIL KETUA</span>
                                {{ $wakil->nomor_anggota ? 'NIA: ' . $wakil->nomor_anggota : 'Wakil Ketua Aktif' }}
                            </div>
                            @if($wakil->no_hp)
                                <div class="text-xs text-[#2D4736] mt-1 font-semibold">
                                    <i class="bi bi-whatsapp me-1 text-success"></i>{{ $wakil->no_hp }}
                                </div>
                            @endif
                        @else
                            <h6 class="text-[#2D4736] fst-italic mb-1 font-bold">Wakil Ketua belum ditentukan</h6>
                            <span class="text-xs text-[#2D4736] d-block mb-2">Pilih anggota sebagai wakil ketua melalui menu edit.</span>
                            <a href="{{ route('admin.organisasi.edit', $organisasi->id) }}" class="btn-solid-light btn-solid-sm btn-solid-press py-1 px-2 text-xs text-decoration-none">
                                <i class="bi bi-person-plus me-1"></i>Pilih Wakil Ketua
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DIAGRAM HIERARKI / TERMINAL BOX -->
    <div class="card-solid p-4 mb-4">
        <div class="d-flex align-items-center gap-2 pb-2 mb-3 border-bottom-solid">
            <span class="badge-solid badge-solid-forest"><i class="bi bi-diagram-3-fill"></i></span>
            <h5 class="fw-extrabold text-[#142E25] uppercase tracking-wider mb-0 text-sm">Diagram Struktur Kepengurusan</h5>
        </div>
        <div class="bg-[#142E25] text-[#D9E8DD] p-4 rounded-xl border-2 border-[#142E25] font-mono text-sm overflow-x-auto">
            <div class="text-[#FFD56C] pb-2 mb-2 border-bottom border-[#2D4736] d-flex justify-content-between align-items-center text-xs">
                <span>STRUKTUR RESMI BADAN OTONOM</span>
                <span><i class="bi bi-check-circle me-1"></i>SINKRON DATABASE</span>
            </div>
            <pre class="mb-0 text-[#D9E8DD]" style="font-family: monospace; font-size: 0.85rem; line-height: 1.6; white-space: pre-wrap; word-break: break-word;">[{{ $organisasi->nama }}]
  ├── Ketua: {{ $ketua ? $ketua->nama : 'Belum ditentukan' }}
  ├── Wakil Ketua: {{ $wakil ? $wakil->nama : 'Belum ditentukan' }}
  ├── Visi: {{ $organisasi->visi ? \Illuminate\Support\Str::limit($organisasi->visi, 50) : 'Belum ditambahkan' }}
  └── Misi: {{ count($misiList) > 0 ? count($misiList) . ' Butir Misi' : 'Belum ditambahkan' }}</pre>
        </div>
    </div>

    <!-- VISI & MISI DETAILS -->
    <div class="row g-4 mb-4">
        <!-- VISI -->
        <div class="col-12 col-lg-5">
            <div class="card-solid h-100 p-4 d-flex flex-column">
                <div class="d-flex align-items-center gap-2 pb-2 mb-3 border-bottom-solid">
                    <span class="badge-solid badge-solid-forest"><i class="bi bi-compass"></i></span>
                    <h5 class="fw-extrabold text-[#142E25] uppercase tracking-wider mb-0 text-sm">Visi Organisasi</h5>
                </div>
                @if($organisasi->visi)
                    <div class="p-3 rounded-xl bg-[#FAF8F5] border-2 border-[#142E25] flex-grow-1">
                        <p class="fst-italic text-[#142E25] font-serif mb-0 leading-relaxed text-sm text-safe" style="overflow-wrap: anywhere; word-break: break-word;">
                            &ldquo;{{ $organisasi->visi }}&rdquo;
                        </p>
                    </div>
                @else
                    <div class="text-center py-4 text-[#2D4736] flex-grow-1 d-flex flex-column justify-content-center">
                        <i class="bi bi-file-earmark-text fs-2 d-block mb-1"></i>
                        <span class="fst-italic text-xs font-semibold">Visi belum ditambahkan.</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- MISI -->
        <div class="col-12 col-lg-7">
            <div class="card-solid h-100 p-4 d-flex flex-column">
                <div class="d-flex align-items-center gap-2 pb-2 mb-3 border-bottom-solid">
                    <span class="badge-solid badge-solid-forest"><i class="bi bi-list-check"></i></span>
                    <h5 class="fw-extrabold text-[#142E25] uppercase tracking-wider mb-0 text-sm">Misi Organisasi</h5>
                </div>
                @if(count($misiList) > 0)
                    <div class="space-y-2 flex-grow-1">
                        @foreach($misiList as $idx => $misiItem)
                            <div class="d-flex align-items-start gap-2 p-2.5 rounded-lg bg-[#FAF8F5] border-2 border-[#142E25] mb-2">
                                <div style="width: 24px; height: 24px; min-width: 24px; min-height: 24px; border-radius: 6px; background-color: var(--brand-forest); color: var(--brand-matcha); font-weight: 800; font-size: 0.75rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;" class="mt-0.5">
                                    {{ $idx + 1 }}
                                </div>
                                <span class="text-[#142E25] text-xs font-semibold leading-relaxed pt-0.5 text-safe min-w-0" style="overflow-wrap: anywhere; word-break: break-word;">{{ $misiItem }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-[#2D4736] flex-grow-1 d-flex flex-column justify-content-center">
                        <i class="bi bi-card-checklist fs-2 d-block mb-1"></i>
                        <span class="fst-italic text-xs font-semibold">Misi belum ditambahkan.</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ANGGOTA TERDAFTAR TABLE CARD -->
    <div class="card-solid p-0 overflow-hidden mb-4">
        <div class="p-3 border-bottom-solid d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 bg-[#FAF8F5]">
            <div class="d-flex align-items-center gap-2">
                <h5 class="fw-extrabold text-[#142E25] mb-0">Anggota Terdaftar di {{ $organisasi->singkatan ?: $organisasi->nama }}</h5>
                <span class="badge-solid badge-solid-forest text-xs">{{ $anggotas->total() }} Anggota</span>
            </div>
            <a href="{{ route('admin.anggota.create') }}" class="btn-solid-primary btn-solid-press text-xs py-1.5 px-3 text-decoration-none">
                <i class="bi bi-plus-lg me-1"></i>Tambah Anggota
            </a>
        </div>

        <div class="table-solid-wrapper table-responsive border-0 shadow-none rounded-0">
            <table class="table-solid" style="min-width: 760px;">
                <thead>
                    <tr>
                        <th style="width: 32%;">Anggota</th>
                        <th style="width: 18%;">Jabatan</th>
                        <th style="width: 18%;">Nomor Anggota</th>
                        <th style="width: 14%;">No. HP</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 8%;" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($anggotas as $anggota)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="table-avatar-thumb">
                                        @if($anggota->foto)
                                            <img src="{{ asset('storage/' . $anggota->foto) }}" 
                                                 alt="{{ $anggota->nama }}" 
                                                 loading="lazy"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; font-weight: 800; font-size: 1.25rem; color: var(--brand-forest); background-color: var(--brand-matcha);">
                                                {{ strtoupper(substr($anggota->nama, 0, 1)) }}
                                            </div>
                                        @else
                                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.25rem; color: var(--brand-forest); background-color: var(--brand-matcha);">
                                                {{ strtoupper(substr($anggota->nama, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-grow-1">
                                        <div class="fw-extrabold text-[#142E25] text-xs text-truncate" title="{{ $anggota->nama }}">{{ $anggota->nama }}</div>
                                        <div class="text-[11px] text-[#2D4736] text-truncate" title="{{ $anggota->alamat ?: 'Sindang' }}">
                                            <i class="bi bi-geo-alt me-1 text-muted"></i>{{ $anggota->alamat ?: 'Sindang' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($anggota->id === $organisasi->ketua_id || ($ketua && $anggota->id === $ketua->id))
                                    <span class="badge-solid badge-solid-forest text-xs">
                                        <i class="bi bi-star-fill text-[#FFD56C] me-1"></i>Ketua
                                    </span>
                                @elseif($anggota->id === $organisasi->wakil_ketua_id || ($wakil && $anggota->id === $wakil->id))
                                    <span class="badge-solid badge-solid-matcha text-xs">
                                        <i class="bi bi-award-fill me-1"></i>Wakil Ketua
                                    </span>
                                @else
                                    <span class="text-xs font-bold text-[#142E25]">{{ $anggota->jabatan ?: 'Anggota' }}</span>
                                @endif
                            </td>
                            <td>
                                <code class="text-xs font-mono text-[#142E25] font-bold">{{ $anggota->nomor_anggota ?: '-' }}</code>
                            </td>
                            <td>
                                <span class="text-xs text-[#2D4736] font-semibold">{{ $anggota->no_hp ?: '-' }}</span>
                            </td>
                            <td>
                                @if($anggota->status === 'aktif')
                                    <span class="badge-solid badge-solid-matcha text-[11px]">Aktif</span>
                                @else
                                    <span class="badge-solid badge-solid-light text-[11px]">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.anggota.edit', $anggota->id) }}" class="btn-solid-light btn-solid-press px-2 py-1 text-xs" title="Edit Anggota">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-[#2D4736] text-xs font-semibold">
                                Belum ada anggota yang terdaftar pada organisasi ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($anggotas->hasPages())
            <div class="p-3 border-top-solid bg-[#FAF8F5]">
                {{ $anggotas->links('partials.pagination-solid', ['paginationLabel' => 'anggota']) }}
            </div>
        @endif
    </div>

</div>
@endsection
