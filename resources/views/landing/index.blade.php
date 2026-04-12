@extends('landing.layout.default')

@section('content')
    <!-- banner-section start -->
    <section class="bg-gradient-color overflow-visible" id="home">
        <div class="header-container z-1">
            <div class="banner">
                <div class="banner-page text-center">
                    <div class="banner-text">
                        <h1 class="col-sm-7 text-capitalize col-11 mx-auto">{{ trans('landing.hero_banner_title') }}</h1>
                        <p class="pt-4 px-2 mx-auto">{{ trans('landing.hero_banner_description') }}</p>

                        <div class="mt-5 d-flex flex-wrap justify-content-center align-items-center gap-3">
                            <a href="{{ URL::to('/admin') }}"
                                class="btn-secondary rounded-2 fs-7 m-0 fw-500" target="_blank">
                                {{ trans('landing.get_started') }}
                            </a>
                            @if (env('Environment') == 'sendbox')
                                <div class="dropdown">
                                    @php
                                        $getuserslist = App\Models\User::where('type', 2)
                                            ->where('is_deleted', 2)
                                            ->get();
                                    @endphp
                                    @foreach ($getuserslist as $key => $user)
                                        <a href="{{ helper::storefront_url($user, '/pwa') }}" target="_blank"
                                            class="btn-border-white fs-7 m-0 fw-500 border-white rounded-2">
                                            {{ trans('landing.useapp') }}
                                        </a>
                                        @php break; @endphp
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    <div>
                        <img src="{{ @helper::image_path(helper::landingsettings()->landing_home_banner) }}" alt=""
                            class="img-fluid mt-5">
                    </div>
                </div>
            </div>
        </div>
        <div class="box">
        </div>
    </section>
    @if ($workdata->count() > 0)
        <section class="project-management py-5 overflow-hidden">
            <div class="container position-relative container-project-management">
                <h5 class="beautiful-ui-kit-title color-changer col-md-12">
                    {{ trans('landing.how_it_work') }}
                </h5>
                <p class="subtitle text-muted col-md-8 sub-title-mein">
                    {{ trans('landing.how_it_work_description') }}
                </p>
                <!-- Create Your Account start -->
                @foreach ($workdata as $key => $data)
                    <div
                        class="management-main mt-5 row {{ $key % 2 == 0 ? '' : 'flex-row-reverse' }} justify-content-between align-items-center">
                        <div class="project-management-text col-lg-6 mb-3 order-md-0 order-2">
                            <div data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000" direction="false">
                                <div class="{{ $key }}">
                                    <h5
                                        class="work-title color-changer mb-3 col-md-auto landing-rtl {{ session()->get('direction') == 2 ? 'rtl text-start' : 'ltr text-end' }}">
                                        {{ $data->title }}
                                    </h5>
                                </div>
                                <p
                                    class="mt-2 text-muted sub-title-mein landing-rtl {{ session()->get('direction') == 2 ? 'rtl text-start' : 'ltr text-end' }}">
                                    {{ $data->description }}
                                </p>
                                <div class="mt-4 landing-rtl {{ session()->get('direction') == 2 ? 'rtl' : 'ltr' }}">
                                    <div
                                        class="row g-3 {{ session()->get('direction') == 2 ? 'justify-content-end' : 'justify-content-end' }} ">
                                        <div class="col-6 col-sm-auto">
                                            <a href="{{ URL::to('/admin') }}"
                                                class="btn-secondary text-center w-100 fs-7 m-0 btn-class rounded-2"
                                                target="_blank">
                                                {{ trans('landing.get_started') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-auto management-image-2 aos-init overflow-hidden">
                            <div data-aos="fade-down" data-aos-delay="100" data-aos-duration="1000">
                                <img src="{{ helper::image_path($data->image) }}" alt=""
                                    class="project-management-image">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- features -->
    @if ($features->count() > 0)
        <section id="features">
            <div class="beautiful-ui-kit-bg-color bg-changer">
                <div class="container beautiful-ui-kit-container">
                    <h5 class="beautiful-ui-kit-title color-changer col-md-12">
                        {{ trans('landing.premium_features') }}
                    </h5>
                    <p class="subtitle col-md-8 sub-title-mein text-muted">
                        {{ trans('landing.premium_features_description') }}
                    </p>
                    <div class="row row-cols-1 row-cols-xl-3 row-cols-lg-2 row-cols-md-1 pt-4 g-3">
                        @php
                            $strings = [
                                'card-bg-color-1',
                                'card-bg-color-2',
                                'card-bg-color-3',
                                'card-bg-color-4',
                                'card-bg-color-5',
                                'card-bg-color-6',
                            ];
                            $count = count($strings);
                        @endphp
                        @if ($features->count() > 0)
                            @foreach ($features as $key => $feature)
                                <div class="col " data-aos="zoom-in" data-aos-delay="100" data-aos-duration="1000">
                                    <div
                                        class="card serviceBox  {{ session()->get('direction') == 2 ? 'rtl' : '' }} h-100">
                                        <div class="card-body p-0">
                                            <div class="service-icon">
                                                <img src="{{ helper::image_path($feature->image) }}" alt=""
                                                    class="bg-white rounded p-1">
                                            </div>
                                            <div class="service-Content">
                                                <h3 class="title">{{ $feature->title }}</h3>
                                                <p class="description text-muted">
                                                    {{ Str::limit($feature->description) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!-- features -->

    @if ($themes->count() > 0)
        <!-- our-template -->
        <section id="our-template">
            <div class="container-fluid clients-container">
                <div class="clients overflow-hidden">
                    <div class="what-our-clients-says-main">
                        <h5 class="what-our-clients-says-title color-changer col-md-8">
                            {{ trans('landing.awesome_templates') }}
                        </h5>
                        <p class="how-works-subtitle text-center text-muted col-md-8 mx-auto sub-title-mein text-muted">
                            {{ trans('landing.awesome_templates_description') }}
                        </p>
                    </div>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-0 pt-sm-5 pt-4 px-lg-5">
                        @foreach ($themes as $key => $theme)
                            <div class="col p-sm-4 p-3" data-aos="fade-up"
                                data-aos-delay="{{ $key == 0 ? $key + 1 : $key }}00" data-aos-duration="1000">
                                <div class="card card-bg border-0 h-100 them-card-box">
                                    <div class="card-body shadow p-3 border rounded-2">
                                        <div class="overflow-hidden rounded-2 w-100">
                                            <img src="{{ helper::image_path($theme->image) }}"
                                                class="card-img-top them-name-images rounded-2">
                                        </div>
                                    </div>
                                    <h6 class="card-title mt-3 color-changer fw-600 text-center m-0">
                                        {{ $theme->name }}
                                    </h6>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- free_section -->
    <section class="you-work-everywhere-you-are-bg-color bg-primary-rgb bg-changer">
        <div class="container">
            <div data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                <h5
                    class="you-work-everywhere-you-are color-changer text-capitalize position-relative text-center choose-plan-title">
                    {{ trans('landing.free_section') }}
                </h5>
                <P class="choose-plan text-muted text-center pt-4 sub-title-mein">
                    {{ trans('landing.free_section_description') }}
                </P>
                <div class="text-center mt-4">
                    <a href="{{ URL::to('/admin') }}" class="btn-secondary rounded-2 fs-7" target="_blank">
                        {{ trans('landing.get_started') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- free_section -->

    <!-- our-stores -->
    @if (helper::storedata()->count() > 0)
        @if (@helper::checkaddons('subscription'))
            <!-- our-stores -->
            <section id="our-stores">
                <div class="card-section-bg-color">
                    <div class="container card-section-container">
                        <div>
                            <h5 class="hotel-main-title color-changer">
                                {{ trans('landing.our_stores') }}
                            </h5>
                            <p class="hotel-main-subtitle col-md-8 sub-title-mein px-1 text-muted ">
                                {{ trans('landing.our_stores_description') }}
                            </p>
                        </div>
                        <div
                            class="row row-cols-2 mt-2 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-xll-4 g-sm-4 g-2">
                            @include('landing.storelist')
                        </div>
                        <div class="d-flex justify-content-center mt-sm-5 mt-4 view-all-btn">
                            <a href="{{ URL::to('/stores') }}"
                                class="btn-secondary rounded-2 fs-7 fw-500">{{ trans('landing.view_all') }}
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @endif
    <!-- our-stores -->

    <!-- Mobile app -->
    @if (!empty($app_settings))
        @if (@helper::checkaddons('vendor_app'))
            @php
                $mobile_app = App\Models\AppSettings::where('vendor_id', 1)->first();
            @endphp
            @if ($mobile_app->mobile_app_on_off == 1)
                <section class="use-as-extension pt-0">
                    <div class="container work-together-container">
                        <div class="p-sm-5 p-3 bg-primary-rgb rounded bg-changer">
                            <div class="work-together row flex-row-reverse align-items-center justify-content-between">
                                <div class="col-lg-5 overflow-hidden d-lg-block d-none">
                                    <div data-aos="fade-down" data-aos-delay="100" data-aos-duration="1000">
                                        <img src="{{ helper::image_path($app_settings->image) }}" alt=""
                                            class="img-fluidn object-fit-cover w-100 ">
                                    </div>
                                </div>
                                <div class="col-lg-6 overflow-hidden">
                                    <div data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                                        <div class="work-together-content">
                                            <div class="extension-text">
                                                <div>
                                                    <h5
                                                        class="Work-together-title color-changer position-relative use-extension">
                                                        {{ trans('landing.mobile_app_section') }}
                                                    </h5>
                                                </div>
                                                <p class="with-notes mt-3 use-as-extension-text sub-title-mein text-muted">
                                                    {{ trans('landing.mobile_app_section_description') }}
                                                </p>
                                                <div class="d-flex gap-3 mt-sm-5 mt-4 store-img-box">
                                                    @if (!empty($app_settings->android_link) && $app_settings->android_link != '')
                                                        <a href="{{ $app_settings->android_link }}" target="_blank">
                                                            <img src="{{ url(env('ASSETPATHURL') . '/landing/images/playstore.png') }}"
                                                                class="store-img store_bg_changer py-3 px-4 rounded-2"
                                                                alt="">
                                                        </a>
                                                    @endif
                                                    @if (!empty($app_settings->ios_link) && $app_settings->ios_link != '')
                                                        <a href="{{ $app_settings->ios_link }}" target="_blank">
                                                            <img src="{{ url(env('ASSETPATHURL') . '/landing/images/appstorebtn.png') }}"
                                                                class="store-img store_bg_changer py-3 px-4 rounded-2"
                                                                alt="">
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </section>
            @endif
        @endif
    @endif
    <!-- Mobile app -->

    <!-- pricing-plans -->
    @if ($planlist->count() > 0)
        @if (@helper::checkaddons('subscription'))
            <!-- pricing-plans -->
            <section id="pricing-plans" class="pt-0">
                <div class="container choose-your-plan-container">
                    <h5 class="Work-together-title color-changer position-relative text-center choose-plan-title">
                        {{ trans('landing.pricing_plan_title') }}
                    </h5>
                    <P class="choose-plan text-center mt-3 sub-title-mein col-md-8 text-muted mx-auto">
                        {{ trans('landing.pricing_plan_description') }}
                    </P>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-3 g-4 pt-5 choose-card">
                        @foreach ($planlist as $plan)
                            <div data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                                <div class="col d-flex plan-card h-100">
                                    <div class="pricingTable rounded-4 card bg-primary-rgb">
                                        <div class="card-body p-sm-4">
                                            <div class="price-value">
                                                <h5 class="fs-18 mb-3 fw-500 text-secondary-color">{{ $plan->name }}
                                                </h5>
                                                <div class="price-wrapper">
                                                    <span
                                                        class="amount color-changer">{{ helper::currency_formate($plan->price, '') }}</span>
                                                    <span class="fw-500 duration color-changer">
                                                        / @if ($plan->plan_type == 1)
                                                            @if ($plan->duration == 1)
                                                                {{ trans('labels.one_month') }}
                                                            @elseif($plan->duration == 2)
                                                                {{ trans('labels.three_month') }}
                                                            @elseif($plan->duration == 3)
                                                                {{ trans('labels.six_month') }}
                                                            @elseif($plan->duration == 4)
                                                                {{ trans('labels.one_year') }}
                                                            @elseif($plan->duration == 5)
                                                                {{ trans('labels.lifetime') }}
                                                            @endif
                                                        @endif
                                                        @if ($plan->plan_type == 2)
                                                            {{ $plan->days }}
                                                            {{ $plan->days > 1 ? trans('labels.days') : trans('labels.day') }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            @if ($plan->tax != null && $plan->tax != '')
                                                <small
                                                    class="text-danger">{{ trans('labels.exclusive_taxes') }}</small><br>
                                            @else
                                                <small class="text-success">{{ trans('labels.inclusive_taxes') }}</small>
                                                <br>
                                            @endif
                                            <p class="text-muted capture mt-2 mb-0 pb-3 border-bottom border-secondary">
                                                {{ $plan->description }}
                                            </p>
                                            <ul
                                                class="pricing-content {{ session()->get('direction') == 2 ? 'rtl' : '' }} w-100 mt-2 mb-0">
                                                <li class="color-changer">
                                                    {{ $plan->order_limit == -1 ? trans('labels.unlimited') : $plan->order_limit }}
                                                    {{ $plan->order_limit > 1 || $plan->order_limit == -1 ? trans('labels.products') : trans('labels.products') }}
                                                </li>
                                                <li class="color-changer">
                                                    {{ $plan->appointment_limit == -1 ? trans('labels.unlimited') : $plan->appointment_limit }}
                                                    {{ $plan->appointment_limit > 1 || $plan->appointment_limit == -1 ? trans('labels.orders') : trans('labels.orders') }}
                                                </li>
                                                <li class="color-changer">
                                                    @php
                                                        $themes = [];
                                                        if ($plan->themes_id != '' && $plan->themes_id != null) {
                                                            $themes = explode('|', $plan->themes_id);
                                                    } @endphp
                                                    <p class="fs-15px">{{ count($themes) }}
                                                        {{ count($themes) > 1 ? trans('labels.themes') : trans('labels.theme') }}
                                                        <span>
                                                            <a onclick="themeinfo('{{ $plan->id }}','{{ $plan->themes_id }}','{{ $plan->name }}')"
                                                                tooltip="{{ trans('labels.info') }}"
                                                                href="javascript:void(0)" class="text-dark color-changer">
                                                                <i class="fa-regular fa-circle-info"></i>
                                                            </a>
                                                        </span>
                                                    </p>
                                                </li>
                                                @if ($plan->coupons == 1)
                                                    <li class="color-changer">
                                                        {{ trans('labels.coupons') }}
                                                    </li>
                                                @endif
                                                @if ($plan->custom_domain == 1)
                                                    <li class="color-changer">
                                                        {{ trans('labels.custom_domain_available') }}
                                                    </li>
                                                @endif
                                                @if ($plan->google_analytics == 1)
                                                    <li class="color-changer">
                                                        {{ trans('labels.google_analytics_available') }}
                                                    </li>
                                                @endif
                                                @if ($plan->blogs == 1)
                                                    <li class="color-changer">
                                                        {{ trans('labels.blogs') }}
                                                    </li>
                                                @endif
                                                @if ($plan->google_login == 1)
                                                    <li class="color-changer">
                                                        {{ trans('labels.google_login') }}
                                                    </li>
                                                @endif
                                                @if ($plan->facebook_login == 1)
                                                    <li class="color-changer">
                                                        {{ trans('labels.facebook_login') }}
                                                    </li>
                                                @endif
                                                @if ($plan->sound_notification == 1)
                                                    <li class="color-changer">
                                                        {{ trans('labels.sound_notification') }}
                                                    </li>
                                                @endif
                                                @if ($plan->whatsapp_message == 1)
                                                    <li class="color-changer">
                                                        {{ trans('labels.whatsapp_message') }}
                                                    </li>
                                                @endif
                                                @if ($plan->social_logins == 1)
                                                    <li class="color-changer">
                                                        {{ trans('labels.social_logins') }}
                                                    </li>
                                                @endif
                                                @if ($plan->telegram_message == 1)
                                                    <li class="color-changer">
                                                        {{ trans('labels.telegram_message') }}
                                                    </li>
                                                @endif
                                                @if ($plan->vendor_app == 1)
                                                    <li class="color-changer">
                                                        {{ trans('labels.vendor_app_available') }}
                                                    </li>
                                                @endif
                                                @if ($plan->customer_app == 1)
                                                    <li class="color-changer">
                                                        {{ trans('labels.customer_app') }}
                                                    </li>
                                                @endif
                                                @if ($plan->pos == 1)
                                                    <li class="color-changer">
                                                        {{ trans('labels.pos') }}
                                                    </li>
                                                @endif
                                                @if ($plan->pwa == 1)
                                                    <li class="color-changer">
                                                        {{ trans('labels.pwa') }}
                                                    </li>
                                                @endif
                                                @if ($plan->role_management == 1)
                                                    <li class="color-changer">
                                                        {{ trans('labels.role_management') }}
                                                    </li>
                                                @endif
                                                @if ($plan->pixel == 1)
                                                    <li class="color-changer">
                                                        {{ trans('labels.pixel') }}
                                                    </li>
                                                @endif
                                                @php $features = ($plan->features == null ? null : explode('|', $plan->features));@endphp
                                                @if ($features != '')
                                                    @foreach ($features as $feature)
                                                        <li class="color-changer">{{ $feature }}</li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                        <div class="card-footer p-0">
                                            <a href="{{ URL::to('/admin') }}" target="_blank"
                                                class="px-5 py-3 theme-btn style5 rounded-bottom-4 text-center">
                                                {{ trans('landing.get_started') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    @endif
    <!-- pricing-plans -->

    <!-- Funfact start -->
    @if ($funfacts->count() > 0)
        <section class="fun-fact bg-primary-rgb bg-changer">
            <div class="container js-ranig-number py-5">
                <div class="d-lg-block d-none">
                    <div class="row js-nnum row-cols-md-2 row-cols-lg-4 row-cols-xl-4 g-3 mx-auto" data-aos="fade-up"
                        data-aos-delay="100" data-aos-duration="1000">
                        @foreach ($funfacts as $fact)
                            <div class="col-lg-3 col-sm-6">
                                <div class="counter d-flex align-items-center justify-content-center flex-column gap-1">
                                    <div class="counter-icon">
                                        <span>{!! $fact->icon !!}</span>
                                    </div>
                                    <h3 class="px-3 color-changer m-0">{{ $fact->description }}</h3>
                                    <span class="counter-value">{{ $fact->title }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="funface-icon owl-carousel owl-theme d-lg-none" data-aos="fade-up" data-aos-delay="100"
                    data-aos-duration="1000">
                    @foreach ($funfacts as $fact)
                        <div class="item h-100 js-main-card">
                            <div class="counter d-flex align-items-center justify-content-center flex-column gap-1">
                                <div class="counter-icon">
                                    <span>{!! $fact->icon !!}</span>
                                </div>
                                <h3 class="px-3 color-changer m-0">{{ $fact->description }}</h3>
                                <span class="counter-value">{{ $fact->title }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <!-- Funfact End -->

    <!-- What Our Clients Says -->
    @if (@helper::checkaddons('store_reviews'))
        @if ($testimonials->count() > 0)
            <div class="py-5">
                <div class="container py-lg-5">
                    <div data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                        <div class="carousel-client-slaider my-4">
                            <h5 class="text-center color-changer">{{ trans('labels.testimonial_title') }}</h5>
                            <p class="text-center text-muted">{{ trans('labels.testimonial_subtitle') }}</p>
                        </div>
                        <div id="testimonial-12" class="owl-carousel owl-theme">
                            @foreach ($testimonials as $testimonial)
                                <div class="item">
                                    <div class="testimonial-12">
                                        <p
                                            class="description color-changer {{ session()->get('direction') == 2 ? 'rtl' : '' }}">
                                            “{{ $testimonial->description }}”
                                        </p>
                                        <div class="pic p-1">
                                            <img src="{{ helper::image_path($testimonial->image) }}" alt="avatar"
                                                class="rounded-circle">
                                        </div>
                                        <div class="testimonial-prof">
                                            <h4> {{ $testimonial->name }}</h4>
                                            <small class="text-muted">{{ $testimonial->position }}</small>
                                            <ul class="list-inline small mb-3">
                                                @php
                                                    $count = (int) $testimonial->star;
                                                @endphp
                                                @for ($i = 0; $i < 5; $i++)
                                                    @if ($i <= $count)
                                                        <li class="list-inline-item me-0 small"><i
                                                                class="fa-solid fa-star text-warning"></i>
                                                        </li>
                                                    @else
                                                        <li class="list-inline-item me-0 small"><i
                                                                class="fa-regular fa-star text-warning"></i>
                                                        </li>
                                                    @endif
                                                @endfor
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
    <!-- What Our Clients Says End -->

    <!-- Blogs start -->
    @if ($blogs->count() > 0)
        @if (@helper::checkaddons('blog'))
            <section id="blogs" class="bg-primary-rgb bg-changer">
                <div class="blogs-bg-color">
                    <div class="container blog-container">
                        <div class="blog-card">
                            <div data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                                <h5 class="latest-blog color-changer">
                                    {{ trans('landing.blog_section_title') }}
                                </h5>
                                <p class="blog-lorem-text col-md-8 pt-lg-4 pt-2 pb-5 sub-title-mein text-muted">
                                    {{ trans('landing.blog_section_description') }}
                                </p>
                            </div>
                            <div class="owl-carousel blogs-slaider owl-theme pb-5" data-aos="fade-down"
                                data-aos-delay="100" data-aos-duration="1000">
                                @include('landing.included.blogcommonview')
                            </div>
                            <div class="d-flex justify-content-center view-all-btn">
                                <a href="{{ URL::to('/blogs') }}"
                                    class="btn-secondary rounded-2 fw-500 fs-7">{{ trans('landing.view_all') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @endif
    <!-- Blogs End -->

    <!-- contact  start -->
    <section id="contact-us" class="contact-bg-color py-5">
        <div class="container contact-container">
            <div class="row justify-content-between">
                <div class="col-lg-7">
                    <div class="d-flex align-items-center h-100" data-aos="fade-up" data-aos-delay="100"
                        data-aos-duration="1000">
                        <div class="main-text-title">
                            <div class="contact-title color-changer col-md-10 pb-2">
                                {{ trans('landing.contact_section_title') }}
                            </div>
                            <p class="contact-subtitle col-md-12 col-lg-10 pb-4 sub-title-mein text-muted">
                                {{ trans('landing.contact_section_description') }}
                            </p>
                            <div class="pb-4 contact-email-box">
                                <div class="row g-3">
                                    <div class="col-xl-4 col-md-6 col-lg-6">
                                        <div class="email-icon-text-box bg-primary-rgb card border-0 rounded-3 h-100">
                                            <div class="card-body">
                                                <p class="mb-2 p-0 text-center">
                                                    <i class="fa-light fa-envelope text-secondary-color fs-2"></i>
                                                </p>
                                                <p class="fs-5 text-center color-changer fw-600 p-0 m-0">
                                                    {{ trans('landing.email_us') }}</p>
                                                <p class="infogolio-email p-0 mt-2 m-0 text-center"><a
                                                        href="mailto:{{ helper::appdata('')->email }}">{{ helper::appdata('')->email }}</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-lg-6">
                                        <div class="email-icon-text-box bg-primary-rgb card border-0 rounded-3 h-100">
                                            <div class="card-body">
                                                <p class="mb-2 p-0 text-center">
                                                    <i class="fa-light fa-phone-volume text-secondary-color fs-2"></i>
                                                </p>
                                                <p class="fs-5 text-center color-changer fw-600 p-0 m-0">
                                                    {{ trans('landing.call_us') }}
                                                </p>
                                                <p class="infogolio-email p-0 mt-2 m-0 text-center"><a
                                                        href="tel:{{ helper::appdata('')->contact }}">{{ helper::appdata('')->contact }}</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-12">
                                        <div class="email-icon-text-box bg-primary-rgb card border-0 rounded-3 h-100">
                                            <div class="card-body">
                                                <p class="mb-2 p-0 text-center">
                                                    <i class="fa-light fa-location-dot text-secondary-color fs-2"></i>
                                                </p>
                                                <p class="fs-5 text-center color-changer fw-600 p-0 m-0">
                                                    {{ trans('landing.address') }}
                                                </p>
                                                <p class="infogolio-email p-0 mt-2 m-0 text-center"><a href="#"
                                                        target="_blank">{{ helper::appdata('')->address }}</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="email-icon-text-box-2">
                                <div class="email-md-center">
                                    <div class="contact-icons d-flex">
                                        @foreach (@helper::getsociallinks(1) as $links)
                                            <a href="{{ $links->link }}" target="_blank"
                                                class="rounded-2 contact-icon">{!! $links->icon !!}</a>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="contact-form col-lg-5">
                    <div data-aos="fade-down" data-aos-delay="100" data-aos-duration="1000">
                        <form class="rgb-secondary-light rounded-3 px-sm-4 px-3 py-4" action="{{ URL::To('/inquiry') }}"
                            method="post">
                            @csrf
                            <h5 class="contact-form-title text-dark text-center color-changer">
                                {{ trans('landing.contact_us') }}
                            </h5>
                            <p class="contact-form-subtitle text-center text-muted">
                                {{ trans('landing.contact_section_description_two') }}
                            </p>
                            <div class="row g-3 mt-3">
                                <div class="col-md-6">
                                    <label for="name"
                                        class="form-label contact-form-label">{{ trans('landing.name') }}</label>
                                    <input type="text" class="form-control contact-input" name="name"
                                        placeholder="{{ trans('landing.name') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email"
                                        class="form-label contact-form-label">{{ trans('landing.email') }}</label>
                                    <input type="email" class="form-control contact-input" name="email"
                                        placeholder="{{ trans('landing.email') }}" required>
                                </div>
                                <div class="col-12">
                                    <label for="inputAddress"
                                        class="form-label contact-form-label">{{ trans('landing.mobile') }}</label>
                                    <input type="number" class="form-control contact-input" name="mobile"
                                        placeholder="{{ trans('landing.mobile') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="message"
                                        class="form-label contact-form-label">{{ trans('landing.message') }}</label>
                                    <textarea class="form-control contact-input" rows="3" name="message"
                                        placeholder="{{ trans('landing.message') }}" required></textarea>
                                </div>
                                @include('landing.layout.recaptcha')
                                <div class="col-6 mx-auto">
                                    <button type="submit"
                                        class="btn-secondary w-100 rounded-2">{{ trans('landing.submit') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- contact end -->

    <!-- subscription -->
    @include('landing.newslatter')
    <!--theme image Modal -->
    <div class="modal fade" id="themeinfo" tabindex="-1" aria-labelledby="themeinfoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title text-dark color-changer fw-600" id="themeinfoLabel"></h5>
                    <button type="button" class="bg-transparent border-0" data-bs-dismiss="modal">
                        <i class="fa-regular fa-xmark fs-4 color-changer"></i>
                    </button>
                </div>
                <div class="modal-body" id="theme_modalbody">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var layout = "{{ session()->get('direction') }}";
    </script>
    <!-- IF VERSION 2  -->
    @if (helper::appdata('')->recaptcha_version == 'v2')
        <script src='https://www.google.com/recaptcha/api.js'></script>
    @endif
    <!-- IF VERSION 3  -->
    @if (helper::appdata('')->recaptcha_version == 'v3')
        {!! RecaptchaV3::initJs() !!}
    @endif
@endsection
