<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Theme;
use App\Helpers\helper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function index(Request $request)
    {
        $themes = Theme::where('vendor_id',1)->orderBy('reorder_id')->get();
        return view('admin.theme.index',compact('themes'));
    }
    public function add(Request $request)
    {
        return view('admin.theme.add');
    }
    public function edit(Request $request)
    {
        $theme = Theme::where('vendor_id',1)->where('id',$request->id)->first();
        return view('admin.theme.edit',compact('theme'));
    }
    public function update(Request $request)
    {
        $edittheme = theme::where('id',$request->id)->first();
        $edittheme->name = $request->name;
        $edittheme->vendor_id = Auth::user()->id;
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
            if (file_exists(storage_path('app/public/admin-assets/images/theme/' . $edittheme->theme))) {
                @unlink(storage_path('app/public/admin-assets/images/theme/' . $edittheme->theme));
            }
            $theme = 'theme-' . uniqid() . '.' . $request->image->getClientOriginalExtension();
            $request->file('image')->move(storage_path('app/public/admin-assets/images/theme/'), $theme);
            $edittheme->image = $theme;
        }
        $edittheme->save();
        return redirect('admin/themes')->with('success',trans('messages.success'));
    }
    public function save(Request $request)
    {
        $newtheme = new theme();
        $newtheme->name = $request->name;
        $newtheme->vendor_id = Auth::user()->id;
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
            $theme = 'theme-' . uniqid() . '.' . $request->image->getClientOriginalExtension();
            $request->file('image')->move(storage_path('app/public/admin-assets/images/theme/'), $theme);
            $newtheme->image = $theme;
        }
        $newtheme->save();
        return redirect('admin/themes')->with('success',trans('messages.success'));
    }
    public function delete(Request $request)
    {
        $theme = Theme::where('id',$request->id)->first();
        if (file_exists(storage_path('app/public/admin-assets/images/theme/' . $theme->image))) {
            unlink(storage_path('app/public/admin-assets/images/theme/' . $theme->image));
        }
        $theme->delete();
        return redirect('admin/themes')->with('success',trans('messages.success'));
    }
    public function reorder_theme(Request $request)
    {
        if(Auth::user()->type == 4)
        {
            $vendor_id = Auth::user()->vendor_id;
        }else{
            $vendor_id = Auth::user()->id;
        }
        $gettheme = Theme::where('vendor_id',$vendor_id )->get();
        foreach ($gettheme as $theme) {
            foreach ($request->order as $order) {
               $theme = Theme::where('id',$order['id'])->first();
               $theme->reorder_id = $order['position'];
               $theme->save();
            }
        }
        return response()->json(['status' => 1,'msg' => trans('messages.success')], 200);
    }

    public function bulk_delete(Request $request)
    {
        foreach ($request->id as $id) {
            $theme = Theme::where('id',$id)->first();
            if (file_exists(storage_path('app/public/admin-assets/images/theme/' . $theme->image))) {
                unlink(storage_path('app/public/admin-assets/images/theme/' . $theme->image));
            }
            $theme->delete();
        }
        return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
    }
    
}