<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
     <head>
         <meta charset="utf-8">
         <title> PHP HTML </title>
          <link rel="stylesheet" href="style.css"> </head>
           <body> 
    <nav> 
        <div class="wrapper"> 
                <ul> 
                    <!-- <li><a href="index.php">Home</a></li> -->
                      <?php
                      if (isset($_SESSION["useruid"]))  {
                        echo "<li><a href='index.php'>Home</a></li>";
                        echo "<li><a href='profile.php'>Profile page</a></li>";
                        echo "<li><a href='includes/logout.inc.php'>Log out</a></li>";
                      }
                      elseif(isset($_SESSION["adminuid"])) {
                        echo "<li><a href='admin_dashboard.php'>Home</a></li>";
                        echo "<li><a href='profile.php'>Profile page</a></li>";
                        echo "<li><a href='includes/logout.inc.php'>Log out</a></li>";
                      }
                      else {
                        echo "<li><a href='index.php'>Home</a></li>";
                        echo "<li><a href='signup.php'>Sign up</a></li>";
                        echo "<li><a href='login.php'>Log in</a></li>";
                        echo "<li><a href='admin.php'>Admin Sign up</a></li>";
                        echo "<li><a href='loginadmin.php'>Admin Log in</a></li>";
                      }
                      ?>

    </ul> 
        </div>

    </nav>

