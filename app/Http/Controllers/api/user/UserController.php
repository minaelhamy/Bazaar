<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Helpers\helper;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Config;
class UserController extends Controller
{
    public function register_customer(Request $request)
    {
        if($request->name == "")
        {
            return response()->json(["status"=>0,"message"=>trans('messages.name_required')],200);
        }
        if($request->email == "")
        {
            return response()->json(["status"=>0,"message"=>trans('messages.email_required')],200);
        }
        if($request->password == "")
        {
            return response()->json(["status"=>0,"message"=>trans('messages.password_required')],200);
        }
        if($request->mobile == "")
        {
            return response()->json(["status"=>0,"message"=>trans('messages.unique_mobile_required')],200);
        }
        $checkemail = User::select('email')->where('email',$request->email)->first();
        $checkmobile = User::where('mobile',$request->mobile)->first();
        if(!empty($checkemail))
        {
            return response()->json(["status"=>0,"message"=>trans('messages.unique_email_required')],200);
        }
        if(!empty($checkmobile))
        {
            return response()->json(["status"=>0,"message"=>trans('messages.unique_mobile_required')],200);
        }
        $newuser = new User();
        $newuser->name = $request->name;
        $newuser->email = $request->email;
        $newuser->password = hash::make($request->password);
        $newuser->mobile = $request->mobile;
        $newuser->type = "3";
        $newuser->login_type = "email";
        $newuser->image = "default-logo.png";
        $newuser->is_available = "1";
        $newuser->is_verified = "1";
        $newuser->save();

        Auth::login($newuser);
         return response()->json(["status"=>1,"message"=>trans('messages.success'),'data' => $newuser],200);
    }
    
    public function login_customer(Request $request)
    {
        if ($request->email == "") {
            return response()->json(["status" => 0, "message" => trans('messages.email_required')], 200);
        }
        if ($request->password == "") {
            return response()->json(["status" => 0, "message" => trans('messages.password_required')], 200);
        }
        $checkuser = User::where('email', $request->email)->where('type', '3')->first();
        if (!empty($checkuser)) {
            if (Hash::check($request->password, $checkuser->password)) {
                if ($checkuser->is_available == '1') {
                    $checkuser->token = $request->token;
                    $checkuser->save();
                    $checkuser = $checkuser::select('id','name','email','mobile','image')->where('id',$checkuser->id)->first();
                    $checkuser->image = helper::image_path($checkuser->image);
                    return response()->json(['status' => 1, 'message' => trans('messages.success'), 'data' => $checkuser], 200);
                } else {
                    return response()->json(['status' => 0, 'message' => trans('messages.blocked')], 200);
                }
            } else {
                return response()->json(['status' => 0, 'message' => trans('messages.email_password_not_match')], 200);
            }
        } else {
            return response()->json(['status' => 0, 'message' => trans('messages.email_password_not_match')], 200);
        }
    }
    public function send_userpassword(Request $request)
    {
        if($request->email == "")
        {
            return response()->json(["status" => 0, "message" => trans('messages.email_required')], 200);
        }
        
        $checkuser = User::where('email', $request->email)->where('is_available', 1)->first();
        if (!empty($checkuser)) {
            $password = substr(str_shuffle($checkuser->password), 1, 6);
            $emaildata = helper::emailconfigration('');
            Config::set('mail',$emaildata);
            $check_send_mail = helper::send_pass($request->email,$checkuser->name,$password,$checkuser->id);
            if ($check_send_mail == 1) {
                $checkuser->password = Hash::make($password);
                $checkuser->save();
                return response()->json(['status' => 1, 'message' => trans('messages.success')], 200);
            } else {
                return response()->json(['status' => 0, 'message' => trans('messages.wrong')], 200);
            }
        } else {
            return response()->json(['status' => 0, 'message' => trans('messages.invalid_user')], 200);
        }
    }
  
    public function updateprofile(Request $request)
    {
        if($request->user_id == "")
        {
            return response()->json(['status' => 0, 'message' => trans('messages.user_id_required')], 200);
        }
        if($request->name == "")
        {
            return response()->json(['status' => 0, 'message' => trans('messages.name_required')], 200);
        }
        if($request->email == "")
        {
            return response()->json(['status' => 0, 'message' => trans('messages.email_required')], 200);
        }
        if($request->mobile == "")
        {
            return response()->json(['status' => 0, 'message' => trans('messages.unique_mobile_required')], 200);
        }
        if($request->profile == "")
        {
            return response()->json(['status' => 0, 'message' => trans('messages.profile_required')], 200);
        }
        $edituser = User::where('id', $request->user_id)->first();
        $edituser->name = $request->name;
        $edituser->email = $request->email;
        $edituser->mobile = $request->mobile;
        if ($request->has('profile')) {
            $validator = Validator::make($request->all(), [
                'profile' => 'image|max:'.helper::imagesize().'|'.helper::imageext(),
            ], [
                'profile.max' => trans('messages.image_size_message'). ' ' . helper::appdata('')->image_size .' ' .'MB',
            ]);
            if ($edituser->image != "" && file_exists(storage_path('app/public/admin-assets/images/profile/' . $edituser->image))) {
                unlink(storage_path('app/public/admin-assets/images/profile/' . $edituser->image));
            }
            $edit_image = $request->file('profile');
            $profileImage = 'profile-' . uniqid() . "." . $edit_image->getClientOriginalExtension();
            $edit_image->move(storage_path('app/public/admin-assets/images/profile/'), $profileImage);
            $edituser->image = $profileImage;
        }
        $edituser->update();
        if ($edituser) {
            $edituser->image = helper::image_path($edituser->image);
            $edituser = $edituser::select('id','name','mobile','password','email','image',DB::raw("CONCAT('".url(env('ASSETPATHURL').'admin-assets/images/profile')."/', image) AS image_url"))->where('id',$request->user_id)->first();
            return response()->json(['status' => 1, 'message' => trans('messages.success'),'data'=>$edituser], 200);
            
        } else {
            return response()->json(['status' => 0, 'message' => trans('messages.wrong')], 200);
        }
    }
   
    public function change_password(Request $request)
    {
        if($request->user_id == "")
        {
            return response()->json(["status" => 0, "message" => trans('messages.user_id_required')], 200);
        }
        if($request->current_password == "")
        {
            return response()->json(["status" => 0, "message" => trans('messages.current_password_required')], 200);
        }
        if($request->new_password == "")
        {
            return response()->json(["status" => 0, "message" => trans('messages.new_password_required')], 200);
        }
        if($request->confirm_password == "")
        {
            return response()->json(["status" => 0, "message" => trans('messages.confirm_password_required')], 200);
        }
       
        $user = User::where('id',$request->user_id)->first();
        if (Hash::check($request->current_password, $user->password)) {
            if ($request->current_password == $request->new_password) {
                return response()->json(["status" => 0, "message" => trans('messages.new_old_password_diffrent')], 200);
            } else {
                if ($request->new_password == $request->confirm_password) {
                     $user->password = Hash::make($request->new_password);
                     $user->update();
                     return response()->json(["status" => 1, "message" => trans('messages.success')], 200);
                } else {
                    return response()->json(["status" => 0, "message" => trans('messages.new_confirm_password_inccorect')], 200);
                }
            }
        } else {
            return response()->json(["status" => 0, "message" => trans('messages.old_password_incorect')], 200);
        }
    }
    public function wishlist(Request $request)
    {
        if($request->user_id == "")
        {
            return response()->json(["status" => 0, "message" => trans('messages.user_id_required')], 200);
        }
        if($request->vendor_id == "")
        {
            return response()->json(["status" => 0, "message" => trans('messages.vendor_id_required')], 200);
        }
        $getitem = Item::with(['variation', 'extras', 'product_image', 'multi_image'])->select('items.*', DB::raw('ROUND(AVG(testimonials.star),1) as ratings_average'))->leftjoin('favorite', 'favorite.product_id', '=', 'items.id')
        ->leftJoin('testimonials', 'testimonials.item_id', '=', 'items.id')
        ->groupBy('items.id')->where('favorite.vendor_id', $request->vendor_id)
        ->where('items.vendor_id', $request->vendor_id)
        ->where('favorite.user_id', $request->user_id)->orderBy('items.reorder_id', 'ASC')
        ->where('items.is_available', 1)->paginate(8);
        return response()->json(["status" => 1, "message" => trans('messages.success'),'data' => $getitem], 200);
    }
}
