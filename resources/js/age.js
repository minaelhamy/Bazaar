$(document).ready(function () {
    // Check if age verification is successful
    age = localStorage.getItem("age");
    if (age === "verified") {
        // Remove the blur class from the main content
        $('#main-content').removeClass('blur');
    } else {
        // Show the age verification modal
        $('#age_modal').modal('show');
    }
});

function ageverification() {

    var popup_type = $('#popup_type').val();
    var min_age = $('#min_age').val();

    if (popup_type == 1) {
        localStorage.setItem("age", "verified");
        $('#age_modal').modal('hide');
        setTimeout(function () {
            $("#NewsModal").modal("show");
            $('#main-content').removeClass('blur');
        }, 1000);
    }

    if (popup_type == 2) {
        var dd = $('#dd').val();
        var mm = $('#mm').val();
        var yyyy = $('#yyyy').val();

        if (dd == "") {
            $('#dd-required').show();
        }
        if (mm == "") {
            $('#mm-required').show();
        }
        if (yyyy == "") {
            $('#yyyy-required').show();
        }

        var today = new Date();
        var birthDate = new Date(yyyy + '/' + mm + '/' + dd);

        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        if (age >= min_age) {
            localStorage.setItem("age", "verified");
            $('#age_modal').modal('hide');
            setTimeout(function () {
                $("#NewsModal").modal("show");
                $('#main-content').removeClass('blur');
            }, 1000);

        } else {
            $('#age-alert').show();
        }
    }

    if (popup_type == 3) {
        var age = $('#age').val();

        if (age == "") {
            $('#age-required').show();
        }

        if (age >= min_age) {
            localStorage.setItem("age", "verified");
            $('#age_modal').modal('hide');
            setTimeout(function () {
                $("#NewsModal").modal("show");
                $('#main-content').removeClass('blur');
            }, 1000);

        } else {
            $('#age-alert').show();
        }
    }
}

function ageverificationcancel() {
    $('#age-alert').show();
}
function ageverificationclose() {
    $('#age_modal').modal('hide');
    setTimeout(function () {
        $("#NewsModal").modal("show");
        $('#main-content').removeClass('blur');
    }, 1000);
}