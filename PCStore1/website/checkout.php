<?php
session_start();

if (isset($_POST['sub'])) {
    include("include/connect.php");

    $aid = $_SESSION['aid'];
    $add = $_POST['houseadd'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $acc = $_POST['acc'];
    $query = "";

    if (empty($acc)) {
        $query = "insert into `orders` (dateod, datedel, aid, address, city, country, account, total) values(CURDATE(), NULL, '$aid', '$add', '$city', '$country', NULL, 0)";
    } else {
        if (preg_match('/\D/', $acc) || strlen($acc) != 16) {
            echo "<script> alert('invalid account number'); setTimeout(function(){ window.location.href = 'checkout.php'; }, 100); </script>";
            exit();
        }

        $query = "insert into `orders` (dateod, datedel, aid, address, city, country, account, total) values(CURDATE(), NULL, '$aid', '$add', '$city', '$country', '$acc', 0)";
    }
    $result = mysqli_query($con, $query);

    $oid = mysqli_insert_id($con);

    $query = "SELECT * FROM cart JOIN products ON cart.pid = products.pid WHERE aid = $aid";

    $result = mysqli_query($con, $query);
    global $tott;
    while ($row = mysqli_fetch_assoc($result)) {
        $pid = $row['pid'];
        $pname = $row['pname'];
        $desc = $row['description'];
        $qty = $row['qtyavail'];
        $price = $row['price'];
        $cat = $row['category'];
        $img = $row['img'];
        $brand = $row['brand'];
        $cqty = $row['cqty'];
        $tott = $price * $cqty;

        $query = "insert into `order-details` (oid, pid, qty) values ($oid, $pid, $cqty)";

        mysqli_query($con, $query);

        $query = "update products set qtyavail = qtyavail - $cqty where pid = $pid";

        mysqli_query($con, $query);
    }

    $query = "delete from cart where aid = $aid";

    mysqli_query($con, $query);

    $query = "update orders set total = $tott where oid = $oid";

    mysqli_query($con, $query);


    header("Location: profile.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MyTechPC | Checkout</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css" />

    <style>
        #account-field {
            display: block;
        }

        .hidden {
            display: none;
        }
        .input11 {
  display: block;
  width: 80%;
  margin: 40px auto;
  padding: 10px 5px;
  border: none;
  border-bottom: 0.01rem dimgray solid;
  outline: none;
}

.table12 {
  margin: 0;
  padding: 0;
  width: 100%;
  overflow: auto;
}

.table12 tr{
    width: 100%;
  overflow: auto;

}

    </style>

</head>

<body class="bg-[#faf9f6]">
    <section id="header">
        <a href="index.php"><img src="img/lg.png" class="logo" alt="" /></a>

        <div>
            <ul id="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="contact.php">Contact</a></li>

                <?php

                if ($_SESSION['aid'] < 0) {
                    echo "   <li><a href='login.php'>login</a></li>
            <li><a href='signup.php'>SignUp</a></li>
            ";
                } else {
                    echo "   <li><a href='profile.php'>My Profile</a></li>
          ";
                }
                ?>
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

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Checkout</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Order Summary -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold mb-6">Order Summary</h3>
                    <div class="space-y-4">
                        <?php
                        include("include/connect.php");
                        $aid = $_SESSION['aid'];
                        $query = "SELECT * FROM cart JOIN products ON cart.pid = products.pid WHERE aid = $aid";
                        $result = mysqli_query($con, $query);
                        global $tot;

                        while ($row = mysqli_fetch_assoc($result)) {
                            $pid = $row['pid'];
                            $pname = $row['pname'];
                            $price = $row['price'];
                            $cqty = $row['cqty'];
                            $a = $price * $cqty;
                            $tot += $a;
                            
                            echo "
                            <div class='flex justify-between py-2 border-b'>
                                <div class='text-gray-600'>
                                    <span class='font-medium'>$pname</span>
                                    <span class='text-sm'> × $cqty</span>
                                </div>
                                <span class='font-medium'>₱$a</span>
                            </div>";
                        }

                        $tot += 250;
                        
                        echo "
                        <div class='flex justify-between py-2'>
                            <span class='text-gray-600'>Subtotal</span>
                            <span class='font-medium'>₱$tot.00</span>
                        </div>
                        <div class='flex justify-between py-2 border-b'>
                            <span class='text-gray-600'>Handling Fee (Metro Manila)</span>
                            <span class='font-medium'>₱250</span>
                        </div>
                        <div class='flex justify-between py-2 text-lg font-bold'>
                            <span>Total</span>
                            <span>₱$tot</span>
                        </div>";
                        ?>
                    </div>
                </div>

                <!-- Shipping Form -->
                <div class="bg-white rounded-lg shadow-lg p-8 border border-gray-200">
                    <h3 class="text-2xl font-semibold mb-8 text-gray-900 border-b pb-4">Shipping Details</h3>
                    <form method="post" id="form1" class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-2">Address</label>
                            <input 
                                type="text" 
                                name="houseadd" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent transition duration-200"
                                placeholder="Enter your complete address"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-2">City</label>
                            <input 
                                type="text" 
                                name="city" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent transition duration-200"
                                placeholder="Enter your city"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-2">County/State</label>
                            <input 
                                type="text" 
                                name="country" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent transition duration-200"
                                placeholder="Enter your county/state"
                            >
                        </div>
                        
                        <div class="mt-8">
                            <h4 class="text-lg font-semibold mb-4 text-gray-900">Payment Method</h4>
                            <label class="flex items-center space-x-3 p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition duration-200">
                                <input 
                                    type="radio" 
                                    id="ac1" 
                                    name="dbt" 
                                    value="cod" 
                                    onchange="showInputBox()"
                                    class="h-5 w-5 text-blue-600 focus:ring-blue-400"
                                    checked
                                >
                                <span class="text-gray-800 font-medium">Cash on Delivery</span>
                            </label>
                        </div>

                        <button 
                            name="sub" 
                            type="submit" 
                            class="w-full bg-blue-600 text-white py-4 px-6 rounded-lg hover:bg-blue-700 transition duration-300 mt-8 font-semibold text-lg shadow-md hover:shadow-lg"
                        >
                            Place Order
                        </button>
                    </form>
                </div>
            </div>
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
            <p>2023. MyTechPC.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>

</html>

<script>
    function showInputBox() {
        var select = document.querySelector('#ac1');
        var inputBox = document.getElementById("account-field");
        if (!select.checked) {
            inputBox.style.display = "block";
        } else {
            inputBox.style.display = "none";
        }
    }
</script>

<script>
window.addEventListener("unload", function() {
  // Call a PHP script to log out the user
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "logout.php", false);
  xhr.send();
});
</script>