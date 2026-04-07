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
        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{ trans('labels.edit') }}</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item text-dark"><a href="{{ URL::to('admin/testimonials') }}" class="color-changer">{{ trans('labels.testimonials') }}</a></li>
                <li class="breadcrumb-item active {{session()->get('direction') == 2 ? 'breadcrumb-rtl' : ''}}" aria-current="page">{{ trans('labels.edit') }}</li>
            </ol>
        </nav>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card border-0 box-shadow">
                <div class="card-body">
                    <form action="{{ URL::to('/admin/testimonials/update-' . $edittestimonial->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label">{{ trans('labels.name') }}<span class="text-danger"> *
                                    </span></label>
                                <input type="text" class="form-control" name="name"
                                    value="{{ $edittestimonial->name }}" placeholder="{{ trans('labels.name') }}" required>
                               
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">{{ trans('labels.position') }}<span class="text-danger"> *
                                    </span></label>
                                <input type="text" class="form-control" name="position"
                                    value="{{ $edittestimonial->position }}" placeholder="{{ trans('labels.position') }}"
                                    required>
                                
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">{{ trans('labels.ratting') }}<span class="text-danger"> *
                                    </span></label>
                                <select name="rating" class="form-select">
                                    <option value="1" {{ $edittestimonial->star == 1 ? 'selected' : '' }}>1</option>
                                    <option value="2" {{ $edittestimonial->star == 2 ? 'selected' : '' }}>2</option>
                                    <option value="3" {{ $edittestimonial->star == 3 ? 'selected' : '' }}>3</option>
                                    <option value="4" {{ $edittestimonial->star == 4 ? 'selected' : '' }}>4</option>
                                    <option value="5" {{ $edittestimonial->star == 5 ? 'selected' : '' }}>5</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">{{ trans('labels.image') }}<span class="text-danger">
                                        * </span></label>
                                <input type="file" class="form-control" name="image"
                                    placeholder="{{ trans('labels.image') }}">
                                <img src="{{ helper::image_path($edittestimonial->image) }}"
                                    class="img-fluid rounded hw-50 mt-1 object" alt="">
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ trans('labels.description') }}<span class="text-danger"> *
                                    </span></label>
                                <textarea class="form-control" name="description" placeholder="{{ trans('labels.description') }}" rows="5"
                                    required>{{ $edittestimonial->description }}</textarea>
                               
                            </div>

                        </div>
                        <div class="mt-3 {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                            <a href="{{ URL::to('admin/testimonials') }}"
                                class="btn btn-danger px-sm-4">{{ trans('labels.cancel') }}</a>
                            <button
                                @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif
                                class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_testimonials', Auth::user()->role_id, $vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">{{ trans('labels.save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
