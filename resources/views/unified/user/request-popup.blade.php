<link rel="stylesheet" href="{{asset('assets/css/request-detail.css')}}">

{{-- مودال جزئیات درخواست برای کاربران --}}
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

            <div class="bg-white rounded-2xl w-full max-h-[95vh] overflow-y-auto shadow-2xl modal-content">
            {{-- محتوای مودال --}}
            <div class="p-8 bg-gray-50">
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                    {{-- ستون چپ - پروفایل و اطلاعات شخصی --}}
                    <div class="xl:col-span-1">
                        <div class="bg-white rounded-2xl h-full  shadow-lg p-8 mb-6 border border-gray-200 transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:shadow-2xl">
                            {{-- بخش پروفایل --}}
                            <div class="text-center mb-8">
                                <div class="relative mx-auto mb-6">
                                    <div class="w-32 h-32 mx-auto rounded-full overflow-hidden bg-gray-200 border-4 border-white shadow-lg">
                                        <img id="modalProfileImg" src="" alt="" class="w-full h-full object-cover">
                                    </div>
                                    <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2">
                                        <span id="modalStatusBadge" class="status-badge px-3 py-1 text-white text-xs font-bold rounded-full shadow-lg">
                                        </span>
                                    </div>
                                </div>

                                <h2 id="modalUserName" class="text-2xl font-bold text-gray-800 mb-2"></h2>
                                <p id="modalUserGrade" class="text-lg text-gray-600 mb-6"></p>
                            </div>

                            {{-- بخش اطلاعات شخصی --}}
                            <div class="border-t pt-8">
                                <div class="flex items-center">
                                    <div class="icon-wrapper w-10 h-10 rounded-lg flex items-center justify-center mr-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-800">اطلاعات شخصی</h3>
                                </div>
                                <div class="flex flex-row w-full justify-between gap-8 mt-[84px]">
                                    <div class="flex flex-col justify-between flex-1">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500 mb-1">کد ملی</label>
                                            <div class="flex items-center justify-between">
                                                <span id="modalNationalCodeDisplay" class="text-lg font-mono font-semibold text-gray-800 "></span>
                                                <form id="modalNationalCodeForm" class="hidden items-center space-x-2 space-x-reverse" style="margin:0;">
                                                    <input type="text" id="modalNationalCodeInput" class="border num-input border-gray-300 text-black rounded px-2 py-1 w-32 text-lg font-mono" maxlength="10" pattern="[0-9]{10}" autocomplete="off">
                                                    <button type="submit" class="bg-blue-500 text-white rounded px-2 py-1 text-sm ml-1">ذخیره</button>
                                                    <button type="button" id="cancelNationalCodeEdit" class="text-gray-400 hover:text-red-500 text-sm">لغو</button>
                                                    <span id="modalNationalCodeError" class="text-red-500 text-xs ml-2 hidden"></span>
                                                </form>
                                                <button type="button" id="editNationalCodeBtn" class="ml-2 p-1 text-gray-400 hover:text-blue-600 transition-colors" title="ویرایش کد ملی">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500 mb-1">تاریخ تولد</label>
                                            <p id="modalBirthdate" class="text-base font-semibold text-gray-800"></p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col justify-between flex-1">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500 mb-1">شماره موبایل</label>
                                            <p id="modalPhone" class="text-base font-mono font-semibold text-gray-800"></p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500 mb-1">تلفن ثابت</label>
                                            <p id="modalTelephone" class="text-base font-mono font-semibold text-gray-800"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ستون راست - جزئیات --}}
                    <div class="xl:col-span-2 space-y-6">
                        {{-- اطلاعات تحصیلی --}}
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:shadow-2xl">
                            <div class="flex items-center mb-6">
                                <div class="icon-wrapper w-12 h-12 rounded-xl flex items-center justify-center mr-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800">اطلاعات تحصیلی</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-[7rem]">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-1">پایه تحصیلی</label>
                                        <p id="modalGrade" class="text-lg font-semibold text-gray-800"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-1">نام مدرسه</label>
                                        <p id="modalSchool" class="text-lg font-semibold text-gray-800"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-1">نام مدیر مدرسه</label>
                                        <p id="modalPrincipal" class="text-lg font-semibold text-gray-800"></p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-1">رشته تحصیلی</label>
                                        <p id="modalMajor" class="text-lg font-semibold text-gray-800"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-1">معدل ترم قبل</label>
                                        <p id="modalLastScore" class="text-lg font-semibold text-gray-800"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 mb-1">تلفن مدرسه</label>
                                        <p id="modalSchoolTelephone" class="text-lg font-semibold text-gray-800 font-mono"></p>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">سطح زبان انگلیسی</label>
                                    <div class="flex items-center space-x-3 space-x-reverse">
                                        <div class="flex-1 bg-gray-200 rounded-full h-6">
                                            <div id="modalEnglishBar" class="bg-gradient-to-r from-blue-600 to-purple-600 h-6 rounded-full transition-all duration-500" style="width: 0%"></div>
                                        </div>
                                        <span id="modalEnglishPercent" class="text-lg font-semibold text-gray-800">0%</span>
                                    </div>
                                </div>
                            </div>

                            {{-- کارنامه --}}
                            <div id="modalGradeSheet" class="mt-6 p-4 bg-gray-50 rounded-xl hidden">
                                <label class="block text-sm font-medium text-gray-500 mb-3">کارنامه ترم قبل</label>
                                <div class="flex items-center space-x-3 space-x-reverse">
                                    <img id="modalGradeSheetImg" src="" alt="کارنامه" class="w-16 h-16 object-cover rounded-lg border-2 border-gray-300">
                                    <a id="modalGradeSheetLink" href="" target="_blank" class="text-blue-600 hover:text-blue-800 font-medium">مشاهده کارنامه</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- اطلاعات مسکن و خانواده --}}
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mt-6">
                    {{-- اطلاعات مسکن --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:shadow-2xl">
                        <div class="flex items-center mb-6">
                            <div class="icon-wrapper w-12 h-12 rounded-xl flex items-center justify-center mr-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">اطلاعات مسکن</h3>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">وضعیت مسکن</label>
                                <p id="modalRental" class="text-lg font-semibold text-gray-800"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">آدرس کامل</label>
                                <p id="modalAddress" class="text-lg font-semibold text-gray-800 leading-relaxed"></p>
                            </div>
                        </div>
                    </div>

                    {{-- اطلاعات خانوادگی --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:shadow-2xl">
                        <div class="flex items-center mb-6">
                            <div class="icon-wrapper w-12 h-12 rounded-xl flex items-center justify-center mr-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">اطلاعات خانوادگی</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">تعداد فرزندان</label>
                                    <p id="modalSiblingsCount" class="text-lg font-semibold text-gray-800"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">رتبه متقاضی</label>
                                    <p id="modalSiblingsRank" class="text-lg font-semibold text-gray-800"></p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">نحوه آشنایی با بنیاد</label>
                                    <p id="modalKnow" class="text-lg font-semibold text-gray-800"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">روش مشاوره مورد نظر</label>
                                    <p id="modalCounselingMethod" class="text-lg font-semibold text-gray-800"></p>
                                </div>
                                <div id="modalWhyCounselingMethodDiv" class="hidden">
                                    <label class="block text-sm font-medium text-gray-500 mb-1">دلیل انتخاب روش مشاوره</label>
                                    <p id="modalWhyCounselingMethod" class="text-lg font-semibold text-gray-800"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- اطلاعات والدین --}}
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mt-6">
                    {{-- اطلاعات پدر --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:shadow-2xl">
                        <div class="flex items-center mb-6">
                            <div class="icon-wrapper w-12 h-12 rounded-xl flex items-center justify-center mr-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
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
                                <p id="modalFatherPhone" class="text-lg font-semibold text-gray-800 font-mono"></p>
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
                                <p id="modalFatherJobAddress" class="text-lg font-semibold text-gray-800 leading-relaxed"></p>
                            </div>
                        </div>
                    </div>

                    {{-- اطلاعات مادر --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:shadow-2xl">
                        <div class="flex items-center mb-6">
                            <div class="icon-wrapper w-12 h-12 rounded-xl flex items-center justify-center mr-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
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
                                <p id="modalMotherPhone" class="text-lg font-semibold text-gray-800 font-mono"></p>
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
                                <p id="modalMotherJobAddress" class="text-lg font-semibold text-gray-800 leading-relaxed"></p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- سوالات نهایی و انگیزه‌ها --}}
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 transition-all duration-300 ease-in-out hover:-translate-y-0.5 hover:shadow-2xl mt-6">
                    <div class="flex items-center mb-6">
                        <div class="icon-wrapper w-12 h-12 rounded-xl flex items-center justify-center mr-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">سوالات نهایی و انگیزه‌ها</h3>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">انگیزه درخواست بورسیه</label>
                            <div class="bg-gray-50 rounded-xl p-4">
                                <p id="modalMotivation" class="text-gray-800 leading-relaxed"></p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">نحوه استفاده از کمک مالی</label>
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
                                <label class="block text-sm font-medium text-gray-500 mb-2">برنامه‌های آینده</label>
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <p id="modalFuture" class="text-gray-800 leading-relaxed"></p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-2">رشته مورد علاقه</label>
                                <p id="modalFavoriteMajor" class="text-lg font-semibold text-gray-800"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-2">آمادگی کمک به دیگران</label>
                                <p id="modalHelpOthers" class="text-lg font-semibold text-gray-800"></p>
                            </div>
                        </div>

                        <div id="modalSuggestionDiv" class="hidden">
                            <label class="block text-sm font-medium text-gray-500 mb-2">پیشنهادات برای بهتر شدن عملکرد بنیاد</label>
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
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

<script src="{{asset('assets/js/request-detail-popup/close.js')}}"></script>
<script src='{{asset("assets/js/request-detail-popup/popup-functionality.js")}}'></script>
<script src="{{asset('assets/js/numinput.js')}}"></script>
<script>
// ویرایش اینلاین کد ملی
document.addEventListener('DOMContentLoaded', function() {
    // اطمینان از آماده بودن المان‌ها
    function initializeNationalCodeEdit() {
        const display = document.getElementById('modalNationalCodeDisplay');
        const form = document.getElementById('modalNationalCodeForm');
        const input = document.getElementById('modalNationalCodeInput');
        const error = document.getElementById('modalNationalCodeError');
        const editBtn = document.getElementById('editNationalCodeBtn');
        const cancelBtn = document.getElementById('cancelNationalCodeEdit');

        // بررسی وجود تمام المان‌ها
        if (!display || !form || !input || !error || !editBtn || !cancelBtn) {
            console.log('بعضی المان‌های مورد نیاز برای ویرایش کد ملی یافت نشدند');
            return;
        }

        // مقدار اولیه را تنظیم کن
        function setNationalCodeValue(val) {
            if (display && input) {
                display.textContent = val;
                input.value = val;
            }
        }

        // نمایش فرم ویرایش
        editBtn.addEventListener('click', function() {
            const currentVal = display.textContent.trim();
            input.value = currentVal;
            display.classList.add('hidden');
            editBtn.classList.add('hidden');
            form.classList.remove('hidden');
            form.classList.add('flex');
            error.classList.add('hidden');
            input.focus();
            input.select();
        });

        // لغو ویرایش
        cancelBtn.addEventListener('click', function() {
            form.classList.add('hidden');
            form.classList.remove('flex');
            display.classList.remove('hidden');
            editBtn.classList.remove('hidden');
            error.classList.add('hidden');
        });

        // اعتبارسنجی کد ملی
        function validateNationalCode(val) {
            if (!val || val.length !== 10) return 'کد ملی باید 10 رقم باشد';
            if (!/^[0-9]+$/.test(val)) return 'کد ملی فقط باید شامل اعداد باشد';
            // الگوریتم صحت کد ملی
            const check = parseInt(val.charAt(9));
            let sum = 0;
            for (let i = 0; i < 9; i++) sum += parseInt(val.charAt(i)) * (10 - i);
            const rem = sum % 11;
            if (!((rem < 2 && check === rem) || (rem >= 2 && check === 11 - rem))) return 'کد ملی معتبر نیست';
            return '';
        }

        // ذخیره تغییرات
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const newVal = input.value.trim();
            const errMsg = validateNationalCode(newVal);

            if (errMsg) {
                error.textContent = errMsg;
                error.classList.remove('hidden');
                return;
            }

            if (newVal === display.textContent.trim()) {
                // مقدار تغییر نکرده
                form.classList.add('hidden');
                form.classList.remove('flex');
                display.classList.remove('hidden');
                editBtn.classList.remove('hidden');
                return;
            }

            // ارسال AJAX
            fetch('/unified/update-request-field', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    request_id: window.currentRequestId,
                    field_name: 'nationalcode',
                    field_value: newVal
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // بستن فرم ویرایش
                    form.classList.add('hidden');
                    form.classList.remove('flex');
                    display.classList.remove('hidden');
                    editBtn.classList.remove('hidden');
                    error.classList.add('hidden');

                    // نمایش پیام موفقیت
                    showSuccessMessage('کد ملی با موفقیت ذخیره شد');

                    // بارگیری مجدد اطلاعات از دیتابیس
                    refreshRequestData();
                } else {
                    error.textContent = data.message || 'خطا در ذخیره اطلاعات';
                    error.classList.remove('hidden');
                }
            })
            .catch(() => {
                error.textContent = 'خطا در ارتباط با سرور';
                error.classList.remove('hidden');
            });
        });
    }

    // اجرای تابع init بعد از بارگذاری کامل
    setTimeout(initializeNationalCodeEdit, 100);
});

// نمایش پیام موفقیت
function showSuccessMessage(message) {
    const messageEl = document.createElement('div');
    messageEl.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-[100] transform translate-x-full transition-transform duration-300';
    messageEl.textContent = message;
    document.body.appendChild(messageEl);

    setTimeout(() => messageEl.classList.remove('translate-x-full'), 100);
    setTimeout(() => {
        messageEl.classList.add('translate-x-full');
        setTimeout(() => document.body.removeChild(messageEl), 300);
    }, 3000);
}

// بارگیری مجدد اطلاعات درخواست از دیتابیس
function refreshRequestData() {
    if (!window.currentRequestId) {
        console.error('شناسه درخواست در دسترس نیست');
        return;
    }

    // نمایش loading
    const loadingEl = document.createElement('div');
    loadingEl.className = 'fixed top-4 left-4 bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg z-[100] flex items-center space-x-2 space-x-reverse';
    loadingEl.innerHTML = `
        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>در حال بروزرسانی...</span>
    `;
    document.body.appendChild(loadingEl);

    // درخواست AJAX برای دریافت اطلاعات جدید
    fetch(`/unified/get-request-data/${window.currentRequestId}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('خطا در دریافت اطلاعات');
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.request) {
            // بروزرسانی اطلاعات در modal
            updateModalWithNewData(data.request);
            showSuccessMessage('اطلاعات بروزرسانی شد');
        } else {
            throw new Error(data.message || 'خطا در دریافت اطلاعات');
        }
    })
    .catch(error => {
        console.error('خطا در بارگیری مجدد اطلاعات:', error);
        showErrorMessage('خطا در بروزرسانی اطلاعات');
    })
    .finally(() => {
        // حذف loading
        if (document.body.contains(loadingEl)) {
            document.body.removeChild(loadingEl);
        }
    });
}

// بروزرسانی modal با اطلاعات جدید
function updateModalWithNewData(request) {
    try {
        // اطلاعات شخصی
        document.getElementById('modalUserName').textContent = request.name || '';
        document.getElementById('modalUserGrade').textContent = "پایه "+request.grade || '';
        document.getElementById('modalNationalCodeDisplay').textContent = request.nationalcode || '';
        document.getElementById('modalBirthdate').textContent = request.birthdate || '';
        document.getElementById('modalPhone').textContent = request.phone || '';
        document.getElementById('modalTelephone').textContent = request.telephone || 'ندارد';

        // اطلاعات تحصیلی
        document.getElementById('modalGrade').textContent = request.grade || '';
        document.getElementById('modalSchool').textContent = request.school || '';
        document.getElementById('modalPrincipal').textContent = request.principal || '';
        document.getElementById('modalMajor').textContent = request.major_name || 'ندارد';
        document.getElementById('modalLastScore').textContent = request.last_score || '';
        document.getElementById('modalSchoolTelephone').textContent = request.school_telephone || 'ندارد';

        // سطح انگلیسی
        const englishLevel = parseInt(request.english_proficiency) || 0;
        document.getElementById('modalEnglishPercent').textContent = englishLevel + '%';
        document.getElementById('modalEnglishBar').style.width = englishLevel + '%';

        // اطلاعات مسکن
        document.getElementById('modalRental').textContent = request.rental || '';
        document.getElementById('modalAddress').textContent = request.address || '';

        // اطلاعات خانوادگی
        document.getElementById('modalSiblingsCount').textContent = request.siblings_count || '';
        document.getElementById('modalSiblingsRank').textContent = request.siblings_rank || '';
        document.getElementById('modalKnow').textContent = request.know || '';
        document.getElementById('modalCounselingMethod').textContent = request.counseling_method || '';

        if (request.why_counseling_method) {
            document.getElementById('modalWhyCounselingMethod').textContent = request.why_counseling_method;
            document.getElementById('modalWhyCounselingMethodDiv').classList.remove('hidden');
        }

        // اطلاعات والدین
        document.getElementById('modalFatherName').textContent = request.father_name || '';
        document.getElementById('modalFatherPhone').textContent = request.father_phone || '';
        document.getElementById('modalFatherJob').textContent = request.father_job || '';
        document.getElementById('modalFatherIncome').textContent = request.father_income ? request.father_income.toLocaleString('fa-IR') + ' تومان' : '';
        document.getElementById('modalFatherJobAddress').textContent = request.father_job_address || '';

        document.getElementById('modalMotherName').textContent = request.mother_name || '';
        document.getElementById('modalMotherPhone').textContent = request.mother_phone || '';
        document.getElementById('modalMotherJob').textContent = request.mother_job || '';
        document.getElementById('modalMotherIncome').textContent = request.mother_income ? request.mother_income.toLocaleString('fa-IR') + ' تومان' : '';
        document.getElementById('modalMotherJobAddress').textContent = request.mother_job_address || '';

        // سوالات نهایی
        document.getElementById('modalMotivation').textContent = request.motivation || '';
        document.getElementById('modalSpend').textContent = request.spend || '';
        document.getElementById('modalHowAmI').textContent = request.how_am_i || '';
        document.getElementById('modalFuture').textContent = request.future || '';
        document.getElementById('modalFavoriteMajor').textContent = request.favorite_major || '';
        document.getElementById('modalHelpOthers').textContent = request.help_others || '';

        if (request.suggestion) {
            document.getElementById('modalSuggestion').textContent = request.suggestion;
            document.getElementById('modalSuggestionDiv').classList.remove('hidden');
        }

        // بروزرسانی تصاویر با استفاده از URL های آماده شده از سرور
        if (request.imgpath_url) {
            document.getElementById('modalProfileImg').src = "http://127.0.0.1:8000/private/"+ request.imgpath;
    ;
        }

        if (request.gradesheetpath_url) {
            document.getElementById('modalGradeSheetImg').src = request.gradesheetpath_url;
            document.getElementById('modalGradeSheetLink').href = request.gradesheetpath_url;
            document.getElementById('modalGradeSheet').classList.remove('hidden');
        }

        console.log('اطلاعات modal با موفقیت بروزرسانی شد');
    } catch (error) {
        console.error('خطا در بروزرسانی اطلاعات modal:', error);
        showErrorMessage('خطا در نمایش اطلاعات جدید');
    }
}

// نمایش پیام خطا
function showErrorMessage(message) {
    const messageEl = document.createElement('div');
    messageEl.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-[100] transform translate-x-full transition-transform duration-300';
    messageEl.textContent = message;
    document.body.appendChild(messageEl);

    setTimeout(() => messageEl.classList.remove('translate-x-full'), 100);
    setTimeout(() => {
        messageEl.classList.add('translate-x-full');
        setTimeout(() => document.body.removeChild(messageEl), 300);
    }, 3000);
}
</script>
