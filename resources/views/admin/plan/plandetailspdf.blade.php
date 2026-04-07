<html>
<head>
    <title>{{ helper::appdata($plan->vendor_id)->web_title }}</title>
</head>
<style type="text/css">
    body {
        font-family: 'Roboto Condensed', sans-serif;
    }
    .m-0 {
        margin: 0px;
    }
    .p-0 {
        padding: 0px;
    }
    .pt-5 {
        padding-top: 5px;
    }
    .mt-10 {
        margin-top: 10px;
    }
    .mt-15 {
        margin-top: 15px;
    }
    .text-center {
        text-align: center !important;
    }
    .w-100 {
        width: 100%;
    }
    .w-50 {
        width: 50%;
    }
    .w-85 {
        width: 85%;
    }
    .w-15 {
        width: 15%;
    }
    .logo img {
        width: 200px;
        height: 60px;
    }
    .gray-color {
        color: #5D5D5D;
    }
    .text-bold {
        font-weight: bold;
    }
    .border {
        border: 1px solid black;
    }
    table tr,
    th,
    td {
        border: 1px solid #d2d2d2;
        border-collapse: collapse;
        padding: 7px 8px;
    }
    table tr th {
        background: #F4F4F4;
        font-size: 15px;
    }
    table tr td {
        font-size: 13px;
    }
    table {
        border-collapse: collapse;
    }
    .box-text p {
        line-height: 10px;
    }
    .float-left {
        float: left;
    }
    .total-part {
        font-size: 16px;
        line-height: 12px;
    }
    .total-right p {
        padding-right: 20px;
    }
</style>
<body>
    <div class="head-title">
        <h1 class="text-center m-0 p-0">Transaction Invoice</h1>
    </div>
    @php
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
    @endphp
    <div class="add-detail mt-10">
        <div class="w-50 float-left mt-10">
            <p class="m-0 pt-5 text-bold w-100">Transaction number : <span
                    class="gray-color">{{ $plan->transaction_number }}</span></p>
            <p class="m-0 pt-5 text-bold w-100">Purchase date : <span
                    class="gray-color">{{ helper::date_format($plan->created_at, $vendor_id) }}</span></p>
            <p class="m-0 pt-5 text-bold w-100">Expire date : <span
                    class="gray-color">{{ $plan->expire_date != '' ? helper::date_format($plan->expire_date, $vendor_id) : '-' }}</span>
            </p>
        </div>
        <div style="clear: both;"></div>
    </div>
    <div class="table-section bill-tbl w-100 mt-10">
        <table class="table w-100 mt-10">
            <tr>
                <th class="w-50">{{ trans('labels.vendor_info') }}</th>
                <th class="w-50">{{ trans('labels.Payment_information') }}</th>
            </tr>
            <tr>
                <td>
                    <div class="box-text">
                        <p><i class="fa-regular fa-user"></i> Name : {{$user->name}}</p> 
                        <p><i class="fa-regular fa-phone"></i> Mobile : {{$user->mobile}}</p>
                        <p><i class="fa-regular fa-envelope"></i> Email : {{$user->email}}</p>
                    </div>
                </td>
                <td>
                    <p>Subtotal : {{ helper::currency_formate($plan->amount, '') }}</p>
                    @if ($plan->amount != 0)
                        @if ($plan->tax != null && $plan->tax != '')
                            @php
                                $tax = explode('|', $plan->tax);
                                $tax_name = explode('|', $plan->tax_name);
                            @endphp
                            @foreach ($tax as $key => $tax_value)
                                @if ($tax_value != 0)
                                    <p>{{ $tax_name[$key] }} :
                                        {{ helper::currency_formate(@$tax[$key], '') }}</p>
                                @endif
                            @endforeach
                        @endif
                    @endif
                    @if ($plan->offer_amount != '' && $plan->offer_amount != null)
                        <p>Discount : -{{ helper::currency_formate($plan->offer_amount, '') }}
                        </p>
                    @endif
                    <p>Total amount :
                        {{ helper::currency_formate($plan->grand_total, '') }}</p>
                    <div class="box-text">
                        <p>Payment type</p>
                        @if ($plan->payment_type == 6)
                            {{ @helper::getpayment($plan->payment_type, 1)->payment_name }}
                            : <small><a href="{{ helper::image_path($plan->screenshot) }}" target="_blank"
                                    class="text-danger">Click here</a></small>
                        @elseif(in_array($plan->payment_type, [2, 3, 4, 5, 7, 8, 9, 10, 11, 12, 13, 14, 15]))
                            {{ @helper::getpayment($plan->payment_type, 1)->payment_name }}
                            : {{ $plan->payment_id }}
                        @elseif($plan->payment_type == 6)
                            {{ @helper::getpayment($plan->payment_type, 1)->payment_name }}
                        @elseif($plan->payment_type == 0)
                            Manual
                        @elseif($plan->payment_type == 1)
                            COD
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="table-section bill-tbl w-100 mt-10">
        <table class="table w-100 mt-10">
            <tr>
                <th class="w-50">Plan info.</th>
            </tr>
            <tr>
                <td>
                    <div class="box-text">
                        <h1>{{ $plan->plan_name }}</h1>
                        <h2 class="mb-2">{{ helper::currency_formate($plan->amount, '') }}
                            <span class="fs-7 text-muted">/
                                @if ($plan->duration != null || $plan->duration != '')
                                    @if ($plan->duration == 1)
                                        One month
                                    @elseif($plan->duration == 2)
                                        Three month
                                    @elseif($plan->duration == 3)
                                        Six month
                                    @elseif($plan->duration == 4)
                                        One year
                                    @elseif($plan->duration == 5)
                                        Lifetime
                                    @endif
                                @else
                                    {{ $plan->days }}
                                    {{ $plan->days > 1 ? 'Days' : 'Day' }}
                                @endif
                            </span>
                            @if ($plan->tax != null && $plan->tax != '')
                                <small class="text-danger">Exclusive taxes</small><br>
                            @else
                                <small class="text-success">Inclusive taxes</small> <br>
                            @endif 
                            <small class="text-muted text-center">{{ $plan->description }}</small>
                        </h2>
                        
                        <ul class="pb-5">
                            @php $features = ($plan->features == null ? null : explode('|', $plan->features));@endphp
                            <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                <span class="mx-2">
                                    {{ $plan->service_limit == -1 ? 'Unlimited' : $plan->service_limit }}
                                    {{ $plan->service_limit > 1 || $plan->service_limit == -1 ? 'Products' : 'product' }}
                                </span>
                            </li>
                            <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                <span class="mx-2">
                                    {{ $plan->appoinment_limit == -1 ? 'Unlimited' : $plan->appoinment_limit }}
                                    {{ $plan->appoinment_limit > 1 || $plan->appoinment_limit == -1 ? 'Orders' : 'Order' }}
                                </span>
                            </li>
                            @php
                                $themes = [];
                                if ($plan->themes_id != '' && $plan->themes_id != null) {
                                    $themes = explode('|', $plan->themes_id);
                            } @endphp
                            <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                <span class="mx-2">{{ count($themes) }}
                                    {{ count($themes) > 1 ? 'Themes' : 'Theme' }}</span>
                            </li>
                            @if (@helper::checkaddons('coupon'))
                                @if ($plan->coupons == 1)
                                    <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                        <span class="mx-2">Coupons</span>
                                    </li>
                                @endif
                            @endif
                            @if (@helper::checkaddons('custom_domain'))
                                @if ($plan->custom_domain == 1)
                                    <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                        <span class="mx-2">Custom domain</span>
                                    </li>
                                @endif
                            @endif
                            @if (@helper::checkaddons('blog'))
                                @if ($plan->blogs == 1)
                                    <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                        <span class="mx-2">Blogs</span>
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
                                        <span class="mx-2">Sound notification</span>
                                    </li>
                                @endif
                            @endif
                            @if (@helper::checkaddons('whatsapp_message'))
                                @if ($plan->whatsapp_message == 1)
                                    <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                        <span class="mx-2">Whatsapp message</span>
                                    </li>
                                @endif
                            @endif
                            @if (@helper::checkaddons('telegram_message'))
                                @if ($plan->telegram_message == 1)
                                    <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                        <span class="mx-2">Telegram message</span>
                                    </li>
                                @endif
                            @endif
                            @if (@helper::checkaddons('pos'))
                                @if ($plan->pos == 1)
                                    <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                        <span class="mx-2">POS</span>
                                    </li>
                                @endif
                            @endif
                            @if (@helper::checkaddons('vendor_app'))
                                @if ($plan->vendor_app == 1)
                                    <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                        <span class="mx-2">Vendor app</span>
                                    </li>
                                @endif
                            @endif
                            @if (@helper::checkaddons('user_app'))
                                @if ($plan->customer_app == 1)
                                    <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                        <span class="mx-2">Customer app</span>
                                    </li>
                                @endif
                            @endif
                            @if (@helper::checkaddons('pwa'))
                                @if ($plan->pwa == 1)
                                    <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                        <span class="mx-2">PWA</span>
                                    </li>
                                @endif
                            @endif
                            @if (@helper::checkaddons('employee'))
                                @if ($plan->role_management == 1)
                                    <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                        <span class="mx-2">Role management</span>
                                    </li>
                                @endif
                            @endif
                            @if (@helper::checkaddons('pixel'))
                                @if ($plan->pixel == 1)
                                    <li class="mb-2 d-flex fs-7"> <i class="fa-regular fa-circle-check text-secondary "></i>
                                        <span class="mx-2">Pixel</span>
                                    </li>
                                @endif
                            @endif
                            @if ($features != '')
                                @foreach ($features as $feature)
                                    @if ($feature != '' && $feature != null)
                                        <li class="mb-2 d-flex fs-7"> <i
                                                class="fa-regular fa-circle-check text-secondary "></i>
                                            <span class="mx-2"> {{ $feature }} </span>
                                        </li>
                                    @endif
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="mt-15 text-center bg-white fixed-bottom border-top">
        <span>{{ helper::appdata('')->copyright }}</span>
    </div>
</body>
</html>
