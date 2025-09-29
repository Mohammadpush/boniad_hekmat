@extends('layouts.unified')

@section('page-title', 'پیام‌ها')

@section('head')
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
        integrity="sha512-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- استایل‌های عمومی -->
    <link rel="stylesheet" href="{{ asset('assets/css/common/ui-elements.css') }}">

    <!-- استایل‌های مخصوص این صفحه -->
    <link rel="stylesheet" href="{{ asset('assets/css/pages/message/styles.css') }}">
@endsection

@section('content')
    @php
        use Morilog\Jalali\Jalalian;
    @endphp

    <main class="flex-1 p-8">
        <header class="bg-white shadow rounded-lg p-6 mb-8 flex items-center justify-between max-[600px]:flex-col-reverse gap-4">
            <div class="mb-4 max-[600px]:w-full">
                <div class="search-wrapper w-[250px] max-[728px]:w-[200px] max-[600px]:w-full">
                    <div class="search-container">
                        <input class="search-input" type="text" id="searchInput" placeholder="جستجو...">
                        <div class="search-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.35-4.35"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center w-full max-[600px]:flex-col max-[600px]:gap-3">
                <h2 class="text-2xl font-bold text-gray-800 max-[460px]:text-sm">لیست تمامی پیام‌ها</h2>
                <div class="flex gap-2 max-[600px]:w-full max-[600px]:flex-col">
                    <a href="{{ route('unified.addmessage', ['id' => $id]) }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors max-[600px]:text-center flex items-center max-[520px]:w-full">
                        <span class="max-[520px]:hidden">ارسال پیام</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 max-[520px]:ml-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </a>

                    <a href="{{ route('unified.allrequests') }}"
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors max-[600px]:text-center m-auto">
                        <button class="singbutton w-[125px] h-10 rounded max-[520px]:w-fit">
                            <span class="max-[520px]:hidden">بازگشت</span>
                            <div class="arrow-wrapper">
                                <div class="arrow"></div>
                            </div>
                        </button>
                    </a>
                </div>
            </div>
        </header>

        <div class="bg-white rounded-lg shadow p-6">
            @if ($scholarships->isEmpty())
                <div class="text-center py-8">
                    <p class="text-gray-600">هیچ پیامی یافت نشد.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($scholarships as $scholarship)
                        <div class="flex flex-col w-fit {{ empty($scholarship->profile_id) ? 'mr-auto' : 'ml-auto' }}">
                            <div class="{{ empty($scholarship->profile_id) ? 'bg-[#9faeff]' : 'bg-[#c5bebe]' }} flex flex-col w-full h-fit p-3 rounded-t-xl {{ empty($scholarship->profile_id) ? 'rounded-br-xl' : 'rounded-bl-xl' }}">
                                @if(!empty($scholarship->title))
                                    <div class="font-bold text-sm mb-2">{{ $scholarship->title }}</div>
                                @endif

                                <div class="text-sm">{{ $scholarship->description }}</div>

                                @if(!empty($scholarship->price))
                                    <div class="text-xs mt-2 opacity-75">مبلغ: {{ number_format($scholarship->price) }} تومان</div>
                                @endif
                            </div>

                            <div class="text-xs text-gray-500 mt-1 {{ empty($scholarship->profile_id) ? 'text-right' : 'text-left' }}">
                                @if (!empty($scholarship->profile_id))
                                    <span>{{ Jalalian::fromDateTime($scholarship->created_at)->format('H:i Y/m/d l') }}</span>
                                @else
                                    <span>{{ Jalalian::fromDateTime($scholarship->created_at)->format('Y/m/d H:i l') }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </main>

    <!-- Modal for showing full message -->
    <div id="messageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-lg w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">متن کامل پیام</h3>
                <button onclick="closeMessageModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="messageContent" class="text-gray-700">
                <!-- Message content will be inserted here -->
            </div>
        </div>
    </div>

@section('scripts')
    <!-- اسکریپت مخصوص این صفحه -->
    <script src="{{ asset('assets/js/pages/message/message-manager.js') }}"></script>
@endsection
