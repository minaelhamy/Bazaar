<?php

namespace App\Http\Controllers\addons\included;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WhatsappMessage;
use Illuminate\Support\Facades\Auth;

class WhatsappmessageController extends Controller
{
    public function index()
    {
        return view('admin.included.whatsapp_message.setting_form');
    }

    public function order_message_update(Request $request)
    {
        try {
            if (Auth::user()->type == 4) {
                $vendor_id = Auth::user()->vendor_id;
            } else {
                $vendor_id = Auth::user()->id;
            }
            $order_message = WhatsappMessage::where('vendor_id', $vendor_id)->first();
            if (empty($order_message)) {
                $order_message = new WhatsappMessage();
                $order_message->vendor_id = $vendor_id;
            }
            if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1)) {
                $order_message->item_message = $request->item_message;
                $order_message->order_whatsapp_message = $request->order_whatsapp_message;
                $order_message->order_created = isset($request->order_created) ? 1 : 2;
            }
            $order_message->whatsapp_mobile_view_on_off = isset($request->whatsapp_mobile_view_on_off) ? 1 : 2;
            $order_message->whatsapp_chat_on_off = isset($request->whatsapp_chat_on_off) ? 1 : 2;
            $order_message->whatsapp_chat_position = $request->whatsapp_chat_position;
            if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1)) {
                $order_message->whatsapp_number = $request->whatsapp_number;
            }
            $order_message->save();
            return redirect()->back()->with('success', trans('messages.success'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function status_message(Request $request)
    {
        try {
            if (Auth::user()->type == 4) {
                $vendor_id = Auth::user()->vendor_id;
            } else {
                $vendor_id = Auth::user()->id;
            }
            $about = WhatsappMessage::where('vendor_id', $vendor_id)->first();
            if (empty($about)) {
                $about = new WhatsappMessage();
                $about->vendor_id = $vendor_id;
            }
            $about->order_status_message = $request->order_status_message;
            $about->status_change = isset($request->status_change) ? 1 : 2;
            $about->save();
            return redirect()->back()->with('success', trans('messages.success'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', trans('messages.wrong'));
        }
    }

    public function business_api(Request $request)
    {
        try {
            if (Auth::user()->type == 4) {
                $vendor_id = Auth::user()->vendor_id;
            } else {
                $vendor_id = Auth::user()->id;
            }
            $about = WhatsappMessage::where('vendor_id', $vendor_id)->first();
            if (empty($about)) {
                $about = new WhatsappMessage();
                $about->vendor_id = $vendor_id;
            }
            $about->whatsapp_number = $request->whatsapp_number;
            $about->whatsapp_phone_number_id = $request->whatsapp_phone_number_id;
            $about->whatsapp_access_token = $request->whatsapp_access_token;
            $about->message_type = $request->message_type;
            $about->save();
            return redirect()->back()->with('success', trans('messages.success'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', trans('messages.wrong'));
        }
    }
}
