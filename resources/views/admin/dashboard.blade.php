@extends('layouts.admin')

@section('title', 'Dashboard Admin - Organisasi NU Wangon')

@section('content')
@php
    $totalBeritaVal = $totalBerita ?? 0;
    $totalKegiatanVal = $totalKegiatan ?? 0;
    $beritaPublishedVal = $beritaPublished ?? 0;
    $kegiatanPublishedVal = $kegiatanPublished ?? 0;

    $totalSemuaKonten = $totalBeritaVal + $totalKegiatanVal;
    $totalPublished = $beritaPublishedVal + $kegiatanPublishedVal;
    $totalDraft = max(0, $totalSemuaKonten - $totalPublished);

    // Hitung persentase keterbitan
    $persenPublished = $totalSemuaKonten > 0 ? round(($totalPublished / $totalSemuaKonten) * 100) : 0;
    $persenBerita = $totalBeritaVal > 0 ? round(($beritaPublishedVal / $totalBeritaVal) * 100) : 0;
    $persenKegiatan = $totalKegiatanVal > 0 ? round(($kegiatanPublishedVal / $totalKegiatanVal) * 100) : 0;
@endphp

<div class="dashboard-ref-frame">
    <!-- FULL-SCREEN APPLICATION FLEX ROW -->
    <div class="dashboard-layout-row">
        
        <!-- ========================================================
             MIDDLE WORKSPACE: GREETING, 3 FOCUS CARDS, TASKS & STATS
             ======================================================== -->
        <div class="dashboard-workspace-col">
            
            <!-- HEADER ROW: GREETING + LIVE SEARCH + ADD BUTTON -->
            <div class="ref-header-anim d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4 min-w-0">
                <div class="min-w-0">
                    <h1 class="header-greeting font-serif text-safe mb-1">
                        Hello, {{ Auth::user()->name ?? 'Administrator' }}
                    </h1>
                    <p class="header-date text-safe mb-0">
                        Hari ini: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </p>
                </div>

                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    <!-- Search Box -->
                    <div class="ref-search-box position-relative">
                        <i class="bi bi-search ref-search-icon"></i>
                        <input type="text" 
                               id="dashboardSearchInput" 
                               class="form-control-solid ps-5 pe-4 py-2" 
                               placeholder="Cari warta / agenda..." 
                               aria-label="Cari warta atau agenda">
                        <button type="button" id="clearSearchBtn" class="ref-clear-btn d-none" aria-label="Hapus pencarian">
                            <i class="bi bi-x-circle-fill"></i>
                        </button>
                    </div>

                    <!-- Add Project CTA (Solid Button) -->
                    @if(Route::has('admin.berita.create'))
                        <a href="{{ route('admin.berita.create') }}" class="btn-solid-primary text-nowrap btn-solid-press">
                            <i class="bi bi-plus-lg"></i>
                            <span>Tambah Berita</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- ROW OF 3 SUMMARY FOCUS CARDS (SOLID GROTESQUE HTML #1 SPEC) -->
            <div class="row g-3 mb-4" id="focusCardsRow">
                
                <!-- CARD 1: DEEP FOREST GREEN (WARTA & PUBLIKASI) -->
                <div class="col-12 col-md-4 min-w-0">
                    <div class="solid-focus-card card-forest">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge-solid badge-solid-matcha">
                                <i class="bi bi-newspaper"></i> +{{ number_format($totalBeritaVal) }}
                            </span>
                            <a href="{{ route('admin.berita.index') }}" class="solid-dots-btn" title="Kelola Berita">
                                <i class="bi bi-three-dots"></i>
                            </a>
                        </div>
                        <h3 class="solid-card-title text-safe">Warta &amp; Publikasi</h3>
                        <div class="solid-card-meta text-safe">
                            <span>{{ number_format($beritaPublishedVal) }} terbit</span>
                            <span class="fw-bold">{{ $persenBerita }}%</span>
                        </div>
                        <div class="solid-progress-track">
                            <div class="solid-progress-bar bg-matcha" style="width: {{ $persenBerita }}%;"></div>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: SAGE GREEN (AGENDA KEGIATAN) -->
                <div class="col-12 col-md-4 min-w-0">
                    <div class="solid-focus-card card-sage">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge-solid badge-solid-yellow">
                                <i class="bi bi-calendar-event"></i> +{{ number_format($totalKegiatanVal) }}
                            </span>
                            <a href="{{ route('admin.kegiatan.index') }}" class="solid-dots-btn" title="Kelola Kegiatan">
                                <i class="bi bi-three-dots"></i>
                            </a>
                        </div>
                        <h3 class="solid-card-title text-safe">Agenda Kegiatan</h3>
                        <div class="solid-card-meta text-safe">
                            <span>{{ number_format($kegiatanPublishedVal) }} aktif</span>
                            <span class="fw-bold">{{ $persenKegiatan }}%</span>
                        </div>
                        <div class="solid-progress-track">
                            <div class="solid-progress-bar bg-yellow" style="width: {{ $persenKegiatan }}%;"></div>
                        </div>
                    </div>
                </div>

                <!-- CARD 3: MATCHA CREAM (RASIO PUBLIKASI) -->
                <div class="col-12 col-md-4 min-w-0">
                    <div class="solid-focus-card card-matcha">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge-solid badge-solid-forest">
                                <i class="bi bi-pie-chart-fill"></i> {{ $persenPublished }}%
                            </span>
                            <a href="{{ route('admin.dashboard') }}" class="solid-dots-btn dark-dots" title="Ringkasan Status">
                                <i class="bi bi-three-dots"></i>
                            </a>
                        </div>
                        <h3 class="solid-card-title dark-text text-safe">Rasio Publikasi</h3>
                        <div class="solid-card-meta dark-meta text-safe">
                            <span>{{ number_format($totalPublished) }} terbit</span>
                            <span class="fw-bold">{{ $persenPublished }}%</span>
                        </div>
                        <div class="solid-progress-track border-dark-track">
                            <div class="solid-progress-bar bg-forest" style="width: {{ $persenPublished }}%;"></div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ROW OF 2 SUB-COLUMNS: TASKS FOR TODAY (LEFT) & STATISTICS (RIGHT) -->
            <div class="row g-3 flex-grow-1 align-items-stretch">
                
                <!-- LEFT SUB-COLUMN: TASKS FOR TODAY (WARTA TERBARU) -->
                <div class="col-12 col-lg-6 min-w-0 d-flex flex-column" id="tasksColumnBox">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="solid-section-title font-serif mb-0">Tasks for today</h2>
                        @if(Route::has('admin.berita.index'))
                            <a href="{{ route('admin.berita.index') }}" class="solid-view-all-link">
                                <span>Semua</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        @endif
                    </div>

                    <div class="ref-tasks-list flex-grow-1 d-flex flex-column gap-2" id="wartaListContainer">
                        @isset($beritaTerbaru)
                            @php
                                $accentColors = ['#142E25', '#2D4736', '#F38848', '#D97706', '#0E221B'];
                            @endphp
                            @forelse ($beritaTerbaru as $index => $b)
                                @php
                                    $isPublished = (isset($b->status) && $b->status === 'published');
                                    $stripeColor = $accentColors[$index % count($accentColors)];
                                    $searchPayload = strtolower(($b->title ?? $b->judul ?? '') . ' ' . ($b->kategori ?? ''));
                                @endphp
                                <div class="solid-task-card searchable-item" data-search-text="{{ $searchPayload }}">
                                    <!-- Left Colored Vertical Accent Stripe -->
                                    <div class="solid-task-stripe" style="background-color: {{ $stripeColor }};"></div>

                                    <!-- Middle Content -->
                                    <div class="min-w-0 flex-grow-1 px-3">
                                        <a href="{{ route('admin.berita.edit', $b->id) }}" 
                                           class="solid-task-heading line-clamp-2 text-safe" 
                                           title="{{ $b->title ?? $b->judul ?? 'Tanpa Judul' }}">
                                            {{ $b->title ?? $b->judul ?? 'Tanpa Judul' }}
                                        </a>
                                        <div class="solid-task-sub line-clamp-1 text-safe">
                                            <span class="badge-solid badge-solid-matcha" style="font-size: 0.65rem; padding: 2px 7px;">
                                                {{ \App\Support\Organisasi::badgeLabel($b->kategori) }}
                                            </span>
                                            <span class="text-muted">·</span>
                                            <span class="text-muted">{{ isset($b->created_at) ? \Carbon\Carbon::parse($b->created_at)->diffForHumans() : '-' }}</span>
                                        </div>
                                    </div>

                                    <!-- Right Check Indicator -->
                                    <div class="flex-shrink-0 ms-2">
                                        <a href="{{ route('admin.berita.edit', $b->id) }}" class="text-decoration-none">
                                            @if($isPublished)
                                                <span class="solid-check-circle checked" title="Terbit">
                                                    <i class="bi bi-check-lg"></i>
                                                </span>
                                            @else
                                                <span class="solid-check-circle empty" title="Draft (Belum Terbit)"></span>
                                            @endif
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="solid-empty-box text-center py-4">
                                    <p class="text-muted fs-8 mb-2">Belum ada warta terbaru.</p>
                                    @if(Route::has('admin.berita.create'))
                                        <a href="{{ route('admin.berita.create') }}" class="btn-solid-primary btn-solid-sm btn-solid-press">
                                            + Tambah Warta
                                        </a>
                                    @endif
                                </div>
                            @endforelse
                        @else
                            <div class="solid-empty-box text-center py-4">
                                <p class="text-muted fs-8 mb-0">Data warta tidak tersedia.</p>
                            </div>
                        @endisset

                        <div id="noWartaResults" class="solid-empty-box text-center py-4 d-none">
                            <p class="text-muted fs-8 mb-0">Tidak ada warta yang cocok.</p>
                        </div>
                    </div>
                </div>

                <!-- RIGHT SUB-COLUMN: STATISTICS (METRICS + CHART/BANNER) -->
                <div class="col-12 col-lg-6 min-w-0 d-flex flex-column" id="statsColumnBox">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="solid-section-title font-serif mb-0">Statistics</h2>
                        <!-- Period filter toggle -->
                        <div class="solid-period-toggle" role="group">
                            <button type="button" class="solid-toggle-btn active" data-period="12">12B</button>
                            <button type="button" class="solid-toggle-btn" data-period="6">6B</button>
                            <button type="button" class="solid-toggle-btn" data-period="all">Semua</button>
                        </div>
                    </div>

                    <!-- 3 MINI METRIC CARDS -->
                    <div class="row g-2 mb-3">
                        <div class="col-4 min-w-0">
                            <div class="solid-metric-box">
                                <div class="solid-metric-val text-safe">{{ number_format($totalSemuaKonten) }}</div>
                                <div class="solid-metric-lbl text-safe">Total Konten</div>
                            </div>
                        </div>
                        <div class="col-4 min-w-0">
                            <div class="solid-metric-box">
                                <div class="solid-metric-val text-safe" style="color: var(--brand-sage);">{{ number_format($totalPublished) }}</div>
                                <div class="solid-metric-lbl text-safe">Terbit</div>
                            </div>
                        </div>
                        <div class="col-4 min-w-0">
                            <div class="solid-metric-box dashed">
                                <div class="solid-metric-val text-safe" style="color: var(--brand-orange);">{{ number_format($totalDraft) }}</div>
                                <div class="solid-metric-lbl text-safe">Draft</div>
                            </div>
                        </div>
                    </div>

                    <!-- CHART TREND BULANAN -->
                    <div class="solid-chart-container mb-3 flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold" style="font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--brand-forest);">
                                Aktivitas Publikasi &amp; Kegiatan
                            </span>
                            <div class="d-flex align-items-center gap-3" style="font-size: 0.72rem; font-weight: 700;">
                                <span class="d-flex align-items-center gap-1">
                                    <span style="width: 10px; height: 10px; background: #142E25; border-radius: 3px; display: inline-block;"></span> Berita
                                </span>
                                <span class="d-flex align-items-center gap-1">
                                    <span style="width: 10px; height: 10px; background: #2D4736; border-radius: 3px; display: inline-block;"></span> Kegiatan
                                </span>
                            </div>
                        </div>
                        <div style="height: 180px; position: relative;">
                            <canvas id="nuRefActivityChart"></canvas>
                        </div>
                    </div>

                    <!-- PROMO / PORTAL CARD -->
                    <div class="solid-promo-card mt-auto">
                        <div class="d-flex align-items-center justify-content-between gap-2 min-w-0">
                            <div class="min-w-0">
                                <div class="solid-promo-price text-safe font-serif">MWC NU WANGON</div>
                                <div class="solid-promo-heading text-safe">Portal Resmi Jam'iyyah</div>
                                <div class="solid-promo-sub text-safe">Khidmat dakwah &amp; layanan warga se-Kecamatan.</div>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="{{ route('home') }}" target="_blank" class="solid-promo-btn btn-solid-press" title="Buka Website">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <!-- ========================================================
                 GALERI & DOKUMENTASI KEGIATAN (EXCERPT SHORT CARDS)
                 ======================================================== -->
            <div class="mt-4 pt-2">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <h2 class="solid-section-title font-serif mb-0">Galeri &amp; Dokumentasi</h2>
                        <span class="badge-solid badge-solid-forest" style="font-size: 0.65rem;">ARSIP TERBARU</span>
                    </div>
                    <a href="{{ route('admin.galeri.index') }}" class="solid-view-all-link" title="Buka Semua Galeri">
                        <span>Buka Galeri</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                @php
                    $galeriItems = $historyKegiatan ?? ($kegiatanTerbaru ?? collect());
                @endphp

                <div class="row g-3">
                    @forelse ($galeriItems->take(3) as $keg)
                        @php
                            $firstFoto = $keg->fotos ? $keg->fotos->first() : null;
                            $coverUrl = $keg->image ? asset('storage/' . $keg->image) : ($firstFoto && $firstFoto->file_path ? asset('storage/' . $firstFoto->file_path) : null);
                        @endphp
                        <div class="col-12 col-md-4">
                            <div class="card-solid d-flex flex-column h-100 p-0 overflow-hidden" style="min-height: 250px;">
                                <!-- Image Header Thumbnail -->
                                <div style="height: 120px; background-color: var(--brand-sand); position: relative; overflow: hidden; border-bottom: 2px solid var(--brand-forest);">
                                    @if($coverUrl)
                                        <img src="{{ $coverUrl }}" alt="{{ $keg->title }}" class="w-100 h-100 object-cover">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                            <i class="bi bi-camera-reels fs-2 text-[#2D4736]"></i>
                                        </div>
                                    @endif
                                    <span class="badge-solid badge-solid-forest position-absolute top-0 start-0 m-2" style="font-size: 0.65rem;">
                                        {{ \App\Support\Organisasi::badgeLabel($keg->kategori) }}
                                    </span>
                                    @if($keg->fotos && $keg->fotos->count() > 0)
                                        <span class="badge-solid badge-solid-matcha position-absolute bottom-0 end-0 m-2" style="font-size: 0.65rem;">
                                            <i class="bi bi-camera-fill me-1"></i>{{ $keg->fotos->count() }} Media
                                        </span>
                                    @endif
                                </div>

                                <!-- Card Body with Strict Excerpt and Uniform Height -->
                                <div class="p-3 d-flex flex-column flex-grow-1">
                                    <div class="text-muted fs-8 mb-1">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ $keg->event_date ? \Carbon\Carbon::parse($keg->event_date)->translatedFormat('d M Y') : 'Agenda Terlaksana' }}
                                    </div>
                                    <h6 class="fw-bold font-serif mb-2" style="font-size: 0.92rem; color: var(--brand-forest); line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;" title="{{ $keg->title }}">
                                        {{ $keg->title }}
                                    </h6>
                                    <!-- Short Excerpt / Summary with Line-Clamp -->
                                    <p class="text-muted fs-8 mb-3" style="line-height: 1.45; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 2.9em; max-height: 2.9em;">
                                        {{ \Illuminate\Support\Str::limit($keg->description, 85) ?: 'Dokumentasi kegiatan dan arsip pelaksanaan agenda jam\'iyyah.' }}
                                    </p>
                                    <!-- Bottom Action Link -->
                                    <div class="mt-auto pt-2 border-top border-2 border-dark d-flex align-items-center justify-content-between">
                                        <a href="{{ route('admin.galeri.show', $keg->id) }}" class="btn-solid-light btn-solid-sm btn-solid-press w-100 justify-content-center text-xs">
                                            <span>Lihat Detail &rarr;</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="solid-empty-box text-center py-4">
                                <p class="text-muted fs-8 mb-2">Belum ada dokumentasi galeri kegiatan.</p>
                                <a href="{{ route('admin.galeri.index') }}" class="btn-solid-primary btn-solid-sm btn-solid-press">
                                    Buka Galeri
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- ========================================================
             RIGHT COLUMN: CALENDAR PANEL (~360px DESKTOP PANEL)
             ======================================================== -->
        <div class="dashboard-calendar-col">
            <aside class="solid-calendar-panel d-flex flex-column h-100 min-w-0">
                
                <!-- CALENDAR HEADER WITH NOTIFICATION BELL & HISTORY LINK -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="solid-section-title font-serif mb-0">Calendar</h2>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('admin.galeri.index') }}" class="solid-view-all-link" title="Buka History & Galeri Kegiatan">
                            <i class="bi bi-images me-1"></i>
                            <span>Galeri</span>
                        </a>
                        <button type="button" class="solid-bell-btn btn-solid-press" title="Pemberitahuan Agenda">
                            <i class="bi bi-bell"></i>
                            <span class="solid-bell-dot"></span>
                        </button>
                    </div>
                </div>

                <!-- CHRONOLOGICAL EVENT FEED -->
                <div class="ref-calendar-feed flex-grow-1 min-w-0 pe-1" id="calendarEventsFeed">
                    @isset($kegiatanTerbaru)
                        @php
                            $feedColors = ['#142E25', '#F38848', '#2D4736', '#D97706', '#0E221B'];
                            $groupedKegiatan = $kegiatanTerbaru->groupBy(function($item) {
                                return isset($item->event_date) 
                                    ? \Carbon\Carbon::parse($item->event_date)->translatedFormat('d M Y') 
                                    : 'Jadwal Mendatang';
                            });
                        @endphp

                        @forelse ($groupedKegiatan as $dateLabel => $events)
                            <div class="solid-date-group mb-3 min-w-0">
                                <!-- Date Group Header with 3 dots -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="solid-date-label text-safe">{{ $dateLabel }}</span>
                                    <span class="text-muted"><i class="bi bi-three-dots"></i></span>
                                </div>

                                <!-- Events under this date -->
                                @foreach ($events as $idx => $ev)
                                    @php
                                        $barColor = $feedColors[($idx + $loop->parent->index) % count($feedColors)];
                                        $evTime = !empty($ev->start_time) ? substr($ev->start_time, 0, 5) : '10:00';
                                        $evSearch = strtolower(($ev->title ?? $ev->nama_kegiatan ?? '') . ' ' . ($ev->location ?? ''));
                                    @endphp
                                    <a href="{{ route('admin.kegiatan.edit', $ev->id) }}" 
                                       class="solid-calendar-event-row searchable-item text-decoration-none min-w-0 d-flex align-items-start gap-2 mb-2"
                                       data-search-text="{{ $evSearch }}"
                                       title="Edit Agenda: {{ $ev->title ?? $ev->nama_kegiatan ?? 'Agenda' }}">
                                        
                                        <!-- Time (e.g. 10:00) -->
                                        <div class="solid-ev-time text-nowrap flex-shrink-0">
                                            {{ $evTime }}
                                        </div>

                                        <!-- Vertical Color Stripe -->
                                        <div class="solid-ev-stripe flex-shrink-0" style="background-color: {{ $barColor }};"></div>

                                        <!-- Event Details -->
                                        <div class="min-w-0 flex-grow-1">
                                            <div class="solid-ev-title line-clamp-1 text-safe">
                                                {{ $ev->title ?? $ev->nama_kegiatan ?? 'Tanpa Nama Agenda' }}
                                            </div>
                                            <div class="solid-ev-sub line-clamp-1 text-safe d-flex align-items-center justify-content-between gap-1">
                                                <span><i class="bi bi-geo-alt me-1"></i>{{ $ev->location ?? 'Kantor MWC NU' }}</span>
                                                @if(method_exists($ev, 'isSelesai') && $ev->isSelesai())
                                                    <span class="badge-solid badge-solid-matcha" style="font-size: 0.6rem; padding: 1px 5px;">Selesai</span>
                                                @endif
                                            </div>
                                        </div>

                                    </a>
                                @endforeach
                            </div>
                        @empty
                            <div class="solid-empty-box text-center py-4 my-auto">
                                <p class="text-muted fs-8 mb-2">Belum ada agenda kegiatan.</p>
                                @if(Route::has('admin.kegiatan.create'))
                                    <a href="{{ route('admin.kegiatan.create') }}" class="btn-solid-primary btn-solid-sm btn-solid-press">
                                        + Jadwalkan
                                    </a>
                                @endif
                            </div>
                        @endforelse
                    @else
                        <div class="solid-empty-box text-center py-4 my-auto">
                            <p class="text-muted fs-8 mb-0">Data kegiatan tidak tersedia.</p>
                        </div>
                    @endisset

                    <div id="noCalendarResults" class="solid-empty-box text-center py-4 d-none">
                        <p class="text-muted fs-8 mb-0">Tidak ada agenda yang cocok.</p>
                    </div>
                </div>

                <!-- BOTTOM CTA: + JADWALKAN KEGIATAN -->
                @if(Route::has('admin.kegiatan.create'))
                    <div class="pt-3 border-top border-2 border-dark mt-3">
                        <a href="{{ route('admin.kegiatan.create') }}" class="btn-solid-primary w-100 justify-content-center btn-solid-press">
                            <i class="bi bi-calendar-plus"></i>
                            <span>Jadwalkan Agenda Baru</span>
                        </a>
                    </div>
                @endif

            </aside>
        </div>

    </div>
</div>

<!-- Chart.js 4.x CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    
    // 1. DATA INJECTION DARI CONTROLLER (12B, 6B, Semua)
    const rawActivityChart = @json($activityChart ?? null);
    const chartPeriods = rawActivityChart || {
        '12': {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            berita: Array(12).fill(0),
            kegiatan: Array(12).fill(0)
        },
        '6': { labels: [], berita: [], kegiatan: [] },
        'all': { labels: [], berita: [], kegiatan: [] }
    };

    const initialData = chartPeriods['12'] || {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        berita: Array(12).fill(0),
        kegiatan: Array(12).fill(0)
    };

    // 2. CHART.JS INISIALISASI (SOLID GROTESQUE STYLING)
    const chartEl = document.getElementById('nuRefActivityChart');
    if (chartEl) {
        const ctx = chartEl.getContext('2d');

        const activityChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: initialData.labels,
                datasets: [
                    {
                        label: 'Berita',
                        data: initialData.berita,
                        borderColor: '#142E25',
                        backgroundColor: 'rgba(20, 46, 37, 0.12)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2.5,
                        pointRadius: 3,
                        pointBackgroundColor: '#142E25',
                        pointBorderColor: '#FFFFFF',
                        pointBorderWidth: 1.5,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Kegiatan',
                        data: initialData.kegiatan,
                        borderColor: '#2D4736',
                        backgroundColor: 'rgba(45, 71, 54, 0.08)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2.5,
                        pointRadius: 3,
                        pointBackgroundColor: '#2D4736',
                        pointBorderColor: '#FFFFFF',
                        pointBorderWidth: 1.5,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 650,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#142E25',
                        borderColor: '#142E25',
                        borderWidth: 1.5,
                        titleFont: { family: 'Plus Jakarta Sans', size: 11, weight: 'bold' },
                        bodyFont: { family: 'Plus Jakarta Sans', size: 10 },
                        padding: 9,
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#ECE8DD',
                            drawBorder: false
                        },
                        ticks: {
                            font: { size: 9, family: 'Plus Jakarta Sans', weight: 'bold' },
                            color: '#142E25',
                            precision: 0
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 9, family: 'Plus Jakarta Sans', weight: 'bold' },
                            color: '#142E25'
                        }
                    }
                }
            }
        });

        // Period filter toggle (12B / 6B / Semua)
        const toggleButtons = document.querySelectorAll('.solid-toggle-btn');
        toggleButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                toggleButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const period = this.getAttribute('data-period');
                const targetDataset = chartPeriods[period] || chartPeriods['12'];
                if (targetDataset) {
                    activityChart.data.labels = targetDataset.labels;
                    activityChart.data.datasets[0].data = targetDataset.berita;
                    activityChart.data.datasets[1].data = targetDataset.kegiatan;
                    activityChart.update();
                }
            });
        });
    }

    // 3. FRAMER MOTION ORCHESTRATION
    if (window.Motion && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        const { animate, stagger } = window.Motion;
        const easeSilk = [0.16, 1, 0.3, 1];

        animate('.ref-header-anim', { opacity: [0, 1], y: [10, 0] }, { duration: 0.35, easing: easeSilk });

        const focusCards = document.querySelectorAll('.solid-focus-card');
        if (focusCards.length > 0) {
            animate(focusCards, { opacity: [0, 1], y: [12, 0] }, { delay: stagger(0.06, { start: 0.05 }), duration: 0.4, easing: easeSilk });
        }

        const taskCards = document.querySelectorAll('.solid-task-card');
        if (taskCards.length > 0) {
            animate(taskCards, { opacity: [0, 1], y: [10, 0] }, { delay: stagger(0.04, { start: 0.15 }), duration: 0.35, easing: easeSilk });
        }

        const calendarPanel = document.querySelector('.solid-calendar-panel');
        if (calendarPanel) {
            animate(calendarPanel, { opacity: [0, 1], x: [10, 0] }, { delay: 0.1, duration: 0.4, easing: easeSilk });
        }
    }

    // 4. LIVE CLIENT-SIDE SEARCH
    const searchInput = document.getElementById('dashboardSearchInput');
    const clearBtn = document.getElementById('clearSearchBtn');
    const searchableItems = document.querySelectorAll('.searchable-item');
    const noWartaResults = document.getElementById('noWartaResults');
    const noCalendarResults = document.getElementById('noCalendarResults');

    function applySearch() {
        const query = searchInput.value.toLowerCase().trim();

        if (query.length > 0) {
            clearBtn.classList.remove('d-none');
        } else {
            clearBtn.classList.add('d-none');
        }

        let visibleWarta = 0;
        let visibleCalendar = 0;

        searchableItems.forEach(item => {
            const text = (item.getAttribute('data-search-text') || '') + ' ' + item.innerText.toLowerCase();
            const matches = text.includes(query);

            if (matches) {
                item.style.display = '';
                if (item.classList.contains('solid-task-card')) visibleWarta++;
                if (item.classList.contains('solid-calendar-event-row')) visibleCalendar++;
            } else {
                item.style.display = 'none';
            }
        });

        if (noWartaResults) {
            noWartaResults.classList.toggle('d-none', visibleWarta > 0 || query === '');
        }
        if (noCalendarResults) {
            noCalendarResults.classList.toggle('d-none', visibleCalendar > 0 || query === '');
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', applySearch);
    }

    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            searchInput.value = '';
            applySearch();
            searchInput.focus();
        });
    }

});
</script>

<style>
    /* ========================================================
       DASHBOARD SOLID GROTESQUE STYLING
       ======================================================== */
    .dashboard-ref-frame {
        width: 100%;
        min-height: calc(100vh - 40px);
    }

    .dashboard-layout-row {
        display: flex;
        gap: 24px;
        width: 100%;
        align-items: stretch;
    }

    .dashboard-workspace-col {
        flex: 1 1 0;
        min-width: 0;
        display: flex;
        flex-direction: column;
    }

    .dashboard-calendar-col {
        flex: 0 0 360px;
        width: 360px;
        min-width: 340px;
        max-width: 380px;
        display: flex;
        flex-direction: column;
    }

    @media (max-width: 1199.98px) {
        .dashboard-layout-row {
            flex-direction: column;
        }
        .dashboard-calendar-col {
            flex: 1 1 auto;
            width: 100%;
            max-width: 100%;
        }
    }

    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .header-greeting {
        font-size: 1.85rem;
        font-weight: 800;
        color: var(--brand-forest);
        line-height: 1.15;
        letter-spacing: -0.015em;
    }

    .header-date {
        font-size: 0.86rem;
        color: rgba(20, 46, 37, 0.7);
        font-weight: 600;
    }

    .solid-section-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--brand-forest);
        letter-spacing: -0.01em;
    }

    .fs-8 { font-size: 0.8rem; }

    .ref-search-box {
        min-width: 220px;
    }

    .ref-search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--brand-forest);
        font-size: 0.88rem;
        pointer-events: none;
    }

    .ref-clear-btn {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--brand-forest);
        cursor: pointer;
        padding: 0;
        font-size: 0.9rem;
    }

    .solid-view-all-link {
        font-size: 0.82rem;
        font-weight: 800;
        color: var(--brand-forest);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: gap 0.15s ease;
    }
    .solid-view-all-link:hover {
        gap: 8px;
        color: var(--brand-sage);
    }

    /* SUMMARY FOCUS CARDS (SOLID GROTESQUE HTML #1) */
    .solid-focus-card {
        border-radius: 20px;
        padding: 22px;
        border: 2.5px solid var(--brand-forest);
        box-shadow: 5px 5px 0px var(--brand-forest);
        position: relative;
        min-height: 165px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .solid-focus-card:hover {
        transform: translate(-1px, -1px);
        box-shadow: 6px 6px 0px var(--brand-forest);
    }

    .card-forest {
        background-color: var(--brand-forest);
        color: #FFFFFF;
    }
    .card-sage {
        background-color: var(--brand-sage);
        color: #FFFFFF;
    }
    .card-matcha {
        background-color: var(--brand-matcha);
        color: var(--brand-forest);
    }

    .solid-dots-btn {
        color: #FFFFFF;
        text-decoration: none;
        font-size: 1.1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 1.5px solid rgba(255, 255, 255, 0.4);
        background: rgba(255, 255, 255, 0.15);
        transition: all 0.15s ease;
    }
    .solid-dots-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        color: #FFFFFF;
        transform: scale(1.1);
    }
    .solid-dots-btn.dark-dots {
        color: var(--brand-forest);
        border: 1.5px solid var(--brand-forest);
        background: #FFFFFF;
    }
    .solid-dots-btn.dark-dots:hover {
        background: var(--brand-forest);
        color: #FFFFFF;
    }

    .solid-card-title {
        font-family: var(--font-heading);
        font-size: 1.25rem;
        font-weight: 800;
        margin-top: 10px;
        margin-bottom: 4px;
        color: #FFFFFF;
    }
    .solid-card-title.dark-text {
        color: var(--brand-forest);
    }

    .solid-card-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.82rem;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 10px;
        font-weight: 600;
    }
    .solid-card-meta.dark-meta {
        color: var(--brand-forest);
    }

    .solid-progress-track {
        height: 8px;
        background: rgba(255, 255, 255, 0.25);
        border: 1.5px solid rgba(255, 255, 255, 0.5);
        border-radius: 9999px;
        overflow: hidden;
    }
    .solid-progress-track.border-dark-track {
        background: rgba(20, 46, 37, 0.15);
        border: 1.5px solid var(--brand-forest);
    }

    .solid-progress-bar {
        height: 100%;
        border-radius: 9999px;
        transition: width 0.6s ease;
    }
    .bg-matcha { background-color: var(--brand-matcha); }
    .bg-yellow { background-color: var(--brand-yellow); }
    .bg-forest { background-color: var(--brand-forest); }

    /* TASKS FOR TODAY (SOLID CARDS) */
    .solid-task-card {
        background-color: #FFFFFF;
        border: 2px solid var(--brand-forest);
        border-radius: 14px;
        padding: 12px 14px;
        display: flex;
        align-items: center;
        box-shadow: 3px 3px 0px var(--brand-forest);
        transition: transform 0.18s ease, box-shadow 0.18s ease;
        min-width: 0;
    }
    .solid-task-card:hover {
        transform: translate(-1px, -1px);
        box-shadow: 4px 4px 0px var(--brand-forest);
        background-color: var(--brand-cream);
    }

    .solid-task-stripe {
        width: 5px;
        height: 42px;
        border-radius: 4px;
        border: 1px solid var(--brand-forest);
        flex-shrink: 0;
    }

    .solid-task-heading {
        font-size: 0.92rem;
        font-weight: 800;
        color: var(--brand-forest);
        text-decoration: none;
        line-height: 1.35;
    }
    .solid-task-heading:hover {
        color: var(--brand-sage);
    }

    .solid-task-sub {
        font-size: 0.74rem;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .solid-check-circle {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--brand-forest);
        transition: transform 0.15s ease;
    }
    .solid-check-circle.empty {
        background: #FFFFFF;
    }
    .solid-check-circle.checked {
        background: var(--brand-forest);
        color: #FFFFFF;
        font-size: 0.95rem;
    }

    /* STATISTICS TOGGLE & METRIC BOXES */
    .solid-period-toggle {
        display: inline-flex;
        background: var(--brand-sand);
        border: 2px solid var(--brand-forest);
        border-radius: 10px;
        padding: 2px;
        box-shadow: 2px 2px 0px var(--brand-forest);
    }

    .solid-toggle-btn {
        background: transparent;
        border: none;
        border-radius: 7px;
        padding: 4px 10px;
        font-size: 0.72rem;
        font-weight: 800;
        color: var(--brand-forest);
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .solid-toggle-btn.active {
        background: var(--brand-forest);
        color: #FFFFFF;
    }

    .solid-metric-box {
        background-color: #FFFFFF;
        border: 2px solid var(--brand-forest);
        border-radius: 14px;
        padding: 12px 14px;
        display: flex;
        flex-direction: column;
        min-width: 0;
        box-shadow: 3px 3px 0px var(--brand-forest);
        transition: transform 0.18s ease;
    }
    .solid-metric-box:hover {
        transform: translateY(-2px);
    }
    .solid-metric-box.dashed {
        background-color: var(--brand-cream);
        border-style: dashed;
    }

    .solid-metric-val {
        font-size: 1.5rem;
        font-weight: 900;
        color: var(--brand-forest);
        line-height: 1.1;
    }

    .solid-metric-lbl {
        font-size: 0.7rem;
        font-weight: 800;
        color: rgba(20, 46, 37, 0.7);
        margin-top: 4px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .solid-chart-container {
        min-height: 180px;
        position: relative;
        width: 100%;
        background: #FFFFFF;
        border: 2px solid var(--brand-forest);
        border-radius: 16px;
        padding: 14px;
        box-shadow: 4px 4px 0px var(--brand-forest);
    }

    .solid-promo-card {
        background: var(--brand-sand);
        border: 2px solid var(--brand-forest);
        border-radius: 16px;
        padding: 14px 18px;
        box-shadow: 3px 3px 0px var(--brand-forest);
    }

    .solid-promo-price {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--brand-forest);
        line-height: 1.2;
    }
    .solid-promo-heading {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--brand-forest);
    }
    .solid-promo-sub {
        font-size: 0.72rem;
        color: rgba(20, 46, 37, 0.7);
        font-weight: 500;
    }

    .solid-promo-btn {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #FFFFFF;
        border: 2px solid var(--brand-forest);
        color: var(--brand-forest);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 0.95rem;
        box-shadow: 2px 2px 0px var(--brand-forest);
    }
    .solid-promo-btn:hover {
        background: var(--brand-yellow);
        color: var(--brand-forest);
    }

    /* CALENDAR PANEL (SOLID PANEL) */
    .solid-calendar-panel {
        background-color: #FFFFFF;
        border: 2.5px solid var(--brand-forest);
        border-radius: 22px;
        padding: 22px 18px;
        box-shadow: 5px 5px 0px var(--brand-forest);
        width: 100% !important;
        height: 100% !important;
    }

    .solid-bell-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #FFFFFF;
        border: 2px solid var(--brand-forest);
        color: var(--brand-forest);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        position: relative;
        cursor: pointer;
        box-shadow: 2px 2px 0px var(--brand-forest);
    }
    .solid-bell-btn:hover {
        background: var(--brand-sand);
    }
    .solid-bell-dot {
        position: absolute;
        top: 6px;
        right: 6px;
        width: 7px;
        height: 7px;
        background-color: var(--brand-danger);
        border-radius: 50%;
        border: 1px solid var(--brand-forest);
    }

    .ref-calendar-feed {
        overflow-y: auto;
    }

    .solid-date-label {
        font-size: 0.78rem;
        font-weight: 800;
        color: var(--brand-forest);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .solid-calendar-event-row {
        padding: 8px 10px;
        border-radius: 10px;
        border: 1.5px solid transparent;
        transition: all 0.15s ease;
    }
    .solid-calendar-event-row:hover {
        background-color: var(--brand-cream);
        border-color: var(--brand-forest);
        transform: translateX(3px);
    }

    .solid-ev-time {
        font-size: 0.8rem;
        font-weight: 800;
        color: var(--brand-forest);
        width: 48px;
        padding-top: 1px;
    }

    .solid-ev-stripe {
        width: 4px;
        height: 34px;
        border-radius: 2px;
        border: 1px solid var(--brand-forest);
    }

    .solid-ev-title {
        font-size: 0.86rem;
        font-weight: 800;
        color: var(--brand-forest);
        line-height: 1.35;
    }
    .solid-calendar-event-row:hover .solid-ev-title {
        color: var(--brand-sage);
    }

    .solid-ev-sub {
        font-size: 0.74rem;
        color: rgba(20, 46, 37, 0.7);
        font-weight: 600;
        margin-top: 1px;
    }

    .solid-empty-box {
        background: var(--brand-cream);
        border: 2px dashed var(--brand-forest);
        border-radius: 14px;
        padding: 20px 14px;
    }
</style>
@endsection