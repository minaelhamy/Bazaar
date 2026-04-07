<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\helper;
use App\Models\User;
use App\Models\PricingPlan;
use App\Models\Country;
use App\Models\City;
use App\Models\CustomDomain;
use App\Models\Settings;
use App\Models\StoreCategory;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Lunaweb\RecaptchaV3\Facades\RecaptchaV3;


class VendorController extends Controller
{
    public function index(Request $request)
    {
        $getuserslist = User::where('type', 2)->where('is_deleted', 2)->orderBy('id')->get();
        return view('admin.user.index', compact('getuserslist'));
    }
    public function add(Request $request)
    {
        $countries = Country::where('Is_deleted', 2)->where('is_available', 1)->orderBy('reorder_id')->get();
        $stores = StoreCategory::where('is_deleted', 2)->where('is_available', 1)->orderBy('reorder_id')->get();
        return view('admin.user.add', compact('countries', 'stores'));
    }
    public function edit($id)
    {
        $getuserdata = User::where('id', $id)->first();
        $getplanlist = PricingPlan::where('is_available', 1)->orderBy('reorder_id')->get();
        $countries = Country::where('Is_deleted', 2)->where('is_available', 1)->orderBy('reorder_id')->get();
        $stores = StoreCategory::where('is_deleted', 2)->where('is_available', 1)->orderBy('reorder_id')->get();
        return view('admin.user.edit', compact('getuserdata', 'getplanlist', 'countries', 'stores'));
    }
    public function update(Request $request)
    {
        $edituser = User::where('id', $request->id)->first();
        $usersetting = Settings::where('vendor_id', $request->id)->first();
        $validatoremail = Validator::make(['email' => $request->email], [
            'email' => [
                'required',
                'email',
                Rule::unique('users')->whereIn('type', [1, 2, 4])->where('is_deleted', 2)->ignore($edituser->id),
            ]
        ]);
        if ($validatoremail->fails()) {
            return redirect()->back()->with('error', trans('messages.unique_email'));
        }
        $validatormobile = Validator::make(['mobile' => $request->mobile], [
            'mobile' => [
                'required',
                'numeric',
                Rule::unique('users')->whereIn('type', [1, 2, 4])->where('is_deleted', 2)->ignore($edituser->id),
            ]
        ]);
        if ($validatormobile->fails()) {
            return redirect()->back()->with('error', trans('messages.unique_mobile'));
        }

        if (@helper::checkaddons('unique_slug')) {
            if (@Auth::user()->type == 2) {
                $validatorslug = Validator::make(['slug' => $request->slug], [
                    'slug' => [
                        'required',
                        Rule::unique('users')->where('type', 2)->where('is_deleted', 2)->ignore($edituser->id),
                    ]
                ]);
                if ($validatorslug->fails()) {
                    return redirect()->back()->with('error', trans('messages.unique_slug'));
                }
            }
        }
        $request->validate([

            'profile' => 'max:' . helper::imagesize() . '|' . helper::imageext(),
        ], [
            'profile.max' => trans('messages.image_size_message') . ' ' . helper::appdata('')->image_size . ' ' . 'MB',
        ]);
        $edituser->name = $request->name;
        $edituser->email = $request->email;
        $edituser->mobile = $request->mobile;
        $edituser->country_id = $request->country;
        $edituser->city_id = $request->city;
        if ($request->store != null && $request->store != "") {
            $edituser->store_id = $request->store;
        }
        if ($request->has('profile')) {

            if (file_exists(storage_path('app/public/admin-assets/images/profile/' . $edituser->image))) {
                unlink(storage_path('app/public/admin-assets/images/profile/' . $edituser->image));
            }
            $edit_image = $request->file('profile');
            $profileImage = 'profile-' . uniqid() . "." . $edit_image->getClientOriginalExtension();
            $edit_image->move(storage_path('app/public/admin-assets/images/profile/'), $profileImage);
            $edituser->image = $profileImage;
        }
        if (!isset($request->allow_store_subscription)) {
            if ($request->plan != null && !empty($request->plan)) {
                $plan = PricingPlan::where('id', $request->plan)->first();
                $edituser->plan_id = $plan->id;
                $edituser->purchase_amount = $plan->price;
                $edituser->purchase_date = date("Y-m-d h:i:sa");
                $transaction = new Transaction();
                $transaction->vendor_id = $edituser->id;
                $transaction->plan_id = $plan->id;
                $transaction->plan_name = $plan->name;
                $transaction->payment_type = "0";
                $transaction->transaction_number = Str::upper(Str::random(8));
                $transaction->payment_id = "";
                $transaction->amount = $plan->price;

                $tax_amount = [];
                $tax_name = [];
                $totaltax = 0;

                if ($plan->tax != null && $plan->tax != "") {
                    $tax_detail = helper::gettax($plan->tax);

                    foreach ($tax_detail as $tax) {
                        if ($tax->type == 1) {
                            $tax_amount[] = $tax->tax;
                        } else {
                            $tax_amount[] = ($tax->tax / 100) * $plan->price;
                        }

                        $tax_name[] = $tax->name;
                    }

                    foreach ($tax_amount as $item) {
                        $totaltax += (float)$item;
                    }
                }

                // Use implode safely with initialized arrays
                $transaction->tax = implode('|', $tax_amount);
                $transaction->tax_name = implode('|', $tax_name);
                $transaction->grand_total = $plan->price == 0 ? 0 : ($plan->price) +  $totaltax;
                $transaction->service_limit = $plan->order_limit;
                $transaction->appoinment_limit = $plan->appointment_limit;
                $transaction->status = 2;
                $transaction->purchase_date = date("Y-m-d h:i:sa");
                $transaction->expire_date = helper::get_plan_exp_date($plan->duration, $plan->days);
                $transaction->duration = $plan->duration;
                $transaction->days = $plan->days;
                $transaction->custom_domain = $plan->custom_domain;
                $transaction->themes_id = $plan->themes_id;
                $transaction->google_analytics = $plan->google_analytics;
                $transaction->pos = $plan->pos;
                $transaction->vendor_app = $plan->vendor_app;
                $transaction->customer_app = $plan->customer_app;
                $transaction->role_management = $plan->role_management;
                $transaction->pwa = $plan->pwa;
                $transaction->coupons = $plan->coupons;
                $transaction->blogs = $plan->blogs;
                $transaction->google_login = $plan->google_login;
                $transaction->facebook_login = $plan->facebook_login;
                $transaction->sound_notification = $plan->sound_notification;
                $transaction->whatsapp_message = $plan->whatsapp_message;
                $transaction->telegram_message = $plan->telegram_message;
                $transaction->features = $plan->features;
                $transaction->save();
                if ($plan->custom_domain == "2") {
                    User::where('vendor_id', Auth::user()->id)->update(['custom_domain' => "-"]);
                }
                if ($plan->custom_domain == "1") {
                    $checkdomain = CustomDomain::where('vendor_id', Auth::user()->id)->first();
                    if (@$checkdomain->status == 2) {
                        User::where('vendor_id', Auth::user()->id)->update(['custom_domain' => $checkdomain->current_domain]);
                    }
                }
            }
        }
        if (Str::contains(request()->url(), 'user')) {
            if (isset($request->allow_store_subscription)) {
                $edituser->plan_id = "";
                $edituser->purchase_amount = "";
                $edituser->purchase_date = "";
            }
            $edituser->allow_without_subscription = isset($request->allow_store_subscription) ? 1 : 2;
            $edituser->available_on_landing = isset($request->show_landing_page) ? 1 : 2;
        }
        if (!empty($request->slug)) {
            $edituser->slug = $request->slug;
        }
        $edituser->update();
        if ($request->product_type != null && $request->product_type != "") {
            $usersetting->product_type = $request->product_type;
            $usersetting->update();
        }
        if ($request->has('updateprofile') && $request->updateprofile == 1) {
            return redirect('admin/settings')->with('success', trans('messages.success'));
        } else {
            return redirect('admin/users')->with('success', trans('messages.success'));
        }
    }
    public function status(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $user->is_available = $request->status;
        $user->update();
        if ($request->status == 2) {
            $emaildata = helper::emailconfigration(helper::appdata('')->id);
            Config::set('mail', $emaildata);
            helper::send_mail_vendor_block($user);
        }
        return redirect('admin/users')->with('success', trans('messages.success'));
    }

    public function vendor_login(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        session()->put('vendor_login', Auth::user()->id);
        Auth::login($user);
        return redirect('admin/dashboard');
    }
    public function admin_back(Request $request)
    {
        $getuser = User::where('id', session()->get('vendor_login'))->first();
        Auth::login($getuser);
        session()->forget('vendor_login');
        return redirect('admin/users');
    }
    // ------------------------------------------------------------------------
    // ----------------- registration & Auth pages ----------------------------
    // ------------------------------------------------------------------------
    public function register()
    {
        if (helper::appdata('')->vendor_register == 2) {
            abort(404);
        }
        helper::language(1);
        $countries = Country::where('Is_deleted', 2)->where('is_available', 1)->orderBy('reorder_id')->get();
        $stores = StoreCategory::where('is_available', 1)->where('is_deleted', 2)->orderBy('reorder_id')->get();
        return view('admin.auth.register', compact('countries', 'stores'));
    }

    public function register_vendor(Request $request)
    {

        $validatoremail = Validator::make(['email' => $request->email], [
            'email' => [
                'required',
                'email',
                Rule::unique('users')->whereIn('type', [1, 2, 4])->where('is_deleted', 2),
            ]
        ]);
        if ($validatoremail->fails()) {
            return redirect()->back()->with('error', trans('messages.unique_email'));
        }
        $validatormobile = Validator::make(['mobile' => $request->mobile], [
            'mobile' => [
                'required',
                'numeric',
                Rule::unique('users')->whereIn('type', [1, 2, 4])->where('is_deleted', 2),
            ]
        ]);
        if ($validatormobile->fails()) {
            return redirect()->back()->with('error', trans('messages.unique_mobile'));
        }
        $validatorslug = Validator::make(['slug' => $request->slug], [
            'slug' => [
                'required',
                Rule::unique('users')->where('type', 2)->where('is_deleted', 2),
            ]
        ]);
        if (@helper::checkaddons('unique_slug')) {
            if ($validatorslug->fails()) {
                return redirect()->back()->with('error', trans('messages.unique_slug'));
            }
        }


        if (@Auth::user()->type != 1) {
            if (@helper::checkaddons('google_recaptcha')) {

                if (helper::appdata('')->recaptcha_version == 'v2') {
                    $request->validate([
                        'g-recaptcha-response' => 'required'
                    ], [
                        'g-recaptcha-response.required' => 'The g-recaptcha-response field is required.'
                    ]);
                }

                if (helper::appdata('')->recaptcha_version == 'v3') {
                    $score = RecaptchaV3::verify($request->get('g-recaptcha-response'), 'contact');
                    if ($score <= helper::appdata('')->score_threshold) {
                        return redirect()->back()->with('error', 'You are most likely a bot');
                    }
                }
            }
        }

        $data = helper::vendor_register($request->name, $request->email, $request->mobile, hash::make($request->password), '', $request->slug, '', '', $request->country, $request->city, $request->store, $request->product_type);
        if (@Auth::user()->type == 1) {
            return redirect('admin/users')->with('success', trans('messages.success'));
        } else {
            session()->put('user_login', 1);
            $newuser = User::select('id', 'name', 'email', 'mobile', 'image')->where('id', $data)->first();
  
            Auth::login($newuser);
            return redirect('admin/dashboard')->with('success', trans('messages.success'));
        }
    }
    public function forgot_password()
    {
        helper::language(1);
        return view('admin.auth.forgotpassword');
    }
    public function send_password(Request $request)
    {
        $checkuser = User::where('email', $request->email)->where('is_available', 1)->where('is_deleted', 2)->whereIn('type', [1, 2])->first();
        if (!empty($checkuser)) {
            $password = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, 6);
            $emaildata = helper::emailconfigration(helper::appdata('')->id);
            Config::set('mail', $emaildata);
            $pass = helper::send_pass($request->email, $checkuser->name, $password, '1');
            if ($pass == 1) {
                $checkuser->password = Hash::make($password);
                $checkuser->save();
                return redirect('admin')->with('success', trans('messages.success'));
            } else {
                return redirect('admin/forgot_password')->with('error', trans('messages.wrong'));
            }
        } else {
            return redirect()->back()->with('error', trans('messages.invalid_user'));
        }
    }
    public function change_password(Request $request)
    {
        if ($request->type != "" || $request->type != null) {
            if ($request->new_password == $request->confirm_password) {
                $changepassword = User::where('id', $request->modal_vendor_id)->first();
                $changepassword->password = Hash::make($request->new_password);
                $changepassword->update();
                $emaildata = helper::emailconfigration(helper::appdata("")->id);
                Config::set('mail', $emaildata);
                helper::send_pass($changepassword->email, $changepassword->name, $request->new_password, helper::appdata("")->logo);
                return redirect()->back()->with('success', trans('messages.success'));
            } else {
                return redirect()->back()->with('error', trans('messages.new_confirm_password_inccorect'));
            }
        } else {

            if (Hash::check($request->current_password, Auth::user()->password)) {
                if ($request->current_password == $request->new_password) {
                    return redirect()->back()->with('error', trans('messages.new_old_password_diffrent'));
                } else {
                    if ($request->new_password == $request->confirm_password) {
                        $changepassword = User::where('id', Auth::user()->id)->first();
                        $changepassword->password = Hash::make($request->new_password);
                        $changepassword->update();
                        return redirect()->back()->with('success', trans('messages.success'));
                    } else {
                        return redirect()->back()->with('error', trans('messages.new_confirm_password_inccorect'));
                    }
                }
            } else {
                return redirect()->back()->with('error', trans('messages.old_password_incorect'));
            }
        }
    }
    public function is_allow(Request $request)
    {
        $status = User::where('id', $request->id)->update(['allow_without_subscription' => $request->status]);
        if ($status) {
            return 1;
        } else {
            return 0;
        }
    }
    public function getcity(Request $request)
    {
        try {
            $data['city'] = City::select("id", "city")->where('country_id', $request->country)->where('is_available', 1)->where('is_deleted', 2)->orderBy('reorder_id')->get();
            return response()->json($data);
        } catch (\Throwable $th) {
            return response()->json(['status' => 0, 'message' => trans('messages.wrong')], 200);
        }
    }
    public function deletevendor(Request $request)
    {

        $user = User::where('id', $request->id)->first();
        $user->is_deleted = 1;
        $user->slug = '';
        $user->update();
        $emaildata = helper::emailconfigration(1);
        Config::set('mail', $emaildata);
        helper::send_mail_delete_account($user);
        return redirect('admin/users')->with('success', trans('messages.success'));
    }
}
