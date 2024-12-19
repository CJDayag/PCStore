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
    <title>MyTechPC | Login</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css" />

</head>

<body class="bg-[#f8f7f4]">
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


    <div class="container mx-auto px-4 py-8 flex items-center justify-center min-h-[calc(100vh-200px)]">
        <div class="w-full max-w-md">
            <form method="post" id="form" class="bg-[#fdfdfd] p-8 rounded-lg shadow-md">
                <h3 class="text-gray-800 text-2xl font-bold text-center mb-6">Login</h3>
                <div class="space-y-4">
                    <input 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" 
                        id="user" 
                        name="username" 
                        type="text" 
                        placeholder="Username *"
                        required
                    >
                    <input 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" 
                        id="pass" 
                        name="password" 
                        type="password" 
                        placeholder="Password *"
                        required
                    >
                    <button 
                        type="submit" 
                        class="w-full bg-[#222] text-white py-2 rounded-lg hover:bg-[#333] transition duration-200"
                        name="submit"
                    >
                        Login
                    </button>
                    <div class="text-left">
                        <a href="forgot-password.php" class="text-[#222] hover:underline">Forgot password?</a>
                    </div>
                </div>
            </form>

            <div class="text-center mt-4">
                <a href="signup.php" class="text-[#222] hover:underline">Do not have an account?</a>
            </div>
        </div>
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
