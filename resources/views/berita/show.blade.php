@extends('layouts.app')

@section('title', $news->title . ' - Organisasi NU Wangon')

@section('content')

<style>
    .article-body {
        overflow-wrap: break-word;
        word-break: break-word;
    }
    .article-body blockquote {
        border-left: 4px solid #142E25;
        background-color: #ECE8DD;
        padding: 0.75rem 1.25rem;
        border-radius: 0 1rem 1rem 0;
        margin-top: 1.25rem;
        margin-bottom: 1.25rem;
        font-style: italic;
        color: #111A16;
    }

    /* Related Content Grid (<360px Ultra-Compact Fallback) */
    @media (max-width: 360px) {
        .related-content-grid {
            grid-template-columns: 1fr !important;
        }
        .related-content-grid > article {
            grid-column: 1 / -1 !important;
        }
    }
</style>

<div class="bg-brand-cream text-brand-darktext font-sans min-h-screen py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto space-y-10">

        <!-- ================= BREADCRUMB & BACK ================= -->
        <nav class="flex items-center justify-between gap-4 text-xs font-bold text-brand-darktext/70">
            <div class="flex items-center gap-2 overflow-hidden">
                <a href="{{ route('home') }}" class="hover:text-brand-matcha transition-colors text-decoration-none">Beranda</a>
                <span>/</span>
                <a href="{{ route('berita.index') }}" class="hover:text-brand-matcha transition-colors text-decoration-none">Berita</a>
                <span>/</span>
                <span class="text-brand-deep font-black truncate max-w-xs sm:max-w-md">{{ $news->title }}</span>
            </div>

            <a href="{{ route('berita.index') }}" 
               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-white border-2 border-brand-forest hover:bg-brand-sand text-brand-forest text-xs font-black uppercase tracking-wider transition-all btn-solid-press shadow-solid-sm text-decoration-none shrink-0">
                <i class="bi bi-arrow-left"></i>
                <span class="hidden sm:inline">Semua Berita</span>
            </a>
        </nav>

        <!-- ================= TOP HEADER (CENTERED) ================= -->
        <header class="text-center max-w-3xl mx-auto space-y-4">
            <div>
                <span class="inline-block bg-brand-lightmatcha text-brand-forest text-xs font-black uppercase tracking-wider px-4 py-1.5 rounded-full border-2 border-brand-forest shadow-solid-sm">
                    {{ \App\Support\Organisasi::badgeLabel($news->kategori) }}
                </span>
            </div>

            <h1 data-text-animate="blurInUp" class="text-2xl sm:text-4xl lg:text-5xl font-black text-brand-deep tracking-tight leading-[1.15]">
                {{ $news->title }}
            </h1>

            <div class="text-xs sm:text-sm font-bold text-brand-darktext/70 flex items-center justify-center gap-3 pt-1">
                <span class="inline-flex items-center gap-1.5">
                    <i class="bi bi-calendar3 text-brand-forest"></i>
                    {{ $news->published_at?->translatedFormat('d F Y') ?? $news->created_at->translatedFormat('d F Y') }}
                </span>
                <span>•</span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-brand-forest"></span>
                    <span>Redaksi MWC NU Wangon</span>
                </span>
            </div>

            @if($news->excerpt ?? false)
                <div class="bg-brand-sand border-2 border-brand-forest rounded-2xl p-4 sm:p-5 shadow-solid-sm text-sm sm:text-base font-bold text-brand-deep italic max-w-2xl mx-auto leading-relaxed mt-4">
                    &ldquo;{{ $news->excerpt }}&rdquo;
                </div>
            @endif
        </header>

        <!-- ================= HERO IMAGE WITH CAPTION ================= -->
        @if ($news->image)
            <figure class="space-y-0 overflow-hidden rounded-2xl sm:rounded-3xl border-2 border-brand-forest shadow-solid-md bg-brand-sand" data-parallax="card-large">
                <div class="relative group overflow-hidden bg-brand-sand">
                    <img src="{{ asset('storage/' . $news->image) }}" 
                         class="w-full h-auto max-h-[520px] object-cover block" 
                         alt="{{ $news->title }}">
                </div>
                <figcaption class="bg-brand-forest text-brand-cream text-xs font-bold py-3 px-4 sm:px-5 flex flex-wrap justify-between items-center gap-2 border-t-2 border-brand-forest">
                    <span class="truncate">Dokumentasi: {{ $news->title }}</span>
                    <span class="text-brand-lightmatcha text-[11px] font-mono shrink-0">Foto: Dok. Media MWC NU Wangon</span>
                </figcaption>
            </figure>
        @endif

        <!-- ================= MAIN CONTENT GRID ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
            
            <!-- LEFT: ARTICLE CONTENT -->
            <main class="lg:col-span-8 space-y-6">
                <div class="bg-white rounded-2xl sm:rounded-3xl border-2 border-brand-forest p-4 sm:p-10 shadow-solid-sm leading-relaxed text-brand-darktext/90 font-medium text-sm sm:text-base">
                    <div class="article-body space-y-5">
                        {!! nl2br(e($news->content)) !!}
                    </div>
                </div>
            </main>

            <!-- RIGHT: SIDEBAR WIDGETS -->
            <aside class="lg:col-span-4 space-y-6">
                
                <!-- WIDGET 1: INFORMASI WARTA -->
                <div class="bg-white border-2 border-brand-forest rounded-3xl p-6 space-y-4 shadow-solid-sm">
                    <h4 class="text-xs font-black uppercase tracking-wider text-brand-deep border-b-2 border-brand-sand pb-3">
                        Informasi Warta
                    </h4>

                    <div class="space-y-4 text-xs font-medium">
                        <div>
                            <span class="text-brand-darktext/60 block text-[10px] uppercase font-bold tracking-wider">Penerbit &amp; Redaksi</span>
                            <span class="font-black text-brand-deep text-sm flex items-center gap-2 mt-0.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-brand-forest"></span> Organisasi NU Wangon
                            </span>
                        </div>

                        <div>
                            <span class="text-brand-darktext/60 block text-[10px] uppercase font-bold tracking-wider">Tanggal Publikasi</span>
                            <span class="font-black text-brand-deep text-sm mt-0.5 block">
                                {{ $news->published_at?->translatedFormat('d F Y') ?? $news->created_at->translatedFormat('d F Y') }}
                            </span>
                        </div>

                        <div>
                            <span class="text-brand-darktext/60 block text-[10px] uppercase font-bold tracking-wider">Kategori Arsip</span>
                            <span class="font-black text-brand-deep text-sm mt-0.5 block">
                                {{ \App\Support\Organisasi::badgeLabel($news->kategori) }}
                            </span>
                        </div>

                        <div>
                            <span class="text-brand-darktext/60 block text-[10px] uppercase font-bold tracking-wider">Estimasi Baca</span>
                            <span class="font-black text-brand-deep text-sm mt-0.5 block">
                                {{ ceil(str_word_count(strip_tags($news->content)) / 200) }} Menit Membaca
                            </span>
                        </div>
                    </div>
                </div>

                <!-- WIDGET 2: BAGIKAN BERITA -->
                <div class="bg-white border-2 border-brand-forest rounded-3xl p-6 space-y-4 shadow-solid-sm">
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-wider text-brand-deep">
                            Bagikan Berita
                        </h4>
                        <p class="text-xs text-brand-darktext/70 mt-1 mb-0 font-medium">
                            Syiarkan warta dan hikmah ini ke kerabat dan jaringan Nahdliyin.
                        </p>
                    </div>

                    <div class="space-y-2.5 pt-1">
                        <!-- WhatsApp -->
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($news->title . ' - ' . url()->current()) }}" 
                           target="_blank" rel="noopener noreferrer"
                           class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-2.5 px-4 rounded-xl text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2 transition-all shadow-solid-sm btn-solid-press border-2 border-emerald-900 text-decoration-none">
                            <i class="bi bi-whatsapp text-sm"></i>
                            <span>WhatsApp</span>
                        </a>

                        <!-- Facebook -->
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                           target="_blank" rel="noopener noreferrer"
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 px-4 rounded-xl text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2 transition-all shadow-solid-sm btn-solid-press border-2 border-blue-900 text-decoration-none">
                            <i class="bi bi-facebook text-sm"></i>
                            <span>Facebook</span>
                        </a>

                        <!-- Salin Link -->
                        <button onclick="copyLinkOnly()" id="copyBtn"
                                class="w-full bg-brand-sand hover:bg-[#DDD7C8] border-2 border-brand-forest text-brand-forest py-2.5 px-4 rounded-xl text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2 transition-all shadow-solid-sm btn-solid-press cursor-pointer">
                            <i class="bi bi-link-45deg text-base"></i>
                            <span id="copyBtnText">Salin Tautan</span>
                        </button>
                    </div>
                </div>

                <!-- WIDGET 3: KOMITMEN WARTA DIGITAL -->
                <div class="bg-brand-lightmatcha border-2 border-brand-forest rounded-3xl p-6 text-center space-y-2 shadow-solid-sm">
                    <div class="w-10 h-10 rounded-xl bg-brand-forest text-white border-2 border-brand-deep flex items-center justify-center mx-auto shadow-solid-sm">
                        <i class="bi bi-newspaper text-lg"></i>
                    </div>
                    <h5 class="text-xs font-black uppercase tracking-wider text-brand-deep">Komitmen Warta Digital</h5>
                    <p class="text-xs font-medium text-brand-darktext/80 leading-relaxed mb-0">
                        Seluruh warta dan dokumentasi kegiatan resmi diterbitkan oleh Tim Media &amp; Dakwah Majelis Wakil Cabang Nahdlatul Ulama Wangon untuk kemaslahatan warga.
                    </p>
                </div>

            </aside>

        </div>

        <!-- ================= SECTION BERITA LAINNYA ================= -->
        @if(isset($relatedNews) && $relatedNews->isNotEmpty())
            <div class="pt-8 sm:pt-10 border-t-2 border-brand-sand space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3">
                    <div>
                        <span data-text-animate="blurInUp" class="text-xs font-black uppercase tracking-wider text-brand-matcha">ARSIP &amp; PUBLIKASI</span>
                        <h3 data-text-animate="blurInUp" class="text-2xl sm:text-3xl font-black text-brand-deep tracking-tight mt-0.5">Warta Lainnya</h3>
                        <p class="text-xs sm:text-sm font-medium text-brand-darktext/75 mb-0">Baca berita dan artikel kegiatan terbaru dari Organisasi NU Wangon.</p>
                    </div>
                    <a href="{{ route('berita.index') }}" 
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border-2 border-brand-forest hover:bg-brand-sand text-brand-forest text-xs font-black uppercase tracking-wider transition-all btn-solid-press shadow-solid-sm text-decoration-none self-start sm:self-auto shrink-0">
                        <span>Semua Warta</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-7 sm:gap-x-6 sm:gap-y-8 lg:gap-x-7 lg:gap-y-10 related-content-grid">
                    @foreach($relatedNews->take(4) as $related)
                        @php
                            $i = $loop->index;
                            $relImg = !empty($related->image) 
                                ? asset('storage/' . $related->image) 
                                : asset('images/landing/gallery-' . (($i % 3) + 1) . '.jpg');
                            $relDate = \Carbon\Carbon::parse($related->published_at ?? $related->created_at ?? now());
                            $relExcerpt = strip_tags($related->content ?? $related->excerpt ?? '');
                            // Responsive mobile: Item 0 is full width anchor (col-span-2), Items 1 & 2 are half width (col-span-1), Item 3 is full width (col-span-2)
                            $mobileSpan = ($i === 0 || $i === 3) ? 'col-span-2 lg:col-span-1' : 'col-span-1 lg:col-span-1';
                            $isCompactMobile = ($i === 1 || $i === 2);
                        @endphp
                        <article class="{{ $mobileSpan }} bg-white border-2 border-brand-forest rounded-2xl sm:rounded-3xl shadow-solid-md hover:shadow-solid-lg transition-all duration-300 flex flex-col justify-between group overflow-hidden isolate h-auto min-w-0"
                                 data-parallax="card">
                            <div class="flex flex-col w-full">
                                <!-- Large 16:9 Image with Clamped Inner Radius -->
                                <div class="relative w-full aspect-[16/9] overflow-hidden rounded-t-[14px] sm:rounded-t-[22px] bg-brand-sand border-b-2 border-brand-forest shrink-0">
                                    <a href="{{ route('berita.show', $related->slug) }}" class="block w-full h-full relative" aria-label="{{ $related->title }}">
                                        <img src="{{ $relImg }}" 
                                             class="w-full h-full object-cover block rounded-t-[14px] sm:rounded-t-[22px] group-hover:scale-105 transition-transform duration-500" 
                                             alt="{{ $related->title }}"
                                             loading="lazy">
                                    </a>
                                    <div class="absolute top-2.5 left-2.5 sm:top-3.5 sm:left-3.5 z-10 pointer-events-none">
                                        <span class="inline-flex items-center px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full bg-brand-lightmatcha text-brand-forest border border-brand-forest text-[10px] sm:text-xs font-black uppercase tracking-wider shadow-solid-xs">
                                            {{ \App\Support\Organisasi::badgeLabel($related->kategori) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Card Content -->
                                <div class="p-3.5 sm:p-5 lg:p-6 flex flex-col flex-1 text-left w-full">
                                    <div class="flex items-center justify-between gap-2 text-[10px] sm:text-xs font-bold text-brand-darktext/70">
                                        <span class="inline-flex items-center gap-1.5">
                                            <i class="bi bi-calendar3 text-brand-forest text-[10px] sm:text-xs"></i>
                                            {{ $relDate->translatedFormat('d M Y') }}
                                        </span>
                                        <span class="text-[10px] font-black uppercase tracking-wider text-brand-forest/70">
                                            Warta NU
                                        </span>
                                    </div>

                                    <h4 class="font-black text-sm sm:text-base lg:text-lg text-brand-deep leading-snug group-hover:text-brand-matcha transition-colors line-clamp-3 mt-2 sm:mt-2.5 mb-0">
                                        <a href="{{ route('berita.show', $related->slug) }}" class="text-inherit text-decoration-none block">
                                            {{ $related->title }}
                                        </a>
                                    </h4>

                                    @if($relExcerpt)
                                        <p class="{{ $isCompactMobile ? 'hidden sm:block' : 'block' }} text-xs sm:text-sm font-medium text-brand-darktext/75 leading-relaxed line-clamp-2 mt-2 mb-0">
                                            {{ Str::limit($relExcerpt, 110) }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Footer Action -->
                            <div class="px-3.5 py-2.5 sm:px-5 sm:py-3.5 lg:px-6 border-t-2 border-brand-sand bg-brand-cream/50 flex items-center justify-between w-full mt-auto">
                                <span class="text-[10px] sm:text-xs font-black uppercase tracking-wider text-brand-forest/80">
                                    Wangon
                                </span>
                                <a href="{{ route('berita.show', $related->slug) }}" 
                                   class="inline-flex items-center gap-1 sm:gap-1.5 h-8 sm:h-9 px-3 sm:px-4 rounded-xl bg-brand-forest hover:bg-brand-deep text-white font-black text-[11px] sm:text-xs uppercase tracking-wider border border-brand-deep shadow-solid-xs transition-all btn-solid-press text-decoration-none">
                                    <span>BACA</span>
                                    <span class="text-sm">&rarr;</span>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- ================= SECTION GALERI LAINNYA ================= -->
        @php
            $relatedGalleries = \App\Models\Kegiatan::selesai()
                ->where(function ($q) {
                    $q->has('fotos')->orWhereNotNull('image');
                })
                ->with(['fotos'])
                ->orderBy('event_date', 'desc')
                ->take(3)
                ->get();
        @endphp

        @if($relatedGalleries->isNotEmpty())
            <div class="pt-8 sm:pt-10 border-t-2 border-brand-sand space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3">
                    <div>
                        <span data-text-animate="blurInUp" class="text-xs font-black uppercase tracking-wider text-brand-matcha">DOKUMENTASI &amp; FOTO KEGIATAN</span>
                        <h3 data-text-animate="blurInUp" class="text-2xl sm:text-3xl font-black text-brand-deep tracking-tight mt-0.5">Galeri Lainnya</h3>
                        <p class="text-xs sm:text-sm font-medium text-brand-darktext/75 mb-0">Dokumentasi momen kegiatan dan syiar Nahdlatul Ulama Wangon.</p>
                    </div>
                    <a href="{{ route('galeri.index') }}" 
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border-2 border-brand-forest hover:bg-brand-sand text-brand-forest text-xs font-black uppercase tracking-wider transition-all btn-solid-press shadow-solid-sm text-decoration-none self-start sm:self-auto shrink-0">
                        <span>Semua Galeri</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-6 lg:gap-7 related-content-grid">
                    @foreach($relatedGalleries as $relGaleri)
                        @php
                            $i = $loop->index;
                            $galeriCover = $relGaleri->cover_photo;
                            $galeriImg = $galeriCover 
                                ? asset('storage/' . $galeriCover)
                                : asset('images/landing/gallery-' . (($i % 3) + 1) . '.jpg');
                            $fotoCount = $relGaleri->fotos->count() + ($relGaleri->image ? 1 : 0);
                            $mobileGaleriSpan = ($i === 0) ? 'col-span-2 lg:col-span-1' : 'col-span-1 lg:col-span-1';
                        @endphp
                        <article class="{{ $mobileGaleriSpan }} bg-white border-2 border-brand-forest rounded-2xl sm:rounded-3xl shadow-solid-md hover:shadow-solid-lg transition-all duration-300 flex flex-col justify-between group overflow-hidden isolate h-auto min-w-0"
                                 data-parallax="card">
                            <div class="flex flex-col w-full">
                                <!-- Large Photo Focal Point (16:9 Widescreen) -->
                                <div class="relative w-full aspect-[16/9] overflow-hidden rounded-t-[14px] sm:rounded-t-[22px] bg-brand-sand border-b-2 border-brand-forest shrink-0">
                                    <a href="{{ route('galeri.show', $relGaleri->id) }}" class="block w-full h-full relative" aria-label="{{ $relGaleri->title }}">
                                        <img src="{{ $galeriImg }}" 
                                             class="w-full h-full object-cover block rounded-t-[14px] sm:rounded-t-[22px] group-hover:scale-105 transition-transform duration-700 ease-out" 
                                             alt="{{ $relGaleri->title }}"
                                             loading="lazy">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent opacity-60 group-hover:opacity-30 transition-opacity pointer-events-none"></div>
                                    </a>
                                    <!-- Category Badge Top Left -->
                                    <div class="absolute top-2.5 left-2.5 sm:top-3.5 sm:left-3.5 z-10 pointer-events-none">
                                        <span class="inline-flex items-center px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full bg-white text-brand-forest border border-brand-forest text-[10px] sm:text-xs font-black uppercase tracking-wider shadow-solid-xs">
                                            {{ \App\Support\Organisasi::badgeLabel($relGaleri->kategori) }}
                                        </span>
                                    </div>
                                    <!-- Photo Count Badge Bottom Right -->
                                    <div class="absolute bottom-2.5 right-2.5 sm:bottom-3.5 sm:right-3.5 z-10 pointer-events-none">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-full bg-brand-deep/85 text-white border border-brand-forest text-[10px] sm:text-xs font-black shadow-solid-xs">
                                            <i class="bi bi-camera-fill text-brand-lightmatcha text-[10px]"></i>
                                            <span>{{ $fotoCount }} Foto</span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Content Details -->
                                <div class="p-3.5 sm:p-5 lg:p-6 flex flex-col flex-1 text-left w-full">
                                    <div class="flex items-center justify-between gap-2 text-[10px] sm:text-xs font-bold text-brand-darktext/70">
                                        <span class="inline-flex items-center gap-1.5">
                                            <i class="bi bi-calendar3 text-brand-forest text-[10px] sm:text-xs"></i>
                                            {{ $relGaleri->event_date ? \Carbon\Carbon::parse($relGaleri->event_date)->translatedFormat('d M Y') : '-' }}
                                        </span>
                                        @if($relGaleri->location)
                                            <span class="inline-flex items-center gap-1 text-brand-forest/75 truncate max-w-[120px] sm:max-w-[150px]">
                                                <i class="bi bi-geo-alt text-[10px]"></i>
                                                <span class="truncate">{{ $relGaleri->location }}</span>
                                            </span>
                                        @endif
                                    </div>

                                    <h4 class="font-black text-sm sm:text-base lg:text-lg text-brand-deep leading-snug group-hover:text-brand-matcha transition-colors line-clamp-2 mt-2 sm:mt-2.5 mb-0">
                                        <a href="{{ route('galeri.show', $relGaleri->id) }}" class="text-inherit text-decoration-none block">
                                            {{ $relGaleri->title }}
                                        </a>
                                    </h4>
                                </div>
                            </div>

                            <!-- Footer Action -->
                            <div class="px-3.5 py-2.5 sm:px-5 sm:py-3.5 lg:px-6 border-t-2 border-brand-sand bg-brand-cream/50 flex items-center justify-between w-full mt-auto">
                                <span class="text-[10px] sm:text-xs font-black uppercase tracking-wider text-brand-forest/80">
                                    Dokumentasi
                                </span>
                                <a href="{{ route('galeri.show', $relGaleri->id) }}" 
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

<script>
    function copyLinkOnly() {
        navigator.clipboard.writeText('{{ url()->current() }}').then(() => {
            const btnText = document.getElementById('copyBtnText');
            if (btnText) {
                btnText.textContent = 'Tautan Disalin!';
                setTimeout(() => {
                    btnText.textContent = 'Salin Tautan';
                }, 2000);
            }
        });
    }
</script>

@endsection