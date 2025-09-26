@extends('layouts.app')
@section ("title")
صفحه اصلی
@endsection
@section("content")
<div class="container mx-auto p-4 max-w-md">
    <h1 class="text-2xl font-bold mb-6">ورود به سیستم</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}" class="bg-white p-6 rounded-lg shadow-md w-2/3">
        @csrf
        <input type="hidden" name="_security_token" value="{{ old('_token') }}">
        <div class="mb-4">
            <label for="username" class="block text-gray-700 mb-2">نام کاربری</label>
            <input type="text" id="username" name="username" required
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-6">
            <label for="password" class="block text-gray-700 mb-2">رمز عبور</label>
            <input type="password" id="password" name="password" required
                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
            ورود
        </button>
    </form>
</div>
@endsection
