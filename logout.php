<?php
session_start();
include_once 'include.php';
if (!loggedIn()) {
	header('location:login.php');
}
unset($_SESSION['user_id']);
header('location: index.php');
?>

