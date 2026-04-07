@include('front.theme.header')
<section class="breadcrumb-sec bg-change-mode">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-dark"><a class="text-dark color-changer"
                        href="{{ helper::storefront_url($storeinfo, '/') }}">{{ trans('labels.home') }}</a>
                </li>
                <li class="text-muted breadcrumb-item {{ session()->get('direction') == 2 ? 'rtl' : '' }} text-dark active"
                    aria-current="page">
                    {{ trans('labels.product_detail') }}
                </li>
            </ol>
        </nav>
    </div>
</section>


<section>
    <div class="container">
        @if ($item_check != null)
            <div class="row g-4 g-md-5 view-product">
                <div class="col-md-5 mb-sm-5 mb-3">
                    <div class="card card-bg h-100 overflow-hidden rounded-0 border-0 position-relative">
                        <!-- new big-view -->

                        <div class="sp-wrap ">
                            @foreach ($getitem['multi_image'] as $key => $image)
                                <a href="{{ $image->image_url }}"><img src="{{ helper::image_path($image->image) }}"
                                        alt=""></a>
                            @endforeach
                        </div>
                        <!-- new big-view -->
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card-body p-0 text-left">
                        @php
                            if ($getitem->top_deals == 1 && helper::top_deals($storeinfo->id) != null) {
                                if (@helper::top_deals($storeinfo->id)->offer_type == 1) {
                                    if ($getitem['variation']->count() > 0) {
                                        if (
                                            $getitem['variation'][0]->price >
                                            @helper::top_deals($storeinfo->id)->offer_amount
                                        ) {
                                            $price =
                                                $getitem['variation'][0]->price -
                                                @helper::top_deals($storeinfo->id)->offer_amount;
                                        } else {
                                            $price = $getitem['variation'][0]->price;
                                        }
                                    } else {
                                        if ($getitem->item_price > @helper::top_deals($storeinfo->id)->offer_amount) {
                                            $price =
                                                $getitem->item_price - @helper::top_deals($storeinfo->id)->offer_amount;
                                        } else {
                                            $price = $getitem->item_price;
                                        }
                                    }
                                } else {
                                    if ($getitem['variation']->count() > 0) {
                                        $price =
                                            $getitem['variation'][0]->price -
                                            $getitem['variation'][0]->price *
                                                (@helper::top_deals($storeinfo->id)->offer_amount / 100);
                                    } else {
                                        $price =
                                            $getitem->item_price -
                                            $getitem->item_price *
                                                (@helper::top_deals($storeinfo->id)->offer_amount / 100);
                                    }
                                }
                                if ($getitem['variation']->count() > 0) {
                                    $original_price = $getitem['variation'][0]->price;
                                } else {
                                    $original_price = $getitem->item_price;
                                }
                                $off =
                                    $original_price > 0 ? number_format(100 - ($price * 100) / $original_price, 1) : 0;
                            } else {
                                $price = $getitem->item_price;
                                $original_price = $getitem->original_price;
                                if ($getitem['variation']->count() > 0) {
                                    $price = $getitem['variation'][0]->price;
                                    $original_price = $getitem['variation'][0]->original_price;
                                } else {
                                    $price = $getitem->item_price;
                                    $original_price = $getitem->item_original_price;
                                }
                                $off =
                                    $original_price > 0 ? number_format(100 - ($price * 100) / $original_price, 1) : 0;
                            }
                        @endphp
                        @if ($off > 0)
                            <span class="badge text-bg-primary border fs-7 mb-2" id="offer">{{ $off }}%
                                {{ trans('labels.off') }}</span>
                        @endif

                        <p class="pro-title color-changer fs-4 fw-600 mb-sm-3 mb-2" id="item_name">
                            {{ $getitem->item_name }}</p>
                        <!-- category name and rating star -->
                        <div class="d-flex align-items-center justify-content-between mb-0">
                            <p id="laodertext" class="d-none laodertext"></p>
                            <div class="d-flex flex-wrap align-items-center product-detail-price">

                                @if ($getitem->is_available != 2 || $getitem->is_deleted == 1)

                                    <p class="pro-text color-changer pricing detail_item_price">
                                        {{ helper::currency_formate($price, $storeinfo->id) }}
                                    </p>
                                    @if ($original_price > $price)
                                        <del
                                            class="card-text pro-org-value text-muted pricing mb-0 px-1 detail_original_price">
                                            {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                        </del>
                                    @endif
                                @endif

                                @if ($getitem->has_variants == 2)
                                    @if ($getitem->is_available == 2 || $getitem->is_deleted == 1)
                                        <h3 class="text-danger">{{ trans('labels.not_available') }}</h3>
                                    @endif
                                @else
                                    <h3 class="text-danger" id="detail_not_available_text"></h3>
                                @endif
                            </div>

                            <!-- rating star -->
                            @if (@helper::checkaddons('product_reviews'))
                                @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                    <ul class="d-flex bg-gray px-2 py-1 rounded-2 align-items-center p-0 m-0 cursor-pointer"
                                        tooltip="View"
                                        onclick="rattingmodal('{{ $getitem->id }}','{{ $getitem->vendor_id }}','{{ $getitem->item_name }}')">
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="fa-solid fa-star text-warning fs-7"></i>
                                            <div id="ratting-div" class="fs-7 fw-semibold">
                                                <p class="px-1 avg-ratting">
                                                    {{ number_format($getitem->ratings_average, 1) }}
                                                </p>
                                            </div>
                                        </li>
                                    </ul>
                                @endif
                            @endif
                        </div>

                        @if ($getitem->is_available != 2 || $getitem->is_deleted == 1)
                            <p id="tax" class="responcive-tax text-left mb-1">

                                @if ($getitem->tax != null && $getitem->tax != '')
                                    <span class="text-danger fs-7"> {{ trans('labels.exclusive_taxes') }} </span>
                                @else
                                    <span class="text-success fs-7"> {{ trans('labels.inclusive_taxes') }}</span>
                                @endif

                            </p>
                        @endif
                        @if (@Helper::checkaddons('fake_view'))
                            @if (Helper::appdata($storeinfo->id)->product_fake_view == 1)
                                @php

                                    $var = ['{eye}', '{count}'];
                                    $newvar = [
                                        "<i class='fa-solid fa-eye'></i>",
                                        rand(
                                            Helper::appdata($storeinfo->id)->min_view_count,
                                            Helper::appdata($storeinfo->id)->max_view_count,
                                        ),
                                    ];

                                    $fake_view = str_replace(
                                        $var,
                                        $newvar,
                                        Helper::appdata($storeinfo->id)->fake_view_message,
                                    );
                                @endphp
                                <div class="d-flex gap-1 align-items-center blink_me mb-2">
                                    <p class="fw-600 text-success m-0">{!! $fake_view !!}</p>
                                </div>
                            @endif
                        @endif

                        <div
                            class=" border-bottom pb-3 {{ $getitem->sku != null || $getitem->stock_management == 1 || $getitem->attchment_url != null || $getitem->attchment_name != null ? 'd-block' : 'd-none' }}">
                            <div class="meta-content bg-gray bg-changer p-3 mt-3 rounded-2">
                                @if ($getitem->sku != '')
                                    <div class="sku-wrapper product_meta py-1"><span
                                            class="fs-7 fw-semibold color-changer">{{ trans('labels.sku') }}:
                                        </span><span class="text-muted fs-7">{{ $getitem->sku }}</span>
                                    </div>
                                @endif

                                @if ($getitem->has_variants == 2 && $getitem->stock_management == 1)
                                    <div class="sku-wrapper product_meta" id="stock">
                                        <span class="fs-7 fw-semibold color-changer">{{ trans('labels.stock') }}:
                                        </span>
                                        @if ($getitem->qty > 0)
                                            <span class="text-success fs-7">{{ $getitem->qty }}
                                                {{ trans('labels.in_stock') }}</span>
                                        @else
                                            <span class="text-danger fs-7"> {{ trans('labels.out_of_stock') }} </span>
                                        @endif
                                    </div>
                                @elseif ($getitem->has_variants == 1)
                                    <div class="sku-wrapper product_meta" id="stock">
                                        <span class="fs-7 color-changer">{{ trans('labels.stock') }}: </span>
                                        <span class="fs-7 text-muted" id="detail_out_of_stock"></span>
                                    </div>
                                @endif
                                @if ($getitem->attchment_url != '' && $getitem->attchment_url != null)
                                    <div>
                                        @if ($getitem->attchment_name != '' && $getitem->attchment_name != null)
                                            <a href="{{ $getitem->attchment_url }}" target="_blank">
                                                <p class="fs-7 d-flex align-items-center color-changer gap-2"><i
                                                        class="fa-light fa-file fs-6"></i>
                                                    {{ $getitem->attchment_name }}</p>
                                            </a>
                                        @else
                                            <a href="{{ $getitem->attchment_url }}" target="_blank">
                                                <p class="fs-7 d-flex align-items-center gap-2 color-changer"><i
                                                        class="fa-light fa-file fs-6"></i>
                                                    {{ trans('labels.click_here') }}</p>
                                            </a>
                                        @endif
                                    </div>
                                @endif
                                @if (@helper::otherappdata(@$storeinfo->id)->estimated_delivery_on_off == 1)
                                    <div class="sku-wrapper product_meta py-1">
                                        <span
                                            class="fs-7 fw-semibold color-changer">{{ trans('labels.estimated_delivery') }}
                                            :</span>
                                        <span class="text-muted fs-7">
                                            {{ helper::otherappdata(@$storeinfo->id)->days_of_estimated_delivery }}
                                            {{ trans('labels.days') }}
                                    </div>
                                @endif
                            </div>
                        </div>


                        @if ($getitem->has_variants == 1)
                            <div class="product-variations-wrapper">
                                <div class="size-variation detail_size_variation" id="detail_variation">

                                    @for ($i = 0; $i < count($getitem->variants_json); $i++)
                                        <h6 class="fw-semibold color-changer mt-3" for="">
                                            {{ $getitem->variants_json[$i]['variant_name'] }}</h6>
                                        <div class="d-flex flex-wrap gap-2 border-bottom pb-3 mt-3">
                                            @for ($t = 0; $t < count($getitem->variants_json[$i]['variant_options']); $t++)
                                                <label
                                                    class="checkbox-inline check{{ str_replace(' ', '_', $getitem->variants_json[$i]['variant_name']) }}"
                                                    id="check_{{ str_replace(' ', '_', $getitem->variants_json[$i]['variant_name']) }}-{{ str_replace(' ', '_', $getitem->variants_json[$i]['variant_options'][$t]) }}"
                                                    for="{{ str_replace(' ', '_', $getitem->variants_json[$i]['variant_name']) }}-{{ str_replace(' ', '_', $getitem->variants_json[$i]['variant_options'][$t]) }}">
                                                    <input type="checkbox" class="" name="skills"
                                                        {{ $t == 0 ? 'checked' : '' }}
                                                        value="{{ str_replace(' ', '_', $getitem->variants_json[$i]['variant_options'][$t]) }}"
                                                        id="{{ str_replace(' ', '_', $getitem->variants_json[$i]['variant_name']) }}-{{ str_replace(' ', '_', $getitem->variants_json[$i]['variant_options'][$t]) }}">
                                                    {{ $getitem->variants_json[$i]['variant_options'][$t] }}
                                                </label>
                                            @endfor
                                        </div>
                                    @endfor

                                </div>
                            </div>
                        @endif
                        @if (count($getitem['extras']) > 0)
                            <div class="woo_pr_color flex_inline_center my-3 border-bottom pb-3">
                                <div class="woo_colors_list text-left">
                                    <span id="extras">
                                        <h6 class="extra-title fw-semibold color-changer">{{ trans('labels.extras') }}
                                        </h6>
                                        <ul class="list-unstyled extra-food m-0">
                                            <div id="pricelist">
                                                @foreach ($getitem['extras'] as $key => $extras)
                                                    <li class="mb-2"><input type="checkbox" name="addons[]"
                                                            extras_name="{{ $extras->name }}" class="Checkbox"
                                                            value="{{ $extras->id }}" price="{{ $extras->price }}">
                                                        <p class="color-changer">{{ $extras->name }} :
                                                            {{ helper::currency_formate($extras->price, $getitem->vendor_id) }}
                                                        </p>
                                                    </li>
                                                @endforeach
                                            </div>
                                        </ul>
                                    </span>

                                </div>
                            </div>
                        @endif
                        @if ($getitem->top_deals == 1 && helper::top_deals($storeinfo->id) != null)
                            <div id="eapps-countdown-timer-1"
                                class="countdown rounded eapps-countdown-timer-align-center  eapps-countdown-timer-finish-button-show   eapps-countdown-timer-style-combined eapps-countdown-timer-style-blocks eapps-countdown-timer-position-bar eapps-countdown-timer-area-clickable eapps-countdown-timer-has-background">
                                <div class="eapps-countdown-timer-container">
                                    <div class="eapps-countdown-timer-inner col-12 ">
                                        <div class="d-flex flex-column gap-2 align-items-sm-start align-items-center">
                                            <div class="eapps-countdown-timer-header">
                                                <div class="eapps-countdown-timer-header-title">
                                                    <div
                                                        class="text-dark color-changer col-12 d-flex gap-2 align-items-center">
                                                        <i class="fa-regular fa-clock fs-6"></i>
                                                        <div class="line-2 fw-bolder">Hurry up!</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="eapps-countdown-timer-item-container">
                                                <div
                                                    class="eapps-countdown-timer-item d-flex gap-2 justify-content-center countdowntime">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($getitem->is_available != 2 || $getitem->is_deleted == 1)
                            <div class="row g-md-3 g-2 py-3" id="detail_plus_minus">
                                @if (helper::appdata($storeinfo->id)->online_order == 1)
                                    <div class="col-xl-3 col-6">
                                        <div
                                            class="input-group qty-input2 col-md-auto col-12 responsive-margin m-0 rounded-2">
                                            <button class="btn p-0 change-qty-1" id="minus" data-type="minus"
                                                data-item_id="{{ $getitem->id }}"
                                                onclick="changeqty($(this).attr('data-item_id'),'minus')"
                                                value="minus value"><i class="fa fa-minus"></i>
                                            </button>
                                            <input type="number"
                                                class="border text-center bg-transparent color-changer item_qty_{{ $getitem->id }}"
                                                name="number" value="1" readonly="">
                                            <button class="btn p-0 change-qty-1" id="plus"
                                                data-item_id="{{ $getitem->id }}"
                                                onclick="changeqty($(this).attr('data-item_id'),'plus')"
                                                data-type="plus" value="plus value"><i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                                @if (@helper::checkaddons('subscription'))
                                    @if (@helper::checkaddons('whatsapp_message'))
                                        @php
                                            $checkplan = App\Models\Transaction::where('vendor_id', $storeinfo->id)
                                                ->orderByDesc('id')
                                                ->first();
                                            $user = App\Models\User::where('id', $storeinfo->id)->first();
                                            if (@$user->allow_without_subscription == 1) {
                                                $whatsapp_message = 1;
                                            } else {
                                                $whatsapp_message = @$checkplan->whatsapp_message;
                                            }
                                        @endphp
                                        @if (
                                            $whatsapp_message == 1 &&
                                                @whatsapp_helper::whatsapp_message_config($storeinfo->id)->whatsapp_number != '' &&
                                                @whatsapp_helper::whatsapp_message_config($storeinfo->id)->whatsapp_number != null)
                                            <div class="col-xl-3 col-6">
                                                <a href="https://api.whatsapp.com/send?phone={{ @whatsapp_helper::whatsapp_message_config($storeinfo->id)->whatsapp_number }}&text={{ $getitem->item_name }}"
                                                    class="btn btn-enquir m-0 add-btn px-0 w-100 h-100" id="enquiries"
                                                    target="_blank">
                                                    <i
                                                        class="fa-brands fa-whatsapp fs-5 {{ session()->get('direction') == 2 ? 'glyphicon' : '' }}"></i>
                                                    <span class="px-1">{{ trans('labels.enquiries') }}</span>
                                                </a>
                                            </div>
                                        @endif
                                    @endif
                                @else
                                    @if (@helper::checkaddons('whatsapp_message'))
                                        <div class="col-xl-3 col-6">
                                            <a href="https://api.whatsapp.com/send?phone={{ @whatsapp_helper::whatsapp_message_config($storeinfo->id)->whatsapp_number }}&text={{ $getitem->item_name }}"
                                                class="btn btn-enquir m-0 add-btn px-0 w-100 h-100" id="enquiries"
                                                target="_blank">
                                                <i
                                                    class="fa-brands fa-whatsapp fs-5 {{ session()->get('direction') == 2 ? 'glyphicon' : '' }}"></i>
                                                <span class="px-1">{{ trans('labels.enquiries') }}</span>
                                            </a>
                                        </div>
                                    @endif
                                @endif

                                @if (helper::appdata($storeinfo->id)->online_order == 1)
                                    <div class="col-xl-3 col-6">
                                        <button
                                            class="btn btn-store m-0 add-btn px-0 w-100 addtocart h-100 {{ $getitem->has_variants == 2 && $getitem->stock_management == 1 && $getitem->qty == 0 ? 'disabled' : '' }}"
                                            onclick="AddtoCart('0')">
                                            <span class="px-1">{{ trans('labels.add_to_cart') }}</span>
                                        </button>
                                    </div>
                                    <div class="col-xl-3 col-6">
                                        @if (@helper::checkaddons('customer_login'))
                                            @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                <button
                                                    class="btn btn-store-outline m-0 px-0 add-btn w-100 buynow h-100 {{ $getitem->has_variants == 2 && $getitem->stock_management == 1 && $getitem->qty == 0 ? 'disabled' : '' }}"
                                                    @if (helper::appdata($storeinfo->id)->is_checkout_login_required == 1) onclick="login()" @else onclick="AddtoCart('1')" @endif>
                                                    <span class="px-1">{{ trans('labels.buy_now') }}</span>
                                                </button>
                                            @else
                                                <button
                                                    class="btn btn-store-outline m-0 px-0 add-btn w-100 buynow h-100 {{ $getitem->has_variants == 2 && $getitem->stock_management == 1 && $getitem->qty == 0 ? 'disabled' : '' }}"
                                                    onclick="AddtoCart('1')">
                                                    <span class="px-1">{{ trans('labels.buy_now') }}</span>
                                                </button>
                                            @endif
                                        @else
                                            <button
                                                class="btn btn-store-outline m-0 px-0 add-btn w-100 buynow h-100 {{ $getitem->has_variants == 2 && $getitem->stock_management == 1 && $getitem->qty == 0 ? 'disabled' : '' }}"
                                                onclick="AddtoCart('1')">
                                                <span class="px-1">{{ trans('labels.buy_now') }}</span>
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div
                            class="d-flex border-top flex-wrap pt-3 gap-sm-2 gap-3 justify-content-between w-100 mb-3">
                            <div
                                class="d-flex gap-sm-5 gap-2 justify-content-between align-items-center col-sm-auto col-12">
                                <div>

                                    @if (@helper::checkaddons('customer_login'))
                                        @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)

                                            <p class="fs-7 d-flex align-items-center">

                                                <a onclick="managefavorite('{{ $getitem->id }}',{{ $storeinfo->id }},'{{ helper::storefront_url($storeinfo, '/managefavorite') }}')"
                                                    class="btn-sm btn-Wishlist cursor-pointer {{ session()->get('direction') == 2 ? 'me-auto' : 'ms-auto' }}">
                                                    @if (Auth::user() && Auth::user()->type == 3)
                                                        @php

                                                            $favorite = helper::ceckfavorite(
                                                                $getitem->id,
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
                                                <span
                                                    class="color-changer mx-2">{{ trans('labels.add_to_wishlist') }}</span>
                                            </p>

                                        @endif
                                    @endif
                                </div>

                            </div>
                            <div>
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    @if ($getitem->video_url != '' && $getitem->video_url != null)
                                        <a href="{{ $getitem->video_url }}" class=" rounded-circle prod-social m-0"
                                            tooltip="Video" id="btn-video" target="_blank">
                                            <i class="fa-regular fa-video fs-7"></i></a>
                                    @endif

                                    @if (helper::appdata($storeinfo->id)->google_review != '' && helper::appdata($storeinfo->id)->google_review != null)
                                        <a href="{{ helper::appdata($storeinfo->id)->google_review }}"
                                            target="_blank" tooltip="Review"
                                            class=" rounded-circle prod-social fs-7">
                                            <i class="fa-regular fa-star"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if (@helper::checkaddons('frequently_bought_together'))
                            @if (count($frequently_bought_items) > 0)
                                <div class="row g-2 my-2 border-top pt-2" id="frequently_bought_items_section">
                                    <div class="col-12 mb-3">
                                        <h4 class="section-title color-changer line-1 m-0">
                                            {{ trans('labels.frequently_together') }}
                                        </h4>
                                        <div class="pt-2">
                                            <p class="fs-7 pages_subtitle text-muted line-1">
                                                {{ trans('labels.frequently_bought_items_description') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="item h-100">
                                            <div class="card border h-100">
                                                <img src="{{ helper::image_path($getitem['product_image']->image) }}"
                                                    class="card-img-top object frequently_img" height="230"
                                                    alt="...">
                                                <div class="woo_product_caption card-body">
                                                    <div class="woo_title">
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-2">
                                                            <p class="text-muted fs-8 mb-0">
                                                                {{ $getitem['category_info']->name }}
                                                            </p>
                                                            @if (@helper::checkaddons('product_reviews'))
                                                                <h6 class="fs-8 product_rating d-flex gap-1">
                                                                    <i class="fa fa-star filled text-warning"></i>
                                                                    <span class="color-changer">
                                                                        {{ number_format(@$getitem->ratings_average, 1) }}
                                                                    </span>
                                                                </h6>
                                                            @endif
                                                        </div>
                                                        <a
                                                            href="{{ helper::storefront_url($storeinfo, '/detail-' . $getitem->slug) }}">
                                                            <h5 class="color-changer text-dark fw-600 line-2 fs-7">
                                                                {{ $getitem->item_name }}</h5>
                                                        </a>
                                                    </div>
                                                    <div class="woo_price mt-3">
                                                        <div class="d-flex align-items-center flex-wrap gap-1">
                                                            <h6
                                                                class="color-changer fw-600 fs-7 detail_item_price mb-0">
                                                                {{ helper::currency_formate($price, $getitem->vendor_id) }}
                                                            </h6>
                                                            @if ($original_price > 0 && $original_price > $price)
                                                                <del
                                                                    class="text-muted fw-500 fs-8 detail_original_price">
                                                                    {{ helper::currency_formate($original_price, $getitem->vendor_id) }}
                                                                </del>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @foreach ($frequently_bought_items as $key => $frequently_bought_item)
                                        @php
                                            if (
                                                $frequently_bought_item->top_deals == 1 &&
                                                helper::top_deals($storeinfo->id) != null
                                            ) {
                                                if (@helper::top_deals($storeinfo->id)->offer_type == 1) {
                                                    if ($frequently_bought_item['variation']->count() > 0) {
                                                        if (
                                                            $frequently_bought_item['variation'][0]->price >
                                                            @helper::top_deals($storeinfo->id)->offer_amount
                                                        ) {
                                                            $fbtprice =
                                                                $frequently_bought_item['variation'][0]->price -
                                                                @helper::top_deals($storeinfo->id)->offer_amount;
                                                        } else {
                                                            $fbtprice = $frequently_bought_item['variation'][0]->price;
                                                        }
                                                    } else {
                                                        if (
                                                            $frequently_bought_item->item_price >
                                                            @helper::top_deals($storeinfo->id)->offer_amount
                                                        ) {
                                                            $fbtprice =
                                                                $frequently_bought_item->item_price -
                                                                @helper::top_deals($storeinfo->id)->offer_amount;
                                                        } else {
                                                            $fbtprice = $frequently_bought_item->item_price;
                                                        }
                                                    }
                                                } else {
                                                    if ($frequently_bought_item['variation']->count() > 0) {
                                                        $fbtprice =
                                                            $frequently_bought_item['variation'][0]->price -
                                                            $frequently_bought_item['variation'][0]->price *
                                                                (@helper::top_deals($storeinfo->id)->offer_amount /
                                                                    100);
                                                    } else {
                                                        $fbtprice =
                                                            $frequently_bought_item->item_price -
                                                            $frequently_bought_item->item_price *
                                                                (@helper::top_deals($storeinfo->id)->offer_amount /
                                                                    100);
                                                    }
                                                }
                                                if ($frequently_bought_item['variation']->count() > 0) {
                                                    $fbtoriginal_price = $frequently_bought_item['variation'][0]->price;
                                                } else {
                                                    $fbtoriginal_price = $frequently_bought_item->item_price;
                                                }
                                                $off =
                                                    $fbtoriginal_price > 0
                                                        ? number_format(100 - ($fbtprice * 100) / $fbtoriginal_price, 1)
                                                        : 0;
                                            } else {
                                                if ($frequently_bought_item['variation']->count() > 0) {
                                                    $fbtprice = $frequently_bought_item['variation'][0]->price;
                                                    $fbtoriginal_price =
                                                        $frequently_bought_item['variation'][0]->original_price;
                                                } else {
                                                    $fbtprice = $frequently_bought_item->item_price;
                                                    $fbtoriginal_price = $frequently_bought_item->item_original_price;
                                                }
                                                $off =
                                                    $fbtoriginal_price > 0
                                                        ? number_format(100 - ($fbtprice * 100) / $fbtoriginal_price, 1)
                                                        : 0;
                                            }
                                        @endphp
                                        <div class="col-md-4">
                                            <div class="item h-100">
                                                <div class="card h-100 border">
                                                    <div class="position-relative">
                                                        <img src="{{ helper::image_path($frequently_bought_item['product_image']->image) }}"
                                                            class="card-img-top object frequently_img image_opacity_{{ $frequently_bought_item->id }}"
                                                            height="230" alt="..." style="opacity: 0.6;">
                                                        <div
                                                            class="position-absolute top-0 {{ session()->get('direction') == 2 ? 'start-0' : 'end-0' }} p-1 d-flex justify-content-center align-items-center">
                                                            <input type="checkbox"
                                                                frequently_bought_items-price="{{ $fbtprice }}"
                                                                frequently_bought_items-id="{{ $frequently_bought_item->id }}"
                                                                onclick="add_frequently_product('{{ $getitem->id }}')"
                                                                class="form-check-input m-0 checkbox-custom-padding cursor-pointer frequently_bought_items_chk_{{ $getitem->id }}">
                                                        </div>
                                                    </div>
                                                    <div class="woo_product_caption card-body">
                                                        <div class="woo_title">
                                                            <div
                                                                class="d-flex justify-content-between align-items-center mb-2">
                                                                <p class="text-muted fs-8 mb-0">
                                                                    {{ $frequently_bought_item['category_info']->name }}
                                                                </p>
                                                                @if (@helper::checkaddons('product_reviews'))
                                                                    <h6 class="fs-8 product_rating d-flex gap-1">
                                                                        <i class="fa fa-star filled text-warning"></i>
                                                                        <span class="color-changer">
                                                                            {{ number_format($frequently_bought_item->ratings_average, 1) }}
                                                                        </span>
                                                                    </h6>
                                                                @endif
                                                            </div>
                                                            <a
                                                                href="{{ helper::storefront_url($storeinfo, '/detail-' . $frequently_bought_item->slug) }}">
                                                                <h5 class="color-changer text-dark fw-600 line-2 fs-7">
                                                                    {{ $frequently_bought_item->item_name }}</h5>
                                                            </a>
                                                        </div>
                                                        <div class="woo_price mt-3">
                                                            <div class="d-flex align-items-center flex-wrap gap-1">
                                                                <h6 class="color-changer fw-600 fs-7 mb-0 ">
                                                                    {{ helper::currency_formate($fbtprice, $getitem->vendor_id) }}
                                                                </h6>
                                                                @if ($fbtoriginal_price > 0 && $fbtoriginal_price > $fbtprice)
                                                                    <del class="text-muted fw-500 fs-8">
                                                                        {{ helper::currency_formate($fbtoriginal_price, $getitem->vendor_id) }}
                                                                    </del>
                                                                @endif
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div
                                        class="d-flex justify-content-between align-items-center py-3 mt-3 gap-3 border-top flex-wrap">
                                        <div class="d-flex flex-wrap align-items-center gap-3 d-none"
                                            id="addon_section">
                                            <div class="">
                                                <p class="fs-7 text-muted mb-0">1 {{ trans('labels.product') }}</p>
                                                <p class="color-changer fs-6 fw-600 detail_item_price">
                                                    {{ helper::currency_formate($price, $getitem->vendor_id) }}
                                                </p>
                                            </div>
                                            <i class="fa-solid fa-plus fs-6 color-changer"></i>
                                            <div>
                                                <p class="fs-7 text-muted mb-0" id="total_addon"></p>
                                                <p class="color-changer fs-6 fw-600" id="addon_total"></p>
                                            </div>
                                            <i class="fa-regular fa-equals fs-6 color-changer"></i>
                                            <div>
                                                <p class="fs-7 text-muted mb-0">{{ trans('labels.total') }}</p>
                                                <p class="color-changer fs-6 fw-600" id="total_price"></p>
                                            </div>
                                        </div>
                                        <div class="color-changer" id="addons_display_error">
                                            {{ trans('messages.please_add_at_least_addon_item_to_proceed') }}</div>
                                        <div class="col-lg-3">
                                            <button class="btn btn-store m-0 px-0 w-100 h-100  " id="addon_cart_btn"
                                                onclick="addonaddtocart('0','addon_add_cart')" disabled>
                                                <div id="total_product">{{ trans('labels.add_to_cart') }}
                                                </div>
                                                <div class="loader d-none" id="addon_cart_loader"></div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                        @include('front.service-trusted')

                        <input type="hidden" name="vendor" id="overview_vendor"
                            value="{{ $getitem->vendor_id }}">
                        <input type="hidden" name="item_id" id="overview_item_id" value="{{ $getitem->id }}">
                        <input type="hidden" name="item_name" id="overview_item_name"
                            value="{{ $getitem->item_name }}">
                        <input type="hidden" name="item_image" id="overview_item_image"
                            value="{{ @$getitem['product_image']->image }}">
                        <input type="hidden" name="item_min_order" id="item_min_order"
                            value="{{ $getitem->min_order }}">
                        <input type="hidden" name="item_max_order" id="item_max_order"
                            value="{{ $getitem->max_order }}">
                        <input type="hidden" name="item_price" id="overview_item_price"
                            value="{{ $price }}">
                        <input type="hidden" name="item_original_price" id="overview_item_original_price"
                            value ="{{ $original_price }}">
                        <input type="hidden" name="tax" id="tax_val" value="{{ $getitem->tax }}">
                        <input type="hidden" name="variants_name" id="variants_name">
                        <input type="hidden" name="stock_management" id="stock_management"
                            value="{{ $getitem->stock_management }}">
                    </div>
                </div>
            @else
                @include('front.no_data')
        @endif
    </div>
</section>

<section class="my-3">
    <div class="container">
        <div class="product-view mt-3" id="review-tab">
            <ul class="nav nav-pills py-3 border-bottom border-top gap-3" id="pills-tab" role="tablist">
                @if ($getitem->description != '')
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" href="javascript:void(0)" data-bs-toggle="pill"
                            data-bs-target="#description" aria-selected="false"
                            role="tab">{{ trans('labels.description') }}</a>
                    </li>
                @endif
                @if (@helper::checkaddons('product_inquiry'))
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" href="javascript:void(0)" data-bs-toggle="pill"
                            data-bs-target="#pills-product_inquiry" aria-selected="false"
                            role="tab">{{ trans('labels.product_inquiry') }}</a>
                    </li>
                @endif
                @if (@helper::checkaddons('question_answer'))
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" href="javascript:void(0)" data-bs-toggle="pill"
                            data-bs-target="#pills-quastions" aria-selected="false"
                            role="tab">{{ trans('labels.quastions_ans') }}</a>
                    </li>
                @endif
            </ul>
        </div>
        <div class="tab-content mt-3 pb-3 border-bottom" id="pills-tabContent">
            @if ($getitem->description != '')
                <div class="tab-pane fade active show" id="description" role="tabpanel"
                    aria-labelledby="description-tab">
                    <div class="card sevirce-trued">
                        <div class="card-body cms-section">
                            <p class="fw-500 fs-15">
                                {!! $getitem->description !!}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            @if (@helper::checkaddons('product_inquiry'))
                <div class="tab-pane fade show" id="pills-product_inquiry" role="tabpanel"
                    aria-labelledby="pills-product_inquiry-tab">
                    <div class="card sevirce-trued">
                        <div class="card-body">
                            <form action="{{ URL::to('product_inquiry') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $getitem->id }}">
                                <input type="hidden" name="vendor_id" value="{{ $getitem->vendor_id }}">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="first_name" class="form-label d-flex gap-1">
                                            {{ trans('labels.first_name') }}
                                            <div aria-hidden="true" class="text-danger">*</div>
                                        </label>
                                        <input type="text" class="form-control fs-7 input-h" id="first_name"
                                            name="first_name" placeholder="{{ trans('labels.first_name') }}"
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="last_name" class="form-label d-flex gap-1">
                                            {{ trans('labels.last_name') }}
                                            <div aria-hidden="true" class="text-danger">*</div>
                                        </label>
                                        <input type="text" class="form-control fs-7 input-h" name="last_name"
                                            placeholder="{{ trans('labels.last_name') }}" id="last_name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label d-flex gap-1">
                                            {{ trans('labels.email') }}
                                            <div aria-hidden="true" class="text-danger">*</div>
                                        </label>
                                        <input type="email" class="form-control fs-7 input-h" id="email"
                                            name="email" placeholder="{{ trans('labels.email') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="mobile" class="form-label d-flex gap-1">
                                            {{ trans('labels.mobile') }}
                                            <div aria-hidden="true" class="text-danger">*</div>
                                        </label>
                                        <input type="text" class="form-control fs-7 input-h number" name="mobile"
                                            placeholder="{{ trans('labels.mobile') }}" id="mobile" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="message" class="form-label d-flex gap-1">
                                            {{ trans('labels.comment') }}
                                            <div aria-hidden="true" class="text-danger">*</div>
                                        </label>
                                        <p class="fs-8 mb-1 text-muted">{{ trans('labels.note') }}
                                            {{ trans('messages.product_inquiry_note') }}</p>
                                        <textarea class="form-control fs-7 m-0" id="message" placeholder="{{ trans('labels.textarea') }}" name="message"
                                            rows="3" required></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-secondary py-2 px-5 fs-15 fw-500 m-0">
                                            {{ trans('labels.submit') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
            @if (@helper::checkaddons('question_answer'))
                <div class="tab-pane fade" id="pills-quastions" role="tabpanel"
                    aria-labelledby="pills-product_inquiry-tab">
                    <div class="card sevirce-trued">
                        <div class="card-body">
                            <div
                                class="d-flex align-items-center justify-content-between gap-2 mb-2 pb-2 border-bottom">
                                <p class="fs-7 line-1 color-changer">
                                    {{ trans('labels.have_doubts_regarding_this_product') }}</p>
                                <div class="col-auto">
                                    <a type="button" class="w-100 fw-600 text-primary color-changer rounded-0 p-0"
                                        data-bs-toggle="modal"
                                        data-bs-target="#qustions_answer">{{ trans('labels.post_your_question') }}</a>
                                </div>
                            </div>
                            @if (count($question_answer) > 0)
                                @foreach ($question_answer as $item)
                                    <div class="border-bottom p-2">
                                        <h6 class="fs-7 fw-600 line-2 color-changer">{{ $item->question }}
                                        </h6>
                                        <p class="fs-13  text-muted">{{ $item->answer }}
                                        </p>
                                    </div>
                                @endforeach
                            @else
                                @include('admin.layout.no_data')
                            @endif

                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

@if (@helper::checkaddons('sticky_cart_bar'))
    @include('front.view-cart-bar')
@endif
@if ($relateditem->count() > 0)
    <section class="my-5">
        <div class="container">
            <h4 class="m-0 fw-600 text-dark color-changer">{{ trans('labels.related_product') }}</h4>
            <div class="related-product recipe-card owl-carousel owl-theme product-prev-sec">
                @foreach ($relateditem as $key => $item)
                    <div class="item">
                        <div class="col custom-product-column">
                            <div class="pro-box box-shadow-none">
                                <div class="pro-img ">
                                    <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                        @if (@$item['product_image']->image == null)
                                            <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                alt="product image">
                                        @else
                                            <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                alt="product image">
                                        @endif
                                    </a>
                                    <div class="sale-heart">
                                        @php
                                            if ($item->top_deals == 1 && helper::top_deals($storeinfo->id) != null) {
                                                if (@helper::top_deals($storeinfo->id)->offer_type == 1) {
                                                    if ($item['variation']->count() > 0) {
                                                        if (
                                                            $item['variation'][0]->price >
                                                            @helper::top_deals($storeinfo->id)->offer_amount
                                                        ) {
                                                            $rprice =
                                                                $item['variation'][0]->price -
                                                                @helper::top_deals($storeinfo->id)->offer_amount;
                                                        } else {
                                                            $rprice = $item['variation'][0]->price;
                                                        }
                                                    } else {
                                                        if (
                                                            $item->item_price >
                                                            @helper::top_deals($storeinfo->id)->offer_amount
                                                        ) {
                                                            $rprice =
                                                                $item->item_price -
                                                                @helper::top_deals($storeinfo->id)->offer_amount;
                                                        } else {
                                                            $rprice = $item->item_price;
                                                        }
                                                    }
                                                } else {
                                                    if ($item['variation']->count() > 0) {
                                                        $rprice =
                                                            $item['variation'][0]->price -
                                                            $item['variation'][0]->price *
                                                                (@helper::top_deals($storeinfo->id)->offer_amount /
                                                                    100);
                                                    } else {
                                                        $rprice =
                                                            $item->item_price -
                                                            $item->item_price *
                                                                (@helper::top_deals($storeinfo->id)->offer_amount /
                                                                    100);
                                                    }
                                                }
                                                if ($item['variation']->count() > 0) {
                                                    $roriginal_price = $item['variation'][0]->price;
                                                } else {
                                                    $roriginal_price = $item->item_price;
                                                }
                                                $off =
                                                    $roriginal_price > 0
                                                        ? number_format(100 - ($rprice * 100) / $roriginal_price, 1)
                                                        : 0;
                                            } else {
                                                if ($item['variation']->count() > 0) {
                                                    $rprice = $item['variation'][0]->price;
                                                    $roriginal_price = $item['variation'][0]->original_price;
                                                } else {
                                                    $rprice = $item->item_price;
                                                    $roriginal_price = $item->item_original_price;
                                                }
                                                $off =
                                                    $roriginal_price > 0
                                                        ? number_format(100 - ($rprice * 100) / $roriginal_price, 1)
                                                        : 0;
                                            }
                                        @endphp
                                        @if ($off > 0)
                                            <div class="sale-label-on">{{ $off }}%
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
                                            {{ helper::currency_formate($rprice, $storeinfo->id) }}
                                        </p>
                                        @if ($roriginal_price > $rprice)
                                            <p class="pro-pricing pro-org-value line-1 m-0">
                                                {{ helper::currency_formate($roriginal_price, $storeinfo->id) }}
                                            </p>
                                        @endif

                                    </div>
                                    <button class="btn btn-sm m-0 py-1 w-100 btn-content rounded-5"
                                        id="verifybtn{{ $key }}"
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
@if (@helper::checkaddons('recent_view_product'))
    @if (@helper::otherappdata(@$storeinfo->id)->recent_view_product == 1)
        @if (count($recentProducts) > 0)
            <section class="my-5">
                <div class="container">
                    <h4 class="m-0 fw-600 text-dark color-changer">{{ trans('labels.recent_view_product') }}</h4>
                    <div class="related-product recipe-card owl-carousel owl-theme product-prev-sec">
                        @foreach ($recentProducts as $key => $item)
                            <div class="item">
                                <div class="col custom-product-column">
                                    <div class="pro-box box-shadow-none">
                                        <div class="pro-img ">
                                            <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $item->slug) }}">
                                                @if (@$item['product_image']->image == null)
                                                    <img src="{{ url(env('ASSETPATHURL') . 'admin-assets/images/about/defaultimages/item-placeholder.png') }}"
                                                        alt="product image">
                                                @else
                                                    <img src="{{ @helper::image_path($item['product_image']->image) }}"
                                                        alt="product image">
                                                @endif
                                            </a>
                                            <div class="sale-heart">
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
                                                                    $rprice =
                                                                        $item['variation'][0]->price -
                                                                        @helper::top_deals($storeinfo->id)
                                                                            ->offer_amount;
                                                                } else {
                                                                    $rprice = $item['variation'][0]->price;
                                                                }
                                                            } else {
                                                                if (
                                                                    $item->item_price >
                                                                    @helper::top_deals($storeinfo->id)->offer_amount
                                                                ) {
                                                                    $rprice =
                                                                        $item->item_price -
                                                                        @helper::top_deals($storeinfo->id)
                                                                            ->offer_amount;
                                                                } else {
                                                                    $rprice = $item->item_price;
                                                                }
                                                            }
                                                        } else {
                                                            if ($item['variation']->count() > 0) {
                                                                $rprice =
                                                                    $item['variation'][0]->price -
                                                                    $item['variation'][0]->price *
                                                                        (@helper::top_deals($storeinfo->id)
                                                                            ->offer_amount /
                                                                            100);
                                                            } else {
                                                                $rprice =
                                                                    $item->item_price -
                                                                    $item->item_price *
                                                                        (@helper::top_deals($storeinfo->id)
                                                                            ->offer_amount /
                                                                            100);
                                                            }
                                                        }
                                                        if ($item['variation']->count() > 0) {
                                                            $roriginal_price = $item['variation'][0]->price;
                                                        } else {
                                                            $roriginal_price = $item->item_price;
                                                        }
                                                        $off =
                                                            $roriginal_price > 0
                                                                ? number_format(
                                                                    100 - ($rprice * 100) / $roriginal_price,
                                                                    1,
                                                                )
                                                                : 0;
                                                    } else {
                                                        if ($item['variation']->count() > 0) {
                                                            $rprice = $item['variation'][0]->price;
                                                            $roriginal_price = $item['variation'][0]->original_price;
                                                        } else {
                                                            $rprice = $item->item_price;
                                                            $roriginal_price = $item->item_original_price;
                                                        }
                                                        $off =
                                                            $roriginal_price > 0
                                                                ? number_format(
                                                                    100 - ($rprice * 100) / $roriginal_price,
                                                                    1,
                                                                )
                                                                : 0;
                                                    }
                                                @endphp
                                                @if ($off > 0)
                                                    <div class="sale-label-on">{{ $off }}%
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
                                                    {{ helper::currency_formate($rprice, $storeinfo->id) }}
                                                </p>
                                                @if ($roriginal_price > $rprice)
                                                    <p class="pro-pricing pro-org-value line-1 m-0">
                                                        {{ helper::currency_formate($roriginal_price, $storeinfo->id) }}
                                                    </p>
                                                @endif

                                            </div>
                                            <button class="btn btn-sm m-0 py-1 w-100 btn-content rounded-5"
                                                id="verifybtn{{ $key }}"
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
@endif

<!-- question answer  Modal -->
<div class="modal fade" id="qustions_answer" tabindex="-1" aria-labelledby="qustions_answerLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header justify-content-between">
                <h1 class="modal-title fs-5 fw-600 m-0 color-changer" id="qustions_answer">
                    {{ trans('labels.ask_a_question') }}</h1>
                <button type="button" class="bg-transparent border-0 m-0" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i class="fa-regular fa-xmark fs-4 color-changer"></i>
                </button>
            </div>
            <form action="{{ helper::storefront_url($storeinfo, '/product_question_answer') }}" method="post"
                class="border-top ">
                @csrf
                <input type="hidden" name="question_answer_item_slug" id="question_answer_item_slug"
                    value="{{ $storeinfo->slug }}">
                <input type="hidden" name="question_answer_item_id" value="{{ $getitem->id }}">
                <div class="modal-body">
                    <div class="d-flex align-items-center gap-2">
                        <div>
                            <img src="{{ helper::image_path($getitem['product_image']->image) }}" alt=""
                                class="rounded" height="110px" width="110px">
                        </div>
                        <div class="w-100">
                            <h6 class="line-2 fs-15 fw-500 color-changer">
                                {{ @$getitem->item_name }}
                            </h6>
                            <div class="d-flex gap-1 flex-wrap align-items-center">
                                <p class="pro-text fs-7 detail_item_price">
                                    {{ helper::currency_formate($price, $storeinfo->id) }}
                                </p>
                                @if ($original_price > $price)
                                    <del
                                        class="card-text pro-org-value text-muted fs-8 mb-0 px-1 detail_original_price">
                                        {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                    </del>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="exampleFormControlTextarea1" class="form-label d-flex gap-1 mt-2">
                            {{ trans('labels.your_question') }}
                            <div aria-hidden="true" class="text-danger">*</div>
                        </label>
                        <input type="hidden" name="id" value="{{ @$service->id }}">
                        <input type="hidden" name="product_id" value="{{ @$product->id }}">
                        <textarea class="form-control m-0 fs-7" id="question" name="question" placeholder="Your Questions" rows="3"
                            required=""></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn fs-7  fw-500" data-bs-dismiss="modal"
                        style="background-color:#dc3545; color: white;">Close</button>
                    <button type="submit" class="btn fs-7  fw-500"
                        style="background-color:#5ca032; color: white;">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- newsletter -->
@include('front.newsletter')
@include('front.theme.footer')

<script>
    function add_frequently_product(id) {
        var item_price = $('#overview_item_price').val();
        var addonstotal = 0;

        var checkboxes = document.querySelectorAll(".frequently_bought_items_chk_" + id);
        var checkedCount = 0;

        checkboxes.forEach(function(el) {
            var fb_id = el.getAttribute("frequently_bought_items-id"); // extract product ID
            if (el.checked) {
                addonstotal += parseFloat(el.getAttribute("frequently_bought_items-price"));
                $('.image_opacity_' + fb_id).css("opacity", '1');
                checkedCount++;
            } else {
                $('.image_opacity_' + fb_id).css("opacity", '0.6');
            }
        });

        if (checkedCount > 0) {
            var subtotal = parseFloat(item_price) + parseFloat(addonstotal);
            $('#addons_display_error').addClass("d-none");
            $('#addon_section').removeClass("d-none");
            $('#total_product').text("{{ trans('labels.add') }} " + (checkedCount + 1) +
                " {{ trans('labels.item_to_cart') }}");
            $('#total_addon').text(checkedCount + " {{ trans('labels.addon') }}");
            $('#addon_total').text(currency_formate(addonstotal));
            $('#total_price').text(currency_formate(subtotal));
            $("#addon_cart_btn").prop('disabled', false);
        } else {
            $('#addon_section').addClass("d-none");
            $('#addons_display_error').removeClass("d-none");
            $('#total_product').text("{{ trans('labels.add_to_cart') }}");
            $("#addon_cart_btn").prop('disabled', true);
        }
    }
</script>
