@extends('layouts.admin')

@section('title', 'Pendaftaran Anggota (KARTANU)')

@section('content')
<!-- PAGE TITLE BAR -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-2">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge-solid badge-solid-forest">
                <i class="bi bi-shield-check"></i> ADMIN PORTAL
            </span>
            <span class="text-muted fw-bold" style="font-size: 0.75rem;">•</span>
            <span class="text-muted fw-bold" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Verifikasi KARTANU</span>
        </div>
        <h1 class="fw-black font-serif text-safe mb-1" style="font-size: 1.85rem; color: var(--brand-forest); letter-spacing: -0.015em;">
            Pendaftaran Anggota
        </h1>
        <p class="text-muted text-safe mb-0" style="font-size: 0.86rem; font-weight: 500;">
            Kelola berkas permohonan pendaftaran, verifikasi data pemohon, dan cetak kartu KARTANU.
        </p>
    </div>
</div>

<!-- TOP STATS SECTION -->
<div class="row g-3 mb-4">
    <!-- TOTAL PENDAFTAR -->
    <div class="col-md-6 col-lg-3">
        <div class="solid-stat-card d-flex align-items-center gap-3">
            <div class="sidebar-user-avatar-solid" style="width: 46px; height: 46px; background: var(--brand-forest);">
                <i class="bi bi-folder-fill fs-5 text-white"></i>
            </div>
            <div>
                <span class="text-muted fs-8 fw-bold text-uppercase d-block mb-1" style="letter-spacing: 0.04em;">Total Berkas</span>
                <h3 class="fw-black font-serif mb-0" style="font-size: 1.6rem; color: var(--brand-forest);">{{ $counts['total'] ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <!-- PENDING -->
    <div class="col-md-6 col-lg-3">
        <div class="solid-stat-card d-flex align-items-center gap-3">
            <div class="sidebar-user-avatar-solid" style="width: 46px; height: 46px; background: var(--brand-orange);">
                <i class="bi bi-hourglass-split fs-5 text-white"></i>
            </div>
            <div>
                <span class="text-muted fs-8 fw-bold text-uppercase d-block mb-1" style="letter-spacing: 0.04em;">Menunggu Verifikasi</span>
                <h3 class="fw-black font-serif mb-0" style="font-size: 1.6rem; color: var(--brand-orange);">{{ $counts['pending'] ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <!-- APPROVED -->
    <div class="col-md-6 col-lg-3">
        <div class="solid-stat-card d-flex align-items-center gap-3">
            <div class="sidebar-user-avatar-solid" style="width: 46px; height: 46px; background: var(--brand-sage);">
                <i class="bi bi-check-circle-fill fs-5 text-white"></i>
            </div>
            <div>
                <span class="text-muted fs-8 fw-bold text-uppercase d-block mb-1" style="letter-spacing: 0.04em;">Disetujui</span>
                <h3 class="fw-black font-serif mb-0" style="font-size: 1.6rem; color: var(--brand-sage);">{{ $counts['approved'] ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <!-- REJECTED -->
    <div class="col-md-6 col-lg-3">
        <div class="solid-stat-card d-flex align-items-center gap-3">
            <div class="sidebar-user-avatar-solid" style="width: 46px; height: 46px; background: var(--brand-danger); color: #FFFFFF;">
                <i class="bi bi-x-circle-fill fs-5"></i>
            </div>
            <div>
                <span class="text-muted fs-8 fw-bold text-uppercase d-block mb-1" style="letter-spacing: 0.04em;">Ditolak</span>
                <h3 class="fw-black font-serif mb-0" style="font-size: 1.6rem; color: #DC2626;">{{ $counts['rejected'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- MAIN CONTENT CARD & TABLE -->
<div class="card-solid p-3 p-md-4">
    <form id="bulkForm" action="{{ route('admin.pendaftaran-anggota.cetak-massal') }}" method="POST" target="_blank">
        @csrf

        <!-- FILTER & ACTION BAR -->
        <div class="d-flex flex-column gap-3 mb-3">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <!-- STATUS FILTER PILLS -->
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <span class="text-muted fw-bold me-1 fs-8 text-uppercase">Status:</span>

                    <a href="{{ route('admin.pendaftaran-anggota.index', array_filter(['status' => 'pending', 'posisi' => $posisi])) }}" 
                       class="btn-solid-light btn-solid-sm {{ $status === 'pending' ? 'btn-solid-primary' : '' }} btn-solid-press">
                        <span>Pending</span>
                        <span class="badge-solid {{ $status === 'pending' ? 'badge-solid-yellow' : 'badge-solid-forest' }}" style="font-size: 0.65rem; padding: 1px 6px;">
                            {{ $counts['pending'] ?? 0 }}
                        </span>
                    </a>

                    <a href="{{ route('admin.pendaftaran-anggota.index', array_filter(['status' => 'approved', 'posisi' => $posisi])) }}" 
                       class="btn-solid-light btn-solid-sm {{ $status === 'approved' ? 'btn-solid-primary' : '' }} btn-solid-press">
                        <span>Disetujui</span>
                        <span class="badge-solid {{ $status === 'approved' ? 'badge-solid-yellow' : 'badge-solid-forest' }}" style="font-size: 0.65rem; padding: 1px 6px;">
                            {{ $counts['approved'] ?? 0 }}
                        </span>
                    </a>

                    <a href="{{ route('admin.pendaftaran-anggota.index', array_filter(['status' => 'rejected', 'posisi' => $posisi])) }}" 
                       class="btn-solid-light btn-solid-sm {{ $status === 'rejected' ? 'btn-solid-primary' : '' }} btn-solid-press">
                        <span>Ditolak</span>
                        <span class="badge-solid {{ $status === 'rejected' ? 'badge-solid-yellow' : 'badge-solid-forest' }}" style="font-size: 0.65rem; padding: 1px 6px;">
                            {{ $counts['rejected'] ?? 0 }}
                        </span>
                    </a>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    @if ($status === 'approved')
                        <button type="submit" id="btnBulkPrint" class="btn-solid-yellow btn-solid-sm btn-solid-press" disabled>
                            <i class="bi bi-printer-fill"></i>
                            <span>Cetak Massal Terpilih (<span id="selectedCount">0</span>)</span>
                        </button>
                    @endif

                    <div class="position-relative" style="min-width: 240px;">
                        <i class="bi bi-search position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" id="searchInput" class="form-control-solid ps-5 py-1.5" placeholder="Cari pemohon...">
                    </div>
                </div>
            </div>

            <!-- POSISI / JABATAN FILTER PILLS -->
            <div class="d-flex flex-wrap gap-2 align-items-center pt-2 border-top border-light">
                <span class="text-muted fw-bold me-1 fs-8 text-uppercase">Posisi:</span>

                <a href="{{ route('admin.pendaftaran-anggota.index', array_filter(['status' => $status])) }}" 
                   class="btn-solid-light btn-solid-sm {{ empty($posisi) ? 'btn-solid-primary' : '' }} btn-solid-press">
                    <span>Semua Posisi</span>
                    <span class="badge-solid {{ empty($posisi) ? 'badge-solid-yellow' : 'badge-solid-forest' }}" style="font-size: 0.65rem; padding: 1px 6px;">
                        {{ $counts['total'] ?? 0 }}
                    </span>
                </a>

                <a href="{{ route('admin.pendaftaran-anggota.index', array_filter(['status' => $status, 'posisi' => 'ketua'])) }}" 
                   class="btn-solid-light btn-solid-sm {{ $posisi === 'ketua' ? 'btn-solid-primary' : '' }} btn-solid-press">
                    <span>Calon Ketua</span>
                    <span class="badge-solid {{ $posisi === 'ketua' ? 'badge-solid-yellow' : 'badge-solid-forest' }}" style="font-size: 0.65rem; padding: 1px 6px;">
                        {{ $counts['ketua'] ?? 0 }}
                    </span>
                </a>

                <a href="{{ route('admin.pendaftaran-anggota.index', array_filter(['status' => $status, 'posisi' => 'wakil_ketua'])) }}" 
                   class="btn-solid-light btn-solid-sm {{ $posisi === 'wakil_ketua' ? 'btn-solid-primary' : '' }} btn-solid-press">
                    <span>Calon Wakil Ketua</span>
                    <span class="badge-solid {{ $posisi === 'wakil_ketua' ? 'badge-solid-yellow' : 'badge-solid-forest' }}" style="font-size: 0.65rem; padding: 1px 6px;">
                        {{ $counts['wakil_ketua'] ?? 0 }}
                    </span>
                </a>

                <a href="{{ route('admin.pendaftaran-anggota.index', array_filter(['status' => $status, 'posisi' => 'anggota'])) }}" 
                   class="btn-solid-light btn-solid-sm {{ $posisi === 'anggota' ? 'btn-solid-primary' : '' }} btn-solid-press">
                    <span>Anggota Biasa</span>
                    <span class="badge-solid {{ $posisi === 'anggota' ? 'badge-solid-yellow' : 'badge-solid-forest' }}" style="font-size: 0.65rem; padding: 1px 6px;">
                        {{ $counts['anggota'] ?? 0 }}
                    </span>
                </a>
            </div>
        </div>

        <!-- SOLID TABLE CONTAINER -->
        @php
            $pendaftaranItems = $pendaftaranList ?? ($pendaftarans ?? collect());
        @endphp
        <div class="table-solid-wrapper table-responsive">
            <table class="table-solid" id="mainPendaftaranTable">
                <thead>
                    <tr>
                        @if ($status === 'approved')
                            <th style="width: 4%;">
                                <input type="checkbox" id="selectAllCheckbox" class="form-check-input border-2 border-dark">
                            </th>
                        @endif
                        <th style="width: 32%;">Pemohon</th>
                        <th style="width: 18%;">Organisasi / Banom</th>
                        <th style="width: 18%;">Tanggal Daftar</th>
                        <th style="width: 14%;">Status</th>
                        <th style="width: 14%;" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pendaftaranItems as $index => $item)
                        <tr>
                            @if ($status === 'approved')
                                <td>
                                    <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="form-check-input item-checkbox border-2 border-dark">
                                </td>
                            @endif
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="sidebar-user-avatar-solid" style="width: 40px; height: 40px; border-radius: 50%; background: var(--brand-forest); color: #FFFFFF;">
                                        {{ strtoupper(substr($item->nama, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <h6 class="mb-0 fw-bold" style="font-size: 0.9rem; color: var(--brand-forest);">
                                            {{ $item->nama }}
                                        </h6>
                                        <div class="text-muted fs-8 mt-0.5">
                                            <span>NIK: {{ $item->nik ?? '-' }}</span>
                                            @if($item->no_hp)
                                                <span class="ms-1">· <i class="bi bi-whatsapp text-success"></i> {{ $item->no_hp }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-solid badge-solid-matcha" style="font-size: 0.72rem;">
                                    {{ \App\Support\Organisasi::badgeLabel($item->organisasi) }}
                                </span>
                                @if ($item->posisi_pendaftaran === 'ketua')
                                    <span class="badge-solid badge-solid-yellow ms-1" style="font-size: 0.65rem;">
                                        Calon Ketua
                                    </span>
                                @elseif ($item->posisi_pendaftaran === 'wakil_ketua')
                                    <span class="badge-solid badge-solid-forest ms-1" style="font-size: 0.65rem;">
                                        Calon Wakil Ketua
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold" style="font-size: 0.8rem; color: var(--brand-forest);">
                                    <i class="bi bi-calendar3 text-muted me-1"></i>
                                    {{ $item->created_at ? $item->created_at->translatedFormat('d M Y, H:i') : '-' }}
                                </div>
                                <div class="text-muted fs-8 font-monospace">
                                    Kode: {{ $item->kode_pendaftaran }}
                                </div>
                            </td>
                            <td>
                                @if ($item->status_pendaftaran === 'pending')
                                    <span class="badge-solid badge-solid-yellow" style="font-size: 0.68rem;">
                                        <i class="bi bi-hourglass-split"></i> Pending
                                    </span>
                                @elseif ($item->status_pendaftaran === 'approved')
                                    <span class="badge-solid badge-solid-forest" style="font-size: 0.68rem;">
                                        <i class="bi bi-check-circle-fill"></i> Disetujui
                                    </span>
                                @else
                                    <span class="badge-solid badge-solid-danger" style="font-size: 0.68rem;">
                                        <i class="bi bi-x-circle-fill"></i> Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('admin.pendaftaran-anggota.show', $item->id) }}" class="btn-solid-light btn-solid-sm btn-solid-press" title="Rincian Berkas">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    @if ($item->status_pendaftaran === 'approved')
                                        <a href="{{ route('admin.pendaftaran-anggota.cetak', $item->id) }}" target="_blank" class="btn-solid-yellow btn-solid-sm btn-solid-press" title="Cetak KARTANU">
                                            <i class="bi bi-printer-fill"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $status === 'approved' ? 6 : 5 }}" class="text-center py-5">
                                <div class="py-4">
                                    <div class="sidebar-user-avatar-solid mx-auto mb-3" style="width: 56px; height: 56px; background: var(--brand-sand); color: var(--brand-forest);">
                                        <i class="bi bi-inbox fs-3"></i>
                                    </div>
                                    <h6 class="fw-bold mb-1 font-serif" style="color: var(--brand-forest);">Tidak Ada Data Pendaftaran</h6>
                                    <p class="text-muted fs-8 mb-0">Belum ada pemohon pada status kategori pendaftaran ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div class="mt-4 pt-3 border-top border-2 border-dark">
            @if(method_exists($pendaftaranItems, 'links'))
                {{ $pendaftaranItems->appends(request()->query())->links('partials.pagination-solid', ['paginationLabel' => 'berkas pendaftaran']) }}
            @endif
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search Filter
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const query = this.value.toLowerCase();
            const rows = document.querySelectorAll('#mainPendaftaranTable tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }

    // Bulk selection handler
    const selectAll = document.getElementById('selectAllCheckbox');
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const btnBulk = document.getElementById('btnBulkPrint');
    const selectedCount = document.getElementById('selectedCount');

    function updateBulkButton() {
        const checked = document.querySelectorAll('.item-checkbox:checked').length;
        if (selectedCount) selectedCount.textContent = checked;
        if (btnBulk) btnBulk.disabled = checked === 0;
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
            updateBulkButton();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkButton);
    });
});
</script>
@endsection