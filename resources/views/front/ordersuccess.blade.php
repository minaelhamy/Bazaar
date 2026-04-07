<!DOCTYPE html>
<html lang="en" dir="{{ session()->get('direction') == 2 ? 'rtl' : 'ltr' }}">

<head>
    <title>{{ helper::appdata($storeinfo->id)->website_title }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:title" content="{{ helper::appdata($storeinfo->id)->meta_title }}" />
    <meta property="og:description" content="{{ helper::appdata($storeinfo->id)->meta_description }}" />
    <meta property="og:image" content='{{ helper::image_path(helper::appdata($storeinfo->id)->og_image) }}' />
    <link rel="icon" href='{{ helper::image_path(helper::appdata($storeinfo->id)->favicon) }}' type="image/x-icon">
    <!-- favicon-icon  -->
    <link rel="stylesheet" type="text/css" href="{{ url(env('ASSETPATHURL') . 'front/css/all.min.css') }}">
    <!-- font-awsome css  -->
    <link rel="stylesheet" type="text/css" href="{{ url(env('ASSETPATHURL') . 'front/css/bootstrap.min.css') }}">
    <!-- bootstrap css -->
    <link rel="stylesheet" type="text/css" href="{{ url(env('ASSETPATHURL') . 'front/css/owl.carousel.min.css') }}">
    <!-- owl.carousel css -->
    <link rel="stylesheet" type="text/css" href="{{ url(env('ASSETPATHURL') . 'front/css/style.css') }}">
    <!-- style css  -->
    <link rel="stylesheet" type="text/css" href="{{ url(env('ASSETPATHURL') . 'front/css/fonts.css') }}">
    <!-- Fonts css  -->
    <link rel="stylesheet" type="text/css" href="{{ url(env('ASSETPATHURL') . 'front/css/responsive.css') }}">
    <!-- responsive css  -->
    <link rel="stylesheet" type="text/css"
        href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/sweetalert/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'front/css/dataTables.bootstrap4.min.css') }}">
    <style>
        #splash {
            background-color: #000;
        }
    </style>
    <style>
        :root {
            --primary-font: 'Lexend', sans-serif;
            --font-family: var(--font-family);
            /* Color */
            --primary-color: #000;
            --primary-bg-color: #f4f4f8;
            /* --body-color: #f7f7f7; */
            --active-tab: #3ba2a484;
            /* Hover Color */
            --bs-primary: {{ helper::appdata($storeinfo->id)->primary_color }};
            --primary-bg-color-hover: #000;
            --bs-secondary: {{ helper::appdata($storeinfo->id)->secondary_color }};
            --active-menu: {{ helper::appdata($storeinfo->id)->primary_color }}30;
        }
    </style>

</head>

<body class="light">
    <section class="order-success-sec">
        <div class="container">
            <div class="text-center order-success-img">

                <div class="justify-items-center align-items-center ">
                    <img src="{{ helper::image_path(helper::appdata($storeinfo->id)->order_success_image) }}"
                        class="success-img m-auto" alt="ordersuccess" srcset="">
                </div>
                <h4 class="mb-5 order-title color-changer {{ session()->get('direction') == '2' ? 'text-right' : '' }}">
                    {{ trans('labels.order_successfull') }}</h4>
                <div class="d-md-flex mb-4 col-md-8 col-12 mx-auto">
                    <input type="text"
                        value="{{ helper::storefront_url($storeinfo, '/find-order?order=' . $order_number) }}"
                        id="data" class="form-control p-3 rounded-2 mb-3 mb-md-0" readonly>
                    <button onclick="copytext('{{ trans('labels.copied') }}')" class="btn btn-store mx-sm-2 btn-100">
                        <span class="tooltiptext" id="tool">{{ trans('labels.copy') }}</span>
                    </button>
                </div>
                <div class="d-md-flex justify-content-center align-content-center">
                    <a href="{{ helper::storefront_url($storeinfo) }}"
                        class="btn mx-md-2 btn-store mb-3 mb-md-0">
                        <i class="far fa-home-lg mx-2"></i>
                        {{ trans('labels.continue_shop') }}
                    </a>
                    @if (@helper::checkaddons('subscription'))
                        @if (@helper::checkaddons('whatsapp_message'))
                            @php
                                $checkplan = App\Models\Transaction::where('vendor_id', $storeinfo->id)
                                    ->orderByDesc('id')
                                    ->first();
                                $user = App\Models\User::where('id', $storeinfo->id)->first();
                                if (@$user->allow_without_subscription == 1) {
                                    $whatsapp_message = 1;
                                } else {
                                    $whatsapp_message = @$checkplan->whatsapp_message;
                                }
                            @endphp
                            @if ($whatsapp_message == 1 && @whatsapp_helper::whatsapp_message_config($storeinfo->id)->order_created == 1)
                                @if (@whatsapp_helper::whatsapp_message_config($storeinfo->id)->message_type == 2)
                                    <a href="https://api.whatsapp.com/send?phone={{ @whatsapp_helper::whatsapp_message_config($storeinfo->id)->whatsapp_number }}&text={{ $whmessage }}"
                                        target="_blank" class="btn btn-store btn-whatsapp mb-3 mb-md-0">
                                        <i class="fab fa-whatsapp mx-2"></i>
                                        {{ trans('labels.whatsapp_message') }}
                                    </a>
                                @endif
                            @endif
                        @endif
                    @else
                        @if (@helper::checkaddons('whatsapp_message'))
                            @if (@whatsapp_helper::whatsapp_message_config($storeinfo->id)->order_created == 1)
                                @if (@whatsapp_helper::whatsapp_message_config($storeinfo->id)->message_type == 2)
                                    <a href="https://api.whatsapp.com/send?phone={{ @whatsapp_helper::whatsapp_message_config($storeinfo->id)->whatsapp_number }}&text={{ $whmessage }}"
                                        target="_blank" class="btn btn-store btn-whatsapp mb-3 mb-md-0">
                                        <i class="fab fa-whatsapp mx-2"></i>
                                        {{ trans('labels.whatsapp_message') }}
                                    </a>
                                @endif
                            @endif
                        @endif
                    @endif

                    @if (@helper::checkaddons('subscription'))
                        @if (@helper::checkaddons('telegram_message'))
                            @php
                                $checkplan = App\Models\Transaction::where('vendor_id', $storeinfo->id)
                                    ->orderByDesc('id')
                                    ->first();
                                $user = App\Models\User::where('id', $storeinfo->id)->first();
                                if (@$user->allow_without_subscription == 1) {
                                    $telegram_message = 1;
                                } else {
                                    $telegram_message = @$checkplan->telegram_message;
                                }
                            @endphp
                            @if ($telegram_message == 1 && helper::telegramdata($storeinfo->id)->order_created == 1)
                                <a href="{{ helper::storefront_url($storeinfo, '/telegram/' . $order_number . '') }}"
                                    class="btn btn-store btn-telegram mx-md-2 mb-3 mb-md-0">
                                    <i class="fab fa-telegram mx-2"></i>{{ trans('labels.telegram_message') }}</a>
                            @endif
                        @endif
                    @else
                        @if (@helper::checkaddons('telegram_message'))
                            @if (helper::telegramdata($storeinfo->id)->order_created == 1)
                                <a href="{{ helper::storefront_url($storeinfo, '/telegram/' . $order_number . '') }}"
                                    class="btn btn-store btn-telegram mx-md-2 mb-3 mb-md-0">
                                    <i class="fab fa-telegram mx-2"></i>{{ trans('labels.telegram_message') }}</a>
                            @endif
                        @endif
                    @endif

                </div>
            </div>
        </div>
    </section>
    <!-- jquery -->
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/jquery/jquery.min.js') }}"></script>

    <!-- bootstrap js -->
    <script src="{{ url(env('ASSETPATHURL') . 'front/js/bootstrap.bundle.js') }}"></script>
    <script>
        window.onload = function() {
            setTimeout(() => {
                document.body.classList.add('loaded');
                $('body').find('.placeholder').removeClass('placeholder , d-inline-block , mb-2 , mx-1');
            }, 1000)
        };
    </script>
    <script>
        function copytext(copied) {
            "use strict";
            var copyText = document.getElementById("data");
            copyText.select();
            document.execCommand("copy");
            document.getElementById("tool").innerHTML = copied;
        }
    </script>
    <script>
        function setLightMode() {
            document.body.classList.remove('dark');
            document.body.classList.add('light');
            localStorage.setItem('theme', 'light');
        }

        function setDarkMode() {
            document.body.classList.remove('light');
            document.body.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }

        // Load saved theme on page load
        $(document).ready(function() {
            if (localStorage.getItem('theme') === 'dark') {
                setDarkMode();
            } else {
                setLightMode();
            }
        });
    </script>
</body>

</html>
