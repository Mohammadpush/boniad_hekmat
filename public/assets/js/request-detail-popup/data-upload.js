// Data and Upload Management
// Combines data-manager.js and upload-handlers.js

// Refresh request data from database
function refreshRequestData() {
    if (!window.currentRequestId) {
        console.error('❌ Request ID not available');
        return;
    }

    console.log('🔄 Refreshing request data for ID:', window.currentRequestId);

    // Show loading
    const loadingEl = showLoadingIndicator('در حال بروزرسانی...');

    // AJAX request to get new data
    makeAjaxRequest(`/unified/get-request-data/${window.currentRequestId}`)
        .then(data => {
            console.log('✅ Refresh data:', data);
            if (data.success && data.request) {
                // Update information in modal
                updateModalWithNewData(data.request);
                showSuccessMessage('اطلاعات بروزرسانی شد');
            } else {
                throw new Error(data.message || 'خطا در دریافت اطلاعات');
            }
        })
        .catch(error => {
            console.error('❌ Refresh error:', error);
            showErrorMessage('خطا در بروزرسانی اطلاعات');
        })
        .finally(() => {
            hideLoadingIndicator(loadingEl);
        });
}

// Send AJAX request to update field
function updateRequestField(fieldName, fieldValue) {
    console.log('📡 Sending AJAX request for field:', fieldName);

    return makeAjaxRequest('/unified/update-request-field', {
        method: 'POST',
        body: JSON.stringify({
            request_id: window.currentRequestId,
            field_name: fieldName,
            field_value: fieldValue
        })
    });
}

// Update modal with new data
function updateModalWithNewData(request) {
    try {
        console.log('🔄 Updating modal with new data...');

        // Update basic info
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
        ];

        updates.forEach(update => {
            const element = document.getElementById(update.id);
            if (element && element.textContent !== update.value) {
                element.textContent = update.value;
            }
        });

        // Update images
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

// Upload handlers

// آپلود عکس پروفایل
function initializeProfileImageUpload() {
    console.log('🎯 Starting profile image upload initialization...');

    // 1. یافتن المنت‌های مورد نیاز
    const uploadBtn = document.getElementById('uploadProfileImgBtn');
    const fileInput = document.getElementById('profileImgInput');
    const imgElement = document.getElementById('modalProfileImg');
    const requestId = window.currentRequestId;

    // 2. بررسی وجود المنت‌ها
    if (!uploadBtn || !fileInput || !imgElement) {
        console.error('❌ Required elements not found:', {
            uploadBtn: !!uploadBtn,
            fileInput: !!fileInput,
            imgElement: !!imgElement,
            requestId: !!requestId
        });
        return;
    }

    // حذف event listenerهای قبلی با ساخت المنت‌های جدید
    const newUploadBtn = uploadBtn.cloneNode(true);
    const newFileInput = fileInput.cloneNode(true);
    uploadBtn.parentNode.replaceChild(newUploadBtn, uploadBtn);
    fileInput.parentNode.replaceChild(newFileInput, fileInput);

    // 3. اضافه کردن event listener برای دکمه آپلود
    newUploadBtn.addEventListener('click', () => {
        console.log('📸 Upload button clicked');
        newFileInput.click();
    });

    // 4. اضافه کردن event listener برای انتخاب فایل
    newFileInput.addEventListener('change', async function(e) {
        console.log('🔄 File input changed');

        const file = e.target.files[0];
        if (!file) {
            console.log('❌ No file selected');
            return;
        }

        // 5. اعتبارسنجی فایل
        console.log('📝 File details:', {
            name: file.name,
            type: file.type,
            size: file.size
        });

        if (!file.type.startsWith('image/')) {
            showErrorMessage('فایل انتخاب شده باید تصویر باشد');
            return;
        }

        if (file.size > 2048 * 1024) {
            showErrorMessage('حجم فایل نباید بیشتر از 2 مگابایت باشد');
            return;
        }

        // 6. نمایش loading
        uploadBtn.disabled = true;
        uploadBtn.innerHTML = '<div class="animate-spin h-5 w-5 border-2 border-blue-500 border-t-transparent rounded-full"></div>';

        try {
            // 7. ساخت FormData
            const formData = new FormData();
            formData.append('imgpath', file);
            formData.append('id', requestId);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            console.log('📤 Sending request with data:', {
                fileSize: file.size,
                fileName: file.name,
                requestId: requestId
            });

            // 8. ارسال درخواست
            const response = await fetch('/unified/upload-file', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            // 9. بررسی پاسخ
            console.log('📥 Response status:', response.status);
            const data = await response.json();
            console.log('📥 Response data:', data);

            if (!response.ok) {
                throw new Error(data.message || 'خطا در آپلود فایل');
            }

            if (data.success) {
                // 10. بروزرسانی تصویر
                imgElement.src = data.file_url || `/private/${data.file_path}`;
                showSuccessMessage('عکس پروفایل با موفقیت آپلود شد');
            } else {
                throw new Error(data.message || 'خطا در آپلود فایل');
            }
        } catch (error) {
            console.error('❌ Upload error:', error);
            showErrorMessage(error.message);
        } finally {
            // 11. بازنشانی وضعیت دکمه
            newUploadBtn.disabled = false;
            newUploadBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /></svg>';
            newFileInput.value = '';
        }
    });
}

// آپلود کارنامه
function initializeGradeSheetUpload() {
    console.log('🎯 Starting grade sheet upload initialization...');

    // 1. یافتن المنت‌های مورد نیاز
    const uploadBtn = document.getElementById('uploadGradeSheetBtn');
    const fileInput = document.getElementById('gradeSheetInput');
    const imgElement = document.getElementById('modalGradeSheetImg');
    const linkElement = document.getElementById('modalGradeSheetLink');
    const requestId = window.currentRequestId;

    // 2. بررسی وجود المنت‌ها
    if (!uploadBtn || !fileInput || !imgElement || !linkElement) {
        console.error('❌ Required elements not found:', {
            uploadBtn: !!uploadBtn,
            fileInput: !!fileInput,
            imgElement: !!imgElement,
            linkElement: !!linkElement,
            requestId: !!requestId
        });
        return;
    }

    // 3. حذف event listenerهای قبلی با ساخت المنت‌های جدید
    const newUploadBtn = uploadBtn.cloneNode(true);
    const newFileInput = fileInput.cloneNode(true);
    uploadBtn.parentNode.replaceChild(newUploadBtn, uploadBtn);
    fileInput.parentNode.replaceChild(newFileInput, fileInput);

    // 4. اضافه کردن event listener برای دکمه آپلود
    newUploadBtn.addEventListener('click', () => {
        console.log('📄 Grade sheet upload button clicked');
        newFileInput.click();
    });

    // 5. اضافه کردن event listener برای انتخاب فایل
    newFileInput.addEventListener('change', async function(e) {
        console.log('🔄 File input changed');

        const file = e.target.files[0];
        if (!file) {
            console.log('❌ No file selected');
            return;
        }

        // 6. اعتبارسنجی فایل
        console.log('📝 File details:', {
            name: file.name,
            type: file.type,
            size: file.size
        });

        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'application/pdf'];
        if (!allowedTypes.includes(file.type)) {
            showErrorMessage('فایل انتخاب شده باید تصویر یا PDF باشد');
            return;
        }

        if (file.size > 50048 * 1024) { // 5MB
            showErrorMessage('حجم فایل نباید بیشتر از 5 مگابایت باشد');
            return;
        }

        // 7. نمایش loading
        newUploadBtn.disabled = true;
        newUploadBtn.innerHTML = '<div class="animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full"></div>';

        try {
            // 8. ساخت FormData
            const formData = new FormData();
            formData.append('gradesheetpath', file);
            formData.append('id', requestId);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            console.log('📤 Sending grade sheet upload request:', {
                fileType: file.type,
                fileSize: file.size,
                fieldName: 'gradesheetpath',
                requestId: requestId
            });

            // 9. ارسال درخواست
            const response = await fetch('/unified/upload-pdf', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            // 10. بررسی پاسخ
            console.log('📥 Response status:', response.status);
            const data = await response.json();
            console.log('📥 Response data:', data);

            if (!response.ok) {
                throw new Error(data.message || 'خطا در آپلود فایل');
            }

            if (data.success) {
                // 11. بروزرسانی تصویر/لینک
                if (file.type === 'application/pdf') {
                    imgElement.style.display = 'none';
                    linkElement.textContent = 'مشاهده کارنامه (PDF)';
                } else {
                    imgElement.src = data.file_url || `/private/${data.file_path}`;
                    imgElement.style.display = 'block';
                    linkElement.textContent = 'مشاهده کارنامه';
                }
                linkElement.href = data.file_url || `/private/${data.file_path}`;
                showSuccessMessage('کارنامه با موفقیت آپلود شد');

                // بروزرسانی داده‌های modal
                if (typeof refreshRequestData === 'function') {
                    refreshRequestData();
                }
            } else {
                throw new Error(data.message || 'خطا در آپلود فایل');
            }
        } catch (error) {
            console.error('❌ Upload error:', error);
            showErrorMessage(error.message);
        } finally {
            // 12. بازنشانی وضعیت دکمه
            newUploadBtn.disabled = false;
            newUploadBtn.innerHTML = 'آپلود مجدد';
            newFileInput.value = '';
        }
    });
}

