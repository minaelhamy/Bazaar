<div id="email_settings">
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 box-shadow">
                <div class="card-header p-3 bg-secondary">
                    <h5 class="text-capitalize fw-600 settings-color">
                        {{ trans('labels.email_settings') }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ URL::to('/admin/emailsettings') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="form-label">{{ trans('labels.mail_driver') }}<span class="text-danger"> *
                                    </span></label>
                                <input type="text" class="form-control" name="mail_driver"
                                    @if (env('Environment') == 'sendbox') value="*********" @else value="{{ @$settingdata->mail_driver }}" @endif
                                    placeholder="{{ trans('labels.mail_driver') }}" required>

                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label">{{ trans('labels.mail_host') }}<span class="text-danger"> *
                                    </span></label>
                                <input type="text" class="form-control" name="mail_host"
                                    @if (env('Environment') == 'sendbox') value="*********" @else value="{{ @$settingdata->mail_host }}" @endif
                                    placeholder="{{ trans('labels.mail_host') }}" required>

                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label">{{ trans('labels.mail_port') }}<span class="text-danger"> *
                                    </span></label>
                                <input type="text" class="form-control" name="mail_port"
                                    @if (env('Environment') == 'sendbox') value="*********" @else value="{{ @$settingdata->mail_port }}" @endif
                                    placeholder="{{ trans('labels.mail_port') }}" required>

                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label">{{ trans('labels.mail_username') }}<span class="text-danger">
                                        *
                                    </span></label>
                                <input type="text" class="form-control" name="mail_username"
                                    @if (env('Environment') == 'sendbox') value="*********" @else value="{{ @$settingdata->mail_username }}" @endif
                                    placeholder="{{ trans('labels.mail_username') }}" required>

                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label">{{ trans('labels.mail_password') }}<span class="text-danger">
                                        *
                                    </span></label>
                                <input type="text" class="form-control" name="mail_password"
                                    @if (env('Environment') == 'sendbox') value="*********" @else value="{{ @$settingdata->mail_password }}" @endif
                                    placeholder="{{ trans('labels.mail_password') }}" required>

                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label">{{ trans('labels.mail_encryption') }}<span
                                        class="text-danger"> *
                                    </span></label>
                                <input type="text" class="form-control" name="mail_encryption"
                                    @if (env('Environment') == 'sendbox') value="*********" @else  value="{{ @$settingdata->mail_encryption }}" @endif
                                    placeholder="{{ trans('labels.mail_encryption') }}" required>

                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label">{{ trans('labels.mail_fromaddress') }}<span
                                        class="text-danger">
                                        * </span></label>
                                <input type="text" class="form-control" name="mail_fromaddress"
                                    @if (env('Environment') == 'sendbox') value="*********" @else  value="{{ @$settingdata->mail_fromaddress }}" @endif
                                    placeholder="{{ trans('labels.mail_fromaddress') }}" required>

                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label">{{ trans('labels.mail_fromname') }}<span class="text-danger">
                                        *
                                    </span></label>
                                <input type="text" class="form-control" name="mail_fromname"
                                    @if (env('Environment') == 'sendbox') value="*********" @else value="{{ @$settingdata->mail_fromname }}" @endif
                                    placeholder="{{ trans('labels.mail_fromname') }}" required>

                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#testmailmodal"
                                class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">{{ trans('labels.send_test_mail') }}</button>
                            <button type="submit"
                                class="btn btn-primary px-sm-4  {{ Auth::user()->type == 4 ? (helper::check_access('role_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">{{ trans('labels.save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="testmailmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="testmailmodalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ URL::to('/admin/testmail') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header justify-content-between">
                        <h5 class="modal-title text-dark" id="testmailmodalLabel">{{ trans('labels.send_test_mail') }}
                        </h5>
                        <button type="button" class="btn-close m-0" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">{{ trans('labels.email') }}<span class="text-danger"> *
                            </span></label>
                        <input type="text" class="form-control" name="email_address"
                            value="{{ @$settingdata->email_address }}" placeholder="{{ trans('labels.email') }}"
                            required>
                    </div>
                    <div class="modal-footer">
                        <button
                            @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif
                            class="btn btn-secondary px-sm-4">{{ trans('labels.send_test_mail') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
