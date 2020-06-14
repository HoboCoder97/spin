<?php

include("../../global/con.php");
require("../../global/cooldown.php");

$code = (string)$_GET['q'];
$sql = "SELECT * FROM claims WHERE email = '$code'";
$result = mysqli_query($con, $sql) or die(mysqli_errno($con));
if (mysqli_num_rows($result)==1) {
    $row = mysqli_fetch_assoc($result);
    $dbdate = $row['datetime'];
    $timestamp = strtotime($dbdate);
     $date = date("Y-m-d H:i:s");



    if ($date < $dbdate) {
        echo "Your Email is on Cooldown!";
    } else {
        echo "Your Email is Not On Cooldown, Press Claim to Claim Your eWallet!";
    }

}
else {
    echo "Email not in database, Click Sign Up below To get your eWallet!";
}