<?php
session_start();
include("include/connect.php");

if (isset($_POST['submit'])) {

  $username = $_POST['username'];
  $password = $_POST['password'];

  if ($username == "admin1") {

    $query = "select * from admin where username='$username' and password='$password'";
    $result = mysqli_query($con, $query);


    if (mysqli_num_rows($result) > 0) {
      echo "<script> window.open('inventory.php', '_blank') </script>";


    } else {
      echo "<script> alert('Wrong credentials') </script>";
    }

  } else {
    echo "<script> alert('Wrong credentials') </script>";
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MyTechPC</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />

    <link rel="stylesheet" href="style.css" />

</head>

<body>
    <section id="header">
        <a href="index.php"><img src="img/lg.png" class="logo" alt="" /></a>

        
    </section>


    <form method="post" id="form">
        <h3 style="color: darkred; margin: auto"></h3>
        <input class="input1" id="user" name="username" type="text" placeholder="Username *">
        <input class="input1" id="pass" name="password" type="password" placeholder="Password *">
        <button type="submit" class="btn" name="submit">login</button>

    </form>


    <footer class="section-p1">
        <div class="col">
            <img class="logo" src="img/lg.png" />
        </div>

        <div class="col">
        <h4>Contact</h4>
            <p>
                <strong>Email: </strong> mytechpc@gmail.com

            </p>
            <p>
                <strong>Phone: </strong> 09355498379
            </p>
            <p>
                <strong>Hours: </strong> 9am-5pm
            </p>
        </div>

        <div class="copyright">
            <p>2023. MytechPC.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>

</html>