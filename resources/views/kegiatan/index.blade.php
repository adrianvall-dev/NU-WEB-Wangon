@extends('layouts.app')

@section('title', 'Agenda & Kegiatan - Organisasi NU Wangon')

@section('content')

<div class="min-h-screen bg-brand-cream text-brand-darktext font-sans antialiased pb-24 pt-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- ================= 1. HEADER & SEARCH BAR ================= -->
        <div class="bg-white rounded-2xl sm:rounded-3xl border-2 border-brand-forest p-4 sm:p-10 shadow-solid-md flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative overflow-hidden">
            <!-- Abstract Geometric Background (PixelOS Inspiration - Large Header Geometry with 5px Stroke & Translucent Fill) -->
            <div class="absolute inset-0 pixel-grid-blueprint opacity-60 pointer-events-none -z-0"></div>
            <div class="pointer-events-none absolute -bottom-20 -right-20 w-72 h-72 sm:w-80 sm:h-80 rounded-full border-[5px] border-brand-forest/[0.24] flex items-center justify-center p-12 hidden sm:flex -z-0" data-parallax="bg-slow" data-parallax-rotate="3">
                <div class="w-full h-full rounded-full border-[3px] border-dashed border-brand-matcha/[0.28] bg-brand-lightmatcha/[0.15]"></div>
            </div>
            <div class="pointer-events-none absolute top-4 left-6 text-brand-forest/[0.35] text-sm font-mono font-bold select-none hidden sm:block -z-0">+</div>

            <div class="space-y-3 max-w-xl relative z-10">
                <div data-text-animate="blurInUp" class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-brand-lightmatcha text-brand-forest border-2 border-brand-forest rounded-full text-xs font-black uppercase tracking-wider shadow-solid-sm">
                    <span class="w-2 h-2 rounded-full bg-brand-matcha animate-pulse"></span>
                    <span>JADWAL &amp; AGENDA RESMI</span>
                </div>
                <h1 data-text-animate="blurInUp" class="text-3xl sm:text-4xl lg:text-5xl font-black text-brand-deep tracking-tight leading-tight">
                    Agenda &amp; Kegiatan
                </h1>
                <p class="text-sm sm:text-base font-medium text-brand-darktext/80 leading-relaxed">
                    Ikuti jadwal pengajian, rapat kerja, peringatan hari besar Islam, dan agenda sosial keagamaan Nahdlatul Ulama di wilayah Kecamatan Wangon.
                </p>
            </div>

            <!-- Search Form -->
            <div class="w-full lg:w-auto shrink-0 relative z-10">
                <form action="{{ route('kegiatan.index') }}" method="GET" class="m-0">
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
                               placeholder="Cari kegiatan atau lokasi...">
                        
                        @if(request('search'))
                            <a href="{{ route('kegiatan.index', array_filter(['kategori' => request('kategori')])) }}" 
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
        </div>

        <!-- ================= 2. CATEGORY FILTER TABS ================= -->
        <div class="flex items-center gap-2.5 overflow-x-auto pb-2 scrollbar-none">
            <a href="{{ route('kegiatan.index', array_filter(['search' => request('search')])) }}"
               class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all whitespace-nowrap border-2 text-decoration-none btn-solid-press {{ !request('kategori') ? 'bg-brand-forest text-white border-brand-deep shadow-solid-sm' : 'bg-white text-brand-forest border-brand-forest hover:bg-brand-sand' }}">
                Semua Agenda
            </a>
            @foreach(\App\Support\Organisasi::badgeLabelsWithUmum() as $key => $label)
                <a href="{{ route('kegiatan.index', array_filter(['search' => request('search'), 'kategori' => $key])) }}"
                   class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all whitespace-nowrap border-2 text-decoration-none btn-solid-press {{ request('kategori') === $key ? 'bg-brand-forest text-white border-brand-deep shadow-solid-sm' : 'bg-white text-brand-forest border-brand-forest hover:bg-brand-sand' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- ================= 3. CONTENT AREA ================= -->
        @if ($kegiatanList->isEmpty())
            <!-- EMPTY STATE -->
            <div class="bg-white border-2 border-dashed border-brand-forest rounded-3xl p-10 sm:p-14 text-center flex flex-col items-center justify-center space-y-4 my-8 shadow-solid-sm">
                <div class="w-16 h-16 rounded-2xl bg-brand-lightmatcha text-brand-forest border-2 border-brand-forest flex items-center justify-center text-3xl shadow-solid-sm">
                    <i class="bi bi-calendar-x"></i>
                </div>
                <div class="space-y-1.5 max-w-md">
                    <h3 class="font-black text-2xl text-brand-deep tracking-tight">Belum Ada Agenda Kegiatan</h3>
                    <p class="text-xs sm:text-sm text-brand-darktext/75 leading-relaxed">
                        @if(request('search') || request('kategori'))
                            Tidak ada agenda kegiatan yang cocok dengan filter atau kata kunci pencarian Anda saat ini.
                        @else
                            Saat ini belum ada agenda kegiatan yang dijadwalkan oleh pengurus. Silakan cek kembali secara berkala.
                        @endif
                    </p>
                </div>
                @if(request('search') || request('kategori'))
                    <a href="{{ route('kegiatan.index') }}" 
                       class="inline-flex items-center gap-2 bg-brand-forest hover:bg-brand-deep text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-solid-sm btn-solid-press text-decoration-none mt-2">
                        <i class="bi bi-arrow-left"></i>
                        <span>Tampilkan Semua Agenda</span>
                    </a>
                @endif
            </div>
        @else

            <!-- GRID CARDS KEGIATAN -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                @foreach ($kegiatanList as $item)
                    @php
                        $isPast = \Carbon\Carbon::parse($item->event_date)->isPast();
                    @endphp

                    <article class="bg-white rounded-3xl p-3.5 sm:p-4.5 lg:p-5 border-2 border-brand-forest shadow-solid-md flex flex-col justify-between hover:-translate-y-1 hover:shadow-solid-lg transition-all group overflow-hidden relative"
                             data-parallax="card">
                        <div class="space-y-3.5 sm:space-y-4 min-w-0">
                            
                            <!-- Thumbnail Image Container (Optimized Proportions & Breathing Room) -->
                            <div class="relative h-52 sm:h-56 lg:h-48 xl:h-52 aspect-[16/10] sm:aspect-auto rounded-2xl overflow-hidden bg-brand-sand border-2 border-brand-forest">
                                @if ($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" 
                                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                                         alt="{{ $item->title }}">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-brand-forest/60">
                                        <i class="bi bi-calendar-event text-4xl mb-1"></i>
                                        <span class="text-[11px] font-black uppercase tracking-wider">Dokumentasi Agenda</span>
                                    </div>
                                @endif

                                <!-- Status Badge (Mendatang / Selesai) -->
                                <div class="absolute top-2.5 left-2.5 sm:top-3 sm:left-3">
                                    @if($isPast)
                                        <span class="bg-brand-sand text-brand-forest border-2 border-brand-forest text-[10px] font-black uppercase tracking-wider px-2.5 sm:px-3 py-1 rounded-full shadow-solid-sm flex items-center gap-1.5">
                                            <i class="bi bi-check-circle-fill text-brand-forest"></i> Selesai
                                        </span>
                                    @else
                                        <span class="bg-brand-forest text-white border-2 border-brand-deep text-[10px] font-black uppercase tracking-wider px-2.5 sm:px-3 py-1 rounded-full shadow-solid-sm flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span> Mendatang
                                        </span>
                                    @endif
                                </div>

                                <!-- Category Badge -->
                                <div class="absolute top-2.5 right-2.5 sm:top-3 sm:right-3">
                                    <span class="bg-white text-brand-forest border-2 border-brand-forest text-[10px] font-black uppercase tracking-wider px-2.5 sm:px-3 py-1 rounded-full shadow-solid-sm">
                                        {{ \App\Support\Organisasi::badgeLabel($item->kategori) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Content Details (Hierarchy & Spacious Metadata) -->
                            <div class="min-w-0 space-y-2.5 sm:space-y-3 pt-1">
                                <!-- Date & Time Row -->
                                <div class="flex flex-wrap items-center gap-2 text-xs font-bold text-brand-darktext/75">
                                    <span class="inline-flex items-center gap-1.5 bg-brand-cream px-2.5 sm:px-3 py-1.5 rounded-xl border border-brand-forest/25 text-xs font-bold">
                                        <i class="bi bi-calendar3 text-brand-forest"></i>
                                        <span>{{ \Carbon\Carbon::parse($item->event_date)->translatedFormat('d M Y') }}</span>
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 bg-brand-cream px-2.5 sm:px-3 py-1.5 rounded-xl border border-brand-forest/25 text-xs font-bold">
                                        <i class="bi bi-clock text-brand-forest"></i>
                                        <span>{{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }} WIB</span>
                                    </span>
                                </div>

                                <!-- Title with Stronger Visual Hierarchy -->
                                <h3 class="text-lg sm:text-xl font-black text-brand-deep leading-snug line-clamp-2 break-words group-hover:text-brand-matcha transition-colors">
                                    <a href="{{ route('kegiatan.show', $item->id) }}" class="text-inherit text-decoration-none">
                                        {{ $item->title }}
                                    </a>
                                </h3>

                                <!-- Location -->
                                <p class="text-xs sm:text-[13px] font-bold text-brand-darktext/80 flex items-center gap-1.5 line-clamp-1 m-0 pt-0.5">
                                    <i class="bi bi-geo-alt-fill text-brand-accent text-sm shrink-0"></i>
                                    <span class="truncate">{{ $item->location }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- Card Action Footer with Accessible Touch Target -->
                        <div class="pt-3.5 sm:pt-4 mt-4 sm:mt-5 border-t-2 border-brand-sand flex items-center justify-between">
                            <span class="text-[11px] font-black uppercase tracking-wider text-brand-forest/70">
                                Organisasi NU Wangon
                            </span>
                            
                            <a href="{{ route('kegiatan.show', $item->id) }}" 
                               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 lg:py-2 min-h-[44px] lg:min-h-0 rounded-xl bg-brand-sand hover:bg-brand-forest hover:text-white text-brand-forest font-black text-xs uppercase tracking-wider border-2 border-brand-forest shadow-solid-sm transition-all btn-solid-press text-decoration-none">
                                <span>Rincian</span>
                                <span class="text-sm leading-none">&rarr;</span>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- PAGINATION -->
            <div class="mt-10 flex justify-center pt-4">
                {{ $kegiatanList->links() }}
            </div>

        @endif
    </div>
</div>

@endsection