<?php
session_start();

?>
<?php
$link = mysqli_connect("localhost", "root", "one", "final") or die("Connect Error " . mysqli_error($link));
	$username = htmlspecialchars($_POST['user']);
	$userid= htmlspecialchars($_POST['userid']);
	$fname = htmlspecialchars($_POST['fname']);
	$lname = htmlspecialchars($_POST['lname']);
	$email = htmlspecialchars($_POST['email']);
	$password = htmlspecialchars($_POST['pass']);
	$isNew = $_POST["new"];


	$query = "SELECT password FROM employee WHERE username = ?";
  $stmt=mysqli_prepare($link, $query) or die("Prepare:".mysql_error());
	 mysqli_stmt_bind_param($stmt, "s", $username) or die("bind param");
	 mysqli_stmt_execute($stmt) or die("execute");
	 mysqli_stmt_store_result($stmt) or die (mysqli_stmt_error($stmt));

//login attempt----------------+_+_+_+__+_+_++++++
if($isNew == "0"){

	 if(mysqli_stmt_num_rows($stmt) == 0){
		          mysqli_stmt_close ($stmt);
	            echo "fail";
	          }
	  else{
		          mysqli_stmt_bind_result($stmt, $pass) or die (mysqli_stmt_error($stmt));

					  	mysqli_stmt_fetch($stmt) or die(mysqli_stmt_error($stmt));

	           if(password_verify($password, $pass)){
							$_SESSION["user"] = $username;
								echo "success";


              }
	          	else{
			        echo "fail";
	          	}
        }
}


      	//sign in attempt
else{
// | Field      | Type         | Null | Key | Default | Extra          |
// +------------+--------------+------+-----+---------+----------------+
// | id         | int(11)      | NO   | PRI | NULL    | auto_increment |
// | username   | varchar(16)  | YES  |     | NULL    |                |
// | email      | varchar(255) | YES  |     | NULL    |                |
// | name_first | varchar(30)  | YES  |     | NULL    |                |
// | name_last  | varchar(45)  | YES  |     | NULL    |                |
// | password   | varchar(256) | YES  |     | NULL    |                        |
// +------------+--------------+------+-----+---------+----------------+


		  if(mysqli_stmt_num_rows($stmt) == 1 ){
			 mysqli_stmt_close ($stmt);
			 echo "existed";
		  }
		  else{    //sign in!
			mysqli_stmt_close ($stmt);
			$query1 = "INSERT INTO employee (username, email, name_first, name_last, password) VALUES (?,?,?,?,?)";
			$stmt=mysqli_prepare($link, $query1) or die("Prepare:".mysql_error());
			 mysqli_stmt_bind_param($stmt, "sssss", $username,$email,$fname,$lname,password_hash($password, PASSWORD_DEFAULT)) or die("bind param");
			if(mysqli_stmt_execute($stmt)) {
  			mysqli_stmt_close ($stmt);
				echo "signin";

			}
	  	}
	}
  mysqli_free_result($result);
?>
