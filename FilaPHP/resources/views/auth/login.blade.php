@extends('blog.layouts.app')

@section('title')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
 

    <div class="py-1" role="none">
            <a href="/locale/en" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">🇺🇸 English</a>
            <a href="/locale/ru" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">🇷🇺 Русский</a>
            <a href="/locale/kk" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">🇰🇿 Қазақша</a>
        </div>

        <h2 class="text-2xl font-bold mb-6">@lang('Login')</h2>


        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium mb-2">{{ App\Helpers\LocalizationHelper::trans('Email') }}</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-medium mb-2">{{ App\Helpers\LocalizationHelper::trans('Password') }}</label>
                <input type="password" name="password" id="password" required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="ml-2 text-gray-700">{{ App\Helpers\LocalizationHelper::trans('Remember me') }}</span>
                </label>
            </div>

            <div>
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 cursor-pointer">
                    {{ App\Helpers\LocalizationHelper::trans('Log in') }}
                </button>
            </div>
        </form>

        <p class="mt-4 text-center text-gray-600">
            {{ App\Helpers\LocalizationHelper::trans("Don't have an account?") }}
            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700">{{ App\Helpers\LocalizationHelper::trans('Register') }}</a>
        </p>
    </div>
</div>
@endsection
