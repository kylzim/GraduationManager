<?php

function emptyInputSignup($name, $email, $username, $pwd, $pwdRepeat)
	{
		$result;
		if( empty($name) || empty($email) || empty($username) || empty($pwd) || empty($pwdRepeat) )
		{
			$result = true;
		}
		else
		{
			$result = false;
		}
		return $result;
	}


function invalidUid($username)
	{
		$result;
		if( !preg_match("/^[a-zA-Z0-9]*$/", $username) )
		{
			$result = true;
		}
		else
		{
			$result = false;
		}
		return $result;
	}

function invalidEmail($email)
	{
		$result;
		if( !filter_var($email, FILTER_VALIDATE_EMAIL) )
		{
			$result = true;
		}
		else
		{
			$result = false;
		}
		return $result;
	}

function pwdMatch($pwd, $pwdRepeat)
	{
		$result;
		if( $pwd !== $pwdRepeat )
		{
			$result = true;
		}
		else
		{
			$result = false;
		}
		return $result;
	}

function uidExists($conn, $username, $email)
	{
		$sql = "SELECT * FROM users WHERE usersUid = ? OR usersEmail = ?;";
		
		$stmt = mysqli_stmt_init($conn);
		
		if( !mysqli_stmt_prepare($stmt, $sql) )
		{
			header("location: ../signup.php?error=stmtfailed");
			exit();
		}
		
		mysqli_stmt_bind_param($stmt, "ss", $username, $email);
		mysqli_stmt_execute($stmt);
		
		$resultData = mysqli_stmt_get_result($stmt);
		
	
		if( $row = mysqli_fetch_assoc($resultData) )
		{
			return $row;
		}
		else
		{
            $result = false;
			return $result;
		}
        mysqli_smt_close($stmt);
	}

    function createUser($conn, $name, $email, $username, $pwd)
	{
		$sql = "INSERT INTO users (usersName, usersEmail, usersUid, usersPwd) VALUES (?, ?, ?, ?);";
		
		$stmt = mysqli_stmt_init($conn);
		
		if ( !mysqli_stmt_prepare($stmt, $sql) )
		{
			header("location: ../signup.php?error=stmtfailed");
			exit();
		}
		
		$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
		
		mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $username, $hashedPwd);
		
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		header("location: ../signup.php?error=none");
		exit();
	}

	function createAdminUser($conn, $name, $email, $username, $pwd)
	{
		$sql = "INSERT INTO admin (adminName, adminEmail, adminUid, adminPwd) VALUES (?, ?, ?, ?);";
		
		$stmt = mysqli_stmt_init($conn);
		
		if ( !mysqli_stmt_prepare($stmt, $sql) )
		{
			header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		
		$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
		
		mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $username, $hashedPwd);
		
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		header("location: ../admin.php?error=none");
		exit();
	}

	function uidAdminExists($conn, $username, $email)
	{
		$sql = "SELECT * FROM admin WHERE adminUid = ? OR adminEmail = ?;";
		
		$stmt = mysqli_stmt_init($conn);
		
		if( !mysqli_stmt_prepare($stmt, $sql) )
		{
			header("location: ../admin.php?error=stmtfailed");
			exit();
		}
		
		mysqli_stmt_bind_param($stmt, "ss", $username, $email);
		mysqli_stmt_execute($stmt);
		
		$resultData = mysqli_stmt_get_result($stmt);
		
	
		if( $row = mysqli_fetch_assoc($resultData) )
		{
			return $row;
		}
		else
		{
            $result = false;
			return $result;
		}
        mysqli_smt_close($stmt);
	}

	function loginAdminUser($conn, $username, $pwd) {
        $uidAdminExists = uidAdminExists($conn,$username,$username);

        if ($uidAdminExists === false) {
            header("location: ../login.php?error=wronglogin");
            exit();
        }

        $pwdHashed = $uidAdminExists["adminPwd"];
        $checkPwd = password_verify($pwd, $pwdHashed);

        if ($checkPwd === false) {
            header("location: ../login.php?error=wronglogin");
            exit();
        }
        else if ($checkPwd === true) {
            session_start();

            $_SESSION["adminid"] = $uidAdminExists["adminId"];
            $_SESSION["adminuid"] = $uidAdminExists["adminUid"];
            header("location: ../admin_dashboard.php");
            exit();

        }
    }

    function emptyInputLogin($username, $pwd)
	{
		$result;
		if( empty($username) || empty($pwd))
		{
			$result = true;
		}
		else
		{
			$result = false;
		}
		return $result;
	}
    
    function loginUser($conn, $username, $pwd) {
        $uidExists = uidExists($conn,$username,$username);

        if ($uidExists === false) {
            header("location: ../login.php?error=wronglogin");
            exit();
        }

        $pwdHashed = $uidExists["usersPwd"];
        $checkPwd = password_verify($pwd, $pwdHashed);

        if ($checkPwd === false) {
            header("location: ../login.php?error=wronglogin");
            exit();
        }
        else if ($checkPwd === true) {
            session_start();

            $_SESSION["userid"] = $uidExists["usersId"];
            $_SESSION["useruid"] = $uidExists["usersUid"];
            header("location: ../index.php");
            exit();

        }
    }

	

//my attempt

// Get user information
function getUserInfo($conn, $field, $userId) {
    $sql = "SELECT $field FROM users WHERE usersId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../profile.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        return $row[$field];
    } else {
        return "";
    }

    mysqli_stmt_close($stmt);
}

function getAdminInfo($conn, $field, $userId) {
    $sql = "SELECT $field FROM admin WHERE adminId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../profile.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        return $row[$field];
    } else {
        return "";
    }

    mysqli_stmt_close($stmt);
}

// Update user information
function updateUser($conn, $userId, $name, $email, $uid) {
	error_log("userId: $userId");
    error_log("name: $name");
    error_log("email: $email");
    $sql = "UPDATE users SET usersName = ?, usersEmail = ?, usersUid = ? WHERE usersId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../profile.php?error=stmtfailed");
        exit();
    }

	// $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $uid, $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../profile.php?update=success");
    exit();
}

function updateAdminUser($conn, $userId, $name, $email, $uid) {

    $sql = "UPDATE admin SET adminName = ?, adminEmail = ?, adminUid = ? WHERE adminId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../profile.php?error=stmtfailed");
        exit();
    }

	// $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $uid, $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../profile.php?update=success");
    exit();
}

// Delete user account
function deleteUser($conn, $userId) {
    $sql = "DELETE FROM users WHERE usersId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../profile.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    session_unset();
    session_destroy();
    header("location: logout.inc.php?delete=success");
    exit();
}

function deleteAdminUser($conn, $userId) {
    $sql = "DELETE FROM admin WHERE adminId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../profile.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    session_unset();
    session_destroy();
    header("location: logout.inc.php?delete=success");
    exit();
}