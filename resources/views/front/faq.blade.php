@include('front.theme.header')
<section class="breadcrumb-sec bg-change-mode">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-dark"><a class="text-dark color-changer"
                        href="{{ helper::storefront_url($storeinfo, '/') }}">{{ trans('labels.home') }}</a>
                </li>
                <li class="text-muted breadcrumb-item {{ session()->get('direction') == 2 ? 'rtl' : '' }} text-dark active" aria-current="page">{{ trans('labels.faqs') }}
                </li>
            </ol>
        </nav>
    </div>
</section>
<section class="mb-5 faqs">
    <div class="container">
        @if (helper::getfaqs($storeinfo->id)->count() > 0)
            <div class="accordion rounded-0 faq-accordion" id="accordionExample">
                @foreach (helper::getfaqs($storeinfo->id) as $key => $faq)
                    <div class="accordion-item rounded-0 bg-transparent mb-3">
                        <h2 class="accordion-header">
                            <button
                                class="accordion-button shadow-none justify-content-between m-0 {{ $key != 0 ? 'collapsed' : '' }}"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $key }}"
                                aria-expanded="true" aria-controls="collapse{{ $key }}">
                                {{ $faq->question }}
                            </button>
                        </h2>
                        <div id="collapse{{ $key }}"
                            class="accordion-collapse collapse rounded-0 {{ $key == 0 ? 'show' : '' }}"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body bg-changer m-0">
                                <p class="color-changer">{{ $faq->answer }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            @include('front.no_data')
        @endif

    </div>
</section>
<!-- newsletter -->
@include('front.newsletter')
<!-- newsletter -->
@include('front.theme.footer')
