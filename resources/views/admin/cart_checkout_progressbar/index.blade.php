<form action="{{ URL::to('admin/settings/cart_checkout_progressbar') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div id="cart_checkout_progressbar">
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 box-shadow">
                    <div class="d-flex justify-content-between align-items-center card-header p-3 bg-secondary">
                        <h5 class="text-capitalize text-dark fw-600 m-0">
                            {{ trans('labels.cart_checkout_progressbar') }}
                        </h5>
                        <div class="d-flex justify-content-end align-items-center">
                            <input id="cart_checkout_progressbar-switch" type="checkbox" class="checkbox-switch"
                                name="cart_checkout_progressbar" value="1"
                                {{ $settingdata->cart_checkout_progressbar == 1 ? 'checked' : '' }}>
                            <label for="cart_checkout_progressbar-switch" class="switch">
                                <span
                                    class="{{ session()->get('direction') == '2' ? 'switch__circle-rtl' : 'switch__circle' }} switch__circle"><span
                                        class="switch__circle-inner"></span></span>
                                <span
                                    class="switch__left {{ session()->get('direction') == '2' ? ' pe-2' : ' ps-2' }}">{{ trans('labels.off') }}</span>
                                <span
                                    class="switch__right {{ session()->get('direction') == '2' ? ' ps-2' : ' pe-2' }}">{{ trans('labels.on') }}</span>
                            </label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="form-label fs-7 fw-500"
                                    for="progress_message">{{ trans('labels.progress_message') }}</label>
                                <span class="text-danger">*</span>
                                <p class="text-muted">{{ trans('labels.progress_message_info') }}
                                </p>
                                <textarea class="form-control" name="progress_message" placeholder="{{ trans('labels.progress_message') }}" required>{{ $settingdata->progress_message }}</textarea>
                                @error('progress_message')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label fs-7 fw-500"
                                    for="progress_message_end">{{ trans('labels.progress_message_end') }}</label>
                                <span class="text-danger">*</span>
                                <p class="text-muted">{{ trans('labels.progress_message_end_info') }}
                                </p>
                                <textarea class="form-control" name="progress_message_end" placeholder="{{ trans('labels.progress_message_end') }}"
                                    required>{{ $settingdata->progress_message_end }}</textarea>
                                @error('progress_message_end')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                            <button
                                @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif
                                class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">
                                {{ trans('labels.save') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
