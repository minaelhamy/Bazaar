<div class="view-cart-bar-2">
    <section class="view-cart-bar d-none">
        <div class="container">
            <div class="row g-2 align-items-center">
                <div class="col-xl-6 col-md-6">
                    <div class="d-flex gap-3 align-items-center">
                        <div class="product-img">
                            <img src="{{ helper::image_path($getitem['product_image']->image) }}" class="rounded">
                        </div>
                        <div>
                            <h5 class="text-dark color-changer line-2 fs-15 fw-600 my-1">
                                {{ $getitem->item_name }}</h5>
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
                </div>
                <div class="col-xl-6 col-md-6">
                    <div class="row g-md-3 g-2" id="detail_plus_minus">
                        @if (helper::appdata($storeinfo->id)->online_order == 1)
                            <div class="col-md-3 col-12">
                                <div class="input-group qty-input2 col-md-auto col-12 responsive-margin m-0 rounded-2">
                                    <button class="btn p-0 change-qty-1" id="minus" data-type="minus"
                                        data-item_id="{{ $getitem->id }}"
                                        onclick="changeqty($(this).attr('data-item_id'),'minus')" value="minus value"><i
                                            class="fa fa-minus"></i>
                                    </button>
                                    <input type="number" class="border text-center bg-transparent color-changer item_qty_{{ $getitem->id }}"
                                        name="number" value="1" readonly="">
                                    <button class="btn p-0 change-qty-1" id="plus"
                                        data-item_id="{{ $getitem->id }}"
                                        onclick="changeqty($(this).attr('data-item_id'),'plus')" data-type="plus"
                                        value="plus value"><i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        @endif

                        @if (helper::appdata($storeinfo->id)->online_order == 1)
                            <div class="col-md-4 col-12">
                                <button
                                    class="btn btn-store m-0 add-btn px-0 w-100 addtocart h-100 {{ $getitem->has_variants == 2 && $getitem->stock_management == 1 && $getitem->qty == 0 ? 'disabled' : '' }}"
                                    onclick="AddtoCart('0')">
                                    <span class="px-1">{{ trans('labels.add_to_cart') }}</span>
                                </button>
                            </div>
                            <div class="col-md-4 col-12">
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
                        <div class="col-md-1 col-12">
                            <button class="border border-dark bg-transparent m-0 h-100 rounded close-btn-view" id="close-btn2">
                                <i class="fa-regular fa-xmark fs-4 color-changer"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
