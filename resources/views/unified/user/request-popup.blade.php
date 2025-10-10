{{-- CSS فایل‌ها --}}
<link rel="stylesheet" href="{{ asset('assets/css/request-detail.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/request-detail-popup/slider-styles.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/request-detail-popup/progress-bar-styles.css') }}">{{-- مودال جزئیات درخواست برای کاربران --}}
<div id="requestDetailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 ">
    <div class="flex items-center justify-center min-h-screen p-2">
        {{-- دکمه بستن کنار مودال (سمت راست بالا) --}}
        <div class="relative w-full max-w-7xl">
            <button type="button" id="closeRequestDetailModal"
                class="absolute -top-4 -right-4 bg-red-400 hover:bg-red-700 text-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg transition-colors z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

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
                                        <div class="flex items-center justify-between">
                                            <p id="modalWhyCounselingMethod" class="text-lg font-semibold text-gray-800">
                                            </p>
                                            <form id="modalWhyCounselingMethodForm"
                                                class="hidden items-center space-x-2 space-x-reverse"
                                                style="margin:0;">
                                                <textarea id="modalWhyCounselingMethodInput"
                                                    class="border border-gray-300 text-black rounded px-2 py-1 text-sm w-64 h-20 resize-none"
                                                    placeholder="دلیل انتخاب روش مشاوره را توضیح دهید..."></textarea>
                                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                        stroke="currentColor"
                                                        class="size-5 text-gray-400 hover:text-green-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg></button>
                                                <button type="button" id="cancelWhyCounselingMethodEdit"><svg
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                        class="size-5 text-gray-400 hover:text-red-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg></button>
                                                <span id="modalWhyCounselingMethodError"
                                                    class="text-red-500 text-xs ml-2 hidden"></span>
                                            </form>
                                            <button type="button" id="editWhyCounselingMethodBtn"
                                                class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                                title="ویرایش دلیل روش مشاوره">
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
                                    <div class="flex items-center justify-between">
                                        <p id="modalFatherName" class="text-lg font-semibold text-gray-800"></p>
                                        <form id="modalFatherNameForm"
                                            class="hidden items-center space-x-2 space-x-reverse"
                                            style="margin:0;">
                                            <input type="text" id="modalFatherNameInput"
                                                class="border border-gray-300 text-black rounded px-2 py-1 text-sm w-48"
                                                placeholder="نام پدر">
                                            <button type="submit"><svg xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                    stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-green-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <button type="button" id="cancelFatherNameEdit"><svg
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <span id="modalFatherNameError"
                                                class="text-red-500 text-xs ml-2 hidden"></span>
                                        </form>
                                        <button type="button" id="editFatherNameBtn"
                                            class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                            title="ویرایش نام پدر">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">شماره موبایل</label>
                                    <div class="flex items-center justify-between">
                                        <p id="modalFatherPhone" class="text-lg font-semibold text-gray-800 font-mono">
                                        </p>
                                        <form id="modalFatherPhoneForm"
                                            class="hidden items-center space-x-2 space-x-reverse"
                                            style="margin:0;">
                                            <input type="text" id="modalFatherPhoneInput"
                                                class="border border-gray-300 text-black rounded px-2 py-1 text-sm w-48 num-input"
                                                placeholder="09123456789">
                                            <button type="submit"><svg xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                    stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-green-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <button type="button" id="cancelFatherPhoneEdit"><svg
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <span id="modalFatherPhoneError"
                                                class="text-red-500 text-xs ml-2 hidden"></span>
                                        </form>
                                        <button type="button" id="editFatherPhoneBtn"
                                            class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                            title="ویرایش شماره موبایل پدر">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">شغل</label>
                                    <div class="flex items-center justify-between">
                                        <p id="modalFatherJob" class="text-lg font-semibold text-gray-800"></p>
                                        <form id="modalFatherJobForm"
                                            class="hidden items-center space-x-2 space-x-reverse"
                                            style="margin:0;">
                                            <input type="text" id="modalFatherJobInput"
                                                class="border border-gray-300 text-black rounded px-2 py-1 text-sm w-48"
                                                placeholder="شغل پدر">
                                            <button type="submit"><svg xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                    stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-green-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <button type="button" id="cancelFatherJobEdit"><svg
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <span id="modalFatherJobError"
                                                class="text-red-500 text-xs ml-2 hidden"></span>
                                        </form>
                                        <button type="button" id="editFatherJobBtn"
                                            class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                            title="ویرایش شغل پدر">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">درآمد ماهانه</label>
                                    <div class="flex items-center justify-between">
                                        <p id="modalFatherIncome" class="text-lg font-semibold text-gray-800"></p>
                                        <form id="modalFatherIncomeForm"
                                            class="hidden items-center space-x-2 space-x-reverse"
                                            style="margin:0;">
                                            <input type="number" id="modalFatherIncomeInput"
                                                class="border border-gray-300 text-black rounded px-2 py-1 text-sm w-48"
                                                placeholder="درآمد به تومان" min="0">
                                            <button type="submit"><svg xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                    stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-green-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <button type="button" id="cancelFatherIncomeEdit"><svg
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <span id="modalFatherIncomeError"
                                                class="text-red-500 text-xs ml-2 hidden"></span>
                                        </form>
                                        <button type="button" id="editFatherIncomeBtn"
                                            class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                            title="ویرایش درآمد پدر">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">آدرس محل کار</label>
                                    <div class="flex items-center justify-between">
                                        <p id="modalFatherJobAddress"
                                            class="text-lg font-semibold text-gray-800 leading-relaxed"></p>
                                        <form id="modalFatherJobAddressForm"
                                            class="hidden items-center space-x-2 space-x-reverse"
                                            style="margin:0;">
                                            <textarea id="modalFatherJobAddressInput"
                                                class="border border-gray-300 text-black rounded px-2 py-1 text-sm w-48 h-20 resize-none"
                                                placeholder="آدرس محل کار پدر"></textarea>
                                            <button type="submit"><svg xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                    stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-green-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <button type="button" id="cancelFatherJobAddressEdit"><svg
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <span id="modalFatherJobAddressError"
                                                class="text-red-500 text-xs ml-2 hidden"></span>
                                        </form>
                                        <button type="button" id="editFatherJobAddressBtn"
                                            class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                            title="ویرایش آدرس محل کار پدر">
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
                                    <div class="flex items-center justify-between">
                                        <p id="modalMotherName" class="text-lg font-semibold text-gray-800"></p>
                                        <form id="modalMotherNameForm"
                                            class="hidden items-center space-x-2 space-x-reverse"
                                            style="margin:0;">
                                            <input type="text" id="modalMotherNameInput"
                                                class="border border-gray-300 text-black rounded px-2 py-1 text-sm w-48"
                                                placeholder="نام مادر">
                                            <button type="submit"><svg xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                    stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-green-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <button type="button" id="cancelMotherNameEdit"><svg
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <span id="modalMotherNameError"
                                                class="text-red-500 text-xs ml-2 hidden"></span>
                                        </form>
                                        <button type="button" id="editMotherNameBtn"
                                            class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                            title="ویرایش نام مادر">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">شماره موبایل</label>
                                    <div class="flex items-center justify-between">
                                        <p id="modalMotherPhone" class="text-lg font-semibold text-gray-800 font-mono">
                                        </p>
                                        <form id="modalMotherPhoneForm"
                                            class="hidden items-center space-x-2 space-x-reverse"
                                            style="margin:0;">
                                            <input type="text" id="modalMotherPhoneInput"
                                                class="border border-gray-300 text-black rounded px-2 py-1 text-sm w-48 num-input"
                                                placeholder="09123456789">
                                            <button type="submit"><svg xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                    stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-green-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <button type="button" id="cancelMotherPhoneEdit"><svg
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <span id="modalMotherPhoneError"
                                                class="text-red-500 text-xs ml-2 hidden"></span>
                                        </form>
                                        <button type="button" id="editMotherPhoneBtn"
                                            class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                            title="ویرایش شماره موبایل مادر">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">شغل</label>
                                    <div class="flex items-center justify-between">
                                        <p id="modalMotherJob" class="text-lg font-semibold text-gray-800"></p>
                                        <form id="modalMotherJobForm"
                                            class="hidden items-center space-x-2 space-x-reverse"
                                            style="margin:0;">
                                            <input type="text" id="modalMotherJobInput"
                                                class="border border-gray-300 text-black rounded px-2 py-1 text-sm w-48"
                                                placeholder="شغل مادر">
                                            <button type="submit"><svg xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                    stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-green-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <button type="button" id="cancelMotherJobEdit"><svg
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <span id="modalMotherJobError"
                                                class="text-red-500 text-xs ml-2 hidden"></span>
                                        </form>
                                        <button type="button" id="editMotherJobBtn"
                                            class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                            title="ویرایش شغل مادر">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">درآمد ماهانه</label>
                                    <div class="flex items-center justify-between">
                                        <p id="modalMotherIncome" class="text-lg font-semibold text-gray-800"></p>
                                        <form id="modalMotherIncomeForm"
                                            class="hidden items-center space-x-2 space-x-reverse"
                                            style="margin:0;">
                                            <input type="number" id="modalMotherIncomeInput"
                                                class="border border-gray-300 text-black rounded px-2 py-1 text-sm w-48"
                                                placeholder="درآمد به تومان" min="0">
                                            <button type="submit"><svg xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                    stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-green-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <button type="button" id="cancelMotherIncomeEdit"><svg
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <span id="modalMotherIncomeError"
                                                class="text-red-500 text-xs ml-2 hidden"></span>
                                        </form>
                                        <button type="button" id="editMotherIncomeBtn"
                                            class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                            title="ویرایش درآمد مادر">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">آدرس محل کار</label>
                                    <div class="flex items-center justify-between">
                                        <p id="modalMotherJobAddress"
                                            class="text-lg font-semibold text-gray-800 leading-relaxed"></p>
                                        <form id="modalMotherJobAddressForm"
                                            class="hidden items-center space-x-2 space-x-reverse"
                                            style="margin:0;">
                                            <textarea id="modalMotherJobAddressInput"
                                                class="border border-gray-300 text-black rounded px-2 py-1 text-sm w-48 h-20 resize-none"
                                                placeholder="آدرس محل کار مادر"></textarea>
                                            <button type="submit"><svg xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                    stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-green-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <button type="button" id="cancelMotherJobAddressEdit"><svg
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <span id="modalMotherJobAddressError"
                                                class="text-red-500 text-xs ml-2 hidden"></span>
                                        </form>
                                        <button type="button" id="editMotherJobAddressBtn"
                                            class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                            title="ویرایش آدرس محل کار مادر">
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
                                    <div class="flex items-center justify-between mb-2">
                                        <p id="modalMotivation" class="text-gray-800 leading-relaxed flex-1"></p>
                                        <button type="button" id="editMotivationBtn"
                                            class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                            title="ویرایش انگیزه">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <form id="modalMotivationForm"
                                        class="hidden items-center space-x-2 space-x-reverse"
                                        style="margin:0;">
                                        <textarea id="modalMotivationInput"
                                            class="border border-gray-300 text-black rounded px-2 py-1 text-sm w-full h-32 resize-none"
                                            placeholder="انگیزه خود را توضیح دهید..."></textarea>
                                        <button type="submit"><svg xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                stroke="currentColor"
                                                class="size-5 text-gray-400 hover:text-green-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg></button>
                                        <button type="button" id="cancelMotivationEdit"><svg
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                class="size-5 text-gray-400 hover:text-red-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg></button>
                                        <span id="modalMotivationError"
                                            class="text-red-500 text-xs ml-2 hidden"></span>
                                    </form>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-2">نحوه استفاده از کمک
                                    مالی</label>
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <p id="modalSpend" class="text-gray-800 leading-relaxed flex-1"></p>
                                        <button type="button" id="editSpendBtn"
                                            class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                            title="ویرایش نحوه استفاده">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <form id="modalSpendForm"
                                        class="hidden items-center space-x-2 space-x-reverse"
                                        style="margin:0;">
                                        <textarea id="modalSpendInput"
                                            class="border border-gray-300 text-black rounded px-2 py-1 text-sm w-full h-32 resize-none"
                                            placeholder="نحوه استفاده از کمک مالی را توضیح دهید..."></textarea>
                                        <button type="submit"><svg xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                stroke="currentColor"
                                                class="size-5 text-gray-400 hover:text-green-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg></button>
                                        <button type="button" id="cancelSpendEdit"><svg
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                class="size-5 text-gray-400 hover:text-red-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg></button>
                                        <span id="modalSpendError"
                                            class="text-red-500 text-xs ml-2 hidden"></span>
                                    </form>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-2">معرفی خود</label>
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <p id="modalHowAmI" class="text-gray-800 leading-relaxed flex-1"></p>
                                            <button type="button" id="editHowAmIBtn"
                                                class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                                title="ویرایش معرفی خود">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                        </div>
                                        <form id="modalHowAmIForm"
                                            class="hidden items-center space-x-2 space-x-reverse"
                                            style="margin:0;">
                                            <textarea id="modalHowAmIInput"
                                                class="border border-gray-300 text-black rounded px-2 py-1 text-sm w-full h-32 resize-none"
                                                placeholder="خود را معرفی کنید..."></textarea>
                                            <button type="submit"><svg xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                    stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-green-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <button type="button" id="cancelHowAmIEdit"><svg
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <span id="modalHowAmIError"
                                                class="text-red-500 text-xs ml-2 hidden"></span>
                                        </form>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-2">برنامه‌های
                                        آینده</label>
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <p id="modalFuture" class="text-gray-800 leading-relaxed flex-1"></p>
                                            <button type="button" id="editFutureBtn"
                                                class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                                title="ویرایش برنامه‌های آینده">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                        </div>
                                        <form id="modalFutureForm"
                                            class="hidden items-center space-x-2 space-x-reverse"
                                            style="margin:0;">
                                            <textarea id="modalFutureInput"
                                                class="border border-gray-300 text-black rounded px-2 py-1 text-sm w-full h-32 resize-none"
                                                placeholder="برنامه‌های آینده خود را توضیح دهید..."></textarea>
                                            <button type="submit"><svg xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                    stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-green-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <button type="button" id="cancelFutureEdit"><svg
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <span id="modalFutureError"
                                                class="text-red-500 text-xs ml-2 hidden"></span>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-2">رشته مورد
                                        علاقه</label>
                                    <div class="flex items-center justify-between">
                                        <p id="modalFavoriteMajor" class="text-lg font-semibold text-gray-800"></p>
                                        <form id="modalFavoriteMajorForm"
                                            class="hidden items-center space-x-2 space-x-reverse w-full"
                                            style="margin:0;">
                                        <textarea id="modalFavoriteMajorInput"
                                            class="border border-gray-300 text-black rounded px-2 py-1 text-sm w-full h-32 resize-none"
                                            placeholder="انگیزه خود را توضیح دهید..."></textarea>
                                            <button type="submit"><svg xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                    stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-green-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <button type="button" id="cancelFavoriteMajorEdit"><svg
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <span id="modalFavoriteMajorError"
                                                class="text-red-500 text-xs ml-2 hidden"></span>
                                        </form>
                                        <button type="button" id="editFavoriteMajorBtn"
                                            class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                            title="ویرایش رشته مورد علاقه">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-2">آمادگی کمک به
                                        دیگران در آینده</label>
                                    <div class="flex items-center justify-between">
                                        <p id="modalHelpOthers" class="text-lg font-semibold text-gray-800"></p>
                                        <form id="modalHelpOthersForm"
                                            class="hidden items-center space-x-2 space-x-reverse"
                                            style="margin:0;">
                                            <select id="modalHelpOthersInput"
                                                class="border border-gray-300 text-black rounded px-2 py-1 text-sm">
                                                <option value="">انتخاب کنید</option>
                                                <option value="بله">بله</option>
                                                <option value="خیر">خیر</option>
                                                <option value="در صورت امکان">در صورت امکان</option>
<option value="هرکسی باید با تکیه بر توانایی های خود به دنبال موفقیت باشد و کمک در این راه لزومی ندارد"
        title="متن کامل: هرکسی باید با تکیه بر توانایی های خود به دنبال موفقیت باشد و کمک در این راه لزومی ندارد">
    هرکسی باید با تکیه بر توانایی‌های خود موفق شود (کمک لازم نیست)
</option>
                                            </select>
                                            <button type="submit"><svg xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                    stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-green-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <button type="button" id="cancelHelpOthersEdit"><svg
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                    class="size-5 text-gray-400 hover:text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg></button>
                                            <span id="modalHelpOthersError"
                                                class="text-red-500 text-xs ml-2 hidden"></span>
                                        </form>
                                        <button type="button" id="editHelpOthersBtn"
                                            class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                            title="ویرایش آمادگی کمک">
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

                            <div id="modalSuggestionDiv" class="hidden">
                                <label class="block text-sm font-medium text-gray-500 mb-2">پیشنهادات برای بهتر شدن
                                    عملکرد بنیاد</label>
                                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <p id="modalSuggestion" class="text-gray-800 leading-relaxed flex-1"></p>
                                        <button type="button" id="editSuggestionBtn"
                                            class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                            title="ویرایش پیشنهادات">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <form id="modalSuggestionForm"
                                        class="hidden items-center space-x-2 space-x-reverse"
                                        style="margin:0;">
                                        <textarea id="modalSuggestionInput"
                                            class="border border-gray-300 text-black rounded px-2 py-1 text-sm w-full h-32 resize-none"
                                            placeholder="پیشنهادات خود را بیان کنید..."></textarea>
                                        <button type="submit"><svg xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                stroke="currentColor"
                                                class="size-5 text-gray-400 hover:text-green-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg></button>
                                        <button type="button" id="cancelSuggestionEdit"><svg
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                class="size-5 text-gray-400 hover:text-red-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg></button>
                                        <span id="modalSuggestionError"
                                            class="text-red-500 text-xs ml-2 hidden"></span>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- دکمه‌های عملیات --}}
                    <div class="flex justify-center items-center space-x-4 space-x-reverse mt-8">


                        {{-- دکمه کنسل --}}
                        <a
                            class="bg-gradient-to-r from-gray-600 to-gray-700 text-white py-3 px-8 rounded-xl hover:from-gray-700 hover:to-gray-800 transition font-medium shadow-lg warning" id="warning-open">
                            ❌ کنسل
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed inset-0 bg-black bg-opacity-50 z-[60] hidden" id="waning-popup">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 border border-gray-200">
                <div class="text-center">
                    <!-- آیکون هشدار -->
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                        <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>

                    <!-- عنوان -->
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">تأیید کنسل کردن</h3>

                    <!-- متن -->
                    <p class="text-sm text-gray-600 mb-6">آیا مطمئن هستید که می‌خواهید این درخواست را کنسل کنید؟ این عمل قابل بازگشت نیست.</p>

                    <!-- دکمه‌ها -->
                    <div class="flex space-x-3 space-x-reverse justify-center">
                        <button id="warning-closepopup" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition duration-200">
                            خیر، انصراف
                        </button>
                        <a href="{{ route('unified.cancel', $request->id) }}" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 text-center">
                            بله، کنسل کن
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- JavaScript فایل‌ها --}}
    <script src="{{ asset('assets/js/request-detail-popup/utils.js') }}"></script>
    <script src="{{ asset('assets/js/request-detail-popup/modal-core.js') }}"></script>
    <script src="{{ asset('assets/js/request-detail-popup/field-management.js') }}"></script>
    <script src="{{ asset('assets/js/request-detail-popup/data-upload.js') }}"></script>
    <script src="{{ asset('assets/js/popup-functionality.js') }}"></script>
