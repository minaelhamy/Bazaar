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
        <h5 class="text-capitalize color-changer mb-0">{{ trans('labels.add_new') }}</h5>

        <div class="d-flex align-items-center">


            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 bg-transparent p-0" style="background-color: transparent;">
                    <li class="breadcrumb-item"><a href="{{ URL::to('admin/products') }}"
                            class="color-changer">{{ trans('labels.products') }}</a></li>
                    <li class="breadcrumb-item active {{ session()->get('direction') == 2 ? 'breadcrumb-rtl' : '' }}"
                        aria-current="page">{{ trans('labels.add') }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card border-0 box-shadow">
                <div class="card-body">
                    <form action="{{ URL::to('admin/products/save') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{ trans('labels.category') }} <span class="text-danger"> *
                                        </span></label>
                                    <select class="form-control selectpicker" name="category[]" data-live-search="true"
                                        id="editcat_id" required>
                                        @if (!empty($getcategorylist))
                                            @foreach ($getcategorylist as $catdata)
                                                <option value="{{ $catdata->id }}" data-id="{{ $catdata->id }}">
                                                    {{ $catdata->name }}
                                                </option>
                                            @endforeach
                                        @endif

                                    </select>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{ trans('labels.name') }} <span class="text-danger"> *
                                        </span></label>

                                    <div class="d-flex align-items-center gap-2">
                                        <input type="text" class="form-control" name="product_name" id="product_name"
                                            value="{{ old('product_name') }}" placeholder="{{ trans('labels.name') }}"
                                            required>

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
                                        value="{{ old('product_sku') }}" placeholder="{{ trans('labels.sku') }}"
                                        id="product_sku">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ trans('labels.image') }} <span class="text-danger"> *
                                        </span></label>
                                    <input type="file" class="form-control" name="product_image[]" multiple
                                        id="image" multiple="" required>

                                    <div class="gallery"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ trans('labels.video_url') }} </label>
                                    <input class="form-control" type="text" name="video_url"
                                        placeholder="{{ trans('labels.video_url') }}" value="{{ old('video_url') }}">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ trans('labels.tax') }} </label>
                                    <select name="tax[]" class="form-control selectpicker" multiple
                                        data-live-search="true">
                                        @if (!empty($gettaxlist))
                                            @foreach ($gettaxlist as $tax)
                                                <option value="{{ $tax->id }}"> {{ $tax->name }} </option>
                                            @endforeach
                                        @endif
                                    </select>

                                </div>
                            </div>

                            @if (@helper::checkaddons('frequently_bought_together'))
                                @if (count($getitems) > 0)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ trans('labels.frequently_bought_items') }}
                                            </label>
                                            <select name="frequently_bought_items[]" class="form-control selectpicker"
                                                id="frequently_bought_items" multiple data-live-search="true"
                                                onchange="change_frequently_bought_items()">
                                                @foreach ($getitems as $item)
                                                    <option value="{{ $item->id }}"> {{ $item->item_name }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                            @endif
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ trans('labels.attachment_name') }} </label>
                                    <input type="text" class="form-control" name="attachment_name" id="attachment_name"
                                        placeholder="{{ trans('labels.attachment_name') }}">
                                    @error('attachment_name')
                                        <span class="text-danger">{{ $message }}</span> <br>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ trans('labels.attachment_file') }}</label>
                                    <input type="file" class="form-control" name="attachment_file"
                                        id="attachment_file">

                                </div>
                            </div>

                            @if (@helper::checkaddons('digital_product'))
                                @include('admin.product.digital_product')
                            @endif

                            <div class="col-md-12 form-group">
                                <label class="form-label">{{ trans('labels.description') }} </label>
                                <textarea class="form-control" id="ckeditor" name="description" id="description">{{ old('description') }}</textarea>

                            </div>

                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12 d-flex flex-wrap justify-content-between align-items-center">
                                        <div class="form-group">
                                            <label for="has_extras"
                                                class="form-label">{{ trans('labels.product_has_extras') }}</label>
                                            <div class="col-md-12">
                                                <div class="form-check-inline">
                                                    <input class="form-check-input me-0 has_extras" type="radio"
                                                        name="has_extras" id="extras_no" value="2" checked
                                                        @if (old('has_extras') == 2) checked @endif>
                                                    <label class="form-check-label"
                                                        for="extras_no">{{ trans('labels.no') }}</label>
                                                </div>
                                                <div class="form-check-inline">
                                                    <input class="form-check-input me-0 has_extras" type="radio"
                                                        name="has_extras" id="extras_yes" value="1"
                                                        @if (old('has_extras') == 1) checked @endif>
                                                    <label class="form-check-label"
                                                        for="extras_yes">{{ trans('labels.yes') }}</label>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center col-sm-auto col-12 mb-sm-0 mb-2">
                                            @if (count($globalextras) > 0)
                                                <div
                                                    class="col-sm-auto col-10 {{ session()->get('direction') == 2 ? 'ps-2' : 'pe-2' }}">
                                                    <button class="btn btn-primary px-sm-4 w-100 align-items-end "
                                                        type="button" id="globalextra" onclick="global_extras()"><i
                                                            class="fa-sharp fa-solid fa-plus"></i>
                                                        {{ trans('labels.add_global_extras') }}</button>
                                                </div>
                                            @endif
                                            <div class="col-auto">
                                                <button class="btn btn-secondary" type="button" id="add_extra"
                                                    onclick="extras_fields('{{ trans('labels.name') }}','{{ trans('labels.price') }}')"><i
                                                        class="fa-sharp fa-solid fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                    <div id="extras">
                                        {{-- <div class="row m-0">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{ trans('labels.name') }} <span
                                                            class="text-danger">
                                                            * </span></label>
                                                    <input type="text" class="form-control" name="extras_name[]" required
                                                        placeholder="{{ trans('labels.name') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{ trans('labels.price') }} <span
                                                            class="text-danger">
                                                            * </span></label>
                                                    <div class="d-flex">
                                                        <input type="text" class="form-control numbers_only"
                                                            name="extras_price[]" placeholder="{{ trans('labels.price') }}"
                                                            required>

                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                        @if (!empty($globalextras) && $globalextras->count() > 0)
                                            <div id="global-extras"></div>
                                        @endif
                                        <div id="more_extras_fields"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 d-flex align-items-center justify-content-between">
                                <div class="form-group">
                                    <label for="has_variants"
                                        class="form-label">{{ trans('labels.product_has_variation') }}</label>
                                    <div class="col-md-12">
                                        <div class="form-check-inline">
                                            <input class="form-check-input me-0 has_variants" type="radio"
                                                name="has_variants" id="no" value="2" checked
                                                @if (old('has_variants') == 2) checked @endif>
                                            <label class="form-check-label"
                                                for="no">{{ trans('labels.no') }}</label>
                                        </div>
                                        <div class="form-check-inline">
                                            <input class="form-check-input me-0 has_variants" type="radio"
                                                name="has_variants" id="yes" value="1"
                                                @if (old('has_variants') == 1) checked @endif>
                                            <label class="form-check-label"
                                                for="yes">{{ trans('labels.yes') }}</label>
                                        </div>

                                    </div>
                                </div>
                                <button class="btn btn-secondary" type="button" id="btn_addvariants"
                                    onclick="commonModal()">
                                    <i class="fa-sharp fa-solid fa-plus"></i>
                                </button>
                            </div>

                            <div class="col-12 dn @if ($errors->has('variants_name.*') || $errors->has('variants_price.*')) dn @endif @if (old('variants') == 2) d-flex @endif"
                                id="price_row">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ trans('labels.original_price') }} <span
                                                    class="text-danger"> * </span>
                                            </label>
                                            <input type="text" class="form-control numbers_only" name="original_price"
                                                value="{{ old('original_price') }}"
                                                placeholder="{{ trans('labels.original_price') }}" id="original_price"
                                                required>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ trans('labels.selling_price') }} <span
                                                    class="text-danger"> * </span></label>
                                            <input type="text" class="form-control numbers_only" name="price"
                                                value="{{ old('price') }}"
                                                placeholder="{{ trans('labels.selling_price') }}" id="price"
                                                required>

                                        </div>
                                    </div>

                                    <div class="col-12 d-flex align-items-center justify-content-between">
                                        <div class="form-group">
                                            <label for="has_stock"
                                                class="form-label">{{ trans('labels.stock_management') }}</label>
                                            <div class="col-md-12">
                                                <div class="form-check-inline">
                                                    <input class="form-check-input me-0 has_stock" type="radio"
                                                        name="has_stock" id="stock_no" value="2" checked
                                                        @if (old('has_stock') == 2) checked @endif>
                                                    <label class="form-check-label"
                                                        for="stock_no">{{ trans('labels.no') }}</label>
                                                </div>
                                                <div class="form-check-inline">
                                                    <input class="form-check-input me-0 has_stock" type="radio"
                                                        name="has_stock" id="stock_yes" value="1"
                                                        @if (old('has_stock') == 1) checked @endif>
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
                                            <input type="text" class="form-control numbers_only"
                                                onkeypress="allowNumbersOnly(event)" name="qty"
                                                value="{{ old('qty') }}"
                                                placeholder="{{ trans('labels.stock_qty') }}" id="qty">
                                        </div>
                                    </div>
                                    <div class="col-md-3" id="block_min_order">
                                        <div class="form-group">
                                            <label class="form-label">{{ trans('labels.min_order_qty') }} <span
                                                    class="text-danger"> * </span>
                                            </label>
                                            <input type="text" class="form-control numbers_only"
                                                onkeypress="allowNumbersOnly(event)" name="min_order"
                                                value="{{ old('min_order') }}"
                                                placeholder="{{ trans('labels.min_order_qty') }}" id="min_order">

                                        </div>
                                    </div>
                                    <div class="col-md-3" id="block_max_order">
                                        <div class="form-group">
                                            <label class="form-label">{{ trans('labels.max_order_qty') }} <span
                                                    class="text-danger"> * </span>
                                            </label>
                                            <input type="text"
                                                class="form-control numbers_only"onkeypress="allowNumbersOnly(event)"
                                                name="max_order" value="{{ old('max_order') }}"
                                                placeholder="{{ trans('labels.max_order_qty') }}" id="max_order">

                                        </div>
                                    </div>
                                    <div class="col-md-3" id="block_product_low_qty_warning">
                                        <div class="form-group">
                                            <label class="form-label">{{ trans('labels.product_low_qty_warning') }} <span
                                                    class="text-danger"> * </span></label>
                                            <input type="text" class="form-control numbers_only variation_qty"
                                                onkeypress="allowNumbersOnly(event)" name="low_qty" id="low_qty"
                                                placeholder="{{ trans('labels.product_low_qty_warning') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 dn @if ($errors->has('variation.*') || $errors->has('variation_price.*') || old('has_variants') == 1) d-flex @endif" id="variations">
                                <div id="productVariant">
                                    <div class="card my-3 d-none" id="variant_card">
                                        <div class="card-header">
                                            <div class="row flex-grow-1">
                                                <div class="col-md d-flex align-items-center">
                                                    <h5 class="card-header-title">
                                                        {{ trans('labels.product') }}
                                                        {{ trans('labels.variants') }}
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <input type="hidden" id="hiddenVariantOptions" name="hiddenVariantOptions"
                                                value="{}">
                                            <div class="variant-table col-12">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                            <a href="{{ URL::to('admin/products') }}"
                                class="btn px-sm-4 btn-danger">{{ trans('labels.cancel') }}</a>
                            <button
                                class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_products', Auth::user()->role_id, $vendor_id, 'add') == 1 ? '' : 'd-none') : '' }}"
                                @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif>{{ trans('labels.save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade  modal-fade-transform" id="commonModal" tabindex="-1" role="dialog" aria-hidden="true">
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
                        <form method="POST" action="{{ URL::to('admin/products/get-product-variants-possibilities') }}">
                            @csrf
                            <div class="form-group">
                                <label for="variant_name" class="form-label">{{ trans('labels.variant_name') }}</label>
                                <input class="form-control" name="variant_name" type="text" id="variant_name"
                                    onkeyup="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')"
                                    placeholder="{{ 'Variant Name, i.e Size, Color etc' }}">
                            </div>
                            <div class="form-group">
                                <label for="variant_options"
                                    class="form-label">{{ trans('labels.variant_options') }}</label>
                                <input class="form-control" name="variant_options" type="text" id="variant_options"
                                    placeholder="{{ 'Variant Options separated by|pipe symbol, i.e Black|Blue|Red' }}">
                            </div>
                            <div class="mt-3 col-12 d-flex gap-2 justify-content-end">
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
@endsection
@section('scripts')
    <script></script>
    <script>
        var extrasurl = "{{ URL::to('admin/getextras') }}";
        var vendor_id = "{{ $vendor_id }}";
        var placehodername = "{{ trans('labels.name') }}";
        var placeholderprice = "{{ trans('labels.price') }}";
        var page = "add";
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.12.1/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('ckeditor');
    </script>
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
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
