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

// Profile image upload
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

    // Remove old event listeners
    uploadBtn.replaceWith(uploadBtn.cloneNode(true));
    fileInput.replaceWith(fileInput.cloneNode(true));

    // Get new elements
    const newUploadBtn = document.getElementById('uploadProfileImgBtn');
    const newFileInput = document.getElementById('profileImgInput');

    console.log('✅ Adding event listeners for profile image upload');

    newUploadBtn.addEventListener('click', function() {
        console.log('📸 Profile image upload button clicked');
        newFileInput.click();
    });

    newFileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Validate file
        if (!file.type.startsWith('image/')) {
            showErrorMessage('فایل انتخاب شده باید تصویر باشد');
            return;
        }

        if (file.size > 2048 * 1024) { // 2MB
            showErrorMessage('حجم فایل نباید بیشتر از 2 مگابایت باشد');
            return;
        }

        console.log('📤 Uploading profile image...');
        const loadingEl = showLoadingIndicator('در حال آپلود تصویر...');

        // Create FormData
        const formData = new FormData();
        formData.append('profile_image', file);
        formData.append('request_id', window.currentRequestId);

        // Upload request
        fetch('/unified/upload-profile-image', {
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
                console.log('✅ Profile image uploaded successfully');
                imgElement.src = data.image_url;
                showSuccessMessage('تصویر پروفایل با موفقیت آپلود شد');

                // Refresh modal data
                setTimeout(refreshRequestData, 500);
            } else {
                throw new Error(data.message || 'خطا در آپلود تصویر');
            }
        })
        .catch(error => {
            console.error('❌ Profile image upload error:', error);
            showErrorMessage('خطا در آپلود تصویر پروفایل');
        })
        .finally(() => {
            hideLoadingIndicator(loadingEl);
        });
    });
}

// Grade sheet upload
function initializeGradeSheetUpload() {
    console.log('📄 Initializing grade sheet upload...');

    const uploadBtn = document.getElementById('uploadGradeSheetBtn');
    const fileInput = document.getElementById('gradeSheetInput');

    console.log('Grade sheet upload elements found:', {
        uploadBtn: !!uploadBtn,
        fileInput: !!fileInput
    });

    if (!uploadBtn || !fileInput) {
        console.log('❌ Grade sheet upload elements not found');
        return;
    }

    // Remove old event listeners
    uploadBtn.replaceWith(uploadBtn.cloneNode(true));
    fileInput.replaceWith(fileInput.cloneNode(true));

    // Get new elements
    const newUploadBtn = document.getElementById('uploadGradeSheetBtn');
    const newFileInput = document.getElementById('gradeSheetInput');

    console.log('✅ Adding event listeners for grade sheet upload');

    newUploadBtn.addEventListener('click', function() {
        console.log('📄 Grade sheet upload button clicked');
        newFileInput.click();
    });

    newFileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Validate file
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
        if (!allowedTypes.includes(file.type)) {
            showErrorMessage('فایل باید تصویر (JPG, PNG) یا PDF باشد');
            return;
        }

        if (file.size > 5120 * 1024) { // 5MB
            showErrorMessage('حجم فایل نباید بیشتر از 5 مگابایت باشد');
            return;
        }

        console.log('📤 Uploading grade sheet...');
        const loadingEl = showLoadingIndicator('در حال آپلود کارنامه...');

        // Create FormData
        const formData = new FormData();
        formData.append('grade_sheet', file);
        formData.append('request_id', window.currentRequestId);

        // Upload request
        fetch('/unified/upload-grade-sheet', {
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
                console.log('✅ Grade sheet uploaded successfully');
                showSuccessMessage('کارنامه با موفقیت آپلود شد');

                // Refresh modal data
                setTimeout(refreshRequestData, 500);
            } else {
                throw new Error(data.message || 'خطا در آپلود کارنامه');
            }
        })
        .catch(error => {
            console.error('❌ Grade sheet upload error:', error);
            showErrorMessage('خطا در آپلود کارنامه');
        })
        .finally(() => {
            hideLoadingIndicator(loadingEl);
        });
    });
}