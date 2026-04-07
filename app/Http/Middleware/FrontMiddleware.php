<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\helper;
class FrontMiddleware
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
        $user = helper::currentStoreUser(helper::requestedVendorSlug($request));

        if (empty($user)) {
            abort(404);
        }

        helper::language($user->id);

        if (@helper::otherappdata($user->id)->maintenance_on_off == 1) {
            return response(view('errors.maintenance'));
        }

        $checkplan = helper::checkplan($user->id, '3');
        $v = json_decode(json_encode($checkplan));
        if (@$v->original->status == 2) {
            return response(view('errors.accountdeleted'));
        }
        if ($user->is_available == 2) {
            return response(view('errors.accountdeleted'));
        }
        return $next($request);
    }
}
