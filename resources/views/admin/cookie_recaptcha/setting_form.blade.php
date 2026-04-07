<div id="recaptcha">
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 box-shadow">
                <div class="card-header p-3 bg-secondary">
                    <h5 class="text-capitalize fw-600 settings-color">
                        {{ trans('labels.google_recaptcha') }}
                    </h5>
                </div>
                <div class="card-body pb-0">
                    <form method="POST" action="{{ URL::to('admin/settings/updaterecaptcha') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">{{ trans('labels.recaptcha_version') }}
                                        <span class="text-danger"> * </span> </label>
                                    <select class="form-control" name="recaptcha_version" required
                                        id="recaptcha_version">
                                        <option value="">{{ trans('labels.select') }}</option>
                                        <option value="v2"
                                            {{ @$settingdata->recaptcha_version == 'v2' ? 'selected' : '' }}>V2</option>
                                        <option value="v3"
                                            {{ @$settingdata->recaptcha_version == 'v3' ? 'selected' : '' }}>V3</option>
                                    </select>
                                    @error('recaptcha_version')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">{{ trans('labels.google_recaptcha_site_key') }} <span
                                            class="text-danger"> * </span> </label>
                                    <input type="text" class="form-control" name="google_recaptcha_site_key" required
                                        value="{{ @$settingdata->google_recaptcha_site_key }}"
                                        placeholder="{{ trans('labels.google_recaptcha_site_key') }}">
                                    @error('google_recaptcha_site_key')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">{{ trans('labels.google_recaptcha_secret_key') }} <span
                                            class="text-danger"> * </span> </label>
                                    <input type="text" class="form-control" name="google_recaptcha_secret_key"
                                        required value="{{ @$settingdata->google_recaptcha_secret_key }}"
                                        placeholder="{{ trans('labels.google_recaptcha_secret_key') }}">
                                    @error('google_recaptcha_secret_key')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12" id="score_threshold"
                                @if ($settingdata->recaptcha_version == 'v3') @else style="display: none;" @endif>
                                <div class="form-group">
                                    <label class="form-label">{{ trans('labels.score_threshold') }} <span
                                            class="text-danger"> * </span> </label>
                                    <input type="text" class="form-control" name="score_threshold"
                                        value="{{ @$settingdata->score_threshold }}"
                                        placeholder="{{ trans('labels.score_threshold') }}">
                                    <span class="text-muted"><i>reCAPTCHA v3 returns a score (1.0 is very likely a good
                                            interaction, 0.0 is very likely a bot). If the score less than or equal to
                                            this threshold, the form submission will be blocked and the message above
                                            will be displayed.</i><span>
                                            @error('score_threshold')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                </div>
                            </div>
                            <div class="form-group {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                <button
                                    class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
                                    @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif>{{ trans('labels.save') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
