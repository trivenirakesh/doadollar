<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetHeadersTokenWeb
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // user_admin token
        $userAdminLoginToken = session('admin_login_token');
        if ($userAdminLoginToken) {
            $request->headers->set('Authorization', 'Bearer ' . $userAdminLoginToken);
        }
        
        return $next($request);
    }
}
