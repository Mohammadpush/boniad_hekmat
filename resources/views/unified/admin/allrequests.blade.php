@extends('layouts.unified')

@section('head')
    <!-- استایل‌های عمومی -->
    <link rel="stylesheet" href="{{ asset('assets/css/common/animations.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/common/scroll-containers.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/common/ui-elements.css') }}">

    <!-- استایل‌های مخصوص این صفحه -->
    <link rel="stylesheet" href="{{ asset('assets/css/pages/allrequests/styles.css') }}">
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
                    <a href="{{ route('unified.addoreditrequests') }}"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        ثبت اولین درخواست
                    </a>
                </div>
            @else
                @php
                    // مرتب‌سازی بر اساس تاریخ (جدیدترین ابتدا)
                    $requests = $requests->sortByDesc('created_at');

                    // انتخاب نوع دسته‌بندی (پیش‌فرض: وضعیت)
                    $currentGroupType = request('group_type', 'status');
                    $sortOrder = request('sort_order', 'desc'); // desc یا asc

                    // دسته‌بندی درخواست‌ها بر اساس وضعیت
                    $groupedRequests = $requests->groupBy('story');
                    $statusLabels = [
                        'submit' => ['label' => '📤 ارسال شده', 'color' => 'blue'],
                        'check' => ['label' => '🔍 در حال بررسی', 'color' => 'yellow'],
                        'epointment' => ['label' => '📅 ملاقات', 'color' => 'purple'],
                        'accept' => ['label' => '✅ تایید شده', 'color' => 'green'],
                        'reject' => ['label' => '❌ رد شده', 'color' => 'red'],
                    ];

                    // دسته‌بندی بر اساس پایه
                    $gradeGroups = [
                        'elementary' => ['label' => '🎒 ابتدایی (1-6)', 'grades' => ['اول', 'دوم', 'سوم', 'چهارم', 'پنجم', 'ششم'], 'color' => 'green'],
                        'middle' => ['label' => '🎓 متوسطه اول (7-9)', 'grades' => ['هفتم', 'هشتم', 'نهم'], 'color' => 'blue'],
                        'high' => ['label' => '🏆 متوسطه دوم (10-12)', 'grades' => ['دهم', 'یازدهم', 'دوازدهم'], 'color' => 'purple']
                    ];

                    // دسته‌بندی بر اساس حروف الفبا
                    $alphabetGroups = [
                        'group1' => ['label' => '🔤 الف - چ', 'chars' => ['آ', 'ا', 'ب', 'پ', 'ت', 'ث', 'ج', 'چ'], 'color' => 'red'],
                        'group2' => ['label' => '🔤 ح - ع', 'chars' => ['ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'ژ', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع'], 'color' => 'orange'],
                        'group3' => ['label' => '🔤 غ - م', 'chars' => ['غ', 'ف', 'ق', 'ک', 'گ', 'ل', 'م'], 'color' => 'yellow'],
                        'group4' => ['label' => '🔤 ن - ی', 'chars' => ['ن', 'و', 'ه', 'ی'], 'color' => 'green'],
                        'group5' => ['label' => '🔤 سایر حروف', 'chars' => [], 'color' => 'gray']
                    ];
                @endphp

                <div class="w-full min-w-0">
                    <!-- آمار کلی -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-100 text-sm">کل درخواست‌ها</p>
                                    <p class="text-2xl font-bold">{{ $requests->count() }}</p>
                                </div>
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                    📊
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-100 text-sm">تایید شده</p>
                                    <p class="text-2xl font-bold">{{ $requests->where('story', 'accept')->count() }}</p>
                                </div>
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                    ✅
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-yellow-100 text-sm">در حال بررسی</p>
                                    <p class="text-2xl font-bold">{{ $requests->where('story', 'check')->count() }}</p>
                                </div>
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                    🔍
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-100 text-sm">دسته‌بندی/مرتب‌سازی</p>
                                    <p class="text-lg font-bold">
                                        {{ $currentGroupType === 'status' ? 'وضعیت' :
                                           ($currentGroupType === 'grade' ? 'پایه' : 'حروف الفبا') }}
                                        @if($currentGroupType !== 'status')
                                            / {{ $sortOrder === 'asc' ? 'صعودی' : 'نزولی' }}
                                        @endif-
                                    </p>
                                </div>
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                    {{ $currentGroupType === 'status' ? '📊' :
                                       ($currentGroupType === 'grade' ? '🎓' : '🔤') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <!-- کنترل‌های دسته‌بندی -->
                        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-4 mb-6 border border-blue-100">
                            <div class="flex items-center mb-3">

                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">تنظیمات نمایش و مرتب‌سازی</h3>
                                        <div class="search-bar" action="" dir="ltr">
            <input class="search-input" required="" name="search" type="search" id="searchInput"
                autocomplete="off" placeholder="جستجو..." dir="rtl">
            <button type="reset" class="search-btn"
                onclick="this.previousElementSibling.value=''; this.previousElementSibling.blur();">
                <span>Search/Close</span>
            </button>
        </div>
                            </div>

                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="text-sm font-medium text-gray-700">دسته‌بندی بر اساس:</span>
                                    <div class="flex gap-2">
                                        <button onclick="changeGroupType('status')"
                                           class="control-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $currentGroupType === 'status' ? 'bg-blue-500 text-white shadow-md' : 'bg-white text-gray-600 hover:bg-blue-50 border border-gray-200' }}">
                                            📊 وضعیت
                                        </button>
                                        <button onclick="changeGroupType('grade')"
                                           class="control-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $currentGroupType === 'grade' ? 'bg-blue-500 text-white shadow-md' : 'bg-white text-gray-600 hover:bg-blue-50 border border-gray-200' }}">
                                            🎓 پایه تحصیلی
                                        </button>
                                        <button onclick="changeGroupType('alphabet')"
                                           class="control-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $currentGroupType === 'alphabet' ? 'bg-blue-500 text-white shadow-md' : 'bg-white text-gray-600 hover:bg-blue-50 border border-gray-200' }}">
                                            🔤 حروف الفبا
                                        </button>
                                    </div>
                                </div>

                                @if($currentGroupType !== 'status')
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-medium text-gray-700">ترتیب:</span>
                                    <div class="flex gap-2">
                                        <button onclick="changeSortOrder('asc')"
                                           class="control-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $sortOrder === 'asc' ? 'bg-green-500 text-white shadow-md' : 'bg-white text-gray-600 hover:bg-green-50 border border-gray-200' }}">
                                            ↑ صعودی
                                        </button>
                                        <button onclick="changeSortOrder('desc')"
                                           class="control-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $sortOrder === 'desc' ? 'bg-red-500 text-white shadow-md' : 'bg-white text-gray-600 hover:bg-red-50 border border-gray-200' }}">
                                            ↓ نزولی
                                        </button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        @php
                            // تعیین گروه‌ها بر اساس نوع انتخاب شده
                            if ($currentGroupType === 'status') {
                                $currentGroups = $statusLabels;
                                $groupedData = $groupedRequests;

                                // مرتب‌سازی برای هر گروه وضعیت (اگر نیاز باشد)
                                foreach ($groupedData as $key => $group) {
                                    $groupedData[$key] = $group->sortByDesc('created_at'); // همیشه بر اساس تاریخ
                                }
                            } elseif ($currentGroupType === 'grade') {
                                $currentGroups = $gradeGroups;
                                $groupedData = [];
                                foreach ($gradeGroups as $key => $group) {
                                    $groupRequests = $requests->filter(function($request) use ($group) {
                                        return in_array($request->grade, $group['grades']);
                                    });

                                    // مرتب‌سازی بر اساس پایه
                                    if ($sortOrder === 'asc') {
                                        // صعودی: از پایه کم به زیاد
                                        $gradeOrder = ['اول' => 1, 'دوم' => 2, 'سوم' => 3, 'چهارم' => 4, 'پنجم' => 5, 'ششم' => 6,
                                                      'هفتم' => 7, 'هشتم' => 8, 'نهم' => 9, 'دهم' => 10, 'یازدهم' => 11, 'دوازدهم' => 12];
                                        $groupRequests = $groupRequests->sortBy(function($request) use ($gradeOrder) {
                                            return $gradeOrder[$request->grade] ?? 999;
                                        });
                                    } else {
                                        // نزولی: از پایه زیاد به کم
                                        $gradeOrder = ['دوازدهم' => 1, 'یازدهم' => 2, 'دهم' => 3, 'نهم' => 4, 'هشتم' => 5, 'هفتم' => 6,
                                                      'ششم' => 7, 'پنجم' => 8, 'چهارم' => 9, 'سوم' => 10, 'دوم' => 11, 'اول' => 12];
                                        $groupRequests = $groupRequests->sortBy(function($request) use ($gradeOrder) {
                                            return $gradeOrder[$request->grade] ?? 999;
                                        });
                                    }

                                    if ($groupRequests->count() > 0) {
                                        $groupedData[$key] = $groupRequests;
                                    }
                                }
                            } else { // alphabet
                                $currentGroups = $alphabetGroups;
                                $groupedData = [];

                                // ابتدا همه درخواست‌ها را دسته‌بندی کنیم
                                $usedRequests = collect(); // برای جلوگیری از تکرار

                                foreach ($alphabetGroups as $key => $group) {
                                    if ($key === 'group5') {
                                        // گروه سایر حروف - درخواست‌هایی که در گروه‌های قبلی قرار نگرفته‌اند
                                        $groupRequests = $requests->filter(function($request) use ($usedRequests) {
                                            return !$usedRequests->contains('id', $request->id);
                                        });
                                    } else {
                                        // گروه‌های معمولی
                                        $groupRequests = $requests->filter(function($request) use ($group, $usedRequests) {
                                            if ($usedRequests->contains('id', $request->id)) {
                                                return false; // از تکرار جلوگیری می‌کنیم
                                            }

                                            $name = trim($request->name);
                                            if (empty($name)) return false;

                                            $firstChar = mb_substr($name, 0, 1, 'UTF-8');
                                            $isMatch = in_array($firstChar, $group['chars']);

                                            return $isMatch;
                                        });

                                        // درخواست‌های این گروه را به لیست استفاده شده اضافه می‌کنیم
                                        $usedRequests = $usedRequests->merge($groupRequests);
                                    }

                                    if ($sortOrder === 'asc') {
                                        $groupRequests = $groupRequests->sortBy('name');
                                    } else {
                                        $groupRequests = $groupRequests->sortByDesc('name');
                                    }

                                    if ($groupRequests->count() > 0) {
                                        $groupedData[$key] = $groupRequests;
                                    }
                                }
                            }
                        @endphp

                        @foreach ($currentGroups as $groupKey => $groupInfo)
                            @if (isset($groupedData[$groupKey]) && $groupedData[$groupKey]->count() > 0)
                                <!-- Debug info - موقتی -->
                                @if($currentGroupType === 'alphabet')
                                    <!-- Debug: نمایش تعداد واقعی -->
                                    {{-- <div class="text-xs text-red-600 mb-2">
                                        Debug: گروه {{ $groupKey }} - تعداد: {{ $groupedData[$groupKey]->count() }}
                                        نام‌ها: {{ $groupedData[$groupKey]->pluck('name')->implode(', ') }}
                                    </div> --}}
                                @endif
                                <!-- دسته‌بندی هر گروه -->
                                <div class="mb-8 category-section category-transition">
                                    <!-- عنوان دسته -->
                                    <div class="flex items-center justify-between mb-4">
                                        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                                            <span class="w-1 h-8 bg-{{ $groupInfo['color'] }}-500 rounded-full"></span>
                                            {{ $groupInfo['label'] }}
                                            <span
                                                class="bg-{{ $groupInfo['color'] }}-100 text-{{ $groupInfo['color'] }}-700 px-3 py-1 rounded-full text-sm font-medium">
                                                {{ $groupedData[$groupKey]->count() }} درخواست
                                            </span>
                                        </h2>
                                    </div>

                                    <!-- کانتینر اسکرول افقی -->
                                    <div class="relative">
                                        <div class="scroll-wrapper ">
                                            <div id="scroll-{{ $groupKey }}" class="horizontal-scroll-container show-scrollbar min-w-0">
                                            @foreach ($groupedData[$groupKey] as $request)
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

                                                    <!-- جزئیات اضافی -->
                                                    <div class="mt-4 w-full">

                                                            <div class="flex justify-between items-center text-xs text-gray-600">
                                                                <span>تاریخ ثبت:</span>
                                                                <span>{{ Jalalian::fromDateTime($request->created_at)->format('H:i Y/m/d ') }}</span>
                                                            </div>

                                                    </div>

                                                    <!-- افکت دکوراتیو -->
                                                    <div
                                                        class="absolute -top-4 -left-4 w-16 h-16 bg-gradient-to-br from-blue-200 to-purple-200 rounded-full opacity-20">
                                                    </div>
                                                    <div
                                                        class="absolute -bottom-4 -right-4 w-12 h-12 bg-gradient-to-br from-green-200 to-blue-200 rounded-full opacity-20">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

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
    <!-- اسکریپت‌های عمومی -->
    <script src="{{ asset('assets/js/search-functionality.js') }}"></script>
    <script src="{{ asset('assets/js/popup-functionality.js') }}"></script>
    <script src="{{ asset('assets/js/input-validation.js') }}"></script>

    <!-- اسکریپت مخصوص کارت بانکی (مشترک) -->
    <script src="{{ asset('assets/js/pages/myrequests/card-manager.js') }}"></script>
<script>

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

        // متغیرهای سراسری
        let currentGroupType = '{{ $currentGroupType }}';
        let currentSortOrder = '{{ $sortOrder }}';
        let allRequests = @json($requests->values()->all());

        // گروه‌بندی و برچسب‌ها
        const statusLabels = {
            'submit': {label: '📤 ارسال شده', color: 'blue'},
            'check': {label: '🔍 در حال بررسی', color: 'yellow'},
            'epointment': {label: '📅 ملاقات', color: 'purple'},
            'accept': {label: '✅ تایید شده', color: 'green'},
            'reject': {label: '❌ رد شده', color: 'red'}
        };

        const gradeGroups = {
            'elementary': {label: '🎒 ابتدایی (1-6)', grades: ['اول', 'دوم', 'سوم', 'چهارم', 'پنجم', 'ششم'], color: 'green'},
            'middle': {label: '🎓 متوسطه اول (7-9)', grades: ['هفتم', 'هشتم', 'نهم'], color: 'blue'},
            'high': {label: '🏆 متوسطه دوم (10-12)', grades: ['دهم', 'یازدهم', 'دوازدهم'], color: 'purple'}
        };

        const alphabetGroups = {
            'group1': {label: '🔤 الف - چ', chars: ['آ', 'ا', 'ب', 'پ', 'ت', 'ث', 'ج', 'چ'], color: 'red'},
            'group2': {label: '🔤 ح - ع', chars: ['ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'ژ', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع'], color: 'orange'},
            'group3': {label: '🔤 غ - م', chars: ['غ', 'ف', 'ق', 'ک', 'گ', 'ل', 'م'], color: 'yellow'},
            'group4': {label: '🔤 ن - ی', chars: ['ن', 'و', 'ه', 'ی'], color: 'green'},
            'group5': {label: '🔤 سایر حروف', chars: [], color: 'gray'}
        };

        // تابع گروه‌بندی درخواست‌ها
        function groupRequests(requests, groupType, sortOrder) {
            let groupedData = {};

            if (groupType === 'status') {
                // گروه‌بندی بر اساس وضعیت
                Object.keys(statusLabels).forEach(status => {
                    const statusRequests = requests.filter(req => req.story === status);
                    if (statusRequests.length > 0) {
                        // همیشه بر اساس تاریخ مرتب‌سازی
                        groupedData[status] = statusRequests.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                    }
                });
            } else if (groupType === 'grade') {
                // گروه‌بندی بر اساس پایه
                Object.keys(gradeGroups).forEach(groupKey => {
                    const group = gradeGroups[groupKey];
                    const groupRequests = requests.filter(req => group.grades.includes(req.grade));

                    if (groupRequests.length > 0) {
                        // مرتب‌سازی بر اساس پایه
                        const gradeOrder = sortOrder === 'asc'
                            ? {'اول': 1, 'دوم': 2, 'سوم': 3, 'چهارم': 4, 'پنجم': 5, 'ششم': 6, 'هفتم': 7, 'هشتم': 8, 'نهم': 9, 'دهم': 10, 'یازدهم': 11, 'دوازدهم': 12}
                            : {'دوازدهم': 1, 'یازدهم': 2, 'دهم': 3, 'نهم': 4, 'هشتم': 5, 'هفتم': 6, 'ششم': 7, 'پنجم': 8, 'چهارم': 9, 'سوم': 10, 'دوم': 11, 'اول': 12};

                        groupedData[groupKey] = groupRequests.sort((a, b) => (gradeOrder[a.grade] || 999) - (gradeOrder[b.grade] || 999));
                    }
                });
            } else if (groupType === 'alphabet') {
                // گروه‌بندی بر اساس حروف الفبا
                let usedRequests = [];

                Object.keys(alphabetGroups).forEach(groupKey => {
                    if (groupKey === 'group5') {
                        // گروه سایر حروف
                        const groupRequests = requests.filter(req => !usedRequests.includes(req.id));
                        if (groupRequests.length > 0) {
                            groupedData[groupKey] = sortOrder === 'asc'
                                ? groupRequests.sort((a, b) => a.name.localeCompare(b.name, 'fa'))
                                : groupRequests.sort((a, b) => b.name.localeCompare(a.name, 'fa'));
                        }
                    } else {
                        const group = alphabetGroups[groupKey];
                        const groupRequests = requests.filter(req => {
                            if (usedRequests.includes(req.id)) return false;

                            const name = req.name?.trim();
                            if (!name) return false;

                            const firstChar = name.charAt(0);
                            return group.chars.includes(firstChar);
                        });

                        if (groupRequests.length > 0) {
                            usedRequests.push(...groupRequests.map(req => req.id));
                            groupedData[groupKey] = sortOrder === 'asc'
                                ? groupRequests.sort((a, b) => a.name.localeCompare(b.name, 'fa'))
                                : groupRequests.sort((a, b) => b.name.localeCompare(a.name, 'fa'));
                        }
                    }
                });
            }

            return groupedData;
        }

        // تابع بازنمایی دسته‌ها
        function renderGroups(groupedData, groupType) {
            const container = document.querySelector('.space-y-8');
            const groupsContainer = container.querySelector('.mb-8:last-child')?.parentElement || container;

            // حذف دسته‌های قبلی
            const existingSections = groupsContainer.querySelectorAll('.category-section');
            existingSections.forEach(section => section.remove());

            const currentGroups = groupType === 'status' ? statusLabels :
                                 groupType === 'grade' ? gradeGroups : alphabetGroups;

            Object.keys(groupedData).forEach(groupKey => {
                const requests = groupedData[groupKey];
                const groupInfo = currentGroups[groupKey];

                if (requests && requests.length > 0) {
                    const sectionHTML = createGroupSection(groupKey, groupInfo, requests, groupType);
                    groupsContainer.insertAdjacentHTML('beforeend', sectionHTML);
                }
            });
        }

        // تابع ایجاد HTML برای هر دسته
        function createGroupSection(groupKey, groupInfo, requests, groupType) {
            let cardsHTML = '';

            requests.forEach(request => {
                cardsHTML += createCardHTML(request, groupType);
            });

            return `
                <div class="mb-8 category-section category-transition">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                            <span class="w-1 h-8 bg-${groupInfo.color}-500 rounded-full"></span>
                            ${groupInfo.label}
                            <span class="bg-${groupInfo.color}-100 text-${groupInfo.color}-700 px-3 py-1 rounded-full text-sm font-medium">
                                ${requests.length} درخواست
                            </span>
                        </h2>
                    </div>
                    <div class="relative">
                        <div class="scroll-wrapper">
                            <div id="scroll-${groupKey}" class="horizontal-scroll-container show-scrollbar">
                                ${cardsHTML}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // تابع ایجاد HTML برای هر کارت
        function createCardHTML(request, groupType) {
            const statusMap = {
                'submit': {class: 'bg-blue-100 text-blue-700 border-blue-200', text: '📤 ارسال شده'},
                'accept': {class: 'bg-green-100 text-green-700 border-green-200', text: '✅ تایید شده'},
                'check': {class: 'bg-yellow-100 text-yellow-700 border-yellow-200', text: '🔍 در حال بررسی'},
                'reject': {class: 'bg-red-100 text-red-700 border-red-200', text: '❌ رد شده'},
                'epointment': {class: 'bg-purple-100 text-purple-700 border-purple-200', text: '📅 ملاقات'}
            };

            const status = statusMap[request.story] || {class: 'bg-gray-100 text-gray-700 border-gray-200', text: '❓ نامشخص'};

            let extraInfo = '';
            if (groupType === 'grade') {
                extraInfo = `<span>پایه:</span><span class="font-medium">${request.grade}</span>`;
            } else if (groupType === 'alphabet') {
                extraInfo = `<span>نام:</span><span class="font-medium">${request.name}</span>`;
            } else {
                const statusText = request.story === 'submit' ? 'ارسال شده' :
                                 request.story === 'accept' ? 'تایید شده' :
                                 request.story === 'check' ? 'در حال بررسی' :
                                 request.story === 'reject' ? 'رد شده' :
                                 request.story === 'epointment' ? 'ملاقات' : 'نامشخص';
                extraInfo = `<span>وضعیت:</span><span class="font-medium">${statusText}</span>`;
            }

            // تبدیل تاریخ به فرمت مناسب
            const createdAt = request.created_at;
            // فرض می‌کنیم که سرور تاریخ جلالی را آماده کرده
            const createdDate = createdAt; // یا هر فرمت‌دهی که نیاز دارید

            return `
                <div class="card-hover flex-shrink-0 flex flex-col items-center bg-gradient-to-br from-white to-gray-50 w-72 h-96 justify-center rounded-3xl shadow-lg border border-gray-200 p-6 relative overflow-hidden">
                    <div class="absolute top-4 right-4">
                        <div class="status-badge px-3 py-1 rounded-full text-xs font-medium border ${status.class}">
                            ${status.text}
                        </div>
                    </div>
                    <div class="relative mb-4">
                        <img src="/img/${request.imgpath}" alt="تصویر کاربر" class="w-24 h-24 rounded-full object-cover shadow-md border-4 border-white">
                        <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-green-500 rounded-full border-2 border-white"></div>
                    </div>
                    <div class="text-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">${request.name}</h3>
                        <p class="text-sm text-gray-500">پایه: ${request.grade}</p>
                    </div>
                    <div class="flex gap-3 w-full">
                        <a href="{{ route('unified.addoreditrequests') }}?id=${request.id}" class="action-btn flex-1 bg-blue-500 hover:bg-blue-600 text-white rounded-xl text-sm font-medium text-center shadow-md hover:shadow-lg flex items-center justify-center py-3 gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            ویرایش
                        </a>
                        <form method="POST" action="/unified/requestdetail/${request.id}" class="flex-1">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''}">
                            <button type="submit" class="action-btn flex-1 w-full bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm font-medium text-center shadow-md hover:shadow-lg flex items-center py-3 justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                مشاهده
                            </button>
                        </form>
                    </div>
                    <div class="mt-4 w-full">
                        <div class="bg-gray-50 rounded-xl p-3">
                            <div class="flex justify-between items-center text-xs text-gray-600">
                                <span>تاریخ ثبت:</span>
                                <span>${createdDate}</span>
                            </div>
                            <div class="flex justify-between items-center text-xs text-gray-600 mt-1">
                                ${extraInfo}
                            </div>
                        </div>
                    </div>
                    <div class="absolute -top-4 -left-4 w-16 h-16 bg-gradient-to-br from-blue-200 to-purple-200 rounded-full opacity-20"></div>
                    <div class="absolute -bottom-4 -right-4 w-12 h-12 bg-gradient-to-br from-green-200 to-blue-200 rounded-full opacity-20"></div>
                </div>
            `;
        }

        // تابع به‌روزرسانی دکمه‌ها
        function updateButtons() {
            // به‌روزرسانی دکمه‌های دسته‌بندی
            document.querySelectorAll('[onclick^="changeGroupType"]').forEach(btn => {
                const type = btn.getAttribute('onclick').match(/'([^']+)'/)[1];
                if (type === currentGroupType) {
                    btn.className = btn.className.replace(/bg-white.*?border-gray-200/, 'bg-blue-500 text-white shadow-md');
                } else {
                    btn.className = btn.className.replace(/bg-blue-500.*?shadow-md/, 'bg-white text-gray-600 hover:bg-blue-50 border border-gray-200');
                }
            });

            // به‌روزرسانی دکمه‌های مرتب‌سازی
            document.querySelectorAll('[onclick^="changeSortOrder"]').forEach(btn => {
                const order = btn.getAttribute('onclick').match(/'([^']+)'/)[1];
                if (order === currentSortOrder) {
                    if (order === 'asc') {
                        btn.className = btn.className.replace(/bg-white.*?border-gray-200/, 'bg-green-500 text-white shadow-md');
                    } else {
                        btn.className = btn.className.replace(/bg-white.*?border-gray-200/, 'bg-red-500 text-white shadow-md');
                    }
                } else {
                    if (order === 'asc') {
                        btn.className = btn.className.replace(/bg-green-500.*?shadow-md/, 'bg-white text-gray-600 hover:bg-green-50 border border-gray-200');
                    } else {
                        btn.className = btn.className.replace(/bg-red-500.*?shadow-md/, 'bg-white text-gray-600 hover:bg-red-50 border border-gray-200');
                    }
                }
            });
        }

        // تابع تغییر نوع دسته‌بندی
        function changeGroupType(newGroupType) {
            if (currentGroupType === newGroupType) return;

            currentGroupType = newGroupType;
            const groupedData = groupRequests(allRequests, currentGroupType, currentSortOrder);
            renderGroups(groupedData, currentGroupType);
            updateButtons();
        }

        // تابع تغییر ترتیب مرتب‌سازی
        function changeSortOrder(newSortOrder) {
            if (currentSortOrder === newSortOrder) return;

            currentSortOrder = newSortOrder;
            const groupedData = groupRequests(allRequests, currentGroupType, currentSortOrder);
            renderGroups(groupedData, currentGroupType);
            updateButtons();
        }

    </script>

@endsection
