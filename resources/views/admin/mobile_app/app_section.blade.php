<div id="app_section">
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 box-shadow">
                <div class="card-header p-3 bg-secondary">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="text-capitalize fw-600 settings-color">{{ trans('labels.app_section') }}</h5>
                        @if (Auth::user()->type == 1)
                            <input id="mobile_app-switch" type="checkbox" class="checkbox-switch" name="mobile_app_on_off"
                                value="1" {{ @$app->mobile_app_on_off == 1 ? 'checked' : '' }}>
                            <label for="mobile_app-switch" class="switch">
                                <span
                                    class="{{ session()->get('direction') == 2 ? 'switch__circle-rtl' : 'switch__circle' }}"><span
                                        class="switch__circle-inner"></span></span>
                                <span
                                    class="switch__left {{ session()->get('direction') == 2 ? 'pe-2' : 'ps-2' }}">{{ trans('labels.off') }}</span>
                                <span
                                    class="switch__right {{ session()->get('direction') == 2 ? 'ps-2' : 'pe-2' }}">{{ trans('labels.on') }}</span>
                            </label>
                        @endif
                    </div>
                </div>
                <div class="card-body pb-0">
                    <form action="{{ URL::to('admin/app_section/update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label">{{ trans('labels.android_link') }}</label>
                                <input type="text" class="form-control" name="android_link"
                                    value="{{ @$app->android_link }}" placeholder="{{ trans('labels.android_link') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">{{ trans('labels.ios_link') }} </label>
                                <input type="text" class="form-control" name="ios_link"
                                    value="{{ @$app->ios_link }}" placeholder="{{ trans('labels.ios_link') }}">
                            </div>
                            @if (Auth::user()->type == 1)
                                <div class="form-group col-md-6">
                                    <label class="form-label">{{ trans('labels.image') }} </label>
                                    <input type="file" class="form-control" name="image">
                                    <img class="img-fluid rounded hw-70 mt-1 object-fit-cover"
                                        src="{{ helper::image_Path(@$app->image) }}" alt="">
                                </div>
                            @endif
                            <div class="form-group {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                <button class="btn btn-primary px-sm-4"
                                    @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif>{{ trans('labels.save') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
