<!DOCTYPE html>
<html lang="ru">
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
                    @auth
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