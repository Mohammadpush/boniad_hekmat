{{-- استایل‌های انیمیشن --}}
<style>
    /* انیمیشن مودال */
    #requestDetailModal {
        transition: all 0.3s ease;
    }

    #requestDetailModal.show {
        animation: modalFadeIn 0.4s ease-out;
    }

    #requestDetailModal .modal-content {
        animation: modalSlideIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    /* انیمیشن کارت */
    .card-hover {
        transition: all 0.3s ease;
        transform-origin: center;
    }

    .card-hover.animating {
        z-index: 1000;
        position: relative;
    }

    @keyframes modalFadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.8) translateY(20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    @keyframes cardToCenter {
        from {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        to {
            transform: scale(1.1);
        }
    }

    .card-animate-to-center {
        animation: cardToCenter 0.6s ease-out;
    }
</style>

{{-- مودال جزئیات درخواست برای کاربران --}}
<div id="requestDetailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
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
                                            <p id="modalNationalCode" class="text-lg font-mono font-semibold text-gray-800"></p>
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

                {{-- دکمه کنسل --}}
                <div class="flex justify-center mt-8">
                    <button type="button" onclick="document.getElementById('closeRequestDetailModal').click()"
                        class="bg-gradient-to-r from-gray-600 to-gray-700 text-white py-3 px-8 rounded-xl hover:from-gray-700 hover:to-gray-800 transition font-medium shadow-lg">
                        ❌ کنسل
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- اسکریپت مودال --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('requestDetailModal');
    const closeBtn = document.getElementById('closeRequestDetailModal');

    // تابع برای بستن مودال با انیمیشن
    function closeModal() {
        modal.classList.remove('show');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    }

    // بستن مودال با دکمه
    closeBtn.addEventListener('click', closeModal);

    // بستن مودال با کلیک روی پس‌زمینه
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    // بستن مودال با کلید اسکیپ
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
});

// تابع برای باز کردن مودال و پر کردن اطلاعات
function openRequestDetailModal(requestData, cardElement = null) {
    const modal = document.getElementById('requestDetailModal');

    // انیمیشن کارت اگر المان کارت ارسال شده باشد
    if (cardElement) {
        cardElement.classList.add('card-animate-to-center');
        setTimeout(() => {
            cardElement.classList.remove('card-animate-to-center');
        }, 600);
    }

    // پر کردن اطلاعات پروفایل
    document.getElementById('modalProfileImg').src = requestData.imgpath_url;
    document.getElementById('modalProfileImg').alt = requestData.name;
    document.getElementById('modalUserName').textContent = requestData.name;
    document.getElementById('modalUserGrade').textContent = 'پایه ' + requestData.grade;

    // تنظیم وضعیت
    const statusBadge = document.getElementById('modalStatusBadge');
    let statusColor = '';
    let statusText = '';

    switch(requestData.story) {
        case 'check':
            statusColor = 'bg-yellow-500';
            statusText = 'در انتظار';
            break;
        case 'accept':
            statusColor = 'bg-green-500';
            statusText = 'تایید شده';
            break;
        case 'reject':
            statusColor = 'bg-red-500';
            statusText = 'رد شده';
            break;
        case 'epointment':
            statusColor = 'bg-pink-600';
            statusText = 'قرار ملاقات';
            break;
        case 'submit':
            statusColor = 'bg-blue-500';
            statusText = 'ارسال شده';
            break;
        default:
            statusColor = 'bg-gray-500';
            statusText = 'نامشخص';
    }

    statusBadge.className = 'status-badge px-3 py-1 text-white text-xs font-bold rounded-full shadow-lg ' + statusColor;
    statusBadge.textContent = statusText;

    // پر کردن اطلاعات شخصی
    document.getElementById('modalNationalCode').textContent = requestData.nationalcode || '';
    document.getElementById('modalBirthdate').textContent = requestData.birthdate || '';
    document.getElementById('modalPhone').textContent = requestData.phone || '';
    document.getElementById('modalTelephone').textContent = requestData.telephone || 'وارد نشده';

    // پر کردن اطلاعات تحصیلی
    document.getElementById('modalGrade').textContent = requestData.grade || '';
    document.getElementById('modalSchool').textContent = requestData.school || '';
    document.getElementById('modalPrincipal').textContent = requestData.principal || '';
    document.getElementById('modalMajor').textContent = requestData.major_name || 'ندارد';
    document.getElementById('modalLastScore').textContent = requestData.last_score || '';
    document.getElementById('modalSchoolTelephone').textContent = requestData.school_telephone || 'وارد نشده';

    // تنظیم نوار پیشرفت زبان انگلیسی
    const englishPercent = requestData.english_proficiency || 0;
    document.getElementById('modalEnglishBar').style.width = englishPercent + '%';
    document.getElementById('modalEnglishPercent').textContent = englishPercent + '%';

    // نمایش کارنامه اگر وجود دارد
    if (requestData.gradesheetpath) {
        document.getElementById('modalGradeSheet').classList.remove('hidden');
        document.getElementById('modalGradeSheetImg').src = requestData.gradesheetpath_url;
        document.getElementById('modalGradeSheetLink').href = requestData.gradesheetpath_url;
    } else {
        document.getElementById('modalGradeSheet').classList.add('hidden');
    }

    // پر کردن اطلاعات مسکن
    document.getElementById('modalRental').textContent = requestData.rental == '0' ? '🏠 ملکی' : '🏠 استیجاری';
    document.getElementById('modalAddress').textContent = requestData.address || '';

    // پر کردن اطلاعات خانوادگی
    document.getElementById('modalSiblingsCount').textContent = (requestData.siblings_count || '0') + ' نفر';
    document.getElementById('modalSiblingsRank').textContent = 'فرزند ' + (requestData.siblings_rank || '1') + 'ام';
    document.getElementById('modalKnow').textContent = requestData.know || '';
    document.getElementById('modalCounselingMethod').textContent = requestData.counseling_method || '';

    if (requestData.why_counseling_method) {
        document.getElementById('modalWhyCounselingMethodDiv').classList.remove('hidden');
        document.getElementById('modalWhyCounselingMethod').textContent = requestData.why_counseling_method;
    } else {
        document.getElementById('modalWhyCounselingMethodDiv').classList.add('hidden');
    }

    // پر کردن اطلاعات والدین
    document.getElementById('modalFatherName').textContent = requestData.father_name || '';
    document.getElementById('modalFatherPhone').textContent = requestData.father_phone || '';
    document.getElementById('modalFatherJob').textContent = requestData.father_job || '';
    document.getElementById('modalFatherIncome').textContent = requestData.father_income ? (parseInt(requestData.father_income).toLocaleString() + ' تومان') : '';
    document.getElementById('modalFatherJobAddress').textContent = requestData.father_job_address || '';

    document.getElementById('modalMotherName').textContent = requestData.mother_name || '';
    document.getElementById('modalMotherPhone').textContent = requestData.mother_phone || '';
    document.getElementById('modalMotherJob').textContent = requestData.mother_job || '';
    document.getElementById('modalMotherIncome').textContent = requestData.mother_income ? (parseInt(requestData.mother_income).toLocaleString() + ' تومان') : '';
    document.getElementById('modalMotherJobAddress').textContent = requestData.mother_job_address || '';

    // پر کردن سوالات نهایی
    document.getElementById('modalMotivation').textContent = requestData.motivation || '';
    document.getElementById('modalSpend').textContent = requestData.spend || '';
    document.getElementById('modalHowAmI').textContent = requestData.how_am_i || '';
    document.getElementById('modalFuture').textContent = requestData.future || '';
    document.getElementById('modalFavoriteMajor').textContent = requestData.favorite_major || '';
    document.getElementById('modalHelpOthers').textContent = requestData.help_others || '';

    if (requestData.suggestion) {
        document.getElementById('modalSuggestionDiv').classList.remove('hidden');
        document.getElementById('modalSuggestion').textContent = requestData.suggestion;
    } else {
        document.getElementById('modalSuggestionDiv').classList.add('hidden');
    }

    // نمایش مودال با انیمیشن
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // جلوگیری از اسکرول صفحه

    // اضافه کردن کلاس انیمیشن بعد از نمایش
    setTimeout(() => {
        modal.classList.add('show');
    }, 10);
}
</script>
