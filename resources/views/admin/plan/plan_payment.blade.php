@extends('admin.layout.default')
@section('content')
    @php
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
    @endphp
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="text-capitalize fw-600 text-primary fs-4">{{ trans('labels.purchase_plan') }}</h5>
    </div>
    <div class="row g-3">
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card border-0 box-shadow">
                <div class="card-header p-3 bg-secondary">
                    <h5 class="text-dark">{{ $plan->name }}</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h5 class="mb-1 text-dark">{{ helper::currency_formate($plan->price, '') }}
                            <span class="fs-7 text-muted">/
                                @if ($plan->plan_type == 1)
                                    @if ($plan->duration == 1)
                                        {{ trans('labels.one_month') }}
                                    @elseif($plan->duration == 2)
                                        {{ trans('labels.three_month') }}
                                    @elseif($plan->duration == 3)
                                        {{ trans('labels.six_month') }}
                                    @elseif($plan->duration == 4)
                                        {{ trans('labels.one_year') }}
                                    @elseif($plan->duration == 5)
                                        {{ trans('labels.lifetime') }}
                                    @endif
                                @endif
                                @if ($plan->plan_type == 2)
                                    {{ $plan->days }}
                                    {{ $plan->days > 1 ? trans('labels.days') : trans('labels.day') }}
                                @endif
                            </span>
                        </h5>
                        @if ($plan->tax != null && $plan->tax != '')
                            <small class="text-danger">{{ trans('labels.exclusive_taxes') }}</small><br>
                        @else
                            <small class="text-success">{{ trans('labels.inclusive_taxes') }}</small> <br>
                        @endif
                        <small class="text-muted text-center">{{ $plan->description }}</small>
                    </div>
                    <ul class="pb-5">
                        @php $features = ($plan->features == null ? null : explode('|', $plan->features));@endphp
                        <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2">
                                {{ $plan->order_limit == -1 ? trans('labels.unlimited') : $plan->order_limit }}
                                {{ $plan->order_limit > 1 || $plan->order_limit == -1 ? trans('labels.products') : trans('labels.product') }}
                            </span>
                        </li>
                        <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2">
                                {{ $plan->appointment_limit == -1 ? trans('labels.unlimited') : $plan->appointment_limit }}
                                {{ $plan->appointment_limit > 1 || $plan->appointment_limit == -1 ? trans('labels.orders') : trans('labels.order') }}
                            </span>
                        </li>
                        @php
                            $themes = [];
                            if ($plan->themes_id != '' && $plan->themes_id != null) {
                                $themes = explode('|', $plan->themes_id);
                        } @endphp
                        <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2">{{ count($themes) }}
                                {{ count($themes) > 1 ? trans('labels.themes') : trans('labels.theme') }}
                                @if (Auth::user()->type == 2 || Auth::user()->type == 4)
                                    <a onclick="themeinfo('{{ $plan->id }}','{{ $plan->themes_id }}','{{ $plan->name }}')"
                                        tooltip="{{ trans('labels.info') }}" class="cursor-pointer"> <i
                                            class="fa-regular fa-circle-info"></i> </a>
                                @endif
                            </span>
                        </li>
                        @if (@helper::checkaddons('coupon'))
                            @if ($plan->coupons == 1)
                                <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                    <span class="mx-2">{{ trans('labels.coupons') }}</span>
                                </li>
                            @endif
                        @endif
                        @if (@helper::checkaddons('custom_domain'))
                            @if ($plan->custom_domain == 1)
                                <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                    <span class="mx-2">{{ trans('labels.custome_domain') }}</span>
                                </li>
                            @endif
                        @endif
                        @if (@helper::checkaddons('blog'))
                            @if ($plan->blogs == 1)
                                <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                    <span class="mx-2">{{ trans('labels.blogs') }}</span>
                                </li>
                            @endif
                        @endif
                        @if (@helper::checkaddons('google_login'))
                            @if ($plan->google_login == 1)
                                <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                    <span class="mx-2">{{ trans('labels.google_login') }}</span>
                                </li>
                            @endif
                        @endif
                        @if (@helper::checkaddons('facebook_login'))
                            @if ($plan->facebook_login == 1)
                                <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                    <span class="mx-2">{{ trans('labels.facebook_login') }}</span>
                                </li>
                            @endif
                        @endif
                        @if (@helper::checkaddons('notification'))
                            @if ($plan->sound_notification == 1)
                                <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                    <span class="mx-2">{{ trans('labels.sound_notification') }}</span>
                                </li>
                            @endif
                        @endif
                        @if (@helper::checkaddons('whatsapp_message'))
                            @if ($plan->whatsapp_message == 1)
                                <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                    <span class="mx-2">{{ trans('labels.whatsapp_message') }}</span>
                                </li>
                            @endif
                        @endif
                        @if (@helper::checkaddons('telegram_message'))
                            @if ($plan->telegram_message == 1)
                                <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                    <span class="mx-2">{{ trans('labels.telegram_message') }}</span>
                                </li>
                            @endif
                        @endif
                        @if (@helper::checkaddons('vendor_app'))
                            @if ($plan->vendor_app == 1)
                                <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                    <span class="mx-2">{{ trans('labels.vendor_app_available') }}</span>
                                </li>
                            @endif
                        @endif
                        @if (@helper::checkaddons('user_app'))
                            @if ($plan->customer_app == 1)
                                <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                    <span class="mx-2">{{ trans('labels.customer_app') }}</span>
                                </li>
                            @endif
                        @endif
                        @if (@helper::checkaddons('pos'))
                            @if ($plan->pos == 1)
                                <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                    <span class="mx-2">{{ trans('labels.pos') }}</span>
                                </li>
                            @endif
                        @endif
                        @if (@helper::checkaddons('pwa'))
                            @if ($plan->pwa == 1)
                                <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                    <span class="mx-2">{{ trans('labels.pwa') }}</span>
                                </li>
                            @endif
                        @endif
                        @if (@helper::checkaddons('employee'))
                            @if ($plan->role_management == 1)
                                <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                    <span class="mx-2">{{ trans('labels.role_management') }}</span>
                                </li>
                            @endif
                        @endif
                        @if (@helper::checkaddons('pixel'))
                            @if ($plan->pixel == 1)
                                <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                    <span class="mx-2">{{ trans('labels.pixel') }}</span>
                                </li>
                            @endif
                        @endif
                        @if ($features != null)
                            @foreach ($features as $feature)
                                <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                    <span class="mx-2"> {{ $feature }} </span>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-sm-6 mb-3 payments">
            @if (@helper::checkaddons('coupon'))
                <div class="card border-0 box-shadow">
                    <div class="card-header p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title m-0 text-dark">{{ trans('labels.apply_coupon') }}</h5>
                            <p class="text-secondary cursor-pointer {{ session()->has('discount_data') ? 'd-none' : '' }}"
                                data-bs-toggle="modal" data-bs-target="#couponmodal">
                                {{ trans('labels.select_promocode') }}</p>
                        </div>
                    </div>
                    <div class="card-body">
                        @php
                            $count = App\Models\Coupons::where('vendor_id', 1)->count();
                            $coupons = App\Models\Coupons::where('vendor_id', 1)->orderBy('reorder_id')->get();
                        @endphp
                        @if (session()->has('discount_data'))
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="promocode" name="promocode"
                                    value="{{ session()->get('discount_data')['offer_code'] }}" readonly
                                    placeholder="{{ trans('labels.enter_coupon_code') }}">
                                <button type="button" onclick="removecoupon()"
                                    class="btn btn-secondary px-sm-4">{{ trans('labels.remove') }}</button>
                            </div>
                        @else
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="promocode" name="promocode" readonly
                                    placeholder="{{ trans('labels.enter_coupon_code') }}">
                                <button type="button" onclick="applyCopon()"
                                    class="btn btn-secondary px-sm-4">{{ trans('labels.apply') }}</button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
            <div class="card border-0 box-shadow mt-3">
                <div class="card-header p-3">
                    <h5 class="card-title m-0 text-dark">{{ trans('labels.payment_details') }}</h5>
                </div>
                <div class="card-body">

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <p class="fs-15 fw-500">{{ trans('labels.sub_total') }}</p>
                            <p class="fs-15 fw-500">{{ helper::currency_formate($plan->price, '') }}</p>
                        </li>
                        @if (session()->has('discount_data'))
                            @php
                                $discount = session()->get('discount_data')['offer_amount'];
                            @endphp
                            <li class="list-group-item d-flex justify-content-between">
                                <p class="fs-15 fw-500">{{ trans('labels.discount') }} <span
                                        class="text-dark">({{ session()->get('discount_data')['offer_code'] }})</span>
                                </p>
                                <p class="fs-15 fw-500">
                                    -{{ helper::currency_formate(session()->get('discount_data')['offer_amount'], '') }}
                                </p>
                            </li>
                        @else
                            @php
                                $discount = 0;
                            @endphp
                        @endif

                        @php
                            $taxlist = helper::gettax($plan->tax);
                            $newtax = [];
                            $totaltax = 0;
                        @endphp
                        @if ($plan->tax != null && $plan->tax != '')
                            @foreach ($taxlist as $tax)
                                <li class="list-group-item d-flex justify-content-between">
                                    <p class="fs-15 fw-500">
                                        {{ @$tax->name }}
                                    </p>
                                    <p class="fs-15 fw-500">
                                        {{ @$tax->type == 1 ? helper::currency_formate(@$tax->tax, '') : helper::currency_formate($plan->price * (@$tax->tax / 100), '') }}
                                    </p>
                                    @php
                                        if (@$tax->type == 1) {
                                            $newtax[] = @$tax->tax;
                                        } else {
                                            $newtax[] = $plan->price * (@$tax->tax / 100);
                                        }
                                    @endphp
                                </li>
                            @endforeach
                        @endif
                        @foreach ($newtax as $item)
                            @php
                                $totaltax += (float) $item;
                            @endphp
                        @endforeach
                        <li class="list-group-item d-flex justify-content-between">
                            @php
                                $grand_total = $plan->price - $discount + $totaltax;
                            @endphp
                            <p class="fs-16 fw-600 text-primary">{{ trans('labels.grand_total') }}</p>
                            <input type="hidden" name="grand_total" id="grand_total" value="{{ $grand_total }}">
                            <p class="fw-600 fs-16 text-primary">{{ helper::currency_formate($grand_total, '') }}</p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card border-0 box-shadow mt-3">
                <div class="card-header p-3">
                    <h5 class="card-title mb-0 text-dark">{{ trans('labels.payment_methods') }}</h5>
                </div>
                <div class="card-body">

                    <div class="row g-3">
                        @foreach ($paymentmethod as $pmdata)
                            @php
                                $payment_type = $pmdata->payment_type;
                            @endphp
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <div class="input-group-text">
                                        <input class="form-check-input mt-0" type="radio" id="{{ $payment_type }}"
                                            value="{{ $pmdata->public_key }}"
                                            data-transaction-type="{{ $pmdata->payment_type }}"
                                            data-currency="{{ $pmdata->currency }}"
                                            @if ($payment_type == '6') data-bank-name="{{ $pmdata->bank_name }}"  data-account-holder-name="{{ $pmdata->account_holder_name }}"  data-account-number="{{ $pmdata->account_number }}" data-bank-ifsc-code="{{ $pmdata->bank_ifsc_code }}" @endif
                                            name="paymentmode">
                                        @if ($payment_type == '6')
                                            <input type="hidden" value="{{ $pmdata->payment_description }}"
                                                id="bank_payment">
                                        @endif
                                    </div>
                                    <label for="{{ $payment_type }}"
                                        class="d-flex align-items-center form-control cursor-pointer fs-15">
                                        <img src="{{ helper::image_path($pmdata->image) }}" class="mx-2 hw-20"
                                            alt="" srcset="">
                                        {{ $pmdata->payment_name }}
                                    </label>
                                </div>
                                @if ($payment_type == '3')
                                    <input type="hidden" name="stripe_public_key" id="stripe_public_key"
                                        value="{{ $pmdata->public_key }}">
                                    <div class="stripe-form d-none">
                                        <div id="card-element"></div>
                                    </div>
                                    <div class="text-danger stripe_error"></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="text-end mt-2">
                        <a href="{{ URL::to('/admin/plan') }}"
                            class="btn btn-danger px-sm-4">{{ trans('labels.cancel') }}</a>
                        @if ($paymentmethod->count() > 0)
                            <button
                                @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="button" @endif
                                class="btn btn-primary px-sm-4 {{ env('Environment') == 'sendbox' ? '' : 'buy_now' }} ">
                                {{ trans('labels.checkout') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="price" id="price" value="{{ $plan->price }}">
    <input type="hidden" name="plan_id" id="plan_id" value="{{ $plan->id }}">
    <input type="hidden" name="user_name" id="user_name" value="{{ Auth::user()->name }}">
    <input type="hidden" name="user_email" id="user_email" value="{{ Auth::user()->email }}">
    <input type="hidden" name="user_mobile" id="user_mobile" value="{{ Auth::user()->mobile }}">
    <input type="hidden" name="payment_error" id="payment_error" value="{{ trans('messages.select_atleast_one') }}">
    <form action="{{ url('admin/plan/buyplan/paypalrequest') }}" method="post" class="d-none">
        {{ csrf_field() }}
        <input type="hidden" name="return" value="2">
        <input type="submit" class="callpaypal" name="submit">
    </form>
    <!-- Modal -->
    <div class="modal fade" id="modalbankdetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalbankdetailsLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title" id="modalbankdetailsLabel">{{ trans('labels.banktransfer') }}</h5>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form enctype="multipart/form-data" action="{{ URL::to('admin/plan/buyplan') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="payment_type" id="modal_payment_type" class="form-control"
                            value="">
                        <input type="hidden" name="plan_id" id="modal_plan_id" class="form-control" value="">
                        <input type="hidden" name="amount" id="modal_amount" class="form-control" value="">
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
                        <button type="button" class="btn btn-danger px-sm-4"
                            data-bs-dismiss="modal">{{ trans('labels.close') }}</button>
                        <button @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" type="submit" @endif
                            class="btn btn-primary"> {{ trans('labels.save') }} </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="couponmodal" tabindex="-1" aria-labelledby="couponmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded overflow-hidden">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title text-primary" id="couponmodalLabel">{{ trans('labels.coupons_offers') }}</h5>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="available-cuppon {{ session()->get('direction') == '2' ? 'text-right' : '' }}">
                        <p class="available-title text-dark fw-500" id="exampleModalLabel">
                            {{ trans('labels.available_coupons') }}
                        </p>
                    </div>
                    @foreach ($coupons as $coupon)
                        <div class="card my-3 border-0 bg-white box-shadow">
                            <div
                                class="card-body p-0 {{ session()->get('direction') == '2' ? 'pe-3' : 'ps-3' }} overflow-hidden ">
                                <div class="coupon bg-white rounded d-flex justify-content-between align-items-center">
                                    <div
                                        class="{{ session()->get('direction') == '2' ? 'right-side' : 'left-side' }} py-3 d-flex w-100 justify-content-start align-items-center">
                                        <div>
                                            <h6 class="fw-600 text-dark">{{ $coupon->offer_name }}</h6>
                                            <p class="dark_color mb-0 fw-500 fs-15 dark_color mt-1">
                                                Coupon :
                                                <span
                                                    class="fw-normal text-decoration-underline text-uppercase text-primary">{{ $coupon->offer_code }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div
                                        class="right-side {{ session()->get('direction') == '2' ? 'border-0' : 'border-0' }}">
                                        <div class="info m-3 d-flex align-items-center">
                                            <span
                                                class="{{ session()->get('direction') == '2' ? 'coupn-circle-up-right' : 'coupn-circle-up-left' }}"></span>
                                            <div class="w-100 d-flex justify-content-center">
                                                <button class="btn btn-success px-sm-4"
                                                    onclick="copy('{{ $coupon->offer_code }}')">{{ trans('labels.copy') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- @foreach ($coupons as $coupon)
                        <div class="d-flex justify-content-between align-items-center my-2">
                            <div class="coupon_codewrapper">
                                <div class="coupon_circle1"></div>
                                <div class="coupon_circle2"></div>
                                <div class="coupon_couponcode">
                                    <span class="coupon_text">{{ $coupon->offer_code }}</span>
                                </div>
                            </div>
                            <span class="coupon_text">{{ $coupon->offer_name }}</span>
                            <button class="btn btn-success px-sm-4"
                                onclick="copy('{{ $coupon->offer_code }}')">{{ trans('labels.copy') }}</button>
                        </div>
                    @endforeach --}}
                </div>

            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script src="https://checkout.flutterwave.com/v3.js"></script>
    <script>
        var SITEURL = "{{ URL::to('') }}";
        var planlisturl = "{{ URL::to('admin/plan') }}";
        var buyurl = "{{ URL::to('admin/plan/buyplan') }}";
        var plan_name = "{{ $plan->name }}";
        var plan_description = "{{ $plan->description }}";
        var title = "{{ Str::limit(helper::appdata('')->website_title, 50) }}";
        var description = "Plan Subscription";
        var applycouponurl = "{{ URL::to('/admin/applycoupon') }}";
        var removecouponurl = "{{ URL::to('/admin/removecoupon') }}";
        var offer_code = "{{ session()->has('discount_data') ? session()->get('discount_data')['offer_code'] : 0 }}";
        var discount = "{{ session()->has('discount_data') ? session()->get('discount_data')['offer_amount'] : 0 }}";
        var sub_total = "{{ $plan->price }}";
        var checkout = "{{ trans('labels.checkout') }}";
    </script>
    <script>
        function themeinfo(id, theme_id, plan_name) {
            let string = theme_id;
            let arr = string.split('|');
            $('#themeinfoLabel').text(plan_name);
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                url: "{{ URL::to('admin/themeimages') }}",
                method: 'GET',
                data: {
                    theme_id: arr
                },
                dataType: 'json',
                success: function(data) {
                    $('#theme_modalbody').html(data.output);
                    $('#themeinfo').modal('show');
                }
            })
        }
    </script>
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/plan_payment.js') }}"></script>
@endsection
