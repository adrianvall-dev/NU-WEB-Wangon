@extends('layouts.admin')

@section('title', 'Kelola Admin')

@section('content')
<div class="space-y-4">

    <!-- PAGE TITLE BAR -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge-solid badge-solid-forest text-[11px]">SISTEM</span>
                <span class="text-xs text-[#2D4736] font-bold uppercase tracking-wider">• HAK AKSES PENGELOLA</span>
            </div>
            <h1 class="h2 fw-extrabold text-[#142E25] tracking-tight mb-1">Kelola Akun Admin</h1>
            <p class="text-[#2D4736] text-sm mb-0">Atur akun administrator yang memiliki izin mengelola panel website.</p>
        </div>
        <div>
            <a href="{{ route('admin.admin-user.create') }}" class="btn-solid-primary btn-solid-press">
                <i class="bi bi-person-plus-fill me-1"></i>Tambah Admin Baru
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

    <!-- TOP STATS (4 SOLID STAT CARDS) -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="solid-stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-xs font-bold text-[#2D4736] uppercase tracking-wider">Total Admin</span>
                    <span class="badge-solid badge-solid-forest"><i class="bi bi-shield-shaded"></i></span>
                </div>
                <div class="h2 fw-extrabold text-[#142E25] mb-0">
                    {{ is_object($admins) && method_exists($admins, 'total') ? $admins->total() : count($admins) }}
                </div>
                <div class="text-xs text-[#2D4736] mt-1 font-semibold">Pengguna terdaftar</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="solid-stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-xs font-bold text-[#2D4736] uppercase tracking-wider">Akun Aktif</span>
                    <span class="badge-solid badge-solid-matcha"><i class="bi bi-person-check-fill"></i></span>
                </div>
                <div class="h2 fw-extrabold text-[#142E25] mb-0">
                    {{ is_object($admins) && method_exists($admins, 'count') ? $admins->count() : count($admins) }}
                </div>
                <div class="text-xs text-[#2D4736] mt-1 font-semibold">Siap bertugas</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="solid-stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-xs font-bold text-[#2D4736] uppercase tracking-wider">Akun Anda</span>
                    <span class="badge-solid badge-solid-yellow"><i class="bi bi-person-workspace"></i></span>
                </div>
                <div class="h2 fw-extrabold text-[#142E25] mb-0">1</div>
                <div class="text-xs text-[#2D4736] mt-1 font-semibold">Sesi login saat ini</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="solid-stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-xs font-bold text-[#2D4736] uppercase tracking-wider">Hak Akses</span>
                    <span class="badge-solid badge-solid-light"><i class="bi bi-key-fill"></i></span>
                </div>
                <div class="h2 fw-extrabold text-[#142E25] mb-0">Full</div>
                <div class="text-xs text-[#2D4736] mt-1 font-semibold">Super Administrator</div>
            </div>
        </div>
    </div>

    <!-- MAIN TABLE CARD -->
    <div class="card-solid p-0 overflow-hidden mb-4">
        <div class="p-3 border-bottom-solid d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 bg-[#FAF8F5]">
            <div class="d-flex align-items-center gap-2">
                <h5 class="fw-extrabold text-[#142E25] mb-0">Daftar Administrator</h5>
                <span class="badge-solid badge-solid-light text-xs">
                    {{ is_object($admins) && method_exists($admins, 'total') ? $admins->total() : count($admins) }} Pengguna
                </span>
            </div>
            <div class="w-full sm:w-72">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-2 border-[#142E25] text-[#142E25]"><i class="bi bi-search"></i></span>
                    <input type="text" id="searchInput" class="form-control form-control-solid text-sm" placeholder="Cari nama atau email...">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-solid" id="mainAdminTable">
                <thead>
                    <tr>
                        <th style="width: 10%;">Avatar</th>
                        <th style="width: 35%;">Nama Administrator</th>
                        <th style="width: 35%;">Alamat Email</th>
                        <th style="width: 20%;" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($admins as $index => $item)
                        <tr>
                            <td>
                                <div class="w-10 h-10 rounded-xl bg-[#D9E8DD] border-2 border-[#142E25] text-[#142E25] font-extrabold text-sm d-flex align-items-center justify-content-center hard-shadow-xs">
                                    {{ strtoupper(substr($item->name, 0, 1)) }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-extrabold text-[#142E25] text-sm">{{ $item->name }}</span>
                                    @if ($item->id === auth()->id())
                                        <span class="badge-solid badge-solid-matcha text-[10px]">
                                            <i class="bi bi-person-check-fill me-1"></i>Anda
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="text-[#2D4736] font-mono text-xs font-semibold">
                                    <i class="bi bi-envelope text-[#142E25] me-1"></i>{{ $item->email }}
                                </span>
                            </td>
                            <td class="text-end">
                                @if ($item->id !== auth()->id())
                                    <form action="{{ route('admin.admin-user.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun admin {{ $item->name }}? Tindakan ini tidak dapat dibatalkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-solid-danger btn-solid-press px-2.5 py-1 text-xs" title="Hapus Akun Admin">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="badge-solid badge-solid-forest text-xs">
                                        <i class="bi bi-shield-check me-1"></i>Sesi Aktif
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <div class="w-16 h-16 rounded-2xl bg-[#D9E8DD] border-2 border-[#142E25] d-flex align-items-center justify-content-center text-[#142E25] fs-2 mb-3 hard-shadow-sm">
                                        <i class="bi bi-shield-x"></i>
                                    </div>
                                    <h5 class="fw-extrabold text-[#142E25] mb-1">Belum Ada Admin Tambahan</h5>
                                    <p class="text-[#2D4736] text-sm mb-3">Klik tombol di bawah untuk membuat akun baru.</p>
                                    <a href="{{ route('admin.admin-user.create') }}" class="btn-solid-primary btn-solid-press">
                                        <i class="bi bi-plus-lg me-1"></i>Tambah Admin
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(is_object($admins) && method_exists($admins, 'links') && $admins->hasPages())
            <div class="p-3 border-top-solid bg-[#FAF8F5]">
                {{ $admins->links('partials.pagination-solid', ['paginationLabel' => 'admin']) }}
            </div>
        @endif
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const query = this.value.toLowerCase();
                const rows = document.querySelectorAll('#mainAdminTable tbody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(query) ? '' : 'none';
                });
            });
        }
    });
</script>
@endsection