<?php
include_once 'include.php';
$database = new Database();
$username = $_POST['username'];
$firstName = $_POST['firstname'];
$lastName = $_POST['lastname'];
$email = $_POST['email'];
$group_id = $_POST['group-id'];
$users = $database->prepare("SELECT * FROM users WHERE username=:username AND (FirstName=:firstname OR LastName=:lastname OR email=:email) LIMIT 1;");
$users->bindValue(':username', $username, SQLITE3_TEXT);
$users->bindValue(':email', $email, SQLITE3_TEXT);
$users->bindValue(':firstname', $firstName, SQLITE3_TEXT);
$users->bindValue(':lastname', $lastName, SQLITE3_TEXT);
$results = $users->execute();
while (($user = $results->fetchArray())) {
	$user_id = $user['user_id'];
}
$updateTable = $database->prepare("INSERT INTO UserInGroup VALUES (:userid,:groupid)");
$updateTable->bindValue(':groupid', $group_id, SQLITE3_INTEGER);
$updateTable->bindValue(':userid', $user_id, SQLITE3_INTEGER);

$exe = $updateTable->execute();
header('location:dashboard.php')
?>