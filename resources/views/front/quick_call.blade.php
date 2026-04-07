<!------ Quick call start ------>
<input type="checkbox" id="quick_call">
<label
    class="quick-btn {{ helper::appdata($storeinfo->id)->quick_call_position == '1' ? 'quick-btn_rtl' : 'quick-btn_ltr' }}"
    id="quick-btn" for="quick_call">
    <div class="comment">
        <div class="d-flex gap-2 align-items-center">
            <i class="fa-solid fa-phone fs-5"></i>
        </div>
    </div>
    <i class="fa fa-close close"></i>
</label>

<div class="shadow card {{ helper::appdata($storeinfo->id)->quick_call_position == '1' ? 'quick_call_rtl' : 'quick_call' }}">
    <div class="call_info pb-0">
        @if (helper::appdata($storeinfo->id)->quick_call_image != '')
            <img src="{{ helper::image_path(helper::appdata($storeinfo->id)->quick_call_image) }}"
                class="caller_img mx-auto" alt="">
        @endif
        <h6 class="color-changer">{{ helper::appdata($storeinfo->id)->quick_call_name }}</h6>
        <p class="text-center color-changer mb-0 mt-1 fs-8">{{ helper::appdata($storeinfo->id)->quick_call_description }}</p>
    </div>
    <div class="p-3">
        <div class="text-center bg-secondary-rgb rounded-3 py-2 w-100">
            <a href="tel:{{ helper::appdata($storeinfo->id)->quick_call_mobile }}" class="text-dark color-changer">
                <i class="fa-solid fa-phone"></i> {{ helper::appdata($storeinfo->id)->quick_call_mobile }}
            </a>
        </div>
    </div>
</div>
