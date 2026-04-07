@include('front.theme.header')

<section class="breadcrumb-sec bg-change-mode">

    <div class="container">

        <nav aria-label="breadcrumb">

            <ol class="breadcrumb">

                <li class="breadcrumb-item text-dark"><a class="text-dark color-changer"
                        href="{{ helper::storefront_url($storeinfo) }}">{{trans('labels.home')}}</a>
                </li>

                <li class="text-muted breadcrumb-item {{ session()->get('direction') == 2 ? 'rtl' : '' }} text-dark active" aria-current="page">{{ trans('labels.privacypolicy') }}</li>

            </ol>

        </nav>

    </div>

</section>

<section class="product-prev-sec cms-section product-list-sec">
    <div class="container">
        @if (@$privacy->privacypolicy_content != "")
            {!!@$privacy->privacypolicy_content!!}
        @else
            @include('front.no_data')
        @endif
    </div>
</section>

<!-- newsletter -->
@include('front.newsletter')
<!-- newsletter -->


@include('front.theme.footer')
