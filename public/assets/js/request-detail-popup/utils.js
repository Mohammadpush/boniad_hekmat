// Utility functions for request detail popup
// Common functions used across multiple files

// Show success message
function showSuccessMessage(message) {
    showNotification(message, 'success');
}

// Show error message
function showErrorMessage(message) {
    showNotification(message, 'error');
}

// Generic notification function
function showNotification(message, type = 'info') {
    // Remove existing notification
    const existingNotification = document.querySelector('.custom-notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    // Create notification
    const notification = document.createElement('div');
    notification.className = `custom-notification fixed top-4 left-4 px-4 py-2 rounded-lg shadow-lg z-[100] flex items-center space-x-2 space-x-reverse animate-pulse ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;

    const icon = type === 'success' ? 'M5 13l4 4L19 7' :
                type === 'error' ? 'M6 18L18 6M6 6l12 12' :
                'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';

    notification.innerHTML = `
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${icon}"></path>
        </svg>
        <span>${message}</span>
    `;

    document.body.appendChild(notification);

    // Remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}

// Update card in main page
function updateCard(cardElement, requestData) {
    try {
        // Update image
        const img = cardElement.querySelector('img');
        if (img && img.src !== requestData.imgpath_url) {
            img.src = requestData.imgpath_url;
        }

        // Update name
        const nameElement = cardElement.querySelector('h3');
        if (nameElement && nameElement.textContent !== requestData.name) {
            nameElement.textContent = requestData.name;
        }

        // Update grade
        const gradeElement = cardElement.querySelector('p.text-sm.text-gray-500');
        if (gradeElement) {
            const expectedText = 'پایه: ' + requestData.grade;
            if (gradeElement.textContent !== expectedText) {
                gradeElement.textContent = expectedText;
            }
        }

        // Update status
        const statusBadge = cardElement.querySelector('.status-badge');
        if (statusBadge) {
            statusBadge.className = 'status-badge px-3 py-1 rounded-full text-xs font-medium border';

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

        // Update onclick for view button
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

// Update page with new data (used by both live-update and modal-close)
function updatePageWithNewData(data) {
    const mainContainer = document.querySelector('main .bg-white.rounded-xl.shadow.p-6');

    if (!mainContainer) {
        console.error('❌ Main container not found');
        return;
    }

    // If requests existed and now don't exist
    if (data.requests.length === 0 && document.querySelector('.flex.flex-wrap.gap-14')) {
        console.log('🔄 No requests found, showing empty state...');
        location.reload();
        return;
    }

    // If requests didn't exist and now exist
    if (data.requests.length > 0 && !document.querySelector('.flex.flex-wrap.gap-14')) {
        console.log('🔄 Requests found, showing requests list...');
        location.reload();
        return;
    }

    // If number of requests changed
    const currentCards = document.querySelectorAll('.card-hover').length;
    if (currentCards !== data.requests.length) {
        console.log('🔄 Number of requests changed, reloading page...');
        location.reload();
        return;
    }

    // Update existing cards
    const cards = document.querySelectorAll('.card-hover');
    data.requests.forEach((request, index) => {
        if (cards[index]) {
            updateCard(cards[index], request);
        }
    });
}

// Generic AJAX request function
function makeAjaxRequest(url, options = {}) {
    const defaultOptions = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    };

    return fetch(url, { ...defaultOptions, ...options })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        });
}

// Show loading indicator
function showLoadingIndicator(message = 'در حال بارگذاری...') {
    const loadingEl = document.createElement('div');
    loadingEl.className = 'loading-indicator fixed top-4 left-4 bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg z-[100] flex items-center space-x-2 space-x-reverse';
    loadingEl.innerHTML = `
        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>${message}</span>
    `;
    document.body.appendChild(loadingEl);
    return loadingEl;
}

// Hide loading indicator
function hideLoadingIndicator(loadingEl) {
    if (loadingEl && document.body.contains(loadingEl)) {
        document.body.removeChild(loadingEl);
    }
}
