// فایل JavaScript بهینه‌شده - شامل تمام توابع عمومی

/**
 * Application utilities and common functions
 */
class AppUtils {
    constructor() {
        this.init();
    }

    init() {
        this.setupGlobalEventListeners();
        this.initializeTooltips();
        this.setupModalHandlers();
        this.initializeFormValidation();
    }

    setupGlobalEventListeners() {
        // Global escape key handler
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllModals();
                this.clearAllSearches();
            }
        });

        // Global click outside handler
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-backdrop') || e.target.classList.contains('modal')) {
                this.closeAllModals();
            }
        });
    }

    initializeTooltips() {
        const elements = document.querySelectorAll('[data-tooltip]');
        elements.forEach(element => {
            this.addTooltip(element);
        });
    }

    addTooltip(element) {
        let tooltip;

        element.addEventListener('mouseenter', () => {
            tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = element.getAttribute('data-tooltip');
            tooltip.style.cssText = `
                position: absolute;
                background: #1f2937;
                color: white;
                padding: 6px 12px;
                border-radius: 6px;
                font-size: 12px;
                font-weight: 500;
                z-index: 1000;
                white-space: nowrap;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                opacity: 0;
                transition: opacity 0.2s ease;
            `;

            document.body.appendChild(tooltip);

            const rect = element.getBoundingClientRect();
            const tooltipRect = tooltip.getBoundingClientRect();

            tooltip.style.left = (rect.left + rect.width / 2 - tooltipRect.width / 2) + 'px';
            tooltip.style.top = (rect.top - tooltipRect.height - 8) + 'px';

            setTimeout(() => {
                tooltip.style.opacity = '1';
            }, 10);
        });

        element.addEventListener('mouseleave', () => {
            if (tooltip) {
                tooltip.style.opacity = '0';
                setTimeout(() => {
                    if (document.body.contains(tooltip)) {
                        document.body.removeChild(tooltip);
                    }
                }, 200);
            }
        });
    }

    setupModalHandlers() {
        // Auto-setup modal close buttons
        const closeButtons = document.querySelectorAll('[data-modal-close]');
        closeButtons.forEach(button => {
            button.addEventListener('click', () => {
                const modalId = button.getAttribute('data-modal-close');
                this.closeModal(modalId);
            });
        });
    }

    initializeFormValidation() {
        const forms = document.querySelectorAll('form[data-validate]');
        forms.forEach(form => {
            this.setupFormValidation(form);
        });
    }

    setupFormValidation(form) {
        const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');

        inputs.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', () => {
                if (input.classList.contains('error')) {
                    this.validateField(input);
                }
            });
        });

        form.addEventListener('submit', (e) => {
            if (!this.validateForm(form)) {
                e.preventDefault();
            }
        });
    }

    validateField(field) {
        const value = field.value.trim();
        const isRequired = field.hasAttribute('required');
        const type = field.type;
        const minLength = field.getAttribute('minlength');
        const maxLength = field.getAttribute('maxlength');

        // Clear previous errors
        this.clearFieldError(field);

        // Required validation
        if (isRequired && !value) {
            this.showFieldError(field, 'این فیلد الزامی است.');
            return false;
        }

        // Length validation
        if (value && minLength && value.length < parseInt(minLength)) {
            this.showFieldError(field, `حداقل ${minLength} کاراکتر وارد کنید.`);
            return false;
        }

        if (value && maxLength && value.length > parseInt(maxLength)) {
            this.showFieldError(field, `حداکثر ${maxLength} کاراکتر مجاز است.`);
            return false;
        }

        // Email validation
        if (type === 'email' && value && !this.isValidEmail(value)) {
            this.showFieldError(field, 'آدرس ایمیل معتبر وارد کنید.');
            return false;
        }

        // Phone validation (Iranian format)
        if (field.classList.contains('phone-input') && value && !this.isValidPhone(value)) {
            this.showFieldError(field, 'شماره تماس معتبر وارد کنید.');
            return false;
        }

        // National code validation
        if (field.classList.contains('national-code-input') && value && !this.isValidNationalCode(value)) {
            this.showFieldError(field, 'کد ملی معتبر وارد کنید.');
            return false;
        }

        return true;
    }

    validateForm(form) {
        const inputs = form.querySelectorAll('input, textarea, select');
        let isValid = true;

        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
            }
        });

        if (!isValid) {
            const firstError = form.querySelector('.error');
            if (firstError) {
                firstError.focus();
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        return isValid;
    }

    showFieldError(field, message) {
        field.classList.add('error', 'border-red-500', 'bg-red-50');
        field.classList.remove('border-gray-300', 'border-gray-200');

        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }

        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error text-red-600 text-sm mt-1';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }

    clearFieldError(field) {
        field.classList.remove('error', 'border-red-500', 'bg-red-50');
        field.classList.add('border-gray-300');

        const errorDiv = field.parentNode.querySelector('.field-error');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    // Utility functions
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    isValidPhone(phone) {
        const phoneRegex = /^09\d{9}$/;
        return phoneRegex.test(phone);
    }

    isValidNationalCode(code) {
        if (!/^\d{10}$/.test(code)) return false;

        const check = parseInt(code[9]);
        let sum = 0;

        for (let i = 0; i < 9; i++) {
            sum += parseInt(code[i]) * (10 - i);
        }

        const remainder = sum % 11;
        return remainder < 2 ? check === remainder : check === 11 - remainder;
    }

    // Modal functions
    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    }

    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
    }

    closeAllModals() {
        const modals = document.querySelectorAll('.modal:not(.hidden)');
        modals.forEach(modal => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
        document.body.style.overflow = '';
    }

    // Search functions
    clearAllSearches() {
        const searchInputs = document.querySelectorAll('input[type="search"], input[placeholder*="جستجو"]');
        searchInputs.forEach(input => {
            input.value = '';
            input.dispatchEvent(new Event('input'));
        });
    }

    // Notification system
    showNotification(message, type = 'success', duration = 3000) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300 ${this.getNotificationClass(type)}`;
        notification.textContent = message;

        document.body.appendChild(notification);

        // Slide in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 10);

        // Auto hide
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, duration);
    }

    getNotificationClass(type) {
        switch (type) {
            case 'success':
                return 'bg-green-500 text-white';
            case 'error':
                return 'bg-red-500 text-white';
            case 'warning':
                return 'bg-yellow-500 text-white';
            case 'info':
                return 'bg-blue-500 text-white';
            default:
                return 'bg-gray-500 text-white';
        }
    }

    // Loading state management
    showLoading(element) {
        if (element.tagName === 'BUTTON') {
            element.disabled = true;
            element.innerHTML = `
                <svg class="animate-spin h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                در حال پردازش...
            `;
        }
    }

    hideLoading(element, originalText) {
        if (element.tagName === 'BUTTON') {
            element.disabled = false;
            element.innerHTML = originalText;
        }
    }

    // Format utilities
    formatPrice(price) {
        return new Intl.NumberFormat('fa-IR').format(price);
    }

    formatDate(date) {
        // This would integrate with Jalalian date formatting if needed
        return new Date(date).toLocaleDateString('fa-IR');
    }
}

// Initialize the app utilities
document.addEventListener('DOMContentLoaded', () => {
    window.appUtils = new AppUtils();
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AppUtils;
}
