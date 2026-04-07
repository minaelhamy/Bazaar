@extends('landing.layout.default')

@section('content')
    <!-- BREADCRUMB AREA START -->
    <section class="breadcrumb-sec bg-light bg-changer">
        <div class="container">
            <nav aria-label="breadcrumb">
                {{-- <h3 class="breadcrumb-title fw-semibold mb-2 text-center">{{ trans('landing.faq_section_title') }}</h3> --}}
                <ol class="breadcrumb">
                    <li class="{{ session()->get('direction') == 2 ? 'breadcrumb-item-rtl' : ' breadcrumb-item ' }}"><a
                            class="text-dark color-changer" href="{{ helper::storefront_url($vendordata) }}">{{ trans('labels.home') }}</a>
                    </li>
                    <li class="text-muted {{ session()->get('direction') == 2 ? 'breadcrumb-item-rtl' : ' breadcrumb-item ' }}"
                        aria-current="page">{{ trans('landing.faq_section_title') }}</li>
                </ol>
            </nav>
        </div>
    </section>
    <section>
        <div class="container faq-container faq">
            <div class="row mt-3">
                
                <div class="col-lg-7">
                    <div class="accordion" id="accordionExample">
                        @foreach ($allfaqs as $key => $faq)
                            <div class="accordion-item border-0 bg-transparent {{ $key == 0 ? 'pt-0' : 'pt-2' }}">
                                <h2 class="accordion-header" id="heading-{{ $key }}">
                                    <button
                                        class="{{ session()->get('direction') == 2 ? 'accordion-button-rtl text-end' : 'accordion-button text-start' }} border rounded-3 color-changer faq-btn-bg justify-content-between m-0 {{ $key == 0 ? '' : 'collapsed' }}"
                                        type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $key }}"
                                        aria-expanded="true" aria-controls="collapse-{{ $key }}">
                                        {{ $faq->question }}
                                    </button>
                                </h2>
                                <div id="collapse-{{ $key }}"
                                    class="accordion-collapse border rounded-2 collapse mt-2 {{ $key == 0 ? 'show bg-black' : '' }}"
                                    aria-labelledby="heading-{{ $key }}" data-bs-parent="#accordionExample">
                                    <div class="accordion-body rounded-1 bg-changer">
                                        <p class="faq-accordion-lorem-text pt-2 fs-7 color-changer">
                                            {{ $faq->answer }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-5 d-lg-block d-none">
                    <img src="{{ @helper::image_path( helper::landingsettings()->faq_image) }}" alt=""
                        class="w-100 faq-img">
                </div>
            </div>
        </div>
    </section>
    <!-- subscription -->
    @include('landing.newslatter')  
@endsection
