<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\helper;
use Illuminate\Support\Str;
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
        $requestedSlug = (string) (helper::requestedVendorSlug($request) ?? '');
        $user = helper::currentStoreUser($requestedSlug);

        if (empty($user)) {
            abort(404);
        }

        $aliasSlug = (string) $request->attributes->get('resolved_storefront_alias_slug', '');
        if ($aliasSlug !== '' && helper::isPlatformHost() && $aliasSlug !== $user->slug) {
            $currentPath = trim($request->path(), '/');
            $suffix = trim(Str::after($currentPath, trim($aliasSlug, '/')), '/');
            $redirectUrl = helper::storefront_url($user, $suffix);

            if ($request->getQueryString()) {
                $redirectUrl .= '?' . $request->getQueryString();
            }

            return redirect()->to($redirectUrl, 301);
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
