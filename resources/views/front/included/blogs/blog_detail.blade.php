@include('front.theme.header')

<section class="breadcrumb-sec bg-change-mode">

    <div class="container">

        <nav aria-label="breadcrumb">

            <ol class="breadcrumb">

                <li class="breadcrumb-item text-dark"><a class="text-dark color-changer"
                        href="{{ helper::storefront_url($storeinfo) }}">{{trans('labels.home')}}</a>
                </li>

                <li class="text-muted breadcrumb-item active" aria-current="page">{{ trans('labels.latest-post') }}</li>

                <li class="text-muted breadcrumb-item active" aria-current="page">{{ $blogdetail->title }}</li>

            </ol>

        </nav>

    </div>

</section>

<div class="blog-sec">
    <div class="container">
        <section class="blog-section">
            <div class="container">
                <div class="row mb-5">
                    <div class="blog-details px-0">
                        <div class="card card-bg border border-0">

                            <img src="{{ helper::image_path($blogdetail->image) }}"
                                class="card-img-top border blog-card-border">

                            <div class="card-body px-0">
                                <div class="row justify-content-between mb-3">
                                    <div class="col-auto text-muted">
                                        <span>{{ helper::date_format($blogdetail->created_at,$blogdetail->vendor_id) }}</span>
                                    </div>
                                </div>
                                <p class="fs-4 color-changer mb-3">{{ $blogdetail->title }}</p>
                                <small class="text-muted card-text">{!! $blogdetail->description !!}</small>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        @if ($getblog->count() > 0)
            <section class="mb-4">

                <div class="d-flex align-items-center justify-content-between">
                    <h2 class="text-font color-changer main-title m-0">{{ trans('labels.latest-post') }}</h2>
                    <a href="{{ helper::storefront_url($storeinfo, '/blogs') }}" class="btn btn-store mobile-btn">{{trans('labels.view_all')}}</a>
                </div>

                <div class="row">
                    @foreach ($getblog as $blog)
                        <div class="col-md-6 col-lg-4 col-xl-3 d-flex mt-3 justify-content-sm-center">
                            <div class="card card-bg w-100 border-0 border-1">
                                <div class="img-overlay border rounded-4 overflow-hidden">
                                    <img src="{{ helper::image_path($blog->image) }}" height="300"
                                        class="card-img-top" alt="...">
                                </div>
                                <div class="card-body px-0">
                                    <p class="mb-3 line-2 color-changer">{{ $blog->title }}</p>
                                    <small class="card-text m-0 text-muted line-3">{!! Str::limit($blog->description, 100) !!}</small>
                                </div>
                                <div class="card-footer border-0 bg-transparent px-0">
                                    <div class="d-flex justify-content-between">
                                        <p class="m-0 text-primary-color fw-medium fs-7 text-muted"><i
                                                class="fa-regular fa-calendar-days"></i>
                                            {{ helper::date_format($blog->created_at,$blog->vendor_id) }}</p>
                                        <a href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}"
                                            class="read-btn fs-7">{{ trans('labels.readmore') }}<span class="mx-1"><i
                                                    class="{{ session()->get('direction') == 2 ? 'fa-regular fa-arrow-left' : 'fa-regular fa-arrow-right' }}"></i></span></a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </section>
        @else
            @include('front.no_data')
        @endif
    </div>

</div>
@include('front.newsletter')
@include('front.theme.footer')
