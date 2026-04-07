<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:title" content="{{ helper::appdata('')->meta_title }}" />
    <meta property="og:description" content="{{ helper::appdata('')->meta_description }}" />
    <meta property="og:image" content='{{ helper::image_path(helper::appdata('')->og_image) }}' />
    <link rel="icon" href="{{ helper::image_path(helper::appdata('')->favicon) }}" type="image" sizes="16x16">
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/bootstrap/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/fontawesome/all.min.css') }}" />
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/lending/aos.css') }}" />
    <link rel="stylesheet" href="{{ url(env('ASSETPATHURL') . 'admin-assets/css/lending/app.css') }}" />
    <title> {{ helper::appdata('')->website_title }} </title>
</head>
<body>
    <div class="container">
        <div class="row align-items-center justify-content-center vh-100">
            <div class="col-12">
                <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/404.svg') }}" class="w-100"
                    alt="" height="400">
                <div class="text-center">
                    <h1 class="display-1 fw-bold">409</h1>
                    <p> <span class="text-danger error-content">Opps!</span></p>
                    <p class="text-uppercase fw-bold">Conflict</p>
                    <p>The request could not be completed due to a conflict.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
