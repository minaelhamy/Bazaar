<html>

<head>
    <title>{{ helper::appdata($getorderdata->vendor_id)->web_title }}</title>
</head>
<style type="text/css">
    body {
        font-family: 'DejaVu Sans', sans-serif;
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

    .w-33 {
        width: 33.33%;
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
        <h1 class="text-center m-0 p-0">{{ trans('labels.invoice') }}</h1>
    </div>
    <div class="add-detail mt-10">
        <div class="w-50 float-left mt-10">
            <p class="m-0 pt-5 text-bold w-100">{{ trans('labels.invoice_id') }} - <span
                    class="gray-color">#{{ $getorderdata->id }}</span></p>
            <p class="m-0 pt-5 text-bold w-100">{{ trans('labels.order_id') }} -
                <span class="gray-color">#{{ $getorderdata->order_number }}</span>
            </p>
            <p class="m-0 pt-5 text-bold w-100">{{ trans('labels.order_date') }} -
                <span
                    class="gray-color">{{ helper::date_format($getorderdata->created_at, $getorderdata->vendor_id) }}</span>
            </p>
            @if (helper::appdata($getorderdata->vendor_id)->ordertype_date_time == 1)
                @if ($getorderdata->order_from != 'pos' && $getorderdata->order_type != 3)
                    @if ($getorderdata->delivery_date != '' && $getorderdata->delivery_date != null)
                        <p class="m-0 pt-5 text-bold w-100">
                            {{ $getorderdata->order_type == 1 ? trans('labels.delivery_date') : trans('labels.pickup_date') }}
                            -
                            <span
                                class="gray-color">{{ helper::date_format($getorderdata->delivery_date, $getorderdata->vendor_id) }}</span>
                        </p>
                    @endif
                    @if ($getorderdata->delivery_time != '' && $getorderdata->delivery_time != null)
                        <p class="m-0 pt-5 text-bold w-100">
                            {{ $getorderdata->order_type == 1 ? trans('labels.delivery_time') : trans('labels.pickup_time') }}
                            -
                            <span class="gray-color">{{ $getorderdata->delivery_time }}</span>
                        </p>
                    @endif
                @endif
            @endif
        </div>
        <div style="clear: both;"></div>
    </div>
    <div class="table-section bill-tbl w-100 mt-10">
        <table class="table w-100 mt-10">
            <tr>
                <th colspan="2" class="w-50">{{ trans('labels.customer_info') }}</th>
            </tr>
            <tr>
                <td>
                    <div class="box-text">
                        <p><i class="fa-regular fa-user"></i> {{ $getorderdata->customer_name }}</p>
                        <p><i class="fa-regular fa-phone"></i> {{ $getorderdata->mobile }} </p>
                        <p><i class="fa-regular fa-envelope"></i> {{ $getorderdata->customer_email }}</p>
                    </div>
                </td>
                @if ($getorderdata->order_type == 1)
                    <td>
                        <div class="box-text">
                            <p>{{ $getorderdata->address }},</p>
                            <p>{{ $getorderdata->building }},</p>
                            <p>{{ $getorderdata->landmark }}</p>
                            <p>{{ $getorderdata->pincode }}.</p>
                        </div>
                    </td>
                @endif
            </tr>
        </table>
    </div>
    <div class="table-section bill-tbl w-100 mt-10">
        <table class="table w-100 mt-10">
            <tr>
                <th class="w-33">{{ trans('labels.payment_options') }}</th>
                <th class="w-33">{{ trans('labels.shipping_method') }}</th>
                @if (@helper::checkaddons('vendor_tip'))
                    @if (@helper::otherappdata($getorderdata->vendor_id)->tips_settings == 1)
                        <th class="w-33">{{ trans('labels.tips') }}</th>
                    @endif
                @endif
            </tr>
            <tr>
                <td>
                    @if ($getorderdata->payment_type == 0)
                        {{ trans('labels.online') }}
                    @elseif ($getorderdata->payment_type == 6)
                        {{ @helper::getpayment($getorderdata->payment_type, $getorderdata->vendor_id)->payment_name }}
                        : <small>
                            <a href="{{ helper::image_path($transaction->screenshot) }}" target="_blank"
                                class="text-danger">{{ trans('labels.click_here') }}</a>
                        </small>
                    @else
                        {{ @helper::getpayment($getorderdata->payment_type, $getorderdata->vendor_id)->payment_name }}
                    @endif
                </td>
                <td>
                    @if ($getorderdata->order_type == 1)
                        {{ trans('labels.delivery') }}
                    @elseif ($getorderdata->order_type == 2)
                        {{ trans('labels.pickup') }}
                    @elseif ($getorderdata->order_type == 3)
                        {{ trans('labels.table') }}
                    @elseif ($getorderdata->order_type == 4)
                        {{ trans('labels.pos') }}
                    @endif
                </td>
                @if (@helper::checkaddons('vendor_tip'))
                    @if (@helper::otherappdata($getorderdata->vendor_id)->tips_settings == 1)
                        <td>
                            {{ helper::currency_formate($getorderdata->tips, $getorderdata->vendor_id) }}
                        </td>
                    @endif
                @endif
            </tr>
        </table>
    </div>
    <div class="table-section bill-tbl w-100 mt-10">
        <table class="table w-100 mt-10">
            <tr>
                <th class="w-50">{{ trans('labels.product_name') }}</th>
                <th class="w-50">{{ trans('labels.unit_cost') }}</th>
                <th class="w-50">{{ trans('labels.qty') }}</th>
                <th class="w-50">{{ trans('labels.sub_total') }}</th>
            </tr>
            @foreach ($ordersdetails as $orders)
                <tr align="center">
                    <td>{{ $orders->item_name }}
                        @if ($orders->variants_id != '')
                            - <small>{{ $orders->variants_name }}
                                ({{ helper::currency_formate($orders->variants_price, $getorderdata->vendor_id) }})</small>
                        @endif
                        @if ($orders->extras_id != '')
                            @php
                                $extras_id = explode('|', $orders->extras_id);
                                $extras_name = explode('|', $orders->extras_name);
                                $extras_price = explode('|', $orders->extras_price);
                                $extras_total_price = 0;
                            @endphp
                            <br>
                            @foreach ($extras_id as $key => $addons)
                                <small>
                                    <b class="text-muted">{{ $extras_name[$key] }}</b> :
                                    {{ helper::currency_formate($extras_price[$key], $getorderdata->vendor_id) }}<br>
                                </small>
                                @php
                                    $extras_total_price += $extras_price[$key];
                                @endphp
                            @endforeach
                        @else
                            @php
                                $extras_total_price = 0;
                            @endphp
                        @endif
                    </td>
                    @php
                        $price = (float) $extras_total_price + (float) $orders->variants_price;
                        $total = (float) $price * (float) $orders->qty;
                    @endphp
                    <td> {{ helper::currency_formate($price, $getorderdata->vendor_id) }}
                    </td>
                    <td>{{ $orders->qty }}</td>
                    <td> {{ helper::currency_formate($total, $getorderdata->vendor_id) }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4">
                    <div class="total-part">
                        @php
                            $tax = explode('|', $getorderdata->tax);
                            $tax_name = explode('|', $getorderdata->tax_name);
                        @endphp
                        <div class="total-left w-85 float-left" align="left">
                            <p>{{ trans('labels.sub_total') }}</p>
                            @if ($getorderdata->discount_amount > 0)
                                <p>{{ trans('labels.discount') }}{{ $getorderdata->couponcode != '' ? '(' . $getorderdata->couponcode . ')' : '' }}
                                </p>
                            @endif
                            @if ($getorderdata->tax != null && $getorderdata->tax != '')
                                @foreach ($tax as $key => $tax_value)
                                    <p>{{ $tax_name[$key] }}</p>
                                @endforeach
                            @endif

                            @if ($getorderdata->order_type == 1)
                                <p>{{ trans('labels.delivery') }}
                                    @if ($getorderdata->delivery_area != '')
                                        ({{ $getorderdata->delivery_area }})
                                    @endif
                                </p>
                            @endif
                            <p><strong>{{ trans('labels.grand_total') }}</strong></p>
                        </div>
                        <div class="total-right w-15 float-left text-bold" align="right">
                            <p>{{ helper::currency_formate($getorderdata->sub_total, $getorderdata->vendor_id) }}</p>
                            @if ($getorderdata->discount_amount > 0)
                                <p>{{ helper::currency_formate($getorderdata->discount_amount, $getorderdata->vendor_id) }}
                                </p>
                            @endif
                            @if ($getorderdata->tax != null && $getorderdata->tax != '')
                                @foreach ($tax as $key => $tax_value)
                                    <p>{{ helper::currency_formate((float) $tax[$key], $getorderdata->vendor_id) }}
                                @endforeach
                            @endif
                            </p>
                            @if ($getorderdata->order_type == 1)
                                <p>
                                    @if ($getorderdata->delivery_charge > 0)
                                        {{ helper::currency_formate($getorderdata->delivery_charge, $getorderdata->vendor_id) }}
                                    @else
                                        {{ trans('labels.free') }}
                                    @endif
                                </p>
                            @endif
                            <p> <strong>{{ helper::currency_formate($getorderdata->grand_total, $getorderdata->vendor_id) }}</strong>
                            </p>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
