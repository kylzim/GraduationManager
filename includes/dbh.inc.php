<?php

$serverName = "localhost";
$dBUsername = "root";
$dBPassword = "";
$dBName = "GradDatabase1";
$port = 3307;


$conn = mysqli_connect($serverName, $dBUsername, $dBPassword, $dBName, $port);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}