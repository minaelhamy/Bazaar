<!DOCTYPE html>
<html lang="en" dir="{{ session()->get('direction') == 2 ? 'rtl' : 'ltr' }}" class="light">

<head>
    <title>{{ helper::appdata(@$storeinfo->id)->website_title }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">

    <script>
        const theme = localStorage.getItem('theme');
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.add('light');
        }
    </script>

    <link rel="icon" href='{{ helper::image_path(helper::appdata(@$storeinfo->id)->favicon) }}' type="image/x-icon">
    <meta name="bingbot" content="nocache">
    @if (helper::storefront_request_is($storeinfo, 'detail-*'))
        <meta property="og:title" content="{{ @$getitem->item_name }}" />
        <meta property="og:description" content="{{ strip_tags(trim($getitem->description)) }}" />
        <meta property="og:image" content="{{ @helper::image_path($getitem['product_image']->image) }}" />
    @elseif (helper::storefront_request_is($storeinfo, 'blogs-*'))
        <meta property="og:title" content="{{ @$blogdetail->title }}" />
        <meta property="og:description" content="{{ strip_tags(trim($blogdetail->description)) }}" />
        <meta property="og:image" content="{{ @helper::image_path(@$blogdetail->image) }}" />
    @else
        <meta property="og:title" content="{{ helper::appdata(@$storeinfo->id)->meta_title }}" />
        <meta property="og:description" content="{{ helper::appdata(@$storeinfo->id)->meta_description }}" />
        <meta property="og:image" content='{{ helper::image_path(helper::appdata(@$storeinfo->id)->og_image) }}' />
    @endif
    <!-- favicon-icon  -->

    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="{{ url(env('ASSETPATHURL') . 'front/css/all.min.css') }}">
    <!-- font-awsome css  -->
    <link rel="stylesheet" type="text/css" href="{{ url(env('ASSETPATHURL') . 'front/css/bootstrap.min.css') }}">
    <!-- bootstrap css -->
    <link rel="stylesheet" type="text/css" href="{{ url(env('ASSETPATHURL') . 'front/css/owl.carousel.min.css') }}">
    <!-- owl.carousel css -->
    <link rel="stylesheet" type="text/css" href="{{ url(env('ASSETPATHURL') . 'front/css/swiper-bundle.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ url(env('ASSETPATHURL') . 'front/css/style.css') }}">
    <!-- slick slider css -->
    <link rel="stylesheet" type="text/css" href="{{ url(env('ASSETPATHURL') . 'front/css/slick.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url(env('ASSETPATHURL') . 'front/css/slick-theme.css') }}">
    <!-- style css  -->
    <link rel="stylesheet" type="text/css" href="{{ url(env('ASSETPATHURL') . 'front/css/fonts.css') }}">
    <!-- Fonts css  -->
    <link rel="stylesheet" type="text/css" href="{{ url(env('ASSETPATHURL') . 'front/css/responsive.css') }}">
    <!-- responsive css  -->
    <link rel="stylesheet" type="text/css"
        href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/sweetalert/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'front/css/dataTables.bootstrap4.min.css') }}">

    <!-- IF VERSION 2  -->
    @if (helper::adminappdata()->recaptcha_version == 'v2')
        <script src='https://www.google.com/recaptcha/api.js'></script>
    @endif
    <!-- IF VERSION 3  -->
    @if (helper::adminappdata()->recaptcha_version == 'v3')
        {!! RecaptchaV3::initJs() !!}
    @endif

    <style>
        #splash {
            background-color: #000;
        }

        :root {
            @if (helper::appdata(@$storeinfo->id)->template == 8)
                --bs-secondary: {{ helper::appdata($storeinfo->id)->secondary_color . '10' }};
            @endif
        }
    </style>
    <!-- PWA  -->
    @if (@helper::checkaddons('subscription'))
        @if (@helper::checkaddons('pwa'))
            @php
                $checkplan = App\Models\Transaction::where('vendor_id', @$storeinfo->id)
                    ->orderByDesc('id')
                    ->first();
                $user = App\Models\User::where('id', @$storeinfo->id)->first();
                if ($user->allow_without_subscription == 1) {
                    $pwa = 1;
                } else {
                    $pwa = @$checkplan->pwa;
                }
            @endphp

            @if ($pwa == 1)
                @if (helper::appdata(@$storeinfo->id)->pwa == 1)
                    @include('front.pwa.pwa')
                @endif
            @endif
        @else
            @if (@helper::checkaddons('pwa'))
                @if (helper::appdata(@$storeinfo->id)->pwa == 1)
                    @include('front.pwa.pwa')
                @endif
            @endif
        @endif
    @endif

    <!-- PIXEL  -->
    @if (@helper::checkaddons('subscription'))
        @if (@helper::checkaddons('pixel'))
            @php
                $checkplan = App\Models\Transaction::where('vendor_id', @$storeinfo->id)
                    ->orderByDesc('id')
                    ->first();
                $user = App\Models\User::where('id', @$storeinfo->id)->first();
                if ($user->allow_without_subscription == 1) {
                    $pixel = 1;
                } else {
                    $pixel = @$checkplan->pixel;
                }

            @endphp
            @if ($pixel == 1)
                @include('front.pixel.pixel')
            @endif
        @endif
    @else
        @if (@helper::checkaddons('pixel'))
            @include('front.pixel.pixel')
        @endif
    @endif
</head>

<body>
    <div id="splash"></div>

    <!-- Age Modal -->
    @if (@helper::checkaddons('age_verification'))
        @include('front.age_modal')
    @endif

    <!-- mobile category Modal -->

    <div class="modal fade" id="catModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content mt-6 cat-over">
                <div class="modal-header justify-content-between py-2">
                    <h1 class="modal-title fs-5 color-changer" id="exampleModalLabel">{{ trans('labels.category') }}
                    </h1>
                    <button type="button" class="bg-transparent border-0 m-0" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="fa-regular fa-xmark fs-4 color-changer"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <div class="card card-header cat-dispaly bg-transparent px-0">
                            <div class=" d-inline-block">
                                <h4
                                    class="theme-4-title  {{ session()->get('direction') == 2 ? 'text-right' : '' }} m-0">
                                    {{ trans('labels.category') }}
                                </h4>
                            </div>
                        </div>
                        <div>
                            @foreach (helper::getcategory(@$storeinfo->id) as $key => $category)
                                @php
                                    $check_cat_count = 0;
                                @endphp
                                @foreach (helper::getitems(@$storeinfo->id) as $item)
                                    @if ($category->id == $item->cat_id)
                                        @php
                                            $check_cat_count++;
                                        @endphp
                                    @endif
                                @endforeach
                                @if ($check_cat_count > 0)
                                    <div data-bs-dismiss="modal">
                                        <a class="nav-link mx-0 mt-0 border-0 py-2 fw-normal d-flex align-items-center justify-content-between {{ session()->get('direction') == 2 ? 'rtl-side-cat-check' : 'side-cat-check' }} btn-sm {{ $category->slug }}"
                                            href="{{ helper::storefront_url($storeinfo, '/search?category=' . $category->slug) }}">{{ $category->name }}
                                            <div class="fw-semibold">{{ $check_cat_count }}</div>
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="catbox-arow"></div>
                </div>
            </div>
        </div>
    </div>

    <!---------------- mobile sider bar ---------------->
    <div class="offcanvas mobile-sidebar {{ session()->get('direction') == 2 ? 'offcanvas-end' : 'offcanvas-start' }}"
        tabindex="-1" id="mobile-sidebar" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header justify-content-between border-bottom">
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    let logo = "";


                    if (localStorage.getItem('theme') === 'dark') {
                        logo = "{{ helper::image_path(helper::appdata(@$storeinfo->id)->darklogo) }}";
                    } else {
                        logo = "{{ helper::image_path(helper::appdata(@$storeinfo->id)->logo) }}";
                    }

                    // Set logo image src
                    document.getElementById('logoimage').src = logo;
                });
            </script>

            <a href="{{ helper::storefront_url($storeinfo) }}">
                <img id="logoimage" src="" alt="logo" class="object-fit-cover logo-h-55-px">
            </a>

            <button type="button" class="bg-transparent border-0 m-0" data-bs-dismiss="offcanvas"
                aria-label="Close">
                <i class="fa-regular fa-xmark fs-4 color-changer"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            <ul class="list-group list-group-flush">
                @if (@helper::checkaddons('blog'))
                    @if (helper::getblogs(@$storeinfo->id)->count() > 0)
                        <li class="list-group-item py-3 bg-transparent">
                            <a class="text-dark color-changer" href="{{ helper::storefront_url($storeinfo, '/blogs') }}"
                                class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-list"></i>
                                <span>{{ trans('labels.blogs') }}</span>
                            </a>
                        </li>
                    @endif
                @endif
                <li class="list-group-item py-3 bg-transparent">
                    <a class="text-dark color-changer" href="{{ helper::storefront_url($storeinfo, '/contact') }}"
                        class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-phone-flip"></i>
                        <span>{{ trans('labels.contact_us') }}</span>
                    </a>
                </li>
                @if (helper::getfaqs(@$storeinfo->id)->count() > 0)
                    <li class="list-group-item py-3 bg-transparent">
                        <a class="text-dark color-changer" href="{{ helper::storefront_url($storeinfo, '/faqs') }}"
                            class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-circle-question"></i>
                            <span>{{ trans('labels.faqs') }}</span>
                        </a>
                    </li>
                @endif
                <li class="list-group-item py-3 bg-transparent">
                    <a class="text-dark color-changer" href="{{ helper::storefront_url($storeinfo, '/privacypolicy') }}"
                        class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-user-shield"></i>
                        <span>{{ trans('labels.privacy_policy') }}</span>
                    </a>
                </li>
                <li class="list-group-item py-3 bg-transparent">
                    <a class="text-dark color-changer" href="{{ helper::storefront_url($storeinfo, '/aboutus') }}"
                        class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-users"></i>
                        <span>{{ trans('labels.about_us') }}</span>
                    </a>
                </li>
                <li class="list-group-item py-3 bg-transparent">
                    <a class="text-dark color-changer" href="{{ helper::storefront_url($storeinfo, '/terms_condition') }}"
                        class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-clipboard-list"></i>
                        <span>{{ trans('labels.terms_condition') }}</span>
                    </a>
                </li>
                <li class="list-group-item py-3 bg-transparent">
                    <a class="text-dark color-changer" href="{{ helper::storefront_url($storeinfo, '/refund_policy') }}"
                        class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                        <span>{{ trans('labels.refund_policy') }}</span>
                    </a>
                </li>
                <li class="list-group-item py-3 bg-transparent">
                    <a class="text-dark color-changer" href="{{ helper::storefront_url($storeinfo, '/find-order') }}"
                        class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-truck-fast"></i>
                        <span>{{ trans('labels.track_order') }}</span>
                    </a>
                </li>
                <li class="list-group-item py-3 bg-transparent">
                    <a class="d-flex align-items-center gap-2 text-dark color-changer" data-bs-toggle="modal"
                        data-bs-target="#infomodal">
                        <i class="fa-solid fa-circle-info"></i>
                        <span>{{ trans('labels.store_information') }}</span>
                    </a>
                </li>
            </ul>
            <!-- app install btn -->
            <div class="justify-content-center d-flex gap-2 my-3">
                @if (@helper::checkaddons('subscription'))
                    @if (@helper::checkaddons('user_app'))
                        @php
                            $checkplan = App\Models\Transaction::where('vendor_id', @$storeinfo->id)
                                ->orderByDesc('id')
                                ->first();

                            if (@$user->allow_without_subscription == 1) {
                                $user_app = 1;
                            } else {
                                $user_app = @$checkplan->customer_app;
                            }
                        @endphp
                        @if ($user_app == 1)
                            <!-- Google play store button -->
                            @if (
                                @helper::getappsetting(@$storeinfo->id)->android_link != null &&
                                    @helper::getappsetting(@$storeinfo->id)->android_link != '')
                                <a href="{{ @helper::getappsetting(@$storeinfo->id)->android_link }}"> <img
                                        src="{{ url(env('ASSETPATHURL') . 'front/images/google-play.svg') }}"
                                        class="app-btn" alt=""> </a>
                            @endif
                            @if (
                                @helper::getappsetting(@$storeinfo->id)->ios_link != null &&
                                    @helper::getappsetting(@$storeinfo->id)->ios_link != '')
                                <!-- App store button -->
                                <a href="{{ @helper::getappsetting(@$storeinfo->id)->ios_link }}"> <img
                                        src="{{ url(env('ASSETPATHURL') . 'front/images/app-store.svg') }}"
                                        class="app-btn" alt=""> </a>
                            @endif
                        @endif
                    @endif
                @else
                    @if (@helper::checkaddons('user_app'))
                        <!-- Google play store button -->
                        @if (
                            @helper::getappsetting(@$storeinfo->id)->android_link != null &&
                                @helper::getappsetting(@$storeinfo->id)->android_link != '')
                            <a href="{{ @helper::getappsetting(@$storeinfo->id)->android_link }}"> <img
                                    src="{{ url(env('ASSETPATHURL') . 'front/images/google-play.svg') }}"
                                    class="app-btn" alt=""> </a>
                        @endif
                        @if (
                            @helper::getappsetting(@$storeinfo->id)->ios_link != null &&
                                @helper::getappsetting(@$storeinfo->id)->ios_link != '')
                            <!-- App store button -->
                            <a href="{{ @helper::getappsetting(@$storeinfo->id)->ios_link }}"> <img
                                    src="{{ url(env('ASSETPATHURL') . 'front/images/app-store.svg') }}"
                                    class="app-btn" alt=""> </a>
                        @endif
                    @endif
                @endif
            </div>
        </div>
        <div class="offcanvas-footer">
            <div class="text-center color-changer border-top p-2 fs-8">
                {{ @helper::appdata(@$storeinfo->id)->copyright }}</div>
        </div>
    </div>


    <!--------------------- login sidebar --------------------->
    <div class="offcanvas {{ session()->get('direction') == 2 ? 'offcanvas-start' : 'offcanvas-end' }}"
        tabindex="-1" id="loginpage" aria-labelledby="loginpageLabel">
        <div class="offcanvas-header justify-content-between py-4 border-bottom">
            <h5 class="offcanvas-title color-changer" id="auth_form_title">{{ trans('labels.login') }}</h5>
            <button type="button" class="bg-transparent border-0 m-0" data-bs-dismiss="offcanvas"
                aria-label="Close">
                <i class="fa-regular fa-xmark fs-4 color-changer"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            <!-------------------------- login -------------------------->
            <div class="login input-14" id="login_form">
                <form method="POST" action="{{ helper::storefront_url($storeinfo, '/checklogin-normal') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="emailid" class="form-label fw-semibold">{{ trans('labels.email') }}</label>
                        <input type="email" class="form-control rounded-2 p-3" name="email" id="emailid"
                            placeholder="Email" required>
                    </div>
                    <div class="mb-3">
                        <label for="Password" class="form-label fw-semibold">{{ trans('labels.password') }}</label>
                        <input type="password" class="form-control rounded-2 p-3" name="password" id="Password"
                            placeholder="Password" required>
                    </div>
                    <a class="forgot_password_btn color-changer fw-bolder pb-3 d-flex fs-7 {{ session()->get('direction') == 2 ? ' justify-content-start' : ' justify-content-end ' }}"
                        href="javascript:void(0)">{{ trans('labels.forgot_password') }}?</a>
                    <button type="submit" id="btnsignin"
                        class="btn btn-store d-block w-100 mb-3">{{ trans('labels.login') }}</button>
                </form>
                <p class="text-center color-changer mb-3">{{ trans('labels.dont_have_account') }} <a
                        class="signup-filter-btn fw-bolder create_account_btn text-secondary fw-semibold"
                        href="javascript:void(0)">{{ trans('labels.create_account') }}</a></p>
                <div class="or_section">
                    <div class="line"></div>
                    <p class="mb-0 color-changer fw-medium">{{ trans('labels.or') }}</p>
                    <div class="line"></div>
                </div>
                @php
                    $setting = App\Models\Settings::where('vendor_id', @$storeinfo->id)->first();
                @endphp
                @if (@helper::checkaddons('subscription'))
                    @if (@helper::checkaddons('google_login'))
                        @php
                            $checkplan = App\Models\Transaction::where('vendor_id', @$storeinfo->id)
                                ->orderByDesc('id')
                                ->first();
                            $user = App\Models\User::where('id', @$storeinfo->id)->first();
                            if (@$user->allow_without_subscription == 1) {
                                $google_login = 1;
                            } else {
                                $google_login = @$checkplan->google_login;
                            }
                        @endphp
                        @if ($google_login == 1)
                            @if (@$setting->google_mode == 1)
                                <div class="d-sm-flex justify-content-between my-3">
                                    <a @if (env('Environment') == 'sendbox') onclick="myFunction()" @else href="{{ helper::storefront_url($storeinfo, '/login/google-user') }}" @endif
                                        class="btn btn-store-outline border-dark d-block m-0 w-100 mb-3 mb-sm-0 {{ session()->get('direction') == 2 ? 'ms-2' : 'me-2' }}">
                                        <img src="{{ url(env('ASSETPATHURL') . 'front/images/google.svg') }}"
                                            alt="goole" class="social-login">
                                        <span
                                            class="text-dark color-changer px-1">{{ trans('labels.sign_in') }}</span>
                                    </a>
                                </div>
                            @endif
                        @endif
                    @endif
                @else
                    @if (@helper::checkaddons('google_login'))
                        @if (@$setting->google_mode == 1)
                            <div class="d-sm-flex justify-content-between my-3">
                                <a @if (env('Environment') == 'sendbox') onclick="myFunction()" @else href="{{ helper::storefront_url($storeinfo, '/login/google-user') }}" @endif
                                    class="btn btn-store-outline border-dark d-block m-0 w-100 mb-3 mb-sm-0 {{ session()->get('direction') == 2 ? 'ms-2' : 'me-2' }}">
                                    <img src="{{ url(env('ASSETPATHURL') . 'front/images/google.svg') }}"
                                        alt="goole" class="social-login">
                                    <span class="text-dark color-changer px-1">{{ trans('labels.sign_in') }}</span>
                                </a>
                            </div>
                        @endif
                    @endif
                @endif

                @if (@helper::checkaddons('subscription'))
                    @if (@helper::checkaddons('facebook_login'))
                        @php
                            $checkplan = App\Models\Transaction::where('vendor_id', @$storeinfo->id)
                                ->orderByDesc('id')
                                ->first();
                            $user = App\Models\User::where('id', @$storeinfo->id)->first();
                            if (@$user->allow_without_subscription == 1) {
                                $facebook_login = 1;
                            } else {
                                $facebook_login = @$checkplan->facebook_login;
                            }
                        @endphp
                        @if ($facebook_login == 1)
                            @if (@$setting->facebook_mode == 1)
                                <div class="d-sm-flex justify-content-between my-3">
                                    <a @if (env('Environment') == 'sendbox') onclick="myFunction()" @else href="{{ helper::storefront_url($storeinfo, '/login/facebook-user') }}" @endif
                                        class="btn btn-store-outline border-dark d-block m-0 w-100">
                                        <img src="{{ url(env('ASSETPATHURL') . 'front/images/facebook.svg') }}"
                                            alt="goole" class="social-login">
                                        <span
                                            class="text-dark color-changer px-1">{{ trans('labels.sign_in') }}</span>
                                    </a>
                                </div>
                            @endif
                        @endif
                    @endif
                @else
                    @if (@helper::checkaddons('facebook_login'))
                        @if (@$setting->facebook_mode == 1)
                            <div class="d-sm-flex justify-content-between my-3">
                                <a @if (env('Environment') == 'sendbox') onclick="myFunction()" @else href="{{ helper::storefront_url($storeinfo, '/login/facebook-user') }}" @endif
                                    class="btn btn-store-outline border-dark d-block m-0 w-100">
                                    <img src="{{ url(env('ASSETPATHURL') . 'front/images/facebook.svg') }}"
                                        alt="goole" class="social-login">
                                    <span class="text-dark color-changer px-1">{{ trans('labels.sign_in') }}</span>
                                </a>
                            </div>
                        @endif
                    @endif
                @endif

            </div>

            <!-------------------------- register -------------------------->
            <div class="register input-14 d-none" id="register_form">
                <form action="{{ helper::storefront_url($storeinfo, '/register_customer') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">{{ trans('labels.name') }}</label>
                        <input type="text" class="form-control rounded-2 p-3" id="name" name="name"
                            placeholder="{{ trans('labels.name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="emailid" class="form-label fw-semibold">{{ trans('labels.email') }}</label>
                        <input type="email" class="form-control rounded-2 p-3" id="emailid" name="email"
                            placeholder="{{ trans('labels.email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="mobile" class="form-label fw-semibold">{{ trans('labels.mobile') }}</label>
                        <input type="number" class="form-control rounded-2 p-3" id="mobile" name="mobile"
                            placeholder="{{ trans('labels.mobile') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">{{ trans('labels.password') }}</label>
                        <input type="password" class="form-control rounded-2 p-3" id="password" name="password"
                            placeholder="{{ trans('labels.password') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmpassword"
                            class="form-label fw-semibold">{{ trans('labels.confirm_password') }}</label>
                        <input type="password" class="form-control rounded-2 p-3" id="confirmpassword"
                            name="confirmpassword" placeholder="{{ trans('labels.confirm_password') }}" required>
                    </div>

                    @include('landing.layout.recaptcha')

                    <div class="mb-3 d-flex align-items-center">
                        <input type="checkbox" class="form-check-input p-0" id="exampleCheck2" required checked>
                        <label class="form-check-label fw-normal mx-2"
                            for="exampleCheck2">{{ trans('labels.i_accept_the') }} <a
                                href="{{ helper::storefront_url($storeinfo, '/terms_condition') }}"
                                class="fw-semibold text-secondary">{{ trans('labels.terms_condition') }}</a></label>
                    </div>
                    <button type="submit" id="btnsignup"
                        class="btn btn-store d-block w-100 p-3">{{ trans('labels.signup') }}</button>
                    <p class="text-center color-changer mb25 mt10">{{ trans('labels.already_account') }} <a
                            href="javascript:void(0)"
                            class="fw-semibold login_btn text-secondary">{{ trans('labels.sign_in') }}</a></p>
                </form>
            </div>

            <!-------------------------- forgot password -------------------------->
            <div class="forgotpassword input-14 d-none" id="forgot_password_form">
                <form action="{{ helper::storefront_url($storeinfo, '/send_password') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="forgetemailid"
                            class="form-label fw-semibold">{{ trans('labels.email') }}</label>
                        <input type="email" class="form-control rounded-2 p-3" id="forgetemailid" name="email"
                            placeholder="{{ trans('labels.email') }}" required>
                    </div>
                    <button type="submit" id="btnsubmit"
                        class="btn btn-store d-block w-100 p-3">{{ trans('labels.submit') }}</button>
                    <p class="text-center mb25 color-changer mt10">{{ trans('labels.dont_have_account') }} <a
                            href="javascript:void(0)"
                            class="fw-semibold create_account_btn text-secondary">{{ trans('labels.signup') }}</a>
                    </p>
                </form>
            </div>

        </div>
    </div>


    <!--------------- rating sidebar --------------->
    @if (@helper::checkaddons('product_reviews'))
        <div class="" id="viewreviewsbody"></div>
    @endif


    <main id="main-content">

        <!-- navbar -->
        @if (helper::appdata(@$storeinfo->id)->template != 11)
            <div class="d-none d-lg-block">
                <nav class="top-header border-bottom">
                    <div class="container">
                        <div class="d-flex align-items-center mobile-header">
                            <div class="col-md-6 p-0 ">
                                <div class="header-contact">
                                    <a href="tel:{{ helper::appdata(@$storeinfo->id)->contact }}" target="_blank"
                                        class="color-changer"><i class="fa-light fa-phone-flip"></i><span
                                            class="mx-2">{{ helper::appdata(@$storeinfo->id)->contact }}</span></a>

                                    <a href="mailto:{{ helper::appdata(@$storeinfo->id)->email }}" target="_blank"
                                        class="color-changer"><i class="fa-light fa-envelope"></i><span
                                            class="mx-2">{{ helper::appdata(@$storeinfo->id)->email }}</span></a>
                                </div>
                            </div>
                            <div class="col-md-6 p-0 ">
                                <div class="header-social">
                                    <div class="social-media d-none d-lg-block">
                                        <ul class="d-flex gap-2 m-0 p-0">
                                            @foreach (@helper::getsociallinks(@$storeinfo->id) as $links)
                                                <li><a href="{{ $links->link }}" target="_blank"
                                                        class="social-rounded fb p-0">{!! $links->icon !!}</a>
                                                </li>
                                            @endforeach

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        @endif

        <!-- navbar -->

        @php
            $current_url = Request()->url();
            $home_url = helper::storefront_url($storeinfo);
        @endphp
        <!-- mine header -->

        <div
            class="navbar main-header main-sticky-top p-0 {{ helper::appdata(@$storeinfo->id)->template == 10 ? 'header-10-bg top-0' : '' }}">
            <div class="container">

                <div class="col-xxl-4 col-xl-5 col-lg-5 d-none d-xl-block main-menu">
                    <ul class="d-flex gap-4 p-0 m-0">
                        <li>
                            <a class="{{ helper::storefront_request_is($storeinfo, '/') ? 'menu-active' : '' }}"
                                href="{{ helper::storefront_url($storeinfo) }}">{{ trans('labels.home') }}</a>
                        </li>
                        @if (helper::appdata(@$storeinfo->id)->online_order == 1)
                            <li><a class="{{ helper::storefront_request_is($storeinfo, '/find-order') ? 'menu-active' : '' }}"
                                    href="{{ helper::storefront_url($storeinfo, '/find-order') }}">{{ trans('labels.track_order') }}</a>
                            </li>
                        @endif
                        @if (@helper::checkaddons('blog'))
                            @if (helper::getblogs(@$storeinfo->id)->count() > 0)
                                <li><a class="{{ helper::storefront_request_is($storeinfo, '/blogs') ? 'menu-active' : '' }}"
                                        href="{{ helper::storefront_url($storeinfo, '/blogs') }}">{{ trans('labels.blogs') }}
                                    </a>
                                </li>
                            @endif
                        @endif

                        <li><a class="{{ helper::storefront_request_is($storeinfo, '/contact') ? 'menu-active' : '' }}"
                                href="{{ helper::storefront_url($storeinfo, '/contact') }}">{{ trans('labels.contact_us') }}</a>
                        </li>
                        @if (helper::getfaqs(@$storeinfo->id)->count() > 0)
                            <li><a class="{{ helper::storefront_request_is($storeinfo, '/faqs') ? 'menu-active' : '' }}"
                                    href="{{ helper::storefront_url($storeinfo, '/faqs') }}">{{ trans('labels.faqs') }}</a>
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="col-auto">
                    <div class="d-flex align-items-center gap-3">
                        <!-- mobile sidebar trigger -->
                        @if (@helper::checkaddons('customer_login'))
                            @if (helper::appdata(@$storeinfo->id)->checkout_login_required == 1)
                                <li class="d-block d-xl-none">
                                    <a type="button" data-bs-toggle="offcanvas" data-bs-target="#mobile-sidebar"
                                        aria-controls="offcanvasExample"
                                        class="d-flex justify-content-center align-items-center">
                                        <i class="fa-light fa-bars fs-4 color-changer text-dark"></i>
                                    </a>
                                </li>
                            @endif
                        @endif
                        
                        <script>
                document.addEventListener("DOMContentLoaded", function() {
                    let logo = "";


                    if (localStorage.getItem('theme') === 'dark') {
                        logo = "{{ helper::image_path(helper::appdata(@$storeinfo->id)->darklogo) }}";
                    } else {
                        logo = "{{ helper::image_path(helper::appdata(@$storeinfo->id)->logo) }}";
                    }

                    // Set logo image src
                    document.getElementById('logoimage2').src = logo;
                });
            </script>

            <a href="{{ helper::storefront_url($storeinfo) }}">
                <img id="logoimage2" src="" alt="logo" class="object-fit-cover my-2 logo-h-55-px">
            </a>

                       

                    </div>
                </div>

                <!-- mobile lag button -->
                <div class="col-xxl-4 col-xl-5">

                    <!-- right side option -->
                    @php
                        $languages = explode('|', helper::appdata(@$storeinfo->id)->languages);
                        $currencies = explode('|', helper::appdata(@$storeinfo->id)->currencies);
                    @endphp

                    <ul class="d-flex align-items-center justify-content-end gap-lg-4 gap-3 m-0 p-0">
                        @if (@helper::checkaddons('language'))
                            @if (count($languages) > 1)
                                <li>
                                    <div class="dropdown language-dropdown lag-btn">
                                        <a class="dropdown-toggle open-btn bg-transparent p-0 border-0 m-0"
                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-light fa-globe fs-5 color-changer"></i>
                                        </a>
                                        <ul
                                            class="dropdown-menu p-0 bg-body-secondary border-0 shadow mt-2 {{ session()->get('direction') == 2 ? 'min-dropdown-rtl' : 'min-dropdown-ltr' }}">
                                            @foreach (helper::available_language(@$storeinfo->id) as $languagelist)
                                                @if (in_array($languagelist->code, explode('|', helper::appdata(@$storeinfo->id)->languages)))
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center p-2 gap-2"
                                                            href="{{ URL::to('/lang/change?lang=' . $languagelist->code) }}">
                                                            <img src="{{ helper::image_path($languagelist->image) }}"
                                                                alt="" class="img-fluid lag-img">
                                                            <span class="fw-normal">
                                                                {{ $languagelist->name }}
                                                            </span>
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </li>
                            @endif
                        @endif
                        <li>
                            <div class="dropdown language-dropdown lag-btn">
                                <a role="button" data-bs-toggle="dropdown" aria-expanded="false"
                                    class="dropdown-toggle open-btn bg-transparent p-0 border-0 m-0">
                                    <i class="fa-regular fa-circle-half-stroke fs-5 color-changer"></i>
                                </a>
                                <ul
                                    class="dropdown-menu p-0 bg-body-secondary border-0 shadow mt-2 {{ session()->get('direction') == 2 ? 'min-dropdown-rtl' : 'min-dropdown-ltr' }}">
                                    <li>
                                        <a class="dropdown-item d-flex cursor-pointer align-items-center p-2 gap-2"
                                            onclick="setLightMode()">
                                            <i class="fa-light fa-lightbulb fs-6"></i>
                                            <span>Light</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex cursor-pointer align-items-center p-2 gap-2"
                                            onclick="setDarkMode()">
                                            <i class="fa-solid fa-moon fs-6"></i>
                                            <span>Dark</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        @if (@helper::checkaddons('currency_settigns'))
                            @if (count($currencies) > 1)
                                <li>
                                    <div class="dropdown language-dropdown lag-btn">
                                        <a class="dropdown-toggle open-btn bg-transparent p-0 border-0 m-0"
                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="fs-5 color-changer">
                                                {{ session()->get('currency') }}
                                            </span>
                                        </a>

                                        <ul
                                            class="dropdown-menu p-0 bg-body-secondary border-0 shadow mt-2 {{ session()->get('direction') == 2 ? 'min-dropdown-rtl' : 'min-dropdown-ltr' }}">
                                            @foreach (helper::available_currency(@$storeinfo->id) as $currencylist)
                                                @if (in_array($currencylist->code, explode('|', helper::appdata(@$storeinfo->id)->currencies)))
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center p-2 gap-2"
                                                            href="{{ URL::to('/currency/change?currency=' . $currencylist['code']) }}">
                                                            <p class="fs-7">
                                                                {{ $currencylist['currency'] . '  ' . $currencylist['name'] }}
                                                            </p>
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </li>
                            @endif
                        @endif
                        <li class="d-none d-lg-block">
                            <a href="{{ helper::storefront_url($storeinfo, '/search') }}">
                                <i class="fa-light fa-magnifying-glass fs-5 color-changer"></i></a>
                        </li>

                        @if (helper::appdata(@$storeinfo->id)->online_order == 1)
                            <li class="shopping-cart d-none d-lg-block">

                                <a href="{{ helper::storefront_url($storeinfo, '/cart/') }}">
                                    <i class="fa-light fa-bag-shopping fs-5 color-changer"></i></a>
                                <div class="cart-count {{ session()->get('cart') > 0 ? '' : 'd-none' }} {{ session()->get('direction') == 2 ? 'left_10px' : '' }}"
                                    id="cartcnt">{{ session()->get('cart') }}</div>
                            </li>
                        @endif

                        <!-- loginpage trigar -->
                        @if (@helper::checkaddons('customer_login'))
                            @if (helper::appdata(@$storeinfo->id)->checkout_login_required == 1)
                                <li class="d-lg-block d-none">
                                    @if (Auth::user() && Auth::user()->type == 3)
                                        <a href="{{ helper::storefront_url($storeinfo, '/profile') }}">
                                            <i class="fa-light fa-user fs-5"></i>
                                        </a>
                                    @else
                                        <a type="button" data-bs-toggle="offcanvas" data-bs-target="#loginpage"
                                            id="btnlogin" aria-controls="loginpage">
                                            <i class="fa-light fa-user fs-5"></i>
                                        </a>
                                    @endif
                                </li>
                                <!-- loginpage trigar -->
                            @endif
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <!-- mine header -->

        <!----------------------- mobile menu footer ----------------------->
        <div class="mobile-menu-footer d-none">
            <ul class="p-0 m-0">
                <li class="{{ helper::storefront_request_is($storeinfo, '/') ? 'mobile-active' : '' }}">
                    <a href="{{ helper::storefront_url($storeinfo) }}">
                        <i class="fa-light fa-house"></i>
                        <span>{{ trans('labels.home') }}</span>
                    </a>
                </li>
                <li class="{{ helper::storefront_request_is($storeinfo, '/search') ? 'mobile-active' : '' }}">
                    <a href="{{ helper::storefront_url($storeinfo, '/search') }}">
                        <i class="fa-light fa-magnifying-glass"></i>
                        <span>{{ trans('labels.search') }}</span>
                    </a>
                </li>
                <li class="{{ helper::storefront_request_is($storeinfo, '/category') ? 'mobile-active' : '' }}">
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#catModal">
                        <i class="fa-light fa-box-archive"></i>
                        <span>{{ trans('labels.category') }}</span>
                    </a>
                </li>
                @if (helper::appdata(@$storeinfo->id)->online_order == 1)
                    <li class="{{ helper::storefront_request_is($storeinfo, '/cart') ? 'mobile-active' : '' }}">
                        <a href="{{ helper::storefront_url($storeinfo, '/cart/') }}">

                            <i class="fa-light fa-bag-shopping position-relative">
                                @if (session()->get('cart') > 0)
                                    <div class="mobile-cart-count" id="cartcnt">{{ session()->get('cart') }}</div>
                                @endif
                            </i>
                            <span>{{ trans('labels.cart') }}</span>
                        </a>
                    </li>
                @endif


                @if (@helper::checkaddons('customer_login'))
                    @if (helper::appdata(@$storeinfo->id)->checkout_login_required == 1)
                        @if (helper::appdata(@$storeinfo->id)->online_order == 1)
                            <li class="{{ helper::storefront_request_is($storeinfo, '/profile') ? 'mobile-active' : '' }}">
                                @if (Auth::user() && Auth::user()->type == 3)
                                    <a href="{{ helper::storefront_url($storeinfo, '/profile') }}">
                                        <i class="fa-light fa-user"></i>
                                        <span>{{ trans('labels.account') }}</span></a>
                                @else
                                    <a data-bs-toggle="offcanvas" data-bs-target="#loginpage">
                                        <i class="fa-light fa-user"></i>
                                        <span>{{ trans('labels.account') }}</span>
                                    </a>
                                @endif

                            </li>
                        @endif
                    @endif
                @else
                    <li class="">
                        <a data-bs-toggle="offcanvas" data-bs-target="#mobile-sidebar"
                            aria-controls="offcanvasExample">
                            <i class="fa-light fa-ellipsis-vertical"></i>
                            <span>{{ trans('labels.more') }}</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>


        <div id="success-msg" class="alert alert-dismissible mt-3" style="display: none;">
            <span id="msg"></span>
        </div>
        <div id="error-msg" class="alert alert-dismissible mt-3" style="display: none;">
            <span id="ermsg"></span>
        </div>
        <style>
            :root {
                /* Color */
                --primary-color: #000;
                --primary-bg-color: #f4f4f8;
                /* --body-color: #f7f7f7; */
                --active-tab: #3ba2a484;

                /* Hover Color */
                --bs-primary: {{ helper::appdata(@$storeinfo->id)->primary_color }};
                --primary-bg-color-hover: #000;
                --bs-secondary: {{ helper::appdata(@$storeinfo->id)->secondary_color }};

                --active-menu: {{ helper::appdata(@$storeinfo->id)->primary_color }}30;
                --in-stock: #28a745;
                --out-stock: #D41A1A;
                --bs-primary-srg: color-mix(in srgb, var(--bs-primary), transparent 90%);
                --bs-secondary-srg: color-mix(in srgb, var(--bs-secondary), transparent 90%);

            }
        </style>
        @if (!helper::storefront_request_is($storeinfo, '/detail-*'))
            @include('front.theme.timer')
        @endif
        @include('cookie-consent::index')
        {{-- <span class="d-block {{ helper::appdata(@$storeinfo->id)->template == 11 ? 'mb-40px' : '' }}"></span> --}}
    </main>
