<?php
include_once 'includes/dbh.inc.php';

session_start();

if (isset($_SESSION["userid"])) {
    $courseId = $_POST["courseId"];
    $x = $_POST["x"];
    $y = $_POST["y"];

    $sql = "UPDATE courses SET x = ?, y = ? WHERE courseId = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(500);
        exit();
    }

    mysqli_stmt_bind_param($stmt, "iii", $x, $y, $courseId);
    if (!mysqli_stmt_execute($stmt)) {
        http_response_code(500);
        exit();
    }

    mysqli_stmt_close($stmt);
    http_response_code(200);
} else {
    http_response_code(401);
}