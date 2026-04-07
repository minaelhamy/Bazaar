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
        <h5 class="text-capitalize fw-600 text-dark fs-4 color-changer">{{ trans('labels.shopify') }}</h5>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card border-0 my-3 box-shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        @if (env('Environment') == 'sendbox')
                        <table class="table table-striped table-bordered py-3 zero-configuration w-100">
                            <thead>
                                <tr class="text-capitalize fw-500 fs-15">
                                    <th>#</th>
                                    <th>{{ trans('labels.name') }}</th>
                                    <th>{{ trans('labels.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach ($category as $category)
                                    <tr class="align-middle">
                                        <td> @php echo $i++; @endphp </td>
                                        <td> {{ $category->name }}</td>
                                        <td>
                                            <a @if (env('Environment') == 'sendbox') onclick="myFunction()" @else href="{{ URL::to('admin/shopify-category/add-category-' . $cat_data['id']) }}" @endif tooltip="{{trans('labels.add_category')}}"
                                                class="btn btn-info hov btn-sm"> <i class="fa-solid fa-plus"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <table class="table table-striped table-bordered py-3 zero-configuration w-100">
                            <thead>
                                <tr class="text-capitalize fw-500 fs-15">
                                    <th>#</th>
                                    <th>{{ trans('labels.name') }}</th>
                                    <th>{{ trans('labels.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($category['custom_collections'] as $key => $cat_data)
                                    <tr class="align-middle">
                                        <td> {{ $key+1 }} </td>
                                        <td> {{ $cat_data['title'] }} </td>
                                        <td>
                                            <a href="{{ URL::to('admin/shopify-category/add-category-' . $cat_data['id']) }}" tooltip="{{trans('labels.add_category')}}"
                                                class="btn btn-info hov btn-sm"> <i class="fa-solid fa-plus"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection