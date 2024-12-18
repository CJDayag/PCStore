<?php
session_start();

include("include/connect.php");

if (isset($_POST['submit'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM accounts WHERE username='" . mysqli_real_escape_string($con, $username) . "' AND password='" . mysqli_real_escape_string($con, $password) . "'";

    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_assoc($result);

        $_SESSION['aid'] = $row['aid'];

        header("Location: profile.php");
        exit();

    } else {
        // More informative error message
        echo "<script>alert('Invalid username or password. Please try again.');</script>";
    }

    // Important: Close the database connection
    mysqli_close($con);
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
        <a href="#"><img src="img/lg.png" class="logo" alt="" /></a>

        <div>
            <ul id="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a class="active" href="login.php">Login</a></li>
                <li><a href="signup.php">Sign Up</a></li>
                <li id="lg-bag">
                    <a href="cart.php"><i class="far fa-shopping-bag"></i></a>
                </li>
                <a href="#" id="close"><i class="far fa-times"></i></a>
            </ul>
        </div>
        <div id="mobile">
            <a href="cart.php"><i class="far fa-shopping-bag"></i></a>
            <i id="bar" class="fas fa-outdent"></i>
        </div>
    </section>


    <form method="post" id="form">
        <h3 style="color: darkred; margin: auto"></h3>
        <input class="input1" id="user" name="username" type="text" placeholder="Username *">
        <input class="input1" id="pass" name="password" type="password" placeholder="Password *"> 
        <button type="submit" class="btn" name="submit">login</button>
        <div class="link forget-pass text-left"><a href="forgot-password.php">Forgot password?</a></div>

    </form>

    <div class="sign">
        <a href="signup.php" class="signn">Do not have an account?</a>
    </div>


    <footer class="section-p1">
        <div class="col">
            <img class="logo" src="img/lg.png" />
            <h4>Contact</h4>
            <p>
                <strong>Email: </strong> MyechPC@gmail.com

            </p>
            <p>
                <strong>Phone: </strong> 09355498379
            </p>
            <p>
                <strong>Hours: </strong> 9am-5pm
            </p>
        </div>

        <div class="col">
            <h4>My Account</h4>
            <a href="cart.php">View Cart</a>
        </div>
        <div class="copyright">
            <p>Copyright © 2023 My Tech Pc</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>

</html>

<script>
window.addEventListener("unload", function() {
  // Call a PHP script to log out the user
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "logout.php", false);
  xhr.send();
});
</script>
