$(window).on("load", function () {
  "use strict";

  $("#preloader").fadeOut("slow");
  if ($(".multimenu").find(".active")) {
    $(".multimenu").find(".active").parent().parent().addClass("show");
    $(".multimenu")
      .find(".active")
      .parent()
      .parent()
      .parent()
      .attr("aria-expanded", true);
  }
});
var tooltipTriggerList = [].slice.call(
  document.querySelectorAll('[data-bs-toggle="tooltip"]')
);
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  "use strict";
  return new bootstrap.Tooltip(tooltipTriggerEl);
});
$(document).ready(function () {
  "use strict";
  $(".zero-configuration").DataTable({
    dom: "lBfrtip",
    lengthMenu: [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
    buttons: [
      {
        extend: "excelHtml5",
        filename: filename,
      }
    ]
  });

  $("form").on("submit", function () {
    "use strict";
    if (env == "sandbox") {
      myFunction();
      return false;
    }
  });
});
//    <!-- functions -->

$(function () {
  $("#tabledetails").sortable({
    items: "tr",
    cursor: "move",
    opacity: 0.6,
    update: function () {
      sendOrderToServer();
    }
  });

  function sendOrderToServer() {
    var order = [];
    $("tr.row1").each(function (index, element) {
      order.push({
        id: $(this).attr("data-id"),
        position: index + 1
      });
    });

    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
      },
      type: "POST",
      dataType: "json",
      url: $("#tabledetails").attr("data-url"),
      data: {
        order: order
      },
      success: function (response) {
        if (response.status == 1) {
          toastr.success(response.msg);
        } else {
          console.log(response);
        }
      }
    });
  }
});

function myFunction() {
  "use strict";
  toastr.error("This operation was not performed due to demo mode");
  return false;
}
function statusupdate(nexturl) {
  "use strict";
  manegedata(nexturl);
}
function deletedata(nexturl) {
  "use strict";
  manegedata(nexturl);
}
function manegedata(nexturl) {
  "use strict";
  if (env == "sandbox") {
    if (!nexturl.includes("orders") && !nexturl.includes("logout")) {
      myFunction();
      return false;
    }
  }
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: "btn btn-success mx-1",
      cancelButton: "btn btn-danger mx-1"
    },
    buttonsStyling: false
  });
  swalWithBootstrapButtons
    .fire({
      title: are_you_sure,
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: yes,
      cancelButtonText: no,
      reverseButtons: true
    })
    .then(result => {
      if (result.isConfirmed) {
        $("#preloader").show();
        location.href = nexturl;
      } else {
        result.dismiss === Swal.DismissReason.cancel;
      }
    });
}

function is_allow(id, status, title, yes, no, statusurl, wrong, recordsafe) {
  "use strict";
  swal(
    {
      title: title,
      type: "warning",
      showCancelButton: true,
      confirmButtonText: yes,
      cancelButtonText: no,
      closeOnConfirm: false,
      closeOnCancel: false,
      showLoaderOnConfirm: true
    },
    function (isConfirm) {
      if (isConfirm) {
        $.ajax({
          headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
          },
          url: statusurl,
          data: {
            id: id,
            status: status
          },
          method: "POST",
          success: function (response) {
            if (response == 1) {
              swal.close();
              window.location.reload();
            } else {
              swal("Cancelled", wrong, "error");
            }
          },
          error: function (e) {
            swal("Cancelled", wrong, "error");
          }
        });
      } else {
        swal("Cancelled", recordsafe, "error");
      }
    }
  );
}

$(".numbers_only").on("keyup", function () {
  "use strict";
  var val = $(this).val();
  if (isNaN(val)) {
    val = val.replace(/[^0-9\.]/g, "");
    if (val.split(".").length > 2) {
      val = val.replace(/\.+$/, "");
    }
  }
  $(this).val(val);
});

$(".mobile-number").on("keyup", function () {
  "use strict";
  var val = $(this).val();
  if (isNaN(val)) {
    val = val.replace(/[^0-9]/g, "");
    if (val.split(".").length > 2) {
      val = val.replace(/\.+$/, "");
    }
  }
  $(this).val(val);
});

function editcustomerdata(order_id, customer_name, customer_mobile, customer_email, customer_address, customer_building, customer_landmark, customer_pincode, type) {
  "use strict";
  $('#customerinfo').modal('show');
  $('#modal_order_id').val(order_id);

  if (type == "customer_info") {
    $('#customer_name').val(customer_name);
    $('#customer_mobile').val(customer_mobile);
    $('#customer_email').val(customer_email);
    $('#edit_type').val(type);
    $('#delivery_info').hide();
    $('#customer_info').show();
    $('#customer_address').removeAttr('required');
    $('#customer_building').removeAttr('required');
    $('#customer_landmark').removeAttr('required');
    $('#customer_pincode').removeAttr('required');
  } else {
    $('#customer_address').val(customer_address.replace(/[|]+/g, ","));
    $('#customer_building').val(customer_building.replace(/[|]+/g, ","));
    $('#customer_landmark').val(customer_landmark.replace(/[|]+/g, ","));
    $('#customer_pincode').val(customer_pincode);
    $('#edit_type').val(type);
    $('#customer_info').hide();
    $('#delivery_info').show();
    $('#customer_name').removeAttr('required');
    $('#customer_email').removeAttr('required');
    $('#customer_mobile').removeAttr('required');
  }
}

$("#close-btn2").click(function () {
  $(".notice_card").addClass("d-none");
});

$("#close-btn3").click(function () {
  $(".notice_card").addClass("d-none");
});

function setLightMode() {
  document.documentElement.classList.remove('dark');
  document.documentElement.classList.add('light');
  localStorage.setItem('theme', 'light');
  $('#logoimage').attr('src', lightlogo);
  $('#footerlogoimage').attr('src', lightlogo);
}

function setDarkMode() {
  document.documentElement.classList.remove('light');
  document.documentElement.classList.add('dark');
  localStorage.setItem('theme', 'dark');
  $('#logoimage').attr('src', lightlogo);
  $('#footerlogoimage').attr('src', lightlogo);
}

//bulk_delete
  $('#selectAll').on('change', function () {
    $('.row-checkbox').prop('checked', $(this).prop('checked'));
    toggleDeleteButton();
  });

//  Delete Button based on individual selection
  $('.row-checkbox').on('change', function () {
    toggleDeleteButton();
  // Uncheck "Select All" if any is unchecked
        if (!$(this).prop('checked')) {
            $('#selectAll').prop('checked', false);
        }
    });

    function toggleDeleteButton() {
        let anyChecked = $('.row-checkbox:checked').length > 0;
        $('#bulkDeleteBtn').toggleClass('d-none', !anyChecked);
    }

    // Bulk Delete Handler
  function deleteSelected(statusurl) {
    let id = $('.row-checkbox:checked').map(function () {
      return $(this).val();
    }).get();
    "use strict";
    if (env == "sandbox") {
      if (!nexturl.includes("orders") && !nexturl.includes("logout")) {
        myFunction();
        return false;
      }
    }
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: "btn btn-success mx-1",
        cancelButton: "btn btn-danger mx-1"
      },
      buttonsStyling: false
    });
    swalWithBootstrapButtons
    .fire({
      title: are_you_sure,
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: yes,
      cancelButtonText: no,
      reverseButtons: true
    })
    .then(result => {
      if (result.isConfirmed) {

        $.ajax({
          headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            url: statusurl,
            data: {
            id: id,
            },
            method: "GET",
            success: function (response) {
              console.log(response);
              $("#preload").hide();

              if (response.status == 0) {
                toastr.error(response.msg);
              } else {
                sessionStorage.setItem("successMessage", response.msg);
                location.reload();
              }
            }
        });
       
      } else {
        result.dismiss === Swal.DismissReason.cancel;
      }
    });
  }
   document.addEventListener("DOMContentLoaded", function () {
    const successMessage = sessionStorage.getItem("successMessage");
    if (successMessage) {
      toastr.success(successMessage);
      sessionStorage.removeItem("successMessage");
    }
  });

  