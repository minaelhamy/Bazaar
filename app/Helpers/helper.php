<?php

namespace App\Helpers;

use App\Models\Item;
use App\Models\Settings;
use App\Models\User;
use App\Models\Timing;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Transaction;
use App\Models\Payment;
use App\Models\PricingPlan;
use App\Models\SystemAddons;
use App\Models\RoleAccess;
use App\Models\RoleManager;
use App\Models\Variants;
use App\Models\Cart;
use App\Models\Coupons;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Blog;
use App\Models\CustomDomain;
use App\Models\StorefrontAlias;
use App\Models\SocialLinks;
use App\Models\CustomStatus;
use App\Models\AppSettings;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Models\Languages;
use App\Models\Tax;
use Illuminate\Support\Str;
use App\Models\Faq;
use App\Models\LandingSettings;
use App\Models\Pixcel;
use App\Models\Testimonials;
use App\Models\AgeVerification;
use App\Models\CurrencySettings;
use App\Models\Footerfeatures;
use App\Models\OtherSettings;
use App\Models\TelegramMessage;
use App\Models\TopDeals;
use App\Models\WhatsappMessage;
use Carbon\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;

class helper
{
    public static function currentHost($host = null)
    {
        $host = $host ?? request()->getHost() ?? ($_SERVER['HTTP_HOST'] ?? '');
        $host = strtolower(trim($host));

        return Str::before($host, ':');
    }

    public static function platformHost()
    {
        $host = env('WEBSITE_HOST');

        if (empty($host) && !empty(config('app.url'))) {
            $host = parse_url(config('app.url'), PHP_URL_HOST);
        }

        return self::currentHost($host);
    }

    public static function isPlatformHost($host = null)
    {
        return self::currentHost($host) === self::platformHost();
    }

    public static function platformSubdomainSlug($host = null)
    {
        $host = self::currentHost($host);
        $platformHost = self::platformHost();

        if (empty($host) || empty($platformHost) || $host === $platformHost) {
            return null;
        }

        $suffix = '.' . $platformHost;
        if (!Str::endsWith($host, $suffix)) {
            return null;
        }

        $subdomain = substr($host, 0, -strlen($suffix));

        if (empty($subdomain) || Str::contains($subdomain, '.')) {
            return null;
        }

        return $subdomain;
    }

    public static function isPlatformSubdomain($host = null)
    {
        return !empty(self::platformSubdomainSlug($host));
    }

    public static function requestedVendorSlug($request = null)
    {
        $request = $request ?? request();

        return $request->route('vendor')
            ?? $request->route('vendor_slug')
            ?? self::platformSubdomainSlug();
    }

    public static function currentStoreUser($slug = null)
    {
        $slug = $slug ?: self::requestedVendorSlug();

        if (!empty($slug)) {
            return self::resolveVendorBySlug($slug);
        }

        $host = self::currentHost();

        if (empty($host) || self::isPlatformHost($host)) {
            return null;
        }

        $setting = Settings::where('custom_domain', $host)->first();
        if (!empty($setting)) {
            return User::where('id', $setting->vendor_id)->first();
        }

        return User::where('custom_domain', $host)->first();
    }

    public static function storefront_base_url($store = null)
    {
        if (empty($store)) {
            $store = self::currentStoreUser();
        }

        if (is_numeric($store)) {
            $store = User::find($store);
        }

        if (is_string($store)) {
            $store = User::where('slug', $store)->first();
        }

        if (empty($store)) {
            return rtrim(config('app.url'), '/');
        }

        $scheme = parse_url(config('app.url'), PHP_URL_SCHEME) ?: request()->getScheme();
        $currentHost = self::currentHost();

        if (!empty($store->custom_domain) && self::currentHost($store->custom_domain) === $currentHost) {
            return $scheme . '://' . $currentHost;
        }

        if (self::isPlatformSubdomain($currentHost) && self::platformSubdomainSlug($currentHost) === $store->slug) {
            return $scheme . '://' . $currentHost;
        }

        if ((bool) env('STORE_SUBDOMAIN_ROUTING', false) && !empty(self::platformHost())) {
            return $scheme . '://' . $store->slug . '.' . self::platformHost();
        }

        return rtrim(config('app.url'), '/') . '/' . $store->slug;
    }

    public static function storefront_url($store = null, $path = '')
    {
        $baseUrl = rtrim(self::storefront_base_url($store), '/');

        if ($path === '' || $path === null || $path === '/') {
            return $baseUrl;
        }

        if (Str::startsWith($path, '?')) {
            return $baseUrl . $path;
        }

        return $baseUrl . '/' . ltrim($path, '/');
    }

    public static function storefront_request_is($store, $pattern)
    {
        $store = is_object($store) ? $store : self::currentStoreUser($store);
        $pattern = ltrim($pattern, '/');
        $currentPath = trim(request()->path(), '/');

        if (empty($store)) {
            if ($pattern === '') {
                return $currentPath === '';
            }
            return request()->is($pattern);
        }

        if (self::isPlatformHost()) {
            if ($pattern === '') {
                return $currentPath === trim($store->slug, '/');
            }
            return request()->is($store->slug . '/' . $pattern);
        }

        if ($pattern === '') {
            return $currentPath === '';
        }

        return request()->is($pattern);
    }

    public static function appdata($vendor_id)
    {
        if (!empty($vendor_id)) {
            return Settings::where('vendor_id', $vendor_id)->first();
        }

        $store = self::currentStoreUser();

        if (!empty($store)) {
            return Settings::where('vendor_id', $store->id)->first();
        }

        return Settings::where('vendor_id', 1)->first();
    }

    public static function adminappdata()
    {
        return Settings::first();
    }

    public static function otherappdata($vendor_id)
    {
        if (!empty($vendor_id)) {
            $data = OtherSettings::where('vendor_id', $vendor_id)->first();
        } else {
            $data = OtherSettings::where('vendor_id', 1)->first();
        }

        return $data;
    }
    public static function vendordata()
    {
        return self::currentStoreUser() ?? User::first();
    }
    // front
    public static function vendor_data($slug)
    {
        return self::resolveVendorBySlug($slug);
    }

    private static function resolveVendorBySlug($slug)
    {
        $slug = trim((string) $slug);
        if ($slug === '') {
            return null;
        }

        $vendor = User::where('slug', $slug)->first();
        if (!empty($vendor)) {
            request()->attributes->remove('resolved_storefront_alias_slug');

            return $vendor;
        }

        $alias = StorefrontAlias::where('slug', $slug)->first();
        if (empty($alias)) {
            return null;
        }

        $vendor = User::find($alias->vendor_id);
        if (!empty($vendor)) {
            request()->attributes->set('resolved_storefront_alias_slug', $slug);
        }

        return $vendor;
    }
    public static function telegramdata($vendor_id)
    {
        return TelegramMessage::where('vendor_id', $vendor_id)->first();
    }

    public static function image_path($image)
    {
        if ($image == "" && $image == null) {
            $path = asset('storage/app/public/admin-assets/images/about/defaultimages/item-placeholder.png');
        } else {
            $path = asset('storage/app/public/admin-assets/images/about/defaultimages/item-placeholder.png');
        }

        if (Str::contains($image, 'authformbgimage') || Str::contains($image, 'quick-call-')) {
            if (file_exists(storage_path('app/public/admin-assets/images/about/' . $image))) {
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/about/' . $image);
            }
        }
        if (Str::contains($image, 'theme-')) {
            if (file_exists(storage_path('app/public/admin-assets/images/theme/' . $image))) {
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/theme/' . $image);
            }
        }
        if (Str::contains($image, 'feature-')) {
            if (file_exists(storage_path('app/public/admin-assets/images/feature/' . $image))) {
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/feature/' . $image);
            }
        }
        if (Str::contains($image, 'testimonial-')) {
            if (file_exists(storage_path('app/public/admin-assets/images/testimonials/' . $image))) {
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/testimonials/' . $image);
            }
        }
        if (Str::contains($image, 'screenshot-')) {
            if (file_exists(storage_path('app/public/admin-assets/images/screenshot/' . $image))) {
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/screenshot/' . $image);
            }
        }
        if (Str::contains($image, 'banktransfer') || Str::contains($image, 'cod') || Str::contains($image, 'razorpay') || Str::contains($image, 'stripe') || Str::contains($image, 'wallet') || Str::contains($image, 'flutterwave') || Str::contains($image, 'paystack') || Str::contains($image, 'mercadopago') || Str::contains($image, 'paypal') || Str::contains($image, 'myfatoorah') || Str::contains($image, 'toyyibpay') || Str::contains($image, 'phonepe') || Str::contains($image, 'paytab') || Str::contains($image, 'mollie') || Str::contains($image, 'khalti') || Str::contains($image, 'xendit') || Str::contains($image, 'payment')) {
            if (file_exists(storage_path('app/public/admin-assets/images/about/payment/' . $image))) {
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/about/payment/' . $image);
            }
        }
        if (Str::contains($image, 'res')) {
            if (file_exists(storage_path('app/public/admin-assets/images/about/' . $image))) {
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/about/' . $image);
            }
        }
        if (Str::contains($image, 'trusted_badge')) {
            if (file_exists(storage_path('app/public/admin-assets/images/about/trusted_badge/' . $image))) {
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/about/trusted_badge/' . $image);
            }
        }
        if (Str::contains($image, 'logo')) {
            if (file_exists(storage_path('app/public/admin-assets/images/about/logo/' . $image))) {
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/about/logo/' . $image);
            }
            if (file_exists(storage_path('app/public/admin-assets/images/about/defaultimages/' . $image))) {
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/' . $image);
            }
        }
        if (Str::contains($image, 'darklogo')) {
            if (file_exists(storage_path('app/public/admin-assets/images/about/darklogo/' . $image))) {
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/about/darklogo/' . $image);
            }
            if (file_exists(storage_path('app/public/admin-assets/images/about/defaultimages/' . $image))) {
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/' . $image);
            }
        }

        if (Str::contains($image, 'favicon')) {
            if (file_exists(storage_path('app/public/admin-assets/images/about/favicon/' . $image))) {
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/about/favicon/' . $image);
            }
            if (file_exists(storage_path('app/public/admin-assets/images/about/defaultimages/' . $image))) {
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/' . $image);
            }
        }
        if (Str::contains($image, 'og_image')) {
            if (file_exists(storage_path('app/public/admin-assets/images/about/og_image/' . $image))) {
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/about/og_image/' . $image);
            }
            if (file_exists(storage_path('app/public/admin-assets/images/about/defaultimages/' . $image))) {
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/' . $image);
            }
        }
        if (Str::contains($image, 'item-') || Str::contains($image, 'item')) {
            if (file_exists(storage_path('app/public/item/' . $image))) {
                $path = url(env('ASSETPATHURL') . 'item/' . $image);
            }
            if (file_exists(storage_path('app/public/media/' . $image))) {
                $path = url(env('ASSETPATHURL') . 'media/' . $image);
            }
        }
        if (Str::contains($image, 'banner') || Str::contains($image, 'promotion-')) {
            if (file_exists(storage_path('app/public/admin-assets/images/banners/' . $image))) {
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/banners/' . $image);
            }
        }
        if (Str::contains($image, 'order')) {
            if (file_exists(storage_path('app/public/front/images/' . $image))) {
                $path = url(env('ASSETPATHURL') . 'front/images/' . $image);
            }
        }
        if (Str::contains($image, 'profile')) {
            if (file_exists(storage_path('app/public/admin-assets/images/profile/' . $image))) {
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/profile/' . $image);
            }
        }
        if (Str::contains($image, 'blog')) {
            if (file_exists(storage_path('app/public/admin-assets/images/blog/' . $image))) {
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/blog/' . $image);
            }
        }
        if (Str::contains($image, 'flag')) {
            if (file_exists(storage_path('app/public/admin-assets/images/language/' . $image))) {
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/language/' . $image);
            }
        }
        if (Str::contains($image, 'cover')) {
            if (file_exists(storage_path('app/public/admin-assets/images/coverimage/' . $image))) {
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/coverimage/' . $image);
            }
        }
        if (Str::contains($image, 'app') || Str::contains($image, 'work') || Str::contains($image, 'whoweare') || Str::contains($image, 'faq') || Str::contains($image, 'subscribe') || Str::contains($image, 'order_detail') || Str::contains($image, 'order_success') || Str::contains($image, 'no_data') || Str::contains($image, 'maintenance') || Str::contains($image, 'store_unavailable') || Str::contains($image, 'admin_auth')) {

            if (file_exists(storage_path('app/public/admin-assets/images/index/' . $image))) {
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/index/' . $image);
            }
        }
        if (Str::contains($image, 'default')) {
            if (file_exists(storage_path('app/public/admin-assets/images/about/defaultimages/' . $image))) {
                $path = url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/' . $image);
            }
        }
        return $path;
    }
    public static function currency_formate($price, $vendor_id)
    {
        $price = floatval($price) * helper::currencyinfo($vendor_id)->exchange_rate;

        if (helper::currencyinfo($vendor_id)->currency_position == "1") {
            if (helper::currencyinfo($vendor_id)->decimal_separator == 1) {
                if (helper::currencyinfo($vendor_id)->currency_space == 1) {
                    return helper::currencyinfo($vendor_id)->currency . ' ' . number_format($price, helper::currencyinfo($vendor_id)->currency_formate, '.', ',');
                } else {
                    return helper::currencyinfo($vendor_id)->currency . number_format($price, helper::currencyinfo($vendor_id)->currency_formate, '.', ',');
                }
            } else {
                if (helper::currencyinfo($vendor_id)->currency_space == 1) {
                    return helper::currencyinfo($vendor_id)->currency . ' ' . number_format($price, helper::currencyinfo($vendor_id)->currency_formate, ',', '.');
                } else {
                    return helper::currencyinfo($vendor_id)->currency . number_format($price, helper::currencyinfo($vendor_id)->currency_formate, ',', '.');
                }
            }
        }
        if (helper::currencyinfo($vendor_id)->currency_position == "2") {
            if (helper::currencyinfo($vendor_id)->decimal_separator == 1) {
                if (helper::currencyinfo($vendor_id)->currency_space == 1) {
                    return number_format($price, helper::currencyinfo($vendor_id)->currency_formate, '.', ',') . ' ' . helper::currencyinfo($vendor_id)->currency;
                } else {
                    return number_format($price, helper::currencyinfo($vendor_id)->currency_formate, '.', ',') . helper::currencyinfo($vendor_id)->currency;
                }
            } else {
                if (helper::currencyinfo($vendor_id)->currency_space == 1) {
                    return number_format($price, helper::currencyinfo($vendor_id)->currency_formate, ',', '.') . ' ' . helper::currencyinfo($vendor_id)->currency;
                } else {
                    return number_format($price, helper::currencyinfo($vendor_id)->currency_formate, ',', '.') . helper::currencyinfo($vendor_id)->currency;
                }
                return number_format($price, helper::currencyinfo($vendor_id)->currency_formate, ',', '.') . helper::currencyinfo($vendor_id)->currency;
            }
        }
        return $price;
    }

    public static function vendortime($vendor)
    {
        date_default_timezone_set(helper::appdata($vendor)->timezone);
        $t = date('d-m-Y');
        $time = Timing::select('close_time')->where('vendor_id', $vendor)->where('day', date("l", strtotime($t)))->first();
        $txt = "Opened until " . date("D", strtotime($t)) . " " . $time->close_time . "";
        return $txt;
    }
    public static function date_format($date, $vendor_id)
    {
        return date(helper::appdata($vendor_id)->date_format, strtotime($date));
    }
    public static function top_deals($vendor_id)
    {
        date_default_timezone_set(helper::appdata($vendor_id)->timezone);
        $current_date  = Carbon::now()->format('Y-m-d');
        $current_time  = Carbon::now()->format('H:i:s');
        $topdeal = TopDeals::where('vendor_id', $vendor_id)->first();
        $topdeals = null;
        if (SystemAddons::where('unique_identifier', 'top_deals')->first() != null && SystemAddons::where('unique_identifier', 'top_deals')->first()->activated == 1) {
            if (isset($topdeal) && $topdeal->top_deals_switch == 1) {
                $startDate = $topdeal['start_date'];
                $starttime = $topdeal['start_time'];
                $endDate = $topdeal['end_date'];
                $endtime = $topdeal['end_time'];
                // Checking validity of top deal offer
                if ($topdeal->deal_type == 1) {
                    if ($current_date > $startDate) {
                        if ($current_date < $endDate) {
                            $topdeals = TopDeals::where('vendor_id', $vendor_id)->first();
                        } elseif ($current_date == $endDate) {
                            if ($current_time < $endtime) {
                                $topdeals = TopDeals::where('vendor_id', $vendor_id)->first();
                            }
                        }
                    } elseif ($current_date == $startDate) {
                        if ($current_date < $endDate && $current_time >= $starttime) {
                            $topdeals = TopDeals::where('vendor_id', $vendor_id)->first();
                        } elseif ($current_date == $endDate) {
                            if ($current_time >= $starttime && $current_time <= $endtime) {
                                $topdeals = TopDeals::where('vendor_id', $vendor_id)->first();
                            }
                        }
                    }
                } else if ($topdeal->deal_type == 2) {
                    if ($current_time >= $starttime && $current_time <= $endtime) {
                        $topdeals = TopDeals::where('vendor_id', $vendor_id)->first();
                    }
                }
            }
        }
        return $topdeals;
    }

    public static function topdalsitemlist($vendor_id)
    {
        $itemlist = Item::with(['variation', 'extras', 'product_image', 'multi_image'])
            ->select('items.*', DB::raw('ROUND(AVG(testimonials.star),1) as ratings_average'))
            ->join('categories', 'categories.id', 'items.cat_id')
            ->leftjoin('testimonials', 'testimonials.item_id', 'items.id')
            ->where('items.top_deals', 1)
            ->where('items.vendor_id', @$vendor_id)->where('items.is_available', '1')->where('items.is_deleted', '2')
            ->where('categories.is_available', '1')
            ->groupBy('items.id')
            ->orderBy('items.reorder_id', 'ASC')
            ->paginate(15);
        return $itemlist;
    }

    public static function time_format($time, $vendor_id)
    {
        if (helper::appdata($vendor_id)->time_format == 1) {
            return $time->format('H:i');
        } else {
            return $time->format('h:i A');
        }
    }
    public static function get_plan_exp_date($duration, $days)
    {
        date_default_timezone_set(helper::appdata('')->timezone);
        $purchasedate = date("Y-m-d h:i:sa");
        $exdate = "";
        if (!empty($duration) && $duration != "") {
            if ($duration == "1") {
                $exdate = date('Y-m-d', strtotime($purchasedate . ' + 30 days'));
            }
            if ($duration == "2") {
                $exdate = date('Y-m-d', strtotime($purchasedate . ' + 90 days'));
            }
            if ($duration == "3") {
                $exdate = date('Y-m-d', strtotime($purchasedate . ' + 180 days'));
            }
            if ($duration == "4") {
                $exdate = date('Y-m-d', strtotime($purchasedate . ' + 365 days'));
            }
            if ($duration == "5") {
                $exdate = "";
            }
        }
        if (!empty($days) && $days != "") {
            $exdate = date('Y-m-d', strtotime($purchasedate . ' + ' . $days . 'days'));
        }
        return $exdate;
    }
    public static function timings($vendor)
    {
        $timings = Timing::where('vendor_id', @$vendor)->get();
        return $timings;
    }
    public static function storeinfo($vendor)
    {
        $vendorinfo = self::currentStoreUser($vendor);

        if (empty($vendorinfo)) {
            abort(404);
        }

        if (!self::isPlatformHost() && !self::isPlatformSubdomain()) {
            $domain = CustomDomain::where('vendor_id', $vendorinfo->id)->first();

            if (empty($domain) || (int) $domain->status !== 2) {
                abort(404);
            }

            if (@$vendorinfo->allow_without_subscription == 1) {
                return $vendorinfo;
            }

            $checkplan = Transaction::where('vendor_id', $vendorinfo->id)
                ->orderByDesc('id')
                ->first();

            if (!empty($checkplan) && (int) $checkplan->custom_domain === 1) {
                return $vendorinfo;
            }

            abort(404);
        }

        return $vendorinfo;
    }

    public static function checkplan($id, $type)
    {
        $check = SystemAddons::where('unique_identifier', 'subscription')->first();

        if (@$check->activated != 1) {
            return response()->json(['status' => 1, 'message' => '', 'expdate' => "", 'showclick' => "0", 'plan_message' => '', 'plan_date' => '', 'checklimit' => '', 'bank_transfer' => ''], 200);
        }
        $data = Settings::where('vendor_id', $id)->first();
        date_default_timezone_set(@helper::appdata($id)->timezone);
        $vendorinfo = User::where('id', $id)->first();

        if ($vendorinfo->is_available == 2) {
            return response()->json(['status' => 2, 'message' => trans('messages.account_blocked_by_admin'), 'showclick' => "0", 'plan_message' => '', 'plan_date' => '', 'checklimit' => '', 'bank_transfer' => ''], 200);
        }

        $checkplan = Transaction::where('plan_id', $vendorinfo->plan_id)->where('vendor_id', $vendorinfo->id)->orderByDesc('id')->first();

        $totalservice = Item::where('vendor_id', $vendorinfo->id)->count();

        if ($vendorinfo->allow_without_subscription != 1) {

            if (!empty($checkplan)) {
                if ($vendorinfo->is_available == 2) {
                    return response()->json(['status' => 2, 'message' => trans('messages.account_blocked_by_admin'), 'showclick' => "0", 'plan_message' => '', 'plan_date' => '', 'checklimit' => '', 'bank_transfer' => ''], 200);
                }
                if ($checkplan->payment_type == 1) {
                    if ($checkplan->status == 1) {
                        return response()->json(['status' => 2, 'message' => trans('messages.cod_pending'), 'showclick' => "0", 'plan_message' => trans('messages.cod_pending'), 'plan_date' => '', 'checklimit' => '', 'bank_transfer' => '1'], 200);
                    } elseif ($checkplan->status == 3) {
                        return response()->json(['status' => 2, 'message' => trans('messages.cod_rejected'), 'showclick' => "1", 'plan_message' => trans('messages.cod_rejected'), 'plan_date' => '', 'checklimit' => '', 'bank_transfer' => ''], 200);
                    }
                }
                if ($checkplan->payment_type == 6) {
                    if ($checkplan->status == 1) {
                        return response()->json(['status' => 2, 'message' => trans('messages.bank_request_pending'), 'showclick' => "0", 'plan_message' => trans('messages.bank_request_pending'), 'plan_date' => '', 'checklimit' => '', 'bank_transfer' => '1'], 200);
                    } elseif ($checkplan->status == 3) {
                        return response()->json(['status' => 2, 'message' => trans('messages.bank_request_rejected'), 'showclick' => "1", 'plan_message' => trans('messages.bank_request_rejected'), 'plan_date' => '', 'checklimit' => '', 'bank_transfer' => ''], 200);
                    }
                }
                if ($checkplan->expire_date != "") {
                    if (date('Y-m-d') > $checkplan->expire_date) {

                        return response()->json(['status' => 2, 'message' => trans('messages.plan_expired'), 'expdate' => $checkplan->expire_date, 'showclick' => "1", 'plan_message' => trans('messages.plan_expired'), 'plan_date' => $checkplan->expire_date, 'checklimit' => '', 'bank_transfer' => ''], 200);
                    }
                }
                if (Str::contains(request()->url(), 'admin')) {
                    if ($checkplan->service_limit != -1) {
                        if ($totalservice >= $checkplan->service_limit) {
                            if (Auth::user()->type == 1) {
                                return response()->json(['status' => 2, 'message' => trans('messages.products_limit_exceeded'), 'expdate' => '', 'showclick' => "1", 'plan_message' => trans('messages.plan_expires'), 'plan_date' => '', 'checklimit' => '', 'bank_transfer' => ''], 200);
                            }
                            if (Auth::user()->type == 2 || Auth::user()->type == 4) {
                                if ($checkplan->expire_date != "") {
                                    return response()->json(['status' => 2, 'message' => trans('messages.vendor_products_limit_message'), 'expdate' => '', 'showclick' => "2", 'plan_message' => trans('messages.plan_expires'), 'plan_date' => $checkplan->expire_date, 'checklimit' => 'service', 'bank_transfer' => ''], 200);
                                } else {
                                    return response()->json(['status' => 2, 'message' => trans('messages.vendor_products_limit_message'), 'expdate' => '', 'showclick' => "2", 'plan_message' => trans('messages.lifetime_subscription'), 'plan_date' => $checkplan->expire_date, 'checklimit' => 'service', 'bank_transfer' => ''], 200);
                                }
                            }
                        }
                    }
                    if ($checkplan->appoinment_limit != -1) {
                        if ($checkplan->appoinment_limit <= 0) {
                            if (Auth::user()->type == 1) {
                                return response()->json(['status' => 2, 'message' => trans('messages.order_limit_exceeded'), 'expdate' => '', 'showclick' => "1", 'plan_message' => trans('messages.plan_expires'), 'plan_date' => '', 'checklimit' => '', 'bank_transfer' => ''], 200);
                            }
                            if (Auth::user()->type == 2 || Auth::user()->type == 4) {
                                if ($checkplan->expire_date != "") {
                                    return response()->json(['status' => 2, 'message' => trans('messages.vendor_order_limit_message'), 'expdate' => '', 'showclick' => "1", 'plan_message' => trans('messages.plan_expires'), 'plan_date' => $checkplan->expire_date, 'checklimit' => 'booking', 'bank_transfer' => ''], 200);
                                } else {
                                    return response()->json(['status' => 2, 'message' => trans('messages.vendor_order_limit_message'), 'expdate' => '', 'showclick' => "1", 'plan_message' => trans('messages.lifetime_subscription'), 'plan_date' => $checkplan->expire_date, 'checklimit' => 'service', 'bank_transfer' => ''], 200);
                                }
                            }
                        }
                    }
                }
                if ($type == 3) {

                    if ($checkplan->appoinment_limit != -1) {
                        if ($checkplan->appoinment_limit <= 0) {
                            return response()->json(['status' => 2, 'message' => trans('messages.front_store_unavailable'), 'expdate' => '', 'showclick' => "1", 'plan_message' => trans('messages.plan_expires'), 'plan_date' => '', 'checklimit' => 'booking', 'bank_transfer' => ''], 200);
                        }
                    }
                }
                if ($checkplan->expire_date != "") {

                    return response()->json(['status' => 1, 'message' => trans('messages.plan_expires'), 'expdate' => $checkplan->expire_date, 'showclick' => "0", 'plan_message' => trans('messages.plan_expires'), 'plan_date' => $checkplan->expire_date, 'checklimit' => '', 'bank_transfer' => ''], 200);
                } else {

                    return response()->json(['status' => 1, 'message' => trans('messages.lifetime_subscription'), 'expdate' => $checkplan->expire_date, 'showclick' => "0", 'plan_message' => trans('messages.lifetime_subscription'), 'plan_date' => $checkplan->expire_date, 'checklimit' => '', 'bank_transfer' => ''], 200);
                }
            } else {
                if (Auth::user()->type == 1) {
                    return response()->json(['status' => 2, 'message' => trans('messages.doesnot_select_any_plan'), 'expdate' => '', 'showclick' => "0", 'plan_message' => '', 'plan_date' => '', 'checklimit' => '', 'bank_transfer' => ''], 200);
                }
                if (Auth::user()->type == 2 || Auth::user()->type == 4) {
                    return response()->json(['status' => 2, 'message' => trans('messages.vendor_plan_purchase_message'), 'expdate' => '', 'showclick' => "1", 'plan_message' => '', 'plan_date' => '', 'checklimit' => '', 'bank_transfer' => ''], 200);
                }
            }
        } else {
            return response()->json(['status' => 1, 'message' => trans('messages.success'), 'expdate' => '', 'showclick' => "1", 'plan_message' => '', 'plan_date' => '', 'checklimit' => '', 'bank_transfer' => ''], 200);
        }
    }
    public static function createorder($vendor, $user_id, $session_id, $payment_type_data, $payment_id, $customer_email, $customer_name, $customer_mobile, $stripeToken, $grand_total, $tips, $delivery_area, $delivery_charge, $address, $building, $landmark, $postal_code, $discount_amount, $sub_total, $tax, $tax_name, $delivery_time, $delivery_date, $couponcode, $order_type, $notes, $filename, $table, $tablename, $buynow)
    {
        try {
            date_default_timezone_set(helper::appdata($vendor)->timezone);
            $vendorinfo = User::where('id', $vendor)->first();
            if ($user_id != "" || $user_id != null) {
                if ($buynow == 1) {
                    $data = Cart::where('user_id', $user_id)->where('vendor_id', $vendorinfo->id)->where('buynow', 1)->get();
                } else {
                    $data = Cart::where('user_id', $user_id)->where('vendor_id', $vendorinfo->id)->where('buynow', 0)->get();
                }
            } else {
                if ($buynow == 1) {
                    $data = Cart::where('session_id', $session_id)->where('vendor_id', $vendorinfo->id)->where('buynow', 1)->get();
                } else {
                    $data = Cart::where('session_id', $session_id)->where('vendor_id', $vendorinfo->id)->where('buynow', 0)->get();
                }
            }
            if (helper::appdata($vendorinfo->id)->product_type == 1) {
                $defaultsatus = CustomStatus::where('vendor_id', $vendor)->where('type', 1)->where('order_type', $order_type)->where('is_available', 1)->where('is_deleted', 2)->first();
                if (empty($defaultsatus) && $defaultsatus == null) {
                    return "false";
                }
            }

            if ($data->count() > 0) {
                //payment_type = COD : 1,RazorPay : 2, Stripe : 3, Flutterwave : 4, Paystack : 5, Bank Transfer:6, Mercado Pago : 7, PayPal : 8, MyFatoorah : 9, toyyibpay : 10
                if ($order_type == "2") {
                    $delivery_charge = "0.00";
                    $address = "";
                    $building = "";
                    $landmark = "";
                    $postal_code = "";
                } else {
                    $delivery_charge = $delivery_charge;
                    $address = $address;
                    $building = $building;
                    $landmark = $landmark;
                    $postal_code = $postal_code;
                }
                if ($discount_amount == "NaN") {
                    $discount_amount = 0;
                } else {
                    $discount_amount = $discount_amount;
                }


                $getordernumber = Order::select('order_number', 'order_number_digit', 'order_number_start')->where('vendor_id', $vendor)->orderBy('id', 'DESC')->first();

                if (empty($getordernumber->order_number_digit)) {
                    $n = helper::appdata($vendor)->order_number_start;
                    $newbooking_number = str_pad($n, 0, STR_PAD_LEFT);
                } else {
                    if ($getordernumber->order_number_start == helper::appdata($vendor)->order_number_start) {
                        $n = (int)($getordernumber->order_number_digit);
                        $newbooking_number = str_pad($n + 1, 0, STR_PAD_LEFT);
                    } else {
                        $n = helper::appdata($vendor)->order_number_start;
                        $newbooking_number = str_pad($n, 0, STR_PAD_LEFT);
                    }
                }
                $order = new Order;
                $order_number = helper::appdata($vendor)->order_prefix . $newbooking_number;

                $order->order_number = $order_number;
                $order->order_number_digit = $newbooking_number;
                $order->order_number_start = helper::appdata($vendor)->order_number_start;

                $order->vendor_id = $vendor;
                $order->user_id = $user_id;
                $order->payment_type = $payment_type_data;
                $order->payment_id = @$payment_id;
                $order->sub_total = $sub_total;
                $order->tax = $tax;
                $order->tax_name = $tax_name;
                $order->grand_total = $grand_total - $tips;
                $order->tips = $tips;
                if (helper::appdata($vendorinfo->id)->product_type == 1) {
                    $order->status = $defaultsatus->id;
                    $order->status_type = $defaultsatus->type;
                } else {
                    $order->status_type = 3;
                }
                $order->address = $address;
                $order->delivery_time = $delivery_time;
                $order->delivery_date = $delivery_date;
                $order->delivery_area = $delivery_area;
                $order->delivery_charge = $delivery_charge;
                $order->discount_amount = $discount_amount;
                $order->couponcode = $couponcode;
                $order->order_type = $order_type;
                $order->building = $building;
                $order->landmark = $landmark;
                $order->pincode = $postal_code;
                $order->customer_name = $customer_name;
                $order->customer_email = $customer_email;
                $order->mobile = $customer_mobile;
                $order->order_notes = $notes;
                $order->dinein_table = $table;
                $order->dinein_tablename = $tablename;
                if ($payment_type_data == '1') {
                    $order->payment_status = 1;
                } elseif ($payment_type_data == '6') {
                    $order->screenshot = $filename;
                    $order->payment_status = 1;
                } else {
                    $order->payment_status = 2;
                }

                $order->save();
                $order_id = DB::getPdo()->lastInsertId();

                $checkuser = User::where('is_available', 1)->where('vendor_id', $vendor)->where('id', @Auth::user()->id)->first();
                if ($payment_type_data == 16) {
                    $checkuser->wallet = $checkuser->wallet - (float)$grand_total;
                    $transaction = new Transaction();
                    $transaction->user_id = @$checkuser->id;
                    $transaction->order_id = $order_id;
                    $transaction->order_number = $order_number;
                    $transaction->payment_type = 16;
                    $transaction->transaction_type = 2;
                    $transaction->amount = $grand_total - $tips;
                    $transaction->tips = $tips;
                    if ($transaction->save()) {
                        $checkuser->save();
                    }
                }


                foreach ($data as $value) {

                    $OrderPro = new OrderDetails();
                    $OrderPro->order_id = $order_id;
                    $OrderPro->item_id = $value['item_id'];
                    $OrderPro->item_name = $value['item_name'];
                    $OrderPro->item_image = $value['item_image'];
                    $OrderPro->extras_id = $value['extras_id'];
                    $OrderPro->extras_name = $value['extras_name'];
                    $OrderPro->extras_price = $value['extras_price'];
                    $OrderPro->price = $value['item_price'];
                    if ($value['variants_id'] == "") {
                        $product = Item::where('id', $value['item_id'])->first();
                        if ($product->stock_management == 1) {
                            $product->qty = (int)$product->qty - (int)$value['qty'];
                        }
                        $product->update();
                    } else {
                        $variant = Variants::where('item_id', $value['item_id'])->where('id', $value['variants_id'])->first();
                        if ($variant->stock_management == 1) {
                            $variant->qty = (int)$variant->qty - (int)$value['qty'];
                        }
                        $variant->update();
                    }
                    $OrderPro->variants_id = $value['variants_id'];
                    $OrderPro->variants_name = $value['variants_name'];
                    $OrderPro->variants_price = $value['variants_price'];
                    $OrderPro->attribute = $value['attribute'];
                    $OrderPro->qty = $value['qty'];
                    $OrderPro->save();
                }

                if ($user_id != "" || $user_id != null) {
                    if ($buynow == 1) {
                        $data = Cart::where('user_id', $user_id)->where('buynow', 1)->delete();
                    } else {
                        $data = Cart::where('user_id', $user_id)->where('buynow', 0)->delete();
                    }
                } else {
                    if ($buynow == 1) {
                        $data = Cart::where('session_id', $session_id)->where('buynow', 1)->delete();
                    } else {
                        $data = Cart::where('session_id', $session_id)->where('buynow', 0)->delete();
                    }
                }

                session()->forget(['offer_amount', 'offer_code', 'offer_type']);
                if ($user_id != "" || $user_id != null) {
                    $count = Cart::where('user_id', $user_id)->where('buynow', 0)->count();
                } else {
                    $count = Cart::where('session_id', $session_id)->where('buynow', 0)->count();
                }

                session()->put('cart', $count);

                $trackurl = helper::storefront_url($vendorinfo, '/find-order?order=' . $order_number);
                $emaildata = helper::emailconfigration(helper::appdata($vendorinfo->id)->id);
                Config::set('mail', $emaildata);
                helper::create_order_invoice($customer_email, $customer_name, $vendorinfo->email, $vendorinfo->name, $vendor, $order_number, $order_type, helper::date_format($delivery_date, $vendor), $delivery_time, helper::currency_formate($grand_total, $vendor), $trackurl);
                $title = trans('labels.order_update');
                $body = "Congratulations! Your store just received a new order " . $order_number;
                helper::push_notification($vendorinfo->token, $title, $body, "order", $order->id);
                $checkplan = Transaction::where('vendor_id', $vendor)->wherenotNULL('transaction_number')->orderByDesc('id')->first();
                if (!empty($checkplan)) {
                    if ($checkplan->appoinment_limit != -1) {
                        $checkplan->appoinment_limit -= 1;
                        $checkplan->save();
                    }
                }

                return $order_number;
            } else {
                return $order_number = "";
            }
        } catch (\Throwable $th) {
            dd($th);
            return $th;
        }
    }

    public static function push_notification($token, $title, $body, $type, $order_id)
    {
        $customdata = array(
            "type" => $type,
            "order_id" => $order_id,
        );

        $msg = array(
            'body' => $body,
            'title' => $title,
            'sound' => 1/*Default sound*/
        );
        $fields = array(
            'to'           => $token,
            'notification' => $msg,
            'data' => $customdata
        );
        $headers = array(
            'Authorization: key=' . @helper::appdata('')->firebase,
            'Content-Type: application/json'
        );
        #Send Reponse To FireBase Server
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $firebaseresult = curl_exec($ch);
        curl_close($ch);

        return $firebaseresult;
    }

    public static function vendor_register($vendor_name, $vendor_email, $vendor_mobile, $vendor_password, $firebasetoken, $slug, $google_id, $facebook_id, $country_id, $city_id, $store, $product_type, $username = null)
    {
        try {
            if (!empty($slug)) {
                $check = User::where('slug', $slug)->first();
                if ($check != "") {
                    $last = User::select('id')->orderByDesc('id')->first();
                    $slug =   Str::slug($slug . " " . ($last->id + 1), '-');
                } else {
                    $slug = $slug;
                }
            } else {
                $check = User::where('slug', Str::slug($vendor_name, '-'))->first();
                if ($check != "") {
                    $last = User::select('id')->orderByDesc('id')->first();
                    $slug =   Str::slug($vendor_name . " " . ($last->id + 1), '-');
                } else {
                    $slug = Str::slug($vendor_name, '-');
                }
            }
            $rec = Settings::where('vendor_id', '1')->first();
            date_default_timezone_set($rec->timezone);
            $logintype = "normal";
            if ($google_id != "") {
                $logintype = "google";
            }
            if ($facebook_id != "") {
                $logintype = "facebook";
            }
            if ($product_type == null) {
                $product_type = 1;
            }

            $username = trim((string) $username);
            if ($username === '') {
                $usernameBase = $slug ?: ($vendor_email ? strstr($vendor_email, '@', true) : Str::slug($vendor_name, ''));
                $username = preg_replace('/[^A-Za-z0-9_]/', '', (string) $usernameBase);
                if ($username === '') {
                    $username = 'founder';
                }
            }

            $originalUsername = $username;
            $suffix = 1;
            while (User::where('username', $username)->exists()) {
                $username = $originalUsername . $suffix;
                $suffix++;
            }

            $user = new User;
            $landingsettings = LandingSettings::where('vendor_id', 1)->first();

            $user->name = $vendor_name;
            $user->username = $username;
            $user->email = $vendor_email;
            $user->password = $vendor_password;
            $user->google_id = $google_id;
            $user->facebook_id = $facebook_id;
            $user->mobile = $vendor_mobile;
            $user->image = "default.png";
            $user->slug = $slug;
            $user->login_type = $logintype;
            $user->type = 2;
            $user->token = $firebasetoken;
            $user->country_id = $country_id;
            $user->city_id = $city_id;
            $user->is_verified = 2;
            $user->is_available = 1;
            $user->store_id = $store;
            $user->save();
            $vendor_id = DB::getPdo()->lastInsertId();
            $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];

            foreach ($days as $day) {

                $timedata = new Timing;
                $timedata->vendor_id = $vendor_id;
                $timedata->day = $day;
                $timedata->open_time = '09:00 AM';
                $timedata->break_start = '01:00 PM';
                $timedata->break_end = '02:00 PM';
                $timedata->close_time = '09:00 PM';
                $timedata->is_always_close = '2';
                $timedata->save();
            }
            $status_name = CustomStatus::where('vendor_id', '1')->get();

            foreach ($status_name as $name) {
                $customstatus = new CustomStatus;
                $customstatus->vendor_id = $vendor_id;
                $customstatus->name = $name->name;
                $customstatus->type = $name->type;
                $customstatus->order_type = $name->order_type;
                $customstatus->is_available = $name->is_available;
                $customstatus->is_deleted = $name->is_deleted;
                $customstatus->save();
            }

            $paymentlist = Payment::select('unique_identifier', 'payment_name', 'currency', 'image', 'is_activate', 'payment_type')->where('vendor_id', '1')->get();
            foreach ($paymentlist as $payment) {
                $gateway = new Payment;
                $gateway->vendor_id = $vendor_id;
                $gateway->unique_identifier = $payment->unique_identifier;
                $gateway->payment_name = $payment->payment_name;
                $gateway->currency = $payment->currency;
                $gateway->image = $payment->image;
                $gateway->payment_type = $payment->payment_type;
                $gateway->public_key = '-';
                $gateway->secret_key = '-';
                $gateway->encryption_key = '-';
                $gateway->environment = '1';
                $gateway->payment_description = '-';
                $gateway->is_available = '1';
                $gateway->is_activate = $payment->is_activate;
                $gateway->save();
            }

            $whatsappdata = WhatsappMessage::where('vendor_id', 1)->first();
            $whatsapp = new WhatsappMessage();
            $whatsapp->vendor_id = $vendor_id;
            $whatsapp->item_message = $whatsappdata->item_message;
            $whatsapp->order_whatsapp_message = $whatsappdata->order_whatsapp_message;
            $whatsapp->order_status_message = $whatsappdata->order_status_message;
            $whatsapp->whatsapp_number = $whatsappdata->whatsapp_number;
            $whatsapp->whatsapp_phone_number_id = $whatsappdata->whatsapp_phone_number_id;
            $whatsapp->whatsapp_access_token = $whatsappdata->whatsapp_access_token;
            $whatsapp->whatsapp_chat_on_off = $whatsappdata->whatsapp_chat_on_off;
            $whatsapp->whatsapp_mobile_view_on_off = $whatsappdata->whatsapp_mobile_view_on_off;
            $whatsapp->whatsapp_chat_position = $whatsappdata->whatsapp_chat_position;
            $whatsapp->order_created = $whatsappdata->order_created;
            $whatsapp->status_change = $whatsappdata->status_change;
            $whatsapp->message_type = $whatsappdata->message_type;
            $whatsapp->save();

            $telegramdata = TelegramMessage::where('vendor_id', 1)->first();
            $telegram = new TelegramMessage();
            $telegram->vendor_id = $vendor_id;
            $telegram->item_message = $telegramdata->item_message;
            $telegram->telegram_message = $telegramdata->telegram_message;
            $telegram->order_created = $telegramdata->order_created;
            $telegram->telegram_access_token = $telegramdata->telegram_access_token;
            $telegram->telegram_chat_id = $telegramdata->telegram_chat_id;
            $telegram->save();

            $data = new Settings;
            $data->vendor_id = $vendor_id;
            $data->currencies = 'usd';
            $data->default_currency = $rec->default_currency;

            // logo===================================================
            $data->logo = "default.png";

            // favicon=============
            $data->favicon = "default.png";

            // og_image
            $data->og_image = "default.png";

            $data->timezone = $rec->timezone;
            $data->copyright = $rec->copyright;
            $data->contact = '-';

            $data->time_format = $rec->time_format;
            $data->date_format = $rec->date_format;
            $data->order_prefix = 'PITS';
            $data->order_number_start = 1001;
            $data->firebase = '-';
            $data->primary_color = $landingsettings->primary_color;
            $data->secondary_color = $landingsettings->secondary_color;
            $data->contact_email_message = $rec->contact_email_message;
            $data->new_order_invoice_email_message = $rec->new_order_invoice_email_message;
            $data->vendor_new_order_email_message = $rec->vendor_new_order_email_message;
            $data->order_status_email_message = $rec->order_status_email_message;


            $data->description = $rec->description;
            $data->website_title = $rec->website_title;
            $data->meta_title = $rec->meta_title;
            $data->meta_description = $rec->meta_description;
            $data->delivery_type = 'delivery';
            $data->interval_time = 1;
            $data->interval_type = 2;
            $data->product_type = $product_type;
            $data->shopify_store_url = '-';
            $data->shopify_access_token = '-';
            $data->save();

            $otherdata = OtherSettings::where('vendor_id', '1')->first();
            $other = new OtherSettings();
            $other->vendor_id = $vendor_id;
            $other->maintenance_on_off = $otherdata->maintenance_on_off;
            $other->maintenance_title = $otherdata->maintenance_title;
            $other->maintenance_description = $otherdata->maintenance_description;
            $other->save();

            $emaildata = helper::emailconfigration(helper::adminappdata()->id);
            Config::set('mail', $emaildata);
            helper::send_mail_vendor_register($user);
            return $vendor_id;
        } catch (\Throwable $th) {
            return $th;
        }
    }
    // get plan name
    public static function plandetail($plan_id)
    {
        $planinfo = PricingPlan::select('name')->where('id', $plan_id)->first();
        return $planinfo;
    }
    // display footer features...........
    public static function footer_features($vendor_id)
    {
        return Footerfeatures::select('id', 'icon', 'title', 'description')->where('vendor_id', $vendor_id)->get();
    }

    //========================================== All Send email =========================================
    public static function send_subscription_email($vendor_email, $vendor_name, $plan_name, $duration, $price, $payment_method, $transaction_id)
    {

        $admininfo = User::where('id', '1')->first();
        $vendorvar = ["{vendorname}", "{payment_type}", "{subscription_duration}", "{subscription_price}", "{plan_name}", "{adminname}", "{adminemail}"];
        $vendornewvar = [$vendor_name, $payment_method, $duration, $price, $plan_name, $admininfo->name, $admininfo->email];
        $vendormessage = str_replace($vendorvar, $vendornewvar, nl2br(helper::adminappdata()->subscription_success_email_message));

        $adminvar = ["{adminname}", "{vendorname}", "{vendoremail}", "{plan_name}", "{subscription_duration}", "{subscription_price}", "{payment_type}"];
        $adminnewvar = [$admininfo->name, $vendor_name, $vendor_email, $plan_name, $duration, $price, $payment_method];
        $adminmessage = str_replace($adminvar, $adminnewvar, nl2br(helper::adminappdata()->admin_subscription_success_email_message));

        $data = ['title' => trans('labels.new_subscription_purchase'), 'vendor_email' => $vendor_email, 'vendormessage' => $vendormessage];

        $adminemail = ['title' => trans('labels.new_subscription_purchase'), 'admin_email' => $admininfo->email, 'adminmessage' => $adminmessage];

        try {
            Mail::send('email.subscription', $data, function ($message) use ($data) {
                $message->to($data['vendor_email'])->subject($data['title']);
            });
            Mail::send('email.adminsubscription', $adminemail, function ($message) use ($adminemail) {
                $message->to($adminemail['admin_email'])->subject($adminemail['title']);
            });
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }
    public static function cod_request($vendor_email, $vendor_name, $plan_name, $duration, $price, $payment_method, $transaction_id)
    {
        $admininfo = User::where('id', '1')->first();
        $vendorvar = ["{vendorname}", "{adminname}", "{adminemail}"];
        $vendornewvar = [$vendor_name, $admininfo->name, $admininfo->email];
        $vendormessage = str_replace($vendorvar, $vendornewvar, nl2br(helper::adminappdata()->cod_request_email_message));

        $adminvar = ["{adminname}", "{vendorname}", "{vendoremail}", "{plan_name}", "{subscription_duration}", "{subscription_price}", "{payment_type}"];
        $adminnewvar = [$admininfo->name, $vendor_name, $vendor_email, $plan_name, $duration, $price, $payment_method];
        $adminmessage = str_replace($adminvar, $adminnewvar, nl2br(helper::adminappdata()->admin_subscription_request_email_message));

        $data = ['title' =>  trans('labels.cod'), 'vendor_email' => $vendor_email, 'vendormessage' => $vendormessage];
        $adminemail = ['title' =>  trans('labels.cod'), 'admin_email' => $admininfo->email, 'adminmessage' => $adminmessage,];
        try {
            Mail::send('email.codvendor', $data, function ($message) use ($data) {
                $message->to($data['vendor_email'])->subject($data['title']);
            });

            Mail::send('email.banktransferadmin', $adminemail, function ($message) use ($adminemail) {
                $message->to($adminemail['admin_email'])->subject($adminemail['title']);
            });
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    public static function bank_transfer_request($vendor_email, $vendor_name, $plan_name, $duration, $price, $payment_method, $transaction_id)
    {
        $admininfo = User::where('id', '1')->first();
        $vendorvar = ["{vendorname}", "{adminname}", "{adminemail}"];
        $vendornewvar = [$vendor_name, $admininfo->name, $admininfo->email];
        $vendormessage = str_replace($vendorvar, $vendornewvar, nl2br(helper::adminappdata()->banktransfer_request_email_message));

        $adminvar = ["{adminname}", "{vendorname}", "{vendoremail}", "{plan_name}", "{subscription_duration}", "{subscription_price}", "{payment_type}"];
        $adminnewvar = [$admininfo->name, $vendor_name, $vendor_email, $plan_name, $duration, $price, $payment_method];
        $adminmessage = str_replace($adminvar, $adminnewvar, nl2br(helper::adminappdata()->admin_subscription_request_email_message));

        $data = ['title' =>  trans('labels.banktransfer'), 'vendor_email' => $vendor_email, 'vendormessage' => $vendormessage];

        $adminemail = ['title' =>  trans('labels.banktransfer'), 'adminmessage' => $adminmessage, 'admin_email' => $admininfo->email];

        try {
            Mail::send('email.banktransfervendor', $data, function ($message) use ($data) {
                $message->to($data['vendor_email'])->subject($data['title']);
            });

            Mail::send('email.banktransferadmin', $adminemail, function ($message) use ($adminemail) {
                $message->to($adminemail['admin_email'])->subject($adminemail['title']);
            });
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    public static function send_mail_delete_account($vendor)
    {
        $var = ["{vendorname}"];
        $newvar = [$vendor->name];
        $userdeletemessage = str_replace($var, $newvar, nl2br(helper::adminappdata()->delete_account_email_message));
        $data = ['title' => trans('labels.account_deleted'), 'userdeletemessage' => $userdeletemessage, 'email' => $vendor->email];
        try {
            Mail::send('email.accountdeleted', $data, function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    public static function send_mail_vendor_register($vendor)
    {

        $admininfo = User::where('id', 1)->first();
        $vendorvar = ["{vendorname}"];
        $vendornewvar = [$vendor->name];
        $vendormessage = str_replace($vendorvar, $vendornewvar, nl2br(helper::adminappdata()->vendor_register_email_message));

        $adminvar = ["{adminname}", "{vendorname}", "{vendoremail}", "{vendormobile}"];
        $adminnewvar = [$admininfo->name, $vendor->name, $vendor->email, $vendor->mobile];
        $adminmessage = str_replace($adminvar, $adminnewvar, nl2br(helper::adminappdata()->admin_vendor_register_email_message));

        $data = ['title' => trans('labels.registration'), 'title1' => 'New Vendor Registration', 'vendor_email' => $vendor->email, 'admin_email' => $admininfo->email, "vendormessage" => $vendormessage, 'adminmessage' => $adminmessage];
        try {
            Mail::send('email.vendorregister', $data, function ($message) use ($data) {
                $message->to($data['vendor_email'])->subject($data['title']);
            });

            Mail::send('email.newvendorregistration', $data, function ($message) use ($data) {
                $message->to($data['admin_email'])->subject($data['title1']);
            });
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    public static function send_mail_vendor_block($vendor)
    {
        $var = ["{vendorname}"];
        $newvar = [$vendor->name];
        $vendorblokedmessage = str_replace($var, $newvar, nl2br(helper::adminappdata()->vendor_status_change_email_message));

        $data = ['title' => trans('labels.account_deleted'), 'vendorblokedmessage' => $vendorblokedmessage, 'vendor_email' => $vendor->email];
        try {
            Mail::send('email.vendorbloked', $data, function ($message) use ($data) {
                $message->to($data['vendor_email'])->subject($data['title']);
            });
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    public static function subscription_rejected($vendor_email, $vendor_name, $plan_name, $payment_method)
    {
        $admindata = User::select('name', 'email')->where('id', '1')->first();
        $var = ["{vendorname}", "{payment_type}", "{plan_name}", "{adminname}", "{adminemail}"];
        $newvar = [$vendor_name, $payment_method, $plan_name, $admindata->name, $admindata->email];
        $rejectmessage = str_replace($var, $newvar, nl2br(helper::adminappdata()->subscription_reject_email_message));
        $data = ['title' => "Bank transfer rejected", 'vendor_email' => $vendor_email, 'rejectmessage' => $rejectmessage];
        try {
            Mail::send('email.banktransferreject', $data, function ($message) use ($data) {
                $message->to($data['vendor_email'])->subject($data['title']);
            });
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    public static function vendor_contact_data($id, $vendor_name, $vendor_email, $full_name, $useremail, $usermobile, $usermessage)
    {
        $var = ["{vendorname}", "{username}", "{useremail}", "{usermobile}", "{usermessage}"];
        $newvar = [$vendor_name, $full_name, $useremail, $usermobile, $usermessage];
        $vendorcontactmessage = str_replace($var, $newvar, nl2br(helper::appdata($id)->contact_email_message));

        $data = ['title' => trans('labels.inquiry'), 'vendor_email' => $vendor_email, 'vendorcontactmessage' => $vendorcontactmessage];
        try {
            Mail::send('email.vendorcontcatform', $data, function ($message) use ($data) {
                $message->to($data['vendor_email'])->subject($data['title']);
            });
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    public static function create_order_invoice($customer_email, $customer_name, $companyemail, $companyname, $vendorid, $order_number, $order_type, $delivery_date, $delivery_time, $grand_total, $trackurl)
    {
        $orderinvoicevar = ["{customername}", "{ordernumber}", "{date}", "{time}", "{grandtotal}", "{track_order_url}", "{vendorname}"];
        $orderinvoicenewvar = [$customer_name, $order_number, $delivery_date, $delivery_time, $grand_total, $trackurl, $companyname];
        $neworderinvoicemessage = str_replace($orderinvoicevar, $orderinvoicenewvar, nl2br(helper::appdata($vendorid)->new_order_invoice_email_message));

        $orderemailvar = ["{customername}", "{ordernumber}", "{date}", "{time}", "{grandtotal}", "{vendorname}"];
        $orderemailnewvar = [$customer_name, $order_number, $delivery_date, $delivery_time, $grand_total, $companyname];
        $vendorneworderemailmessage = str_replace($orderemailvar, $orderemailnewvar, nl2br(helper::appdata($vendorid)->vendor_new_order_email_message));

        $data = ['title' => "Order Invoice", 'customer_email' => $customer_email, 'company_email' => $companyemail, 'neworderinvoicemessage' => $neworderinvoicemessage, 'vendorneworderemailmessage' => $vendorneworderemailmessage,];

        try {
            Mail::send('email.customerorderemail', $data, function ($message) use ($data) {
                $message->to($data['customer_email'])->subject($data['title']);
            });

            Mail::send('email.vendororderemail', $data, function ($companymessage) use ($data) {
                $companymessage->to($data['company_email'])->subject($data['title']);
            });
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    public static function order_status_email($email, $name, $title, $message_text, $vendor)
    {
        $var = ["{customername}", "{status_message}", "{vendorname}"];
        $newvar = [$name, $message_text, $vendor->name];
        $orderstatusmessage = str_replace($var, $newvar, nl2br(helper::appdata($vendor->id)->order_status_email_message));
        $data = ['email' => $email, 'title' => $title, 'orderstatusmessage' => $orderstatusmessage, 'logo' => helper::image_path(@helper::appdata($vendor->id)->logo)];
        try {
            Mail::send('email.orderemail', $data, function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    public static function send_pass($email, $name, $password, $id)
    {
        $var = ["{user}", "{password}"];
        $newvar = [$name, $password];
        $forpasswordmessage = str_replace($var, $newvar, nl2br(helper::adminappdata()->forget_password_email_message));
        $data = ['title' => "New Password", 'email' => $email, 'forpasswordmessage' => $forpasswordmessage, 'logo' => @helper::appdata($id)->logo];
        try {

            Mail::send('email.sendpassword', $data, function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    public static function cancel_order($email, $name, $title, $message_text, $vendor)
    {
        $var = ["{customername}", "{status_message}", "{vendorname}"];
        $newvar = [$name, $message_text, $vendor->user_name];
        $orderstatusmessage = str_replace($var, $newvar, nl2br(helper::appdata($vendor->vendor_id)->order_status_email_message));
        $data = ['email' => $email, 'title' => $title, 'orderstatusmessage' => $orderstatusmessage, 'logo' => Helper::image_path(@Helper::appdata($vendor->id)->logo)];
        try {
            Mail::send('email.orderemail', $data, function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }
    //========================================== All Send email end =========================================

    public static function language($vendor_id)
    {
        if (session()->get('locale') == null) {
            $layout = Languages::select('name', 'layout', 'image', 'is_default', 'code')->where('code', helper::appdata($vendor_id)->default_language)->first();
            App::setLocale($layout->code);
            session()->put('locale', $layout->code);
            session()->put('language', $layout->name);
            session()->put('flag', $layout->image);
            session()->put('direction', $layout->layout);
        } else {
            $layout = Languages::select('name', 'layout', 'image', 'is_default', 'code')->where('code', session()->get('locale'))->first();
            App::setLocale(session()->get('locale'));
            session()->put('locale', @$layout->code);
            session()->put('language', @$layout->name);
            session()->put('flag', @$layout->image);
            session()->put('direction', @$layout->layout);
        }
    }
    // get language list vendor side.
    public static function available_language($vendor_id)
    {
        if ($vendor_id == "") {
            $listoflanguage = Languages::where('is_available', '1')->where('is_deleted', 2)->get();
        } else {
            $listoflanguage = Languages::where('is_deleted', 2)->get();
        }
        return $listoflanguage;
    }

    // get language list in atuh pages.
    public static function listoflanguage()
    {
        $listoflanguage = Languages::where('is_available', '1')->get();
        return $listoflanguage;
    }


    public static function role($id)
    {
        $role = RoleManager::select('role')->where('id', $id)->first();
        return $role;
    }
    public static function check_menu($role_id, $slug)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        if ($role_id == "" || $role_id == null || $role_id == 0) {
            return 1;
        } else {
            $module = RoleManager::where('id', $role_id)->where('vendor_id', $vendor_id)->first();
            $module = explode('|', $module->module);
            if (in_array($slug, $module)) {
                return 1;
            } else {

                return 0;
            }
        }
    }
    public static function check_access($module, $role_id, $vendor_id, $action)
    {

        $module = RoleAccess::where('module_name', $module)->where('role_id', $role_id)->where('vendor_id', $vendor_id)->first();
        if (!empty($module) && $module != null) {
            if ($action == 'add' && $module->add == 1) {
                return 1;
            } elseif ($action == 'edit' && $module->edit == 1) {
                return 1;
            } elseif ($action == 'delete' && $module->delete == 1) {
                return 1;
            } elseif ($action == 'manage' && $module->manage == 1) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
    public static function getplantransaction($vendor_id)
    {
        $plan = Transaction::where('vendor_id', $vendor_id)->orderbyDesc('id')->first();
        return $plan;
    }
    public static function getslug($vendor_id)
    {
        $data = User::where('id', $vendor_id)->first();
        return $data;
    }
    public static function getpixelid($vendor_id)
    {
        $pixcel = Pixcel::where('vendor_id', $vendor_id)->first();
        return $pixcel;
    }
    public static function checkvariantqty($item_id, $vendor_id)
    {
        $item = Item::where('id', $item_id)->where('vendor_id', $vendor_id)->first();
        if ($item->has_variants == 1) {
            $qty = Variants::select('item_id', 'qty')->where('item_id', $item_id)->get();
            $array = [];
            foreach ($qty as $qty) {
                array_push($array, $qty->qty);
            }
            if (count(array_filter($array)) == 0) {
                return 2;
            }
        }
    }
    public static function checklowqty($item_id, $vendor_id)
    {
        $item = Item::where('id', $item_id)->where('vendor_id', $vendor_id)->first();
        if ($item->has_variants == 1) {
            $qty = Variants::select('item_id', 'qty')->where('item_id', $item_id)->get();
            $array = [];

            foreach ($qty as $qty) {
                array_push($array, $qty->qty);
            }
            if (in_array(0, $array)) {
                return 2;
            }
            if (count(array_filter($array)) == 0) {
                return 3;
            }
            foreach ($array as $qty) {
                if ($qty != null && $qty != "") {
                    if ($qty <= $item->low_qty) {
                        return 1;
                    }
                }
            }
        } else {

            if ($item->qty == null && $item->qty == "") {
                return 3;
            }
            if ((string)$item->qty != null && (string)$item->qty != "") {
                if ((string)$item->qty == 0) {
                    return 2;
                }
                if ($item->qty <= $item->low_qty) {
                    return 1;
                }
            }
        }
    }
    // dynamic email configration
    public static function emailconfigration($vendor_id)
    {
        $mailsettings = Settings::where('vendor_id', $vendor_id)->first();
        $emaildata = [];
        if ($mailsettings) {
            $emaildata = [
                'driver' => $mailsettings->mail_driver,
                'host' => $mailsettings->mail_host,
                'port' => $mailsettings->mail_port,
                'encryption' => $mailsettings->mail_encryption,
                'username' => $mailsettings->mail_username,
                'password' => $mailsettings->mail_password,
                'from'     => ['address' => $mailsettings->mail_fromaddress, 'name' => $mailsettings->mail_fromname]
            ];
        }
        return $emaildata;
    }
    // display dynamic paymant name
    public static function getpayment($payment_type, $vendor_id)
    {
        $payment = Payment::select('payment_name')->where('payment_type', $payment_type)->where('vendor_id', $vendor_id)->first();
        return $payment;
    }
    // diplay all paymane images in footer
    public static function getallpayment($vendor_id)
    {
        if (Auth::user() && Auth::user()->type == 3) {
            $payment = Payment::where('is_available', '1')->where('vendor_id', $vendor_id)->where('is_activate', 1)->orderBy('reorder_id')->get();
        } else {
            $payment = Payment::where('is_available', '1')->whereNotIn('payment_type', array(16))->where('vendor_id', $vendor_id)->where('is_activate', 1)->orderBy('reorder_id')->get();
        }
        return $payment;
    }
    public static function allpaymentcheckaddons($vendor_id)
    {
        $getpaymentmethods = Payment::where('is_available', '1')->where('vendor_id', $vendor_id)->where('is_activate', 1)->where('payment_type', '!=', 6)->get();
        foreach ($getpaymentmethods as $pmdata) {
            $systemAddonActivated = false;
            $addon = SystemAddons::where('unique_identifier', $pmdata->unique_identifier)->first();
            if ($addon != null && $addon->activated == 1) {
                $systemAddonActivated = true;
                break;
            }
        }
        return $systemAddonActivated;
    }
    // get category list
    public static function getcategory($vendor_id)
    {
        $getcategory = Category::where('vendor_id', @$vendor_id)->where('is_available', '1')->where('is_deleted', '2')->orderBy('reorder_id', 'ASC')->get();
        return $getcategory;
    }
    // item count category wise mobile modal
    public static function getitems($vendor_id)
    {
        $getitem = Item::with(['variation', 'extras'])->where('vendor_id', @$vendor_id)->where('is_available', '1')->orderBy('reorder_id', 'ASC')->get();
        return $getitem;
    }
    public static function ceckfavorite($product_id, $vendor_id, $user_id)
    {
        $getfavorite = Favorite::where('vendor_id', $vendor_id)->where('user_id', $user_id)->where('product_id', $product_id)->first();
        return $getfavorite;
    }
    public static function getcoupons($vendor_id)
    {
        $coupons = Coupons::where('vendor_id', $vendor_id)->where('is_available', 1)->where('start_date', '<=', date('Y-m-d'))->where('exp_date', '>=', date('Y-m-d'))->orderBy('reorder_id')->get();
        $data = array();
        foreach ($coupons as $prod) {
            $count = helper::getcouponcodecount($vendor_id, $prod->offer_code);
            if ($prod->usage_type == 1) {
                if ($count < $prod->usage_limit) {
                    $data[] = $prod;
                }
            } else {
                $data[] = $prod;
            }
        }
        return $data;
    }
    public static function getratting($item_id, $vendor_id, $type)
    {
        if ($type == "") {
            $ratting = Testimonials::where('item_id', $item_id)->where('vendor_id', $vendor_id)->count();
        } else {
            $ratting = Testimonials::where('item_id', $item_id)->where('vendor_id', $vendor_id)->where('star', $type)->count('star');
        }
        return $ratting;
    }
    public static function averagereview($item_id, $vendor_id)
    {
        $averagerating = Testimonials::where('item_id', $item_id)->where('vendor_id', $vendor_id)->avg('star');
        return $averagerating;
    }
    public static function getuserreviews($item_id, $vendor_id)
    {
        $review = Testimonials::select('testimonials.*', 'users.name', DB::raw("CONCAT('" . url('/storage/app/public/admin-assets/images/profile') . "/', users.image) AS image_url"))->join('users', 'users.id', 'testimonials.user_id')->where('testimonials.vendor_id', $vendor_id)->where('item_id', $item_id)->get();
        return $review;
    }
    public static function getcouponcodecount($vendor_id, $coupon_code)
    {
        $count = Order::where('vendor_id', $vendor_id)->where('couponcode', $coupon_code)->count();
        return $count;
    }
    public static function getappsetting($vendor_id)
    {
        $appsetting = AppSettings::where('vendor_id', $vendor_id)->first();
        return $appsetting;
    }
    public static function getmin_maxorder($item_id, $vendor_id)
    {
        $item = Item::where('vendor_id', $vendor_id)->where('id', $item_id)->first();
        return $item;
    }
    public static function customstauts($vendor_id, $order_type)
    {
        $status = CustomStatus::where('vendor_id', $vendor_id)->where('order_type', $order_type)->where('is_available', 1)->where('is_deleted', 2)->orderBy('reorder_id')->get();
        return $status;
    }
    public static function gettype($status, $type, $order_type, $vendor_id)
    {
        $status = CustomStatus::where('vendor_id', $vendor_id)->where('order_type', $order_type)->where('type', $type)->where('id', $status)->first();
        return $status;
    }
    public static function getblogs($vendor_id)
    {
        $blogs = Blog::where('vendor_id', @$vendor_id)->orderBy('reorder_id')->get();
        return $blogs;
    }
    public static function getfaqs($vendor_id)
    {
        $faqs = Faq::where('vendor_id', @$vendor_id)->orderBy('reorder_id')->get();
        return $faqs;
    }
    public static function getsociallinks($vendor_id)
    {
        $links = SocialLinks::where('vendor_id', $vendor_id)->get();
        return $links;
    }
    public static function imagesize()
    {
        $imagesize  = (int)1024 * (int)helper::appdata('')->image_size;
        return $imagesize;
    }
    public static function imageext()
    {
        $imageext = 'mimes:jpeg,jpg,png,webp';
        return $imageext;
    }
    public static function imageresize($file, $directory_name)
    {
        $reimage = 'item-' . uniqid() . "." . $file->getClientOriginalExtension();

        $new_width = 1000;

        // create image manager with desired driver      

        $manager = new ImageManager(new Driver());

        // read image from file system
        $image = $manager->read($file);


        // Get Height & Width
        list($width, $height) = getimagesize("$file");

        // Get Ratio
        $ratio = $width / $height;

        // Create new height & width
        $new_height = $new_width / $ratio;

        // resize image proportionally to 200px width
        $image->scale(width: $new_width, height: $new_height);

        $extension = File::extension($reimage);

        $exif = @exif_read_data("$file");

        $degrees = 0;
        if (isset($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 8:
                    $degrees = 90;
                    break;
                case 3:
                    $degrees = 180;
                    break;
                case 6:
                    $degrees = -90;
                    break;
            }
        }

        // $image->rotate($degrees);
        $convert = $image;
        if (Str::endsWith($reimage, '.jpeg')) {
            $convert = $convert->toJpeg();
        } else if (Str::endsWith($reimage, '.jpg')) {
            $convert = $convert->toJpeg();
        } else if (Str::endsWith($reimage, '.webp')) {
            $convert = $convert->toWebp();
        } else if (Str::endsWith($reimage, '.gif')) {
            $convert = $convert->toGif();
        } else if (Str::endsWith($reimage, '.png')) {
            $convert = $convert->toPng();
        } else if (Str::endsWith($reimage, '.avif')) {
            $convert = $convert->toAvif();
        } else if (Str::endsWith($reimage, '.bmp')) {
            $convert = $convert->toBitmap();
        }
        if ($extension == "webp") {
            $convertimg = $reimage;
        } else {
            $convertimg = str_replace($extension, 'webp', $reimage);
        }


        $convert->save("$directory_name/$convertimg");

        return $convertimg;
    }
    public static function gettax($tax_id)
    {
        $taxArr = explode('|', $tax_id);
        $taxes = [];
        foreach ($taxArr as $tax) {
            $taxes[] = Tax::find($tax);
        }
        return $taxes;
    }

    public static function taxRate($taxRate, $price, $quantity, $tax_type)
    {
        if ($tax_type == 1) {
            return $taxRate * $quantity;
        }

        if ($tax_type == 2) {
            return ($taxRate / 100) * ($price * $quantity);
        }
    }
    // landing page condition
    public static function storedata()
    {
        $userdata = User::select('users.id', 'name', 'slug', 'settings.description', 'website_title', 'cover_image')->where('users.available_on_landing', 1)->where('users.id', '!=', 1)->join('settings', 'users.id', '=', 'settings.vendor_id')->where('is_available', '=', '1')->where('is_deleted', '2')->get();
        return $userdata;
    }

    public static function landingsettings()
    {
        $landigsettings = LandingSettings::where('vendor_id', 1)->first();
        return $landigsettings;
    }
    public static function itemdetails($item_id, $vendor_id)
    {
        $item = Item::where('vendor_id', $vendor_id)->where('id', $item_id)->first();
        return $item;
    }
    public static function productImage($item_id, $vendor_id)
    {
        $itemimage = ProductImage::where('item_id', $item_id)->where('vendor_id', $vendor_id)->orderBy('id')->first();
        return $itemimage;
    }
    public static function getorders($vendor_id, $user_id)
    {
        $orders = Order::where('vendor_id', $vendor_id)->where('user_id', $user_id)->get();
        return $orders;
    }
    public static function getagedetails($vendor_id)
    {
        $agedetails = AgeVerification::where('vendor_id', $vendor_id)->first();
        return $agedetails;
    }

    public static function checkaddons($addons)
    {
        if (str_contains(url()->current(), 'admin')) {
            if (session()->get('demo') == "free-addon") {
                $check = SystemAddons::where('unique_identifier', $addons)->where('activated', 1)->where('type', 1)->first();
            } elseif (session()->get('demo') == "free-with-extended-addon") {
                $check = SystemAddons::where('unique_identifier', $addons)->where('activated', 1)->whereIn('type', ['1', '2'])->first();
            } elseif (session()->get('demo') == "all-addon") {
                $check = SystemAddons::where('unique_identifier', $addons)->where('activated', 1)->whereIn('type', ['1', '2', '3'])->first();
            } else {
                $check = SystemAddons::where('unique_identifier', $addons)->where('activated', 1)->first();
            }
        } else {
            $check = SystemAddons::where('unique_identifier', $addons)->where('activated', 1)->first();
        }

        return $check;
    }

    public static function checkthemeaddons($addons)
    {
        if (session()->get('demo') == "free-addon") {
            $check = SystemAddons::where('unique_identifier', 'LIKE', '%' . $addons . '%')->where('activated', 1)->where('type', 1)->get();
        } elseif (session()->get('demo') == "free-with-extended-addon") {
            $check = SystemAddons::where('unique_identifier', 'LIKE', '%' . $addons . '%')->where('activated', 1)->whereIn('type', ['1', '2'])->get();
        } elseif (session()->get('demo') == "all-addon") {
            $check = SystemAddons::where('unique_identifier', 'LIKE', '%' . $addons . '%')->where('activated', 1)->whereIn('type', ['1', '2', '3'])->get();
        } else {
            $check = SystemAddons::where('unique_identifier', 'LIKE', '%' . $addons . '%')->where('activated', 1)->get();
        }
        return $check;
    }

    public static function availablePlanThemes($directoryPrefix = 'theme-')
    {
        $themes = [];

        foreach (File::directories(resource_path('views/front')) as $directory) {
            $name = basename($directory);
            if (preg_match('/^' . preg_quote($directoryPrefix, '/') . '(\d+)$/', $name, $matches)) {
                $themes[] = (string) ((int) $matches[1]);
            }
        }

        if (empty($themes)) {
            foreach (self::checkthemeaddons('theme_') as $themeAddon) {
                $themes[] = str_replace('theme_', '', $themeAddon->unique_identifier);
            }
        }

        $themes = array_values(array_unique($themes));
        sort($themes, SORT_NATURAL);

        return $themes;
    }

    public static function getcartcount($session_id)
    {
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $cartitems = Cart::where('vendor_id', $vendor_id)->where('session_id', $session_id)->count();
        return $cartitems;
    }
    // get currency list vendor side.
    public static function available_currency()
    {
        $listofcurrency = CurrencySettings::where('is_available', '1')->get();
        return $listofcurrency;
    }
    // get language list in athu pages.
    public static function currencyinfo($vendor_id)
    {
        if (Cookie::get('code') == null) {
            $currency = CurrencySettings::where('code', helper::appdata($vendor_id)->default_currency)->first();
            session()->put('currency', $currency->currency);
        } else {

            $currency = CurrencySettings::where('code', Cookie::get('code'))->first();
            if (empty($currency)) {
                $currency = CurrencySettings::where('code', helper::appdata($vendor_id)->default_currency)->first();
            }
            session()->put('currency', $currency->currency);
        }
        return $currency;
    }
}
