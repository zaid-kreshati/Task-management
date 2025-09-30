<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLanguage
{
    public function handle($request, Closure $next)
    {
        $locale = Session::get('locale', 'en');  // Default to English if no language is set
        App::setLocale($locale);

        return $next($request);
    }
}

