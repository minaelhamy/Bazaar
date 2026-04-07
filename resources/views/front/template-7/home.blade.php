@include('front.theme.header')

@if ($sliders->count() > 0)
    <div class="card border-0">

        <div class="furniture_home owl-carousel owl-theme">
            @foreach ($sliders as $slider)
                <div class="item">
                    @if ($slider->product_id != 0 || $slider->category_id != 0)
                        @if ($slider->type == 1)
                            <a
                                href="{{ helper::storefront_url($storeinfo, '/search?category=' . $slider['category_info']->slug) }}">
                            @elseif($slider->type == 2)
                                @php
                                    $item = helper::itemdetails($slider->product_id, $storeinfo->id);
                                @endphp
                                <a onclick="GetProductOverview('{{ $item->slug }}')" class="cursor-pointer">
                                @else
                                    <a href="javascript:void(0)">
                        @endif
                    @endif

                    <img class="banner-bg" src=" {{ helper::image_path($slider->banner_image) }}" alt="">
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="furniture_home owl-carousel owl-theme">
        <div class="item"><img class="banner-bg"
                src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/banner-placeholder.png') }} "
                alt="">
        </div>
    </div>
@endif

<!-- Best-selling-Items -->
@if (helper::appdata($storeinfo->id)->product_section_display == 1 ||
        helper::appdata($storeinfo->id)->product_section_display == 3)
    @if (count($bestsellingitems) > 0)
        <section class="my-5 pro-7-sec">
            <div class="container">
                <div class="sec-header mb-4">
                    <h4 class="main-title-7 mb-2 color-changer main-title text-center">{{ trans('labels.selling_product') }}</h4>
                    <p class="m-0 line-2 fs-15 text-center mb-2 fw-500 text-muted">
                        {{ trans('labels.selling_product_subtitle') }}</p>
                </div>
                <div class="pro-7">
                    <div class="row g-sm-4 g-3 row-cols-xl-4 row-cols-lg-3 row-cols-2">
                        @foreach ($bestsellingitems as $key => $item)
                            @php
                                if ($item->top_deals == 1 && helper::top_deals($storeinfo->id) != null) {
                                    if (@helper::top_deals($storeinfo->id)->offer_type == 1) {
                                        if ($item['variation']->count() > 0) {
                                            if (
                                                $item['variation'][0]->price >
                                                @helper::top_deals($storeinfo->id)->offer_amount
                                            ) {
                                                $price =
                                                    $item['variation'][0]->price -
                                                    @helper::top_deals($storeinfo->id)->offer_amount;
                                            } else {
                                                $price = $item['variation'][0]->price;
                                            }
                                        } else {
                                            if ($item->item_price > @helper::top_deals($storeinfo->id)->offer_amount) {
                                                $price =
                                                    $item->item_price -
                                                    @helper::top_deals($storeinfo->id)->offer_amount;
                                            } else {
                                                $price = $item->item_price;
                                            }
                                        }
                                    } else {
                                        if ($item['variation']->count() > 0) {
                                            $price =
                                                $item['variation'][0]->price -
                                                $item['variation'][0]->price *
                                                    (@helper::top_deals($storeinfo->id)->offer_amount / 100);
                                        } else {
                                            $price =
                                                $item->item_price -
                                                $item->item_price *
                                                    (@helper::top_deals($storeinfo->id)->offer_amount / 100);
                                        }
                                    }
                                    if ($item['variation']->count() > 0) {
                                        $original_price = $item['variation'][0]->price;
                                    } else {
                                        $original_price = $item->item_price;
                                    }
                                    $off =
                                        $original_price > 0
                                            ? number_format(100 - ($price * 100) / $original_price, 1)
                                            : 0;
                                } else {
                                    if ($item['variation']->count() > 0) {
                                        $price = $item['variation'][0]->price;
                                        $original_price = $item['variation'][0]->original_price;
                                    } else {
                                        $price = $item->item_price;
                                        $original_price = $item->item_original_price;
                                    }
                                    $off =
                                        $original_price > 0
                                            ? number_format(100 - ($price * 100) / $original_price, 1)
                                            : 0;
                                }
                            @endphp
                            <div class="col">
                                <div class="card card-bg h-100 rounded-0">
                                    <div class="pro-7-img">
                                        <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                            @if (@$item['product_image']->image == null)
                                                <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                    alt="pro img" class="w-100 object-fit-cover cursor-pointer img-1">
                                            @else
                                                <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                    alt="pro img" class="w-100 object-fit-cover cursor-pointer img-1">
                                            @endif
                                        </a>
                                        <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                            @if ($item['multi_image']->count() > 1)
                                                <img src="{{ @helper::image_path($item['multi_image'][1]->image) }}"
                                                    alt="pro img" class="w-100 obaject-fit-cover cursor-pointer img-2">
                                            @endif
                                        </a>

                                        @if ($off > 0)
                                            <div class="offer-7 rounded-0 ltr">{{ $off }}%
                                                {{ trans('labels.off') }}
                                            </div>
                                        @endif
                                        <ul class="outer-functional">
                                            @if (@helper::checkaddons('customer_login'))
                                                @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                    <li class="wishlist">
                                                        <a
                                                            onclick="managefavorite('{{ $item->id }}',{{ $storeinfo->id }},'{{ helper::storefront_url($storeinfo, '/managefavorite') }}')">
                                                            @if (Auth::user() && Auth::user()->type == 3)
                                                                @php

                                                                    $favorite = helper::ceckfavorite(
                                                                        $item->id,
                                                                        $storeinfo->id,
                                                                        Auth::user()->id,
                                                                    );

                                                                @endphp
                                                                @if (!empty($favorite) && $favorite->count() > 0)
                                                                    <i class="fa-solid fa-heart"></i>
                                                                @else
                                                                    <i class="fa-regular fa-heart"></i>
                                                                @endif
                                                            @else
                                                                <i class="fa-regular fa-heart"></i>
                                                            @endif
                                                        </a>
                                                    </li>
                                                @endif
                                            @endif
                                            <li class="product-add">
                                                <button class="btn p-0 rounded-0 border-0"
                                                    id="iconverifybtn{{ $key }}_{{ $item->id }}"
                                                    onclick="GetProductOverview('{{ $item->slug }}',this.id)">
                                                    @if (helper::appdata($storeinfo->id)->online_order == 1)
                                                        <i class="fa-regular fa-cart-shopping"></i>
                                                    @else
                                                        <i class="fa-regular fa-eye"></i>
                                                    @endif
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body px-0 pb-0">
                                        @if (@helper::checkaddons('product_reviews'))
                                            @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                <p class="m-0 pro-rating cursor-pointer"
                                                    onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')">
                                                    <i class="fa-solid fa-star text-warning"></i>
                                                    <span
                                                        class="px-1 color-changer">{{ number_format($item->ratings_average, 1) }}</span>
                                                </p>
                                            @endif
                                        @endif

                                        <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                            <h4 id="itemname" class="title mb-2 color-changer text-dark line-2">
                                                {{ $item->item_name }}</h4>
                                        </a>
                                    </div>
                                    <div class="card-footer px-0 bg-transparent border-0">
                                        <p class="pro-pricing color-changer line-1 m-0">
                                            {{ helper::currency_formate($price, $storeinfo->id) }}
                                            @if ($original_price > $price)
                                                <span class="old-price">
                                                    {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                                </span>
                                            @endif
                                        </p>
                                        @if ($item->stock_management == 1)
                                            @if (helper::checklowqty($item->id, $storeinfo->id) == 2 && $item->has_variants != 1)
                                                <div class="out-stock mt-1">
                                                    <span class="out-stock-indicator-dot"></span>
                                                    <p class="out-stock-text">
                                                        {{ trans('labels.out_of_stock') }}</p>
                                                </div>
                                            @else
                                                <div class="in-stock mt-1">
                                                    <span class="in-stock-indicator-dot"></span>
                                                    <p class="in-stock-text">
                                                        {{ trans('labels.in_stock') }}
                                                    </p>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
@endif

<!-- feature-sec -->
@if ($bannerimage1->count() > 0)
    <section class="feature-sec my-5">
        <div class="container">
            <div class="feature-slider-6 owl-carousel owl-rtl owl-theme">

                @foreach ($bannerimage1 as $image)
                    <div class="item">
                        @if ($image->type == 1)
                            <a href="{{ helper::storefront_url($storeinfo, '/search?category=' . @$image['category_info']->slug) }}"
                                class="cursor-pointer">
                            @elseif($image->type == 2)
                                @php
                                    $item = helper::itemdetails($image->product_id, $storeinfo->id);
                                @endphp
                                <a onclick="GetProductOverview('{{ $item->slug }}')" class="cursor-pointer">
                                @else
                                    <a href="javascript:void(0)" class="cursor-pointer">
                        @endif
                        <img src='{{ helper::image_path($image->banner_image) }}' alt="" class=""></a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

<!-- new product-sec -->
@if (helper::getcategory($storeinfo->id)->count() > 0)
    <section class="pro-7-sec my-sm-5 my-3">
        <div class="container">
            <div class="sec-header mb-4">
                <h4 class="main-title-7 mb-2 color-changer main-title text-center">{{ trans('labels.featured_products') }}</h4>
                <p class="m-0 line-2 fs-15 text-center mb-2 fw-500 text-muted">
                    {{ trans('labels.featured_products_subtitle') }}</p>
            </div>
            <!-- category -->
            <div class="category-7 navbarnav px-2">
                <ul id="myTab" class="nav nav-tabs justify-content-center gap-1 border-0 slider menu-nav"
                    role="tablist">
                    @foreach (helper::getcategory($storeinfo->id) as $key => $category)
                        @php
                            $check_cat_count = 0;
                        @endphp
                        @foreach ($getitem as $item)
                            @if (in_array($category->id, explode('|', $item->cat_id)))
                                @php
                                    $check_cat_count++;
                                @endphp
                            @endif
                        @endforeach
                        @if ($check_cat_count > 0)
                            <li class="nav-item">
                                <button class="nav-link " data-bs-toggle="tab" data-bs-target="#{{ $category->slug }}"
                                    type="button" role="tab" aria-controls="{{ $category->slug }}"
                                    aria-selected="true">{{ $category->name }}</button>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>

            <!-- product sall -->
            <div class="tab-content pro-7" id="myTabContent">
                @foreach (helper::getcategory($storeinfo->id) as $key => $category)
                    @php
                        $check_cat_count = 0;
                    @endphp
                    @foreach ($getitem as $item)
                        @if (in_array($category->id, explode('|', $item->cat_id)))
                            @php
                                $check_cat_count++;
                            @endphp
                        @endif
                    @endforeach
                    @if ($check_cat_count > 0)
                        <div class="tab-pane fade show mt-md-3 " id="{{ $category->slug }}" role="tabpanel"
                            aria-labelledby="home-tab" tabindex="0">
                            <div class="row g-sm-4 g-3 row-cols-xl-4 row-cols-lg-3 row-cols-2">
                                @foreach ($getitem as $key => $item)
                                    @php
                                        if ($item->top_deals == 1 && helper::top_deals($storeinfo->id) != null) {
                                            if (@helper::top_deals($storeinfo->id)->offer_type == 1) {
                                                if ($item['variation']->count() > 0) {
                                                    if (
                                                        $item['variation'][0]->price >
                                                        @helper::top_deals($storeinfo->id)->offer_amount
                                                    ) {
                                                        $price =
                                                            $item['variation'][0]->price -
                                                            @helper::top_deals($storeinfo->id)->offer_amount;
                                                    } else {
                                                        $price = $item['variation'][0]->price;
                                                    }
                                                } else {
                                                    if (
                                                        $item->item_price >
                                                        @helper::top_deals($storeinfo->id)->offer_amount
                                                    ) {
                                                        $price =
                                                            $item->item_price -
                                                            @helper::top_deals($storeinfo->id)->offer_amount;
                                                    } else {
                                                        $price = $item->item_price;
                                                    }
                                                }
                                            } else {
                                                if ($item['variation']->count() > 0) {
                                                    $price =
                                                        $item['variation'][0]->price -
                                                        $item['variation'][0]->price *
                                                            (@helper::top_deals($storeinfo->id)->offer_amount / 100);
                                                } else {
                                                    $price =
                                                        $item->item_price -
                                                        $item->item_price *
                                                            (@helper::top_deals($storeinfo->id)->offer_amount / 100);
                                                }
                                            }
                                            if ($item['variation']->count() > 0) {
                                                $original_price = $item['variation'][0]->price;
                                            } else {
                                                $original_price = $item->item_price;
                                            }
                                            $off =
                                                $original_price > 0
                                                    ? number_format(100 - ($price * 100) / $original_price, 1)
                                                    : 0;
                                        } else {
                                            if ($item['variation']->count() > 0) {
                                                $price = $item['variation'][0]->price;
                                                $original_price = $item['variation'][0]->original_price;
                                            } else {
                                                $price = $item->item_price;
                                                $original_price = $item->item_original_price;
                                            }
                                            $off =
                                                $original_price > 0
                                                    ? number_format(100 - ($price * 100) / $original_price, 1)
                                                    : 0;
                                        }
                                    @endphp
                                    @if (in_array($category->id, explode('|', $item->cat_id)))
                                        <div class="col">
                                            <div class="card card-bg h-100 rounded-0">
                                                <div class="pro-7-img">
                                                    <a
                                                        href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                        @if (@$item['product_image']->image == null)
                                                            <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                                alt="pro img"
                                                                class="w-100 object-fit-cover cursor-pointer img-1">
                                                        @else
                                                            <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                                alt="pro img"
                                                                class="w-100 object-fit-cover cursor-pointer img-1">
                                                        @endif
                                                    </a>
                                                    <a
                                                        href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                        @if ($item['multi_image']->count() > 1)
                                                            <img src="{{ @helper::image_path($item['multi_image'][1]->image) }}"
                                                                alt="pro img"
                                                                class="w-100 obaject-fit-cover cursor-pointer img-2">
                                                        @endif
                                                    </a>

                                                    @if ($off > 0)
                                                        <div class="offer-7 rounded-0 ltr">{{ $off }}%
                                                            {{ trans('labels.off') }}
                                                        </div>
                                                    @endif
                                                    <ul class="outer-functional">
                                                        @if (@helper::checkaddons('customer_login'))
                                                            @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                                <li class="wishlist">
                                                                    <a href="javascript:void(0)"
                                                                        onclick="managefavorite('{{ $item->id }}',{{ $storeinfo->id }},'{{ helper::storefront_url($storeinfo, '/managefavorite') }}')">
                                                                        @if (Auth::user() && Auth::user()->type == 3)
                                                                            @php

                                                                                $favorite = helper::ceckfavorite(
                                                                                    $item->id,
                                                                                    $storeinfo->id,
                                                                                    Auth::user()->id,
                                                                                );

                                                                            @endphp
                                                                            @if (!empty($favorite) && $favorite->count() > 0)
                                                                                <i class="fa-solid fa-heart"></i>
                                                                            @else
                                                                                <i class="fa-regular fa-heart"></i>
                                                                            @endif
                                                                        @else
                                                                            <i class="fa-regular fa-heart"></i>
                                                                        @endif
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        @endif
                                                        <li class="product-add">
                                                            <button class="btn p-0 rounded-0 border-0"
                                                                id="iconverifybtn{{ $key }}_{{ $category->id }}"
                                                                onclick="GetProductOverview('{{ $item->slug }}',this.id)">
                                                                @if (helper::appdata($storeinfo->id)->online_order == 1)
                                                                    <i class="fa-regular fa-cart-shopping"></i>
                                                                @else
                                                                    <i class="fa-regular fa-eye"></i>
                                                                @endif
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="card-body px-0 pb-0">
                                                    @if (@helper::checkaddons('product_reviews'))
                                                        @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                            <p class="m-0 pro-rating cursor-pointer"
                                                                onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')">
                                                                <i class="fa-solid fa-star text-warning"></i>
                                                                <span
                                                                    class="px-1 color-changer">{{ number_format($item->ratings_average, 1) }}</span>
                                                            </p>
                                                        @endif
                                                    @endif
                                                    <a
                                                        href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                        <h4 id="itemname" class="title mb-2 color-changer text-dark line-2">
                                                            {{ $item->item_name }}</h4>
                                                    </a>
                                                </div>
                                                <div class="card-footer px-0 bg-transparent border-0">
                                                    <p class="pro-pricing color-changer line-1 m-0">
                                                        {{ helper::currency_formate($price, $storeinfo->id) }}
                                                        @if ($original_price > $price)
                                                            <span class="old-price">
                                                                {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                                            </span>
                                                        @endif
                                                    </p>
                                                    @if ($item->stock_management == 1)
                                                        @if (helper::checklowqty($item->id, $storeinfo->id) == 2 && $item->has_variants != 1)
                                                            <div class="out-stock mt-1">
                                                                <span class="out-stock-indicator-dot"></span>
                                                                <p class="out-stock-text">
                                                                    {{ trans('labels.out_of_stock') }}</p>
                                                            </div>
                                                        @else
                                                            <div class="in-stock mt-1">
                                                                <span class="in-stock-indicator-dot"></span>
                                                                <p class="in-stock-text">
                                                                    {{ trans('labels.in_stock') }}
                                                                </p>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="d-flex justify-content-center my-4">
                <div class="d-none">
                    <a href="#" class="btn btn-store mobile-btn">{{ trans('labels.view_all') }}
                        <i class="fa-solid fa-arrow-right px-1"></i></a>
                </div>
            </div>
        </div>
    </section>
@endif

<!---------- WHO WE ARE START ---------->
@if ($whowearedata->count() > 0)
    <section class="my-5 my-lg-5">
        <div class="container">
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="sec-header">
                        <h4 class="line-1 mb-2 color-changer fs-5">{{ helper::appdata($storeinfo->id)->whoweare_title }}
                        </h4>
                    </div>
                    <h3 class="line-2 main-title color-changer fw-600">{{ helper::appdata($storeinfo->id)->whoweare_subtitle }}</h3>
                    <p class="m-0 text-muted line-3 fs-15">{{ helper::appdata($storeinfo->id)->whoweare_description }}
                    </p>
                    <div class="col-12">
                        <div class="row g-3 mt-1">
                            @foreach ($whowearedata as $whoweare)
                                <div class="col-12">
                                    <div class="card bg-primary rounded-0 border h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="icon-img-15">
                                                    <img src="{{ helper::image_path($whoweare->image) }}"
                                                        alt="" class="border">
                                                </div>
                                                <div class="tital-15">
                                                    <h6 class="line-1 text-white fw-600">
                                                        {{ $whoweare->title }}
                                                    </h6>
                                                    <p class="m-0 fs-8 text-white fw-500 mt-1 line-2">
                                                        {{ $whoweare->sub_title }}.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="img-15">
                        <img src="{{ helper::image_path(helper::appdata($storeinfo->id)->whoweare_image) }}"
                            alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
<!---------- WHO WE ARE END ---------->

<!-- Top-Rated-Items -->
@if (helper::appdata($storeinfo->id)->product_section_display == 2 ||
        helper::appdata($storeinfo->id)->product_section_display == 3)
    @if (count($toprateditems) > 0)
        <section class="mb-5 pro-7-sec">
            <div class="container">
                <div class="sec-header mb-4">
                    <h4 class="main-title-7 mb-2 color-changer main-title text-center">{{ trans('labels.top_rated_product') }}</h4>
                    <p class="m-0 line-2 fs-15 text-center mb-2 fw-500 text-muted">
                        {{ trans('labels.top_rated_product_subtitle') }}</p>
                </div>
                <div class="pro-7">
                    <div class="row g-sm-4 g-3 row-cols-xl-4 row-cols-lg-3 row-cols-2">
                        @foreach ($toprateditems as $key => $item)
                            @php
                                if ($item->top_deals == 1 && helper::top_deals($storeinfo->id) != null) {
                                    if (@helper::top_deals($storeinfo->id)->offer_type == 1) {
                                        if ($item['variation']->count() > 0) {
                                            if (
                                                $item['variation'][0]->price >
                                                @helper::top_deals($storeinfo->id)->offer_amount
                                            ) {
                                                $price =
                                                    $item['variation'][0]->price -
                                                    @helper::top_deals($storeinfo->id)->offer_amount;
                                            } else {
                                                $price = $item['variation'][0]->price;
                                            }
                                        } else {
                                            if ($item->item_price > @helper::top_deals($storeinfo->id)->offer_amount) {
                                                $price =
                                                    $item->item_price -
                                                    @helper::top_deals($storeinfo->id)->offer_amount;
                                            } else {
                                                $price = $item->item_price;
                                            }
                                        }
                                    } else {
                                        if ($item['variation']->count() > 0) {
                                            $price =
                                                $item['variation'][0]->price -
                                                $item['variation'][0]->price *
                                                    (@helper::top_deals($storeinfo->id)->offer_amount / 100);
                                        } else {
                                            $price =
                                                $item->item_price -
                                                $item->item_price *
                                                    (@helper::top_deals($storeinfo->id)->offer_amount / 100);
                                        }
                                    }
                                    if ($item['variation']->count() > 0) {
                                        $original_price = $item['variation'][0]->price;
                                    } else {
                                        $original_price = $item->item_price;
                                    }
                                    $off =
                                        $original_price > 0
                                            ? number_format(100 - ($price * 100) / $original_price, 1)
                                            : 0;
                                } else {
                                    if ($item['variation']->count() > 0) {
                                        $price = $item['variation'][0]->price;
                                        $original_price = $item['variation'][0]->original_price;
                                    } else {
                                        $price = $item->item_price;
                                        $original_price = $item->item_original_price;
                                    }
                                    $off =
                                        $original_price > 0
                                            ? number_format(100 - ($price * 100) / $original_price, 1)
                                            : 0;
                                }
                            @endphp
                            <div class="col">
                                <div class="card card-bg h-100 rounded-0">
                                    <div class="pro-7-img">
                                        <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                            @if (@$item['product_image']->image == null)
                                                <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                    alt="pro img"
                                                    class="w-100 object-fit-cover cursor-pointer img-1">
                                            @else
                                                <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                    alt="pro img"
                                                    class="w-100 object-fit-cover cursor-pointer img-1">
                                            @endif
                                        </a>
                                        <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                            @if ($item['multi_image']->count() > 1)
                                                <img src="{{ @helper::image_path($item['multi_image'][1]->image) }}"
                                                    alt="pro img"
                                                    class="w-100 obaject-fit-cover cursor-pointer img-2">
                                            @endif
                                        </a>

                                        @if ($off > 0)
                                            <div class="offer-7 rounded-0 ltr">{{ $off }}%
                                                {{ trans('labels.off') }}
                                            </div>
                                        @endif
                                        <ul class="outer-functional">
                                            @if (@helper::checkaddons('customer_login'))
                                                @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                    <li class="wishlist">
                                                        <a
                                                            onclick="managefavorite('{{ $item->id }}',{{ $storeinfo->id }},'{{ helper::storefront_url($storeinfo, '/managefavorite') }}')">
                                                            @if (Auth::user() && Auth::user()->type == 3)
                                                                @php

                                                                    $favorite = helper::ceckfavorite(
                                                                        $item->id,
                                                                        $storeinfo->id,
                                                                        Auth::user()->id,
                                                                    );

                                                                @endphp
                                                                @if (!empty($favorite) && $favorite->count() > 0)
                                                                    <i class="fa-solid fa-heart"></i>
                                                                @else
                                                                    <i class="fa-regular fa-heart"></i>
                                                                @endif
                                                            @else
                                                                <i class="fa-regular fa-heart"></i>
                                                            @endif
                                                        </a>
                                                    </li>
                                                @endif
                                            @endif
                                            <li class="product-add">
                                                <button class="btn p-0 rounded-0 border-0"
                                                    id="iconverifybtn3{{ $key }}_{{ $item->id }}"
                                                    onclick="GetProductOverview('{{ $item->slug }}',this.id)">
                                                    @if (helper::appdata($storeinfo->id)->online_order == 1)
                                                        <i class="fa-regular fa-cart-shopping"></i>
                                                    @else
                                                        <i class="fa-regular fa-eye"></i>
                                                    @endif
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body px-0 pb-0">
                                        @if (@helper::checkaddons('product_reviews'))
                                            @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                <p class="m-0 pro-rating cursor-pointer"
                                                    onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')">
                                                    <i class="fa-solid fa-star text-warning"></i>
                                                    <span
                                                        class="px-1 color-changer">{{ number_format($item->ratings_average, 1) }}</span>
                                                </p>
                                            @endif
                                        @endif

                                        <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                            <h4 id="itemname" class="title mb-2 color-changer text-dark line-2">
                                                {{ $item->item_name }}</h4>
                                        </a>
                                    </div>
                                    <div class="card-footer px-0 bg-transparent border-0">
                                        <p class="pro-pricing color-changer line-1 m-0">
                                            {{ helper::currency_formate($price, $storeinfo->id) }}
                                            @if ($original_price > $price)
                                                <span class="old-price">
                                                    {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                                </span>
                                            @endif
                                        </p>
                                        @if ($item->stock_management == 1)
                                            @if (helper::checklowqty($item->id, $storeinfo->id) == 2 && $item->has_variants != 1)
                                                <div class="out-stock mt-1">
                                                    <span class="out-stock-indicator-dot"></span>
                                                    <p class="out-stock-text">
                                                        {{ trans('labels.out_of_stock') }}</p>
                                                </div>
                                            @else
                                                <div class="in-stock mt-1">
                                                    <span class="in-stock-indicator-dot"></span>
                                                    <p class="in-stock-text">
                                                        {{ trans('labels.in_stock') }}
                                                    </p>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
@endif

@if ($bannerimage2->count() > 0)
    <section class="feature-sec my-5">
        <div class="container">
            <div class="feature-carousel-15 owl-carousel owl-theme">
                @foreach ($bannerimage2 as $image)
                    @if ($image->type == 1)
                        <a href="{{ helper::storefront_url($storeinfo, '/search?category=' . @$image['category_info']->slug) }}"
                            class="cursor-pointer">
                        @elseif($image->type == 2)
                            @php
                                $item = helper::itemdetails($image->product_id, $storeinfo->id);
                            @endphp
                            <a href="javascript:void(0)" onclick="GetProductOverview('{{ $item->slug }}','')"
                                class="cursor-pointer">
                            @else
                                <a href="javascript:void(0)" class="cursor-pointer">
                    @endif
                    <div class="item">
                        <div class="feature">
                            <img src="{{ helper::image_path($image->banner_image) }}" alt=""
                                class="rounded">
                        </div>
                    </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

<!--------- storereview --------->
@if (@helper::checkaddons('store_reviews'))
    @if ($testimonials->count() > 0)
        <section class="storereview-sec mb-lg-5 mb-4">
            <div class="container">
                <div class="sec-header mb-4">
                    <h4 class="main-title-7 mb-2 color-changer main-title text-center">{{ trans('labels.testimonials') }}</h4>
                    <p class="m-0 line-2 fs-15 text-center mb-2 fw-500 text-muted">
                        {{ trans('labels.testimonials_subtitle') }}</p>
                </div>
                <div class="store-review-8 owl-carousel owl-theme">
                    @foreach ($testimonials as $item)
                        <div class="item h-100">
                            <div class="card h-100 border p-4">
                                <div class="card-body p-0">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="review-img">
                                            <img src="{{ helper::image_path($item->image) }}" alt="">
                                        </div>
                                        <div class="px-3">
                                            <h5 class="line-1 color-changer mb-1 review_title">{{ $item->name }}</h5>
                                            <p class="review_date text-muted fs-7">
                                                {{ helper::date_format($item->created_at, $storeinfo->id) }}</p>
                                        </div>
                                    </div>
                                    @php
                                        $count = $item->star;
                                    @endphp
                                    <div class="d-flex gap-1 pb-2">
                                        @for ($i = 0; $i < 5; $i++)
                                            @if ($i < $count)
                                                <li class="list-inline-item me-0 small"><i
                                                        class="fa-solid fa-star text-warning"></i>
                                                </li>
                                            @else
                                                <li class="list-inline-item me-0 small"><i
                                                        class="fa-regular fa-star text-warning"></i>
                                                </li>
                                            @endif
                                        @endfor
                                    </div>
                                    <div class="review_description">
                                        <p class="text-muted">{{ $item->description }}</p>
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

<!--------- newsletter --------->
@include('front.newsletter')

<!-- blog -->
@if (helper::getblogs($storeinfo->id)->count() > 0)
    <section class="blog-6-sec pro-7-sec my-5">
        @php
            $blog = helper::getblogs($storeinfo->id);
        @endphp
        <div class="container">
            <div class="sec-header mb-4">
                <h4 class="main-title-7 mb-2 color-changer main-title text-center">{{ trans('labels.our_latest_blogs') }}</h4>
                <p class="m-0 line-2 fs-15 text-center mb-2 fw-500 text-muted">
                    {{ trans('labels.our_latest_blogs_subtitle') }}</p>
            </div>
            <!-- blogs -->
            @if (@helper::checkaddons('subscription'))
                @if (@helper::checkaddons('blog'))
                    @php
                        $checkplan = App\Models\Transaction::where('vendor_id', $storeinfo->id)
                            ->orderByDesc('id')
                            ->first();
                        if ($storeinfo->allow_without_subscription == 1) {
                            $blogs_allow = 1;
                        } else {
                            $blogs_allow = @$checkplan->blogs;
                        }
                    @endphp
                    @if ($blogs_allow == 1)
                        <div class="blog-7 owl-carousel owl-theme">
                            @foreach ($blog as $blog)
                                <div class="item h-100 mx-1">
                                    <div class="card card-bg border h-100 rounded-0 border-0 overflow-hidden">
                                        <div class="blog-6-img">
                                            <a href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}">
                                                <img src="{{ helper::image_path($blog->image) }}" height="300"
                                                    alt="blog img" class="w-100 object-fit-cover">
                                            </a>
                                            <div class="post-image-hover">
                                                <a href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}"
                                                    class="blog-btn">
                                                    <i class="fa-regular fa-link"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body px-0">
                                            <h4 class="title line-2">
                                                <a class="color-changer text-dark"
                                                    href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}">{{ $blog->title }}</a>
                                            </h4>
                                            <span class="blog-created text-muted">
                                                <i class="fa-regular fa-calendar-days"></i>
                                                <span
                                                    class="date">{{ helper::date_format($blog->created_at, $storeinfo->id) }}</span>
                                            </span>
                                            <div class="description color-changer line-2">{!! Str::limit($blog->description, 200) !!}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
            @else
                @if (@helper::checkaddons('blog'))
                    <div class="blog-6 owl-carousel owl-theme">
                        @foreach ($blog as $blog)
                            <div class="item h-100 mx-1">
                                <div class="card border h-100 rounded-0 border-0 overflow-hidden">
                                    <div class="blog-6-img">
                                        <a href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}">
                                            <img src="{{ helper::image_path($blog->image) }}" height="300"
                                                alt="blog img" class="w-100 object-fit-cover">
                                        </a>
                                        <div class="post-image-hover">
                                            <a href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}"
                                                class="blog-btn">
                                                <i class="fa-regular fa-link"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body px-0">
                                        <h4 class="title line-2">
                                            <a class="color-changer text-dark"
                                                href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}">{{ $blog->title }}</a>
                                        </h4>
                                        <span class="blog-created text-muted">
                                            <i class="fa-regular fa-calendar-days"></i>
                                            <span class="date">{{ helper::date_format($blog->created_at) }}</span>
                                        </span>
                                        <div class="description color-changer line-2">{!! Str::limit($blog->description, 200) !!}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </section>
@endif



@include('front.theme.footer')
