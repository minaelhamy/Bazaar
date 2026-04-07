@include('front.theme.header')

<!-- furniture_home -->
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

<!-- feature-sec -->
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
        <section class="my-5">
            <div class="container">
                <div class="sec-header mb-4">
                    <h4 class="main-title-8 color-changer main-title m-0">{{ trans('labels.selling_product') }}</h4>
                    <p class="m-0 line-2 fs-15 fw-500 mt-2 text-muted">{{ trans('labels.selling_product_subtitle') }}
                    </p>
                </div>
                <div class="pro-theme-8 pro-8 owl-carousel owl-theme position-relative mb-5">
                    @php $i = 0; @endphp
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
                        <div class="item h-100">
                            <div class="card h-100 w-100 border overflow-hidden">
                                @if ($off > 0)
                                    <div class="sale-label-on ltr">{{ $off }}% {{ trans('labels.off') }}</div>
                                @endif
                                <div class="pro-8-img position-relative">
                                    <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                        @if (@$item['product_image']->image == null)
                                            <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                class="card-img-top rounded-2" alt="product image">
                                        @else
                                            <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                class="card-img-top rounded-2" alt="product image">
                                        @endif
                                    </a>
                                    <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                        @if ($item['multi_image']->count() > 1)
                                            <img src="{{ @helper::image_path($item['multi_image'][1]->image) }}"
                                                alt="product image" class="w-100 object-fit-cover img-flip">
                                        @endif
                                    </a>
                                    <ul class="outer-functional">
                                        <li>
                                            @if (@helper::checkaddons('customer_login'))
                                                @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                    <a onclick="managefavorite('{{ $item->id }}',{{ $storeinfo->id }},'{{ helper::storefront_url($storeinfo, '/managefavorite') }}')"
                                                        class="cursor-pointer {{ session()->get('direction') == 2 ? 'me-auto' : 'ms-auto' }}"
                                                        title="wishlist">
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
                                        </li>
                                        <li>
                                            <button class="btn p-0 border-0" title="cart"
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

                                    <!-- in-stock -->
                                    @if ($item->stock_management == 1)
                                        @if (helper::checklowqty($item->id, $storeinfo->id) == 2 && $item->has_variants != 1)
                                            <div class="out-stock m-0 badge bg-white border rounded-pill rounded-0">
                                                <span class="out-stock-indicator-dot"></span>
                                                <p class="out-stock-text">
                                                    {{ trans('labels.out_of_stock') }}
                                                </p>
                                            </div>
                                        @else
                                            <div class="in-stock m-0 badge bg-white border rounded-pill rounded-0">
                                                <span class="in-stock-indicator-dot"></span>
                                                <p class="in-stock-text">{{ trans('labels.in_stock') }}
                                                </p>
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                <div class="card-body pb-0">
                                    <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                        <h4 class="title line-2 text-secondary">
                                            {{ $item->item_name }}
                                        </h4>
                                    </a>
                                </div>
                                <div class="card-footer bg-transparent border-0">
                                    <!-- rating -->
                                    @if (@helper::checkaddons('product_reviews'))
                                        <div class="d-flex align-items-center justify-content-between mb-2 ">
                                            @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                <p class="m-0 rating-star cursor-pointer"
                                                    onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')">
                                                    <i class="fa-solid fa-star text-warning"></i>
                                                    <span
                                                        class="px-1 color-changer">{{ number_format($item->ratings_average, 1) }}</span>
                                                </p>
                                            @endif
                                        </div>
                                    @endif
                                    <!-- price -->
                                    <p class="price color-changer m-0">
                                        {{ helper::currency_formate($price, $storeinfo->id) }}

                                        <!-- false-price -->
                                        @if ($original_price > $price)
                                            <span class="theme-5-false-price">
                                                {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                            </span>
                                        @endif
                                    </p>
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
                    <div class="sec-header mb-2">
                        <h4 class="main-title-8 fs-5 color-changer line-1 m-0">{{ helper::appdata($storeinfo->id)->whoweare_title }}
                        </h4>
                    </div>
                    <h3 class="line-2 main-title color-changer fw-600">{{ helper::appdata($storeinfo->id)->whoweare_subtitle }}</h3>
                    <p class="m-0 text-muted fs-15 line-3">{{ helper::appdata($storeinfo->id)->whoweare_description }}
                    </p>
                    <div class="col-12">
                        <div class="row g-3 mt-1">
                            @foreach ($whowearedata as $whoweare)
                                <div class="col-md-6">
                                    <div class="card bg-theme-8 border h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center gap-3 flex-column">
                                                <div class="icon-img-15">
                                                    <img src="{{ helper::image_path($whoweare->image) }}"
                                                        alt="" class="border rounded-circle">
                                                </div>
                                                <div class="tital-15 text-center">
                                                    <h6 class="line-1 text-center color-changer fw-600">
                                                        {{ $whoweare->title }}
                                                    </h6>
                                                    <p class="m-0 fs-8 fw-500 mt-1 color-changer line-2">{{ $whoweare->sub_title }}
                                                    </p>
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

<!-- category with products -->
@if (helper::getcategory($storeinfo->id)->count() > 0)
    <div class="product-sec2 mt-sm-5 mt-3">
        <div class="container">
            <div class="product-display">
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
                        <div class="card card-bg card-header sec-header bg-transparent px-0 mb-3 w-100"
                            id="{{ $category->slug }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4
                                    class="title-8 color-changer {{ session()->get('direction') == 2 ? 'text-right mt-2' : 'm-0' }}">
                                    {{ $category->name }} ({{ $check_cat_count }})
                                </h4>
                                <div class="d-none">
                                    <a href="#" class="btn-category">{{ trans('labels.view_all') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="pro-theme-8 pro-8 owl-carousel owl-theme position-relative mb-sm-5 mb-4">
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
                                                    $item->item_price > @helper::top_deals($storeinfo->id)->offer_amount
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
                                    <div class="item h-100">
                                        <div class="card h-100 w-100 border overflow-hidden">
                                            @if ($off > 0)
                                                <div class="sale-label-on ltr">{{ $off }}%
                                                    {{ trans('labels.off') }}</div>
                                            @endif
                                            <div class="pro-8-img position-relative">
                                                <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                    @if (@$item['product_image']->image == null)
                                                        <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                            class="card-img-top rounded-2" alt="product image">
                                                    @else
                                                        <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                            class="card-img-top rounded-2" alt="product image">
                                                    @endif
                                                </a>
                                                <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                    @if ($item['multi_image']->count() > 1)
                                                        <img src="{{ @helper::image_path($item['multi_image'][1]->image) }}"
                                                            alt="product image"
                                                            class="w-100 object-fit-cover img-flip">
                                                    @endif
                                                </a>
                                                <ul class="outer-functional">
                                                    <li>
                                                        @if (@helper::checkaddons('customer_login'))
                                                            @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                                <a onclick="managefavorite('{{ $item->id }}',{{ $storeinfo->id }},'{{ helper::storefront_url($storeinfo, '/managefavorite') }}')"
                                                                    class="cursor-pointer {{ session()->get('direction') == 2 ? 'me-auto' : 'ms-auto' }}"
                                                                    title="wishlist">
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
                                                    </li>
                                                    <li>
                                                        <button class="btn p-0 border-0" title="cart"
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

                                                <!-- in-stock -->
                                                @if ($item->stock_management == 1)
                                                    @if (helper::checklowqty($item->id, $storeinfo->id) == 2 && $item->has_variants != 1)
                                                        <div
                                                            class="out-stock m-0 badge bg-white border rounded-pill rounded-0">
                                                            <span class="out-stock-indicator-dot"></span>
                                                            <p class="out-stock-text">
                                                                {{ trans('labels.out_of_stock') }}
                                                            </p>
                                                        </div>
                                                    @else
                                                        <div
                                                            class="in-stock m-0 badge bg-white border rounded-pill rounded-0">
                                                            <span class="in-stock-indicator-dot"></span>
                                                            <p class="in-stock-text">{{ trans('labels.in_stock') }}
                                                            </p>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>

                                            <div class="card-body pb-0">
                                                <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                    <h4 class="title line-2 text-secondary">
                                                        {{ $item->item_name }}
                                                    </h4>
                                                </a>
                                            </div>
                                            <div class="card-footer bg-transparent border-0">
                                                <!-- rating -->
                                                @if (@helper::checkaddons('product_reviews'))
                                                    <div
                                                        class="d-flex align-items-center justify-content-between mb-2 ">
                                                        @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                            <p class="m-0 rating-star cursor-pointer"
                                                                onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')">
                                                                <i class="fa-solid fa-star text-warning"></i>
                                                                <span
                                                                    class="px-1 color-changer">{{ number_format($item->ratings_average, 1) }}</span>
                                                            </p>
                                                        @endif
                                                    </div>
                                                @endif
                                                <!-- price -->
                                                <p class="price color-changer m-0">
                                                    {{ helper::currency_formate($price, $storeinfo->id) }}

                                                    <!-- false-price -->
                                                    @if ($original_price > $price)
                                                        <span class="theme-5-false-price">
                                                            {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                                        </span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
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

<!-- Top-Rated-Items -->
@if (helper::appdata($storeinfo->id)->product_section_display == 2 ||
        helper::appdata($storeinfo->id)->product_section_display == 3)
    @if (count($toprateditems) > 0)
        <section class="my-5">
            <div class="container">
                <div class="sec-header mb-4">
                    <h4 class="main-title-8 color-changer main-title m-0">{{ trans('labels.top_rated_product') }}</h4>
                    <p class="m-0 line-2 fs-15 fw-500 mt-2 text-muted">
                        {{ trans('labels.top_rated_product_subtitle') }}
                    </p>
                </div>
                <div class="pro-theme-8 pro-8 owl-carousel owl-theme position-relative mb-5">
                    @php $i = 0; @endphp
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
                        <div class="item h-100">
                            <div class="card h-100 w-100 border overflow-hidden">
                                @if ($off > 0)
                                    <div class="sale-label-on ltr">{{ $off }}% {{ trans('labels.off') }}
                                    </div>
                                @endif
                                <div class="pro-8-img position-relative">
                                    <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                        @if (@$item['product_image']->image == null)
                                            <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                class="card-img-top rounded-2" alt="product image">
                                        @else
                                            <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                class="card-img-top rounded-2" alt="product image">
                                        @endif
                                    </a>
                                    <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                        @if ($item['multi_image']->count() > 1)
                                            <img src="{{ @helper::image_path($item['multi_image'][1]->image) }}"
                                                alt="product image" class="w-100 object-fit-cover img-flip">
                                        @endif
                                    </a>
                                    <ul class="outer-functional">
                                        <li>
                                            @if (@helper::checkaddons('customer_login'))
                                                @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                    <a onclick="managefavorite('{{ $item->id }}',{{ $storeinfo->id }},'{{ helper::storefront_url($storeinfo, '/managefavorite') }}')"
                                                        class="cursor-pointer {{ session()->get('direction') == 2 ? 'me-auto' : 'ms-auto' }}"
                                                        title="wishlist">
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
                                        </li>
                                        <li>
                                            <button class="btn p-0 border-0" title="cart"
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

                                    <!-- in-stock -->
                                    @if ($item->stock_management == 1)
                                        @if (helper::checklowqty($item->id, $storeinfo->id) == 2 && $item->has_variants != 1)
                                            <div class="out-stock m-0 badge bg-white border rounded-pill rounded-0">
                                                <span class="out-stock-indicator-dot"></span>
                                                <p class="out-stock-text">
                                                    {{ trans('labels.out_of_stock') }}
                                                </p>
                                            </div>
                                        @else
                                            <div class="in-stock m-0 badge bg-white border rounded-pill rounded-0">
                                                <span class="in-stock-indicator-dot"></span>
                                                <p class="in-stock-text">{{ trans('labels.in_stock') }}
                                                </p>
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                <div class="card-body pb-0">
                                    <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                        <h4 class="title line-2 text-secondary">
                                            {{ $item->item_name }}
                                        </h4>
                                    </a>
                                </div>
                                <div class="card-footer bg-transparent border-0">
                                    <!-- rating -->
                                    @if (@helper::checkaddons('product_reviews'))
                                        <div class="d-flex align-items-center justify-content-between mb-2 ">
                                            @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                <p class="m-0 rating-star cursor-pointer"
                                                    onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')">
                                                    <i class="fa-solid fa-star text-warning"></i>
                                                    <span
                                                        class="px-1 color-changer">{{ number_format($item->ratings_average, 1) }}</span>
                                                </p>
                                            @endif
                                        </div>
                                    @endif
                                    <!-- price -->
                                    <p class="price color-changer m-0">
                                        {{ helper::currency_formate($price, $storeinfo->id) }}

                                        <!-- false-price -->
                                        @if ($original_price > $price)
                                            <span class="theme-5-false-price">
                                                {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                            </span>
                                        @endif
                                    </p>
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
        <section class="storereview-sec mb-5 bg-light bg-change-mode py-5">
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
                                            <p class="review_date color-changer fs-7">
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

<!-- blogs -->
@if (helper::getblogs($storeinfo->id)->Count() > 0)
    <section class="blog-6-sec">
        @php
            $blog = helper::getblogs($storeinfo->id);
        @endphp
        <div class="container">
            <div class="sec-header mb-4">
                <h4 class="main-title-8 color-changer main-title m-0">{{ trans('labels.our_latest_blogs') }}</h4>
                <p class="m-0 line-2 fs-15 fw-500 mt-2 text-muted">{{ trans('labels.our_latest_blogs_subtitle') }}
                </p>
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
                            @foreach ($blog as $blog)
                                <div class="item mx-1 h-100">
                                    <div class="card border h-100 overflow-hidden">
                                        <div class="blog-6-img">
                                            <a href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}">
                                                <img src="{{ helper::image_path($blog->image) }}" height="300"
                                                    alt="blog img" class="w-100 object-fit-cover">
                                            </a>
                                            <div class="post-image-hover">
                                                <a href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}"
                                                    class="blog-btn blog-8-2" title="Read More">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h4 class="title line-2">
                                                <a class="color-changer text-dark"
                                                    href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}">{{ $blog->title }}</a>
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
                    <div class="blogs-8 owl-carousel owl-theme overflow-hidden">
                        @foreach ($blog as $blog)
                            <div class="item h-100 mx-1">
                                <div class="card border h-100 overflow-hidden">
                                    <div class="blog-6-img">
                                        <a href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}">
                                            <img src="{{ helper::image_path($blog->image) }}" height="300"
                                                alt="blog img" class="w-100 object-fit-cover">
                                        </a>
                                        <div class="post-image-hover">
                                            <a href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}"
                                                class="blog-btn blog-8-2" title="Read More">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h4 class="title line-2">
                                            <a class="color-changer text-dark"
                                                href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}">{{ $blog->title }}</a>
                                        </h4>
                                        <span class="blog-created text-muted">
                                            <i class="fa-regular fa-calendar-days"></i>
                                            <span class="date">{{ helper::date_format($blog->created_at) }}</span>
                                        </span>
                                        <div class="description text-muted line-2">{!! Str::limit($blog->description, 200) !!}</div>
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
