
<div class="table-responsive">
    <table class="table table-bordered" id='tblvariants'>
        <thead>
        <tr class="text-center align-middle fs-15 fw-600">

            @foreach($variantArray as $variant)
                <th><span class="fs-15 fw-600">{{ ucwords($variant) }}</span></th>
            @endforeach
            <th><span class="fs-15 fw-600">{{ trans('labels.original_price')  }}</span></th>
            <th><span class="fs-15 fw-600">{{ trans('labels.selling_price')  }}</span></th>
            <th><span class="fs-15 fw-600">{{ trans('labels.stock_qty')  }}</span></th>
            <th><span class="fs-15 fw-600">{{ trans('labels.min_order_qty')  }}</span></th>
            <th><span class="fs-15 fw-600">{{ trans('labels.max_order_qty') }}</span></th>
            <th><span class="fs-15 fw-600">{{ trans('labels.product_low_qty_warning') }}</span></th>
            <th><span class="fs-15 fw-600">{{ trans('labels.stock_management') }}</span></th>
            <th><span class="fs-15 fw-600">{{ trans('labels.is_available') }}</span></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
            @php
                $io=0;
            @endphp
            @foreach($possibilities as $counter => $possibility)
            @php
                $name = App\Models\Variants::variant_name($possibility, $io, $item_id);
                
                if ($name['has_variant'] == 0) {
                    $io++;
                }
                @endphp
            <tr class="fs-7 fw-500 align-middle">
                @foreach(explode('|', $possibility) as $key => $values)
                    <td>
                        <input type="text" autocomplete="off" spellcheck="false" class="form-control" value="{{ $values }}" name="{{ !empty($name['has_name'][$key]) ? $name['has_name'][$key] : $name['has_name'][0] }}" readonly>
                        <input name="{{ !empty($name['has_name'][$key]) ? $name['has_name'][$key] : $name['has_name'][0] }}" type="hidden" value="{{$possibility}}">
                    </td>
                @endforeach
                <td>
                    <input type="number" step="any" id="voriginal_price_{{ $counter }}" autocomplete="off" spellcheck="false" placeholder="{{ trans('labels.original_price')  }}" class="form-control"
                    name="{{ $name['original_price'] }}" value="{{ $name['original_price_val'] }}" required>
                </td>
                <td>
                    <input type="number" step="any" id="vprice_{{ $counter }}" autocomplete="off" spellcheck="false" placeholder="{{ trans('labels.selling_price')  }}" class="form-control"
                    name="{{ $name['price'] }}" value="{{ $name['price_val'] }}" required>
                </td>

                <td>
                    <input type="text" onkeypress="allowNumbersOnly(event)" id="vqty_{{ $counter }}" autocomplete="off" spellcheck="false" placeholder="{{ trans('labels.stock_qty')  }}" class="form-control"
                    name="{{ $name['qty'] }}" value="{{ $name['qty_val'] }}">
                </td>
                <td>
                    <input type="text" onkeypress="allowNumbersOnly(event)" id="vmin_order_{{ $counter }}" autocomplete="off" spellcheck="false" placeholder="{{ trans('labels.min_order_qty')  }}" class="form-control"
                    name="{{ $name['min_order'] }}" value="{{ $name['min_order_val'] }}">
                </td>
                <td>
                    <input type="text" onkeypress="allowNumbersOnly(event)"  id="vmax_order_{{ $counter }}" autocomplete="off" spellcheck="false" placeholder="{{ trans('labels.max_order_qty') }}" class="form-control"
                    name="{{ $name['max_order'] }}" value="{{ $name['max_order_val'] }}">
                </td>
                <td>
                    <input type="text" onkeypress="allowNumbersOnly(event)" id="vlow_qty_{{ $counter }}" autocomplete="off" spellcheck="false" placeholder="{{ trans('labels.low_qty') }}" class="form-control"
                    name="{{ $name['low_qty'] }}" value="{{ $name['low_qty_val'] }}">
                </td>
                <td class="text-center">
                    <input type="checkbox" id="vstockmanagement_{{ $counter }}" class="form-check-input" 
                    name="{{ $name['stock_management'] }}" value="1" {{ $name['stock_management_val'] == 1 ? 'checked' : ''}}  onclick="edit_stock_management(this.id)">
                </td>
                <td class="text-center">
                    <input type="checkbox" id="{{ $counter }}" class="form-check-input product_available" id="{{$counter}}" onclick="edit_checkavailable(this.id)"
                    name="{{ $name['is_available'] }}" value="1" {{ $name['is_available_val'] == 1 ? 'checked' : ''}}  {{ $name['is_available_val'] == 1 ? 'checked' : ''}}>
                </td>
              
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
