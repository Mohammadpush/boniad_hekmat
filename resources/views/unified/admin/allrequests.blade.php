@extends('layouts.unified')
@section('head')
    <link rel="stylesheet" href="{{ asset('assets/css/jalalydatepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/flatpicker.css') }}">
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
            width: 100%;
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
            scrollbar-width: none;
            width: 100%;
            max-width: 100%;
            max-height: 450px;
            /* محدود کردن ارتفاع */
            transition: max-height 0.3s ease;
        }

        /* حالت expanded */
        .horizontal-scroll-container[data-expanded="true"] {
            grid-auto-flow: row;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            max-height: none;
            overflow-y: auto;
        }


        /* کارت‌ها در اسکرول افقی */
        .horizontal-scroll-container .card-hover {
            width: 320px;
            min-width: 320px;
            max-width: 320px;
            flex-shrink: 0;
            animation: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
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
        .bg-orange-100 {
            background-color: #fed7aa;
        }

        .text-orange-700 {
            color: #c2410c;
        }

        .bg-orange-500 {
            background-color: #f97316;
        }
    </style>
@endsection

@section('page-title', 'درخواست‌های من')

@section('content')
    @php
        use Morilog\Jalali\Jalalian;
        use Illuminate\Support\Facades\Auth;
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
                        'elementary' => [
                            'label' => '🎒 ابتدایی (1-6)',
                            'grades' => ['اول', 'دوم', 'سوم', 'چهارم', 'پنجم', 'ششم'],
                            'color' => 'green',
                        ],
                        'middle' => [
                            'label' => '🎓 متوسطه اول (7-9)',
                            'grades' => ['هفتم', 'هشتم', 'نهم'],
                            'color' => 'blue',
                        ],
                        'high' => [
                            'label' => '🏆 متوسطه دوم (10-12)',
                            'grades' => ['دهم', 'یازدهم', 'دوازدهم'],
                            'color' => 'purple',
                        ],
                    ];

                    // دسته‌بندی بر اساس حروف الفبا
                    $alphabetGroups = [
                        'group1' => [
                            'label' => '🔤 الف - چ',
                            'chars' => ['آ', 'ا', 'ب', 'پ', 'ت', 'ث', 'ج', 'چ'],
                            'color' => 'red',
                        ],
                        'group2' => [
                            'label' => '🔤 ح - ع',
                            'chars' => ['ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'ژ', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع'],
                            'color' => 'orange',
                        ],
                        'group3' => [
                            'label' => '🔤 غ - م',
                            'chars' => ['غ', 'ف', 'ق', 'ک', 'گ', 'ل', 'م'],
                            'color' => 'yellow',
                        ],
                        'group4' => ['label' => '🔤 ن - ی', 'chars' => ['ن', 'و', 'ه', 'ی'], 'color' => 'green'],
                        'group5' => ['label' => '🔤 سایر حروف', 'chars' => [], 'color' => 'gray'],
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
                                        {{ $currentGroupType === 'status' ? 'وضعیت' : ($currentGroupType === 'grade' ? 'پایه' : 'حروف الفبا') }}
                                        @if ($currentGroupType !== 'status')
                                            / {{ $sortOrder === 'asc' ? 'صعودی' : 'نزولی' }}
                                        @endif-
                                    </p>
                                </div>
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                    {{ $currentGroupType === 'status' ? '📊' : ($currentGroupType === 'grade' ? '🎓' : '🔤') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <!-- کنترل‌های دسته‌بندی -->
                        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-4 mb-6 border border-blue-100">
                            <div class="flex items-center mb-3">

                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">تنظیمات نمایش و مرتب‌سازی</h3>
                                <div class="search-bar" action="" dir="ltr">
                                    <input class="search-input" required="" name="search" type="search"
                                        id="searchInput" autocomplete="off" placeholder="جستجو..." dir="rtl">
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

                                @if ($currentGroupType !== 'status')
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
                                    $groupRequests = $requests->filter(function ($request) use ($group) {
                                        return in_array($request->grade, $group['grades']);
                                    });

                                    // مرتب‌سازی بر اساس پایه
                                    if ($sortOrder === 'asc') {
                                        // صعودی: از پایه کم به زیاد
                                        $gradeOrder = [
                                            'اول' => 1,
                                            'دوم' => 2,
                                            'سوم' => 3,
                                            'چهارم' => 4,
                                            'پنجم' => 5,
                                            'ششم' => 6,
                                            'هفتم' => 7,
                                            'هشتم' => 8,
                                            'نهم' => 9,
                                            'دهم' => 10,
                                            'یازدهم' => 11,
                                            'دوازدهم' => 12,
                                        ];
                                        $groupRequests = $groupRequests->sortBy(function ($request) use ($gradeOrder) {
                                            return $gradeOrder[$request->grade] ?? 999;
                                        });
                                    } else {
                                        // نزولی: از پایه زیاد به کم
                                        $gradeOrder = [
                                            'دوازدهم' => 1,
                                            'یازدهم' => 2,
                                            'دهم' => 3,
                                            'نهم' => 4,
                                            'هشتم' => 5,
                                            'هفتم' => 6,
                                            'ششم' => 7,
                                            'پنجم' => 8,
                                            'چهارم' => 9,
                                            'سوم' => 10,
                                            'دوم' => 11,
                                            'اول' => 12,
                                        ];
                                        $groupRequests = $groupRequests->sortBy(function ($request) use ($gradeOrder) {
                                            return $gradeOrder[$request->grade] ?? 999;
                                        });
                                    }

                                    if ($groupRequests->count() > 0) {
                                        $groupedData[$key] = $groupRequests;
                                    }
                                }
                            } else {
                                // alphabet
                                $currentGroups = $alphabetGroups;
                                $groupedData = [];

                                // ابتدا همه درخواست‌ها را دسته‌بندی کنیم
                                $usedRequests = collect(); // برای جلوگیری از تکرار

                                foreach ($alphabetGroups as $key => $group) {
                                    if ($key === 'group5') {
                                        // گروه سایر حروف - درخواست‌هایی که در گروه‌های قبلی قرار نگرفته‌اند
                                        $groupRequests = $requests->filter(function ($request) use ($usedRequests) {
                                            return !$usedRequests->contains('id', $request->id);
                                        });
                                    } else {
                                        // گروه‌های معمولی
                                        $groupRequests = $requests->filter(function ($request) use (
                                            $group,
                                            $usedRequests,
                                        ) {
                                            if ($usedRequests->contains('id', $request->id)) {
                                                return false; // از تکرار جلوگیری می‌کنیم
                                            }

                                            $name = trim($request->name);
                                            if (empty($name)) {
                                                return false;
                                            }

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
                                @if ($currentGroupType === 'alphabet')
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
                                                <span>{{ $groupedData[$groupKey]->count() }}</span> درخواست
                                            </span>
                                        </h2>

                                        <!-- دکمه مشاهده همه -->
                                        @if ($groupedData[$groupKey]->count() > 3)
                                            <button type="button" onclick="toggleViewAll('{{ $groupKey }}')"
                                                class="view-all-btn flex items-center gap-2 px-4 py-2 bg-{{ $groupInfo['color'] }}-50 hover:bg-{{ $groupInfo['color'] }}-100 text-{{ $groupInfo['color'] }}-700 rounded-lg transition-all duration-200 text-sm font-medium">
                                                <span class="view-all-text">مشاهده همه</span>
                                                <svg class="w-4 h-4 transition-transform duration-200" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>

                                    <!-- کانتینر اسکرول افقی -->
                                    <div class="relative">
                                        <div class="scroll-wrapper">
                                            <div id="scroll-{{ $groupKey }}"
                                                class="horizontal-scroll-container show-scrollbar min-w-0"
                                                data-expanded="false">
                                                @foreach ($groupedData[$groupKey] as $request)
                                                    <!-- کارت -->
                                                    <div class="card-hover bg-gradient-to-br from-white to-gray-50 w-72 h-96 flex flex-col items-center justify-center rounded-3xl shadow-lg border border-gray-200 p-6 relative overflow-hidden select-none"
                                                        id='cardcontainar-{{ $request->id }}'>
                                                        <a href="{{ route('unified.message', ['id' => $request->id]) }}"
                                                            id='messageBtn-{{ $request->id }}'
                                                            class="absolute top-5 bg-blue-400 p-1 rounded-full right-5 opacity-0 transition-opacity duration-300 invisible">
                                                            <svg class="w-5 h-5 text-[#e2e0e0] flex-shrink-0 transition-colors duration-200"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                                            </svg> </a>
                                                        <!-- آیکون وضعیت در گوشه -->

                                                        <!-- تصویر پروفایل -->
                                                        <div class="relative mb-4">
                                                            <img src="{{ route('img', ['filename' => $request->imgpath]) }}"
                                                                alt="تصویر کاربر"
                                                                class="w-24 h-24 rounded-full object-cover shadow-md border-4 border-white">
                                                        </div>
                                                        <div
                                                            class="absolute bottom-[13.5rem] left-1/2 transform -translate-x-1/2">
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
                                                            <p class="text-sm text-gray-500">پایه: {{ $request->grade }}
                                                            </p>
                                                        </div>

                                                        <!-- دکمه‌های عملکرد -->
                                                        <div class="flex gap-3 w-full mt-8">

                                                            <button type="button"
                                                                onclick="openRequestDetailModal({
                                                                this_user_id : {{ Auth::user()->id }},
                                                                user_id : {{ $request->user ? $request->user->id : '' }},
                                                                id: {{ $request->id }},
                                                                name: '{{ addslashes($request->name ?? '') }}',
                                                                grade: '{{ addslashes($request->grade ?? '') }}',
                                                                story: '{{ $request->story ?? '' }}',
                                                                imgpath_url: '{{ route('img', ['filename' => $request->imgpath ?? '']) }}',
                                                                nationalcode: '{{ addslashes($request->nationalcode ?? '') }}',
                                                                birthdate: '{{ $request->birthdate ? addslashes(Jalalian::fromDateTime($request->birthdate)->format(' Y/m/d ')) : '' }}',
                                                                phone: '{{ addslashes($request->phone ?? '') }}',
                                                                telephone: '{{ addslashes($request->telephone ?? '') }}',
                                                                school: '{{ addslashes($request->school ?? '') }}',
                                                                principal: '{{ addslashes($request->principal ?? '') }}',
                                                                major_name: '{{ $request->major ? addslashes($request->major->name) : '' }}',
                                                                last_score: '{{ addslashes($request->last_score ?? '') }}',
                                                                school_telephone: '{{ addslashes($request->school_telephone ?? '') }}',
                                                                english_proficiency: {{ $request->english_proficiency ?? 0 }},
                                                                gradesheetpath: '{{ $request->gradesheetpath ?? '' }}',
                                                                gradesheetpath_url: '{{ $request->gradesheetpath ? route('img', ['filename' => $request->gradesheetpath]) : '' }}',
                                                                rental: '{{ $request->rental ?? '' }}',
                                                                address: '{{ addslashes($request->address ?? '') }}',
                                                                siblings_count: '{{ $request->siblings_count ?? '' }}',
                                                                siblings_rank: '{{ $request->siblings_rank ?? '' }}',
                                                                know: '{{ addslashes($request->know ?? '') }}',
                                                                counseling_method: '{{ addslashes($request->counseling_method ?? '') }}',
                                                                why_counseling_method: '{{ addslashes($request->why_counseling_method ?? '') }}',
                                                                father_name: '{{ addslashes($request->father_name ?? '') }}',
                                                                father_phone: '{{ addslashes($request->father_phone ?? '') }}',
                                                                father_job: '{{ addslashes($request->father_job ?? '') }}',
                                                                father_income: '{{ $request->father_income ?? '' }}',
                                                                father_job_address: '{{ addslashes($request->father_job_address ?? '') }}',
                                                                mother_name: '{{ addslashes($request->mother_name ?? '') }}',
                                                                mother_phone: '{{ addslashes($request->mother_phone ?? '') }}',
                                                                mother_job: '{{ addslashes($request->mother_job ?? '') }}',
                                                                mother_income: '{{ $request->mother_income ?? '' }}',
                                                                mother_job_address: '{{ addslashes($request->mother_job_address ?? '') }}',
                                                                motivation: '{{ addslashes($request->motivation ?? '') }}',
                                                                spend: '{{ addslashes($request->spend ?? '') }}',
                                                                how_am_i: '{{ addslashes($request->how_am_i ?? '') }}',
                                                                future: '{{ addslashes($request->future ?? '') }}',
                                                                favorite_major: '{{ addslashes($request->favorite_major ?? '') }}',
                                                                help_others: '{{ addslashes($request->help_others ?? '') }}',
                                                                suggestion: '{{ addslashes($request->suggestion ?? '') }}'
                                                            },null , true)"
                                                                class="action-btn flex-1 w-1/2 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm font-medium text-center shadow-md hover:shadow-lg flex items-center py-3 justify-center gap-2">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                                مشاهده
                                                            </button>

                                                        </div>



                                                        <!-- روزشمار -->
                                                        @if ($request->DailyTracker)
                                                            @php
                                                                $today = \Carbon\Carbon::now()
                                                                    ->startOfDay()
                                                                    ->addDays(15);
                                                                if ($request->DailyTracker->id == 1) {
                                                                    $today = \Carbon\Carbon::now()
                                                                        ->startOfDay()
                                                                        ->addDays(31);
                                                                }
                                                                $start = \Carbon\Carbon::parse(
                                                                    $request->DailyTracker->start_date,
                                                                    'Asia/Tehran',
                                                                )->startOfDay();
                                                                $passed = $start->diffInDays($today);
                                                                $max = $request->DailyTracker->max_days;
                                                                $percent = min(($passed / $max) * 100, 100);
                                                            @endphp
                                                            <div class="mb-4">
                                                                <div
                                                                    class="bg-gray-200 rounded-b-[20px] overflow-hidden h-8 absolute bottom-0 w-full  left-0">
                                                                    @if ($passed >= $max)
                                                                        <div
                                                                            class="h-full w-full bg-gradient-to-r from-green-500 to-green-600 flex items-center justify-center">
                                                                            <button
                                                                                onclick="openScholarshipModal({{ $request->id }})"
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
                                                                                {{ $passed }} / {{ $max }}
                                                                                روز
                                                                            @endif
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
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

                </div>
            @endif
        </div>
    </main>
    <div class="fixed inset-0 flex w-full h-full bg-black bg-opacity-50 items-center justify-center z-[51] hidden"
        id='epointmet-modal'>
        <form method="post" action="{{ route('unified.epointment') }}"
            class="bg-white rounded-2xl p-8 min-w-96 max-w-md mx-4 shadow-2xl">
            @csrf
            <input type="hidden" name="id" class="requestid" value="">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
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
                <button type="button" onclick="closeModal('epointmet-modal')"
                    class="flex-1 bg-gradient-to-r from-gray-600 to-gray-700 text-white py-3 px-6 rounded-xl hover:from-gray-700 hover:to-gray-800 transition font-medium shadow-lg">
                    ❌ انصراف
                </button>
            </div>
        </form>
    </div>
    <!-- Scholarship Modal -->
    <div id="scholarshipModal"
        class="form fixed inset-0 bg-black bg-opacity-60 flex flex-col items-center justify-center z-50 hidden backdrop-blur-sm">
        <div class="bg-white rounded-3xl p-8 min-w-96 max-w-md mx-4 shadow-2xl transform transition-all duration-300 scale-95 opacity-0"
            id="modalContent">
            <form id="scholarshipForm" method="POST">
                @csrf
                <!-- Header -->
                <div class="text-center mb-8">
                    <div
                        class="bg-gradient-to-r from-green-400 to-blue-500 w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">تعیین بورسیه</h3>
                    <p class="text-gray-600 text-sm">لطفاً اطلاعات بورسیه را کامل کنید</p>
                </div>

                <!-- Hidden Fields -->
                <input type="hidden" name="story" value="scholarship">
                <input type="hidden" name="request_id" id="modalRequestId">

                <!-- Description Field -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <svg class="w-4 h-4 ml-2 text-green-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
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
                        <svg class="w-4 h-4 ml-2 text-yellow-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                            </path>
                        </svg>
                        مبلغ بورسیه (تومان)
                    </label>
                    <div class="relative">
                        <input type="text" name="price" id="price"
                            class="w-full px-4 py-3 pr-12 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all duration-200 hover:border-gray-300"
                            placeholder="مثلاً: 1,000,000" inputmode="numeric">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">تومان</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">فاصل‌گذاری اتوماتیک است</p>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 justify-center">
                    <button type="submit"
                        class="bg-gradient-to-r from-green-500 to-green-600 text-white px-8 py-3 rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        تعیین بورسیه
                    </button>
                    <button type="button" onclick="closeScholarshipModal()"
                        class="bg-gradient-to-r from-red-500 to-red-600 text-white px-8 py-3 rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        انصراف
                    </button>
                </div>
            </form>
        </div>
    </div>
    @include('unified.user.request-popup')

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
                date: true, // فعال کردن انتخاب تاریخ
                time: true, // فعال کردن انتخاب زمان
                hasSecond: true, // فعال کردن انتخاب ثانیه
                minDate: 'today', // حداقل تاریخ از امروز
                showTodayBtn: true, // نمایش دکمه امروز
                showEmptyBtn: true, // نمایش دکمه خالی
                showCloseBtn: true, // نمایش دکمه بستن
                autoHide: true, // بستن خودکار پس از انتخاب
                hideAfterChange: false // عدم بستن خودکار برای تنظیم زمان
            });
        });

        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>
    <script src="{{ asset('assets/js/request-cards.js') }}"></script>
    <script src="{{ asset('assets/js/search-functionality.js') }}"></script>

    <script src="{{ asset('assets/js/input-validation.js') }}"></script>
    {{-- <!-- اسکریپت‌های عمومی -->
    <script src="{{ asset('assets/js/price-input.js') }}"></script> --}}
    <script src="{{ asset('assets/js/copytext.js') }}"></script>

    <!-- اسکریپت‌های مخصوص این صفحه -->
    <script src="{{ asset('assets/js/pages/acceptes/accepted-manager.js') }}"></script>
    <script src="{{ asset('assets/js/pages/myrequests/live-update.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/aceeptes/modal.js') }}"></script> --}}
    <script>
        // متغیرهای سراسری
        let currentGroupType = '{{ $currentGroupType }}';
        let currentSortOrder = '{{ $sortOrder }}';
        let allRequests = @json($requests->values()->all());

        // گروه‌بندی و برچسب‌ها
        const statusLabels = {
            'submit': {
                label: '📤 ارسال شده',
                color: 'blue'
            },
            'check': {
                label: '🔍 در حال بررسی',
                color: 'yellow'
            },
            'epointment': {
                label: '📅 ملاقات',
                color: 'purple'
            },
            'accept': {
                label: '✅ تایید شده',
                color: 'green'
            },
            'reject': {
                label: '❌ رد شده',
                color: 'red'
            }
        };

        const gradeGroups = {
            'elementary': {
                label: '🎒 ابتدایی (1-6)',
                grades: ['اول', 'دوم', 'سوم', 'چهارم', 'پنجم', 'ششم'],
                color: 'green'
            },
            'middle': {
                label: '🎓 متوسطه اول (7-9)',
                grades: ['هفتم', 'هشتم', 'نهم'],
                color: 'blue'
            },
            'high': {
                label: '🏆 متوسطه دوم (10-12)',
                grades: ['دهم', 'یازدهم', 'دوازدهم'],
                color: 'purple'
            }
        };

        const alphabetGroups = {
            'group1': {
                label: '🔤 الف - چ',
                chars: ['آ', 'ا', 'ب', 'پ', 'ت', 'ث', 'ج', 'چ'],
                color: 'red'
            },
            'group2': {
                label: '🔤 ح - ع',
                chars: ['ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'ژ', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع'],
                color: 'orange'
            },
            'group3': {
                label: '🔤 غ - م',
                chars: ['غ', 'ف', 'ق', 'ک', 'گ', 'ل', 'م'],
                color: 'yellow'
            },
            'group4': {
                label: '🔤 ن - ی',
                chars: ['ن', 'و', 'ه', 'ی'],
                color: 'green'
            },
            'group5': {
                label: '🔤 سایر حروف',
                chars: [],
                color: 'gray'
            }
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
                        groupedData[status] = statusRequests.sort((a, b) => new Date(b.created_at) - new Date(a
                            .created_at));
                    }
                });
            } else if (groupType === 'grade') {
                // گروه‌بندی بر اساس پایه
                Object.keys(gradeGroups).forEach(groupKey => {
                    const group = gradeGroups[groupKey];
                    const groupRequests = requests.filter(req => group.grades.includes(req.grade));

                    if (groupRequests.length > 0) {
                        // مرتب‌سازی بر اساس پایه
                        const gradeOrder = sortOrder === 'asc' ? {
                            'اول': 1,
                            'دوم': 2,
                            'سوم': 3,
                            'چهارم': 4,
                            'پنجم': 5,
                            'ششم': 6,
                            'هفتم': 7,
                            'هشتم': 8,
                            'نهم': 9,
                            'دهم': 10,
                            'یازدهم': 11,
                            'دوازدهم': 12
                        } : {
                            'دوازدهم': 1,
                            'یازدهم': 2,
                            'دهم': 3,
                            'نهم': 4,
                            'هشتم': 5,
                            'هفتم': 6,
                            'ششم': 7,
                            'پنجم': 8,
                            'چهارم': 9,
                            'سوم': 10,
                            'دوم': 11,
                            'اول': 12
                        };

                        groupedData[groupKey] = groupRequests.sort((a, b) => (gradeOrder[a.grade] || 999) - (
                            gradeOrder[b.grade] || 999));
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
                            groupedData[groupKey] = sortOrder === 'asc' ?
                                groupRequests.sort((a, b) => a.name.localeCompare(b.name, 'fa')) :
                                groupRequests.sort((a, b) => b.name.localeCompare(a.name, 'fa'));
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
                            groupedData[groupKey] = sortOrder === 'asc' ?
                                groupRequests.sort((a, b) => a.name.localeCompare(b.name, 'fa')) :
                                groupRequests.sort((a, b) => b.name.localeCompare(a.name, 'fa'));
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

            // دکمه مشاهده همه فقط اگر بیش از 3 کارت داشته باشیم
            const viewAllBtn = requests.length > 3 ? `
                <button type="button"
                        onclick="toggleViewAll('${groupKey}')"
                        class="view-all-btn flex items-center gap-2 px-4 py-2 bg-${groupInfo.color}-50 hover:bg-${groupInfo.color}-100 text-${groupInfo.color}-700 rounded-lg transition-all duration-200 text-sm font-medium">
                    <span class="view-all-text">مشاهده همه</span>
                    <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            ` : '';

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
                        ${viewAllBtn}
                    </div>
                    <div class="relative">
                        <div class="scroll-wrapper">
                            <div id="scroll-${groupKey}" class="horizontal-scroll-container show-scrollbar" data-expanded="false">
                                ${cardsHTML}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // تابع ایجاد HTML برای هر کارت
        function createCardHTML(request, groupType) {
            // نقشه رنگ‌بندی وضعیت‌ها
            const statusConfig = {
                'submit': {
                    bgClass: 'bg-blue-500',
                    text: ' ارسال شده'
                },
                'accept': {
                    bgClass: 'bg-green-500',
                    text: ' تایید شده'
                },
                'check': {
                    bgClass: 'bg-yellow-500',
                    text: ' در  حال بررسی'
                },
                'reject': {
                    bgClass: 'bg-red-500',
                    text: ' رد شده'
                },
                'epointment': {
                    bgClass: 'bg-pink-600',
                    text: '  قرارملاقات'
                }
            };

            const currentStatus = statusConfig[request.story] || {
                bgClass: 'bg-gray-500',
                text: ' نامشخص'
            };

            // محاسبه روزشمار (اگر DailyTracker وجود داشته باشد)
            let dailyTrackerHTML = '';
            if (request.daily_tracker) {
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                // اضافه کردن روزهای اضافی بر اساس id
                if (request.daily_tracker.id == 1) {
                    today.setDate(today.getDate() + 31);
                } else {
                    today.setDate(today.getDate() + 15);
                }

                const startDate = new Date(request.daily_tracker.start_date);
                startDate.setHours(0, 0, 0, 0);

                const diffTime = today - startDate;
                const passed = Math.floor(diffTime / (1000 * 60 * 60 * 24));
                const max = request.daily_tracker.max_days;
                const percent = Math.min((passed / max) * 100, 100);

                if (passed >= max) {
                    dailyTrackerHTML = `
                        <div class="mb-4">
                            <div class="bg-gray-200 rounded-b-[20px] overflow-hidden h-8 absolute bottom-0 w-full left-0">
                                <div class="h-full w-full bg-gradient-to-r from-green-500 to-green-600 flex items-center justify-center">
                                    <button onclick="openScholarshipModal(${request.id})"
                                        class="w-full h-full text-white font-bold text-sm hover:bg-green-700 transition cursor-pointer rounded-full">
                                        🎓 دریافت بورسیه
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    const progressText = passed == 0 ? 'شروع نشده' : `${passed} / ${max} روز`;
                    dailyTrackerHTML = `
                        <div class="mb-4">
                            <div class="bg-gray-200 rounded-b-[20px] overflow-hidden h-8 absolute bottom-0 w-full left-0">
                                <div class="h-full bg-gradient-to-r from-blue-400 to-cyan-500 flex items-center justify-center text-white font-bold text-sm transition-all duration-500"
                                     style="width: ${percent}%;">
                                    ${progressText}
                                </div>
                            </div>
                        </div>
                    `;
                }
            }

            return `
                <div class="card-hover bg-gradient-to-br from-white to-gray-50 w-72 h-96 flex flex-col items-center justify-center rounded-3xl shadow-lg border border-gray-200 p-6 relative overflow-hidden select-none">
                    <!-- تصویر پروفایل -->
                    <div class="relative mb-4">
                        <img src="/img/${request.imgpath}" alt="تصویر کاربر" class="w-24 h-24 rounded-full object-cover shadow-md border-4 border-white">
                    </div>

                    <!-- بج وضعیت -->
                    <div class="absolute bottom-[13.5rem] left-1/2 transform -translate-x-1/2">
                        <div class="status-badge px-3 py-1 text-white rounded-full text-xs font-medium ${currentStatus.bgClass}">
                            ${currentStatus.text}
                        </div>
                    </div>

                    <!-- اطلاعات کاربر -->
                    <div class="text-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">${request.name}</h3>
                        <p class="text-sm text-gray-500">پایه: ${request.grade}</p>
                    </div>

                    <!-- دکمه‌های عملکرد -->
                    <div class="flex gap-3 w-full mt-8">
                        <button type="button"
                            onclick='openRequestDetailModal(${JSON.stringify({
                                this_user_id : {{ Auth::user()->id }},
                                user_id : request.user_id,
                                id: request.id,
                                name: request.name,
                                grade: request.grade,
                                story: request.story,
                                imgpath_url: "/img/" + request.imgpath,
                                nationalcode: request.nationalcode || "",
                                birthdate: request.birthdate || "",
                                phone: request.phone || "",
                                telephone: request.telephone || "",
                                school: request.school || "",
                                principal: request.principal || "",
                                major_name: request.major_name || "",
                                last_score: request.last_score || "",
                                school_telephone: request.school_telephone || "",
                                english_proficiency: request.english_proficiency || 0,
                                gradesheetpath: request.gradesheetpath || "",
                                gradesheetpath_url: request.gradesheetpath ? "/img/" + request.gradesheetpath : "",
                                rental: request.rental || "",
                                address: request.address || "",
                                siblings_count: request.siblings_count || "",
                                siblings_rank: request.siblings_rank || "",
                                know: request.know || "",
                                counseling_method: request.counseling_method || "",
                                why_counseling_method: request.why_counseling_method || "",
                                father_name: request.father_name || "",
                                father_phone: request.father_phone || "",
                                father_job: request.father_job || "",
                                father_income: request.father_income || "",
                                father_job_address: request.father_job_address || "",
                                mother_name: request.mother_name || "",
                                mother_phone: request.mother_phone || "",
                                mother_job: request.mother_job || "",
                                mother_income: request.mother_income || "",
                                mother_job_address: request.mother_job_address || "",
                                motivation: request.motivation || "",
                                spend: request.spend || "",
                                how_am_i: request.how_am_i || "",
                                future: request.future || "",
                                favorite_major: request.favorite_major || "",
                                help_others: request.help_others || "",
                                suggestion: request.suggestion || ""
                            })}, null, true)'
                            class="action-btn flex-1 w-1/2 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm font-medium text-center shadow-md hover:shadow-lg flex items-center py-3 justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            مشاهده
                        </button>
                    </div>

                    <!-- روزشمار -->
                    ${dailyTrackerHTML}

                    <!-- افکت دکوراتیو -->
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
                    btn.className = btn.className.replace(/bg-white.*?border-gray-200/,
                        'bg-blue-500 text-white shadow-md');
                } else {
                    btn.className = btn.className.replace(/bg-blue-500.*?shadow-md/,
                        'bg-white text-gray-600 hover:bg-blue-50 border border-gray-200');
                }
            });

            // به‌روزرسانی دکمه‌های مرتب‌سازی
            document.querySelectorAll('[onclick^="changeSortOrder"]').forEach(btn => {
                const order = btn.getAttribute('onclick').match(/'([^']+)'/)[1];
                if (order === currentSortOrder) {
                    if (order === 'asc') {
                        btn.className = btn.className.replace(/bg-white.*?border-gray-200/,
                            'bg-green-500 text-white shadow-md');
                    } else {
                        btn.className = btn.className.replace(/bg-white.*?border-gray-200/,
                            'bg-red-500 text-white shadow-md');
                    }
                } else {
                    if (order === 'asc') {
                        btn.className = btn.className.replace(/bg-green-500.*?shadow-md/,
                            'bg-white text-gray-600 hover:bg-green-50 border border-gray-200');
                    } else {
                        btn.className = btn.className.replace(/bg-red-500.*?shadow-md/,
                            'bg-white text-gray-600 hover:bg-red-50 border border-gray-200');
                    }
                }
            });
        }

        // تابع تغییر نوع دسته‌بندی
        window.changeGroupType = function(newGroupType) {
            if (currentGroupType === newGroupType) return;

            currentGroupType = newGroupType;
            const groupedData = groupRequests(allRequests, currentGroupType, currentSortOrder);
            renderGroups(groupedData, currentGroupType);
            updateButtons();
        };

        // تابع تغییر ترتیب مرتب‌سازی
        window.changeSortOrder = function(newSortOrder) {
            if (currentSortOrder === newSortOrder) return;

            currentSortOrder = newSortOrder;
            const groupedData = groupRequests(allRequests, currentGroupType, currentSortOrder);
            renderGroups(groupedData, currentGroupType);
            updateButtons();
        };

        // تابع مشاهده همه / بستن
        window.toggleViewAll = function(groupKey) {
            const container = document.getElementById('scroll-' + groupKey);
            const button = event.currentTarget;
            const textSpan = button.querySelector('.view-all-text');
            const icon = button.querySelector('svg');

            if (!container) return;

            const isExpanded = container.getAttribute('data-expanded') === 'true';

            if (isExpanded) {
                // بستن - برگشت به حالت اسکرول افقی
                container.setAttribute('data-expanded', 'false');
                textSpan.textContent = 'مشاهده همه';
                icon.style.transform = 'rotate(0deg)';

                // اسکرول به بالای دسته
                container.closest('.category-section').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            } else {
                // باز کردن - نمایش grid
                container.setAttribute('data-expanded', 'true');
                textSpan.textContent = 'بستن';
                icon.style.transform = 'rotate(180deg)';
            }
        };

        // Price inputs with comma formatting
        document.querySelectorAll('.price-input').forEach(input => {
            input.addEventListener('input', function(e) {
                let value = this.value.replace(/[^0-9]/g, '');
                if (value.length > 3) {
                    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                }
                this.value = value;
            });

            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text');
                let value = paste.replace(/[^0-9]/g, '');
                if (value.length > 3) {
                    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                }
                this.value = value;
            });
        });
    </script>
@endsection
