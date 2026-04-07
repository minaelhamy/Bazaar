@include('front.theme.header')

<!-- furniture home -->
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

@if ($bannerimage1->count() > 0)
    <!-- new feature-sec -->
    <section class="feature-sec-6 my-4 my-lg-5">
        <div class="container">
            <div class="feature-carousel-6 owl-carousel owl-rtl owl-theme">
                @foreach ($bannerimage1 as $image)
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
                    <div class="item h-100">
                        <img src='{{ helper::image_path($image->banner_image) }}' alt="" class="h-100 rounded">
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
        <section class="mb-5 products-6 py-5">
            <div class="container">
                <div class="sec-header mb-4">
                    <h3 class="d-flex justify-content-center color-changer align-items-center main-title text-center m-0">
                        <span class="dots"></span>
                        {{ trans('labels.selling_product') }}
                        <span class="dots"></span>
                    </h3>
                    <p class="m-0 line-2 fs-15 text-center mt-2 fw-500 text-muted">
                        {{ trans('labels.selling_product_subtitle') }}</p>
                </div>
                <div class="pro-6 row row-cols-xl-5 row-cols-lg-4 row-cols-md-3 row-cols-2 px-sm-2 g-3 g-sm-4 mb-4">
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
                        <div class="col sal-padding">
                            <div class="card product-grid-item">
                                <div class="sale-heart">
                                    @if ($off > 0)
                                        <div class="sale-label-on">{{ $off }}% {{ trans('labels.off') }}
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
                                                            <i class="fa-solid fa-heart text-white"></i>
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
                                </div>

                                <div class="pro-6-img">
                                    <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                        @if (@$item['product_image']->image == null)
                                            <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                class="card-img-top rounded-0" alt="product image">
                                        @else
                                            <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                class="card-img-top rounded-0" alt="product image">
                                        @endif
                                    </a>
                                </div>

                                <div class="card-body pb-0">
                                    <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                        <h4 class="pro-6-title color-changer text-dark line-2">
                                            {{ $item->item_name }}
                                        </h4>
                                    </a>
                                </div>
                                <div class="card-footer">
                                    @if (@helper::checkaddons('product_reviews'))
                                        @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                            <p class="mb-2 rating-star cursor-pointer cursor-pointer"
                                                onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')">
                                                <i class="fa-solid fa-star text-warning"></i>
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
                                    <div class="d-flex align-items-center justify-content-between">
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
                                                    <p class="in-stock-text">{{ trans('labels.in_stock') }}</p>
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
            </div>
        </section>
    @endif
@endif

<!---------- WHO WE ARE START ---------->
@if ($whowearedata->count() > 0)
    <section class="storereview-sec-6 my-4 py-5 my-md-5">
        <div class="container">
            <div class="py-sm-5">
                <div class="row g-3">
                    <div class="col-lg-6">
                        <h4 class="sec-title-6 line-1 color-changer fw-500 mb-2 justify-content-start fs-5">
                            <span class="dots"></span>
                            {{ helper::appdata($storeinfo->id)->whoweare_title }}
                            <span class="dots"></span>
                        </h4>
                        <h3 class="line-2 main-title color-changer fw-600">{{ helper::appdata($storeinfo->id)->whoweare_subtitle }}
                        </h3>
                        <p class="m-0 text-muted fs-15 line-3">
                            {{ helper::appdata($storeinfo->id)->whoweare_description }}</p>
                        <div class="col-12">
                            <div class="row g-3 mt-1">
                                @foreach ($whowearedata as $whoweare)
                                    <div class="col-md-6">
                                        <div class="card border h-100">
                                            <div class="card-body">
                                                <div class="d-flex flex-column align-items-center gap-3">
                                                    <div class="icon-img-15">
                                                        <img src="{{ helper::image_path($whoweare->image) }}"
                                                            alt=""
                                                            class="rounded-circle border border-secondary p-1 border-2">
                                                    </div>
                                                    <div class="tital-15 text-center">
                                                        <h6 class="line-1 text-primary text-center fw-600">
                                                            {{ $whoweare->title }}
                                                        </h6>
                                                        <p class="m-0 fs-7 fw-500 text-muted mt-1 line-2">
                                                            {{ $whoweare->sub_title }}
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
                        <div class="img-15 rounded">
                            <img src="{{ helper::image_path(helper::appdata($storeinfo->id)->whoweare_image) }}"
                                alt="" class="rounded">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
<!---------- WHO WE ARE END ---------->


<!-- category and products -->
@if (helper::getcategory($storeinfo->id)->count() > 0)
    <section class="products-6 my-4 my-lg-5">
        <div class="container">
            <div class="sec-header mb-4">
                <h3 class="d-flex justify-content-center color-changer align-items-center main-title text-center m-0">
                    <span class="dots"></span>
                    {{ trans('labels.theme6_product_title') }}
                    <span class="dots"></span>
                </h3>
                <p class="m-0 line-2 fs-15 text-center mt-2 fw-500 text-muted">
                    {{ trans('labels.theme6_product_subtitle') }}</p>
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
                    <div class="category d-flex justify-content-between align-items-center"
                        id="{{ $category->slug }}">
                        <h4 class="cat-title">
                            {{ $category->name }} ({{ $check_cat_count }})
                        </h4>
                        <div class="d-none">
                            <a href="#" class="btn-category text-white">{{ trans('labels.view_all') }}</a>
                        </div>
                    </div>
                @endif

                <!-- products -->
                <div class="pro-6 row row-cols-xl-5 row-cols-lg-4 row-cols-md-3 row-cols-2 px-sm-2 g-3 g-sm-4 mb-4">
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
                            @if (in_array($category->id, explode('|', $item->cat_id)))
                                <div class="col sal-padding">
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
                                                                    <i class="fa-solid fa-heart text-white"></i>
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
                                        </div>

                                        <div class="pro-6-img">
                                            <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                @if (@$item['product_image']->image == null)
                                                    <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                        class="card-img-top rounded-0" alt="product image">
                                                @else
                                                    <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                        class="card-img-top rounded-0" alt="product image">
                                                @endif
                                            </a>
                                        </div>

                                        <div class="card-body pb-0">
                                            <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                <h4 class="pro-6-title color-changer text-dark line-2">
                                                    {{ $item->item_name }}
                                                </h4>
                                            </a>
                                        </div>
                                        <div class="card-footer">
                                            @if (@helper::checkaddons('product_reviews'))
                                                @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                                    <p class="mb-2 rating-star cursor-pointer cursor-pointer"
                                                        onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')">
                                                        <i class="fa-solid fa-star text-warning"></i>
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
                                            <div class="d-flex align-items-center justify-content-between">
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
                                                <button href="javascript:void(0)" class="btn btn-cart m-0"
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
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            @endforeach
        </div>
    </section>
@endif


<!-- storereview -->
@if (@helper::checkaddons('store_reviews'))
    @if ($testimonials->count() > 0)
        <section class="storereview-sec-6 mb-5 py-5">
            <div class="container">
                <div class="py-5">
                    <div class="sec-header mb-4">
                        <h3 class="d-flex justify-content-center color-changer align-items-center main-title text-center m-0">
                            <span class="dots"></span>
                            {{ trans('labels.testimonials') }}
                            <span class="dots"></span>
                        </h3>
                        <p class="m-0 line-2 fs-15 text-center mt-2 fw-500 text-muted">
                            {{ trans('labels.testimonials_subtitle') }}</p>
                    </div>
                    <div class="store-review-6 owl-carousel owl-theme">
                        @foreach ($testimonials as $item)
                            <div class="item">
                                <div class="card h-100 bg-light rounded p-4">
                                    <div class="card-body p-0">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="review-img">
                                                <img src="{{ helper::image_path($item->image) }}" alt="">
                                            </div>
                                            <div class="px-3">
                                                <h5 class="line-1 mb-1 color-changer review_title">{{ $item->name }}</h5>
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
            </div>
        </section>
    @endif
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
                    <div class="item">
                        <div class="feature">
                            <img src="{{ helper::image_path($image->banner_image) }}" alt="" class="">
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
        <section class="mb-5 products-6 storereview-sec-6 py-5">
            <div class="container">
                <div class="sec-header mb-4">
                    <h3 class="d-flex justify-content-center color-changer align-items-center main-title text-center m-0">
                        <span class="dots"></span>
                        {{ trans('labels.top_rated_product') }}
                        <span class="dots"></span>
                    </h3>
                    <p class="m-0 line-2 fs-15 text-center mt-2 fw-500 text-muted">
                        {{ trans('labels.top_rated_product_subtitle') }}</p>
                </div>
                <div class="pro-6 row row-cols-xl-5 row-cols-lg-4 row-cols-md-3 row-cols-2 px-sm-2 g-3 g-sm-4 mb-4">
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
                        <div class="col sal-padding">
                            <div class="card product-grid-item">
                                <div class="sale-heart">
                                    @if ($off > 0)
                                        <div class="sale-label-on">{{ $off }}% {{ trans('labels.off') }}
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
                                                            <i class="fa-solid fa-heart text-white"></i>
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
                                </div>

                                <div class="pro-6-img">
                                    <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                        @if (@$item['product_image']->image == null)
                                            <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                class="card-img-top rounded-0" alt="product image">
                                        @else
                                            <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                class="card-img-top rounded-0" alt="product image">
                                        @endif
                                    </a>
                                </div>

                                <div class="card-body pb-0">
                                    <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                        <h4 class="pro-6-title color-changer text-dark line-2">
                                            {{ $item->item_name }}
                                        </h4>
                                    </a>
                                </div>
                                <div class="card-footer">
                                    @if (@helper::checkaddons('product_reviews'))
                                        @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                            <p class="mb-2 rating-star cursor-pointer cursor-pointer"
                                                onclick="rattingmodal('{{ $item->id }}','{{ $storeinfo->id }}','{{ $item->item_name }}')">
                                                <i class="fa-solid fa-star text-warning"></i>
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
                                    <div class="d-flex align-items-center justify-content-between">
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
                                                    <p class="in-stock-text">{{ trans('labels.in_stock') }}</p>
                                                </div>
                                            @endif
                                        @endif
                                        <button class="btn btn-cart m-0"
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
            <div class="sec-header mb-4">
                <h3 class="d-flex justify-content-center color-changer align-items-center main-title text-center m-0">
                    <span class="dots"></span>
                    {{ trans('labels.our_latest_blogs') }}
                    <span class="dots"></span>
                </h3>
                <p class="m-0 line-2 fs-15 text-center mt-2 fw-500 text-muted">
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
                        <div class="blog-6 owl-carousel owl-theme overflow-hidden">
                            @foreach ($blog as $blog)
                                <div class="item h-100 mx-1">
                                    <div class="card border h-100 rounded-0">
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
                                        <div class="card-body">
                                            <h4 class="title line-2">
                                                <a
                                                    href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}" class="text-primary">{{ $blog->title }}</a>
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
                    <div class="blog-6 owl-carousel owl-theme overflow-hidden">
                        @foreach ($blog as $blog)
                            <div class="item h-100 mx-1">
                                <div class="card border h-100 rounded-0">
                                    <div class="blog-6-img">
                                        <a href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog[$i]->slug) }}">
                                            <img src="{{ helper::image_path($blog[$i]->image) }}" height="300"
                                                alt="blog img" class="w-100 object-fit-cover">
                                        </a>
                                        <div class="post-image-hover">
                                            <a href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog[$i]->slug) }}"
                                                class="blog-btn">
                                                <i class="fa-regular fa-link"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h4 class="title line-2">
                                            <a
                                                href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog[$i]->slug) }}">{{ $blog[$i]->title }}</a>
                                        </h4>
                                        <span class="blog-created">
                                            <i class="fa-regular fa-calendar-days"></i>
                                            <span
                                                class="date">{{ helper::date_format($blog[$i]->created_at) }}</span>
                                        </span>
                                        <div class="description line-2">{!! Str::limit($blog[$i]->description, 200) !!}</div>
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

<!-- newsletter -->
@include('front.newsletter')

<!-- footer -->
@include('front.theme.footer')
