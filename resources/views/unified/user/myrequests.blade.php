@extends('layouts.unified')

@section('head')
    <!-- استایل‌های عمومی -->
    <link rel="stylesheet" href="{{ asset('assets/css/common/animations.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/common/scroll-containers.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/common/ui-elements.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/myrequests/scroling-text.css') }}">
    <!-- استایل‌های مخصوص این صفحه -->
    <link rel="stylesheet" href="{{ asset('assets/css/pages/myrequests/styles.css') }}">
@endsection

@section('title')
    <h1 class="text-black text-[clamp(0.7rem,3.5vw,1.25rem)] font-bold h-fit my-auto">
        درخواست های من
    </h1>
    @if (!$requests->isEmpty())
    <a href="{{ route('unified.requestform') }}"
        class="bg-green-500 text-center rounded-3xl flex w-[clamp(100px,25vw,150px)] max-[342px]:fixed max-[342px]:bottom-0 max-[342px]:w-[100vw] max-[342px]:left-0  max-[342px]:rounded-none z-20 h-[50px] justify-center ">

        <span class="h-fit m-auto text-[clamp(0.5rem,3vw,1rem)] max-[342px]:text-base">افزودن درخواست +</span>
    </a>
    @endif
@endsection

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
                <!-- Container برای کارت‌های wrap شونده -->
                <div class="flex flex-wrap gap-14 justify-center ">

                    @foreach ($requests as $request)
                        <!-- کارت -->
                        <div
                            class="card-hover bg-gradient-to-br from-white to-gray-50 w-72 h-96 flex flex-col items-center justify-center rounded-3xl shadow-lg border border-gray-200 p-6 relative overflow-hidden select-none">

                            <!-- آیکون وضعیت در گوشه -->

                            <!-- تصویر پروفایل -->
                            <div class="relative mb-4">
                                <img src="{{ route('img', ['filename' => $request->imgpath]) }}" alt="تصویر کاربر"
                                    class="w-24 h-24 rounded-full object-cover shadow-md border-4 border-white">
                            </div>
                            <div class="absolute bottom-[13.5rem] left-1/2 transform -translate-x-1/2">
                                <div
                                    class="status-badge px-3 py-1 text-white rounded-full text-xs font-medium
                            {{ $request->story === 'submit'
                                ? 'bg-blue-500 '
                                : ($request->story === 'accept'
                                    ? 'bg-green-500'
                                    : ($request->story === 'check'
                                        ? 'bg-yellow-500'
                                        : ($request->story === 'reject'
                                            ? 'bg-red-500'
                                            : ($request->story === 'epointment'
                                                ? 'bg-pink-600'
                                                : 'bg-gray-500')))) }}">
                                    {{ $request->story === 'submit'
                                        ? ' ارسال شده'
                                        : ($request->story === 'accept'
                                            ? ' تایید شده'
                                            : ($request->story === 'check'
                                                ? ' در  حال بررسی'
                                                : ($request->story === 'reject'
                                                    ? ' رد شده'
                                                    : ($request->story === 'epointment'
                                                        ? '  قرارملاقات'
                                                        : ' نامشخص')))) }}
                                </div>
                            </div>

                            <!-- اطلاعات کاربر -->
                            <div class="text-center mb-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-1">
                                    {{ $request->name }}</h3>
                                <p class="text-sm text-gray-500">پایه: {{ $request->grade }}</p>
                            </div>

                            <!-- دکمه‌های عملکرد -->
                            <div class="flex gap-3 w-full mt-8">

                                <button type="button"
                                    onclick="openRequestDetailModal({
                                                                id: {{ $request->id }},
                                                                name: '{{ addslashes($request->name) }}',
                                                                grade: '{{ addslashes($request->grade) }}',
                                                                story: '{{ $request->story }}',
                                                                imgpath_url: '{{ route('img', ['filename' => $request->imgpath]) }}',
                                                                nationalcode: '{{ addslashes($request->nationalcode) }}',
                                                                birthdate: '{{ addslashes(Jalalian::fromDateTime($request->birthdate)->format(' Y/m/d ')) }}',
                                                                phone: '{{ addslashes($request->phone) }}',
                                                                telephone: '{{ addslashes($request->telephone) }}',
                                                                school: '{{ addslashes($request->school) }}',
                                                                principal: '{{ addslashes($request->principal) }}',
                                                                major_name: '{{ $request->major ? addslashes($request->major->name) : '' }}',
                                                                last_score: '{{ addslashes($request->last_score) }}',
                                                                school_telephone: '{{ addslashes($request->school_telephone) }}',
                                                                english_proficiency: {{ $request->english_proficiency ?? 0 }},
                                                                gradesheetpath: '{{ $request->gradesheetpath }}',
                                                                gradesheetpath_url: '{{ $request->gradesheetpath ? route('img', ['filename' => $request->gradesheetpath]) : '' }}',
                                                                rental: '{{ $request->rental }}',
                                                                address: '{{ addslashes($request->address) }}',
                                                                siblings_count: '{{ $request->siblings_count }}',
                                                                siblings_rank: '{{ $request->siblings_rank }}',
                                                                know: '{{ addslashes($request->know) }}',
                                                                counseling_method: '{{ addslashes($request->counseling_method) }}',
                                                                why_counseling_method: '{{ addslashes($request->why_counseling_method) }}',
                                                                father_name: '{{ addslashes($request->father_name) }}',
                                                                father_phone: '{{ addslashes($request->father_phone) }}',
                                                                father_job: '{{ addslashes($request->father_job) }}',
                                                                father_income: '{{ $request->father_income }}',
                                                                father_job_address: '{{ addslashes($request->father_job_address) }}',
                                                                mother_name: '{{ addslashes($request->mother_name) }}',
                                                                mother_phone: '{{ addslashes($request->mother_phone) }}',
                                                                mother_job: '{{ addslashes($request->mother_job) }}',
                                                                mother_income: '{{ $request->mother_income }}',
                                                                mother_job_address: '{{ addslashes($request->mother_job_address) }}',
                                                                motivation: '{{ addslashes($request->motivation) }}',
                                                                spend: '{{ addslashes($request->spend) }}',
                                                                how_am_i: '{{ addslashes($request->how_am_i) }}',
                                                                future: '{{ addslashes($request->future) }}',
                                                                favorite_major: '{{ addslashes($request->favorite_major) }}',
                                                                help_others: '{{ addslashes($request->help_others) }}',
                                                                suggestion: '{{ addslashes($request->suggestion) }}'
                                                            })"
                                    class="action-btn flex-1 w-1/2 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm font-medium text-center shadow-md hover:shadow-lg flex items-center py-3 justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    مشاهده {{  $request->story=='accept' ? '' : ' / ویرایش' }}
                                </button>
                                @if ($request->story == 'accept')
                                    <button onclick="opencardnumberpopup({
                                    id: {{ $request->id  }},
                                    cardnumber : {{ $request->cardnumber }}
                                    })"
                                        class=" bg-blue-600 w-1/2 hover:bg-blue-700  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-white font-medium text-[10px] py-3 px-4 rounded-lg shadow-sm transition-colors duration-200 ease-in-out border border-transparent">
                                        ویرایش شماره کارت
                                    </button>
                                @endif
                            </div>



                            <!-- افکت دکوراتیو -->
                            <div
                                class="absolute -top-4 -left-4 w-16 h-16 bg-gradient-to-br from-blue-200 to-purple-200 rounded-full opacity-20">
                            </div>
                            <div
                                class="absolute -bottom-4 -right-4 w-12 h-12 bg-gradient-to-br from-green-200 to-blue-200 rounded-full opacity-20">
                            </div>

                            @if ($request->story == 'accept' && empty($request->cardnumber))
                                <div
                                    class=" absolute w-full h-full bg-black bg-opacity-50 flex flex-col justify-center p-3   pt-[14.3rem]">
                                    <!-- بخش متن اسکرولینگ: در یک خط، با overflow hidden و انیمیشن سفارشی -->
                                    <div
                                        class="overflow-hidden whitespace-nowrap bg-gray-50 border border-gray-200 rounded-md py-3 px-4">
                                        <p class="inline-block text-gray-800 font-medium text-sm scrolling-text pr-4">
                                            برای دریافت بورسیه، حساب بانکی در بانک پارسیان برای دانش آموز خود ایجاد کنید
                                        </p>
                                    </div>

                                    <!-- باتن شیک و مرتب: بدون لوس‌بازی، با hover effect ملایم -->
                                    <button onclick="opencardnumberpopup({ id: {{ $request->id ?? 'default-id' }} })"
                                        class="w-full bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-white font-medium py-3 px-4 rounded-lg shadow-sm transition-colors duration-200 ease-in-out border border-transparent">
                                        افزودن شماره کارت
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
    </main>

    {{-- اضافه کردن مودال جزئیات درخواست --}}
    @include('unified.user.request-popup')
    </div>
    <!-- Modal -->
    <div id="popup" class=" fixed inset-0 bg-black bg-opacity-50 z-50" style="display: none">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-2xl p-8 max-w-[35.6rem] w-full mx-4 shadow-2xl">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                        </svg>

                    </div>

                    <h3 class="text-xl font-bold text-gray-800 mb-2" id="card-title">افزودن شماره کارت</h3>
                    <p class="text-gray-600">شماره کارت بانک پارسیان خود را وارد کنید</p>
                </div>

                <form method="post" action="{{ route('unified.storecard') }}">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-4 text-center" id="card-digit">شماره
                            کارت</label>
                        <input type="hidden" name="id" value="" id="cardnumberpopupid">
                        <!-- 16 مستطیل برای نمایش ارقام -->
                        <div class="flex  justify-center items-center gap-2 mb-4" dir="ltr">
                            <!-- گروه اول: 4 رقم -->
                            <div class="flex gap-1">
                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 text-black  transition-all duration-200"
                                    data-index="0">X</div>
                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 text-black  transition-all duration-200"
                                    data-index="1">X</div>
                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 text-black transition-all duration-200"
                                    data-index="2">X</div>
                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 text-black transition-all duration-200"
                                    data-index="3">X</div>
                            </div>

                            <!-- خط تیره -->
                            <div class="text-gray-400 text-xl font-bold">-</div>

                            <!-- گروه دوم: 4 رقم -->
                            <div class="flex gap-1">
                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 text-black transition-all duration-200"
                                    data-index="4">X</div>
                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 text-black transition-all duration-200"
                                    data-index="5">X</div>
                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 text-black transition-all duration-200"
                                    data-index="6">X</div>
                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 text-black transition-all duration-200"
                                    data-index="7">X</div>
                            </div>

                            <!-- خط تیره -->
                            <div class="text-gray-400 text-xl font-bold">-</div>

                            <!-- گروه سوم: 4 رقم -->
                            <div class="flex gap-1">
                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 text-black transition-all duration-200"
                                    data-index="8">X</div>
                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 text-black transition-all duration-200"
                                    data-index="9">X</div>
                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 text-black transition-all duration-200"
                                    data-index="10">X</div>
                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 text-black transition-all duration-200"
                                    data-index="11">X</div>
                            </div>

                            <!-- خط تیره -->
                            <div class="text-gray-400 text-xl font-bold">-</div>

                            <!-- گروه چهارم: 4 رقم -->
                            <div class="flex gap-1">
                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 text-black transition-all duration-200"
                                    data-index="12">X</div>
                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 text-black transition-all duration-200"
                                    data-index="13">X</div>
                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 text-black transition-all duration-200"
                                    data-index="14">X</div>
                                <div class="card-digit w-[1.5rem] h-[2.4rem] border-2 border-gray-300 rounded-lg flex items-center justify-center text-lg font-mono bg-gray-50 text-black transition-all duration-200"
                                    data-index="15">X</div>
                            </div>
                        </div>

                        <!-- اینپوت مخفی برای کیبورد -->
                        <input type="text" id="cardNumberInput" class="sr-only" maxlength="16" autocomplete="off"
                            tabindex="-1">

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
    @endif
    </div>
    </main>
@endsection
@section('scripts')
    <!-- اسکریپت‌های عمومی -->

    <script src="{{ asset('assets/js/input-validation.js') }}"></script>

    <!-- اسکریپت مخصوص این صفحه -->
    <script src="{{ asset('assets/js/pages/myrequests/card-manager.js') }}"></script>
    <script src="{{ asset('assets/js/pages/myrequests/live-update.js') }}"></script>
    <script src="{{ asset('assets/js/numinput.js') }}"></script>
@endsection
