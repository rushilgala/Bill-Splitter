<?php
session_start();
include_once 'include.php';
if (loggedIn()) {
	header('location:dashboard.php');
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>User Registration</title>
		<link rel="stylesheet" href="css/overall.css" type="text/css" charset="utf-8">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	</head>
	<body>
		<?php include_once('header.php');?>
		<div class="container">
			<h1>Registration</h1>
			<form class="form-horizontal" role="form" method="post" action="createuser.php">
				<div class="form-group">
					<label class="control-label col-sm-2" for="username">Username:</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="username" pattern="[a-zA-Z0-9]{5,}" title="Minmimum 5 letters or numbers." id="username" placeholder="Enter username" required />
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="pwd">Password:</label>
					<div class="col-sm-10"> 
						<input type="password" class="form-control" name="pwd" id="pwd" pattern=".{5,}" title="Minmimum 5 letters or numbers." placeholder="Enter password" required />
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="pwd_repeat">Repeat Password:</label>
					<div class="col-sm-10"> 
						<input type="password" class="form-control" id="pwd_repeat" pattern=".{5,}" title="Minmimum 5 letters or numbers." placeholder="Repeat password" required />
					</div>
				</div>
				<script>
					var password = document.getElementById("pwd")  
					var confirm_password = document.getElementById("pwd_repeat");

					function validatePassword(){
						if(password.value != confirm_password.value) {
							confirm_password.setCustomValidity("Passwords Don't Match");
						} else {
							confirm_password.setCustomValidity('');
						}
					}

					password.onchange = validatePassword;
					confirm_password.onkeyup = validatePassword;
				
				</script>
				<div class="form-group">
					<label class="control-label col-sm-2" for="email">Email:</label>
					<div class="col-sm-10"> 
						<input type="email" class="form-control" name="email" id="email" placeholder="Enter email" required />
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="firstname">First Name:</label>
					<div class="col-sm-10"> 
						<input type="text" class="form-control" name="firstname" id="firstname" pattern="[a-zA-Z]{1,}" title="Can only contain letters." placeholder="Enter first name" required />
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="lastname">Last Name:</label>
					<div class="col-sm-10"> 
						<input type="text" class="form-control" name="lastname" id="lastname" placeholder="Enter last name" pattern="[a-zA-Z]{1,}" title="Can only contain letters." required />
					</div>
				</div>
				<div class="form-group"> 
					<div class="col-sm-offset-2 col-sm-10">
						<button id="submit" type="submit" class="btn btn-default">Register</button>
					</div>
				</div>
			</form>
		</div>
	</body>
</html>