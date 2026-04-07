<meta name="theme-color" content="{{ helper::appdata($storeinfo->id)->theme_color }}">
<meta name="background-color" content="{{ helper::appdata($storeinfo->id)->background_color }}">
<link rel="apple-touch-icon" href="{{ helper::image_path(helper::appdata($storeinfo->id)->app_logo) }}">
<link rel="manifest"
    href='data:application/manifest+json,{"name": "{{ helper::appdata($storeinfo->id)->app_name }}","short_name": "{{ helper::appdata($storeinfo->id)->app_name }}","icons": [{"src": "{{ helper::image_path(helper::appdata($storeinfo->id)->app_logo) }}", "sizes": "512x512", "type": "image/png"}, {"src": "{{ helper::image_path(helper::appdata($storeinfo->id)->app_logo) }}", "sizes": "1024x1024", "type": "image/png"}, {"src": "{{ helper::image_path(helper::appdata($storeinfo->id)->app_logo) }}", "sizes": "1024x1024", "type": "image/png"}], "start_url": "{{ request()->url() }}","display": "standalone","prefer_related_applications":"false" }'>


<!--------------- PWA Section start ------------------>
<div class="d-block d-sm-none">
    <div class="pwa d-flex gap-2">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ helper::image_path($user->image) }}" class="pwa-image" alt="" height="40px">
            <div class="pwa-content">
                <h5 class="mb-1 line-1 fs-7">{{ helper::appdata(@$storeinfo->id)->app_title }}</h5>
                <p class="m-0 fs-8 line-1 text-dark">{{ trans('labels.pwa_message') }}</p>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a class="btn mobile-install-btn" id="mobile-install-app">{{ trans('labels.install') }}</a>
            <a class="close-btn" id="close-btn">
                <i class="fa-solid fa-xmark fs-7 text-danger"></i>
            </a>
        </div>
    </div>
</div>
<!--------------- PWA Section End ------------------>
