{{--
    3D PHYSICAL FLIPCARD INTERACTION ENGINE (VANILLA JS SPRING PHYSICS)
    Matches Reference FlipCard Architecture:
    - 3D Flip (Front / Back rotateY with backface-visibility: hidden)
    - Click to Flip
    - Horizontal Drag / Swipe to Flip with pointerCapture
    - Velocity-based snap & fling (stiffness ~170, damping ~20)
    - Mouse Tilt up to 12deg on desktop
    - Glare reflection tracking mouse coordinates (--fc-gx, --fc-gy)
    - Hover scale (1.025) & Depth shadow
    - Mobile-friendly touch-action: pan-y (unobstructed vertical scrolling)
    - Keyboard accessibility (role="button", tabindex="0", Enter / Space)
    - Reduced motion accessibility (@media (prefers-reduced-motion: reduce))
--}}
<style>
    /* 3D Card Architecture & Gestures */
    .card-3d-wrapper {
        perspective: 1200px;
        -webkit-perspective: 1200px;
        touch-action: pan-y;
        user-select: none;
        -webkit-user-select: none;
        -webkit-touch-callout: none;
        -webkit-tap-highlight-color: transparent;
        outline: none;
        position: relative;
    }

    .card-3d-wrapper[data-draggable] {
        cursor: grab;
    }

    .card-3d-wrapper[data-dragging] {
        cursor: grabbing;
    }

    .card-3d-inner {
        position: relative;
        width: 100%;
        height: 100%;
        transform-style: preserve-3d;
        -webkit-transform-style: preserve-3d;
        will-change: transform;
    }

    /* Fallback CSS transition when JS spring is idle or not hydrated */
    .card-3d-inner:not([data-spring-active]) {
        transition: transform 0.65s cubic-bezier(0.34, 1.25, 0.64, 1);
    }

    .card-3d-inner.is-flipped:not([data-spring-active]) {
        transform: rotateY(180deg);
    }

    .card-face {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        overflow: hidden;
    }

    .card-front {
        transform: rotateY(0deg);
        z-index: 2;
    }

    .card-back {
        transform: rotateY(180deg);
        z-index: 1;
    }

    /* 3D Physical Depth Shadow (Adapts with card tilt & rotation) */
    .card-3d-shadow {
        position: absolute;
        inset: 8% 7% -5%;
        border-radius: 1.5rem;
        background: rgba(14, 34, 27, 0.30);
        filter: blur(20px);
        -webkit-filter: blur(20px);
        pointer-events: none;
        z-index: -1;
        transform-origin: center center;
        transition: opacity 0.2s ease;
    }

    /* Glare Reflection Layer following mouse pointer */
    .card-face-glare {
        position: absolute;
        inset: 0;
        border-radius: inherit;
        pointer-events: none;
        background: radial-gradient(
            circle farthest-side at var(--fc-gx, 50%) var(--fc-gy, 50%),
            rgba(255, 255, 255, var(--fc-glare, 0.20)) 0%,
            rgba(255, 255, 255, calc(var(--fc-glare, 0.20) * 0.74)) 14%,
            rgba(255, 255, 255, calc(var(--fc-glare, 0.20) * 0.38)) 30%,
            rgba(255, 255, 255, calc(var(--fc-glare, 0.20) * 0.10)) 55%,
            rgba(255, 255, 255, 0) 75%
        );
        opacity: var(--fc-sheen, 0);
        transition: opacity 220ms ease;
        z-index: 35;
        mix-blend-mode: overlay;
    }

    @media (prefers-reduced-motion: reduce) {
        .card-face-glare {
            display: none !important;
        }
        .card-3d-shadow {
            display: none !important;
        }
        .card-3d-inner {
            transition: transform 0.25s ease !important;
        }
    }
</style>

<script>
(function() {
    'use strict';

    if (window.__card3DFlipEngineLoaded) return;
    window.__card3DFlipEngineLoaded = true;

    // Physics Constants (Reference Match)
    const SLOP = { fine: 4, coarse: 8 };
    const TILT_SPRING = { stiffness: 240, damping: 24, mass: 0.6 };
    const LIFT_SPRING = { stiffness: 320, damping: 26, mass: 1.0 };
    const TURN_SPRING = { stiffness: 170, damping: 20, mass: 1.0 };
    const SHEEN_SPRING = { stiffness: 320, damping: 26, mass: 1.0 };
    const FLING = 0.16;
    const HISTORY_MS = 90;
    const TILT_MAX = 12;
    const HOVER_SCALE = 1.025;
    const GLARE_OPACITY = 0.20;
    const PERSPECTIVE = 1200;

    const clamp = (v, lo, hi) => Math.min(hi, Math.max(lo, v));
    const snap = deg => Math.round(deg / 180) * 180;
    const isBack = deg => Math.abs(Math.round(deg / 180)) % 2 === 1;

    // Semi-implicit Euler Spring Oscillator
    function createSpring(stiffness, damping, mass = 1.0, restDelta = 0.05) {
        let current = 0;
        let target = 0;
        let velocity = 0;

        return {
            get: () => current,
            getTarget: () => target,
            getVelocity: () => velocity,
            set: (v) => { current = v; target = v; velocity = 0; },
            setTarget: (t) => { target = t; },
            setVelocity: (v) => { velocity = v; },
            jump: (v) => { current = v; target = v; velocity = 0; },
            isAtRest: () => Math.abs(current - target) < restDelta && Math.abs(velocity) < restDelta,
            update: (dt) => {
                const subDt = dt / 2;
                for (let i = 0; i < 2; i++) {
                    const springForce = -stiffness * (current - target);
                    const dampingForce = -damping * velocity;
                    const acc = (springForce + dampingForce) / mass;
                    velocity += acc * subDt;
                    current += velocity * subDt;
                }
                if (Math.abs(current - target) < restDelta && Math.abs(velocity) < restDelta) {
                    current = target;
                    velocity = 0;
                    return true;
                }
                return false;
            }
        };
    }

    // Active Card Registry
    const cardInstances = new Map();

    function initCard(wrapper) {
        if (!wrapper || cardInstances.has(wrapper)) return cardInstances.get(wrapper);

        const rotor = wrapper.querySelector('.card-3d-inner');
        if (!rotor) return null;

        const frontFace = rotor.querySelector('.card-front');
        const backFace = rotor.querySelector('.card-back');
        if (!frontFace || !backFace) return null;

        // Check reduced motion
        const prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // Accessibility attributes on wrapper
        if (!wrapper.hasAttribute('role')) wrapper.setAttribute('role', 'button');
        if (!wrapper.hasAttribute('tabindex')) wrapper.setAttribute('tabindex', '0');
        wrapper.setAttribute('data-draggable', '');
        
        const initialFlipped = rotor.classList.contains('is-flipped');
        wrapper.setAttribute('aria-pressed', initialFlipped ? 'true' : 'false');
        if (!wrapper.hasAttribute('aria-label')) {
            wrapper.setAttribute('aria-label', 'Kartu Identitas Digital, klik atau geser untuk membalik');
        }

        // Attach Physical Depth Shadow
        let shadow = wrapper.querySelector('.card-3d-shadow');
        if (!shadow) {
            shadow = document.createElement('span');
            shadow.className = 'card-3d-shadow';
            shadow.setAttribute('aria-hidden', 'true');
            wrapper.insertBefore(shadow, wrapper.firstChild);
        }

        // Attach Glare Layers
        let frontGlare = frontFace.querySelector('.card-face-glare');
        if (!frontGlare) {
            frontGlare = document.createElement('span');
            frontGlare.className = 'card-face-glare';
            frontGlare.setAttribute('aria-hidden', 'true');
            frontFace.appendChild(frontGlare);
        }

        let backGlare = backFace.querySelector('.card-face-glare');
        if (!backGlare) {
            backGlare = document.createElement('span');
            backGlare.className = 'card-face-glare';
            backGlare.setAttribute('aria-hidden', 'true');
            backFace.appendChild(backGlare);
        }

        // Springs
        const initialAngle = initialFlipped ? 180 : 0;
        const turn = createSpring(TURN_SPRING.stiffness, TURN_SPRING.damping, TURN_SPRING.mass, 0.04);
        turn.set(initialAngle);

        const tiltX = createSpring(TILT_SPRING.stiffness, TILT_SPRING.damping, TILT_SPRING.mass, 0.05);
        const tiltY = createSpring(TILT_SPRING.stiffness, TILT_SPRING.damping, TILT_SPRING.mass, 0.05);
        const lift = createSpring(LIFT_SPRING.stiffness, LIFT_SPRING.damping, LIFT_SPRING.mass, 0.002);
        lift.set(1.0);

        const sheen = createSpring(SHEEN_SPRING.stiffness, SHEEN_SPRING.damping, SHEEN_SPRING.mass, 0.01);
        sheen.set(0.0);

        let gx = 50;
        let gy = 50;
        let grip = null;
        let isDragging = false;
        let isHovered = false;
        let isRunning = false;
        let lastTime = performance.now();

        // Mark rotor as spring active
        rotor.setAttribute('data-spring-active', 'true');
        rotor.style.transition = 'none';

        function renderFrame() {
            const currentTurn = turn.get();
            const currentTiltX = tiltX.get();
            const currentTiltY = tiltY.get();
            const currentLift = lift.get();
            const currentSheen = sheen.get();

            if (prefersReduced) {
                const isBackSide = isBack(turn.getTarget());
                rotor.style.transform = isBackSide ? 'rotateY(180deg)' : 'rotateY(0deg)';
                return;
            }

            // 3D Matrix Composition
            const totalRotX = currentTiltX;
            const totalRotY = currentTurn + currentTiltY;

            rotor.style.transform = `perspective(${PERSPECTIVE}px) scale(${currentLift.toFixed(4)}) rotateX(${totalRotX.toFixed(2)}deg) rotateY(${totalRotY.toFixed(2)}deg)`;

            // Depth shadow deformation based on facing angle & lift
            if (shadow) {
                const turnRad = (currentTurn * Math.PI) / 180;
                const facing = Math.abs(Math.cos(turnRad));
                const spread = (0.10 + 0.90 * facing) * currentLift;
                const shade = 0.12 + 0.88 * facing * facing;

                shadow.style.transform = `scaleX(${spread.toFixed(3)}) scaleY(${currentLift.toFixed(3)})`;
                shadow.style.opacity = `${(shade * 0.35).toFixed(3)}`;
            }

            // Glare dynamic properties
            rotor.style.setProperty('--fc-gx', `${gx.toFixed(1)}%`);
            rotor.style.setProperty('--fc-gy', `${gy.toFixed(1)}%`);
            rotor.style.setProperty('--fc-sheen', currentSheen.toFixed(3));
            rotor.style.setProperty('--fc-glare', GLARE_OPACITY);
        }

        function requestTick() {
            if (!isRunning) {
                isRunning = true;
                lastTime = performance.now();
                requestAnimationFrame(tick);
            }
        }

        function tick(now) {
            const dt = Math.min(0.032, Math.max(0.001, (now - lastTime) / 1000));
            lastTime = now;

            const turnRest = turn.update(dt);
            const tiltXRest = tiltX.update(dt);
            const tiltYRest = tiltY.update(dt);
            const liftRest = lift.update(dt);
            const sheenRest = sheen.update(dt);

            renderFrame();

            const allRest = turnRest && tiltXRest && tiltYRest && liftRest && sheenRest;

            if (!allRest || isDragging) {
                requestAnimationFrame(tick);
            } else {
                isRunning = false;
                syncRestState();
            }
        }

        function syncRestState() {
            const back = isBack(turn.get());
            if (back) {
                rotor.classList.add('is-flipped');
                wrapper.setAttribute('aria-pressed', 'true');
            } else {
                rotor.classList.remove('is-flipped');
                wrapper.setAttribute('aria-pressed', 'false');
            }

            frontFace.setAttribute('aria-hidden', back ? 'true' : 'false');
            backFace.setAttribute('aria-hidden', back ? 'false' : 'true');
        }

        function settle(to, velocity = 0) {
            turn.setTarget(to);
            if (velocity !== 0) {
                turn.setVelocity(velocity);
            }
            if (prefersReduced) {
                turn.jump(to);
                syncRestState();
            }
            requestTick();
        }

        function flip(instant = false) {
            const base = snap(turn.get());
            const target = isBack(base) ? base - 180 : base + 180;
            if (instant || prefersReduced) {
                turn.jump(target);
                renderFrame();
                syncRestState();
            } else {
                settle(target, 0);
            }
        }

        function resetHover() {
            tiltX.setTarget(0);
            tiltY.setTarget(0);
            sheen.setTarget(0);
            lift.setTarget(1.0);
            requestTick();
        }

        // Pointer Event Handlers
        function onPointerDown(e) {
            if (e.button !== 0) return;

            // Do not intercept external actions or profile modals
            if (e.target.closest('[data-open-member], [data-close-member], a:not([data-flip-target])')) {
                return;
            }

            try {
                wrapper.setPointerCapture(e.pointerId);
            } catch (err) {}

            grip = {
                id: e.pointerId,
                startX: e.clientX,
                startY: e.clientY,
                baseTurn: turn.get(),
                moved: false,
                slop: e.pointerType === 'touch' ? SLOP.coarse : SLOP.fine,
                hist: [{ t: performance.now(), v: turn.get() }]
            };

            if (!prefersReduced && e.pointerType !== 'touch') {
                lift.setTarget(HOVER_SCALE);
                requestTick();
            }
        }

        function onPointerMove(e) {
            if (grip && grip.id === e.pointerId) {
                const dx = e.clientX - grip.startX;
                const dy = e.clientY - grip.startY;

                if (!grip.moved) {
                    // Mobile vertical scrolling check: if swipe is predominantly vertical, abort gesture
                    if (e.pointerType === 'touch' && Math.abs(dy) > Math.abs(dx) && Math.abs(dy) > grip.slop) {
                        try {
                            wrapper.releasePointerCapture(e.pointerId);
                        } catch (err) {}
                        grip = null;
                        return;
                    }

                    if (Math.abs(dx) < grip.slop) {
                        return;
                    }

                    grip.moved = true;
                    isDragging = true;
                    wrapper.setAttribute('data-dragging', '');
                    tiltX.jump(0);
                    tiltY.jump(0);
                    sheen.jump(0);
                }

                const cardWidth = wrapper.getBoundingClientRect().width || 320;
                const deg = grip.baseTurn + (dx / cardWidth) * 180;

                turn.set(deg);
                turn.setTarget(deg);

                const now = performance.now();
                grip.hist.push({ t: now, v: deg });
                while (grip.hist.length > 2 && now - grip.hist[0].t > HISTORY_MS) {
                    grip.hist.shift();
                }

                requestTick();
                return;
            }

            // Desktop Mouse Tilt & Glare
            if (isDragging || prefersReduced || e.pointerType === 'touch') return;

            const rect = wrapper.getBoundingClientRect();
            const px = clamp((e.clientX - rect.left) / rect.width, 0, 1);
            const py = clamp((e.clientY - rect.top) / rect.height, 0, 1);

            tiltX.setTarget((0.5 - py) * 2 * TILT_MAX);
            tiltY.setTarget((px - 0.5) * 2 * TILT_MAX);
            gx = px * 100;
            gy = py * 100;
            sheen.setTarget(1);
            lift.setTarget(HOVER_SCALE);
            requestTick();
        }

        function onPointerUp(e) {
            if (!grip || grip.id !== e.pointerId) return;

            const currentGrip = grip;
            grip = null;

            try {
                wrapper.releasePointerCapture(e.pointerId);
            } catch (err) {}

            wrapper.removeAttribute('data-dragging');
            isDragging = false;

            if (e.pointerType === 'touch' || !wrapper.matches(':hover')) {
                resetHover();
            }

            if (!currentGrip.moved) {
                // Click / Tap triggered
                if (e.target.closest('[data-open-member]')) {
                    settle(snap(turn.get()), 0);
                    return;
                }
                flip(false);
                return;
            }

            // Drag Release Velocity & Fling Snap
            const currentAngle = turn.get();
            let velocity = 0;
            const a = currentGrip.hist[0];
            const b = currentGrip.hist[currentGrip.hist.length - 1];

            if (a && b && b.t > a.t && (performance.now() - b.t < 75)) {
                velocity = ((b.v - a.v) / (b.t - a.t)) * 1000;
            }

            const projected = currentAngle + velocity * FLING;
            const snapTarget = snap(projected);
            const clampedTarget = clamp(snapTarget, snap(currentGrip.baseTurn) - 180, snap(currentGrip.baseTurn) + 180);

            settle(clampedTarget, velocity);
        }

        function onPointerCancel(e) {
            if (!grip || grip.id !== e.pointerId) return;
            grip = null;
            isDragging = false;
            wrapper.removeAttribute('data-dragging');
            resetHover();
            settle(snap(turn.get()), 0);
        }

        function onPointerEnter(e) {
            if (prefersReduced || e.pointerType === 'touch') return;
            isHovered = true;
            lift.setTarget(HOVER_SCALE);
            requestTick();
        }

        function onPointerLeave(e) {
            isHovered = false;
            if (!grip) {
                resetHover();
            }
        }

        function onKeyDown(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                if (e.target.closest('[data-open-member], a, input, select')) return;
                e.preventDefault();
                flip(false);
            }
        }

        // Attach Event Listeners
        wrapper.addEventListener('pointerdown', onPointerDown);
        wrapper.addEventListener('pointermove', onPointerMove);
        wrapper.addEventListener('pointerup', onPointerUp);
        wrapper.addEventListener('pointercancel', onPointerCancel);
        wrapper.addEventListener('pointerenter', onPointerEnter);
        wrapper.addEventListener('pointerleave', onPointerLeave);
        wrapper.addEventListener('keydown', onKeyDown);

        // Initial Frame
        renderFrame();
        syncRestState();

        const instance = {
            wrapper,
            rotor,
            flip,
            settle,
            isFlipped: () => isBack(turn.get()),
            getAngle: () => turn.get()
        };

        cardInstances.set(wrapper, instance);
        wrapper._flipCard3D = instance;

        return instance;
    }

    // Scan & initialize all cards in document
    function initAll() {
        const wrappers = document.querySelectorAll('.card-3d-wrapper');
        wrappers.forEach(initCard);
    }

    // Expose global bridge and override toggleCardFlip smoothly
    window.Card3DFlipEngine = {
        init: initCard,
        initAll: initAll,
        flip: (target) => {
            let wrapper = null;
            if (typeof target === 'string') {
                const el = document.getElementById(target);
                if (el) wrapper = el.closest('.card-3d-wrapper');
            } else if (target instanceof HTMLElement) {
                wrapper = target.closest('.card-3d-wrapper');
            }

            if (wrapper) {
                const instance = cardInstances.get(wrapper) || initCard(wrapper);
                if (instance) {
                    instance.flip();
                    return;
                }
            }

            // Fallback
            if (typeof target === 'string') {
                const flipEl = document.getElementById(target);
                if (flipEl) flipEl.classList.toggle('is-flipped');
            }
        }
    };

    // Override toggleCardFlip to use 3D engine smoothly
    window.toggleCardFlip = function(targetId) {
        window.Card3DFlipEngine.flip(targetId);
    };

    // Auto-init on DOMContentLoaded or immediately
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }

    // Observer for dynamically injected cards (e.g. modals)
    if ('MutationObserver' in window) {
        const observer = new MutationObserver((mutations) => {
            let hasNewCard = false;
            for (const m of mutations) {
                for (const node of m.addedNodes) {
                    if (node.nodeType === 1 && (node.matches?.('.card-3d-wrapper') || node.querySelector?.('.card-3d-wrapper'))) {
                        hasNewCard = true;
                        break;
                    }
                }
                if (hasNewCard) break;
            }
            if (hasNewCard) initAll();
        });
        observer.observe(document.body || document.documentElement, { childList: true, subtree: true });
    }
})();
</script>
