<div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6 col-sm-6" data-id="{{ $plandata->id }}">
    <div class="card border-0 box-shadow h-100 {{ $plan_id == $plandata->id ? 'plan-card-active' : 'border-0' }} handle">
        <div class="card-header bg-secondary sub-plan">
            <div class="d-flex justify-content-between">
                <h5 class="settings-color">{{ $plandata->name }}</h5>
                @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                    <a tooltip="{{ trans('labels.move') }}"><i class="fa-light fa-up-down-left-right"></i></a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <h5 class="mb-1 settings-color color-changer">{{ helper::currency_formate($plandata->price, '') }}

                    <span class="fs-7 text-muted">/
                        @if ($plandata->plan_type == 1)
                            @if ($plandata->duration == 1)
                                {{ trans('labels.one_month') }}
                            @elseif($plandata->duration == 2)
                                {{ trans('labels.three_month') }}
                            @elseif($plandata->duration == 3)
                                {{ trans('labels.six_month') }}
                            @elseif($plandata->duration == 4)
                                {{ trans('labels.one_year') }}
                            @elseif($plandata->duration == 5)
                                {{ trans('labels.lifetime') }}
                            @endif
                        @endif
                        @if ($plandata->plan_type == 2)
                            {{ $plandata->days }}
                            {{ $plandata->days > 1 ? trans('labels.days') : trans('labels.day') }}
                        @endif

                    </span>
                </h5>
                @if ($plandata->tax != null && $plandata->tax != '')
                    <small class="text-danger">{{ trans('labels.exclusive_taxes') }}</small><br>
                @else
                    <small class="text-success">{{ trans('labels.inclusive_taxes') }}</small> <br>
                @endif
                <small class="text-muted text-center">{{ Str::limit($plandata->description, 150) }}</small>
            </div>
            <ul>

                @php $features = ($plandata->features == null ? null : explode('|', $plandata->features));@endphp

                <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                    <span class="mx-2">
                        {{ $plandata->order_limit == -1 ? trans('labels.unlimited') : $plandata->order_limit }}
                        {{ $plandata->order_limit > 1 || $plandata->order_limit == -1 ? trans('labels.products') : trans('labels.product') }}
                    </span>
                </li>
                <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                    <span class="mx-2">
                        {{ $plandata->appointment_limit == -1 ? trans('labels.unlimited') : $plandata->appointment_limit }}
                        {{ $plandata->appointment_limit > 1 || $plandata->appointment_limit == -1 ? trans('labels.orders') : trans('labels.order') }}
                    </span>
                </li>
                @php
                    $themes = [];
                    if ($plandata->themes_id != '' && $plandata->themes_id != null) {
                        $themes = explode('|', $plandata->themes_id);
                } @endphp
                <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                    <span class="mx-2">{{ count($themes) }}
                        {{ count($themes) > 1 ? trans('labels.themes') : trans('labels.theme') }}
                        @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                            <a onclick="themeinfo('{{ $plandata->id }}','{{ $plandata->themes_id }}','{{ $plandata->name }}')"
                                tooltip="{{ trans('labels.info') }}" class="cursor-pointer"> <i
                                    class="fa-regular fa-circle-info"></i> </a>
                        @endif
                    </span>
                </li>
                @if (@helper::checkaddons('coupon'))
                    @if ($plandata->coupons == 1)
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2">{{ trans('labels.coupons') }}</span>
                        </li>
                    @endif
                @endif
                @if (@helper::checkaddons('custom_domain'))
                    @if ($plandata->custom_domain == 1)
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2">{{ trans('labels.custome_domain') }}</span>
                        </li>
                    @endif
                @endif
                @if (@helper::checkaddons('blog'))
                    @if ($plandata->blogs == 1)
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2">{{ trans('labels.blogs') }}</span>
                        </li>
                    @endif
                @endif
                @if (@helper::checkaddons('google_login'))
                    @if ($plandata->google_login == 1)
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2">{{ trans('labels.google_login') }}</span>
                        </li>
                    @endif
                @endif
                @if (@helper::checkaddons('facebook_login'))
                    @if ($plandata->facebook_login == 1)
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2">{{ trans('labels.facebook_login') }}</span>
                        </li>
                    @endif
                @endif
                @if (@helper::checkaddons('notification'))
                    @if ($plandata->sound_notification == 1)
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2">{{ trans('labels.sound_notification') }}</span>
                        </li>
                    @endif
                @endif
                @if (@helper::checkaddons('whatsapp_message'))
                    @if ($plandata->whatsapp_message == 1)
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2">{{ trans('labels.whatsapp_message') }}</span>
                        </li>
                    @endif
                @endif
                @if (@helper::checkaddons('telegram_message'))
                    @if ($plandata->telegram_message == 1)
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2">{{ trans('labels.telegram_message') }}</span>
                        </li>
                    @endif
                @endif
                @if (@helper::checkaddons('vendor_app'))
                    @if ($plandata->vendor_app == 1)
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2">{{ trans('labels.vendor_app_available') }}</span>
                        </li>
                    @endif
                @endif
                @if (@helper::checkaddons('user_app'))
                    @if ($plandata->customer_app == 1)
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2">{{ trans('labels.customer_app') }}</span>
                        </li>
                    @endif
                @endif
                @if (@helper::checkaddons('pos'))
                    @if ($plandata->pos == 1)
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2">{{ trans('labels.pos') }}</span>
                        </li>
                    @endif
                @endif
                @if (@helper::checkaddons('pwa'))
                    @if ($plandata->pwa == 1)
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2">{{ trans('labels.pwa') }}</span>
                        </li>
                    @endif
                @endif
                @if (@helper::checkaddons('employee'))
                    @if ($plandata->role_management == 1)
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2">{{ trans('labels.role_management') }}</span>
                        </li>
                    @endif
                @endif
                @if (@helper::checkaddons('pixel'))
                    @if ($plandata->pixel == 1)
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2">{{ trans('labels.pixel') }}</span>
                        </li>
                    @endif
                @endif
                @if ($features != null)
                    @foreach ($features as $feature)
                        <li class="mb-2 d-flex fs-7 color-changer"> <i class="fa-regular fa-circle-check text-secondary "></i>
                            <span class="mx-2"> {{ $feature }} </span>
                        </li>
                    @endforeach
                @endif
            </ul>

        </div>
        <div class="card-footer bg-transparent border-top-0 my-2 text-center">
            @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    @if ($plandata->is_available == 1)
                        <a tooltip="{{ trans('labels.active') }}"
                            @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('admin/plan/status_change-' . $plandata->id . '/2') }}')" @endif
                            class="btn btn-success hov btn-sm {{ Auth::user()->type == 4 ? (helper::check_access('role_pricing_plans', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">
                            <i class="fas fa-check"></i>
                        </a>
                    @elseif ($plandata->is_available == 2)
                        <a tooltip="{{ trans('labels.inactive') }}"
                            @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('admin/plan/status_change-' . $plandata->id . '/1') }}')" @endif
                            class="btn btn-danger hov btn-sm {{ Auth::user()->type == 4 ? (helper::check_access('role_pricing_plans', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">
                            <i class="fas fa-close mx-1"></i>
                        </a>
                    @endif
                    <a href="{{ URL::to('admin/plan/edit-' . $plandata->id) }}"
                        class="btn btn-info hov btn-sm {{ Auth::user()->type == 4 ? (helper::check_access('role_pricing_plans', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
                        tooltip="{{ trans('labels.edit') }}">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </a>
                    <a href="javascript:void(0)"
                        class="btn btn-danger hov btn-sm {{ Auth::user()->type == 4 ? (helper::check_access('role_pricing_plans', Auth::user()->role_id, Auth::user()->vendor_id, 'delete') == 1 ? '' : 'd-none') : '' }}"
                        @if (env('Environment') == 'sendbox') onclick="myFunction()" @else onclick="statusupdate('{{ URL::to('admin/plan/delete-' . $plandata->id) }}')" @endif
                        tooltip="{{ trans('labels.delete') }}">
                        <i class="fa-regular fa-trash"></i>
                    </a>
                </div>
            @else
                @if ($plan_id == $plandata->id)
                    @if (@$data['original']['status'] == '2')
                        @if ($plandata->price > 0)
                            @if (@$plandata->duration == 5)
                                <small
                                    class="text-success d-block"><span>{{ @$data['original']['plan_message'] }}</span></small>
                            @else
                                @if (@$data['original']['plan_date'] > date('Y-m-d'))
                                    <small class="text-dark d-block">{{ @$data['original']['plan_message'] }}
                                        : <span
                                            class="text-success">{{ $data['original']['plan_date'] != '' ? helper::date_format($data['original']['plan_date'], $vendor_id) : '' }}</span>
                                    </small>
                                @else
                                    <small class="text-dark d-block">{{ @$data['original']['plan_message'] }}
                                        : <span
                                            class="text-danger">{{ $data['original']['plan_date'] != '' ? helper::date_format($data['original']['plan_date'], $vendor_id) : '' }}</span>
                                    </small>
                                @endif
                            @endif

                            @if (@$data['original']['showclick'] == 1)
                                <a href="{{ URL::to('admin/plan/selectplan-' . $plandata->id) }}"
                                    class="btn btn-sm hov btn-primary d-block mt-2 {{ Auth::user()->type == 4 ? (helper::check_access('role_pricing_plans', Auth::user()->role_id, $vendor_id, 'add') == 1 ? '' : 'd-none') : '' }}">{{ trans('labels.subscribe') }}</a>
                            @endif
                        @else
                            @if (@$data['original']['plan_date'] > date('Y-m-d'))
                                <small class="text-dark d-block">{{ @$data['original']['plan_message'] }}
                                    <span class="text-success">
                                        {{ $data['original']['plan_date'] != '' ? helper::date_format($data['original']['plan_date'], $vendor_id) : '' }}
                                    </span>
                                </small>
                                <a href="{{ URL::to('admin/plan/selectplan-' . $plandata->id) }}"
                                    class="btn btn-sm btn-primary hov d-block {{ Auth::user()->type == 4 ? (helper::check_access('role_pricing_plans', Auth::user()->role_id, $vendor_id, 'add') == 1 ? '' : 'd-none') : '' }}">{{ trans('labels.subscribe') }}</a>
                            @else
                                <small class="text-dark d-block">{{ @$data['original']['plan_message'] }}
                                    <span class="text-danger">
                                        {{ $data['original']['plan_date'] != '' ? helper::date_format($data['original']['plan_date'], $vendor_id) : '' }}</span>
                                </small>
                                <a href="{{ URL::to('admin/plan/selectplan-' . $plandata->id) }}"
                                    class="btn btn-sm btn-primary hov d-block d-none {{ Auth::user()->type == 4 ? (helper::check_access('role_pricing_plans', Auth::user()->role_id, $vendor_id, 'add') == 1 ? '' : 'd-none') : '' }}">{{ trans('labels.subscribe') }}</a>
                            @endif
                        @endif
                    @elseif(@$data['original']['status'] == '1')
                        @if (@$plandata->duration == 5)
                            <small class="text-dark">
                                <span>
                                    {{ @$data['original']['plan_message'] }}
                                </span>
                            </small>
                        @else
                            @if ($data['original']['plan_date'] != '')
                                <small class="text-dark">
                                    {{ @$data['original']['plan_message'] }}: <span
                                        class="text-success">{{ $data['original']['plan_date'] != '' ? helper::date_format($data['original']['plan_date'], $vendor_id) : '' }}</span>
                                </small>
                                <a href="{{ URL::to('admin/plan/selectplan-' . $plandata->id) }}"
                                    class="btn btn-sm hov btn-primary d-block py-2 mt-1 @if ($purchase_amount <= 0) d-none @endif {{ Auth::user()->type == 4 ? (helper::check_access('role_pricing_plans', Auth::user()->role_id, $vendor_id, 'manage') == 1 ? '' : 'd-none') : '' }}">{{ trans('labels.subscribe') }}</a>
                            @else
                                <small class="text-success">{{ @$data['original']['plan_message'] }}</small>
                                <a href="{{ URL::to('admin/plan/selectplan-' . $plandata->id) }}"
                                    class="btn btn-sm hov btn-primary d-block py-2 mt-1 {{ Auth::user()->type == 4 ? (helper::check_access('role_pricing_plans', Auth::user()->role_id, $vendor_id, 'manage') == 1 ? '' : 'd-none') : '' }}">{{ trans('labels.subscribe') }}</a>
                            @endif
                        @endif
                    @else
                        -
                    @endif
                @else
                    @if ($plandata->price > 0)
                        <a href="{{ URL::to('admin/plan/selectplan-' . $plandata->id) }}"
                            class="btn btn-sm btn-primary hov hov d-block py-2 {{ Auth::user()->type == 4 ? (helper::check_access('role_pricing_plans', Auth::user()->role_id, $vendor_id, 'manage') == 1 ? '' : 'd-none') : '' }}">{{ trans('labels.subscribe') }}</a>
                    @elseif ((float) $purchase_amount > $plandata->price)
                    @else
                        <a href="{{ URL::to('admin/plan/selectplan-' . $plandata->id) }}"
                            class="btn btn-sm btn-primary hov d-block py-2 {{ Auth::user()->type == 4 ? (helper::check_access('role_pricing_plans', Auth::user()->role_id, $vendor_id, 'manage') == 1 ? '' : 'd-none') : '' }}">{{ trans('labels.subscribe') }}</a>
                    @endif
                @endif
            @endif
        </div>
    </div>
</div>
