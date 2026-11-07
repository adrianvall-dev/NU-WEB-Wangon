<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - Organisasi NU Wangon')</title>

    <!-- Google Fonts: Plus Jakarta Sans & Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3.3 & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    @stack('styles')

    <!-- Motion JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/motion@11.11.17/dist/motion.js"></script>

    <style>
        :root {
            /* SOLID GROTESQUE PALETTE (HTML #1 & #2 SPEC) */
            --brand-forest: #142E25;
            --brand-forest-deep: #0E221B;
            --brand-sage: #2D4736;
            --brand-matcha: #D9E8DD;
            --brand-cream: #F6F4EE;
            --brand-sand: #ECE8DD;
            --brand-orange: #F38848;
            --brand-amber: #D97706;
            --brand-yellow: #FFD56C;
            --brand-yellow-light: #FFF8E1;
            --brand-white: #FFFFFF;
            --brand-border: #142E25;
            --brand-border-muted: #D5D7CC;
            --brand-danger: #EF4444;
            --brand-danger-bg: #FEE2E2;

            --font-heading: 'Playfair Display', Georgia, serif;
            --font-body: 'Plus Jakarta Sans', system-ui, sans-serif;
        }

        * {
            box-sizing: border-box;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            font-family: var(--font-body);
            background-color: var(--brand-cream);
            color: var(--brand-forest);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .font-serif {
            font-family: var(--font-heading) !important;
        }

        /* TEXT OVERFLOW & RESPONSIVE WRAPPING RULES */
        .text-safe {
            max-width: 100%;
            overflow-wrap: anywhere;
            word-break: break-word;
            white-space: normal;
            line-height: 1.4;
        }

        .min-w-0 {
            min-width: 0 !important;
        }

        /* HARD SHADOW UTILITIES */
        .hard-shadow-xs {
            box-shadow: 2px 2px 0px var(--brand-forest);
        }
        .hard-shadow-sm {
            box-shadow: 3px 3px 0px var(--brand-forest);
        }
        .hard-shadow {
            box-shadow: 5px 5px 0px var(--brand-forest);
        }
        .hard-shadow-lg {
            box-shadow: 7px 7px 0px var(--brand-forest);
        }

        /* TACTILE PRESS BUTTONS */
        .btn-solid-press {
            transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .btn-solid-press:hover {
            transform: translate(-1px, -1px);
            box-shadow: 4px 4px 0px var(--brand-forest);
        }
        .btn-solid-press:active {
            transform: translate(2px, 2px);
            box-shadow: 1px 1px 0px var(--brand-forest);
        }

        .btn-solid-primary {
            background-color: var(--brand-forest);
            color: #FFFFFF !important;
            border: 2px solid var(--brand-forest);
            border-radius: 12px;
            padding: 9px 20px;
            font-weight: 700;
            font-size: 0.86rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            box-shadow: 3px 3px 0px var(--brand-forest);
            cursor: pointer;
        }
        .btn-solid-primary:hover {
            background-color: var(--brand-forest-deep);
            color: #FFFFFF !important;
        }

        .btn-solid-accent {
            background-color: var(--brand-orange);
            color: #FFFFFF !important;
            border: 2px solid var(--brand-forest);
            border-radius: 12px;
            padding: 9px 20px;
            font-weight: 700;
            font-size: 0.86rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            box-shadow: 3px 3px 0px var(--brand-forest);
            cursor: pointer;
        }
        .btn-solid-accent:hover {
            background-color: #E07335;
            color: #FFFFFF !important;
        }

        .btn-solid-yellow {
            background-color: var(--brand-yellow);
            color: var(--brand-forest) !important;
            border: 2px solid var(--brand-forest);
            border-radius: 12px;
            padding: 9px 20px;
            font-weight: 700;
            font-size: 0.86rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            box-shadow: 3px 3px 0px var(--brand-forest);
            cursor: pointer;
        }
        .btn-solid-yellow:hover {
            background-color: #FACC15;
            color: var(--brand-forest) !important;
        }

        .btn-solid-light {
            background-color: #FFFFFF;
            color: var(--brand-forest) !important;
            border: 2px solid var(--brand-forest);
            border-radius: 12px;
            padding: 9px 20px;
            font-weight: 700;
            font-size: 0.86rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            box-shadow: 3px 3px 0px var(--brand-forest);
            cursor: pointer;
        }
        .btn-solid-light:hover {
            background-color: var(--brand-sand);
            color: var(--brand-forest) !important;
        }

        .btn-solid-danger {
            background-color: var(--brand-danger-bg);
            color: #991B1B !important;
            border: 2px solid var(--brand-forest);
            border-radius: 12px;
            padding: 8px 16px;
            font-weight: 700;
            font-size: 0.84rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            box-shadow: 3px 3px 0px var(--brand-forest);
            cursor: pointer;
        }
        .btn-solid-danger:hover {
            background-color: #FCA5A5;
            color: #7F1D1D !important;
        }

        .btn-solid-sm {
            padding: 6px 14px;
            font-size: 0.78rem;
            border-radius: 10px;
        }

        /* SOLID BADGES */
        .badge-solid {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 9999px;
            font-weight: 700;
            font-size: 0.72rem;
            border: 1.5px solid var(--brand-forest);
            line-height: 1.2;
        }
        .badge-solid-forest {
            background-color: var(--brand-forest);
            color: #FFFFFF;
        }
        .badge-solid-matcha {
            background-color: var(--brand-matcha);
            color: var(--brand-forest);
        }
        .badge-solid-yellow {
            background-color: var(--brand-yellow);
            color: var(--brand-forest);
        }
        .badge-solid-orange {
            background-color: var(--brand-orange);
            color: #FFFFFF;
        }
        .badge-solid-light {
            background-color: #FFFFFF;
            color: var(--brand-forest);
        }
        .badge-solid-danger {
            background-color: var(--brand-danger-bg);
            color: #991B1B;
        }

        /* FORM CONTROLS SOLID */
        .form-control-solid,
        .form-select-solid {
            background: #FFFFFF;
            border: 2px solid var(--brand-forest);
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 0.88rem;
            font-weight: 500;
            color: var(--brand-forest);
            box-shadow: 2px 2px 0px rgba(20, 46, 37, 0.15);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            width: 100%;
        }
        .form-control-solid:focus,
        .form-select-solid:focus {
            background: #FFFFFF;
            border-color: var(--brand-forest);
            box-shadow: 4px 4px 0px var(--brand-forest);
            outline: none;
            color: var(--brand-forest);
        }
        .form-label-solid {
            font-size: 0.78rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--brand-forest);
            margin-bottom: 6px;
            display: block;
        }

        /* SOLID TABLE CONTAINER */
        .table-solid-wrapper {
            border: 2px solid var(--brand-forest);
            border-radius: 16px;
            box-shadow: 4px 4px 0px var(--brand-forest);
            background: #FFFFFF;
            overflow: hidden;
            width: 100%;
        }
        .table-solid {
            width: 100%;
            margin-bottom: 0;
            border-collapse: collapse;
        }
        .table-solid thead th {
            background-color: var(--brand-sand);
            color: var(--brand-forest);
            border-bottom: 2px solid var(--brand-forest);
            border-top: none;
            padding: 13px 16px;
            font-size: 0.76rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .table-solid tbody td {
            padding: 14px 16px;
            vertical-align: middle;
            border-bottom: 1.5px solid var(--brand-sand);
            color: var(--brand-forest);
            font-size: 0.86rem;
            background: #FFFFFF;
        }
        .table-solid tbody tr:last-child td {
            border-bottom: none;
        }
        .table-solid tbody tr:hover td {
            background-color: var(--brand-cream);
        }

        /* TABLE AVATAR THUMBNAIL (ORGANISASI / ANGGOTA) */
        .table-avatar-thumb {
            width: 64px;
            height: 64px;
            min-width: 64px;
            max-width: 64px;
            min-height: 64px;
            max-height: 64px;
            border-radius: 12px;
            border: 2px solid var(--brand-forest);
            background-color: var(--brand-matcha);
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: 2px 2px 0px var(--brand-forest);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .table-avatar-thumb img {
            width: 100% !important;
            height: 100% !important;
            max-width: 100% !important;
            max-height: 100% !important;
            object-fit: cover !important;
            object-position: center !important;
            display: block;
        }

        /* CARD AVATARS (KETUA & WAKIL KETUA) */
        .card-pimpinan-avatar {
            width: 76px;
            height: 76px;
            min-width: 76px;
            max-width: 76px;
            min-height: 76px;
            max-height: 76px;
            border-radius: 16px;
            border: 2px solid var(--brand-forest);
            background-color: var(--brand-matcha);
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: 3px 3px 0px var(--brand-forest);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .card-pimpinan-avatar img {
            width: 100% !important;
            height: 100% !important;
            max-width: 100% !important;
            max-height: 100% !important;
            object-fit: cover !important;
            object-position: center !important;
            display: block;
        }

        /* ORGANISASI LOGO THUMB */
        .organisasi-logo-thumb {
            width: 88px;
            height: 88px;
            min-width: 88px;
            max-width: 88px;
            min-height: 88px;
            max-height: 88px;
            border-radius: 18px;
            border: 2px solid var(--brand-forest);
            background-color: var(--brand-matcha);
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: 3px 3px 0px var(--brand-forest);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .organisasi-logo-thumb img {
            width: 100% !important;
            height: 100% !important;
            max-width: 100% !important;
            max-height: 100% !important;
            object-fit: cover !important;
            object-position: center !important;
            display: block;
        }

        /* UTILITY FALLBACKS FOR ROBUSTNESS */
        .w-full { width: 100% !important; }
        .h-full { height: 100% !important; }
        .object-cover { object-fit: cover !important; }
        .border-bottom-solid { border-bottom: 2px solid var(--brand-sand) !important; }
        .border-top-solid { border-top: 2px solid var(--brand-sand) !important; }

        /* SOLID STAT CARD */
        .solid-stat-card {
            background: #FFFFFF;
            border: 2px solid var(--brand-forest);
            border-radius: 16px;
            padding: 18px;
            box-shadow: 3px 3px 0px var(--brand-forest);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }
        .solid-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 5px 5px 0px var(--brand-forest);
        }

        /* 4-CARD BALANCED STAT GRID (DESKTOP: 4, TABLET: 2x2, MOBILE: 1) */
        .stat-grid-4 {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        @media (max-width: 991.98px) {
            .stat-grid-4 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (max-width: 575.98px) {
            .stat-grid-4 {
                grid-template-columns: minmax(0, 1fr);
            }
        }

        /* PAGINATION SOLID STYLING & LEAK PREVENTION */
        .pagination {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-bottom: 0;
            list-style: none;
            padding-left: 0;
        }
        .page-item .page-link {
            background: #FFFFFF;
            color: var(--brand-forest);
            border: 2px solid var(--brand-forest);
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 6px 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 2px 2px 0px var(--brand-forest);
            transition: all 0.15s ease;
        }
        .page-item.active .page-link {
            background: var(--brand-forest);
            color: #FFFFFF;
        }
        .page-item.disabled .page-link {
            opacity: 0.45;
            pointer-events: none;
            box-shadow: none;
        }
        nav[role="navigation"] svg {
            width: 1.1rem !important;
            height: 1.1rem !important;
            max-width: 18px !important;
            max-height: 18px !important;
            vertical-align: middle;
        }
        .sm\:hidden {
            display: none !important;
        }

        /* SOLID CARDS */
        .card-solid {
            background: #FFFFFF;
            border: 2px solid var(--brand-forest);
            border-radius: 20px;
            box-shadow: 5px 5px 0px var(--brand-forest);
            padding: 24px;
        }
        .card-solid-cream {
            background: var(--brand-cream);
            border: 2px solid var(--brand-forest);
            border-radius: 20px;
            box-shadow: 5px 5px 0px var(--brand-forest);
            padding: 24px;
        }
        .card-solid-sm {
            padding: 16px;
            border-radius: 16px;
            box-shadow: 3px 3px 0px var(--brand-forest);
        }

        /* FLASH ALERTS */
        .alert-solid-success {
            background-color: var(--brand-matcha);
            border: 2px solid var(--brand-forest);
            border-radius: 14px;
            padding: 14px 18px;
            color: var(--brand-forest);
            font-weight: 600;
            box-shadow: 4px 4px 0px var(--brand-forest);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .alert-solid-danger {
            background-color: var(--brand-danger-bg);
            border: 2px solid var(--brand-forest);
            border-radius: 14px;
            padding: 14px 18px;
            color: #991B1B;
            font-weight: 600;
            box-shadow: 4px 4px 0px var(--brand-forest);
            margin-bottom: 20px;
        }

        /* ========================================================
           SIDEBAR ARCHITECTURE (HTML #2 NEOBRUTALIST SPEC)
           ======================================================== */
        .sidebar-wrapper {
            position: sticky;
            top: 16px;
            height: calc(100vh - 32px);
            z-index: 100;
        }

        .sidebar-card-solid {
            height: 100%;
            background: var(--brand-cream);
            border: 2.5px solid var(--brand-forest);
            border-radius: 22px;
            padding: 18px 14px;
            box-shadow: 5px 5px 0px var(--brand-forest);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow-y: auto;
            scrollbar-width: thin;
        }

        .sidebar-brand-solid {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 8px;
            text-decoration: none;
            margin-bottom: 14px;
        }

        .sidebar-brand-logo-solid {
            width: 40px;
            height: 40px;
            background: #FFFFFF;
            border: 2px solid var(--brand-forest);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 2px 2px 0px var(--brand-forest);
        }

        .sidebar-brand-title-solid {
            font-family: var(--font-heading);
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--brand-forest);
            line-height: 1.15;
            letter-spacing: -0.01em;
        }

        .sidebar-user-box-solid {
            background: #FFFFFF;
            border: 2px solid var(--brand-forest);
            border-radius: 14px;
            padding: 10px 12px;
            box-shadow: 3px 3px 0px var(--brand-forest);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-user-avatar-solid {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--brand-forest);
            color: #FFFFFF;
            font-weight: 800;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--brand-forest);
            flex-shrink: 0;
            position: relative;
        }

        .sidebar-user-avatar-solid .status-dot {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 10px;
            height: 10px;
            background-color: #22C55E;
            border: 2px solid #FFFFFF;
            border-radius: 50%;
        }

        /* NAVIGATION GROUPING (HTML #2 SPEC) */
        .sidebar-nav-group-title {
            font-size: 0.68rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(20, 46, 37, 0.65);
            padding: 0 8px;
            margin-top: 12px;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .sidebar-nav-group-title::after {
            content: '';
            flex: 1;
            height: 1.5px;
            background-color: var(--brand-border-muted);
        }

        .sidebar-nav-list-solid {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .sidebar-nav-link-solid {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 12px;
            border-radius: 11px;
            color: var(--brand-forest);
            font-size: 0.85rem;
            font-weight: 700;
            text-decoration: none;
            border: 2px solid transparent;
            transition: all 0.16s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .sidebar-nav-link-solid:hover {
            background-color: var(--brand-sand);
            border-color: var(--brand-forest);
            color: var(--brand-forest);
            transform: translate(-1px, -1px);
            box-shadow: 2px 2px 0px var(--brand-forest);
        }
        .sidebar-nav-link-solid.active-page {
            background-color: var(--brand-forest);
            color: #FFFFFF !important;
            border-color: var(--brand-forest);
            box-shadow: 3px 3px 0px var(--brand-forest);
        }
        .sidebar-nav-link-solid.active-page i {
            color: var(--brand-yellow) !important;
        }
        .sidebar-nav-link-solid i {
            font-size: 1.05rem;
            margin-right: 10px;
            color: var(--brand-sage);
            flex-shrink: 0;
            transition: color 0.15s ease;
        }

        /* BOTTOM WEBSITE CTA */
        .btn-website-solid {
            background: #FFFFFF;
            border: 2px solid var(--brand-forest);
            color: var(--brand-forest);
            font-weight: 800;
            font-size: 0.82rem;
            border-radius: 12px;
            padding: 9px 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            box-shadow: 3px 3px 0px var(--brand-forest);
            transition: all 0.18s ease;
        }
        .btn-website-solid:hover {
            background: var(--brand-yellow);
            color: var(--brand-forest);
            transform: translate(-1px, -1px);
            box-shadow: 4px 4px 0px var(--brand-forest);
        }
        .btn-website-solid:active {
            transform: translate(2px, 2px);
            box-shadow: 1px 1px 0px var(--brand-forest);
        }

        /* ========================================================
           TOPBAR ARCHITECTURE
           ======================================================== */
        .topbar-solid {
            background: #FFFFFF;
            border: 2.5px solid var(--brand-forest);
            border-radius: 18px;
            padding: 10px 18px;
            box-shadow: 4px 4px 0px var(--brand-forest);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            width: 100%;
            margin-bottom: 22px;
        }

        .status-pill-solid {
            background: var(--brand-matcha);
            color: var(--brand-forest);
            border: 2px solid var(--brand-forest);
            border-radius: 9999px;
            padding: 5px 14px;
            font-size: 0.78rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 2px 2px 0px var(--brand-forest);
        }

        .pulse-dot-solid {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #16A34A;
            box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.7);
            animation: pulseEffect 2s infinite;
        }

        @keyframes pulseEffect {
            0% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.7); }
            70% { box-shadow: 0 0 0 7px rgba(22, 163, 74, 0); }
            100% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0); }
        }

        .admin-profile-btn-solid {
            background: #FFFFFF;
            border: 2px solid var(--brand-forest);
            border-radius: 9999px;
            padding: 4px 14px 4px 5px;
            display: inline-flex;
            align-items: center;
            gap: 9px;
            cursor: pointer;
            box-shadow: 2px 2px 0px var(--brand-forest);
            transition: all 0.18s ease;
            user-select: none;
        }
        .admin-profile-btn-solid:hover {
            background: var(--brand-sand);
            transform: translate(-1px, -1px);
            box-shadow: 3px 3px 0px var(--brand-forest);
        }
        .admin-profile-btn-solid:active {
            transform: translate(1px, 1px);
            box-shadow: 1px 1px 0px var(--brand-forest);
        }

        .dropdown-menu-solid {
            border: 2.5px solid var(--brand-forest);
            box-shadow: 5px 5px 0px var(--brand-forest);
            border-radius: 16px;
            padding: 8px;
            min-width: 210px;
            background: #FFFFFF;
            margin-top: 10px !important;
        }
        .dropdown-item-solid {
            border-radius: 10px;
            padding: 8px 12px;
            font-weight: 700;
            font-size: 0.86rem;
            color: var(--brand-forest);
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            background: transparent;
            border: none;
            width: 100%;
            transition: all 0.15s ease;
            cursor: pointer;
        }
        .dropdown-item-solid:hover {
            background-color: var(--brand-sand);
            color: var(--brand-forest);
        }
        .dropdown-item-solid.danger-item:hover {
            background-color: var(--brand-danger-bg);
            color: #991B1B;
        }

        /* MOBILE DRAWER (OFFCANVAS) STYLING */
        .mobile-drawer-toggle {
            background: #FFFFFF;
            border: 2px solid var(--brand-forest);
            border-radius: 10px;
            padding: 6px 10px;
            color: var(--brand-forest);
            box-shadow: 2px 2px 0px var(--brand-forest);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .mobile-drawer-toggle:active {
            transform: translate(2px, 2px);
            box-shadow: 0px 0px 0px var(--brand-forest);
        }

        .offcanvas-solid {
            background-color: var(--brand-cream) !important;
            border-right: 3px solid var(--brand-forest) !important;
            width: 290px !important;
        }

        /* PROPORTIONAL DESKTOP GRID */
        @media (min-width: 992px) {
            .admin-main-row {
                display: flex !important;
                flex-wrap: nowrap !important;
                width: 100% !important;
            }
            .admin-sidebar-col {
                flex: 0 0 260px !important;
                width: 260px !important;
                max-width: 270px !important;
                min-width: 250px !important;
            }
            .admin-content-col {
                flex: 1 1 0 !important;
                min-width: 0 !important;
                width: auto !important;
            }
        }

        @media (max-width: 991.98px) {
            .sidebar-wrapper {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="container-fluid px-2 px-sm-3 px-md-4 py-3 position-relative">
        <div class="row g-3 g-md-4 admin-main-row">
            
            <!-- ========================================================
                 LEFT DESKTOP SIDEBAR (~260px PROPORTIONAL COLUMN)
                 ======================================================== -->
            <div class="col-12 col-lg-3 col-xl-2 col-xxl-2 admin-sidebar-col d-none d-lg-block">
                <div class="sidebar-wrapper">
                    <aside class="sidebar-card-solid">
                        
                        <div>
                            <!-- Brand (HTML #2 Neobrutalist Specs) -->
                            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand-solid">
                                <div class="sidebar-brand-logo-solid">
                                    <img src="https://iconlogovector.com/uploads/images/2024/06/lg-66775f54ef918-NU-Nahdlatul-Ulama.webp" 
                                         alt="Logo NU" 
                                         style="max-height: 24px; width: auto;">
                                </div>
                                <div class="min-w-0">
                                    <div class="sidebar-brand-title-solid text-safe">NU WANGON</div>
                                    <div class="badge-solid badge-solid-forest mt-1" style="font-size: 0.62rem; padding: 2px 7px;">
                                        PORTAL ADMIN
                                    </div>
                                </div>
                            </a>

                            <!-- User Profile Box -->
                            <div class="sidebar-user-box-solid min-w-0">
                                <div class="sidebar-user-avatar-solid">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                                    <span class="status-dot" title="Aktif"></span>
                                </div>
                                <div class="min-w-0 flex-grow-1">
                                    <div class="fw-bold text-truncate" style="font-size: 0.84rem; color: var(--brand-forest);">
                                        {{ Auth::user()->name ?? 'Administrator' }}
                                    </div>
                                    <div class="text-truncate text-muted" style="font-size: 0.7rem;">
                                        {{ Auth::user()->email ?? 'admin@nuwangon.or.id' }}
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation List Grouped (HTML #2 Specs) -->
                            <nav class="sidebar-nav-list-solid">
                                
                                <!-- GROUP 1: MENU UTAMA -->
                                <div class="sidebar-nav-group-title">Menu Utama</div>
                                
                                <a href="{{ route('admin.dashboard') }}" 
                                   class="sidebar-nav-link-solid {{ request()->routeIs('admin.dashboard') ? 'active-page' : '' }}">
                                    <span class="d-flex align-items-center">
                                        <i class="bi bi-grid-1x2-fill"></i>
                                        <span>Dashboard</span>
                                    </span>
                                    @if(request()->routeIs('admin.dashboard'))
                                        <span class="badge-solid badge-solid-yellow" style="font-size: 0.6rem; padding: 1px 6px;">Aktif</span>
                                    @endif
                                </a>

                                <a href="{{ route('admin.berita.index') }}" 
                                   class="sidebar-nav-link-solid {{ request()->routeIs('admin.berita.*') ? 'active-page' : '' }}">
                                    <span class="d-flex align-items-center">
                                        <i class="bi bi-newspaper"></i>
                                        <span>Berita &amp; Warta</span>
                                    </span>
                                    @if(request()->routeIs('admin.berita.*'))
                                        <span class="badge-solid badge-solid-yellow" style="font-size: 0.6rem; padding: 1px 6px;">Aktif</span>
                                    @endif
                                </a>

                                <a href="{{ route('admin.kegiatan.index') }}" 
                                   class="sidebar-nav-link-solid {{ request()->routeIs('admin.kegiatan.*') ? 'active-page' : '' }}">
                                    <span class="d-flex align-items-center">
                                        <i class="bi bi-calendar-event"></i>
                                        <span>Kegiatan Dakwah</span>
                                    </span>
                                    @if(request()->routeIs('admin.kegiatan.*'))
                                        <span class="badge-solid badge-solid-yellow" style="font-size: 0.6rem; padding: 1px 6px;">Aktif</span>
                                    @endif
                                </a>

                                <a href="{{ route('admin.galeri.index') }}" 
                                   class="sidebar-nav-link-solid {{ request()->routeIs('admin.galeri.*') ? 'active-page' : '' }}">
                                    <span class="d-flex align-items-center">
                                        <i class="bi bi-images"></i>
                                        <span>Galeri Kegiatan</span>
                                    </span>
                                    @if(request()->routeIs('admin.galeri.*'))
                                        <span class="badge-solid badge-solid-yellow" style="font-size: 0.6rem; padding: 1px 6px;">Aktif</span>
                                    @endif
                                </a>

                                <!-- GROUP 2: DATA MASTER -->
                                <div class="sidebar-nav-group-title">Data Master</div>

                                <a href="{{ route('admin.anggota.index') }}" 
                                   class="sidebar-nav-link-solid {{ request()->routeIs('admin.anggota.*') ? 'active-page' : '' }}">
                                    <span class="d-flex align-items-center">
                                        <i class="bi bi-people-fill"></i>
                                        <span>Data Anggota</span>
                                    </span>
                                    @if(request()->routeIs('admin.anggota.*'))
                                        <span class="badge-solid badge-solid-yellow" style="font-size: 0.6rem; padding: 1px 6px;">Aktif</span>
                                    @endif
                                </a>

                                <a href="{{ route('admin.pendaftaran-anggota.index') }}" 
                                   class="sidebar-nav-link-solid {{ request()->routeIs('admin.pendaftaran-anggota.*') ? 'active-page' : '' }}">
                                    <span class="d-flex align-items-center">
                                        <i class="bi bi-person-check-fill"></i>
                                        <span>Pendaftaran</span>
                                    </span>
                                    @if(request()->routeIs('admin.pendaftaran-anggota.*'))
                                        <span class="badge-solid badge-solid-yellow" style="font-size: 0.6rem; padding: 1px 6px;">Aktif</span>
                                    @endif
                                </a>

                                <a href="{{ route('admin.organisasi.index') }}" 
                                   class="sidebar-nav-link-solid {{ request()->routeIs('admin.organisasi.*') ? 'active-page' : '' }}">
                                    <span class="d-flex align-items-center">
                                        <i class="bi bi-diagram-3-fill"></i>
                                        <span>Organisasi / Banom</span>
                                    </span>
                                    @if(request()->routeIs('admin.organisasi.*'))
                                        <span class="badge-solid badge-solid-yellow" style="font-size: 0.6rem; padding: 1px 6px;">Aktif</span>
                                    @endif
                                </a>

                                <!-- GROUP 3: PENGATURAN & SISTEM -->
                                <div class="sidebar-nav-group-title">Pengaturan Website</div>

                                <a href="{{ route('admin.pengaturan.sekretariat') }}" 
                                   class="sidebar-nav-link-solid {{ request()->routeIs('admin.pengaturan.sekretariat*') ? 'active-page' : '' }}">
                                    <span class="d-flex align-items-center">
                                        <i class="bi bi-geo-alt-fill"></i>
                                        <span>Pusat Layanan &amp; Sekretariat</span>
                                    </span>
                                    @if(request()->routeIs('admin.pengaturan.sekretariat*'))
                                        <span class="badge-solid badge-solid-yellow" style="font-size: 0.6rem; padding: 1px 6px;">Aktif</span>
                                    @endif
                                </a>

                                <div class="sidebar-nav-group-title">Sistem</div>

                                <a href="{{ route('admin.admin-user.index') }}" 
                                   class="sidebar-nav-link-solid {{ request()->routeIs('admin.admin-user.*') ? 'active-page' : '' }}">
                                    <span class="d-flex align-items-center">
                                        <i class="bi bi-shield-lock-fill"></i>
                                        <span>Kelola Admin</span>
                                    </span>
                                    @if(request()->routeIs('admin.admin-user.*'))
                                        <span class="badge-solid badge-solid-yellow" style="font-size: 0.6rem; padding: 1px 6px;">Aktif</span>
                                    @endif
                                </a>

                            </nav>
                        </div>

                        <!-- Footer Action: Lihat Website -->
                        <div class="pt-3 border-top border-2 border-dark mt-3">
                            <a href="{{ route('home') }}" target="_blank" class="btn-website-solid">
                                <i class="bi bi-box-arrow-up-right"></i>
                                <span>Lihat Website</span>
                            </a>
                        </div>

                    </aside>
                </div>
            </div>

            <!-- ========================================================
                 MOBILE DRAWER (OFFCANVAS)
                 ======================================================== -->
            <div class="offcanvas offcanvas-start offcanvas-solid" tabindex="-1" id="adminMobileDrawer" aria-labelledby="adminMobileDrawerLabel">
                <div class="offcanvas-header border-bottom border-2 border-dark pb-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="sidebar-brand-logo-solid" style="width: 34px; height: 34px;">
                            <img src="https://iconlogovector.com/uploads/images/2024/06/lg-66775f54ef918-NU-Nahdlatul-Ulama.webp" 
                                 alt="Logo NU" 
                                 style="max-height: 20px; width: auto;">
                        </div>
                        <h5 class="offcanvas-title fw-black font-serif" id="adminMobileDrawerLabel" style="font-size: 1.1rem; color: var(--brand-forest);">
                            NU WANGON
                        </h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body d-flex flex-column justify-content-between p-3">
                    <div>
                        <!-- Mobile User Profile -->
                        <div class="sidebar-user-box-solid min-w-0 mb-3">
                            <div class="sidebar-user-avatar-solid" style="width: 34px; height: 34px; font-size: 0.9rem;">
                                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-grow-1">
                                <div class="fw-bold text-truncate" style="font-size: 0.82rem;">
                                    {{ Auth::user()->name ?? 'Administrator' }}
                                </div>
                                <div class="text-truncate text-muted" style="font-size: 0.68rem;">
                                    {{ Auth::user()->email ?? 'admin@nuwangon.or.id' }}
                                </div>
                            </div>
                        </div>

                        <!-- Mobile Nav List -->
                        <nav class="sidebar-nav-list-solid">
                            <div class="sidebar-nav-group-title">Menu Utama</div>
                            <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-link-solid {{ request()->routeIs('admin.dashboard') ? 'active-page' : '' }}">
                                <span><i class="bi bi-grid-1x2-fill"></i> Dashboard</span>
                            </a>
                            <a href="{{ route('admin.berita.index') }}" class="sidebar-nav-link-solid {{ request()->routeIs('admin.berita.*') ? 'active-page' : '' }}">
                                <span><i class="bi bi-newspaper"></i> Berita &amp; Warta</span>
                            </a>
                            <a href="{{ route('admin.kegiatan.index') }}" class="sidebar-nav-link-solid {{ request()->routeIs('admin.kegiatan.*') ? 'active-page' : '' }}">
                                <span><i class="bi bi-calendar-event"></i> Kegiatan Dakwah</span>
                            </a>
                            <a href="{{ route('admin.galeri.index') }}" class="sidebar-nav-link-solid {{ request()->routeIs('admin.galeri.*') ? 'active-page' : '' }}">
                                <span><i class="bi bi-images"></i> Galeri Kegiatan</span>
                            </a>

                            <div class="sidebar-nav-group-title">Data Master</div>
                            <a href="{{ route('admin.anggota.index') }}" class="sidebar-nav-link-solid {{ request()->routeIs('admin.anggota.*') ? 'active-page' : '' }}">
                                <span><i class="bi bi-people-fill"></i> Data Anggota</span>
                            </a>
                            <a href="{{ route('admin.pendaftaran-anggota.index') }}" class="sidebar-nav-link-solid {{ request()->routeIs('admin.pendaftaran-anggota.*') ? 'active-page' : '' }}">
                                <span><i class="bi bi-person-check-fill"></i> Pendaftaran</span>
                            </a>
                            <a href="{{ route('admin.organisasi.index') }}" class="sidebar-nav-link-solid {{ request()->routeIs('admin.organisasi.*') ? 'active-page' : '' }}">
                                <span><i class="bi bi-diagram-3-fill"></i> Organisasi / Banom</span>
                            </a>

                            <div class="sidebar-nav-group-title">Pengaturan Website</div>
                            <a href="{{ route('admin.pengaturan.sekretariat') }}" class="sidebar-nav-link-solid {{ request()->routeIs('admin.pengaturan.sekretariat*') ? 'active-page' : '' }}">
                                <span><i class="bi bi-geo-alt-fill"></i> Pusat Layanan &amp; Sekretariat</span>
                            </a>

                            <div class="sidebar-nav-group-title">Sistem</div>
                            <a href="{{ route('admin.admin-user.index') }}" class="sidebar-nav-link-solid {{ request()->routeIs('admin.admin-user.*') ? 'active-page' : '' }}">
                                <span><i class="bi bi-shield-lock-fill"></i> Kelola Admin</span>
                            </a>
                        </nav>
                    </div>

                    <div class="pt-3 border-top border-2 border-dark mt-3">
                        <a href="{{ route('home') }}" target="_blank" class="btn-website-solid">
                            <i class="bi bi-box-arrow-up-right"></i>
                            <span>Lihat Website</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- ========================================================
                 MAIN CONTENT AREA
                 ======================================================== -->
            <div class="col-12 col-lg-9 col-xl-10 col-xxl-10 min-w-0 admin-content-col">
                
                <!-- TOPBAR (SOLID GROTESQUE ARCHITECTURE) -->
                <header class="topbar-solid">
                    <div class="d-flex align-items-center gap-2">
                        <!-- Mobile Drawer Toggle Button -->
                        <button type="button" class="mobile-drawer-toggle d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#adminMobileDrawer" aria-controls="adminMobileDrawer">
                            <i class="bi bi-list fs-5"></i>
                        </button>

                        <div class="status-pill-solid">
                            <span class="pulse-dot-solid"></span>
                            <span class="d-none d-sm-inline">Sistem Online &amp; Terhubung</span>
                            <span class="d-inline d-sm-none">Online</span>
                        </div>
                    </div>

                    <div class="dropdown">
                        <div class="admin-profile-btn-solid" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                            <div class="sidebar-user-avatar-solid" style="width: 30px; height: 30px; font-size: 0.8rem; border-radius: 50%;">
                                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="d-none d-sm-flex flex-column text-start min-w-0 pe-1">
                                <span class="fw-bold text-safe" style="font-size: 0.84rem; color: var(--brand-forest);">
                                    {{ Auth::user()->name ?? 'Admin NU' }}
                                </span>
                                <span class="text-muted text-safe" style="font-size: 0.68rem; margin-top: -2px;">
                                    Pengelola Portal
                                </span>
                            </div>
                            <i class="bi bi-chevron-down text-dark ms-1" style="font-size: 0.75rem;"></i>
                        </div>

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-solid">
                            <li>
                                <div class="px-3 py-2 border-bottom border-1 border-secondary border-opacity-25 mb-1">
                                    <div class="fw-bold" style="font-size: 0.84rem;">{{ Auth::user()->name ?? 'Admin' }}</div>
                                    <div class="text-muted" style="font-size: 0.72rem;">{{ Auth::user()->email ?? '' }}</div>
                                </div>
                            </li>
                            <li>
                                <a href="{{ route('admin.admin-user.index') }}" class="dropdown-item-solid">
                                    <i class="bi bi-shield-lock"></i>
                                    <span>Kelola Admin</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('home') }}" target="_blank" class="dropdown-item-solid">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                    <span>Lihat Website</span>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1 border-dark"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item-solid danger-item">
                                        <i class="bi bi-box-arrow-right"></i>
                                        <span>Keluar</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </header>

                <!-- GLOBAL FLASH MESSAGES -->
                @if (session('success'))
                    <div class="alert-solid-success">
                        <i class="bi bi-check-circle-fill fs-5 text-forest"></i>
                        <span class="flex-grow-1">{{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert-solid-danger">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                                <span>{{ session('error') }}</span>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert-solid-success">
                        <i class="bi bi-info-circle-fill fs-5"></i>
                        <span class="flex-grow-1">{{ session('status') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- MAIN WORKSPACE INJECTION -->
                <main class="min-w-0">
                    @yield('content')
                </main>
            </div>

        </div>
    </div>

    <!-- Bootstrap 5.3.3 Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Motion JS Micro-Stagger progressive enhancement
            if (window.Motion && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                const { animate, stagger } = window.Motion;
                const easeSilk = [0.16, 1, 0.3, 1];
                
                const topBar = document.querySelector('.topbar-solid');
                if (topBar) {
                    animate(topBar, { opacity: [0, 1], y: [-8, 0] }, { duration: 0.3, easing: easeSilk });
                }

                const navLinks = document.querySelectorAll('.sidebar-nav-link-solid');
                if (navLinks.length > 0) {
                    animate(navLinks, { opacity: [0, 1], x: [-6, 0] }, { delay: stagger(0.025, { start: 0.05 }), duration: 0.3, easing: easeSilk });
                }
            }
        });
    </script>

    {{-- PLAYFUL MAGNETIC & CLICK INTERACTION ENGINE (ADMIN WORKSPACE, TOPBAR & SIDEBAR EXCLUDED) --}}
    @include('layouts.partials.magnetic-interaction')

    @stack('scripts')
</body>
</html>