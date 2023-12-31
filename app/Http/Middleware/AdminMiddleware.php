<?php

namespace App\Http\Middleware;

use App\Enums\UserRoleEnums;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
		$user = Auth::user();
		if (!$user && !$user->hasRole(UserRoleEnums::Manager->value)) {
			abort(401);
		}
        return $next($request);
    }
}
