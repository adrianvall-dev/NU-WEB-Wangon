{{--
    SHARED LAYOUT / MORPH TRANSITION & 3D CARD FLIP ENGINE FOR ANGGOTA PROFILES
    Adapted from Vue / Motion AnimateView Shared Name & Spring Transition Reference
    Features:
    - 3D Card Flip (Front / Back with QR & Barcode)
    - Shared element identity: member-card-{id} and member-image-{id}
    - Spring transition with visualDuration ~0.38s and light bounce (cubic-bezier)
    - Card -> Expand -> 2-Column Detail Dossier Morph
    - Profile image smooth resize & reposition morph
    - Fullscreen smooth overlay with backdrop-blur
    - Reverse transition on close: Detail -> Morph back -> Original Card
    - Close via close button, backdrop click, or Escape key
    - Responsive mobile / desktop handling
    - Full accessibility (prefers-reduced-motion & focus restoration)
--}}
<style>
    /* View Transitions standard hook (matching reference) */
    ::view-transition-image-pair(*) {
        mix-blend-mode: normal;
    }

    /* 3D Card Flip System */
    .card-3d-wrapper {
        perspective: 1200px;
    }

    .card-3d-inner {
        position: relative;
        width: 100%;
        height: 100%;
        transform-style: preserve-3d;
        -webkit-transform-style: preserve-3d;
        transition: transform 0.65s cubic-bezier(0.34, 1.25, 0.64, 1);
    }

    .card-3d-inner.is-flipped {
        transform: rotateY(180deg);
    }

    .card-face {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
    }

    .card-front {
        transform: rotateY(0deg);
        z-index: 2;
    }

    .card-back {
        transform: rotateY(180deg);
        z-index: 1;
    }

    /* Morph overlay & elements */
    .member-morph-modal {
        display: none;
    }

    .member-morph-modal.is-active {
        display: flex !important;
    }

    .member-card-item {
        will-change: transform, opacity;
    }

    .member-card-item[data-morph-active="true"] {
        opacity: 0 !important;
        pointer-events: none !important;
    }

    .member-detail-dialog {
        will-change: transform;
        transform-origin: top left;
    }

    .member-detail-dialog [data-detail-image] {
        will-change: transform;
        transform-origin: center center;
    }

    /* Lock background scroll while modal is active */
    body.member-modal-open {
        overflow: hidden !important;
    }

    /* Accessibility: Respect Reduced Motion */
    @media (prefers-reduced-motion: reduce) {
        .card-3d-inner {
            transition: none !important;
        }
        .member-card-item,
        .member-detail-dialog,
        .member-detail-dialog [data-detail-image],
        .member-morph-backdrop {
            transform: none !important;
            transition: none !important;
            will-change: auto !important;
        }
    }
</style>

<script>
(function() {
    'use strict';

    if (window.__anggotaMorphEngineLoaded) return;
    window.__anggotaMorphEngineLoaded = true;

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const OPEN_DURATION = 380;   // visualDuration ~0.38s
    const CLOSE_DURATION = 320;  // visualDuration ~0.32s
    const OPEN_SPRING = 'cubic-bezier(0.34, 1.25, 0.64, 1)'; // spring with light bounce (bounce ~0.25)
    const CLOSE_SPRING = 'cubic-bezier(0.25, 1, 0.5, 1)';    // spring deceleration return

    let activeMemberId = null;
    let isAnimating = false;
    let lastActiveElement = null;

    /**
     * Open Member Profile with Shared Morph Transition
     */
    function openMemberProfile(id) {
        if (!id || isAnimating || activeMemberId === id) return;

        const sourceCard = document.querySelector(`[data-shared-card="member-card-${id}"]`);
        const modal = document.getElementById(`modalProfil${id}`);
        if (!sourceCard || !modal) return;

        const sourceImg = sourceCard.querySelector(`[data-shared-image="member-image-${id}"]`);
        const targetDialog = modal.querySelector(`[data-detail-dialog="${id}"]`);
        const targetImg = modal.querySelector(`[data-detail-image="${id}"]`);
        const backdrop = modal.querySelector('.member-morph-backdrop');
        const detailBody = modal.querySelector('.member-detail-body');
        const detailFooter = modal.querySelector('.member-detail-footer');

        if (!targetDialog) return;

        lastActiveElement = document.activeElement;

        // 1. Measure FIRST state (Source Card in Grid)
        const firstCardRect = sourceCard.getBoundingClientRect();
        const firstImgRect = sourceImg ? sourceImg.getBoundingClientRect() : null;

        // 2. Prepare Target Modal
        modal.style.display = 'flex';
        modal.classList.add('is-active');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('member-modal-open');

        // 3. Measure LAST state (Target Dialog Centered)
        const lastCardRect = targetDialog.getBoundingClientRect();
        const lastImgRect = targetImg ? targetImg.getBoundingClientRect() : null;

        // If user prefers reduced motion, simply fade in
        if (prefersReducedMotion) {
            targetDialog.style.opacity = '1';
            targetDialog.style.transform = 'none';
            if (backdrop) backdrop.style.opacity = '1';
            activeMemberId = id;
            const closeBtn = modal.querySelector('[data-close-member]');
            if (closeBtn) closeBtn.focus();
            return;
        }

        // 4. Calculate INVERT Transforms
        const deltaX = firstCardRect.left - lastCardRect.left;
        const deltaY = firstCardRect.top - lastCardRect.top;
        const scaleX = firstCardRect.width / lastCardRect.width;
        const scaleY = firstCardRect.height / lastCardRect.height;

        let compX = 0, compY = 0, compScale = 1;
        if (firstImgRect && lastImgRect && scaleX > 0 && scaleY > 0) {
            const imgFirstCenterX = firstImgRect.left + firstImgRect.width / 2;
            const imgFirstCenterY = firstImgRect.top + firstImgRect.height / 2;
            const relTargetImgX = (lastImgRect.left - lastCardRect.left);
            const relTargetImgY = (lastImgRect.top - lastCardRect.top);
            const uncompensatedScreenCenterX = firstCardRect.left + (relTargetImgX + lastImgRect.width / 2) * scaleX;
            const uncompensatedScreenCenterY = firstCardRect.top + (relTargetImgY + lastImgRect.height / 2) * scaleY;
            compX = (imgFirstCenterX - uncompensatedScreenCenterX) / scaleX;
            compY = (imgFirstCenterY - uncompensatedScreenCenterY) / scaleY;
            compScale = (firstImgRect.width / lastImgRect.width) / scaleX;
        }

        // 5. Apply Invert State
        targetDialog.style.transformOrigin = 'top left';
        targetDialog.style.transform = `translate3d(${deltaX.toFixed(2)}px, ${deltaY.toFixed(2)}px, 0) scale(${scaleX.toFixed(4)}, ${scaleY.toFixed(4)})`;
        targetDialog.style.transition = 'none';

        if (targetImg && firstImgRect) {
            targetImg.style.transformOrigin = 'center center';
            targetImg.style.transform = `translate3d(${compX.toFixed(2)}px, ${compY.toFixed(2)}px, 0) scale(${compScale.toFixed(4)})`;
            targetImg.style.transition = 'none';
        }

        if (backdrop) {
            backdrop.style.transition = 'none';
            backdrop.style.opacity = '0';
        }

        if (detailBody) {
            detailBody.style.transition = 'none';
            detailBody.style.opacity = '0';
        }
        if (detailFooter) {
            detailFooter.style.transition = 'none';
            detailFooter.style.opacity = '0';
        }

        // Hide origin card in grid (avoid ghost duplicate)
        sourceCard.style.opacity = '0';
        sourceCard.setAttribute('data-morph-active', 'true');

        // Force browser reflow
        targetDialog.offsetHeight;

        // 6. PLAY Spring Transition
        isAnimating = true;
        activeMemberId = id;

        targetDialog.style.transition = `transform ${OPEN_DURATION}ms ${OPEN_SPRING}`;
        targetDialog.style.transform = 'translate3d(0, 0, 0) scale(1, 1)';

        if (targetImg) {
            targetImg.style.transition = `transform ${OPEN_DURATION}ms ${OPEN_SPRING}`;
            targetImg.style.transform = 'translate3d(0, 0, 0) scale(1)';
        }

        if (backdrop) {
            backdrop.style.transition = 'opacity 320ms ease';
            backdrop.style.opacity = '1';
        }

        if (detailBody) {
            detailBody.style.transition = 'opacity 220ms ease 120ms';
            detailBody.style.opacity = '1';
        }
        if (detailFooter) {
            detailFooter.style.transition = 'opacity 220ms ease 150ms';
            detailFooter.style.opacity = '1';
        }

        setTimeout(() => {
            isAnimating = false;
            const closeBtn = modal.querySelector('[data-close-member]');
            if (closeBtn) closeBtn.focus();
        }, OPEN_DURATION + 20);
    }

    /**
     * Close Member Profile with Reverse Morph Transition
     */
    function closeActiveMemberProfile() {
        if (!activeMemberId || isAnimating) return;

        const id = activeMemberId;
        const sourceCard = document.querySelector(`[data-shared-card="member-card-${id}"]`);
        const modal = document.getElementById(`modalProfil${id}`);
        if (!modal) {
            activeMemberId = null;
            document.body.classList.remove('member-modal-open');
            return;
        }

        const sourceImg = sourceCard ? sourceCard.querySelector(`[data-shared-image="member-image-${id}"]`) : null;
        const targetDialog = modal.querySelector(`[data-detail-dialog="${id}"]`);
        const targetImg = modal.querySelector(`[data-detail-image="${id}"]`);
        const backdrop = modal.querySelector('.member-morph-backdrop');
        const detailBody = modal.querySelector('.member-detail-body');
        const detailFooter = modal.querySelector('.member-detail-footer');

        if (!targetDialog || !sourceCard || prefersReducedMotion) {
            modal.style.display = 'none';
            modal.classList.remove('is-active');
            modal.setAttribute('aria-hidden', 'true');
            if (sourceCard) {
                sourceCard.style.opacity = '1';
                sourceCard.removeAttribute('data-morph-active');
            }
            document.body.classList.remove('member-modal-open');
            activeMemberId = null;
            if (lastActiveElement) lastActiveElement.focus();
            return;
        }

        // Measure Current Positions for Clean Reverse
        const currCardRect = sourceCard.getBoundingClientRect();
        const currImgRect = sourceImg ? sourceImg.getBoundingClientRect() : null;
        const currDialogRect = targetDialog.getBoundingClientRect();
        const currTargetImgRect = targetImg ? targetImg.getBoundingClientRect() : null;

        const closeDeltaX = currCardRect.left - currDialogRect.left;
        const closeDeltaY = currCardRect.top - currDialogRect.top;
        const closeScaleX = currCardRect.width / currDialogRect.width;
        const closeScaleY = currCardRect.height / currDialogRect.height;

        let closeCompX = 0, closeCompY = 0, closeCompScale = 1;
        if (currImgRect && currTargetImgRect && closeScaleX > 0 && closeScaleY > 0) {
            const closeImgCenterX = currImgRect.left + currImgRect.width / 2;
            const closeImgCenterY = currImgRect.top + currImgRect.height / 2;
            const relTargetImgX = (currTargetImgRect.left - currDialogRect.left);
            const relTargetImgY = (currTargetImgRect.top - currDialogRect.top);
            const uncompensatedScreenCenterX = currCardRect.left + (relTargetImgX + currTargetImgRect.width / 2) * closeScaleX;
            const uncompensatedScreenCenterY = currCardRect.top + (relTargetImgY + currTargetImgRect.height / 2) * closeScaleY;
            closeCompX = (closeImgCenterX - uncompensatedScreenCenterX) / closeScaleX;
            closeCompY = (closeImgCenterY - uncompensatedScreenCenterY) / closeScaleY;
            closeCompScale = (currImgRect.width / currTargetImgRect.width) / closeScaleX;
        }

        // PLAY Reverse Morph Transition
        isAnimating = true;

        if (detailBody) {
            detailBody.style.transition = 'opacity 120ms ease';
            detailBody.style.opacity = '0';
        }
        if (detailFooter) {
            detailFooter.style.transition = 'opacity 120ms ease';
            detailFooter.style.opacity = '0';
        }

        if (backdrop) {
            backdrop.style.transition = 'opacity 260ms ease';
            backdrop.style.opacity = '0';
        }

        targetDialog.style.transition = `transform ${CLOSE_DURATION}ms ${CLOSE_SPRING}`;
        targetDialog.style.transform = `translate3d(${closeDeltaX.toFixed(2)}px, ${closeDeltaY.toFixed(2)}px, 0) scale(${closeScaleX.toFixed(4)}, ${closeScaleY.toFixed(4)})`;

        if (targetImg && currImgRect) {
            targetImg.style.transition = `transform ${CLOSE_DURATION}ms ${CLOSE_SPRING}`;
            targetImg.style.transform = `translate3d(${closeCompX.toFixed(2)}px, ${closeCompY.toFixed(2)}px, 0) scale(${closeCompScale.toFixed(4)})`;
        }

        setTimeout(() => {
            modal.style.display = 'none';
            modal.classList.remove('is-active');
            modal.setAttribute('aria-hidden', 'true');
            targetDialog.style.transform = '';
            targetDialog.style.transition = '';
            if (targetImg) {
                targetImg.style.transform = '';
                targetImg.style.transition = '';
            }
            sourceCard.style.opacity = '1';
            sourceCard.removeAttribute('data-morph-active');
            document.body.classList.remove('member-modal-open');
            isAnimating = false;
            activeMemberId = null;

            if (lastActiveElement) {
                lastActiveElement.focus();
            } else {
                const triggerBtn = sourceCard.querySelector('[data-open-member]');
                if (triggerBtn) triggerBtn.focus();
            }
        }, CLOSE_DURATION + 20);
    }

    // Window Delegation Event Listeners
    document.addEventListener('click', function(e) {
        // 1. 3D FLIP TRIGGER (Toggles Front / Back on Card)
        const flipBtn = e.target.closest('[data-flip-target]');
        if (flipBtn) {
            e.preventDefault();
            e.stopPropagation();
            const targetId = flipBtn.getAttribute('data-flip-target');
            if (window.Card3DFlipEngine && typeof window.Card3DFlipEngine.flip === 'function') {
                window.Card3DFlipEngine.flip(targetId);
            } else {
                const flipEl = document.getElementById(targetId);
                if (flipEl) {
                    flipEl.classList.toggle('is-flipped');
                }
            }
            return;
        }

        // 2. OPEN TRIGGER (Card item or Profil button outside open modal)
        const openTrigger = e.target.closest('[data-open-member]');
        if (openTrigger && !openTrigger.closest('.member-morph-modal')) {
            e.preventDefault();
            e.stopPropagation();
            const id = openTrigger.getAttribute('data-open-member');
            openMemberProfile(id);
            return;
        }

        // 3. CLOSE TRIGGER (X button, Tutup button, or backdrop)
        const closeTrigger = e.target.closest('[data-close-member], [data-bs-dismiss="modal"]');
        if (closeTrigger) {
            e.preventDefault();
            e.stopPropagation();
            closeActiveMemberProfile();
            return;
        }

        // 4. Click directly on modal backdrop area outside dialog
        const modalContainer = e.target.closest('.member-morph-modal');
        if (modalContainer && !e.target.closest('.member-detail-dialog')) {
            closeActiveMemberProfile();
            return;
        }
    });

    // Keyboard Accessibility (Escape to close, Enter/Space to open focused card)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && activeMemberId) {
            e.preventDefault();
            closeActiveMemberProfile();
            return;
        }

        if ((e.key === 'Enter' || e.key === ' ') && !activeMemberId) {
            const focusedCard = document.activeElement ? document.activeElement.closest('[data-open-member]') : null;
            if (focusedCard && !focusedCard.closest('.member-morph-modal')) {
                e.preventDefault();
                const id = focusedCard.getAttribute('data-open-member');
                openMemberProfile(id);
            }
        }
    });

    // Expose API for external scripts or testing
    window.AnggotaMorphEngine = {
        open: openMemberProfile,
        close: closeActiveMemberProfile,
        getActiveId: () => activeMemberId,
        isAnimating: () => isAnimating
    };
})();
</script>

@include('anggota.partials.card-3d-flip-engine')
