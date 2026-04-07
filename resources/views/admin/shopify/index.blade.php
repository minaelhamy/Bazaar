@php
    if(Auth::user()->type == 4)
    {
        $vendor_id = Auth::user()->vendor_id;
    }else{
        $vendor_id = Auth::user()->id;
    }
@endphp
@extends('admin.layout.default')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{ trans('labels.shopify') }}</h5>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card border-0 box-shadow my-3">
                <div class="card-body">
                    <div class="table-responsive">
                        @include('admin.shopify.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection