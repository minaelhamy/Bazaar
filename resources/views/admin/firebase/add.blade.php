@extends('admin.layout.default')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="text-capitalize fw-600 text-dark fs-4">{{ trans('labels.add_new') }}</h5>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ URL::to('admin/notification') }}">{{ trans('labels.firebase_notification') }}</a></li>
            <li class="breadcrumb-item active {{session()->get('direction') == 2 ? 'breadcrumb-rtl' : ''}}" aria-current="page">{{ trans('labels.add') }}</li>
        </ol>
    </nav>
</div>
        <div class="row">
            <div class="col-12">
                <div class="card border-0 box-shadow">
                    <div class="card-body">
                        <form action="{{URL::to('/admin/notification/save')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                 <div class="form-group">
                                    <label class="form-label">{{trans('labels.title')}}<span class="text-danger"> * </span></label>
                                    <input type="text" name="title" class="form-control" required>
                                    @error('image')
                                    <span class="text-danger">{{ $message }}</span> 
                                 @enderror
                                </div>
                            </div>
                            <div class="row">
                                 <div class="form-group">
                                    <label class="form-label">{{trans('labels.sub_title')}}<span class="text-danger"> * </span></label>
                                    <textarea name="sub_title" rows="5" class="form-control" required></textarea>
                                </div>
                            </div>
                            <div class="form-group {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                <a href="{{URL::to('admin/notification')}}" class="btn btn-danger px-sm-4">{{ trans('labels.cancel') }}</a>
                                <button @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif class="btn btn-primary px-sm-4 {{Auth::user()->type == 4 ? (helper::check_access('role_firebase_notification',Auth::user()->role_id,Auth::user()->vendor_id,'add') == 1 ? '' : 'd-none') : '' }}">{{ trans('labels.save') }}</button>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection