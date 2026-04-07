<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Banner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DB;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $allcategories = Category::where('vendor_id', $vendor_id)->where('is_deleted', 2)->orderBy('reorder_id')->get();
        return view('admin.category.category', compact("allcategories"));
    }
    public function add_category(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        return view('admin.category.add', compact('vendor_id'));
    }
    public function save_category(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }

        $check_slug = Category::where('slug', Str::slug($request->category_name, '-'))->first();
        if (!empty($check_slug)) {
            $last_id = Category::select('id')->orderByDesc('id')->first()->id;
            $slug = Str::slug($request->category_name . ' ' . $last_id, '-');
        } else {
            $slug = Str::slug($request->category_name, '-');
        }
        $savecategory = new Category();
        $savecategory->vendor_id = $vendor_id;
        $savecategory->name = $request->category_name;
        $savecategory->slug = $slug;
        $savecategory->save();
        return redirect('admin/categories/')->with('success', trans('messages.success'));
    }
    public function edit_category(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $editcategory = category::where('slug', $request->slug)->first();
        return view('admin.category.edit', compact("editcategory", 'vendor_id'));
    }
    public function update_category(Request $request)
    {
        $check_slug = Category::where('slug', Str::slug($request->category_name, '-'))->first();
        if (!empty($check_slug)) {
            $last_id = Category::select('id')->orderByDesc('id')->first()->id;
            $slug = Str::slug($request->category_name . ' ' . $last_id, '-');
        } else {
            $slug = Str::slug($request->category_name, '-');
        }
        $editcategory = Category::where('slug', $request->slug)->first();
        $editcategory->name = $request->category_name;
        $editcategory->slug = $slug;
        $editcategory->update();
        return redirect('admin/categories')->with('success', trans('messages.success'));
    }
    public function change_status(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $checkcategory = Category::where('slug', $request->slug)->first();
        if ($request->status == 2) {
            $getproduct = Item::where(DB::Raw("FIND_IN_SET($checkcategory->id, replace(items.cat_id, '|', ','))"), '>', 0)->get();

            $getbanner = Banner::where('category_id', $checkcategory->id)->where('vendor_id', $vendor_id)->get();
            foreach ($getproduct as $product) {
                $cat_id = explode('|', $product->cat_id);
                $key = array_search($checkcategory->id, $cat_id);
                if ($key !== false) {
                    unset($cat_id[$key]);
                    Item::where('vendor_id', $vendor_id)->update(array('cat_id' => implode('|', $cat_id)));
                }
            }
            foreach ($getbanner as $banner) {
                $banner->type = "";
                $banner->category_id = "";
                $banner->update();
            }
        }
        $checkcategory->is_available = $request->status;
        $checkcategory->update();
        return redirect('admin/categories')->with('success', trans('messages.success'));
    }
    public function delete_category(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $checkcategory = Category::where('slug', $request->slug)->first();

        $getproduct = Item::where(DB::Raw("FIND_IN_SET($checkcategory->id, replace(items.cat_id, '|', ','))"), '>', 0)->get();

        $getbanner = Banner::where('category_id', $checkcategory->id)->where('vendor_id', $vendor_id)->get();
        foreach ($getproduct as $product) {
            $cat_id = explode('|', $product->cat_id);
            $key = array_search($checkcategory->id, $cat_id);
            if ($key !== false) {
                unset($cat_id[$key]);
                Item::where('vendor_id', $vendor_id)->update(array('cat_id' => implode('|', $cat_id)));
            }
        }
        foreach ($getbanner as $banner) {
            $banner->type = "";
            $banner->category_id = "";
            $banner->update();
        }
        $checkcategory->delete();
        return redirect('admin/categories')->with('success', trans('messages.success'));
    }
    public function reorder_category(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $getcategory = Category::where('vendor_id', $vendor_id)->get();
        foreach ($getcategory as $category) {
            foreach ($request->order as $order) {
                $category = Category::where('id', $order['id'])->first();
                $category->reorder_id = $order['position'];
                $category->save();
            }
        }
        return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
    }
    public function bulk_delete_category(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        foreach ($request->id as $id) {
            $checkcategory = Category::where('id', $id)->first();

            $getproduct = Item::where(DB::Raw("FIND_IN_SET($checkcategory->id, replace(items.cat_id, '|', ','))"), '>', 0)->get();
            
            $getbanner = Banner::where('category_id', $checkcategory->id)->where('vendor_id', $vendor_id)->get();
            foreach ($getproduct as $product) {
                $cat_id = explode('|', $product->cat_id);
                $key = array_search($checkcategory->id, $cat_id);
                if ($key !== false) {
                    unset($cat_id[$key]);
                    Item::where('vendor_id', $vendor_id)->update(array('cat_id' => implode('|', $cat_id)));
                }
            }
            foreach ($getbanner as $banner) {
                $banner->type = "";
                $banner->category_id = "";
                $banner->update();
            }
            $checkcategory->delete();
        }
        return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
       
    }
}
