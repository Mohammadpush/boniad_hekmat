// اسکریپت مدیریت پیام‌ها - طراحی تلگرامی
document.addEventListener('DOMContentLoaded', function() {
    initRequestSearch();
    initRequestSelection();
    initMessageInput();
    initStoryTypeModal();
    scrollToBottom();
});

// جستجو در لیست درخواست‌ها
function initRequestSearch() {
    const searchInput = document.getElementById('searchRequests');
    if (!searchInput) return;

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        const requestItems = document.querySelectorAll('.request-item');

        requestItems.forEach(item => {
            const name = item.dataset.requestName?.toLowerCase() || '';

            if (searchTerm === '' || name.includes(searchTerm)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // پاک کردن جستجو با Escape
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            this.value = '';
            this.dispatchEvent(new Event('input'));
            this.blur();
        }
    });
}

// انتخاب درخواست و تعویض چت
function initRequestSelection() {
    const requestItems = document.querySelectorAll('.request-item');

    requestItems.forEach(item => {
        item.addEventListener('click', function() {
            const requestId = this.dataset.requestId;

            // حذف کلاس active از همه آیتم‌ها
            requestItems.forEach(i => i.classList.remove('active'));

            // اضافه کردن active به آیتم انتخاب شده
            this.classList.add('active');

            // انتقال به صفحه پیام این درخواست
            window.location.href = `/unified/message/${requestId}`;
        });
    });
}

// مدیریت textarea پیام
function initMessageInput() {
    const textarea = document.getElementById('messageInput');
    if (!textarea) return;

    // Auto-resize textarea
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });

    // ارسال با Ctrl+Enter یا Cmd+Enter
    textarea.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('messageForm').submit();
        }
    });

    // جلوگیری از ارسال فرم با Enter (فقط newline اضافه می‌کند)
    textarea.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey && !e.ctrlKey && !e.metaKey) {
            // در حالت عادی Enter خط جدید اضافه می‌کند
            // برای ارسال باید Ctrl+Enter زد
        }
    });
}

// مدیریت modal انتخاب نوع پیام
function initStoryTypeModal() {
    const storyTypeButton = document.getElementById('storyTypeButton');
    const storyTypeModal = document.getElementById('storyTypeModal');
    const confirmButton = document.getElementById('confirmStoryType');
    const cancelButton = document.getElementById('cancelStoryType');
    const storyInput = document.getElementById('storyInput');
    const priceInput = document.getElementById('priceInput');
    const priceInputModal = document.getElementById('priceInputModal');
    const priceSection = document.getElementById('priceSection');
    const storyOptions = document.querySelectorAll('.story-option');

    if (!storyTypeButton || !storyTypeModal) return;

    let selectedStory = 'message';

    // باز کردن modal
    storyTypeButton.addEventListener('click', function() {
        storyTypeModal.classList.remove('hidden');

        // تنظیم story فعلی
        storyOptions.forEach(option => {
            if (option.dataset.story === selectedStory) {
                option.classList.add('active');
            } else {
                option.classList.remove('active');
            }
        });

        // نمایش/مخفی کردن بخش مبلغ
        if (selectedStory === 'scholarship') {
            priceSection.classList.remove('hidden');
        } else {
            priceSection.classList.add('hidden');
        }
    });

    // انتخاب نوع story
    storyOptions.forEach(option => {
        option.addEventListener('click', function() {
            // حذف active از همه
            storyOptions.forEach(opt => opt.classList.remove('active'));

            // اضافه کردن active به گزینه انتخاب شده
            this.classList.add('active');
            selectedStory = this.dataset.story;

            // نمایش بخش مبلغ برای بورسیه
            if (selectedStory === 'scholarship') {
                priceSection.classList.remove('hidden');
            } else {
                priceSection.classList.add('hidden');
            }
        });
    });

    // تایید انتخاب
    confirmButton.addEventListener('click', function() {
        storyInput.value = selectedStory;

        // تنظیم مبلغ
        if (selectedStory === 'scholarship' && priceInputModal.value) {
            priceInput.value = priceInputModal.value;
        } else {
            priceInput.value = '';
        }

        // بستن modal
        storyTypeModal.classList.add('hidden');

        // تغییر آیکون دکمه بر اساس نوع انتخاب شده
        updateStoryButtonIcon(selectedStory);
    });

    // انصراف
    cancelButton.addEventListener('click', function() {
        storyTypeModal.classList.add('hidden');
    });

    // بستن modal با کلیک خارج از آن
    storyTypeModal.addEventListener('click', function(e) {
        if (e.target === storyTypeModal) {
            storyTypeModal.classList.add('hidden');
        }
    });

    // بستن modal با Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !storyTypeModal.classList.contains('hidden')) {
            storyTypeModal.classList.add('hidden');
        }
    });
}

// تغییر آیکون دکمه بر اساس نوع پیام انتخاب شده
function updateStoryButtonIcon(storyType) {
    const storyButton = document.getElementById('storyTypeButton');
    if (!storyButton) return;

    const iconMap = {
        'message': '💬',
        'thanks': '🙏',
        'warning': '⚠️',
        'scholarship': '🎓'
    };

    const colorMap = {
        'message': '#3b82f6',
        'thanks': '#60a5fa',
        'warning': '#eab308',
        'scholarship': '#10b981'
    };

    // تغییر رنگ background
    storyButton.style.background = colorMap[storyType] || '#f3f4f6';

    // اگر پیام عادی نیست، رنگ متن سفید باشد
    if (storyType !== 'message') {
        storyButton.style.color = 'white';
    } else {
        storyButton.style.color = '#6b7280';
    }
}

// اسکرول به آخرین پیام
function scrollToBottom() {
    const messagesContainer = document.getElementById('messagesContainer');
    if (!messagesContainer) return;

    setTimeout(() => {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }, 100);
}

// نمایش پیام موفقیت/خطا
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;

    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : '#ef4444'};
        color: white;
        padding: 16px 24px;
        border-radius: 12px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        animation: slideInRight 0.3s ease-out;
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease-in';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Mobile: مخفی/نمایش sidebar
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    if (!sidebar) return;

    if (window.innerWidth <= 768) {
        sidebar.classList.toggle('hidden-mobile');
    }
}

// Event listener برای تغییر اندازه صفحه
window.addEventListener('resize', function() {
    const sidebar = document.querySelector('.sidebar');
    if (sidebar && window.innerWidth > 768) {
        sidebar.classList.remove('hidden-mobile');
    }
});

// نمایش پیام‌های سیستمی (اگر در session باشد)
window.addEventListener('load', function() {
    const successMessage = document.querySelector('.alert-success');
    const errorMessage = document.querySelector('.alert-error');

    if (successMessage) {
        showNotification(successMessage.textContent, 'success');
    }

    if (errorMessage) {
        showNotification(errorMessage.textContent, 'error');
    }
});
