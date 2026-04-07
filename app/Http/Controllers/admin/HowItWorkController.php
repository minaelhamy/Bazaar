<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HowWorks;
use App\Helpers\helper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class HowItWorkController extends Controller
{
    public function index(Request $request)
    {
        $datas = HowWorks::where('vendor_id', 1)->orderBy('reorder_id')->get();
        return view('admin.howwork.index', compact('datas'));
    }
    public function add(Request $request)
    {
        return view('admin.howwork.add');
    }
    public function edit(Request $request)
    {
        $data = HowWorks::where('id', $request->id)->first();
        return view('admin.howwork.edit', compact('data'));
    }
    public function save(Request $request)
    {
        $data = new HowWorks();
        $data->vendor_id = Auth::user()->id;
        $data->title = $request->title;
        $data->description = $request->description;
        if ($request->hasfile('image')) {
            $validator = Validator::make($request->all(), [
                'image' => 'image|max:'.helper::imagesize().'|'.helper::imageext(),
            ], [
                "image.image" => trans('messages.enter_image_file'),
                'image.max' => trans('messages.image_size_message'),
            ]);
            if ($validator->fails()) {
                return redirect()->back()->with('error', trans('messages.image_size_message'). ' ' . helper::appdata('')->image_size .' ' .'MB');
            }
            $workimage = 'work-' . uniqid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/admin-assets/images/index/'), $workimage);
            $data->image = $workimage;
        }
        $data->save();
        return redirect('/admin/how_it_works')->with('success', trans('messages.success'));
    }
    public function update(Request $request)
    {

        $data = HowWorks::where('id', $request->id)->first();
        $data->vendor_id = Auth::user()->id;
        $data->title = $request->title;
        $data->description = $request->description;
        if ($request->hasfile('image')) {
            $validator = Validator::make($request->all(), [
                'image' => 'image|max:'.helper::imagesize().'|'.helper::imageext(),
            ], [
                "image.image" => trans('messages.enter_image_file'),
                'image.max' => trans('messages.image_size_message'),
            ]);
            if ($validator->fails()) {
                return redirect()->back()->with('error', trans('messages.image_size_message'). ' ' . helper::appdata('')->image_size .' ' .'MB');
            }
            if (file_exists(storage_path('app/public/admin-assets/images/index/' . $data->image))) {
                @unlink(storage_path('app/public/admin-assets/images/index/' . $data->image));
            }
            $workimage = 'work-' . uniqid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(storage_path('app/public/admin-assets/images/index/'), $workimage);
            $data->image = $workimage;
        }
        $data->update();
        return redirect('/admin/how_it_works')->with('success', trans('messages.success'));
    }
    public function delete(Request $request)
    {
        $data = HowWorks::where('id', $request->id)->first();
        if (file_exists(storage_path('app/public/admin-assets/images/index/' . $data->image))) {
            @unlink(storage_path('app/public/admin-assets/images/index/' . $data->image));
        }
        $data->delete();
        return redirect('/admin/how_it_works')->with('success', trans('messages.success'));
    }
    public function reorder_how_work(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $howworks = HowWorks::where('vendor_id', $vendor_id)->get();
        foreach ($howworks as $works) {
            foreach ($request->order as $order) {
                $works = HowWorks::where('id', $order['id'])->first();
                $works->reorder_id = $order['position'];
                $works->save();
            }
        }
        return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
    }
    public function bulk_delete(Request $request)
    {
        foreach ($request->id as $id) {
            $data = HowWorks::where('id', $id)->first();
            if (file_exists(storage_path('app/public/admin-assets/images/index/' . $data->image))) {
                @unlink(storage_path('app/public/admin-assets/images/index/' . $data->image));
            }
            $data->delete();
        }
        return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
    }
}
