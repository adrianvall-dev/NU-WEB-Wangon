{{--
    Parallax Scroll & Abstract Geometric Motion Engine (PixelOS-inspired Layered Depth)
    - GPU-accelerated transform: translate3d(var(--parallax-x), var(--parallax-y), 0) rotate(var(--parallax-rot))
    - Zero layout reflow (never touches top/left/width/height)
    - Multi-layer depth speeds:
        * BACKGROUND: speed 0.01 - 0.02
        * MIDGROUND: speed 0.02 - 0.04
        * FOREGROUND DECORATION: speed 0.03 - 0.05
    - Gentle scroll-driven rotation: ±2 to 5 degrees
    - Fluid ambient floating keyframes: translateY ±3-5px over 10-15s
    - Micro-interpolated lerp (0.09 factor) for silk-smooth motion with 0 snapping or jumping
    - IntersectionObserver viewport culling for 60-120 FPS performance
    - Mobile damping (45-55% reduction on < 768px screens to preserve native touch responsiveness)
    - Strict prefers-reduced-motion accessibility compliance
    - Reusable presets: bg-slow, bg, mid, fore, image, card, accent, subtle, slow, medium
    - Full harmony with Lenis Smooth Scroll, TextAnimate, and NumberTicker
--}}
<style>
    /* Parallax Base Layer Variables & Composite Transforms */
    [data-parallax],
    .parallax-item {
        --parallax-x: 0px;
        --parallax-y: 0px;
        --parallax-rot: 0deg;
        transform: translate3d(var(--parallax-x, 0px), var(--parallax-y, 0px), 0) rotate(var(--parallax-rot, 0deg)) !important;
        will-change: transform;
    }

    /* Seamless coexistence with card hover states: retains --parallax-y while applying subtle lift */
    [data-parallax]:hover,
    .floating-frame-card[data-parallax]:hover {
        transform: translate3d(var(--parallax-x, 0px), calc(var(--parallax-y, 0px) - 4px), 0) rotate(var(--parallax-rot, 0deg)) scale(1.006) !important;
    }

    /* STRICTLY LOCK IMAGES AND INNER CARD CONTENT:
       Images and inner elements must NEVER receive independent scroll-based parallax transforms.
       The entire card container moves as ONE UNIT, and images remain locked inside. */
    [data-parallax] img,
    [data-parallax="card"] img,
    [data-parallax="card-large"] img,
    [data-parallax="card-medium"] img,
    [data-parallax="card-small"] img,
    .floating-frame-card img,
    article img,
    figure img {
        --parallax-y: 0px !important;
        --parallax-x: 0px !important;
        --parallax-rot: 0deg !important;
    }

    /* Ambient Floating Physics for background decorative blobs (8-16s smooth breathing cycle) */
    @keyframes ambientFloatSlow1 {
        0%, 100% {
            transform: translateY(0px) rotate(0deg);
        }
        50% {
            transform: translateY(-8px) rotate(2deg);
        }
    }

    @keyframes ambientFloatSlow2 {
        0%, 100% {
            transform: translateY(0px) rotate(0deg);
        }
        50% {
            transform: translateY(8px) rotate(-2deg);
        }
    }

    @keyframes ambientFloatSlow3 {
        0%, 100% {
            transform: translateY(0px) translateX(0px) rotate(0deg);
        }
        50% {
            transform: translateY(-6px) translateX(5px) rotate(1.5deg);
        }
    }

    .ambient-float-slow-1 {
        animation: ambientFloatSlow1 10s ease-in-out infinite;
        will-change: transform;
    }

    .ambient-float-slow-2 {
        animation: ambientFloatSlow2 13s ease-in-out infinite;
        will-change: transform;
    }

    .ambient-float-slow-3 {
        animation: ambientFloatSlow3 15s ease-in-out infinite;
        will-change: transform;
    }

    /* Accessibility: Complete Disablement on Reduced Motion */
    @media (prefers-reduced-motion: reduce) {
        [data-parallax],
        .parallax-item {
            transform: none !important;
            --parallax-x: 0px !important;
            --parallax-y: 0px !important;
            --parallax-rot: 0deg !important;
            will-change: auto !important;
            transition: none !important;
        }

        .ambient-float-slow-1,
        .ambient-float-slow-2,
        .ambient-float-slow-3 {
            animation: none !important;
            transform: none !important;
        }
    }

    /* Abstract Geometric Background Textures (PixelOS Inspiration - Enhanced Visibility) */
    .pixel-dot-matrix {
        background-image: radial-gradient(rgba(20, 46, 37, 0.28) 1.6px, transparent 1.6px);
        background-size: 18px 18px;
    }
    .pixel-dot-matrix-light {
        background-image: radial-gradient(rgba(217, 232, 221, 0.28) 1.6px, transparent 1.6px);
        background-size: 18px 18px;
    }
    .pixel-dot-matrix-gold {
        background-image: radial-gradient(rgba(197, 160, 89, 0.32) 1.6px, transparent 1.6px);
        background-size: 18px 18px;
    }
    .pixel-grid-blueprint {
        background-image: linear-gradient(to right, rgba(20, 46, 37, 0.08) 1px, transparent 1px),
                          linear-gradient(to bottom, rgba(20, 46, 37, 0.08) 1px, transparent 1px);
        background-size: 48px 48px;
    }
    .pixel-grid-blueprint-dark {
        background-image: linear-gradient(to right, rgba(217, 232, 221, 0.08) 1px, transparent 1px),
                          linear-gradient(to bottom, rgba(217, 232, 221, 0.08) 1px, transparent 1px);
        background-size: 48px 48px;
    }
</style>

<script>
(() => {
    'use strict';

    // Preset configurations for multi-layer depth:
    // BACKGROUND: distinct background geometric movement (±40-70px)
    // CARD: entire card/container moves as ONE unit (border, image, text, shadow together)
    //   * large card: ±26-38px (staggered across items)
    //   * medium card / default: ±18-34px (staggered: 28px, 18px, 34px, 22px)
    //   * small card: ±12-18px
    // IMAGE & INNER ELEMENTS: strictly locked (speed: 0, max: 0, no scroll parallax)
    const PRESETS = {
        'bg-slow': { max: 64, dir: 1 },
        'bg': { max: 52, dir: 1 },
        'background': { max: 52, dir: 1 },
        'mid': { max: 40, dir: 1 },
        'midground': { max: 40, dir: 1 },
        'fore': { max: 26, dir: -1 },
        'foreground': { max: 26, dir: -1 },

        // Card Parallax Presets (The whole card/container moves as ONE unit)
        'card-large': { max: 34, dir: 1 },
        'card-strong': { max: 34, dir: 1 },
        'card': { max: 26, dir: 1 },
        'card-medium': { max: 26, dir: 1 },
        'card-slow': { max: 18, dir: 1 },
        'card-subtle': { max: 18, dir: 1 },
        'card-small': { max: 15, dir: 1 },

        // Explicit depth levels for custom staggered layouts
        'card-depth-1': { max: 28, dir: 1 },
        'card-depth-2': { max: 18, dir: 1 },
        'card-depth-3': { max: 34, dir: 1 },
        'card-depth-4': { max: 22, dir: 1 },

        // Image & Inner card elements: Strictly locked (0 scroll movement)
        'image': { max: 0, dir: 1 },
        'accent': { max: 0, dir: 1 },
        'locked': { max: 0, dir: 1 },

        'subtle': { max: 18, dir: 1 },
        'slow': { max: 55, dir: 1 },
        'medium': { max: 30, dir: 1 }
    };

    // Stagger cycles for organic visual depth across cards
    const STAGGER_MEDIUM = [28, 18, 34, 22]; // As requested by user: Card A: 28px, B: 18px, C: 34px, D: 22px
    const STAGGER_LARGE  = [36, 28, 38, 32];
    const STAGGER_SMALL  = [16, 12, 18, 14];

    function initParallaxEngine() {
        const motionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
        if (motionQuery.matches) {
            // Respect accessibility preference immediately
            return;
        }

        const elements = document.querySelectorAll('[data-parallax], .parallax-item');
        if (elements.length === 0) return;

        let isMobile = window.innerWidth < 768;
        const mobileFactor = isMobile ? 0.68 : 1.0; // 68% mobile strength as per guidelines (60-75%)
        const visibleItems = new Set();
        const items = [];
        let isRunning = false;
        let viewportHeight = window.innerHeight;

        let cardMedCounter = 0;
        let cardLrgCounter = 0;
        let cardSmlCounter = 0;

        // Parse items and attributes
        elements.forEach(el => {
            // Avoid double init
            if (el._parallaxItem) {
                items.push(el._parallaxItem);
                return;
            }

            const rawPreset = (el.getAttribute('data-parallax') || el.getAttribute('data-parallax-preset') || 'subtle').trim().toLowerCase();
            const config = PRESETS[rawPreset] || PRESETS['subtle'];

            // Speed parsing (allow custom data-parallax-speed override)
            const customSpeedAttr = el.getAttribute('data-parallax-speed');
            const customSpeed = (customSpeedAttr !== null && !isNaN(parseFloat(customSpeedAttr))) 
                ? parseFloat(customSpeedAttr) 
                : null;

            // Direction parsing: 'up' (1) or 'down' (-1)
            let dir = config.dir;
            const customDir = el.getAttribute('data-parallax-direction');
            if (customDir === 'up') dir = 1;
            else if (customDir === 'down') dir = -1;

            if (customSpeed !== null && customSpeed < 0) {
                dir = -dir;
            }

            // Max offset in pixels with organic depth variation staggering
            let baseMax = config.max;
            const customMaxAttr = el.getAttribute('data-parallax-max') || el.getAttribute('data-parallax-depth');
            if (customMaxAttr !== null && !isNaN(parseFloat(customMaxAttr))) {
                baseMax = Math.abs(parseFloat(customMaxAttr));
            } else if (rawPreset === 'card' || rawPreset === 'card-medium') {
                baseMax = STAGGER_MEDIUM[cardMedCounter % STAGGER_MEDIUM.length];
                cardMedCounter++;
            } else if (rawPreset === 'card-large' || rawPreset === 'card-strong') {
                baseMax = STAGGER_LARGE[cardLrgCounter % STAGGER_LARGE.length];
                cardLrgCounter++;
            } else if (rawPreset === 'card-small') {
                baseMax = STAGGER_SMALL[cardSmlCounter % STAGGER_SMALL.length];
                cardSmlCounter++;
            }

            // Horizontal drift offset (optional)
            const customX = el.getAttribute('data-parallax-x');
            const hasX = customX !== null && !isNaN(parseFloat(customX));
            const baseSpeedX = hasX ? parseFloat(customX) : 0;
            const customMaxX = el.getAttribute('data-parallax-max-x');
            const baseMaxX = customMaxX !== null && !isNaN(parseFloat(customMaxX)) ? Math.abs(parseFloat(customMaxX)) : 6;

            // Rotation parsing (optional ±2-5 deg)
            const customRot = el.getAttribute('data-parallax-rotate');
            const hasRot = customRot !== null && !isNaN(parseFloat(customRot));
            const baseMaxRot = hasRot ? Math.abs(parseFloat(customRot)) : 0;

            const item = {
                el,
                customSpeed,
                dir,
                baseMax,
                hasX,
                baseSpeedX,
                baseMaxX,
                hasRot,
                baseMaxRot,
                max: Math.round(baseMax * mobileFactor),
                maxX: Math.round(baseMaxX * mobileFactor),
                maxRot: baseMaxRot * mobileFactor,
                currentY: 0,
                currentX: 0,
                currentRot: 0,
                targetY: 0,
                targetX: 0,
                targetRot: 0,
                lerp: 0.10, // Controlled 0.10 factor for responsive and silky smooth deceleration
                initialized: false
            };

            el._parallaxItem = item;
            items.push(item);
        });

        // Viewport Culling Observer with 200px buffer to prevent any edge popping
        const observer = ('IntersectionObserver' in window) ? new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const item = entry.target._parallaxItem;
                if (!item) return;

                if (entry.isIntersecting) {
                    visibleItems.add(item);
                    // Initial calculation to prevent jump on entrance
                    if (!item.initialized) {
                        calculateTarget(item, viewportHeight);
                        item.currentY = item.targetY;
                        item.currentX = item.targetX;
                        item.currentRot = item.targetRot;
                        item.el.style.setProperty('--parallax-y', `${item.currentY.toFixed(2)}px`);
                        if (item.hasX) {
                            item.el.style.setProperty('--parallax-x', `${item.currentX.toFixed(2)}px`);
                        }
                        if (item.hasRot) {
                            item.el.style.setProperty('--parallax-rot', `${item.currentRot.toFixed(2)}deg`);
                        }
                        item.initialized = true;
                    }
                    scheduleTick();
                } else {
                    visibleItems.delete(item);
                }
            });
        }, {
            threshold: 0,
            rootMargin: '200px 0px 200px 0px'
        }) : null;

        if (observer) {
            items.forEach(item => observer.observe(item.el));
        } else {
            // Fallback for environments without IntersectionObserver
            items.forEach(item => visibleItems.add(item));
        }

        function calculateTarget(item, vh) {
            const rect = item.el.getBoundingClientRect();
            const elementCenter = rect.top + (rect.height / 2);
            const viewportCenter = vh / 2;

            // Normalized viewport progress:
            // Element center at viewport center: progress = 0
            // Element center at bottom edge: progress = -1.0
            // Element center at top edge: progress = +1.0
            const progress = (viewportCenter - elementCenter) / (vh * 0.5);

            let rawY;
            if (item.customSpeed !== null) {
                rawY = (viewportCenter - elementCenter) * Math.abs(item.customSpeed) * item.dir;
            } else {
                rawY = progress * item.max * item.dir;
            }

            item.targetY = Math.max(-item.max, Math.min(item.max, rawY));

            if (item.hasX) {
                const rawX = progress * item.maxX;
                item.targetX = Math.max(-item.maxX, Math.min(item.maxX, rawX));
            }

            if (item.hasRot) {
                const rawRot = progress * item.maxRot;
                item.targetRot = Math.max(-item.maxRot, Math.min(item.maxRot, rawRot));
            }
        }

        function scheduleTick() {
            if (!isRunning && visibleItems.size > 0) {
                isRunning = true;
                requestAnimationFrame(tick);
            }
        }

        function tick() {
            let hasPendingMotion = false;
            viewportHeight = window.innerHeight;

            visibleItems.forEach(item => {
                calculateTarget(item, viewportHeight);

                // Lerp smoothing (0.10 interpolation for organic, fluid tracking)
                const diffY = item.targetY - item.currentY;
                if (Math.abs(diffY) > 0.04) {
                    item.currentY += diffY * item.lerp;
                    hasPendingMotion = true;
                } else {
                    item.currentY = item.targetY;
                }
                item.el.style.setProperty('--parallax-y', `${item.currentY.toFixed(2)}px`);

                if (item.hasX) {
                    const diffX = item.targetX - item.currentX;
                    if (Math.abs(diffX) > 0.04) {
                        item.currentX += diffX * item.lerp;
                        hasPendingMotion = true;
                    } else {
                        item.currentX = item.targetX;
                    }
                    item.el.style.setProperty('--parallax-x', `${item.currentX.toFixed(2)}px`);
                }

                if (item.hasRot) {
                    const diffRot = item.targetRot - item.currentRot;
                    if (Math.abs(diffRot) > 0.02) {
                        item.currentRot += diffRot * item.lerp;
                        hasPendingMotion = true;
                    } else {
                        item.currentRot = item.targetRot;
                    }
                    item.el.style.setProperty('--parallax-rot', `${item.currentRot.toFixed(2)}deg`);
                }
            });

            if (hasPendingMotion) {
                requestAnimationFrame(tick);
            } else {
                isRunning = false;
            }
        }

        function onScrollEvent() {
            scheduleTick();
        }

        function onResizeEvent() {
            isMobile = window.innerWidth < 768;
            const factor = isMobile ? 0.68 : 1.0;
            viewportHeight = window.innerHeight;
            items.forEach(item => {
                item.max = Math.round(item.baseMax * factor);
                item.maxX = Math.round(item.baseMaxX * factor);
                item.maxRot = item.baseMaxRot * factor;
            });
            scheduleTick();
        }

        // Attach to scroll providers
        if (window.lenisInstance && typeof window.lenisInstance.on === 'function') {
            window.lenisInstance.on('scroll', onScrollEvent);
        }
        window.addEventListener('scroll', onScrollEvent, { passive: true });
        window.addEventListener('resize', onResizeEvent, { passive: true });

        // Reduced motion listener if dynamically enabled
        motionQuery.addEventListener('change', (e) => {
            if (e.matches) {
                items.forEach(item => {
                    item.el.style.removeProperty('--parallax-y');
                    item.el.style.removeProperty('--parallax-x');
                    item.el.style.removeProperty('--parallax-rot');
                });
                visibleItems.clear();
                window.removeEventListener('scroll', onScrollEvent);
            } else {
                initParallaxEngine();
            }
        });

        // Initial tick after slight frame delay to ensure layout metrics
        requestAnimationFrame(() => {
            scheduleTick();
        });
    }

    // Auto-init on DOMContentLoaded or immediate if already loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initParallaxEngine);
    } else {
        initParallaxEngine();
    }

    // Expose global initializer for dynamic/SPA DOM updates
    window.initParallaxEngine = initParallaxEngine;
})();
</script>
