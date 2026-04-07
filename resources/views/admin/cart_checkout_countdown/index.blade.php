<form action="{{ URL::to('admin/settings/cart_checkout_countdown') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div id="cart_checkout_countdown">
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 box-shadow">
                    <div class="d-flex justify-content-between align-items-center card-header p-3 bg-secondary">
                        <h5 class="text-capitalize text-dark fw-600 m-0">
                            {{ trans('labels.cart_checkout_countdown') }}
                        </h5>
                        <div class="d-flex justify-content-end align-items-center">
                            <input id="cart_checkout_countdown-switch" type="checkbox" class="checkbox-switch"
                                name="cart_checkout_countdown" value="1"
                                {{ $settingdata->cart_checkout_countdown == 1 ? 'checked' : '' }}>
                            <label for="cart_checkout_countdown-switch" class="switch">
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
                                    for="countdown_message">{{ trans('labels.countdown_message') }}</label>
                                <span class="text-danger">*</span>
                                <p class="text-muted">{{ trans('labels.countdown_message_info') }}
                                </p>
                                <textarea class="form-control" name="countdown_message" placeholder="{{ trans('labels.countdown_message') }}" required>{{ $settingdata->countdown_message }}</textarea>
                                @error('countdown_message')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label fs-7 fw-500"
                                    for="countdown_expired_message">{{ trans('labels.countdown_expired_message') }}</label>
                                <span class="text-danger">*</span>
                                <p class="text-muted">{{ trans('labels.countdown_expired_message_info') }}
                                </p>
                                <textarea class="form-control" name="countdown_expired_message"
                                    placeholder="{{ trans('labels.countdown_expired_message') }}" required>{{ $settingdata->countdown_expired_message }}</textarea>
                                @error('countdown_expired_message')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label fs-7 fw-500"
                                        for="countdown_mins">{{ trans('labels.countdown_mins') }} </label>
                                    <span class="text-danger">*</span>
                                    <input type="number" min="1" max="10" class="form-control"
                                        name="countdown_mins" id="countdown_mins"
                                        value="{{ $settingdata->countdown_mins }}" required>
                                    @error('countdown_mins')
                                        <span class="text-danger">{{ $message }}</span><br>
                                    @enderror
                                </div>
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
