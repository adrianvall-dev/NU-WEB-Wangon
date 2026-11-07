@extends('layouts.admin')

@section('title', 'Detail Pendaftaran: ' . $anggota->nama)

@section('content')
<!-- TOP NAVIGATION & STATUS BAR -->
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.pendaftaran-anggota.index') }}" class="btn-solid-light btn-solid-sm btn-solid-press">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Daftar</span>
        </a>
        <div>
            <h1 class="fw-black font-serif text-safe mb-0" style="font-size: 1.5rem; color: var(--brand-forest); letter-spacing: -0.01em;">
                Detail Berkas Pendaftaran
            </h1>
            <p class="text-muted text-safe mb-0 fs-8">Verifikasi identitas dan kelayakan permohonan kader jam'iyyah.</p>
        </div>
    </div>

    <!-- STATUS INDICATOR -->
    <div>
        @if ($anggota->status_pendaftaran === 'pending')
            <span class="badge-solid badge-solid-yellow" style="font-size: 0.8rem; padding: 6px 14px;">
                <i class="bi bi-hourglass-split"></i> Menunggu Verifikasi
            </span>
        @elseif ($anggota->status_pendaftaran === 'approved')
            <span class="badge-solid badge-solid-forest" style="font-size: 0.8rem; padding: 6px 14px;">
                <i class="bi bi-check-circle-fill"></i> Disetujui
            </span>
        @else
            <span class="badge-solid badge-solid-danger" style="font-size: 0.8rem; padding: 6px 14px;">
                <i class="bi bi-x-circle-fill"></i> Ditolak
            </span>
        @endif
    </div>
</div>

<!-- MAIN DETAIL CARD -->
<div class="card-solid p-4 p-md-5 mb-5">
    <div class="row g-4">
        <!-- LEFT SIDEBAR: PROFILE FOTO & QUICK INFO -->
        <div class="col-lg-4 col-xl-3">
            <div class="card-solid-cream p-4 text-center h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="mb-3 position-relative d-inline-block">
                        @if ($anggota->foto)
                            <img src="{{ asset('storage/' . $anggota->foto) }}" class="rounded-3 border-2 border-dark" style="width: 140px; height: 160px; object-fit: cover;" alt="{{ $anggota->nama }}">
                        @else
                            <div class="sidebar-user-avatar-solid mx-auto" style="width: 140px; height: 160px; border-radius: 12px; font-size: 3rem; background: var(--brand-forest); color: #FFFFFF;">
                                {{ strtoupper(substr($anggota->nama, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    
                    <span class="badge-solid badge-solid-forest mb-2">FOTO PENDAFTAR</span>

                    <h5 class="fw-bold font-serif text-safe mb-1" style="color: var(--brand-forest); font-size: 1.15rem;">
                        {{ $anggota->nama }}
                    </h5>
                    <p class="text-muted fs-8 mb-3">
                        Organisasi: <strong style="color: var(--brand-forest);">{{ \App\Support\Organisasi::badgeLabel($anggota->organisasi) }}</strong>
                    </p>
                </div>

                <div class="d-flex flex-column gap-2 text-start pt-3 border-top border-2 border-dark">
                    <div class="p-2 card-solid bg-white">
                        <span class="text-muted fs-8 fw-bold text-uppercase d-block mb-1">Posisi Diajukan</span>
                        @if ($anggota->posisi_pendaftaran === 'ketua')
                            <span class="badge-solid badge-solid-yellow" style="font-size: 0.72rem;">
                                <i class="bi bi-star-fill text-warning me-1"></i> Calon Ketua
                            </span>
                        @elseif ($anggota->posisi_pendaftaran === 'wakil_ketua')
                            <span class="badge-solid badge-solid-forest" style="font-size: 0.72rem;">
                                <i class="bi bi-award-fill text-success me-1"></i> Calon Wakil Ketua
                            </span>
                        @else
                            <span class="badge-solid badge-solid-light" style="font-size: 0.72rem;">
                                Anggota Biasa
                            </span>
                        @endif
                    </div>

                    <div class="p-2 card-solid bg-white">
                        <span class="text-muted fs-8 fw-bold text-uppercase d-block mb-1">Kode Pendaftaran</span>
                        <span class="fw-bold font-monospace" style="color: var(--brand-forest); font-size: 0.85rem;">
                            {{ $anggota->kode_pendaftaran }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT CONTENT: DETAILED GRID DATA -->
        <div class="col-lg-8 col-xl-9 d-flex flex-column justify-content-between ps-lg-3">
            <div>
                <div class="mb-4">
                    <h5 class="fw-bold font-serif mb-1" style="color: var(--brand-forest); font-size: 1.25rem;">Data Lengkap Pemohon</h5>
                    <p class="text-muted fs-8 mb-0">Pastikan data diri pemohon sesuai dengan arsip kependudukan resmi.</p>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card-solid-cream p-3">
                            <span class="text-muted fs-8 fw-bold text-uppercase d-block mb-1">Nama Lengkap</span>
                            <p class="fw-bold mb-0" style="color: var(--brand-forest); font-size: 0.95rem;">{{ $anggota->nama }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card-solid-cream p-3">
                            <span class="text-muted fs-8 fw-bold text-uppercase d-block mb-1">Nomor Induk Kependudukan (NIK)</span>
                            <p class="fw-bold mb-0 font-monospace" style="color: var(--brand-forest); font-size: 0.95rem;">{{ $anggota->nik }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card-solid-cream p-3">
                            <span class="text-muted fs-8 fw-bold text-uppercase d-block mb-1">Organisasi / Banom</span>
                            <p class="fw-bold mb-0" style="color: var(--brand-forest); font-size: 0.95rem;">{{ \App\Support\Organisasi::fullName($anggota->organisasi) }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card-solid-cream p-3">
                            <span class="text-muted fs-8 fw-bold text-uppercase d-block mb-1">No. HP / WhatsApp</span>
                            <p class="fw-bold mb-0" style="color: var(--brand-forest); font-size: 0.95rem;">
                                <i class="bi bi-whatsapp text-success me-1"></i>{{ $anggota->no_hp }}
                            </p>
                        </div>
                    </div>

                    <!-- NOTICE CALON KETUA -->
                    @if ($anggota->posisi_pendaftaran === 'ketua')
                        @php
                            $org = \App\Models\Organisasi::where('slug', $anggota->organisasi)->with('ketua')->first();
                            $hasExistingKetua = $org && $org->hasActiveKetua() && $org->getActiveKetua()?->id !== $anggota->id;
                            $existingKetuaName = $hasExistingKetua ? $org->getActiveKetua()?->nama : null;
                        @endphp
                        <div class="col-12">
                            @if ($hasExistingKetua)
                                <div class="alert-solid-danger p-3 mb-0">
                                    <strong>Peringatan Calon Ketua:</strong> Organisasi <strong>{{ $org->nama }}</strong> saat ini sudah memiliki ketua aktif: <strong>{{ $existingKetuaName }}</strong>. Jika permohonan ini disetujui, pemohon akan ditetapkan sebagai Ketua dan ketua sebelumnya akan disesuaikan menjadi Anggota.
                                </div>
                            @else
                                <div class="alert-solid-success p-3 mb-0">
                                    <strong>Pemberitahuan Calon Ketua:</strong> Organisasi <strong>{{ $org ? $org->nama : \App\Support\Organisasi::fullName($anggota->organisasi) }}</strong> belum memiliki ketua aktif. Apabila disetujui, pemohon akan ditetapkan sebagai Ketua Organisasi resmi.
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- NOTICE CALON WAKIL KETUA -->
                    @if ($anggota->posisi_pendaftaran === 'wakil_ketua')
                        @php
                            $org = \App\Models\Organisasi::where('slug', $anggota->organisasi)->with('wakilKetua')->first();
                            $hasExistingWakilKetua = $org && $org->hasActiveWakilKetua() && $org->getActiveWakilKetua()?->id !== $anggota->id;
                            $existingWakilKetuaName = $hasExistingWakilKetua ? $org->getActiveWakilKetua()?->nama : null;
                        @endphp
                        <div class="col-12">
                            @if ($hasExistingWakilKetua)
                                <div class="alert-solid-danger p-3 mb-0">
                                    <strong>Peringatan Calon Wakil Ketua:</strong> Organisasi <strong>{{ $org->nama }}</strong> saat ini sudah memiliki wakil ketua aktif: <strong>{{ $existingWakilKetuaName }}</strong>. Jika permohonan ini disetujui, pemohon akan ditetapkan sebagai Wakil Ketua dan wakil ketua sebelumnya akan disesuaikan menjadi Anggota.
                                </div>
                            @else
                                <div class="alert-solid-success p-3 mb-0">
                                    <strong>Pemberitahuan Calon Wakil Ketua:</strong> Organisasi <strong>{{ $org ? $org->nama : \App\Support\Organisasi::fullName($anggota->organisasi) }}</strong> belum memiliki wakil ketua aktif. Apabila disetujui, pemohon akan ditetapkan sebagai Wakil Ketua Organisasi resmi.
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="col-12">
                        <div class="card-solid-cream p-3">
                            <span class="text-muted fs-8 fw-bold text-uppercase d-block mb-1">Alamat Domisili Lengkap</span>
                            <p class="fw-bold mb-0" style="color: var(--brand-forest); line-height: 1.5;">{{ $anggota->alamat }}</p>
                        </div>
                    </div>

                    @if ($anggota->status_pendaftaran === 'approved' && $anggota->nomor_anggota)
                        <div class="col-12">
                            <div class="card-solid p-3" style="background: var(--brand-matcha);">
                                <span class="fw-bold fs-8 text-uppercase d-block mb-1" style="color: var(--brand-forest);">Nomor Anggota Resmi (KARTANU)</span>
                                <p class="fw-black font-monospace mb-0" style="color: var(--brand-forest); font-size: 1.25rem;">
                                    {{ $anggota->nomor_anggota }}
                                </p>
                            </div>
                        </div>
                    @endif

                    @if ($anggota->status_pendaftaran === 'rejected' && $anggota->alasan_penolakan)
                        <div class="col-12">
                            <div class="card-solid p-3" style="background: var(--brand-danger-bg);">
                                <span class="fw-bold fs-8 text-uppercase d-block mb-1 text-danger">Alasan Penolakan Berkas</span>
                                <p class="fw-bold mb-0 text-danger" style="line-height: 1.4;">{{ $anggota->alasan_penolakan }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- ACTION BUTTON FOOTER -->
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-4 pt-4 border-top border-2 border-dark">
                <a href="{{ route('admin.pendaftaran-anggota.index') }}" class="btn-solid-light btn-solid-press">
                    <i class="bi bi-arrow-left"></i>
                    <span>Kembali ke Daftar</span>
                </a>

                <div class="d-flex align-items-center gap-2">
                    @if ($anggota->status_pendaftaran === 'pending')
                        <button type="button" class="btn-solid-danger btn-solid-press" data-bs-toggle="modal" data-bs-target="#modalTolak">
                            <i class="bi bi-x-circle-fill"></i>
                            <span>Tolak Berkas</span>
                        </button>

                        <form action="{{ route('admin.pendaftaran-anggota.approve', $anggota->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-solid-primary btn-solid-press">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Setujui Pendaftaran</span>
                            </button>
                        </form>
                    @elseif ($anggota->status_pendaftaran === 'approved')
                        <a href="{{ route('admin.pendaftaran-anggota.cetak', $anggota->id) }}" class="btn-solid-yellow btn-solid-press" target="_blank">
                            <i class="bi bi-printer-fill"></i>
                            <span>Cetak KARTANU</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TOLAK PENDAFTARAN -->
@if ($anggota->status_pendaftaran === 'pending')
    <div class="modal fade" id="modalTolak" tabindex="-1" aria-labelledby="modalTolakLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content card-solid p-0 overflow-hidden">
                <form action="{{ route('admin.pendaftaran-anggota.reject', $anggota->id) }}" method="POST">
                    @csrf
                    <div class="modal-header border-bottom border-2 border-dark p-3" style="background: var(--brand-sand);">
                        <h5 class="modal-title fw-black font-serif mb-0" id="modalTolakLabel" style="color: var(--brand-forest);">Tolak Pendaftaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="text-muted fs-8 mb-3">Tuliskan alasan penolakan berkas agar pendaftar dapat mengetahui perbaikan yang diperlukan.</p>
                        <label class="form-label-solid">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="alasan_penolakan" rows="4" class="form-control-solid" placeholder="Contoh: Foto KTP tidak terbaca jelas / Domisili di luar wilayah..." required></textarea>
                    </div>
                    <div class="modal-footer border-top border-2 border-dark p-3" style="background: var(--brand-cream);">
                        <button type="button" class="btn-solid-light btn-solid-press" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-solid-danger btn-solid-press">
                            <i class="bi bi-x-circle-fill"></i> Tolak Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection