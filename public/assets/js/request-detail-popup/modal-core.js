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
    if (requestData.user_id != requestData.this_user_id || requestData.story == 'accept') {
// المان‌هایی که id آنها با "edit" شروع می‌شود و با "lBtn" تمام می‌شود
const editbtns = document.querySelectorAll('[id^="edit"][id$="Btn"]');
        editbtns.forEach(editbtn => {
            editbtn.classList.add('hidden');
        });
    }
    else{
        const editbtns = document.querySelectorAll('[id^="edit"][id$="Btn"]');
        editbtns.forEach(editbtn => {
            editbtn.classList.remove('hidden');
        });
    }

    // Fill profile information
    document.getElementById('modalProfileImg').src = requestData.imgpath_url;
    document.getElementById('modalProfileImg').alt = requestData.name;
    document.getElementById('modalUserName').textContent = requestData.name;
    document.getElementById('modalUserGrade').textContent = 'پایه ' + requestData.grade;
    const requestid =document.querySelectorAll('.requestid');
    requestid.forEach(id => {
        id.value = requestData.id
        console.log('requst id is set')
    });
    // Set status
    const statusBadge = document.getElementById('modalStatusBadge');
    let statusColor = '';
    let statusText = '';

    switch(requestData.story) {
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

    // Fill appointments list
    const appointmentsList = document.getElementById('appointmentsList');
    const appointmentsSection = document.getElementById('appointmentsSection');

    // Show appointments section only for admin users
    if (appointmentsSection && requestData.user_id !== requestData.this_user_id) {
        appointmentsSection.classList.remove('hidden');
    }

    if (requestData.appointments && requestData.appointments.length > 0) {
        let appointmentsHTML = '';
        requestData.appointments.forEach(appointment => {
            // Determine status badge
            let statusBadge = '';
            if (appointment.status === 'scheduled') {
                statusBadge = '<span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">زمان‌بندی شده</span>';
            } else if (appointment.status === 'completed') {
                statusBadge = '<span class="inline-block px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">انجام شده</span>';
            } else if (appointment.status === 'cancelled') {
                statusBadge = '<span class="inline-block px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-full">لغو شده</span>';
            }

            appointmentsHTML += `
                <div class="bg-white rounded-xl p-4 border border-gray-200 hover:border-purple-300 transition-all duration-200 group" data-appointment-id="${appointment.id}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <h4 class="font-semibold text-gray-800">${appointment.title}</h4>
                                ${statusBadge}
                            </div>
                            <p class="text-sm text-gray-600">
                                📅 ${appointment.scheduled_at_jalali}
                                🕐 ${appointment.scheduled_at_time}
                            </p>
                            ${appointment.notes ? `<p class="text-sm text-gray-500 mt-2 italic">📝 ${appointment.notes}</p>` : ''}
                            <p class="text-xs text-gray-400 mt-2">
                                توسط ${appointment.user_name} - ${appointment.updated_at_human}
                            </p>
                        </div>
                        <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <button type="button"
                                onclick="editAppointment(${appointment.id})"
                                class="p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors"
                                title="ویرایش">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button type="button"
                                onclick="deleteAppointment(${appointment.id})"
                                class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors"
                                title="حذف">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        if (appointmentsList) {
            appointmentsList.innerHTML = appointmentsHTML;
        }

        // Update appointments count
        const appointmentsCount = document.getElementById('appointmentsCount');
        if (appointmentsCount) {
            appointmentsCount.textContent = `${requestData.appointments.length} قرار ملاقات`;
        }
    } else {
        // Show empty state
        if (appointmentsList && appointmentsList.parentElement) {
            appointmentsList.parentElement.innerHTML = `
                <div class="text-center py-6">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-gray-500">هنوز قرار ملاقاتی برای این درخواست تعیین نشده است</p>
                </div>
            `;
        }

        // Update appointments count
        const appointmentsCount = document.getElementById('appointmentsCount');
        if (appointmentsCount) {
            appointmentsCount.textContent = '0 قرار ملاقات';
        }
    }

    // Show modal with animation
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Add animation class after display
    setTimeout(() => {
        modal.classList.add('show');

        // Re-initialize editors after modal show with more delay

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

        // // Start live update for modal
        // startModalLiveUpdate(requestData.id);
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

            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';

            // Hide grade sheet when closing modal
            const gradeSheetDiv = document.getElementById('modalGradeSheet');
            if (gradeSheetDiv) {
                gradeSheetDiv.classList.add('hidden');
            }

            // Update main page after modal close
            updateMainPageAfterModalClose();

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


        // Check if we're on myrequests page
        if (!window.location.pathname.includes('myrequests')) {
            return;
        }

        // Use AJAX to get new data
        makeAjaxRequest('/unified/myrequests-data')
            .then(data => {
                if (data.success) {


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
function stopModalLiveUpdate() {
    if (modalLiveUpdateInterval) {

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

                    modalLastUpdateTime = currentUpdateTime;

                    // Softly update modal content
                    updateModalSoftly(data.request);
                } else {

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


    } catch (error) {
        console.error('❌ Error updating modal:', error);
    }
}
