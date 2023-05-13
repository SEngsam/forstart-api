<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
     
        if ((auth()->guard('client-api')->user() &&  auth()->guard('client-api')->user()->is_verified) ||
            (auth()->guard('driver-api')->user() && auth()->guard('driver-api')->user()->is_verified)
        ) {
            return $next($request);
        } else {
            return response()->json("Unauthorized : Your phone is not i verified", 401);
        }
        //         if (Auth::check() && Auth::user()->type == 1) {
        //             return $next($request);
        //         } else {

        //             return response()->json("Unauthorized : Your phone is not verfied", 401);

        //         } 

    }
}
