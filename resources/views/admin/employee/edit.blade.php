@extends('admin.layout.default')

@section('content')
<div class="d-flex justify-content-between align-items-center">
    <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{trans('labels.edit')}}</h5>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item text-dark"><a href="{{URL::to('admin/employees')}}" class="color-changer">{{trans('labels.employees')}}</a></li>
            <li class="breadcrumb-item active {{session()->get('direction') == 2 ? 'breadcrumb-rtl' : ''}}" aria-current="page">{{trans('labels.edit')}}</li>
        </ol>
    </nav>
</div>
    <div class="row mt-3">

        <div class="col-12">

            <div class="card border-0 box-shadow">

                <div class="card-body">

                    <form action="{{ URL::to('/admin/employees/update-'.$editemployee->id) }}" method="post"
                        enctype="multipart/form-data">

                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">

                                <input type="hidden" value="{{ $editemployee->id }}" name="id">

                                <label class="form-label">{{ trans('labels.name') }}<span class="text-danger"> *
                                    </span></label>

                                <input type="text" class="form-control" name="name" value="{{ $editemployee->name }}"
                                    placeholder="name" required>

                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>

                            <div class="form-group col-md-6">

                                <label class="form-label">{{ trans('labels.email') }}<span class="text-danger"> *
                                    </span></label>

                                <input type="email" class="form-control" name="email" value="{{ $editemployee->email }}"
                                    placeholder="email" required>

                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>
                            <div class="col-md-6">
                                <div class="form-group ">

                                    <label class="form-label">{{ trans('labels.mobile') }}<span class="text-danger"> *
                                        </span></label>

                                    <input type="number" class="form-control" name="mobile" value="{{ $editemployee->mobile }}"
                                        placeholder="mobile" required>

                                    @error('mobile')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>
                               
                            </div>
                            <div class="col-md-6 form-group">

                                <label class="form-label">{{ trans('labels.image') }} (250 x 250) </label>

                                <input type="file" class="form-control" name="profile">

                                <img class="rounded-circle mt-2" src="{{ helper::image_path($editemployee->image) }}"
                                    alt="" width="70" height="70">

                                @error('profile')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>
                            <div class="form-group col-md-6">
                                <label for="role" class="form-label">{{ trans('labels.role') }}<span
                                        class="text-danger"> * </span></label>
                                <select name="role" class="form-select" id="role" required>
                                    <option value="">{{ trans('labels.select') }}</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ $role->id == $editemployee->role_id ? 'selected' : '' }}>{{ $role->role }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                          
                           
                            <div class="mt-3 {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">

                                <a href="{{ URL::to('admin/employees') }}"
                                    class="btn btn-danger px-sm-4">{{ trans('labels.cancel') }}</a>

                                <button
                                    @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif
                                    class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_employees', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">{{ trans('labels.save') }}</button>

                            </div>

                        </div>



                    </form>

                </div>

            </div>

        </div>

    </div>
@endsection

