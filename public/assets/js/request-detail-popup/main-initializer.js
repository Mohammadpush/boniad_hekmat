// اینیشالایزر اصلی - مدیریت شروع همه فیلدها
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 DOM loaded, initializing all editors...');

    // اجرای تابع‌های init با تأخیر کم برای جلوگیری از تداخل
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
    console.log('⏰ All initialization functions scheduled');
});
