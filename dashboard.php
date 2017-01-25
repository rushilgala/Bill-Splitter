<?php
session_start();
include_once 'include.php';
if (!loggedIn()) {
	header('location:index.php');
}
$id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Dashboard</title>
		<link rel="stylesheet" href="css/overall.css" type="text/css" charset="utf-8">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/dashboard.js"></script>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	</head>
	<body>
		<?php include_once('header.php');?>
		<div class="container">
			<h1>Dashboard</h1>
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#overview">Overview</a></li>
				<?php 
					$db = new Database();
					$groups_id_prep = $db->prepare("SELECT group_id FROM UserInGroup WHERE user_id=:id");
					$groups_id_prep->bindValue(':id', $id, SQLITE3_INTEGER);
					$groups_id = $groups_id_prep->execute();
					while(($group_id = $groups_id->fetchArray())) {
						$groups_prep = $db->prepare("SELECT * FROM groups WHERE group_id=:group_id");
						$groups_prep->bindValue(':group_id', h($group_id['group_id']), SQLITE3_INTEGER);
						$groups = $groups_prep->execute();
						while($group = $groups->fetchArray()) {
							$id_group = $group['group_id'];
							$id_user = $id;
							$unpaid_bills = $db->querySingle("SELECT count(*) from bills INNER JOIN UserBill ON bills.bill_id=UserBill.bill_id WHERE UserBill.group_id=${id_group} AND UserBill.user_id=${id_user} AND UserBill.amount_due>0 AND bills.bill_paid='0'");
							$num = array_values($unpaid_bills)[0];
							echo '<li><a data-toggle="tab" href="#group-id-'.h($group['group_id']).'">'.h($group['group_name']).' <span class="badge">'.$num.'</span></a></li>';
						}
					}
				?>
				<li><a href="#" data-toggle="modal" data-target="#addnewgroup"><span class="glyphicon glyphicon-plus"></span>Add a new group</a></li>
			</ul>
			<div class="tab-content">
				<div id="overview" class="tab-pane fade in active">
					<h3>Overview</h3>
					<h4>Money you owe</h4>
					<?php
						$total_owe = $db->querySingle("SELECT total(amount_due) from UserBill WHERE user_id=${id}");
						$sum = array_values($total_owe)[0]/100;
						$format_sum = number_format($sum,2);
						if ($sum > 0) {
							echo '<div class="alert alert-danger text-center">You currently owe a total of <strong>&pound;'.$format_sum.'</strong> across your groups!</div>';
						} else {
							echo '<div class="alert alert-success text-center">You currently do not owe anyone anything!</div>';
						}
					?>
					<h4>Money you are owed</h4>
					<table class="table">
						<thread><tr><th>Bill name</th><th>What you are owed</th><th>Group Name</th></tr></thread>
						<tbody>
							<?php
								$money_owed = $db->query("SELECT * FROM bills WHERE user_id=${id} AND amount_owed>0");
								while ($money = $money_owed->fetchArray()) {
									$gid = $money['group_id'];
									$groupID = $db->querySingle("SELECT * FROM groups WHERE group_id=${gid}");
									$groupName = $groupID['group_name'];
									echo '<tr>';
									echo '<td>'.h($money['bill_name']).'</td>';
									echo '<td>&pound;'.number_format(h($money['amount_owed'])/100, 2).'</td>';
									echo '<td>'.h($groupName).'</td>';
									echo '</tr>';
								}
							
							?>
						</tbody>
					</table>
				</div>
				<!-- Add content dynamically -->
				<?php 
					while(($group_id = $groups_id->fetchArray())) {
						$groups_prep = $db->prepare("SELECT * FROM groups WHERE group_id=:group_id");
						$groups_prep->bindValue(':group_id', h($group_id['group_id']), SQLITE3_INTEGER);
						$groups = $groups_prep->execute();
						while($group = $groups->fetchArray()) {
							$groupid = h($group_id['group_id']);
							$user_id = $id;
							echo '<div id="group-id-'.h($group['group_id']).'"  class="tab-pane fade">
								<div class="row">
									<div class="col-sm-6">
										<h3>Pending payments</h3><table class="table"><tbody>';
											$payments_prep = $db->prepare("SELECT * from bills INNER JOIN UserBill ON bills.bill_id=UserBill.bill_id WHERE bills.group_id=:groupid AND UserBill.user_id=:userid AND UserBill.amount_due>0");
											$payments_prep->bindValue(':groupid', $group['group_id'], SQLITE3_INTEGER);
											$payments_prep->bindValue(':userid', $id, SQLITE3_INTEGER);
											$payments = $payments_prep->execute();
											$count_payments = $db->querySingle("SELECT count(*) from bills INNER JOIN UserBill ON bills.bill_id=UserBill.bill_id WHERE UserBill.group_id=${groupid} AND UserBill.user_id=${user_id} AND UserBill.amount_due>0 AND bills.bill_paid='0'");
											
											if(array_values($count_payments)[0]==0) {
												echo '<div class="alert alert-success">You currently do not owe this group anything!</div>';
											} else {
												echo '<thread><tr><th>Bill name</th><th>What you owe</th><th>Paid?</th></tr></thread>';
											}
											while ($payment = $payments->fetchArray()){
												echo '<tr id="bill-id-'.$payment["bill_id"].'" class="danger">';
												echo '<td>'.h($payment["bill_name"]).'</td>';
												echo '<td>&pound;'.number_format(h($payment["amount_due"])/100,2).'</td>';
												echo '<td><input type="checkbox" onClick="completeBill('.$payment["bill_id"].','.$payment["group_id"].','.$payment["user_id"].');" value="'.$payment["bill_id"].'" name="complete"></td>';
												echo '</tr>';
											}
										echo '</tbody></table>';
										echo '<h3 class="current_bills" data-toggle="collapse" data-target="#billsgroup-'.h($group['group_id']).'">Current bills <i id="glyphicon-'.h($group['group_id']).'" class="glyphicon glyphicon-minus"></i></h3>
										<div id="billsgroup-'.h($group['group_id']).'" class="collapse in"><table class="table"><thread><tr><th>Payment owed</th><th>Bill name</th><th>Paid by</th></tr></thread><tbody>';
											$bills_prep = $db->prepare("SELECT * FROM bills WHERE group_id=:group_id AND bill_paid='0'");
											$bills_prep->bindValue(':group_id', h($group_id['group_id']), SQLITE3_INTEGER);
											$bills = $bills_prep->execute();
											while($bill = $bills->fetchArray()) {
												$user = new user($bill['user_id']);
												$name = $user->fullName();
												echo '<tr><td>&pound;'.number_format((h($bill['amount_owed'])/100),2) .'</td><td>'.h($bill['bill_name']).'</td><td>'.h($name).'</td></tr>';
											}
										echo '</tbody></table></div>
										<script type = "text/javascript">
										   $("#billsgroup-'.h($group['group_id']).'").on("hidden.bs.collapse", function () {
											   $("#glyphicon-'.h($group['group_id']).'").removeClass("glyphicon-minus").addClass("glyphicon-plus");
											});

											$("#billsgroup-'.h($group['group_id']).'").on("shown.bs.collapse", function () {
											   $("#glyphicon-'.h($group['group_id']).'").removeClass("glyphicon-plus").addClass("glyphicon-minus");
											});
										</script> 
										<h3 class="history" value="hide" data-toggle="collapse" data-target="#historygroup-'.h($group['group_id']).'">History <i id="history-'.h($group['group_id']).'" class="glyphicon glyphicon-minus"></i></h3>
										<div id="historygroup-'.h($group['group_id']).'" class="collapse in"><table class="table"><tbody><thread><tr><th>Total payment</th><th>Bill name</th><th>Originally paid by</th></tr></thread>';
										$history_prep = $db->prepare("SELECT * FROM bills WHERE group_id=:group_id AND bill_paid='1'");
										$history_prep->bindValue(':group_id', h($group_id['group_id']), SQLITE3_INTEGER);
										$history = $history_prep->execute();
										while($h = $history->fetchArray()) {
											$user = new user($h['user_id']);
											$name = $user->fullName();
											echo '<tr><td>&pound;'.number_format((h($h['amount_paid'])/100),2) .'</td><td>'.h($h['bill_name']).'</td><td>'.h($name).'</td></tr>';
										}
										echo '</tbody></table></div>
										<script type = "text/javascript">
										   $("#historygroup-'.h($group['group_id']).'").on("hidden.bs.collapse", function () {
											   $("#history-'.h($group['group_id']).'").removeClass("glyphicon-minus").addClass("glyphicon-plus");
											});

											$("#historygroup-'.h($group['group_id']).'").on("shown.bs.collapse", function () {
											   $("#history-'.h($group['group_id']).'").removeClass("glyphicon-plus").addClass("glyphicon-minus");
											});
										</script> 
									</div>
									<div class="col-sm-6">
										<br>
										<a href="#group-id-'.h($group['group_id']).'" class="btn btn-default btn-lg" role="button" data-toggle="modal" data-target="#addbill-'.h($group['group_id']).'">
											<span class="glyphicon glyphicon-plus"></span>Add a new bill/expense
										</a>
										<h3>Group</h3>
										<table class="table"><tbody>';
							$users_prep = $db->prepare("SELECT * FROM users INNER JOIN UserInGroup on UserInGroup.user_id=users.user_id WHERE UserInGroup.group_id=:group_id");
							$users_prep->bindValue(':group_id', h($group_id['group_id']), SQLITE3_INTEGER);
							$users = $users_prep->execute();
							while ($user = $users->fetchArray()) {
								echo '<tr>';
								echo '<td><div class="col-xs-12 col-md-12 col-lg-12 vcenter">'.h($user['FirstName']).' '.h($user['LastName']).' ('.h($user['username']).')</div></td>';
								echo '</tr>';
							}
							echo '</tbody></table><a href="#group-id-'.h($group['group_id']).'" class="btn btn-link" role="button" data-toggle="modal" data-target="#adduser-'.h($group['group_id']).'"><span class="glyphicon glyphicon-plus"></span>Add another group member</a></div></div></div>';
							echo '<div class="modal fade" id="adduser-'.h($group['group_id']).'" tabindex="-1" role="dialog" aria-labelledby="add new user" aria-hidden="true">
										<div class="modal-dialog modal-lg">
											<div class="modal-content">
												<!-- Modal Header -->
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal">
														<span aria-hidden="true">&times;</span>
														<span class="sr-only">Close</span>
													</button>
													<h4 class="modal-title" id="myModalLabel">Add new user</h4>
												</div>
												<!-- Modal Body -->
												<div class="modal-body">
													<form class="form-horizontal" role="form" method="post" action="adduser.php">
														<div class="form-group">
															<label  class="col-sm-2 control-label" for="username">Their Username:</label>
															<div class="col-sm-10">
																<input type="text" class="form-control" name="username" id="username" placeholder="Enter Username" required/>
															</div>
														</div>
														<div class="form-group">
														<label  class="col-sm-2 control-label" for="firstname">Their First Name:</label>
														<div class="col-sm-10">
															<input type="text" class="form-control" name="firstname" id="firstname" placeholder="Enter First Name"/>
														</div>
													</div>
													<div class="form-group">
														<label  class="col-sm-2 control-label" for="lastname">Their Last Name:</label>
														<div class="col-sm-10">
															<input type="text" class="form-control" name="lastname" id="lastname" placeholder="Enter Last Name"/>
														</div>
													</div>
													<div class="form-group">
														<label  class="col-sm-2 control-label" for="email">Their Email:</label>
														<div class="col-sm-10">
															<input type="text" class="form-control" name="email" id="email" placeholder="Enter Email"/>
														</div>
													</div>
													<div class="form-group">
															<input type="hidden" class="form-control" name="group-id" id="group-id-'.h($group['group_id']).'" value="'.h($group['group_id']).'"/>
													</div>
													<div class="form-group">
														<div class="col-sm-offset-2 col-sm-10">
															<button type="submit" class="btn btn-default">Add user to group</button>
														</div>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>';
							
							echo '<div class="modal fade" id="addbill-'.h($group['group_id']).'" tabindex="-1" role="dialog" aria-labelledby="add new bill" aria-hidden="true">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<!-- Modal Header -->
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal">
												<span aria-hidden="true">&times;</span>
												<span class="sr-only">Close</span>
											</button>
											<h4 class="modal-title" id="myModalLabel">Add new bill</h4>
										</div>
										<!-- Modal Body -->
										<div class="modal-body">
											<form class="form-horizontal" id="bill-form-'.h($group['group_id']).'" role="form" method="post" action="createbill.php">
												<div class="row">
													<div class="col-sm-6">
														<h4 class="modal-title" id="myModalLabel">Details</h4>
														<div class="form-group">
															<label  class="col-sm-4 control-label" for="billname">Bill Name:</label>
															<div class="col-sm-8">
																<input type="text" class="form-control" name="billname" id="billname" placeholder="What is the bill for?" required/>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-4 control-label" for="amount">Amount:</label>
															<label class="col-sm-1 control-label text-right" for="amount">&pound;</label>
															<div class="col-sm-4 pull-left">
																<input type="number" min="0" step="0.01" class="form-control" name="amount_paid" id="amount-'.h($group['group_id']).'" placeholder="0.00" required/>
															</div>
														</div>
														<div class="form-group">
															<label  class="col-sm-4 control-label" for="payer">Payer:</label>
															<div class="col-sm-8">
																<select class="form-control" id="payer" name="payer">';
																	while ($user = $users->fetchArray()) {
																		echo '<option value="'.h($user['user_id']).'">'.h($user['FirstName']).' '.h($user['LastName']).'</option>';
																	}
															echo '</select>
															</div>
														</div>
													</div>
													<div class="col-sm-6">
														<div class="checkbox pull-right"><label><input type="checkbox" name="split" value="1" id="checkme-'.h($group['group_id']).'"><h5 class="vcenter">Split Equally?</h5></label></div>
														<h4 class="modal-title" id="myModalLabel">Split with who?</h4>
														<table class="table">
															<tbody>
																<!-- For every user... -->';
																while ($user = $users->fetchArray()) {
																		echo '<tr><td>';
																		echo '<div class="col-xs-12 col-md-12 col-lg-12 vcenter">'.h($user['FirstName']).' '.h($user['LastName']).'</div></td>'; // Full name
																		echo '<td><div id="splitequal-'.h($group['group_id']).'-'.h($user['user_id']).'" class="splitequal-'.h($group['group_id']).'" class="col-xs-6 col-md-12 col-lg-10 vcenter"><input type="number" min="0" step="0.01" class="form-control amount-'.h($group['group_id']).'" name="amount[][owe]" id="amount" placeholder="0.00"/><input type="hidden" name="user[][user_id]" value="'.h($user['user_id']).'" /></div></td>'; // Amount due only sho if not split equall -->
																		echo '<td><div class="checkbox col-xs-1 col-md-1 col-lg-1 vcenter"><input type="checkbox" id="checkme-'.h($group['group_id']).'-'.h($user['user_id']).'" value="'.h($user['user_id']).'" name="checkbox-'.h($group['group_id']).'[]" checked></div>'; //<!-- Include or not - subset...-->
																		echo '</td></tr>';
																		echo '<script>
																		$("#checkme-'.h($group['group_id']).'-'.h($user['user_id']).'").change(function() {
																			if( !this.checked) {
																				$("#splitequal-'.h($group['group_id']).'-'.h($user['user_id']).'").fadeOut("slow");
																			} else {
																				$("#splitequal-'.h($group['group_id']).'-'.h($user['user_id']).'").fadeIn("slow");
																			}
																		});';
																		echo '</script>';
																}
															echo '</tbody>
														</table>
														<div id="numCheck-'.h($group['group_id']).'"><div class="alert alert-danger">You must select at least <strong>one</strong> user</div></div>
														<div id="splitCheck-'.h($group['group_id']).'"><div class="alert alert-danger">Your unequal splits <strong>do not</strong> equal the total amount!</div></div>
														<script>
															$("#checkme-'.h($group['group_id']).'").change(function() {
																if( this.checked) {
																	$(".splitequal-'.h($group['group_id']).'").fadeOut("slow");
																} else {
																	$(".splitequal-'.h($group['group_id']).'").fadeIn("slow");
																}
															});
														</script>
													</div>
												</div>
												<div class="form-group">
														<div class="col-sm-offset-2 col-sm-10">
															<input type="hidden" class="form-control" name="group-id" id="group-id" value="'.h($group['group_id']).'"/>
															<button type="submit" class="btn btn-default">Split My Way!</button>
														</div>
												</div>
											</form>';
											$groupingId = h($group['group_id']);
											
											?>
											<script>
												$(document).ready( function() {
													$("#numCheck-<?php echo $groupingId;?>").hide();
													$("#splitCheck-<?php echo $groupingId;?>").hide();
													$("#bill-form-<?php echo $groupingId;?>").submit( function() {
															var num = ($('[name="checkbox-' + <?php echo $groupingId;?> + '[]"]:checked').length);
															var elem = document.getElementById('checkme-' + <?php echo $groupingId;?>);
															var amount = document.getElementById('amount-' + <?php echo $groupingId;?>);
															var amountByGroup = document.getElementsByClassName('amount-' + <?php echo $groupingId;?>);
															$("#numCheck-<?php echo $groupingId;?>").hide();
															$("#splitCheck-<?php echo $groupingId;?>").hide();
															var error_free=true;
															if (num==0) {
																
																$("#numCheck-<?php echo $groupingId;?>").show();
																error_free=false;
															}
															if (!(elem.checked)) {
																var i;
																var total = 0;
																for (i = 0; i < amountByGroup.length; i++){
																	if (amountByGroup[i].value != null) {
																		total = +total + +amountByGroup[i].value;
																	}
																}
																if (total != amount.value) {
																	
																	error_free=false;
																	$("#splitCheck-<?php echo $groupingId;?>").show();
																}
																
															}
																if (error_free) {
																return true;
															} else {
																return false;
															}
													});
												});
											</script>
										<?php echo '</div>
									</div>
								</div>
							</div>';
						}
					}
					?>
				
				<!--- add new group -->
				<div id="addgroup" class="tab-pane fade">
				</div>
			</div>
		</div>
		
		
		
		<div class="modal fade" id="addnewgroup" tabindex="-1" role="dialog" aria-labelledby="add new group" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<!-- Modal Header -->
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true">&times;</span>
							<span class="sr-only">Close</span>
						</button>
						<h4 class="modal-title" id="myModalLabel">Add new group</h4>
					</div>
					<!-- Modal Body -->
					<div class="modal-body">
						<form class="form-horizontal" role="form" method="post" action="creategroup.php">
							<div class="form-group">
								<label  class="col-sm-2 control-label" for="inputGroup">Group Name</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="groupName" id="inputGroup" placeholder="Enter Group Name"/>
								</div>
							</div>
							<div class="form-group">
								<input type="hidden" class="form-control" name="user-id" id="user-id" value="<?php echo $id;?>"/>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button type="submit" class="btn btn-default">Add group</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>