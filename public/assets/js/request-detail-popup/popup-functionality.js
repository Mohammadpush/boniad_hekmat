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
    document.getElementById('modalNationalCodeDisplay').textContent = requestData.nationalcode || '';
    document.getElementById('modalBirthdate').textContent = requestData.birthdate || '';
    document.getElementById('modalPhone').textContent = requestData.phone || '';
    document.getElementById('modalTelephone').textContent = requestData.telephone || 'وارد نشده';

    // پر کردن اطلاعات تحصیلی
    document.getElementById('modalGrade').textContent = requestData.grade || '';
    document.getElementById('modalSchool').textContent = requestData.school || '';
    document.getElementById('modalPrincipal').textContent = requestData.principal || '';
    document.getElementById('modalMajor').textContent = requestData.major_name || 'ندارد';
    document.getElementById('modalLastScore').textContent = requestData.last_score || '';
    document.getElementById('modalSchoolTelephone').textContent = requestData.school_telephone || 'وارد نشده';

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
    document.getElementById('modalRental').textContent = requestData.rental == '0' ? '🏠 ملکی' : '🏠 استیجاری';
    document.getElementById('modalAddress').textContent = requestData.address || '';

    // پر کردن اطلاعات خانوادگی
    document.getElementById('modalSiblingsCount').textContent = (requestData.siblings_count || '0') + ' نفر';
    document.getElementById('modalSiblingsRank').textContent = 'فرزند ' + (requestData.siblings_rank || '1') + 'ام';
    document.getElementById('modalKnow').textContent = requestData.know || '';
    document.getElementById('modalCounselingMethod').textContent = requestData.counseling_method || '';

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

    // نمایش مودال با انیمیشن
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // جلوگیری از اسکرول صفحه

    // اضافه کردن کلاس انیمیشن بعد از نمایش
    setTimeout(() => {
        modal.classList.add('show');
    }, 10);
}
