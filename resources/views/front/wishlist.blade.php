@include('front.theme.header')
<!------ breadcrumb ------>
<section class="breadcrumb-sec bg-change-mode">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-dark"><a class="text-dark color-changer"
                        href="{{ helper::storefront_url($storeinfo, '/') }}">Home</a>
                </li>
                <li class="text-muted breadcrumb-item {{ session()->get('direction') == 2 ? 'rtl' : '' }} active"
                    aria-current="page">{{ trans('labels.wishlist') }}</li>
            </ol>
        </nav>
    </div>
</section>
<section class="product-prev-sec product-list-sec">
    <div class="container">
        <div class="user-bg-color mb-5">
            <div class="container">
                <div class="row">
                    @include('front.theme.sidebar')
                    <div class="col-xl-9 col-lg-8 col-xxl-9 col-12">
                        <div class="card-v p-0 border rounded user-form">
                            <div class="settings-box">
                                <div class="settings-box-header border-bottom px-4 py-3">
                                    <h5 class="mb-0 color-changer"><i class="fa-regular fa-heart"></i><span
                                            class="px-2">{{ trans('labels.wishlist') }}</span></h5>
                                </div>
                                @if ($getitem->count() > 0)
                                    <div class="p-3">
                                        @if (helper::appdata($storeinfo->id)->template == 1)
                                            <div
                                                class="row recipe-card row-cols-xl-4 row-cols-lg-3 row-cols-md-3 row-cols-sm-2 row-cols-1 g-3">
                                                @foreach ($getitem as $key => $item)
                                                    <div class="col custom-product-column">
                                                        <div class="pro-box">
                                                            <div class="pro-img ">
                                                                <a
                                                                    href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                    @if (@$item['product_image']->image == null)
                                                                        <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                                            alt="product image">
                                                                    @else
                                                                        <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                                            alt="product image">
                                                                    @endif
                                                                </a>
                                                                @php
                                                                    if (
                                                                        $item->top_deals == 1 &&
                                                                        helper::top_deals($storeinfo->id) != null
                                                                    ) {
                                                                        if (
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_type == 1
                                                                        ) {
                                                                            if ($item['variation']->count() > 0) {
                                                                                if (
                                                                                    $item['variation'][0]->price >
                                                                                    @helper::top_deals($storeinfo->id)
                                                                                        ->offer_amount
                                                                                ) {
                                                                                    $price =
                                                                                        $item['variation'][0]->price -
                                                                                        @helper::top_deals(
                                                                                            $storeinfo->id,
                                                                                        )->offer_amount;
                                                                                } else {
                                                                                    $price =
                                                                                        $item['variation'][0]->price;
                                                                                }
                                                                            } else {
                                                                                if (
                                                                                    $item->item_price >
                                                                                    @helper::top_deals($storeinfo->id)
                                                                                        ->offer_amount
                                                                                ) {
                                                                                    $price =
                                                                                        $item->item_price -
                                                                                        @helper::top_deals(
                                                                                            $storeinfo->id,
                                                                                        )->offer_amount;
                                                                                } else {
                                                                                    $price = $item->item_price;
                                                                                }
                                                                            }
                                                                        } else {
                                                                            if ($item['variation']->count() > 0) {
                                                                                $price =
                                                                                    $item['variation'][0]->price -
                                                                                    $item['variation'][0]->price *
                                                                                        (@helper::top_deals(
                                                                                            $storeinfo->id,
                                                                                        )->offer_amount /
                                                                                            100);
                                                                            } else {
                                                                                $price =
                                                                                    $item->item_price -
                                                                                    $item->item_price *
                                                                                        (@helper::top_deals(
                                                                                            $storeinfo->id,
                                                                                        )->offer_amount /
                                                                                            100);
                                                                            }
                                                                        }
                                                                        if ($item['variation']->count() > 0) {
                                                                            $original_price =
                                                                                $item['variation'][0]->price;
                                                                        } else {
                                                                            $original_price = $item->item_price;
                                                                        }
                                                                        $off =
                                                                            $original_price > 0
                                                                                ? number_format(
                                                                                    100 -
                                                                                        ($price * 100) /
                                                                                            $original_price,
                                                                                    1,
                                                                                )
                                                                                : 0;
                                                                    } else {
                                                                        $price = $item->item_price;
                                                                        $original_price = $item->original_price;
                                                                        if ($item['variation']->count() > 0) {
                                                                            $price = $item['variation'][0]->price;
                                                                            $original_price =
                                                                                $item['variation'][0]->original_price;
                                                                        } else {
                                                                            $price = $item->item_price;
                                                                            $original_price =
                                                                                $item->item_original_price;
                                                                        }
                                                                        $off =
                                                                            $original_price > 0
                                                                                ? number_format(
                                                                                    100 -
                                                                                        ($price * 100) /
                                                                                            $original_price,
                                                                                    1,
                                                                                )
                                                                                : 0;
                                                                    }
                                                                @endphp

                                                                <div class="sale-heart">
                                                                    @if ($off > 0)
                                                                        <div class="sale-label-on">{{ $off }}%
                                                                            {{ trans('labels.off') }}</div>
                                                                    @endif
                                                                    @if (@helper::checkaddons('customer_login'))
                                                                        @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                                            <a onclick="managefavorite('{{ $item->id }}',{{ $storeinfo->id }},'{{ helper::storefront_url($storeinfo, '/managefavorite') }}')"
                                                                                class="btn-sm btn-Wishlist cursor-pointer {{ session()->get('direction') == 2 ? 'me-auto' : 'ms-auto' }}">
                                                                                @if (Auth::user() && Auth::user()->type == 3)
                                                                                    @php

                                                                                        $favorite = helper::ceckfavorite(
                                                                                            $item->id,
                                                                                            $storeinfo->id,
                                                                                            Auth::user()->id,
                                                                                        );

                                                                                    @endphp
                                                                                    @if (!empty($favorite) && $favorite->count() > 0)
                                                                                        <i
                                                                                            class="fa-solid fa-heart"></i>
                                                                                    @else
                                                                                        <i
                                                                                            class="fa-regular fa-heart"></i>
                                                                                    @endif
                                                                                @else
                                                                                    <i class="fa-regular fa-heart"></i>
                                                                                @endif
                                                                            </a>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="product-details-wrap">
                                                                <div class="product-details-inner  mb-2 line-2">
                                                                    <a
                                                                        href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                        <h4 id="itemname"
                                                                            class="color-changer text-dark {{ session()->get('direction') == 2 ? 'text-right' : '' }}">
                                                                            {{ $item->item_name }}</h4>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="pro-footer">
                                                                <div class="d-flex justify-content-between">
                                                                    @if (@helper::checkaddons('product_reviews'))
                                                                        @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                                            <p class="m-0 rating-star d-inline cursor-pointer"
                                                                                onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')">
                                                                                <i
                                                                                    class="fa-solid fa-star text-warning"></i>
                                                                                <span
                                                                                    class="px-1 color-changer">{{ number_format($item->ratings_average, 1) }}</span>
                                                                            </p>
                                                                        @endif
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
                                                                <div
                                                                    class="d-flex align-items-baseline flex-wrap gap-1">
                                                                    <p class="pro-pricing color-changer line-1">
                                                                        {{ helper::currency_formate($price, $storeinfo->id) }}
                                                                    </p>
                                                                    @if ($original_price > $price)
                                                                        <p class="pro-pricing pro-org-value line-1 m-0">
                                                                            {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                                                        </p>
                                                                    @endif
                                                                </div>
                                                                <button
                                                                    class="btn btn-sm m-0 py-1 w-100 btn-content rounded-5"
                                                                    id="verifybtn{{ $key }}_{{ $item->id }}"
                                                                    onclick="GetProductOverview('{{ $item->slug }}',this.id)">{{ helper::appdata($storeinfo->id)->online_order == 1 ? trans('labels.add_to_cart') : trans('labels.view') }}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif (helper::appdata($storeinfo->id)->template == 2)
                                            <div
                                                class="row row-cols-xl-4 row-cols-lg-3 row-cols-md-3 row-cols-sm-2 row-cols-1 g-3 recipe-card">
                                                @foreach ($getitem as $key => $item)
                                                    @php
                                                        if (
                                                            $item->top_deals == 1 &&
                                                            helper::top_deals($storeinfo->id) != null
                                                        ) {
                                                            if (@helper::top_deals($storeinfo->id)->offer_type == 1) {
                                                                if ($item['variation']->count() > 0) {
                                                                    if (
                                                                        $item['variation'][0]->price >
                                                                        @helper::top_deals($storeinfo->id)->offer_amount
                                                                    ) {
                                                                        $price =
                                                                            $item['variation'][0]->price -
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount;
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
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount;
                                                                    } else {
                                                                        $price = $item->item_price;
                                                                    }
                                                                }
                                                            } else {
                                                                if ($item['variation']->count() > 0) {
                                                                    $price =
                                                                        $item['variation'][0]->price -
                                                                        $item['variation'][0]->price *
                                                                            (@helper::top_deals($storeinfo->id)
                                                                                ->offer_amount /
                                                                                100);
                                                                } else {
                                                                    $price =
                                                                        $item->item_price -
                                                                        $item->item_price *
                                                                            (@helper::top_deals($storeinfo->id)
                                                                                ->offer_amount /
                                                                                100);
                                                                }
                                                            }
                                                            if ($item['variation']->count() > 0) {
                                                                $original_price = $item['variation'][0]->price;
                                                            } else {
                                                                $original_price = $item->item_price;
                                                            }
                                                            $off =
                                                                $original_price > 0
                                                                    ? number_format(
                                                                        100 - ($price * 100) / $original_price,
                                                                        1,
                                                                    )
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
                                                                    ? number_format(
                                                                        100 - ($price * 100) / $original_price,
                                                                        1,
                                                                    )
                                                                    : 0;
                                                        }
                                                    @endphp
                                                    <div class="col">
                                                        <div class="card p-2 border rounded-0 custom-product-column h-100"
                                                            id="pro-box">
                                                            <a
                                                                href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                <div class="image">
                                                                    @if (@$item['product_image']->image == null)
                                                                        <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                                            class="cursor-pointer object w-100 theme-2-img"
                                                                            alt="product image">
                                                                    @else
                                                                        <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                                            class="cursor-pointer object w-100 theme-2-img"
                                                                            alt="product image">
                                                                    @endif
                                                                </div>
                                                            </a>
                                                            @if ($off > 0)
                                                                <div class="sale-heart">
                                                                    <div class="sale-label-on rounded-0">
                                                                        {{ $off }}%
                                                                        {{ trans('labels.off') }}
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div class="card-body pb-2 rounded-0 px-0">
                                                                <a
                                                                    href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                    <h6 id="itemname"
                                                                        class="fw-600 color-changer text-dark line-2 {{ session()->get('direction') == 2 ? 'text-right' : '' }} cursor-pointer">
                                                                        {{ $item->item_name }}
                                                                    </h6>
                                                                </a>
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center">
                                                                    @if (@helper::checkaddons('product_reviews'))
                                                                        @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                                            <span
                                                                                class="rating-star d-inline d-flex align-items-center cursor-pointer"
                                                                                onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')"
                                                                                aria-controls="offcanvasRight">
                                                                                <i
                                                                                    class="fa-solid fa-star text-warning"></i>
                                                                                <span
                                                                                    class="px-1 color-changer">{{ number_format($item->ratings_average, 1) }}</span>
                                                                            </span>
                                                                        @endif
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
                                                                                    {{ trans('labels.in_stock') }}
                                                                                </p>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                                @if ($item->stock_management == 1)
                                                                    @if (helper::checklowqty($item->id, $storeinfo->id) == 2 && $item->has_variants != 1)
                                                                        <div class="item-stock text-center rounded-0">
                                                                            <span
                                                                                class="bg-danger p-1 px-2 fs-8 rounded-1 text-white border border-white">
                                                                                {{ trans('labels.out_of_stock') }}
                                                                            </span>
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                            <div class="card-footer p-0 bg-transparent border-0">
                                                                <div
                                                                    class="d-flex align-items-center gap-2 justify-content-between w-100 mb-2">
                                                                    <div
                                                                        class="d-flex flex-wrap gap-1 align-items-center">
                                                                        <p class="card-text pro-value">
                                                                            {{ helper::currency_formate($price, $storeinfo->id) }}
                                                                        </p>
                                                                        @if ($original_price > $price)
                                                                            <del class="card-text pro-org-value m-0">
                                                                                {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                                                            </del>
                                                                        @endif
                                                                    </div>
                                                                    @if (@helper::checkaddons('customer_login'))
                                                                        @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                                            <a onclick="managefavorite('{{ $item->id }}',{{ $storeinfo->id }},'{{ helper::storefront_url($storeinfo, '/managefavorite') }}')"
                                                                                class="btn-sm btn-Wishlist2 cursor-pointer">
                                                                                @if (Auth::user() && Auth::user()->type == 3)
                                                                                    @php

                                                                                        $favorite = helper::ceckfavorite(
                                                                                            $item->id,
                                                                                            $storeinfo->id,
                                                                                            Auth::user()->id,
                                                                                        );

                                                                                    @endphp
                                                                                    @if (!empty($favorite) && $favorite->count() > 0)
                                                                                        <i
                                                                                            class="fa-solid fa-heart"></i>
                                                                                    @else
                                                                                        <i
                                                                                            class="fa-regular fa-heart"></i>
                                                                                    @endif
                                                                                @else
                                                                                    <i class="fa-regular fa-heart"></i>
                                                                                @endif
                                                                            </a>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                                <button
                                                                    class="btn btn-sm m-0 py-1 btn-content col-12 rounded-0 btn-100"
                                                                    id="verifybtn{{ $key }}_{{ $item->id }}"
                                                                    onclick="GetProductOverview('{{ $item->slug }}',this.id)">
                                                                    {{ helper::appdata($storeinfo->id)->online_order == 1 ? trans('labels.add_to_cart') : trans('labels.view') }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif (helper::appdata($storeinfo->id)->template == 3)
                                            <div
                                                class="row row-cols-xl-4 row-cols-lg-3 row-cols-md-3 row-cols-sm-2 row-cols-1 g-3 recipe-card">
                                                @foreach ($getitem as $key => $item)
                                                    @php
                                                        if (
                                                            $item->top_deals == 1 &&
                                                            helper::top_deals($storeinfo->id) != null
                                                        ) {
                                                            if (@helper::top_deals($storeinfo->id)->offer_type == 1) {
                                                                if ($item['variation']->count() > 0) {
                                                                    if (
                                                                        $item['variation'][0]->price >
                                                                        @helper::top_deals($storeinfo->id)->offer_amount
                                                                    ) {
                                                                        $price =
                                                                            $item['variation'][0]->price -
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount;
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
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount;
                                                                    } else {
                                                                        $price = $item->item_price;
                                                                    }
                                                                }
                                                            } else {
                                                                if ($item['variation']->count() > 0) {
                                                                    $price =
                                                                        $item['variation'][0]->price -
                                                                        $item['variation'][0]->price *
                                                                            (@helper::top_deals($storeinfo->id)
                                                                                ->offer_amount /
                                                                                100);
                                                                } else {
                                                                    $price =
                                                                        $item->item_price -
                                                                        $item->item_price *
                                                                            (@helper::top_deals($storeinfo->id)
                                                                                ->offer_amount /
                                                                                100);
                                                                }
                                                            }
                                                            if ($item['variation']->count() > 0) {
                                                                $original_price = $item['variation'][0]->price;
                                                            } else {
                                                                $original_price = $item->item_price;
                                                            }
                                                            $off =
                                                                $original_price > 0
                                                                    ? number_format(
                                                                        100 - ($price * 100) / $original_price,
                                                                        1,
                                                                    )
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
                                                                    ? number_format(
                                                                        100 - ($price * 100) / $original_price,
                                                                        1,
                                                                    )
                                                                    : 0;
                                                        }
                                                    @endphp
                                                    <div class="col">
                                                        <div class="product-card-box card h-100">
                                                            <div class="product-details-wrap">
                                                                <div class="content-side p-0">
                                                                    <a
                                                                        href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                        <div class="border rounded overflow-hidden">
                                                                            @if (@$item['product_image']->image == null)
                                                                                <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                                                    class="cursor-pointer theme-3-img"
                                                                                    alt="product image">
                                                                            @else
                                                                                <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                                                    class="cursor-pointer theme-3-img"
                                                                                    alt="product image">
                                                                            @endif
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                                <div class="card-body content-side w-100 p-3 px-0">
                                                                    @if ($off > 0)
                                                                        <div
                                                                            class="sale-label-on rounded-1 position-static res-bg">
                                                                            {{ $off }}%
                                                                            {{ trans('labels.off') }}
                                                                        </div>
                                                                    @endif

                                                                    <a
                                                                        href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                        <h4 id="itemname"
                                                                            class="line-2 color-changer text-dark fs-7 {{ session()->get('direction') == 2 ? 'text-right' : '' }}">
                                                                            {{ $item->item_name }}
                                                                        </h4>
                                                                    </a>
                                                                    <div
                                                                        class="d-flex justify-content-between gap-2 mb-1">
                                                                        @if (@helper::checkaddons('product_reviews'))
                                                                            @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                                                <p class="m-0 rating-star gap-1 d-inline cursor-pointer"
                                                                                    onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')">
                                                                                    <i
                                                                                        class="fa-solid fa-star text-warning"></i>
                                                                                    <span
                                                                                        class="color-changer">{{ number_format($item->ratings_average, 1) }}</span>
                                                                                </p>
                                                                            @endif
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
                                                                                        {{ trans('labels.in_stock') }}
                                                                                    </p>
                                                                                </div>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center gap-2">
                                                                        <div
                                                                            class="d-flex flex-wrap align-items-center">
                                                                            <div>
                                                                                <p class="pro-pricing m-0">
                                                                                    {{ helper::currency_formate($price, $storeinfo->id) }}
                                                                                </p>
                                                                            </div>
                                                                            @if ($original_price > $price)
                                                                                <p class="text-muted pro-org-value">
                                                                                    {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                                                                </p>
                                                                            @endif
                                                                        </div>
                                                                        @if (@helper::checkaddons('customer_login'))
                                                                            @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                                                <a onclick="managefavorite('{{ $item->id }}',{{ $storeinfo->id }},'{{ helper::storefront_url($storeinfo, '/managefavorite') }}')"
                                                                                    class="btn-sm btn-Wishlist3 cursor-pointer col-auto">
                                                                                    @if (Auth::user() && Auth::user()->type == 3)
                                                                                        @php

                                                                                            $favorite = helper::ceckfavorite(
                                                                                                $item->id,
                                                                                                $storeinfo->id,
                                                                                                Auth::user()->id,
                                                                                            );

                                                                                        @endphp
                                                                                        @if (!empty($favorite) && $favorite->count() > 0)
                                                                                            <i
                                                                                                class="fa-solid fa-heart"></i>
                                                                                        @else
                                                                                            <i
                                                                                                class="fa-regular fa-heart"></i>
                                                                                        @endif
                                                                                    @else
                                                                                        <i
                                                                                            class="fa-regular fa-heart"></i>
                                                                                    @endif
                                                                                </a>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                @if ($item->stock_management == 1)
                                                                    @if (helper::checklowqty($item->id, $storeinfo->id) == 2 && $item->has_variants != 1)
                                                                        <div class="item-stock text-center"><span
                                                                                class="bg-danger p-1 px-2 fs-8 rounded-1 text-white border border-white">{{ trans('labels.out_of_stock') }}</span>
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                                <div class="card-footer p-0 bg-transparent border-0">
                                                                    <button
                                                                        class="btn btn-sm m-0 py-1 w-100 p-0 btn-content"
                                                                        id="verifybtn3{{ $key }}_{{ $item->id }}"
                                                                        onclick="GetProductOverview('{{ $item->slug }}',this.id)">
                                                                        {{ helper::appdata($storeinfo->id)->online_order == 1 ? trans('labels.add_to_cart') : trans('labels.view') }}
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif (helper::appdata($storeinfo->id)->template == 4)
                                            <div
                                                class="row row-cols-xl-3 row-cols-lg-2 row-cols-md-2 row-cols-sm-2 row-cols-1 g-3 recipe-card">
                                                @foreach ($getitem as $key => $item)
                                                    @php
                                                        if (
                                                            $item->top_deals == 1 &&
                                                            helper::top_deals($storeinfo->id) != null
                                                        ) {
                                                            if (@helper::top_deals($storeinfo->id)->offer_type == 1) {
                                                                if ($item['variation']->count() > 0) {
                                                                    if (
                                                                        $item['variation'][0]->price >
                                                                        @helper::top_deals($storeinfo->id)->offer_amount
                                                                    ) {
                                                                        $price =
                                                                            $item['variation'][0]->price -
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount;
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
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount;
                                                                    } else {
                                                                        $price = $item->item_price;
                                                                    }
                                                                }
                                                            } else {
                                                                if ($item['variation']->count() > 0) {
                                                                    $price =
                                                                        $item['variation'][0]->price -
                                                                        $item['variation'][0]->price *
                                                                            (@helper::top_deals($storeinfo->id)
                                                                                ->offer_amount /
                                                                                100);
                                                                } else {
                                                                    $price =
                                                                        $item->item_price -
                                                                        $item->item_price *
                                                                            (@helper::top_deals($storeinfo->id)
                                                                                ->offer_amount /
                                                                                100);
                                                                }
                                                            }
                                                            if ($item['variation']->count() > 0) {
                                                                $original_price = $item['variation'][0]->price;
                                                            } else {
                                                                $original_price = $item->item_price;
                                                            }
                                                            $off =
                                                                $original_price > 0
                                                                    ? number_format(
                                                                        100 - ($price * 100) / $original_price,
                                                                        1,
                                                                    )
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
                                                                    ? number_format(
                                                                        100 - ($price * 100) / $original_price,
                                                                        1,
                                                                    )
                                                                    : 0;
                                                        }
                                                    @endphp
                                                    <div class="col">
                                                        <div class="card pro-card h-100">
                                                            <div class="sale-heart">
                                                                @if ($off > 0)
                                                                    <div class="sale-label-on rounded-1">
                                                                        {{ $off }}%
                                                                        {{ trans('labels.off') }}
                                                                    </div>
                                                                @endif
                                                                @if (@helper::checkaddons('customer_login'))
                                                                    @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                                        <a onclick="managefavorite('{{ $item->id }}',{{ $storeinfo->id }},'{{ helper::storefront_url($storeinfo, '/managefavorite') }}')"
                                                                            class="btn-sm btn-Wishlist cursor-pointer {{ session()->get('direction') == 2 ? 'me-auto' : 'ms-auto' }}">
                                                                            @if (Auth::user() && Auth::user()->type == 3)
                                                                                @php

                                                                                    $favorite = helper::ceckfavorite(
                                                                                        $item->id,
                                                                                        $storeinfo->id,
                                                                                        Auth::user()->id,
                                                                                    );

                                                                                @endphp
                                                                                @if (!empty($favorite) && $favorite->count() > 0)
                                                                                    <i class="fa-solid fa-heart "></i>
                                                                                @else
                                                                                    <i class="fa-light fa-heart"></i>
                                                                                @endif
                                                                            @else
                                                                                <i class="fa-light fa-heart"></i>
                                                                            @endif
                                                                        </a>
                                                                    @endif
                                                                @endif
                                                            </div>

                                                            <a
                                                                href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                <div class="rounded-0">
                                                                    <div class="position-relative">
                                                                        @if (@$item['product_image']->image == null)
                                                                            <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                                                class="card-img-top  rounded-0 cursor-pointer"
                                                                                alt="product image">
                                                                        @else
                                                                            <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                                                class="card-img-top  rounded-0 cursor-pointer"
                                                                                alt="product image">
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </a>
                                                            <div class="card-body pro-body">
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
                                                                <a
                                                                    href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                    <div class="h-60px">
                                                                        <h5 class="pro-title cp color-changer text-dark line-2"
                                                                            id="itemname">
                                                                            {{ $item->item_name }}
                                                                        </h5>
                                                                    </div>
                                                                </a>
                                                                @if (@helper::checkaddons('product_reviews'))
                                                                    @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                                        <p class="my-2 rating-star cursor-pointer"
                                                                            onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')">
                                                                            <i
                                                                                class="fa-solid fa-star text-warning"></i>
                                                                            <span
                                                                                class="px-1 color-changer cp">{{ number_format($item->ratings_average, 1) }}</span>
                                                                        </p>
                                                                    @endif
                                                                @endif

                                                                <div
                                                                    class="d-flex align-items-baseline justify-content-center">
                                                                    <div class="d-flex align-items-baseline">
                                                                        <p class="pro-text ">
                                                                            {{ helper::currency_formate($price, $storeinfo->id) }}
                                                                        </p>
                                                                        @if ($original_price > $price)
                                                                            <p
                                                                                class="pro-text pro-org-value text-muted">
                                                                                {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="d-inline-block">
                                                                <button
                                                                    class="btn color-changer hide-cart-btn w-100 m-0"
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
                                                @endforeach
                                            </div>
                                        @elseif (helper::appdata($storeinfo->id)->template == 5)
                                            <div
                                                class="row row-cols-xl-3 row-cols-lg-2 row-cols-md-2 row-cols-sm-2 row-cols-1 g-3">
                                                @foreach ($getitem as $key => $item)
                                                    @php
                                                        if (
                                                            $item->top_deals == 1 &&
                                                            helper::top_deals($storeinfo->id) != null
                                                        ) {
                                                            if (@helper::top_deals($storeinfo->id)->offer_type == 1) {
                                                                if ($item['variation']->count() > 0) {
                                                                    if (
                                                                        $item['variation'][0]->price >
                                                                        @helper::top_deals($storeinfo->id)->offer_amount
                                                                    ) {
                                                                        $price =
                                                                            $item['variation'][0]->price -
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount;
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
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount;
                                                                    } else {
                                                                        $price = $item->item_price;
                                                                    }
                                                                }
                                                            } else {
                                                                if ($item['variation']->count() > 0) {
                                                                    $price =
                                                                        $item['variation'][0]->price -
                                                                        $item['variation'][0]->price *
                                                                            (@helper::top_deals($storeinfo->id)
                                                                                ->offer_amount /
                                                                                100);
                                                                } else {
                                                                    $price =
                                                                        $item->item_price -
                                                                        $item->item_price *
                                                                            (@helper::top_deals($storeinfo->id)
                                                                                ->offer_amount /
                                                                                100);
                                                                }
                                                            }
                                                            if ($item['variation']->count() > 0) {
                                                                $original_price = $item['variation'][0]->price;
                                                            } else {
                                                                $original_price = $item->item_price;
                                                            }
                                                            $off =
                                                                $original_price > 0
                                                                    ? number_format(
                                                                        100 - ($price * 100) / $original_price,
                                                                        1,
                                                                    )
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
                                                                    ? number_format(
                                                                        100 - ($price * 100) / $original_price,
                                                                        1,
                                                                    )
                                                                    : 0;
                                                        }
                                                    @endphp
                                                    <div class="col them-5-card">
                                                        <div class="card h-100 w-100 product-card">
                                                            <div class="sale-heart">
                                                                @if ($off > 0)
                                                                    <div class="sale-label-on rounded-1">
                                                                        {{ $off }}%
                                                                        {{ trans('labels.off') }}
                                                                    </div>
                                                                @endif
                                                                @if (@helper::checkaddons('customer_login'))
                                                                    @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                                        <a onclick="managefavorite('{{ $item->id }}',{{ $storeinfo->id }},'{{ helper::storefront_url($storeinfo, '/managefavorite') }}')"
                                                                            class="btn-sm btn-Wishlist cursor-pointer {{ session()->get('direction') == 2 ? 'me-auto' : 'ms-auto' }}">
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
                                                                                    <i class="fa-light fa-heart"></i>
                                                                                @endif
                                                                            @else
                                                                                <i class="fa-light fa-heart"></i>
                                                                            @endif
                                                                        </a>
                                                                    @endif
                                                                @endif
                                                            </div>

                                                            <div class="them-5img d-flex justify-content-center">
                                                                <div class="testing-card">
                                                                    <a
                                                                        href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                        @if (@$item['product_image']->image == null)
                                                                            <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                                                class="w-100 h-100 object-fit-cover rounded-2"
                                                                                alt="product image">
                                                                        @else
                                                                            <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                                                class="w-100 h-100 object-fit-cover rounded-2"
                                                                                alt="product image">
                                                                        @endif
                                                                    </a>
                                                                </div>
                                                            </div>

                                                            <div class="card-body them-5-card-body">
                                                                <a
                                                                    href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                    <div>
                                                                        <h4
                                                                            class="them-5-card-title color-changer text-dark mt-3 mb-2">
                                                                            {{ $item->item_name }}
                                                                        </h4>
                                                                    </div>
                                                                </a>
                                                                <div
                                                                    class="d-flex flex-wrap gap-2 justify-content-between align-items-center my-2">
                                                                    @if (@helper::checkaddons('product_reviews'))
                                                                        @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                                            <p class="rating-star cursor-pointer cursor-pointer mb-0"
                                                                                onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')">
                                                                                <i
                                                                                    class="fa-solid fa-star text-warning"></i>
                                                                                <span
                                                                                    class="px-1 color-changer">{{ number_format($item->ratings_average, 1) }}</span>
                                                                            </p>
                                                                        @endif
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

                                                                <div class="d-flex justify-content-between">
                                                                    <div class="d-flex align-items-baseline">
                                                                        <p class="price color-changer m-0">
                                                                            {{ helper::currency_formate($price, $storeinfo->id) }}
                                                                        </p>
                                                                        @if ($original_price > $price)
                                                                            <p class="theme-5-false-price">
                                                                                {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <div
                                                                    class="card-footer border-0 bg-transparent px-0 pb-0">
                                                                    <button type="button"
                                                                        id="verifybtn{{ $key }}_{{ $item->id }}"
                                                                        class="btn-outline-dark them-5-btn-hover w-100 btn-sm rounded-1 p-1 m-0"
                                                                        onclick="GetProductOverview('{{ $item->slug }}',this.id)">{{ helper::appdata($storeinfo->id)->online_order == 1 ? trans('labels.add_to_cart') : trans('labels.view') }}</button>
                                                                </div>
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
                                                @endforeach
                                            </div>
                                        @elseif (helper::appdata($storeinfo->id)->template == 6)
                                            <div
                                                class="products-6 row row-cols-xl-4 row-cols-lg-3 row-cols-md-3 row-cols-sm-2 row-cols-1 g-3">
                                                @php $i = 0; @endphp
                                                @foreach ($getitem as $key => $item)
                                                    @php
                                                        if (
                                                            $item->top_deals == 1 &&
                                                            helper::top_deals($storeinfo->id) != null
                                                        ) {
                                                            if (@helper::top_deals($storeinfo->id)->offer_type == 1) {
                                                                if ($item['variation']->count() > 0) {
                                                                    if (
                                                                        $item['variation'][0]->price >
                                                                        @helper::top_deals($storeinfo->id)->offer_amount
                                                                    ) {
                                                                        $price =
                                                                            $item['variation'][0]->price -
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount;
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
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount;
                                                                    } else {
                                                                        $price = $item->item_price;
                                                                    }
                                                                }
                                                            } else {
                                                                if ($item['variation']->count() > 0) {
                                                                    $price =
                                                                        $item['variation'][0]->price -
                                                                        $item['variation'][0]->price *
                                                                            (@helper::top_deals($storeinfo->id)
                                                                                ->offer_amount /
                                                                                100);
                                                                } else {
                                                                    $price =
                                                                        $item->item_price -
                                                                        $item->item_price *
                                                                            (@helper::top_deals($storeinfo->id)
                                                                                ->offer_amount /
                                                                                100);
                                                                }
                                                            }
                                                            if ($item['variation']->count() > 0) {
                                                                $original_price = $item['variation'][0]->price;
                                                            } else {
                                                                $original_price = $item->item_price;
                                                            }
                                                            $off =
                                                                $original_price > 0
                                                                    ? number_format(
                                                                        100 - ($price * 100) / $original_price,
                                                                        1,
                                                                    )
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
                                                                    ? number_format(
                                                                        100 - ($price * 100) / $original_price,
                                                                        1,
                                                                    )
                                                                    : 0;
                                                        }
                                                    @endphp
                                                    <div class="col pro-6 sal-padding">
                                                        <div class="card product-grid-item">
                                                            <div class="sale-heart">
                                                                @if ($off > 0)
                                                                    <div class="sale-label-on">{{ $off }}%
                                                                        {{ trans('labels.off') }}
                                                                    </div>
                                                                @endif
                                                                @if (@helper::checkaddons('customer_login'))
                                                                    @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                                        <div
                                                                            class="pro-like {{ session()->get('direction') == 2 ? 'me-auto' : 'ms-auto' }}">
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
                                                                                        <i
                                                                                            class="fa-solid fa-heart text-white"></i>
                                                                                    @else
                                                                                        <i
                                                                                            class="fa-regular fa-heart"></i>
                                                                                    @endif
                                                                                @else
                                                                                    <i class="fa-regular fa-heart"></i>
                                                                                @endif
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                            <div class="pro-6-img">
                                                                <a
                                                                    href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                    @if (@$item['product_image']->image == null)
                                                                        <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                                            class="card-img-top rounded-0"
                                                                            alt="product image">
                                                                    @else
                                                                        <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                                            class="card-img-top rounded-0"
                                                                            alt="product image">
                                                                    @endif
                                                                </a>
                                                            </div>

                                                            <div class="card-body pb-0">
                                                                <a
                                                                    href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                    <h4
                                                                        class="pro-6-title color-changer text-dark line-2">
                                                                        {{ $item->item_name }}
                                                                    </h4>
                                                                </a>
                                                            </div>
                                                            <div class="card-footer">
                                                                @if (@helper::checkaddons('product_reviews'))
                                                                    @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                                        <p class="mb-2 rating-star cursor-pointer cursor-pointer"
                                                                            onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')">
                                                                            <i
                                                                                class="fa-solid fa-star text-warning"></i>
                                                                            <span
                                                                                class="px-1 color-changer">{{ number_format($item->ratings_average, 1) }}</span>
                                                                        </p>
                                                                    @endif
                                                                @endif

                                                                <div class="d-flex justify-content-between ">
                                                                    <div class="d-flex">
                                                                        <p class="price color-changer m-0">
                                                                            {{ helper::currency_formate($price, $storeinfo->id) }}
                                                                        </p>
                                                                        @if ($original_price > $price)
                                                                            <p class="old-price">
                                                                                {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="d-flex align-items-center justify-content-between">
                                                                    @if ($item->stock_management == 1)
                                                                        @if (helper::checklowqty($item->id, $storeinfo->id) == 2 && $item->has_variants != 1)
                                                                            <div class="out-stock">
                                                                                <span
                                                                                    class="out-stock-indicator-dot"></span>
                                                                                <p class="out-stock-text">
                                                                                    {{ trans('labels.out_of_stock') }}
                                                                                </p>
                                                                            </div>
                                                                        @else
                                                                            <div class="in-stock">
                                                                                <span
                                                                                    class="in-stock-indicator-dot"></span>
                                                                                <p class="in-stock-text">
                                                                                    {{ trans('labels.in_stock') }}
                                                                                </p>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                    <button class="btn btn-cart m-0"
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
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif (helper::appdata($storeinfo->id)->template == 7)
                                            <div
                                                class="row g-3 row-cols-xl-4 row-cols-lg-3 row-cols-md-3 row-cols-sm-2 row-cols-1 pro-7-sec">
                                                @foreach ($getitem as $key => $item)
                                                    @php
                                                        if (
                                                            $item->top_deals == 1 &&
                                                            helper::top_deals($storeinfo->id) != null
                                                        ) {
                                                            if (@helper::top_deals($storeinfo->id)->offer_type == 1) {
                                                                if ($item['variation']->count() > 0) {
                                                                    if (
                                                                        $item['variation'][0]->price >
                                                                        @helper::top_deals($storeinfo->id)->offer_amount
                                                                    ) {
                                                                        $price =
                                                                            $item['variation'][0]->price -
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount;
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
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount;
                                                                    } else {
                                                                        $price = $item->item_price;
                                                                    }
                                                                }
                                                            } else {
                                                                if ($item['variation']->count() > 0) {
                                                                    $price =
                                                                        $item['variation'][0]->price -
                                                                        $item['variation'][0]->price *
                                                                            (@helper::top_deals($storeinfo->id)
                                                                                ->offer_amount /
                                                                                100);
                                                                } else {
                                                                    $price =
                                                                        $item->item_price -
                                                                        $item->item_price *
                                                                            (@helper::top_deals($storeinfo->id)
                                                                                ->offer_amount /
                                                                                100);
                                                                }
                                                            }
                                                            if ($item['variation']->count() > 0) {
                                                                $original_price = $item['variation'][0]->price;
                                                            } else {
                                                                $original_price = $item->item_price;
                                                            }
                                                            $off =
                                                                $original_price > 0
                                                                    ? number_format(
                                                                        100 - ($price * 100) / $original_price,
                                                                        1,
                                                                    )
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
                                                                    ? number_format(
                                                                        100 - ($price * 100) / $original_price,
                                                                        1,
                                                                    )
                                                                    : 0;
                                                        }
                                                    @endphp
                                                    <div class="col pro-7">
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
                                                                    <div class="offer-7 rounded-0 ltr">
                                                                        {{ $off }}%
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
                                                                                            <i
                                                                                                class="fa-solid fa-heart"></i>
                                                                                        @else
                                                                                            <i
                                                                                                class="fa-regular fa-heart"></i>
                                                                                        @endif
                                                                                    @else
                                                                                        <i
                                                                                            class="fa-regular fa-heart"></i>
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
                                                                                <i
                                                                                    class="fa-regular fa-cart-shopping"></i>
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
                                                                            <i
                                                                                class="fa-solid fa-star text-warning"></i>
                                                                            <span
                                                                                class="px-1 color-changer">{{ number_format($item->ratings_average, 1) }}</span>
                                                                        </p>
                                                                    @endif
                                                                @endif

                                                                <a
                                                                    href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                    <h4 id="itemname"
                                                                        class="title color-changer text-dark mb-2 line-2">
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
                                                                            <span
                                                                                class="out-stock-indicator-dot"></span>
                                                                            <p class="out-stock-text">
                                                                                {{ trans('labels.out_of_stock') }}</p>
                                                                        </div>
                                                                    @else
                                                                        <div class="in-stock mt-1">
                                                                            <span
                                                                                class="in-stock-indicator-dot"></span>
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
                                        @elseif (helper::appdata($storeinfo->id)->template == 8)
                                            <div
                                                class="pro-8 position-relative g-3 row row-cols-xl-4 row-cols-lg-3 row-cols-md-3 row-cols-sm-2 row-cols-1">
                                                @php $i = 0; @endphp
                                                @foreach ($getitem as $key => $item)
                                                    @php
                                                        if (
                                                            $item->top_deals == 1 &&
                                                            helper::top_deals($storeinfo->id) != null
                                                        ) {
                                                            if (@helper::top_deals($storeinfo->id)->offer_type == 1) {
                                                                if ($item['variation']->count() > 0) {
                                                                    if (
                                                                        $item['variation'][0]->price >
                                                                        @helper::top_deals($storeinfo->id)->offer_amount
                                                                    ) {
                                                                        $price =
                                                                            $item['variation'][0]->price -
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount;
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
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount;
                                                                    } else {
                                                                        $price = $item->item_price;
                                                                    }
                                                                }
                                                            } else {
                                                                if ($item['variation']->count() > 0) {
                                                                    $price =
                                                                        $item['variation'][0]->price -
                                                                        $item['variation'][0]->price *
                                                                            (@helper::top_deals($storeinfo->id)
                                                                                ->offer_amount /
                                                                                100);
                                                                } else {
                                                                    $price =
                                                                        $item->item_price -
                                                                        $item->item_price *
                                                                            (@helper::top_deals($storeinfo->id)
                                                                                ->offer_amount /
                                                                                100);
                                                                }
                                                            }
                                                            if ($item['variation']->count() > 0) {
                                                                $original_price = $item['variation'][0]->price;
                                                            } else {
                                                                $original_price = $item->item_price;
                                                            }
                                                            $off =
                                                                $original_price > 0
                                                                    ? number_format(
                                                                        100 - ($price * 100) / $original_price,
                                                                        1,
                                                                    )
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
                                                                    ? number_format(
                                                                        100 - ($price * 100) / $original_price,
                                                                        1,
                                                                    )
                                                                    : 0;
                                                        }
                                                    @endphp
                                                    <div class="col">
                                                        <div class="card h-100 w-100 border overflow-hidden">
                                                            @if ($off > 0)
                                                                <div class="sale-label-on ltr">{{ $off }}%
                                                                    {{ trans('labels.off') }}</div>
                                                            @endif
                                                            <div class="pro-8-img position-relative">
                                                                <a
                                                                    href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                    @if (@$item['product_image']->image == null)
                                                                        <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                                            class="card-img-top rounded-2"
                                                                            alt="product image">
                                                                    @else
                                                                        <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                                            class="card-img-top rounded-2"
                                                                            alt="product image">
                                                                    @endif
                                                                </a>
                                                                <a
                                                                    href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
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
                                                                                            <i
                                                                                                class="fa-solid fa-heart"></i>
                                                                                        @else
                                                                                            <i
                                                                                                class="fa-regular fa-heart"></i>
                                                                                        @endif
                                                                                    @else
                                                                                        <i
                                                                                            class="fa-regular fa-heart"></i>
                                                                                    @endif
                                                                                </a>
                                                                            @endif
                                                                        @endif
                                                                    </li>
                                                                    <li>
                                                                        <button class="btn p-0 border-0"
                                                                            title="cart"
                                                                            id="iconverifybtn{{ $key }}_{{ $item->id }}"
                                                                            onclick="GetProductOverview('{{ $item->slug }}',this.id)">
                                                                            @if (helper::appdata($storeinfo->id)->online_order == 1)
                                                                                <i
                                                                                    class="fa-regular fa-cart-shopping"></i>
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
                                                                            <span
                                                                                class="out-stock-indicator-dot"></span>
                                                                            <p class="out-stock-text">
                                                                                {{ trans('labels.out_of_stock') }}
                                                                            </p>
                                                                        </div>
                                                                    @else
                                                                        <div
                                                                            class="in-stock m-0 badge bg-white border rounded-pill rounded-0">
                                                                            <span
                                                                                class="in-stock-indicator-dot"></span>
                                                                            <p class="in-stock-text">
                                                                                {{ trans('labels.in_stock') }}
                                                                            </p>
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            </div>

                                                            <div class="card-body pb-0">
                                                                <a
                                                                    href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
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
                                                                                <i
                                                                                    class="fa-solid fa-star text-warning"></i>
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
                                        @elseif (helper::appdata($storeinfo->id)->template == 9)
                                            <div
                                                class="row row-cols-xl-3 row-cols-lg-2 row-cols-md-2 row-cols-sm-2 row-cols-1 g-3">
                                                @foreach ($getitem as $key => $item)
                                                    @php
                                                        if (
                                                            $item->top_deals == 1 &&
                                                            helper::top_deals($storeinfo->id) != null
                                                        ) {
                                                            if (@helper::top_deals($storeinfo->id)->offer_type == 1) {
                                                                if ($item['variation']->count() > 0) {
                                                                    if (
                                                                        $item['variation'][0]->price >
                                                                        @helper::top_deals($storeinfo->id)->offer_amount
                                                                    ) {
                                                                        $price =
                                                                            $item['variation'][0]->price -
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount;
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
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount;
                                                                    } else {
                                                                        $price = $item->item_price;
                                                                    }
                                                                }
                                                            } else {
                                                                if ($item['variation']->count() > 0) {
                                                                    $price =
                                                                        $item['variation'][0]->price -
                                                                        $item['variation'][0]->price *
                                                                            (@helper::top_deals($storeinfo->id)
                                                                                ->offer_amount /
                                                                                100);
                                                                } else {
                                                                    $price =
                                                                        $item->item_price -
                                                                        $item->item_price *
                                                                            (@helper::top_deals($storeinfo->id)
                                                                                ->offer_amount /
                                                                                100);
                                                                }
                                                            }
                                                            if ($item['variation']->count() > 0) {
                                                                $original_price = $item['variation'][0]->price;
                                                            } else {
                                                                $original_price = $item->item_price;
                                                            }
                                                            $off =
                                                                $original_price > 0
                                                                    ? number_format(
                                                                        100 - ($price * 100) / $original_price,
                                                                        1,
                                                                    )
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
                                                                    ? number_format(
                                                                        100 - ($price * 100) / $original_price,
                                                                        1,
                                                                    )
                                                                    : 0;
                                                        }
                                                    @endphp
                                                    <div class="col">
                                                        <div class="card card-bg pro-9 h-100 bg-transparent">
                                                            <div class="pro-9-img">
                                                                <a
                                                                    href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                    @if (@$item['product_image']->image == null)
                                                                        <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                                            class="card-img-top rounded-2"
                                                                            alt="product image">
                                                                    @else
                                                                        <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                                            class="card-img-top rounded-2"
                                                                            alt="product image">
                                                                    @endif
                                                                </a>
                                                                <!-- wishlist -->
                                                                @if (@helper::checkaddons('customer_login'))
                                                                    @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                                        <div
                                                                            class="wishlist {{ session()->get('direction') == 2 ? 'rtl' : 'ltr' }}">
                                                                            <a onclick="managefavorite('{{ $item->id }}','{{ $storeinfo->id }}','{{ helper::storefront_url($storeinfo, '/managefavorite') }}')"
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
                                                                                        <i
                                                                                            class="fa-solid fa-heart"></i>
                                                                                    @else
                                                                                        <i
                                                                                            class="fa-regular fa-heart"></i>
                                                                                    @endif
                                                                                @else
                                                                                    <i class="fa-regular fa-heart"></i>
                                                                                @endif
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                @endif

                                                                <!-- add to cart -->
                                                                <div class="position-absolute bottom-12 w-100 d-flex">
                                                                    <button class="btn pro-9-add mx-3 hover-none"
                                                                        id="verifybtn{{ $key }}_{{ $item->id }}"
                                                                        title="cart"
                                                                        onclick="GetProductOverview('{{ $item->slug }}',this.id)">{{ helper::appdata($storeinfo->id)->online_order == 1 ? trans('labels.add_to_cart') : trans('labels.view') }}
                                                                    </button>
                                                                </div>

                                                                <!-- stock -->
                                                                @if ($item->stock_management == 1)
                                                                    @if (helper::checklowqty($item->id, $storeinfo->id) == 2 && $item->has_variants != 1)
                                                                        <div
                                                                            class="out-stock m-0 badge bg-white shadow rounded-pill {{ session()->get('direction') == 2 ? 'rtl' : 'ltr' }}">
                                                                            <span
                                                                                class="out-stock-indicator-dot"></span>
                                                                            <p class="out-stock-text">
                                                                                {{ trans('labels.out_of_stock') }}
                                                                            </p>
                                                                        </div>
                                                                    @else
                                                                        <div
                                                                            class="in-stock m-0 badge bg-white shadow rounded-pill {{ session()->get('direction') == 2 ? 'rtl' : 'ltr' }}">
                                                                            <span
                                                                                class="in-stock-indicator-dot"></span>
                                                                            <p class="in-stock-text">
                                                                                {{ trans('labels.in_stock') }}
                                                                            </p>
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            </div>


                                                            <div class="card-body p-2 p-sm-3 pb-0 pb-sm-0">
                                                                <a
                                                                    href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                    <h4 class="title line-2 color-changer text-dark">
                                                                        {{ $item->item_name }}
                                                                    </h4>
                                                                </a>
                                                            </div>
                                                            <div
                                                                class="card-footer bg-transparent border-0 p-2 p-sm-3">
                                                                @if (@helper::checkaddons('product_reviews'))
                                                                    <div
                                                                        class="d-flex alogn-items-center justify-content-between mb-2 ">
                                                                        @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                                            <p class="m-0 rating-star cursor-pointer"
                                                                                onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')">
                                                                                <i
                                                                                    class="fa-solid fa-star text-warning"></i>
                                                                                <span
                                                                                    class="px-1 color-changer">{{ number_format($item->ratings_average, 1) }}</span>
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                                <!-- price -->
                                                                <div
                                                                    class="d-flex flex-wrap gap-1 justify-content-between">
                                                                    <p
                                                                        class="price color-changer m-0 d-flex flex-wrap gap-1 align-items-center">
                                                                        {{ helper::currency_formate($price, $storeinfo->id) }}
                                                                        <!-- old-price -->
                                                                        @if ($original_price > $price)
                                                                            <span class="old-price">
                                                                                {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                                                            </span>
                                                                        @endif
                                                                    </p>
                                                                    @if ($off > 0)
                                                                        <div class="discount ltr fs-7 fw-500">
                                                                            ({{ $off }}%
                                                                            {{ trans('labels.off') }})
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif (helper::appdata($storeinfo->id)->template == 10)
                                            <div
                                                class="row row-cols-xl-4 row-cols-lg-3 row-cols-md-3 row-cols-sm-2 row-cols-1 g-3 theme-10">
                                                @foreach ($getitem as $key => $item)
                                                    @php
                                                        if (
                                                            $item->top_deals == 1 &&
                                                            helper::top_deals($storeinfo->id) != null
                                                        ) {
                                                            if (@helper::top_deals($storeinfo->id)->offer_type == 1) {
                                                                if ($item['variation']->count() > 0) {
                                                                    if (
                                                                        $item['variation'][0]->price >
                                                                        @helper::top_deals($storeinfo->id)->offer_amount
                                                                    ) {
                                                                        $price =
                                                                            $item['variation'][0]->price -
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount;
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
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount;
                                                                    } else {
                                                                        $price = $item->item_price;
                                                                    }
                                                                }
                                                            } else {
                                                                if ($item['variation']->count() > 0) {
                                                                    $price =
                                                                        $item['variation'][0]->price -
                                                                        $item['variation'][0]->price *
                                                                            (@helper::top_deals($storeinfo->id)
                                                                                ->offer_amount /
                                                                                100);
                                                                } else {
                                                                    $price =
                                                                        $item->item_price -
                                                                        $item->item_price *
                                                                            (@helper::top_deals($storeinfo->id)
                                                                                ->offer_amount /
                                                                                100);
                                                                }
                                                            }
                                                            if ($item['variation']->count() > 0) {
                                                                $original_price = $item['variation'][0]->price;
                                                            } else {
                                                                $original_price = $item->item_price;
                                                            }
                                                            $off =
                                                                $original_price > 0
                                                                    ? number_format(
                                                                        100 - ($price * 100) / $original_price,
                                                                        1,
                                                                    )
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
                                                                    ? number_format(
                                                                        100 - ($price * 100) / $original_price,
                                                                        1,
                                                                    )
                                                                    : 0;
                                                        }
                                                    @endphp
                                                    <div class="col">
                                                        <div
                                                            class="card pro-box border h-100 rounded-0 position-relative p-0">
                                                            <div class="pro-img rounded-0">
                                                                <a
                                                                    href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                    @if (@$item['product_image']->image == null)
                                                                        <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                                            class="card-img-top rounded-2"
                                                                            alt="product image">
                                                                    @else
                                                                        <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                                            class="card-img-top rounded-2"
                                                                            alt="product image">
                                                                    @endif
                                                                </a>
                                                            </div>
                                                            @if ($off > 0)
                                                                <div
                                                                    class="ribbon-pop {{ session()->get('direction') == 2 ? 'rtl rounded-start-5' : 'rounded-end-5' }}">
                                                                    {{ $off }}% {{ trans('labels.off') }}
                                                                </div>
                                                            @endif
                                                            <div class="card-body product-details-wrap p-2">
                                                                <div
                                                                    class="icon-cart-hart {{ session()->get('direction') == 2 ? 'rtl' : '' }}">
                                                                    <div class="d-flex gap-2 justify-content-end">
                                                                        <!-- wishlist -->
                                                                        @if (@helper::checkaddons('customer_login'))
                                                                            @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                                                <div
                                                                                    class="wishlist {{ session()->get('direction') == 2 ? 'rtl' : 'ltr' }}">
                                                                                    <a onclick="managefavorite('{{ $item->id }}','{{ $storeinfo->id }}','{{ helper::storefront_url($storeinfo, '/managefavorite') }}')"
                                                                                        title="wishlist"
                                                                                        class="btn-sm btn-Wishlist shadow">
                                                                                        @if (Auth::user() && Auth::user()->type == 3)
                                                                                            @php
                                                                                                $favorite = helper::ceckfavorite(
                                                                                                    $item->id,
                                                                                                    $storeinfo->id,
                                                                                                    Auth::user()->id,
                                                                                                );
                                                                                            @endphp
                                                                                            @if (!empty($favorite) && $favorite->count() > 0)
                                                                                                <i
                                                                                                    class="fa-solid fa-heart"></i>
                                                                                            @else
                                                                                                <i
                                                                                                    class="fa-regular fa-heart"></i>
                                                                                            @endif
                                                                                        @else
                                                                                            <i
                                                                                                class="fa-regular fa-heart"></i>
                                                                                        @endif
                                                                                    </a>
                                                                                </div>
                                                                            @endif
                                                                        @endif
                                                                        <button
                                                                            class="btn m-0 btn-sm shadow btn-product-cart"
                                                                            title="cart"
                                                                            id="iconverifybtn{{ $key }}_{{ $item->id }}"
                                                                            onclick="GetProductOverview('{{ $item->slug }}',this.id)">
                                                                            @if (helper::appdata($storeinfo->id)->online_order == 1)
                                                                                <i
                                                                                    class="fa-regular fa-cart-shopping"></i>
                                                                            @else
                                                                                <i class="fa-regular fa-eye"></i>
                                                                            @endif
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-2 line-2">
                                                                    <a
                                                                        href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                        <h4
                                                                            class="fs-7 fw-600 color-changer text-dark">
                                                                            {{ $item->item_name }}
                                                                        </h4>
                                                                    </a>
                                                                </div>
                                                                <div class="d-flex justify-content-between">
                                                                    <!-- rating -->
                                                                    @if (@helper::checkaddons('product_reviews'))
                                                                        <div
                                                                            class="d-flex align-items-center justify-content-between">
                                                                            @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                                                <p class="m-0 rating-star d-inline cursor-pointer"
                                                                                    onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')">
                                                                                    <i
                                                                                        class="fa-solid fa-star text-warning"></i>
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
                                                                                <span
                                                                                    class="out-stock-indicator-dot"></span>
                                                                                <p class="out-stock-text">
                                                                                    {{ trans('labels.out_of_stock') }}
                                                                                </p>
                                                                            </div>
                                                                        @else
                                                                            <div class="in-stock">
                                                                                <span
                                                                                    class="in-stock-indicator-dot"></span>
                                                                                <p class="in-stock-text">
                                                                                    {{ trans('labels.in_stock') }}
                                                                                </p>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="card-footer p-2 border-0 bg-transparent">
                                                                <div
                                                                    class="d-flex align-items-baseline flex-wrap gap-1">
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
                                        @elseif (helper::appdata($storeinfo->id)->template == 11)
                                            <div
                                                class="row row-cols-xl-4 row-cols-lg-3 row-cols-md-3 row-cols-sm-2 row-cols-1 recipe-card g-3 theme-11-card">
                                                @foreach ($getitem as $key => $item)
                                                    @php
                                                        if (
                                                            $item->top_deals == 1 &&
                                                            helper::top_deals($storeinfo->id) != null
                                                        ) {
                                                            if (@helper::top_deals($storeinfo->id)->offer_type == 1) {
                                                                if ($item['variation']->count() > 0) {
                                                                    if (
                                                                        $item['variation'][0]->price >
                                                                        @helper::top_deals($storeinfo->id)->offer_amount
                                                                    ) {
                                                                        $price =
                                                                            $item['variation'][0]->price -
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount;
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
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount;
                                                                    } else {
                                                                        $price = $item->item_price;
                                                                    }
                                                                }
                                                            } else {
                                                                if ($item['variation']->count() > 0) {
                                                                    $price =
                                                                        $item['variation'][0]->price -
                                                                        $item['variation'][0]->price *
                                                                            (@helper::top_deals($storeinfo->id)
                                                                                ->offer_amount /
                                                                                100);
                                                                } else {
                                                                    $price =
                                                                        $item->item_price -
                                                                        $item->item_price *
                                                                            (@helper::top_deals($storeinfo->id)
                                                                                ->offer_amount /
                                                                                100);
                                                                }
                                                            }
                                                            if ($item['variation']->count() > 0) {
                                                                $original_price = $item['variation'][0]->price;
                                                            } else {
                                                                $original_price = $item->item_price;
                                                            }
                                                            $off =
                                                                $original_price > 0
                                                                    ? number_format(
                                                                        100 - ($price * 100) / $original_price,
                                                                        1,
                                                                    )
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
                                                                    ? number_format(
                                                                        100 - ($price * 100) / $original_price,
                                                                        1,
                                                                    )
                                                                    : 0;
                                                        }
                                                    @endphp
                                                    <div class="col">
                                                        <div class="pro-box h-100 rounded p-0">
                                                            <div class="pro-img rounded-0">
                                                                <a
                                                                    href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                    @if (@$item['product_image']->image == null)
                                                                        <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                                            class="card-img-top rounded-2 h-100"
                                                                            alt="product image">
                                                                    @else
                                                                        <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                                            class="card-img-top rounded-2 h-100"
                                                                            alt="product image">
                                                                    @endif
                                                                </a>
                                                                <div
                                                                    class="sale-heart flex-wrap justify-content-end gap-2">
                                                                    <div
                                                                        class="d-flex w-100 justify-content-end align-items-center gap-2">
                                                                        @if ($off > 0)
                                                                            <div
                                                                                class="shadow {{ session()->get('direction') == 2 ? 'sale-label-on-rtl' : 'sale-label-on' }}">
                                                                                {{ $off }}%
                                                                                {{ trans('labels.off') }}
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
                                                                                            <i
                                                                                                class="fa-solid fa-heart"></i>
                                                                                        @else
                                                                                            <i
                                                                                                class="fa-regular fa-heart"></i>
                                                                                        @endif
                                                                                    @else
                                                                                        <i
                                                                                            class="fa-regular fa-heart"></i>
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
                                                                <div class="product-details-inner mb-2 line-2">
                                                                    <a
                                                                        href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                        <h4 id="itemname"
                                                                            class="color-changer text-dark {{ session()->get('direction') == 2 ? 'text-right' : '' }}">
                                                                            {{ $item->item_name }}
                                                                        </h4>
                                                                    </a>
                                                                </div>
                                                                <div class="card-footer border-0 bg-transparent p-0">
                                                                    <div
                                                                        class="d-flex gap-2 flex-wrap justify-content-between">
                                                                        <!-- rating -->
                                                                        @if (@helper::checkaddons('product_reviews'))
                                                                            <div
                                                                                class="d-flex align-items-center justify-content-between">
                                                                                @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                                                    <p class="m-0 rating-star gap-1 d-inline cursor-pointer"
                                                                                        onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')">
                                                                                        <i
                                                                                            class="fa-solid fa-star text-warning"></i>
                                                                                        <span class="color-changer">
                                                                                            {{ number_format($item->ratings_average, 1) }}
                                                                                        </span>
                                                                                    </p>
                                                                                @endif
                                                                            </div>
                                                                        @endif
                                                                        <!-- stock -->
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
                                                                                        {{ trans('labels.in_stock') }}
                                                                                    </p>
                                                                                </div>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                    <div
                                                                        class="d-flex align-items-baseline flex-wrap gap-1">
                                                                        <p
                                                                            class="pro-pricing color-changer line-1 m-0">
                                                                            {{ helper::currency_formate($price, $storeinfo->id) }}
                                                                        </p>
                                                                        @if ($original_price > $price)
                                                                            <p
                                                                                class="pro-pricing pro-org-value line-1 m-0">
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
                                        @elseif (helper::appdata($storeinfo->id)->template == 12)
                                            <div
                                                class="row row-cols-xl-3 row-cols-lg-2 row-cols-md-2 row-cols-sm-2 row-cols-1 g-3">
                                                @foreach ($getitem as $key => $item)
                                                    @php
                                                        if (
                                                            $item->top_deals == 1 &&
                                                            helper::top_deals($storeinfo->id) != null
                                                        ) {
                                                            if (@helper::top_deals($storeinfo->id)->offer_type == 1) {
                                                                if ($item['variation']->count() > 0) {
                                                                    if (
                                                                        $item['variation'][0]->price >
                                                                        @helper::top_deals($storeinfo->id)->offer_amount
                                                                    ) {
                                                                        $price =
                                                                            $item['variation'][0]->price -
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount;
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
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount;
                                                                    } else {
                                                                        $price = $item->item_price;
                                                                    }
                                                                }
                                                            } else {
                                                                if ($item['variation']->count() > 0) {
                                                                    $price =
                                                                        $item['variation'][0]->price -
                                                                        $item['variation'][0]->price *
                                                                            (@helper::top_deals($storeinfo->id)
                                                                                ->offer_amount /
                                                                                100);
                                                                } else {
                                                                    $price =
                                                                        $item->item_price -
                                                                        $item->item_price *
                                                                            (@helper::top_deals($storeinfo->id)
                                                                                ->offer_amount /
                                                                                100);
                                                                }
                                                            }
                                                            if ($item['variation']->count() > 0) {
                                                                $original_price = $item['variation'][0]->price;
                                                            } else {
                                                                $original_price = $item->item_price;
                                                            }
                                                            $off =
                                                                $original_price > 0
                                                                    ? number_format(
                                                                        100 - ($price * 100) / $original_price,
                                                                        1,
                                                                    )
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
                                                                    ? number_format(
                                                                        100 - ($price * 100) / $original_price,
                                                                        1,
                                                                    )
                                                                    : 0;
                                                        }
                                                    @endphp
                                                    <div class="col">
                                                        <div
                                                            class="card border rounded-4 h-100 card__article bg-transparent">
                                                            <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}"
                                                                class=" position-relative">
                                                                @if (@$item['product_image']->image == null)
                                                                    <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                                        class="card-img-top rounded-4"
                                                                        alt="product image">
                                                                @else
                                                                    <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                                        class="card-img-top rounded-4"
                                                                        alt="product image">
                                                                @endif
                                                            </a>
                                                            <div
                                                                class="pro-9 p-3 w-100 d-flex justify-content-between align-items-center position-absolute">
                                                                @if ($off > 0)
                                                                    <div
                                                                        class="discount text-bg-primary fs-13 px-2 py-1 rounded">
                                                                        {{ $off }}%
                                                                        {{ trans('labels.off') }}
                                                                    </div>
                                                                @endif
                                                                <!-- wishlist -->
                                                                @if (@helper::checkaddons('customer_login'))
                                                                    @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                                        <div class="wishlist ">
                                                                            <a onclick="managefavorite('{{ $item->id }}','{{ $storeinfo->id }}','{{ helper::storefront_url($storeinfo, '/managefavorite') }}')"
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
                                                                                        <i
                                                                                            class="fa-solid fa-heart"></i>
                                                                                    @else
                                                                                        <i
                                                                                            class="fa-regular fa-heart"></i>
                                                                                    @endif
                                                                                @else
                                                                                    <i class="fa-regular fa-heart"></i>
                                                                                @endif
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                            <div class="card__data p-3">
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center gap-2">
                                                                    <a
                                                                        href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                        <span
                                                                            class="card__description text-dark line-2">
                                                                            {{ $item->item_name }}</span>
                                                                    </a>
                                                                    @if (@helper::checkaddons('product_reviews'))
                                                                        <div
                                                                            class="d-flex align-items-center justify-content-between mb-2 ">
                                                                            @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                                                <p class="m-0 fs-8 d-flex gap-1 align-items-center fw-500 cursor-pointer"
                                                                                    onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')">
                                                                                    <i
                                                                                        class="fa-solid fa-star text-warning"></i>
                                                                                    <span>
                                                                                        {{ number_format($item->ratings_average, 1) }}
                                                                                    </span>
                                                                                </p>
                                                                            @endif
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <!-- stock -->
                                                                @if ($item->stock_management == 1)
                                                                    @if (helper::checklowqty($item->id, $storeinfo->id) == 2 && $item->has_variants != 1)
                                                                        <div class="out-stock my-1">
                                                                            <span
                                                                                class="out-stock-indicator-dot"></span>
                                                                            <p class="out-stock-text">
                                                                                {{ trans('labels.out_of_stock') }}
                                                                            </p>
                                                                        </div>
                                                                    @else
                                                                        <div class="in-stock my-1">
                                                                            <span
                                                                                class="in-stock-indicator-dot"></span>
                                                                            <p class="in-stock-text">
                                                                                {{ trans('labels.in_stock') }}
                                                                            </p>
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                                <!-- price -->
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                                                                    <p class="price m-0 fw-600">
                                                                        {{ helper::currency_formate($price, $storeinfo->id) }}
                                                                        <!-- old-price -->
                                                                        @if ($original_price > $price)
                                                                            <del class="old-price fw-500 text-muted">
                                                                                {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                                                            </del>
                                                                        @endif
                                                                    </p>
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
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif (helper::appdata($storeinfo->id)->template == 13)
                                            <div
                                                class="theme-13-card row row-cols-xxl-4 row-cols-xl-3 row-cols-lg-2 row-cols-md-2 row-cols-sm-2 row-cols-1 g-3">
                                                @foreach ($getitem as $key => $item)
                                                    @php
                                                        if (
                                                            $item->top_deals == 1 &&
                                                            helper::top_deals($storeinfo->id) != null
                                                        ) {
                                                            if (@helper::top_deals($storeinfo->id)->offer_type == 1) {
                                                                if ($item['variation']->count() > 0) {
                                                                    if (
                                                                        $item['variation'][0]->price >
                                                                        @helper::top_deals($storeinfo->id)->offer_amount
                                                                    ) {
                                                                        $price =
                                                                            $item['variation'][0]->price -
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount;
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
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount;
                                                                    } else {
                                                                        $price = $item->item_price;
                                                                    }
                                                                }
                                                            } else {
                                                                if ($item['variation']->count() > 0) {
                                                                    $price =
                                                                        $item['variation'][0]->price -
                                                                        $item['variation'][0]->price *
                                                                            (@helper::top_deals($storeinfo->id)
                                                                                ->offer_amount /
                                                                                100);
                                                                } else {
                                                                    $price =
                                                                        $item->item_price -
                                                                        $item->item_price *
                                                                            (@helper::top_deals($storeinfo->id)
                                                                                ->offer_amount /
                                                                                100);
                                                                }
                                                            }
                                                            if ($item['variation']->count() > 0) {
                                                                $original_price = $item['variation'][0]->price;
                                                            } else {
                                                                $original_price = $item->item_price;
                                                            }
                                                            $off =
                                                                $original_price > 0
                                                                    ? number_format(
                                                                        100 - ($price * 100) / $original_price,
                                                                        1,
                                                                    )
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
                                                                    ? number_format(
                                                                        100 - ($price * 100) / $original_price,
                                                                        1,
                                                                    )
                                                                    : 0;
                                                        }
                                                    @endphp
                                                    <div class="col sal-padding">
                                                        <div
                                                            class="card border p-sm-3 p-2 h-100 position-relative overflow-hidden">
                                                            @if ($off > 0)
                                                                <div
                                                                    class="{{ session()->get('direction') == 2 ? 'sale-label-on-rtl' : 'sale-label-on' }} shadow">
                                                                    {{ $off }}% {{ trans('labels.off') }}
                                                                </div>
                                                            @endif
                                                            <div class="pro-6-list-img position-relative rounded">
                                                                <a
                                                                    href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                    @if (@$item['product_image']->image == null)
                                                                        <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                                            class="card-img-top rounded"
                                                                            alt="product image">
                                                                    @else
                                                                        <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                                            class="card-img-top rounded"
                                                                            alt="product image">
                                                                    @endif
                                                                </a>
                                                                <div class="sale-heart">
                                                                    @if (@helper::checkaddons('customer_login'))
                                                                        @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                                            <div class="pro-like">
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
                                                                                            <i
                                                                                                class="fa-solid fa-heart text-white"></i>
                                                                                        @else
                                                                                            <i
                                                                                                class="fa-regular fa-heart"></i>
                                                                                        @endif
                                                                                    @else
                                                                                        <i
                                                                                            class="fa-regular fa-heart"></i>
                                                                                    @endif
                                                                                </a>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="card-body px-0">
                                                                <a
                                                                    href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                    <h6
                                                                        class="fw-600 color-changer text-dark line-2 {{ session()->get('direction') == 2 ? 'ps-1' : 'pe-1' }}">
                                                                        {{ $item->item_name }}
                                                                    </h6>
                                                                </a>
                                                                <div
                                                                    class="d-flex align-items-center justify-content-between">
                                                                    @if ($item->stock_management == 1)
                                                                        @if (helper::checklowqty($item->id, $storeinfo->id) == 2 && $item->has_variants != 1)
                                                                            <div class="out-stock">
                                                                                <span
                                                                                    class="out-stock-indicator-dot"></span>
                                                                                <p class="out-stock-text">
                                                                                    {{ trans('labels.out_of_stock') }}
                                                                                </p>
                                                                            </div>
                                                                        @else
                                                                            <div class="in-stock">
                                                                                <span
                                                                                    class="in-stock-indicator-dot"></span>
                                                                                <p class="in-stock-text">
                                                                                    {{ trans('labels.in_stock') }}</p>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                    @if (@helper::checkaddons('product_reviews'))
                                                                        @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                                            <p class="rating-star color-changer d-flex gap-1 fs-13 align-items-center cursor-pointer"
                                                                                onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')">
                                                                                <i
                                                                                    class="fa-solid fa-star text-warning"></i>
                                                                                <span>{{ number_format($item->ratings_average, 1) }}</span>
                                                                            </p>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="card-footer p-0 border-0 bg-transparent">
                                                                <div class="d-flex gap-2 justify-content-between">
                                                                    <div
                                                                        class="d-flex gap-1 align-items-center flex-wrap">
                                                                        <p class="price color-changer m-0">
                                                                            {{ helper::currency_formate($price, $storeinfo->id) }}
                                                                        </p>
                                                                        @if ($original_price > $price)
                                                                            <del class="text-muted fw-600 fs-13">
                                                                                {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                                                            </del>
                                                                        @endif
                                                                    </div>
                                                                    <button class="btn btn-cart m-0"
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
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif (helper::appdata($storeinfo->id)->template == 14)
                                            <div class="pro-7-sec">
                                                <div
                                                    class="row g-3 row-cols-xl-4 row-cols-lg-3 row-cols-md-3 row-cols-sm-2 row-cols-1 theme-14-card">
                                                    @foreach ($getitem as $key => $item)
                                                        @php
                                                            if (
                                                                $item->top_deals == 1 &&
                                                                helper::top_deals($storeinfo->id) != null
                                                            ) {
                                                                if (
                                                                    @helper::top_deals($storeinfo->id)->offer_type == 1
                                                                ) {
                                                                    if ($item['variation']->count() > 0) {
                                                                        if (
                                                                            $item['variation'][0]->price >
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount
                                                                        ) {
                                                                            $price =
                                                                                $item['variation'][0]->price -
                                                                                @helper::top_deals($storeinfo->id)
                                                                                    ->offer_amount;
                                                                        } else {
                                                                            $price = $item['variation'][0]->price;
                                                                        }
                                                                    } else {
                                                                        if (
                                                                            $item->item_price >
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount
                                                                        ) {
                                                                            $price =
                                                                                $item->item_price -
                                                                                @helper::top_deals($storeinfo->id)
                                                                                    ->offer_amount;
                                                                        } else {
                                                                            $price = $item->item_price;
                                                                        }
                                                                    }
                                                                } else {
                                                                    if ($item['variation']->count() > 0) {
                                                                        $price =
                                                                            $item['variation'][0]->price -
                                                                            $item['variation'][0]->price *
                                                                                (@helper::top_deals($storeinfo->id)
                                                                                    ->offer_amount /
                                                                                    100);
                                                                    } else {
                                                                        $price =
                                                                            $item->item_price -
                                                                            $item->item_price *
                                                                                (@helper::top_deals($storeinfo->id)
                                                                                    ->offer_amount /
                                                                                    100);
                                                                    }
                                                                }
                                                                if ($item['variation']->count() > 0) {
                                                                    $original_price = $item['variation'][0]->price;
                                                                } else {
                                                                    $original_price = $item->item_price;
                                                                }
                                                                $off =
                                                                    $original_price > 0
                                                                        ? number_format(
                                                                            100 - ($price * 100) / $original_price,
                                                                            1,
                                                                        )
                                                                        : 0;
                                                            } else {
                                                                if ($item['variation']->count() > 0) {
                                                                    $price = $item['variation'][0]->price;
                                                                    $original_price =
                                                                        $item['variation'][0]->original_price;
                                                                } else {
                                                                    $price = $item->item_price;
                                                                    $original_price = $item->item_original_price;
                                                                }
                                                                $off =
                                                                    $original_price > 0
                                                                        ? number_format(
                                                                            100 - ($price * 100) / $original_price,
                                                                            1,
                                                                        )
                                                                        : 0;
                                                            }
                                                        @endphp
                                                        <div class="col">
                                                            <div class="card h-100 rounded-0 border">
                                                                <div class="card-img">
                                                                    <a
                                                                        href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                        @if (@$item['product_image']->image == null)
                                                                            <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                                                class="card-img-top rounded"
                                                                                alt="product image">
                                                                        @else
                                                                            <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                                                class="card-img-top rounded"
                                                                                alt="product image">
                                                                        @endif
                                                                    </a>
                                                                    @if ($item->stock_management == 1)
                                                                        @if (helper::checklowqty($item->id, $storeinfo->id) == 2 && $item->has_variants != 1)
                                                                            <div class="out-stock">
                                                                                <span
                                                                                    class="out-stock-indicator-dot"></span>
                                                                                <p class="out-stock-text">
                                                                                    {{ trans('labels.out_of_stock') }}
                                                                                </p>
                                                                            </div>
                                                                        @else
                                                                            <div class="in-stock">
                                                                                <span
                                                                                    class="in-stock-indicator-dot"></span>
                                                                                <p class="in-stock-text">
                                                                                    {{ trans('labels.in_stock') }}</p>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                                @if ($off > 0)
                                                                    <div class="offer-box shadow">
                                                                        <span class="offer-text text-white p-2">
                                                                            {{ $off }}%
                                                                            {{ trans('labels.off') }}
                                                                        </span>
                                                                    </div>
                                                                @endif
                                                                <div class="card-body border-top">
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-start mb-2 gap-2">
                                                                        <a
                                                                            href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                            <h6 id="itemname"
                                                                                class="fs-15 color-changer text-dark fw-600 m-0 line-2">
                                                                                {{ $item->item_name }}</h6>
                                                                        </a>
                                                                        @if (@helper::checkaddons('product_reviews'))
                                                                            @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                                                <p class="m-0 fs-13 d-flex gap-1 align-items-center pro-rating cursor-pointer"
                                                                                    onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')">
                                                                                    <i
                                                                                        class="fa-solid fa-star text-warning"></i>
                                                                                    <span
                                                                                        class="color-changer">{{ number_format($item->ratings_average, 1) }}</span>
                                                                                </p>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                    <p
                                                                        class="pro-pricing color-changer fs-15 line-1 m-0">
                                                                        {{ helper::currency_formate($price, $storeinfo->id) }}
                                                                        @if ($original_price > $price)
                                                                            <del
                                                                                class="old-price fs-13 text-muted fw-600">
                                                                                {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                                                            </del>
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                                <div
                                                                    class="card-footer p-0 overflow-hidden d-flex rounded-0">
                                                                    @if (@helper::checkaddons('customer_login'))
                                                                        @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                                            <a class="wishlist"
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
                                                                                        <i
                                                                                            class="fa-solid fa-heart"></i>
                                                                                    @else
                                                                                        <i
                                                                                            class="fa-regular fa-heart"></i>
                                                                                    @endif
                                                                                @else
                                                                                    <i class="fa-regular fa-heart"></i>
                                                                                @endif
                                                                            </a>
                                                                        @endif
                                                                    @endif
                                                                    <button
                                                                        class="btn btn-sm m-0 btn-primary w-100 rounded-0"
                                                                        id="verifybtn{{ $key }}_{{ $item->id }}"
                                                                        onclick="GetProductOverview('{{ $item->slug }}',this.id)">
                                                                        {{ helper::appdata($storeinfo->id)->online_order == 1 ? trans('labels.add_to_cart') : trans('labels.view') }}
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @elseif (helper::appdata($storeinfo->id)->template == 15)
                                            <div
                                                class="pro-wishlist-15 row row-cols-xl-4 row-cols-lg-2 row-cols-md-3 row-cols-sm-2 row-cols-1 g-3">
                                                @foreach ($getitem as $key => $item)
                                                    @php
                                                        if (
                                                            $item->top_deals == 1 &&
                                                            helper::top_deals($storeinfo->id) != null
                                                        ) {
                                                            if (@helper::top_deals($storeinfo->id)->offer_type == 1) {
                                                                if ($item['variation']->count() > 0) {
                                                                    if (
                                                                        $item['variation'][0]->price >
                                                                        @helper::top_deals($storeinfo->id)->offer_amount
                                                                    ) {
                                                                        $price =
                                                                            $item['variation'][0]->price -
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount;
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
                                                                            @helper::top_deals($storeinfo->id)
                                                                                ->offer_amount;
                                                                    } else {
                                                                        $price = $item->item_price;
                                                                    }
                                                                }
                                                            } else {
                                                                if ($item['variation']->count() > 0) {
                                                                    $price =
                                                                        $item['variation'][0]->price -
                                                                        $item['variation'][0]->price *
                                                                            (@helper::top_deals($storeinfo->id)
                                                                                ->offer_amount /
                                                                                100);
                                                                } else {
                                                                    $price =
                                                                        $item->item_price -
                                                                        $item->item_price *
                                                                            (@helper::top_deals($storeinfo->id)
                                                                                ->offer_amount /
                                                                                100);
                                                                }
                                                            }
                                                            if ($item['variation']->count() > 0) {
                                                                $original_price = $item['variation'][0]->price;
                                                            } else {
                                                                $original_price = $item->item_price;
                                                            }
                                                            $off =
                                                                $original_price > 0
                                                                    ? number_format(
                                                                        100 - ($price * 100) / $original_price,
                                                                        1,
                                                                    )
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
                                                                    ? number_format(
                                                                        100 - ($price * 100) / $original_price,
                                                                        1,
                                                                    )
                                                                    : 0;
                                                        }
                                                    @endphp
                                                    <div class="col">
                                                        <div class="card h-100 rounded-0 w-100 border">
                                                            <div class="pro-8-img position-relative overflow-hidden">
                                                                <a
                                                                    href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                    @if (@$item['product_image']->image == null)
                                                                        <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                                            class="card-img-top rounded-0"
                                                                            alt="product image">
                                                                    @else
                                                                        <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                                            class="card-img-top rounded-0"
                                                                            alt="product image">
                                                                    @endif
                                                                </a>
                                                                <a
                                                                    href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                    @if ($item['multi_image']->count() > 1)
                                                                        <img src="{{ @helper::image_path($item['multi_image'][1]->image) }}"
                                                                            alt="product image"
                                                                            class="w-100 object-fit-cover img-flip">
                                                                    @endif
                                                                </a>
                                                                @if ($off > 0)
                                                                    <div class="sale-label-on ltr">
                                                                        {{ $off }}%
                                                                        {{ trans('labels.off') }}
                                                                    </div>
                                                                @endif
                                                                <!-- rating -->
                                                                @if (@helper::checkaddons('product_reviews'))
                                                                    <div
                                                                        class="rating rounded-5 d-flex align-items-center justify-content-between">
                                                                        @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                                            <p class="m-0 fw-600  d-flex align-items-center gap-1 cursor-pointer"
                                                                                onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')">
                                                                                <i
                                                                                    class="fa-solid fa-star text-warning"></i>
                                                                                <span
                                                                                    class="">{{ number_format($item->ratings_average, 1) }}</span>
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                                <ul class="p-0 d-flex flex-column gap-2 m-0 icons-hc">
                                                                    <li>
                                                                        @if (@helper::checkaddons('customer_login'))
                                                                            @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                                                <a onclick="managefavorite('{{ $item->id }}',{{ $storeinfo->id }},'{{ helper::storefront_url($storeinfo, '/managefavorite') }}')"
                                                                                    class="cursor-pointer wishlist"
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
                                                                                            <i
                                                                                                class="fa-solid fa-heart"></i>
                                                                                        @else
                                                                                            <i
                                                                                                class="fa-regular fa-heart"></i>
                                                                                        @endif
                                                                                    @else
                                                                                        <i
                                                                                            class="fa-regular fa-heart"></i>
                                                                                    @endif
                                                                                </a>
                                                                            @endif
                                                                        @endif
                                                                    </li>
                                                                    <li>
                                                                        <button class="btn p-0 border-0 pro-8-add"
                                                                            title="cart"
                                                                            id="iconverifybtn{{ $key }}_{{ $item->id }}"
                                                                            onclick="GetProductOverview('{{ $item->slug }}',this.id)">
                                                                            @if (helper::appdata($storeinfo->id)->online_order == 1)
                                                                                <i
                                                                                    class="fa-regular fa-cart-shopping"></i>
                                                                            @else
                                                                                <i class="fa-regular fa-eye"></i>
                                                                            @endif
                                                                        </button>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="card-body pb-0">
                                                                <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}"
                                                                    class="text-secondary">
                                                                    <h6 class="fs-15 line-2">
                                                                        {{ $item->item_name }}
                                                                    </h6>
                                                                </a>
                                                                <!-- in-stock -->
                                                                @if ($item->stock_management == 1)
                                                                    @if (helper::checklowqty($item->id, $storeinfo->id) == 2 && $item->has_variants != 1)
                                                                        <div class="out-stock m-0">
                                                                            <span
                                                                                class="out-stock-indicator-dot"></span>
                                                                            <p class="out-stock-text">
                                                                                {{ trans('labels.out_of_stock') }}
                                                                            </p>
                                                                        </div>
                                                                    @else
                                                                        <div class="in-stock m-0">
                                                                            <span
                                                                                class="in-stock-indicator-dot"></span>
                                                                            <p class="in-stock-text">
                                                                                {{ trans('labels.in_stock') }}
                                                                            </p>
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                            <div class="card-footer bg-transparent border-0">
                                                                <!-- price -->
                                                                <p class="price color-changer fs-15 m-0">
                                                                    {{ helper::currency_formate($price, $storeinfo->id) }}
                                                                    <!-- false-price -->
                                                                    @if ($original_price > $price)
                                                                        <del class="fs-13 text-muted">
                                                                            {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                                                        </del>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="d-flex justify-content-center mt-3">
                                        {!! $getitem->links() !!}
                                    </div>
                                @else
                                    @include('front.no_data')
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- newsletter -->
@include('front.newsletter')
<!-- newsletter -->
@include('front.theme.footer')
