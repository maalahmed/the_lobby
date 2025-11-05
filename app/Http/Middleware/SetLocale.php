<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->getLocale($request);
        
        // Set the application locale
        App::setLocale($locale);
        
        // Optionally set the locale for date/time formatting
        setlocale(LC_TIME, $locale === 'ar' ? 'ar_AE.UTF-8' : 'en_US.UTF-8');
        
        return $next($request);
    }

    /**
     * Determine the locale from various sources.
     *
     * Priority:
     * 1. URL query parameter (?lang=ar)
     * 2. Accept-Language header
     * 3. Authenticated user's preference
     * 4. Application default
     */
    private function getLocale(Request $request): string
    {
        // Check for query parameter
        if ($request->has('lang')) {
            $lang = $request->query('lang');
            if ($this->isSupported($lang)) {
                return $lang;
            }
        }

        // Check Accept-Language header
        $headerLocale = $request->header('Accept-Language');
        if ($headerLocale) {
            // Extract the primary language (e.g., 'ar' from 'ar-AE' or 'ar')
            $primaryLang = strtolower(substr($headerLocale, 0, 2));
            if ($this->isSupported($primaryLang)) {
                return $primaryLang;
            }
        }

        // Check authenticated user's preference
        if ($request->user() && $request->user()->profile) {
            $preferences = $request->user()->profile->preferences;
            if (is_array($preferences) && isset($preferences['language'])) {
                $userLang = $preferences['language'];
                if ($this->isSupported($userLang)) {
                    return $userLang;
                }
            }
        }

        // Fall back to application default
        return config('app.locale', 'en');
    }

    /**
     * Check if the locale is supported.
     */
    private function isSupported(string $locale): bool
    {
        return in_array($locale, ['en', 'ar']);
    }
}
