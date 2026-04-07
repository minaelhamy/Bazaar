<?php

namespace App\Http\Controllers\addons\included;

use App\Http\Controllers\Controller;

use App\Models\Blog;
use App\Models\User;
use App\Helpers\helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $blog = Blog::orderBy('reorder_id')->where('vendor_id', $vendor_id)->get();
        return view('admin.included.blog.blog', compact('blog'));
    }
    public function add()
    {
        return view('admin.included.blog.add');
    }
    public function save(Request $request)
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
        $check_slug = Blog::where('slug', Str::slug($request->title, '-'))->first();
        if (!empty($check_slug)) {
            $last_id = Blog::select('id')->orderByDesc('id')->first();
            $slug = Str::slug($request->title . ' ' . $last_id->id, '-');
        } else {
            $slug = Str::slug($request->title, '-');
        }
        $blogimage = 'blog-' . uniqid() . "." . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(storage_path('app/public/admin-assets/images/blog/'), $blogimage);
        $blog = new Blog();
        $blog->vendor_id = $vendor_id;
        $blog->title = $request->title;
        $blog->slug = $slug;
        $blog->description = $request->description;
        $blog->image = $blogimage;
        $blog->save();
        return redirect('admin/blogs')->with('success', trans('messages.success'));
    }
    public function edit($slug)
    {
        $getblog = Blog::where('slug', $slug)->first();
        if (!empty($getblog)) {
            return view('admin.included.blog.edit', compact('getblog'));
        }
        return redirect('admin/blogs');
    }
    public function update(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'image|max:' . helper::imagesize() . '|' . helper::imageext(),
        ], [
            'image.max' => trans('messages.image_size_message'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', trans('messages.image_size_message') . ' ' . helper::appdata('')->image_size . ' ' . 'MB');
        }
        $blog = Blog::where('slug', $slug)->first();
        $blog->title = $request->title;
        $blog->description = $request->description;
        if ($request->has('image')) {
            if (file_exists(storage_path('app/public/admin-assets/images/blog/' . $blog->image))) {
                unlink(storage_path('app/public/admin-assets/images/blog/' . $blog->image));
            }
            $blogimage = 'blog-' . uniqid() . "." . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(storage_path('app/public/admin-assets/images/blog/'), $blogimage);
            $blog->image = $blogimage;
        }
        $blog->update();
        return redirect('admin/blogs')->with('success', trans('messages.success'));
    }
    public function delete($slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        if (file_exists(storage_path('app/public/admin-assets/images/blog/' . $blog->image))) {
            unlink(storage_path('app/public/admin-assets/images/blog/' . $blog->image));
        }
        $blog->delete();
        return redirect()->back()->with('success', trans('messages.success'));
    }
    public function bulk_delete(Request $request)
    {
        foreach ($request->id as $id) {
            $blog = Blog::where('id', $id)->where('vendor_id',Auth::user()->type)->first();
            if (file_exists(storage_path('app/public/admin-assets/images/blog/' . $blog->image))) {
                unlink(storage_path('app/public/admin-assets/images/blog/' . $blog->image));
            }
            $blog->delete();
        }
        return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
        
    }

    // ------------front blogs functions----------
    public function blogs(Request $request)
    {
        $storeinfo = helper::storeinfo($request->vendor);
        $getblog = Blog::where('vendor_id', $storeinfo->id)->orderBy('reorder_id')->paginate(8);
        return view('front.included.blogs.index', compact('getblog', 'storeinfo'));
    }
    public function blogdetails(Request $request)
    {
        $storeinfo = helper::storeinfo($request->vendor);
        $blogdetail = Blog::where('slug', $request->slug)->first();
        $getblog = Blog::where('vendor_id', $storeinfo->id)->where('slug', '!=', $request->slug)->orderBy('reorder_id')->take(4)->get();
        return view('front.included.blogs.blog_detail', compact('getblog', 'blogdetail', 'storeinfo'));
    }

    // landing page function
    public function allblogs()
    {
        $admindata = User::where('type', 1)->first();
        $blogs = Blog::where('vendor_id', $admindata->id)->orderBy('reorder_id')->paginate(12);
        return view('landing.included.bloglist', compact('blogs'));
    }
    public function pageblogdetail(Request $request)
    {
        $admindata = User::where('type', 1)->first();
        $getblog = Blog::where('slug', $request->slug)->first();
        $blogs = Blog::where('vendor_id', $admindata->id)->whereNot('slug', $request->slug)->orderBy('reorder_id')->take(5)->get();
        return view('landing.included.blogdetail', compact('getblog', 'blogs'));
    }
    public function reorder_blogs(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $getblogs = Blog::where('vendor_id', $vendor_id)->get();
        foreach ($getblogs as $blog) {
            foreach ($request->order as $order) {
                $blog = Blog::where('id', $order['id'])->first();
                $blog->reorder_id = $order['position'];
                $blog->save();
            }
        }
        return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
    }
}
