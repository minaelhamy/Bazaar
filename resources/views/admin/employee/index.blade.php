@extends('admin.layout.default')

@section('content')
    @php

        $module = 'users';

    @endphp

    <div class="d-flex justify-content-between align-items-center">

        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{ trans('labels.employees') }}</h5>

        <div class="d-flex align-items-center" style="gap: 10px;">
            <!-- Bulk Delete Button -->
            @if (@helper::checkaddons('bulk_delete'))
            <button id="bulkDeleteBtn"
                @if (env('Environment')=='sendbox' ) onclick="myFunction()" @else onclick="deleteSelected('{{ URL::to('admin/employees/bulk_delete') }}')" @endif class="btn btn-danger hov btn-sm d-none d-flex" tooltip="{{ trans('labels.delete') }}">
                <i class="fa-regular fa-trash"></i>
            </button>
            @endif
            
            <a href="{{ URL::to('admin/employees/add') }}"
                class="btn btn-secondary px-sm-4 d-flex {{ Auth::user()->type == 4 ? (helper::check_access('role_employees', Auth::user()->role_id, Auth::user()->vendor_id, 'add') == 1 ? '' : 'd-none') : '' }}">
                <i class="fa-regular fa-plus mx-1"></i>{{ trans('labels.add') }}
            </a>
        </div>

    </div>

    <div class="row">
        @php
            if (Auth::user()->type == 4) {
                $vendor_id = Auth::user()->vendor_id;
            } else {
                $vendor_id = Auth::user()->id;
            }
        @endphp
        <div class="col-12">

            <div class="card border-0 my-3 box-shadow">

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-striped table-bordered py-3 zero-configuration w-100">

                            <thead>

                                <tr class="text-capitalize fw-500 fs-15">
                                    @if (@helper::checkaddons('bulk_delete'))
                                        @if($employees->count() > 0)
                                            <td> <input type="checkbox" id="selectAll" class="form-check-input checkbox-style"></td>
                                        @endif
                                    @endif

                                    <td>{{ trans('labels.srno') }}</td>

                                    <td>{{ trans('labels.image') }}</td>

                                    <td>{{ trans('labels.role') }}</td>

                                    <td>{{ trans('labels.name') }}</td>

                                    <td>{{ trans('labels.email') }}</td>

                                    <td>{{ trans('labels.mobile') }}</td>

                                    <td>{{ trans('labels.status') }}</td>

                                    <td>{{ trans('labels.created_date') }}</td>

                                    <td>{{ trans('labels.updated_date') }}</td>

                                    <td>{{ trans('labels.action') }}</td>



                                </tr>

                            </thead>

                            <tbody>

                                @php

                                    $i = 1;

                                @endphp

                                @foreach ($employees as $employee)
                                    <tr class="fs-7 align-middle">

                                        @if (@helper::checkaddons('bulk_delete'))
                                            <td><input type="checkbox" class="row-checkbox form-check-input checkbox-style" value="{{ $employee->id }}"></td>
                                        @endif
                                        <td>@php

                                            echo $i++;

                                        @endphp</td>

                                        <td><img src="{{ helper::image_path($employee->image) }}" height="50"
                                                width="50" alt=""></td>

                                        <td>{{ @helper::role($employee->role_id)->role }}</td>

                                        <td>{{ $employee->name }}</td>

                                        <td>{{ $employee->email }}</td>

                                        <td>{{ $employee->mobile }}</td>

                                        <td>

                                            @if ($employee->is_available == '1')
                                                <a tooltip="{{ trans('labels.active') }}"
                                                    class="btn btn-sm btn-outline-success hov {{ Auth::user()->type == 4 ? (helper::check_access('role_employees', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
                                                    href="javascript::void(0)"
                                                    @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('admin/employees/status-' . $employee->id . '/2') }}')" @endif><i
                                                        class="fa-regular fa-check"></i>

                                                </a>
                                            @else
                                                <a tooltip="{{ trans('labels.inactive') }}"
                                                    class="btn btn-sm btn-outline-danger hov {{ Auth::user()->type == 4 ? (helper::check_access('role_employees', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
                                                    href="javascript::void(0)"
                                                    @if (env('Environment') == 'sendbox') onclick="myFunction()" @else  onclick="statusupdate('{{ URL::to('admin/employees/status-' . $employee->id . '/1') }}')" @endif><i
                                                        class="fa-regular fa-xmark "></i>

                                                </a>
                                            @endif

                                        </td>

                                        <td>{{ helper::date_format($employee->created_at, $vendor_id) }}<br>
                                            {{ helper::time_format($employee->created_at, $vendor_id) }}
                                        </td>

                                        <td>{{ helper::date_format($employee->updated_at, $vendor_id) }}<br>
                                            {{ helper::time_format($employee->updated_at, $vendor_id) }}
                                        </td>

                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                <a href="{{ URL::to('admin/employees/edit-' . $employee->id) }}"
                                                    tooltip="{{ trans('labels.edit') }}"
                                                    class="btn btn-info hov btn-sm {{ Auth::user()->type == 4 ? (helper::check_access('role_employees', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">
                                                    <i class="fa-regular fa-pen-to-square"></i>

                                                </a>

                                                <a href="javascript:void(0)" tooltip="{{ trans('labels.delete') }}"
                                                    @if (env('Environment') == 'sendbox') onclick="myFunction()" @else

                                                    onclick="statusupdate('{{ URL::to('/admin/employees/delete-' . $employee->id) }}')" @endif
                                                    class="btn btn-danger hov btn-sm {{ Auth::user()->type == 4 ? (helper::check_access('role_employees', Auth::user()->role_id, Auth::user()->vendor_id, 'delete') == 1 ? '' : 'd-none') : '' }}">

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
