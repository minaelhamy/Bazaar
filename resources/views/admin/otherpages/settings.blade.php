@extends('admin.layout.default')
@php
    if (Auth::user()->type == 4) {
        $vendor_id = Auth::user()->vendor_id;
    } else {
        $vendor_id = Auth::user()->id;
    }
    $user = App\Models\User::where('id', $vendor_id)->first();
@endphp

@section('content')
    <h5 class="text-capitalize fw-600 text-dark fs-4">
        {{ trans('labels.settings') }}</h5>
    <div class="row settings mt-3">
        <div class="col-xl-3 mb-3">
            <div class="card card-sticky-top border-0">
                <ul class="list-group list-options">
                    <a href="#basicinfo" data-tab="basicinfo"
                        class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center active"
                        aria-current="true">{{ trans('labels.basic_info') }}
                        <i class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                    </a>
                    @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                        @if (count(@helper::checkthemeaddons('theme_')) > 0)
                            <a href="#theme_images" data-tab="theme_images"
                                class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                aria-current="true">{{ trans('labels.theme') }} {{ trans('labels.images') }}
                                <i
                                    class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                            </a>
                        @endif
                    @endif
                    <a href="#editprofile" data-tab="editprofile"
                        class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                        aria-current="true">{{ trans('labels.edit_profile') }}
                        <i class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                    </a>
                    <a href="#changepasssword" data-tab="changepasssword"
                        class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                        aria-current="true">{{ trans('labels.change_password') }}
                        <i class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                    </a>

                    @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                        @if (@helper::checkaddons('whatsapp_message'))
                            <a href="#whatsappmessagesettings" data-tab="whatsappmessagesettings"
                                class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                aria-current="true">{{ trans('labels.whatsapp_message_settings') }}
                                <div class="d-flex align-items-center gap-1 justify-content-between">
                                    @if (env('Environment') == 'sendbox')
                                        <span class="badge badge bg-danger">{{ trans('labels.addon') }}</span>
                                    @endif
                                    <i
                                        class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                                </div>
                            </a>
                        @endif
                        @if (@helper::checkaddons('google_recaptcha'))
                            <a href="#recaptcha" data-tab="recaptcha"
                                class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                aria-current="true">{{ trans('labels.google_recaptcha') }}
                                <div class="d-flex align-items-center gap-1 justify-content-between">
                                    @if (env('Environment') == 'sendbox')
                                        <span class="badge badge bg-danger">{{ trans('labels.addon') }}</span>
                                    @endif
                                    <i
                                        class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                                </div>
                            </a>
                        @endif
                    @endif
                    @if (@helper::checkaddons('email_settings'))
                        <a href="#email_settings" data-tab="email_settings"
                            class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                            aria-current="true">{{ trans('labels.email_settings') }}
                            <div class="d-flex align-items-center gap-1 justify-content-between">
                                @if (env('Environment') == 'sendbox')
                                    <span class="badge badge bg-danger">{{ trans('labels.addon') }}</span>
                                @endif
                                <i
                                    class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                            </div>
                        </a>
                        <a href="#email_template" data-tab="email_template"
                            class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                            aria-current="true">{{ trans('labels.email_message_settings') }}
                            <div class="d-flex align-items-center gap-1 justify-content-between">
                                @if (env('Environment') == 'sendbox')
                                    <span class="badge badge bg-danger">{{ trans('labels.addon') }}</span>
                                @endif
                                <i
                                    class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                            </div>
                        </a>
                    @endif

                    @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                        @if (@helper::checkaddons('tawk_addons'))
                            <a href="#tawk_settings" data-tab="tawk_settings"
                                class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                aria-current="true">{{ trans('labels.tawk_settings') }}
                                <div class="d-flex align-items-center gap-1 justify-content-between">
                                    @if (env('Environment') == 'sendbox')
                                        <span class="badge badge bg-danger">{{ trans('labels.addon') }}</span>
                                    @endif
                                    <i
                                        class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                                </div>
                            </a>
                        @endif
                        @if (@helper::checkaddons('quick_call'))
                            <a href="#quick_call" data-tab="quick_call"
                                class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                aria-current="true">{{ trans('labels.quick_call') }}
                                <div class="d-flex align-items-center gap-1 justify-content-between">
                                    @if (env('Environment') == 'sendbox')
                                        <span class="badge badge bg-danger">{{ trans('labels.addon') }}</span>
                                    @endif
                                    <i
                                        class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                                </div>
                            </a>
                        @endif
                    @endif
                    @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                        @if (@helper::checkaddons('wizz_chat'))
                            <a href="#wizz_chat_settings" data-tab="wizz_chat_settings"
                                class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                aria-current="true">{{ trans('labels.wizz_chat_settings') }}
                                <div class="d-flex align-items-center gap-1 justify-content-between">
                                    @if (env('Environment') == 'sendbox')
                                        <span class="badge badge bg-danger">{{ trans('labels.addon') }}</span>
                                    @endif
                                    <i
                                        class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                                </div>
                            </a>
                        @endif
                    @endif
                    @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                        @if (@helper::checkaddons('subscription'))
                            @if (@helper::checkaddons('pixel'))
                                @php
                                    $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)
                                        ->orderByDesc('id')
                                        ->first();

                                    if (@$user->allow_without_subscription == 1) {
                                        $pixel = 1;
                                    } else {
                                        $pixel = @$checkplan->pixel;
                                    }
                                @endphp
                                @if ($pixel == 1)
                                    <a href="#pixel_settings" data-tab="pixel_settings"
                                        class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                        aria-current="true">{{ trans('labels.pixel_settings') }}
                                        <div class="d-flex align-items-center gap-1 justify-content-between">
                                            @if (env('Environment') == 'sendbox')
                                                <span class="badge badge bg-danger">{{ trans('labels.addon') }}</span>
                                            @endif
                                            <i
                                                class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                                        </div>
                                    </a>
                                @endif
                            @endif
                        @else
                            @if (@helper::checkaddons('pixel'))
                                <a href="#pixel_settings" data-tab="pixel_settings"
                                    class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                    aria-current="true">{{ trans('labels.pixel_settings') }}
                                    <div class="d-flex align-items-center gap-1 justify-content-between">
                                        @if (env('Environment') == 'sendbox')
                                            <span class="badge badge bg-danger">{{ trans('labels.addon') }}</span>
                                        @endif
                                        <i
                                            class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                                    </div>
                                </a>
                            @endif
                        @endif
                        @if (@helper::checkaddons('shopify'))
                            <a href="#shopify_settings" data-tab="shopify_settings"
                                class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                aria-current="true">{{ trans('labels.shopify_settings') }}
                                <div class="d-flex align-items-center gap-1 justify-content-between">
                                    @if (env('Environment') == 'sendbox')
                                        <span class="badge badge bg-danger">{{ trans('labels.addon') }}</span>
                                    @endif
                                    <i
                                        class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                                </div>
                            </a>
                        @endif
                        @if (@helper::checkaddons('tawk_addons'))
                            <a href="#tawk_settings" data-tab="tawk_settings"
                                class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                aria-current="true">{{ trans('labels.tawk_settings') }}
                                <div class="d-flex align-items-center gap-1 justify-content-between">
                                    @if (env('Environment') == 'sendbox')
                                        <span class="badge badge bg-danger">{{ trans('labels.addon') }}</span>
                                    @endif
                                    <i
                                        class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                                </div>
                            </a>
                        @endif
                        @if (@helper::checkaddons('quick_call'))
                            <a href="#quick_call" data-tab="quick_call"
                                class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                aria-current="true">{{ trans('labels.quick_call') }}
                                <div class="d-flex align-items-center gap-1 justify-content-between">
                                    @if (env('Environment') == 'sendbox')
                                        <span class="badge badge bg-danger">{{ trans('labels.addon') }}</span>
                                    @endif
                                    <i
                                        class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                                </div>
                            </a>
                        @endif
                        @if (@helper::checkaddons('sales_notification'))
                            <a href="#fake_sales_notification" data-tab="fake_sales_notification"
                                class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                aria-current="true">{{ trans('labels.fake_sales_notification') }}
                                <div class="d-flex align-items-center gap-1 justify-content-between">
                                    @if (env('Environment') == 'sendbox')
                                        <span class="badge badge bg-danger">{{ trans('labels.addon') }}</span>
                                    @endif
                                    <i
                                        class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                                </div>
                            </a>
                        @endif
                        @if (@helper::checkaddons('fake_view'))
                            <a href="#product_fake_view" data-tab="product_fake_view"
                                class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                aria-current="true">{{ trans('labels.product_fake_view') }}
                                <div class="d-flex align-items-center gap-1 justify-content-between">
                                    @if (env('Environment') == 'sendbox')
                                        <span class="badge badge bg-danger">{{ trans('labels.addon') }}</span>
                                    @endif
                                    <i
                                        class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                                </div>
                            </a>
                        @endif
                        @if (@helper::checkaddons('wizz_chat'))
                            <a href="#wizz_chat_settings" data-tab="wizz_chat_settings"
                                class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                aria-current="true">{{ trans('labels.wizz_chat_settings') }}
                                <div class="d-flex align-items-center gap-1 justify-content-between">
                                    @if (env('Environment') == 'sendbox')
                                        <span class="badge badge bg-danger">{{ trans('labels.addon') }}</span>
                                    @endif
                                    <i
                                        class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                                </div>
                            </a>
                        @endif
                        @if (@helper::checkaddons('google_login'))
                            <a href="#google_login_settings" data-tab="google_login_settings"
                                class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                aria-current="true">{{ trans('labels.google_login') }}
                                <div class="d-flex align-items-center gap-1 justify-content-between">
                                    @if (env('Environment') == 'sendbox')
                                        <span class="badge badge bg-danger">{{ trans('labels.addon') }}</span>
                                    @endif
                                    <i
                                        class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                                </div>
                            </a>
                        @endif
                        @if (@helper::checkaddons('facebook_login'))
                            <a href="#facebook_login_settings" data-tab="facebook_login_settings"
                                class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                aria-current="true">{{ trans('labels.facebook_login') }}
                                <div class="d-flex align-items-center gap-1 justify-content-between">
                                    @if (env('Environment') == 'sendbox')
                                        <span class="badge badge bg-danger">{{ trans('labels.addon') }}</span>
                                    @endif
                                    <i
                                        class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                                </div>
                            </a>
                        @endif
                        @if (@helper::checkaddons('trusted_badges'))
                            <a href="#trusted_badges" data-tab="trusted_badges"
                                class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                aria-current="true">{{ trans('labels.trusted_badges') }}
                                <div class="d-flex align-items-center gap-1 justify-content-between">
                                    @if (env('Environment') == 'sendbox')
                                        <span class="badge badge bg-danger">{{ trans('labels.addon') }}</span>
                                    @endif
                                    <i
                                        class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                                </div>
                            </a>
                        @endif
                        @if (@helper::checkaddons('safe_secure_checkout'))
                            <a href="#safe_secure" data-tab="safe_secure"
                                class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                aria-current="true">{{ trans('labels.safe_secure') }}
                                <div class="d-flex align-items-center gap-1 justify-content-between">
                                    @if (env('Environment') == 'sendbox')
                                        <span class="badge badge bg-danger">{{ trans('labels.addon') }}</span>
                                    @endif
                                    <i
                                        class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                                </div>
                            </a>
                        @endif
                        @if (@helper::checkaddons('cart_checkout_countdown'))
                            <a href="#cart_checkout_countdown" data-tab="cart_checkout_countdown"
                                class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                aria-current="true">{{ trans('labels.cart_checkout_countdown') }}
                                <div class="d-flex align-items-center gap-1 justify-content-between">
                                    @if (env('Environment') == 'sendbox')
                                        <span class="badge badge bg-danger">{{ trans('labels.addon') }}</span>
                                    @endif
                                    <i
                                        class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                                </div>
                            </a>
                        @endif
                        @if (@helper::checkaddons('cart_checkout_progressbar'))
                            <a href="#cart_checkout_progressbar" data-tab="cart_checkout_progressbar"
                                class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                aria-current="true">{{ trans('labels.cart_checkout_progressbar') }}
                                <div class="d-flex align-items-center gap-1 justify-content-between">
                                    @if (env('Environment') == 'sendbox')
                                        <span class="badge badge bg-danger">{{ trans('labels.addon') }}</span>
                                    @endif
                                    <i
                                        class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                                </div>
                            </a>
                        @endif

                        @if (@helper::checkaddons('recent_view_product'))
                            <a href="#recent_view_product" data-tab="recent_view_product"
                                class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                aria-current="true">{{ trans('labels.recent_view_product') }}
                                <div class="d-flex gap-2 align-items-center">
                                    @if (env('Environment') == 'sendbox')
                                        <span class="badge badge bg-danger">{{ trans('labels.addon') }}</span>
                                    @endif
                                    <i
                                        class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                                </div>
                            </a>
                        @endif

                        <a href="#estimated_delivery" data-tab="estimated_delivery"
                            class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                            aria-current="true"> {{ trans('labels.estimated_delivery') }}
                            <i
                                class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                        </a>
                        <a href="#delete_profile" data-tab="delete_profile"
                            class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                            aria-current="true">{{ trans('labels.delete_profile') }}
                            <i
                                class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                        </a>
                    @endif
                    <a href="#maintenance_mode" data-tab="maintenance_mode"
                        class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                        aria-current="true">{{ trans('labels.maintenance_mode') }}
                        <i class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                    </a>

                    @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                        <a href="#notice_mode" data-tab="notice_mode"
                            class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                            aria-current="true">{{ trans('labels.notice') }}
                            <i
                                class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                        </a>
                    @endif
                    @if (@helper::checkaddons('ai_settings'))
                        <a href="#ai_settings" data-tab="ai_settings"
                            class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                            aria-current="true">{{ trans('labels.ai_settings') }}
                            <div class="d-flex gap-2 align-items-center">
                                @if (env('Environment') == 'sendbox')
                                    <span class="badge badge bg-danger">{{ trans('labels.addon') }}</span>
                                @endif
                                <i
                                    class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                            </div>
                        </a>
                    @endif
                </ul>
            </div>
        </div>
        <div class="col-xl-9">
            <div id="settingmenuContent">
                <div id="basicinfo">
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="card border-0 box-shadow">
                                <div class="card-header p-3 bg-secondary">
                                    <h5 class="text-capitalize fw-600 settings-color">
                                        {{ trans('labels.basic_info') }}
                                    </h5>
                                </div>
                                <div class="card-body pb-0">
                                    <form action="{{ URL::to('admin/settings/update') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                                                <div class="form-group col-sm-6">
                                                    <label class="form-label">{{ trans('labels.primary_color') }}</label>
                                                    <input type="color"
                                                        class="form-control form-control-color w-100 border-0"
                                                        name="primary_color" value="{{ $settingdata->primary_color }}">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label
                                                        class="form-label">{{ trans('labels.secondary_color') }}</label>
                                                    <input type="color"
                                                        class="form-control form-control-color w-100 border-0"
                                                        name="secondary_color"
                                                        value="{{ $settingdata->secondary_color }}">
                                                </div>
                                            @endif
                                        </div>
                                        <div class="row">
                                            @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                                                <div class="col-md-6 form-group">
                                                    <label class="form-label"
                                                        for="">{{ trans('labels.vendor_register') }}
                                                    </label>
                                                    <input id="vendor_register-switch" type="checkbox"
                                                        class="checkbox-switch" name="vendor_register" value="1"
                                                        {{ $settingdata->vendor_register == 1 ? 'checked' : '' }}>
                                                    <label for="vendor_register-switch" class="switch">
                                                        <span
                                                            class="{{ session()->get('direction') == 2 ? 'switch__circle-rtl' : 'switch__circle' }}"><span
                                                                class="switch__circle-inner"></span></span>
                                                        <span
                                                            class="switch__left {{ session()->get('direction') == 2 ? 'pe-2' : 'ps-2' }}">{{ trans('labels.off') }}</span>
                                                        <span
                                                            class="switch__right {{ session()->get('direction') == 2 ? 'ps-2' : 'pe-2' }}">{{ trans('labels.on') }}</span>
                                                    </label>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label class="form-label"
                                                        for="">{{ trans('labels.image_size') }}<span
                                                            class="text-danger"> * </span>
                                                    </label>
                                                    <input type="text" step="any"
                                                        class="form-control numbers_only" name="image_size"
                                                        value="{{ @$settingdata->image_size }}"
                                                        placeholder="{{ trans('labels.image_size') }}" required>
                                                </div>
                                            @endif
                                            @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                                @if (@helper::checkaddons('customer_login'))
                                                    <div class="col-md-3" id="checkout_login_required">
                                                        <label class="form-label"
                                                            for="">{{ trans('labels.checkout_login_required') }}
                                                        </label>
                                                        @if (env('Environment') == 'sendbox')
                                                            <span
                                                                class="badge badge bg-danger ms-2 mb-0">{{ trans('labels.addon') }}</span>
                                                        @endif
                                                        <input id="checkout_login_required-switch" type="checkbox"
                                                            class="checkbox-switch" name="checkout_login_required"
                                                            value="1"
                                                            {{ $settingdata->checkout_login_required == 1 ? 'checked' : '' }}>
                                                        <label for="checkout_login_required-switch" class="switch">
                                                            <span
                                                                class="{{ session()->get('direction') == 2 ? 'switch__circle-rtl' : 'switch__circle' }}"><span
                                                                    class="switch__circle-inner"></span></span>
                                                            <span
                                                                class="switch__left {{ session()->get('direction') == 2 ? 'pe-2' : 'ps-2' }}">{{ trans('labels.off') }}</span>
                                                            <span
                                                                class="switch__right {{ session()->get('direction') == 2 ? 'ps-2' : 'pe-2' }}">{{ trans('labels.on') }}</span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3 {{ $settingdata->checkout_login_required == 1 ? '' : 'd-none' }}"
                                                        id="is_checkout_login_required">
                                                        <label class="form-label"
                                                            for="">{{ trans('labels.is_checkout_login_required') }}
                                                        </label>
                                                        @if (env('Environment') == 'sendbox')
                                                            <span
                                                                class="badge badge bg-danger ms-2 mb-0">{{ trans('labels.addon') }}</span>
                                                        @endif
                                                        <input id="is_checkout_login_required-switch" type="checkbox"
                                                            class="checkbox-switch" name="is_checkout_login_required"
                                                            value="1"
                                                            {{ $settingdata->is_checkout_login_required == 1 ? 'checked' : '' }}>
                                                        <label for="is_checkout_login_required-switch" class="switch">
                                                            <span
                                                                class="{{ session()->get('direction') == 2 ? 'switch__circle-rtl' : 'switch__circle' }}"><span
                                                                    class="switch__circle-inner"></span></span>
                                                            <span
                                                                class="switch__left {{ session()->get('direction') == 2 ? 'pe-2' : 'ps-2' }}">{{ trans('labels.off') }}</span>
                                                            <span
                                                                class="switch__right {{ session()->get('direction') == 2 ? 'ps-2' : 'pe-2' }}">{{ trans('labels.on') }}</span>
                                                        </label>
                                                    </div>
                                                @endif
                                            @endif
                                            @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                                <div class="col-md-3 form-group">
                                                    <label
                                                        class="form-label">{{ trans('labels.order_prefix_number') }}<span
                                                            class="text-danger"> * </span></label>
                                                    <input type="text" class="form-control w-10" name="order_prefix"
                                                        value="{{ @$settingdata->order_prefix }}"
                                                        placeholder="{{ trans('labels.order_prefix_number') }}"
                                                        onkeyup="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')"
                                                        required>
                                                </div>
                                                @if ($order->count() == 0)
                                                    <div class="col-md-3 form-group">
                                                        <label
                                                            class="form-label">{{ trans('labels.order_number_start') }}<span
                                                                class="text-danger"> * </span></label>
                                                        <input type="number" class="form-control numbers_only"
                                                            name="order_number_start"
                                                            value="{{ @$settingdata->order_number_start }}"
                                                            placeholder="{{ trans('labels.order_number_start') }}"
                                                            onkeyup="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')"
                                                            required>
                                                    </div>
                                                @endif
                                                <div class="form-group col-sm-4">
                                                    <label
                                                        class="form-label">{{ trans('labels.min_order_amount') }}</label>
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ trans('labels.min_order_amount') }}"
                                                        name="min_order_amount"
                                                        value="{{ @$settingdata->min_order_amount }}">
                                                </div>
                                            @endif
                                            <div
                                                class="{{ env('Environment') == 'sendbox' ? 'col-md-6' : 'col-md-6' }} form-group">
                                                <label class="form-label"
                                                    for="">{{ trans('labels.time_format') }}
                                                </label>
                                                <select class="form-select" name="time_format">
                                                    <option value="2"
                                                        {{ $settingdata->time_format == 2 ? 'selected' : '' }}>12
                                                        {{ trans('labels.hour') }}</option>
                                                    <option value="1"
                                                        {{ $settingdata->time_format == 1 ? 'selected' : '' }}>24
                                                        {{ trans('labels.hour') }}</option>
                                                </select>
                                            </div>
                                            <div
                                                class="{{ env('Environment') == 'sendbox' ? 'col-md-6' : 'col-md-6' }} form-group">
                                                <label class="form-label"
                                                    for="">{{ trans('labels.date_format') }}
                                                </label>

                                                <select class="form-select" name="date_format">
                                                    <option value="d M, Y"
                                                        {{ $settingdata->date_format == 'd M, Y' ? 'selected' : '' }}>dd
                                                        MMM, yyyy</option>
                                                    <option value="M d, Y"
                                                        {{ $settingdata->date_format == 'M d, Y' ? 'selected' : '' }}>MMM
                                                        dd, yyyy</option>
                                                    <option value="d-m-Y"
                                                        {{ $settingdata->date_format == 'd-m-Y' ? 'selected' : '' }}>
                                                        dd-MM-yyyy</option>
                                                    <option value="m-d-Y"
                                                        {{ $settingdata->date_format == 'm-d-Y' ? 'selected' : '' }}>
                                                        MM-dd-yyyy</option>
                                                    <option value="d/m/Y"
                                                        {{ $settingdata->date_format == 'd/m/Y' ? 'selected' : '' }}>
                                                        dd/MM/yyyy</option>
                                                    <option value="m/d/Y"
                                                        {{ $settingdata->date_format == 'm/d/Y' ? 'selected' : '' }}>
                                                        MM/dd/yyyy</option>
                                                    <option value="Y/m/d"
                                                        {{ $settingdata->date_format == 'Y/m/d' ? 'selected' : '' }}>
                                                        yyyy/MM/dd</option>
                                                    <option value="Y-m-d"
                                                        {{ $settingdata->date_format == 'Y-m-d' ? 'selected' : '' }}>
                                                        yyyy-MM-dd</option>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">{{ trans('labels.time_zone') }}</label>
                                                    <select class="form-select" name="timezone">
                                                        <option
                                                            {{ @$settingdata->timezone == 'Pacific/Midway' ? 'selected' : '' }}
                                                            value="Pacific/Midway">(GMT-11:00) Midway Island, Samoa
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/Adak' ? 'selected' : '' }}
                                                            value="America/Adak">(GMT-10:00) Hawaii-Aleutian
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Etc/GMT+10' ? 'selected' : '' }}
                                                            value="Etc/GMT+10">(GMT-10:00) Hawaii</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Pacific/Marquesas' ? 'selected' : '' }}
                                                            value="Pacific/Marquesas">(GMT-09:30) Marquesas Islands
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Pacific/Gambier' ? 'selected' : '' }}
                                                            value="Pacific/Gambier">(GMT-09:00) Gambier Islands
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/Anchorage' ? 'selected' : '' }}
                                                            value="America/Anchorage">(GMT-09:00) Alaska</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/Ensenada' ? 'selected' : '' }}
                                                            value="America/Ensenada">(GMT-08:00) Tijuana, Baja
                                                            California </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Etc/GMT+8' ? 'selected' : '' }}
                                                            value="Etc/GMT+8">(GMT-08:00) Pitcairn Islands</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/Los_Angeles' ? 'selected' : '' }}
                                                            value="America/Los_Angeles">(GMT-08:00) Pacific Time
                                                            (US
                                                            &amp; Canada) </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/Denver' ? 'selected' : '' }}
                                                            value="America/Denver">(GMT-07:00) Mountain Time (US
                                                            &amp;
                                                            Canada) </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/Chihuahua' ? 'selected' : '' }}
                                                            value="America/Chihuahua">(GMT-07:00) Chihuahua, La
                                                            Paz,
                                                            Mazatlan </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/Dawson_Creek' ? 'selected' : '' }}
                                                            value="America/Dawson_Creek">(GMT-07:00) Arizona
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/Belize' ? 'selected' : '' }}
                                                            value="America/Belize">(GMT-06:00) Saskatchewan,
                                                            Central
                                                            America </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/Cancun' ? 'selected' : '' }}
                                                            value="America/Cancun">(GMT-06:00) Guadalajara, Mexico
                                                            City,
                                                            Monterrey </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Chile/EasterIsland' ? 'selected' : '' }}
                                                            value="Chile/EasterIsland">(GMT-06:00) Easter Island
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/Chicago' ? 'selected' : '' }}
                                                            value="America/Chicago">(GMT-06:00) Central Time (US
                                                            &amp;
                                                            Canada) </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/New_York' ? 'selected' : '' }}
                                                            value="America/New_York">(GMT-05:00) Eastern Time (US
                                                            &amp;
                                                            Canada) </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/Havana' ? 'selected' : '' }}
                                                            value="America/Havana">(GMT-05:00) Cuba</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/Bogota' ? 'selected' : '' }}
                                                            value="America/Bogota">(GMT-05:00) Bogota, Lima, Quito,
                                                            Rio
                                                            Branco </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/Caracas' ? 'selected' : '' }}
                                                            value="America/Caracas">(GMT-04:30) Caracas</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/Santiago' ? 'selected' : '' }}
                                                            value="America/Santiago">(GMT-04:00) Santiago</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/La_Paz' ? 'selected' : '' }}
                                                            value="America/La_Paz">(GMT-04:00) La Paz</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Atlantic/Stanley' ? 'selected' : '' }}
                                                            value="Atlantic/Stanley">(GMT-04:00) Faukland Islands
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/Campo_Grande' ? 'selected' : '' }}
                                                            value="America/Campo_Grande">(GMT-04:00) Brazil
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/Goose_Bay' ? 'selected' : '' }}
                                                            value="America/Goose_Bay">(GMT-04:00) Atlantic Time
                                                            (Goose
                                                            Bay) </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/Glace_Bay' ? 'selected' : '' }}
                                                            value="America/Glace_Bay">(GMT-04:00) Atlantic Time
                                                            (Canada) </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/St_Johns' ? 'selected' : '' }}
                                                            value="America/St_Johns">(GMT-03:30) Newfoundland
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/Araguaina' ? 'selected' : '' }}
                                                            value="America/Araguaina">(GMT-03:00) UTC-3</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/Montevideo' ? 'selected' : '' }}
                                                            value="America/Montevideo">(GMT-03:00) Montevideo
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/Miquelon' ? 'selected' : '' }}
                                                            value="America/Miquelon">(GMT-03:00) Miquelon, St.
                                                            Pierre
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/Godthab' ? 'selected' : '' }}
                                                            value="America/Godthab">(GMT-03:00) Greenland</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/Argentina' ? 'selected' : '' }}
                                                            value="America/Argentina/Buenos_Aires">(GMT-03:00)
                                                            Buenos
                                                            Aires </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/Sao_Paulo' ? 'selected' : '' }}
                                                            value="America/Sao_Paulo">(GMT-03:00) Brasilia</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'America/Noronha' ? 'selected' : '' }}
                                                            value="America/Noronha">(GMT-02:00) Mid-Atlantic
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Atlantic/Cape_Verde' ? 'selected' : '' }}
                                                            value="Atlantic/Cape_Verde">(GMT-01:00) Cape Verde Is.
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Atlantic/Azores' ? 'selected' : '' }}
                                                            value="Atlantic/Azores">(GMT-01:00) Azores</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Europe/Belfast' ? 'selected' : '' }}
                                                            value="Europe/Belfast">(GMT) Greenwich Mean Time :
                                                            Belfast
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Europe/Dublin' ? 'selected' : '' }}
                                                            value="Europe/Dublin">(GMT) Greenwich Mean Time :
                                                            Dublin
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Europe/Lisbon' ? 'selected' : '' }}
                                                            value="Europe/Lisbon">(GMT) Greenwich Mean Time :
                                                            Lisbon
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Europe/London' ? 'selected' : '' }}
                                                            value="Europe/London">(GMT) Greenwich Mean Time :
                                                            London
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Africa/Abidjan' ? 'selected' : '' }}
                                                            value="Africa/Abidjan">(GMT) Monrovia, Reykjavik
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Europe/Amsterdam' ? 'selected' : '' }}
                                                            value="Europe/Amsterdam">(GMT+01:00) Amsterdam, Berlin,
                                                            Bern, Rome, Stockholm, Vienna</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Europe/Belgrade' ? 'selected' : '' }}
                                                            value="Europe/Belgrade">(GMT+01:00) Belgrade,
                                                            Bratislava,
                                                            Budapest, Ljubljana, Prague</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Europe/Brussels' ? 'selected' : '' }}
                                                            value="Europe/Brussels">(GMT+01:00) Brussels,
                                                            Copenhagen,
                                                            Madrid, Paris </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Africa/Algiers' ? 'selected' : '' }}
                                                            value="Africa/Algiers">(GMT+01:00) West Central Africa
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Africa/Windhoek' ? 'selected' : '' }}
                                                            value="Africa/Windhoek">(GMT+01:00) Windhoek</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Asia/Beirut' ? 'selected' : '' }}
                                                            value="Asia/Beirut">(GMT+02:00) Beirut</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Africa/Cairo' ? 'selected' : '' }}
                                                            value="Africa/Cairo">(GMT+02:00) Cairo</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Asia/Gaza' ? 'selected' : '' }}
                                                            value="Asia/Gaza">(GMT+02:00) Gaza</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Africa/Blantyre' ? 'selected' : '' }}
                                                            value="Africa/Blantyre">(GMT+02:00) Harare, Pretoria
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Asia/Jerusalem' ? 'selected' : '' }}
                                                            value="Asia/Jerusalem">(GMT+02:00) Jerusalem</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Europe/Minsk' ? 'selected' : '' }}
                                                            value="Europe/Minsk">(GMT+02:00) Minsk</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Asia/Damascus' ? 'selected' : '' }}
                                                            value="Asia/Damascus">(GMT+02:00) Syria</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Europe/Moscow' ? 'selected' : '' }}
                                                            value="Europe/Moscow">(GMT+03:00) Moscow, St.
                                                            Petersburg,
                                                            Volgograd </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Africa/Addis_Ababa' ? 'selected' : '' }}
                                                            value="Africa/Addis_Ababa">(GMT+03:00) Nairobi</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Asia/Tehran' ? 'selected' : '' }}
                                                            value="Asia/Tehran">(GMT+03:30) Tehran</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Asia/Dubai' ? 'selected' : '' }}
                                                            value="Asia/Dubai">(GMT+04:00) Abu Dhabi, Muscat
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Asia/Yerevan' ? 'selected' : '' }}
                                                            value="Asia/Yerevan">(GMT+04:00) Yerevan</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Asia/Kabul' ? 'selected' : '' }}
                                                            value="Asia/Kabul">(GMT+04:30) Kabul</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Asia/Yekaterinburg' ? 'selected' : '' }}
                                                            value="Asia/Yekaterinburg">(GMT+05:00) Ekaterinburg
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Asia/Tashkent' ? 'selected' : '' }}
                                                            value="Asia/Tashkent"> (GMT+05:00) Tashkent</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Asia/Kolkata' ? 'selected' : '' }}
                                                            value="Asia/Kolkata"> (GMT+05:30) Chennai, Kolkata,
                                                            Mumbai,
                                                            New Delhi</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Asia/Katmandu' ? 'selected' : '' }}
                                                            value="Asia/Katmandu">(GMT+05:45) Kathmandu</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Asia/Dhaka' ? 'selected' : '' }}
                                                            value="Asia/Dhaka">(GMT+06:00) Astana, Dhaka</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Asia/Novosibirsk' ? 'selected' : '' }}
                                                            value="Asia/Novosibirsk">(GMT+06:00) Novosibirsk
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Asia/Rangoon' ? 'selected' : '' }}
                                                            value="Asia/Rangoon">(GMT+06:30) Yangon (Rangoon)
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Asia/Bangkok' ? 'selected' : '' }}
                                                            value="Asia/Bangkok">(GMT+07:00) Bangkok, Hanoi,
                                                            Jakarta
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Asia/Kuala_Lumpur' ? 'selected' : '' }}
                                                            value="Asia/Kuala_Lumpur">(GMT+08:00) Kuala Lumpur
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Asia/Krasnoyarsk' ? 'selected' : '' }}
                                                            value="Asia/Krasnoyarsk">(GMT+07:00) Krasnoyarsk
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Asia/Hong_Kong' ? 'selected' : '' }}
                                                            value="Asia/Hong_Kong">(GMT+08:00) Beijing, Chongqing,
                                                            Hong
                                                            Kong, Urumqi</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Asia/Irkutsk' ? 'selected' : '' }}
                                                            value="Asia/Irkutsk">(GMT+08:00) Irkutsk, Ulaan Bataar
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Australia/Perth' ? 'selected' : '' }}
                                                            value="Australia/Perth">(GMT+08:00) Perth</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Australia/Eucla' ? 'selected' : '' }}
                                                            value="Australia/Eucla">(GMT+08:45) Eucla</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Asia/Tokyo' ? 'selected' : '' }}
                                                            value="Asia/Tokyo">(GMT+09:00) Osaka, Sapporo, Tokyo
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Asia/Seoul' ? 'selected' : '' }}
                                                            value="Asia/Seoul">(GMT+09:00) Seoul</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Asia/Yakutsk' ? 'selected' : '' }}
                                                            value="Asia/Yakutsk">(GMT+09:00) Yakutsk</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Australia/Adelaide' ? 'selected' : '' }}
                                                            value="Australia/Adelaide">(GMT+09:30) Adelaide
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Australia/Darwin' ? 'selected' : '' }}
                                                            value="Australia/Darwin">(GMT+09:30) Darwin</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Australia/Brisbane' ? 'selected' : '' }}
                                                            value="Australia/Brisbane">(GMT+10:00) Brisbane
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Australia/Hobart' ? 'selected' : '' }}
                                                            value="Australia/Hobart">(GMT+10:00) Hobart</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Asia/Vladivostok' ? 'selected' : '' }}
                                                            value="Asia/Vladivostok">(GMT+10:00) Vladivostok
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Australia/Lord_Howe' ? 'selected' : '' }}
                                                            value="Australia/Lord_Howe">(GMT+10:30) Lord Howe
                                                            Island
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Etc/GMT-11' ? 'selected' : '' }}
                                                            value="Etc/GMT-11">(GMT+11:00) Solomon Is., New
                                                            Caledonia
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Asia/Magadan' ? 'selected' : '' }}
                                                            value="Asia/Magadan">(GMT+11:00) Magadan</option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Pacific/Norfolk' ? 'selected' : '' }}
                                                            value="Pacific/Norfolk">(GMT+11:30) Norfolk Island
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Asia/Anadyr' ? 'selected' : '' }}
                                                            value="Asia/Anadyr">(GMT+12:00) Anadyr, Kamchatka
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Pacific/Auckland' ? 'selected' : '' }}
                                                            value="Pacific/Auckland">(GMT+12:00) Auckland,
                                                            Wellington
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Etc/GMT-12' ? 'selected' : '' }}
                                                            value="Etc/GMT-12">(GMT+12:00) Fiji, Kamchatka,
                                                            Marshall
                                                            Is. </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Pacific/Chatham' ? 'selected' : '' }}
                                                            value="Pacific/Chatham">(GMT+12:45) Chatham Islands
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Pacific/Tongatapu' ? 'selected' : '' }}
                                                            value="Pacific/Tongatapu">(GMT+13:00) Nuku'alofa
                                                        </option>
                                                        <option
                                                            {{ @$settingdata->timezone == 'Pacific/Kiritimati' ? 'selected' : '' }}
                                                            value="Pacific/Kiritimati">(GMT+14:00) Kiritimati
                                                        </option>
                                                    </select>

                                                </div>
                                            </div>

                                            @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                                <div class="col-md-6">
                                                    <label for=""
                                                        class="form-label">{{ trans('labels.order_type') }}</label>
                                                    <div class="form-group">


                                                        <input class="form-check-input me-0 store_can_sale mx-2"
                                                            type="checkbox" name="delivery_type[]" id="delivery1"a
                                                            value="delivery"
                                                            @if (in_Array('delivery', explode('|', $settingdata->delivery_type))) checked @endif>
                                                        <label class="form-check-label"
                                                            for="delivery1">{{ trans('labels.delivery') }}</label>

                                                        <input class="form-check-input me-0 store_can_sale mx-2"
                                                            type="checkbox" name="delivery_type[]" id="delivery2"
                                                            value="pickup"
                                                            @if (in_Array('pickup', explode('|', $settingdata->delivery_type))) checked @endif>
                                                        <label class="form-check-label"
                                                            for="delivery2">{{ trans('labels.pickup') }}</label>

                                                        <input class="form-check-input me-0 store_can_sale mx-2"
                                                            type="checkbox" name="delivery_type[]" id="delivery3"
                                                            value="table"
                                                            @if (in_Array('table', explode('|', $settingdata->delivery_type))) checked @endif>
                                                        <label class="form-check-label"
                                                            for="delivery3">{{ trans('labels.table') }}</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label class="form-label"
                                                        for="">{{ trans('labels.date_time') }}
                                                    </label>
                                                    <input id="ordertypedatetime-switch" type="checkbox"
                                                        class="checkbox-switch" name="ordertypedatetime" value="1"
                                                        {{ $settingdata->ordertype_date_time == 1 ? 'checked' : '' }}>
                                                    <label for="ordertypedatetime-switch" class="switch">
                                                        <span
                                                            class="{{ session()->get('direction') == 2 ? 'switch__circle-rtl' : 'switch__circle' }}"><span
                                                                class="switch__circle-inner"></span></span>
                                                        <span
                                                            class="switch__left {{ session()->get('direction') == 2 ? 'pe-2' : 'ps-2' }}">{{ trans('labels.off') }}</span>
                                                        <span
                                                            class="switch__right {{ session()->get('direction') == 2 ? 'ps-2' : 'pe-2' }}">{{ trans('labels.on') }}</span>
                                                    </label>
                                                </div>
                                            @endif
                                            @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))

                                                @if (@helper::checkaddons('subscription'))
                                                    @if (@helper::checkaddons('notification'))
                                                        @php

                                                            $checkplan = App\Models\Transaction::where(
                                                                'vendor_id',
                                                                $vendor_id,
                                                            )
                                                                ->orderByDesc('id')
                                                                ->first();

                                                            if (@$user->allow_without_subscription == 1) {
                                                                $sound_notification = 1;
                                                            } else {
                                                                $sound_notification = @$checkplan->sound_notification;
                                                            }

                                                        @endphp
                                                        @if ($sound_notification == 1)
                                                            <div class="form-group col-md-6">
                                                                <label
                                                                    class="form-label">{{ trans('labels.notification_sound') }}</label>
                                                                @if (env('Environment') == 'sendbox')
                                                                    <span
                                                                        class="badge badge bg-danger ms-2 mb-0">{{ trans('labels.addon') }}</span>
                                                                @endif
                                                                <input type="file" class="form-control"
                                                                    name="notification_sound">
                                                                @if (!empty($settingdata->notification_sound) && $settingdata->notification_sound != null)
                                                                    <audio controls class="mt-1">
                                                                        <source
                                                                            src="{{ url(env('ASSETPATHURL') . 'admin-assets/notification/' . $settingdata->notification_sound) }}"
                                                                            type="audio/mpeg">
                                                                    </audio>
                                                                @endif
                                                            </div>
                                                        @endif

                                                    @endif
                                                @else
                                                    @if (@helper::checkaddons('notification'))
                                                        <div class="form-group col-md-6">
                                                            <label
                                                                class="form-label">{{ trans('labels.notification_sound') }}</label>
                                                            @if (env('Environment') == 'sendbox')
                                                                <span
                                                                    class="badge badge bg-danger ms-2 mb-0">{{ trans('labels.addon') }}</span>
                                                            @endif
                                                            <input type="file" class="form-control"
                                                                name="notification_sound">
                                                            @if (!empty($settingdata->notification_sound) && $settingdata->notification_sound != null)
                                                                <audio controls class="mt-1">
                                                                    <source
                                                                        src="{{ url(env('ASSETPATHURL') . 'admin-assets/notification/' . $settingdata->notification_sound) }}"
                                                                        type="audio/mpeg">
                                                                </audio>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endif
                                            @endif

                                            <div
                                                class="form-group {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                                <button
                                                    class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
                                                    @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" name="updatebasicinfo" value="1" @endif>{{ trans('labels.save') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                    @if (count(@helper::checkthemeaddons('theme_')) > 0)
                        <div id="theme_images">
                            <div class="row mb-5">
                                <div class="col-12">
                                    <div class="card border-0 box-shadow">
                                        <div class="card-header p-3 bg-secondary">
                                            <h5 class="text-capitalize fw-600 settings-color">
                                                {{ trans('labels.themes') }}
                                                {{ trans('labels.images') }} @if (env('Environment') == 'sendbox')
                                                    <span
                                                        class="badge badge bg-danger ms-2">{{ trans('labels.addon') }}</span>
                                                @endif
                                            </h5>
                                        </div>
                                        <div class="card-body pb-0">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    @php
                                                        $checktheme = @helper::checkthemeaddons('theme_');
                                                        $themes = [];
                                                        foreach ($checktheme as $ttlthemes) {
                                                            array_push(
                                                                $themes,
                                                                str_replace(
                                                                    'theme_',
                                                                    '',
                                                                    $ttlthemes->unique_identifier,
                                                                ),
                                                            );
                                                        }
                                                    @endphp
                                                    <ul
                                                        class="theme-selection g-3 row row-cols-xl-6 row-cols-lg-4 row-cols-md-4 row-cols-sm-3 row-cols-2">
                                                        @foreach ($themes as $key => $item)
                                                            <li class="col">
                                                                <label for="template{{ $item }}" class="p-0">
                                                                    <img
                                                                        src="{{ helper::image_path('theme-' . $item . '.png') }}">
                                                                </label>
                                                                <div
                                                                    class="text-center mt-2 d-flex justify-content-center">
                                                                    <a class="btn btn-info hov btn-sm {{ Auth::user()->type == 4 ? (helper::check_access('role_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
                                                                        id="{{ $item }}"
                                                                        onclick="editimage(this.id)">
                                                                        <i class="fa-regular fa-pen-to-square"></i>
                                                                    </a>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
                <div id="editprofile">
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="card border-0 box-shadow">
                                <div class="card-header p-3 bg-secondary">
                                    <h5 class="text-capitalize fw-600 settings-color">
                                        {{ trans('labels.edit_profile') }}
                                    </h5>
                                </div>
                                <div class="card-body pb-0">
                                    <form method="POST"
                                        action="{{ URL::to('admin/settings/update-profile-' . Auth::user()->id) }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-sm-6">
                                                <label class="form-label">{{ trans('labels.name') }}
                                                    <span class="text-danger"> * </span>
                                                </label>
                                                <input type="text" class="form-control" name="name"
                                                    value="{{ Auth::user()->name }}"
                                                    placeholder="{{ trans('labels.name') }}" required>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="form-label">{{ trans('labels.email') }}
                                                    <span class="text-danger"> * </span>
                                                </label>
                                                <input type="email" class="form-control" name="email"
                                                    value="{{ Auth::user()->email }}"
                                                    placeholder="{{ trans('labels.email') }}" required>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="form-label" for="mobile">{{ trans('labels.mobile') }}
                                                    <span class="text-danger"> * </span></label>
                                                <input type="text" class="form-control mobile-number" name="mobile"
                                                    id="mobile" value="{{ Auth::user()->mobile }}"
                                                    placeholder="{{ trans('labels.mobile') }}" required>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="form-label">{{ trans('labels.image') }}</label>
                                                <input type="file" class="form-control" name="profile">
                                                <img class="img-fluid rounded hw-70 mt-1"
                                                    src="{{ helper::image_Path(Auth::user()->image) }}" alt="">
                                            </div>
                                            @if (Auth::user()->type == 2)
                                                @if (@helper::checkaddons('unique_slug'))
                                                    @if ($user->custom_domain == null)
                                                        <div class="form-group">
                                                            <label
                                                                class="form-label">{{ trans('labels.personlized_link') }}
                                                                <span class="text-danger"> * </span></label>
                                                            @if (env('Environment') == 'sendbox')
                                                                <span
                                                                    class="badge badge bg-danger ms-2 mb-0">{{ trans('labels.addon') }}
                                                                </span>
                                                            @endif
                                                            <div class="input-group">
                                                                <span class="input-group-text">{{ URL::to('/') }}</span>
                                                                <input type="text" class="form-control" id="slug"
                                                                    name="slug" value="{{ $user->slug }}" required>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endif
                                            @if (Auth::user()->type == 2)
                                                <div class="form-group col-md-6">
                                                    <label for="country"
                                                        class="form-label">{{ trans('labels.city') }}<span
                                                            class="text-danger"> * </span></label>
                                                    <select name="country" class="form-select" id="country" required>
                                                        <option value="">{{ trans('labels.select') }}</option>
                                                        @foreach ($countries as $country)
                                                            <option value="{{ $country->id }}"
                                                                {{ $country->id == $user->country_id ? 'selected' : '' }}>
                                                                {{ $country->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="city"
                                                        class="form-label">{{ trans('labels.area') }}<span
                                                            class="text-danger">
                                                            * </span></label>
                                                    <select name="city" class="form-select" id="city" required>
                                                        <option value="">{{ trans('labels.select') }}</option>
                                                    </select>
                                                </div>
                                            @endif
                                            <div
                                                class="form-group {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                                <button
                                                    class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
                                                    @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" name="updateprofile" value="1" @endif>{{ trans('labels.save') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="changepasssword">
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="card border-0 box-shadow">
                                <div class="card-header p-3 bg-secondary">
                                    <h5 class="text-capitalize fw-600 settings-color">
                                        {{ trans('labels.change_password') }}
                                    </h5>
                                </div>
                                <div class="card-body pb-0">
                                    <form action="{{ URL::to('admin/settings/change-password') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-sm-12">
                                                <label class="form-label">{{ trans('labels.current_password') }}<span
                                                        class="text-danger"> * </span></label>
                                                <input type="password" class="form-control" name="current_password"
                                                    value="{{ old('current_password') }}"
                                                    placeholder="{{ trans('labels.current_password') }}" required>

                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="form-label">{{ trans('labels.new_password') }}<span
                                                        class="text-danger"> * </span></label>
                                                <input type="password" class="form-control" name="new_password"
                                                    value="{{ old('new_password') }}"
                                                    placeholder="{{ trans('labels.new_password') }}" required>

                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="form-label">{{ trans('labels.confirm_password') }}<span
                                                        class="text-danger"> * </span></label>
                                                <input type="password" class="form-control" name="confirm_password"
                                                    value="{{ old('confirm_password') }}"
                                                    placeholder="{{ trans('labels.confirm_password') }}" required>

                                            </div>
                                            <div
                                                class="form-group {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                                <button
                                                    class="btn btn-primary px-sm-4  {{ Auth::user()->type == 4 ? (helper::check_access('role_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
                                                    @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif>{{ trans('labels.save') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                    @if (@helper::checkaddons('whatsapp_message'))
                        @include('admin.included.whatsapp_message.admin_setting_form')
                    @endif
                    @if (@helper::checkaddons('google_recaptcha'))
                        @include('admin.cookie_recaptcha.setting_form')
                    @endif
                @endif
                @if (@helper::checkaddons('email_settings'))
                    @include('admin.emailsettings.email_setting')
                    @include('admin.email_template.setting_form')
                @endif
                @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                    @if (@helper::checkaddons('tawk_addons'))
                        @include('admin.tawk_settings.index')
                    @endif

                    @if (@helper::checkaddons('quick_call'))
                        @include('admin.quick_call.index')
                    @endif

                    @if (@helper::checkaddons('wizz_chat'))
                        @include('admin.wizz_chat_settings.index')
                    @endif
                @endif

                @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                    @if (@helper::checkaddons('subscription'))
                        @if (@helper::checkaddons('pixel'))
                            @php
                                $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)
                                    ->orderByDesc('id')
                                    ->first();

                                if (@$user->allow_without_subscription == 1) {
                                    $pixel = 1;
                                } else {
                                    $pixel = @$checkplan->pixel;
                                }
                            @endphp
                            @if ($pixel == 1)
                                @include('admin.pixel.pixel_setting')
                            @endif
                        @endif
                    @else
                        @if (@helper::checkaddons('pixel'))
                            @include('admin.pixel.pixel_setting')
                        @endif
                    @endif
                    @if (@helper::checkaddons('shopify'))
                        @include('admin.shopify.shopify_setting')
                    @endif
                    @if (@helper::checkaddons('tawk_addons'))
                        @include('admin.tawk_settings.index')
                    @endif
                    @if (@helper::checkaddons('quick_call'))
                        @include('admin.quick_call.index')
                    @endif
                    @if (@helper::checkaddons('sales_notification'))
                        @include('admin.fake_sales_notification.index')
                    @endif
                    @if (@helper::checkaddons('fake_view'))
                        @include('admin.product_fake_view.index')
                    @endif
                    @if (@helper::checkaddons('wizz_chat'))
                        @include('admin.wizz_chat_settings.index')
                    @endif
                    @if (@helper::checkaddons('google_login'))
                        <div id="google_login_settings">
                            @include('admin.sociallogin.google_login')
                        </div>
                    @endif
                    @if (@helper::checkaddons('facebook_login'))
                        <div id="facebook_login_settings">
                            @include('admin.sociallogin.facebook_login')
                        </div>
                    @endif
                    @if (@helper::checkaddons('trusted_badges'))
                        <div id="trusted_badges">
                            <div class="row mb-5">
                                <div class="col-12">
                                    <div class="card border-0 box-shadow">
                                        <div class="card-header p-3 bg-secondary">
                                            <h5 class="text-capitalize fw-600 settings-color">
                                                {{ trans('labels.trusted_badges') }}
                                            </h5>
                                        </div>
                                        <form action="{{ URL::to('admin/settings/safe-secure-store') }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div
                                                            class="row row-cols-xxl-4 row-cols-xl-2 row-cols-lg-2 row-cols-md-2 row-cols-1">
                                                            <div class="form-group col">
                                                                <label
                                                                    class="form-label">{{ trans('labels.trusted_badge_image_1') }}
                                                                </label>
                                                                <input type="file" class="form-control"
                                                                    name="trusted_badge_image_1">
                                                                <img class="img-fluid rounded h-40 mt-1"
                                                                    src="{{ @helper::image_Path($othersettingdata->trusted_badge_image_1) }}"
                                                                    alt="">
                                                            </div>
                                                            <div class="form-group col">
                                                                <label
                                                                    class="form-label">{{ trans('labels.trusted_badge_image_2') }}
                                                                </label>
                                                                <input type="file" class="form-control"
                                                                    name="trusted_badge_image_2">
                                                                <img class="img-fluid rounded h-40 mt-1"
                                                                    src="{{ @helper::image_Path($othersettingdata->trusted_badge_image_2) }}"
                                                                    alt="">
                                                            </div>
                                                            <div class="form-group col">
                                                                <label
                                                                    class="form-label">{{ trans('labels.trusted_badge_image_3') }}
                                                                </label>
                                                                <input type="file" class="form-control"
                                                                    name="trusted_badge_image_3">
                                                                <img class="img-fluid rounded h-40 mt-1"
                                                                    src="{{ @helper::image_Path($othersettingdata->trusted_badge_image_3) }}"
                                                                    alt="">
                                                            </div>
                                                            <div class="form-group col">
                                                                <label
                                                                    class="form-label">{{ trans('labels.trusted_badge_image_4') }}
                                                                </label>
                                                                <input type="file" class="form-control"
                                                                    name="trusted_badge_image_4">
                                                                <img class="img-fluid rounded h-40 mt-1"
                                                                    src="{{ @helper::image_Path($othersettingdata->trusted_badge_image_4) }}"
                                                                    alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                                    <button
                                                        @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" name="trusted_badges" value="1" @endif
                                                        class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">{{ trans('labels.save') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (@helper::checkaddons('safe_secure_checkout'))
                        <div id="safe_secure">
                            <div class="row mb-5">
                                <div class="col-12">
                                    <div class="card border-0 box-shadow">
                                        <div class="card-header p-3 bg-secondary">
                                            <h5 class="text-capitalize fw-600 settings-color">
                                                {{ trans('labels.safe_secure') }}
                                            </h5>
                                        </div>
                                        <form action="{{ URL::to('admin/settings/safe-secure-store') }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="form-group col-sm-12">
                                                        <label
                                                            class="form-label">{{ trans('labels.safe_secure_checkout_payment_selection') }}
                                                        </label>
                                                        <div class="row">
                                                            @foreach ($getpayment as $payment)
                                                                @php
                                                                    // Check if the current $payment is a system addon and activated
                                                                    if (
                                                                        $payment->payment_type == '1' ||
                                                                        $payment->payment_type == '16'
                                                                    ) {
                                                                        $systemAddonActivated = true;
                                                                    } else {
                                                                        $systemAddonActivated = false;
                                                                    }
                                                                    $addon = App\Models\SystemAddons::where(
                                                                        'unique_identifier',
                                                                        $payment->unique_identifier,
                                                                    )->first();
                                                                    if ($addon != null && $addon->activated == 1) {
                                                                        $systemAddonActivated = true;
                                                                    }
                                                                @endphp
                                                                @if ($systemAddonActivated)
                                                                    <div class="form-group col-auto">
                                                                        <div class="form-check">
                                                                            <input
                                                                                class="form-check-input payment-checkbox"
                                                                                type="checkbox"
                                                                                name="safe_secure_checkout_payment_selection[]"
                                                                                {{ @in_array($payment->payment_type, explode(',', $othersettingdata->safe_secure_checkout_payment_selection)) ? 'checked' : '' }}
                                                                                id="{{ $payment->payment_type }}"
                                                                                value="{{ $payment->payment_type }}">
                                                                            <label class="form-check-label fw-bolder"
                                                                                for="{{ $payment->payment_type }}">
                                                                                {{ $payment->payment_name }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-sm-6">
                                                        <label
                                                            class="form-label">{{ trans('labels.safe_secure_checkout_text') }}
                                                        </label>
                                                        <input type="text" class="form-control"
                                                            name="safe_secure_checkout_text"
                                                            value="{{ @$othersettingdata->safe_secure_checkout_text }}">
                                                    </div>
                                                    <div class="form-group col-sm-6">
                                                        <label
                                                            class="form-label">{{ trans('labels.safe_secure_checkout_text_color') }}
                                                        </label>
                                                        <input type="color"
                                                            class="form-control form-control-color w-100 border-0"
                                                            name="safe_secure_checkout_text_color"
                                                            value="{{ @$othersettingdata->safe_secure_checkout_text_color }}">
                                                    </div>
                                                </div>
                                                <div
                                                    class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                                    <button
                                                        @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" name="safe_secure" value="1" @endif
                                                        class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">{{ trans('labels.save') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (@helper::checkaddons('cart_checkout_countdown'))
                        @include('admin.cart_checkout_countdown.index')
                    @endif
                    @if (@helper::checkaddons('cart_checkout_progressbar'))
                        @include('admin.cart_checkout_progressbar.index')
                    @endif
                    @if (@helper::checkaddons('recent_view_product'))
                        @include('admin.recent_view_product.index')
                    @endif
                    <div id="estimated_delivery">
                        <div class="row mb-5">
                            <div class="col-12">
                                <div class="card border-0 box-shadow">
                                    <form action="{{ URL::to('admin/settings/update') }}" method="POST">
                                        @csrf
                                        <div class="card-header p-3 bg-secondary">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h5 class="text-capitalize fw-600 text-dark">
                                                    {{ trans('labels.estimated_delivery') }}
                                                </h5>
                                                <div class="text-center">
                                                    <input id="estimated_delivery_on_off" type="checkbox"
                                                        class="checkbox-switch" name="estimated_delivery_on_off"
                                                        value="1"
                                                        {{ @$othersettingdata->estimated_delivery_on_off == 1 ? 'checked' : '' }}>
                                                    <label for="estimated_delivery_on_off" class="switch">
                                                        <span
                                                            class="{{ session()->get('direction') == 2 ? 'switch__circle-rtl' : 'switch__circle' }}">
                                                            <span class="switch__circle-inner"></span>
                                                        </span>
                                                        <span
                                                            class="switch__left {{ session()->get('direction') == 2 ? 'pe-2' : 'ps-2' }}">
                                                            {{ trans('labels.off') }}
                                                        </span>
                                                        <span
                                                            class="switch__right {{ session()->get('direction') == 2 ? 'ps-2' : 'pe-2' }}">
                                                            {{ trans('labels.on') }}
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="form-group col-sm-12">
                                                    <label for="days_of_estimated_delivery" class="form-label">
                                                        {{ trans('labels.number_of_day') }}
                                                        <span class="text-danger"> * </span>
                                                    </label>
                                                    <input type="number" class="form-control"
                                                        id="days_of_estimated_delivery" name="days_of_estimated_delivery"
                                                        value="{{ @$othersettingdata->days_of_estimated_delivery }}"
                                                        required placeholder="{{ trans('labels.number_of_day') }}">
                                                </div>
                                            </div>
                                            <div class="text-{{ session()->get('direction') == '2' ? 'start' : 'end' }}">
                                                <button
                                                    @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" name="estimated_delivery" value="1" @endif
                                                    class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_setting', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">
                                                    {{ trans('labels.save') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="delete_profile">
                        <div class="row mb-5">
                            <div class="col-12">
                                <div class="card border-0 box-shadow">
                                    <div class="card-header p-3 bg-secondary">
                                        <h5 class="text-capitalize fw-600 text-dark">{{ trans('labels.delete_profile') }}
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <p class="form-group">{{ trans('labels.before_delete_msg') }}
                                            </p>
                                            <div class="form-group col-sm-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        name="delete_account" id="delete_account" required>
                                                    <label class="form-check-label fw-bolder" for="delete_account">
                                                        {{ trans('labels.are_you_sure_delete_account') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="{{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                            <button
                                                @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit"  onclick="deleteaccount('{{ URL::to('admin/deleteaccount-' . $vendor_id) }}')" @endif
                                                class="btn btn-primary px-sm-4  {{ Auth::user()->type == 4 ? (helper::check_access('role_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">{{ trans('labels.save') }}</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div id="maintenance_mode">
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="card border-0 box-shadow">
                                <form action="{{ URL::to('admin/maintenance_update') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-header p-3 bg-secondary rounded-top">
                                        <h5 class="text-capitalize fw-600 text-dark">
                                            {{ trans('labels.maintenance_mode') }}
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-sm-3">

                                                <label class="form-label"
                                                    for="">{{ trans('labels.maintenance_mode') }}
                                                </label>
                                                <input id="maintenance_on_off" type="checkbox" class="checkbox-switch"
                                                    name="maintenance_on_off" value="1"
                                                    {{ @$othersettingdata->maintenance_on_off == 1 ? 'checked' : '' }}>
                                                <label for="maintenance_on_off" class="switch">
                                                    <span
                                                        class="{{ session()->get('direction') == 2 ? 'switch__circle-rtl' : 'switch__circle' }}">
                                                        <span class="switch__circle-inner"></span>
                                                    </span>
                                                    <span
                                                        class="switch__left {{ session()->get('direction') == 2 ? 'pe-2' : 'ps-2' }}">
                                                        {{ trans('labels.off') }}
                                                    </span>
                                                    <span
                                                        class="switch__right {{ session()->get('direction') == 2 ? 'ps-2' : 'pe-2' }}">
                                                        {{ trans('labels.on') }}
                                                    </span>
                                                </label>

                                            </div>
                                            <div class="form-group col-sm-12">
                                                <label for="maintenance_title" class="form-label">
                                                    {{ trans('labels.title') }}
                                                    <span class="text-danger"> * </span>
                                                </label>
                                                <input type="text" class="form-control" id="maintenance_title"
                                                    name="maintenance_title" placeholder=" {{ trans('labels.title') }}"
                                                    required="" value="{{ @$othersettingdata->maintenance_title }}">
                                            </div>
                                            <div class="form-group col-sm-12">
                                                <label for="maintenance_description" class="form-label">
                                                    {{ trans('labels.description') }}
                                                    <span class="text-danger"> * </span>
                                                </label>
                                                <textarea name="maintenance_description" class="form-control" rows="4"
                                                    placeholder=" {{ trans('labels.description') }}" required="">{{ @$othersettingdata->maintenance_description }}</textarea>
                                            </div>

                                            <div class="form-group col-sm-6">
                                                <label class="form-label">
                                                    {{ trans('labels.image') }}<span class="text-danger">
                                                        *
                                                    </span>
                                                </label>
                                                <input type="file" class="form-control" name="maintenance_image">

                                                <img class="img-fluid rounded hw-70 mt-1"
                                                    src="{{ @helper::image_Path($othersettingdata->maintenance_image) }}"
                                                    alt="">
                                            </div>
                                        </div>
                                        <div class="text-{{ session()->get('direction') == '2' ? 'start' : 'end' }}">
                                            <button
                                                @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif
                                                class="btn btn-primary px-sm-4">
                                                {{ trans('labels.save') }}
                                            </button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                    <div id="notice_mode">
                        <div class="row mb-5">
                            <div class="col-12">
                                <div class="card border-0 box-shadow">
                                    <form action="{{ URL::to('admin/notice_update') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-header p-3 bg-secondary rounded-top">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h5 class="text-capitalize fw-600 text-dark">
                                                    {{ trans('labels.notice') }}
                                                </h5>
                                                <div>
                                                    <div class="text-center">
                                                        <input id="notice_on_off" type="checkbox" class="checkbox-switch"
                                                            name="notice_on_off" value="1"
                                                            {{ @$othersettingdata->notice_on_off == 1 ? 'checked' : '' }}>
                                                        <label for="notice_on_off" class="switch">
                                                            <span
                                                                class="{{ session()->get('direction') == 2 ? 'switch__circle-rtl' : 'switch__circle' }}">
                                                                <span class="switch__circle-inner"></span>
                                                            </span>
                                                            <span
                                                                class="switch__left {{ session()->get('direction') == 2 ? 'pe-2' : 'ps-2' }}">
                                                                {{ trans('labels.off') }}
                                                            </span>
                                                            <span
                                                                class="switch__right {{ session()->get('direction') == 2 ? 'ps-2' : 'pe-2' }}">
                                                                {{ trans('labels.on') }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="form-group col-sm-12">
                                                    <label for="notice_title" class="form-label">
                                                        {{ trans('labels.title') }}
                                                        <span class="text-danger"> * </span>
                                                    </label>
                                                    <input type="text" class="form-control" id="notice_title"
                                                        name="notice_title" placeholder=" {{ trans('labels.title') }}"
                                                        required="" value="{{ @$othersettingdata->notice_title }}">
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="notice_description" class="form-label">
                                                        {{ trans('labels.description') }}
                                                        <span class="text-danger"> * </span>
                                                    </label>
                                                    <textarea name="notice_description" class="form-control" rows="4"
                                                        placeholder=" {{ trans('labels.description') }}" required="">{{ @$othersettingdata->notice_description }}</textarea>
                                                </div>
                                            </div>
                                            <div class="text-{{ session()->get('direction') == '2' ? 'start' : 'end' }}">
                                                <button
                                                    @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif
                                                    class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_general_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}">
                                                    {{ trans('labels.save') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (@helper::checkaddons('ai_settings'))
                    @include('admin.ai_settings.index')
                @endif
            </div>
        </div>
    </div>
    {{-- EDIT THEME IMAGE MODAL --}}
    <div class="modal modal-fade-transform" id="editModal" tabindex="-1" aria-labelledby="editModallable"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title" id="editModallable">{{ trans('labels.image') }}<span class="text-danger"> *
                        </span></h5>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action=" {{ URL::to('admin/plan/updateimage') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="image_id" name="image_id">
                        <input type="file" name="theme_image" class="form-control" id="">

                    </div>
                    <div class="modal-footer">
                        <button
                            @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif
                            class="btn btn-secondary px-sm-4">{{ trans('labels.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        @if (count($errors) > 0)
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>
    <script>
        var cityurl = "{{ URL::to('admin/getcity') }}";
        var select = "{{ trans('labels.select') }}";
        var cityid = "{{ Auth::user()->city_id != null ? Auth::user()->city_id : '0' }}";
        var requiredmsg = "{{ trans('messages.checkbox_delete_account') }}";
        var langurl = "{{ URL::to('admin/settings/getlanguage') }}";
        $(document).ready(function() {
            $('#recaptcha_version').on('change', function() {
                var recaptcha_version = $(this).val();
                if (recaptcha_version == 'v3') {
                    $("#score_threshold").show();
                } else {
                    $("#score_threshold").hide();
                }
            });
        });
    </script>
    <script>
        function deleteaccount(nexturl) {
            var deleted = document.getElementById("delete_account").checked;
            if (deleted == true) {
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success mx-1',
                        cancelButton: 'btn btn-danger mx-1'
                    },
                    buttonsStyling: false
                })
                swalWithBootstrapButtons.fire({
                    title: are_you_sure,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: yes,
                    cancelButtonText: no,
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = nexturl;
                    } else {
                        result.dismiss === Swal.DismissReason.cancel
                    }
                })
            } else {
                toastr.error(requiredmsg);
            }
        }
    </script>

    <script src="{{ url('storage/app/public/admin-assets/js/user.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.12.1/ckeditor.js"></script>
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/settings.js') }}"></script>
@endsection
