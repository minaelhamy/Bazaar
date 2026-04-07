$('.type').on('change', function() {
    "use strict";
    if ($('.type').val() == '1') {
        $('#usage_limit_input').show();
    } else {
        $('#usage_limit_input').hide();
    }
}).change();
$('#start_date').on('change',function() {
    "use strict";
    if (new Date($('#start_date').val()) > new Date($('#end_date').val())) {
        $('#start_date').val('');
        toastr.error('start date must be less end date !!');
    }
   
});
$('#end_date').on('change',function() {
    "use strict";
    if (new Date($('#start_date').val()) > new Date($('#end_date').val())) {
        $('#end_date').val('');
        toastr.error('start date must be less end date !!');
    }
});