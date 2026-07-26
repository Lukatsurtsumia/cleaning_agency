import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Auto-advancing, swipeable slideshow (the hero carousel). Autoplay is
// skipped entirely for anyone who asked their OS to reduce motion, and pauses
// on hover/focus so it never fights a reader trying to look at one slide.
Alpine.data('slideshow', (total, intervalMs = 6500) => ({
    index: 0,
    total,
    timer: null,
    touchStartX: null,
    touchStartY: null,

    init() {
        this.play();
    },

    play() {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return;
        }

        this.stop();
        this.timer = setInterval(() => this.next(), intervalMs);
    },

    stop() {
        clearInterval(this.timer);
        this.timer = null;
    },

    next() {
        this.index = (this.index + 1) % this.total;
    },

    prev() {
        this.index = (this.index - 1 + this.total) % this.total;
    },

    go(i) {
        this.index = i;
    },

    onTouchStart(event) {
        this.touchStartX = event.changedTouches[0].clientX;
        this.touchStartY = event.changedTouches[0].clientY;
    },

    onTouchEnd(event) {
        if (this.touchStartX === null) {
            return;
        }

        const dx = event.changedTouches[0].clientX - this.touchStartX;
        const dy = event.changedTouches[0].clientY - this.touchStartY;

        // Only a clearly horizontal swipe changes slide. A vertical (or mostly
        // vertical) gesture is the reader scrolling the page — leave it alone,
        // otherwise scrolling past the hero would flip the picture.
        if (Math.abs(dx) > 45 && Math.abs(dx) > Math.abs(dy) * 1.5) {
            dx < 0 ? this.next() : this.prev();
        }

        this.touchStartX = null;
        this.touchStartY = null;
    },
}));

Alpine.start();
