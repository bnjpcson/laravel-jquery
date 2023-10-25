<?php

namespace App\Http\Middleware;

use Closure;

class isAdminMiddleWare
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
        // dd(auth()->user()->role);
        // if (auth()->check() || !auth()->user()->role) {
        //     abort(443);
        // }
        return $next($request);
    }
}
