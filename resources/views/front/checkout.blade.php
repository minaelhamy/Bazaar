@include('front.theme.header')

<section class="breadcrumb-sec bg-change-mode">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-dark"><a class="text-dark color-changer"
                        href="{{ helper::storefront_url($storeinfo) }}">{{ trans('labels.home') }}</a>
                </li>
                <li class="text-muted breadcrumb-item {{ session()->get('direction') == 2 ? 'rtl' : '' }} text-dark active"
                    aria-current="page">{{ trans('labels.checkout') }}
                </li>
            </ol>
        </nav>
    </div>
</section>

<div class="cart-sec">
    <div class="container">
        @if (count($cartdata) > 0)
            <div class="row">
                @if (@helper::checkaddons('cart_checkout_countdown'))
                    @include('front.cart_checkout_countdown')
                @endif
                <div class="col-lg-8">
                    <div class="yourcart-sec">
                        @foreach ($cartdata as $cart)
                            @php
                                $data[] = [
                                    'sub_total' => $cart->item_price * $cart->qty,
                                ];
                            @endphp
                        @endforeach
                        @php
                            $delivery_type = explode('|', @helper::appdata($storeinfo->id)->delivery_type);
                        @endphp
                        @if (@helper::appdata($storeinfo->id)->product_type == 1)
                            <div class="mb-4" id="order_type">
                                <div class="w-100 mb-4 {{ count($delivery_type) >= 2 ? 'd-block' : 'd-none' }}">
                                    <div class="card h-100 bg-light mb-3 mb-md-0">
                                        <div class="card-body">
                                            <div class="delivery-title">
                                                <div class="mb-2">
                                                    <h5 class="border-bottom color-changer pb-2 mb-4">
                                                        <i class="fa-light fa-location-dot"></i>
                                                        <span
                                                            class="px-2 checkoutform-title">{{ trans('labels.delivery_option') }}</span>
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="row g-sm-4 g-3">
                                                @if (count($delivery_type) == 1)
                                                    @if (in_array('delivery', $delivery_type))
                                                        <label for="cart-delivery">
                                                            <input type="radio" name="cart-delivery"
                                                                id="cart-delivery" checked value="1">
                                                        </label>
                                                    @endif
                                                    @if (in_array('pickup', $delivery_type))
                                                        <label for="cart-pickup">
                                                            <input type="radio" name="cart-delivery" id="cart-pickup"
                                                                checked value="2">
                                                        </label>
                                                    @endif
                                                    @if (in_array('table', $delivery_type))
                                                        <label for="cart-table">
                                                            <input type="radio" name="cart-delivery" id="cart-table"
                                                                checked value="3">
                                                        </label>
                                                    @endif
                                                @endif
                                                @if (in_array('delivery', $delivery_type))
                                                    <div class="col-xl-4 col-sm-6">
                                                        <div class="payment-check justify-content-between cp">

                                                            <input type="radio" name="cart-delivery"
                                                                id="cart-delivery"
                                                                class="form-check-input payment-input p-2 opacity-100 position-static m-0"
                                                                value="1" checked>
                                                            <label for="cart-delivery"
                                                                class="option-label cp text-start w-100 d-flex align-items-center">
                                                                <img src="{{ url(env('ASSETPATHURL') . 'front/images/order_delivery.png') }}"
                                                                    alt="delivery" class="bg-white p-1" width="25">
                                                                <span
                                                                    class="px-2 color-changer">{{ trans('labels.delivery') }}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if (in_array('pickup', $delivery_type))
                                                    <div class="col-xl-4 col-sm-6">
                                                        <div class="payment-check justify-content-between cp">

                                                            <input type="radio" name="cart-delivery" id="cart-pickup"
                                                                class="form-check-input payment-input p-2 opacity-100 position-static m-0 label14"
                                                                value="2">
                                                            <label for="cart-pickup"
                                                                class="option-label cp text-start w-100 d-flex align-items-center">
                                                                <img src="{{ url(env('ASSETPATHURL') . 'front/images/pickup.png') }}"
                                                                    alt="delivery" class="bg-white p-1" width="25">
                                                                <span
                                                                    class="px-2 color-changer">{{ trans('labels.pickup') }}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if (in_array('table', $delivery_type))
                                                    <div class="col-xl-4 col-sm-6">

                                                        <div class="payment-check justify-content-between cp">

                                                            <input type="radio" name="cart-delivery" id="cart-table"
                                                                class="form-check-input payment-input p-2 opacity-100 position-static m-0 label14"
                                                                value="3">
                                                            <label for="cart-table"
                                                                class="option-label cp text-start w-100 d-flex align-items-center">
                                                                <img src="{{ url(env('ASSETPATHURL') . 'front/images/dining_table.png') }}"
                                                                    alt="delivery" class="bg-white p-1" width="25">
                                                                <span
                                                                    class="px-2 color-changer">{{ trans('labels.table') }}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if (helper::appdata($storeinfo->id)->ordertype_date_time == 1 &&
                                        (in_array('delivery', $delivery_type) || in_array('pickup', $delivery_type)))
                                    <div class="w-100">
                                        <div class="card h-100 bg-light" id="date_time">
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <h5
                                                        class="customer-title color-changer border-bottom pb-2 mb-4 {{ session()->get('direction') == 2 ? 'text-right' : '' }}">
                                                        <i class="fa-light fa-calendar-days"></i>
                                                        <span
                                                            class="px-2 checkoutform-title">{{ trans('labels.date_time') }}</span>
                                                    </h5>
                                                </div>

                                                <div class="row g-3">
                                                    <div
                                                        class="col-sm-6 delivery-date {{ session()->get('direction') == '2' ? 'text-right' : '' }}">
                                                        <label id="delivery_date"
                                                            class="form-label justify-content-start fw-semibold">{{ trans('labels.delivery_date') }}
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <label id="pickup_date" class="form-label fw-semibold"
                                                            style="display: none;">
                                                            {{ trans('labels.pickup_date') }}
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text"
                                                            class="form-control rounded-2 p-3 delivery_pickup_date"
                                                            name="delivery_date" value="{{ old('delivery_date') }}"
                                                            id="delivery_dt">
                                                    </div>
                                                    <div
                                                        class="col-sm-6 delivery-time {{ session()->get('direction') == '2' ? 'text-right' : '' }}">
                                                        <label id="delivery"
                                                            class="form-label justify-content-start fw-semibold">{{ trans('labels.delivery_time') }}
                                                            <span class="text-danger"> *</span>
                                                        </label>
                                                        <label id="pickup"
                                                            class="form-label justify-content-start fw-semibold"
                                                            style="display: none;">{{ trans('labels.pickup_time') }}
                                                            <span class="text-danger"> *</span>
                                                        </label>
                                                        <label id="store_close"
                                                            class="d-none form-label text-danger label14">{{ trans('labels.today_store_closed') }}</label>
                                                        <select name="delivery_time" id="delivery_time"
                                                            class="form-select rounded-2 p-3">
                                                            <option value="{{ old('delivery_time') }}">
                                                                {{ trans('labels.select') }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>


                            <div class="card mb-4 card-shadow bg-light" id="open">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <h5
                                            class="customer-title color-changer border-bottom pb-2 mb-4  {{ session()->get('direction') == 2 ? 'text-right' : '' }}">
                                            <i class="fa-light fa-truck-fast"></i><span
                                                class="px-2 checkoutform-title">{{ trans('labels.delivery_info') }}</span>
                                        </h5>
                                    </div>
                                    <div
                                        class="row g-3 delivery-sec {{ session()->get('direction') == 2 ? 'text-right' : '' }}">
                                        <div class="col-md-6">
                                            <label for="address"
                                                class="form-label d-flex justify-content-start fw-semibold">{{ trans('labels.address') }}
                                                <span class="text-danger"> *</span>
                                            </label>
                                            <input type="text" class="form-control rounded-2 p-3"
                                                placeholder="{{ trans('labels.address') }}"
                                                @if (env('Environment') == 'sendbox') value="1112 4th Ave" @else value="{{ old('address') }}" @endif
                                                name="address" id="address" required="">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="building"
                                                class="form-label d-flex justify-content-start fw-semibold">{{ trans('labels.building') }}
                                                <span class="text-danger"> *</span>
                                            </label>
                                            <input type="text" class="form-control rounded-2 p-3"
                                                placeholder="{{ trans('labels.building') }}" name="building"
                                                @if (env('Environment') == 'sendbox') value="Seattle" @else value="{{ old('building') }}" @endif
                                                id="building" required="">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="landmark"
                                                class="form-label d-flex justify-content-start fw-semibold">{{ trans('labels.landmark') }}
                                                <span class="text-danger"> *</span>
                                            </label>
                                            <input type="text" class="form-control rounded-2 p-3"
                                                placeholder="{{ trans('labels.landmark') }}" name="landmark"
                                                @if (env('Environment') == 'sendbox') value="Washington" @else value="{{ old('landmark') }}" @endif
                                                id="landmark" required="">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="postal_code"
                                                class="form-label d-flex justify-content-start fw-semibold">{{ trans('labels.pincode') }}
                                                <span class="text-danger"> *</span>
                                            </label>
                                            <input type="text" class="form-control rounded-2 p-3"
                                                placeholder="{{ trans('labels.pincode') }}" id="postal_code"
                                                @if (env('Environment') == 'sendbox') value="98101" @else value="{{ old('pincode') }}" @endif
                                                name="postal_code" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4 card-shadow bg-light" id="tableinfo">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <h5
                                            class="customer-title color-changer border-bottom pb-2 mb-4  {{ session()->get('direction') == 2 ? 'text-right' : '' }}">
                                            <img src="{{ url(env('ASSETPATHURL') . 'front/images/dining_table.png') }}"
                                                alt="delivery" width="25">
                                            <span class="px-2 checkoutform-title">{{ trans('labels.table') }}</span>
                                        </h5>
                                    </div>
                                    <label for="table" class="form-label fw-semibold">{{ trans('labels.tables') }}
                                        <span class="text-danger"> *</span>
                                    </label>
                                    <select name="table" id="table" class="form-select rounded-2 p-3">
                                        <option value="">{{ trans('labels.select') }}</option>
                                        @foreach ($tables as $table)
                                            <option value="{{ $table->id }}">{{ $table->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="card mb-4 card-shadow bg-light">
                            <div class="card-body">
                                <div class="mb-2">
                                    <h5
                                        class="customer-title color-changer border-bottom pb-2 mb-4  {{ session()->get('direction') == 2 ? 'text-right' : '' }}">
                                        <i class="fa-light fa-user"></i><span
                                            class="px-2 checkoutform-title">{{ trans('labels.customer') }}</span>
                                    </h5>
                                </div>
                                <form>
                                    <div class="row g-3 {{ session()->get('direction') == 2 ? 'text-right' : '' }}">
                                        <div class="col-md-6">
                                            <label for="customer_name"
                                                class="form-label d-flex justify-content-start fw-semibold">{{ trans('labels.name') }}
                                                <span class="text-danger"> *</span>
                                            </label>
                                            <input type="text" placeholder="{{ trans('labels.name') }}"
                                                class="form-control rounded-2 p-3" name="customer_name"
                                                @if (env('Environment') == 'sendbox') value="Etha Jaskolski" @else value ="{{ @Auth::user() && @Auth::user()->type == 3 ? @Auth::user()->name : '' }}" @endif
                                                id="customer_name">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="customer_mobile"
                                                class="form-label d-flex justify-content-start fw-semibold">{{ trans('labels.mobile') }}
                                                <span class="text-danger"> *</span>
                                            </label>
                                            <input type="text" class="form-control rounded-2 p-3"
                                                placeholder="{{ trans('labels.mobile') }}" name="customer_mobile"
                                                @if (env('Environment') == 'sendbox') value="937-267-4384" @else value="{{ @Auth::user() && @Auth::user()->type == 3 ? @Auth::user()->mobile : '' }}" @endif
                                                id="customer_mobile" onkeypress="return isNumber(event)">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="customer_email"
                                                class="form-label d-flex justify-content-start fw-semibold">{{ trans('labels.email') }}
                                                <span class="text-danger"> *</span>
                                            </label>
                                            <input type="email" class="form-control rounded-2 p-3"
                                                placeholder="{{ trans('labels.email') }}" name="customer_email"
                                                @if (env('Environment') == 'sendbox') value="etha@gmail.com" @else value="{{ @Auth::user() && @Auth::user()->type == 3 ? @Auth::user()->email : '' }}" @endif
                                                id="customer_email">
                                        </div>
                                    </div>
                                    <input type="hidden" id="vendor" name="vendor"
                                        value="{{ helper::storeinfo($storeinfo->slug)->id }}" />
                                </form>
                            </div>
                        </div>
                        @if (@helper::checkaddons('vendor_tip'))
                            @if (@helper::otherappdata($storeinfo->id)->tips_settings == 1)
                                <div class="card mb-4 card-shadow bg-light">
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <h3
                                                class="select-payment color-changer border-bottom {{ session()->get('direction') == '2' ? 'text-right' : '' }}">
                                                <i class="fa-solid fa-coins"></i><span
                                                    class="px-2 checkoutform-title">{{ trans('labels.tips_pro') }}</span>
                                            </h3>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group m-0">
                                                    <label for="add_amount"
                                                        class="form-label">{{ trans('labels.add_amount') }}</label>
                                                    <input type="number" class="form-control p-3" id="add_amount"
                                                        placeholder="{{ trans('labels.add_amount') }} . . . .">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                        <div class="customer-info mt-3 bg-light card">
                            <div class="mb-2">
                                <h3
                                    class="select-payment border-bottom color-changer {{ session()->get('direction') == '2' ? 'text-right' : '' }}">
                                    <i class="fa-regular fa-credit-card"></i><span
                                        class="px-2 checkoutform-title">{{ trans('labels.payment_option') }}</span>
                                </h3>
                            </div>
                            <div class="row justify-content-center g-3">
                                @foreach ($paymentlist as $key => $payment)
                                    @php
                                        // Check if the current $payment is a system addon and activated
                                        if ($payment->payment_type == '1' || $payment->payment_type == '16') {
                                            $systemAddonActivated = true;
                                        } else {
                                            $systemAddonActivated = false;
                                        }
                                        $addon = App\Models\SystemAddons::where(
                                            'unique_identifier',
                                            $payment->unique_identifier,
                                        )->first();
                                        if ($addon != null && $addon->activated == 1) {
                                            $systemAddonActivated = true;
                                        }
                                    @endphp
                                    @if ($systemAddonActivated)
                                        <label class="form-check-label col-md-6 label14 cp"
                                            for="{{ $payment->payment_type }}">
                                            <div class="payment-check">
                                                <img src="{{ helper::image_path($payment->image) }}"
                                                    class="img-fluid" alt="" width="40px" />

                                                @if ($payment->payment_type == '2')
                                                    <input type="hidden" name="razorpay" id="razorpay"
                                                        value="{{ $payment->public_key }}">
                                                @endif
                                                @if ($payment->payment_type == '3')
                                                    <input type="hidden" name="stripe" id="stripe"
                                                        value="{{ $payment->public_key }}">
                                                @endif
                                                @if ($payment->payment_type == '4')
                                                    <input type="hidden" name="flutterwavekey" id="flutterwavekey"
                                                        value="{{ $payment->public_key }}">
                                                @endif
                                                @if ($payment->payment_type == '5')
                                                    <input type="hidden" name="paystackkey" id="paystackkey"
                                                        value="{{ $payment->public_key }}">
                                                @endif

                                                <p class="m-0">{{ $payment->payment_name }}</p>

                                                @if (Auth::user() && Auth::user()->type == 3)
                                                    @if ($payment->payment_type == 16)
                                                        <span
                                                            class="text-end text-muted">{{ Helper::currency_formate(Auth::user()->wallet, $storeinfo->id) }}</span>
                                                    @endif
                                                @endif

                                                <input
                                                    class="form-check-input payment-input {{ session()->get('direction') == '2' ? 'me-auto' : 'ms-auto' }}"
                                                    type="radio" name="payment" id="{{ $payment->payment_type }}"
                                                    data-payment_type="{{ strtolower($payment->payment_type) }}"
                                                    @if (!$key) {!! 'checked' !!} @endif>

                                                @if (strtolower($payment->payment_type) == '6')
                                                    <input type="hidden" value="{{ $payment->payment_description }}"
                                                        id="bank_payment">
                                                @endif
                                            </div>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="row gx-3 justify-content-between mt-3">
                            <div class="col-xl-3 col-sm-4 col-6">
                                <a href="{{ helper::storefront_url($storeinfo) }}"
                                    class="btn btn-store-outline px-0">{{ trans('labels.continue_shoping') }}</a>
                            </div>
                            <div class="col-xl-3 col-sm-4 col-6">
                                @if ($paymentlist->count() > 0)
                                    <button onclick="Order()"
                                        class="btn btn-store proceed_to_pay px-0 h-100 w-100">{{ trans('labels.proceed_to_pay') }}</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">

                    <div class="payment-side-position">
                        @php
                            $sub_total = array_sum(array_column(@$data, 'sub_total'));
                        @endphp

                        @if (@helper::checkaddons('subscription'))
                            @if (@helper::checkaddons('coupon'))
                                @php
                                    $checkplan = App\Models\Transaction::where('vendor_id', $storeinfo->id)
                                        ->orderByDesc('id')
                                        ->first();
                                    $user = App\Models\User::where('id', $storeinfo->id)->first();
                                    if ($user->allow_without_subscription == 1) {
                                        $coupon = 1;
                                    } else {
                                        $coupon = @$checkplan->coupons;
                                    }
                                @endphp
                                @if ($coupon == 1)
                                    <div class="payment-sec my-3 bg-light card border-0">
                                        <div class="mb-2">
                                            <h3
                                                class="payment-title color-changer border-bottom {{ session()->get('direction') == '2' ? 'text-right' : '' }}">
                                                <i class="fa-light fa-badge-percent"></i><span
                                                    class="px-2 checkoutform-title">{{ trans('labels.apply_offer') }}</span>
                                            </h3>
                                        </div>

                                        <div class="d-flex gap-3 align-items-center">
                                            <input type="text" class="form-control rounded-2 offer-input"
                                                value="{{ Session::has('offer_code') ? Session::get('offer_code') : '' }}"
                                                name="promocode" id="couponcode"
                                                placeholder="{{ trans('labels.coupon_code') }}" readonly>

                                            <button class="btn btn-md mb-0 btn-store d-none" id="btnremove"
                                                onclick="RemoveCoupon()">{{ trans('labels.remove') }}</button>

                                            <button class="btn btn-md mb-0 btn-store d-block" id="btnapply"
                                                onclick="ApplyCoupon()">{{ trans('labels.apply') }}</button>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @else
                            @if (@helper::checkaddons('coupon'))
                                <div class="payment-sec my-3 bg-light card border-0">
                                    <div class="mb-2">
                                        <h3
                                            class="payment-title color-changer border-bottom {{ session()->get('direction') == '2' ? 'text-right' : '' }}">
                                            <i class="fa-light fa-badge-percent"></i><span
                                                class="px-2 checkoutform-title">{{ trans('labels.apply_offer') }}</span>
                                        </h3>
                                    </div>

                                    <div class="d-flex align-items-center gap-3">
                                        <input type="text" class="form-control rounded-2 offer-input"
                                            value="{{ Session::has('offer_code') ? Session::get('offer_code') : '' }}"
                                            name="promocode" id="couponcode"
                                            placeholder="{{ trans('labels.coupon_code') }}" readonly>

                                        <button class="btn btn-md mb-0 btn-store d-none" id="btnremove"
                                            onclick="RemoveCoupon()">{{ trans('labels.remove') }}</button>

                                        <button class="btn btn-md mb-0 btn-store d-block" id="btnapply"
                                            onclick="ApplyCoupon()">{{ trans('labels.apply') }}</button>
                                    </div>
                                </div>
                            @endif
                        @endif
                        @if (@helper::checkaddons('shipping_area'))
                            @if (helper::appdata($storeinfo->id)->shipping_area == 1)
                                @if (count($getshippingarealist) > 0)
                                    <div class="payment-sec my-3 card bg-light border-0" id="shippinginfodiv">
                                        <div class="mb-2">
                                            <h3
                                                class="payment-title color-changer border-bottom {{ session()->get('direction') == '2' ? 'text-right' : '' }}">
                                                <i class="fa-light fa-truck-fast"></i>
                                                <span
                                                    class="px-2 checkoutform-title">{{ trans('labels.shipping_area') }}</span>
                                            </h3>
                                        </div>

                                        <select name="shipping_area" id="shipping_area" class="form-select input-h">
                                            <option value="" selected disabled>{{ trans('labels.select') }}
                                            </option>
                                            @foreach ($getshippingarealist as $shippingarea)
                                                <option value="{{ $shippingarea->id }}"
                                                    data-delivery-charge="{{ $shippingarea->delivery_charge }}"
                                                    data-area-name="{{ $shippingarea->area_name }}">
                                                    {{ $shippingarea->area_name }}
                                                    @if (helper::appdata($storeinfo->id)->min_order_amount_for_free_shipping > $sub_total)
                                                        @if ($shippingarea->delivery_charge > 0)
                                                            {{ trans('labels.delivery_charge') }}
                                                            :
                                                            {{ helper::currency_formate($shippingarea->delivery_charge, @$storeinfo->id) }}
                                                        @endif
                                                    @else
                                                        {{ trans('labels.free_delivery') }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            @endif
                        @endif
                        <div class="payment-sec my-3 bg-light card border-0">
                            <div class="mb-2">
                                <h3
                                    class="payment-title color-changer border-bottom {{ session()->get('direction') == '2' ? 'text-right' : '' }}">
                                    <i class="fa-light fa-file-invoice"></i>
                                    <span
                                        class="px-2 checkoutform-title">{{ trans('labels.payment_summary') }}</span>
                                </h3>
                            </div>
                            <div class="form form-a active">
                                <div class="total-sec">
                                    <div class="sub-total d-flex justify-content-between" id="subtotal">
                                        <h6 class="m-0 fw-semibold color-changer">{{ trans('labels.sub_total') }}
                                        </h6>
                                        <span
                                            class="color-changer">{{ helper::currency_formate($sub_total, $storeinfo->id) }}</span>
                                    </div>

                                    <div class="sub-total d-flex justify-content-between">
                                        <h6 class="m-0 fw-semibold color-changer">{{ trans('labels.discount') }}</h6>
                                        @php
                                            if (Session::get('offer_type') == 1) {
                                                $discount = Session::get('offer_amount');
                                            } else {
                                                $discount = $sub_total * (Session::get('offer_amount') / 100);
                                            }
                                        @endphp
                                        <span id="offer_amount"
                                            class="color-changer">-{{ helper::currency_formate($discount, $storeinfo->id) }}</span>
                                    </div>
                                    @php
                                        $totalcarttax = 0;
                                    @endphp
                                    @foreach ($taxArr['tax'] as $k => $tax)
                                        @php
                                            $rate = $taxArr['rate'][$k];
                                            $totalcarttax += (float) $taxArr['rate'][$k];
                                        @endphp

                                        <div class="sub-total d-flex justify-content-between">

                                            <h6 class="m-0 fw-semibold color-changer">{{ $tax }}</h6>
                                            <span class="color-changer">
                                                {{ helper::currency_formate($rate, $storeinfo->id) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>

                                @if (helper::appdata($storeinfo->id)->product_type == 1)
                                    <div class="sub-total d-flex justify-content-between" id="delivery_charge_hide">
                                        <h6 class="m-0 fw-semibold color-changer">
                                            {{ trans('labels.delivery_charge') }}</h6>
                                        @if (@helper::checkaddons('shipping_area'))
                                            @if (helper::appdata($storeinfo->id)->shipping_area == 1)
                                                @if (count($getshippingarealist) > 0)
                                                    @php
                                                        $grandtotal = $sub_total - $discount + $totalcarttax;
                                                    @endphp
                                                    @if ($sub_total >= helper::appdata($storeinfo->id)->min_order_amount_for_free_shipping)
                                                        <input type="hidden" name="delivery_charge"
                                                            id="delivery_charge" value="0">

                                                        <span
                                                            class="delivery_charge color-changer">{{ trans('labels.free') }}</span>
                                                    @else
                                                        <span
                                                            class="delivery_charge color-changer">{{ helper::currency_formate(0, @$storeinfo->id) }}</span>

                                                        <input type="hidden" name="delivery_charge"
                                                            id="delivery_charge" value="0">
                                                    @endif
                                                @else
                                                    @if ($sub_total >= helper::appdata($storeinfo->id)->min_order_amount_for_free_shipping)
                                                        @php
                                                            $grandtotal = $sub_total - $discount + $totalcarttax;
                                                        @endphp

                                                        <input type="hidden" name="delivery_charge"
                                                            id="delivery_charge" value="0">

                                                        <span
                                                            class="delivery_charge color-changer">{{ trans('labels.free') }}</span>
                                                    @else
                                                        @php
                                                            $grandtotal =
                                                                $sub_total -
                                                                $discount +
                                                                $totalcarttax +
                                                                helper::appdata($storeinfo->id)->shipping_charges;
                                                        @endphp

                                                        <span
                                                            class="delivery_charge color-changer">{{ helper::currency_formate(helper::appdata($storeinfo->id)->shipping_charges, @$storeinfo->id) }}</span>

                                                        <input type="hidden" name="delivery_charge"
                                                            id="delivery_charge"
                                                            value="{{ helper::appdata($storeinfo->id)->shipping_charges }}">
                                                    @endif
                                                @endif
                                            @else
                                                @if ($sub_total >= helper::appdata($storeinfo->id)->min_order_amount_for_free_shipping)
                                                    @php
                                                        $grandtotal = $sub_total - $discount + $totalcarttax;
                                                    @endphp

                                                    <input type="hidden" name="delivery_charge" id="delivery_charge"
                                                        value="0">

                                                    <span
                                                        class="delivery_charge color-changer">{{ trans('labels.free') }}</span>
                                                @else
                                                    @php
                                                        $grandtotal =
                                                            $sub_total -
                                                            $discount +
                                                            $totalcarttax +
                                                            helper::appdata($storeinfo->id)->shipping_charges;
                                                    @endphp

                                                    <span
                                                        class="delivery_charge color-changer">{{ helper::currency_formate(helper::appdata($storeinfo->id)->shipping_charges, @$storeinfo->id) }}</span>

                                                    <input type="hidden" name="delivery_charge" id="delivery_charge"
                                                        value="{{ helper::appdata($storeinfo->id)->shipping_charges }}">
                                                @endif
                                            @endif
                                        @else
                                            @if ($sub_total >= helper::appdata($storeinfo->id)->min_order_amount_for_free_shipping)
                                                @php
                                                    $grandtotal = $sub_total - $discount + $totalcarttax;
                                                @endphp

                                                <input type="hidden" name="delivery_charge" id="delivery_charge"
                                                    value="0">

                                                <span
                                                    class="delivery_charge color-changer">{{ trans('labels.free') }}</span>
                                            @else
                                                @php
                                                    $grandtotal =
                                                        $sub_total -
                                                        $discount +
                                                        $totalcarttax +
                                                        helper::appdata($storeinfo->id)->shipping_charges;
                                                @endphp

                                                <span
                                                    class="delivery_charge color-changer">{{ helper::currency_formate(helper::appdata($storeinfo->id)->shipping_charges, @$storeinfo->id) }}</span>

                                                <input type="hidden" name="delivery_charge" id="delivery_charge"
                                                    value="{{ helper::appdata($storeinfo->id)->shipping_charges }}">
                                            @endif
                                        @endif
                                    </div>
                                @endif
                                <p class="cart-total border-top text-success">{{ trans('labels.total_amount') }}
                                    <span id="total_amount" class="text-success">
                                        {{ helper::currency_formate($grandtotal, $storeinfo->id) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4 card-shadow bg-light">
                        <div class="card-body">
                            <h5 class="customer-title color-changer border-bottom pb-2 mb-4">
                                <i class="fa-light fa-comment-dots"></i><span
                                    class="px-2 checkoutform-title">{{ trans('labels.product_notes') }}</span>
                            </h5>
                            <div class="mb-3">
                                <label for="notes"
                                    class="form-label d-flex justify-content-start fw-semibold">{{ trans('labels.note') }}</label>
                                <textarea id="notes" name="notes" placeholder="{{ trans('labels.enter_order_note') }}"
                                    class="form-control rounded-2 p-3"></textarea>
                            </div>
                        </div>
                    </div>
                    @include('front.service-trusted')
                </div>
            </div>
        @else
            @include('front.no_data')
        @endif
    </div>

    <!-- offers-label -->
    @if (@helper::checkaddons('subscription'))
        @if (@helper::checkaddons('coupon'))
            @php
                $checkplan = App\Models\Transaction::where('vendor_id', @$storeinfo->id)
                    ->orderByDesc('id')
                    ->first();
                $user = App\Models\User::where('id', @$storeinfo->id)->first();
                if ($user->allow_without_subscription == 1) {
                    $coupon = 1;
                } else {
                    $coupon = @$checkplan->coupons;
                }
            @endphp
            @if ($coupon == 1)
                <div data-bs-toggle="offcanvas" data-bs-target="#offerslabel" aria-controls="offcanvasExample">
                    <div
                        class="offers-label {{ session()->get('direction') == 2 ? 'offers-label-rtl rtl' : 'offers-label-ltr' }}">
                        <i class="fa-light fa-badge-percent text-white"></i>
                        <div class="offers-label-name">{{ trans('labels.offers') }}</div>
                    </div>
                </div>
            @endif
        @endif
    @else
        @if (@helper::checkaddons('coupon'))
            <div data-bs-toggle="offcanvas" data-bs-target="#offerslabel" aria-controls="offcanvasExample">
                <div
                    class="offers-label {{ session()->get('direction') == 2 ? 'offers-label-rtl' : 'offers-label-ltr' }}">
                    <i class="fa-light fa-badge-percent text-white"></i>
                    <div class="offers-label-name">{{ trans('labels.offers') }}</div>
                </div>
            </div>
        @endif
    @endif


    <!-- offers-label sidebar -->
    <div class="offcanvas {{ session()->get('direction') == 2 ? 'offcanvas-start' : 'offcanvas-end' }}"
        tabindex="-1" id="offerslabel" aria-labelledby="offerslabelLabel">
        <div class="offcanvas-header justify-content-between border-bottom">
            <h5 class="offcanvas-title color-changer offers-title" id="offerslabelLabel"><i
                    class="fa-light fa-badge-percent"></i>
                {{ trans('labels.coupons_offers') }}
            </h5>
            <button type="button" class="bg-transparent border-0 m-0" data-bs-dismiss="offcanvas"
                aria-label="Close">
                <i class="fa-regular fa-xmark fs-4 color-changer"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            @if (count(helper::getcoupons(@$storeinfo->id)) > 0)
                @foreach (helper::getcoupons(@$storeinfo->id) as $key => $coupon)
                    <div class="card border p-0 mb-3">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="coupons_card"
                                    id="span{{ $key }}">{{ $coupon->offer_code }}</span>
                                @if (str_contains(Request()->url(), 'checkout'))
                                    <p class="cp copy-code fw-500 color-changer" id="{{ $coupon->offer_code }}"
                                        onclick="copyToClipboard(this.id)">
                                        {{ trans('labels.copy_code') }}
                                    </p>
                                @endif
                            </div>

                            <h5 class="m-0 coupon-label color-changer line-2">{{ $coupon->offer_name }}</h5>
                            <p class="text-muted fw-400 fs-7 pt-2 line-3">
                                {{ $coupon->description }}
                            </p>
                        </div>
                    </div>
                @endforeach
            @else
                <h5 class="pt-3 m-0 color-changer coupon-label line-2">{{ trans('labels.no_offer_found') }}</h5>
            @endif
        </div>
    </div>

    <input type="hidden" id="delivery_time_required" value="{{ trans('messages.delivery_time_required') }}">
    <input type="hidden" id="delivery_date_required" value="{{ trans('messages.delivery_date_required') }}">
    <input type="hidden" id="address_required" value="{{ trans('messages.address_required') }}">
    <input type="hidden" id="no_required" value="{{ trans('messages.no_required') }}">
    <input type="hidden" id="landmark_required" value="{{ trans('messages.landmark_required') }}">
    <input type="hidden" id="pincode_required" value="{{ trans('messages.pincode_required') }}">
    <input type="hidden" id="pickup_date_required" value="{{ trans('messages.pickup_date_required') }}">
    <input type="hidden" id="pickup_time_required" value="{{ trans('messages.pickup_time_required') }}">
    <input type="hidden" id="customer_mobile_required" value="{{ trans('messages.customer_mobile_required') }}">
    <input type="hidden" id="customer_email_required" value="{{ trans('messages.customer_email_required') }}">
    <input type="hidden" id="valid_email" value="{{ trans('messages.valid_email') }}">
    <input type="hidden" id="customer_name_required" value="{{ trans('messages.customer_name_required') }}">
    <input type="hidden" id="table_required" value="{{ trans('messages.table_required') }}">
    <input type="hidden" id="delivery_area_required" value="{{ trans('messages.delivery_area') }}">
    <input type="hidden" id="currency" value="{{ helper::appdata($storeinfo->id)->currency }}">
    <input type="hidden" name="totaltax" id="totaltax" value="{{ @$totalcarttax }}">
    <form action="{{ url('/orders/paypalrequest') }}" method="post" class="d-none">
        {{ csrf_field() }}
        <input type="hidden" name="return" value="2">
        <input type="submit" class="callpaypal" name="submit">
    </form>
    <input type="hidden" name="sloturl" id="sloturl" value="{{ helper::storefront_url($storeinfo, '/timeslot') }}">
    <input type="hidden" name="store_id" id="store_id" value="{{ $storeinfo->id }}">
    <input type="hidden" name="copycodeurl" id="copycodeurl"
        value="{{ helper::storefront_url($storeinfo, '/copycode') }}">
    <input type="hidden" name="sub_total" id="sub_total" value="{{ @$sub_total }}">
    <input type="hidden" name="tax" id="tax" value="{{ implode('|', $taxArr['rate']) }}">
    <input type="hidden" name="tax_name" id="tax_name" value="{{ implode('|', $taxArr['tax']) }}">
    <input type="hidden" name="grand_total" id="grand_total" value="{{ number_format(@$grandtotal) }}">
    <input type="hidden" name="discount_amount" id="discount_amount" value="{{ @$discount }}">
    <input type="hidden" name="couponcode" id="couponcode" value="{{ Session::get('offer_code') }}">
    <input type="hidden" name="buynow_key" id="buynow_key" value="{{ request()->get('buy_now') }}">

</div>

<!-- newsletter -->
@include('front.newsletter')
<!-- newsletter -->

@include('front.theme.footer')
{{-- checkout js --}}

<!-- Modal -->
<div class="modal fade" id="modalbankdetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="modalbankdetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header justify-content-between">
                <h5 class="modal-title" id="modalbankdetailsLabel">{{ trans('labels.banktransfer') }}</h5>
                <button type="button" class="btn-close m-0 bg-white border-0" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form enctype="multipart/form-data" action="{{ URL::to('/orders/paymentmethod') }}" method="POST">
                <div class="modal-body">
                    @csrf

                    <input type="hidden" name="payment_type" id="payment_type" value="">
                    <input type="hidden" name="modal_customer_name" id="modal_customer_name" value="">
                    <input type="hidden" name="modal_customer_email" id="modal_customer_email" value="">
                    <input type="hidden" name="modal_customer_mobile" id="modal_customer_mobile" value="">
                    <input type="hidden" name="modal_delivery_date" id="modal_delivery_date" value="">
                    <input type="hidden" name="modal_delivery_time" id="modal_delivery_time" value="">
                    <input type="hidden" name="modal_delivery_area" id="modal_delivery_area" value="">
                    <input type="hidden" name="modal_delivery_charge" id="modal_delivery_charge" value="">
                    <input type="hidden" name="modal_address" id="modal_address" value="">
                    <input type="hidden" name="modal_landmark" id="modal_landmark" value="">
                    <input type="hidden" name="modal_postal_code" id="modal_postal_code" value="">
                    <input type="hidden" name="modal_building" id="modal_building" value="">
                    <input type="hidden" name="modal_message" id="modal_message" value="">
                    <input type="hidden" name="modal_subtotal" id="modal_subtotal" value="">
                    <input type="hidden" name="modal_discount_amount" id="modal_discount_amount" value="">
                    <input type="hidden" name="modal_couponcode" id="modal_couponcode" value="">
                    <input type="hidden" name="modal_ordertype" id="modal_ordertype" value="">
                    <input type="hidden" name="modal_vendor_id" id="modal_vendor_id" value="">
                    <input type="hidden" name="modal_grand_total" id="modal_grand_total" value="">
                    <input type="hidden" name="modal_tax" id="modal_tax" value="">
                    <input type="hidden" name="modal_tax_name" id="modal_tax_name" value="">
                    <input type="hidden" name="modal_order_type" id="modal_order_type" value="">
                    <input type="hidden" name="modal_table" id="modal_table" value="">
                    <input type="hidden" name="modal_tablename" id="modal_tablename" value="">
                    <input type="hidden" name="modal_tips" id="modal_tips" value="">
                    <input type="hidden" name="modal_buynow" id="modal_buynow" value="">
                    <p>{{ trans('labels.payment_description') }}</p>
                    <hr>
                    <p class="payment_description" id="payment_description"></p>
                    <hr>
                    <div class="form-group col-md-12">
                        <label for="screenshot"> {{ trans('labels.screenshot') }} </label>
                        <div class="controls">
                            <input type="file" name="screenshot" id="screenshot"
                                class="form-control  @error('screenshot') is-invalid @enderror" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger px-sm-4 p-0 p-2"
                        data-bs-dismiss="modal">{{ trans('labels.close') }}</button>
                    <button type="submit" class="btn btn-primary px-sm-4 p-0 p-2"> {{ trans('labels.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://checkout.stripe.com/v2/checkout.js"></script>
<script src="https://checkout.flutterwave.com/v3.js"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
    var formate = "{{ helper::currencyinfo($storeinfo->id)->currency_formate }}";
</script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    var showbutton = "{{ Session::has('offer_code') }}";
    var delivery_type = "{{ helper::appdata($storeinfo->id)->delivery_type }}";
    var min_order_amount_for_free_shipping =
        "{{ helper::appdata(@$storeinfo->id)->min_order_amount_for_free_shipping }}";
    var dtype = delivery_type.split('|');
    $(document).ready(function() {
        if (showbutton == true) {
            $('#btnremove').removeClass('d-none');
            $('#btnapply').addClass('d-none');
        } else {
            $('#btnremove').addClass('d-none');
            $('#btnapply').removeClass('d-none');
        }
        $("input[name$='cart-delivery']").click(function() {
            var test = $(this).val();
            if (test == 1) {
                $("#open").show();
                // $('#order_type').addClass('d-md-flex');
                $("#date_time").show();
                $("#delivery_charge_hide").removeClass('d-none');
                $("#delivery").show();
                $("#pickup").hide();
                $("#delivery_date").show();
                $("#shippinginfodiv").show();
                $("#pickup_date").hide();
                $('#tableinfo').hide();
                var sub_total = parseFloat($('#sub_total').val());
                var delivery_charge = parseFloat($('#delivery_charge').val());
                var tax = "{{ @$totalcarttax }}";

                var discount_amount = parseFloat($('#discount_amount').val());

                if (isNaN(discount_amount) || discount_amount == 0) {
                    $('#total_amount').text(currency_formate(parseFloat(sub_total) + parseFloat(tax) +
                        parseFloat(delivery_charge)));
                    $('#grand_total').val((parseFloat(sub_total) + parseFloat(tax) + parseFloat(
                        delivery_charge)).toFixed(formate));
                } else {

                    $('#total_amount').text(currency_formate(parseFloat(sub_total) + parseFloat(tax) +
                        parseFloat(delivery_charge) - parseFloat(discount_amount)));
                    $('#grand_total').val((parseFloat(sub_total) + parseFloat(tax) + parseFloat(
                        delivery_charge) - parseFloat(discount_amount)).toFixed(
                        2));
                }
            } else if (test == 2) {
                $("#open").hide();
                // $('#order_type').addClass('d-md-flex');
                $("#date_time").show();
                $("#delivery_charge_hide").addClass('d-none');
                $("#delivery").hide();
                $("#pickup").show();
                $("#delivery_date").hide();
                $("#shippinginfodiv").hide();
                $("#pickup_date").show();
                $('#tableinfo').hide();
                var sub_total = parseFloat($('#sub_total').val());
                var tax = "{{ @$totalcarttax }}";
                var discount_amount = parseFloat($('#discount_amount').val());
                if (isNaN(discount_amount)) {
                    $('#total_amount').text(currency_formate(parseFloat(sub_total) + parseFloat(tax)));
                    $('#grand_total').val((parseFloat(sub_total) + parseFloat(tax)).toFixed(formate));
                } else {
                    $('#total_amount').text(currency_formate(parseFloat(sub_total) + parseFloat(tax) -
                        parseFloat(discount_amount)));
                    $('#grand_total').val((parseFloat(sub_total) + parseFloat(tax) - parseFloat(
                        discount_amount)).toFixed(formate));
                }
            } else if (test == 3) {
                // $('#order_type').removeClass('d-md-flex');
                $("#open").hide();
                $('#tableinfo').show();
                $("#delivery_charge_hide").addClass('d-none');
                $("#delivery").hide();
                $("#pickup").show();
                $("#delivery_date").hide();
                $("#shippinginfodiv").hide();
                $("#pickup_date").show();
                $("#date_time").hide();
                var sub_total = parseFloat($('#sub_total').val());
                var tax = "{{ @$totalcarttax }}";
                var discount_amount = parseFloat($('#discount_amount').val());
                if (isNaN(discount_amount)) {
                    $('#total_amount').text(currency_formate(parseFloat(sub_total) + parseFloat(tax)));
                    $('#grand_total').val((parseFloat(sub_total) + parseFloat(tax)).toFixed(formate));
                } else {
                    $('#total_amount').text(currency_formate(parseFloat(sub_total) + parseFloat(tax) -
                        parseFloat(discount_amount)));
                    $('#grand_total').val((parseFloat(sub_total) + parseFloat(tax) - parseFloat(
                        discount_amount)).toFixed(formate));
                }
            }
        });

        $(function() {
            for (var i = 0; i < dtype.length; i++) {
                if (i == 0) {
                    $("input[id$='cart-" + dtype[i] + "']").click();
                }
            }
        });
    });

    var minorderamount = "{{ helper::appdata($storeinfo->id)->min_order_amount }}";

    function Order() {
        var sub_total = parseFloat($('#sub_total').val());
        var vendor = $('#vendor').val();

        if (parseInt(minorderamount) >= parseInt(sub_total)) {
            toastr.error('{{ trans('messages.min_order_amount_required') }}' + minorderamount);
            return false;
        }
        var tax = $('#tax').val();
        var tax_name = $('#tax_name').val();
        if ($("#add_amount").val() != "" && $("#add_amount").val() != undefined) {
            var tips = $("#add_amount").val();
            var grand_total =
                parseFloat($('#grand_total').val()) +
                parseFloat($("#add_amount").val());
        } else {
            var tips = 0;
            var grand_total = parseFloat($('#grand_total').val());
        }
        var delivery_time = $('#delivery_time').val();
        var delivery_date = $('#delivery_dt').val();
        var delivery_area = $("#shipping_area").find(":selected").attr("data-area-name");
        var delivery_charge = parseFloat($('#delivery_charge').val());
        var discount_amount = parseFloat($('#discount_amount').val());
        var couponcode = $('#couponcode').val();

        var order_type;
        if ("{{ helper::appdata($storeinfo->id)->product_type == 1 }}") {
            order_type = $("input:radio[name=cart-delivery]:checked").val();
        } else {
            order_type = 5;
        }
        var address = $('#address').val();
        var postal_code = $('#postal_code').val();
        var building = $('#building').val();
        var landmark = $('#landmark').val();
        var notes = $('#notes').val();
        var customer_name = $('#customer_name').val();
        var customer_email = $('#customer_email').val();
        var customer_mobile = $('#customer_mobile').val();

        var payment_type = $('input[name="payment"]:checked').attr("data-payment_type");
        var flutterwavekey = $('#flutterwavekey').val();
        var paystackkey = $('#paystackkey').val();
        var table = $('#table').val();
        var tablename = $("#table option:selected").text();

        if (order_type == "1") {
            if ("{{ helper::appdata($storeinfo->id)->ordertype_date_time == 1 }}") {
                if (delivery_date == "") {
                    toastr.error($('#delivery_date_required').val());
                    return false;
                } else if (delivery_time == "") {
                    toastr.error($('#delivery_time_required').val());
                    return false;
                }
            }
            if (address == "") {
                toastr.error($('#address_required').val());
                return false;
            } else if (landmark == "") {
                toastr.error($('#landmark_required').val());
                return false;
            } else if (building == "") {
                toastr.error($('#no_required').val());
                return false;
            } else if (postal_code == "") {
                toastr.error($('#pincode_required').val());
                return false;
            } else if (customer_name == "") {
                toastr.error($('#customer_name_required').val());
                return false;
            } else if (customer_mobile == "") {
                toastr.error($('#customer_mobile_required').val());
                return false;
            } else if (customer_email == "") {
                toastr.error($('#customer_email_required').val());
                return false;
            } else if (!validateEmail(customer_email)) {
                toastr.error($('#valid_email').val());
                return false;
            } else if ($("#shipping_area").find(":selected").val() == "") {
                toastr.error($('#delivery_area_required').val());
                return false;
            }
        } else if (order_type == "2") {
            if ("{{ helper::appdata($storeinfo->id)->ordertype_date_time == 1 }}") {
                if (delivery_date == "") {
                    toastr.error($('#pickup_date_required').val());
                    return false;
                } else if (delivery_time == "") {
                    toastr.error($('#pickup_time_required').val());
                    return false;
                }
            }
            if (customer_name == "") {
                toastr.error($('#customer_name_required').val());
                return false;
            } else if (customer_mobile == "") {
                toastr.error($('#customer_mobile_required').val());
                return false;
            } else if (customer_email == "") {
                toastr.error($('#customer_email_required').val());
                return false;
            } else if (!validateEmail(customer_email)) {
                toastr.error($('#valid_email').val());
                return false;
            }
        } else if (order_type == "3") {
            if (table == "") {
                toastr.error($('#table_required').val());
                return false;
            } else if (customer_name == "") {
                toastr.error($('#customer_name_required').val());
                return false;
            } else if (customer_mobile == "") {
                toastr.error($('#customer_mobile_required').val());
                return false;
            } else if (customer_email == "") {
                toastr.error($('#customer_email_required').val());
                return false;
            } else if (!validateEmail(customer_email)) {
                toastr.error($('#valid_email').val());
                return false;
            }
        }
        $('.proceed_to_pay').prop("disabled", true);
        $('.proceed_to_pay').html('<span class="loader"></span>');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ URL::to('/orders/checkplan') }}",
            data: {
                vendor_id: vendor,
            },
            method: 'POST',
            success: function(response) {
                if (response.status == 1) {
                    //COD or Wallet
                    if (payment_type == "1" || payment_type == "16") {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ URL::to('/orders/paymentmethod') }}",
                            data: {
                                sub_total: sub_total,
                                tax: tax,
                                tax_name: tax_name,
                                grand_total: grand_total,
                                delivery_time: delivery_time,
                                delivery_date: delivery_date,
                                delivery_area: delivery_area,
                                delivery_charge: delivery_charge,
                                discount_amount: discount_amount,
                                couponcode: couponcode,
                                order_type: order_type,
                                address: address,
                                postal_code: postal_code,
                                building: building,
                                landmark: landmark,
                                notes: notes,
                                customer_name: customer_name,
                                customer_email: customer_email,
                                customer_mobile: customer_mobile,
                                vendor_id: vendor,
                                payment_type: payment_type,
                                table: table,
                                tablename: tablename,
                                tips: tips,
                                buynow: $('#buynow_key').val(),
                            },
                            method: 'POST',
                            success: function(response) {
                                if (response.status == 1) {
                                    window.location.href =
                                        "{{ helper::storefront_url($storeinfo, '/success') }}/" + response
                                        .order_number;
                                } else {
                                    $('.proceed_to_pay').html(
                                        "{{ trans('labels.proceed_to_pay') }}");
                                    $('.proceed_to_pay').prop("disabled", false);
                                    toastr.error(response.message);
                                    return false;
                                }
                            },
                            error: function(error) {
                                $('.proceed_to_pay').html(
                                    "{{ trans('labels.proceed_to_pay') }}");
                                $('.proceed_to_pay').prop("disabled", false);
                                toastr.error(wrong);
                                return false;
                            }
                        });
                    }

                    //Razorpay
                    if (payment_type == "2") {
                        var options = {
                            "key": $('#razorpay').val(),
                            "amount": (parseInt(grand_total * 100)), // 2000 paise = INR 20
                            "name": "{{ helper::appdata($storeinfo->id)->website_title }}",
                            "description": "Order payment",
                            "image": "{{ helper::appdata(@$storeinfo->id)->image }}",
                            "handler": function(response) {
                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                            'content')
                                    },
                                    url: "{{ URL::to('/orders/paymentmethod') }}",
                                    type: 'post',
                                    dataType: 'json',
                                    data: {
                                        payment_id: response.razorpay_payment_id,
                                        sub_total: sub_total,
                                        tax: tax,
                                        tax_name: tax_name,
                                        grand_total: grand_total,
                                        delivery_time: delivery_time,
                                        delivery_date: delivery_date,
                                        delivery_area: delivery_area,
                                        delivery_charge: delivery_charge,
                                        discount_amount: discount_amount,
                                        couponcode: couponcode,
                                        order_type: order_type,
                                        address: address,
                                        postal_code: postal_code,
                                        building: building,
                                        landmark: landmark,
                                        notes: notes,
                                        customer_name: customer_name,
                                        customer_email: customer_email,
                                        customer_mobile: customer_mobile,
                                        vendor_id: vendor,
                                        payment_type: payment_type,
                                        table: table,
                                        tablename: tablename,
                                        tips: tips,
                                        buynow: $('#buynow_key').val(),
                                    },
                                    success: function(response) {
                                        if (response.status == 1) {
                                            window.location.href =
                                                "{{ helper::storefront_url($storeinfo, '/success') }}/" +
                                                response.order_number;
                                        } else {
                                            $('.proceed_to_pay').html(
                                                "{{ trans('labels.proceed_to_pay') }}"
                                            );
                                            $('.proceed_to_pay').prop("disabled",
                                                false);
                                            toastr.error(response.message);
                                            return false;
                                        }
                                    },
                                    error: function(error) {
                                        $('.proceed_to_pay').html(
                                            "{{ trans('labels.proceed_to_pay') }}"
                                        );
                                        $('.proceed_to_pay').prop("disabled", false);
                                        toastr.error(wrong);
                                        return false;
                                    }
                                });
                            },
                            "prefill": {
                                "contact": customer_mobile,
                                "email": customer_email,
                                "name": customer_name,
                            },
                            "theme": {
                                "color": "#366ed4"
                            },
                            "modal": {
                                "ondismiss": function() {
                                    $('.proceed_to_pay').html(
                                        "{{ trans('labels.proceed_to_pay') }}");
                                    $('.proceed_to_pay').prop("disabled", false);
                                }
                            },
                        };
                        var rzp1 = new Razorpay(options);
                        rzp1.open();
                        e.preventDefault();
                    }

                    //Stripe
                    if (payment_type == "3") {
                        var handler = StripeCheckout.configure({
                            key: $('#stripe').val(),
                            image: "{{ helper::appdata(@$storeinfo->id)->image }}",
                            locale: 'auto',
                            token: function(token) {
                                // You can access the token ID with `token.id`.
                                // Get the token ID to your server-side code for use.
                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                            .attr('content')
                                    },
                                    url: "{{ URL::to('/orders/paymentmethod') }}",
                                    data: {
                                        stripeToken: token.id,
                                        sub_total: sub_total,
                                        tax: tax,
                                        tax_name: tax_name,
                                        grand_total: grand_total,
                                        delivery_time: delivery_time,
                                        delivery_date: delivery_date,
                                        delivery_area: delivery_area,
                                        delivery_charge: delivery_charge,
                                        discount_amount: discount_amount,
                                        couponcode: couponcode,
                                        order_type: order_type,
                                        address: address,
                                        postal_code: postal_code,
                                        building: building,
                                        landmark: landmark,
                                        notes: notes,
                                        customer_name: customer_name,
                                        customer_email: customer_email,
                                        customer_mobile: customer_mobile,
                                        vendor_id: vendor,
                                        payment_type: payment_type,
                                        table: table,
                                        tablename: tablename,
                                        tips: tips,
                                        buynow: $('#buynow_key').val(),
                                    },
                                    method: 'POST',
                                    success: function(response) {
                                        if (response.status == 1) {
                                            window.location.href =
                                                "{{ helper::storefront_url($storeinfo, '/success') }}/" +
                                                response.order_number;
                                        } else {
                                            $('.proceed_to_pay').html(
                                                "{{ trans('labels.proceed_to_pay') }}"
                                            );
                                            $('.proceed_to_pay').prop("disabled",
                                                false);
                                            toastr.error(response.message);
                                            return false;
                                        }
                                    },
                                    error: function(error) {
                                        $('.proceed_to_pay').html(
                                            "{{ trans('labels.proceed_to_pay') }}"
                                        );
                                        $('.proceed_to_pay').prop("disabled",
                                            false);
                                        toastr.error(wrong);
                                        return false;
                                    }
                                });
                            },
                            closed: function() {
                                $('.proceed_to_pay').html(
                                    "{{ trans('labels.proceed_to_pay') }}");
                                $('.proceed_to_pay').prop("disabled", false);
                            }
                        });
                        //Stripe Popup
                        handler.open({
                            name: "{{ helper::appdata($storeinfo->id)->website_title }}",
                            description: 'Order payment',
                            amount: grand_total * 100,
                            currency: "USD",
                            email: customer_email
                        });
                        e.preventDefault();
                        // Close Checkout on page navigation:
                        $(window).on('popstate', function() {
                            handler.close();
                        });
                    }

                    //Flutterwave
                    if (payment_type == "4") {
                        FlutterwaveCheckout({
                            public_key: flutterwavekey,
                            tx_ref: customer_name,
                            amount: grand_total,
                            currency: "NGN",
                            payment_options: " ",
                            customer: {
                                email: customer_email,
                                phone_number: customer_mobile,
                                name: customer_name,
                            },
                            callback: function(data) {
                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                            .attr('content')
                                    },
                                    url: "{{ URL::to('/orders/paymentmethod') }}",
                                    method: 'POST',
                                    dataType: 'json',
                                    data: {
                                        payment_id: data.flw_ref,
                                        sub_total: sub_total,
                                        tax: tax,
                                        tax_name: tax_name,
                                        grand_total: grand_total,
                                        delivery_time: delivery_time,
                                        delivery_date: delivery_date,
                                        delivery_area: delivery_area,
                                        delivery_charge: delivery_charge,
                                        discount_amount: discount_amount,
                                        couponcode: couponcode,
                                        order_type: order_type,
                                        address: address,
                                        postal_code: postal_code,
                                        building: building,
                                        landmark: landmark,
                                        notes: notes,
                                        customer_name: customer_name,
                                        customer_email: customer_email,
                                        customer_mobile: customer_mobile,
                                        vendor_id: vendor,
                                        payment_type: payment_type,
                                        table: table,
                                        tablename: tablename,
                                        tips: tips,
                                        buynow: $('#buynow_key').val(),
                                    },
                                    success: function(response) {
                                        if (response.status == 1) {
                                            window.location.href =
                                                "{{ helper::storefront_url($storeinfo, '/success') }}/" +
                                                response.order_number;
                                        } else {
                                            $('.proceed_to_pay').html(
                                                "{{ trans('labels.proceed_to_pay') }}"
                                            );
                                            $('.proceed_to_pay').prop("disabled",
                                                false);
                                            toastr.error(response.message);
                                            return false;
                                        }
                                    },
                                    error: function(error) {
                                        $('.proceed_to_pay').html(
                                            "{{ trans('labels.proceed_to_pay') }}"
                                        );
                                        $('.proceed_to_pay').prop("disabled",
                                            false);
                                        toastr.error(wrong);
                                        return false;
                                    }
                                });
                            },
                            onclose: function() {
                                $('.proceed_to_pay').html(
                                    "{{ trans('labels.proceed_to_pay') }}");
                                $('.proceed_to_pay').prop("disabled", false);
                            },
                            customizations: {
                                title: "{{ helper::appdata($storeinfo->id)->website_title }}",
                                description: "Order payment",
                                logo: "{{ helper::appdata(@$storeinfo->id)->image }}",
                            },
                        });
                    }

                    //Paystack
                    if (payment_type == "5") {
                        let handler = PaystackPop.setup({
                            key: paystackkey,
                            email: customer_email,
                            amount: grand_total * 100,
                            currency: 'GHS', // Use GHS for Ghana Cedis or USD for US Dollars
                            ref: 'trx_' + Math.floor((Math.random() * 1000000000) + 1),
                            label: "Order payment",
                            onClose: function() {
                                $('.proceed_to_pay').html(
                                    "{{ trans('labels.proceed_to_pay') }}");
                                $('.proceed_to_pay').prop("disabled", false);
                            },
                            callback: function(response) {
                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                            .attr('content')
                                    },
                                    url: "{{ URL::to('/orders/paymentmethod') }}",
                                    data: {
                                        payment_id: response.trxref,
                                        sub_total: sub_total,
                                        tax: tax,
                                        tax_name: tax_name,
                                        grand_total: grand_total,
                                        delivery_time: delivery_time,
                                        delivery_date: delivery_date,
                                        delivery_area: delivery_area,
                                        delivery_charge: delivery_charge,
                                        discount_amount: discount_amount,
                                        couponcode: couponcode,
                                        order_type: order_type,
                                        address: address,
                                        postal_code: postal_code,
                                        building: building,
                                        landmark: landmark,
                                        notes: notes,
                                        customer_name: customer_name,
                                        customer_email: customer_email,
                                        customer_mobile: customer_mobile,
                                        vendor_id: vendor,
                                        payment_type: payment_type,
                                        table: table,
                                        tablename: tablename,
                                        tips: tips,
                                        buynow: $('#buynow_key').val(),
                                    },
                                    method: 'POST',
                                    success: function(response) {
                                        if (response.status == 1) {
                                            window.location.href =
                                                "{{ helper::storefront_url($storeinfo, '/success') }}/" +
                                                response.order_number;
                                        } else {
                                            $('.proceed_to_pay').html(
                                                "{{ trans('labels.proceed_to_pay') }}"
                                            );
                                            $('.proceed_to_pay').prop("disabled",
                                                false);
                                            toastr.error(response.message);
                                            return false;
                                        }
                                    },
                                    error: function(error) {
                                        $('.proceed_to_pay').html(
                                            "{{ trans('labels.proceed_to_pay') }}"
                                        );
                                        $('.proceed_to_pay').prop("disabled",
                                            false);
                                        toastr.error(wrong);
                                        return false;
                                    }
                                });
                            }
                        });
                        handler.openIframe();
                    }

                    //mercado pago
                    if (payment_type == "7") {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ URL::to('/orders/mercadoorderrequest') }}",
                            data: {
                                sub_total: sub_total,
                                tax: tax,
                                tax_name: tax_name,
                                grand_total: grand_total,
                                delivery_time: delivery_time,
                                delivery_date: delivery_date,
                                delivery_area: delivery_area,
                                delivery_charge: delivery_charge,
                                discount_amount: discount_amount,
                                couponcode: couponcode,
                                order_type: order_type,
                                address: address,
                                postal_code: postal_code,
                                building: building,
                                landmark: landmark,
                                notes: notes,
                                customer_name: customer_name,
                                customer_email: customer_email,
                                customer_mobile: customer_mobile,
                                vendor_id: vendor,
                                payment_type: payment_type,
                                slug: "{{ $storeinfo->slug }}",
                                url: "{{ helper::storefront_url($storeinfo, '/payment') }}",
                                failure: "{{ url()->current() }}",
                                table: table,
                                tablename: tablename,
                                buynow: $('#buynow_key').val(),
                            },
                            method: 'POST',
                            success: function(response) {
                                if (response.status == 1) {
                                    window.location.href = response.url;
                                } else {
                                    $('.proceed_to_pay').html(
                                        "{{ trans('labels.proceed_to_pay') }}");
                                    $('.proceed_to_pay').prop("disabled", false);
                                    toastr.error(response.message);
                                    return false;
                                }
                            },
                            error: function(error) {
                                $('.proceed_to_pay').html(
                                    "{{ trans('labels.proceed_to_pay') }}");
                                $('.proceed_to_pay').prop("disabled", false);
                                toastr.error(wrong);
                                return false;
                            }
                        });
                    }

                    //PayPal
                    if (payment_type == "8") {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ URL::to('/orders/paypalrequest') }}",
                            data: {
                                sub_total: sub_total,
                                tax: tax,
                                tax_name: tax_name,
                                grand_total: grand_total,
                                delivery_time: delivery_time,
                                delivery_date: delivery_date,
                                delivery_area: delivery_area,
                                delivery_charge: delivery_charge,
                                discount_amount: discount_amount,
                                couponcode: couponcode,
                                order_type: order_type,
                                address: address,
                                postal_code: postal_code,
                                building: building,
                                landmark: landmark,
                                notes: notes,
                                customer_name: customer_name,
                                customer_email: customer_email,
                                customer_mobile: customer_mobile,
                                vendor_id: vendor,
                                payment_type: payment_type,
                                return: '1',
                                slug: "{{ $storeinfo->slug }}",
                                url: "{{ helper::storefront_url($storeinfo, '/payment') }}",
                                failure: "{{ url()->current() }}",
                                table: table,
                                tablename: tablename,
                                tips: tips,
                                buynow: $('#buynow_key').val(),
                            },
                            method: 'POST',
                            success: function(response) {
                                if (response.status == 1) {
                                    $(".callpaypal").trigger("click")
                                } else {
                                    $('.proceed_to_pay').html(
                                        "{{ trans('labels.proceed_to_pay') }}");
                                    $('.proceed_to_pay').prop("disabled", false);
                                    toastr.error(response.message);
                                    return false;
                                }
                            },
                            error: function(error) {
                                $('.proceed_to_pay').html(
                                    "{{ trans('labels.proceed_to_pay') }}");
                                $('.proceed_to_pay').prop("disabled", false);
                                toastr.error(wrong);
                                return false;
                            }
                        });
                    }

                    // myfatoorah
                    if (payment_type == '9') {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ URL::to('/orders/myfatoorahrequest') }}",
                            data: {
                                sub_total: sub_total,
                                tax: tax,
                                tax_name: tax_name,
                                grand_total: grand_total,
                                delivery_time: delivery_time,
                                delivery_date: delivery_date,
                                delivery_area: delivery_area,
                                delivery_charge: delivery_charge,
                                discount_amount: discount_amount,
                                couponcode: couponcode,
                                order_type: order_type,
                                address: address,
                                postal_code: postal_code,
                                building: building,
                                landmark: landmark,
                                notes: notes,
                                customer_name: customer_name,
                                customer_email: customer_email,
                                customer_mobile: customer_mobile,
                                vendor_id: vendor,
                                payment_type: payment_type,
                                return: '1',
                                slug: "{{ $storeinfo->slug }}",
                                url: "{{ helper::storefront_url($storeinfo, '/payment') }}",
                                failure: "{{ url()->current() }}",
                                table: table,
                                tablename: tablename,
                                tips: tips,
                                buynow: $('#buynow_key').val(),
                            },
                            method: 'POST',
                            success: function(response) {
                                if (response.status == 1) {
                                    window.location.href = response.url;
                                } else {
                                    $('.proceed_to_pay').html(
                                        "{{ trans('labels.proceed_to_pay') }}");
                                    $('.proceed_to_pay').prop("disabled", false);
                                    toastr.error(response.message);
                                    return false;
                                }
                            },
                            error: function(error) {
                                $('.proceed_to_pay').html(
                                    "{{ trans('labels.proceed_to_pay') }}");
                                $('.proceed_to_pay').prop("disabled", false);
                                toastr.error(wrong);
                                return false;
                            }
                        });
                    }

                    //toyyibpay
                    if (payment_type == '10') {
                        $('#preloader').show();
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ URL::to('/orders/toyyibpayrequest') }}",
                            data: {
                                sub_total: sub_total,
                                tax: tax,
                                tax_name: tax_name,
                                grand_total: grand_total,
                                delivery_time: delivery_time,
                                delivery_date: delivery_date,
                                delivery_area: delivery_area,
                                delivery_charge: delivery_charge,
                                discount_amount: discount_amount,
                                couponcode: couponcode,
                                order_type: order_type,
                                address: address,
                                postal_code: postal_code,
                                building: building,
                                landmark: landmark,
                                notes: notes,
                                customer_name: customer_name,
                                customer_email: customer_email,
                                customer_mobile: customer_mobile,
                                vendor_id: vendor,
                                payment_type: payment_type,
                                return: '1',
                                slug: "{{ $storeinfo->slug }}",
                                url: "{{ helper::storefront_url($storeinfo, '/payment') }}",
                                failure: "{{ url()->current() }}",
                                table: table,
                                tablename: tablename,
                                tips: tips,
                                buynow: $('#buynow_key').val(),
                            },
                            method: 'POST',
                            success: function(response) {
                                if (response.status == 1) {
                                    window.location.href = response.url;
                                } else {
                                    $('.proceed_to_pay').html(
                                        "{{ trans('labels.proceed_to_pay') }}");
                                    $('.proceed_to_pay').prop("disabled", false);
                                    toastr.error(response.message);
                                    return false;
                                }
                            },
                            error: function(error) {
                                $('.proceed_to_pay').html(
                                    "{{ trans('labels.proceed_to_pay') }}");
                                $('.proceed_to_pay').prop("disabled", false);
                                toastr.error(wrong);
                                return false;
                            }
                        });
                    }

                    //phonepe
                    if (payment_type == '11') {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ URL::to('/orders/phoneperequest') }}",
                            data: {
                                sub_total: sub_total,
                                tax: tax,
                                tax_name: tax_name,
                                grand_total: grand_total,
                                delivery_time: delivery_time,
                                delivery_date: delivery_date,
                                delivery_area: delivery_area,
                                delivery_charge: delivery_charge,
                                discount_amount: discount_amount,
                                couponcode: couponcode,
                                order_type: order_type,
                                address: address,
                                postal_code: postal_code,
                                building: building,
                                landmark: landmark,
                                notes: notes,
                                customer_name: customer_name,
                                customer_email: customer_email,
                                customer_mobile: customer_mobile,
                                vendor_id: vendor,
                                payment_type: payment_type,
                                return: '1',
                                slug: "{{ $storeinfo->slug }}",
                                url: "{{ helper::storefront_url($storeinfo, '/payment') }}",
                                failure: "{{ url()->current() }}?buy_now=" + $('#buynow_key')
                                    .val(),
                                table: table,
                                tablename: tablename,
                                tips: tips,
                                buynow: $('#buynow_key').val(),
                            },
                            method: 'POST',
                            success: function(response) {
                                if (response.status == 1) {
                                    window.location.href = response.url;
                                } else {
                                    $('.proceed_to_pay').html(
                                        "{{ trans('labels.proceed_to_pay') }}");
                                    $('.proceed_to_pay').prop("disabled", false);
                                    toastr.error(response.message);
                                    return false;
                                }
                            },
                            error: function(error) {
                                $('.proceed_to_pay').html(
                                    "{{ trans('labels.proceed_to_pay') }}");
                                $('.proceed_to_pay').prop("disabled", false);
                                toastr.error(wrong);
                                return false;
                            }
                        });
                    }

                    //paytab
                    if (payment_type == '12') {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ URL::to('/orders/paytabrequest') }}",
                            data: {
                                sub_total: sub_total,
                                tax: tax,
                                tax_name: tax_name,
                                grand_total: grand_total,
                                delivery_time: delivery_time,
                                delivery_date: delivery_date,
                                delivery_area: delivery_area,
                                delivery_charge: delivery_charge,
                                discount_amount: discount_amount,
                                couponcode: couponcode,
                                order_type: order_type,
                                address: address,
                                postal_code: postal_code,
                                building: building,
                                landmark: landmark,
                                notes: notes,
                                customer_name: customer_name,
                                customer_email: customer_email,
                                customer_mobile: customer_mobile,
                                vendor_id: vendor,
                                payment_type: payment_type,
                                return: '1',
                                slug: "{{ $storeinfo->slug }}",
                                url: "{{ helper::storefront_url($storeinfo, '/payment') }}",
                                failure: "{{ url()->current() }}",
                                table: table,
                                tablename: tablename,
                                tips: tips,
                                buynow: $('#buynow_key').val(),
                            },
                            method: 'POST',
                            success: function(response) {
                                if (response.status == 1) {
                                    window.location.href = response.url;
                                } else {
                                    $('.proceed_to_pay').html(
                                        "{{ trans('labels.proceed_to_pay') }}");
                                    $('.proceed_to_pay').prop("disabled", false);
                                    toastr.error(response.message);
                                    return false;
                                }
                            },
                            error: function(error) {
                                $('.proceed_to_pay').html(
                                    "{{ trans('labels.proceed_to_pay') }}");
                                $('.proceed_to_pay').prop("disabled", false);
                                toastr.error(wrong);
                                return false;
                            }
                        });
                    }

                    //mollie
                    if (payment_type == '13') {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ URL::to('/orders/mollierequest') }}",
                            data: {
                                sub_total: sub_total,
                                tax: tax,
                                tax_name: tax_name,
                                grand_total: grand_total,
                                delivery_time: delivery_time,
                                delivery_date: delivery_date,
                                delivery_area: delivery_area,
                                delivery_charge: delivery_charge,
                                discount_amount: discount_amount,
                                couponcode: couponcode,
                                order_type: order_type,
                                address: address,
                                postal_code: postal_code,
                                building: building,
                                landmark: landmark,
                                notes: notes,
                                customer_name: customer_name,
                                customer_email: customer_email,
                                customer_mobile: customer_mobile,
                                vendor_id: vendor,
                                payment_type: payment_type,
                                return: '1',
                                slug: "{{ $storeinfo->slug }}",
                                url: "{{ helper::storefront_url($storeinfo, '/payment') }}",
                                failure: "{{ url()->current() }}",
                                table: table,
                                tablename: tablename,
                                tips: tips,
                                buynow: $('#buynow_key').val(),
                            },
                            method: 'POST',
                            success: function(response) {
                                if (response.status == 1) {
                                    window.location.href = response.url;
                                } else {
                                    $('.proceed_to_pay').html(
                                        "{{ trans('labels.proceed_to_pay') }}");
                                    $('.proceed_to_pay').prop("disabled", false);
                                    toastr.error(response.message);
                                    return false;
                                }
                            },
                            error: function(error) {
                                $('.proceed_to_pay').html(
                                    "{{ trans('labels.proceed_to_pay') }}");
                                $('.proceed_to_pay').prop("disabled", false);
                                toastr.error(wrong);
                                return false;
                            }
                        });
                    }

                    //khalti
                    if (payment_type == '14') {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ URL::to('/orders/khaltirequest') }}",
                            data: {
                                sub_total: sub_total,
                                tax: tax,
                                tax_name: tax_name,
                                grand_total: grand_total,
                                delivery_time: delivery_time,
                                delivery_date: delivery_date,
                                delivery_area: delivery_area,
                                delivery_charge: delivery_charge,
                                discount_amount: discount_amount,
                                couponcode: couponcode,
                                order_type: order_type,
                                address: address,
                                postal_code: postal_code,
                                building: building,
                                landmark: landmark,
                                notes: notes,
                                customer_name: customer_name,
                                customer_email: customer_email,
                                customer_mobile: customer_mobile,
                                vendor_id: vendor,
                                payment_type: payment_type,
                                return: '1',
                                slug: "{{ $storeinfo->slug }}",
                                url: "{{ helper::storefront_url($storeinfo, '/payment') }}",
                                failure: "{{ url()->current() }}",
                                table: table,
                                tablename: tablename,
                                tips: tips,
                                buynow: $('#buynow_key').val(),
                            },
                            method: 'POST',
                            success: function(response) {
                                if (response.status == 1) {
                                    window.location.href = response.url;
                                } else {
                                    $('.proceed_to_pay').html(
                                        "{{ trans('labels.proceed_to_pay') }}");
                                    $('.proceed_to_pay').prop("disabled", false);
                                    toastr.error(response.message);
                                    return false;
                                }
                            },
                            error: function(error) {
                                $('.proceed_to_pay').html(
                                    "{{ trans('labels.proceed_to_pay') }}");
                                $('.proceed_to_pay').prop("disabled", false);
                                toastr.error(wrong);
                                return false;
                            }
                        });
                    }

                    //xendit
                    if (payment_type == '15') {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ URL::to('/orders/xenditrequest') }}",
                            data: {
                                sub_total: sub_total,
                                tax: tax,
                                tax_name: tax_name,
                                grand_total: grand_total,
                                delivery_time: delivery_time,
                                delivery_date: delivery_date,
                                delivery_area: delivery_area,
                                delivery_charge: delivery_charge,
                                discount_amount: discount_amount,
                                couponcode: couponcode,
                                order_type: order_type,
                                address: address,
                                postal_code: postal_code,
                                building: building,
                                landmark: landmark,
                                notes: notes,
                                customer_name: customer_name,
                                customer_email: customer_email,
                                customer_mobile: customer_mobile,
                                vendor_id: vendor,
                                payment_type: payment_type,
                                return: '1',
                                slug: "{{ $storeinfo->slug }}",
                                url: "{{ helper::storefront_url($storeinfo, '/payment') }}",
                                failure: "{{ url()->current() }}",
                                table: table,
                                tablename: tablename,
                                tips: tips,
                                buynow: $('#buynow_key').val(),
                            },
                            method: 'POST',
                            success: function(response) {
                                if (response.status == 1) {
                                    window.location.href = response.url;
                                } else {
                                    $('.proceed_to_pay').html(
                                        "{{ trans('labels.proceed_to_pay') }}");
                                    $('.proceed_to_pay').prop("disabled", false);
                                    toastr.error(response.message);
                                    return false;
                                }
                            },
                            error: function(error) {
                                $('.proceed_to_pay').html(
                                    "{{ trans('labels.proceed_to_pay') }}");
                                $('.proceed_to_pay').prop("disabled", false);
                                toastr.error(wrong);
                                return false;
                            }
                        });
                    }

                    // Banktransfer
                    if (payment_type == '6') {
                        $('#payment_type').val(payment_type);
                        $('#modal_customer_name').val($('#customer_name').val());
                        $('#modal_customer_email').val($('#customer_name').val());
                        $('#modal_customer_mobile').val($('#customer_mobile').val());
                        $('#modal_address').val(address);
                        $('#modal_delivery_date').val(delivery_date);
                        $('#modal_delivery_time').val(delivery_time);
                        $('#modal_delivery_area').val(delivery_area);
                        $('#modal_delivery_charge').val(delivery_charge);
                        $('#modal_discount_amount').val(discount_amount);
                        $('#modal_couponcode').val(couponcode);
                        $('#modal_ordertype').val(order_type);
                        $('#modal_building').val(building);
                        $('#modal_landmark').val(landmark);
                        $('#modal_postal_code').val(postal_code);
                        $('#modal_message').val(notes);
                        $('#modal_vendor_id').val(vendor);
                        $('#modal_subtotal').val(sub_total);
                        $('#modal_grand_total').val(grand_total);
                        $('#modal_tax').val(tax);
                        $('#modal_tax_name').val(tax_name);
                        $('#modal_order_type').val(order_type);
                        $('#modal_table').val(table);
                        $('#modal_tablename').val(tablename);
                        $('#modal_tips').val(tips);
                        $('#modal_buynow').val($('#buynow_key').val());
                        $('#payment_description').html($('#bank_payment').val());
                        $('#modalbankdetails').modal('show');
                        $("#modalbankdetails").on('hidden.bs.modal', function(e) {
                            $('.proceed_to_pay').html("{{ trans('labels.proceed_to_pay') }}");
                            $('.proceed_to_pay').prop("disabled", false);
                        });
                    }
                } else {
                    $('.proceed_to_pay').html("{{ trans('labels.proceed_to_pay') }}");
                    $('.proceed_to_pay').prop("disabled", false);
                    toastr.error(response.message);
                }
            },
            error: function(error) {
                $('.proceed_to_pay').html("{{ trans('labels.proceed_to_pay') }}");
                $('.proceed_to_pay').prop("disabled", false);
            }
        });
    }

    function ApplyCoupon() {
        $('#btnapply').prop("disabled", true);
        $('#btnapply').html('<span class="loader"></span>');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ URL::to('/cart/applypromocode') }}",
            method: 'post',
            data: {
                promocode: $('#couponcode').val(),
                sub_total: $('#sub_total').val(),
                vendor_id: "{{ $storeinfo->id }}",
            },
            success: function(response) {
                $('#btnapply').html("{{ trans('labels.apply') }}");
                $('#btnapply').prop("disabled", false);
                if (response.status == 1) {
                    var total = parseFloat($('#sub_total').val());
                    var tax = "{{ @$totalcarttax }}";
                    var discount = "";
                    if (response.data.offer_type == 1) {
                        discount = response.data.offer_amount;
                    }
                    if (response.data.offer_type == 2) {
                        discount = total * parseFloat(response.data.offer_amount) / 100;
                    }
                    var delivery_charge = parseFloat($('#delivery_charge').val());
                    if ($("input:radio[name=cart-delivery]:checked").val() == 1) {
                        var grandtotal = parseFloat(total) + parseFloat(tax) + parseFloat(delivery_charge) -
                            parseFloat(discount);
                    } else {
                        var grandtotal = parseFloat(total) + parseFloat(tax) - parseFloat(discount);
                    }
                    $('#offer_amount').text('-' + currency_formate(parseFloat(discount)));
                    $('#total_amount').text(currency_formate(parseFloat(grandtotal)));
                    $('#grand_total').val(grandtotal);
                    $('#discount_amount').val(discount);
                    $('#couponcode').val(response.data.offer_code);
                    $('#btnremove').removeClass('d-none');
                    $('#btnapply').addClass('d-none');
                } else {
                    toastr.error(response.message);

                }
            }
        });
    }

    function RemoveCoupon() {
        $('#btnremove').prop("disabled", true);
        $('#btnremove').html('<span class="loader"></span>');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ URL::to('/cart/removepromocode') }}",
            method: 'post',
            data: {
                promocode: $('#couponcode').val()
            },
            success: function(response) {
                $('#btnremove').html("{{ trans('labels.remove') }}");
                $('#btnremove').prop("disabled", false);
                if (response.status == 1) {
                    var total = $('#sub_total').val();
                    var tax = "{{ @$totalcarttax }}";
                    var delivery_charge = $('#delivery_charge').val();
                    var discount = 0;
                    if ($("input:radio[name=cart-delivery]:checked").val() == 1) {
                        var grandtotal = parseFloat(total) + parseFloat(tax) + parseFloat(delivery_charge) -
                            parseFloat(discount);
                    } else {
                        var grandtotal = parseFloat(total) + parseFloat(tax) - parseFloat(discount);
                    }
                    $('#offer_amount').text('-' + currency_formate(parseFloat(0)));
                    $('#total_amount').text(currency_formate(parseFloat(grandtotal)));
                    $('#couponcode').val('');
                    $('#grand_total').val(grandtotal);
                    $('#discount_amount').val(discount);
                    $('#couponcode').val('');
                    $('#btnremove').addClass('d-none');
                    $('#btnapply').removeClass('d-none');
                } else {
                    toastr.error(response.message);
                }
            }
        });
    }

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    function validateEmail($email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return emailReg.test($email);
    }

    var select = "{{ trans('labels.select') }}";
    var dateFormat = "{{ helper::appdata($storeinfo->id)->date_format }}";
    var placeholderFormat = dateFormat
        .replace(/Y/g, 'yyyy') // Full year
        .replace(/m/g, 'mm') // Month
        .replace(/d/g, 'dd'); // Day

    document.getElementById("delivery_dt").setAttribute("placeholder", placeholderFormat);

    flatpickr(".delivery_pickup_date", {
        dateFormat: dateFormat,
        enableTime: false,
        altInput: true,
        altFormat: dateFormat,
        minDate: 'today'
    });
</script>
<script src="{{ url(env('ASSETPATHURL') . 'front/js/checkout.js') }}"></script>
