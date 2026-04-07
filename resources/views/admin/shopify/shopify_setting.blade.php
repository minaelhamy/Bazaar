<div id="shopify_settings">
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 box-shadow">
                <div class="card-header p-3 bg-secondary">
                    <h5 class="text-capitalize fw-600 text-dark">
                        {{ trans('labels.shopify_settings') }}
                    </h5>
                </div>
                <div class="card-body">
                    {{-- <div class="d-flex align-items-center mb-3">
                    <h5 class="text-capitalize fw-600 text-dark"></h5>
                </div> --}}
                    <form action="{{ URL::to('admin/settings/shopify_settings') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="shopify_store_url"
                                    class="form-label">{{ trans('labels.shopify_store_url') }} </label>
                                <input type="text" class="form-control" name="shopify_store_url"
                                    placeholder="{{ trans('labels.shopify_store_url') }}"
                                    value="{{ @$settingdata->shopify_store_url }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="shopify_access_token"
                                    class="form-label">{{ trans('labels.shopify_access_token') }} </label>
                                <input type="text" class="form-control" name="shopify_access_token"
                                    placeholder="{{ trans('labels.shopify_access_token') }}"
                                    value="{{ @$settingdata->shopify_access_token }}" required>
                            </div>
                        </div>
                        <div class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                            <button
                                @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif
                                class="btn btn-primary px-sm-4  {{ Auth::user()->type == 4 ? (helper::check_access('role_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">{{ trans('labels.save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
