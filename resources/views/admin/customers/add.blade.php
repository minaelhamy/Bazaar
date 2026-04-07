@extends('admin.layout.default')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{ trans('labels.add_new') }}</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item text-dark"><a href="{{ URL::to('admin/users') }}" class="color-changer">{{ trans('labels.customers') }}</a>
                </li>
                <li class="breadcrumb-item active {{ session()->get('direction') == 2 ? 'breadcrumb-rtl' : '' }}"
                    aria-current="page">{{ trans('labels.add') }}</li>
            </ol>
        </nav>
    </div>
    <div class="col-12">
        <div class="card border-0 box-shadow">
            <div class="card-body">
                <form action="{{ URL::to('admin/customers/save') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="name" class="form-label">{{ trans('labels.name') }}<span
                                    class="text-danger">
                                    * </span></label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                id="name" placeholder="{{ trans('labels.name') }}" required>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email" class="form-label">{{ trans('labels.email') }}<span
                                    class="text-danger"> * </span></label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                id="email" placeholder="{{ trans('labels.email') }}" required>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="mobile" class="form-label">{{ trans('labels.mobile') }}<span
                                    class="text-danger"> * </span></label>
                            <input type="text" class="form-control mobile-number" name="mobile"
                                value="{{ old('mobile') }}" id="mobile" placeholder="{{ trans('labels.mobile') }}"
                                required>
                            @error('mobile')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password" class="form-label">{{ trans('labels.password') }}<span
                                    class="text-danger"> * </span></label>
                            <input type="password" class="form-control" name="password" value="{{ old('password') }}"
                                id="password" placeholder="{{ trans('labels.password') }}" required>
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-3 {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                        <a href="{{ URL::to('admin/customers') }}"
                            class="btn btn-danger px-sm-4">{{ trans('labels.cancel') }}</a>
                        <button class="btn btn-primary px-sm-4"
                            @if (env('Environment') == 'sendbox') type="button"
                        onclick="myFunction()" @else type="submit" @endif>{{ trans('labels.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
