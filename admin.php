<?php
include_once 'header.php'
?>

<section class="admin-form">
    <h2>Administrator Sign up</h2>

    <div class="admin-form-form">

    <form action="includes/admin.inc.php" method="post">
        <input type="text" name = "adminpwd" placeholder = "Admin password...">
        <input type="text" name = "name" placeholder = "Full name...">
        <input type="text" name = "email" placeholder = "Email...">
        <input type="text" name = "uid" placeholder = "Username...">
        <input type="password" name = "pwd" placeholder = "Password...">
        <input type="password" name = "pwdrepeat" placeholder = "Repeat password...">
        <button type = "submit" name = "submit">Sign up</button>

        </form>    
    </div>


<?php
    if (isset($_GET["error"])) {

    if ($_GET["error"] == "emptyinput") {
        echo "<p>Fill in all fields!</p>";
    }
    if ($_GET["error"] == "wrongpass") {
        echo "<p>Wrong admin password!</p>";
    }
    else if($_GET["error"] == "invaliduid") {
        echo "<p>Choose a proper username</p>";
    }
    else if($_GET["error"] == "invalidemail") {
        echo "<p>Choose a proper email</p>";
    }
    else if($_GET["error"] == "passwordsdontmatch") {
        echo "<p>Password's do not match</p>";
    }
    else if($_GET["error"] == "stmtfailed") {
        echo "<p>Something went wrong, try again</p>";
    }
    else if($_GET["error"] == "usernametaken") {
        echo "<p>Username already taken</p>";
    }
    else if($_GET["error"] == "none") {
        echo "<p>You have signed up!</p>";
    }
    

}
?>

</section>

