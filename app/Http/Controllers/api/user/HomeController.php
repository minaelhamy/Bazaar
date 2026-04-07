<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\SystemAddons;
use App\Models\Category;
use App\Models\Item;
use App\Models\Testimonials;
use App\Models\Banner;
use App\Models\Order;
use App\Models\Variants;
use App\Models\Favorite;
use App\Models\Cart;
use App\Models\Blog;
use App\Models\DineIn;
use App\Models\Payment;
use App\Models\OrderDetails;
use App\Helpers\helper;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        if ($request->vendor_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.vendor_id_required')], 200);
        }
        $userid = "";
        if ($request->user_id != "") {
            $userid = $request->user_id;
        }
        $getcategory = Category::where('vendor_id', $request->vendor_id)->where('is_available', '1')->where('is_deleted', '2')->orderBy('reorder_id')->get();
        if ($request->category_id == "") {
            $getitem = Item::with(['variation', 'category_info', 'extras', 'product_image', 'multi_image'])->select('items.*', DB::raw('(case when favorite.product_id is null then 0 else 1 end) as is_favorite'), DB::raw('ROUND(AVG(testimonials.star),1) as ratings_average'), DB::raw('ROUND(AVG(testimonials.star),1) as ratings_average'))->leftjoin('testimonials', 'testimonials.item_id', 'items.id')->leftJoin('favorite', function ($query) use ($userid) {
                $query->on('favorite.product_id', '=', 'items.id')
                    ->where('favorite.user_id', '=', $userid);
            })->where('items.vendor_id', $request->vendor_id)->where('items.is_available', '1')->groupBy('items.id')->orderBy('items.reorder_id', 'ASC')->get();
        } else {
            $getitem = Item::with(['variation', 'category_info', 'extras', 'product_image', 'multi_image'])->select('items.*', DB::raw('(case when favorite.product_id is null then 0 else 1 end) as is_favorite'), DB::raw('ROUND(AVG(testimonials.star),1) as ratings_average'), DB::raw('ROUND(AVG(testimonials.star),1) as ratings_average'))->leftJoin('favorite', function ($query) use ($userid) {
                $query->on('favorite.product_id', '=', 'items.id')
                    ->where('favorite.user_id', '=', $userid);
            })->leftjoin('testimonials', 'testimonials.item_id', 'items.id')->where('items.vendor_id', $request->vendor_id)->where('items.is_available', '1')->where(DB::Raw("FIND_IN_SET($request->category_id, items.cat_id)"), '>', 0)->groupBy('items.id')->orderBy('items.reorder_id', 'ASC')->get();
        }
        $bannerimage = Banner::with('category_info', 'product_info')->where('vendor_id', $request->vendor_id)->where('section', 1)->orderBy('reorder_id')->get();
        $sliders = Banner::with('category_info', 'product_info')->where('vendor_id', $request->vendor_id)->where('section', 0)->orderBy('reorder_id')->get();
        foreach ($sliders as $image) {
            $image->banner_image = helper::image_path($image->banner_image);
        }
        foreach ($bannerimage as $image) {
            $image->banner_image = helper::image_path($image->banner_image);
        }
        if ($request->user_id != "" && $request->user_id != null) {
            $count = Cart::where('user_id', $request->user_id)->where('vendor_id', $request->vendor_id)->count();
        } else {
            $count = Cart::where('session_id', $request->session_id)->where('vendor_id', $request->vendor_id)->count();
        }
        $testimonials = Testimonials::select('*', \DB::raw("CONCAT('" . url('/storage/app/public/admin-assets/images/testimonials/') . "/', image) AS image_url"))->where('vendor_id', $request->vendor_id)->where('item_id', null)->where('user_id', null)->orderBy('reorder_id')->get();
        $blogs = Blog::select('*', \DB::raw("CONCAT('" . url('/storage/app/public/admin-assets/images/blog/') . "/', image) AS image_url"))->where('vendor_id', $request->vendor_id)->take(4)->orderBy('reorder_id')->get();
        return response()->json(["status" => 1, "message" => trans('messages.success'), 'banners' => $sliders, 'categorydata' => $getcategory, 'items' => $getitem, 'cartcount' => $count, 'currency' => helper::appdata($request->vendor_id)->currency, 'currency_space' => helper::appdata($request->vendor_id)->currency_space, 'currency_position' => helper::appdata($request->vendor_id)->currency_position,'decimal_separator'=>helper::appdata($request->vendor_id)->decimal_separator,'currency_formate'=>helper::appdata($request->vendor_id)->currency_formate,
        'testimonials' => $testimonials, 'blogs' => $blogs, 'sliders' => $bannerimage, 'store_type' => helper::appdata($request->vendor_id)->product_type, 'google_review_link' => helper::appdata($request->vendor_id)->google_review, 'whatsapp_number' => helper::appdata($request->vendor_id)->whatsapp_number, 'contact_number' => helper::appdata($request->vendor_id)->contact], 200);
    }
    public function systemaddon(Request $request)
    {
        if ($request->vendor_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.vendor_id_required')], 200);
        }
        $addons = SystemAddons::select('unique_identifier', 'activated')->get();
        $checkcustomerlogin = helper::appdata($request->vendor_id)->checkout_login_required;
        $primary_color = helper::appdata($request->vendor_id)->primary_color;
        $delivery = in_array('delivery', explode('|', helper::appdata($request->vendor_id)->delivery_type)) ? 1 : 2;
        $pickup = in_array('pickup', explode('|', helper::appdata($request->vendor_id)->delivery_type)) ? 1 : 2;
        $dinein = in_array('table', explode('|', helper::appdata($request->vendor_id)->delivery_type)) ? 1 : 2;
        return response()->json(["status" => 1, "message" => trans('messages.success'), 'addons' =>  $addons, 'checkout_login_required' => $checkcustomerlogin, 'session_id' => session()->getId(), 'primary_color' => $primary_color, 'online_order' => helper::appdata($request->vendor_id)->online_order, 'order_date_time' => helper::appdata($request->vendor_id)->ordertype_date_time, 'delivery' => $delivery, 'pickup' => $pickup, 'dinein' => $dinein], 200);
    }
    public function paymentmethods(Request $request)
    {
        if ($request->vendor_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.vendor_id_required')], 200);
        }
        $getpaymentmethodslist = Payment::where('is_available', 1)->where('vendor_id', $request->vendor_id)->whereNotIn('payment_name', ['wallet', 'banktransfer'])->where('is_activate', 1)->orderBy('reorder_id')->get();
        foreach ($getpaymentmethodslist as $paymentlist) {
            $paymentlist->image = helper::image_path($paymentlist->image);
        }
        return response()->json(['status' => 1, 'message' => trans('messages.success'), "paymentmethods" => $getpaymentmethodslist], 200);
    }
    public function itemdetails(Request $request)
    {
        if ($request->vendor_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.vendor_id_required')], 200);
        }
        if ($request->item_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.item_id_required')], 200);
        }
        if ($request->category_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.category_id_required')], 200);
        }
        $userid = "";
        if ($request->user_id != "") {
            $userid = $request->user_id;
        }

        $getitem = Item::with(['variation', 'category_info', 'extras', 'product_image', 'multi_image'])->select('items.*', DB::raw('(case when favorite.product_id is null then 0 else 1 end) as is_favorite'), DB::raw('ROUND(AVG(testimonials.star),1) as ratings_average'), DB::raw('ROUND(AVG(testimonials.star),1) as ratings_average'), \DB::raw("CONCAT('" . url('/storage/app/public/admin-assets/images/product/') . "/', items.attchment_file) AS attchment_file"))->leftjoin('testimonials', 'testimonials.item_id', 'items.id')->leftJoin('favorite', function ($query) use ($userid) {
            $query->on('favorite.product_id', '=', 'items.id')
                ->where('favorite.user_id', '=', $userid);
        })->where('items.vendor_id', $request->vendor_id)->where('items.is_available', '1')->where('items.id', $request->item_id)->first();
        $getitem->variants_json = json_decode($getitem->variants_json, true);
        $getrelateproducts = Item::select('items.*', DB::raw('(case when favorite.product_id is null then 0 else 1 end) as is_favorite'), DB::raw('ROUND(AVG(testimonials.star),1) as ratings_average'), DB::raw('ROUND(AVG(testimonials.star),1) as ratings_average'))->with(['variation', 'category_info', 'extras', 'product_image', 'multi_image'])->leftjoin('testimonials', 'testimonials.item_id', 'items.id')->leftJoin('favorite', function ($query) use ($userid) {
            $query->on('favorite.product_id', '=', 'items.id')
                ->where('favorite.user_id', '=', $userid);
        })->where('items.vendor_id', $request->vendor_id)->where('items.is_available', '1')->where('items.id', '!=', $request->item_id)->whereIn('items.cat_id', explode('|', $request->category_id))->orderBy('items.reorder_id')->get();

        $review = Testimonials::select('*', DB::raw("CONCAT('" . url(env('ASSETPATHURL') . 'admin-assets/images/testimonials') . "/', image) AS image"))->where('vendor_id', $request->vendor_id)->where('item_id', $request->id)->get();
        $averagerating = Testimonials::where('item_id', $request->item_id)->where('vendor_id', $request->vendor_id)->avg('star');
        $totalreview = Testimonials::where('item_id', $request->item_id)->where('vendor_id', $request->vendor_id)->count();

        if ($totalreview != 0) {
            $avgfive = (Testimonials::where('item_id', $request->item_id)->where('vendor_id', $request->vendor_id)->where('star', 5)->count()) / $totalreview * 100;
            $avgfour = (Testimonials::where('item_id', $request->item_id)->where('vendor_id', $request->vendor_id)->where('star', 4)->count()) / $totalreview * 100;
            $avgthree = (Testimonials::where('item_id', $request->item_id)->where('vendor_id', $request->vendor_id)->where('star', 3)->count()) / $totalreview * 100;
            $avgtwo = (Testimonials::where('item_id', $request->item_id)->where('vendor_id', $request->vendor_id)->where('star', 2)->count()) / $totalreview * 100;
            $avgone = (Testimonials::where('item_id', $request->item_id)->where('vendor_id', $request->vendor_id)->where('star', 1)->count()) / $totalreview * 100;
        } else {
            $avgfive = 0;
            $avgfour = 0;
            $avgthree = 0;
            $avgtwo = 0;
            $avgone = 0;
        }
        return response()->json(['status' => 1, 'message' => trans('messages.success'), "data" => $getitem, "relatedproducts" => $getrelateproducts, 'user_review' => $review, 'avg_rating' => number_format($averagerating, 1), 'total_reviews' => $totalreview, 'avgfive' => number_format($avgfive, 1), 'avgfour' => number_format($avgfour, 1), 'avgthree' => number_format($avgthree, 1), 'avgtwo' => number_format($avgtwo, 1), 'avgone' => number_format($avgone, 1)], 200);
    }
    public function search(Request $request)
    {
        if ($request->vendor_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.vendor_id_required')], 200);
        }
        $getitem = Item::select('items.*', 'items.image as image', 'items.image as image_name')->with(['variation', 'extras', 'category_info'])->where('vendor_id', $request->vendor_id)->where('is_available', '1');

        if (!empty($request->search_input)) {
            $getitem = $getitem->where('items.item_name', 'like', '%' . $request->search_input . '%');
        }
        $getitem = $getitem->paginate(16);
        if (!empty($getitem) && $getitem != null) {
            foreach ($getitem as $item) {
                $item->image = helper::image_path($item->image);
            }
        }
        return response()->json(['status' => 1, 'message' => trans('messages.success'), 'data' => $getitem], 200);
    }
    public function tablelist(Request $request)
    {
        if ($request->vendor_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.vendor_id_required')], 200);
        }
        $tables = DineIn::where('vendor_id', $request->vendor_id)->where('is_available', 1)->orderBy('reorder_id')->get();
        return response()->json(['status' => 1, 'message' => trans('messages.success'), 'tablelist' => $tables], 200);
    }
    public function postreview(Request $request)
    {

        if ($request->vendor_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.vendor_id_required')], 200);
        }
        if ($request->item_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.item_id_required')], 200);
        }
        if ($request->user_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.user_id_required')], 200);
        }
        if ($request->ratting == "") {
            return response()->json(["status" => 0, "message" => trans('messages.ratting_required')], 200);
        }
        if ($request->review == "") {
            return response()->json(["status" => 0, "message" => trans('messages.review_required')], 200);
        }

        $orders = Order::where('orders.user_id', $request->user_id)->where('orders.vendor_id', $request->vendor_id)->join('order_details', 'orders.id', 'order_details.order_id')->where('order_details.item_id', $request->item_id)->where('orders.status_type', '3')->count();
        $rattingcount = Testimonials::where('user_id', $request->user_id)->where('item_id', $request->item_id)->where('vendor_id', $request->vendor_id)->count();
        if ($orders > 0 && $rattingcount == 0) {
            $user = User::where('id', $request->user_id)->first();
            $review = new Testimonials();
            $review->vendor_id = $request->vendor_id;
            $review->user_id = $request->user_id;
            $review->item_id = $request->item_id;
            $review->star = $request->ratting;
            $review->description = $request->review;
            $review->name = $user->name;
            $review->image = $user->image;
            $review->save();
            return response()->json(['status' => 1, 'message' => trans('messages.success')], 200);
        } else {
            return response()->json(["status" => 0, "message" => trans('messages.post_review_message')], 200);
        }
    }
    public function managefavorite(Request $request)
    {
        if ($request->vendor_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.vendor_id_required')], 200);
        }
        if ($request->item_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.item_id_required')], 200);
        }
        if ($request->type == "") {
            return response()->json(["status" => 0, "message" => trans('messages.type_required')], 200);
        }
        if ($request->user_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.user_id_required')], 200);
        }

        try {
            $favorite = Favorite::where('product_id', $request->item_id)->where('vendor_id', $request->vendor_id)->where('user_id', $request->user_id)->first();
            if ($request->type == 2 && !empty($favorite)) {
                $favorite->delete();
            }
            if ($request->type == 1 && empty($favorite)) {
                $favorite = new Favorite();
                $favorite->vendor_id = $request->vendor_id;
                $favorite->user_id = $request->user_id;
                $favorite->product_id = $request->item_id;
                $favorite->save();
            }
            return response()->json(['status' => 1, 'message' => trans('messages.success')], 200);
        } catch (\Throwable $th) {

            return response()->json(['status' => 0, 'message' => trans('messages.wrong')], 200);
        }
    }
    public function filteration(Request $request)
    {
        $userid = "";
        if ($request->vendor_id == "") {
            return response()->json(['status' => 0, 'message' => trans('messages.vendor_id_required')], 200);
        }
        if ($request->user_id != "") {
            $userid = $request->user_id;
        }
        $getitem = Item::with(['variation', 'category_info', 'extras', 'product_image', 'multi_image'])->select('items.*', DB::raw('(case when favorite.product_id is null then 0 else 1 end) as is_favorite'), DB::raw('ROUND(AVG(testimonials.star),1) as ratings_average'), DB::raw('ROUND(AVG(testimonials.star),1) as ratings_average'))->leftjoin('testimonials', 'testimonials.item_id', 'items.id')->leftJoin('favorite', function ($query) use ($userid) {
            $query->on('favorite.product_id', '=', 'items.id')
                ->where('favorite.user_id', '=', $userid);
        })->where('items.vendor_id', $request->vendor_id)->where('items.is_available', '1')->groupBy('items.id')->orderBy('items.reorder_id', 'ASC');

        if (!empty($getitem) && $request->category_id != "") {
            $getitem = $getitem->where(DB::Raw("FIND_IN_SET($request->category_id, items.cat_id)"), '>', 0);
        }
        if (!empty($getitem) && $request->item) {
            $getitem = $getitem->where('items.item_name', 'LIKE', "%{$request->item}%");
        }
        $getitem =  $getitem->get();
        return response()->json(['status' => 1, 'message' => trans('messages.success'), "items" => $getitem], 200);
    }
    public function categorylist(Request $request)
    {
        if ($request->vendor_id == "") {
            return response()->json(['status' => 0, 'message' => trans('messages.vendor_id_required')], 200);
        }
        $category = Category::where('vendor_id', $request->vendor_id)->where('is_available', '1')->where('is_deleted', '2')->orderBy('reorder_id')->get();
        return response()->json(['status' => 1, 'message' => trans('messages.success'), "category" => $category], 200);
    }
    public function bloglisting(Request $request)
    {
        if ($request->vendor_id == "") {
            return response()->json(['status' => 0, 'message' => trans('messages.vendor_id_required')], 200);
        }
        $blogs = Blog::select('*', \DB::raw("CONCAT('" . url('/storage/app/public/admin-assets/images/blog/') . "/', image) AS image_url"))->where('vendor_id', $request->vendor_id)->orderBy('reorder_id')->get();
        return response()->json(['status' => 1, 'message' => trans('messages.success'), "blogs" => $blogs], 200);
    }
    public function getvariationprice(Request $request)
    {

        if ($request->item_id == "") {
            return response()->json(['status' => 0, 'message' => trans('messages.item_id_required')], 200);
        }
        if ($request->variant_name == "") {
            return response()->json(['status' => 0, 'message' => trans('messages.variant_name_required')], 200);
        }
        $quantity = $variant_id = 0;
        $product = Item::find($request->item_id);
        $price = 0;
        $status = false;
        if ($product && $request->variant_name != '') {
            $variant = Variants::where('item_id', $request->item_id)->where('name', $request->variant_name)->first();
            $status = true;
            $quantity = @$variant->qty;
            $price = @$variant->price;
            $original_price = @$variant->original_price;
            $variant_id = @$variant->id;
            $min_order = @$variant->min_order;
            $max_order = @$variant->max_order;
            $stock_management = @$variant->stock_management;
            $variants_name = @$request->name;
            $is_available = @$variant->is_available;
        }
        return response()->json(
            [
                'status' => $status,
                'price' => $price,
                'original_price' => $original_price,
                'quantity' => $quantity,
                'variant_id' => $variant_id,
                'min_order' => $min_order,
                'max_order' => $max_order,
                'stock_management' => $stock_management,
                'variants_name' => $variants_name,
                'is_available' => $is_available,
            ]
        );
    }

   
}
