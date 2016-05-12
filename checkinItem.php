<?php

	require_once("../../db.conf");

	$link = mysqli_connect($host, $user, $pass) or die("Connect Error " . mysql_error());
	mysqli_select_db($link, "final") or die ("Database Error " . mysqli_error($link));

	$id = $_POST['id'];

	if($stmt = mysqli_prepare($link, "DELETE FROM student_item_transaction WHERE item_id = ?") or die ("prepare error" . mysqli_error($link))){

		mysqli_stmt_bind_param($stmt, "i", $id) or die ("bind param" . mysqli_stmt_error($stmt));

		if(mysqli_stmt_execute($stmt) or die ("not executed")){

			if(mysqli_affected_rows($link)){

				print "success";
			}else{

				var_dump($id);
			}
		}
	}

	mysqli_stmt_close($stmt);
	mysqli_close($link);
?>
