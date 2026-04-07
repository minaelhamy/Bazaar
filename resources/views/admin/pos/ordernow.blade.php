@php
    if (Auth::user()->type == 4) {
        $vendor_id = Auth::user()->vendor_id;
    } else {
        $vendor_id = Auth::user()->id;
    }
@endphp
<div class="modal-header py-2">
    <div class="modal-title d-flex justify-content-between col-12 fs-5" id="exampleModalLabel">
        <div class="col-lg-11 text-center">
            <div class="fw-semibold text-dark color-changer my-3">
                {{ trans('labels.order_confirmation') }}
            </div>
        </div>
        <div class="col-lg-1 d-flex justify-content-end">
            <button type="button" class="bg-transparent border-0" data-bs-dismiss="modal">
                <i class="fa-regular fa-xmark color-changer fs-4"></i>
            </button>
        </div>
    </div>
</div>
<div class="modal-body">
    <div class="col-12">
        <div class="col-12 py-2 border-bottom">
            <p class="m-0 mb-1 fs-7 text-dark color-changer fw-medium"> {{ trans('labels.order_information') }} </p>
        </div>
        <div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr class="align-middle">
                            <td class="fs-13">
                                {{ trans('labels.items') }}
                            </td>
                            <td class="fs-13 text-{{ session()->get('direction') == 2 ? 'start' : 'end' }}">
                                {{ trans('labels.qty') }}
                            </td>
                            <td class="fs-13 text-{{ session()->get('direction') == 2 ? 'start' : 'end' }}">
                                {{ trans('labels.price') }}
                            </td>
                            <td class="fs-13 text-{{ session()->get('direction') == 2 ? 'start' : 'end' }}">
                                {{ trans('labels.sub_total') }}
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartitems as $item)
                            <tr class="align-middle">
                                <td class="py-3">
                                    <h6 class="line-2 m-0 fw-500 fs-13">{{ $item->item_name }}</h6>
                                    <p class="m-0 line-2 text-muted fs-8">{{ $item->extras_name }}</p>
                                </td>
                                <td class="py-3 text-{{ session()->get('direction') == 2 ? 'start' : 'end' }}">
                                    <div class="product-text-size d-flex align-items-center justify-content-end">
                                        <p class="m-0 fw-500 text-dark td_a">{{ $item->qty }}</p>
                                    </div>
                                </td>
                                <td class="py-3 text-{{ session()->get('direction') == 2 ? 'start' : 'end' }}">
                                    <p class="fw-500 text-dark line-1 td_a m-0 product-text-size">
                                    {{ helper::currency_formate($item->price, $vendor_id) }}</p>
                                </td>
                                <td class="py-3 text-{{ session()->get('direction') == 2 ? 'start' : 'end' }}">
                                    @php
                                        $subtotal = $item->item_price * $item->qty;
                                    @endphp
                                    <p class="fw-500 text-dark line-1 td_a m-0 product-text-size">
                                       {{ helper::currency_formate($subtotal, $vendor_id) }}</p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-12 my-3 d-flex flex-column flex-md-row justify-content-between">
        <div class="col-12 col-md-6 py-2 px-2 bg-light-subtle card rounded notes-box">
            <div class="col-12">
                <form>
                    <label for="message-text" class="col-form-label fs-7 text-dark color-changer fw-medium">
                        {{ trans('labels.order_note') }} </label>
                    <textarea class="form-control modal-price text-area" placeholder="Add note(with extra Instructions) "
                        id="cart_order_note" style="height: 100px"></textarea>
                    <!-- <textarea class="form-control modal-price" id="message-text" placeholder="Add note(with extra Instructions) "></textarea> -->
                </form>
            </div>
        </div>
        <div class="col-12 col-md-6 mt-2 mt-md-0 d-flex flex-column justify-content-between">
            <div class="col-12 py-2 px-2">
                <div class="d-flex justify-content-between my-1 py-1">
                    <span class="fw-600 fs-13 color-changer">{{ trans('labels.sub_total') }}</span>
                    <span class="fw-600 fs-13 text-dark color-changer" id="ordersub_total"></span>
                </div>
                <div class="text-muted fw-500">
                    <div class="d-flex justify-content-between my-1">
                        <span class="text-sm color-changer" id="ordertax_name"></span>
                        <span class="text-sm color-changer" id="ordertax_rate"></span>
                        <span id="hiddentax_name" class="d-none color-changer"></span>
                        <span id="hiddentax_rate" class="d-none color-changer"></span>
                    </div>
                    <div class="d-flex justify-content-between my-1">
                        <span class="text-sm color-changer">{{ trans('labels.discount') }}</span>
                        <span class="text-sm color-changer" id="orderdiscount_amount"></span>
                    </div>
                </div>
                <div class="d-flex justify-content-between fs-7 border-top py-1">
                    <span class="fw-semibold text-success">{{ trans('labels.total') }}</span>
                    <span class="fw-semibold text-success" id="ordergrand_total"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 border-top py-3 border-bottom">
        <p class="m-0 pb-2 fs-7 text-dark color-changer fw-medium"> {{ trans('labels.customer_information') }} </p>
        <form class="row g-3 m-0">
            <div class="col-md-4 m-0 customer">
                <label for="validationCustom01" class="form-label mb-1 from-name"> {{ trans('labels.fullname') }}
                </label>
                <input type="text" class="form-control fs-8 py-2" placeholder="Full Name" id="customer_name"
                    value="{{ @$customers1->name }}" required>
                <span class="text-danger fs-7" id="customer_name_required"></span>

            </div>
            <div class="col-md-4 m-0 customer">
                <label for="validationCustom02" class="form-label mb-1 from-name">{{ trans('labels.email') }}</label>
                <input type="text" class="form-control fs-8 py-2" placeholder="Email" id="customer_email"
                    value="{{ @$customers1->email }}" required>
                <span class="text-danger fs-7" id="customer_email_required"></span>
            </div>
            <div class="col-md-4 m-0 customer">
                <label for="validationCustomUsername" class="form-label mb-1 from-name">
                    {{ trans('labels.mobile') }}
                </label>
                <input type="number" class="form-control fs-8 py-2" id="customer_phone"
                    aria-describedby="inputGroupPrepend" placeholder="Mobile" value="{{ @$customers1->mobile }}"
                    required>
                <span class="text-danger fs-7" id="customer_phone_required"></span>
            </div>
        </form>
    </div>
    <div class="col-12 pt-3">
        <p class="m-0 mb-1 fs-7 text-dark color-changer fw-medium"> {{ trans('labels.Payment_information') }} </p>
        <div class="col-12 d-flex gap-4">
            <div class="form-check form-check-inline m-0 d-flex p-0 align-items-center gap-2">
                <input class="form-check-input m-0 p-0" type="radio" name="payment_type" id="inlineRadio1"
                    value="1">
                <label class="form-check-label modal-price m-0 p-0 fw-500"
                    for="inlineRadio1">{{ trans('labels.cash') }}</label>
            </div>
            <div class="form-check form-check-inline m-0 p-0 d-flex align-items-center gap-2">
                <input class="form-check-input m-0 p-0" type="radio" name="payment_type" id="inlineRadio2"
                    value="0">
                <label class="form-check-label m-0 p-0 modal-price fw-500"
                    for="inlineRadio2">{{ trans('labels.online') }}</label>
            </div>
        </div>
        <span class="text-danger fs-7" id="payment_type_required"></span>
    </div>
</div>

<div class="modal-footer justify-content-center">
    <div class="col-12 m-0">
        <div class="row gx-2 align-items-center justify-content-between">
            <div class="col-6">
                <button type="button" class="btn btn-danger fw-500 close-btn border-0 fs-7 total-pay mt-2 mt-lg-0"
                    data-bs-dismiss="modal">{{ trans('labels.close') }}</button>
            </div>
            <div class="col-6">
                <button type="button"
                    class="btn btn-primary fw-500 border-0 fs-7 orderconfirmbtn total-pay mt-2 mt-lg-0"
                    onclick="placeorder('{{ URL::to('admin/pos/placeorder') }}')">{{ trans('labels.confirm') }}</button>
            </div>
        </div>
    </div>
</div>


@section('scripts')
    <script type="text/javascript">
        var title = "{{ trans('messages.are_you_sure') }}";
        var yes = "{{ trans('messages.yes') }}";
        var no = "{{ trans('messages.no') }}";
        var stock_message = "{{ trans('labels.out_of_stock') }}"
        var discounturl = "{{ URL::to('admin/pos/discount') }}";
        var removediscounturl = "{{ URL::to('admin/pos/remove-discount') }}";
        var discount_message = "{{ trans('messages.discount_message') }}";
        var vendor_id = "{{ $vendor_id }}";
        var add_to_cart = "{{ trans('labels.add_to_cart') }}";
        var order_now = "{{ trans('labels.order_now') }}";
        var confirm_order = "{{ trans('labels.confirm') }}";

        function currency_formate(price) {
            var price = price * {{ @helper::currencyinfo($vendordata->id)->exchange_rate }};
            if ("{{ @helper::currencyinfo($vendor_id)->currency_position }}" == "left") {

                if ("{{ helper::currencyinfo($vendor_id)->decimal_separator }}" == 1) {
                    if ("{{ helper::currencyinfo($vendor_id)->currency_space }}" == 1) {
                        return "{{ @helper::currencyinfo($vendor_id)->currency }}" + " " + parseFloat(price).toFixed(
                            "{{ helper::currencyinfo($vendor_id)->currency_formate }}");
                    } else {
                        return "{{ @helper::currencyinfo($vendor_id)->currency }}" + parseFloat(price).toFixed(
                            "{{ helper::currencyinfo($vendor_id)->currency_formate }}");
                    }

                } else {
                    if ("{{ helper::currencyinfo($vendor_id)->currency_space }}" == 1) {
                        var newprice = "{{ @helper::currencyinfo($vendor_id)->currency }}" + " " + (parseFloat(price)
                            .toFixed(
                                "{{ helper::currencyinfo($vendor_id)->currency_formate }}"));
                    } else {
                        var newprice = "{{ @helper::currencyinfo($vendor_id)->currency }}" + (parseFloat(price).toFixed(
                            "{{ helper::currencyinfo($vendor_id)->currency_formate }}"));
                    }
                    newprice = newprice.replace('.', ',');
                    return newprice;
                }
            } else {
                if ("{{ helper::currencyinfo($vendor_id)->decimal_separator }}" == 1) {
                    if ("{{ helper::currencyinfo($vendor_id)->currency_space }}" == 1) {
                        return parseFloat(price).toFixed("{{ helper::currencyinfo($vendor_id)->currency_formate }}") +
                            " " +
                            "{{ @helper::currencyinfo($vendor_id)->currency }}";
                    } else {
                        return parseFloat(price).toFixed("{{ helper::currencyinfo($vendor_id)->currency_formate }}") +
                            "{{ @helper::currencyinfo($vendor_id)->currency }}";
                    }
                } else {
                    if ("{{ helper::currencyinfo($vendor_id)->currency_space }}" == 1) {
                        var newprice = (parseFloat(price).toFixed(
                                "{{ helper::currencyinfo($vendor_id)->currency_formate }}")) +
                            " " + "{{ @helper::currencyinfo($vendor_id)->currency }}";
                    } else {
                        var newprice = (parseFloat(price).toFixed(
                                "{{ helper::currencyinfo($vendor_id)->currency_formate }}")) +
                            "{{ @helper::currencyinfo($vendor_id)->currency }}";
                    }
                    newprice = newprice.replace('.', ',');
                    return newprice;
                }
            }
        }
    </script>
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/pos_cartview.js') }}" type="text/javascript"></script>
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/pos.js') }}" type="text/javascript"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get references to the off-canvas and modal elements
            var offCanvas = document.getElementById("offCanvas");
            var modal = document.getElementById("orderButton");

            // Get references to the open buttons
            var openOffCanvasBtn = document.getElementById("openOffCanvas");
            var openModalBtn = document.getElementById("order");

            // Function to open off-canvas menu
            function openOffCanvas() {
                var offCanvasInstance = new bootstrap.Offcanvas(offCanvas);
                offCanvasInstance.show();
            }

            // Function to open modal
            function openModal() {
                var modalInstance = new bootstrap.Modal(modal);
                modalInstance.show();
            }

            // Event listener for open off-canvas button
            openOffCanvasBtn.addEventListener("click", openOffCanvas);

            // Event listener for open modal button
            openModalBtn.addEventListener("click", openModal);
        });

        var header = document.getElementById("myDIV");
        var btns = header.getElementsByClassName("sidebr-box");
        for (var i = 0; i < btns.length; i++) {
            btns[i].addEventListener("click", function() {
                var current = document.getElementsByClassName("actives");
                current[0].className = current[0].className.replace(" actives", "");
                this.className += " actives";
            });
        }
    </script>
@endsection
