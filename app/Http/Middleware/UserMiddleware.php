<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Helpers\helper;

class UserMiddleware
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

        if (Auth::user() && Auth::user()->type == 3) {
            $user = helper::currentStoreUser(helper::requestedVendorSlug($request));
            date_default_timezone_set(helper::appdata($user->id)->timezone);
            helper::language($user->id);
            return $next($request);
        }
        return redirect(helper::storefront_url(helper::currentStoreUser(helper::requestedVendorSlug($request))));
    }
}
