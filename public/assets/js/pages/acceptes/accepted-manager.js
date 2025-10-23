/**
 * اسکریپت اختصاصی صفحه پذیرفته‌شدگان
 * مدیریت مودال و فرم های بورسیه
 */
class AcceptedStudentsManager {
    constructor() {
        this.currentModal = null;
        this.currentRequestId = null;

        // Cache DOM elements
        this.elements = {
            modal: document.getElementById('scholarshipModal'),
            modalContent: document.getElementById('modalContent'),
            form: document.getElementById('scholarshipForm'),
            requestIdInput: document.getElementById('modalRequestId'),
        };

        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupFormSubmission();
    }

    setupEventListeners() {
        // Close modals with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllModals();
            }
        });

        // Close modals when clicking outside
        if (this.elements.modal) {
            this.elements.modal.addEventListener('click', (e) => {
                if (e.target === this.elements.modal) {
                    this.closeScholarshipModal();
                }
            });
        }
    }

    setupFormSubmission() {
        if (!this.elements.form) return;

        // ✅ اضافه کردن formatter برای price input
        const priceInput = this.elements.form.querySelector('#price');
        if (priceInput) {
            priceInput.addEventListener('input', (e) => {
                this.formatPrice(e.target);
            });
        }

        this.elements.form.addEventListener('submit', (e) => {
            e.preventDefault();

            // Validate form
            if (!this.validateScholarshipForm()) {
                return;
            }

            // Remove commas from price before submit
            const priceInput = this.elements.form.querySelector('#price');
            if (priceInput) {
                priceInput.value = priceInput.value.replace(/,/g, '');
            }

            // Submit form via AJAX
            this.submitScholarshipForm();
        });
    }

    // Scholarship Modal Functions
    openScholarshipModal(requestId) {

        this.currentRequestId = requestId;
        this.elements.requestIdInput.value = requestId;
        this.elements.form.action = `/unified/storemessage/${requestId}`;

        // Show modal
        this.elements.modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Animate modal appearance
        requestAnimationFrame(() => {
            this.elements.modalContent.classList.remove('scale-95', 'opacity-0');
            this.elements.modalContent.classList.add('scale-100', 'opacity-100');
        });

        // Focus first input
        const firstInput = this.elements.modal.querySelector('input[type="text"]');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 300);
        }

        this.currentModal = 'scholarship';
    }

    closeScholarshipModal() {

        // Animate modal disappearance
        this.elements.modalContent.classList.add('scale-95', 'opacity-0');
        this.elements.modalContent.classList.remove('scale-100', 'opacity-100');

        setTimeout(() => {
            this.elements.modal.classList.add('hidden');
            document.body.style.overflow = '';

            // Reset form
            this.elements.form.reset();

            this.currentModal = null;
            this.currentRequestId = null;
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
        if (!this.elements.form) return false;

        const description = this.elements.form.querySelector('#description')?.value.trim() || '';
        const price = this.elements.form.querySelector('#price')?.value.trim() || '';

        if (!description) {
            this.showError('لطفاً توضیحات را وارد کنید');
            return false;
        }

        if (description.length < 10) {
            this.showError('توضیحات باید حداقل 10 کاراکتر باشد');
            return false;
        }

        if (!price) {
            this.showError('لطفاً مبلغ بورسیه را وارد کنید');
            return false;
        }

        // بررسی اینکه قیمت عدد است
        const priceNumber = parseInt(price.replace(/,/g, ''));
        if (isNaN(priceNumber) || priceNumber <= 0) {
            this.showError('مبلغ بورسیه باید عدد مثبت باشد');
            return false;
        }

        return true;
    }

    /**
     * ارسال فرم بورسیه از طریق AJAX
     */
    async submitScholarshipForm() {
        const formData = new FormData(this.elements.form);
        const submitBtn = this.elements.form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        try {
            // Show loading state
            submitBtn.innerHTML = '<svg class="animate-spin w-5 h-5 inline-block ml-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>درحال پردازش...';
            submitBtn.disabled = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            const response = await fetch(this.elements.form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',  // ✅ اضافه کردن Accept header
                }
            });

            console.log('Response Status:', response.status);
            console.log('Response Headers:', response.headers);

            if (!response.ok) {
                const errorText = await response.text();
                console.error('Response Error:', errorText);
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('Response Data:', data);

            if (data.success) {
                this.showSuccessPopup('بورسیه با موفقیت تعیین شد');
                this.closeScholarshipModal();

                // Reload page after success
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                this.showError(data.message || 'خطا در ارسال اطلاعات');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        } catch (error) {
            console.error('Error submitting form:', error);
            this.showError('خطا در ارسال اطلاعات. لطفاً دوباره تلاش کنید.');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    showError(message) {
        if (window.toastManager) {
            window.toastManager.error(message);
        } else {
            alert(message);
        }
    }

    /**
     * نمایش Popup موفقیت
     */
    showSuccessPopup(message = 'عملیات با موفقیت انجام شد') {
        const popup = document.getElementById('successPopup');
        const content = document.getElementById('successContent');
        const messageElement = document.getElementById('successMessage');

        if (!popup || !content) {
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

    // Utility methods
    formatPrice(input) {
        // Remove non-digits
        let value = input.value.replace(/[^0-9]/g, '');

        // Add thousand separators with comma
        if (value.length > 3) {
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        input.value = value;
    }
}

/**
 * Global functions for compatibility with onclick handlers
 */
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
document.addEventListener('DOMContentLoaded', function () {
    window.acceptedManager = new AcceptedStudentsManager();
});
