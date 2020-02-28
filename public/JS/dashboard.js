$(document).ready(function () {

    var Id = localStorage.getItem('Id');
    // console.log(Id);
    // console.log("hi");
   var token = localStorage.getItem('token');
  //console.log(token);
   if(token==null)
   {
       location = "index.html";
       return false;
   }
    // var data = JSON.stringify(Id);
    // console.log(data);
    $.ajax({
        method: 'get',
        url: '../../../slimapp/public/index.php/api/users/'+Id,
        async: false,
        headers: {"Authorization": token},
        datatype: 'html',
        success: function (response) {
            $data = response.result;
           // console.log(response.result);
            var create_table = function () {
                var table = $("#tbl");
                //i = submcounter();
                str = "<tr><td>Id</td><td>"+response.result.Id+"</td></tr><tr><td>Name</td><td>" + response.result.Name + "</td></tr><tr><td>Address</td><td>" + response.result.Address + "</td></tr><tr><td>Email</td><td>" +response.result.Email +
                "</td></tr><tr><td>Telephone</td><td>" + response.result.Telephone + "</td></tr><tr><td>Gender</td><td>" + response.result.Gender + "</td></tr><tr><td>Course</td><td>" + response.result.Course + "</td></tr>";
                table.append(str);
            }
            create_table();
        }
    })


    // $.ajax({
    //     method: 'post',
    //     url: 'fetch.php',
    //     async: false,
    //     data: $('#tbl').serialize(),
    //     datatype: 'html',
    //     success: function (response) {
    //         $data = JSON.parse(response);

    //         var create_table = function () {
    //             var table = $("#tbl");
    //             //i = submcounter();
    //             str = "<tr><td>Name</td><td>" + $data[0] + "</td></tr><tr><td>Address</td><td>" + $data[1] + "</td></tr><tr><td>Email</td><td>" + $data[2] +
    //                 "</td></tr><tr><td>Telephone</td><td>" + $data[4] + "</td></tr><tr><td>Gender</td><td>" + $data[5] + "</td></tr><tr><td>Course</td><td>" + $data[6] + "</td></tr>";
    //             table.append(str);
    //         }
    //         create_table();
    //     }
    // })
});


$delete = function () {
    var Id = localStorage.getItem('Id');
    //var datum = JSON.stringify(Id);
    // console.log(datum);
    $.ajax({
        method: "DELETE",
        url: "../../../slimapp/public/index.php/api/users/"+Id,
       // data :id
    }).done(function (result) {
        console.log(result);
       if(JSON.parse(result.success) == true){
            $logout();
       }
    })
}
$update = function () {
    //console.log($data);
    //console.log($data.Name);
    $("#Name").val($data.Name);
    $("#Address").val($data.Address);
    $("#Email").val($data.Email);
    var table_genderOptions = $('[name="gender"]');
    for (var j = 0; j < table_genderOptions.length; j++) {
        if (table_genderOptions[j].value == $data.Gender) {
            table_genderOptions[j].checked = "checked";
            break;
        }
    }
    console.log("hi"+$data.Course);
    $("#Course").val($data.Course);
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
$save_data = function () {
    var Id = localStorage.getItem('Id');
    var formData = new FormData(form_login);
        result = {};
        for (var entry of formData.entries()) {
            var name = entry[0];
            var value = entry[1];
            result[name] = value;
        }
        var datum = JSON.stringify(result);
        console.log(datum);
        var token = localStorage.getItem('token');
        console.log(token)
    $.ajax({
        method: "PUT",
        url: "../../../slimapp/public/index.php/api/users/"+Id,
        data: datum,
        headers: {"Authorization": token}
    })
        .done(function (result) {
            console.log(result);
            if (result.success == true) {
                document.getElementById('div_edit').style.display = 'none';
                window.location.href = "dashboard.php";
            }
            else
                $("#update_error_msg").html(result);
        });
}
$logout = function(){
    localStorage.clear();
    location = "index.html";
}