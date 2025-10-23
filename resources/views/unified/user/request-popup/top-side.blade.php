@php
    use Illuminate\Support\Facades\Auth;
@endphp
{{-- Container اصلی grid --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
    {{-- ستون چپ - پروفایل و اطلاعات شخصی --}}
    <div class="xl:col-span-1">
        <div
            class="bg-white rounded-2xl h-full  shadow-lg p-8 mb-6 border border-gray-200 transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:shadow-2xl">
            {{-- بخش پروفایل --}}
            <div class="text-center mb-8">
                <div class="relative mx-auto mb-6">
                    <div
                        class="w-32 h-32 mx-auto rounded-full overflow-hidden bg-gray-200 border-4 border-white shadow-lg">
                        <img id="modalProfileImg" src="" alt="" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2">
                        <span id="modalStatusBadge"
                            class="status-badge px-3 py-1 text-white text-xs font-bold rounded-full shadow-lg">
                        </span>
                    </div>
                    <div class="absolute bottom-0 right-0">
                        <div class="relative group">
                            <input type="file" name="imgpath" id="profileImgInput" accept="image/*" class="hidden">
                            <button type="button" id="edituploadProfileImgBtn"
                                class="absolute bottom-0 right-0 p-2 bg-white hover:bg-blue-50 text-blue-600 rounded-full shadow-lg transform transition-all duration-200 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 group-hover:translate-y-0 translate-y-2 opacity-90 hover:opacity-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                            <!-- Loading Spinner -->
                            <div id="uploadSpinner" class="hidden absolute bottom-0 right-0 p-2">
                                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-600"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <h2 id="modalUserName" class="text-2xl font-bold text-gray-800 mb-2"></h2>
                <p id="modalUserGrade" class="text-lg text-gray-600 mb-6"></p>
            </div>

            {{-- بخش اطلاعات شخصی --}}
            <div class="border-t pt-8 relative">
                <div class="flex items-center">
                    <div class="icon-wrapper w-10 h-10 rounded-lg flex items-center justify-center mr-4"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">اطلاعات شخصی</h3>
                </div>
                <div class="flex w-full justify-between gap-8 mt-[58px] flex-col ">

                    <!-- نام و نام خانوادگی -->
                    <div class="mt-[-41px] w-fit">
                        <label class=" text-sm font-medium text-gray-500 mb-1">نام و نام
                            خانوادگی</label>
                        <div class=" items-center justify-between">
                            <span id="modalNameDisplay" class="text-lg font-semibold text-gray-800 "></span>
                            <form id="modalNameForm" class="hidden items-center space-x-2 space-x-reverse"
                                style="margin:0;">
                                <input type="text" id="modalNameInput"
                                    class="border border-gray-300 text-black rounded  w-28 text-lg" maxlength="75"
                                    autocomplete="off">
                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                        class="size-5 text-gray-400 hover:text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg></button>
                                <button type="button" id="cancelNameEdit"><svg xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                        class="size-5 text-gray-400  hover:text-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg></button>
                                <span id="modalNameError" class="text-red-500 text-xs ml-2 hidden"></span>
                            </form>
                            <button type="button" id="editNameBtn"
                                class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors" title="ویرایش نام">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- کد ملی -->
                    <div class="mt-[-41px] w-fit mr-auto">
                        <label class=" text-sm font-medium text-gray-500 mb-1">کد ملی</label>
                        <div class=" items-center justify-between">
                            <span id="modalNationalCodeDisplay"
                                class="text-lg font-mono font-semibold text-gray-800 "></span>
                            <form id="modalNationalCodeForm" class="hidden items-center space-x-2 space-x-reverse"
                                style="margin:0;">
                                <input type="text" id="modalNationalCodeInput"
                                    class="border border-gray-300 text-black rounded  w-28 text-lg num-input">
                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                        class="size-5 text-gray-400 hover:text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                                <button type="button" id="cancelNationalCodeEdit"><svg
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2.5" stroke="currentColor"
                                        class="size-5 text-gray-400  hover:text-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                                <span id="modalNationalCodeError"
                                    class="text-red-500 text-xs ml-2 hidden absolute left-44 bottom-[146px]"></span>
                            </form>
                            <button type="button" id="editNationalCodeBtn"
                                class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                title="ویرایش کد ملی">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- تاریخ تولد -->
                    <div class="mt-[-41px] w-fit">
                        <label class=" text-sm font-medium text-gray-500 mb-1">تاریخ تولد</label>
                        <div class=" items-center justify-between">
                            <span id="modalBirthdateDisplay" class="text-base font-semibold text-gray-800"></span>
                            <form id="modalBirthdateForm" class="hidden items-center space-x-2 space-x-reverse"
                                style="margin:0;">
                                <input type="text" id="modalBirthdateInput"
                                    class="border border-gray-300 text-black rounded  w-28 text-lg"
                                    placeholder="۱۴۰۰/۰۱/۰۱" autocomplete="off">
                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                        class="size-5 text-gray-400 hover:text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg></button>
                                <button type="button" id="cancelBirthdateEdit"><svg
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2.5" stroke="currentColor"
                                        class="size-5 text-gray-400  hover:text-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg></button>
                                <span id="modalBirthdateError" class="text-red-500 text-xs ml-2 hidden"></span>
                            </form>
                            <button type="button" id="editBirthdateBtn"
                                class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                title="ویرایش تاریخ تولد">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- شماره موبایل -->
                    <div class="mt-[-41px] w-fit mr-auto ">

                        <label class=" text-sm font-medium text-gray-500 mb-1 ">شماره موبایل</label>
                        <div class=" items-center justify-between">
                            <span id="modalPhoneDisplay"
                                class="text-base font-mono font-semibold text-gray-800"></span>
                            <form id="modalPhoneForm" class="hidden items-center space-x-2 space-x-reverse "
                                style="margin:0;">
                                <span id="modalPhoneError"
                                    class="text-red-500 text-xs ml-2 hidden absolute left-44 bottom-[54px]"></span>
                                <input type="text" id="modalPhoneInput"
                                    class="border border-gray-300 text-black rounded  w-28 text-lg num-input">
                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                        class="size-5 text-gray-400 hover:text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg></button>
                                <button type="button" id="cancelPhoneEdit"><svg xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                        class="size-5 text-gray-400  hover:text-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg></button>
                            </form>
                            <button type="button" id="editPhoneBtn"
                                class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                title="ویرایش شماره موبایل">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- تلفن ثابت -->
                    <div class="mt-[-41px] w-fit">

                        <label class=" text-sm font-medium text-gray-500 mb-1">تلفن ثابت</label>
                        <div class=" items-center justify-between">
                            <span id="modalTelephoneDisplay"
                                class="text-base font-mono font-semibold text-gray-800"></span>
                            <form id="modalTelephoneForm" class="hidden items-center space-x-2 space-x-reverse"
                                style="margin:0;">
                                <input type="text" id="modalTelephoneInput"
                                    class="border border-gray-300 text-black rounded  w-28 text-lg num-input"
                                    maxlength="11" autocomplete="off">
                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                        class="size-5 text-gray-400 hover:text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg></button>
                                <button type="button" id="cancelTelephoneEdit"><svg
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2.5" stroke="currentColor"
                                        class="size-5 text-gray-400  hover:text-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg></button>
                                <span id="modalTelephoneError" class="text-red-500 text-xs ml-2 hidden"></span>
                            </form>
                            <button type="button" id="editTelephoneBtn"
                                class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                title="ویرایش تلفن ثابت">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    @if (Auth::user()->role != 'user')
                        <div class="mt-8 border-t pt-6">

                            <!-- دکمه‌های عملیات - سه دکمه با اندازه یکسان -->
                            <div class="grid grid-cols-3 gap-3">
                                <!-- دکمه تایید درخواست -->
                                <form method="POST" action="{{ route('unified.accept') }}" class="w-full">
                                    @csrf
                                    <input type="hidden" name="id" class="requestid">
                                    <button type="submit"
                                        class="group relative w-full px-3 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>تایید</span>
                                        <div
                                            class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 rounded-xl transition-opacity duration-200">
                                        </div>
                                    </button>
                                </form>

                                <!-- دکمه رد درخواست -->
                                <form method="POST" action="{{ route('unified.reject') }}" class="w-full">
                                    @csrf
                                    <input type="hidden" name="id" class="requestid">
                                    <button type="submit"
                                        class="group relative w-full px-3 py-3 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        <span>رد</span>
                                        <div
                                            class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 rounded-xl transition-opacity duration-200">
                                        </div>
                                    </button>
                                </form>


                                <button type="button" onclick="openModal('epointmet-modal')"
                                    class="group relative w-full px-3 py-3 bg-gradient-to-r from-purple-500 to-violet-600 hover:from-purple-600 hover:to-violet-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span>ملاقات</span>
                                    <div
                                        class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 rounded-xl transition-opacity duration-200">
                                    </div>
                                </button>
                            </div>
                            <!-- دکمه چاپ - در بالا و جدا با عرض کامل -->
                            <div class="mt-4">
                                <button type="button" id="printRequestBtn"
                                    class="group relative w-full px-6 py-3 bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                        </path>
                                    </svg>
                                    <span>چاپ درخواست</span>
                                    <div
                                        class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 rounded-xl transition-opacity duration-200">
                                    </div>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ستون راست - جزئیات --}}
    <div class="xl:col-span-2 space-y-6">
        {{-- اطلاعات تحصیلی --}}
        <div
            class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:shadow-2xl">
            <div class="flex items-center mb-[4.5rem]">
                <div class="icon-wrapper w-12 h-12 rounded-xl flex items-center justify-center mr-4"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">اطلاعات تحصیلی</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-[7rem]">
                <div class="space-y-4 flex flex-col gap-[50px]">
                    <!-- پایه تحصیلی -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">پایه
                            تحصیلی</label>
                        <div class="flex items-center justify-between">
                            <span id="modalGradeDisplay" class="text-lg font-semibold text-gray-800"></span>
                            <form id="modalGradeForm" class="hidden items-center space-x-2 space-x-reverse"
                                style="margin:0;">
                                <select id="modalGradeInput"
                                    class="border border-gray-300 text-black rounded px-2 py-1 text-sm">
                                    <option value="">انتخاب کنید</option>
                                    <option value="اول">اول</option>
                                    <option value="دوم">دوم</option>
                                    <option value="سوم">سوم</option>
                                    <option value="چهارم">چهارم</option>
                                    <option value="پنجم">پنجم</option>
                                    <option value="ششم">ششم</option>
                                    <option value="هفتم">هفتم</option>
                                    <option value="هشتم">هشتم</option>
                                    <option value="نهم">نهم</option>
                                    <option value="دهم">دهم</option>
                                    <option value="یازدهم">یازدهم</option>
                                    <option value="دوازدهم">دوازدهم</option>
                                </select>
                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                        class="size-5 text-gray-400 hover:text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg></button>
                                <button type="button" id="cancelGradeEdit"><svg xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                        class="size-5 text-gray-400 hover:text-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg></button>
                                <span id="modalGradeError" class="text-red-500 text-xs ml-2 hidden"></span>
                            </form>
                            <button type="button" id="editGradeBtn"
                                class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                title="ویرایش پایه تحصیلی">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- نام مدرسه -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">نام
                            مدرسه</label>
                        <div class="flex items-center justify-between">
                            <span id="modalSchoolDisplay" class="text-lg font-semibold text-gray-800"></span>
                            <form id="modalSchoolForm" class="hidden items-center space-x-2 space-x-reverse"
                                style="margin:0;">
                                <input type="text" id="modalSchoolInput"
                                    class="border border-gray-300 text-black rounded px-2 py-1 w-32 text-sm"
                                    maxlength="255" autocomplete="off">
                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                        class="size-5 text-gray-400 hover:text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg></button>
                                <button type="button" id="cancelSchoolEdit"><svg xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                        class="size-5 text-gray-400 hover:text-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg></button>
                                <span id="modalSchoolError" class="text-red-500 text-xs ml-2 hidden"></span>
                            </form>
                            <button type="button" id="editSchoolBtn"
                                class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                title="ویرایش نام مدرسه">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- نام مدیر مدرسه -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">نام مدیر
                            مدرسه</label>
                        <div class="flex items-center justify-between">
                            <span id="modalPrincipalDisplay" class="text-lg font-semibold text-gray-800"></span>
                            <form id="modalPrincipalForm" class="hidden items-center space-x-2 space-x-reverse"
                                style="margin:0;">
                                <input type="text" id="modalPrincipalInput"
                                    class="border border-gray-300 text-black rounded px-2 py-1 w-32 text-sm"
                                    maxlength="75" autocomplete="off">
                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                        class="size-5 text-gray-400 hover:text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg></button>
                                <button type="button" id="cancelPrincipalEdit"><svg
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2.5" stroke="currentColor"
                                        class="size-5 text-gray-400 hover:text-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg></button>
                                <span id="modalPrincipalError" class="text-red-500 text-xs ml-2 hidden"></span>
                            </form>
                            <button type="button" id="editPrincipalBtn"
                                class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                title="ویرایش نام مدیر">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="space-y-4 flex flex-col gap-[50px]">
                    <!-- رشته تحصیلی (فقط نمایش) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">رشته
                            تحصیلی</label>
                        <p id="modalMajor" class="text-lg font-semibold text-gray-800"></p>
                    </div>

                    <!-- معدل ترم قبل -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">معدل ترم
                            قبل</label>
                        <div class="flex items-center justify-between">
                            <span id="modalLastScoreDisplay" class="text-lg font-semibold text-gray-800"></span>
                            <form id="modalLastScoreForm" class="hidden items-center space-x-2 space-x-reverse"
                                style="margin:0;">
                                <input type="number" id="modalLastScoreInput"
                                    class="border border-gray-300 text-black rounded px-2 py-1 w-20 text-sm"
                                    min="0" max="20" step="0.01" autocomplete="off">
                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                        class="size-5 text-gray-400 hover:text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg></button>
                                <button type="button" id="cancelLastScoreEdit"><svg
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2.5" stroke="currentColor"
                                        class="size-5 text-gray-400 hover:text-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg></button>
                                <span id="modalLastScoreError" class="text-red-500 text-xs ml-2 hidden"></span>
                            </form>
                            <button type="button" id="editLastScoreBtn"
                                class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                title="ویرایش معدل">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- تلفن مدرسه -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">تلفن
                            مدرسه</label>
                        <div class="flex items-center justify-between">
                            <span id="modalSchoolTelephoneDisplay"
                                class="text-lg font-semibold text-gray-800 font-mono"></span>
                            <form id="modalSchoolTelephoneForm" class="hidden items-center space-x-2 space-x-reverse"
                                style="margin:0;">
                                <input type="text" id="modalSchoolTelephoneInput"
                                    class="border border-gray-300 text-black rounded px-2 py-1 w-28 text-sm num-input">
                                <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                        class="size-5 text-gray-400 hover:text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg></button>
                                <button type="button" id="cancelSchoolTelephoneEdit"><svg
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2.5" stroke="currentColor"
                                        class="size-5 text-gray-400 hover:text-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg></button>
                                <span id="modalSchoolTelephoneError" class="text-red-500 text-xs ml-2 hidden"></span>
                            </form>
                            <button type="button" id="editSchoolTelephoneBtn"
                                class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                                title="ویرایش تلفن مدرسه">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- سطح زبان انگلیسی -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-500 mb-1">سطح زبان
                        انگلیسی</label>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 space-x-reverse flex-1" id="modalEnglishDisplay">
                            <div class="flex-1 bg-gray-200 rounded-full h-6">
                                <div id="modalEnglishBar"
                                    class="h-6 rounded-full transition-all duration-500 english-progress-bar"
                                    style="width: 0%; background: linear-gradient(270deg, #8b5cf6 0%, #3b82f6 100%);">
                                </div>
                            </div>
                            <span id="modalEnglishPercent" class="text-lg font-semibold text-gray-800">0%</span>
                        </div>
                        <div class="items-center space-x-3 space-x-reverse flex-1 hidden" id="modalEnglishEdit">
                            <input type="range" id="modalEnglishSlider"
                                class="flex-1 h-6 rounded-full appearance-none  cursor-pointer slider-purple-main dynamic-bg"
                                min="0" max="100" value="0" step="1">
                            <span id="modalEnglishSliderValue" class="text-lg font-semibold text-gray-800">0%</span>
                        </div>
                        <button type="button" id="editEnglishBtn"
                            class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors"
                            title="ویرایش سطح انگلیسی">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button type="button" id="saveEnglishBtn"
                            class="ml-2 p-1 text-gray-400 hover:text-green-600 transition-colors hidden"
                            title="ذخیره سطح انگلیسی">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </button>
                        <button type="button" id="cancelEnglishBtn"
                            class="ml-2 p-1 text-gray-400 hover:text-red-600 transition-colors hidden"
                            title="لغو ویرایش">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- کارنامه --}}
            <div id="modalGradeSheet" class="mt-[4.5rem] p-4 bg-gray-50 rounded-xl hidden mb-[66px]">
                <label class="block text-sm font-medium text-gray-500 mb-3">کارنامه ترم قبل</label>
                <div class="flex items-center space-x-3 space-x-reverse">
                    <img id="modalGradeSheetImg" src="" alt="کارنامه"
                        class="w-16 h-16 object-cover rounded-lg border-2 border-gray-300">
                    <a id="modalGradeSheetLink" href="" target="_blank"
                        class="text-blue-600 hover:text-blue-800 font-medium">مشاهده کارنامه</a>
                    <button type="button" id="edituploadGradeSheetBtn"
                        class="bg-green-500 hover:bg-green-600 text-white rounded-lg px-3 py-1 text-sm shadow-lg transition-colors"
                        title="آپلود کارنامه جدید">
                        آپلود مجدد
                    </button>
                    <input type="file" id="gradeSheetInput" accept="image/*,application/pdf" class="hidden">
                </div>
            </div>
        </div>
    </div>
</div> {{-- بستن container grid اصلی --}}
