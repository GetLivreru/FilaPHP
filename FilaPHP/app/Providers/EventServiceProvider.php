<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // Обрабатываем запросы после маршрутизации
        Event::listen('Illuminate\Routing\Events\RouteMatched', function ($event) {
            $request = request();
            $this->handleLocale($request);
        });
    }
    
    /**
     * Handle locale for each request
     */
    protected function handleLocale(Request $request): void
    {
        // Получаем параметр локали из URL
        $routeLocale = $request->route('locale');
        
        // Логируем
        Log::debug("EventServiceProvider: Checking locale. Route locale: " . ($routeLocale ?? 'null'));
        Log::debug("EventServiceProvider: Session locale: " . Session::get('locale', 'null'));
        Log::debug("EventServiceProvider: Cookie locale: " . $request->cookie('locale', 'null'));
        Log::debug("EventServiceProvider: App::getLocale(): " . App::getLocale());
        
        // Доступные локали
        $availableLocales = ['en', 'ru', 'kk'];
        
        // Определяем локаль в порядке приоритета
        $locale = null;
        
        // 1. Из параметра маршрута
        if ($routeLocale && in_array($routeLocale, $availableLocales)) {
            $locale = $routeLocale;
            Log::debug("EventServiceProvider: Using locale from route: $locale");
        }
        // 2. Из сессии
        elseif (Session::has('locale') && in_array(Session::get('locale'), $availableLocales)) {
            $locale = Session::get('locale');
            Log::debug("EventServiceProvider: Using locale from session: $locale");
        }
        // 3. Из cookie
        elseif ($request->cookie('locale') && in_array($request->cookie('locale'), $availableLocales)) {
            $locale = $request->cookie('locale');
            Log::debug("EventServiceProvider: Using locale from cookie: $locale");
        }
        // 4. По умолчанию
        else {
            $locale = config('app.locale', 'ru');
            Log::debug("EventServiceProvider: Using default locale: $locale");
        }
        
        // Если локаль определена, применяем ее
        if ($locale) {
            // Устанавливаем локаль
            App::setLocale($locale);
            
            // Обновляем сессию
            Session::put('locale', $locale);
            
            // Очищаем кеш переводов
            $cacheKey = 'translations_' . $locale;
            Cache::forget($cacheKey);
            
            Log::debug("EventServiceProvider: Locale set to: $locale");
            Log::debug("EventServiceProvider: App::getLocale() after set: " . App::getLocale());
        }
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
} 