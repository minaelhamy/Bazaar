
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
        </tr>
        </thead>
        <tbody>
            @foreach($possibilities as $counter => $possibility)
            <tr class="fs-7 fw-500 align-middle">
              
                @foreach(explode('|', $possibility) as $key => $values)
                    <td>
                        <input type="text" autocomplete="off" spellcheck="false" class="form-control" value="{{ $values }}" name="verians[{{$counter}}][name]" readonly>
                        <input type="hidden" autocomplete="off" spellcheck="false" class="form-control" value="{{ $possibility }}" name="verians[{{$counter}}][name]" readonly>
                    </td>
                @endforeach
                <td> 
                    <input type="number" step="any" id="voriginal_price_{{ $counter }}" placeholder="{{ trans('labels.original_price')  }}" class="form-control" name="verians[{{$counter}}][original_price]"  required>
                </td>
                <td>
                    <input type="number" step="any" id="vprice_{{ $counter }}" autocomplete="off" spellcheck="false" placeholder="{{ trans('labels.selling_price')  }}" class="form-control" name="verians[{{$counter}}][price]" required>
                </td>
               
                <td>
                    <input type="text"  onkeypress="allowNumbersOnly(event)" id="vquantity_{{ $counter }}" autocomplete="off" spellcheck="false" placeholder="{{ trans('labels.stock_qty')  }}" class="form-control" name="verians[{{$counter}}][qty]">
                </td>
                <td>
                    <input type="text" onkeypress="allowNumbersOnly(event)" id="vmin_order_{{ $counter }}" autocomplete="off" spellcheck="false" placeholder="{{ trans('labels.min_order_qty')  }}" class="form-control" name="verians[{{$counter}}][min_order]">
                </td>
                <td>
                    <input type="text" onkeypress="allowNumbersOnly(event)" id="vmax_order_{{ $counter }}" autocomplete="off" spellcheck="false" placeholder="{{ trans('labels.max_order_qty') }}" class="form-control" name="verians[{{$counter}}][max_order]">
                </td>
                <td>
                    <input type="text" onkeypress="allowNumbersOnly(event)" id="vlow_qty_{{ $counter }}" autocomplete="off" spellcheck="false" placeholder="{{ trans('labels.product_low_qty_warning') }} " class="form-control" name="verians[{{$counter}}][low_qty]">
                </td>
                <td class="text-center">
                    <input class="form-check-input stock_management" type="checkbox" value="1" onclick="stock_management(this.id)"
                    name="verians[{{$counter}}][stock_management]" id="vstockmanagement_{{ $counter }}">
                </td>
                <td class="text-center">
                    <input class="form-check-input product_available" type="checkbox" value="1" name="verians[{{$counter}}][is_available]" id="{{$counter}}" onclick="checkavailable(this.id)" checked>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
