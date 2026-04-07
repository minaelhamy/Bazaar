@if (helper::appdata($storeinfo->id)->fake_sales_notification == 1)
<div class="d-lg-block d-none">
    <div id="sales-booster-popup" class="animation-slide_right {{ helper::appdata($storeinfo->id)->sales_notification_position == '2' ? 'rtl' : '' }} rounded-3" style="display:none">
        <span class="close pos-absolute top {{ session()->get('direction') == '2' ? 'left' : 'right' }}">
            <i class="fa-light fa-xmark"></i>
        </span>
        <div class="sales-booster-popup-inner" id="notification_body">
            
        </div>
    </div>
</div>
@endif