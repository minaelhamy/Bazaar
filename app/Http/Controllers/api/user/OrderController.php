<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Helpers\helper;
use App\Helpers\whatsapp_helper;
use App\Models\OrderDetails;
use App\Models\User;
use App\Models\Payment;
use Carbon\Carbon;
use App\Models\Timing;
use App\Models\Item;
use App\Models\Cart;
use App\Models\Variants;
use App\Models\CustomStatus;
use App\Models\SystemAddons;
use Stripe\Stripe;
use Stripe\Charge;
use DateTime;
use Config;
use DateInterval;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function placeorder(Request $request)
    {
        if ($request->vendor_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.vendor_id_required')], 200);
        }
        if ($request->payment_type == "") {
            return response()->json(["status" => 0, "message" => trans('messages.payment_type_required')], 200);
        }
        if ($request->customer_name == "") {
            return response()->json(["status" => 0, "message" => trans('messages.customer_name_required')], 200);
        }
        if ($request->customer_email == "") {
            return response()->json(["status" => 0, "message" => trans('messages.customer_email_required')], 200);
        }
        if ($request->customer_mobile == "") {
            return response()->json(["status" => 0, "message" => trans('messages.customer_mobile_required')], 200);
        }
        if ($request->grand_total == "") {
            return response()->json(["status" => 0, "message" => trans('messages.grand_total_required')], 200);
        }

        if ($request->order_type == "1") {
            if ($request->delivery_charge == "") {
                return response()->json(["status" => 0, "message" => trans('messages.delivery_charge_required')], 200);
            }
            if ($request->address == "") {
                return response()->json(["status" => 0, "message" => trans('messages.address_required')], 200);
            }
            if ($request->building == "") {
                return response()->json(["status" => 0, "message" => trans('messages.building_required')], 200);
            }
            if ($request->landmark == "") {
                return response()->json(["status" => 0, "message" => trans('messages.landmark_required')], 200);
            }
            if ($request->postal_code == "") {
                return response()->json(["status" => 0, "message" => trans('messages.postal_code_required')], 200);
            }
            if ($request->delivery_area == "") {
                return response()->json(["status" => 0, "message" => trans('messages.delivery_area_required')], 200);
            }
        }
        if ($request->sub_total == "") {
            return response()->json(["status" => 0, "message" => trans('messages.sub_total_required')], 200);
        }
        if ($request->order_type == "") {
            return response()->json(["status" => 0, "message" => trans('messages.order_type_required')], 200);
        }
        $vendordata = User::where('id', $request->vendor_id)->first();

        $payment_id = $request->payment_id;
        $screenshot = "";
        if ($request->payment_type == "3") {

            $getstripe = Payment::select('environment', 'secret_key', 'currency')->where('payment_type', 3)->where('vendor_id', $request->vendor_id)->first();
            $stripe = new \Stripe\StripeClient($getstripe->secret_key);
            $gettoken = $stripe->tokens->create([
                'card' => [
                    'number' => $request->card_number,
                    'exp_month' => $request->card_exp_month,
                    'exp_year' => $request->card_exp_year,
                    'cvc' => $request->card_cvc,
                ],
            ]);
            Stripe::setApiKey($getstripe->secret_key);

            $charge = Charge::create(
                array(
                    "amount" => $request->grand_total * 100,
                    'currency' => $getstripe->currency,
                    "source" => $gettoken->id,
                    "description" => "Store-Mart",
                )
            );

            if ($request->payment_id == "") {
                $payment_id = $charge['id'];
            } else {
                $payment_id = $request->payment_id;
            }
        }
        if ($request->payment_type == "6") {
            if ($request->hasFile('screenshot')) {
                $filename = 'screenshot-' . uniqid() . "." . $request->file('screenshot')->getClientOriginalExtension();
                $request->file('screenshot')->move(env('ASSETPATHURL') . 'admin-assets/images/screenshot/', $filename);
            }
            $screenshot = $filename;
        }
        $orderresponse = helper::createorder($request->vendor_id, $request->user_id, $request->session_id, $request->payment_type, $payment_id, $request->customer_email, $request->customer_name, $request->customer_mobile, $request->stripeToken, $request->grand_total, $request->delivery_charge, $request->address, $request->building, $request->landmark, $request->postal_code, $request->discount_amount, $request->sub_total, $request->tax, $request->tax_name, $request->delivery_time, $request->delivery_date, $request->delivery_area, $request->couponcode, $request->order_type, $request->notes, $screenshot, $request->table, $request->tablename, $request->buynow);

        if ($orderresponse == "") {
            return response()->json(["status" => 0, "message" => trans('messages.cart_empty')], 200);
        } else {
            $whmessage = "";
            if (@helper::checkaddons('whatsapp_message')) {
                if (@whatsapp_helper::whatsapp_message_config($vendordata->id)->order_created == 1) {
                    if (whatsapp_helper::whatsapp_message_config($vendordata->id)->message_type == 1) {
                        whatsapp_helper::whatsappmessage($orderresponse, $vendordata->id, $vendordata);
                    } else {
                        $whmessage = whatsapp_helper::whatsappmessage($orderresponse, $vendordata->id, $vendordata);
                    }
                }
                $whatsapp_number = whatsapp_helper::whatsapp_message_config($vendordata->id)->whatsapp_number;
            } else {
                $whmessage = "";
                $whatsapp_number = "";
            }
            return response()->json(["status" => 1, "message" => trans('messages.success'), 'order_number' => $orderresponse, 'whmessage' => $whmessage, 'whatsapp_number' => $whatsapp_number], 200);
        }
    }
    public function orderhistory(Request $request)
    {
        if ($request->vendor_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.vendor_id_required')], 200);
        }
        if ($request->user_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.user_id_required')], 200);
        }
        if (helper::appdata($request->vendor_id)->online_order == 1) {
            $orders = Order::select("orders.id", "orders.order_number", "orders.grand_total", "orders.order_type", "orders.status", "orders.status_type", DB::raw('DATE_FORMAT(orders.created_at, "%d-%m-%Y") as order_date'), 'custom_status.name as status_name', 'orders.payment_status', 'orders.payment_type', 'payments.payment_name')->join('custom_status', 'custom_status.id', 'orders.status')->join("payments", function ($join) {
                $join->on("payments.vendor_id", "=", "orders.vendor_id")
                    ->on("payments.payment_type", "=", "orders.payment_type");
            })->where('orders.vendor_id', $request->vendor_id)->orderByDesc('orders.id');
        } else {
            $orders = Order::select("orders.id", "orders.order_number", "orders.grand_total", "orders.order_type", "orders.status", "orders.status_type", DB::raw('DATE_FORMAT(orders.created_at, "%d-%m-%Y") as order_date'), 'payments.payment_name')->join('payments', 'payments.vendor_id', 'orders.vendor_id')->where('orders.vendor_id', $request->vendor_id)->orderByDesc('orders.id');
        }
        if ($request->type != "") {
            if ($request->type == 1) {
                $orders = $orders->whereIn('status_type', [1, 2]);
            }
            if ($request->type == 3) {
                $orders = $orders->where('status_type', 3);
            }
            if ($request->type == 4) {
                $orders = $orders->where('status_type', 4);
            }
        }
        $orders = $orders->get();

        return response()->json(['status' => 1, 'message' => trans('messages.success'), 'data' => $orders], 200);
    }
    public function orderdetails(Request $request)
    {
        if ($request->vendor_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.vendor_id_required')], 200);
        }
        if ($request->order_number == "") {
            return response()->json(["status" => 0, "message" => trans('messages.order_number_required')], 200);
        }
        $orders = Order::select('orders.*', DB::raw('DATE_FORMAT(orders.delivery_date, "%d-%m-%Y") as order_date'), "orders.order_notes")->where('orders.vendor_id', $request->vendor_id)->where("orders.order_number", $request->order_number)->first();
        $shippinginfo = $orders->select("address", "pincode", "building", "landmark", "delivery_area")->first();

        $order_detail = OrderDetails::select('order_details.*', \DB::raw("CONCAT('" . url('/storage/app/public/admin-assets/images/product') . "/', items.download_file) AS download_file"))->where('order_id', $orders->id)->join('items', 'items.id', 'order_details.item_id')->get();
        foreach ($order_detail as $detail) {
            $detail->item_image = helper::image_path($detail->item_image);
        }
        $custom_status = CustomStatus::where('vendor_id', $request->vendor_id)->where('order_type', $orders->order_type)->where('type', $orders->status_type)->where('id', $orders->status)->first();
        $statuslist = CustomStatus::where('vendor_id', $request->vendor_id)->where('is_available', 1)->where('is_deleted', 2)->orderBy('reorder_id')->get();

        $payment = Payment::where('vendor_id', $request->vendor_id)->where('payment_type', $orders->payment_type)->first();
        return response()->json(['status' => 1, 'message' => trans('messages.success'), 'data' => $orders, 'ordrdetail' => $order_detail, "shippinginfo" => $shippinginfo, 'status_name' => @$custom_status->name, 'statuslist' => $statuslist, 'payment_name' => $payment->payment_name], 200);
    }
    public function cancelorder(Request $request)
    {
        if ($request->order_number == "") {
            return response()->json(["status" => 0, "message" => trans('messages.order_number_required')], 200);
        }
        $order = Order::where('order_number', $request->order_number)->first();
        $orderdetail = OrderDetails::where('order_id', $order->id)->get();
        $storeinfo = User::where('id', $order->vendor_id)->first();
        $defaultsatus = CustomStatus::where('vendor_id', $order->vendor_id)->where('order_type', $order->order_type)->where('type', 4)->where('is_available', 1)->where('is_deleted', 2)->first();
        if (helper::appdata($storeinfo->id)->product_type == 1) {
            if (empty($defaultsatus) && $defaultsatus == null) {

                return response()->json(['status' => 0, 'message' => trans('messages.not_cancel_order')], 200);
            } else {
                if ($order->status_type == 1 || $order->status_type == 2) {

                    $order->status_type = 4;
                    if (helper::appdata($storeinfo->id)->product_type == 1) {
                        $order->status = $defaultsatus->id;
                    }
                    $order->update();
                    foreach ($orderdetail as $orders) {
                        if ($orders->variants_id != null && $orders->variants_id != "") {
                            $item = Variants::where('id', $orders->variants_id)->where('item_id', $orders->item_id)->first();
                        } else {
                            $item = Item::where('id', $orders->item_id)->where('vendor_id', $storeinfo->id)->first();
                        }
                        $item->qty = $item->qty + $orders->qty;
                        $item->update();
                    }
                    if (helper::appdata($storeinfo->id)->product_type == 1) {
                        $title = helper::gettype($order->status, $order->status_type, $order->order_type, $order->vendor_id)->name;
                    } else {
                        $title = "{{trans('labels.order_cancelled')}}";
                    }
                    $message_text = 'Order ' . $order->order_number . ' has been cancelled by ' . $order->customer_name;
                    $emaildata = helper::emailconfigration($order->vendor_id);
                    Config::set('mail', $emaildata);
                    // Order::where('order_number', $order_number)->update(['status_type' => "4"]);
                    $checkmail = helper::cancel_order($storeinfo->email, $storeinfo->name, $title, $message_text, $order);
                    $emaildata = User::select('id', 'name', 'slug', 'email', 'mobile', 'token')->where('id', $order->vendor_id)->first();
                    $body = "#" . $order->order_number . " has been cancelled";
                    helper::push_notification($emaildata->token, $title, $body, "order", $order->id);
                    return response()->json(['status' => 1, 'message' => trans('messages.success')], 200);
                } else {
                    return response()->json(['status' => 0, 'message' => trans('messages.not_cancel_order')], 200);
                }
            }
        } else {
            if ($order->payment_status == 1) {
                $order->status_type = 4;
                $order->update();
                if (helper::appdata($storeinfo->id)->product_type == 1) {
                    $title = helper::gettype($order->status, $order->status_type, $order->order_type, $order->vendor_id)->name;
                } else {
                    $title = "{{trans('labels.order_cancelled')}}";
                }
                $message_text = 'Order ' . $order->order_number . ' has been cancelled by ' . $order->customer_name;
                $emaildata = helper::emailconfigration($order->vendor_id);
                Config::set('mail', $emaildata);
                // Order::where('order_number', $order_number)->update(['status_type' => "4"]);
                $checkmail = helper::cancel_order($storeinfo->email, $storeinfo->name, $title, $message_text, $order);
                $emaildata = User::select('id', 'name', 'slug', 'email', 'mobile', 'token')->where('id', $order->vendor_id)->first();
                $body = "#" . $order->order_number . " has been cancelled";
                helper::push_notification($emaildata->token, $title, $body, "order", $order->id);
                return response()->json(['status' => 1, 'message' => trans('messages.success')], 200);
            }
        }
    }

    public function timeslot(Request $request)
    {
        try {
            if ($request->vendor_id == "") {
                return response()->json(["status" => 0, "message" => trans('messages.vendor_id_required')], 200);
            }
            if ($request->date == "") {
                return response()->json(["status" => 0, "message" => trans('messages.date_required')], 200);
            }
            $timezone = helper::appdata($request->vendor_id);
            $slots = [];
            date_default_timezone_set($timezone->timezone);

            if ($request->date != "" || $request->date != null) {
                $day = date('l', strtotime($request->date));
                $minute = "";
                $time = Timing::where('vendor_id', $request->vendor_id)->where('day', $day)->first();
                if ($time->is_always_close == 1) {
                    $slots = [];
                } else {
                    if (helper::appdata($request->vendor_id)->interval_type == 2) {
                        $minute = (float)helper::appdata($request->vendor_id)->interval_time * 60;
                    }
                    if (helper::appdata($request->vendor_id)->interval_type == 1) {
                        $minute = helper::appdata($request->vendor_id)->interval_time;
                    }
                    $duration = $minute;
                    $cleanup = 0;
                    $start = $time->open_time;
                    $break_start = $time->break_start; // break start
                    $break_end   = $time->break_end; // break end
                    $end = $time->close_time;

                    $firsthalf = self::firsthalf($duration, $cleanup, $start, $break_start);
                    $secondhalf = self::secondhalf($duration, $cleanup, $break_end, $end);

                    $period = array_merge($firsthalf, $secondhalf);
                    $currenttime = Carbon::now()->format('h:i a');
                    $current_date = Carbon::now()->format('Y-m-d');

                    foreach ($period as $item) {
                        if ($request->date == $current_date) {
                            $slottime = explode('-', $item);
                            if (strtotime($slottime[0]) <= strtotime($currenttime)) {
                                $status = "";
                            } else {
                                $status = "active";
                            }
                        } else {
                            $status = "active";
                        }
                        $slots[] = array(
                            'slot' =>  $item,
                            'status' => $status,
                        );
                    }
                }
            }
            return response()->json(['status' => 1, 'message' => trans('messages.success'), 'timeslot' => $slots], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 0, 'message' => trans('messages.wrong')], 200);
        }
    }

    function firsthalf($duration, $cleanup, $start, $break_start)
    {
        $start = new DateTime($start);
        $break_start  = new DateTime($break_start);
        $interval = new DateInterval('PT' . $duration . 'M');
        $cleanupinterval = new DateInterval('PT' . $cleanup . 'M');
        $slots = array();

        for ($intStart = $start; $intStart < $break_start; $intStart->add($interval)->add($cleanupinterval)) {
            $endperiod = clone $intStart;
            $endperiod->add($interval);
            if (strtotime($break_start->format('h:i A')) < strtotime($endperiod->format('h:i A')) && strtotime($endperiod->format('h:i A')) < strtotime($break_start->format('h:i A'))) {
                $endperiod = $break_start;
                $slots[] = $intStart->format('h:i A') . ' - ' . $endperiod->format('h:i A');
                $intStart = $break_start;
                $endperiod = $break_start;
                $intStart->sub($interval);
            }
            $slots[] = $intStart->format('h:i A') . ' - ' . $endperiod->format('h:i A');
        }
        return $slots;
    }

    function secondhalf($duration, $cleanup, $break_end, $end)
    {
        $break_end = new DateTime($break_end);
        $end  = new DateTime($end);
        $interval = new DateInterval('PT' . $duration . 'M');
        $cleanupinterval = new DateInterval('PT' . $cleanup . 'M');
        $slots = array();

        for ($intStart = $break_end; $intStart < $end; $intStart->add($interval)->add($cleanupinterval)) {
            $endperiod = clone $intStart;
            $endperiod->add($interval);
            if (strtotime($end->format('h:i A')) < strtotime($endperiod->format('h:i A')) && strtotime($endperiod->format('h:i A')) < strtotime($break_end->format('h:i A'))) {
                $endperiod = $end;
                $slots[] = $intStart->format('h:i A') . ' - ' . $endperiod->format('h:i A');
                $intStart = $end;
                $endperiod = $end;
                $intStart->sub($interval);
            }
            $slots[] = $intStart->format('h:i A') . ' - ' . $endperiod->format('h:i A');
        }
        return $slots;
    }
    public function qtycheckurl(Request $request)
    {
        if ($request->vendor_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.vendor_id_required')], 200);
        }
        try {
            $cartitems = Cart::select('carts.id', 'carts.item_id', 'carts.item_name', 'carts.item_image', 'carts.item_price', 'carts.extras_name', 'carts.extras_price', 'carts.qty', 'carts.price', 'carts.tax', 'carts.variants_id', 'carts.variants_name', 'carts.variants_price', \DB::raw("GROUP_CONCAT(tax.name) as name"))
                ->leftjoin("tax", \DB::raw("FIND_IN_SET(tax.id,carts.tax)"), ">", \DB::raw("'0'"), DB::raw('SUM((qty)*(item_price)) AS sub_total'))
                ->where('carts.vendor_id', $request->vendor_id);
            if ($request->user_id != null && $request->user_id != "") {
                $cartitems->where('carts.user_id', $request->user_id);
                $carttax = Cart::select(DB::raw('SUM((qty)*(item_price)) AS sub_total'))->where('user_id', $request->user_id)->where('vendor_id', $request->vendor_id)->first();
            } else {
                $carttax = Cart::select(DB::raw('SUM((qty)*(item_price)) AS sub_total'))->where('session_id', $request->session_id)->where('vendor_id', $request->vendor_id)->first();
                $cartitems->where('carts.session_id', $request->session_id);
            }
            $cartdata = $cartitems->groupBy("carts.id")->get();

            $qtyexist = 0;
            $itemtaxes = [];
            $producttax = 0;
            $tax_name = [];
            $tax_price = [];
            foreach ($cartdata as $cart) {
                $taxlist =  helper::gettax($cart->tax);
                if (!empty($taxlist)) {
                    foreach ($taxlist as $tax) {
                        if (!empty($tax)) {
                            $producttax = helper::taxRate($tax->tax, $cart->price, $cart->qty, $tax->type);
                            $itemTax['tax_name'] = $tax->name;
                            $itemTax['tax'] = $tax->tax;
                            $itemTax['tax_rate'] = $producttax;
                            $itemtaxes[] = $itemTax;
                            if (!in_array($tax->name, $tax_name)) {
                                $tax_name[] = $tax->name;
                                if ($tax->type == 1) {
                                    $price = $tax->tax * $cart->qty;
                                }
                                if ($tax->type == 2) {
                                    $price = ($tax->tax / 100) * ($cart->price);
                                }
                                $tax_price[] = $price;
                            } else {
                                if ($tax->type == 1) {
                                    $price = $tax->tax * $cart->qty;
                                }
                                if ($tax->type == 2) {
                                    $price = ($tax->tax / 100) * ($cart->price);
                                }
                                $tax_price[array_search($tax->name, $tax_name)] += $price;
                            }
                        }
                    }
                }
                $taxArr['tax'] = $tax_name;
                $taxArr['rate'] = $tax_price;
                $totalcarttax = 0;
                foreach ($taxArr['tax'] as $k => $tax) {
                    $totalcarttax += (float)$taxArr['rate'][$k];
                }
                $item = Item::where('id', $cart->item_id)->first();
                if ($cart->variants_id != "" && $cart->variants_id != null) {
                    $variant = Variants::where('id', $cart->variants_id)->first();
                    if ($variant->stock_management == 1) {
                        if ($cart->qty > $variant->qty) {
                            $qtyexist = 1;
                        }
                    } else {
                        $qtyexist = 0;
                    }
                } else {

                    if ($item->stock_management == 1) {
                        if ($cart->qty > $item->qty) {
                            $qtyexist = 1;
                            // return response()->json(['status' => 0, 'message' => trans($item->item_name . ' qty not enough for order !!')], 200);
                        }
                    } else {
                        $qtyexist = 0;
                    }
                }
            }
            if ($qtyexist == 1) {
                return response()->json(['status' => 0, 'message' => trans('messages.cart_qty_msg') . ' ' . trans('labels.out_of_stock_msg') . ' ' . $item->item_name . ''  . '(' . $variant->name . ')', 'sub_total' => $carttax->sub_total, 'tax_name' =>  $taxArr['tax'], 'tax_rate' => $taxArr['rate'], 'total_tax' => $totalcarttax], 200);
            } else {
                return response()->json(['status' => 1, 'message' => trans('messages.success'), 'sub_total' => $carttax->sub_total, 'tax_name' =>  $taxArr['tax'], 'tax_rate' => $taxArr['rate'], 'total_tax' => $totalcarttax], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => 0, 'message' => $th], 400);
        }
    }
}
