@extends('layouts.unified')
@section('head')
<link rel="stylesheet" href="{{ asset('assets/css/jalalydatepicker.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/flatpicker.css') }}">
<style>
    .step-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .status-badge {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    .progress-ring {
        width: 120px;
        height: 120px;
    }
    .progress-ring-circle {
        stroke-width: 8;
        fill: transparent;
        stroke-dasharray: 283;
        stroke-dashoffset: 283;
        transform: rotate(-90deg);
        transform-origin: 50% 50%;
        transition: stroke-dashoffset 0.35s;
    }
    .icon-wrapper {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    /* Print Styles */
    @media print {
        /* Hide elements with .hide class */
        .hide,#sidebar {
            display: none !important;
        }

        /* Page setup */
        @page {
            size: A4;
            margin: 1cm;
        }

        /* Body styles for print */
        body {
            font-size: 12pt;
            line-height: 1.4;
            color: #000 !important;
            background: white !important;
        }

        /* Main layout adjustments */
        main {
            padding: 0 !important;
            background: white !important;
        }

        /* Card styles for print */
        .info-card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
            margin-bottom: 1rem !important;
            page-break-inside: avoid;
        }

        /* Grid adjustments */
        .grid {
            display: block !important;
        }

        .xl\:col-span-1, .xl\:col-span-2 {
            width: 100% !important;
        }

        /* Typography adjustments */
        h1, h2, h3 {
            color: #000 !important;
        }

        /* Remove gradients and shadows */
        .bg-gradient-to-r,
        .icon-wrapper {
            background: #666 !important;
        }

        /* Status badge */
        .status-badge {
            background: #666 !important;
            animation: none !important;
        }

        /* Progress bar adjustments */
        .bg-gradient-to-r {
            background: #666 !important;
        }

    }
</style>
@endsection

@section('page-title', 'جزئیات درخواست')

@section('content')
<main class="flex-1 p-8 bg-gray-50 ">
    <!-- Header Section -->
    <header class="bg-white hide shadow-lg rounded-2xl p-6 mb-8 border border-gray-200">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4 space-x-reverse">
                <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-600 to-purple-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">جزئیات درخواست بورسیه</h1>
                    <p class="text-gray-600 mt-1">متقاضی: {{ $userrequest->name }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3 space-x-reverse">
                <button type="button" onclick="window.print()"
                    class="hide flex items-center space-x-2 space-x-reverse bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>پرینت فرم</span>
                </button>
                <button type="button" onclick="history.back()"
                    class="hide flex items-center space-x-2 space-x-reverse bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>بازگشت</span>
                </button>
            </div>
        </div>
    </header>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 ">
        <!-- Combined Profile & Personal Information Card -->
        <div class="xl:col-span-1 w-">
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6 border border-gray-200 transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:shadow-2xl">
                <!-- Profile Section -->
                <div class="text-center mb-8">
                    <div class="relative mx-auto mb-6 ">
                        <div class="w-32 h-32 mx-auto rounded-full overflow-hidden bg-gray-200 border-4 border-white shadow-lg">
                            <img src="{{ route('img', ['filename' => $userrequest->imgpath]) }}"
                                alt="{{ $userrequest->name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2">
                            @php
                                $statusColor = match($userrequest->story) {
                                    'check' => 'bg-yellow-500',
                                    'accept' => 'bg-green-500',
                                    'reject' => 'bg-red-500',
                                    'appointment' => 'bg-pink-600',
                                    'submit' => 'bg-blue-500',
                                    default => 'bg-gray-500'
                                };
                                $statusText = match($userrequest->story) {
                                    'check' => 'در انتظار',
                                    'accept' => 'تایید شده',
                                    'reject' => 'رد شده',
                                    'appointment' => 'قرار ملاقات',
                                    'submit' => 'ارسال شده',
                                    default => 'نامشخص'
                                };
                            @endphp
                            <span class="status-badge px-3 py-1 {{ $statusColor }} text-white text-xs font-bold rounded-full shadow-lg">
                                {{ $statusText }}
                            </span>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $userrequest->name }}</h2>
                    <p class="text-lg text-gray-600 mb-6">پایه {{ $userrequest->grade }}</p>
                </div>

                <!-- Personal Information Section -->
                <div class="border-t pt-6">
                    <div class="flex items-center mb-4">
                        <div class="icon-wrapper w-8 h-8 rounded-lg flex items-center justify-center mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">اطلاعات شخصی</h3>
                    </div>
<div class="flex flex-row w-full justify-around">

    <div class="flex flex-col  ">

        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">کد ملی</label>
            <p class="text-base font-mono font-semibold text-gray-800">{{ $userrequest->nationalcode }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">تاریخ تولد</label>
            <p class="text-base font-semibold text-gray-800">{{ $userrequest->birthdate }}</p>
        </div>
    </div>
    <div class="flex flex-col">

        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">شماره موبایل</label>
            <p class="text-base font-mono font-semibold text-gray-800">{{ $userrequest->phone }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">تلفن ثابت</label>
            <p class="text-base font-mono font-semibold text-gray-800">{{ $userrequest->telephone ?? 'وارد نشده' }}</p>
        </div>
    </div>
</div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3 mt-8 hide">
                    <div id='openpopup'
                        class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition cursor-pointer font-medium shadow-lg text-center">
                        📅 تعیین زمان ملاقات
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('unified.accept', [$userrequest->id]) }}"
                            class="py-3 px-4 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 transition text-center font-medium shadow-lg">
                            ✅ تایید
                        </a>
                        <a href="{{ route('unified.reject', [$userrequest->id]) }}"
                            class="py-3 px-4 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition text-center font-medium shadow-lg">
                            ❌ رد
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Details -->
        <div class="xl:col-span-2 space-y-6">

            <!-- Educational Information -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:shadow-2xl">
                <div class="flex items-center mb-6">
                    <div class="icon-wrapper w-12 h-12 rounded-xl flex items-center justify-center mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">اطلاعات تحصیلی</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-[7rem]">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">پایه تحصیلی</label>
                            <p class="text-lg font-semibold text-gray-800">{{ $userrequest->grade }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">نام مدرسه</label>
                            <p class="text-lg font-semibold text-gray-800">{{ $userrequest->school }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">نام مدیر مدرسه</label>
                            <p class="text-lg font-semibold text-gray-800">{{ $userrequest->principal }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">رشته تحصیلی</label>
                            <p class="text-lg font-semibold text-gray-800">
                                @if($userrequest->major_id && $userrequest->major)
                                    {{ $userrequest->major->name }}
                                @else
                                    ندارد
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">معدل ترم قبل</label>
                            <p class="text-lg font-semibold text-gray-800">{{ $userrequest->last_score }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">تلفن مدرسه</label>
                            <p class="text-lg font-semibold text-gray-800 font-mono">{{ $userrequest->school_telephone ?? 'وارد نشده' }}</p>
                        </div>

                    </div>
                                    <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">سطح زبان انگلیسی</label>
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <div class="flex-1 bg-gray-200 rounded-full h-6">
                            <div class="bg-gradient-to-r from-blue-600 to-purple-600 h-6 rounded-full transition-all duration-500"
                                 style="width: {{ $userrequest->english_proficiency }}%"></div>
                        </div>
                        <span class="text-lg font-semibold text-gray-800">{{ $userrequest->english_proficiency }}%</span>
                    </div>
                </div>
                </div>

                <!-- Grade Sheet Preview -->
                @if($userrequest->gradesheetpath)
                <div class="mt-6 p-4 bg-gray-50 rounded-xl">
                    <label class="block text-sm font-medium text-gray-500 mb-3">کارنامه ترم قبل</label>
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <img src="{{ route('img', ['filename' => $userrequest->gradesheetpath]) }}"
                             alt="کارنامه" class="w-16 h-16 object-cover rounded-lg border-2 border-gray-300">
                        <a href="{{ route('img', ['filename' => $userrequest->gradesheetpath]) }}"
                           target="_blank"
                           class="text-blue-600 hover:text-blue-800 font-medium">مشاهده کارنامه</a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Housing Information -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mt-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:shadow-2xl">
            <div class="flex items-center mb-6">
                <div class="icon-wrapper w-12 h-12 rounded-xl flex items-center justify-center mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">اطلاعات مسکن</h3>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">وضعیت مسکن</label>
                    <p class="text-lg font-semibold text-gray-800">
                        @if($userrequest->rental == '0')
                            🏠 ملکی
                        @else
                            🏠 استیجاری
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">آدرس کامل</label>
                    <p class="text-lg font-semibold text-gray-800 leading-relaxed">{{ $userrequest->address }}</p>
                </div>
            </div>
        </div>

        <!-- Family Information -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:shadow-2xl">
            <div class="flex items-center mb-6">
                <div class="icon-wrapper w-12 h-12 rounded-xl flex items-center justify-center mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">اطلاعات خانوادگی</h3>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">تعداد فرزندان</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $userrequest->siblings_count }} نفر</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">رتبه متقاضی</label>
                        <p class="text-lg font-semibold text-gray-800">فرزند {{ $userrequest->siblings_rank }}ام</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">نحوه آشنایی با بنیاد</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $userrequest->know }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">روش مشاوره مورد نظر</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $userrequest->counseling_method }}</p>
                    </div>
                    @if($userrequest->why_counseling_method)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">دلیل انتخاب روش مشاوره</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $userrequest->why_counseling_method }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Parents Information -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mt-6">
        <!-- Father Information -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:shadow-2xl">
            <div class="flex items-center mb-6">
                <div class="icon-wrapper w-12 h-12 rounded-xl flex items-center justify-center mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">اطلاعات پدر</h3>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">نام پدر</label>
                    <p class="text-lg font-semibold text-gray-800">{{ $userrequest->father_name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">شماره موبایل</label>
                    <p class="text-lg font-semibold text-gray-800 font-mono">{{ $userrequest->father_phone }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">شغل</label>
                    <p class="text-lg font-semibold text-gray-800">{{ $userrequest->father_job }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">درآمد ماهانه</label>
                    <p class="text-lg font-semibold text-gray-800">{{ number_format($userrequest->father_income) }} تومان</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">آدرس محل کار</label>
                    <p class="text-lg font-semibold text-gray-800 leading-relaxed">{{ $userrequest->father_job_address }}</p>
                </div>
            </div>
        </div>

        <!-- Mother Information -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:shadow-2xl">
            <div class="flex items-center mb-6">
                <div class="icon-wrapper w-12 h-12 rounded-xl flex items-center justify-center mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">اطلاعات مادر</h3>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">نام مادر</label>
                    <p class="text-lg font-semibold text-gray-800">{{ $userrequest->mother_name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">شماره موبایل</label>
                    <p class="text-lg font-semibold text-gray-800 font-mono">{{ $userrequest->mother_phone }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">شغل</label>
                    <p class="text-lg font-semibold text-gray-800">{{ $userrequest->mother_job }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">درآمد ماهانه</label>
                    <p class="text-lg font-semibold text-gray-800">{{ number_format($userrequest->mother_income) }} تومان</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">آدرس محل کار</label>
                    <p class="text-lg font-semibold text-gray-800 leading-relaxed">{{ $userrequest->mother_job_address }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Final Questions -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:shadow-2xl mt-6">
        <div class="flex items-center mb-6">
            <div class="icon-wrapper w-12 h-12 rounded-xl flex items-center justify-center mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-800">سوالات نهایی و انگیزه‌ها</h3>
        </div>

        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-2">انگیزه درخواست بورسیه</label>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-gray-800 leading-relaxed">{{ $userrequest->motivation }}</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 mb-2">نحوه استفاده از کمک مالی</label>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-gray-800 leading-relaxed">{{ $userrequest->spend }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">معرفی خود</label>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-gray-800 leading-relaxed">{{ $userrequest->how_am_i }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">برنامه‌های آینده</label>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-gray-800 leading-relaxed">{{ $userrequest->future }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">رشته مورد علاقه</label>
                    <p class="text-lg font-semibold text-gray-800">{{ $userrequest->favorite_major }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">آمادگی کمک به دیگران</label>
                    <p class="text-lg font-semibold text-gray-800">{{ $userrequest->help_others }}</p>
                </div>
            </div>

            @if($userrequest->suggestion)
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-2">پیشنهادات برای بهتر شدن عملکرد بنیاد</label>
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                    <p class="text-gray-800 leading-relaxed">{{ $userrequest->suggestion }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Popup Modal -->
    <div class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden hide" id='popup'>
        <form method="post" action="{{ route('unified.epointment',['id' => $userrequest->id]) }}"
              class="bg-white rounded-2xl p-8 min-w-96 max-w-md mx-4 shadow-2xl">
            @csrf
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">تعیین زمان ملاقات</h3>
                <p class="text-gray-600">لطفاً تاریخ و زمان مورد نظر خود را انتخاب کنید</p>
            </div>

            <div class="mb-6">
                <input data-jdp name="mydate" id="mydate" type="text" value=""
                    placeholder="انتخاب تاریخ و زمان ملاقات"
                    class="w-full h-12 bg-gray-50 border-2 border-gray-200 text-gray-700 rounded-xl text-center focus:border-blue-500 focus:outline-none focus:bg-white transition-all">
            </div>

            <div class="flex gap-4">
                <button type="submit"
                    class="flex-1 bg-gradient-to-r from-green-600 to-green-700 text-white py-3 px-6 rounded-xl hover:from-green-700 hover:to-green-800 transition font-medium shadow-lg">
                    ✅ تایید ملاقات
                </button>
                <button type="button" id='closepopup'
                    class="flex-1 bg-gradient-to-r from-gray-600 to-gray-700 text-white py-3 px-6 rounded-xl hover:from-gray-700 hover:to-gray-800 transition font-medium shadow-lg">
                    ❌ انصراف
                </button>
            </div>
        </form>
    </div>
</main>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/libraris/jalalidatepicker.js') }}"></script>
    <script src="{{ asset('assets/js/libraris/flatpicker.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Jalali Datepicker
            jalaliDatepicker.startWatch({
                selector: 'input[data-jdp]',
                persianDigits: true,
                date: true,                    // فعال کردن انتخاب تاریخ
                time: true,                    // فعال کردن انتخاب زمان
                hasSecond: true,               // فعال کردن انتخاب ثانیه
                minDate: 'today',              // حداقل تاریخ از امروز
                showTodayBtn: true,            // نمایش دکمه امروز
                showEmptyBtn: true,            // نمایش دکمه خالی
                showCloseBtn: true,            // نمایش دکمه بستن
                autoHide: true,                // بستن خودکار پس از انتخاب
                hideAfterChange: false         // عدم بستن خودکار برای تنظیم زمان
            });

            var openPopup = document.getElementById('openpopup');
            var popup = document.getElementById('popup');
            var closePopup = document.getElementById('closepopup');

            openPopup.addEventListener('click', function() {
                popup.classList.remove('hidden');
                popup.classList.add('flex', 'flex-col');
            });

            closePopup.addEventListener('click', function(e) {
                e.preventDefault();
                popup.classList.add('hidden');
                popup.classList.remove('flex', 'flex-col');
            });

            // Close popup when clicking outside
            popup.addEventListener('click', function(e) {
                if (e.target === popup) {
                    popup.classList.add('hidden');
                    popup.classList.remove('flex', 'flex-col');
                }
            });
        });
    </script>
@endsection
