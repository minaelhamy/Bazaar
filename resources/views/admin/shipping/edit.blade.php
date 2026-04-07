@extends('admin.layout.default')
@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{ trans('labels.edit') }}</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item text-dark">
                    <a href="{{ URL::to('admin/shipping') }}" class="color-changer">{{ trans('labels.shipping_management') }}</a>
                </li>
                <li class="breadcrumb-item active {{ session()->get('direction') == 2 ? 'breadcrumb-rtl' : '' }}"
                    aria-current="page">{{ trans('labels.edit') }}</li>
            </ol>
        </nav>
    </div>
    <div class="row my-3">
        <div class="col-12">
            <div class="card border-0 box-shadow">
                <div class="card-body">
                    <form action="{{ URL::to('/admin/shipping/update-' . $editwork->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label">{{ trans('labels.area_name') }}
                                    <span class="text-danger"> * </span></label>
                                <input type="text" class="form-control" name="area_name"
                                    value="{{ $editwork->area_name }}" placeholder="{{ trans('labels.area_name') }}"
                                    required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">{{ trans('labels.delivery_charge') }}
                                    <span class="text-danger"> * </span></label>
                                <input type="text" class="form-control" name="delivery_charge"
                                    value="{{ $editwork->delivery_charge }}"
                                    placeholder="{{ trans('labels.delivery_charge') }}" required>
                            </div>
                        </div>
                        <div class="mt-3 d-flex gap-1 justify-content-end">
                            <a href="{{ URL::to('admin/shipping') }}"
                                class="btn btn-danger px-4">{{ trans('labels.cancel') }}</a>
                            <button
                                @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif
                                class="btn btn-primary px-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_shipping_management', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">{{ trans('labels.save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
