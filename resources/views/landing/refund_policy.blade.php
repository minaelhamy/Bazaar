@extends('landing.layout.default')
@section('content')
    <!-- BREADCRUMB AREA START -->
    <section class="breadcrumb-sec bg-light bg-changer">
        <div class="container">
            <nav aria-label="breadcrumb">
                {{-- <h3 class="breadcrumb-title fw-semibold mb-2 text-center">{{ trans('landing.refund_policy') }}</h3> --}}
                <ol class="breadcrumb">
                    <li class="{{ session()->get('direction') == 2 ? 'breadcrumb-item-rtl' : ' breadcrumb-item ' }}"><a
                            class="text-dark color-changer" href="{{ helper::storefront_url($vendordata) }}">{{ trans('labels.home') }}</a>
                    </li>
                    <li class="text-muted {{ session()->get('direction') == 2 ? 'breadcrumb-item-rtl' : ' breadcrumb-item ' }}"
                        aria-current="page">{{ trans('landing.refund_policy') }}</li>
                </ol>
            </nav>
        </div>
    </section>
<section>
    <div class="about-us-bg-color">
        <div class="container">
            <div class="about-us-main">
                @if (!empty($policy->refund_policy))
                    <div class="cms-section my-3">

                        {!! $policy->refund_policy !!}

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
