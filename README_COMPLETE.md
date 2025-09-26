# 📋 راهنمای جامع پروژه بنیاد حکمت

## 📖 توضیح کلی پروژه

پروژه بنیاد حکمت یک سامانه مدیریت درخواست‌های بورسیه تحصیلی است که بر روی فریمورک Laravel 12 طراحی شده و شامل سه سطح کاربری متفاوت می‌باشد.

## 🚀 فناوری‌های استفاده شده

### Backend
- **Framework**: Laravel 12 (PHP ^8.2)
- **Database**: SQLite (برای production می‌توان MySQL استفاده کرد)
- **Authentication**: Laravel Built-in Auth
- **ORM**: Eloquent

### Frontend  
- **Template Engine**: Blade Templates
- **CSS Framework**: TailwindCSS
- **JavaScript**: Vanilla JS + کتابخانه‌های خارجی
- **Date Picker**: JalaliDatePicker + FlatPickr

### پکیج‌های مهم
- **Livewire 3.6**: برای تعامل‌های Real-time
- **morilog/jalali**: تبدیل تاریخ شمسی

## 🔐 سیستم کاربران و نقش‌ها

### 👤 User (role='user')
- کاربران عادی که درخواست بورسیه می‌دهند
- قابلیت‌ها: ثبت درخواست، ویرایش، مشاهده پیام‌ها
- دسترسی: فقط به درخواست‌های خودشان

### 🛡 Admin (role='admin') 
- مدیران بررسی‌کننده درخواست‌ها
- **نکته مهم**: باید حتماً پروفایل کامل داشته باشند
- قابلیت‌ها: بررسی درخواست‌ها، ارسال پیام، تعیین وقت ملاقات
- دسترسی: تمام درخواست‌ها + مدیریت کاربران عادی

### 👑 Master (role='master')
- مدیر ارشد سیستم
- قابلیت‌ها: تمام اختیارات Admin + ارتقاء کاربران به Admin
- دسترسی: کامل به سیستم

## 📊 ساختار دیتابیس

### جداول اصلی

#### 1️⃣ `users` - کاربران سیستم
```
id, name, username, password, role, created_at, updated_at
```
- **role**: 'user' | 'admin' | 'master'
- **username**: کاربری منحصر به فرد

#### 2️⃣ `requests` - درخواست‌های بورسیه
```
id, user_id, name, female(نام خانوادگی), birthdate, nationalcode, phone, 
telephone, rental, grade, major_id, school, last_score, principal, 
school_telephone, father_name, father_phone, father_job, mother_name, 
mother_phone, mother_job, address, father_job_address, mother_job_address, 
father_income, mother_income, siblings_count, siblings_rank, 
english_proficiency, know, counseling_method, why_counseling_method, 
motivation, spend, how_am_i, favorite_major, future, help_others, 
suggestion, imgpath, gradesheetpath, story, date, cardnumber
```
- **story**: 'submit' | 'cancel' | 'check' | 'reject' | 'accept' | 'appointment'
- **rental**: boolean (خانه استیجاری/ملکی)
- **siblings_count/siblings_rank**: تعداد فرزندان خانواده و رتبه متقاضی

#### 3️⃣ `profiles` - اطلاعات ادمین‌ها
```  
id, user_id, name, nationalcode, position, imgpath, phone, created_at, updated_at
```
- **اجباری برای Admin‌ها**: بدون پروفایل نمی‌توانند وارد داشبورد شوند

#### 4️⃣ `scholarships` - سیستم پیام‌رسانی
```
id, profile_id, request_id, message, price, story, ismaster, created_at, updated_at
```
- **story**: 'warning' | 'thanks' | 'message' | 'scholarship'
- **ismaster**: آیا توسط Master ارسال شده

#### 5️⃣ `daily_trackers` - پیگیری 31 روزه
```
id, request_id, start_date, max_days(31), created_at, updated_at
```
- فعال می‌شود وقتی درخواست تایید شود (story='accept')

#### 6️⃣ `aboutreqs` - مهارت‌ها و افتخارات
```
id, request_id, about, created_at, updated_at
```

#### 7️⃣ `majors` - رشته‌های تحصیلی
```
id, name, created_at, updated_at
```

## 📈 چرخه کاری درخواست‌ها

1. **submit**: کاربر درخواست ثبت می‌کند
2. **check**: Admin درخواست را باز می‌کند (تغییر خودکار)
3. **accept**: Admin درخواست را تایید می‌کند (DailyTracker شروع)
4. **reject**: Admin درخواست را رد می‌کند
5. **appointment**: Admin زمان ملاقات تعیین می‌کند
6. **cancel**: User خودش درخواست را لغو می‌کند

## 🗂 ساختار فایل‌ها

### کنترلرها
- `AuthController`: ورود/خروج + هدایت بر اساس نقش
- `UserController`: داشبورد کاربران عادی
- `AdminController`: عملیات ادمین‌ها  
- `MasterController`: عملیات مستر
- `UnifiedController`: سیستم جدید وحدت‌یافته

### مدل‌ها و روابط
```php
User hasMany Request
User hasOne Profile

Request belongsTo User
Request belongsTo Major
Request hasMany Scholarship
Request hasMany Aboutreq  
Request hasOne DailyTracker

Profile belongsTo User
Profile hasMany Scholarship

Major hasOne Request
```

### مسیریابی (Routes)
- **Public**: `/`, `/login`, `/singup`
- **Unified**: `/unified/*` 

## 🔧 تنظیمات مهم

### متغیرهای محیطی (.env)
```bash
APP_NAME="بنیاد حکمت"
APP_TIMEZONE=Asia/Tehran
DB_CONNECTION=sqlite
```

### Middleware
- `RoleMiddleware`: کنترل دسترسی بر اساس نقش
- `auth`: احراز هویت لازم
- بررسی اجباری پروفایل برای Admin‌ها

## 🚨 نکات مهم برای توسعه‌دهنده

### 1. مدیریت نقش‌ها
```php
// بررسی نقش کاربر
if (Auth::user()->role === 'admin') {
    // کد مخصوص ادمین
}

// Middleware در Route ها
Route::middleware(['auth', 'role:admin,master'])->group(function () {
    // فقط admin و master
});
```

### 2. پروفایل اجباری Admin‌ها
```php
// در RoleMiddleware
if ($userRole === 'admin') {
    $thisadmin = Auth::user();
    if (!$thisadmin->profile && !$request->is('dashboard/addprofile')) {
        return redirect()->route('unified.addprofile');
    }
}
```

### 3. تغییر خودکار وضعیت درخواست
```php
// در AdminController::userdetail
if ($userrequest->story == 'submit') {
    $userrequest->update(['story' => 'check']);
}
```

### 4. شروع پیگیری 31 روزه
```php
// هنگام accept شدن درخواست
DailyTracker::create([
    'request_id' => $id,
    'start_date' => now(),
    'max_days' => 31
]);
```

### 5. ساختار پیام‌رسانی
```php
// انواع پیام در scholarships
'warning'     => 'هشدار (زرد)',
'thanks'      => 'تشکر (آبی)', 
'message'     => 'پیام عادی (آبی)',
'scholarship' => 'اعلان بورسیه (سبز)'
```

## 📱 رابط کاربری

### طراحی واکنش‌گرا
- TailwindCSS برای استایل‌دهی
- ریسپانسیو برای موبایل و دسکتاپ
- انیمیشن‌های ساده با CSS

### تقویم فارسی
- JalaliDatePicker برای انتخاب تاریخ
- morilog/jalali برای نمایش تاریخ شمسی

## 💾 فایل‌های آپلود

### مسیرهای ذخیره
- تصاویر کاربران: 'storage\app\private\userimage'
- کارنامه‌ها: 'storage\app\private\gradesheets'


### نکته امنیتی
فایل‌های آپلود شده باید validation مناسب داشته باشند

## 🔍 جستجو و فیلترینگ

### جستجوی کاربران
- بر اساس نام، نام کاربری، نقش
- JavaScript خالص برای فیلتر فوری

### فیلتر درخواست‌ها
- بر اساس وضعیت (story)
- تاریخ ثبت
- رشته تحصیلی

## 🎯 قسمت‌های تکمیل نشده

### سیستم Unified
بخش‌هایی از سیستم جدید (UnifiedController) هنوز در حال توسعه هستند

### پیام‌رسانی Real-time
می‌توان با Livewire یا WebSocket بهبود داد

### گزارش‌گیری
سیستم آمار و گزارش‌گیری جامع

## 📋 دستورات مفید

### راه‌اندازی پروژه
```bash
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

### مدیریت دیتابیس
```bash
php artisan migrate:fresh --seed  # شروع مجدد
php artisan make:migration create_table_name
php artisan make:model ModelName -mcr
```

### کش و بهینه‌سازی
```bash
php artisan cache:clear
php artisan config:clear
php artisan optimize
```

## 👥 اکانت‌های پیش‌فرض (برای تست)

```
User: username='a' | password='12345678'
Admin: username='b' | password='12345678' 
Master: username='c' | password='12345678'
```

## ⚠️ نکات امنیتی

1. همیشه از `Auth::user()->id` برای کاربر فعلی استفاده کنید
2. Middleware ها را رعایت کنید
3. Validation ورودی‌ها ضروری است
4. فایل‌های آپلود باید محدود باشند

---
