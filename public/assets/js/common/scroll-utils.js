/**
 * عملکردهای عمومی اسکرول افقی
 */

/**
 * مدیریت اسکرول افقی برای کانتینرها
 */
class HorizontalScrollManager {
    constructor(containerSelector) {
        this.container = document.querySelector(containerSelector);
        this.scrollAmount = 320; // مقدار اسکرول به پیکسل
        this.init();
    }

    init() {
        if (!this.container) return;

        this.createScrollButtons();
        this.addEventListeners();
        this.updateButtonsVisibility();
    }

    createScrollButtons() {
        const wrapper = this.container.parentNode;

        // دکمه اسکرول چپ
        const leftBtn = document.createElement('button');
        leftBtn.className = 'scroll-btn left';
        leftBtn.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        `;
        leftBtn.onclick = () => this.scrollLeft();

        // دکمه اسکرول راست
        const rightBtn = document.createElement('button');
        rightBtn.className = 'scroll-btn right';
        rightBtn.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        `;
        rightBtn.onclick = () => this.scrollRight();

        wrapper.appendChild(leftBtn);
        wrapper.appendChild(rightBtn);

        this.leftBtn = leftBtn;
        this.rightBtn = rightBtn;
    }

    addEventListeners() {
        this.container.addEventListener('scroll', () => {
            this.updateButtonsVisibility();
        });

        // اسکرول با ماوس
        let isDown = false;
        let startX;
        let scrollLeft;

        this.container.addEventListener('mousedown', (e) => {
            isDown = true;
            this.container.style.cursor = 'grabbing';
            startX = e.pageX - this.container.offsetLeft;
            scrollLeft = this.container.scrollLeft;
        });

        this.container.addEventListener('mouseleave', () => {
            isDown = false;
            this.container.style.cursor = 'grab';
        });

        this.container.addEventListener('mouseup', () => {
            isDown = false;
            this.container.style.cursor = 'grab';
        });

        this.container.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - this.container.offsetLeft;
            const walk = (x - startX) * 2;
            this.container.scrollLeft = scrollLeft - walk;
        });
    }

    scrollLeft() {
        this.container.scrollBy({
            left: -this.scrollAmount,
            behavior: 'smooth'
        });
    }

    scrollRight() {
        this.container.scrollBy({
            left: this.scrollAmount,
            behavior: 'smooth'
        });
    }

    updateButtonsVisibility() {
        if (!this.leftBtn || !this.rightBtn) return;

        const canScrollLeft = this.container.scrollLeft > 0;
        const canScrollRight = this.container.scrollLeft <
            (this.container.scrollWidth - this.container.clientWidth);

        this.leftBtn.style.opacity = canScrollLeft ? '1' : '0.5';
        this.rightBtn.style.opacity = canScrollRight ? '1' : '0.5';

        this.leftBtn.style.pointerEvents = canScrollLeft ? 'auto' : 'none';
        this.rightBtn.style.pointerEvents = canScrollRight ? 'auto' : 'none';
    }

    scrollToStart() {
        this.container.scrollTo({
            left: 0,
            behavior: 'smooth'
        });
    }

    scrollToEnd() {
        this.container.scrollTo({
            left: this.container.scrollWidth,
            behavior: 'smooth'
        });
    }
}

/**
 * اسکرول نرم به عنصر مشخص
 * @param {string} selector سلکتور عنصر
 * @param {number} offset افست (پیش‌فرض: 80)
 */
function smoothScrollTo(selector, offset = 80) {
    const element = document.querySelector(selector);
    if (!element) return;

    const elementPosition = element.getBoundingClientRect().top;
    const offsetPosition = elementPosition + window.pageYOffset - offset;

    window.scrollTo({
        top: offsetPosition,
        behavior: 'smooth'
    });
}

/**
 * تشخیص اینکه آیا عنصر در ناحیه نمایش است یا نه
 * @param {HTMLElement} element عنصر مورد نظر
 * @param {number} threshold حد آستانه (پیش‌فرض: 0.5)
 * @returns {boolean}
 */
function isElementInViewport(element, threshold = 0.5) {
    const rect = element.getBoundingClientRect();
    const windowHeight = window.innerHeight || document.documentElement.clientHeight;
    const elementHeight = rect.height;

    const visibleHeight = Math.min(rect.bottom, windowHeight) - Math.max(rect.top, 0);
    return visibleHeight >= (elementHeight * threshold);
}

/**
 * اسکرول بی‌نهایت (Infinite Scroll)
 */
class InfiniteScroll {
    constructor(container, loadCallback, options = {}) {
        this.container = typeof container === 'string' ?
            document.querySelector(container) : container;
        this.loadCallback = loadCallback;
        this.options = {
            threshold: 200,
            loading: false,
            ...options
        };

        this.init();
    }

    init() {
        if (!this.container) return;

        this.container.addEventListener('scroll', () => {
            if (this.shouldLoadMore()) {
                this.loadMore();
            }
        });
    }

    shouldLoadMore() {
        const { scrollTop, scrollHeight, clientHeight } = this.container;
        return scrollTop + clientHeight >= scrollHeight - this.options.threshold;
    }

    async loadMore() {
        if (this.options.loading) return;

        this.options.loading = true;
        try {
            await this.loadCallback();
        } finally {
            this.options.loading = false;
        }
    }
}

// مقداردهی اولیه خودکار برای کانتینرهای اسکرول افقی
document.addEventListener('DOMContentLoaded', function() {
    // تشخیص خودکار کانتینرهای اسکرول افقی
    const horizontalContainers = document.querySelectorAll('.horizontal-scroll-container');
    horizontalContainers.forEach(container => {
        new HorizontalScrollManager(`#${container.id}` || '.horizontal-scroll-container');
    });
});

// Export برای استفاده در ماژول‌ها
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        HorizontalScrollManager,
        smoothScrollTo,
        isElementInViewport,
        InfiniteScroll
    };
}
