<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Cookie;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Принудительно устанавливаем локаль из сессии
        $locale = Session::get('locale');
        
        if ($locale && in_array($locale, ['en', 'ru', 'kk'])) {
            App::setLocale($locale);
        } elseif ($cookie = request()->cookie('locale')) {
            if (in_array($cookie, ['en', 'ru', 'kk'])) {
                App::setLocale($cookie);
                Session::put('locale', $cookie);
            }
        }
    }
}
