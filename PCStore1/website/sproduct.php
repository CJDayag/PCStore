<?php
session_start();

if (isset($_POST['submit'])) {
  include("include/connect.php");
  $pid = $_GET['pid'];
  $aid = $_SESSION['aid'];
  $qty = $_POST['qty'];

  if ($aid < 0) {
    header("Location: login.php");
    exit();
  }

  $query = "select * from `cart`  where aid = $aid and pid = $pid";

  $result = mysqli_query($con, $query);
  $row = mysqli_fetch_assoc($result);

  if ($row) {
    echo "<script> alert('item already added to cart') </script>";

    header("Location: cart.php");
    exit();
  } else {

    $query = "INSERT INTO `cart` (aid, pid, cqty) values ($aid, $pid, $qty)";
    $result = mysqli_query($con, $query);
    header("Location: shop.php");
    exit();
  }

}


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MyTechPC | Product Details</title>
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="style.css" />
  

  <style>
    .heart {
      margin-left: 25px;
      display: inline-flex;
      justify-content: center;
      align-items: center;
    }

    .star i {
      font-size: 12px;
      color: rgb(243, 181, 25);
    }

    .tb {
      max-height: 400px;
      overflow-x: auto;
      overflow-y: auto;
    }

    .tb tr {
      height: 60px;
      margin: 10px;
    }

    .tb td {
      text-align: center;
      margin: 10px;
      padding-left: 40px;
      padding-right: 40px;
    }

    .rev {
      margin: 70px;
    }
  </style>

</head>

<body class="bg-[#faf9f6]">
  <section id="header">
    <a href="index.php"><img src="img/lg.png" class="logo" alt="" /></a>

    <div>
      <ul id="navbar">
        <li><a href="index.php">Home</a></li>
        <li><a class="active" href="shop.php">Shop</a></li>
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

  <?php
    include("include/connect.php");

    if (isset($_GET['pid'])) {
        $pid = $_GET['pid'];
        $query = "SELECT * FROM PRODUCTS WHERE pid = $pid";

        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($result);

        $pname = $row['pname'];
        $desc = $row['description'];
        $qty = $row['qtyavail'];
        $price = $row['price'];
        $cat = $row['category'];
        $img = $row['img'];
        $brand = $row['brand'];
    }
    ?>

    <!-- Product Details Section -->
    <div class="container mx-auto px-4 py-10 grid md:grid-cols-2 gap-10">
        <!-- Product Image -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <img 
                src="product_images/<?php echo $img; ?>" 
                alt="<?php echo $pname; ?>" 
                class="w-full h-96 object-cover rounded-lg"
                id="MainImg"
            >
        </div>

        <!-- Product Information -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-4"><?php echo $pname; ?></h1>
            
            <div class="flex items-center mb-4">
                <span class="text-xl font-semibold text-[#222] mr-4">₱<?php echo number_format($price, 2); ?></span>
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Product Details</h3>
                <p class="text-gray-600"><?php echo $desc; ?></p>
            </div>

            <div class="mb-6">
                <p class="text-sm text-gray-500">
                    <span class="font-semibold">Category:</span> <?php echo $cat; ?>
                </p>
                <p class="text-sm text-gray-500">
                    <span class="font-semibold">Brand:</span> <?php echo $brand; ?>
                </p>
                <p class="text-sm text-gray-500">
                    <span class="font-semibold">Availability:</span> 
                    <?php 
                    echo $qty > 0 
                        ? "<span class='text-green-600'>In Stock (" . $qty . " available)</span>" 
                        : "<span class='text-red-600'>Out of Stock</span>"; 
                    ?>
                </p>
            </div>

            <!-- Add to Cart Form -->
            <form method="post" class="space-y-4">
                <div class="flex items-center space-x-4">
                    <label for="qty" class="text-gray-700">Quantity:</label>
                    <input 
                        type="number" 
                        name="qty" 
                        value="1" 
                        min="1" 
                        max="<?php echo $qty; ?>"
                        class="w-20 border rounded px-2 py-1"
                    >
                </div>

                <button 
                    type="submit" 
                    name="submit" 
                    <?php echo $qty <= 0 ? 'disabled' : ''; ?>
                    class="
                        w-full 
                        bg-[#222] 
                        text-white 
                        py-2 
                        rounded 
                        hover:bg-gray-700 
                        transition 
                        <?php echo $qty <= 0 ? 'opacity-50 cursor-not-allowed' : ''; ?>
                    "
                >
                    <?php echo $qty <= 0 ? 'Out of Stock' : 'Add to Cart'; ?>
                </button>
            </form>
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

  <script>
    var MainImg = document.getElementById("MainImg");
    var smallimg = document.getElementsByClassName("small-img");

    smallimg[0].onclick = function() {
      MainImg.src = smallimg[0].src;
    };
    smallimg[1].onclick = function() {
      MainImg.src = smallimg[1].src;
    };
    smallimg[2].onclick = function() {
      MainImg.src = smallimg[2].src;
    };
    smallimg[3].onclick = function() {
      MainImg.src = smallimg[3].src;
    };
  </script>
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