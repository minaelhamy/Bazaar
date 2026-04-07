<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\SystemAddons;
use App\Models\OrderDetails;
use App\Models\CustomStatus;
use App\Models\StoreCategory;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use App\Helpers\helper;

class HomeController extends Controller
{
     public function index(Request $request)
    {
        if($request->vendor_id == ""){
            return response()->json(["status"=>0,"message"=>trans('messages.vendor_id_required')],200);
        }
        $currency = helper::appdata($request->vendor_id)->currency;
        $currency_position = helper::appdata($request->vendor_id)->currency_position;
        $currency_space = helper::appdata($request->vendor_id)->currency_space;
        $admin_address = helper::appdata($request->vendor_id)->address;
        $store_type = helper::appdata($request->vendor_id)->product_type;
        $admindata = User::select('mobile','email')->where('type',1)->first();
        $revenue = Order::where('vendor_id', $request->vendor_id)->where('status_type', 3)->where('payment_status','2')->sum('grand_total');
        $totalorders = Order::where('vendor_id', $request->vendor_id)->count();
        $completedorders = Order::where('status_type', 3)->where('vendor_id',$request->vendor_id)->count();
        $cancelorders = Order::where('vendor_id', $request->vendor_id)->where('status_type',4)->count();
        $orderlist = Order::select("orders.id","orders.order_number","orders.grand_total","orders.order_type","orders.payment_type","orders.status","orders.status_type",DB::raw('DATE_FORMAT(orders.created_at, "%d-%m-%Y") as order_date'),'custom_status.name as status_name','orders.payment_status')->join('custom_status','custom_status.id','orders.status')->where('orders.vendor_id',$request->vendor_id)->whereIn('status_type',[1, 2])->orderByDesc('orders.id')->get();

        return response()->json(['status'=>1,'message'=>trans('messages.success'),'revenue'=>$revenue,'totalorders'=>$totalorders,'completedorders'=>$completedorders,'cancelorders'=>$cancelorders,'data'=>$orderlist,'currency'=>$currency,'currency_position'=>$currency_position,'currency_space'=>$currency_space,'admin_address'=>$admin_address,'admin_mobile' => $admindata->mobile,'admin_email'=> $admindata->email,'store_type' => $store_type,'decimal_separator'=>helper::appdata('')->decimal_separator,'currency_formate'=>helper::appdata('')->currency_formate],200);
    }
    public function getstores()
    {
        $stores = StoreCategory::where('is_deleted', 2)->where('is_available', 1)->orderBy('reorder_id')->get();
        return response()->json(["status" => 1, "message" => trans('messages.success'), 'stores' => $stores], 200);
    }
    public function order_history(Request $request)
    {
        if($request->vendor_id == ""){
            return response()->json(["status"=>0,"message"=>trans('messages.vendor_id_required')],200);
        }

        if(helper::appdata($request->vendor_id)->online_order == 1)
        {
            $orders = Order::select("orders.id","orders.order_number","orders.grand_total","orders.order_type","orders.status","orders.status_type",DB::raw('DATE_FORMAT(orders.created_at, "%d-%m-%Y") as order_date'),'custom_status.name as status_name','orders.payment_status')->join('custom_status','custom_status.id','orders.status')->where('orders.vendor_id',$request->vendor_id)->orderByDesc('orders.id')->get();
        }else{
            $orders = Order::select("orders.id","orders.order_number","orders.grand_total","orders.order_type","orders.status","orders.status_type",DB::raw('DATE_FORMAT(orders.created_at, "%d-%m-%Y") as order_date'))->where('orders.vendor_id',$request->vendor_id)->orderByDesc('orders.id')->get();
        }

        if($request->start_date != ""){
            $orders = $orders->whereBetween('created_at', [$request->startdate, $request->enddate]);
        }

        return response()->json(['status'=>1,'message'=>trans('messages.success'),'data'=>$orders],200);
    }
    public function order_detail(Request $request)
    {
        if($request->vendor_id == ""){
            return response()->json(["status"=>0,"message"=>trans('messages.vendor_id_required')],200);
        }
        if($request->order_number == ""){
            return response()->json(["status"=>0,"message"=>trans('messages.order_number_required')],200);
        }
        $orders = Order::where('orders.vendor_id',$request->vendor_id)->where("orders.order_number",$request->order_number)->orderByDesc('id');
        $orders =  $orders->select("orders.id","orders.order_number","orders.customer_name","orders.customer_email","orders.mobile","orders.grand_total","orders.sub_total","orders.couponcode","orders.discount_amount","orders.tax","orders.tax_name","orders.delivery_charge","orders.payment_id","orders.address","orders.pincode","orders.building","orders.landmark","orders.payment_type","orders.status","orders.status_type",
        "orders.order_type",DB::raw('DATE_FORMAT(orders.created_at, "%d-%m-%Y") as order_date'),DB::raw('DATE_FORMAT(orders.delivery_date, "%d-%m-%Y") as delivery_date'),"orders.delivery_time","orders.order_notes","orders.payment_status","vendor_note",DB::raw("CONCAT('".url(env('ASSETPATHURL').'admin-assets/images/screenshot/')."/', screenshot) AS screenshot"))->first();
        
        $order_detail = OrderDetails::select("id","order_id","item_id","item_name","price",DB::raw("CONCAT('".url(env('ASSETPATHURL').'item/')."/', item_image) AS product_image"),"extras_id","extras_name","extras_price","variants_id","variants_name","variants_price","qty")->where('order_id',$orders->id)->get();
        $custom_status = CustomStatus::where('vendor_id', $request->vendor_id)->where('order_type', $orders->order_type)->where('type', $orders->status_type)->where('id', $orders->status)->first();
        $payment = Payment::where('vendor_id', $request->vendor_id)->where('payment_type',$orders->payment_type)->first();
        
        if ($orders->payment_type == 0) {
            $payment_name = trans('labels.offline');
        } else {
            $payment_name = $payment->payment_name;
        }
        $statuslist = CustomStatus::where('vendor_id', $request->vendor_id)->where('is_available',1)->where('is_deleted',2)->where('order_type',$orders->order_type)->orderBy('reorder_id')->get();
        return response()->json(['status'=>1,'message'=>trans('messages.success'),'data'=>$orders,'ordrdetail'=>$order_detail,'customstatus' => @$custom_status->name,'statuslist'=>$statuslist,'payment_name'=>$payment_name],200);
    }
   
    public function status_change(Request $request)
    {
        if($request->vendor_id == ""){
            return response()->json(["status"=>0,"message"=>trans('messages.vendor_id_required')],200);
        }
        if($request->status_type == ""){
            return response()->json(["status"=>0,"message"=>trans('messages.status_type_required')],200);
        }
        if($request->status == "")
        {
            return response()->json(["status"=>0,"message"=>trans('messages.status_required')],200);
        }
        if($request->order_number == ""){
            return response()->json(["status"=>0,"message"=>trans('messages.order_number_required')],200);
        }
        $order = Order::where('order_number', $request->order_number)->first();
        
        $defaultsatus = CustomStatus::where('vendor_id', $request->vendor_id)->where('order_type',$order->order_type)->where('type', $request->status_type)->where('id',$request->status)->first();

        if (empty($defaultsatus) && $defaultsatus == null) {
            return response()->json(['status'=>0,'message'=>trans('messages.wrong')],200);
        } else {
            $order->status = $defaultsatus->id;
            $order->status_type = $request->status_type;
            $order->update();
            return response()->json(['status'=>1,'message'=>trans('messages.success')],200);
        }
       
    }
    public function systemaddon()
    {
        $addons = SystemAddons::select('unique_identifier', 'activated')->get();
        return response()->json(["status" => 1, "message" => trans('messages.success'), 'addons' =>  $addons], 200);
    }
    
}
