<?php
include_once 'includes/dbh.inc.php';
session_start();

if (isset($_SESSION["userid"])) {
    $userId = $_SESSION["userid"];
    $name = $_POST["name"];
    $x = $_POST["x"];
    $y = $_POST["y"];

    $sql = "INSERT INTO courses (name, x, y, usersId) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        http_response_code(500); // Internal Server Error
        exit();
    }

    mysqli_stmt_bind_param($stmt, "siii", $name, $x, $y, $userId);
    if (!mysqli_stmt_execute($stmt)) {
        http_response_code(500); // Internal Server Error
        exit();
    }

    // Get the courseId of the newly inserted row
    $courseId = mysqli_stmt_insert_id($stmt);

    mysqli_stmt_close($stmt);

    // Send the courseId as a response
    echo $courseId;
    http_response_code(200); // OK
} else {
    http_response_code(401); // Unauthorized
}
?>