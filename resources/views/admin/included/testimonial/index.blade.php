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
        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{ trans('labels.testimonials') }}</h5>

        <div class="d-flex align-items-center" style="gap: 10px;">
            <!-- Bulk Delete Button -->
            @if (@helper::checkaddons('bulk_delete'))
            <button id="bulkDeleteBtn"
                @if (env('Environment')=='sendbox' ) onclick="myFunction()" @else onclick="deleteSelected('{{ URL::to('admin/testimonials/bulk_delete') }}')" @endif class="btn btn-danger hov btn-sm d-none d-flex" tooltip="{{ trans('labels.delete') }}">
                <i class="fa-regular fa-trash"></i>
            </button>
            @endif
            <a href="{{ URL::to('admin/testimonials/add') }}" class="btn btn-secondary px-sm-4 d-flex {{ Auth::user()->type == 4 ? (helper::check_access('role_testimonials', Auth::user()->role_id, $vendor_id, 'add') == 1 ? '' : 'd-none') : '' }}">
                <i class="fa-regular fa-plus mx-1"></i>{{ trans('labels.add') }}
            </a>
        </div>
    </div>
    <div class="row mt-3">
    
        <div class="col-12">
            <div class="card border-0 mb-3 box-shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered py-3 zero-configuration w-100 dataTable no-footer">
                            <thead>
                                <tr class="text-capitalize fw-500 fs-15">
                                    <td></td>
                                    @if (@helper::checkaddons('bulk_delete'))
                                        @if($testimonials->count() > 0)
                                            <td> <input type="checkbox" id="selectAll" class="form-check-input checkbox-style"></td>
                                        @endif
                                    @endif
                                    <td>{{ trans('labels.srno') }}</td>
                                    <td>{{ trans('labels.image') }}</td>
                                    <td>{{ trans('labels.name') }}</td>
                                    <td>{{ trans('labels.position') }}</td>
                                    <td>{{ trans('labels.description') }}</td>
                                    <td>{{ trans('labels.ratting') }}</td>
                                    <td>{{ trans('labels.created_date') }}</td>
                                        <td>{{ trans('labels.updated_date') }}</td>
                                    <td>{{ trans('labels.action') }}</td>
                                </tr>
                            </thead>
                            <tbody id="tabledetails" data-url="{{url('admin/testimonials/reorder_testimonials')}}">
                                @php
                                    $i = 1;
                                @endphp
                            @foreach ($testimonials as $testimonial)
                            <tr class="fs-7 row1 align-middle" id="dataid{{$testimonial->id}}" data-id="{{$testimonial->id}}">
                                <td><a tooltip="{{trans('labels.move')}}"><i class="fa-light fa-up-down-left-right mx-2"></i></a></td>

                                @if (@helper::checkaddons('bulk_delete'))
                                    <td><input type="checkbox" class="row-checkbox form-check-input checkbox-style" value="{{ $testimonial->id }}"></td>
                                @endif
                                <td>@php
                                    echo $i++;
                                @endphp</td>
                                <td><img src="{{helper::image_path($testimonial->image)}}"  class="img-fluid rounded hw-50 object-fit-cover" alt=""></td>
                                <td>{{$testimonial->name}}</td>
                                <td>{{$testimonial->position}}</td>
                                <td>{{$testimonial->description}}</td>
                                <td>{{$testimonial->star}}</td>
                                <td>{{ helper::date_format($testimonial->created_at,$vendor_id) }}<br>
                                {{helper::time_format($testimonial->created_at,$vendor_id)}}
                                </td>
                                <td>{{ helper::date_format($testimonial->updated_at,$vendor_id) }}<br>
                                {{helper::time_format($testimonial->updated_at,$vendor_id)}}
                                </td>
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        <a href="{{ URL::to('/admin/testimonials/edit-'.$testimonial->id ) }}" tooltip="{{trans('labels.edit')}}"
                                            class="btn btn-info btn-sm hov {{ Auth::user()->type == 4 ? (helper::check_access('role_testimonials', Auth::user()->role_id, $vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"> <i
                                                class="fa-regular fa-pen-to-square"></i></a>
                                        <a href="javascript:void(0)" tooltip="{{trans('labels.delete')}}"
                                            @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('admin/testimonials/delete-'.$testimonial->id) }}')" @endif
                                            class="btn btn-danger btn-sm hov {{ Auth::user()->type == 4 ? (helper::check_access('role_testimonials', Auth::user()->role_id, $vendor_id, 'delete') == 1 ? '' : 'd-none') : '' }}">
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
