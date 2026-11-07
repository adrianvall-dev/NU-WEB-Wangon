{{-- 
    PLAYFUL MAGNETIC BUTTON & CLICK INTERACTION ENGINE
    Framer Motion Reference: https://framer.com/m/Magnetic-playful-button-bvkUGv.js
    
    Features:
    - Magnetic attraction towards cursor within subtle boundaries (4-7px)
    - Playful spring recoil on release with cubic-bezier overshoot
    - Tactile compression press feedback on pointerdown
    - Micro-ripple / click impact from cursor contact point
    - Complete mobile / touch friendliness (press + ripple, no stuck hover)
    - Full accessibility (prefers-reduced-motion respected)
    - 100% TOTAL EXCLUSION for Navbar elements and Admin sidebar/topbar
--}}
<style>
    /* Magnetic Target Hardware Acceleration */
    .mag-active {
        will-change: transform;
    }

    /* Ripple Host & Overflow Clip */
    .mag-ripple-host {
        position: relative;
        overflow: hidden !important;
    }

    /* Subtle Click Micro-Ripple */
    .mag-click-ripple {
        position: absolute;
        border-radius: 50%;
        transform: translate(-50%, -50%) scale(0);
        pointer-events: none !important;
        animation: magRippleAnim 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        z-index: 25;
    }

    @keyframes magRippleAnim {
        0% {
            transform: translate(-50%, -50%) scale(0);
            opacity: 0.42;
        }
        100% {
            transform: translate(-50%, -50%) scale(1.6);
            opacity: 0;
        }
    }

    /* STRICT NAVBAR EXCLUSION (CSS REINFORCEMENT) */
    #mainNavbarHeader,
    #mainNavbarHeader *,
    [data-navbar],
    [data-navbar] *,
    header.site-header,
    header.site-header *,
    .motion-trail-nav,
    .motion-trail-nav * {
        /* No magnetic transforms ever applied to navbar */
    }

    /* Accessibility: Respect Reduced Motion */
    @media (prefers-reduced-motion: reduce) {
        .mag-active {
            transform: none !important;
            transition: none !important;
            will-change: auto !important;
        }
        .mag-click-ripple {
            display: none !important;
        }
    }
</style>

<script>
(function() {
    'use strict';

    if (window.__playfulMagneticEngineLoaded) return;
    window.__playfulMagneticEngineLoaded = true;

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const isTouchDevice = window.matchMedia('(pointer: coarse)').matches || !window.matchMedia('(hover: hover)').matches;

    // State Variables
    let currentEl = null;
    let currentRect = null;
    let currentProfile = null;
    let currentTx = 0;
    let currentTy = 0;
    let isPressed = false;
    let rafId = null;

    /**
     * Resolve interactive target outside of navbar and excluded containers
     */
    function getInteractiveTarget(target) {
        if (!target || !(target instanceof Element)) return null;

        // 1. ABSOLUTE NAVBAR EXCLUSION
        if (target.closest('#mainNavbarHeader, header, [data-navbar], .site-header, .motion-trail-nav, #desktopNav, .navbar, #navbarMain')) {
            return null;
        }

        // 2. ADMIN SIDEBAR & TOPBAR EXCLUSION
        if (target.closest('.sidebar-solid, .topbar-solid, .dropdown-menu, .nav-tabs')) {
            return null;
        }

        // 3. FORM INPUTS & EXPLICIT OPT-OUT
        if (target.closest('.no-magnetic, [data-no-magnetic], input, textarea, select, option, label')) {
            return null;
        }

        // 4. FIND CLOSEST INTERACTIVE ELEMENT
        const el = target.closest([
            'button',
            '.btn-solid-press',
            '.btn',
            '.btn-primary',
            '.btn-secondary',
            '.btn-success',
            '.btn-danger',
            '.btn-light',
            '.btn-nu-primary',
            '.btn-nu-outline',
            'a.btn-solid-press',
            'a.btn',
            'a[role="button"]',
            '.pagination .page-link',
            '.page-item a',
            'nav[role="navigation"] a',
            '.btn-website-solid',
            '.btn-solid-primary',
            '.btn-solid-accent',
            '.btn-solid-light',
            '.floating-frame-card',
            '[data-magnetic]'
        ].join(', '));

        if (!el) return null;

        // Secondary check: verify the resolved element itself is not inside navbar or admin shell
        if (el.closest('#mainNavbarHeader, header, [data-navbar], .site-header, .motion-trail-nav, #desktopNav, .sidebar-solid, .topbar-solid')) {
            return null;
        }

        return el;
    }

    /**
     * Compute magnetic strength, limits, and scales based on element category
     */
    function getProfile(el) {
        // A. Clickable Large Cards
        if (el.classList.contains('floating-frame-card') || el.classList.contains('card') || el.offsetWidth > 320) {
            return {
                type: 'card',
                strength: 0.06,
                maxDist: 3.5,
                hoverScale: 1.006,
                pressScale: 0.99,
                enableRipple: false
            };
        }

        // B. Small / Icon Buttons (<= 52px or round pills)
        const rect = el.getBoundingClientRect();
        if ((rect.width <= 52 && rect.height <= 52) || el.classList.contains('rounded-full')) {
            return {
                type: 'icon',
                strength: 0.38,
                maxDist: 6.5,
                hoverScale: 1.045,
                pressScale: 0.92,
                enableRipple: true
            };
        }

        // C. Standard Buttons, CTAs, Filter Pills, Pagination Links
        return {
            type: 'button',
            strength: 0.28,
            maxDist: 7.0,
            hoverScale: 1.025,
            pressScale: 0.958,
            enableRipple: true
        };
    }

    /**
     * Detect if background is dark for micro-ripple contrast
     */
    function isDarkBg(el) {
        try {
            const comp = window.getComputedStyle(el);
            const bg = comp.backgroundColor;
            const rgb = bg.match(/\d+/g);
            if (rgb && rgb.length >= 3) {
                const brightness = (parseInt(rgb[0], 10) * 299 + parseInt(rgb[1], 10) * 587 + parseInt(rgb[2], 10) * 114) / 1000;
                return brightness < 128;
            }
        } catch (e) {}
        return el.classList.contains('bg-brand-forest') || el.classList.contains('bg-brand-deep') || el.classList.contains('btn-primary');
    }

    /**
     * Create subtle click micro-ripple
     */
    function triggerSubtleRipple(el, clientX, clientY) {
        if (prefersReducedMotion) return;
        const rect = el.getBoundingClientRect();
        const x = clientX - rect.left;
        const y = clientY - rect.top;

        const diameter = Math.max(rect.width, rect.height) * 1.5;
        const ripple = document.createElement('span');
        ripple.className = 'mag-click-ripple';
        ripple.style.width = `${diameter}px`;
        ripple.style.height = `${diameter}px`;
        ripple.style.left = `${x}px`;
        ripple.style.top = `${y}px`;

        const isDark = isDarkBg(el);
        ripple.style.backgroundColor = isDark ? 'rgba(255, 255, 255, 0.28)' : 'rgba(20, 46, 37, 0.14)';

        const comp = window.getComputedStyle(el);
        if (comp.position === 'static') {
            el.style.position = 'relative';
        }
        el.classList.add('mag-ripple-host');

        el.appendChild(ripple);

        setTimeout(() => {
            ripple.remove();
            if (!el.querySelector('.mag-click-ripple')) {
                el.classList.remove('mag-ripple-host');
            }
        }, 450);
    }

    /**
     * Activate magnetic element tracking on hover
     */
    function activateElement(el, e) {
        currentEl = el;
        const rect = el.getBoundingClientRect();
        currentRect = {
            left: rect.left,
            top: rect.top,
            width: rect.width,
            height: rect.height,
            centerX: rect.left + rect.width / 2,
            centerY: rect.top + rect.height / 2
        };
        currentProfile = getProfile(el);
        isPressed = false;

        el.classList.add('mag-active');
        el.style.willChange = 'transform';
        el.style.transition = 'transform 0.12s cubic-bezier(0.25, 1, 0.5, 1)';

        const dx = e.clientX - currentRect.centerX;
        const dy = e.clientY - currentRect.centerY;
        let tx = dx * currentProfile.strength;
        let ty = dy * currentProfile.strength;
        tx = Math.max(-currentProfile.maxDist, Math.min(currentProfile.maxDist, tx));
        ty = Math.max(-currentProfile.maxDist, Math.min(currentProfile.maxDist, ty));
        currentTx = tx;
        currentTy = ty;
        el.style.transform = `translate3d(${tx.toFixed(2)}px, ${ty.toFixed(2)}px, 0px) scale(${currentProfile.hoverScale})`;
    }

    /**
     * Release magnetic element with spring-like snap back
     */
    function releaseElement(el) {
        if (!el) return;
        el.style.transition = 'transform 0.48s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
        el.style.transform = 'translate3d(0px, 0px, 0px) scale(1)';
        el.classList.remove('mag-active', 'mag-pressed');

        const toClean = el;
        setTimeout(() => {
            if (toClean !== currentEl) {
                toClean.style.transition = '';
                toClean.style.transform = '';
                toClean.style.willChange = '';
            }
        }, 500);

        if (el === currentEl) {
            currentEl = null;
            currentRect = null;
            currentProfile = null;
            isPressed = false;
        }
    }

    /**
     * rAF Transform Updater
     */
    function updateTransform() {
        rafId = null;
        if (!currentEl) return;
        const scale = isPressed ? currentProfile.pressScale : currentProfile.hoverScale;
        currentEl.style.transform = `translate3d(${currentTx.toFixed(2)}px, ${currentTy.toFixed(2)}px, 0px) scale(${scale})`;
    }

    /**
     * Global Pointer Move Listener
     */
    window.addEventListener('pointermove', function(e) {
        if (isTouchDevice || prefersReducedMotion) return;

        const targetEl = getInteractiveTarget(e.target);

        if (targetEl !== currentEl) {
            if (currentEl) {
                releaseElement(currentEl);
            }
            if (targetEl) {
                activateElement(targetEl, e);
            }
        }

        if (!currentEl || !currentRect) return;

        const dx = e.clientX - currentRect.centerX;
        const dy = e.clientY - currentRect.centerY;

        // Check if pointer is still reasonably near element
        const tolerance = 24;
        if (
            e.clientX < currentRect.left - tolerance ||
            e.clientX > currentRect.left + currentRect.width + tolerance ||
            e.clientY < currentRect.top - tolerance ||
            e.clientY > currentRect.top + currentRect.height + tolerance
        ) {
            releaseElement(currentEl);
            return;
        }

        let tx = dx * currentProfile.strength;
        let ty = dy * currentProfile.strength;
        tx = Math.max(-currentProfile.maxDist, Math.min(currentProfile.maxDist, tx));
        ty = Math.max(-currentProfile.maxDist, Math.min(currentProfile.maxDist, ty));

        currentTx = tx;
        currentTy = ty;

        if (!rafId) {
            rafId = requestAnimationFrame(updateTransform);
        }
    }, { passive: true });

    /**
     * Global Pointer Down (Click Compression + Ripple)
     */
    window.addEventListener('pointerdown', function(e) {
        const targetEl = getInteractiveTarget(e.target);
        if (!targetEl) return;

        const profile = currentEl === targetEl ? currentProfile : getProfile(targetEl);
        isPressed = true;
        targetEl.classList.add('mag-pressed');

        // Tactile compression
        targetEl.style.transition = 'transform 0.08s cubic-bezier(0.2, 0, 0, 1)';
        const tx = (currentEl === targetEl ? currentTx : 0) * 0.7;
        const ty = (currentEl === targetEl ? currentTy : 0) * 0.7;
        targetEl.style.transform = `translate3d(${tx.toFixed(2)}px, ${ty.toFixed(2)}px, 0px) scale(${profile.pressScale})`;

        // Micro-ripple impact
        if (profile.enableRipple && !prefersReducedMotion) {
            triggerSubtleRipple(targetEl, e.clientX, e.clientY);
        }
    }, { passive: true });

    /**
     * Global Pointer Up & Cancel (Elastic Recoil)
     */
    function handlePointerRelease(e) {
        isPressed = false;
        if (currentEl) {
            currentEl.classList.remove('mag-pressed');
            currentEl.style.transition = 'transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1)';
            const scale = isTouchDevice ? 1 : currentProfile.hoverScale;
            currentEl.style.transform = `translate3d(${currentTx.toFixed(2)}px, ${currentTy.toFixed(2)}px, 0px) scale(${scale})`;
            if (isTouchDevice) {
                releaseElement(currentEl);
            }
        } else {
            const targetEl = getInteractiveTarget(e.target);
            if (targetEl) {
                releaseElement(targetEl);
            }
        }
    }

    window.addEventListener('pointerup', handlePointerRelease, { passive: true });
    window.addEventListener('pointercancel', handlePointerRelease, { passive: true });

    /**
     * Reset on Window Blur or Scroll
     */
    window.addEventListener('blur', function() {
        if (currentEl) releaseElement(currentEl);
    });

    window.addEventListener('scroll', function() {
        if (currentEl && !isTouchDevice) {
            const rect = currentEl.getBoundingClientRect();
            currentRect = {
                left: rect.left,
                top: rect.top,
                width: rect.width,
                height: rect.height,
                centerX: rect.left + rect.width / 2,
                centerY: rect.top + rect.height / 2
            };
        }
    }, { passive: true });

})();
</script>
