<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

if (!function_exists('__')) {
    /**
     * Переопределение стандартной функции перевода
     */
    function __($key, $replace = [], $locale = null)
    {
        $currentLocale = App::getLocale();
        return app('translator')->get($key, $replace, $currentLocale);
    }
} 