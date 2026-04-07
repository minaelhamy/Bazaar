@include('front.theme.header')

<section class="breadcrumb-sec bg-change-mode">

    <div class="container">

        <nav aria-label="breadcrumb">


            <ol class="breadcrumb">

                <li class="breadcrumb-item text-dark"><a class="text-dark color-changer"
                        href="{{ helper::storefront_url($storeinfo, '/') }}">{{ trans('labels.home') }}</a>
                </li>

                <li class="text-muted breadcrumb-item active" aria-current="page">
                    {{ trans('labels.latest-post') }}</li>

            </ol>
        </nav>
    </div>
</section>

<div class="blog-sec">
    <div class="container">
        @if ($getblog->count() > 0)
            <section class="mb-4">
                <div class="container">
                    {{-- <h2 class="text-font text-center blog-titel">{{ trans('labels.latest-post') }}</h2> --}}
                    <div class="row">
                        @foreach (helper::getblogs($storeinfo->id) as $blog)
                            <div class="col-md-6 col-lg-4 col-xl-3 d-flex mb-5 justify-content-sm-center">
                                <div class="card card-bg w-100 border-0 border-1">
                                    <div class="img-overlay border rounded-4">
                                        <img src="{{ helper::image_path($blog->image) }}" height="300"
                                            class="card-img-top rounded-4 object-fit-cover" alt="...">
                                    </div>
                                    <div class="card-body px-0 pb-2">
                                        <p class="mb-2 line-2"> <a
                                                href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}"
                                                class="color-changer text-dark">{{ $blog->title }}</a>
                                        </p>
                                        <small class="card-text m-0 text-muted line-3">{!! Str::limit($blog->description, 100) !!}</small>
                                    </div>
                                    <div class="card-footer border-0 bg-transparent px-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="m-0 text-primary-color fw-medium fs-7 text-muted"><i
                                                    class="fa-light fa-calendar-days"></i>
                                                {{ helper::date_format($blog->created_at, $blog->vendor_id) }}</p>
                                            <a href="{{ helper::storefront_url($storeinfo, '/blogs-' . $blog->slug) }}"
                                                class="read-btn fs-7">{{ trans('labels.readmore') }}<span
                                                    class="mx-1"><i
                                                        class="{{ session()->get('direction') == 2 ? 'fa-regular fa-arrow-left' : 'fa-regular fa-arrow-right' }}"></i></span></a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {!! $getblog->links() !!}
                    </div>
                </div>
            </section>
        @else
            @include('front.no_data')
        @endif

    </div>

</div>


<!-- newsletter -->
@include('front.newsletter')
<!-- newsletter -->

@include('front.theme.footer')
