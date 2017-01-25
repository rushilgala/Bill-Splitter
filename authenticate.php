<?php
function checkLogin($uname,$pname) {
	$db = new Database;
	$result = $db->prepare("SELECT * FROM users WHERE username=:uname");
	$result->bindValue(':uname', $uname, SQLITE3_TEXT);
	$results = $result->execute();
	while ($r = $results->fetchArray()) {
		$psalt = $r['password_salt'];
		$hpass = $r['password_hash'];
		$user_id = $r['user_id'];
	}
	$password_hash = $pname . $psalt;
	$hashedpassword = sha1($password_hash);
	if ($hashedpassword == $hpass) {
	//Match found so lets start a session
	session_start();
	$_SESSION['user_id'] = $user_id;
	header('location: dashboard.php');
	} else {
	//No match found so redirect back to the login page
	header('location: login.php');
	}
}

?>