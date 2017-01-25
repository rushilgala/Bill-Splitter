<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Settings</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" role="form" method="post">
					<div class="form-group">
						<label  class="col-sm-2 control-label" for="currentpwd">Current Password:</label>
						<div class="col-sm-10">
							<input type="password" class="form-control" name="currentpassword" id="currentpwd" placeholder="Enter Current Password"/>
						</div>
					</div>
					<h4 data-toggle="collapse" data-target="#password_change">Change Password [show/hide]</h4>
					<div id="password_change" class="collapse">
						<div class="form-group">
							<label  class="col-sm-2 control-label" for="newpwd">New Password:</label>
							<div class="col-sm-10">
								<input type="password" class="form-control" name="newpwd" id="newpwd" placeholder="Enter New Password"/>
							</div>
						</div>
						<div class="form-group">
							<label  class="col-sm-2 control-label" for="reppwd">Repeat New Password:</label>
							<div class="col-sm-10">
								<input type="password" class="form-control" name="reppwd" id="reppwd" placeholder="Repeat New Password"/>
							</div>
						</div>
					</div>
					<h4 data-toggle="collapse" data-target="#email_change">Change Email [show/hide]</h4>
					<div id="email_change" class="collapse">
						<div class="form-group">
							<label  class="col-sm-2 control-label" for="newemail">New Email:</label>
							<div class="col-sm-10">
								<input type="email" class="form-control" name="newemail" id="newemail" placeholder="Enter New Email"/>
							</div>
						</div>
					</div>
					<h4 data-toggle="collapse" data-target="#detail_change">Change Details [show/hide]</h4>
					<div id="detail_change" class="collapse">
						<div class="form-group">
							<label class="control-label col-sm-2" for="firstname">First Name:</label>
							<div class="col-sm-10"> 
								<input type="text" class="form-control" name="firstname" id="firstname" placeholder="Enter new first name">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2" for="lastname">Last Name:</label>
							<div class="col-sm-10"> 
								<input type="text" class="form-control" name="lastname" id="lastname" placeholder="Enter new last name">
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-default">Submit Changes</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>