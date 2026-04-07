@foreach ($blogs as $blog)
    <div class="item h-100 px-1">
        <div class="card rounded-3 h-100 p-3">
            <div class="overflow-hidden rounded-3">
                <img src="{{ helper::image_path($blog->image) }}"
                    class="card-img-top blog-card-top-img rounded-3 blog-card-hover" height="260" alt="...">
            </div>
            <div class="card-body p-0 pt-3">
                <a href="{{ URL::to('/blogdetail-' . $blog->slug) }}">
                    <h6 class="fw-500 pt-2 text-secondary-color text_truncation2">
                        {{ $blog->title }}
                    </h6>
                </a>
                <p class="fs-7 text_truncation2 text-muted m-0">Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus,
                    nesciunt debitis. Asperiores perferendis, sed iure aut maxime repellat sunt debitis placeat numquam
                    quam non aliquid commodi animi excepturi ab perspiciatis.</p>
            </div>
            <div class="card-footer pt-3 mt-3 p-0 bg-transparent text-end border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-2 align-items-center">
                        <i class="fa-solid fa-calendar-days text-muted fs-7"></i>
                        <p class="fs-7 text-muted">
                            {{ helper::date_format($blog->created_at, 1) }}
                        </p>
                    </div>
                    <a href="{{ URL::to('/blogdetail-' . $blog->slug) }}">
                        <div class="text-secondary-color fs-7">
                            {{ Str::contains(request()->url(), 'blog') ? trans('landing.read_more') : trans('landing.read_more') }}
                            <i class="fa-solid {{ session()->get('direction') == 2 ? 'fa-arrow-left' : 'fa-arrow-right' }}"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endforeach