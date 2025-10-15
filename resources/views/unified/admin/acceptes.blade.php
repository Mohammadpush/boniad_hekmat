@extends('layouts.unified')

@section('head')
    <!-- استایل‌های عمومی -->
    <link rel="stylesheet" href="{{ asset('assets/css/common/progress.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/common/ui-elements.css') }}">

    <!-- استایل‌های مخصوص این صفحه -->
    <link rel="stylesheet" href="{{ asset('assets/css/pages/acceptes/styles.css') }}">
@endsection

@section('page-title', 'درخواست‌های پذیرفته شده')

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
                    <h3 class="text-lg font-medium text-gray-800 mb-2">هیچ درخواست پذیرفته شده‌ای یافت نشد</h3>
                    <p class="text-gray-600 mb-6">در حال حاضر درخواستی تایید نشده است</p>
                </div>
            @else
                <!-- Container برای کارت‌ها -->
                <div class="flex flex-wrap gap-14 justify-center">
                    @foreach ($requests as $request)
                        <!-- کارت -->
                        <div class="card-hover bg-gradient-to-br from-white to-gray-50 w-80 flex flex-col rounded-3xl shadow-lg border border-gray-200 p-6 relative overflow-hidden select-none transition-all duration-300 hover:shadow-2xl hover:-translate-y-2">

                            <!-- بج وضعیت تایید شده -->
                            <div class="absolute top-4 left-4">
                                <div class="px-3 py-1 bg-green-500 text-white rounded-full text-xs font-medium">
                                    ✅ تایید شده
                                </div>
                            </div>

                            <!-- تصویر پروفایل -->
                            <div class="relative mb-4 mt-2">
                                <img src="{{ route('img', ['filename' => $request->imgpath]) }}" alt="تصویر کاربر"
                                    class="w-24 h-24 rounded-full object-cover shadow-md border-4 border-white mx-auto">
                            </div>

                            <!-- اطلاعات کاربر -->
                            <div class="text-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 mb-1">
                                    {{ $request->name }} {{ $request->female }}
                                </h3>
                                <p class="text-sm text-gray-500">نام کاربری: {{ $request->user->name }}</p>
                            </div>

                            <!-- شماره کارت -->
                            <div class="mb-4 bg-blue-50 rounded-xl p-3">
                                <p class="text-xs text-gray-600 mb-1 text-center">💳 شماره کارت</p>
                                @if (empty($request->cardnumber))
                                    <p class="text-sm text-red-600 text-center font-medium">شماره کارت ثبت نشده است</p>
                                @else
                                    <p class="text-blue-800 text-center font-mono text-sm cursor-pointer hover:bg-blue-100 transition rounded p-2"
                                       onclick="copyText('{{ $request->cardnumber }}')"
                                       title="کلیک کنید تا کپی شود">
                                        {{ $request->cardnumber }}
                                    </p>
                                @endif
                            </div>

                            <!-- روزشمار -->
                            @if ($request->DailyTracker)
                                @php
                                    $today = \Carbon\Carbon::now()->startOfDay()->addDays(15);
                                    if ($request->DailyTracker->id == 1) {
                                        $today = \Carbon\Carbon::now()->startOfDay()->addDays(31);
                                    }
                                    $start = \Carbon\Carbon::parse($request->DailyTracker->start_date, 'Asia/Tehran')->startOfDay();
                                    $passed = $start->diffInDays($today);
                                    $max = $request->DailyTracker->max_days;
                                    $percent = min(($passed / $max) * 100, 100);
                                @endphp
                                <div class="mb-4">
                                    <div class="bg-gray-200 rounded-full overflow-hidden h-8 relative">
                                        @if ($passed >= $max)
                                            <div class="h-full w-full bg-gradient-to-r from-green-500 to-green-600 flex items-center justify-center">
                                                <button onclick="openScholarshipModal({{ $request->id }})"
                                                    class="w-full h-full text-white font-bold text-sm hover:bg-green-700 transition cursor-pointer rounded-full">
                                                    🎓 دریافت بورسیه
                                                </button>
                                            </div>
                                        @else
                                            <div class="h-full bg-gradient-to-r from-blue-400 to-cyan-500 flex items-center justify-center text-white font-bold text-sm transition-all duration-500"
                                                 style="width: {{ $percent }}%;">
                                                @if ($passed == 0)
                                                    شروع نشده
                                                @else
                                                    {{ $passed }} / {{ $max }} روز
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <p class="text-red-600 text-sm text-center mb-4">⚠️ روز‌شمار موجود نیست</p>
                            @endif

                            <!-- دکمه‌های عملکرد -->
                            <div class="flex gap-3 mt-auto">
                                <form method="POST" action="{{ route('unified.requestdetail', ['id' => $request->id]) }}" class="flex-1">
                                    @csrf
                                    <button type="submit"
                                        class="w-full bg-blue-500 hover:bg-blue-600 text-white rounded-xl py-3 text-sm font-medium transition-colors shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        مشاهده
                                    </button>
                                </form>

                                <a href="{{ route('unified.message', ['id' => $request->id]) }}" class="flex-1">
                                    <button type="button"
                                        class="w-full bg-green-500 hover:bg-green-600 text-white rounded-xl py-3 text-sm font-medium transition-colors shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                        </svg>
                                        پیام
                                    </button>
                                </a>
                            </div>

                            <!-- افکت دکوراتیو -->
                            <div class="absolute -top-4 -left-4 w-16 h-16 bg-gradient-to-br from-green-200 to-blue-200 rounded-full opacity-20"></div>
                            <div class="absolute -bottom-4 -right-4 w-12 h-12 bg-gradient-to-br from-blue-200 to-purple-200 rounded-full opacity-20"></div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </main>

<!-- Success Popup -->
<div id="successPopup" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-sm mx-4 shadow-2xl transform transition-all duration-300" id="successContent">
        <div class="text-center">
            <div class="bg-gradient-to-r from-green-400 to-green-500 w-20 h-20 rounded-full mx-auto mb-6 flex items-center justify-center animate-pulse">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">عملیات موفق!</h3>
            <p class="text-gray-600 mb-6" id="successMessage">بورسیه با موفقیت تعیین شد</p>
            <button onclick="closeSuccessPopup()" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-2 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105">
                تأیید
            </button>
        </div>
    </div>
</div>

<!-- Scholarship Modal -->
<div id="scholarshipModal" class="form fixed inset-0 bg-d bg-black bg-opacity-60 flex flex-col items-center justify-center z-50 hidden backdrop-blur-sm">
    <div class="bg-white rounded-3xl p-8 min-w-96 max-w-md mx-4 shadow-2xl transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
        <form id="scholarshipForm" method="POST">
            @csrf
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="bg-gradient-to-r from-green-400 to-blue-500 w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">تعیین بورسیه</h3>
                <p class="text-gray-600 text-sm">لطفاً اطلاعات بورسیه را کامل کنید</p>
            </div>

            <!-- Hidden Fields -->
            <input type="hidden" name="story" value="scholarship">
            <input type="hidden" name="request_id" id="modalRequestId">

            <!-- Title Field -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                    <svg class="w-4 h-4 ml-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    عنوان بورسیه
                </label>
                <input type="text" name="title" id="title" required maxlength="25"
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-300"
                       placeholder="عنوان بورسیه را وارد کنید">
            </div>

            <!-- Description Field -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                    <svg class="w-4 h-4 ml-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    توضیحات
                </label>
                <textarea name="description" id="description" required rows="3"
                          class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 hover:border-gray-300 resize-none"
                          placeholder="توضیحات مربوط به بورسیه"></textarea>
            </div>

            <!-- Price Field -->
            <div class="mb-8">
                <label for="price" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                    <svg class="w-4 h-4 ml-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    مبلغ بورسیه (تومان)
                </label>
                <div class="relative">
                    <input type="text" name="price" id="price"
                           class="w-full px-4 py-3 pr-12 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all duration-200 hover:border-gray-300"
                           placeholder="مبلغ بورسیه را وارد کنید">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">تومان</span>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 justify-center">
                <button type="submit" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-8 py-3 rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    تعیین بورسیه
                </button>
                <button type="button" onclick="closeScholarshipModal()"
                        class="bg-gradient-to-r from-red-500 to-red-600 text-white px-8 py-3 rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    انصراف
                </button>
            </div>
        </form>
    </div>
</div>
    </main>
@endsection

@section('scripts')
    <!-- اسکریپت‌های عمومی -->
    <script src="{{ asset('assets/js/price-input.js') }}"></script>
    <script src="{{ asset('assets/js/copytext.js') }}"></script>

    <!-- اسکریپت‌های مخصوص این صفحه -->
    <script src="{{ asset('assets/js/pages/acceptes/accepted-manager.js') }}"></script>

    <script>
        // Check for Laravel success message
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize manager if not already done
                if (typeof acceptedManager === 'undefined') {
                    window.acceptedManager = new AcceptedStudentsManager();
                }
                acceptedManager.showSuccessPopup('{{ session('success') }}');
            });
        @endif
    </script>
@endsection
