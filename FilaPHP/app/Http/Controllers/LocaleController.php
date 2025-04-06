<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class LocaleController extends Controller
{
    public function setLocale($lang)
    {
      if(in_array($lang, ['en', 'ru', 'kk'])) {
        App::setLocale($lang);
        Session::put('locale', $lang); 
      }
      return back();
    }
} 