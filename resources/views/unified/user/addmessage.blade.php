@extends('layouts.unified')

@section('head')
    <!-- استایل‌های عمومی -->
    <link rel="stylesheet" href="{{ asset('assets/css/common/forms.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/common/ui-elements.css') }}">

    <!-- استایل‌های مخصوص این صفحه -->
    <link rel="stylesheet" href="{{ asset('assets/css/pages/addmessage/styles.css') }}">
@endsection

@section('page-title', 'افزودن پیام جدید')

@section('content')
<main class="flex-1 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6 max-[600px]:flex-col max-[600px]:gap-3">
                <h2 class="text-2xl font-bold text-gray-800">افزودن پیام جدید</h2>
                <button type="button" onclick="history.back()"
                   class="text-blue-600 hover:text-blue-800 max-[600px]:w-full max-[600px]:text-center max-[600px]:bg-blue-100 max-[600px]:py-2 max-[600px]:rounded">
                    بازگشت به لیست پیام‌ها
                </button>
            </div>

            <form action="{{ route('unified.storemessage',$id) }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="request_id" value="{{ $id }}">

                <div class="grid grid-cols-1 gap-6">
                    <!-- عنوان پیام -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">عنوان پیام</label>
                        <input type="text" name="title" id="title"
                               value="{{ old('title') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- متن پیام -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">متن پیام</label>
                        <textarea name="description" id="description" rows="8"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="متن پیام خود را وارد کنید..."
                                  required>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- مبلغ (اختیاری) -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">مبلغ (تومان) - اختیاری</label>
                        <input type="number" name="price" id="price"
                               value="{{ old('price') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="مثال: 50000">
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- وضعیت -->
                    <div>
                        <label for="story" class="block text-sm font-medium text-gray-700 mb-2">وضعیت</label>
                        <select name="story" id="story"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="pending" {{ old('story') === 'pending' ? 'selected' : '' }}>در انتظار</option>
                            <option value="approved" {{ old('story') === 'approved' ? 'selected' : '' }}>تایید شده</option>
                            <option value="rejected" {{ old('story') === 'rejected' ? 'selected' : '' }}>رد شده</option>
                        </select>
                        @error('story')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    @if(Auth::user()->role === 'master')
                        <!-- نشانگر مدیر -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="ismaster" value="1"
                                       {{ old('ismaster') ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                <span class="mr-2 text-sm text-gray-700">ارسال به عنوان مدیر</span>
                            </label>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end space-x-4 space-x-reverse max-[600px]:flex-col max-[600px]:space-x-0 max-[600px]:space-y-3">
                    <a href="{{ route('unified.message', ['id' => $id]) }}"
                       class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors max-[600px]:text-center">
                        انصراف
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors max-[600px]:w-full">
                        ارسال پیام
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

@section('scripts')
    <!-- اسکریپت مخصوص این صفحه -->
    <script src="{{ asset('assets/js/pages/addmessage/form-manager.js') }}"></script>
@endsection
