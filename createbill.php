<?php
include_once('include.php');
$db = new Database();
$billname = $_POST['billname'];
$group = $_POST['group-id'];
$amountpaid = $_POST['amount_paid'];
$amountpaid = $amountpaid * 100;
$payer = $_POST['payer'];
$checkbox = 'checkbox-'.$group;
$checked_arr = $_POST[$checkbox];
$count = count($checked_arr);
$billpaid = 0;
$query = $db->prepare("INSERT INTO bills VALUES (NULL, :billname,:amountpaid,:amount_owed,:billpaid,:user_id,:group_id)");
$query->bindValue(':billname', $billname, SQLITE3_TEXT);
$query->bindValue(':amountpaid', $amountpaid, SQLITE3_INTEGER);
$query->bindValue(':amount_owed', $amountpaid, SQLITE3_INTEGER);
$query->bindValue(':billpaid', $billpaid, SQLITE3_TEXT);
$query->bindValue(':user_id', $payer, SQLITE3_INTEGER);
$query->bindValue(':group_id', $group, SQLITE3_INTEGER);
$results = $query->execute();
$bill = $db->querySingle("SELECT * from bills ORDER BY bill_id DESC LIMIT 1");
$billID = $bill['bill_id'];
// Find out if its being split equally
// 0 if not splitting equally,
// 1 if splitting equally
if (isset($_POST['split'])) {
	$amount_due = $amountpaid / $count;
	for($x = 0; $x < $count; $x++) {
		if ($checked_arr[$x] == $payer) {
			$amount_left = $amountpaid - $amount_due;
			if ($amount_left <= 0) {
				$updateBill = $db->exec("UPDATE bills SET amount_owed=${amount_left}, bill_paid='1' WHERE bill_id=${billID}");	
			} else {
				$updateBill = $db->exec("UPDATE bills SET amount_owed=${amount_left} WHERE bill_id=${billID}");
			}
		} else {
			// Add new UserBill for user,group,bill_id,amount_due
			$addBill = $db->prepare("INSERT INTO UserBill VALUES (:userid,:groupid,:billid,:amountdue)");
			$addBill->bindValue(':userid', $checked_arr[$x], SQLITE3_INTEGER);
			$addBill->bindValue(':groupid', $group, SQLITE3_INTEGER);
			$addBill->bindValue(':billid', $billID, SQLITE3_INTEGER);
			$addBill->bindValue(':amountdue', $amount_due, SQLITE3_INTEGER);
			$result = $addBill->execute();
		}
	}
} else {
	$amount_arr = $_POST['amount'];
	$user_arr = $_POST['user'];
	$amountCheck = count($amount_arr);
	for ($i = 0; $i < $amountCheck; $i++) {
		if ($amount_arr[$i]['owe']!=null) {
			$amount_due = $amount_arr[$i]['owe']*100;
			$user_id = $user_arr[$i]['user_id'];
			
			if ($user_id == $payer) {
				$amount_left = $amountpaid - $amount_due;
				if ($amount_left <= 0) {
					$updateBill = $db->exec("UPDATE bills SET amount_owed=${amount_left}, bill_paid='1' WHERE bill_id=${billID}");	
				} else {
					$updateBill = $db->exec("UPDATE bills SET amount_owed=${amount_left} WHERE bill_id=${billID}");
				}
			} else {
				$addNewBill = $db->prepare("INSERT INTO UserBill VALUES (:userid,:groupid,:billid,:amountdue)");
				$addNewBill->bindValue(':userid', $user_id, SQLITE3_INTEGER);
				$addNewBill->bindValue(':groupid', $group, SQLITE3_INTEGER);
				$addNewBill->bindValue(':billid', $billID, SQLITE3_INTEGER);
				$addNewBill->bindValue(':amountdue', $amount_due, SQLITE3_INTEGER);
				$result = $addNewBill->execute();
			}
		}	
	}
	
}



header('location:dashboard.php')


?>