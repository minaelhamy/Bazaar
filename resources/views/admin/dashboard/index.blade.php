@extends('admin.layout.default')
@section('content')
    @php
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
    @endphp
    @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
        @if (@helper::otherappdata(1)->notice_on_off == 1)
            <div class="card mb-3 notice_card border-0 box-shadow">
                <div class="card-body">
                    <div class="d-flex flex-wrap flex-sm-nowrap gap-3">
                        <div class="d-flex justify-content-between col-12 col-sm-auto">
                            <div class="alert-icons rgb-danger-light col-auto">
                                <i class="fa-regular fa-circle-exclamation text-danger"></i>
                            </div>
                            <div class="d-sm-none">
                                <div class="close-button cursor-pointer" id="close-btn3">
                                    <i class="fa-solid fa-xmark text-danger"></i>
                                </div>
                            </div>
                        </div>
                        <div class="w-100">
                            <div class="d-flex gap-2 align-items-center mb-2 justify-content-between">
                                <h6 class="line-2 color-changer fs-17">
                                    {{ @helper::otherappdata(1)->notice_title }}
                                </h6>
                                <div class="d-sm-block d-none">
                                    <div class="close-button cursor-pointer" id="close-btn2">
                                        <i class="fa-solid fa-xmark text-danger"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="text-muted fs-13 m-0">
                                {{ @helper::otherappdata(1)->notice_description }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
    <div class="d-flex mb-3">
        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{ trans('labels.dashboard') }}</h5>
    </div>
    <div class="row g-3 mb-3">
        @php
            if (Auth::user()->type == 4) {
                $vendor_id = Auth::user()->vendor_id;
            } else {
                $vendor_id = Auth::user()->id;
            }
            $user = App\Models\User::where('id', $vendor_id)->first();
        @endphp
        <div class="col-xl-6 col-12">
            <div class="row g-3">
                @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                    <div class="col-md-6">
                        <div class="card border-0 rgb-secondary-light box-shadow h-100">
                            <div class="card-body">
                                <div class="dashboard-card">
                                    <span class="card-icon bg-secondary">
                                        <i class="fa-regular fa-user fs-5"></i>
                                    </span>
                                    <span class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                        <p class="text-dark color-changer fs-15 fw-500 mb-1">{{ trans('labels.users') }}</p>
                                        <h5 class="color-changer fw-600">{{ $totalvendors }}</h4>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 rgb-info-light box-shadow h-100">
                            <div class="card-body">
                                <div class="dashboard-card">
                                    <span class="card-icon bg-info">
                                        <i class="fa-regular fa-medal fs-5"></i>
                                    </span>
                                    <span class="{{ session()->get('direction') == 2 ? 'text-start' : 'text-end' }}">
                                        <p class="text-dark color-changer fs-15 fw-500 mb-1">{{ trans('labels.pricing_plans') }}</p>
                                        <h5 class="color-changer fw-600">{{ $totalplans }}</h4>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                    <div class="col-md-6">
                        <div class="card border-0 rgb-secondary-light box-shadow h-100">
                            <div class="card-body">
                                <div class="dashboard-card">
                                    <span class="card-icon bg-secondary">
                                        <i class="fa-solid fa-list-timeline fs-5"></i>
                                    </span>
                                    <span class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                        <p class="text-dark color-changer fs-15 fw-500 mb-1">{{ trans('labels.products') }}</p>
                                        <h5 class="color-changer fw-600">{{ $totalvendors }}</h4>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 box-shadow rgb-info-light h-100">
                            <div class="card-body">
                                <div class="dashboard-card">
                                    <span class="card-icon bg-info">
                                        <i class="fa-regular fa-medal fs-5"></i>
                                    </span>
                                    <span class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                        <p class="text-dark color-changer fs-15 fw-500 mb-1">{{ trans('labels.current_plan') }}</p>
                                        @if (!empty($currentplanname))
                                            <h5 class="color-changer fw-600"> {{ @$currentplanname->name }} </h4>
                                            @else
                                                <i class="fa-regular fa-exclamation-triangle m-0 text-muted"></i>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-md-6">
                    <div class="card rgb-dark-light border-0 box-shadow h-100">
                        <div class="card-body">
                            <div class="dashboard-card">
                                <span class="card-icon bg-dark">
                                    @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                                        <i class="fa-solid fa-ballot-check fs-5"></i>
                                    @else
                                        <i class="fa-regular fa-cart-shopping fs-5"></i>
                                    @endif
                                </span>
                                <span class="{{ session()->get('direction') == 2 ? 'text-start' : 'text-end' }}">
                                    <p class="text-dark color-changer fs-15 fw-500 mb-1">
                                        {{ Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1) ? trans('labels.transaction') : trans('labels.orders') }}
                                    </p>
                                    <h5 class="color-changer fw-600">{{ $totalorders }}</h5>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card rgb-danger-light border-0 box-shadow h-100">
                        <div class="card-body">
                            <div class="dashboard-card">
                                <span class="card-icon bg-danger">
                                    <i class="fa-regular fa-money-bill-1-wave fs-5"></i>
                                </span>
                                <span class="{{ session()->get('direction') == 2 ? 'text-start' : 'text-end' }}">
                                    <p class="text-dark color-changer fs-15 fw-500 mb-1">{{ trans('labels.revenue') }}</p>
                                    <h5 class="color-changer fw-600">
                                        {{ helper::currency_formate($totalrevenue, $vendor_id) }}</h4>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-12">
            <div class="card border-0 box-shadow h-100 fixed-bg-card-changer">
                <div class="card-body">

                    <div class="d-flex flex-wrap justify-content-sm-between justify-content-center gap-2">
                        <div
                            class="col-xxl-8 col-xl-7 col-lg-8 col-md-8 col-sm-7 d-flex flex-column gap-2 justify-content-center align-items-start">
                            <h5 class="text-dark fw-600 d-flex gap-2 align-items-center">
                                <img src="{{ helper::image_path(@Auth::user()->image) }}"
                                    class="object border rounded-circle dasbord-img" alt="">
                                <small class="text-dark color-changer">{{ @Auth::user()->name }}</small>
                            </h5>
                            <p class="text-muted fs-7 m-0 line-3">{{ trans('labels.dashboard_description') }}</p>
                            <div class="dropdown">
                                <a class="btn btn-secondary fs-7 text-light fw-500 dropdown-toggle" href="#"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-regular fa-plus"></i> {{ trans('labels.quick_add') }}
                                </a>
                                @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                    <ul class="dropdown-menu fw-500 fs-7 text-dark">
                                        <li><a class="dropdown-item py-2 {{ helper::check_menu(@Auth::user()->role_id, 'role_products') == 1 ? 'd-block' : 'd-none' }}"
                                                href="{{ URL::to('/admin/products') }}">{{ trans('labels.products') }}</a>
                                        </li>
                                        <li><a class="dropdown-item py-2 {{ helper::check_menu(@Auth::user()->role_id, 'role_categories') == 1 ? 'd-block' : 'd-none' }}"
                                                href="{{ URL::to('/admin/categories') }}">{{ trans('labels.categories') }}
                                            </a></li>
                                        <li><a class="dropdown-item py-2 {{ helper::check_menu(@Auth::user()->role_id, 'role_settings') == 1 ? 'd-block' : 'd-none' }}"
                                                href="{{ URL::to('/admin/basic_settings') }}">{{ trans('labels.basic_settings') }}</a>
                                        </li>
                                    </ul>
                                @else
                                    <ul class="dropdown-menu fw-500 p-0 bg-body-secondary fs-7 overflow-hidden">
                                        <li><a class="dropdown-item py-2"
                                                href="{{ URL::to('admin/users') }}">{{ trans('labels.restaurants') }}</a>
                                        </li>
                                        <li><a class="dropdown-item py-2"
                                                href="{{ URL::to('admin/plan') }}">{{ trans('labels.pricing_plans') }}
                                            </a></li>
                                        <li><a class="dropdown-item py-2"
                                                href="{{ URL::to('/admin/basic_settings') }}">{{ trans('labels.basic_settings') }}</a>
                                        </li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                        @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                            @php
                                if ($user->custom_domain == null) {
                                    $url = URL::to('/' . $user->slug);
                                } else {
                                    $url = 'https://' . $user->custom_domain;
                                }
                            @endphp
                            <div
                                class="col-xxl-3 col-xl-4 mt-2 mt-sm-0 col-lg-3 col-md-3 col-sm-5 gap-2 d-flex flex-column justify-content-center align-items-center">
                                <img src="https://qrcode.tec-it.com/API/QRCode?data={{ $url }}&choe=UTF-8"
                                    class="object quer-code" alt="">
                                <div class="d-flex mt-sm-2">
                                    <button class="btn btn-primary fw-500 fs-7" id="copyButton">
                                        <i class="fa-regular fa-clone"></i> {{ trans('labels.copy_link') }}
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-md-8 mb-3">
            <div class="card border-0 box-shadow h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3 border-bottom pb-3 justify-content-between">
                        <h5 class="card-title color-changer m-0">{{ trans('labels.revenue') }}</h5>
                        <select class="form-select form-select-sm w-auto" id="revenueyear"
                            data-url="{{ URL::to('/admin/dashboard') }}">
                            @if (count($revenue_years) > 0 && !in_array(date('Y'), array_column($revenue_years->toArray(), 'year')))
                                <option value="{{ date('Y') }}" selected>{{ date('Y') }}</option>
                            @endif
                            @forelse ($revenue_years as $revenue)
                                <option value="{{ $revenue->year }}" {{ date('Y') == $revenue->year ? 'selected' : '' }}>
                                    {{ $revenue->year }}
                                </option>
                            @empty
                                <option value="" selected disabled>{{ trans('labels.select') }}</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="row">
                        <canvas id="revenuechart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 box-shadow h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                        <h5 class="card-title color-changer m-0">
                            {{ Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1) ? trans('labels.users') : trans('labels.orders') }}
                        </h5>
                        <select class="form-select form-select-sm w-auto" id="doughnutyear"
                            data-url="{{ request()->url() }}">
                            @if (count($doughnut_years) > 0 && !in_array(date('Y'), array_column($doughnut_years->toArray(), 'year')))
                                <option value="{{ date('Y') }}" selected>{{ date('Y') }}</option>
                            @endif
                            @forelse ($doughnut_years as $useryear)
                                <option value="{{ $useryear->year }}"
                                    {{ date('Y') == $useryear->year ? 'selected' : '' }}>{{ $useryear->year }}
                                </option>
                            @empty
                                <option value="" selected disabled>{{ trans('labels.select') }}</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="row">
                        <canvas id="doughnut"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
        @php
            $ran = [
                'gradient-1',
                'gradient-2',
                'gradient-3',
                'gradient-4',
                'gradient-5',
                'gradient-6',
                'gradient-7',
                'gradient-8',
                'gradient-9',
            ];
        @endphp
        <div class="row g-3 mb-3">
            <div class="col-xl-6">
                <div class="card border-0 box-shadow h-100">
                    <div class="card-body">
                        <h5 class="card-title pb-3 color-changer border-bottom">{{ trans('labels.top_products') }}</h5>
                        <div class="table-responsive" id="table-items">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="fs-15 fw-500">{{ trans('labels.image') }}</th>
                                        <th class="fs-15 fw-500">{{ trans('labels.item_name') }}</th>
                                        <th class="fs-15 fw-500">{{ trans('labels.category') }}</th>
                                        <th class="fs-15 fw-500">{{ trans('labels.orders') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if (count($topitems) > 0)
                                        @foreach (@$topitems as $row)
                                            <tr class="fs-7 fw-500 text-dark align-middle">
                                                <td>
                                                    <img src="{{ Helper::image_path($row['product_image']->image) }}"
                                                        class="rounded hw-50 object" alt="">
                                                </td>
                                                <td>
                                                    <a
                                                        href="{{ URL::to('admin/products/edit-' . $row->slug) }}" class="td_a">{{ $row->item_name }}</a>
                                                </td>
                                                <td>{{ @$row['category_info']->name }}</td>
                                                <td>
                                                    @php
                                                        $per =
                                                            $getorderdetailscount > 0
                                                                ? ($row->item_order_counter * 100) /
                                                                    $getorderdetailscount
                                                                : 0;
                                                    @endphp
                                                    {{ number_format($per, 2) }}%
                                                    <div class="progress h-10-px">
                                                        <div class="progress-bar gradient-6 {{ $ran[array_rand($ran, 1)] }}"
                                                            style="width: {{ $per }}%;" role="progressbar">
                                                            <span class="sr-only">{{ $per }}%
                                                                {{ trans('labels.orders') }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card border-0 box-shadow h-100">
                    <div class="card-body">
                        <h5 class="card-title pb-3 color-changer border-bottom">{{ trans('labels.top_customers') }}</h5>
                        <div class="table-responsive" id="table-users">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="fs-15 fw-500">{{ trans('labels.image') }}</th>
                                        <th class="fs-15 fw-500">{{ trans('labels.customer_info') }}</th>
                                        <th class="fs-15 fw-500">{{ trans('labels.customer_email') }}</th>
                                        <th class="fs-15 fw-500">{{ trans('labels.orders') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @if (count($topusers) > 0)
                                        @foreach (@$topusers as $user)
                                            <tr class="fs-7 fw-500 text-dark align-middle">
                                                <td>
                                                    <img src="{{ Helper::image_path($user->profile_image) }}"
                                                        class="rounded hw-50 object" alt="">
                                                </td>
                                                <td>
                                                    <div class="fs-7 fw-500 td_a">
                                                        <p>{{ $user->name }}</p>
                                                        <p>{{ $user->mobile }}</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    {{ $user->email }}
                                                </td>
                                                <td>
                                                    {{ number_format($per, 2) }}%
                                                    <div class="progress h-10-px">
                                                        <div class="progress-bar {{ $ran[array_rand($ran, 1)] }}"
                                                            style="width: {{ $per }}%;" role="progressbar">
                                                            <span class="sr-only">{{ $per }}%
                                                                {{ trans('labels.orders') }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card border-0 box-shadow">
                <div class="card-body">
                    <h5 class="card-title color-changer pb-3 border-bottom">
                        {{ Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1) ? trans('labels.today_transaction') : trans('labels.processing_orders') }}
                    </h5>
                    <div class="table-responsive">
                        @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                            @include('admin.dashboard.admintransaction')
                        @else
                            @include('admin.orders.orderstable')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.getElementById('copyButton').addEventListener('click', function() {
            var urlToCopy = "{{ @$url }}";
            var tempInput = document.createElement('input');
            tempInput.value = urlToCopy;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            var confirmationMessage = 'Link copied to clipboard!';
            toastr.success(confirmationMessage);
        });
    </script>
    <!--- Admin -------- users-chart-script --->
    <!--- VendorAdmin -------- orders-count-chart-script --->
    <script type="text/javascript">
        var doughnut = null;
        var doughnutlabels = {{ Js::from($doughnutlabels) }};
        var doughnutdata = {{ Js::from($doughnutdata) }};
    </script>
    <!--- Admin ------ revenue-by-plans-chart-script --->
    <!--- vendorAdmin ------ revenue-by-orders-script --->
    <script type="text/javascript">
        var revenuechart = null;
        var labels = {{ Js::from($revenuelabels) }};
        var revenuedata = {{ Js::from($revenuedata) }};
    </script>
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/dashboard.js') }}"></script>
@endsection
