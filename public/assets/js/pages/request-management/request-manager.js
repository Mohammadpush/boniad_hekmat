/**
 * اسکریپت اختصاصی مدیریت درخواست‌ها
 */

class RequestManagement {
    constructor() {
        this.selectedRequests = new Set();
        this.currentFilter = 'all';
        this.currentPage = 1;
        this.searchTerm = '';

        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupBulkActions();
        this.loadRequests();
    }

    setupEventListeners() {
        // Filter tabs
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.addEventListener('click', (e) => {
                this.setActiveFilter(e.target.dataset.filter);
            });
        });

        // Search input
        const searchInput = document.querySelector('.search-input input');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                this.searchTerm = e.target.value;
                this.debounceSearch();
            });
        }

        // Request cards selection
        document.addEventListener('change', (e) => {
            if (e.target.matches('.request-checkbox')) {
                this.toggleRequestSelection(e.target);
            }
        });

        // Quick actions
        document.addEventListener('click', (e) => {
            if (e.target.matches('.quick-action-btn')) {
                this.handleQuickAction(e.target);
            }
        });

        // Bulk actions
        document.addEventListener('click', (e) => {
            if (e.target.matches('.bulk-approve')) {
                this.bulkAction('approve');
            } else if (e.target.matches('.bulk-reject')) {
                this.bulkAction('reject');
            } else if (e.target.matches('.bulk-delete')) {
                this.bulkAction('delete');
            }
        });
    }

    setupBulkActions() {
        // Select all checkbox
        const selectAllCheckbox = document.getElementById('select-all');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', (e) => {
                this.selectAll(e.target.checked);
            });
        }
    }

    setActiveFilter(filter) {
        // Update active tab
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.classList.toggle('active', tab.dataset.filter === filter);
        });

        this.currentFilter = filter;
        this.currentPage = 1;
        this.loadRequests();
    }

    debounceSearch() {
        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(() => {
            this.currentPage = 1;
            this.loadRequests();
        }, 300);
    }

    toggleRequestSelection(checkbox) {
        const requestId = checkbox.value;

        if (checkbox.checked) {
            this.selectedRequests.add(requestId);
        } else {
            this.selectedRequests.delete(requestId);
        }

        this.updateBulkActionsVisibility();
        this.updateSelectAllState();
    }

    selectAll(checked) {
        const checkboxes = document.querySelectorAll('.request-checkbox');

        checkboxes.forEach(checkbox => {
            checkbox.checked = checked;
            this.toggleRequestSelection(checkbox);
        });
    }

    updateBulkActionsVisibility() {
        const bulkActions = document.querySelector('.bulk-actions');
        const selectedCount = document.querySelector('.selected-count');

        if (bulkActions && selectedCount) {
            if (this.selectedRequests.size > 0) {
                bulkActions.classList.remove('hidden');
                selectedCount.textContent = `${this.selectedRequests.size} درخواست انتخاب شده`;
            } else {
                bulkActions.classList.add('hidden');
            }
        }
    }

    updateSelectAllState() {
        const selectAllCheckbox = document.getElementById('select-all');
        const allCheckboxes = document.querySelectorAll('.request-checkbox');

        if (selectAllCheckbox && allCheckboxes.length > 0) {
            const checkedCount = Array.from(allCheckboxes).filter(cb => cb.checked).length;

            selectAllCheckbox.checked = checkedCount === allCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < allCheckboxes.length;
        }
    }

    async handleQuickAction(button) {
        const action = button.dataset.action;
        const requestId = button.dataset.requestId;

        const confirmMessage = this.getConfirmMessage(action);
        if (!confirm(confirmMessage)) return;

        const loading = LoadingManager.show(button);

        try {
            await this.performAction(action, [requestId]);
            this.loadRequests();
            window.toastManager.success(this.getSuccessMessage(action));
        } catch (error) {
            window.toastManager.error('خطا در انجام عملیات');
        } finally {
            loading.hide();
        }
    }

    async bulkAction(action) {
        if (this.selectedRequests.size === 0) {
            window.toastManager.warning('هیچ درخواستی انتخاب نشده است');
            return;
        }

        const confirmMessage = this.getBulkConfirmMessage(action, this.selectedRequests.size);
        if (!confirm(confirmMessage)) return;

        try {
            await this.performAction(action, Array.from(this.selectedRequests));
            this.selectedRequests.clear();
            this.updateBulkActionsVisibility();
            this.loadRequests();
            window.toastManager.success(this.getBulkSuccessMessage(action, this.selectedRequests.size));
        } catch (error) {
            window.toastManager.error('خطا در انجام عملیات');
        }
    }

    async loadRequests() {
        const container = document.querySelector('.requests-container');
        if (!container) return;

        // Show loading state
        container.innerHTML = this.getLoadingHTML();

        try {
            const response = await fetch('/api/requests?' + new URLSearchParams({
                filter: this.currentFilter,
                search: this.searchTerm,
                page: this.currentPage
            }));

            if (!response.ok) throw new Error('Failed to load requests');

            const data = await response.json();

            container.innerHTML = this.renderRequests(data.requests);
            this.updateStats(data.stats);
            this.setupInfiniteScroll(data.hasMore);

        } catch (error) {
            container.innerHTML = this.getErrorHTML();
            console.error('Error loading requests:', error);
        }
    }

    renderRequests(requests) {
        if (requests.length === 0) {
            return this.getEmptyStateHTML();
        }

        return requests.map(request => this.renderRequestCard(request)).join('');
    }

    renderRequestCard(request) {
        const statusClass = `status-${request.status}`;
        const priorityClass = `priority-${request.priority}`;

        return `
            <div class="request-card ${statusClass}">
                <div class="flex items-start justify-between">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" class="request-checkbox" value="${request.id}">
                        <span class="font-medium">${request.student_name}</span>
                    </label>
                    <span class="status-badge badge-${request.status}">
                        ${this.getStatusLabel(request.status)}
                    </span>
                </div>

                <div class="mt-2 space-y-1 text-sm text-gray-600">
                    <div>کد ملی: ${request.national_code}</div>
                    <div>پایه: ${request.grade}</div>
                    <div>تاریخ درخواست: ${request.created_at}</div>
                </div>

                <div class="quick-actions">
                    ${request.status === 'pending' ? `
                        <button class="quick-action-btn approve"
                                data-action="approve"
                                data-request-id="${request.id}">
                            تأیید
                        </button>
                        <button class="quick-action-btn reject"
                                data-action="reject"
                                data-request-id="${request.id}">
                            رد
                        </button>
                    ` : ''}
                    <button class="quick-action-btn"
                            onclick="showRequestDetail(${request.id})">
                        جزئیات
                    </button>
                </div>
            </div>
        `;
    }

    updateStats(stats) {
        Object.keys(stats).forEach(key => {
            const element = document.querySelector(`[data-stat="${key}"]`);
            if (element) {
                element.textContent = stats[key];
            }
        });
    }

    setupInfiniteScroll(hasMore) {
        if (!hasMore) return;

        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                this.currentPage++;
                this.loadMoreRequests();
                observer.disconnect();
            }
        });

        const lastCard = document.querySelector('.request-card:last-child');
        if (lastCard) {
            observer.observe(lastCard);
        }
    }

    async loadMoreRequests() {
        // Similar to loadRequests but append to existing content
        // Implementation details...
    }

    async performAction(action, requestIds) {
        const response = await fetch('/api/requests/bulk-action', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                action,
                request_ids: requestIds
            })
        });

        if (!response.ok) {
            throw new Error('Action failed');
        }

        return response.json();
    }

    getConfirmMessage(action) {
        const messages = {
            approve: 'آیا از تأیید این درخواست اطمینان دارید؟',
            reject: 'آیا از رد این درخواست اطمینان دارید؟',
            delete: 'آیا از حذف این درخواست اطمینان دارید؟'
        };
        return messages[action] || 'آیا از انجام این عملیات اطمینان دارید؟';
    }

    getBulkConfirmMessage(action, count) {
        const messages = {
            approve: `آیا از تأیید ${count} درخواست اطمینان دارید؟`,
            reject: `آیا از رد ${count} درخواست اطمینان دارید؟`,
            delete: `آیا از حذف ${count} درخواست اطمینان دارید؟`
        };
        return messages[action] || `آیا از انجام این عملیات روی ${count} درخواست اطمینان دارید؟`;
    }

    getSuccessMessage(action) {
        const messages = {
            approve: 'درخواست با موفقیت تأیید شد',
            reject: 'درخواست با موفقیت رد شد',
            delete: 'درخواست با موفقیت حذف شد'
        };
        return messages[action] || 'عملیات با موفقیت انجام شد';
    }

    getBulkSuccessMessage(action, count) {
        const messages = {
            approve: `${count} درخواست با موفقیت تأیید شد`,
            reject: `${count} درخواست با موفقیت رد شد`,
            delete: `${count} درخواست با موفقیت حذف شد`
        };
        return messages[action] || `عملیات روی ${count} درخواست با موفقیت انجام شد`;
    }

    getStatusLabel(status) {
        const labels = {
            pending: 'در انتظار بررسی',
            approved: 'تأیید شده',
            rejected: 'رد شده',
            'in-review': 'در حال بررسی'
        };
        return labels[status] || status;
    }

    getLoadingHTML() {
        return `
            <div class="flex justify-center items-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="mr-2">در حال بارگذاری...</span>
            </div>
        `;
    }

    getErrorHTML() {
        return `
            <div class="text-center py-8 text-red-600">
                <svg class="w-12 h-12 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <p>خطا در بارگذاری درخواست‌ها</p>
            </div>
        `;
    }

    getEmptyStateHTML() {
        return `
            <div class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 6a2 2 0 104 0 2 2 0 00-4 0zm6 0a2 2 0 104 0 2 2 0 00-4 0z" clip-rule="evenodd"></path>
                </svg>
                <p>هیچ درخواستی یافت نشد</p>
            </div>
        `;
    }
}

// Global function for request detail
function showRequestDetail(requestId) {
    window.location.href = `/admin/requests/${requestId}`;
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new RequestManagement();
});
