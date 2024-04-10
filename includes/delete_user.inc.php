<?php
if (isset($_GET["id"])) {
    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    $userId = $_GET["id"];
    deleteUser($conn, $userId);
} else {
    header("location: ../profile.php");
    exit();
}