function categories_filter(cat_id, nexturl) {
  $(".actives").removeClass("actives");
  $("#search-keyword").val("");

  $("#cat-" + cat_id).addClass("actives");
  $.ajax({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    },
    url: nexturl,
    method: "get",
    data: {
      id: cat_id
    },
    success: function (data) {
      $("#pos-item").html("");
      $("#cat_id").val();
      $("#pos-item").html(data);
    },
    error: function () {
      toastr.error(wrong);
      return false;
    }
  });
}

$("#search-keyword").keyup(function () {
  "use strict";
  var cat_id = $("#cat_id").val();
  var keyword = $("#search-keyword").val();
  $.ajax({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    },
    url: $("#search-url").val(),
    method: "get",
    data: {
      id: cat_id,
      keyword: keyword
    },
    success: function (data) {
      $("#pos-item").html("");
      $("#cat_id").val();
      $("#pos-item").html(data);
    },
    error: function (data) {
      // toastr.error(wrong);
      return false;
    }
  });
});

function posaddtocart() {

  // add spinner to button for some time
  $('.addtocart').prop("disabled", true);
  $('.addtocart').html('<span class="loader"></span>');

  var vendor = $('#modal_overview_vendor').val();
  var id = $('#modal_overview_item_id').val();
  var name = $('#modal_overview_item_name').val();
  var image = $('#modal_overview_item_image').val();
  var price = $('#modal_overview_item_price').val();
  var qty = $('#modal_detail_plus_minus .item_qty_' + id).val();
  var original_price = $('#modal_overview_item_original_price').val();
  var tax = $('#modal_tax_val').val();
  var variants_name = $('#modal_variants_name').val();
  var item_min_order = $('#modal_item_min_order').val();
  var item_max_order = $('#modal_item_max_order').val();
  var stock_management = $('#modal_stock_management').val();
  var extras_id = ($('.Checkbox:checked').map(function () {
    return this.value;
  }).get().join('| '));
  var extras_name = ($('.Checkbox:checked').map(function () {
    return $(this).attr('extras_name');
  }).get().join('| '));
  var extras_price = ($('.Checkbox:checked').map(function () {
    return $(this).attr('price');
  }).get().join('| '));

  $.ajax({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    },
    url: $("#addtocarturl").val(),
    data: {
      id: id,
      name: name,
      image: image,
      item_price: price,
      price: original_price,
      tax: tax,
      variants_name: variants_name,
      extras_id: extras_id,
      extras_name: extras_name,
      extras_price: extras_price,
      qty: qty,
      item_min_order: item_min_order,
      item_max_order: item_max_order,
      stock_management: stock_management
    },
    method: "POST", //Post method,
    success: function (response) {
      $("#openOffCanvas").removeClass('d-none');
      $("#cartitemcount").text(response.cartitemcount);

      if (response.status == 0) {
        toastr.error(response.message);
        $('.addtocart').html(add_to_cart);
        $('.addtocart').prop("disabled", false);
      } else {
        toastr.success('Add Success')
        $("#pos-viewproduct-over").modal("hide");
      }
    },
    error: function () {
      toastr.error(wrong);
      $('.addtocart').html(add_to_cart);
      $('.addtocart').prop("disabled", false);
    }
  });
}

function callcartview() {
  $.ajax({
    url: $("#cartViewUrl").val(),
    method: 'GET',
    success: function (response) {

      if (response.status === 1) {
        $('#cartItemsContainer').html(response.output);
        $('#cart-offCanvas').offcanvas('show');
      } else {
        toastr.error('Something went Wrong !');
      }
    },
    error: function () {
      toastr.error('Something went Wrong !');
    }
  });
}


function showitems(id, slug) {
  "use strict";
  $.ajax({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    },
    url: $("#showitemurl").val(),
    method: "post",
    data: {
      slug: slug,
      id: id
    },
    success: function (response) {
      $('#pos_viewproduct_body').html(response.output);
      $('#pos-viewproduct-over').modal('show');
    },
    error: function () {
      toastr.error("An error occurred while fetching data.");
      $('#pos_viewproduct-over').modal('hide'); // Show modal even on error
    }
  });
}

function changeqty(item_id, type) {
  var qtys = parseInt($('.item_qty_' + item_id).val());
  if (type == "minus") {
    qty = qtys - 1;
  } else {
    qty = qtys + 1;
  }
  if (qty >= "1") {
    var variants_name = $('#modal_variants_name').val();
    var stock_management = $('#modal_stock_management').val();
    $('.change-qty-2').prop('disabled', true);

    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: $('#changeqtyurl').val(),
      data: {
        item_id: item_id,
        type: type,
        qty: qty,
        vendor_id: vendor_id,
        variants_name: variants_name,
        stock_management: stock_management,
      },
      method: 'POST',
      success: function (response) {
        if (response.status == 1) {
          $('.change-qty-2').prop('disabled', false);
          $('.item_qty_' + item_id).val(response.qty);
        } else {
          $('.change-qty-2').prop('disabled', false);
          $('.item_qty_' + item_id).val(response.qty);
          toastr.error(response.message);
        }
      },
      error: function () {
        $('.change-qty-2').prop('disabled', false);
      }
    });
  }

}

function RemoveCart(cart_id) {
  "use strict";
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: "btn btn-success mx-1",
      cancelButton: "btn btn-danger bg-danger mx-1"
    },
    buttonsStyling: false
  });
  swalWithBootstrapButtons
    .fire({
      icon: "warning",
      title: title,
      showCancelButton: true,
      allowOutsideClick: false,
      allowEscapeKey: false,
      confirmButtonText: yes,
      cancelButtonText: no,
      reverseButtons: true,
      showLoaderOnConfirm: true,
      preConfirm: function () {
        return new Promise(function (resolve, reject) {
          $.ajax({
            headers: {
              "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            url: $("#deletecarturl").val(),
            data: {
              cart_id: cart_id
            },
            method: "POST",
            success: function (response) {
              if (response.status == 1) {
                location.reload();
              } else {
                swal("Cancelled", "{{ trans('messages.wrong') }} :(", "error");
              }
            },
            error: function (e) {
              swal("Cancelled", "{{ trans('messages.wrong') }} :(", "error");
            }
          });
        });
      }
    })
    .then(result => {
      if (!result.isConfirmed) {
        result.dismiss === Swal.DismissReason.cancel;
      }
    });
}

function placeorder(placeorderurl) {
  var discount_amount = $('#orderdiscount_amount').text().trim();
  var cleaned_amount = discount_amount.replace(/[^0-9.]/g, '');
  var numeric_amount = parseFloat(cleaned_amount);

  var order_note = $('#cart_order_note').text();

  var numeric_amount_sub = $("#ordersub_total").text().trim();
  var cleaned_amount_sub = numeric_amount_sub.replace(/[^0-9.]/g, '');
  var sub_total = parseFloat(cleaned_amount_sub);

  var tax_name = $("#hiddentax_name").text();

  var tax_rate = $("#hiddentax_rate").text().trim();
  var tax_rates = tax_rate.replace(/[^\d.-|]/g, '');


  var numeric_amount_grand = $("#ordergrand_total").text().trim();
  var cleaned_amount_grand = numeric_amount_grand.replace(/[^0-9.]/g, '');
  var grand_total = parseFloat(cleaned_amount_grand);

  var customer_name = $("#customer_name").val();
  var customer_email = $("#customer_email").val();
  var customer_phone = $("#customer_phone").val();


  // Reset previous error messages
  $('#customer_name_required').text("");
  $('#customer_email_required').text("");
  $('#customer_phone_required').text("");
  $('#payment_type_required').text("");

  // Validate customer details
  var valid = true; // Flag to check if all validations pass

  if (customer_name === '') {
    $('#customer_name_required').text("Please enter your name.");
    valid = false;
  }
  if (customer_email === '') {
    $('#customer_email_required').text("Please enter your email address.");
    valid = false;
  }
  if (customer_phone === '') {
    $('#customer_phone_required').text("Please enter your phone number.");
    valid = false;
  }

  var payment_type = $('input[name="payment_type"]:checked').val();
  if (!payment_type) {
    $('#payment_type_required').text("Please select a payment type.");
    valid = false;
  }

  if (!valid) {
    $('#orderButton').modal('show');
    return;
  }

  $('.orderconfirmbtn').prop("disabled", true);
  $('.orderconfirmbtn').html('<span class="loader"></span>');

  $.ajax({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    },
    url: placeorderurl,
    data: {
      discount_amount: numeric_amount,
      customer_name: customer_name,
      customer_email: customer_email,
      customer_phone: customer_phone,
      payment_type: payment_type,
      sub_total: sub_total,
      tax_rates: tax_rates,
      tax_names: tax_name,
      grand_total: grand_total,
      order_note: order_note
    },
    method: "POST",
    success: function (response) {
      if (response.status == 0) {
        $('.orderconfirmbtn').prop("disabled", false);
        $('.orderconfirmbtn').html(confirm_order);
        toastr.error(response.message);
      } else {
        $('#customer_name_required').text("");
        $('#customer_email_required').text("");
        $('#customer_phone_required').text("");
        $('#payment_type_required').text("");
        $('#orderButton').modal('hide');

        $('#order_id').attr('href', response.url);
        $("#pos-invoice").modal("show");
        $("#pos-invoice").on('hidden.bs.modal', function (e) {
          location.reload();
        });
      }
    },
    error: function (e) {
      $('.orderconfirmbtn').prop("disabled", false);
      $('.orderconfirmbtn').html(confirm_order);
      toastr.error(wrong);
    }
  });
}