<?php require_once "controllerUserData.php"; ?>
<?php
if($_SESSION['info'] == false){
    header('Location: login.php');  
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Form</title>
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
  background: navy;
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
            <div class="col-md-4 offset-md-4 form login-form">
            <?php 
            if(isset($_SESSION['info'])){
                ?>
                <div class="alert alert-success text-center">
                <?php echo $_SESSION['info']; ?>
                </div>
                <?php
            }
            ?>
                <form action="login.php" method="POST">
                    <div class="form-group">
                        <input class="form-control button" type="submit" name="login-now" value="Login Now">
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>