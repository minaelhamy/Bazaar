<footer>
    <div class="footer-bg-color bg-changer overflow-hidden">
        <div class="container footer-container">
            <div class="row g-3">
                <div class="col-xl-4 col-lg-4">
                    <div>
                        <script>
                            document.addEventListener("DOMContentLoaded", function(event) {
                                if (localStorage.getItem('theme') === 'dark') {
                                    var logo = "{{ helper::image_path(helper::appdata('')->darklogo) }}";
                                } else {
                                    var logo = "{{ helper::image_path(helper::appdata('')->logo) }}";
                                }
                                $('#footerlogoimage').attr('src', logo);
                            });
                        </script>
                        <a href="{{ URL::to('/') }}">
                            <img src="" id="footerlogoimage" height="50" alt="">
                        </a>
                        <p class="footer-contain mt-4 fs-6 text-white col-lg-10">
                            {{ trans('landing.footer_description') }}
                        </p>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-6 footer-contain">
                    <p class="widget-head">{{ trans('landing.pages') }}</p>
                    <ul class="list-area m-0 p-0">
                        <li>
                            <a href="{{ URL::to('/aboutus') }}">
                                {{ trans('landing.about_us') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('/privacypolicy') }}">
                                {{ trans('landing.privacy_policy') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('/refund_policy') }}">
                                {{ trans('landing.refund_policy') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('/termscondition') }}">
                                {{ trans('landing.terms_conditions') }}
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-xl-2 col-lg-4 col-6 footer-contain">
                    <div>
                        <p class="widget-head">{{ trans('landing.other') }}</p>
                        <ul class="list-area m-0 p-0">
                            @if (@helper::checkaddons('blog'))
                                @if (helper::getblogs(1)->count() > 0)
                                    <li>
                                        <a href="{{ URL::to('/blogs') }}">
                                            {{ trans('landing.blogs') }}
                                        </a>
                                    </li>
                                @endif
                            @endif
                            <li>
                                <a href="{{ URL::to('/faqs') }}">
                                    {{ trans('landing.faqs') }}
                                </a>
                            </li>
                            @if (@helper::checkaddons('subscription'))
                                <li>
                                    <a href="{{ URL::to('/stores') }}">
                                        {{ trans('landing.our_stores') }}
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="{{ URL::to('/contact') }}">
                                    {{ trans('landing.contact_us') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-4 footer-contain">
                    <div class="contact-box p-sm-5 p-4">
                        <div class="widget-head">{{ trans('landing.help') }}</div>
                        <div class="text mb-4">{{ trans('labels.It is a long established fact that a reader will be distracted layout.') }}
                        </div>
                        <p class="pb-2 fw-500 fs-7">
                            <a href="mailto:{{ helper::appdata('')->email }}"
                                class="d-flex align-items-center text-dark gap-2">
                                <i class="fa-solid fa-envelope fs-5 text-primary-color"></i>
                                {{ helper::appdata('')->email }}
                            </a>
                        </p>
                        <p class="pb-2 fw-500 fs-7">
                            <a href="tel:{{ helper::appdata('')->contact }}"
                                class="d-flex align-items-center text-dark gap-2">
                                <i class="fa-solid fa-phone fs-5 text-primary-color"></i>
                                {{ helper::appdata('')->contact }}
                            </a>
                        </p>
                        <p class="fw-500 fs-7">
                            <a href="#" class="d-flex text-dark align-items-center gap-2">
                                <i class="fa-solid fa-location-dot fs-5 text-primary-color"></i>
                                {{ helper::appdata('')->address }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            <!------ whatsapp_icon ------>
            @if (@helper::checkaddons('whatsapp_message'))
                @if (@whatsapp_helper::whatsapp_message_config('')->whatsapp_chat_on_off == 1)
                    @if (
                        @whatsapp_helper::whatsapp_message_config('')->whatsapp_number != null &&
                            @whatsapp_helper::whatsapp_message_config('')->whatsapp_number != '')
                        <div
                            class="{{ @whatsapp_helper::whatsapp_message_config('')->whatsapp_mobile_view_on_off == 1 ? 'd-block' : 'd-lg-block d-none' }}">
                            <input type="checkbox" id="check" class="d-none">
                            <label
                                class="chat-btn {{ @whatsapp_helper::whatsapp_message_config('')->whatsapp_chat_position == 1 ? 'chat-btn_rtl' : 'chat-btn_ltr' }}"
                                for="check">
                                <i class="fa-brands fa-whatsapp comment"></i>
                                <i class="fa fa-close close"></i>
                            </label>
                            <div
                                class="shadow {{ @whatsapp_helper::whatsapp_message_config('')->whatsapp_chat_position == 1 ? 'wrapper_rtl' : 'wrapper' }}">
                                <div class="msg_header">
                                    <h6>{{ helper::appdata('')->website_title }}</h6>
                                </div>
                                <div class="text-start p-3 bg-msg">
                                    <div class="card p-2 color-changer msg d-inline-block fs-7">
                                        {{ trans('labels.how_can_help_you') }}
                                    </div>
                                </div>
                                <div class="chat-form">
                                    <form action="https://api.whatsapp.com/send" method="get" target="_blank"
                                        class="d-flex align-items-center d-grid gap-2">
                                        <textarea class="form-control m-0" name="text" placeholder="Your Text Message" cols="30" rows="10"
                                            required></textarea>
                                        <input type="hidden" name="phone"
                                            value="{{ @whatsapp_helper::whatsapp_message_config('')->whatsapp_number }}">
                                        <button type="submit" class="btn btn-whatsapp btn-block m-0">
                                            <i class="fa-solid fa-paper-plane"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @endif
            <hr class="text-white mt-5">
            <div class="d-md-flex justify-content-between align-items-center pb-4">
                <h5 class="copy-right-text m-0">{{ helper::appdata('')->copyright }}</h5>
                <ul class="footer_acceped_card d-flex flex-wrap justify-content-center gap-2 p-0 m-0 mt-3 mt-md-0">
                    @foreach (helper::getallpayment(1) as $item)
                        <li>
                            <a href="#">
                                <img src="{{ helper::image_path($item->image) }}" class="w-20px">
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</footer>
