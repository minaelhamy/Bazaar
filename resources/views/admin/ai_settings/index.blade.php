<div id="ai_settings">
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 box-shadow">
                <div class="card-header p-3 bg-secondary">
                    <h5 class="text-capitalize fw-600">
                        {{ trans('labels.ai_settings') }}
                    </h5>
                </div>
                <div class="card-body">
                    <form class="form-body" action="{{ URL::to('admin/ai_settings/update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">{{ trans('labels.gemini_api_key') }} <span
                                            class="text-danger"> * </span> </label>
                                    <input type="text" class="form-control" name="gemini_api_key" required
                                        value="{{ @$othersettingdata->gemini_api_key }}"
                                        placeholder="{{ trans('labels.gemini_api_key') }}">
                                    @error('gemini_api_key')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">{{ trans('labels.gemini_version') }} <span
                                            class="text-danger"> * </span> </label>
                                    <input type="text" class="form-control mb-1" name="gemini_version" required
                                        value="{{ @$othersettingdata->gemini_version }}"
                                        placeholder="{{ trans('labels.gemini_version') }}">
                                    <small>
                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                            data-bs-target="#geminiHelpModal" style="text-decoration: underline;">
                                            How to get Gemini API Key & Version?
                                        </a>
                                    </small>
                                    @error('gemini_version')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div
                                class="form-group {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
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

<!-- Gemini Help Modal -->
<div class="modal fade" id="geminiHelpModal" tabindex="-1" aria-labelledby="geminiHelpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="geminiHelpModalLabel">{{ trans('labels.how_to_get_gemini_API_key_version') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="container-fluid">

                    <div class="p-3 mb-3 border rounded bg-light">
                        <h6 class="fw-bold mb-2">{{ trans('labels.step_1_open_google_AI_studio') }}</h6>
                        <p class="mb-0">
                            {{ trans('labels.go_to') }}
                            <a href="https://aistudio.google.com/" target="_blank">{{ trans('labels.google_AI_studio') }}</a>
                            {{ trans('labels.and_sign_in_with_your_google_account') }}.
                        </p>
                    </div>

                    <div class="p-3 mb-3 border rounded">
                        <h6 class="fw-bold mb-2"> {{ trans('labels.step_2_generate_the_gemini_API_key') }}</h6>
                        <ul class="mb-0 ps-3">
                            <li>{{ trans('labels.click_on_dashboard_from_the_left_sidebar') }}.</li>
                            <li>{{ trans('labels.select_API_keys') }}.</li>
                            <li>{{ trans('labels.click_create_API_key') }}.</li>
                            <li>{{ trans('labels.copy_the_generated_API_key') }}.</li>
                        </ul>
                    </div>

                    <div class="p-3 mb-3 border rounded bg-light">
                        <h6 class="fw-bold mb-2">{{ trans('labels.step_3_get_the_gemini_model_version') }}</h6>
                        <ul class="mb-0 ps-3">
                            <li>{{ trans('labels.click_the_home_option_in_the_left_sidebar') }}.</li>
                            <li>{{ trans('labels.on_the_right_side_you_will_see_the_model_version_list') }}.</li>
                            <li>{{ trans('labels.select_the_gemini_model_you_want_to_use') }}.</li>
                        </ul>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('labels.close') }} </button>
            </div>

        </div>
    </div>
</div>
