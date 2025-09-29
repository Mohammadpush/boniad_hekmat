<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'پنل مدیریت')</title>
    <script src="{{ mix('js/app.js') }}"></script>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Common Styles -->
    <link href="{{ asset('assets/css/common/animations.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/common/forms.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/common/progress.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/common/badges.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/common/scroll-containers.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/common/ui-elements.css') }}" rel="stylesheet">

    <!-- Layout Styles -->
    <link href="{{ asset('assets/css/layouts/unified-layout.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/search-box.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/sidebar.css') }}" rel="stylesheet">

    <!-- Page Specific Styles -->
    @yield('page-styles')

    <script src="{{ asset('assets/js/libraris/tail.js') }}"></script>

        .navbar-menu.active {
            transform: translateX(0) !important;
        }

        .lamp {
            text-decoration: none;
            font-weight: 500;
            padding: 8px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .lamp:hover {
            background-color: #f3f4f6;
        }
    </style>

    @yield('head')
</head>

<body class="bg-gray-100 m-0 p-0">
    <div class="flex min-h-screen w-full">
        <!-- Mobile Overlay -->
        <div id="mobileOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

        <!-- Sidebar -->
        <div id="sidebar"
            class="fixed h-screen flex flex-col w-16 bg-slate-50 border-l border-gray-200 shadow-lg transition-all duration-300 ease-in-out overflow-hidden z-10 transform translate-x-full
            max-[658px]:hidden max-[658px]:w-0 max-[658px]:p-0 max-[658px]:m-0 max-[658px]:z-0">
            <!-- Header with Toggle Button -->
            <div class="p-6 flex items-center justify-between">
                {{-- <h2 id="sidebarTitle" class="text-xl font-bold text-gray-800">پنل مدیریت</h2> --}}
                <button id="toggleSidebar"
                    class="hover:bg-gray-100 transition-all duration-200 ease-in-out hover:scale-105 p-2 rounded">
                    <svg id="toggleIcon" class="w-5 h-5 text-gray-600 transform rotate-180" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                </button>
            </div>

            <nav class="mt-6 flex flex-col min-h-96">
                <div class="px-6 py-2 h-10 flex items-center mt-6 transition-opacity duration-300">
                    <div id="menuLabel"
                        class="text-xs font-semibold text-gray-400 uppercase tracking-widest h-4 leading-4 block transition-opacity duration-300">
                        منوی اصلی
                    </div>
                </div>

                <!-- My Requests - All Roles -->
                <a href="{{ route('unified.myrequests') }}"
                    class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 group relative transition-all duration-200 min-h-12 hover:pr-7"
                    title="درخواست‌های من">
                    <svg class="w-5 h-5 ml-3 flex-shrink-0 transition-colors duration-200" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="menu-text transition-opacity duration-300">درخواست‌های من</span>
                </a>

                @if (Auth::user()->role !== 'user')
                    <!-- All Requests - Admin/Master Only -->
                    <a href="{{ route('unified.allrequests') }}"
                        class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 group relative transition-all duration-200 min-h-12 hover:pr-7"
                        title="تمام درخواست‌ها">
                        <svg class="w-5 h-5 ml-3 flex-shrink-0 transition-colors duration-200" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span class="menu-text transition-opacity duration-300">تمام درخواست‌ها</span>
                    </a>

                    <!-- Accepted Requests - Admin/Master Only -->
                    <a href="{{ route('unified.acceptes') }}"
                        class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 group relative transition-all duration-200 min-h-12 hover:pr-7"
                        title="درخواست‌های پذیرفته شده">
                        <svg class="w-5 h-5 ml-3 flex-shrink-0 transition-colors duration-200" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="menu-text transition-opacity duration-300">
                            پذیرفته شدگان
                        </span>
                    </a>

                    <!-- Users Management - Admin/Master Only -->
                    <a href="{{ route('unified.users') }}"
                        class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 group relative transition-all duration-200 min-h-12 hover:pr-7"
                        title="مدیریت کاربران">
                        <svg class="w-5 h-5 ml-3 flex-shrink-0 transition-colors duration-200" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                        <span class="menu-text transition-opacity duration-300">مدیریت کاربران</span>
                    </a>
                @endif

                @if (Auth::user()->role === 'admin')
                    <!-- Add Profile - Admin Only -->
                    <a href="{{ route('unified.addprofile') }}"
                        class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 group relative transition-all duration-200 min-h-12 hover:pr-7"
                        title="مدیریت پروفایل">
                        <svg class="w-5 h-5 ml-3 flex-shrink-0 transition-colors duration-200" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="menu-text transition-opacity duration-300">مدیریت پروفایل</span>
                    </a>
                @endif

                <div class="px-6 py-2 h-10 flex items-center mt-6 transition-opacity duration-300">
                    <div id="accountLabel"
                        class="text-xs font-semibold text-gray-400 uppercase tracking-widest h-4 leading-4 block transition-opacity duration-300">
                        حساب
                        کاربری</div>
                </div>

                <a href="{{ route('logout') }}"
                    class="flex items-center px-6 py-3 text-gray-700 hover:bg-gray-50 group relative transition-all duration-200 min-h-12 hover:pr-7"
                    title="خروج">
                    <svg class="w-5 h-5 ml-3 flex-shrink-0 transition-colors duration-200" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="menu-text transition-opacity duration-300">خروج</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 min-[658px]:mr-[63px]  w-full ">
            <div class="bg-white shadow hide">
                <div class="px-6 py-4 h-fit ">
                    <div class="flex justify-between items-center">
                        <!-- Hamburger Menu Button (Mobile Only) -->
                        <button class="navbar-toggle max-[658px]:block min-[659px]:hidden z-[50]">
                            <span class="bar"></span>
                            <span class="bar"></span>
                            <span class="bar"></span>
                        </button>
<div class="w-full h-full">

    <div class="mb-4 max-[600px]:w-full">

    </div>
</div>

                    </div>
                </div>

            </div>

            <!-- Mobile Navigation Menu (Hidden by default) -->
            <ul
                class="navbar-menu max-[658px]:flex min-[659px]:hidden fixed top-0 left-0 w-full h-full bg-white flex-col justify-center items-center text-center z-40 transform translate-x-full transition-transform duration-300">
                <!-- My Requests - All Roles -->
                <li><a href="{{ route('unified.myrequests') }}"
                        class="lamp block py-4 text-lg text-gray-700 hover:text-blue-600">درخواست‌های من</a></li>

                @if (Auth::user()->role !== 'user')
                    <!-- All Requests - Admin/Master Only -->
                    <li><a href="{{ route('unified.allrequests') }}"
                            class="lamp block py-4 text-lg text-gray-700 hover:text-blue-600">تمام درخواست‌ها</a></li>

                    <!-- Accepted Requests - Admin/Master Only -->
                    <li><a href="{{ route('unified.acceptes') }}"
                            class="lamp block py-4 text-lg text-gray-700 hover:text-blue-600">پذیرفته شدگان</a></li>

                    <!-- Users Management - Admin/Master Only -->
                    <li><a href="{{ route('unified.users') }}"
                            class="lamp block py-4 text-lg text-gray-700 hover:text-blue-600">مدیریت کاربران</a></li>
                @endif

                @if (Auth::user()->role === 'admin')
                    <!-- Add Profile - Admin Only -->
                    <li><a href="{{ route('unified.addprofile') }}"
                            class="lamp block py-4 text-lg text-gray-700 hover:text-blue-600">مدیریت پروفایل</a></li>
                @endif

                <!-- Logout -->
                <li><a href="{{ route('logout') }}"
                        class="lamp block py-4 text-lg text-gray-700 hover:text-red-600">خروج</a></li>
            </ul>

            @yield('content')
        </div>
    </div>

    <!-- Load Sidebar JavaScript -->
    <script src="{{ asset('assets/js/sidebar.js') }}"></script>

    <!-- Common JavaScript -->
    <script src="{{ asset('assets/js/common/form-validation.js') }}"></script>
    <script src="{{ asset('assets/js/common/ui-components.js') }}"></script>
    <script src="{{ asset('assets/js/common/scroll-utils.js') }}"></script>

    <!-- Layout JavaScript -->
    <script src="{{ asset('assets/js/layouts/unified-layout.js') }}"></script>
    <script src="{{ asset('assets/js/search-functionality.js') }}"></script>

    @yield('scripts')
</body>

</html>
