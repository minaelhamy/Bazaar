@include('front.theme.header')


<section class="header-10">
    <div class="container">
        <div class="text-center">
            <!-- title -->
            <h1 class="title color-changer text-dark">{{ helper::appdata($storeinfo->id)->website_title }}</h1>
            <!-- sub title -->
            <p class="sub-title"><a href="mailto:{{ helper::appdata($storeinfo->id)->email }}"
                    class="call color-changer">{{ helper::appdata($storeinfo->id)->email }}</a></p>
            <!-- contact -->
            <a href="tel:{{ helper::appdata($storeinfo->id)->contact }}" target="_blank" class="d-block mb-3">
                <span class="mx-2 color-changer call">{{ helper::appdata($storeinfo->id)->contact }}</span>
            </a>
            <!-- social-media -->
            <div class="d-flex justify-content-center">
                <div class="social-media">
                    <ul class="d-flex gap-2 m-0 p-0">
                        @foreach (@helper::getsociallinks($storeinfo->id) as $links)
                            <li><a href="{{ $links->link }}" target="_blank"
                                    class="social-rounded fb p-0">{!! $links->icon !!}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

@if ($bannerimage1->count() > 0)
    <section class="feature-sec my-5">
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
                    <div class="item">
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

<!-- Best-selling-Items -->
@if (helper::appdata($storeinfo->id)->product_section_display == 1 ||
        helper::appdata($storeinfo->id)->product_section_display == 3)
    @if (count($bestsellingitems) > 0)
        <section class="my-5 theme-10">
            <div class="container">
                <div class="sec-header mb-4">
                    <h4 class="main-title-8 color-changer main-title m-0">{{ trans('labels.selling_product') }}</h4>
                    <p class="m-0 line-2 fs-15 fw-500 mt-2 text-muted">{{ trans('labels.selling_product_subtitle') }}
                    </p>
                </div>
                <div class="row row-cols-xl-5 row-cols-lg-4 row-cols-md-3 row-cols-2 g-3">
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
                            <div class="card pro-box border h-100 rounded-0 position-relative p-0">
                                <div class="pro-img rounded-0">
                                    <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                        @if (@$item['product_image']->image == null)
                                            <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                class="card-img-top rounded-2" alt="product image">
                                        @else
                                            <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                class="card-img-top rounded-2" alt="product image">
                                        @endif
                                    </a>
                                </div>
                                @if ($off > 0)
                                    <div
                                        class="ribbon-pop {{ session()->get('direction') == 2 ? 'rtl rounded-start-5' : 'rounded-end-5' }}">
                                        {{ $off }}% {{ trans('labels.off') }}</div>
                                @endif
                                <div class="card-body product-details-wrap p-2">
                                    <div class="icon-cart-hart {{ session()->get('direction') == 2 ? 'rtl' : '' }}">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <!-- wishlist -->
                                            @if (@helper::checkaddons('customer_login'))
                                                @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                    <div
                                                        class="wishlist {{ session()->get('direction') == 2 ? 'rtl' : 'ltr' }}">
                                                        <a onclick="managefavorite('{{ $item->id }}','{{ $storeinfo->id }}','{{ helper::storefront_url($storeinfo, '/managefavorite') }}')"
                                                            title="wishlist" class="btn-sm btn-Wishlist shadow">
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
                                                    </div>
                                                @endif
                                            @endif
                                            <button class="btn m-0 btn-sm shadow btn-product-cart" title="cart"
                                                id="iconverifybtn{{ $key }}_{{ $item->id }}"
                                                onclick="GetProductOverview('{{ $item->slug }}',this.id)">
                                                @if (helper::appdata($storeinfo->id)->online_order == 1)
                                                    <i class="fa-regular fa-cart-shopping"></i>
                                                @else
                                                    <i class="fa-regular fa-eye"></i>
                                                @endif
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mb-2 line-2">
                                        <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                            <h4 class="fs-7 color-changer text-dark fw-600">
                                                {{ $item->item_name }}
                                            </h4>
                                        </a>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <!-- rating -->
                                        @if (@helper::checkaddons('product_reviews'))
                                            <div class="d-flex align-items-center justify-content-between">
                                                @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                    <p class="m-0 rating-star d-inline cursor-pointer"
                                                        onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')">
                                                        <i class="fa-solid fa-star text-warning"></i>
                                                        <span
                                                            class="px-1 color-changer">{{ number_format($item->ratings_average, 1) }}</span>
                                                    </p>
                                                @endif
                                            </div>
                                        @endif
                                        <!-- stock -->
                                        @if ($item->stock_management == 1)
                                            @if (helper::checklowqty($item->id, $storeinfo->id) == 2 && $item->has_variants != 1)
                                                <div class="out-stock">
                                                    <span class="out-stock-indicator-dot"></span>
                                                    <p class="out-stock-text">
                                                        {{ trans('labels.out_of_stock') }}
                                                    </p>
                                                </div>
                                            @else
                                                <div class="in-stock">
                                                    <span class="in-stock-indicator-dot"></span>
                                                    <p class="in-stock-text">{{ trans('labels.in_stock') }}
                                                    </p>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="card-footer p-2 border-0 bg-transparent">
                                    <div class="d-flex align-items-baseline flex-wrap gap-1">
                                        <p class="pro-pricing color-changer line-1 m-0">
                                            {{ helper::currency_formate($price, $storeinfo->id) }}
                                        </p>
                                        <!-- false-price -->
                                        @if ($original_price > $price)
                                            <del class="fs-13 fw-600 text-muted m-0">
                                                {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                            </del>
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

<!---------- WHO WE ARE START ---------->
@if ($whowearedata->count() > 0)
    <section class="my-4 my-lg-5">
        <div class="container">
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="img-15">
                        <img src="{{ helper::image_path(helper::appdata($storeinfo->id)->whoweare_image) }}"
                            alt="">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="sec-header mb-2">
                        <h4 class="main-title-8 color-changer fs-5 line-1 m-0">{{ helper::appdata($storeinfo->id)->whoweare_title }}
                        </h4>
                    </div>
                    <h3 class="line-2 main-tital color-changer fw-600">{{ helper::appdata($storeinfo->id)->whoweare_subtitle }}</h3>
                    <p class="m-0 text-muted fs-15 line-3">{{ helper::appdata($storeinfo->id)->whoweare_description }}
                    </p>
                    <div class="col-12">
                        <div class="row g-3 mt-1">
                            @foreach ($whowearedata as $whoweare)
                                <div class="col-12">
                                    <div class="card bg-primary border h-100">
                                        <div class="card-body p-2">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="icon-img-15">
                                                    <img src="{{ helper::image_path($whoweare->image) }}"
                                                        alt="" class="border rounded">
                                                </div>
                                                <div class="tital-15">
                                                    <h6 class="line-1 text-white fw-600">
                                                        {{ $whoweare->title }}
                                                    </h6>
                                                    <p class="m-0 fs-8 text-white fw-500 mt-1 line-2">
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
            </div>
        </div>
    </section>
@endif
<!----------- WHO WE ARE END ----------->

@if (@helper::getcategory($storeinfo->id)->count() > 0)
    <section class="product-prev-sec product-list-sec theme-10-cat pt-20">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-8 col-12 pro-sec pro-top">
                    <div class="cart-pro-head">
                        <div class="z-index-9 row g-2">
                            @foreach (@helper::getcategory($storeinfo->id) as $key => $category)
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ helper::storefront_url($storeinfo, '/search?category=' . $category->slug) }}"
                                        class="cat-check card-10 m-0">
                                        <p>{{ $category->name }}</p>
                                    </a>
                                    <div class="btn-copy">
                                        <a class="btn rounded-2 copy-btn" id="clickCopy{{ $key }}"
                                            onclick="copy('{{ $key }}')" data-toggle="tooltip"
                                            data-placement="top" title="Copy">
                                            <i class="fa-solid fa-copy"></i>
                                        </a>
                                    </div>
                                    <div id="goodContent{{ $key }}" class="d-none">
                                        {{ helper::storefront_url($storeinfo, '/search?category=' . $category->slug) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endif

<!-- feature-sec -->
@if ($bannerimage2->count() > 0)
    <section class="feature-sec my-4 my-md-5">
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
                        <div class="feature-box rounded-0">
                            <img src="{{ helper::image_path($image->banner_image) }}" alt=""
                                class="rounded-0">
                        </div>
                    </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

<!-- Top-Rated-Items -->
@if (helper::appdata($storeinfo->id)->product_section_display == 2 ||
        helper::appdata($storeinfo->id)->product_section_display == 3)
    @if (count($toprateditems) > 0)
        <section class="my-5 theme-10">
            <div class="container">
                <div class="sec-header mb-4">
                    <h4 class="main-title-8 color-changer main-title m-0">{{ trans('labels.top_rated_product') }}</h4>
                    <p class="m-0 line-2 fs-15 fw-500 mt-2 text-muted">
                        {{ trans('labels.top_rated_product_subtitle') }}
                    </p>
                </div>
                <div class="row row-cols-xl-5 row-cols-lg-4 row-cols-md-3 row-cols-2 g-3">
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
                            <div class="card pro-box border h-100 position-relative p-0 rounded-0">
                                <div class="pro-img rounded-0">
                                    <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                        @if (@$item['product_image']->image == null)
                                            <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                class="card-img-top rounded-2" alt="product image">
                                        @else
                                            <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                class="card-img-top rounded-2" alt="product image">
                                        @endif
                                    </a>
                                </div>
                                @if ($off > 0)
                                    <div
                                        class="ribbon-pop {{ session()->get('direction') == 2 ? 'rtl rounded-start-5' : 'rounded-end-5' }}">
                                        {{ $off }}% {{ trans('labels.off') }}</div>
                                @endif
                                <div class="card-body product-details-wrap p-2">
                                    <div class="icon-cart-hart {{ session()->get('direction') == 2 ? 'rtl' : '' }}">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <!-- wishlist -->
                                            @if (@helper::checkaddons('customer_login'))
                                                @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                    <div
                                                        class="wishlist {{ session()->get('direction') == 2 ? 'rtl' : 'ltr' }}">
                                                        <a onclick="managefavorite('{{ $item->id }}','{{ $storeinfo->id }}','{{ helper::storefront_url($storeinfo, '/managefavorite') }}')"
                                                            title="wishlist" class="btn-sm btn-Wishlist shadow">
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
                                                    </div>
                                                @endif
                                            @endif
                                            <button class="btn m-0 btn-sm shadow btn-product-cart" title="cart"
                                                id="iconverifybtn3{{ $key }}_{{ $item->id }}"
                                                onclick="GetProductOverview('{{ $item->slug }}',this.id)">
                                                @if (helper::appdata($storeinfo->id)->online_order == 1)
                                                    <i class="fa-regular fa-cart-shopping"></i>
                                                @else
                                                    <i class="fa-regular fa-eye"></i>
                                                @endif
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mb-2 line-2">
                                        <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                            <h4 class="fs-7 fw-600 color-changer text-dark">
                                                {{ $item->item_name }}
                                            </h4>
                                        </a>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <!-- rating -->
                                        @if (@helper::checkaddons('product_reviews'))
                                            <div class="d-flex align-items-center justify-content-between">
                                                @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                    <p class="m-0 rating-star d-inline cursor-pointer"
                                                        onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')">
                                                        <i class="fa-solid fa-star text-warning"></i>
                                                        <span
                                                            class="px-1 color-changer">{{ number_format($item->ratings_average, 1) }}</span>
                                                    </p>
                                                @endif
                                            </div>
                                        @endif
                                        <!-- stock -->
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
                                                    <p class="in-stock-text m-0">{{ trans('labels.in_stock') }}
                                                    </p>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="card-footer border-0 bg-transparent p-2">
                                    <div class="d-flex align-items-baseline flex-wrap gap-1">
                                        <p class="pro-pricing color-changer line-1 m-0">
                                            {{ helper::currency_formate($price, $storeinfo->id) }}
                                        </p>
                                        <!-- false-price -->
                                        @if ($original_price > $price)
                                            <del class="fs-13 fw-600 text-muted m-0">
                                                {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                            </del>
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

<!--------- storereview --------->
@if (@helper::checkaddons('store_reviews'))
    @if ($testimonials->count() > 0)
        <section class="storereview-sec mb-5 bg-light py-5">
            <div class="container">
                <div class="sec-header mb-4">
                    <h4 class="main-title-8 color-changer main-title m-0">{{ trans('labels.testimonials') }}</h4>
                    <p class="m-0 line-2 fs-15 fw-500 mt-2 text-muted">{{ trans('labels.testimonials_subtitle') }}</p>
                </div>
                <div class="store-review-8 owl-carousel owl-theme">
                    @foreach ($testimonials as $item)
                        <div class="item">
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

<!-- blogs -->
@if (helper::getblogs($storeinfo->id)->Count() > 0)
    <section class="blog-6-sec my-5">
        <div class="container">
            @php
                $blog = helper::getblogs($storeinfo->id);
            @endphp
            <div class="sec-header mb-4">
                <h4 class="main-title-8 color-changer main-title m-0">{{ trans('labels.our_latest_blogs') }}</h4>
                <p class="m-0 line-2 fs-15 fw-500 mt-2 text-muted">{{ trans('labels.our_latest_blogs_subtitle') }}</p>
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
                        <div class="blogs-8 owl-carousel owl-theme">
                            @for ($i = 0; $i < 4; $i++)
                                <div class="item mx-1 h-100">
                                    <div class="card border h-100 overflow-hidden">
                                        <div class="blog-6-img">
                                            <a href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog[$i]->slug) }}">
                                                <img src="{{ helper::image_path($blog[$i]->image) }}" height="300"
                                                    alt="blog img" class="w-100 object-fit-cover">
                                            </a>
                                            <div class="post-image-hover">
                                                <a href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog[$i]->slug) }}"
                                                    class="blog-btn blog-8-2" title="Read More">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h4 class="title line-2">
                                                <a class="color-changer text-dark"
                                                    href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog[$i]->slug) }}">{{ $blog[$i]->title }}</a>
                                            </h4>
                                            <span class="blog-created text-muted">
                                                <i class="fa-regular fa-calendar-days"></i>
                                                <span
                                                    class="date">{{ helper::date_format($blog[$i]->created_at, $storeinfo->id) }}</span>
                                            </span>
                                            <div class="description color-changer line-2">{!! Str::limit($blog[$i]->description, 200) !!}</div>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    @endif
                @endif
            @else
                @if (@helper::checkaddons('blog'))
                    <div class="blogs-8 owl-carousel owl-theme overflow-hidden">
                        @for ($i = 0; $i < 4; $i++)
                            <div class="item h-100 mx-1">
                                <div class="card border h-100 overflow-hidden">
                                    <div class="blog-6-img">
                                        <a href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog[$i]->slug) }}">
                                            <img src="{{ helper::image_path($blog[$i]->image) }}" height="300"
                                                alt="blog img" class="w-100 object-fit-cover">
                                        </a>
                                        <div class="post-image-hover">
                                            <a href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog[$i]->slug) }}"
                                                class="blog-btn blog-8-2" title="Read More">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h4 class="title line-2">
                                            <a class="color-changer text-dark"
                                                href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog[$i]->slug) }}">{{ $blog[$i]->title }}</a>
                                        </h4>
                                        <span class="blog-created text-muted">
                                            <i class="fa-regular fa-calendar-days"></i>
                                            <span
                                                class="date">{{ helper::date_format($blog[$i]->created_at, $storeinfo->id) }}</span>
                                        </span>
                                        <div class="description color-changer line-2">{!! Str::limit($blog[$i]->description, 200) !!}</div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                @endif
            @endif
        </div>
    </section>
@endif



@include('front.theme.footer')


<script>
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })

    function copy(id) {
        "use strict";
        copyToClipboard(document.getElementById("goodContent" + id));
        toastr.success("Copied");
    }

    function copyToClipboard(e) {
        "use strict";
        var tempItem = document.createElement('input');

        tempItem.setAttribute('type', 'text');
        tempItem.setAttribute('display', 'none');

        let content = e;
        if (e instanceof HTMLElement) {
            content = e.innerHTML;
        }

        tempItem.setAttribute('value', content);
        document.body.appendChild(tempItem);

        tempItem.select();
        document.execCommand('Copy');

        tempItem.parentElement.removeChild(tempItem);
    }
</script>
