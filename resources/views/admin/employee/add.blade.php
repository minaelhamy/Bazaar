@extends('admin.layout.default')
@section('content')
<div class="d-flex justify-content-between align-items-center">
    <h5 class="text-capitalize fw-600 color-changer fs-4 text-dark">{{trans('labels.add_new')}}</h5>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item text-dark"><a href="{{URL::to('admin/employees')}}" class="color-changer">{{trans('labels.employees')}}</a></li>
            <li class="breadcrumb-item active {{session()->get('direction') == 2 ? 'breadcrumb-rtl' : ''}}" aria-current="page">{{trans('labels.add')}}</li>
        </ol>
    </nav>
</div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card border-0 box-shadow">
                <div class="card-body">
                    <form action="{{ URL::to('/admin/employees/save') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="form-group col-6">
                                <label for="name" class="form-label">{{ trans('labels.name') }}<span class="text-danger"> *
    
                                    </span></label>
    
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}"
    
                                   placeholder="{{ trans('labels.name') }}" required>
    
                                @error('name')
    
                                    <span class="text-danger">{{ $message }}</span>
    
                                @enderror
    
                            </div>
    
                            <div class="form-group col-6">
    
                                <label for="email" class="form-label">{{ trans('labels.email') }}<span class="text-danger"> *
    
                                    </span></label>
    
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}"
    
                                     placeholder="{{ trans('labels.email') }}" required>
    
                                @error('email')
    
                                    <span class="text-danger">{{ $message }}</span>
    
                                @enderror
    
                            </div>
    
                            <div class="form-group col-6">
    
                                <label for="mobile" class="form-label">{{ trans('labels.mobile') }}<span class="text-danger">
    
                                        * </span></label>
    
                                <input type="number" class="form-control" name="mobile" value="{{ old('mobile') }}"  placeholder="{{ trans('labels.mobile') }}" required>
    
                                @error('mobile')
    
                                    <span class="text-danger">{{ $message }}</span>
    
                                @enderror
    
                            </div>
    
                            <div class="form-group col-6">
    
                                <label for="password" class="form-label">{{ trans('labels.password') }}<span
    
                                        class="text-danger"> * </span></label>
    
                                <input type="password" class="form-control" name="password" value="{{ old('password') }}"
    
                                   placeholder="{{ trans('labels.password') }}" required>
    
                                @error('password')
    
                                    <span class="text-danger">{{ $message }}</span>
    
                                @enderror
    
                            </div>
                            <div class="form-group col-6">
                                <label for="role" class="form-label">{{ trans('labels.role') }}<span
                                        class="text-danger"> * </span></label>
                                <select name="role" class="form-select" id="role" required>
                                    <option value="">{{trans('labels.select')}}</option>
                                    @foreach ($roles as $role)
                                        <option value="{{$role->id}}">{{$role->role}}</option>
                                    @endforeach
                                </select>
                                
                            </div>
                            
                        </div>
                      
                        <div class="mt-3 {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                            <a href="{{URL::to('admin/employees')}}" class="btn btn-danger px-sm-4">{{ trans('labels.cancel') }}</a>
                            <button @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_employees', Auth::user()->role_id, Auth::user()->vendor_id, 'add') == 1 ? '' : 'd-none') : '' }}">{{ trans('labels.save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

