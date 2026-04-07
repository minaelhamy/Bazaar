<div class="row g-3">
    <div class="col-md-6 col-lg-6 col-xl-3">
        <div class="card rgb-info-light box-shadow h-100 {{ request()->get('status') == '' ? 'border border-primary' : 'border-0' }}">
            @if (request()->is('admin/report'))
                <a
                    href="{{ URL::to(request()->url() . '?status=&customer_id=' . request()->get('customer_id') . '&startdate=' . request()->get('startdate') . '&enddate=' . request()->get('enddate')) }}">
                @elseif(request()->is('admin/orders'))
                    <a href="{{ URL::to('admin/orders?status=') }}">
                    @elseif(request()->is('admin/customers/orders*'))
                        <a href="{{ URL::to('admin/customers/orders-' . @$userinfo->id . '?status=') }}">
            @endif
            <div class="card-body">
                <div class="dashboard-card">
                    <span class="card-icon bg-info">
                        <i class="fa fa-shopping-cart"></i>
                    </span>
                    <span class="{{ session()->get('direction') == 2 ? 'text-start' : 'text-end' }}">
                        <p class="text-dark color-changer fs-15 fw-500 mb-1">{{ trans('labels.total_orders') }}</p>
                        <h5 class="text-dark color-changer fw-600">{{ $totalorders }}</h4>
                    </span>
                </div>
            </div>
            </a>
        </div>
    </div>
    @if (helper::appdata($vendor_id)->product_type == 1)
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div
                class="card box-shadow rgb-warning-light h-100 {{ request()->get('status') == 'processing' ? 'border border-primary' : 'border-0' }}">
                @if (request()->is('admin/report'))
                    <a
                        href="{{ URL::to(request()->url() . '?status=processing&customer_id=' . request()->get('customer_id') . '&startdate=' . request()->get('startdate') . '&enddate=' . request()->get('enddate')) }}">
                    @elseif(request()->is('admin/orders'))
                        <a href="{{ URL::to('admin/orders?status=processing') }}">
                        @elseif(request()->is('admin/customers/orders*'))
                            <a href="{{ URL::to('admin/customers/orders-' . @$userinfo->id . '?status=processing') }}">
                @endif
                <div class="card-body">
                    <div class="dashboard-card">
                        <span class="card-icon bg-warning">
                            <i class="fa fa-hourglass"></i>
                        </span>
                        <span class="{{ session()->get('direction') == 2 ? 'text-start' : 'text-end' }}">
                            <p class="text-dark color-changer fs-15 fw-500 mb-1">{{ trans('labels.processing') }}</p>
                            <h5 class="text-dark color-changer fw-600">{{ $totalprocessing }}</h4>
                        </span>
                    </div>
                </div>
                </a>
            </div>
        </div>
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div
                class="card box-shadow rgb-success-light h-100 {{ request()->get('status') == 'delivered' ? 'border border-primary' : 'border-0' }}">
                @if (request()->is('admin/report'))
                    <a
                        href="{{ URL::to(request()->url() . '?status=delivered&customer_id=' . request()->get('customer_id') . '&startdate=' . request()->get('startdate') . '&enddate=' . request()->get('enddate')) }}">
                    @elseif(request()->is('admin/orders'))
                        <a href="{{ URL::to('admin/orders?status=delivered') }}">
                        @elseif(request()->is('admin/customers/orders*'))
                            <a href="{{ URL::to('admin/customers/orders-' . @$userinfo->id . '?status=delivered') }}">
                @endif
                <div class="card-body">
                    <div class="dashboard-card">
                        <span class="card-icon bg-success">
                            <i class="fa fa-check"></i>
                        </span>
                        <span class="{{ session()->get('direction') == 2 ? 'text-start' : 'text-end' }}">
                            <p class="text-dark color-changer fs-15 fw-500 mb-1">{{ trans('labels.delivered') }}</p>
                            <h5 class="text-dark color-changer fw-600">{{ $totalcompleted }}</h4>
                        </span>
                    </div>
                </div>
                </a>
            </div>
        </div>
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div
                class="card box-shadow h-100 rgb-danger-light {{ request()->get('status') == 'cancelled' ? 'border border-primary' : 'border-0' }}">
                @if (request()->is('admin/report'))
                    <a
                        href="{{ URL::to(request()->url() . '?status=cancelled&customer_id=' . request()->get('customer_id') . '&startdate=' . request()->get('startdate') . '&enddate=' . request()->get('enddate')) }}">
                    @elseif(request()->is('admin/orders'))
                        <a href="{{ URL::to('admin/orders?status=cancelled') }}">
                        @elseif(request()->is('admin/customers/orders*'))
                            <a href="{{ URL::to('admin/customers/orders-' . @$userinfo->id . '?status=cancelled') }}">
                @endif
                <div class="card-body">
                    <div class="dashboard-card">
                        <span class="card-icon bg-danger">
                            <i class="fa fa-close"></i>
                        </span>
                        <span class="{{ session()->get('direction') == 2 ? 'text-start' : 'text-end' }}">
                            <p class="text-dark color-changer fs-15 fw-500 mb-1">{{ trans('labels.cancelled') }}</p>
                            <h5 class="text-dark color-changer fw-600">{{ $totalcancelled }}</h4>
                        </span>
                    </div>
                </div>
                </a>
            </div>
        </div>
    @endif

    @if (request()->is('admin/report*'))
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="card box-shadow rgb-secondary-light h-100">
                <div class="card-body">
                    <div class="dashboard-card">
                        <span class="card-icon bg-secondary">
                            <i class="fa-regular fa-money-bill-1-wave"></i>
                        </span>
                        <span class="{{ session()->get('direction') == 2 ? 'text-start' : 'text-end' }}">
                            <p class="text-dark color-changer fs-15 fw-500 mb-1">{{ trans('labels.revenue') }}</p>
                            <h5 class="text-dark color-changer fw-600">{{ helper::currency_formate($totalrevenue, $vendor_id) }}
                                </h4>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
