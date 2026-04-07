<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Promotionalbanner;
use App\Models\User;
use App\Models\Category;
use App\Helpers\helper;
use App\Models\Item;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class BannerController extends Controller
{
    public function index()
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $getbannerlist = Banner::with('category_info', 'product_info')->orderBy('reorder_id')->where('vendor_id', $vendor_id)->get();
        return view('admin.banner.banner', compact('getbannerlist'));
    }
    public function add()
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $getcategorylist =  $allcategories = Category::where('vendor_id', $vendor_id)->where('is_deleted', 2)->where('is_available', 1)->orderBy('reorder_id')->get();
        $getproductslist = Item::where('is_available', 1)->where('vendor_id', $vendor_id)->orderBy('reorder_id')->get();
        return view('admin.banner.add', compact('getcategorylist', 'getproductslist'));
    }
    public function store(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $validator = Validator::make($request->all(), [
            'image' => 'image|max:' . helper::imagesize() . '|' . helper::imageext(),
        ], [
            'image.max' => trans('messages.image_size_message'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', trans('messages.image_size_message') . ' ' . helper::appdata('')->image_size . ' ' . 'MB');
        }

        $image = 'banner-' . uniqid() . "." . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(storage_path('app/public/admin-assets/images/banners/'), $image);
        $banner = new Banner();
        $banner->section = $request->section;
        $banner->vendor_id = $vendor_id;
        $banner->banner_image = $image;
        $banner->category_id = $request->banner_info == 1 ? $request->category : 0;
        $banner->product_id = $request->banner_info == 2 ? $request->product : 0;
        $banner->type = $request->banner_info;
        $banner->save();
        if ($request->section == 0) {
            return redirect('admin/sliders')->with('success', trans('messages.success'));
        } else {
            return redirect('admin/bannersection-' . $request->section)->with('success', trans('messages.success'));
        }
    }
    public function show($id)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $getbannerdata = Banner::where('id', $id)->first();
        $getcategorylist =  $allcategories = Category::where('vendor_id', $vendor_id)->where('is_deleted', 2)->where('is_available', 1)->orderBy('reorder_id')->get();
        $getproductslist = Item::where('is_available', 1)->where('vendor_id', $vendor_id)->orderBy('reorder_id')->get();
        return view('admin.banner.edit', compact('getbannerdata', 'getcategorylist', 'getproductslist'));
    }
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'image' => 'image|max:' . helper::imagesize() . '|' . helper::imageext(),
        ], [
            'image.max' => trans('messages.image_size_message'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', trans('messages.image_size_message') . ' ' . helper::appdata('')->image_size . ' ' . 'MB');
        }
        $banner = Banner::where('id', $id)->first();
        if ($request->has('image')) {
            if (file_exists(storage_path('app/public/admin-assets/images/banners/' . $banner->banner_image))) {
                unlink(storage_path('app/public/admin-assets/images/banners/' . $banner->banner_image));
            }
            $image = 'banner-' . uniqid() . "." . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(storage_path('app/public/admin-assets/images/banners/'), $image);
            $banner->banner_image = $image;
        }
        if ($request->banner_info == "1") {
            $banner->category_id = $request->category;
            $banner->product_id = 0;
        }
        if ($request->banner_info == "2") {
            $banner->product_id = $request->product;
            $banner->category_id = 0;
        }
        $banner->type = $request->banner_info;
        $banner->section = $request->section;
        $banner->update();
        if ($request->section == 0) {
            return redirect('admin/sliders')->with('success', trans('messages.success'));
        } else {
            return redirect('admin/bannersection-' . $request->section)->with('success', trans('messages.success'));
        }
    }
    public function delete($id)
    {
        $banner = Banner::where('id', $id)->first();
        if (file_exists(storage_path('app/public/admin-assets/images/banners/' . $banner->banner_image))) {
            unlink(storage_path('app/public/admin-assets/images/banners/' . $banner->banner_image));
        }
        $banner->delete();
        return redirect()->back()->with('success', trans('messages.success'));
    }
    public function bulk_delete(Request $request)
    { 
        foreach ($request->id as $id) {
            $banner = Banner::where('id', $id)->first();
            if (file_exists(storage_path('app/public/admin-assets/images/banners/' . $banner->banner_image))) {
                unlink(storage_path('app/public/admin-assets/images/banners/' . $banner->banner_image));
            }
            $banner->delete();
        }
        return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
    }


    public function promotional_banner()
    {
        $getbannerlist = Promotionalbanner::with('vendor_info')->orderBy('reorder_id')->get();
        return view('admin.promotionalbanners.index', compact('getbannerlist'));
    }
    public function promotional_banneradd()
    {
        $vendors = User::where('is_available', 1)->where('type', 2)->get();
        return view('admin.promotionalbanners.add', compact('vendors'));
    }
    public function promotional_bannersave_banner(Request $request)
    {
        $banner = new Promotionalbanner();
        if ($request->has('image')) {

            $validator = Validator::make($request->all(), [
                'image' => 'image|max:' . helper::imagesize() . '|' . helper::imageext(),
            ], [
                'image.max' => trans('messages.image_size_message'),
            ]);
            if ($validator->fails()) {
                return redirect()->back()->with('error', trans('messages.image_size_message') . ' ' . helper::appdata('')->image_size . ' ' . 'MB');
            }
            $image = 'promotion-' . uniqid() . "." . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(storage_path('app/public/admin-assets/images/banners/'), $image);
            $banner->image = $image;
        }
        $banner->vendor_id = $request->vendor;
        $banner->save();
        return redirect('admin/promotionalbanners')->with('success', trans('messages.success'));
    }
    public function promotional_banneredit(Request $request)
    {
        $vendors = User::where('is_available', 1)->where('type', 2)->get();
        $banner = Promotionalbanner::where('id', $request->id)->first();
        return view('admin.promotionalbanners.edit', compact('vendors', 'banner'));
    }
    public function promotional_bannerupdate(Request $request)
    {
        $banner = Promotionalbanner::where('id', $request->id)->first();
        if ($request->has('image')) {

            $validator = Validator::make($request->all(), [
                'image' => 'image|max:' . helper::imagesize() . '|' . helper::imageext(),
            ], [
                'image.max' => trans('messages.image_size_message'),
            ]);
            if ($validator->fails()) {
                return redirect()->back()->with('error', trans('messages.image_size_message') . ' ' . helper::appdata('')->image_size . ' ' . 'MB');
            }

            if (file_exists(storage_path('app/public/admin-assets/images/banners/' . $banner->image))) {
                unlink(storage_path('app/public/admin-assets/images/banners/' . $banner->image));
            }
            $image = 'promotion-' . uniqid() . "." . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(storage_path('app/public/admin-assets/images/banners/'), $image);
            $banner->image = $image;
        }
        $banner->vendor_id = $request->vendor;
        $banner->update();
        return redirect('admin/promotionalbanners')->with('success', trans('messages.success'));
    }
    public function promotional_bannerdelete(Request $request)
    {
        $banner = Promotionalbanner::where('id', $request->id)->first();
        if (file_exists(storage_path('app/public/admin-assets/images/banners/' . $banner->image))) {
            unlink(storage_path('app/public/admin-assets/images/banners/' . $banner->image));
        }
        $banner->delete();
        return redirect()->back()->with('success', trans('messages.success'));
    }
    public function reorder_promotionalbanner(Request $request)
    {

        $getbanner = Promotionalbanner::with('vendor_info')->get();
        foreach ($getbanner as $banner) {
            foreach ($request->order as $order) {
                $banner = Promotionalbanner::where('id', $order['id'])->first();
                $banner->reorder_id = $order['position'];
                $banner->save();
            }
        }
        return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
    }
    public function reorder_banner(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $getbanner = Banner::where('vendor_id', $vendor_id)->get();
        foreach ($getbanner as $banner) {
            foreach ($request->order as $order) {
                $banner = Banner::where('id', $order['id'])->first();
                $banner->reorder_id = $order['position'];
                $banner->save();
            }
        }
        return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
    }
    public function promotional_bulkdelete(Request $request)
    {
        foreach ($request->id as $id) {
            $banner = Promotionalbanner::where('id', $id)->first();
            if (file_exists(storage_path('app/public/admin-assets/images/banners/' . $banner->image))) {
                unlink(storage_path('app/public/admin-assets/images/banners/' . $banner->image));
            }
            $banner->delete();
        }
        return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
    }
}