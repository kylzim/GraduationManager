<?php

if (isset($_POST["submit"])) {
    
    $adminpwd = $_POST["adminpwd"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $username = $_POST["uid"];
    $pwd = $_POST["pwd"];
    $pwdRepeat = $_POST["pwdrepeat"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if ($adminpwd != "admin")
        {
            header("location: ../admin.php?error=wrongpass");
		    exit();
        }
    if( emptyInputSignup($name, $email, $username, $pwd, $pwdRepeat) !== false )
		{
			header("location: ../admin.php?error=emptyinput");
			exit();
		}
    if(invalidUid($username) !== false )
		{
			header("location: ../admin.php?error=invaliduid");
			exit();
		}
    if(invalidEmail($email) !== false )
		{
			header("location: ../admin.php?error=invalidemail");
			exit();
		}
    if(pwdMatch($pwd, $pwdRepeat) !== false )
		{
			header("location: ../admin.php?error=passwordsdontmatch");
			exit();
		}
    if(uidExists($conn, $username, $email) !== false )
		{
			header("location: ../admin.php?error=usernametaken");
			exit();
		}


        createAdminUser($conn, $name, $email, $username, $pwd);
}

else {
    header("location: ../admin.php");
    exit();
}