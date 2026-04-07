<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DineIn;
use Illuminate\Support\Facades\Auth;

class TableController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $tables = DineIn::where('vendor_id', $vendor_id)->orderBy('reorder_id')->get();
        return view('admin.table.index', compact("tables"));
    }
    public function add_category()
    {
        return view('admin.table.add');
    }
    public function save_category(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }

        $newtable = new DineIn();
        $newtable->vendor_id = $vendor_id;
        $newtable->name = $request->name;
        $newtable->save();
        return redirect('admin/dinein/')->with('success', trans('messages.success'));
    }
    public function edit_category(Request $request)
    {
        $edittable = DineIn::where('id', $request->id)->first();
        return view('admin.table.edit', compact("edittable"));
    }
    public function update_category(Request $request)
    {
        $edittable = DineIn::where('id', $request->id)->first();
        $edittable->name = $request->name;
        $edittable->update();
        return redirect('admin/dinein')->with('success', trans('messages.success'));
    }
    public function change_status(Request $request)
    {
        DineIn::where('id', $request->id)->update(['is_available' => $request->status]);
        return redirect('admin/dinein')->with('success', trans('messages.success'));
    }
    public function delete_category(Request $request)
    {
        $checktable = DineIn::where('id', $request->id)->first();
        $checktable->delete();
        return redirect('admin/dinein')->with('success', trans('messages.success'));
    }
    public function reorder_category(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $gettable = DineIn::where('vendor_id', $vendor_id)->get();
        foreach ($gettable as $table) {
            foreach ($request->order as $order) {
                $table = DineIn::where('id', $order['id'])->first();
                $table->reorder_id = $order['position'];
                $table->save();
            }
        }
        return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
    }
}
