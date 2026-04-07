<?php

namespace App\Http\Controllers\addons\included;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function getorder()
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $todayorders = Order::whereDate('created_at', Carbon::today())->where('is_notification', '=', '1')->where('vendor_id', $vendor_id)->count();
        return json_encode($todayorders);
    }
}
