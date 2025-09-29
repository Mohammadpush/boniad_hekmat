// اسکریپت مخصوص صفحه جزئیات درخواست
document.addEventListener('DOMContentLoaded', function() {
    // Initialize datepickers
    initDatePickers();

    // Initialize popup handlers
    initRequestDetailPopup();

    // Initialize print functionality
    initPrintHandler();
});

function initDatePickers() {
    // Initialize Jalali Datepicker
    if (typeof jalaliDatepicker !== 'undefined') {
        jalaliDatepicker.startWatch({
            selector: 'input[data-jdp]',
            persianDigits: true,
            date: true,                    // فعال کردن انتخاب تاریخ
            time: true,                    // فعال کردن انتخاب زمان
            hasSecond: true,               // فعال کردن انتخاب ثانیه
            minDate: 'today',              // حداقل تاریخ از امروز
            showTodayBtn: true,            // نمایش دکمه امروز
            showEmptyBtn: true,            // نمایش دکمه خالی
            showCloseBtn: true,            // نمایش دکمه بستن
            autoHide: true,                // بستن خودکار پس از انتخاب
            hideAfterChange: false         // عدم بستن خودکار برای تنظیم زمان
        });
    }
}

function initRequestDetailPopup() {
    const openPopup = document.getElementById('openpopup');
    const popup = document.getElementById('popup');
    const closePopup = document.getElementById('closepopup');

    if (!openPopup || !popup || !closePopup) return;

    openPopup.addEventListener('click', function() {
        popup.classList.remove('hidden');
        popup.classList.add('flex', 'flex-col');
    });

    closePopup.addEventListener('click', function(e) {
        e.preventDefault();
        popup.classList.add('hidden');
        popup.classList.remove('flex', 'flex-col');
    });

    // Close popup when clicking outside
    popup.addEventListener('click', function(e) {
        if (e.target === popup) {
            popup.classList.add('hidden');
            popup.classList.remove('flex', 'flex-col');
        }
    });

    // Close popup with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !popup.classList.contains('hidden')) {
            popup.classList.add('hidden');
            popup.classList.remove('flex', 'flex-col');
        }
    });
}

function initPrintHandler() {
    // Add print button functionality if exists
    const printBtn = document.querySelector('[onclick*="print"]');
    if (printBtn) {
        printBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.print();
        });
    }
}
