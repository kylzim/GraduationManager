<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
if (isset($_POST["update_user"])) {
    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    $userId = $_SESSION["userid"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $uid = $_POST["uid"];
    // $pwd = $_POST["pwd"];

    error_log("my userId: $userId");

    updateUser($conn, $userId, $name, $email, $uid);
} 
elseif (isset($_POST["update_admin"])) {
    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    $userId = $_SESSION["adminid"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $uid = $_POST["uid"];
    // $pwd = $_POST["pwd"];

    

    updateAdminUser($conn, $userId, $name, $email, $uid);
} else {
    header("location: ../profile.php");
    exit();
}