<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Helpers\helper;
use App\Models\Variants;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function cart(Request $request)
    {
        if ($request->vendor_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.vendor_id_required')], 200);
        }
        if ($request->user_id != "" || $request->user_id != null) {
            $getcartlist = Cart::where('vendor_id', $request->vendor_id)->where('user_id', $request->user_id)->get();
        } else {
            $getcartlist = Cart::where('vendor_id', $request->vendor_id)->where('session_id', $request->session_id)->get();
        }
        if ($request->user_id != "") {
            $carttax = Cart::select(DB::raw('SUM((qty)*(item_price)) AS sub_total'), DB::raw('SUM((qty)*(tax)) AS total_tax'))->where('user_id', $request->user_id)->where('vendor_id', $request->vendor_id)->first();
        } else {
            $carttax = Cart::select(DB::raw('SUM((qty)*(item_price)) AS sub_total'), DB::raw('SUM((qty)*(tax)) AS total_tax'))->where('session_id', $request->session_id)->where('vendor_id', $request->vendor_id)->first();
        }
        foreach ($getcartlist as $cart) {
            $cart->item_image = helper::image_path($cart->item_image);
            // $cart->product_tax = helper::decimal_formate($cart->product_tax);
        }

        $itemtaxes = [];
        $producttax = 0;
        $tax_name = [];
        $tax_price = [];

        foreach ($getcartlist as $cart) {
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
        }
        $taxArr['tax'] = $tax_name;
        $taxArr['rate'] = $tax_price;
        $totalcarttax = 0;
        foreach ($taxArr['tax'] as $k => $tax) {
            $totalcarttax += (float)$taxArr['rate'][$k];
        }
        return response()->json(['status' => 1, 'message' => trans('messages.success'), 'cartdata' => $getcartlist, 'sub_total' => $carttax->sub_total, 'tax_name' =>  $taxArr['tax'], 'tax_rate' => $taxArr['rate'], 'total_tax' => $totalcarttax], 200);
    }
    public function addtocart(Request $request)
    {
        if ($request->vendor_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.vendor_id_required')], 200);
        }
        if ($request->item_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.item_id_required')], 200);
        }
        if ($request->item_name == "") {
            return response()->json(["status" => 0, "message" => trans('messages.item_name_required')], 200);
        }
        if ($request->item_image == "") {
            return response()->json(["status" => 0, "message" => trans('messages.item_image_required')], 200);
        }

        if ($request->qty == "") {
            return response()->json(["status" => 0, "message" => trans('messages.qty_required')], 200);
        }
        if ($request->item_price == "") {
            return response()->json(["status" => 0, "message" => trans('messages.price_required')], 200);
        }
        if ($request->stock_management == "") {
            return response()->json(["status" => 0, "message" => trans('messages.stock_management_required')], 200);
        }
        try {
            $cart = new Cart;
            $item = Item::where('id', $request->item_id)->first();
            $variation = Variants::where('name', $request->variants_name)->first();
           
            if ($request->variants_name != null && $request->variants_name != "") {
                if ($request->user_id != null && $request->user_id != "") {
                    $cartqty = Cart::select(DB::raw("SUM(qty) as totalqty"))->where('variants_id', $variation->id)->where('user_id', $request->user_id)->first();
                } else {
                    $cartqty = Cart::select(DB::raw("SUM(qty) as totalqty"))->where('variants_id', $variation->id)->where('session_id', $request->session_id)->first();
                }
                if ($cartqty->totalqty != null && $cartqty->totalqty != "") {
                    $qty = $cartqty->totalqty + $request->qty;
                } else {
                    $qty = $request->qty;
                }
                if ($variation->stock_management == 1) {
                  
                    if ($qty > $variation->qty) {
                        return response()->json(['status' => 0, 'message' => trans('labels.out_of_stock_msg') . ' ' . $item->item_name . '(' . $request->variants_name . ')'], 200);
                    }
                    if ($variation->max_order != null && $variation->max_order != "" && $variation->max_order != 0) {
                        if ($qty > $variation->max_order) {
                            if ($cartqty->totalqty == null) {
                                return response()->json(['status' => 0, 'message' => trans('messages.max_qty_message') . $request->max_order], 200);
                            } else {
                                return response()->json(['status' => 0, 'message' => trans('messages.cart_qty_msg') . ' ' . trans('messages.max_qty_message') . $request->max_order, 'qty' => $request->qty - 1], 200);
                            }
                        }
                    }
                }
            } else {
                if ($request->user_id != null && $request->user_id != "") {
                    $cartqty = Cart::select(DB::raw("SUM(qty) as totalqty"))->where('item_id', $request->item_id)->where('user_id', $request->user_id)->first();
                } else {
                    $cartqty = Cart::select(DB::raw("SUM(qty) as totalqty"))->where('item_id', $request->item_id)->where('session_id', $request->session_id)->first();
                }
                if ($cartqty->totalqty != null && $cartqty->totalqty != "") {
                    $qty = $cartqty->totalqty + $request->qty;
                } else {
                    $qty = $request->qty;
                }
                if ($item->stock_management == 1) {
                    if ($item->min_order != null && $item->min_order != ""  && $item->min_order != 0) {
                        if ($qty < $item->min_order) {
                            return response()->json(['status' => 0, 'message' => trans('messages.min_qty_message') . $request->min_order], 200);
                        }
                    }

                    if ($qty > $item->qty) {
                        return response()->json(['status' => 0, 'message' => trans('labels.out_of_stock_msg'). ' ' . $item->item_name], 200);
                    }
                 
                    if ($item->max_order != null && $item->max_order != "" && $item->max_order != 0) {
                        if ($qty > $item->max_order) {
                            if ($cartqty->totalqty == null) {
                                return response()->json(['status' => 0, 'message' => trans('messages.max_qty_message') . $request->max_order], 200);
                            } else {
                                return response()->json(['status' => 0, 'message' => trans('messages.cart_qty_msg') . ' ' . trans('messages.max_qty_message') . $request->max_order, 'qty' => $request->qty - 1], 200);
                            }
                        }
                    }
                }
            }
           
            if ($request->variants_id == null && $request->variants_id == "") {
                $cartprice = $request->item_price;
               
                $cart->variants_price = $request->item_price;
            } else {
                $cartprice = $request->variants_price;
                $cart->variants_price = $request->variants_price;
            }
            $extra_price = explode('|', $request->extras_price);
            if ($request->extras_price != null || $request->extras_price != "") {
                foreach ($extra_price as $price) {
                    $cartprice  = $cartprice +  $price;
                }
            }
            
            $cart->vendor_id = $request->vendor_id;
            $cart->session_id = $request->session_id;
            $cart->user_id = $request->user_id;
            $cart->item_id = $request->item_id;
            $cart->item_name = $request->item_name;
            $cart->item_image = $request->item_image;
            $cart->item_price = $cartprice;
            $cart->tax = $request->tax;
            $cart->extras_name = $request->extras_name;
            $cart->extras_price = $request->extras_price;
            $cart->extras_id = $request->extras_id;
            $cart->price = (float)$cartprice * (float)$request->qty;
            $cart->qty = $request->qty;
            if($request->variants_name !="" && $request->variants_name != null)
            {
                $cart->variants_id = $variation->id;
                $cart->variants_name = $request->variants_name;
            }
            $cart->save();
            if ($request->user_id != null  && $request->user_id != "") {
                $count = Cart::where('user_id', $request->user_id)->where('vendor_id',  $request->vendor_id)->count();
            } else {
                $count = Cart::where('session_id',  $request->session_id)->where('vendor_id',  $request->vendor_id)->count();
            }
            return response()->json(['status' => 1, 'message' => trans('messages.success'), 'cartcount' => $count], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'message' => trans('messages.wrong')], 400);
        }
    }
    public function qtyupdate(Request $request)
    {
        if ($request->cart_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.cart_id_required')], 200);
        }
        if ($request->type == "") {
            return response()->json(["status" => 0, "message" => trans('messages.type_required')], 200);
        }
        if ($request->user_id != "" && $request->user_id != null) {
          
            $checkcart = Cart::where('id', $request->cart_id)->where('user_id', $request->user_id)->first();
            $cartqty = Cart::select(DB::raw("SUM(qty) as totalqty"))->where('variants_id', $checkcart->variants_id)->where('user_id', $request->user_id)->first();
        } else {
            $checkcart = Cart::where('id', $request->cart_id)->where('session_id', $request->session_id)->first();
            $cartqty = Cart::select(DB::raw("SUM(qty) as totalqty"))->where('item_id', $checkcart->item_id)->where('user_id', $request->user_id)->first();
        }
        if (!empty($checkcart)) {
            try {
                if (in_array($request->type, ['minus', 'plus'])) {
                    if ($checkcart->qty == 1 && $request->type == "minus") {
                        $checkcart->delete();
                        return response()->json(['status' => 1, 'message' => trans('messages.success')], 200);
                    } else {
                        if ($request->type == "plus") {
                            $updateqty = (int)$checkcart->qty + 1;
                            $qty = (int)$cartqty->totalqty + 1;
                        }
                        if ($request->type == "minus") {
                            $updateqty = (int)$checkcart->qty - 1;
                            $qty = (int)$cartqty->totalqty - 1;
                        }
                        if ($checkcart->variants_name != "" && $checkcart->variants_name != null) {
                            $variants = Variants::where('name', $checkcart->variants_name)->first();
                        } else {
                            $variants = Item::where('id', $checkcart->item_id)->first();
                        }
                       
                        if ($variants->stock_management == 1) {
                            if ($variants->min_order != null && $variants->min_order != "" && $variants->min_order != 0) {
                                if ($variants->min_order > $qty) {
                                    return response()->json(['status' => 0, 'message' => trans('messages.min_qty_message') . $variants->min_order], 200);
                                }
                            }
                            if ($variants->max_order != null && $variants->max_order != "" && $variants->max_order != 0) {
                                if ($variants->max_order < $qty) {
                                    return response()->json(['status' => 0, 'message' => trans('messages.max_qty_message') . $variants->max_order], 200);
                                }
                            }
                            if ($qty == $variants->qty) {
                                $checkcart->qty = $updateqty;
                                $checkcart->update();
                                return response()->json(['status' => 1, 'message' => trans('messages.qty_update_msg')], 200);
                            }
                            if ($variants->qty < $qty) {
                                if ($checkcart->variants_name != "" && $checkcart->variants_name != null) {
                                    $item_name = Item::select('item_name')->where('id', $checkcart->item_id)->first();
                                    return response()->json(['status' => 0, 'message' => trans('labels.out_of_stock_msg') . ' ' . $item_name->item_name . '(' . $checkcart->variants_name . ')'], 200);
                                } else {
                                    return response()->json(['status' => 0, 'message' => trans('labels.out_of_stock_msg') . ' ' . $variants->item_name], 200);
                                }
                            } else {
                                
                                $checkcart->qty = $updateqty;
                                $checkcart->update();
                                return response()->json(['status' => 1, 'message' => trans('messages.qty_update_msg')], 200);
                            }
                        } else {
                            $checkcart->qty = $updateqty;
                            $checkcart->update();
                            return response()->json(['status' => 1, 'message' => trans('messages.qty_update_msg')], 200);
                        }
                    }
                } else {
                    return response()->json(['status' => 0, 'message' => trans('messages.wrong')], 200);
                }
            } catch (\Throwable $th) {
                return response()->json(['status' => 0, 'message' => trans('messages.wrong')], 200);
            }
        } else {
            return response()->json(['status' => 0, 'message' => trans('messages.nodata_found')], 200);
        }
    }
    public function shippingarea(Request $request)
    {
        if ($request->vendor_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.vendor_id_required')], 200);
        }
        if ($request->user_id != "") {
            $cart = Cart::select(DB::raw('SUM((qty)*(item_price)) AS sub_total'), DB::raw('SUM((qty)*(tax)) AS total_tax'))->where('user_id', $request->user_id)->where('vendor_id', $request->vendor_id)->first();
        } else {
            $cart = Cart::select(DB::raw('SUM((qty)*(item_price)) AS sub_total'), DB::raw('SUM((qty)*(tax)) AS total_tax'))->where('session_id', $request->session_id)->where('vendor_id', $request->vendor_id)->first();
        }
        return response()->json(["status" => 1, "message" => trans('messages.success'), 'sub_total' => $cart->sub_total, 'total_tax' => $cart->total_tax], 200);
    }
    public function deletecartitem(Request $request)
    {
        if ($request->cart_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.cart_required_msg')], 200);
        }
        Cart::where('id', $request->cart_id)->delete();
        if ($request->user_id != "" && $request->user_id != null) {
            $count = Cart::where('user_id', $request->user_id)->where('vendor_id', $request->vendor_id)->count();
        } else {
            $count = Cart::where('session_id', $request->session_id)->where('vendor_id', $request->vendor_id)->count();
        }
        return response()->json(['status' => 1, 'message' => 'Success', 'cart_count' => $count], 200);
    }
}
