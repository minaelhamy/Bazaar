@extends('landing.layout.default')

@section('content')
    <!-- BREADCRUMB AREA START -->
    <section class="breadcrumb-sec bg-light bg-changer">
        <div class="container">
            <nav aria-label="breadcrumb">
                {{-- <h3 class="breadcrumb-title fw-semibold mb-2 text-center">{{ trans('landing.blog_detail') }}</h3> --}}
                <ol class="breadcrumb">
                    <li class="{{ session()->get('direction') == 2 ? 'breadcrumb-item-rtl' : ' breadcrumb-item ' }}"><a
                            class="text-dark color-changer"
                            href="{{ helper::storefront_url($vendordata) }}">{{ trans('labels.home') }}</a>
                    </li>
                    <li class="text-muted {{ session()->get('direction') == 2 ? 'breadcrumb-item-rtl' : ' breadcrumb-item ' }}"
                        aria-current="page">{{ trans('labels.blog_details') }}</li>
                </ol>
            </nav>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="details-text">
                <img src="{{ helper::image_path($getblog->image) }}" class="img-fluid blog-details-img " alt="...">
                <div class="d-flex align-items-baseline pt-3">
                    <i class="fa-solid fa-calendar-days card-date"></i>
                    <p class="card-date px-2">{{ helper::date_format($getblog->created_at, 1) }}</p>
                </div>
                <h5 class="blog-details-title fw-semibold pb-3 pt-2 color-changer">
                    {{ $getblog->title }}</h5>
                <div class="cms-section fs-7">
                    <p class="details-footer m-0" data-aos="fade-up" data-aos-darution="1000" data-aos-delay="100">
                        {!! $getblog->description !!}
                    </p>
                </div>
                <h5 class="recent-blogs-titel color-changer pt-5 pb-4">{{ trans('landing.related_blogs') }}</h5>
            </div>
            <div class="owl-carousel blogs-slaider owl-theme pb-5">
                @include('landing.included.blogcommonview')
            </div>
            <div class="d-flex justify-content-center view-all-btn">
                <a href="{{ URL::to('/blogs') }}" class="btn-secondary rounded-2">{{ trans('landing.view_all') }}
                </a>
            </div>
        </div>
    </section>
    <!-- subscription -->
    @include('landing.newslatter')
@endsection
