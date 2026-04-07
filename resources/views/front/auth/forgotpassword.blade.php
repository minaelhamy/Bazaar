@extends('admin.layout.auth_default')
@section('content')
<div class="wrapper">
    <section>
            <div class="container">
                <div class="d-flex justify-between align-items-center w-100 h-100vh">
                    <div class="row justify-content-around align-items-center g-0 w-100 py-5 rounded-4 box-shadow login-form-bg-color">
                        <div class="col-xl-4 col-lg-6 col-sm-8 col-auto d-lg-block d-none">
                            <img src="{{ url(env('ASSETPATHURL') . '/admin-assets/images/login-form.png') }}" class="login-page-img" alt="">
                        </div>
                        <div class="col-xl-4 col-lg-6 col-sm-8 col-auto login-form-box">
                            <div class="card overflow-hidden border-0 rounded-0">
                                <div class="row">
                                    <a href="{{ URL::to($slug) }}" class="logo p-0 d-flex justify-content-center align-items-center ">
                                        <img src="{{ helper::image_path(helper::appdata(@$storeinfo->id)->logo) }}" alt="" class="rounded-circle mb-4 login-imag object-fit-cover">
                                    </a>
                                    <h4 class="text-center">{{ trans('labels.forgot_password') }}</h4>
                                </div>
                                <div class="card-body pt-0 p-2">
                                    <form class="my-3" method="POST" action="{{ URL::to($slug.'/send_password') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label for="email" class="form-label">{{ trans('labels.email') }}<span
                                                    class="text-danger"> * </span></label>
                                            <input type="email" class="form-control" name="email"
                                                placeholder="{{ trans('labels.email') }}" id="email" required>
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <input type="hidden" class="form-control" name="type" value="user">
                                        <div class="text-end">
                                            <a href="{{ URL::to($slug.'/forgotpassword?redirect=user') }}" class="text-muted fs-8 fw-500">
                                                <i
                                                    class="fa-solid fa-lock-keyhole mx-2 fs-7"></i>{{ trans('labels.forgot_password') }}
                                            </a>
                                        </div>
                                        <button class="btn btn-primary w-100 mt-3" @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else 
                                            type="submit" @endif>{{ trans('labels.submit') }}</button>
                                    </form>
                                </div>
                            </div>
                            <p class="fs-7 text-center mt-3">{{ trans('labels.dont_have_account') }}
                                <a href="{{ URL::to($slug.'/login') }}"
                                    class="text-primary fw-semibold">{{ trans('labels.login') }}</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>
@endsection