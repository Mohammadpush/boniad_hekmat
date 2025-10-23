<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name',75);
            $table->date('birthdate');
             //میخوام از jalali-datepicker استفاده کنی با توجه به اینکه ساعتش برداشته شود و توانست هر تاریخی را سلکت کرد.
            $table->string('nationalcode',10);
            $table->string('phone',11);
            $table->string('telephone',11)->nullable();
            $table->boolean('rental');
             //به معنای اینکه آیا منزل ملکی است یا استیجاری اگر استیجاری بود true
            $table->string('grade',25);
             // از (اول،دوم،سوم،چهارم تا دوزادهم به صورت متن ولی سلکت باشد) نه اینپوت که خود کاربر متن وارد کند.

// ...existing code...

$table->foreignId('major_id')->nullable()->constrained('majors')->onDelete('cascade');

// ...existing code...
            // در ایران از پایه دهم دانش آموزان انتخاب رشته میکنند پس از قبل از دهم نیازی به پر کردن این فیلد نیست ولی شاید ادمین نتواند تمامی رشته های ایران را شناسایی کند دوست دارم خودت با جست و جو این جدول رابطه یک به یک با  majors دارد.ی=
            $table->string('school');
            $table->integer('last_score');
            $table->string('principal',75);
            $table->string('school_telephone',11)->nullable();
            $table->string('father_name',75);
            $table->string('father_phone',11);
            $table->string('father_job',75);
            $table->string('mother_name',75);
            $table->string('mother_phone',11);
            $table->string('mother_job',75);
            $table->text('address');
            $table->text('father_job_address');
            $table->text('mother_job_address');
            $table->integer('father_income');
            //ایده(موقع نمایش فرم به ادمین یا مدیر ، میخوام به چند تا نسبتی که خودم تعرقف میکنم بنویسی ضعیف،متوسط ،خوب)
            $table->integer('mother_income');
            //ایده(موقع نمایش فرم به ادمین یا مدیر ، میخوام به چند تا نسبتی که خودم تعرقف میکنم بنویسی ضعیف،متوسط ،خوب)
            $table->integer('siblings_count');
            $table->integer('siblings_rank');
            //دو ردیف با هم  مرتبط بهم هستند اینکه مجموعا این خانواده چند فرزند دارد و این دانشآموزی که درخواست بورسیه میکند نفر چندم است.
            $table->unsignedTinyInteger('english_proficiency');
            // 'Percentage of English proficiency (0-100)'
            $table->string('know');
            //فرد چگونه با بنیاد حکمت آشنا شده است چندگزینه ای
            $table->string('counseling_method');
            //این جدول بین سه حالت مشاوره مدرسه،مشاوره خارجی ،روش های دیگر ،مشاوره نمیکنم است
            $table->text('why_counseling_method');
            //اینم دلیل اینکه چرا همچین روش را استفاده میکند.
            $table->text('motivation');
            //انگیزه فرد برای درخواست بورسیه (حداقل 30 کلمه)
            $table->text('spend');
            $table->text('how_am_i');
            $table->text('favorite_major');
            $table->text('future');
            $table->string('help_others');
            $table->text('suggestion');
            //اینم توضیحاتی درباره خود فرد است که میخواهد بورسیه بگیرد

            $table->string('imgpath')->default('userimage/default.png');
            //عکس فرد
            $table->string('gradesheetpath');
            //کارنامه فرد

            // از این ردیف به بعد
            $table->string('story')->default('submit');
            //وضعیت درخواست فرد که شامل submit, accept, check, reject میباشد
            $table->string('cardnumber',16)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
