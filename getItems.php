<?php

	require_once("../../db.conf");

	$link = mysqli_connect($host, $user, $pass) or die("Connect Error " . mysql_error());
	mysqli_select_db($link, "final") or die ("Database Error " . mysqli_error($link));
	
	class item{
    
		public $name = '';
		public $id = -1;
		public $location = "";
		public $tags = array();
		//checked out fields
		public $outName = "";
	}
	
	if($stmt = mysqli_prepare($link, "SELECT id, name, location FROM item") or die ("prepare error" . mysqli_error($link))){
		
		if(mysqli_stmt_execute($stmt) or die ("not executed")){
			
			mysqli_stmt_store_result($stmt) or die (mysqli_stmt_error($stmt));
			
			if(mysqli_stmt_num_rows($stmt) == 0){
				
				mysqli_stmt_close($stmt);
				print "Empty Set";
			}else{
				
				$itemList = array();
				
				mysqli_stmt_bind_result($stmt, $id, $name, $locationId) or die (mysqli_stmt_error($stmt));
				
				while(mysqli_stmt_fetch($stmt)){
					
					$new = new item();
					$new->name = $name;
					$new->id = $id;
					
					if($stmtLocation = mysqli_prepare($link, "SELECT name FROM location WHERE id = ?") or die ("prepare error" . mysqli_error($link))){
						
						mysqli_stmt_bind_param($stmtLocation, "s", $locationId) or die ("bind param" . mysqli_stmt_error($stmtLocation));
						
						if(mysqli_stmt_execute($stmtLocation) or die ("not executed")){
							
							mysqli_stmt_store_result($stmtLocation) or die (mysqli_stmt_error($stmtLocation));
							
							if(mysqli_stmt_num_rows($stmtLocation) != 0){
								
								mysqli_stmt_bind_result($stmtLocation, $locationName) or die (mysqli_stmt_error($stmtLocation));
								
								mysqli_stmt_fetch($stmtLocation);
								
								$new->location = $locationName;
							}
						}
					}
					
					mysqli_stmt_close($stmtLocation);
					
					if($stmtTag = mysqli_prepare($link, "SELECT item_category_id FROM item_has_category WHERE item_id = ?") or die ("prepare error" . mysqli_error($link))){
										
						mysqli_stmt_bind_param($stmtTag, "i", $id) or die ("bind param" . mysqli_stmt_error($stmtTag));
						
						if(mysqli_stmt_execute($stmtTag) or die ("not executed")){
							
							mysqli_stmt_store_result($stmtTag) or die (mysqli_stmt_error($stmtTag));
							
							$tagList = array();
							
							if(mysqli_stmt_num_rows($stmtTag) != 0){
								
								mysqli_stmt_bind_result($stmtTag, $tagId) or die (mysqli_stmt_error($stmtTag));
								
								while(mysqli_stmt_fetch($stmtTag)){
								
									$tagList[] = $tagId;
								}
							}
						}
					}
					
					mysqli_stmt_close($stmtTag);
					
					for($i = 0; $i < count($tagList); $i++){
						
						if($stmtTag = mysqli_prepare($link, "SELECT name FROM item_category WHERE id = ?") or die ("prepare error" . mysqli_error($link))){
										
							mysqli_stmt_bind_param($stmtTag, "i", $tagList[$i]) or die ("bind param" . mysqli_stmt_error($stmtTag));
							
							if(mysqli_stmt_execute($stmtTag) or die ("not executed")){
								
								mysqli_stmt_store_result($stmtTag) or die (mysqli_stmt_error($stmtTag));
								
								if(mysqli_stmt_num_rows($stmtTag) != 0){
									
									mysqli_stmt_bind_result($stmtTag, $tagName) or die (mysqli_stmt_error($stmtTag));
									
									while(mysqli_stmt_fetch($stmtTag)){
									
										$tagList[$i] = $tagName;
									}
								}
							}
						}
					}
					
					$new->tags = $tagList;
					$itemList[] = $new;
				}
			}
		}
	}
	
	mysqli_stmt_close ($stmt);
	
	echo json_encode($itemList);
	mysqli_close($link);
?>