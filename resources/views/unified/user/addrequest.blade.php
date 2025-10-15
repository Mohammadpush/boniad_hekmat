@extends('layouts.unified')

@section('page-title', 'فرم درخواست بورسیه')

@section('head')
<style>
    .progress-bar {
        background: linear-gradient(to right, #3b82f6 0%, #3b82f6 var(--progress), #e5e7eb var(--progress), #e5e7eb 100%);
        transition: all 0.3s ease;
    }
    .step-circle {
        transition: all 0.3s ease;
    }
    .step-circle.active {
        background: #3b82f6;
        color: white;
        transform: scale(1.1);
    }
    .step-circle.completed {
        background: #10b981;
        color: white;
    }
    .form-section {
        display: none;
        opacity: 0;
        transform: translateX(20px);
        transition: all 0.3s ease;
    }
    .form-section.active {
        display: block;
        opacity: 1;
        transform: translateX(0);
    }
    .field-error {
        border-color: #ef4444 !important;
        background-color: #fef2f2 !important;
    }
    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    /* Custom Slider Styles */
    .slider {
        -webkit-appearance: none;
        appearance: none;
        background: linear-gradient(to left, #3b82f6 0%, #3b82f6 50%, #e5e7eb 50%, #e5e7eb 100%);
        outline: none;
        border-radius: 6px;
        direction: rtl;
    }

    .slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #3b82f6;
        cursor: pointer;
        border: 2px solid #ffffff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        transition: all 0.2s ease;
    }

    .slider::-webkit-slider-thumb:hover {
        transform: scale(1.1);
        background: #2563eb;
    }

    .slider::-moz-range-thumb {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #3b82f6;
        cursor: pointer;
        border: 2px solid #ffffff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        transition: all 0.2s ease;
    }

    .slider::-moz-range-thumb:hover {
        transform: scale(1.1);
        background: #2563eb;
    }

    /* Person icon styles */
    .person-icon svg {
        transition: all 0.2s ease;
    }
        align-items: center;
        justify-content: center;
    }

    .person-icon:hover {
        transform: scale(1.05);
        background: #f3f4f6;
        border-color: #d1d5db;
    }

    .person-icon.selected {
        background: #3b82f6;
        color: white;
        border-color: #2563eb;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .person-icon.selected::after {
        content: '';
        position: absolute;
        bottom: -6px;
        left: 50%;
        transform: translateX(-50%);
        width: 6px;
        height: 6px;
        background: #10b981;
        border-radius: 50%;
        border: 2px solid white;
    }

    .person-number {
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 10px;
        font-weight: bold;
        color: #374151;
        background: white;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #d1d5db;
    }

    .person-icon.selected .person-number {
        color: #3b82f6;
        border-color: #3b82f6;
    }
</style>
@endsection

@section('content')
<main class="flex-1 p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Progress Bar -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <div class="text-right">
                    <h2 class="text-xl font-bold text-gray-800">درخواست بورسیه تحصیلی</h2>
                    <p class="text-gray-600 text-sm mt-1">مرحله <span id="current-step">1</span> از 6</p>
                </div>
                <div class="text-left">
                    <p class="text-sm text-gray-500">پیشرفت: <span id="progress-percent">17</span>%</p>
                </div>
            </div>

            <!-- Progress Steps -->
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center step-indicator" data-step="1">
                    <div class="step-circle active w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold">1</div>
                    <span class="mr-2 text-sm font-medium text-gray-700">اطلاعات شخصی</span>
                </div>
                <div class="flex-1 h-0.5 bg-gray-300 mx-4"></div>

                <div class="flex items-center step-indicator" data-step="2">
                    <div class="step-circle w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-sm font-semibold text-gray-600">2</div>
                    <span class="mr-2 text-sm font-medium text-gray-500">اطلاعات تحصیلی</span>
                </div>
                <div class="flex-1 h-0.5 bg-gray-300 mx-4"></div>

                <div class="flex items-center step-indicator" data-step="3">
                    <div class="step-circle w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-sm font-semibold text-gray-600">3</div>
                    <span class="mr-2 text-sm font-medium text-gray-500">اطلاعات مسکن</span>
                </div>
                <div class="flex-1 h-0.5 bg-gray-300 mx-4"></div>

                <div class="flex items-center step-indicator" data-step="4">
                    <div class="step-circle w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-sm font-semibold text-gray-600">4</div>
                    <span class="mr-2 text-sm font-medium text-gray-500">اطلاعات والدین</span>
                </div>
                <div class="flex-1 h-0.5 bg-gray-300 mx-4"></div>

                <div class="flex items-center step-indicator" data-step="5">
                    <div class="step-circle w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-sm font-semibold text-gray-600">5</div>
                    <span class="mr-2 text-sm font-medium text-gray-500">اطلاعات خانوادگی</span>
                </div>
                <div class="flex-1 h-0.5 bg-gray-300 mx-4"></div>

                <div class="flex items-center step-indicator" data-step="6">
                    <div class="step-circle w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-sm font-semibold text-gray-600">6</div>
                    <span class="mr-2 text-sm font-medium text-gray-500">سوالات نهایی</span>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div id="progress-bar" class="bg-blue-500 h-2 rounded-full transition-all duration-300" style="width: 17%"></div>
            </div>
        </div>

        <!-- Form Container -->
        <div class="bg-white rounded-lg shadow-md">
            <form id="scholarship-form" action="{{ route('unified.storerequestform') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Laravel Validation Errors -->
                @if ($errors->any())
                    <div class="p-8 bg-red-50 border border-red-200 rounded-lg m-8 mb-0">
                        <div class="flex items-center mb-4">
                            <svg class="w-5 h-5 text-red-400 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <h4 class="text-lg font-medium text-red-800">خطاهای اعتبارسنجی</h4>
                        </div>
                        <ul class="text-sm text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="flex items-start">
                                    <span class="text-red-400 ml-2">•</span>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Step 1: اطلاعات شخصی -->
                <div class="form-section active p-8" id="step-1">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">اطلاعات شخصی</h3>
                        <p class="text-gray-600 text-sm">لطفاً اطلاعات شخصی خود را به دقت وارد کنید</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- نام و نام خانوادگی -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">نام و نام خانوادگی *</label>
                            <input type="text" id="name" name="name" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="نام و نام خانوادگی خود را وارد کنید">
                            <div class="error-message" id="name-error"></div>
                        </div>

                        <!-- تاریخ تولد -->
                        <div>
                            <label for="birthdate" class="block text-sm font-medium text-gray-700 mb-2">تاریخ تولد *</label>
                            <input type="text" id="birthdate" name="birthdate" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="۱۴۰۰/۰۱/۰۱">
                            <div class="error-message" id="birthdate-error"></div>
                        </div>

                        <!-- کد ملی -->
                        <div>
                            <label for="nationalcode" class="block text-sm font-medium text-gray-700 mb-2">کد ملی *</label>
                            <input type="text" id="nationalcode" name="nationalcode" required maxlength="10"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent numinput"
                                placeholder="کد ملی 10 رقمی">
                            <div class="error-message" id="nationalcode-error"></div>
                        </div>

                        <!-- موبایل -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">شماره موبایل *</label>
                            <input type="text" id="phone" name="phone" required maxlength="11"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent numinput"
                                placeholder="09xxxxxxxxx">
                            <div class="error-message" id="phone-error"></div>
                        </div>

                        <!-- تلفن ثابت -->
                        <div>
                            <label for="telephone" class="block text-sm font-medium text-gray-700 mb-2">تلفن ثابت</label>
                            <input type="text" id="telephone" name="telephone"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent numinput"
                                placeholder="021xxxxxxxx">
                            <div class="error-message" id="telephone-error"></div>
                        </div>
                    </div>

                    <!-- عکس شخصی -->
                    <div class="mt-6">
                        <label for="imgpath" class="block text-sm font-medium text-gray-700 mb-2">عکس شخصی *</label>
                        <div class="flex items-center space-x-4 space-x-reverse">
                            <input type="file" id="imgpath" name="imgpath" accept="image/*" required
                                class="block w-full text-sm text-gray-500 file:ml-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <div id="image-preview" class="hidden">
                                <img id="preview-img" src="" alt="پیش نمایش" class="w-20 h-20 rounded-lg object-cover border-2 border-gray-300">
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">فرمت‌های مجاز: JPG, PNG - حداکثر 2MB</p>
                        <div class="error-message" id="imgpath-error"></div>
                    </div>
                </div>

                <!-- Step 2: اطلاعات تحصیلی -->
                <div class="form-section p-8" id="step-2">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">اطلاعات تحصیلی</h3>
                        <p class="text-gray-600 text-sm">اطلاعات مربوط به تحصیلات و مدرسه خود را وارد کنید</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- پایه تحصیلی -->
                        <div>
                            <label for="grade" class="block text-sm font-medium text-gray-700 mb-2">پایه تحصیلی *</label>
                            <select id="grade" name="grade" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                            <div class="error-message" id="grade-error"></div>
                        </div>

                        <!-- رشته تحصیلی -->
                        <div id="major-field" style="display: none;">
                            <label for="major_id" class="block text-sm font-medium text-gray-700 mb-2">رشته تحصیلی *</label>
                            <div class="relative">
                                <input type="text" id="major_search"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="جستجو و انتخاب رشته تحصیلی..." autocomplete="off">
                                <input type="hidden" id="major_id" name="major_id" value="">

                                <!-- Dropdown List -->
                                <div id="major-dropdown" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-48 overflow-y-auto hidden">
                                    @if(isset($majors))
                                        @foreach($majors as $major)
                                            <div class="major-option px-4 py-2 hover:bg-blue-50 cursor-pointer border-b border-gray-100"
                                                 data-value="{{ $major->id }}" data-name="{{ $major->name }}">
                                                {{ $major->name }}
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="error-message" id="major_id-error"></div>
                        </div>

                        <!-- نام مدرسه -->
                        <div>
                            <label for="school" class="block text-sm font-medium text-gray-700 mb-2">نام مدرسه *</label>
                            <input type="text" id="school" name="school" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="نام مدرسه خود را وارد کنید">
                            <div class="error-message" id="school-error"></div>
                        </div>

                        <!-- معدل ترم قبل -->
                        <div>
                            <label for="last_score" class="block text-sm font-medium text-gray-700 mb-2">معدل ترم قبل *</label>
                            <input type="number" id="last_score" name="last_score" required min="0" max="20" step="0.01"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="مثال: 18.50">
                            <div class="error-message" id="last_score-error"></div>
                        </div>

                        <!-- نام مدیر مدرسه -->
                        <div>
                            <label for="principal" class="block text-sm font-medium text-gray-700 mb-2">نام مدیر مدرسه *</label>
                            <input type="text" id="principal" name="principal" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="نام مدیر مدرسه">
                            <div class="error-message" id="principal-error"></div>
                        </div>

                        <!-- تلفن مدرسه -->
                        <div>
                            <label for="school_telephone" class="block text-sm font-medium text-gray-700 mb-2">تلفن مدرسه</label>
                            <input type="text" id="school_telephone" name="school_telephone"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent numinput"
                                placeholder="021xxxxxxxx">
                            <div class="error-message" id="school_telephone-error"></div>
                        </div>
                    </div>

                    <!-- کارنامه -->
                    <div class="mt-6">
                        <label for="gradesheetpath" class="block text-sm font-medium text-gray-700 mb-2">کارنامه ترم قبل *</label>
                        <div class="flex items-center space-x-4 space-x-reverse">
                            <input type="file" id="gradesheetpath" name="gradesheetpath" accept="image/*,.pdf" required
                                class="block w-full text-sm text-gray-500 file:ml-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                            <div id="gradesheet-preview" class="hidden">
                                <img id="gradesheet-img" src="" alt="پیش نمایش کارنامه" class="w-20 h-20 rounded-lg object-cover border-2 border-gray-300">
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">فرمت‌های مجاز: PDF, JPG, PNG - حداکثر 5MB</p>
                        <div class="error-message" id="gradesheetpath-error"></div>
                    </div>
                </div>

                <!-- Step 3: اطلاعات مسکن -->
                <div class="form-section p-8" id="step-3">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">اطلاعات مسکن</h3>
                        <p class="text-gray-600 text-sm">اطلاعات مربوط به محل سکونت خود را وارد کنید</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- نوع مسکن -->
                        <div>
                            <label for="rental" class="block text-sm font-medium text-gray-700 mb-2">وضعیت مسکن *</label>
                            <select id="rental" name="rental" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">انتخاب کنید</option>
                                <option value="0">ملکی</option>
                                <option value="1">استیجاری</option>
                            </select>
                            <div class="error-message" id="rental-error"></div>
                        </div>
                    </div>

                    <!-- آدرس -->
                    <div class="mt-6">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">آدرس کامل *</label>
                        <textarea id="address" name="address" required rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="آدرس کامل محل سکونت خود را وارد کنید"></textarea>
                        <div class="error-message" id="address-error"></div>
                    </div>
                </div>

                <!-- Step 4: اطلاعات والدین -->
                <div class="form-section p-8" id="step-4">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">اطلاعات والدین</h3>
                        <p class="text-gray-600 text-sm">اطلاعات مربوط به پدر و مادر خود را وارد کنید</p>
                    </div>

                    <!-- اطلاعات پدر -->
                    <div class="mb-8">
                        <h4 class="text-md font-semibold text-gray-700 mb-4 border-b pb-2">اطلاعات پدر</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- نام پدر -->
                            <div>
                                <label for="father_name" class="block text-sm font-medium text-gray-700 mb-2">نام پدر *</label>
                                <input type="text" id="father_name" name="father_name" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="نام پدر">
                                <div class="error-message" id="father_name-error"></div>
                            </div>

                            <!-- موبایل پدر -->
                            <div>
                                <label for="father_phone" class="block text-sm font-medium text-gray-700 mb-2">موبایل پدر *</label>
                                <input type="text" id="father_phone" name="father_phone" required maxlength="11"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent numinput"
                                    placeholder="09xxxxxxxxx">
                                <div class="error-message" id="father_phone-error"></div>
                            </div>

                            <!-- شغل پدر -->
                            <div>
                                <label for="father_job" class="block text-sm font-medium text-gray-700 mb-2">شغل پدر *</label>
                                <input type="text" id="father_job" name="father_job" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="شغل پدر">
                                <div class="error-message" id="father_job-error"></div>
                            </div>

                            <!-- درآمد پدر -->
                            <div>
                                <label for="father_income" class="block text-sm font-medium text-gray-700 mb-2">درآمد ماهانه پدر (تومان) *</label>
                                <input type="text" id="father_income" name="father_income" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent price-input"
                                    placeholder="مثال: 5,000,000">
                                <div class="error-message" id="father_income-error"></div>
                            </div>

                            <!-- آدرس محل کار پدر -->
                            <div class="md:col-span-2">
                                <label for="father_job_address" class="block text-sm font-medium text-gray-700 mb-2">آدرس محل کار پدر *</label>
                                <textarea id="father_job_address" name="father_job_address" required rows="2"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="آدرس محل کار پدر"></textarea>
                                <div class="error-message" id="father_job_address-error"></div>
                            </div>
                        </div>
                    </div>

                    <!-- اطلاعات مادر -->
                    <div>
                        <h4 class="text-md font-semibold text-gray-700 mb-4 border-b pb-2">اطلاعات مادر</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- نام مادر -->
                            <div>
                                <label for="mother_name" class="block text-sm font-medium text-gray-700 mb-2">نام مادر *</label>
                                <input type="text" id="mother_name" name="mother_name" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="نام مادر">
                                <div class="error-message" id="mother_name-error"></div>
                            </div>

                            <!-- موبایل مادر -->
                            <div>
                                <label for="mother_phone" class="block text-sm font-medium text-gray-700 mb-2">موبایل مادر *</label>
                                <input type="text" id="mother_phone" name="mother_phone" required maxlength="11"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent numinput"
                                    placeholder="09xxxxxxxxx">
                                <div class="error-message" id="mother_phone-error"></div>
                            </div>

                            <!-- شغل مادر -->
                            <div>
                                <label for="mother_job" class="block text-sm font-medium text-gray-700 mb-2">شغل مادر *</label>
                                <input type="text" id="mother_job" name="mother_job" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="شغل مادر">
                                <div class="error-message" id="mother_job-error"></div>
                            </div>

                            <!-- درآمد مادر -->
                            <div>
                                <label for="mother_income" class="block text-sm font-medium text-gray-700 mb-2">درآمد ماهانه مادر (تومان) *</label>
                                <input type="text" id="mother_income" name="mother_income" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent price-input"
                                    placeholder="مثال: 3,000,000">
                                <div class="error-message" id="mother_income-error"></div>
                            </div>

                            <!-- آدرس محل کار مادر -->
                            <div class="md:col-span-2">
                                <label for="mother_job_address" class="block text-sm font-medium text-gray-700 mb-2">آدرس محل کار مادر *</label>
                                <textarea id="mother_job_address" name="mother_job_address" required rows="2"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="آدرس محل کار مادر"></textarea>
                                <div class="error-message" id="mother_job_address-error"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 5: اطلاعات خانوادگی و مهارت‌ها -->
                <div class="form-section p-8" id="step-5">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">اطلاعات خانوادگی و مهارت‌ها</h3>
                        <p class="text-gray-600 text-sm">اطلاعات تکمیلی و مهارت‌های خود را وارد کنید</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- تعداد فرزندان خانواده -->
                        <div>
                            <label for="siblings_count" class="block text-sm font-medium text-gray-700 mb-2">تعداد فرزندان خانواده *</label>
                            <input type="number" id="siblings_count" name="siblings_count" required min="1" max="20"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="مثال: 3">
                            <div class="error-message" id="siblings_count-error"></div>
                        </div>

                        <!-- رتبه متقاضی در خانواده -->
                        <div>
                            <label for="siblings_rank" class="block text-sm font-medium text-gray-700 mb-2">رتبه شما در خانواده *</label>
                            <!-- Hidden input to store selected value -->
                            <input type="hidden" id="siblings_rank" name="siblings_rank" required>

                            <!-- Person icons container -->
                            <div id="siblings-icons-container" class="flex flex-wrap gap-3 p-4 border border-gray-300 rounded-lg min-h-[80px] items-center justify-center">
                                <span class="text-gray-400 text-sm">ابتدا تعداد فرزندان را وارد کنید</span>
                            </div>
                            <div class="error-message" id="siblings_rank-error"></div>
                        </div>

                        <!-- سطح زبان انگلیسی -->
                        <div>
                            <label for="english_proficiency" class="block text-sm font-medium text-gray-700 mb-2">سطح زبان انگلیسی *</label>
                            <div class="px-3 py-4 border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-gray-600">مبتدی</span>
                                    <span id="english-value" class="text-lg font-bold text-blue-600">50</span>
                                    <span class="text-sm text-gray-600">عالی</span>
                                </div>
                                <input type="range"
                                       id="english_proficiency"
                                       name="english_proficiency"
                                       min="0"
                                       max="100"
                                       value="50"
                                       step="1"
                                       required
                                       class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider">
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span>0</span>
                                    <span>25</span>
                                    <span>50</span>
                                    <span>75</span>
                                    <span>100</span>
                                </div>
                            </div>
                            <div class="error-message" id="english_proficiency-error"></div>
                        </div>

                        <!-- نحوه آشنایی با بنیاد -->
                        <div>
                            <label for="know" class="block text-sm font-medium text-gray-700 mb-2">چگونه با بنیاد آشنا شدید؟ *</label>
                            <select id="know" name="know" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">انتخاب کنید</option>
                                <option value="اینترنت">اینترنت</option>
                                <option value="دوستان">دوستان</option>
                                <option value="مدرسه">مدرسه</option>
                                <option value="رسانه">رسانه‌ها</option>
                                <option value="سایر">سایر</option>
                            </select>
                            <div class="error-message" id="know-error"></div>
                        </div>

                        <!-- روش مشاوره -->
                        <div>
                            <label for="counseling_method" class="block text-sm font-medium text-gray-700 mb-2">روش مشاوره مورد نظر *</label>
                            <select id="counseling_method" name="counseling_method" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">انتخاب کنید</option>
                                <option value="مدرسه">مشاوره در مدرسه</option>
                                <option value="خارجی">مشاوره خارجی</option>
                                <option value="سایر">سایر</option>
                                <option value="هیچ">هیچکدام</option>
                            </select>
                            <div class="error-message" id="counseling_method-error"></div>
                        </div>

                        <!-- دلیل انتخاب روش مشاوره -->
                        <div>
                            <label for="why_counseling_method" class="block text-sm font-medium text-gray-700 mb-2">دلیل انتخاب این روش مشاوره</label>
                            <textarea id="why_counseling_method" name="why_counseling_method" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="دلیل انتخاب روش مشاوره را توضیح دهید"></textarea>
                            <div class="error-message" id="why_counseling_method-error"></div>
                        </div>
                    </div>
                </div>

                <!-- Step 6: سوالات نهایی -->
                <div class="form-section p-8" id="step-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">سوالات نهایی</h3>
                        <p class="text-gray-600 text-sm">لطفاً به سوالات زیر پاسخ دهید</p>
                    </div>

                    <div class="space-y-6">
                        <!-- انگیزه درخواست بورسیه -->
                        <div>
                            <label for="motivation" class="block text-sm font-medium text-gray-700 mb-2">انگیزه شما برای درخواست بورسیه چیست؟ *</label>
                            <textarea id="motivation" name="motivation" required rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="حداقل 30 کلمه"></textarea>
                            <p class="text-xs text-gray-500 mt-1">حداقل 30 کلمه</p>
                            <div class="error-message" id="motivation-error"></div>
                        </div>

                        <!-- نحوه مصرف کمک مالی -->
                        <div>
                            <label for="spend" class="block text-sm font-medium text-gray-700 mb-2">در صورت دریافت کمک مالی، چگونه از آن استفاده می‌کنید؟ *</label>
                            <textarea id="spend" name="spend" required rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="نحوه استفاده از کمک مالی را توضیح دهید"></textarea>
                            <div class="error-message" id="spend-error"></div>
                        </div>

                        <!-- معرفی خود -->
                        <div>
                            <label for="how_am_i" class="block text-sm font-medium text-gray-700 mb-2">خودتان را معرفی کنید *</label>
                            <textarea id="how_am_i" name="how_am_i" required rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="درباره خودتان، علایق، نقاط قوت و ضعف بنویسید"></textarea>
                            <div class="error-message" id="how_am_i-error"></div>
                        </div>

                        <!-- رشته مورد علاقه -->
                        <div>
                            <label for="favorite_major" class="block text-sm font-medium text-gray-700 mb-2">رشته مورد علاقه شما برای ادامه تحصیل چیست؟ *</label>
                            <input type="text" id="favorite_major" name="favorite_major" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="مثال: مهندسی کامپیوتر">
                            <div class="error-message" id="favorite_major-error"></div>
                        </div>

                        <!-- برنامه‌های آینده -->
                        <div>
                            <label for="future" class="block text-sm font-medium text-gray-700 mb-2">برنامه‌های آینده شما چیست؟ *</label>
                            <textarea id="future" name="future" required rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="برنامه‌های تحصیلی، شغلی و زندگی خود را بنویسید"></textarea>
                            <div class="error-message" id="future-error"></div>
                        </div>

                        <!-- کمک به دیگران -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">آیا در صورت موفقیت و رسیدن به مدارج عالی، آمادگی کمک به افرادی همچون خود را در آینده دارید؟ *</label>
                            <div class="space-y-3">
                                <label class="flex items-center">
                                    <input type="radio" name="help_others" value="بلی" required
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="mr-2 text-sm text-gray-700">بلی</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="help_others" value="خیر" required
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="mr-2 text-sm text-gray-700">خیر</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="help_others" value="درصورتی که از لحاظ مالی به حد مورد نظرم رسیده باشم، به دیگران کمک میکنم" required
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="mr-2 text-sm text-gray-700">درصورتی که از لحاظ مالی به حد مورد نظرم رسیده باشم، به دیگران کمک میکنم</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="help_others" value="هرکسی باید با تکیه بر توانایی های خود به دنبال موفقیت باشد." required
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="mr-2 text-sm text-gray-700">هرکسی باید با تکیه بر توانایی‌های خود به دنبال موفقیت باشد.</span>
                                </label>
                            </div>
                            <div class="error-message" id="help_others-error"></div>
                        </div>

                        <!-- پیشنهادات -->
                        <div>
                            <label for="suggestion" class="block text-sm font-medium text-gray-700 mb-2">پیشنهادات شما برای بهتر شدن عملکرد بنیاد</label>
                            <textarea id="suggestion" name="suggestion" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="پیشنهادات خود را بنویسید"></textarea>
                            <div class="error-message" id="suggestion-error"></div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="px-8 py-6 border-t border-gray-200 flex justify-between">
                    <button type="button" id="prev-btn" class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        مرحله قبل
                    </button>
                    <button type="button" id="next-btn" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        مرحله بعد
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 6;

    const steps = [17, 34, 50, 67, 84, 100]; // Progress percentages

    // Elements
    const currentStepEl = document.getElementById('current-step');
    const progressPercentEl = document.getElementById('progress-percent');
    const progressBarEl = document.getElementById('progress-bar');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const form = document.getElementById('scholarship-form');

    // Real-time Validation
    const validationRules = {
        // Step 1: Personal Info
        name: { required: true, minLength: 2, pattern: /^[\u0600-\u06FF\s]+$/, message: 'نام باید فارسی و حداقل 2 حرف باشد' },
        birthdate: { required: true, pattern: /^[0-9]{4}\/[0-9]{2}\/[0-9]{2}$/, message: 'تاریخ تولد باید به فرمت ۱۴۰۰/۰۱/۰۱ باشد' },
        nationalcode: { required: true, length: 10, pattern: /^[0-9]{10}$/, message: 'کد ملی باید 10 رقم باشد' },
        phone: { required: true, length: 11, pattern: /^09[0-9]{9}$/, message: 'شماره موبایل باید 11 رقم و با 09 شروع شود' },
        telephone: { required: false, pattern: /^[0-9]{11}$/, message: 'تلفن ثابت باید 11 رقم باشد' },

        // Step 2: Education Info
        grade: { required: true, message: 'انتخاب پایه تحصیلی الزامی است' },
        major_id: { required: false, message: 'انتخاب رشته تحصیلی الزامی است' }, // Dynamic based on grade
        school: { required: true, minLength: 2, message: 'نام مدرسه باید حداقل 2 کاراکتر باشد' },
        last_score: { required: true, pattern: /^[0-9]{1,2}(\.[0-9]{1,2})?$/, message: 'معدل باید عدد صحیح یا اعشاری معتبر باشد' },
        principal: { required: true, minLength: 2, pattern: /^[\u0600-\u06FF\s]+$/, message: 'نام مدیر باید فارسی و حداقل 2 حرف باشد' },
        school_telephone: { required: false, pattern: /^[0-9]{11}$/, message: 'تلفن مدرسه باید 11 رقم باشد' },

        // Step 3: Housing Info
        rental: { required: true, message: 'انتخاب وضعیت مسکن الزامی است' },
        address: { required: true, minLength: 10, message: 'آدرس باید حداقل 10 کاراکتر باشد' },

        // Step 4: Parents Info
        father_name: { required: true, minLength: 2, pattern: /^[\u0600-\u06FF\s]+$/, message: 'نام پدر باید فارسی و حداقل 2 حرف باشد' },
        father_phone: { required: true, length: 11, pattern: /^09[0-9]{9}$/, message: 'موبایل پدر باید 11 رقم و با 09 شروع شود' },
        father_job: { required: true, minLength: 2, message: 'شغل پدر باید حداقل 2 کاراکتر باشد' },
        father_income: { required: true, message: 'درآمد پدر الزامی است' },
        father_job_address: { required: true, minLength: 5, message: 'آدرس محل کار پدر باید حداقل 5 کاراکتر باشد' },
        mother_name: { required: true, minLength: 2, pattern: /^[\u0600-\u06FF\s]+$/, message: 'نام مادر باید فارسی و حداقل 2 حرف باشد' },
        mother_phone: { required: true, length: 11, pattern: /^09[0-9]{9}$/, message: 'موبایل مادر باید 11 رقم و با 09 شروع شود' },
        mother_job: { required: true, minLength: 2, message: 'شغل مادر باید حداقل 2 کاراکتر باشد' },
        mother_income: { required: true, message: 'درآمد مادر الزامی است' },
        mother_job_address: { required: true, minLength: 5, message: 'آدرس محل کار مادر باید حداقل 5 کاراکتر باشد' },

        // Step 5: Family & Skills Info
        siblings_count: { required: true, pattern: /^[1-9][0-9]*$/, message: 'تعداد فرزندان باید عدد مثبت باشد' },
        siblings_rank: { required: true, pattern: /^[1-9][0-9]*$/, message: 'رتبه باید عدد مثبت باشد' },
        english_proficiency: { required: true, message: 'انتخاب سطح انگلیسی الزامی است' },
        know: { required: true, message: 'انتخاب نحوه آشنایی الزامی است' },
        counseling_method: { required: true, message: 'انتخاب روش مشاوره الزامی است' },

        // Step 6: Final Questions
        motivation: { required: true, minLength: 30, message: 'انگیزه باید حداقل 30 کلمه باشد' },
        spend: { required: true, minLength: 10, message: 'نحوه مصرف کمک باید حداقل 10 کاراکتر باشد' },
        how_am_i: { required: true, minLength: 20, message: 'معرفی خود باید حداقل 20 کاراکتر باشد' },
        favorite_major: { required: true, minLength: 2, message: 'رشته مورد علاقه باید حداقل 2 کاراکتر باشد' },
        future: { required: true, minLength: 20, message: 'برنامه‌های آینده باید حداقل 20 کاراکتر باشد' },
        help_others: { required: true, message: 'انتخاب پاسخ الزامی است' }
    };

    // Validate single field
    function validateField(fieldName, value) {
        const rule = validationRules[fieldName];
        if (!rule) return true;

        // Required check
        if (rule.required && (!value || value.trim() === '')) {
            return 'این فیلد الزامی است';
        }

        // Skip other checks if field is empty and not required
        if (!rule.required && (!value || value.trim() === '')) {
            return true;
        }

        // Length check
        if (rule.length && value.length !== rule.length) {
            return rule.message || `باید دقیقاً ${rule.length} کاراکتر باشد`;
        }

        // MinLength check
        if (rule.minLength && value.length < rule.minLength) {
            return rule.message || `باید حداقل ${rule.minLength} کاراکتر باشد`;
        }

        // Pattern check
        if (rule.pattern && !rule.pattern.test(value)) {
            return rule.message || 'فرمت وارد شده صحیح نیست';
        }

        return true;
    }

    // Show field error
    function showFieldError(fieldName, message) {
        const field = document.getElementById(fieldName);
        const errorEl = document.getElementById(fieldName + '-error');

        if (field) {
            field.classList.add('field-error');
        }
        if (errorEl) {
            errorEl.textContent = message;
        }
    }

    // Clear field error
    function clearFieldError(fieldName) {
        const field = document.getElementById(fieldName);
        const errorEl = document.getElementById(fieldName + '-error');

        if (field) {
            field.classList.remove('field-error');
        }
        if (errorEl) {
            errorEl.textContent = '';
        }
    }

    // Real-time validation setup
    Object.keys(validationRules).forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (!field) return;

        field.addEventListener('blur', function() {
            const result = validateField(fieldName, this.value);
            if (result === true) {
                clearFieldError(fieldName);
            } else {
                showFieldError(fieldName, result);
            }
        });

        field.addEventListener('input', function() {
            // Clear error on input
            clearFieldError(fieldName);
        });
    });

    // Auto-save form data to localStorage
    function saveFormData() {
        const formData = {};
        const form = document.getElementById('scholarship-form');
        const formElements = form.querySelectorAll('input, select, textarea');

        formElements.forEach(element => {
            if (element.type !== 'file' && element.name) {
                formData[element.name] = element.value;
            }
        });

        // Save current step
        formData['current_step'] = currentStep;

        localStorage.setItem('scholarship_form_data', JSON.stringify(formData));
    }

    // Load form data from localStorage
    function loadFormData() {
        const savedData = localStorage.getItem('scholarship_form_data');
        if (savedData) {
            const formData = JSON.parse(savedData);

            // Load all form values
            Object.keys(formData).forEach(key => {
                if (key !== 'current_step') {
                    const element = document.querySelector(`[name="${key}"]`);
                    if (element) {
                        element.value = formData[key];

                        // Trigger change event for special fields
                        if (key === 'grade') {
                            element.dispatchEvent(new Event('change'));
                        }

                        // Handle major search field
                        if (key === 'major_id' && formData[key]) {
                            const majorOption = document.querySelector(`.major-option[data-value="${formData[key]}"]`);
                            if (majorOption) {
                                const majorSearch = document.getElementById('major_search');
                                if (majorSearch) {
                                    majorSearch.value = majorOption.getAttribute('data-name');
                                }
                            }
                        }

                        // Handle english proficiency slider
                        if (key === 'english_proficiency' && formData[key]) {
                            const englishSlider = document.getElementById('english_proficiency');
                            if (englishSlider) {
                                englishSlider.value = formData[key];
                                // Trigger the update function
                                if (window.updateEnglishSlider) {
                                    window.updateEnglishSlider();
                                }
                            }
                        }

                        // Handle siblings relationship
                        if (key === 'siblings_count' && formData[key]) {
                            const siblingsCountInput = document.getElementById('siblings_count');
                            if (siblingsCountInput) {
                                siblingsCountInput.value = formData[key];
                                // Trigger the update function to populate siblings rank icons
                                if (window.updateSiblingsRank) {
                                    window.updateSiblingsRank();

                                    // If there's a saved siblings_rank value, select the corresponding icon
                                    if (formData['siblings_rank']) {
                                        setTimeout(() => {
                                            const iconToSelect = document.querySelector(`[data-rank="${formData['siblings_rank']}"]`);
                                            if (iconToSelect) {
                                                iconToSelect.click();
                                            }
                                        }, 100);
                                    }
                                }
                            }
                        }
                    }
                }
            });

            // Restore current step
            if (formData['current_step']) {
                currentStep = parseInt(formData['current_step']);
                updateProgress();
                showStep(currentStep);
            }
        }
    }

    // Clear saved data
    function clearSavedData() {
        localStorage.removeItem('scholarship_form_data');
    }

    // Auto-save on input changes
    document.getElementById('scholarship-form').addEventListener('input', function() {
        saveFormData();
    });

    // Auto-save on select changes
    document.getElementById('scholarship-form').addEventListener('change', function() {
        saveFormData();
    });

    // Clear saved data on successful form submission
    document.getElementById('scholarship-form').addEventListener('submit', function(e) {
        // Check if form is being submitted for real (not just clicking next button)
        if (currentStep === totalSteps) {
            clearSavedData();
        }
    });

    // Numeric inputs
    document.querySelectorAll('.numinput').forEach(input => {
        input.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            this.value = paste.replace(/[^0-9]/g, '');
        });
    });

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

    // Image preview
    document.getElementById('imgpath').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('image-preview').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
            clearFieldError('imgpath');
        }
    });

    // English proficiency slider
    const englishSlider = document.getElementById('english_proficiency');
    const englishValue = document.getElementById('english-value');

    // Make updateEnglishSlider global for loadFormData
    window.updateEnglishSlider = function() {
        if (!englishSlider || !englishValue) return;

        const value = parseInt(englishSlider.value);
        englishValue.textContent = value;

        // Update slider background (RTL - from right to left)
        const percentage = (value / 100) * 100;
        englishSlider.style.background = `linear-gradient(to left, #3b82f6 0%, #3b82f6 ${percentage}%, #e5e7eb ${percentage}%, #e5e7eb 100%)`;

        // Clear any previous errors
        clearFieldError('english_proficiency');
    };

    if (englishSlider && englishValue) {
        // Listen for slider changes
        englishSlider.addEventListener('input', updateEnglishSlider);
        englishSlider.addEventListener('change', updateEnglishSlider);

        // Initialize slider
        updateEnglishSlider();
    }

    // Siblings count and rank relationship
    const siblingsCount = document.getElementById('siblings_count');
    const siblingsRank = document.getElementById('siblings_rank');
    const siblingsIconsContainer = document.getElementById('siblings-icons-container');

    if (siblingsCount && siblingsRank && siblingsIconsContainer) {
        // Function to update siblings rank icons
        window.updateSiblingsRank = function() {
            const count = parseInt(siblingsCount.value);

            // Clear previous icons
            siblingsIconsContainer.innerHTML = '';

            if (!count || count < 1 || count > 20) {
                // If no valid count, show message
                siblingsIconsContainer.innerHTML = '<span class="text-gray-400 text-sm">ابتدا تعداد فرزندان را وارد کنید</span>';
                siblingsRank.value = '';
                return;
            }

            // Generate person icons based on siblings count
            for (let i = 1; i <= count; i++) {
                const iconWrapper = document.createElement('div');
                iconWrapper.className = 'w-14 h-14 p-2 border-2 border-gray-300 rounded-xl cursor-pointer transition-all duration-200 hover:border-blue-400 hover:bg-blue-50 flex items-center justify-center group';
                iconWrapper.dataset.rank = i;

                const icon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                icon.setAttribute('fill', 'none');
                icon.setAttribute('viewBox', '0 0 24 24');
                icon.setAttribute('stroke-width', '1.5');
                icon.setAttribute('stroke', 'currentColor');
                icon.className = 'w-8 h-8 text-gray-500 group-hover:text-blue-600 transition-colors duration-200';

                const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                path.setAttribute('stroke-linecap', 'round');
                path.setAttribute('stroke-linejoin', 'round');
                path.setAttribute('d', 'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z');

                icon.appendChild(path);
                iconWrapper.appendChild(icon);

                // Add rank number below icon
                const rankNumber = document.createElement('div');
                rankNumber.className = 'absolute -bottom-2 left-1/2 transform -translate-x-1/2 bg-gray-200 text-gray-600 rounded-full w-6 h-6 flex items-center justify-center text-xs font-medium transition-all duration-200';
                rankNumber.textContent = i;
                iconWrapper.style.position = 'relative';
                iconWrapper.appendChild(rankNumber);

                // Add click event
                iconWrapper.addEventListener('click', function() {
                    // Reset all icons to outline
                    siblingsIconsContainer.querySelectorAll('[data-rank]').forEach(wrapper => {
                        wrapper.className = 'w-14 h-14 p-2 border-2 border-gray-300 rounded-xl cursor-pointer transition-all duration-200 hover:border-blue-400 hover:bg-blue-50 flex items-center justify-center group';
                        const svg = wrapper.querySelector('svg');
                        svg.setAttribute('fill', 'none');
                        svg.className = 'w-8 h-8 text-gray-500 group-hover:text-blue-600 transition-colors duration-200';
                        const numberDiv = wrapper.querySelector('div');
                        numberDiv.className = 'absolute -bottom-2 left-1/2 transform -translate-x-1/2 bg-gray-200 text-gray-600 rounded-full w-6 h-6 flex items-center justify-center text-xs font-medium transition-all duration-200';
                    });

                    // Set selected icon to solid
                    this.className = 'w-14 h-14 p-2 border-2 border-blue-500 rounded-xl cursor-pointer transition-all duration-200 bg-blue-100 flex items-center justify-center group';
                    const selectedSvg = this.querySelector('svg');
                    selectedSvg.setAttribute('fill', 'currentColor');
                    selectedSvg.className = 'w-8 h-8 text-blue-600 transition-colors duration-200';
                    const selectedNumber = this.querySelector('div');
                    selectedNumber.className = 'absolute -bottom-2 left-1/2 transform -translate-x-1/2 bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-medium transition-all duration-200';

                    // Set hidden input value
                    siblingsRank.value = i;

                    // Clear any previous errors
                    clearFieldError('siblings_rank');

                    // Save form data
                    saveFormData();
                });

                siblingsIconsContainer.appendChild(iconWrapper);
            }

            // Clear any previous errors
            clearFieldError('siblings_rank');
        };

        // Listen for changes in siblings count
        siblingsCount.addEventListener('input', updateSiblingsRank);
        siblingsCount.addEventListener('change', updateSiblingsRank);

        // Initialize on page load if value exists
        if (siblingsCount.value) {
            updateSiblingsRank();
        }
    }

    // Validate current step
    function validateCurrentStep() {
        const stepFields = getStepFields(currentStep);
        let isValid = true;

        // Validate regular fields
        stepFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);

            // Skip validation if field doesn't exist or is not visible
            if (!field) {
                return;
            }

            // Skip validation for file inputs that are in hidden steps
            const fieldStep = field.closest('.form-section');
            if (fieldStep && !fieldStep.classList.contains('active')) {
                return;
            }

            const value = field ? field.value : '';
            const result = validateField(fieldName, value);

            if (result !== true) {
                showFieldError(fieldName, result);
                isValid = false;
            }
        });

        // Validate file inputs separately for current step
        if (currentStep === 1) {
            // Check imgpath
            const imgField = document.getElementById('imgpath');
            if (imgField && (!imgField.files || imgField.files.length === 0)) {
                showFieldError('imgpath', 'انتخاب عکس شخصی الزامی است');
                isValid = false;
            }
        }

        if (currentStep === 2) {
            // Check gradesheetpath
            const gradeField = document.getElementById('gradesheetpath');
            if (gradeField && (!gradeField.files || gradeField.files.length === 0)) {
                showFieldError('gradesheetpath', 'انتخاب کارنامه الزامی است');
                isValid = false;
            }
        }

        // در مرحله آخر، همه فایل‌ها را چک کن
        if (currentStep === totalSteps) {
            // Check imgpath
            const imgField = document.getElementById('imgpath');
            if (imgField && (!imgField.files || imgField.files.length === 0)) {
                showFieldError('imgpath', 'انتخاب عکس شخصی الزامی است');
                isValid = false;
                // هدایت به step 1
                alert('لطفاً به مرحله 1 بروید و عکس شخصی را انتخاب کنید');
            }

            // Check gradesheetpath
            const gradeField = document.getElementById('gradesheetpath');
            if (gradeField && (!gradeField.files || gradeField.files.length === 0)) {
                showFieldError('gradesheetpath', 'انتخاب کارنامه الزامی است');
                isValid = false;
                // هدایت به step 2
                alert('لطفاً به مرحله 2 بروید و کارنامه را انتخاب کنید');
            }
        }

        return isValid;
    }

    // Get fields for specific step
    function getStepFields(step) {
        const stepFieldsMap = {
            1: ['name', 'birthdate', 'nationalcode', 'phone', 'telephone'], // حذف imgpath
            2: ['grade', 'school', 'last_score', 'principal', 'school_telephone'], // حذف gradesheetpath
            3: ['rental', 'address'],
            4: ['father_name', 'father_phone', 'father_job', 'father_income', 'father_job_address',
                'mother_name', 'mother_phone', 'mother_job', 'mother_income', 'mother_job_address'],
            5: ['siblings_count', 'siblings_rank', 'english_proficiency', 'know', 'counseling_method', 'why_counseling_method'],
            6: ['motivation', 'spend', 'how_am_i', 'favorite_major', 'future', 'help_others', 'suggestion']
        };

        // Add major_id dynamically for step 2 if grade >= 10
        if (step === 2) {
            const gradeField = document.getElementById('grade');
            const gradeValue = parseInt(gradeField ? gradeField.value : 0);
            if (gradeValue >= 10) {
                stepFieldsMap[2].push('major_id');
            }
        }

        return stepFieldsMap[step] || [];
    }

    // Update progress
    function updateProgress() {
        currentStepEl.textContent = currentStep;
        progressPercentEl.textContent = steps[currentStep - 1];
        progressBarEl.style.width = steps[currentStep - 1] + '%';

        // Update step indicators
        document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
            const circle = indicator.querySelector('.step-circle');
            const text = indicator.querySelector('span');

            if (index + 1 < currentStep) {
                circle.className = 'step-circle completed w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold';
                text.className = 'mr-2 text-sm font-medium text-green-700';
            } else if (index + 1 === currentStep) {
                circle.className = 'step-circle active w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold';
                text.className = 'mr-2 text-sm font-medium text-gray-700';
            } else {
                circle.className = 'step-circle w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-sm font-semibold text-gray-600';
                text.className = 'mr-2 text-sm font-medium text-gray-500';
            }
        });

        // Update navigation buttons
        prevBtn.disabled = currentStep === 1;
        if (currentStep === totalSteps) {
            nextBtn.textContent = 'ارسال درخواست';
            nextBtn.type = 'submit';
        } else {
            nextBtn.textContent = 'مرحله بعد';
            nextBtn.type = 'button';
        }
    }

    // Show step
    function showStep(step) {
        // Hide all steps
        document.querySelectorAll('.form-section').forEach(section => {
            section.classList.remove('active');
        });

        // Show current step (for now just step 1)
        document.getElementById('step-' + step).classList.add('active');
    }

    // Next button handler
// Next button handler
nextBtn.addEventListener('click', function(e) {
    if (currentStep < totalSteps) {
        // جلوگیری از submit در مراحل غیر آخر
        e.preventDefault();

        if (validateCurrentStep()) {
            currentStep++;
            updateProgress();
            showStep(currentStep);
        }
    } else {
        // مرحله آخر - اجازه submit
        if (validateCurrentStep()) {
            // اجازه submit طبیعی فرم
            return true; // Allow natural form submission
        } else {
            // اگر validation ناموفق بود، جلوگیری از submit
            e.preventDefault();
            return false;
        }
    }
});

    // Previous button handler
    prevBtn.addEventListener('click', function() {
        if (currentStep > 1) {
            currentStep--;
            updateProgress();
            showStep(currentStep);
        }
    });

    // Grade change handler - show/hide major field
    document.getElementById('grade').addEventListener('change', function() {
        const gradeValue = this.value;
        const majorField = document.getElementById('major-field');
        const majorSelect = document.getElementById('major_id');

        // Show major field only for grades 10, 11, 12
        if (gradeValue === 'دهم' || gradeValue === 'یازدهم' || gradeValue === 'دوازدهم') {
            majorField.style.display = 'block';
            majorSelect.setAttribute('required', 'required');
        } else {
            majorField.style.display = 'none';
            majorSelect.removeAttribute('required');
            majorSelect.value = '';
            clearFieldError('major_id');
        }
    });

    // Major search functionality
    const majorSearch = document.getElementById('major_search');
    const majorDropdown = document.getElementById('major-dropdown');
    const majorId = document.getElementById('major_id');
    const majorOptions = document.querySelectorAll('.major-option');

    if (majorSearch && majorDropdown) {
        // Show dropdown on focus
        majorSearch.addEventListener('focus', function() {
            majorDropdown.classList.remove('hidden');
            filterMajors('');
        });

        // Filter majors based on search input
        majorSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            filterMajors(searchTerm);
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#major-field')) {
                majorDropdown.classList.add('hidden');
            }
        });

        // Handle option selection
        majorOptions.forEach(option => {
            option.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                const name = this.getAttribute('data-name');

                majorSearch.value = name;
                majorId.value = value;
                majorDropdown.classList.add('hidden');
                clearFieldError('major_id');
            });
        });

        // Filter function
        function filterMajors(searchTerm) {
            majorOptions.forEach(option => {
                const majorName = option.getAttribute('data-name').toLowerCase();
                if (majorName.includes(searchTerm)) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });
        }

        // Clear selection if input is manually cleared
        majorSearch.addEventListener('keyup', function() {
            if (this.value === '') {
                majorId.value = '';
            }
        });
    }

    // Initialize
    loadFormData(); // Load saved data first
    updateProgress();
});

// Add clear data button for testing
document.addEventListener('DOMContentLoaded', function() {
    // Add a small clear button for testing (can be removed in production)
    const clearButton = document.createElement('button');
    clearButton.type = 'button';
    clearButton.textContent = 'پاک کردن داده‌های ذخیره شده';
    clearButton.className = 'fixed bottom-4 left-4 bg-red-500 text-white px-3 py-2 rounded text-xs z-50';
    clearButton.onclick = function() {
        if (confirm('آیا می‌خواهید تمام داده‌های ذخیره شده را پاک کنید؟')) {
            localStorage.removeItem('scholarship_form_data');
            location.reload();
        }
    };
    document.body.appendChild(clearButton);
});
</script>
@endsection
