<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Session;

class BuyerAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request->session()->put('BUYER_LOGIN',true);
        
        if($request->session()->has('BUYER_LOGIN'))
        {
            return $next($request);
        }
        else
        {
            $request->session()->flash('error','Access Denied');
            return redirect('buyerPortalLogin');
        }


    }
}
