// Modal Core Functionality
// Combines popup-functionality.js, close.js, and modal-live-update.js

// Modal live update variables
let modalLiveUpdateInterval = null;
let modalLastUpdateTime = null;
let modalIsUpdating = false;

// Open modal function
function openRequestDetailModal(requestData, cardElement = null) {
    const modal = document.getElementById('requestDetailModal');

    // Store request ID for editing
    window.currentRequestId = requestData.id;

    // Card animation if card element is provided
    if (cardElement) {
        cardElement.classList.add('card-animate-to-center');
        setTimeout(() => {
            cardElement.classList.remove('card-animate-to-center');
        }, 600);
    }

    // Fill profile information
    document.getElementById('modalProfileImg').src = requestData.imgpath_url;
    document.getElementById('modalProfileImg').alt = requestData.name;
    document.getElementById('modalUserName').textContent = requestData.name;
    document.getElementById('modalUserGrade').textContent = 'پایه ' + requestData.grade;

    // Set status
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

    // Fill personal information
    document.getElementById('modalNameDisplay').textContent = requestData.name || '';
    document.getElementById('modalNationalCodeDisplay').textContent = requestData.nationalcode || '';
    document.getElementById('modalBirthdateDisplay').textContent = requestData.birthdate || '';
    document.getElementById('modalPhoneDisplay').textContent = requestData.phone || '';
    document.getElementById('modalTelephoneDisplay').textContent = requestData.telephone || 'وارد نشده';

    // Fill educational information
    document.getElementById('modalGradeDisplay').textContent = requestData.grade || '';
    document.getElementById('modalSchoolDisplay').textContent = requestData.school || '';
    document.getElementById('modalPrincipalDisplay').textContent = requestData.principal || '';
    document.getElementById('modalMajor').textContent = requestData.major_name || 'ندارد';
    document.getElementById('modalLastScoreDisplay').textContent = requestData.last_score || '';
    document.getElementById('modalSchoolTelephoneDisplay').textContent = requestData.school_telephone || 'وارد نشده';

    // Set English proficiency bar
    const englishPercent = requestData.english_proficiency || 0;
    document.getElementById('modalEnglishBar').style.width = englishPercent + '%';
    document.getElementById('modalEnglishPercent').textContent = englishPercent + '%';

    // Show grade sheet if exists
    if (requestData.gradesheetpath) {
        document.getElementById('modalGradeSheet').classList.remove('hidden');
        document.getElementById('modalGradeSheetImg').src = requestData.gradesheetpath_url;
        document.getElementById('modalGradeSheetLink').href = requestData.gradesheetpath_url;
    } else {
        document.getElementById('modalGradeSheet').classList.add('hidden');
    }

    // Fill housing information
    document.getElementById('modalRentalDisplay').textContent = requestData.rental == '0' ? '🏠 ملکی' : '🏠 استیجاری';
    document.getElementById('modalAddressDisplay').textContent = requestData.address || '';

    // Fill family information
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

    // Fill parents information
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

    // Fill final questions
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

    // Show grade sheet if exists
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

    // Show modal with animation
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Add animation class after display
    setTimeout(() => {
        modal.classList.add('show');

        // Re-initialize editors after modal show with more delay
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
            // Initialize upload handlers last so elements are displayed
            setTimeout(() => {
                initializeProfileImageUpload();
                setTimeout(initializeGradeSheetUpload, 100);
            }, 800);
        }, 500);

        // Start live update for modal
        startModalLiveUpdate(requestData.id);
    }, 10);
}

// Close modal functionality
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('requestDetailModal');
    const closeBtn = document.getElementById('closeRequestDetailModal');

    // Function to close modal with animation
    function closeModal() {
        // Stop modal live update if active
        if (typeof stopModalLiveUpdate === 'function') {
            stopModalLiveUpdate();
        }

        modal.classList.remove('show');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';

            // Hide grade sheet when closing modal
            const gradeSheetDiv = document.getElementById('modalGradeSheet');
            if (gradeSheetDiv) {
                gradeSheetDiv.classList.add('hidden');
            }

            // Update main page after modal close
            updateMainPageAfterModalClose();
        }, 300);
    }

    // Close modal with button
    closeBtn.addEventListener('click', closeModal);

    // Close modal with background click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    // Function to update main page after modal close
    function updateMainPageAfterModalClose() {
        console.log('🔄 Updating main page after modal close...');

        // Check if we're on myrequests page
        if (!window.location.pathname.includes('myrequests')) {
            return;
        }

        // Use AJAX to get new data
        makeAjaxRequest('/unified/myrequests-data')
            .then(data => {
                if (data.success) {
                    console.log('📡 Updating page with new data after modal close...');

                    // Update page with new data
                    updatePageWithNewData(data);

                    // Show update notification
                    showSuccessMessage('اطلاعات بروزرسانی شد');
                } else {
                    console.error('❌ Failed to fetch requests data after modal close:', data);
                }
            })
            .catch(error => {
                console.error('❌ Error updating page after modal close:', error);
            });
    }
});

// Modal live update functions
function startModalLiveUpdate(requestId) {
    if (modalLiveUpdateInterval) {
        stopModalLiveUpdate();
    }

    console.log('🔄 Starting modal live update for request:', requestId);

    // Check immediately at start
    checkModalForUpdates(requestId);

    // Set interval to check every 15 seconds (faster than main page)
    modalLiveUpdateInterval = setInterval(() => {
        checkModalForUpdates(requestId);
    }, 15000);
}

function stopModalLiveUpdate() {
    if (modalLiveUpdateInterval) {
        console.log('⏹️ Stopping modal live update...');
        clearInterval(modalLiveUpdateInterval);
        modalLiveUpdateInterval = null;
        modalLastUpdateTime = null;
        modalIsUpdating = false;
    }
}

function checkModalForUpdates(requestId) {
    if (modalIsUpdating || !requestId) return;

    modalIsUpdating = true;

    makeAjaxRequest(`/unified/get-request-data/${requestId}`)
        .then(data => {
            if (data.success && data.request) {
                // Check if data has changed
                const currentUpdateTime = data.request.updated_at;
                const hasNewData = !modalLastUpdateTime || currentUpdateTime !== modalLastUpdateTime;

                if (hasNewData) {
                    console.log('📡 Modal data changed, updating softly...');
                    modalLastUpdateTime = currentUpdateTime;

                    // Softly update modal content
                    updateModalSoftly(data.request);
                } else {
                    console.log('✅ Modal data is up to date');
                }
            }
        })
        .catch(error => {
            console.error('❌ Error checking modal for updates:', error);
        })
        .finally(() => {
            modalIsUpdating = false;
        });
}

function updateModalSoftly(request) {
    try {
        console.log('🔄 Updating modal softly...');

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
    }
}