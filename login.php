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
		<title>Login</title>
		<link rel="stylesheet" href="css/overall.css" type="text/css" charset="utf-8">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script sec="js/registration.js"></script>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	</head>
	<body>
		<?php include_once('header.php');?>
		<div class="container">
			<h1>Login</h1>
			<form class="form-horizontal" role="form" action="login_user.php" method="post">
				<div class="form-group">
					<label class="control-label col-sm-2" for="username">Username:</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="username" id="username" placeholder="Enter username" required />
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" for="pwd">Password:</label>
					<div class="col-sm-10"> 
						<input type="password" class="form-control" name="pwd" id="pwd" placeholder="Enter password" required />
					</div>
				</div>
				<div class="form-group"> 
					<div class="col-sm-offset-2 col-sm-10">
						<div class="checkbox">
							<label><input type="checkbox"> Remember me</label>
						</div>
					</div>
				</div>
				<div class="form-group"> 
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-default">Submit</button>
					</div>
				</div>
			</form>
			
	
		</div>
	</body>
</html>