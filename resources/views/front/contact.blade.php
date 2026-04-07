@include('front.theme.header')

<section class="breadcrumb-sec bg-change-mode">

    <div class="container">

        <nav aria-label="breadcrumb">

            <ol class="breadcrumb">

                <li class="breadcrumb-item text-dark"><a class="text-dark color-changer"
                        href="{{ helper::storefront_url($storeinfo, '/') }}">{{ trans('labels.home') }}</a>
                </li>
                <li class="text-muted breadcrumb-item {{ session()->get('direction') == 2 ? 'rtl' : '' }} active"
                    aria-current="page">{{ trans('labels.contact_us') }}</li>
            </ol>
        </nav>
    </div>
</section>

<div class="container">
    <div class="row mb-5">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="card h-100 border-0 border-0 box-shadow">
                <div class="card-body">
                    <div class="mb-4 text-center">
                        <h3 class="fw-semibold color-changer">{{ trans('labels.contact_title') }}</h3>
                    </div>
                    <!-- email -->
                    <a href="mailto:{{ helper::appdata($storeinfo->id)->email }}" class="text-dark color-changer">
                        <div class="bg-light bg-change-mode rounded p-3 mb-4 steps-box">
                            <span class="contact-icon p-3">
                                <i class="fa-light fa-envelope fs"></i>
                            </span>
                            <div class="mx-3">
                                <h6 class="m-0 fw-semibold">{{ trans('labels.email') }}</h6>
                                <p class="mb-0">
                                    <span class="text-muted fs-7">{{ helper::appdata($storeinfo->id)->email }}</span>
                                </p>
                            </div>
                        </div>
                    </a>


                    <!-- Mobile -->
                    <a href="tel:{{ helper::appdata($storeinfo->id)->contact }}" class="text-dark color-changer">
                        <div class="bg-light bg-change-mode rounded p-3 mb-4 steps-box">
                            <span class="contact-icon">
                                <i class="fa-light fa-phone-flip"></i>
                            </span>
                            <div class="mx-3">
                                <h6 class="m-0 fw-semibold">{{ trans('labels.mobile') }}</h6>
                                <p class="mb-0">

                                    <span class="fs-7 text-muted">{{ helper::appdata($storeinfo->id)->contact }}</span>
                                </p>
                            </div>
                        </div>
                    </a>
                    <!-- Address -->
                    <a href="https://www.google.com/maps/place/{{ helper::appdata($storeinfo->id)->address }}"
                        target="_blank" class="text-dark color-changer">
                        <div class="bg-light bg-change-mode rounded p-3 mb-4 steps-box">
                            <div class="contact-icon p-3">
                                <span class="contact-icon">
                                    <i class="fa-light fa-location-dot text-white"></i></span>
                            </div>
                            <div class="mx-3">
                                <h6 class="m-0 fw-semibold">{{ trans('labels.address') }}</h6>
                                <p class="mb-0 fs-7 text-muted">
                                    {{ empty(helper::appdata($storeinfo->id)->address) ? '-' : helper::appdata($storeinfo->id)->address }}
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

        </div>

        <div class="col-lg-6">
            <div class="card h-100 border-0 border-0 box-shadow">
                <div class="card-body">
                    <form method="POST" action="{{ helper::storefront_url($storeinfo, '/submit') }}">
                        @csrf
                        <div class="mb-4 text-center">
                            <h3 class="fw-semibold color-changer">{{ trans('labels.contact_us') }}</h3>
                            <p class="text-muted font-weight-bold form-subtitle">
                                {{ trans('labels.contact_further_question') }}</p>
                        </div>
                        <div class="mb-3 ">
                            <div class="row">
                                <div>
                                    <input type="hidden" name="vendor_id" value="{{ $storeinfo->id }}">
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label class="form-label d-flex justify-content-start align-items-center label14">
                                        {{ trans('labels.first_name') }}
                                        <span class="text-danger mx-1"> * </span>
                                    </label>
                                    <input type="text" class="form-control rounded-2 p-3" name="fname"
                                        placeholder="{{ trans('labels.first_name') }}" required>
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label class="form-label d-flex justify-content-start align-items-center label14">
                                        {{ trans('labels.last_name') }}
                                        <span class="text-danger mx-1"> * </span>
                                    </label>
                                    <input type="text" class="form-control rounded-2 p-3" name="lname"
                                        placeholder="{{ trans('labels.last_name') }}" required>
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label class="form-label d-flex justify-content-start align-items-center label14">
                                        {{ trans('labels.email') }}
                                        <span class="text-danger mx-1"> * </span>
                                    </label>
                                    <input type="email" class="form-control rounded-2 p-3" name="email"
                                        placeholder="{{ trans('labels.email') }}" required>
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label class="form-label d-flex justify-content-start align-items-center label14">
                                        {{ trans('labels.mobile') }}
                                        <span class="text-danger mx-1"> * </span>
                                    </label>
                                    <input type="text" class="form-control mobile-number rounded-2 p-3"
                                        name="mobile" placeholder="{{ trans('labels.mobile') }}" required>
                                </div>
                                <div class="form-group col-md-12 mb-3">
                                    <label class="form-label d-flex justify-content-start align-items-center label14">
                                        {{ trans('labels.message') }}
                                        <span class="text-danger mx-1"> * </span>
                                    </label>
                                    <textarea class="form-control rounded-2" rows="2" name="message" placeholder="{{ trans('labels.message') }}"
                                        required></textarea>
                                </div>
                                @include('landing.layout.recaptcha')
                            </div>
                        </div>

                        <div class="d-flex">
                            <button type="submit" name="submit" id="btnsubmit"
                                class="btn btn-store w-100">{{ trans('labels.submit') }}</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- newsletter -->
@include('front.newsletter')
<!-- newsletter -->

@include('front.theme.footer')
