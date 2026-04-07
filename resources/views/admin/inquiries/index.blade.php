@extends('admin.layout.default')
@section('content')
<div class="d-flex justify-content-between align-items-center">
    <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{ trans('labels.inquiries') }}</h5>
    
    <div class="d-flex align-items-center" >
        <!-- Bulk Delete Button -->
        @if (@helper::checkaddons('bulk_delete'))
            <button id="bulkDeleteBtn"
                @if (env('Environment')=='sendbox' ) onclick="myFunction()" @else onclick="deleteSelected('{{ URL::to('admin/inquiries/bulk_delete') }}')" @endif class="btn btn-danger hov btn-sm d-none d-flex" tooltip="{{ trans('labels.delete') }}" style="margin-right: 30px;">
                <i class="fa-regular fa-trash"></i>
            </button>
        @endif
    </div>
</div>
    @php
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
    @endphp
    <div class="row">
        <div class="col-12">
            <div class="card border-0 my-3 box-shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered py-3 zero-configuration w-100">
                            <thead>
                                <tr class="text-capitalize fw-500 fs-15">
                                    @if (@helper::checkaddons('bulk_delete'))
                                        @if($getinquiries->count() > 0)
                                            <td> <input type="checkbox" id="selectAll" class="form-check-input checkbox-style"></td>
                                        @endif
                                    @endif
                                    <td>{{ trans('labels.srno') }}</td>
                                    <td>{{ trans('labels.name') }}</td>
                                    <td>{{ trans('labels.email') }}</td>
                                    <td>{{ trans('labels.mobile') }}</td>
                                    <td>{{ trans('labels.message') }}</td>
                                    <td>{{ trans('labels.status') }}</td>
                                    <td>{{ trans('labels.created_date') }}</td>
                                    <td>{{ trans('labels.updated_date') }}</td>
                                    <td>{{ trans('labels.action') }}</td>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i=1; @endphp
                                @foreach ($getinquiries as $inquiry)
                                    <tr class="fs-7 align-middle">
                                        @if (@helper::checkaddons('bulk_delete'))
                                            <td><input type="checkbox" class="row-checkbox form-check-input checkbox-style" value="{{ $inquiry->id }}"></td>
                                        @endif
                                        <td>@php echo $i++ @endphp</td>
                                        <td>{{ $inquiry->name }}</td>
                                        <td>{{ $inquiry->email }}</td>
                                        <td>{{ $inquiry->mobile }}</td>
                                        <td>{{ $inquiry->message }}</td>
                                        <td>
                                            @if ($inquiry->status == 1)
                                                <span class="badge bg-warning"> {{ trans('labels.pending') }}</span>
                                            @else
                                                <span class="badge bg-success"> {{ trans('labels.completed') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ helper::date_format($inquiry->created_at, $vendor_id) }}<br>
                                            {{ helper::time_format($inquiry->created_at, $vendor_id) }}
                                        </td>
                                        <td>{{ helper::date_format($inquiry->updated_at, $vendor_id) }}<br>
                                            {{ helper::time_format($inquiry->updated_at, $vendor_id) }}
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1 flex-wrap">
                                                <a tooltip="{{ trans('labels.delete') }}"
                                                    @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="deletedata('{{ URL::to('admin/inquiries/delete-' . $inquiry->id) }}')" @endif
                                                    class="btn btn-danger hov btn-sm {{ Auth::user()->type == 4 ? (helper::check_access('role_inquiries', Auth::user()->role_id, Auth::user()->vendor_id, 'delete') == 1 ? '' : 'd-none') : '' }}">
                                                    <i class="fa-regular fa-trash"></i>
                                                </a>
                                                @if ($inquiry->status == 1)
                                                    <a @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('admin/inquiries/change_status-' . $inquiry->id . '/2') }}')" @endif
                                                        class="btn btn-sm btn-success hov {{ Auth::user()->type == 4 ? (helper::check_access('role_inquiries', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
                                                        tooltip="{{ trans('labels.active') }}">
                                                        <i class="fa-regular fa-check"></i>
                                                    </a>
                                                @endif
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
