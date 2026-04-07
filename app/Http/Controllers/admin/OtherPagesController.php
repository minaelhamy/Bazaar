<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Privacypolicy;
use App\Models\Terms;
use App\Models\About;
use App\Models\Subscriber;
use App\Models\Country;
use App\Models\Settings;
use App\Models\City;
use Illuminate\Support\Facades\Auth;
use App\Models\Faq;
use App\Models\Contact;
use App\Models\Shipping;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Jorenvh\Share\ShareFacade;

class OtherPagesController extends Controller
{
    public function share()
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $storeinfo = User::select('slug', 'custom_domain')->where('id', $vendor_id)->first();
        if ($storeinfo->custom_domain == null) {
            $url = URL::to('/' . $storeinfo->slug);
        } else {
            $url = 'https://' . $storeinfo->custom_domain;
        }
        $shareComponent = ShareFacade::page(
            $url
        )
            ->facebook()
            ->twitter()
            ->linkedin()
            ->telegram()
            ->whatsapp()
            ->reddit();
        return view('admin.otherpages.share', compact('shareComponent', 'storeinfo', 'url'));
    }
    // -----------------------------------------------------------------
    // -------------------  Privacy-Policy  ----------------------------
    // -----------------------------------------------------------------
    public function privacypolicy()
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $getprivacypolicy = Privacypolicy::where('vendor_id', $vendor_id)->first();
        return view('admin.otherpages.privacypolicy', compact('getprivacypolicy'));
    }
    public function privacypolicy_update(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }

        $privacypolicy = Privacypolicy::where('vendor_id', $vendor_id)->first();
        if (empty($privacypolicy)) {
            $privacypolicy = new Privacypolicy();
            $privacypolicy->vendor_id = $vendor_id;
        }
        $privacypolicy->privacypolicy_content = $request->privacypolicy;
        $privacypolicy->save();
        return redirect('admin/privacy-policy')->with('success', trans('messages.success'));
    }
    // -----------------------------------------------------------------
    // ------------------- Terms-Condition -----------------------------
    // -----------------------------------------------------------------
    public function termscondition()
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $gettermscondition = Terms::where('vendor_id', $vendor_id)->first();
        return view('admin.otherpages.termscondition', compact('gettermscondition'));
    }
    public function termscondition_update(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }

        $termscondition = Terms::where('vendor_id', $vendor_id)->first();
        if (empty($termscondition)) {
            $termscondition = new Terms();
            $termscondition->vendor_id = $vendor_id;
        }
        $termscondition->terms_content = $request->termscondition;
        $termscondition->save();
        return redirect('admin/terms-conditions')->with('success', trans('messages.success'));
    }
    // -----------------------------------------------------------------
    // -------------------------- About us -----------------------------
    // -----------------------------------------------------------------
    public function aboutus()
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $getaboutus = About::where('vendor_id', $vendor_id)->first();
        return view('admin.otherpages.aboutus', compact('getaboutus'));
    }
    public function aboutus_update(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }

        $aboutus = About::where('vendor_id', $vendor_id)->first();
        if (empty($aboutus)) {
            $aboutus = new About();
            $aboutus->vendor_id = $vendor_id;
        }
        $aboutus->about_content = $request->aboutus;
        $aboutus->save();
        return redirect('admin/aboutus')->with('success', trans('messages.success'));
    }
    // -----------------------------------------------------------------
    // ---------------------------- FAQs -------------------------------
    // -----------------------------------------------------------------
    public function faq_index(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $faqs = Faq::where('vendor_id', $vendor_id)->orderBy('reorder_id')->get();
        return view('admin.faqs.index', compact('faqs'));
    }
    public function faq_add(Request $request)
    {
        return view('admin.faqs.add');
    }
    public function faq_save(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }

        $faqs = new Faq();
        $faqs->vendor_id = $vendor_id;
        $faqs->question = $request->question;
        $faqs->answer = $request->answer;
        $faqs->save();
        return redirect('/admin/faqs')->with('success', trans('messages.success'));
    }
    public function faq_edit(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $getfaq = Faq::where('vendor_id', $vendor_id)->where('id', $request->id)->first();
        return view('admin.faqs.edit', compact('getfaq'));
    }
    public function faq_update(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $getfaq = Faq::where('id', $request->id)->first();
        $getfaq->vendor_id = $vendor_id;
        $getfaq->question = $request->question;
        $getfaq->answer = $request->answer;
        $getfaq->update();
        return redirect('/admin/faqs')->with('success', trans('messages.success'));
    }
    public function faq_delete(Request $request)
    {
        $deletefaq = Faq::where('id', $request->id)->first();
        $deletefaq->delete();
        return redirect('/admin/faqs')->with('success', trans('messages.success'));
    }

    public function faq_bulk_delete(Request $request)
    {
        foreach ($request->id as $id) {
            $deletefaq = Faq::where('id', $id)->first();
            $deletefaq->delete();
        }
        return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200); 
    }

    public function reorder_faq(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $getfaqs =  Faq::where('vendor_id', $vendor_id)->get();
        foreach ($getfaqs as $faq) {
            foreach ($request->order as $order) {
                $faq = Faq::where('id', $order['id'])->first();
                $faq->reorder_id = $order['position'];
                $faq->save();
            }
        }
        return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
    }
    // -----------------------------------------------------------------
    // ------------------------- Subscribers ---------------------------
    // -----------------------------------------------------------------
    public function subscribers(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $getsubscribers = Subscriber::where('vendor_id', $vendor_id)->orderByDesc('id')->get();
        return view('admin.subscriber.index', compact('getsubscribers'));
    }

    public function subscribers_delete(Request $request)
    {
        $subscriber = Subscriber::find($request->id);
        if (!empty($subscriber)) {
            $subscriber->delete();
            return redirect('/admin/subscribers')->with('success', trans('messages.success'));
        }
        return redirect('/admin/subscribers')->with('error', trans('messages.wrong'));
    }
    public function subscribers_bulk_delete(Request $request)
    {
        if (!empty($request->id)) {
            foreach ($request->id as $id) {
                $subscriber = Subscriber::find($id);
                $subscriber->delete();
            }
            return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
        }else{
            return redirect('/admin/subscribers')->with('error', trans('messages.wrong'));
        }
    }
    // -----------------------------------------------------------------
    // ------------------------- Inquiries -----------------------------
    // -----------------------------------------------------------------
    public function inquiries(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $getinquiries = Contact::where('vendor_id', $vendor_id)->where('product_id', null)->orderByDesc('id')->get();
        return view('admin.inquiries.index', compact('getinquiries'));
    }

    public function inquiries_delete(Request $request)
    {
        $inquiry = Contact::find($request->id);
        if (!empty($inquiry)) {
            $inquiry->delete();
            return redirect('/admin/inquiries')->with('success', trans('messages.success'));
        }
        return redirect('/admin/inquiries')->with('error', trans('messages.wrong'));
    }
    public function inquiries_bulk_delete(Request $request)
    {
        if (!empty($request->id)) {
            foreach ($request->id as $id) {
                $inquiry = Contact::find($id);
                $inquiry->delete();
            }
            return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
        }
         return response()->json(['status' => 0, 'msg' => trans('messages.error')], 200);
    }

    public function change_status(Request $request)
    {
        $inquiry = Contact::find($request->id);
        $inquiry->status = $request->status;
        $inquiry->update();
        return redirect('/admin/inquiries')->with('success', trans('messages.success'));
    }
    // -----------------------------------------------------------------
    // ------------------------- Countries -----------------------------
    // -----------------------------------------------------------------
    public function countries(Request $request)
    {
        $allcontries = Country::where('is_deleted', 2)->orderBy('reorder_id')->get();
        return view('admin.country.index', compact('allcontries'));
    }

    public function add_country()
    {
        return view('admin.country.add');
    }

    public function save_country(Request $request)
    {
        $country = new Country();
        $country->name = $request->name;
        $country->save();
        return redirect('/admin/countries')->with('success', trans('messages.success'));
    }

    public function edit_country(Request $request)
    {
        $editcountry = Country::where('id', $request->id)->first();
        return view('admin.country.edit', compact('editcountry'));
    }

    public function update_country(Request $request)
    {
        $editcountry = Country::where('id', $request->id)->first();
        $editcountry->name = $request->name;
        $editcountry->update();
        return redirect('/admin/countries')->with('success', trans('messages.success'));
    }

    public function delete_country(Request $request)
    {
        $country = Country::where('id', $request->id)->first();
        $country->is_deleted = 1;
        $country->update();
        return redirect('/admin/countries')->with('success', trans('messages.success'));
    }

    public function statuschange_country(Request $request)
    {
        $country = Country::where('id', $request->id)->first();
        $country->is_available = $request->status;
        $country->update();
        return redirect('/admin/countries')->with('success', trans('messages.success'));
    }

    public function reorder_city(Request $request)
    {
        $getcity = Country::where('is_deleted', 2)->get();
        foreach ($getcity as $city) {
            foreach ($request->order as $order) {
                $city = Country::where('id', $order['id'])->first();
                $city->reorder_id = $order['position'];
                $city->save();
            }
        }
        return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
    }

    public function bulk_delete_country(Request $request)
    {
        foreach ($request->id as $id) {
            $country = Country::where('id', $id)->first();
            $country->is_deleted = 1;
            $country->update();
        }
        return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);

    }

    // -----------------------------------------------------------------
    // --------------------------- Cities ------------------------------
    // -----------------------------------------------------------------
    public function cities(Request $request)
    {
        $allcities = City::with('country_info')->where('is_deleted', 2)->orderBy('reorder_id')->get();
        return view('admin.city.index', compact('allcities'));
    }

    public function add_city(Request $request)
    {
        $allcountry = Country::where('is_deleted', 2)->get();
        return view('admin.city.add', compact('allcountry'));
    }

    public function save_city(Request $request)
    {
        $city = new City();
        $city->country_id = $request->country;
        $city->city = $request->name;
        $city->save();
        return redirect('/admin/cities')->with('success', trans('messages.success'));
    }

    public function edit_city(Request $request)
    {
        $allcountry = Country::where('is_deleted', 2)->get();
        $editcity = City::where('id', $request->id)->first();
        return view('admin.city.edit', compact('editcity', 'allcountry'));
    }

    public function update_city(Request $request)
    {
        $editcity = City::where('id', $request->id)->first();
        $editcity->country_id = $request->country;
        $editcity->city = $request->name;
        $editcity->update();
        return redirect('/admin/cities')->with('success', trans('messages.success'));
    }

    public function delete_city(Request $request)
    {
        $city = City::where('id', $request->id)->first();
        $city->is_deleted = 1;
        $city->update();
        return redirect('/admin/cities')->with('success', trans('messages.success'));
    }

    public function statuschange_city(Request $request)
    {
        $city = City::where('id', $request->id)->first();
        $city->is_available = $request->status;
        $city->update();
        return redirect('/admin/cities')->with('success', trans('messages.success'));
    }

    public function reorder_area(Request $request)
    {
        $getarea = City::with('country_info')->where('is_deleted', 2)->get();
        foreach ($getarea as $area) {
            foreach ($request->order as $order) {
                $area = City::where('id', $order['id'])->first();
                $area->reorder_id = $order['position'];
                $area->save();
            }
        }
        return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
    }

    public function bulk_delete_city(Request $request)
    {
        foreach ($request->id as $id) {
            $city = City::where('id', $id)->first();
            $city->is_deleted = 1;
            $city->update();
        }
        return response()->json(['status' => 1, 'msg' => trans('messages.success')], 200);
    }
    
    public function refund_policy(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $policy = Settings::where('vendor_id', $vendor_id)->first();
        return view('admin.otherpages.refundpolicy', compact('policy'));
    }

    public function refund_policy_update(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $policy = Settings::where('vendor_id', $vendor_id)->first();
        $policy->refund_policy = $request->refund_policy;
        $policy->update();
        return redirect('/admin/refund_policy')->with('success', trans('messages.success'));
    }


    /*===================================== Shipping ==================================*/
    public function shippingindex(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $content = Settings::where('vendor_id', $vendor_id)->first();
        $allshippingcontent = Shipping::where('vendor_id', $vendor_id)->orderBy('reorder_id')->get();
        return view('admin.shipping.index', compact('content', 'allshippingcontent'));
    }
    public function savecontent(Request $request)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $newcontent = Settings::where('vendor_id', $vendor_id)->first();
        $newcontent->min_order_amount_for_free_shipping = $request->min_order_amount_for_free_shipping;
        $newcontent->shipping_charges = $request->shipping_charges;
        $newcontent->shipping_area = isset($request->shipping_area) ? 1 : 2;
        $newcontent->save();
        return redirect('admin/shipping')->with('success', trans('messages.success'));
    }
}
