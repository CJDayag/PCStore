<?php
session_start();

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
    <script src="https://cdn.tailwindcss.com"></script>
    

    <style>
    .search-container {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        background: #e3e6f3;
        padding: 10px;
    }

    #category-filter {
        padding: 6px;
        margin-right: 10px;
        border: none;
        border-radius: 4px;
    }

    #search {
        padding: 6px;
        margin-right: 10px;
        border: none;
        border-radius: 4px;
    }

    #search-btn {
        outline: none;
        border: none;
        padding: 10px 30px;
        background-color: navy;
        color: white;
        border-radius: 1rem;
        cursor: pointer;
    }
        /* Container for the cards */
    .pro-container {
        display: flex;
        flex-wrap: wrap; /* Ensures wrapping of cards */
        gap: 20px; /* Adds spacing between cards */
        justify-content: space-around; /* Centers cards evenly */
    }

    /* Card Styles */
    .w-full.max-w-sm,
    .pro {
        flex: 1 1 calc(22% - 20px); /* Responsive sizing: 4 cards per row */
        box-sizing: border-box;
        max-width: 300px; /* Ensure consistent width */
        height: 420px; /* Ensure consistent height */
        display: flex;
        flex-direction: column;
        justify-content: space-between; /* Align content properly */
        margin: 10px; /* Additional margin */
        padding: 15px; /* Padding for content */
        border-radius: 10px;
        background-color: #fff; /* White background */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        overflow: hidden; /* Prevent overflow */
    }

    .w-full.max-w-sm img,
    .pro img {
        width: 100%; /* Ensures images take up full width */
        height: 200px; /* Uniform image height */
        object-fit: cover; /* Crop images proportionally */
        border-radius: 8px; /* Rounded corners for images */
    }

    .des {
        text-align: center;
    }

    .text-3xl {
        font-size: 0.5rem; /* Adjust font size for price */
        font-weight: bold;
    }

    
    </style>

 
</head>

<body>
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

    <section id="page-header">
        <h2>Premium Gaming</h2>

    </section>

    <div class="search-container">
        <form id="search-form" method="post">
            <label for="search">Search:</label>
            <input type="text" id="search" name="search">
            <label for="category-filter">Category:</label>
            <select id="category-filter" name="cat">
                <option value="all">All</option>
                <option value="set">Pre-Built</option>
                <option value="keyboard">Keyboard</option>
                <option value="mouse">Mouse</option>
                <option value="headset">Headset</option>
                <option value="motherboard">Motherboard</option>
                <option value="chassis">Chassis</option>
                <option value="Powersupply">Power Supply</option>
                <option value="coolingfan">Cooling Fan</option>
                <option value="cpu">CPU</option>
                <option value="gpu">GPU</option>
                <option value="ram">RAM</option>
            </select>
            <button type="submit" id="search-btn" name="search1">Search</button>
        </form>
    </div>

    <?php
include("include/connect.php");

// Function to display product cards
function displayProductCard($pid, $pname, $brand, $price, $img)
{
    $shortName = (strlen($pname) > 35) ? substr($pname, 0, 35) . '...' : $pname;

    echo "<div class='w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700'>";
    echo "<a href='#'><img class='p-8 rounded-t-lg' src='product_images/$img' alt='$shortName' onclick=\"topage('$pid')\"/></a>";
    echo "<div class='px-5 pb-5'>";
    echo "<a href='#'><h5 class='text-xl font-semibold tracking-tight text-gray-900 dark:text-white'>$brand - $shortName</h5></a>";
    echo "<div class='flex items-center mt-2.5 mb-5'>";
    echo "<div class='flex items-center space-x-1 rtl:space-x-reverse'>";

    echo "</div>";
    //echo "<span class='bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800 ms-3'></span>";
    echo "</div>";
    echo "<div class='flex items-center justify-between mt-5'>";
    echo "<span class='text-3xl font-bold text-gray-900 dark:text-white'>₱$price</span>";
    echo "<a href='#' class='text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800' onclick=\"topage('$pid')\">View</a>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
}

// Search Logic
if (isset($_POST['search1'])) {
    $search = $_POST['search'];
    $category = $_POST['cat'];
    $query = "SELECT pid, pname, brand, price, img FROM `products`";

    if (!empty($search)) {
        $query .= " WHERE (pname LIKE '%$search%' OR brand LIKE '%$search%' OR description LIKE '%$search%')";
        if ($category != "all") {
            $query .= " AND category = '$category'";
        }
    } elseif ($category != "all") {
        $query .= " WHERE category = '$category'";
    }

    $result = mysqli_query($con, $query);
} else {
    // Default display for random products
    $result = mysqli_query($con, "SELECT pid, pname, brand, price, img FROM `products` WHERE qtyavail > 0 ORDER BY RAND()");
}

if ($result) {
    echo "<section id='product1' class='section-p1'>";
    echo "<div class='pro-container'>";
    while ($row = mysqli_fetch_assoc($result)) {
        $pid = $row['pid'];
        $pname = $row['pname'];
        $brand = $row['brand'];
        $price = $row['price'];
        $img = $row['img'];

        // Fetch average rating
        $query2 = "SELECT AVG(rating) AS average_rating FROM reviews WHERE pid = $pid";
        $result2 = mysqli_query($con, $query2);
        $stars = 0;

        if ($row2 = mysqli_fetch_assoc($result2)) {
            $stars = round($row2['average_rating'], 0);
        }

        // Display Product Card
        displayProductCard($pid, $pname, $brand, $price, $img, $stars);
    }
    echo "</div></section>";
}
?>



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
    function topage(pid) {
        window.location.href = `sproduct.php?pid=${pid}`;
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