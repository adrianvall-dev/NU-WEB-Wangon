@extends('layouts.admin')

@section('title', 'Kelola Berita & Warta')

@section('content')
<!-- PAGE TITLE BAR -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-2">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge-solid badge-solid-forest">
                <i class="bi bi-shield-check"></i> ADMIN PORTAL
            </span>
            <span class="text-muted fw-bold" style="font-size: 0.75rem;">•</span>
            <span class="text-muted fw-bold" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Manajemen Konten</span>
        </div>
        <h1 class="fw-black font-serif text-safe mb-1" style="font-size: 1.85rem; color: var(--brand-forest); letter-spacing: -0.015em;">
            Kelola Berita &amp; Warta
        </h1>
        <p class="text-muted text-safe mb-0" style="font-size: 0.86rem; font-weight: 500;">
            Pantau publikasi warta jam'iyyah, kabar ranting/banom, dan artikel warga Nahdliyin.
        </p>
    </div>
    <div>
        <a href="{{ route('admin.berita.create') }}" class="btn-solid-primary btn-solid-press">
            <i class="bi bi-plus-circle-fill"></i>
            <span>Buat Artikel Baru</span>
        </a>
    </div>
</div>

<!-- STATS CARDS -->
<div class="row g-3 mb-4">
    <!-- STAT 1: TOTAL ARTIKEL -->
    <div class="col-md-6 col-lg-3">
        <div class="solid-stat-card d-flex align-items-center gap-3">
            <div class="sidebar-user-avatar-solid" style="width: 46px; height: 46px; background: var(--brand-forest);">
                <i class="bi bi-newspaper fs-5 text-white"></i>
            </div>
            <div>
                <span class="text-muted fs-8 fw-bold text-uppercase d-block mb-1" style="letter-spacing: 0.04em;">Total Artikel</span>
                <h3 class="fw-black font-serif mb-0" style="font-size: 1.6rem; color: var(--brand-forest);">{{ $totalNews ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <!-- STAT 2: TERPUBLIKASI -->
    <div class="col-md-6 col-lg-3">
        <div class="solid-stat-card d-flex align-items-center gap-3">
            <div class="sidebar-user-avatar-solid" style="width: 46px; height: 46px; background: var(--brand-sage);">
                <i class="bi bi-send-check-fill fs-5 text-white"></i>
            </div>
            <div>
                <span class="text-muted fs-8 fw-bold text-uppercase d-block mb-1" style="letter-spacing: 0.04em;">Terpublikasi</span>
                <h3 class="fw-black font-serif mb-0" style="font-size: 1.6rem; color: var(--brand-sage);">{{ $publishedCount ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <!-- STAT 3: DRAFT -->
    <div class="col-md-6 col-lg-3">
        <div class="solid-stat-card d-flex align-items-center gap-3">
            <div class="sidebar-user-avatar-solid" style="width: 46px; height: 46px; background: var(--brand-orange);">
                <i class="bi bi-pencil-square fs-5 text-white"></i>
            </div>
            <div>
                <span class="text-muted fs-8 fw-bold text-uppercase d-block mb-1" style="letter-spacing: 0.04em;">Konsep / Draft</span>
                <h3 class="fw-black font-serif mb-0" style="font-size: 1.6rem; color: var(--brand-orange);">{{ $draftCount ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <!-- MAIN GRAPH MINI CARD -->
    <div class="col-md-6 col-lg-3">
        <div class="solid-stat-card d-flex flex-column justify-content-between p-3" style="height: 100%;">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted fs-8 fw-bold text-uppercase" style="letter-spacing: 0.04em;">Tren 7 Hari</span>
                <span class="badge-solid badge-solid-matcha" style="font-size: 0.65rem;">Riil Data</span>
            </div>
            <div style="height: 44px;">
                <canvas id="publicationChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- MAIN CONTENT CARD & TABLE -->
<div class="card-solid p-3 p-md-4">
    <!-- SEARCH & ACTION BAR -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
        <div class="d-flex align-items-center gap-2">
            <h5 class="fw-bold font-serif mb-0" style="color: var(--brand-forest); font-size: 1.15rem;">Daftar Artikel</h5>
            <span class="badge-solid badge-solid-matcha">
                {{ $totalNews ?? 0 }} Item
            </span>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2" style="max-width: 320px; width: 100%;">
            <div class="position-relative w-100">
                <i class="bi bi-search position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                <input type="text" id="searchInput" class="form-control-solid ps-5 py-2" placeholder="Cari judul warta...">
            </div>
        </div>
    </div>

    <!-- SOLID TABLE CONTAINER -->
    <div class="table-solid-wrapper table-responsive">
        <table class="table-solid" id="mainNewsTable">
            <thead>
                <tr>
                    <th style="width: 45%;">Artikel</th>
                    <th style="width: 18%;">Status</th>
                    <th style="width: 25%;">Tanggal Pembuatan</th>
                    <th style="width: 12%;" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($newsList as $index => $item)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="sidebar-user-avatar-solid" style="width: 40px; height: 40px; border-radius: 10px; background: var(--brand-sand); color: var(--brand-forest);">
                                    <i class="bi bi-file-earmark-text-fill fs-5"></i>
                                </div>
                                <div class="min-w-0">
                                    <h6 class="mb-1 fw-bold text-truncate" style="max-width: 320px; font-size: 0.9rem; color: var(--brand-forest);" title="{{ $item->title }}">
                                        {{ $item->title }}
                                    </h6>
                                    <span class="text-muted d-block" style="font-size: 0.74rem;">
                                        Kategori: <strong style="color: var(--brand-forest);">{{ \App\Support\Organisasi::badgeLabel($item->kategori) }}</strong>
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if ($item->status === 'published')
                                <span class="badge-solid badge-solid-forest">
                                    <i class="bi bi-check-circle-fill"></i> Published
                                </span>
                            @else
                                <span class="badge-solid badge-solid-yellow">
                                    <i class="bi bi-clock-history"></i> Draft
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="d-inline-flex align-items-center gap-2 fw-bold" style="white-space: nowrap; font-size: 0.8rem; color: var(--brand-forest);">
                                <i class="bi bi-calendar3 text-muted"></i>
                                <span>{{ $item->created_at ? $item->created_at->format('d M Y, H:i') : '-' }}</span>
                            </div>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-2">
                                <a href="{{ route('admin.berita.edit', $item->id) }}" class="btn-solid-light btn-solid-sm btn-solid-press" title="Edit Berita">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('admin.berita.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus berita ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-solid-danger btn-solid-sm btn-solid-press" title="Hapus Berita">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="py-4">
                                <div class="sidebar-user-avatar-solid mx-auto mb-3" style="width: 56px; height: 56px; background: var(--brand-sand); color: var(--brand-forest);">
                                    <i class="bi bi-folder2-open fs-3"></i>
                                </div>
                                <h6 class="fw-bold mb-1 font-serif" style="color: var(--brand-forest);">Belum Ada Data Berita</h6>
                                <p class="text-muted fs-8 mb-3">Klik tombol "Buat Artikel Baru" di atas untuk menambahkan konten pertama.</p>
                                <a href="{{ route('admin.berita.create') }}" class="btn-solid-primary btn-solid-sm btn-solid-press">
                                    + Tambah Berita
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
        {{ $newsList->links('partials.pagination-solid', ['paginationLabel' => 'warta']) }}
    </div>
</div>

<!-- CHART.JS INTEGRATION -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Live Search Input Filter
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const query = this.value.toLowerCase();
                const rows = document.querySelectorAll('#mainNewsTable tbody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(query) ? '' : 'none';
                });
            });
        }

        // Dynamic Chart JS dengan Data Riil dari Database
        const ctx = document.getElementById('publicationChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels ?? []),
                    datasets: [{
                        label: 'Artikel Baru',
                        data: @json($chartData ?? []),
                        borderColor: '#142E25',
                        borderWidth: 2,
                        pointBackgroundColor: '#142E25',
                        fill: true,
                        backgroundColor: 'rgba(20, 46, 37, 0.15)',
                        tension: 0.35
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' Artikel';
                                }
                            }
                        }
                    },
                    scales: {
                        x: { display: false },
                        y: { display: false, beginAtZero: true }
                    }
                }
            });
        }
    });
</script>
@endsection