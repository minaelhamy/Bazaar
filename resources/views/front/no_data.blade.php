<div class="col-lg-7 col-md-10 mx-auto my-5 text-center">

    <div class="col-md-6 mx-auto">

        <img src="{{ helper::image_path(helper::appdata($storeinfo->id)->no_data_image) }}" alt="cart empty image"
            class="w-100 mb-5 object-fit-over">

    </div>

    <h2 class="cart-title color-changer border-0 {{ session()->get('direction') == '2' ? 'text-right' : '' }}">

        {{ trans('labels.no_data_found') }}

    </h2>

    <p class="text-muted">{{ trans('labels.no_data_msg') }} </p>

    <div class="d-flex justify-content-center">

        <a href="{{ helper::storefront_url($storeinfo, '/') }}" class="btn btn-store mt-3"><i
                class="{{ session()->get('direction') == '2' ? 'fa-solid fa-angle-right' : 'fa-solid fa-angle-left' }}"></i><span
                class="px-2">{{ trans('labels.return_to_shop') }}</span></a>

    </div>

</div>
