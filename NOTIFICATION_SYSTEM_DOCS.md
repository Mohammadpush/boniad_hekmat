# 🔔 سیستم نوتیفیکیشن - مستندات کامل

## 📋 خلاصه سیستم

سیستم نوتیفیکیشن یکپارچه که:
- **Badge** تعداد پیام‌های ناخوانده را در همه صفحات نمایش می‌دهد
- **نوتیفیکیشن مرورگر** هنگام دریافت پیام جدید نمایش می‌دهد
- **صدای نوتیفیکیشن** پخش می‌کند
- **عنوان صفحه** را به‌روز می‌کند (مثل تلگرام)
- **Favicon** را با badge تغییر می‌دهد

---

## 🏗️ معماری سیستم

### الگوی طراحی: **Optimized Polling**

```
┌─────────────────────────────────────────────────────────────┐
│                      صفحه Message                            │
│                                                              │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  real-time-updates.js (MessageUpdater)               │  │
│  │                                                       │  │
│  │  • هر 3 ثانیه: چک پیام‌های جدید این chat           │  │
│  │  • هر 9 ثانیه: چک تعداد کل ناخوانده‌ها            │  │
│  │  • وقتی پیام جدید آمد: فوراً چک ناخوانده‌ها        │  │
│  └──────────────────────────────────────────────────────┘  │
│                          │                                   │
│                          │ updateFromMessageData()          │
│                          ▼                                   │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  notification-system.js (NotificationSystem)         │  │
│  │                                                       │  │
│  │  • دریافت داده از MessageUpdater                    │  │
│  │  • بروزرسانی Badge (Desktop + Mobile)               │  │
│  │  • نمایش نوتیفیکیشن                                 │  │
│  │  • پخش صدا                                           │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                  صفحات دیگر (غیر Message)                    │
│                                                              │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  notification-system.js (NotificationSystem)         │  │
│  │                                                       │  │
│  │  • هر 3 ثانیه: چک تعداد کل ناخوانده‌ها             │  │
│  │  • بروزرسانی Badge                                   │  │
│  │  • نمایش نوتیفیکیشن                                 │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
```

---

## 🚀 فرآیند کار (Step by Step)

### حالت 1: کاربر در صفحه Message است

#### گام به گام:

```
زمان 00:00 - کاربر وارد /unified/message/10 می‌شود
├─ notification-system.js تشخیص می‌دهد که در صفحه message است
├─ Polling خود را متوقف می‌کند (برای جلوگیری از درخواست دوبل)
├─ فقط یک چک اولیه انجام می‌دهد و previousUnreadCount را ست می‌کند
└─ MessageUpdater را راه‌اندازی می‌کند

زمان 00:00 - MessageUpdater شروع می‌کند
├─ Polling با interval 3 ثانیه
└─ lastUnreadCheckTime = 0

زمان 00:03 - اولین چک MessageUpdater
├─ درخواست به /unified/api/new-messages/10
├─ اگر پیام جدیدی باشد:
│   ├─ اضافه به UI
│   ├─ فوراً checkGlobalUnreadCount() را صدا می‌زند
│   │   ├─ درخواست به /unified/api/unread-count
│   │   └─ نتیجه را به notification-system.updateFromMessageData() می‌دهد
│   └─ notification-system Badge را به‌روز می‌کند
└─ اگر پیام جدیدی نباشد: هیچ کاری نمی‌کند

زمان 00:06 - دومین چک
└─ مشابه بالا

زمان 00:09 - سومین چک
├─ چک پیام‌های جدید
└─ چون 9 ثانیه گذشته، checkGlobalUnreadCount() اجرا می‌شود
    └─ Badge به‌روز می‌شود

زمان 00:12 - ادمین پیام جدید می‌فرستد
└─ MessageUpdater در چک بعدی (00:15) پیام را دریافت می‌کند
    ├─ پیام به UI اضافه می‌شود
    ├─ فوراً checkGlobalUnreadCount() اجرا می‌شود
    │   └─ notification-system.updateFromMessageData(data, false)
    │       ├─ Badge به‌روز می‌شود
    │       └─ نوتیفیکیشن نشان داده نمی‌شود (چون در همین صفحه هستیم)
    └─ صدای نوتیفیکیشن پخش می‌شود
```

### حالت 2: کاربر در صفحه دیگر است (مثلاً My Requests)

```
زمان 00:00 - کاربر در /unified/myrequests
├─ notification-system.js تشخیص می‌دهد که در صفحه message نیست
├─ Polling را شروع می‌کند (هر 3 ثانیه)
└─ یک چک اولیه انجام می‌دهد

زمان 00:03 - اولین چک
├─ درخواست به /unified/api/unread-count
├─ دریافت: { total_unread: 2, ... }
└─ updateBadge(2) → Badge می‌شود "2"

زمان 00:04 - ادمین پیام جدید می‌فرستد (در یک chat دیگر)
└─ کاربر هنوز خبر ندارد...

زمان 00:06 - دومین چک
├─ درخواست به /unified/api/unread-count
├─ دریافت: { total_unread: 3, ... }
├─ تشخیص می‌دهد که 3 > 2 (تعداد افزایش یافته!)
├─ updateBadge(3) → Badge می‌شود "3"
├─ showNotification(1) → "یک پیام جدید دریافت کردید"
├─ پخش صدای نوتیفیکیشن
└─ نمایش Browser Notification
```

---

## 📊 مقایسه تعداد درخواست‌ها

### ❌ قبل (سیستم قدیمی):

```
صفحه Message:
├─ notification-system.js: هر 3 ثانیه → /unified/api/unread-count
└─ real-time-updates.js: هر 3 ثانیه → /unified/api/new-messages/{id}
└─ نتیجه: 2 درخواست هر 3 ثانیه = 40 درخواست در دقیقه

صفحات دیگر:
└─ notification-system.js: هر 3 ثانیه → /unified/api/unread-count
└─ نتیجه: 1 درخواست هر 3 ثانیه = 20 درخواست در دقیقه
```

### ✅ بعد (سیستم جدید بهینه):

```
صفحه Message:
├─ real-time-updates.js:
│   ├─ هر 3 ثانیه → /unified/api/new-messages/{id}
│   └─ هر 9 ثانیه → /unified/api/unread-count
└─ نتیجه: 1.33 درخواست هر 3 ثانیه = 27 درخواست در دقیقه
└─ کاهش 32%! 🎉

صفحات دیگر:
└─ notification-system.js: هر 3 ثانیه → /unified/api/unread-count
└─ نتیجه: 1 درخواست هر 3 ثانیه = 20 درخواست در دقیقه
└─ بدون تغییر (بهینه است)
```

---

## 🔧 API Endpoints

### 1. `/unified/api/unread-count` (GET)

**Response:**
```json
{
  "success": true,
  "total_unread": 5,
  "unread_per_request": {
    "10": 2,
    "15": 3
  }
}
```

### 2. `/unified/api/new-messages/{request_id}` (GET)

**Query Params:**
- `last_message_id`: آخرین ID پیامی که دریافت شده

**Response:**
```json
{
  "success": true,
  "messages": [
    {
      "id": 123,
      "description": "سلام",
      "story": "message",
      "price": null,
      "created_at": "2025-10-18T10:30:00Z",
      "sender_name": "ادمین",
      "is_from_admin": true
    }
  ]
}
```

---

## 🎨 UI Components

### Badge Locations:

1. **Desktop Sidebar** (`#messagesBadge`)
   ```html
   <span id="messagesBadge" style="display: none;"
         class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
       0
   </span>
   ```

2. **Mobile Menu** (`#mobileMessagesBadge`)
   ```html
   <span id="mobileMessagesBadge" style="display: none;"
         class="absolute top-2 right-16 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
       0
   </span>
   ```

### Browser Notification:

```
┌────────────────────────────────────┐
│ 💬 پیام جدید                       │
│ یک پیام جدید دریافت کردید          │
│                          [مشاهده]  │
└────────────────────────────────────┘
```

### In-Page Notification:

```
┌──────────────────────────────────────────────┐
│ 💬 پیام جدید                                 │
│ یک پیام جدید دریافت کردید                   │
│                          [مشاهده]  [✕]      │
└──────────────────────────────────────────────┘
```

---

## ⚙️ تنظیمات

### فرکانس Polling:

```javascript
// در real-time-updates.js
this.pollingInterval = 3000; // 3 ثانیه (هر 3s چک پیام‌های جدید)

// چک تعداد کل ناخوانده‌ها
if (now - this.lastUnreadCheckTime > 9000) { // هر 9 ثانیه
    this.checkGlobalUnreadCount();
}
```

### غیرفعال کردن صدا:

```javascript
window.notificationSystem.toggleSound(); // true/false
```

### بروزرسانی دستی:

```javascript
window.notificationSystem.forceUpdate(); // چک فوری
```

---

## 🐛 Debugging

### لاگ‌های مفید:

```javascript
// Console logs:
🔔 Initializing Notification System...
🔔 Message page detected: Polling will be handled by MessageUpdater
✅ Notification System initialized!

📨 Received 1 new message(s)
🔔 Global unread count updated: 5

🔔 Updating badge: 5
✅ Desktop badge updated
✅ Mobile badge updated

🔔 New messages detected: 1
🔔 Showing notification for 1 new message(s)
```

### بررسی وضعیت:

```javascript
// در Console مرورگر:
console.log(window.notificationSystem);
console.log(window.messageUpdater); // فقط در صفحه message

// چک Badge:
console.log(document.querySelector('#messagesBadge').textContent);
console.log(document.querySelector('#messagesBadge').style.display);

// چک previousUnreadCount:
console.log(window.notificationSystem.previousUnreadCount);
```

---

## 📈 بهینه‌سازی‌های انجام شده

1. ✅ **حذف درخواست دوبل** در صفحه message
2. ✅ **Conditional Polling**: فقط در صفحات غیر message
3. ✅ **Batch Update**: هر 9 ثانیه به جای هر 3 ثانیه برای تعداد کل
4. ✅ **Immediate Update**: وقتی پیام جدید آمد، فوراً چک می‌کند
5. ✅ **Tab Visibility**: وقتی تب مخفی است، polling متوقف می‌شود
6. ✅ **No Spam**: نوتیفیکیشن فقط برای افزایش تعداد نشان داده می‌شود

---

## 🔐 امنیت

- ✅ همه درخواست‌ها دارای CSRF Token هستند
- ✅ Authentication توسط Laravel middleware چک می‌شود
- ✅ هر کاربر فقط پیام‌های مربوط به خودش را می‌بیند

---

## 🚦 وضعیت‌های مختلف

| حالت | Polling | Badge | نوتیفیکیشن |
|------|---------|-------|------------|
| صفحه Message باز | هر 3s پیام + هر 9s تعداد کل | ✅ | ❌ (صدا ✅) |
| صفحه دیگر باز | هر 3s تعداد کل | ✅ | ✅ |
| تب مخفی | ❌ متوقف | ✅ | ✅ (وقتی بازگردد) |

---

## 📞 پشتیبانی

اگر مشکلی داشتید:
1. Console مرورگر را بررسی کنید
2. Network tab را برای بررسی درخواست‌ها چک کنید
3. مطمئن شوید که `meta[name="csrf-token"]` در صفحه موجود است
4. مطمئن شوید که Badge ها در HTML موجود هستند (`#messagesBadge`, `#mobileMessagesBadge`)

---

**نسخه:** 2.0 (Optimized)
**تاریخ:** October 18, 2025
**Developer:** Boniad Hekmat Team
