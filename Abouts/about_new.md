# مستندات کامل پروژه بنیاد حکمت

## 📋 معرفی پروژه
**بنیاد حکمت** یک سیستم جامع مدیریت درخواست‌های بورسیه تحصیلی است که با هدف کمک به دانش‌آموزان نیازمند طراحی شده است. این پلتفرم امکان ثبت درخواست، پیگیری وضعیت، ارتباط با مدیران و دریافت بورسیه را فراهم می‌کند.

## 🛠 استک تکنولوژی

### Backend
- **Framework**: Laravel 12.0
- **Language**: PHP 8.2
- **Database**: SQLite
- **Authentication**: Laravel Auth with custom role system

### Frontend
- **Template Engine**: Blade Templates
- **CSS Framework**: TailwindCSS
- **JavaScript**: Vanilla JS + External Files
- **UI Components**: Custom responsive components
- **External Assets**: sidebar.css, sidebar.js, search-box.css

### پکیج‌های خاص
- **Livewire 3.6**: Real-time interactivity
- **morilog/jalali**: Persian date conversion
- **JalaliDatePicker**: Persian calendar widget
- **FlatPickr**: Alternative date picker (backup)

## 🔐 سیستم احراز هویت و دسترسی

### معماری Authentication
```
AuthController::roler() → Role Detection → Dashboard Redirect
```

### سطوح دسترسی

#### 👤 User (role='user')
- کاربران عادی که درخواست بورسیه می‌دهند
- امکان ثبت چندین درخواست (برای فرزندان مختلف)
- پیگیری وضعیت درخواست‌ها
- ارتباط با ادمین‌ها از طریق سیستم پیام‌رسانی
- وارد کردن شماره کارت در صورت تایید

#### 🛡 Admin (role='admin')
- مدیران بررسی‌کننده درخواست‌ها
- **پیش‌نیاز**: تکمیل پروفایل شخصی (اجباری)
- مدیریت کامل چرخه درخواست‌ها
- تعیین وقت ملاقات با JalaliDatePicker
- ارسال انواع پیام به کاربران
- مدیریت بورسیه‌های 31 روزه

#### 👑 Master (role='master')
- مدیران ارشد با دسترسی کامل
- تمام اختیارات Admin
- مدیریت نقش کاربران (User → Admin)
- نظارت بر کل سیستم

## 🗂 ساختار دیتابیس

### جداول اصلی و روابط

#### 👥 users
```sql
id, name, username, password, role, created_at, updated_at
```
- **نقش**: جدول مادر تمام کاربران
- **روابط**: hasMany → requests, hasOne → profile

#### 📋 requests (جدول اصلی کسب‌وکار)
```sql
id, user_id, name, female, grade, nationalcode, phone, address,
story, imgpath, date, cardnumber, created_at, updated_at
```
- **نقش**: ذخیره درخواست‌های بورسیه
- **وضعیت‌های story**:
  - `submit`: ثبت اولیه توسط User
  - `cancel`: لغو توسط User
  - `check`: در حال بررسی توسط Admin
  - `reject`: رد شده توسط Admin
  - `accept`: تایید شده + شروع پیگیری 31 روزه
  - `appointment`: تعیین وقت ملاقات حضوری

#### 👤 profiles
```sql
id, user_id, name, female, grade, phone, address, imgpath, created_at, updated_at
```
- **نقش**: اطلاعات تکمیلی ادمین‌ها (اجباری)
- **روابط**: belongsTo → users

#### 💬 scholarships (سیستم پیام‌رسانی)
```sql
id, profile_id, request_id, title, description, price, story, ismaster, created_at, updated_at
```
- **انواع story**:
  - `warning`: پیام هشدار (زرد)
  - `thanks`: پیام تشکر (آبی)
  - `message`: پیام عادی (آبی)
  - `scholarship`: اعلان بورسیه (سبز)

#### 🏆 aboutreq (تخصص‌ها و افتخارات)
```sql
id, request_id, title, description, created_at, updated_at
```
- **نقش**: ذخیره مهارت‌ها و افتخارات متقاضیان
- **روابط**: belongsTo → requests

#### ⏰ daily_trackers (پیگیری زمانی)
```sql
id, request_id, start_date, max_days, created_at, updated_at
```
- **نقش**: پیگیری 31 روزه پس از تایید
- **منطق**: start_date + 31 days = scholarship eligibility

## 🏗 معماری سیستم

### ساختار کنترلرها
```
app/Http/Controllers/
├── AuthController.php     → Login/Logout + Role Router
├── UserController.php     → User Dashboard Management
├── AdminController.php    → Admin Operations
├── MasterController.php   → Master Level Management
└── HomeController.php     → Public Pages + Registration
```

### ساختار View
```
resources/views/
├── layouts/
│   ├── unified.blade.php   → 🆕 یکپارچه Layout (جایگزین user.blade.php و admin.blade.php)
│   ├── user.blade.php      → 🗑️ Deprecated
│   └── admin.blade.php     → 🗑️ Deprecated
├── user/                   → User Dashboard Pages
│   ├── dashboard.blade.php
│   ├── addrequest.blade.php
│   ├── editrequest.blade.php
│   ├── message.blade.php
│   ├── addmessage.blade.php
│   └── addcard.blade.php
├── admin/                  → Admin Dashboard Pages
│   ├── dashboard.blade.php
│   ├── users.blade.php
│   ├── accepts.blade.php
│   ├── requestdetail.blade.php  → 🆕 تعیین وقت ملاقات
│   └── userdetail.blade.php
├── master/                 → Master Dashboard Pages
│   ├── dashboard.blade.php
│   ├── users.blade.php
│   ├── accepts.blade.php
│   └── addprofile.blade.php
└── public/assets/          → 🆕 External Assets
    ├── css/
    │   ├── sidebar.css      → 🆕 سیستم سایدبار
    │   └── search-box.css   → 🆕 کامپوننت جستجو
    └── js/
        └── sidebar.js       → 🆕 عملکرد سایدبار
```

## 🔄 فلوی کامل کسب‌وکار

### مسیر کاربر عادی (User Journey)
```
1. ثبت نام/ورود
2. ورود به داشبورد User
3. ثبت درخواست جدید (addrequest)
   ├── اطلاعات شخصی
   ├── آپلود عکس
   └── اضافه کردن تخصص‌ها
4. پیگیری وضعیت (dashboard)
5. دریافت پیام از ادمین (message)
6. در صورت تایید: ثبت شماره کارت (addcard)
7. دریافت بورسیه پس از 31 روز
```

### مسیر مدیر (Admin Journey)
```
1. ورود + تکمیل پروفایل (اجباری)
2. مشاهده درخواست‌ها (dashboard)
3. ورود به جزئیات → خودکار تغییر به "check"
4. تصمیم‌گیری:
   ├── Reject: رد نهایی
   ├── Accept: تایید + شروع daily_tracker
   └── Appointment: تعیین وقت ملاقات
5. ارسال پیام به کاربر
6. مدیریت بورسیه‌ها (پس از 31 روز)
```

## ⚙️ ویژگی‌های فنی فعلی

### 🆕 سیستم Layout یکپارچه (Unified Dashboard)
- **معماری**: یک Layout برای همه نقش‌ها با نمایش شرطی منو
- **Sidebar**: قابل بسته/باز شدن با انیمیشن هموار
- **State Management**: localStorage برای حفظ وضعیت sidebar
- **Responsive**: کاملاً ریسپانسیو با پشتیبانی موبایل
- **Active Menu**: تشخیص خودکار صفحه فعال

### 🆕 سیستم سایدبار پیشرفته
```css
/* ویژگی‌های کلیدی */
- Width Toggle: 64px ↔ 256px (w-16 ↔ w-64)
- Smooth Animations: cubic-bezier transitions
- Tooltip System: نمایش راهنما در حالت جمع
- Fixed Positioning: جلوگیری از جهش دکمه‌ها
- CSS Variables: استفاده از کاستوم Properties
```

### 🆕 تعیین وقت ملاقات (JalaliDatePicker Integration)
```javascript
// فیچرهای پیاده‌سازی شده
- Persian Calendar: تقویم شمسی کامل
- Time Selection: انتخاب ساعت دقیق
- Date Restrictions: محدودیت روزهای گذشته
- Persian Digits: نمایش اعداد فارسی
- Responsive Design: سازگار با موبایل
```

### سیستم پیام‌رسانی
- **معماری**: دوطرفه User ↔ Admin/Master
- **ذخیره‌سازی**: جدول scholarships
- **انواع**: Warning, Thanks, Message, Scholarship
- **🆕 UI Enhancement**: Modal system با AJAX
- **🆕 Success Feedback**: پاپ‌آپ تایید ارسال

### سیستم پیگیری زمانی
```php
// Logic در AdminController
DailyTracker::create([
    'request_id' => $id,
    'start_date' => Carbon::now()->startOfDay(),
    'max_days' => 31
]);
```
- **Progress Bar**: نمایش پیشرفت در accepts.blade.php
- **محاسبه**: diffInDays() برای درصد پیشرفت
- **اتمام**: لینک دریافت بورسیه پس از 100%

### محدودیت‌های کنترل دسترسی
- **appointment**: هیچ تغییری مجاز نیست
- **accept**: فقط ویرایش شماره کارت
- **Admin profile**: اجباری قبل از ورود به داشبورد

### امنیت
- **CSRF Protection**: در تمام فرم‌ها
- **File Upload**: محدودیت نوع و سایز
- **Middleware**: کنترل نقش در سطح کنترلر
- **Private Storage**: فایل‌های آپلودی محافظت شده

## 📊 آمار و وضعیت فعلی
- **Database**: SQLite (قابل migration به MySQL/PostgreSQL)
- **🆕 Layout System**: یکپارچه برای همه نقش‌ها (unified.blade.php)
- **Form Handling**: Traditional POST/GET + AJAX Components
- **Validation**: Server-side Laravel validation
- **Responsive**: TailwindCSS responsive classes + Custom CSS
- **Browser Support**: Modern browsers
- **🆕 External Assets**: منفصل CSS/JS برای بهتر شدن maintainability
- **🆕 Animation System**: CSS Transitions + JavaScript State Management

## 🔧 تنظیمات و Assets جدید

### فایل‌های CSS اضافه شده
```css
public/assets/css/sidebar.css
├── Sidebar transitions and animations
├── Tooltip system for collapsed state  
├── Active menu highlighting
├── Responsive mobile adaptations
└── Fixed positioning to prevent button jumping

public/assets/css/search-box.css
├── Custom search component styling
└── Form input enhancements
```

### فایل‌های JavaScript اضافه شده
```javascript
public/assets/js/sidebar.js
├── Toggle functionality with smooth animations
├── localStorage state persistence
├── Active menu detection based on URL
├── Mobile responsive behavior
└── Icon direction management
```

## 🆕 Route Updates در web.php
```php
// یکپارچه‌سازی routes برای همه نقش‌ها
Route::prefix('unified')->name('unified.')->group(function () {
    Route::get('/myrequests', [UnifiedController::class, 'myrequests'])->name('myrequests');
    Route::get('/allrequests', [UnifiedController::class, 'allrequests'])->name('allrequests');
    Route::get('/acceptes', [UnifiedController::class, 'acceptes'])->name('acceptes');
    Route::get('/users', [UnifiedController::class, 'users'])->name('users');
    Route::get('/addprofile', [UnifiedController::class, 'addprofile'])->name('addprofile');
});
```

---
*این مستندات وضعیت بهبود یافته پروژه را به تاریخ ۱۶ مرداد ۱۴۰۴ نشان می‌دهد.*
*آخرین به‌روزرسانی: یکپارچه‌سازی Dashboard + سیستم سایدبار پیشرفته + JalaliDatePicker Integration*
