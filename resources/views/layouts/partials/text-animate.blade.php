{{--
    TextAnimate Engine
    Inspired by motion/react TextAnimate reference:
    - Text splitting by word
    - Staggered word-by-word animation (blurInUp: opacity + translateY + blur)
    - Viewport trigger with IntersectionObserver & once option
    - Strict semantic HTML & accessibility preservation (sr-only + aria-label)
    - Reduced motion fallback
    - GPU-accelerated (opacity, transform, filter)
    - Zero horizontal overflow & layout shift prevention
--}}
<style>
    /* Screen reader accessible text */
    .text-animate-sr-only {
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        padding: 0 !important;
        margin: -1px !important;
        overflow: hidden !important;
        clip: rect(0, 0, 0, 0) !important;
        white-space: nowrap !important;
        border: 0 !important;
    }

    .text-animate-wrapper {
        display: inline;
    }

    /* Base Word Segment */
    .text-animate-word {
        display: inline-block;
        vertical-align: baseline;
        white-space: normal;
        word-break: normal;
        overflow-wrap: break-word;
        transform-origin: center bottom;
        will-change: opacity, transform, filter;
        transition-property: opacity, transform, filter;
        transition-timing-function: cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* ================= 1. DEFAULT: blurInUp ================= */
    [data-text-animate="blurInUp"] .text-animate-word,
    .text-animate-blurInUp .text-animate-word,
    .text-animate-default .text-animate-word {
        opacity: 0;
        transform: translate3d(0, 20px, 0);
        filter: blur(10px);
    }
    [data-text-animate="blurInUp"].is-animated .text-animate-word,
    .text-animate-blurInUp.is-animated .text-animate-word,
    .text-animate-default.is-animated .text-animate-word {
        opacity: 1;
        transform: translate3d(0, 0, 0);
        filter: blur(0px);
    }

    /* ================= 2. blurInDown ================= */
    [data-text-animate="blurInDown"] .text-animate-word {
        opacity: 0;
        transform: translate3d(0, -20px, 0);
        filter: blur(10px);
    }
    [data-text-animate="blurInDown"].is-animated .text-animate-word {
        opacity: 1;
        transform: translate3d(0, 0, 0);
        filter: blur(0px);
    }

    /* ================= 3. fadeIn ================= */
    [data-text-animate="fadeIn"] .text-animate-word {
        opacity: 0;
        transform: translate3d(0, 16px, 0);
    }
    [data-text-animate="fadeIn"].is-animated .text-animate-word {
        opacity: 1;
        transform: translate3d(0, 0, 0);
    }

    /* ================= 4. blurIn ================= */
    [data-text-animate="blurIn"] .text-animate-word {
        opacity: 0;
        filter: blur(10px);
    }
    [data-text-animate="blurIn"].is-animated .text-animate-word {
        opacity: 1;
        filter: blur(0px);
    }

    /* ================= 5. slideUp ================= */
    [data-text-animate="slideUp"] .text-animate-word {
        opacity: 0;
        transform: translate3d(0, 24px, 0);
    }
    [data-text-animate="slideUp"].is-animated .text-animate-word {
        opacity: 1;
        transform: translate3d(0, 0, 0);
    }

    /* ================= 6. scaleUp ================= */
    [data-text-animate="scaleUp"] .text-animate-word {
        opacity: 0;
        transform: scale(0.65);
    }
    [data-text-animate="scaleUp"].is-animated .text-animate-word {
        opacity: 1;
        transform: scale(1);
    }

    /* ================= REDUCED MOTION ACCESSIBILITY ================= */
    @media (prefers-reduced-motion: reduce) {
        .text-animate-word {
            opacity: 1 !important;
            transform: none !important;
            filter: none !important;
            transition: none !important;
        }
    }
</style>

<script>
(() => {
    'use strict';

    function initTextAnimate() {
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // Candidate elements selector
        const selectors = [
            '[data-text-animate]',
            '.text-animate',
            'main h1:not([data-no-text-animate])',
            'main section h2:not([data-no-text-animate])',
            'main .section-heading:not([data-no-text-animate])',
            'main .page-heading:not([data-no-text-animate])',
            'main .cta-heading:not([data-no-text-animate])',
            'main [data-section-title]:not([data-no-text-animate])',
        ];

        const elements = document.querySelectorAll(selectors.join(', '));

        function isExcluded(el) {
            if (el.hasAttribute('data-no-text-animate')) return true;
            if (el.closest('nav, #navbarMain, #mobileMenu, .navbar, header.fixed, [data-nav-id]')) return true;
            if (el.closest('footer, .footer')) return true;
            if (el.closest('button, .btn, .btn-solid-press, a.btn, a[class*="btn-"]')) return true;
            if (el.closest('table, thead, tbody, tr, th, td')) return true;
            if (el.closest('.modal, #photoLightbox, #toast, .leaflet-container, .alert')) return true;
            if (el.closest('form, input, textarea, select')) return true;
            if (el.matches('article h3, article h4, .card h3, .card h4, .bento-card h3')) return true;
            if (el.closest('.card-title, .news-card-title, .agenda-card-title, .member-card')) return true;
            return false;
        }

        const observer = ('IntersectionObserver' in window) ? new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                const target = entry.target;
                const once = target.dataset.textAnimateOnce !== 'false';

                if (entry.isIntersecting) {
                    target.classList.add('is-animated');
                    if (once) {
                        obs.unobserve(target);
                    }
                } else if (!once) {
                    target.classList.remove('is-animated');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -40px 0px'
        }) : null;

        elements.forEach(el => {
            if (el.dataset.textAnimateInitialized) return;
            if (isExcluded(el)) return;

            const fullText = el.textContent.replace(/\s+/g, ' ').trim();
            if (!fullText) return;

            el.dataset.textAnimateInitialized = 'true';

            // Default animation variant: blurInUp
            const animation = el.dataset.textAnimate || 'blurInUp';
            el.setAttribute('data-text-animate', animation);

            const delay = parseFloat(el.dataset.textAnimateDelay || '0');
            const duration = parseFloat(el.dataset.textAnimateDuration || '0.45');
            const by = el.dataset.textAnimateBy || 'word';

            if (!el.hasAttribute('aria-label')) {
                el.setAttribute('aria-label', fullText);
            }

            if (prefersReducedMotion) {
                el.classList.add('is-animated');
                return;
            }

            // Screen reader container for complete, uninterrupted reading
            const srOnly = document.createElement('span');
            srOnly.className = 'text-animate-sr-only';
            srOnly.textContent = fullText;

            // Visual wrapper for animated words
            const wrapper = document.createElement('span');
            wrapper.className = 'text-animate-wrapper';
            wrapper.setAttribute('aria-hidden', 'true');

            const wordsList = [];

            // Recursive function to split words while preserving inner DOM (such as <br>, <span> badges, etc.)
            function processNode(node, targetParent) {
                if (node.nodeType === 3) { // TextNode
                    const text = node.nodeValue;
                    const parts = text.split(/(\s+)/);
                    parts.forEach(part => {
                        if (!part) return;
                        if (/^\s+$/.test(part)) {
                            targetParent.appendChild(document.createTextNode(part));
                        } else {
                            const span = document.createElement('span');
                            span.className = 'text-animate-word';
                            span.textContent = part;
                            targetParent.appendChild(span);
                            wordsList.push(span);
                        }
                    });
                } else if (node.nodeType === 1) { // ElementNode
                    const tag = node.tagName.toLowerCase();
                    if (tag === 'br') {
                        targetParent.appendChild(node.cloneNode(true));
                    } else {
                        const clone = node.cloneNode(false);
                        Array.from(node.childNodes).forEach(child => processNode(child, clone));
                        targetParent.appendChild(clone);
                    }
                }
            }

            Array.from(el.childNodes).forEach(child => processNode(child, wrapper));

            // Clear original content and mount sr-only + wrapper
            el.innerHTML = '';
            el.appendChild(srOnly);
            el.appendChild(wrapper);

            // Compute dynamic stagger based on word count (between 0.03s and 0.065s)
            const count = wordsList.length;
            const stagger = Math.max(0.03, Math.min(0.065, 0.45 / Math.max(count, 1)));

            wordsList.forEach((wordSpan, index) => {
                const wordDelay = (delay + (index * stagger)).toFixed(3);
                wordSpan.style.transitionDelay = `${wordDelay}s`;
                wordSpan.style.transitionDuration = `${duration}s`;
            });

            // If already inside viewport (e.g. Hero headings on initial load), trigger smoothly
            const rect = el.getBoundingClientRect();
            if (rect.top < window.innerHeight && rect.bottom > 0) {
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        el.classList.add('is-animated');
                    });
                });
            }

            if (observer) {
                observer.observe(el);
            } else {
                el.classList.add('is-animated');
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTextAnimate);
    } else {
        initTextAnimate();
    }

    // Expose for dynamic page transitions or turbo/livewire if needed
    window.initTextAnimate = initTextAnimate;
})();
</script>
