{{-- CSS فایل‌ها --}}
<link rel="stylesheet" href="{{ asset('assets/css/request-detail.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/request-detail-popup/slider-styles.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/request-detail-popup/progress-bar-styles.css') }}">{{-- مودال جزئیات درخواست برای کاربران --}}
<div id="requestDetailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 ">
    <div class="flex items-center justify-center min-h-screen p-2">
        {{-- دکمه بستن کنار مودال (سمت راست بالا) --}}
        <div class="relative w-full max-w-7xl">
            <a type="button" href="{{ route('unified.myrequests') }}"
                class="absolute -top-4 -right-4 bg-red-400 hover:bg-red-700 text-white rounded-full size-12 flex items-center justify-center shadow-lg transition-colors z-10">
                <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </a>

            <div class="bg-white rounded-2xl w-full max-h-[95vh] overflow-y-auto shadow-2xl modal-content p-[24px]">
                {{-- محتوای مودال --}}
                <div class="p-8 bg-gray-50">
                    {{-- محتوای اصلی بدون grid اضافی --}}
                    @include('unified.user.request-popup.top-side')

                    {{-- اطلاعات مسکن و خانواده --}}
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mt-6">
                        {{-- اطلاعات مسکن --}}
                        <div
                            class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:shadow-2xl">
                            <div class="flex items-center mb-6">
                                <div class="icon-wrapper w-12 h-12 rounded-xl flex items-center justify-center mr-4"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800">اطلاعات مسکن</h3>
                            </div>

                            <div class="space-y-4">
                                <!-- وضعیت مسکن -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">وضعیت مسکن</label>
                                    <div class="flex items-center justify-between">
                                        <span id="modalRentalDisplay"
                                            class="text-lg font-semibold text-gray-800"></span>
                                        <form id="modalRentalForm" class="hidden items-center space-x-2 space-x-reverse"
                                            style="margin:0;">
                                            <select id="modalRentalInput"
                                                class="border border-gray-300 text-black rounded px-2 py-1 text-sm">
                                                <option value="">انتخاب کنید</option>
                                                <option value="0">ملکی</option>
                                                <option value="1">استیجاری</option>
                                            </select>
                                            <button type="submit"><svg xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                    stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-green-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <button type="button" id="cancelRentalEdit"><svg
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <span id="modalRentalError" class="text-red-500 text-xs ml-2 hidden"></span>
                                        </form>
                                        <button type="button" id="editRentalBtn"
                                            class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                            title="ویرایش وضعیت مسکن">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- آدرس کامل -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">آدرس کامل</label>
                                    <div class="flex items-start justify-between">
                                        <p id="modalAddressDisplay"
                                            class="text-lg font-semibold text-gray-800 leading-relaxed flex-1"></p>
                                        <form id="modalAddressForm" class="hidden items-start space-x-2 space-x-reverse"
                                            style="margin:0;">
                                            <textarea id="modalAddressInput" class="border border-gray-300 text-black rounded px-2 py-1 w-64 text-sm" rows="3"
                                                autocomplete="off"></textarea>
                                            <div class="flex flex-col space-y-1">
                                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                        stroke="currentColor"
                                                        class="size-5 text-gray-400 hover:text-green-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg></button>
                                                <button type="button" id="cancelAddressEdit"><svg
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                        class="size-5 text-gray-400 hover:text-red-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg></button>
                                            </div>
                                            <span id="modalAddressError"
                                                class="text-red-500 text-xs ml-2 hidden"></span>
                                        </form>
                                        <button type="button" id="editAddressBtn"
                                            class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                            title="ویرایش آدرس">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- اطلاعات خانوادگی --}}
                        <div
                            class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:shadow-2xl">
                            <div class="flex items-center mb-6">
                                <div class="icon-wrapper w-12 h-12 rounded-xl flex items-center justify-center mr-4"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800">اطلاعات خانوادگی</h3>
                            </div>

                            <div class="space-y-4">
                                <!-- تعداد فرزندان و رتبه -->
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- تعداد فرزندان -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-1">تعداد
                                            فرزندان</label>
                                        <div class="flex items-center justify-between">
                                            <span id="modalSiblingsCountDisplay"
                                                class="text-lg font-semibold text-gray-800"></span>
                                            <form id="modalSiblingsCountForm"
                                                class="hidden items-center space-x-2 space-x-reverse"
                                                style="margin:0;">
                                                <input type="number" id="modalSiblingsCountInput"
                                                    class="border border-gray-300 text-black rounded px-2 py-1 w-16 text-sm"
                                                    min="1" max="20" autocomplete="off">
                                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                        stroke="currentColor"
                                                        class="size-5 text-gray-400 hover:text-green-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg></button>
                                                <button type="button" id="cancelSiblingsCountEdit"><svg
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                        class="size-5 text-gray-400 hover:text-red-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg></button>
                                                <span id="modalSiblingsCountError"
                                                    class="text-red-500 text-xs ml-2 hidden"></span>
                                            </form>
                                            <button type="button" id="editSiblingsCountBtn"
                                                class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                                title="ویرایش تعداد فرزندان">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- رتبه متقاضی -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-1">رتبه متقاضی</label>
                                        <div class="flex items-center justify-between">
                                            <span id="modalSiblingsRankDisplay"
                                                class="text-lg font-semibold text-gray-800"></span>
                                            <form id="modalSiblingsRankForm"
                                                class="hidden items-center space-x-2 space-x-reverse"
                                                style="margin:0;">
                                                <div id="modalSiblingsIconsContainer"
                                                    class="flex gap-2 p-2 border border-gray-300 rounded min-w-[120px]">
                                                    <!-- آیکون‌های interactive در اینجا ایجاد خواهند شد -->
                                                </div>
                                                <input type="hidden" id="modalSiblingsRankInput">
                                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                        stroke="currentColor"
                                                        class="size-5 text-gray-400 hover:text-green-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg></button>
                                                <button type="button" id="cancelSiblingsRankEdit"><svg
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                        class="size-5 text-gray-400 hover:text-red-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg></button>
                                                <span id="modalSiblingsRankError"
                                                    class="text-red-500 text-xs ml-2 hidden"></span>
                                            </form>
                                            <button type="button" id="editSiblingsRankBtn"
                                                class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                                title="ویرایش رتبه">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- سایر اطلاعات خانوادگی -->
                                <div class="grid grid-cols-1 gap-4">
                                    <!-- نحوه آشنایی با بنیاد -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-1">نحوه آشنایی با
                                            بنیاد</label>
                                        <div class="flex items-center justify-between">
                                            <span id="modalKnowDisplay"
                                                class="text-lg font-semibold text-gray-800"></span>
                                            <form id="modalKnowForm"
                                                class="hidden items-center space-x-2 space-x-reverse"
                                                style="margin:0;">
                                                <select id="modalKnowInput"
                                                    class="border border-gray-300 text-black rounded px-2 py-1 text-sm">
                                                    <option value="">انتخاب کنید</option>
                                                    <option value="تبلیغات">تبلیغات</option>
                                                    <option value="دوستان">دوستان</option>
                                                    <option value="اینترنت">اینترنت</option>
                                                    <option value="رسانه‌های اجتماعی">رسانه‌های اجتماعی</option>
                                                    <option value="سایر">سایر</option>
                                                </select>
                                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                        stroke="currentColor"
                                                        class="size-5 text-gray-400 hover:text-green-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg></button>
                                                <button type="button" id="cancelKnowEdit"><svg
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                        class="size-5 text-gray-400 hover:text-red-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg></button>
                                                <span id="modalKnowError"
                                                    class="text-red-500 text-xs ml-2 hidden"></span>
                                            </form>
                                            <button type="button" id="editKnowBtn"
                                                class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                                title="ویرایش نحوه آشنایی">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- روش مشاوره -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-1">روش مشاوره مورد
                                            نظر</label>
                                        <div class="flex items-center justify-between">
                                            <span id="modalCounselingMethodDisplay"
                                                class="text-lg font-semibold text-gray-800"></span>
                                            <form id="modalCounselingMethodForm"
                                                class="hidden items-center space-x-2 space-x-reverse"
                                                style="margin:0;">
                                                <select id="modalCounselingMethodInput"
                                                    class="border border-gray-300 text-black rounded px-2 py-1 text-sm">
                                                    <option value="">انتخاب کنید</option>
                                                    <option value="مشاوره مدرسه">مشاوره مدرسه</option>
                                                    <option value="مشاوره خارجی">مشاوره خارجی</option>
                                                    <option value="روش‌های دیگر">روش‌های دیگر</option>
                                                    <option value="مشاوره نمی‌کنم">مشاوره نمی‌کنم</option>
                                                </select>
                                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                        stroke="currentColor"
                                                        class="size-5 text-gray-400 hover:text-green-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg></button>
                                                <button type="button" id="cancelCounselingMethodEdit"><svg
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                        class="size-5 text-gray-400 hover:text-red-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg></button>
                                                <span id="modalCounselingMethodError"
                                                    class="text-red-500 text-xs ml-2 hidden"></span>
                                            </form>
                                            <button type="button" id="editCounselingMethodBtn"
                                                class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                                title="ویرایش روش مشاوره">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div id="modalWhyCounselingMethodDiv" class="hidden">
                                        <label class="block text-sm font-medium text-gray-500 mb-1">دلیل انتخاب روش
                                            مشاوره</label>
                                        <p id="modalWhyCounselingMethod" class="text-lg font-semibold text-gray-800">
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- اطلاعات والدین --}}
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mt-6">
                        {{-- اطلاعات پدر --}}
                        <div
                            class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:shadow-2xl">
                            <div class="flex items-center mb-6">
                                <div class="icon-wrapper w-12 h-12 rounded-xl flex items-center justify-center mr-4"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800">اطلاعات پدر</h3>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">نام پدر</label>
                                    <p id="modalFatherName" class="text-lg font-semibold text-gray-800"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">شماره موبایل</label>
                                    <p id="modalFatherPhone" class="text-lg font-semibold text-gray-800 font-mono">
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">شغل</label>
                                    <p id="modalFatherJob" class="text-lg font-semibold text-gray-800"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">درآمد ماهانه</label>
                                    <p id="modalFatherIncome" class="text-lg font-semibold text-gray-800"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">آدرس محل کار</label>
                                    <p id="modalFatherJobAddress"
                                        class="text-lg font-semibold text-gray-800 leading-relaxed"></p>
                                </div>
                            </div>
                        </div>

                        {{-- اطلاعات مادر --}}
                        <div
                            class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:shadow-2xl">
                            <div class="flex items-center mb-6">
                                <div class="icon-wrapper w-12 h-12 rounded-xl flex items-center justify-center mr-4"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800">اطلاعات مادر</h3>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">نام مادر</label>
                                    <p id="modalMotherName" class="text-lg font-semibold text-gray-800"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">شماره موبایل</label>
                                    <p id="modalMotherPhone" class="text-lg font-semibold text-gray-800 font-mono">
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">شغل</label>
                                    <p id="modalMotherJob" class="text-lg font-semibold text-gray-800"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">درآمد ماهانه</label>
                                    <p id="modalMotherIncome" class="text-lg font-semibold text-gray-800"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">آدرس محل کار</label>
                                    <p id="modalMotherJobAddress"
                                        class="text-lg font-semibold text-gray-800 leading-relaxed"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- سوالات نهایی و انگیزه‌ها --}}
                    <div
                        class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:shadow-2xl mt-6">
                        <div class="flex items-center mb-6">
                            <div class="icon-wrapper w-12 h-12 rounded-xl flex items-center justify-center mr-4"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">سوالات نهایی و انگیزه‌ها</h3>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-2">انگیزه درخواست
                                    بورسیه</label>
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <p id="modalMotivation" class="text-gray-800 leading-relaxed"></p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-2">نحوه استفاده از کمک
                                    مالی</label>
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <p id="modalSpend" class="text-gray-800 leading-relaxed"></p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-2">معرفی خود</label>
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <p id="modalHowAmI" class="text-gray-800 leading-relaxed"></p>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-2">برنامه‌های
                                        آینده</label>
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <p id="modalFuture" class="text-gray-800 leading-relaxed"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-2">رشته مورد
                                        علاقه</label>
                                    <p id="modalFavoriteMajor" class="text-lg font-semibold text-gray-800"></p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-2">آمادگی کمک به
                                        دیگران</label>
                                    <p id="modalHelpOthers" class="text-lg font-semibold text-gray-800"></p>
                                </div>
                            </div>

                            <div id="modalSuggestionDiv" class="hidden">
                                <label class="block text-sm font-medium text-gray-500 mb-2">پیشنهادات برای بهتر شدن
                                    عملکرد بنیاد</label>
                                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                                    <p id="modalSuggestion" class="text-gray-800 leading-relaxed"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- دکمه‌های عملیات --}}
                    <div class="flex justify-center items-center space-x-4 space-x-reverse mt-8">
                        {{-- دکمه ویرایش --}}
                        <button type="button" id="editRequestBtn"
                            class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 px-8 rounded-xl hover:from-blue-700 hover:to-blue-800 transition font-medium shadow-lg flex items-center space-x-2 space-x-reverse">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span>✏️ ویرایش درخواست</span>
                        </button>

                        {{-- دکمه کنسل --}}
                        <button type="button" onclick="document.getElementById('closeRequestDetailModal').click()"
                            class="bg-gradient-to-r from-gray-600 to-gray-700 text-white py-3 px-8 rounded-xl hover:from-gray-700 hover:to-gray-800 transition font-medium shadow-lg">
                            ❌ کنسل
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript فایل‌ها --}}
    <script src="{{ asset('assets/js/request-detail-popup/close.js') }}"></script>
    <script src='{{ asset('assets/js/request-detail-popup/popup-functionality.js') }}'></script>
    <script src="{{ asset('assets/js/numinput.js') }}"></script>

    {{-- اسکریپت‌های بهینه شده --}}
    <script src="{{ asset('assets/js/request-detail-popup/data-manager.js') }}"></script>
    <script src="{{ asset('assets/js/request-detail-popup/field-editors.js') }}"></script>
    <script src="{{ asset('assets/js/request-detail-popup/field-initializers.js') }}"></script>
    <script src="{{ asset('assets/js/request-detail-popup/main-initializer.js') }}"></script>
