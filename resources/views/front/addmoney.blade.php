@include('front.theme.header')
<!------ breadcrumb ------>
<section class="breadcrumb-sec bg-change-mode">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-dark"><a class="text-dark color-changer"
                        href="{{ helper::storefront_url($storeinfo, '/') }}">{{ trans('labels.home') }}</a>
                </li>

                <li class="text-muted breadcrumb-item {{ session()->get('direction') == 2 ? 'rtl' : '' }} active"
                    aria-current="page">
                    {{ trans('labels.add_money') }}
                </li>

            </ol>
        </nav>
    </div>
</section>

<section class="product-prev-sec product-list-sec">
    <div class="container">
        <div class="user-bg-color mb-5">
            <div class="container">
                <div class="row">
                    @include('front.theme.sidebar')
                    <div class="col-xl-9 col-lg-8 col-xxl-9 col-12">
                        <div class="card-v p-0 border rounded user-form">
                            <div class="settings-box">
                                <div class="settings-box-header flex-wrap border-bottom px-4 py-3">
                                    <div class="mb-0 d-flex color-changer align-items-center">
                                        <i class="fa-light fa-hand-holding-dollar fs-4"></i>
                                        <span class="fs-5 fw-500 px-3">
                                            {{ trans('labels.add_money') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="settings-box-body p-3 dashboard-section">
                                    <p class="mb-2 fw-500 color-changer">{{ trans('labels.add_amount') }}</p>
                                    <div class="mb-3">
                                        <div class="input-group">
                                            <span
                                                class="input-group-text fw-600 fs-6">{{ helper::appdata($storeinfo->id)->currency }}</span>
                                            <input type="number" name="amount" id="amount" class="form-control">
                                        </div>
                                    </div>

                                    <div class="row justify-content-between border-bottom">
                                        <div class="col-xl-6 col-12">
                                            <p class="mb-0 fw-500 color-changer">{{ trans('labels.notes') }} :</p>
                                            <ul class="p-0 pb-3 mt-1">
                                                <li class="text-muted fs-7 d-flex gap-2 align-items-center">
                                                    <i class="fa-regular fa-circle-check text-success"></i>
                                                    {{ trans('labels.wallet_note_1') }}
                                                </li>
                                                <li class="text-muted fs-7 d-flex gap-2 align-items-center">
                                                    <i class="fa-regular fa-circle-check text-success"></i>
                                                    {{ trans('labels.wallet_note_2') }}
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-xl-6 col-12">
                                            @include('front.service-trusted')
                                        </div>
                                    </div>
                                    <p class="my-2 fw-500 color-changer fs-5">{{ trans('labels.payment_options') }}</p>
                                    <div class="recharge_payment_option row g-3">
                                        @php
                                            $i = 0;
                                        @endphp
                                        @foreach ($getpaymentmethods as $key => $payment)
                                            @php
                                                // Check if the current $payment is a system addon and activated
                                                $systemAddonActivated = false;

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
                                                    <div>
                                                        <div class="payment-check">
                                                            <img src="{{ helper::image_path($payment->image) }}"
                                                                class="img-fluid" alt="" width="40px" />
                                                            @if (strtolower($payment->payment_type) == '2')
                                                                <input type="hidden" name="razorpay" id="razorpay"
                                                                    value="{{ $payment->public_key }}">
                                                            @endif
                                                            @if (strtolower($payment->payment_type) == '3')
                                                                <input type="hidden" name="stripe" id="stripe"
                                                                    value="{{ $payment->public_key }}">
                                                            @endif
                                                            @if (strtolower($payment->payment_type) == '4')
                                                                <input type="hidden" name="flutterwavekey"
                                                                    id="flutterwavekey"
                                                                    value="{{ $payment->public_key }}">
                                                            @endif
                                                            @if (strtolower($payment->payment_type) == '5')
                                                                <input type="hidden" name="paystackkey"
                                                                    id="paystackkey"
                                                                    value="{{ $payment->public_key }}">
                                                            @endif

                                                            <p class="m-0">{{ $payment->payment_name }}</p>
                                                            <input
                                                                class="form-check-input payment_radio  payment-input {{ session()->get('direction') == '2' ? 'me-auto' : 'ms-auto' }}"
                                                                type="radio" name="transaction_type"
                                                                value="{{ $payment->payment_type }}"
                                                                data-currency="{{ $payment->currency }}"
                                                                {{ $i++ == 0 ? 'checked' : '' }}
                                                                id="{{ $payment->payment_type }}"
                                                                data-payment_type="{{ strtolower($payment->payment_type) }}"
                                                                @if (!$key) {!! 'checked' !!} @endif
                                                                style="">

                                                            @if (strtolower($payment->payment_type) == '6')
                                                                <input type="hidden"
                                                                    value="{{ $payment->payment_description }}"
                                                                    id="bank_payment">
                                                            @endif
                                                        </div>
                                                    </div>
                                                </label>
                                            @endif
                                        @endforeach
                                    </div>

                                    <div class="col-12 d-flex gap-2 mt-3 justify-content-end">
                                        <a href="{{ helper::storefront_url($storeinfo, '/wallet') }}"
                                            class="btn btn-danger px-3 p-2 m-0">{{ trans('labels.cancel') }}</a>
                                        <button class="btn btn-store px-3 p-2 m-0 add_money"
                                            onclick="addmoney()">{{ trans('labels.proceed_to_pay') }}</button>
                                    </div>

                                    <input type="hidden" name="walleturl" id="walleturl"
                                        value="{{ helper::storefront_url($storeinfo, '/wallet/recharge') }}">
                                    <input type="hidden" name="successurl" id="successurl"
                                        value="{{ helper::storefront_url($storeinfo, '/wallet') }}">
                                    <input type="hidden" name="user_name" id="user_name"
                                        value="{{ Auth::user()->name }}">
                                    <input type="hidden" name="user_email" id="user_email"
                                        value="{{ Auth::user()->email }}">
                                    <input type="hidden" name="user_mobile" id="user_mobile"
                                        value="{{ Auth::user()->mobile }}">
                                    <input type="hidden" name="vendor_id" id="vendor_id"
                                        value="{{ $storeinfo->id }}">
                                    <input type="hidden" name="title" id="title"
                                        value="{{ helper::appdata($storeinfo->id)->website_title }}">
                                    <input type="hidden" name="logo" id="logo"
                                        value="{{ helper::appdata(@$storeinfo->id)->image }}">
                                    <input type="hidden" name="addsuccessurl" id="addsuccessurl"
                                        value="{{ helper::storefront_url($storeinfo, '/addwalletsuccess') }}">
                                    <input type="hidden" name="addfailurl" id="addfailurl"
                                        value="{{ helper::storefront_url($storeinfo, '/addfail') }}">
                                    <input type="hidden" name="myfatoorahurl" id="myfatoorahurl"
                                        value="{{ URL::to('/orders/myfatoorahrequest') }}">
                                    <input type="hidden" name="mercadopagourl" id="mercadopagourl"
                                        value="{{ URL::to('/orders/mercadoorderrequest') }}">
                                    <input type="hidden" name="paypalurl" id="paypalurl"
                                        value="{{ URL::to('/orders/paypalrequest') }}">
                                    <input type="hidden" name="toyyibpayurl" id="toyyibpayurl"
                                        value="{{ URL::to('/orders/toyyibpayrequest') }}">
                                    <input type="hidden" name="paytaburl" id="paytaburl"
                                        value="{{ URL::to('/orders/paytabrequest') }}">
                                    <input type="hidden" name="phonepeurl" id="phonepeurl"
                                        value="{{ URL::to('/orders/phoneperequest') }}">
                                    <input type="hidden" name="mollieurl" id="mollieurl"
                                        value="{{ URL::to('/orders/mollierequest') }}">
                                    <input type="hidden" name="khaltiurl" id="khaltiurl"
                                        value="{{ URL::to('/orders/khaltirequest') }}">
                                    <input type="hidden" name="xenditurl" id="xenditurl"
                                        value="{{ URL::to('/orders/xenditrequest') }}">

                                    <input type="hidden" name="slug" id="slug"
                                        value="{{ $storeinfo->slug }}">

                                    <input type="hidden" value="{{ trans('messages.payment_selection_required') }}"
                                        name="payment_type_message" id="payment_type_message">

                                    <input type="hidden" value="{{ trans('messages.amount_required') }}"
                                        name="amount_message" id="amount_message">

                                    <form action="{{ url('/orders/paypalrequest') }}" method="post" class="d-none">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="return" value="2">
                                        <input type="submit" class="callpaypal" name="submit">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    var proceed_to_pay = "{{ trans('labels.proceed_to_pay') }}";
</script>

<!-- newsletter -->
@include('front.newsletter')
<!-- newsletter -->

@include('front.theme.footer')
