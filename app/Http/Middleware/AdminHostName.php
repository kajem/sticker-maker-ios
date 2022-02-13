<?php

namespace App\Http\Middleware;

use Closure;

class AdminHostName
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
        $request->route()->forgetParameter('admin');
        return $next($request);
    }
}
