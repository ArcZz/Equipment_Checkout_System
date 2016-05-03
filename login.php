<?php
session_start();



// Pull username from $_COOKIE, if it exists
setcookie('userid', '', 1);
$username = empty($_COOKIE['userid']) ? '' : $_COOKIE['userid'];

// If the user is logged in, redirect them home
if ($username) {
	header("Location: index.php");
	exit;
}


?>
<html lang="en">
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"><!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css"><!-- Optional theme -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script><!-- Latest compiled and minified JavaScript -->
		<link rel="stylesheet" type="text/css" href="css/log.css">
		<script src="js/login.js"></script>
		<title>Check us out</title>

	</head>
	<body>
		<nav class="navbar navbar-inverse mizzou" style="height = 100px">
      <div class="container-fluid">
        <form class="navbar-right">
          <ul class="nav navbar-nav" id="navbar_top">
          <li><a style="color:rgba(241,184,45,.7);" href="http://missouri.edu/">Mizzou</a></li>
          <li><a href="#">address</a></li>
          <li><a href="#">phone-number</a></li>
          </ul>
        </form>
      </div>
    </nav>

		<div class="container">
			<div class="row" >
				 <div class="col-md-3 col-sm-3 col-xs-3">
			   </div>
	    	 <div class="col-md-6 col-sm-6 col-xs-6">
            <form id="form" class="form-signin" >
              <h1 class="form-signin-heading"><img src="images/logo.png" hspace="10" >Check out</h3>
              <br>
					  	<div class="row form-group">
              <p class="thick">I am a
              <select id = "isNew" class = "border lightHover">
            	<option value = "0">returning</option>
					    <option value = "1">new</option>
				      </select>	employee
						  </p>
						</div>

						<div class="row form-group">
              <label for="user">Username</label>
						  <input class="form-control" type="text" id="user" name="user" placeholder="needs to be at least 2 letters long">
					  </div>

						<div class="row form-group sign hidden">
							<label for="fname">First name</label>
							<input class="form-control" type="text" id="fname" name="fname" placeholder="needs to be at least 2 letters long">
						</div>

						<div class="row form-group sign hidden">
							<label for="lname">Last name</label>
							<input class="form-control" type="text" id="lname" name="lname" placeholder="needs to be at least 2 letters long">
						</div>

						<div class="row form-group sign hidden">
							<label for="email">Email</label>
							<input class="form-control" type="text" id="email" name="email" placeholder="xxxx@mail.missouri.edu">
						</div>

						<div class="row form-group">
              <label for="pass">Password</label>
             	<input class="form-control" type="password" id="pass" name="pass" placeholder="needs to be at least 4 letters long">
					  </div>

						<div class="row form-group sign hidden">
								<input class="form-control" type="password" id="pass1" name="pass1" placeholder="retype the password">
						</div>

						<div class="row form-group">
								<button class=" btn btn-large btn-primary" type="button" id="submit" name="submit"> Submit</button>
						</div>
            <div id="hint" class="row form-group">

           </div>
					</form>


</div>
</div>
</div>
</body>
</html>
