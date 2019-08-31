$(document).ready(function () {

    $("form[name='login']").validate({

        rules: {
            cp: {
                required:true
            },
            np: {
                required:true,
                minlength: 6
            },
            rp: {
                required: true,
                passmatch2 : true
            }
        },
        messages: {
            cp:{
                required: "Please enter your current password"
            },
            np: {
                required: "Please enter a new password",
                minlength: "New password must be at least 6 characters long"
            },
            rp: {
                required: "Please type your password again here",
                minlength: "Please specify a password of length between 6 and 20."
            }
        },

        submitHandler: function (form) {
            form.submit();
        }
    });

    jQuery.validator.addMethod("passmatch2", function (value, element) {

        var pass = $("#np").val();
        var repeat = $("#rp").val();

        return (pass == repeat);

    }, "Passwords mismatched. Please type in the same password in each password field.");

});








/*$(document).ready(function (e) {

    $('[data-toggle="tooltip"]').tooltip();

    $('#email').change(function () {

        var sEmail = $('#email').val();
        if (validateEmail(sEmail)) {
            $(".this-email").removeClass("has-error has-feedback");
            $("#login_email_error").html("");
        }
        else {
            var erMsg = "Please enter a valid email address. This address will be used to communicate with you."
            $("#login_email_error").html(erMsg);
            $(".this-email").addClass("has-error has-feedback");
            e.preventDefault();
        }
    });

    $('#password').change(function () {
        var pw = $('#password').val();
        var rep = $('#repeat').val();

        if (pw.length < 6) {
            $("#login_password_error").html("Please specify a password of length <br>between 6 and 20.");
            $(".this-pw").addClass("has-error has-feedback");
            e.preventDefault();
        }else {
            $("#login_password_error").html("");
            $(".this-pw").removeClass("has-error has-feedback");
        }

        if (rep != "" && rep != pw) {
            $("#login_repeat_error").html("Passwords mismatched. Please type in the same password<br>in each password field.");
        }
    });

    $('#repeat').change(function () {
        var pw = $('#password').val();
        var rep = $('#repeat').val();

        if (pw != rep) {
            $("#login_repeat_error").html("Passwords mismatched. Please type in the same password in each password field.");
        } else {
            $("#login_repeat_error").html("");
        }
    });

    $("#agree").change(function(){
        var agree = $("#agree").is(':checked');

        if (agree == 0) {
            $("#submit").addClass("disabled");
        } else {
            $("#submit").removeClass("disabled");
        }
    });
});

function validateEmail(sEmail) {
    var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
    if (filter.test(sEmail)) {
        return true;
    }
    else {
        return false;
    }
}*/