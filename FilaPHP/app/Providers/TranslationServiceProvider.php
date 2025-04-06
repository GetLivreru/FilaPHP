<?php

namespace App\Providers;

use Illuminate\Translation\TranslationServiceProvider as BaseProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class TranslationServiceProvider extends BaseProvider
{
    /**
     * Register the translation line loader.
     *
     * @return void
     */
    protected function registerLoader()
    {
        parent::registerLoader();
    }

    /**
     * Register the translator component.
     *
     * @return void
     */
    protected function registerTranslator()
    {
        parent::registerTranslator();

        // Регистрируем макрос для метода get у translator
        $this->app['translator']->macro('getWithForcedLocale', function ($key, array $replace = [], $locale = null) {
            // Принудительно используем текущую локаль из App
            $forcedLocale = App::getLocale();
            
            // Логируем для отладки
            Log::debug("Using forced locale: {$forcedLocale} for key: {$key}");
            
            return $this->get($key, $replace, $forcedLocale);
        });
    }
} 