<?php



namespace App\Http\Controllers\admin;

use App\Helpers\helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settings;
use App\Models\WhoWeAre;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class WhoWeAreController extends Controller

{

    public function index()
    {
        if (Auth::user()->type == 4) {

            $vendor_id = Auth::user()->vendor_id;
        } else {

            $vendor_id = Auth::user()->id;
        }

        $content = Settings::where('vendor_id', $vendor_id)->first();

        $allworkcontent = WhoWeAre::where('vendor_id', $vendor_id)->orderBy('reorder_id')->get();

        return view('admin.who_we_are.index', compact('content', 'allworkcontent'));
    }

    public function savecontent(Request $request)
    {

        if (Auth::user()->type == 4) {

            $vendor_id = Auth::user()->vendor_id;
        } else {

            $vendor_id = Auth::user()->id;
        }

        $newcontent = Settings::where('vendor_id', $vendor_id)->first();
        $newcontent->whoweare_title = $request->title;
        $newcontent->whoweare_subtitle = $request->sub_title;
        $newcontent->whoweare_description = $request->description;

        if ($request->has('image')) {

            $validator = Validator::make($request->all(), [
                'image' => 'image|max:' . helper::imagesize() . '|' . helper::imageext(),
            ], [
                'image.max' => trans('messages.image_size_message'),
            ]);
            if ($validator->fails()) {
                return redirect()->back()->with('error', trans('messages.image_size_message') . ' ' . helper::appdata('')->image_size . ' ' . 'MB');
            }
            if ($newcontent->whoweare_image != null && file_exists(storage_path('app/public/admin-assets/images/index/' . $newcontent->whoweare_image))) {

                unlink(storage_path('app/public/admin-assets/images/index/' . $newcontent->whoweare_image));
            }

            $weareImage = 'whoweare-' . uniqid() . "." . $request->file('image')->getClientOriginalExtension();

            $request->file('image')->move(storage_path('app/public/admin-assets/images/index/'), $weareImage);

            $newcontent->whoweare_image = $weareImage;
        }

        $newcontent->save();

        return redirect()->back()->with('success', trans('messages.success'));
    }

    public function add()
    {
        return view('admin.who_we_are.add');
    }

    public function save(Request $request)
    {
        if (Auth::user()->type == 4) {

            $vendor_id = Auth::user()->vendor_id;
        } else {

            $vendor_id = Auth::user()->id;
        }

        $newcontent = new WhoWeAre();

        $newcontent->vendor_id = $vendor_id;

        $newcontent->title = $request->title;

        $newcontent->sub_title = $request->description;

        if ($request->has('image')) {

            $validator = Validator::make($request->all(), [
                'image' => 'image|max:' . helper::imagesize() . '|' . helper::imageext(),
            ], [
                'image.max' => trans('messages.image_size_message'),
            ]);
            if ($validator->fails()) {
                return redirect()->back()->with('error', trans('messages.image_size_message') . ' ' . helper::appdata('')->image_size . ' ' . 'MB');
            }
            if ($newcontent->image != null && file_exists(storage_path('app/public/admin-assets/images/index/' . $newcontent->image))) {

                unlink(storage_path('app/public/admin-assets/images/index/' . $newcontent->image));
            }

            $weareImage = 'whoweare-' . uniqid() . "." . $request->file('image')->getClientOriginalExtension();

            $request->file('image')->move(storage_path('app/public/admin-assets/images/index/'), $weareImage);

            $newcontent->image = $weareImage;
        }

        $newcontent->save();

        return redirect('/admin/whoweare')->with('success', trans('messages.success'));
    }

    public function edit(Request $request)
    {
        if (Auth::user()->type == 4) {

            $vendor_id = Auth::user()->vendor_id;
        } else {

            $vendor_id = Auth::user()->id;
        }

        $editwork = WhoWeAre::where('vendor_id', $vendor_id)->where('id', $request->id)->first();

        return view('admin.who_we_are.edit', compact('editwork'));
    }

    public function update(Request $request)
    {
        if (Auth::user()->type == 4) {

            $vendor_id = Auth::user()->vendor_id;
        } else {

            $vendor_id = Auth::user()->id;
        }

        $editwork = WhoWeAre::where('vendor_id', $vendor_id)->where('id', $request->id)->first();

        $editwork->vendor_id = $vendor_id;

        $editwork->title = $request->title;

        if ($request->has('image')) {

            $validator = Validator::make($request->all(), [
                'image' => 'image|max:' . helper::imagesize() . '|' . helper::imageext(),
            ], [
                'image.max' => trans('messages.image_size_message'),
            ]);
            if ($validator->fails()) {
                return redirect()->back()->with('error', trans('messages.image_size_message') . ' ' . helper::appdata('')->image_size . ' ' . 'MB');
            }
            if ($editwork->image != null && file_exists(storage_path('app/public/admin-assets/images/index/' . $editwork->image))) {

                unlink(storage_path('app/public/admin-assets/images/index/' . $editwork->image));
            }

            $weareImage = 'whoweare-' . uniqid() . "." . $request->file('image')->getClientOriginalExtension();

            $request->file('image')->move(storage_path('app/public/admin-assets/images/index/'), $weareImage);

            $editwork->image = $weareImage;
        }

        $editwork->sub_title = $request->description;

        $editwork->update();

        return redirect('/admin/whoweare')->with('success', trans('messages.success'));
    }

    public function delete(Request $request)
    {
        if (Auth::user()->type == 4) {

            $vendor_id = Auth::user()->vendor_id;
        } else {

            $vendor_id = Auth::user()->id;
        }

        $deletework = WhoWeAre::where('vendor_id', $vendor_id)->where('id', $request->id)->first();

        if ($deletework->image != null && file_exists(storage_path('app/public/admin-assets/images/index/' . $deletework->image))) {

            unlink(storage_path('app/public/admin-assets/images/index/' . $deletework->image));
        }

        $deletework->delete();

        return redirect('/admin/whoweare')->with('success', trans('messages.success'));
    }
    public function reorder_whoweare(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $getwhoweare = WhoWeAre::where('vendor_id', $vendor_id)->get();
        foreach ($getwhoweare as $item) {
            foreach ($request->order as $order) {
                $item = WhoWeAre::where('id', $order['id'])->first();
                $item->reorder_id = $order['position'];
                $item->save();
            }
        }
        return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
    }
}
