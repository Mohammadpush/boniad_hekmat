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
// اینیشالایزر مسکن
function initializeHousingFields() {
    console.log('🏠 Initializing housing fields...');

    // مسکن (استیجاری/ملکی)
    createSelectEditor({
        displayId: 'modalRentalDisplay',
        formId: 'modalRentalForm',
        inputId: 'modalRentalInput',
        errorId: 'modalRentalError',
        editBtnId: 'editRentalBtn',
        cancelBtnId: 'cancelRentalEdit',
        fieldName: 'rental',
        options: [
            { value: '0', text: '🏠 ملکی' },
            { value: '1', text: '🏠 استیجاری' }
        ],
        validateFn: (val) => val === '0' || val === '1' ? '' : 'نوع مسکن نامعتبر است',
        successMessage: 'وضعیت مسکن با موفقیت ذخیره شد'
    });

    // آدرس
    createFieldEditor({
        displayId: 'modalAddressDisplay',
        formId: 'modalAddressForm',
        inputId: 'modalAddressInput',
        errorId: 'modalAddressError',
        editBtnId: 'editAddressBtn',
        cancelBtnId: 'cancelAddressEdit',
        fieldName: 'address',
        validateFn: (val) => !val || val.trim().length < 5 ? 'آدرس باید حداقل 5 کاراکتر باشد' : '',
        successMessage: 'آدرس با موفقیت ذخیره شد'
    });
}
// اینیشالایزر خانواده
function initializeFamilyFields() {
    console.log('👨‍👩‍👧‍👦 Initializing family fields...');

    // تعداد خواهر برادر
    createFieldEditor({
        displayId: 'modalSiblingsCountDisplay',
        formId: 'modalSiblingsCountForm',
        inputId: 'modalSiblingsCountInput',
        errorId: 'modalSiblingsCountError',
        editBtnId: 'editSiblingsCountBtn',
        cancelBtnId: 'cancelSiblingsCountEdit',
        fieldName: 'siblings_count',
        inputType: 'number',
        validateFn: (val) => {
            const num = parseInt(val);
            return isNaN(num) || num < 0 || num > 20 ? 'تعداد خواهر برادر باید بین 0 تا 20 باشد' : '';
        },
        successMessage: 'تعداد خواهر برادر با موفقیت ذخیره شد'
    });

    // رتبه فرزندی
    createSiblingsRankEditor({
        displayId: 'modalSiblingsRankDisplay',
        formId: 'modalSiblingsRankForm',
        inputId: 'modalSiblingsRankInput',
        errorId: 'modalSiblingsRankError',
        editBtnId: 'editSiblingsRankBtn',
        cancelBtnId: 'cancelSiblingsRankEdit',
        iconsContainerId: 'modalSiblingsIconsContainer',
        fieldName: 'siblings_rank',
        validateFn: (val) => {
            const num = parseInt(val);
            return isNaN(num) || num < 1 || num > 20 ? 'رتبه فرزندی باید بین 1 تا 20 باشد' : '';
        },
        successMessage: 'رتبه فرزندی با موفقیت ذخیره شد'
    });

    // چگونه آشنا شدید
    createSelectEditor({
        displayId: 'modalKnowDisplay',
        formId: 'modalKnowForm',
        inputId: 'modalKnowInput',
        errorId: 'modalKnowError',
        editBtnId: 'editKnowBtn',
        cancelBtnId: 'cancelKnowEdit',
        fieldName: 'know',
        options: [
            { value: 'دوستان', text: 'دوستان' },
            { value: 'خانواده', text: 'خانواده' },
            { value: 'معلم', text: 'معلم' },
            { value: 'اینترنت', text: 'اینترنت' },
            { value: 'تبلیغات', text: 'تبلیغات' },
            { value: 'سایر', text: 'سایر' }
        ],
        validateFn: (val) => !val ? 'نحوه آشنایی الزامی است' : '',
        successMessage: 'نحوه آشنایی با موفقیت ذخیره شد'
    });

    // روش مشاوره
    createSelectEditor({
        displayId: 'modalCounselingMethodDisplay',
        formId: 'modalCounselingMethodForm',
        inputId: 'modalCounselingMethodInput',
        errorId: 'modalCounselingMethodError',
        editBtnId: 'editCounselingMethodBtn',
        cancelBtnId: 'cancelCounselingMethodEdit',
        fieldName: 'counseling_method',
        options: [
            { value: 'حضوری', text: 'حضوری' },
            { value: 'آنلاین', text: 'آنلاین' },
            { value: 'تلفنی', text: 'تلفنی' }
        ],
        validateFn: (val) => !val ? 'روش مشاوره الزامی است' : '',
        successMessage: 'روش مشاوره با موفقیت ذخیره شد',
        onChange: function(value) {
            // نمایش/مخفی کردن فیلد why_counseling_method
            const whyDiv = document.getElementById('modalWhyCounselingMethodDiv');
            if (value === 'آنلاین' || value === 'تلفنی') {
                whyDiv.classList.remove('hidden');
            } else {
                whyDiv.classList.add('hidden');
                // پاک کردن مقدار اگر مخفی شد
                const whyDisplay = document.getElementById('modalWhyCounselingMethod');
                const whyForm = document.getElementById('modalWhyCounselingMethodForm');
                if (whyDisplay && whyForm) {
                    whyDisplay.textContent = '';
                    whyForm.classList.add('hidden');
                }
            }
        }
    });

    // چرا این روش مشاوره
    createFieldEditor({
        displayId: 'modalWhyCounselingMethod',
        formId: 'modalWhyCounselingMethodForm',
        inputId: 'modalWhyCounselingMethodInput',
        errorId: 'modalWhyCounselingMethodError',
        editBtnId: 'editWhyCounselingMethodBtn',
        cancelBtnId: 'cancelWhyCounselingMethodEdit',
        fieldName: 'why_counseling_method',
        validateFn: (val) => !val || val.trim().length < 5 ? 'دلیل انتخاب روش مشاوره باید حداقل 5 کاراکتر باشد' : '',
        successMessage: 'دلیل انتخاب روش مشاوره با موفقیت ذخیره شد'
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

    console.log('English elements found:', {
        displayContainer: !!displayContainer,
        editContainer: !!editContainer,
        slider: !!slider,
        sliderValue: !!sliderValue,
        editBtn: !!editBtn,
        saveBtn: !!saveBtn,
        cancelBtn: !!cancelBtn,
        progressBar: !!progressBar,
        percentDisplay: !!percentDisplay
    });

    if (!displayContainer || !editContainer || !slider || !editBtn || !saveBtn || !cancelBtn) {
        console.log('❌ Missing elements for English level edit');
        return;
    }

    console.log('✅ Setting up English level edit event listeners');

    // پاک کردن event listenerهای قبلی
    editBtn.replaceWith(editBtn.cloneNode(true));
    saveBtn.replaceWith(saveBtn.cloneNode(true));
    cancelBtn.replaceWith(cancelBtn.cloneNode(true));
    slider.replaceWith(slider.cloneNode(true));

    // گرفتن المنت‌های جدید
    const newEditBtn = document.getElementById('editEnglishBtn');
    const newSaveBtn = document.getElementById('saveEnglishBtn');
    const newCancelBtn = document.getElementById('cancelEnglishBtn');
    const newSlider = document.getElementById('modalEnglishSlider');
    const newSliderValue = document.getElementById('modalEnglishSliderValue');

    // تنظیم مقدار اولیه اسلایدر
    newSlider.value = currentValue;
    newSlider.setAttribute('value', currentValue);
    newSliderValue.textContent = currentValue + '%';

    // دکمه ویرایش
    newEditBtn.addEventListener('click', function() {
        console.log('✏️ English edit button clicked');
        displayContainer.classList.add('hidden');
        editContainer.classList.remove('hidden');
        editContainer.classList.add('flex');

        newSlider.value = currentValue;
        newSlider.setAttribute('value', currentValue);
        newSliderValue.textContent = currentValue + '%';

        newEditBtn.classList.add('hidden');
        newSaveBtn.classList.remove('hidden');
        newCancelBtn.classList.remove('hidden');
    });

    // دکمه لغو
    newCancelBtn.addEventListener('click', function() {
        console.log('❌ English cancel button clicked');
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
    newSlider.addEventListener('input', function() {
        const newValue = parseInt(this.value);
        newSliderValue.textContent = newValue + '%';
        this.setAttribute('value', newValue);
    });

    // دکمه ذخیره
    newSaveBtn.addEventListener('click', function() {
        console.log('💾 English save button clicked');
        const newValue = parseInt(newSlider.value);

        updateRequestField('english_proficiency', newValue.toString())
        .then(data => {
            if (data.success) {
                currentValue = newValue;

                editContainer.classList.add('hidden');
                displayContainer.classList.remove('hidden');

                newEditBtn.classList.remove('hidden');
                newSaveBtn.classList.add('hidden');
                newCancelBtn.classList.add('hidden');

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
// اینیشالایزرهای والدین
function initializeParentFields() {
    // پدر
    createFieldEditor({
        displayId: 'modalFatherName',
        formId: 'modalFatherNameForm',
        inputId: 'modalFatherNameInput',
        errorId: 'modalFatherNameError',
        editBtnId: 'editFatherNameBtn',
        cancelBtnId: 'cancelFatherNameEdit',
        fieldName: 'father_name',
        validateFn: (val) => !val || val.trim().length < 2 ? 'نام پدر باید حداقل 2 کاراکتر باشد' : '',
        successMessage: 'نام پدر با موفقیت ذخیره شد'
    });

    createFieldEditor({
        displayId: 'modalFatherPhone',
        formId: 'modalFatherPhoneForm',
        inputId: 'modalFatherPhoneInput',
        errorId: 'modalFatherPhoneError',
        editBtnId: 'editFatherPhoneBtn',
        cancelBtnId: 'cancelFatherPhoneEdit',
        fieldName: 'father_phone',
        validateFn: (val) => !val || !/^09[0-9]{9}$/.test(val) ? 'شماره موبایل پدر باید 11 رقم و با 09 شروع شود' : '',
        successMessage: 'شماره موبایل پدر با موفقیت ذخیره شد'
    });

    createFieldEditor({
        displayId: 'modalFatherJob',
        formId: 'modalFatherJobForm',
        inputId: 'modalFatherJobInput',
        errorId: 'modalFatherJobError',
        editBtnId: 'editFatherJobBtn',
        cancelBtnId: 'cancelFatherJobEdit',
        fieldName: 'father_job',
        validateFn: (val) => !val || val.trim().length < 2 ? 'شغل پدر باید حداقل 2 کاراکتر باشد' : '',
        successMessage: 'شغل پدر با موفقیت ذخیره شد'
    });

    createFieldEditor({
        displayId: 'modalFatherIncome',
        formId: 'modalFatherIncomeForm',
        inputId: 'modalFatherIncomeInput',
        errorId: 'modalFatherIncomeError',
        editBtnId: 'editFatherIncomeBtn',
        cancelBtnId: 'cancelFatherIncomeEdit',
        fieldName: 'father_income',
        validateFn: (val) => !val || isNaN(val) || val < 0 ? 'درآمد پدر باید عدد مثبت باشد' : '',
        successMessage: 'درآمد پدر با موفقیت ذخیره شد'
    });

    createTextAreaEditor({
        displayId: 'modalFatherJobAddress',
        formId: 'modalFatherJobAddressForm',
        inputId: 'modalFatherJobAddressInput',
        errorId: 'modalFatherJobAddressError',
        editBtnId: 'editFatherJobAddressBtn',
        cancelBtnId: 'cancelFatherJobAddressEdit',
        fieldName: 'father_job_address',
        validateFn: (val) => !val || val.trim().length < 10 ? 'آدرس محل کار پدر باید حداقل 10 کاراکتر باشد' : '',
        successMessage: 'آدرس محل کار پدر با موفقیت ذخیره شد'
    });

    // مادر
    createFieldEditor({
        displayId: 'modalMotherName',
        formId: 'modalMotherNameForm',
        inputId: 'modalMotherNameInput',
        errorId: 'modalMotherNameError',
        editBtnId: 'editMotherNameBtn',
        cancelBtnId: 'cancelMotherNameEdit',
        fieldName: 'mother_name',
        validateFn: (val) => !val || val.trim().length < 2 ? 'نام مادر باید حداقل 2 کاراکتر باشد' : '',
        successMessage: 'نام مادر با موفقیت ذخیره شد'
    });

    createFieldEditor({
        displayId: 'modalMotherPhone',
        formId: 'modalMotherPhoneForm',
        inputId: 'modalMotherPhoneInput',
        errorId: 'modalMotherPhoneError',
        editBtnId: 'editMotherPhoneBtn',
        cancelBtnId: 'cancelMotherPhoneEdit',
        fieldName: 'mother_phone',
        validateFn: (val) => !val || !/^09[0-9]{9}$/.test(val) ? 'شماره موبایل مادر باید 11 رقم و با 09 شروع شود' : '',
        successMessage: 'شماره موبایل مادر با موفقیت ذخیره شد'
    });

    createFieldEditor({
        displayId: 'modalMotherJob',
        formId: 'modalMotherJobForm',
        inputId: 'modalMotherJobInput',
        errorId: 'modalMotherJobError',
        editBtnId: 'editMotherJobBtn',
        cancelBtnId: 'cancelMotherJobEdit',
        fieldName: 'mother_job',
        validateFn: (val) => !val || val.trim().length < 2 ? 'شغل مادر باید حداقل 2 کاراکتر باشد' : '',
        successMessage: 'شغل مادر با موفقیت ذخیره شد'
    });

    createFieldEditor({
        displayId: 'modalMotherIncome',
        formId: 'modalMotherIncomeForm',
        inputId: 'modalMotherIncomeInput',
        errorId: 'modalMotherIncomeError',
        editBtnId: 'editMotherIncomeBtn',
        cancelBtnId: 'cancelMotherIncomeEdit',
        fieldName: 'mother_income',
        validateFn: (val) => !val || isNaN(val) || val < 0 ? 'درآمد مادر باید عدد مثبت باشد' : '',
        successMessage: 'درآمد مادر با موفقیت ذخیره شد'
    });

    createTextAreaEditor({
        displayId: 'modalMotherJobAddress',
        formId: 'modalMotherJobAddressForm',
        inputId: 'modalMotherJobAddressInput',
        errorId: 'modalMotherJobAddressError',
        editBtnId: 'editMotherJobAddressBtn',
        cancelBtnId: 'cancelMotherJobAddressEdit',
        fieldName: 'mother_job_address',
        validateFn: (val) => !val || val.trim().length < 10 ? 'آدرس محل کار مادر باید حداقل 10 کاراکتر باشد' : '',
        successMessage: 'آدرس محل کار مادر با موفقیت ذخیره شد'
    });
}

// اینیشالایزرهای سوالات نهایی
function initializeFinalQuestionsFields() {
    // انگیزه
    createTextAreaEditor({
        displayId: 'modalMotivation',
        formId: 'modalMotivationForm',
        inputId: 'modalMotivationInput',
        errorId: 'modalMotivationError',
        editBtnId: 'editMotivationBtn',
        cancelBtnId: 'cancelMotivationEdit',
        fieldName: 'motivation',
        validateFn: (val) => !val || val.trim().length < 30 ? 'انگیزه باید حداقل 30 کاراکتر باشد' : '',
        successMessage: 'انگیزه با موفقیت ذخیره شد'
    });

    // نحوه استفاده
    createTextAreaEditor({
        displayId: 'modalSpend',
        formId: 'modalSpendForm',
        inputId: 'modalSpendInput',
        errorId: 'modalSpendError',
        editBtnId: 'editSpendBtn',
        cancelBtnId: 'cancelSpendEdit',
        fieldName: 'spend',
        validateFn: (val) => !val || val.trim().length < 10 ? 'نحوه استفاده باید حداقل 10 کاراکتر باشد' : '',
        successMessage: 'نحوه استفاده با موفقیت ذخیره شد'
    });

    // معرفی خود
    createTextAreaEditor({
        displayId: 'modalHowAmI',
        formId: 'modalHowAmIForm',
        inputId: 'modalHowAmIInput',
        errorId: 'modalHowAmIError',
        editBtnId: 'editHowAmIBtn',
        cancelBtnId: 'cancelHowAmIEdit',
        fieldName: 'how_am_i',
        validateFn: (val) => !val || val.trim().length < 10 ? 'معرفی خود باید حداقل 10 کاراکتر باشد' : '',
        successMessage: 'معرفی خود با موفقیت ذخیره شد'
    });

    // برنامه‌های آینده
    createTextAreaEditor({
        displayId: 'modalFuture',
        formId: 'modalFutureForm',
        inputId: 'modalFutureInput',
        errorId: 'modalFutureError',
        editBtnId: 'editFutureBtn',
        cancelBtnId: 'cancelFutureEdit',
        fieldName: 'future',
        validateFn: (val) => !val || val.trim().length < 10 ? 'برنامه‌های آینده باید حداقل 10 کاراکتر باشد' : '',
        successMessage: 'برنامه‌های آینده با موفقیت ذخیره شد'
    });

    // رشته مورد علاقه
    createFieldEditor({
        displayId: 'modalFavoriteMajor',
        formId: 'modalFavoriteMajorForm',
        inputId: 'modalFavoriteMajorInput',
        errorId: 'modalFavoriteMajorError',
        editBtnId: 'editFavoriteMajorBtn',
        cancelBtnId: 'cancelFavoriteMajorEdit',
        fieldName: 'favorite_major',
        validateFn: (val) => !val || val.trim().length < 2 ? 'رشته مورد علاقه باید حداقل 2 کاراکتر باشد' : '',
        successMessage: 'رشته مورد علاقه با موفقیت ذخیره شد'
    });

    // آمادگی کمک به دیگران
    createSelectEditor({
        displayId: 'modalHelpOthers',
        formId: 'modalHelpOthersForm',
        inputId: 'modalHelpOthersInput',
        errorId: 'modalHelpOthersError',
        editBtnId: 'editHelpOthersBtn',
        cancelBtnId: 'cancelHelpOthersEdit',
        fieldName: 'help_others',
        validateFn: (val) => !val ? 'انتخاب این گزینه الزامی است' : '',
        successMessage: 'آمادگی کمک با موفقیت ذخیره شد'
    });

    // پیشنهادات
    createTextAreaEditor({
        displayId: 'modalSuggestion',
        formId: 'modalSuggestionForm',
        inputId: 'modalSuggestionInput',
        errorId: 'modalSuggestionError',
        editBtnId: 'editSuggestionBtn',
        cancelBtnId: 'cancelSuggestionEdit',
        fieldName: 'suggestion',
        validateFn: (val) => '', // optional
        successMessage: 'پیشنهادات با موفقیت ذخیره شد'
    });
}
