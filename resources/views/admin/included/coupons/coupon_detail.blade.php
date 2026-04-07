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

        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">

            {{ trans('labels.coupon_details') }}

        </h5>

        <nav aria-label="breadcrumb">

            <ol class="breadcrumb m-0">

                <li class="breadcrumb-item text-dark"><a
                        href="{{ URL::to('admin/coupons') }}" class="color-changer">{{ trans('labels.coupons') }}</a>

                </li>

                <li class="breadcrumb-item active {{ session()->get('direction') == 2 ? 'breadcrumb-rtl' : '' }}"
                    aria-current="page">{{ trans('labels.coupon_details') }}</li>

            </ol>

        </nav>

    </div>
    <div class="row">
        <div class="col-12 mb-lg-0">
            <div class="card border-0 box-shadow">
                <div class="card-body">
                    <table class="table table-striped table-bordered py-3 zero-configuration w-100">
                        <thead>

                            <tr class="text-capitalize fw-500 fs-15">

                                <td>{{ trans('labels.srno') }}</td>

                                @if (Auth::user()->type == 1)
                                    <td>{{ trans('labels.transaction_number') }}</td>
                                @else
                                    <td>{{ trans('labels.order_number') }}</td>
                                @endif

                                <td>{{ trans('labels.discount_amount') }}</td>

                                <td>{{ trans('labels.date') }}</td>



                            </tr>

                        </thead>
                        <tbody>

                            @php $i = 1; @endphp

                            @foreach ($coupons as $coupon)
                                <tr class="fs-7">

                                    <td>@php echo $i++; @endphp</td>

                                    @if (Auth::user()->type == 1)
                                        <td>{{ $coupon->transaction_number }}</td>

                                        <td>{{ helper::currency_formate($coupon->offer_amount, $coupon->vendor_id) }}</td>
                                    @else
                                        <td>{{ $coupon->order_number }}</td>

                                        <td>{{ helper::currency_formate($coupon->discount_amount, $coupon->vendor_id) }}
                                        </td>
                                    @endif

                                    <td>{{ helper::date_format($coupon->created_at, $vendor_id) }}<br>
                                        {{ helper::time_format($coupon->created_at, $vendor_id) }}

                                    </td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
