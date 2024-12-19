<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MyTechPC | Shop</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="style.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    

    <style>
    .search-container {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        background: #fcfcf7;
        border-bottom: 1px solid #222; /* Subtle border */
        padding: 10px;
        flex-wrap: wrap; /* Ensure wrapping on smaller screens */
    }

    #category-filter, #search {
        padding: 6px;
        margin-right: 10px;
        border: none;
        border-radius: 4px;
        flex: 1 1 200px; /* Responsive sizing */
    }

    #search-btn {
        outline: none;
        border: none;
        padding: 10px 30px;
        background-color: #222;
        color: white;
        border-radius: 1rem;
        cursor: pointer;
        flex: 1 1 100px; /* Responsive sizing */
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

    @media (max-width: 799px) {
        .pro {
            flex: 1 1 calc(45% - 20px); /* Responsive sizing: 2 cards per row */
        }

        .search-container {
            justify-content: center; /* Center search container on smaller screens */
        }

        #category-filter, #search, #search-btn {
            flex: 1 1 100%; /* Full width on smaller screens */
            margin-bottom: 10px; /* Add spacing between elements */
        }
    }

    @media (max-width: 499px) {
        .pro {
            flex: 1 1 calc(100% - 20px); /* Responsive sizing: 1 card per row */
        }
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

    <div class="container mx-auto px-4 py-6">
        <?php
        include("include/connect.php");

        // Pagination setup
        $resultsPerPage = 16;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $resultsPerPage;

        // Search and Filter Logic
        $query = "SELECT * FROM `products` WHERE qtyavail > 0";
        $countQuery = "SELECT COUNT(*) as total FROM `products` WHERE qtyavail > 0";

        if (isset($_POST['search1'])) {
            $search = $_POST['search'];
            $category = $_POST['cat'];

            if (!empty($search)) {
                $query .= " AND (pname LIKE '%$search%' OR brand LIKE '%$search%' OR description LIKE '%$search%')";
                $countQuery .= " AND (pname LIKE '%$search%' OR brand LIKE '%$search%' OR description LIKE '%$search%')";
            }

            if ($category != "all") {
                $query .= " AND category = '$category'";
                $countQuery .= " AND category = '$category'";
            }
        }

        // Add pagination to the query
        $query .= " LIMIT $offset, $resultsPerPage";

        // Get total number of results
        $totalResult = mysqli_query($con, $countQuery);
        $totalRow = mysqli_fetch_assoc($totalResult);
        $totalProducts = $totalRow['total'];
        $totalPages = ceil($totalProducts / $resultsPerPage);

        // Execute main query
        $result = mysqli_query($con, $query);
        ?>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                $pid = $row['pid'];
                $pname = $row['pname'];
                $brand = $row['brand'];
                $price = $row['price'];
                $img = $row['img'];
            ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden transform transition duration-300 hover:scale-105 animate-product">
                    <img 
                        src="product_images/<?php echo $img; ?>" 
                        alt="<?php echo $pname; ?>" 
                        class="w-full h-48 object-cover"
                        onclick="topage('<?php echo $pid; ?>')"
                    >
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800 truncate"><?php echo $brand . ' - ' . $pname; ?></h3>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-[#222] font-bold">₱<?php echo number_format($price, 2); ?></span>
                            <button 
                                onclick="topage('<?php echo $pid; ?>')"  class="px-4 py-2 bg-[#222] text-white rounded-lg hover:bg-gray-700 transition duration-300"
                            >
                                View
                            </button>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <!-- Pagination Section -->
        <div class="flex justify-center mt-6">
            <nav aria-label="Page navigation">
                <ul class="inline-flex -space-x-px">
                    <?php if ($page > 1): ?>
                        <li>
                            <a href="?page=<?php echo $page - 1; ?>" class="pagination-btn px-3 py-2 border border-gray-300 rounded-l-md bg-white text-gray-500 hover:bg-gray-100">
                                Previous
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li>
                            <a href="?page=<?php echo $i; ?>" class="pagination-btn px-3 py-2 border border-gray-300 bg-white text-gray-500 hover:bg-gray-100 <?php echo $i === $page ? 'bg-blue-600 text-white' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <li>
                            <a href="?page=<?php echo $page + 1; ?>" class="pagination-btn px-3 py-2 border border-gray-300 rounded-r-md bg-white text-gray-500 hover:bg-gray-100">
                                Next
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
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