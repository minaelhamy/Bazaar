@extends('admin.layout.default')
@section('content')
    @php
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $module = 'role_currency_settings';
    @endphp
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{ trans('labels.currency') }}</h5>
        
        <div class="d-flex align-items-center" style="gap: 10px;">
            @if (@helper::checkaddons('bulk_delete'))
                <button id="bulkDeleteBtn"
                    @if (env('Environment')=='sendbox' ) onclick="myFunction()" @else onclick="deleteSelected('{{ URL::to('admin/currencys/bulk_delete') }}')" @endif class="btn btn-danger hov btn-sm d-none d-flex" tooltip="{{ trans('labels.delete') }}">
                    <i class="fa-regular fa-trash"></i>
                </button>
            @endif 
            
            @if (helper::checkaddons('currency_settigns'))
                <a href="{{ URL::to('admin/currencys/currency_add') }}"
                    class="btn btn-secondary px-sm-4 d-flex {{ Auth::user()->type == 4 ? (helper::check_access('role_currency_settings', Auth::user()->role_id, $vendor_id, 'add') == 1 ? '' : 'd-none') : '' }}">
                    <i class="fa-regular fa-plus mx-1"></i>{{ trans('labels.add') }}
                </a>
            @endif
        </div>
    </div>
    @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
        <div class="alert alert-warning my-3" role="alert">
            <p>{{ trans('labels.dont_change_default_currency') }}</p>
        </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card border-0 box-shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered py-3 zero-configuration w-100">
                            <thead>
                                <tr class="text-capitalize fw-500 fs-15">
                                    <td></td>
                                    @if (@helper::checkaddons('bulk_delete'))
                                        @if($getcurrency->count() > 0)
                                            <td> <input type="checkbox" id="selectAll" class="form-check-input checkbox-style"></td>
                                        @endif
                                     @endif
                                    <td>{{ trans('labels.srno') }}</td>
                                    <td>{{ trans('labels.name') }}</td>
                                    <td>{{ trans('labels.currency') }}</td>
                                    <td>{{ trans('labels.status') }}</td>
                                    <td>{{ trans('labels.created_date') }}</td>
                                    <td>{{ trans('labels.updated_date') }}</td>
                                    <td>{{ trans('labels.action') }}</td>


                                </tr>
                            </thead>
                            <tbody id="tabledetails" data-url="">
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($getcurrency as $currency)
                                    <tr class="fs-7 row1 align-middle" id="dataid{{ $currency->id }}"
                                        data-id="{{ $currency->id }}">
                                        <td><a tooltip="{{ trans('labels.move') }}">
                                                <i class="fa-light fa-up-down-left-right mx-2"></i>
                                            </a>
                                        </td>
                                        @if (@helper::checkaddons('bulk_delete'))
                                            @if (Strtoupper($currency->currency) != 'USD')
                                                <td><input type="checkbox" class="row-checkbox form-check-input checkbox-style" value="{{ $currency->id }}"></td>
                                            @else
                                                <td></td>
                                            @endif
                                        @endif
                                        <td>@php
                                            echo $i++;
                                        @endphp </td>
                                        <td>{{ $currency->currency }}</td>

                                        <td>
                                            {{ $currency->currency_symbol }}
                                        </td>
                                        <td>
                                            @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                                                @if ($currency->is_available == '1')
                                                    <a tooltip="{{ trans('labels.active') }}"
                                                        @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('admin/currencys/currencystatus-' . $currency->code . '/2') }}')" @endif
                                                        class="btn btn-sm btn-outline-success hov {{ Auth::user()->type == 4 ? (helper::check_access('role_currency_settings', Auth::user()->role_id, $vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                @else
                                                    <a tooltip="{{ trans('labels.inactive') }}"
                                                        @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('admin/currencys/currencystatus-' . $currency->code . '/1') }}')" @endif
                                                        class="btn btn-sm btn-outline-danger hov {{ Auth::user()->type == 4 ? (helper::check_access('role_currency_settings', Auth::user()->role_id, $vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">
                                                        <i class="fas fa-close mx-1"></i>
                                                    </a>
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ helper::date_format($currency->created_at, $vendor_id) }}<br>
                                            {{ helper::time_format($currency->created_at, $vendor_id) }}
                                        </td>
                                        <td>{{ helper::date_format($currency->updated_at, $vendor_id) }}<br>
                                            {{ helper::time_format($currency->updated_at, $vendor_id) }}
                                        </td>
                                        @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                                            <td>
                                                @if (Strtoupper($currency->currency) != 'USD')
                                                    <div class="d-flex flex-wrap gap-2">
                                                        <a href="{{ URL::to('admin/currencys/currency_edit-' . $currency->id) }}"
                                                            class="btn btn-info hov btn-sm {{ Auth::user()->type == 4 ? (helper::check_access('role_currency_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
                                                            tooltip="{{ trans('labels.edit') }}">
                                                            <i class="fa-regular fa-pen-to-square"></i>
                                                        </a>

                                                        <a class="btn btn-danger hov {{ Auth::user()->type == 4 ? (helper::check_access('role_currency_settings', Auth::user()->role_id, $vendor_id, 'delete') == 1 ? '' : 'd-none') : '' }}"
                                                            tooltip="{{ trans('labels.delete') }}"
                                                            @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('admin/currencys/delete-' . $currency->id . '/1') }}')" @endif>
                                                            <i class="fa-regular fa-trash"></i>
                                                        </a>

                                                    </div>
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
    </div>
@endsection
