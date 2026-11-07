{{--
    NumberTicker Engine
    Inspired by Magic UI NumberTicker reference:
    - Counting from 0 up to target value
    - Smooth ease-out easing (1 - (1 - t)^4)
    - Viewport trigger with IntersectionObserver & once option
    - Strict accessibility: sr-only final value + aria-hidden ticker display
    - Tabular numbers to prevent layout shifts & width jitter
    - Dynamic duration proportional to number size (0.7s - 1.5s)
    - Formatting preservation (Indonesian / English separators)
    - Prefix & suffix preservation (+, %, orang, foto, etc.)
    - Reduced motion fallback (immediate final value)
--}}
<style>
    .number-ticker,
    [data-number-ticker] {
        font-variant-numeric: tabular-nums;
        font-feature-settings: "tnum";
        display: inline-block;
        vertical-align: baseline;
    }

    .number-ticker-sr-only {
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

    .number-ticker-display {
        font-variant-numeric: tabular-nums;
        font-feature-settings: "tnum";
        display: inline-block;
        vertical-align: baseline;
    }

    @media (prefers-reduced-motion: reduce) {
        .number-ticker,
        [data-number-ticker] {
            font-variant-numeric: tabular-nums;
            transition: none !important;
        }
    }
</style>

<script>
(() => {
    'use strict';

    function initNumberTickers() {
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        const selectors = [
            '[data-number-ticker]',
            '.number-ticker',
            '.stat-counter-val'
        ];

        const elements = document.querySelectorAll(selectors.join(', '));

        function getDurationForTarget(val) {
            const absVal = Math.abs(val);
            if (absVal === 0) return 0;
            if (absVal <= 10) return 750;
            if (absVal <= 50) return 900;
            if (absVal <= 500) return 1100;
            if (absVal <= 5000) return 1300;
            return Math.min(1500, Math.round(1300 + Math.log10(absVal) * 50));
        }

        function formatNumber(num, separator, decimalPlaces) {
            if (decimalPlaces > 0) {
                const parts = num.toFixed(decimalPlaces).split('.');
                const integerPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, separator);
                const decimalSep = separator === '.' ? ',' : '.';
                return integerPart + decimalSep + parts[1];
            }
            const rounded = Math.round(num);
            return rounded.toString().replace(/\B(?=(\d{3})+(?!\d))/g, separator);
        }

        const observer = ('IntersectionObserver' in window) ? new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const targetEl = entry.target;
                    obs.unobserve(targetEl);
                    if (targetEl._startNumberTicker) {
                        targetEl._startNumberTicker();
                    }
                }
            });
        }, {
            threshold: 0.15,
            rootMargin: '0px 0px -20px 0px'
        }) : null;

        elements.forEach(el => {
            if (el.dataset.numberTickerInitialized) return;
            el.dataset.numberTickerInitialized = 'true';

            // Extract initial text and data attributes
            const initialText = el.textContent.trim();
            const rawVal = el.dataset.numberTicker ?? el.dataset.count ?? el.getAttribute('data-value');

            let targetValue;
            if (rawVal !== null && rawVal !== undefined && rawVal !== '') {
                targetValue = parseFloat(rawVal);
            } else {
                // Parse clean number from inner text
                const numericMatch = initialText.replace(/[^\d.-]/g, '');
                targetValue = numericMatch ? parseFloat(numericMatch) : 0;
            }

            if (isNaN(targetValue)) {
                targetValue = 0;
            }

            const startValue = parseFloat(el.dataset.start || '0') || 0;
            const decimalPlaces = parseInt(el.dataset.decimalPlaces || '0', 10);
            const prefix = el.dataset.prefix || '';
            const suffix = el.dataset.suffix || '';

            // Detect separator: default to '.' if text had dots, ',' if comma, else '.'
            let separator = '.';
            if (initialText.includes(',')) {
                separator = ',';
            } else if (initialText.includes('.')) {
                separator = '.';
            }

            const formattedTarget = formatNumber(targetValue, separator, decimalPlaces);
            const fullFinalText = `${prefix}${formattedTarget}${suffix}`;

            // Accessibility attributes
            if (!el.hasAttribute('aria-label')) {
                el.setAttribute('aria-label', fullFinalText);
            }

            // Reduced motion: set final immediately
            if (prefersReducedMotion) {
                el.textContent = fullFinalText;
                return;
            }

            // Screen reader accessible element
            const srOnly = document.createElement('span');
            srOnly.className = 'number-ticker-sr-only';
            srOnly.textContent = fullFinalText;

            // Visual animated ticker display
            const displaySpan = document.createElement('span');
            displaySpan.className = 'number-ticker-display';
            displaySpan.setAttribute('aria-hidden', 'true');
            displaySpan.textContent = `${prefix}${formatNumber(startValue, separator, decimalPlaces)}${suffix}`;

            el.innerHTML = '';
            el.appendChild(srOnly);
            el.appendChild(displaySpan);

            const duration = parseFloat(el.dataset.duration) || getDurationForTarget(targetValue - startValue);
            const delay = (parseFloat(el.dataset.delay) || 0) * 1000;

            let animationStarted = false;

            el._startNumberTicker = () => {
                if (animationStarted) return;
                animationStarted = true;

                if (targetValue === startValue || duration <= 0) {
                    displaySpan.textContent = fullFinalText;
                    return;
                }

                let startTime = null;

                function tick(now) {
                    if (!startTime) startTime = now;
                    const elapsed = now - startTime;

                    if (elapsed < delay) {
                        requestAnimationFrame(tick);
                        return;
                    }

                    const progressTime = elapsed - delay;
                    const progress = Math.min(progressTime / duration, 1);

                    // Smooth ease-out curve (Magic UI spring-like deceleration)
                    const easeProgress = 1 - Math.pow(1 - progress, 4);
                    const currentNum = startValue + easeProgress * (targetValue - startValue);

                    displaySpan.textContent = `${prefix}${formatNumber(currentNum, separator, decimalPlaces)}${suffix}`;

                    if (progress < 1) {
                        requestAnimationFrame(tick);
                    } else {
                        // Ensure final value matches target exactly
                        displaySpan.textContent = fullFinalText;
                    }
                }

                requestAnimationFrame(tick);
            };

            // Check if already in viewport on load
            const rect = el.getBoundingClientRect();
            if (rect.top < window.innerHeight && rect.bottom > 0) {
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        if (el._startNumberTicker) {
                            el._startNumberTicker();
                        }
                    });
                });
            } else if (observer) {
                observer.observe(el);
            } else {
                el._startNumberTicker();
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initNumberTickers);
    } else {
        initNumberTickers();
    }

    // Expose for dynamic page events or Livewire/Turbolinks
    window.initNumberTickers = initNumberTickers;
})();
</script>
