<?php
session_start();
include_once 'include.php';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Split My Way</title>
		<link rel="stylesheet" href="css/overall.css" type="text/css" charset="utf-8">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	</head>
	<body>
		<?php include_once('header.php');?>
		<div class="container">
			<h1>Split My Way</h1>
			<a href="registration.php" role="button" class="btn btn-default btn-lg">Sign up!</a>&nbsp;<a href="login.php" role="button" class="btn btn-default btn-lg">Login!</a>
			
			<div id="myCarousel" class="carousel slide" data-ride="carousel">
				<!-- Indicators -->
				<ol class="carousel-indicators">
					<li data-target="#myCarousel" data-slide-to="0" class="active"/>
					<li data-target="#myCarousel" data-slide-to="1"/>
					<li data-target="#myCarousel" data-slide-to="2"/>
					<li data-target="#myCarousel" data-slide-to="3"/>
				</ol>
				<!-- Wrapper for slides -->
				<div class="carousel-inner" role="listbox">
					<div class="item active">
						<img src="images/addbill.png" alt="Add Bill">
						<div class="carousel-caption">
							<h3>Add Bills!</h3>
							<p>Add your own bills to the groups selecting who else needs to pay!</p>
						</div>
					</div>

					<div class="item">
						<img src="images/addgroup.png" alt="Add Groups">
						<div class="carousel-caption">
							<h3>Add Groups!</h3>
							<p>Create groups to share bills between your household!</p>
						</div>
					</div>
					
					<div class="item">
						<img src="images/adduser.png" alt="Add Users">
						<div class="carousel-caption">
							<h3>Add Users!</h3>
							<p>Search for users to add to a group so that group can be selected quickly!</p>
						</div>
					</div>

					<div class="item">
						<img src="images/nav.png" alt="Easy Registration">
						<div class="carousel-caption">
							<h3>Easy Registration!</h3>
							<p>Click sign up and register for free, splitting your bills today!</p>
						</div>
					</div>
				</div>

				<!-- Left and right controls -->
				<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
					<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"/>
					<span class="sr-only">Previous</span>
				</a>
				<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
					<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"/>
					<span class="sr-only">Next</span>
				</a>
			</div>
		</div>
	</body>
</html>