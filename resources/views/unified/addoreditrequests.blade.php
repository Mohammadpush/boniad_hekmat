@extends('layouts.unified')

@section('page-title', isset($request) ? 'ویرایش درخواست' : 'افزودن درخواست جدید')

@section('content')
<main class="flex-1 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">
                    {{ isset($request) ? 'ویرایش درخواست' : 'افزودن درخواست جدید' }}
                </h2>
                <a href="{{ route('unified.myrequests') }}" class="text-blue-600 hover:text-blue-800">
                    بازگشت به لیست
                </a>
            </div>

            <form action="{{ isset($request) ? route('unified.updaterequest', $request->id) : route('unified.storerequest') }}" 
                  method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if(isset($request))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- نام -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">نام</label>
                        <input type="text" name="name" id="name" 
                               value="{{ old('name', $request->name ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- نام خانوادگی -->
                    <div>
                        <label for="female" class="block text-sm font-medium text-gray-700 mb-2">نام خانوادگی</label>
                        <input type="text" name="female" id="female" 
                               value="{{ old('female', $request->female ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('female')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- شماره تماس -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">شماره تماس</label>
                        <input type="text" name="phone" id="phone" 
                               value="{{ old('phone', $request->phone ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- کد ملی -->
                    <div>
                        <label for="nationalcode" class="block text-sm font-medium text-gray-700 mb-2">کد ملی</label>
                        <input type="text" name="nationalcode" id="nationalcode" 
                               value="{{ old('nationalcode', $request->nationalcode ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('nationalcode')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- پایه تحصیلی -->
                    <div>
                        <label for="grade" class="block text-sm font-medium text-gray-700 mb-2">پایه تحصیلی</label>
                        <input type="text" name="grade" id="grade" 
                               value="{{ old('grade', $request->grade ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('grade')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- عکس -->
                    <div>
                        <label for="imgpath" class="block text-sm font-medium text-gray-700 mb-2">عکس</label>
                        <input type="file" name="imgpath" id="imgpath" 
                               accept="image/*"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @if(isset($request) && $request->imgpath)
                            <div class="mt-2">
                                <img src="{{ route('img', ['filename' => $request->imgpath]) }}" 
                                     alt="تصویر فعلی" class="w-20 h-20 object-cover rounded">
                                <p class="text-sm text-gray-600">تصویر فعلی</p>
                            </div>
                        @endif
                        @error('imgpath')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- آدرس -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">آدرس</label>
                    <textarea name="address" id="address" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              required>{{ old('address', $request->address ?? '') }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- توضیحات اضافی -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات اضافی</label>
                    <div id="abouts-container">
                        @if(isset($request) && $request->aboutreqs->count() > 0)
                            @foreach($request->aboutreqs as $index => $about)
                                <div class="flex items-center mb-2 about-item">
                                    <input type="text" name="abouts[]" 
                                           value="{{ old('abouts.' . $index, $about->about) }}"
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="توضیح {{ $index + 1 }}">
                                    <button type="button" class="mr-2 text-red-600 hover:text-red-800 remove-about">
                                        حذف
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <div class="flex items-center mb-2 about-item">
                                <input type="text" name="abouts[]" 
                                       value="{{ old('abouts.0') }}"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="توضیح 1">
                                <button type="button" class="mr-2 text-red-600 hover:text-red-800 remove-about">
                                    حذف
                                </button>
                            </div>
                        @endif
                    </div>
                    <button type="button" id="add-about" class="text-blue-600 hover:text-blue-800 text-sm">
                        + افزودن توضیح جدید
                    </button>
                </div>

                <!-- دکمه‌های عملیات -->
                <div class="flex justify-end space-x-4 space-x-reverse">
                    <a href="{{ route('unified.myrequests') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        انصراف
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        {{ isset($request) ? 'ویرایش' : 'ثبت' }} درخواست
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
    const container = document.getElementById('abouts-container');
    const addButton = document.getElementById('add-about');
    let aboutCount = container.children.length;

    addButton.addEventListener('click', function() {
        aboutCount++;
        const div = document.createElement('div');
        div.className = 'flex items-center mb-2 about-item';
        div.innerHTML = `
            <input type="text" name="abouts[]" 
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="توضیح ${aboutCount}">
            <button type="button" class="mr-2 text-red-600 hover:text-red-800 remove-about">
                حذف
            </button>
        `;
        container.appendChild(div);
    });

    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-about')) {
            if (container.children.length > 1) {
                e.target.parentElement.remove();
            }
        }
    });
});
</script>
@endsection
