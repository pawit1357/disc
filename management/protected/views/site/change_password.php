<div>

	<form class="login-form" method="post">
		<h3 class="form-title font-green">Force Change Password</h3>
			<?php
			if (isset ( $_SESSION ['FAIL_MESSAGE'] )) {
				?>
				<div class="alert alert-danger">
					<button class="close" data-close="alert"></button>
					<span><?php echo $_SESSION ['FAIL_MESSAGE'];?></span>
				</div>
				<?php
				unset ( $_SESSION ['FAIL_MESSAGE'] );
			} else {
				?>
				<div class="alert alert-danger display-hide">
					<button class="close" data-close="alert"></button>
					<span> Enter any username and password. </span>
				</div>
			<?php }?>



		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label visible-ie8 visible-ie9">Username</label>
			<input class="form-control form-control-solid placeholder-no-fix"
				type="text" autocomplete="off" placeholder="Username" value="<?php echo UserLoginUtils::getUserInfo()->username;?>"
				name="UsersLogin[username]" readonly/>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">Password</label>
			<input class="form-control form-control-solid placeholder-no-fix"
				type="password" autocomplete="off" placeholder="Password"
				name="UsersLogin[password]" />
		</div>
		
		<div class="form-actions">
			<button type="submit" class="btn green uppercase">Save</button>
		</div>
		
		<div class="create-account">
			<p>
			</p>
		</div>
	</form>
</div>
