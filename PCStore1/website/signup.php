<?php
include("include/connect.php");

if (isset($_POST['submit'])) {
    $firstname = $_POST['firstName'];
    $lastname = $_POST['lastName'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmpassowrd = $_POST['confirmPassword'];
    $dob = $_POST['dob'];
    $contact = $_POST['phone'];
    $gen = $_POST['gender'];
    $email = $_POST['email'];

    $query = "select * from accounts where username = '$username' or phone='$contact' or email='$email'";

    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    if (!empty($row['aid'])) {
        echo "<script> alert('Credentials already exists'); setTimeout(function(){ window.location.href = 'signup.php'; }, 100); </script>";
        exit();
    }
    if ($password != $confirmpassowrd) {
        echo "<script> alert('Passwords do not match'); setTimeout(function(){ window.location.href = 'signup.php'; }, 100); </script>";
        exit();
    }

    if (strtotime($dob) > time()) {
        echo "<script> alert('invalid date'); setTimeout(function(){ window.location.href = 'signup.php'; }, 100); </script>";
        exit();
    }
    if ($gen == "S") {
        echo "<script> alert('select gender'); setTimeout(function(){ window.location.href = 'signup.php'; }, 100); </script>";
        exit();
    }
    
    if (preg_match('/\D/', $contact) || strlen($contact) != 11) {
        echo "<script> alert('invalid number'); setTimeout(function(){ window.location.href = 'signup.php'; }, 100); </script>";
        exit();
    }

    $query = "insert into `accounts` (afname, alname, phone, email, dob, username, gender,password) values ('$firstname', '$lastname', '$contact','$email', '$dob', '$username', '$gen','$password')";

    $result = mysqli_query($con, $query);

    if ($result) {
        echo "<script> alert('Successfully entered account'); setTimeout(function(){ window.location.href = 'login.php'; }, 100); </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MyTechPC | Sign up</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css" />
</head>

<body class="bg-[#faf9f6]">
    <section id="header" class="sticky top-0 z-50 bg-white shadow-md">
        <a href="#"><img src="img/lg.png" class="logo w-24" alt="" /></a>

        <div>
            <ul id="navbar" class="md:flex md:items-center">
                <li><a href="index.php" class="hover:text-blue-600">Home</a></li>
                <li><a href="shop.php" class="hover:text-blue-600">Shop</a></li>
                <li><a href="contact.php" class="hover:text-blue-600">Contact</a></li>
                <li><a href="login.php" class="hover:text-blue-600">Login</a></li>
                <li><a class="active hover:text-blue-600" href="signup.php">Sign Up</a></li>
                <li id="lg-bag">
                    <a href="cart.php"><i class="far fa-shopping-bag"></i></a>
                </li>
                <a href="#" id="close"><i class="far fa-times"></i></a>
            </ul>
        </div>
        <div id="mobile" class="md:hidden">
            <a href="cart.php"><i class="far fa-shopping-bag"></i></a>
            <i id="bar" class="fas fa-outdent"></i>
        </div>
    </section>

    <div class="container mx-auto px-4 py-8">
        <form method="post" class="max-w-xl mx-auto bg-[#fdfdfd] p-8 rounded-lg shadow-md">
            <h3 class="text-2xl font-bold text-center mb-6 text-gray-800">Sign Up</h3>
            
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-[#222]" 
                        id="fn" name="firstName" type="text" placeholder="First Name *" required>
                    
                    <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-[#222]" 
                        id="ln" name="lastName" type="text" placeholder="Last Name *" required>
                </div>
                
                <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-[#222]" 
                    id="user" name="username" type="text" placeholder="Username *" required>
                
                <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-[#222]" 
                    id="email" name="email" type="email" placeholder="Email *" required>
                
                <div class="grid grid-cols-2 gap-4">
                    <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-[#222]" 
                        id="pass" name="password" type="password" placeholder="Password *" required>
                    
                    <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-[#222]" 
                        id="cpass" name="confirmPassword" type="password" placeholder="Confirm Password *" required>
                </div>
                
                <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-[#222]" 
                    id="dob" name="dob" type="date" required>
                
                <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-[#222]" 
                    id="contact" name="phone" type="number" placeholder="Contact *" required>
                
                <select class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-[#222]" 
                    id="gen" name="gender" required>
                    <option value="S">Select Gender</option>
                    <option value="M">Male</option>
                    <option value="F">Female</option>
                </select>

                <button name="submit" type="submit" 
                    class="w-full bg-[#222] text-white py-2 rounded-lg hover:bg-[#333] transition duration-200">
                    Register
                </button>
            </div>
        </form>

        <div class="text-center mt-4">
            <a href="login.php" class="text-[#222] hover:underline">Already have an account?</a>
        </div>
    </div>

    <footer class="section-p1">
        <div class="col">
            <img class="logo" src="img/lg.png" />
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
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "logout.php", false);
    xhr.send();
});
</script>