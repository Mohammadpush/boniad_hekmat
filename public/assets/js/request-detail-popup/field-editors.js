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

// تابع برای ایجاد ویرایشگر رتبه فرزندی با آیکون‌های interactive
function createSiblingsRankEditor(config) {
    console.log(`👶 Creating siblings rank editor for: ${config.fieldName}`);

    const display = document.getElementById(config.displayId);
    const form = document.getElementById(config.formId);
    const input = document.getElementById(config.inputId);
    const error = document.getElementById(config.errorId);
    const editBtn = document.getElementById(config.editBtnId);
    const cancelBtn = document.getElementById(config.cancelBtnId);
    const iconsContainer = document.getElementById(config.iconsContainerId);

    // بررسی وجود تمام المان‌ها
    if (!display || !form || !input || !error || !editBtn || !cancelBtn || !iconsContainer) {
        console.log(`❌ Missing elements for ${config.fieldName}`);
        return;
    }

    // تابع برای ایجاد آیکون‌های رتبه
    function createRankIcons(selectedRank = 1) {
        iconsContainer.innerHTML = '';
        for (let i = 1; i <= 10; i++) {
            const icon = document.createElement('div');
            icon.className = `w-8 h-8 rounded-full border-2 cursor-pointer transition-all flex items-center justify-center text-sm font-bold ${
                i <= selectedRank
                    ? 'bg-blue-500 border-blue-500 text-white'
                    : 'border-gray-300 text-gray-400 hover:border-blue-300'
            }`;
            icon.textContent = i;
            icon.dataset.rank = i;

            icon.addEventListener('click', function() {
                const rank = parseInt(this.dataset.rank);
                input.value = rank;
                createRankIcons(rank);
            });

            iconsContainer.appendChild(icon);
        }
    }

    // نمایش فرم ویرایش
    editBtn.addEventListener('click', function() {
        const currentText = display.textContent.trim();
        const currentRank = currentText.includes('فرزند') ?
            parseInt(currentText.match(/فرزند (\d+)ام/)[1]) : 1;

        input.value = currentRank;
        createRankIcons(currentRank);

        display.classList.add('hidden');
        editBtn.classList.add('hidden');
        form.classList.remove('hidden');
        form.classList.add('flex');
        error.classList.add('hidden');
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

        // ارسال به سرور
        updateRequestField(config.fieldName, newVal)
        .then(data => {
            if (data.success) {
                const rank = parseInt(newVal);
                display.textContent = `فرزند ${rank}ام`;

                form.classList.add('hidden');
                form.classList.remove('flex');
                display.classList.remove('hidden');
                editBtn.classList.remove('hidden');
                error.classList.add('hidden');

                // نمایش پیام موفقیت
                console.log(`✅ ${config.successMessage}`);
                if (typeof showSuccessMessage === 'function') {
                    showSuccessMessage(config.successMessage);
                }

                // بروزرسانی داده‌ها
                if (typeof refreshRequestData === 'function') {
                    refreshRequestData();
                }
            } else {
                error.textContent = data.message || 'خطا در ذخیره اطلاعات';
                error.classList.remove('hidden');
            }
        })
        .catch(err => {
            console.error(`❌ Siblings rank error for ${config.fieldName}:`, err);
            error.textContent = 'خطا در ارتباط با سرور';
            error.classList.remove('hidden');
        });
    });
}
