<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Country;
use App\Models\City;
use Illuminate\Support\Facades\Validator;
use App\Helpers\helper;

class UserController extends Controller
{
    public function check_admin_login(Request $request)
    {
        if ($request->email == "") {
            return response()->json(["status" => 0, "message" => trans('messages.email_required')], 200);
        }
        if ($request->password == "") {
            return response()->json(["status" => 0, "message" => trans('messages.password_required')], 200);
        }
        
        $checkuser = User::where('email', $request->email)->where('type', '2')->first();
        if (!empty($checkuser)) {
            if (Hash::check($request->password, $checkuser->password)) {
                if ($checkuser->is_available == '1' && $checkuser->is_deleted == '2') {
                    $checkuser->token = $request->token;
                    $checkuser->save();
                    $checkuser = $checkuser::select('id','name','email','mobile','image','login_type')->where('id',$checkuser->id)->first();
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

    public function register_vendor(Request $request)
    {
        if($request->name == ""){
            return response()->json(["status"=>0,"message"=>trans('messages.name_required')],200);
        }
        if($request->email == ""){
            return response()->json(["status"=>0,"message"=>trans('messages.email_required')],200);
        }
        if($request->mobile == ""){
            return response()->json(["status"=>0,"message"=>trans('messages.unique_mobile_required')],200);
        }
        if($request->password == ""){
            return response()->json(["status"=>0,"message"=>trans('messages.password_required')],200);
        }
        if($request->country_id == ""){
            return response()->json(["status"=>0,"message"=>trans('messages.country_id_required')],200);
        }
        if($request->city_id == ""){
            return response()->json(["status"=>0,"message"=>trans('messages.city_id_required')],200);
        }
        if($request->store_id == ""){
            return response()->json(["status"=>0,"message"=>trans('messages.store_id_required')],200);
        }
        if($request->store_type == ""){
            return response()->json(["status"=>0,"message"=>trans('messages.store_type_required')],200);
        }
        $checkemail=User::where('email',$request->email)->first();
        $checkmobile=User::where('mobile',$request->mobile)->first();
        $checkslug=User::where('slug',$request->slug)->first();
        if(!empty($checkemail)){
            return response()->json(['status'=>0,'message'=>trans('messages.unique_email_required')],200);
        }
        if(!empty($checkmobile)){
            return response()->json(['status'=>0,'message'=>trans('messages.unique_mobile')],200);
        }
        if(!empty($checkslug)){
            return response()->json(['status'=>0,'message'=>trans('messages.unique_slug')],200);
        }
        $data = helper::vendor_register($request->name, $request->email, $request->mobile, hash::make($request->password), $request->token,$request->slug,'','', $request->country_id, $request->city_id,$request->store_id,$request->store_type);
        
        if (!empty($data)) {
            $newuser = User::select('id','name','email','mobile','image','login_type')->where('id',$data)->first();
            $newuser->image = helper::image_path($newuser->image);

            return response()->json(['status'=>1,'message'=>trans('messages.success'),'data'=>$newuser],200);
        } else {
            return response()->json(['status'=>0,'message'=>trans('messages.wrong')],200);
        }
    }
    public function forgotpassword(Request $request)
    {
        if ($request->email == "") {
            return response()->json(["status" => 0, "message" => trans('messages.email_required')], 200);
        }
        $checkuser = User::where('email', $request->email)->where('is_available', 1)->first();
        if (!empty($checkuser)) {
            $password = substr(str_shuffle($checkuser->password), 1, 6);
            $check_send_mail = Helper::send_pass($request->email, $checkuser->name, $password, '1');
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

    public function edit_profile(Request $request)
    {
        if($request->vendor_id == ""){
            return response()->json(["status"=>0,"message"=>trans('messages.vendor_id_required')],200);
        }
        if ($request->name == "") {
            return response()->json(["status" => 0, "message" => trans('messages.name_required')], 200);
        }
        if ($request->email == "") {
            return response()->json(["status" => 0, "message" => trans('messages.email_required')], 200);
        }
        if ($request->mobile == "") {
            return response()->json(["status" => 0, "message" => trans('messages.unique_mobile_required')], 200);
        }
        $edituser = User::where('id', $request->vendor_id)->first();
        $checkmobile = User::where('id','!=',$edituser->id)->where('mobile',$request->mobile)->first();
        $checkemail = User::where('id','!=',$edituser->id)->where('email',$request->email)->first();
        $profileImage="";
        if(!empty($checkemail)){
            return response()->json(['status'=>0,'message'=>trans('messages.unique_email_required')],200);
        }
        if(!empty($checkmobile)){
            return response()->json(['status'=>0,'message'=>trans('messages.unique_mobile')],200);
        }
      
        if(!empty($edituser))
        {
            $edituser->name = $request->name;
            $edituser->email = $request->email;
            $edituser->mobile = $request->mobile;
            
            if ($request->has('profile')) {
                $validator = Validator::make($request->all(), [
                    'profile' => 'image|max:'.helper::imagesize().'|'.helper::imageext(),
                ], [
                    'profile.max' => trans('messages.image_size_message'),
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->with('error', trans('messages.image_size_message'). ' ' . helper::appdata('')->image_size .' ' .'MB');
                }
                if (file_exists(storage_path('app/public/admin-assets/images/profile/' . $edituser->image))) {
                    unlink(storage_path('app/public/admin-assets/images/profile/' . $edituser->image));
                }
                $edit_image = $request->file('profile');
                $profileImage = 'profile-' . uniqid() . "." . $edit_image->getClientOriginalExtension();
                $edit_image->move(storage_path('app/public/admin-assets/images/profile/'), $profileImage);
                $edituser->image = $profileImage;
            }
            $edituser->update();
            return response()->json(['status' => 1, 'message' => trans('messages.success'),"name"=>$request->name,"email"=>$request->email,"mobile"=>$request->mobile,"login_type"=>$edituser->login_type,"image"=>helper::image_path( $edituser->image)], 200);
        }
        else
        {
            return response()->json(["status" => 0, "message" => trans('messages.vendor_not_exist')], 200);
        }
    }

    public function change_password(Request $request)
    {
        if($request->vendor_id == ""){
            return response()->json(["status"=>0,"message"=>trans('messages.vendor_id_required')],200);
        }
        if ($request->current_password == "") {
            return response()->json(["status" => 0, "message" => trans('messages.cuurent_password_required')], 200);
        }
        if ($request->new_password == "") {
            return response()->json(["status" => 0, "message" => trans('messages.new_password_required')], 200);
        }
        if ($request->confirm_password == "") {
            return response()->json(["status" => 0, "message" => trans('messages.confirm_password_required')], 200);
        }
        $user = User::where('id',$request->vendor_id)->first();
        if (Hash::check($request->current_password, $user->password)) {
            if ($request->current_password == $request->new_password) {
                return response()->json(['status' => 0, 'message' => trans('messages.new_old_password_diffrent')], 200);
            } else {
                if ($request->new_password == $request->confirm_password) {
                    $user->password = Hash::make($request->new_password);
                    $user->update();
                    return response()->json(['status' => 1, 'message' => trans('messages.success')], 200);
                } else {
                    return response()->json(['status' => 0, 'message' => trans('messages.new_confirm_password_inccorect')], 200);
                }
            }
        } else {
            return response()->json(['status' => 0, 'message' => trans('messages.old_password_incorect')], 200);
        }
    }
    public function getcountry()
    {
        $countrylist = Country::select('id','name')->where('is_deleted', '2')->orderBy('reorder_id')->get();
        return response()->json(['status' => 1, 'message' => trans('messages.success'), 'data' => $countrylist], 200);
    }

    public function getcity(Request $request)
    {
        if($request->country_id == ""){
            return response()->json(["status"=>0,"message"=>trans('messages.country_id_required')],200);
        }
        $citylist = City::select('id','country_id','city')->where('country_id',$request->country_id)->where('is_deleted', '2')->orderBy('reorder_id')->get();
    
        if (empty($citylist)) {
            return response()->json(['status'=>0,'message'=>trans('lables.nodata_found')],200);   
        } else {
            return response()->json(['status'=>1,'message'=>trans('messages.success'),'data'=>$citylist],200);
        }
    }

    public function deletevendor(Request $request)
    {
        if($request->vendor_id == ""){
            return response()->json(["status"=>0,"message"=>trans('messages.vendor_id_required')],200);
        }

        $user = User::where('id', $request->vendor_id)->first();
        $user->is_deleted = 1;
        $user->slug = '';
        $user->update();
        $emaildata = helper::emailconfigration(helper::appdata("")->id);
        Config::set('mail', $emaildata);
        helper::send_mail_delete_account($user);
        return response()->json(['status' => 1, 'message' => trans('messages.success')], 200);
    }

}
