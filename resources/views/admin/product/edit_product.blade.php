@extends('admin.layout.default')
@php
    if (Auth::user()->type == 4) {
        $vendor_id = Auth::user()->vendor_id;
    } else {
        $vendor_id = Auth::user()->id;
    }
@endphp
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{ trans('labels.edit') }}</h5>
        <div class="d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item text-dark"><a href="{{ URL::to('admin/products') }}"
                            class="color-changer">{{ trans('labels.products') }}</a></li>
                    <li class="breadcrumb-item  active {{ session()->get('direction') == 2 ? 'breadcrumb-rtl' : '' }}"
                        aria-current="page">{{ trans('labels.edit') }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 box-shadow">
                <div class="card-body">
                    @if (!empty($getproductdata))
                        <form action="{{ URL::to('admin/products/update-' . $getproductdata->slug) }}" method="POST"
                            enctype="multipart/form-data" class="m-0">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">{{ trans('labels.category') }} <span class="text-danger">
                                                *</span></label><br>
                                        <select class="form-control selectpicker" name="category[]" data-live-search="true"
                                            id="editcat_id" required>
                                            <option value="">{{ trans('labels.select') }}</option>
                                            @if (!empty($getcategorylist))
                                                @foreach ($getcategorylist as $catdata)
                                                    <option value="{{ $catdata->id }}" data-id="{{ $catdata->id }}"
                                                        {{ in_array($catdata->id, explode('|', $getproductdata->cat_id)) ? 'selected' : '' }}>
                                                        {{ $catdata->name }} </option>
                                                @endforeach
                                            @endif
                                        </select>

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">{{ trans('labels.name') }} <span class="text-danger">
                                                * </span></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="text" class="form-control" name="product_name" id="product_name"
                                                value="{{ $getproductdata->item_name }}"
                                                placeholder="{{ trans('labels.name') }}" required>
                                            @if (@helper::checkaddons('ai_settings'))
                                                {{-- AI Button: flex-shrink-0 lagaya taaki button chhota na ho --}}
                                                <button type="button" id="ai_btn"
                                                    class="btn btn-sm btn-primary d-none flex-shrink-0"
                                                    style="border-radius: 5px;" onclick="ai_generate()"  tooltip="{{ trans('labels.ai_generate') }}">
                                                    <i class="fa-solid fa-robot me-1"></i> {{ trans('labels.ai') }}
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">{{ trans('labels.sku') }}</label>
                                        <input type="text" class="form-control" name="product_sku"
                                            value="{{ $getproductdata->sku }}" placeholder="{{ trans('labels.sku') }}"
                                            id="product_sku">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ trans('labels.video_url') }} </label>
                                        <input class="form-control" type="text" name="video_url"
                                            placeholder="{{ trans('labels.video_url') }}"
                                            value="{{ $getproductdata->video_url }}">

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ trans('labels.tax') }}</label>
                                        <select name="tax[]" class="form-control selectpicker" multiple
                                            data-live-search="true">
                                            @if (!empty($gettaxlist))
                                                @foreach ($gettaxlist as $tax)
                                                    <option value="{{ $tax->id }}"
                                                        {{ in_array($tax->id, explode('|', $getproductdata->tax)) ? 'selected' : '' }}>
                                                        {{ $tax->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>

                                    </div>
                                </div>
                                @if (@helper::checkaddons('frequently_bought_together'))
                                    <div class="col-md-4">
                                        <div
                                            class="form-group add-extra-class {{ session()->get('direction') == 2 ? 'rtl' : '' }}">
                                            <label class="form-label">{{ trans('labels.frequently_bought_items') }}</label>
                                            <select name="frequently_bought_items[]" class="form-control selectpicker"
                                                id="frequently_bought_items" multiple data-live-search="true"
                                                onchange="change_frequently_bought_items()">
                                                @foreach ($getitems as $product)
                                                    <option value="{{ $product->id }}"
                                                        {{ in_array($product->id, explode('|', $getproductdata->frequently_bought_items)) ? 'selected' : '' }}>
                                                        {{ $product->item_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-form-label">{{ trans('labels.attachment_name') }} </label>
                                        <input type="text" class="form-control" name="attachment_name"
                                            id="attachment_name" placeholder="{{ trans('labels.attachment_name') }}"
                                            value="{{ $getproductdata->attchment_name }}">

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-form-label">{{ trans('labels.attachment_file') }}</label>
                                        <input type="file" class="form-control" name="attachment_file"
                                            id="attachment_file">
                                        <p class="text-danger mt-2">{{ trans('labels.attachment') }} : <span
                                                class="text-dark">
                                                <a href="{{ url(env('ASSETPATHURL') . 'admin-assets/images/product/' . $getproductdata->attchment_file) }}"
                                                    target="_blank">{{ $getproductdata->attchment_file }}</a></span></p>

                                    </div>
                                </div>
                                @if (@helper::checkaddons('digital_product'))
                                    @include('admin.product.digital_product')
                                @endif
                                <div class="col-md-12 form-group">
                                    <label class="form-label">{{ trans('labels.description') }}</label>
                                    <textarea class="form-control" id="ckeditor" name="description">{!! $getproductdata->description !!}</textarea>
                                </div>
                                <div
                                    class="col-12 border-bottom py-2 my-2 d-flex flex-wrap justify-content-between align-items-center">
                                    <div class="form-group">
                                        <label for="has_extras"
                                            class="col-form-label">{{ trans('labels.product_has_extras') }}</label>
                                        <div class="col-md-12">
                                            <div class="form-check-inline">
                                                <input class="form-check-input me-0 has_extras" type="radio"
                                                    name="has_extras" id="extras_no" value="2"
                                                    {{ count($getproductdata['extras']) > 0 ? '' : 'checked' }}>
                                                <label class="form-check-label"
                                                    for="extras_no">{{ trans('labels.no') }}</label>
                                            </div>
                                            <div class="form-check-inline">
                                                <input class="form-check-input me-0 has_extras" type="radio"
                                                    name="has_extras" id="extras_yes" value="1"
                                                    {{ count($getproductdata['extras']) > 0 ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="extras_yes">{{ trans('labels.yes') }}</label>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="d-flex gap-1">
                                        @if (count($globalextras) > 0)
                                            <button class="btn btn-primary px-sm-4" type="button"
                                                onclick="global_extras()"><i class="fa-sharp fa-solid fa-plus"></i>
                                                {{ trans('labels.add_global_extras') }}</button>
                                        @endif
                                        <button class="btn btn-secondary float-end" id="add_extras" type="button"
                                            onclick="more_editextras_fields('{{ trans('labels.name') }}','{{ trans('labels.price') }}')">
                                            <i class="fa-sharp fa-solid fa-plus"></i>
                                        </button>

                                    </div>

                                </div>

                                <div class=" border-bottom pb-2 mb-2" id="extras">
                                    @forelse ($getproductdata['extras'] as $key => $extras)
                                        <div class="row pe-0">
                                            <input type="hidden" class="form-control" name="extras_id[]"
                                                value="{{ $extras->id }}">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    @if ($key == 0)
                                                        <label class="col-form-label">{{ trans('labels.name') }} <span
                                                                class="text-danger">
                                                                * </span></label>
                                                    @endif
                                                    <input type="text" class="form-control extras_name"
                                                        name="extras_name[]" value="{{ $extras->name }}"
                                                        placeholder="{{ trans('labels.name') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    @if ($key == 0)
                                                        <label class="col-form-label">{{ trans('labels.price') }}
                                                            <span class="text-danger">
                                                                * </span></label>
                                                    @endif
                                                    <div class="d-flex gap-sm-4 gap-2">
                                                        <input type="text"
                                                            class="form-control numbers_only extras_price"
                                                            name="extras_price[]" value="{{ $extras->price }}"
                                                            placeholder="{{ trans('labels.price') }}" required>
                                                        <button class="btn btn-danger" type="button"
                                                            @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="deletedata('{{ URL::to('admin/products/delete/extras-' . $extras->id) }}')" @endif><i
                                                                class="fa fa-trash" aria-hidden="true"></i> </button>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                        <span class="hiddenextrascount d-none">{{ $key }}</span>
                                    @empty
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">{{ trans('labels.name') }} <span
                                                            class="text-danger">
                                                            * </span></label>
                                                    <input type="text" class="form-control extras_name"
                                                        name="extras_name[]" placeholder="{{ trans('labels.name') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">{{ trans('labels.price') }} <span
                                                            class="text-danger">
                                                            * </span></label>
                                                    <div class="d-flex">
                                                        <input type="text"
                                                            class="form-control numbers_only extras_price"
                                                            name="extras_price[]"
                                                            placeholder="{{ trans('labels.price') }}">
                                                        <button class="btn btn-outline-info mx-2" type="button"
                                                            onclick="more_editextras_fields('{{ trans('labels.name') }}','{{ trans('labels.price') }}')"><i
                                                                class="fa-sharp fa-solid fa-plus"></i> </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforelse
                                    <div id="global-extras"></div>
                                    <div id="more_editextras_fields"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="has_variants"
                                            class="col-form-label">{{ trans('labels.product_has_variation') }}</label>
                                        <div class="col-md-12">

                                            <div class="form-check-inline">
                                                <input class="form-check-input me-0 has_variants" type="radio"
                                                    name="has_variants" id="no" value="2" checked
                                                    @if ($getproductdata->has_variants == 2) checked @endif>
                                                <label class="form-check-label"
                                                    for="no">{{ trans('labels.no') }}</label>
                                            </div>
                                            <div class="form-check-inline">
                                                <input class="form-check-input me-0 has_variants" type="radio"
                                                    name="has_variants" id="yes" value="1"
                                                    @if ($getproductdata->has_variants == 1) checked @endif>
                                                <label class="form-check-label"
                                                    for="yes">{{ trans('labels.yes') }}</label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @if ($getproductdata->has_variants == 1 && count($getproductdata['variation']) > 0)
                                    <div
                                        class="col-md-6 {{ session()->get('direction') == 2 ? 'text-start' : 'text-end' }}">
                                        <button class="btn btn-secondary get-variants" type="button"
                                            dataa-url="{{ URL::to('admin/products/variants/edit', $getproductdata->id) }}">
                                            <i class="fa-sharp fa-solid fa-plus"></i>
                                        </button>
                                    </div>
                                @else
                                    <div
                                        class="col-md-6 {{ session()->get('direction') == 2 ? 'text-start' : 'text-end' }}">
                                        <button class="btn btn-secondary" type="button" id="btn_addvariants"
                                            onclick="addvariantModal()">
                                            <i class="fa-sharp fa-solid fa-plus"></i>
                                        </button>
                                    </div>
                                @endif

                                <div class="col-12 @if ($getproductdata->has_variants == 1) dn @endif" id="price_row">
                                    <div class="row border-top py-2 my-2">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">{{ trans('labels.original_price') }} <span
                                                        class="text-danger">
                                                        * </span></label>
                                                <input type="text" step="any" class="form-control numbers_only"
                                                    name="original_price"
                                                    value="{{ $getproductdata->has_variants == 1 ? '' : ($getproductdata->item_original_price > 0 ? $getproductdata->item_original_price : 0) }}"
                                                    placeholder="{{ trans('labels.original_price') }}"
                                                    id="original_price">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">{{ trans('labels.selling_price') }} <span
                                                        class="text-danger">
                                                        * </span></label>
                                                <input type="text" step="any" class="form-control numbers_only"
                                                    name="price"
                                                    value="{{ $getproductdata->has_variants == 1 ? '' : $getproductdata->item_price }}"
                                                    placeholder="{{ trans('labels.selling_price') }}" id="price">
                                            </div>
                                        </div>
                                        <div
                                            class="col-12 d-flex border-top border-bottom py-2 my-2 align-items-center justify-content-between">
                                            <div class="form-group">
                                                <label for="has_stock"
                                                    class="form-label">{{ trans('labels.stock_management') }}</label>
                                                <div class="col-md-12">
                                                    <div class="form-check-inline">
                                                        <input class="form-check-input me-0 has_stock" type="radio"
                                                            name="has_stock" id="stock_no" value="2" checked
                                                            @if ($getproductdata->stock_management == 2) checked @endif>
                                                        <label class="form-check-label"
                                                            for="stock_no">{{ trans('labels.no') }}</label>
                                                    </div>
                                                    <div class="form-check-inline">
                                                        <input class="form-check-input me-0 has_stock" type="radio"
                                                            name="has_stock" id="stock_yes" value="1"
                                                            @if ($getproductdata->stock_management == 1) checked @endif>
                                                        <label class="form-check-label"
                                                            for="stock_yes">{{ trans('labels.yes') }}</label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3" id="block_stock_qty">
                                            <div class="form-group">
                                                <label class="form-label">{{ trans('labels.stock_qty') }} <span
                                                        class="text-danger"> * </span></label>
                                                <input type="text" class="form-control numbers_only" name="qty"
                                                    onkeypress="allowNumbersOnly(event)"
                                                    value="{{ $getproductdata->has_variants == 1 ? '' : $getproductdata->qty }}"
                                                    placeholder="{{ trans('labels.stock_qty') }}" id="qty">
                                            </div>
                                        </div>
                                        <div class="col-md-3" id="block_min_order">
                                            <div class="form-group">
                                                <label class="form-label">{{ trans('labels.min_order_qty') }} <span
                                                        class="text-danger"> * </span></label>
                                                <input type="text" class="form-control numbers_only" name="min_order"
                                                    onkeypress="allowNumbersOnly(event)"
                                                    value="{{ $getproductdata->has_variants == 1 ? '' : ($getproductdata->min_order > 0 ? $getproductdata->min_order : 0) }}"
                                                    placeholder="{{ trans('labels.min_order_qty') }}" id="min_order">

                                            </div>
                                        </div>
                                        <div class="col-md-3" id="block_max_order">
                                            <div class="form-group">
                                                <label class="form-label">{{ trans('labels.max_order_qty') }} <span
                                                        class="text-danger"> * </span></label>
                                                <input type="text" class="form-control numbers_only" name="max_order"
                                                    onkeypress="allowNumbersOnly(event)"
                                                    value="{{ $getproductdata->has_variants == 1 ? '' : ($getproductdata->max_order > 0 ? $getproductdata->max_order : 0) }}"
                                                    placeholder="{{ trans('labels.max_order_qty') }}" id="max_order">

                                            </div>
                                        </div>
                                        <div class="col-md-3" id="block_product_low_qty_warning">
                                            <div class="form-group">
                                                <label class="form-label">{{ trans('labels.product_low_qty_warning') }}
                                                    <span class="text-danger"> * </span></label>
                                                <input type="text" class="form-control" name="low_qty" id="low_qty"
                                                    onkeypress="allowNumbersOnly(event)"
                                                    value="{{ $getproductdata->low_qty }}"
                                                    placeholder="{{ trans('labels.product_low_qty_warning') }}">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="@if ($getproductdata->has_variants == 2) dn @endif" id="variations">
                                    <div class="card border my-3 {{ count($productVariantArrays) > 0 ? 'd-flex' : 'd-none' }}"
                                        id="variant_card">
                                        <div class="card-header border-bottom">
                                            <div class="row flex-grow-1">
                                                <div class="col-md d-flex align-items-center">
                                                    <h5 class="card-header-title color-changer text-dark fw-500">
                                                        {{ trans('labels.product') }}
                                                        {{ trans('labels.variants') }}
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row form-group m-0">
                                                <div class="table-responsive p-0">
                                                    <input type="hidden" id="hiddenVariantOptions"
                                                        name="hiddenVariantOptions"
                                                        value="{{ $getproductdata->variants_json == null ? '{}' : $getproductdata->variants_json }}">
                                                    <div class="variant-table">
                                                        <table class="table table-bordered m-0" id='tblvariants'>
                                                            <thead>
                                                                <tr class="text-center fs-15 fw-600">
                                                                    @if (isset($product_variant_names))
                                                                        @foreach ($product_variant_names as $variant)
                                                                            <th><span>{{ ucwords($variant) }}</span>
                                                                            </th>
                                                                        @endforeach
                                                                    @endif
                                                                    <th><span>{{ trans('labels.original_price') }}</span>
                                                                    </th>
                                                                    <th><span>{{ trans('labels.selling_price') }}</span>
                                                                    </th>
                                                                    <th><span>{{ trans('labels.stock_qty') }}</span>
                                                                    </th>
                                                                    <th><span>{{ trans('labels.min_order_qty') }}</span>
                                                                    </th>
                                                                    <th><span>{{ trans('labels.max_order_qty') }}</span>
                                                                    </th>
                                                                    <th><span>{{ trans('labels.product_low_qty_warning') }}</span>
                                                                    </th>
                                                                    <th><span>{{ trans('labels.stock_management') }}</span>
                                                                    </th>
                                                                    <th><span>{{ trans('labels.is_available') }}</span>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                                @if (isset($productVariantArrays))
                                                                    @foreach ($productVariantArrays as $counter => $productVariant)
                                                                        <tr class="fs-7 fw-500"
                                                                            data-id="{{ $productVariant['product_variants']['id'] }}">
                                                                            @foreach (explode('|', $productVariant['product_variants']['name']) as $key => $values)
                                                                                <td>
                                                                                    <input type="text"
                                                                                        name="variants[{{ $productVariant['product_variants']['id'] }}][variants][{{ $key }}][]"
                                                                                        autocomplete="off"
                                                                                        spellcheck="false"
                                                                                        class="form-control"
                                                                                        value="{{ $values }}"
                                                                                        readonly>
                                                                                </td>
                                                                            @endforeach
                                                                            <td>
                                                                                <input type="number"
                                                                                    name="variants[{{ $productVariant['product_variants']['id'] }}][original_price]"
                                                                                    autocomplete="off" spellcheck="false"
                                                                                    placeholder="{{ trans('labels.original_price') }}"
                                                                                    class="form-control voriginal_price_{{ $counter }}"
                                                                                    value="{{ $productVariant['product_variants']['original_price'] }}"
                                                                                    id="voriginal_price_{{ $counter }}"
                                                                                    required>
                                                                            </td>
                                                                            <td>
                                                                                <input type="number"
                                                                                    name="variants[{{ $productVariant['product_variants']['id'] }}][price]"
                                                                                    autocomplete="off" spellcheck="false"
                                                                                    placeholder="{{ trans('labels.selling_price') }}"
                                                                                    class="form-control vprice_{{ $counter }}"
                                                                                    value="{{ $productVariant['product_variants']['price'] }}"
                                                                                    id="vprice_{{ $counter }}"
                                                                                    required>
                                                                            </td>

                                                                            <td>
                                                                                <input type="number"
                                                                                    name="variants[{{ $productVariant['product_variants']['id'] }}][qty]"
                                                                                    autocomplete="off" spellcheck="false"
                                                                                    placeholder="{{ trans('labels.stock_qty') }}"
                                                                                    class="form-control vqty_{{ $counter }}"
                                                                                    value="{{ $productVariant['product_variants']['qty'] }}"
                                                                                    id="vqty_{{ $counter }}">
                                                                            </td>
                                                                            <td>
                                                                                <input type="number"
                                                                                    name="variants[{{ $productVariant['product_variants']['id'] }}][min_order]"
                                                                                    autocomplete="off" spellcheck="false"
                                                                                    placeholder="{{ trans('labels.min_order_qty') }}"
                                                                                    class="form-control vmin_order_{{ $counter }}"
                                                                                    value="{{ $productVariant['product_variants']['min_order'] }}"
                                                                                    id="vmin_order_{{ $counter }}">
                                                                            </td>
                                                                            <td>
                                                                                <input type="number"
                                                                                    name="variants[{{ $productVariant['product_variants']['id'] }}][max_order]"
                                                                                    autocomplete="off" spellcheck="false"
                                                                                    placeholder="{{ trans('labels.max_order_qty') }}"
                                                                                    class="form-control vmax_order_{{ $counter }}"
                                                                                    value="{{ $productVariant['product_variants']['max_order'] }}"
                                                                                    id="vmax_order_{{ $counter }}">
                                                                            </td>
                                                                            <td>
                                                                                <input type="number"
                                                                                    name="variants[{{ $productVariant['product_variants']['id'] }}][low_qty]"
                                                                                    autocomplete="off" spellcheck="false"
                                                                                    placeholder="{{ trans('labels.product_low_qty_warning') }}"
                                                                                    class="form-control vlow_qty_{{ $counter }}"
                                                                                    value="{{ $productVariant['product_variants']['low_qty'] }}"
                                                                                    id="vlow_qty_{{ $counter }}">
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <input
                                                                                    class="form-check-input  vstock_management_{{ $counter }}"
                                                                                    type="checkbox" value="1"
                                                                                    {{ $productVariant['product_variants']['stock_management'] == 1 ? 'checked' : '' }}
                                                                                    onclick="edit_stock_management(this.id)"
                                                                                    name="variants[{{ $productVariant['product_variants']['id'] }}][stock_management]"
                                                                                    id="vstockmanagement_{{ $counter }}">

                                                                            </td>
                                                                            <td class="text-center">
                                                                                <input
                                                                                    class="form-check-input  vis_available_{{ $counter }} product_available"
                                                                                    type="checkbox" value="1"
                                                                                    onclick="edit_checkavailable(this.id)"
                                                                                    {{ $productVariant['product_variants']['is_available'] == 1 ? 'checked' : '' }}
                                                                                    name="variants[{{ $productVariant['product_variants']['id'] }}][is_available]"
                                                                                    id="{{ $counter }}">

                                                                            </td>

                                                                        </tr>
                                                                    @endforeach
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-3">
                                    <div>
                                        <a href="{{ URL::to('admin/products') }}"
                                            class="btn btn-danger px-sm-4">{{ trans('labels.cancel') }}</a>
                                        <button
                                            class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_products', Auth::user()->role_id, $vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
                                            @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif>{{ trans('labels.save') }}</button>
                                    </div>
                                </div>
                        </form>
                    @else
                        @include('admin.layout.no_data')
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 mt-4">
        <div class="card border-0 box-shadow"
            data-url="{{ url('admin/products/reorder_image-' . $getproductdata->id) }}" id="carddetails">
            <div class="card-body">
                <div class="col-12 d-flex justify-content-between align-items-center pb-3 border-bottom mb-3">
                    <h5 class="text-capitalize fw-600 text-dark color-changer">{{ trans('labels.product_images') }}</h5>
                    <a href="javascript:void(0)" onclick="addimage('{{ $getproductdata->id }}')"
                        class="btn btn-secondary px-sm-4">
                        <i class="fa-regular fa-plus mx-1"></i>{{ trans('labels.add_new') }}
                    </a>
                </div>
                <div class="row g-3 sort_menu">
                    @foreach ($getproductdata['multi_image'] as $key => $productimage)
                        <div class="col-xl-2 col-lg-3 col-md-4 col-6" data-id="{{ $productimage->id }}">
                            <div class="card-dec h-100 handle">
                                <img src="{{ helper::image_path($productimage->image) }}"
                                    class="img-fluid product-image rounded-3 h-75 w-100 object">
                                <div class="d-flex gap-1 mt-2 justify-content-center">
                                    <a tooltip="{{ trans('labels.move') }}" class="btn btn-secondary hov btn-sm"><i
                                            class="fa-light fa-up-down-left-right"></i></a>
                                    <a href="javascript:void(0)" class="btn btn-info hov btn-sm"
                                        onclick="imageview('{{ $productimage->id }}','{{ $productimage->image }}')"><i
                                            class="fa-regular fa-pen-to-square"></i></a>
                                    <a href="javascript:void(0)"
                                        @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('admin/products/delete_image-' . $productimage->id . '/' . $productimage->item_id) }}')" @endif
                                        class="btn btn-danger hov btn-sm @if ($getproductdata['multi_image']->count() == 1) d-none @else '' @endif"><i
                                            class="fa-regular fa-trash"></i></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 mt-4">
        <div class="card border-0 box-shadow">
            <div class="card-body">
                <h5 class="text-capitalize color-changer fw-600 text-dark pb-3 border-bottom mb-3">
                    {{ trans('labels.product_reviews') }}</h5>
                <div class="table-responsive p-0">
                    <table
                        class="table m-0 table-striped table-bordered py-3 zero-configuration w-100 dataTable no-footer">
                        <thead>
                            <tr class="text-capitalize fw-500 fs-15">
                                <td>{{ trans('labels.srno') }}</td>
                                <td>{{ trans('labels.image') }}</td>
                                <td>{{ trans('labels.name') }}</td>
                                <td>{{ trans('labels.description') }}</td>
                                <td>{{ trans('labels.ratting') }}</td>
                                <td>{{ trans('labels.action') }}</td>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($productreview as $item)
                                <tr class="fs-7 row1 align-middle" id="dataid{{ $item->id }}"
                                    data-id="{{ $item->id }}">
                                    <td>@php
                                        echo $i++;
                                    @endphp</td>
                                    <td>
                                        <img src="{{ @helper::image_path($item->image) }}"
                                            class="img-fluid rounded hw-50" alt="">
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->star }} </td>
                                    <td>
                                        <a href="javascript:void(0)"
                                            @if (env('Environment') == 'sendbox') onclick="myFunction()" @else
                                                onclick="statusupdate('{{ URL::to('/admin/products/review/delete-' . $item->id) }}')" @endif
                                            class="btn btn-danger hov btn-sm {{ Auth::user()->type == 4 ? (helper::check_access('role_products', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">
                                            <i class="fa-regular fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
{{-- add Modal --}}
<div class="modal modal-fade-transform" id="addModal" tabindex="-1" aria-labelledby="addModallable"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header justify-content-between">
                <h5 class="modal-title color-changer settings-color" id="addModallable">{{ trans('labels.image') }}
                    <span class="text-danger"> *
                    </span>
                </h5>
                <button type="button" class="bg-transparent border-0" data-bs-dismiss="modal">
                    <i class="fa-regular fa-xmark fs-4 color-changer"></i>
                </button>
            </div>
            <form class="m-0" action=" {{ URL::to('admin/products/add_image') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="product_id" name="product_id">
                    <input type="file" name="image[]" class="form-control" multiple>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger px-sm-4" data-bs-dismiss="modal">Close</button>
                    <button
                        @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif
                        class="btn btn-primary px-sm-4 m-0">{{ trans('labels.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- EDIT PRODUCT IMAGE MODAL --}}
<div class="modal modal-fade-transform" id="editModal" tabindex="-1" aria-labelledby="editModallable"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-dark justify-content-between">
                <h5 class="modal-title color-changer" id="editModallable">{{ trans('labels.image') }} <span
                        class="text-danger">
                        * </span></h5>
                <button type="button" class="bg-transparent border-0" data-bs-dismiss="modal">
                    <i class="fa-regular fa-xmark fs-4 color-changer"></i>
                </button>
            </div>
            <form action=" {{ URL::to('admin/products/updateimage') }}" method="post"
                enctype="multipart/form-data" class="m-0">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="img_id" name="id">
                    <input type="hidden" id="img_name" name="image">
                    <input type="file" name="product_image" class="form-control" id="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger px-sm-4" data-bs-dismiss="modal">Close</button>
                    <button
                        @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif
                        class="btn btn-primary px-sm-4">{{ trans('labels.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal modal-fade-transform" id="commonModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-inner lg-dialog" role="document">
        <div class="modal-content">
            <div class="popup-content">
                <div class="modal-header justify-content-between popup-header align-items-center">
                    <h5 class="modal-title mb-0 color-changer" id="modelCommanModelLabel">
                        {{ trans('labels.add_variants') }}</h5>
                    <button type="button" class="bg-transparent border-0" data-bs-dismiss="modal">
                        <i class="fa-regular fa-xmark fs-4 color-changer"></i>
                    </button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-fade-transform" id="addvariantModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-inner lg-dialog" role="document">
        <div class="modal-content">
            <div class="popup-content">
                <div class="modal-header justify-content-between popup-header align-items-center">
                    <div class="modal-title">
                        <h5 class="mb-0 settings-color color-changer" id="modelCommanModelLabel">
                            {{ trans('labels.add_variants') }}</h5>
                    </div>
                    <button type="button" class="bg-transparent border-0" data-bs-dismiss="modal">
                        <i class="fa-regular fa-xmark fs-4 color-changer"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST"
                        action="{{ URL::to('admin/products/get-product-variants-possibilities') }}">
                        @csrf
                        <div class="form-group settings-color">
                            <label for="variant_name" class="mb-1">{{ trans('labels.variant_name') }}</label>
                            <input class="form-control" name="variant_name" type="text" id="variant_name"
                                onkeyup="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')"
                                placeholder="{{ 'Variant Name, i.e Size, Color etc' }}">
                        </div>
                        <div class="form-group settings-color">
                            <label for="variant_options"
                                class="mb-1">{{ trans('labels.variant_options') }}</label>
                            <input class="form-control" name="variant_options" type="text" id="variant_options"
                                placeholder="{{ 'Variant Options separated by|pipe symbol, i.e Black|Blue|Red' }}">
                        </div>
                        <div class="form-group col-12 d-flex gap-2 justify-content-end form-label">
                            <input type="button" value="{{ trans('labels.cancel') }}"
                                class="btn btn-danger px-sm-4" data-bs-dismiss="modal">
                            <input type="button" value="{{ trans('labels.add_variants') }}"
                                class="btn btn-primary px-sm-4 add-variants">
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@section('scripts')
    <script>
        var extrasurl = "{{ URL::to('admin/editgetextras-' . $getproductdata->id) }}";
        var vendor_id = "{{ $vendor_id }}";
        var placehodername = "{{ trans('labels.name') }}";
        var placeholderprice = "{{ trans('labels.price') }}";
        var page = "edit";
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.12.1/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('ckeditor');

        $(document).on('click', '.get-variants', function(e) {
            $("#commonModal .modal-title").html('{{ __('Add Variants') }}');
            $("#commonModal .modal-dialog").addClass('modal-md');
            $("#commonModal").modal('show');
            var data_url = $(this).attr('dataa-url');

            $.get(data_url, {}, function(data) {
                $('#commonModal .modal-body').html(data);
            });
        });

        $(document).on('click', '.add-variants', function(e) {
            e.preventDefault();
            var form = $(this).parents('form');
            var variantNameEle = $('#variant_name');
            var variantOptionsEle = $('#variant_options');
            var isValid = true;
            var hiddenVariantOptions = $('#hiddenVariantOptions').val();

            if (variantNameEle.val() == '') {
                variantNameEle.focus();
                isValid = false;
            } else if (variantOptionsEle.val() == '') {
                variantOptionsEle.focus();
                isValid = false;
            }

            if (isValid) {
                $.ajax({
                    url: form.attr('action'),
                    datType: 'json',
                    data: {
                        variant_name: variantNameEle.val(),
                        variant_options: variantOptionsEle.val(),
                        hiddenVariantOptions: hiddenVariantOptions
                    },
                    success: function(data) {
                        if (data.message != "" && data.message != null) {
                            toastr.error(data.message);
                        }
                        $('#hiddenVariantOptions').val(data.hiddenVariantOptions);
                        $('.variant-table').html(data.varitantHTML);
                        $('#variant_card').removeClass('d-none');
                        $("#commonModal").modal('hide');
                    }
                })
            }
        });
    </script>
    <script>
        function validation(value, id) {
            if (value.includes('@')) {
                newval = value.replaceAll('@', '');
                $('#' + id).val(newval);
            }
            if (value.includes('\\')) {
                newval = value.replaceAll('\\', '');
                $('#' + id).val(newval);
            }
        }
    </script>
    <script>
        $(document).ready(function() {

            $('.sort_menu').sortable({
                handle: '.handle',
                cursor: 'move',
                placeholder: 'highlight',
                axis: "x,y",

                update: function(e, ui) {
                    var sortData = $('.sort_menu').sortable('toArray', {
                        attribute: 'data-id'
                    })
                    updateToDatabase(sortData.join('|'))
                }
            })

            function updateToDatabase(idString) {

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    dataType: "json",
                    url: $('#carddetails').attr('data-url'),
                    data: {
                        ids: idString,
                    },
                    success: function(response) {
                        if (response.status == 1) {
                            toastr.success(response.msg);
                        } else {
                            toastr.success(wrong);
                        }
                    }
                });
            }

        })

        $(document).ready(function() {

            function toggleAiButton() {
                var titleVal = $('#product_name').val();

                console.log(titleVal);

                if (titleVal && titleVal.trim() !== '') {
                    $('#ai_btn').removeClass('d-none');
                } else {
                    $('#ai_btn').addClass('d-none');
                }
            }

            toggleAiButton();

            $(document).on('input keyup change', '#product_name', function() {
                toggleAiButton();
            });

        });

        function ai_generate() {
            let btn = $('#ai_btn');
            let originalHtml = btn.html();
            let productname = $('#product_name').val();

            // 1. Loader Start: Disable button and show spinner
            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>');

            $.ajax({
                url: "{{ url('admin/products/ai_generate') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    meta_title: productname
                },
                success: function(response) {
                    if (response.success) {
                        $('#product_name').val(response.meta_title);
                        // 2. CKEditor ka data set karein
                        if (typeof CKEDITOR !== "undefined" && CKEDITOR.instances['ckeditor']) {
                            CKEDITOR.instances['ckeditor'].setData(response.meta_description);
                        } else {
                            // Agar CKEditor load nahi hua to normal textarea mein set karein
                            $('textarea[name="description"]').val(response.meta_description);
                        }
                        toastr.success("{{ trans('labels.product_content_generated_successfully') }}");
                    } else {
                        // Show specific error from backend (Invalid Key, 503, etc.)
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    // Network or Request errors
                    toastr.error(
                        "{{ trans('labels.request_failed_please_check_your_internet_connection_or_API_setup') }}"
                    );
                },
                complete: function() {
                    // 2. Loader Stop: Reset button state whether success or error
                    btn.prop('disabled', false).html(originalHtml);
                }
            });
        }
    </script>
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/product.js') }}"></script>
@endsection
