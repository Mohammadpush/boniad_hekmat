// تابع عمومی برای ایجاد ویرایشگر فیلد
function createFieldEditor(config) {
    console.log(`📝 Creating field editor for: ${config.fieldName}`);

    const display = document.getElementById(config.displayId);
    const form = document.getElementById(config.formId);
    const input = document.getElementById(config.inputId);
    const error = document.getElementById(config.errorId);
    const editBtn = document.getElementById(config.editBtnId);
    const cancelBtn = document.getElementById(config.cancelBtnId);

    // بررسی وجود تمام المان‌ها
    if (!display || !form || !input || !error || !editBtn || !cancelBtn) {
        console.log(`❌ Missing elements for ${config.fieldName}`);
        return;
    }

    // نمایش فرم ویرایش
    editBtn.addEventListener('click', function() {
        const currentVal = display.textContent.trim();
        input.value = currentVal === 'ندارد' ? '' : currentVal;

        display.classList.add('hidden');
        editBtn.classList.add('hidden');
        form.classList.remove('hidden');
        form.classList.add('flex');
        error.classList.add('hidden');

        input.focus();
        input.select();
    });

    // لغو ویرایش
    cancelBtn.addEventListener('click', function() {
        form.classList.add('hidden');
        form.classList.remove('flex');
        display.classList.remove('hidden');
        editBtn.classList.remove('hidden');
        error.classList.add('hidden');
    });

    // ذخیره تغییرات
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const newVal = input.value.trim();
        const errMsg = config.validateFn(newVal);

        if (errMsg) {
            error.textContent = errMsg;
            error.classList.remove('hidden');
            return;
        }

        // بررسی تغییر
        const currentDisplayVal = display.textContent.trim();
        if (newVal === currentDisplayVal || (newVal === '' && currentDisplayVal === 'ندارد')) {
            // مقدار تغییر نکرده
            form.classList.add('hidden');
            form.classList.remove('flex');
            display.classList.remove('hidden');
            editBtn.classList.remove('hidden');
            return;
        }

        // ارسال AJAX
        updateRequestField(config.fieldName, newVal)
        .then(data => {
            if (data.success) {
                form.classList.add('hidden');
                form.classList.remove('flex');
                display.classList.remove('hidden');
                editBtn.classList.remove('hidden');
                error.classList.add('hidden');

                showSuccessMessage(config.successMessage);
                refreshRequestData();
            } else {
                error.textContent = data.message || 'خطا در ذخیره اطلاعات';
                error.classList.remove('hidden');
            }
        })
        .catch(err => {
            console.error(`❌ Error for ${config.fieldName}:`, err);
            error.textContent = 'خطا در ارتباط با سرور';
            error.classList.remove('hidden');
        });
    });
}

// تابع عمومی برای select ها
function createSelectEditor(config) {
    console.log(`📋 Creating select editor for: ${config.fieldName}`);

    const display = document.getElementById(config.displayId);
    const form = document.getElementById(config.formId);
    const input = document.getElementById(config.inputId);
    const error = document.getElementById(config.errorId);
    const editBtn = document.getElementById(config.editBtnId);
    const cancelBtn = document.getElementById(config.cancelBtnId);

    if (!display || !form || !input || !error || !editBtn || !cancelBtn) {
        console.log(`❌ Missing elements for select ${config.fieldName}`);
        return;
    }

    editBtn.addEventListener('click', function() {
        const currentVal = display.textContent.trim();
        if (config.fieldName === 'rental') {
            input.value = currentVal === 'ملکی' ? '0' : '1';
        } else {
            input.value = currentVal;
        }

        display.classList.add('hidden');
        editBtn.classList.add('hidden');
        form.classList.remove('hidden');
        form.classList.add('flex');
        error.classList.add('hidden');
        input.focus();
    });

    cancelBtn.addEventListener('click', function() {
        form.classList.add('hidden');
        form.classList.remove('flex');
        display.classList.remove('hidden');
        editBtn.classList.remove('hidden');
        error.classList.add('hidden');
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const newVal = input.value.trim();
        const errMsg = config.validateFn(newVal);

        if (errMsg) {
            error.textContent = errMsg;
            error.classList.remove('hidden');
            return;
        }

        updateRequestField(config.fieldName, newVal)
        .then(data => {
            if (data.success) {
                form.classList.add('hidden');
                form.classList.remove('flex');
                display.classList.remove('hidden');
                editBtn.classList.remove('hidden');
                error.classList.add('hidden');

                if (config.transformDisplay) {
                    display.textContent = config.transformDisplay(newVal);
                } else {
                    display.textContent = newVal;
                }

                showSuccessMessage(config.successMessage);
                setTimeout(() => refreshRequestData(), 500);
            } else {
                error.textContent = data.message || 'خطا در ذخیره اطلاعات';
                error.classList.remove('hidden');
            }
        })
        .catch(err => {
            console.error(`❌ Select error for ${config.fieldName}:`, err);
            error.textContent = 'خطا در ارتباط با سرور';
            error.classList.remove('hidden');
        });
    });
}

// تابع عمومی برای textarea ها
function createTextAreaEditor(config) {
    console.log(`📝 Creating textarea editor for: ${config.fieldName}`);

    const display = document.getElementById(config.displayId);
    const form = document.getElementById(config.formId);
    const input = document.getElementById(config.inputId);
    const error = document.getElementById(config.errorId);
    const editBtn = document.getElementById(config.editBtnId);
    const cancelBtn = document.getElementById(config.cancelBtnId);

    if (!display || !form || !input || !error || !editBtn || !cancelBtn) {
        console.log(`❌ Missing elements for textarea ${config.fieldName}`);
        return;
    }

    editBtn.addEventListener('click', function() {
        const currentVal = display.textContent.trim();
        input.value = currentVal;
        display.classList.add('hidden');
        editBtn.classList.add('hidden');
        form.classList.remove('hidden');
        form.classList.add('flex');
        error.classList.add('hidden');
        input.focus();
    });

    cancelBtn.addEventListener('click', function() {
        form.classList.add('hidden');
        form.classList.remove('flex');
        display.classList.remove('hidden');
        editBtn.classList.remove('hidden');
        error.classList.add('hidden');
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const newVal = input.value.trim();
        const errMsg = config.validateFn(newVal);

        if (errMsg) {
            error.textContent = errMsg;
            error.classList.remove('hidden');
            return;
        }

        updateRequestField(config.fieldName, newVal)
        .then(data => {
            if (data.success) {
                form.classList.add('hidden');
                form.classList.remove('flex');
                display.classList.remove('hidden');
                editBtn.classList.remove('hidden');
                error.classList.add('hidden');

                display.textContent = newVal;
                showSuccessMessage(config.successMessage);
                setTimeout(() => refreshRequestData(), 500);
            } else {
                error.textContent = data.message || 'خطا در ذخیره اطلاعات';
                error.classList.remove('hidden');
            }
        })
        .catch(err => {
            console.error(`❌ Textarea error for ${config.fieldName}:`, err);
            error.textContent = 'خطا در ارتباط با سرور';
            error.classList.remove('hidden');
        });
    });
}
