<form id="Form1" method="post" enctype="multipart/form-data"
	class="form-horizontal">

	<div class="portlet light">
		<div class="portlet-title">
			<div class="caption">
				<?php echo  MenuUtil::getMenuName($_SERVER['REQUEST_URI'])?>
			</div>
			<div class="actions">
						<?php echo CHtml::link('ย้อนกลับ',array('UsersRole/'),array('class'=>'btn btn-default btn-sm'));?>
			
			</div>
		</div>
		<div class="portlet-body form">
			<div class="form-body">
				<!-- BEGIN FORM-->
				<div class="row">
					<div class="col-md-9">
						<div class="form-group">
							<label class="control-label col-md-3">รหัส:<span class="required">*</span></label>
							<div class="col-md-6">
								<input id="ROLE_ID" type="text"
									value="<?php echo UsersRole::getMax();?>"
									class="grpOfInt form-control" name="UsersRole[ROLE_ID]"
									readonly>
							</div>
							<div id="divReq-role_id"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-9">
						<div class="form-group">
							<label class="control-label col-md-3">ชื่อกลุ่มผู้ใช้:<span
								class="required">*</span></label>
							<div class="col-md-6">
								<input id="ROLE_NAME" type="text" value="" class="form-control"
									name="UsersRole[ROLE_NAME]">
							</div>
							<div id="divReq-role_name"></div>
						</div>
					</div>
				</div>

				<h4>กำหนดการเข้าถึงเมนู</h4>
				<table class="table table-striped table-hover table-bordered">
					<thead>
						<tr>
							<th>ชื่อเมนู</th>
							<th>ใช้งานเมนู</th>
							<th>บันทึกข้อมูล</th>
							<th>แก้ไขข้อมูล</th>
							<th>ลบข้อมูล</th>
						</tr>
					</thead>

					<tbody>
	<?php
	
	$counter = 1;
	$dataProvider = Menu::model ()->search ();
	$index = 1;
	foreach ( $dataProvider->data as $item ) {
		
		if ($item->PREVIOUS_MENU_ID == - 1) {
			?>
					<tr>

							<td class="center"><i class="fa fa-th"></i>&nbsp;&nbsp;<?php echo $item->MENU_NAME?></td>
							<td><input type="checkbox" value="<?php echo $item->MENU_ID?>"
								name="listOfActive[<?php echo $item->MENU_ID?>]" /></td>
							<td><input type="checkbox" value="<?php echo $item->MENU_ID?>"
								name="listOfCreate[<?php echo $item->MENU_ID?>]"
								<?php echo (($item->URL_NAVIGATE == "#" || $item->URL_NAVIGATE ==null))? 'disabled="disabled"':'' ?> /></td>
							<td><input type="checkbox" value="<?php echo $item->MENU_ID?>"
								name="listOfEdit[<?php echo $item->MENU_ID?>]"
								<?php echo (($item->URL_NAVIGATE == "#" || $item->URL_NAVIGATE ==null))? 'disabled="disabled"':'' ?> /></td>
							<td><input type="checkbox" value="<?php echo $item->MENU_ID?>"
								name="listOfDelete[<?php echo $item->MENU_ID?>]"
								<?php echo (($item->URL_NAVIGATE == "#" || $item->URL_NAVIGATE ==null))? 'disabled="disabled"':'' ?> /></td>
						</tr>
				<?php
			foreach ( $dataProvider->data as $item2 ) {
				if ($item2->PREVIOUS_MENU_ID == $item->MENU_ID) {
					
					?>
						<tr>
							
							<?php if ($item2->URL_NAVIGATE == "#") {?>
							<td class="center"><i class="fa fa-angle-double-right"></i>&nbsp;&nbsp;<?php echo $item2->MENU_NAME?></td>
							
							<?php }else{?>
							<td class="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i
								class="fa fa-angle-double-right"></i>&nbsp;&nbsp;<?php echo $item2->MENU_NAME?></td>
<?php }?>
							<td><input type="checkbox" value="<?php echo $item2->MENU_ID?>"
								name="listOfActive[<?php echo $item2->MENU_ID?>]" /></td>
							<td><input type="checkbox" value="<?php echo $item2->MENU_ID?>"
								name="listOfCreate[<?php echo $item2->MENU_ID?>]"
								<?php echo (($item2->URL_NAVIGATE == "#" || $item2->URL_NAVIGATE ==null))? 'disabled="disabled"':'' ?> /></td>
							<td><input type="checkbox" value="<?php echo $item2->MENU_ID?>"
								name="listOfEdit[<?php echo $item2->MENU_ID?>]"
								<?php echo (($item2->URL_NAVIGATE == "#" || $item2->URL_NAVIGATE ==null))? 'disabled="disabled"':'' ?> /></td>
							<td><input type="checkbox" value="<?php echo $item2->MENU_ID?>"
								name="listOfDelete[<?php echo $item2->MENU_ID?>]"
								<?php echo (($item2->URL_NAVIGATE == "#" || $item2->URL_NAVIGATE ==null))? 'disabled="disabled"':'' ?> /></td>
						</tr>
												<?php
					if ($item2->URL_NAVIGATE == "#") {
						foreach ( $dataProvider->data as $item3 ) {
							
							if ($item3->PREVIOUS_MENU_ID == $item2->MENU_ID) {
								?>
						<tr>

							<td class="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i
								class="fa fa-angle-double-right"></i><i
								class="fa fa-angle-double-right"></i><?php echo $item3->MENU_NAME?></td>
							<td><input type="checkbox" value="<?php echo $item3->MENU_ID?>"
								name="listOfActive[<?php echo $item3->MENU_ID?>]" /></td>
							<td><input type="checkbox" value="<?php echo $item3->MENU_ID?>"
								name="listOfCreate[<?php echo $item3->MENU_ID?>]"
								<?php echo (($item3->URL_NAVIGATE == "#" || $item3->URL_NAVIGATE ==null))? 'disabled="disabled"':'' ?> /></td>
							<td><input type="checkbox" value="<?php echo $item3->MENU_ID?>"
								name="listOfEdit[<?php echo $item3->MENU_ID?>]"
								<?php echo (($item3->URL_NAVIGATE == "#" || $item3->URL_NAVIGATE ==null))? 'disabled="disabled"':'' ?> /></td>
							<td><input type="checkbox" value="<?php echo $item3->MENU_ID?>"
								name="listOfDelete[<?php echo $item3->MENU_ID?>]"
								<?php echo (($item3->URL_NAVIGATE == "#" || $item3->URL_NAVIGATE ==null))? 'disabled="disabled"':'' ?> /></td>
						</tr>
						<?php
								if ($item3->URL_NAVIGATE == "#") {
									foreach ( $dataProvider->data as $item4 ) {
										
										if ($item4->PREVIOUS_MENU_ID == $item3->MENU_ID) {
											
											?>
												<tr class="line-<?php echo $counter%2 == 0 ? '1' : '2'?>">

							<td class="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i
								class="fa fa-angle-double-right"></i><i
								class="fa fa-angle-double-right"></i><?php echo $item4->MENU_NAME?></td>
							<td><input type="checkbox" value="<?php echo $item4->MENU_ID?>"
								name="listOfActive[<?php echo $item4->MENU_ID?>]" /></td>
							<td><input type="checkbox" value="<?php echo $item4->MENU_ID?>"
								name="listOfCreate[<?php echo $item4->MENU_ID?>]"
								<?php echo (($item4->URL_NAVIGATE == "#" || $item4->URL_NAVIGATE ==null))? 'disabled="disabled"':'' ?> /></td>
							<td><input type="checkbox" value="<?php echo $item4->MENU_ID?>"
								name="listOfEdit[<?php echo $item4->MENU_ID?>]"
								<?php echo (($item4->URL_NAVIGATE == "#" || $item4->URL_NAVIGATE ==null))? 'disabled="disabled"':'' ?> /></td>
							<td><input type="checkbox" value="<?php echo $item3->MENU_ID?>"
								name="listOfDelete[<?php echo $item4->MENU_ID?>]"
								<?php echo (($item4->URL_NAVIGATE == "#" || $item4->URL_NAVIGATE ==null))? 'disabled="disabled"':'' ?> /></td>
						</tr>
																									<?php
										}
									}
								}
								?>
						
																			<?php
							}
						}
					}
				}
			}
			$index ++;
		}
	}
	?>
						</tbody>
				</table>

				<!-- END FORM-->
				<div class="form-actions">
					<div class="row">
						<div class="col-md-9">
							<div class="row">
								<div class="col-md-offset-3 col-md-9">
									<button type="submit" class="btn green uppercase"><?php echo ConfigUtil::getBtnSaveButton();?></button>
								<?php echo CHtml::link(ConfigUtil::getBtnCancelButton(),array('UsersRole/'),array('class'=>'btn btn-default uppercase'));?>
					</div>
							</div>
						</div>
						<div class="col-md-9"></div>
					</div>
				</div>
			</div>
		</div>
	</div>



	<script
		src="<?php echo ConfigUtil::getAppName();?>/assets/global/plugins/jquery.min.js"
		type="text/javascript"></script>

	<script>
	var host = 'http://localhost:81/mu_rad';
    jQuery(document).ready(function () {
    	 $("#ROLE_ID").attr('maxlength','3');
      	 $("#ROLE_NAME").attr('maxlength','50');
    

 	    $('.grpOfInt').keypress(function (event) {
            return isNumber(event);
        });
        
        	$( "#Form1" ).submit(function( event ) {

            	if($("#ROLE_ID").val().length==0){
            		$("#ROLE_ID").closest('.form-group').addClass('has-error');
            		$("#divReq-role_id").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
            		$("#ROLE_ID").focus();
            		return false;
                }else{
                	$("#divReq-role_id").html('');
                	$("#ROLE_ID").closest('.form-group').removeClass('has-error');
            	}
            	
            	if($("#ROLE_NAME").val().length==0){
            		$("#ROLE_NAME").closest('.form-group').addClass('has-error');
            		$("#divReq-role_name").html("<span id=\"id-error\" class=\"help-block help-block-error\">This field is required.</span>");
            		$("#ROLE_NAME").focus();
            		return false;
                }else{
                	$("#divReq-role_name").html('');
                	$("#ROLE_NAME").closest('.form-group').removeClass('has-error');
            	}
            	
            	

            	this.submit();
        	});
    });
    
    function initRayGenerator(){
    	$.ajax({
		     url: host+"/index.php/AjaxRequest/GetRayGenerator",
		     type: "GET",
		     dataType: "json",
		     success: function (json) {
		            $('#ray_generator_id').empty();
		            $('#ray_generator_id').append($('<option>').text("Select"));
		            $.each(json, function(i, obj){
		                    $('#ray_generator_id').append($('<option>').text(obj.name).attr('value', obj.id));
		            });
     	
		     },
		     error: function (xhr, ajaxOptions, thrownError) {
				alert('ERROR');
		     }
    	});
    }

    function initCodeUsage(){
    	$.ajax({
		     url: host+"/index.php/AjaxRequest/GetCodeUsage",
		     type: "GET",
		     dataType: "json",
		     success: function (json) {
		            $('#code_usage_id').empty();
		            $('#code_usage_id').append($('<option>').text("Select"));
		            $.each(json, function(i, obj){
		                    $('#code_usage_id').append($('<option>').text(obj.name).attr('value', obj.id));
		            });
     	
		     },
		     error: function (xhr, ajaxOptions, thrownError) {
				alert('ERROR');
		     }
    	});
    }
    function initManufacturer(){
    	$.ajax({
		     url: host+"/index.php/AjaxRequest/GetManufacturer",
		     type: "GET",
		     dataType: "json",
		     success: function (json) {
		            $('#maufacturer_id').empty();
		            $('#maufacturer_id').append($('<option>').text("Select"));
		            $.each(json, function(i, obj){
		                    $('#maufacturer_id').append($('<option>').text(obj.name).attr('value', obj.id));
		            });
     	
		     },
		     error: function (xhr, ajaxOptions, thrownError) {
				alert('ERROR');
		     }
    	});
    }
    function initUseType(){
    	$.ajax({
		     url: host+"/index.php/AjaxRequest/GetUseType",
		     type: "GET",
		     dataType: "json",
		     success: function (json) {
		            $('#use_type_id').empty();
		            $('#use_type_id').append($('<option>').text("Select"));
		            $.each(json, function(i, obj){
		                    $('#use_type_id').append($('<option>').text(obj.name).attr('value', obj.id));
		            });
     	
		     },
		     error: function (xhr, ajaxOptions, thrownError) {
				alert('ERROR');
		     }
    	});
    }
    function initPower(){
    	$.ajax({
		     url: host+"/index.php/AjaxRequest/GetPower",
		     type: "GET",
		     dataType: "json",
		     success: function (json) {
		            $('#power_id').empty();
		            $('#power_id').append($('<option>').text("Select"));
		            $.each(json, function(i, obj){
		                    $('#power_id').append($('<option>').text(obj.name).attr('value', obj.id));
		            });
     	
		     },
		     error: function (xhr, ajaxOptions, thrownError) {
				alert('ERROR');
		     }
    	});
    }
    function initDealer(){
    	$.ajax({
		     url: host+"/index.php/AjaxRequest/GetDealer",
		     type: "GET",
		     dataType: "json",
		     success: function (json) {
		            $('#dealer_id').empty();
		            $('#dealer_id').append($('<option>').text("Select"));
		            $.each(json, function(i, obj){
		                    $('#dealer_id').append($('<option>').text(obj.name).attr('value', obj.id));
		            });
     	
		     },
		     error: function (xhr, ajaxOptions, thrownError) {
				alert('ERROR');
		     }
    	});
    }
    function initUsageStatus(){
    	$.ajax({
		     url: host+"/index.php/AjaxRequest/GetUsageStatus",
		     type: "GET",
		     dataType: "json",
		     success: function (json) {
		            $('#usage_status_id').empty();
		            $('#usage_status_id').append($('<option>').text("Select"));
		            $.each(json, function(i, obj){
		                    $('#usage_status_id').append($('<option>').text(obj.name).attr('value', obj.id));
		            });
     	
		     },
		     error: function (xhr, ajaxOptions, thrownError) {
				alert('ERROR');
		     }
    	});
    }
    function initCompany(){
    	$.ajax({
		     url: host+"/index.php/AjaxRequest/GetCompany",
		     type: "GET",
		     dataType: "json",
		     success: function (json) {
		            $('#company_id').empty();
		            $('#company_id').append($('<option>').text("Select"));
		            $.each(json, function(i, obj){
		                    $('#company_id').append($('<option>').text(obj.name).attr('value', obj.id));
		            });
     	
		     },
		     error: function (xhr, ajaxOptions, thrownError) {
				alert('ERROR');
		     }
    	});
    }
    function initRoom(){
    	$.ajax({
		     url: host+"/index.php/AjaxRequest/GetRoom",
		     type: "GET",
		     dataType: "json",
		     success: function (json) {
		            $('#room_id').empty();
		            $('#room_id').append($('<option>').text("Select"));
		            $.each(json, function(i, obj){
		                    $('#room_id').append($('<option>').text(obj.name).attr('value', obj.id));
		            });
     	
		     },
		     error: function (xhr, ajaxOptions, thrownError) {
				alert('ERROR');
		     }
    	});
    }

</script>

</form>