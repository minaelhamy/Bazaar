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
        <h5 class="text-capitalize fw-600 text-dark color-changer fs-4">{{ trans('labels.add_new') }}</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item text-dark"><a href="{{ URL::to('admin/roles') }}" class="color-changer">{{ trans('labels.roles') }}</a>
                </li>
                <li class="breadcrumb-item active {{ session()->get('direction') == 2 ? 'breadcrumb-rtl' : '' }}"
                    aria-current="page">{{ trans('labels.add') }}</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card border-0 my-3 box-shadow">
                <div class="card-body">
                    <form action="{{ URL::to('admin/roles/save') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label" for="">{{ trans('labels.role') }} <span
                                            class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" name="name"
                                        placeholder="{{ trans('labels.role') }}" required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <h5 class="mb-3 fw-bold color-changer">{{ trans('labels.system_modules') }} <span class="text-danger">*</span>
                        </h5>
                        <div class="row bg-light rolmangement_dark py-3">
                            <div class="col-sm-4 col-6 cursor-pointer d-block">
                                <input class="form-check-input" type="checkbox" value="" name="checkall"
                                    id="checkall">
                                <label
                                    class="form-check-label fw-600 text-dark {{ session()->get('direction') == 2 ? 'ms-5' : 'me-5' }}"
                                    for="checkall">
                                    {{ trans('labels.modules') }}
                                </label>

                            </div>
                            <div class="col-sm-8 col-6 d-block">
                                <label
                                    class="form-check-label {{ session()->get('direction') == 2 ? 'ms-5' : 'me-5' }} fw-bolder">
                                    {{ trans('labels.permissions') }}
                                </label>
                            </div>
                        </div>
                        <div class="row mt-3">

                            <div class="col-sm-4 col-6" id="checkboxes">
                                <div class="cursor-pointer d-block mb-3">
                                    <input class="form-check-input" type="checkbox" value="" name="dashboard"
                                        id="dashboard">
                                    <label class="cursor-pointer form-label fs-13" for="dashboard">
                                        {{ trans('labels.dashboard') }}
                                    </label>
                                </div>

                                @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value="" name="addons_manager"
                                            id="addons_manager">
                                        <label class="cursor-pointer form-label fs-13" for="addons_manager">
                                            {{ trans('labels.addons_manager') }}
                                        </label>
                                    </div>
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value="" name="vendors"
                                            id="vendors">
                                        <label class="cursor-pointer form-label fs-13" for="vendors">
                                            {{ trans('labels.users') }}
                                        </label>
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
                                                <div class="cursor-pointer d-block mb-3">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        name="POS (Point Of Sale)" id="pos">
                                                    <label class="cursor-pointer form-label fs-13" for="pos">
                                                        {{ trans('labels.pos') }}
                                                    </label>
                                                </div>
                                            @endif
                                        @endif
                                    @else
                                        @if (@helper::checkaddons('pos'))
                                            <div class="cursor-pointer d-block mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    name="POS (Point Of Sale)" id="pos">
                                                <label class="cursor-pointer form-label fs-13" for="pos">
                                                    {{ trans('labels.pos') }}
                                                </label>
                                            </div>
                                        @endif
                                    @endif
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value="" name="orders"
                                            id="orders">
                                        <label class="cursor-pointer form-label fs-13" for="orders">
                                            {{ trans('labels.orders') }}
                                        </label>
                                    </div>

                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value="" name="report"
                                            id="report">
                                        <label class="cursor-pointer form-label fs-13" for="report">
                                            {{ trans('labels.report') }}
                                        </label>
                                    </div>
                                @endif

                                @if (@helper::checkaddons('customer_login'))
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value="" name="Customers"
                                            id="customers">
                                        <label class="cursor-pointer form-label fs-13" for="customers">
                                            {{ trans('labels.customers') }}
                                        </label>
                                    </div>
                                @endif

                                @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value="" name="Categories"
                                            id="categories">
                                        <label class="cursor-pointer form-label fs-13" for="categories">
                                            {{ trans('labels.categories') }}
                                        </label>
                                    </div>
                                @endif
                                <div class="cursor-pointer d-block mb-3">
                                    <input class="form-check-input" type="checkbox" value="" name="Tax"
                                        id="tax">
                                    <label class="cursor-pointer form-label fs-13" for="tax">
                                        {{ trans('labels.tax') }}
                                    </label>
                                </div>
                                @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value=""
                                            name="Global Extras" id="global_extras">
                                        <label class="cursor-pointer form-label fs-13" for="global_extras">
                                            {{ trans('labels.global_extras') }}
                                        </label>
                                    </div>
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value="" name="Products"
                                            id="products">
                                        <label class="cursor-pointer form-label fs-13" for="products">
                                            {{ trans('labels.products') }}
                                        </label>
                                    </div>
                                    @if (@helper::checkaddons('product_import'))
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="import_products" id="import_products">
                                            <label class="cursor-pointer form-label fs-13" for="import_products">
                                                {{ trans('labels.product_upload') }}
                                            </label>
                                        </div>
                                    @endif
                                    
                                    
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value=""
                                            name="shipping_management" id="shipping_management">
                                        <label class="cursor-pointer form-label fs-13" for="shipping_management">
                                            {{ trans('labels.shipping_management') }}
                                        </label>
                                    </div>
                                    @if (@helper::checkaddons('question_answer'))
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value=""
                                            name="product_question_answer" id="product_question_answer">
                                        <label class="cursor-pointer form-label fs-13" for="product_question_answer">
                                            {{ trans('labels.product_question_answer') }}
                                        </label>
                                    </div>
                                    @endif
                                    @if (@helper::checkaddons('shopify'))
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="Shopify" id="shopify">
                                            <label class="cursor-pointer form-label fs-13" for="shopify">
                                                {{ trans('labels.shopify') }}
                                            </label>
                                        </div>
                                    @endif
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value="" name="Sliders"
                                            id="sliders">
                                        <label class="cursor-pointer form-label fs-13" for="sliders">
                                            {{ trans('labels.sliders') }}
                                        </label>
                                    </div>
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value="" name="Banner"
                                            id="banner">
                                        <label class="cursor-pointer form-label fs-13" for="banner">
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
                                                        name="Coupons" id="coupons">
                                                    <label class="cursor-pointer form-label fs-13" for="coupons">
                                                        {{ trans('labels.coupons') }}
                                                    </label>
                                                </div>
                                            @endif
                                        @endif
                                    @else
                                        @if (@helper::checkaddons('coupon'))
                                            <div class="cursor-pointer d-block mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    name="Coupons" id="coupons">
                                                <label class="cursor-pointer form-label fs-13" for="coupons">
                                                    {{ trans('labels.coupons') }}
                                                </label>
                                            </div>
                                        @endif
                                    @endif
                                    @if (@helper::checkaddons('top_deals'))
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="top_deals" id="top_deals">
                                            <label class="cursor-pointer form-label fs-13" for="top_deals">
                                                {{ trans('labels.top_deals') }}
                                            </label>
                                        </div>
                                    @endif
                                    @if (@helper::checkaddons('firebase_notification'))
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="firebase_notification" id="firebase_notification">
                                            <label class="cursor-pointer form-label fs-13" for="firebase_notification">
                                                {{ trans('labels.firebase_notification') }}
                                            </label>
                                        </div>
                                    @endif
                                @endif
                                @if (@helper::checkaddons('subscription'))
                                    @if ($user->allow_without_subscription != 1)
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="Subscription Plans" id="pricing_plans">
                                            <label class="cursor-pointer form-label fs-13" for="pricing_plans">
                                                {{ trans('labels.pricing_plans') }}
                                            </label>
                                        </div>
                                    @endif

                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value=""
                                            name="Transactions" id="transaction">
                                        <label class="cursor-pointer form-label fs-13" for="transaction">
                                            {{ trans('labels.transaction') }}
                                        </label>
                                    </div>
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value=""
                                            name="Payment Methods" id="payment_methods">
                                        <label class="cursor-pointer form-label fs-13" for="payment_methods">
                                            {{ trans('labels.payment_methods') }}
                                        </label>
                                    </div>
                                @else
                                    @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="Payment Methods" id="payment_methods">
                                            <label class="cursor-pointer form-label fs-13" for="payment_methods">
                                                {{ trans('labels.payment_methods') }}
                                            </label>
                                        </div>
                                    @endif
                                @endif
                                @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value="" name="cities"
                                            id="cities">
                                        <label class="cursor-pointer form-label fs-13" for="cities">
                                            {{ trans('labels.cities') }}
                                        </label>
                                    </div>
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value="" name="areas"
                                            id="areas">
                                        <label class="cursor-pointer form-label fs-13" for="areas">
                                            {{ trans('labels.areas') }}
                                        </label>
                                    </div>
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value=""
                                            name="store_categories" id="store_categories">
                                        <label class="cursor-pointer form-label fs-13" for="store_categories">
                                            {{ trans('labels.store_categories') }}
                                        </label>
                                    </div>
                                    @if (@helper::checkaddons('custom_domain'))
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="Custom Domains" id="custom_domains">
                                            <label class="cursor-pointer form-label fs-13" for="custom_domains">
                                                {{ trans('labels.custom_domains') }}
                                            </label>
                                        </div>
                                    @endif
                                @endif
                                @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value=""
                                            name="Working Hours" id="working_hours">
                                        <label class="cursor-pointer form-label fs-13" for="working_hours">
                                            {{ trans('labels.working_hours') }}
                                        </label>
                                    </div>
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value="" name="Table"
                                            id="table">
                                        <label class="cursor-pointer form-label fs-13" for="table">
                                            {{ trans('labels.table') }}
                                        </label>
                                    </div>
                                    @if (@helper::checkaddons('custom_status'))
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="Custom Status" id="custom_status">
                                            <label class="cursor-pointer form-label fs-13" for="custom_status">
                                                {{ trans('labels.custom_status') }}
                                            </label>
                                        </div>
                                    @endif
                                    @if (@helper::checkaddons('subscription'))
                                        @if (@helper::checkaddons('custom_domain'))
                                            @php
                                                $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)
                                                    ->orderByDesc('id')
                                                    ->first();

                                                if ($user->allow_without_subscription == 1) {
                                                    $custom_domain = 1;
                                                } else {
                                                    $custom_domain = @$checkplan->custom_domain;
                                                }
                                            @endphp
                                            @if (@$custom_domain == 1)
                                                <div class="cursor-pointer d-block mb-3">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        name="Custom Domains" id="custom_domains">
                                                    <label class="cursor-pointer form-label fs-13" for="custom_domains">
                                                        {{ trans('labels.custom_domains') }}
                                                    </label>
                                                </div>
                                            @endif
                                        @endif
                                    @else
                                        @if (@helper::checkaddons('custom_domain'))
                                            <div class="cursor-pointer d-block mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    name="Custom Domains" id="custom_domains">
                                                <label class="cursor-pointer form-label fs-13" for="custom_domains">
                                                    {{ trans('labels.custom_domains') }}
                                                </label>
                                            </div>
                                        @endif
                                    @endif
                                @endif

                                <div class="cursor-pointer d-block mb-3">
                                    <input class="form-check-input" type="checkbox" value="" name="Basic Settings"
                                        id="basic_settings">
                                    <label class="cursor-pointer form-label fs-13" for="basic_settings">
                                        {{ trans('labels.basic_settings') }}
                                    </label>
                                </div>
                                @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value=""
                                            name="how_it_works" id="how_it_works">
                                        <label class="cursor-pointer form-label fs-13" for="how_it_works">
                                            {{ trans('labels.how_it_works') }}
                                        </label>
                                    </div>
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value=""
                                            name="theme_images" id="theme_images">
                                        <label class="cursor-pointer form-label fs-13" for="theme_images">
                                            {{ trans('labels.theme_images') }}
                                        </label>
                                    </div>
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value="" name="features"
                                            id="features">
                                        <label class="cursor-pointer form-label fs-13" for="features">
                                            {{ trans('labels.features') }}
                                        </label>
                                    </div>
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value=""
                                            name="promotional_banners" id="promotional_banners">
                                        <label class="cursor-pointer form-label fs-13" for="promotional_banners">
                                            {{ trans('labels.promotional_banners') }}
                                        </label>
                                    </div>
                                    @if (@helper::checkaddons('blog'))
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="Blogs" id="blogs">
                                            <label class="cursor-pointer form-label fs-13" for="blogs">
                                                {{ trans('labels.blogs') }}
                                            </label>
                                        </div>
                                    @endif
                                @endif
                                @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value="" name="Who We Are"
                                            id="who_we_are">
                                        <label class="cursor-pointer form-label fs-13" for="who_we_are">
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
                                                        name="Blogs" id="blogs">
                                                    <label class="cursor-pointer form-label fs-13" for="blogs">
                                                        {{ trans('labels.blogs') }}
                                                    </label>
                                                </div>
                                            @endif
                                        @endif
                                    @else
                                        @if (@helper::checkaddons('blog'))
                                            <div class="cursor-pointer d-block mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    name="Blogs" id="blogs">
                                                <label class="cursor-pointer form-label fs-13" for="blogs">
                                                    {{ trans('labels.blogs') }}
                                                </label>
                                            </div>
                                        @endif
                                    @endif
                                @endif

                                <div class="cursor-pointer d-block mb-3">
                                    <input class="form-check-input" type="checkbox" value="" name="Testimonials"
                                        id="testimonials">
                                    <label class="cursor-pointer form-label fs-13" for="testimonials">
                                        {{ trans('labels.testimonials') }}
                                    </label>
                                </div>
                                <div class="cursor-pointer d-block mb-3">
                                    <input class="form-check-input" type="checkbox" value="" name="Faqs"
                                        id="faqs">
                                    <label class="cursor-pointer form-label fs-13" for="faqs">
                                        {{ trans('labels.faqs') }}
                                    </label>
                                </div>
                                <div class="cursor-pointer d-block mb-3">
                                    <input class="form-check-input" type="checkbox" value="" name="Cms Pages"
                                        id="cms_pages">
                                    <label class="cursor-pointer form-label fs-13" for="cms_pages">
                                        {{ trans('labels.cms_pages') }}
                                    </label>
                                </div>
                                @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                                    @if (@helper::checkaddons('coupon'))
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="Coupons" id="coupons">
                                            <label class="cursor-pointer form-label fs-13" for="coupons">
                                                {{ trans('labels.coupons') }}
                                            </label>
                                        </div>
                                    @endif
                                    @if (@helper::checkaddons('employee'))
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="Roles" id="roles">
                                            <label class="cursor-pointer form-label fs-13" for="roles">
                                                {{ trans('labels.roles') }}
                                            </label>
                                        </div>

                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="Employees" id="employees">
                                            <label class="cursor-pointer form-label fs-13" for="employees">
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
                                                        name="Roles" id="roles">
                                                    <label class="cursor-pointer form-label fs-13" for="roles">
                                                        {{ trans('labels.roles') }}
                                                    </label>
                                                </div>

                                                <div class="cursor-pointer d-block mb-3">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        name="Employees" id="employees">
                                                    <label class="cursor-pointer form-label fs-13" for="employees">
                                                        {{ trans('labels.employees') }}
                                                    </label>
                                                </div>
                                            @endif
                                        @endif
                                    @else
                                        @if (@helper::checkaddons('employee'))
                                            <div class="cursor-pointer d-block mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    name="Roles" id="roles">
                                                <label class="cursor-pointer form-label fs-13" for="roles">
                                                    {{ trans('labels.roles') }}
                                                </label>
                                            </div>

                                            <div class="cursor-pointer d-block mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    name="Employees" id="employees">
                                                <label class="cursor-pointer form-label fs-13" for="employees">
                                                    {{ trans('labels.employees') }}
                                                </label>
                                            </div>
                                        @endif
                                    @endif
                                @endif

                                <div class="cursor-pointer d-block mb-3">
                                    <input class="form-check-input" type="checkbox" value="" name="Subscribers"
                                        id="subscribers">
                                    <label class="cursor-pointer form-label fs-13" for="subscribers">
                                        {{ trans('labels.subscribers') }}
                                    </label>
                                </div>
                                <div class="cursor-pointer d-block mb-3">
                                    <input class="form-check-input" type="checkbox" value="" name="Inquiries"
                                        id="inquiries">
                                    <label class="cursor-pointer form-label fs-13" for="inquiries">
                                        {{ trans('labels.inquiries') }}
                                    </label>
                                </div>
                                @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                    @if (@helper::checkaddons('product_inquiry'))
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="product_inquiry" id="product_inquiry">
                                            <label class="cursor-pointer form-label fs-13" for="product_inquiry">
                                                {{ trans('labels.product_inquiry') }}
                                            </label>
                                        </div>
                                    @endif

                                    <div class="cursor-pointer d-block mb-3">
                                        <input class="form-check-input" type="checkbox" value="" name="Share"
                                            id="share">
                                        <label class="cursor-pointer form-label fs-13" for="share">
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
                                                        name="whatsapp_settings" id="whatsapp_settings">
                                                    <label class="cursor-pointer form-label fs-13" for="whatsapp_settings">
                                                        {{ trans('labels.whatsapp_settings') }}
                                                    </label>
                                                </div>
                                            @endif
                                        @endif
                                    @else
                                        @if (@helper::checkaddons('whatsapp_message'))
                                            <div class="cursor-pointer d-block mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    name="whatsapp_settings" id="whatsapp_settings">
                                                <label class="cursor-pointer form-label fs-13" for="whatsapp_settings">
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
                                                        name="telegram_settings" id="telegram_settings">
                                                    <label class="cursor-pointer form-label fs-13" for="telegram_settings">
                                                        {{ trans('labels.telegram_settings') }}
                                                    </label>
                                                </div>
                                            @endif
                                        @endif
                                    @else
                                        @if (@helper::checkaddons('telegram_message'))
                                            <div class="cursor-pointer d-block mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    name="telegram_settings" id="telegram_settings">
                                                <label class="cursor-pointer form-label fs-13" for="telegram_settings">
                                                    {{ trans('labels.telegram_settings') }}
                                                </label>
                                            </div>
                                        @endif
                                    @endif
                                    @if (@helper::checkaddons('language'))
                                        @if (helper::listoflanguage()->count() > 1)
                                            <div class="cursor-pointer d-block mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    name="Language Settings" id="language_settings">
                                                <label class="cursor-pointer form-label fs-13" for="language_settings">
                                                    {{ trans('labels.language-settings') }}
                                                </label>
                                            </div>
                                        @endif
                                    @endif
                                    @if (@helper::checkaddons('currency_settigns'))
                                        @if (helper::available_currency()->count() > 1)
                                            <div class="cursor-pointer d-block mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    name="Currency Settings" id="currency_settings">
                                                <label class="cursor-pointer form-label fs-13" for="currency_settings">
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
                                                name="language_settings" id="language_settings">
                                            <label class="cursor-pointer form-label fs-13" for="language_settings">
                                                {{ trans('labels.language-settings') }}
                                            </label>
                                        </div>
                                    @endif
                                    @if (@helper::checkaddons('currency_settigns'))
                                        <div class="cursor-pointer d-block mb-3">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="currency_settings" id="currency_settings">
                                            <label class="cursor-pointer form-label fs-13" for="currency_settings">
                                                {{ trans('labels.currency-settings') }}
                                            </label>
                                        </div>
                                    @endif
                                @endif
                                <div class="cursor-pointer d-block mb-3">
                                    <input class="form-check-input" type="checkbox" value="" name="Settings"
                                        id="settings">
                                    <label class="cursor-pointer form-label fs-13" for="settings">
                                        {{ trans('labels.settings') }}
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-8 col-6" id="permissioncheckbox">
                                <div class="d-block mb-3">
                                    <div class="row">
                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                            <input class="form-check-input" type="checkbox" value="role_dashboard"
                                                name="modules[role_dashboard]" id="manage[role_dashboard]">
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
                                                    name="modules[role_vendors]" id="manage[role_vendors]">
                                                <label class="form-label fs-13" for="manage[role_vendors]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_vendors"
                                                    name="add[role_vendors]" id="add[role_vendors]">
                                                <label class="form-label fs-13" for="add[role_vendors]">
                                                    {{ trans('labels.add') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_vendors"
                                                    name="edit[role_vendors]" id="edit[role_vendors]">
                                                <label class="form-label fs-13" for="edit[role_vendors]">
                                                    {{ trans('labels.edit') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_vendors"
                                                    name="delete[role_vendors]" id="delete[role_vendors]">
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
                                                                id="manage[role_pos]">
                                                            <label class="form-label fs-13" for="manage[role_pos]">
                                                                {{ trans('labels.view') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_pos" name="add[role_pos]" id="add[role_pos]">
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
                                                        <input class="form-check-input" type="checkbox" value="role_pos"
                                                            name="modules[role_pos]" id="manage[role_pos]">
                                                        <label class="form-label fs-13" for="manage[role_pos]">
                                                            {{ trans('labels.view') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox" value="role_pos"
                                                            name="add[role_pos]" id="add[role_pos]">
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
                                                    name="modules[role_orders]" id="manage[role_orders]">
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
                                                    name="modules[role_report]" id="manage[role_report]">
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
                                                <input class="form-check-input" type="checkbox" value="role_customers"
                                                    name="modules[role_customers]" id="manage[role_customers]">
                                                <label class="form-label fs-13" for="manage[role_customers]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_customers" name="add[role_customers]"
                                                        id="add[role_customers]">
                                                    <label class="form-label fs-13" for="add[role_customers]">
                                                        {{ trans('labels.add') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_customers" name="edit[role_customers]"
                                                        id="edit[role_customers]">
                                                    <label class="form-label fs-13" for="edit[role_customers]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_customers" name="delete[role_customers]"
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
                                                <input class="form-check-input" type="checkbox" value="role_categories"
                                                    name="modules[role_categories]" id="manage[role_categories]">
                                                <label class="form-label fs-13" for="manage[role_categories]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_categories"
                                                    name="add[role_categories]" id="add[role_categories]">
                                                <label class="form-label fs-13" for="add[role_categories]">
                                                    {{ trans('labels.add') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_categories"
                                                    name="edit[role_categories]" id="edit[role_categories]">
                                                <label class="form-label fs-13" for="edit[role_categories]">
                                                    {{ trans('labels.edit') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_categories"
                                                    name="delete[role_categories]" id="delete[role_categories]">
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
                                                name="modules[role_tax]" id="manage[role_tax]">
                                            <label class="form-label fs-13" for="manage[role_tax]">
                                                {{ trans('labels.view') }}
                                            </label>
                                        </div>
                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                            <input class="form-check-input" type="checkbox" value="role_tax"
                                                name="add[role_tax]" id="add[role_tax]">
                                            <label class="form-label fs-13" for="add[role_tax]">
                                                {{ trans('labels.add') }}
                                            </label>
                                        </div>
                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                            <input class="form-check-input" type="checkbox" value="role_tax"
                                                name="edit[role_tax]" id="edit[role_tax]">
                                            <label class="form-label fs-13" for="edit[role_tax]">
                                                {{ trans('labels.edit') }}
                                            </label>
                                        </div>
                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                            <input class="form-check-input" type="checkbox" value="role_tax"
                                                name="delete[role_tax]" id="delete[role_tax]">
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
                                                    id="manage[role_global_extras]">
                                                <label class="form-label fs-13" for="manage[role_global_extras]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_global_extras" name="add[role_global_extras]"
                                                    id="add[role_global_extras]">
                                                <label class="form-label fs-13" for="add[role_global_extras]">
                                                    {{ trans('labels.add') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_global_extras" name="edit[role_global_extras]"
                                                    id="edit[role_global_extras]">
                                                <label class="form-label fs-13" for="edit[role_global_extras]">
                                                    {{ trans('labels.edit') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_global_extras" name="delete[role_global_extras]"
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
                                                    name="modules[role_products]" id="manage[role_products]">
                                                <label class="form-label fs-13" for="manage[role_products]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_products"
                                                    name="add[role_products]" id="add[role_products]">
                                                <label class="form-label fs-13" for="add[role_products]">
                                                    {{ trans('labels.add') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_products"
                                                    name="edit[role_products]" id="edit[role_products]">
                                                <label class="form-label fs-13" for="edit[role_products]">
                                                    {{ trans('labels.edit') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_products"
                                                    name="delete[role_products]" id="delete[role_products]">
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
                                                        value="role_import_product" name="modules[role_import_product]"
                                                        id="manage[role_import_product]">
                                                    <label class="form-label fs-13" for="manage[role_import_product]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_import_product" name="add[role_import_product]"
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
                                                    id="manage[role_shipping_management]">
                                                <label class="form-label fs-13" for="manage[role_shipping_management]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            @if (@helper::checkaddons('shipping_area'))
                                                @if (helper::appdata($vendor_id)->shipping_area == 1)
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_shipping_management"
                                                            name="add[role_shipping_management]"
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
                                                    value="role_shipping_management" name="edit[role_shipping_management]"
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
                                    @if (@helper::checkaddons('question_answer'))
                                        <div class="d-block mb-3">
                                        <div class="row">
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_product_question_answer"
                                                    name="modules[role_product_question_answer]"
                                                    id="manage[role_product_question_answer]">
                                                <label class="form-label fs-13" for="manage[role_product_question_answer]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_product_question_answer" name="edit[role_product_question_answer]"
                                                    id="edit[role_product_question_answer]">
                                                <label class="form-label fs-13" for="edit[role_product_question_answer]">
                                                    {{ trans('labels.edit') }}
                                                </label>
                                            </div>
                                            @if (@helper::checkaddons('shipping_area'))
                                                @if (helper::appdata($vendor_id)->shipping_area == 1)
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_product_question_answer"
                                                            name="delete[role_product_question_answer]"
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
                                    @endif
                                    @if (@helper::checkaddons('shopify'))
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_shopify"
                                                        name="modules[role_shopify]" id="manage[role_shopify]">
                                                    <label class="form-label fs-13" for="manage[role_shopify]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_shopify"
                                                        name="add[role_shopify]" id="add[role_shopify]">
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
                                                    name="modules[role_sliders]" id="manage[role_sliders]">
                                                <label class="form-label fs-13" for="manage[role_sliders]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_sliders"
                                                    name="add[role_sliders]" id="add[role_sliders]">
                                                <label class="form-label fs-13" for="add[role_sliders]">
                                                    {{ trans('labels.add') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_sliders"
                                                    name="edit[role_sliders]" id="edit[role_sliders]">
                                                <label class="form-label fs-13" for="edit[role_sliders]">
                                                    {{ trans('labels.edit') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_sliders"
                                                    name="delete[role_sliders]" id="delete[role_sliders]">
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
                                                    name="modules[role_banner]" id="manage[role_banner]">
                                                <label class="form-label fs-13" for="manage[role_banner]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_banner"
                                                    name="add[role_banner]" id="add[role_banner]">
                                                <label class="form-label fs-13" for="add[role_banner]">
                                                    {{ trans('labels.add') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_banner"
                                                    name="edit[role_banner]" id="edit[role_banner]">
                                                <label class="form-label fs-13" for="edit[role_banner]">
                                                    {{ trans('labels.edit') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_banner"
                                                    name="delete[role_banner]" id="delete[role_banner]">
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
                                                                id="manage[role_coupons]">
                                                            <label class="form-label fs-13" for="manage[role_coupons]">
                                                                {{ trans('labels.view') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_coupons" name="add[role_coupons]"
                                                                id="add[role_coupons]">
                                                            <label class="form-label fs-13" for="add[role_coupons]">
                                                                {{ trans('labels.add') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_coupons" name="edit[role_coupons]"
                                                                id="edit[role_coupons]">
                                                            <label class="form-label fs-13" for="edit[role_coupons]">
                                                                {{ trans('labels.edit') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_coupons" name="delete[role_coupons]"
                                                                id="delete[role_coupons]">
                                                            <label class="form-label fs-13" for="delete[role_coupons]">
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
                                                            id="manage[role_coupons]">
                                                        <label class="form-label fs-13" for="manage[role_coupons]">
                                                            {{ trans('labels.view') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_coupons" name="add[role_coupons]"
                                                            id="add[role_coupons]">
                                                        <label class="form-label fs-13" for="add[role_coupons]">
                                                            {{ trans('labels.add') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_coupons" name="edit[role_coupons]"
                                                            id="edit[role_coupons]">
                                                        <label class="form-label fs-13" for="edit[role_coupons]">
                                                            {{ trans('labels.edit') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_coupons" name="delete[role_coupons]"
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
                                                        id="manage[role_top_deals]">
                                                    <label class="form-label fs-13" for="manage[role_top_deals]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-4">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_top_deals" name="add[role_top_deals]"
                                                        id="add[role_top_deals]">
                                                    <label class="form-label fs-13" for="add[role_top_deals]">
                                                        {{ trans('labels.add') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_top_deals" name="delete[role_top_deals]"
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
                                                        value="role_pricing_plans" name="modules[role_pricing_plans]"
                                                        id="manage[role_pricing_plans]">
                                                    <label class="form-label fs-13" for="manage[role_pricing_plans]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_pricing_plans" name="add[role_pricing_plans]"
                                                            id="add[role_pricing_plans]">
                                                        <label class="form-label fs-13" for="add[role_pricing_plans]">
                                                            {{ trans('labels.add') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_pricing_plans" name="edit[role_pricing_plans]"
                                                            id="edit[role_pricing_plans]">
                                                        <label class="form-label fs-13" for="edit[role_pricing_plans]">
                                                            {{ trans('labels.edit') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_pricing_plans" name="delete[role_pricing_plans]"
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
                                                    value="role_payment_methods" name="modules[role_payment_methods]"
                                                    id="manage[role_payment_methods]">
                                                <label class="form-label fs-13" for="manage[role_payment_methods]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_payment_methods" name="add[role_payment_methods]"
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
                                                        id="manage[role_payment_methods]">
                                                    <label class="form-label fs-13" for="manage[role_payment_methods]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_payment_methods" name="add[role_payment_methods]"
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
                                                <input class="form-check-input" type="checkbox" value="role_cities"
                                                    name="modules[role_cities]" id="manage[role_cities]">
                                                <label class="form-label fs-13" for="manage[role_cities]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_cities"
                                                    name="add[role_cities]" id="add[role_cities]">
                                                <label class="form-label fs-13" for="add[role_cities]">
                                                    {{ trans('labels.add') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_cities"
                                                    name="edit[role_cities]" id="edit[role_cities]">
                                                <label class="form-label fs-13" for="edit[role_cities]">
                                                    {{ trans('labels.edit') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_cities"
                                                    name="delete[role_cities]" id="delete[role_cities]">
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
                                                    name="modules[role_areas]" id="manage[role_areas]">
                                                <label class="form-label fs-13" for="manage[role_areas]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_areas"
                                                    name="add[role_areas]" id="add[role_areas]">
                                                <label class="form-label fs-13" for="add[role_areas]">
                                                    {{ trans('labels.add') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_areas"
                                                    name="edit[role_areas]" id="edit[role_areas]">
                                                <label class="form-label fs-13" for="edit[role_areas]">
                                                    {{ trans('labels.edit') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_areas"
                                                    name="delete[role_areas]" id="delete[role_areas]">
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
                                                    value="role_store_categories" name="modules[role_store_categories]"
                                                    id="manage[role_store_categories]">
                                                <label class="form-label fs-13" for="manage[role_store_categories]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_store_categories" name="add[role_store_categories]"
                                                    id="add[role_store_categories]">
                                                <label class="form-label fs-13" for="add[role_store_categories]">
                                                    {{ trans('labels.add') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_store_categories" name="edit[role_store_categories]"
                                                    id="edit[role_store_categories]">
                                                <label class="form-label fs-13" for="edit[role_store_categories]">
                                                    {{ trans('labels.edit') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_store_categories" name="delete[role_store_categories]"
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
                                                        value="role_custom_domains" name="modules[role_custom_domains]"
                                                        id="manage[role_custom_domains]">
                                                    <label class="form-label fs-13" for="manage[role_custom_domains]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_custom_domains" name="edit[role_custom_domains]"
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
                                                    id="manage[role_working_hours]">
                                                <label class="form-label fs-13" for="manage[role_working_hours]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_working_hours" name="edit[role_working_hours]"
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
                                                    name="modules[role_table]" id="manage[role_table]">
                                                <label class="form-label fs-13" for="manage[role_table]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_table"
                                                    name="add[role_table]" id="add[role_table]">
                                                <label class="form-label fs-13" for="add[role_table]">
                                                    {{ trans('labels.add') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_table"
                                                    name="edit[role_table]" id="edit[role_table]">
                                                <label class="form-label fs-13" for="edit[role_table]">
                                                    {{ trans('labels.edit') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_table"
                                                    name="delete[role_table]" id="delete[role_table]">
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
                                                        value="role_custom_status" name="modules[role_custom_status]"
                                                        id="manage[role_custom_status]">
                                                    <label class="form-label fs-13" for="manage[role_custom_status]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_custom_status" name="add[role_custom_status]"
                                                        id="add[role_custom_status]">
                                                    <label class="form-label fs-13" for="add[role_custom_status]">
                                                        {{ trans('labels.add') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_custom_status" name="edit[role_custom_status]"
                                                        id="edit[role_custom_status]">
                                                    <label class="form-label fs-13" for="edit[role_custom_status]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_custom_status" name="delete[role_custom_status]"
                                                        id="delete[role_custom_status]">
                                                    <label class="form-label fs-13" for="delete[role_custom_status]">
                                                        {{ trans('labels.delete') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if (@helper::checkaddons('subscription'))
                                        @php
                                            if (Auth::user()->type == 2 || Auth::user()->type == 4) {
                                                $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)
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
                                                            id="manage[role_custom_domains]">
                                                        <label class="form-label fs-13"
                                                            for="manage[role_custom_domains]">
                                                            {{ trans('labels.view') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_custom_domains" name="add[role_custom_domains]"
                                                            id="add[role_custom_domains]">
                                                        <label class="form-label fs-13" for="add[role_custom_domains]">
                                                            {{ trans('labels.add') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        @if (@helper::checkaddons('custom_domain'))
                                            <div class="d-block mb-3">
                                                <div class="row">
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_custom_domains"
                                                            name="modules[role_custom_domains]"
                                                            id="manage[role_custom_domains]">
                                                        <label class="form-label fs-13"
                                                            for="manage[role_custom_domains]">
                                                            {{ trans('labels.view') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_custom_domains" name="add[role_custom_domains]"
                                                            id="add[role_custom_domains]">
                                                        <label class="form-label fs-13" for="add[role_custom_domains]">
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
                                                id="manage[role_basic_settings]">
                                            <label class="form-label fs-13" for="manage[role_basic_settings]">
                                                {{ trans('labels.view') }}
                                            </label>
                                        </div>
                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                            <input class="form-check-input" type="checkbox"
                                                value="role_basic_settings" name="edit[role_basic_settings]"
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
                                                    id="manage[role_how_it_works]">
                                                <label class="form-label fs-13" for="manage[role_how_it_works]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_how_it_works" name="add[role_how_it_works]"
                                                    id="add[role_how_it_works]">
                                                <label class="form-label fs-13" for="add[role_how_it_works]">
                                                    {{ trans('labels.add') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_how_it_works" name="edit[role_how_it_works]"
                                                    id="edit[role_how_it_works]">
                                                <label class="form-label fs-13" for="edit[role_how_it_works]">
                                                    {{ trans('labels.edit') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_how_it_works" name="delete[role_how_it_works]"
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
                                                    id="manage[role_theme_images]">
                                                <label class="form-label fs-13" for="manage[role_theme_images]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_theme_images" name="add[role_theme_images]"
                                                    id="add[role_theme_images]">
                                                <label class="form-label fs-13" for="add[role_theme_images]">
                                                    {{ trans('labels.add') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_theme_images" name="edit[role_theme_images]"
                                                    id="edit[role_theme_images]">
                                                <label class="form-label fs-13" for="edit[role_theme_images]">
                                                    {{ trans('labels.edit') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_theme_images" name="delete[role_theme_images]"
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
                                                <input class="form-check-input" type="checkbox" value="role_features"
                                                    name="modules[role_features]" id="manage[role_features]">
                                                <label class="form-label fs-13" for="manage[role_features]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_features"
                                                    name="add[role_features]" id="add[role_features]">
                                                <label class="form-label fs-13" for="add[role_features]">
                                                    {{ trans('labels.add') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_features"
                                                    name="edit[role_features]" id="edit[role_features]">
                                                <label class="form-label fs-13" for="edit[role_features]">
                                                    {{ trans('labels.edit') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox" value="role_features"
                                                    name="delete[role_features]" id="delete[role_features]">
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
                                                    id="manage[role_promotional_banners]">
                                                <label class="form-label fs-13" for="manage[role_promotional_banners]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_promotional_banners"
                                                    name="add[role_promotional_banners]"
                                                    id="add[role_promotional_banners]">
                                                <label class="form-label fs-13" for="add[role_promotional_banners]">
                                                    {{ trans('labels.add') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_promotional_banners"
                                                    name="edit[role_promotional_banners]"
                                                    id="edit[role_promotional_banners]">
                                                <label class="form-label fs-13" for="edit[role_promotional_banners]">
                                                    {{ trans('labels.edit') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_promotional_banners"
                                                    name="delete[role_promotional_banners]"
                                                    id="delete[role_promotional_banners]">
                                                <label class="form-label fs-13" for="delete[role_promotional_banners]">
                                                    {{ trans('labels.delete') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @if (@helper::checkaddons('blog'))
                                        <div class="d-block mb-3">
                                            <div class="row">
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_blogs"
                                                        name="modules[role_blogs]" id="manage[role_blogs]">
                                                    <label class="form-label fs-13" for="manage[role_blogs]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_blogs"
                                                        name="add[role_blogs]" id="add[role_blogs]">
                                                    <label class="form-label fs-13" for="add[role_blogs]">
                                                        {{ trans('labels.add') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_blogs"
                                                        name="edit[role_blogs]" id="edit[role_blogs]">
                                                    <label class="form-label fs-13" for="edit[role_blogs]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_blogs"
                                                        name="delete[role_blogs]" id="delete[role_blogs]">
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
                                                    id="manage[role_who_we_are]">
                                                <label class="form-label fs-13" for="manage[role_who_we_are]">
                                                    {{ trans('labels.view') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_who_we_are" name="add[role_who_we_are]"
                                                    id="add[role_who_we_are]">
                                                <label class="form-label fs-13" for="add[role_who_we_are]">
                                                    {{ trans('labels.add') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_who_we_are" name="edit[role_who_we_are]"
                                                    id="edit[role_who_we_are]">
                                                <label class="form-label fs-13" for="edit[role_who_we_are]">
                                                    {{ trans('labels.edit') }}
                                                </label>
                                            </div>
                                            <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                <input class="form-check-input" type="checkbox"
                                                    value="role_who_we_are" name="delete[role_who_we_are]"
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
                                                                id="manage[role_blogs]">
                                                            <label class="form-label fs-13" for="manage[role_blogs]">
                                                                {{ trans('labels.view') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_blogs" name="add[role_blogs]"
                                                                id="add[role_blogs]">
                                                            <label class="form-label fs-13" for="add[role_blogs]">
                                                                {{ trans('labels.add') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_blogs" name="edit[role_blogs]"
                                                                id="edit[role_blogs]">
                                                            <label class="form-label fs-13" for="edit[role_blogs]">
                                                                {{ trans('labels.edit') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_blogs" name="delete[role_blogs]"
                                                                id="delete[role_blogs]">
                                                            <label class="form-label fs-13" for="delete[role_blogs]">
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
                                                            id="manage[role_blogs]">
                                                        <label class="form-label fs-13" for="manage[role_blogs]">
                                                            {{ trans('labels.view') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_blogs" name="add[role_blogs]"
                                                            id="add[role_blogs]">
                                                        <label class="form-label fs-13" for="add[role_blogs]">
                                                            {{ trans('labels.add') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_blogs" name="edit[role_blogs]"
                                                            id="edit[role_blogs]">
                                                        <label class="form-label fs-13" for="edit[role_blogs]">
                                                            {{ trans('labels.edit') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_blogs" name="delete[role_blogs]"
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
                                            <input class="form-check-input" type="checkbox" value="role_testimonials"
                                                name="modules[role_testimonials]" id="manage[role_testimonials]">
                                            <label class="form-label fs-13" for="manage[role_testimonials]">
                                                {{ trans('labels.view') }}
                                            </label>
                                        </div>
                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                            <input class="form-check-input" type="checkbox" value="role_testimonials"
                                                name="add[role_testimonials]" id="add[role_testimonials]">
                                            <label class="form-label fs-13" for="add[role_testimonials]">
                                                {{ trans('labels.add') }}
                                            </label>
                                        </div>
                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                            <input class="form-check-input" type="checkbox" value="role_testimonials"
                                                name="edit[role_testimonials]" id="edit[role_testimonials]">
                                            <label class="form-label fs-13" for="edit[role_testimonials]">
                                                {{ trans('labels.edit') }}
                                            </label>
                                        </div>
                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                            <input class="form-check-input" type="checkbox" value="role_testimonials"
                                                name="delete[role_testimonials]" id="delete[role_testimonials]">
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
                                                name="modules[role_faqs]" id="manage[role_faqs]">
                                            <label class="form-label fs-13" for="manage[role_faqs]">
                                                {{ trans('labels.view') }}
                                            </label>
                                        </div>
                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                            <input class="form-check-input" type="checkbox" value="role_faqs"
                                                name="add[role_faqs]" id="add[role_faqs]">
                                            <label class="form-label fs-13" for="add[role_faqs]">
                                                {{ trans('labels.add') }}
                                            </label>
                                        </div>
                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                            <input class="form-check-input" type="checkbox" value="role_faqs"
                                                name="edit[role_faqs]" id="edit[role_faqs]">
                                            <label class="form-label fs-13" for="edit[role_faqs]">
                                                {{ trans('labels.edit') }}
                                            </label>
                                        </div>
                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                            <input class="form-check-input" type="checkbox" value="role_faqs"
                                                name="delete[role_faqs]" id="delete[role_faqs]">
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
                                                name="modules[role_cms_pages]" id="manage[role_cms_pages]">
                                            <label class="form-label fs-13" for="manage[role_cms_pages]">
                                                {{ trans('labels.view') }}
                                            </label>
                                        </div>
                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                            <input class="form-check-input" type="checkbox" value="role_cms_pages"
                                                name="edit[role_cms_pages]" id="edit[role_cms_pages]">
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
                                                        id="manage[role_coupons]">
                                                    <label class="form-label fs-13" for="manage[role_coupons]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_coupons" name="add[role_coupons]"
                                                        id="add[role_coupons]">
                                                    <label class="form-label fs-13" for="add[role_coupons]">
                                                        {{ trans('labels.add') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_coupons" name="edit[role_coupons]"
                                                        id="edit[role_coupons]">
                                                    <label class="form-label fs-13" for="edit[role_coupons]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_coupons" name="delete[role_coupons]"
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
                                                    <input class="form-check-input" type="checkbox" value="role_roles"
                                                        name="modules[role_roles]" id="manage[role_roles]">
                                                    <label class="form-label fs-13" for="manage[role_roles]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_roles"
                                                        name="add[role_roles]" id="add[role_roles]">
                                                    <label class="form-label fs-13" for="add[role_roles]">
                                                        {{ trans('labels.add') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_roles"
                                                        name="edit[role_roles]" id="edit[role_roles]">
                                                    <label class="form-label fs-13" for="edit[role_roles]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox" value="role_roles"
                                                        name="delete[role_roles]" id="delete[role_roles]">
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
                                                        id="manage[role_employees]">
                                                    <label class="form-label fs-13" for="manage[role_employees]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_employees" name="add[role_employees]"
                                                        id="add[role_employees]">
                                                    <label class="form-label fs-13" for="add[role_employees]">
                                                        {{ trans('labels.add') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_employees" name="edit[role_employees]"
                                                        id="edit[role_employees]">
                                                    <label class="form-label fs-13" for="edit[role_employees]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_employees" name="delete[role_employees]"
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
                                                                id="manage[role_roles]">
                                                            <label class="form-label fs-13" for="manage[role_roles]">
                                                                {{ trans('labels.view') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_roles" name="add[role_roles]"
                                                                id="add[role_roles]">
                                                            <label class="form-label fs-13" for="add[role_roles]">
                                                                {{ trans('labels.add') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_roles" name="edit[role_roles]"
                                                                id="edit[role_roles]">
                                                            <label class="form-label fs-13" for="edit[role_roles]">
                                                                {{ trans('labels.edit') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_roles" name="delete[role_roles]"
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
                                                                id="manage[role_employees]">
                                                            <label class="form-label fs-13"
                                                                for="manage[role_employees]">
                                                                {{ trans('labels.view') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_employees" name="add[role_employees]"
                                                                id="add[role_employees]">
                                                            <label class="form-label fs-13" for="add[role_employees]">
                                                                {{ trans('labels.add') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_employees" name="edit[role_employees]"
                                                                id="edit[role_employees]">
                                                            <label class="form-label fs-13" for="edit[role_employees]">
                                                                {{ trans('labels.edit') }}
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="role_employees" name="delete[role_employees]"
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
                                                            id="manage[role_roles]">
                                                        <label class="form-label fs-13" for="manage[role_roles]">
                                                            {{ trans('labels.view') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_roles" name="add[role_roles]"
                                                            id="add[role_roles]">
                                                        <label class="form-label fs-13" for="add[role_roles]">
                                                            {{ trans('labels.add') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_roles" name="edit[role_roles]"
                                                            id="edit[role_roles]">
                                                        <label class="form-label fs-13" for="edit[role_roles]">
                                                            {{ trans('labels.edit') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_roles" name="delete[role_roles]"
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
                                                            id="manage[role_employees]">
                                                        <label class="form-label fs-13" for="manage[role_employees]">
                                                            {{ trans('labels.view') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_employees" name="add[role_employees]"
                                                            id="add[role_employees]">
                                                        <label class="form-label fs-13" for="add[role_employees]">
                                                            {{ trans('labels.add') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_employees" name="edit[role_employees]"
                                                            id="edit[role_employees]">
                                                        <label class="form-label fs-13" for="edit[role_employees]">
                                                            {{ trans('labels.edit') }}
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="role_employees" name="delete[role_employees]"
                                                            id="delete[role_employees]">
                                                        <label class="form-label fs-13" for="delete[role_employees]">
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
                                            <input class="form-check-input" type="checkbox" value="role_subscribers"
                                                name="modules[role_subscribers]" id="manage[role_subscribers]">
                                            <label class="form-label fs-13" for="manage[role_subscribers]">
                                                {{ trans('labels.view') }}
                                            </label>
                                        </div>
                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                            <input class="form-check-input" type="checkbox" value="role_subscribers"
                                                name="delete[role_subscribers]" id="delete[role_subscribers]">
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
                                                name="modules[role_inquiries]" id="manage[role_inquiries]">
                                            <label class="form-label fs-13" for="manage[role_inquiries]">
                                                {{ trans('labels.view') }}
                                            </label>
                                        </div>
                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                            <input class="form-check-input" type="checkbox" value="role_inquiries"
                                                name="edit[role_inquiries]" id="edit[role_inquiries]">
                                            <label class="form-label fs-13" for="edit[role_inquiries]">
                                                {{ trans('labels.edit') }}
                                            </label>
                                        </div>
                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                            <input class="form-check-input" type="checkbox" value="role_inquiries"
                                                name="delete[role_inquiries]" id="delete[role_inquiries]">
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
                                                        id="manage[role_product_inquiry]">
                                                    <label class="form-label fs-13" for="manage[role_product_inquiry]">
                                                        {{ trans('labels.view') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_product_inquiry" name="edit[role_product_inquiry]"
                                                        id="edit[role_product_inquiry]">
                                                    <label class="form-label fs-13" for="edit[role_product_inquiry]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_product_inquiry" name="delete[role_product_inquiry]"
                                                        id="delete[role_product_inquiry]">
                                                    <label class="form-label fs-13" for="delete[role_product_inquiry]">
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
                                                    name="modules[role_share]" id="manage[role_share]">
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
                                                        id="add[role_language_settings]">
                                                    <label class="form-label fs-13" for="add[role_language_settings]">
                                                        {{ trans('labels.add') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_language_settings"
                                                        name="edit[role_language_settings]"
                                                        id="edit[role_language_settings]">
                                                    <label class="form-label fs-13" for="edit[role_language_settings]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_language_settings"
                                                        name="delete[role_language_settings]"
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
                                                        id="add[role_currency_settings]">
                                                    <label class="form-label fs-13" for="add[role_currency_settings]">
                                                        {{ trans('labels.add') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_currency_settings"
                                                        name="edit[role_currency_settings]"
                                                        id="edit[role_currency_settings]">
                                                    <label class="form-label fs-13" for="edit[role_currency_settings]">
                                                        {{ trans('labels.edit') }}
                                                    </label>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="role_currency_settings"
                                                        name="delete[role_currency_settings]"
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
                                                name="modules[role_settings]" id="manage[role_settings]">
                                            <label class="form-label fs-13" for="manage[role_settings]">
                                                {{ trans('labels.view') }}
                                            </label>
                                        </div>
                                        <div class="col-xl-2 col-lg-3 col-md-3 col-6">
                                            <input class="form-check-input" type="checkbox" value="role_settings"
                                                name="edit[role_settings]" id="edit[role_settings]">
                                            <label class="form-label fs-13" for="edit[role_settings]">
                                                {{ trans('labels.edit') }}
                                            </label>
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
                                class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_roles', Auth::user()->role_id, Auth::user()->vendor_id, 'add') == 1 ? '' : 'd-none') : '' }}"
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
        $('#checkall').on('click', function() {
            "use strict";
            var checked = $(this).prop('checked');
            $('input:checkbox').prop('checked', checked);
        }).change();

        $('#checkboxes input:checkbox').on('click', function() {

            var checked = $(this).prop('checked');
            var manageid = "manage[role_" + this.id + "]";
            var addid = "add[role_" + this.id + "]";
            var editid = "edit[role_" + this.id + "]";
            var deleteid = "delete[role_" + this.id + "]";
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
        });
    </script>
@endsection
