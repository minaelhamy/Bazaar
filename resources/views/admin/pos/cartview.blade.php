@php
    if (Auth::user()->type == 4) {
        $vendor_id = Auth::user()->vendor_id;
    } else {
        $vendor_id = Auth::user()->id;
    }
@endphp
<div class="offcanvas offcanvas-end " id="cart-offCanvas" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <button type="button" class="closing-button-1 d-none d-md-block" data-bs-dismiss="offcanvas" aria-label="Close">
        <i class="fa-regular fa-xmark fs-4"></i>
    </button>
    <div class="offcanvas-header py-3 gap-1 px-2 gx-3 align-items-center">
        <select id="customer" class="form-select fs-7" aria-label="Default select example">
            <option value="0">Walk In Customers</option>
            @foreach ($customers as $customer)
                <option value="{{ @$customer->id }}">{{ @$customer->name }}</option>
            @endforeach
        </select>
        <button type="button" class="closing-button-2 border rounded bg-transparent d-block d-md-none"
            data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="fa-regular fa-xmark color-changer"></i>
        </button>
    </div>
    <div class="offcanvas-body p-0">
        <table class="table mb-0 table-hover" id="myTable">
            <thead>
                <tr class="border-top">
                    <th scope="col"></th>
                    <th scope="col" class="product-text-size fw-500 ps-0">{{ trans('labels.items') }}</th>
                    <th scope="col" class="product-text-size fw-500 text-center"> {{ trans('labels.qty') }}</th>
                    <th scope="col" class="product-text-size fw-500">{{ trans('labels.price') }}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @foreach ($cartitems as $item)
                    <tr class="align-middle">
                        <td class="pe-sm-2 py-3 md-2 pe-0">
                            <a @if (env('Environment') == 'sendbox') onclick="myFunction()" @else  onclick="deletedata('{{ URL::to('admin/pos/cartview/delete-' . $item->id) }}')" @endif
                                tooltip="{{ trans('labels.delete') }}" class="btn btn-danger hov btn-sm"> <i
                                    class="fa-regular fa-trash"></i>
                            </a>
                        </td>
                        <td class="ps-1 ps-sm-0 py-3">
                            <div class="text-dark">
                                <h6 class="line-1 m-0 product-text-size fw-600 color-changer">{{ $item->item_name }}</h6>
                                <p class="m-0 line-1 product-text-size text-muted">{{ $item->variants_name }}@if ($item->variants_price != 0)
                                        ({{ helper::currency_formate($item->variants_price, $vendor_id) }})
                                    @endif
                                </p>
                                @php
                                    $extras_name = explode('|', $item->extras_name);
                                    $extras_price = explode('|', $item->extras_price);
                                    $total_extras_price = array_sum(array_map('floatval', $extras_price));
                                @endphp
                                @foreach ($extras_name as $index => $name)
                                    <p class="m-0 line-1 product-text-size text-muted">
                                        {{ $name }}
                                        @if (isset($extras_price[$index]) && $extras_price[$index] != '')
                                            ({{ helper::currency_formate($extras_price[$index], $vendor_id) }})
                                        @endif
                                    </p>
                                @endforeach
                            </div>
                        </td>
                        <td class="py-3">
                            <div class="price-range pb-2">
                                <div class="d-flex align-items-center justify-content-center">
                                    <a href="javascript:void(0);" class="circle"
                                        onclick="qtyupdate('{{ $item->id }}','minus','{{ URL::to('admin/pos/cart/qtyupdate') }}','{{ $item->item_id }}','{{ $item->variants_id }}','{{ $item->qty }}')">
                                        <i class="fa-light fa-minus"></i></a>
                                    <input type="text" class="color-changer fs-7" value="{{ $item->qty }}" readonly>
                                    <a href="javascript:void(0);" class="circle"
                                        onclick="qtyupdate('{{ $item->id }}','plus','{{ URL::to('admin/pos/cart/qtyupdate') }}','{{ $item->item_id }}','{{ $item->variants_id }}','{{ $item->qty }}')">
                                        <i class="fa-light fa-plus"></i></a>
                                </div>
                            </div>
                        </td>
                        @php
                            $variants_price = floatval($item->variants_price);
                            $extras_price = floatval($total_extras_price);
                            $itemtotal = ($variants_price + $extras_price) * $item->qty;
                            $total += $itemtotal;
                            $discount = floatval(session()->get('discount', 0));
                            $sub_total = $total - $discount;
                        @endphp
                        <td class="py-3">
                            <p class="fw-500 text-dark color-changer m-0 line-1 product-text-size itemtotal">
                                {{ helper::currency_formate($itemtotal, $vendor_id) }}</p>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    <div class="offcanvas-footer p-3">
        <form class="footer-form m-0">
            <div class="col-12 d-flex gap-1 fs-7">
                <div class="input-group gap-0 fs-8">
                    @if (session()->get('discount') > 0)
                        <input type="text" id="discount-input" class="form-control fs-7" placeholder="Add Discount"
                            aria-label="Add Discount" aria-describedby="button-addon2"
                            value="{{ session()->get('discount') }}" disabled>
                        <button class="btn btn-primary fw-500 border-0 text-light fs-7 rounded-end d-none"
                            type="button" id="apply-discount"> {{ trans('labels.apply') }} </button>
                        <button class="btn bg-danger fw-500 border-0 text-light fs-7 rounded-end " type="button"
                            id="remove-discount"> {{ trans('labels.remove') }} </button>
                    @else
                        <input type="text" id="discount-input" class="form-control fs-7" placeholder="Add Discount"
                            aria-label="Add Discount" aria-describedby="button-addon2">
                        <button class="btn btn-primary fw-500 border-0 text-light fs-7 rounded-end " type="button"
                            id="apply-discount"> {{ trans('labels.apply') }} </button>
                        <button class="btn bg-danger fw-500 border-0 text-light fs-7 rounded-end d-none" type="button"
                            id="remove-discount"> {{ trans('labels.remove') }} </button>
                    @endif


                </div>
            </div>

        </form>
        <div class="col-12 py-2">
            @php
                $taxtotal = 0;
                foreach ($taxArr['tax'] as $index => $name) {
                    $taxtotal += $taxArr['rate'][$index];
                }

                $grandTotal = $taxtotal + $total - session()->get('discount');
            @endphp

            <div class="d-flex justify-content-between my-1 py-1">
                <span class="fw-600 color-changer fs-7"> {{ trans('labels.sub_total') }} </span>
                <span
                    class="fw-semibold text-dark color-changer fs-7 sub_total d-none">{{ helper::currency_formate(@$sub_total, $vendor_id) }}</span>
                <span
                    class="fw-semibold text-dark color-changer fs-7 sub_total1">{{ helper::currency_formate($total, $vendor_id) }}</span>
            </div>

            <div class="text-muted fw-500">
                @foreach ($taxArr['tax'] as $index => $name)
                    <div class="d-flex justify-content-between my-1">
                        <span class="text-sm tax_name color-changer">{{ $name }}</span>
                        <span
                            class="text-sm tax-rate color-changer">{{ helper::currency_formate($taxArr['rate'][$index], $vendor_id) }}</span>
                    </div>
                @endforeach
                <div class="d-flex justify-content-between my-1">
                    <span class="text-sm color-changer"> {{ trans('labels.discount') }} </span>
                    <span class="text-sm discount color-changer"
                        id="discount_amount">-{{ helper::currency_formate(session()->get('discount'), $vendor_id) }}</span>
                </div>
            </div>

            <div class="d-flex justify-content-between fs-7 underline-2 py-1">
                <span class="fw-semibold text-dark color-changer">{{ trans('labels.total') }} </span>
                <span
                    class="fw-semibold text-dark color-changer grand_total">{{ helper::currency_formate($grandTotal, $vendor_id) }}</span>
            </div>
        </div>
        <div class="col-12">
            <div class="row gx-3 align-items-center justify-content-between mt-1">
                <div class="col-6">
                    <button id="deleteAllBtn"
                        class="total-pay Empty-cart fs-7 rounded fw-500 bg-danger text-light border-0"
                        onclick="RemoveCart('')">{{ trans('labels.empty_carts') }}</button>
                </div>
                <div class="col-6">
                    <button id="order" type="button" onclick="OrderNow('{{ URL::to('admin/pos/ordernow') }}')"
                        class="orderbtn total-pay btn btn-primary fs-7 rounded fw-500 text-light border-0">
                        {{ trans('labels.order_now') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/pos_cartview.js') }}" type="text/javascript"></script>
<script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/pos.js') }}" type="text/javascript"></script>
