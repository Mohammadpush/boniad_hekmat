/**
 * عملکردهای عمومی برای کار با Modal و Popup
 */

/**
 * کلاس مدیریت Modal
 */
class ModalManager {
    constructor() {
        this.currentModal = null;
        this.init();
    }

    init() {
        // Event delegation برای دکمه‌های باز کردن modal
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-modal-target]')) {
                e.preventDefault();
                const modalId = e.target.getAttribute('data-modal-target');
                this.openModal(modalId);
            }

            if (e.target.matches('[data-modal-close]') || e.target.closest('[data-modal-close]')) {
                e.preventDefault();
                this.closeModal();
            }

            // بستن modal با کلیک روی backdrop
            if (e.target.matches('.modal-backdrop')) {
                this.closeModal();
            }
        });

        // بستن modal با کلید Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.currentModal) {
                this.closeModal();
            }
        });
    }

    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        this.currentModal = modal;

        // نمایش modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // انیمیشن ورود
        requestAnimationFrame(() => {
            modal.classList.add('modal-show');
        });

        // قفل کردن اسکرول body
        document.body.style.overflow = 'hidden';

        // فوکس روی اولین input
        const firstInput = modal.querySelector('input, textarea, select, button');
        if (firstInput) {
            firstInput.focus();
        }

        // trigger event
        modal.dispatchEvent(new CustomEvent('modal:opened'));
    }

    closeModal() {
        if (!this.currentModal) return;

        const modal = this.currentModal;

        // انیمیشن خروج
        modal.classList.remove('modal-show');

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');

            // آزاد کردن اسکرول body
            document.body.style.overflow = '';

            this.currentModal = null;

            // trigger event
            modal.dispatchEvent(new CustomEvent('modal:closed'));
        }, 200);
    }

    isOpen() {
        return this.currentModal !== null;
    }
}

/**
 * کلاس مدیریت Toast notifications
 */
class ToastManager {
    constructor() {
        this.container = this.createContainer();
        this.toasts = [];
    }

    createContainer() {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed top-4 left-4 z-50 space-y-2';
            document.body.appendChild(container);
        }
        return container;
    }

    show(message, type = 'info', duration = 5000) {
        const toast = this.createToast(message, type);
        this.container.appendChild(toast);
        this.toasts.push(toast);

        // انیمیشن ورود
        requestAnimationFrame(() => {
            toast.classList.add('toast-show');
        });

        // حذف خودکار
        if (duration > 0) {
            setTimeout(() => {
                this.remove(toast);
            }, duration);
        }

        return toast;
    }

    createToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type} transform translate-x-full opacity-0 transition-all duration-300`;

        const icons = {
            success: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>`,
            error: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>`,
            warning: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>`,
            info: `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>`
        };

        toast.innerHTML = `
            <div class="flex items-center gap-3">
                ${icons[type] || icons.info}
                <span class="flex-1">${message}</span>
                <button class="toast-close text-current opacity-50 hover:opacity-100">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        `;

        // دکمه بستن
        toast.querySelector('.toast-close').addEventListener('click', () => {
            this.remove(toast);
        });

        return toast;
    }

    remove(toast) {
        toast.classList.remove('toast-show');

        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
            this.toasts = this.toasts.filter(t => t !== toast);
        }, 300);
    }

    success(message, duration = 5000) {
        return this.show(message, 'success', duration);
    }

    error(message, duration = 5000) {
        return this.show(message, 'error', duration);
    }

    warning(message, duration = 5000) {
        return this.show(message, 'warning', duration);
    }

    info(message, duration = 5000) {
        return this.show(message, 'info', duration);
    }
}

/**
 * مدیریت Loading states
 */
class LoadingManager {
    static show(element, text = 'در حال بارگذاری...') {
        if (typeof element === 'string') {
            element = document.querySelector(element);
        }

        if (!element) return;

        element.disabled = true;
        element.classList.add('loading');

        const originalText = element.textContent;
        element.setAttribute('data-original-text', originalText);
        element.textContent = text;

        return {
            hide: () => this.hide(element)
        };
    }

    static hide(element) {
        if (typeof element === 'string') {
            element = document.querySelector(element);
        }

        if (!element) return;

        element.disabled = false;
        element.classList.remove('loading');

        const originalText = element.getAttribute('data-original-text');
        if (originalText) {
            element.textContent = originalText;
            element.removeAttribute('data-original-text');
        }
    }
}

// ایجاد نمونه‌های global
window.modalManager = new ModalManager();
window.toastManager = new ToastManager();

// Export برای استفاده در ماژول‌ها
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        ModalManager,
        ToastManager,
        LoadingManager
    };
}
