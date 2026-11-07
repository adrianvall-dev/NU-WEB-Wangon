@extends('layouts.app')

@section('title', 'Dokumentasi ' . $kegiatan->title . ' - Organisasi NU Wangon')

@section('content')

@php
    $fotoRecords = $kegiatan->fotos->filter(fn($f) => $f->isFoto());
    $videoRecords = $kegiatan->fotos->filter(fn($f) => $f->isVideo());

    // Kumpulkan seluruh foto dokumentasi (dari relasi fotos bertipe foto + thumbnail image jika ada)
    $allPhotos = collect();

    foreach ($fotoRecords as $foto) {
        $photoUrl = asset('storage/' . $foto->file_path);
        $allPhotos->push((object)[
            'id' => $foto->id,
            'url' => $photoUrl,
            'caption' => $foto->caption,
            'created_at' => $foto->created_at,
        ]);
    }

    // Jika ada image utama dan belum termasuk di fotos, tambahkan
    if ($kegiatan->image) {
        $coverUrl = asset('storage/' . $kegiatan->image);
        if (!$allPhotos->contains('url', $coverUrl)) {
            $allPhotos->prepend((object)[
                'id' => 'cover',
                'url' => $coverUrl,
                'caption' => 'Foto Utama Kegiatan',
                'created_at' => $kegiatan->created_at,
            ]);
        }
    }

    // Kumpulkan seluruh video dokumentasi
    $allVideos = collect();
    foreach ($videoRecords as $video) {
        $allVideos->push((object)[
            'id' => $video->id,
            'url' => asset('storage/' . $video->file_path),
            'caption' => $video->caption,
            'created_at' => $video->created_at,
        ]);
    }
@endphp

<div class="galeri-detail-wrapper min-h-screen bg-brand-cream text-brand-darktext font-sans antialiased pb-24 pt-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- ================= 1. BREADCRUMB & BACK NAV ================= -->
        <nav class="flex items-center justify-between gap-4 text-xs font-bold text-brand-darktext/70">
            <div class="flex items-center gap-2 overflow-hidden">
                <a href="{{ route('home') }}" class="hover:text-brand-matcha transition-colors text-decoration-none">Beranda</a>
                <span>/</span>
                <a href="{{ route('galeri.index') }}" class="hover:text-brand-matcha transition-colors text-decoration-none">Galeri</a>
                <span>/</span>
                <span class="text-brand-deep font-black truncate max-w-xs sm:max-w-md">{{ $kegiatan->title }}</span>
            </div>

            <a href="{{ route('galeri.index') }}" 
               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-white border-2 border-brand-forest hover:bg-brand-sand text-brand-forest text-xs font-black uppercase tracking-wider transition-all btn-solid-press shadow-solid-sm text-decoration-none shrink-0">
                <i class="bi bi-arrow-left"></i>
                <span class="hidden sm:inline">Semua Galeri</span>
            </a>
        </nav>

        <!-- ================= 2. EVENT HERO CARD ================= -->
        <header class="bg-white rounded-2xl sm:rounded-3xl border-2 border-brand-forest p-4 sm:p-10 shadow-solid-md space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-2.5 flex-wrap">
                    <span class="inline-block bg-brand-lightmatcha text-brand-forest text-xs font-black uppercase tracking-wider px-3.5 py-1.5 rounded-full border-2 border-brand-forest shadow-solid-sm">
                        {{ \App\Support\Organisasi::badgeLabel($kegiatan->kategori) }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 bg-brand-sand text-brand-forest text-xs font-black uppercase tracking-wider px-3.5 py-1.5 rounded-full border-2 border-brand-forest shadow-solid-sm">
                        <i class="bi bi-check-circle-fill"></i> Kegiatan Selesai
                    </span>
                </div>

                <div class="flex items-center gap-2 flex-wrap">
                    @if ($allVideos->isNotEmpty())
                        <div class="text-xs font-black bg-brand-deep text-white border border-brand-forest px-3.5 py-1.5 rounded-full flex items-center gap-1.5 shadow-solid-sm">
                            <i class="bi bi-play-circle-fill text-brand-accent"></i>
                            <span data-number-ticker="{{ $allVideos->count() }}" data-suffix=" Video Dokumentasi">{{ $allVideos->count() }} Video Dokumentasi</span>
                        </div>
                    @endif
                    <div class="text-xs font-black bg-brand-deep text-white border border-brand-forest px-3.5 py-1.5 rounded-full flex items-center gap-1.5 shadow-solid-sm">
                        <i class="bi bi-camera-fill text-brand-lightmatcha"></i>
                        <span data-number-ticker="{{ $allPhotos->count() }}" data-suffix=" Foto Dokumentasi">{{ $allPhotos->count() }} Foto Dokumentasi</span>
                    </div>
                </div>
            </div>

            <div class="space-y-4 max-w-4xl">
                <h1 data-text-animate="blurInUp" class="text-2xl sm:text-4xl lg:text-5xl font-black text-brand-deep leading-tight tracking-tight">
                    {{ $kegiatan->title }}
                </h1>

                <!-- Meta Row -->
                <div class="flex flex-wrap items-center gap-4 sm:gap-6 text-xs sm:text-sm font-bold text-brand-darktext/75 pt-1">
                    <div class="flex items-center gap-2.5">
                        <span class="w-9 h-9 rounded-xl bg-brand-sand border-2 border-brand-forest text-brand-forest flex items-center justify-center shrink-0 shadow-solid-sm">
                            <i class="bi bi-calendar3"></i>
                        </span>
                        <div>
                            <span class="block text-[10px] uppercase tracking-wider text-brand-forest/70 font-black">Tanggal Pelaksanaan</span>
                            <span class="font-black text-brand-deep">
                                {{ $kegiatan->event_date ? \Carbon\Carbon::parse($kegiatan->event_date)->translatedFormat('l, d F Y') : '-' }}
                            </span>
                        </div>
                    </div>

                    @if ($kegiatan->start_time)
                        <div class="flex items-center gap-2.5">
                            <span class="w-9 h-9 rounded-xl bg-brand-sand border-2 border-brand-forest text-brand-forest flex items-center justify-center shrink-0 shadow-solid-sm">
                                <i class="bi bi-clock"></i>
                            </span>
                            <div>
                                <span class="block text-[10px] uppercase tracking-wider text-brand-forest/70 font-black">Waktu</span>
                                <span class="font-black text-brand-deep">
                                    {{ \Carbon\Carbon::parse($kegiatan->start_time)->format('H:i') }}
                                    @if($kegiatan->end_time) - {{ \Carbon\Carbon::parse($kegiatan->end_time)->format('H:i') }} @endif WIB
                                </span>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center gap-2.5">
                        <span class="w-9 h-9 rounded-xl bg-brand-sand border-2 border-brand-forest text-brand-forest flex items-center justify-center shrink-0 shadow-solid-sm">
                            <i class="bi bi-geo-alt"></i>
                        </span>
                        <div>
                            <span class="block text-[10px] uppercase tracking-wider text-brand-forest/70 font-black">Lokasi Acara</span>
                            <span class="font-black text-brand-deep">
                                {{ $kegiatan->location }}
                            </span>
                        </div>
                    </div>

                    @if($kegiatan->hasCoordinates())
                        <div class="flex items-center">
                            <a href="{{ $kegiatan->google_maps_url }}" target="_blank" 
                               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-brand-forest text-white border-2 border-brand-deep hover:bg-brand-deep text-xs font-black uppercase tracking-wider transition-all btn-solid-press shadow-solid-sm text-decoration-none">
                                <i class="bi bi-compass"></i>
                                <span>Rute Maps</span>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Description from Database -->
                @if ($kegiatan->description)
                    <div class="pt-5 border-t-2 border-brand-sand">
                        <h3 class="text-xs uppercase tracking-wider font-black text-brand-deep mb-2">
                            Tentang Kegiatan
                        </h3>
                        <div class="text-xs sm:text-sm font-medium text-brand-darktext/85 leading-relaxed space-y-2 whitespace-pre-line">
                            {{ $kegiatan->description }}
                        </div>
                    </div>
                @endif
            </div>
        </header>

        <!-- ================= 2.1 MEDIA FILTER SEGMENTED CONTROL ================= -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-1">
            <div class="inline-flex max-w-full overflow-x-auto scrollbar-none p-1.5 rounded-2xl bg-white border-2 border-brand-forest shadow-solid-sm self-start gap-1" role="tablist">
                <button type="button" 
                        role="tab"
                        id="tabMediaSemua" 
                        onclick="switchMediaTab('semua')" 
                        class="media-tab-btn whitespace-nowrap px-3.5 sm:px-5 py-2 sm:py-2.5 rounded-xl text-[11px] sm:text-xs font-black uppercase tracking-wider transition-all flex items-center gap-2 bg-brand-forest text-white border-2 border-brand-deep shadow-solid-sm btn-solid-press cursor-pointer">
                    <i class="bi bi-grid-fill text-xs"></i>
                    <span>Semua Media</span>
                    <span class="text-[10px] bg-white/20 px-2 py-0.5 rounded-full font-black">
                        <span data-number-ticker="{{ $allPhotos->count() + $allVideos->count() }}">{{ $allPhotos->count() + $allVideos->count() }}</span>
                    </span>
                </button>
                <button type="button" 
                        role="tab"
                        id="tabMediaFoto" 
                        onclick="switchMediaTab('foto')" 
                        class="media-tab-btn whitespace-nowrap px-3.5 sm:px-5 py-2 sm:py-2.5 rounded-xl text-[11px] sm:text-xs font-black uppercase tracking-wider transition-all flex items-center gap-2 text-brand-forest bg-white border-2 border-brand-forest hover:bg-brand-sand btn-solid-press cursor-pointer">
                    <i class="bi bi-camera-fill text-xs"></i>
                    <span>Foto</span>
                    <span class="text-[10px] bg-brand-sand text-brand-forest px-2 py-0.5 rounded-full font-black border border-brand-forest/40">
                        <span data-number-ticker="{{ $allPhotos->count() }}">{{ $allPhotos->count() }}</span>
                    </span>
                </button>
                <button type="button" 
                        role="tab"
                        id="tabMediaVideo" 
                        onclick="switchMediaTab('video')" 
                        class="media-tab-btn whitespace-nowrap px-3.5 sm:px-5 py-2 sm:py-2.5 rounded-xl text-[11px] sm:text-xs font-black uppercase tracking-wider transition-all flex items-center gap-2 text-brand-forest bg-white border-2 border-brand-forest hover:bg-brand-sand btn-solid-press cursor-pointer">
                    <i class="bi bi-play-circle-fill text-xs text-brand-accent"></i>
                    <span>Video</span>
                    <span class="text-[10px] bg-brand-sand text-brand-forest px-2 py-0.5 rounded-full font-black border border-brand-forest/40">
                        <span data-number-ticker="{{ $allVideos->count() }}">{{ $allVideos->count() }}</span>
                    </span>
                </button>
            </div>

            <div class="text-xs font-bold text-brand-darktext/70 hidden sm:block">
                Menampilkan: <strong id="activeTabLabel" class="text-brand-deep font-black">Semua Media (Foto &amp; Video)</strong>
            </div>
        </div>



        <!-- ================= EMPTY VIDEO STATE ================= -->
        <div id="emptyVideoContainer" class="hidden bg-white rounded-3xl border-2 border-dashed border-brand-forest p-10 sm:p-12 text-center flex flex-col items-center justify-center space-y-3 shadow-solid-sm">
            <div class="w-14 h-14 rounded-2xl bg-brand-sand text-brand-forest border-2 border-brand-forest flex items-center justify-center text-2xl shadow-solid-sm">
                <i class="bi bi-film text-brand-accent"></i>
            </div>
            <h4 class="font-black text-xl text-brand-deep tracking-tight">Belum Ada Video Dokumentasi</h4>
            <p class="text-xs sm:text-sm text-brand-darktext/75 max-w-sm">
                Kegiatan ini belum memiliki rekaman video dokumentasi. Silakan pilih tab <strong>Foto</strong> atau <strong>Semua</strong> untuk melihat dokumentasi yang tersedia.
            </p>
            <button type="button" onclick="switchMediaTab('semua')" class="mt-2 inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-brand-forest hover:bg-brand-deep text-white text-xs font-black uppercase tracking-wider transition-all btn-solid-press shadow-solid-sm border-2 border-brand-deep cursor-pointer">
                <span>Tampilkan Semua Dokumentasi</span>
            </button>
        </div>

        <!-- ================= EMPTY PHOTO STATE ================= -->
        <div id="emptyPhotoContainer" class="hidden bg-white rounded-3xl border-2 border-dashed border-brand-forest p-10 sm:p-12 text-center flex flex-col items-center justify-center space-y-3 shadow-solid-sm">
            <div class="w-14 h-14 rounded-2xl bg-brand-sand text-brand-forest border-2 border-brand-forest flex items-center justify-center text-2xl shadow-solid-sm">
                <i class="bi bi-images"></i>
            </div>
            <h4 class="font-black text-xl text-brand-deep tracking-tight">Belum Ada Foto Dokumentasi</h4>
            <p class="text-xs sm:text-sm text-brand-darktext/75 max-w-sm">
                Dokumentasi foto untuk kegiatan ini belum diunggah oleh pengurus.
            </p>
            @if($allVideos->isNotEmpty())
                <button type="button" onclick="switchMediaTab('video')" class="mt-2 inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-brand-forest hover:bg-brand-deep text-white text-xs font-black uppercase tracking-wider transition-all btn-solid-press shadow-solid-sm border-2 border-brand-deep cursor-pointer">
                    <i class="bi bi-play-circle-fill text-brand-accent"></i>
                    <span>Lihat Video Dokumentasi</span>
                </button>
            @endif
        </div>

        <!-- ================= 3. VIDEO DOCUMENTATION SECTION ================= -->
        <div id="videoContainer" class="{{ $allVideos->isEmpty() ? 'hidden' : '' }}">
            @if ($allVideos->isNotEmpty())
                <section class="space-y-4">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-deep text-white border border-brand-forest text-xs font-black uppercase tracking-wider mb-2 shadow-solid-sm">
                            <i class="bi bi-play-circle-fill text-brand-accent"></i>
                            <span data-number-ticker="{{ $allVideos->count() }}" data-suffix=" Video Dokumentasi">{{ $allVideos->count() }} Video Dokumentasi</span>
                        </div>
                        <h2 data-text-animate="blurInUp" class="text-2xl sm:text-3xl font-black text-brand-deep tracking-tight">
                            Video Dokumentasi Acara
                        </h2>
                        <p class="text-xs sm:text-sm font-medium text-brand-darktext/75">
                            Putar video dokumentasi langsung di bawah ini. Rasio asli video terjaga seutuhnya tanpa pemotongan (portrait maupun landscape).
                        </p>
                    </div>

                    <!-- Responsive Grid: Supports portrait (9:16) & landscape (16:9) without cropping -->
                    <div class="grid grid-cols-1 {{ $allVideos->count() > 1 ? 'md:grid-cols-2' : 'max-w-4xl mx-auto' }} gap-6">
                        @foreach ($allVideos as $video)
                            <div class="bg-white rounded-3xl border-2 border-brand-forest p-4 sm:p-5 shadow-solid-md flex flex-col justify-between overflow-hidden">
                                <!-- Container with black background and object-contain -->
                                <div class="rounded-2xl overflow-hidden bg-black border-2 border-brand-forest flex items-center justify-center" style="min-height: 240px;">
                                    <video controls 
                                           playsinline 
                                           preload="metadata" 
                                           class="w-full h-auto max-h-[520px] object-contain rounded-2xl block">
                                        <source src="{{ $video->url }}">
                                        Browser Anda tidak mendukung pemutar video HTML5.
                                    </video>
                                </div>

                                @if ($video->caption)
                                    <div class="mt-3.5 px-1">
                                        <p class="text-xs sm:text-sm text-brand-darktext font-bold leading-snug m-0">
                                            <i class="bi bi-film text-brand-accent me-1.5"></i>{{ $video->caption }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <!-- ================= 4. PHOTO DOCUMENTATION MASONRY GALLERY ================= -->
        <div id="photoContainer" class="{{ $allPhotos->isEmpty() ? 'hidden' : '' }}">
            <section class="space-y-4">
                <div>
                    <h2 data-text-animate="blurInUp" class="text-2xl sm:text-3xl font-black text-brand-deep tracking-tight">
                        Foto Dokumentasi Acara
                    </h2>
                    <p class="text-xs sm:text-sm font-medium text-brand-darktext/75">
                        Klik pada foto apa pun untuk memperbesar dalam tampilan penuh layar dengan resolusi penuh.
                    </p>
                </div>

                @if ($allPhotos->isNotEmpty())
                    <!-- CSS MASONRY LAYOUT: RETAINS FULL NATURAL ASPECT RATIO OF EVERY PHOTO -->
                    <div class="columns-2 sm:columns-2 lg:columns-3 xl:columns-4 gap-3 sm:gap-4 space-y-3 sm:space-y-4">
                        @foreach ($allPhotos as $index => $photo)
                            <div class="break-inside-avoid rounded-2xl overflow-hidden bg-white border-2 border-brand-forest shadow-solid-sm hover:shadow-solid-md transition-all duration-300 transform-gpu group cursor-pointer"
                                 onclick="openLightbox({{ $index }})">
                                
                                <div class="relative overflow-hidden bg-brand-sand">
                                    <img src="{{ $photo->url }}" 
                                         alt="{{ $photo->caption ?? $kegiatan->title }}" 
                                         class="w-full h-auto block transition-transform duration-700 ease-out group-hover:scale-105 transform-gpu" 
                                         loading="lazy">

                                    <!-- Hover zoom icon overlay -->
                                    <div class="absolute inset-0 bg-brand-deep/30 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <span class="w-11 h-11 rounded-xl bg-white border-2 border-brand-forest text-brand-forest flex items-center justify-center shadow-solid-sm transform-gpu group-hover:scale-110 transition-transform duration-300">
                                            <i class="bi bi-arrows-fullscreen text-sm"></i>
                                        </span>
                                    </div>
                                </div>

                                @if ($photo->caption)
                                    <div class="p-3 text-[11px] font-bold text-brand-darktext/80 border-t-2 border-brand-sand leading-snug">
                                        {{ $photo->caption }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>

        <!-- ================= 5. RELATED GALERI ================= -->
        @if (isset($relatedGaleri) && $relatedGaleri->isNotEmpty())
            <div class="pt-8 sm:pt-10 border-t-2 border-brand-sand space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3">
                    <div>
                        <span data-text-animate="blurInUp" class="text-xs font-black uppercase tracking-wider text-brand-matcha">DOKUMENTASI LAINNYA</span>
                        <h3 data-text-animate="blurInUp" class="text-2xl sm:text-3xl font-black text-brand-deep tracking-tight mt-0.5">Galeri Kegiatan Lainnya</h3>
                        <p class="text-xs sm:text-sm font-medium text-brand-darktext/75 mb-0">Dokumentasi momen kegiatan dan syiar Nahdlatul Ulama Wangon.</p>
                    </div>
                    <a href="{{ route('galeri.index') }}" 
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border-2 border-brand-forest hover:bg-brand-sand text-brand-forest text-xs font-black uppercase tracking-wider transition-all btn-solid-press shadow-solid-sm text-decoration-none self-start sm:self-auto shrink-0">
                        <span>Semua Galeri</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-6 lg:gap-7 related-content-grid">
                    @foreach ($relatedGaleri as $relItem)
                        @php
                            $i = $loop->index;
                            $relCover = $relItem->cover_photo;
                            $relGaleriImg = $relCover 
                                ? asset('storage/' . $relCover) 
                                : asset('images/landing/gallery-' . (($i % 3) + 1) . '.jpg');
                            $mobileGaleriSpan = ($i === 0) ? 'col-span-2 lg:col-span-1' : 'col-span-1 lg:col-span-1';
                        @endphp
                        <article class="{{ $mobileGaleriSpan }} bg-white rounded-2xl sm:rounded-3xl border-2 border-brand-forest shadow-solid-md hover:shadow-solid-lg transition-all duration-300 flex flex-col justify-between group overflow-hidden isolate h-auto min-w-0"
                                 data-parallax="card">
                            <div class="flex flex-col w-full">
                                <!-- Large Photo Focal Point (16:9 Widescreen) -->
                                <div class="relative w-full aspect-[16/9] sm:aspect-[16/10] overflow-hidden rounded-t-[14px] sm:rounded-t-[22px] bg-brand-sand border-b-2 border-brand-forest shrink-0">
                                    <a href="{{ route('galeri.show', $relItem->id) }}" class="block w-full h-full relative" aria-label="{{ $relItem->title }}">
                                        <img src="{{ $relGaleriImg }}" 
                                             class="w-full h-full object-cover block rounded-t-[14px] sm:rounded-t-[22px] group-hover:scale-105 transition-transform duration-700 ease-out" 
                                             alt="{{ $relItem->title }}" 
                                             loading="lazy">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent opacity-60 group-hover:opacity-30 transition-opacity pointer-events-none"></div>
                                    </a>
                                    <!-- Category Badge Top Left -->
                                    <div class="absolute top-2.5 left-2.5 sm:top-3.5 sm:left-3.5 z-10 pointer-events-none">
                                        <span class="inline-flex items-center px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full bg-white text-brand-forest border border-brand-forest text-[10px] sm:text-xs font-black uppercase tracking-wider shadow-solid-xs">
                                            {{ \App\Support\Organisasi::badgeLabel($relItem->kategori) }}
                                        </span>
                                    </div>
                                    <!-- Photo Count Badge Bottom Right -->
                                    <div class="absolute bottom-2.5 right-2.5 sm:bottom-3.5 sm:right-3.5 z-10 pointer-events-none">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-full bg-brand-deep/85 text-white border border-brand-forest text-[10px] sm:text-xs font-black shadow-solid-xs">
                                            <i class="bi bi-camera-fill text-brand-lightmatcha text-[10px]"></i>
                                            <span>{{ $relItem->foto_count }} Foto</span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Content Details -->
                                <div class="p-3.5 sm:p-5 lg:p-6 flex flex-col flex-1 text-left w-full">
                                    <div class="flex items-center justify-between gap-2 text-[10px] sm:text-xs font-bold text-brand-darktext/70">
                                        <span class="inline-flex items-center gap-1.5">
                                            <i class="bi bi-calendar3 text-brand-forest text-[10px] sm:text-xs"></i>
                                            {{ $relItem->event_date ? \Carbon\Carbon::parse($relItem->event_date)->translatedFormat('d M Y') : '-' }}
                                        </span>
                                        <span class="text-[10px] font-black uppercase tracking-wider text-brand-forest/70">
                                            Dokumentasi
                                        </span>
                                    </div>

                                    <h4 class="font-black text-sm sm:text-base lg:text-lg text-brand-deep leading-snug group-hover:text-brand-matcha transition-colors line-clamp-2 mt-2 sm:mt-2.5 mb-0">
                                        <a href="{{ route('galeri.show', $relItem->id) }}" class="text-inherit text-decoration-none block">
                                            {{ $relItem->title }}
                                        </a>
                                    </h4>
                                </div>
                            </div>

                            <!-- Footer Action -->
                            <div class="px-3.5 py-2.5 sm:px-5 sm:py-3.5 lg:px-6 border-t-2 border-brand-sand bg-brand-cream/50 flex items-center justify-between w-full mt-auto">
                                <span class="text-[10px] sm:text-xs font-black uppercase tracking-wider text-brand-forest/80">
                                    Wangon
                                </span>
                                <a href="{{ route('galeri.show', $relItem->id) }}" 
                                   class="inline-flex items-center gap-1 sm:gap-1.5 h-8 sm:h-9 px-3 sm:px-4 rounded-xl bg-brand-forest hover:bg-brand-deep text-white font-black text-[11px] sm:text-xs uppercase tracking-wider border border-brand-deep shadow-solid-xs transition-all btn-solid-press text-decoration-none">
                                    <span>Lihat Foto</span>
                                    <span class="text-sm">&rarr;</span>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>

<!-- ================= 6. LIGHTBOX MODAL ================= -->
<div id="photoLightbox" 
     class="fixed inset-0 z-[10000] hidden items-center justify-center p-3 sm:p-6 md:p-8 select-none"
     role="dialog" 
     aria-modal="true"
     aria-label="Penampil Foto Layar Penuh">
    
    <!-- 1. Dedicated Backdrop Overlay: Clicking backdrop ONLY closes the viewer -->
    <div id="photoLightboxBackdrop" 
         class="absolute inset-0 bg-black/92 backdrop-blur-md cursor-pointer transition-opacity z-10"
         title="Klik di luar foto untuk menutup"></div>

    <!-- 2. Close Button (Top Right) -->
    <button type="button" 
            id="lightboxCloseBtn"
            class="absolute top-3.5 right-3.5 sm:top-5 sm:right-5 z-50 w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-white/90 hover:bg-white text-brand-forest hover:text-brand-deep border-2 border-brand-forest/25 hover:border-brand-forest flex items-center justify-center text-lg sm:text-xl transition-all hover:scale-105 active:scale-95 cursor-pointer shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-brand-forest/40"
            aria-label="Tutup Pratinjau (Escape)">
        <i class="bi bi-x-lg"></i>
    </button>

    <!-- 3. Prev Button (Left) -->
    @if ($allPhotos->count() > 1)
        <button type="button" 
                id="lightboxPrevBtn"
                class="absolute left-3 sm:left-6 md:left-8 top-1/2 -translate-y-1/2 z-50 w-11 h-11 sm:w-13 sm:h-13 rounded-full bg-white/90 hover:bg-white text-brand-forest hover:text-brand-deep border-2 border-brand-forest/25 hover:border-brand-forest flex items-center justify-center text-xl sm:text-2xl transition-all hover:scale-110 active:scale-95 cursor-pointer shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-brand-forest/40"
                aria-label="Foto Sebelumnya (Panah Kiri)">
            <i class="bi bi-chevron-left"></i>
        </button>
    @endif

    <!-- 4. Next Button (Right) -->
    @if ($allPhotos->count() > 1)
        <button type="button" 
                id="lightboxNextBtn"
                class="absolute right-3 sm:right-6 md:right-8 top-1/2 -translate-y-1/2 z-50 w-11 h-11 sm:w-13 sm:h-13 rounded-full bg-white/90 hover:bg-white text-brand-forest hover:text-brand-deep border-2 border-brand-forest/25 hover:border-brand-forest flex items-center justify-center text-xl sm:text-2xl transition-all hover:scale-110 active:scale-95 cursor-pointer shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-brand-forest/40"
                aria-label="Foto Selanjutnya (Panah Kanan)">
            <i class="bi bi-chevron-right"></i>
        </button>
    @endif

    <!-- 5. Content Container: Clicks inside here NEVER close the viewer -->
    <div id="lightboxContent" class="relative z-30 max-w-5xl max-h-[90vh] flex flex-col items-center justify-center pointer-events-auto">
        <img id="lightboxImage" 
             src="" 
             alt="Foto Dokumentasi" 
             class="max-w-full max-h-[78vh] object-contain rounded-2xl border-2 border-white/20 shadow-2xl block transition-opacity duration-200 cursor-default">
        
        <div class="mt-3.5 sm:mt-4 text-center text-white space-y-1.5 pointer-events-auto max-w-xl mx-auto px-4">
            <p id="lightboxCaption" class="text-xs sm:text-sm font-bold text-white/90 leading-relaxed m-0 drop-shadow-sm"></p>
            <span id="lightboxCounter" class="inline-block text-[11px] font-mono font-bold text-brand-lightmatcha bg-black/60 px-3.5 py-1 rounded-full border border-white/15 tracking-wider shadow-inner"></span>
        </div>
    </div>
</div>

<script>
    const photos = @json($allPhotos);
    const hasPhotos = {{ $allPhotos->isNotEmpty() ? 'true' : 'false' }};
    const hasVideos = {{ $allVideos->isNotEmpty() ? 'true' : 'false' }};
    let currentIndex = 0;

    function switchMediaTab(tab) {
        const tabs = ['semua', 'foto', 'video'];
        const tabConfigs = {
            'semua': {
                id: 'tabMediaSemua',
                label: 'Semua Media (Foto & Video)'
            },
            'foto': {
                id: 'tabMediaFoto',
                label: 'Foto Dokumentasi'
            },
            'video': {
                id: 'tabMediaVideo',
                label: 'Video Dokumentasi'
            }
        };

        if (!tabs.includes(tab)) return;

        // 1. Update button styling with solid grotesque classes
        tabs.forEach(t => {
            const btn = document.getElementById(tabConfigs[t].id);
            if (!btn) return;

            if (t === tab) {
                btn.className = 'media-tab-btn px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all flex items-center gap-2 bg-brand-forest text-white border-2 border-brand-deep shadow-solid-sm btn-solid-press cursor-pointer';
                const badge = btn.querySelector('span:last-child');
                if (badge) {
                    badge.className = 'text-[10px] bg-white/20 px-2 py-0.5 rounded-full font-black';
                }
                const icon = btn.querySelector('i');
                if (icon && t === 'video') {
                    icon.className = 'bi bi-play-circle-fill text-xs text-white';
                }
            } else {
                btn.className = 'media-tab-btn px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all flex items-center gap-2 text-brand-forest bg-white border-2 border-brand-forest hover:bg-brand-sand btn-solid-press cursor-pointer';
                const badge = btn.querySelector('span:last-child');
                if (badge) {
                    badge.className = 'text-[10px] bg-brand-sand text-brand-forest px-2 py-0.5 rounded-full font-black border border-brand-forest/40';
                }
                const icon = btn.querySelector('i');
                if (icon && t === 'video') {
                    icon.className = 'bi bi-play-circle-fill text-xs text-brand-accent';
                }
            }
        });

        // 2. Update active label
        const labelEl = document.getElementById('activeTabLabel');
        if (labelEl) {
            labelEl.textContent = tabConfigs[tab].label;
        }

        // 3. Pause video if switching away from video / to foto
        if (tab === 'foto') {
            document.querySelectorAll('#videoContainer video').forEach(v => {
                if (!v.paused) v.pause();
            });
        }

        // 4. Toggle sections
        const videoContainer = document.getElementById('videoContainer');
        const emptyVideoContainer = document.getElementById('emptyVideoContainer');
        const photoContainer = document.getElementById('photoContainer');
        const emptyPhotoContainer = document.getElementById('emptyPhotoContainer');

        if (tab === 'semua') {
            if (videoContainer) videoContainer.classList.toggle('hidden', !hasVideos);
            if (emptyVideoContainer) emptyVideoContainer.classList.add('hidden');
            if (photoContainer) photoContainer.classList.toggle('hidden', !hasPhotos);
            if (emptyPhotoContainer) emptyPhotoContainer.classList.add('hidden');
        } else if (tab === 'foto') {
            if (videoContainer) videoContainer.classList.add('hidden');
            if (emptyVideoContainer) emptyVideoContainer.classList.add('hidden');
            if (photoContainer) photoContainer.classList.toggle('hidden', !hasPhotos);
            if (emptyPhotoContainer) emptyPhotoContainer.classList.toggle('hidden', hasPhotos);
        } else if (tab === 'video') {
            if (videoContainer) videoContainer.classList.toggle('hidden', !hasVideos);
            if (emptyVideoContainer) emptyVideoContainer.classList.toggle('hidden', hasVideos);
            if (photoContainer) photoContainer.classList.add('hidden');
            if (emptyPhotoContainer) emptyPhotoContainer.classList.add('hidden');
        }

        // 5. Update URL query parameter seamlessly without reload
        if (window.history && window.history.replaceState) {
            const url = new URL(window.location.href);
            if (tab === 'semua') {
                url.searchParams.delete('media');
            } else {
                url.searchParams.set('media', tab);
            }
            window.history.replaceState({}, '', url.toString());
        }
    }

    // Auto-select tab if 'media' query param is present on page load
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const media = urlParams.get('media');
        if (media && ['semua', 'foto', 'video'].includes(media)) {
            switchMediaTab(media);
        }
    });

    function openLightbox(index) {
        if (!photos || photos.length === 0) return;
        currentIndex = typeof index === 'number' ? index : 0;
        if (currentIndex < 0) currentIndex = 0;
        if (currentIndex >= photos.length) currentIndex = photos.length - 1;

        updateLightboxView();

        const lb = document.getElementById('photoLightbox');
        if (!lb) return;
        lb.classList.remove('hidden');
        lb.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox(e) {
        if (e && typeof e.stopPropagation === 'function') {
            e.stopPropagation();
        }
        const lb = document.getElementById('photoLightbox');
        if (!lb) return;
        lb.classList.add('hidden');
        lb.classList.remove('flex');
        document.body.style.overflow = '';
    }

    function updateLightboxView() {
        if (!photos || photos.length === 0) return;
        const photo = photos[currentIndex];
        const img = document.getElementById('lightboxImage');
        const counter = document.getElementById('lightboxCounter');
        const caption = document.getElementById('lightboxCaption');

        if (img && photo) {
            img.src = photo.url;
            img.alt = photo.caption || 'Foto Dokumentasi';
        }
        if (counter) {
            counter.textContent = `${currentIndex + 1} / ${photos.length}`;
        }
        if (caption) {
            caption.textContent = photo?.caption || '';
            caption.style.display = photo?.caption ? 'block' : 'none';
        }
    }

    function nextPhoto(e) {
        if (e && typeof e.stopPropagation === 'function') {
            e.stopPropagation();
        }
        if (!photos || photos.length <= 1) return;
        currentIndex = (currentIndex + 1) % photos.length;
        updateLightboxView();
    }

    function prevPhoto(e) {
        if (e && typeof e.stopPropagation === 'function') {
            e.stopPropagation();
        }
        if (!photos || photos.length <= 1) return;
        currentIndex = (currentIndex - 1 + photos.length) % photos.length;
        updateLightboxView();
    }

    // Attach explicit click & touch handlers with complete event isolation
    document.addEventListener('DOMContentLoaded', function() {
        const backdrop = document.getElementById('photoLightboxBackdrop');
        const closeBtn = document.getElementById('lightboxCloseBtn');
        const prevBtn = document.getElementById('lightboxPrevBtn');
        const nextBtn = document.getElementById('lightboxNextBtn');
        const content = document.getElementById('lightboxContent');
        const image = document.getElementById('lightboxImage');

        if (backdrop) {
            backdrop.addEventListener('click', closeLightbox);
        }
        if (closeBtn) {
            closeBtn.addEventListener('click', closeLightbox);
        }
        if (prevBtn) {
            prevBtn.addEventListener('click', prevPhoto);
        }
        if (nextBtn) {
            nextBtn.addEventListener('click', nextPhoto);
        }
        if (content) {
            // Prevent clicks on content or caption from closing viewer
            content.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            // Mobile Touch Swipe gesture support
            let touchStartX = 0;
            let touchStartY = 0;

            content.addEventListener('touchstart', function(e) {
                if (e.touches && e.touches.length === 1) {
                    touchStartX = e.touches[0].clientX;
                    touchStartY = e.touches[0].clientY;
                }
            }, { passive: true });

            content.addEventListener('touchend', function(e) {
                if (e.changedTouches && e.changedTouches.length === 1) {
                    const touchEndX = e.changedTouches[0].clientX;
                    const touchEndY = e.changedTouches[0].clientY;
                    const diffX = touchEndX - touchStartX;
                    const diffY = touchEndY - touchStartY;
                    // Horizontal swipe threshold: 40px and more horizontal than vertical
                    if (Math.abs(diffX) > 40 && Math.abs(diffX) > Math.abs(diffY)) {
                        if (diffX < 0) {
                            nextPhoto(e);
                        } else {
                            prevPhoto(e);
                        }
                    }
                }
            }, { passive: true });
        }
        if (image) {
            image.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    });

    // Keyboard navigation strictly active only when lightbox is open
    document.addEventListener('keydown', function(e) {
        const lb = document.getElementById('photoLightbox');
        if (!lb || lb.classList.contains('hidden')) return;

        if (e.key === 'Escape') {
            e.preventDefault();
            closeLightbox(e);
        } else if (e.key === 'ArrowRight') {
            e.preventDefault();
            nextPhoto(e);
        } else if (e.key === 'ArrowLeft') {
            e.preventDefault();
            prevPhoto(e);
        }
    });
</script>

@endsection
