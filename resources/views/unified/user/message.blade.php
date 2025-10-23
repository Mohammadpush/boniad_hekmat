@extends('layouts.message-only')

@section('page-title', 'پیام‌ها')

@section('head')
    <!-- استایل‌های عمومی -->
    <link rel="stylesheet" href="{{ asset('assets/css/common/ui-elements.css') }}">

    <!-- استایل‌های مخصوص این صفحه -->
    <link rel="stylesheet" href="{{ asset('assets/css/pages/message/styles.css') }}">
@endsection

@section('content')
    @php
        use Morilog\Jalali\Jalalian;
    @endphp

    <div class="message-container">
        <!-- Sidebar - لیست درخواست‌ها -->
        <div class="sidebar">
            <!-- Header -->
            <div class="sidebar-header">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-xl font-semibold text-gray-800">پیام‌ها</h1>
                    <button class="p-2 rounded-full hover:bg-gray-100">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                            </path>
                        </svg>
                    </button>
                </div>

                <div class="search-container-wrapper">
                    <input type="text" id="searchRequests" placeholder="جستجو..." class="search-input">
                    <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- لیست درخواست‌ها -->
            <div class="requests-list">
                @if ($requests->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-gray-500">درخواستی یافت نشد.</p>
                    </div>
                @else
                    @foreach ($requests as $request)
                        @php
                            $lastMessage = App\Models\Scholarship::where('request_id', $request->id)
                                ->orderBy('created_at', 'desc')
                                ->first();

                            // محاسبه پیام‌های ناخوانده: پیام‌هایی که من فرستنده آن نیستم
                            $unreadCount = App\Models\Scholarship::where('request_id', $request->id)
                                ->where('sender_user_id', '!=', Auth::id())
                                ->where('tick', false)
                                ->count();

                            // تعیین رنگ بر اساس story
                            $storyColor = match ($request->story) {
                                'submit' => 'bg-blue-500',
                                'check' => 'bg-yellow-500',
                                'accept' => 'bg-green-500',
                                'reject' => 'bg-red-500',
                                'appointment' => 'bg-purple-500',
                                'cancel' => 'bg-gray-500',
                                default => 'bg-gray-500',
                            };

                            $storyLabel = match ($request->story) {
                                'submit' => 'ثبت شده',
                                'check' => 'در حال بررسی',
                                'accept' => 'تایید شده',
                                'reject' => 'رد شده',
                                'appointment' => 'وقت ملاقات',
                                'cancel' => 'لغو شده',
                                default => 'نامشخص',
                            };
                        @endphp

                        <div class="request-item {{ $selectedRequest && $selectedRequest->id == $request->id ? 'active' : '' }}"
                            data-request-id="{{ $request->id }}" data-request-name="{{ $request->name }}">
                            <div class="flex items-center">
                                <div class="avatar {{ $storyColor }}">
                                    <img src="{{ route('img', ['filename' => $request->imgpath]) }}" class="size-full rounded-full" style="" alt="">
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-center mb-1">
                                        <h3 class="request-name">{{ $request->name }}</h3>
                                        @if ($lastMessage)
                                            <span
                                                class="message-time">{{ Jalalian::fromDateTime($lastMessage->created_at)->format('H:i') }}</span>
                                        @endif
                                    </div>
                                    <div class="flex justify-between items-center">
                                        @if ($lastMessage)
                                            <p class="last-message">{{ Str::limit($lastMessage->description, 30) }}</p>
                                        @else
                                            <p class="last-message text-gray-400">بدون پیام</p>
                                        @endif

                                        @if ($unreadCount > 0)
                                            <div class="unread-badge">{{ $unreadCount }}</div>
                                        @endif
                                    </div>
                                    <span class="request-status {{ $storyColor }}">{{ $storyLabel }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Chat Area -->
        <div class="chat-area">
            @if ($selectedRequest)
                <!-- Chat Header -->
                <div class="chat-header">
                    <div class="flex items-center">
                        @php
                            $storyColor = match ($selectedRequest->story) {
                                'submit' => 'bg-blue-500',
                                'check' => 'bg-yellow-500',
                                'accept' => 'bg-green-500',
                                'reject' => 'bg-red-500',
                                'appointment' => 'bg-purple-500',
                                'cancel' => 'bg-gray-500',
                                default => 'bg-gray-500',
                            };
                        @endphp

                        <button
                            onclick="                                    openRequestDetailModal({
                                                                id: {{ $selectedRequest->id }},
                                                                name: '{{ addslashes($selectedRequest->name) }}',
                                                                grade: '{{ addslashes($selectedRequest->grade) }}',
                                                                story: '{{ $selectedRequest->story }}',
                                                                imgpath_url: '{{ route('img', ['filename' => $selectedRequest->imgpath]) }}',
                                                                nationalcode: '{{ addslashes($selectedRequest->nationalcode) }}',
                                                                birthdate: '{{ addslashes(Jalalian::fromDateTime($selectedRequest->birthdate)->format(' Y/m/d ')) }}',
                                                                phone: '{{ addslashes($selectedRequest->phone) }}',
                                                                telephone: '{{ addslashes($selectedRequest->telephone) }}',
                                                                school: '{{ addslashes($selectedRequest->school) }}',
                                                                principal: '{{ addslashes($selectedRequest->principal) }}',
                                                                major_name: '{{ $selectedRequest->major ? addslashes($selectedRequest->major->name) : '' }}',
                                                                last_score: '{{ addslashes($selectedRequest->last_score) }}',
                                                                school_telephone: '{{ addslashes($selectedRequest->school_telephone) }}',
                                                                english_proficiency: {{ $selectedRequest->english_proficiency ?? 0 }},
                                                                gradesheetpath: '{{ $selectedRequest->gradesheetpath }}',
                                                                gradesheetpath_url: '{{ $selectedRequest->gradesheetpath ? route('img', ['filename' => $selectedRequest->gradesheetpath]) : '' }}',
                                                                rental: '{{ $selectedRequest->rental }}',
                                                                address: '{{ addslashes($selectedRequest->address) }}',
                                                                siblings_count: '{{ $selectedRequest->siblings_count }}',
                                                                siblings_rank: '{{ $selectedRequest->siblings_rank }}',
                                                                know: '{{ addslashes($selectedRequest->know) }}',
                                                                counseling_method: '{{ addslashes($selectedRequest->counseling_method) }}',
                                                                why_counseling_method: '{{ addslashes($selectedRequest->why_counseling_method) }}',
                                                                father_name: '{{ addslashes($selectedRequest->father_name) }}',
                                                                father_phone: '{{ addslashes($selectedRequest->father_phone) }}',
                                                                father_job: '{{ addslashes($selectedRequest->father_job) }}',
                                                                father_income: '{{ $selectedRequest->father_income }}',
                                                                father_job_address: '{{ addslashes($selectedRequest->father_job_address) }}',
                                                                mother_name: '{{ addslashes($selectedRequest->mother_name) }}',
                                                                mother_phone: '{{ addslashes($selectedRequest->mother_phone) }}',
                                                                mother_job: '{{ addslashes($selectedRequest->mother_job) }}',
                                                                mother_income: '{{ $selectedRequest->mother_income }}',
                                                                mother_job_address: '{{ addslashes($selectedRequest->mother_job_address) }}',
                                                                motivation: '{{ addslashes($selectedRequest->motivation) }}',
                                                                spend: '{{ addslashes($selectedRequest->spend) }}',
                                                                how_am_i: '{{ addslashes($selectedRequest->how_am_i) }}',
                                                                future: '{{ addslashes($selectedRequest->future) }}',
                                                                favorite_major: '{{ addslashes($selectedRequest->favorite_major) }}',
                                                                help_others: '{{ addslashes($selectedRequest->help_others) }}',
                                                                suggestion: '{{ addslashes($selectedRequest->suggestion) }}'
                                                            },null,{{ Auth::user()->role != 'user' && Auth::user()->id != $selectedRequest->user_id ? 'true' : 'false' }})"
                            class=" avatar {{ $storyColor }}" title="مشاهده جزئیات درخواست">
                           <img src="{{ route('img', ['filename' => $selectedRequest->imgpath]) }}" class="size-full rounded-full" style="" alt="">
                        </button>
                        <div>
                            <h2 class="chat-title">{{ $selectedRequest->name }}</h2>
                            <p class="chat-subtitle">
                                @if (Auth::user()->role !== 'user')
                                    کاربر: {{ $selectedRequest->user->name }}
                                @else
                                    کد ملی: {{ $selectedRequest->nationalcode }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button
                            onclick="                                    openRequestDetailModal({
                                                                id: {{ $selectedRequest->id }},
                                                                name: '{{ addslashes($selectedRequest->name) }}',
                                                                grade: '{{ addslashes($selectedRequest->grade) }}',
                                                                story: '{{ $selectedRequest->story }}',
                                                                imgpath_url: '{{ route('img', ['filename' => $selectedRequest->imgpath]) }}',
                                                                nationalcode: '{{ addslashes($selectedRequest->nationalcode) }}',
                                                                birthdate: '{{ addslashes(Jalalian::fromDateTime($selectedRequest->birthdate)->format(' Y/m/d ')) }}',
                                                                phone: '{{ addslashes($selectedRequest->phone) }}',
                                                                telephone: '{{ addslashes($selectedRequest->telephone) }}',
                                                                school: '{{ addslashes($selectedRequest->school) }}',
                                                                principal: '{{ addslashes($selectedRequest->principal) }}',
                                                                major_name: '{{ $selectedRequest->major ? addslashes($selectedRequest->major->name) : '' }}',
                                                                last_score: '{{ addslashes($selectedRequest->last_score) }}',
                                                                school_telephone: '{{ addslashes($selectedRequest->school_telephone) }}',
                                                                english_proficiency: {{ $selectedRequest->english_proficiency ?? 0 }},
                                                                gradesheetpath: '{{ $selectedRequest->gradesheetpath }}',
                                                                gradesheetpath_url: '{{ $selectedRequest->gradesheetpath ? route('img', ['filename' => $selectedRequest->gradesheetpath]) : '' }}',
                                                                rental: '{{ $selectedRequest->rental }}',
                                                                address: '{{ addslashes($selectedRequest->address) }}',
                                                                siblings_count: '{{ $selectedRequest->siblings_count }}',
                                                                siblings_rank: '{{ $selectedRequest->siblings_rank }}',
                                                                know: '{{ addslashes($selectedRequest->know) }}',
                                                                counseling_method: '{{ addslashes($selectedRequest->counseling_method) }}',
                                                                why_counseling_method: '{{ addslashes($selectedRequest->why_counseling_method) }}',
                                                                father_name: '{{ addslashes($selectedRequest->father_name) }}',
                                                                father_phone: '{{ addslashes($selectedRequest->father_phone) }}',
                                                                father_job: '{{ addslashes($selectedRequest->father_job) }}',
                                                                father_income: '{{ $selectedRequest->father_income }}',
                                                                father_job_address: '{{ addslashes($selectedRequest->father_job_address) }}',
                                                                mother_name: '{{ addslashes($selectedRequest->mother_name) }}',
                                                                mother_phone: '{{ addslashes($selectedRequest->mother_phone) }}',
                                                                mother_job: '{{ addslashes($selectedRequest->mother_job) }}',
                                                                mother_income: '{{ $selectedRequest->mother_income }}',
                                                                mother_job_address: '{{ addslashes($selectedRequest->mother_job_address) }}',
                                                                motivation: '{{ addslashes($selectedRequest->motivation) }}',
                                                                spend: '{{ addslashes($selectedRequest->spend) }}',
                                                                how_am_i: '{{ addslashes($selectedRequest->how_am_i) }}',
                                                                future: '{{ addslashes($selectedRequest->future) }}',
                                                                favorite_major: '{{ addslashes($selectedRequest->favorite_major) }}',
                                                                help_others: '{{ addslashes($selectedRequest->help_others) }}',
                                                                suggestion: '{{ addslashes($selectedRequest->suggestion) }}'
                                                            },null,{{ Auth::user()->role != 'user' && Auth::user()->id != $selectedRequest->user_id ? 'true' : 'false' }})"
                            class="p-2 rounded-full hover:bg-gray-100" title="مشاهده جزئیات درخواست">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Messages -->
                <div id="messagesContainer" class="messages-container">
                    @if ($scholarships->isEmpty())
                        <div class="empty-messages">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                </path>
                            </svg>
                            <p class="text-gray-500">هنوز پیامی ارسال نشده است</p>
                            <p class="text-sm text-gray-400 mt-1">پیام خود را از پایین صفحه ارسال کنید</p>
                        </div>
                    @else
                        @php
                            $previousDate = null;
                        @endphp
                        @foreach ($scholarships as $index => $scholarship)
                            @php
                                $isFromAdmin = $scholarship->sender_user_id === Auth::id() ? false : true;

                                // تاریخ فارسی پیام جاری
                                $currentJalali = Jalalian::fromDateTime($scholarship->created_at);
                                $currentDate = $currentJalali->format('Y/m/d'); // تاریخ به صورت سال/ماه/روز
                                $today = Jalalian::now();
                                $todayDate = $today->format('Y/m/d');
                                $currentYear = $currentJalali->getYear();
                                $todayYear = $today->getYear();

                                // تعیین رنگ بر اساس story
                                $messageBgColor = match ($scholarship->story) {
                                    'thanks' => $isFromAdmin ? 'bg-blue-100' : 'bg-blue-500',
                                    'warning' => $isFromAdmin ? 'bg-yellow-100' : 'bg-yellow-500',
                                    'scholarship' => $isFromAdmin ? 'bg-green-100' : 'bg-green-500',
                                    'message' => $isFromAdmin ? 'bg-gray-100' : 'bg-blue-500',
                                    default => $isFromAdmin ? 'bg-gray-100' : 'bg-blue-500',
                                };

                                $messageTextColor = $isFromAdmin ? 'text-gray-800' : 'text-white';
                                $timeTextColor = $isFromAdmin ? 'text-gray-500' : 'text-gray-200';
                            @endphp

                            {{-- نمایش date separator اگر تاریخ تغییر کرده باشد --}}
                            @if ($previousDate !== $currentDate)
                                <div class="date-separator">
                                    <span class="date-separator-text">
                                        @if ($currentDate === $todayDate)
                                            امروز
                                        @else
                                            @php
                                                $day = $currentJalali->getDay();
                                                $month = $currentJalali->getMonth();
                                                $monthNames = [
                                                    'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور',
                                                    'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'
                                                ];
                                                $monthName = $monthNames[$month - 1] ?? '';
                                                $dateStr = "$day $monthName";
                                                if ($currentYear !== $todayYear) {
                                                    $dateStr .= " $currentYear";
                                                }
                                                echo $dateStr;
                                            @endphp
                                        @endif
                                    </span>
                                </div>
                                @php
                                    $previousDate = $currentDate;
                                @endphp
                            @endif

                            <div class="message-wrapper {{ $isFromAdmin ? 'message-left' : 'message-right' }}" data-message-id="{{ $scholarship->id }}">
                                <div class="message-bubble {{ $messageBgColor }} {{ $messageTextColor }}">
                                    <div class="message-content">{{ $scholarship->description }}</div>

                                    @if (!empty($scholarship->price))
                                        <div class="message-price {{ $timeTextColor }}">
                                            💰 مبلغ: {{ number_format($scholarship->price) }} تومان
                                        </div>
                                    @endif

                                    <div class="message-footer">
                                        <span class="message-time {{ $timeTextColor }}">
                                            {{ Jalalian::fromDateTime($scholarship->created_at)->format('H:i') }}
                                        </span>

                                        @if (!$isFromAdmin)
                                            <span class="message-ticks {{ $timeTextColor }}">
                                                @if ($scholarship->tick)
                                                    <svg class="w-4 h-4 inline-block" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M4 12L9 17L20 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M9 12L14 17L24 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transform: translateX(-8px); transform-origin: right;"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 inline-block" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M4 12L9 17L20 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                @endif
                                            </span>
                                        @endif

                                        @if ($scholarship->story !== 'message')
                                            <span class="message-badge {{ $messageBgColor }}">
                                                {{ match ($scholarship->story) {
                                                    'thanks' => '🙏 تشکر',
                                                    'warning' => '⚠️ هشدار',
                                                    'scholarship' => '🎓 بورسیه',
                                                    default => '',
                                                } }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Message Input -->
                <div class="message-input-container">
                    <form id="messageForm" action="{{ route('unified.storemessage', $selectedRequest->id) }}"
                        method="POST">
                        @csrf
                        <div class="flex items-end gap-2">
                            <button type="button" id="storyTypeButton" class="story-type-btn" title="نوع پیام">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                                    </path>
                                </svg>
                            </button>

                            <div class="flex-1 relative">
                                <textarea name="description" id="messageInput" placeholder="پیام بنویسید..." class="message-textarea"
                                    rows="1" required></textarea>

                                <input type="hidden" name="story" id="storyInput" value="message">
                                <input type="hidden" name="price" id="priceInput">
                            </div>

                            <button type="submit" id="sendButton" class="send-button">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Story Type Selector Modal -->
                <div id="storyTypeModal" class="story-modal hidden">
                    <div class="story-modal-content">
                        <h3 class="text-lg font-bold mb-4">انتخاب نوع پیام</h3>

                        <div class="story-options">
                            <button type="button" class="story-option" data-story="message">
                                <div class="story-icon bg-blue-500">💬</div>
                                <div class="story-label">پیام عادی</div>
                            </button>

                            <button type="button" class="story-option" data-story="thanks">
                                <div class="story-icon bg-blue-400">🙏</div>
                                <div class="story-label">تشکر</div>
                            </button>

                            <button type="button" class="story-option" data-story="warning">
                                <div class="story-icon bg-yellow-500">⚠️</div>
                                <div class="story-label">هشدار</div>
                            </button>

                            @if (Auth::user()->role !== 'user')
                                <button type="button" class="story-option" data-story="scholarship">
                                    <div class="story-icon bg-green-500">🎓</div>
                                    <div class="story-label">بورسیه</div>
                                </button>
                            @endif
                        </div>

                        <div id="priceSection" class="mt-4 hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">مبلغ (تومان)</label>
                            <input type="number" id="priceInputModal"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="50000">
                        </div>

                        <div class="flex gap-2 mt-4">
                            <button type="button" id="confirmStoryType" class="btn-primary flex-1">تایید</button>
                            <button type="button" id="cancelStoryType" class="btn-secondary">انصراف</button>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                        </path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">یک درخواست را انتخاب کنید</h3>
                    <p class="text-gray-500">برای مشاهده و ارسال پیام، یکی از درخواست‌ها را از لیست کنار انتخاب کنید</p>
                </div>
            @endif
        </div>
    </div>
    {{-- اضافه کردن مودال جزئیات درخواست --}}
    @include('unified.user.request-popup')
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/pages/message/message-manager.js') }}"></script>
    <script src="{{ asset('assets/js/pages/message/real-time-updates.js') }}"></script>
    <script src="{{ asset('assets/js/pages/myrequests/live-update.js') }}"></script>
@endsection
