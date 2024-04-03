<?php
include_once 'includes/dbh.inc.php';

session_start();

echo "debug 1";

if (isset($_SESSION["userid"])) {
    $courseId = $_POST["courseId"];

    echo $courseId;
    echo "debug 2<br>";

    $sql = "DELETE FROM courses WHERE courseId = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        error_log('internal error 1');
        http_response_code(500);
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $courseId);
    if (!mysqli_stmt_execute($stmt)) {
        error_log('internal error 2');
        http_response_code(500);
        exit();
    }

    echo "debug 3";
    mysqli_stmt_close($stmt);
    http_response_code(200);
} else {
    echo "debug error 400";
    http_response_code(400); // Bad request
    exit();
}
?>
