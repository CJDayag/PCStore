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
          echo "   <li><a class='active'  href='profile.php'>profile</a></li>
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
        <!-- End -->
    </div>
    <!-- End -->

    <!-- Sidenav -->
    <div class="sidenav">
        <div class="profile">
            <img src="https://imdezcode.files.wordpress.com/2020/02/imdezcode-logo.png" alt="" width="100" height="100">

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
            <h2>RECIEPT</h2>
            <div class="card">
                <div class="card-body">
                    <i class="fa fa-pen fa-xs edit"></i>
                    <table>
                        <tbody>
                        <div id="rightcontent" style="position:absolute; top:10%;">
			<div class="alert alert-info"><center><h2>Transactions	</h2></center></div>
			<br />

			
			<div class="alert alert-info">
			<form method="post" class="well"  style="background-color:#fff; overflow:hidden;">
	<div id="printablediv">
	<center> 
	<table class="table" style="width:50%;">
	<label style="font-size:25px;">MyTechPC</label>
	<label style="font-size:20px;">Official Receipt</label>
		<tr>
			<th><h5>Quantity</h5></td>
			<th><h5>Product Name</h5></td>
			<th><h5>Size</h5></td>
			<th><h5>Price</h5></td>
		</tr>
		
		<?php
		$oid = $_SESSION['oid'];
		$query = mysqli_query($conn, "SELECT * FROM orders WHERE transaction_id = '$oid'") or die (mysqli_error());
		$fetch = mysqli_fetch_array($query);
		
		$amnt = $fetch['amount'];
		echo "Date : ". $fetch['order_date']."";
		
		$query2 = mysqli_query($conn, "SELECT * FROM transaction_detail LEFT JOIN product ON product.product_id = transaction_detail.product_id WHERE transaction_detail.transaction_id = '$t_id'") or die (mysqli_error());
		while($row = mysqli_fetch_array($query2)){
		
		$pname = $row['product_name'];
		$psize = $row['product_size'];
		$pprice = $row['product_price'];
		$oqty = $row['order_qty'];
		
		echo "<tr>";
		echo "<td>".$oqty."</td>";
		echo "<td>".$pname."</td>";
		echo "<td>".$psize."</td>";
		echo "<td>".$pprice."</td>";
		echo "</tr>";
		}
		?>

	</table>
	<legend></legend>
	<h4>TOTAL: Php <?php echo $amnt; ?></h4>
	</center>
	</div>
	
	<div class='pull-right'>
	<div class="add"><a onclick="javascript:printDiv('printablediv')" name="print" style="cursor:pointer;" class="btn btn-info"><i class="icon-white icon-print"></i> Print Receipt</a></div>
	</div>
	</form>
			</div>
			</div>
                        </tbody>
                    </table>
                </div>
            </div>


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
                <a href="wishlist.php">My Wishlist</a>
            </div>

            <div class="copyright">
                <p>2023. MytechPC.</p>
            </div>
        </footer>