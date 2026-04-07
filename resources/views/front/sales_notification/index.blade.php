<div class="card border-0 w-100">
    <div class="card-body">
        <div class="d-flex gap-2 align-items-center">
            <div class="col-auto">
                <img src="{{ helper::image_path($productdata->image) }}" class="rounded">
            </div>
            <div class="w-100">
                <h6 class="heading color-changer text-capitalize">
                    {{ $productdata->item_name }}
                </h6>
                <p class="info text-muted line-2">
                    {{ trans('labels.recently_purchased') }}
                </p>
                <div class="read-more-wrapper">
                    <a href="{{ helper::storefront_url($storeinfo, '/detail-' . $productdata->slug) }}" class="text-primary">
                        <span
                            class="read-more text-secondary text-decoration-underline">{{ trans('labels.view_product') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
