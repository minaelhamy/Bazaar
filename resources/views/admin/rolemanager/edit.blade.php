@extends('admin.layout.default')
@section('content')
    @php
        if (Auth::user()->type == 4) {
            $vendor_id = Auth::user()->vendor_id;
        } else {
            $vendor_id = Auth::user()->id;
        }
        $user = App\Models\User::where('id', $vendor_id)->first();
    @endphp
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{ trans('labels.edit') }}</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item text-dark"><a href="{{ URL::to('admin/roles') }}"
                        class="color-changer">{{ trans('labels.roles') }}</a>
                </li>
                <li class="breadcrumb-item active {{ session()->get('direction') == 2 ? 'breadcrumb-rtl' : '' }}"
                    aria-current="page">{{ trans('labels.edit') }}</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card border-0 my-3 box-shadow">
                <div class="card-body">
                    @php $modules = explode('|',$data->module); @endphp
                    <form action="{{ URL::to('admin/roles/update-' . $data->id) }}" method="post">
                        @csrf
                        <div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label" for="">{{ trans('labels.role') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" required
                                        placeholder="{{ trans('labels.role') }}" value="{{ $data->role }}">
                                </div>
                            </div>
                            <h5 class="mb-3 fw-bold color-changer" for="">{{ trans('labels.system_modules') }} <span
                                    class="text-danger">*</span> </h5>
                            <div class="row bg-light rolmangement_dark py-3">
                                <div class="col-sm-4 col-6 cursor-pointer d-block">
                                    <input class="form-check-input" type="checkbox" value="" name="checkall"
                                        id="checkall">
                                    <label
                                        class="form-check-label text-dark fw-600 {{ session()->get('direction') == 2 ? 'ms-5' : 'me-5' }}"
                                        for="checkall">
                                        {{ trans('labels.modules') }}
                                    </label>

                                </div>
                                <div class="col-sm-8 col-6 d-block">
                                    <label
                                        class="form-check-label text-dark fw-600 {{ session()->get('direction') == 2 ? 'ms-5' : 'me-5' }}">
                                        {{ trans('labels.permissions') }}
                                    </label>

                                </div>
                            </div>
                            <div class="row mt-3">

                                <div class="col-4" id="checkboxes">
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value="" name="dashboard"
                                            id="role_dashboard">
                                        <label class="cursor-pointer form-label fs-13" for="role_dashboard">
                                            {{ trans('labels.dashboard') }}
                                        </label>
                                    </div>
                                    @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="addons_manager" id="role_addons_manager">
                                            <label class="cursor-pointer form-label fs-13" for="role_addons_manager">
                                                {{ trans('labels.addons_manager') }}
                                            </label>
                                        </div>
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value="" name="vendors"
                                                id="role_vendors">
                                            <label class="cursor-pointer form-label fs-13" for="role_vendors">
                                                {{ trans('labels.users') }}
                                            </label>
                                        </div>
                                    @endif
                                    @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                        @if (@helper::checkaddons('subscription'))
                                            @if (@helper::checkaddons('pos'))
                                                @php
                                                    if (Auth::user()->type == 2 || Auth::user()->type == 4) {
                                                        $checkplan = App\Models\Transaction::where(
                                                            'vendor_id',
                                                            $vendor_id,
                                                        )
                                                            ->orderByDesc('id')
                                                            ->first();
                                                    }
                                                    if ($user->allow_without_subscription == 1) {
                                                        $pos = 1;
                                                    } else {
                                                        $pos = @$checkplan->pos;
                                                    }
                                                @endphp
                                                @if ($pos == 1)
                                                    <div class="cursor-pointer d-block mb-3">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            name="POS (Point Of Sale)" id="role_pos">
                                                        <label class="cursor-pointer form-label fs-13" for="role_pos">
                                                            {{ trans('labels.pos') }}
                                                        </label>
                                                    </div>
                                                @endif
                                            @endif
                                        @else
                                            @if (@helper::checkaddons('pos'))
                                                <div class="cursor-pointer d-block mb-3">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        name="POS (Point Of Sale)" id="role_pos">
                                                    <label class="cursor-pointer form-label fs-13" for="role_pos">
                                                        {{ trans('labels.pos') }}
                                                    </label>
                                                </div>
                                            @endif
                                        @endif
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value="" name="orders"
                                                id="role_orders">
                                            <label class="cursor-pointer form-label fs-13" for="role_orders">
                                                {{ trans('labels.orders') }}
                                            </label>
                                        </div>

                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value="" name="report"
                                                id="role_report">
                                            <label class="cursor-pointer form-label fs-13" for="role_report">
                                                {{ trans('labels.report') }}
                                            </label>
                                        </div>
                                    @endif

                                    @if (@helper::checkaddons('customer_login'))
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="Customers" id="role_customers">
                                            <label class="cursor-pointer form-label fs-13" for="role_customers">
                                                {{ trans('labels.customers') }}
                                            </label>
                                        </div>
                                    @endif

                                    @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="Categories" id="role_categories">
                                            <label class="cursor-pointer form-label fs-13" for="role_categories">
                                                {{ trans('labels.categories') }}
                                            </label>
                                        </div>
                                    @endif

                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value="" name="Tax"
                                            id="role_tax">
                                        <label class="cursor-pointer form-label fs-13" for="role_tax">
                                            {{ trans('labels.tax') }}
                                        </label>
                                    </div>

                                    @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="Global Extras" id="role_global_extras">
                                            <label class="cursor-pointer form-label fs-13" for="role_global_extras">
                                                {{ trans('labels.global_extras') }}
                                            </label>
                                        </div>
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="Products" id="role_products">
                                            <label class="cursor-pointer form-label fs-13" for="role_products">
                                                {{ trans('labels.products') }}
                                            </label>
                                        </div>
                                        @if (@helper::checkaddons('product_import'))
                                            <div class="cursor-pointer d-block mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    name="import_product" id="role_import_product">
                                                <label class="cursor-pointer form-label fs-13" for="role_import_product">
                                                    {{ trans('labels.product_upload') }}
                                                </label>
                                            </div>
                                        @endif
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="shipping_management" id="role_shipping_management">
                                            <label class="cursor-pointer form-label fs-13" for="role_shipping_management">
                                                {{ trans('labels.shipping_management') }}
                                            </label>
                                        </div>
                                        @if (@helper::checkaddons('question_answer'))
                                            <div class="cursor-pointer d-block mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    name="product_question_answer" id="role_product_question_answer">
                                                <label class="cursor-pointer form-label fs-13"
                                                    for="role_product_question_answer">
                                                    {{ trans('labels.product_question_answer') }}
                                                </label>
                                            </div>
                                        @endif
                                        @if (@helper::checkaddons('shopify'))
                                            <div class="cursor-pointer d-block mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    name="Shopify" id="role_shopify">
                                                <label class="cursor-pointer form-label fs-13" for="role_shopify">
                                                    {{ trans('labels.shopify') }}
                                                </label>
                                            </div>
                                        @endif
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="Sliders" id="role_sliders">
                                            <label class="cursor-pointer form-label fs-13" for="role_sliders">
                                                {{ trans('labels.sliders') }}
                                            </label>
                                        </div>
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="Banner" id="role_banner">
                                            <label class="cursor-pointer form-label fs-13" for="role_banner">
                                                {{ trans('labels.banner') }}
                                            </label>
                                        </div>
                                        @if (@helper::checkaddons('subscription'))
                                            @if (@helper::checkaddons('coupon'))
                                                @php
                                                    $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)
                                                        ->orderByDesc('id')
                                                        ->first();
                                                    if ($user->allow_without_subscription == 1) {
                                                        $coupons = 1;
                                                    } else {
                                                        $coupons = @$checkplan->coupons;
                                                    }
                                                @endphp
                                                @if ($coupons == 1)
                                                    <div class="cursor-pointer d-block mb-3">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            name="Coupons" id="role_coupons">
                                                        <label class="cursor-pointer form-label fs-13" for="role_coupons">
                                                            {{ trans('labels.coupons') }}
                                                        </label>
                                                    </div>
                                                @endif
                                            @endif
                                        @else
                                            @if (@helper::checkaddons('coupon'))
                                                <div class="cursor-pointer d-block mb-3">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        name="Coupons" id="role_coupons">
                                                    <label class="cursor-pointer form-label fs-13" for="role_coupons">
                                                        {{ trans('labels.coupons') }}
                                                    </label>
                                                </div>
                                            @endif
                                        @endif
                                        @if (@helper::checkaddons('top_deals'))
                                            <div class="cursor-pointer d-block mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    name="top_deals" id="role_top_deals">
                                                <label class="cursor-pointer form-label fs-13" for="role_top_deals">
                                                    {{ trans('labels.top_deals') }}
                                                </label>
                                            </div>
                                        @endif
                                        @if (@helper::checkaddons('firebase_notification'))
                                            <div class="cursor-pointer d-block mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    name="firebase_notification" id="role_firebase_notification">
                                                <label class="cursor-pointer form-label fs-13"
                                                    for="role_firebase_notification">
                                                    {{ trans('labels.firebase_notification') }}
                                                </label>
                                            </div>
                                        @endif
                                    @endif
                                    @if (@helper::checkaddons('subscription'))
                                        @if ($user->allow_without_subscription != 1)
                                            <div class="cursor-pointer d-block mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    name="Subscription Plans" id="role_pricing_plans">
                                                <label class="cursor-pointer form-label fs-13" for="role_pricing_plans">
                                                    {{ trans('labels.pricing_plans') }}
                                                </label>
                                            </div>
                                        @endif
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="Transactions" id="role_transaction">
                                            <label class="cursor-pointer form-label fs-13" for="role_transaction">
                                                {{ trans('labels.transaction') }}
                                            </label>
                                        </div>
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="Payment Methods" id="role_payment_methods">
                                            <label class="cursor-pointer form-label fs-13" for="role_payment_methods">
                                                {{ trans('labels.payment_methods') }}
                                            </label>
                                        </div>
                                    @else
                                        @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                            <div class="cursor-pointer d-block mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    name="Payment Methods" id="role_payment_methods">
                                                <label class="cursor-pointer form-label fs-13" for="role_payment_methods">
                                                    {{ trans('labels.payment_methods') }}
                                                </label>
                                            </div>
                                        @endif
                                    @endif
                                    @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="cities" id="role_cities">
                                            <label class="cursor-pointer form-label fs-13" for="role_cities">
                                                {{ trans('labels.cities') }}
                                            </label>
                                        </div>
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="areas" id="role_areas">
                                            <label class="cursor-pointer form-label fs-13" for="role_areas">
                                                {{ trans('labels.areas') }}
                                            </label>
                                        </div>
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="store_categories" id="role_store_categories">
                                            <label class="cursor-pointer form-label fs-13" for="role_store_categories">
                                                {{ trans('labels.store_categories') }}
                                            </label>
                                        </div>
                                        @if (@helper::checkaddons('custom_domain'))
                                            <div class="cursor-pointer d-block mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    name="Custom Domains" id="role_custom_domains">
                                                <label class="cursor-pointer form-label fs-13" for="role_custom_domains">
                                                    {{ trans('labels.custom_domains') }}
                                                </label>
                                            </div>
                                        @endif
                                    @endif
                                    @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="Working Hours" id="role_working_hours">
                                            <label class="cursor-pointer form-label fs-13" for="role_working_hours">
                                                {{ trans('labels.working_hours') }}
                                            </label>
                                        </div>
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="Table" id="role_table">
                                            <label class="cursor-pointer form-label fs-13" for="role_table">
                                                {{ trans('labels.table') }}
                                            </label>
                                        </div>
                                        @if (@helper::checkaddons('custom_status'))
                                            <div class="cursor-pointer d-block mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    name="Custom Status" id="role_custom_status">
                                                <label class="cursor-pointer form-label fs-13" for="role_custom_status">
                                                    {{ trans('labels.custom_status') }}
                                                </label>
                                            </div>
                                        @endif
                                        @if (@helper::checkaddons('subscription'))
                                            @if (@helper::checkaddons('custom_domain'))
                                                @php
                                                    if (Auth::user()->type == 2 || Auth::user()->type == 4) {
                                                        $checkplan = App\Models\Transaction::where(
                                                            'vendor_id',
                                                            $vendor_id,
                                                        )
                                                            ->orderByDesc('id')
                                                            ->first();
                                                    }
                                                    if ($user->allow_without_subscription == 1) {
                                                        $custom_domain = 1;
                                                    } else {
                                                        $custom_domain = @$checkplan->custom_domain;
                                                    }
                                                @endphp
                                                @if (@$custom_domain == 1)
                                                    <div class="cursor-pointer d-block mb-3">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            name="Custom Domains" id="role_custom_domains">
                                                        <label class="cursor-pointer form-label fs-13"
                                                            for="role_custom_domains">
                                                            {{ trans('labels.custom_domains') }}
                                                        </label>
                                                    </div>
                                                @endif
                                            @endif
                                        @else
                                            @if (@helper::checkaddons('custom_domain'))
                                                <div class="cursor-pointer d-block mb-3">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        name="Custom Domains" id="role_custom_domains">
                                                    <label class="cursor-pointer form-label fs-13"
                                                        for="role_custom_domains">
                                                        {{ trans('labels.custom_domains') }}
                                                    </label>
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value=""
                                            name="Basic Settings" id="role_basic_settings">
                                        <label class="cursor-pointer form-label fs-13" for="role_basic_settings">
                                            {{ trans('labels.basic_settings') }}
                                        </label>
                                    </div>
                                    @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="how_it_works" id="role_how_it_works">
                                            <label class="cursor-pointer form-label fs-13" for="role_how_it_works">
                                                {{ trans('labels.how_it_works') }}
                                            </label>
                                        </div>
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="theme_images" id="role_theme_images">
                                            <label class="cursor-pointer form-label fs-13" for="role_theme_images">
                                                {{ trans('labels.theme_images') }}
                                            </label>
                                        </div>
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="features" id="role_features">
                                            <label class="cursor-pointer form-label fs-13" for="role_features">
                                                {{ trans('labels.features') }}
                                            </label>
                                        </div>
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="promotional_banners" id="role_promotional_banners">
                                            <label class="cursor-pointer form-label fs-13" for="role_promotional_banners">
                                                {{ trans('labels.promotional_banners') }}
                                            </label>
                                        </div>
                                        @if (@helper::checkaddons('blog'))
                                            <div class="cursor-pointer d-block mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    name="Blogs" id="role_blogs">
                                                <label class="cursor-pointer form-label fs-13" for="role_blogs">
                                                    {{ trans('labels.blogs') }}
                                                </label>
                                            </div>
                                        @endif
                                    @endif
                                    @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="Basic Settings" id="role_who_we_are">
                                            <label class="cursor-pointer form-label fs-13" for="role_who_we_are">
                                                {{ trans('labels.who_we_are') }}
                                            </label>
                                        </div>
                                        @if (@helper::checkaddons('subscription'))
                                            @if (@helper::checkaddons('blog'))
                                                @php
                                                    $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)
                                                        ->orderByDesc('id')
                                                        ->first();
                                                    if ($user->allow_without_subscription == 1) {
                                                        $blogs = 1;
                                                    } else {
                                                        $blogs = @$checkplan->blogs;
                                                    }
                                                @endphp
                                                @if ($blogs == 1)
                                                    <div class="cursor-pointer d-block mb-3">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            name="Blogs" id="role_blogs">
                                                        <label class="cursor-pointer form-label fs-13" for="role_blogs">
                                                            {{ trans('labels.blogs') }}
                                                        </label>
                                                    </div>
                                                @endif
                                            @endif
                                        @else
                                            @if (@helper::checkaddons('blog'))
                                                <div class="cursor-pointer d-block mb-3">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        name="Blogs" id="role_blogs">
                                                    <label class="cursor-pointer form-label fs-13" for="role_blogs">
                                                        {{ trans('labels.blogs') }}
                                                    </label>
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value=""
                                            name="Testimonials" id="role_testimonials">
                                        <label class="cursor-pointer form-label fs-13" for="role_testimonials">
                                            {{ trans('labels.testimonials') }}
                                        </label>
                                    </div>
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value="" name="Faqs"
                                            id="role_faqs">
                                        <label class="cursor-pointer form-label fs-13" for="role_faqs">
                                            {{ trans('labels.faqs') }}
                                        </label>
                                    </div>
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value="" name="Cms Pages"
                                            id="role_cms_pages">
                                        <label class="cursor-pointer form-label fs-13" for="role_cms_pages">
                                            {{ trans('labels.cms_pages') }}
                                        </label>
                                    </div>
                                    @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                                        @if (@helper::checkaddons('coupon'))
                                            <div class="cursor-pointer d-block mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    name="Coupons" id="role_coupons">
                                                <label class="cursor-pointer form-label fs-13" for="role_coupons">
                                                    {{ trans('labels.coupons') }}
                                                </label>
                                            </div>
                                        @endif
                                        @if (@helper::checkaddons('employee'))
                                            <div class="cursor-pointer d-block mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    name="Roles" id="role_roles">
                                                <label class="cursor-pointer form-label fs-13" for="role_roles">
                                                    {{ trans('labels.roles') }}
                                                </label>
                                            </div>

                                            <div class="cursor-pointer d-block mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    name="Employees" id="role_employees">
                                                <label class="cursor-pointer form-label fs-13" for="role_employees">
                                                    {{ trans('labels.employees') }}
                                                </label>
                                            </div>
                                        @endif
                                    @endif
                                    @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                        @if (@helper::checkaddons('subscription'))
                                            @if (@helper::checkaddons('employee'))
                                                @php
                                                    $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)
                                                        ->orderByDesc('id')
                                                        ->first();
                                                    if ($user->allow_without_subscription == 1) {
                                                        $role_management = 1;
                                                    } else {
                                                        $role_management = @$checkplan->role_management;
                                                    }
                                                @endphp
                                                @if ($role_management == 1)
                                                    <div class="cursor-pointer d-block mb-3">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            name="Roles" id="role_roles">
                                                        <label class="cursor-pointer form-label fs-13" for="role_roles">
                                                            {{ trans('labels.roles') }}
                                                        </label>
                                                    </div>

                                                    <div class="cursor-pointer d-block mb-3">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            name="Employees" id="role_employees">
                                                        <label class="cursor-pointer form-label fs-13"
                                                            for="role_employees">
                                                            {{ trans('labels.employees') }}
                                                        </label>
                                                    </div>
                                                @endif
                                            @endif
                                        @else
                                            @if (@helper::checkaddons('employee'))
                                                <div class="cursor-pointer d-block mb-3">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        name="Roles" id="role_roles">
                                                    <label class="cursor-pointer form-label fs-13" for="role_roles">
                                                        {{ trans('labels.roles') }}
                                                    </label>
                                                </div>

                                                <div class="cursor-pointer d-block mb-3">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        name="Employees" id="role_employees">
                                                    <label class="cursor-pointer form-label fs-13" for="role_employees">
                                                        {{ trans('labels.employees') }}
                                                    </label>
                                                </div>
                                            @endif
                                        @endif
                                    @endif

                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value=""
                                            name="Subscribers" id="role_subscribers">
                                        <label class="cursor-pointer form-label fs-13" for="role_subscribers">
                                            {{ trans('labels.subscribers') }}
                                        </label>
                                    </div>
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value="" name="Inquiries"
                                            id="role_inquiries">
                                        <label class="cursor-pointer form-label fs-13" for="role_inquiries">
                                            {{ trans('labels.inquiries') }}
                                        </label>
                                    </div>
                                    @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                        @if (@helper::checkaddons('product_inquiry'))
                                            <div class="cursor-pointer d-block mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    name="product_inquiry" id="role_product_inquiry">
                                                <label class="cursor-pointer form-label fs-13" for="role_product_inquiry">
                                                    {{ trans('labels.product_inquiry') }}
                                                </label>
                                            </div>
                                        @endif

                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="Share" id="role_share">
                                            <label class="cursor-pointer form-label fs-13" for="role_share">
                                                {{ trans('labels.share') }}
                                            </label>
                                        </div>
                                        @if (@helper::checkaddons('subscription'))
                                            @if (@helper::checkaddons('whatsapp_message'))
                                                @php
                                                    $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)
                                                        ->orderByDesc('id')
                                                        ->first();
                                                    if ($user->allow_without_subscription == 1) {
                                                        $whatsapp_message = 1;
                                                    } else {
                                                        $whatsapp_message = @$checkplan->whatsapp_message;
                                                    }
                                                @endphp
                                                @if ($whatsapp_message == 1)
                                                    <div class="cursor-pointer d-block mb-3">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            name="whatsapp_settings" id="role_whatsapp_settings">
                                                        <label class="cursor-pointer form-label fs-13"
                                                            for="role_whatsapp_settings">
                                                            {{ trans('labels.whatsapp_settings') }}
                                                        </label>
                                                    </div>
                                                @endif
                                            @endif
                                        @else
                                            @if (@helper::checkaddons('whatsapp_message'))
                                                <div class="cursor-pointer d-block mb-3">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        name="whatsapp_settings" id="role_whatsapp_settings">
                                                    <label class="cursor-pointer form-label fs-13"
                                                        for="role_whatsapp_settings">
                                                        {{ trans('labels.whatsapp_settings') }}
                                                    </label>
                                                </div>
                                            @endif
                                        @endif
                                        @if (@helper::checkaddons('subscription'))
                                            @if (@helper::checkaddons('telegram_message'))
                                                @php
                                                    $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)
                                                        ->orderByDesc('id')
                                                        ->first();
                                                    if ($user->allow_without_subscription == 1) {
                                                        $telegram_message = 1;
                                                    } else {
                                                        $telegram_message = @$checkplan->telegram_message;
                                                    }
                                                @endphp
                                                @if ($telegram_message == 1)
                                                    <div class="cursor-pointer d-block mb-3">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            name="telegram_settings" id="role_telegram_settings">
                                                        <label class="cursor-pointer form-label fs-13"
                                                            for="role_telegram_settings">
                                                            {{ trans('labels.telegram_settings') }}
                                                        </label>
                                                    </div>
                                                @endif
                                            @endif
                                        @else
                                            @if (@helper::checkaddons('telegram_message'))
                                                <div class="cursor-pointer d-block mb-3">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        name="telegram_settings" id="role_telegram_settings">
                                                    <label class="cursor-pointer form-label fs-13"
                                                        for="role_telegram_settings">
                                                        {{ trans('labels.telegram_settings') }}
                                                    </label>
                                                </div>
                                            @endif
                                        @endif
                                        @if (@helper::checkaddons('language'))
                                            @if (helper::listoflanguage()->count() > 1)
                                                <div class="cursor-pointer d-block mb-3">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        name="Language Settings" id="role_language_settings">
                                                    <label class="cursor-pointer form-label fs-13"
                                                        for="role_language_settings">
                                                        {{ trans('labels.language-settings') }}
                                                    </label>
                                                </div>
                                            @endif
                                        @endif
                                        @if (@helper::checkaddons('currency_settigns'))
                                            @if (helper::available_currency()->count() > 1)
                                                <div class="cursor-pointer d-block mb-3">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        name="Currency Settings" id="role_currency_settings">
                                                    <label class="cursor-pointer form-label fs-13"
                                                        for="role_currency_settings">
                                                        {{ trans('labels.currency-settings') }}
                                                    </label>
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                    @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                                        @if (@helper::checkaddons('language'))
                                            <div class="cursor-pointer d-block mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    name="Language Settings" id="role_language_settings">
                                                <label class="cursor-pointer form-label fs-13"
                                                    for="role_language_settings">
                                                    {{ trans('labels.language-settings') }}
                                                </label>
                                            </div>
                                        @endif
                                        @if (@helper::checkaddons('currency_settigns'))
                                            <div class="cursor-pointer d-block mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    name="Currency Settings" id="role_currency_settings">
                                                <label class="cursor-pointer form-label fs-13"
                                                    for="role_currency_settings">
                                                    {{ trans('labels.currency-settings') }}
                                                </label>
                                            </div>
                                        @endif
                                    @endif
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value="" name="Settings"
                                            id="role_settings">
                                        <label class="cursor-pointer form-label fs-13" for="role_settings">
                                            {{ trans('labels.settings') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-8" id="permissioncheckbox">
                                    <div class="d-block mb-3">
                                        <div class="row">
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_dashboard"
                                                    name="modules[role_dashboard]"
                                                    {{ helper::check_access('role_dashboard', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                    id="manage[role_dashboard]">
                                                <label class="form-label fs-13" for="manage[role_dashboard]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_addons_manager" name="modules[role_addons_manager]"
                                                        {{ helper::check_access('role_addons_manager', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                        id="manage[role_addons_manager]">
                                                    <label class="form-label fs-13" for="manage[role_addons_manager]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_vendors"
                                                        name="modules[role_vendors]"
                                                        {{ helper::check_access('role_vendors', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                        id="manage[role_vendors]">
                                                    <label class="form-label fs-13" for="manage[role_vendors]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_vendors"
                                                        name="add[role_vendors]"
                                                        {{ helper::check_access('role_vendors', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                        id="add[role_vendors]">
                                                    <label class="form-label fs-13" for="add[role_vendors]">
                                                        {{ trans('labels.add') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_vendors"
                                                        name="edit[role_vendors]"
                                                        {{ helper::check_access('role_vendors', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                        id="edit[role_vendors]">
                                                    <label class="form-label fs-13" for="edit[role_vendors]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_vendors"
                                                        name="delete[role_vendors]"
                                                        {{ helper::check_access('role_vendors', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                        id="delete[role_vendors]">
                                                    <label class="form-label fs-13" for="delete[role_vendors]">
                                                        {{ trans('labels.delete') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                        @if (@helper::checkaddons('subscription'))
                                            @if (@helper::checkaddons('pos'))
                                                @php
                                                    $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)
                                                        ->orderByDesc('id')
                                                        ->first();
                                                    if ($user->allow_without_subscription == 1) {
                                                        $pos = 1;
                                                    } else {
                                                        $pos = @$checkplan->pos;
                                                    }
                                                @endphp
                                                @if ($pos == 1)
                                                    <div class="d-block mb-3">
                                                        <div class="row">
                                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="role_pos" name="modules[role_pos]"
                                                                    {{ helper::check_access('role_pos', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                                    id="manage[role_pos]">
                                                                <label class="form-label fs-13" for="manage[role_pos]">
                                                                    {{ trans('labels.view') }}
                                                                </label>
                                                            </div>
                                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="role_pos" name="add[role_pos]"
                                                                    {{ helper::check_access('role_pos', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                                    id="add[role_pos]">
                                                                <label class="form-label fs-13" for="add[role_pos]">
                                                                    {{ trans('labels.add') }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @else
                                            @if (@helper::checkaddons('pos'))
                                                <div class="d-block mb-3">
                                                    <div class="row">
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_pos" name="modules[role_pos]"
                                                                {{ helper::check_access('role_pos', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                                id="manage[role_pos]">
                                                            <label class="form-label fs-13" for="manage[role_pos]">
                                                                {{ trans('labels.view') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_pos" name="add[role_pos]"
                                                                {{ helper::check_access('role_pos', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                                id="add[role_pos]">
                                                            <label class="form-label fs-13" for="add[role_pos]">
                                                                {{ trans('labels.add') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_orders"
                                                        name="modules[role_orders]"
                                                        {{ helper::check_access('role_orders', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                        id="manage[role_orders]">
                                                    <label class="form-label fs-13" for="manage[role_orders]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_report"
                                                        name="modules[role_report]"
                                                        {{ helper::check_access('role_report', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                        id="manage[role_report]">
                                                    <label class="form-label fs-13" for="manage[role_report]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if (@helper::checkaddons('customer_login'))
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_customers" name="modules[role_customers]"
                                                        {{ helper::check_access('role_customers', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                        id="manage[role_customers]">
                                                    <label class="form-label fs-13" for="manage[role_customers]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_customers" name="add[role_customers]"
                                                            {{ helper::check_access('role_customers', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                            id="add[role_customers]">
                                                        <label class="form-label fs-13" for="add[role_customers]">
                                                            {{ trans('labels.add') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_customers" name="edit[role_customers]"
                                                            {{ helper::check_access('role_customers', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                            id="edit[role_customers]">
                                                        <label class="form-label fs-13" for="edit[role_customers]">
                                                            {{ trans('labels.edit') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_customers" name="delete[role_customers]"
                                                            {{ helper::check_access('role_customers', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                            id="delete[role_customers]">
                                                        <label class="form-label fs-13" for="delete[role_customers]">
                                                            {{ trans('labels.delete') }}
                                                        </label>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_categories" name="modules[role_categories]"
                                                        {{ helper::check_access('role_categories', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                        id="manage[role_categories]">
                                                    <label class="form-label fs-13" for="manage[role_categories]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_categories" name="add[role_categories]"
                                                        {{ helper::check_access('role_categories', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                        id="add[role_categories]">
                                                    <label class="form-label fs-13" for="add[role_categories]">
                                                        {{ trans('labels.add') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_categories" name="edit[role_categories]"
                                                        {{ helper::check_access('role_categories', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                        id="edit[role_categories]">
                                                    <label class="form-label fs-13" for="edit[role_categories]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_categories" name="delete[role_categories]"
                                                        {{ helper::check_access('role_categories', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                        id="delete[role_categories]">
                                                    <label class="form-label fs-13" for="delete[role_categories]">
                                                        {{ trans('labels.delete') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="d-block mb-3">
                                        <div class="row">
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_tax"
                                                    name="modules[role_tax]"
                                                    {{ helper::check_access('role_tax', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                    id="manage[role_tax]">
                                                <label class="form-label fs-13" for="manage[role_tax]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_tax"
                                                    name="add[role_tax]"
                                                    {{ helper::check_access('role_tax', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                    id="add[role_tax]">
                                                <label class="form-label fs-13" for="add[role_tax]">
                                                    {{ trans('labels.add') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_tax"
                                                    name="edit[role_tax]"
                                                    {{ helper::check_access('role_tax', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                    id="edit[role_tax]">
                                                <label class="form-label fs-13" for="edit[role_tax]">
                                                    {{ trans('labels.edit') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_tax"
                                                    name="delete[role_tax]"
                                                    {{ helper::check_access('role_tax', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                    id="delete[role_tax]">
                                                <label class="form-label fs-13" for="delete[role_tax]">
                                                    {{ trans('labels.delete') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_global_extras" name="modules[role_global_extras]"
                                                        {{ helper::check_access('role_global_extras', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                        id="manage[role_global_extras]">
                                                    <label class="form-label fs-13" for="manage[role_global_extras]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_global_extras" name="add[role_global_extras]"
                                                        {{ helper::check_access('role_global_extras', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                        id="add[role_global_extras]">
                                                    <label class="form-label fs-13" for="add[role_global_extras]">
                                                        {{ trans('labels.add') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_global_extras" name="edit[role_global_extras]"
                                                        {{ helper::check_access('role_global_extras', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                        id="edit[role_global_extras]">
                                                    <label class="form-label fs-13" for="edit[role_global_extras]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_global_extras" name="delete[role_global_extras]"
                                                        {{ helper::check_access('role_global_extras', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                        id="delete[role_global_extras]">
                                                    <label class="form-label fs-13" for="delete[role_global_extras]">
                                                        {{ trans('labels.delete') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_products"
                                                        name="modules[role_products]"
                                                        {{ helper::check_access('role_products', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                        id="manage[role_products]">
                                                    <label class="form-label fs-13" for="manage[role_products]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_products"
                                                        name="add[role_products]"
                                                        {{ helper::check_access('role_products', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                        id="add[role_products]">
                                                    <label class="form-label fs-13" for="add[role_products]">
                                                        {{ trans('labels.add') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_products"
                                                        name="edit[role_products]"
                                                        {{ helper::check_access('role_products', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                        id="edit[role_products]">
                                                    <label class="form-label fs-13" for="edit[role_products]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_products"
                                                        name="delete[role_products]"
                                                        {{ helper::check_access('role_products', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                        id="delete[role_products]">
                                                    <label class="form-label fs-13" for="delete[role_products]">
                                                        {{ trans('labels.delete') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        @if (@helper::checkaddons('product_import'))
                                            <div class="d-block mb-3">
                                                <div class="row">
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_import_product"
                                                            name="modules[role_import_product]"
                                                            {{ helper::check_access('role_import_product', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                            id="manage[role_import_product]">
                                                        <label class="form-label fs-13" for="manage[role_import_product]">
                                                            {{ trans('labels.view') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_import_product" name="add[role_import_product]"
                                                            {{ helper::check_access('role_import_product', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                            id="add[role_import_product]">
                                                        <label class="form-label fs-13" for="add[role_import_product]">
                                                            {{ trans('labels.add') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_shipping_management"
                                                        name="modules[role_shipping_management]"
                                                        {{ helper::check_access('role_shipping_management', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                        id="manage[role_shipping_management]">
                                                    <label class="form-label fs-13"
                                                        for="manage[role_shipping_management]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                @if (@helper::checkaddons('shipping_area'))
                                                    @if (helper::appdata($vendor_id)->shipping_area == 1)
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_shipping_management"
                                                                name="add[role_shipping_management]"
                                                                {{ helper::check_access('role_shipping_management', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                                id="add[role_shipping_management]">
                                                            <label class="form-label fs-13"
                                                                for="add[role_shipping_management]">
                                                                {{ trans('labels.add') }}
                                                            </label>
                                                        </div>
                                                    @endif
                                                @endif
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_shipping_management"
                                                        name="edit[role_shipping_management]"
                                                        {{ helper::check_access('role_shipping_management', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                        id="edit[role_shipping_management]">
                                                    <label class="form-label fs-13" for="edit[role_shipping_management]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                                @if (@helper::checkaddons('shipping_area'))
                                                    @if (helper::appdata($vendor_id)->shipping_area == 1)
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_shipping_management"
                                                                name="delete[role_shipping_management]"
                                                                {{ helper::check_access('role_shipping_management', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                                id="delete[role_shipping_management]">
                                                            <label class="form-label fs-13"
                                                                for="delete[role_shipping_management]">
                                                                {{ trans('labels.delete') }}
                                                            </label>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_product_question_answer"
                                                        name="modules[role_product_question_answer]"
                                                        {{ helper::check_access('role_product_question_answer', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                        id="manage[role_product_question_answer]">
                                                    <label class="form-label fs-13"
                                                        for="manage[role_product_question_answer]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>

                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_product_question_answer"
                                                        name="edit[role_product_question_answer]"
                                                        {{ helper::check_access('role_product_question_answer', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                        id="edit[role_product_question_answer]">
                                                    <label class="form-label fs-13"
                                                        for="edit[role_product_question_answer]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                                @if (@helper::checkaddons('shipping_area'))
                                                    @if (helper::appdata($vendor_id)->shipping_area == 1)
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_product_question_answer"
                                                                name="delete[role_product_question_answer]"
                                                                {{ helper::check_access('role_product_question_answer', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                                id="delete[role_product_question_answer]">
                                                            <label class="form-label fs-13"
                                                                for="delete[role_product_question_answer]">
                                                                {{ trans('labels.delete') }}
                                                            </label>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                        @if (@helper::checkaddons('shopify'))
                                            <div class="d-block mb-3">
                                                <div class="row">
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_shopify" name="modules[role_shopify]"
                                                            {{ helper::check_access('role_shopify', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                            id="manage[role_shopify]">
                                                        <label class="form-label fs-13" for="manage[role_shopify]">
                                                            {{ trans('labels.view') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_shopify" name="add[role_shopify]"
                                                            {{ helper::check_access('role_shopify', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                            id="add[role_shopify]">
                                                        <label class="form-label fs-13" for="add[role_shopify]">
                                                            {{ trans('labels.add') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_sliders"
                                                        name="modules[role_sliders]"
                                                        {{ helper::check_access('role_sliders', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                        id="manage[role_sliders]">
                                                    <label class="form-label fs-13" for="manage[role_sliders]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_sliders"
                                                        name="add[role_sliders]"
                                                        {{ helper::check_access('role_sliders', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                        id="add[role_sliders]">
                                                    <label class="form-label fs-13" for="add[role_sliders]">
                                                        {{ trans('labels.add') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_sliders"
                                                        name="edit[role_sliders]"
                                                        {{ helper::check_access('role_sliders', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                        id="edit[role_sliders]">
                                                    <label class="form-label fs-13" for="edit[role_sliders]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_sliders"
                                                        name="delete[role_sliders]"
                                                        {{ helper::check_access('role_sliders', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                        id="delete[role_sliders]">
                                                    <label class="form-label fs-13" for="delete[role_sliders]">
                                                        {{ trans('labels.delete') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_banner"
                                                        name="modules[role_banner]"
                                                        {{ helper::check_access('role_banner', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                        id="manage[role_banner]">
                                                    <label class="form-label fs-13" for="manage[role_banner]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_banner" name="add[role_banner]"
                                                        {{ helper::check_access('role_banner', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                        id="add[role_banner]">
                                                    <label class="form-label fs-13" for="add[role_banner]">
                                                        {{ trans('labels.add') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_banner" name="edit[role_banner]"
                                                        {{ helper::check_access('role_banner', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                        id="edit[role_banner]">
                                                    <label class="form-label fs-13" for="edit[role_banner]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_banner" name="delete[role_banner]"
                                                        {{ helper::check_access('role_banner', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                        id="delete[role_banner]">
                                                    <label class="form-label fs-13" for="delete[role_banner]">
                                                        {{ trans('labels.delete') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        @if (@helper::checkaddons('subscription'))
                                            @if (@helper::checkaddons('coupon'))
                                                @php
                                                    $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)
                                                        ->orderByDesc('id')
                                                        ->first();
                                                    if ($user->allow_without_subscription == 1) {
                                                        $coupons = 1;
                                                    } else {
                                                        $coupons = @$checkplan->coupons;
                                                    }
                                                @endphp
                                                @if ($coupons == 1)
                                                    <div class="d-block mb-3">
                                                        <div class="row">
                                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="role_coupons" name="modules[role_coupons]"
                                                                    {{ helper::check_access('role_coupons', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                                    id="manage[role_coupons]">
                                                                <label class="form-label fs-13"
                                                                    for="manage[role_coupons]">
                                                                    {{ trans('labels.view') }}
                                                                </label>
                                                            </div>
                                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="role_coupons" name="add[role_coupons]"
                                                                    {{ helper::check_access('role_coupons', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                                    id="add[role_coupons]">
                                                                <label class="form-label fs-13" for="add[role_coupons]">
                                                                    {{ trans('labels.add') }}
                                                                </label>
                                                            </div>
                                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="role_coupons" name="edit[role_coupons]"
                                                                    {{ helper::check_access('role_coupons', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                                    id="edit[role_coupons]">
                                                                <label class="form-label fs-13"
                                                                    for="edit[role_coupons]">
                                                                    {{ trans('labels.edit') }}
                                                                </label>
                                                            </div>
                                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="role_coupons" name="delete[role_coupons]"
                                                                    {{ helper::check_access('role_coupons', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                                    id="delete[role_coupons]">
                                                                <label class="form-label fs-13"
                                                                    for="delete[role_coupons]">
                                                                    {{ trans('labels.delete') }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @else
                                            @if (@helper::checkaddons('coupon'))
                                                <div class="d-block mb-3">
                                                    <div class="row">
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_coupons" name="modules[role_coupons]"
                                                                {{ helper::check_access('role_coupons', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                                id="manage[role_coupons]">
                                                            <label class="form-label fs-13" for="manage[role_coupons]">
                                                                {{ trans('labels.view') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_coupons" name="add[role_coupons]"
                                                                {{ helper::check_access('role_coupons', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                                id="add[role_coupons]">
                                                            <label class="form-label fs-13" for="add[role_coupons]">
                                                                {{ trans('labels.add') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_coupons" name="edit[role_coupons]"
                                                                {{ helper::check_access('role_coupons', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                                id="edit[role_coupons]">
                                                            <label class="form-label fs-13" for="edit[role_coupons]">
                                                                {{ trans('labels.edit') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_coupons" name="delete[role_coupons]"
                                                                {{ helper::check_access('role_coupons', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                                id="delete[role_coupons]">
                                                            <label class="form-label fs-13" for="delete[role_coupons]">
                                                                {{ trans('labels.delete') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                        @if (@helper::checkaddons('top_deals'))
                                            <div class="d-block mb-3">
                                                <div class="row">
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_top_deals" name="modules[role_top_deals]"
                                                            {{ helper::check_access('role_top_deals', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                            id="manage[role_top_deals]">
                                                        <label class="form-label fs-13" for="manage[role_top_deals]">
                                                            {{ trans('labels.view') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-4">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_top_deals" name="add[role_top_deals]"
                                                            {{ helper::check_access('role_top_deals', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                            id="add[role_top_deals]">
                                                        <label class="form-label fs-13" for="add[role_top_deals]">
                                                            {{ trans('labels.add') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_top_deals" name="delete[role_top_deals]"
                                                            {{ helper::check_access('role_top_deals', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                            id="delete[role_top_deals]">
                                                        <label class="form-label fs-13" for="delete[role_top_deals]">
                                                            {{ trans('labels.delete') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if (@helper::checkaddons('firebase_notification'))
                                            <div class="d-block mb-3">
                                                <div class="row">
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_firebase_notification"
                                                            name="modules[role_firebase_notification]"
                                                            {{ helper::check_access('role_firebase_notification', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                            id="manage[role_firebase_notification]">
                                                        <label class="form-label fs-13"
                                                            for="manage[role_firebase_notification]">
                                                            {{ trans('labels.view') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_firebase_notification"
                                                            name="add[role_firebase_notification]"
                                                            {{ helper::check_access('role_firebase_notification', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                            id="add[role_firebase_notification]">
                                                        <label class="form-label fs-13"
                                                            for="add[role_firebase_notification]">
                                                            {{ trans('labels.add') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_firebase_notification"
                                                            name="edit[role_firebase_notification]"
                                                            {{ helper::check_access('role_firebase_notification', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                            id="edit[role_firebase_notification]">
                                                        <label class="form-label fs-13"
                                                            for="edit[role_firebase_notification]">
                                                            {{ trans('labels.edit') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_firebase_notification"
                                                            name="delete[role_firebase_notification]"
                                                            {{ helper::check_access('role_firebase_notification', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                            id="delete[role_firebase_notification]">
                                                        <label class="form-label fs-13"
                                                            for="delete[role_firebase_notification]">
                                                            {{ trans('labels.delete') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                    @if (@helper::checkaddons('subscription'))
                                        @if ($user->allow_without_subscription != 1)
                                            <div class="d-block mb-3">
                                                <div class="row">
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_pricing_plans"
                                                            name="modules[role_pricing_plans]"
                                                            {{ helper::check_access('role_pricing_plans', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                            id="manage[role_pricing_plans]">
                                                        <label class="form-label fs-13"
                                                            for="manage[role_pricing_plans]">
                                                            {{ trans('labels.view') }}
                                                        </label>
                                                    </div>
                                                    @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_pricing_plans"
                                                                name="add[role_pricing_plans]"
                                                                {{ helper::check_access('role_pricing_plans', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                                id="add[role_pricing_plans]">
                                                            <label class="form-label fs-13"
                                                                for="add[role_pricing_plans]">
                                                                {{ trans('labels.add') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_pricing_plans"
                                                                name="edit[role_pricing_plans]"
                                                                {{ helper::check_access('role_pricing_plans', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                                id="edit[role_pricing_plans]">
                                                            <label class="form-label fs-13"
                                                                for="edit[role_pricing_plans]">
                                                                {{ trans('labels.edit') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_pricing_plans"
                                                                name="delete[role_pricing_plans]"
                                                                {{ helper::check_access('role_pricing_plans', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                                id="delete[role_pricing_plans]">
                                                            <label class="form-label fs-13"
                                                                for="delete[role_pricing_plans]">
                                                                {{ trans('labels.delete') }}
                                                            </label>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_transaction" name="modules[role_transaction]"
                                                        {{ helper::check_access('role_transaction', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                        id="manage[role_transaction]">
                                                    <label class="form-label fs-13" for="manage[role_transaction]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_payment_methods"
                                                        name="modules[role_payment_methods]"
                                                        {{ helper::check_access('role_payment_methods', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                        id="manage[role_payment_methods]">
                                                    <label class="form-label fs-13" for="manage[role_payment_methods]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_payment_methods" name="add[role_payment_methods]"
                                                        {{ helper::check_access('role_payment_methods', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                        id="add[role_payment_methods]">
                                                    <label class="form-label fs-13" for="add[role_payment_methods]">
                                                        {{ trans('labels.add') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                            <div class="d-block mb-3">
                                                <div class="row">
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_payment_methods"
                                                            name="modules[role_payment_methods]"
                                                            {{ helper::check_access('role_payment_methods', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                            id="manage[role_payment_methods]">
                                                        <label class="form-label fs-13"
                                                            for="manage[role_payment_methods]">
                                                            {{ trans('labels.view') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_payment_methods"
                                                            name="add[role_payment_methods]"
                                                            {{ helper::check_access('role_payment_methods', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                            id="add[role_payment_methods]">
                                                        <label class="form-label fs-13" for="add[role_payment_methods]">
                                                            {{ trans('labels.add') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                    @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_cities" name="modules[role_cities]"
                                                        {{ helper::check_access('role_cities', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                        id="manage[role_cities]">
                                                    <label class="form-label fs-13" for="manage[role_cities]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_cities" name="add[role_cities]"
                                                        {{ helper::check_access('role_cities', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                        id="add[role_cities]">
                                                    <label class="form-label fs-13" for="add[role_cities]">
                                                        {{ trans('labels.add') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_cities" name="edit[role_cities]"
                                                        {{ helper::check_access('role_cities', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                        id="edit[role_cities]">
                                                    <label class="form-label fs-13" for="edit[role_cities]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_cities" name="delete[role_cities]"
                                                        {{ helper::check_access('role_cities', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                        id="delete[role_cities]">
                                                    <label class="form-label fs-13" for="delete[role_cities]">
                                                        {{ trans('labels.delete') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_areas"
                                                        name="modules[role_areas]"
                                                        {{ helper::check_access('role_areas', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                        id="manage[role_areas]">
                                                    <label class="form-label fs-13" for="manage[role_areas]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_areas"
                                                        name="add[role_areas]"
                                                        {{ helper::check_access('role_areas', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                        id="add[role_areas]">
                                                    <label class="form-label fs-13" for="add[role_areas]">
                                                        {{ trans('labels.add') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_areas"
                                                        name="edit[role_areas]"
                                                        {{ helper::check_access('role_areas', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                        id="edit[role_areas]">
                                                    <label class="form-label fs-13" for="edit[role_areas]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_areas"
                                                        name="delete[role_areas]"
                                                        {{ helper::check_access('role_areas', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                        id="delete[role_areas]">
                                                    <label class="form-label fs-13" for="delete[role_areas]">
                                                        {{ trans('labels.delete') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_store_categories"
                                                        name="modules[role_store_categories]"
                                                        {{ helper::check_access('role_store_categories', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                        id="manage[role_store_categories]">
                                                    <label class="form-label fs-13" for="manage[role_store_categories]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_store_categories" name="add[role_store_categories]"
                                                        {{ helper::check_access('role_store_categories', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                        id="add[role_store_categories]">
                                                    <label class="form-label fs-13" for="add[role_store_categories]">
                                                        {{ trans('labels.add') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_store_categories" name="edit[role_store_categories]"
                                                        {{ helper::check_access('role_store_categories', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                        id="edit[role_store_categories]">
                                                    <label class="form-label fs-13" for="edit[role_store_categories]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_store_categories"
                                                        name="delete[role_store_categories]"
                                                        {{ helper::check_access('role_store_categories', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                        id="delete[role_store_categories]">
                                                    <label class="form-label fs-13" for="delete[role_store_categories]">
                                                        {{ trans('labels.delete') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        @if (@helper::checkaddons('custom_domain'))
                                            <div class="d-block mb-3">
                                                <div class="row">
                                                    <div class="col-4">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_custom_domains"
                                                            name="modules[role_custom_domains]"
                                                            {{ helper::check_access('role_custom_domains', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                            id="manage[role_custom_domains]">
                                                        <label class="form-label fs-13"
                                                            for="manage[role_custom_domains]">
                                                            {{ trans('labels.view') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_custom_domains" name="edit[role_custom_domains]"
                                                            {{ helper::check_access('role_custom_domains', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                            id="edit[role_custom_domains]">
                                                        <label class="form-label fs-13" for="edit[role_custom_domains]">
                                                            {{ trans('labels.edit') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                    @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-4">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_working_hours" name="modules[role_working_hours]"
                                                        {{ helper::check_access('role_working_hours', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                        id="manage[role_working_hours]">
                                                    <label class="form-label fs-13" for="manage[role_working_hours]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_working_hours" name="edit[role_working_hours]"
                                                        {{ helper::check_access('role_working_hours', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                        id="edit[role_working_hours]">
                                                    <label class="form-label fs-13" for="edit[role_working_hours]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_table"
                                                        name="modules[role_table]"
                                                        {{ helper::check_access('role_table', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                        id="manage[role_table]">
                                                    <label class="form-label fs-13" for="manage[role_table]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_table"
                                                        name="add[role_table]"
                                                        {{ helper::check_access('role_table', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                        id="add[role_table]">
                                                    <label class="form-label fs-13" for="add[role_table]">
                                                        {{ trans('labels.add') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_table"
                                                        name="edit[role_table]"
                                                        {{ helper::check_access('role_table', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                        id="edit[role_table]">
                                                    <label class="form-label fs-13" for="edit[role_table]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_table"
                                                        name="delete[role_table]"
                                                        {{ helper::check_access('role_table', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                        id="delete[role_table]">
                                                    <label class="form-label fs-13" for="delete[role_table]">
                                                        {{ trans('labels.delete') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        @if (@helper::checkaddons('custom_status'))
                                            <div class="d-block mb-3">
                                                <div class="row">
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_custom_status"
                                                            name="modules[role_custom_status]"
                                                            {{ helper::check_access('role_custom_status', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                            id="manage[role_custom_status]">
                                                        <label class="form-label fs-13"
                                                            for="manage[role_custom_status]">
                                                            {{ trans('labels.view') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_custom_status" name="add[role_custom_status]"
                                                            {{ helper::check_access('role_custom_status', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                            id="add[role_custom_status]">
                                                        <label class="form-label fs-13" for="add[role_custom_status]">
                                                            {{ trans('labels.add') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_custom_status" name="edit[role_custom_status]"
                                                            {{ helper::check_access('role_custom_status', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                            id="edit[role_custom_status]">
                                                        <label class="form-label fs-13" for="edit[role_custom_status]">
                                                            {{ trans('labels.edit') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_custom_status" name="delete[role_custom_status]"
                                                            {{ helper::check_access('role_custom_status', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                            id="delete[role_custom_status]">
                                                        <label class="form-label fs-13"
                                                            for="delete[role_custom_status]">
                                                            {{ trans('labels.delete') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if (@helper::checkaddons('subscription'))
                                            @if (@helper::checkaddons('custom_domain'))
                                                @php
                                                    if (Auth::user()->type == 2 || Auth::user()->type == 4) {
                                                        $checkplan = App\Models\Transaction::where(
                                                            'vendor_id',
                                                            $vendor_id,
                                                        )
                                                            ->orderByDesc('id')
                                                            ->first();
                                                    }
                                                    if ($user->allow_without_subscription == 1) {
                                                        $custom_domain = 1;
                                                    } else {
                                                        $custom_domain = @$checkplan->custom_domain;
                                                    }
                                                @endphp
                                                @if ($custom_domain == 1)
                                                    <div class="d-block mb-3">
                                                        <div class="row">
                                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="role_custom_domains"
                                                                    name="modules[role_custom_domains]"
                                                                    {{ helper::check_access('role_custom_domains', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                                    id="manage[role_custom_domains]">
                                                                <label class="form-label fs-13"
                                                                    for="manage[role_custom_domains]">
                                                                    {{ trans('labels.view') }}
                                                                </label>
                                                            </div>
                                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="role_custom_domains"
                                                                    name="add[role_custom_domains]"
                                                                    {{ helper::check_access('role_custom_domains', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                                    id="add[role_custom_domains]">
                                                                <label class="form-label fs-13"
                                                                    for="add[role_custom_domains]">
                                                                    {{ trans('labels.add') }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @else
                                            @if (@helper::checkaddons('custom_domain'))
                                                <div class="d-block mb-3">
                                                    <div class="row">
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_custom_domains"
                                                                name="modules[role_custom_domains]"
                                                                {{ helper::check_access('role_custom_domains', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                                id="manage[role_custom_domains]">
                                                            <label class="form-label fs-13"
                                                                for="manage[role_custom_domains]">
                                                                {{ trans('labels.view') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_custom_domains"
                                                                name="add[role_custom_domains]"
                                                                {{ helper::check_access('role_custom_domains', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                                id="add[role_custom_domains]">
                                                            <label class="form-label fs-13"
                                                                for="add[role_custom_domains]">
                                                                {{ trans('labels.add') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                    <div class="d-block mb-3">
                                        <div class="row">
                                            <div class="col-4">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_basic_settings" name="modules[role_basic_settings]"
                                                    {{ helper::check_access('role_basic_settings', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                    id="manage[role_basic_settings]">
                                                <label class="form-label fs-13" for="manage[role_basic_settings]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_basic_settings" name="edit[role_basic_settings]"
                                                    {{ helper::check_access('role_basic_settings', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                    id="edit[role_basic_settings]">
                                                <label class="form-label fs-13" for="edit[role_basic_settings]">
                                                    {{ trans('labels.edit') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_how_it_works" name="modules[role_how_it_works]"
                                                        {{ helper::check_access('role_how_it_works', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                        id="manage[role_how_it_works]">
                                                    <label class="form-label fs-13" for="manage[role_how_it_works]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_how_it_works" name="add[role_how_it_works]"
                                                        {{ helper::check_access('role_how_it_works', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                        id="add[role_how_it_works]">
                                                    <label class="form-label fs-13" for="add[role_how_it_works]">
                                                        {{ trans('labels.add') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_how_it_works" name="edit[role_how_it_works]"
                                                        {{ helper::check_access('role_how_it_works', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                        id="edit[role_how_it_works]">
                                                    <label class="form-label fs-13" for="edit[role_how_it_works]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_how_it_works" name="delete[role_how_it_works]"
                                                        {{ helper::check_access('role_how_it_works', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                        id="delete[role_how_it_works]">
                                                    <label class="form-label fs-13" for="delete[role_how_it_works]">
                                                        {{ trans('labels.delete') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_theme_images" name="modules[role_theme_images]"
                                                        {{ helper::check_access('role_theme_images', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                        id="manage[role_theme_images]">
                                                    <label class="form-label fs-13" for="manage[role_theme_images]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_theme_images" name="add[role_theme_images]"
                                                        {{ helper::check_access('role_theme_images', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                        id="add[role_theme_images]">
                                                    <label class="form-label fs-13" for="add[role_theme_images]">
                                                        {{ trans('labels.add') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_theme_images" name="edit[role_theme_images]"
                                                        {{ helper::check_access('role_theme_images', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                        id="edit[role_theme_images]">
                                                    <label class="form-label fs-13" for="edit[role_theme_images]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_theme_images" name="delete[role_theme_images]"
                                                        {{ helper::check_access('role_theme_images', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                        id="delete[role_theme_images]">
                                                    <label class="form-label fs-13" for="delete[role_theme_images]">
                                                        {{ trans('labels.delete') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_features" name="modules[role_features]"
                                                        {{ helper::check_access('role_features', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                        id="manage[role_features]">
                                                    <label class="form-label fs-13" for="manage[role_features]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_features" name="add[role_features]"
                                                        {{ helper::check_access('role_features', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                        id="add[role_features]">
                                                    <label class="form-label fs-13" for="add[role_features]">
                                                        {{ trans('labels.add') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_features" name="edit[role_features]"
                                                        {{ helper::check_access('role_features', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                        id="edit[role_features]">
                                                    <label class="form-label fs-13" for="edit[role_features]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_features" name="delete[role_features]"
                                                        {{ helper::check_access('role_features', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                        id="delete[role_features]">
                                                    <label class="form-label fs-13" for="delete[role_features]">
                                                        {{ trans('labels.delete') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_promotional_banners"
                                                        name="modules[role_promotional_banners]"
                                                        {{ helper::check_access('role_promotional_banners', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                        id="manage[role_promotional_banners]">
                                                    <label class="form-label fs-13"
                                                        for="manage[role_promotional_banners]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_promotional_banners"
                                                        name="add[role_promotional_banners]"
                                                        {{ helper::check_access('role_promotional_banners', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                        id="add[role_promotional_banners]">
                                                    <label class="form-label fs-13" for="add[role_promotional_banners]">
                                                        {{ trans('labels.add') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_promotional_banners"
                                                        name="edit[role_promotional_banners]"
                                                        {{ helper::check_access('role_promotional_banners', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                        id="edit[role_promotional_banners]">
                                                    <label class="form-label fs-13"
                                                        for="edit[role_promotional_banners]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_promotional_banners"
                                                        name="delete[role_promotional_banners]"
                                                        {{ helper::check_access('role_promotional_banners', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                        id="delete[role_promotional_banners]">
                                                    <label class="form-label fs-13"
                                                        for="delete[role_promotional_banners]">
                                                        {{ trans('labels.delete') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        @if (@helper::checkaddons('blog'))
                                            <div class="d-block mb-3">
                                                <div class="row">
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_blogs" name="modules[role_blogs]"
                                                            {{ helper::check_access('role_blogs', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                            id="manage[role_blogs]">
                                                        <label class="form-label fs-13" for="manage[role_blogs]">
                                                            {{ trans('labels.view') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_blogs" name="add[role_blogs]"
                                                            {{ helper::check_access('role_blogs', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                            id="add[role_blogs]">
                                                        <label class="form-label fs-13" for="add[role_blogs]">
                                                            {{ trans('labels.add') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_blogs" name="edit[role_blogs]"
                                                            {{ helper::check_access('role_blogs', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                            id="edit[role_blogs]">
                                                        <label class="form-label fs-13" for="edit[role_blogs]">
                                                            {{ trans('labels.edit') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_blogs" name="delete[role_blogs]"
                                                            {{ helper::check_access('role_blogs', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                            id="delete[role_blogs]">
                                                        <label class="form-label fs-13" for="delete[role_blogs]">
                                                            {{ trans('labels.delete') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                    @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_who_we_are" name="modules[role_who_we_are]"
                                                        {{ helper::check_access('role_who_we_are', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                        id="manage[role_who_we_are]">
                                                    <label class="form-label fs-13" for="manage[role_who_we_are]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_who_we_are" name="add[role_who_we_are]"
                                                        {{ helper::check_access('role_who_we_are', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                        id="add[role_who_we_are]">
                                                    <label class="form-label fs-13" for="add[role_who_we_are]">
                                                        {{ trans('labels.add') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_who_we_are" name="edit[role_who_we_are]"
                                                        {{ helper::check_access('role_who_we_are', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                        id="edit[role_who_we_are]">
                                                    <label class="form-label fs-13" for="edit[role_who_we_are]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_who_we_are" name="delete[role_who_we_are]"
                                                        {{ helper::check_access('role_who_we_are', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                        id="delete[role_who_we_are]">
                                                    <label class="form-label fs-13" for="delete[role_who_we_are]">
                                                        {{ trans('labels.delete') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        @if (@helper::checkaddons('subscription'))
                                            @if (@helper::checkaddons('blog'))
                                                @php
                                                    $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)
                                                        ->orderByDesc('id')
                                                        ->first();
                                                    if ($user->allow_without_subscription == 1) {
                                                        $blogs = 1;
                                                    } else {
                                                        $blogs = @$checkplan->blogs;
                                                    }
                                                @endphp
                                                @if ($blogs == 1)
                                                    <div class="d-block mb-3">
                                                        <div class="row">
                                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="role_blogs" name="modules[role_blogs]"
                                                                    {{ helper::check_access('role_blogs', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                                    id="manage[role_blogs]">
                                                                <label class="form-label fs-13"
                                                                    for="manage[role_blogs]">
                                                                    {{ trans('labels.view') }}
                                                                </label>
                                                            </div>
                                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="role_blogs" name="add[role_blogs]"
                                                                    {{ helper::check_access('role_blogs', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                                    id="add[role_blogs]">
                                                                <label class="form-label fs-13" for="add[role_blogs]">
                                                                    {{ trans('labels.add') }}
                                                                </label>
                                                            </div>
                                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="role_blogs" name="edit[role_blogs]"
                                                                    {{ helper::check_access('role_blogs', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                                    id="edit[role_blogs]">
                                                                <label class="form-label fs-13" for="edit[role_blogs]">
                                                                    {{ trans('labels.edit') }}
                                                                </label>
                                                            </div>
                                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="role_blogs" name="delete[role_blogs]"
                                                                    {{ helper::check_access('role_blogs', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                                    id="delete[role_blogs]">
                                                                <label class="form-label fs-13"
                                                                    for="delete[role_blogs]">
                                                                    {{ trans('labels.delete') }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @else
                                            @if (@helper::checkaddons('blog'))
                                                <div class="d-block mb-3">
                                                    <div class="row">
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_blogs" name="modules[role_blogs]"
                                                                {{ helper::check_access('role_blogs', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                                id="manage[role_blogs]">
                                                            <label class="form-label fs-13" for="manage[role_blogs]">
                                                                {{ trans('labels.view') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_blogs" name="add[role_blogs]"
                                                                {{ helper::check_access('role_blogs', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                                id="add[role_blogs]">
                                                            <label class="form-label fs-13" for="add[role_blogs]">
                                                                {{ trans('labels.add') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_blogs" name="edit[role_blogs]"
                                                                {{ helper::check_access('role_blogs', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                                id="edit[role_blogs]">
                                                            <label class="form-label fs-13" for="edit[role_blogs]">
                                                                {{ trans('labels.edit') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_blogs" name="delete[role_blogs]"
                                                                {{ helper::check_access('role_blogs', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                                id="delete[role_blogs]">
                                                            <label class="form-label fs-13" for="delete[role_blogs]">
                                                                {{ trans('labels.delete') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                    <div class="d-block mb-3">
                                        <div class="row">
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_testimonials" name="modules[role_testimonials]"
                                                    {{ helper::check_access('role_testimonials', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                    id="manage[role_testimonials]">
                                                <label class="form-label fs-13" for="manage[role_testimonials]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_testimonials" name="add[role_testimonials]"
                                                    {{ helper::check_access('role_testimonials', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                    id="add[role_testimonials]">
                                                <label class="form-label fs-13" for="add[role_testimonials]">
                                                    {{ trans('labels.add') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_testimonials" name="edit[role_testimonials]"
                                                    {{ helper::check_access('role_testimonials', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                    id="edit[role_testimonials]">
                                                <label class="form-label fs-13" for="edit[role_testimonials]">
                                                    {{ trans('labels.edit') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_testimonials" name="delete[role_testimonials]"
                                                    {{ helper::check_access('role_testimonials', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                    id="delete[role_testimonials]">
                                                <label class="form-label fs-13" for="delete[role_testimonials]">
                                                    {{ trans('labels.delete') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-block mb-3">
                                        <div class="row">
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_faqs"
                                                    name="modules[role_faqs]"
                                                    {{ helper::check_access('role_faqs', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                    id="manage[role_faqs]">
                                                <label class="form-label fs-13" for="manage[role_faqs]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_faqs"
                                                    name="add[role_faqs]"
                                                    {{ helper::check_access('role_faqs', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                    id="add[role_faqs]">
                                                <label class="form-label fs-13" for="add[role_faqs]">
                                                    {{ trans('labels.add') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_faqs"
                                                    name="edit[role_faqs]"
                                                    {{ helper::check_access('role_faqs', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                    id="edit[role_faqs]">
                                                <label class="form-label fs-13" for="edit[role_faqs]">
                                                    {{ trans('labels.edit') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_faqs"
                                                    name="delete[role_faqs]"
                                                    {{ helper::check_access('role_faqs', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                    id="delete[role_faqs]">
                                                <label class="form-label fs-13" for="delete[role_faqs]">
                                                    {{ trans('labels.delete') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-block mb-3">
                                        <div class="row">
                                            <div class="col-4">
                                                <input class="form-check-input" type="checkbox" value="role_cms_pages"
                                                    name="modules[role_cms_pages]"
                                                    {{ helper::check_access('role_cms_pages', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                    id="manage[role_cms_pages]">
                                                <label class="form-label fs-13" for="manage[role_cms_pages]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_cms_pages"
                                                    name="edit[role_cms_pages]"
                                                    {{ helper::check_access('role_cms_pages', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                    id="edit[role_cms_pages]">
                                                <label class="form-label fs-13" for="edit[role_cms_pages]">
                                                    {{ trans('labels.edit') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                                        @if (@helper::checkaddons('coupon'))
                                            <div class="d-block mb-3">
                                                <div class="row">
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_coupons" name="modules[role_coupons]"
                                                            {{ helper::check_access('role_coupons', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                            id="manage[role_coupons]">
                                                        <label class="form-label fs-13" for="manage[role_coupons]">
                                                            {{ trans('labels.view') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_coupons" name="add[role_coupons]"
                                                            {{ helper::check_access('role_coupons', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                            id="add[role_coupons]">
                                                        <label class="form-label fs-13" for="add[role_coupons]">
                                                            {{ trans('labels.add') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_coupons" name="edit[role_coupons]"
                                                            {{ helper::check_access('role_coupons', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                            id="edit[role_coupons]">
                                                        <label class="form-label fs-13" for="edit[role_coupons]">
                                                            {{ trans('labels.edit') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_coupons" name="delete[role_coupons]"
                                                            {{ helper::check_access('role_coupons', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                            id="delete[role_coupons]">
                                                        <label class="form-label fs-13" for="delete[role_coupons]">
                                                            {{ trans('labels.delete') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if (@helper::checkaddons('employee'))
                                            <div class="d-block mb-3">
                                                <div class="row">
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_roles" name="modules[role_roles]"
                                                            {{ helper::check_access('role_roles', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                            id="manage[role_roles]">
                                                        <label class="form-label fs-13" for="manage[role_roles]">
                                                            {{ trans('labels.view') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_roles" name="add[role_roles]"
                                                            {{ helper::check_access('role_roles', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                            id="add[role_roles]">
                                                        <label class="form-label fs-13" for="add[role_roles]">
                                                            {{ trans('labels.add') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_roles" name="edit[role_roles]"
                                                            {{ helper::check_access('role_roles', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                            id="edit[role_roles]">
                                                        <label class="form-label fs-13" for="edit[role_roles]">
                                                            {{ trans('labels.edit') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_roles" name="delete[role_roles]"
                                                            {{ helper::check_access('role_roles', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                            id="delete[role_roles]">
                                                        <label class="form-label fs-13" for="delete[role_roles]">
                                                            {{ trans('labels.delete') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-block mb-3">
                                                <div class="row">
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_employees" name="modules[role_employees]"
                                                            {{ helper::check_access('role_employees', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                            id="manage[role_employees]">
                                                        <label class="form-label fs-13" for="manage[role_employees]">
                                                            {{ trans('labels.view') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_employees" name="add[role_employees]"
                                                            {{ helper::check_access('role_employees', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                            id="add[role_employees]">
                                                        <label class="form-label fs-13" for="add[role_employees]">
                                                            {{ trans('labels.add') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_employees" name="edit[role_employees]"
                                                            {{ helper::check_access('role_employees', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                            id="edit[role_employees]">
                                                        <label class="form-label fs-13" for="edit[role_employees]">
                                                            {{ trans('labels.edit') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_employees" name="delete[role_employees]"
                                                            {{ helper::check_access('role_employees', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                            id="delete[role_employees]">
                                                        <label class="form-label fs-13" for="delete[role_employees]">
                                                            {{ trans('labels.delete') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                    @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                        @if (@helper::checkaddons('subscription'))
                                            @if (@helper::checkaddons('employee'))
                                                @php
                                                    $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)
                                                        ->orderByDesc('id')
                                                        ->first();
                                                    if ($user->allow_without_subscription == 1) {
                                                        $role_management = 1;
                                                    } else {
                                                        $role_management = @$checkplan->role_management;
                                                    }
                                                @endphp
                                                @if ($role_management == 1)
                                                    <div class="d-block mb-3">
                                                        <div class="row">
                                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="role_roles" name="modules[role_roles]"
                                                                    {{ helper::check_access('role_roles', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                                    id="manage[role_roles]">
                                                                <label class="form-label fs-13"
                                                                    for="manage[role_roles]">
                                                                    {{ trans('labels.view') }}
                                                                </label>
                                                            </div>
                                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="role_roles" name="add[role_roles]"
                                                                    {{ helper::check_access('role_roles', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                                    id="add[role_roles]">
                                                                <label class="form-label fs-13" for="add[role_roles]">
                                                                    {{ trans('labels.add') }}
                                                                </label>
                                                            </div>
                                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="role_roles" name="edit[role_roles]"
                                                                    {{ helper::check_access('role_roles', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                                    id="edit[role_roles]">
                                                                <label class="form-label fs-13" for="edit[role_roles]">
                                                                    {{ trans('labels.edit') }}
                                                                </label>
                                                            </div>
                                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="role_roles" name="delete[role_roles]"
                                                                    {{ helper::check_access('role_roles', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                                    id="delete[role_roles]">
                                                                <label class="form-label fs-13"
                                                                    for="delete[role_roles]">
                                                                    {{ trans('labels.delete') }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-block mb-3">
                                                        <div class="row">
                                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="role_employees"
                                                                    name="modules[role_employees]"
                                                                    {{ helper::check_access('role_employees', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                                    id="manage[role_employees]">
                                                                <label class="form-label fs-13"
                                                                    for="manage[role_employees]">
                                                                    {{ trans('labels.view') }}
                                                                </label>
                                                            </div>
                                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="role_employees" name="add[role_employees]"
                                                                    {{ helper::check_access('role_employees', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                                    id="add[role_employees]">
                                                                <label class="form-label fs-13"
                                                                    for="add[role_employees]">
                                                                    {{ trans('labels.add') }}
                                                                </label>
                                                            </div>
                                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="role_employees" name="edit[role_employees]"
                                                                    {{ helper::check_access('role_employees', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                                    id="edit[role_employees]">
                                                                <label class="form-label fs-13"
                                                                    for="edit[role_employees]">
                                                                    {{ trans('labels.edit') }}
                                                                </label>
                                                            </div>
                                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="role_employees" name="delete[role_employees]"
                                                                    {{ helper::check_access('role_employees', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                                    id="delete[role_employees]">
                                                                <label class="form-label fs-13"
                                                                    for="delete[role_employees]">
                                                                    {{ trans('labels.delete') }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @else
                                            @if (@helper::checkaddons('employee'))
                                                <div class="d-block mb-3">
                                                    <div class="row">
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_roles" name="modules[role_roles]"
                                                                {{ helper::check_access('role_roles', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                                id="manage[role_roles]">
                                                            <label class="form-label fs-13" for="manage[role_roles]">
                                                                {{ trans('labels.view') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_roles" name="add[role_roles]"
                                                                {{ helper::check_access('role_roles', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                                id="add[role_roles]">
                                                            <label class="form-label fs-13" for="add[role_roles]">
                                                                {{ trans('labels.add') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_roles" name="edit[role_roles]"
                                                                {{ helper::check_access('role_roles', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                                id="edit[role_roles]">
                                                            <label class="form-label fs-13" for="edit[role_roles]">
                                                                {{ trans('labels.edit') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_roles" name="delete[role_roles]"
                                                                {{ helper::check_access('role_roles', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                                id="delete[role_roles]">
                                                            <label class="form-label fs-13" for="delete[role_roles]">
                                                                {{ trans('labels.delete') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-block mb-3">
                                                    <div class="row">
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_employees" name="modules[role_employees]"
                                                                {{ helper::check_access('role_employees', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                                id="manage[role_employees]">
                                                            <label class="form-label fs-13"
                                                                for="manage[role_employees]">
                                                                {{ trans('labels.view') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_employees" name="add[role_employees]"
                                                                {{ helper::check_access('role_employees', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                                id="add[role_employees]">
                                                            <label class="form-label fs-13" for="add[role_employees]">
                                                                {{ trans('labels.add') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_employees" name="edit[role_employees]"
                                                                {{ helper::check_access('role_employees', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                                id="edit[role_employees]">
                                                            <label class="form-label fs-13" for="edit[role_employees]">
                                                                {{ trans('labels.edit') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_employees" name="delete[role_employees]"
                                                                {{ helper::check_access('role_employees', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                                id="delete[role_employees]">
                                                            <label class="form-label fs-13"
                                                                for="delete[role_employees]">
                                                                {{ trans('labels.delete') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                    <div class="d-block mb-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_subscribers" name="modules[role_subscribers]"
                                                    {{ helper::check_access('role_subscribers', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                    id="manage[role_subscribers]">
                                                <label class="form-label fs-13" for="manage[role_subscribers]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_subscribers" name="delete[role_subscribers]"
                                                    {{ helper::check_access('role_subscribers', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                    id="delete[role_subscribers]">
                                                <label class="form-label fs-13" for="delete[role_subscribers]">
                                                    {{ trans('labels.delete') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-block mb-3">
                                        <div class="row">
                                            <div class="col-4">
                                                <input class="form-check-input" type="checkbox" value="role_inquiries"
                                                    name="modules[role_inquiries]"
                                                    {{ helper::check_access('role_inquiries', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                    id="manage[role_inquiries]">
                                                <label class="form-label fs-13" for="manage[role_inquiries]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_inquiries"
                                                    name="edit[role_inquiries]"
                                                    {{ helper::check_access('role_inquiries', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                    id="edit[role_inquiries]">
                                                <label class="form-label fs-13" for="edit[role_inquiries]">
                                                    {{ trans('labels.edit') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_inquiries"
                                                    name="delete[role_inquiries]"
                                                    {{ helper::check_access('role_inquiries', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                    id="delete[role_inquiries]">
                                                <label class="form-label fs-13" for="delete[role_inquiries]">
                                                    {{ trans('labels.delete') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                        @if (@helper::checkaddons('product_inquiry'))
                                            <div class="d-block mb-3">
                                                <div class="row">
                                                    <div class="col-4">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_product_inquiry"
                                                            name="modules[role_product_inquiry]"
                                                            {{ helper::check_access('role_product_inquiry', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                            id="manage[role_product_inquiry]">
                                                        <label class="form-label fs-13"
                                                            for="manage[role_product_inquiry]">
                                                            {{ trans('labels.view') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_product_inquiry"
                                                            name="edit[role_product_inquiry]"
                                                            {{ helper::check_access('role_product_inquiry', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                            id="edit[role_product_inquiry]">
                                                        <label class="form-label fs-13"
                                                            for="edit[role_product_inquiry]">
                                                            {{ trans('labels.edit') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_product_inquiry"
                                                            name="delete[role_product_inquiry]"
                                                            {{ helper::check_access('role_product_inquiry', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                            id="delete[role_product_inquiry]">
                                                        <label class="form-label fs-13"
                                                            for="delete[role_product_inquiry]">
                                                            {{ trans('labels.delete') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_share"
                                                        name="modules[role_share]"
                                                        {{ helper::check_access('role_share', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                        id="manage[role_share]">
                                                    <label class="form-label fs-13" for="manage[role_share]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        @if (@helper::checkaddons('subscription'))
                                            @if (@helper::checkaddons('whatsapp_message'))
                                                @php
                                                    $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)
                                                        ->orderByDesc('id')
                                                        ->first();
                                                    if ($user->allow_without_subscription == 1) {
                                                        $whatsapp_message = 1;
                                                    } else {
                                                        $whatsapp_message = @$checkplan->whatsapp_message;
                                                    }
                                                @endphp
                                                @if ($whatsapp_message == 1)
                                                    <div class="d-block mb-3">
                                                        <div class="row">
                                                            <div class="col-4">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="role_whatsapp_settings"
                                                                    name="modules[role_whatsapp_settings]"
                                                                    {{ helper::check_access('role_whatsapp_settings', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                                    id="manage[role_whatsapp_settings]">
                                                                <label class="form-label fs-13"
                                                                    for="manage[role_whatsapp_settings]">
                                                                    {{ trans('labels.view') }}
                                                                </label>
                                                            </div>
                                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="role_whatsapp_settings"
                                                                    name="edit[role_whatsapp_settings]"
                                                                    {{ helper::check_access('role_whatsapp_settings', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                                    id="edit[role_whatsapp_settings]">
                                                                <label class="form-label fs-13"
                                                                    for="edit[role_whatsapp_settings]">
                                                                    {{ trans('labels.edit') }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @else
                                            @if (@helper::checkaddons('whatsapp_message'))
                                                <div class="d-block mb-3">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_whatsapp_settings"
                                                                name="modules[role_whatsapp_settings]"
                                                                {{ helper::check_access('role_whatsapp_settings', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                                id="manage[role_whatsapp_settings]">
                                                            <label class="form-label fs-13"
                                                                for="manage[role_whatsapp_settings]">
                                                                {{ trans('labels.view') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_whatsapp_settings"
                                                                name="edit[role_whatsapp_settings]"
                                                                {{ helper::check_access('role_whatsapp_settings', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                                id="edit[role_whatsapp_settings]">
                                                            <label class="form-label fs-13"
                                                                for="edit[role_whatsapp_settings]">
                                                                {{ trans('labels.edit') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                        @if (@helper::checkaddons('subscription'))
                                            @if (@helper::checkaddons('telegram_message'))
                                                @php
                                                    $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)
                                                        ->orderByDesc('id')
                                                        ->first();
                                                    if ($user->allow_without_subscription == 1) {
                                                        $telegram_message = 1;
                                                    } else {
                                                        $telegram_message = @$checkplan->telegram_message;
                                                    }
                                                @endphp
                                                @if ($telegram_message == 1)
                                                    <div class="d-block mb-3">
                                                        <div class="row">
                                                            <div class="col-4">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="role_telegram_settings"
                                                                    name="modules[role_telegram_settings]"
                                                                    {{ helper::check_access('role_telegram_settings', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                                    id="manage[role_telegram_settings]">
                                                                <label class="form-label fs-13"
                                                                    for="manage[role_telegram_settings]">
                                                                    {{ trans('labels.view') }}
                                                                </label>
                                                            </div>
                                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="role_telegram_settings"
                                                                    name="edit[role_telegram_settings]"
                                                                    {{ helper::check_access('role_telegram_settings', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                                    id="edit[role_telegram_settings]">
                                                                <label class="form-label fs-13"
                                                                    for="edit[role_telegram_settings]">
                                                                    {{ trans('labels.edit') }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @else
                                            @if (@helper::checkaddons('telegram_message'))
                                                <div class="d-block mb-3">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_telegram_settings"
                                                                name="modules[role_telegram_settings]"
                                                                {{ helper::check_access('role_telegram_settings', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                                id="manage[role_telegram_settings]">
                                                            <label class="form-label fs-13"
                                                                for="manage[role_telegram_settings]">
                                                                {{ trans('labels.view') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_telegram_settings"
                                                                name="edit[role_telegram_settings]"
                                                                {{ helper::check_access('role_telegram_settings', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                                id="edit[role_telegram_settings]">
                                                            <label class="form-label fs-13"
                                                                for="edit[role_telegram_settings]">
                                                                {{ trans('labels.edit') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                        @if (@helper::checkaddons('language'))
                                            @if (helper::listoflanguage()->count() > 1)
                                                <div class="d-block mb-3">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_language_settings"
                                                                name="modules[role_language_settings]"
                                                                {{ helper::check_access('role_language_settings', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                                id="manage[role_language_settings]">
                                                            <label class="form-label fs-13"
                                                                for="manage[role_language_settings]">
                                                                {{ trans('labels.view') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_language_settings"
                                                                name="edit[role_language_settings]"
                                                                {{ helper::check_access('role_language_settings', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                                id="edit[role_language_settings]">
                                                            <label class="form-label fs-13"
                                                                for="edit[role_language_settings]">
                                                                {{ trans('labels.edit') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                        @if (@helper::checkaddons('currency_settigns'))
                                            @if (helper::available_currency()->count() > 1)
                                                <div class="d-block mb-3">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_currency_settings"
                                                                name="modules[role_currency_settings]"
                                                                {{ helper::check_access('role_currency_settings', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                                id="manage[role_currency_settings]">
                                                            <label class="form-label fs-13"
                                                                for="manage[role_currency_settings]">
                                                                {{ trans('labels.view') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_currency_settings"
                                                                name="edit[role_currency_settings]"
                                                                {{ helper::check_access('role_currency_settings', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                                id="edit[role_currency_settings]">
                                                            <label class="form-label fs-13"
                                                                for="edit[role_currency_settings]">
                                                                {{ trans('labels.edit') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                    @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                                        @if (@helper::checkaddons('language'))
                                            <div class="d-block mb-3">
                                                <div class="row">
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_language_settings"
                                                            name="modules[role_language_settings]"
                                                            {{ helper::check_access('role_language_settings', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                            id="manage[role_language_settings]">
                                                        <label class="form-label fs-13"
                                                            for="manage[role_language_settings]">
                                                            {{ trans('labels.view') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_language_settings"
                                                            name="add[role_language_settings]"
                                                            {{ helper::check_access('role_language_settings', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                            id="add[role_language_settings]">
                                                        <label class="form-label fs-13"
                                                            for="add[role_language_settings]">
                                                            {{ trans('labels.add') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_language_settings"
                                                            name="edit[role_language_settings]"
                                                            {{ helper::check_access('role_language_settings', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                            id="edit[role_language_settings]">
                                                        <label class="form-label fs-13"
                                                            for="edit[role_language_settings]">
                                                            {{ trans('labels.edit') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_language_settings"
                                                            name="delete[role_language_settings]"
                                                            {{ helper::check_access('role_language_settings', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                            id="delete[role_language_settings]">
                                                        <label class="form-label fs-13"
                                                            for="delete[role_language_settings]">
                                                            {{ trans('labels.delete') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if (@helper::checkaddons('currency_settigns'))
                                            <div class="d-block mb-3">
                                                <div class="row">
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_currency_settings"
                                                            name="modules[role_currency_settings]"
                                                            {{ helper::check_access('role_currency_settings', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                            id="manage[role_currency_settings]">
                                                        <label class="form-label fs-13"
                                                            for="manage[role_currency_settings]">
                                                            {{ trans('labels.view') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_currency_settings"
                                                            name="add[role_currency_settings]"
                                                            {{ helper::check_access('role_currency_settings', $data->id, $data->vendor_id, 'add') == 1 ? 'checked' : '' }}
                                                            id="add[role_currency_settings]">
                                                        <label class="form-label fs-13"
                                                            for="add[role_currency_settings]">
                                                            {{ trans('labels.add') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_currency_settings"
                                                            name="edit[role_currency_settings]"
                                                            {{ helper::check_access('role_currency_settings', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                            id="edit[role_currency_settings]">
                                                        <label class="form-label fs-13"
                                                            for="edit[role_currency_settings]">
                                                            {{ trans('labels.edit') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_currency_settings"
                                                            name="delete[role_currency_settings]"
                                                            {{ helper::check_access('role_currency_settings', $data->id, $data->vendor_id, 'delete') == 1 ? 'checked' : '' }}
                                                            id="delete[role_currency_settings]">
                                                        <label class="form-label fs-13"
                                                            for="delete[role_currency_settings]">
                                                            {{ trans('labels.delete') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif


                                    <div class="d-block mb-3">
                                        <div class="row">
                                            <div class="col-4">
                                                <input class="form-check-input" type="checkbox" value="role_settings"
                                                    name="modules[role_settings]"
                                                    {{ helper::check_access('role_settings', $data->id, $data->vendor_id, 'manage') == 1 ? 'checked' : '' }}
                                                    id="manage[role_settings]">
                                                <label class="form-label fs-13" for="manage[role_settings]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_settings"
                                                    name="edit[role_settings]"
                                                    {{ helper::check_access('role_settings', $data->id, $data->vendor_id, 'edit') == 1 ? 'checked' : '' }}
                                                    id="edit[role_settings]">
                                                <label class="form-label fs-13" for="edit[role_settings]">
                                                    {{ trans('labels.edit') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('modules')
                                <br><span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mt-3 {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                            <a href="{{ URL::to('admin/roles') }}"
                                class="btn btn-danger px-sm-4">{{ trans('labels.cancel') }}</a>
                            <button
                                class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_roles', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
                                @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif>{{ trans('labels.save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#permissioncheckbox input:checkbox').each(function(e) {
                "use strict";
                var id = $(this).val();
                var manageid = "manage[" + id + "]";
                var addid = "add[" + id + "]";
                var editid = "edit[" + id + "]";
                var deleteid = "delete[" + id + "]";
                if ($("[id='" + manageid + "']").prop('checked') == true ||
                    $("[id='" + addid + "']").prop('checked') == true ||
                    $("[id='" + editid + "']").prop('checked') == true ||
                    $("[id='" + deleteid + "']").prop('checked') == true) {
                    $($('#' + id)).prop('checked', $(this).prop('checked'));
                }
                $('#checkall').prop('checked', $(this).prop('checked'));
            });
        });

        $('#checkall').on('click', function() {
            "use strict";
            var checked = $(this).prop('checked');
            $('input:checkbox').prop('checked', checked);
        }).change();

        $('#checkboxes input:checkbox').on('click', function() {

            var checked = $(this).prop('checked');
            var manageid = "manage[" + this.id + "]";
            var addid = "add[" + this.id + "]";
            var editid = "edit[" + this.id + "]";
            var deleteid = "delete[" + this.id + "]";
            $("[id='" + manageid + "']").prop('checked', checked);
            $("[id='" + addid + "']").prop('checked', checked);
            $("[id='" + editid + "']").prop('checked', checked);
            $("[id='" + deleteid + "']").prop('checked', checked);
        });

        $('#permissioncheckbox input:checkbox').on('click', function() {

            var checked = $(this).prop('checked');
            var value = $(this).val();
            var manageid = "manage[" + $(this).val() + "]";
            var addid = "add[" + $(this).val() + "]";
            var editid = "edit[" + $(this).val() + "]";
            var deleteid = "delete[" + $(this).val() + "]";
            if ($("[id='" + addid + "']").prop('checked') == true || $("[id='" + editid + "']").prop('checked') ==
                true || $("[id='" + deleteid + "']").prop('checked') == true) {
                $("[id='" + manageid + "']").prop('checked', true);
            }

        }).change();
    </script>
@endsection
