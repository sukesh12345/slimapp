$(document).ready(function () {
    var head_counter = 0;
    $.ajax({
        method: 'post',
        url: 'alldata.php',
        async: false,
        data: $('#tbl').serialize(),
        datatype: 'html',
        success: function (response) {
            $data = JSON.parse(response);
            i = 0;
           if(!$data[0])
            {
                alert("we have got no users");
            }
            var create_table_head = function () {
                head_counter++;
                var table = $("#tbl");
                str = "<tr class=\"bg-info text-white\"><td>Id</td><td>Name</td><td>Address</td><td>Email</td><td>Password</td><td>Telephone</td><td>Gender</td><td>Course</td><td>Operate</td></tr>";
                table.append(str);
            }
            var create_table = function () {
                if (head_counter == 0)       //creates table head if no head is created
                {
                    create_table_head();
                }
                var table = $("#tbl");
                str = "<tr><td>" + $data[i + 0] + "</td><td>" + $data[i + 1] + "</td><td>" + $data[i+2] + "</td><td>" + $data[i+3] + "</td><td>" + $data[i+4] + "</td><td>" + $data[i+5] + "</td><td>" + $data[i+6] +"</td><td>" + $data[i+7] + "</td><td><input type=\"button\" onclick=\"$deactivate(this)\" value=\"Deactivate\" id=\"deactivate\" class=\"button\" ></input><input type=\"button\" class=\"button\" value=\"Edit\" onclick=\"$update(this)\"></input></td></tr>";
                table.append(str);


                $('#RegForm').trigger("reset");

            }
            while($data[i+6]){
                create_table();
                i=i+7;
            }
           
        }
    })
})
var error_msg = 'field required';
var mailformat = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;

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
    if (password == ""){
        console.log(password);
        $("#password_error_msg").html(error_msg);
    }    
    else if (password.length < 8)
        $("#password_error_msg").html("enter strong password");
    else
        $("#password_error_msg").html("");
}

$gender_check = function () {
    $email_check();
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
$verify = function () {
    var fields = "noerror";
    $("small[name='error_msg']").each(function () {
        if ($(this).text() != "")
            fields = "error";
    });
    if (fields == "noerror") {
        $(".save_button").prop("disabled", false);
    }
    else $(".save_button").prop("disabled", true);
}
$update = function(data){
    document.getElementById('div_edit').style.display='block';
    var row = data.parentNode.parentNode.rowIndex;
    $("#Name").val( $("table > tr:eq(" + row + ")>td:eq(0)").html());
    $("#Address").val($("table > tr:eq(" + row + ")>td:eq(1)").html());
    $("#Email").val($("table > tr:eq(" + row + ")>td:eq(2)").html());
    $("#Password").val($("table > tr:eq(" + row + ")>td:eq(3)").html());
    $("#Telephone").val($("table > tr:eq(" + row + ")>td:eq(4)").html());
    var table_genderOptions = $('[name="gender"]');
    for (var j = 0; j < table_genderOptions.length; j++) {
        if (table_genderOptions[j].value == $("table > tr:eq(" + row + ")>td:eq(5)").html()) {
            table_genderOptions[j].checked = "checked";
            break;
        }
    }
    $("#Course").val($("table > tr:eq(" + row + ")>td:eq(6)").html());
}
$logout = function(){
    location = "logout.php";
}
$deactivate = function(data){
    var row = data.parentNode.parentNode.rowIndex;
    var number= $("table > tr:eq(" + row + ")>td:eq(4)").html();
    $.ajax({
        method: "POST",
        url: "admindelete.php",
        data :{
            user_name: number
        }
    }).done(function (result) {
        location = "admindashboard.php";
    })
}
$save_data = function () {
    $.ajax({
        method: "POST",
        url: "adminupdate.php",
        data: {
            Name: $("#Name").val(), Address: $("#Address").val(), Email: $("#Email").val(),
            Password: $("#Password").val(), Telephone: $("#Telephone").val(), Gender: $("input[name='gender']:checked").val(),
            Course: $("#Course").val()
        }
    })
        .done(function (result) {
            $("#mail_error_msg").html(result);
            if (result == "updated") {
                $('#form_login').trigger("reset");
                window.location.href = "admindashboard.php";
            }
        });

}
