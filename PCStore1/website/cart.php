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
    <title>MyTechPC</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />

    <link rel="stylesheet" href="style.css" />


</head>

<body onload="totala()">
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

    <section id="cart" class="section-p1">
        <table width="100%">
            <thead>
                <tr>
                    <td>Remove</td>
                    <td>Image</td>
                    <td>Product</td>
                    <td>Price</td>
                    <td>Quantity</td>
                    <td>Subtotal</td>
                </tr>
            </thead>
            <tbody>

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

            <tr>
              <td>
                <a href='cart.php?re=$pid'><i class='far fa-times-circle'></i></a>
              </td>
              <td><img src='product_images/$img' alt='' /></td>
              <td>$pname</td>
              <td class='pr'>₱$price</td>
              <td><input type='number' class = 'aqt' value='$cqty' min = '1' max = '$qty' onchange='subprice()' /></td>
              <td class = 'atd'>₱$a</td>
            </tr>
            ";
                }
                ?>

            </tbody>
        </table>
    </section>

    <section id="cart-add" class="section-p1">
        <div id="coupon">

        </div>
        <div id="subtotal">
            <h3>Cart Totals</h3>
            <table>
                <tr>
                    <td>Cart Subtotal</td>
                    <td id='tot1' onload="totala()">₱</td>
                </tr>
                <tr>
                    <td>Handling Fee (Within Metro Manila)</td>
                    <td>₱250</td>
                </tr>
                <tr>
                    <td><strong>Total</strong></td>
                    <td id='tot' onload="totala()"><strong>₱</strong></td>
                </tr>
            </table>

            <form method="post">
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

              <input style='display: none;' name='$pid-p' class='inp' type = 'number' value = '$pid'/>
              <input style='display: none;' name='$pid-qt' class='inq' type = 'number' value = '$cqty'/>
              ";
                }
                ?>
                <button class="normal" name="check">Proceed to checkout</button>
            </form>
            </a>
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
