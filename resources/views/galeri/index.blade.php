@extends('layouts.app')

@section('title', 'Galeri Dokumentasi Kegiatan - Organisasi NU Wangon')

@section('content')

<div class="galeri-page-wrapper min-h-screen bg-brand-cream text-brand-darktext font-sans antialiased pb-24 pt-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- ================= 1. HEADER & SEARCH BAR ================= -->
        <div class="bg-white rounded-2xl sm:rounded-3xl border-2 border-brand-forest p-4 sm:p-10 shadow-solid-md flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
            <!-- Abstract Geometric Background (PixelOS Inspiration - Large Header Geometry with 5px Stroke & Translucent Fill) -->
            <div class="absolute inset-0 pixel-grid-blueprint opacity-60 pointer-events-none -z-0"></div>
            <div class="pointer-events-none absolute -bottom-20 -right-20 w-72 h-72 sm:w-80 sm:h-80 rounded-full border-[5px] border-brand-forest/[0.24] flex items-center justify-center p-12 hidden sm:flex -z-0" data-parallax="bg-slow" data-parallax-rotate="-3">
                <div class="w-full h-full rounded-full border-[3px] border-dashed border-brand-matcha/[0.28] bg-brand-lightmatcha/[0.15]"></div>
            </div>
            <div class="pointer-events-none absolute top-4 left-6 text-brand-forest/[0.35] text-sm font-mono font-bold select-none hidden sm:block -z-0">+</div>

            <div class="space-y-3 max-w-xl relative z-10">
                <div data-text-animate="blurInUp" class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-brand-lightmatcha text-brand-forest border-2 border-brand-forest rounded-full text-xs font-black uppercase tracking-wider shadow-solid-sm">
                    <i class="bi bi-images"></i>
                    <span>DOKUMENTASI MEDIA RESMI</span>
                </div>
                <h1 data-text-animate="blurInUp" class="text-3xl sm:text-4xl lg:text-5xl font-black text-brand-deep tracking-tight leading-tight">
                    Galeri Dokumentasi
                </h1>
                <p class="text-sm sm:text-base font-medium text-brand-darktext/80 leading-relaxed">
                    Arsip foto dan video dokumentasi kegiatan dakwah, kaderisasi, dan aksi sosial Nahdlatul Ulama yang telah terlaksana di Wangon.
                </p>
            </div>

            <!-- Search Form -->
            <form action="{{ route('galeri.index') }}" method="GET" class="w-full md:w-auto m-0 relative z-10">
                @if(request('kategori'))
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                @endif
                @if(request('media'))
                    <input type="hidden" name="media" value="{{ request('media') }}">
                @endif
                <div class="flex items-center bg-brand-cream rounded-2xl p-1.5 border-2 border-brand-forest shadow-solid-sm focus-within:shadow-solid-md w-full min-w-0 sm:min-w-[360px] transition-all">
                    <span class="pl-3.5 pr-2 text-brand-forest text-base">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           class="w-full bg-transparent border-0 py-2 px-1 text-xs sm:text-sm font-bold text-brand-darktext focus:outline-none placeholder:text-brand-darktext/50" 
                           placeholder="Cari dokumentasi atau lokasi...">
                    
                    @if(request('search'))
                        <a href="{{ route('galeri.index', array_filter(['kategori' => request('kategori'), 'media' => request('media')])) }}" 
                           class="px-2 text-brand-darktext/60 hover:text-rose-600 transition-colors" 
                           title="Reset Pencarian">
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    @endif

                    <button type="submit" 
                            class="bg-brand-forest hover:bg-brand-deep text-white px-3.5 sm:px-5 py-2 sm:py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all btn-solid-press border border-brand-deep shadow-solid-sm shrink-0">
                        Cari
                    </button>
                </div>
            </form>
        </div>

        <!-- ================= 2. CATEGORY FILTER PILLS ================= -->
        <div class="flex items-center gap-2.5 overflow-x-auto pb-2 scrollbar-none">
            <a href="{{ route('galeri.index', array_filter(['search' => request('search'), 'media' => request('media')])) }}"
               class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all whitespace-nowrap border-2 text-decoration-none btn-solid-press {{ !request('kategori') ? 'bg-brand-forest text-white border-brand-deep shadow-solid-sm' : 'bg-white text-brand-forest border-brand-forest hover:bg-brand-sand' }}">
                Semua Kategori
            </a>
            @foreach(\App\Support\Organisasi::badgeLabelsWithUmum() as $key => $label)
                <a href="{{ route('galeri.index', array_filter(['search' => request('search'), 'kategori' => $key, 'media' => request('media')])) }}"
                   class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all whitespace-nowrap border-2 text-decoration-none btn-solid-press {{ request('kategori') === $key ? 'bg-brand-forest text-white border-brand-deep shadow-solid-sm' : 'bg-white text-brand-forest border-brand-forest hover:bg-brand-sand' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- ================= 2.1 MEDIA TYPE FILTER (SEMUA | FOTO | VIDEO) ================= -->
        @php
            $currentMedia = request('media', 'semua');
            $buildMediaUrl = function($type) {
                $params = array_filter([
                    'search' => request('search'),
                    'kategori' => request('kategori'),
                    'media' => $type === 'semua' ? null : $type,
                ]);
                return route('galeri.index', $params);
            };
        @endphp
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
            <div class="inline-flex max-w-full overflow-x-auto p-1.5 rounded-2xl bg-white border-2 border-brand-forest shadow-solid-sm self-start gap-1 scrollbar-none">
                <a href="{{ $buildMediaUrl('semua') }}"
                   data-carousel-filter="semua"
                   class="whitespace-nowrap px-3 sm:px-4 py-1.5 sm:py-2 rounded-xl text-[11px] sm:text-xs font-black uppercase tracking-wider transition-all flex items-center gap-2 text-decoration-none btn-solid-press {{ ($currentMedia === 'semua' || !$currentMedia) ? 'bg-brand-forest text-white shadow-solid-sm' : 'text-brand-forest hover:bg-brand-sand' }}">
                    <i class="bi bi-grid-fill text-xs"></i>
                    <span>Semua Media</span>
                </a>
                <a href="{{ $buildMediaUrl('foto') }}"
                   data-carousel-filter="foto"
                   class="whitespace-nowrap px-3 sm:px-4 py-1.5 sm:py-2 rounded-xl text-[11px] sm:text-xs font-black uppercase tracking-wider transition-all flex items-center gap-2 text-decoration-none btn-solid-press {{ $currentMedia === 'foto' ? 'bg-brand-forest text-white shadow-solid-sm' : 'text-brand-forest hover:bg-brand-sand' }}">
                    <i class="bi bi-camera-fill text-xs"></i>
                    <span>Hanya Foto</span>
                </a>
                <a href="{{ $buildMediaUrl('video') }}"
                   data-carousel-filter="video"
                   class="whitespace-nowrap px-3 sm:px-4 py-1.5 sm:py-2 rounded-xl text-[11px] sm:text-xs font-black uppercase tracking-wider transition-all flex items-center gap-2 text-decoration-none btn-solid-press {{ $currentMedia === 'video' ? 'bg-brand-forest text-white shadow-solid-sm' : 'text-brand-forest hover:bg-brand-sand' }}">
                    <i class="bi bi-play-circle-fill text-xs {{ $currentMedia === 'video' ? 'text-white' : 'text-brand-accent' }}"></i>
                    <span>Hanya Video</span>
                </a>
            </div>

            <div class="text-xs font-bold text-brand-darktext/70">
                Menampilkan: <strong class="text-brand-deep font-black">{{ $currentMedia === 'video' ? 'Hanya Video' : ($currentMedia === 'foto' ? 'Hanya Foto' : 'Semua Media') }}</strong> ({{ $galeriList->total() }} Kegiatan)
            </div>
        </div>

        <!-- ================= 3. CONTENT AREA ================= -->
        @if ($galeriList->isEmpty())
            <!-- EMPTY STATE -->
            <div class="bg-white border-2 border-dashed border-brand-forest rounded-3xl p-10 sm:p-14 text-center flex flex-col items-center justify-center space-y-4 my-8 shadow-solid-sm">
                <div class="w-16 h-16 rounded-2xl bg-brand-lightmatcha text-brand-forest border-2 border-brand-forest flex items-center justify-center text-3xl shadow-solid-sm">
                    @if(request('media') === 'video')
                        <i class="bi bi-film text-brand-accent"></i>
                    @else
                        <i class="bi bi-images"></i>
                    @endif
                </div>
                <div class="space-y-1.5 max-w-md">
                    <h3 class="font-black text-2xl text-brand-deep tracking-tight">
                        @if(request('media') === 'video')
                            Belum Ada Dokumentasi Video Kegiatan
                        @elseif(request('media') === 'foto')
                            Belum Ada Dokumentasi Foto Kegiatan
                        @else
                            Belum Ada Dokumentasi Kegiatan
                        @endif
                    </h3>
                    <p class="text-xs sm:text-sm text-brand-darktext/75 leading-relaxed">
                        @if(request('media') === 'video')
                            Belum ada kegiatan yang memiliki rekaman video dokumentasi untuk pencarian atau filter yang dipilih.
                        @else
                            Dokumentasi untuk kategori ini belum tersedia atau tidak cocok dengan kata kunci pencarian Anda.
                        @endif
                    </p>
                </div>
                @if(request('search') || request('kategori') || request('media'))
                    <a href="{{ route('galeri.index') }}" 
                       class="inline-flex items-center gap-2 bg-brand-forest hover:bg-brand-deep text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-solid-sm btn-solid-press text-decoration-none mt-2">
                        <i class="bi bi-arrow-left"></i>
                        <span>Tampilkan Semua Galeri</span>
                    </a>
                @endif
            </div>
        @else

            <!-- ================= BENTO GRID GALLERY OF DOCUMENTED ACTIVITIES ================= -->
            @php
                $totalItems = $galeriList->count();

                $detectRatio = function(?string $path) {
                    if (!$path) return 'landscape';
                    $fullPath = storage_path('app/public/' . $path);
                    if (!file_exists($fullPath)) {
                        $publicPath = public_path($path);
                        if (file_exists($publicPath)) {
                            $fullPath = $publicPath;
                        } else {
                            return 'landscape';
                        }
                    }
                    $info = @getimagesize($fullPath);
                    if (!$info || empty($info[0]) || empty($info[1])) return 'landscape';
                    $ratio = $info[0] / $info[1];
                    if ($ratio >= 1.6) return 'wide';
                    if ($ratio >= 1.15) return 'landscape';
                    if ($ratio <= 0.85) return 'portrait';
                    return 'square';
                };

                $getBentoConfig = function($index, $total, $orientation, $hasVideo) {
                    $i = $index + 1; // 1-based index
                    
                    if ($total === 1) {
                        return [
                            'col' => 'col-span-1 md:col-span-2 lg:col-span-12',
                            'isBanner' => true,
                            'imgHeight' => 'h-72 sm:h-80 lg:h-96',
                            'isHero' => true,
                        ];
                    }
                    
                    if ($total === 2) {
                        return [
                            'col' => ($i === 1) ? 'col-span-1 md:col-span-1 lg:col-span-7' : 'col-span-1 md:col-span-1 lg:col-span-5',
                            'isBanner' => false,
                            'imgHeight' => 'h-64 sm:h-72 lg:h-80',
                            'isHero' => ($i === 1),
                        ];
                    }

                    if ($total === 3) {
                        if ($i === 1) return ['col' => 'col-span-1 md:col-span-1 lg:col-span-7', 'isBanner' => false, 'imgHeight' => 'h-64 sm:h-72 lg:h-80', 'isHero' => true];
                        if ($i === 2) return ['col' => 'col-span-1 md:col-span-1 lg:col-span-5', 'isBanner' => false, 'imgHeight' => 'h-64 sm:h-72 lg:h-80', 'isHero' => false];
                        return ['col' => 'col-span-1 md:col-span-2 lg:col-span-12', 'isBanner' => true, 'imgHeight' => 'h-64 sm:h-80 lg:h-full', 'isHero' => true];
                    }

                    if ($total === 4) {
                        if ($i === 1) return ['col' => 'col-span-1 md:col-span-1 lg:col-span-7', 'isBanner' => false, 'imgHeight' => 'h-64 sm:h-72 lg:h-80', 'isHero' => true];
                        if ($i === 2) return ['col' => 'col-span-1 md:col-span-1 lg:col-span-5', 'isBanner' => false, 'imgHeight' => 'h-64 sm:h-72 lg:h-80', 'isHero' => false];
                        return ['col' => 'col-span-1 md:col-span-1 lg:col-span-6', 'isBanner' => false, 'imgHeight' => 'h-56 sm:h-64 lg:h-72', 'isHero' => false];
                    }

                    // For 5 or more items:
                    // Row 1 (i = 1, 2): 7 + 5 = 12
                    if ($i === 1) return ['col' => 'col-span-1 md:col-span-1 lg:col-span-7', 'isBanner' => false, 'imgHeight' => 'h-64 sm:h-72 lg:h-80', 'isHero' => true];
                    if ($i === 2) return ['col' => 'col-span-1 md:col-span-1 lg:col-span-5', 'isBanner' => false, 'imgHeight' => 'h-64 sm:h-72 lg:h-80', 'isHero' => false];

                    // Row 2 (i = 3, 4, 5): 4 + 4 + 4 = 12
                    if ($i === 3 || $i === 4 || $i === 5) {
                        return ['col' => 'col-span-1 md:col-span-1 lg:col-span-4', 'isBanner' => false, 'imgHeight' => 'h-52 sm:h-56 lg:h-60', 'isHero' => false];
                    }

                    // Row 3 (i = 6, 7):
                    if ($total === 6) {
                        return ['col' => 'col-span-1 md:col-span-2 lg:col-span-12', 'isBanner' => true, 'imgHeight' => 'h-64 sm:h-80 lg:h-full', 'isHero' => true];
                    }
                    if ($total === 7) {
                        return ['col' => 'col-span-1 md:col-span-1 lg:col-span-6', 'isBanner' => false, 'imgHeight' => 'h-56 sm:h-64 lg:h-72', 'isHero' => false];
                    }
                    // In total 8 or 9: Inverted Hero rhythm (5 + 7 = 12)
                    if ($i === 6) return ['col' => 'col-span-1 md:col-span-1 lg:col-span-5', 'isBanner' => false, 'imgHeight' => 'h-64 sm:h-72 lg:h-80', 'isHero' => false];
                    if ($i === 7) return ['col' => 'col-span-1 md:col-span-1 lg:col-span-7', 'isBanner' => false, 'imgHeight' => 'h-64 sm:h-72 lg:h-80', 'isHero' => true];

                    // Row 4 (i = 8, 9):
                    if ($total === 8) {
                        return ['col' => 'col-span-1 md:col-span-2 lg:col-span-12', 'isBanner' => true, 'imgHeight' => 'h-64 sm:h-80 lg:h-full', 'isHero' => true];
                    }
                    return ['col' => 'col-span-1 md:col-span-1 lg:col-span-6', 'isBanner' => false, 'imgHeight' => 'h-56 sm:h-64 lg:h-72', 'isHero' => false];
                };
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-5 sm:gap-6">
                @foreach ($galeriList as $index => $item)
                    @php
                        $coverPhoto = $item->cover_photo;
                        $fotoCount = $item->foto_count;
                        $videoCount = $item->video_count;
                        $hasVideo = $videoCount > 0;
                        $orientation = $detectRatio($coverPhoto);
                        $bento = $getBentoConfig($index, $totalItems, $orientation, $hasVideo);
                    @endphp

                    @if (!empty($bento['isBanner']))
                        {{-- ================= BENTO PANORAMIC BANNER CARD (SPAN 12) ================= --}}
                        <article class="{{ $bento['col'] }} bg-white rounded-3xl border-2 border-brand-forest shadow-solid-md hover:shadow-solid-lg transition-all duration-500 transform-gpu hover:-translate-y-1 group relative overflow-hidden flex flex-col lg:flex-row justify-between"
                                 data-parallax="card-large">
                            
                            <!-- Media Area (58% on lg screens) -->
                            <div class="relative lg:w-7/12 {{ $bento['imgHeight'] }} min-h-[260px] lg:min-h-[320px] overflow-hidden bg-brand-sand border-b-2 lg:border-b-0 lg:border-r-2 border-brand-forest shrink-0">
                                <a href="{{ route('galeri.show', $item->id) }}" class="block w-full h-full relative" aria-label="{{ $item->title }}">
                                    @if ($coverPhoto)
                                        <img src="{{ asset('storage/' . $coverPhoto) }}" 
                                             class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105 transform-gpu" 
                                             alt="{{ $item->title }}"
                                             loading="lazy">
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center text-brand-forest/60 bg-brand-sand">
                                            <i class="bi bi-images text-5xl mb-2"></i>
                                            <span class="text-xs font-black uppercase tracking-wider">Dokumentasi Media Resmi</span>
                                        </div>
                                    @endif

                                    <!-- Subtle Gradient Scrim -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/10 to-transparent pointer-events-none transition-opacity duration-300 group-hover:opacity-40"></div>
                                </a>

                                <!-- Category Badge -->
                                <span class="absolute top-3 left-3 bg-white/95 backdrop-blur-sm text-brand-forest border-2 border-brand-forest text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-solid-sm flex items-center gap-1.5 z-10 pointer-events-none">
                                    <span class="w-1.5 h-1.5 rounded-full bg-brand-forest"></span>
                                    <span>{{ \App\Support\Organisasi::badgeLabel($item->kategori) }}</span>
                                </span>

                                <!-- Media Badges -->
                                <div class="absolute bottom-3 right-3 flex items-center gap-1.5 flex-wrap justify-end z-10 pointer-events-none">
                                    @if ($videoCount > 0)
                                        <span class="bg-brand-deep/90 backdrop-blur-sm text-white border border-brand-forest text-[10px] font-black px-2.5 py-1 rounded-full flex items-center gap-1.5 shadow-solid-sm">
                                            <i class="bi bi-play-circle-fill text-brand-accent"></i> 
                                            <span data-number-ticker="{{ $videoCount }}" data-suffix=" Video">{{ $videoCount }} Video</span>
                                        </span>
                                    @endif
                                    <span class="bg-brand-deep/90 backdrop-blur-sm text-white border border-brand-forest text-[10px] font-black px-2.5 py-1 rounded-full flex items-center gap-1.5 shadow-solid-sm">
                                        <i class="bi bi-camera-fill text-brand-lightmatcha"></i> 
                                        <span data-number-ticker="{{ $fotoCount }}" data-suffix=" Foto">{{ $fotoCount }} Foto</span>
                                    </span>
                                </div>

                                <!-- Video Center Play Indicator -->
                                @if ($videoCount > 0)
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/15 group-hover:bg-black/5 transition-colors pointer-events-none z-10">
                                        <div class="w-13 h-13 rounded-2xl bg-white/95 text-brand-forest border-2 border-brand-forest flex items-center justify-center shadow-solid-sm group-hover:scale-110 group-hover:bg-brand-forest group-hover:text-white transition-all duration-300">
                                            <i class="bi bi-play-fill text-2xl ml-0.5"></i>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Content Area (42% on lg screens) -->
                            <div class="p-5 sm:p-6 lg:p-7 flex flex-col justify-between flex-grow bg-white">
                                <div class="space-y-3 transform-gpu transition-all duration-300 lg:group-hover:-translate-y-1">
                                    <!-- Date & Location -->
                                    <div class="flex items-center gap-2 text-xs font-bold text-brand-darktext/70 flex-wrap">
                                        <span class="inline-flex items-center gap-1.5 bg-brand-cream/80 px-2.5 py-1 rounded-lg border border-brand-forest/20">
                                            <i class="bi bi-calendar3 text-brand-forest"></i> 
                                            <span>{{ $item->event_date ? \Carbon\Carbon::parse($item->event_date)->translatedFormat('d M Y') : '-' }}</span>
                                        </span>
                                        @if ($item->location)
                                            <span class="truncate max-w-[200px] inline-flex items-center gap-1 text-brand-darktext/80">
                                                <i class="bi bi-geo-alt-fill text-brand-accent text-xs"></i> 
                                                <span>{{ $item->location }}</span>
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Title -->
                                    <h3 class="text-xl sm:text-2xl font-black text-brand-deep leading-snug break-words group-hover:text-brand-matcha transition-colors">
                                        <a href="{{ route('galeri.show', $item->id) }}" class="text-inherit text-decoration-none">
                                            {{ $item->title }}
                                        </a>
                                    </h3>

                                    <!-- Description -->
                                    @if ($item->description)
                                        <p class="text-xs sm:text-sm font-medium text-brand-darktext/75 line-clamp-3 leading-relaxed m-0">
                                            {{ Str::limit(strip_tags($item->description), 160) }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Action Footer -->
                                <div class="pt-4 mt-5 border-t-2 border-brand-sand flex items-center justify-between">
                                    <span class="text-[11px] font-black uppercase tracking-wider text-brand-forest/70">
                                        Dokumentasi Resmi
                                    </span>
                                    
                                    <a href="{{ route('galeri.show', $item->id) }}" 
                                       class="inline-flex items-center gap-1.5 px-4.5 py-2.5 min-h-[44px] lg:min-h-0 rounded-xl bg-brand-sand group-hover:bg-brand-forest group-hover:text-white text-brand-forest font-black text-xs uppercase tracking-wider border-2 border-brand-forest shadow-solid-sm transition-all duration-300 btn-solid-press text-decoration-none">
                                        <span>Buka Galeri</span>
                                        <span class="text-sm leading-none transition-transform duration-300 group-hover:translate-x-1">&rarr;</span>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @else
                        {{-- ================= BENTO STANDARD / HERO VERTICAL CARD ================= --}}
                        <article class="{{ $bento['col'] }} bg-white rounded-3xl border-2 border-brand-forest shadow-solid-md hover:shadow-solid-lg transition-all duration-500 transform-gpu hover:-translate-y-1 group relative overflow-hidden flex flex-col justify-between"
                                 data-parallax="card">
                            
                            <div class="space-y-0 min-w-0 flex flex-col">
                                <!-- Media Image Area -->
                                <div class="relative {{ $bento['imgHeight'] }} overflow-hidden bg-brand-sand border-b-2 border-brand-forest">
                                    <a href="{{ route('galeri.show', $item->id) }}" class="block w-full h-full relative" aria-label="{{ $item->title }}">
                                        @if ($coverPhoto)
                                            <img src="{{ asset('storage/' . $coverPhoto) }}" 
                                                 class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105 transform-gpu" 
                                                 alt="{{ $item->title }}"
                                                 loading="lazy">
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center text-brand-forest/60 bg-brand-sand">
                                                <i class="bi bi-images text-4xl mb-1"></i>
                                                <span class="text-[11px] font-black uppercase tracking-wider">Dokumentasi Media</span>
                                            </div>
                                        @endif

                                        <!-- Subtle Gradient Scrim -->
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/10 to-transparent pointer-events-none transition-opacity duration-300 group-hover:opacity-40"></div>
                                    </a>

                                    <!-- Category Badge -->
                                    <span class="absolute top-3 left-3 bg-white/95 backdrop-blur-sm text-brand-forest border-2 border-brand-forest text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-solid-sm flex items-center gap-1.5 z-10 pointer-events-none">
                                        <span class="w-1.5 h-1.5 rounded-full bg-brand-forest"></span>
                                        <span>{{ \App\Support\Organisasi::badgeLabel($item->kategori) }}</span>
                                    </span>

                                    <!-- Media Badges -->
                                    <div class="absolute bottom-3 right-3 flex items-center gap-1.5 flex-wrap justify-end z-10 pointer-events-none">
                                        @if ($videoCount > 0)
                                            <span class="bg-brand-deep/90 backdrop-blur-sm text-white border border-brand-forest text-[10px] font-black px-2.5 py-1 rounded-full flex items-center gap-1.5 shadow-solid-sm">
                                                <i class="bi bi-play-circle-fill text-brand-accent"></i> 
                                                <span data-number-ticker="{{ $videoCount }}" data-suffix=" Video">{{ $videoCount }} Video</span>
                                            </span>
                                        @endif
                                        <span class="bg-brand-deep/90 backdrop-blur-sm text-white border border-brand-forest text-[10px] font-black px-2.5 py-1 rounded-full flex items-center gap-1.5 shadow-solid-sm">
                                            <i class="bi bi-camera-fill text-brand-lightmatcha"></i> 
                                            <span data-number-ticker="{{ $fotoCount }}" data-suffix=" Foto">{{ $fotoCount }} Foto</span>
                                        </span>
                                    </div>

                                    <!-- Video Center Play Indicator -->
                                    @if ($videoCount > 0)
                                        <div class="absolute inset-0 flex items-center justify-center bg-black/15 group-hover:bg-black/5 transition-colors pointer-events-none z-10">
                                            <div class="w-12 h-12 rounded-2xl bg-white/95 text-brand-forest border-2 border-brand-forest flex items-center justify-center shadow-solid-sm group-hover:scale-110 group-hover:bg-brand-forest group-hover:text-white transition-all duration-300">
                                                <i class="bi bi-play-fill text-2xl ml-0.5"></i>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Content Area -->
                                <div class="p-4 sm:p-5 flex flex-col justify-between flex-grow bg-white">
                                    <div class="space-y-2.5 transform-gpu transition-all duration-300 lg:group-hover:-translate-y-1">
                                        <!-- Date & Location -->
                                        <div class="flex items-center gap-2 text-xs font-bold text-brand-darktext/70 flex-wrap">
                                            <span class="inline-flex items-center gap-1.5 bg-brand-cream/80 px-2.5 py-1 rounded-lg border border-brand-forest/20">
                                                <i class="bi bi-calendar3 text-brand-forest"></i> 
                                                <span>{{ $item->event_date ? \Carbon\Carbon::parse($item->event_date)->translatedFormat('d M Y') : '-' }}</span>
                                            </span>
                                            @if ($item->location)
                                                <span class="truncate max-w-[150px] inline-flex items-center gap-1 text-brand-darktext/80">
                                                    <i class="bi bi-geo-alt-fill text-brand-accent text-xs"></i> 
                                                    <span>{{ $item->location }}</span>
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Title -->
                                        <h3 class="{{ $bento['isHero'] ? 'text-xl sm:text-2xl' : 'text-lg sm:text-xl' }} font-black text-brand-deep leading-snug line-clamp-2 break-words group-hover:text-brand-matcha transition-colors">
                                            <a href="{{ route('galeri.show', $item->id) }}" class="text-inherit text-decoration-none">
                                                {{ $item->title }}
                                            </a>
                                        </h3>

                                        <!-- Description -->
                                        @if ($item->description)
                                            <p class="text-xs font-medium text-brand-darktext/75 line-clamp-2 leading-relaxed m-0">
                                                {{ Str::limit(strip_tags($item->description), 110) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Card Action Footer -->
                            <div class="px-4 sm:px-5 pb-4 sm:pb-5 pt-3.5 border-t-2 border-brand-sand flex items-center justify-between mt-auto bg-white">
                                <span class="text-[11px] font-black uppercase tracking-wider text-brand-forest/70">
                                    Dokumentasi Resmi
                                </span>
                                
                                <a href="{{ route('galeri.show', $item->id) }}" 
                                   class="inline-flex items-center gap-1.5 px-4 py-2 min-h-[44px] lg:min-h-0 rounded-xl bg-brand-sand group-hover:bg-brand-forest group-hover:text-white text-brand-forest font-black text-xs uppercase tracking-wider border-2 border-brand-forest shadow-solid-sm transition-all duration-300 btn-solid-press text-decoration-none">
                                    <span>Buka Galeri</span>
                                    <span class="text-sm leading-none transition-transform duration-300 group-hover:translate-x-1">&rarr;</span>
                                </a>
                            </div>
                        </article>
                    @endif
                @endforeach
            </div>

            <!-- PAGINATION -->
            <div class="mt-10 flex justify-center pt-4">
                {{ $galeriList->links() }}
            </div>

        @endif
    </div>
</div>

@endsection