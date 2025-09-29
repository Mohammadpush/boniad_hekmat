// اسکریپت مخصوص صفحه پیام‌ها
document.addEventListener('DOMContentLoaded', function() {
    // Initialize message search
    initMessageSearch();

    // Initialize modal handlers
    initModalHandlers();

    // Initialize smart navigation
    initSmartNavigation();
});

function initMessageSearch() {
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) return;

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        const messageContainers = document.querySelectorAll('.space-y-4 > div');

        messageContainers.forEach(container => {
            const title = container.querySelector('.font-bold')?.textContent.toLowerCase() || '';
            const description = container.querySelector('.text-sm')?.textContent.toLowerCase() || '';

            if (searchTerm === '' || title.includes(searchTerm) || description.includes(searchTerm)) {
                container.style.display = '';
                container.classList.remove('hidden');
            } else {
                container.style.display = 'none';
                container.classList.add('hidden');
            }
        });

        // Show "no results" message if needed
        updateNoResultsMessage(searchTerm);
    });

    // Clear search on Escape
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            this.value = '';
            this.dispatchEvent(new Event('input'));
            this.blur();
        }
    });
}

function updateNoResultsMessage(searchTerm) {
    const messageContainer = document.querySelector('.space-y-4');
    const visibleMessages = document.querySelectorAll('.space-y-4 > div:not([style*="display: none"])');

    let noResultsDiv = document.getElementById('noResultsMessage');

    if (searchTerm && visibleMessages.length === 0) {
        if (!noResultsDiv) {
            noResultsDiv = document.createElement('div');
            noResultsDiv.id = 'noResultsMessage';
            noResultsDiv.className = 'text-center py-8';
            noResultsDiv.innerHTML = `
                <div class="text-gray-400 mb-2">
                    <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <p class="text-gray-600">نتیجه‌ای برای جستجوی شما یافت نشد.</p>
                <p class="text-sm text-gray-500 mt-1">لطفاً کلمات کلیدی دیگری امتحان کنید.</p>
            `;
            messageContainer.appendChild(noResultsDiv);
        } else {
            noResultsDiv.style.display = '';
        }
    } else if (noResultsDiv) {
        noResultsDiv.style.display = 'none';
    }
}

function initModalHandlers() {
    // Message modal handlers
    const messageModal = document.getElementById('messageModal');
    if (!messageModal) return;

    // Close modal when clicking outside
    messageModal.addEventListener('click', function(e) {
        if (e.target === messageModal) {
            closeMessageModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !messageModal.classList.contains('hidden')) {
            closeMessageModal();
        }
    });
}

function openMessageModal(title, content, price = null) {
    const modal = document.getElementById('messageModal');
    const modalContent = document.getElementById('messageContent');

    if (!modal || !modalContent) return;

    let fullContent = '';
    if (title) {
        fullContent += `<h4 class="font-bold text-lg mb-3 text-blue-600">${title}</h4>`;
    }
    fullContent += `<p class="text-gray-700 leading-relaxed">${content}</p>`;
    if (price) {
        fullContent += `<div class="mt-4 p-3 bg-yellow-50 border-r-4 border-yellow-400 rounded">
            <p class="text-sm text-yellow-800"><strong>مبلغ:</strong> ${price} تومان</p>
        </div>`;
    }

    modalContent.innerHTML = fullContent;
    modal.classList.remove('hidden');
    modal.classList.add('show');
}

function closeMessageModal() {
    const modal = document.getElementById('messageModal');
    if (!modal) return;

    modal.classList.add('hidden');
    modal.classList.remove('show');
}

function initSmartNavigation() {
    // Smart back navigation
    window.smartGoBack = function() {
        // بررسی تعداد صفحات موجود در history
        if (window.history.length > 1) {
            // بررسی آدرس فعلی
            const currentUrl = window.location.href;

            // اگر از addmessage اومدیم، دو قدم برگرد
            if (document.referrer.includes('addmessage')) {
                window.history.go(-2);
            } else {
                // در غیر این صورت یک قدم
                window.history.back();
            }
        } else {
            // اگر history خالی است، به صفحه پیش‌فرض برو
            window.location.href = '/unified/allrequests';
        }
    };
}

// Helper function to format numbers
function formatPrice(price) {
    return new Intl.NumberFormat('fa-IR').format(price);
}

// Add click handlers for message bubbles to show full content
function addMessageClickHandlers() {
    document.addEventListener('click', function(e) {
        const messageDiv = e.target.closest('.message-bubble, .bg-\\[\\#9faeff\\], .bg-\\[\\#c5bebe\\]');
        if (!messageDiv) return;

        const title = messageDiv.querySelector('.font-bold')?.textContent || '';
        const description = messageDiv.querySelector('.text-sm')?.textContent || '';
        const priceElement = messageDiv.querySelector('.opacity-75');
        const price = priceElement ? priceElement.textContent.replace('مبلغ: ', '').replace(' تومان', '') : null;

        if (description.length > 100 || title.length > 50) {
            openMessageModal(title, description, price);
        }
    });
}

// Initialize click handlers when DOM is ready
document.addEventListener('DOMContentLoaded', addMessageClickHandlers);
