<section class="newsletter my-5">
    <div class="container">
        <div class="col bg-light bg-change-mode p-3 rounded-2">
            <div class="row align-items-center justify-content-between">
                <div class="col-lg-6 col-md-7 col-12">
    
                    <h2 class="text-capitalize color-changer mt-4 mb-3 fw-bold">{{ trans('labels.subscribe_title') }}</h2>
    
                    <p>{{ trans('labels.subscribe_sub_title') }}</p>
    
                    <form action="{{ helper::storefront_url($storeinfo, '/subscribe') }}" method="POST">
    
                        @csrf
    
    
    
                        <div class="form-floating d-sm-flex pb-4">
    
                            <input type="email" class="w-100 p-3 border color-input rounded-2 mb-3 mb-sm-0" id="emailsubscribe"
                                placeholder="{{ trans('labels.email') }}" name="subscribe_email" required>
    
                            <button type="submit" id="btnsubscribe"
                                class="btn btn-store mx-md-2 mb-0 btn-100">{{ trans('labels.subscribe') }}</button>
    
                        </div>
    
                    </form>
    
                </div>
                <div class="newsletter-img col-4 d-md-block d-none">
    
                    <img src="{{ helper::image_path(helper::appdata($storeinfo->id)->subscribe_image) }}" class="w-100">
    
                </div>
            </div>
        </div>
    </div>
</section>
