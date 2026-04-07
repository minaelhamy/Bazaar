<div class="modal fade age_modal" id="age_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="age_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header justify-content-between">
                {{-- <div class="d-flex border-bottom"> --}}
                <h5 class="modal-title fw-600 color-changer d-flex align-items-center gap-2" id="age_modalLabel"><span
                        class="number-verification">{{ @helper::getagedetails($storeinfo->id)->min_age }}+</span>
                    {{ trans('labels.age_verification') }}</h5>
                <button type="button" class="bg-transparent border-0 m-0" onclick="ageverificationclose()">
                    <i class="fa-regular fa-xmark fs-4 color-changer"></i>
                </button>
                {{-- </div> --}}
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <div class="alert alert-danger p-2 fs-7" style="display: none;" id="age-alert" role="alert">
                        {{ trans('labels.age_alert') }}
                    </div>
                    <p class="mt-2 fs-15 color-changer">{{ trans('labels.age_verification_text') }}</p>
                </div>

                <input type="hidden" id="popup_type" value="{{ @helper::getagedetails($storeinfo->id)->popup_type }}">
                <input type="hidden" id="min_age" value="{{ @helper::getagedetails($storeinfo->id)->min_age }}">
                <div class="row g-3">
                    @if (@helper::getagedetails($storeinfo->id)->popup_type == 2)
                        <div class="form-group col-sm-4">
                            <input type="number" inputmode="numeric" name="dd" id="dd" placeholder="DD"
                                class="form-control p-2 fs-7" value="">
                            <span class="text-danger" id="dd-required"
                                style="display: none;">{{ trans('labels.required') }}</span>
                        </div>
                        <div class="form-group col-sm-4">
                            <input type="number" inputmode="numeric" name="mm" id="mm" placeholder="MM"
                                class="form-control p-2 fs-7" value="">
                            <span class="text-danger" id="mm-required"
                                style="display: none;">{{ trans('labels.required') }}</span>
                        </div>
                        <div class="form-group col-sm-4">
                            <input type="number" inputmode="numeric" name="yyyy" id="yyyy" placeholder="YYYY"
                                class="form-control p-2 fs-7" value="">
                            <span class="text-danger" id="yyyy-required"
                                style="display: none;">{{ trans('labels.required') }}</span>
                        </div>
                    @endif
                    @if (@helper::getagedetails($storeinfo->id)->popup_type == 3)
                        <div class="col-md-12">
                            <input type="number" inputmode="numeric" name="age" id="age"
                                class="form-control p-2 fs-7" value=""
                                placeholder="{{ trans('labels.enter_age') }}">
                            <span class="text-danger" id="age-required"
                                style="display: none;">{{ trans('labels.required') }}</span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer p-3">
                <div class="col-12 m-0">
                    <div class="row g-3">
                        <div class="col-6">
                            <button type="button" onclick="ageverificationcancel()"
                                class="btn btn-age-outline m-0 w-100 px-0">{{ trans('labels.cancel') }}</button>
                        </div>
                        <div class="col-6">
                            <button type="button" onclick="ageverification()" class="btn btn-age m-0 w-100 px-0">
                                @if (@helper::getagedetails($storeinfo->id)->popup_type == 1)
                                    {{ trans('labels.yes_i_am') }}
                                    {{ @helper::getagedetails($storeinfo->id)->min_age }}
                                @else
                                    {{ trans('labels.yes') }}
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
