 این یک پروژه لارول است. فرانت این پروژه با تیلویند است. به جای css عادی هر اسکریپتی هم باشد من در داخل صفحه بلید  با تگ script انجام میدم نه جدا
تمامی افراد در واقع user هستند
و با role از هم دیگر تفکیک میشوند
سه مقدار برای این role وجود دارد
user
admin
master
من برای هرکدامشان داشبورد مشخصی تایین کردم


اشکال: من تا اینجا برای هر فرد یک داشبورد جدا درست کردم مشکل از ساختار پروژه من است . چرا که میخواهم یک داشبورد
واحد برای همه کاربران درست کرده و آپشن هارا برای افراد فیلتر کنم جلو تر توضیح میدهم...

ترفند جالب من برای لاگین این بود که افراد همان اول به فانکشنroler  در app/http/Controllers/AuthController.php

متصل میشود و اگر در  آن بخش

if (!Auth::check()) {
            return redirect()->route('login'); // ریدایرکت به صفحه لاگین
        }

سول: آیا ساخت گزینه remember me بهتر نیست؟

بعد از اون با توجه به رول خود وارد داشبورد مخصوص خود میشود.

User/Dashboard:
پیج ها:
تمامی محویات داخل پوشه
F:\boniad_hekmat\resources\views\user
لیوت یا صفحه ها:
F:\boniad_hekmat\resources\views\leyout\ user.blade.php
کنترلر :
usercontroler
مهم ترین جدول:
requests
جدول های در ارتباط:
users:
افرادی که role = ‘user’
است.یک جدول مادر
aboutreq:
تخصص های درخواست
scholarship:
ارتباط دو طرفه با ادمین و مستر

حال اینجا user هایی که رول ‘user’ دارند
میتوانند به تعداد دلخواه request  برای دریافت بورسیه
انگار والدین میتوانند برای دو پسر خود و حتی مثلا برای پسر داداشش درخواست بزنه
users  یک رابطه چند به یک با request دارد و این درخواست برای دانش آموزی است که میخواهد بورسیه شود.
addrequset:
*
اشکال: نمیخواهم یک صفحه برای فرد بیاید بلکه میخواهم یک popup برای این درخواست بسازم  و با js کلا validate را مدیریت کنم چرا که validate  باز هم به من بگو چکار باید بکنم تا این مشکل validate را حل کنم؟ چرا که حتی زبان اروری که میدهد انگلیسی هست نه فارسی.
ایده: به نظر من برای اینکه کاربر گیج نشود، میخواهم که فیلد ها مرحله به مرحله بیایند

مثال:

نام			نام خانوادگی
مرحله بعد

شماره تلفن
مرحله بعد
*
اگر دقت کرده باشی
request با جدول aboutreq یک رابطه دارد
aboutreq
در واقع تخصص ها ، و افتاخارات  فرد است که در user/addrequst.blade.php
    <!-- بخش تخصص‌ها -->
    <div class="flex flex-col">
        <label class="mb-1 font-medium">تخصص‌ها</label>
        <div id="about-container" class="space-y-2">
            <div class="about-input-group flex flex-row-reverse">
                <input type="text" name="abouts[]" placeholder="تخصص"
                       class="flex-1 px-3 py-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="button" class="add-about px-3 py-2 bg-green-500 text-white rounded-r-md hover:bg-green-600 transition">
                    +
                </button>
            </div>
        </div>
    </div>
 پر میشود.

*این امکان را هم برای پاپ آپ درست کن

و بالا هم یک رود مپ وجود داشته باشد به صورت pattern که نشان میدهد که کاربر مرحله چند است و در نهایت یک پاپ باز شده و بگوی درخواست با موفقیت ثبت شد
*
requests->story
این بخش در جدول requests  وضعیت درخواست را مشخص میکند
دو وضعیت
submit و cancel به دست خود user یعنی user->role = ‘user’
و چهار وضعیت دیگر به دست admin و master است که جلو تر توضیح میدهم.
اینجا فقط اسمشو میگم
check
reject
accept
epointment
*
بخش بعدی editrequset هستش این هم میخواهم یک پاپ باز شود و فیلد ها با داده های قبلی پر شده باشد

و دوباره بعد  از آن پیغام پاپ آپ همون جا بیاد یک انیمیشن تیک زیبا و پیغام : درخواست شما با موفقیت تغییر کرد.
*
حالا وارد بخش message در جدول  scholarship میشویم
این بخش ارتباط ادمین ها با پروفایل،
و مستر به صورت  مستقیم است
// اگر متوجه نشدی بیشتر توضیح بده یا فایل F:\boniad_hekmat\resources\views\user\ message.blade.php
را مشاهده کن
//
با
user ها است
و دارای سه story
warning
thanks
message
است.
حالت آخر حالت
scholarship است که
مربوط به بورسیه است مخصوص request->story = accept
*
دیگر از بخش addmessage خسته شدم.
اشکال: ساختار شکنی کن کلا scholarship->title را بردار
میخواهم مانند تلگرام یک اینپوت پایین باشد
و فرد هر چقدر میخواهد پیام پر کند و کنار اون حالتش را انتخاب کند

    <div class="mb-4">
                    <label class="block text-gray-700 mb-2" for="nationalcode">وضعیت پیام</label>
                    <div class="flex gap-3">

                        <!-- warning -->
                        <input type="radio" name="story" id="telegram" value="warning" class="sr-only peer/telegram" checked>
                        <label for="telegram" class="cursor-pointer peer-checked/telegram:grayscale-0 grayscale transition duration-200">
                            <i class="fa fa-exclamation-triangle text-4xl text-yellow-500"></i>
                        </label>

                        <!-- thanks -->
                        <input type="radio" name="story" id="instagram" value="thanks" class="sr-only peer/instagram">
                        <label for="instagram" class="cursor-pointer peer-checked/instagram:grayscale-0 grayscale transition duration-200">
                            <i class="fa fa-handshake-o text-4xl text-blue-500"></i>
                        </label>
                        {{-- message --}}
                        <input type="radio" name="story" id="c" value="message" class="sr-only peer/c">
                        <label for="c" class="cursor-pointer peer-checked/c:grayscale-0 grayscale transition duration-200">
                            <i class="fa fa-comments text-4xl text-blue-500"></i>
                        </label>
                    </div>
                </div>



در این بخش در addmessage مشخص کردم.

حالت هارا به صورت ساده تر توی همون فیلد بیار کنار اینپوت پیام.
حالا دیگر این بخش message را نمیخواهم فقط از طریق لینک داخل داشبورد
                                    <a  href="{{ route('user.message', ['id' => $request->id]) }}"
                                        class="text-blue-600 hover:text-blue-800  flex">

                                        <button type="submit" class="mt-auto mb-auto">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                            </svg>

                                        </button>
                                    </a>
به داخل message ها  برود بلکه یک بخش جدیدی به نام
messages
در هدر باشد.
و دقیقا مثل تلگرام .
چون میبینی که request ها imgpath هم دارند یکی عکس هم دارند میخواهم در یک طرف همین عکس ها با نام و نام خانودگی و در آن طرف هم message ها آن درخواست و فیلد addmesage
باشد و از لینک هم به این صفحه مراجعه شود اعلان های جدید به اختیار خودت در یک جایی به کاربر نمایش داده شود تا از پیام های جدید مطلع شود.
و با راست کلیک روی هر پیام
سه آپشن کپی کردن متن، ویرایش متن و پاک کردن متن ظاهر شود.
در موبایل با کلیک و نگه داشتن این فیچر ها زیر انگشت ظاهر شود.
کل منظورم اینه که با راست کلیک یه جای دیگه نمایش داده نشود زیر موس نمایش داده شود.

*


و در نهایت بخش addcard است.
زمانی که request->story = ‘accept’

user باید فیلد cardnumber را در جدول requests  پر کند
یک شماره کارت که بنیاد برای آن بورسیه را بریزد.

توجه: بعد از دو حالت
accept و epointment
فرد دیگر نمیتواند ریکوست را ادیت کند
و فقط در حالت
accept
میتواند
تنها فیلد cardnumber را ادیت کند

من این کار را کردم که وقتی accept شد فقط ادیت شماره کارت باشد
ولی برای اپوینت منت اینکار نکردم و از تو میخواهم کلا در بخش ملاقات حضوری هیچ تغییری مجاز نیست.

(نگاه کن اصلا  فرم ها میخواهم پاپ آپ شوند دلیل اینکه قبلش نکردم این بود که مشکلات ویلیدیت لارول نمیآمد چون صفحه رفرش و پاپ آپ بسته میشد)

جمع بندی درخواست های من از تو برای  user داشبورد

تبدیل صفحه
addrequest و editrequset  و addcard به پاپ آپ
تبدیل صفحه
addmessage
به
یک فیلد در پایین صفحه messages
مانند تلگرام
حذف فیچر ادیت کل درخواست در requser->story
epointment
و
accept

.حالا کلا دوبخش درخواست های خود کاربر و مسیج ها برای این کاربر باشد باقی آپشن ها فیلتر شود (اول گفتم که میخواهم یک داشبورد واحد منتها با فیلتر درست کنم.)
اسمشم بزار
درخواست های خود
پیام ها (فیلتر شده برای درخواست های خودش)

view/admin
پروفایل یک رابطه یک به یک با users دارد و برای ادمین ها اجباری است.
یعنی تازمانی که ادمین پروفایل خود را پر نکند وارد داشبود خود نمیشود.

بخش اضافه و حذف و ویرایش request کاملا از این بخش حذف میشود.
 البته با اینکه من این بخش هارا نگزاشتم ولی میگویم.
درخواست ها خود:
میتوانی تمامی بخش های user برای این ادمین هم باز باشد ولی فقط برای درخواست های خود
کنترلر درخواست ها:
قبل از هرچیز وارد request->story میشویم
check:
زمانی که ادمین وارد جزییات درخواست شود،
یعنی userdetail
خودکار وضعیت به check یا درحال بررسی تغییر میکند
reject:
ادمین درخواست را رد میکند
accept:
ادمین درخواست را  قبول میکند.
تاریخ زدن این دکمه در dailytrakers
به عنوان startdate میفرستد
public function accept($id){
    $req = modelrequest::findOrFail($id);
    $req->story ='accept';
        DailyTracker::create([
            'request_id' => $id,
            'start_date' => Carbon::now()->startOfDay(),
            'max_days' => 31
        ]);

    $req->update();
    return redirect()->route('admin.dashboard');
}
بعد از این جدول استفاده خواهم کرد.

epontment:
ادمین یک قرار ملاقات حضوری برای فرد درخواست کننده میفرستد که من این بخش را
                            <div id='openpopup'
                                class="block w-full text-center py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition mb-2 cursor-pointer">
                                دریافت نوبت
                            </div>
                            <div
                                class="fixed inset-0 bg-black bg-opacity-50 flex flex-col items-center justify-center z-50 hidden "id='popup'>

                                <form method="post" action="{{ route('admin.epointment',['id' => $userrequest->id]) }}" class="bg-amber-50 rounded-2xl justify-center flex flex-col p-7">
                                            @csrf
                                    <div class="mb-4 items-center flex flex-col">
                                        <input data-jdp name="mydate" id="mydate" type="text" value="دریافت"
                                            placeholder="انتخاب تاریخ و زمان شمسی"
                                            class="w-52 h-11 bg-[#569ff7] text-white rounded-sm text-center ">
                                    </div>
                                    <div class="flex flex-row gap-3 justify-center items-center">

                                        <input
                                            class="bg-green-500 text-white px-6 py-2 rounded hover:bg-gray-400 transition w-24 h-10"
                                            type="submit" value="ارسال">
                                        <a href="#" id='closepopup'
                                            class="bg-red-700 text-white px-6 py-2 rounded hover:bg-gray-400 transition w-24 h-10">انصراف</a>
                                    </div>
                                </form>
                            </div>
یک پاپ ساختم و با کتابخانه jalalydatepicker
ادمین تاریخ شمسی را وارد میکند
و در کنترلر این تاریخ به میلادی کانورت میشود با کتابخانه jalaly  و برای نشان دادن در ویو دوباره به تاریخ شمسی کانورت میشود.

قبول شدگان
برای درخواست هایی است که قبول شدند .حال اینجا از dailytacker استفاده میشود .
در accepts.blade.php
                        <!-- Progress Bar for DailyTracker -->
                        <tr>
                            <td colspan="6" class="">
                                @if ($request->DailyTracker)
                                    @php
                                        $today = \Carbon\Carbon::now()->startOfDay()->addDays(31);
                                        $start = \Carbon\Carbon::parse($request->DailyTracker->start_date, 'Asia/Tehran')->startOfDay();
                                        $passed = $start->diffInDays($today);
                                        $max = $request->DailyTracker->max_days;
                                        $percent = min(($passed / $max) * 100, 100);
                                    @endphp
                                    <div style="background:#e0e0e0; width:100%; overflow:hidden; margin-bottom: 1rem;" class="rounded-lg max-[728px]:rounded-t-[0px] rounded-b-lg">
                                        @if ($percent == 100)
                                            <div style="
                                                width: 100%;
                                                background: linear-gradient(90deg, #4caf50, #81c784);
                                                color: white;
                                                padding: 10px 15px;
                                                font-weight: bold;
                                                font-family: sans-serif;
                                                text-align: left;
                                                border-radius: 15px 0 0 15px;
                                                transition: width 0.5s ease;">
                                                <a href="{{ route('admin.scholarship', ['id' => $request->id]) }}" class="w-full block text-center ">
                                                    دریافت بورسیه
                                                </a>
                                            </div>
                                        @else
                                            <div style="
                                                width: {{ $percent }}%;
                                                background: linear-gradient(90deg, #4cafaf, #81b9c7);
                                                color: white;
                                                padding: 10px 15px;
                                                font-weight: bold;
                                                font-family: sans-serif;
                                                text-align: left;
                                                transition: width 0.5s ease;">
                                                @if ($passed == 0)
                                                    0
                                                @else
                                                    {{ $passed }} / {{ $max }} روز
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-red-600">روز‌شمار موجود نیست.</p>
                                @endif
                            </td>
                        </tr>
 عملیات شمارش روز های برای رسیدن به روز 31 است چرا که بورسیه هر 1 ماه یکبار ارسال میشود.
بخش scholarship->story = “scholarship”
دقیقا برای این بخش ساخته شده و در پیام عادی برداشته شده است
و حالا دوباره فکر که چی یک پاپ آپ باید باز شود  و این پیام ارسال شود برای ux بهتر.(که اینطور نیست)

کاربران
این بخش هم برای تمامی کاربران با role  = user است که
ادمین فقط میتواند درخواست های مرتبط با این کاربر را ببیند و این کاربر را کاملا پاک کند

master:
تمامی فیلد های دقیقا مرتبط با admin است با اضافه کردن یک چیز
master میتواند به admin ها role بدهد که در بخش کاربران این عملیات را انجام دادم.


این متن برای  آشنایی تو با پروژه من است قبل از ادامه کار هر سوالی داری بپرس و نتیجه بررسی کل پروژه و( در آینده کار هایی که قرار است انجام بدهی) را در این فایل aithink.txt قرار بده

پروژه تقریبا بزرگ و سنگین شده است پس احتمال خطای تو در کد ها وجود دارد . قبل از هر عملیاتی یک کاممیت به منظور
before(عملیات)
و after(عملیات )
بکن و قبل از هر تغغیری باز هم از من اجازه بخواه توجه داشته باش این پروژه مهمه و نباید خرابکاری کنی خیلی باید هواست به فایل ها باشد حالا من هم برنامه نویس هستم و تا اینجارو خودم انجام دادم ولی بازم تو هواست باشه لطفا

قبل از ادامه کار سوالی داری در خدمتم.
