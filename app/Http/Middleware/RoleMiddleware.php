<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  ...$roles
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        if (!in_array($userRole, $roles)) {
            return redirect()->route('login')->with('error', 'شما دسترسی لازم را ندارید.');
        }

        // Check if admin needs profile (existing logic)
        if ($userRole === 'admin') {
            $thisadmin = Auth::user();
            if (!$thisadmin->profile && !$request->is('unified/addprofile') && !$request->is('unified/storeprofile')) {
                return redirect()->route('unified.addprofile');
            }
        }

        return $next($request);
    }
}
