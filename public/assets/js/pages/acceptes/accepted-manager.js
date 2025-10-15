/**
 * اسکریپت اختصاصی صفحه پذیرفته‌شدگان
 */

class AcceptedStudentsManager {
    constructor() {
        this.currentModal = null;
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.checkForLaravelMessages();
    }

    setupEventListeners() {
        // Close modals with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllModals();
            }
        });

        // Close modals when clicking outside
        document.addEventListener('click', (e) => {
            if (e.target.matches('.modal-backdrop')) {
                this.closeAllModals();
            }
        });
    }

    checkForLaravelMessages() {
        // This will be replaced by Laravel Blade template when needed
    }

    // Scholarship Modal Functions
    openScholarshipModal(requestId) {
        const modal = document.getElementById('scholarshipModal');
        const modalContent = document.getElementById('modalContent');
        const form = document.getElementById('scholarshipForm');
        const requestIdInput = document.getElementById('modalRequestId');

        if (!modal || !modalContent || !form || !requestIdInput) {
            console.error('Modal elements not found');
            return;
        }
        else
        {
            console.log(modal,modalContent,form,requestIdInput);
        }

        // Set the request ID
        requestIdInput.value = requestId;


        // Show modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Animate modal appearance
        requestAnimationFrame(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        });

        // Focus first input
        const firstInput = modal.querySelector('input[type="text"]');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 300);
        }

        this.currentModal = 'scholarship';
    }

    closeScholarshipModal() {
        const modal = document.getElementById('scholarshipModal');
        const modalContent = document.getElementById('modalContent');
        const form = document.getElementById('scholarshipForm');

        if (!modal || !modalContent || !form) return;

        // Animate modal disappearance
        modalContent.classList.add('scale-95', 'opacity-0');
        modalContent.classList.remove('scale-100', 'opacity-100');

        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';

            // Reset form
            form.reset();

            this.currentModal = null;
        }, 300);
    }

    // Success Popup Functions
    showSuccessPopup(message = 'عملیات با موفقیت انجام شد') {
        const popup = document.getElementById('successPopup');
        const content = document.getElementById('successContent');
        const messageElement = document.getElementById('successMessage');

        if (!popup || !content) {
            // Create popup if it doesn't exist
            this.createSuccessPopup(message);
            return;
        }

        if (messageElement) {
            messageElement.textContent = message;
        }

        popup.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        requestAnimationFrame(() => {
            content.classList.add('show');
        });

        // Auto close after 5 seconds
        setTimeout(() => {
            this.closeSuccessPopup();
        }, 5000);
    }

    closeSuccessPopup() {
        const popup = document.getElementById('successPopup');
        const content = document.getElementById('successContent');

        if (!popup || !content) return;

        content.classList.remove('show');

        setTimeout(() => {
            popup.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
    }

    createSuccessPopup(message) {
        const popup = document.createElement('div');
        popup.id = 'successPopup';
        popup.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';

        popup.innerHTML = `
            <div class="success-content" id="successContent">
                <div class="success-icon">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="success-title">عملیات موفق!</h3>
                <p class="success-message" id="successMessage">${message}</p>
                <button onclick="acceptedManager.closeSuccessPopup()" class="btn btn-primary">
                    تأیید
                </button>
            </div>
        `;

        document.body.appendChild(popup);

        // Show with animation
        requestAnimationFrame(() => {
            const content = popup.querySelector('.success-content');
            content.classList.add('show');
        });

        // Auto close
        setTimeout(() => {
            this.closeSuccessPopup();
        }, 5000);
    }

    closeAllModals() {
        if (this.currentModal === 'scholarship') {
            this.closeScholarshipModal();
        }
        this.closeSuccessPopup();
    }

    // Form validation and submission
    validateScholarshipForm() {
        const form = document.getElementById('scholarshipForm');
        if (!form) return false;

        const title = form.querySelector('#title').value.trim();
        const description = form.querySelector('#description').value.trim();
        const price = form.querySelector('#price').value.trim();

        if (!title) {
            this.showError('لطفاً عنوان بورسیه را وارد کنید');
            return false;
        }

        if (title.length > 25) {
            this.showError('عنوان بورسیه نمی‌تواند بیش از 25 کاراکتر باشد');
            return false;
        }

        if (!description) {
            this.showError('لطفاً توضیحات را وارد کنید');
            return false;
        }

        if (!price) {
            this.showError('لطفاً مبلغ بورسیه را وارد کنید');
            return false;
        }

        return true;
    }

    showError(message) {
        if (window.toastManager) {
            window.toastManager.error(message);
        } else {
            alert(message);
        }
    }

    // Progress bar animations
    animateProgressBar(element, targetPercent) {
        let currentPercent = 0;
        const increment = targetPercent / 50; // 50 frames for smooth animation

        const animate = () => {
            if (currentPercent < targetPercent) {
                currentPercent += increment;
                element.style.width = `${Math.min(currentPercent, targetPercent)}%`;
                requestAnimationFrame(animate);
            }
        };

        animate();
    }

    // Initialize progress bars with animation
    initializeProgressBars() {
        const progressBars = document.querySelectorAll('.progress-bar-fill[data-percent]');

        progressBars.forEach(bar => {
            const percent = parseFloat(bar.dataset.percent);
            bar.style.width = '0%';

            // Animate after a short delay
            setTimeout(() => {
                this.animateProgressBar(bar, percent);
            }, 100);
        });
    }

    // Copy functionality
    copyToClipboard(text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(() => {
                this.showSuccess('متن کپی شد');
            }).catch(() => {
                this.fallbackCopyTextToClipboard(text);
            });
        } else {
            this.fallbackCopyTextToClipboard(text);
        }
    }

    fallbackCopyTextToClipboard(text) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.top = '0';
        textArea.style.left = '0';
        textArea.style.position = 'fixed';

        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            document.execCommand('copy');
            this.showSuccess('متن کپی شد');
        } catch (err) {
            console.error('Fallback: Could not copy text: ', err);
        }

        document.body.removeChild(textArea);
    }

    showSuccess(message) {
        if (window.toastManager) {
            window.toastManager.success(message);
        }
    }

    // Utility methods
    formatPrice(input) {
        // Remove non-digits
        let value = input.value.replace(/[^0-9]/g, '');

        // Add thousand separators with comma (same as price-input.js)
        if (value.length > 3) {
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        input.value = value;
    }

    // Initialize everything
    initialize() {
        this.initializeProgressBars();

        // Note: price input formatting is handled by price-input.js
        // We don't need to set up the event listener here to avoid conflicts

        // Setup form submission - remove commas before submit
        const scholarshipForm = document.getElementById('scholarshipForm');
        if (scholarshipForm) {
            scholarshipForm.addEventListener('submit', (e) => {
                // Remove commas from price before validation
                const priceInput = document.getElementById('price');
                if (priceInput) {
                    priceInput.value = priceInput.value.replace(/,/g, '');
                }

                if (!this.validateScholarshipForm()) {
                    e.preventDefault();
                }
            });
        }
    }
}

// Global functions for compatibility
function openScholarshipModal(requestId) {
    if (window.acceptedManager) {
        window.acceptedManager.openScholarshipModal(requestId);
    }
}

function closeScholarshipModal() {
    if (window.acceptedManager) {
        window.acceptedManager.closeScholarshipModal();
    }
}

function showSuccessPopup(message) {
    if (window.acceptedManager) {
        window.acceptedManager.showSuccessPopup(message);
    }
}

function closeSuccessPopup() {
    if (window.acceptedManager) {
        window.acceptedManager.closeSuccessPopup();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.acceptedManager = new AcceptedStudentsManager();
    window.acceptedManager.initialize();
});
