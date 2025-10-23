class MessageUpdater {
    constructor(requestId) {
        this.requestId = requestId;
        this.pollingInterval = 3000; // 3 seconds
        this.intervalId = null;
        this.lastMessageId = 0;
        this.isRunning = false;
        this.lastUnreadCheckTime = 0; // ✅ برای کنترل فرکانس چک ناخوانده‌ها

        this.init();
    }

    init() {
        // Get the last message ID from the page
        const messages = document.querySelectorAll('[data-message-id]');
        if (messages.length > 0) {
            const lastMessage = messages[messages.length - 1];
            this.lastMessageId = parseInt(lastMessage.dataset.messageId) || 0;
        }

        // Start polling
        this.start();

        // Stop polling when tab is not visible
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.stop();
            } else {
                this.start();
            }
        });

        // Stop polling when user leaves the page
        window.addEventListener('beforeunload', () => {
            this.stop();
        });
    }

    start() {
        if (this.isRunning) return;
        this.isRunning = true;

        this.checkNewMessages();
        this.intervalId = setInterval(() => {
            this.checkNewMessages();

            // ✅ هر 9 ثانیه یکبار (هر 3 iteration) تعداد ناخوانده کل را چک کن
            const now = Date.now();
            if (now - this.lastUnreadCheckTime > 9000) {
                this.checkGlobalUnreadCount();
                this.lastUnreadCheckTime = now;
            }
        }, this.pollingInterval);
    }

    stop() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
            this.intervalId = null;
        }
        this.isRunning = false;
    }

    async checkNewMessages() {
        try {
            const response = await fetch(`/unified/api/new-messages/${this.requestId}?last_message_id=${this.lastMessageId}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            });

            if (!response.ok) throw new Error('Failed to fetch new messages');

            const data = await response.json();

            if (data.success && data.messages.length > 0) {
                console.log(`📨 Received ${data.messages.length} new message(s)`);

                data.messages.forEach(message => {
                    this.addMessageToUI(message);
                    this.lastMessageId = Math.max(this.lastMessageId, message.id);
                });

                // Scroll to bottom
                this.scrollToBottom();

                // ✅ بلافاصله تعداد ناخوانده کل را چک کن (چون پیام جدید آمده)
                this.checkGlobalUnreadCount();

                // Play notification sound for messages from others
                const hasNewMessagesFromOthers = data.messages.some(msg => msg.is_from_admin !== this.isCurrentUserAdmin());
                if (hasNewMessagesFromOthers && window.notificationSystem) {
                    // ✅ صدا از طریق سیستم نوتیفیکیشن پخش می‌شود
                    if (window.notificationSystem.audioEnabled) {
                        window.notificationSystem.notificationSound.play().catch(e => console.log('⚠️ Audio play failed:', e));
                    }
                }
            }

            // ✅ اپدیت تیک‌های پیام‌های موجود
            if (data.success && data.updated_ticks && data.updated_ticks.length > 0) {
                data.updated_ticks.forEach(messageId => {
                    this.updateMessageTick(messageId);
                });
            }
        } catch (error) {
            console.error('❌ Error checking new messages:', error);
        }
    }

    /**
     * ✅ چک کردن تعداد کل ناخوانده‌ها و ارسال به notification-system
     * این متد یکبار درخواست می‌فرستد و نتیجه را به notification-system می‌دهد
     */
    async checkGlobalUnreadCount() {
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

            if (data.success && window.notificationSystem) {
                // ✅ بروزرسانی مستقیم notification-system بدون درخواست مجدد
                window.notificationSystem.updateFromMessageData(data, false);
                console.log(`🔔 Global unread count updated: ${data.total_unread}`);
            }
        } catch (error) {
            console.error('❌ Error checking global unread count:', error);
        }
    }

    addMessageToUI(message) {
        const container = document.getElementById('messagesContainer');
        if (!container) return;

        // Check if message already exists
        if (document.querySelector(`[data-message-id="${message.id}"]`)) {
            return;
        }

        const isFromAdmin = message.is_from_admin;
        const messageWrapper = document.createElement('div');
        messageWrapper.className = `message-wrapper ${isFromAdmin ? 'message-left' : 'message-right'}`;
        messageWrapper.dataset.messageId = message.id;
        messageWrapper.style.animation = 'fadeInUp 0.3s ease-out';

        // Determine colors based on story
        let bgColor, textColor, timeColor;
        if (isFromAdmin) {
            bgColor = message.story === 'thanks' ? 'bg-blue-100' :
                     message.story === 'warning' ? 'bg-yellow-100' :
                     message.story === 'scholarship' ? 'bg-green-100' :
                     'bg-gray-100';
            textColor = 'text-gray-800';
            timeColor = 'text-gray-500';
        } else {
            bgColor = message.story === 'thanks' ? 'bg-blue-500' :
                     message.story === 'warning' ? 'bg-yellow-500' :
                     message.story === 'scholarship' ? 'bg-green-500' :
                     'bg-blue-500';
            textColor = 'text-white';
            timeColor = 'text-gray-200';
        }

        let badgeText = '';
        if (message.story === 'thanks') badgeText = '🙏 تشکر';
        else if (message.story === 'warning') badgeText = '⚠️ هشدار';
        else if (message.story === 'scholarship') badgeText = '🎓 بورسیه';

        const time = new Date(message.created_at);
        const timeString = time.toLocaleTimeString('fa-IR', { hour: '2-digit', minute: '2-digit' });

        // ✅ تیک‌های SVG
        const ticksHTML = !isFromAdmin ? `
            <span class="message-ticks ${timeColor}">
                ${message.tick ? `
                    <svg class="w-4 h-4 inline-block" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 12L9 17L20 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 12L14 17L24 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transform: translateX(-8px); transform-origin: right;"/>
                    </svg>
                ` : `
                    <svg class="w-4 h-4 inline-block" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 12L9 17L20 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                `}
            </span>
        ` : '';

        messageWrapper.innerHTML = `
            <div class="message-bubble ${bgColor} ${textColor}">
                <div class="message-content">${this.escapeHtml(message.description)}</div>
                ${message.price ? `
                    <div class="message-price ${timeColor}">
                        💰 مبلغ: ${this.formatNumber(message.price)} تومان
                    </div>
                ` : ''}
                <div class="message-footer">
                    <span class="message-time ${timeColor}">${timeString}</span>
                    ${ticksHTML}
                    ${badgeText ? `<span class="message-badge ${bgColor}">${badgeText}</span>` : ''}
                </div>
            </div>
        `;

        container.appendChild(messageWrapper);

        // Remove empty messages placeholder if exists
        const emptyMessages = container.querySelector('.empty-messages');
        if (emptyMessages) {
            emptyMessages.remove();
        }
    }

    scrollToBottom(smooth = true) {
        const container = document.getElementById('messagesContainer');
        if (container) {
            container.scrollTo({
                top: container.scrollHeight,
                behavior: smooth ? 'smooth' : 'auto'
            });
        }
    }

    isCurrentUserAdmin() {
        // You can determine this from the page data or a global variable
        return window.currentUserRole !== 'user';
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    formatNumber(num) {
        return new Intl.NumberFormat('fa-IR').format(num);
    }
}

// Add CSS animations
const animationStyle = document.createElement('style');
animationStyle.textContent = `
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(animationStyle);

// Auto-initialize if on message page
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('messagesContainer');
    if (container) {
        const requestId = window.location.pathname.split('/').pop();
        if (requestId && !isNaN(requestId)) {
            window.messageUpdater = new MessageUpdater(requestId);
        }
    }
});
