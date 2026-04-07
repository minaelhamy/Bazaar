@include('front.theme.header')
<!------ breadcrumb ------>
<section class="breadcrumb-sec bg-change-mode">

    <div class="container">

        <nav aria-label="breadcrumb">

            <ol class="breadcrumb">

                <li class="breadcrumb-item text-dark"><a class="text-dark color-changer"
                        href="{{ helper::storefront_url($storeinfo, '/') }}">{{ trans('labels.home') }}</a>
                </li>

                <li class="text-muted breadcrumb-item {{ session()->get('direction') == 2 ? 'rtl' : '' }} text-dark active"
                    aria-current="page">{{ trans('labels.wallet') }}
                </li>

            </ol>

        </nav>

    </div>

</section>

<section class="product-prev-sec product-list-sec">
    <div class="container">
        <div class="user-bg-color mb-5">
            <div class="container">
                <div class="row">
                    @include('front.theme.sidebar')
                    <div class="col-xl-9 col-lg-8 col-xxl-9 col-12">
                        <div class="card-v p-0 border rounded user-form">
                            <div class="settings-box">
                                <div class="settings-box-header flex-wrap border-bottom px-4 py-3">
                                    <div class="mb-0 d-flex color-changer align-items-center gap-3">
                                        <i class="fa-light fa-wallet fs-4"></i>
                                        <div>
                                            <span class="fs-5 fw-500">
                                                {{ trans('labels.wallet_balance') }}
                                            </span>
                                            <p class="text-success fs-6 fw-600">
                                                {{ helper::currency_formate(Auth::user()->wallet, $storeinfo->id) }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-sm-auto col-12">
                                        <a href="{{ helper::storefront_url($storeinfo, '/wallet/addmoney') }}"
                                            class="w-100 border-0 btn btn-store m-0 mt-2 mt-sm-0 align-items-center fs-15 fw-500 justify-content-center p-2 px-3 d-flex gap-2">
                                            <i class="fa-regular fa-plus"></i>
                                            {{ trans('labels.add_money') }}
                                        </a>
                                    </div>
                                </div>
                                <div class="settings-box-body p-3 dashboard-section">
                                    @if ($gettransactions->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped align-middle table-hover">
                                                <thead class="table-light">
                                                    <tr class="fs-7 fw-600">
                                                        <th scope="col">{{ trans('labels.date') }}</th>
                                                        <th scope="col"> {{ trans('labels.amount') }} </th>
                                                        <th scope="col">{{ trans('labels.remark') }}</th>
                                                        <th scope="col">{{ trans('labels.status') }}</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach ($gettransactions as $row)
                                                        <tr class="fs-7">
                                                            <td>{{ helper::date_format($row->created_at, $storeinfo->id) }}<br>
                                                                {{ helper::time_format($row->created_at, $storeinfo->id) }}
                                                            </td>
                                                            <td>
                                                                @if ($row->tips > 0)
                                                                    ({{ helper::currency_formate($row->amount, $storeinfo->id) }}
                                                                    +
                                                                    {{ trans('labels.tips') . ' : ' . helper::currency_formate($row->tips, $storeinfo->id) }})
                                                                @else
                                                                    {{ helper::currency_formate($row->amount, $storeinfo->id) }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($row->transaction_type == 2)
                                                                    {{ trans('labels.order_placed') }}
                                                                    <span>{{ $row->order_number }} </span>
                                                                @elseif ($row->transaction_type == 3)
                                                                    {{ trans('labels.order_cancel') }}
                                                                    <span>{{ $row->order_number }} </span>
                                                                @else
                                                                    {{ trans('labels.wallet_recharge') }}
                                                                    <span>{{ @helper::getpayment($row->payment_type, $storeinfo->id)->payment_name }}</span>
                                                                    <span>{{ $row->payment_id }} </span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($row->transaction_type == 2)
                                                                    <div
                                                                        class="badge bg-debit custom-badge bg-cancelled rounded-0">
                                                                        <span> {{ trans('labels.debit') }}</span>
                                                                    </div>
                                                                @else
                                                                    <div
                                                                        class="badge bg-debit custom-badge rounded-0 bg-completed">
                                                                        <span> {{ trans('labels.credit') }}</span>
                                                                    </div>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <div class="d-flex justify-content-center">
                                                {{ $gettransactions->links() }}
                                            </div>
                                        </div>
                                    @else
                                        @include('front.no_data')
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- newsletter -->
@include('front.newsletter')
<!-- newsletter -->
@include('front.theme.footer')
