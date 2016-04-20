<?php

	$link = mysqli_connect("localhost", "root", "") or die("Connect Error " . mysql_error());
	mysqli_select_db($link, "lab7") or die ("Database Error " . mysql_error($link));
	
	session_start();
	
	$username = htmlspecialchars($_POST["uName"]);
	$password = htmlspecialchars($_POST["pass"]);
	$isNew = $_POST["new"];
	
	//+-----------------+--------------+------+-----+---------+-------+
	//| Field           | Type         | Null | Key | Default | Extra |
	//+-----------------+--------------+------+-----+---------+-------+
	//| username        | varchar(20)  | NO   | PRI | NULL    |       |
	//| salt            | varchar(20)  | YES  |     | NULL    |       |
	//| hashed_password | varchar(256) | YES  |     | NULL    |       |
	//+-----------------+--------------+------+-----+---------+-------+

	
	if($stmt = mysqli_prepare($link, "SELECT hashed_password FROM user WHERE username = ?") or die ("prepare error" . mysqli_error($link))){
		
		mysqli_stmt_bind_param($stmt, "s", $username) or die ("bind param" . mysqli_stmt_error($stmt));
		
		if(mysqli_stmt_execute($stmt) or die ("not executed")){
			
			mysqli_stmt_store_result($stmt) or die (mysqli_stmt_error($stmt));
			
			if($isNew == "0"){//login attempt
				
				if(mysqli_stmt_num_rows($stmt) == 0){//user does not exist
					
					mysqli_stmt_close($stmt);
					print "Unreal";
				}else{//user exists
					
					mysqli_stmt_bind_result($stmt, $storedPass) or die (mysqli_stmt_error($stmt));
					mysqli_stmt_fetch($stmt) or die(mysqli_stmt_error($stmt));
					
					if(password_verify($password, $storedPass)){
						
						$_SESSION['loggedin'] = $username;
						print "Golden";
					}else{
						
						print "Liar";
					}
					
					mysqli_stmt_close ($stmt);
				}
			}else{//new account creation
				
				if(mysqli_stmt_num_rows($stmt) == 1){//account exists
					
					mysqli_stmt_close ($stmt);
					print "Exist";
				}else{//account does not exist yet
				
					mysqli_stmt_close ($stmt);
					
					if($stmt = mysqli_prepare($link, "INSERT INTO user (username, hashed_password) VALUES (?,?)") or die ("prepare error" . mysqli_error($link))){
						
						mysqli_stmt_bind_param($stmt, "ss", $username, password_hash($password, PASSWORD_DEFAULT)) or die ("bind param" . mysqli_stmt_error($stmt));
						mysqli_stmt_execute($stmt) or die(mysqli_stmt_error($stmt));
						
						if(mysqli_affected_rows($link)){
							
							print "Add";
						}
					}
				}
			}
		}
	}
	
	mysqli_close($link);
?>