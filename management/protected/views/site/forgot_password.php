<div class="content">

	<form class="login-form" method="post">
		<h3 class="form-title font-green">ลืมรหัสผ่าน</h3>
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
			<label class="control-label visible-ie8 visible-ie9">Email</label>
			<input class="form-control form-control-solid placeholder-no-fix"
				type="text" autocomplete="off" placeholder="Email" value=""
				name="UsersLogin[email]" />
				
		</div>
		
				<p><font color="grey">ระบบจะทำการส่งรหัสผ่านใหม่ไปยังอีเมล์ที่ได้ลงทะเบียนไว้</font></p>
			

		
		<div class="form-actions">
			<button type="submit" class="btn green uppercase">Send</button>
								<?php echo CHtml::link("ยกเลิก",array('Site/LogOut'),array('class'=>'btn btn-default uppercase'));?>
			
		</div>
		
		<div class="create-account">
			<p>
			</p>
		</div>
	</form>
</div>
