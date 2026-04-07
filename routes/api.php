<?php

use App\Http\Controllers\addons\CouponsController;
use App\Http\Controllers\addons\SocialLoginController;
use App\Http\Controllers\addons\TelegramController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\UserController as VendorController;
use App\Http\Controllers\api\HomeController as VendorHomeController;
use App\Http\Controllers\api\OtherController;
use App\Http\Controllers\api\user\HomeController;
use App\Http\Controllers\api\user\UserController;
use App\Http\Controllers\api\user\CategoryController;
use App\Http\Controllers\api\user\CartController;
use App\Http\Controllers\api\user\OrderController;
use App\Http\Controllers\api\user\OtherController as UserOtherController;
use App\Http\Controllers\addons\MercadoPagoController;
use App\Http\Controllers\addons\ToyyibpayController;
use App\Http\Controllers\addons\MyFatoorahController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['namespace'=>'Api'],function (){
    Route::post('register',[VendorController::class,'register_vendor']);
    Route::post('sociallogin',[SocialLoginController::class,'social_loginvendor']);
    Route::post('login',[VendorController::class,'check_admin_login']);
    Route::post('forgotpassword',[VendorController::class,'forgotpassword']);
    Route::post('changepassword', [VendorController::class, 'change_password']);
    Route::post('editprofile', [VendorController::class, 'edit_profile']);
    Route::get('getcountry', [VendorController::class, 'getcountry']);
    Route::post('getcity', [VendorController::class, 'getcity']);
    Route::post('deletevendor', [VendorController::class, 'deletevendor']);
    Route::get('systemaddon', [VendorHomeController::class, 'systemaddon']);
    Route::post('home',[VendorHomeController::class,'index']);
    Route::post('orderdetail',[VendorHomeController::class,'order_detail']);
    Route::post('orderhistory',[VendorHomeController::class,'order_history']);
    Route::post('statuschange',[VendorHomeController::class,'status_change']);
    
    Route::get('cmspages',[OtherController::class,'getcontent']);
    Route::post('inquiries',[OtherController::class,'inquiries']);
    Route::post('updatepaymentstatus',[OtherController::class,'payment_status']);
    Route::post('updatecustomerdetails',[OtherController::class,'customerinfo']);
    Route::post('updatevendornote',[OtherController::class,'vendor_note']);

    // user app
    Route::post('user/registeruser',[UserController::class,'register_customer']);
    Route::post('user/login',[UserController::class,'login_customer']);
    Route::post('user/sociallogin',[SocialLoginController::class,'social_loginuser']);
    Route::post('user/forgotpassword',[UserController::class,'send_userpassword']);
    Route::post('user/changepassword',[UserController::class,'change_password']);
    Route::post('user/updateprofile',[UserController::class,'updateprofile']);
    Route::post('user/cmspages',[UserOtherController::class,'cmspages']);
    
    //home screen
    Route::post('user/home',[HomeController::class,'home']);
    Route::post('user/paymentmethods',[HomeController::class,'paymentmethods']);
    Route::post('user/systemaddon',[HomeController::class,'systemaddon']);
    Route::post('user/search',[HomeController::class,'search']);
    Route::post('user/promocode', [CouponsController::class, 'promocode']);
    Route::post('user/applypromocode', [CouponsController::class, 'applypromocode']);
    // category
    Route::post('user/allcategory',[CategoryController::class,'allcategory']);
    Route::post('user/categorywiseitems',[CategoryController::class,'categorywiseitems']);
    Route::post('user/itemdetails', [HomeController::class, 'itemdetails']);
    Route::post('user/cart',[CartController::class,'cart']);
    Route::post('user/addtocart',[CartController::class,'addtocart']);
    Route::post('user/qtyupdate',[CartController::class,'qtyupdate']);
    Route::post('user/shippingarea',[CartController::class,'shippingarea']);
    Route::post('user/deletecart',[CartController::class,'deletecartitem']);
    // orders
    Route::post('user/orderhistory',[OrderController::class,'orderhistory']);
    Route::post('user/timeslots',[OrderController::class,'timeslot']);
    Route::post('user/orderdetails',[OrderController::class,'orderdetails']);
    Route::post('user/cancelorder',[OrderController::class,'cancelorder']);
    Route::post('user/qtycheckurl',[OrderController::class,'qtycheckurl']);
    Route::post('user/placeorder',[OrderController::class,'placeorder']);
    
    Route::post('user/tablelist',[HomeController::class,'tablelist']);
    Route::post('user/postreview',[HomeController::class,'postreview']);
    Route::post('user/managefavorite',[HomeController::class,'managefavorite']);
    Route::post('user/wishlist',[UserController::class,'wishlist']);
    Route::post('user/filteration', [HomeController::class, 'filteration']);
    Route::post('user/categorylist', [HomeController::class, 'categorylist']);
    Route::post('user/contact_detail',[UserOtherController::class,'contact_detail']);
    Route::post('user/contact',[UserOtherController::class,'contact']);
    Route::post('user/bloglisting',[HomeController::class,'bloglisting']);

    Route::post('/user/telegram', [TelegramController::class, 'telegram_msg']);

    //Payment-Gateway
    Route::post('user/mercadorequest', [MercadoPagoController::class,'mercadorequestapi']);
    Route::post('user/toyyibpayrequest', [ToyyibpayController::class,'toyyibpayrequestapi']);
    Route::post('user/myfatoorahrequest', [MyFatoorahController::class,'myfatoorahapi']);
    Route::post('user/getvariationprice', [HomeController::class,'getvariationprice']);
});