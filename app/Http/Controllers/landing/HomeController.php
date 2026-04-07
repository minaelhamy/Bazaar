<?php

namespace App\Http\Controllers\landing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Features;
use App\Models\PricingPlan;
use App\Models\User;
use App\Models\Testimonials;
use App\Models\Blog;
use App\Models\Subscriber;
use App\Models\Category;
use App\Models\StoreCategory;
use App\Models\Item;
use App\Models\Banner;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\Settings;
use App\Models\Country;
use App\Models\City;
use App\Models\Terms;
use App\Models\About;
use App\Models\Privacypolicy;
use App\Models\Promotionalbanner;
use App\Models\Faq;
use App\Models\AppSettings;
use App\Models\Contact;
use App\Models\Theme;
use App\Helpers\helper;
use App\Models\HowWorks;
use App\Models\FunFact;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Lunaweb\RecaptchaV3\Facades\RecaptchaV3;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // if the current host contains the website domain
        $host = $_SERVER['HTTP_HOST'];
        if ($host  ==  env('WEBSITE_HOST')) {
            $admindata = User::where('type', 1)->first();
            $features = Features::where('vendor_id', $admindata->id)->orderBy('reorder_id')->get();
            $planlist = PricingPlan::where('is_available', 1)->where('vendor_id', null)->orderBy('reorder_id')->get();
            $testimonials = Testimonials::where('vendor_id', $admindata->id)->orderBy('reorder_id')->get();
            $blogs = Blog::where('vendor_id', $admindata->id)->orderBy('reorder_id')->take(6)->get();
            $settingdata = Settings::where('vendor_id', $admindata->id)->first();
            $workdata = HowWorks::where('vendor_id', $admindata->id)->orderBy('reorder_id')->get();
            $themes = Theme::where('vendor_id', $admindata->id)->orderBy('reorder_id')->get();
            $app_settings = AppSettings::where('vendor_id', $admindata->id)->first();
            $funfacts = FunFact::where('vendor_id', $admindata->id)->orderByDesc('id')->get();
            return view('landing.index', compact('features', 'planlist', 'testimonials', 'blogs', 'settingdata', 'workdata', 'themes', 'app_settings', 'funfacts'));
        }
        // if the current host doesn't contain the website domain (meaning, custom domain)
        else {
            // if the current package doesn't have 'custom domain' feature || the custom domain is not connected
            $settingdata = User::where('custom_domain', $host)->first();
            if (empty($settingdata)) {
                abort(404);
            }
            $storeinfo = User::where('id', @$settingdata->vendor_id)->first();
            $getcategory = Category::where('vendor_id', @$settingdata->vendor_id)->where('is_available', '=', '1')->where('is_deleted', '2')->orderBy('id', 'ASC')->get();
            $getitem = Item::with(['variation', 'extras'])->where('vendor_id', @$settingdata->vendor_id)->where('is_available', '1')->orderBy('id', 'ASC')->get();
            $bannerimage = Banner::where('vendor_id', @$settingdata->vendor_id)->orderBy('id', 'ASC')->get();
            $cartitems = Cart::select('id', 'item_id', 'item_name', 'item_image', 'item_price', 'extras_id', 'extras_name', 'extras_price', 'qty', 'price', 'tax', 'variants_id', 'variants_name')
                ->where('vendor_id', @$settingdata->vendor_id);
            if (Auth::user() && Auth::user()->type == 3) {
                $cartitems->where('user_id', @Auth::user()->id);
            } else {
                $cartitems->where('session_id', Session::getId());
            }
            $cartdata = $cartitems->get();
            if (empty($settingdata)) {
                abort(404);
            }
            if (Auth::user() && Auth::user()->type == 3) {
                $count = Cart::where('user_id', Auth::user()->id)->where('vendor_id', @$settingdata->vendor_id)->count();
            } else {
                $count = Cart::where('session_id', Session::getId())->where('vendor_id', @$settingdata->vendor_id)->count();
            }
            session()->put('cart', $count);
            return view('front.template-' . $settingdata->template . '.home', compact('getcategory', 'getitem', 'storeinfo', 'bannerimage', 'cartdata'));
        }
    }
    public function emailsubscribe(Request $request)
    {
        $newsubscriber = new Subscriber();
        $newsubscriber->vendor_id = 1;
        $newsubscriber->email = $request->email;
        $newsubscriber->save();
        return redirect()->back()->with('success', trans('messages.success'));
    }
    public function inquiry(Request $request)
    {
        try {
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
            $newinquiry = new Contact();
            $newinquiry->vendor_id = 1;
            $newinquiry->name = $request->name;
            $newinquiry->email = $request->email;
            $newinquiry->mobile = $request->mobile;
            $newinquiry->message = $request->message;
            $newinquiry->save();
            $vendordata = User::select('name', 'email')->where('id', 1)->first();
            $emaildata = helper::emailconfigration(helper::appdata('')->id);
            Config::set('mail', $emaildata);
            helper::vendor_contact_data(1, $vendordata->name, $vendordata->email, $request->name, $request->email, $request->mobile, $request->message);
            return redirect()->back()->with('success', trans('messages.success'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', trans('messages.wrong'));
        }
    }
    public function termscondition()
    {
        $terms = Terms::select('terms_content')->where('vendor_id', 1)->first();
        return view('landing.terms_condition', compact('terms'));
    }
    public function aboutus()
    {
        $aboutus = About::select('about_content')->where('vendor_id', 1)->first();
        return view('landing.aboutus', compact('aboutus'));
    }
    public function privacypolicy()
    {
        $privacypolicy = Privacypolicy::select('privacypolicy_content')->where('vendor_id', 1)->first();
        return view('landing.privacypolicy', compact('privacypolicy'));
    }
    public function refund_policy()
    {
        $policy = Settings::select('refund_policy')->where('vendor_id', 1)->first();
        return view('landing.refund_policy', compact('policy'));
    }
    public function faqs(Request $request)
    {
        $allfaqs = Faq::where('vendor_id', 1)->orderBy('reorder_id')->get();
        return view('landing.faq', compact('allfaqs'));
    }
    public function contact()
    {
        return view('landing.contact');
    }
    public function allstores(Request $request)
    {
        $countries = Country::where('is_deleted', 2)->where('is_available', 1)->get();
        $banners = Promotionalbanner::with('vendor_info')->orderBy('reorder_id')->get();
        $storecategory = StoreCategory::where('is_available', 1)->where('is_deleted', 2)->get();
        $stores = User::where('type', 2)->where('is_available', 1)->where('is_deleted', 2);
        if ($request->country == "" && $request->city == "") {
            $stores = $stores;
        }
        $city_name = "";
        if ($request->has('country') && $request->country != "") {
            $country = Country::select('id')->where('name', $request->country)->first();
            $stores =  $stores->where('country_id', $country->id);
        }
        if ($request->has('city') && $request->city != "") {
            $city = City::where('city', $request->city)->first();
            $stores =  $stores->where('city_id', $city->id);
            $city_name = $city->city;
        }
        if ($request->has('store') && $request->store != "") {
            $storeinfo = StoreCategory::where('name', $request->store)->first();
            $stores =  $stores->where('store_id', $storeinfo->id);
        }
        if ($stores != null) {
            $stores = $stores->paginate(12)->onEachSide(0);
        }
        return view('landing.stores', compact('countries', 'stores', 'city_name', 'banners', 'storecategory'));
    }
    public function themeimages(Request $request)
    {
        $newpath = [];
        $output = '';
        foreach ($request->theme_id as $theme_id) {
            $image = 'theme-' . $theme_id;
            if (file_exists(storage_path('app/public/admin-assets/images/theme/' . $image . '.png'))) {
                $image = 'theme-' . $theme_id . '.png';
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/theme/' . $image);
            } elseif (file_exists(storage_path('app/public/admin-assets/images/theme/' . $image . '.jpeg'))) {
                $image = 'theme-' . $theme_id . '.jpeg';
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/theme/' . $image);
            } elseif (file_exists(storage_path('app/public/admin-assets/images/theme/' . $image . '.jpg'))) {
                $image = 'theme-' . $theme_id . '.jpg';
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/theme/' . $image);
            } elseif (file_exists(storage_path('app/public/admin-assets/images/theme/' . $image . '.webp'))) {
                $image = 'theme-' . $theme_id . '.webp';
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/theme/' . $image);
            } else {
                $path =  asset('storage/app/public/admin-assets/images/about/defaultimages/item-placeholder.png');
            }
            $newpath[] = $path;
        }
        $html = view('admin.theme.themeimages', compact('newpath'))->render();
        return response()->json(['status' => 1, 'output' => $html], 200);
    }
}
