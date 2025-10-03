// بارگیری مجدد اطلاعات درخواست از دیتابیس
function refreshRequestData() {
    if (!window.currentRequestId) {
        console.error('❌ Request ID not available');
        return;
    }

    console.log('🔄 Refreshing request data for ID:', window.currentRequestId);

    // نمایش loading
    const loadingEl = document.createElement('div');
    loadingEl.className = 'fixed top-4 left-4 bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg z-[100] flex items-center space-x-2 space-x-reverse';
    loadingEl.innerHTML = `
        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>در حال بروزرسانی...</span>
    `;
    document.body.appendChild(loadingEl);

    // درخواست AJAX برای دریافت اطلاعات جدید
    fetch(`/unified/get-request-data/${window.currentRequestId}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('📥 Refresh response:', response.status);
        if (!response.ok) {
            throw new Error('خطا در دریافت اطلاعات');
        }
        return response.json();
    })
    .then(data => {
        console.log('✅ Refresh data:', data);
        if (data.success && data.request) {
            // بروزرسانی اطلاعات در modal
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
        // حذف loading
        if (document.body.contains(loadingEl)) {
            document.body.removeChild(loadingEl);
        }
    });
}

// ارسال AJAX برای بروزرسانی فیلد
function updateRequestField(fieldName, fieldValue) {
    console.log('📡 Sending AJAX request for field:', fieldName);

    return fetch('/unified/update-request-field', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            request_id: window.currentRequestId,
            field_name: fieldName,
            field_value: fieldValue
        })
    })
    .then(res => {
        console.log(`📥 Response for ${fieldName}:`, res.status);
        return res.json();
    });
}
