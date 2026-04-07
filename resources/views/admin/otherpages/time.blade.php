@php
    if(Auth::user()->type == 4){
        $vendor_id = Auth::user()->vendor_id;
    }else{
        $vendor_id = Auth::user()->id;
    }
@endphp

@extends('admin.layout.default')
@section('styles')
    <link rel="stylesheet" href="{{ url('storage/app/public/admin-assets/css/timepicker/jquery.timepicker.min.css') }}">
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{ trans('labels.working_hours') }}</h5>
    </div>
    <div class="card border-0 mt-2 box-shadow">
        <div class="card-body">
            <form action="{{ URL::to('/admin/time/store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-lg-0">
                        <div class="form-group">
                            <label class="form-label">{{ trans('labels.time_interval') }}<span class="text-danger"> *
                                </span></label>
                                <div class="input-group rounded overflow-hidden">
                                    <input type="text" class="form-control {{session()->get('direction') == 2 ? 'input-group-rtl' : ''}} numbers_only" name="interval_time"
                                        placeholder="{{ trans('labels.time_interval') }}" aria-describedby="button-addon2"
                                        value="{{$settingsdata->interval_time}}" required>
                                    <select name="interval_type" class="border border-muted {{session()->get('direction') == 2 ? 'rounded-start' : 'rounded-end'}}" id="">
                                        <option value="1" {{$settingsdata->interval_type == 1 ?'selected' : ''}}>
                                            {{ trans('labels.minute') }}</option>
                                        <option value="2" {{$settingsdata->interval_type == 2 ?'selected' : ''}}>
                                            {{ trans('labels.hour') }}</option>
                                    </select>
                                </div>
                                @error('interval_time')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            
                        </div>
                    </div>
                    <div class="col-md-6 mb-lg-0">
                        <div class="form-group">
                            <label class="form-label">{{ trans('labels.perslot_booking_limit') }}<span
                                    class="text-danger">
                                    * </span></label>
                            <input type="number" class="form-control" name="slot_limit"
                                placeholder="{{ trans('labels.perslot_booking_limit') }}"
                                aria-describedby="button-addon2" value="{{ $settingsdata->per_slot_limit }}"
                                required>

                            @error('slot_limit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                  
                </div>
                <div class="row fs-15 settings-color fw-500">
                    <label class="col-md-2 col-form-label"></label>
                    <label
                        class="col-md-2 text-center mb-0 d-none d-lg-block d-xl-block d-xxl-block form-label">{{ trans('labels.opening_time') }}</label>
                    <label
                        class="col-md-2 text-center mb-0 d-none d-lg-block d-xl-block d-xxl-block form-label">{{ trans('labels.break_start') }}</label>
                    <label
                        class="col-md-2 text-center mb-0 d-none d-lg-block d-xl-block d-xxl-block form-label">{{ trans('labels.break_end') }}</label>
                    <label
                        class="col-md-2 text-center mb-0 d-none d-lg-block d-xl-block d-xxl-block form-label">{{ trans('labels.closing_time') }}</label>
                    <label
                        class="col-md-2 text-center mb-0 d-none d-lg-block d-xl-block d-xxl-block form-label">{{ trans('labels.always_closed') }}</label>
                </div>
                @foreach ($gettime as $time)
                    <div class="row my-2">
                        <div class="form-group col-md-2">
                            <label class="form-label text-center fw-600 fs-15 settings-color">
                                @if (strtolower($time->day) == 'monday')
                                    {{ trans('labels.monday') }}
                                @endif
                                @if (strtolower($time->day) == 'tuesday')
                                    {{ trans('labels.tuesday') }}
                                @endif
                                @if (strtolower($time->day) == 'wednesday')
                                    {{ trans('labels.wednesday') }}
                                @endif
                                @if (strtolower($time->day) == 'thursday')
                                    {{ trans('labels.thursday') }}
                                @endif
                                @if (strtolower($time->day) == 'friday')
                                    {{ trans('labels.friday') }}
                                @endif
                                @if (strtolower($time->day) == 'saturday')
                                    {{ trans('labels.saturday') }}
                                @endif
                                @if (strtolower($time->day) == 'sunday')
                                    {{ trans('labels.sunday') }}
                                @endif
                            </label>
                        </div>

                        <input type="hidden" name="day[]" value="{{ $time->day }}">
                        <div class="form-group col-md-2">
                            <input type="text" class="form-control timepicker"
                                placeholder="{{ trans('labels.opening_time') }}" id="open{{ $time->day }}"
                                name="open_time[]"
                                @if ($time->is_always_close == '2') value="{{ $time->open_time }}" 
                                            @else value="{{ trans('labels.closed') }}" readonly="" @endif>
                        </div>
                        <div class="form-group col-md-2">
                            <input type="text" class="form-control timepicker"
                                placeholder="{{ trans('labels.break_start') }}" id="break_start{{ $time->day }}"
                                name="break_start[]" @if ($time->is_always_close == '2') value="{{ $time->break_start }}" 
                                            @else value="{{ trans('labels.closed') }}" readonly="" @endif>
                        </div>
                        <div class="form-group col-md-2">
                            <input type="text" class="form-control timepicker"
                                placeholder="{{ trans('labels.break_end') }}" id="break_end{{ $time->day }}"
                                name="break_end[]" @if ($time->is_always_close == '2') value="{{ $time->break_end }}" 
                                            @else value="{{ trans('labels.closed') }}" readonly="" @endif>
                        </div>
                        <div class="form-group col-md-2">
                            <input type="text" class="form-control timepicker"
                                placeholder="{{ trans('labels.closing_time') }}" id="close{{ $time->day }}"
                                name="close_time[]"
                                @if ($time->is_always_close == '2') value="{{ $time->close_time }}" 
                                            @else value="{{ trans('labels.closed') }}" readonly="" @endif>
                        </div>
                        <div class="form-group col-md-2">
                            <select class="form-control form-select" name="is_always_close[]"
                                id="is_always_close{{ $time->day }}">
                                <option value="" disabled>{{ trans('labels.select') }}</option>
                                <option value="1" @if ($time->is_always_close == '1') selected @endif>
                                    {{ trans('messages.yes') }}</option>
                                <option value="2" @if ($time->is_always_close == '2') selected @endif>
                                    {{ trans('messages.no') }}</option>
                            </select>
                        </div>
                    </div>
                @endforeach
                <div class="mt-3 {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                    <button
                        @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif
                        class="btn btn-primary px-sm-4 btn-raised {{Auth::user()->type == 4 ? (helper::check_access('role_working_hours', Auth::user()->role_id, $vendor_id, 'add') == 1  ? '':'d-none'): ''}}">{{ trans('labels.save') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ url('storage/app/public/admin-assets/js/timepicker/jquery.timepicker.min.js') }}"></script>
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/workinghours.js') }}"></script>
@endsection