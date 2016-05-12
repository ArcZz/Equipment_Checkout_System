<?php

	require_once("../../db.conf");

	$link = mysqli_connect($host, $user, $pass) or die("Connect Error " . mysql_error());
	mysqli_select_db($link, "final") or die ("Database Error " . mysqli_error($link));
	
	if($stmt = mysqli_prepare($link, "SELECT name FROM item_condition") or die ("prepare error" . mysqli_error($link))){
		
		if(mysqli_stmt_execute($stmt) or die ("not executed")){
			
			mysqli_stmt_store_result($stmt) or die (mysqli_stmt_error($stmt));
			
			$conditions = array();
			
			if(mysqli_stmt_num_rows($stmt) != 0){
				
				mysqli_stmt_bind_result($stmt, $condition) or die (mysqli_stmt_error($stmtTag));
									
				while(mysqli_stmt_fetch($stmt)){
				
					$conditions[] = $condition;
				}
			}
		}
	}
	
	mysqli_stmt_close($stmt);
	
	echo json_encode($conditions);
	mysqli_close($link);
?>