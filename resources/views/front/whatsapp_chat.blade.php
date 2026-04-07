<div
    class="{{ whatsapp_helper::whatsapp_message_config($storeinfo->id)->whatsapp_mobile_view_on_off == 1 ? 'd-block' : 'd-lg-block d-none' }}">
    <input type="checkbox" id="check">
    <label
        class="chat-btn {{ @whatsapp_helper::whatsapp_message_config($storeinfo->id)->whatsapp_chat_position == 1 ? 'chat-btn_rtl' : 'chat-btn_ltr' }}"
        for="check">
        <i class="fa-brands fa-whatsapp comment"></i>
        <i class="fa fa-close close"></i>
    </label>

    <div
        class="shadow {{ @whatsapp_helper::whatsapp_message_config($storeinfo->id)->whatsapp_chat_position == 1 ? 'wrapper_rtl' : 'wrapper' }}">
        <div class="msg_header">
            <h6>{{ helper::appdata(@$storeinfo->id)->website_title }}</h6>
        </div>

        <div class="text-start p-3 bg-msg">
            <div class="card p-2 color-changer msg d-inline-block fs-7">
                {{ trans('labels.how_can_help_you') }}
            </div>
        </div>

        <div class="chat-form">

            <form action="https://api.whatsapp.com/send" method="get" target="_blank"
                class="d-flex align-items-center d-grid gap-2">
                <textarea class="form-control m-0" name="text" placeholder="{{ trans('messages.your_text_message') }}" cols="30"
                    rows="10" required></textarea>
                <input type="hidden" name="phone"
                    value="{{ @whatsapp_helper::whatsapp_message_config($storeinfo->id)->whatsapp_number }}">
                <button type="submit" class="btn btn-whatsapp btn-block m-0">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>

        </div>
    </div>
</div>
