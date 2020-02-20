//ask for confirmation on reloading the tab
$(window).on("beforeunload", function () {
    return "Are you sure?";
});
var error_msg = "field required";
var mailformat = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
var head_counter = 0;
var fields = "error";
var rowCount = 0;

//sets all warnings to null
$clearwarnings = function () {
    $("#name_error_msg").html("");
    $("#address_error_msg").html("");
    $("#mail_error_msg").html("");
    $("#password_error_msg").html("");
    $("#phone_error_msg").html("");
    $("#gender_error_msg").html("");
    $("#course_error_msg").html("");
    $("#terms_error_msg").html("");
}
//resets the buttons on updation
var update_btn_reset = function () {
    $('#RegForm').trigger("reset");
    $clearwarnings();
    $("#button").html("<th><input type=\"reset\" value=\"Reset\" class=\"btn btn-primary btn-block\" onclick=\"$clearwarnings();\"</input></th><th><input type=\"button\" class=\"btn btn-primary btn-block\" id=\"btn\" onclick=\"register()\" value=\"Register\" name=\"btn\"></th>");
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
        $clearwarnings();
        create_table();
    }
}
//function to create table head
var create_table_head = function () {
    head_counter++;
    var table = $("#tbl");
    str = "<tr><td>Name</td><td>Address</td><td>Email</td><td>Telephone</td><td>Gender</td><td>Course</td><td>Operate</td></tr>";
    table.append(str);
}
var create_table = function () {
    if (head_counter == 0)       //creates table head if no head is created
    {
        create_table_head();
    }
    var table = $("#tbl");
    str = "<tr><td>" + $("#Name").val() + "</td><td>" + $("#Address").val() + "</td><td>" + $("#Email").val() + "</td><td>" + $("#Telephone").val() + "</td><td>" + $("input[name='gender']:checked").val() + "</td><td>" + $("#Course").val() + "</td><td><input type=\"button\" value=\"Delete\" id=\"table_delete_button\" class=\"button\" ></input><input type=\"button\" class=\"button\" value=\"Edit\" onclick=\"$update(this)\"></input></td></tr>";
    table.append(str);
    rowCount++;

    $('#RegForm').trigger("reset");

}
//delete table row
$(document).on('click', '#table_delete_button', function () {

    if (!confirm("Do you want to delete?")) {
        alert("cancelled");
        return false;
    }
    else {

        var $row = $(this).closest("tr"); // Get the .closest Row
        $row.remove();   // and delete it
        rowCount--;
        //if the rows are 0 remove the header
        if (rowCount == 0) {
            $("#tbl tr").remove();
            rowCount = 0;
            head_counter = 0;
        }
        exit;
    }

});
//populates data in table to form
$update = function (data) {
    $clearwarnings();
    var row = data.parentNode.parentNode.rowIndex;
    if ($("#Terms:checked").length == 0)
        $("#terms_error_msg").html(error_msg);
    else
        $("#terms_error_msg").html("");
    var table_name = $("table > tr:eq(" + row + ")>td:eq(0)").html();
    var table_address = $("table > tr:eq(" + row + ")>td:eq(1)").html();
    var table_email = $("table > tr:eq(" + row + ")>td:eq(2)").html();
    var table_telephone = $("table > tr:eq(" + row + ")>td:eq(3)").html();
    var table_gender = $("table > tr:eq(" + row + ")>td:eq(4)").html();
    var table_course = $("table > tr:eq(" + row + ")>td:eq(5)").html();

    $("#Name").val(table_name);
    $("#Address").val(table_address);
    $("#Email").val(table_email);
    $("#Telephone").val(table_telephone);
    var table_genderOptions = $('[name="gender"]');
    for (var j = 0; j < table_genderOptions.length; j++) {
        if (table_genderOptions[j].value == table_gender) {
            table_genderOptions[j].checked = "checked";
            break;
        }
    }
    $("#Course").val(table_course);
    $("#Password").val("********");
    $("#button").html("<th><input type=\"button\" id=\"reset_button\" value=\"Reset\" class=\"btn btn-primary btn-block\" onclick=\"update_btn_reset();\"</input></th><th><input type=\"button\" value=\"Update\" class=\"btn btn-primary btn-block\" id=\"update_btn\"  onclick=\"updatesubmit(" + row + ");\"></input></th>");
}

//submits the updated data
var updatesubmit = function (row) {
    fields = "noerror";
    $("small").each(function () {
        if ($(this).text() != "")
        {
            fields = "error";
            console.log("1");
        }
    });
    console.log(fields);
    if (fields == "noerror") {
        console.log("2");

        $("table > tr:eq(" + row + ")>td:eq(0)").html($("#Name").val());
        $("table > tr:eq(" + row + ")>td:eq(1)").html($("#Address").val());
        $("table > tr:eq(" + row + ")>td:eq(2)").html($("#Email").val());
        $("table > tr:eq(" + row + ")>td:eq(3)").html($("#Telephone").val());
        $("table > tr:eq(" + row + ")>td:eq(4)").html($("input[name='gender']:checked").val());
        $("table > tr:eq(" + row + ")>td:eq(5)").html($("#Course").val());
        $('#RegForm').trigger("reset");
        update_btn_reset();
    }
}