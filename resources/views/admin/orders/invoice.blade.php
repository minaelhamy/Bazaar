@php
    if (Auth::user()->type == 4) {
        $vendor_id = Auth::user()->vendor_id;
    } else {
        $vendor_id = Auth::user()->id;
    }
@endphp

@extends('admin.layout.default')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{ trans('labels.invoice') }}</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                @if (Auth::user()->type == 1)
                    <li class="breadcrumb-item text-dark">
                        <a href="{{ URL::to('admin/customers/orders-' . $getorderdata->user_id) }}"
                            class="color-changer">{{ trans('labels.orders') }}</a>
                    </li>
                @else
                    <li class="breadcrumb-item text-dark">
                        <a href="{{ URL::to('admin/orders') }}" class="color-changer">{{ trans('labels.orders') }}</a>
                    </li>
                @endif

                <li class="breadcrumb-item active {{ session()->get('direction') == 2 ? 'breadcrumb-rtl' : '' }}"
                    aria-current="page">{{ trans('labels.invoice') }}</li>
            </ol>
        </nav>
    </div>
    <div class="row">

        @if ($getorderdata->status_type == 3 || $getorderdata->status_type == 4)
            @if (helper::appdata($vendor_id)->product_type == 1)
                <div class="col-md-12 my-2 d-flex justify-content-end">
                    @if ($getorderdata->status_type == '1')
                        <span class="px-sm-4 btn btn-warning">
                            {{ @helper::gettype($getorderdata->status, $getorderdata->status_type, $getorderdata->order_type, $vendor_id)->name == null ? '-' : @helper::gettype($getorderdata->status, $getorderdata->status_type, $getorderdata->order_type, $vendor_id)->name }}
                        </span>
                    @elseif($getorderdata->status_type == '2')
                        <span class="px-sm-4 btn btn-info">
                            {{ @helper::gettype($getorderdata->status, $getorderdata->status_type, $getorderdata->order_type, $vendor_id)->name == null ? '-' : @helper::gettype($getorderdata->status, $getorderdata->status_type, $getorderdata->order_type, $vendor_id)->name }}
                        </span>
                    @elseif($getorderdata->status_type == '3')
                        <span class="px-sm-4 btn btn-success">
                            {{ @helper::gettype($getorderdata->status, $getorderdata->status_type, $getorderdata->order_type, $vendor_id)->name == null ? '-' : @helper::gettype($getorderdata->status, $getorderdata->status_type, $getorderdata->order_type, $vendor_id)->name }}
                        </span>
                    @elseif($getorderdata->status_type == '4')
                        <span class="px-sm-4 btn btn-danger">
                            {{ @helper::gettype($getorderdata->status, $getorderdata->status_type, $getorderdata->order_type, $vendor_id)->name == null ? '-' : @helper::gettype($getorderdata->status, $getorderdata->status_type, $getorderdata->order_type, $vendor_id)->name }}
                        </span>
                    @else
                        --
                    @endif
                </div>
            @else
                <div class="col-md-12 my-2 d-flex justify-content-end">
                    @if ($orderdata->status_type == '3')
                        <span class="px-sm-4 btn btn-success">{{ trans('labels.completed') }}</span>
                    @elseif($orderdata->status_type == '4')
                        <span class="px-sm-4 btn btn-danger">{{ trans('labels.cancelled') }}</span>
                    @else
                        --
                    @endif
                </div>
            @endif
        @else
            <div class="col-md-12 my-2 d-flex justify-content-end">
                @if (helper::appdata($vendor_id)->product_type == 1)
                    @if ($getorderdata->status_type != 3 || $getorderdata->status_type != 4)
                        <div class="lag-btn dropdown">
                            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle py-2 px-sm-4"
                                data-bs-toggle="dropdown">{{ @helper::gettype($getorderdata->status, $getorderdata->status_type, $getorderdata->order_type, $vendor_id)->name == null ? '-' : @helper::gettype($getorderdata->status, $getorderdata->status_type, $getorderdata->order_type, $vendor_id)->name }}</button>
                            <div
                                class="dropdown-menu rounded mt-1 p-0 bg-body-secondary shadow border-0 overflow-hidden {{ Auth::user()->type == 1 ? 'disabled' : '' }}">
                                @foreach (helper::customstauts($getorderdata->vendor_id, $getorderdata->order_type) as $status)
                                    <a class="dropdown-item w-auto cursor-pointer p-2 @if ($getorderdata->status == $status->id) fw-600 @endif"
                                        onclick="statusupdate('{{ URL::to('admin/orders/update-' . $getorderdata->id . '-' . $status->id . '-' . $status->type) }}')">{{ $status->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        @endif
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row justify-content-between g-3">
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="card border-0 mb-3 h-100 d-flex box-shadow">
                        <div
                            class="card-header border-bottom d-flex align-items-center bg-transparent text-dark py-3 justify-content-between">
                            <h6 class="px-2 fw-500 text-dark color-changer"><i class="fa-solid fa-clipboard fs-5"></i>
                                {{ trans('labels.order_details') }}</h6>
                        </div>
                        <div class="card-body">

                            <div class="basic-list-group">
                                <ul class="list-group list-group-flush">
                                    <li
                                        class="list-group-item px-0 fs-7 fw-500 d-flex justify-content-between align-items-center">
                                        <p class="color-changer">{{ trans('labels.order_number') }}</p>
                                        <p class="text-dark color-changer fw-600">{{ $getorderdata->order_number }}</p>
                                    </li>
                                    <li class="list-group-item px-0 fs-7 fw-500 d-flex justify-content-between">
                                        <p class="color-changer">{{ trans('labels.order_date') }}</p>
                                        <p class="text-muted">
                                            {{ helper::date_format($getorderdata->created_at, $vendor_id) }}</p>
                                    </li>
                                    @if (helper::appdata($vendor_id)->ordertype_date_time == 1)
                                        @if ($getorderdata->order_from != 'pos' && $getorderdata->order_type != 3)
                                            @if ($getorderdata->delivery_date != '' && $getorderdata->delivery_date != null)
                                                <li class="list-group-item px-0 fs-7 fw-500 d-flex justify-content-between">
                                                    <p class="color-changer">
                                                        {{ $getorderdata->order_type == 1 ? trans('labels.delivery_date') : trans('labels.pickup_date') }}
                                                    </p>
                                                    <p class="text-muted">
                                                        {{ helper::date_format($getorderdata->delivery_date, $vendor_id) }}
                                                    </p>
                                                </li>
                                            @endif
                                            @if ($getorderdata->delivery_time != '' && $getorderdata->delivery_time != null)
                                                <li class="list-group-item px-0 fs-7 fw-500 d-flex justify-content-between">
                                                    <p class="color-changer">
                                                        {{ $getorderdata->order_type == 1 ? trans('labels.delivery_time') : trans('labels.pickup_time') }}
                                                    </p>
                                                    <p class="text-muted">{{ $getorderdata->delivery_time }}</p>
                                                </li>
                                            @endif
                                        @endif
                                    @endif

                                    {{-- payment_type = COD : 1,RazorPay : 2, Stripe : 3, Flutterwave : 4, Paystack : 5, Mercado Pago : 7, PayPal : 8, MyFatoorah : 9, toyyibpay : 10, phonepe : 11 --}}
                                    <li class="list-group-item px-0 fs-7 fw-500 d-flex justify-content-between">
                                        <p class="color-changer">{{ trans('labels.payment_type') }}</p>
                                        <span class="text-muted">
                                            @if ($getorderdata->payment_type == 0)
                                                {{ trans('labels.online') }}
                                            @elseif ($getorderdata->payment_type == 6)
                                                {{ @helper::getpayment($getorderdata->payment_type, $getorderdata->vendor_id)->payment_name }}
                                                : <small><a href="{{ helper::image_path($getorderdata->screenshot) }}"
                                                        target="_blank"
                                                        class="text-danger">{{ trans('labels.click_here') }}</a></small>
                                            @else
                                                {{ @helper::getpayment($getorderdata->payment_type, $getorderdata->vendor_id)->payment_name }}
                                            @endif
                                        </span>
                                    </li>
                                    @if (@helper::checkaddons('vendor_tip'))
                                        @if (@helper::otherappdata($getorderdata->vendor_id)->tips_settings == 1)
                                            <li class="list-group-item px-0 fs-7 fw-500 d-flex justify-content-between">
                                                <p class="color-changer">{{ trans('labels.tips_pro') }}</p>
                                                <p class="text-muted">
                                                    {{ helper::currency_formate($getorderdata->tips, $getorderdata->vendor_id) }}
                                                </p>
                                            </li>
                                        @endif
                                    @endif
                                    @if (in_array($getorderdata->payment_type, [2, 3, 4, 5, 7, 8, 9, 10, 11, 12, 13, 14, 15]))
                                        <li class="list-group-item px-0 fs-7 fw-500">
                                            <p class="color-changer">{{ trans('labels.payment_id') }}</p>
                                            <p class="text-muted">
                                                {{ $getorderdata->payment_id }}
                                            </p>
                                        </li>
                                    @endif
                                    @if ($getorderdata->order_notes != '')
                                        <li class="list-group-item px-0 fs-7 fw-500">
                                            <p class="color-changer">{{ trans('labels.notes') }}</p>
                                            <p class="text-muted">
                                                {{ $getorderdata->order_notes }}
                                            </p>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="card border-0 mb-3 h-100 d-flex box-shadow">
                        <div
                            class="card-header border-bottom d-flex align-items-center bg-transparent text-dark py-3 justify-content-between">
                            <h6 class="px-2 fw-500 text-dark color-changer"><i class="fa-solid fa-user fs-5"></i>
                                {{ trans('labels.customer') }}</h6>
                            <p class="text-muted cursor-pointer "
                                onclick="editcustomerdata('{{ $getorderdata->order_number }}','{{ $getorderdata->customer_name }}','{{ $getorderdata->mobile }}','{{ $getorderdata->customer_email }}','{{ str_replace(',', '|', $getorderdata->address) }}','{{ str_replace(',', '|', $getorderdata->building) }}','{{ str_replace(',', '|', $getorderdata->landmark) }}','{{ $getorderdata->pincode }}','customer_info')">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </p>
                        </div>
                        <div class="card-body">
                            <div class="basic-list-group">
                                <div class="row">
                                    <div class="basic-list-group">
                                        <ul class="list-group list-group-flush">

                                            <li
                                                class="list-group-item px-0 fs-7 fw-500 d-flex justify-content-between align-items-center">
                                                <p class="color-changer">{{ trans('labels.name') }}</p>
                                                <p class="text-muted"> {{ $getorderdata->customer_name }}</p>
                                            </li>

                                            @if ($getorderdata->mobile != null)
                                                <li class="list-group-item px-0 fs-7 fw-500 d-flex justify-content-between">
                                                    <p class="color-changer">{{ trans('labels.mobile') }}</p>
                                                    <p class="text-muted">{{ $getorderdata->mobile }}</p>
                                                </li>
                                            @endif

                                            @if ($getorderdata->customer_email != null)
                                                <li class="list-group-item px-0 fs-7 fw-500 d-flex justify-content-between">
                                                    <p class="color-changer">{{ trans('labels.email') }}</p>
                                                    <p class="text-muted">{{ $getorderdata->customer_email }}</p>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="card border-0 mb-3 h-100 d-flex box-shadow">

                        <div
                            class="card-header border-bottom d-flex align-items-center bg-transparent text-dark py-3 justify-content-between">
                            <h6 class="px-2 fw-500 text-dark color-changer"><i class="fa-solid fa-file-invoice fs-5"></i>
                                @if ($getorderdata->order_type == 3 or $getorderdata->order_type == 4 or $getorderdata->order_type == 5)
                                    {{ trans('labels.info') }}
                                @else
                                    {{ trans('labels.bill_to') }}
                                @endif
                            </h6>
                            @if ($getorderdata->order_type == 1)
                                <p class="text-muted cursor-pointer"
                                    onclick="editcustomerdata('{{ $getorderdata->order_number }}','{{ $getorderdata->customer_name }}','{{ $getorderdata->mobile }}','{{ $getorderdata->customer_email }}','{{ str_replace(',', '|', $getorderdata->address) }}','{{ str_replace(',', '|', $getorderdata->building) }}','{{ str_replace(',', '|', $getorderdata->landmark) }}','{{ $getorderdata->pincode }}','delivery_info')">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </p>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="basic-list-group">
                                <div class="row">
                                    @if ($getorderdata->order_type == 1)
                                        <div class="col-md-12 mb-2">
                                            <div class="basic-list-group">
                                                <ul class="list-group list-group-flush">
                                                    @if ($getorderdata->order_from == 'pos')
                                                        <li
                                                            class="list-group-item px-0 fs-7 fw-500 d-flex justify-content-between align-items-center">
                                                            <p class="color-changer">{{ trans('labels.pos') }}</p>
                                                            <p class="text-muted"> {{ trans('labels.dine_in') }}</p>
                                                        </li>
                                                    @else
                                                        <li
                                                            class="list-group-item px-0 fs-7 fw-500 d-flex justify-content-between align-items-center">
                                                            <p class="color-changer">{{ trans('labels.address') }}</p>
                                                            <p class="text-muted"> {{ $getorderdata->address }}</p>
                                                        </li>
                                                        <li
                                                            class="list-group-item px-0 fs-7 fw-500 d-flex justify-content-between">
                                                            <p class="color-changer">{{ trans('labels.building') }}</p>
                                                            <p class="text-muted">{{ $getorderdata->building }}</p>
                                                        </li>
                                                        <li
                                                            class="list-group-item px-0 fs-7 fw-500 d-flex justify-content-between">
                                                            <p class="color-changer">{{ trans('labels.landmark') }}</p>
                                                            <p class="text-muted">{{ $getorderdata->landmark }}</p>
                                                        </li>
                                                        <li
                                                            class="list-group-item px-0 fs-7 fw-500 d-flex justify-content-between">
                                                            <p class="color-changer">{{ trans('labels.pincode') }}</p>
                                                            <p class="text-muted"> {{ $getorderdata->pincode }}.</p>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    @elseif ($getorderdata->order_type == 2)
                                        <div class="col-md-12 mb-2">
                                            <div class="basic-list-group">
                                                <ul class="list-group list-group-flush">
                                                    @if ($getorderdata->order_from == 'pos')
                                                        <li
                                                            class="list-group-item px-0 fs-7 fw-500 d-flex justify-content-between align-items-center">
                                                            <p class="color-changer">{{ trans('labels.order_type') }}</p>
                                                            <p class="text-muted"> {{ trans('labels.takeaway') }}</p>
                                                        </li>
                                                    @else
                                                        <li
                                                            class="list-group-item px-0 fs-7 fw-500 d-flex justify-content-between align-items-center">
                                                            <p class="color-changer">{{ trans('labels.order_type') }}</p>
                                                            <p class="text-muted"> {{ trans('labels.pickup') }}</p>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    @elseif ($getorderdata->order_type == 3)
                                        <div class="col-md-12 mb-2">
                                            <div class="basic-list-group">
                                                <ul class="list-group list-group-flush">
                                                    <li
                                                        class="list-group-item px-0 fs-7 fw-500 d-flex justify-content-between align-items-center">
                                                        <p class="color-changer">{{ trans('labels.table') }}</p>
                                                        <p class="text-muted">
                                                            ({{ $getorderdata->dinein_tablename != '' ? $getorderdata->dinein_tablename : '-' }})
                                                        </p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    @elseif ($getorderdata->order_type == 4)
                                        <div class="col-md-12 mb-2">
                                            <div class="basic-list-group">
                                                <ul class="list-group list-group-flush">
                                                    <li
                                                        class="list-group-item px-0 fs-7 fw-500 d-flex justify-content-between align-items-center">
                                                        <p class="color-changer">{{ trans('labels.pos') }}</p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    @elseif ($getorderdata->order_type == 5)
                                        <div class="col-md-12 mb-2">
                                            <div class="basic-list-group">
                                                <ul class="list-group list-group-flush">
                                                    <li
                                                        class="list-group-item px-0 fs-7 fw-500 d-flex justify-content-between align-items-center">
                                                        <p class="color-changer">{{ trans('labels.digital') }}</p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="card border-0 mb-3 h-100 d-flex box-shadow">
                        <div
                            class="card-header border-bottom d-flex align-items-center bg-transparent text-dark py-3 justify-content-between">
                            <h6 class="px-2 fw-500 text-dark color-changer"><i class="fa-solid fa-clipboard fs-5"></i>
                                {{ trans('labels.notes') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="basic-list-group">
                                <div class="row">
                                    <div class="basic-list-group">
                                        @if ($getorderdata->vendor_note != '')
                                            <div class="alert alert-info" role="alert">
                                                {{ $getorderdata->vendor_note }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top">
                            <form action="{{ URL::to('admin/orders/vendor_note') }}" method="POST">
                                @csrf
                                <div class="form-group col-md-12">
                                    <label for="note" class="form-label"> {{ trans('labels.note') }} </label>
                                    <div class="controls">
                                        <input type="hidden" name="order_id" class="form-control"
                                            value="{{ $getorderdata->order_number }}">
                                        <input type="text" name="vendor_note" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group text-end">
                                    <button
                                        @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" type="submit" @endif
                                        class="btn btn-primary"> {{ trans('labels.update') }} </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 mt-3 box-shadow">
                <div
                    class="card-header border-bottom d-flex align-items-center justify-content-between bg-transparent text-dark py-3">
                    <h6 class="px-2 fw-500 color-changer text-dark">
                        <i class="fa-solid fa-bag-shopping fs-5"></i>
                        {{ trans('labels.orders') }}
                    </h6>
                    <a href="{{ URL::to('admin/orders/print/' . $getorderdata->order_number) }}" target="_blank"
                        class="btn btn-secondary px-sm-4 {{ Auth::user()->type == 1 ? 'disabled' : '' }} {{ Auth::user()->type == 4 ? (helper::check_access('role_orders', Auth::user()->role_id, $vendor_id, 'manage') == 1 ? '' : 'd-none') : '' }}">
                        {{ trans('labels.print') }}
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr class="text-capitalize fs-15 fw-500">
                                    <td>{{ trans('labels.products') }}</td>
                                    <td class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                        {{ trans('labels.unit_cost') }}</td>
                                    <td class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                        {{ trans('labels.qty') }}</td>
                                    <td class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                        {{ trans('labels.sub_total') }}</td>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($ordersdetails as $orders)
                                    <tr class="align-middle fs-7 fw-500">
                                        <td>{{ $orders->item_name }}
                                            @if ($orders->variants_id != '')
                                                - <small>{{ $orders->variants_name }}
                                                    ({{ helper::currency_formate($orders->variants_price, $getorderdata->vendor_id) }})
                                                </small>
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
                                        <td class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                            {{ helper::currency_formate((float) $orders->variants_price + (float) $extras_total_price, $getorderdata->vendor_id) }}
                                        </td>
                                        <td class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                            {{ $orders->qty }}</td>
                                        @php
                                            $total =
                                                ((float) $orders->variants_price + (float) $extras_total_price) *
                                                (float) $orders->qty;
                                        @endphp
                                        <td class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                            {{ helper::currency_formate($total, $getorderdata->vendor_id) }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }} fs-15 fw-500 p-2"
                                        colspan="3">
                                        {{ trans('labels.sub_total') }}
                                    </td>
                                    <td
                                        class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }} fs-16 fw-500 p-2">
                                        {{ helper::currency_formate($getorderdata->sub_total, $getorderdata->vendor_id) }}
                                    </td>
                                </tr>
                                @if ($getorderdata->discount_amount > 0)
                                    <tr>
                                        <td class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }} fs-15 fw-500 p-2"
                                            colspan="3">
                                            {{ trans('labels.discount') }}{{ $getorderdata->couponcode != '' ? '(' . $getorderdata->couponcode . ')' : '' }}
                                        </td>
                                        <td
                                            class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }} fs-16 fw-500 p-2">
                                            -{{ helper::currency_formate($getorderdata->discount_amount, $getorderdata->vendor_id) }}
                                        </td>
                                    </tr>
                                @endif

                                @php
                                    $tax = explode('|', $getorderdata->tax);
                                    $tax_name = explode('|', $getorderdata->tax_name);
                                @endphp
                                @if ($getorderdata->tax != null && $getorderdata->tax != '')
                                    @foreach ($tax as $key => $tax_value)
                                        <tr>
                                            <td class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }} fs-15 fw-500 p-2"
                                                colspan="3">
                                                {{ $tax_name[$key] }}
                                            </td>
                                            <td
                                                class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }} fs-16 fw-500 p-2">
                                                {{ helper::currency_formate((float) $tax[$key], $getorderdata->vendor_id) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($getorderdata->order_type == 1)
                                    <tr>
                                        <td class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }} fs-15 fw-500 p-2"
                                            colspan="3">
                                            {{ trans('labels.delivery') }}
                                            @if ($getorderdata->delivery_area != '')
                                                ({{ $getorderdata->delivery_area }})
                                            @endif
                                        </td>
                                        <td
                                            class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }} fs-16 fw-500 p-2">
                                            @if ($getorderdata->delivery_charge > 0)
                                                {{ helper::currency_formate($getorderdata->delivery_charge, $getorderdata->vendor_id) }}
                                            @else
                                                {{ trans('labels.free') }}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }} fs-16 fw-600 p-2 text-success"
                                        colspan="3">
                                        {{ trans('labels.total') }} {{ trans('labels.amount') }}
                                    </td>
                                    <td
                                        class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }} fs-16 fw-600 p-2 text-success">
                                        {{ helper::currency_formate($getorderdata->grand_total, $getorderdata->vendor_id) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="customerinfo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content rounded">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title color-changer" id="modalbankdetailsLabel">{{ trans('labels.edit') }}</h5>
                    <button type="button" class="bg-transparent border-0" data-bs-dismiss="modal">
                        <i class="fa-regular fa-xmark fs-4 color-changer"></i>
                    </button>
                </div>
                <form enctype="multipart/form-data" action="{{ URL::to('admin/orders/customerinfo') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="order_id" id="modal_order_id" class="form-control" value="">
                        <input type="hidden" name="edit_type" id="edit_type" class="form-control" value="">
                        <div id="customer_info">
                            <div class="form-group col-md-12">
                                <label class="form-label" for="customer_name"> {{ trans('labels.customer_name') }}
                                </label>
                                <div class="controls">
                                    <input type="text" name="customer_name" id="customer_name" class="form-control"
                                        required>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label" for="customer_mobile"> {{ trans('labels.mobile') }} </label>
                                <div class="controls">
                                    <input type="text" name="customer_mobile" id="customer_mobile"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label" for="customer_email"> {{ trans('labels.email') }} </label>
                                <div class="controls">
                                    <input type="text" name="customer_email" id="customer_email" class="form-control"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div id="delivery_info">
                            <div class="form-group col-md-12">
                                <label class="form-label" for="customer_address"> {{ trans('labels.address') }} </label>
                                <div class="controls">
                                    <input type="text" name="customer_address" id="customer_address"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label" for="customer_building"> {{ trans('labels.building') }}
                                </label>
                                <div class="controls">
                                    <input type="text" name="customer_building" id="customer_building"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label" for="customer_landmark"> {{ trans('labels.landmark') }}
                                </label>
                                <div class="controls">
                                    <input type="text" name="customer_landmark" id="customer_landmark"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label" for="customer_pincode"> {{ trans('labels.pincode') }} </label>
                                <div class="controls">
                                    <input type="text" name="customer_pincode" id="customer_pincode"
                                        class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger px-sm-4"
                            data-bs-dismiss="modal">{{ trans('labels.close') }}</button>
                        <button @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" type="submit" @endif
                            class="btn btn-primary px-sm-4"> {{ trans('labels.save') }} </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
