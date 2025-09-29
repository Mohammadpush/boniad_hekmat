/**
 * عملکردهای عمومی اعتبارسنجی فرم‌ها
 */

// قوانین اعتبارسنجی مشترک
const commonValidationRules = {
    // اطلاعات شخصی
    name: {
        required: true,
        minLength: 2,
        pattern: /^[\u0600-\u06FF\s]+$/,
        message: 'نام باید فارسی و حداقل 2 حرف باشد'
    },
    phone: {
        required: true,
        length: 11,
        pattern: /^09[0-9]{9}$/,
        message: 'شماره موبایل باید 11 رقم و با 09 شروع شود'
    },
    telephone: {
        required: false,
        pattern: /^[0-9]{11}$/,
        message: 'تلفن ثابت باید 11 رقم باشد'
    },
    nationalcode: {
        required: true,
        length: 10,
        pattern: /^[0-9]{10}$/,
        message: 'کد ملی باید 10 رقم باشد'
    },
    birthdate: {
        required: true,
        pattern: /^[0-9]{4}\/[0-9]{2}\/[0-9]{2}$/,
        message: 'تاریخ تولد باید به فرمت ۱۴۰۰/۰۱/۰۱ باشد'
    },
    // اطلاعات تحصیلی
    score: {
        required: true,
        pattern: /^[0-9]{1,2}(\.[0-9]{1,2})?$/,
        message: 'معدل باید عدد صحیح یا اعشاری معتبر باشد'
    },
    // آدرس
    address: {
        required: true,
        minLength: 10,
        message: 'آدرس باید حداقل 10 کاراکتر باشد'
    }
};

/**
 * اعتبارسنجی یک فیلد بر اساس قوانین
 * @param {HTMLElement} field فیلد مورد نظر
 * @param {Object} rules قوانین اعتبارسنجی
 * @returns {boolean} آیا فیلد معتبر است یا نه
 */
function validateField(field, rules) {
    const value = field.value.trim();
    const fieldName = field.name || field.id;

    // پاک کردن خطاهای قبلی
    clearFieldError(fieldName);

    // بررسی الزامی بودن
    if (rules.required && !value) {
        showFieldError(fieldName, rules.message || 'این فیلد الزامی است');
        return false;
    }

    // اگر فیلد خالی است و الزامی نیست
    if (!value && !rules.required) {
        return true;
    }

    // بررسی طول دقیق
    if (rules.length && value.length !== rules.length) {
        showFieldError(fieldName, rules.message || `این فیلد باید دقیقاً ${rules.length} کاراکتر باشد`);
        return false;
    }

    // بررسی حداقل طول
    if (rules.minLength && value.length < rules.minLength) {
        showFieldError(fieldName, rules.message || `این فیلد باید حداقل ${rules.minLength} کاراکتر باشد`);
        return false;
    }

    // بررسی حداکثر طول
    if (rules.maxLength && value.length > rules.maxLength) {
        showFieldError(fieldName, rules.message || `این فیلد نمی‌تواند بیش از ${rules.maxLength} کاراکتر باشد`);
        return false;
    }

    // بررسی الگو
    if (rules.pattern && !rules.pattern.test(value)) {
        showFieldError(fieldName, rules.message || 'فرمت وارد شده صحیح نیست');
        return false;
    }

    // فیلد معتبر است
    showFieldSuccess(fieldName);
    return true;
}

/**
 * نمایش خطای فیلد
 * @param {string} fieldName نام فیلد
 * @param {string} message پیام خطا
 */
function showFieldError(fieldName, message) {
    const field = document.querySelector(`[name="${fieldName}"], #${fieldName}`);
    if (!field) return;

    field.classList.add('field-error');
    field.classList.remove('field-success');

    // حذف پیام خطای قبلی
    const existingError = field.parentNode.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }

    // اضافه کردن پیام خطای جدید
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.innerHTML = `
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
        </svg>
        ${message}
    `;
    field.parentNode.appendChild(errorDiv);
}

/**
 * نمایش موفقیت فیلد
 * @param {string} fieldName نام فیلد
 */
function showFieldSuccess(fieldName) {
    const field = document.querySelector(`[name="${fieldName}"], #${fieldName}`);
    if (!field) return;

    field.classList.remove('field-error');
    field.classList.add('field-success');

    // حذف پیام خطای قبلی
    const existingError = field.parentNode.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
}

/**
 * پاک کردن خطای فیلد
 * @param {string} fieldName نام فیلد
 */
function clearFieldError(fieldName) {
    const field = document.querySelector(`[name="${fieldName}"], #${fieldName}`);
    if (!field) return;

    field.classList.remove('field-error', 'field-success');

    const existingError = field.parentNode.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
}

/**
 * اعتبارسنجی همه فیلدهای یک فرم
 * @param {HTMLFormElement} form فرم مورد نظر
 * @param {Object} validationRules قوانین اعتبارسنجی
 * @returns {boolean} آیا فرم معتبر است یا نه
 */
function validateForm(form, validationRules) {
    let isValid = true;

    Object.keys(validationRules).forEach(fieldName => {
        const field = form.querySelector(`[name="${fieldName}"], #${fieldName}`);
        if (field && !validateField(field, validationRules[fieldName])) {
            isValid = false;
        }
    });

    return isValid;
}

/**
 * تبدیل اعداد فارسی به انگلیسی
 * @param {string} str رشته ورودی
 * @returns {string} رشته با اعداد انگلیسی
 */
function persianToEnglishNumbers(str) {
    const persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    for (let i = 0; i < persianNumbers.length; i++) {
        str = str.replace(new RegExp(persianNumbers[i], 'g'), englishNumbers[i]);
    }

    return str;
}

/**
 * تبدیل اعداد انگلیسی به فارسی
 * @param {string} str رشته ورودی
 * @returns {string} رشته با اعداد فارسی
 */
function englishToPersianNumbers(str) {
    const persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    for (let i = 0; i < englishNumbers.length; i++) {
        str = str.replace(new RegExp(englishNumbers[i], 'g'), persianNumbers[i]);
    }

    return str;
}

// Export functions برای استفاده در ماژول‌ها
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        commonValidationRules,
        validateField,
        showFieldError,
        showFieldSuccess,
        clearFieldError,
        validateForm,
        persianToEnglishNumbers,
        englishToPersianNumbers
    };
}
