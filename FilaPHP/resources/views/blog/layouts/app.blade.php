<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Блог') - FilaPHP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between">
                <div class="flex space-x-7">
                    <div>
                        <a href="{{ route('home') }}" class="flex items-center py-4">
                            <span class="font-semibold text-gray-500 text-lg">FilaPHP Blog</span>
                        </a>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Language Switcher -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-gray-600 hover:text-gray-800">
                            <span class="mr-1">
                                @if(app()->getLocale() == 'en')
                                    🇺🇸
                                @elseif(app()->getLocale() == 'ru')
                                    🇷🇺
                                @elseif(app()->getLocale() == 'kk')
                                    🇰🇿
                                @endif
                            </span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" 
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="/locale/en" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                🇺🇸 English
                            </a>
                            <a href="/locale/ru" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                🇷🇺 Русский
                            </a>
                            <a href="/locale/kk" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                🇰🇿 Қазақша
                            </a>
                        </div>
                    </div>

                    @auth
                        <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:text-blue-700">@lang('Profile')</a>
                        <span class="text-gray-600">{{ auth()->user()->name }}</span>
                        @if(auth()->user()->isAdmin())
                            <a href="/admin" class="text-blue-600 hover:text-blue-700">Админ панель</a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-700 cursor-pointer">Выйти</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700">Войти</a>
                        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700">Регистрация</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-white shadow-lg mt-8">
        <div class="max-w-6xl mx-auto px-4 py-4">
            <p class="text-center text-gray-600">&copy; {{ date('Y') }} FilaPHP Blog. Все права защищены.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html> 