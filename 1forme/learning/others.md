آه! حالا متوجه شدم منظورتان از انتخاب قسمت `$casts` چیست!

## **`$casts` برای چیست؟**

`$casts` در Laravel به شما اجازه می‌دهد که **نوع داده‌ها را خودکار تبدیل کنید** وقتی از دیتابیس خوانده یا در آن ذخیره می‌شوند.

### **مثال‌های کاربردی:**

```php
protected $casts = [
    'birthdate' => 'date',        // رشته دیتابیس → Carbon date object
    'rental' => 'boolean',        // 0/1 دیتابیس → true/false PHP
    'date' => 'datetime',         // رشته دیتابیس → Carbon datetime object  
    'father_income' => 'integer', // رشته دیتابیس → عدد صحیح PHP
];
```

### **فواید:**

1. **خوانایی بهتر:**
```php
// بدون cast
if($request->rental == '1') // رشته

// با cast  
if($request->rental) // boolean واقعی
```

2. **کار با تاریخ آسان‌تر:**
```php
// بدون cast
$date = Carbon::parse($request->birthdate);

// با cast
$age = $request->birthdate->diffInYears(now()); // مستقیماً Carbon object
```

3. **Type Safety:**
```php
$income = $request->father_income + 1000; // حتماً integer است
```

### **چرا این `$casts` مهم است؟**

چون در فرم‌های HTML همه چیز به صورت رشته ارسال می‌شود، اما ما نیاز داریم:
- `rental` → boolean باشد (true/false)  
- `birthdate` → Carbon object باشد (برای کار با تاریخ)
- `father_income` → integer باشد (برای محاسبات)

**خلاصه:** `$casts` باعث می‌شود Laravel خودکار نوع داده‌ها را تبدیل کند تا کدنویسی آسان‌تر شود! 🎯
