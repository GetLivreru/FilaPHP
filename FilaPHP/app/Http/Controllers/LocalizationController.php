<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

class LocalizationController extends Controller
{
    /**
     * Переводит текст на текущий язык.
     * Принудительно использует файлы json.
     */
    public static function trans($key)
    {
        $locale = App::getLocale();
        \Log::info("Translate key: {$key} for locale: {$locale}");
    
        $jsonPath = resource_path("lang/{$locale}.json");
    
        if (File::exists($jsonPath)) {
            $translations = json_decode(File::get($jsonPath), true);
            if (isset($translations[$key])) {
                \Log::info("Translation from JSON: " . $translations[$key]);
                return $translations[$key];
            }
        }
    
        if (Lang::has("auth.{$key}", $locale)) {
            \Log::info("Translation from auth.php: " . Lang::get("auth.{$key}", [], $locale));
            return Lang::get("auth.{$key}", [], $locale);
        }
    
        \Log::warning("Translation fallback to key: {$key}");
        return $key;
    }
    
} 