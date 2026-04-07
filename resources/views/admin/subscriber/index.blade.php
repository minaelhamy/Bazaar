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
        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{ trans('labels.subscribers') }}</h5>
        <div class="d-flex align-items-center" >
            <!-- Bulk Delete Button -->
            @if (@helper::checkaddons('bulk_delete'))
            <button id="bulkDeleteBtn"
                @if (env('Environment')=='sendbox' ) onclick="myFunction()" @else onclick="deleteSelected('{{ URL::to('admin/subscribers/bulk_delete') }}')" @endif class="btn btn-danger hov btn-sm d-none d-flex" tooltip="{{ trans('labels.delete') }}" style="margin-right: 30px;">
                <i class="fa-regular fa-trash"></i>
            </button>
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
                                    @if (@helper::checkaddons('bulk_delete'))
                                        @if($getsubscribers->count() > 0)
                                            <td> <input type="checkbox" id="selectAll" class="form-check-input checkbox-style"></td>
                                        @endif
                                    @endif
                                    <td>{{ trans('labels.srno') }}</td>
                                    <td>{{ trans('labels.email') }}</td>
                                    <td>{{ trans('labels.created_date') }}</td>
                                    <td>{{ trans('labels.updated_date') }}</td>
                                    <td>{{ trans('labels.action') }}</td>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i=1; @endphp
                                @foreach ($getsubscribers as $subscriber)
                                    <tr class="fs-7 align-middle">
                                        @if (@helper::checkaddons('bulk_delete'))
                                            <td><input type="checkbox" class="row-checkbox form-check-input checkbox-style" value="{{ $subscriber->id }}"></td>
                                        @endif
                                        <td>@php echo $i++ @endphp</td>
                                        <td>{{ $subscriber->email }}</td>
                                        <td>{{ helper::date_format($subscriber->created_at, $vendor_id) }}<br>
                                            {{ helper::time_format($subscriber->created_at, $vendor_id) }}
                                        </td>
                                        <td>{{ helper::date_format($subscriber->updated_at, $vendor_id) }}<br>
                                            {{ helper::time_format($subscriber->updated_at, $vendor_id) }}
                                        </td>
                                        <td>
                                            <a tooltip="{{ trans('labels.delete') }}"
                                                @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="deletedata('{{ URL::to('admin/subscribers/delete-' . $subscriber->id) }}')" @endif
                                                class="btn btn-danger hov btn-sm {{ Auth::user()->type == 4 ? (helper::check_access('role_subscribers', Auth::user()->role_id, Auth::user()->vendor_id, 'delete') == 1 ? '' : 'd-none') : '' }}">
                                                <i class="fa-regular fa-trash"></i>
                                            </a>
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
