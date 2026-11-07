@extends('layouts.admin')

@section('title', 'Kelola Galeri & History Dokumentasi')

@section('content')
<!-- PAGE TITLE BAR -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-2">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge-solid badge-solid-forest">
                <i class="bi bi-shield-check"></i> ADMIN PORTAL
            </span>
            <span class="text-muted fw-bold" style="font-size: 0.75rem;">•</span>
            <span class="text-muted fw-bold" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Dokumentasi &amp; Arsip</span>
        </div>
        <h1 class="fw-black font-serif text-safe mb-1" style="font-size: 1.85rem; color: var(--brand-forest); letter-spacing: -0.015em;">
            Galeri &amp; History Kegiatan
        </h1>
        <p class="text-muted text-safe mb-0" style="font-size: 0.86rem; font-weight: 500;">
            Kelola arsip foto &amp; video dokumentasi kegiatan yang terlaksana serta profil organisasi.
        </p>
    </div>
    <div>
        <a href="{{ route('admin.kegiatan.create') }}" class="btn-solid-primary btn-solid-press">
            <i class="bi bi-calendar-plus"></i>
            <span>Tambah Agenda Baru</span>
        </a>
    </div>
</div>

<!-- STATS SECTION -->
<div class="row g-3 mb-4">
    <!-- STAT 1: FOTO DOKUMENTASI -->
    <div class="col-6 col-lg-3">
        <div class="solid-stat-card d-flex align-items-center gap-3">
            <div class="sidebar-user-avatar-solid" style="width: 44px; height: 44px; background: var(--brand-forest);">
                <i class="bi bi-camera-fill fs-5 text-white"></i>
            </div>
            <div>
                <span class="text-muted fs-8 fw-bold text-uppercase d-block mb-1" style="letter-spacing: 0.04em;">Foto Dokumentasi</span>
                <h3 class="fw-black font-serif mb-0" style="font-size: 1.5rem; color: var(--brand-forest);">{{ $totalDokumentasi ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <!-- STAT 2: VIDEO DOKUMENTASI -->
    <div class="col-6 col-lg-3">
        <div class="solid-stat-card d-flex align-items-center gap-3">
            <div class="sidebar-user-avatar-solid" style="width: 44px; height: 44px; background: var(--brand-orange);">
                <i class="bi bi-film fs-5 text-white"></i>
            </div>
            <div>
                <span class="text-muted fs-8 fw-bold text-uppercase d-block mb-1" style="letter-spacing: 0.04em;">Video Dokumentasi</span>
                <h3 class="fw-black font-serif mb-0" style="font-size: 1.5rem; color: var(--brand-orange);">{{ $totalVideo ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <!-- STAT 3: HISTORY SELESAI -->
    <div class="col-6 col-lg-3">
        <div class="solid-stat-card d-flex align-items-center gap-3">
            <div class="sidebar-user-avatar-solid" style="width: 44px; height: 44px; background: var(--brand-sage);">
                <i class="bi bi-calendar-check-fill fs-5 text-white"></i>
            </div>
            <div>
                <span class="text-muted fs-8 fw-bold text-uppercase d-block mb-1" style="letter-spacing: 0.04em;">History Selesai</span>
                <h3 class="fw-black font-serif mb-0" style="font-size: 1.5rem; color: var(--brand-sage);">{{ number_format($totalKegiatanSelesai ?? 0) }}</h3>
            </div>
        </div>
    </div>

    <!-- STAT 4: TAHUN BERDIRI -->
    <div class="col-6 col-lg-3">
        <div class="solid-stat-card d-flex align-items-center gap-3">
            <div class="sidebar-user-avatar-solid" style="width: 44px; height: 44px; background: var(--brand-yellow); color: var(--brand-forest);">
                <i class="bi bi-building fs-5"></i>
            </div>
            <div>
                <span class="text-muted fs-8 fw-bold text-uppercase d-block mb-1" style="letter-spacing: 0.04em;">Tahun Berdiri</span>
                <h3 class="fw-black font-serif mb-0" style="font-size: 1.5rem; color: var(--brand-forest);">{{ $pengaturan->tahun_berdiri ?? 1926 }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- SETTINGS CARD: TAHUN BERDIRI -->
<div class="card-solid-cream p-3 p-md-4 mb-4">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="sidebar-user-avatar-solid" style="width: 44px; height: 44px; background: var(--brand-forest); color: #FFFFFF;">
                <i class="bi bi-gear-wide-connected fs-5"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 mb-0.5">
                    <h6 class="fw-bold font-serif mb-0" style="font-size: 1.05rem; color: var(--brand-forest);">Pengaturan Tahun Berdiri Organisasi</h6>
                    <span class="badge-solid badge-solid-forest" style="font-size: 0.62rem;">PROFIL</span>
                </div>
                <p class="text-muted fs-8 mb-0">Tahun awal khidmat jam'iyyah yang dikalkulasikan di halaman publik.</p>
            </div>
        </div>

        <form action="{{ route('admin.galeri.pengaturan') }}" method="POST" class="m-0">
            @csrf
            <div class="d-flex align-items-center gap-2">
                <input type="number" 
                       name="tahun_berdiri" 
                       class="form-control-solid py-2 text-center font-monospace fw-bold" 
                       style="width: 100px;" 
                       value="{{ old('tahun_berdiri', $pengaturan->tahun_berdiri ?? '1926') }}" 
                       placeholder="1926" 
                       required>
                <button type="submit" class="btn-solid-primary btn-solid-press text-nowrap">
                    <i class="bi bi-check-lg"></i> Simpan
                </button>
            </div>
            @error('tahun_berdiri')
                <div class="text-danger fs-8 mt-1">{{ $message }}</div>
            @enderror
        </form>
    </div>
</div>

<!-- ALBUM DOKUMENTASI GRID -->
<div class="card-solid p-3 p-md-4">
    <!-- FILTER & SEARCH -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <!-- FILTER KATEGORI -->
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="text-muted fw-bold me-1 fs-8 text-uppercase">Filter:</span>
            <a href="{{ route('admin.galeri.index') }}" class="btn-solid-light btn-solid-sm {{ !request('kategori') ? 'btn-solid-primary' : '' }} btn-solid-press">
                Semua Kategori
            </a>
            @foreach(\App\Support\Organisasi::badgeLabelsWithUmum() as $key => $label)
                <a href="{{ route('admin.galeri.index', ['kategori' => $key]) }}" class="btn-solid-light btn-solid-sm {{ request('kategori') === $key ? 'btn-solid-primary' : '' }} btn-solid-press">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- SEARCH INPUT -->
        <form action="{{ route('admin.galeri.index') }}" method="GET" class="d-flex align-items-center gap-2" style="max-width: 320px; width: 100%;">
            @if(request('kategori'))
                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
            @endif
            <div class="position-relative w-100">
                <i class="bi bi-search position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control-solid ps-5 py-2" placeholder="Cari kegiatan...">
            </div>
        </form>
    </div>

    <!-- ALBUM CARDS GRID -->
    <div class="row g-4">
        @forelse ($kegiatanList as $item)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="solid-stat-card p-0 h-100 d-flex flex-column justify-content-between overflow-hidden">
                    <div>
                        <!-- Cover Image -->
                        <div class="position-relative bg-light border-bottom border-2 border-dark" style="height: 190px; overflow: hidden;">
                            @if ($item->cover_photo)
                                <img src="{{ asset('storage/' . $item->cover_photo) }}" 
                                     class="w-100 h-100 object-fit-cover" 
                                     alt="{{ $item->title }}">
                            @elseif ($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" 
                                     class="w-100 h-100 object-fit-cover" 
                                     alt="{{ $item->title }}">
                            @else
                                <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted" style="background: var(--brand-sand);">
                                    <i class="bi bi-images fs-1 text-muted mb-1"></i>
                                    <span class="fs-8 fw-bold">Belum ada foto</span>
                                </div>
                            @endif

                            <!-- Kategori Badge -->
                            <span class="badge-solid badge-solid-light position-absolute top-0 start-0 m-3">
                                {{ \App\Support\Organisasi::badgeLabel($item->kategori) }}
                            </span>

                            <!-- Count Media Badge -->
                            <div class="position-absolute bottom-0 end-0 m-2 d-flex align-items-center gap-1">
                                @if ($item->video_count > 0)
                                    <span class="badge-solid badge-solid-orange" style="font-size: 0.65rem;">
                                        <i class="bi bi-play-circle-fill"></i> {{ $item->video_count }} Video
                                    </span>
                                @endif
                                <span class="badge-solid badge-solid-forest" style="font-size: 0.65rem;">
                                    <i class="bi bi-camera-fill"></i> {{ $item->foto_count }} Foto
                                </span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-3">
                            <div class="text-muted fs-8 mb-2 d-flex align-items-center gap-2 flex-wrap">
                                <span><i class="bi bi-calendar3 me-1 text-forest"></i> {{ $item->event_date ? \Carbon\Carbon::parse($item->event_date)->translatedFormat('d M Y') : '-' }}</span>
                                @if ($item->location)
                                    <span>&bull;</span>
                                    <span class="text-truncate" style="max-width: 140px;" title="{{ $item->location }}">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $item->location }}
                                    </span>
                                @endif
                            </div>

                            <h6 class="fw-bold font-serif mb-2 line-clamp-2" style="font-size: 0.95rem; color: var(--brand-forest); line-height: 1.35;" title="{{ $item->title }}">
                                {{ $item->title }}
                            </h6>

                            <p class="text-muted fs-8 mb-0" style="line-height: 1.45; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 2.9em; max-height: 2.9em;">
                                {{ \Illuminate\Support\Str::limit($item->description, 95) }}
                            </p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="p-3 pt-2 border-top border-2 border-dark d-flex align-items-center justify-content-between gap-2 mt-auto">
                        <a href="{{ route('admin.galeri.show', $item->id) }}" class="btn-solid-primary btn-solid-sm btn-solid-press flex-grow-1 justify-content-center">
                            <i class="bi bi-camera-reels"></i>
                            <span>Kelola Dokumentasi ({{ $item->foto_count + $item->video_count }})</span>
                        </a>

                        @if ($item->fotos->count() > 0 || $item->image)
                            <a href="{{ route('galeri.show', $item->id) }}" target="_blank" class="btn-solid-light btn-solid-sm btn-solid-press" title="Lihat di Web Publik">
                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="sidebar-user-avatar-solid mx-auto mb-3" style="width: 56px; height: 56px; background: var(--brand-sand); color: var(--brand-forest);">
                        <i class="bi bi-calendar-check fs-3"></i>
                    </div>
                    <h6 class="fw-bold mb-1 font-serif" style="color: var(--brand-forest);">Belum Ada History Kegiatan</h6>
                    <p class="text-muted fs-8 mb-3">Kegiatan yang telah selesai akan otomatis muncul di sini untuk dikelola dokumentasinya.</p>
                    <a href="{{ route('admin.kegiatan.create') }}" class="btn-solid-primary btn-solid-sm btn-solid-press">
                        + Buat Kegiatan Baru
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    <div class="mt-4 pt-3 border-top border-2 border-dark">
        {{ $kegiatanList->links('partials.pagination-solid', ['paginationLabel' => 'history kegiatan']) }}
    </div>
</div>
@endsection