<div class="offcanvas {{ session()->get('direction') == 2 ? 'offcanvas-start' : 'offcanvas-end' }}" tabindex="-1"
    id="ratingsidebar" aria-labelledby="ratingsidebarLabel">
    <div class="offcanvas-header border-bottom p-0 py-3 flex-column">
        <div class="d-flex w-100 border-bottom justify-content-between px-3 pb-3 align-items-center">
            <h5 class="offcanvas-title line-1" id="ratingsidebarLabel">{{ $getitem->item_name }}</h5>
            <button type="button" class="btn-close m-0" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="px-3 mt-3">
            <div class="bg-light-subtle border p-md-3 p-2">
                <div class="d-flex gap-1 justify-content-center align-items-center">
                    <i class="fa-solid fa-star text-warning fw-600 fs-5"></i>
                    <span class="fs-5 fw-600 mb-0 text-dark">{{ number_format($averagerating, 1) }}</span>
                </div>
                <p class="text-center text-muted fw-500 fs-7">{{ trans('labels.based_on') }}
                    {{ count($itemreviewdata) }}
                    {{ trans('labels.reviews') }}</p>
                <div class="row gx-3 g-2 align-items-center">
                    <div class="col-2 col-sm-2 px-0 px-2 d-flex gap-1 align-items-center">
                        <i class="fa-solid fa-star text-warning fs-8"></i>
                        <span class="fs-7 fw-500 mb-0 text-dark">5.0</span>
                    </div>
                    @php
                        if (count(@$itemreviewdata) != 0) {
                            $five = ($fivestaraverage / count(@$itemreviewdata)) * 100;
                        } else {
                            $five = 0;
                        }
                    @endphp
                    <div class="col-8 col-sm-8">
                        <!-- Progress item -->
                        <div class="progress progress-sm" role="progressbar" aria-label="Animated striped example"
                            aria-valuenow="{{ round($five) }}" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated"
                                style="width: {{ round($five) }}%"></div>
                        </div>
                    </div>
                    <!-- Percentage -->
                    <div class="col-2 col-sm-2 text-end">
                        <span class="fs-7 fw-500 mb-0 text-dark">{{ round($five) }}%</span>
                    </div>

                    <!-- Progress bar and Rating -->
                    <div class="col-2 col-sm-2 px-0 px-2 d-flex gap-1 align-items-center">
                        <i class="fa-solid fa-star text-warning fs-8"></i>
                        <span class="fs-7 fw-500 mb-0 text-dark">4.0</span>
                    </div>
                    @php
                        if (count(@$itemreviewdata) != 0) {
                            $four = ($fourstaraverage / count(@$itemreviewdata)) * 100;
                        } else {
                            $four = 0;
                        }
                    @endphp
                    <div class="col-8 col-sm-8">
                        <!-- Progress item -->
                        <div class="progress progress-sm" role="progressbar" aria-label="Animated striped example"
                            aria-valuenow="{{ round($four) }}" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated"
                                style="width: {{ round($four) }}%"></div>
                        </div>
                    </div>
                    <!-- Percentage -->
                    <div class="col-2 col-sm-2 text-end">
                        <span class="fs-7 fw-500 mb-0 text-dark">{{ round($four) }}%</span>
                    </div>

                    <!-- Progress bar and Rating -->
                    <div class="col-2 col-sm-2 px-0 px-2 d-flex gap-1 align-items-center">
                        <i class="fa-solid fa-star text-warning fs-8"></i>
                        <span class="fs-7 fw-500 mb-0 text-dark">3.0</span>
                    </div>
                    @php
                        if (count(@$itemreviewdata) != 0) {
                            $three = ($threestaraverage / count(@$itemreviewdata)) * 100;
                        } else {
                            $three = 0;
                        }
                    @endphp
                    <div class="col-8 col-sm-8">
                        <!-- Progress item -->
                        <div class="progress progress-sm" role="progressbar" aria-label="Animated striped example"
                            aria-valuenow="{{ round($three) }}" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated"
                                style="width: {{ round($three) }}%"></div>
                        </div>
                    </div>
                    <!-- Percentage -->
                    <div class="col-2 col-sm-2 text-end">
                        <span class="fs-7 fw-500 mb-0 text-dark">{{ round($three) }}%</span>
                    </div>

                    <!-- Progress bar and Rating -->
                    <div class="col-2 col-sm-2 px-0 px-2 d-flex gap-1 align-items-center">
                        <i class="fa-solid fa-star text-warning fs-8"></i>
                        <span class="fs-7 fw-500 mb-0 text-dark">2.0</span>
                    </div>
                    @php
                        if (count(@$itemreviewdata) != 0) {
                            $two = ($twostaraverage / count(@$itemreviewdata)) * 100;
                        } else {
                            $two = 0;
                        }
                    @endphp
                    <div class="col-8 col-sm-8">
                        <!-- Progress item -->
                        <div class="progress progress-sm" role="progressbar" aria-label="Animated striped example"
                            aria-valuenow="{{ round($two) }}" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated"
                                style="width: {{ round($two) }}%"></div>
                        </div>
                    </div>
                    <!-- Percentage -->
                    <div class="col-2 col-sm-2 text-end">
                        <span class="fs-7 fw-500 mb-0 text-dark">{{ round($two) }}%</span>
                    </div>

                    <!-- Progress bar and Rating -->
                    <div class="col-2 col-sm-2 px-0 px-2 d-flex gap-1 align-items-center">
                        <i class="fa-solid fa-star text-warning fs-8"></i>
                        <span class="fs-7 fw-500 mb-0 text-dark">1.0</span>
                    </div>
                    @php
                        if (count(@$itemreviewdata) != 0) {
                            $one = ($onestaraverage / count(@$itemreviewdata)) * 100;
                        } else {
                            $one = 0;
                        }
                    @endphp
                    <div class="col-8 col-sm-8">
                        <!-- Progress item -->
                        <div class="progress progress-sm" role="progressbar" aria-label="Animated striped example"
                            aria-valuenow="{{ round($one) }}" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated"
                                style="width: {{ round($one) }}%"></div>
                        </div>
                    </div>
                    <!-- Percentage -->
                    <div class="col-2 col-sm-2 text-end">
                        <span class="fs-7 fw-500 mb-0 text-dark">{{ round($one) }}%</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @if (count($itemreviewdata) > 0)
        <div class="offcanvas-body">
            @foreach ($itemreviewdata as $key => $reviewdata)
                <div class="card bg-light @if ($key > 0) mt-3 @endif">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar avatar-lg me-md-3 me-2 flex-shrink-0">
                                <img src="{{ helper::image_path($reviewdata->user_info->image) }}" alt=""
                                    class="w-100 rounded-circle">
                            </div>
                            <div class="w-100">
                                <div class="d-flex gap-2 align-items-center justify-content-between">
                                    <h6 class="m-0 fw-600">{{ $reviewdata->user_info->name }}</h6>
                                    <div class="fs-8 px-2 py-1 rounded d-flex gap-1 align-items-center bg-warning">
                                        <i class="fa-solid fa-star text-dark"></i>
                                        <span
                                            class="cursor-pointer fw-600 fs-8">{{ number_format($reviewdata->star, 1) }}</span>
                                    </div>
                                </div>
                                <div class="d-flex gap-1 align-items-center text-muted">
                                    <i class="fa-solid fa-calendar-days fs-8"></i>
                                    <span class="fs-8">
                                        {{ helper::date_format($reviewdata->created_at, $vdata) }}</span>
                                </div>
                            </div>
                        </div>
                        <p class="fs-13 text-muted mt-2">
                            <span><i class="fa-solid fa-quote-left"></i></span>
                            {{ Str::limit($reviewdata->description, 180) }}
                            <span><i class="fa-solid fa-quote-right"></i></span>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    @if (@helper::checkaddons('customer_login'))
        @if (helper::appdata($vdata)->checkout_login_required == 1)
            @if ($orders > 0 && $rattingcount == 0)
                <div class="offcanvas-footer p-3">
                    <div class="btn btn-primary w-100 py-2 fw-500 fs-15 py-3 text-center"
                        onclick="postreview('{{ $getitem->id }}','{{ $getitem->item_name }}')">
                        {{ trans('labels.add_ratting') }}
                    </div>
                </div>
            @endif
        @endif
    @endif
</div>

<!-- Ratings add Modal -->
<div class="modal fade" id="ratingsadd" tabindex="-1" aria-labelledby="ratingsaddLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header pro-title">
                <h4 class="modal-title line-2" id="ratingsaddLabel"></h4>
                <button type="button" class="btn-close rounded-2 shadow-lg border" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="{{ helper::storefront_url($storeinfo, '/postreview') }}" method="POST">
                @csrf
                <!-- star -->
                <div class="modal-body">
                    <input type="hidden" name="item_id" id="item_id" value="">
                    <div class="rating mb-3">
                        <select class="form-select border bg-lights py-2 px-3" name="ratting"
                            aria-label="Default select example">
                            <option value="5" selected="">★★★★★ (5/5)</option>
                            <option value="4">★★★★☆ (4/5)</option>
                            <option value="3">★★★☆☆ (3/5)</option>
                            <option value="2">★★☆☆☆ (2/5)</option>
                            <option value="1">★☆☆☆☆ (1/5)</option>
                        </select>
                    </div>
                    <textarea class="border w-100 p-2" name="review" id="rating" cols="30" rows="10"
                        placeholder="Your review"></textarea>

                </div>

                <div class="modal-footer">
                    <div class="d-sm-flex justify-content-between w-100">
                        <a class="btn btn-danger col-sm-6 col-12 my-0"
                            data-bs-dismiss="modal">{{ trans('labels.cancel') }}</a>
                        <button type="submit"
                            class="btn btn-store col-sm-6 col-12 my-0 mx-sm-1 mt-md-0 mt-3">{{ trans('labels.submit') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
