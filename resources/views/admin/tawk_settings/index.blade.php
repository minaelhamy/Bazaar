<form action="{{ URL::to('admin/settings/tawk_settings') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div id="tawk_settings">
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 box-shadow">
                    <div class="card-header p-3 bg-secondary">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="text-capitalize fw-600 settings-color">{{ trans('labels.tawk_settings') }}</h5>
                            <div>
                                <div class="text-center">
                                    <input id="tawk_on_off" type="checkbox" class="checkbox-switch" name="tawk_on_off"
                                        value="1" {{ $settingdata->tawk_on_off == 1 ? 'checked' : '' }}>
                                    <label for="tawk_on_off" class="switch">
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
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-sm-23">
                                <label class="form-label">{{ trans('labels.widget_id') }}<span class="text-danger"> *
                                    </span></label>
                                <textarea class="form-control" name="widget_id" rows="10" placeholder="{{ trans('labels.widget_id') }}" required>{{ @$settingdata->tawk_widget_id }}</textarea>
                            </div>
                        </div>
                        <div class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                            <button
                                @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif
                                class="btn btn-primary px-sm-4  {{ Auth::user()->type == 4 ? (helper::check_access('role_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">
                                {{ trans('labels.save') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
