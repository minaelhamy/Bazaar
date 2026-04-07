<form action="{{ URL::to('admin/settings/product_fake_view') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div id="product_fake_view">
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 box-shadow">
                    <div class="d-flex align-items-center card-header p-3 bg-secondary">
                        <h5 class="col-md-6 fw-600 text-dark">
                            {{ trans('labels.product_fake_view') }}
                        </h5>
                        <div class="col-md-6 d-flex justify-content-end align-items-center">
                            <input id="product_fake_view-switch" type="checkbox" class="checkbox-switch"
                                name="product_fake_view" value="1"
                                {{ $settingdata->product_fake_view == 1 ? 'checked' : '' }}>
                            <label for="product_fake_view-switch" class="switch">
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
                                    for="fake_view_message">{{ trans('labels.fake_view_message') }}</label>
                                <span class="text-danger">*</span>
                                <p class="text-muted">{{ trans('labels.fake_view_message_line_1') }} <br>
                                    - {eye} {{ trans('labels.fake_view_message_line_2') }} <br>
                                    - {count} {{ trans('labels.fake_view_message_line_3') }} <br>
                                </p>
                                <textarea class="form-control" name="fake_view_message" placeholder="{{ trans('labels.fake_view_message') }}" required>{{ $settingdata->fake_view_message }}</textarea>
                                @error('fake_view_message')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label fs-7 fw-500"
                                        for="min_view_count">{{ trans('labels.min_view_count') }}</label>
                                    <span class="text-danger">*</span>
                                    <p class="text-muted">{{ trans('labels.min_view_count_info') }}</p>
                                    <input type="number" min="1" class="form-control" name="min_view_count"
                                        id="min_view_count" value="{{ $settingdata->min_view_count }}" required>
                                    @error('min_view_count')
                                        <span class="text-danger">{{ $message }}</span><br>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label fs-7 fw-500"
                                        for="max_view_count">{{ trans('labels.max_view_count') }} </label>
                                    <span class="text-danger">*</span>
                                    <p class="text-muted">{{ trans('labels.max_view_count_info') }}</p>
                                    <input type="number" min="1" class="form-control" name="max_view_count"
                                        id="max_view_count" value="{{ $settingdata->max_view_count }}" required>
                                    @error('max_view_count')
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
