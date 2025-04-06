<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class LocaleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->logDebug('LocaleServiceProvider registered');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->logDebug('==== LocaleServiceProvider BOOT START ====');
        $this->logDebug('App::getLocale() before: ' . App::getLocale());
        $this->logDebug('config("app.locale") before: ' . config('app.locale'));
        
        // Устанавливаем локаль для приложения
        $this->setLocale();
        
        $this->logDebug('App::getLocale() after: ' . App::getLocale());
        $this->logDebug('config("app.locale") after: ' . config('app.locale'));
        
        // Проверим, используется ли правильный драйвер перевода
        $translationDriver = $this->app['translator']->getLoader();
        $this->logDebug('Translation driver class: ' . get_class($translationDriver));
        
        // Проверим тестовый перевод
        $this->testTranslation('Login');
        
        $this->logDebug('==== LocaleServiceProvider BOOT END ====');
    }

    /**
     * Устанавливает локаль для приложения
     */
    protected function setLocale(): void
    {
        $this->logDebug('setLocale() method called');
        
        // Определяем доступные локали
        $availableLocales = ['ru', 'en', 'kk'];
        $defaultLocale = config('locale.default_locale', 'ru');
        
        $this->logDebug('Available locales: ' . implode(', ', $availableLocales));
        $this->logDebug('Default locale: ' . $defaultLocale);
        
        // Определяем текущую локаль из различных источников
        $locale = null;
        
        // Проверяем сессию (приоритет)
        if (Session::has('locale')) {
            $localeFromSession = Session::get('locale');
            $this->logDebug('Locale from session: ' . $localeFromSession);
            
            if (in_array($localeFromSession, $availableLocales)) {
                $locale = $localeFromSession;
                $this->logDebug('Using locale from session: ' . $locale);
            }
        }
        
        // Если не нашли в сессии, смотрим в cookie
        if (!$locale && Cookie::has('locale')) {
            $cookieValue = Cookie::get('locale');
            $this->logDebug('Raw cookie value: ' . $cookieValue);
            
            // Извлекаем значение из cookie (может быть в зашифрованном формате)
            if (in_array($cookieValue, $availableLocales)) {
                $locale = $cookieValue;
                $this->logDebug('Using locale from cookie: ' . $locale);
            } else {
                $this->logDebug('Cookie value is not a valid locale');
            }
        }
        
        // Если нигде не нашли, используем локаль по умолчанию
        if (!$locale) {
            $locale = $defaultLocale;
            $this->logDebug('No locale found. Using default: ' . $locale);
        }
        
        // Устанавливаем локаль
        App::setLocale($locale);
        $this->logDebug('App::setLocale() called with: ' . $locale);
        $this->logDebug('App::getLocale() after setting: ' . App::getLocale());
        
        Config::set('app.locale', $locale);
        $this->logDebug('Config::set("app.locale") called with: ' . $locale);
        $this->logDebug('config("app.locale") after setting: ' . config('app.locale'));
        
        // Проверим наличие файла локализации
        $this->checkTranslationFile($locale);
        
        // Обновляем сессию
        Session::put('locale', $locale);
        $this->logDebug('Session::put("locale") called with: ' . $locale);
    }
    
    /**
     * Логирует сообщение в отладочный файл
     */
    private function logDebug(string $message): void
    {
        $logMessage = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
        
        // Логируем в специальный файл и в общие логи Laravel
        $logPath = storage_path('logs/locale_provider_debug.log');
        File::append($logPath, $logMessage);
        
        // Также отправим в общий лог Laravel
        Log::debug($message);
    }
    
    /**
     * Проверяет наличие файла локализации
     */
    private function checkTranslationFile(string $locale): void
    {
        $this->logDebug('Checking translation files for locale: ' . $locale);
        
        // Проверяем в новом формате
        $jsonPath = base_path("lang/{$locale}.json");
        $this->logDebug("Checking translation file: {$jsonPath}");
        
        if (File::exists($jsonPath)) {
            $this->logDebug("Translation file exists: {$jsonPath}");
            
            try {
                $fileContent = File::get($jsonPath);
                $this->logDebug("File content length: " . strlen($fileContent));
                
                $translations = json_decode($fileContent, true, 512, JSON_THROW_ON_ERROR);
                $this->logDebug("JSON successfully parsed, found " . count($translations) . " translations");
            } catch (\Exception $e) {
                $this->logDebug("ERROR reading/parsing file: " . $e->getMessage());
            }
        } else {
            $this->logDebug("WARNING: Translation file does not exist: {$jsonPath}");
            
            // Проверяем в старом формате
            $oldJsonPath = resource_path("lang/{$locale}.json");
            $this->logDebug("Checking old translation file: {$oldJsonPath}");
            
            if (File::exists($oldJsonPath)) {
                $this->logDebug("Old translation file exists: {$oldJsonPath}");
                
                // Копируем файл в новое место
                try {
                    File::copy($oldJsonPath, $jsonPath);
                    $this->logDebug("Successfully copied translation file from {$oldJsonPath} to {$jsonPath}");
                } catch (\Exception $e) {
                    $this->logDebug("ERROR copying file: " . $e->getMessage());
                }
            } else {
                $this->logDebug("WARNING: Old translation file does not exist: {$oldJsonPath}");
            }
        }
    }
    
    /**
     * Тестирует перевод
     */
    private function testTranslation(string $key): void
    {
        $this->logDebug("Testing translation for key: '{$key}'");
        
        $translated = __($key);
        $this->logDebug("Translation result: '{$key}' => '{$translated}'");
        
        if ($key === $translated) {
            $this->logDebug("WARNING: Translation not found for key '{$key}'");
        }
    }
} 