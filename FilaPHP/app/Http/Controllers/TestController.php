<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class TestController extends Controller
{
    public function test()
    {
        $locale = 'kk'; // Попробуем принудительно установить казахский
        
        // Используем все доступные способы
        App::setLocale($locale);
        Config::set('app.locale', $locale);
        Session::put('locale', $locale);
        
        // Вернем информацию о локализации
        return [
            'app_locale' => App::getLocale(),
            'config_locale' => Config::get('app.locale'),
            'session_locale' => Session::get('locale'),
            'env_locale' => env('APP_LOCALE'),
            'translations' => [
                'Login' => __('Login'),
                'Email' => __('Email'),
                'Password' => __('Password')
            ]
        ];
    }
} 