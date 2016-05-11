<?php
    
    session_start();

	$link = mysqli_connect("localhost", "root", "one") or die("Connect Error " . mysql_error());
	mysqli_select_db($link, "final") or die ("Database Error " . mysqli_error($link));
	
	$tag = strtolower(mysqli_real_escape_string($link, htmlspecialchars($_POST["category"])));
	$itemID = mysqli_real_escape_string($link, htmlspecialchars($_POST["id"]));
	
	if($stmt = mysqli_prepare($link, "SELECT id FROM item_category WHERE name = ?") or die ("prepare error" . mysqli_error($link))){
						
		mysqli_stmt_bind_param($stmt, "s", $tag) or die ("bind param" . mysqli_stmt_error($stmt));
		
		if(mysqli_stmt_execute($stmt) or die ("not executed")){
			
			mysqli_stmt_store_result($stmt) or die (mysqli_stmt_error($stmt));
			
			if(mysqli_stmt_num_rows($stmt) == 0){
				
				if($stmtNewTag = mysqli_prepare($link, "INSERT INTO item_category (name) VALUES (?)") or die ("prepare error" . mysqli_error($link))){
			
					mysqli_stmt_bind_param($stmtNewTag, "s", strtolower($tag)) or die ("bind param" . mysqli_stmt_error($stmtNewTag));
					mysqli_stmt_execute($stmtNewTag) or die(mysqli_stmt_error($stmtNewTag));
						
					if(!mysqli_affected_rows($link)){
						
						print "no change";
					}
				}
				
				mysqli_stmt_close($stmtNewTag);
				
				if($stmtGetNewTag = mysqli_prepare($link, "SELECT id FROM item_category WHERE name = ?") or die ("prepare error" . mysqli_error($link))){
									
					mysqli_stmt_bind_param($stmtGetNewTag, "s", $tag) or die ("bind param" . mysqli_stmt_error($stmtGetNewTag));
					
					if(mysqli_stmt_execute($stmtGetNewTag) or die ("not executed")){
						
						mysqli_stmt_store_result($stmtGetNewTag) or die (mysqli_stmt_error($stmtGetNewTag));
						
						if(mysqli_stmt_num_rows($stmtGetNewTag) != 0){
							
							mysqli_stmt_bind_result($stmtGetNewTag, $tagID) or die (mysqli_stmt_error($stmtGetNewTag));
				
							mysqli_stmt_fetch($stmtGetNewTag);
						}
					}
				}
				
				mysqli_stmt_close($stmtGetNewTag);
				
			}else{
				
				mysqli_stmt_bind_result($stmt, $tagID) or die (mysqli_stmt_error($stmt));
				
				mysqli_stmt_fetch($stmt);
			}
			
			if($stmtTagItem = mysqli_prepare($link, "INSERT INTO item_has_category (item_category_id, item_id) VALUES (?, ?)") or die ("prepare error" . mysqli_error($link))){
	
				mysqli_stmt_bind_param($stmtTagItem, "ii", intval($tagID), intval($itemID)) or die ("bind param" . mysqli_stmt_error($stmtTagItem));
				mysqli_stmt_execute($stmtTagItem) or die(mysqli_stmt_error($stmtTagItem));
					
				if(mysqli_affected_rows($link)){
					
					print "added";
				}
			}
			
			mysqli_stmt_close($stmtTagItem);
		}
	}
	
	mysqli_stmt_close($stmt);
	mysqli_close($link);
?>