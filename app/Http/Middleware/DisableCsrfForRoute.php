<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class DisableCsrfForRoute extends Middleware
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
        // Define your route(s) where CSRF protection should be disabled
        if ($request->is('SendEmailToClient')) {
            return $next($request);
        }
    
        return parent::handle($request, $next);
    }
}
