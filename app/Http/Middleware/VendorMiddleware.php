<?php



namespace App\Http\Middleware;



use Closure;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Helpers\helper;

class VendorMiddleware

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

        // Get default language from admin 
     
        helper::language(1);

        if (Auth::user() && (Auth::user()->type==2 || Auth::user()->type == 4)) {
            if (Auth::user()->type == 4) {
                $vendor_id = Auth::user()->vendor_id;
            } else {
                $vendor_id = Auth::user()->id;
            }
            date_default_timezone_set(@helper::appdata($vendor_id)->timezone);
            return $next($request);

        }
        return redirect('admin');

    }

}

