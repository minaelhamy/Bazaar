<!DOCTYPE html>
<html lang="en" dir="{{ session()->get('direction') == 2 ? 'rtl' : 'ltr' }}"  class="light">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta property="og:title" content="{{ helper::appdata('')->meta_title }}" />
    <meta property="og:description" content="{{ helper::appdata('')->meta_description }}" />
    <meta property="og:image" content="{{ helper::image_path(helper::appdata('')->og_image) }}" />

    <script>
        const theme = localStorage.getItem('theme');
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.add('light');
        }
    </script>

    <link rel="icon" href="{{ helper::image_path(helper::appdata('')->favicon) }}" type="image" sizes="16x16">
    <title>{{ helper::appdata('')->website_title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet"
        href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/bootstrap/bootstrap-select.min.css') }}">
    <!--multi-selection css-->
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/fontawesome/all.min.css') }}">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/toastr/toastr.min.css') }}">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/sweetalert/sweetalert2.min.css') }}">
    <!-- Sweetalert CSS -->
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/style.css') }}"><!-- Custom CSS -->
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/hatchers-brand.css') }}">
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/responsive.css') }}">
    <!-- Responsive CSS -->
    <link rel="stylesheet"
        href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/timepicker/jquery.timepicker.min.css') }}">
    <link rel="stylesheet"
        href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/datatables/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet"
        href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/datatables/buttons.dataTables.min.css') }}">
    <!-- magnific-popup -->
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/magnific/magnific-popup.min.css') }}">

    <style>
        :root {
            /* Color */
            --bs-primary: {{ helper::appdata('')->primary_color }};
            --bs-secondary: {{ helper::appdata('')->secondary_color }};
        }
    </style>
</head>

<body>
    @php
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
    @endphp
    <main>
        <div class="wrapper">
            @include('admin.layout.header')
            <div class="content-wrapper">
                @include('admin.layout.sidebar')
                <div class="{{ session()->get('direction') == 2 ? 'main-content-rtl' : 'main-content' }}">
                    <div class="page-content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12 ml-sm-auto">
                                    @if (env('Environment') == 'live')
                                        @if (request()->is('admin/custom_domain'))
                                            <div class="alert alert-warning" role="alert">
                                                {{ trans('messages.custom_domain_message') }}
                                            </div>
                                        @endif
                                        @if (request()->is('admin/apps'))
                                            <div class="alert alert-warning" role="alert">
                                                {{ trans('messages.addon_message') }}
                                            </div>
                                        @endif
                                    @endif
                                    @if (Auth::user()->type == 2)
                                        <?php
                                        $checkplan = helper::checkplan(Auth::user()->id, '');
                                        $plan = json_decode(json_encode($checkplan));
                                        ?>
                                        @if (@$plan->original->status == '2' && @$plan->original->showclick != 2)
                                            <div class="alert alert-warning" role="alert">
                                                {{ @$plan->original->message }}{{ empty($plan->original->expdate) ? '' : ':' . $plan->original->expdate }}
                                                @if (@$plan->original->showclick == 1)
                                                    <u><a
                                                            href="{{ URL::to('/admin/plan') }}">{{ trans('labels.click_here') }}</a></u>
                                                @endif
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <!--Modal: order-modal-->
                            <div class="modal fade" id="order-modal" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-notify modal-info" role="document">
                                    <div class="modal-content text-center">
                                        <div class="modal-header d-flex justify-content-center">
                                            <p class="heading color-changer">{{ trans('messages.be_up_to_date') }}</p>
                                        </div>
                                        <div class="modal-body color-changer"><i
                                                class="fa fa-bell fa-4x animated rotateIn mb-4"></i>
                                            <p>{{ trans('messages.new_order_arrive') }}</p>
                                        </div>
                                        <div class="modal-footer flex-center">
                                            <a role="button" class="btn btn-secondary waves-effect"
                                                onClick="window.location.reload();"
                                                data-bs-dismiss="modal">{{ trans('labels.okay') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>

            <!----- theme sidebar ----->
            <div class="offcanvas {{ session()->get('direction') == 2 ? 'offcanvas-start' : 'offcanvas-end ' }}"
                data-bs-scroll="true" tabindex="-1" id="themelabel" aria-labelledby="offcanvasWithBothOptionsLabel">

                <div class="offcanvas-header justify-content-between">
                    <h5 class="offcanvas-title color-changer" id="offcanvasWithBothOptionsLabel">All theme</h5>
                    <button type="button" class="bg-transparent border-0 m-0" data-bs-dismiss="offcanvas"
                        aria-label="Close">
                        <i class="fa-regular fa-xmark fs-4 color-changer"></i>
                    </button>
                </div>

                <div class="offcanvas-body">
                    <p class="color-changer">Try scrolling the rest of the page to see this option in action.</p>
                </div>

            </div>

            <footer class="py-3 text-center bg-white fixed-bottom border-top">
                <span>{{ helper::appdata('')->copyright }}</span>
            </footer>
        </div>

        <!--theme image Modal -->
        <div class="modal fade" id="themeinfo" tabindex="-1" aria-labelledby="themeinfoLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header justify-content-between">
                        <h5 class="modal-title text-dark color-changer" id="themeinfoLabel"></h5>
                        <button type="button" class="bg-transparent border-0" data-bs-dismiss="modal">
                            <i class="fa-regular fa-xmark fs-4 color-changer"></i>
                        </button>
                    </div>
                    <div class="modal-body" id="theme_modalbody">
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/jquery/jquery.min.js') }}"></script><!-- jQuery JS -->
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script><!-- Bootstrap JS -->
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/bootstrap/bootstrap-select.min.js') }}"></script><!-- Bootstrap multi-select JS -->
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/toastr/toastr.min.js') }}"></script><!-- Toastr JS -->
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/sweetalert/sweetalert2.min.js') }}"></script><!-- Sweetalert JS -->
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/chartjs/chart_3.9.1.min.js') }}"></script>
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/datatables/jquery.dataTables.min.js') }}"></script><!-- Datatables JS -->
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/datatables/dataTables.bootstrap5.min.js') }}"></script><!-- Datatables Bootstrap5 JS -->
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/datatables/dataTables.buttons.min.js') }}"></script><!-- Datatables Buttons JS -->
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/datatables/jszip.min.js') }}"></script><!-- Datatables Excel Buttons JS -->
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/datatables/pdfmake.min.js') }}"></script><!-- Datatables Make PDF Buttons JS -->
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/datatables/vfs_fonts.js') }}"></script><!-- Datatables Export PDF Buttons JS -->
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/datatables/buttons.html5.min.js') }}"></script><!-- Datatables Buttons HTML5 JS -->
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/magnific/magnific-popup.min.js') }}"></script><!-- magnific-popup js -->

    <script>
        var are_you_sure = "{{ trans('messages.are_you_sure') }}";
        var yes = "{{ trans('messages.yes') }}";
        var no = "{{ trans('messages.no') }}";
        var cancel = "{{ trans('labels.cancel') }}";
        let wrong = "{{ trans('messages.wrong') }}";
        let env = "{{ env('Environment') }}";
        var time_format = "{{ helper::appdata($vendor_id)->time_format }}";
        // pdf and excel file name table wise
        var filename = "";
        var title = "";
        if ("{{ Auth::user()->type == 2 }}" && "{{ request()->is('admin/dashboard') }}") {
            filename = "trans('labels.processing_orders')";
            title = "trans('labels.processing_orders')";
        }
        if ("{{ Auth::user()->type == 1 }}" && "{{ request()->is('admin/dashboard') }}") {
            filename = "trans('labels.today_transaction')";
            title = "trans('labels.today_transaction')";
        }
        if ("{{ request()->is('admin/orders*') }}" || "{{ request()->is('admin/report') }}") {
            filename = "{{ trans('labels.orders') }}";
            title = "{{ trans('labels.orders') }}";
        }
        if ("{{ request()->is('admin/customers*') }}") {
            filename = "{{ trans('labels.customers') }}";
            title = "{{ trans('labels.customers') }}";
        }
        if ("{{ request()->is('admin/users*') }}") {
            filename = "{{ trans('labels.users') }}";
            title = "{{ trans('labels.users') }}";
        }
        if ("{{ request()->is('admin/countries*') }}") {
            filename = "{{ trans('labels.cities') }}";
            title = "{{ trans('labels.cities') }}";
        }
        if ("{{ request()->is('admin/cities*') }}") {
            filename = "{{ trans('labels.areas') }}";
            title = "{{ trans('labels.areas') }}";
        }
        if ("{{ request()->is('admin/how_it_works*') }}") {
            filename = "{{ trans('labels.how_it_works') }}";
            title = "{{ trans('labels.how_it_works') }}";
        }
        if ("{{ request()->is('admin/themes*') }}") {
            filename = "{{ trans('labels.theme_images') }}";
            title = "{{ trans('labels.theme_images') }}";
        }
        if ("{{ request()->is('admin/features*') }}") {
            filename = "{{ trans('labels.features') }}";
            title = "{{ trans('labels.features') }}";
        }
        if ("{{ request()->is('admin/promotionalbanners*') }}") {
            filename = "{{ trans('labels.promotional_banners') }}";
            title = "{{ trans('labels.promotional_banners') }}";
        }
        if ("{{ request()->is('admin/transaction') }}") {
            filename = "{{ trans('labels.transactions') }}";
            title = "{{ trans('labels.transactions') }}";
        }
        if ("{{ request()->is('admin/shipping-area') }}") {
            filename = "{{ trans('labels.shipping_area') }}";
            title = "{{ trans('labels.shipping_area') }}";
        }
        if ("{{ request()->is('admin/blogs') }}") {
            filename = "{{ trans('labels.blogs') }}";
            title = "{{ trans('labels.blogs') }}";
        }
        if ("{{ request()->is('admin/testimonials') }}") {
            filename = "{{ trans('labels.testimonials') }}";
            title = "{{ trans('labels.testimonials') }}";
        }
        if ("{{ request()->is('admin/faqs') }}") {
            filename = "{{ trans('labels.faqs') }}";
            title = "{{ trans('labels.faqs') }}";
        }
        if ("{{ request()->is('admin/categories') }}") {
            filename = "{{ trans('labels.categories') }}";
            title = "{{ trans('labels.categories') }}";
        }
        if ("{{ request()->is('admin/products') }}") {
            filename = "{{ trans('labels.products') }}";
            title = "{{ trans('labels.products') }}";
        }
        if ("{{ request()->is('admin/sliders') }}") {
            filename = "{{ trans('labels.sliders') }}";
            title = "{{ trans('labels.sliders') }}";
        }
        if ("{{ request()->is('admin/banner') }}") {
            filename = "{{ trans('labels.banners') }}";
            title = "{{ trans('labels.banners') }}";
        }
        if ("{{ request()->is('admin/coupons') }}") {
            filename = "{{ trans('labels.coupons') }}";
            title = "{{ trans('labels.coupons') }}";
        }
        if ("{{ request()->is('admin/roles') }}") {
            filename = "{{ trans('labels.roles') }}";
            title = "{{ trans('labels.roles') }}";
        }
        if ("{{ request()->is('admin/employees') }}") {
            filename = "{{ trans('labels.employees') }}";
            title = "{{ trans('labels.employees') }}";
        }
        if ("{{ request()->is('admin/subscribers') }}") {
            filename = "{{ trans('labels.subscribers') }}";
            title = "{{ trans('labels.subscribers') }}";
        }
        if ("{{ request()->is('admin/inquiries') }}") {
            filename = "{{ trans('labels.inquiries') }}";
            title = "{{ trans('labels.inquiries') }}";
        }
        if ("{{ request()->is('admin/language-settings') }}") {
            filename = "{{ trans('labels.language-settings') }}";
            title = "{{ trans('labels.language-settings') }}";
        }
        if ("{{ request()->is('admin/store_categories') }}") {
            filename = "{{ trans('labels.store_categories') }}";
            title = "{{ trans('labels.store_categories') }}";
        }

        toastr.options = {
            "closeButton": true,
            "positionClass": "toast-top-right",
        }
        @if (Session::has('success'))
            toastr.success("{{ session('success') }}", "Success");
        @endif
        @if (Session::has('error'))
            toastr.error("{{ session('error') }}", "Error");
        @endif

        @if (Auth::user()->type == 2)
            // New Notification
            var noticount = 0;
            var notificationurl = "{{ URL::to('/admin/getorder') }}";
            var vendoraudio =
                "{{ url(env('ASSETPATHURL') . 'admin-assets/notification/' . helper::appdata(Auth::user()->id)->notification_sound) }}";
        @endif
    </script>
    @if (@helper::checkaddons('notification'))
        @if (Auth::user()->type == 2)
            <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/sound.js') }}"></script>
        @endif
    @endif
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/jquery/jquery-ui.min.js') }}"></script>
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/common.js') }}"></script><!-- Common JS -->
    @yield('scripts')
</body>

</html>
