<form id="Form1" method="post" enctype="multipart/form-data"
	class="form-horizontal">

<?php
$discText=array("D  – Dominance (มิติของความมีอำนาจเหนือผู้อื่น)","I   – Influence (มิติของความสามารถในการโน้มน้าวผู้อื่น)","S  – Steadiness (มิติของความมั่นคงสม่ำเสมอ)","C  – Conscientiousness (มิติของความะละเอียดมีระเบียบแบบแผน)");
$charts = array();
$xs1 = array();
$xs2 = array();
$xs3 = array();

$sql = "
SELECT 
    (CASE
        WHEN type = 'D' THEN 1
        WHEN type = 'I' THEN 2
        WHEN type = 'S' THEN 3
        WHEN type = 'C' THEN 4
    END) AS SEQ,
    type,
    (CASE 
		WHEN type = 'D' THEN (SELECT IFNULL(MAX(seq), 0) FROM m_data WHERE type = 'M' AND D <> '' AND D <= M)
        WHEN type = 'I' THEN (SELECT IFNULL(MAX(seq), 0) FROM m_data WHERE type = 'M' AND I <> '' AND I <= M)
        WHEN type = 'S' THEN (SELECT IFNULL(MAX(seq), 0) FROM m_data WHERE type = 'M' AND S <> '' AND S <= M)
        WHEN type = 'C' THEN (SELECT IFNULL(MAX(seq), 0) FROM m_data WHERE type = 'M' AND C <> '' AND C <= M) 
	END) AS M,
    (CASE
        WHEN type = 'D' THEN 28 - (SELECT IFNULL(MAX(seq), 0) FROM m_data WHERE type = 'L' AND D <> '' AND D <= L)
        WHEN type = 'I' THEN 28 - (SELECT IFNULL(MAX(seq), 0) FROM m_data WHERE type = 'L' AND I <> '' AND I <= L)
        WHEN type = 'S' THEN 28 - (SELECT IFNULL(MAX(seq), 0) FROM m_data WHERE type = 'L' AND S <> '' AND S <= L)
        WHEN type = 'C' THEN 28 - (SELECT IFNULL(MAX(seq), 0) FROM m_data WHERE type = 'L' AND C <> '' AND C <= L)
    END) AS L,
    (CASE 
		WHEN type = 'D' THEN (SELECT IFNULL(MAX(seq), 0) FROM m_data WHERE type = 'O' AND D <> '' AND D <= A)
        WHEN type = 'I' THEN (SELECT IFNULL(MAX(seq), 0) FROM m_data WHERE type = 'O' AND I <> '' AND I <= A)
        WHEN type = 'S' THEN (SELECT IFNULL(MAX(seq), 0) FROM m_data WHERE type = 'O' AND S <> '' AND S <= A)
        WHEN type = 'C' THEN (SELECT IFNULL(MAX(seq), 0) FROM m_data WHERE type = 'O' AND C <> '' AND C <= A)
    END) AS A
FROM
    questionnaire_result
WHERE
    person_phone_num = '" . $data->person_phone_num . "'
        AND type <> '#'
ORDER BY SEQ";

$rows = Yii::app()->db->createCommand($sql)->queryAll();
?>


		<input type="hidden" id="image_url" name="Questionnaire[image_url]" value=""> 
		<input type="hidden" id="person_phone_num" name="Questionnaire[person_phone_num]" value="<? echo $data->person_phone_num ?>">
        <input type="hidden" id="exportUrl" value="<?php echo ConfigUtil::getHightChartExportURL();?>">
        <input type="hidden" id="hostUrl" value="<?php echo ConfigUtil::getUrlHostName().''.ConfigUtil::getAppName();?>">


	<div class="portlet light">
		<div class="portlet-title">
			<div class="caption">
				<?php echo  MenuUtil::getMenuName($_SERVER['REQUEST_URI'])?>

			</div>
			<div class="actions">

			<?php 
    			$path = getcwd() . "/uploads/" . date('Y/m/d');
    			$report_file_doc = $path . "/" . $data->person_name . ".docx";
    			if(file_exists($report_file_doc)){
    			    ?>
			<a href="<?php echo ConfigUtil::getUrlHostName().''.ConfigUtil::getAppName().'/uploads/'.date('Y/m/d').'/'.$data->person_name.'.docx';?>" class="btn btn-success btn-sm" >ดาวโหลดไฟล์ word</a>

    			    <?php
    			}else{
    			    ?>
			<input type="button" name="btnPrint" value="สร้างไฟล์ (Word)" onclick="redirectTo()" class="btn btn-w btn-sm" />

    			    <?php
    			}
			?>
			

			
			<?php echo CHtml::link('ย้อนกลับ',array('Questionnaire/'),array('class'=>'btn btn-default btn-sm'));?>
			

		</div>
		</div>
		<div class="portlet-body form">
			<div class="form-body">

				<!-- BEGIN FORM-->

				<div class="panel-group accordion" id="accordion1">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse"
									data-parent="#accordion1" href="#collapse_1"> <i
									class="fa fa-user"></i> DiSC Classic Profile
								</a>
							</h4>
						</div>
						<br>
						<div id="collapse_1" class="panel-collapse in">
							<div class="row">
								<div class="form-group">
									<label class="control-label col-md-2">ชื่อ - นามสกุล:<span
										class="required"></span></label>
									<div class="col-md-4">
										<input id="person_name" type="text"
											value="<?php echo $data->person_name;?>" class="form-control"
											name="Questionnaire[person_name]" readonly="readonly">
									</div>
									<div id="divReq-name"></div>
								</div>
							</div>
							<div class="row">
								<div class="form-group">
									<label class="control-label col-md-2">เบอร์โทรศัพท์:<span
										class="required"></span></label>
									<div class="col-md-4">
										<input id="person_name" type="text"
											value="<?php echo $data->person_phone_num;?>"
											class="form-control" name="Questionnaire[person_name]"
											readonly="readonly">
									</div>
									<div id="divReq-name"></div>
								</div>
							</div>
							<div class="row">
								<div class="form-group">
									<label class="control-label col-md-2">อีเมล์:<span
										class="required"></span></label>
									<div class="col-md-4">
										<input id="person_name" type="text"
											value="<?php echo $data->person_email;?>"
											class="form-control" name="Questionnaire[person_name]"
											readonly="readonly">
									</div>
									<div id="divReq-name"></div>
								</div>
							</div>
							<div class="row">
								<div class="form-group">
									<label class="control-label col-md-2">เวลาที่ใช้ทำแบบทดสอบ:<span
										class="required"></span></label>
									<div class="col-md-4">
										<input id="person_name" type="text"
											value="<?php echo CommonUtil::dateDifference( $data->start_date,$data->end_date);?>"
											class="form-control" name="Questionnaire[person_name]"
											readonly="readonly">
									</div>
									<div id="divReq-name"></div>
								</div>
							</div>
						</div>

					</div>
				</div>
				<div class="panel-group accordion" id="accordion2">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse"
									data-parent="#accordion2" href="#collapse_2"> <i
									class="fa fa-line-chart"></i> กราฟแสดงแนวโน้มพฤติกรรม
								</a>
							</h4>
						</div>
						<br>

						<div id="collapse_2" class="panel-collapse in">
							<div class="row">
								<div class="form-group">
									<label class="control-label col-md-2"><span
										class="required"></span></label>
									<div class="col-md-4">
									<table>
									<tr>
									<td>

                        				<table class="table table-striped table-hover table-bordered">
                        
                        					<tr>
                        						<th style="width: 200px; text-align: center;"></th>
                        						<th style="width: 100px; text-align: center;">M</th>
                        						<th style="width: 100px; text-align: center;">L</th>
                        						<th style="width: 100px; text-align: center;">A</th>
                        					</tr>
                        		    <?php
                        
                            $xs1['name'] = 'Most';
                            $xs2['name'] = 'Least';
                            $xs3['name'] = 'Actual';
                            
                            $xs1['color'] = '#FF0000';
                            $xs2['color'] = '#0066FF';
                            $xs3['color'] = '#FFA500';
                            $index = 0;
                            foreach ($rows as $row) {
                        
                                echo "<tr>
                        		    			<td style=\"width: 200px;text-align: center;\">{$row["type"]}</td>
                        		    			<td style=\"width: 100px;text-align: center;\" title=\"" . DISCUtils::getDesc($row["type"], $row["M"]) . "\">{$row["M"]}</td>
                        		    			<td style=\"width: 100px;text-align: center;\" title=\"" . DISCUtils::getDesc($row["type"], $row["L"]) . "\">{$row["L"]}</td>
                        		    			<td style=\"width: 100px;text-align: center;\" title=\"" . DISCUtils::getDesc($row["type"], $row["A"]) . "\">{$row["A"]}</td>
                        		    		  </tr>";
                                switch ($index) {
                                    case 0:
                                        $xs1['data'][0] = (int) $row["M"];
                                        $xs2['data'][0] = (int) $row["L"];
                                        $xs3['data'][0] = (int) $row["A"];
                                        break;
                                    case 1:
                                        $xs1['data'][1] = (int) $row["M"];
                                        $xs2['data'][1] = (int) $row["L"];
                                        $xs3['data'][1] = (int) $row["A"];
                                        break;
                                    case 2:
                                        $xs1['data'][2] = (int) $row["M"];
                                        $xs2['data'][2] = (int) $row["L"];
                                        $xs3['data'][2] = (int) $row["A"];
                                        break;
                                    case 3:
                                        $xs1['data'][3] = (int) $row["M"];
                                        $xs2['data'][3] = (int) $row["L"];
                                        $xs3['data'][3] = (int) $row["A"];
                                        break;
                                }
                                $index ++;
                            }
                            $xs1['type'] = 'line';
                            $xs2['type'] = 'line';
                            $xs3['type'] = 'line';
                        
                            array_push($charts, $xs1);
                            array_push($charts, $xs2);
                            array_push($charts, $xs3);
                        
                            ?>
                        	    </table>
                        	    </td>
									<td><div id="container" style="width: 80%;"></div></td>
                        	    
                        	    									</tr>
									</table>
                        	    										
                        	    
									</div>
								</div>
							</div>
							<div class="row">
								<div class="form-group">
									<label class="control-label col-md-2"><span
										class="required">*</span></label>
									<div class="col-md-4">
									</div>
									<div id="divReq-name"></div>
								</div>
							</div>
						</div>
					</div>
				</div>


				<div class="panel-group accordion" id="accordion3">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse"
									data-parent="#accordion3" href="#collapse_3"> <i
									class="fa fa-bullseye"></i> 1.2.1	พฤติกรรมของท่านหรือตัวท่านในสายตาผู้อื่น
								</a>
							</h4>
						</div>
						<br>
						<div id="collapse_3" class="panel-collapse in">
							<div class="row">
								<div class="form-group">
															<label class="control-label col-md-2"><span
										class="required"></span></label>
									<div class="col-md-8">
									<h3></h3>
									
										<table border="1" class="table table-striped table-hover table-bordered">
											<?php for($i=0;$i<4;$i++){
// 											    echo $i.':'.DISCUtils::getDesc2($rows[$i]['type'], $rows[$i]['A']).'<br>';
											    
											    
											    list ($ActualD01, $ActualD02, $ActualD03, $ActualD04, $ActualD05) = split('#', DISCUtils::getDesc2($rows[$i]['type'], $rows[$i]['A']));
											    
											    ?>
											    <tr><td colspan="2"><?php echo $discText[$i];?></td></tr>
											    <tr><td width="200px;"><?php echo split(',', $ActualD01)[0];?></td><td><?php echo split(',', $ActualD01)[1];?></td></tr>
											    <tr><td width="200px;"><?php echo split(',', $ActualD02)[0];?></td><td><?php echo split(',', $ActualD02)[1];?></td></tr>
											    <tr><td width="200px;"><?php echo split(',', $ActualD03)[0];?></td><td><?php echo split(',', $ActualD03)[1];?></td></tr>
											    <tr><td width="200px;"><?php echo split(',', $ActualD04)[0];?></td><td><?php echo split(',', $ActualD04)[1];?></td></tr>
											    <tr><td width="200px;"><?php echo split(',', $ActualD05)[0];?></td><td><?php echo split(',', $ActualD05)[1];?></td></tr>
											    <tr><td colspan="2">&nbsp;</td></tr>
											    <?php 
											}?>
										</table>
									</div>
									<div id="divReq-name"></div>
								</div>
							</div>
							
						</div>
					</div>
				</div>


				<div class="panel-group accordion" id="accordion4">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse"
									data-parent="#accordion4" href="#collapse_4"> <i
									class="fa fa-street-view"></i> 1.2.2	สิ่งที่ท่านคิดว่าเป็นพฤติกรรมของท่าน
								</a>
							</h4>
						</div>
						<br>
						<div id="collapse_4" class="panel-collapse in">
							<div class="row">
								<div class="form-group">
															<label class="control-label col-md-2"><span
										class="required"></span></label>
									<div class="col-md-8">
									<h3></h3>
										<table border="1" class="table table-striped table-hover table-bordered">
											<?php for($i=0;$i<4;$i++){
											    list ($ActualD01, $ActualD02, $ActualD03, $ActualD04, $ActualD05) = split('#', DISCUtils::getDesc2($rows[$i]['type'], $rows[$i]['M']));
											    ?>
											    <tr><td colspan="2"><?php echo $discText[$i];?></td></tr>
											    <tr><td width="200px;"><?php echo split(',', $ActualD01)[0];?></td><td><?php echo split(',', $ActualD01)[1];?></td></tr>
											    <tr><td width="200px;"><?php echo split(',', $ActualD02)[0];?></td><td><?php echo split(',', $ActualD02)[1];?></td></tr>
											    <tr><td width="200px;"><?php echo split(',', $ActualD03)[0];?></td><td><?php echo split(',', $ActualD03)[1];?></td></tr>
											    <tr><td width="200px;"><?php echo split(',', $ActualD04)[0];?></td><td><?php echo split(',', $ActualD04)[1];?></td></tr>
											    <tr><td width="200px;"><?php echo split(',', $ActualD05)[0];?></td><td><?php echo split(',', $ActualD05)[1];?></td></tr>
											    <tr><td colspan="2">&nbsp;</td></tr>
											    <?php 
											}?>
										</table>
									</div>
									<div id="divReq-name"></div>
								</div>
							</div>
							
						</div>
					</div>
				</div>


				<div class="panel-group accordion" id="accordion5">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a class="accordion-toggle" data-toggle="collapse"
									data-parent="#accordion5" href="#collapse_5"> <i
									class="fa fa-user-times"></i> 1.2.3	พฤติกรรมของตัวท่านจากภายใน
								</a>
							</h4>
						</div>
						<br>
						<div id="collapse_5" class="panel-collapse in">
							<div class="row">
								<div class="form-group">
															<label class="control-label col-md-2"><span
										class="required"></span></label>
									<div class="col-md-8">
									<h3></h3>
										<table border="1" class="table table-striped table-hover table-bordered">
											<?php for($i=0;$i<4;$i++){
											    list ($ActualD01, $ActualD02, $ActualD03, $ActualD04, $ActualD05) = split('#', DISCUtils::getDesc2($rows[$i]['type'], $rows[$i]['L']));
											    ?>
											    <tr><td colspan="2"><?php echo $discText[$i];?></td></tr>
											    <tr><td width="200px;"><?php echo split(',', $ActualD01)[0];?></td><td><?php echo split(',', $ActualD01)[1];?></td></tr>
											    <tr><td width="200px;"><?php echo split(',', $ActualD02)[0];?></td><td><?php echo split(',', $ActualD02)[1];?></td></tr>
											    <tr><td width="200px;"><?php echo split(',', $ActualD03)[0];?></td><td><?php echo split(',', $ActualD03)[1];?></td></tr>
											    <tr><td width="200px;"><?php echo split(',', $ActualD04)[0];?></td><td><?php echo split(',', $ActualD04)[1];?></td></tr>
											    <tr><td width="200px;"><?php echo split(',', $ActualD05)[0];?></td><td><?php echo split(',', $ActualD05)[1];?></td></tr>
											    <tr><td colspan="2">&nbsp;</td></tr>
											    <?php 
											}?>
										</table>
									</div>
									<div id="divReq-name"></div>
								</div>
							</div>
							
						</div>
					</div>
				</div>

				<!-- END FORM-->

			</div>

		</div>

	</div>





	<script
		src="<?php echo ConfigUtil::getAppName();?>/assets/global/plugins/jquery.min.js"
		type="text/javascript"></script>

	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>
	<script src="https://code.highcharts.com/modules/export-data.js"></script>
	<script>

	var exportUrl = document.getElementById("exportUrl").value;  
	var hostUrl = document.getElementById("hostUrl").value;  
	
    var optionsStr = JSON.stringify(
		{
			"title": {
                text: 'DISC'
            },
			"xAxis": {
				"lineColor": '#000000',
				"categories": [
					"D",
					"I",
					"S",
					"C"
				],
			    labels: {
		            style: {
		                color: 'black'
		            }
		        }
			},
			"yAxis":{
				  max: 28,
				  min:0,
				  tickInterval: 4,
				  lineWidth: 1,
				  lineColor: '#000000',
    			    labels: {
    		            style: {
    		                color: 'black'
    		            }
    		        },
    		        title: {
    		        	   style: {
    		                   color: '#000000'
    		                } 
    	            }
			},
			"series": <?php echo json_encode($charts, JSON_NUMERIC_CHECK) ?>
		});

        dataString = encodeURI('async=true&type=jpeg&width=550&options=' + optionsStr);

        if (window.XDomainRequest) {
            var xdr = new XDomainRequest();
            xdr.open("post", exportUrl+ '?' + dataString);
            xdr.onload = function () {
                console.log(xdr.responseText);
                $('#container').html('<img src="' + exporturl + xdr.responseText + '"/>');
            };
            xdr.send();
        } else {
            $.ajax({
                type: 'POST',
                data: dataString,
                url: exportUrl,
                success: function (data) {
                    console.log('get the file from relative url: ', data);
                    $('#container').html('<img src="' + exportUrl + data + '"/>');
					$("#image_url").val(data);
                },
                error: function (err) {
                    debugger;
                    console.log('error', err.statusText)
                }
            });
        }

        function redirectTo(){
            var _image_url = document.getElementById('image_url').value;
            var _phone_num = document.getElementById('person_phone_num').value;
            console.log(hostUrl+'/index.php/Questionnaire/Print/'+_image_url+'/phone_num/'+_phone_num);
            window.location.href = hostUrl+'/index.php/Questionnaire/Print/'+_image_url+'/phone_num/'+_phone_num;
        }


</script>
</form>