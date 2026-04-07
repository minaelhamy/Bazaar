@extends('admin.layout.auth_default')
@section('content')
    <section>
        <div class="row align-items-center g-0 w-100 h-100vh position-relative">
            <div class="col-xl-7 col-lg-6 col-md-6 d-md-block d-none">
                <img src="{{ helper::image_path(helper::appdata('')->admin_auth_pages_bg_image) }}" alt=""
                    class="object h-100vh w-100">
            </div>
            <div class="col-xl-5 col-lg-6 col-md-6">
                <div class="login-right-content register-padding">
                    <div class="pb-0 px-0  d-flex flex-column justify-content-xl-center h-100">
                        <div class="text-primary d-flex justify-content-between">
                            <div>
                                <h2 class="fw-bold color-changer title-text text-color mb-2">{{ trans('labels.register') }}
                                </h2>
                                <p class="text-color color-changer">{{ trans('labels.create_sub_title') }}</p>
                            </div>
                            <!-- FOR SMALL DEVICE TOP CATEGORIES -->
                            @if (helper::available_language('')->count() > 1)
                                @if (@helper::checkaddons('language'))
                                    <div class="lag-btn dropdown border-0 shadow-none login-lang">
                                        <button class="border-0 bg-transparent language-dropdown" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <img src="{{ helper::image_path(session()->get('flag')) }}" alt=""
                                                class="lag-img rounded-circle w-25">
                                        </button>
                                        <ul
                                            class="dropdown-menu rounded-1 mt-1 p-0 bg-body-secondary shadow border-0 rounded-3 overflow-hidden">
                                            @foreach (helper::listoflanguage() as $languagelist)
                                                <li>
                                                    <a class="dropdown-item text-dark d-flex align-items-center px-2 gap-2 py-2"
                                                        href="{{ URL::to('/lang/change?lang=' . $languagelist->code) }}">
                                                        <img src="{{ helper::image_path($languagelist->image) }}"
                                                            alt="" class="img-fluid lag-img w-25">
                                                        {{ $languagelist->name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            @endif
                        </div>
                        <form class="my-3" method="POST" action="{{ URL::to('admin/register_vendor') }}">
                            @csrf
                            <div class="row">
                                <div class="form-group col-6">
                                    <label for="name" class="form-label">{{ trans('labels.name') }}<span
                                            class="text-danger"> * </span></label>
                                    <input type="text" class="form-control extra-padding" name="name"
                                        value="{{ old('name') }}" id="name" placeholder="{{ trans('labels.name') }}"
                                        required>
                                </div>
                                <div class="form-group col-6">
                                    <label for="email" class="form-label">{{ trans('labels.email') }}<span
                                            class="text-danger"> * </span></label>
                                    <input type="email" class="form-control extra-padding" name="email"
                                        value="{{ old('email') }}" id="email"
                                        placeholder="{{ trans('labels.email') }}" required>

                                </div>
                                <div class="form-group col-6">
                                    <label for="mobile" class="form-label">{{ trans('labels.mobile') }}<span
                                            class="text-danger"> * </span></label>
                                    <input type="text" class="form-control extra-padding mobile-number" name="mobile"
                                        value="{{ old('mobile') }}" id="mobile"
                                        placeholder="{{ trans('labels.mobile') }}" required>

                                </div>
                                <div class="form-group  col-6 mb-lg-2 mb-0">
                                    <label for="password"
                                        class="form-label fs-7 text-color">{{ trans('labels.password') }}<span
                                            class="text-danger"> * </span></label>
                                    <div class="form-control extra-padding d-flex align-items-center gap-3">
                                        <input type="password" class="form-control text-color border-0 p-0" name="password"
                                            value="{{ old('password') }}" id="password"
                                            placeholder="{{ trans('labels.password') }}" required>
                                        <span>
                                            <a href="#"><i class="fa-light fa-eye-slash color-changer"
                                                    id="eye"></i></a>
                                        </span>
                                    </div>

                                </div>
                                <div class="form-group col-6">
                                    <label for="country" class="form-label">{{ trans('labels.country') }}<span
                                            class="text-danger"> * </span></label>
                                    <select name="country" class="form-select extra-padding" id="country" required>
                                        <option value="">{{ trans('labels.select') }}</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="form-group col-6">
                                    <label for="city" class="form-label">{{ trans('labels.city') }}<span
                                            class="text-danger"> * </span></label>
                                    <select name="city" class="form-select extra-padding" id="city" required>
                                        <option value="">{{ trans('labels.select') }}</option>
                                    </select>

                                </div>

                                @if (@helper::checkaddons('digital_product'))
                                    <div class="form-group col-md-6">
                                        <label for="store"
                                            class="form-label">{{ trans('labels.store_categories') }}<span
                                                class="text-danger"> * </span></label>
                                        <select name="store" class="form-select extra-padding" id="store" required>
                                            <option value="">{{ trans('labels.select') }}</option>
                                            @foreach ($stores as $store)
                                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="product_type"
                                            class="form-label">{{ trans('labels.product_type') }}<span
                                                class="text-danger">
                                                * </span></label>
                                        <select name="product_type" class="form-select extra-padding" required>
                                            <option value="">{{ trans('labels.select') }}</option>
                                            <option value="1" {{ old('store') == 1 ? 'selected' : '' }}>
                                                {{ trans('labels.physical') }}</option>
                                            <option value="2" {{ old('store') == 2 ? 'selected' : '' }}>
                                                {{ trans('labels.digital') }}</option>
                                        </select>

                                    </div>
                                @else
                                    <div class="form-group col-md-12">
                                        <label for="store"
                                            class="form-label">{{ trans('labels.store_categories') }}<span
                                                class="text-danger"> * </span></label>
                                        <select name="store" class="form-select extra-padding" id="store" required>
                                            <option value="">{{ trans('labels.select') }}</option>
                                            @foreach ($stores as $store)
                                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                @if (@helper::checkaddons('unique_slug'))
                                    <div class="form-group">
                                        <label for="basic-url"
                                            class="form-label">{{ trans('labels.personlized_link') }}<span
                                                class="text-danger"> * </span></label>
                                        @if (env('Environment') == 'sendbox')
                                            <span
                                                class="badge badge bg-danger ms-2 mb-0">{{ trans('labels.addon') }}</span>
                                        @endif
                                        <div class="input-group ">
                                            <span
                                                class="input-group-text col-5 overflow-x-auto {{ session()->get('direction') == 2 ? 'rounded-start-0 rounded-end' : 'rounded-end-0' }}">{{ URL::to('/') }}/</span>
                                            <input type="text"
                                                class="form-control {{ session()->get('direction') == 2 ? 'rounded-end-0 rounded-start' : 'rounded-start-0' }} extra-padding"
                                                id="slug" name="slug" value="{{ old('slug') }}" required>
                                        </div>

                                    </div>
                                @endif
                                <div class="form-group">
                                    <input class="form-check-input" type="checkbox" value="" name="check_terms"
                                        id="check_terms" checked required>
                                    <label class="form-check-label" for="check_terms">
                                        {{ trans('labels.i_accept_the') }} <span class="fw-600"><a
                                                href="{{ URL::to('/termscondition') }}" target="_blank"
                                                class="text-secondary">{{ trans('labels.terms') }}</a> </span>
                                    </label>
                                </div>
                            </div>

                            @include('landing.layout.recaptcha')

                            <div class="d-flex gap-2">
                                <a href ="{{ URL::to('/admin') }}"
                                    class="btn btn-primary padding w-100 mt-3 mb-3">{{ trans('labels.login') }}</a>
                                <button class="btn btn-secondary padding w-100 mt-3 mb-3"
                                    @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif>{{ trans('labels.register') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if (env('Environment') == 'sendbox')
        <button class="btn btn-primary theme-label text-white" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">

            <i class="fa-solid fa-list text-white px-2"></i>
            Themes</button>

        <div class="offcanvas {{ session()->get('direction') == 2 ? 'offcanvas-start' : 'offcanvas-end ' }}"
            tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header justify-content-between border-bottom">
                <h5 id="offcanvasRightLabel" class="color-changer">Themes</h5>
                <button type="button" class="bg-transparent border-0 m-0" data-bs-dismiss="offcanvas"
                    aria-label="Close">
                    <i class="fa-regular fa-xmark fs-4 color-changer"></i>
                </button>
            </div>
            <div class="offcanvas-body">
                <div class="row px-3">
                    <a href="https://store-mart.paponapps.co.in/theme-1" target="_blank"
                        class="card h-100 them-card-box overflow-hidden mb-3 rounded-5 border-0 p-0">
                        <img src="{{ helper::image_path('theme-1.png') }}" class="card-img-top them-name-images">
                        <div class="card-body">
                            <h5 class="card-title text-center color-changer">Theme - 1</h5>
                        </div>
                    </a>

                    <a href="https://store-mart.paponapps.co.in/theme-2" target="_blank"
                        class="card h-100 them-card-box overflow-hidden mb-3 rounded-5 border-0 p-0">
                        <img src="{{ helper::image_path('theme-2.png') }}" class="card-img-top them-name-images">
                        <div class="card-body">
                            <h5 class="card-title text-center color-changer">Theme - 2</h5>
                        </div>
                    </a>

                    <a href="https://store-mart.paponapps.co.in/theme-3" target="_blank"
                        class="card h-100 them-card-box overflow-hidden mb-3 rounded-5 border-0 p-0">
                        <img src="{{ helper::image_path('theme-3.png') }}" class="card-img-top them-name-images">
                        <div class="card-body">
                            <h5 class="card-title text-center color-changer">Theme - 3</h5>
                        </div>
                    </a>

                    <a href="https://store-mart.paponapps.co.in/theme-4" target="_blank"
                        class="card h-100 them-card-box overflow-hidden mb-3 rounded-5 border-0 p-0">
                        <img src="{{ helper::image_path('theme-4.png') }}" class="card-img-top them-name-images">
                        <div class="card-body">
                            <h5 class="card-title text-center color-changer">Theme - 4</h5>
                        </div>
                    </a>

                    <a href="https://store-mart.paponapps.co.in/theme-5" target="_blank"
                        class="card h-100 them-card-box overflow-hidden mb-3 rounded-5 border-0 p-0">
                        <img src="{{ helper::image_path('theme-5.png') }}" class="card-img-top them-name-images">
                        <div class="card-body">
                            <h5 class="card-title text-center color-changer">Theme - 5</h5>
                        </div>
                    </a>

                    <a href="https://store-mart.paponapps.co.in/theme-6" target="_blank"
                        class="card h-100 them-card-box overflow-hidden mb-3 rounded-5 border-0 p-0">
                        <img src="{{ helper::image_path('theme-6.png') }}" class="card-img-top them-name-images">
                        <div class="card-body">
                            <h5 class="card-title text-center color-changer">Theme - 6</h5>
                        </div>
                    </a>

                    <a href="https://store-mart.paponapps.co.in/theme-7" target="_blank"
                        class="card h-100 them-card-box overflow-hidden mb-3 rounded-5 border-0 p-0">
                        <img src="{{ helper::image_path('theme-7.png') }}" class="card-img-top them-name-images">
                        <div class="card-body">
                            <h5 class="card-title text-center color-changer">Theme - 7</h5>
                        </div>
                    </a>

                    <a href="https://store-mart.paponapps.co.in/theme-8" target="_blank"
                        class="card h-100 them-card-box overflow-hidden mb-3 rounded-5 border-0 p-0">
                        <img src="{{ helper::image_path('theme-8.png') }}" class="card-img-top them-name-images">
                        <div class="card-body">
                            <h5 class="card-title text-center color-changer">Theme - 8</h5>
                        </div>
                    </a>

                    <a href="https://store-mart.paponapps.co.in/theme-9" target="_blank"
                        class="card h-100 them-card-box overflow-hidden mb-3 rounded-5 border-0 p-0">
                        <img src="{{ helper::image_path('theme-9.png') }}" class="card-img-top them-name-images">
                        <div class="card-body">
                            <h5 class="card-title text-center color-changer">Theme - 9</h5>
                        </div>
                    </a>

                    <a href="https://store-mart.paponapps.co.in/theme-10" target="_blank"
                        class="card h-100 them-card-box overflow-hidden mb-3 rounded-5 border-0 p-0">
                        <img src="{{ helper::image_path('theme-10.png') }}" class="card-img-top them-name-images">
                        <div class="card-body">
                            <h5 class="card-title text-center color-changer">Theme - 10</h5>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    @endif
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
        function AdminFill(email, password) {
            $('#email').val(email);
            $('#password').val(password);
        }
        // password eye hide
        $(function() {
            $('#eye').click(function() {
                if ($(this).hasClass('fa-eye-slash')) {
                    $(this).removeClass('fa-eye-slash');
                    $(this).addClass('fa-eye');
                    $('#password').attr('type', 'text');
                } else {
                    $(this).removeClass('fa-eye');
                    $(this).addClass('fa-eye-slash');
                    $('#password').attr('type', 'password');
                }
            });
        });
    </script>
    <script>
        var cityurl = "{{ URL::to('admin/getcity') }}";
        var select = "{{ trans('labels.select') }}";
        var cityid = "0";
    </script>
    <script src="{{ url(env('ASSETPATHURL') . '/admin-assets/js/user.js') }}"></script>
@endsection
