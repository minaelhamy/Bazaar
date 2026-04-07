@include('front.theme.header')
<!------ breadcrumb ------>
<section class="breadcrumb-sec">

    <div class="container">

        <nav aria-label="breadcrumb">

            <ol class="breadcrumb">

                <li class="breadcrumb-item text-dark"><a class="text-dark color-changer"
                        href="{{ helper::storefront_url($storeinfo) }}">{{trans('labels.home')}}</a>
                </li>

                <li class="text-muted breadcrumb-item {{ session()->get('direction') == 2 ? 'rtl' : '' }} active" aria-current="page">{{ trans('labels.change_password') }}</li>

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
                                <div class="settings-box-header border-bottom px-4 py-3">
                                    <h5 class="mb-0 color-changer"><i class="fa-light fa-unlock"></i><span class="px-3">{{ trans('labels.change_password') }}</span></h5>
                                </div>
                                <div class="settings-box-body p-3">
                                    <form id="deatilsForm" action="{{ helper::storefront_url($storeinfo, '/change_password/') }}" method="POST">
                                        @csrf
                                        <div class="row row-cols-1 row-cols-sm-2 g-3 mb-3">
                                            <div class="col-12">
                                                <label class="form-label label14">{{ trans('labels.current_password') }} : <span class="required">*</span></label>
                                                <input type="password" name="current_password" class="form-control p-3 rounded-2" placeholder="{{ trans('labels.current_password') }}" required="">
                                                @error('current_password')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label label14">{{ trans('labels.new_password') }} : <span class="required">*</span></label>
                                                <input type="password" name="new_password" class="form-control p-3 rounded-2 mb-0" placeholder="{{ trans('labels.new_password') }}" required="">
                                                @error('new_password')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label label14">{{ trans('labels.confirm_password') }} : <span class="required">*</span></label>
                                            <input type="password" name="confirm_password" class="form-control p-3 rounded-2 mb-0" placeholder="{{ trans('labels.confirm_password') }}" required="">
                                            @error('confirm_password')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn-store m-0" id="btnsubmit">{{ trans('labels.submit') }}</button>
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
</section>

<!-- newsletter -->
@include('front.newsletter')
<!-- newsletter -->
@include('front.theme.footer')
