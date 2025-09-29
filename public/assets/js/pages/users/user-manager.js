// اسکریپت مخصوص صفحه کاربران
document.addEventListener('DOMContentLoaded', function() {
    // Initialize user search
    initUserSearch();

    // Initialize modal handlers
    initModalHandlers();

    // Initialize action confirmations
    initActionConfirmations();

    // Initialize table enhancements
    initTableEnhancements();
});

function initUserSearch() {
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) return;

    let searchTimeout;

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();

        // Clear previous timeout
        clearTimeout(searchTimeout);

        // Debounce search to improve performance
        searchTimeout = setTimeout(() => {
            performSearch(searchTerm);
        }, 300);
    });

    // Clear search on Escape
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            this.value = '';
            performSearch('');
            this.blur();
        }
    });
}

function performSearch(searchTerm) {
    const tableRows = document.querySelectorAll('tbody tr');
    let visibleCount = 0;

    tableRows.forEach(row => {
        // Skip if it's an empty state row
        if (row.querySelector('[colspan]')) return;

        // Get text content from different cells
        const cells = row.querySelectorAll('td');
        let textContent = '';

        cells.forEach(cell => {
            textContent += cell.textContent.toLowerCase() + ' ';
        });

        if (searchTerm === '' || textContent.includes(searchTerm)) {
            row.style.display = '';
            row.classList.remove('hidden');
            visibleCount++;
        } else {
            row.style.display = 'none';
            row.classList.add('hidden');
        }
    });

    // Show/hide no results message
    updateNoResultsMessage(searchTerm, visibleCount);
}

function updateNoResultsMessage(searchTerm, visibleCount) {
    const tbody = document.querySelector('tbody');
    let noResultsRow = document.getElementById('noResultsRow');

    if (searchTerm && visibleCount === 0) {
        if (!noResultsRow) {
            noResultsRow = document.createElement('tr');
            noResultsRow.id = 'noResultsRow';
            noResultsRow.innerHTML = `
                <td colspan="6" class="text-center py-12">
                    <div class="empty-state">
                        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <p class="text-lg font-medium text-gray-900 mb-2">نتیجه‌ای یافت نشد</p>
                        <p class="text-gray-500">لطفاً کلمات کلیدی دیگری امتحان کنید.</p>
                    </div>
                </td>
            `;
            tbody.appendChild(noResultsRow);
        } else {
            noResultsRow.style.display = '';
        }
    } else if (noResultsRow) {
        noResultsRow.style.display = 'none';
    }
}

function initModalHandlers() {
    const roleModal = document.getElementById('roleModal');
    if (!roleModal) return;

    // Close modal when clicking outside
    roleModal.addEventListener('click', function(e) {
        if (e.target === roleModal) {
            closeRoleModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !roleModal.classList.contains('hidden')) {
            closeRoleModal();
        }
    });
}

function initActionConfirmations() {
    // Enhance delete confirmations
    const deleteButtons = document.querySelectorAll('button[onclick*="confirm"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            showDeleteConfirmation(this.closest('form'));
        });
    });
}

function showDeleteConfirmation(form) {
    const userRow = form.closest('tr');
    const userName = userRow.querySelector('td:first-child')?.textContent?.trim() || 'این کاربر';

    const confirmModal = createConfirmModal(
        'حذف کاربر',
        `آیا از حذف کاربر "${userName}" مطمئن هستید؟ این عمل قابل بازگردانی نیست.`,
        'حذف کاربر',
        'btn-danger',
        () => {
            form.submit();
        }
    );

    document.body.appendChild(confirmModal);
    confirmModal.classList.remove('hidden');
    confirmModal.classList.add('flex');
}

function createConfirmModal(title, message, confirmText, confirmClass, onConfirm) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 modal-content">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">${title}</h3>
            </div>
            <p class="text-gray-600 mb-6">${message}</p>
            <div class="flex justify-end space-x-4 space-x-reverse">
                <button type="button" class="btn-cancel px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                    انصراف
                </button>
                <button type="button" class="btn-confirm px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                    ${confirmText}
                </button>
            </div>
        </div>
    `;

    // Add event listeners
    const cancelBtn = modal.querySelector('.btn-cancel');
    const confirmBtn = modal.querySelector('.btn-confirm');

    const closeModal = () => {
        modal.classList.add('hidden');
        document.body.removeChild(modal);
    };

    cancelBtn.addEventListener('click', closeModal);
    confirmBtn.addEventListener('click', () => {
        closeModal();
        onConfirm();
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    return modal;
}

function initTableEnhancements() {
    // Add loading state for forms
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.disabled) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="animate-spin h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    در حال پردازش...
                `;
            }
        });
    });

    // Add tooltips for action buttons
    addActionTooltips();
}

function addActionTooltips() {
    const actionButtons = document.querySelectorAll('.action-btn');
    actionButtons.forEach(button => {
        const svg = button.querySelector('svg');
        if (!svg) return;

        let tooltipText = '';
        if (button.closest('a[href*="userdetail"]')) {
            tooltipText = 'مشاهده جزئیات';
        } else if (button.closest('form[action*="deleteuser"]')) {
            tooltipText = 'حذف کاربر';
        } else if (button.closest('a[href*="admin"]')) {
            tooltipText = button.closest('a').href.includes('nadmin') ? 'لغو دسترسی ادمین' : 'ارتقا به ادمین';
        }

        if (tooltipText) {
            button.title = tooltipText;
            button.setAttribute('data-tooltip', tooltipText);
        }
    });
}

// Role modal functions (if modal exists)
function changeRole(userId, currentRole) {
    const roleForm = document.getElementById('roleForm');
    const roleSelect = document.getElementById('role');
    const roleModal = document.getElementById('roleModal');

    if (!roleForm || !roleSelect || !roleModal) return;

    roleForm.action = `/unified/users/${userId}/role`;
    roleSelect.value = currentRole;
    roleModal.classList.remove('hidden');
    roleModal.classList.add('flex');
}

function closeRoleModal() {
    const roleModal = document.getElementById('roleModal');
    if (!roleModal) return;

    roleModal.classList.add('hidden');
    roleModal.classList.remove('flex');
}

// Utility functions
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Export functions for global use
window.changeRole = changeRole;
window.closeRoleModal = closeRoleModal;
