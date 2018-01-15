<?php
/**
 * User: Ly
 * Date: 2018/1/9
 * Time: 16:47
 */
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class AdminAuthMiddleware
{
	public function handle ($request, Closure $next, $guard = null)
	{
		if (Auth::guard($guard)->guest()) {
			if ($request->ajax() || $request->wantsJson()) {
				return response('Unauthorized.', 401);
			} else {
				return redirect()->guest('/admin');
			}
		}
		return $next($request);
	}
}
