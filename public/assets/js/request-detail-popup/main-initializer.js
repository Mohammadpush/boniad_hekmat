// اینیشالایزر اصلی - مدیریت شروع همه فیلدها
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 DOM loaded, initializing all editors...');

    // اجرای تابع‌های init با تأخیر کم برای جلوگیری از تداخل
    setTimeout(initializeNationalCodeEdit, 100);
    setTimeout(initializeBasicFields, 200);
    setTimeout(initializeEducationFields, 300);
    setTimeout(initializeHousingFields, 400);
    // اضافه کردن این خط:
    setTimeout(initializeEnglishLevelEdit, 500);
    console.log('⏰ All initialization functions scheduled');
});
