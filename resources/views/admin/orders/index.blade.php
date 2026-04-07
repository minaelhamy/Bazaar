@extends('admin.layout.default')
@section('content')
    @php
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
    @endphp
    <div class="d-flex justify-content-between mb-3">
        <h5 class="text-capitalize align-items-center text-dark color-changer fs-4">
            {{ request()->is('admin/report*') ? trans('labels.report') : trans('labels.orders') }}</h5>
        @if (request()->is('admin/report*'))
            <form action="{{ URL::to('/admin/report') }}" class="mb-">
                <div class="input-group col-md-12 ps-0 justify-content-end gap-2">
                    @if ($getcustomerslist->count() > 0)
                        <div
                            class="input-group-append col-auto px-1 {{ Auth::user()->type == 4 ? (helper::check_menu(Auth::user()->role_id, 'role_customers') == 1 ? '' : 'd-none') : '' }}">
                            <select name="customer_id" class="form-select rounded">
                                <option value="">{{ trans('labels.select_customer') }}</option>
                                @foreach ($getcustomerslist as $getcustomer)
                                    <option value="{{ $getcustomer->id }}"
                                        {{ $getcustomer->id == @$_GET['customer_id'] ? 'selected' : '' }}>
                                        {{ $getcustomer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="input-group-append col-auto">
                        <input type="date" class="form-control rounded p-2" name="startdate"
                            @isset($_GET['startdate'])
                             value="{{ $_GET['startdate'] }}" @endisset
                            required>
                    </div>
                    <div class="input-group-append col-auto">
                        <input type="date" class="form-control rounded p-2" name="enddate"
                            @isset($_GET['enddate'])
                             value="{{ $_GET['enddate'] }}" @endisset
                            required>
                    </div>
                    <div class="input-group-append">
                        <button class="btn btn-primary rounded fs-15 fw-500"
                            type="submit">{{ trans('labels.fetch') }}</button>
                    </div>
                </div>
            </form>
        @endif
    </div>

    @include('admin.orders.statistics')
    <div class="row">
        <div class="col-12">
            <div class="card border-0 my-3 box-shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        @include('admin.orders.orderstable')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title color-changer" id="paymentModalLabel">{{ trans('labels.record_payment') }}</h5>
                    <button type="button" class="bg-transparent border-0" data-bs-dismiss="modal">
                        <i class="fa-regular fa-xmark color-changer fs-4"></i>
                    </button>
                </div>
                <form action=" {{ URL::to('admin/orders/payment_status-' . '2') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div>
                            <input type="hidden" id="booking_number" name="booking_number" value="">
                            <div class="form-group">
                                <label for="modal_total_amount" class="form-label">
                                    {{ trans('labels.total') }} {{ trans('labels.amount') }}
                                </label>
                                <input type="text" class="form-control numbers_only" name="modal_total_amount"
                                    id="modal_total_amount" disabled value="">
                            </div>
                            <div class="form-group">
                                <label for="modal_amount" class="form-label">
                                    {{ trans('labels.cash_received') }}
                                </label>
                                <input type="text" class="form-control numbers_only" name="modal_amount"
                                    id="modal_amount" value="" onkeyup="validation($(this).val())">
                            </div>
                            <div class="form-group">
                                <label for="modal_amount" class="form-label">
                                    {{ trans('labels.change_amount') }}
                                </label>
                                <input type="number" class="form-control" name="ramin_amount" id="ramin_amount"
                                    value="" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-secondary px-sm-4">{{ trans('labels.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function codpayment(booking_number, grand_total) {
            $('#modal_total_amount').val(grand_total);
            $('#booking_number').val(booking_number);
            $('#paymentModal').modal('show');
        }

        function validation(value) {
            var remaining = $('#modal_total_amount').val() - value;
            $('#ramin_amount').val(remaining.toFixed(2));
        }
    </script>
@endsection
