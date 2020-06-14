<?php

// Create connection
$servername = "localhost";

$password = "";
$dbname = "spin";
$con = new mysqli($servername, "root", $password, $dbname);
// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
else {

}
