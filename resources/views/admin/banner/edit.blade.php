@extends('admin.layout.default')
@php
    if (Auth::user()->type == 4) {
        $vendor_id = Auth::user()->vendor_id;
    } else {
        $vendor_id = Auth::user()->id;
    }
    if (request()->is('admin/sliders*')) {
        $title = trans('labels.sliders');
        $url = URL::to('admin/sliders');
    } elseif (request()->is('admin/bannersection-1*')) {
        $title = trans('labels.section-1');
        $url = URL::to('admin/bannersection-1');
    } elseif (request()->is('admin/bannersection-2*')) {
        $title = trans('labels.section-2');
        $url = URL::to('admin/bannersection-2');
    }
@endphp
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{ trans('labels.edit') }}</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item text-dark"><a href="{{ $url }}" class="color-changer">{{ $title }}</a>
                </li>
                <li class="breadcrumb-item active {{ session()->get('direction') == 2 ? 'breadcrumb-rtl' : '' }}"
                    aria-current="page">{{ trans('labels.edit') }}</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card border-0 box-shadow">
                <div class="card-body">
                    <form action="{{ $url . '/update-' . $getbannerdata->id }}" method="POST"
                        enctype="multipart/form-data" class="m-0">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="section" value="{{ $getbannerdata->section }}">
                            <div class="col-sm-6 form-group">
                                <label class="form-label">{{ trans('labels.type') }}</label>
                                <select class="form-select type" name="banner_info">
                                    <option value="0">{{ trans('labels.select') }} </option>
                                    <option value="1" {{ $getbannerdata->type == '1' ? 'selected' : '' }}>
                                        {{ trans('labels.category') }}</option>
                                    <option value="2" {{ $getbannerdata->type == '2' ? 'selected' : '' }}>
                                        {{ trans('labels.product') }}</option>
                                </select>

                            </div>
                            <div class="col-sm-6 form-group 1 gravity">
                                <label class="form-label">{{ trans('labels.category') }}<span class="text-danger">
                                        *
                                    </span></label>
                                <select class="form-select" name="category" id="category">
                                    <option value="" selected>{{ trans('labels.select') }} </option>
                                    @foreach ($getcategorylist as $item)
                                        <option
                                            value="{{ $item->id }}"{{ $item->id == $getbannerdata->category_id ? 'selected' : '' }}>
                                            {{ $item->name }} </option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="col-sm-6 form-group 2 gravity">
                                <label class="form-label">{{ trans('labels.product') }}<span class="text-danger"> *
                                    </span></label>
                                <select class="form-select" name="product" id="product">
                                    <option value="" selected>{{ trans('labels.select') }} </option>
                                    @foreach ($getproductslist as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $item->id == $getbannerdata->product_id ? 'selected' : '' }}>
                                            {{ $item->item_name }}</option>
                                    @endforeach
                                </select>

                            </div>

                        
                            <div class="col-sm-6 form-group">
                                <label class="form-label">{{ trans('labels.image') }}<span class="text-danger"> *
                                    </span></label>
                                <input type="file" class="form-control" name="image">

                                <img src="{{ helper::image_path($getbannerdata->banner_image) }}"
                                    class="img-fluid rounded hight-50 mt-1" alt="">
                            </div>
                       
                            <div class="mt-3 {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                <a href="{{ URL::to($url) }}"
                                    class="btn btn-danger px-sm-4">{{ trans('labels.cancel') }}</a>
                                <button
                                    class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_banner', Auth::user()->role_id, $vendor_id, 'edit') == 1 || helper::check_access('role_sliders', Auth::user()->role_id, $vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
                                    @if (env('Environment') == 'sendbox') type="button"
                                    onclick="myFunction()" @else type="submit" @endif>{{ trans('labels.save') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('.type').on('change', function() {
            "use strict";
            var optionValue = $(this).find("option:selected").attr("value");

            if (optionValue) {
                $(".gravity").not("." + optionValue).hide();
                $(".gravity").not("." + optionValue).find('select').prop('required', false);
                $("." + optionValue).show();
                $("." + optionValue).find('select').prop('required', true);

            } else {
                $(".gravity").hide();
                $(".gravity").find('select').prop('required', false);
                $('#link_text').prop('required', false);
            }
            if (optionValue != 0) {
                $('#link_text').prop('required', true);
                $('.link_text').removeClass('d-none');

            } else {
                $('#link_text').prop('required', false);
                $('.link_text').addClass('d-none');
            }
        }).change();
    </script>
@endsection
