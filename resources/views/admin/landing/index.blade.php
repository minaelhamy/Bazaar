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
        {{ trans('labels.basic_settings') }}</h5>
    <div class="row settings mt-3">
        <div class="col-xl-3 mb-3">
            <div class="card-sticky-top border-0">
                <ul class="list-group list-options">
                    <a href="#theme_settings" data-tab="theme_settings"
                        class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center active"
                        aria-current="true">{{ trans('labels.theme_settings') }}
                        <i class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                    </a>

                    <a href="#contact_settings" data-tab="contact_settings"
                        class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                        aria-current="true">{{ trans('labels.contact_settings') }}
                        <i class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                    </a>
                    <a href="#seo" data-tab="seo"
                        class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                        aria-current="true">{{ trans('labels.seo') }}
                        <i class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                    </a>
                    @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                        @if (@helper::checkaddons('vendor_app'))
                            <a href="#app_section" data-tab="app_section"
                                class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                aria-current="true">{{ trans('labels.app_section') }}
                                <div class="d-flex align-items-center gap-1 justify-content-between">
                                    @if (env('Environment') == 'sendbox')
                                        <span class="badge badge bg-danger">{{ trans('labels.addon') }}</span>
                                    @endif
                                    <i
                                        class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                                </div>
                            </a>
                        @endif
                        <a href="#fun_fact" data-tab="fun_fact"
                            class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                            aria-current="true">{{ trans('labels.fun_fact') }}
                            <i class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                        </a>
                    @endif
                    @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                        @if (@helper::checkaddons('subscription'))
                            @if (@helper::checkaddons('user_app'))
                                @php
                                    $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)
                                        ->orderByDesc('id')
                                        ->first();

                                    if (@$user->allow_without_subscription == 1) {
                                        $user_app = 1;
                                    } else {
                                        $user_app = @$checkplan->customer_app;
                                    }
                                @endphp
                                @if ($user_app == 1)
                                    <a href="#app_section" data-tab="app_section"
                                        class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                        aria-current="true">{{ trans('labels.app_section') }}
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
                            @if (@helper::checkaddons('user_app'))
                                <a href="#app_section" data-tab="app_section"
                                    class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                    aria-current="true">{{ trans('labels.app_section') }}
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
                        <a href="#footer_features" data-tab="footer_features"
                            class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                            aria-current="true">{{ trans('labels.footer_features') }}
                            <i
                                class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i></a>

                        @if (@helper::checkaddons('subscription'))
                            @if (@helper::checkaddons('pwa'))
                                @php
                                    $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)
                                        ->orderByDesc('id')
                                        ->first();

                                    if (@$user->allow_without_subscription == 1) {
                                        $pwa = 1;
                                    } else {
                                        $pwa = @$checkplan->pwa;
                                    }
                                @endphp
                                @if ($pwa == 1)
                                    <a href="#pwa" data-tab="pwa"
                                        class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                        aria-current="true">{{ trans('labels.pwa') }}
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
                            @if (@helper::checkaddons('pwa'))
                                <a href="#pwa" data-tab="pwa"
                                    class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                    aria-current="true">{{ trans('labels.pwa') }}
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
                        @if (@helper::checkaddons('age_verification'))
                            <a href="#age_verification" data-tab="age_verification"
                                class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                aria-current="true">{{ trans('labels.age_verification') }}
                                <div class="d-flex align-items-center gap-1 justify-content-between">
                                    @if (env('Environment') == 'sendbox')
                                        <span class="badge badge bg-danger">{{ trans('labels.addon') }}</span>
                                    @endif
                                    <i
                                        class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                                </div>
                            </a>
                        @endif
                        @if (@helper::checkaddons('vendor_tip'))
                            <a href="#tips_settings" data-tab="tips_settings"
                                class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                                aria-current="true">{{ trans('labels.tips_settings') }}
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
                    <a href="#social_links" data-tab="social_links"
                        class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                        aria-current="true">{{ trans('labels.social_links') }} <i
                            class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i></a>
                    <a href="#other" data-tab="other"
                        class="list-group-item basicinfo p-3 list-item-secondary d-flex justify-content-between align-items-center"
                        aria-current="true">{{ trans('labels.other') }}
                        <i class="fa-regular fa-angle-{{ session()->get('direction') == '2' ? 'left' : 'right' }}"></i>
                    </a>
                </ul>
            </div>
        </div>
        <div class="col-xl-9">
            <div id="settingmenuContent">
                <div id="theme_settings">
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="card border-0 box-shadow">
                                <div class="card-header p-3 bg-secondary">
                                    <h5 class="text-capitalize fw-600 settings-color">
                                        {{ trans('labels.theme_settings') }}
                                    </h5>
                                </div>
                                <div class="card-body pb-0">
                                    <form action="{{ URL::to('admin/theme_settings/update') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="form-label">{{ trans('labels.website_title') }}<span
                                                        class="text-danger"> * </span></label>
                                                <input type="text" class="form-control" name="website_title"
                                                    value="{{ @$settingdata->website_title }}"
                                                    placeholder="{{ trans('labels.website_title') }}">
                                                @error('website_title')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                                                <div class="form-group col-sm-6">
                                                    <label class="form-label">{{ trans('labels.primary_color') }}</label>
                                                    <input type="color"
                                                        class="form-control form-control-color w-100 border-0"
                                                        name="landing_primary_color"
                                                        value="{{ $landingdata->primary_color }}">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label
                                                        class="form-label">{{ trans('labels.secondary_color') }}</label>
                                                    <input type="color"
                                                        class="form-control form-control-color w-100 border-0"
                                                        name="landing_secondary_color"
                                                        value="{{ $landingdata->secondary_color }}">
                                                </div>
                                            @else
                                                <div class="form-group col-sm-6">
                                                    <label class="form-label">{{ trans('labels.primary_color') }}</label>
                                                    <input type="color"
                                                        class="form-control form-control-color w-100 border-0"
                                                        name="landing_primary_color"
                                                        value="{{ $settingdata->primary_color }}">
                                                </div>
                                                <div class="form-group col-sm-6">
                                                    <label
                                                        class="form-label">{{ trans('labels.secondary_color') }}</label>
                                                    <input type="color"
                                                        class="form-control form-control-color w-100 border-0"
                                                        name="landing_secondary_color"
                                                        value="{{ $settingdata->secondary_color }}">
                                                </div>
                                            @endif

                                            <div class="form-group">
                                                <label class="form-label">{{ trans('labels.copyright') }}<span
                                                        class="text-danger"> * </span></label>
                                                <input type="text" class="form-control" name="copyright"
                                                    value="{{ @$settingdata->copyright }}"
                                                    placeholder="{{ trans('labels.copyright') }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Footer Description</label>
                                                <textarea class="form-control" name="footer_description" rows="4"
                                                    placeholder="Enter the landing page footer description">{{ @$settingdata->description }}</textarea>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="form-label">{{ trans('labels.logo') }}</label>
                                                <input type="file" class="form-control" name="logo">
                                                <img class="img-fluid rounded hw-70 mt-1 object-fit-contain"
                                                    src="{{ helper::image_path(@$settingdata->logo) }}" alt="">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="form-label">{{ trans('labels.darklogo') }}</label>
                                                <input type="file" class="form-control" name="darklogo">
                                                <img class="img-fluid rounded hw-70 mt-1 object-fit-contain"
                                                    src="{{ helper::image_path(@$settingdata->darklogo) }}"
                                                    alt="">
                                            </div>

                                            <div class="form-group col-sm-6">
                                                <label class="form-label">{{ trans('labels.favicon') }}</label>
                                                <input type="file" class="form-control" name="favicon">

                                                <img class="img-fluid rounded hw-70 mt-1 object-fit-contain"
                                                    src="{{ helper::image_path(@$settingdata->favicon) }}"
                                                    alt="">
                                            </div>
                                            @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                                                <div class="form-group col-sm-6">
                                                    <label class="form-label"
                                                        for="">{{ trans('labels.landing_page') }}
                                                    </label>
                                                    <input id="landing-switch" type="checkbox" class="checkbox-switch"
                                                        name="landing" value="1"
                                                        {{ $settingdata->landing_page == 1 ? 'checked' : '' }}>
                                                    <label for="landing-switch" class="switch">
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
                                                @php
                                                    $checktheme = @helper::checkthemeaddons('theme_');
                                                    $themes = [];
                                                    if ($user->allow_without_subscription == 1) {
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
                                                    } else {
                                                        if (@helper::checkaddons('subscription')) {
                                                            if (empty($theme)) {
                                                                $themes = [$settingdata->template];
                                                            } else {
                                                                $themes = explode('|', @$theme->themes_id);
                                                            }
                                                        } else {
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
                                                        }
                                                    }
                                                @endphp
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">{{ trans('labels.theme') }}
                                                            <span class="text-danger"> * </span> </label>
                                                        @if (env('Environment') == 'sendbox')
                                                            <span
                                                                class="badge badge bg-danger ms-2">{{ trans('labels.addon') }}</span>
                                                        @endif
                                                        <ul
                                                            class="theme-selection g-3 row row-cols-xl-6 row-cols-lg-4 row-cols-md-4 row-cols-sm-3 row-cols-2">
                                                            @foreach ($themes as $item)
                                                                <li class="col">
                                                                    <input type="radio" name="template"
                                                                        id="template{{ $item }}"
                                                                        value="{{ $item }}"
                                                                        {{ @$settingdata->template == $item ? 'checked' : '' }}>
                                                                    <label for="template{{ $item }}"
                                                                        class="p-0">
                                                                        <img
                                                                            src="{{ helper::image_path('theme-' . $item . '.png') }}">
                                                                    </label>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div
                                            class="form-group {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                            <button
                                                class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_basic_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
                                                @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif>{{ trans('labels.save') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="contact_settings">
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="card border-0 box-shadow">
                                <div class="card-header p-3 bg-secondary">
                                    <h5 class="text-capitalize fw-600 settings-color">
                                        {{ trans('labels.contact_settings') }}
                                    </h5>
                                </div>
                                <div class="card-body pb-0">
                                    <form action="{{ URL::to('admin/contact_settings/update') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="form-label">{{ trans('labels.email') }}<span
                                                        class="text-danger"> * </span></label>
                                                <input type="email" class="form-control" name="landing_email"
                                                    value="{{ @$settingdata->email }}"
                                                    placeholder="{{ trans('labels.email') }}" required>
                                                @error('landing_email')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="form-label">{{ trans('labels.mobile') }}<span
                                                        class="text-danger"> * </span></label>
                                                <input type="text" class="form-control mobile-number"
                                                    name="landing_mobile" value="{{ @$settingdata->contact }}"
                                                    placeholder="{{ trans('labels.mobile') }}" required>
                                                @error('contact_mobile')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">{{ trans('labels.address') }}<span
                                                        class="text-danger"> * </span></label>
                                                <textarea class="form-control" name="landing_address" rows="3" placeholder="{{ trans('labels.address') }}">{{ @$settingdata->address }}</textarea>
                                                @error('address')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div
                                            class="form-group {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                            <button
                                                class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_basic_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
                                                @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif>{{ trans('labels.save') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="seo">
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="card border-0 box-shadow">
                                <div
                                    class="card-header p-3 bg-secondary d-flex align-items-center justify-content-between">
                                    <h5 class="text-capitalize fw-600 settings-color">
                                        {{ trans('labels.seo') }}
                                    </h5>
                                    {{-- AI Button: Default d-none (hidden) rahega --}}
                                    <button type="button" id="ai_btn" class="btn btn-sm btn-primary  d-none"
                                        style="padding: 2px 10px;" onclick="ai_generate()"  tooltip="{{ trans('labels.ai_generate') }}">
                                        <i class="fa-solid fa-robot me-1"></i>{{ trans('labels.ai') }}
                                    </button>
                                </div>
                                <div class="card-body pb-0">
                                    <form action="{{ URL::to('admin/seo/update') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="form-label">{{ trans('labels.meta_title') }}<span
                                                        class="text-danger"> * </span></label>
                                                <input type="text" class="form-control" name="meta_title"
                                                    value="{{ @$settingdata->meta_title }}" id="meta_title"
                                                    placeholder="{{ trans('labels.meta_title') }}" required>
                                                @error('meta_title')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">{{ trans('labels.meta_description') }}<span
                                                        class="text-danger"> * </span></label>
                                                <textarea class="form-control" name="meta_description" rows="3"
                                                    placeholder="{{ trans('labels.meta_description') }}" required>{{ @$settingdata->meta_description }}</textarea>
                                                @error('meta_description')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">{{ trans('labels.og_image') }} </label>
                                                <input type="file" class="form-control" name="og_image">

                                                <img class="img-fluid rounded hw-70 mt-1 object-fit-cover"
                                                    src="{{ helper::image_Path(@$settingdata->og_image) }}"
                                                    alt="">
                                            </div>
                                            <div
                                                class="form-group {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                                <button
                                                    class="btn btn-primary px-sm-4  {{ Auth::user()->type == 4 ? (helper::check_access('role_basic_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
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
                    @if (@helper::checkaddons('vendor_app'))
                        @include('admin.mobile_app.app_section')
                    @endif
                    <div id="fun_fact">
                        <div class="row mb-5">
                            <div class="col-12">
                                <div class="col-12">
                                    <div class="card border-0 box-shadow">
                                        <div class="card-header p-3 bg-secondary">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h5 class="text-capitalize fw-600 settings-color">
                                                    {{ trans('labels.fun_fact') }} <span class=""
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Ex. <i class='fa-solid fa-truck-fast'></i> Visit https://fontawesome.com/ for more info">
                                                        <i class="fa-solid fa-circle-info"></i> </span></h5>
                                                <button class="btn btn-primary hov" type="button"
                                                    tooltip="{{ trans('labels.add') }}"
                                                    onclick="add_funfact('{{ trans('labels.icon') }}','{{ trans('labels.title') }}','{{ trans('labels.sub_title') }}')">
                                                    <i class="fa-sharp fa-solid fa-plus"></i> </button>
                                            </div>
                                        </div>
                                        <div class="card-body pb-0">
                                            <form action="{{ URL::to('admin/fun_fact/update') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    @foreach ($funfacts as $key => $facts)
                                                        <input type="hidden" name="edit_icon_key[]"
                                                            value="{{ $facts->id }}">
                                                        <div class="col-md-4 form-group">
                                                            <div class="input-group">
                                                                <input type="text"
                                                                    class="form-control feature_required  {{ session()->get('direction') == 2 ? 'input-group-rtl' : '' }}"
                                                                    onkeyup="show_funfact_icon(this)"
                                                                    name="edi_funfact_icon[{{ $facts->id }}]"
                                                                    placeholder="{{ trans('labels.icon') }}"
                                                                    value="{{ $facts->icon }}" required>
                                                                <p
                                                                    class="input-group-text {{ session()->get('direction') == 2 ? 'input-group-icon-rtl' : '' }}">
                                                                    {!! $facts->icon !!}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 form-group">
                                                            <input type="text" class="form-control"
                                                                name="edi_funfact_title[{{ $facts->id }}]"
                                                                placeholder="{{ trans('labels.title') }}"
                                                                value="{{ $facts->title }}" required>
                                                        </div>
                                                        <div class="col-md-4 form-group d-flex gap-2 gap-sm-4">
                                                            <input type="text" class="form-control"
                                                                name="edi_funfact_subtitle[{{ $facts->id }}]"
                                                                placeholder="{{ trans('labels.sub_title') }}"
                                                                value="{{ $facts->description }}" required>
                                                            <div class="col-auto form-group m-0">
                                                                <button class="btn btn-danger" type="button"
                                                                    tooltip="{{ trans('labels.delete') }}"
                                                                    @if (env('Environment') == 'sendbox') onclick="myFunction()" @else
                                                                    onclick="statusupdate('{{ URL::to('admin/fun_fact/delete-' . $facts->id) }}')" @endif>
                                                                    <i class="fa fa-trash"></i> </button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    <span class="extra_footer_features"></span>
                                                    <div
                                                        class="form-group {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                                        <button
                                                            class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_basic_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
                                                            @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif>{{ trans('labels.save') }}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                    @if (@helper::checkaddons('subscription'))
                        @if (@helper::checkaddons('user_app'))
                            @php
                                $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)
                                    ->orderByDesc('id')
                                    ->first();

                                if (@$user->allow_without_subscription == 1) {
                                    $user_app = 1;
                                } else {
                                    $user_app = @$checkplan->customer_app;
                                }
                            @endphp
                            @if ($user_app == 1)
                                @include('admin.mobile_app.app_section')
                            @endif
                        @endif
                    @else
                        @if (@helper::checkaddons('user_app'))
                            @include('admin.mobile_app.app_section')
                        @endif
                    @endif
                    <div id="footer_features">
                        <div class="row mb-5">
                            <div class="col-12">
                                <div class="col-12">
                                    <div class="card border-0 box-shadow">
                                        <div class="card-header p-3 bg-secondary">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h5 class="text-capitalize fw-600 settings-color">
                                                    {{ trans('labels.footer_features') }}<span class=""
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Ex. <i class='fa-solid fa-truck-fast'></i> Visit https://fontawesome.com/ for more info">
                                                        <i class="fa-solid fa-circle-info"></i> </span></h5>
                                                <button class="btn btn-primary hov" type="button"
                                                    tooltip="{{ trans('labels.add') }}"
                                                    onclick="add_features('{{ trans('labels.icon') }}','{{ trans('labels.title') }}','{{ trans('labels.description') }}')">
                                                    <i class="fa-sharp fa-solid fa-plus"></i> </button>
                                            </div>
                                        </div>
                                        <div class="card-body  pb-0">
                                            <form action="{{ URL::to('admin/footer_features/update') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    @foreach ($getfooterfeatures as $key => $features)
                                                        <input type="hidden" name="edit_icon_key[]"
                                                            value="{{ $features->id }}">
                                                        <div class="col-md-4 form-group">
                                                            <div class="input-group">
                                                                <input type="text"
                                                                    class="form-control feature_required  {{ session()->get('direction') == 2 ? 'input-group-rtl' : '' }}"
                                                                    onkeyup="show_feature_icon(this)"
                                                                    name="edi_feature_icon[{{ $features->id }}]"
                                                                    placeholder="{{ trans('labels.icon') }}"
                                                                    value="{{ $features->icon }}" required>
                                                                <p
                                                                    class="input-group-text {{ session()->get('direction') == 2 ? 'input-group-icon-rtl' : '' }}">
                                                                    {!! $features->icon !!}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 form-group">
                                                            <input type="text" class="form-control"
                                                                name="edi_feature_title[{{ $features->id }}]"
                                                                placeholder="{{ trans('labels.title') }}"
                                                                value="{{ $features->title }}" required>
                                                        </div>
                                                        <div class="col-md-4 gap-2 gap-sm-4 d-flex form-group">
                                                            <input type="text" class="form-control"
                                                                name="edi_feature_description[{{ $features->id }}]"
                                                                placeholder="{{ trans('labels.description') }}"
                                                                value="{{ $features->description }}" required>
                                                            <button class="btn btn-danger" type="button"
                                                                tooltip="{{ trans('labels.delete') }}"
                                                                onclick="statusupdate('{{ URL::to('admin/settings/delete-feature-' . $features->id) }}')">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                    <span class="extra_footer_features"></span>
                                                    <div
                                                        class="form-group {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                                        <button
                                                            class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_basic_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
                                                            @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif>{{ trans('labels.save') }}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (@helper::checkaddons('subscription'))
                        @if (@helper::checkaddons('pwa'))
                            @php
                                $checkplan = App\Models\Transaction::where('vendor_id', $vendor_id)
                                    ->orderByDesc('id')
                                    ->first();

                                if (@$user->allow_without_subscription == 1) {
                                    $pwa = 1;
                                } else {
                                    $pwa = @$checkplan->pwa;
                                }
                            @endphp
                            @if ($pwa == 1)
                                @include('admin.pwa.pwa_settings')
                            @endif
                        @endif
                    @else
                        @if (@helper::checkaddons('pwa'))
                            @include('admin.pwa.pwa_settings')
                        @endif
                    @endif

                    @if (@helper::checkaddons('age_verification'))
                        @include('admin.age_verification.index')
                    @endif

                    @if (@helper::checkaddons('vendor_tip'))
                        @include('admin.tips_settings.tips_settings')
                    @endif
                @endif
                <div id="social_links">
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="col-12">
                                <div class="card border-0 box-shadow">
                                    <div class="card-header p-3 bg-secondary">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h5 class="text-capitalize fw-600 settings-color">
                                                {{ trans('labels.social_links') }} <span class=""
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Ex. <i class='fa-solid fa-truck-fast'></i> Visit https://fontawesome.com/ for more info">
                                                    <i class="fa-solid fa-circle-info"></i> </span></h5>
                                            <button class="btn btn-primary hov" type="button"
                                                tooltip="{{ trans('labels.add') }}"
                                                onclick="add_social_links('{{ trans('labels.icon') }}','{{ trans('labels.link') }}')">
                                                <i class="fa-sharp fa-solid fa-plus"></i> </button>
                                        </div>
                                    </div>
                                    <div class="card-body  pb-0">
                                        <form action="{{ URL::to('admin/social_links/update') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">

                                                @foreach ($getsociallinks as $key => $links)
                                                    <input type="hidden" name="edit_icon_key[]"
                                                        value="{{ $links->id }}">
                                                    <div class="col-md-6 form-group">
                                                        <div class="input-group">
                                                            <input type="text"
                                                                class="form-control soaciallink_required  {{ session()->get('direction') == 2 ? 'input-group-rtl' : '' }}"
                                                                onkeyup="show_feature_icon(this)"
                                                                name="edi_sociallink_icon[{{ $links->id }}]"
                                                                placeholder="{{ trans('labels.icon') }}"
                                                                value="{{ $links->icon }}" required>
                                                            <p
                                                                class="input-group-text {{ session()->get('direction') == 2 ? 'input-group-icon-rtl' : '' }}">
                                                                {!! $links->icon !!}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="col-md-6 gap-2 gap-sm-4 d-flex align-items-center form-group">
                                                        <input type="text" class="form-control"
                                                            name="edi_sociallink_link[{{ $links->id }}]"
                                                            placeholder="{{ trans('labels.link') }}"
                                                            value="{{ $links->link }}" required>
                                                        <button class="btn btn-danger" type="button"
                                                            tooltip="{{ trans('labels.delete') }}"
                                                            @if (env('Environment') == 'sendbox') onclick="myFunction()" @else
                                                            onclick="statusupdate('{{ URL::to('admin/settings/delete-sociallinks-' . $links->id) }}')" @endif>
                                                            <i class="fa fa-trash"></i> </button>
                                                    </div>
                                                @endforeach

                                                <span class="extra_social_links"></span>
                                                <div
                                                    class="form-group {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                                    <button
                                                        class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_basic_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
                                                        @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif>{{ trans('labels.save') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="other">
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="col-12">
                                <div class="card border-0 box-shadow">
                                    <div class="card-header p-3 bg-secondary">
                                        <h5 class="text-capitalize fw-600 settings-color">{{ trans('labels.other') }}
                                        </h5>
                                    </div>
                                    <div class="card-body pb-0">
                                        <form action="{{ URL::to('admin/other/update') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                                    <div class="form-group col-sm-4">
                                                        <label
                                                            class="form-label">{{ trans('labels.google_review_url') }}</label>
                                                        <input type="text" class="form-control"
                                                            placeholder="{{ trans('labels.google_review_url') }}"
                                                            name="google_review_url"
                                                            value="{{ $settingdata->google_review }}">
                                                    </div>
                                                    <div class="form-group col-sm-4">
                                                        <label
                                                            class="form-label">{{ trans('labels.product_section_display') }}</label>
                                                        <select name="product_section_display" class="form-select">
                                                            <option value="">{{ trans('labels.select_section') }}
                                                            </option>
                                                            <option value="1"
                                                                {{ $settingdata->product_section_display == 1 ? 'selected' : '' }}>
                                                                {{ trans('labels.selling_product') }}</option>
                                                            <option value="2"
                                                                {{ $settingdata->product_section_display == 2 ? 'selected' : '' }}>
                                                                {{ trans('labels.top_rated_product') }}</option>
                                                            <option value="3"
                                                                {{ $settingdata->product_section_display == 3 ? 'selected' : '' }}>
                                                                {{ trans('labels.both') }}</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-sm-4">
                                                        <label
                                                            class="form-label">{{ trans('labels.product_display_limit') }}</label>
                                                        <input type="number" class="form-control"
                                                            placeholder="{{ trans('labels.product_display_limit') }}"
                                                            name="product_display_limit"
                                                            value="{{ $settingdata->product_display_limit }}">
                                                    </div>
                                                    @if (@helper::checkaddons('product_reviews'))
                                                        <div class="col-md-3 form-group">
                                                            <label class="form-label"
                                                                for="">{{ trans('labels.product_ratting_switch') }}
                                                            </label>
                                                            <div class="text-center">
                                                                <input id="product_ratting_switch" type="checkbox"
                                                                    class="checkbox-switch" name="product_ratting_switch"
                                                                    value="1"
                                                                    {{ $settingdata->product_ratting_switch == 1 ? 'checked' : '' }}>
                                                                <label for="product_ratting_switch" class="switch">
                                                                    <span
                                                                        class="{{ session()->get('direction') == 2 ? 'switch__circle-rtl' : 'switch__circle' }}"><span
                                                                            class="switch__circle-inner"></span></span>
                                                                    <span
                                                                        class="switch__left {{ session()->get('direction') == 2 ? 'pe-2' : 'ps-2' }}">{{ trans('labels.off') }}</span>
                                                                    <span
                                                                        class="switch__right {{ session()->get('direction') == 2 ? 'ps-2' : 'pe-2' }}">{{ trans('labels.on') }}</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="col-md-3 form-group">
                                                        <label class="form-label"
                                                            for="">{{ trans('labels.online_order') }}
                                                        </label>
                                                        <div class="text-center">
                                                            <input id="online_order_switch" type="checkbox"
                                                                class="checkbox-switch" name="online_order_switch"
                                                                value="1"
                                                                {{ $settingdata->online_order == 1 ? 'checked' : '' }}>
                                                            <label for="online_order_switch" class="switch">
                                                                <span
                                                                    class="{{ session()->get('direction') == 2 ? 'switch__circle-rtl' : 'switch__circle' }}"><span
                                                                        class="switch__circle-inner"></span></span>
                                                                <span
                                                                    class="switch__left {{ session()->get('direction') == 2 ? 'pe-2' : 'ps-2' }}">{{ trans('labels.off') }}</span>
                                                                <span
                                                                    class="switch__right {{ session()->get('direction') == 2 ? 'ps-2' : 'pe-2' }}">{{ trans('labels.on') }}</span>
                                                            </label>
                                                        </div>

                                                    </div>
                                                    <div class="form-group col-sm-6">
                                                        <label
                                                            class="form-label">{{ trans('labels.landing_page_cover_image') }}
                                                        </label>
                                                        <input type="file" class="form-control"
                                                            name="landin_page_cover_image">

                                                        <img class="img-fluid rounded hw-70 mt-1 object-fit-cover"
                                                            src="{{ helper::image_path($settingdata->cover_image) }}"
                                                            alt="">
                                                    </div>
                                                    <div class="form-group col-sm-6">
                                                        <label
                                                            class="form-label">{{ trans('labels.subscribe_image') }}</label>
                                                        <input type="file" class="form-control"
                                                            name="subscribe_image">


                                                        <img class="img-fluid rounded hw-70 mt-1 object-fit-cover"
                                                            src="{{ helper::image_path(@$settingdata->subscribe_image) }}"
                                                            alt="">
                                                    </div>
                                                    <div class="form-group col-sm-6">
                                                        <label
                                                            class="form-label">{{ trans('labels.order_detail_image') }}</label>
                                                        <input type="file" class="form-control"
                                                            name="order_detail_image">

                                                        <img class="img-fluid rounded hw-70 mt-1 object-fit-cover"
                                                            src="{{ helper::image_path(@$settingdata->order_detail_image) }}"
                                                            alt="">
                                                    </div>
                                                    <div class="form-group col-sm-6">
                                                        <label
                                                            class="form-label">{{ trans('labels.order_success_image') }}</label>
                                                        <input type="file" class="form-control"
                                                            name="order_success_image">

                                                        <img class="img-fluid rounded hw-70 mt-1 object-fit-cover"
                                                            src="{{ helper::image_path(@$settingdata->order_success_image) }}"
                                                            alt="">
                                                    </div>
                                                    <div class="form-group col-sm-6">
                                                        <label
                                                            class="form-label">{{ trans('labels.no_data_image') }}</label>
                                                        <input type="file" class="form-control" name="no_data_image">

                                                        <img class="img-fluid rounded hw-70 mt-1 object-fit-cover"
                                                            src="{{ helper::image_path(@$settingdata->no_data_image) }}"
                                                            alt="">
                                                    </div>
                                                @endif
                                                @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                                                    <div class="form-group col-sm-6">
                                                        <label
                                                            class="form-label">{{ trans('labels.landing_home_banner') }}</label>
                                                        <input type="file" class="form-control"
                                                            name="landing_home_banner">
                                                        <img class="img-fluid rounded hw-70 mt-1 object-fit-cover"
                                                            src="{{ helper::image_path(@$landingdata->landing_home_banner) }}"
                                                            alt="">
                                                    </div>
                                                    <div class="form-group col-sm-6">
                                                        <label
                                                            class="form-label">{{ trans('labels.subscribe_image') }}</label>
                                                        <input type="file" class="form-control"
                                                            name="subscribe_image">
                                                        <img class="img-fluid rounded hw-70 mt-1 object-fit-cover"
                                                            src="{{ helper::image_path(@$landingdata->subscribe_image) }}"
                                                            alt="">
                                                    </div>
                                                    <div class="form-group col-sm-6">
                                                        <label class="form-label">{{ trans('labels.faq_image') }}</label>
                                                        <input type="file" class="form-control" name="faq_image">
                                                        <img class="img-fluid rounded hw-70 mt-1 object-fit-cover"
                                                            src="{{ helper::image_path(@$landingdata->faq_image) }}"
                                                            alt="">
                                                    </div>
                                                    <div class="form-group col-sm-6">
                                                        <label
                                                            class="form-label">{{ trans('labels.maintenance_image') }}</label>
                                                        <input type="file" class="form-control"
                                                            name="maintenance_image">
                                                        <img class="img-fluid rounded hw-70 mt-1 object-fit-cover"
                                                            src="{{ helper::image_path(@$settingdata->maintenance_image) }}"
                                                            alt="">
                                                    </div>
                                                    <div class="form-group col-sm-6">
                                                        <label
                                                            class="form-label">{{ trans('labels.store_unavailable_image') }}</label>
                                                        <input type="file" class="form-control"
                                                            name="store_unavailable_image">
                                                        <img class="img-fluid rounded hw-70 mt-1 object-fit-cover"
                                                            src="{{ helper::image_path(@$settingdata->store_unavailable_image) }}"
                                                            alt="">
                                                    </div>
                                                    <div class="form-group col-sm-6">
                                                        <label
                                                            class="form-label">{{ trans('labels.admin_auth_pages_bg_image') }}</label>
                                                        <input type="file" class="form-control"
                                                            name="admin_auth_pages_bg_image">
                                                        <img class="img-fluid rounded hw-70 mt-1 object-fit-cover"
                                                            src="{{ helper::image_path(@$settingdata->admin_auth_pages_bg_image) }}"
                                                            alt="">
                                                    </div>
                                                @endif
                                                <div
                                                    class="form-group {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                                    <button
                                                        class="btn btn-primary px-sm-4  {{ Auth::user()->type == 4 ? (helper::check_access('role_basic_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
                                                        @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif>{{ trans('labels.save') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ url('storage/app/public/admin-assets/js/user.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.12.1/ckeditor.js"></script>
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/funfact.js') }}"></script>
    <script src="{{ url(env('ASSETPATHURL') . 'admin-assets/js/settings.js') }}"></script>
    <script>
        // handleFormSubmit('#ai_btn',
        //     '<i class="fa-solid fa-spinner fa-spin"></i>');
        $(document).ready(function() {

            function toggleAiButton() {
                var titleVal = $('#meta_title').val();

                if (titleVal && titleVal.trim() !== '') {
                    $('#ai_btn').removeClass('d-none').show();
                } else {
                    $('#ai_btn').addClass('d-none').hide();
                }
            }

            // Page load par check
            toggleAiButton();

            // Jab user type kare
            $('#meta_title').on('input keyup change', function() {
                toggleAiButton();
            });

        });

        function ai_generate() {
            let btn = $('#ai_btn');
            let originalHtml = btn.html();
            let metaTitle = $('#meta_title').val();

            // 1. Loader Start: Disable button and show spinner
            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>');

            $.ajax({
                url: "{{ url('admin/seo/ai_generate') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    meta_title: metaTitle
                },
                success: function(response) {
                    if (response.success) {
                        // Fill data in fields
                        $('#meta_title').val(response.meta_title);
                        $('textarea[name="meta_description"]').val(response.meta_description);
                        toastr.success("{{ trans('labels.seo_content_generated_successfully') }}");
                    } else {
                        // Show specific error from backend (Invalid Key, 503, etc.)
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    // Network or Request errors
                    toastr.error("{{ trans('labels.request_failed_please_check_your_internet_connection_or_API_setup') }}");
                },
                complete: function() {
                    // 2. Loader Stop: Reset button state whether success or error
                    btn.prop('disabled', false).html(originalHtml);
                }
            });
        }
    </script>
@endsection
