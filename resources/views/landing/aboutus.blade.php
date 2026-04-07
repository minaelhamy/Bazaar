@extends('landing.layout.default')
@section('content')
<!-- BREADCRUMB AREA START -->
<section class="breadcrumb-sec bg-light bg-changer">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="{{ session()->get('direction') == 2 ? 'breadcrumb-item-rtl' : ' breadcrumb-item ' }}"><a
                        class="text-dark color-changer" href="{{ helper::storefront_url($vendordata) }}">{{ trans('labels.home') }}</a>
                </li>
                <li class="text-muted {{ session()->get('direction') == 2 ? 'breadcrumb-item-rtl' : ' breadcrumb-item ' }}"
                    aria-current="page">{{ trans('landing.about_us') }}</li>
            </ol>
        </nav>
    </div>
</section>
<section>
    <div class="about-us-bg-color">
        <div class="container">
            <div class="about-us-main">
                @if (!empty($aboutus->about_content))
                    <div class="cms-section my-3">

                        {!! $aboutus->about_content !!}

                    </div>
                @else
                    @include('admin.layout.no_data')
                @endif
            </div>
        </div>
    </div>
</section>

    <!-- subscription -->
    @include('landing.newslatter')
@endsection
