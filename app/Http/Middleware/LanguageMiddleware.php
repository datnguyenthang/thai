<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;

class LanguageMiddleware
{
    public function handle($request, Closure $next)
    {
        $locale = $request->get('lang');
         if (in_array($locale, LANGUAGES)) {
            App::setLocale($locale);
            session()->put('locale', $locale);
        } else if (session()->has('locale')) {
            App::setLocale(session()->get('locale'));
        } else {
            App::setLocale(config('app.fallback_locale'));
        }
        return $next($request);
    }
}
