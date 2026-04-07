@if (helper::appdata($storeinfo->id)->cart_checkout_progressbar == 1)
    @if (helper::appdata($storeinfo->id)->min_order_amount_for_free_shipping > 0)
        <div class="py-3 my-3">
            @php
                $percentage = round(
                    ($subtotal / helper::appdata($storeinfo->id)->min_order_amount_for_free_shipping) * 100,
                );
            @endphp

            <div class="progress" role="progressbar" aria-label="Animated striped example" aria-valuenow="25"
                aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar progress-bar-striped progress-bar-animated"
                    style="width: {{ $percentage }}%; background-color: @if ($percentage <= 33) var(--bs-danger) @elseif($percentage > 33 && $percentage <= 66) var(--bs-warning) @else var(--bs-success) @endif">
                    <div class="w-100 d-flex justify-content-end">
                        <i
                            class="fa-solid fa-truck-fast col-auto fs-5 text-light {{ session()->get('direction') == 2 ? 'glyphicon' : '' }}"></i>
                    </div>
                </div>
            </div>

            @php
                $updatedprice = helper::appdata($storeinfo->id)->min_order_amount_for_free_shipping - $subtotal;
                $pvar = ['{price}'];
                $pnewvar = [helper::currency_formate($updatedprice, @$storeinfo->id)];

                $progress_message = str_replace($pvar, $pnewvar, helper::appdata($storeinfo->id)->progress_message);
            @endphp

            @if ($updatedprice <= 0)
                <p class="fs-7 mt-3 fw-bold text-success text-capitalize">
                    {{ helper::appdata($storeinfo->id)->progress_message_end }}</p>
            @else
                <p class="fs-7 mt-3 fw-bold color-changer text-capitalize">{{ $progress_message }}</p>
            @endif
        </div>
    @endif
@endif
