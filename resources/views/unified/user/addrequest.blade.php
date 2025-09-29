@extends('layouts.unified')

@section('page-title', 'فرم درخواست بورسیه')

@section('page-styles')
    <link href="{{ asset('assets/css/pages/request-form/styles.css') }}" rel="stylesheet">
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
<script src="{{ asset('assets/js/pages/request-form/form-manager.js') }}"></script>
@endsection
