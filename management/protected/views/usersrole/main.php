<form id="Form1" method="POST" enctype="multipart/form-data"
	class="form-horizontal">



	<div class="row">
		<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
	<div class="portlet light">
				<div class="portlet-title">
					<div class="caption">
						<?php echo  MenuUtil::getMenuName($_SERVER['REQUEST_URI'])?>
					</div>
					<div class="actions">
						<?php echo (UserLoginUtils::canCreate($_SERVER['REQUEST_URI']) == false)? "":  CHtml::link('เพิ่มข้อมูล',array('UsersRole/Create'),array('class'=>'btn btn-default btn-sm'));?>
					</div>
				</div>
				<div class="portlet-body">
					<table class="table table-striped table-hover table-bordered"
						id="gvResult">
						<thead>
							<tr>
								<th>รหัส</th>
								<th>ชื่อกลุ่มผู้ใช้</th>
								
								<th class="no-sort"></th>
							</tr>
						</thead>
						<tbody>
	<?php
	$counter = 1;
	$dataProvider = $data->search ();
	
	foreach ( $dataProvider->data as $data ) {
		?>
				<tr class="line-<?php echo $counter%2 == 0 ? '1' : '2'?>">
								<td class="center"><?php echo $data->ROLE_ID?></td>
								<td class="center"><?php echo $data->ROLE_NAME?></td>

								<td class="center">
				
									
<?php if(UserLoginUtils::canUpdate( $_SERVER['REQUEST_URI'])){?>
<a title="Edit" class="fa fa-edit"
									href="<?php echo Yii::app()->CreateUrl('UsersRole/Update/id/'.$data->ROLE_ID)?>"></a>
<?php }?>
<?php if(UserLoginUtils::canDelete( $_SERVER['REQUEST_URI'])){?>
<a title="Delete" onclick="return confirm('ต้องการลบข้อมูลใช่หรือไม่?')"
									class="fa fa-trash"
									href="<?php echo Yii::app()->CreateUrl('UsersRole/Delete/id/'.$data->ROLE_ID)?>"></a>
<?php }?>	


								</td>
							</tr>
			<?php
	}
	?>
						</tbody>
					</table>

				</div>
			</div>
		</div>
	</div>

	<script
		src="<?php echo ConfigUtil::getAppName();?>/assets/global/plugins/jquery.min.js"
		type="text/javascript"></script>
	<script>
jQuery(document).ready(function () {
	var table = $('#gvResult');

	var oTable = table.dataTable({

	    // Internationalisation. For more info refer to http://datatables.net/manual/i18n
	    "language": {
	        "aria": {
	            "sortAscending": ": activate to sort column ascending",
	            "sortDescending": ": activate to sort column descending"
	        },
	        "emptyTable": "ไม่พบข้อมูล",
	        "info": "แสดง  _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
	        "infoEmpty": "No entries found",
	        "infoFiltered": "(filtered1 from _MAX_ total entries)",
	        "lengthMenu": "แสดงข้อมูล  _MENU_ รายการ",
	        "search": "ใส่คำที่ต้องการค้นหา:",
	        "zeroRecords": "ไม่พบรายการที่ค้นหา"
	    },


	    // setup responsive extension: http://datatables.net/extensions/responsive/
	    responsive: true,

	    //"ordering": false, disable column ordering 
	    //"paging": false, disable pagination

	    "order": [
	        [0, 'asc']
	    ],
	    
	    "lengthMenu": [
	        [5, 10, 15, 20, -1],
	        [5, 10, 15, 20, "ทั้งหมด"] // change per page values here
	    ],
	    // set the initial value
	    "pageLength": 10 ,
	    "columnDefs": [ {
	        "targets": 'no-sort',
	        "orderable": false,
	  } ]
		});
});
</script>
</form>