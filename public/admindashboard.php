<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admindashboard</title>
    <!-- custom CSS -->
    <link rel="stylesheet" href="CSS\styles.css">
    <link rel="stylesheet" href="CSS\modal.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>

<body>
    <?php
    session_start();
    if (!isset($_SESSION['user_name'])) {
        header("location: index.html");
    }
    ?>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="navbar-collapse" id="navbarCollapse" style="padding-right:0px;right:0; ">
            <button type="submit" onclick="$logout()" id="logout" class="btn btn-outline-light">Logout</button>

        </div>
    </nav>
    <table id="tbl" class="table text-black">
    </table>
    <div id="div_edit" align="center" class="modal">
        <div style="width: 30%;right: 60px;">
          <form name="form_login" id="form_login" class="modal-content animate" method="POST">
            <div class="imgcontainer">
              <span onclick="document.getElementById('div_edit').style.display='none'" class="close" id="close" title="Close Modal">&times;</span>
            </div>
            <div class="container" style="padding: 16px; ">
            
              <label>Fullname:</label><sup class="text-danger">*</sup><small id="name_error_msg" name="error_msg" class="text-danger"></small>
              <input type="text" class="form-control" id="Name" placeholder="Fullname" class="value" name="Name">
              <label>Address:</label><sup class="text-danger">*</sup> <small id="address_error_msg" name="error_msg" class="text-danger"></small>
              <input type="text" class="form-control" id="Address" placeholder="Complete Address" class="class" name="Address">
              <label>E-mail Address:</label><sup class="text-danger">*</sup><small id="mail_error_msg" name="error_msg" class="text-danger"></small>
              <input type="email" class="form-control" id="Email" placeholder="example@email.com" class="value" name="Email">
              <label>Password:</label><sup class="text-danger">*</sup><small id="password_error_msg" name="error_msg" class="text-danger"></small>
              <input type="password" class="form-control" id="Password" placeholder="must be 8-20 characters long" class="value" name="Password">
              <label>Telephone:</label><sup class="text-danger">*</sup><small id="phone_error_msg" name="error_msg" class="text-danger"></small>
              <input type="number" class="form-control" id="Telephone" placeholder="10 digit number" class="value" name="Telephone" disabled>
              <label>Gender:</label><sup class="text-danger">*</sup><small id="gender_error_msg" name="error_msg" class="text-danger"></small><br>
              <input type="radio" id="gender" name="gender" class="valu" value="male">Male
              <input type="radio" class="valu" id="gender" name="gender" value="female">Female
              <input type="radio" name="gender" id="gender" class="valu" value="other">Other<br>
              <label>Course:</label><sup class="text-danger">*</sup><small id="course_error_msg" name="error_msg" class="text-danger"></small>
              <select type="text" class="form-control" class="value" id="Course" name="Course">
                <option>BTECH</option>
                <option>BBA</option>
                <option>BCA</option>
                <option>B.COM</option>
              </select>
            </div>
            <div class="container" style="background-color:#f1f1f1">
              <button type="button" id="button" onclick="$verify();" class="btn btn-success">Verify</button>
              <button type="button" id="button" onclick="$save_data();" class="save_button btn btn-success" disabled>Save</button>
            </div>
          </form>
        </div>


   
</body>
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="JS\jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<!--custom js-->
<script src="JS/admindashboard.js"></script>

</html>