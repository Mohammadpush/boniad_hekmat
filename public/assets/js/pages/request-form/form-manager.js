/**
 * اسکریپت اختصاصی فرم درخواست بورسیه
 */

class ScholarshipFormManager {
    constructor() {
        this.currentStep = 1;
        this.totalSteps = 6;
        this.steps = [17, 34, 50, 67, 84, 100]; // Progress percentages
        this.formData = {};
        this.validationRules = this.getValidationRules();

        this.init();
    }

    init() {
        this.cacheElements();
        this.setupEventListeners();
        this.loadFormData();
        this.updateProgress();
    }

    cacheElements() {
        this.elements = {
            currentStep: document.getElementById('current-step'),
            progressPercent: document.getElementById('progress-percent'),
            progressBar: document.getElementById('progress-bar'),
            prevBtn: document.getElementById('prev-btn'),
            nextBtn: document.getElementById('next-btn'),
            form: document.getElementById('scholarship-form')
        };
    }

    getValidationRules() {
        return {
            // Step 1: Personal Info
            name: { required: true, minLength: 2, pattern: /^[\u0600-\u06FF\s]+$/, message: 'نام باید فارسی و حداقل 2 حرف باشد' },
            birthdate: { required: true, pattern: /^[0-9]{4}\/[0-9]{2}\/[0-9]{2}$/, message: 'تاریخ تولد باید به فرمت ۱۴۰۰/۰۱/۰۱ باشد' },
            nationalcode: { required: true, length: 10, pattern: /^[0-9]{10}$/, message: 'کد ملی باید 10 رقم باشد' },
            phone: { required: true, length: 11, pattern: /^09[0-9]{9}$/, message: 'شماره موبایل باید 11 رقم و با 09 شروع شود' },
            telephone: { required: false, pattern: /^[0-9]{11}$/, message: 'تلفن ثابت باید 11 رقم باشد' },

            // Step 2: Education Info
            grade: { required: true, message: 'انتخاب پایه تحصیلی الزامی است' },
            major_id: { required: false, message: 'انتخاب رشته تحصیلی الزامی است' },
            school: { required: true, minLength: 2, message: 'نام مدرسه باید حداقل 2 کاراکتر باشد' },
            last_score: { required: true, pattern: /^[0-9]{1,2}(\.[0-9]{1,2})?$/, message: 'معدل باید عدد صحیح یا اعشاری معتبر باشد' },
            principal: { required: true, minLength: 2, pattern: /^[\u0600-\u06FF\s]+$/, message: 'نام مدیر باید فارسی و حداقل 2 حرف باشد' },
            school_telephone: { required: false, pattern: /^[0-9]{11}$/, message: 'تلفن مدرسه باید 11 رقم باشد' },

            // Step 3: Housing Info
            rental: { required: true, message: 'انتخاب وضعیت مسکن الزامی است' },
            address: { required: true, minLength: 10, message: 'آدرس باید حداقل 10 کاراکتر باشد' },

            // Step 4: Parents Info
            father_name: { required: true, minLength: 2, pattern: /^[\u0600-\u06FF\s]+$/, message: 'نام پدر باید فارسی و حداقل 2 حرف باشد' },
            father_phone: { required: true, length: 11, pattern: /^09[0-9]{9}$/, message: 'موبایل پدر باید 11 رقم و با 09 شروع شود' },
            father_job: { required: true, minLength: 2, message: 'شغل پدر باید حداقل 2 کاراکتر باشد' },
            father_income: { required: true, message: 'درآمد پدر الزامی است' },
            father_job_address: { required: true, minLength: 5, message: 'آدرس محل کار پدر باید حداقل 5 کاراکتر باشد' },
            mother_name: { required: true, minLength: 2, pattern: /^[\u0600-\u06FF\s]+$/, message: 'نام مادر باید فارسی و حداقل 2 حرف باشد' },
            mother_phone: { required: true, length: 11, pattern: /^09[0-9]{9}$/, message: 'موبایل مادر باید 11 رقم و با 09 شروع شود' },
            mother_job: { required: true, minLength: 2, message: 'شغل مادر باید حداقل 2 کاراکتر باشد' },
            mother_income: { required: true, message: 'درآمد مادر الزامی است' },

            // Step 5: Family Info
            family_size: { required: true, message: 'تعداد اعضای خانواده الزامی است' },

            // Step 6: Documents
            description: { required: false, maxLength: 500, message: 'توضیحات نباید بیش از 500 کاراکتر باشد' }
        };
    }

    setupEventListeners() {
        // دکمه‌های ناوبری
        this.elements.prevBtn?.addEventListener('click', () => this.previousStep());
        this.elements.nextBtn?.addEventListener('click', () => this.nextStep());

        // اعتبارسنجی Real-time
        this.setupRealTimeValidation();

        // ذخیره خودکار
        this.setupAutoSave();

        // Income sliders
        this.setupIncomeSliders();

        // Major dropdown
        this.setupMajorDropdown();
    }

    setupRealTimeValidation() {
        if (!this.elements.form) return;

        const inputs = this.elements.form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            ['blur', 'change'].forEach(event => {
                input.addEventListener(event, () => {
                    this.validateField(input);
                });
            });
        });
    }

    setupAutoSave() {
        if (!this.elements.form) return;

        const inputs = this.elements.form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                this.saveFormData();
            });
        });
    }

    setupIncomeSliders() {
        const sliders = document.querySelectorAll('.income-slider');
        sliders.forEach(slider => {
            const display = slider.parentNode.querySelector('.income-display span');

            slider.addEventListener('input', (e) => {
                const value = parseInt(e.target.value);
                if (display) {
                    display.textContent = this.formatIncome(value);
                }
                this.updateSliderBackground(slider, value);
            });

            // تنظیم اولیه
            const initialValue = parseInt(slider.value);
            if (display) {
                display.textContent = this.formatIncome(initialValue);
            }
            this.updateSliderBackground(slider, initialValue);
        });
    }

    setupMajorDropdown() {
        const gradeSelect = document.getElementById('grade');
        const majorSection = document.getElementById('major-section');
        const majorSearch = document.getElementById('major-search');
        const majorDropdown = document.getElementById('major-dropdown');
        const majorId = document.getElementById('major-id');

        if (!gradeSelect) return;

        gradeSelect.addEventListener('change', (e) => {
            const selectedGrade = e.target.value;

            if (['دهم', 'یازدهم', 'دوازدهم', 'فارغ‌التحصیل'].includes(selectedGrade)) {
                majorSection?.classList.remove('hidden');
                this.validationRules.major_id.required = true;
            } else {
                majorSection?.classList.add('hidden');
                this.validationRules.major_id.required = false;
                if (majorSearch) majorSearch.value = '';
                if (majorId) majorId.value = '';
            }
        });

        // Major dropdown functionality
        if (majorSearch && majorDropdown) {
            majorSearch.addEventListener('focus', () => {
                majorDropdown.classList.remove('hidden');
            });

            majorSearch.addEventListener('input', (e) => {
                this.filterMajors(e.target.value.toLowerCase());
                majorDropdown.classList.remove('hidden');
            });

            // Handle option selection
            const majorOptions = majorDropdown.querySelectorAll('.major-option');
            majorOptions.forEach(option => {
                option.addEventListener('click', () => {
                    const value = option.getAttribute('data-value');
                    const name = option.getAttribute('data-name');

                    majorSearch.value = name;
                    majorId.value = value;
                    majorDropdown.classList.add('hidden');
                    this.clearFieldError('major_id');
                });
            });
        }
    }

    nextStep() {
        if (!this.validateCurrentStep()) {
            this.showValidationErrors();
            return;
        }

        if (this.currentStep < this.totalSteps) {
            this.hideStep(this.currentStep);
            this.currentStep++;
            this.showStep(this.currentStep);
            this.updateProgress();
            this.saveFormData();
        } else {
            this.submitForm();
        }
    }

    previousStep() {
        if (this.currentStep > 1) {
            this.hideStep(this.currentStep);
            this.currentStep--;
            this.showStep(this.currentStep);
            this.updateProgress();
        }
    }

    showStep(stepNumber) {
        const step = document.getElementById(`step-${stepNumber}`);
        if (step) {
            step.classList.remove('hidden');
            step.classList.add('active', 'entering');

            setTimeout(() => {
                step.classList.remove('entering');
            }, 300);

            // Focus first input
            const firstInput = step.querySelector('input, select, textarea');
            if (firstInput) {
                setTimeout(() => firstInput.focus(), 300);
            }
        }
    }

    hideStep(stepNumber) {
        const step = document.getElementById(`step-${stepNumber}`);
        if (step) {
            step.classList.add('leaving');

            setTimeout(() => {
                step.classList.remove('active', 'leaving');
                step.classList.add('hidden');
            }, 300);
        }
    }

    validateCurrentStep() {
        const currentStepElement = document.getElementById(`step-${this.currentStep}`);
        if (!currentStepElement) return true;

        const inputs = currentStepElement.querySelectorAll('input, select, textarea');
        let isValid = true;

        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
            }
        });

        return isValid;
    }

    validateField(field) {
        const fieldName = field.name || field.id;
        const rules = this.validationRules[fieldName];

        if (!rules) return true;

        return validateField(field, rules); // استفاده از تابع global
    }

    updateProgress() {
        const progress = this.steps[this.currentStep - 1];

        if (this.elements.currentStep) {
            this.elements.currentStep.textContent = this.currentStep;
        }

        if (this.elements.progressPercent) {
            this.elements.progressPercent.textContent = `${progress}%`;
        }

        if (this.elements.progressBar) {
            this.elements.progressBar.style.setProperty('--progress', `${progress}%`);
        }

        // Update navigation buttons
        if (this.elements.prevBtn) {
            this.elements.prevBtn.style.display = this.currentStep === 1 ? 'none' : 'block';
        }

        if (this.elements.nextBtn) {
            this.elements.nextBtn.textContent = this.currentStep === this.totalSteps ? 'ارسال درخواست' : 'مرحله بعد';
        }
    }

    formatIncome(value) {
        if (value === 0) return 'بدون درآمد';
        if (value >= 100) return 'بالای 100 میلیون تومان';
        return `${value} میلیون تومان`;
    }

    updateSliderBackground(slider, value) {
        const percentage = (value / 100) * 100;
        slider.style.background = `linear-gradient(to left, #3b82f6 0%, #3b82f6 ${percentage}%, #e5e7eb ${percentage}%, #e5e7eb 100%)`;
    }

    filterMajors(searchTerm) {
        const majorOptions = document.querySelectorAll('.major-option');
        majorOptions.forEach(option => {
            const majorName = option.getAttribute('data-name').toLowerCase();
            option.style.display = majorName.includes(searchTerm) ? 'block' : 'none';
        });
    }

    clearFieldError(fieldName) {
        clearFieldError(fieldName); // استفاده از تابع global
    }

    showValidationErrors() {
        window.toastManager.error('لطفاً تمام فیلدهای الزامی را تکمیل کنید');
    }

    saveFormData() {
        if (!this.elements.form) return;

        const formData = new FormData(this.elements.form);
        const data = {};

        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }

        localStorage.setItem('scholarship_form_data', JSON.stringify(data));
    }

    loadFormData() {
        const savedData = localStorage.getItem('scholarship_form_data');
        if (!savedData) return;

        try {
            const data = JSON.parse(savedData);

            Object.keys(data).forEach(key => {
                const field = this.elements.form?.querySelector(`[name="${key}"], #${key}`);
                if (field && data[key]) {
                    field.value = data[key];

                    // Trigger change event for dependent fields
                    field.dispatchEvent(new Event('change'));
                }
            });
        } catch (error) {
            console.error('Error loading form data:', error);
        }
    }

    async submitForm() {
        const loading = LoadingManager.show(this.elements.nextBtn, 'در حال ارسال...');

        try {
            const formData = new FormData(this.elements.form);

            const response = await fetch(this.elements.form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (response.ok) {
                window.toastManager.success('درخواست شما با موفقیت ارسال شد');
                localStorage.removeItem('scholarship_form_data');

                // Redirect after success
                setTimeout(() => {
                    window.location.href = '/dashboard';
                }, 2000);
            } else {
                throw new Error('خطا در ارسال درخواست');
            }
        } catch (error) {
            window.toastManager.error('خطا در ارسال درخواست. لطفاً دوباره تلاش کنید');
        } finally {
            loading.hide();
        }
    }
}

// Initialize form when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    const scholarshipForm = new ScholarshipFormManager();

    // Add clear data button for testing
    const clearButton = document.createElement('button');
    clearButton.type = 'button';
    clearButton.textContent = 'پاک کردن داده‌های ذخیره شده';
    clearButton.className = 'fixed bottom-4 left-4 bg-red-500 text-white px-3 py-2 rounded text-xs z-50';
    clearButton.onclick = function() {
        if (confirm('آیا می‌خواهید تمام داده‌های ذخیره شده را پاک کنید؟')) {
            localStorage.removeItem('scholarship_form_data');
            location.reload();
        }
    };
    document.body.appendChild(clearButton);
});
