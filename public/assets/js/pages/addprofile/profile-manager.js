// اسکریپت مخصوص صفحه افزودن/ویرایش پروفایل
document.addEventListener('DOMContentLoaded', function() {
    // Initialize numeric inputs
    initNumericInputs();

    // Initialize image preview
    initImagePreview();

    // Initialize form validation
    initFormValidation();
});

function initNumericInputs() {
    const numInputs = document.querySelectorAll('.numinput');

    numInputs.forEach(input => {
        // Handle paste events
        input.addEventListener('paste', function(e) {
            const pastedData = e.clipboardData.getData('text');
            if (!/^\d+$/.test(pastedData)) {
                e.preventDefault();
            }
        });

        // Handle input events
        input.addEventListener('input', function(e) {
            const value = e.target.value;
            if (!/^\d*$/.test(value)) {
                e.target.value = value.replace(/\D/g, '');
            }
        });

        // Handle keydown events
        input.addEventListener('keydown', function(e) {
            // Allow: backspace, delete, tab, escape, enter, home, end, left, right, up, down
            if ([46, 8, 9, 27, 13, 35, 36, 37, 38, 39, 40].indexOf(e.keyCode) !== -1 ||
                // Allow Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                (e.keyCode === 65 && e.ctrlKey === true) ||
                (e.keyCode === 67 && e.ctrlKey === true) ||
                (e.keyCode === 86 && e.ctrlKey === true) ||
                (e.keyCode === 88 && e.ctrlKey === true)) {
                return;
            }

            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    });
}

function initImagePreview() {
    const imageInput = document.getElementById('imgpath');
    if (!imageInput) return;

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('لطفاً فقط فایل تصویری انتخاب کنید.');
                e.target.value = '';
                return;
            }

            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('حجم فایل نباید بیشتر از 2 مگابایت باشد.');
                e.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.querySelector('.profile-image-container img');
                const placeholder = document.querySelector('.profile-image-container > div');

                if (preview) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    if (placeholder) placeholder.style.display = 'none';
                } else if (placeholder) {
                    // Create new img element if doesn't exist
                    const newImg = document.createElement('img');
                    newImg.src = e.target.result;
                    newImg.alt = 'تصویر پروفایل';
                    newImg.className = 'w-20 h-20 rounded-full object-cover border-4 border-gray-100';
                    placeholder.parentNode.insertBefore(newImg, placeholder);
                    placeholder.style.display = 'none';
                }
            };
            reader.readAsDataURL(file);
        }
    });
}

function initFormValidation() {
    const form = document.querySelector('form');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('border-red-500');
                field.classList.remove('border-gray-300');

                // Add error message if not exists
                let errorMsg = field.parentNode.querySelector('.error-message');
                if (!errorMsg) {
                    errorMsg = document.createElement('p');
                    errorMsg.className = 'error-message mt-1 text-sm text-red-600';
                    errorMsg.textContent = 'این فیلد الزامی است.';
                    field.parentNode.appendChild(errorMsg);
                }
            } else {
                field.classList.remove('border-red-500');
                field.classList.add('border-gray-300');

                // Remove error message if exists
                const errorMsg = field.parentNode.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.remove();
                }
            }
        });

        // Validate national code
        const nationalCodeField = document.getElementById('nationalcode');
        if (nationalCodeField && nationalCodeField.value) {
            if (!validateNationalCode(nationalCodeField.value)) {
                isValid = false;
                nationalCodeField.classList.add('border-red-500');

                let errorMsg = nationalCodeField.parentNode.querySelector('.nc-error-message');
                if (!errorMsg) {
                    errorMsg = document.createElement('p');
                    errorMsg.className = 'nc-error-message mt-1 text-sm text-red-600';
                    errorMsg.textContent = 'کد ملی وارد شده معتبر نیست.';
                    nationalCodeField.parentNode.appendChild(errorMsg);
                }
            } else {
                nationalCodeField.classList.remove('border-red-500');
                const errorMsg = nationalCodeField.parentNode.querySelector('.nc-error-message');
                if (errorMsg) {
                    errorMsg.remove();
                }
            }
        }

        if (!isValid) {
            e.preventDefault();
        }
    });
}

function validateNationalCode(code) {
    if (!/^\d{10}$/.test(code)) return false;

    const check = parseInt(code[9]);
    let sum = 0;

    for (let i = 0; i < 9; i++) {
        sum += parseInt(code[i]) * (10 - i);
    }

    const remainder = sum % 11;
    return remainder < 2 ? check === remainder : check === 11 - remainder;
}
