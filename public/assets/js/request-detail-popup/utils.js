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

// Update editing popup
function updateEditingPopup() {
    // Remove existing popup
    const existingPopup = document.querySelector('.editing-popup');
    if (existingPopup) {
        existingPopup.remove();
    }

    // If no fields are being edited, don't show popup
    if (!window.editingFields || window.editingFields.size === 0) {
        return;
    }

    // Create popup
    const popup = document.createElement('div');
    popup.className = 'editing-popup fixed top-4 right-4 bg-blue-500 text-white px-4 py-3 rounded-lg shadow-lg z-[100] flex items-center space-x-2 space-x-reverse';
    popup.innerHTML = `
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
        <span>${window.editingFields.size} فیلد در حال ویرایش</span>
        <button id="saveAllFieldsBtn" class="bg-white text-blue-500 px-3 py-1 rounded text-sm font-medium hover:bg-gray-100 transition-colors">
            ویرایش همه
        </button>
    `;

    document.body.appendChild(popup);

    // Add event listener to save all button
    document.getElementById('saveAllFieldsBtn').addEventListener('click', saveAllEditingFields);
}

// Save all editing fields
function saveAllEditingFields() {
    if (!window.editingFields || window.editingFields.size === 0) return;

    const loadingEl = showLoadingIndicator('در حال ذخیره همه فیلدها...');
    let completed = 0;
    const total = window.editingFields.size;

    // Create a promise for each field
    const promises = Array.from(window.editingFields).map(fieldName => {
        // Find the form for this field
        const formId = getFormIdForField(fieldName);
        const form = document.getElementById(formId);
        if (!form) return Promise.resolve();

        // Get input value
        const input = form.querySelector('input, select, textarea');
        if (!input) return Promise.resolve();

        const newVal = input.value.trim();

        // Send update request
        return updateRequestField(fieldName, newVal)
            .then(response => {
                if (response.success) {
                    // Update display
                    const displayId = getDisplayIdForField(fieldName);
                    const display = document.getElementById(displayId);
                    if (display) {
                        display.textContent = newVal || 'ندارد';
                    }

                    // Hide form and show display
                    form.classList.add('hidden');
                    form.classList.remove('flex');
                    const displayEl = document.getElementById(displayId);
                    if (displayEl) displayEl.classList.remove('hidden');
                    const editBtn = document.getElementById(getEditBtnIdForField(fieldName));
                    if (editBtn) editBtn.classList.remove('hidden');

                    completed++;
                }
            })
            .catch(() => {}); // Continue even on error
    });

    // Wait for all to complete
    Promise.all(promises).then(() => {
        hideLoadingIndicator(loadingEl);
        window.editingFields.clear();
        updateEditingPopup();
        showSuccessMessage(`همه ${total} فیلد با موفقیت ذخیره شد`);
        // Refresh modal data
        if (typeof refreshRequestData === 'function') {
            setTimeout(refreshRequestData, 500);
        }
    });
}

// Confirmation popup functions for cancel action
function showCancelConfirmation() {
    const popup = document.getElementById('waning-popup');
    if (popup) {
        popup.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }
}

function hideCancelConfirmation() {
    const popup = document.getElementById('waning-popup');
    if (popup) {
        popup.classList.add('hidden');
        document.body.style.overflow = ''; // Restore scrolling
    }
}

// Initialize cancel popup functionality
function initializeCancelPopup() {
    const openBtn = document.getElementById('warning-open');
    const closeBtn = document.getElementById('warning-closepopup');

    if (openBtn) {
        openBtn.addEventListener('click', function(e) {
            e.preventDefault();
            showCancelConfirmation();
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', hideCancelConfirmation);
    }

    // Close on background click
    const popup = document.getElementById('waning-popup');
    if (popup) {
        popup.addEventListener('click', function(e) {
            if (e.target === popup) {
                hideCancelConfirmation();
            }
        });
    }

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !popup.classList.contains('hidden')) {
            hideCancelConfirmation();
        }
    });
}

// Call initialization when DOM is loaded
document.addEventListener('DOMContentLoaded', initializeCancelPopup);

// Helper function to get form ID for a field
function getFormIdForField(fieldName) {
    const fieldMappings = {
        'name': 'modalNameForm',
        'birthdate': 'modalBirthdateForm',
        'phone': 'modalPhoneForm',
        'telephone': 'modalTelephoneForm',
        'grade': 'modalGradeForm',
        'school': 'modalSchoolForm',
        'principal': 'modalPrincipalForm',
        'last_score': 'modalLastScoreForm',
        'school_telephone': 'modalSchoolTelephoneForm',
        'rental': 'modalRentalForm',
        'address': 'modalAddressForm',
        'siblings_count': 'modalSiblingsCountForm',
        'siblings_rank': 'modalSiblingsRankForm',
        'know': 'modalKnowForm',
        'counseling_method': 'modalCounselingMethodForm',
        'father_name': 'modalFatherNameForm',
        'father_phone': 'modalFatherPhoneForm',
        'father_job': 'modalFatherJobForm',
        'father_income': 'modalFatherIncomeForm',
        'father_job_address': 'modalFatherJobAddressForm',
        'mother_name': 'modalMotherNameForm',
        'mother_phone': 'modalMotherPhoneForm',
        'mother_job': 'modalMotherJobForm',
        'mother_income': 'modalMotherIncomeForm',
        'mother_job_address': 'modalMotherJobAddressForm',
        'motivation': 'modalMotivationForm',
        'spend': 'modalSpendForm',
        'how_am_i': 'modalHowAmIForm',
        'future': 'modalFutureForm',
        'favorite_major': 'modalFavoriteMajorForm',
        'help_others': 'modalHelpOthersForm',
        'suggestion': 'modalSuggestionForm',
        'nationalcode': 'modalNationalCodeForm',
        'english_proficiency': 'modalEnglishForm'
    };
    return fieldMappings[fieldName] || '';
}

// Helper function to get display ID for a field
function getDisplayIdForField(fieldName) {
    const fieldMappings = {
        'name': 'modalNameDisplay',
        'birthdate': 'modalBirthdateDisplay',
        'phone': 'modalPhoneDisplay',
        'telephone': 'modalTelephoneDisplay',
        'grade': 'modalGradeDisplay',
        'school': 'modalSchoolDisplay',
        'principal': 'modalPrincipalDisplay',
        'last_score': 'modalLastScoreDisplay',
        'school_telephone': 'modalSchoolTelephoneDisplay',
        'rental': 'modalRentalDisplay',
        'address': 'modalAddressDisplay',
        'siblings_count': 'modalSiblingsCountDisplay',
        'siblings_rank': 'modalSiblingsRankDisplay',
        'know': 'modalKnowDisplay',
        'counseling_method': 'modalCounselingMethodDisplay',
        'father_name': 'modalFatherName',
        'father_phone': 'modalFatherPhone',
        'father_job': 'modalFatherJob',
        'father_income': 'modalFatherIncome',
        'father_job_address': 'modalFatherJobAddress',
        'mother_name': 'modalMotherName',
        'mother_phone': 'modalMotherPhone',
        'mother_job': 'modalMotherJob',
        'mother_income': 'modalMotherIncome',
        'mother_job_address': 'modalMotherJobAddress',
        'motivation': 'modalMotivation',
        'spend': 'modalSpend',
        'how_am_i': 'modalHowAmI',
        'future': 'modalFuture',
        'favorite_major': 'modalFavoriteMajor',
        'help_others': 'modalHelpOthers',
        'suggestion': 'modalSuggestion',
        'nationalcode': 'modalNationalCodeDisplay',
        'english_proficiency': 'modalEnglishPercent'
    };
    return fieldMappings[fieldName] || '';
}

// Helper function to get edit button ID for a field
function getEditBtnIdForField(fieldName) {
    const fieldMappings = {
        'name': 'editNameBtn',
        'birthdate': 'editBirthdateBtn',
        'phone': 'editPhoneBtn',
        'telephone': 'editTelephoneBtn',
        'grade': 'editGradeBtn',
        'school': 'editSchoolBtn',
        'principal': 'editPrincipalBtn',
        'last_score': 'editLastScoreBtn',
        'school_telephone': 'editSchoolTelephoneBtn',
        'rental': 'editRentalBtn',
        'address': 'editAddressBtn',
        'siblings_count': 'editSiblingsCountBtn',
        'siblings_rank': 'editSiblingsRankBtn',
        'know': 'editKnowBtn',
        'counseling_method': 'editCounselingMethodBtn',
        'father_name': 'editFatherNameBtn',
        'father_phone': 'editFatherPhoneBtn',
        'father_job': 'editFatherJobBtn',
        'father_income': 'editFatherIncomeBtn',
        'father_job_address': 'editFatherJobAddressBtn',
        'mother_name': 'editMotherNameBtn',
        'mother_phone': 'editMotherPhoneBtn',
        'mother_job': 'editMotherJobBtn',
        'mother_income': 'editMotherIncomeBtn',
        'mother_job_address': 'editMotherJobAddressBtn',
        'motivation': 'editMotivationBtn',
        'spend': 'editSpendBtn',
        'how_am_i': 'editHowAmIBtn',
        'future': 'editFutureBtn',
        'favorite_major': 'editFavoriteMajorBtn',
        'help_others': 'editHelpOthersBtn',
        'suggestion': 'editSuggestionBtn',
        'nationalcode': 'editNationalCodeBtn',
        'english_proficiency': 'editEnglishBtn'
    };
    return fieldMappings[fieldName] || '';
}

