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
                <li class="breadcrumb-item text-dark">
                    <a href="{{ URL::to('admin/tax') }}" class="color-changer">
                        {{ trans('labels.tax') }}
                    </a>
                </li>
                <li class="breadcrumb-item active {{ session()->get('direction') == 2 ? 'breadcrumb-rtl' : '' }}"
                    aria-current="page">
                    {{ trans('labels.edit') }}
                </li>
            </ol>
        </nav>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card border-0 box-shadow">
                <div class="card-body">
                    <form action="{{ URL::to('admin/tax/update-' . $edittax->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label">{{ trans('labels.name') }}<span class="text-danger"> *
                                    </span></label>
                                <input type="text" class="form-control" name="name" value="{{ $edittax->name }}"
                                    placeholder="{{ trans('labels.name') }}" required>
                                
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">{{ trans('labels.type') }}<span class="text-danger"> *
                                    </span></label>
                                <select name="type" class="form-select" required>
                                    <option value="">{{ trans('labels.select') }}</option>
                                    <option value="1" {{ $edittax->type == 1 ? 'selected' : '' }}>
                                        {{ trans('labels.fixed') }} ({{ helper::appdata($vendor_id)->currency }})</option>
                                    <option value="2" {{ $edittax->type == 2 ? 'selected' : '' }}>
                                        {{ trans('labels.percentage') }} (%)</option>

                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">{{ trans('labels.tax') }}<span class="text-danger"> *
                                    </span></label>
                                <input type="text" class="form-control numbers_only" name="tax" value="{{ $edittax->tax }}"
                                    placeholder="{{ trans('labels.tax') }}" required>
                               
                            </div>
                            <div class="mt-3 {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                <a href="{{ URL::to('admin/tax') }}"
                                    class="btn btn-danger px-sm-4">{{ trans('labels.cancel') }}</a>
                                <button class="btn btn-primary px-sm-4"
                                    @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif>{{ trans('labels.save') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
