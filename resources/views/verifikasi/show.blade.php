@extends('layouts.app')

@section('title', 'Verifikasi Keanggotaan - Organisasi NU Wangon')

@section('content')
<div class="py-4 py-md-5">
    <div class="container text-center" style="max-width: 480px;">
        
        <div class="mb-4 reveal-on-scroll">
            <div data-text-animate="blurInUp" class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-light border text-muted fs-xs fw-semibold mb-2">
                <i class="bi bi-qr-code-scan text-success-nu"></i> Hasil Pemindaian QR Code
            </div>
            <h1 data-text-animate="blurInUp" class="fw-bold text-dark fs-4 mb-1">Verifikasi Keaslian Kartu</h1>
            <p class="text-muted small mb-0">Organisasi NU Wangon</p>
        </div>

        <div class="card border-0 shadow-2xs rounded-4 bg-white overflow-hidden reveal-on-scroll delay-100">
            <div class="card-body p-3 p-sm-4 p-md-5">
                @if (!$anggota || $anggota->status_pendaftaran !== 'approved')
                    <div class="my-2 reveal-scale">
                        <div class="d-inline-flex p-3 rounded-circle bg-danger-subtle text-danger mb-3">
                            <i class="bi bi-x-circle-fill display-5"></i>
                        </div>
                        <h2 class="fw-bold text-danger fs-5 mb-2">Kartu Tidak Terdaftar</h2>
                        <p class="text-muted small px-3 mb-4">Kode verifikasi ini tidak ditemukan dalam database resmi atau status pendaftaran belum disetujui.</p>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fs-sm fw-medium">
                            Kembali ke Beranda
                        </a>
                    </div>

                @elseif ($anggota->status !== 'aktif')
                    <div class="my-2 reveal-scale">
                        <div class="d-inline-flex p-3 rounded-circle bg-warning-subtle text-warning-emphasis mb-3">
                            <i class="bi bi-exclamation-triangle-fill display-5"></i>
                        </div>
                        <h2 class="fw-bold text-warning-emphasis fs-5 mb-2">Keanggotaan Tidak Aktif</h2>
                        <p class="text-muted small px-3 mb-4">Data ditemukan, namun status keanggotaan yang bersangkutan saat ini berstatus non-aktif.</p>
                        
                        <div class="card bg-light border-0 rounded-3 p-3 text-start mb-4 fs-sm">
                            <div class="d-flex justify-content-between py-1 border-bottom">
                                <span class="text-muted">Nama</span>
                                <span class="fw-semibold text-dark">{{ $anggota->nama }}</span>
                            </div>
                            <div class="d-flex justify-content-between pt-1">
                                <span class="text-muted">Nomor Anggota</span>
                                <span class="font-monospace text-dark">{{ $anggota->nomor_anggota }}</span>
                            </div>
                        </div>

                        <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fs-sm fw-medium">
                            Kembali ke Beranda
                        </a>
                    </div>

                @else
                    <div class="my-2 reveal-scale">
                        <div class="d-inline-flex p-3 rounded-circle bg-success-subtle text-success-nu mb-3">
                            <i class="bi bi-patch-check-fill display-4"></i>
                        </div>
                        <h2 class="fw-bold text-dark fs-4 mb-1">Anggota Sah & Terverifikasi</h2>
                        <span class="badge bg-success-subtle text-success-nu rounded-pill px-3 py-1 fs-xs fw-semibold mb-4">
                            Status: Aktif Resmi
                        </span>

                        @if ($anggota->foto)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $anggota->foto) }}" class="rounded-circle border shadow-2xs object-fit-cover" style="width: 80px; height: 80px;" alt="{{ $anggota->nama }}">
                            </div>
                        @endif

                        <div class="card bg-light border-0 rounded-4 p-3.5 text-start mb-4 fs-sm">
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Nama Anggota</span>
                                <strong class="text-dark">{{ $anggota->nama }}</strong>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Nomor Anggota</span>
                                <span class="font-monospace fw-bold text-dark">{{ $anggota->nomor_anggota }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Organisasi</span>
                                <span class="fw-semibold text-dark">{{ ucfirst($anggota->organisasi) }} NU Wangon</span>
                            </div>
                            <div class="d-flex justify-content-between pt-2">
                                <span class="text-muted">Keabsahan</span>
                                <span class="text-success-nu fw-bold d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-check-circle-fill"></i> Terdaftar Resmi
                                </span>
                            </div>
                        </div>

                        <div class="p-2.5 rounded-3 bg-success-subtle text-success-nu fs-xs mb-4">
                            <i class="bi bi-shield-check me-1"></i> Data diverifikasi langsung melalui database Organisasi NU Wangon.
                        </div>

                        <a href="{{ route('home') }}" class="btn btn-nu-primary rounded-pill px-4 py-2 fs-sm fw-semibold shadow-sm">
                            Halaman Utama
                        </a>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

<style>
    .fs-xs { font-size: 0.75rem !important; }
    .fs-sm { font-size: 0.875rem !important; }
</style>
@endsection