@php
    if (Auth::user()->type == 4) {
        $vendor_id = Auth::user()->vendor_id;
    } else {
        $vendor_id = Auth::user()->id;
    }
    if (request()->is('admin/sliders*')) {
        $section = 0;
        $title = trans('labels.sliders');
        $url = 'sliders';
    } elseif (request()->is('admin/bannersection-1*')) {
        $section = 1;
        $title = trans('labels.section-1');
        $url = 'bannersection-1';
    } elseif (request()->is('admin/bannersection-2*')) {
        $section = 2;
        $title = trans('labels.section-2');
        $url = 'bannersection-2';
    }
@endphp

@extends('admin.layout.default')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{ $title }}</h5>
        <div class="d-flex align-items-center" style="gap: 10px;">
            <!-- Bulk Delete Button -->
            @if (@helper::checkaddons('bulk_delete'))
            <button id="bulkDeleteBtn"
                @if (env('Environment')=='sendbox' ) onclick="myFunction()" @else onclick="deleteSelected('{{ URL::to(request()->url() . '/bulk_delete') }}')" @endif class="btn btn-danger hov btn-sm d-none d-flex" tooltip="{{ trans('labels.delete') }}">
                <i class="fa-regular fa-trash"></i>
            </button>
            @endif
            @if (
                (Auth::user()->type == 4 && helper::check_access('role_banner', Auth::user()->role_id, $vendor_id, 'add') == 1) ||
                    helper::check_access('role_sliders', Auth::user()->role_id, $vendor_id, 'add') == 1)
                <a href="{{ URL::to(request()->url() . '/add') }}" class="btn btn-secondary px-sm-4 d-flex">
                    <i class="fa-regular fa-plus mx-1"></i>{{ trans('labels.add') }}
                </a>
            @endif
            @if (Auth::user()->type == 1 || Auth::user()->type == 2)
                <a href="{{ URL::to(request()->url() . '/add') }}" class="btn btn-secondary px-sm-4 d-flex">
                    <i class="fa-regular fa-plus mx-1"></i>{{ trans('labels.add') }}
                </a>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card border-0 box-shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        @include('admin.banner.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
