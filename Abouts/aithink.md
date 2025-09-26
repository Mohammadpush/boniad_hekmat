# تحلیل جامع پروژه بنیاد حکمت

## معرفی کلی
این پروژه یک سیستم مدیریت درخواست‌های بورسیه تحصیلی برای بنیاد حکمت است که با استفاده از فریمورک Laravel 12 پیاده‌سازی شده است. پروژه دارای سیستم احراز هویت پیچیده و سطوح دسترسی سه‌گانه (User, Admin, Master) می‌باشد.

## تکنولوژی‌های استفاده شده
- **Backend**: PHP 8.2, Laravel 12.0
- **Frontend**: Blade Templates, TailwindCSS
- **Database**: SQLite
- **JavaScript**: Vanilla JS (inline در Blade templates)
- **Packages**:
  - Livewire 3.6 (برای reactivity)
  - morilog/jalali (تاریخ شمسی)
  - JalilaDatePicker (انتخاب تاریخ شمسی)

## معماری پروژه

### سیستم احراز هویت
- **AuthController**: مدیریت ورود/خروج و تشخیص نقش
- **Roler Function**: هدایت کاربران به داشبورد مناسب بر اساس نقش
- **Middleware**: کنترل دسترسی در سطح کنترلر

### سطوح دسترسی (Roles)
1. **User (role='user')**:
   - ایجاد، ویرایش و مدیریت درخواست‌های بورسیه
   - دریافت و ارسال پیام با ادمین/مستر
   - مدیریت شماره کارت برای دریافت بورسیه

2. **Admin (role='admin')**:
   - بررسی و تصمیم‌گیری روی درخواست‌ها
   - مدیریت وضعیت درخواست‌ها (check, reject, accept, appointment)
   - ارسال پیام به کاربران
   - مدیریت تعیین وقت ملاقات
   - **الزام**: باید پروفایل شخصی تکمیل کند

3. **Master (role='master')**:
   - تمام دسترسی‌های Admin
   - مدیریت نقش‌های کاربران (تبدیل User به Admin)

## ساختار دیتابیس

### جداول اصلی
1. **users**: کاربران سیستم با نقش‌های مختلف
2. **requests**: درخواست‌های بورسیه با 6 وضعیت
   - `submit`, `cancel` (توسط User)
   - `check`, `reject`, `accept`, `appointment` (توسط Admin/Master)
3. **profiles**: اطلاعات تکمیلی ادمین‌ها (الزامی)
4. **scholarships**: سیستم پیام‌رسانی با 4 نوع
   - `warning`, `thanks`, `message`, `scholarship`
5. **aboutreq**: تخصص‌ها و افتخارات متقاضیان
6. **daily_trackers**: پیگیری 31 روزه بورسیه‌های تایید شده

### روابط دیتابیس
- **User ← hasMany → Requests** (یک کاربر، چند درخواست)
- **Request ← hasOne → DailyTracker** (پیگیری بورسیه)
- **Request ← hasMany → Scholarships** (پیام‌ها)
- **Request ← hasMany → Aboutreq** (تخصص‌ها)
- **Profile ← belongsTo → User** (پروفایل ادمین)

## فلوی کاری پروژه

### مسیر User
1. ثبت نام و ورود
2. ایجاد درخواست بورسیه + تخصص‌ها
3. پیگیری وضعیت درخواست
4. دریافت پیام از ادمین
5. در صورت تایید: وارد کردن شماره کارت

### مسیر Admin
1. ورود و تکمیل پروفایل (الزامی)
2. مشاهده درخواست‌ها
3. تغییr وضعیت به "در حال بررسی" (check)
4. تصمیم‌گیری: تایید/رد/تعیین وقت ملاقات
5. در صورت تایید: شروع پیگیری 31 روزه
6. ارسال بورسیه پس از اتمام 31 روز

## مشکلات شناسایی شده

### مشکلات UX/UI
1. **فرم‌های جداگانه**: User باید برای هر عمل به صفحه جدید برود
2. **Validation غیرفارسی**: پیام‌های خطا به انگلیسی
3. **عدم وجود Progress Indicator**: کاربر نمی‌داند در چه مرحله‌ای است
4. **سیستم پیام‌رسانی ضعیف**: نیاز به رابط مشابه تلگرام

### مشکلات فنی
1. **ساختار View نامنظم**: فولدر `leyout` به جای `layout`
2. **Mixed Concerns**: JavaScript inline در Blade templates
3. **عدم استفاده از API**: فقط traditional form submission
4. **کمبود Eager Loading**: احتمال N+1 problem
5. **عدم استفاده از Events/Observers**: برای پیگیری تغییرات

## درخواست‌های بهبود (مشخص شده با *)

### بخش User Dashboard
1. **تبدیل به Popup**:
   - `addrequest` → Multi-step popup با roadmap
   - `editrequest` → Popup با pre-filled data
   - `addcard` → Simple popup

2. **پیام‌رسانی مشابه تلگرام**:
   - صفحه جداگانه `messages` در header
   - نمایش عکس + نام در سمت چپ
   - پیام‌ها در سمت راست
   - Input field در پایین با انتخاب نوع پیام
   - Context menu (راست کلیک): Copy, Edit, Delete

3. **محدودیت‌های ویرایش**:
   - `appointment`: هیچ ویرایشی مجাز نیست
   - `accept`: فقط ویرایش شماره کارت

### بخش Admin Dashboard
1. **بهبود UX بورسیه**: Popup برای ارسال بورسیه به جای صفحه جداگانه
2. **بهبود Progress Bar**: نمایش بهتر پیشرفت 31 روزه

## پیشنهادات معماری

### بازسازی Frontend
1. **Component-based Structure**: تبدیل به Alpine.js یا Vue.js
2. **API-first Approach**: جداسازی Backend/Frontend
3. **Real-time Updates**: WebSocket برای پیام‌رسانی
4. **Progressive Enhancement**: بهبود تدریجی UX

### بهبود Backend
1. **Service Layer**: جداسازی business logic
2. **Repository Pattern**: abstract data access
3. **Events & Listeners**: برای پیگیری تغییرات
4. **Queue Jobs**: برای عملیات سنگین
5. **Form Request Classes**: برای validation بهتر

### مدیریت حالت
1. **State Machine**: برای مدیریت وضعیت‌های request
2. **Observer Pattern**: برای پیگیری تغییرات
3. **Cache Layer**: برای بهبود performance

## نکات امنیتی
1. **File Upload Security**: بررسی نوع و سایز فایل
2. **CSRF Protection**: در تمام فرم‌ها
3. **XSS Prevention**: sanitization ورودی‌ها
4. **SQL Injection**: استفاده از Eloquent ORM
5. **Authorization**: middleware برای کنترل دسترسی

## برنامه اجرایی
با توجه به درخواست‌های مشخص شده، اولویت‌های کاری:
1. ایجاد popup system برای فرم‌ها
2. بازسازی سیستم پیام‌رسانی
3. بهبود validation و فارسی‌سازی پیام‌ها
4. پیاده‌سازی محدودیت‌های ویرایش
5. ایجاد dashboard واحد با فیلترهای نقش‌محور

## سوالات قبل از شروع کار

1. **Git Strategy**: آیا ترجیح می‌دهید برای هر feature برنچ جداگانه ایجاد کنم؟
2. **Validation**: آیا ترجیح می‌دهید validation را کاملاً client-side انجام دهم یا همچنان server-side نیز باشد؟
3. **Mobile Responsiveness**: آیا طراحی موبایل اولویت دارد؟
4. **Browser Support**: چه مرورگرهایی باید پشتیبانی شوند؟
5. **Animation Library**: آیا ترجیح خاصی برای انیمیشن‌ها دارید یا CSS pure کافی است؟
6. **Remember Me**: آیا باید گزینه "مرا به خاطر بسپار" در صفحه لاگین اضافه کنم؟

پروژه شما ساختار منطقی خوبی دارد اما نیاز به بهینه‌سازی UX و refactoring برخی بخش‌ها دارد. آماده شروع کار هستم!
