// Live Update برای صفحه درخواست‌های من
document.addEventListener('DOMContentLoaded', function () {
    console.log('🚀 Initializing page update handler...');

    // تعریف تابع بروزرسانی به صورت global برای دسترسی از سایر فایل‌ها
    window.updatePageData = function () {
        console.log('🔄 Updating page data...');
        checkForUpdates();
    };

    function checkForUpdates() {

        fetch('/unified/myrequests-data', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updatePageWithNewData(data);

                }
            })
    }

    function updatePageWithNewData(data) {
        const mainContainer = document.querySelector('main .bg-white.rounded-xl.shadow.p-6');

        if (!mainContainer) {
            console.error('❌ Main container not found');
            return;
        }

        // اگر قبلاً درخواست‌هایی وجود داشته و حالا وجود ندارد
        if (data.requests.length === 0 && document.querySelector('.flex.flex-wrap.gap-14')) {
            console.log('🔄 No requests found, showing empty state...');
            location.reload(); // reload کامل برای نمایش حالت خالی
            return;
        }

        // اگر قبلاً درخواست‌هایی وجود نداشته و حالا وجود دارد
        if (data.requests.length > 0 && !document.querySelector('.flex.flex-wrap.gap-14')) {
            console.log('🔄 Requests found, showing requests list...');
            location.reload(); // reload کامل برای نمایش لیست
            return;
        }

        // اگر تعداد درخواست‌ها تغییر کرده
        const currentCards = document.querySelectorAll('.card-hover').length;
        if (currentCards !== data.requests.length) {
            console.log('🔄 Number of requests changed, reloading page...');
            location.reload();
            return;
        }

        // بروزرسانی کارت‌های موجود
        const cards = document.querySelectorAll('.card-hover');
        data.requests.forEach((request, index) => {
            if (cards[index]) {
                updateCard(cards[index], request);
            }
        });

        // نمایش پیام بروزرسانی
        showUpdateNotification();
    }

    function updateCard(cardElement, requestData) {
        try {
            // بروزرسانی تصویر
            const img = cardElement.querySelector('img');
            if (img && img.src !== requestData.imgpath_url) {
                img.src = requestData.imgpath_url;
            }

            // بروزرسانی نام
            const nameElement = cardElement.querySelector('h3');
            if (nameElement && nameElement.textContent !== requestData.name) {
                nameElement.textContent = requestData.name;
            }

            // بروزرسانی پایه
            const gradeElement = cardElement.querySelector('p.text-sm.text-gray-500');
            if (gradeElement) {
                const expectedText = 'پایه: ' + requestData.grade;
                if (gradeElement.textContent !== expectedText) {
                    gradeElement.textContent = expectedText;
                }
            }

            // بروزرسانی وضعیت
            const statusBadge = cardElement.querySelector('.status-badge');
            if (statusBadge) {
                // پاک کردن کلاس‌های قبلی
                statusBadge.className = 'status-badge px-3 py-1 rounded-full text-xs font-medium border';

                // اضافه کردن کلاس‌های جدید بر اساس وضعیت
                let statusColor = '';
                let statusText = '';
                switch (requestData.story) {
                    case 'check':
                        statusColor = 'bg-yellow-500';
                        statusText = 'در حال بررسی';
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

                statusBadge.className += ' ' + statusColor;
                statusBadge.textContent = statusText;
            }

            // بروزرسانی onclick برای دکمه مشاهده
            const viewButton = cardElement.querySelector('button[onclick*="openRequestDetailModal"]');
            if (viewButton) {
                const newOnclick = `openRequestDetailModal({
                    id: ${requestData.id},
                    name: '${requestData.name.replace(/'/g, "\\'")}',
                    grade: '${requestData.grade}',
                    story: '${requestData.story}',
                    imgpath_url: '${requestData.imgpath_url}',
                    nationalcode: '${requestData.nationalcode}',
                    birthdate: '${requestData.birthdate}',
                    phone: '${requestData.phone}',
                    telephone: '${requestData.telephone || ''}',
                    school: '${requestData.school}',
                    principal: '${requestData.principal}',
                    major_name: '${requestData.major_name}',
                    last_score: '${requestData.last_score}',
                    school_telephone: '${requestData.school_telephone || ''}',
                    english_proficiency: ${requestData.english_proficiency},
                    gradesheetpath: '${requestData.gradesheetpath || ''}',
                    gradesheetpath_url: '${requestData.gradesheetpath_url || ''}',
                    rental: '${requestData.rental}',
                    address: '${requestData.address}',
                    siblings_count: '${requestData.siblings_count}',
                    siblings_rank: '${requestData.siblings_rank}',
                    know: '${requestData.know}',
                    counseling_method: '${requestData.counseling_method}',
                    why_counseling_method: '${requestData.why_counseling_method || ''}',
                    father_name: '${requestData.father_name || ''}',
                    father_phone: '${requestData.father_phone || ''}',
                    father_job: '${requestData.father_job || ''}',
                    father_income: '${requestData.father_income || ''}',
                    father_job_address: '${requestData.father_job_address || ''}',
                    mother_name: '${requestData.mother_name || ''}',
                    mother_phone: '${requestData.mother_phone || ''}',
                    mother_job: '${requestData.mother_job || ''}',
                    mother_income: '${requestData.mother_income || ''}',
                    mother_job_address: '${requestData.mother_job_address || ''}',
                    motivation: '${requestData.motivation || ''}',
                    spend: '${requestData.spend || ''}',
                    how_am_i: '${requestData.how_am_i || ''}',
                    future: '${requestData.future || ''}',
                    favorite_major: '${requestData.favorite_major || ''}',
                    help_others: '${requestData.help_others || ''}',
                    suggestion: '${requestData.suggestion || ''}'
                })`;

                viewButton.setAttribute('onclick', newOnclick);
            }

        } catch (error) {
            console.error('❌ Error updating card:', error);
        }
    }

    function showUpdateNotification() {
        // حذف notification قبلی اگر وجود داشته باشد
        const existingNotification = document.querySelector('.live-update-notification');
        if (existingNotification) {
            existingNotification.remove();
        }

        // ایجاد notification جدید
        const notification = document.createElement('div');
        notification.className = 'live-update-notification fixed top-4 left-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-[100] flex items-center space-x-2 space-x-reverse animate-pulse';
        notification.innerHTML = `
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>اطلاعات بروزرسانی شد</span>
        `;

        document.body.appendChild(notification);

        // حذف notification بعد از 3 ثانیه
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 3000);
    }
});
