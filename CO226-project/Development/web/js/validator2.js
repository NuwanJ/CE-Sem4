$(document).ready(function () {

    $("form[name='details']").validate({

        rules: {
            firstName: "required",
            lastName: "required",
            dob: {
                required: true,
                date: true
            },
            nic: {
                required: true,
                nicNumber: true
            },
            address: "required",
            tele: {
                required: true,
                teleNumber: true
            }
        },
        messages: {
            firstName: "Please enter your First Name",
            lastName: "Please enter your Last Name",
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 6 characters long"
            },
            dob: {
                required: "Please select your Birthday"
            },
            nic: {
                required: "Please enter your National Identity Card Number",
                minlength: "Please enter a valid National Identity Card Number"
            },
            address: "Please enter your postal address",
            tele: {
                required: "Please enter your telephone number"
            }
        },

        submitHandler: function (form) {
            form.submit();
        }
    });

    jQuery.validator.addMethod("nicNumber", function (value, element) {

        if (value.length == 10 && jQuery.isNumeric(value.toString().substring(0, 9))) {
            // old method
            return true;
        } else if (value.length == 13 && jQuery.isNumeric(value.toString().substring(0, 12))) {
            // new nic format
            return true;
        } else {
            return false;
        }

    }, "Please enter a valid NIC number. Ex: 911234567v or 199112345678v (new format)");

    jQuery.validator.addMethod("teleNumber", function (value, element) {

        if ((value.length == 10) && (jQuery.isNumeric(value) == true)) {
            return true;
        } else {
            return false;
        }

    }, "Please enter a valid telephone number. Ex: 0771234567");

    /*jQuery.validator.addMethod("nicVerify", function (value, element) {

        var date1 = new Date($("#dob").val());
        var date2 = new Date(date1.getFullYear(), 1, 1);
        var dif = date1 - date2;

        var nic = $("#nic").val();
        var days = 0;

            if (nic.length == 10) {
            days = nic.substring(2, 5);
        }

        if(days >0){
            days = days - 500;
        }

        if (dif==1 || dif==0 || dif==-1) {
            return true;
        } else {
            return false;
        }

    }, "Please enter a valid NIC number");*/


});


 

