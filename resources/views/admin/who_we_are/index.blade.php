@extends('admin.layout.default')

@php

    if (Auth::user()->type == 4) {
        $vendor_id = Auth::user()->vendor_id;
    } else {
        $vendor_id = Auth::user()->id;
    }

    $user = App\Models\User::where('id', $vendor_id)->first();

@endphp

@section('content')
    <div class="d-flex justify-content-between align-items-center">

        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{ trans('labels.who_we_are') }}</h5>

    </div>

    <div class="row mt-3">

        <div class="col-12">

            <form action="{{ URL::to('admin/whoweare/savecontent') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="card border-0 mb-3 p-3 box-shadow">

                    <div class="row">

                        <div class="col-md-6 mb-lg-0">

                            <div class="form-group">

                                <label class="form-label">{{ trans('labels.title') }}<span class="text-danger"> *

                                    </span></label>

                                <input type="text"
                                    class="form-control {{ session()->get('direction') == 2 ? 'input-group-rtl' : '' }}"
                                    name="title" placeholder="{{ trans('labels.title') }}"
                                    value="{{ @$content->whoweare_title }}" required>

                            </div>

                        </div>

                        <div class="col-md-6 mb-lg-0">

                            <div class="form-group">

                                <label class="form-label">{{ trans('labels.sub_title') }}<span class="text-danger"> *

                                    </span></label>

                                <input type="text"
                                    class="form-control {{ session()->get('direction') == 2 ? 'input-group-rtl' : '' }}"
                                    name="sub_title" placeholder="{{ trans('labels.sub_title') }}"
                                    value="{{ @$content->whoweare_subtitle }}" required>
                            </div>

                        </div>

                        <div class="col-md-6 mb-lg-0">

                            <div class="form-group">

                                <label class="form-label">{{ trans('labels.description') }}<span class="text-danger"> *

                                    </span></label>

                                <textarea class="form-control" placeholder="{{ trans('labels.description') }}" name="description" rows="5"
                                    required>{{ @$content->whoweare_description }}</textarea>

                            </div>

                        </div>

                        <div class="col-md-6 mb-lg-0">

                            <div class="form-group">

                                <label class="form-label">{{ trans('labels.image') }}<span class="text-danger"> *
                                    </span></label>

                                <input type="file" class="form-control" name="image">

                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>

                            <img src="{{ helper::image_path(@$content->whoweare_image) }}" class="img-fluid rounded hw-70"
                                alt="">

                        </div>

                        <div class="{{ session()->get('direction') == 2 ? 'text-start' : 'text-end' }}">

                            <button
                                @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif
                                class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_who_we_are', Auth::user()->role_id, $vendor_id, 'add') == 1 ? '' : 'd-none') : '' }}">{{ trans('labels.save') }}</button>

                        </div>

                    </div>

                </div>

            </form>

            <div class="card border-0 mb-3 box-shadow">

                <div class="text-end">

                    <a href="{{ URL::to(request()->url() . '/add') }}"
                        class="btn btn-secondary px-sm-4 text-capitalize mx-3 mt-3 {{ Auth::user()->type == 4 ? (helper::check_access('role_who_we_are', Auth::user()->role_id, $vendor_id, 'add') == 1 ? '' : 'd-none') : '' }}">

                        <i class="fa-regular fa-plus mx-1"></i>{{ trans('labels.add') }}</a>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-striped table-bordered py-3 zero-configuration w-100 dataTable no-footer">

                            <thead>

                                <tr class="text-capitalize fs-15 fw-500">
                                    <td></td>
                                    <td>{{ trans('labels.srno') }}</td>

                                    <td>{{ trans('labels.image') }}</td>

                                    <td>{{ trans('labels.title') }}</td>

                                    <td>{{ trans('labels.sub_title') }}</td>
                                    <td>{{ trans('labels.created_date') }}</td>
                                    <td>{{ trans('labels.updated_date') }}</td>

                                    <td>{{ trans('labels.action') }}</td>
                                </tr>

                            </thead>

                            <tbody id="tabledetails" data-url="{{ url('admin/whoweare/reorder_whoweare') }}">

                                @php

                                    $i = 1;

                                @endphp

                                @foreach ($allworkcontent as $content)
                                    <tr class="fs-7 align row1" id="dataid{{ $content->id }}"
                                        data-id="{{ $content->id }}">
                                        <td><a tooltip="{{ trans('labels.move') }}">
                                                <i class="fa-light fa-up-down-left-right mx-2"></i>
                                            </a>
                                        </td>
                                        <td>@php

                                            echo $i++;

                                        @endphp</td>

                                        <td>
                                            <img src="{{ helper::image_path($content->image) }}"
                                                class="img-fluid rounded hw-50 object-fit-cover" alt="">
                                        </td>

                                        <td>{{ $content->title }}</td>

                                        <td>{{ $content->sub_title }}</td>
                                        <td>{{ helper::date_format($content->created_at, $content->vendor_id) }}<br>
                                            {{ helper::time_format($content->created_at, $content->vendor_id) }}
                                        </td>
                                        <td>{{ helper::date_format($content->updated_at, $content->vendor_id) }}<br>
                                            {{ helper::time_format($content->updated_at, $content->vendor_id) }}
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                <a href="{{ URL::to('/admin/whoweare/edit-' . $content->id) }}"
                                                    tooltip="{{ trans('labels.edit') }}"
                                                    class="btn btn-info hov btn-sm {{ Auth::user()->type == 4 ? (helper::check_access('role_who_we_are', Auth::user()->role_id, $vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">
                                                    <i class="fa-regular fa-pen-to-square"></i></a>

                                                <a href="javascript:void(0)" tooltip="{{ trans('labels.delete') }}"
                                                    @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('/admin/whoweare/delete-' . $content->id) }}')" @endif
                                                    class="btn btn-danger hov btn-sm {{ Auth::user()->type == 4 ? (helper::check_access('role_who_we_are', Auth::user()->role_id, $vendor_id, 'delete') == 1 ? '' : 'd-none') : '' }}">
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
