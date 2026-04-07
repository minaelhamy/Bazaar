@include('front.theme.header')
<!------ breadcrumb ------>
<section class="breadcrumb-sec bg-change-mode">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-dark"><a class="text-dark color-changer"
                        href="{{ helper::storefront_url($storeinfo, '/') }}">{{ trans('labels.home') }}</a>
                </li>
                <li class="text-muted breadcrumb-item {{ session()->get('direction') == 2 ? 'rtl' : '' }} active"
                    aria-current="page">{{ trans('labels.cart') }}
                </li>
            </ol>
        </nav>
    </div>
</section>
<div class="cart-sec">
    <div class="container">
        @if (count($cartdata) > 0)
            <div class="row">
                <div class="col-lg-12">
                    <div class="yourcart-sec">
                        @if (@helper::checkaddons('cart_checkout_countdown'))
                            @include('front.cart_checkout_countdown')
                        @endif

                        <!-- new product cart list -->
                        <div class="table-responsive">
                            <table class="table cart-table m-md-0">
                                <thead>
                                    <tr class="border-top">
                                        <th>{{ trans('labels.product') }}</th>
                                        <th>{{ trans('labels.price') }}</th>
                                        <th>{{ trans('labels.quantity') }}</th>
                                        <th>{{ trans('labels.total') }}</th>
                                        <th>{{ trans('labels.remove') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $subtotal = 0;
                                    @endphp
                                    @foreach ($cartdata as $cart)
                                        @php
                                            $subtotal += $cart->item_price * $cart->qty;
                                        @endphp
                                        <tr class="align-middle">
                                            <td>
                                                <div class="product-detail align-items-center">
                                                    <div class="pr-img">
                                                        <img src="{{ helper::image_path($cart->item_image) }}"
                                                            alt=""
                                                            class="img-fluid h-100 w-100 object-fit-cover">
                                                    </div>
                                                    <div class="details">
                                                        <div class="d-flex justify-content-between mb-2 mb-sm-0">
                                                            <div class="cart_title">
                                                                <a
                                                                    href="{{ helper::storefront_url($storeinfo, '/detail-' . helper::getmin_maxorder($cart->item_id, $storeinfo->id)->slug) }}" class="color-changer text-dark">
                                                                    <h5 class="cart-card-title card-font mb-1 line-2">
                                                                        {{ $cart->item_name }}
                                                                    </h5>
                                                                </a>
                                                                @if ($cart->variants_id != '' || $cart->extras_id != '')
                                                                    <li class="mb-2">
                                                                        <p>
                                                                            <span type="button" class="text-muted fs-7"
                                                                                onclick='showaddons("{{ $cart->id }}","{{ $cart->item_name }}","{{ $cart->attribute }}","{{ $cart->extras_name }}","{{ $cart->extras_price }}","{{ $cart->variants_name }}","{{ $cart->variants_price }}")'>
                                                                                {{ trans('labels.customize') }}
                                                                            </span>
                                                                        </p>
                                                                    </li>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="price">
                                                <p class="cart-total-price td_a m-0 text-left">
                                                    {{ helper::currency_formate($cart->item_price, $storeinfo->id) }}
                                                </p>
                                            </td>
                                            <td class="">
                                                <div
                                                    class="input-group qty-input2 qtu-width d-flex justify-content-between rounded-2 py-2 input-postion m-auto">
                                                    <button class="btn btn-sm py-0 change-qty cart-padding"
                                                        data-type="minus" value="minus value"
                                                        onclick="qtyupdate('{{ $cart->id }}','{{ $cart->item_id }}','{{ $cart->variants_id }}','{{ $cart->item_price }}','decreaseValue')">
                                                        <i class="fa fa-minus"></i>
                                                    </button>
                                                    <input type="text" class="border color-changer text-center bg-transparent"
                                                        id="number_{{ $cart->id }}" name="number"
                                                        value="{{ $cart->qty }}" min="1" max="10"
                                                        readonly>
                                                    <button class="btn btn-sm py-0 change-qty cart-padding"
                                                        data-type="plus" id="cart-plus"
                                                        onclick="qtyupdate('{{ $cart->id }}','{{ $cart->item_id }}','{{ $cart->variants_id }}','{{ $cart->item_price }}','increase')"
                                                        value="plus value"><i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="cart-total-price m-0 td_a text-left" id="total_price">
                                                    {{ helper::currency_formate($cart->price, $storeinfo->id) }}
                                                </p>
                                            </td>
                                            <td>
                                                <a onclick="RemoveCart('{{ $cart->id }}','{{ $storeinfo->id }}')"
                                                    tooltip="Remove"
                                                    class="item-delete text-danger py-1 px-2 col-xl-3 col-md-4 col-5 mx-auto cursor-pointer">
                                                    <i class="fa-light fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if (@helper::checkaddons('cart_checkout_progressbar'))
                            @include('front.cart_checkout_progressbar')
                        @endif

                        <!-- new product cart list -->
                        <div class="promo-code bg-changer d-md-flex justify-content-between align-items-center my-3">
                            <div class="cuppon-text text-center">
                                <span class="m-0 card-sub-total-text color-changer">{{ trans('labels.sub_total') }} :
                                    {{ helper::currency_formate($subtotal, $storeinfo->id) }}</span>
                            </div>
                            <div class="col-xxl-5 col-lg-6 col-md-8">
                                <div class="row justify-content-between align-items-center mt-2 mt-md-0 g-3">
                                    <!-- Continue Shopping btn -->
                                    <div class="col-sm-6 col-12 mt-3 mt-md-0">
                                        <a href="{{ helper::storefront_url($storeinfo) }}"
                                            class="btn btn-store-outline px-0">{{ trans('labels.continue_shoping') }}</a>
                                    </div>
                                    <!-- Continue Shopping btn -->
                                    <div class="col-sm-6 col-12 mt-3 mt-md-0">
                                        @if (@helper::checkaddons('customer_login'))
                                            @if (Auth::user() && Auth::user()->type == 3)
                                                <button class="btn btn-store w-100 cart_checkout"
                                                    onclick="checkminorderamount('{{ $subtotal }}','{{ helper::storefront_url($storeinfo, '/checkout?buy_now=0') }}')"><span>{{ trans('labels.checkout') }}</span></button>
                                            @else
                                                @if (helper::appdata($storeinfo->id)->checkout_login_required == 1)
                                                    <button type="button"
                                                        class="btn btn-store m-0 w-100 px-0 cart_checkout"
                                                        @if (helper::appdata($storeinfo->id)->is_checkout_login_required == 1) onclick="login()" @else onclick="checkminorderamount('{{ $subtotal }}','')" @endif>
                                                        {{ trans('labels.checkout') }}
                                                    </button>
                                                @else
                                                    <button class="btn btn-store m-0 w-100 px-0 cart_checkout"
                                                        onclick="checkminorderamount('{{ $subtotal }}','{{ helper::storefront_url($storeinfo, '/checkout?buy_now=0') }}')"><span>{{ trans('labels.checkout') }}</span></button>
                                                @endif
                                            @endif
                                        @else
                                            <button class="btn btn-store m-0 w-100 px-0 cart_checkout"
                                                onclick="checkminorderamount('{{ $subtotal }}','{{ helper::storefront_url($storeinfo, '/checkout?buy_now=0') }}')"><span>{{ trans('labels.checkout') }}</span></button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            @include('front.no_data')
        @endif
    </div>
</div>
<!-- newsletter -->
@include('front.newsletter')
<!-- newsletter -->

@include('front.theme.footer')

<script>
    var minorderamount = "{{ helper::appdata($storeinfo->id)->min_order_amount }}";
    var qtycheckurl = "{{ helper::storefront_url($storeinfo, '/qtycheckurl') }}";

    function checkminorderamount(subtotal, checkouturl) {
        $('.cart_checkout').prop("disabled", true);
        $('.cart_checkout').html('<span class="loader"></span>');
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            url: qtycheckurl,
            method: "post",
            data: {
                vendor_id: "{{ $storeinfo->id }}"
            },
            success: function(data) {
                if (data.status == 1) {
                    if (parseInt(minorderamount) <= parseInt(subtotal)) {
                        if (checkouturl != null && checkouturl != "") {
                            location.href = checkouturl;
                        } else {
                            $('#loginmodel').modal('show');
                            $("#loginmodel").on('hidden.bs.modal', function(e) {
                                $('.cart_checkout').prop("disabled", false);
                                $('.cart_checkout').html('{{ trans('labels.checkout') }}');
                            });
                        }
                    } else {
                        $('.cart_checkout').prop("disabled", false);
                        $('.cart_checkout').html('{{ trans('labels.checkout') }}');
                        toastr.error('{{ trans('messages.min_order_amount_required') }}' + minorderamount);
                    }
                } else {
                    $('.cart_checkout').prop("disabled", false);
                    $('.cart_checkout').html('{{ trans('labels.checkout') }}');
                    toastr.error(data.message);
                }
            },
            error: function() {
                $('.cart_checkout').prop("disabled", false);
                $('.cart_checkout').html('{{ trans('labels.checkout') }}');
                toastr.error(wrong);
                return false;
            }
        });
    }
</script>
