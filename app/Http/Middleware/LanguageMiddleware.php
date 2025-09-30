<?php
namespace App\Http\Middleware;

use Closure;
use App;
use Session;

class LanguageMiddleware
{
    public function handle($request, Closure $next)
    {
        // Check if locale is stored in session and apply it
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }

        return $next($request);
    }
}
