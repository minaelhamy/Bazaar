
function show_funfact_icon(x){

    "use strict";

    $(x).next().html($(x).val())

}

var id = 1;

function add_funfact(icon, title, description) {

    "use strict";

    var html = '<div class="row remove' + id + '"><div class="col-md-4 form-group"><div class="input-group"><input type="text" class="form-control feature_icon" onkeyup="show_funfact_icon(this)" name="funfact_icon[]" placeholder="' + icon + '" required><p class="input-group-text"></p></div></div><div class="col-md-4 form-group"><input type="text" class="form-control" name="funfact_title[]" placeholder="' + title + '" required></div><div class="col-md-4 form-group d-flex gap-2 gap-sm-4"><input type="text" class="form-control" name="funfact_subtitle[]" placeholder="' + description + '" required><div class="col-auto form-group m-0" > <button class="btn btn-danger" type="button" onclick="remove_funfcat(' + id + ')"><i class="fa fa-trash"></i></button></div ></div></div> ';

    $('.extra_footer_features').append(html);

    $(".feature_required").prop('required',true);

    id++;

}

function remove_funfcat(id) {

    "use strict";

    $('.remove' + id).remove();

    if ($('.extra_footer_features .row').length == 0) {

        $(".feature_required").prop('required',false);

    }

}