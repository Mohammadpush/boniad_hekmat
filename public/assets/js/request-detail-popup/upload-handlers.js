// هندلرهای آپلود فایل‌ها

// آپلود عکس پروفایل
function initializeProfileImageUpload() {
    console.log('🎯 Initializing profile image upload...');

    const uploadBtn = document.getElementById('uploadProfileImgBtn');
    const fileInput = document.getElementById('profileImgInput');
    const imgElement = document.getElementById('modalProfileImg');

    console.log('Upload elements found:', {
        uploadBtn: !!uploadBtn,
        fileInput: !!fileInput,
        imgElement: !!imgElement
    });

    if (!uploadBtn || !fileInput || !imgElement) {
        console.log('❌ Profile image upload elements not found');
        return;
    }

    // پاک کردن event listenerهای قبلی
    uploadBtn.replaceWith(uploadBtn.cloneNode(true));
    fileInput.replaceWith(fileInput.cloneNode(true));

    // گرفتن المنت‌های جدید
    const newUploadBtn = document.getElementById('uploadProfileImgBtn');
    const newFileInput = document.getElementById('profileImgInput');

    console.log('✅ Adding event listeners for profile image upload');

    newUploadBtn.addEventListener('click', function() {
        console.log('📸 Profile image upload button clicked');
        newFileInput.click();
    });

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // اعتبارسنجی فایل
        if (!file.type.startsWith('image/')) {
            showErrorMessage('فایل انتخاب شده باید تصویر باشد');
            return;
        }

        if (file.size > 2048 * 1024) { // 2MB
            showErrorMessage('حجم فایل نباید بیشتر از 2 مگابایت باشد');
            return;
        }

        // نمایش loading
        uploadBtn.disabled = true;
        uploadBtn.innerHTML = '<svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

        // آپلود فایل
        const formData = new FormData();
        formData.append('file', file);
        formData.append('field_name', 'imgpath');

        fetch('/unified/upload-file', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // بروزرسانی تصویر
                imgElement.src = data.file_url;
                showSuccessMessage('عکس پروفایل با موفقیت آپلود شد');
                // بروزرسانی داده‌های modal
                refreshRequestData();
            } else {
                throw new Error(data.message || 'خطا در آپلود فایل');
            }
        })
        .catch(error => {
            console.error('Upload error:', error);
            showErrorMessage('خطا در آپلود فایل: ' + error.message);
        })
        .finally(() => {
            // بازنشانی دکمه
            uploadBtn.disabled = false;
            uploadBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>';
            fileInput.value = '';
        });
    });
}

// آپلود کارنامه
function initializeGradeSheetUpload() {
    console.log('📄 Initializing grade sheet upload...');

    const uploadBtn = document.getElementById('uploadGradeSheetBtn');
    const fileInput = document.getElementById('gradeSheetInput');
    const imgElement = document.getElementById('modalGradeSheetImg');
    const linkElement = document.getElementById('modalGradeSheetLink');

    console.log('Grade sheet elements found:', {
        uploadBtn: !!uploadBtn,
        fileInput: !!fileInput,
        imgElement: !!imgElement,
        linkElement: !!linkElement
    });

    if (!uploadBtn || !fileInput || !imgElement || !linkElement) {
        console.log('❌ Grade sheet upload elements not found');
        return;
    }

    // پاک کردن event listenerهای قبلی
    uploadBtn.replaceWith(uploadBtn.cloneNode(true));
    fileInput.replaceWith(fileInput.cloneNode(true));

    // گرفتن المنت‌های جدید
    const newUploadBtn = document.getElementById('uploadGradeSheetBtn');
    const newFileInput = document.getElementById('gradeSheetInput');

    console.log('✅ Adding event listeners for grade sheet upload');

    newUploadBtn.addEventListener('click', function() {
        console.log('📄 Grade sheet upload button clicked');
        newFileInput.click();
    });

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // اعتبارسنجی فایل
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'application/pdf'];
        if (!allowedTypes.includes(file.type)) {
            showErrorMessage('فایل انتخاب شده باید تصویر یا PDF باشد');
            return;
        }

        if (file.size > 5048 * 1024) { // 5MB
            showErrorMessage('حجم فایل نباید بیشتر از 5 مگابایت باشد');
            return;
        }

        // نمایش loading
        uploadBtn.disabled = true;
        uploadBtn.textContent = 'در حال آپلود...';

        // آپلود فایل
        const formData = new FormData();
        formData.append('file', file);
        formData.append('field_name', 'gradesheetpath');

        fetch('/unified/upload-file', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // بروزرسانی تصویر/لینک
                if (file.type === 'application/pdf') {
                    imgElement.style.display = 'none';
                    linkElement.textContent = 'مشاهده کارنامه (PDF)';
                } else {
                    imgElement.src = data.file_url;
                    imgElement.style.display = 'block';
                    linkElement.textContent = 'مشاهده کارنامه';
                }
                linkElement.href = data.file_url;
                showSuccessMessage('کارنامه با موفقیت آپلود شد');
                // بروزرسانی داده‌های modal
                refreshRequestData();
            } else {
                throw new Error(data.message || 'خطا در آپلود فایل');
            }
        })
        .catch(error => {
            console.error('Upload error:', error);
            showErrorMessage('خطا در آپلود فایل: ' + error.message);
        })
        .finally(() => {
            // بازنشانی دکمه
            uploadBtn.disabled = false;
            uploadBtn.textContent = 'آپلود مجدد';
            fileInput.value = '';
        });
    });
}

// توابع کمکی برای نمایش پیام‌ها
function showSuccessMessage(message) {
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 left-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-[100] flex items-center space-x-2 space-x-reverse';
    toast.innerHTML = `
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>${message}</span>
    `;
    document.body.appendChild(toast);
    setTimeout(() => document.body.removeChild(toast), 3000);
}

function showErrorMessage(message) {
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 left-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-[100] flex items-center space-x-2 space-x-reverse';
    toast.innerHTML = `
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>${message}</span>
    `;
    document.body.appendChild(toast);
    setTimeout(() => document.body.removeChild(toast), 3000);
}
