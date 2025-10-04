document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('requestDetailModal');
    const closeBtn = document.getElementById('closeRequestDetailModal');

    // تابع برای بستن مودال با انیمیشن
    function closeModal() {
        // توقف live update مودال اگر فعال باشد
        if (typeof stopModalLiveUpdate === 'function') {
            stopModalLiveUpdate();
        }

        modal.classList.remove('show');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';

            // مخفی کردن کارنامه هنگام بستن مودال
            const gradeSheetDiv = document.getElementById('modalGradeSheet');
            if (gradeSheetDiv) {
                gradeSheetDiv.classList.add('hidden');
            }

            // بروزرسانی صفحه اصلی بعد از بستن مودال
            updateMainPageAfterModalClose();
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

    // تابع برای بروزرسانی صفحه اصلی بعد از بستن مودال
    function updateMainPageAfterModalClose() {
        console.log('🔄 Updating main page after modal close...');

        // بررسی اینکه آیا در صفحه myrequests هستیم
        if (!window.location.pathname.includes('myrequests')) {
            return; // اگر در صفحه myrequests نیستیم، کاری نکن
        }

        // استفاده از AJAX برای دریافت داده‌های جدید
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
                console.log('📡 Updating page with new data after modal close...');

                // بروزرسانی صفحه با داده‌های جدید (همان منطق live-update.js)
                updatePageWithNewData(data);

                // نمایش پیام بروزرسانی
                showUpdateNotification();
            } else {
                console.error('❌ Failed to fetch requests data after modal close:', data);
            }
        })
        .catch(error => {
            console.error('❌ Error updating page after modal close:', error);
        });
    }

    // توابع کمکی برای بروزرسانی صفحه (کپی از live-update.js)
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

                switch(requestData.story) {
                    case 'submit':
                        statusColor = 'bg-blue-100 text-blue-700 border-blue-200';
                        statusText = '📤 ارسال شده';
                        break;
                    case 'accept':
                        statusColor = 'bg-green-100 text-green-700 border-green-200';
                        statusText = '✅ تایید شده';
                        break;
                    case 'check':
                        statusColor = 'bg-yellow-100 text-yellow-700 border-yellow-200';
                        statusText = '🔍 در حال بررسی';
                        break;
                    case 'reject':
                        statusColor = 'bg-red-100 text-red-700 border-red-200';
                        statusText = '❌ رد شده';
                        break;
                    case 'epointment':
                        statusColor = 'bg-purple-100 text-purple-700 border-purple-200';
                        statusText = '📅 ملاقات';
                        break;
                    default:
                        statusColor = 'bg-gray-100 text-gray-700 border-gray-200';
                        statusText = '❓ نامشخص';
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
