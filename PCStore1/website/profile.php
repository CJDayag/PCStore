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

// Get user info early
$afname = '';
$alname = '';
$email = '';
$phone = '';
$dob = '';
$user = '';
$gender = '';

if($_SESSION['aid'] > 0) {
  include("include/connect.php");
  $aid = $_SESSION['aid'];
  $query = "SELECT * FROM ACCOUNTS WHERE aid = $aid";
  $result = mysqli_query($con, $query);
  $row = mysqli_fetch_assoc($result);
  $afname = $row['afname'];
  $alname = $row['alname'];
  $email = $row['email'];
  $phone = $row['phone'];
  $dob = $row['dob'];
  $user = $row['username'];
  $gender = $row['gender'];
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
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css" />
</head>

<body class="bg-[#f8f7f4]">

    <section id="header">
        <a href="index.php"><img src="img/lg.png" class="logo" alt="" /></a>

        <div>
            <ul id="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="contact.php">Contact</a></li>

                <?php
                if ($_SESSION['aid'] < 0) {
                  echo "<li><a href='login.php'>login</a></li>
                        <li><a href='signup.php'>SignUp</a></li>";
                } else {
                  echo "<li><a class='active' href='profile.php'>My Profile</a></li>";
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

    <?php if($_SESSION['aid'] > 0): ?>
    <style>
        @keyframes slideIn {
            0% {
                transform: translateX(100%);
                opacity: 0;
            }
            100% {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes fadeOut {
            0% {
                transform: translateX(0);
                opacity: 1;
            }
            100% {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        #welcome-banner {
            animation: slideIn 0.5s ease-out;
        }

        #welcome-banner.fade-out {
            animation: fadeOut 0.5s ease-in forwards;
        }
    </style>
    <div id="welcome-banner" class="fixed top-0 right-4 z-50 bg-white shadow-lg rounded-lg p-4 max-w-xl mt-20">
        <div class="flex items-center justify-between">
            <p class="text-gray-800 font-semibold">Welcome back, <?php echo $afname; ?>!</p>
            <button onclick="closeWelcomeBanner()" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
    <?php endif; ?>

    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="text-center mb-6">
                        <h2 class="text-xl font-bold text-gray-800"><?php echo $afname . " " . $alname; ?></h2>
                        <p class="text-gray-600">Customer</p>
                    </div>
                    <div class="space-y-4">
                        <a href="profile.php?lo=1" class="block w-full text-center bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded transition duration-200">
                            Log out
                        </a>
                        <a href="profile.php?upd=1" class="block w-full text-center bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded transition duration-200">
                            Update Profile
                        </a>
                        <?php if (isset($_GET['odd'])): ?>
                        <a href="profile.php" class="block w-full text-center bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded transition duration-200">
                            Back
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="md:col-span-3">
                <!-- Profile Information -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h2 class="text-2xl font-bold mb-6">Profile Information</h2>
                    <?php if (isset($_GET['upd'])): ?>
                    <form method="post" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 mb-2">First Name</label>
                                <input type="text" name="a1" value="<?php echo $afname; ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-2">Last Name</label>
                                <input type="text" name="a2" value="<?php echo $alname; ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-2">Email</label>
                                <input type="email" name="a3" value="<?php echo $email; ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-2">Phone</label>
                                <input type="text" name="a4" value="<?php echo $phone; ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-2">Date of Birth</label>
                                <input type="date" name="a5" value="<?php echo $dob; ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                            </div>
                        </div>
                        <button type="submit" name="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg transition duration-200">
                            Save Changes
                        </button>
                    </form>
                    <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-gray-600">First Name</p>
                            <p class="font-semibold"><?php echo $afname; ?></p>
                        </div>
                        <div>
                            <p class="text-gray-600">Last Name</p>
                            <p class="font-semibold"><?php echo $alname; ?></p>
                        </div>
                        <div>
                            <p class="text-gray-600">Email</p>
                            <p class="font-semibold"><?php echo $email; ?></p>
                        </div>
                        <div>
                            <p class="text-gray-600">Phone</p>
                            <p class="font-semibold"><?php echo $phone; ?></p>
                        </div>
                        <div>
                            <p class="text-gray-600">Date of Birth</p>
                            <p class="font-semibold"><?php echo $dob; ?></p>
                        </div>
                        <div>
                            <p class="text-gray-600">Username</p>
                            <p class="font-semibold"><?php echo $user; ?></p>
                        </div>
                        <div>
                            <p class="text-gray-600">Gender</p>
                            <p class="font-semibold"><?php echo $gender; ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Order History -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold mb-6">Order History</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Ordered</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php
                                include("include/connect.php");
                                $aid = $_SESSION['aid'];
                                $query = "SELECT * FROM orders WHERE aid = $aid ORDER BY dateod DESC";
                                $result = mysqli_query($con, $query);
                                
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $oid = $row['oid'];
                                    $dateod = $row['dateod'];
                                    $status = empty($row['datedel']) ? 'Pending' : 'Delivered';
                                    $total = $row['total'] + 250; // Adding delivery fee
                                    
                                    echo "<tr class='hover:bg-gray-50'>
                                        <td class='px-6 py-4 whitespace-nowrap'>#$oid</td>
                                        <td class='px-6 py-4 whitespace-nowrap'>$dateod</td>
                                        <td class='px-6 py-4 whitespace-nowrap'>
                                            <span class='px-2 inline-flex text-xs leading-5 font-semibold rounded-full " . 
                                            ($status == 'Delivered' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800') . 
                                            "'>$status</span>
                                        </td>
                                        <td class='px-6 py-4 whitespace-nowrap'>₱$total</td>
                                        <td class='px-6 py-4 whitespace-nowrap'>
                                            <a href='pdf.php?oid=$oid' target='_blank' 
                                               class='text-blue-600 hover:text-blue-900'>
                                                View Receipt
                                            </a>
                                        </td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
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
            <p>Copyright © 2023 My Tech Pc</p>
        </div>
    </footer>

    <script src="script.js"></script>
    <script>
    function closeWelcomeBanner() {
        const banner = document.getElementById('welcome-banner');
        banner.style.opacity = '0';
        setTimeout(() => {
            banner.style.display = 'none';
        }, 300);
    }

    setTimeout(closeWelcomeBanner, 5000);
    </script>
</body>
</html>
