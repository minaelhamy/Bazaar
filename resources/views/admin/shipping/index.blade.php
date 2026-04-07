@extends('admin.layout.default')
@section('content')
    @php
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
    @endphp
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{ trans('labels.shipping_management') }}</h5>
    </div>
    <div class="row">
        <div class="col-12">
            <form action="{{ URL::to('admin/shipping/savecontent') }}" method="POST">
                @csrf
                <div class="card border-0 my-3 p-3 box-shadow">
                    <div class="row">
                        <div class="col-md-6 mb-lg-0">
                            <div class="form-group">
                                <label class="form-label">{{ trans('labels.min_order_amount_for_free_shipping') }}
                                    <span class="text-danger"> * </span></label>
                                <input type="text"
                                    class="form-control {{ session()->get('direction') == 2 ? 'input-group-rtl' : '' }}"
                                    name="min_order_amount_for_free_shipping"
                                    placeholder="{{ trans('labels.min_order_amount_for_free_shipping') }}"
                                    value="{{ $content->min_order_amount_for_free_shipping }}" required>
                            </div>
                        </div>
                        @if (@helper::checkaddons('shipping_area'))
                            <div class="col-md-6 mb-lg-0">
                                <div class="form-group">
                                    <label class="form-label" for="">{{ trans('labels.shipping_area') }}</label>
                                    @if (env('Environment') == 'sendbox')
                                        <span class="badge badge bg-danger ms-2 mb-0">{{ trans('labels.addon') }}</span>
                                    @endif
                                    <input id="shipping_area-switch" type="checkbox" class="checkbox-switch"
                                        name="shipping_area" value="1"
                                        {{ $content->shipping_area == 1 ? 'checked' : '' }}>
                                    <label for="shipping_area-switch" class="switch">
                                        <span
                                            class="{{ session()->get('direction') == 2 ? 'switch__circle-rtl' : 'switch__circle' }}"><span
                                                class="switch__circle-inner"></span></span>
                                        <span
                                            class="switch__left {{ session()->get('direction') == 2 ? 'pe-1' : 'ps-1' }}">{{ trans('labels.off') }}</span>
                                        <span
                                            class="switch__right {{ session()->get('direction') == 2 ? 'ps-2' : 'pe-2' }}">{{ trans('labels.on') }}</span>
                                    </label>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-6 mb-lg-0" id="shipping_charges_section">
                            <div class="form-group">
                                <label class="form-label">{{ trans('labels.shipping_charges') }}
                                    <span class="text-danger"> *</span></label>
                                <input type="text"
                                    class="form-control {{ session()->get('direction') == 2 ? 'input-group-rtl' : '' }}"
                                    name="shipping_charges" placeholder="{{ trans('labels.shipping_charges') }}"
                                    value="{{ $content->shipping_charges }}" id="shipping_charges" required>
                            </div>
                        </div>
                        <div class="text-end">
                            <button
                                @if (env('Environment') == 'sendbox') onclick="myFunction()" type="button" @else type="submit" @endif
                                class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_shipping_management', Auth::user()->role_id, $vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">{{ trans('labels.save') }}</button>
                        </div>
                    </div>
                </div>
            </form>

            @if (@helper::checkaddons('shipping_area'))
                @if (helper::appdata($vendor_id)->shipping_area == 1)
                    <div class="card border-0 mb-3 box-shadow">
                        <div class="d-flex justify-content-between align-items-center mx-3 mt-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <h5 class="text-capitalize text-dark color-changer fs-4 fw-600">
                                            {{ trans('labels.shipping_area') }}
                                        </h5>
                                    </li>
                                </ol>
                            </nav>
                            <div class="d-flex align-items-center" style="gap: 10px;">
                                <!-- Bulk Delete Button -->
                                @if (@helper::checkaddons('bulk_delete'))
                                    <button id="bulkDeleteBtn"
                                        @if (env('Environment')=='sendbox' ) onclick="myFunction()" @else onclick="deleteSelected('{{ URL::to('admin/shipping/bulk_delete') }}')" @endif class="btn btn-danger hov btn-sm d-none d-flex" tooltip="{{ trans('labels.delete') }}">
                                        <i class="fa-regular fa-trash"></i>
                                    </button>
                                @endif
                                <a href="{{ URL::to(request()->url() . '/add') }}"
                                    class="btn btn-secondary px-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_shipping_management', Auth::user()->role_id, Auth::user()->vendor_id, 'add') == 1 ? '' : 'd-none') : '' }}">
                                    <i class="fa-regular fa-plus mx-1"></i>{{ trans('labels.add') }}</a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table table-striped table-bordered py-3 zero-configuration w-100 dataTable no-footer">
                                    <thead>
                                        <tr class="text-capitalize fw-500 fs-15">
                                            <td></td>
                                            @if (@helper::checkaddons('bulk_delete'))
                                                @if($allshippingcontent->count() > 0)
                                                    <td> <input type="checkbox" id="selectAll" class="form-check-input checkbox-style"></td>
                                                @endif
                                            @endif
                                            <td>{{ trans('labels.srno') }}</td>
                                            <td>{{ trans('labels.area_name') }}</td>
                                            <td>{{ trans('labels.delivery_charge') }}</td>
                                            <td>{{ trans('labels.status') }}</td>
                                            <td>{{ trans('labels.created_date') }}</td>
                                            <td>{{ trans('labels.updated_date') }}</td>
                                            <td>{{ trans('labels.action') }}</td>
                                        </tr>
                                    </thead>

                                    <tbody id="tabledetails" data-url="{{ url('admin/shipping/reorder_shipping') }}">
                                        @foreach ($allshippingcontent as $key => $content)
                                            <tr class="fs-7 row1 align-middle" id="dataid{{ $content->id }}"
                                                data-id="{{ $content->id }}">
                                                <td>
                                                    <a tooltip="{{ trans('labels.move') }}">
                                                        <i class="fa-light fa-up-down-left-right mx-2"></i>
                                                    </a>
                                                </td>
                                                @if (@helper::checkaddons('bulk_delete'))
                                                    <td><input type="checkbox" class="row-checkbox form-check-input checkbox-style" value="{{ $content->id }}"></td>
                                                @endif
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $content->area_name }}</td>
                                                <td>{{ helper::currency_formate($content->delivery_charge, $vendor_id) }}
                                                </td>
                                                <td>
                                                    @if ($content->is_available == '1')
                                                        <a href="javascript:void(0)" tooltip="{{ trans('labels.active') }}"
                                                            @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('/admin/shipping/status_change-' . $content->id . '/2') }}')" @endif
                                                            class="btn btn-sm btn-size btn-outline-success {{ Auth::user()->type == 4 ? (helper::check_access('role_shipping_management', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                    @else
                                                        <a href="javascript:void(0)"
                                                            tooltip="{{ trans('labels.inactive') }}"
                                                            @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('/admin/shipping/status_change-' . $content->id . '/1') }}')" @endif
                                                            class="btn btn-sm btn-outline-danger btn-size {{ Auth::user()->type == 4 ? (helper::check_access('role_shipping_management', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">
                                                            <i class="fas fa-xmark"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ helper::date_format($content->created_at, $vendor_id) }}<br>
                                                    {{ helper::time_format($content->created_at, $vendor_id) }}
                                                </td>
                                                <td>
                                                    {{ helper::date_format($content->updated_at, $vendor_id) }}<br>
                                                    {{ helper::time_format($content->updated_at, $vendor_id) }}
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <a href="{{ URL::to('/admin/shipping/edit-' . $content->id) }}"
                                                            tooltip="{{ trans('labels.edit') }}"
                                                            class="btn btn-info btn-sm btn-size {{ Auth::user()->type == 4 ? (helper::check_access('role_shipping_management', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">
                                                            <i class="fa-regular fa-pen-to-square"></i>
                                                        </a>

                                                        <a href="javascript:void(0)" tooltip="{{ trans('labels.delete') }}"
                                                            @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('/admin/shipping/delete-' . $content->id) }}')" @endif
                                                            class="btn btn-danger btn-sm btn-size {{ Auth::user()->type == 4 ? (helper::check_access('role_shipping_management', Auth::user()->role_id, Auth::user()->vendor_id, 'delete') == 1 ? '' : 'd-none') : '' }}">
                                                            <i class="fa-regular fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $("#shipping_area-switch").on("change", function(e) {
            if (this.checked) {
                $("#shipping_charges_section").hide();
                $("#shipping_charges").prop("required", false);
            } else {
                $("#shipping_charges_section").show();
                $("#shipping_charges").prop("required", true);
            }
        }).change();
    </script>
@endsection
