<?php

	session_start();

	$link = mysqli_connect("localhost", "root", "one") or die("Connect Error " . mysql_error());
	mysqli_select_db($link, "final") or die ("Database Error " . mysqli_error($link));
	
	$tag = mysqli_real_escape_string($link, htmlspecialchars($_POST["tag"]));
	$itemID = mysqli_real_escape_string($link, htmlspecialchars($_POST["id"]));
	
	if($stmt = mysqli_prepare($link, "SELECT id FROM item_category WHERE name = ?") or die ("prepare error" . mysqli_error($link))){
						
		mysqli_stmt_bind_param($stmt, "s", $tag) or die ("bind param" . mysqli_stmt_error($stmt));
		
		if(mysqli_stmt_execute($stmt) or die ("not executed")){
			
			mysqli_stmt_store_result($stmt) or die (mysqli_stmt_error($stmt));
			
			if(mysqli_stmt_num_rows($stmt) != 0){
				
				mysqli_stmt_bind_result($stmt, $tagID) or die (mysqli_stmt_error($stmt));
				
				mysqli_stmt_fetch($stmt);
				
				if($stmtDelete = mysqli_prepare($link, "DELETE FROM item_has_category WHERE item_id = ? AND item_category_id = ?") or die ("prepare error" . mysqli_error($link))){
						
					mysqli_stmt_bind_param($stmtDelete, "ii", intval($itemID), intval($tagID)) or die ("bind param" . mysqli_stmt_error($stmtDelete));
					
					if(mysqli_stmt_execute($stmtDelete) or die ("not executed")){
						
						mysqli_stmt_store_result($stmtDelete) or die (mysqli_stmt_error($stmtDelete));
						
						if(mysqli_affected_rows($link)){
							
							print "success";
						}
					}
				}
				
				mysqli_stmt_close($stmtDelete);
			}else{
				
				print "fake";
			}
		}
	}
	
	mysqli_stmt_close($stmt);
	mysqli_close($link);
?>