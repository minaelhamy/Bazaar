@extends('admin.layout.default')
@php
    if (Auth::user()->type == 4) {
        $vendor_id = Auth::user()->vendor_id;
    } else {
        $vendor_id = Auth::user()->id;
    }
@endphp
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{ trans('labels.products') }}</h5>
        <div class="d-flex">
            <div class="d-flex align-items-center" style="gap: 10px;">
                <!-- Bulk Delete Button -->
                @if (@helper::checkaddons('bulk_delete'))
                    <button id="bulkDeleteBtn"
                        @if (env('Environment')=='sendbox' ) onclick="myFunction()" @else onclick="deleteSelected('{{ URL::to('admin/products/bulk_delete') }}')" @endif class="btn btn-danger hov btn-sm d-none d-flex" tooltip="{{ trans('labels.delete') }}">
                        <i class="fa-regular fa-trash"></i>
                    </button>
                @endif
                <a href="{{ URL::to('admin/products/add') }}"
                    class="btn btn-secondary px-sm-4 d-flex {{ Auth::user()->type == 4 ? (helper::check_access('role_products', Auth::user()->role_id, $vendor_id, 'add') == 1 ? '' : 'd-none') : '' }}">
                    <i class="fa-regular fa-plus mx-1"></i>{{ trans('labels.add') }}
                </a>
            </div>
            @if (@helper::checkaddons('product_import'))
                @if ($getproductslist->count() > 0)
                    <a href="{{ URL::to('/admin/exportproduct') }}"
                        class="btn btn-secondary px-sm-4 d-flex {{ Auth::user()->type == 4 ? (helper::check_access('role_products', Auth::user()->role_id, $vendor_id, 'add') == 1 ? '' : 'd-none') : '' }} mx-2">{{ trans('labels.export') }}</a>
                @endif
            @endif

        </div>

    </div>
    <div class="row">

        <div class="col-12">
            <div class="card border-0 my-3 box-shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered py-3 zero-configuration w-100">
                            <thead>
                                <tr class="text-capitalize fw-500 fs-15">
                                    <td></td>
                                    @if (@helper::checkaddons('bulk_delete'))
                                        @if($getproductslist->count() > 0)
                                            <td> <input type="checkbox" id="selectAll" class="form-check-input checkbox-style"></td>
                                        @endif
                                    @endif
                                    <td>{{ trans('labels.srno') }}</td>
                                    <td>{{ trans('labels.image') }}</td>
                                    <td>{{ trans('labels.name') }}</td>
                                    <td>{{ trans('labels.price') }}</td>
                                    <td>{{ trans('labels.stock') }}</td>
                                    <td>{{ trans('labels.status') }}</td>
                                    <td>{{ trans('labels.created_date') }}</td>
                                    <td>{{ trans('labels.updated_date') }}</td>
                                    <td>{{ trans('labels.action') }}</td>
                                </tr>
                            </thead>
                            <tbody id="tabledetails" data-url="{{ url('admin/products/reorder_category') }}">
                                @php $i = 1; @endphp
                                @foreach ($getproductslist as $product)
                                    <tr class="fs-7 row1 align-middle" id="dataid{{ $product->id }}"
                                        data-id="{{ $product->id }}">
                                        <td><a tooltip="{{ trans('labels.move') }}"><i
                                                    class="fa-light fa-up-down-left-right mx-2"></i></a></td>
                                        @if (@helper::checkaddons('bulk_delete'))
                                            <td><input type="checkbox" class="row-checkbox form-check-input checkbox-style" value="{{ $product->id }}"></td>
                                        @endif
                                        <td>@php echo $i++; @endphp</td>
                                        <td><img src="{{ @helper::image_path($product['product_image']->image) }}"
                                                class="img-fluid rounded hw-50 object-fit-cover" alt=""> </td>

                                        <td>{{ $product->item_name }} <br>
                                            @if ($product->view_count > 0)
                                                <span class="badge bg-success"><i class="fa-solid fa-eye"></i>
                                                    {{ $product->view_count }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($product->has_variants == 1)
                                                <span class="badge bg-info">{{ trans('labels.in_variants') }}</span><br>
                                            @else
                                                {{ helper::currency_formate($product->item_price, $vendor_id) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($product->has_variants == 1)
                                                <span class="badge bg-info">{{ trans('labels.in_variants') }}</span><br>
                                                @if (helper::checklowqty($product->id, $product->vendor_id) == 1)
                                                    <span class="badge bg-warning">{{ trans('labels.low_qty') }}</span>
                                                @endif
                                            @else
                                                @if ($product->stock_management == 1)
                                                    @if (helper::checklowqty($product->id, $product->vendor_id) == 1)
                                                        <span
                                                            class="badge bg-success">{{ trans('labels.in_stock') }}</span><br>
                                                        <span class="badge bg-warning">{{ trans('labels.low_qty') }}</span>
                                                    @elseif(helper::checklowqty($product->id, $product->vendor_id) == 2)
                                                        <span
                                                            class="badge bg-danger">{{ trans('labels.out_of_stock') }}</span>
                                                    @elseif(helper::checklowqty($product->id, $product->vendor_id) == 3)
                                                        -
                                                    @else
                                                        <span
                                                            class="badge bg-success">{{ trans('labels.in_stock') }}</span>
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            @endif

                                        </td>
                                        <td>
                                            @if ($product->is_available == '1')
                                                <a tooltip="{{ trans('labels.active') }}"
                                                    @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('admin/products/status-' . $product->slug . '/2') }}')" @endif
                                                    class="btn btn-sm btn-outline-success {{ Auth::user()->type == 4 ? (helper::check_access('role_products', Auth::user()->role_id, $vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"><i
                                                        class="fas fa-check"></i></a>
                                            @else
                                                <a tooltip="{{ trans('labels.inactive') }}"
                                                    @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('admin/products/status-' . $product->slug . '/1') }}')" @endif
                                                    class="btn btn-sm btn-outline-danger {{ Auth::user()->type == 4 ? (helper::check_access('role_products', Auth::user()->role_id, $vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"><i
                                                        class="fas fa-close"></i></a>
                                            @endif
                                        </td>
                                        <td>{{ helper::date_format($product->created_at, $vendor_id) }}<br>
                                            {{ helper::time_format($product->created_at, $vendor_id) }}
                                        </td>
                                        <td>{{ helper::date_format($product->updated_at, $vendor_id) }}<br>
                                            {{ helper::time_format($product->updated_at, $vendor_id) }}
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1 flex-wrap">
                                                <a tooltip="{{ trans('labels.edit') }}"
                                                    class="btn btn-info hov btn-sm {{ Auth::user()->type == 4 ? (helper::check_access('role_products', Auth::user()->role_id, $vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
                                                    href="{{ URL::to('admin/products/edit-' . $product->slug) }}">
                                                    <i class="fa-regular fa-pen-to-square"></i></a>
                                                <a tooltip="{{ trans('labels.delete') }}"
                                                    class="btn btn-danger hov btn-sm {{ Auth::user()->type == 4 ? (helper::check_access('role_products', Auth::user()->role_id, $vendor_id, 'delete') == 1 ? '' : 'd-none') : '' }}"
                                                    @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('admin/products/delete-' . $product->slug) }}')" @endif>
                                                    <i class="fa-regular fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
