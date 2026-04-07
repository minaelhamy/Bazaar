<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ trans('labels.print') }}</title>
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ helper::image_path(@helper::appdata($getorderdata->vendor_id)->favicon) }}">
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/bootstrap/bootstrap.min.css') }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/fontawesome/all.min.css') }}">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/toastr/toastr.min.css') }}">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/sweetalert/sweetalert2.min.css') }}">
    <!-- Sweetalert CSS -->
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/style.css') }}"><!-- Custom CSS -->
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/responsive.css') }}">
    <!-- Responsive CSS -->
    <style type="text/css">
        body {
            width: 88mm;
            height: 100%;
            margin: 0;
            padding: 0;
            --webkit-font-smoothing: antialiased;
        }

        #printDiv {
            font-weight: 600;
            margin-left: 0px;
            padding: 0;
        }

        #printDiv div .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
        }

        @media print {
            @page {
                margin: 0;
            }

            body {
                margin: 1.6cm;
            }

            #btnPrint {
                display: none;
            }
        }

        .border-top-bottom {
            border-top: 1px solid black !important;
            border-bottom: 1px solid black !important;
        }

        /* =================add extra css (Dhruvil)================= */

        .resept {
            width: 80mm;
            background-color: #ececec;
        }

        .fs-10 {
            font-size: 12px !important;
        }

        .underline-3 {
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
        }

        .resept .table>:not(caption)>*>* {
            background-color: transparent !important;
        }

        .product-text-size {
            font-size: .75rem !important;
        }

        .line-1 {
            text-overflow: ellipsis;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
        }

        .line-2 {
            text-overflow: ellipsis;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .txt-resept-font-size {
            font-size: 10px;
        }

        .fs-8 {
            font-size: 14px !important;
        }

        .fw-600 {
            font-weight: 600;
        }

        .fw-500 {
            font-weight: 500;
        }

        .btn-secondary {
            --bs-btn-color: #fff;
            --bs-btn-bg: var(--bs-secondary) !important;
            --bs-btn-border-color: var(--bs-secondary) !important;
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: var(--bs-secondary) !important;
            --bs-btn-hover-border-color: var(--bs-secondary) !important;
            --bs-btn-focus-shadow-rgb: 49, 132, 253;
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: var(--bs-secondary) !important;
            --bs-btn-active-border-color: var(--bs-secondary) !important;
            --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
            --bs-btn-disabled-color: #fff;
            --bs-btn-disabled-bg: var(--bs-secondary) !important;
            --bs-btn-disabled-border-color: var(--bs-secondary) !important;
        }
    </style>
</head>

<body>
    @php
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
    @endphp
    <div id="printDiv">
        <div class="resept p-2">
            <h5 class="m-0 text-uppercase text-dark fs-8 text-center line-2 fw-600">
                {{ @helper::appdata($getorderdata->vendor_id)->website_title }}
            </h5>
            <div class="col-12 mt-1 d-flex gap-1 align-items-center justify-content-center ">
                <small class=" text-uppercase fs-10 text-center text-dark fw-500 line-2">
                    {{ @$getorderdata->delivery_area }}
                </small>
            </div>
            <div class="col-12 mt-1 d-flex gap-1 align-items-center justify-content-center">
                <p class=" m-0 fw-500 text-uppercase fs-10 text-center  text-dark line-1">
                    {{ trans('labels.name') }} :</p>
                <small class="fw-500 text-uppercase fs-10 text-center text-dark  line-1">
                    {{ @$getorderdata->customer_name }}
                </small>
            </div>
            <div class="col-12 mt-1 d-flex gap-1 align-items-center justify-content-center">
                <p class="fw-500 m-0 text-uppercase fs-10 text-center  text-dark line-1">
                    {{ trans('labels.email') }} :</p>
                <small class="fw-500 text-uppercase fs-10 text-center text-dark  line-1">
                    {{ @$getorderdata->customer_email }}
                </small>
            </div>
            <div class="col-12 mt-1 d-flex gap-1 align-items-center justify-content-center">
                <p class="fw-500 m-0 text-uppercase fs-10 text-center  text-dark line-1">
                    {{ trans('labels.mobile') }} :</p>
                <small class="fw-500 text-uppercase fs-10 text-center text-dark  line-1">
                    {{ @$getorderdata->mobile }}
                </small>
            </div>
            @if (helper::appdata($vendor_id)->ordertype_date_time == 1)
                @if ($getorderdata->order_from != 'pos' && $getorderdata->order_type != 3)
                    <div class="total-billes-amount">
                        @if ($getorderdata->delivery_date != '' && $getorderdata->delivery_date != null)
                            <div
                                class="fw-500 d-flex gap-1 align-items-center justify-content-between m-0 text-uppercase fs-10 text-center text-dark">
                                {{ $getorderdata->order_type == 1 ? trans('labels.delivery_date') : trans('labels.pickup_date') }}
                                :
                                <small class="fw-500 text-uppercase fs-10 text-center text-dark line-1">
                                    {{ helper::date_format($getorderdata->delivery_date, $vendor_id) }}
                                </small>
                            </div>
                        @endif
                        @if ($getorderdata->delivery_time != '' && $getorderdata->delivery_time != null)
                            <p
                                class="fw-500 d-flex gap-1 align-items-center justify-content-between m-0 text-uppercase fs-10 text-center text-dark mt-1 line-1">
                                {{ $getorderdata->order_type == 1 ? trans('labels.delivery_time') : trans('labels.pickup_time') }}
                                :
                                <small class="fw-500 text-uppercase fs-10 text-center text-dark line-1">
                                    {{ @$getorderdata->delivery_time }}
                                </small>
                            </p>
                        @endif
                    </div>
                @endif
            @endif
            <div class="total-billes-amount">
                <div class="col-12 d-flex justify-content-between align-items-end">
                    <div
                        class="fw-500 d-flex gap-1 align-items-center justify-content-center m-0 text-uppercase fs-10 text-center text-dark">
                        Order no :
                        <small class="fw-500 text-uppercase fs-10 text-center text-dark line-1">
                            #{{ @$getorderdata->order_number }}
                        </small>
                    </div>
                    <p
                        class="fw-500 d-flex gap-1 align-items-center justify-content-center m-0 text-uppercase fs-10 text-center text-dark mt-1 line-1">
                        {{ trans('labels.date') }} :
                        <small class="fw-500 text-uppercase fs-10 text-center text-dark line-1">
                            {{ helper::date_format($getorderdata->created_at, $vendor_id) }}
                        </small>
                    </p>
                </div>
            </div>
            <table class="table table-borderless my-2 bg-transparent">
                <thead class="underline-3">
                    <tr class="text-secondary">
                        <th scope="col" class=" product-text-size fw-bold">#</th>
                        <th scope="col" class=" product-text-size fw-bold">{{ trans('labels.item') }}
                        </th>
                        <th scope="col" class=" product-text-size fw-bold text-end">{{ trans('labels.qty') }}</th>
                        <th scope="col" class=" product-text-size fw-bold text-end pe-0">{{ trans('labels.total') }}
                        </th>
                    </tr>
                </thead>
                <tbody>

                    @if (!empty($ordersdetails))
                        @php
                            $i = 1;
                            $qty = [];
                        @endphp
                        @foreach ($ordersdetails as $item)
                            @php
                                $qty[] = $item->qty;
                            @endphp
                            <tr class="align-middle">
                                <td class="py-2">
                                    <p class="fw-500 text-dark line-1 m-0 product-text-size">{{ $i++ }}</p>
                                </td>
                                <td class="py-2">
                                    <h6 class="m-0 fw-500 product-text-size">
                                        <span class="fw-500 mb-3"> {{ @$item->item_name }} @if ($item->variants_name != null)
                                                - ({{ $item->variants_name }} :
                                                {{$item->variants_price, $vendor_id}}
                                            @endif
                                        </span>
                                        @if ($item->extras_id != '')
                                            @php
                                                $extras_id = explode('|', $item->extras_id);
                                                $extras_name = explode('|', $item->extras_name);
                                                $extras_price = explode('|', $item->extras_price);
                                                $extras_total_price = 0;
                                            @endphp
                                            <br>
                                            @foreach ($extras_id as $key => $addons)
                                                <small>
                                                    <b class="text-muted">{{ $extras_name[$key] }}</b> :
                                                    {{$extras_price[$key], $vendor_id}}
                                                    <br>
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
                                    </h6>
                                </td>
                                <td class="py-2 text-end">
                                    <div
                                        class="fw-500 product-text-size d-flex align-items-center justify-content-center">
                                        <p class="m-0 text-dark">
                                            @php
                                                $price = (float) $extras_total_price + (float) $item->variants_price;
                                                $total = (float) $price * (float) $item->qty;
                                            @endphp
                                            @if ($item->variants_id != null && $item->variants_id != '')
                                                {{ @$item->qty }}
                                            @else
                                                {{ @$item->qty }}
                                            @endif
                                        </p>
                                    </div>
                                </td>
                                <td class="py-2 pe-0 text-end">
                                    <p class="text-dark fw-500 line-1 m-0  product-text-size">
                                        {{$total, $vendor_id}}
                                    </p>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
                <tfoot>
                    <tr class="underline-3">
                        <td class="py-2" colspan="2">
                            <h6 class="line-1 m-0 fw-600 product-text-size">{{ trans('labels.sub_total') }}</h6>
                        </td>
                        <td class="py-2 text-end">
                            <div class=" product-text-size d-flex align-items-center justify-content-center">
                                <p class="m-0 text-dark">
                                    @if ($item->variants_id != null && $item->variants_id != '')
                                        {{ array_sum($qty) }}
                                    @else
                                        {{ array_sum($qty) }}
                                    @endif
                                </p>
                            </div>
                        </td>
                        <td class="py-2 pe-0 text-end">
                            <p class="text-dark line-1 fw-500 m-0  product-text-size">
                                {{$getorderdata->sub_total, $vendor_id}}
                            </p>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <div class="col-12 d-flex mb-2 justify-content-end">
                <div class="col-7">
                    <div class="col-12">
                        <div class="text-dark">
                            @if ($getorderdata->discount_amount > 0)
                                <div class="d-flex justify-content-between text-dark my-1">
                                    <div class="">
                                        <span class="txt-resept-font-size fw-500 text-uppercase line-1">
                                            {{ trans('labels.discount') }} (-)
                                        </span>
                                    </div>
                                    <div class="">
                                        <span class="txt-resept-font-size fw-500 text-uppercase text-end line-1">
                                            {{$getorderdata->discount_amount, $vendor_id}}
                                        </span>
                                    </div>
                                </div>
                            @endif
                            @php
                                $tax = explode('|', $getorderdata->tax);
                                $tax_name = explode('|', $getorderdata->tax_name);
                            @endphp

                            @if ($getorderdata->tax != null && $getorderdata->tax != '')
                                @foreach ($tax as $key => $tax_value)
                                    <div class="d-flex justify-content-between text-dark my-1">
                                        <span class="txt-resept-font-size fw-500 text-uppercase line-1 text-end">
                                            {{ @$tax_name[$key] }}
                                        </span>
                                        <span class="txt-resept-font-size fw-500 text-uppercase line-1 text-end">
                                            {{@(float) $tax[$key], $vendor_id}}
                                        </span>
                                    </div>
                                @endforeach
                            @endif
                            @if ($getorderdata->order_type == 1)
                                <div class="d-flex justify-content-between text-dark my-1">
                                    <span class="txt-resept-font-size fw-500 text-uppercase line-1">
                                        {{ trans('labels.delivery') }}
                                        @if ($getorderdata->delivery_area != '')
                                            ({{ $getorderdata->delivery_area }})
                                        @endif
                                    </span>
                                    <span class="txt-resept-font-size fw-500 text-uppercase line-1 text-end">
                                        @if ($getorderdata->delivery_charge > 0)
                                        {{$getorderdata->delivery_charge, $vendor_id}}
                                        @else
                                            {{ trans('labels.free') }}
                                        @endif
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between fw-600 text-dark underline-3 py-2">
                <span class="fw-semibold product-text-size line-1">{{ trans('labels.total_amount') }}</span>
                <span class="fw-semibold line-1 product-text-size">
                    {{$getorderdata->grand_total, $vendor_id}}
                </span>
            </div>
            @if (@helper::checkaddons('vendor_tip'))
                @if (@helper::otherappdata($getorderdata->vendor_id)->tips_settings == 1)
                    <div class="col-12 d-flex justify-content-between py-2">
                        <span class="fw-semibold product-text-size line-1">{{ trans('labels.tips') }}</span>
                        <span class="fw-semibold line-1 product-text-size">
                            {{$getorderdata->tips, $getorderdata->vendor_id}}
                        </span>
                    </div>
                @endif
            @endif
            <h2 class="mb-2 fs-8 fw-600 text-dark text-center line-1">{{ trans('labels.thank_you_note') }}</h2>
            <div class="col-12 mt-2 d-flex justify-content-center">
                <button type='button' id="btnPrint"
                    class="rounded border-0 btn btn-secondary px-sm-4 text-light text-capitalize fs-8">{{ trans('labels.print') }}</button>
            </div>
        </div>
    </div>
    <script>
        const $btnPrint = document.querySelector("#btnPrint");
        $btnPrint.addEventListener("click", () => {
            window.print();
        });
    </script>
</body>
