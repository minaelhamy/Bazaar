<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\StoreCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class StoreCategoryController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::user()->type == 4)
        {
            $vendor_id = Auth::user()->vendor_id;
        }else{
            $vendor_id = Auth::user()->id;
        }
        $allcategories = StoreCategory::where('is_deleted', 2)->orderBy('reorder_id')->get();
        return view('admin.store_categories.index', compact("allcategories"));
    }
    public function add_category(Request $request)
    {
        return view('admin.store_categories.add');
    }
    public function save_category(Request $request)
    {
        $savecategory = new StoreCategory();
        $savecategory->name = $request->category_name;
        $savecategory->save();
        return redirect('admin/store_categories/')->with('success', trans('messages.success'));
    }
    public function edit_category(Request $request)
    {
        $editcategory = StoreCategory::where('id', $request->id)->first();
        return view('admin.store_categories.edit', compact("editcategory"));
    }
    public function update_category(Request $request)
    {
        $editcategory = StoreCategory::where('id', $request->id)->first();
        $editcategory->name = $request->category_name;
        $editcategory->update();
        return redirect('admin/store_categories')->with('success', trans('messages.success'));
    }
    public function change_status(Request $request)
    {
        StoreCategory::where('id', $request->id)->update(['is_available' => $request->status]);
        return redirect('admin/store_categories')->with('success', trans('messages.success'));
    }
    public function delete_category(Request $request)
    {
        $checkcategory = StoreCategory::where('id', $request->id)->first();
        if (!empty($checkcategory)) {
            $checkcategory->is_deleted = 1;
            $checkcategory->save();
            return redirect('admin/store_categories')->with('success', trans('messages.success'));
        } else {
            return redirect()->back()->with('error', trans('messages.wrong'));
        }
    }
    public function reorder_category(Request $request)
    {
       
        $getcategory = StoreCategory::get();
        foreach ($getcategory as $category) {
            foreach ($request->order as $order) {
               $category = StoreCategory::where('id',$order['id'])->first();
               $category->reorder_id = $order['position'];
               $category->save();
            }
        }
        return response()->json(['status' => 1,'msg' => trans('messages.success')], 200);
    }
    public function bulk_delete(Request $request)
    {
        foreach ($request->id as $id) {
            $checkcategory = StoreCategory::where('id', $id)->first();
            if (!empty($checkcategory)) {
                $checkcategory->is_deleted = 1;
                $checkcategory->save();
            }
        }
        return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
    }
}