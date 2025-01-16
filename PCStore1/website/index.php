<?php
session_start();

if (empty($_SESSION['aid']))
    $_SESSION['aid'] = -1;
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const stickyElement = document.querySelector('.sticky-banner');
            
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    stickyElement.classList.add('shadow-md');
                } else {
                    stickyElement.classList.remove('shadow-md');
                }
            });
        });
</script>

</head>

<body class="bg-[#f8f7f4]">
    <section id="header">
        <a href="index.php"><img src="img/lg.png" class="logo" /></a>

        <div>
            <ul id="navbar">
                <li><a class="active" href="index.php">Home</a></li>
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
    <div class="sticky top-0 z-50 bg-gray-100 dark:bg-gray-800 px-4 py-3 text-gray-900 dark:text-gray-100 shadow-sm">
            <p class="flex justify-center text-sm">
                <a href="shop.php" class="group flex items-center">
                    <span class="mr-1 text-base leading-none">✨</span>
                    New and Affordable Products!
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" 
                        class="ml-2 inline-flex opacity-60 transition-transform group-hover:translate-x-1" 
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            </p>
        </div>


    <section id="hero">
        <h2>MAKE THE WORLD</h2>
        <h1>A BETTER PLACE</h1>
        <h1>WITH COMPUTERS</h1>
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
window.addEventListener("onunload", function() {
  // Call a PHP script to log out the user
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "logout.php", false);
  xhr.send();
});
</script>