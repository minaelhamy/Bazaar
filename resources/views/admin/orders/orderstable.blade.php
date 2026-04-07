<table class="table table-striped table-bordered py-3 zero-configuration w-100">
    <thead>
        <tr class="text-capitalize fw-500 fs-15">
            <td>{{ trans('labels.srno') }}</td>
            @if (
                (request()->is('admin/customers*') && Auth::user()->type == 1) ||
                    (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                <td>{{ trans('labels.vendor_title') }}</td>
            @endif
            <td>{{ trans('labels.order_number') }}</td>
            <td>{{ trans('labels.total') }} {{ trans('labels.amount') }}</td>
            <td>{{ trans('labels.payment_type') }}</td>
            <td>{{ trans('labels.order_type') }}</td>
            <td>{{ trans('labels.status') }}</td>
            <td>{{ trans('labels.created_date') }}</td>
            <td>{{ trans('labels.updated_date') }}</td>
            <td>{{ trans('labels.action') }}</td>
        </tr>
    </thead>
    <tbody>

        @php $i = 1; @endphp
        @foreach ($getorders as $orderdata)
            <tr id="dataid{{ $orderdata->id }}" class="fs-7 align-middle">
                <td>@php echo $i++; @endphp</td>
                @if (
                    (request()->is('admin/customers*') && Auth::user()->type == 1) ||
                        (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                    <td>{{ $orderdata['vendorinfo']->name }}</td>
                @endif
                <td>
                    <div class="d-flex justify-content-between">
                        <a href="{{ URL::to('admin/orders/invoice/' . $orderdata->order_number) }}" class="td_a">
                            {{ $orderdata->order_number }} </a>
                        @if ($orderdata->vendor_note != '')
                            <a href="javascript:void(0)" class="btn btn-outline-primary btn-sm"
                                tooltip="{{ $orderdata->vendor_note }}">
                                <i class="fa-solid fa-clipboard"></i>
                            </a>
                        @endif
                    </div>
                </td>
                <td>
                    {{ $orderdata->grand_total == 'NaN' || $orderdata->grand_total == '' ? '-' : helper::currency_formate($orderdata->grand_total, $orderdata->vendor_id) }}
                </td>
                <td>
                    @if ($orderdata->payment_type == 0)
                        {{ trans('labels.online') }} </br>
                    @else
                        {{ @helper::getpayment($orderdata->payment_type, $vendor_id)->payment_name }} </br>
                    @endif
                    @if ($orderdata->payment_status == 1)
                        <small class="text-danger"><i class="far fa-clock"></i>
                            {{ trans('labels.unpaid') }}</small>
                    @else
                        <small class="text-success"><i class="far fa-clock"></i>
                            {{ trans('labels.paid') }}</small>
                    @endif
                </td>
                <td>
                    @if ($orderdata->order_type == 1)
                        {{ trans('labels.delivery') }}
                    @elseif ($orderdata->order_type == 2)
                        {{ trans('labels.pickup') }}
                    @elseif ($orderdata->order_type == 3)
                        {{ trans('labels.table') }}
                        ({{ $orderdata->dinein_tablename != '' ? $orderdata->dinein_tablename : '-' }})
                    @elseif ($orderdata->order_type == 4)
                        {{ trans('labels.pos') }}
                    @elseif ($orderdata->order_type == 5)
                        {{ trans('labels.digital') }}
                    @endif
                </td>

                @if (helper::appdata($vendor_id)->product_type == 1)
                    <td>
                        @if ($orderdata->status_type == '1')
                            <span
                                class="badge bg-warning">{{ @helper::gettype($orderdata->status, $orderdata->status_type, $orderdata->order_type, $vendor_id)->name == null ? '-' : @helper::gettype($orderdata->status, $orderdata->status_type, $orderdata->order_type, $vendor_id)->name }}</span>
                        @elseif($orderdata->status_type == '2')
                            <span
                                class="badge bg-info">{{ @helper::gettype($orderdata->status, $orderdata->status_type, $orderdata->order_type, $vendor_id)->name == null ? '-' : @helper::gettype($orderdata->status, $orderdata->status_type, $orderdata->order_type, $vendor_id)->name }}</span>
                        @elseif($orderdata->status_type == '3')
                            <span
                                class="badge bg-success">{{ @helper::gettype($orderdata->status, $orderdata->status_type, $orderdata->order_type, $vendor_id)->name == null ? '-' : @helper::gettype($orderdata->status, $orderdata->status_type, $orderdata->order_type, $vendor_id)->name }}</span>
                        @elseif($orderdata->status_type == '4')
                            <span
                                class="badge bg-danger">{{ @helper::gettype($orderdata->status, $orderdata->status_type, $orderdata->order_type, $vendor_id)->name == null ? '-' : @helper::gettype($orderdata->status, $orderdata->status_type, $orderdata->order_type, $vendor_id)->name }}</span>
                        @else
                            --
                        @endif
                    </td>
                @else
                    <td>
                        @if ($orderdata->status_type == '3')
                            <span class="badge bg-success">{{ trans('labels.completed') }}</span>
                        @elseif($orderdata->status_type == '4')
                            <span class="badge bg-danger">{{ trans('labels.cancelled') }}</span>
                        @else
                            --
                        @endif
                    </td>
                @endif
                <td>{{ helper::date_format($orderdata->created_at, $vendor_id) }}<br>
                    {{ helper::time_format($orderdata->created_at, $vendor_id) }}
                </td>
                <td>{{ helper::date_format($orderdata->updated_at, $vendor_id) }}<br>
                    {{ helper::time_format($orderdata->updated_at, $vendor_id) }}
                </td>
                <td>
                    <div class="d-flex flex-wrap gap-1">
                        @if (Auth::user()->type == 2 || Auth::user()->type == 4)
                            <a href="{{ URL::to('admin/orders/print/' . $orderdata->order_number) }}"
                                class="btn btn-dark btn-sm hov" tooltip="{{ trans('labels.print') }}">
                                <i class="fa-solid fa-print"></i>
                            </a>
                        @endif
                        <a class="btn btn-sm btn-secondary hov"
                            href="{{ URL::to('admin/orders/invoice/' . $orderdata->order_number) }}"
                            tooltip="{{ trans('labels.view') }}"> <i class="fa-regular fa-eye"></i> </a>
                        @if (Auth::user()->type == 2 || Auth::user()->type == 4)
                            @if (
                                ($orderdata->payment_type == 1 || $orderdata->payment_type == 6) &&
                                    $orderdata->payment_status == 1 &&
                                    $orderdata->status_type == 3 &&
                                    $orderdata->status_type != 4)
                                <a class="btn btn-sm btn-success hov"
                                    onclick="codpayment('{{ $orderdata->order_number }}','{{ $orderdata->grand_total }}')"
                                    tooltip="{{ trans('labels.payment') }}"><i
                                        class="fa-solid fa-file-invoice-dollar"></i>
                                </a>
                            @endif
                        @endif
                        <a href="{{ URL::to('/admin/orders/generatepdf/' . $orderdata->order_number) }}"
                            tooltip="{{ trans('labels.downloadpdf') }}" class="btn btn-info hov"><i
                                class="fa-solid fa-file-pdf"></i></a>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
