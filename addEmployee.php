<?php
    
    session_start();

	$link = mysqli_connect("localhost", "root", "one") or die("Connect Error " . mysql_error());
	mysqli_select_db($link, "final") or die ("Database Error " . mysqli_error($link));
	
	$username = mysqli_real_escape_string($link, htmlspecialchars($_POST["username"]));
    $first = mysqli_real_escape_string($link, htmlspecialchars($_POST["first"]));
    $last = mysqli_real_escape_string($link, htmlspecialchars($_POST["last"]));
    $pass = mysqli_real_escape_string($link, htmlspecialchars($_POST["pass"]));
    $email = $username . "@mail.missouri.edu";

	if($stmt = mysqli_prepare($link, "INSERT INTO employee (username, email, name_first, name_last, password) VALUES (?, ?, ?, ?, ?)") or die ("prepare error" . mysqli_error($link))){
			
		mysqli_stmt_bind_param($stmt, "sssss", $username, $email , $first, $last, $pass) or die ("bind param" . mysqli_stmt_error($stmt));
		mysqli_stmt_execute($stmt) or die(mysqli_stmt_error($stmt));
			
		if(mysqli_affected_rows($link)){
				
			print $username.$first.$last.$pass;
		}
	}
?>