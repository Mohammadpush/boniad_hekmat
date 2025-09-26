@extends('layouts.unified')

@section('head')
    <style>


        /* انیمیشن smooth scroll */
        .scroll-container {
            scroll-behavior: smooth;
        }

        /* افکت hover برای کارت‌ها */
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* استایل دکمه مشاهده همه */
        .view-all-btn svg {
            transition: transform 0.2s ease;
        }

        .view-all-btn.expanded svg {
            transform: rotate(180deg);
        }

        /* کانتینر اسکرول افقی */
        .scroll-wrapper {
            width:100%;
            position: relative;
            container-type: inline-size;
        }

        .horizontal-scroll-container {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: 320px;
            gap: 1.5rem;
            overflow-x: auto;
            overflow-y: hidden;
            scroll-behavior: smooth;
            padding: 1rem 0;
            -ms-overflow-style: none;
            scrollbar-width: none;
            width: 100%;
            max-width: 100%;

        }

        .horizontal-scroll-container::-webkit-scrollbar {
            display: none;
        }

        /* کارت‌ها در اسکرول افقی */
        .horizontal-scroll-container .card-hover {
            width: 320px;
            min-width: 320px;
            max-width: 320px;
            flex-shrink: 0;
            animation: none;
              transition: transform 0.1s ease, box-shadow 0.1s ease;
        }

        /* جلوگیری از شکستگی layout */
        .overflow-x-auto {
            max-width: 100%;
            position: relative;
        }

        /* تضمین عدم تاثیر بر کل صفحه */
        .space-y-8 {
            max-width: 100%;
            overflow: visible;
        }

        /* نمایش اسکرول بار در حالت مشاهده همه */
        .show-scrollbar {
            scrollbar-width: thin;
            -ms-overflow-style: auto;
        }

        .show-scrollbar::-webkit-scrollbar {
            display: block;
            height: 8px;
        }

        .show-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .show-scrollbar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .show-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* استایل‌های دکمه‌های کنترل */
        .control-btn {
            transition: all 0.2s ease;
        }

        .control-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* انیمیشن برای تغییر دسته‌بندی */
        .category-transition {
            animation: fadeInUp 0.4s ease-out;
        }

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

        /* استایل‌های رنگی برای گروه‌های مختلف */
        .bg-orange-100 { background-color: #fed7aa; }
        .text-orange-700 { color: #c2410c; }
        .bg-orange-500 { background-color: #f97316; }
    </style>
@endsection

@section('page-title', 'درخواست‌های من')

@section('content')
    @php
        use Morilog\Jalali\Jalalian;
    @endphp




    <main class="p-8 w-full min-w-0">


        <div class="bg-white rounded-xl shadow p-6 w-full min-w-0">
            @if ($requests->isEmpty())

                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">هیچ درخواستی یافت نشد</h3>
                    <p class="text-gray-600 mb-6">شما هنوز درخواستی ثبت نکرده‌اید</p>
                    <a href="{{ route('unified.requestform') }}"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        ثبت اولین درخواست
                    </a>
                </div>
            @else

                                            @foreach ($requests as $request)
                                                <!-- کارت -->
                                                <div
                                                    class="card-hover flex-shrink-0 flex flex-col items-center bg-gradient-to-br from-white to-gray-50 w-72 h-96 justify-center rounded-3xl shadow-lg border border-gray-200 p-6 relative overflow-hidden select-none">

                                                    <!-- آیکون وضعیت در گوشه -->
                                                    <div class="absolute top-4 right-4">
                                                        <div
                                                            class="status-badge px-3 py-1 rounded-full text-xs font-medium border
                                                        {{ $request->story === 'submit'
                                                            ? 'bg-blue-100 text-blue-700 border-blue-200'
                                                            : ($request->story === 'accept'
                                                                ? 'bg-green-100 text-green-700 border-green-200'
                                                                : ($request->story === 'check'
                                                                    ? 'bg-yellow-100 text-yellow-700 border-yellow-200'
                                                                    : ($request->story === 'reject'
                                                                        ? 'bg-red-100 text-red-700 border-red-200'
                                                                        : ($request->story === 'epointment'
                                                                            ? 'bg-purple-100 text-purple-700 border-purple-200'
                                                                            : 'bg-gray-100 text-gray-700 border-gray-200')))) }}">
                                                            {{ $request->story === 'submit'
                                                                ? '📤 ارسال شده'
                                                                : ($request->story === 'accept'
                                                                    ? '✅ تایید شده'
                                                                    : ($request->story === 'check'
                                                                        ? '🔍 در حال بررسی'
                                                                        : ($request->story === 'reject'
                                                                            ? '❌ رد شده'
                                                                            : ($request->story === 'epointment'
                                                                                ? '📅 ملاقات'
                                                                                : '❓ نامشخص')))) }}
                                                        </div>
                                                    </div>

                                                    <!-- تصویر پروفایل -->
                                                    <div class="relative mb-4">
                                                        <img src="{{ route('img', ['filename' => $request->imgpath]) }}"
                                                            alt="تصویر کاربر"
                                                            class="w-24 h-24 rounded-full object-cover shadow-md border-4 border-white">
                                                        <div
                                                            class="absolute -bottom-2 -right-2 w-6 h-6 bg-green-500 rounded-full border-2 border-white">
                                                        </div>
                                                    </div>

                                                    <!-- اطلاعات کاربر -->
                                                    <div class="text-center mb-6">
                                                        <h3 class="text-lg font-semibold text-gray-800 mb-1">
                                                            {{ $request->name }}</h3>
                                                        <p class="text-sm text-gray-500">پایه: {{ $request->grade }}</p>
                                                    </div>

                                                    <!-- دکمه‌های عملکرد -->
                                                    <div class="flex gap-3 w-full">
                                                        <a href="{{ route('unified.addoreditrequests', ['id' => $request->id]) }}"
                                                            class="action-btn flex-1 bg-blue-500 hover:bg-blue-600 text-white rounded-xl text-sm font-medium text-center shadow-md hover:shadow-lg flex items-center justify-center py-3 gap-2">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                                </path>
                                                            </svg>
                                                            ویرایش
                                                        </a>

                                                        <form method="POST"
                                                            action="{{ route('unified.requestdetail', ['id' => $request->id]) }}"
                                                            class="flex-1">
                                                            @csrf
                                                            <button type="submit"
                                                                class="action-btn flex-1 w-full bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm font-medium text-center shadow-md hover:shadow-lg flex items-center  py-3 justify-center gap-2">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                                مشاهده
                                                            </button>
                                                        </form>
                                                    </div>

                                                    {{-- <!-- جزئیات اضافی -->
                                                    <div class="mt-4 w-full">
                                                        <div class="bg-gray-50 rounded-xl p-3">
                                                            <div class="flex justify-between items-center text-xs text-gray-600">
                                                                <span>تاریخ ثبت:</span>
                                                                <span>{{ Jalalian::fromDateTime($request->created_at)->format('H:i Y/m/d ') }}</span>
                                                            </div>
                                                            @if($currentGroupType === 'grade')
                                                            <div class="flex justify-between items-center text-xs text-gray-600 mt-1">
                                                                <span>پایه:</span>
                                                                <span class="font-medium">{{ $request->grade }}</span>
                                                            </div>
                                                            @elseif($currentGroupType === 'alphabet')
                                                            <div class="flex justify-between items-center text-xs text-gray-600 mt-1">
                                                                <span>نام:</span>
                                                                <span class="font-medium">{{ $request->name }}</span>
                                                            </div>
                                                            @else
                                                            <div class="flex justify-between items-center text-xs text-gray-600 mt-1">
                                                                <span>وضعیت:</span>
                                                                <span class="font-medium">
                                                                    {{ $request->story === 'submit' ? 'ارسال شده' :
                                                                       ($request->story === 'accept' ? 'تایید شده' :
                                                                       ($request->story === 'check' ? 'در حال بررسی' :
                                                                       ($request->story === 'reject' ? 'رد شده' :
                                                                       ($request->story === 'epointment' ? 'ملاقات' : 'نامشخص')))) }}
                                                                </span>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div> --}}

                                                    <!-- افکت دکوراتیو -->
                                                    <div
                                                        class="absolute -top-4 -left-4 w-16 h-16 bg-gradient-to-br from-blue-200 to-purple-200 rounded-full opacity-20">
                                                    </div>
                                                    <div
                                                        class="absolute -bottom-4 -right-4 w-12 h-12 bg-gradient-to-br from-green-200 to-blue-200 rounded-full opacity-20">
                                                    </div>
                                                </div>
                                            @endforeach


                    <!-- Modal -->
                    <div id="popup" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
                        <div class="flex items-center justify-center min-h-screen">
                            <div class="bg-white rounded-2xl p-8 max-w-[35.6rem] w-full mx-4 shadow-2xl">
                                <div class="text-center mb-6">
                                    <div
                                        class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                                        </svg>

                                    </div>
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">افزودن شماره کارت</h3>
                                    <p class="text-gray-600">شماره کارت بانک پارسیان خود را وارد کنید</p>
                                </div>

                                <form method="post" action="{{ route('unified.storecard', ['id' => $request->id]) }}">
                                    @csrf
                                    <div class="mb-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-4 text-center">شماره
                                            کارت</label>

                                        <!-- 16 مستطیل برای نمایش ارقام -->
                                        <div class="flex  justify-center items-center gap-2 mb-4" dir="ltr">
                                            <!-- گروه اول: 4 رقم -->
                                            <div class="flex gap-1">
                                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 transition-all duration-200"
                                                    data-index="0">0</div>
                                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 transition-all duration-200"
                                                    data-index="1">0</div>
                                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 transition-all duration-200"
                                                    data-index="2">0</div>
                                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 transition-all duration-200"
                                                    data-index="3">0</div>
                                            </div>

                                            <!-- خط تیره -->
                                            <div class="text-gray-400 text-xl font-bold">-</div>

                                            <!-- گروه دوم: 4 رقم -->
                                            <div class="flex gap-1">
                                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 transition-all duration-200"
                                                    data-index="4">0</div>
                                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 transition-all duration-200"
                                                    data-index="5">0</div>
                                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 transition-all duration-200"
                                                    data-index="6">0</div>
                                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 transition-all duration-200"
                                                    data-index="7">0</div>
                                            </div>

                                            <!-- خط تیره -->
                                            <div class="text-gray-400 text-xl font-bold">-</div>

                                            <!-- گروه سوم: 4 رقم -->
                                            <div class="flex gap-1">
                                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 transition-all duration-200"
                                                    data-index="8">0</div>
                                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 transition-all duration-200"
                                                    data-index="9">0</div>
                                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 transition-all duration-200"
                                                    data-index="10">0</div>
                                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 transition-all duration-200"
                                                    data-index="11">0</div>
                                            </div>

                                            <!-- خط تیره -->
                                            <div class="text-gray-400 text-xl font-bold">-</div>

                                            <!-- گروه چهارم: 4 رقم -->
                                            <div class="flex gap-1">
                                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 transition-all duration-200"
                                                    data-index="12">0</div>
                                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 transition-all duration-200"
                                                    data-index="13">0</div>
                                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 transition-all duration-200"
                                                    data-index="14">0</div>
                                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 transition-all duration-200"
                                                    data-index="15">0</div>
                                            </div>
                                        </div>

                                        <!-- اینپوت مخفی برای کیبورد -->
                                        <input type="text" id="cardNumberInput" class="sr-only" maxlength="16"
                                            autocomplete="off" tabindex="-1">

                                        <!-- اینپوت برای ارسال به سرور -->
                                        <input type="hidden" name="cardnumber" id="cardNumberFinal">
                                    </div>

                                    <div class="flex gap-3">
                                        <button type="submit"
                                            class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-lg font-medium transition-colors">
                                            ✅ ثبت شماره کارت
                                        </button>
                                        <button type="button" id="closepopup"
                                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-3 px-6 rounded-lg font-medium transition-colors">
                                            ❌ انصراف
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            @endif
        </div>
    </main>
@endsection
@section('scripts')
    <script src="{{ asset('assets/js/search-functionality.js') }}"></script>
    <script src="{{ asset('assets/js/popup-functionality.js') }}"></script>
    <script src="{{ asset('assets/js/input-validation.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const digits = document.querySelectorAll('.card-digit');
            const hiddenInput = document.getElementById('cardNumberInput');
            const finalInput = document.getElementById('cardNumberFinal');
            const popup = document.getElementById('popup');
            let currentIndex = 0;
            let cardNumber = ['0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'];

            // فوکس روی اولین مستطیل
            function focusCurrentDigit() {
                digits.forEach((digit, index) => {
                    if (index === currentIndex) {
                        digit.classList.add('border-blue-500', 'bg-blue-50', 'ring-2', 'ring-blue-200');
                        digit.classList.remove('border-gray-300', 'bg-gray-50');
                    } else {
                        digit.classList.remove('border-blue-500', 'bg-blue-50', 'ring-2', 'ring-blue-200');
                        if (cardNumber[index] !== '0') {
                            digit.classList.add('border-green-500', 'bg-green-50');
                            digit.classList.remove('border-gray-300', 'bg-gray-50');
                        } else {
                            digit.classList.add('border-gray-300', 'bg-gray-50');
                            digit.classList.remove('border-green-500', 'bg-green-50');
                        }
                    }
                });
            }

            // بررسی تکمیل همه ارقام
            function checkCompletion() {
                const isComplete = cardNumber.every(digit => digit !== '0');
                if (isComplete) {
                    digits.forEach(digit => {
                        digit.classList.remove('border-gray-300', 'bg-gray-50', 'border-blue-500',
                            'bg-blue-50');
                        digit.classList.add('border-green-500', 'bg-green-100', 'animate-pulse');
                    });

                    // ارسال شماره کارت نهایی
                    finalInput.value = cardNumber.join('');

                    // انیمیشن موفقیت
                    setTimeout(() => {
                        digits.forEach(digit => {
                            digit.classList.remove('animate-pulse');
                        });
                    }, 1000);
                } else {
                    // پاک کردن مقدار اگر کامل نیست
                    finalInput.value = '';
                }
            }

            // ریست کردن فرم
            function resetForm() {
                currentIndex = 0;
                cardNumber = ['0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'];
                digits.forEach((digit, index) => {
                    digit.textContent = '0';
                    digit.classList.remove('border-blue-500', 'bg-blue-50', 'ring-2', 'ring-blue-200',
                        'border-green-500', 'bg-green-50', 'bg-green-100', 'animate-pulse');
                    digit.classList.add('border-gray-300', 'bg-gray-50');
                });
                finalInput.value = '';
                focusCurrentDigit();
            }

            // مدیریت کلیک روی مستطیل‌ها
            digits.forEach((digit, index) => {
                digit.addEventListener('click', function() {
                    currentIndex = index;
                    focusCurrentDigit();
                    hiddenInput.focus();
                });
            });

            // مدیریت ورودی کیبورد
            hiddenInput.addEventListener('input', function(e) {
                const value = e.target.value.replace(/\D/g, '');

                if (value.length > 0) {
                    const lastDigit = value[value.length - 1];

                    // تنظیم رقم در موقعیت فعلی
                    cardNumber[currentIndex] = lastDigit;
                    digits[currentIndex].textContent = lastDigit;

                    // انتقال به مستطیل بعدی
                    if (currentIndex < 15) {
                        currentIndex++;
                        focusCurrentDigit();
                    }

                    // بررسی تکمیل
                    checkCompletion();
                }

                // پاک کردن اینپوت مخفی
                e.target.value = '';
            });

            // مدیریت کلیدهای ویژه
            hiddenInput.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace') {
                    e.preventDefault();

                    // اگر در موقعیت فعلی عددی وجود دارد، آن را پاک کن
                    if (cardNumber[currentIndex] !== '0') {
                        cardNumber[currentIndex] = '0';
                        digits[currentIndex].textContent = '0';
                    }
                    // اگر موقعیت فعلی خالی است و موقعیت قبلی وجود دارد
                    else if (currentIndex > 0) {
                        currentIndex--;
                        cardNumber[currentIndex] = '0';
                        digits[currentIndex].textContent = '0';
                    }

                    focusCurrentDigit();
                    checkCompletion();
                }

                if (e.key === 'ArrowLeft' && currentIndex > 0) {
                    e.preventDefault();
                    currentIndex--;
                    focusCurrentDigit();
                }

                if (e.key === 'ArrowRight' && currentIndex < 15) {
                    e.preventDefault();
                    currentIndex++;
                    focusCurrentDigit();
                }
            });

            // مدیریت باز شدن پاپ‌آپ
            const openButtons = document.querySelectorAll('#openpopup');
            openButtons.forEach(button => {
                button.addEventListener('click', function() {
                    popup.classList.toggle('hidden');


                    // ریست کردن فرم
                    resetForm();

                    // فوکس خودکار با تاخیر کوتاه
                    setTimeout(() => {
                        hiddenInput.focus();
                    }, 100);
                });
            });

            // مدیریت بستن پاپ‌آپ
            const closeButton = document.getElementById('closepopup');
            if (closeButton) {
                closeButton.addEventListener('click', function() {
                    popup.classList.add('hidden');
                    popup.style.display = 'none';
                });
            }

            // بستن پاپ‌آپ با کلیک روی پس‌زمینه
            popup.addEventListener('click', function(e) {
                if (e.target === popup) {
                    popup.classList.add('hidden');
                    popup.style.display = 'none';
                }
            });

            // کلیک روی کل منطقه برای فوکس
            const cardContainer = document.querySelector('[dir="ltr"]');
            if (cardContainer) {
                cardContainer.addEventListener('click', function() {
                    hiddenInput.focus();
                });
            }

            // بستن پاپ‌آپ با کلید Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !popup.classList.contains('hidden')) {
                    popup.classList.add('hidden');
                    popup.style.display = 'none';
                }
            });
        });


    </script>

@endsection
