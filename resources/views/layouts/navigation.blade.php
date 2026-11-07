<!-- FIXED SOLID-WEIGHT NAVBAR (MATCHING REFERENCE DESIGN) -->
<header id="mainNavbarHeader" 
        data-navbar="true"
        x-data="{ 
            mobileMenuOpen: false, 
            userDropdownOpen: false, 
            mobileBanomOpen: {{ request()->has('organisasi') ? 'true' : 'false' }} 
        }" 
        x-init="$watch('mobileMenuOpen', value => { 
            document.body.style.overflow = value ? 'hidden' : ''; 
        })"
        @keydown.escape.window="mobileMenuOpen = false"
        @resize.window="if (window.innerWidth >= 1024) { mobileMenuOpen = false; document.body.style.overflow = ''; }"
        class="fixed top-0 left-0 right-0 w-full z-[9999] bg-[#F6F4EE] border-b-2 border-brand-forest"
        style="padding-top: env(safe-area-inset-top, 0px); width: 100%; max-width: 100%;">
    <div class="w-full max-w-full lg:max-w-7xl mx-auto px-3.5 sm:px-6 lg:px-8 h-16 lg:h-20 flex items-center justify-between relative z-[10000]">
        
        <!-- Brand Logo & Identity (Mobile Compact, Desktop Full) -->
        <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-3 group text-decoration-none min-w-0 flex-1 lg:flex-initial pr-2">
            <div class="w-9 h-9 sm:w-11 sm:h-11 lg:w-12 lg:h-12 bg-brand-forest text-brand-lightmatcha flex items-center justify-center rounded-xl border-2 border-brand-deep shadow-solid-xs sm:shadow-solid-sm group-hover:bg-brand-matcha transition-colors p-1 sm:p-1.5 shrink-0">
                <img src="{{ asset('images/landing/logo-nu.png') }}" 
                     alt="Logo MWC NU Wangon" 
                     class="w-full h-full object-contain filter logo-contrast-subtle group-hover:scale-105 transition-transform duration-200">
            </div>
            <div class="min-w-0 flex-1">
                <span class="text-xs sm:text-base lg:text-lg font-black tracking-tight text-brand-deep block leading-tight truncate">
                    ORGANISASI <span class="text-brand-matcha">NU WANGON</span>
                </span>
                <span class="text-[9px] sm:text-[10px] font-black tracking-wider uppercase text-brand-forest/70 block leading-none mt-0.5 truncate">
                    Majelis Wakil Cabang
                </span>
            </div>
        </a>

        <!-- Desktop Nav Links (Motion Trail Tabs - Framer Style) - 100% LOCKED DESKTOP -->
        <nav id="desktopNav" 
             class="motion-trail-nav hidden lg:flex items-center relative p-1 rounded-2xl bg-brand-sand/65 border-2 border-brand-forest/20 shadow-xs font-extrabold text-[13px] text-brand-forest select-none isolate"
             role="tablist"
             aria-label="Navigasi Utama">
            
            <!-- Shared Motion Trail Sliding Indicator Pill -->
            <div id="motionTrailIndicator"
                 class="motion-trail-indicator pointer-events-none rounded-xl bg-brand-forest shadow-solid-xs z-0"
                 aria-hidden="true"
                 style="opacity: 0; transform: translate3d(0, 0, 0); top: 4px; left: 0; width: 0; height: calc(100% - 8px);">
                
                <!-- SVG Motion Trail (Framer Motion style lines following the pill) -->
                <svg id="motionTrailSvg"
                     class="motion-trail-svg pointer-events-none overflow-visible z-0"
                     aria-hidden="true"
                     viewBox="0 0 100 100"
                     preserveAspectRatio="none"
                     style="height: 100%; width: 0; opacity: 0;">
                    <path class="motion-trail-path motion-trail-path-1" d="M 100 36 L 0 36" fill="none" stroke="#142E25" stroke-width="3.5" stroke-linecap="round" vector-effect="non-scaling-stroke" />
                    <path class="motion-trail-path motion-trail-path-2" d="M 100 64 L 25 64" fill="none" stroke="#142E25" stroke-width="2.5" stroke-linecap="round" vector-effect="non-scaling-stroke" />
                </svg>
            </div>

            <a href="{{ route('home') }}#beranda" 
               data-nav-id="beranda"
               class="nav-trail-link relative z-10 px-3.5 py-1.5 rounded-xl font-extrabold text-[13px] transition-colors duration-200 text-decoration-none select-none {{ request()->routeIs('home') ? 'is-active' : '' }}"
               {{ request()->routeIs('home') ? 'aria-current="page"' : '' }}>
                <span class="nav-trail-text block transition-transform duration-150">Beranda</span>
            </a>
            <a href="{{ route('home') }}#tentang" 
               data-nav-id="tentang"
               class="nav-trail-link relative z-10 px-3.5 py-1.5 rounded-xl font-extrabold text-[13px] transition-colors duration-200 text-decoration-none select-none">
                <span class="nav-trail-text block transition-transform duration-150">Tentang</span>
            </a>
            <a href="{{ route('kegiatan.index') }}" 
               data-nav-id="agenda"
               class="nav-trail-link relative z-10 px-3.5 py-1.5 rounded-xl font-extrabold text-[13px] transition-colors duration-200 text-decoration-none select-none {{ request()->routeIs('kegiatan.*') ? 'is-active' : '' }}"
               {{ request()->routeIs('kegiatan.*') ? 'aria-current="page"' : '' }}>
                <span class="nav-trail-text block transition-transform duration-150">Agenda</span>
            </a>
            <a href="{{ route('berita.index') }}" 
               data-nav-id="berita"
               class="nav-trail-link relative z-10 px-3.5 py-1.5 rounded-xl font-extrabold text-[13px] transition-colors duration-200 text-decoration-none select-none {{ request()->routeIs('berita.*') ? 'is-active' : '' }}"
               {{ request()->routeIs('berita.*') ? 'aria-current="page"' : '' }}>
                <span class="nav-trail-text block transition-transform duration-150">Berita</span>
            </a>
            <a href="{{ route('galeri.index') }}" 
               data-nav-id="galeri"
               class="nav-trail-link relative z-10 px-3.5 py-1.5 rounded-xl font-extrabold text-[13px] transition-colors duration-200 text-decoration-none select-none {{ request()->routeIs('galeri.*') ? 'is-active' : '' }}"
               {{ request()->routeIs('galeri.*') ? 'aria-current="page"' : '' }}>
                <span class="nav-trail-text block transition-transform duration-150">Galeri</span>
            </a>
            <a href="{{ route('anggota.index') }}" 
               data-nav-id="anggota"
               class="nav-trail-link relative z-10 px-3.5 py-1.5 rounded-xl font-extrabold text-[13px] transition-colors duration-200 text-decoration-none select-none {{ request()->routeIs('anggota.*') && !request()->routeIs('anggota.daftar*') ? 'is-active' : '' }}"
               {{ request()->routeIs('anggota.*') && !request()->routeIs('anggota.daftar*') ? 'aria-current="page"' : '' }}>
                <span class="nav-trail-text block transition-transform duration-150">Anggota</span>
            </a>
        </nav>

        <!-- Right Side: Desktop Controls & Mobile Hamburger -->
        <div class="flex items-center gap-2 sm:gap-3 shrink-0">
            <!-- Desktop Only CTA Button: Daftar Anggota -->
            <a href="{{ route('anggota.daftar') }}" 
               class="hidden lg:inline-flex items-center gap-2 px-5 py-2.5 bg-brand-forest hover:bg-brand-deep text-white font-black text-xs uppercase tracking-wider rounded-xl border-2 border-brand-deep shadow-solid-sm transition-all btn-solid-press text-decoration-none shrink-0 desktop-cta-btn">
                <i class="bi bi-person-plus-fill text-sm"></i>
                <span>Daftar Anggota</span>
            </a>

            <!-- Desktop Only User Profile / Menu Dropdown Control -->
            <div class="hidden lg:block relative desktop-user-dropdown" @click.outside="userDropdownOpen = false">
                @guest
                    <button type="button" 
                            @click="userDropdownOpen = !userDropdownOpen"
                            class="w-10 h-10 rounded-xl bg-brand-sand border-2 border-brand-forest hover:bg-white text-brand-forest flex items-center justify-center transition-colors shadow-solid-sm btn-solid-press cursor-pointer"
                            title="Menu Akun"
                            aria-label="Menu Akun">
                        <i class="bi bi-person text-base font-bold"></i>
                    </button>

                    <!-- Guest Dropdown Menu -->
                    <div x-show="userDropdownOpen" 
                         x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                         class="absolute right-0 mt-2 w-52 bg-white rounded-2xl border-2 border-brand-forest shadow-solid-md py-2 z-50">
                        <a href="{{ route('login') }}" 
                           class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-black text-brand-deep hover:bg-brand-cream hover:text-brand-matcha transition-colors text-decoration-none">
                            <i class="bi bi-box-arrow-in-right text-sm text-brand-forest"></i>
                            <span>Masuk Akun</span>
                        </a>
                        <a href="{{ route('anggota.cek-status') }}" 
                           class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold text-brand-darktext/70 hover:bg-brand-cream hover:text-brand-forest transition-colors text-decoration-none">
                            <i class="bi bi-search text-xs text-brand-forest"></i>
                            <span>Cek Status KARTANU</span>
                        </a>
                    </div>
                @else
                    <button type="button" 
                            @click="userDropdownOpen = !userDropdownOpen"
                            class="w-10 h-10 rounded-xl bg-brand-forest text-white border-2 border-brand-deep flex items-center justify-center text-xs font-black shadow-solid-sm btn-solid-press cursor-pointer"
                            aria-expanded="false"
                            aria-haspopup="true">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </button>

                    <!-- Authenticated User Dropdown -->
                    <div x-show="userDropdownOpen" 
                         x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                         class="absolute right-0 mt-2 w-56 bg-white rounded-2xl border-2 border-brand-forest shadow-solid-md py-2 z-50">
                        
                        <div class="px-4 py-2.5 border-b-2 border-brand-sand">
                            <p class="text-xs font-black text-brand-deep truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] font-bold text-brand-forest/70 capitalize mt-0.5">{{ Auth::user()->role }}</p>
                        </div>

                        <div class="py-1 text-xs font-bold">
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" 
                                   class="flex items-center gap-2.5 px-4 py-2 text-brand-deep hover:bg-brand-cream hover:text-brand-matcha transition-colors text-decoration-none">
                                    <i class="bi bi-speedometer2 text-brand-matcha"></i>
                                    <span>Dashboard Admin</span>
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" 
                                   class="flex items-center gap-2.5 px-4 py-2 text-brand-deep hover:bg-brand-cream hover:text-brand-matcha transition-colors text-decoration-none">
                                    <i class="bi bi-person-circle text-brand-matcha"></i>
                                    <span>Profil Saya</span>
                                </a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center gap-2.5 px-4 py-2 text-rose-600 hover:bg-rose-50 transition-colors text-left cursor-pointer font-bold">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>

            <!-- Mobile-Only Hamburger Toggle Button (Animated ☰ → ✕) -->
            <button type="button" 
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="shrink-0 lg:hidden w-11 h-11 rounded-xl bg-brand-sand border-2 border-brand-forest text-brand-forest shadow-solid-xs active:translate-x-0.5 active:translate-y-0.5 transition-all flex items-center justify-center cursor-pointer select-none focus:outline-none touch-manipulation mobile-hamburger-btn"
                    :aria-expanded="mobileMenuOpen.toString()"
                    aria-label="Toggle Menu Navigasi Mobile">
                <div class="w-5 h-4 relative flex flex-col justify-between items-center pointer-events-none">
                    <span class="w-full h-0.5 bg-brand-forest rounded-full transition-all duration-300 transform origin-center"
                          :class="mobileMenuOpen ? 'rotate-45 translate-y-[7px]' : ''"></span>
                    <span class="w-full h-0.5 bg-brand-forest rounded-full transition-all duration-200"
                          :class="mobileMenuOpen ? 'opacity-0 scale-x-0' : 'opacity-100'"></span>
                    <span class="w-full h-0.5 bg-brand-forest rounded-full transition-all duration-300 transform origin-center"
                          :class="mobileMenuOpen ? '-rotate-45 -translate-y-[7px]' : ''"></span>
                </div>
            </button>
        </div>
    </div>

    <!-- Mobile Backdrop Dimming Overlay -->
    <div x-show="mobileMenuOpen" 
         x-cloak
         @click="mobileMenuOpen = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-brand-deep/75 backdrop-blur-xs z-[9990] lg:hidden touch-manipulation mobile-backdrop"
         style="top: 64px; top: calc(var(--nav-height, 64px) + env(safe-area-inset-top, 0px));"
         aria-hidden="true"></div>

    <!-- Mobile Navigation Menu Drawer -->
    <div x-show="mobileMenuOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="fixed left-0 right-0 w-full max-w-full z-[9995] lg:hidden bg-brand-cream border-b-2 border-brand-forest shadow-[0_8px_0_0_#142E25] overflow-y-auto overscroll-contain px-4 pt-3 space-y-3 font-bold text-brand-forest mobile-drawer"
         style="top: 64px; top: calc(var(--nav-height, 64px) + env(safe-area-inset-top, 0px)); max-height: calc(100vh - 64px); max-height: calc(100dvh - var(--nav-height, 64px) - env(safe-area-inset-top, 0px)); padding-bottom: calc(2.5rem + env(safe-area-inset-bottom, 0px)); -webkit-overflow-scrolling: touch; touch-action: pan-y; overscroll-behavior-y: contain;">
        
        <!-- Navigation Links with Rich Icon Badges & Active States -->
        <div class="space-y-1.5">
            <a href="{{ route('home') }}#beranda" 
               @click="mobileMenuOpen = false"
               class="min-h-[48px] flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all text-decoration-none touch-manipulation {{ request()->routeIs('home') ? 'bg-brand-forest text-brand-cream border-2 border-brand-deep shadow-solid-xs' : 'hover:bg-brand-sand text-brand-forest' }}">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-lg {{ request()->routeIs('home') ? 'bg-brand-matcha text-white' : 'bg-brand-sand border border-brand-forest/20 text-brand-forest' }} flex items-center justify-center shrink-0">
                        <i class="bi bi-house-door-fill text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="block font-black text-sm leading-tight">Beranda</span>
                        <span class="block text-[10px] {{ request()->routeIs('home') ? 'text-brand-lightmatcha/80' : 'text-brand-forest/60' }} font-bold leading-tight mt-0.5">Halaman Utama Portal</span>
                    </div>
                </div>
                @if(request()->routeIs('home'))
                    <span class="px-2 py-0.5 bg-brand-matcha text-white text-[9px] font-black uppercase rounded-md tracking-wider">Aktif</span>
                @else
                    <i class="bi bi-chevron-right text-xs opacity-50"></i>
                @endif
            </a>

            <a href="{{ route('home') }}#tentang" 
               @click="mobileMenuOpen = false"
               class="min-h-[48px] flex items-center justify-between px-3.5 py-2.5 rounded-xl hover:bg-brand-sand transition-all text-decoration-none text-brand-forest touch-manipulation">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-lg bg-brand-sand border border-brand-forest/20 flex items-center justify-center text-brand-forest shrink-0">
                        <i class="bi bi-info-circle-fill text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="block font-black text-sm leading-tight">Tentang</span>
                        <span class="block text-[10px] text-brand-forest/60 font-bold leading-tight mt-0.5">Sejarah &amp; Visi Jam'iyyah</span>
                    </div>
                </div>
                <i class="bi bi-chevron-right text-xs opacity-50"></i>
            </a>

            <a href="{{ route('kegiatan.index') }}" 
               @click="mobileMenuOpen = false"
               class="min-h-[48px] flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all text-decoration-none touch-manipulation {{ request()->routeIs('kegiatan.*') ? 'bg-brand-forest text-brand-cream border-2 border-brand-deep shadow-solid-xs' : 'hover:bg-brand-sand text-brand-forest' }}">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-lg {{ request()->routeIs('kegiatan.*') ? 'bg-brand-matcha text-white' : 'bg-brand-sand border border-brand-forest/20 text-brand-forest' }} flex items-center justify-center shrink-0">
                        <i class="bi bi-calendar-event-fill text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="block font-black text-sm leading-tight">Agenda Kegiatan</span>
                        <span class="block text-[10px] {{ request()->routeIs('kegiatan.*') ? 'text-brand-lightmatcha/80' : 'text-brand-forest/60' }} font-bold leading-tight mt-0.5">Jadwal &amp; Rangkaian Acara</span>
                    </div>
                </div>
                @if(request()->routeIs('kegiatan.*'))
                    <span class="px-2 py-0.5 bg-brand-matcha text-white text-[9px] font-black uppercase rounded-md tracking-wider">Aktif</span>
                @else
                    <i class="bi bi-chevron-right text-xs opacity-50"></i>
                @endif
            </a>

            <a href="{{ route('berita.index') }}" 
               @click="mobileMenuOpen = false"
               class="min-h-[48px] flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all text-decoration-none touch-manipulation {{ request()->routeIs('berita.*') ? 'bg-brand-forest text-brand-cream border-2 border-brand-deep shadow-solid-xs' : 'hover:bg-brand-sand text-brand-forest' }}">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-lg {{ request()->routeIs('berita.*') ? 'bg-brand-matcha text-white' : 'bg-brand-sand border border-brand-forest/20 text-brand-forest' }} flex items-center justify-center shrink-0">
                        <i class="bi bi-newspaper text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="block font-black text-sm leading-tight">Warta &amp; Berita</span>
                        <span class="block text-[10px] {{ request()->routeIs('berita.*') ? 'text-brand-lightmatcha/80' : 'text-brand-forest/60' }} font-bold leading-tight mt-0.5">Kabar &amp; Informasi Terkini</span>
                    </div>
                </div>
                @if(request()->routeIs('berita.*'))
                    <span class="px-2 py-0.5 bg-brand-matcha text-white text-[9px] font-black uppercase rounded-md tracking-wider">Aktif</span>
                @else
                    <i class="bi bi-chevron-right text-xs opacity-50"></i>
                @endif
            </a>

            <a href="{{ route('galeri.index') }}" 
               @click="mobileMenuOpen = false"
               class="min-h-[48px] flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all text-decoration-none touch-manipulation {{ request()->routeIs('galeri.*') ? 'bg-brand-forest text-brand-cream border-2 border-brand-deep shadow-solid-xs' : 'hover:bg-brand-sand text-brand-forest' }}">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-lg {{ request()->routeIs('galeri.*') ? 'bg-brand-matcha text-white' : 'bg-brand-sand border border-brand-forest/20 text-brand-forest' }} flex items-center justify-center shrink-0">
                        <i class="bi bi-images text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="block font-black text-sm leading-tight">Galeri Dokumentasi</span>
                        <span class="block text-[10px] {{ request()->routeIs('galeri.*') ? 'text-brand-lightmatcha/80' : 'text-brand-forest/60' }} font-bold leading-tight mt-0.5">Arsip Foto &amp; Video</span>
                    </div>
                </div>
                @if(request()->routeIs('galeri.*'))
                    <span class="px-2 py-0.5 bg-brand-matcha text-white text-[9px] font-black uppercase rounded-md tracking-wider">Aktif</span>
                @else
                    <i class="bi bi-chevron-right text-xs opacity-50"></i>
                @endif
            </a>

            <a href="{{ route('anggota.index') }}" 
               @click="mobileMenuOpen = false"
               class="min-h-[48px] flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all text-decoration-none touch-manipulation {{ request()->routeIs('anggota.*') && !request()->routeIs('anggota.daftar*') ? 'bg-brand-forest text-brand-cream border-2 border-brand-deep shadow-solid-xs' : 'hover:bg-brand-sand text-brand-forest' }}">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-lg {{ request()->routeIs('anggota.*') && !request()->routeIs('anggota.daftar*') ? 'bg-brand-matcha text-white' : 'bg-brand-sand border border-brand-forest/20 text-brand-forest' }} flex items-center justify-center shrink-0">
                        <i class="bi bi-people-fill text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="block font-black text-sm leading-tight">Direktori Anggota</span>
                        <span class="block text-[10px] {{ request()->routeIs('anggota.*') && !request()->routeIs('anggota.daftar*') ? 'text-brand-lightmatcha/80' : 'text-brand-forest/60' }} font-bold leading-tight mt-0.5">Database KARTANU Resmi</span>
                    </div>
                </div>
                @if(request()->routeIs('anggota.*') && !request()->routeIs('anggota.daftar*'))
                    <span class="px-2 py-0.5 bg-brand-matcha text-white text-[9px] font-black uppercase rounded-md tracking-wider">Aktif</span>
                @else
                    <i class="bi bi-chevron-right text-xs opacity-50"></i>
                @endif
            </a>
        </div>

        <!-- Submenu Accordion: Badan Otonom (Banom) -->
        <div class="rounded-xl border-2 border-brand-forest/20 overflow-hidden bg-brand-sand/50">
            <button type="button" 
                    @click="mobileBanomOpen = !mobileBanomOpen"
                    class="w-full min-h-[46px] flex items-center justify-between px-3.5 py-2.5 text-left transition-colors cursor-pointer select-none touch-manipulation">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-brand-sand border border-brand-forest/20 text-brand-forest flex items-center justify-center shrink-0">
                        <i class="bi bi-diagram-3-fill text-xs"></i>
                    </div>
                    <div>
                        <span class="block font-black text-xs text-brand-forest leading-tight">Badan Otonom (Banom)</span>
                        <span class="block text-[10px] text-brand-forest/60 font-bold leading-tight mt-0.5">Jelajahi Organisasi &amp; Banom</span>
                    </div>
                </div>
                <i class="bi bi-chevron-down text-xs text-brand-forest/70 transition-transform duration-200" :class="mobileBanomOpen ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="mobileBanomOpen" x-cloak class="px-2 pb-2.5 pt-1 space-y-1 border-t border-brand-forest/15 bg-white/70">
                <a href="{{ route('anggota.index', ['organisasi' => 'ansor']) }}" @click="mobileMenuOpen = false" class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-black {{ request('organisasi') === 'ansor' ? 'bg-brand-forest text-brand-cream shadow-solid-xs' : 'text-brand-forest hover:bg-brand-sand' }} transition-colors text-decoration-none touch-manipulation">
                    <span>GP Ansor &amp; Banser</span>
                    <i class="bi bi-arrow-right text-[10px] opacity-60"></i>
                </a>
                <a href="{{ route('anggota.index', ['organisasi' => 'fatayat']) }}" @click="mobileMenuOpen = false" class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-black {{ request('organisasi') === 'fatayat' ? 'bg-brand-forest text-brand-cream shadow-solid-xs' : 'text-brand-forest hover:bg-brand-sand' }} transition-colors text-decoration-none touch-manipulation">
                    <span>Fatayat NU</span>
                    <i class="bi bi-arrow-right text-[10px] opacity-60"></i>
                </a>
                <a href="{{ route('anggota.index', ['organisasi' => 'muslimat']) }}" @click="mobileMenuOpen = false" class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-black {{ request('organisasi') === 'muslimat' ? 'bg-brand-forest text-brand-cream shadow-solid-xs' : 'text-brand-forest hover:bg-brand-sand' }} transition-colors text-decoration-none touch-manipulation">
                    <span>Muslimat NU</span>
                    <i class="bi bi-arrow-right text-[10px] opacity-60"></i>
                </a>
                <a href="{{ route('anggota.index', ['organisasi' => 'ipnu']) }}" @click="mobileMenuOpen = false" class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-black {{ request('organisasi') === 'ipnu' ? 'bg-brand-forest text-brand-cream shadow-solid-xs' : 'text-brand-forest hover:bg-brand-sand' }} transition-colors text-decoration-none touch-manipulation">
                    <span>IPNU (Pelajar Putra)</span>
                    <i class="bi bi-arrow-right text-[10px] opacity-60"></i>
                </a>
                <a href="{{ route('anggota.index', ['organisasi' => 'ippnu']) }}" @click="mobileMenuOpen = false" class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-black {{ request('organisasi') === 'ippnu' ? 'bg-brand-forest text-brand-cream shadow-solid-xs' : 'text-brand-forest hover:bg-brand-sand' }} transition-colors text-decoration-none touch-manipulation">
                    <span>IPPNU (Pelajar Putri)</span>
                    <i class="bi bi-arrow-right text-[10px] opacity-60"></i>
                </a>
                <a href="{{ route('anggota.index', ['organisasi' => 'pagar_nusa']) }}" @click="mobileMenuOpen = false" class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-black {{ request('organisasi') === 'pagar_nusa' ? 'bg-brand-forest text-brand-cream shadow-solid-xs' : 'text-brand-forest hover:bg-brand-sand' }} transition-colors text-decoration-none touch-manipulation">
                    <span>Pagar Nusa (Pencak Silat)</span>
                    <i class="bi bi-arrow-right text-[10px] opacity-60"></i>
                </a>
            </div>
        </div>
        
        <!-- Mobile Action Buttons & User Controls -->
        <div class="pt-3 border-t-2 border-brand-sand flex flex-col gap-2">
            <a href="{{ route('anggota.daftar') }}" 
               @click="mobileMenuOpen = false"
               class="min-h-[48px] w-full text-center py-3 px-4 rounded-xl {{ request()->routeIs('anggota.daftar*') ? 'bg-brand-deep border-2 border-brand-matcha text-brand-cream shadow-solid-sm' : 'bg-brand-forest hover:bg-brand-deep text-white border-2 border-brand-deep shadow-solid-sm' }} font-black text-xs uppercase tracking-wider btn-solid-press text-decoration-none flex items-center justify-center gap-2 touch-manipulation">
                <i class="bi bi-person-plus-fill text-sm"></i>
                <span>Daftar Anggota (KARTANU)</span>
            </a>
            <a href="{{ route('anggota.cek-status') }}" 
               @click="mobileMenuOpen = false"
               class="min-h-[44px] w-full text-center py-2.5 px-4 rounded-xl border-2 border-brand-forest {{ request()->routeIs('anggota.cek-status*') ? 'bg-white shadow-solid-sm text-brand-forest' : 'bg-brand-sand hover:bg-white text-brand-forest shadow-solid-xs' }} text-xs font-black btn-solid-press text-decoration-none flex items-center justify-center gap-2 touch-manipulation">
                <i class="bi bi-search text-xs"></i>
                <span>Cek Status KARTANU</span>
            </a>
            @guest
                <a href="{{ route('login') }}" 
                   @click="mobileMenuOpen = false"
                   class="min-h-[44px] w-full text-center py-2.5 px-4 rounded-xl border-2 border-brand-forest {{ request()->routeIs('login') ? 'bg-brand-forest text-white shadow-solid-sm' : 'bg-white hover:bg-brand-cream text-brand-forest shadow-solid-xs' }} text-xs font-black btn-solid-press text-decoration-none flex items-center justify-center gap-2 touch-manipulation">
                    <i class="bi bi-box-arrow-in-right text-xs"></i>
                    <span>Masuk Akun Admin</span>
                </a>
            @else
                <div class="p-3.5 rounded-xl bg-white border-2 border-brand-forest shadow-solid-xs space-y-2.5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-lg bg-brand-forest text-white font-black text-xs flex items-center justify-center shrink-0 border border-brand-deep">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <span class="block text-xs font-black text-brand-deep truncate">{{ Auth::user()->name }}</span>
                                <span class="block text-[10px] font-bold text-brand-forest/70 capitalize">{{ Auth::user()->role }}</span>
                            </div>
                        </div>
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" @click="mobileMenuOpen = false" class="px-3 py-1.5 rounded-lg bg-brand-forest text-white text-[10px] font-black uppercase tracking-wider text-decoration-none border border-brand-deep shrink-0 touch-manipulation">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" @click="mobileMenuOpen = false" class="px-3 py-1.5 rounded-lg bg-brand-forest text-white text-[10px] font-black uppercase tracking-wider text-decoration-none border border-brand-deep shrink-0 touch-manipulation">
                                Profil
                            </a>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-brand-sand m-0">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 py-1.5 text-rose-600 hover:text-rose-700 text-xs font-black bg-rose-50 hover:bg-rose-100 rounded-lg transition-colors cursor-pointer border border-rose-200 touch-manipulation">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Keluar dari Akun</span>
                        </button>
                    </form>
                </div>
            @endguest
        </div>
    </div>
</header>

<!-- MOTION TRAIL TABS STYLES & INTERACTION CONTROLLER -->
<style>
    /* ==========================================================================
       RESPONSIVE DISPLAY ISOLATION (ZERO LEAKAGE ACROSS BREAKPOINTS)
       ========================================================================== */
    
    /* 1. Mobile Viewports (< 1024px): Desktop elements are 100% STRICTLY HIDDEN */
    .motion-trail-nav,
    #desktopNav,
    .desktop-cta-btn,
    .desktop-user-dropdown {
        display: none !important;
    }

    .mobile-hamburger-btn {
        display: inline-flex !important;
    }

    /* 2. Desktop Viewports (>= 1024px): Desktop nav is active; mobile elements are 100% STRICTLY HIDDEN */
    @media (min-width: 1024px) {
        .motion-trail-nav,
        #desktopNav {
            position: relative;
            display: inline-flex !important;
            align-items: center;
            background-color: rgba(236, 232, 221, 0.72);
            border: 2px solid rgba(20, 46, 37, 0.18);
            border-radius: 16px;
            padding: 4px;
            isolation: isolate;
        }

        .desktop-cta-btn {
            display: inline-flex !important;
        }

        .desktop-user-dropdown {
            display: block !important;
        }

        .mobile-hamburger-btn,
        .mobile-backdrop,
        .mobile-drawer {
            display: none !important;
        }
    }

    /* Individual Nav Link */
    .nav-trail-link {
        position: relative;
        z-index: 2;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 14px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 800;
        color: #142E25;
        text-decoration: none !important;
        cursor: pointer;
        white-space: nowrap;
        user-select: none;
        border: none !important;
        outline: none !important;
        transition: color 0.22s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Micro-interactions on Text */
    .nav-trail-link .nav-trail-text {
        display: inline-block;
        transition: transform 0.18s cubic-bezier(0.16, 1, 0.3, 1);
        pointer-events: none;
    }

    .nav-trail-link:hover .nav-trail-text {
        transform: translateY(-0.5px);
    }

    .nav-trail-link:active .nav-trail-text {
        transform: scale(0.96);
    }

    /* Highlighted State (when sliding pill is underneath) */
    .nav-trail-link.is-highlighted {
        color: #F6F4EE !important;
    }

    .nav-trail-link.is-highlighted .nav-trail-text {
        text-shadow: 0 1px 1px rgba(0, 0, 0, 0.12);
    }

    /* Static fallback before JS initialization */
    .nav-trail-link.is-active:not(.is-highlighted) {
        color: #142E25;
    }

    /* Sliding Pill Indicator */
    .motion-trail-indicator {
        position: absolute;
        top: 4px;
        left: 0;
        height: calc(100% - 8px);
        border-radius: 12px;
        background-color: #142E25;
        box-shadow: 2px 2px 0px rgba(14, 34, 27, 0.75);
        pointer-events: none;
        z-index: 1;
        opacity: 0;
        will-change: transform, width, opacity;
        /* Framer Spring Easing Curve */
        transition: transform 0.36s cubic-bezier(0.16, 1, 0.3, 1),
                    width 0.36s cubic-bezier(0.16, 1, 0.3, 1),
                    opacity 0.22s ease;
    }

    /* Motion Trail SVG */
    .motion-trail-svg {
        position: absolute;
        top: 0;
        height: 100%;
        pointer-events: none;
        overflow: visible;
        z-index: -1;
        opacity: 0;
        transition: opacity 0.28s ease;
    }

    .motion-trail-path {
        stroke: #142E25;
        stroke-linecap: round;
        vector-effect: non-scaling-stroke;
    }

    /* Accessibility: Respect Reduced Motion */
    @media (prefers-reduced-motion: reduce) {
        .motion-trail-indicator {
            transition: opacity 0.15s ease !important;
        }
        .motion-trail-svg {
            display: none !important;
        }
        .nav-trail-link .nav-trail-text {
            transition: none !important;
        }
    }
</style>

<script>
    (function() {
        function setupMotionTrailNavbar() {
            const nav = document.getElementById('desktopNav');
            if (!nav) return;

            // Run only for desktop viewport where desktopNav is visible
            if (window.innerWidth < 1024) return;

            const indicator = document.getElementById('motionTrailIndicator');
            const svg = document.getElementById('motionTrailSvg');
            const path1 = svg ? svg.querySelector('.motion-trail-path-1') : null;
            const path2 = svg ? svg.querySelector('.motion-trail-path-2') : null;
            const links = Array.from(nav.querySelectorAll('.nav-trail-link'));
            if (!links.length || !indicator) return;

            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            // Determine active item based on current URL / route / hash
            let activeLink = null;
            const isHomePage = window.location.pathname === '/' || window.location.pathname === '';
            
            function resolveCurrentActiveLink() {
                if (isHomePage) {
                    if (window.location.hash === '#tentang') {
                        return nav.querySelector('[data-nav-id="tentang"]');
                    }
                    return nav.querySelector('[data-nav-id="beranda"]');
                }
                return nav.querySelector('.nav-trail-link.is-active');
            }

            activeLink = resolveCurrentActiveLink();

            let currentHighlightedLink = null;
            let lastPositionIndex = activeLink ? links.indexOf(activeLink) : 0;
            let returnTimer = null;
            let stretchTimer = null;

            function getMetrics(link) {
                if (!link) return null;
                const navRect = nav.getBoundingClientRect();
                const linkRect = link.getBoundingClientRect();
                return {
                    left: linkRect.left - navRect.left,
                    width: linkRect.width,
                    index: links.indexOf(link)
                };
            }

            function triggerTrail(fromIndex, toIndex, direction) {
                if (prefersReducedMotion || !svg || fromIndex === toIndex) return;

                const fromLink = links[fromIndex];
                const toLink = links[toIndex];
                if (!fromLink || !toLink) return;

                const fromMetrics = getMetrics(fromLink);
                const toMetrics = getMetrics(toLink);
                if (!fromMetrics || !toMetrics) return;

                const pixelDistance = Math.abs(toMetrics.left - fromMetrics.left);
                const trailLength = Math.min(Math.max(pixelDistance * 0.45, 24), 85);

                svg.setAttribute('viewBox', `0 0 ${trailLength} 100`);
                svg.style.width = `${trailLength}px`;

                if (direction > 0) {
                    // Moving RIGHT: trail shoots to LEFT of indicator
                    svg.style.right = 'calc(100% + 4px)';
                    svg.style.left = 'auto';
                    if (path1) path1.setAttribute('d', `M ${trailLength} 36 L ${trailLength * 0.12} 36`);
                    if (path2) path2.setAttribute('d', `M ${trailLength} 64 L ${trailLength * 0.42} 64`);
                } else {
                    // Moving LEFT: trail shoots to RIGHT of indicator
                    svg.style.left = 'calc(100% + 4px)';
                    svg.style.right = 'auto';
                    if (path1) path1.setAttribute('d', `M 0 36 L ${trailLength * 0.88} 36`);
                    if (path2) path2.setAttribute('d', `M 0 64 L ${trailLength * 0.58} 64`);
                }

                // Official Framer Motion animate engine if loaded
                if (window.Motion && typeof window.Motion.animate === 'function') {
                    window.Motion.animate(svg, { opacity: [1, 1, 0] }, { duration: 0.32, ease: [0.22, 1, 0.36, 1] });
                    if (path1) window.Motion.animate(path1, { pathLength: [0, 1, 0] }, { duration: 0.32, ease: [0.22, 1, 0.36, 1] });
                    if (path2) window.Motion.animate(path2, { pathLength: [0, 1, 0] }, { duration: 0.32, delay: 0.02, ease: [0.22, 1, 0.36, 1] });
                } else {
                    // Fallback CSS animation
                    svg.style.opacity = '1';
                    setTimeout(() => { svg.style.opacity = '0'; }, 280);
                }
            }

            function moveIndicatorTo(link, animate = true, withTrail = true) {
                if (!link) {
                    indicator.style.opacity = '0';
                    links.forEach(l => l.classList.remove('is-highlighted'));
                    currentHighlightedLink = null;
                    return;
                }

                const metrics = getMetrics(link);
                if (!metrics) return;

                const targetIndex = metrics.index;
                const fromIndex = lastPositionIndex;
                const direction = targetIndex >= fromIndex ? 1 : -1;
                const distance = Math.abs(targetIndex - fromIndex);

                if (!animate || prefersReducedMotion) {
                    indicator.style.transition = 'none';
                    indicator.style.transform = `translate3d(${metrics.left}px, 0, 0)`;
                    indicator.style.width = `${metrics.width}px`;
                    indicator.style.opacity = '1';
                    void indicator.offsetHeight;
                    indicator.style.transition = '';
                } else {
                    if (withTrail && distance > 0) {
                        triggerTrail(fromIndex, targetIndex, direction);
                    }

                    // Framer-style micro-stretch during movement
                    if (distance > 0 && !prefersReducedMotion) {
                        indicator.style.transform = `translate3d(${metrics.left}px, 0, 0) scaleX(1.03)`;
                        clearTimeout(stretchTimer);
                        stretchTimer = setTimeout(() => {
                            indicator.style.transform = `translate3d(${metrics.left}px, 0, 0) scaleX(1)`;
                        }, 140);
                    } else {
                        indicator.style.transform = `translate3d(${metrics.left}px, 0, 0)`;
                    }

                    indicator.style.width = `${metrics.width}px`;
                    indicator.style.opacity = '1';
                }

                // Update highlighted text styling
                links.forEach(l => l.classList.remove('is-highlighted'));
                link.classList.add('is-highlighted');

                currentHighlightedLink = link;
                lastPositionIndex = targetIndex;
            }

            // Initial positioning on page load
            if (activeLink) {
                moveIndicatorTo(activeLink, false, false);
            } else {
                indicator.style.opacity = '0';
            }

            // Hover interactions
            links.forEach((link) => {
                link.addEventListener('mouseenter', () => {
                    clearTimeout(returnTimer);
                    moveIndicatorTo(link, true, true);
                });

                link.addEventListener('click', (e) => {
                    const href = link.getAttribute('href');
                    if (href && href.includes('#')) {
                        activeLink = link;
                    }
                    moveIndicatorTo(link, true, true);
                });
            });

            // Mouse leave navbar: spring back to active tab
            nav.addEventListener('mouseleave', () => {
                clearTimeout(returnTimer);
                returnTimer = setTimeout(() => {
                    if (activeLink) {
                        moveIndicatorTo(activeLink, true, true);
                    } else {
                        moveIndicatorTo(null, true, false);
                    }
                }, 120);
            });

            // Auto-reposition on viewport resize
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    const target = currentHighlightedLink || activeLink;
                    if (target) moveIndicatorTo(target, false, false);
                } else {
                    indicator.style.opacity = '0';
                }
            });

            // Homepage ScrollSpy for #beranda and #tentang
            if (isHomePage) {
                const berandaSec = document.getElementById('beranda');
                const tentangSec = document.getElementById('tentang');
                const berandaLink = nav.querySelector('[data-nav-id="beranda"]');
                const tentangLink = nav.querySelector('[data-nav-id="tentang"]');

                if (tentangSec && tentangLink && berandaLink) {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.target === tentangSec && entry.isIntersecting) {
                                activeLink = tentangLink;
                                if (!nav.matches(':hover')) {
                                    moveIndicatorTo(tentangLink, true, true);
                                }
                            } else if (entry.target === tentangSec && !entry.isIntersecting) {
                                if (window.scrollY < tentangSec.offsetTop - 150) {
                                    activeLink = berandaLink;
                                    if (!nav.matches(':hover')) {
                                        moveIndicatorTo(berandaLink, true, true);
                                    }
                                }
                            }
                        });
                    }, { threshold: 0.25 });

                    observer.observe(tentangSec);
                }
            }

            // Handle back/forward cache navigation
            window.addEventListener('pageshow', () => {
                activeLink = resolveCurrentActiveLink();
                if (activeLink) moveIndicatorTo(activeLink, false, false);
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupMotionTrailNavbar);
        } else {
            setupMotionTrailNavbar();
        }
    })();
</script>