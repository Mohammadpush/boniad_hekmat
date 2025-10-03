// اینیشالایزرهای مختلف فیلدها

// ویرایش کد ملی (با اعتبارسنجی خاص)
function initializeNationalCodeEdit() {
    console.log('🆔 Initializing national code edit...');

    const display = document.getElementById('modalNationalCodeDisplay');
    const form = document.getElementById('modalNationalCodeForm');
    const input = document.getElementById('modalNationalCodeInput');
    const error = document.getElementById('modalNationalCodeError');
    const editBtn = document.getElementById('editNationalCodeBtn');
    const cancelBtn = document.getElementById('cancelNationalCodeEdit');

    if (!display || !form || !input || !error || !editBtn || !cancelBtn) {
        console.log('❌ Missing elements for national code edit');
        return;
    }

    // اعتبارسنجی کد ملی
    function validateNationalCode(val) {
        if (!val || val.length !== 10) return 'کد ملی باید 10 رقم باشد';
        if (!/^[0-9]+$/.test(val)) return 'کد ملی فقط باید شامل اعداد باشد';

        // الگوریتم صحت کد ملی
        const check = parseInt(val.charAt(9));
        let sum = 0;
        for (let i = 0; i < 9; i++) sum += parseInt(val.charAt(i)) * (10 - i);
        const rem = sum % 11;
        if (!((rem < 2 && check === rem) || (rem >= 2 && check === 11 - rem)))
            return 'کد ملی معتبر نیست';
        return '';
    }

    createFieldEditor({
        displayId: 'modalNationalCodeDisplay',
        formId: 'modalNationalCodeForm',
        inputId: 'modalNationalCodeInput',
        errorId: 'modalNationalCodeError',
        editBtnId: 'editNationalCodeBtn',
        cancelBtnId: 'cancelNationalCodeEdit',
        fieldName: 'nationalcode',
        validateFn: validateNationalCode,
        successMessage: 'کد ملی با موفقیت ذخیره شد'
    });
}

// اینیشالایزرهای ساده
function initializeBasicFields() {
    // نام
    createFieldEditor({
        displayId: 'modalNameDisplay',
        formId: 'modalNameForm',
        inputId: 'modalNameInput',
        errorId: 'modalNameError',
        editBtnId: 'editNameBtn',
        cancelBtnId: 'cancelNameEdit',
        fieldName: 'name',
        validateFn: (val) => {
            if (!val || val.trim().length < 2) return 'نام باید حداقل 2 کاراکتر باشد';
            if (val.length > 75) return 'نام نباید بیشتر از 75 کاراکتر باشد';
            return '';
        },
        successMessage: 'نام با موفقیت ذخیره شد'
    });

    // تاریخ تولد
    createFieldEditor({
        displayId: 'modalBirthdateDisplay',
        formId: 'modalBirthdateForm',
        inputId: 'modalBirthdateInput',
        errorId: 'modalBirthdateError',
        editBtnId: 'editBirthdateBtn',
        cancelBtnId: 'cancelBirthdateEdit',
        fieldName: 'birthdate',
        validateFn: (val) => {
            if (!val) return 'تاریخ تولد الزامی است';
            const datePattern = /^(\d{4})\/(\d{1,2})\/(\d{1,2})$/;
            if (!datePattern.test(val)) return 'فرمت تاریخ صحیح نیست (مثال: ۱۴۰۰/۰۱/۰۱)';
            return '';
        },
        successMessage: 'تاریخ تولد با موفقیت ذخیره شد'
    });

    // شماره موبایل
    createFieldEditor({
        displayId: 'modalPhoneDisplay',
        formId: 'modalPhoneForm',
        inputId: 'modalPhoneInput',
        errorId: 'modalPhoneError',
        editBtnId: 'editPhoneBtn',
        cancelBtnId: 'cancelPhoneEdit',
        fieldName: 'phone',
        validateFn: (val) => {
            if (!val) return 'شماره موبایل الزامی است';
            if (val.length !== 11) return 'شماره موبایل باید 11 رقم باشد';
            if (!/^09[0-9]{9}$/.test(val)) return 'شماره موبایل باید با 09 شروع شود';
            return '';
        },
        successMessage: 'شماره موبایل با موفقیت ذخیره شد'
    });

    // تلفن ثابت
    createFieldEditor({
        displayId: 'modalTelephoneDisplay',
        formId: 'modalTelephoneForm',
        inputId: 'modalTelephoneInput',
        errorId: 'modalTelephoneError',
        editBtnId: 'editTelephoneBtn',
        cancelBtnId: 'cancelTelephoneEdit',
        fieldName: 'telephone',
        validateFn: (val) => {
            if (val && val.length !== 11) return 'تلفن ثابت باید 11 رقم باشد';
            if (val && !/^[0-9]+$/.test(val)) return 'تلفن ثابت فقط باید شامل اعداد باشد';
            return '';
        },
        successMessage: 'تلفن ثابت با موفقیت ذخیره شد'
    });
}

// اینیشالایزرهای تحصیلی
function initializeEducationFields() {
    // پایه تحصیلی
    createSelectEditor({
        displayId: 'modalGradeDisplay',
        formId: 'modalGradeForm',
        inputId: 'modalGradeInput',
        errorId: 'modalGradeError',
        editBtnId: 'editGradeBtn',
        cancelBtnId: 'cancelGradeEdit',
        fieldName: 'grade',
        validateFn: (val) => !val ? 'پایه تحصیلی الزامی است' : '',
        successMessage: 'پایه تحصیلی با موفقیت ذخیره شد'
    });

    // نام مدرسه
    createFieldEditor({
        displayId: 'modalSchoolDisplay',
        formId: 'modalSchoolForm',
        inputId: 'modalSchoolInput',
        errorId: 'modalSchoolError',
        editBtnId: 'editSchoolBtn',
        cancelBtnId: 'cancelSchoolEdit',
        fieldName: 'school',
        validateFn: (val) => !val || val.trim().length < 2 ? 'نام مدرسه باید حداقل 2 کاراکتر باشد' : '',
        successMessage: 'نام مدرسه با موفقیت ذخیره شد'
    });

    // نام مدیر
    createFieldEditor({
        displayId: 'modalPrincipalDisplay',
        formId: 'modalPrincipalForm',
        inputId: 'modalPrincipalInput',
        errorId: 'modalPrincipalError',
        editBtnId: 'editPrincipalBtn',
        cancelBtnId: 'cancelPrincipalEdit',
        fieldName: 'principal',
        validateFn: (val) => !val || val.trim().length < 2 ? 'نام مدیر مدرسه باید حداقل 2 کاراکتر باشد' : '',
        successMessage: 'نام مدیر مدرسه با موفقیت ذخیره شد'
    });

    // معدل
    createFieldEditor({
        displayId: 'modalLastScoreDisplay',
        formId: 'modalLastScoreForm',
        inputId: 'modalLastScoreInput',
        errorId: 'modalLastScoreError',
        editBtnId: 'editLastScoreBtn',
        cancelBtnId: 'cancelLastScoreEdit',
        fieldName: 'last_score',
        validateFn: (val) => {
            if (!val) return 'معدل الزامی است';
            if (isNaN(val) || val < 0 || val > 20) return 'معدل باید بین 0 تا 20 باشد';
            return '';
        },
        successMessage: 'معدل با موفقیت ذخیره شد'
    });

    // تلفن مدرسه
    createFieldEditor({
        displayId: 'modalSchoolTelephoneDisplay',
        formId: 'modalSchoolTelephoneForm',
        inputId: 'modalSchoolTelephoneInput',
        errorId: 'modalSchoolTelephoneError',
        editBtnId: 'editSchoolTelephoneBtn',
        cancelBtnId: 'cancelSchoolTelephoneEdit',
        fieldName: 'school_telephone',
        validateFn: (val) => {
            if (val && val.length !== 11) return 'تلفن مدرسه باید 11 رقم باشد';
            if (val && !/^[0-9]+$/.test(val)) return 'تلفن مدرسه فقط باید شامل اعداد باشد';
            return '';
        },
        successMessage: 'تلفن مدرسه با موفقیت ذخیره شد'
    });
}
// اضافه کردن اینیشالایزر سطح انگلیسی
function initializeEnglishLevelEdit() {
    console.log('🇬🇧 Initializing English level edit...');

    const displayContainer = document.getElementById('modalEnglishDisplay');
    const editContainer = document.getElementById('modalEnglishEdit');
    const slider = document.getElementById('modalEnglishSlider');
    const sliderValue = document.getElementById('modalEnglishSliderValue');
    const editBtn = document.getElementById('editEnglishBtn');
    const saveBtn = document.getElementById('saveEnglishBtn');
    const cancelBtn = document.getElementById('cancelEnglishBtn');
    const progressBar = document.getElementById('modalEnglishBar');
    const percentDisplay = document.getElementById('modalEnglishPercent');

    if (!displayContainer || !editContainer || !slider || !editBtn || !saveBtn || !cancelBtn) {
        console.log('❌ Missing elements for English level edit');
        return;
    }

    let currentValue = parseInt(percentDisplay.textContent.replace('%', '')) || 0;

    // تنظیم مقدار اولیه اسلایدر
    slider.value = currentValue;
    slider.setAttribute('value', currentValue);
    sliderValue.textContent = currentValue + '%';

    // دکمه ویرایش
    editBtn.addEventListener('click', function() {
        displayContainer.classList.add('hidden');
        editContainer.classList.remove('hidden');
        editContainer.classList.add('flex');

        slider.value = currentValue;
        slider.setAttribute('value', currentValue);
        sliderValue.textContent = currentValue + '%';

        editBtn.classList.add('hidden');
        saveBtn.classList.remove('hidden');
        cancelBtn.classList.remove('hidden');
    });

    // دکمه لغو
    cancelBtn.addEventListener('click', function() {
        editContainer.classList.add('hidden');
        displayContainer.classList.remove('hidden');

        editBtn.classList.remove('hidden');
        saveBtn.classList.add('hidden');
        cancelBtn.classList.add('hidden');

        slider.value = currentValue;
        slider.setAttribute('value', currentValue);
        sliderValue.textContent = currentValue + '%';
    });

    // بروزرسانی زنده اسلایدر
    slider.addEventListener('input', function() {
        const newValue = parseInt(this.value);
        sliderValue.textContent = newValue + '%';
        this.setAttribute('value', newValue);
    });

    // دکمه ذخیره
    saveBtn.addEventListener('click', function() {
        const newValue = parseInt(slider.value);

        updateRequestField('english_proficiency', newValue.toString())
        .then(data => {
            if (data.success) {
                currentValue = newValue;

                editContainer.classList.add('hidden');
                displayContainer.classList.remove('hidden');

                editBtn.classList.remove('hidden');
                saveBtn.classList.add('hidden');
                cancelBtn.classList.add('hidden');

                // بروزرسانی نمایش
                percentDisplay.textContent = newValue + '%';
                progressBar.style.width = newValue + '%';

                // نمایش پیام موفقیت ساده
                console.log('✅ سطح زبان انگلیسی با موفقیت ذخیره شد');

                // اگر تابع نمایش پیام موجود است، از آن استفاده کند
                if (typeof showSuccessMessage === 'function') {
                    showSuccessMessage('سطح زبان انگلیسی با موفقیت ذخیره شد');
                }

                // بروزرسانی داده‌ها اگر تابع موجود است
                if (typeof refreshRequestData === 'function') {
                    refreshRequestData();
                }
            } else {
                alert(data.message || 'خطا در ذخیره اطلاعات');
            }
        })
        .catch(err => {
            console.error('❌ English level error:', err);
            alert('خطا در ارتباط با سرور');
        });
    });
}
// اینیشالایزرهای مسکن
function initializeHousingFields() {
    // وضعیت مسکن
    createSelectEditor({
        displayId: 'modalRentalDisplay',
        formId: 'modalRentalForm',
        inputId: 'modalRentalInput',
        errorId: 'modalRentalError',
        editBtnId: 'editRentalBtn',
        cancelBtnId: 'cancelRentalEdit',
        fieldName: 'rental',
        validateFn: (val) => val === '' ? 'وضعیت مسکن الزامی است' : '',
        successMessage: 'وضعیت مسکن با موفقیت ذخیره شد',
        transformDisplay: (val) => val === '0' || val === 0 ? 'ملکی' : 'استیجاری'
    });

    // آدرس
    createTextAreaEditor({
        displayId: 'modalAddressDisplay',
        formId: 'modalAddressForm',
        inputId: 'modalAddressInput',
        errorId: 'modalAddressError',
        editBtnId: 'editAddressBtn',
        cancelBtnId: 'cancelAddressEdit',
        fieldName: 'address',
        validateFn: (val) => !val || val.trim().length < 10 ? 'آدرس باید حداقل 10 کاراکتر باشد' : '',
        successMessage: 'آدرس با موفقیت ذخیره شد'
    });
}
