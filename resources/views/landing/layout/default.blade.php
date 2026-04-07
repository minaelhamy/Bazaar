<!DOCTYPE html>
<html lang="en" dir="{{ session()->get('direction') == 2 ? 'rtl' : 'ltr' }}"  class="light">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <link rel="icon" type="image" sizes="16x16" href="{{ helper::image_path(helper::appdata('')->favicon) }}">
    <!-- Favicon icon -->
    <title>{{ helper::appdata('')->website_title }}</title>
    <!----------------font-awesome---------------->
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'landing/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/fontawesome/all.min.css') }}">
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/toastr/toastr.min.css') }}">
    <!-- Toastr CSS -->
    <!---------------owl-carousel-link-min-css------------->
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'landing/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'landing/css/owl.theme.default.min.css') }}">
    <!---------------font-family-Lexend----------->
    <!-- <link rel="stylesheet" href="assets/font/css2.css"> -->
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'landing/css/fonts.css') }}">
    <!--aos css link-->
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'landing/css/aos.css') }}">
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'landing/css/style.css') }}">
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'landing/css/responsive.css') }}">
    <style>
        :root {
            /* Color */
            --primary-color: {{ helper::landingsettings()->primary_color }};
            --secondary-color: {{ helper::landingsettings()->secondary_color }};
        }
    </style>
    @yield('styles')
</head>

<body>
    @include('landing.layout.header')
    <div>
        @yield('content')
    </div>
    @include('landing.layout.footer')
    <!-- Quick call -->
    @if (@helper::checkaddons('quick_call'))
        @if (@helper::appdata('')->quick_call == 1)
            <div
                class="{{ helper::appdata('')->quick_call_mobile_view_on_off == 1 ? 'd-block' : 'd-lg-block d-none' }}">
                @include('landing.quick_call')
            </div>
        @endif
    @endif

    @if (@helper::checkaddons('tawk_addons'))
        @if (helper::appdata('')->tawk_on_off == 1)
            {!! @helper::appdata('')->tawk_widget_id !!}
        @endif
    @endif

    <!-- Wizz Chat -->
    @if (@helper::checkaddons('wizz_chat'))
        @if (helper::appdata('')->wizz_chat_on_off == 1)
            {!! helper::appdata('')->wizz_chat_settings !!}
        @endif
    @endif


    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/jquery/jquery.min.js') }}"></script><!-- jQuery JS -->
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script><!-- Bootstrap JS -->
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/toastr/toastr.min.js') }}"></script><!-- Toastr JS -->
    <script src="{{ url(env('ASSETPATHURL') . 'landing/js/aos.js') }}"></script>
    <script src="{{ url(env('ASSETPATHURL') . 'landing/js/owl.carousel.js') }}"></script>
    <script src="{{ url(env('ASSETPATHURL') . 'landing/js/owl.carousel.min.js') }}"></script>
    <script>
        toastr.options = {
            "closeButton": true,
        }
        @if (Session::has('success'))
            toastr.success("{{ session('success') }}");
        @endif
        @if (Session::has('error'))
            toastr.error("{{ session('error') }}");
        @endif
        var layout = "{{ session()->get('direction') }}";
    </script>
    <script>
        function themeinfo(id, theme_id, plan_name) {

            let string = theme_id;
            let arr = string.split('|');
            $('#themeinfoLabel').text(plan_name);
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                url: "{{ URL::to('/themeimages') }}",
                method: 'GET',
                data: {
                    theme_id: arr
                },
                dataType: 'json',
                success: function(data) {
                    $('#theme_modalbody').html(data.output);
                    $('#themeinfo').modal('show');
                }
            })

        }
    </script>
    <script>
        var darklogo = "{{ helper::image_path(helper::appdata('')->darklogo) }}";
        var lightlogo = "{{ helper::image_path(helper::appdata('')->logo) }}";
        
    </script>
    @yield('scripts')
    <script src="{{ url(env('ASSETPATHURL') . 'landing/js/landing.js') }}"></script>

    <script>
        // JavaScript
        const button = document.getElementById('quick-btn');

        button.addEventListener('click', function() {
            this.classList.toggle('expanded');
        });
    </script>
</body>
