<?php

	require_once("../../db.conf");

	$link = mysqli_connect($host, $user, $pass) or die("Connect Error " . mysql_error());
	mysqli_select_db($link, "final") or die ("Database Error " . mysqli_error($link));
	
	class item{
    
		public $name = "";
		public $id = -1;
		public $location = "";
		public $condition = "";
		public $tags = array();
		public $checkoutInfo = null;
	}
	
	class checkOutInfo{
		
		public $studentName = "";
		public $employeeName = "";
		public $timeExpire = "";
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
					
					if($stmtCondition = mysqli_prepare($link, "SELECT item_condition_id FROM item_condition_update WHERE item_id = ?") or die ("prepare error" . mysqli_error($link))){
						
						mysqli_stmt_bind_param($stmtCondition, "i", $id) or die ("bind param" . mysqli_stmt_error($stmtCondition));
						
						if(mysqli_stmt_execute($stmtCondition) or die ("not executed")){
							
							mysqli_stmt_store_result($stmtCondition) or die (mysqli_stmt_error($stmtCondition));
							
							if(mysqli_stmt_num_rows($stmtCondition) != 0){
								
								mysqli_stmt_bind_result($stmtCondition, $conditionID) or die (mysqli_stmt_error($stmtCondition));
								
								mysqli_stmt_fetch($stmtCondition);
								
								$new->condition = $conditionID;
							}
						}
					}
					
					mysqli_stmt_close($stmtCondition);
					
					if($stmtCondition = mysqli_prepare($link, "SELECT name FROM item_condition WHERE id = ?") or die ("prepare error" . mysqli_error($link))){
						
						mysqli_stmt_bind_param($stmtCondition, "s", $new->condition) or die ("bind param" . mysqli_stmt_error($stmtCondition));
						
						if(mysqli_stmt_execute($stmtCondition) or die ("not executed")){
							
							mysqli_stmt_store_result($stmtCondition) or die (mysqli_stmt_error($stmtCondition));
							
							if(mysqli_stmt_num_rows($stmtCondition) != 0){
								
								mysqli_stmt_bind_result($stmtCondition, $conditionName) or die (mysqli_stmt_error($stmtCondition));
								
								mysqli_stmt_fetch($stmtCondition);
								
								$new->condition = $conditionName;
							}
						}
					}
					
					mysqli_stmt_close($stmtCondition);
					
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
					
					if($stmtCheckout = mysqli_prepare($link, "SELECT student_id, employee_id, time_expire FROM student_item_transaction WHERE item_id = ?") or die ("prepare error" . mysqli_error($link))){
						
						mysqli_stmt_bind_param($stmtCheckout, "s", $id) or die ("bind param" . mysqli_stmt_error($stmtCheckout));
						
						if(mysqli_stmt_execute($stmtCheckout) or die ("not executed")){
							
							mysqli_stmt_store_result($stmtCheckout) or die (mysqli_stmt_error($stmtCheckout));
							
							if(mysqli_stmt_num_rows($stmtCheckout) != 0){
								
								mysqli_stmt_bind_result($stmtCheckout, $checkStudent, $checkEmployee, $checkExpire) or die (mysqli_stmt_error($stmtCheckout));
								
								mysqli_stmt_fetch($stmtCheckout);
								
								$checkInfo = new checkOutInfo();
								$checkInfo->studentName = $checkStudent;
								
								if($stmtStudentName = mysqli_prepare($link, "SELECT name_first, name_last FROM student WHERE id = ?") or die ("prepare error" . mysqli_error($link))){
						
									mysqli_stmt_bind_param($stmtStudentName, "i", $checkStudent) or die ("bind param" . mysqli_stmt_error($stmtStudentName));
									
									if(mysqli_stmt_execute($stmtStudentName) or die ("not executed")){
										
										mysqli_stmt_store_result($stmtStudentName) or die (mysqli_stmt_error($stmtStudentName));
										
										if(mysqli_stmt_num_rows($stmtStudentName) != 0){
											
											mysqli_stmt_bind_result($stmtStudentName, $fName, $lName) or die (mysqli_stmt_error($stmtStudentName));
											
											mysqli_stmt_fetch($stmtStudentName);
											
											$checkInfo->studentName = $fName . " " . $lName;
										}
									}
								}
								
								mysqli_stmt_close($stmtStudentName);
								
								$checkInfo->employeeName = $checkEmployee;
								
								if($stmtEmployeeName = mysqli_prepare($link, "SELECT name_first, name_last FROM employee WHERE id = ?") or die ("prepare error" . mysqli_error($link))){
						
									mysqli_stmt_bind_param($stmtEmployeeName, "i", $checkEmployee) or die ("bind param" . mysqli_stmt_error($stmtEmployeeName));
									
									if(mysqli_stmt_execute($stmtEmployeeName) or die ("not executed")){
										
										mysqli_stmt_store_result($stmtEmployeeName) or die (mysqli_stmt_error($stmtEmployeeName));
										
										if(mysqli_stmt_num_rows($stmtEmployeeName) != 0){
											
											mysqli_stmt_bind_result($stmtEmployeeName, $fName, $lName) or die (mysqli_stmt_error($stmtEmployeeName));
											
											mysqli_stmt_fetch($stmtEmployeeName);
											
											$checkInfo->employeeName = $fName . " " . $lName;
										}
									}
								}
								
								mysqli_stmt_close($stmtEmployeeName);
								
								$checkInfo->timeExpire = $checkExpire;
								
								$new->checkoutInfo = $checkInfo;
							}
						}
					}
					
					mysqli_stmt_close($stmtCheckout);
					
					$new->tags = $tagList;
					$itemList[] = $new;
				}
			}
		}
	}
	
	mysqli_stmt_close($stmt);
	
	echo json_encode($itemList);
	mysqli_close($link);
?>