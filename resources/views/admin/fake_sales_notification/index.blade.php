<form action="{{ URL::to('admin/settings/fake_sales_notification') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div id="fake_sales_notification">
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 box-shadow">
                    <div class="d-flex align-items-center card-header p-3 bg-secondary">
                        <h5 class="col-md-6 fw-600 text-dark">
                            {{ trans('labels.fake_sales_notification') }}
                        </h5>
                        <div class="col-md-6 d-flex justify-content-end align-items-center">
                            <input id="fake_sales_notification-switch" type="checkbox" class="checkbox-switch"
                                name="fake_sales_notification" value="1"
                                {{ $settingdata->fake_sales_notification == 1 ? 'checked' : '' }}>
                            <label for="fake_sales_notification-switch" class="switch">
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
                            <div class="col-md-6 form-group">
                                <p class="form-label">
                                    {{ trans('labels.sales_notification_position') }}
                                </p>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input form-check-input-secondary" type="radio"
                                        name="sales_notification_position" id="saleradio" value="1"
                                        {{ @$settingdata->sales_notification_position == '1' ? 'checked' : '' }} />
                                    <label for="saleradio" class="form-check-label">{{ trans('labels.left') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input form-check-input-secondary" type="radio"
                                        name="sales_notification_position" id="saleradio1" value="2"
                                        {{ @$settingdata->sales_notification_position == '2' ? 'checked' : '' }} />
                                    <label for="saleradio1"
                                        class="form-check-label">{{ trans('labels.right') }}</label>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label fs-7 fw-500"
                                    for="product_source">{{ trans('labels.product_source') }}</label>
                                <span class="text-danger">*</span>
                                <select class="form-control" name="product_source" id="product_source" required>
                                    <option value="">{{ trans('labels.select') }}</option>
                                    <option value="1" {{ $settingdata->product_source == '1' ? 'selected' : '' }}>
                                        {{ trans('labels.all_random_product') }}</option>
                                    <option value="2" {{ $settingdata->product_source == '2' ? 'selected' : '' }}>
                                        {{ trans('labels.all_random_orders') }}</option>
                                </select>
                                @error('product_source')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label fs-7 fw-500"
                                        for="next_time_popup">{{ trans('labels.next_time_popup') }}</label>
                                    <span class="text-danger">*</span>
                                    <p class="text-muted">{{ trans('labels.next_time_popup_info') }}</p>
                                    <input type="number" min="1" class="form-control" name="next_time_popup"
                                        id="next_time_popup" value="{{ $settingdata->next_time_popup }}" required>
                                    @error('next_time_popup')
                                        <span class="text-danger">{{ $message }}</span><br>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label fs-7 fw-500"
                                        for="notification_display_time">{{ trans('labels.notification_display_time') }}
                                    </label>
                                    <span class="text-danger">*</span>
                                    <p class="text-muted">{{ trans('labels.next_time_popup_info') }}</p>
                                    <input type="number" min="1" class="form-control"
                                        name="notification_display_time" id="notification_display_time"
                                        value="{{ $settingdata->notification_display_time }}" required>
                                    @error('notification_display_time')
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
