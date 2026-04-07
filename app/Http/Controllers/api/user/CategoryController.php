<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Helpers\helper;
class CategoryController extends Controller
{
    public function allcategory(Request $request)
    {
        if($request->vendor_id == "")
        {
            return response()->json(["status" => 0, "message" => trans('messages.vendor_id_required')], 200);
        }
        $getcategory = Category::select('id','vendor_id','name','slug')->where('vendor_id', $request->vendor_id)->where('is_available','1')->where('is_deleted', '2')->get();
        return response()->json(["status" => 1, "message" => trans('messages.success'),'data' => $getcategory], 200);
    }
    public function categorywiseitems(Request $request)
    {
        if($request->vendor_id == "")
        {
            return response()->json(["status" => 0, "message" => trans('messages.vendor_id_required')], 200);
        }
        if($request->category_id == "")
        {
            return response()->json(["status" => 0, "message" => trans('messages.category_id_required')], 200);
        }
        $items =  Item::with(['variation', 'extras'])->where('vendor_id', $request->vendor_id)->where('cat_id',$request->category_id)->where('is_available', '1')->get();
        foreach($items as $item)
        {
            $item->image = helper::image_path($item->image);
        }
        return response()->json(["status" => 1, "message" => trans('messages.success'),'data' => $items], 200);
    }
}
