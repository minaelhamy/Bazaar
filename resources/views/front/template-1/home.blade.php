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

@if ($bannerimage1->count() > 0)
    <section class="feature-sec my-5">
        <div class="container">
            <div class="feature-carousel owl-carousel owl-theme m-0">
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
<!-- Best-selling-Items -->
@if (helper::appdata($storeinfo->id)->product_section_display == 1 ||
        helper::appdata($storeinfo->id)->product_section_display == 3)
    @if (count($bestsellingitems) > 0)
        <section class="product-prev-sec my-5">
            <div class="container">
                <div class="sec-header py-2 mb-3">
                    <h4 class="main-title mb-2 color-changer">{{ trans('labels.selling_product') }}</h4>
                    <p class="m-0 line-2 fs-15 text-muted">{{ trans('labels.selling_product_subtitle') }}</p>
                </div>
                <div class="row row-cols-xl-5 row-cols-lg-4 row-cols-md-3 row-cols-2 g-2 recipe-card">
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
                            <div class="pro-box h-100">

                                <div class="pro-img">
                                    <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                        @if (@$item['product_image']->image == null)
                                            <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                class="cursor-pointer" alt="product image">
                                        @else
                                            <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                class="cursor-pointer" alt="product image">
                                        @endif
                                    </a>

                                    <div class="sale-heart">
                                        @if ($off > 0)
                                            <div class="sale-label-on">{{ $off }}% {{ trans('labels.off') }}
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

                                <div class="product-details-wrap">
                                    <div class="product-details-inner  mb-2 line-2">
                                        <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                            <h4 id="itemname"
                                                class="text-dark color-changer {{ session()->get('direction') == 2 ? 'text-right' : '' }}">
                                                {{ $item->item_name }}</h4>
                                        </a>
                                    </div>
                                    <div class="card-footer border-0 bg-transparent p-0">
                                        <div class="d-flex justify-content-between">
                                            @if (@helper::checkaddons('product_reviews'))
                                                @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                    <p class="m-0 rating-star d-inline cursor-pointer"
                                                        onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')"
                                                        aria-controls="offcanvasRight">
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
                                        <button class="btn btn-sm m-0 py-1 w-100 btn-content rounded-5"
                                            id="verifybtn{{ $key }}_{{ $item->id }}"
                                            onclick="GetProductOverview('{{ $item->slug }}',this.id)">{{ helper::appdata($storeinfo->id)->online_order == 1 ? trans('labels.add_to_cart') : trans('labels.view') }}</button>
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
    <section class="my-5 py-5 who_are bg-change-mode">
        <div class="container">
            <div class="py-sm-5">
                <div class="row g-3">
                    <div class="col-lg-6">
                        <div class="img-15 rounded">
                            <img src="{{ helper::image_path(helper::appdata($storeinfo->id)->whoweare_image) }}"
                                alt="" class="rounded">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h5 class="line-1 mb-2 color-changer main-text-sub fw-500 m-0">
                            {{ helper::appdata($storeinfo->id)->whoweare_title }}</h5>
                        <h3 class="line-2 color-changer main-tital fw-600">
                            {{ helper::appdata($storeinfo->id)->whoweare_subtitle }}
                        </h3>
                        <p class="m-0 text-muted fs-15 line-3">
                            {{ helper::appdata($storeinfo->id)->whoweare_description }}
                        </p>
                        <div class="col-12">
                            <div class="row g-3 mt-1">
                                @foreach ($whowearedata as $whoweare)
                                    <div class="col-sm-6">
                                        <div class="card border h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center gap-3 flex-column">
                                                    <div class="icon-img-15">
                                                        <img src="{{ helper::image_path($whoweare->image) }}"
                                                            alt="" class="border rounded-circle">
                                                    </div>
                                                    <div class="tital-15 text-center">
                                                        <h6 class="line-1 color-changer text-center fw-600">
                                                            {{ $whoweare->title }}
                                                        </h6>
                                                        <p class="m-0 fs-8 text-muted fw-500 mt-1 line-2">
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
        </div>
    </section>
@endif
<!---------- WHO WE ARE END ---------->

@if (helper::getcategory($storeinfo->id)->count() > 0)
    <section class="product-prev-sec product-list-sec pt-0 mt-sm-5 mt-3">
        <div class="container">
            <div class="product-rev-wrap row" id="settingmenuContent">
                <div
                    class="card card-bg bg-transparent cat-aside cat-aside-theme1 col-xl-3 col-lg-3 d-none d-lg-block">
                    <div class="card-header cat-dispaly bg-transparent px-0">
                        <div class="d-inline-block">
                            <h4 class="color-changer {{ session()->get('direction') == 2 ? 'text-right' : '' }} m-0 ">
                                {{ trans('labels.category') }}</h4>
                        </div>
                    </div>
                    <div
                        class="cat-aside-wrap responsiv-cat-aside mt-2 {{ session()->get('direction') == 2 ? 'text-right' : '' }}">

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
                                <a href="#{{ $category->slug }}" data-cat-type="{{ $key }}"
                                    class="border-top-no cat-check rounded-5 {{ session()->get('direction') == 2 ? 'cat-right-margin' : 'cat-left-margin' }} {{ $key == 0 ? 'active' : '' }}"
                                    data-tab="{{ $category->slug }}">
                                    <p class="line-1">{{ $category->name }}</p>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="cat-product col-xl-9 col-lg-9 custom-categories-main-sec">
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
                            <div id="{{ $category->slug }}">
                                <div
                                    class="card card-bg card-header responsive-padding bg-transparent px-0 custom-cat-name-sec mt-4 mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-inline-block">
                                            <h4
                                                class="sec-title color-changer {{ session()->get('direction') == 2 ? 'text-right mt-2' : '' }}">
                                                {{ $category->name }}
                                                <span class="px-2">({{ $check_cat_count }})</span>
                                            </h4>
                                        </div>
                                        <div class="d-none">
                                            <a href="{{ helper::storefront_url($storeinfo, '/search?category=' . $category->slug) }}"
                                                class="btn-category">{{ trans('labels.view_all') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row recipe-card custom-product-card g-2">
                                    @if (!helper::getcategory($storeinfo->id)->isEmpty())
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
                                                                    (@helper::top_deals($storeinfo->id)->offer_amount /
                                                                        100);
                                                        } else {
                                                            $price =
                                                                $item->item_price -
                                                                $item->item_price *
                                                                    (@helper::top_deals($storeinfo->id)->offer_amount /
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
                                                <div class="col-xl-3 col-md-4 responsive-col custom-product-column"
                                                    id="pro-box">
                                                    <div class="pro-box h-100">
                                                        <div class="pro-img">
                                                            <a
                                                                href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                @if (@$item['product_image']->image == null)
                                                                    <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                                        class="cursor-pointer" alt="product image">
                                                                @else
                                                                    <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                                        class="cursor-pointer" alt="product image">
                                                                @endif
                                                            </a>

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

                                                        <div class="product-details-wrap">
                                                            <div class="product-details-inner  mb-2 line-2">
                                                                <a
                                                                    href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                                    <h4 id="itemname"
                                                                        class="text-dark color-changer {{ session()->get('direction') == 2 ? 'text-right' : '' }}">
                                                                        {{ $item->item_name }}</h4>
                                                                </a>
                                                            </div>
                                                            <div class="card-footer border-0 bg-transparent p-0">
                                                                <div class="d-flex justify-content-between">
                                                                    @if (@helper::checkaddons('product_reviews'))
                                                                        @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                                            <p class="m-0 rating-star d-inline cursor-pointer"
                                                                                onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')"
                                                                                aria-controls="offcanvasRight">
                                                                                <i
                                                                                    class="fa-solid fa-star text-warning"></i>
                                                                                <span class="px-1 color-changer">
                                                                                    {{ number_format($item->ratings_average, 1) }}
                                                                                </span>
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
                                                                    class="d-flex align-items-baseline flex-wrap gap-1">
                                                                    <p class="pro-pricing color-changer line-1">
                                                                        {{ helper::currency_formate($price, $storeinfo->id) }}
                                                                    </p>
                                                                    @if ($original_price > $price)
                                                                        <p
                                                                            class="pro-pricing pro-org-value line-1 m-0">
                                                                            {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                                                        </p>
                                                                    @endif
                                                                </div>
                                                                <button
                                                                    class="btn btn-sm m-0 py-1 w-100 btn-content rounded-5"
                                                                    id="verifybtn{{ $key }}_{{ $category->id }}"
                                                                    onclick="GetProductOverview('{{ $item->slug }}',this.id)">{{ helper::appdata($storeinfo->id)->online_order == 1 ? trans('labels.add_to_cart') : trans('labels.view') }}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif

<!-- feature-sec -->
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
                    <div class="item rounded-2 mx-1">
                        <div class="feature-box rounded-2">
                            <img src="{{ helper::image_path($image->banner_image) }}" alt=""
                                class="rounded-2">
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
        <section class="product-prev-sec who_are bg-change-mode py-5 my-5">
            <div class="container">
                <div class="sec-header py-2 mb-3">
                    <h4 class="main-title color-changer mb-2">{{ trans('labels.top_rated_product') }}</h4>
                    <p class="m-0 line-2 fs-15 text-muted">{{ trans('labels.top_rated_product_subtitle') }}</p>
                </div>
                <div class="row row-cols-xl-5 row-cols-lg-4 row-cols-md-3 row-cols-2 g-2 recipe-card">
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
                            <div class="pro-box h-100">

                                <div class="pro-img">
                                    <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                        @if (@$item['product_image']->image == null)
                                            <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                class="cursor-pointer" alt="product image">
                                        @else
                                            <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                class="cursor-pointer" alt="product image">
                                        @endif
                                    </a>

                                    <div class="sale-heart">
                                        @if ($off > 0)
                                            <div class="sale-label-on">{{ $off }}% {{ trans('labels.off') }}
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

                                <div class="product-details-wrap">
                                    <div class="product-details-inner  mb-2 line-2">
                                        <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                            <h4 id="itemname"
                                                class="text-dark color-changer {{ session()->get('direction') == 2 ? 'text-right' : '' }}">
                                                {{ $item->item_name }}</h4>
                                        </a>
                                    </div>
                                    <div class="card-footer border-0 bg-transparent p-0">
                                        <div class="d-flex justify-content-between">
                                            @if (@helper::checkaddons('product_reviews'))
                                                @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                    <p class="m-0 rating-star d-inline cursor-pointer"
                                                        onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')"
                                                        aria-controls="offcanvasRight">
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
                                            <p class="pro-pricing line-1 color-changer">
                                                {{ helper::currency_formate($price, $storeinfo->id) }}
                                            </p>
                                            @if ($original_price > $price)
                                                <p class="pro-pricing pro-org-value line-1 m-0">
                                                    {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                                </p>
                                            @endif
                                        </div>
                                        <button class="btn btn-sm m-0 py-1 w-100 btn-content rounded-5"
                                            id="verifybtn3{{ $key }}_{{ $item->id }}"
                                            onclick="GetProductOverview('{{ $item->slug }}',this.id)">{{ helper::appdata($storeinfo->id)->online_order == 1 ? trans('labels.add_to_cart') : trans('labels.view') }}</button>
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
    @include('front.testimonial')
@endif

<!-- blog -->
@if (helper::getblogs($storeinfo->id)->count() > 0)
    <section class="blog-6-sec">
        <div class="container">
            <div class="sec-header py-2 mb-3">
                <h4 class="main-title color-changer mb-2">{{ trans('labels.our_latest_blogs') }}</h4>
                <p class="m-0 line-2 fs-15 text-muted">{{ trans('labels.our_latest_blogs_subtitle') }}</p>
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
                        <div class="blog-6 owl-carousel owl-theme th-2-p">
                            @foreach ($blog as $blog)
                                <div class="item h-100">
                                    <div class="card card-bg border-0 h-100 rounded-3 overflow-hidden p-2">
                                        <div class="blog-6-img rounded-3">
                                            <a href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}">
                                                <img src="{{ helper::image_path($blog->image) }}" height="300"
                                                    alt="blog img" class="w-100 object-fit-cover rounded-3">
                                            </a>
                                        </div>
                                        <div class="card-body px-0 py-3">
                                            <h4 class="title line-2">
                                                <a
                                                    href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}" class="color-changer text-dark">{{ $blog->title }}</a>
                                            </h4>
                                            <span class="blog-created">
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
                                                href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}" class="color-changer text-dark">{{ $blog->title }}</a>
                                        </h4>
                                        <span class="blog-created">
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
        </div>
    </section>
@endif

<!--------- newsletter --------->
@include('front.newsletter')


@include('front.theme.footer')
