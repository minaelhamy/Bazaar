@include('front.theme.header')
<section class="breadcrumb-sec bg-change-mode">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-dark"><a class="text-dark color-changer"
                        href="{{ helper::storefront_url($storeinfo, '/') }}">{{ trans('labels.home') }}</a>
                </li>
                <li class="text-muted breadcrumb-item {{ session()->get('direction') == 2 ? 'rtl' : '' }} text-dark active"
                    aria-current="page">{{ trans('labels.order_details') }}</li>
            </ol>
        </nav>
    </div>
</section>
@if (Auth::user() && Auth::user()->type == 3 && request()->has('order'))
@else
    <section class="order_detail py-3">
        <div class="container">
            <div class="row align-items-center justify-content-between mb-5">
                <div class="col-lg-6">
                    <img src="{{ helper::image_path(helper::appdata($storeinfo->id)->order_detail_image) }}"
                        class="w-100 mb-5 mb-lg-0" alt="tracking">
                </div>
                <div class="col-lg-6 col-xl-5">
                    <h2 class="track-title color-changer text-truncate">{{ trans('labels.track_orders') }}</h2>
                    <p class="text-muted mb-4 line-3 fs-7">{{ trans('labels.track_order_message') }}</p>
                    <form action="{{ helper::storefront_url($storeinfo, '/find-order') }}" method="get">
                        <label class="form-label label14">{{ trans('labels.order_id') }}</label>
                        <div class="input-group mb-4">
                            <input type="text" class="form-control rounded-2 p-3" name="order"
                                value="{{ $order_number }}" placeholder="{{ trans('labels.find_order_placeholder') }}"
                                required>
                        </div>
                        <button class="btn btn-store w-100" type="submit"
                            id="track_here">{{ trans('labels.track_here') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endif
@if (!empty($getorderdata))
    <section class="order_details">
        <div class="container">
            @if (Auth::user() && Auth::user()->type == 3)
            @else
                <h2 class="text-center mb-4 text-dark fw-bold color-changer">{{ trans('labels.order_details') }}</h2>
            @endif
            <!-- Your Order details -->
            <div class="card rounded-2 bg-light mb-3">
                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-12 col-md-9 col-lg-8 col-xl-6">
                            <div class="d-md-flex justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center justify-contente-between py-2">
                                        <span class="text-dark color-changer fw-bold">{{ trans('labels.order_id') }} :&nbsp;</span>
                                        <div class="fw-bold text-secondary">#{{ $order_number }}</div>
                                    </div>

                                    @if (helper::appdata($getorderdata->vendor_id)->product_type == 1 &&
                                            helper::appdata($getorderdata->vendor_id)->ordertype_date_time == 1)
                                        @if ($getorderdata->delivery_date)
                                            <div class="d-flex align-items-center text-muted justify-contente-between py-2">
                                                <span class="text-dark color-changer fw-bold">
                                                    {{ trans('labels.order_date') }}
                                                    :&nbsp;
                                                </span>{{ helper::date_format($getorderdata->delivery_date, $storeinfo->id) }}
                                            </div>
                                        @endif
                                    @elseif(helper::appdata($getorderdata->vendor_id)->product_type == 2)
                                        <div class="d-flex align-items-center text-muted justify-contente-between py-2">
                                            <span class="text-dark color-changer fw-bold">{{ trans('labels.order_date') }}
                                                :&nbsp;</span>{{ helper::date_format($getorderdata->created_at, $storeinfo->id) }}
                                            
                                        </div>
                                    @endif
                                    @if (helper::appdata($getorderdata->vendor_id)->product_type == 1)
                                        <div class="d-flex align-items-center text-muted justify-contente-between py-2">
                                            <span class="text-dark color-changer fw-bold">{{ trans('labels.order') }}
                                                {{ trans('labels.status') }}
                                                :&nbsp;</span>
                                            @if ($getorderdata->status_type == 1)
                                                {{ @helper::gettype($getorderdata->status, $getorderdata->status_type, $getorderdata->order_type, $getorderdata->vendor_id)->name }}
                                            @elseif($getorderdata->status_type == 2)
                                                {{ @helper::gettype($getorderdata->status, $getorderdata->status_type, $getorderdata->order_type, $getorderdata->vendor_id)->name }}
                                            @elseif($getorderdata->status_type == 4)
                                                {{ @helper::gettype($getorderdata->status, $getorderdata->status_type, $getorderdata->order_type, $getorderdata->vendor_id)->name }}
                                            @elseif($getorderdata->status_type == 3)
                                                {{ @helper::gettype($getorderdata->status, $getorderdata->status_type, $getorderdata->order_type, $getorderdata->vendor_id)->name }}
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <div class="px-sm-2">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex px-0 bg-transparent">
                                            <span class="text-dark color-changer fw-bold">{{ trans('labels.order_type') }}
                                                :&nbsp;</span>
                                            <span class="text-muted">
                                                @if ($getorderdata->order_type == 1)
                                                    {{ trans('labels.delivery') }}
                                                @elseif ($getorderdata->order_type == 2)
                                                    {{ trans('labels.pickup') }}
                                                @elseif ($getorderdata->order_type == 3)
                                                    {{ trans('labels.table') }}
                                                    ({{ $getorderdata->dinein_tablename != '' ? $getorderdata->dinein_tablename : '-' }})
                                                @elseif ($getorderdata->order_type == 4)
                                                    {{ trans('labels.pos') }}
                                                @elseif ($getorderdata->order_type == 5)
                                                    {{ trans('labels.digital') }}
                                                @endif
                                            </span>
                                        </li>
                                    </ul>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex px-0 bg-transparent">
                                            <span class="text-dark color-changer fw-bold">{{ trans('labels.payment_type') }}
                                                :&nbsp;</span>
                                            <span class="text-muted">
                                                {{ @helper::getpayment($getorderdata->payment_type, $storeinfo->id)->payment_name }}
                                            </span>
                                        </li>
                                    </ul>
                                    @if (@helper::checkaddons('vendor_tip'))
                                        @if (@helper::otherappdata($storeinfo->id)->tips_settings == 1)
                                            <div class="d-flex align-items-center justify-contente-between py-2">
                                                <span class="text-dark color-changer fw-bold">
                                                    {{ trans('labels.tips_pro') }} :&nbsp;
                                                </span>
                                                <span class="text-muted">
                                                    {{ helper::currency_formate($getorderdata->tips, $storeinfo->id) }}
                                                </span>
                                                </p>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if (
                            ($getorderdata->status_type == 1 && helper::appdata($storeinfo->id)->product_type == 1) ||
                                (helper::appdata($storeinfo->id)->product_type == 2 && $getorderdata->payment_status == 1))
                            <div class="col-12 col-md-3 col-lg-3 col-xl-2">
                                <a class="btn btn-store fw-500  {{ session()->get('direction') == 2 ? 'float-start' : 'float-end' }}"
                                    href="{{ helper::storefront_url($storeinfo, '/cancel-order/' . $order_number) }}"
                                    onclick="#">{{ trans('labels.cancel_order') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row justify-content-between g-4">
                <!-- Delivery Info -->
                <div
                    class="{{ helper::appdata($storeinfo->id)->product_type == 1 && $getorderdata->order_type == 1 ? 'col-md-6 d-block' : 'd-none' }} col-12">
                    <div class="order-add h-100 rounded-2 bg-light card">
                        <h5 class="customer-title border-bottom pb-2">
                            <span class="custom-icon color-changer d-flex m-0">
                                <i class="fa-light fa-truck-ramp-box"></i>
                                <span class="px-2 checkoutform-title m-0">{{ trans('labels.delivery_info') }}</span>
                            </span>
                        </h5>
                        <span class="color-changer">{{ trans('labels.address') }}</span>
                        <p class="border-bottom color-changer">{{ $getorderdata->address }}</p>
                        <span class="color-changer">{{ trans('labels.building') }}</span>
                        <p class="border-bottom color-changer">{{ $getorderdata->building }}</p>
                        <span class="color-changer">{{ trans('labels.landmark') }}</span>
                        <p class="border-bottom color-changer">{{ $getorderdata->landmark }}</p>
                        <span class="color-changer">{{ trans('labels.pincode') }}</span>
                        <p class="border-bottom color-changer">{{ $getorderdata->pincode }}</p>
                    </div>
                </div>
                <!-- Customer Info -->
                <div
                    class="{{ helper::appdata($storeinfo->id)->product_type == 1 && $getorderdata->order_type == 1 ? 'col-md-6' : 'col-md-12' }} col-12">
                    <div class="order-add h-100 rounded-2 bg-light card">
                        <h5 class="customer-title border-bottom pb-2">
                            <span class="custom-icon color-changer d-flex m-0">
                                <i class="fa-light fa-user "></i>
                                <span class="px-2 checkoutform-title m-0">{{ trans('labels.customer_info') }}</span>
                            </span>
                        </h5>
                        <span class="color-changer">{{ trans('labels.name') }}</span>
                        <p class="border-bottom color-changer">{{ $getorderdata->customer_name }}</p>
                        <span class="color-changer">{{ trans('labels.email') }}</span>
                        <p class="border-bottom color-changer">{{ $getorderdata->customer_email }}</p>
                        <span class="color-changer">{{ trans('labels.mobile') }}</span>
                        <p class="border-bottom color-changer">{{ $getorderdata->mobile }}</p>
                    </div>
                </div>
            </div>
            @if ($getorderdata->vendor_note != null && $getorderdata->vendor_note != '')
                <div class="card rounded-0 mt-4 card-shadow bg-light">
                    <div class="card-body">
                        <h5 class="customer-title color-changer border-bottom pb-2 mb-4">
                            <i class="fa-light fa-comment-dots"></i>
                            <span class="px-2 checkoutform-title">{{ trans('labels.vendor_note') }}</span>
                        </h5>
                        <p class="line-2 text-capitalize text-muted">{{ $getorderdata->vendor_note }}</p>
                    </div>
                </div>
            @endif
            <div class="row my-4">
                <div class="col-md-12 col-lg-7 col-xl-8 mb-4 mb-lg-0">
                    <!-- order Summary -->
                    <div class="card bg-light rounded-2">
                        <div class="card-body">
                            <h5 class="payment-title border-bottom pb-2 m-0">
                                <span class="custom-icon color-changer">
                                    <i class="fa-light fa-box-archive"></i>
                                    <span class="px-2 checkoutform-title">{{ trans('labels.order_summary') }}</span>
                                </span>
                            </h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr class="text-capitalize fw-semibold">
                                            <td>{{ trans('labels.image') }}</td>
                                            <td>{{ trans('labels.product') }}</td>
                                            <td>{{ trans('labels.unit_cost') }}</td>
                                            <td>{{ trans('labels.qty') }}</td>
                                            <td>{{ trans('labels.sub_total') }}</td>
                                            <td></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($getorderitemlist as $product)
                                            <tr class="align-middle">
                                                <td><img src="{{ helper::image_path($product->item_image) }}"
                                                        class="object-fit-cover rounded hw-70-px"> </td>
                                                @if ($product->extras_id != '')
                                                    @php
                                                        $extras_id = explode('|', $product->extras_id);
                                                        $extras_name = explode('|', $product->extras_name);
                                                        $extras_price = explode('|', $product->extras_price);
                                                        $extras_total_price = 0;
                                                    @endphp
                                                    @foreach ($extras_id as $key => $addons)
                                                        @php
                                                            $extras_total_price += $extras_price[$key];
                                                        @endphp
                                                    @endforeach
                                                @else
                                                    @php
                                                        $extras_total_price = 0;
                                                    @endphp
                                                @endif

                                                <td class="mw-400">
                                                    <p class="line-2 mb-1">{{ $product->item_name }}</p>
                                                    @if ($product->variants_id != '' || $product->extras_id != '')
                                                        <span class="text-muted fs-7 cursor-pointer"
                                                            onclick='showaddons("{{ $product->id }}","{{ $product->item_name }}","{{ $product->attribute }}","{{ $product->extras_name }}","{{ $product->extras_price }}","{{ $product->variants_name }}","{{ $product->variants_price }}")'>
                                                            {{ trans('labels.customize') }}</span>
                                                    @endif
                                                </td>
                                                @php
                                                    $price =
                                                        (float) $extras_total_price + (float) $product->variants_price;
                                                    $total = (float) $price * (float) $product->qty;
                                                @endphp
                                                <td>
                                                    {{ helper::currency_formate($price, $storeinfo->id) }}
                                                </td>
                                                <td>{{ $product->qty }}</td>
                                                <td>
                                                    {{ helper::currency_formate($total, $storeinfo->id) }}
                                                </td>
                                                @if (@helper::checkaddons('digital_product'))
                                                    <td>
                                                        @php
                                                            $items = helper::getmin_maxorder(
                                                                $product->item_id,
                                                                $storeinfo->id,
                                                            );
                                                        @endphp
                                                        @if (helper::appdata($getorderdata->vendor_id)->product_type == 2 && $getorderdata->payment_status == 2)
                                                            @if ($items->download_file != '' && $items->download_file != null)
                                                                <a href="{{ url(env('ASSETPATHURL') . 'admin-assets/images/product/' . $items->download_file) }}"
                                                                    tooltip="{{ trans('labels.download') }}"
                                                                    target="_blank">
                                                                    <i class="fa-solid fa-download"></i></a>
                                                            @endif
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-12">
                    <!-- Product order nots -->
                    @if ($getorderdata->order_notes != null && $getorderdata->order_notes != '')
                        <div class="card rounded-0 mb-4 card-shadow">
                            <div class="card-body">
                                <h5 class="customer-title color-changer border-bottom pb-2 mb-4">
                                    <i class="fa-light fa-comment-dots"></i>
                                    <span class="px-2 checkoutform-title">{{ trans('labels.notes') }}</span>
                                </h5>
                                <p class="line-2 text-capitalize text-muted">{{ $getorderdata->order_notes }}</p>
                            </div>
                        </div>
                    @endif
                    <!-- payment summary -->
                    <div class="card bg-light rounded-2">
                        <div class="card-body">
                            <h5 class="payment-title border-bottom pb-2 mb-4">
                                <span class="custom-icon color-changer">
                                    <i class="fa-light fa-file-invoice"></i>
                                    <span class="px-2 checkoutform-title">{{ trans('labels.payment_summary') }}</span>
                                </span>
                            </h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between border-0 bg-transparent color-changer sub-total">
                                    <h6 class="m-0 fw-semibold">{{ trans('labels.sub_total') }}</h6>
                                    <span class="color-changer">{{ helper::currency_formate($getorderdata->sub_total, $storeinfo->id) }}</span>
                                </li>
                                @if ($getorderdata->discount_amount > 0)
                                    <li
                                        class="list-group-item d-flex justify-content-between border-0 bg-transparent color-changer sub-total">
                                        <h6 class="m-0 fw-semibold">{{ trans('labels.discount') }}</h6>
                                        <span class="color-changer">-{{ helper::currency_formate($getorderdata->discount_amount, $storeinfo->id) }}</span>
                                    </li>
                                @endif
                                @php
                                    $tax = explode('|', $getorderdata->tax);
                                    $tax_name = explode('|', $getorderdata->tax_name);
                                @endphp
                                @if ($getorderdata->tax != null && $getorderdata->tax != '')
                                    @foreach ($tax as $key => $tax_value)
                                        <li
                                            class="list-group-item d-flex justify-content-between border-0 bg-transparent color-changer sub-total">
                                            <h6 class="m-0 fw-semibold">{{ $tax_name[$key] }}</h6>
                                            <span class="color-changer">{{ helper::currency_formate(@(float) $tax[$key], $storeinfo->id) }}</span>
                                        </li>
                                    @endforeach
                                @endif
                                @if ($getorderdata->order_type == 1)
                                    <li
                                        class="list-group-item d-flex justify-content-between border-0 bg-transparent color-changer sub-total">
                                        <h6 class="m-0 fw-semibold">{{ trans('labels.delivery') }}
                                            @if ($getorderdata->delivery_area != '')
                                                ({{ $getorderdata->delivery_area }})
                                            @endif
                                        </h6>
                                        <span class="color-changer">
                                            @if ($getorderdata->delivery_charge > 0)
                                                {{ helper::currency_formate($getorderdata->delivery_charge, $storeinfo->id) }}
                                            @else
                                                {{ trans('labels.free') }}
                                            @endif
                                        </span>
                                    </li>
                                @endif

                                <li
                                    class="list-group-item d-flex justify-content-between border-0 border-top border-1 border-dark text-dark bg-transparent sub-total mt-2">
                                    <h6 class="m-0 fw-bolder text-success">{{ trans('labels.total_amount') }}</h6>
                                    <span class="text-success"><strong>{{ helper::currency_formate($getorderdata->grand_total, $storeinfo->id) }}</strong></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
<!-- newsletter -->
@include('front.newsletter')
<!-- newsletter -->
@include('front.theme.footer')
