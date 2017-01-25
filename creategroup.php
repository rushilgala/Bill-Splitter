<?php
include_once 'include.php';
date_default_timezone_set("Europe/London");
$database = new Database();
$groupName = $_POST['groupName'];
$userID = $_POST['user-id'];
$date = date('Y-m-d H:i:s');
$query = $database->prepare("INSERT INTO groups VALUES (NULL, :groupname,:date,:userid)");
$query->bindValue(':groupname', $groupName, SQLITE3_TEXT);
$query->bindValue(':userid', $userID, SQLITE3_INTEGER);
$query->bindValue(':date', $date, SQLITE3_TEXT);
$results = $query->execute();
$groups = $database->query("SELECT * FROM groups WHERE date_created='${date}';");
while (($group = $groups->fetchArray())) {
	$groupID = $group['group_id'];
}
$updateTable = $database->prepare("INSERT INTO UserInGroup VALUES (:userid,:groupid)");
$updateTable->bindValue(':groupid', $groupID, SQLITE3_INTEGER);
$updateTable->bindValue(':userid', $userID, SQLITE3_INTEGER);

$exe = $updateTable->execute();
header("location:dashboard.php#group-id-".$groupID)
?>