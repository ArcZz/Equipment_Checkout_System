<?php

	require_once("../../db.conf");

	$link = mysqli_connect($host, $user, $pass) or die("Connect Error " . mysql_error());
	mysqli_select_db($link, "final") or die ("Database Error " . mysqli_error($link));

	$id = $_POST['id'];
  $location = $_POST['locationitem'];
  $condition = $_POST['conditionitem'];


        $stmt = mysqli_prepare($link, "SELECT id FROM item_condition WHERE name = ?") or die ("prepare error" . mysqli_error($link));

	      mysqli_stmt_bind_param($stmt, "s", $condition) or die("bind param");
        mysqli_stmt_execute($stmt) or die("execute1");
        mysqli_stmt_bind_result($stmt, $condition_id) or die (mysqli_stmt_error($stmt));
        mysqli_stmt_fetch($stmt) or die(mysqli_stmt_error($stmt));
        $cid = $condition_id;
        mysqli_stmt_close($stmt);


  $stmt = mysqli_prepare($link, "SELECT id FROM location WHERE name = ?") or die ("prepare error" . mysqli_error($link));

        mysqli_stmt_bind_param($stmt, "s", $location ) or die("bind param");
        mysqli_stmt_execute($stmt) or die("execute2");
        mysqli_stmt_bind_result($stmt, $location_id) or die (mysqli_stmt_error($stmt));
        mysqli_stmt_fetch($stmt) or die(mysqli_stmt_error($stmt));
        $lid = $location_id;
        mysqli_stmt_close($stmt);
 //
 $stmt = mysqli_prepare($link, "SELECT item_id  FROM student_item_transaction WHERE item_id = ?") or die ("prepare error" . mysqli_error($link));
 	mysqli_stmt_bind_param($stmt, "s", $id) or die ("bind param" . mysqli_stmt_error($stmt));

   	mysqli_stmt_execute($stmt) or die ("not executed");

 		mysqli_stmt_store_result($stmt) or die (mysqli_stmt_error($stmt));

 		if(mysqli_stmt_num_rows($stmt))
		{ mysqli_stmt_close($stmt);
			$stmt = mysqli_prepare($link, "UPDATE student_item_transaction SET item_condition_id  = ?, location_id = ? WHERE item_id = ?") or die ("prepare error" . mysqli_error($link));
				 mysqli_stmt_bind_param($stmt, "ddd", $cid,$lid,$id) or die ("bind param" . mysqli_stmt_error($stmt));
				 mysqli_stmt_execute($stmt) or die ("not executed4");
						if(mysqli_affected_rows($link)){
							print "s";
						}
 	 	}else{
				print "no";
		}

  mysqli_stmt_close($stmt);
$stmt = mysqli_prepare($link, "UPDATE item SET location = ? WHERE id = ?") or die ("prepare error" . mysqli_error($link));
		mysqli_stmt_bind_param($stmt, "dd", $lid, $id) or die ("bind param" . mysqli_stmt_error($stmt));

		mysqli_stmt_execute($stmt) or die ("not executed 13");
	if(mysqli_affected_rows($link)){
        mysqli_stmt_close($stmt);
				if($stmt = mysqli_prepare($link, "UPDATE item_condition_update SET item_condition_id  = ? WHERE item_id = ?") or die ("prepare error" . mysqli_error($link)))
			    {
			     mysqli_stmt_bind_param($stmt, "dd", $cid, $id) or die ("bind param" . mysqli_stmt_error($stmt));
			     mysqli_stmt_execute($stmt) or die ("not executed4");
			        if(mysqli_affected_rows($link)){
			          print "s";
			        }

			    }

		}else{
				var_dump($lid);
			}

	mysqli_stmt_close($stmt);
	mysqli_close($link);
?>
