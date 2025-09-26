@extends('layouts.app')
@section('title')
    صفحه اصلی
@endsection
@section('content')
    <div
        class="flex flex-col gap-2 items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
        <a href="{{ route('singup') }}">
            <button class="singbutton w-[115px] h-14 ">
                ثبت نام
                <div class="arrow-wrapper">
                    <div class="arrow"></div>

                </div>
            </button>
        </a>
        <a href="http://127.0.0.1:8000/unified/myrequests">
            <button class="singbutton w-[115px] h-14">
                ورود
                <div class="arrow-wrapper">
                    <div class="arrow"></div>

                </div>
            </button>
        </a>

    </div>
@endsection
