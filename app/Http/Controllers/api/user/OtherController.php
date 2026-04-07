<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\About;
use App\Models\Timing;
use App\Models\Terms;
use App\Models\Settings;
use App\Models\Privacypolicy;
use App\Models\Contact;

class OtherController extends Controller
{

    public function cmspages(Request $request)
    {
        if ($request->vendor_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.vendor_id_required')], 200);
        }
        $terms = Terms::select('terms_content')->where('vendor_id', $request->vendor_id)->first();
        $aboutus = About::select('about_content')->where('vendor_id', $request->vendor_id)->first();
        $privacypolicy = Privacypolicy::select('privacypolicy_content')->where('vendor_id', $request->vendor_id)->first();
        return response()->json(["status" => 1, "message" => trans('messages.success'), 'privecypolicy' => $privacypolicy->privacypolicy_content, 'termscondition' => $terms->terms_content, 'aboutus' => $aboutus->about_content], 200);
    }
    public function contact_detail(Request $request)
    {
        if ($request->vendor_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.vendor_id_required')], 200);
        }
        $vendor_info = Settings::select('address','contact','email')->where('vendor_id',$request->vendor_id)->first();
        $getworkinghours = Timing::where('vendor_id',$request->vendor_id)->get();
        $day = date('l', time());
        $todayworktime = Timing::where('vendor_id',$request->vendor_id)->where('day',$day)->first();
        return response()->json(["status" => 1, "message" => trans('messages.success'),'vendor_info' =>$vendor_info,'todayworktime'=>$todayworktime,'getworkinghours'=>$getworkinghours], 200);
    }
    public function contact(Request $request)
    {
        if ($request->vendor_id == "") {
            return response()->json(["status" => 0, "message" => trans('messages.vendor_id_required')], 200);
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
        if ($request->message == "") {
            return response()->json(["status" => 0, "message" => trans('messages.message_required')], 200);
        }
        $contact = new Contact();
        $contact->vendor_id = $request->vendor_id;
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->mobile = $request->mobile;
        $contact->message = $request->message;
        $contact->save();
        return response()->json(["status" => 1, "message" => trans('messages.success')], 200);
    }
}
