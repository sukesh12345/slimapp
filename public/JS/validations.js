var error_msg = "field required";
var mailformat = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
var phoneformat = "/^[0-9]\d{9}$/";
var head_counter = 0;
var fields = "error";
var rowCount = 0;
var loginfields = "noerror"
//var progress = 100;
//var i;
// function submcounter() {
//     if (typeof submcounter.counter == 'undefined') {
//         submcounter.counter = 0;
//     }
//     else
//         submcounter.counter = submcounter.counter + 7;
//     return submcounter.counter;
// }
//var data = new Array();

//clears all warning messages
$clearwarnings = function () {
    $("#name_error_msg").html("");
    $("#address_error_msg").html("");
    $("#mail_error_msg").html("");
    $("#password_error_msg").html("");
    $("#phone_error_msg").html("");
    $("#gender_error_msg").html("");
    $("#course_error_msg").html("");
    $("#terms_error_msg").html("");
    $("#register_message").html("");
}
//resttrict from entring number in telephone text box
function restrictAlphabets(e) {
    var x = e.which || e.keycode;
    if ((x >= 48 && x <= 57) || x == 8 ||
        (x >= 35 && x <= 40) || x == 46)
        return true;
    else
        return false;
}
//login form validations part
$("#login_Name").on("change keyup keydown", function () {
    $loginName_check();
    $("#credential_error_msg").html("");
});
$("#login_Password").on("click change keyup keydown", function () {
    $("#credential_error_msg").html("");
    $loginPassword_check();
});
$loginName_check = function () {
    var name = $("#login_Name").val();
    if (name == "") {
        $("#loginName_error_msg").html(error_msg);
        $("#modalbutton_login").prop('disabled', true);
    }
    else
        $("#loginName_error_msg").html("");
    if ($("#loginName_error_msg").html() == "" && $("#loginPassword_error_msg").html() == "" && $("#login_Password").val() != "") {
        $("#modalbutton_login").prop('disabled', false);
        return 1;
    }
    else
        return 0;
}
$loginPassword_check = function () {
    $loginName_check();
    var password = $("#login_Password").val();
    if (password == "") {
        $("#loginPassword_error_msg").html(error_msg);
        $("#modalbutton_login").prop('disabled', true);
    }
    else
        $("#loginPassword_error_msg").html("");
    if ($("#loginName_error_msg").html() == "" && $("#loginPassword_error_msg").html() == "") {
        $("#modalbutton_login").prop('disabled', false);
        return 1;
    }
    else
        return 0;
}

//refresh login form onclick close
$("#close").on("click", function () {
    $('#form_login').trigger("reset");
    $("#loginName_error_msg").html("*");
    $("#loginPassword_error_msg").html("*");
    $("#credential_error_msg").html("");
})
$("#cancel_button").on("click", function () {
    $('#form_login').trigger("reset");
    $("#loginName_error_msg").html("*");
    $("#loginPassword_error_msg").html("*");
    $("#credential_error_msg").html("");
})

var login = function () {
    var x = $loginPassword_check();
    if (x == 1) {
        $dashboard();
    }
}
//validations part
$("#Name").bind("change keyup", function () {
    $name_check();
});
$("#Address").on("click", function () {
    $name_check();
});
$("#Address").bind("change keyup keydown", function () {
    $address_check();
});
$("#Email").on("click", function () {
    $address_check();
});
$("#Email").bind("change keyup keydown", function () {
    $email_check();
});
$("#Password").on("click", function () {
    $email_check();
});
$("#Password").bind("change  keyup keydown", function () {
    $password_check();
});
$("#Telephone").on("click", function () {
    $password_check();
});
$("#Telephone").bind("change keyup keydown", function () {
    $telephone_check();
});
$("input[name='gender']").on("click", function () {
    $gender_check();
});
$("#Course").on("click", function () {
    $gender_check();
});
$("#Course").on("change", function () {
    $course_check();
});
$("#Terms").on("click", function () {
    $course_check();
});
$("#Terms").on("change", function () {
    $terms_check();
});

$name_check = function () {
    var name = $("#Name").val();
    $("#register_message").html("");
    if (name == "")
        $("#name_error_msg").html(error_msg);
    else
        $("#name_error_msg").html("");
}
$address_check = function () {
    $name_check();
    var address = $("#Address").val();
    if (address == "")
        $("#address_error_msg").html(error_msg);
    else
        $("#address_error_msg").html("");
}
$email_check = function () {
    $address_check();
    var email = $("#Email").val();
    if (email == "")
        $("#mail_error_msg").html(error_msg);
    else if (!mailformat.test(email))
        $("#mail_error_msg").html("enter valid email");
    else
        $("#mail_error_msg").html("");
}
$password_check = function () {
    $email_check();
    var password = $("#Password").val();
    if (password == "")
        $("#password_error_msg").html(error_msg);
    else if (password.length < 8)
        $("#password_error_msg").html("enter strong password");
    else
        $("#password_error_msg").html("");
}
$telephone_check = function () {
    $password_check();
    var telephone = $("#Telephone").val();
    if (telephone == "")
        $("#phone_error_msg").html(error_msg);
    else if (telephone.length != 10)
        $("#phone_error_msg").html("please enter valid number");
    else
        $("#phone_error_msg").html("");
}
$gender_check = function () {
    $telephone_check();
    var gender = $("#gender:checked").length;
    if (gender == 0)
        $("#gender_error_msg").html(error_msg);
    else
        $("#gender_error_msg").html("");
}
$course_check = function () {
    $gender_check();
    var course = $("#Course option:selected").index();
    if (course > 0)
        $("#course_error_msg").html("");
    else
        $("#course_error_msg").html(error_msg);

}
$terms_check = function () {
    $course_check();
    if ($("#Terms:checked").length == 0)
        $("#terms_error_msg").html(error_msg);
    else
        $("#terms_error_msg").html("");
}
var register = function () {
    $terms_check();
    fields = "noerror";
    $("small").each(function () {
        if ($(this).text() != "")
            fields = "error";
    });
    if (fields == "noerror") {
        var formData = new FormData(RegForm);
        result = {};
        for (var entry of formData.entries()) {
            var name = entry[0];
            var value = entry[1];
            result[name] = value;
        }
        var datum = JSON.stringify(result);
        //console.log(datum);
        // $.post('/PHP/registration.php', ,
        $.ajax({
            method: "POST",
            url: "../../../slimapp/public/index.php/register",
            data: datum
        }).done(function (result) {
                response = JSON.parse(result.status);
                $('#RegForm').trigger("reset");
                if (response == true) {
                    $("#register_message").html("Registered Successfully");
                    console.log(JSON.parse(result.data).status);
                    localStorage.setItem('Id', JSON.parse(result.data).status);
                    //console.log(result.token);
                    localStorage.setItem('token', (result.token));
                    //var token = localStorage.getItem('token');
                    //console.log("hi" + token);
                }
                if ($("#register_message").html() == "Registered Successfully") {//true must be given to redirect on successful registration
                    window.location.href = "dashboard.php";
                }
            });
    }
}
$dashboard = function () {

    var formData = new FormData(form_login);
    result = {};
    for (var entry of formData.entries()) {
        var name = entry[0];
        var value = entry[1];
        result[name] = value;
    }
    var datum = JSON.stringify(result);
    //console.log(datum);
    $.ajax({
        method: "POST",
        url: "../../../slimapp/public/index.php/api/users",
        data: datum
    }).done(function (result) {
        console.log("hi");
        console.log(JSON.parse(result.data).status);
        if (result != null) {
            $('#form_login').trigger("reset");
            localStorage.setItem('Id', JSON.parse(result.data).status);
            localStorage.setItem('token', (result.token));
            //localStorage.getItem('token');
            //         var id = localStorage.getItem('id');
            // console.log("locali"+id);
            location = "dashboard.php";
        }
        else
            $("#credential_error_msg").html("Invalid credentials");
    })



    // $.ajax({
    //     method: "POST",
    //     url: "login.php",
    //     data: {
    //         login_Name: $("#login_Name").val(), 
    //         login_Password: $("#login_Password").val()
    //     }
    // }).done(function(result){
    //     if(result!=0)
    //     {
    //         $('#form_login').trigger("reset");
    //         window.location.href = "dashboard.php";
    //     }        
    //     else
    //     $("#credential_error_msg").html("Invalid credentials");
    // })
}