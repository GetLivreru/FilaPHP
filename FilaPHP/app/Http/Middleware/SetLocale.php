<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Сразу запишем в лог начало обработки
        $this->logDebug('------- START SetLocale MIDDLEWARE -------');
        $this->logDebug('Request URI: ' . $request->getRequestUri());
        
        // Логируем все входные данные
        $this->logDebug('ENV app.locale: ' . env('APP_LOCALE'));
        $this->logDebug('Config app.locale before: ' . config('app.locale'));
        $this->logDebug('App::getLocale() before: ' . App::getLocale());
        
        // Получаем локаль из разных источников
        $localeFromCookie = $request->cookie('locale');
        $localeFromSession = Session::get('locale');
        
        // Дополнительно проверка route parameter
        $routeLocale = $request->route('locale');
        
        $this->logDebug('Locale from cookie: ' . ($localeFromCookie ?? 'null'));
        $this->logDebug('Locale from session: ' . ($localeFromSession ?? 'null'));
        $this->logDebug('Locale from route parameter: ' . ($routeLocale ?? 'null'));
        
        // Определяем, какую локаль использовать
        $locale = null;
        
        // Доступные локали
        $availableLocales = ['en', 'ru', 'kk'];
        $this->logDebug('Available locales: ' . implode(', ', $availableLocales));
        
        // Приоритет: Session > Cookie > Config
        if ($localeFromSession && in_array($localeFromSession, $availableLocales)) {
            $locale = $localeFromSession;
            $this->logDebug('Using locale from session: ' . $locale);
        } elseif ($localeFromCookie && in_array($localeFromCookie, $availableLocales)) {
            $locale = $localeFromCookie;
            $this->logDebug('Using locale from cookie: ' . $locale);
        } else {
            $locale = config('app.locale', 'ru');
            $this->logDebug('Using locale from config: ' . $locale);
        }
        
        // Проверяем, валидная ли локаль
        if (!in_array($locale, $availableLocales)) {
            $originalLocale = $locale;
            $locale = 'ru';
            $this->logDebug('Invalid locale detected. Changing from: ' . $originalLocale . ' to: ru');
        }
        
        // Устанавливаем локаль в Laravel
        App::setLocale($locale);
        $this->logDebug('App::setLocale() called with: ' . $locale);
        $this->logDebug('App::getLocale() after: ' . App::getLocale());
        
        // Устанавливаем локаль в конфиге
        Config::set('app.locale', $locale);
        $this->logDebug('Config::set("app.locale") called with: ' . $locale);
        $this->logDebug('config("app.locale") after: ' . config('app.locale'));
        
        // Сохраняем в сессии
        Session::put('locale', $locale);
        $this->logDebug('Session::put("locale") called with: ' . $locale);
        $this->logDebug('Session::get("locale") after: ' . Session::get('locale', 'session not set'));
        Session::save(); // Принудительно сохраняем сессию
        $this->logDebug('Session saved');
        
        // Сохраняем в cookie на 1 год
        Cookie::queue('locale', $locale, 60 * 24 * 365);
        $this->logDebug('Cookie::queue("locale") called with: ' . $locale);
        
        // Проверим наличие файлов локализации
        $this->checkTranslationFiles($locale);
        
        // Тестируем перевод
        $this->testTranslation($locale);
        
        $response = $next($request);
        
        // Если это не аяксовый запрос, добавляем cookie к ответу
        if (!$request->ajax() && !$request->wantsJson()) {
            $cookieObject = cookie('locale', $locale, 60 * 24 * 365);
            $response = $response->cookie($cookieObject);
            $this->logDebug('Added cookie to response');
        }
        
        // Добавляем заголовок Content-Language
        $response->headers->set('Content-Language', $locale);
        $this->logDebug('Added Content-Language header: ' . $locale);
        
        $this->logDebug('------- END SetLocale MIDDLEWARE -------');
        
        return $response;
    }
    
    /**
     * Логирует сообщение в отладочный файл
     */
    private function logDebug(string $message): void
    {
        $logMessage = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
        
        // Логируем в специальный файл и в общие логи Laravel
        $logPath = storage_path('logs/locale_debug.log');
        File::append($logPath, $logMessage);
        
        // Также отправим в общий лог Laravel
        Log::debug($message);
    }
    
    /**
     * Проверяет наличие файлов локализации
     */
    private function checkTranslationFiles(string $locale): void
    {
        $jsonPath = base_path("lang/{$locale}.json");
        $this->logDebug("Checking translation file: {$jsonPath}");
        
        if (File::exists($jsonPath)) {
            $this->logDebug("Translation file exists: {$jsonPath}");
            $fileContent = File::get($jsonPath);
            $this->logDebug("File content length: " . strlen($fileContent));
            
            try {
                $translations = json_decode($fileContent, true, 512, JSON_THROW_ON_ERROR);
                $this->logDebug("JSON successfully parsed, found " . count($translations) . " translations");
            } catch (\Exception $e) {
                $this->logDebug("ERROR parsing JSON: " . $e->getMessage());
            }
        } else {
            $this->logDebug("WARNING: Translation file does not exist: {$jsonPath}");
        }
    }
    
    /**
     * Тестирует перевод строки
     */
    private function testTranslation(string $locale): void
    {
        $this->logDebug("Testing translation for locale: {$locale}");
        
        // Сохраним текущую локаль
        $currentLocale = App::getLocale();
        
        // Установим тестовую локаль
        App::setLocale($locale);
        
        // Протестируем перевод
        $originalString = 'Login';
        $translatedString = __($originalString);
        
        $this->logDebug("Original: '{$originalString}', Translated: '{$translatedString}'");
        
        // Восстановим локаль
        App::setLocale($currentLocale);
    }
} 