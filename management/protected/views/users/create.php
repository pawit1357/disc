<?php

$titles = MTitle::model ()->findAll ();
$userRoles = UsersRole::model ()->findAll ();
?>
<form id="Form1" method="post" enctype="multipart/form-data"
	class="form-horizontal">

	<div class="<?php echo ConfigUtil::getPortletTheme(); ?>">
		<div class="portlet-title">
			<div class="caption">
				<?php echo  MenuUtil::getMenuName($_SERVER['REQUEST_URI'])?>
			</div>
			<div class="actions">
			<?php echo CHtml::link('ย้อนกลับ',array('Users/'),array('class'=>'btn btn-default btn-sm'));?>
			</div>
		</div>
		<div class="portlet-body form">
			<div class="form-body">
				<!-- BEGIN FORM-->

				<h4 class="form-section">&nbsp;ข้อมูลบัญชีผู้ใช้งาน</h4>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-4">กลุ่มผู้ใช้:<span
								class="required">*</span></label>
							<div class="col-md-6">
								<select class="form-control select2" name="UsersLogin[role_id]"
									id="role_id">
									<option value="0">-- โปรดเลือก --</option>
			<?php foreach($userRoles as $item) {?>
			<option value="<?php echo $item->ROLE_ID?>"><?php echo $item->ROLE_NAME?></option>
			<?php }?>
								</select>
							</div>
							<div id="divReq-role_id"></div>

						</div>
					</div>


				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-4">รหัสผู้ใช้:<span
								class="required">*</span></label>
							<div class="col-md-6">
								<input id="username" type="text" value="" class="form-control"
									name="UsersLogin[username]">
							</div>
							<div id="divReq-username"></div>
						</div>
					</div>
				</div>


				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-4">รหัสผ่าน:<span
								class="required">*</span></label>
							<div class="col-md-6">
								<input id="password" type="password" value=""
									class="form-control" name="UsersLogin[password]">
							</div>
							<div id="divReq-password"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-4">อีเมล์:<span
								class="required">*</span></label>
							<div class="col-md-6">
								<input id="email" type="text" value="" class="form-control"
									name="UsersLogin[email]">
							</div>
							<div id="divReq-email"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-4"> สถานะ:<span
								class="required"> * </span>
							</label>
							<div class="radio-list">
								<label class="radio-inline"> <input type="radio" id="status"
									name="UsersLogin[status]" value="A" checked="checked" /> Active
								</label> <label class="radio-inline"> <input type="radio"
									id="status" name="UsersLogin[status]" value="I" />InActive
								</label>

							</div>
						</div>
					</div>
				</div>
				<h4 class="form-section">&nbsp;ข้อมูลส่วนตัว</h4>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-4">คำนำหน้า:<span
								class="required">*</span></label>
							<div class="col-md-6">
								<select class="form-control select2" name="UsersLogin[title_id]"
									id="title_id">
									<option value="0">-- โปรดเลือก --</option>
			<?php foreach($titles as $item) {?>
			<option value="<?php echo $item->id?>"><?php echo $item->name?></option>
			<?php }?>
								</select>
							</div>
							<div id="divReq-title_id"></div>
						</div>
					</div>
				</div>
				<div class="row">

					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-4">ชื่อ:<span class="required">*</span></label>
							<div class="col-md-6">
								<input id="first_name" type="text" value="" class="form-control"
									name="UsersLogin[first_name]">
							</div>
							<div id="divReq-first_name"></div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-4">นามสกุล:<span
								class="required">*</span></label>
							<div class="col-md-6">
								<input id="last_name" type="text" value="" class="form-control"
									name="UsersLogin[last_name]">
							</div>
							<div id="divReq-last_name"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-4">เบอร์ติดต่อ:<span
								class="required">*</span></label>
							<div class="col-md-6">
								<input id="mobile_phone" type="text" value=""
									class="form-control" name="UsersLogin[mobile_phone]">
							</div>
							<div id="divReq-mobile_phone"></div>
						</div>
					</div>
				</div>


				<!-- END FORM-->

			</div>
			<div class="form-actions">
				<div class="row">
					<div class="col-md-9">
						<div class="row">
							<div class="col-md-offset-3 col-md-9">
								<button type="submit" class="btn green uppercase"><?php echo ConfigUtil::getBtnSaveButton();?></button>
								<?php echo CHtml::link(ConfigUtil::getBtnCancelButton(),array('Users/'),array('class'=>'btn btn-default uppercase'));?>
							</div>
						</div>
					</div>
					<div class="col-md-9"></div>
				</div>
			</div>
		</div>
	</div>



	<script
		src="<?php echo ConfigUtil::getAppName();?>/assets/global/plugins/jquery.min.js"
		type="text/javascript"></script>

	<script>
	var host = 'http://localhost:81';
    jQuery(document).ready(function () {

      	 $("#username").attr('maxlength','50');
    	 $("#password").attr('maxlength','8');
    	 $("#email").attr('maxlength','50');
    	 $("#first_name").attr('maxlength','20');
    	 $("#last_name").attr('maxlength','20');
    	 $("#mobile_phone").attr('maxlength','20');

    	 

    	 
        	$( "#Form1" ).submit(function( event ) {
            	
            	if($("#role_id").val() == "0"){
            		$("#role_id").closest('.form-group').addClass('has-error');
            		$("#divReq-role_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
            		$("#role_id").focus();
            		return false;
                }else{
                	$("#divReq-role_id").html('');
                	$("#role_id").closest('.form-group').removeClass('has-error');
            	}
            	
            	if($("#username").val().length == 0){
            		$("#username").closest('.form-group').addClass('has-error');
            		$("#divReq-username").html("<span id=\"name-error\" class=\"help-block help-block-error\">This field is required.</span>");
            		$("#username").focus();
            		return false;
                    }else{
                	$("#divReq-username").html('');
            		$("#username").closest('.form-group').removeClass('has-error');
            	}

            	if($("#password").val().length == 0){
            		$("#password").closest('.form-group').addClass('has-error');
            		$("#divReq-password").html("<span id=\"name-error\" class=\"help-block help-block-error\">This field is required.</span>");
            		$("#password").focus();
            		return false;
                    }else{
                	$("#divReq-password").html('');
            		$("#password").closest('.form-group').removeClass('has-error');
            	}
            	
            	if($("#email").val().length == 0){
            		$("#email").closest('.form-group').addClass('has-error');
            		$("#divReq-email").html("<span id=\"name-error\" class=\"help-block help-block-error\">This field is required.</span>");
            		$("#email").focus();
            		return false;
                    }else{
                	$("#divReq-email").html('');
            		$("#email").closest('.form-group').removeClass('has-error');
            	}
            	
            	if($("#title_id").val() == "0"){
            		$("#title_id").closest('.form-group').addClass('has-error');
            		$("#divReq-title_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
            		$("#title_id").focus();
                		return false;
                }else{
                	$("#divReq-title_id").html('');
                	$("#title_id").closest('.form-group').removeClass('has-error');
            	}



            	if($("#first_name").val().length ==0){
            		$("#first_name").closest('.form-group').addClass('has-error');
            		$("#divReq-first_name").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
            		$("#first_name").focus();
            		return false;
                }else{
                	$("#divReq-first_name").html('');
                	$("#first_name").closest('.form-group').removeClass('has-error');
            	}
            	
            	if($("#last_name").val().length ==0){
            		$("#last_name").closest('.form-group').addClass('has-error');
            		$("#divReq-last_name").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
            		$("#last_name").focus();
                		return false;
                }else{
                	$("#divReq-last_name").html('');
                	$("#last_name").closest('.form-group').removeClass('has-error');
            	}
            	
            	if($("#mobile_phone").val().length ==0){
            		$("#mobile_phone").closest('.form-group').addClass('has-error');
            		$("#divReq-mobile_phone").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
            		$("#mobile_phone").focus();
            		return false;
                }else{
                	$("#divReq-mobile_phone").html('');
                	$("#mobile_phone").closest('.form-group').removeClass('has-error');
            	} 
            	
            	if($("#department_id").val() =="0"){
            		$("#department_id").closest('.form-group').addClass('has-error');
            		$("#divReq-department_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
            		$("#department_id").focus();
            		return false;
                }else{
                	$("#divReq-department_id").html('');
                	$("#department_id").closest('.form-group').removeClass('has-error');
            	}
            	if($("#branch_group_id").val() == "0"){
            		$("#branch_group_id").closest('.form-group').addClass('has-error');
            		$("#divReq-branch_group_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
            		$("#branch_group_id").focus();
                		return false;
                }else{
                	$("#divReq-branch_group_id").html('');
                	$("#branch_group_id").closest('.form-group').removeClass('has-error');
            	}
            	this.submit();
        	});
        


    

    });
    

    
</script>

</form>