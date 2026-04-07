<div class="row mb-5">
    <div class="col-12">
        <div class="card border-0 box-shadow">
            <form action="{{ URL::to('/admin/google_login') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-header rounded-top p-3 bg-secondary">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="text-capitalize fw-600 settings-color">{{ trans('labels.google_login') }}</h5>
                        <div class="form-group m-0">
                            <input id="google-switch" type="checkbox" class="checkbox-switch" name="google_mode"
                                value="1" {{ $settingdata->google_mode == 1 ? 'checked' : '' }}>
                            <label for="google-switch" class="switch">
                                <span
                                    class="{{ session()->get('direction') == 2 ? 'switch__circle-rtl' : 'switch__circle' }}"><span
                                        class="switch__circle-inner"></span></span>
                                <span
                                    class="switch__left {{ session()->get('direction') == 2 ? 'pe-2' : 'ps-2' }}">{{ trans('labels.off') }}</span>
                                <span
                                    class="switch__right {{ session()->get('direction') == 2 ? 'ps-2' : 'pe-2' }}">{{ trans('labels.on') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="form-label">{{ trans('labels.google_client_id') }}<span class="text-danger"> *
                                </span></label>
                            <input type="text" class="form-control" name="google_client_id" pattern="*"
                                value="{{ @$settingdata->google_client_id }}"
                                placeholder="{{ trans('labels.google_client_id') }}" required>
                            @error('google_client_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label">{{ trans('labels.google_client_secret') }}<span
                                    class="text-danger"> *
                                </span></label>
                            <input type="text" class="form-control" name="google_client_secret" pattern="*"
                                value="{{ @$settingdata->google_client_secret }}"
                                placeholder="{{ trans('labels.google_client_secret') }}" required>
                            @error('google_client_secret')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="form-label">{{ trans('labels.google_redirect_url') }}<span
                                    class="text-danger"> *
                                </span></label>
                            <input type="text" class="form-control" name="google_redirect_url" pattern="*"
                                value="{{ @$settingdata->google_redirect_url }}"
                                placeholder="{{ trans('labels.google_redirect_url') }}" required>
                            @error('google_redirect_url')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <div class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                        <button
                            @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif
                            class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">{{ trans('labels.save') }}</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
