// Field Management
// Combines field-editors.js, field-initializers.js, and main-initializer.js

// Track multiple editing fields
let editingFields = new Set(); // Use Set to store field names being edited
window.editingFields = editingFields; // Make it globally accessible

// Update editing popup UI (placeholder function)
function updateEditingPopup() {

    // Add logic here if needed to update UI based on editing fields
}

// Generic field editor creator
function createFieldEditor(config) {


    const display = document.getElementById(config.displayId);
    const form = document.getElementById(config.formId);
    const input = document.getElementById(config.inputId);
    const error = document.getElementById(config.errorId);
    const editBtn = document.getElementById(config.editBtnId);
    const cancelBtn = document.getElementById(config.cancelBtnId);

// Check if all elements exist
const elements = { display, form, input, error, editBtn, cancelBtn };
const missing = Object.keys(elements).filter(key => !elements[key]);

if (missing.length > 0) {
    console.log(`❌ Missing elements for ${config.fieldName}: ${missing.join(', ')}`);
    return;
}

    // Show edit form
    editBtn.addEventListener('click', function() {
        const currentVal = display.textContent.trim();
        input.value = currentVal === 'ندارد' ? '' : currentVal;

        display.classList.add('hidden');
        editBtn.classList.add('hidden');
        form.classList.remove('hidden');
        form.classList.add('flex');
        error.classList.add('hidden');

        // Add to editing fields
        editingFields.add(config.fieldName);
        updateEditingPopup();

        input.focus();
        input.select();
    });

    // Cancel edit
    cancelBtn.addEventListener('click', function() {
        form.classList.add('hidden');
        form.classList.remove('flex');
        display.classList.remove('hidden');
        editBtn.classList.remove('hidden');
        error.classList.add('hidden');

        // Remove from editing fields
        editingFields.delete(config.fieldName);
        updateEditingPopup();
    });

    // Save changes
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const newVal = input.value.trim();
        const errMsg = config.validateFn ? config.validateFn(newVal) : '';

        if (errMsg) {
            error.textContent = errMsg;
            error.classList.remove('hidden');
            return;
        }

        // Show loading
        const loadingEl = showLoadingIndicator('در حال ذخیره...');

        // Send AJAX request
        updateRequestField(config.fieldName, newVal)
            .then(response => {
                if (response.success) {
                    // Update display
                    display.textContent = newVal || 'ندارد';

                    // Hide form
                    form.classList.add('hidden');
                    form.classList.remove('flex');
                    display.classList.remove('hidden');
                    editBtn.classList.remove('hidden');
                    error.classList.add('hidden');

                    // Remove from editing fields
                    editingFields.delete(config.fieldName);
                    updateEditingPopup();

                    // Show success message
                    showSuccessMessage(config.successMessage || 'اطلاعات با موفقیت ذخیره شد');

                    // Refresh modal data
                    if (typeof refreshRequestData === 'function') {
                        setTimeout(refreshRequestData, 500);
                    }

                    // Update page data if needed
                    if (typeof window.updatePageData === 'function') {
                        setTimeout(window.updatePageData, 1000);
                    }
                } else {
                    throw new Error(response.message || 'خطا در ذخیره اطلاعات');
                }
            })
            .catch(error => {
                console.error(`❌ Error updating ${config.fieldName}:`, error);
                error.textContent = 'خطا در ذخیره اطلاعات';
                error.classList.remove('hidden');
            })
            .finally(() => {
                hideLoadingIndicator(loadingEl);
            });
    });
}

// Validation functions
function validateRequired(value, fieldName) {
    if (!value || value.trim() === '') {
        return `${fieldName} نمی‌تواند خالی باشد`;
    }
    return '';
}

function validatePhone(value) {
    if (!/^09\d{9}$/.test(value)) {
        return 'شماره موبایل باید 11 رقم و با 09 شروع شود';
    }
    return '';
}

function validateNationalCode(value) {
    if (!value || value.length !== 10) return 'کد ملی باید 10 رقم باشد';
    if (!/^[0-9]+$/.test(value)) return 'کد ملی فقط باید شامل اعداد باشد';

    // National code algorithm validation
    const check = parseInt(value.charAt(9));
    let sum = 0;
    for (let i = 0; i < 9; i++) sum += parseInt(value.charAt(i)) * (10 - i);
    const rem = sum % 11;
    if (!((rem < 2 && check === rem) || (rem >= 2 && check === 11 - rem)))
        return 'کد ملی معتبر نیست';
    return '';
}

function validateNumber(value, min = 0, max = 100) {
    const num = parseInt(value);
    if (isNaN(num) || num < min || num > max) {
        return `عدد باید بین ${min} و ${max} باشد`;
    }
    return '';
}
function validateWordConut(value){
    const wordconut = value.trim().split('/s/+/').length;
    if(wordconut){
        return 'تعداد کلمات باید حداقل 30 کلمه باشد.'
    }
    return '';
}

// Field initializers
function initializeNationalCodeEdit() {


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

function initializeBasicFields() {


    // Name
    createFieldEditor({
        displayId: 'modalNameDisplay',
        formId: 'modalNameForm',
        inputId: 'modalNameInput',
        errorId: 'modalNameError',
        editBtnId: 'editNameBtn',
        cancelBtnId: 'cancelNameEdit',
        fieldName: 'name',
        validateFn: (val) => validateRequired(val, 'نام'),
        successMessage: 'نام با موفقیت ذخیره شد'
    });

    // Birthdate
    createFieldEditor({
        displayId: 'modalBirthdateDisplay',
        formId: 'modalBirthdateForm',
        inputId: 'modalBirthdateInput',
        errorId: 'modalBirthdateError',
        editBtnId: 'editBirthdateBtn',
        cancelBtnId: 'cancelBirthdateEdit',
        fieldName: 'birthdate',
        validateFn: (val) => validateRequired(val, 'تاریخ تولد'),
        successMessage: 'تاریخ تولد با موفقیت ذخیره شد'
    });

    // Phone
    createFieldEditor({
        displayId: 'modalPhoneDisplay',
        formId: 'modalPhoneForm',
        inputId: 'modalPhoneInput',
        errorId: 'modalPhoneError',
        editBtnId: 'editPhoneBtn',
        cancelBtnId: 'cancelPhoneEdit',
        fieldName: 'phone',
        validateFn: (val) =>validateRequired(val,'شماره تلفن')||validatePhone(val),
        successMessage: 'شماره موبایل با موفقیت ذخیره شد'
    });

    // Telephone
    createFieldEditor({
        displayId: 'modalTelephoneDisplay',
        formId: 'modalTelephoneForm',
        inputId: 'modalTelephoneInput',
        errorId: 'modalTelephoneError',
        editBtnId: 'editTelephoneBtn',
        cancelBtnId: 'cancelTelephoneEdit',
        fieldName: 'telephone',
        successMessage: 'شماره تلفن با موفقیت ذخیره شد'
    });
}

function initializeEducationFields() {


    // Grade
    createFieldEditor({
        displayId: 'modalGradeDisplay',
        formId: 'modalGradeForm',
        inputId: 'modalGradeInput',
        errorId: 'modalGradeError',
        editBtnId: 'editGradeBtn',
        cancelBtnId: 'cancelGradeEdit',
        fieldName: 'grade',
        validateFn: (val) => validateRequired(val, 'پایه'),
        successMessage: 'پایه با موفقیت ذخیره شد'
    });

    // School
    createFieldEditor({
        displayId: 'modalSchoolDisplay',
        formId: 'modalSchoolForm',
        inputId: 'modalSchoolInput',
        errorId: 'modalSchoolError',
        editBtnId: 'editSchoolBtn',
        cancelBtnId: 'cancelSchoolEdit',
        fieldName: 'school',
        validateFn: (val) => validateRequired(val, 'نام مدرسه'),
        successMessage: 'نام مدرسه با موفقیت ذخیره شد'
    });

    // Principal
    createFieldEditor({
        displayId: 'modalPrincipalDisplay',
        formId: 'modalPrincipalForm',
        inputId: 'modalPrincipalInput',
        errorId: 'modalPrincipalError',
        editBtnId: 'editPrincipalBtn',
        cancelBtnId: 'cancelPrincipalEdit',
        fieldName: 'principal',
        validateFn: (val) => validateRequired(val, 'نام مدیر'),
        successMessage: 'نام مدیر با موفقیت ذخیره شد'
    });

    // Last Score
    createFieldEditor({
        displayId: 'modalLastScoreDisplay',
        formId: 'modalLastScoreForm',
        inputId: 'modalLastScoreInput',
        errorId: 'modalLastScoreError',
        editBtnId: 'editLastScoreBtn',
        cancelBtnId: 'cancelLastScoreEdit',
        fieldName: 'last_score',
        validateFn: (val) => validateNumber(val, 0, 20),
        successMessage: 'معدل با موفقیت ذخیره شد'
    });

    // School Telephone
    createFieldEditor({
        displayId: 'modalSchoolTelephoneDisplay',
        formId: 'modalSchoolTelephoneForm',
        inputId: 'modalSchoolTelephoneInput',
        errorId: 'modalSchoolTelephoneError',
        editBtnId: 'editSchoolTelephoneBtn',
        cancelBtnId: 'cancelSchoolTelephoneEdit',
        fieldName: 'school_telephone',
        successMessage: 'شماره تلفن مدرسه با موفقیت ذخیره شد'
    });
}

function initializeHousingFields() {


    // Rental status
    const rentalDisplay = document.getElementById('modalRentalDisplay');
    const rentalForm = document.getElementById('modalRentalForm');
    const rentalInput = document.getElementById('modalRentalInput');
    const rentalError = document.getElementById('modalRentalError');
    const editRentalBtn = document.getElementById('editRentalBtn');
    const cancelRentalEdit = document.getElementById('cancelRentalEdit');

    if (rentalDisplay && rentalForm && rentalInput && rentalError && editRentalBtn && cancelRentalEdit) {
        editRentalBtn.addEventListener('click', function() {
            const currentVal = rentalDisplay.textContent.includes('ملکی') ? '0' : '1';
            rentalInput.value = currentVal;

            rentalDisplay.classList.add('hidden');
            editRentalBtn.classList.add('hidden');
            rentalForm.classList.remove('hidden');
            rentalForm.classList.add('flex');
            rentalError.classList.add('hidden');
        });

        cancelRentalEdit.addEventListener('click', function() {
            rentalForm.classList.add('hidden');
            rentalForm.classList.remove('flex');
            rentalDisplay.classList.remove('hidden');
            editRentalBtn.classList.remove('hidden');
            rentalError.classList.add('hidden');
        });

        rentalForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const newVal = rentalInput.value;
            const displayText = newVal === '0' ? '🏠 ملکی' : '🏠 استیجاری';

            const loadingEl = showLoadingIndicator('در حال ذخیره...');

            updateRequestField('rental', newVal)
                .then(response => {
                    if (response.success) {
                        rentalDisplay.textContent = displayText;
                        rentalForm.classList.add('hidden');
                        rentalForm.classList.remove('flex');
                        rentalDisplay.classList.remove('hidden');
                        editRentalBtn.classList.remove('hidden');
                        showSuccessMessage('وضعیت مسکن با موفقیت ذخیره شد');

                        setTimeout(refreshRequestData, 500);
                    } else {
                        throw new Error(response.message || 'خطا در ذخیره اطلاعات');
                    }
                })
                .catch(error => {
                    console.error('❌ Error updating rental:', error);
                    rentalError.textContent = 'خطا در ذخیره اطلاعات';
                    rentalError.classList.remove('hidden');
                })
                .finally(() => {
                    hideLoadingIndicator(loadingEl);
                });
        });
    }

    // Address
    createFieldEditor({
        displayId: 'modalAddressDisplay',
        formId: 'modalAddressForm',
        inputId: 'modalAddressInput',
        errorId: 'modalAddressError',
        editBtnId: 'editAddressBtn',
        cancelBtnId: 'cancelAddressEdit',
        fieldName: 'address',
        validateFn: (val) => validateRequired(val, 'آدرس')||validateWordConut,
        successMessage: 'آدرس با موفقیت ذخیره شد'
    });
}

function initializeFamilyFields() {


    // Siblings count
    createFieldEditor({
        displayId: 'modalSiblingsCountDisplay',
        formId: 'modalSiblingsCountForm',
        inputId: 'modalSiblingsCountInput',
        errorId: 'modalSiblingsCountError',
        editBtnId: 'editSiblingsCountBtn',
        cancelBtnId: 'cancelSiblingsCountEdit',
        fieldName: 'siblings_count',
        validateFn: (val) => validateNumber(val, 0, 20),
        successMessage: 'تعداد خواهر و برادر با موفقیت ذخیره شد'
    });

    // Siblings rank
    const rankDisplay = document.getElementById('modalSiblingsRankDisplay');
    const rankForm = document.getElementById('modalSiblingsRankForm');
    const rankInput = document.getElementById('modalSiblingsRankInput');
    const rankError = document.getElementById('modalSiblingsRankError');
    const editRankBtn = document.getElementById('editSiblingsRankBtn');
    const cancelRankEdit = document.getElementById('cancelSiblingsRankEdit');

    if (rankDisplay && rankForm && rankInput && rankError && editRankBtn && cancelRankEdit) {
        editRankBtn.addEventListener('click', function() {
            const currentVal = rankDisplay.textContent.match(/(\d+)/)?.[1] || '1';
            rankInput.value = currentVal;

            rankDisplay.classList.add('hidden');
            editRankBtn.classList.add('hidden');
            rankForm.classList.remove('hidden');
            rankForm.classList.add('flex');
            rankError.classList.add('hidden');
        });

        cancelRankEdit.addEventListener('click', function() {
            rankForm.classList.add('hidden');
            rankForm.classList.remove('flex');
            rankDisplay.classList.remove('hidden');
            editRankBtn.classList.remove('hidden');
            rankError.classList.add('hidden');
        });

        rankForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const newVal = rankInput.value.trim();
            const numVal = parseInt(newVal);

            if (isNaN(numVal) || numVal < 1 || numVal > 20) {
                rankError.textContent = 'فرزند باید بین ۱ تا ۲۰ باشد';
                rankError.classList.remove('hidden');
                return;
            }

            const displayText = `فرزند ${newVal}ام`;
            const loadingEl = showLoadingIndicator('در حال ذخیره...');

            updateRequestField('siblings_rank', newVal)
                .then(response => {
                    if (response.success) {
                        rankDisplay.textContent = displayText;
                        rankForm.classList.add('hidden');
                        rankForm.classList.remove('flex');
                        rankDisplay.classList.remove('hidden');
                        editRankBtn.classList.remove('hidden');
                        showSuccessMessage('فرزند چندم با موفقیت ذخیره شد');

                        setTimeout(refreshRequestData, 500);
                    } else {
                        throw new Error(response.message || 'خطا در ذخیره اطلاعات');
                    }
                })
                .catch(error => {
                    console.error('❌ Error updating siblings rank:', error);
                    rankError.textContent = 'خطا در ذخیره اطلاعات';
                    rankError.classList.remove('hidden');
                })
                .finally(() => {
                    hideLoadingIndicator(loadingEl);
                });
        });
    }

    // Know
    createFieldEditor({
        displayId: 'modalKnowDisplay',
        formId: 'modalKnowForm',
        inputId: 'modalKnowInput',
        errorId: 'modalKnowError',
        editBtnId: 'editKnowBtn',
        cancelBtnId: 'cancelKnowEdit',
        fieldName: 'know',
        validateFn: (val) => validateRequired(val, 'نحوه آشنایی'),
        successMessage: 'نحوه آشنایی با موفقیت ذخیره شد'
    });

    // Counseling method
    createFieldEditor({
        displayId: 'modalCounselingMethodDisplay',
        formId: 'modalCounselingMethodForm',
        inputId: 'modalCounselingMethodInput',
        errorId: 'modalCounselingMethodError',
        editBtnId: 'editCounselingMethodBtn',
        cancelBtnId: 'cancelCounselingMethodEdit',
        fieldName: 'counseling_method',
        validateFn: (val) => validateRequired(val, 'روش مشاوره'),
        successMessage: 'روش مشاوره با موفقیت ذخیره شد'
    });
}

function initializeParentFields() {


    const parentFields = [
        { prefix: 'Father', field: 'father_name', label: 'نام پدر' },
        { prefix: 'Father', field: 'father_phone', label: 'شماره موبایل پدر' },
        { prefix: 'Father', field: 'father_job', label: 'شغل پدر' },
        { prefix: 'Father', field: 'father_income', label: 'درآمد پدر' },
        { prefix: 'Father', field: 'father_job_address', label: 'آدرس محل کار پدر' },
        { prefix: 'Mother', field: 'mother_name', label: 'نام مادر' },
        { prefix: 'Mother', field: 'mother_phone', label: 'شماره موبایل مادر' },
        { prefix: 'Mother', field: 'mother_job', label: 'شغل مادر' },
        { prefix: 'Mother', field: 'mother_income', label: 'درآمد مادر' },
        { prefix: 'Mother', field: 'mother_job_address', label: 'آدرس محل کار مادر' }
    ];

    parentFields.forEach(({ prefix, field, label }) => {
        const config = {
            displayId: `modal${field.replace(/(^|_)(.)/g, (match, sep, letter) => letter.toUpperCase())}`,
            formId: `modal${field.replace(/(^|_)(.)/g, (match, sep, letter) => letter.toUpperCase())}Form`,
            inputId: `modal${field.replace(/(^|_)(.)/g, (match, sep, letter) => letter.toUpperCase())}Input`,
            errorId: `modal${field.replace(/(^|_)(.)/g, (match, sep, letter) => letter.toUpperCase())}Error`,
            editBtnId: `edit${field.replace(/(^|_)(.)/g, (match, sep, letter) => letter.toUpperCase())}Btn`,
            cancelBtnId: `cancel${field.replace(/(^|_)(.)/g, (match, sep, letter) => letter.toUpperCase())}Edit`,
            fieldName: field,
            successMessage: `${label} با موفقیت ذخیره شد`
        };

        // Special validation for phone fields
        if (field.includes('phone')) {

            config.validateFn = (val)=> validatePhone(val)||validateRequired(val, label);
        } else if (field.includes('income')) {
            config.validateFn = (val) => val ? validateNumber(val, 0, 1000000000) : '';
        } else {
            config.validateFn = (val) => validateRequired(val, label);
        }

        createFieldEditor(config);
    });
}

function initializeFinalQuestionsFields() {

// Motivation
createFieldEditor({
    displayId: 'modalMotivation',
    formId: 'modalMotivationForm',
    inputId: 'modalMotivationInput',
    errorId: 'modalMotivationError',
    editBtnId: 'editMotivationBtn',
    cancelBtnId: 'cancelMotivationEdit',
    fieldName: 'motivation',
    validateFn: (val) => validateRequired(val, 'انگیزه'),
    successMessage: 'انگیزه با موفقیت ذخیره شد'
});

// Spend
createFieldEditor({
    displayId: 'modalSpend',
    formId: 'modalSpendForm',
    inputId: 'modalSpendInput',
    errorId: 'modalSpendError',
    editBtnId: 'editSpendBtn',
    cancelBtnId: 'cancelSpendEdit',
    fieldName: 'spend',
    validateFn: (val) => validateRequired(val, 'نحوه گذران اوقات فراغت'),
    successMessage: 'نحوه گذران اوقات فراغت با موفقیت ذخیره شد'
});

// How Am I
createFieldEditor({
    displayId: 'modalHowAmI',
    formId: 'modalHowAmIForm',
    inputId: 'modalHowAmIInput',
    errorId: 'modalHowAmIError',
    editBtnId: 'editHowAmIBtn',
    cancelBtnId: 'cancelHowAmIEdit',
    fieldName: 'how_am_i',
    validateFn: (val) => validateRequired(val, 'نحوه توصیف خود'),
    successMessage: 'نحوه توصیف خود با موفقیت ذخیره شد'
});

// Future
createFieldEditor({
    displayId: 'modalFuture',
    formId: 'modalFutureForm',
    inputId: 'modalFutureInput',
    errorId: 'modalFutureError',
    editBtnId: 'editFutureBtn',
    cancelBtnId: 'cancelFutureEdit',
    fieldName: 'future',
    validateFn: (val) => validateRequired(val, 'برنامه آینده'),
    successMessage: 'برنامه آینده با موفقیت ذخیره شد'
});

// Favorite Major
createFieldEditor({
    displayId: 'modalFavoriteMajor',
    formId: 'modalFavoriteMajorForm',
    inputId: 'modalFavoriteMajorInput',
    errorId: 'modalFavoriteMajorError',
    editBtnId: 'editFavoriteMajorBtn',
    cancelBtnId: 'cancelFavoriteMajorEdit',
    fieldName: 'favorite_major',
    validateFn: (val) => validateRequired(val, 'رشته مورد علاقه'),
    successMessage: 'رشته مورد علاقه با موفقیت ذخیره شد'
});

// Help Others
createFieldEditor({
    displayId: 'modalHelpOthers',
    formId: 'modalHelpOthersForm',
    inputId: 'modalHelpOthersInput',
    errorId: 'modalHelpOthersError',
    editBtnId: 'editHelpOthersBtn',
    cancelBtnId: 'cancelHelpOthersEdit',
    fieldName: 'help_others',
    validateFn: (val) => validateRequired(val, 'کمک به دیگران'),
    successMessage: 'کمک به دیگران با موفقیت ذخیره شد'
});

// Suggestion
createFieldEditor({
    displayId: 'modalSuggestion',
    formId: 'modalSuggestionForm',
    inputId: 'modalSuggestionInput',
    errorId: 'modalSuggestionError',
    editBtnId: 'editSuggestionBtn',
    cancelBtnId: 'cancelSuggestionEdit',
    fieldName: 'suggestion',
    validateFn: (val) => validateRequired(val, 'پیشنهاد'),
    successMessage: 'پیشنهاد با موفقیت ذخیره شد'
});
}

function initializeEnglishLevelEdit() {


    const display = document.getElementById('modalEnglishDisplay');
    const editDiv = document.getElementById('modalEnglishEdit');
    const slider = document.getElementById('modalEnglishSlider');
    const sliderValue = document.getElementById('modalEnglishSliderValue');
    const bar = document.getElementById('modalEnglishBar');
    const percent = document.getElementById('modalEnglishPercent');
    const editBtn = document.getElementById('editEnglishBtn');
    const saveBtn = document.getElementById('saveEnglishBtn');
    const cancelBtn = document.getElementById('cancelEnglishBtn');

    if (!display || !editDiv || !slider || !sliderValue || !bar || !percent || !editBtn || !saveBtn || !cancelBtn) {

        return;
    }

    // Edit button click
    editBtn.addEventListener('click', function() {

        const currentVal = parseInt(percent.textContent.replace('%', ''));
        slider.value = currentVal;
        sliderValue.textContent = currentVal + '%';
        slider.style.background = `linear-gradient(270deg, #8b5cf6 0%, #7c3aed ${currentVal}%, #e5e7eb ${currentVal}%)`;

        display.classList.add('hidden');
        editBtn.classList.add('hidden');
        editDiv.classList.remove('hidden');
        editDiv.classList.add('flex');
        saveBtn.classList.remove('hidden');
        cancelBtn.classList.remove('hidden');

        // Add to editing fields
        editingFields.add('english_proficiency');
        updateEditingPopup();

        slider.focus();
    });

    // Slider input change
    slider.addEventListener('input', function() {
        const val = slider.value;
        sliderValue.textContent = val + '%';
        slider.style.background = `linear-gradient(270deg, #8b5cf6 0%, #7c3aed ${val}%, #e5e7eb ${val}%)`;
    });

    // Save button click
    saveBtn.addEventListener('click', function() {


        // Prevent multiple clicks
        if (saveBtn.disabled) return;
        saveBtn.disabled = true;

        const newVal = parseInt(slider.value);

        if (isNaN(newVal) || newVal < 0 || newVal > 100) {
            showErrorMessage('سطح زبان باید بین 0 تا 100 باشد');
            saveBtn.disabled = false;
            return;
        }

        const loadingEl = showLoadingIndicator('در حال ذخیره...');

        updateRequestField('english_proficiency', newVal)
            .then(response => {
                if (response.success) {
                    bar.style.width = newVal + '%';
                    percent.textContent = newVal + '%';

                    editDiv.classList.add('hidden');
                    editDiv.classList.remove('flex');
                    display.classList.remove('hidden');
                    editBtn.classList.remove('hidden');
                    saveBtn.classList.add('hidden');
                    cancelBtn.classList.add('hidden');

                    // Remove from editing fields
                    editingFields.delete('english_proficiency');
                    updateEditingPopup();

                    showSuccessMessage('سطح زبان انگلیسی با موفقیت ذخیره شد');

                    setTimeout(refreshRequestData, 500);
                } else {
                    throw new Error(response.message || 'خطا در ذخیره اطلاعات');
                }
            })
            .catch(error => {
                console.error('❌ Error updating English level:', error);
                showErrorMessage('خطا در ذخیره اطلاعات');
            })
            .finally(() => {
                hideLoadingIndicator(loadingEl);
                saveBtn.disabled = false;
            });
    });

    // Cancel button click
    cancelBtn.addEventListener('click', function() {

        editDiv.classList.add('hidden');
        editDiv.classList.remove('flex');
        display.classList.remove('hidden');
        editBtn.classList.remove('hidden');
        saveBtn.classList.add('hidden');
        cancelBtn.classList.add('hidden');

        // Remove from editing fields
        editingFields.delete('english_proficiency');
        updateEditingPopup();

        // Re-enable save button
        saveBtn.disabled = false;
    });
}

// Update progress bar color
// function updateProgressBarColor(progressBar, percentage) {
//     if (!progressBar) return;

//     progressBar.classList.remove('english-low', 'english-medium', 'english-high');

//     if (percentage <= 30) {
//         progressBar.classList.add('english-low');
//         progressBar.style.background = 'linear-gradient(270deg, #ef4444 0%, #dc2626 100%)';
//     } else if (percentage <= 70) {
//         progressBar.classList.add('english-medium');
//         progressBar.style.background = 'linear-gradient(270deg, #f59e0b 0%, #d97706 100%)';
//     } else {
//         progressBar.classList.add('english-high');
//         progressBar.style.background = 'linear-gradient(270deg, #10b981 0%, #059669 100%)';
//     }
// }

// Main initializer
document.addEventListener('DOMContentLoaded', function() {


    // Schedule init functions with delays to prevent conflicts
    setTimeout(initializeNationalCodeEdit, 100);
    setTimeout(initializeBasicFields, 200);
    setTimeout(initializeEducationFields, 300);
    setTimeout(initializeHousingFields, 400);
    setTimeout(initializeFamilyFields, 500);
    setTimeout(initializeParentFields, 600);
    setTimeout(initializeFinalQuestionsFields, 700);
    setTimeout(initializeEnglishLevelEdit, 800);
    setTimeout(initializeProfileImageUpload, 900);
    setTimeout(initializeGradeSheetUpload, 1000);

});
