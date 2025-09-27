@extends('layouts.unified')

@section('page-title', 'جزئیات کاربر')

@section('content')
    @php
        use Morilog\Jalali\Jalalian;
    @endphp

    <main class="flex-1 p-8">

        <!-- اطلاعات کاربر -->
        @if ($user->role == 'admin' && $user->profile)
            <!-- پروفایل ادمین کامل -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 ml-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    اطلاعات پروفایل ادمین
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- عکس پروفایل -->
                    <div class="flex flex-col items-center">
                        <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-blue-200 shadow-lg">
                            @if ($user->profile->imgpath)
                                <img src="{{ route('img', ['filename' => $user->profile->imgpath]) }}" alt="تصویر پروفایل"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- اطلاعات شخصی -->
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 ml-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-sm text-gray-600">نام:</span>
                            <span class="text-sm font-medium text-gray-800 mr-2">{{ $user->profile->name }}</span>
                        </div>

                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 ml-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            <span class="text-sm text-gray-600">جنسیت:</span>
                            <span
                                class="text-sm font-medium text-gray-800 mr-2">{{ $user->profile->female == '1' ? 'خانم' : 'آقا' }}</span>
                        </div>

                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 ml-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="text-sm text-gray-600">کد ملی:</span>
                            <span
                                class="text-sm font-medium text-gray-800 mr-2">{{ $user->profile->nationalcode ?? 'وارد نشده' }}</span>
                        </div>
                    </div>

                    <!-- اطلاعات تماس و شغلی -->
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 ml-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span class="text-sm text-gray-600">تلفن:</span>
                            <span class="text-sm font-medium text-gray-800 mr-2">{{ $user->profile->phone }}</span>
                        </div>

                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 ml-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6" />
                            </svg>
                            <span class="text-sm text-gray-600">موقعیت شغلی:</span>
                            <span
                                class="text-sm font-medium text-gray-800 mr-2">{{ $user->profile->position ?? 'وارد نشده' }}</span>
                        </div>

                        @if (isset($user->profile->isactive))
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 ml-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm text-gray-600">وضعیت:</span>
                                <span
                                    class="text-sm font-medium {{ $user->profile->isactive ? 'text-green-600' : 'text-red-600' }} mr-2">
                                    {{ $user->profile->isactive ? 'فعال' : 'غیرفعال' }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- اطلاعات اضافی -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <span>نام کاربری سیستم: {{ $user->name }}</span>
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                            {{ $user->role === 'admin' ? 'ادمین' : 'مستر' }}
                        </span>
                    </div>
                </div>
            </div>
        @elseif ($user->role == 'admin' && !$user->profile)
            <!-- هشدار پروفایل ناقص -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 mb-6">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-yellow-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-yellow-800">پروفایل تکمیل نشده</h3>
                        <p class="text-yellow-700">این ادمین هنوز پروفایل خود را تکمیل نکرده است.</p>
                        <p class="text-sm text-yellow-600 mt-1">نام کاربری: {{ $user->name }}</p>
                    </div>
                </div>
                @if (Auth::user()->role == 'master')
                    @if ($user->role == 'admin')
                        <!-- دکمه برداشتن ادمین -->
                        <a href="{{ route('unified.nadmin', ['id' => $user->id]) }}" class="inline-block mt-4">
                            <button
                                class="flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                </svg>
                                <span>برداشتن ادمین</span>
                            </button>
                        </a>
                    @else
                        <!-- دکمه ادمین کردن -->
                        <a href="{{ route('unified.admin', ['id' => $user->id]) }}" class="inline-block">
                            <button
                                class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                </svg>
                                <span>ادمین کردن</span>
                            </button>
                        </a>
                    @endif
                @endif
            </div>
        @else
            <!-- اطلاعات کاربر عادی -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 ml-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    اطلاعات کاربر
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- اطلاعات اصلی کاربر -->
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 ml-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-sm text-gray-600">نام کاربری:</span>
                            <span class="text-sm font-medium text-gray-800 mr-2">{{ $user->name }}</span>
                        </div>

                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 ml-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm text-gray-600">نقش:</span>
                            <span
                                class="text-sm font-medium text-gray-800 mr-2">{{ $user->role === 'user' ? 'کاربر عادی' : $user->role }}</span>
                        </div>

                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 ml-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm text-gray-600">تاریخ عضویت:</span>
                            <span class="text-sm font-medium text-gray-800 mr-2">
                                {{ Jalalian::fromDateTime($user->created_at)->format('Y/m/d H:i') }}
                            </span>
                        </div>

                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 ml-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="text-sm text-gray-600">تعداد درخواست‌ها:</span>
                            <span class="text-sm font-medium text-blue-600 mr-2">{{ $user->requests->count() }}
                                درخواست</span>
                        </div>
                    </div>


                </div>

                <!-- اطلاعات اضافی -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <span>آخرین بروزرسانی: {{ Jalalian::fromDateTime($user->updated_at)->format('Y/m/d H:i') }}</span>
                        <span
                            class="bg-{{ $user->role === 'admin' ? 'blue' : 'gray' }}-100 text-{{ $user->role === 'admin' ? 'blue' : 'gray' }}-800 px-2 py-1 rounded-full text-xs">
                            {{ $user->role === 'admin' ? 'ادمین' : ($user->role === 'user' ? 'کاربر عادی' : $user->role) }}
                        </span>
                    </div>
                </div>
            </div>
        @endif
        <!-- عملیات مدیریتی -->
        <div class="w-full bg-amber-50 shadow-md p-4 rounded-lg  flex flex-col items-center mb-4">

            <h4 class="text-md font-medium text-gray-700 mb-3">عملیات مدیریتی</h4>
            <div class="flex flex-col justify-center">
                <div class="flex flex-wrap gap-3">
                    <!-- دکمه حذف کاربر -->
                    <a href="{{ route('unified.deleteuser', ['id' => $user->id]) }}" class="inline-block">
                        <button
                            class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                            <span>حذف کاربر</span>
                        </button>
                    </a>

                    @if (Auth::user()->role == 'master')
                        @if ($user->role == 'admin')
                            <!-- دکمه برداشتن ادمین -->
                            <a href="{{ route('unified.nadmin', ['id' => $user->id]) }}" class="inline-block">
                                <button
                                    class="flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                    </svg>
                                    <span>برداشتن ادمین</span>
                                </button>
                            </a>
                        @else
                            <!-- دکمه ادمین کردن -->
                            <a href="{{ route('unified.admin', ['id' => $user->id]) }}" class="inline-block">
                                <button
                                    class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                    </svg>
                                    <span>ادمین کردن</span>
                                </button>
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- جدول درخواست‌های کاربر -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">درخواست‌های کاربر</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50 max-[728px]:hidden">
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                عکس</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                نام و نام خانوادگی</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                نام کاربری</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                عملکردها</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                وضعیت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 0; @endphp
                        @if ($user->requests->isEmpty())
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-600">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        این کاربر هیچ درخواستی ندارد.
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach ($user->requests as $request)
                                <tr
                                    class="text-center max-[728px]:flex max-[728px]:flex-col max-[728px]:shadow-lg max-[728px]:rounded-lg max-[728px]:justify-center max-[728px]:mb-4 transition-transform transform group-hover:scale-105 max-[728px]:relative {{ $i % 2 == 0 ? 'max-[728px]:bg-[#e0e0dfc9]' : 'max-[728px]:bg-[#ecece5ea]' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <img src="{{ route('img', ['filename' => $request->imgpath]) }}"
                                            alt="تصویر درخواست"
                                            class="w-10 h-10 max-[728px]:w-40 max-[728px]:h-40 m-auto rounded-lg">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap max-[728px]:hidden">{{ $request->name }}
                                        {{ $request->female }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap max-[728px]:hidden">{{ $request->user->name }}
                                    </td>

                                    <td class="hidden px-6 py-4 max-[728px]:block m-auto text-3xl max-[405px]:text-[20px]">
                                        <div class="flex gap-2">
                                            <span>{{ $request->name }} {{ $request->female }}</span>
                                            <span
                                                class="text-[18px] mt-3.5 text-gray-700 max-[405px]:text-[10px]">{{ $request->user->name }}</span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div
                                            class="flex flex-col sm:flex-row gap-2 justify-center items-center max-[728px]:flex-row max-[728px]:gap-3">
                                            <!-- دکمه مشاهده جزئیات -->
                                            <form method="POST"
                                                action="{{ route('unified.requestdetail', ['id' => $request->id]) }}"
                                                class="inline-block">
                                                @csrf
                                                <button type="submit"
                                                    class="flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="max-[520px]:hidden"> جزئیات</span>
                                                </button>
                                            </form>

                                            <!-- دکمه پیام‌ها -->
                                            <a href="{{ route('unified.message', ['id' => $request->id]) }}"
                                                class="inline-block">
                                                <button
                                                    class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                                    </svg>
                                                    <span class="max-[520px]:hidden">پیام‌ها</span>
                                                </button>
                                            </a>
                                        </div>
                                    </td>

                                    <td class="whitespace-nowrap">
                                        <div class="flex flex-row items-center gap-2 justify-center">
                                            @switch($request->story)
                                                @case('submit')
                                                    <span
                                                        class="text-blue-800 max-[728px]:bg-[#84e2ff65] font-bold w-full h-full p-3 rounded-b-lg">
                                                        ارسال شده
                                                    </span>
                                                @break

                                                @case('accept')
                                                    <span
                                                        class="text-green-800 max-[728px]:bg-[#8eff8465] font-semibold w-full h-full p-3 rounded-b-lg">
                                                        قبول شد
                                                    </span>
                                                @break

                                                @case('check')
                                                    <span
                                                        class="text-yellow-800 max-[728px]:bg-[#ff8b4865] font-semibold w-full h-full p-3 rounded-b-lg">
                                                        درحال بررسی
                                                    </span>
                                                @break

                                                @case('cancel')
                                                    <span
                                                        class="text-yellow-800 max-[728px]:bg-[#ff8b4865] font-semibold w-full h-full p-3 rounded-b-lg">
                                                        لغو شده
                                                    </span>
                                                @break

                                                @case('reject')
                                                    <span
                                                        class="text-red-800 max-[728px]:bg-[#ff484865] font-semibold w-full h-full p-3 rounded-b-lg">
                                                        رد شد
                                                    </span>
                                                @break

                                                @case('epointment')
                                                    <span
                                                        class="text-purple-800 max-[728px]:bg-[#df8dff65] font-semibold w-full h-full flex flex-row justify-center max-[728px]:text-xl p-3 rounded-b-lg">
                                                        ملاقات: <p>
                                                            {{ Jalalian::fromDateTime($request->date)->format('Y/m/d H:i') }}</p>
                                                    </span>
                                                @break

                                                @default
                                                    <span class="text-red-600">ناشناخته</span>
                                            @endswitch
                                        </div>
                                    </td>
                                </tr>

                                {{-- @if ($request->story == 'accept' && empty($request->cardnumber))
                                    <tr>
                                        <td colspan="5" class="text-center w-full bg-green-50 p-4">
                                            <div class="flex flex-col items-center">
                                                <strong class="text-green-800 mb-2">توجه</strong>
                                                <span class="text-green-700 mb-3">یک حساب بانک پارسیان به نام دانش آموز
                                                    بسازید</span>
                                                <a href="{{ route('unified.', ['id' => $request->id]) }}"
                                                    class="bg-green-500 hover:bg-green-600 text-white rounded-2xl px-3.5 py-2 transition-colors">
                                                    افزودن شماره کارت
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endif --}}
                                @php $i++; @endphp
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection
