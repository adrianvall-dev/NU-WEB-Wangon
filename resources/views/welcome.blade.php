@extends('layouts.app')

@section('title', 'Organisasi NU Wangon | Majelis Wakil Cabang Nahdlatul Ulama')

@section('content')

<style>
    /* =========================================================================
       SOLID-WEIGHT GROTESQUE THEME PALETTE & INTERACTION STYLES
       ========================================================================= */
    :root {
        --brand-forest: #142E25;
        --brand-deep: #0E221B;
        --brand-matcha: #2D4736;
        --brand-lightmatcha: #D9E8DD;
        --brand-accent: #D97706;
        --brand-cream: #F6F4EE;
        --brand-sand: #ECE8DD;
        --brand-darktext: #111A16;
        --brand-border: #233F34;
        --brand-lightborder: #D5CEC0;
    }

    .bg-brand-forest { background-color: #142E25; }
    .bg-brand-deep { background-color: #0E221B; }
    .bg-brand-matcha { background-color: #2D4736; }
    .bg-brand-lightmatcha { background-color: #D9E8DD; }
    .bg-brand-accent { background-color: #D97706; }
    .bg-brand-cream { background-color: #F6F4EE; }
    .bg-brand-sand { background-color: #ECE8DD; }

    .text-brand-forest { color: #142E25; }
    .text-brand-deep { color: #0E221B; }
    .text-brand-matcha { color: #2D4736; }
    .text-brand-lightmatcha { color: #D9E8DD; }
    .text-brand-accent { color: #D97706; }
    .text-brand-darktext { color: #111A16; }

    .border-brand-forest { border-color: #142E25; }
    .border-brand-deep { border-color: #0E221B; }
    .border-brand-matcha { border-color: #2D4736; }
    .border-brand-lightmatcha { border-color: #D9E8DD; }
    .border-brand-lightborder { border-color: #D5CEC0; }
    .border-brand-sand { border-color: #ECE8DD; }

    .shadow-solid-sm { box-shadow: 3px 3px 0px #142E25; }
    .shadow-solid-md { box-shadow: 5px 5px 0px #142E25; }
    .shadow-solid-lg { box-shadow: 7px 7px 0px #142E25; }
    .shadow-solid-accent { box-shadow: 5px 5px 0px #0E221B; }
    .shadow-solid-light { box-shadow: 4px 4px 0px #C7C0B0; }

    /* Interactive button depression physics */
    .btn-solid-press {
        transition: transform 0.1s ease, box-shadow 0.1s ease;
    }
    .btn-solid-press:active {
        transform: translate(2px, 2px);
        box-shadow: 1px 1px 0px #142E25 !important;
    }

    /* =========================================================================
       FLOATING FRAME VISUAL & MOTION SYSTEM (FRAMER REFERENCE ENGINE)
       ========================================================================= */
    
    /* Floating Frame Base Card */
    .floating-frame-card {
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1),
                    box-shadow 0.35s cubic-bezier(0.16, 1, 0.3, 1),
                    border-color 0.25s ease;
        will-change: transform, box-shadow;
    }
    .floating-frame-card:hover {
        transform: translateY(-5px) scale(1.006) !important;
        box-shadow: 7px 7px 0px #142E25 !important;
    }

    /* Floating Image Zoom within Frame */
    .floating-frame-image {
        transition: transform 0.65s cubic-bezier(0.16, 1, 0.3, 1);
        will-change: transform;
    }
    .floating-frame-card:hover .floating-frame-image,
    .group:hover .floating-frame-image {
        transform: scale(1.035);
    }

    /* Ambient Floating Physics (Framer FloatingFrame engine) */
    @keyframes heroSpotlightFloat {
        0%, 100% {
            transform: translateY(0px) rotate(0deg);
        }
        50% {
            transform: translateY(-7px) rotate(0.35deg);
        }
    }

    @keyframes floatFrameGentle {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-5px);
        }
    }

    @keyframes floatBadgeDrift {
        0%, 100% {
            transform: translateY(0px) translateX(0px);
        }
        33% {
            transform: translateY(-3.5px) translateX(1.5px);
        }
        66% {
            transform: translateY(1.5px) translateX(-1.5px);
        }
    }

    @media (min-width: 1024px) {
        .hero-spotlight-frame {
            animation: heroSpotlightFloat 7s ease-in-out infinite;
            will-change: transform;
        }

        .floating-ambient-float {
            animation: floatFrameGentle 6s ease-in-out infinite;
            will-change: transform;
        }

        .floating-badge-drift {
            animation: floatBadgeDrift 5s ease-in-out infinite;
            will-change: transform;
        }
    }

    /* Unified Floating Scroll Reveal */
    .floating-reveal {
        opacity: 0;
        transform: translateY(22px) scale(0.988);
        transition: opacity 0.75s cubic-bezier(0.16, 1, 0.3, 1),
                    transform 0.75s cubic-bezier(0.16, 1, 0.3, 1);
        will-change: opacity, transform;
    }
    .floating-reveal.is-revealed {
        opacity: 1;
        transform: translateY(0px) scale(1);
    }

    /* Stagger Delays for Grid Elements */
    .reveal-delay-1 { transition-delay: 0.08s; }
    .reveal-delay-2 { transition-delay: 0.16s; }
    .reveal-delay-3 { transition-delay: 0.24s; }
    .reveal-delay-4 { transition-delay: 0.32s; }

    /* Parallax Depth Layer Markers */
    [data-floating-depth] {
        will-change: transform;
    }

    /* Accessibility: Respect Reduced Motion */
    @media (prefers-reduced-motion: reduce) {
        .floating-reveal {
            opacity: 1 !important;
            transform: none !important;
            transition: none !important;
        }
        .floating-frame-card {
            transition: none !important;
            transform: none !important;
        }
        .hero-spotlight-frame,
        .floating-ambient-float,
        .floating-badge-drift {
            animation: none !important;
            transform: none !important;
        }
        .floating-frame-image {
            transition: none !important;
            transform: none !important;
        }
    }

    /* Responsive News Grid Spacing (Visual Breathing Room for Parallax) & Ultra-Compact Fallback */
    .responsive-news-grid {
        column-gap: 16px;
        row-gap: 28px;
    }
    @media (min-width: 640px) {
        .responsive-news-grid {
            column-gap: 24px;
            row-gap: 32px;
        }
    }
    @media (min-width: 1024px) {
        .responsive-news-grid {
            column-gap: 28px;
            row-gap: 40px;
        }
    }
    @media (max-width: 360px) {
        .responsive-news-grid {
            grid-template-columns: 1fr !important;
        }
        .responsive-news-grid > article {
            grid-column: 1 / -1 !important;
        }
    }
</style>

<div x-data="{ consultModalOpen: false }" class="min-h-screen bg-brand-cream text-brand-darktext font-sans selection:bg-brand-matcha selection:text-white">

    <!-- ========================================================================= -->
    <!-- 01: HERO SECTION (SOLID GROTESQUE 2-COLUMN + SHOWCASE CARD + STAT STRIP) -->
    <!-- ========================================================================= -->
    <section class="pt-10 pb-16 sm:pt-14 sm:pb-20 bg-brand-cream border-b-2 border-brand-forest relative overflow-hidden" id="beranda">
        <!-- Abstract Geometric Background (PixelOS Inspiration - Layer 1: Base Grid & Large Translucent Auras 550-750px) -->
        <div class="absolute inset-0 pixel-grid-blueprint opacity-65 pointer-events-none -z-0"></div>
        <div class="absolute -top-16 -left-32 w-[600px] h-[600px] rounded-full bg-gradient-to-tr from-brand-matcha/[0.22] via-brand-lightmatcha/[0.38] to-transparent pointer-events-none blur-3xl -z-0" data-parallax="bg-slow"></div>
        <div class="absolute -top-28 -right-36 w-[720px] h-[720px] rounded-full bg-gradient-to-bl from-brand-matcha/[0.20] via-brand-lightmatcha/[0.32] to-transparent pointer-events-none blur-3xl -z-0" data-parallax="bg-slow"></div>

        <!-- Layer 2: Oversized Focal Geometry (680-820px Concentric Rings with 6-8px Stroke & 4px Curved Line) -->
        <div class="pointer-events-none absolute -top-20 -right-48 w-[680px] h-[680px] lg:w-[820px] lg:h-[820px] -z-0" data-parallax="bg" data-parallax-rotate="4">
            <div class="ambient-float-slow-1 w-full h-full flex items-center justify-center">
                <!-- Outer Ring: 6px bold stroke (opacity 0.28) -->
                <div class="w-full h-full rounded-full border-[6px] border-brand-forest/[0.28] flex items-center justify-center p-14 sm:p-20">
                    <!-- Middle Ring: 4px dashed stroke (opacity 0.32) -->
                    <div class="w-full h-full rounded-full border-4 border-dashed border-brand-matcha/[0.32] flex items-center justify-center p-12 sm:p-16">
                        <!-- Core Ring: 3px stroke with rich translucent fill (opacity 0.25) -->
                        <div class="w-full h-full rounded-full border-[3px] border-brand-forest/[0.22] bg-gradient-to-br from-brand-lightmatcha/40 via-brand-matcha/[0.14] to-transparent"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pointer-events-none absolute top-12 left-1/4 w-[480px] h-[320px] -z-0 hidden md:block" data-parallax="bg-slow" data-parallax-rotate="-2">
            <svg class="w-full h-full overflow-visible" viewBox="0 0 480 320" fill="none">
                <path d="M 0,260 C 140,280 260,180 380,40" stroke="rgba(20, 46, 37, 0.28)" stroke-width="4" stroke-linecap="round" stroke-dasharray="10 10" />
                <circle cx="380" cy="40" r="7" fill="rgba(217, 119, 6, 0.55)" stroke="rgba(20, 46, 37, 0.35)" stroke-width="2" />
                <circle cx="0" cy="260" r="5.5" fill="rgba(20, 46, 37, 0.40)" />
            </svg>
        </div>

        <!-- Layer 3: Midground Geometric Shapes with Thick 4-5px Borders & Solid Visual Mass -->
        <div class="pointer-events-none absolute top-1/2 -left-16 w-80 h-24 -z-0 hidden sm:block" data-parallax="mid" data-parallax-rotate="-3">
            <div class="ambient-float-slow-2 w-full h-full rounded-full border-4 border-brand-forest/[0.26] bg-brand-lightmatcha/40 backdrop-blur-xs -rotate-12 flex items-center px-8 justify-between shadow-sm">
                <span class="w-4 h-4 rounded-full bg-brand-matcha/40 border-2 border-brand-forest/30"></span>
                <span class="h-1.5 w-24 rounded-full bg-brand-forest/25"></span>
                <span class="w-3.5 h-3.5 rounded-full border-2 border-brand-accent/50 bg-brand-accent/35"></span>
            </div>
        </div>
        <div class="pointer-events-none absolute bottom-6 left-1/3 w-64 h-64 -z-0 hidden lg:block" data-parallax="mid" data-parallax-rotate="3">
            <div class="ambient-float-slow-3 w-full h-full rounded-[44px] border-4 border-brand-forest/[0.22] bg-brand-matcha/[0.08] backdrop-blur-xs rotate-6 p-5 flex flex-col justify-between">
                <div class="flex justify-between items-center text-brand-forest/[0.35] text-base font-mono font-bold">
                    <span>+</span>
                    <span class="w-3 h-3 rounded-full bg-brand-matcha/40"></span>
                </div>
                <div class="text-right text-brand-forest/[0.35] text-base font-mono font-bold">+</div>
            </div>
        </div>

        <!-- Layer 4: Foreground Accents & Dot Matrix Details -->
        <div class="pointer-events-none absolute top-10 left-6 w-48 h-48 pixel-dot-matrix opacity-85 hidden lg:block -z-0" data-parallax="fore"></div>
        <div class="pointer-events-none absolute bottom-16 right-1/4 text-brand-forest/[0.35] text-base font-mono font-bold select-none hidden md:block -z-0" data-parallax="fore">+</div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- 2-Column Hero Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
                
                <!-- Left Column (7 cols): Editorial & Action -->
                <div class="lg:col-span-7 space-y-6" data-floating-depth="mid">
                    <!-- Eyebrow Badge -->
                    <div id="hero-label" data-text-animate="blurInUp" class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-white text-brand-forest border-2 border-brand-forest rounded-full text-xs font-black uppercase tracking-wider shadow-solid-sm">
                        <span class="w-2 h-2 rounded-full bg-brand-matcha animate-pulse"></span>
                        <span>MAJELIS WAKIL CABANG NU WANGON</span>
                    </div>

                    <!-- Main Weighty Heading -->
                    <h1 id="hero-title" data-text-animate="blurInUp" class="text-4xl sm:text-5xl lg:text-[54px] font-black text-brand-deep tracking-tight leading-[1.1] sm:leading-[1.08]">
                        Bergerak Bersama <br class="hidden sm:inline">
                        Memajukan Jam'iyyah &amp; <br class="hidden sm:inline">
                        <span class="inline-block bg-brand-lightmatcha text-brand-forest px-3 py-0.5 rounded-xl border-2 border-brand-forest shadow-solid-sm mt-1">
                            Pemberdayaan Umat
                        </span>
                    </h1>

                    <!-- Supporting Narrative -->
                    <p id="hero-desc" class="text-base sm:text-lg font-medium text-brand-darktext/80 max-w-xl leading-relaxed">
                        Portal informasi resmi Organisasi NU Wangon yang menghasilkan informasi kegiatan, agenda, berita, program, dan keanggotaan dalam satu tempat.
                    </p>

                    <!-- CTAs Action Row -->
                    <div id="hero-cta" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-3.5 pt-2">
                        <a href="#berita" 
                           class="w-full sm:w-auto px-6 py-3.5 bg-brand-forest hover:bg-brand-deep text-white font-black text-xs uppercase tracking-wider rounded-xl border-2 border-brand-deep shadow-solid-md transition-all btn-solid-press flex items-center justify-center gap-2 text-decoration-none">
                            <span>Baca Berita &amp; Informasi</span>
                            <span class="text-sm leading-none">&rarr;</span>
                        </a>

                        <a href="#tentang" 
                           class="w-full sm:w-auto px-6 py-3.5 bg-brand-sand hover:bg-[#E2DDD0] text-brand-forest font-black text-xs uppercase tracking-wider rounded-xl border-2 border-brand-forest shadow-solid-sm transition-all btn-solid-press flex items-center justify-center text-decoration-none text-center">
                            <span>Tentang NU Wangon</span>
                        </a>
                    </div>

                    <!-- Banom & Ranting Social Proof Badges -->
                    <div id="hero-meta" class="pt-6 border-t-2 border-brand-lightborder flex items-center gap-4">
                        @php
                            $orgList = $organisasiList ?? collect();
                            $previewOrgs = $orgList->take(3);
                            $remainingOrgCount = max(0, $totalOrganisasi - $previewOrgs->count());
                        @endphp

                        @if($totalOrganisasi > 0)
                            <!-- Stacked Avatars with 2px borders -->
                            <div class="flex -space-x-2.5 overflow-hidden py-0.5">
                                @foreach($previewOrgs as $org)
                                    <a href="{{ route('anggota.index', ['organisasi' => $org->slug]) }}" 
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-brand-forest text-white text-[10px] font-black border-2 border-brand-cream shadow-[2px_2px_0px_#142E25] hover:scale-110 hover:z-10 transition-transform text-decoration-none overflow-hidden shrink-0" 
                                       title="{{ $org->nama }}{{ $org->singkatan ? ' (' . $org->singkatan . ')' : '' }}">
                                        @if($org->logo_url)
                                            <img src="{{ $org->logo_url }}" alt="{{ $org->singkatan ?? $org->nama }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="leading-none {{ strlen($org->inisial) > 3 ? 'text-[8px]' : 'text-[10px]' }}">{{ $org->inisial }}</span>
                                        @endif
                                    </a>
                                @endforeach

                                @if($remainingOrgCount > 0)
                                    <a href="{{ route('anggota.index') }}" 
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-brand-sand text-brand-forest border-2 border-brand-forest text-[10px] font-black shadow-[2px_2px_0px_#142E25] hover:scale-110 hover:z-10 transition-transform text-decoration-none shrink-0" 
                                       title="+{{ $remainingOrgCount }} organisasi lainnya">
                                        +<span data-number-ticker="{{ $remainingOrgCount }}">{{ $remainingOrgCount }}</span>
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="w-9 h-9 rounded-full bg-brand-sand text-brand-forest border-2 border-brand-forest flex items-center justify-center text-xs shrink-0" title="Belum ada data organisasi">
                                <i class="bi bi-diagram-3"></i>
                            </div>
                        @endif

                        <div class="leading-tight">
                            <p class="font-black text-xs sm:text-sm text-brand-deep">
                                {{ $totalOrganisasi }} Badan Otonom &amp; Ranting
                            </p>
                            <p class="text-[11px] sm:text-xs font-semibold text-brand-darktext/70 mt-0.5">
                                @if($totalOrganisasi > 0)
                                    Badan otonom dan lembaga di lingkungan organisasi
                                @else
                                    Belum ada badan otonom terdaftar di sistem
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right Column (5 cols): Hero Feature Spotlight Card -->
                <div class="lg:col-span-5 relative" id="hero-image-col">
                    <div class="bg-white rounded-3xl border-2 border-brand-forest shadow-solid-lg p-3.5 sm:p-5 lg:p-6 overflow-hidden relative group hero-spotlight-frame floating-frame-card" 
                         data-floating-depth="float"
                         data-parallax="card-large">
                        
                        <!-- Top Tag Bar -->
                        <div class="flex items-center justify-between mb-3.5 sm:mb-4">
                            <span class="px-3 py-1 bg-brand-lightmatcha text-brand-forest border-2 border-brand-forest rounded-full text-[10px] font-black uppercase tracking-wider shadow-solid-sm">
                                Dokumentasi Resmi
                            </span>
                            <span class="flex items-center gap-1.5 text-xs font-black text-brand-matcha">
                                <span class="w-2 h-2 rounded-full bg-brand-matcha animate-ping"></span>
                                MWC NU Wangon
                            </span>
                        </div>

                        <!-- Image Container with 2px Border -->
                        <div class="relative rounded-2xl overflow-hidden border-2 border-brand-forest aspect-[15/11] sm:aspect-[14/11] lg:aspect-[14/11] bg-stone-100 group">
                            <img src="{{ asset('images/landing/hero-flag.jpg') }}" 
                                 alt="Dokumentasi Konferensi MWC NU Wangon" 
                                 class="w-full h-full object-cover filter contrast-[1.04] floating-frame-image group-hover:scale-104 transition-transform duration-700 ease-out">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/10 to-transparent pointer-events-none"></div>
                            
                            <!-- Floating Solid Pill Badge on Photo -->
                            <div class="absolute bottom-2.5 left-2.5 right-2.5 sm:bottom-3 sm:left-3 sm:right-3 bg-white/95 backdrop-blur-md rounded-xl p-2.5 sm:p-3 border-2 border-brand-forest shadow-solid-sm flex items-center gap-2.5 sm:gap-3 floating-badge-drift" data-floating-depth="badge">
                                <div class="w-8 h-8 rounded-lg bg-brand-lightmatcha border-2 border-brand-forest flex items-center justify-center text-brand-forest shrink-0">
                                    <i class="bi bi-geo-alt-fill text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-black text-xs sm:text-[13px] text-brand-deep truncate leading-tight">Konferensi MWC NU Wangon</p>
                                    <p class="text-[11px] sm:text-xs font-semibold text-brand-darktext/70 truncate leading-tight mt-0.5">Aula Sindurjan, Banyumas</p>
                                </div>
                            </div>
                        </div>

                        <!-- Solid Spec Grid Beneath Photo -->
                        <div class="mt-3.5 sm:mt-4 bg-brand-cream rounded-xl border-2 border-brand-forest p-2.5 sm:p-3.5 lg:p-3.5 grid grid-cols-2 xs:grid-cols-4 gap-2 text-center">
                            <div class="py-0.5">
                                <span class="text-[9px] sm:text-[10px] font-black uppercase text-brand-forest/70 block leading-tight">Berdiri</span>
                                <span class="text-sm sm:text-base font-black text-brand-deep block leading-tight mt-0.5">1926 M</span>
                            </div>
                            <div class="border-l-2 border-brand-lightborder py-0.5">
                                <span class="text-[9px] sm:text-[10px] font-black uppercase text-brand-forest/70 block leading-tight">Kultur</span>
                                <span class="text-sm sm:text-base font-black text-brand-matcha block leading-tight mt-0.5">Aswaja</span>
                            </div>
                            <div class="border-t-2 xs:border-t-0 xs:border-l-2 pt-2 xs:pt-0.5 pb-0.5 border-brand-lightborder">
                                <span class="text-[9px] sm:text-[10px] font-black uppercase text-brand-forest/70 block leading-tight">Wilayah</span>
                                <span class="text-sm sm:text-base font-black text-brand-deep block leading-tight mt-0.5">Wangon</span>
                            </div>
                            <div class="border-t-2 xs:border-t-0 border-l-2 pt-2 xs:pt-0.5 pb-0.5 border-brand-lightborder">
                                <span class="text-[9px] sm:text-[10px] font-black uppercase text-brand-forest/70 block leading-tight">Khidmah</span>
                                <span class="text-sm sm:text-base font-black text-brand-deep block leading-tight mt-0.5">Umat</span>
                            </div>
                        </div>

                        <!-- Micro Bottom Bar -->
                        <div class="mt-3.5 sm:mt-4 flex flex-col xs:flex-row items-start xs:items-center justify-between gap-1.5 xs:gap-0 text-xs font-black text-brand-forest">
                            <span class="flex items-center gap-1.5">
                                <i class="bi bi-shield-check text-brand-matcha text-sm"></i>
                                <span>Tradisi Keilmuan Terjaga</span>
                            </span>
                            <span class="flex items-center gap-1.5">
                                <i class="bi bi-check-circle-fill text-brand-matcha text-sm"></i>
                                <span>Terverifikasi Resmi</span>
                            </span>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Organization Statistics Section (3 Pillars Beneath Hero) -->
            <div class="mt-14 grid grid-cols-1 md:grid-cols-3 gap-6" id="value-strip">
                <!-- 01: TAHUN BERDIRI -->
                <div class="stat-card-motion floating-frame-card bg-white p-5 sm:p-7 rounded-2xl border-2 border-brand-forest shadow-solid-md flex flex-col justify-between"
                     data-parallax="card">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-black uppercase tracking-wider text-brand-forest/70">Tahun Berdiri</span>
                            <div class="w-12 h-12 rounded-xl bg-brand-lightmatcha border-2 border-brand-forest shadow-solid-sm flex items-center justify-center text-brand-forest text-lg font-black shrink-0">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                        </div>
                        <div class="stat-year-val text-3xl sm:text-4xl lg:text-5xl font-black text-brand-deep tracking-tight leading-none mt-2">
                            {{ $tahunBerdiri ?? ($pengaturan->tahun_berdiri ?? '—') }}
                        </div>
                        <p class="text-xs font-semibold text-brand-darktext/70 mt-2">
                            Landasan khidmah jam'iyyah sejak deklarasi Nahdlatul Ulama.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t-2 border-brand-sand flex items-center justify-between text-xs font-black text-brand-matcha">
                        <span>Sejarah &amp; Landmark</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>

                <!-- 02: JUMLAH ANGGOTA -->
                <div class="stat-card-motion floating-frame-card bg-white p-5 sm:p-7 rounded-2xl border-2 border-brand-forest shadow-solid-md flex flex-col justify-between"
                     data-parallax="card">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-black uppercase tracking-wider text-brand-forest/70">Anggota</span>
                            <div class="w-12 h-12 rounded-xl bg-brand-cream border-2 border-brand-forest shadow-solid-sm flex items-center justify-center text-brand-forest text-lg font-black shrink-0">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                        <div class="stat-counter-val text-3xl sm:text-4xl lg:text-5xl font-black text-brand-deep tracking-tight leading-none mt-2" 
                             data-number-ticker="{{ $totalAnggota ?? ($totalAnggotaAktif ?? 0) }}"
                             data-count="{{ $totalAnggota ?? ($totalAnggotaAktif ?? 0) }}">
                            {{ number_format($totalAnggota ?? ($totalAnggotaAktif ?? 0)) }}
                        </div>
                        <p class="text-xs font-semibold text-brand-darktext/70 mt-2">
                            Warga terdata aktif dalam sistem administrasi keanggotaan KARTANU.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t-2 border-brand-sand flex items-center justify-between text-xs font-black text-brand-forest">
                        <span>Keanggotaan Terpadu</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>

                <!-- 03: JUMLAH ORGANISASI -->
                <div class="stat-card-motion floating-frame-card bg-white p-5 sm:p-7 rounded-2xl border-2 border-brand-forest shadow-solid-md flex flex-col justify-between"
                     data-parallax="card">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-black uppercase tracking-wider text-brand-forest/70">Organisasi</span>
                            <div class="w-12 h-12 rounded-xl bg-brand-lightmatcha border-2 border-brand-forest shadow-solid-sm flex items-center justify-center text-brand-forest text-lg font-black shrink-0">
                                <i class="bi bi-diagram-3"></i>
                            </div>
                        </div>
                        <div class="stat-counter-val text-3xl sm:text-4xl lg:text-5xl font-black text-brand-deep tracking-tight leading-none mt-2" 
                             data-number-ticker="{{ $totalOrganisasi ?? 0 }}"
                             data-count="{{ $totalOrganisasi ?? 0 }}">
                            {{ $totalOrganisasi ?? 0 }}
                        </div>
                        <p class="text-xs font-semibold text-brand-darktext/70 mt-2">
                            Badan otonom, lembaga, dan ranting aktif se-Kecamatan Wangon.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t-2 border-brand-sand flex items-center justify-between text-xs font-black text-brand-matcha">
                        <span>Sinergi Kelembagaan</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 02: TENTANG NU WANGON (HISTORICAL LANDMARK & SEJARAH)                      -->
    <!-- ========================================================================= -->
    <section class="py-16 sm:py-24 bg-brand-sand border-b-2 border-brand-forest relative overflow-hidden" id="tentang">
        <!-- Abstract Geometric Background (PixelOS Inspiration - Layer 1: Base Matrix & Large Auras 500-620px) -->
        <div class="absolute inset-0 pixel-dot-matrix opacity-50 pointer-events-none -z-0"></div>
        <div class="absolute top-1/4 -right-32 w-[620px] h-[620px] rounded-full bg-gradient-to-br from-brand-accent/[0.18] via-brand-lightmatcha/[0.30] to-transparent pointer-events-none blur-3xl -z-0" data-parallax="bg-slow"></div>
        <div class="absolute -bottom-24 -left-28 w-[520px] h-[520px] rounded-full bg-brand-matcha/[0.16] pointer-events-none blur-3xl -z-0" data-parallax="bg-slow"></div>

        <!-- Layer 2: Oversized Concentric Ring Geometry (580-640px with 6px Stroke) & Large 5px Squircle -->
        <div class="pointer-events-none absolute top-10 -right-44 w-[580px] h-[580px] lg:w-[640px] lg:h-[640px] -z-0" data-parallax="bg" data-parallax-rotate="-4">
            <div class="ambient-float-slow-3 w-full h-full flex items-center justify-center">
                <div class="w-full h-full rounded-full border-[6px] border-brand-forest/[0.28] flex items-center justify-center p-14 sm:p-20">
                    <div class="w-full h-full rounded-full border-4 border-dashed border-brand-matcha/[0.32] flex items-center justify-center p-12">
                        <div class="w-full h-full rounded-full border-[3px] border-brand-forest/[0.22] bg-gradient-to-tl from-brand-accent/[0.15] via-brand-lightmatcha/40 to-transparent"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pointer-events-none absolute bottom-12 -left-12 w-80 h-80 -z-0 hidden sm:block" data-parallax="mid" data-parallax-rotate="5">
            <div class="ambient-float-slow-1 w-full h-full rounded-[56px] border-[5px] border-brand-forest/[0.26] bg-brand-lightmatcha/40 backdrop-blur-xs rotate-6 p-7 flex flex-col justify-between shadow-sm">
                <div class="flex justify-between items-center text-brand-forest/[0.35] text-base font-mono font-bold leading-none">
                    <span>+</span>
                    <span class="w-5 h-5 rounded-full border-2 border-brand-forest/40 bg-brand-accent/45"></span>
                </div>
                <div class="text-right text-brand-forest/[0.35] text-base font-mono font-bold leading-none">+</div>
            </div>
        </div>

        <!-- Layer 3: Foreground Crosshair Accents & Grid Details -->
        <div class="pointer-events-none absolute top-16 left-1/3 text-brand-forest/[0.35] text-base font-mono font-bold select-none hidden lg:block -z-0" data-parallax="fore">+</div>
        <div class="pointer-events-none absolute bottom-20 right-1/4 text-brand-forest/[0.30] text-sm font-mono font-bold select-none hidden md:block -z-0" data-parallax="fore">+</div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                <!-- Left: Photo Hadratussyaikh KH Hasyim Asyari -->
                <div class="lg:col-span-5 floating-reveal" id="tentang-image">
                    <div class="bg-white p-3.5 sm:p-4.5 lg:p-5 rounded-3xl border-2 border-brand-forest shadow-solid-lg floating-frame-card" 
                         data-floating-depth="float"
                         data-parallax="card-large">
                        <div class="overflow-hidden rounded-2xl border-2 border-brand-forest aspect-[4/3] bg-stone-100 w-full">
                            <img alt="Hadratussyaikh KH. M. Hasyim Asy'ari" 
                                 class="w-full h-full object-cover filter contrast-[1.04] floating-frame-image" 
                                 src="{{ asset('images/landing/hasyim-asyari.jpg') }}">
                        </div>
                        <div class="pt-3.5 sm:pt-4 mt-3 sm:mt-3.5 lg:mt-2 lg:pt-4 border-t-2 border-brand-sand flex items-center justify-between gap-3 sm:gap-4">
                            <div class="min-w-0 flex-1">
                                <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-widest text-brand-matcha block leading-tight">Hadratussyaikh</span>
                                <h3 class="text-lg sm:text-xl font-black text-brand-deep mt-0.5 leading-snug">KH. M. Hasyim Asy'ari</h3>
                                <p class="text-xs sm:text-[13px] font-semibold text-brand-darktext/70 mt-0.5 leading-normal">Pendiri Nahdlatul Ulama — 1871–1947 M</p>
                            </div>
                            <div class="w-11 h-11 rounded-xl bg-brand-lightmatcha border-2 border-brand-forest shadow-solid-sm flex items-center justify-center text-brand-forest shrink-0">
                                <i class="bi bi-book-half text-base"></i>
                            </div>
                        </div>

                        <!-- Micro Spec Grid -->
                        <div class="mt-3.5 sm:mt-4 bg-brand-cream rounded-xl border-2 border-brand-forest p-2.5 sm:p-3.5 lg:p-3 grid grid-cols-3 gap-2 text-center text-xs font-bold text-brand-forest">
                            <div class="py-0.5">
                                <span class="block text-[9px] sm:text-[10px] font-black text-brand-forest/70 uppercase tracking-wider mb-0.5">Deklarasi</span>
                                <span class="text-xs sm:text-[13px] font-black text-brand-deep block leading-tight">16 Rajab 1344</span>
                            </div>
                            <div class="border-x-2 border-brand-lightborder py-0.5">
                                <span class="block text-[9px] sm:text-[10px] font-black text-brand-forest/70 uppercase tracking-wider mb-0.5">Tempat</span>
                                <span class="text-xs sm:text-[13px] font-black text-brand-deep block leading-tight">Surabaya</span>
                            </div>
                            <div class="py-0.5">
                                <span class="block text-[9px] sm:text-[10px] font-black text-brand-forest/70 uppercase tracking-wider mb-0.5">Khidmah</span>
                                <span class="text-xs sm:text-[13px] font-black text-brand-matcha block leading-tight">Aswaja</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Narrative & Landmark -->
                <div class="lg:col-span-7 space-y-6 floating-reveal reveal-delay-1" id="tentang-text">
                    <div class="space-y-4">
                        <div id="tentang-label" data-text-animate="blurInUp" class="inline-flex items-center gap-2 px-3 py-1 bg-white text-brand-forest border-2 border-brand-forest rounded-full text-xs font-black uppercase tracking-wider shadow-solid-sm">
                            <span class="w-2 h-2 rounded-full bg-brand-matcha"></span>
                            <span>TENTANG NAHDLATUL ULAMA</span>
                        </div>
                        <h2 id="tentang-heading" data-text-animate="blurInUp" class="text-3xl sm:text-4xl lg:text-[42px] font-black text-brand-deep tracking-tight leading-[1.14]">
                            Berakar dari tradisi pesantren, <br class="hidden sm:inline">
                            bergerak untuk kemaslahatan warga.
                        </h2>
                        <div id="tentang-body" class="text-brand-darktext/80 text-base leading-relaxed space-y-3 font-medium">
                            <p>
                                Nahdlatul Ulama didirikan pada <strong class="text-brand-deep font-black">16 Rajab 1344 H / 31 Januari 1926</strong> di Surabaya. Di Wangon, Banyumas, khidmah jam'iyyah NU bergerak konsisten menyentuh denyut nadi masyarakat.
                            </p>
                            <p>
                                Membina majelis keilmuan bersanad, merawat lembaga pendidikan Ma'arif, mengelola kepedulian sosial melalui Lazisnu, dan membina generasi muda berkarakter Ahlussunnah wal Jama'ah.
                            </p>
                        </div>
                    </div>

                    <!-- 4 Aswaja Principles Pills -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2">
                        <div class="bg-white p-3 rounded-xl border-2 border-brand-forest shadow-solid-sm text-center floating-frame-card">
                            <span class="block text-xs font-black text-brand-deep">Tawasuth</span>
                            <span class="text-[10px] font-bold text-brand-forest/70">Sikap Moderat</span>
                        </div>
                        <div class="bg-white p-3 rounded-xl border-2 border-brand-forest shadow-solid-sm text-center floating-frame-card">
                            <span class="block text-xs font-black text-brand-deep">Tawazun</span>
                            <span class="text-[10px] font-bold text-brand-forest/70">Seimbang</span>
                        </div>
                        <div class="bg-white p-3 rounded-xl border-2 border-brand-forest shadow-solid-sm text-center floating-frame-card">
                            <span class="block text-xs font-black text-brand-deep">I'tidal</span>
                            <span class="text-[10px] font-bold text-brand-forest/70">Tegak Lurus</span>
                        </div>
                        <div class="bg-white p-3 rounded-xl border-2 border-brand-forest shadow-solid-sm text-center floating-frame-card">
                            <span class="block text-xs font-black text-brand-deep">Tasamuh</span>
                            <span class="text-[10px] font-bold text-brand-forest/70">Toleran</span>
                        </div>
                    </div>

                    <div id="tentang-cta" class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-3.5">
                        <a href="#berita" 
                           class="w-full sm:w-auto px-6 py-3.5 bg-brand-forest hover:bg-brand-deep text-white font-black text-xs uppercase tracking-wider rounded-xl border-2 border-brand-deep shadow-solid-sm transition-all btn-solid-press flex items-center justify-center gap-2 text-decoration-none">
                            <span>Warta &amp; Khidmah Kami</span>
                            <span>&rarr;</span>
                        </a>
                        <a href="#keanggotaan" 
                           class="w-full sm:w-auto px-6 py-3.5 bg-white hover:bg-brand-cream text-brand-forest font-black text-xs uppercase tracking-wider rounded-xl border-2 border-brand-forest shadow-solid-sm transition-all btn-solid-press flex items-center justify-center text-decoration-none text-center">
                            <span>Gabung Keanggotaan</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 03: WARTA & BERITA (SOLID WEIGHT BENTO CARDS)                             -->
    <!-- ========================================================================= -->
    <section class="py-16 sm:py-24 bg-brand-cream border-b-2 border-brand-forest relative overflow-hidden" id="berita">
        <!-- Abstract Geometric Background (PixelOS Inspiration - Layer 1: Base Grid & Large Aura 600-640px) -->
        <div class="absolute top-0 right-0 w-[640px] h-[640px] pixel-grid-blueprint opacity-65 pointer-events-none -z-0"></div>
        <div class="absolute top-1/4 -left-36 w-[640px] h-[640px] rounded-full bg-gradient-to-tr from-brand-matcha/[0.20] via-brand-lightmatcha/[0.38] to-transparent pointer-events-none blur-3xl -z-0" data-parallax="bg-slow"></div>

        <!-- Layer 2: Oversized Concentric Ring Geometry (620-700px with 6px Stroke) & 4px Guidelines -->
        <div class="pointer-events-none absolute -top-20 -left-48 w-[620px] h-[620px] lg:w-[700px] lg:h-[700px] -z-0" data-parallax="bg-slow" data-parallax-rotate="-3">
            <div class="ambient-float-slow-2 w-full h-full flex items-center justify-center">
                <div class="w-full h-full rounded-full border-[6px] border-brand-forest/[0.28] flex items-center justify-center p-16 sm:p-20">
                    <div class="w-full h-full rounded-full border-4 border-dashed border-brand-matcha/[0.32] flex items-center justify-center p-14">
                        <div class="w-full h-full rounded-full border-[3px] border-brand-forest/[0.22] bg-gradient-to-br from-brand-lightmatcha/40 via-brand-matcha/[0.12] to-transparent"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pointer-events-none absolute top-16 right-10 h-[480px] w-px border-l-4 border-brand-forest/[0.25] hidden lg:flex flex-col justify-between items-center -z-0" data-parallax="mid">
            <span class="w-4 h-4 rounded-full bg-brand-matcha/45 -translate-x-1/2"></span>
            <span class="w-5 h-5 rounded-full border-2 border-brand-forest/40 bg-brand-cream -translate-x-1/2 flex items-center justify-center">
                <span class="w-2 h-2 rounded-full bg-brand-accent/50"></span>
            </span>
            <span class="w-4 h-4 rounded-full bg-brand-matcha/45 -translate-x-1/2"></span>
        </div>
        <div class="pointer-events-none absolute bottom-12 -right-12 w-80 h-24 -z-0 hidden md:block" data-parallax="mid" data-parallax-rotate="4">
            <div class="ambient-float-slow-3 w-full h-full rounded-full border-4 border-brand-forest/[0.26] bg-brand-lightmatcha/40 backdrop-blur-xs rotate-6 flex items-center px-8 justify-between shadow-sm">
                <span class="w-4 h-4 rounded-full bg-brand-forest/30"></span>
                <span class="h-1.5 w-24 rounded-full bg-brand-forest/25"></span>
                <span class="w-3.5 h-3.5 rounded-full border-2 border-brand-accent/50 bg-brand-accent/35"></span>
            </div>
        </div>

        <!-- Layer 3: Foreground Matrix Cluster & Crosshair Accents -->
        <div class="pointer-events-none absolute bottom-8 right-16 w-48 h-48 pixel-dot-matrix opacity-85 hidden md:block -z-0" data-parallax="fore"></div>
        <div class="pointer-events-none absolute top-12 left-1/4 text-brand-forest/[0.35] text-base font-mono font-bold select-none hidden lg:block -z-0" data-parallax="fore">+</div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 floating-reveal">
                <div class="max-w-2xl">
                    <span data-text-animate="blurInUp" class="px-3 py-1 bg-white text-brand-forest border-2 border-brand-forest rounded-full text-xs font-black uppercase tracking-wider shadow-solid-sm inline-block mb-3">
                        WARTA &amp; BERITA TERKINI
                    </span>
                    <h2 data-text-animate="blurInUp" class="text-3xl sm:text-4xl lg:text-[42px] font-black text-brand-deep tracking-tight leading-tight">
                        Kabar, Aspirasi, dan Syiar NU Wangon
                    </h2>
                    <p class="text-base font-semibold text-brand-darktext/80 mt-2">
                        Menyajikan warta terpercaya seputar kegiatan keumatan, khidmah jam'iyyah, dan kabar warga Nahdliyin se-Kecamatan Wangon.
                    </p>
                </div>
                <div class="shrink-0">
                    <a href="{{ route('berita.index') }}" 
                       class="px-6 py-3.5 bg-brand-forest hover:bg-brand-deep text-white font-black text-xs uppercase tracking-wider rounded-xl border-2 border-brand-deep shadow-solid-sm transition-all btn-solid-press inline-flex items-center gap-2 text-decoration-none">
                        <span>Jelajahi Semua Berita</span>
                        <span class="text-sm">&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Asymmetric Bento / Editorial News Grid -->
            @php
                $fallbackArticles = [
                    (object)[
                        'title' => "Penguatan Khidmah Jam'iyyah dan Sinergi Banom MWC NU Wangon",
                        'slug' => 'penguatan-khidmah-jamiyyah-dan-sinergi-banom-mwc-nu-wangon',
                        'excerpt' => "Konsolidasi berkala pengurus ranting dan badan otonom dalam meningkatkan pelayanan keagamaan dan kemandirian ekonomi warga Nahdliyin.",
                        'kategori' => 'utama',
                        'image' => 'images/landing/gallery-1.jpg',
                        'published_at' => '2026-09-19',
                    ],
                    (object)[
                        'title' => "Konsolidasi Pengurus Ranting Se-Kecamatan Wangon",
                        'slug' => 'konsolidasi-pengurus-ranting-se-kecamatan-wangon',
                        'excerpt' => "Pertemuan rutin pengurus ranting NU guna memperkuat koordinasi program kerja keagamaan dan kemasyarakatan.",
                        'kategori' => 'kabar',
                        'image' => 'images/landing/gallery-3.jpg',
                        'published_at' => '2026-09-15',
                    ],
                    (object)[
                        'title' => "Penguatan Kurikulum Aswaja LP Ma'arif NU Wangon",
                        'slug' => 'penguatan-kurikulum-aswaja-lp-maarif-nu-wangon',
                        'excerpt' => "Sinergi satuan pendidikan di bawah naungan LP Ma'arif NU Wangon dalam membentuk generasi berakhlakul karimah.",
                        'kategori' => 'pendidikan',
                        'image' => 'images/landing/gallery-2.jpg',
                        'published_at' => '2026-09-10',
                    ],
                    (object)[
                        'title' => "Penyaluran Santunan Dhuafa dan Tanggap Sosial LAZISNU Wangon",
                        'slug' => 'penyaluran-santunan-dhuafa-dan-tanggap-sosial-lazisnu-wangon',
                        'excerpt' => "Aksi filantropi berkelanjutan menyalurkan bantuan pangan dan penanganan kesehatan bagi mustahik di pelosok ranting.",
                        'kategori' => 'sosial',
                        'image' => 'images/landing/gallery-1.jpg',
                        'published_at' => '2026-09-08',
                    ],
                    (object)[
                        'title' => "Pelatihan Kader Penggerak Nahdlatul Ulama Angkatan VII",
                        'slug' => 'pelatihan-kader-penggerak-nahdlatul-ulama-angkatan-vii',
                        'excerpt' => "Mencetak kader militan yang berpegang teguh pada manhaj ahlussunnah wal jamaah an-nahdliyyah.",
                        'kategori' => 'kaderisasi',
                        'image' => 'images/landing/gallery-3.jpg',
                        'published_at' => '2026-09-05',
                    ],
                    (object)[
                        'title' => "Apel Siaga Banser dan Pengamanan Khidmat Jam'iyyah Wangon",
                        'slug' => 'apel-siaga-banser-dan-pengamanan-khidmat-jamiyyah-wangon',
                        'excerpt' => "Kesiapsiagaan jajaran Satkoryon Banser Wangon dalam mengawal ulama, menjaga kamtibmas, dan melayani keumatan.",
                        'kategori' => 'ansor',
                        'image' => 'images/landing/gallery-2.jpg',
                        'published_at' => '2026-09-02',
                    ],
                ];
            @endphp

            <div class="grid grid-cols-2 lg:grid-cols-12 gap-x-4 gap-y-7 sm:gap-x-6 sm:gap-y-8 lg:gap-x-7 lg:gap-y-10 responsive-news-grid">
                @if(isset($newsList) && $newsList->count() > 0)
                    @php $totalNews = $newsList->count(); @endphp
                    @foreach($newsList->take(6) as $newsItem)
                        @include('berita.partials.bento-card', [
                            'newsItem' => $newsItem, 
                            'index' => $loop->index, 
                            'totalNews' => $totalNews, 
                            'isFallback' => false
                        ])
                    @endforeach
                @else
                    @php $totalNews = count($fallbackArticles); @endphp
                    @foreach($fallbackArticles as $newsItem)
                        @include('berita.partials.bento-card', [
                            'newsItem' => $newsItem, 
                            'index' => $loop->index, 
                            'totalNews' => $totalNews, 
                            'isFallback' => true
                        ])
                    @endforeach
                @endif
            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 04: SECTION KEGIATAN (AGENDA DAN KEGIATAN)                                -->
    <!-- ========================================================================= -->
    <section class="py-16 sm:py-24 bg-brand-sand border-b-2 border-brand-forest relative overflow-hidden" id="kegiatan">
        <!-- Abstract Geometric Background (PixelOS Inspiration - Layer 1: Base Grid & Large Auras 540-680px) -->
        <div class="absolute inset-0 pixel-grid-blueprint opacity-60 pointer-events-none -z-0"></div>
        <div class="absolute -top-24 right-1/4 w-[680px] h-[680px] rounded-full bg-emerald-900/[0.14] pointer-events-none blur-3xl -z-0" data-parallax="bg-slow"></div>
        <div class="absolute -bottom-24 -left-20 w-[540px] h-[540px] rounded-full bg-brand-lightmatcha/40 pointer-events-none blur-3xl -z-0" data-parallax="bg-slow"></div>

        <!-- Layer 2: Oversized Angled Capsule (460px with 5px Stroke & Translucent Fill) & Concentric Ring Geometry (580-660px with 6px Stroke) -->
        <div class="pointer-events-none absolute top-8 -right-20 w-[460px] h-32 -z-0 hidden md:block" data-parallax="mid" data-parallax-rotate="5">
            <div class="ambient-float-slow-3 w-full h-full rounded-full border-[5px] border-brand-forest/[0.28] bg-gradient-to-r from-white/50 via-brand-lightmatcha/40 to-white/20 backdrop-blur-xs rotate-12 flex items-center px-10 justify-between shadow-sm">
                <span class="w-4 h-4 rounded-full bg-brand-forest/35"></span>
                <span class="h-1 w-32 bg-brand-forest/25 rounded-full"></span>
                <span class="w-4 h-4 rounded-full border-[3px] border-brand-forest/40 bg-brand-accent/40"></span>
            </div>
        </div>
        <div class="pointer-events-none absolute bottom-4 -left-36 w-[580px] h-[580px] lg:w-[660px] lg:h-[660px] -z-0" data-parallax="bg-slow" data-parallax-rotate="-3">
            <div class="ambient-float-slow-1 w-full h-full rounded-full border-[6px] border-brand-forest/[0.28] p-16 sm:p-20 flex items-center justify-center">
                <div class="w-full h-full rounded-full border-4 border-dashed border-brand-matcha/[0.32] flex items-center justify-center p-14">
                    <div class="w-full h-full rounded-full border-[3px] border-brand-forest/[0.22] bg-gradient-to-tr from-brand-matcha/[0.14] via-brand-lightmatcha/30 to-transparent"></div>
                </div>
            </div>
        </div>

        <!-- Layer 3: Foreground Crosshair Accents & Grid Details -->
        <div class="pointer-events-none absolute bottom-12 right-1/3 text-brand-forest/[0.35] text-base font-mono font-bold select-none hidden lg:block -z-0" data-parallax="fore">+</div>
        <div class="pointer-events-none absolute top-16 left-1/4 text-brand-forest/[0.30] text-sm font-mono font-bold select-none hidden md:block -z-0" data-parallax="fore">+</div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10 floating-reveal">
                <div class="max-w-2xl">
                    <span data-text-animate="blurInUp" class="px-3 py-1 bg-white text-brand-forest border-2 border-brand-forest rounded-full text-xs font-black uppercase tracking-wider shadow-solid-sm inline-block mb-3">
                        AGENDA DAN KEGIATAN
                    </span>
                    <h2 data-text-animate="blurInUp" class="text-3xl sm:text-4xl lg:text-[42px] font-black text-brand-deep tracking-tight leading-tight">
                        Agenda dan Kegiatan
                    </h2>
                    <p class="text-base font-semibold text-brand-darktext/80 mt-2">
                        Informasi jadwal pertemuan, pengajian, dan rangkaian agenda kegiatan di lingkungan organisasi.
                    </p>
                </div>
                <div class="shrink-0">
                    <a href="{{ route('kegiatan.index') }}" 
                       class="px-6 py-3.5 bg-brand-forest hover:bg-brand-deep text-white font-black text-xs uppercase tracking-wider rounded-xl border-2 border-brand-deep shadow-solid-sm transition-all btn-solid-press inline-flex items-center gap-2 text-decoration-none">
                        <span>Jelajahi Agenda</span>
                        <span class="text-sm">&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Spotlight Agenda Terdekat & Realtime Countdown Widget (Jika Ada Kegiatan Mendatang / Relevan) -->
            @if($kegiatanTerdekat)
                @php
                    $tz = config('app.timezone', 'Asia/Jakarta');
                    $eventDateStr = $kegiatanTerdekat->event_date instanceof \Carbon\Carbon 
                        ? $kegiatanTerdekat->event_date->format('Y-m-d') 
                        : (string) $kegiatanTerdekat->event_date;
                    $startTimeStr = $kegiatanTerdekat->start_time ?: '00:00:00';
                    $endTimeStr = $kegiatanTerdekat->end_time ?: '23:59:59';
                    
                    $startCarbon = \Carbon\Carbon::parse($eventDateStr . ' ' . $startTimeStr, $tz);
                    $endCarbon = $kegiatanTerdekat->end_time 
                        ? \Carbon\Carbon::parse($eventDateStr . ' ' . $endTimeStr, $tz)
                        : $startCarbon->copy()->addHours(2);
                        
                    $startTimestampMs = $startCarbon->getTimestamp() * 1000;
                    $endTimestampMs = $endCarbon->getTimestamp() * 1000;
                    $nowTimestampMs = now($tz)->getTimestamp() * 1000;
                    $isOngoingInitial = ($nowTimestampMs >= $startTimestampMs && $nowTimestampMs < $endTimestampMs);
                    $isFinishedInitial = ($nowTimestampMs >= $endTimestampMs);
                @endphp

                <div class="mb-12 bg-brand-deep text-brand-lightmatcha rounded-3xl border-2 border-brand-forest shadow-solid-lg p-6 sm:p-8 relative overflow-hidden floating-reveal floating-frame-card floating-ambient-float" 
                     id="countdown-card"
                     data-floating-depth="float"
                     data-start="{{ $startTimestampMs }}"
                     data-end="{{ $endTimestampMs }}">
                    
                    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                        <!-- Left Info -->
                        <div class="space-y-4 max-w-xl">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-brand-matcha text-white text-[10px] font-black uppercase tracking-wider border border-white/20 shadow-sm">
                                    <i class="bi bi-clock-history text-amber-300"></i>
                                    <span>Agenda Terdekat</span>
                                </span>
                                <span class="px-2.5 py-1 rounded-lg bg-white/10 text-white text-[10px] font-bold uppercase tracking-wider border border-white/10">
                                    {{ strtoupper($kegiatanTerdekat->kategori ?? 'UMUM') }}
                                </span>
                                <span id="countdownStatusBadge" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $isOngoingInitial ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40' : ($isFinishedInitial ? 'bg-stone-500/20 text-stone-300 border border-stone-500/40' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40') }}">
                                    <span class="w-2 h-2 rounded-full {{ $isOngoingInitial ? 'bg-amber-400 animate-ping' : ($isFinishedInitial ? 'bg-stone-400' : 'bg-emerald-400 animate-pulse') }}"></span>
                                    <span id="countdownStatusBadgeText">
                                        {{ $isOngoingInitial ? 'Sedang Berlangsung' : ($isFinishedInitial ? 'Selesai' : 'Mendatang') }}
                                    </span>
                                </span>
                            </div>

                            <h3 class="text-2xl sm:text-3xl font-black text-white leading-tight">
                                <a href="{{ route('kegiatan.show', $kegiatanTerdekat->id) }}" class="text-white hover:text-emerald-300 transition-colors text-decoration-none">
                                    {{ $kegiatanTerdekat->title }}
                                </a>
                            </h3>

                            <div class="flex items-center gap-4 text-xs font-semibold text-brand-lightmatcha/80 flex-wrap">
                                <div class="flex items-center gap-1.5 bg-white/5 px-2.5 py-1 rounded-lg border border-white/10">
                                    <i class="bi bi-calendar-event text-emerald-400"></i>
                                    <span>{{ $startCarbon->translatedFormat('l, d F Y') }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 bg-white/5 px-2.5 py-1 rounded-lg border border-white/10">
                                    <i class="bi bi-clock text-emerald-400"></i>
                                    <span>{{ substr($startTimeStr, 0, 5) }} WIB {{ $kegiatanTerdekat->end_time ? '- ' . substr($endTimeStr, 0, 5) . ' WIB' : '' }}</span>
                                </div>
                                @if($kegiatanTerdekat->location)
                                    <div class="flex items-center gap-1.5 bg-white/5 px-2.5 py-1 rounded-lg border border-white/10">
                                        <i class="bi bi-geo-alt text-emerald-400"></i>
                                        <span class="truncate max-w-[220px]">{{ $kegiatanTerdekat->location }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Right: Countdown Display Card -->
                        <div class="bg-brand-forest/90 rounded-2xl p-3.5 sm:p-5 border-2 border-brand-matcha shadow-[5px_5px_0px_#000] shrink-0 self-start lg:self-auto w-full sm:w-auto min-w-0 sm:min-w-[340px]">
                            <div class="text-[10px] font-black uppercase tracking-[0.2em] text-white/70 mb-3 flex items-center justify-between">
                                <span id="countdownHeadlineLabel">
                                    <i class="bi bi-stopwatch text-emerald-400 me-1"></i> Hitung Mundur Acara
                                </span>
                                <span id="countdownSummaryText" class="text-emerald-300 font-bold lowercase"></span>
                            </div>

                            <!-- Digits Grid with Solid Tactile Blocks -->
                            <div id="countdownDigitsContainer" class="{{ ($isOngoingInitial || $isFinishedInitial) ? 'hidden' : 'flex' }} items-center justify-center gap-1.5 sm:gap-3 text-center pt-1">
                                <div class="bg-brand-deep rounded-xl px-1.5 sm:px-3 py-2 sm:py-2.5 min-w-[46px] sm:min-w-[60px] border-2 border-brand-matcha shadow-sm">
                                    <span id="cdDays" class="text-xl sm:text-3xl font-black text-white block leading-none">0</span>
                                    <span class="text-[8px] sm:text-[9px] uppercase font-black tracking-wider text-white/60 block mt-1">Hari</span>
                                </div>
                                <span class="text-base sm:text-xl text-emerald-400 font-black">:</span>
                                <div class="bg-brand-deep rounded-xl px-1.5 sm:px-3 py-2 sm:py-2.5 min-w-[46px] sm:min-w-[60px] border-2 border-brand-matcha shadow-sm">
                                    <span id="cdHours" class="text-xl sm:text-3xl font-black text-white block leading-none">00</span>
                                    <span class="text-[8px] sm:text-[9px] uppercase font-black tracking-wider text-white/60 block mt-1">Jam</span>
                                </div>
                                <span class="text-base sm:text-xl text-emerald-400 font-black">:</span>
                                <div class="bg-brand-deep rounded-xl px-1.5 sm:px-3 py-2 sm:py-2.5 min-w-[46px] sm:min-w-[60px] border-2 border-brand-matcha shadow-sm">
                                    <span id="cdMinutes" class="text-xl sm:text-3xl font-black text-white block leading-none">00</span>
                                    <span class="text-[8px] sm:text-[9px] uppercase font-black tracking-wider text-white/60 block mt-1">Menit</span>
                                </div>
                                <span class="text-base sm:text-xl text-emerald-400 font-black">:</span>
                                <div class="bg-brand-deep rounded-xl px-1.5 sm:px-3 py-2 sm:py-2.5 min-w-[46px] sm:min-w-[60px] border-2 border-brand-matcha shadow-sm">
                                    <span id="cdSeconds" class="text-xl sm:text-3xl font-black text-white block leading-none">00</span>
                                    <span class="text-[8px] sm:text-[9px] uppercase font-black tracking-wider text-white/60 block mt-1">Detik</span>
                                </div>
                            </div>

                            <!-- Ongoing State Message -->
                            <div id="countdownOngoingMessage" class="{{ $isOngoingInitial ? '' : 'hidden' }} text-center py-2 space-y-1">
                                <div class="inline-flex items-center gap-2 text-amber-300 font-black text-sm">
                                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-ping"></span>
                                    <span>Acara Sedang Berlangsung</span>
                                </div>
                                <p class="text-xs font-semibold text-white/80">Kegiatan telah dimulai dan sedang berjalan saat ini.</p>
                            </div>

                            <!-- Finished State Message -->
                            <div id="countdownFinishedMessage" class="{{ $isFinishedInitial ? '' : 'hidden' }} text-center py-2 space-y-1">
                                <div class="inline-flex items-center gap-1.5 text-stone-300 font-black text-sm">
                                    <i class="bi bi-check-circle-fill text-stone-400"></i>
                                    <span>Acara Telah Selesai</span>
                                </div>
                                <p class="text-xs font-semibold text-white/70">Kegiatan telah selesai dilaksanakan.</p>
                            </div>

                            <div class="mt-4 pt-3 border-t border-white/20 flex items-center justify-between">
                                <a href="{{ route('kegiatan.show', $kegiatanTerdekat->id) }}" class="text-xs text-emerald-300 hover:text-white font-black transition-colors flex items-center gap-1 text-decoration-none">
                                    <span>Lihat Rincian Kegiatan</span>
                                    <span>&rarr;</span>
                                </a>
                                <span class="text-[10px] font-bold text-white/50">WIB (UTC+7)</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Kegiatan Cards Grid (3 Columns) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @if(isset($kegiatanList) && $kegiatanList->count() > 0)
                    @foreach($kegiatanList as $kegiatan)
                        @php
                            $eventCarbon = \Carbon\Carbon::parse($kegiatan->event_date);
                        @endphp
                        <div class="bg-white rounded-2xl border-2 border-brand-forest shadow-solid-md overflow-hidden flex flex-col justify-between group floating-frame-card floating-reveal reveal-delay-{{ $loop->iteration }}">
                            <div>
                                <!-- Image & Date Badge Container -->
                                <div class="relative aspect-[16/10] overflow-hidden bg-stone-100 border-b-2 border-brand-forest">
                                    @if($kegiatan->image)
                                        <img src="{{ asset('storage/' . $kegiatan->image) }}" 
                                             alt="{{ $kegiatan->title }}" 
                                             class="w-full h-full object-cover floating-frame-image group-hover:scale-104 transition-transform duration-700 ease-out">
                                    @else
                                        <img src="{{ asset('images/landing/gallery-2.jpg') }}" 
                                             alt="{{ $kegiatan->title }}" 
                                             class="w-full h-full object-cover floating-frame-image group-hover:scale-104 transition-transform duration-700 ease-out">
                                    @endif

                                    <!-- Date Pill in Corner -->
                                    <div class="absolute top-3 left-3 bg-white border-2 border-brand-forest rounded-xl px-3 py-1.5 text-center shadow-solid-sm">
                                        <span class="block text-base font-black text-brand-forest leading-none">
                                            {{ $eventCarbon->format('d') }}
                                        </span>
                                        <span class="block text-[9px] font-black uppercase tracking-wider text-brand-forest/70 mt-0.5">
                                            {{ $eventCarbon->translatedFormat('M') }}
                                        </span>
                                    </div>

                                    <!-- Banom Badge -->
                                    <div class="absolute top-3 right-3">
                                        <span class="px-2.5 py-1 rounded-lg bg-brand-forest text-white text-[10px] font-black uppercase tracking-wider border-2 border-brand-deep">
                                            {{ strtoupper($kegiatan->kategori ?? 'UMUM') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-4.5 sm:p-5 md:p-6">
                                    <h3 class="text-xl font-black text-brand-deep leading-snug group-hover:text-brand-matcha transition-colors mb-2.5">
                                        <a href="{{ route('kegiatan.show', $kegiatan->id) }}" class="text-inherit text-decoration-none">
                                            {{ $kegiatan->title }}
                                        </a>
                                    </h3>
                                    <p class="text-xs font-semibold text-brand-darktext/80 leading-relaxed line-clamp-2 mb-4">
                                        {{ Str::limit(strip_tags($kegiatan->description), 110) }}
                                    </p>

                                    <!-- Metadata: Waktu & Lokasi -->
                                    <div class="space-y-2 text-xs font-bold text-brand-forest/90 pt-3 border-t-2 border-brand-sand">
                                        <div class="flex items-center gap-2">
                                            <i class="bi bi-clock text-brand-matcha text-sm"></i>
                                            <span>{{ substr($kegiatan->start_time, 0, 5) }} WIB {{ $kegiatan->end_time ? '- ' . substr($kegiatan->end_time, 0, 5) . ' WIB' : '- Selesai' }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i class="bi bi-geo-alt text-brand-matcha text-sm"></i>
                                            <span class="truncate">{{ $kegiatan->location }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Card Action -->
                            <div class="px-4.5 sm:px-5 md:px-6 py-3.5 md:py-4 border-t-2 border-brand-sand bg-brand-cream/50 flex items-center justify-between mt-auto">
                                <a href="{{ route('kegiatan.show', $kegiatan->id) }}" class="text-xs font-black uppercase tracking-wider text-brand-forest hover:text-brand-matcha text-decoration-none min-h-[44px] flex items-center">
                                    Detail Agenda &rarr;
                                </a>
                                <a href="{{ route('kegiatan.show', $kegiatan->id) }}" 
                                   class="w-9 h-9 md:w-8 md:h-8 rounded-lg bg-brand-lightmatcha border-2 border-brand-forest text-brand-forest shadow-solid-sm flex items-center justify-center text-xs font-black btn-solid-press"
                                   aria-label="Lihat Rincian Agenda">
                                    &nearr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Clean Real Empty State (No Dummy Data) -->
                    <div class="col-span-full py-14 px-6 bg-white rounded-2xl border-2 border-dashed border-brand-forest text-center flex flex-col items-center justify-center space-y-4 shadow-solid-md">
                        <div class="w-16 h-16 rounded-2xl bg-brand-lightmatcha border-2 border-brand-forest text-brand-forest flex items-center justify-center text-2xl font-black shadow-solid-sm">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <h4 class="text-xl sm:text-2xl font-black text-brand-deep">Belum Ada Agenda Mendatang</h4>
                        <p class="text-xs sm:text-sm font-semibold text-brand-darktext/70 max-w-md leading-relaxed">
                            Saat ini belum ada jadwal agenda atau kegiatan terdekat yang dipublikasikan. Silakan pantau kembali halaman ini secara berkala atau lihat dokumentasi kegiatan di galeri.
                        </p>
                        <div class="pt-2">
                            <a href="{{ route('kegiatan.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-brand-forest text-white font-black text-xs uppercase tracking-wider border-2 border-brand-deep shadow-solid-sm btn-solid-press text-decoration-none">
                                <i class="bi bi-calendar3"></i>
                                <span>Lihat Semua Kegiatan</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 05: DOKUMENTASI & GALERI (TERLETAK DI BAWAH KEGIATAN)                     -->
    <!-- ========================================================================= -->
    <section class="py-16 sm:py-24 bg-brand-cream border-b-2 border-brand-forest relative overflow-hidden" id="galeri">
        <!-- Abstract Geometric Background (PixelOS Inspiration - Layer 1: Base Matrix & Large Auras 500-680px) -->
        <div class="absolute top-0 left-0 w-[520px] h-[520px] pixel-dot-matrix-gold opacity-65 pointer-events-none hidden md:block -z-0"></div>
        <div class="absolute top-1/3 -right-36 w-[680px] h-[680px] rounded-full bg-gradient-to-bl from-brand-matcha/[0.18] via-brand-lightmatcha/[0.34] to-transparent pointer-events-none blur-3xl -z-0" data-parallax="bg-slow"></div>
        <div class="absolute -bottom-20 left-1/4 w-[500px] h-[500px] rounded-full bg-brand-sand/70 pointer-events-none blur-2xl -z-0" data-parallax="bg-slow"></div>

        <!-- Layer 2: Oversized Geometric Ring (600-680px with 6px Stroke) & Translucent Squircle (360px with 5px Stroke) Behind Bento -->
        <div class="pointer-events-none absolute -top-24 -left-40 w-[600px] h-[600px] lg:w-[680px] lg:h-[680px] -z-0" data-parallax="bg-slow" data-parallax-rotate="3">
            <div class="ambient-float-slow-2 w-full h-full flex items-center justify-center">
                <div class="w-full h-full rounded-full border-[6px] border-brand-forest/[0.28] p-16 sm:p-20 flex items-center justify-center">
                    <div class="w-full h-full rounded-full border-4 border-dashed border-brand-matcha/[0.32] flex items-center justify-center p-14">
                        <div class="w-full h-full rounded-full border-[3px] border-brand-forest/[0.22] bg-gradient-to-br from-brand-lightmatcha/35 via-brand-matcha/[0.12] to-transparent"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pointer-events-none absolute bottom-8 -right-12 w-[360px] h-[360px] -z-0 hidden sm:block" data-parallax="mid" data-parallax-rotate="-4">
            <div class="ambient-float-slow-1 w-full h-full rounded-[56px] border-[5px] border-brand-forest/[0.26] bg-gradient-to-tl from-white/50 via-brand-lightmatcha/40 to-white/20 backdrop-blur-xs -rotate-6 p-8 flex flex-col justify-between shadow-sm">
                <span class="text-brand-forest/[0.35] text-base font-mono font-bold leading-none">+</span>
                <div class="flex justify-between items-center">
                    <span class="w-4 h-4 rounded-full border-2 border-brand-forest/40 bg-white"></span>
                    <span class="w-5 h-5 rounded-full border-2 border-brand-forest/35 bg-brand-matcha/40"></span>
                </div>
            </div>
        </div>

        <!-- Layer 3: Foreground Details & Crosshair Accents -->
        <div class="pointer-events-none absolute top-10 right-1/4 text-brand-forest/[0.35] text-base font-mono font-bold select-none hidden lg:block -z-0" data-parallax="fore">+</div>
        <div class="pointer-events-none absolute bottom-16 left-1/3 text-brand-forest/[0.30] text-sm font-mono font-bold select-none hidden md:block -z-0" data-parallax="fore">+</div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 floating-reveal">
                <div>
                    <span data-text-animate="blurInUp" class="px-3 py-1 bg-white text-brand-forest border-2 border-brand-forest rounded-full text-xs font-black uppercase tracking-wider shadow-solid-sm inline-block mb-3">
                        GALERI DOKUMENTASI
                    </span>
                    <h2 data-text-animate="blurInUp" class="text-3xl sm:text-4xl lg:text-[42px] font-black text-brand-deep tracking-tight leading-tight">
                        Dokumentasi Khidmah &amp; Aksi Nyata
                    </h2>
                    <p class="text-base font-semibold text-brand-darktext/80 mt-2">
                        Rekam jejak kebersamaan warga Nahdliyin dalam kegiatan keagamaan, sosial, dan kemasyarakatan.
                    </p>
                </div>
                <div>
                    <a href="{{ route('galeri.index') }}" class="px-6 py-3.5 bg-brand-forest hover:bg-brand-deep text-white font-black text-xs uppercase tracking-wider rounded-xl border-2 border-brand-deep shadow-solid-sm transition-all btn-solid-press inline-flex items-center gap-2 text-decoration-none">
                        <span>Semua Galeri</span>
                        <span class="text-sm">&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Dynamic Editorial Bento Grid Cards from Database -->
            @if(isset($galeriList) && $galeriList->isNotEmpty())
                @php
                    $totalHomeGaleri = $galeriList->count();
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-6">
                    @foreach ($galeriList as $index => $item)
                        @php
                            $coverPhoto = $item->cover_photo;
                            $fotoCount = $item->foto_count;
                            $videoCount = $item->video_count;
                            $hasVideo = $videoCount > 0;
                            $i = $index + 1;

                            if ($totalHomeGaleri === 1) {
                                $bentoCol = 'col-span-1 md:col-span-2 lg:col-span-12';
                                $isBanner = true;
                                $imgHeight = 'h-72 sm:h-80 lg:h-96';
                                $isHero = true;
                            } elseif ($totalHomeGaleri === 2) {
                                $bentoCol = ($i === 1) ? 'col-span-1 md:col-span-1 lg:col-span-7' : 'col-span-1 md:col-span-1 lg:col-span-5';
                                $isBanner = false;
                                $imgHeight = 'h-64 sm:h-72 lg:h-80';
                                $isHero = ($i === 1);
                            } else {
                                // 3 items (classic Bento trio)
                                if ($i === 1) {
                                    $bentoCol = 'col-span-1 md:col-span-1 lg:col-span-7';
                                    $isBanner = false;
                                    $imgHeight = 'h-64 sm:h-72 lg:h-80';
                                    $isHero = true;
                                } elseif ($i === 2) {
                                    $bentoCol = 'col-span-1 md:col-span-1 lg:col-span-5';
                                    $isBanner = false;
                                    $imgHeight = 'h-64 sm:h-72 lg:h-80';
                                    $isHero = false;
                                } else {
                                    $bentoCol = 'col-span-1 md:col-span-2 lg:col-span-12';
                                    $isBanner = true;
                                    $imgHeight = 'h-64 sm:h-80 lg:h-full';
                                    $isHero = true;
                                }
                            }
                        @endphp

                        @if ($isBanner)
                            {{-- BENTO PANORAMIC BANNER CARD (SPAN 12) --}}
                            <div class="{{ $bentoCol }} bg-white rounded-3xl border-2 border-brand-forest shadow-solid-md hover:shadow-solid-lg transition-all duration-500 transform-gpu hover:-translate-y-1 group relative overflow-hidden flex flex-col lg:flex-row justify-between floating-reveal reveal-delay-{{ $loop->iteration }}"
                                 data-parallax="card-large">
                                <!-- Media Area -->
                                <div class="relative lg:w-7/12 {{ $imgHeight }} min-h-[260px] lg:min-h-[320px] overflow-hidden bg-brand-sand border-b-2 lg:border-b-0 lg:border-r-2 border-brand-forest shrink-0">
                                    <a href="{{ route('galeri.show', $item->id) }}" class="block w-full h-full relative" aria-label="{{ $item->title }}">
                                        @if ($coverPhoto)
                                            <img src="{{ asset('storage/' . $coverPhoto) }}" 
                                                 alt="{{ $item->title }}" 
                                                 class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105 transform-gpu"
                                                 loading="lazy">
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center text-brand-forest/40 bg-brand-sand">
                                                <i class="bi bi-images text-5xl mb-2"></i>
                                                <span class="text-xs font-black uppercase tracking-wider">Dokumentasi Media</span>
                                            </div>
                                        @endif

                                        <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/10 to-transparent pointer-events-none transition-opacity duration-300 group-hover:opacity-40"></div>
                                    </a>

                                    <!-- Category Pill -->
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

                                    @if ($videoCount > 0)
                                        <div class="absolute inset-0 flex items-center justify-center bg-black/15 group-hover:bg-black/5 transition-colors pointer-events-none z-10">
                                            <div class="w-12 h-12 rounded-2xl bg-white/95 text-brand-forest border-2 border-brand-forest flex items-center justify-center shadow-solid-sm group-hover:scale-110 group-hover:bg-brand-forest group-hover:text-white transition-all duration-300">
                                                <i class="bi bi-play-fill text-2xl ml-0.5"></i>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Content Area -->
                                <div class="p-5 sm:p-6 lg:p-7 flex flex-col justify-between flex-grow bg-white">
                                    <div class="space-y-3 transform-gpu transition-all duration-300 lg:group-hover:-translate-y-1">
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

                                        <h3 class="text-xl sm:text-2xl font-black text-brand-deep leading-snug break-words group-hover:text-brand-matcha transition-colors">
                                            <a href="{{ route('galeri.show', $item->id) }}" class="text-inherit text-decoration-none">
                                                {{ $item->title }}
                                            </a>
                                        </h3>

                                        @if ($item->description)
                                            <p class="text-xs sm:text-sm font-medium text-brand-darktext/75 line-clamp-3 leading-relaxed m-0">
                                                {{ Str::limit(strip_tags($item->description), 160) }}
                                            </p>
                                        @endif
                                    </div>

                                    <div class="pt-4 mt-5 border-t-2 border-brand-sand flex items-center justify-between">
                                        <span class="text-[11px] font-black uppercase tracking-wider text-brand-forest/70">
                                            TERLAKSANA &bull; {{ $item->event_date ? \Carbon\Carbon::parse($item->event_date)->format('Y') : '2026' }}
                                        </span>
                                        <a href="{{ route('galeri.show', $item->id) }}" 
                                            class="inline-flex items-center gap-1.5 px-4.5 py-2.5 min-h-[44px] lg:min-h-0 rounded-xl bg-brand-sand group-hover:bg-brand-forest group-hover:text-white text-brand-forest font-black text-xs uppercase tracking-wider border-2 border-brand-forest shadow-solid-sm transition-all duration-300 btn-solid-press text-decoration-none">
                                            <span>Buka Dokumentasi</span>
                                            <span class="text-sm leading-none transition-transform duration-300 group-hover:translate-x-1">&rarr;</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- BENTO STANDARD / HERO VERTICAL CARD --}}
                            <div class="{{ $bentoCol }} bg-white rounded-3xl border-2 border-brand-forest shadow-solid-md hover:shadow-solid-lg transition-all duration-500 transform-gpu hover:-translate-y-1 group relative overflow-hidden flex flex-col justify-between floating-reveal reveal-delay-{{ $loop->iteration }}"
                                 data-parallax="card">
                                <div class="space-y-0 min-w-0 flex flex-col">
                                    <div class="relative {{ $imgHeight }} overflow-hidden bg-brand-sand border-b-2 border-brand-forest">
                                        <a href="{{ route('galeri.show', $item->id) }}" class="block w-full h-full relative" aria-label="{{ $item->title }}">
                                            @if ($coverPhoto)
                                                <img src="{{ asset('storage/' . $coverPhoto) }}" 
                                                     alt="{{ $item->title }}" 
                                                     class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105 transform-gpu"
                                                     loading="lazy">
                                            @else
                                                <div class="w-full h-full flex flex-col items-center justify-center text-brand-forest/40 bg-brand-sand">
                                                    <i class="bi bi-images text-4xl mb-1"></i>
                                                    <span class="text-[11px] font-black uppercase tracking-wider">Dokumentasi Media</span>
                                                </div>
                                            @endif

                                            <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/10 to-transparent pointer-events-none transition-opacity duration-300 group-hover:opacity-40"></div>
                                        </a>

                                        <span class="absolute top-3 left-3 bg-white/95 backdrop-blur-sm text-brand-forest border-2 border-brand-forest text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-solid-sm flex items-center gap-1.5 z-10 pointer-events-none">
                                            <span class="w-1.5 h-1.5 rounded-full bg-brand-forest"></span>
                                            <span>{{ \App\Support\Organisasi::badgeLabel($item->kategori) }}</span>
                                        </span>

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

                                        @if ($videoCount > 0)
                                            <div class="absolute inset-0 flex items-center justify-center bg-black/15 group-hover:bg-black/5 transition-colors pointer-events-none z-10">
                                                <div class="w-12 h-12 rounded-2xl bg-white/95 text-brand-forest border-2 border-brand-forest flex items-center justify-center shadow-solid-sm group-hover:scale-110 group-hover:bg-brand-forest group-hover:text-white transition-all duration-300">
                                                    <i class="bi bi-play-fill text-2xl ml-0.5"></i>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="p-4 sm:p-5 flex flex-col justify-between flex-grow bg-white">
                                        <div class="space-y-2.5 transform-gpu transition-all duration-300 lg:group-hover:-translate-y-1">
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

                                            <h3 class="{{ $isHero ? 'text-xl sm:text-2xl' : 'text-lg sm:text-xl' }} font-black text-brand-deep leading-snug line-clamp-2 break-words group-hover:text-brand-matcha transition-colors">
                                                <a href="{{ route('galeri.show', $item->id) }}" class="text-inherit text-decoration-none">
                                                    {{ $item->title }}
                                                </a>
                                            </h3>

                                            @if ($item->description)
                                                <p class="text-xs font-medium text-brand-darktext/75 line-clamp-2 leading-relaxed m-0">
                                                    {{ Str::limit(strip_tags($item->description), 110) }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="px-4 sm:px-5 pb-4 sm:pb-5 pt-3.5 border-t-2 border-brand-sand flex items-center justify-between mt-auto bg-white">
                                    <span class="text-[11px] font-black uppercase tracking-wider text-brand-forest/70">
                                        TERLAKSANA &bull; {{ $item->event_date ? \Carbon\Carbon::parse($item->event_date)->format('Y') : '2026' }}
                                    </span>
                                    <a href="{{ route('galeri.show', $item->id) }}" 
                                       class="inline-flex items-center gap-1.5 px-4 py-2 min-h-[44px] lg:min-h-0 rounded-xl bg-brand-sand group-hover:bg-brand-forest group-hover:text-white text-brand-forest font-black text-xs uppercase tracking-wider border-2 border-brand-forest shadow-solid-sm transition-all duration-300 btn-solid-press text-decoration-none">
                                        <span>Buka Dokumentasi</span>
                                        <span class="text-sm leading-none transition-transform duration-300 group-hover:translate-x-1">&rarr;</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-2xl border-2 border-brand-forest shadow-solid-md p-10 text-center space-y-4 max-w-xl mx-auto floating-reveal floating-frame-card">
                    <div class="w-14 h-14 rounded-2xl bg-brand-lightmatcha border-2 border-brand-forest text-brand-forest flex items-center justify-center text-2xl font-black mx-auto shadow-solid-sm">
                        <i class="bi bi-images"></i>
                    </div>
                    <h4 class="text-xl font-black text-brand-deep">Dokumentasi Kegiatan</h4>
                    <p class="text-xs font-semibold text-brand-darktext/70 leading-relaxed">
                        Arsip foto dokumentasi kegiatan yang telah terlaksana akan ditampilkan di sini setelah diunggah oleh pengurus.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('galeri.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-brand-forest text-white font-black text-xs uppercase tracking-wider border-2 border-brand-deep shadow-solid-sm btn-solid-press text-decoration-none">
                            <span>Lihat Halaman Galeri</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 06: KEANGGOTAAN & FORM PENDAFTARAN LENGKAP                                -->
    <!-- ========================================================================= -->
    <section class="py-16 sm:py-24 bg-brand-sand border-b-2 border-brand-forest relative overflow-hidden" id="keanggotaan">
        <!-- Abstract Geometric Background (PixelOS Inspiration - Layer 1: Base Grid & Large Aura 640-680px) -->
        <div class="absolute bottom-0 right-0 w-[640px] h-[640px] pixel-grid-blueprint opacity-60 pointer-events-none -z-0"></div>
        <div class="absolute top-12 -left-36 w-[680px] h-[680px] rounded-full bg-gradient-to-tr from-brand-matcha/[0.18] via-brand-lightmatcha/[0.34] to-transparent pointer-events-none blur-3xl -z-0" data-parallax="bg-slow"></div>

        <!-- Layer 2: Oversized Concentric Shield Geometry (600-680px with 6px Stroke) & Large Floating Pill (360px with 4px Stroke) -->
        <div class="pointer-events-none absolute top-16 -left-44 w-[600px] h-[600px] lg:w-[680px] lg:h-[680px] -z-0" data-parallax="bg-slow" data-parallax-rotate="-3">
            <div class="ambient-float-slow-3 w-full h-full flex items-center justify-center">
                <div class="w-full h-full rounded-full border-[6px] border-brand-forest/[0.28] p-16 sm:p-20 flex items-center justify-center">
                    <div class="w-full h-full rounded-full border-4 border-dashed border-brand-forest/[0.30] flex items-center justify-center p-14">
                        <div class="w-full h-full rounded-full border-[3px] border-brand-forest/[0.22] bg-gradient-to-br from-brand-lightmatcha/40 via-brand-matcha/[0.12] to-transparent"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pointer-events-none absolute bottom-20 -right-10 w-[360px] h-28 -z-0 hidden md:block" data-parallax="mid" data-parallax-rotate="4">
            <div class="ambient-float-slow-2 w-full h-full rounded-full border-4 border-brand-forest/[0.26] bg-gradient-to-r from-white/50 via-brand-lightmatcha/40 to-white/20 backdrop-blur-xs rotate-6 flex items-center px-10 justify-between shadow-sm">
                <span class="w-4 h-4 rounded-full bg-brand-forest/35"></span>
                <span class="h-1 w-28 bg-brand-forest/25 rounded-full"></span>
                <span class="w-4 h-4 rounded-full border-2 border-brand-forest/40 bg-brand-accent/40"></span>
            </div>
        </div>

        <!-- Layer 3: Foreground Matrix Cluster & Crosshair Accents -->
        <div class="pointer-events-none absolute top-8 right-16 w-44 h-44 pixel-dot-matrix opacity-85 hidden sm:block -z-0" data-parallax="fore"></div>
        <div class="pointer-events-none absolute bottom-10 left-1/3 text-brand-forest/[0.35] text-base font-mono font-bold select-none hidden lg:block -z-0" data-parallax="fore">+</div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-start">
                
                <!-- Left Column: Narrative & Contacts (5 cols) -->
                <div class="lg:col-span-5 space-y-6 lg:sticky lg:top-24 floating-reveal">
                    <span data-text-animate="blurInUp" class="px-3 py-1 bg-white text-brand-forest border-2 border-brand-forest rounded-full text-xs font-black uppercase tracking-wider shadow-solid-sm inline-block">
                        KEANGGOTAAN &amp; PERSATUAN
                    </span>
                    <h2 data-text-animate="blurInUp" class="text-3xl sm:text-4xl lg:text-[44px] font-black text-brand-deep tracking-tight leading-[1.12]">
                        Bergabung &amp; Berkhidmah Bersama NU Wangon
                    </h2>
                    <p class="text-base font-semibold text-brand-darktext/80 leading-relaxed">
                        Mari kuatkan jam'iyyah dengan menjadi bagian resmi dari keluarga besar Nahdlatul Ulama. Lengkapi formulir pendaftaran di samping untuk terhubung dengan pengurus ranting dan badan otonom di wilayah Anda.
                    </p>

                    <!-- Contact Boxes with Solid Borders -->
                    <div class="space-y-4 pt-2">
                        <div class="bg-white rounded-2xl p-4 border-2 border-brand-forest shadow-solid-sm flex items-center gap-4 floating-frame-card"
                             data-parallax="card-small">
                            <div class="w-12 h-12 rounded-xl bg-brand-lightmatcha border-2 border-brand-forest text-brand-forest flex items-center justify-center shrink-0 shadow-solid-sm">
                                <i class="bi bi-whatsapp text-xl"></i>
                            </div>
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-wider text-brand-forest/70 block">LAYANAN WHATSAPP RESMI</span>
                                <a href="{{ $pengaturan->whatsapp_url ?? 'https://wa.me/6281234567890' }}" target="_blank" rel="noopener noreferrer" class="text-sm font-black text-brand-deep hover:text-brand-matcha transition-colors text-decoration-none">
                                    {{ $pengaturan->whatsapp ?? '+62 812-3456-7890' }}
                                </a>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl p-4 border-2 border-brand-forest shadow-solid-sm flex items-center gap-4 floating-frame-card"
                             data-parallax="card-small">
                            <div class="w-12 h-12 rounded-xl bg-brand-cream border-2 border-brand-forest text-brand-forest flex items-center justify-center shrink-0 shadow-solid-sm">
                                <i class="bi bi-envelope text-xl"></i>
                            </div>
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-wider text-brand-forest/70 block">EMAIL SEKRETARIAT</span>
                                <a href="mailto:{{ $pengaturan->email ?? 'sekretariat@nuwangon.or.id' }}" class="text-sm font-black text-brand-deep hover:text-brand-matcha transition-colors text-decoration-none">
                                    {{ $pengaturan->email ?? 'sekretariat@nuwangon.or.id' }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Shortcut Cek Status & Direktori Anggota -->
                    <div class="pt-2 space-y-3">
                        <div>
                            <a href="{{ route('anggota.index') }}" class="inline-flex items-center gap-2 text-xs font-black text-brand-forest hover:text-brand-matcha text-decoration-none">
                                <i class="bi bi-people-fill"></i>
                                <span>Lihat Direktori Anggota Terdaftar (KARTANU) &rarr;</span>
                            </a>
                        </div>
                        <div>
                            <a href="{{ route('anggota.cek-status') }}" class="inline-flex items-center gap-2 text-xs font-bold text-brand-darktext/70 hover:text-brand-forest transition-colors text-decoration-none">
                                <i class="bi bi-search"></i>
                                <span>Sudah pernah mendaftar? Cek status pendaftaran Anda di sini &rarr;</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Registration Card (7 cols) -->
                <div class="lg:col-span-7 floating-reveal reveal-delay-1">
                    <div class="bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-9 border-2 border-brand-forest shadow-solid-lg floating-frame-card" data-floating-depth="float">
                        
                        <div class="flex items-center justify-between pb-4 border-b-2 border-brand-sand mb-6">
                            <div>
                                <h3 class="text-2xl sm:text-[26px] font-black text-brand-deep leading-tight">
                                    Formulir Pendaftaran Anggota
                                </h3>
                                <p class="text-xs font-semibold text-brand-darktext/70 mt-1">
                                    Pendaftaran resmi anggota MWC NU Wangon (KARTANU).
                                </p>
                            </div>
                            <span class="hidden sm:inline-block px-3 py-1 bg-brand-lightmatcha text-brand-forest border-2 border-brand-forest rounded-full text-[10px] font-black uppercase tracking-wider shadow-solid-sm">
                                FORM RESMI
                            </span>
                        </div>

                        <!-- Alert Errors jika validasi gagal -->
                        @if (isset($errors) && $errors->any())
                            <div class="mb-6 bg-red-50 border-2 border-red-300 rounded-xl p-4 text-xs font-semibold text-red-700 space-y-1">
                                <p class="font-black flex items-center gap-1.5">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    <span>Mohon periksa kembali formulir Anda:</span>
                                </p>
                                <ul class="list-disc list-inside pl-1 space-y-0.5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Form Pendaftaran -->
                        <form action="{{ route('anggota.daftar.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                            @csrf

                            <!-- 1. Nama Lengkap -->
                            <div>
                                <label for="form_nama" class="block text-xs font-black text-brand-deep mb-1.5 uppercase tracking-wide">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="form_nama" 
                                       name="nama" 
                                       required 
                                       value="{{ old('nama') }}"
                                       placeholder="Contoh: Ahmad Fauzi / Siti Aminah" 
                                       class="w-full bg-brand-cream border-2 border-brand-forest rounded-xl px-4 py-3 text-xs sm:text-sm font-semibold text-brand-deep placeholder:text-stone-400 focus:bg-white focus:outline-none transition-all">
                                <p class="text-[10px] font-semibold text-brand-darktext/60 mt-1">Sesuai dengan kartu identitas resmi (KTP).</p>
                            </div>

                            <!-- 2. NIK -->
                            <div>
                                <label for="form_nik" class="block text-xs font-black text-brand-deep mb-1.5 uppercase tracking-wide">
                                    Nomor Induk Kependudukan (NIK) <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="form_nik" 
                                       name="nik" 
                                       maxlength="16" 
                                       required 
                                       value="{{ old('nik') }}"
                                       placeholder="16 digit NIK sesuai KTP" 
                                       class="w-full bg-brand-cream border-2 border-brand-forest rounded-xl px-4 py-3 text-xs sm:text-sm font-semibold text-brand-deep placeholder:text-stone-400 focus:bg-white focus:outline-none transition-all">
                            </div>

                            <!-- 3. Pilihan Organisasi & Nomor HP -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="form_organisasi" class="block text-xs font-black text-brand-deep mb-1.5 uppercase tracking-wide">
                                        Pilihan Badan Otonom <span class="text-red-500">*</span>
                                    </label>
                                    <select id="form_organisasi" 
                                            name="organisasi" 
                                            required 
                                            class="w-full bg-brand-cream border-2 border-brand-forest rounded-xl px-4 py-3 text-xs font-semibold text-brand-deep focus:bg-white focus:outline-none transition-all">
                                        @if(isset($organisasiOptions) && count($organisasiOptions) > 0)
                                            @foreach($organisasiOptions as $opt)
                                                <option value="{{ $opt->slug }}" 
                                                        data-has-ketua="{{ $opt->has_ketua ? 'true' : 'false' }}"
                                                        data-ketua-name="{{ $opt->ketua_nama ?? '' }}"
                                                        data-has-wakil-ketua="{{ ($opt->has_wakil_ketua ?? false) ? 'true' : 'false' }}"
                                                        data-wakil-ketua-name="{{ $opt->wakil_ketua_nama ?? '' }}"
                                                        {{ old('organisasi', 'muslimat') === $opt->slug ? 'selected' : '' }}>
                                                    {{ $opt->nama }}
                                                </option>
                                            @endforeach
                                        @else
                                            @foreach(\App\Support\Organisasi::registrationOptions() as $orgKey => $orgLabel)
                                                <option value="{{ $orgKey }}" {{ old('organisasi', 'muslimat') === $orgKey ? 'selected' : '' }}>{{ $orgLabel }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div>
                                    <label for="form_no_hp" class="block text-xs font-black text-brand-deep mb-1.5 uppercase tracking-wide">
                                        Nomor WhatsApp / HP <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="form_no_hp" 
                                           name="no_hp" 
                                           required 
                                           value="{{ old('no_hp') }}"
                                           placeholder="Contoh: 081234567890" 
                                           class="w-full bg-brand-cream border-2 border-brand-forest rounded-xl px-4 py-3 text-xs font-semibold text-brand-deep placeholder:text-stone-400 focus:bg-white focus:outline-none transition-all">
                                </div>
                            </div>

                            <!-- DAFTAR SEBAGAI: KETUA / WAKIL KETUA / ANGGOTA -->
                            <div class="space-y-2">
                                <label class="block text-xs font-black text-brand-deep uppercase tracking-wide">
                                    Daftar Sebagai <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <label class="relative flex items-center gap-3 p-3.5 rounded-xl border-2 border-brand-forest bg-brand-cream hover:bg-white cursor-pointer transition-all">
                                        <input type="radio" name="posisi_pendaftaran" id="welcome_posisi_anggota" value="anggota" 
                                               {{ old('posisi_pendaftaran', 'anggota') === 'anggota' ? 'checked' : '' }}
                                               class="w-4 h-4 text-brand-forest focus:ring-brand-forest">
                                        <div>
                                            <div class="text-xs font-black text-brand-deep">Anggota</div>
                                            <div class="text-[10px] font-semibold text-brand-darktext/70">Anggota jam'iyyah.</div>
                                        </div>
                                    </label>

                                    <label id="welcome_label_posisi_ketua" class="relative flex items-center gap-3 p-3.5 rounded-xl border-2 border-brand-forest bg-brand-cream hover:bg-white cursor-pointer transition-all">
                                        <input type="radio" name="posisi_pendaftaran" id="welcome_posisi_ketua" value="ketua"
                                               {{ old('posisi_pendaftaran') === 'ketua' ? 'checked' : '' }}
                                               class="w-4 h-4 text-brand-forest focus:ring-brand-forest">
                                        <div>
                                            <div class="text-xs font-black text-brand-deep flex items-center gap-1.5">
                                                <span>Ketua</span>
                                                <span class="text-[9px] uppercase px-1.5 py-0.5 rounded bg-amber-100 text-amber-800 font-black border border-amber-300">Calon</span>
                                            </div>
                                            <div class="text-[10px] font-semibold text-brand-darktext/70">Calon Ketua.</div>
                                        </div>
                                    </label>

                                    <label id="welcome_label_posisi_wakil_ketua" class="relative flex items-center gap-3 p-3.5 rounded-xl border-2 border-brand-forest bg-brand-cream hover:bg-white cursor-pointer transition-all">
                                        <input type="radio" name="posisi_pendaftaran" id="welcome_posisi_wakil_ketua" value="wakil_ketua"
                                               {{ old('posisi_pendaftaran') === 'wakil_ketua' ? 'checked' : '' }}
                                               class="w-4 h-4 text-brand-forest focus:ring-brand-forest">
                                        <div>
                                            <div class="text-xs font-black text-brand-deep flex items-center gap-1.5">
                                                <span>Wakil Ketua</span>
                                                <span class="text-[9px] uppercase px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800 font-black border border-emerald-300">Calon</span>
                                            </div>
                                            <div class="text-[10px] font-semibold text-brand-darktext/70">Calon Wakil Ketua.</div>
                                        </div>
                                    </label>
                                </div>

                                <!-- Alert jika organisasi sudah memiliki ketua aktif -->
                                <div id="welcome_ketua_warning_box" class="hidden p-3.5 rounded-xl bg-amber-50 border-2 border-amber-300 text-amber-800 text-xs font-bold flex items-center gap-2">
                                    <i class="bi bi-info-circle-fill text-amber-600 shrink-0 text-sm"></i>
                                    <span id="welcome_ketua_warning_text">Organisasi ini sudah memiliki ketua aktif.</span>
                                </div>

                                <!-- Alert jika organisasi sudah memiliki wakil ketua aktif -->
                                <div id="welcome_wakil_ketua_warning_box" class="hidden p-3.5 rounded-xl bg-amber-50 border-2 border-amber-300 text-amber-800 text-xs font-bold flex items-center gap-2">
                                    <i class="bi bi-info-circle-fill text-amber-600 shrink-0 text-sm"></i>
                                    <span id="welcome_wakil_ketua_warning_text">Organisasi ini sudah memiliki wakil ketua aktif.</span>
                                </div>
                            </div>

                            <!-- 4. Alamat Lengkap -->
                            <div>
                                <label for="form_alamat" class="block text-xs font-black text-brand-deep mb-1.5 uppercase tracking-wide">
                                    Alamat Lengkap Domisili <span class="text-red-500">*</span>
                                </label>
                                <textarea id="form_alamat" 
                                          name="alamat" 
                                          rows="2" 
                                          required 
                                          placeholder="Sebutkan RT/RW, Dusun, dan Desa se-Kecamatan Wangon" 
                                          class="w-full bg-brand-cream border-2 border-brand-forest rounded-xl p-3.5 text-xs font-semibold text-brand-deep placeholder:text-stone-400 focus:bg-white focus:outline-none transition-all resize-none">{{ old('alamat') }}</textarea>
                            </div>

                            <!-- 5. Pas Foto Formal -->
                            <div>
                                <label for="form_foto" class="block text-xs font-black text-brand-deep mb-1.5 uppercase tracking-wide">
                                    Pas Foto Formal <span class="text-red-500">*</span>
                                </label>
                                <input type="file" 
                                       id="form_foto" 
                                       name="foto" 
                                       accept="image/*" 
                                       required 
                                       class="w-full bg-brand-cream border-2 border-brand-forest rounded-xl p-2.5 text-xs font-semibold text-brand-deep file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-2 file:border-brand-deep file:text-[11px] file:font-black file:bg-brand-forest file:text-white hover:file:bg-brand-deep cursor-pointer transition-all">
                                <p class="text-[10px] font-semibold text-brand-darktext/60 mt-1">Format: JPG, JPEG, PNG (Maksimal 2 MB). Foto sopan dan jelas.</p>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="pt-3">
                                <button type="submit" 
                                        class="w-full py-4 rounded-xl text-xs font-black uppercase tracking-wider text-white bg-brand-forest hover:bg-brand-deep border-2 border-brand-deep shadow-solid-md btn-solid-press transition-all cursor-pointer flex items-center justify-center gap-2">
                                    <span>Kirim Formulir Pendaftaran</span>
                                    <span class="text-sm">&rarr;</span>
                                </button>
                            </div>

                            <p class="text-[10px] font-bold text-brand-darktext/60 text-center pt-1 leading-relaxed">
                                &#128274; Data jam'iyyah terjaga aman dan hanya digunakan untuk tertib administrasi MWC NU Wangon.
                            </p>
                        </form>

                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const orgSelect = document.getElementById('form_organisasi');
                            const radioKetua = document.getElementById('welcome_posisi_ketua');
                            const radioWakilKetua = document.getElementById('welcome_posisi_wakil_ketua');
                            const radioAnggota = document.getElementById('welcome_posisi_anggota');
                            const labelKetua = document.getElementById('welcome_label_posisi_ketua');
                            const labelWakilKetua = document.getElementById('welcome_label_posisi_wakil_ketua');
                            const warningBox = document.getElementById('welcome_ketua_warning_box');
                            const warningText = document.getElementById('welcome_ketua_warning_text');
                            const warningWakilBox = document.getElementById('welcome_wakil_ketua_warning_box');
                            const warningWakilText = document.getElementById('welcome_wakil_ketua_warning_text');

                            function checkWelcomeAvailability() {
                                if (!orgSelect) return;
                                const selectedOpt = orgSelect.options[orgSelect.selectedIndex];
                                if (!selectedOpt) return;

                                const hasKetua = selectedOpt.getAttribute('data-has-ketua') === 'true';
                                const ketuaName = selectedOpt.getAttribute('data-ketua-name');
                                const hasWakilKetua = selectedOpt.getAttribute('data-has-wakil-ketua') === 'true';
                                const wakilKetuaName = selectedOpt.getAttribute('data-wakil-ketua-name');

                                if (radioKetua) {
                                    if (hasKetua) {
                                        radioKetua.disabled = true;
                                        if (labelKetua) {
                                            labelKetua.classList.add('opacity-50', 'cursor-not-allowed', 'bg-stone-100');
                                        }
                                        if (radioKetua.checked && radioAnggota) {
                                            radioAnggota.checked = true;
                                        }
                                        if (warningBox) {
                                            warningBox.classList.remove('hidden');
                                            warningText.textContent = `Organisasi ini sudah memiliki ketua aktif${ketuaName ? ' (' + ketuaName + ')' : ''}. Pilihan Ketua dinonaktifkan.`;
                                        }
                                    } else {
                                        radioKetua.disabled = false;
                                        if (labelKetua) {
                                            labelKetua.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-stone-100');
                                        }
                                        if (warningBox) {
                                            warningBox.classList.add('hidden');
                                        }
                                    }
                                }

                                if (radioWakilKetua) {
                                    if (hasWakilKetua) {
                                        radioWakilKetua.disabled = true;
                                        if (labelWakilKetua) {
                                            labelWakilKetua.classList.add('opacity-50', 'cursor-not-allowed', 'bg-stone-100');
                                        }
                                        if (radioWakilKetua.checked && radioAnggota) {
                                            radioAnggota.checked = true;
                                        }
                                        if (warningWakilBox) {
                                            warningWakilBox.classList.remove('hidden');
                                            warningWakilText.textContent = `Organisasi ini sudah memiliki wakil ketua aktif${wakilKetuaName ? ' (' + wakilKetuaName + ')' : ''}. Pilihan Wakil Ketua dinonaktifkan.`;
                                        }
                                    } else {
                                        radioWakilKetua.disabled = false;
                                        if (labelWakilKetua) {
                                            labelWakilKetua.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-stone-100');
                                        }
                                        if (warningWakilBox) {
                                            warningWakilBox.classList.add('hidden');
                                        }
                                    }
                                }
                            }

                            if (orgSelect) {
                                orgSelect.addEventListener('change', checkWelcomeAvailability);
                                checkWelcomeAvailability();
                            }
                        });
                        </script>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 07: LOKASI & SEKRETARIAT (HIGH IMPACT SPEC CARD)                          -->
    <!-- ========================================================================= -->
    <section class="py-16 sm:py-20 bg-brand-cream relative overflow-hidden" id="lokasi">
        <!-- Abstract Geometric Background (PixelOS Inspiration - Layer 1: Base Grid & Large Aura 540px) -->
        <div class="absolute inset-0 pixel-grid-blueprint opacity-60 pointer-events-none -z-0"></div>
        <div class="absolute -top-24 -right-24 w-[540px] h-[540px] rounded-full bg-brand-lightmatcha/35 pointer-events-none blur-3xl -z-0" data-parallax="bg-slow"></div>

        <!-- Layer 2: Large Concentric Ring Geometry (480px with 5px Stroke & Translucent Fill) & Accents -->
        <div class="pointer-events-none absolute -bottom-20 -left-20 w-[480px] h-[480px] rounded-full border-[5px] border-brand-forest/[0.26] p-12 flex items-center justify-center hidden sm:flex -z-0" data-parallax="bg-slow" data-parallax-rotate="-3">
            <div class="w-full h-full rounded-full border-[3px] border-dashed border-brand-matcha/[0.30] bg-brand-lightmatcha/[0.15]"></div>
        </div>
        <div class="pointer-events-none absolute top-8 left-12 text-brand-forest/[0.35] text-base font-mono font-bold select-none hidden md:block -z-0" data-parallax="fore">+</div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="bg-white rounded-2xl sm:rounded-3xl border-2 border-brand-forest shadow-solid-lg p-4 sm:p-10 floating-reveal floating-frame-card" data-floating-depth="float">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <div class="lg:col-span-8 space-y-4">
                        <span data-text-animate="blurInUp" class="px-3 py-1 bg-brand-lightmatcha text-brand-forest border-2 border-brand-forest rounded-full text-xs font-black uppercase tracking-wider shadow-solid-sm inline-block">
                            {{ $pengaturan->label_section ?? 'PUSAT LAYANAN & SEKRETARIAT' }}
                        </span>
                        <h3 data-text-animate="blurInUp" class="text-2xl sm:text-3xl font-black text-brand-deep">
                            {{ $pengaturan->nama_kantor ?? 'Kantor MWC Nahdlatul Ulama Wangon' }}
                        </h3>
                        <p class="text-sm font-semibold text-brand-darktext/80 leading-relaxed max-w-2xl">
                            {{ $pengaturan->alamat_lengkap_formatted ?? 'Jl. Raya Wangon - Ajibarang, Dusun Sindurjan, Desa Wangon, Kecamatan Wangon, Kabupaten Banyumas, Jawa Tengah 53176.' }}
                        </p>
                        
                        @php
                            $kartuItems = ($kartuLayananList ?? collect());
                            if ($kartuItems->isEmpty()) {
                                $kartuItems = collect([
                                    (object)['judul' => 'Jam Operasional', 'deskripsi' => $pengaturan->jam_operasional_formatted ?? 'Senin - Ahad, 08.00 - 16.00'],
                                    (object)['judul' => 'Layanan KARTANU', 'deskripsi' => $pengaturan->jam_operasional_status ?? 'Setiap Hari Kerja'],
                                    (object)['judul' => 'Ruang Rapat Banom', 'deskripsi' => 'Aula Lt. 2'],
                                ]);
                            }
                        @endphp

                        <div class="grid grid-cols-1 {{ $kartuItems->count() == 1 ? 'sm:grid-cols-1 max-w-sm' : ($kartuItems->count() == 2 ? 'sm:grid-cols-2' : ($kartuItems->count() >= 4 ? 'sm:grid-cols-2 md:grid-cols-4' : 'sm:grid-cols-3')) }} gap-3 pt-2">
                            @foreach($kartuItems as $kartu)
                                <div class="bg-brand-cream p-3.5 rounded-xl border-2 border-brand-forest floating-frame-card">
                                    <span class="text-[10px] font-black uppercase text-brand-forest/70 block">{{ $kartu->judul }}</span>
                                    <span class="text-xs font-black text-brand-deep break-words">
                                        @if(strtolower(trim($kartu->judul)) === 'jam operasional')
                                            {{ $pengaturan->jam_operasional_formatted ?? $kartu->deskripsi }}
                                        @elseif(strtolower(trim($kartu->judul)) === 'layanan kartanu' && !empty($pengaturan->jam_operasional_status))
                                            {{ $pengaturan->jam_operasional_status }}
                                        @else
                                            {{ $kartu->deskripsi }}
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="lg:col-span-4 flex flex-col gap-3">
                        @if($pengaturan->maps_url)
                            <a href="{{ $pengaturan->maps_url }}" target="_blank" rel="noopener noreferrer" 
                               class="w-full py-4 bg-brand-forest hover:bg-brand-deep text-white font-black text-xs uppercase tracking-wider rounded-xl border-2 border-brand-deep shadow-solid-sm btn-solid-press flex items-center justify-center gap-2 text-decoration-none">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>Buka Petunjuk Arah Maps</span>
                            </a>
                        @else
                            <button type="button" disabled
                               class="w-full py-4 bg-brand-forest/60 text-white/50 font-black text-xs uppercase tracking-wider rounded-xl border-2 border-brand-deep/40 shadow-solid-sm flex items-center justify-center gap-2 cursor-not-allowed">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>Buka Petunjuk Arah Maps</span>
                            </button>
                        @endif

                        @if($pengaturan->whatsapp_url)
                            <a href="{{ $pengaturan->whatsapp_url }}" target="_blank" rel="noopener noreferrer" 
                               class="w-full py-3.5 bg-brand-sand hover:bg-[#E2DDD0] text-brand-forest font-black text-xs uppercase tracking-wider rounded-xl border-2 border-brand-forest shadow-solid-sm btn-solid-press flex items-center justify-center gap-2 text-decoration-none">
                                <i class="bi bi-whatsapp"></i>
                                <span>Hubungi Sekretariat</span>
                            </a>
                        @else
                            <button type="button" disabled
                               class="w-full py-3.5 bg-brand-sand/60 text-brand-forest/50 font-black text-xs uppercase tracking-wider rounded-xl border-2 border-brand-forest/40 shadow-solid-sm flex items-center justify-center gap-2 cursor-not-allowed">
                                <i class="bi bi-whatsapp"></i>
                                <span>Hubungi Sekretariat</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

<!-- ============================================================== -->
<!-- SUPER SMOOTH FRAMER MOTION SCRIPT                              -->
<!-- ============================================================== -->
<script>
(function() {
    function initFloatingFrameExperience() {
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        
        // 1. UNIFIED FLOATING SCROLL REVEAL ENGINE
        const revealElements = document.querySelectorAll('.floating-reveal');
        if (prefersReducedMotion || !('IntersectionObserver' in window)) {
            revealElements.forEach(el => el.classList.add('is-revealed'));
        } else {
            const revealObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-revealed');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.08,
                rootMargin: '0px 0px -40px 0px'
            });

            revealElements.forEach(el => revealObserver.observe(el));
        }

        // 2. HERO SEQUENTIAL ENTRANCE (MOTION ENGINE)
        if (window.Motion && !prefersReducedMotion) {
            const { animate, stagger } = window.Motion;
            const easeSilk = [0.22, 1, 0.36, 1];

            if (document.getElementById('hero-label')) {
                animate('#hero-label', { opacity: [0, 1], y: [16, 0] }, { duration: 0.75, easing: easeSilk });
            }
            if (document.getElementById('hero-title')) {
                animate('#hero-title', { opacity: [0, 1], y: [22, 0] }, { duration: 0.85, delay: 0.08, easing: easeSilk });
            }
            if (document.getElementById('hero-desc')) {
                animate('#hero-desc', { opacity: [0, 1], y: [16, 0] }, { duration: 0.8, delay: 0.16, easing: easeSilk });
            }
            if (document.getElementById('hero-cta')) {
                animate('#hero-cta', { opacity: [0, 1], y: [14, 0] }, { duration: 0.75, delay: 0.24, easing: easeSilk });
            }
            if (document.getElementById('hero-meta')) {
                animate('#hero-meta', { opacity: [0, 1] }, { duration: 0.8, delay: 0.32, easing: easeSilk });
            }
            if (document.getElementById('hero-image-col')) {
                animate('#hero-image-col', { opacity: [0, 1], scale: [0.96, 1], y: [18, 0] }, { duration: 0.95, delay: 0.12, easing: easeSilk });
            }

            // Stat Cards Sequence & Realtime Counter
            if (document.getElementById('value-strip')) {
                const statCards = document.querySelectorAll('.stat-card-motion');
                if (statCards.length > 0) {
                    animate(statCards, { opacity: [0, 1], y: [20, 0] }, {
                        duration: 0.6,
                        delay: stagger(0.12, { start: 0.35 }),
                        easing: easeSilk
                    });

                    const yearElem = document.querySelector('.stat-year-val');
                    if (yearElem) {
                        animate(yearElem, { opacity: [0.4, 1], scale: [0.95, 1] }, {
                            duration: 0.55,
                            delay: 0.42,
                            easing: easeSilk
                        });
                    }


                }
            }
        } else {
            // Immediate display if reduced motion or Motion not loaded
            const statCards = document.querySelectorAll('.stat-card-motion');
            statCards.forEach(card => {
                card.style.opacity = '1';
                card.style.transform = 'none';
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFloatingFrameExperience);
    } else {
        initFloatingFrameExperience();
    }

    // BFCache safety: Never leave elements hidden if restored from history
    window.addEventListener('pageshow', function(e) {
        if (e.persisted) {
            document.querySelectorAll('.floating-reveal').forEach(function(el) {
                el.classList.add('is-revealed');
                el.style.opacity = '1';
                el.style.transform = 'none';
            });
            const allAnimated = document.querySelectorAll('#hero-label, #hero-title, #hero-desc, #hero-cta, #hero-meta, #hero-image-col, #value-strip, .stat-card-motion');
            allAnimated.forEach(function(el) {
                el.style.opacity = '1';
                el.style.transform = 'none';
            });
        }
    });
})();
</script>

<!-- ============================================================== -->
<!-- REALTIME AGENDA COUNTDOWN SCRIPT                               -->
<!-- ============================================================== -->
<script>
(function() {
    function initKegiatanCountdown() {
        const card = document.getElementById('countdown-card');
        if (!card) return;

        const startMs = parseInt(card.getAttribute('data-start'), 10);
        const endMs = parseInt(card.getAttribute('data-end'), 10);
        if (isNaN(startMs)) return;

        const daysEl = document.getElementById('cdDays');
        const hoursEl = document.getElementById('cdHours');
        const minsEl = document.getElementById('cdMinutes');
        const secsEl = document.getElementById('cdSeconds');
        const digitsContainer = document.getElementById('countdownDigitsContainer');
        const ongoingMsg = document.getElementById('countdownOngoingMessage');
        const finishedMsg = document.getElementById('countdownFinishedMessage');
        const badge = document.getElementById('countdownStatusBadge');
        const badgeText = document.getElementById('countdownStatusBadgeText');
        const summaryText = document.getElementById('countdownSummaryText');

        function updateCountdown() {
            const now = Date.now();

            if (now >= endMs) {
                // Acara telah selesai
                if (digitsContainer) digitsContainer.classList.add('hidden');
                if (ongoingMsg) ongoingMsg.classList.add('hidden');
                if (finishedMsg) finishedMsg.classList.remove('hidden');
                if (summaryText) summaryText.textContent = 'telah selesai';
                if (badgeText) badgeText.textContent = 'Acara Telah Selesai';
                if (badge) {
                    badge.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-stone-500/20 text-stone-300 border border-stone-500/40';
                    badge.innerHTML = '<span class="w-2 h-2 rounded-full bg-stone-400"></span><span id="countdownStatusBadgeText">Acara Telah Selesai</span>';
                }
                return;
            }

            if (now >= startMs) {
                // Acara sedang berlangsung
                if (digitsContainer) digitsContainer.classList.add('hidden');
                if (ongoingMsg) ongoingMsg.classList.remove('hidden');
                if (finishedMsg) finishedMsg.classList.add('hidden');
                if (summaryText) summaryText.textContent = 'sedang berlangsung';
                if (badgeText) badgeText.textContent = 'Sedang Berlangsung';
                if (badge) {
                    badge.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/40';
                    badge.innerHTML = '<span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span><span id="countdownStatusBadgeText">Sedang Berlangsung</span>';
                }
                return;
            }

            // Hitung mundur menuju acara
            const diff = Math.max(0, startMs - now);
            const totalSeconds = Math.floor(diff / 1000);
            const days = Math.floor(totalSeconds / 86400);
            const hours = Math.floor((totalSeconds % 86400) / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;

            if (digitsContainer) digitsContainer.classList.remove('hidden');
            if (ongoingMsg) ongoingMsg.classList.add('hidden');
            if (finishedMsg) finishedMsg.classList.add('hidden');

            if (daysEl) daysEl.textContent = days;
            if (hoursEl) hoursEl.textContent = String(hours).padStart(2, '0');
            if (minsEl) minsEl.textContent = String(minutes).padStart(2, '0');
            if (secsEl) secsEl.textContent = String(seconds).padStart(2, '0');

            if (summaryText) {
                if (days > 0) {
                    summaryText.textContent = `${days} hari ${hours} jam lagi`;
                } else if (hours > 0) {
                    summaryText.textContent = `${hours} jam ${minutes} menit lagi`;
                } else {
                    summaryText.textContent = `${minutes} menit ${seconds} detik lagi`;
                }p
            }

            if (badgeText) badgeText.textContent = 'Mendatang';
            if (badge) {
                badge.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/40';
                badge.innerHTML = '<span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span><span id="countdownStatusBadgeText">Mendatang</span>';
            }
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initKegiatanCountdown);
    } else {
        initKegiatanCountdown();
    }
})();
</script>

@endsection