<table class="table table-striped table-bordered py-3 zero-configuration w-100">
    <thead>
        <tr class="text-capitalize fw-500 fs-15">
            <td>{{ trans('labels.srno') }}</td>
            <td>{{ trans('labels.transaction_number') }}</td>
            <td>{{ trans('labels.plan_name') }}</td>
            <td>{{ trans('labels.total') }} {{ trans('labels.amount') }}</td>
            <td>{{ trans('labels.payment_type') }}</td>
            <td>{{ trans('labels.purchase_date') }}</td>
            <td>{{ trans('labels.expire_date') }}</td>
            <td>{{ trans('labels.status') }}</td>
            <td>{{ trans('labels.created_date') }}</td>
            <td>{{ trans('labels.updated_date') }}</td>
            <td>{{ trans('labels.action') }}</td>
        </tr>
    </thead>
    <tbody>
        @php
            if (Auth::user()->type == 4) {
                $vendor_id = Auth::user()->vendor_id;
            } else {
                $vendor_id = Auth::user()->id;
            }
            $i = 1;

        @endphp
        @foreach ($transaction as $transaction)
            <tr class="fs-7 align-middle">
                <td>@php echo $i++; @endphp</td>
                <td>{{ $transaction->transaction_number }}</td>
                <td>{{ $transaction->plan_name }}</td>
                <td>{{ helper::currency_formate($transaction->grand_total, '') }}</td>
                <td>
                    @if ($transaction->payment_type != '')
                        @if ($transaction->payment_type == 0)
                            {{ trans('labels.manual') }}
                        @else
                            {{ @helper::getpayment($transaction->payment_type, 1)->payment_name }}
                        @endif
                    @elseif($transaction->amount == 0)
                        -
                    @else
                        -
                    @endif
                </td>
                <td>

                    @if ($transaction->payment_type == 6 || $transaction->payment_type == 1)
                        @if ($transaction->status == 2)
                            <span
                                class="badge bg-success">{{ helper::date_format($transaction->purchase_date, $vendor_id) }}</span>
                        @else
                            -
                        @endif
                    @else
                        <span
                            class="badge bg-success">{{ helper::date_format($transaction->purchase_date, $vendor_id) }}</span>
                    @endif
                </td>
                <td>
                    @if ($transaction->payment_type == 6 || $transaction->payment_type == 1)
                        @if ($transaction->status == 2)
                            <span
                                class="badge bg-danger">{{ $transaction->expire_date != '' ? helper::date_format($transaction->expire_date, $vendor_id) : '-' }}</span>
                        @else
                            -
                        @endif
                    @else
                        <span
                            class="badge bg-danger">{{ $transaction->expire_date != '' ? helper::date_format($transaction->expire_date, $vendor_id) : '-' }}</span>
                    @endif
                </td>
                <td>
                    @if ($transaction->payment_type == 6 || $transaction->payment_type == 1)
                        @if ($transaction->status == 1)
                            <span class="badge bg-warning">{{ trans('labels.pending') }}</span>
                        @elseif ($transaction->status == 2)
                            <span class="badge bg-success">{{ trans('labels.accepted') }}</span>
                        @elseif ($transaction->status == 3)
                            <span class="badge bg-danger">{{ trans('labels.rejected') }}</span>
                        @else
                            -
                        @endif
                    @else
                        -
                    @endif
                </td>
                <td>{{ helper::date_format($transaction->created_at, $vendor_id) }}<br>
                    {{ helper::time_format($transaction->created_at, $vendor_id) }}
                </td>
                <td>{{ helper::date_format($transaction->updated_at, $vendor_id) }}<br>
                    {{ helper::time_format($transaction->updated_at, $vendor_id) }}
                </td>
                <td>
                    <div>
                        <a class="btn btn-sm btn-secondary hov" tooltip="{{ trans('labels.view') }}"
                            href="{{ URL::to('admin/transaction/plandetails-' . $transaction->id) }}"><i
                                class="fa-regular fa-eye"></i></a>
                    </div>
                </td>

            </tr>
        @endforeach
    </tbody>
</table>
