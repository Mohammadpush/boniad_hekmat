/**
 * Real-time Notification System
 * Checks for new messages and displays notifications
 */

class NotificationSystem {
    constructor() {
        this.pollingInterval = 3000; // ✅ 3 seconds (همزمان با real-time-updates.js)
        this.intervalId = null;
        this.lastNotificationTime = 0; // ✅ صفر تا اولین نوتیفیکیشن بلافاصله نمایش داده شود
        this.previousUnreadCount = 0; // ✅ برای تشخیص پیام جدید
        this.audioEnabled = true;

        // Initialize notification sound with a simple beep (data URL)
        // You can replace this with a real audio file path
        this.notificationSound = new Audio('data:audio/wav;base64,UklGRhQDAABXQVZFZm10IBAAAAABAAEARKwAAIhYAQACABAAZGF0YfACAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBTGH0fPTgjMGHm7A7+OZSA0PVKzn77BdGAg+mNr0yXEpBSp9yvLaizsIGGS57OihUQ0LTKXh8bllHAU2jdXzzn0uBSd2xPDdlkELElyw6OysWBUIQ5zd88p2KwUme8rx3I4/ChVbtOjurVoXCECY3PTKcysFKHnJ8N6PQAoUXbTo7ayWHwU0iM/yz34vBSV1w+/emkIKElyv5+6vXBcJPZPY8sp0LAUndsjw3I8/ChZas+jurVsYCT+V2PPLdSsFKHfG8N2PRgoTW7Ln7q5bGQg+lNfzy3YrBSh1xPDejj8LElux5+6uWxgJPZPY88t1KwUodcbw3Y5AChRas+jurl0YCT6U1/PKdywFKHXE8N6OQQoTWrLn7q1cGAk9lNjzy3UrBSh1xvDdjkEKE1qy5+6uWxgIPZPY88p1LAUodcTv3o5BChNasufsrlwYCT2T2PPLdSsFKHXE8N6OQQoSWrLn7q5cGAo9lNjzy3YrBSh1xPDejkEKElqz6O6uWxgKPpTX88p2KwUodMTw3Y9AChJasufsrlsYCj2U2PPKdSsFKHXE8N6OQQoSWrLn7q5bGAk9k9jzy3UrBSh1xvDdjkAKElqy5+6uWxgJPZPY88t1KwUodMTw3Y5BChNasufsrlsYCT6T2PPLdSsFKHXE8N+OQAoSWrPo7q5cFwk9lNjzy3YrBSh1xPDdjkEKElqy5+6uWxgJPZPY88t1KwUodcXw3Y5AChJas+jurl0YCT6U1/PKdywFKHXE8N6OPwoTWrLn7q1bGAk+k9jzy3UrBSh1xPDejkEKElqz5+6uWxgJPpPY88t1KwUodMTw3o5BChJasufsrlsYCT2T2PPKdSwFKHXE8N+OQAoSWrLn7q5bGAk+k9jzy3YrBSh1xPDdjkEKElqy5+6uWxgJPZPY88t1KwUodcTw3o5BChJas+jurl0YCT6U1/PKdywFKHXE8N6OQQoSWrLn7q5bGAk9k9jzy3UrBSh2xPDdjkEKElqy5+6uWxgJPZPX88t1LAUodcTw3Y5BChJasufsrlsYCT2T2PPLdSsFKHXF8N2OQAoSWrLo7q5cFwk+lNfzy3YrBSh1xPDdjkAKE1qy5+6uWxgJPZPY88t1KwUodcbw3Y5AChJasufsrlsYCT2T2PPLdSsFKHXE8N6OQQoSWrLn7q5bGAk9k9jzy3UrBSh1xPDejkEKElqy5+6uWxgJPZPY88t2KwUodcTw3o5BChJas+jurl0YCT6U1/PKdywFKHXE8N6OQQoSWrLn7q5bGAk9k9jzy3UrBSh1xPDdjkEKElqy5+6uWxgJPZPY88t1KwUodcTw3o5BChJas+jurl0YCT6U1/PKdiwFKHXE8N6OQQoTWrLn7q5bGAk9k9jzy3UsB');

        this.init();
    }

    init() {
        console.log('🔔 Initializing Notification System...');

        // Request notification permission
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission().then(permission => {
                console.log(`🔔 Notification permission: ${permission}`);
            });
        } else if ('Notification' in window) {
            console.log(`🔔 Notification permission: ${Notification.permission}`);
        }

        // ⚠️ حذف startPolling() - الان فقط از طریق صفحه message فراخوانی می‌شود
        // یا از طریق forceUpdate() در صفحات دیگر

        // ✅ اولین چک فوری (و بدون نوتیفیکیشن برای جلوگیری از spam در load اول)
        this.checkUnreadMessagesInitial();

        // ✅ برای صفحاتی که message updater ندارند، polling فعال می‌شود
        // این polling فقط در صفحات غیر از message فعال است
        const isMessagePage = window.location.pathname.includes('/unified/message/');
        if (!isMessagePage) {
            console.log('🔔 Non-message page detected: Starting polling');
            this.startPolling();
        } else {
            console.log('🔔 Message page detected: Polling will be handled by MessageUpdater');
        }

        // Stop/Start polling when tab visibility changes
        document.addEventListener('visibilitychange', () => {
            const isMessagePage = window.location.pathname.includes('/unified/message/');
            if (document.hidden) {
                console.log('🔔 Tab hidden: Stopping polling');
                this.stopPolling();
            } else {
                console.log('🔔 Tab visible: Resuming...');
                // فقط اگر در صفحه message نیستیم، polling را شروع کن
                if (!isMessagePage) {
                    this.startPolling();
                }
                this.checkUnreadMessages(); // چک فوری بعد از بازگشت
            }
        });

        console.log('✅ Notification System initialized!');
    }

    /**
     * چک اولیه بدون نمایش نوتیفیکیشن (فقط مقداردهی)
     */
    async checkUnreadMessagesInitial() {
        try {
            const response = await fetch('/unified/api/unread-count', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            });

            if (!response.ok) throw new Error('Failed to fetch unread count');

            const data = await response.json();

            if (data.success) {
                // ✅ فقط مقداردهی previousUnreadCount
                this.previousUnreadCount = data.total_unread;
                this.updateBadge(data.total_unread);
                console.log(`🔔 Initial unread count: ${data.total_unread}`);
            }
        } catch (error) {
            console.error('❌ Error in initial check:', error);
        }
    }

    startPolling() {
        if (this.intervalId) return;

        this.intervalId = setInterval(() => {
            this.checkUnreadMessages();
        }, this.pollingInterval);
    }

    stopPolling() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
            this.intervalId = null;
        }
    }

    async checkUnreadMessages() {
        try {
            const response = await fetch('/unified/api/unread-count', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            });

            if (!response.ok) throw new Error('Failed to fetch unread count');

            const data = await response.json();

            if (data.success) {
                const currentUnreadCount = data.total_unread;

                // بروزرسانی Badge
                this.updateBadge(currentUnreadCount);

                // ✅ فقط اگر تعداد افزایش یافت، نوتیفیکیشن نشان بده
                if (currentUnreadCount > this.previousUnreadCount) {
                    const newMessagesCount = currentUnreadCount - this.previousUnreadCount;
                    console.log(`🔔 New messages detected: ${newMessagesCount}`);

                    // نمایش نوتیفیکیشن فوری
                    this.showNotification(newMessagesCount);
                }

                // ذخیره تعداد فعلی برای مقایسه در iteration بعدی
                this.previousUnreadCount = currentUnreadCount;

                // Dispatch custom event for other components
                window.dispatchEvent(new CustomEvent('unreadCountUpdated', {
                    detail: {
                        total: currentUnreadCount,
                        perRequest: data.unread_per_request
                    }
                }));
            }
        } catch (error) {
            console.error('Error checking unread messages:', error);
        }
    }

    updateBadge(count) {
        console.log(`🔔 Updating badge: ${count}`);

        // Update sidebar badge (Desktop)
        const sidebarBadge = document.querySelector('#messagesBadge');
        if (sidebarBadge) {
            if (count > 0) {
                sidebarBadge.textContent = count > 99 ? '99+' : count;
                sidebarBadge.style.display = 'flex'; // ✅ استفاده از style.display به جای classList
            } else {
                sidebarBadge.style.display = 'none';
            }
            console.log('✅ Desktop badge updated');
        } else {
            console.log('⚠️ Desktop badge not found');
        }

        // Update mobile menu badge
        const mobileBadge = document.querySelector('#mobileMessagesBadge');
        if (mobileBadge) {
            if (count > 0) {
                mobileBadge.textContent = count > 99 ? '99+' : count;
                mobileBadge.style.display = 'flex'; // ✅ استفاده از style.display
            } else {
                mobileBadge.style.display = 'none';
            }
            console.log('✅ Mobile badge updated');
        } else {
            console.log('⚠️ Mobile badge not found');
        }

        // Update page title
        if (count > 0) {
            document.title = `(${count}) پیام جدید - پنل مدیریت`;
        } else {
            document.title = 'پنل مدیریت';
        }

        // Update favicon with badge
        this.updateFavicon(count);
    }

    updateFavicon(count) {
        // Create a canvas to draw notification badge on favicon
        const canvas = document.createElement('canvas');
        canvas.width = 32;
        canvas.height = 32;
        const ctx = canvas.getContext('2d');

        // Draw base favicon (you can load your actual favicon here)
        ctx.fillStyle = '#3B82F6';
        ctx.fillRect(0, 0, 32, 32);

        if (count > 0) {
            // Draw red badge
            ctx.fillStyle = '#EF4444';
            ctx.beginPath();
            ctx.arc(24, 8, 8, 0, 2 * Math.PI);
            ctx.fill();

            // Draw count
            ctx.fillStyle = '#FFFFFF';
            ctx.font = 'bold 10px Arial';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(count > 9 ? '9+' : count.toString(), 24, 8);
        }

        // Update favicon
        const link = document.querySelector("link[rel*='icon']") || document.createElement('link');
        link.type = 'image/x-icon';
        link.rel = 'shortcut icon';
        link.href = canvas.toDataURL();
        document.getElementsByTagName('head')[0].appendChild(link);
    }

    showNotification(newCount) {
        // ✅ حذف محدودیت زمانی - اکنون هر پیام جدید نوتیفیکیشن می‌دهد
        // اما فقط اگر تب مخفی نباشد (برای جلوگیری از spam)
        if (document.hidden) {
            console.log('🔔 Tab is hidden, notification will be shown when tab is visible');
            return;
        }

        console.log(`🔔 Showing notification for ${newCount} new message(s)`);

        // Play sound
        if (this.audioEnabled) {
            this.notificationSound.play().catch(e => console.log('⚠️ Audio play failed:', e));
        }

        // Show browser notification
        if ('Notification' in window && Notification.permission === 'granted') {
            const body = newCount === 1
                ? 'یک پیام جدید دریافت کردید'
                : `${newCount} پیام جدید دریافت کردید`;

            const notification = new Notification('💬 پیام جدید', {
                body: body,
                icon: '/assets/images/logo.png',
                badge: '/assets/images/badge.png',
                tag: 'new-message-' + Date.now(), // ✅ Tag منحصر به فرد برای هر نوتیفیکیشن
                renotify: true, // ✅ همیشه نوتیفیکیشن نشان بده
                requireInteraction: false
            });

            // کلیک روی نوتیفیکیشن
            notification.onclick = () => {
                window.focus();
                window.location.href = '/unified/message';
                notification.close();
            };
        }

        // Show in-page notification
        this.showInPageNotification(newCount);
    }

    showInPageNotification(newCount) {
        // ✅ اجازه بده چندین نوتیفیکیشن نمایش داده شود (stack می‌شوند)
        // اما نوتیفیکیشن‌های قدیمی را حذف کن
        const existingNotifications = document.querySelectorAll('.in-page-notification');
        existingNotifications.forEach((notif, index) => {
            // نوتیفیکیشن‌هایی که بیش از 3 ثانیه قدیمی هستند را حذف کن
            const age = Date.now() - parseInt(notif.dataset.timestamp || 0);
            if (age > 3000) {
                notif.style.animation = 'slideOutRight 0.3s ease-out';
                setTimeout(() => notif.remove(), 300);
            } else {
                // نوتیفیکیشن‌های جدیدتر را به سمت پایین جابجا کن
                notif.style.top = `${20 + (index + 1) * 90}px`;
            }
        });

        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'in-page-notification';
        notification.dataset.timestamp = Date.now();

        const messageText = newCount === 1
            ? 'یک پیام جدید دریافت کردید'
            : `${newCount} پیام جدید دریافت کردید`;

        notification.innerHTML = `
            <div class="flex items-center gap-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-4 rounded-lg shadow-2xl">
                <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                <div class="flex-1">
                    <p class="font-bold text-lg">💬 پیام جدید</p>
                    <p class="text-sm opacity-90">${messageText}</p>
                </div>
                <a href="/unified/message" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 transition font-semibold">
                    مشاهده
                </a>
                <button class="text-white hover:text-blue-200 transition ml-2" onclick="this.parentElement.parentElement.remove()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        `;

        // Add styles
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            animation: slideInRight 0.3s ease-out, pulse 0.5s ease-out;
        `;

        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }

    toggleSound() {
        this.audioEnabled = !this.audioEnabled;
        console.log(`🔔 Sound ${this.audioEnabled ? 'enabled' : 'disabled'}`);
        return this.audioEnabled;
    }

    /**
     * بروزرسانی دستی (برای استفاده از سایر اسکریپت‌ها)
     */
    forceUpdate() {
        console.log('🔔 Force update triggered');
        this.checkUnreadMessages();
    }

    /**
     * ✅ بروزرسانی مستقیم از داده‌های دریافتی (بدون درخواست جدید)
     * این متد توسط MessageUpdater فراخوانی می‌شود
     *
     * @param {Object} data - داده‌های دریافتی از API
     * @param {number} data.total_unread - تعداد کل پیام‌های ناخوانده
     * @param {boolean} showNotif - آیا نوتیفیکیشن نمایش داده شود؟
     */
    updateFromMessageData(data, showNotif = false) {
        if (!data || typeof data.total_unread === 'undefined') {
            console.error('❌ Invalid data provided to updateFromMessageData');
            return;
        }

        const currentUnreadCount = data.total_unread;
        console.log(`🔔 Update from message data: ${currentUnreadCount} unread`);

        // بروزرسانی Badge
        this.updateBadge(currentUnreadCount);

        // اگر تعداد افزایش یافت و showNotif فعال است
        if (showNotif && currentUnreadCount > this.previousUnreadCount) {
            const newMessagesCount = currentUnreadCount - this.previousUnreadCount;
            console.log(`🔔 New messages detected: ${newMessagesCount}`);
            this.showNotification(newMessagesCount);
        }

        // ذخیره تعداد فعلی
        this.previousUnreadCount = currentUnreadCount;

        // Dispatch custom event
        window.dispatchEvent(new CustomEvent('unreadCountUpdated', {
            detail: {
                total: currentUnreadCount,
                perRequest: data.unread_per_request || {}
            }
        }));
    }
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }
`;
document.head.appendChild(style);

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.notificationSystem = new NotificationSystem();
    });
} else {
    window.notificationSystem = new NotificationSystem();
}
