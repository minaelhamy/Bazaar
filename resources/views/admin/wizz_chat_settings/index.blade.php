<form action="{{ URL::to('admin/settings/wizz_chat_settings') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div id="wizz_chat_settings">
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 box-shadow">
                    <div class="card-header p-3 bg-secondary">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="text-capitalize fw-600 settings-color">{{ trans('labels.wizz_chat_settings') }}
                            </h5>
                            <div>
                                <div class="text-center">
                                    <input id="wizz_chat_on_off" type="checkbox" class="checkbox-switch"
                                        name="wizz_chat_on_off" value="1"
                                        {{ $settingdata->wizz_chat_on_off == 1 ? 'checked' : '' }}>
                                    <label for="wizz_chat_on_off" class="switch">
                                        <span
                                            class="{{ session()->get('direction') == 2 ? 'switch__circle-rtl' : 'switch__circle' }}">
                                            <span class="switch__circle-inner"></span>
                                        </span>
                                        <span
                                            class="switch__left {{ session()->get('direction') == 2 ? 'pe-2' : 'ps-2' }}">
                                            {{ trans('labels.off') }}
                                        </span>
                                        <span
                                            class="switch__right {{ session()->get('direction') == 2 ? 'ps-2' : 'pe-2' }}">
                                            {{ trans('labels.on') }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="form-label">{{ trans('labels.wizz_chat_info') }} <a
                                        href="https://app.wizzchat.com/"
                                        target="_blank">{{ trans('labels.click_here') }}</a></label>
                                <textarea class="form-control" name="wizz_chat_settings" placeholder="<script type=&quot;text/javascript&quot;></script>" required>{{ @$settingdata->wizz_chat_settings }}</textarea>
                            </div>

                        </div>
                        <div class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                            <button
                                @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif
                                class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">{{ trans('labels.save') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
