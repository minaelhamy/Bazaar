@extends('admin.layout.default')
@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="text-capitalize fw-600 color-changer text-dark fs-4">{{ trans('labels.top_deals') }}</h5>
    </div>
    <div class="row">
        <div id="top_deals">
            <div class="row my-4">
                <div class="col-12">
                    <div class="col-12">
                        <div class="card border-0 box-shadow">
                            <div class="card-body pb-0">

                                <form action="{{ URL::to('admin/top_deals/update') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label class="form-label">{{ trans('labels.deals_type') }} <span
                                                    class="text-danger"> * </span></label>

                                            <select name="deal_type" class="form-select" id="deal_type" required>
                                                <option value="">{{ trans('labels.select') }}</option>
                                                <option value="1" {{ @$topdeals->deal_type == 1 ? 'selected' : '' }}>
                                                    {{ trans('labels.one_time') }}</option>
                                                <option value="2" {{ @$topdeals->deal_type == 2 ? 'selected' : '' }}>
                                                    {{ trans('labels.daily') }}</option>
                                            </select>

                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label class="form-label" for="">{{ trans('labels.top_deals') }}
                                            </label>
                                            <input id="top_deals_switch" type="checkbox" class="checkbox-switch"
                                                name="top_deals_switch" value="1"
                                                {{ @$topdeals->top_deals_switch == 1 ? 'checked' : '' }}>
                                            <label for="top_deals_switch" class="switch">
                                                <span
                                                    class="{{ session()->get('direction') == 2 ? 'switch__circle-rtl' : 'switch__circle' }}"><span
                                                        class="switch__circle-inner"></span></span>
                                                <span
                                                    class="switch__left  {{ session()->get('direction') == 2 ? 'pe-2' : 'ps-2' }}">{{ trans('labels.off') }}</span>
                                                <span
                                                    class="switch__right {{ session()->get('direction') == 2 ? 'ps-2' : 'pe-2' }}">{{ trans('labels.on') }}</span>
                                            </label>
                                        </div>

                                        <div class="col-sm-6 form-group d-none" id="start_date">
                                            <label class="form-label">{{ trans('labels.start_date') }}
                                                <span class="text-danger"> * </span></label>
                                            <input type="date" class="form-control" id="top_deals_start_date"
                                                name="top_deals_start_date" value="{{ @$topdeals->start_date }}">
                                        </div>
                                        <div class="col-sm-6 form-group d-none" id="end_date">
                                            <label class="form-label">{{ trans('labels.end_date') }}
                                                <span class="text-danger"> * </span></label>
                                            <input type="date" class="form-control" id="top_deals_end_date"
                                                name="top_deals_end_date" value="{{ @$topdeals->end_date }}">
                                        </div>


                                        <div class="col-sm-6 form-group">
                                            <label class="form-label" for="start_time">{{ trans('labels.start_time') }}
                                                <span class="text-danger"> * </span></label>
                                            <input type="time" class="form-control" name="top_deals_start_time"
                                                id="start_time" value="{{ @$topdeals->start_time }}" required>
                                        </div>

                                        <div class="col-sm-6 form-group">
                                            <label class="form-label" for="end_time">{{ trans('labels.end_time') }}
                                                <span class="text-danger"> * </span></label>
                                            <input type="time" class="form-control" name="top_deals_end_time"
                                                id="end_time" value="{{ @$topdeals->end_time }}" required>
                                        </div>


                                        <div class="col-md-6">
                                            <label class="form-label">{{ trans('labels.offer_type') }}
                                                <span class="text-danger"> * </span></label>
                                            <select class="form-select" name="offer_type" required>
                                                <option value="">{{ trans('labels.select') }}</option>
                                                <option value="1"
                                                    {{ @$topdeals->offer_type == '1' ? 'selected' : '' }}>
                                                    {{ trans('labels.fixed') }}
                                                </option>
                                                <option value="2"
                                                    {{ @$topdeals->offer_type == '2' ? 'selected' : '' }}>
                                                    {{ trans('labels.percentage') }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label">{{ trans('labels.discount') }}<span
                                                    class="text-danger"> *
                                                </span></label>
                                            <input type="text" class="form-control numbers_only" name="amount"
                                                value="{{ @$topdeals->offer_amount }}"
                                                placeholder="{{ trans('labels.discount') }}" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label">{{ trans('labels.products') }}</label>
                                            <select class="form-control selectpicker" name="products[]" multiple
                                                data-live-search="true">

                                                @if (!empty($getItem))
                                                    @foreach ($getItem as $products)
                                                        <option value="{{ $products->id }}">
                                                            {{ $products->item_name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label">{{ trans('labels.description') }}<span
                                                    class="text-danger"> *
                                                </span></label>
                                            <input type="text" class="form-control " name="description"
                                                value="{{ @$topdeals->description }}"
                                                placeholder="{{ trans('labels.description') }}" required>
                                        </div>


                                        <div class="form-group {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                            <button
                                                class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_top_deals', Auth::user()->role_id, $vendor_id, 'add') == 1 ? '' : 'd-none') : '' }}"
                                                @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif>{{ trans('labels.save') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <div class="text-capitalize fw-600 text-dark color-changer fs-4"></div>
            <div class="d-flex align-items-center" >
                <!-- Bulk Delete Button -->
                @if (@helper::checkaddons('bulk_delete'))
                <button id="bulkDeleteBtn"
                    @if (env('Environment')=='sendbox' ) onclick="myFunction()" @else onclick="deleteSelected('{{ URL::to('admin/top_deals/bulk_delete') }}')" @endif class="btn btn-danger hov btn-sm d-none d-flex " tooltip="{{ trans('labels.delete') }}" style="margin-right: 20px;">
                    <i class="fa-regular fa-trash"></i>
                </button>
                @endif
            </div>
        </div>
        <div class="col-12">

            <div class="card border-0 my-3 box-shadow">

                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-striped table-bordered py-3 zero-configuration w-100">

                            <thead>

                                <tr class=" text-capitalize fs-15 fw-500">
                                    @if (@helper::checkaddons('bulk_delete'))
                                        @if($productlist->count() > 0)
                                            <td> <input type="checkbox" id="selectAll" class="form-check-input checkbox-style"></td>
                                        @endif
                                    @endif
                                    <td>{{ trans('labels.srno') }}</td>
                                    <td>{{ trans('labels.products') }}</td>
                                    <td>{{ trans('labels.created_date') }}</td>
                                    <td>{{ trans('labels.updated_date') }}</td>
                                    <td>{{ trans('labels.action') }}</td>
                                </tr>

                            </thead>

                            <tbody>

                                @php $i = 1; @endphp

                                @foreach (@$productlist as $product)
                                    <tr class="fs-7  align-middle">
                                        @if (@helper::checkaddons('bulk_delete'))
                                            <td><input type="checkbox" class="row-checkbox form-check-input checkbox-style" value="{{ $product->id }}"></td>
                                        @endif
                                        <td>@php echo $i++; @endphp</td>

                                        <td>{{ $product->item_name }}</td>
                                        <td>{{ helper::date_format($product->created_at, $product->vendor_id) }}<br>
                                            {{ helper::time_format($product->created_at, $product->vendor_id) }}
                                        </td>
                                        <td>{{ helper::date_format($product->updated_at, $product->vendor_id) }}<br>
                                            {{ helper::time_format($product->updated_at, $product->vendor_id) }}
                                        </td>

                                        <td>
                                            <a href="javascript:void(0)" tooltip="{{ trans('labels.delete') }}"
                                                @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="deletedata('{{ URL::to('admin/top_deals/delete-' . $product->id) }}')" @endif
                                                class="btn btn-danger hov btn-sm {{ Auth::user()->type == 4 ? (helper::check_access('role_top_deals', Auth::user()->role_id, $vendor_id, 'delete') == 1 ? '' : 'd-none') : '' }}">
                                                <i class="fa-regular fa-trash"></i></a>
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
    @section('scripts')
        <script>
            $('#deal_type').on('change', function() {
                if ($('#deal_type').val() == 1) {
                    $('#start_date').removeClass('d-none');
                    $('#end_date').removeClass('d-none');
                    $('#top_deals_start_date').prop('required', true);
                    $('#top_deals_end_date').prop('required', true);
                } else {
                    $('#start_date').addClass('d-none');
                    $('#end_date').addClass('d-none');
                    $('#top_deals_start_date').prop('required', false);
                    $('#top_deals_end_date').prop('required', false);
                }
            }).change()
        </script>
    @endsection
