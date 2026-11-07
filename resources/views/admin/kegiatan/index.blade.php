@extends('layouts.admin')

@section('title', 'Kelola Kegiatan Dakwah & Agenda')

@section('content')
<!-- PAGE TITLE BAR -->
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
            Kelola Kegiatan Dakwah
        </h1>
        <p class="text-muted text-safe mb-0" style="font-size: 0.86rem; font-weight: 500;">
            Pantau jadwal kegiatan, status acara, lokasi pemetaan, dan agenda jam'iyyah.
        </p>
    </div>
    <div>
        <a href="{{ route('admin.kegiatan.create') }}" class="btn-solid-primary btn-solid-press">
            <i class="bi bi-plus-circle-fill"></i>
            <span>Tambah Kegiatan Baru</span>
        </a>
    </div>
</div>

<!-- STATS SECTION -->
<div class="row g-3 mb-4">
    <!-- STAT 1: TOTAL KEGIATAN -->
    <div class="col-md-6 col-lg-3">
        <div class="solid-stat-card d-flex align-items-center gap-3">
            <div class="sidebar-user-avatar-solid" style="width: 46px; height: 46px; background: var(--brand-forest);">
                <i class="bi bi-calendar-check fs-5 text-white"></i>
            </div>
            <div>
                <span class="text-muted fs-8 fw-bold text-uppercase d-block mb-1" style="letter-spacing: 0.04em;">Total Kegiatan</span>
                <h3 class="fw-black font-serif mb-0" style="font-size: 1.6rem; color: var(--brand-forest);">{{ $totalKegiatan ?? $kegiatanList->total() }}</h3>
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
                <h3 class="fw-black font-serif mb-0" style="font-size: 1.6rem; color: var(--brand-sage);">
                    {{ $publishedCount ?? $kegiatanList->where('status', 'published')->count() }}
                </h3>
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
                <h3 class="fw-black font-serif mb-0" style="font-size: 1.6rem; color: var(--brand-orange);">
                    {{ $draftCount ?? $kegiatanList->where('status', '!=', 'published')->count() }}
                </h3>
            </div>
        </div>
    </div>

    <!-- MAIN GRAPH MINI CARD -->
    <div class="col-md-6 col-lg-3">
        <div class="solid-stat-card d-flex flex-column justify-content-between p-3" style="height: 100%;">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted fs-8 fw-bold text-uppercase" style="letter-spacing: 0.04em;">Tren Kegiatan</span>
                <span class="badge-solid badge-solid-matcha" style="font-size: 0.65rem;">Riil Data</span>
            </div>
            <div style="height: 44px;">
                <canvas id="kegiatanChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- MAIN CONTENT CARD & TABLE -->
<div class="card-solid p-3 p-md-4">
    <!-- SEARCH & ACTION BAR -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
        <div class="d-flex align-items-center gap-2">
            <h5 class="fw-bold font-serif mb-0" style="color: var(--brand-forest); font-size: 1.15rem;">Daftar Kegiatan</h5>
            <span class="badge-solid badge-solid-matcha">
                {{ $kegiatanList->total() }} Item
            </span>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2" style="max-width: 320px; width: 100%;">
            <div class="position-relative w-100">
                <i class="bi bi-search position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                <input type="text" id="searchInput" class="form-control-solid ps-5 py-2" placeholder="Cari agenda kegiatan...">
            </div>
        </div>
    </div>

    <!-- SOLID TABLE CONTAINER -->
    <div class="table-solid-wrapper table-responsive">
        <table class="table-solid" id="mainKegiatanTable">
            <thead>
                <tr>
                    <th style="width: 36%;">Judul Kegiatan</th>
                    <th style="width: 16%;">Status</th>
                    <th style="width: 18%;">Tanggal &amp; Waktu</th>
                    <th style="width: 18%;">Lokasi</th>
                    <th style="width: 12%;" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kegiatanList as $index => $item)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="sidebar-user-avatar-solid" style="width: 40px; height: 40px; border-radius: 10px; background: var(--brand-sand); color: var(--brand-forest);">
                                    <i class="bi bi-calendar-event-fill fs-5"></i>
                                </div>
                                <div class="min-w-0">
                                    <h6 class="mb-1 fw-bold text-truncate" style="max-width: 280px; font-size: 0.9rem; color: var(--brand-forest);" title="{{ $item->title }}">
                                        {{ $item->title }}
                                    </h6>
                                    <span class="text-muted d-block" style="font-size: 0.74rem;">
                                        Banom: <strong style="color: var(--brand-forest);">{{ \App\Support\Organisasi::badgeLabel($item->kategori) }}</strong>
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if ($item->status === 'published')
                                @if (method_exists($item, 'isSelesai') && $item->isSelesai())
                                    <span class="badge-solid badge-solid-light">
                                        <i class="bi bi-check-all"></i> Selesai
                                    </span>
                                @elseif (method_exists($item, 'isBerlangsung') && $item->isBerlangsung())
                                    <span class="badge-solid badge-solid-yellow">
                                        <i class="bi bi-broadcast"></i> Berlangsung
                                    </span>
                                @else
                                    <span class="badge-solid badge-solid-forest">
                                        <i class="bi bi-calendar-check"></i> Akan Datang
                                    </span>
                                @endif
                            @else
                                <span class="badge-solid badge-solid-orange">
                                    <i class="bi bi-clock-history"></i> Draft
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold" style="font-size: 0.82rem; color: var(--brand-forest);">
                                <i class="bi bi-calendar3 text-muted me-1"></i>
                                {{ $item->event_date ? \Carbon\Carbon::parse($item->event_date)->translatedFormat('d M Y') : '-' }}
                            </div>
                            <div class="text-muted" style="font-size: 0.72rem;">
                                <i class="bi bi-clock me-1"></i>
                                {{ !empty($item->start_time) ? substr($item->start_time, 0, 5) : '-' }}
                                @if(!empty($item->end_time))
                                    s/d {{ substr($item->end_time, 0, 5) }} WIB
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="text-truncate fw-semibold" style="max-width: 180px; font-size: 0.8rem; color: var(--brand-forest);" title="{{ $item->location ?? 'Kantor MWC NU Wangon' }}">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                {{ $item->location ?? 'Kantor MWC NU Wangon' }}
                            </div>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-2">
                                <a href="{{ route('admin.galeri.show', $item->id) }}" class="btn-solid-light btn-solid-sm btn-solid-press" title="Kelola Dokumentasi Foto & Video">
                                    <i class="bi bi-camera-fill"></i>
                                </a>
                                <a href="{{ route('admin.kegiatan.edit', $item->id) }}" class="btn-solid-light btn-solid-sm btn-solid-press" title="Edit Kegiatan">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('admin.kegiatan.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus agenda kegiatan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-solid-danger btn-solid-sm btn-solid-press" title="Hapus Kegiatan">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="py-4">
                                <div class="sidebar-user-avatar-solid mx-auto mb-3" style="width: 56px; height: 56px; background: var(--brand-sand); color: var(--brand-forest);">
                                    <i class="bi bi-calendar-x fs-3"></i>
                                </div>
                                <h6 class="fw-bold mb-1 font-serif" style="color: var(--brand-forest);">Belum Ada Agenda Kegiatan</h6>
                                <p class="text-muted fs-8 mb-3">Klik tombol "Tambah Kegiatan Baru" untuk menjadwalkan agenda dakwah berikutnya.</p>
                                <a href="{{ route('admin.kegiatan.create') }}" class="btn-solid-primary btn-solid-sm btn-solid-press">
                                    + Tambah Kegiatan
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
        {{ $kegiatanList->links('partials.pagination-solid', ['paginationLabel' => 'agenda kegiatan']) }}
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
                const rows = document.querySelectorAll('#mainKegiatanTable tbody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(query) ? '' : 'none';
                });
            });
        }

        // Dynamic Chart JS dengan Data Riil dari Database
        const ctx = document.getElementById('kegiatanChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels ?? []),
                    datasets: [{
                        label: 'Kegiatan Baru',
                        data: @json($chartData ?? []),
                        borderColor: '#2D4736',
                        borderWidth: 2,
                        pointBackgroundColor: '#2D4736',
                        fill: true,
                        backgroundColor: 'rgba(45, 71, 54, 0.15)',
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
                                    return context.parsed.y + ' Kegiatan';
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