<?php
include_once 'header.php';
include_once 'includes/dbh.inc.php';
include_once 'includes/functions.inc.php';
?>

<section class="profile-page">
    <?php
    if (isset($_SESSION["useruid"])) {
        $userId = $_SESSION["userid"];
        $userUid = $_SESSION["useruid"];
        echo "<h2>Welcome, " . $userUid . "</h2>";
    ?>
        <!-- Add form for updating user information -->
        <form action="includes/update_user.inc.php" method="post">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?php echo getUserInfo($conn, 'usersName', $userId); ?>">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo getUserInfo($conn, 'usersEmail', $userId); ?>">
            <label for="uid">Username:</label>
            <input type="text" name = "uid" value="<?php echo getUserInfo($conn, 'usersUid', $userId); ?>">
        
            <button type="submit" name="update_user">Update</button>
        </form>


        <!-- Add button for deleting user account -->
        <br>
        <br>
        <br>
        <br>
        <button id="deleteAccountBtn">Delete Account</button>
        <script>
            document.getElementById('deleteAccountBtn').addEventListener('click', function() {
                if (confirm('Are you sure you want to delete your account?')) {
                    window.location.href = 'includes/delete_user.inc.php?id=<?php echo $userId; ?>';
                }
            });
        </script>

    <?php
    } else {
        echo "<p>You are not logged in.</p>";
    }
    ?>
</section>