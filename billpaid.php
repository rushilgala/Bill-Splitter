<?php
// When a user has paid his bill...
// We need to take the bill id, group id, and user id
include_once 'include.php';
$db = new Database();
$bill_id = $_POST['bid'];
$group_id = $_POST['gid'];
$user_id = $_POST['uid'];
// Store amount owed in a temp variable
$tempAmountOwed = $db->querySingle("Select * from UserBill INNER JOIN bills ON UserBill.bill_id=bills.bill_id WHERE UserBill.user_id=${user_id} AND UserBill.group_id=${group_id} AND UserBill.bill_id=${bill_id}");
$tempOwed = array_values($tempAmountOwed)[0]['amount_due'];
$amount_owed = array_values($tempAmountOwed)[0]['amount_owed'];
// Change amount owed for UserBill to 0...
$db->exec("UPDATE UserBill SET amount_owed=0 WHERE user_id=${user_id} AND group_id=${group_id} AND bill_id=${bill_id}");
// If tempAmountOwed = bills.amount_owed, change amount_owed to 0 and bill_paid to 1
if ($tempOwed >= $amount_owed) {
	$db->exec("UPDATE bills SET amount_owed=0, bill_paid='1' WHERE bill_id=${bill_id}");
	$db->exec("UPDATE UserBill SET amount_due=0 WHERE user_id=${user_id} AND group_id=${group_id} AND bill_id=${bill_id}");
} else {
	// Else take away tempAmountOwed from bills.amount_owed
	$amount_owed = $amount_owed - $tempOwed;
	$db->exec("UPDATE bills SET amount_owed=${amount_owed} WHERE bill_id=${bill_id}");
	$db->exec("UPDATE UserBill SET amount_due=0 WHERE user_id=${user_id} AND group_id=${group_id} AND bill_id=${bill_id}");
}




?>