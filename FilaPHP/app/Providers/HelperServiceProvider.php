<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\LocalizationHelper;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Регистрируем директиву Blade для перевода
        Blade::directive('trans', function ($expression) {
            return "<?php echo App\\Helpers\\LocalizationHelper::trans($expression); ?>";
        });
        
        // Регистрируем функцию для использования в PHP
        if (!function_exists('t')) {
            function t($key, $locale = null) {
                return LocalizationHelper::trans($key, $locale);
            }
        }
    }
} 