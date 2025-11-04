<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\BuyerAuthenticate as Middleware;

class BuyerAuthenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('buyerPortalLogin');
        }
    }
}
