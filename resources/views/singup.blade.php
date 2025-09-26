@extends('layouts.app')
@section("title")
ثبت نام
@endsection
@section("content")
        <main class="flex-1 p-8">

            <div class="bg-white rounded-lg shadow p-6 max-w-xl mx-auto">
<form method="POST" action="{{ route('register.submit') }}">
    @csrf
    <div class="mb-4">
        <label class="block text-gray-700 mb-2" for="name">نام و نام خانوادگی</label>
        <input id="name" name="name" type="text" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 mb-2" for="username">نام کاربری</label>
        <input id="username" name="username" type="text" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 mb-2" for="password">رمز عبور</label>
        <input id="password" name="password" type="password" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 mb-2" for="password_confirmation">تکرار رمز عبور</label>
        <input id="password_confirmation" name="password_confirmation" type="password" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">ثبت نام</button>
</form>
            </div>
        </main>
@if ($errors->any())
    <div>
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif
@endsection
