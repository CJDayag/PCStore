<?php
session_start();


if (isset($_GET['lo'])) {
  $_SESSION['aid'] = -1;
  header("Location: index.php");
  exit();

}

if (isset($_POST['submit'])) {
  include("include/connect.php");
  $aid = $_SESSION['aid'];

  $firstname = $_POST['a1'];
  $lastname = $_POST['a2'];
  $email = $_POST['a3'];
  $phone = $_POST['a4'];
  $dob = $_POST['a5'];

  $query = "select * from accounts where (phone='$phone' or email='$email') and aid != $aid ";

  $result = mysqli_query($con, $query);
  $row = mysqli_fetch_assoc($result);
  if (!empty($row['aid'])) {
    echo "<script> alert('Credentials already exists'); setTimeout(function(){ window.location.href = 'profile.php'; }, 10); </script>";
    exit();
  }
  if (strtotime($dob) > time()) {
    echo "<script> alert('invalid date'); setTimeout(function(){ window.location.href = 'profile.php'; }, 10); </script>";
    exit();
  }
  if (preg_match('/\D/', $phone) || strlen($phone) != 11) {
    echo "<script> alert('invalid number'); setTimeout(function(){ window.location.href = 'profile.php'; }, 10); </script>";
    exit();
  }

  $query = "UPDATE ACCOUNTS SET afname = '$firstname', alname='$lastname', email='$email', phone='$phone', dob='$dob' WHERE aid = $aid";

  $result = mysqli_query($con, $query);
  header("Location: profile.php");
  exit();
}


if (isset($_POST['abc'])) {
  include("include/connect.php");

  $oid = $_GET['odd'];

  $query = "select * from `order-details` where oid = $oid";
  $result = mysqli_query($con, $query);

  while ($row = mysqli_fetch_assoc($result)) {
    include("include/connect.php");

    $pid = $row['pid'];


    $text = $_POST["$pid-te"];
    $star = $_POST["$pid-rating"];
    $query;
    if (empty($text))
      $query = "insert into `reviews` (oid, pid, rtext, rating) values ($oid, $pid, NULL, $star)";
    else
      $query = "insert into `reviews` (oid, pid, rtext, rating) values ($oid, $pid, '$text', $star)";


    $result2 = mysqli_query($con, $query);
  }

  header("Location: profile.php");
  exit();
}

if (isset($_GET['c'])) {
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
    <title>MyTechPC</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />

    <link rel="stylesheet" href="style.css" />

    <style>
    .tb {
        max-height: 700px;
        overflow-x: auto;
        overflow-y: auto;
    }



    .tb tr {
        height: 60px;
        margin: 20px;
    }

    .tb td {
        text-align: center;
        margin: 10px;
        padding-left: 40px;
        padding-right: 40px;
    }

    .insert-btn {
        display: inline-block;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 5px;
        border: none;
        color: #fff;
        background-color: #088178;
        cursor: pointer;
        margin-right: 20px;
        margin-top: 20px;
        margin-bottom: 20px;
        margin-left: 20px;
    }

    input[type="text"] {
        display: block;
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        font-size: 16px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    input[type="date"] {
        display: block;
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        font-size: 16px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .logup {
        width: auto;
    }
    </style>

    <style>
    .rating {
        display: inline-block;
        font-size: 0;
        line-height: 0;
        border: none;
        border-style: none;

        padding-left: 80px;
    }

    .rating label {
        display: inline-block;
        font-size: 24px;
        color: #ddd;
        cursor: pointer;
    }

    .rating label:before {
        content: '\2606';
    }

    .rating label.checked:before,
    .rating label:hover:before {
        content: '\2605';
        color: #ffc107;
    }

    input[type="radio"] {
        display: none;
    }

    /* .asd {} */
    </style>

    <style>
    </style>
    <script>
    window.addEventListener("unload", function() {
        // Call a PHP script to log out the user
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "logout.php", false);
        xhr.send();
    });
    </script>

</head>

<body>
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
            <li><a  href='signup.php'>SignUp</a></li>
            ";
        } else {
          echo "   <li><a class='active'  href='profile.php'>My Profile</a></li>
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

    <div class="navbar-top">
        <div class="title">
            <h1>Profile</h1>
        </div>
        <!-- End -->
    </div>
    <!-- End -->

    <!-- Sidenav -->
    <div class="sidenav">
        <div class="profile">

            <?php

      include("include/connect.php");

      $aid = $_SESSION['aid'];
      $query = "SELECT * FROM ACCOUNTS WHERE aid = $aid";

      $result = mysqli_query($con, $query);

      $row = mysqli_fetch_assoc($result);

      $afname = $row['afname'];
      $alname = $row['alname'];
      $phone = $row['phone'];
      $email = $row['email'];
      $dob = $row['dob'];
      $user = $row['username'];
      $gender = $row['gender'];
      $name = $afname . " " . $alname;

      echo "
      <div class='name'>
        $name
      </div>
      <div class='job'>
        Customer
      </div>
    </div>
    "
        ?>

            <div class="sidenav-url">
                <div class="url">
                    <a href='profile.php?lo=1' class="btn logup">Log out</a>
                    <hr allign="center">
                </div>
                <div class="url">
                    <a href='profile.php?upd=1' class="btn logup">Update</a>
                    <hr allign="center">
                </div>
                <?php
        if (isset($_GET['odd'])) {
          echo "
                    <div class='url'>
                    <a href='profile.php' class='btn logup'>Back</a>
                    <hr allign='center'>
                    </div>
                    ";
        }
        ?>
            </div>
        </div>
        <!-- End -->

        <!-- Main -->
        <div class="main">
            <h2>IDENTITY</h2>
            <div class="card">
                <div class="card-body">
                    <i class="fa fa-pen fa-xs edit"></i>
                    <table>
                        <tbody>
                            <?php


              if (isset($_GET['upd'])) {
                include("include/connect.php");

                $aid = $_SESSION['aid'];

                $query = "SELECT * FROM ACCOUNTS WHERE aid = $aid";

                $result = mysqli_query($con, $query);

                $row = mysqli_fetch_assoc($result);

                $afname = $row['afname'];
                $alname = $row['alname'];
                $phone = $row['phone'];
                $email = $row['email'];
                $dob = $row['dob'];
                $user = $row['username'];
                $gender = $row['gender'];

                echo "
              <form class='form1' method='post'>
              <tr>
                <td>First Name</td>
                <td>:</td>
                <td><input name='a1' type='text' value='$afname'></td>
              </tr>
              <tr>
                <td>Last Name</td>
                <td>:</td>
                <td><input name='a2' type='text' value='$alname'></td>
              </tr>
              <tr>
                <td>Email</td>
                <td>:</td>
                <td><input name='a3' type='text' value='$email'></td>
              </tr>
              <tr>
              <td>Phone</td>
              <td>:</td>
              <td><input name='a4' type='text' value='$phone'></td>
              </tr>
              <tr>
              <td>Date OF Birth</td>
              <td>:</td>
              <td><input name='a5' type='date' value='$dob'></td>
              </tr>

              <tr>
              <td><button name='submit' type='submit' class='btn' style='width: 50%;'>Submit</button></td>

              </tr>
              </form>
              ";



              } else {
                include("include/connect.php");

                $aid = $_SESSION['aid'];
                $query = "SELECT * FROM ACCOUNTS WHERE aid = $aid";

                $result = mysqli_query($con, $query);

                $row = mysqli_fetch_assoc($result);

                $afname = $row['afname'];
                $alname = $row['alname'];
                $phone = $row['phone'];
                $email = $row['email'];
                $dob = $row['dob'];
                $user = $row['username'];
                $gender = $row['gender'];
                $name = $afname . " " . $alname;

                echo "
              <tr>
                <td>First Name</td>
                <td>:</td>
                <td>$afname</td>
              </tr>
              <tr>
                <td>Last Name</td>
                <td>:</td>
                <td>$alname</td>
              </tr>
              <tr>
                <td>Email</td>
                <td>:</td>
                <td>$email</td>
              </tr>
              <tr>
              <td>Phone</td>
              <td>:</td>
              <td>$phone</td>
              </tr>
              <tr>
              <td>Date OF Birth</td>
              <td>:</td>
              <td>$dob</td>
              </tr>
              <tr>
              <td>Username</td>
              <td>:</td>
              <td>$user</td>
              </tr>
              <tr>
              <td>Gender</td>
              <td>:</td>
              <td>$gender</td>
              </tr>
              ";
              }
              ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php

      if (isset($_GET['odd'])) {
        include("include/connect.php");

        $oid = $_GET['odd'];

        $query = "select * from `order-details` where oid = $oid";
        $result = mysqli_query($con, $query);

        echo "<h2>Additional Info</h2>
                  <div class='card'>
                  <div class='card-body'>
                      <i class='fa fa-pen fa-xs edit'></i>
                      <div class='tb' style: 'height: 700px; max-height: 700px;'>
                      <form method='post'> <table style='display:table; max-height: 700px;' class='tb'><thead>
                <tr>
                  <th>Order ID</th>
                  <th>Date Ordered</th>
                  <th>Total</th>
                  <th>Address</th>
                  <th>Order Status</th>
                  <th>Reciept</th>
                </tr>
                </thead><tbody>";

        while ($row = mysqli_fetch_assoc($result)) {
          include("include/connect.php");

          $aid = $_SESSION['aid'];

          $query = "SELECT * FROM orders join accounts on orders.oid = accounts.aid where accounts.aid and orders.oid = $aid";

          $result2 = mysqli_query($con, $query);

          $row2 = mysqli_fetch_assoc($result2);

          $oid = $row2['oid'];
          $dateod = $row2['dateod'];
          $total = $row2['total'];
          $add = $row2['address'];
          $datedel = $row2['datedel'];
          
          

          echo " <tr>
                    <td>$oid</td>
                    <td>$dateod</td>
                    <td>$total</td>
                    <td>$add</td>
                    <td>$datedel</td>
                    <td><div class='sss'><button type='generate' name='' class = 'btn' style='width: 50px;'> Generate </button></div></td>
                  </tr>";
        }
        echo "</tbody></table>
                </form></tbody>
                  </table>
              </div>
          </div>
         
     ";
      } else {
        echo "<h2>ORDER INFO</h2>
                <div class='card'>
                <div class='card-body'>
                    <i class='fa fa-pen fa-xs edit'></i>
                    <div class='tb'>
                        <table style='display:table;' class='tb'>
                            <thead>
                                <tr>
                                    <th>Order ID </th>
                                    <th>Account ID</th>
                                    <th>Date Ordered </th>
                                    <th>Order Status</th>
                                    <th>Total Price </th>
                                    <th>Address </th>
                                    <th>Reciept </th>
                                </tr>
                            </thead>
                            <tbody>";

        include("include/connect.php");

        $aid = $_SESSION['aid'];

        $query = "SELECT * FROM orders join accounts on orders.aid = accounts.aid where orders.aid = $aid";


        $result = mysqli_query($con, $query);

        while ($row = mysqli_fetch_assoc($result)) {
          $oid = $row['oid'];
          $aid = $row['aid'];
          $dateod = $row['dateod'];
          $datedel = $row['datedel'];
          $add = $row['address'];
          $pri = $row['total'];
          $tot = $pri + 250;
          if (empty($datedel))
            $datedel = "Order pending";
          echo "


                <tr>
                <td>$oid</td>
                <td>$aid</td>
                    <td>$dateod</td>
                    <td>$datedel</td>
                    <td>$tot</td>
                <td style='max-width: 400px; max-height: 100px; overflow-x: auto; overflow-y: auto;'>$add</td>
                ";
          echo "<td><a href='pdf.php?oid=$oid'><button class='insert-btn' target='_blank'>Generate</button></a></td>";
          echo "</tr>";
        }

        echo "</tbody>
                  </table>
              </div>
          </div>
      </div>";
      }
      ?>



        </div>
        <!-- End -->

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

        <script>
        // Get all the rating fields on the page
        function bruh(param) {
            console.log(param);
            const ratingFields = document.querySelectorAll('#a-' + param + '-rating');

            // Loop through each rating field
            ratingFields.forEach(ratingField => {
                // Get all the stars in this rating field
                const stars = ratingField.querySelectorAll('input[type="radio"]');

                // Loop through each star
                stars.forEach(star => {
                    // Listen for click events on this star
                    star.addEventListener('click', function() {
                        // Set the clicked star and all the stars before it to be checked and filled


                        for (let i = 0; i < star.value; i++) {
                            console.log('hello');
                            stars[i].checked = true;
                            stars[i].nextElementSibling.classList.add('checked');
                        }

                        // Set all the stars after the clicked star to be unchecked and empty
                        for (let i = star.value; i < stars.length; i++) {
                            stars[i].checked = false;
                            console.log('hello');

                            stars[i].nextElementSibling.classList.remove('checked');
                        }
                    });
                });
            });
        }
        </script>



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