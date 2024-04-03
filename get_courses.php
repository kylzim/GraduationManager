<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
// session_start();
include_once 'includes/dbh.inc.php';

function getCourses($conn, $userId) {
    $sql = "SELECT courseId, x, y, name FROM courses WHERE usersId = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return [];
    }

    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $courses = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $courses[] = [
            'courseId' => $row['courseId'],
            'x' => $row['x'],
            'y' => $row['y'],
            'name' => $row['name']
        ];
    }

    mysqli_stmt_close($stmt);
    return $courses;
}


if (isset($_SESSION["userid"])) {
    $userId = $_SESSION["userid"];
    $courses = getCourses($conn, $userId);
    echo json_encode($courses);
} else {
    http_response_code(401); // Unauthorized
}
?>