<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Organisasi NU Wangon')</title>

    <!-- Google Fonts: EB Garamond, Manrope, Playfair Display, Plus Jakarta Sans, Lora -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Manrope:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Lora:ital,wght@0,500;0,600;0,700;1,500;1,600&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS (dipakai halaman Berita, Kegiatan, Admin, dll — JANGAN dihapus) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Tailwind CSS (dipakai homepage & navbar baru) -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#2D4736',
                            hover: '#23382B',
                            dark: '#1B2C22',
                            light: '#4E7A5A',
                            subtle: '#EDF2EE',
                        },
                        nu: {
                            DEFAULT: '#2D4736',
                            deep: '#23382B',
                            dark: '#1B2C22',
                            soft: '#4E7A5A',
                            subtle: '#EDF2EE',
                        },
                        ivory: {
                            DEFAULT: '#f9f8f4',
                            dark: '#f3f2ec',
                            border: '#E7E8E1',
                        },
                        charcoal: {
                            DEFAULT: '#191E1A',
                            muted: '#636B65',
                            light: '#8C948E',
                        },
                        gold: {
                            DEFAULT: '#C5A059',
                            dark: '#A6823B',
                            light: '#F8F4EB',
                        },
                        sand: {
                            bg: '#f9f8f4',
                            warm: '#f3f2ec',
                            muted: '#E7E8E1',
                        },
                        ink: {
                            DEFAULT: '#191E1A',
                            muted: '#636B65',
                            light: '#8C948E',
                        },
                        surface: '#fcfcfb',
                        'surface-tint': '#f3f2ec',
                        'border-editorial': '#E7E8E1',
                        brand: {
                            forest: '#142E25',
                            deep: '#0E221B',
                            matcha: '#2D4736',
                            lightmatcha: '#D9E8DD',
                            accent: '#D97706',
                            cream: '#F6F4EE',
                            sand: '#ECE8DD',
                            darktext: '#111A16',
                            border: '#233F34',
                            lightborder: '#D5CEC0',
                        },
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', '"Manrope"', 'system-ui', 'sans-serif'],
                        serif: ['"Playfair Display"', '"EB Garamond"', 'Georgia', 'serif'],
                    },
                    boxShadow: {
                        'soft': '0 12px 36px -8px rgba(45, 71, 54, 0.08)',
                        'float': '0 20px 48px -12px rgba(45, 71, 54, 0.14)',
                        'pill': '0 4px 16px -2px rgba(45, 71, 54, 0.12)',
                        'card': '0 1px 3px rgba(25, 30, 26, 0.04), 0 4px 12px rgba(25, 30, 26, 0.03)',
                        'solid-sm': '3px 3px 0px #142E25',
                        'solid-md': '5px 5px 0px #142E25',
                        'solid-lg': '7px 7px 0px #142E25',
                        'solid-accent': '5px 5px 0px #0E221B',
                        'solid-light': '4px 4px 0px #C7C0B0',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js (dipakai navbar & modal di homepage) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Motion (Framer Motion official engine for DOM/vanilla) -->
    <script src="https://cdn.jsdelivr.net/npm/motion@11.11.17/dist/motion.js"></script>
    <!-- Lenis Smooth Scroll -->
    <script src="https://cdn.jsdelivr.net/gh/darkroomengineering/lenis@1.1.13/dist/lenis.min.js"></script>

    <style>
        :root {
            --nu-primary: #2D4736;
            --nu-primary-hover: #23382B;
            --nu-primary-dark: #1B2C22;
            --nu-primary-darker: #132219;
            --nu-primary-light: #EDF2EE;
            --nu-primary-subtle: #F3F3ED;
            --nu-accent-gold: #C5A059;
            --nu-accent-gold-subtle: rgba(197, 160, 89, 0.12);
            --nu-border: #E7E8E1;
            --nu-border-subtle: rgba(45, 71, 54, 0.12);
            --nu-text-main: #191E1A;
            --nu-text-muted: #636B65;
            --nu-bg-body: #f9f8f4;
            --nu-bg-card: #fcfcfb;

            --font-sans: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-display: 'Playfair Display', Georgia, 'Times New Roman', serif;

            --nu-radius: 1rem;
            --nu-radius-sm: 0.625rem;
            --nu-radius-lg: 1.5rem;
            --nu-radius-pill: 50rem;

            --nu-shadow-2xs: 0 1px 2px rgba(25, 30, 26, 0.04);
            --nu-shadow-sm: 0 1px 3px rgba(25, 30, 26, 0.04), 0 1px 2px rgba(25, 30, 26, 0.02);
            --nu-shadow: 0 6px 20px -6px rgba(45, 71, 54, 0.08), 0 2px 6px rgba(25, 30, 26, 0.04);
            --nu-shadow-lg: 0 16px 36px -10px rgba(45, 71, 54, 0.12), 0 6px 14px -4px rgba(25, 30, 26, 0.05);
            --nu-shadow-hover: 0 12px 28px -8px rgba(45, 71, 54, 0.12), 0 4px 10px rgba(25, 30, 26, 0.04);
        }

        .glass-nav {
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            background-color: rgba(251, 251, 249, 0.95);
        }

        /* Solid-weight design system helpers */
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
        .border-brand-border { border-color: #233F34; }

        .shadow-solid-sm { box-shadow: 3px 3px 0px #142E25; }
        .shadow-solid-md { box-shadow: 5px 5px 0px #142E25; }
        .shadow-solid-lg { box-shadow: 7px 7px 0px #142E25; }
        .shadow-solid-accent { box-shadow: 5px 5px 0px #0E221B; }
        .shadow-solid-light { box-shadow: 4px 4px 0px #C7C0B0; }

        .btn-solid-press {
            transition: transform 0.1s ease, box-shadow 0.1s ease;
        }
        .btn-solid-press:active {
            transform: translate(2px, 2px);
            box-shadow: 1px 1px 0px #142E25 !important;
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

        /* Responsive 2-Column News & Related Grid (<360px Ultra-Compact Fallback) */
        @media (max-width: 360px) {
            .responsive-news-grid,
            .related-content-grid {
                grid-template-columns: 1fr !important;
            }
            .responsive-news-grid > article,
            .related-content-grid > article {
                grid-column: 1 / -1 !important;
            }
        }

        /* Subtle natural contrast separation for transparent logo on dark green background */
        .logo-contrast-subtle {
            filter: drop-shadow(0 1px 2px rgba(217, 232, 221, 0.45));
            -webkit-filter: drop-shadow(0 1px 2px rgba(217, 232, 221, 0.45));
        }

        * {
            box-sizing: border-box;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 96px;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--nu-bg-body);
            color: var(--nu-text-main);
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            line-height: 1.6;
        }

        [x-cloak] { display: none !important; }

        :root {
            --nav-height: 80px;
        }

        @media (max-width: 1023px) {
            :root {
                --nav-height: 64px;
            }
        }

        main.main-content {
            flex: 1 0 auto;
            padding-top: var(--nav-height);
            padding-top: calc(var(--nav-height) + env(safe-area-inset-top, 0px));
        }

        .serif-title {
            font-family: 'EB Garamond', Georgia, serif;
            letter-spacing: -0.015em;
        }
        .hairline-b {
            border-bottom: 1px solid rgba(20, 30, 23, 0.08);
        }
        .hairline-t {
            border-top: 1px solid rgba(20, 30, 23, 0.08);
        }
        .hairline-white-b {
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }
        .hairline-white-t {
            border-top: 1px solid rgba(255, 255, 255, 0.12);
        }
        .reveal-item {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.75s cubic-bezier(0.16, 1, 0.3, 1), transform 0.75s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }
        .reveal-item.is-revealed {
            opacity: 1;
            transform: translateY(0);
        }

        .font-display,
        .display-heading,
        .hero-title,
        .section-title {
            font-family: var(--font-display);
            letter-spacing: -0.015em;
        }
        .font-est-serif {
            font-family: 'Playfair Display', serif !important;
        }

        h1, h2, h3, h4, h5, h6 {
            color: var(--nu-text-main);
            font-weight: 700;
        }

        .nu-pattern-subtle {
            background-color: #ffffff;
            background-image: radial-gradient(rgba(45, 71, 54, 0.08) 1.2px, transparent 1.2px);
            background-size: 24px 24px;
        }
        .nu-pattern-dark {
            background-color: #23382B;
            background-image: radial-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px);
            background-size: 20px 20px;
        }

        .text-success-nu, .text-nu-primary { color: var(--nu-primary) !important; }
        .bg-success-nu, .bg-nu-primary { background-color: var(--nu-primary) !important; color: #ffffff !important; }
        .bg-nu-subtle { background-color: var(--nu-primary-subtle) !important; }

        .btn-nu-primary {
            background-color: var(--nu-primary);
            border: 1.5px solid var(--nu-primary);
            color: #ffffff !important;
            font-weight: 600;
            border-radius: var(--nu-radius-pill);
            padding: 0.65rem 1.4rem;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 12px rgba(45, 71, 54, 0.2);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            line-height: 1.25;
            white-space: nowrap;
        }
        .btn-nu-primary:hover {
            background-color: var(--nu-primary-hover);
            border-color: var(--nu-primary-hover);
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(45, 71, 54, 0.28);
        }
        .btn-nu-primary:active {
            transform: translateY(0) scale(0.98);
            box-shadow: 0 2px 6px rgba(45, 71, 54, 0.2);
        }

        .btn-nu-outline {
            background-color: #ffffff;
            border: 1.5px solid #cbd5e1;
            color: #334155 !important;
            font-weight: 600;
            border-radius: var(--nu-radius-pill);
            padding: 0.65rem 1.4rem;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            line-height: 1.25;
            white-space: nowrap;
        }
        .btn-nu-outline:hover {
            background-color: #f8fafc;
            border-color: var(--nu-primary);
            color: var(--nu-primary) !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.05);
        }
        .btn-nu-outline:active { transform: translateY(0) scale(0.98); }

        .card-nu {
            background: var(--nu-bg-card);
            border: 1px solid var(--nu-border);
            border-radius: var(--nu-radius);
            box-shadow: var(--nu-shadow-sm);
            transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.28s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.28s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .hover-lift {
            transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.28s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.28s ease;
        }
        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: var(--nu-shadow-hover) !important;
            border-color: rgba(0, 133, 66, 0.2) !important;
        }

        .hover-zoom-container { overflow: hidden; }
        .hover-zoom-container img { transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1); }
        .hover-zoom-container:hover img { transform: scale(1.04); }

        .reveal-on-scroll {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.65s cubic-bezier(0.16, 1, 0.3, 1), transform 0.65s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }
        .reveal-on-scroll.is-revealed { opacity: 1; transform: translateY(0); }

        .reveal-fade { opacity: 0; transition: opacity 0.65s cubic-bezier(0.16, 1, 0.3, 1); }
        .reveal-fade.is-revealed { opacity: 1; }

        .reveal-scale {
            opacity: 0;
            transform: scale(0.96);
            transition: opacity 0.65s cubic-bezier(0.16, 1, 0.3, 1), transform 0.65s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-scale.is-revealed { opacity: 1; transform: scale(1); }

        .delay-100 { transition-delay: 100ms; }
        .delay-150 { transition-delay: 150ms; }
        .delay-200 { transition-delay: 200ms; }
        .delay-250 { transition-delay: 250ms; }
        .delay-300 { transition-delay: 300ms; }
        .delay-400 { transition-delay: 400ms; }
        .delay-500 { transition-delay: 500ms; }
        .delay-600 { transition-delay: 600ms; }

        .stagger-children > * {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.55s cubic-bezier(0.16, 1, 0.3, 1), transform 0.55s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .stagger-children.is-revealed > *:nth-child(1) { transition-delay: 60ms; }
        .stagger-children.is-revealed > *:nth-child(2) { transition-delay: 120ms; }
        .stagger-children.is-revealed > *:nth-child(3) { transition-delay: 180ms; }
        .stagger-children.is-revealed > *:nth-child(4) { transition-delay: 240ms; }
        .stagger-children.is-revealed > *:nth-child(5) { transition-delay: 300ms; }
        .stagger-children.is-revealed > *:nth-child(6) { transition-delay: 360ms; }
        .stagger-children.is-revealed > *:nth-child(7) { transition-delay: 420ms; }
        .stagger-children.is-revealed > *:nth-child(8) { transition-delay: 480ms; }
        .stagger-children.is-revealed > *:nth-child(9) { transition-delay: 540ms; }
        .stagger-children.is-revealed > * { opacity: 1; transform: translateY(0); }

        .reveal-left {
            opacity: 0;
            transform: translateX(-30px);
            transition: opacity 0.65s cubic-bezier(0.16, 1, 0.3, 1), transform 0.65s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }
        .reveal-left.is-revealed { opacity: 1; transform: translateX(0); }

        .reveal-right {
            opacity: 0;
            transform: translateX(30px);
            transition: opacity 0.65s cubic-bezier(0.16, 1, 0.3, 1), transform 0.65s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }
        .reveal-right.is-revealed { opacity: 1; transform: translateX(0); }

        .page-hero-section {
            background: linear-gradient(135deg, var(--nu-primary-darker) 0%, var(--nu-primary) 60%, var(--nu-primary-hover) 100%);
            padding: 3rem 0 2.5rem;
            position: relative;
            overflow: hidden;
        }
        .page-hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255,255,255,0.07) 1px, transparent 1px);
            background-size: 20px 20px;
            pointer-events: none;
        }
        .page-hero-title {
            font-family: var(--font-display);
            color: #ffffff;
            font-weight: 700;
            letter-spacing: -0.02em;
            line-height: 1.2;
        }
        .page-hero-subtitle { color: rgba(255, 255, 255, 0.8); max-width: 540px; }

        .nu-section-divider {
            width: 48px;
            height: 4px;
            background: var(--nu-primary);
            border-radius: 2px;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .form-nu-card {
            background: var(--nu-bg-card);
            border: 1px solid var(--nu-border);
            border-radius: var(--nu-radius-lg);
            box-shadow: var(--nu-shadow);
            padding: 2rem;
        }
        @media (min-width: 768px) { .form-nu-card { padding: 2.5rem; } }

        .form-section-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--nu-border);
        }
        .form-section-number {
            width: 28px;
            height: 28px;
            background-color: var(--nu-primary);
            color: #ffffff;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.78rem;
            font-weight: 700;
            flex-shrink: 0;
        }
        .form-section-title { font-weight: 700; color: var(--nu-text-main); font-size: 1rem; margin-bottom: 0; }

        .form-control:focus, .form-select:focus {
            border-color: var(--nu-primary);
            box-shadow: 0 0 0 0.2rem rgba(45, 71, 54, 0.12);
        }
        .form-control, .form-select {
            border-radius: 0.625rem;
            border-color: var(--nu-border);
            transition: border-color 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease;
        }
        .form-label { font-weight: 600; color: var(--nu-text-main); font-size: 0.875rem; margin-bottom: 0.35rem; }
        .form-text { font-size: 0.75rem; color: var(--nu-text-muted); margin-top: 0.3rem; }

        .stat-card-premium {
            background: var(--nu-bg-card);
            border: 1px solid rgba(0, 0, 0, 0.04);
            border-radius: var(--nu-radius);
            padding: 1.15rem;
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .stat-card-premium:hover { transform: translateY(-3px); box-shadow: var(--nu-shadow); }

        .page-hero-description { color: rgba(255, 255, 255, 0.75); font-size: 0.95rem; line-height: 1.6; }

        .link-underline-effect { position: relative; text-decoration: none; }
        .link-underline-effect::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--nu-primary);
            border-radius: 1px;
            transition: width 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .link-underline-effect:hover::after { width: 100%; }

        .card-image-overlay {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.55));
            padding: 3rem 1.25rem 1rem;
            pointer-events: none;
        }


        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
            .reveal-on-scroll, .reveal-fade, .reveal-scale, .reveal-left, .reveal-right, .stagger-children > *, .reveal-item {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }
            .stagger-children > * { transition-delay: 0ms !important; }
        }
    </style>
</head>
<body>

    @include('layouts.navigation')

    <main class="main-content{{ request()->routeIs('home') ? ' main-content--flush' : '' }}">
    @yield('content')
</main>

    @include('layouts.footer')

    <!-- Bootstrap JS (dipakai halaman lain — JANGAN dihapus) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            // 0. LENIS SMOOTH SCROLL ENGINE (Singleton guarded)
            if (typeof Lenis !== 'undefined' && !prefersReducedMotion) {
                if (!window.lenisInstance) {
                    window.lenisInstance = new Lenis({
                        duration: 1.15,
                        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
                        smoothWheel: true,
                    });
                    function raf(time) {
                        if (window.lenisInstance) {
                            window.lenisInstance.raf(time);
                            requestAnimationFrame(raf);
                        }
                    }
                    requestAnimationFrame(raf);
                }
            }

            // 3. Scroll Reveal Observer
            if ('IntersectionObserver' in window) {
                const revealElements = document.querySelectorAll('.reveal-on-scroll, .reveal-fade, .reveal-scale, .reveal-left, .reveal-right, .stagger-children, .reveal-item, .floating-reveal');
                const revealObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-revealed');
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

                revealElements.forEach(el => revealObserver.observe(el));
            } else {
                document.querySelectorAll('.reveal-on-scroll, .reveal-fade, .reveal-scale, .reveal-left, .reveal-right, .stagger-children, .reveal-item, .floating-reveal')
                    .forEach(el => el.classList.add('is-revealed'));
            }

            // 4. Animated Counter untuk elemen [data-counter]
            const counterElements = document.querySelectorAll('[data-counter]');
            if (counterElements.length > 0 && 'IntersectionObserver' in window) {
                const counterObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const el = entry.target;
                            const targetVal = parseInt(el.getAttribute('data-counter'), 10);
                            const suffix = el.getAttribute('data-suffix') || '';
                            const duration = 1400;
                            const startTime = performance.now();

                            const updateCounter = (currentTime) => {
                                const elapsed = currentTime - startTime;
                                const progress = Math.min(elapsed / duration, 1);
                                const easeProgress = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
                                const currentVal = Math.floor(easeProgress * targetVal);
                                el.textContent = currentVal.toLocaleString('id-ID') + suffix;

                                if (progress < 1) {
                                    requestAnimationFrame(updateCounter);
                                } else {
                                    el.textContent = targetVal.toLocaleString('id-ID') + suffix;
                                }
                            };
                            requestAnimationFrame(updateCounter);
                            observer.unobserve(el);
                        }
                    });
                }, { threshold: 0.3 });

                counterElements.forEach(el => counterObserver.observe(el));
            }

            // 5. Smooth Scroll untuk anchor link dalam halaman
            document.addEventListener('click', (event) => {
                const anchor = event.target.closest('a[href^="#"]');
                if (!anchor) return;

                const hash = anchor.getAttribute('href');
                if (!hash || hash === '#') return;

                const target = document.getElementById(hash.slice(1));
                if (!target) return;

                event.preventDefault();

                const navbar = document.getElementById('navbarMain');
                const collapse = navbar && window.bootstrap && bootstrap.Collapse.getInstance(navbar);
                if (collapse) collapse.hide();

                if (window.lenisInstance) {
                    window.lenisInstance.scrollTo(target, { offset: -96 });
                } else {
                    target.scrollIntoView({
                        behavior: prefersReducedMotion ? 'auto' : 'smooth',
                        block: 'start',
                    });
                }

                history.replaceState(null, '', hash);
            });
        });
    </script>

    {{-- PLAYFUL MAGNETIC & CLICK INTERACTION ENGINE (ALL BUTTONS, STRICT NAVBAR EXCLUSION) --}}
    @include('layouts.partials.magnetic-interaction')

    {{-- TEXT ANIMATE ENGINE (WORD-BY-WORD STAGGER, BLURINUP, ACCESSIBLE FOR ALL HEADINGS) --}}
    @include('layouts.partials.text-animate')

    {{-- NUMBER TICKER ENGINE (ACCESSIBLE, SMOOTH STATISTICAL COUNTER FOR ALL USER-FACING METRICS) --}}
    @include('layouts.partials.number-ticker')

    {{-- PARALLAX SCROLL ENGINE (SUBTLE, SMOOTH DEPTH ACCELERATION FOR VISUAL ELEMENTS) --}}
    @include('layouts.partials.parallax-scroll')
</body>
</html>