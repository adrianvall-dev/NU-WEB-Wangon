@extends('layouts.admin')

@section('title', 'Kelola Data Anggota')

@section('content')
<!-- PAGE TITLE BAR -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-2">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge-solid badge-solid-forest">
                <i class="bi bi-shield-check"></i> ADMIN PORTAL
            </span>
            <span class="text-muted fw-bold" style="font-size: 0.75rem;">•</span>
            <span class="text-muted fw-bold" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Direktori Anggota</span>
        </div>
        <h1 class="fw-black font-serif text-safe mb-1" style="font-size: 1.85rem; color: var(--brand-forest); letter-spacing: -0.015em;">
            Kelola Data Anggota
        </h1>
        <p class="text-muted text-safe mb-0" style="font-size: 0.86rem; font-weight: 500;">
            Pantau dan kelola direktori anggota jam'iyyah NU se-Kecamatan Wangon.
        </p>
    </div>
    <div>
        <a href="{{ route('admin.anggota.create') }}" class="btn-solid-primary btn-solid-press">
            <i class="bi bi-person-plus-fill"></i>
            <span>Tambah Anggota Baru</span>
        </a>
    </div>
</div>

<!-- STATS SECTION (STABLE EQUAL-WIDTH/HEIGHT GRID) -->
<div class="stat-grid-4">
    <!-- STAT 1: TOTAL ANGGOTA -->
    <div class="solid-stat-card d-flex flex-column justify-content-between h-100 min-w-0" style="min-height: 120px;">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
            <span class="text-muted fs-8 fw-bold text-uppercase leading-tight" style="letter-spacing: 0.04em; max-width: 75%;">
                Total Anggota
            </span>
            <div class="sidebar-user-avatar-solid flex-shrink-0" style="width: 38px; height: 38px; background: var(--brand-forest); color: #FFFFFF;">
                <i class="bi bi-people-fill fs-6"></i>
            </div>
        </div>
        <div class="mt-auto">
            <h3 class="fw-black font-serif mb-0" style="font-size: 1.75rem; color: var(--brand-forest); line-height: 1;">
                {{ $totalAnggota ?? $anggotaList->total() }}
            </h3>
        </div>
    </div>

    <!-- STAT 2: ANGGOTA AKTIF -->
    <div class="solid-stat-card d-flex flex-column justify-content-between h-100 min-w-0" style="min-height: 120px;">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
            <span class="text-muted fs-8 fw-bold text-uppercase leading-tight" style="letter-spacing: 0.04em; max-width: 75%;">
                Status Aktif
            </span>
            <div class="sidebar-user-avatar-solid flex-shrink-0" style="width: 38px; height: 38px; background: var(--brand-sage); color: #FFFFFF;">
                <i class="bi bi-person-check-fill fs-6"></i>
            </div>
        </div>
        <div class="mt-auto">
            <h3 class="fw-black font-serif mb-0" style="font-size: 1.75rem; color: var(--brand-sage); line-height: 1;">
                {{ $aktifCount ?? $anggotaList->where('status', 'aktif')->count() }}
            </h3>
        </div>
    </div>

    <!-- STAT 3: ANSOR & PAGAR NUSA -->
    <div class="solid-stat-card d-flex flex-column justify-content-between h-100 min-w-0" style="min-height: 120px;">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
            <span class="text-muted fs-8 fw-bold text-uppercase leading-tight" style="letter-spacing: 0.04em; max-width: 75%;">
                Banom Pemuda / Putri
            </span>
            <div class="sidebar-user-avatar-solid flex-shrink-0" style="width: 38px; height: 38px; background: var(--brand-orange); color: #FFFFFF;">
                <i class="bi bi-shield-fill fs-6"></i>
            </div>
        </div>
        <div class="mt-auto">
            <h3 class="fw-black font-serif mb-0" style="font-size: 1.75rem; color: var(--brand-orange); line-height: 1;">
                {{ $banomPemudaCount ?? $anggotaList->whereIn('organisasi', ['ansor', 'banser', 'pagarnusa', 'fatayat'])->count() }}
            </h3>
        </div>
    </div>

    <!-- STAT 4: MUSLIMAT, FATAYAT & IPPNU -->
    <div class="solid-stat-card d-flex flex-column justify-content-between h-100 min-w-0" style="min-height: 120px;">
        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
            <span class="text-muted fs-8 fw-bold text-uppercase leading-tight" style="letter-spacing: 0.04em; max-width: 75%;">
                Muslimat &amp; Banom
            </span>
            <div class="sidebar-user-avatar-solid flex-shrink-0" style="width: 38px; height: 38px; background: var(--brand-yellow); color: var(--brand-forest);">
                <i class="bi bi-gender-female fs-6"></i>
            </div>
        </div>
        <div class="mt-auto">
            <h3 class="fw-black font-serif mb-0" style="font-size: 1.75rem; color: var(--brand-forest); line-height: 1;">
                {{ $muslimatBanomCount ?? $anggotaList->whereIn('organisasi', ['muslimat', 'fatayat', 'ippnu'])->count() }}
            </h3>
        </div>
    </div>
</div>

<!-- MAIN CONTENT CARD & TABLE -->
<div class="card-solid p-3 p-md-4">
    <!-- FILTER & SEARCH BAR -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-3">
        <!-- FILTER ORGANISASI -->
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="text-muted fw-bold me-1 fs-8 text-uppercase">Filter:</span>
            <a href="{{ route('admin.anggota.index') }}" class="btn-solid-light btn-solid-sm {{ !request('organisasi') ? 'btn-solid-primary' : '' }} btn-solid-press">
                Semua
            </a>
            @foreach(\App\Support\Organisasi::badgeLabels() as $key => $label)
                <a href="{{ route('admin.anggota.index', ['organisasi' => $key]) }}" class="btn-solid-light btn-solid-sm {{ request('organisasi') === $key ? 'btn-solid-primary' : '' }} btn-solid-press">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- SEARCH INPUT -->
        <div class="d-flex flex-wrap align-items-center gap-2" style="max-width: 320px; width: 100%;">
            <div class="position-relative w-100">
                <i class="bi bi-search position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                <input type="text" id="searchInput" class="form-control-solid ps-5 py-2" placeholder="Cari nama, HP, jabatan...">
            </div>
        </div>
    </div>

    <!-- SOLID TABLE CONTAINER -->
    <div class="table-solid-wrapper table-responsive">
        <table class="table-solid" id="mainAnggotaTable">
            <thead>
                <tr>
                    <th style="width: 8%;">Foto</th>
                    <th style="width: 25%;">Nama Anggota</th>
                    <th style="width: 15%;">Organisasi</th>
                    <th style="width: 20%;">Jabatan</th>
                    <th style="width: 16%;">No. HP</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 6%;" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($anggotaList as $index => $item)
                    <tr>
                        <td>
                            @if ($item->foto)
                                <img src="{{ asset('storage/' . $item->foto) }}" class="rounded-circle border-2 border-dark" width="40" height="40" style="object-fit: cover;">
                            @else
                                <div class="sidebar-user-avatar-solid mx-auto" style="width: 40px; height: 40px; border-radius: 50%; background: var(--brand-sand); color: var(--brand-forest);">
                                    <i class="bi bi-person-fill fs-5"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <h6 class="mb-0 fw-bold" style="font-size: 0.9rem; color: var(--brand-forest);">
                                {{ $item->nama }}
                            </h6>
                            @if($item->alamat)
                                <span class="text-muted d-block text-truncate" style="max-width: 220px; font-size: 0.72rem;">{{ $item->alamat }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge-solid badge-solid-matcha" style="font-size: 0.72rem;">
                                {{ \App\Support\Organisasi::badgeLabel($item->organisasi) }}
                            </span>
                        </td>
                        <td>
                            <span class="fw-semibold" style="font-size: 0.82rem; color: var(--brand-forest);">{{ $item->jabatan ?? '-' }}</span>
                        </td>
                        <td>
                            @if($item->no_hp)
                                <span class="fw-semibold" style="font-size: 0.82rem; color: var(--brand-forest);">
                                    <i class="bi bi-whatsapp text-success me-1"></i>{{ $item->no_hp }}
                                </span>
                            @else
                                <span class="text-muted fs-8">-</span>
                            @endif
                        </td>
                        <td>
                            @if ($item->status === 'aktif')
                                <span class="badge-solid badge-solid-forest" style="font-size: 0.68rem;">
                                    <i class="bi bi-check-circle-fill"></i> Aktif
                                </span>
                            @else
                                <span class="badge-solid badge-solid-danger" style="font-size: 0.68rem;">
                                    <i class="bi bi-x-circle"></i> Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-2">
                                <a href="{{ route('admin.anggota.edit', $item->id) }}" class="btn-solid-light btn-solid-sm btn-solid-press" title="Edit Anggota">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('admin.anggota.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus anggota ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-solid-danger btn-solid-sm btn-solid-press" title="Hapus Anggota">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="py-4">
                                <div class="sidebar-user-avatar-solid mx-auto mb-3" style="width: 56px; height: 56px; background: var(--brand-sand); color: var(--brand-forest);">
                                    <i class="bi bi-people fs-3"></i>
                                </div>
                                <h6 class="fw-bold mb-1 font-serif" style="color: var(--brand-forest);">Belum Ada Data Anggota</h6>
                                <p class="text-muted fs-8 mb-3">Klik tombol "Tambah Anggota Baru" untuk mendaftarkan kader jam'iyyah.</p>
                                <a href="{{ route('admin.anggota.create') }}" class="btn-solid-primary btn-solid-sm btn-solid-press">
                                    + Tambah Anggota
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="mt-4 pt-3 border-top border-2 border-dark">
        {{ $anggotaList->links('partials.pagination-solid', ['paginationLabel' => 'anggota']) }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const query = this.value.toLowerCase();
                const rows = document.querySelectorAll('#mainAnggotaTable tbody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(query) ? '' : 'none';
                });
            });
        }
    });
</script>
@endsection