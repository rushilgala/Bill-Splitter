<?php
include_once "include.php";
date_default_timezone_set("Europe/London");
$database = new Database();
$username = $_POST["username"];
$password = $_POST["pwd"];
$email = $_POST["email"];
$firstName = $_POST["firstname"];
$lastName = $_POST["lastname"];
$salt = date('Y-m-d H:i:s');
$hash = $password . $salt;
$password_hashed = sha1($hash);
$check = $database->prepare("SELECT COUNT(*) from users WHERE username=:username");
$check->bindValue(':username', $username, SQLITE3_TEXT);
$result = $check->execute();
$num = $result->fetchArray()[0];
if ($num == 0) {
$query = $database->prepare("INSERT INTO users VALUES (NULL, :username,:password_hashed,:salt,:email,:firstName,:lastName)");
$query->bindValue(':username', $username, SQLITE3_TEXT);
$query->bindValue(':password_hashed', $password_hashed, SQLITE3_TEXT);
$query->bindValue(':salt', $salt, SQLITE3_TEXT);
$query->bindValue(':email', $email, SQLITE3_TEXT);
$query->bindValue(':firstName', $firstName, SQLITE3_TEXT);
$query->bindValue(':lastName', $lastName, SQLITE3_TEXT);
$results = $query->execute();
checkLogin($username,$password);
} else {
	header('location:registration.php');
}
?>