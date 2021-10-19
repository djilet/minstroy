<?php

namespace App\Http\Middleware;

use Closure;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {        
        if ($request->method() === 'GET') {

            $session = $request->session();
            $lang = $request->route('lang');
            
            if ($lang && array_key_exists($lang, config('meteor.locales'))) {
                
                //session(['locale' => $lang]);
                app()->setLocale($lang);
                
            } else {
                
                if ($session->has('locale')) {
                    $locale = $session->get('locale');
                    if (array_key_exists($locale, config('meteor.locales'))) {
                        app()->setLocale($locale);
                    }
                }
                
            }
        }
        return $next($request);
    }
}
