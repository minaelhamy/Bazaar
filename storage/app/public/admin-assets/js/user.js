
$("#allow_store_subscription").on('change',function() {
    "use strict";
    if($(this).prop('checked')) {
       $('#plan').addClass('d-none');
       $('#selectplan').prop("required", false);
       $("#plan_checkbox").prop("checked", false);
    } else {
        $('#plan').removeClass('d-none');
        $('#plan').addClass('d-block');
    }
}).change();

$("#plan_checkbox").on('change',function() {
    "use strict";
    if($(this).prop('checked')) {
        $('#selectplan').prop("disabled", false);
        $('#selectplan').prop("required", true);
    }
    else
    {
        $('#selectplan').prop("disabled", true);
        $('#selectplan').prop("required", false);
    }
}).change();

$("#country").change(function() {
  $("#city").empty();
  var cityselect ;
  $.ajax({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    },
    url: cityurl,
    type: "post",
    dataType: "json",
    data: {
      country: $(this).val()
    },
    success: function(result){
        $('#city').html('<option value="">' + select + '</option>'); 
        $.each(result.city,function(key,value){
          if(cityid == value.id)
          {
             cityselect = "selected"; 
          }
          else
          {
            cityselect ="";
          }
        $("#city").append('<option value="'+ value.id +'"'+ cityselect + '>'+ value.city +'</option>');
        });
        }
  });
}).change();