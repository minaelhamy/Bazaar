CKEDITOR.replace("cname_text");
$(".basicinfo").on("click", function () {
  "use strict";
  $("#settingmenuContent").find(".card").attr("style", "");
  if (
    $(this).attr("data-tab") == "basicinfo" ||
    $(this).attr("data-tab") == "theme_settings"
  ) {
    $("html, body").animate(
      {
        scrollTop: 0
      },
      "1000"
    );
  } else {
    if (!$(this).is(":last-child")) {
      $("#" + $(this).attr("data-tab"))
        .find(".card")
        .attr("style", "margin-top: 80px;");
    }
  }
  $(".list-options").find(".active").removeClass("active");
  $(this).addClass("active");
});

$(document).ready(function () {
  $('#templatemenuContent').find('.hidechild').addClass('d-none');

  $('#templatemenuContent :first-child').removeClass('d-none');
});

$('#template_type').on('change', function () {
  $('#templatemenuContent').find('.hidechild').addClass('d-none');
  $('#templatemenuContent').find('textarea').prop('required', false);
  $('#' + $(this).find(':selected').data('attribute')).removeClass('d-none');
  $('#' + $(this).find(':selected').data('attribute')).find('textarea').prop('required', true);
}).change();

//Safe & Secure Checkout
$('.payment-checkbox').on('change', function () {
  var checkedCount = $('.payment-checkbox:checked').length;

  // If 4 checkboxes are already selected, disable others
  if (checkedCount >= 6) {
    $('.payment-checkbox').each(function () {
      if (!$(this).is(':checked')) {
        $(this).prop('disabled', true); // Disable unchecked checkboxes
      }
    });
  } else {
    $('.payment-checkbox').prop('disabled', false); // Enable all checkboxes
  }
}).change();


function show_feature_icon(x) {
  "use strict";
  $(x).next().html($(x).val());
}
var id = 1;
function add_features(icon, title, description) {
  "use strict";
  var html =
    '<div class="row remove' +
    id +
    '"><div class="col-md-4 form-group"><div class="input-group"><input type="text" class="form-control feature_icon" onkeyup="show_feature_icon(this)" name="feature_icon[]" placeholder="' +
    icon +
    '" required><p class="input-group-text"></p></div></div><div class="col-md-4 form-group"><input type="text" class="form-control" name="feature_title[]" placeholder="' +
    title +
    '" required></div><div class="col-md-4 d-flex gap-2 gap-sm-4 form-group"><input type="text" class="form-control" name="feature_description[]" placeholder="' +
    description +
    '" required><button class="btn btn-danger" type="button" onclick="remove_features(' +
    id +
    ')"><i class="fa fa-trash"></i></button></div></div>';
  $(".extra_footer_features").append(html);
  $(".feature_required").prop("required", true);
  id++;
}
function remove_features(id) {
  "use strict";
  $(".remove" + id).remove();
  if ($(".extra_footer_features .row").length == 0) {
    $(".feature_required").prop("required", false);
  }
}
function editimage(id) {
  "use strict";
  $("#image_id").val(id);
  $("#editModal").modal("show");
}
var id = 1;
function add_social_links(icon, link) {
  "use strict";
  var html =
    '<div class="row remove' +
    id +
    '"><div class="col-md-6 form-group"><div class="input-group"><input type="text" class="form-control feature_icon" onkeyup="show_feature_icon(this)" name="social_icon[]" placeholder="' +
    icon +
    '" required><p class="input-group-text"></p></div></div><div class="col-md-6 gap-sm-4 gap-2 d-flex align-items-center form-group"><input type="text" class="form-control" name="social_link[]" placeholder="' +
    link +
    '" required><button class="btn btn-danger" type="button" onclick="remove_features(' +
    id +
    ')"><i class="fa fa-trash"></i></button></div></div>';
  $(".extra_social_links").append(html);
  $(".soaciallink_required").prop("required", true);
  id++;
}

$("#checkout_login_required-switch").on("change", function (e) {
  if (this.checked) {
    $("#is_checkout_login_required").removeClass("d-none");
    $('#checkout_login_required').removeClass('col-md-6');
    $('#checkout_login_required').addClass('col-md-3');
  } else {
    $("#is_checkout_login_required").addClass("d-none");
    $('#checkout_login_required').removeClass('col-md-3');
    $('#checkout_login_required').addClass('col-md-6');
  }
}).change();
