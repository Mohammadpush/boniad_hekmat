<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MajorSeeder::class,
        ]);

        $users = [
            [
                'name' => 'علی حسینی',
                'username' => 'a',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'مریم رضایی',
                'username' => 'b',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'رضا محمدی',
                'username' => 'c',
                'password' => Hash::make('12345678'),
                'role' => 'master',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);

        // Generate fake requests data for each user
        $requests = [];
        $grades = ['اول', 'دوم', 'سوم', 'چهارم', 'پنجم', 'ششم', 'هفتم', 'هشتم', 'نهم', 'دهم', 'یازدهم', 'دوازدهم'];
        $stories = ['submit', 'accept', 'check', 'reject'];
        $knowOptions = ['شبکه‌های اجتماعی', 'دوستان', 'خانواده', 'مدرسه', 'سایت بنیاد', 'سایر'];
        $counselingMethods = ['مشاوره مدرسه', 'مشاوره خارجی', 'روش‌های دیگر', 'مشاوره نمی‌کنم'];
        $helpOthers = ['بله، همیشه', 'گاهی اوقات', 'خیر، هرگز'];

        $names = [
            'محمد حسن حاکمی', 'علی رضایی', 'فاطمه احمدی', 'حسین موسوی', 'زهرا کریمی',
            'احمد نوری', 'مریم صالحی', 'رضا مرادی', 'سارا قاسمی', 'مجید رحیمی',
            'لیلا حسینی', 'امیر محمدی', 'نرگس احمدی', 'بهرام علیزاده', 'طاها جعفری'
        ];

        for ($userId = 1; $userId <= 3; $userId++) {
            for ($i = 0; $i < 10; $i++) {
                $nationalCode = str_pad(mt_rand(1000000000, 9999999999), 10, '0', STR_PAD_LEFT);
                $phone = '091' . mt_rand(10000000, 99999999);
                $fatherPhone = '091' . mt_rand(10000000, 99999999);
                $motherPhone = '091' . mt_rand(10000000, 99999999);
                $schoolPhone = '051' . mt_rand(10000000, 99999999);

                $currentGrade = $grades[array_rand($grades)];
                $isSeniorGrade = in_array($currentGrade, ['دهم', 'یازدهم', 'دوازدهم']);

                $requests[] = [
                    'user_id' => $userId,
                    'name' => $names[array_rand($names)],
                    'birthdate' => '2005-' . str_pad(mt_rand(1, 12), 2, '0', STR_PAD_LEFT) . '-' . str_pad(mt_rand(1, 28), 2, '0', STR_PAD_LEFT),
                    'nationalcode' => $nationalCode,
                    'phone' => $phone,
                    'telephone' => mt_rand(0, 1) ? '051' . mt_rand(10000000, 99999999) : null,
                    'rental' => mt_rand(0, 1),
                    'grade' => $currentGrade,
                    'major_id' => mt_rand(1, 5), // همیشه یک رشته انتخاب کن
                    'school' => 'دبیرستان شهید ' . ['بهشتی', 'چمران', 'رجایی', 'فردوسی', 'سعدی'][array_rand(['بهشتی', 'چمران', 'رجایی', 'فردوسی', 'سعدی'])],
                    'last_score' => mt_rand(15, 20),
                    'principal' => ['آقای احمدی', 'خانم رضایی', 'آقای محمدی', 'خانم صالحی'][array_rand(['آقای احمدی', 'خانم رضایی', 'آقای محمدی', 'خانم صالحی'])],
                    'school_telephone' => mt_rand(0, 1) ? $schoolPhone : null,
                    'father_name' => ['محمد', 'علی', 'حسن', 'رضا', 'احمد'][array_rand(['محمد', 'علی', 'حسن', 'رضا', 'احمد'])],
                    'father_phone' => $fatherPhone,
                    'father_job' => ['کارمند', 'بازنشسته', 'آزاد', 'کارگر', 'راننده'][array_rand(['کارمند', 'بازنشسته', 'آزاد', 'کارگر', 'راننده'])],
                    'mother_name' => ['فاطمه', 'زهرا', 'مریم', 'سارا', 'نرگس'][array_rand(['فاطمه', 'زهرا', 'مریم', 'سارا', 'نرگس'])],
                    'mother_phone' => $motherPhone,
                    'mother_job' => ['خانه‌دار', 'معلم', 'کارمند', 'پرستار', 'خیاط'][array_rand(['خانه‌دار', 'معلم', 'کارمند', 'پرستار', 'خیاط'])],
                    'address' => 'مشهد، خیابان ' . ['امام رضا', 'کوهسنگی', 'احمدآباد', 'سجاد', 'طبرسی'][array_rand(['امام رضا', 'کوهسنگی', 'احمدآباد', 'سجاد', 'طبرسی'])] . '، پلاک ' . mt_rand(1, 500),
                    'father_job_address' => 'مشهد، خیابان ' . ['وکیل‌آباد', 'پیروزی', 'دانشگاه', 'شریعتی'][array_rand(['وکیل‌آباد', 'پیروزی', 'دانشگاه', 'شریعتی'])] . '، پلاک ' . mt_rand(1, 300),
                    'mother_job_address' => 'مشهد، خیابان ' . ['معلم', 'بلوار فردوسی', 'کوثر', 'سناباد'][array_rand(['معلم', 'بلوار فردوسی', 'کوثر', 'سناباد'])] . '، پلاک ' . mt_rand(1, 200),
                    'father_income' => mt_rand(30000000, 80000000),
                    'mother_income' => mt_rand(0, 50000000),
                    'siblings_count' => mt_rand(1, 5),
                    'siblings_rank' => mt_rand(1, 3),
                    'english_proficiency' => mt_rand(30, 95),
                    'know' => $knowOptions[array_rand($knowOptions)],
                    'counseling_method' => $counselingMethods[array_rand($counselingMethods)],
                    'why_counseling_method' => 'دلیل انتخاب این روش مشاوره به علت دسترسی بهتر و کیفیت مناسب می‌باشد.',
                    'motivation' => 'انگیزه من برای دریافت بورسیه تحصیلی، ادامه تحصیل در رشته مورد علاقه و کمک به خانواده می‌باشد. همچنین می‌خواهم در آینده به جامعه خدمت کنم و دانش خود را در اختیار دیگران قرار دهم.',
                    'spend' => 'در صورت دریافت بورسیه، از آن برای تهیه کتاب‌های درسی، شهریه کلاس‌های تقویتی و سایر هزینه‌های تحصیلی استفاده خواهم کرد.',
                    'how_am_i' => 'من فردی مطالعه‌پذیر، با انگیزه و پشتکار هستم که همیشه سعی می‌کنم بهترین نتایج را کسب کنم.',
                    'favorite_major' => 'رشته مورد علاقه من ' . ['پزشکی', 'مهندسی کامپیوتر', 'معماری', 'داروسازی', 'مهندسی برق'][array_rand(['پزشکی', 'مهندسی کامپیوتر', 'معماری', 'داروسازی', 'مهندسی برق'])] . ' است.',
                    'future' => 'در آینده قصد دارم پس از فارغ‌التحصیلی، در حوزه تخصصی خود فعالیت کنم و به پیشرفت جامعه کمک کنم.',
                    'help_others' => $helpOthers[array_rand($helpOthers)],
                    'suggestion' => 'پیشنهاد می‌کنم بنیاد حکمت برنامه‌های آموزشی و کارگاه‌های مهارت‌آموزی بیشتری برگزار کند.',
                    'imgpath' => 'userimage/default.png',
                    'gradesheetpath' => 'gradesheets/sample.pdf',
                    'story' => $stories[array_rand($stories)],
                    'cardnumber' => mt_rand(0, 1) ? str_pad(mt_rand(1000000000000000, 9999999999999999), 16, '0', STR_PAD_LEFT) : null,
                    'created_at' => now()->subDays(mt_rand(0, 60)),
                    'updated_at' => now()->subDays(mt_rand(0, 30)),
                ];
            }
        }

        DB::table('requests')->insert($requests);

    }
}
