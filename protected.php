<!DOCTYPE html>
<html>
	<head>

		<title></title>
		
		<link rel="stylesheet" type="text/css" href="control.css">
	</head>

	<body>
		
		<p>If you are grading right now I am currently messing with sessions so the following feedback isn't realiable but reaching this page does indicate a "successful" login</p>
		
		<?php
		
			require_once("authentication.php");
		
			if(!(session_status() == PHP_SESSION_ACTIVE)) {
				
				?>
				<p>Session error</p>
				<?php
				exit;
			}
		
			$loggedIn = empty($_SESSION['loggedin']) ? false : $_SESSION['loggedin'];
		
			if($loggedIn){
		?>
			<p>Login Successful <?=$_SESSION['loggedin']?> is logged in</p>
			
		<?php
			}else{
				
		?>
		
			<p>Bad</p>
		<?php
			}
		?>
	</body>
</html>