<?php
session_start();

if ($_SESSION['aid'] < 0) {
    header("Location: login.php");
}

if (isset($_GET['re'])) {
    include("include/connect.php");
    $aid = $_SESSION['aid'];
    $pid = $_GET['re'];
    $query = "DELETE FROM CART WHERE aid = $aid and pid = $pid";

    $result = mysqli_query($con, $query);
    header("Location: cart.php");
    exit();
}

if (isset($_POST['check'])) {
    include("include/connect.php");

    $aid = $_SESSION['aid'];

    $query = "SELECT * FROM cart JOIN products ON cart.pid = products.pid WHERE aid = $aid";

    $result = mysqli_query($con, $query);

    $result2 = mysqli_query($con, $query);
    $row2 = mysqli_fetch_assoc($result2);

    if (empty($row2['pid'])) {
        header("Location: shop.php");
        exit();
    }

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
        $a = $price * $cqty;

        $newqty = $_POST["$pid-qt"];

        $query = "UPDATE CART SET cqty = $newqty where aid = $aid and pid = $pid";

        mysqli_query($con, $query);


    }
    header("Location: checkout.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MyTechPC | Cart</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css" />


</head>

<body class="bg-[#f8f7f4]" onload="totala()">
    <section id="header">
        <a href="index.php"><img src="img/lg.png" class="logo" alt="" /></a>

        <div>
            <ul id="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="contact.php">Contact</a></li>

                <?php

                if ($_SESSION['aid'] < 0) {
                    echo "   <li><a href='login.php'>Login</a></li>
            <li><a href='signup.php'>Sign Up</a></li>
            ";
                } else {
                    echo "   <li><a href='profile.php'>My Profile</a></li>
          ";
                }
                ?>
                <li id="lg-bag">
                    <a class="active" href="cart.php"><i class="far fa-shopping-bag"></i></a>
                </li>
                <a href="#" id="close"><i class="far fa-times"></i></a>
            </ul>
        </div>
        <div id="mobile">
            <a href="cart.php"><i class="far fa-shopping-bag"></i></a>
            <i id="bar" class="fas fa-outdent"></i>
        </div>
    </section>

    <section class="container mx-auto px-4 py-8">
        <!-- Cart Table -->
        <div class="bg-white rounded-lg shadow-md overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remove</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    include("include/connect.php");
                    $aid = $_SESSION['aid'];
                    $query = "SELECT * FROM cart JOIN products ON cart.pid = products.pid WHERE aid = $aid";
                    $result = mysqli_query($con, $query);

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
                        $a = $price * $cqty;
                        $total = $a + 250;
                        echo "
                        <tr class='hover:bg-gray-50'>
                            <td class='px-6 py-4 whitespace-nowrap'>
                                <a href='cart.php?re=$pid' class='text-red-500 hover:text-red-700'>
                                    <i class='far fa-times-circle text-xl'></i>
                                </a>
                            </td>
                            <td class='px-6 py-4 whitespace-nowrap'>
                                <img src='product_images/$img' alt='$pname' class='h-20 w-20 object-cover rounded-lg'/>
                            </td>
                            <td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>$pname</td>
                            <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500 pr'>₱$price</td>
                            <td class='px-6 py-4 whitespace-nowrap'>
                                <input type='number' class='aqt w-20 px-2 py-1 text-sm border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500' 
                                    value='$cqty' min='1' max='$qty' onchange='subprice()'/>
                            </td>
                            <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900 atd'>₱$a</td>
                        </tr>
                        ";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Cart Summary -->
        <div class="mt-8 grid md:grid-cols-3 gap-6">
            <div class="md:col-span-2"></div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Cart Totals</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Cart Subtotal</span>
                        <span id="tot1" class="font-medium">₱0</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Handling Fee (Within Metro Manila)</span>
                        <span class="font-medium">₱250</span>
                    </div>
                    <div class="flex justify-between border-t pt-3">
                        <span class="text-gray-900 font-semibold">Total</span>
                        <span id="tot" class="text-gray-900 font-semibold">₱0</span>
                    </div>
                </div>

                <form method="post" class="mt-6">
                    <?php
                    include("include/connect.php");
                    $aid = $_SESSION['aid'];
                    $query = "SELECT * FROM cart JOIN products ON cart.pid = products.pid WHERE aid = $aid";
                    $result = mysqli_query($con, $query);

                    while ($row = mysqli_fetch_assoc($result)) {
                        $pid = $row['pid'];
                        $cqty = $row['cqty'];
                        echo "
                        <input type='hidden' name='$pid-p' class='inp' value='$pid'/>
                        <input type='hidden' name='$pid-qt' class='inq' value='$cqty'/>
                        ";
                    }
                    ?>
                    <button type="submit" name="check" 
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200">
                        Proceed to checkout
                    </button>
                </form>
            </div>
        </div>
    </section>

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
function subprice() {
    var qty = document.getElementsByClassName("aqt");
    var sub = document.getElementsByClassName("atd");
    var pri = document.getElementsByClassName("pr");
    var upd = document.getElementsByClassName("inq");

    for (var i = 0; i < qty.length; i++) {
        var quantity = parseInt(qty[i].value);
        var price = parseFloat(pri[i].innerText.replace('₱', ''));
        sub[i].innerHTML = `₱${quantity * price}`;
        upd[i].value = parseInt(qty[i].value);
    }

    totala();
}

function totala() {
    var pri = document.getElementsByClassName("atd");
    let yes = 0;
    for (var i = 0; i < pri.length; i++) {
        yes = yes + parseFloat(pri[i].innerText.replace('₱', ''));
    }

    // Add 250 to the total
    yes = yes + 250;

    document.getElementById('tot').innerHTML = '₱' + yes;
    document.getElementById('tot1').innerHTML = '₱' + yes;
}
</script>
