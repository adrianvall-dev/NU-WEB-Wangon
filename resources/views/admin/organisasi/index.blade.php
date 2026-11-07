@extends('layouts.admin')

@section('title', 'Manajemen Organisasi')

@section('content')
<div class="space-y-4">

    <!-- HEADER TITLE & ACTIONS -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge-solid badge-solid-forest text-[11px]">DATA MASTER</span>
                <span class="text-xs text-[#2D4736] font-bold uppercase tracking-wider">• STRUKTUR KEPENGURUSAN</span>
            </div>
            <h1 class="h2 fw-extrabold text-[#142E25] tracking-tight mb-1">Manajemen Organisasi</h1>
            <p class="text-[#2D4736] text-sm mb-0">Kelola profil banom, visi, misi, pimpinan ketua & wakil ketua di lingkungan NU Sindang.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.organisasi.create') }}" class="btn-solid-primary btn-solid-press">
                <i class="bi bi-plus-lg fs-6"></i>
                <span>Tambah Organisasi</span>
            </a>
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

    <!-- STATS CARDS (3 SOLID STAT CARDS) -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="solid-stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-xs font-bold text-[#2D4736] uppercase tracking-wider">Total Badan Otonom</span>
                    <span class="badge-solid badge-solid-forest"><i class="bi bi-buildings"></i></span>
                </div>
                <div class="h2 fw-extrabold text-[#142E25] mb-0">{{ $organisasis->count() }}</div>
                <div class="text-xs text-[#2D4736] mt-1 font-semibold">Organisasi / Banom terdata</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="solid-stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-xs font-bold text-[#2D4736] uppercase tracking-wider">Memiliki Ketua Aktif</span>
                    <span class="badge-solid badge-solid-matcha"><i class="bi bi-person-check-fill"></i></span>
                </div>
                <div class="h2 fw-extrabold text-[#142E25] mb-0">{{ $organisasis->filter(fn($o) => $o->hasActiveKetua())->count() }}</div>
                <div class="text-xs text-[#2D4736] mt-1 font-semibold">Struktur kepemimpinan sah</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="solid-stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-xs font-bold text-[#2D4736] uppercase tracking-wider">Visi & Misi Ditetapkan</span>
                    <span class="badge-solid badge-solid-yellow"><i class="bi bi-card-checklist"></i></span>
                </div>
                <div class="h2 fw-extrabold text-[#142E25] mb-0">{{ $organisasis->filter(fn($o) => !empty($o->visi) || !empty($o->misi))->count() }}</div>
                <div class="text-xs text-[#2D4736] mt-1 font-semibold">Profil program kerja lengkap</div>
            </div>
        </div>
    </div>

    <!-- ORGANISASI LIST TABLE CARD -->
    <div class="card-solid p-0 overflow-hidden mb-4">
        <div class="p-3 border-bottom-solid d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 bg-[#FAF8F5]">
            <div class="d-flex align-items-center gap-2">
                <h5 class="fw-extrabold text-[#142E25] mb-0">Daftar Badan Otonom & Lembaga</h5>
                <span class="badge-solid badge-solid-light text-xs">{{ $organisasis->count() }} Data</span>
            </div>
            <div class="w-full sm:w-72">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-2 border-[#142E25] text-[#142E25]"><i class="bi bi-search"></i></span>
                    <input type="text" id="filterOrgInput" class="form-control form-control-solid text-sm" placeholder="Cari organisasi...">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-solid" id="orgTable">
                <thead>
                    <tr>
                        <th style="width: 28%;">Organisasi / Banom</th>
                        <th style="width: 18%;">Ketua</th>
                        <th style="width: 18%;">Wakil Ketua</th>
                        <th style="width: 16%;">Visi & Misi</th>
                        <th style="width: 10%;">Anggota</th>
                        <th style="width: 10%;" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($organisasis as $org)
                        @php
                            $ketua = $org->getActiveKetua();
                            $wakil = $org->getActiveWakilKetua();
                            $misiCount = count($org->getMisiList());
                        @endphp
                        <tr>
                            <!-- Organisasi -->
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="table-avatar-thumb" style="width: 48px; height: 48px; min-width: 48px; max-width: 48px; min-height: 48px; max-height: 48px; border-radius: 12px;">
                                        @if($org->logo_url)
                                            <img src="{{ $org->logo_url }}" alt="{{ $org->nama }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem; color: var(--brand-forest);">
                                                {{ $org->inisial }}
                                            </div>
                                        @else
                                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem; color: var(--brand-forest);">
                                                {{ $org->inisial }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.organisasi.show', $org->id) }}" class="fw-extrabold text-[#142E25] hover:underline text-decoration-none">
                                            {{ $org->nama }}
                                        </a>
                                        <div class="d-flex align-items-center gap-2 mt-1">
                                            @if($org->singkatan)
                                                <span class="badge-solid badge-solid-forest text-[10px]">{{ $org->singkatan }}</span>
                                            @endif
                                            <code class="text-xs font-mono text-[#2D4736]">slug: {{ $org->slug }}</code>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Ketua -->
                            <td>
                                @if($ketua)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="table-avatar-thumb" style="width: 38px; height: 38px; min-width: 38px; max-width: 38px; min-height: 38px; max-height: 38px; border-radius: 10px; background-color: var(--brand-forest); color: #FFFFFF;">
                                            @if($ketua->foto)
                                                <img src="{{ asset('storage/' . $ketua->foto) }}" alt="{{ $ketua->nama }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; color: #FFFFFF;">
                                                    {{ strtoupper(substr($ketua->nama, 0, 1)) }}
                                                </div>
                                            @else
                                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; color: #FFFFFF;">
                                                    {{ strtoupper(substr($ketua->nama, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs fw-extrabold text-[#142E25] text-truncate">{{ $ketua->nama }}</div>
                                            <div class="text-[11px] text-[#2D4736] font-mono">{{ $ketua->nomor_anggota ?? 'Anggota Aktif' }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="badge-solid badge-solid-light text-xs">
                                        <i class="bi bi-dash-circle me-1"></i>Belum Diatur
                                    </span>
                                @endif
                            </td>

                            <!-- Wakil Ketua -->
                            <td>
                                @if($wakil)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="table-avatar-thumb" style="width: 38px; height: 38px; min-width: 38px; max-width: 38px; min-height: 38px; max-height: 38px; border-radius: 10px; background-color: var(--brand-matcha); color: var(--brand-forest);">
                                            @if($wakil->foto)
                                                <img src="{{ asset('storage/' . $wakil->foto) }}" alt="{{ $wakil->nama }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; color: var(--brand-forest);">
                                                    {{ strtoupper(substr($wakil->nama, 0, 1)) }}
                                                </div>
                                            @else
                                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; color: var(--brand-forest);">
                                                    {{ strtoupper(substr($wakil->nama, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs fw-extrabold text-[#142E25] text-truncate">{{ $wakil->nama }}</div>
                                            <div class="text-[11px] text-[#2D4736] font-mono">{{ $wakil->nomor_anggota ?? 'Anggota Aktif' }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="badge-solid badge-solid-light text-xs">
                                        <i class="bi bi-dash-circle me-1"></i>Belum Diatur
                                    </span>
                                @endif
                            </td>

                            <!-- Visi & Misi Status -->
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    @if(!empty($org->visi))
                                        <span class="badge-solid badge-solid-matcha text-[11px] align-self-start">
                                            <i class="bi bi-check me-1"></i>Visi Tersedia
                                        </span>
                                    @else
                                        <span class="badge-solid badge-solid-light text-[11px] align-self-start">
                                            Visi Kosong
                                        </span>
                                    @endif

                                    @if($misiCount > 0)
                                        <span class="badge-solid badge-solid-yellow text-[11px] align-self-start">
                                            <i class="bi bi-list-check me-1"></i>{{ $misiCount }} Butir Misi
                                        </span>
                                    @else
                                        <span class="badge-solid badge-solid-light text-[11px] align-self-start">
                                            Misi Kosong
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Anggota -->
                            <td>
                                <div class="font-bold text-[#142E25]">
                                    {{ number_format($org->active_anggotas_count ?? 0) }}
                                    <span class="text-xs font-normal text-[#2D4736]">aktif</span>
                                </div>
                                <div class="text-xs text-[#2D4736]">
                                    {{ number_format($org->anggotas_count ?? 0) }} total
                                </div>
                            </td>

                            <!-- Aksi -->
                            <td class="text-end">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <a href="{{ route('admin.organisasi.show', $org->id) }}" class="btn-solid-light btn-solid-press px-2.5 py-1 text-xs" title="Lihat Struktur">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.organisasi.edit', $org->id) }}" class="btn-solid-light btn-solid-press px-2.5 py-1 text-xs" title="Edit Organisasi">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.organisasi.destroy', $org->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus organisasi {{ $org->nama }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-solid-danger btn-solid-press px-2.5 py-1 text-xs" title="Hapus Organisasi">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <div class="w-16 h-16 rounded-2xl bg-[#D9E8DD] border-2 border-[#142E25] d-flex align-items-center justify-content-center text-[#142E25] fs-2 mb-3 hard-shadow-sm">
                                        <i class="bi bi-buildings"></i>
                                    </div>
                                    <h5 class="fw-extrabold text-[#142E25] mb-1">Belum Ada Organisasi</h5>
                                    <p class="text-[#2D4736] text-sm mb-3">Tambahkan badan otonom pertama Anda sekarang.</p>
                                    <a href="{{ route('admin.organisasi.create') }}" class="btn-solid-primary btn-solid-press">
                                        <i class="bi bi-plus-lg me-1"></i>Tambah Organisasi
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterInput = document.getElementById('filterOrgInput');
        if (filterInput) {
            filterInput.addEventListener('keyup', function() {
                const q = this.value.toLowerCase();
                const rows = document.querySelectorAll('#orgTable tbody tr');
                rows.forEach(r => {
                    const txt = r.textContent.toLowerCase();
                    r.style.display = txt.includes(q) ? '' : 'none';
                });
            });
        }
    });
</script>
@endsection
