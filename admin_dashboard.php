<?php
include_once 'header.php';
include_once 'includes/dbh.inc.php';
include_once 'includes/functions.inc.php';

// Check if the user is an admin
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
if (!isset($_SESSION["adminuid"])) {
    header("Location: login.php");
    exit();
}

// Get all users and their courses
$sql = "SELECT u.usersUid, c.name, c.x, c.y
        FROM users u
        LEFT JOIN courses c ON u.usersId = c.usersId";
$result = mysqli_query($conn, $sql);

// Prepare the data for rendering
$usersData = array();
while ($row = mysqli_fetch_assoc($result)) {
    $userUid = $row['usersUid'];
    $courseName = $row['name'];
    $courseX = $row['x'];
    $courseY = $row['y'];

    if (!isset($usersData[$userUid])) {
        $usersData[$userUid] = array(
            'courses' => array()
        );
    }

    if ($courseName !== null) {
        $usersData[$userUid]['courses'][] = array(
            'name' => $courseName,
            'x' => $courseX,
            'y' => $courseY
        );
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        #canvas {
            border: 1px solid black;
        }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>

    <?php foreach ($usersData as $userUid => $userData): ?>
        <h2><?php echo $userUid; ?></h2>
        <canvas id="canvas-<?php echo $userUid; ?>" width="400" height="300"></canvas>
        <script>
            (function() {
                const canvas = document.getElementById('canvas-<?php echo $userUid; ?>');
                const ctx = canvas.getContext('2d');

                <?php foreach ($userData['courses'] as $course): ?>
                    ctx.fillStyle = 'blue';
                    ctx.fillRect(<?php echo $course['x'] - 25; ?>, <?php echo $course['y'] - 25; ?>, 50, 50);
                    ctx.fillStyle = 'black';
                    ctx.fillText('<?php echo $course['name']; ?>', <?php echo $course['x'] - 25; ?>, <?php echo $course['y'] - 30; ?>);
                <?php endforeach; ?>
            })();
        </script>
    <?php endforeach; ?>
</body>
</html>