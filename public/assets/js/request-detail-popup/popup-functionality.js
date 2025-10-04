// تابع برای باز کردن مودال و پر کردن اطلاعات
function openRequestDetailModal(requestData, cardElement = null) {
    const modal = document.getElementById('requestDetailModal');

    // ذخیره ID درخواست برای استفاده در ویرایش
    window.currentRequestId = requestData.id;

    // انیمیشن کارت اگر المان کارت ارسال شده باشد
    if (cardElement) {
        cardElement.classList.add('card-animate-to-center');
        setTimeout(() => {
            cardElement.classList.remove('card-animate-to-center');
        }, 600);
    }

    // پر کردن اطلاعات پروفایل
    document.getElementById('modalProfileImg').src = requestData.imgpath_url;
    document.getElementById('modalProfileImg').alt = requestData.name;
    document.getElementById('modalUserName').textContent = requestData.name;
    document.getElementById('modalUserGrade').textContent = 'پایه ' + requestData.grade;

    // تنظیم وضعیت
    const statusBadge = document.getElementById('modalStatusBadge');
    let statusColor = '';
    let statusText = '';

    switch(requestData.story) {
        case 'check':
            statusColor = 'bg-yellow-500';
            statusText = 'در انتظار';
            break;
        case 'accept':
            statusColor = 'bg-green-500';
            statusText = 'تایید شده';
            break;
        case 'reject':
            statusColor = 'bg-red-500';
            statusText = 'رد شده';
            break;
        case 'epointment':
            statusColor = 'bg-pink-600';
            statusText = 'قرار ملاقات';
            break;
        case 'submit':
            statusColor = 'bg-blue-500';
            statusText = 'ارسال شده';
            break;
        default:
            statusColor = 'bg-gray-500';
            statusText = 'نامشخص';
    }

    statusBadge.className = 'status-badge px-3 py-1 text-white text-xs font-bold rounded-full shadow-lg ' + statusColor;
    statusBadge.textContent = statusText;

    // پر کردن اطلاعات شخصی
    document.getElementById('modalNameDisplay').textContent = requestData.name || '';
    document.getElementById('modalNationalCodeDisplay').textContent = requestData.nationalcode || '';
    document.getElementById('modalBirthdateDisplay').textContent = requestData.birthdate || '';
    document.getElementById('modalPhoneDisplay').textContent = requestData.phone || '';
    document.getElementById('modalTelephoneDisplay').textContent = requestData.telephone || 'وارد نشده';

    // پر کردن اطلاعات تحصیلی
    document.getElementById('modalGradeDisplay').textContent = requestData.grade || '';
    document.getElementById('modalSchoolDisplay').textContent = requestData.school || '';
    document.getElementById('modalPrincipalDisplay').textContent = requestData.principal || '';
    document.getElementById('modalMajor').textContent = requestData.major_name || 'ندارد';
    document.getElementById('modalLastScoreDisplay').textContent = requestData.last_score || '';
    document.getElementById('modalSchoolTelephoneDisplay').textContent = requestData.school_telephone || 'وارد نشده';

    // تنظیم نوار پیشرفت زبان انگلیسی
    const englishPercent = requestData.english_proficiency || 0;
    document.getElementById('modalEnglishBar').style.width = englishPercent + '%';
    document.getElementById('modalEnglishPercent').textContent = englishPercent + '%';

    // نمایش کارنامه اگر وجود دارد
    if (requestData.gradesheetpath) {
        document.getElementById('modalGradeSheet').classList.remove('hidden');
        document.getElementById('modalGradeSheetImg').src = requestData.gradesheetpath_url;
        document.getElementById('modalGradeSheetLink').href = requestData.gradesheetpath_url;
    } else {
        document.getElementById('modalGradeSheet').classList.add('hidden');
    }

    // پر کردن اطلاعات مسکن
    document.getElementById('modalRentalDisplay').textContent = requestData.rental == '0' ? '🏠 ملکی' : '🏠 استیجاری';
    document.getElementById('modalAddressDisplay').textContent = requestData.address || '';

    // پر کردن اطلاعات خانوادگی
    document.getElementById('modalSiblingsCountDisplay').textContent = (requestData.siblings_count || '0') + ' نفر';
    document.getElementById('modalSiblingsRankDisplay').textContent = 'فرزند ' + (requestData.siblings_rank || '1') + 'ام';
    document.getElementById('modalKnowDisplay').textContent = requestData.know || '';
    document.getElementById('modalCounselingMethodDisplay').textContent = requestData.counseling_method || '';

    if (requestData.why_counseling_method) {
        document.getElementById('modalWhyCounselingMethodDiv').classList.remove('hidden');
        document.getElementById('modalWhyCounselingMethod').textContent = requestData.why_counseling_method;
    } else {
        document.getElementById('modalWhyCounselingMethodDiv').classList.add('hidden');
    }

    // پر کردن اطلاعات والدین
    document.getElementById('modalFatherName').textContent = requestData.father_name || '';
    document.getElementById('modalFatherPhone').textContent = requestData.father_phone || '';
    document.getElementById('modalFatherJob').textContent = requestData.father_job || '';
    document.getElementById('modalFatherIncome').textContent = requestData.father_income ? (parseInt(requestData.father_income).toLocaleString() + ' تومان') : '';
    document.getElementById('modalFatherJobAddress').textContent = requestData.father_job_address || '';

    document.getElementById('modalMotherName').textContent = requestData.mother_name || '';
    document.getElementById('modalMotherPhone').textContent = requestData.mother_phone || '';
    document.getElementById('modalMotherJob').textContent = requestData.mother_job || '';
    document.getElementById('modalMotherIncome').textContent = requestData.mother_income ? (parseInt(requestData.mother_income).toLocaleString() + ' تومان') : '';
    document.getElementById('modalMotherJobAddress').textContent = requestData.mother_job_address || '';

    // پر کردن سوالات نهایی
    document.getElementById('modalMotivation').textContent = requestData.motivation || '';
    document.getElementById('modalSpend').textContent = requestData.spend || '';
    document.getElementById('modalHowAmI').textContent = requestData.how_am_i || '';
    document.getElementById('modalFuture').textContent = requestData.future || '';
    document.getElementById('modalFavoriteMajor').textContent = requestData.favorite_major || '';
    document.getElementById('modalHelpOthers').textContent = requestData.help_others || '';

    if (requestData.suggestion) {
        document.getElementById('modalSuggestionDiv').classList.remove('hidden');
        document.getElementById('modalSuggestion').textContent = requestData.suggestion;
    } else {
        document.getElementById('modalSuggestionDiv').classList.add('hidden');
    }

    // نمایش کارنامه اگر وجود داشته باشد
    const gradeSheetDiv = document.getElementById('modalGradeSheet');
    if (requestData.gradesheetpath) {
        gradeSheetDiv.classList.remove('hidden');
        const gradeSheetImg = document.getElementById('modalGradeSheetImg');
        const gradeSheetLink = document.getElementById('modalGradeSheetLink');

        if (requestData.gradesheetpath.toLowerCase().endsWith('.pdf')) {
            gradeSheetImg.style.display = 'none';
            gradeSheetLink.textContent = 'مشاهده کارنامه (PDF)';
        } else {
            gradeSheetImg.src = requestData.gradesheetpath_url || `/private/${requestData.gradesheetpath}`;
            gradeSheetImg.style.display = 'block';
            gradeSheetLink.textContent = 'مشاهده کارنامه';
        }
        gradeSheetLink.href = requestData.gradesheetpath_url || `/private/${requestData.gradesheetpath}`;
    } else {
        gradeSheetDiv.classList.add('hidden');
    }

    // نمایش مودال با انیمیشن
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // جلوگیری از اسکرول صفحه

    // اضافه کردن کلاس انیمیشن بعد از نمایش
    setTimeout(() => {
        modal.classList.add('show');

        // راه‌اندازی مجدد initializerها بعد از نمایش modal با تأخیر بیشتر
        console.log('🔄 Re-initializing editors after modal show...');
        setTimeout(() => {
            initializeNationalCodeEdit();
            setTimeout(initializeBasicFields, 100);
            setTimeout(initializeEducationFields, 200);
            setTimeout(initializeHousingFields, 300);
            setTimeout(initializeFamilyFields, 400);
            setTimeout(initializeParentFields, 500);
            setTimeout(initializeFinalQuestionsFields, 600);
            setTimeout(initializeEnglishLevelEdit, 700);
            // آپلود handlers را در آخر اجرا کنیم تا المنت‌ها نمایش داده شوند
            setTimeout(() => {
                initializeProfileImageUpload();
                setTimeout(initializeGradeSheetUpload, 100);
            }, 800);
        }, 500); // تأخیر بیشتر برای اطمینان از نمایش کامل modal
    }, 10);
}

// بروزرسانی modal با اطلاعات جدید - تابع ساده شده
function updateModalWithNewData(request) {
    try {
        console.log('🔄 Updating modal with new data...');

        // اطلاعات اصلی
        const updates = [
            { id: 'modalUserName', value: request.name },
            { id: 'modalUserGrade', value: 'پایه ' + request.grade },
            { id: 'modalNameDisplay', value: request.name },
            { id: 'modalNationalCodeDisplay', value: request.nationalcode },
            { id: 'modalBirthdateDisplay', value: request.birthdate },
            { id: 'modalPhoneDisplay', value: request.phone },
            { id: 'modalTelephoneDisplay', value: request.telephone || 'ندارد' },
            { id: 'modalGrade', value: request.grade },
            { id: 'modalSchool', value: request.school },
            { id: 'modalPrincipal', value: request.principal },
            { id: 'modalMajor', value: request.major_name || 'ندارد' },
            { id: 'modalLastScore', value: request.last_score },
            { id: 'modalSchoolTelephone', value: request.school_telephone || 'ندارد' },
            { id: 'modalRental', value: request.rental },
            { id: 'modalAddress', value: request.address },
            { id: 'modalSiblingsCount', value: request.siblings_count },
            { id: 'modalSiblingsRank', value: request.siblings_rank },
            { id: 'modalKnow', value: request.know },
            { id: 'modalCounselingMethod', value: request.counseling_method }
        ];

        // بروزرسانی المنت‌ها
        updates.forEach(update => {
            const element = document.getElementById(update.id);
            if (element && update.value) {
                element.textContent = update.value;
            }
        });

        // سطح انگلیسی
        const englishLevel = parseInt(request.english_level || request.english_proficiency) || 0;
        const englishPercentElement = document.getElementById('modalEnglishPercent');
        if (englishPercentElement) {
            englishPercentElement.textContent = englishLevel + '%';
        }

        // progress bar
        const progressBar = document.getElementById('modalEnglishBar');
        if (progressBar) {
            progressBar.style.width = englishLevel + '%';
            if (typeof updateProgressBarColor === 'function') {
                updateProgressBarColor(progressBar, englishLevel);
            }
        }

        // بروزرسانی تصاویر
        if (request.imgpath) {
            const profileImg = document.getElementById('modalProfileImg');
            if (profileImg) {
                profileImg.src = `/private/${request.imgpath}`;
            }
        }

        if (request.gradesheetpath) {
            const gradeSheetImg = document.getElementById('modalGradeSheetImg');
            const gradeSheetLink = document.getElementById('modalGradeSheetLink');
            if (gradeSheetImg && gradeSheetLink) {
                if (request.gradesheetpath.toLowerCase().endsWith('.pdf')) {
                    gradeSheetImg.style.display = 'none';
                    gradeSheetLink.textContent = 'مشاهده کارنامه (PDF)';
                } else {
                    gradeSheetImg.src = `/private/${request.gradesheetpath}`;
                    gradeSheetImg.style.display = 'block';
                    gradeSheetLink.textContent = 'مشاهده کارنامه';
                }
                gradeSheetLink.href = `/private/${request.gradesheetpath}`;
            }
        }

        console.log('✅ Modal updated successfully');
    } catch (error) {
        console.error('❌ Error updating modal:', error);
        if (typeof showErrorMessage === 'function') {
            showErrorMessage('خطا در نمایش اطلاعات جدید');
        }
    }
}

// تابع برای بروزرسانی رنگ نوار پیشرفت انگلیسی
function updateProgressBarColor(progressBar, percentage) {
    if (!progressBar) return;

    // حذف کلاس‌های قبلی
    progressBar.classList.remove('english-low', 'english-medium', 'english-high');

    // اضافه کردن کلاس بر اساس درصد
    if (percentage <= 30) {
        progressBar.classList.add('english-low');
        progressBar.style.background = 'linear-gradient(270deg, #ef4444 0%, #dc2626 100%)'; // قرمز
    } else if (percentage <= 70) {
        progressBar.classList.add('english-medium');
        progressBar.style.background = 'linear-gradient(270deg, #f59e0b 0%, #d97706 100%)'; // زرد-نارنجی
    } else {
        progressBar.classList.add('english-high');
        progressBar.style.background = 'linear-gradient(270deg, #10b981 0%, #059669 100%)'; // سبز
    }
}
