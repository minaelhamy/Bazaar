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

                <li class="breadcrumb-item text-dark"><a
                        href="{{ URL::to('admin/custom_status') }}" class="color-changer">{{ trans('labels.custom_status') }}</a></li>

                <li class="breadcrumb-item active {{ session()->get('direction') == 2 ? 'breadcrumb-rtl' : '' }}"
                    aria-current="page">{{ trans('labels.edit') }}</li>

            </ol>

        </nav>

    </div>

    <div class="row mt-3">

        <div class="col-12">

            <div class="card border-0 box-shadow">

                <div class="card-body">

                    <form action="{{ URL::to('admin/custom_status/update-' . $editstatus->id) }}" method="POST"
                        enctype="multipart/form-data">

                        @csrf

                        <div class="row">

                            <div class="form-group col-md-6">

                                <label class="form-label">{{ trans('labels.status') }} {{ trans('labels.type') }}<span
                                        class="text-danger"> * </span></label>
                                <input type="hidden" value="{{$editstatus->type}}" name="type" id="type">
                                <select name="status_type" id="status_type" class="form-select" required  {{ $editstatus->type == 1 || $editstatus->type == 3 || $editstatus->type == 4 ? 'disabled' : '' }}>
                                <option value="0">{{ trans('labels.select') }}</option>
                                    <option value="1" class="{{ $editstatus->type == 1 || $editstatus->type == 3 || $editstatus->type == 4 ? '' : 'd-none' }}" {{ $editstatus->type == 1 ? 'selected' : '' }}>
                                        {{ trans('labels.default') }}</option>

                                    <option value="2"  {{ $editstatus->type == 2 ? 'selected' : '' }}>
                                        {{ trans('labels.process') }}</option>
                                        
                                    <option value="3" class="{{ $editstatus->type == 1 || $editstatus->type == 3 || $editstatus->type == 4 ? '' : 'd-none' }}" {{ $editstatus->type == 3 ? 'selected' : '' }}>
                                        {{ trans('labels.complete') }}</option>
                                    <option value="4" class="{{ $editstatus->type == 1 || $editstatus->type == 3 || $editstatus->type == 4 ? '' : 'd-none' }}" {{ $editstatus->type == 4 ? 'selected' : '' }}>
                                        {{ trans('labels.cancel') }}</option>
                                </select>

                               

                            </div>
                            <div class="form-group col-md-6">

                                <label class="form-label">{{ trans('labels.order_type') }}<span class="text-danger"> *
                                    </span></label>
                                <input type="hidden" value="{{$editstatus->order_type}}" name="order" id="order">
                                <select name="order_type" class="form-select" required  {{ $editstatus->type == 1 || $editstatus->type == 3 || $editstatus->type == 4 ? 'disabled' : '' }}>

                                    <option value="0">{{ trans('labels.select') }}</option>

                                    <option value="1" {{ $editstatus->order_type == 1 ? 'selected' : '' }}>
                                        {{ trans('labels.delivery') }}</option>

                                    <option value="2" {{ $editstatus->order_type == 2 ? 'selected' : '' }}>
                                        {{ trans('labels.pickup') }}</option>

                                    <option value="3" {{ $editstatus->order_type == 3 ? 'selected' : '' }}>
                                        {{ trans('labels.table') }}</option>
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
                                                <option value="4"
                                                    {{ $editstatus->order_type == 4 ? 'selected' : '' }}>
                                                    {{ trans('labels.pos') }}</option>
                                            @endif
                                        @endif
                                    @else
                                        @if (@helper::checkaddons('pos'))
                                            <option value="4" {{ $editstatus->order_type == 4 ? 'selected' : '' }}>
                                                {{ trans('labels.pos') }}</option>
                                        @endif
                                    @endif
                                </select>


                            </div>
                            <div class="form-group col-md-6">

                                <label class="form-label">{{ trans('labels.name') }}<span class="text-danger"> *
                                    </span></label>

                                <input type="text" class="form-control" name="name" value="{{ $editstatus->name }}"
                                    placeholder="{{ trans('labels.name') }}" required>

                                

                            </div>

                            <div class="mt-3 {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">

                                <a href="{{ URL::to('admin/custom_status') }}"
                                    class="btn btn-danger px-sm-4">{{ trans('labels.cancel') }}</a>

                                <button
                                    class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_custom_status', Auth::user()->role_id, $vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
                                    @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif>{{ trans('labels.save') }}</button>

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
        $('#status_type').on('change',function(){
            $('#type').val($("#status_type :selected").val());
        })
        $('#order_type').on('change',function(){
            $('#order').val($("#order_type :selected").val());
        })
    </script>
@endsection