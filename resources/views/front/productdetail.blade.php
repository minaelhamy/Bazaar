<div class="modal-body p-3 pro-modal">
    @if ($item_check != null)
        <div class="row gx-3">
            <div class="d-flex justify-content-end mb-2">
                <button type="button" class="bg-transparent border-0" data-bs-dismiss="modal">
                    <i class="fa-regular fa-xmark fs-4 color-changer"></i>
                </button>
            </div>
            @if (!request()->is('admin/pos/item-details'))
                <div class="col-lg-5">
                    <div id="carouseltest" class="carousel slide pb-3">
                        <div class="carousel-inner">
                            @foreach ($getitem['multi_image'] as $key => $image)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }} "
                                    name="image{{ $key }}">
                                    <img class="img-fluid w-100" src="{{ helper::image_path($image->image) }}">
                                </div>
                            @endforeach
                            @if (count($getitem['multi_image']) > 1)
                                <button class='carousel-control-prev' type='button' data-bs-target='#carouseltest'
                                    data-bs-slide='prev'>
                                    <span
                                        class='carousel-control-prev-icon d-flex justify-content-center align-items-center'
                                        aria-hidden='true'> <i class='fa-solid fa-arrow-left-long text-white'></i>
                                    </span>
                                </button>
                                <button class='carousel-control-next' type='button' data-bs-target='#carouseltest'
                                    data-bs-slide='next'>
                                    <span
                                        class='carousel-control-next-icon d-flex justify-content-center align-items-center'
                                        aria-hidden='true'> <i
                                            class='fa-solid fa-arrow-right-long text-white'></i></span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="{{ !request()->is('admin/pos/item-details') ? 'col-lg-7' : 'col-lg-12' }}">
                @php
                    if (
                        !request()->is('admin/pos/item-details') &&
                        $getitem->top_deals == 1 &&
                        helper::top_deals($storeinfo->id) != null
                    ) {
                        if (@helper::top_deals($storeinfo->id)->offer_type == 1) {
                            if ($getitem['variation']->count() > 0) {
                                if (
                                    $getitem['variation'][0]->price > @helper::top_deals($storeinfo->id)->offer_amount
                                ) {
                                    $price =
                                        $getitem['variation'][0]->price -
                                        @helper::top_deals($storeinfo->id)->offer_amount;
                                } else {
                                    $price = $getitem['variation'][0]->price;
                                }
                            } else {
                                if ($getitem->item_price > @helper::top_deals($storeinfo->id)->offer_amount) {
                                    $price = $getitem->item_price - @helper::top_deals($storeinfo->id)->offer_amount;
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
                                    $getitem->item_price * (@helper::top_deals($storeinfo->id)->offer_amount / 100);
                            }
                        }
                        if ($getitem['variation']->count() > 0) {
                            $original_price = $getitem['variation'][0]->price;
                        } else {
                            $original_price = $getitem->item_price;
                        }
                        $off = $original_price > 0 ? number_format(100 - ($price * 100) / $original_price, 1) : 0;
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
                        $off = $original_price > 0 ? number_format(100 - ($price * 100) / $original_price, 1) : 0;
                    }
                @endphp
                <div class="card-body repsonsive-cart-modal p-0 text-left">
                    @if ($off > 0)
                        <span class="badge text-bg-primary fs-7 mb-2" id="modal_offer">{{ $off }}%
                            {{ trans('labels.off') }}</span>
                    @endif

                    <p class="pro-title color-changer fs-4 fw-600 mb-sm-2 mb-2">{{ $getitem->item_name }}</p>
                    <!-- category name and rating star -->
                    <div class="d-flex align-items-center justify-content-between">
                        <p id="modal_laodertext" class="d-none laodertext"></p>
                        <div class="d-flex align-items-center modal-price modal-product-detail-price">

                            @if ($getitem->is_available != 2 || $getitem->is_deleted == 1)
                                <p class="pro-text pricing" id="modal_detail_item_price">
                                    {{ helper::currency_formate($price, $storeinfo->id) }}
                                </p>
                                @if ($original_price > $price)
                                    <p class="card-text pro-org-value text-muted pricing"
                                        id="modal_detail_original_price">
                                        {{ helper::currency_formate($original_price, $storeinfo->id) }}
                                    </p>
                                @endif
                            @endif

                            @if ($getitem->has_variants == 2)
                                @if ($getitem->is_available == 2 || $getitem->is_deleted == 1)
                                    <h3 class="text-danger">{{ trans('labels.not_available') }}</h3>
                                @endif
                            @else
                                <h3 class="text-danger" id="modal_detail_not_available_text"></h3>
                            @endif

                        </div>

                        <!-- rating star -->
                        @if (!request()->is('admin/pos/item-details'))
                            @if (@helper::checkaddons('product_reviews'))
                                @if (helper::appdata($storeinfo->id)->product_ratting_switch == 1)
                                    <ul class="d-flex bg-gray bg-changer px-2 py-1 rounded-2 align-items-center p-0 m-0 cursor-pointer"
                                        tooltip="View"
                                        onclick="rattingmodal('{{ $getitem->id }}','{{ $getitem->vendor_id }}','{{ $getitem->item_name }}')">
                                        <li class="d-flex align-items-center gap-1">
                                            <i class="fa-solid fa-star text-warning fs-7"></i>
                                            <div id="ratting-div" class="fs-7 fw-semibold">
                                                <p class="px-1 color-changer avg-ratting">
                                                    {{ number_format($getitem->ratings_average, 1) }}</p>
                                            </div>
                                        </li>
                                    </ul>
                                @endif
                            @endif
                        @endif
                    </div>
                    @if ($getitem->is_available != 2 || $getitem->is_deleted == 1)
                        <p id="modal_tax" class="responcive-tax text-left mb-1">
                            <span class="text-muted fs-7">
                                @if ($getitem->tax != null && $getitem->tax != '')
                                    <span class="text-danger fs-7"> {{ trans('labels.exclusive_taxes') }}</span>
                                @else
                                    <span class="text-success fs-7"> {{ trans('labels.inclusive_taxes') }}</span>
                                @endif
                            </span>
                        </p>
                    @endif
                    @if (!request()->is('admin/pos/item-details'))
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
                                <div class="border-bottom pb-2">
                                    <div class="d-flex gap-1 align-items-center blink_me mb-1">
                                        <p class="fw-600 text-success m-0">{!! $fake_view !!}</p>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endif
                    <input type="hidden" name="price" id="price" value="{{ $getitem->price }}">

                    <div class="border-bottom pb-3 {{ $getitem->sku != null || $getitem->stock_management == 1 || $getitem->attchment_url != null || $getitem->attchment_name != null ? 'd-block' : 'd-none' }}"
                        id="sku_stock">
                        <div class="meta-content bg-gray bg-changer p-3 mt-3 rounded-2">
                            @if ($getitem->sku != '' && $getitem->sku != null)
                                <div class="sku-wrapper product_meta ">
                                    <span class="fs-7" id="sku_lable">
                                        <span class="fw-semibold color-changer">{{ trans('labels.sku') }}</span>
                                        <span class="text-muted fs-7" id="sku_value">: {{ $getitem->sku }}</span>
                                    </span>
                                </div>
                            @endif

                            @if ($getitem->has_variants == 2 && $getitem->stock_management == 1)
                                <div class="sku-wrapper product_meta" id="modal_stock">
                                    <span class="fs-7"><span
                                            class="fw-semibold color-changer">{{ trans('labels.stock') }}:</span>
                                    </span>
                                    @if ($getitem->qty > 0)
                                        <span class="text-success fs-7">{{ $getitem->qty }}
                                            {{ trans('labels.in_stock') }}</span>
                                    @else
                                        <span class="text-danger fs-7">{{ trans('labels.out_of_stock') }}</span>
                                    @endif
                                </div>
                            @elseif ($getitem->has_variants == 1)
                                <div class="sku-wrapper product_meta" id="modal_stock">
                                    <span class="fs-7">
                                        <span class="fw-semibold color-changer">{{ trans('labels.stock') }}:</span>
                                    </span>
                                    <span class="fs-7" id="modal_detail_out_of_stock"></span>
                                </div>
                            @endif

                            @if ($getitem->attchment_url != '' && $getitem->attchment_url != null)
                                <div>
                                    @if ($getitem->attchment_name != '' && $getitem->attchment_name != null)
                                        <a href="{{ $getitem->attchment_url }}" target="_blank">
                                            <p class="fs-7 d-flex align-items-center gap-2">
                                                <i class="fa-light fa-file fs-6"></i>
                                                {{ $getitem->attchment_name }}
                                            </p>
                                        </a>
                                    @else
                                        <a href="{{ $getitem->attchment_url }}" target="_blank">
                                            <p class="fs-7 d-flex align-items-center gap-2">
                                                <i class="fa-light fa-file fs-6"></i>
                                                {{ trans('labels.click_here') }}
                                            </p>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($getitem->has_variants == 1)
                        <div class="product-variations-wrapper">
                            <div class="size-variation modal_size_variation" id="modal_variation">

                                @for ($i = 0; $i < count($getitem->variants_json); $i++)
                                    <label class="fw-semibold form-label mt-3"
                                        for="">{{ $getitem->variants_json[$i]['variant_name'] }}</label><br>
                                    <div class="d-flex flex-wrap gap-2 border-bottom pb-3">
                                        @for ($t = 0; $t < count($getitem->variants_json[$i]['variant_options']); $t++)
                                            <label
                                                class="checkbox-inline check{{ str_replace(' ', '_', $getitem->variants_json[$i]['variant_name']) }} {{ $t == 0 ? 'active' : '' }}"
                                                id="check_{{ str_replace(' ', '_', $getitem->variants_json[$i]['variant_name']) }}-{{ str_replace(' ', '_', $getitem->variants_json[$i]['variant_options'][$t]) }}"
                                                for="{{ str_replace(' ', '_', $getitem->variants_json[$i]['variant_name']) }}-{{ str_replace(' ', '_', $getitem->variants_json[$i]['variant_options'][$t]) }}">
                                                <input type="checkbox" class="" name="skills"
                                                    {{ $t == 0 ? 'checked' : '' }}
                                                    value="{{ $getitem->variants_json[$i]['variant_options'][$t] }}"
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
                                    <h5 class="extra-title fw-semibold color-changer mb-2">
                                        {{ trans('labels.extras') }}</h5>
                                    <ul class="list-unstyled extra-food m-0">
                                        <div id="pricelist">
                                            @foreach ($getitem['extras'] as $key => $extras)
                                                <li class="mb-2">
                                                    <input type="checkbox" name="addons[]"
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

                    @if ($getitem->is_available != 2 || $getitem->is_deleted == 1)
                        <div class="row border-bottom g-2 pb-3" id="modal_detail_plus_minus">
                            @if (helper::appdata($storeinfo->id)->online_order == 1)
                                <div
                                    class="{{ !request()->is('admin/pos/item-details') ? 'col-xxl-3 col-lg-6 col-md-3 col-6' : 'col-4' }}">
                                    <div
                                        class="input-group qty-input2 col-md-auto col-12 responsive-margin m-0 rounded-2 h-100">
                                        <button class="btn p-0 change-qty-2 h-100" id="minus" data-type="minus"
                                            data-item_id="{{ $getitem->id }}"
                                            onclick="changeqty($(this).attr('data-item_id'),'minus')"
                                            value="minus value"><i class="fa fa-minus"></i>
                                        </button>
                                        <input type="number" class="bg-transparent color-changer text-center item_qty_{{ $getitem->id }}"
                                            name="number" value="1" readonly="">
                                        <button class="btn p-0 change-qty-2 h-100" id="plus"
                                            data-item_id="{{ $getitem->id }}"
                                            onclick="changeqty($(this).attr('data-item_id'),'plus')" data-type="plus"
                                            value="plus value"><i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                            @if (!request()->is('admin/pos/item-details'))
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
                                            <div class="col-xxl-3 col-lg-6 col-md-3 col-6">
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
                                        <div class="col-xxl-3 col-lg-6 col-md-3 col-6">
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
                            @endif
                            @if (helper::appdata($storeinfo->id)->online_order == 1)
                                <div
                                    class="{{ !request()->is('admin/pos/item-details') ? 'col-xxl-3 col-lg-6 col-md-3 col-6' : 'col-8' }}">
                                    <button
                                        class="btn btn-store m-0 modal_add_btn addtocart w-100 px-0 {{ $getitem->has_variants == 2 && $getitem->stock_management == 1 && $getitem->qty == 0 ? 'disabled' : '' }}"
                                        @if (!request()->is('admin/pos/item-details')) onclick="AddtoCart('0')" @else onclick="posaddtocart()" @endif>
                                        <span class="px-1">{{ trans('labels.add_to_cart') }}</span>
                                    </button>
                                </div>
                                @if (!request()->is('admin/pos/item-details'))
                                    <div class="col-xxl-3 col-lg-6 col-md-3 col-6">
                                        @if (@helper::checkaddons('customer_login'))
                                            @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                <button
                                                    class="btn btn-store-outline m-0 modal_add_btn buynow w-100 px-0 {{ $getitem->has_variants == 2 && $getitem->stock_management == 1 && $getitem->qty == 0 ? 'disabled' : '' }}"
                                                    @if (helper::appdata($storeinfo->id)->is_checkout_login_required == 1) onclick="login()" @else onclick="AddtoCart('1')" @endif>
                                                    <span class="px-1">{{ trans('labels.buy_now') }}</span>
                                                </button>
                                            @else
                                                <button
                                                    class="btn btn-store-outline m-0 modal_add_btn buynow w-100 px-0 {{ $getitem->has_variants == 2 && $getitem->stock_management == 1 && $getitem->qty == 0 ? 'disabled' : '' }}"
                                                    onclick="AddtoCart('1')">
                                                    <span class="px-1">{{ trans('labels.buy_now') }}</span>
                                                </button>
                                            @endif
                                        @else
                                            <button
                                                class="btn btn-store-outline m-0 modal_add_btn buynow w-100 px-0 {{ $getitem->has_variants == 2 && $getitem->stock_management == 1 && $getitem->qty == 0 ? 'disabled' : '' }}"
                                                onclick="AddtoCart('1')">
                                                <span class="px-1">{{ trans('labels.buy_now') }}</span>
                                            </button>
                                        @endif

                                    </div>
                                @endif
                            @endif
                        </div>
                    @endif
                    @if (!request()->is('admin/pos/item-details'))
                        <div
                            class="d-flex flex-wrap gap-sm-2 gap-3 justify-content-between align-items-center w-100 mt-3">
                            @if (@helper::checkaddons('customer_login'))
                                @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                    <p class="fs-7 d-flex align-items-center m-0">
                                        <a onclick="managefavorite('{{ $getitem->id }}',{{ $storeinfo->id }},'{{ helper::storefront_url($storeinfo, '/managefavorite') }}')"
                                            class="btn-sm btn-Wishlist cursor-pointer {{ session()->get('direction') == 2 ? 'me-auto' : 'ms-auto' }}">
                                            <span class=" btn-sm btn-Wishlist mx-2">
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
                                            </span>
                                        </a>
                                        <span class="color-changer mx-2">{{ trans('labels.add_to_wishlist') }}</span>
                                    </p>
                                @endif
                            @endif
                            <div class="d-flex align-items-center justify-content-center gap-2">

                                @if ($getitem->video_url != '' && $getitem->video_url != null)
                                    <a href="{{ $getitem->video_url }}" tooltip="Video"
                                        class=" rounded-circle prod-social m-0" id="btn-video" target="_blank">
                                        <i class="fa-regular fa-video fs-7"></i></a>
                                @endif

                                @if (helper::appdata($storeinfo->id)->google_review != '' && helper::appdata($storeinfo->id)->google_review != null)
                                    <a href="{{ helper::appdata($storeinfo->id)->google_review }}" target="_blank"
                                        tooltip="Review" class=" rounded-circle prod-social m-0"><i
                                            class="fa-regular fa-star fs-7"></i></a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <input type="hidden" name="vendor" id="modal_overview_vendor"
                        value="{{ $getitem->vendor_id }}">
                    <input type="hidden" name="item_id" id="modal_overview_item_id" value="{{ $getitem->id }}">
                    <input type="hidden" name="item_name" id="modal_overview_item_name"
                        value="{{ $getitem->item_name }}">
                    <input type="hidden" name="item_image" id="modal_overview_item_image"
                        value="{{ @$getitem['product_image']->image }}">
                    <input type="hidden" name="item_min_order" id="modal_item_min_order"
                        value="{{ $getitem->min_order }}">
                    <input type="hidden" name="item_max_order" id="modal_item_max_order"
                        value="{{ $getitem->max_order }}">
                    <input type="hidden" name="item_price" id="modal_overview_item_price"
                        value="{{ $price }}">
                    <input type="hidden" name="item_original_price" id="modal_overview_item_original_price"
                        value ="{{ $original_price }}">
                    <input type="hidden" name="tax" id="modal_tax_val" value="{{ $getitem->tax }}">
                    <input type="hidden" name="variants_name" id="modal_variants_name">
                    <input type="hidden" name="stock_management" id="modal_stock_management"
                        value="{{ $getitem->stock_management }}">
                </div>
            </div>
        </div>
    @else
        @include('front.no_data')
    @endif
</div>
<script>
    $(document).ready(function($) {
        var selected = [];
        $('.modal_size_variation input:checked').each(function() {
            $("#modal_variation [id='" + 'check_' + this.id + "']").addClass('active');
            selected.push($(this).attr('value'));
        });
        set_variant_price(selected);
    });
    $('#modal_variation input:checkbox').click(function() {
        var selected = [];
        var divselected = [];
        const myArray = this.id.split("-");
        var id = this.id;
        $('#modal_variation .check' + myArray[0] + ' input:checked').each(function() {
            divselected.push($(this).attr('value'));
        });
        if (divselected.length == 0) {
            $(this).prop('checked', true);
        }
        $('#modal_variation .check' + myArray[0] + ' input:checkbox').not(this).prop('checked', false);
        $('#modal_variation .check' + myArray[0]).removeClass('active');
        $("#modal_variation [id='" + 'check_' + this.id + "']").addClass('active');
        $('.modal_size_variation input:checked').each(function() {
            $('.modal-product-detail-price').addClass('d-none');
            $('#modal_laodertext').removeClass('d-none');
            $('#modal_laodertext').html(
                '<span class="loader"></span>'
            );
            selected.push($(this).attr('value'));
        });
        set_variant_price(selected);
    });

    function set_variant_price(variants) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ !request()->is('admin/pos/item-details') ? URL::to('get-products-variant-quantity') : URL::to('admin/pos/get-products-variant-quantity') }}",
            data: {
                name: variants,
                item_id: $('#modal_overview_item_id').val(),
                vendor_id: {{ $storeinfo->id }},
            },
            success: function(data) {
                if (data.status == 1) {
                    setTimeout(function() {
                        $('#modal_laodertext').html('');
                    }, 4000);
                    var off = ((1 - (data.price / data.original_price)) * 100).toFixed(1);
                    $('#modal_laodertext').addClass('d-none');
                    $('.modal-product-detail-price').removeClass('d-none');
                    $('#modal_variants_name').val(variants);

                    $('#modal_detail_item_price').text(currency_formate(parseFloat(data.price)));
                    $('#modal_overview_item_price').val(data.price);
                    $('#modal_offer').removeClass('d-none');
                    if (parseFloat(data.original_price) > parseFloat(data.price)) {
                        $('#modal_detail_original_price').text(currency_formate(parseFloat(data
                            .original_price)));
                        $('#modal_offer').removeClass('d-none');
                        $('#modal_offer').text($.number(off, 1) + '% {{ trans('labels.off') }}');
                    } else {
                        $('#modal_detail_original_price').text('');
                        $('#modal_offer').addClass('d-none');
                        $('#modal_offer').text('');
                    }
                    $('#modal_overview_item_original_price').val(data.original_price);
                    $('#modal_stock_management').val(data.stock_management);
                    $('#modal_item_min_order').val(data.min_order);
                    $('#modal_item_max_order').val(data.max_order);
                    if (data.is_available == 2) {
                        $('#modal_offer').addClass('d-none');
                        $('#modal_detail_not_available_text').html('{{ trans('labels.not_available') }}');
                        $('.modal_add_btn').attr('disabled', true);
                        $('.modal_add_btn').addClass('d-none');
                        $('#modal_detail_item_price').addClass('d-none');
                        $('#modal_detail_original_price').addClass('d-none');
                        $('#modal_detail_plus_minus').addClass('d-none');
                        $('#modal_tax').addClass('d-none');
                        $('#modal_stock').addClass('d-none');
                    } else {
                        $('#modal_detail_not_available_text').html('');
                        $('#modal_offer').removeClass('d-none');
                        $('.modal_add_btn').attr('disabled', false);
                        $('.modal_add_btn').removeClass('d-none');
                        $('#modal_detail_item_price').removeClass('d-none');
                        $('#modal_detail_original_price').removeClass('d-none');
                        $('#modal_detail_plus_minus').removeClass('d-none');
                        $('#modal_tax').removeClass('d-none');
                        $('#modal_stock').addClass('d-none');
                        if (data.stock_management == 1) {
                            $('#modal_stock').removeClass('d-none');
                            $('#modal_detail_out_of_stock').removeClass('d-none');
                            if (data.quantity > 0) {
                                $('.modal_add_btn').attr('disabled', false);
                                $('#modal_detail_out_of_stock').removeClass('text-danger');
                                $('#modal_detail_out_of_stock').addClass('text-success');
                                $('#modal_detail_out_of_stock').html('(' + data.quantity +
                                    ' {{ trans('labels.in_stock') }})');
                            } else {
                                $('.modal_add_btn').attr('disabled', true);
                                $('#modal_detail_out_of_stock').removeClass('text-dark');
                                $('#modal_detail_out_of_stock').addClass('text-danger');
                                $('#modal_detail_out_of_stock').html(
                                    '({{ trans('labels.out_of_stock') }})');
                            }
                        } else {
                            $('#modal_detail_out_of_stock').addClass('d-none');
                        }

                    }
                }

            }
        });

    }
</script>
