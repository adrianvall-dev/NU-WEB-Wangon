import { gsap } from 'gsap';
import '../css/history-photo-masonry.css';

/**
 * History Photo Masonry Engine — Adapted from React Bits Masonry
 * Strictly isolated for Admin History Kegiatan Photo Documentation (.history-photo-masonry)
 * 
 * Features:
 * - Natural aspect ratio preservation (portrait remains portrait, landscape remains landscape)
 * - Shortest-column packing algorithm
 * - GSAP entrance animation with power3.out, stagger, and blur-to-focus
 * - Smooth ResizeObserver re-layout
 * - Hover scale 0.97
 * - High-definition photo preview modal with keyboard & touch accessibility
 * - Complete isolation from global website animations
 */
export class HistoryPhotoMasonry {
  constructor(containerEl, options = {}) {
    this.container = containerEl;
    this.listEl = containerEl.querySelector('.history-photo-masonry__list');
    if (!this.listEl) return;

    this.options = Object.assign({
      ease: 'power3.out',
      duration: 0.7,
      stagger: 0.05,
      scaleOnHover: true,
      hoverScale: 0.97,
      blurToFocus: true,
      gap: 16,
      reducedMotion: window.matchMedia?.('(prefers-reduced-motion: reduce)').matches ?? false,
    }, options);

    this.items = [];
    this.hasMounted = false;
    this.resizeObserver = null;
    this.cachedWidth = 0;
    this.isLayoutRunning = false;

    this.init();
  }

  init() {
    const itemNodes = Array.from(this.listEl.querySelectorAll('.history-photo-masonry__item'));
    if (!itemNodes.length) return;

    this.items = itemNodes.map((el, idx) => {
      const card = el.querySelector('.history-photo-masonry__card');
      const media = el.querySelector('.history-photo-masonry__media');
      const img = el.querySelector('.history-photo-masonry__img');
      const footer = el.querySelector('.history-photo-masonry__footer');

      return {
        id: el.dataset.id || `photo_${idx}`,
        el,
        card,
        media,
        img,
        footer,
        imgSrc: img ? (img.currentSrc || img.src) : null,
        naturalWidth: img && img.naturalWidth ? img.naturalWidth : 0,
        naturalHeight: img && img.naturalHeight ? img.naturalHeight : 0,
        aspectRatio: 1.0, // default square, will be computed from image
        targetX: 0,
        targetY: 0,
        targetW: 0,
        targetH: 0,
      };
    });

    // 1. Preload image dimensions to ensure exact aspect ratios
    this.preloadImageDimensions().then(() => {
      this.calculateLayout();
      this.mountEntrance();
      this.setupResizeObserver();
      this.setupInspectModal();
    });
  }

  /**
   * Preload natural image dimensions for all cards
   */
  async preloadImageDimensions() {
    const promises = this.items.map(item => {
      return new Promise((resolve) => {
        if (!item.img) return resolve();

        if (item.img.complete && item.img.naturalWidth > 0) {
          item.naturalWidth = item.img.naturalWidth;
          item.naturalHeight = item.img.naturalHeight;
          item.aspectRatio = item.naturalHeight / item.naturalWidth;
          return resolve();
        }

        const probe = new Image();
        probe.src = item.imgSrc || item.img.src;
        probe.onload = () => {
          item.naturalWidth = probe.naturalWidth;
          item.naturalHeight = probe.naturalHeight;
          item.aspectRatio = probe.naturalHeight / probe.naturalWidth;
          resolve();
        };
        probe.onerror = () => {
          // Fallback aspect ratio if image fails to load
          item.aspectRatio = 1.0;
          resolve();
        };
      });
    });

    await Promise.all(promises);
  }

  /**
   * Determine column count based on container width
   */
  getColumnCount(width) {
    if (width >= 1200) return 4;
    if (width >= 860) return 3;
    if (width >= 480) return 2;
    return 1;
  }

  /**
   * Calculate shortest-column layout coordinates
   */
  calculateLayout() {
    const containerWidth = Math.floor(this.listEl.getBoundingClientRect().width) || this.listEl.parentElement.clientWidth;
    if (!containerWidth || containerWidth <= 0) return;

    this.cachedWidth = containerWidth;
    const columns = this.getColumnCount(containerWidth);
    const gap = containerWidth < 640 ? 12 : this.options.gap;
    const totalGaps = (columns - 1) * gap;
    const colWidth = Math.floor((containerWidth - totalGaps) / columns);

    const colHeights = new Array(columns).fill(0);

    this.items.forEach(item => {
      // Clamp ratio between 0.6 (wide landscape) and 1.6 (tall portrait)
      let clampedRatio = item.aspectRatio || 1.0;
      if (clampedRatio < 0.6) clampedRatio = 0.6;
      if (clampedRatio > 1.6) clampedRatio = 1.6;

      const mediaHeight = Math.round(colWidth * clampedRatio);
      
      // Measure footer height if present
      let footerHeight = 0;
      if (item.footer) {
        footerHeight = item.footer.offsetHeight || 38;
      }

      const totalItemHeight = mediaHeight + footerHeight;

      // Find shortest column
      let shortestCol = 0;
      let minHeight = colHeights[0];
      for (let c = 1; c < columns; c++) {
        if (colHeights[c] < minHeight) {
          minHeight = colHeights[c];
          shortestCol = c;
        }
      }

      item.targetX = shortestCol * (colWidth + gap);
      item.targetY = colHeights[shortestCol];
      item.targetW = colWidth;
      item.targetH = totalItemHeight;

      // Adjust media container height
      if (item.media) {
        item.media.style.height = `${mediaHeight}px`;
      }

      colHeights[shortestCol] += totalItemHeight + gap;
    });

    const maxContainerHeight = Math.max(...colHeights);
    this.listEl.style.height = `${maxContainerHeight}px`;
  }

  /**
   * Mount GSAP entrance animation (Power3.out, Stagger, Blur-to-focus)
   */
  mountEntrance() {
    if (this.hasMounted) return;

    this.items.forEach(item => {
      item.el.style.width = `${item.targetW}px`;
      item.el.style.height = `${item.targetH}px`;
    });

    if (this.options.reducedMotion) {
      // Instant layout for users with reduced motion preferences
      this.items.forEach(item => {
        gsap.set(item.el, {
          x: item.targetX,
          y: item.targetY,
          opacity: 1,
          scale: 1,
        });
      });
      this.hasMounted = true;
      return;
    }

    // Set initial position & styles for entrance
    this.items.forEach(item => {
      gsap.set(item.el, {
        x: item.targetX,
        y: item.targetY + 36,
        opacity: 0,
        scale: 0.96,
        filter: this.options.blurToFocus ? 'blur(10px)' : 'none',
      });
    });

    // GSAP Stagger Entrance Animation
    const targets = this.items.map(i => i.el);
    gsap.to(targets, {
      y: (index) => this.items[index].targetY,
      opacity: 1,
      scale: 1,
      filter: 'blur(0px)',
      duration: this.options.duration,
      ease: this.options.ease,
      stagger: this.options.stagger,
      clearProps: 'filter', // prevent subpixel text blur after animation finishes
      onComplete: () => {
        this.hasMounted = true;
      },
    });
  }

  /**
   * Smooth dynamic re-layout on container resize
   */
  reLayout(animated = true) {
    if (this.isLayoutRunning) return;
    this.isLayoutRunning = true;

    this.calculateLayout();

    this.items.forEach(item => {
      item.el.style.width = `${item.targetW}px`;
      item.el.style.height = `${item.targetH}px`;

      if (animated && !this.options.reducedMotion) {
        gsap.to(item.el, {
          x: item.targetX,
          y: item.targetY,
          duration: 0.35,
          ease: 'power2.out',
          overwrite: 'auto',
        });
      } else {
        gsap.set(item.el, {
          x: item.targetX,
          y: item.targetY,
        });
      }
    });

    this.isLayoutRunning = false;
  }

  /**
   * Observe container size changes with debounce
   */
  setupResizeObserver() {
    if (!window.ResizeObserver) {
      window.addEventListener('resize', () => this.reLayout(true), { passive: true });
      return;
    }

    let resizeTimer = null;
    this.resizeObserver = new ResizeObserver((entries) => {
      for (const entry of entries) {
        const newWidth = Math.floor(entry.contentRect.width);
        if (Math.abs(newWidth - this.cachedWidth) > 8) {
          clearTimeout(resizeTimer);
          resizeTimer = setTimeout(() => {
            this.reLayout(true);
          }, 60);
        }
      }
    });

    this.resizeObserver.observe(this.listEl);
  }

  /**
   * Setup Inspect Image / Shared Element Morph Preview
   * - Smooth FLIP (First, Last, Invert, Play) zoom from Masonry card position
   * - Natural aspect ratio preservation (portrait remains portrait, landscape remains landscape)
   * - Reversible zoom-out animation back to original card coordinates
   * - Full backdrop overlay, body scroll lock, ESC key & accessible focus
   */
  setupInspectModal() {
    const modal = document.getElementById('historyPhotoModal');
    if (!modal) return;

    const dialog = modal.querySelector('.history-photo-modal__dialog');
    const backdrop = modal.querySelector('.history-photo-modal__backdrop');
    const modalImg = document.getElementById('historyPhotoModalImg');
    const modalCaption = document.getElementById('historyPhotoModalCaption');
    const modalDownload = document.getElementById('historyPhotoModalDownload');
    const closeBtn = modal.querySelector('.history-photo-modal__close');
    const modalBody = modal.querySelector('.history-photo-modal__body');
    const modalFooter = modal.querySelector('.history-photo-modal__footer');

    this.isInspectActive = false;
    this.isAnimating = false;
    this.inspectState = null;

    const openInspect = (item) => {
      if (this.isInspectActive || this.isAnimating) return;
      this.isAnimating = true;
      this.activeInspectItem = item;

      const originEl = item.card || item.el;
      const originImg = item.img;
      const fullSrc = item.media?.dataset.fullSrc || item.imgSrc || (originImg ? originImg.src : '');
      const caption = item.media?.dataset.caption || '';

      // 1. Populate image and caption metadata
      if (modalImg) {
        modalImg.src = fullSrc;
        modalImg.alt = caption || 'Dokumentasi Kegiatan';
      }
      if (modalCaption) {
        modalCaption.textContent = caption || '';
        modalCaption.style.display = caption ? 'block' : 'none';
      }
      if (modalDownload) {
        modalDownload.href = fullSrc;
      }

      // 2. Measure origin card bounds and border radius
      const originRect = originEl.getBoundingClientRect();
      const originRadius = window.getComputedStyle(originEl).borderRadius || '12px';

      // 3. Compute viewport & target dimensions
      const vw = window.innerWidth;
      const vh = window.innerHeight;
      const isMobile = vw < 640;

      const maxW = isMobile ? Math.min(vw * 0.94, 1200) : Math.min(vw * 0.90, 1200);
      const footerH = (caption || modalDownload) ? (isMobile ? 46 : 52) : 0;
      const maxH = isMobile ? (vh * 0.88 - footerH) : (vh * 0.90 - footerH);

      // Natural aspect ratio (height / width)
      let ratio = item.aspectRatio;
      if (!ratio || ratio <= 0) {
        ratio = originRect.width > 0 ? (originRect.height / originRect.width) : 1.0;
      }

      let targetImgW, targetImgH;
      if (ratio > maxH / maxW) {
        // Tall / portrait photo: bounded by max height
        targetImgH = Math.round(maxH);
        targetImgW = Math.round(maxH / ratio);
      } else {
        // Wide / landscape photo: bounded by max width
        targetImgW = Math.round(maxW);
        targetImgH = Math.round(maxW * ratio);
      }

      // Ensure reasonable min width for card footer
      const minW = isMobile ? Math.min(vw * 0.88, 280) : 320;
      if (targetImgW < minW) {
        targetImgW = minW;
        targetImgH = Math.min(Math.round(targetImgW * ratio), maxH);
      }

      const targetTotalW = targetImgW;
      const targetTotalH = targetImgH + footerH;
      const targetLeft = Math.round((vw - targetTotalW) / 2);
      const targetTop = Math.round((vh - targetTotalH) / 2);

      this.inspectState = {
        item,
        originEl,
        originRadius,
        targetLeft,
        targetTop,
        targetTotalW,
        targetTotalH,
        targetImgW,
        targetImgH,
      };

      // 4. Position and size the dialog at final target
      if (dialog) {
        dialog.style.position = 'fixed';
        dialog.style.left = `${targetLeft}px`;
        dialog.style.top = `${targetTop}px`;
        dialog.style.width = `${targetTotalW}px`;
        dialog.style.height = `${targetTotalH}px`;
      }
      if (modalBody) {
        modalBody.style.width = `${targetImgW}px`;
        modalBody.style.height = `${targetImgH}px`;
      }

      // 5. Inversion Deltas (FLIP)
      const deltaX = originRect.left - targetLeft;
      const deltaY = originRect.top - targetTop;
      const scaleX = originRect.width / targetTotalW;
      const scaleY = originRect.height / targetTotalH;

      // 6. Lock body scroll and activate modal container
      document.body.style.overflow = 'hidden';
      modal.classList.add('is-active');
      modal.setAttribute('aria-hidden', 'false');
      this.isInspectActive = true;

      // Reduced motion fallback: instant/subtle fade
      if (this.options.reducedMotion) {
        gsap.set(backdrop, { opacity: 1 });
        if (dialog) {
          gsap.set(dialog, {
            x: 0,
            y: 0,
            scaleX: 1,
            scaleY: 1,
            borderRadius: '16px',
            opacity: 1,
          });
        }
        if (closeBtn) gsap.set(closeBtn, { opacity: 1, scale: 1 });
        if (modalFooter) gsap.set(modalFooter, { opacity: 1 });
        this.isAnimating = false;
        return;
      }

      // Seamlessly lift the card: hide original in Masonry
      originEl.style.visibility = 'hidden';

      gsap.killTweensOf([backdrop, dialog, closeBtn, modalFooter].filter(Boolean));

      // Set initial inverted position (matching origin card exactly)
      gsap.set(backdrop, { opacity: 0 });
      if (closeBtn) gsap.set(closeBtn, { opacity: 0, scale: 0.8 });
      if (modalFooter) gsap.set(modalFooter, { opacity: 0 });

      if (dialog) {
        gsap.set(dialog, {
          x: deltaX,
          y: deltaY,
          scaleX: scaleX,
          scaleY: scaleY,
          transformOrigin: '0 0',
          borderRadius: originRadius,
          opacity: 1,
          willChange: 'transform, border-radius',
        });
      }

      // Forward Zoom / Morph Animation
      const openDuration = isMobile ? 0.48 : 0.55;

      gsap.to(backdrop, {
        opacity: 1,
        duration: openDuration * 0.9,
        ease: 'power2.out',
      });

      if (dialog) {
        gsap.to(dialog, {
          x: 0,
          y: 0,
          scaleX: 1,
          scaleY: 1,
          borderRadius: '16px',
          duration: openDuration,
          ease: 'power3.out',
          clearProps: 'willChange',
          onComplete: () => {
            this.isAnimating = false;
          },
        });
      }

      if (closeBtn || modalFooter) {
        gsap.to([closeBtn, modalFooter].filter(Boolean), {
          opacity: 1,
          scale: 1,
          duration: 0.35,
          delay: 0.12,
          ease: 'power2.out',
        });
      }
    };

    const closeInspect = () => {
      if (!this.isInspectActive) return;
      this.isAnimating = true;

      const state = this.inspectState;
      const isMobile = window.innerWidth < 640;

      // Reduced motion branch
      if (this.options.reducedMotion) {
        if (state && state.originEl) {
          state.originEl.style.visibility = '';
        }
        modal.classList.remove('is-active');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        if (modalImg) modalImg.src = '';
        this.isInspectActive = false;
        this.isAnimating = false;
        this.inspectState = null;
        return;
      }

      // Re-measure origin card position to handle any resize
      let closeDeltaX = 0;
      let closeDeltaY = 0;
      let closeScaleX = 0.5;
      let closeScaleY = 0.5;
      let closeRadius = '12px';

      if (state && state.originEl) {
        const currentOriginRect = state.originEl.getBoundingClientRect();
        closeDeltaX = currentOriginRect.left - state.targetLeft;
        closeDeltaY = currentOriginRect.top - state.targetTop;
        closeScaleX = currentOriginRect.width / state.targetTotalW;
        closeScaleY = currentOriginRect.height / state.targetTotalH;
        closeRadius = state.originRadius;
      }

      gsap.killTweensOf([backdrop, dialog, closeBtn, modalFooter].filter(Boolean));

      if (closeBtn || modalFooter) {
        gsap.to([closeBtn, modalFooter].filter(Boolean), {
          opacity: 0,
          duration: 0.18,
          ease: 'power2.in',
        });
      }

      const closeDuration = isMobile ? 0.42 : 0.48;

      gsap.to(backdrop, {
        opacity: 0,
        duration: closeDuration,
        ease: 'power2.inOut',
      });

      if (dialog) {
        gsap.to(dialog, {
          x: closeDeltaX,
          y: closeDeltaY,
          scaleX: closeScaleX,
          scaleY: closeScaleY,
          borderRadius: closeRadius,
          duration: closeDuration,
          ease: 'power3.inOut',
          onComplete: () => {
            if (state && state.originEl) {
              state.originEl.style.visibility = '';
            }

            modal.classList.remove('is-active');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';

            gsap.set(dialog, { clearProps: 'all' });
            gsap.set(backdrop, { clearProps: 'all' });
            if (closeBtn) gsap.set(closeBtn, { clearProps: 'all' });
            if (modalFooter) gsap.set(modalFooter, { clearProps: 'all' });
            if (modalImg) modalImg.src = '';

            this.isInspectActive = false;
            this.isAnimating = false;
            this.inspectState = null;
          },
        });
      }
    };

    this.openInspect = openInspect;
    this.closeInspect = closeInspect;

    // Attach click events to Masonry cards
    this.items.forEach(item => {
      const card = item.card || item.el;
      if (card) {
        card.addEventListener('click', (e) => {
          // If clicked delete form or delete button, do not open inspect
          if (e.target.closest('form') || e.target.closest('.history-photo-masonry__btn-delete')) {
            return;
          }
          openInspect(item);
        });
      }

      const previewBtn = item.el.querySelector('.history-photo-masonry__btn-preview');
      if (previewBtn) {
        previewBtn.addEventListener('click', (e) => {
          e.stopPropagation();
          openInspect(item);
        });
      }

      const deleteForm = item.el.querySelector('form');
      if (deleteForm) {
        deleteForm.addEventListener('click', (e) => {
          e.stopPropagation();
        });
      }
    });

    // Close button click
    if (closeBtn) {
      closeBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        closeInspect();
      });
    }

    // Backdrop click
    if (backdrop) {
      backdrop.addEventListener('click', (e) => {
        e.preventDefault();
        closeInspect();
      });
    }

    // Dialog content click: do NOT close when clicking photo or caption
    if (dialog) {
      dialog.addEventListener('click', (e) => {
        if (e.target.closest('.history-photo-modal__close')) {
          return;
        }
        e.stopPropagation();
      });
    }

    // Escape key closes inspect modal
    this.onKeyDown = (e) => {
      if (e.key === 'Escape' && this.isInspectActive) {
        e.preventDefault();
        closeInspect();
      }
    };
    document.addEventListener('keydown', this.onKeyDown);
  }

  destroy() {
    if (this.resizeObserver) {
      this.resizeObserver.disconnect();
    }
    if (this.onKeyDown) {
      document.removeEventListener('keydown', this.onKeyDown);
    }
    if (this.isInspectActive) {
      document.body.style.overflow = '';
      if (this.inspectState && this.inspectState.originEl) {
        this.inspectState.originEl.style.visibility = '';
      }
    }
  }
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  const containers = document.querySelectorAll('.history-photo-masonry');
  containers.forEach(container => {
    new HistoryPhotoMasonry(container);
  });
});
