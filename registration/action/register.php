<?php
// Create Variables
$error = 0;
$msg = "";
//Include Database Connection
include ("../../includes/con.php");
// Check First field is Filled
error_reporting(E_ALL);
ini_set('display_errors', '1');

if (isset($_POST["fullname"])){
    $restaurantname = $_POST["restaurantname"];
    $name = $_POST["fullname"];
    $contact = $_POST["contact"];
    $usertype = $_POST["usertype"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    $query = "SELECT * FROM users WHERE email = '$email'";
    if ($result = mysqli_query($con, $query)){

        if (mysqli_num_rows($result) != 0){
            $error++;
            $msg .= "Email is used for another account";
        }

    }
    else {
        echo "Could not successfully run query ($query) from DataBase: " . mysqli_error();
        $error++;
        $msg .= "Database Error";
    }
}
else {
    $error++;
    $msg .= "Full name not filled";

}

if ($error == 0) {
    $loop=true;
    while($loop){
    //Register User
    include("../includes/con.php");
    $password = trim(password_hash($_POST["password"], PASSWORD_DEFAULT));
    $code = 0;
    if ($usertype == 2) {
        $code = rand(1000, 9999);
    }

    $sql2 = "SELECT * FROM users WHERE code = $code";
    $result = mysqli_query($con, $sql2);

    $row = mysqli_fetch_assoc($result);
    if ($row == 0)
        $loop = false;
}
        $sql = "INSERT INTO users (email, password, fullname , contact, usertype, restaurantname, code)
VALUES ('$email', '$password', '$name', $contact, $usertype, '$restaurantname', $code)";

        if ($con->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {

            echo "Error: " . $sql . "<br>" . $con->error;
            die();
        }

        $con->close();
        //Notify
        echo("<script LANGUAGE='JavaScript'>
    window.alert('Succesfully Updated');
    window.location.href='../index.php';
    </script>");

}
else {
    //Notify of Errors

    echo $msg;
    $msg = 'There is a problem with your registration: \n' . $msg;
    echo "<script type=text/javascript>var message = '" . $msg . "' ;" . "alert(message);window.location.href='../index.php'</script>";


}