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

        <h5 class="text-capitalize fw-600 color-changer text-dark fs-4">{{ trans('labels.add_new') }}</h5>

        <nav aria-label="breadcrumb">

            <ol class="breadcrumb m-0">

                <li class="breadcrumb-item text-dark"><a
                        href="{{ URL::to('admin/custom_status') }}" class="color-changer">{{ trans('labels.custom_status') }}</a></li>

                <li class="breadcrumb-item active {{ session()->get('direction') == 2 ? 'breadcrumb-rtl' : '' }}"
                    aria-current="page">{{ trans('labels.add') }}</li>

            </ol>

        </nav>

    </div>

    <div class="row mt-3">

        <div class="col-12">

            <div class="card border-0 box-shadow">

                <div class="card-body">

                    <form action="{{ URL::to('admin/custom_status/save') }}" method="POST" enctype="multipart/form-data">

                        @csrf

                        <div class="row">

                            <div class="form-group col-md-6">

                                <label class="form-label">{{ trans('labels.status') }} {{ trans('labels.type') }}<span
                                        class="text-danger"> * </span></label>
                                <select name="status_type" class="form-select" required>
                                    <option value="0">{{ trans('labels.select') }}</option>
                                    <option value="2">{{ trans('labels.process') }}</option>
                                </select>

                              
                            </div>
                            <div class="form-group col-md-6">

                                <label class="form-label">{{ trans('labels.order_type') }}<span class="text-danger"> *
                                    </span></label>

                                <select name="order_type" class="form-select" required>

                                    <option value="0">{{ trans('labels.select') }}</option>

                                    <option value="1">{{ trans('labels.delivery') }}</option>

                                    <option value="2">{{ trans('labels.pickup') }}</option>

                                    <option value="3">{{ trans('labels.table') }}</option>

                                    @if (@helper::checkaddons('subscription'))
                                        @if (@helper::checkaddons('pos'))
                                            @php
                                                $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)
                                                    ->orderByDesc('id')
                                                    ->first();
                                                if (helper::getslug($vendor_id)->allow_without_subscription == 1) {
                                                    $pos = 1;
                                                } else {
                                                    $pos = @$checkplan->pos;
                                                }
                                            @endphp
                                            @if ($pos == 1)
                                                <option value="4">
                                                    {{ trans('labels.pos') }}</option>
                                            @endif
                                        @endif
                                    @else
                                        @if (@helper::checkaddons('pos'))
                                            <option value="4">
                                                {{ trans('labels.pos') }}</option>
                                        @endif
                                    @endif

                                </select>

                             

                            </div>

                            <div class="form-group col-md-12">

                                <label class="form-label">{{ trans('labels.name') }}<span class="text-danger"> *
                                    </span></label>

                                <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                    placeholder="{{ trans('labels.name') }}" required>

                               

                            </div>

                            <div class="mt-3 {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">

                                <a href="{{ URL::to('admin/custom_status') }}"
                                    class="btn btn-danger px-sm-4">{{ trans('labels.cancel') }}</a>

                                <button
                                    class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_custom_status', Auth::user()->role_id, Auth::user()->vendor_id, 'add') == 1 ? '' : 'd-none') : '' }}"
                                    @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif>{{ trans('labels.save') }}</button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>



@endsection
