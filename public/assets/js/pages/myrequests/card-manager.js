// اسکریپت مخصوص صفحه درخواست‌های من
document.addEventListener('DOMContentLoaded', function () {
    // مدیریت کارت شماره بانکی
    initCardNumberInput();

    // مدیریت پاپ‌آپ
    initPopupHandlers();
});
function opencardnumberpopup(requestData) {
    const popup = document.getElementById('popup');
    const inputid = document.getElementById('cardnumberpopupid')
    if (popup) {
        popup.style.display = 'Block';
        popup.classList.remove('hidden');

        inputid.value = requestData.id;
        console.log('function clicked', requestData);  // حالا id رو هم لاگ می‌کنه

        // ریست کردن فرم و تنظیم فوکوس
        if (typeof window.resetCardForm === 'function') {
            window.resetCardForm();
        }

        // تأخیر برای فوکوس صحیح
        setTimeout(() => {
            const hiddenInput = document.getElementById('cardNumberInput');
            if (hiddenInput) {
                hiddenInput.focus();
            }
        }, 100);

    } else {
        console.error('Popup element not found!');
    }
    if (requestData.cardnumber) {
        const title = document.getElementById('card-title');
        title.innerText = 'ویرایش شماره کارت';
        requestData.cardnumber = requestData.cardnumber.toString().split('');
    }
}
function initCardNumberInput() {
    const digits = document.querySelectorAll('.card-digit');
    const hiddenInput = document.getElementById('cardNumberInput');
    const finalInput = document.getElementById('cardNumberFinal');
    const popup = document.getElementById('popup');

    if (!digits.length || !hiddenInput || !finalInput || !popup) return;

    let currentIndex = 0;
    let cardNumber = ['0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'];

    // فوکس روی مستطیل فعلی
    function focusCurrentDigit() {
        digits.forEach((digit, index) => {
            if (index === currentIndex) {
                digit.classList.add('border-blue-500', 'bg-blue-50', 'ring-2', 'ring-blue-200');
                digit.classList.remove('border-gray-300', 'bg-gray-50');
            } else {
                digit.classList.remove('border-blue-500', 'bg-blue-50', 'ring-2', 'ring-blue-200');
                if (digit.textContent !== 'X') {
                    digit.classList.add('border-green-500', 'bg-green-50');
                    digit.classList.remove('border-gray-300', 'bg-gray-50');
                } else {
                    digit.classList.add('border-gray-300', 'bg-gray-50');
                    digit.classList.remove('border-green-500', 'bg-green-50');
                }
            }
        });
    }

    // بررسی تکمیل همه ارقام
    function checkCompletion() {
        const isComplete = cardNumber.every(digit => digit !== '0');
        if (isComplete) {
            digits.forEach(digit => {
                digit.classList.remove('border-gray-300', 'bg-gray-50', 'border-blue-500', 'bg-blue-50');
                digit.classList.add('border-green-500', 'bg-green-100', 'animate-pulse');
            });

            // ارسال شماره کارت نهایی
            finalInput.value = cardNumber.join('');

            // انیمیشن موفقیت
            setTimeout(() => {
                digits.forEach(digit => {
                    digit.classList.remove('animate-pulse');
                });
            }, 1000);
        } else {
            finalInput.value = '';
        }
    }



    // مدیریت کلیک روی مستطیل‌ها
    digits.forEach((digit, index) => {
        digit.addEventListener('click', function () {
            currentIndex = index;
            focusCurrentDigit();
            hiddenInput.focus();
        });
    });

    // مدیریت ورودی کیبورد
    hiddenInput.addEventListener('input', function (e) {
        const value = e.target.value.replace(/\D/g, '');

        if (value.length > -1) {
            const lastDigit = value[value.length - 1];

            // تنظیم رقم در موقعیت فعلی
            cardNumber[currentIndex] = lastDigit;
            digits[currentIndex].textContent = lastDigit;

            // انتقال به مستطیل بعدی
            if (currentIndex < 15) {
                currentIndex++;
                focusCurrentDigit();
            }

            checkCompletion();
        }

        e.target.value = '';
    });

    // مدیریت کلیدهای ویژه
    hiddenInput.addEventListener('keydown', function (e) {
        if (e.key === 'Backspace') {
            e.preventDefault();

            if (cardNumber[currentIndex] == 'X') {
                cardNumber[currentIndex] = '0';
                digits[currentIndex].textContent = 'X';
            } else {
                currentIndex--;
                cardNumber[currentIndex] = '0';
                digits[currentIndex].textContent = 'X';
            }

            focusCurrentDigit();
            checkCompletion();
        }

        if (e.key === 'ArrowLeft' && currentIndex > 0) {
            e.preventDefault();
            currentIndex--;
            focusCurrentDigit();
        }

        if (e.key === 'ArrowRight' && currentIndex < 15) {
            e.preventDefault();
            currentIndex++;
            focusCurrentDigit();
        }
    });

    // مدیریت باز شدن پاپ‌آپ
    const openButtons = document.querySelectorAll('#openpopup');
    openButtons.forEach(button => {
        button.addEventListener('click', function () {
            popup.classList.remove('hidden');
            popup.style.display = 'block';
            resetForm();
        });
    });

    // کلیک روی کل منطقه برای فوکس
    const cardContainer = document.querySelector('[dir="ltr"]');
    if (cardContainer) {
        cardContainer.addEventListener('click', function () {
            hiddenInput.focus();
        });
    }

    // ریست کردن فرم - انتقال داخل initCardNumberInput
    function resetForm() {
        currentIndex = 0;
        cardNumber = ['0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'];
        digits.forEach((digit, index) => {
            digit.textContent = 'X';
            digit.classList.remove('border-blue-500', 'bg-blue-50', 'ring-2', 'ring-blue-200',
                'border-green-500', 'bg-green-50', 'bg-green-100', 'animate-pulse');
            digit.classList.add('border-gray-300', 'bg-gray-50');
        });
        finalInput.value = '';

        // تأخیر کوتاه برای اطمینان از اینکه DOM آپدیت شده باشد
        setTimeout(() => {
            focusCurrentDigit();
            hiddenInput.focus();
        }, 10);
    }

    // اضافه کردن resetForm به window تا سایر قسمت‌ها بتوانند از آن استفاده کنند
    window.resetCardForm = resetForm;
}

function initPopupHandlers() {
    const popup = document.getElementById('popup');
    const closeButton = document.getElementById('closepopup');

    if (!popup) return;

    // مدیریت بستن پاپ‌آپ
    if (closeButton) {
        closeButton.addEventListener('click', function () {
            popup.classList.add('hidden');
            popup.style.display = 'none';
            if (typeof window.resetCardForm === 'function') {
                window.resetCardForm();
            }
        });
    }

    // بستن پاپ‌آپ با کلیک روی پس‌زمینه
    popup.addEventListener('click', function (e) {
        if (e.target === popup) {
            popup.classList.add('hidden');
            popup.style.display = 'none';
            if (typeof window.resetCardForm === 'function') {
                window.resetCardForm();
            }
        }
    });

    // بستن پاپ‌آپ با کلید Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !popup.classList.contains('hidden')) {
            popup.classList.add('hidden');
            popup.style.display = 'none';
            if (typeof window.resetCardForm === 'function') {
                window.resetCardForm();
            }
        }
    });
}
