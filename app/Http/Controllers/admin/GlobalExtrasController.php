<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GlobalExtras;
use App\Models\Extra;
use Illuminate\Support\Facades\Auth;

class GlobalExtrasController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $allextras  = GlobalExtras::where('vendor_id', $vendor_id)->orderBy('reorder_id')->get();
        return view('admin.extras.index', compact('allextras'));
    }
    public function add()
    {
        return view('admin.extras.add');
    }
    public function save(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $newaextras = new GlobalExtras();
        $newaextras->vendor_id = $vendor_id;
        $newaextras->name = $request->name;
        $newaextras->price = $request->price;
        $newaextras->save();
        return redirect('admin/extras')->with('success', trans('messages.success'));
    }
    public function edit(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $editextras = GlobalExtras::where('id', $request->id)->where('vendor_id', $vendor_id)->first();
        return view('admin.extras.edit', compact('editextras'));
    }
    public function update(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $editextras = GlobalExtras::where('id', $request->id)->where('vendor_id', $vendor_id)->first();
        $editextras->name = $request->name;
        $editextras->price = $request->price;
        $editextras->update();
        return redirect('admin/extras')->with('success', trans('messages.success'));
    }
    public function change_status(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $extras = GlobalExtras::where('id', $request->id)->where('vendor_id', $vendor_id)->first();
        $extras->is_available = $request->status;
        $extras->update();
        return redirect('admin/extras')->with('success', trans('messages.success'));
    }
    public function delete(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $extras = GlobalExtras::where('id', $request->id)->where('vendor_id', $vendor_id)->first();
        $extras->delete();
        return redirect('admin/extras')->with('success', trans('messages.success'));
    }
    public function getextras()
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $globalextras = GlobalExtras::where('vendor_id', $vendor_id)->where('is_available', 1)->orderBy('reorder_id')->get();
        return response()->json(['status' => 1, 'msg' => trans('messages.success'), 'responsdata' => $globalextras], 200);
    }
    public function editgetextras(Request $request)
    {

        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $extras = Extra::where('item_id', $request->id)->get();
        $globalextras = GlobalExtras::where('vendor_id', $vendor_id)->where('is_available', 1)->get();
        return response()->json(['status' => 1, 'msg' => trans('messages.success'), 'responsdata' => $globalextras], 200);
    }

    public function reorder_extras(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $getextras = GlobalExtras::where('vendor_id', $vendor_id)->get();
        foreach ($getextras as $extras) {
            foreach ($request->order as $order) {
                $extras = GlobalExtras::where('id', $order['id'])->first();
                $extras->reorder_id = $order['position'];
                $extras->save();
            }
        }
        return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
    }
     public function bulk_delete(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        foreach ($request->id as $id) {
            $extras = GlobalExtras::where('id', $id)->where('vendor_id', $vendor_id)->first();
            $extras->delete();
        }
        return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
        
    }
}
