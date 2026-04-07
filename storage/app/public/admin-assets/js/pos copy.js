function cleardata() {
  $("#additems").modal("hide");
  $("#item_id").val("");
  $("#item_name").val("");
  $("#item_price").val("");
  $("#item_tax").val("");
  $("#item_image").val("");
  $("#orignal_price").val("");
  $("#qty").val("");
  $("#extras").html("");
  $("#variants").html("");
  $("#viewitem_name").html("");
  $("#viewitem_price").html("");
}

function categories_filter(cat_id, nexturl) {
  $(".scroll-list").hasClass("active");
  $(".active").removeClass("active");
  $("#search-keyword").val("");

  if (cat_id == "") {
    $("#cat").addClass("active");
  }
  $("#cat-" + cat_id).addClass("active");
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
    error: function (data) {
      toastr.error(wrong);
      return false;
    }
  });
}

$("#plusqty").on("click", function () {
  "use strict";
  $.ajax({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    },
    url: qtyupdateurl,
    data: {
      item_id: $("#item_id").val(),
      item_min_order: $("#item_min_order").val(),
      item_max_order: $("#item_max_order").val(),
      variants_min_order: $("#variants_min_order").val(),
      variants_max_order: $("#variants_max_order").val(),
      variants_id: $("#variant_id").val(),
      qty: $("#qty").val(),
      variant_qty: $("#checked_product_qty").val(),
      stock_management: $("#stock_management").val()
    },
    method: "POST",
    success: function (response) {
      if (response.status == 0) {
        toastr.error(response.message);
        $("#qty").val(response.qty);
      } else {
        $("#qty").val(response.qty);
      }
    },
    error: function (e) {
      $("#qty").val(response.qty);
      toastr.error(response.message);
    }
  });
});

$("#minusqty").on("click", function () {
  "use strict";
  var qty = parseInt($("#qty").val());
  qty = qty - 1;
  if (qty < 1) {
    qty = 1;
  }
  $("#qty").val(qty);
});

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
      toastr.error(wrong);
      return false;
    }
  });
});

function addtocart(
  id,
  name,
  price,
  image,
  tax,
  qty,
  item_min_order,
  item_max_order,
  orignal_price,
  product_qty,
  variant_min_order,
  variant_max_order
) {
  var variants_id = $("#variant_id").val();
  var variants_price = $("#viewitem_price").val();
  var variant_qty = $("#checked_product_qty").val();
  var stock_management = $("#stock_management").val();
  var extras_id = $(".Checkbox:checked")
    .map(function () {
      return this.value;
    })
    .get()
    .join("| ");
  var extras_name = $(".Checkbox:checked")
    .map(function () {
      return $(this).attr("extras_name");
    })
    .get()
    .join("| ");
  var extras_price = $(".Checkbox:checked")
    .map(function () {
      return $(this).attr("price");
    })
    .get()
    .join("| ");
  $(".addbtn-" + id).hide();
  $(".showload-" + id).show();

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
      price: orignal_price,
      tax: tax,
      variants_id: variants_id,
      variants_price: variants_price,
      extras_id: extras_id,
      extras_name: extras_name,
      extras_price: extras_price,
      qty: qty,
      product_qty: product_qty,
      item_min_order: item_min_order,
      item_max_order: item_max_order,
      stock_management: stock_management
    },
    method: "POST", //Post method,
    success: function (response) {
      $("#cartitemcount").text(response.cartitemcount); 

      if (response.status == 0) {
        toastr.error(response.message);
        $(".showload-" + id).hide();
        $(".addbtn-" + id).show();
      } else {
        $("#variant_id").val("");
        $("#variants_name").val("");
        $(".showload-" + id).hide();
        $(".addbtn-" + id).show();
        $(".addactive-" + id).addClass("active");
        $("#additems").modal("hide");
        $("#cartview").html("");
        $("#cartview").html(response);
        toastr.success("Add Success");
        cleardata();
      }
    },
    error: function (response) {
      toastr.error(response.message);
    }
  });
}

function calladdtocart() {
  var id = $("#item_id").val();
  var item_name = $("#item_name").val();
  var item_price = $("#item_price").val();
  var item_qty = $("#qty").val();
  var item_min_order = $("#item_min_order").val();
  var item_max_order = $("#item_max_order").val();
  var item_image = $("#modal_item_image").val();
  var tax = $("#item_tax").val();
  var orignal_price = $("#orignal_price").val();
  var variant_qty = $("#checked_product_qty").val();
  var variant_min_order = $("#variants_min_order").val();
  var variant_max_order = $("#variants_max_order").val();
  addtocart(
    id,
    item_name,
    item_price,
    item_image,
    tax,
    item_qty,
    item_min_order,
    item_max_order,
    orignal_price,
    variant_qty,
    variant_min_order,
    variant_max_order
  );
}

// function showitems(id, item_name, item_price, qty, image) {
//   "use strict";
//   $.ajax({
//     headers: {
//       "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
//     },
//     url: $("#showitemurl").val(),
//     method: "post",
//     data: {
//       id: id
//     },
//     success: function (response) {
//       var e;
//       var i;
//       let html = "";
//       let html1 = "";
//       var count_varient = 0;
//       var count_extra = 0;
//       let price = parseInt(item_price);
//       for (var i = 0; i < response.getitem.variants_json.length; i++) {
//         html +=
//           '<p class="variant_name variant_name text-dark fw-500 pro-title line-2 mb-2 fs-6">' +
//           response.getitem.variants_json[i].variant_name +
//           "</p>";

//         html +=
//           '<select name="product[' +
//           [i] +
//           ']"  id="pro_variants_name" class="form-control variant-selection  pro_variants_name' +
//           [i] +
//           ' pro_variants_name variant_loop variant_val mb-2 py-1">';

//         for (
//           var t = 0;
//           t < response.getitem.variants_json[i].variant_options.length;
//           t++
//         ) {
//           html +=
//             '<option value="' +
//             response.getitem.variants_json[i].variant_options[t] +
//             '" id="' +
//             response.getitem.variants_json[i].variant_options[t] +
//             '_varient_option">' +
//             response.getitem.variants_json[i].variant_options[t] +
//             "</option>";
//         }
//         html += "</select>";
//       }

//       $("#checked_product_qty").val(response.variants[0].qty);
//       $("#stock_management").val(response.variants[0].stock_management);
//       if (response.variants[0].is_available == 2) {
//         $("#not_available_text").html("" + not_available_msg + "");
//         $(".add-btn").attr("disabled", true);
//       } else {
//         $("#not_available_text").html("");
//         $(".add-btn").attr("disabled", false);
//         if (response.variants[0].stock_management == 1) {
//           if (response.variants[0].qty > 0) {
//             $("#out_of_stock").removeClass("text-danger");
//             $("#out_of_stock").addClass("text-success");
//             $("#out_of_stock").html(
//               "" + response.variants[0].qty + " " + in_stock + ""
//             );
//           } else {
//             $("#out_of_stock").removeClass("text-dark");
//             $("#out_of_stock").addClass("text-danger");
//             $("#out_of_stock").html("" + out_of_stock_msg + "");
//           }
//         } else {
//           $("#out_of_stock").html("");
//         }
//       }

//       for (i in response.extras) {
//         count_extra = parseInt(count_extra + 1);
//         html1 +=
//           ' <div class="form-check mb-2 d-flex align-items-center justify-content-between"><input class="form-check-input border Checkbox" type="checkbox" id="Extras' +
//           response.extras[i].id +
//           '" name="extras[]" value="' +
//           response.extras[i].id +
//           '" extras_name="' +
//           response.extras[i].name +
//           '" price="' +
//           response.extras[i].price +
//           '"><label class="col-12 py-0 form-check-label d-flex justify-content-between align-items-center" for= "Extras' +
//           response.extras[i].id +
//           '"><span class="modal-price">' +
//           response.extras[i].name +
//           '</span><span class="text-muted modal-price"> (' +
//           currency_formate(response.extras[i].price) +
//           ") </span></label></div>";
//       }
//       var imgElement = document.getElementById("item_image");

//       // Check if the image element exists
//       if (imgElement) {
//         // Set the new image sourcess
//         imgElement.src = image;
//       }
//       $("#qty").val(1);
//       $("#extras").html(html1);
//       $("#variants").html(html);
//       $("#viewitem_qty").val(response.variants[0].qty);
//       $("#viewitem_name").html(item_name);
//       $("#variant_id").val(response.variants[0].id);
//       $("#viewitem_price").html("" + currency_formate(item_price) + "");
//       $("#checked_product_qty").html(response.variants[0].qty);
//       if (count_extra == 0) {
//         $("#extras_title").html("");
//       }
//       if (count_varient == 0) {
//         $("#variants_title").html("");
//       }
//       $("#item_id").val(id);
//       $("#item_name").val(item_name);
//       $("#item_price").val(item_price);
//       $("#item_tax").val(response.getitem.tax);
//       $("#modal_item_image").val(response.getitem.product_image.image);
//       $("#item_min_order").val(response.variants[0].min_order);
//       $("#item_max_order").val(response.variants[0].max_order);
//       $("#orignal_price").val(parseInt(item_price));

//       $("#additems").modal("show");
//     },
//     error: function (response) {
//       toastr.error(wrong);
//       return false;
//     }
//   });
// }


// function callcartview()
// {
//   $.ajax({
//     url: $("#cartViewUrl").val(),
//     method: 'GET',      
//     success: function(response) {

//       if (response.status === 1) {
//         $('#cartItemsContainer').html(response.output); // Use response.output
//         var cartOffCanvas = new bootstrap.Offcanvas(document.getElementById('cart-offCanvas'));
//         cartOffCanvas.show(); // Show the offcanvas
//       } else {
//         console.error('Unexpected response status: ' + response.status);
//       }

//   },
//     error: function(xhr, status, error) {
//         console.error('AJAX Error: ' + status + error); // Log errors
//     }
// });
// }

function callcartview() {
  $.ajax({
      url: $("#cartViewUrl").val(),
      method: 'GET',
      success: function(response) {
        
          if (response.status === 1) {
              $('#cartItemsContainer').html(response.output);
              // Initialize or refresh the offcanvas
              var cartOffCanvas = new bootstrap.Offcanvas(document.getElementById('cart-offCanvas'));
              cartOffCanvas.show(); // Show the offcanvas
          } else {
              console.error('Unexpected response status: ' + response.status);
          }
      },
      error: function(xhr, status, error) {
          console.error('AJAX Error: ' + status + error); // Log errors
      }
  });
}


function showitems(id, item_name, item_price, qty, off ,image) {
  "use strict";
  $.ajax({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    },
    url: $("#showitemurl").val(),
    method: "post",
    data: {
      id: id
    },
    success: function (response) {

      console.log(response.getitem);

      var html = "";
      var html1 = "";
      var count_varient = 0;
      var count_extra = 0;

      // Ensure response is valid
      if (response && response.getitem) {
        let price = parseInt(item_price);
        if (response.getitem.variation) {
          // Iterate through each variant

          html += '<p class="title pb-1 pt-3"> variants</p>';
          for (let i = 0; i < response.getitem.variation.length; i++) {
            const variant = response.getitem.variation[i];
            const inputId = 'variant-' + variant.id; // Create a unique ID for each variant
            const optionValue = variant.name;
            
            html += '<div class="form-check mb-2 d-flex align-items-center justify-content-between">';
            html += '<input type="radio" id="' + inputId + '" name="product" value="' + optionValue + '" class="form-check-input variant_selection"';
            if (i === 0) {
                html += ' checked';
            }
            html += '>';
            html += '<input type="hidden" id="variantprice-' + variant.id + '" value="'+ variant.price +'">';
            html += '<label for="' + inputId + '" class="col-12 py-0 form-check-label d-flex justify-content-between align-items-center">';
            html += '<span class="modal-price">' + optionValue + '</span>';
            html += '<span class="text-muted modal-price"> (' + currency_formate(variant.price) + ')</span>';
            html += '</label></div>';
        }
        
      }
      
      
        // Update stock status
        if (response.variants && response.variants[0]) {
          $("#checked_product_qty").val(response.variants[0].qty);
          $("#stock_management").val(response.variants[0].stock_management);
          if (response.variants[0].is_available == 2) {
            $("#not_available_text").html(not_available_msg);
            $(".add-btn").attr("disabled", true);
          } else {
            $("#not_available_text").html("");
            $(".add-btn").attr("disabled", false);
            if (response.variants[0].stock_management == 1) {
              if (response.variants[0].qty > 0) {
                $("#out_of_stock").removeClass("text-danger").addClass("text-success").html(response.variants[0].qty + " " + in_stock);
              } else {
                $("#out_of_stock").removeClass("text-dark").addClass("text-danger").html(out_of_stock_msg);
              }
            } else {
              $("#out_of_stock").html("");
            }
          }
        }

        // Build extras
        if (response.extras) {
          for (i in response.extras) {
            count_extra = parseInt(count_extra + 1);
            html1 += '<div class="form-check mb-2 d-flex align-items-center justify-content-between"><input class="form-check-input border Checkbox" type="checkbox" id="Extras' +
              response.extras[i].id +
              '" name="extras[]" value="' +
              response.extras[i].id +
              '" extras_name="' +
              response.extras[i].name +
              '" price="' +
              response.extras[i].price +
              '"><label class="col-12 py-0 form-check-label d-flex justify-content-between align-items-center" for="Extras' +
              response.extras[i].id +
              '"><span class="modal-price">' +
              response.extras[i].name +
              '</span><span class="text-muted modal-price"> (' +
              currency_formate(response.extras[i].price) +
              ") </span></label></div>";
          }
        }

        // Update modal content
        var imgElement = document.getElementById("item_image");
        if (imgElement) {
          imgElement.src = image;
        }
        $("#qty").val(1);
        $("#extras").html(html1);
        $("#variants").html(html);
        $("#viewitem_qty").val(response.variants[0]?.qty || 0);
        $("#viewitem_name").html(item_name);
        $("#variant_id").val(response.variants[0]?.id || "");
        $("#viewitem_price").html(currency_formate(item_price));
        $("#checked_product_qty").html(response.variants[0]?.qty || 0);
        if (count_extra == 0) {
          $("#extras_title").html("");
        }
        if (count_varient == 0) {
          $("#variants_title").html("");
        }
        $("#item_id").val(id);
        $("#item_name").val(item_name);
        $("#item_price").val(item_price);
        $("#item_tax").val(response.getitem?.tax || 0);
        $("#modal_item_image").val(response.getitem?.product_image?.image || "");
        $("#item_min_order").val(response.variants[0]?.min_order || 0);
        $("#item_max_order").val(response.variants[0]?.max_order || 0);
        $("#orignal_price").val(parseInt(item_price));
        if (off > 0) {
          $("#offer-box").text(`${off}%`);
      } else {
          $("#offer-box").text(`${0}%`); 
      }
        $("#additems").modal("show");
      } else {
        // Handle case where response is invalid or empty
        toastr.error("No data found");
        $("#additems").modal("show");
      }
    },
    error: function () {
      toastr.error("An error occurred while fetching data.");
      $("#additems").modal("show"); // Show modal even on error
    }
  });
}

$(document).on('change', '.variant_selection', function() {
  var selectedId = $(this).attr('id');
  var selectedPrice = $('#variantprice-' + selectedId.split('-')[1]).val();   

  $('#viewitem_price').empty();
  $('#viewitem_price').text(currency_formate(selectedPrice));
});
$('.variant_selection:checked').trigger('change');

$(document)
  .on("change", "#pro_variants_name", function () {
    set_variant_price();
  })
  .change();

function set_variant_price() {
  var variants = [];
  $(".variant-selection").each(function (index, element) {
    variants.push(element.value);
  });

  if (variants.length > 0) {
    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
      },
      url: $("#variantsurl").val(),
      data: {
        name: variants.join("|"),
        item_id: $("#item_id").val(),
        vendor_id: vendor_id
      },
      success: function (data) {
        $("#item_min_order").val(data.min_order);
        $("#item_max_order").val(data.max_order);
        $("#checked_product_qty").val(data.quantity);
        $("#orignal_price").html(
          currency_formate(parseFloat(data.original_price))
        );
        $("#item_price").text(currency_formate(parseFloat(data.price)));
        $("#viewitem_price").html("" + currency_formate(data.price) + "");
        $("#variants_name").val(data.variants_name);
        $("#stock_management").val(data.stock_management);
        $("#variant_id").val(data.variant_id);
        if (data.is_available == 2) {
          $("#not_available_text").html("" + not_available_msg + "");
          $(".add-btn").addClass("disabled", true);
          $("#out_of_stock").html("");
        } else {
          $("#not_available_text").html("");
          $(".add-btn").removeClass("disabled", false);
          if (data.stock_management == 1) {
            if (data.quantity > 0) {
              $("#out_of_stock").removeClass("text-danger");
              $("#out_of_stock").addClass("text-success");
              $("#out_of_stock").html("" + data.quantity + in_stock + "");
            } else {
              $("#out_of_stock").removeClass("text-success");
              $("#out_of_stock").addClass("text-danger");
              $("#out_of_stock").html("" + out_of_stock_msg + "");
            }
          }
        }
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
      icon: "error",
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
$("input[type=radio]").change(function () {
  if ($(this).val() == "1") {
    $("#modal_total_amount").val($("#grand_total").val());
    $("#paymentModal").modal("show");
  }
});

function validation(value) {
  var remaining = $("#modal_total_amount").val() - value;
  $("#ramin_amount").val(remaining.toFixed(2));
}
function order() {
  var discount_amount = $("#discount_amount").val();
  var payment_type = $('input[name="payment_type"]:checked').val();
  var sub_total = $("#sub_total").val();
  var tax = $("#tax_data").val();
  var grand_total = $("#grand_total").val();

  $.ajax({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    },
    url: $("#orderurl").val(),
    data: {
      discount_amount: discount_amount,
      name: $("#customer_name").val(),
      email: $("#customer_email").val(),
      mobile: $("#customer_mobile").val(),
      payment_type: payment_type,
      sub_total: sub_total,
      tax: tax,
      tax_name: $("#tax_name").val(),
      grand_total: grand_total
    },
    method: "POST",
    success: function (response) {
      if (response.status == 0) {
        toastr.error(response.message);
      } else {
        $("#cartview").html("");
        $("#cartview").html(response);
        toastr.success("Order Placed!!");
        $("#pos-invoice").modal("show");
      }
    },
    error: function (e) {
      swal("Cancelled", "{{ trans('messages.wrong') }} :(", "error");
    }
  });
}
function cash() {

 
  var sub_total = $("#ordersub_total").val();
  
  if (parseFloat(minorderamount) > parseFloat(sub_total)) {
    toastr.error(minorderamountmsg);
    return false;
  } else {
    $("#paymentModal").modal("show");
  }
}
function placeorder(placeorderurl) {

  var discount_amount = $("#orderdiscount_amount").text();
  var sub_total = $("#ordersub_total").text();
  var tax_names = [];
  var tax_rates = [];
  $('#ordertax_name').each(function() {
    tax_names.push($(this).text().trim());
  });
  
  $('#ordertax_rate').each(function() {
    tax_rates.push($(this).text().trim());
  });
  var tax_names_str = tax_names.join('| ');
  var tax_rates_str = tax_rates.join('| '); 

  var grand_total = $("#ordergrand_total").text();
  var customer_name = $("#customer_name").val();
  var customer_email = $("#customer_email").val();
  var customer_phone = $("#customer_phone").val();

  // Validate customer details
  if (!customer_name) {
    toastr.error("Please enter your name.");
    return; // Exit the function early
  }
  if (!customer_email) {
    toastr.error("Please enter your email address.");
    return; // Exit the function early
  }
  if (!customer_phone) {
    toastr.error("Please enter your phone number.");
    return; // Exit the function early
  }

  var payment_type = $('input[name="payment_type"]:checked').val();  
  if (!payment_type) {
    toastr.error("Please select a payment type.");
    return; 
  }

  $.ajax({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    },
    url: placeorderurl,
    data: {
      discount_amount: discount_amount,
      customer_name: customer_name,
      customer_email: customer_email,
      customer_phone: customer_phone,
      payment_type: payment_type,
      sub_total: sub_total,
      tax_rates: tax_rates_str,
      tax_name: tax_names_str,
      grand_total: grand_total
    },
    method: "POST",
    success: function (response) {
      console.log(response);
      if(response.status == 1){
        $('#exampleModalToggle2').modal('show');
      }

    },
    error: function (e) {
      swal("Cancelled", "{{ trans('messages.wrong') }} :(", "error");
    }
  });
}

$("#btn-print").on("click", function () {
  "use strict";
  location.reload();
});

$("#btn-print-close").on("click", function () {
  "use strict";
  location.reload();
});


// $(document).ready(function() {
//   function updateQuantity($input, increment) {
//     let currentValue = parseInt($input.val(), 10);
//     if (!isNaN(currentValue)) {
//       let newValue = Math.max(currentValue + increment, 0);
//       $input.val(newValue);
//       updateItemTotal($input);
//       updateTotal(); // Update the subtotal
//       updateGrandTotal(); // Update the grand total after each item update
//     } else {
//       console.log("Invalid current value:", $input.val());
//     }
//   }

//   function updateItemTotal($input) {
//     var $row = $input.closest('tr');
//     var quantity = parseInt($input.val(), 10);
//     var basePrice = parseFloat($row.data('price')) || 0;
//     var variantsPrice = parseFloat($row.data('variants-price')) || 0;
//     var extrasPrice = parseFloat($row.data('extras-price')) || 0;

//     var validQuantity = !isNaN(quantity) ? quantity : 0;
//     var validBasePrice = !isNaN(basePrice) ? basePrice : 0;
//     var validVariantsPrice = !isNaN(variantsPrice) ? variantsPrice : 0;
//     var validExtrasPrice = !isNaN(extrasPrice) ? extrasPrice : 0;

//     var itemTotal = (validBasePrice + validVariantsPrice + validExtrasPrice) * validQuantity;
//     $row.find('.itemtotal').text(formatCurrency(itemTotal));
//   }

//   function updateTotal() {
//     let SubTotal = 0;
//     $('.itemtotal').each(function() {
//       let itemTotal = parseFloat(parseCurrency($(this).text())) || 0;
//       SubTotal += itemTotal;
//     });
//     $('.sub-total').text(formatCurrency(SubTotal));
//   }

//   function updateGrandTotal() {
//     let SubTotal = parseFloat(parseCurrency($('.sub-total').text())) || 0;
//     let taxtotal = 0;
//     $('.tax-rate').each(function() {
//       taxtotal += parseFloat(parseCurrency($(this).text())) || 0;
//     });
//     let discount = parseFloat(parseCurrency($('.discount').text())) || 0;
//     let grandTotal = SubTotal + taxtotal - discount;
//     $('.grand-total').text(formatCurrency(grandTotal));
//   }

//   function formatCurrency(value) {
//     return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);
//   }

//   function parseCurrency(value) {
//     return value.replace(/[^0-9.-]+/g, ""); // Remove currency symbols
//   }

//   $(document).on('click', '.qty-btn-minus', function() {
//     const $input = $(this).siblings('.input-qty');
//     updateQuantity($input, -1);
//   });

//   $(document).on('click', '.qty-btn-plus', function() {
//     const $input = $(this).siblings('.input-qty');
//     updateQuantity($input, 1);
//   });


  

    // Handle the click event for the discount button
  //   $(document).on('click', '#button-addon2', function() {
  //     let discountInput = $(this).siblings('input').val();
  //     let discount = parseFloat(parseCurrency(discountInput));
  //     let discounturl = 'admin/pos/discount'; // Remove extra spaces
  
  //     if (!isNaN(discount) && discount >= 0) {
  //         let csrfToken = $('meta[name="csrf-token"]').attr('content');
  
  //         $.ajax({
  //             url: discounturl,
  //             type: 'POST',
  //             data: {
  //                 discount: discount,
  //                 _token: csrfToken // Include CSRF token
  //             },
  //             success: function(response) {

  //                 // Assuming response contains the updated discount or some status message
  //                 $('.discount').text(formatCurrency(discount));
  //                 updateGrandTotal();
  //                 console.log("Discount applied successfully", response);
  //             },
  //             error: function(xhr, status, error) {
  //                 console.error("AJAX error:", status, error);
  //             }
  //         });
  //     } else {
  //         console.log("Invalid discount value:", discountInput);
  //     }
  // });
  
// });




