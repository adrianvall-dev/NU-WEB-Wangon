@extends('layouts.app')

@section('title', $kegiatan->title . ' - Organisasi NU Wangon')

@section('content')

<!-- Leaflet CSS for Interactive Public Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .leaflet-container {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif !important;
    }
    #publicKegiatanMap {
        z-index: 1;
    }
    .leaflet-popup-content-wrapper {
        border-radius: 16px;
        box-shadow: 4px 4px 0px #142E25;
        border: 2px solid #142E25;
    }
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
</style>

<div class="bg-brand-cream text-brand-darktext font-sans min-h-screen py-6 sm:py-10 px-3.5 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto space-y-6 sm:space-y-10">

        <!-- ================= BREADCRUMB & BACK ================= -->
        <nav class="flex items-center justify-between gap-4 text-xs font-bold text-brand-darktext/70">
            <div class="flex items-center gap-2 overflow-hidden">
                <a href="{{ route('home') }}" class="hover:text-brand-matcha transition-colors text-decoration-none">Beranda</a>
                <span>/</span>
                <a href="{{ route('kegiatan.index') }}" class="hover:text-brand-matcha transition-colors text-decoration-none">Agenda</a>
                <span>/</span>
                <span class="text-brand-deep font-black truncate max-w-xs sm:max-w-md">{{ $kegiatan->title }}</span>
            </div>

            <a href="{{ route('kegiatan.index') }}" 
               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-white border-2 border-brand-forest hover:bg-brand-sand text-brand-forest text-xs font-black uppercase tracking-wider transition-all btn-solid-press shadow-solid-sm text-decoration-none shrink-0">
                <i class="bi bi-arrow-left"></i>
                <span class="hidden sm:inline">Daftar Agenda</span>
            </a>
        </nav>

        <!-- ================= TOP HEADER (CENTERED) ================= -->
        <header class="text-center max-w-3xl mx-auto space-y-3.5 sm:space-y-4">
            <div class="flex items-center justify-center gap-2 sm:gap-2.5 flex-wrap">
                <!-- CATEGORY BADGE -->
                <span class="inline-block bg-brand-lightmatcha text-brand-forest text-xs font-black uppercase tracking-wider px-3.5 sm:px-4 py-1.5 rounded-full border-2 border-brand-forest shadow-solid-sm">
                    {{ \App\Support\Organisasi::badgeLabel($kegiatan->kategori) }}
                </span>

                <!-- EVENT STATUS BADGE -->
                @php
                    $tz = config('app.timezone', 'Asia/Jakarta');
                    $eventDateStr = $kegiatan->event_date instanceof \Carbon\Carbon 
                        ? $kegiatan->event_date->format('Y-m-d') 
                        : (string) $kegiatan->event_date;
                    $startTimeStr = $kegiatan->start_time ?: '00:00:00';
                    $endTimeStr = $kegiatan->end_time ?: '23:59:59';

                    $startCarbon = \Carbon\Carbon::parse($eventDateStr . ' ' . $startTimeStr, $tz);
                    $endCarbon = $kegiatan->end_time
                        ? \Carbon\Carbon::parse($eventDateStr . ' ' . $endTimeStr, $tz)
                        : $startCarbon->copy()->addHours(2);

                    $startTimestampMs = $startCarbon->getTimestamp() * 1000;
                    $endTimestampMs = $endCarbon->getTimestamp() * 1000;
                    $nowTimestampMs = now($tz)->getTimestamp() * 1000;

                    if ($nowTimestampMs < $startTimestampMs) {
                        $eventState = 'upcoming';
                    } elseif ($nowTimestampMs < $endTimestampMs) {
                        $eventState = 'ongoing';
                    } else {
                        $eventState = 'finished';
                    }
                @endphp

                <span id="status-badge">
                    @if ($eventState === 'finished')
                        <span class="inline-flex items-center gap-1.5 bg-brand-sand text-brand-forest text-xs font-black uppercase tracking-wider px-3.5 sm:px-4 py-1.5 rounded-full border-2 border-brand-forest shadow-solid-sm">
                            <i class="bi bi-check-circle-fill"></i> Selesai
                        </span>
                    @elseif ($eventState === 'ongoing')
                        <span class="inline-flex items-center gap-1.5 bg-brand-accent text-white text-xs font-black uppercase tracking-wider px-3.5 sm:px-4 py-1.5 rounded-full border-2 border-brand-deep shadow-solid-sm animate-pulse">
                            <span class="w-2 h-2 rounded-full bg-white"></span> Sedang Berlangsung
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-brand-forest text-white text-xs font-black uppercase tracking-wider px-3.5 sm:px-4 py-1.5 rounded-full border-2 border-brand-deep shadow-solid-sm">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span> Mendatang
                        </span>
                    @endif
                </span>
            </div>

            <h1 data-text-animate="blurInUp" class="text-2xl sm:text-4xl lg:text-5xl font-black text-brand-deep tracking-tight leading-[1.18] sm:leading-[1.15]">
                {{ $kegiatan->title }}
            </h1>

            <div class="text-xs sm:text-sm font-bold text-brand-darktext/75 flex flex-wrap items-center justify-center gap-2 sm:gap-3 pt-1">
                <span class="inline-flex items-center gap-1.5">
                    <i class="bi bi-calendar3 text-brand-forest"></i>
                    {{ \Carbon\Carbon::parse($kegiatan->event_date)->translatedFormat('l, d F Y') }}
                </span>
                <span>•</span>
                <span class="inline-flex items-center gap-1.5">
                    <i class="bi bi-geo-alt-fill text-brand-accent"></i>
                    {{ $kegiatan->location }}
                </span>
            </div>
        </header>

        <!-- ================= HERO IMAGE WITH CAPTION ================= -->
        @if ($kegiatan->image)
            <figure class="space-y-0 overflow-hidden rounded-2xl sm:rounded-3xl border-2 border-brand-forest shadow-solid-md bg-brand-sand" data-parallax="card-large">
                <div class="relative group overflow-hidden bg-brand-sand">
                    <img src="{{ asset('storage/' . $kegiatan->image) }}" 
                         class="w-full h-auto min-h-[220px] sm:min-h-[300px] max-h-[520px] object-cover block" 
                         alt="{{ $kegiatan->title }}">
                </div>
                <figcaption class="bg-brand-forest text-brand-cream text-xs font-bold py-2.5 sm:py-3 px-3.5 sm:px-5 flex flex-wrap justify-between items-center gap-2 border-t-2 border-brand-forest">
                    <span class="truncate">Dokumentasi: {{ $kegiatan->title }}</span>
                    <span class="text-brand-lightmatcha text-[11px] font-mono shrink-0">Dok. Resmi MWC NU Wangon</span>
                </figcaption>
            </figure>
        @endif

        <!-- ================= MAIN CONTENT GRID ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 lg:gap-10 items-start">
            
            <!-- LEFT COLUMN: ARTICLE & EVENT DETAILS -->
            <main class="lg:col-span-8 space-y-6 sm:space-y-8 text-brand-darktext leading-relaxed text-sm sm:text-base font-medium">
                
                <!-- COUNTDOWN TIMER (IF UPCOMING / ONGOING) -->
                @if ($eventState !== 'finished')
                    <div class="bg-brand-forest text-white rounded-2xl sm:rounded-3xl p-3.5 sm:p-8 text-center space-y-3.5 sm:space-y-4 border-2 border-brand-deep shadow-solid-md" 
                         id="countdown-card"
                         data-start="{{ $startTimestampMs }}"
                         data-end="{{ $endTimestampMs }}">
                        
                        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-brand-deep/80 text-brand-lightmatcha border border-brand-lightmatcha/30 text-xs font-black uppercase tracking-wider" id="countdown-label">
                            <i class="bi {{ $eventState === 'ongoing' ? 'bi-broadcast text-amber-400' : 'bi-hourglass-split text-emerald-400' }}"></i>
                            <span id="countdown-label-text">{{ $eventState === 'ongoing' ? 'Status Acara:' : 'Hitung Mundur Pelaksanaan' }}</span>
                        </div>
                        
                        <div id="countdown-content" class="pt-1">
                            <div id="countdown-digits" class="{{ $eventState === 'ongoing' ? 'hidden' : 'flex' }} justify-center items-center gap-1.5 sm:gap-5 text-white">
                                <div class="text-center min-w-[48px] xs:min-w-[54px] sm:min-w-[70px] bg-brand-deep/70 rounded-xl sm:rounded-2xl p-2 sm:p-3 border-2 border-brand-matcha shadow-solid-sm">
                                    <span id="days" class="text-xl xs:text-2xl sm:text-4xl font-black block leading-none text-brand-lightmatcha">00</span>
                                    <span class="text-[8px] sm:text-[10px] uppercase font-black tracking-wider text-white/70 mt-1 block">Hari</span>
                                </div>
                                <span class="text-base sm:text-2xl font-black text-brand-lightmatcha">:</span>
                                <div class="text-center min-w-[48px] xs:min-w-[54px] sm:min-w-[70px] bg-brand-deep/70 rounded-xl sm:rounded-2xl p-2 sm:p-3 border-2 border-brand-matcha shadow-solid-sm">
                                    <span id="hours" class="text-xl xs:text-2xl sm:text-4xl font-black block leading-none text-brand-lightmatcha">00</span>
                                    <span class="text-[8px] sm:text-[10px] uppercase font-black tracking-wider text-white/70 mt-1 block">Jam</span>
                                </div>
                                <span class="text-base sm:text-2xl font-black text-brand-lightmatcha">:</span>
                                <div class="text-center min-w-[48px] xs:min-w-[54px] sm:min-w-[70px] bg-brand-deep/70 rounded-xl sm:rounded-2xl p-2 sm:p-3 border-2 border-brand-matcha shadow-solid-sm">
                                    <span id="minutes" class="text-xl xs:text-2xl sm:text-4xl font-black block leading-none text-brand-lightmatcha">00</span>
                                    <span class="text-[8px] sm:text-[10px] uppercase font-black tracking-wider text-white/70 mt-1 block">Menit</span>
                                </div>
                                <span class="text-base sm:text-2xl font-black text-brand-lightmatcha">:</span>
                                <div class="text-center min-w-[48px] xs:min-w-[54px] sm:min-w-[70px] bg-brand-deep/70 rounded-xl sm:rounded-2xl p-2 sm:p-3 border-2 border-brand-matcha shadow-solid-sm">
                                    <span id="seconds" class="text-xl xs:text-2xl sm:text-4xl font-black block leading-none text-brand-lightmatcha">00</span>
                                    <span class="text-[8px] sm:text-[10px] uppercase font-black tracking-wider text-white/70 mt-1 block">Detik</span>
                                </div>
                            </div>

                            <div id="countdown-message" class="{{ $eventState === 'ongoing' ? '' : 'hidden' }} space-y-2 py-2">
                                <h5 class="text-2xl font-black text-amber-300 tracking-tight">Acara Sedang Berlangsung</h5>
                                <p class="text-xs sm:text-sm font-medium text-white/80 max-w-md mx-auto">
                                    Kegiatan telah dimulai dan sedang berlangsung saat ini di lokasi acara.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- DESCRIPTION BODY -->
                <div class="bg-white rounded-2xl sm:rounded-3xl border-2 border-brand-forest p-4 sm:p-6 lg:p-8 shadow-solid-sm space-y-4">
                    <h2 class="text-base sm:text-lg font-black uppercase tracking-wider text-brand-deep border-b-2 border-brand-sand pb-3">
                        Deskripsi &amp; Rincian Kegiatan
                    </h2>
                    <div class="article-body space-y-4 leading-relaxed font-normal text-sm sm:text-base text-brand-darktext/90">
                        {!! nl2br(e($kegiatan->description)) !!}
                    </div>
                </div>

                <!-- ================= LOKASI KEGIATAN & PETA INTERAKTIF ================= -->
                <section class="space-y-4 pt-2" id="lokasi-kegiatan">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <span class="text-xs font-black uppercase tracking-wider text-brand-matcha block">
                                PETA &amp; TITIK LOKASI
                            </span>
                            <h3 class="text-2xl font-black text-brand-deep tracking-tight mt-0.5">
                                Lokasi Pelaksanaan Acara
                            </h3>
                        </div>
                        @if($kegiatan->hasCoordinates())
                            <div>
                                <a href="{{ $kegiatan->google_maps_url }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brand-forest hover:bg-brand-deep text-white text-xs font-black uppercase tracking-wider transition-all shadow-solid-sm btn-solid-press text-decoration-none border-2 border-brand-deep">
                                    <i class="bi bi-compass"></i>
                                    <span>Navigasi Rute Maps</span>
                                    <i class="bi bi-box-arrow-up-right text-[10px] opacity-75"></i>
                                </a>
                            </div>
                        @endif
                    </div>

                    @if($kegiatan->hasCoordinates())
                        <!-- Interactive Leaflet Map Container -->
                        <div class="rounded-2xl sm:rounded-3xl overflow-hidden border-2 border-brand-forest shadow-solid-md relative bg-white" style="height: 380px; min-height: 320px;">
                            <div id="publicKegiatanMap" 
                                 class="w-full h-full"
                                 style="height: 100%; min-height: 320px; width: 100%;"
                                 data-lat="{{ (float) $kegiatan->latitude }}"
                                 data-lng="{{ (float) $kegiatan->longitude }}"
                                 data-title="{{ $kegiatan->title }}"
                                 data-address="{{ $kegiatan->location }}"
                                 data-maps-url="{{ $kegiatan->google_maps_url }}">
                            </div>
                        </div>

                        <!-- Location Detail & Action Card -->
                        <div class="p-4 sm:p-5 rounded-2xl sm:rounded-3xl bg-white border-2 border-brand-forest flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-solid-sm">
                            <div class="flex items-start gap-3 sm:gap-3.5 min-w-0">
                                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-brand-forest text-white border-2 border-brand-deep flex items-center justify-center shrink-0 shadow-solid-sm">
                                    <i class="bi bi-geo-alt-fill text-base sm:text-lg text-brand-accent"></i>
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] sm:text-[11px] uppercase tracking-wider font-black text-brand-forest/70 block">Alamat Pelaksanaan</span>
                                    <p class="text-sm sm:text-base font-bold text-brand-deep mb-0 leading-snug break-words">
                                        {{ $kegiatan->location }}
                                    </p>
                                    <span class="text-[10px] sm:text-[11px] text-brand-darktext/60 block mt-1 font-mono font-bold">
                                        Koordinat: {{ number_format($kegiatan->latitude, 6) }}, {{ number_format($kegiatan->longitude, 6) }}
                                    </span>
                                </div>
                            </div>
                            <div class="shrink-0 w-full sm:w-auto">
                                <a href="{{ $kegiatan->google_maps_url }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 sm:py-2.5 min-h-[44px] lg:min-h-0 rounded-xl bg-brand-sand hover:bg-brand-forest hover:text-white text-brand-forest text-xs font-black uppercase tracking-wider transition-all border-2 border-brand-forest shadow-solid-sm btn-solid-press text-decoration-none">
                                    <i class="bi bi-geo-alt"></i>
                                    <span>Buka di Google Maps</span>
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- Graceful Fallback if coordinates are not set -->
                        <div class="p-4 sm:p-6 rounded-2xl sm:rounded-3xl bg-white border-2 border-brand-forest shadow-solid-sm">
                            <div class="flex items-start gap-3 sm:gap-3.5">
                                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-brand-sand text-brand-forest border-2 border-brand-forest flex items-center justify-center shrink-0">
                                    <i class="bi bi-geo-alt text-base sm:text-lg"></i>
                                </div>
                                <div>
                                    <span class="text-[10px] sm:text-[11px] uppercase tracking-wider font-black text-brand-forest/70 block">Alamat Pelaksanaan</span>
                                    <p class="text-sm sm:text-base font-bold text-brand-deep mb-1">
                                        {{ $kegiatan->location }}
                                    </p>
                                    <p class="text-xs font-medium text-brand-darktext/60 mb-0">
                                        <i class="bi bi-info-circle me-1"></i> Titik koordinat peta interaktif belum ditentukan untuk kegiatan ini.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </section>
            </main>

            <!-- RIGHT COLUMN: SIDEBAR WIDGETS -->
            <aside class="lg:col-span-4 space-y-5 sm:space-y-6">
                
                <!-- WIDGET 1: INFORMASI ACARA -->
                <div class="bg-white border-2 border-brand-forest rounded-2xl sm:rounded-3xl p-4 sm:p-6 lg:p-6 space-y-4 shadow-solid-sm">
                    <h4 class="text-xs font-black uppercase tracking-wider text-brand-deep border-b-2 border-brand-sand pb-3">
                        Informasi Acara
                    </h4>

                    <div class="space-y-3.5 sm:space-y-4 text-xs font-medium">
                        <div>
                            <span class="text-brand-darktext/60 block text-[10px] uppercase font-bold tracking-wider">Hari &amp; Tanggal</span>
                            <span class="font-black text-brand-deep text-sm sm:text-[15px] mt-0.5 block leading-snug">
                                {{ \Carbon\Carbon::parse($kegiatan->event_date)->translatedFormat('l, d F Y') }}
                            </span>
                        </div>

                        <div>
                            <span class="text-brand-darktext/60 block text-[10px] uppercase font-bold tracking-wider">Waktu Pelaksanaan</span>
                            <span class="font-black text-brand-deep text-sm sm:text-[15px] mt-0.5 block leading-snug">
                                {{ \Carbon\Carbon::parse($kegiatan->start_time)->format('H:i') }}
                                @if ($kegiatan->end_time)
                                    - {{ \Carbon\Carbon::parse($kegiatan->end_time)->format('H:i') }}
                                @endif
                                WIB
                            </span>
                        </div>

                        <div>
                            <span class="text-brand-darktext/60 block text-[10px] uppercase font-bold tracking-wider">Lokasi Acara</span>
                            <span class="font-black text-brand-deep text-sm sm:text-[15px] mt-0.5 block leading-snug">
                                {{ $kegiatan->location }}
                            </span>
                            @if($kegiatan->hasCoordinates())
                                <a href="{{ $kegiatan->google_maps_url }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1 text-xs font-black text-brand-matcha hover:underline mt-1.5">
                                    <i class="bi bi-compass"></i>
                                    <span>Petunjuk Rute Maps &rarr;</span>
                                </a>
                            @endif
                        </div>

                        <div>
                            <span class="text-brand-darktext/60 block text-[10px] uppercase font-bold tracking-wider">Penyelenggara</span>
                            <span class="font-black text-brand-deep text-sm sm:text-[15px] flex items-center gap-2 mt-0.5 leading-snug">
                                <span class="w-2.5 h-2.5 rounded-full bg-brand-forest"></span> {{ \App\Support\Organisasi::fullName($kegiatan->kategori) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- WIDGET 2: BAGIKAN AGENDA -->
                <div class="bg-white border-2 border-brand-forest rounded-2xl sm:rounded-3xl p-4 sm:p-6 lg:p-6 space-y-4 shadow-solid-sm">
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-wider text-brand-deep">
                            Bagikan Agenda
                        </h4>
                        <p class="text-xs text-brand-darktext/70 mt-1 mb-0 font-medium">
                            Syiarkan informasi agenda dan kegiatan ini kepada warga Nahdliyin.
                        </p>
                    </div>

                    <div class="space-y-2.5 pt-1">
                        <!-- WhatsApp -->
                        <a href="https://api.whatsapp.com/send?text={{ urlencode('Hadirilah Kegiatan: ' . $kegiatan->title . ' - ' . url()->current()) }}" 
                           target="_blank" rel="noopener noreferrer"
                           class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3 sm:py-2.5 px-4 min-h-[44px] rounded-xl text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2 transition-all shadow-solid-sm btn-solid-press border-2 border-emerald-900 text-decoration-none">
                            <i class="bi bi-whatsapp text-sm"></i>
                            <span>WhatsApp</span>
                        </a>

                        <!-- Facebook -->
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                           target="_blank" rel="noopener noreferrer"
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 sm:py-2.5 px-4 min-h-[44px] rounded-xl text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2 transition-all shadow-solid-sm btn-solid-press border-2 border-blue-900 text-decoration-none">
                            <i class="bi bi-facebook text-sm"></i>
                            <span>Facebook</span>
                        </a>

                        <!-- Salin Link -->
                        <button onclick="copyLinkOnly()" id="copyEventBtn"
                                class="w-full bg-brand-sand hover:bg-[#DDD7C8] border-2 border-brand-forest text-brand-forest py-3 sm:py-2.5 px-4 min-h-[44px] rounded-xl text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2 transition-all shadow-solid-sm btn-solid-press cursor-pointer">
                            <i class="bi bi-link-45deg text-base"></i>
                            <span id="copyBtnText">Salin Tautan</span>
                        </button>
                    </div>
                </div>

                <!-- WIDGET 3: KOMITMEN DAKWAH -->
                <div class="bg-brand-lightmatcha border-2 border-brand-forest rounded-2xl sm:rounded-3xl p-4 sm:p-6 text-center space-y-2 shadow-solid-sm">
                    <div class="w-10 h-10 rounded-xl bg-brand-forest text-white border-2 border-brand-deep flex items-center justify-center mx-auto shadow-solid-sm">
                        <i class="bi bi-shield-check text-lg"></i>
                    </div>
                    <h5 class="text-xs font-black uppercase tracking-wider text-brand-deep">Informasi Resmi Jam'iyyah</h5>
                    <p class="text-xs font-medium text-brand-darktext/80 leading-relaxed mb-0">
                        Seluruh jadwal kegiatan resmi diterbitkan oleh Tim Media &amp; Dakwah Majelis Wakil Cabang Nahdlatul Ulama Wangon.
                    </p>
                </div>

            </aside>

        </div>

        <!-- ================= SECTION AGENDA LAINNYA ================= -->
        @php
            $otherKegiatan = isset($relatedKegiatan) && $relatedKegiatan->isNotEmpty() 
                ? $relatedKegiatan 
                : (isset($kegiatanList) ? $kegiatanList->where('id', '!=', $kegiatan->id)->take(3) : collect());
        @endphp

        @if($otherKegiatan->isNotEmpty())
            <div class="pt-6 sm:pt-8 border-t-2 border-brand-sand space-y-5 sm:space-y-6">
                <div>
                    <span data-text-animate="blurInUp" class="text-xs font-black uppercase tracking-wider text-brand-matcha">ARSIP &amp; AGENDA TERKAIT</span>
                    <h3 data-text-animate="blurInUp" class="text-2xl sm:text-3xl font-black text-brand-deep tracking-tight mt-0.5">Agenda Lainnya</h3>
                    <p class="text-xs sm:text-sm font-medium text-brand-darktext/75">Simak jadwal dan program dakwah lainnya dari Organisasi NU Wangon.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">
                    @foreach($otherKegiatan->take(4) as $item)
                        <article class="bg-white border-2 border-brand-forest rounded-2xl sm:rounded-3xl overflow-hidden flex flex-col justify-between p-3.5 sm:p-4.5 lg:p-4.5 hover:-translate-y-1 hover:shadow-solid-md transition-all shadow-solid-sm group">
                            <div class="space-y-3">
                                <div class="relative h-40 sm:h-36 rounded-2xl overflow-hidden bg-brand-sand border-2 border-brand-forest">
                                    @if ($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" alt="{{ $item->title }}">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-brand-forest/50">
                                            <i class="bi bi-calendar-event text-3xl"></i>
                                        </div>
                                    @endif
                                    <span class="absolute top-2.5 left-2.5 bg-white text-brand-forest border border-brand-forest text-[9px] font-black uppercase px-2.5 py-0.5 rounded-full shadow-solid-sm">
                                        {{ \App\Support\Organisasi::badgeLabel($item->kategori) }}
                                    </span>
                                </div>

                                <div class="space-y-1.5">
                                    <span class="text-[10px] font-bold text-brand-darktext/70 flex items-center gap-1">
                                        <i class="bi bi-calendar3 text-brand-forest"></i>
                                        {{ \Carbon\Carbon::parse($item->event_date)->translatedFormat('d M Y') }}
                                    </span>
                                    <h4 class="font-black text-sm sm:text-base text-brand-deep line-clamp-2 leading-snug group-hover:text-brand-matcha transition-colors">
                                        <a href="{{ route('kegiatan.show', $item->id) }}" class="text-inherit text-decoration-none">
                                            {{ $item->title }}
                                        </a>
                                    </h4>
                                </div>
                            </div>

                            <div class="pt-3 mt-3 border-t-2 border-brand-sand flex items-center justify-between">
                                <span class="text-[10px] font-bold text-brand-darktext/60 truncate max-w-[120px]">{{ $item->location }}</span>
                                <a href="{{ route('kegiatan.show', $item->id) }}" class="text-xs font-black text-brand-forest hover:text-brand-matcha text-decoration-none min-h-[44px] inline-flex items-center py-1">
                                    Rincian &rarr;
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

    @if ($eventState !== 'finished')
    (function () {
        function initDetailCountdown() {
            const card = document.getElementById("countdown-card");
            if (!card) return;

            const startMs = parseInt(card.getAttribute("data-start"), 10);
            const endMs = parseInt(card.getAttribute("data-end"), 10);
            if (isNaN(startMs)) return;

            const label = document.getElementById("countdown-label");
            const digits = document.getElementById("countdown-digits");
            const msg = document.getElementById("countdown-message");
            const badge = document.getElementById("status-badge");
            const daysEl = document.getElementById("days");
            const hoursEl = document.getElementById("hours");
            const minEl = document.getElementById("minutes");
            const secEl = document.getElementById("seconds");

            function pad(num) {
                return num < 10 ? '0' + num : num;
            }

            function updateCountdown() {
                const now = Date.now();

                if (now >= endMs) {
                    // State: Selesai
                    if (label) label.innerHTML = '<i class="bi bi-check-circle-fill me-1 text-white/60"></i> <span id="countdown-label-text">Status Acara:</span>';
                    if (digits) digits.classList.add('hidden');
                    if (msg) {
                        msg.classList.remove('hidden');
                        msg.innerHTML = '<h5 class="text-2xl font-black text-white/90 tracking-tight">Acara Telah Selesai</h5><p class="text-xs sm:text-sm text-white/70">Kegiatan telah selesai dilaksanakan.</p>';
                    }
                    if (badge) {
                        badge.innerHTML = '<span class="inline-flex items-center gap-1.5 bg-brand-sand text-brand-forest text-xs font-black uppercase tracking-wider px-4 py-1.5 rounded-full border-2 border-brand-forest shadow-solid-sm"><i class="bi bi-check-circle-fill"></i> Selesai</span>';
                    }
                    return;
                }

                if (now >= startMs) {
                    // State: Sedang Berlangsung
                    if (label) label.innerHTML = '<i class="bi bi-broadcast me-1 text-amber-400"></i> <span id="countdown-label-text">Status Acara:</span>';
                    if (digits) digits.classList.add('hidden');
                    if (msg) {
                        msg.classList.remove('hidden');
                        msg.innerHTML = '<h5 class="text-2xl font-black text-amber-300 tracking-tight">Acara Sedang Berlangsung</h5><p class="text-xs sm:text-sm font-medium text-white/80 max-w-md mx-auto">Kegiatan telah dimulai dan sedang berlangsung saat ini di lokasi acara.</p>';
                    }
                    if (badge) {
                        badge.innerHTML = '<span class="inline-flex items-center gap-1.5 bg-brand-accent text-white text-xs font-black uppercase tracking-wider px-4 py-1.5 rounded-full border-2 border-brand-deep shadow-solid-sm animate-pulse"><span class="w-2 h-2 rounded-full bg-white"></span> Sedang Berlangsung</span>';
                    }
                    return;
                }

                // State: Mendatang (Hitung Mundur)
                const diff = Math.max(0, startMs - now);
                const totalSeconds = Math.floor(diff / 1000);
                const days = Math.floor(totalSeconds / 86400);
                const hours = Math.floor((totalSeconds % 86400) / 3600);
                const minutes = Math.floor((totalSeconds % 3600) / 60);
                const seconds = totalSeconds % 60;

                if (digits) digits.classList.remove('hidden');
                if (msg) msg.classList.add('hidden');

                if (daysEl) daysEl.textContent = pad(days);
                if (hoursEl) hoursEl.textContent = pad(hours);
                if (minEl) minEl.textContent = pad(minutes);
                if (secEl) secEl.textContent = pad(seconds);
            }

            updateCountdown();
            setInterval(updateCountdown, 1000);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initDetailCountdown);
        } else {
            initDetailCountdown();
        }
    })();
    @endif
</script>

<!-- Leaflet JS for Public Interactive Map -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

@if($kegiatan->hasCoordinates())
<script>
(function() {
    function initPublicKegiatanMap() {
        const mapContainer = document.getElementById('publicKegiatanMap');
        if (!mapContainer || typeof L === 'undefined') return;

        // Prevent duplicate initialization
        if (mapContainer._leaflet_id) return;

        const lat = parseFloat(mapContainer.dataset.lat);
        const lng = parseFloat(mapContainer.dataset.lng);
        const title = mapContainer.dataset.title || '';
        const address = mapContainer.dataset.address || '';
        const mapsUrl = mapContainer.dataset.mapsUrl || ('https://www.google.com/maps/dir/?api=1&destination=' + lat + ',' + lng);

        if (isNaN(lat) || isNaN(lng)) return;

        // Fix Leaflet marker icon asset URLs when loaded via CDN
        delete L.Icon.Default.prototype._getIconUrl;
        L.Icon.Default.mergeOptions({
            iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
            shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
        });

        // Initialize Map
        const map = L.map(mapContainer, {
            scrollWheelZoom: false,
            zoomControl: true,
            tap: true
        }).setView([lat, lng], 16);

        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener noreferrer">OpenStreetMap</a>'
        }).addTo(map);

        // Add Marker
        const marker = L.marker([lat, lng]).addTo(map);

        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }

        const safeTitle = escapeHtml(title);
        const safeAddress = escapeHtml(address);

        marker.bindPopup(`
            <div style="font-family:'Plus Jakarta Sans',system-ui,sans-serif;max-width:260px;padding:4px 2px;">
                <span style="display:inline-block;background:#D9E8DD;color:#142E25;font-size:9px;font-weight:900;text-transform:uppercase;letter-spacing:0.1em;padding:3px 8px;border-radius:9999px;margin-bottom:6px;border:1px solid #142E25;">
                    Lokasi Terpilih
                </span>
                <strong style="color:#0E221B;font-size:13px;display:block;margin-bottom:4px;line-height:1.3;font-weight:900;">
                    ${safeTitle}
                </strong>
                <p style="font-size:11px;color:#333;margin:0 0 8px 0;line-height:1.4;font-weight:500;">
                    ${safeAddress}
                </p>
                <div style="border-top:1px solid #ECE8DD;padding-top:6px;margin-top:4px;">
                    <a href="${mapsUrl}" target="_blank" rel="noopener noreferrer" style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:900;color:#142E25;text-decoration:none;">
                        Buka Rute Google Maps &rarr;
                    </a>
                </div>
            </div>
        `).openPopup();

        setTimeout(function() { map.invalidateSize(); }, 150);
        setTimeout(function() { map.invalidateSize(); }, 500);
        setTimeout(function() { map.invalidateSize(); }, 1000);

        window.addEventListener('resize', function() {
            map.invalidateSize();
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPublicKegiatanMap);
    } else {
        initPublicKegiatanMap();
    }
})();
</script>
@endif

@endsection