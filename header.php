<?php
include_once('include.php');
?>
<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
				<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span> 
			</button>
			<a class="navbar-brand" href="index.php">Split My Way</a>
		</div>
		<div class="collapse navbar-collapse" id="myNavbar">
			<ul class="nav navbar-nav">
				<li class="active"><a href="index.php">Home</a></li>
				<?php if(loggedIn()) {
				echo '<li><a href="dashboard.php">Dashboard</a></li>';
				 } ?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<?php
					if (loggedIn()) {
					$id = $_SESSION['user_id'];
					$new_user = new user($id);
					$fname = $new_user->firstName();
				
				echo '<li class="dropdown">';
					echo '<a class="dropdown-toggle" data-toggle="dropdown">Hello, ' . h($fname);
						echo '<span class="caret"></span>';
					echo '</a>';
					echo '<ul class="dropdown-menu" role="menu" aria-labelledby="menu1">';
					echo '<li role="presentation"><a href="#" data-toggle="modal" data-target="#myModal">Settings</a></li>';
					
						echo '<li role="presentation" class="divider"></li>';
						echo '<li role="presentation"><a role="menuitem" href="logout.php">Logout</a></li>';
					echo '</ul>';
				echo '</div>';
				
				} else { 
				echo '<li><a href="registration.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>';
				echo '<li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>';
				 } ?>
			</ul>
		</div>
		
	</div>
</nav>
<?php include_once('settings.php');