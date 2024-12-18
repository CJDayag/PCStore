<?php
include("include/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oid = $_POST["oid"];
    $datedel = $_POST["datedel"];

    // Update the order status in the database
    $updateQuery = "UPDATE orders SET datedel = '$datedel' WHERE oid = $oid";
    mysqli_query($con, $updateQuery);

    // Redirect back to the inventory page
    header("Location: inventory.php");
    exit();
}

mysqli_close($con);
?>
