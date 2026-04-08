<?php

use Illuminate\Support\Facades\Route;
use App\Helpers\helper;
use App\Http\Controllers\addons\AIContentGenerate;
use App\Http\Controllers\addons\AiController;
use App\Http\Controllers\addons\included\CurrencyController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\PlanPricingController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\MediaController;
use App\Http\Controllers\admin\StoreCategoryController;
use App\Http\Controllers\admin\WebSettingsController;
use App\Http\Controllers\admin\TableController;
use App\Http\Controllers\admin\GlobalExtrasController;
use App\Http\Controllers\admin\HowItWorkController;
use App\Http\Controllers\admin\ThemeController;
use App\Http\Controllers\admin\SettingsController;
use App\Http\Controllers\admin\PaymentController;
use App\Http\Controllers\admin\TransactionController;
use App\Http\Controllers\admin\BannerController;
use App\Http\Controllers\addons\included\BlogController;
use App\Http\Controllers\addons\QuestionAnswerController;
use App\Http\Controllers\admin\VendorController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\OtherPagesController;
use App\Http\Controllers\admin\SystemAddonsController;
use App\Http\Controllers\admin\FeaturesController;
use App\Http\Controllers\admin\TimeController;
use App\Http\Controllers\admin\TaxController;
use App\Http\Controllers\admin\WhoWeAreController;
use App\Http\Controllers\web\HomeController;
use App\Http\Controllers\web\UserController as WebUserController;
use App\Http\Controllers\web\WalletController;
use App\Http\Controllers\landing\HomeController as LandingHomeController;
use App\Http\Controllers\SEOController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



//  ------------------------------- ----------- -----------------------------------------   //
//  -------------------------------  FOR ADMIN  -----------------------------------------   //
//  ------------------------------- ----------- -----------------------------------------   //

Route::get('/seo-generator', [SEOController::class, 'index']);
Route::post('/generate-text', [SEOController::class, 'generateText']);
Route::post('/generate-image', [SEOController::class, 'generateImage']);


Route::post('add-on/session/save', [AdminController::class, 'sessionsave']);
Route::group(['namespace' => 'admin', 'prefix' => 'admin'], function () {
    Route::get('/', [AdminController::class, 'login']);
    Route::post('checklogin-{logintype}', [AdminController::class, 'check_admin_login']);
    Route::get('register', [VendorController::class, 'register']);
    Route::post('register_vendor', [VendorController::class, 'register_vendor']);
    Route::get('forgot_password', [VendorController::class, 'forgot_password']);
    Route::post('send_password', [VendorController::class, 'send_password']);
    Route::post('/getcity', [VendorController::class, 'getcity']);

    Route::get('apps', [SystemAddonsController::class, 'index'])->name('systemaddons');
    Route::get('createsystem-addons', [SystemAddonsController::class, 'createsystemaddons']);
    Route::post('systemaddons/store', [SystemAddonsController::class, 'store']);
    Route::get('systemaddons/status-{id}/{status}', [SystemAddonsController::class, 'change_status']);

    Route::post('other/update', [WebSettingsController::class, 'other_update']);
    Route::group(['middleware' => 'AuthMiddleware'], function () {
        // -------- COMMON --------
        Route::get('admin_back', [VendorController::class, 'admin_back']);
        Route::get('logout', [AdminController::class, 'logout']);
        Route::get('dashboard', [AdminController::class, 'index']);
        // SETTINGS
        Route::get('settings', [SettingsController::class, 'settings_index']);
        Route::post('settings/update', [SettingsController::class, 'settings_update']);

        Route::post('settings/updateanalytics', [SettingsController::class, 'settings_updateanalytics']);
        Route::post('settings/updatecustomedomain', [SettingsController::class, 'settings_updatecustomedomain']);
        Route::post('settings/update-profile-{id}', [VendorController::class, 'update']);
        Route::post('settings/change-password', [VendorController::class, 'change_password']);
        Route::post('settings/shopify_settings', [SettingsController::class, 'shopify_settings']);
        Route::post('settings/safe-secure-store', [SettingsController::class, 'safe_secure_store']);

        Route::post('/notice_update', [SettingsController::class, 'notice_update']);
        Route::post('/maintenance_update', [SettingsController::class, 'maintenance_update']);
        Route::post('/recent_view_product/update', [SettingsController::class, 'recent_view_product']);
        Route::post('/ai_settings/update', [SettingsController::class, 'ai_settings']);


        // TRANSACTION
        Route::get('transaction', [TransactionController::class, 'index']);
        Route::get('transaction/generatepdf-{id}', [PlanPricingController::class, 'generatepdf']);
        // PLANS
        Route::get('plan', [PlanPricingController::class, 'view_plan']);
        Route::get('/themeimages', [PlanPricingController::class, 'themeimages']);
        Route::get('transaction/plandetails-{id}', [PlanPricingController::class, 'plan_details']);

        Route::get('/orders/print/{order_number}', [OrderController::class, 'print']);
        // PAYMENT
        Route::group(['prefix' => 'payment'], function () {
            Route::get('/', [PaymentController::class, 'index']);
            Route::post('update', [PaymentController::class, 'update']);
            Route::post('/reorder_payment', [PaymentController::class, 'reorder_payment']);
        });

        // inquiries
        Route::get('/inquiries', [OtherPagesController::class, 'inquiries']);
        Route::get('/inquiries/delete-{id}', [OtherPagesController::class, 'inquiries_delete']);
        Route::get('/inquiries/bulk_delete', [OtherPagesController::class, 'inquiries_bulk_delete']);
        Route::get('/inquiries/change_status-{id}/{status}', [OtherPagesController::class, 'change_status']);

        // Other Pages
        Route::get('/subscribers', [OtherPagesController::class, 'subscribers']);
        Route::get('/subscribers/delete-{id}', [OtherPagesController::class, 'subscribers_delete']);
        Route::get('/subscribers/bulk_delete', [OtherPagesController::class, 'subscribers_bulk_delete']);

        Route::get('privacy-policy', [OtherPagesController::class, 'privacypolicy']);
        Route::post('privacy-policy/update', [OtherPagesController::class, 'privacypolicy_update']);
        Route::get('terms-conditions', [OtherPagesController::class, 'termscondition']);
        Route::post('terms-conditions/update', [OtherPagesController::class, 'termscondition_update']);
        Route::get('aboutus', [OtherPagesController::class, 'aboutus']);
        Route::post('aboutus/update', [OtherPagesController::class, 'aboutus_update']);
        Route::get('refund_policy', [OtherPagesController::class, 'refund_policy']);
        Route::post('refund_policy/update', [OtherPagesController::class, 'refund_policy_update']);

        //shipping
        Route::group(['prefix' => 'shipping'], function () {
            Route::get('/', [OtherPagesController::class, 'shippingindex']);
            Route::post('/savecontent', [OtherPagesController::class, 'savecontent']);
        });

        Route::get('/basic_settings', [WebSettingsController::class, 'basic_settings']);
        Route::post('/theme_settings/update', [WebSettingsController::class, 'theme_settings']);
        Route::post('/contact_settings/update', [WebSettingsController::class, 'contact_settings']);
        Route::post('seo/update', [WebSettingsController::class, 'seo_update']);
        Route::post('app_section/update', [WebSettingsController::class, 'app_section']);
        
        Route::post('seo/ai_generate', [AiController::class, 'ai_generate']);

        //FAQs
        Route::group(['prefix' => 'faqs'], function () {
            Route::get('/', [OtherPagesController::class, 'faq_index']);
            Route::get('/add', [OtherPagesController::class, 'faq_add']);
            Route::post('/save', [OtherPagesController::class, 'faq_save']);
            Route::get('/edit-{id}', [OtherPagesController::class, 'faq_edit']);
            Route::post('/update-{id}', [OtherPagesController::class, 'faq_update']);
            Route::get('/delete-{id}', [OtherPagesController::class, 'faq_delete']);
            Route::post('/reorder_faq', [OtherPagesController::class, 'reorder_faq']);
            Route::get('/bulk_delete', [OtherPagesController::class, 'faq_bulk_delete']);
        });
        Route::get('/orders/invoice/{order_number}', [OrderController::class, 'invoice']);
        Route::post('/orders/customerinfo/', [OrderController::class, 'customerinfo']);
        Route::post('/orders/vendor_note/', [OrderController::class, 'vendor_note']);

        // tax
        Route::group(['prefix' => 'tax'], function () {
            Route::get('/', [TaxController::class, 'index']);
            Route::get('add', [TaxController::class, 'add']);
            Route::post('save', [TaxController::class, 'save']);
            Route::get('edit-{id}', [TaxController::class, 'edit']);
            Route::post('update-{id}', [TaxController::class, 'update']);
            Route::get('change_status-{id}/{status}', [TaxController::class, 'change_status']);
            Route::get('delete-{id}', [TaxController::class, 'delete']);
            Route::post('reorder_tax', [TaxController::class, 'reorder_tax']);
            Route::get('bulk_delete', [TaxController::class, 'bulk_delete']);
        });

        Route::post('social_links/update', [WebSettingsController::class, 'social_links_update']);
        Route::get('settings/delete-sociallinks-{id}', [WebSettingsController::class, 'delete_sociallinks']);

        Route::middleware('adminmiddleware')->group(function () {
            Route::get('transaction-{id}-{status}', [TransactionController::class, 'status']);
            // PLAN
            Route::group(['prefix' => 'plan'], function () {
                Route::get('add', [PlanPricingController::class, 'add_plan']);
                Route::post('save_plan', [PlanPricingController::class, 'save_plan']);
                Route::get('edit-{id}', [PlanPricingController::class, 'edit_plan']);
                Route::post('update_plan-{id}', [PlanPricingController::class, 'update_plan']);
                Route::get('status_change-{id}/{status}', [PlanPricingController::class, 'status_change']);
                Route::get('delete-{id}', [PlanPricingController::class, 'delete']);
                Route::post('updateimage', [PlanPricingController::class, 'updateimage']);
            });
            // VENDORS
            Route::group(['prefix' => 'users'], function () {
                Route::get('/', [VendorController::class, 'index']);
                Route::get('add', [VendorController::class, 'add']);
                Route::get('edit-{id}', [VendorController::class, 'edit']);
                Route::post('update-{id}', [VendorController::class, 'update']);
                Route::get('status-{id}/{status}', [VendorController::class, 'status']);
                Route::get('login-{id}', [VendorController::class, 'vendor_login']);
                Route::post('/store/page/is_allow', [VendorController::class, 'is_allow']);
                Route::get('delete-{id}', [VendorController::class, 'deletevendor']);
            });

            // PLAN
            Route::group(['prefix' => 'plan'], function () {
                Route::post('reorder_plan', [PlanPricingController::class, 'reorder_plan']);
            });

            //features
            Route::group(['prefix' => 'features'], function () {
                Route::get('/', [FeaturesController::class, 'index']);
                Route::get('/add', [FeaturesController::class, 'add']);
                Route::post('/save', [FeaturesController::class, 'save']);
                Route::get('/edit-{id}', [FeaturesController::class, 'edit']);
                Route::post('/update-{id}', [FeaturesController::class, 'update']);
                Route::get('/delete-{id}', [FeaturesController::class, 'delete']);
                Route::post('/reorder_features', [FeaturesController::class, 'reorder_features']);
                Route::get('/bulk_delete', [FeaturesController::class, 'bulk_delete']);
                Route::post('/ai_feature_generate', [AiController::class, 'ai_feature_generate']);
            });


            // countries
            Route::group(['prefix' => 'countries'], function () {
                Route::get('/', [OtherPagesController::class, 'countries']);
                Route::get('/add', [OtherPagesController::class, 'add_country']);
                Route::post('/save', [OtherPagesController::class, 'save_country']);
                Route::get('/edit-{id}', [OtherPagesController::class, 'edit_country']);
                Route::post('/update-{id}', [OtherPagesController::class, 'update_country']);
                Route::get('/delete-{id}', [OtherPagesController::class, 'delete_country']);
                Route::get('/change_status-{id}/{status}', [OtherPagesController::class, 'statuschange_country']);
                Route::post('/reorder_city', [OtherPagesController::class, 'reorder_city']);
                Route::get('/bulk_delete', [OtherPagesController::class, 'bulk_delete_country']);
            });

            // city
            Route::group(['prefix' => 'cities'], function () {
                Route::get('/', [OtherPagesController::class, 'cities']);
                Route::get('/add', [OtherPagesController::class, 'add_city']);
                Route::post('/save', [OtherPagesController::class, 'save_city']);
                Route::get('/edit-{id}', [OtherPagesController::class, 'edit_city']);
                Route::post('/update-{id}', [OtherPagesController::class, 'update_city']);
                Route::get('/delete-{id}', [OtherPagesController::class, 'delete_city']);
                Route::get('/change_status-{id}/{status}', [OtherPagesController::class, 'statuschange_city']);
                Route::post('/reorder_area', [OtherPagesController::class, 'reorder_area']);
                Route::get('/bulk_delete', [OtherPagesController::class, 'bulk_delete_city']);
            });
            // promotional banner
            Route::group(['prefix' => 'promotionalbanners'], function () {
                Route::get('/', [BannerController::class, 'promotional_banner']);
                Route::get('add', [BannerController::class, 'promotional_banneradd']);
                Route::get('edit-{id}', [BannerController::class, 'promotional_banneredit']);
                Route::post('save', [BannerController::class, 'promotional_bannersave_banner']);
                Route::post('update-{id}', [BannerController::class, 'promotional_bannerupdate']);
                Route::get('delete-{id}', [BannerController::class, 'promotional_bannerdelete']);
                Route::post('reorder_promotionalbanner', [BannerController::class, 'reorder_promotionalbanner']);
                Route::get('bulk_delete', [BannerController::class, 'promotional_bulkdelete']);
            });

            // STORE CATEGORIES
            Route::group(['prefix' => 'store_categories'], function () {
                Route::get('/', [StoreCategoryController::class, 'index']);
                Route::get('add', [StoreCategoryController::class, 'add_category']);
                Route::post('save', [StoreCategoryController::class, 'save_category']);
                Route::get('edit-{id}', [StoreCategoryController::class, 'edit_category']);
                Route::post('update-{id}', [StoreCategoryController::class, 'update_category']);
                Route::get('change_status-{id}/{status}', [StoreCategoryController::class, 'change_status']);
                Route::get('delete-{id}', [StoreCategoryController::class, 'delete_category']);
                Route::post('/reorder_category', [StoreCategoryController::class, 'reorder_category']);
                Route::get('bulk_delete', [StoreCategoryController::class, 'bulk_delete']);
            });
            // theme
            Route::get('/themes', [ThemeController::class, 'index']);
            Route::get('themes/add', [ThemeController::class, 'add']);
            Route::post('/themes/save', [ThemeController::class, 'save']);
            Route::get('/themes/edit-{id}', [ThemeController::class, 'edit']);
            Route::post('/themes/update-{id}', [ThemeController::class, 'update']);
            Route::get('/themes/delete-{id}', [ThemeController::class, 'delete']);
            Route::post('/themes/reorder_theme', [ThemeController::class, 'reorder_theme']);
            Route::get('/themes/bulk_delete', [ThemeController::class, 'bulk_delete']);

            Route::get('/how_it_works', [HowItWorkController::class, 'index']);
            Route::get('/how_it_works/add', [HowItWorkController::class, 'add']);
            Route::get('/how_it_works/edit-{id}', [HowItWorkController::class, 'edit']);
            Route::post('/how_it_works/save', [HowItWorkController::class, 'save']);
            Route::post('/how_it_works/update-{id}', [HowItWorkController::class, 'update']);
            Route::get('/how_it_works/delete-{id}', [HowItWorkController::class, 'delete']);
            Route::post('/how_it_works/reorder_how_work', [HowItWorkController::class, 'reorder_how_work']);
            Route::get('/how_it_works/bulk_delete', [HowItWorkController::class, 'bulk_delete']);
            Route::post('/how_it_works/ai_how_it_works_generate', [AiController::class, 'ai_how_it_works_generate']);

            Route::post('/fun_fact/update', [WebSettingsController::class, 'fun_fact_update']);
            Route::get('/fun_fact/delete-{id}', [WebSettingsController::class, 'fun_fact_delete']);
        });
        Route::middleware('VendorMiddleware')->group(function () {
            // OTHERS
            Route::post('/pwasettings', [SettingsController::class, 'pwasettings']);
            Route::get('share', [OtherPagesController::class, 'share']);
            Route::get('/deleteaccount-{id}', [SettingsController::class, 'deleteaccount']);

            // TIME
            Route::group(['prefix' => 'time'], function () {
                Route::get('/', [TimeController::class, 'index']);
                Route::post('store', [TimeController::class, 'store']);
            });
            // ORDERS
            Route::get('/report', [OrderController::class, 'index']);

            Route::group(['prefix' => 'orders'], function () {
                Route::get('/', [OrderController::class, 'index']);
                Route::get('/update-{id}-{status}-{type}', [OrderController::class, 'update']);


                Route::get('/generatepdf/{order_number}', [OrderController::class, 'generatepdf']);
                Route::post('/payment_status-{status}', [OrderController::class, 'payment_status']);
            });
            // CATEGORIES
            Route::group(['prefix' => 'categories'], function () {
                Route::get('/', [CategoryController::class, 'index']);
                Route::get('add', [CategoryController::class, 'add_category']);
                Route::post('save', [CategoryController::class, 'save_category']);
                Route::get('edit-{slug}', [CategoryController::class, 'edit_category']);
                Route::post('update-{slug}', [CategoryController::class, 'update_category']);
                Route::get('change_status-{slug}/{status}', [CategoryController::class, 'change_status']);
                Route::get('delete-{slug}', [CategoryController::class, 'delete_category']);
                Route::post('/reorder_category', [CategoryController::class, 'reorder_category']);
                Route::get('bulk_delete', [CategoryController::class, 'bulk_delete_category']);
            });

            // PRODUCTS
            Route::group(['prefix' => 'products'], function () {
                Route::get('/', [ProductController::class, 'index']);
                Route::get('add', [ProductController::class, 'add']);
                Route::post('ai_generate', [AiController::class, 'ai_generate']);
                Route::post('save', [ProductController::class, 'save']);
                Route::get('edit-{slug}', [ProductController::class, 'edit']);
                Route::post('update-{slug}', [ProductController::class, 'update_product']);
                Route::post('updateimage', [ProductController::class, 'update_image']);
                Route::get('status-{slug}/{status}', [ProductController::class, 'status']);
                Route::get('delete/variation-{id}-{product_id}', [ProductController::class, 'delete_variation']);
                Route::get('delete/extras-{id}', [ProductController::class, 'delete_extras']);
                Route::get('delete-{slug}', [ProductController::class, 'delete_product']);
                Route::post('/reorder_category', [ProductController::class, 'reorder_category']);
                Route::get('/review/delete-{id}', [ProductController::class, 'delete_review']);
                Route::post('/add_image', [ProductController::class, 'add_image']);
                Route::get('/delete_image-{id}/{item_id}', [ProductController::class, 'delete_image']);
                Route::post('/product-variants-possibilities/{product_id}', [ProductController::class, 'getProductVariantsPossibilities']);
                Route::get('/get-product-variants-possibilities', [ProductController::class, 'getProductVariantsPossibilities']);
                Route::get('/variants/edit/{product_id}', [ProductController::class, 'productVariantsEdit']);
                Route::post('/reorder_image-{item_id}', [ProductController::class, 'reorder_image']);
                Route::get('bulk_delete', [ProductController::class, 'bulk_delete_product']);
            });

            // Media
            Route::group(['prefix' => 'media'], function () {
                Route::get('/', [MediaController::class, 'index']);
                Route::post('/add_image', [MediaController::class, 'add_image']);
                Route::get('delete-{id}', [MediaController::class, 'delete_media']);
                Route::get('download-{id}', [MediaController::class, 'download']);
            });

            // extras
            Route::get('/getextras', [GlobalExtrasController::class, 'getextras']);
            Route::get('/editgetextras-{id}', [GlobalExtrasController::class, 'editgetextras']);
            Route::group(['prefix' => 'extras'], function () {
                Route::get('/', [GlobalExtrasController::class, 'index']);
                Route::get('/add', [GlobalExtrasController::class, 'add']);
                Route::post('/save', [GlobalExtrasController::class, 'save']);
                Route::get('/edit-{id}', [GlobalExtrasController::class, 'edit']);
                Route::post('/update-{id}', [GlobalExtrasController::class, 'update']);
                Route::get('/change_status-{id}/{status}', [GlobalExtrasController::class, 'change_status']);
                Route::get('delete-{id}', [GlobalExtrasController::class, 'delete']);
                Route::post('/reorder_extras', [GlobalExtrasController::class, 'reorder_extras']);
                Route::get('bulk_delete', [GlobalExtrasController::class, 'bulk_delete']);
            });

            // PLAN
            Route::group(['prefix' => 'plan'], function () {
                Route::get('selectplan-{id}', [PlanPricingController::class, 'select_plan']);
                Route::post('buyplan', [PlanPricingController::class, 'buyplan']);
                Route::any('buyplan/paymentsuccess/success', [PlanPricingController::class, 'success']);
            });
            // BANNERS
            Route::group(['prefix' => 'sliders'], function () {
                Route::get('/', [BannerController::class, 'index']);
                Route::get('/add', [BannerController::class, 'add']);
                Route::post('/store', [BannerController::class, 'store']);
                Route::get('/edit-{id}', [BannerController::class, 'show']);
                Route::post('/update-{id}', [BannerController::class, 'update']);
                Route::get('/delete-{id}', [BannerController::class, 'delete']);
                Route::post('/reorder_banner', [BannerController::class, 'reorder_banner']);
                Route::get('/bulk_delete', [BannerController::class, 'bulk_delete']);
            });

            Route::group(['prefix' => 'bannersection-1'], function () {
                Route::get('/', [BannerController::class, 'index'])->name('banner');
                Route::get('/add', [BannerController::class, 'add']);
                Route::post('/store', [BannerController::class, 'store']);
                Route::get('/edit-{id}', [BannerController::class, 'show']);
                Route::post('/update-{id}', [BannerController::class, 'update']);
                Route::get('/delete-{id}', [BannerController::class, 'delete']);
                Route::post('/reorder_banner', [BannerController::class, 'reorder_banner']);
                Route::get('/bulk_delete', [BannerController::class, 'bulk_delete']);
            });
            Route::group(['prefix' => 'bannersection-2'], function () {
                Route::get('/', [BannerController::class, 'index'])->name('banner');
                Route::get('/add', [BannerController::class, 'add']);
                Route::post('/store', [BannerController::class, 'store']);
                Route::get('/edit-{id}', [BannerController::class, 'show']);
                Route::post('/update-{id}', [BannerController::class, 'update']);
                Route::get('/delete-{id}', [BannerController::class, 'delete']);
                Route::post('/reorder_banner', [BannerController::class, 'reorder_banner']);
                Route::get('/bulk_delete', [BannerController::class, 'bulk_delete']);
            });

            // TABLES
            Route::group(['prefix' => 'dinein'], function () {
                Route::get('/', [TableController::class, 'index']);
                Route::get('add', [TableController::class, 'add_category']);
                Route::post('save', [TableController::class, 'save_category']);
                Route::get('edit-{id}', [TableController::class, 'edit_category']);
                Route::post('update-{id}', [TableController::class, 'update_category']);
                Route::get('change_status-{id}/{status}', [TableController::class, 'change_status']);
                Route::get('delete-{id}', [TableController::class, 'delete_category']);
                Route::post('/reorder_category', [TableController::class, 'reorder_category']);
            });
            Route::group(['prefix' => 'whoweare'], function () {
                Route::get('/', [WhoWeAreController::class, 'index']);
                Route::get('/add', [WhoWeAreController::class, 'add']);
                Route::get('/edit-{id}', [WhoWeAreController::class, 'edit']);
                Route::post('/savecontent', [WhoWeAreController::class, 'savecontent']);
                Route::post('/save', [WhoWeAreController::class, 'save']);
                Route::post('/update-{id}', [WhoWeAreController::class, 'update']);
                Route::get('/status_change-{id}/{status}', [WhoWeAreController::class, 'status_update']);
                Route::get('/delete-{id}', [WhoWeAreController::class, 'delete']);
                Route::post('/reorder_whoweare', [WhoWeAreController::class, 'reorder_whoweare']);
            });
            Route::post('footer_features/update', [WebSettingsController::class, 'footer_features_update']);
            Route::get('settings/delete-feature-{id}', [WebSettingsController::class, 'delete_feature']);

            Route::post('tips_settings/update', [WebSettingsController::class, 'tips_settings']);
        });

        // currency-setting
        Route::group(['prefix' => 'currency-settings'], function () {
            Route::get('/', [CurrencyController::class, 'index']);
            Route::get('/currency/edit-{id}', [CurrencyController::class, 'edit']);
            Route::post('/update-{id}', [CurrencyController::class, 'update']);
        });
    });
});
//  ------------------------------- ----------- -----------------------------------------   //
//  -------------------------------  FOR WEB/FRONT  -------------------------------------   //
//  ------------------------------- ----------- -----------------------------------------   //

Route::group(['namespace' => '', 'middleware' => 'landingMiddleware', 'domain' => env('WEBSITE_HOST')], function () {
    if (@helper::appdata('')->landing_page == 1) {
        Route::get('/', [LandingHomeController::class, 'index']);
    }
    Route::post('/emailsubscribe', [LandingHomeController::class, 'emailsubscribe']);
    Route::post('/inquiry', [LandingHomeController::class, 'inquiry']);
    Route::get('/aboutus', [LandingHomeController::class, 'aboutus']);
    Route::get('/privacypolicy', [LandingHomeController::class, 'privacypolicy']);
    Route::get('/refund_policy', [LandingHomeController::class, 'refund_policy']);
    Route::get('/termscondition', [LandingHomeController::class, 'termscondition']);
    Route::get('/blogdetail-{slug}', [BlogController::class, 'pageblogdetail']);
    Route::get('/blogs', [BlogController::class, 'allblogs']);
    Route::get('/faqs', [LandingHomeController::class, 'faqs']);
    Route::get('/contact', [LandingHomeController::class, 'contact']);
    Route::get('/stores', [LandingHomeController::class, 'allstores']);
    Route::post('/getcity', [AdminController::class, 'getcity']);
    Route::get('/themeimages', [LandingHomeController::class, 'themeimages']);
});
Route::post('/product-details/{id}', [HomeController::class, 'details'])->name('front.details');
Route::get('get-products-variant-quantity', [HomeController::class, 'getProductsVariantQuantity']);

Route::post('/orders/checkplan', [HomeController::class, 'checkplan'])->name('front.checkplan');
Route::post('add-to-cart', [HomeController::class, 'addtocart'])->name('front.addtocart');
Route::post('changeqty', [HomeController::class, 'changeqty']);
Route::post('/cart/qtyupdate', [HomeController::class, 'qtyupdate'])->name('front.qtyupdate');

Route::post('/cart/deletecartitem', [HomeController::class, 'deletecartitem'])->name('front.deletecartitem');
Route::post('/orders/paymentmethod', [HomeController::class, 'paymentmethod'])->name('front.whatsapporder');


$storefrontRoutes = function () {
    Route::get('/', [HomeController::class, 'index'])->name('front.home');
    Route::get('/pwa', [HomeController::class, 'index']);
    Route::get('/product_question_answer', [QuestionAnswerController::class, 'product_question_answer']);
    Route::get('/detail-{slug}', [HomeController::class, 'details']);
    Route::get('/product/{id}', [HomeController::class, 'show'])->name('front.home');
    Route::get('/cart', [HomeController::class, 'cart'])->name('front.cart');
    Route::post('/qtycheckurl', [HomeController::class, 'qtycheckurl']);
    Route::get('/checkout', [HomeController::class, 'checkout'])->name('front.checkout');
    Route::post('/managefavorite', [HomeController::class, 'managefavorite']);


    // third party suucess route
    Route::any('/payment', [HomeController::class, 'ordercreate']);
    // third party suucess route

    Route::get('/terms', [HomeController::class, 'terms'])->name('front.terms');
    Route::get('/privacy-policy', [HomeController::class, 'privacy'])->name('front.privacy');
    Route::get('/book', [HomeController::class, 'book'])->name('front.book');
    Route::get('/success/{order_number}', [HomeController::class, 'ordersuccess']);
    Route::get('/privacypolicy', [HomeController::class, 'privacyshow']);
    Route::get('/terms_condition', [HomeController::class, 'terms_condition']);
    Route::get('/aboutus', [HomeController::class, 'aboutus']);
    Route::get('/refund_policy', [HomeController::class, 'refund_policy']);
    Route::post('/timeslot', [HomeController::class, 'timeslot']);
    Route::post('/copycode', [HomeController::class, 'copycode']);
    Route::post('/subscribe', [HomeController::class, 'user_subscribe']);


    Route::get('/login', [WebUserController::class, 'user_login']);
    Route::post('/checklogin-{logintype}', [WebUserController::class, 'check_login']);
    Route::get('/register', [WebUserController::class, 'user_register']);
    Route::get('/forgotpassword', [WebUserController::class, 'userforgotpassword']);

    Route::post('/send_password', [WebUserController::class, 'send_password']);

    Route::post('/register_customer', [WebUserController::class, 'register_customer']);

    Route::get('/contact', [HomeController::class, 'contact']);
    Route::post('/submit', [HomeController::class, 'save_contact']);
    Route::get('/faqs', [HomeController::class, 'faqs']);
    Route::get('/find-order', [HomeController::class, 'find_order']);

    Route::get('/cancel-order/{order_number}', [HomeController::class, 'cancelorder']);

    Route::get('/search', [HomeController::class, 'productseacrh']);

    Route::post('/postreview', [HomeController::class, 'postreview']);
    Route::middleware('UserMiddleware')->group(function () {
        Route::get('/profile', [WebUserController::class, 'profile']);
        Route::post('/updateprofile', [WebUserController::class, 'updateprofile']);
        Route::get('/change-password', [WebUserController::class, 'changepassword']);
        Route::post('/change_password', [WebUserController::class, 'change_password']);
        Route::get('/orders', [WebUserController::class, 'orders']);
        Route::get('/wishlist', [WebUserController::class, 'wishlist']);
        Route::get('/logout', [WebUserController::class, 'logout']);
        Route::get('/delete-password', [HomeController::class, 'deletepassword']);
        Route::get('/deleteaccount', [WebUserController::class, 'deleteaccount']);

        Route::post('/wallet/recharge', [WalletController::class, 'addwallet']);
        Route::any('/addwalletsuccess', [WalletController::class, 'addsuccess']);
        Route::any('/addfail', [WalletController::class, 'addfail']);

        Route::get('/wallet', [WalletController::class, 'wallet']);
        Route::get('/wallet/addmoney', [WalletController::class, 'addmoneywallet']);
    });
};

Route::group(['namespace' => "front", 'domain' => env('WEBSITE_HOST'), 'prefix' => '{vendor}', 'middleware' => 'FrontMiddleware'], $storefrontRoutes);
Route::group(['namespace' => "front", 'domain' => '{store_subdomain}.' . env('WEBSITE_HOST'), 'middleware' => 'FrontMiddleware'], $storefrontRoutes);
Route::group([
    'namespace' => "front",
    'domain' => '{custom_domain}',
    'middleware' => 'FrontMiddleware',
    'where' => ['custom_domain' => '^(?!([^.]+\\.)*' . preg_quote(env('WEBSITE_HOST'), '/') . '$).+'],
], $storefrontRoutes);
