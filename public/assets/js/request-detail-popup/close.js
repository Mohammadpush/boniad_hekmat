document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('requestDetailModal');
    const closeBtn = document.getElementById('closeRequestDetailModal');

    // تابع برای بستن مودال با انیمیشن
    function closeModal() {
        modal.classList.remove('show');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';

            // مخفی کردن کارنامه هنگام بستن مودال
            const gradeSheetDiv = document.getElementById('modalGradeSheet');
            if (gradeSheetDiv) {
                gradeSheetDiv.classList.add('hidden');
            }
        }, 300);
    }

    // بستن مودال با دکمه
    closeBtn.addEventListener('click', closeModal);

    // بستن مودال با کلیک روی پس‌زمینه
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    // بستن مودال با کلید اسکیپ
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
});
