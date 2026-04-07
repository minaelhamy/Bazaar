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
                                <a onclick="GetProductOverview('{{ $item->slug }}','')" class="cursor-pointer">
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

<!---------- WHO WE ARE START ---------->
@if ($whowearedata->count() > 0)
    <section class="my-5">
        <div class="container">
            <div class="row g-3">
                <div class="col-lg-6">
                    <h4 class="fs-5 line-1 color-changer fw-500">{{ helper::appdata($storeinfo->id)->whoweare_title }}
                    </h4>
                    <h3 class="line-2 main-title color-changer fw-600">
                        {{ helper::appdata($storeinfo->id)->whoweare_subtitle }}</h3>
                    <p class="m-0 text-muted line-3 fs-15">{{ helper::appdata($storeinfo->id)->whoweare_description }}
                    </p>
                    <div class="col-12">
                        <div class="row g-3 mt-1">
                            @foreach ($whowearedata as $whoweare)
                                <div class="col-12">
                                    <div class="card border rounded-4 h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="icon-img-15">
                                                    <img src="{{ helper::image_path($whoweare->image) }}" alt=""
                                                        class="rounded-4 border">
                                                </div>
                                                <div class="tital-15">
                                                    <h6 class="line-1 color-changer fw-600">
                                                        {{ $whoweare->title }}
                                                    </h6>
                                                    <p class="m-0 fs-7 fw-500 text-muted mt-1 line-2">
                                                        {{ $whoweare->sub_title }}</p>
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

<!-- Best-selling-Items -->
@if (helper::appdata($storeinfo->id)->product_section_display == 1 ||
        helper::appdata($storeinfo->id)->product_section_display == 3)
    @if (count($bestsellingitems) > 0)
        <section class="mb-5 product-prev-sec p-0">
            <div class="container">
                <div class="sec-header py-2 mb-3">
                    <h4 class="main-title color-changer mb-2">{{ trans('labels.selling_product') }}</h4>
                    <p class="m-0 line-2 fs-15 text-muted">{{ trans('labels.selling_product_subtitle') }}</p>
                </div>
                <div class="row row-cols-xl-4 row-cols-lg-3 row-cols-md-2 row-cols-1 g-3 recipe-card">
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
                                                $item->item_price - @helper::top_deals($storeinfo->id)->offer_amount;
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
                                    $original_price > 0 ? number_format(100 - ($price * 100) / $original_price, 1) : 0;
                            } else {
                                if ($item['variation']->count() > 0) {
                                    $price = $item['variation'][0]->price;
                                    $original_price = $item['variation'][0]->original_price;
                                } else {
                                    $price = $item->item_price;
                                    $original_price = $item->item_original_price;
                                }
                                $off =
                                    $original_price > 0 ? number_format(100 - ($price * 100) / $original_price, 1) : 0;
                            }
                        @endphp
                        <div class="col">
                            <div class="product-card-box h-100">
                                <div class="product-details-wrap">
                                    <div class="reponsive-flex gap-2">
                                        <div class="content-side w-100 p-0">
                                            <div>
                                                <div class="d-flex gap-2 mb-1">
                                                    @if ($off > 0)
                                                        <div class="sale-label-on rounded-1 position-static res-bg">
                                                            {{ $off }}% {{ trans('labels.off') }}</div>
                                                    @endif
                                                    @if ($item->stock_management == 1)
                                                        @if (helper::checklowqty($item->id, $storeinfo->id) == 2 && $item->has_variants != 1)
                                                            <div class="out-stock">
                                                                <span class="out-stock-indicator-dot"></span>
                                                                <p class="out-stock-text m-0">
                                                                    {{ trans('labels.out_of_stock') }}
                                                                </p>
                                                            </div>
                                                        @else
                                                            <div class="in-stock">
                                                                <span class="in-stock-indicator-dot"></span>
                                                                <p class="in-stock-text m-0">
                                                                    {{ trans('labels.in_stock') }}</p>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                                <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                    <h4 id="itemname"
                                                        class="line-2 {{ session()->get('direction') == 2 ? 'text-right' : '' }} color-changer text-dark">
                                                        {{ $item->item_name }}
                                                    </h4>
                                                </a>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <p class="pro-pricing m-0 ">
                                                        {{ helper::currency_formate($price, $storeinfo->id) }}
                                                    </p>
                                                </div>
                                                @if ($original_price > $price)
                                                    <p class="text-muted pro-org-value">
                                                        {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                @if (@helper::checkaddons('product_reviews'))
                                                    @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                        <p class="m-0 rating-star d-inline cursor-pointer"
                                                            onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')">
                                                            <i class="fa-solid fa-star text-warning"></i>
                                                            <span
                                                                class="px-1 color-changer">{{ number_format($item->ratings_average, 1) }}</span>
                                                        </p>
                                                    @endif
                                                @endif

                                                @if (@helper::checkaddons('customer_login'))
                                                    @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                        <a onclick="managefavorite('{{ $item->id }}',{{ $storeinfo->id }},'{{ helper::storefront_url($storeinfo, '/managefavorite') }}')"
                                                            class="btn-sm btn-Wishlist3 cursor-pointer">
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
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                        <div class="content-side p-0">

                                            <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                <div class="product-img mb-3">
                                                    @if (@$item['product_image']->image == null)
                                                        <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                            class="cursor-pointer" alt="product image">
                                                    @else
                                                        <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                            class="cursor-pointer" alt="product image">
                                                    @endif

                                                </div>
                                            </a>
                                            <button class="btn btn-sm m-0 py-1 p-0 btn-content"
                                                id="verifybtn3{{ $key }}_{{ $item->id }}"
                                                onclick="GetProductOverview('{{ $item->slug }}',this.id)">{{ helper::appdata($storeinfo->id)->online_order == 1 ? trans('labels.add_to_cart') : trans('labels.view') }}</button>
                                        </div>
                                        @if ($item->stock_management == 1)
                                            @if (helper::checklowqty($item->id, $storeinfo->id) == 2 && $item->has_variants != 1)
                                                <div class="item-stock text-center"><span
                                                        class="bg-danger p-1 px-2 fs-8 rounded-1 text-white border border-white">{{ trans('labels.out_of_stock') }}</span>
                                                </div>
                                            @endif
                                        @endif
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

<!-- feature-sec -->
@if ($bannerimage2->count() > 0)
    <section class="feature-sec my-5 my-md-5">
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

@if (helper::getcategory($storeinfo->id)->count() > 0)
    <div class="product-sec2 mt-sm-5 mt-3">
        <div class="container">
            <div class="product-display mt-4 mb-5 row">
                <div class="side-sticky col-lg-3 col-xl-3 d-none d-lg-block">
                    <div class="side-menu-list {{ session()->get('direction') == 2 ? 'text-right' : 'text-left' }}">
                        <div class="card card-bg card-header cat-dispaly bg-transparent px-0">
                            <div class=" d-inline-block">
                                <h4
                                    class="theme-4-title color-changer {{ session()->get('direction') == 2 ? 'text-right' : '' }} m-0">
                                    {{ trans('labels.category') }}
                                </h4>
                            </div>
                        </div>

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
                                <div>
                                    <a class="nav-link  {{ session()->get('direction') == 2 ? 'rtl-side-cat-check' : 'side-cat-check' }} btn-sm {{ $key == 0 ? 'active' : '' }} {{ $category->slug }}"
                                        href="#{{ $category->slug }}">{{ $category->name }}</a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div
                    class="cat-product col-lg-9 col-xl-9 {{ session()->get('direction') == 2 ? 'pr-3' : 'pl-3' }} custom-categories-main-sec">
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
                            <div class="card card-header card-bg responsive-padding-top bg-transparent px-0 custom-cat-name-sec mb-1 mt-4"
                                id="{{ $category->slug }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-inline-block">
                                        <h4 class="theme-4-title m-0 color-changer">{{ $category->name }} ({{ $check_cat_count }})
                                        </h4>
                                    </div>
                                    <div class="d-none">
                                        <a href="#" class="btn-category">{{ trans('labels.view_all') }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div
                            class="row row-cols-xl-3 row-cols-lg-2 row-cols-md-2 row-cols-1 recipe-card custom-product-card g-3">
                            @if (!helper::getcategory($storeinfo->id)->isEmpty())
                                @php $i = 0; @endphp
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
                                        <div class="col custom-product-column" id="pro-box">
                                            <div class="product-card-box h-100">
                                                <div class="product-details-wrap">
                                                    <div class="reponsive-flex gap-2">
                                                        <div class="content-side w-100 p-0">
                                                            <div>
                                                                <div class="d-flex gap-2 mb-1">
                                                                    @if ($off > 0)
                                                                        <div
                                                                            class="sale-label-on rounded-1 position-static res-bg">
                                                                            {{ $off }}%
                                                                            {{ trans('labels.off') }}</div>
                                                                    @endif
                                                                    @if ($item->stock_management == 1)
                                                                        @if (helper::checklowqty($item->id, $storeinfo->id) == 2 && $item->has_variants != 1)
                                                                            <div class="out-stock">
                                                                                <span
                                                                                    class="out-stock-indicator-dot"></span>
                                                                                <p class="out-stock-text m-0">
                                                                                    {{ trans('labels.out_of_stock') }}
                                                                                </p>
                                                                            </div>
                                                                        @else
                                                                            <div class="in-stock">
                                                                                <span
                                                                                    class="in-stock-indicator-dot"></span>
                                                                                <p class="in-stock-text m-0">
                                                                                    {{ trans('labels.in_stock') }}</p>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                                <a
                                                                    href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                    <h4 id="itemname" class="line-2 color-changer text-dark {{ session()->get('direction') == 2 ? 'text-right' : '' }}">
                                                                        {{ $item->item_name }}
                                                                    </h4>
                                                                </a>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <div>
                                                                    <p class="pro-pricing m-0 ">
                                                                        {{ helper::currency_formate($price, $storeinfo->id) }}
                                                                    </p>
                                                                </div>
                                                                @if ($original_price > $price)
                                                                    <p class="text-muted pro-org-value">
                                                                        {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                                                    </p>
                                                                @endif
                                                            </div>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between">
                                                                @if (@helper::checkaddons('product_reviews'))
                                                                    @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                                        <p class="m-0 rating-star d-inline cursor-pointer"
                                                                            onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')"
                                                                            aria-controls="offcanvasRight">
                                                                            <i
                                                                                class="fa-solid fa-star text-warning"></i>
                                                                            <span
                                                                                class="px-1 color-changer">{{ number_format($item->ratings_average, 1) }}</span>
                                                                        </p>
                                                                    @endif
                                                                @endif

                                                                @if (@helper::checkaddons('customer_login'))
                                                                    @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                                        <a onclick="managefavorite('{{ $item->id }}',{{ $storeinfo->id }},'{{ helper::storefront_url($storeinfo, '/managefavorite') }}')"
                                                                            class="btn-sm btn-Wishlist3 cursor-pointer">
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
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="content-side p-0">
                                                            <a
                                                                href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                <div class="product-img mb-3">
                                                                    @if (@$item['product_image']->image == null)
                                                                        <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                                            class="cursor-pointer"
                                                                            alt="product image">
                                                                    @else
                                                                        <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                                            class="cursor-pointer"
                                                                            alt="product image">
                                                                    @endif

                                                                </div>
                                                            </a>
                                                            <button class="btn btn-sm m-0 py-1 p-0 btn-content"
                                                                id="verifybtn{{ $key }}_{{ $category->id }}"
                                                                onclick="GetProductOverview('{{ $item->slug }}',this.id)">{{ helper::appdata($storeinfo->id)->online_order == 1 ? trans('labels.add_to_cart') : trans('labels.view') }}</button>
                                                        </div>
                                                        @if ($item->stock_management == 1)
                                                            @if (helper::checklowqty($item->id, $storeinfo->id) == 2 && $item->has_variants != 1)
                                                                <div class="item-stock text-center"><span
                                                                        class="bg-danger p-1 px-2 fs-8 rounded-1 text-white border border-white">{{ trans('labels.out_of_stock') }}</span>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @php $i += 1; @endphp
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

@if ($bannerimage1->count() > 0)
    <section class="feature-sec my-4 my-lg-5">
        <div class="container">
            <div class="feature-carousel owl-carousel owl-rtl owl-theme">
                @foreach ($bannerimage1 as $image)
                    @if ($image->type == 1)
                        <a href="{{ helper::storefront_url($storeinfo, '/search?category=' . @$image['category_info']->slug) }}"
                            class="cursor-pointer">
                        @elseif($image->type == 2)
                            @php
                                $item = helper::itemdetails($image->product_id, $storeinfo->id);
                            @endphp
                            <a onclick="GetProductOverview('{{ $item->slug }}','')" class="cursor-pointer">
                            @else
                                <a href="javascript:void(0)" class="cursor-pointer">
                    @endif
                    <div class="item ">
                        <div class="feature-box">
                            <img src='{{ helper::image_path($image->banner_image) }}' alt="" class="">
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
    @include('front.testimonial')
@endif

<!-- Top-Rated-Items -->
@if (helper::appdata($storeinfo->id)->product_section_display == 2 ||
        helper::appdata($storeinfo->id)->product_section_display == 3)
    @if (count($toprateditems) > 0)
        <section class="mb-5 product-prev-sec p-0">
            <div class="container">
                <div class="sec-header py-2 mb-3">
                    <h4 class="main-title color-changer mb-2">{{ trans('labels.top_rated_product') }}</h4>
                    <p class="m-0 line-2 fs-15 text-muted">{{ trans('labels.top_rated_product_subtitle') }}</p>
                </div>
                <div class="row row-cols-xl-4 row-cols-lg-3 row-cols-md-2 row-cols-1 g-3 recipe-card">
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
                                                $item->item_price - @helper::top_deals($storeinfo->id)->offer_amount;
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
                                    $original_price > 0 ? number_format(100 - ($price * 100) / $original_price, 1) : 0;
                            } else {
                                if ($item['variation']->count() > 0) {
                                    $price = $item['variation'][0]->price;
                                    $original_price = $item['variation'][0]->original_price;
                                } else {
                                    $price = $item->item_price;
                                    $original_price = $item->item_original_price;
                                }
                                $off =
                                    $original_price > 0 ? number_format(100 - ($price * 100) / $original_price, 1) : 0;
                            }
                        @endphp
                        <div class="col">
                            <div class="product-card-box h-100">
                                <div class="product-details-wrap">
                                    <div class="reponsive-flex gap-2">
                                        <div class="content-side w-100 p-0">
                                            <div>
                                                <div class="d-flex gap-2 mb-1">
                                                    @if ($off > 0)
                                                        <div class="sale-label-on rounded-1 position-static res-bg">
                                                            {{ $off }}% {{ trans('labels.off') }}</div>
                                                    @endif
                                                    @if ($item->stock_management == 1)
                                                        @if (helper::checklowqty($item->id, $storeinfo->id) == 2 && $item->has_variants != 1)
                                                            <div class="out-stock">
                                                                <span class="out-stock-indicator-dot"></span>
                                                                <p class="out-stock-text m-0">
                                                                    {{ trans('labels.out_of_stock') }}
                                                                </p>
                                                            </div>
                                                        @else
                                                            <div class="in-stock">
                                                                <span class="in-stock-indicator-dot"></span>
                                                                <p class="in-stock-text m-0">
                                                                    {{ trans('labels.in_stock') }}</p>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                                <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                    <h4 id="itemname" class="line-2 color-changer text-dark {{ session()->get('direction') == 2 ? 'text-right' : '' }}">
                                                        {{ $item->item_name }}
                                                    </h4>
                                                </a>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <p class="pro-pricing m-0 ">
                                                        {{ helper::currency_formate($price, $storeinfo->id) }}
                                                    </p>
                                                </div>
                                                @if ($original_price > $price)
                                                    <p class="text-muted pro-org-value">
                                                        {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                @if (@helper::checkaddons('product_reviews'))
                                                    @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                        <p class="m-0 rating-star d-inline cursor-pointer"
                                                            onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')">
                                                            <i class="fa-solid fa-star text-warning"></i>
                                                            <span
                                                                class="px-1 color-changer">{{ number_format($item->ratings_average, 1) }}</span>
                                                        </p>
                                                    @endif
                                                @endif

                                                @if (@helper::checkaddons('customer_login'))
                                                    @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                        <a onclick="managefavorite('{{ $item->id }}',{{ $storeinfo->id }},'{{ helper::storefront_url($storeinfo, '/managefavorite') }}')"
                                                            class="btn-sm btn-Wishlist3 cursor-pointer">
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
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                        <div class="content-side p-0">

                                            <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                <div class="product-img mb-3">
                                                    @if (@$item['product_image']->image == null)
                                                        <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                            class="cursor-pointer" alt="product image">
                                                    @else
                                                        <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                            class="cursor-pointer" alt="product image">
                                                    @endif

                                                </div>
                                            </a>
                                            <button class="btn btn-sm m-0 py-1 p-0 btn-content"
                                                id="verifybtn{{ $key }}_{{ $item->id }}"
                                                onclick="GetProductOverview('{{ $item->slug }}',this.id)">{{ helper::appdata($storeinfo->id)->online_order == 1 ? trans('labels.add_to_cart') : trans('labels.view') }}</button>
                                        </div>
                                        @if ($item->stock_management == 1)
                                            @if (helper::checklowqty($item->id, $storeinfo->id) == 2 && $item->has_variants != 1)
                                                <div class="item-stock text-center"><span
                                                        class="bg-danger p-1 px-2 fs-8 rounded-1 text-white border border-white">{{ trans('labels.out_of_stock') }}</span>
                                                </div>
                                            @endif
                                        @endif
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

<!-- blog -->
@if (helper::getblogs($storeinfo->id)->count() > 0)
    <section class="blog-6-sec">
        @php
            $blog = helper::getblogs($storeinfo->id);
        @endphp
        <div class="container">
            <div class="sec-header py-2 mb-3">
                <h4 class="main-title color-changer mb-2">{{ trans('labels.our_latest_blogs') }}</h4>
                <p class="m-0 line-2 fs-15 text-muted">{{ trans('labels.our_latest_blogs_subtitle') }}</p>
            </div>
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
                        <div class="blog-6 owl-carousel owl-theme overflow-hidden">
                            @foreach ($blog as $blog)
                                <div class="item h-100 mx-1">
                                    <div class="card border h-100 rounded-3 p-2">
                                        <div class="blog-6-img rounded-3">
                                            <a href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}">
                                                <img src="{{ helper::image_path($blog->image) }}" height="300"
                                                    alt="blog img" class="w-100 object-fit-cover">
                                            </a>
                                            <div class="post-image-hover">
                                                <a href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}"
                                                    class="blog-btn">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body px-0">
                                            <h4 class="title line-2">
                                                <a
                                                    href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}" class="color-changer text-dark">{{ $blog->title }}</a>
                                            </h4>
                                            <span class="blog-created text-muted">
                                                <i class="fa-regular fa-calendar-days"></i>
                                                <span
                                                    class="date">{{ helper::date_format($blog->created_at, $storeinfo->id) }}</span>
                                            </span>
                                            <div class="description text-muted line-2">{!! Str::limit($blog->description, 200) !!}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    @endif
                @endif
            @else
                @if (@helper::checkaddons('blog'))
                    <div class="blog-6 owl-carousel owl-theme overflow-hidden">
                        @foreach ($blog as $blog)
                            <div class="item h-100 mx-1">
                                <div class="card border h-100 rounded-3 p-2">
                                    <div class="blog-6-img rounded-3">
                                        <a href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}">
                                            <img src="{{ helper::image_path($blog->image) }}" height="300"
                                                alt="blog img" class="w-100 object-fit-cover">
                                        </a>
                                        <div class="post-image-hover">
                                            <a href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}"
                                                class="blog-btn">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body px-0">
                                        <h4 class="title line-2">
                                            <a
                                                href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}">{{ $blog->title }}</a>
                                        </h4>
                                        <span class="blog-created">
                                            <i class="fa-regular fa-calendar-days"></i>
                                            <span
                                                class="date">{{ helper::date_format($blog->created_at, $storeinfo->id) }}</span>
                                        </span>
                                        <div class="description line-2">{!! Str::limit($blog->description, 200) !!}</div>
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
<!--------- newsletter --------->
@include('front.newsletter')

@include('front.theme.footer')
