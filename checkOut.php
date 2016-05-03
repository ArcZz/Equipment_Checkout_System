<?php

	session_start();
	
	require_once("../../db.conf");

	$link = mysqli_connect($host, $user, $pass) or die("Connect Error " . mysql_error());
	mysqli_select_db($link, "final") or die ("Database Error " . mysqli_error($link));
	
  /*+-------------------+---------+------+-----+---------+-------+
	| Field             | Type    | Null | Key | Default | Extra |
	+-------------------+---------+------+-----+---------+-------+
	| student_id        | int(11) | NO   | PRI | NULL    |       |
	| item_id           | int(11) | NO   | PRI | NULL    |       |
	| item_condition_id | int(11) | NO   | PRI | 0       |       |
	| location_id       | int(11) | NO   | PRI | NULL    |       |
	| employee_id       | int(11) | NO   | PRI | NULL    |       |
	| time_expire       | time    | NO   |     | NULL    |       |
	+-------------------+---------+------+-----+---------+-------+
	*/
	
	$student_id = intval(mysqli_real_escape_string($link, htmlspecialchars($_POST['student'])));
	$item_barcode = intval(mysqli_real_escape_string($link, htmlspecialchars($_POST['item'])));
	//$condition = $_POST['condition'];
	$time = $_POST['time'];
	
	if($stmt = mysqli_prepare($link, "SELECT id FROM student WHERE id = ?") or die ("prepare error" . mysqli_error($link))){
		
		mysqli_stmt_bind_param($stmt, "s", $student_id) or die ("bind param" . mysqli_stmt_error($stmt));
		
		if(mysqli_stmt_execute($stmt) or die ("not executed")){
			
			mysqli_stmt_store_result($stmt) or die (mysqli_stmt_error($stmt));
			
			$userExists = (mysqli_stmt_num_rows($stmt) == 0) ? false : true;
		}
	}
	
	mysqli_stmt_close($stmt);
	
	if($stmt = mysqli_prepare($link, "SELECT location FROM item WHERE id = ?") or die ("prepare error" . mysqli_error($link))){
		
		mysqli_stmt_bind_param($stmt, "s", $item_barcode) or die ("bind param" . mysqli_stmt_error($stmt));
		
		if(mysqli_stmt_execute($stmt) or die ("not executed")){
			
			mysqli_stmt_store_result($stmt) or die (mysqli_stmt_error($stmt));
			
			if(mysqli_stmt_num_rows($stmt)){
				
				mysqli_stmt_bind_result($stmt, $location);
				
				mysqli_stmt_fetch($stmt);
				$item_location = intval($location);
				$itemExists = true;
			}else{
			
				$itemExists = false;
			}
		}
	}
	
	mysqli_stmt_close($stmt);
	
	$sessionUser = 2;
	$condition = 1;
	
	if($itemExists && $userExists){
	
		if($stmt = mysqli_prepare($link, "INSERT INTO student_item_transaction (student_id, item_id, item_condition_id, location_id, employee_id, time_expire) VALUES (?, ?, ?, ?, ?, ?)") or die ("prepare error" . mysqli_error($link))){
			
			mysqli_stmt_bind_param($stmt, "iiiiis", $student_id, $item_barcode, $condition, $item_location, $sessionUser, $time) or die ("bind param" . mysqli_stmt_error($stmt));
			
			if(mysqli_stmt_execute($stmt) or die ("not executed" .  mysqli_stmt_error($stmt))){
				
				if(mysqli_affected_rows($link)){
					
					print "Success";
				}else{
					
					print "No Change";
				}
			}
		}
	}else{
		
		print $itemExists . " b " . $userExists;
	}
	
	// mysqli_stmt_close ($stmt);
	// mysqli_close($link);
?>