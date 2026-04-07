<div id="pwa">
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 box-shadow">
                <div class="card-header p-3 bg-secondary">
                    <h5 class="text-capitalize fw-600 settings-color">{{ trans('labels.pwa_settings') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        
                    </div>
                    <form method="POST" action="{{ URL::to('admin/pwasettings') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="form-label" for="">{{ trans('labels.pwa') }} </label>
                                <input id="pwa-switch" type="checkbox" class="checkbox-switch"
                                    name="pwa" value="1"
                                    {{ $settingdata->pwa == 1 ? 'checked' : '' }}>
                                <label for="pwa-switch" class="switch">
                                    <span
                                        class="{{ session()->get('direction') == 2 ? 'switch__circle-rtl' : 'switch__circle' }}"><span
                                            class="switch__circle-inner"></span></span>
                                    <span
                                        class="switch__left {{ session()->get('direction') == 2 ? 'pe-2' : 'ps-2' }}">{{ trans('labels.off') }}</span>
                                    <span
                                        class="switch__right {{ session()->get('direction') == 2 ? 'ps-2' : 'pe-2' }}">{{ trans('labels.on') }}</span>
                                </label>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label">{{ trans('labels.app_logo') }}  <small>(512 x 512)</small></label>
                                <input type="file" class="form-control"
                                    name="app_logo">
                                @error('app_logo')
                                    <small class="text-danger">{{ $message }}</small>
                                    <br>
                                @enderror
                                <img class="img-fluid rounded hw-70 mt-1 object-fit-contain"
                                    src="{{ helper::image_path(@$settingdata->app_logo) }}"
                                    alt="">
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label">{{ trans('labels.app_title') }} <span
                                    class="text-danger"> * </span></label>
                                <input type="text" class="form-control"
                                    name="app_title" value="{{ $settingdata->app_title }}" placeholder="{{  trans('labels.app_title') }}" required>
                            </div> 
                            <div class="form-group col-sm-6">
                                <label class="form-label">{{ trans('labels.app_name') }} <span
                                    class="text-danger"> * </span></label>
                                <input type="text" class="form-control"
                                    name="app_name" value="{{ $settingdata->app_name }}" placeholder="{{ trans('labels.app_name') }}" required>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label">{{ trans('labels.background_color') }}</label>
                                <input type="color" class="form-control form-control-color w-100 border-0"
                                    name="background_color" value="{{ $settingdata->background_color }}">
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label">{{ trans('labels.theme_color') }}</label>
                                <input type="color" class="form-control form-control-color w-100 border-0"
                                    name="theme_color" value="{{ $settingdata->theme_color }}">
                            </div>
                        </div>
                        <div class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                            <button
                                @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif
                                class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_basic_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">{{ trans('labels.save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
