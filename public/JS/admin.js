var error_msg = "field required";
$("#admin_Name").on("change keyup keydown", function () {
    $adminName_check();
    $("#credential_error_msg").html("");
});
$("#admin_Password").on("click change keyup keydown", function () {
    $("#credential_error_msg").html("");
    $adminPassword_check();
});
$adminName_check = function () {
    var name = $("#admin_Name").val();
    if (name == "") {
        $("#adminName_error_msg").html(error_msg);
        $("#modalbutton_login").prop('disabled', true);
    }
    else
        $("#adminName_error_msg").html("");
    if ($("#adminName_error_msg").html() == "" && $("#adminPassword_error_msg").html() == "" && $("#admin_Password").val() != "") {
        $("#modalbutton_login").prop('disabled', false);
        return 1;
    }
    else
        return 0;
}
$adminPassword_check = function () {
    $adminName_check();
    var password = $("#admin_Password").val();
    if (password == "") {
        $("#adminPassword_error_msg").html(error_msg);
        $("#modalbutton_login").prop('disabled', true);
    }
    else
        $("#adminPassword_error_msg").html("");
    if ($("#adminName_error_msg").html() == "" && $("#adminPassword_error_msg").html() == "") {
        $("#modalbutton_admin").prop('disabled', false);
        return 1;
    }
    else
        return 0;
}
var adminlogin = function () {
    var x = $adminPassword_check();
    if (x == 1) {
        $admindashboard();
    }
}
$admindashboard = function () {
    var formData = new FormData(form_adminlogin);
    result = {};
    for (var entry of formData.entries()) {
        var name = entry[0];
        var value = entry[1];
        result[ name ] = value;
    }
    $.ajax({
        method: "POST",
        url: "validateadmin.php",
        data: result,
    }).done(function (result) {
        if (result != "invalid credentials" ) {
            $('#form_adminlogin').trigger("reset");
           location = "admindashboard.php"
        }
        else
            $("#credential_error_msg").html(result);
    })
}
