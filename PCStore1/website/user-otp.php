<?php require_once "controllerUserData.php"; ?>
<?php 
$email = $_SESSION['email'];
if ($email == false) {
    header('Location: login.php');
    exit();
}

if (isset($_POST['check'])) {
    // Retrieve the verification code submitted by the user
    $otp_code = mysqli_real_escape_string($con, $_POST['otp']);

    // Check the submitted code against the one stored in the database
    $check_code = "SELECT * FROM accounts WHERE email = '$email' AND code = $otp_code";
    $code_res = mysqli_query($con, $check_code);

    if (mysqli_num_rows($code_res) > 0) {
        // Verification successful
        $fetch_data = mysqli_fetch_assoc($code_res);

        // Redirect the user to the desired page
        header('Location: index.php');
        exit();
    } else {
        // Incorrect verification code
        $errors['otp-error'] = "You've entered incorrect code!";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Code Verification</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap');
        html,body{
  background: #e3e6f3;
  font-family: 'Montserrat', sans-serif;
}
::selection{
  color: #fff;
  background: #6665ee;
}
.container{
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
.container .form{
  background: #fff;
  padding: 30px 35px;
  border-radius: 5px;
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
}
.container .form form .form-control{
  height: 40px;
  font-size: 15px;
}
.container .form form .forget-pass{
  margin: -15px 0 15px 0;
}
.container .form form .forget-pass a{
 font-size: 15px;
}
.container .form form .button{
  background: #6665ee;
  color: #fff;
  font-size: 17px;
  font-weight: 500;
  transition: all 0.3s ease;
}
.container .form form .button:hover{
  background: #5757d1;
}
.container .form form .link{
  padding: 5px 0;
}
.container .form form .link a{
  color: #6665ee;
}
.container .login-form form p{
  font-size: 14px;
}
.container .row .alert{
  font-size: 14px;
}
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form action="user-otp.php" method="POST" autocomplete="off">
                    <h2 class="text-center">Code Verification</h2>
                    <?php
                    if (count($errors) > 0) {
                        ?>
                        <div class="alert alert-danger text-center">
                            <?php
                            foreach ($errors as $showerror) {
                                echo $showerror;
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="form-group">
                        <input class="form-control" type="number" name="otp" placeholder="Enter verification code" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control button" type="submit" name="check" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>
