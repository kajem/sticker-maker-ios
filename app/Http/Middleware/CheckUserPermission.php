<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserPermission
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
        $default_permissions = ['dashboard', 'logout', 'login', 'user.profile', 'user.update'];
        $permissions = !empty(Auth::user()->permissions) ? array_merge($default_permissions, unserialize(Auth::user()->permissions)) : $default_permissions;

        if ( !empty(Auth::user()->id) && (Auth::user()->id > 1 )
                &&
                (empty($permissions) || (!empty($request->route()->action['as']) && !in_array($request->route()->action['as'], $permissions)))
             ) {
                return abort('403');
        }

        return $next($request);
    }
}
