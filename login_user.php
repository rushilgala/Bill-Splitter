<?php
session_start();
include_once 'include.php';
if (loggedIn()) {
	header('location:dasboard.php');
}
$username = $_POST['username'];
$given_password = $_POST['pwd'];
checkLogin($username,$given_password);

?>