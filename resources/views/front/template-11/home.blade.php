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

<!-- Best-selling-Items -->
@if (helper::appdata($storeinfo->id)->product_section_display == 1 ||
        helper::appdata($storeinfo->id)->product_section_display == 3)
    @if (count($bestsellingitems) > 0)
        <section class="my-5">
            <div class="container">
                <div class="sec-header mb-3">
                    <h4 class="text-captalize color-changer main-title m-0">{{ trans('labels.selling_product') }}</h4>
                    <p class="m-0 line-2 fs-15 fw-500 mt-2 text-muted">{{ trans('labels.selling_product_subtitle') }}
                    </p>
                </div>
                <div
                    class="row row-cols-xl-6 row-cols-lg-4 row-cols-md-3 row-cols-2 recipe-card custom-product-card g-2 theme-11-card">
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
                        <div class="col responsive-col custom-product-column" id="pro-box">
                            <div class="pro-box h-100 rounded p-0">
                                <div class="pro-img rounded-0">
                                    <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                        @if (@$item['product_image']->image == null)
                                            <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                class="card-img-top rounded-2 h-100" alt="product image">
                                        @else
                                            <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                class="card-img-top rounded-2 h-100" alt="product image">
                                        @endif
                                    </a>
                                    <div class="sale-heart flex-wrap justify-content-end gap-2">
                                        <div class="d-flex w-100 justify-content-end align-items-center gap-2">
                                            @if ($off > 0)
                                                <div
                                                    class="shadow {{ session()->get('direction') == 2 ? 'sale-label-on-rtl' : 'sale-label-on' }}">
                                                    {{ $off }}% {{ trans('labels.off') }}
                                                </div>
                                            @endif
                                            @if (@helper::checkaddons('customer_login'))
                                                @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                    <a onclick="managefavorite('{{ $item->id }}',{{ $storeinfo->id }},'{{ helper::storefront_url($storeinfo, '/managefavorite') }}')"
                                                        class="btn-sm btn-Wishlist shadow cursor-pointer ">
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
                                        <button class="btn m-0 btn-sm shadow btn-cart"
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
                                <div class="product-details-wrap p-2">
                                    <div class="product-details-inner  mb-2 line-2">
                                        <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                            <h4 id="itemname"
                                                class="color-changer text-dark {{ session()->get('direction') == 2 ? 'text-right' : '' }}">
                                                {{ $item->item_name }}
                                            </h4>
                                        </a>
                                    </div>
                                    <div class="card-footer border-0 bg-transparent p-0">
                                        <div class="d-flex justify-content-between">
                                            <!-- rating -->
                                            @if (@helper::checkaddons('product_reviews'))
                                                <div class="d-flex align-items-center justify-content-between mb-2">
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
                                        <div class="d-flex align-items-baseline flex-wrap gap-1">
                                            <p class="pro-pricing color-changer line-1">
                                                {{ helper::currency_formate($price, $storeinfo->id) }}
                                            </p>
                                            @if ($original_price > $price)
                                                <p class="pro-pricing pro-org-value line-1 m-0">
                                                    {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                                </p>
                                            @endif
                                        </div>
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
    <section class="my-5">
        <div class="container">
            <div class="row g-3">
                <div class="col-lg-6">
                    <h6 class="fs-5 fw-500 color-changer line-1 mb-2">{{ helper::appdata($storeinfo->id)->whoweare_title }}</h6>
                    <h4 class="line-2 main-title color-changer fw-600">{{ helper::appdata($storeinfo->id)->whoweare_subtitle }}</h4>
                    <p class="m-0 text-muted fs-15 line-3">{{ helper::appdata($storeinfo->id)->whoweare_description }}
                    </p>
                    <div class="col-12">
                        <div class="row g-3 mt-1">
                            @foreach ($whowearedata as $whoweare)
                                <div class="col-12">
                                    <div class="card rounded-0 border h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="icon-img-15">
                                                    <img src="{{ helper::image_path($whoweare->image) }}"
                                                        alt="" class="border rounded-circle">
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

@if (helper::getcategory($storeinfo->id)->count() > 0)
    <section class="product-prev-sec product-list-sec pt-0">
        <div class="container">
            <div class="card card-bg mb-sm-4">
                <div class="card-header bg-transparent px-0">
                    <h4 class="text-captalize color-changer main-title m-0">{{ trans('labels.featured_products') }}</h4>
                    <p class="m-0 line-2 fs-15 fw-500 mt-2 text-muted">{{ trans('labels.featured_products_subtitle') }}
                    </p>
                </div>
                <!-- category -->
                <div class="category-7">
                    <ul id="myTab" class="nav nav-tabs justify-content-start gap-1 border-0 slider menu-nav"
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
                                <div class="item p-1">
                                    <div class="col category-book">
                                        <li class="nav-item">
                                            <a class="nav-link text-dark color-changer border-0 m-0" data-bs-toggle="tab"
                                                data-bs-target="#{{ $category->slug }}" type="button"
                                                role="tab" aria-controls="{{ $category->slug }}"
                                                aria-selected="true">{{ $category->name }}</a>
                                        </li>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </ul>
                </div>
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
                            <div
                                class="row row-cols-xl-6 row-cols-lg-4 row-cols-md-3 row-cols-2 recipe-card custom-product-card g-2 theme-11-card">
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
                                        <div class="col responsive-col custom-product-column" id="pro-box">
                                            <div class="pro-box h-100 rounded p-0">
                                                <div class="pro-img rounded">
                                                    <a
                                                        href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                        @if (@$item['product_image']->image == null)
                                                            <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                                class="cursor-pointer h-100" alt="product image">
                                                        @else
                                                            <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                                class="cursor-pointer h-100" alt="product image">
                                                        @endif
                                                    </a>
                                                    <div class="sale-heart flex-wrap justify-content-end gap-2">
                                                        <div
                                                            class="d-flex w-100 justify-content-end align-items-center gap-2">
                                                            @if ($off > 0)
                                                                <div
                                                                    class="shadow {{ session()->get('direction') == 2 ? 'sale-label-on-rtl' : 'sale-label-on' }}">
                                                                    {{ $off }}% {{ trans('labels.off') }}
                                                                </div>
                                                            @endif
                                                            @if (@helper::checkaddons('customer_login'))
                                                                @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                                    <a onclick="managefavorite('{{ $item->id }}',{{ $storeinfo->id }},'{{ helper::storefront_url($storeinfo, '/managefavorite') }}')"
                                                                        class="btn-sm btn-Wishlist shadow cursor-pointer ">
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
                                                        <button class="btn m-0 btn-sm shadow btn-cart"
                                                            id="iconverifybtn{{ $key }}_{{ $category->id }}"
                                                            onclick="GetProductOverview('{{ $item->slug }}',this.id)">
                                                            @if (helper::appdata($storeinfo->id)->online_order == 1)
                                                                <i class="fa-regular fa-cart-shopping"></i>
                                                            @else
                                                                <i class="fa-regular fa-eye"></i>
                                                            @endif
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="product-details-wrap p-2">
                                                    <div class="product-details-inner  mb-2 line-2">
                                                        <a
                                                            href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                            <h4 id="itemname"
                                                                class="color-changer text-dark {{ session()->get('direction') == 2 ? 'text-right' : '' }}">
                                                                {{ $item->item_name }}</h4>
                                                        </a>
                                                    </div>
                                                    <div class="card-footer border-0 bg-transparent p-0">
                                                        <div class="d-flex justify-content-between">
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
                                                            @if ($item->stock_management == 1)
                                                                @if (helper::checklowqty($item->id, $storeinfo->id) == 2 && $item->has_variants != 1)
                                                                    <div class="out-stock">
                                                                        <span class="out-stock-indicator-dot"></span>
                                                                        <p class="out-stock-text m-0">
                                                                            {{ trans('labels.out_of_stock') }}</p>
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
                                                        <div class="d-flex align-items-baseline flex-wrap gap-1">
                                                            <p class="pro-pricing color-changer line-1">
                                                                {{ helper::currency_formate($price, $storeinfo->id) }}
                                                            </p>
                                                            @if ($original_price > $price)
                                                                <p class="pro-pricing pro-org-value line-1 m-0">
                                                                    {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
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

@if ($bannerimage1->count() > 0)
    <section class="feature-sec my-5">
        <div class="container">
            <div class="feature-carousel-11 owl-carousel owl-theme m-0">
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
                    <div class="item h-100">
                        <div class="feature-box h-100">
                            <img src='{{ helper::image_path($image->banner_image) }}' class="">
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
        <section class="my-5">
            <div class="container">
                <div class="sec-header mb-3">
                    <h4 class="text-captalize color-changer main-title m-0">{{ trans('labels.top_rated_product') }}</h4>
                    <p class="m-0 line-2 fs-15 fw-500 mt-2 text-muted">
                        {{ trans('labels.top_rated_product_subtitle') }}
                    </p>
                </div>
                <div
                    class="row row-cols-xl-6 row-cols-lg-4 row-cols-md-3 row-cols-2 recipe-card custom-product-card g-2 theme-11-card">
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
                        <div class="col responsive-col custom-product-column" id="pro-box">
                            <div class="pro-box h-100 rounded-0 p-0">
                                <div class="pro-img rounded-0">
                                    <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                        @if (@$item['product_image']->image == null)
                                            <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                class="card-img-top rounded-2 h-100" alt="product image">
                                        @else
                                            <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                class="card-img-top rounded-2 h-100" alt="product image">
                                        @endif
                                    </a>
                                    <div class="sale-heart flex-wrap justify-content-end gap-2">
                                        <div class="d-flex w-100 justify-content-end align-items-center gap-2">
                                            @if ($off > 0)
                                                <div
                                                    class="shadow {{ session()->get('direction') == 2 ? 'sale-label-on-rtl' : 'sale-label-on' }}">
                                                    {{ $off }}% {{ trans('labels.off') }}
                                                </div>
                                            @endif
                                            @if (@helper::checkaddons('customer_login'))
                                                @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                    <a onclick="managefavorite('{{ $item->id }}',{{ $storeinfo->id }},'{{ helper::storefront_url($storeinfo, '/managefavorite') }}')"
                                                        class="btn-sm btn-Wishlist shadow cursor-pointer ">
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
                                        <button class="btn m-0 btn-sm shadow btn-cart"
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
                                <div class="product-details-wrap p-2">
                                    <div class="product-details-inner  mb-2 line-2">
                                        <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                            <h4 id="itemname"
                                                class="color-changer text-dark {{ session()->get('direction') == 2 ? 'text-right' : '' }}">
                                                {{ $item->item_name }}
                                            </h4>
                                        </a>
                                    </div>
                                    <div class="card-footer border-0 bg-transparent p-0">
                                        <div class="d-flex justify-content-between">
                                            <!-- rating -->
                                            @if (@helper::checkaddons('product_reviews'))
                                                <div class="d-flex align-items-center justify-content-between mb-2">
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
                                        <div class="d-flex align-items-baseline flex-wrap gap-1">
                                            <p class="pro-pricing color-changer line-1">
                                                {{ helper::currency_formate($price, $storeinfo->id) }}
                                            </p>
                                            @if ($original_price > $price)
                                                <p class="pro-pricing pro-org-value line-1 m-0">
                                                    {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                                </p>
                                            @endif
                                        </div>
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
    <section class="feature-sec my-5">
        <div class="container">
            <div class="feature-carousel-11 owl-carousel owl-theme">
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
        <section class="storereview-sec theme-11-testimonial mb-4">
            <div class="container">
                <div class="sec-header mb-3">
                    <h4 class="text-captalize color-changer main-title m-0">{{ trans('labels.testimonials') }}</h4>
                    <p class="m-0 line-2 fs-15 fw-500 mt-2 text-muted">{{ trans('labels.testimonials_subtitle') }}</p>
                </div>
                <div class="store-review-11 owl-carousel owl-theme">
                    @foreach ($testimonials as $item)
                        <div class="item p-1">
                            <div class="card border h-100 rounded-0">
                                <div class="card-header rounded-0 bg-secondary p-3 border-bottom">
                                    <div class="d-flex gap-3 align-items-center">
                                        <div class="review-img">
                                            <img src="{{ helper::image_path($item->image) }}" alt="">
                                        </div>
                                        <div class="">
                                            <h5 class="line-1 mb-1 review_title text-white">{{ $item->name }}</h5>
                                            <p class="review_date fs-8 m-0 text-white">
                                                {{ helper::date_format($item->created_at, $item->vendor_id) }}</p>
                                            <i
                                                class="fa-solid {{ session()->get('direction') == 2 ? 'fa-quote-left' : 'fa-quote-right' }} position-absolute"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    <div class="review_description">
                                        <p class="text-muted text-center">{{ $item->description }}</p>
                                    </div>
                                    @php
                                        $count = $item->star;
                                    @endphp
                                    <div class="d-flex justify-content-center gap-1 pt-2">
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
        <div class="container">
            {{-- <div class="sec-header py-2">
                <h3 class="fw-600">{{ trans('labels.our_latest_blogs') }}</h3>
            </div> --}}
            <div class="sec-header mb-3">
                <h4 class="text-captalize color-changer main-title m-0">{{ trans('labels.our_latest_blogs') }}</h4>
                <p class="m-0 line-2 fs-15 fw-500 mt-2 text-muted">{{ trans('labels.our_latest_blogs_subtitle') }}
                </p>
            </div>
            @php
                $blog = helper::getblogs($storeinfo->id);
            @endphp

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
                        <div class="blog-11 owl-carousel owl-theme th-2-p">
                            @foreach ($blog as $blog)
                                <div class="item h-100 mx-1">
                                    <div class="card border h-100 rounded-0 overflow-hidden">
                                        <div class="card-body px-0 pt-0 py-3">
                                            <div class="blog-6-img rounded-0">
                                                <a href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}">
                                                    <img src="{{ helper::image_path($blog->image) }}" height="250"
                                                        alt="blog img" class="w-100 object-fit-cover rounded-0">
                                                </a>
                                            </div>
                                            <h4 class="title bg-secondary p-3 text-white ">
                                                <a class="text-white line-1"
                                                    href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}">{{ $blog->title }}</a>
                                            </h4>
                                            <div class="px-3">
                                                <span class="blog-created">
                                                    <i class="fa-regular fa-calendar-days"></i>
                                                    <span
                                                        class="date">{{ helper::date_format($blog->created_at, $storeinfo->id) }}</span>
                                                </span>
                                                <div class="description text-muted line-2">{!! Str::limit($blog->description, 200) !!}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
            @else
                @if (@helper::checkaddons('blog'))
                    <div class="blog-6 owl-carousel owl-theme th-2-p">
                        @foreach ($blog as $blog)
                            <div class="item h-100">
                                <div class="card border-0 h-100 rounded-3 overflow-hidden p-2">
                                    <div class="blog-6-img rounded-3">
                                        <a href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}">
                                            <img src="{{ helper::image_path($blog->image) }}" height="300"
                                                alt="blog img" class="w-100 object-fit-cover rounded-3">
                                        </a>
                                    </div>
                                    <div class="card-body px-0 py-3">
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
