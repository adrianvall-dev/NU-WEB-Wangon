@extends('layouts.app')

@section('title', 'Warta & Berita - Organisasi NU Wangon')

@section('content')

<div class="berita-page-wrapper min-h-screen bg-brand-cream text-brand-darktext font-sans antialiased pb-24 pt-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- ================= 1. HEADER & SEARCH BAR ================= -->
        <div class="bg-white rounded-2xl sm:rounded-3xl border-2 border-brand-forest p-4 sm:p-10 shadow-solid-md flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
            <!-- Abstract Geometric Background (PixelOS Inspiration - Large Header Geometry with 5px Stroke & Translucent Fill) -->
            <div class="absolute inset-0 pixel-grid-blueprint opacity-60 pointer-events-none -z-0"></div>
            <div class="pointer-events-none absolute -bottom-20 -right-20 w-72 h-72 sm:w-80 sm:h-80 rounded-full border-[5px] border-brand-forest/[0.24] flex items-center justify-center p-12 hidden sm:flex -z-0" data-parallax="bg-slow" data-parallax-rotate="3">
                <div class="w-full h-full rounded-full border-[3px] border-dashed border-brand-matcha/[0.28] bg-brand-lightmatcha/[0.15]"></div>
            </div>
            <div class="pointer-events-none absolute top-4 left-6 text-brand-forest/[0.35] text-sm font-mono font-bold select-none hidden sm:block -z-0">+</div>

            <div class="space-y-3 max-w-xl relative z-10">
                <div data-text-animate="blurInUp" class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-brand-lightmatcha text-brand-forest border-2 border-brand-forest rounded-full text-xs font-black uppercase tracking-wider shadow-solid-sm">
                    <span class="w-2 h-2 rounded-full bg-brand-matcha animate-pulse"></span>
                    <span>WARTA &amp; KABAR RESMI</span>
                </div>
                <h1 data-text-animate="blurInUp" class="text-3xl sm:text-4xl lg:text-5xl font-black text-brand-deep tracking-tight leading-tight">
                    Warta &amp; Informasi
                </h1>
                <p class="text-sm sm:text-base font-medium text-brand-darktext/80 leading-relaxed">
                    Kabar kegiatan dakwah, khazanah keislaman, program sosial kemasyarakatan, dan pengumuman resmi Majelis Wakil Cabang NU Wangon.
                </p>
            </div>

            <!-- Search Form -->
            <form action="{{ route('berita.index') }}" method="GET" class="w-full md:w-auto m-0 relative z-10">
                @if(request('kategori'))
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                @endif
                <div class="flex items-center bg-brand-cream rounded-2xl p-1.5 border-2 border-brand-forest shadow-solid-sm focus-within:shadow-solid-md w-full min-w-0 sm:min-w-[360px] transition-all">
                    <span class="pl-3.5 pr-2 text-brand-forest text-base">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           class="w-full bg-transparent border-0 py-2 px-1 text-xs sm:text-sm font-bold text-brand-darktext focus:outline-none placeholder:text-brand-darktext/50" 
                           placeholder="Cari berita atau artikel...">
                    
                    @if(request('search'))
                        <a href="{{ route('berita.index', array_filter(['kategori' => request('kategori')])) }}" 
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
            <a href="{{ route('berita.index', array_filter(['search' => request('search')])) }}"
               class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all whitespace-nowrap border-2 text-decoration-none btn-solid-press {{ !request('kategori') ? 'bg-brand-forest text-white border-brand-deep shadow-solid-sm' : 'bg-white text-brand-forest border-brand-forest hover:bg-brand-sand' }}">
                Semua Kategori
            </a>
            @foreach(\App\Support\Organisasi::badgeLabelsWithUmum() as $key => $label)
                <a href="{{ route('berita.index', array_filter(['search' => request('search'), 'kategori' => $key])) }}"
                   class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all whitespace-nowrap border-2 text-decoration-none btn-solid-press {{ request('kategori') === $key ? 'bg-brand-forest text-white border-brand-deep shadow-solid-sm' : 'bg-white text-brand-forest border-brand-forest hover:bg-brand-sand' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- ================= 3. CONTENT AREA ================= -->
        @if ($newsList->isEmpty())
            <!-- EMPTY STATE -->
            <div class="bg-white border-2 border-dashed border-brand-forest rounded-3xl p-10 sm:p-14 text-center flex flex-col items-center justify-center space-y-4 my-8 shadow-solid-sm">
                <div class="w-16 h-16 rounded-2xl bg-brand-lightmatcha text-brand-forest border-2 border-brand-forest flex items-center justify-center text-3xl shadow-solid-sm">
                    <i class="bi bi-journal-x"></i>
                </div>
                <div class="space-y-1.5 max-w-md">
                    <h3 class="font-black text-2xl text-brand-deep tracking-tight">Belum Ada Warta Berita</h3>
                    <p class="text-xs sm:text-sm text-brand-darktext/75 leading-relaxed">
                        @if(request('search') || request('kategori'))
                            Tidak ada warta berita yang cocok dengan kriteria pencarian atau kategori yang Anda pilih.
                        @else
                            Saat ini belum ada warta berita yang dipublikasikan. Silakan kunjungi kembali nanti.
                        @endif
                    </p>
                </div>
                @if(request('search') || request('kategori'))
                    <a href="{{ route('berita.index') }}" 
                       class="inline-flex items-center gap-2 bg-brand-forest hover:bg-brand-deep text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-solid-sm btn-solid-press text-decoration-none mt-2">
                        <i class="bi bi-arrow-left"></i>
                        <span>Kembali ke Semua Berita</span>
                    </a>
                @endif
            </div>
        @else

            <!-- ASYMMETRIC EDITORIAL BENTO NEWS GRID -->
            @php $totalNews = $newsList->count(); @endphp
            <div class="grid grid-cols-2 lg:grid-cols-12 gap-x-4 gap-y-7 sm:gap-x-6 sm:gap-y-8 lg:gap-x-7 lg:gap-y-10 responsive-news-grid">
                @foreach ($newsList as $item)
                    @include('berita.partials.bento-card', [
                        'newsItem' => $item, 
                        'index' => $loop->index, 
                        'totalNews' => $totalNews, 
                        'isFallback' => false
                    ])
                @endforeach
            </div>

            <!-- PAGINATION -->
            <div class="mt-10 flex justify-center pt-4">
                {{ $newsList->links() }}
            </div>

        @endif

    </div>
</div>

@endsection