<?php
function loggedIn() {
	//returns true if logged in
	if (isset($_SESSION['user_id'])) {
		return true;
	} else {
		return false;
	}
}

function accessResource($resource_user_id) {
	//returns true if user == resource user
	if ($_SESSION['user_id'] == $resource_user_id) {
		return true;
	} else {
		return false;
	}
}
?>