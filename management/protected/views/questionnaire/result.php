<form id="Form1" method="post" enctype="multipart/form-data"
	class="form-horizontal">

<?php
    $charts = array();
    $xs1 = array();
    $xs2 = array();
    $xs3 = array();

$sql="
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
    person_phone_num = '" . $data[0]->person_phone_num . "'
        AND type <> '#'
ORDER BY SEQ";

$rows = Yii::app()->db->createCommand($sql)->queryAll();
?>


<input type="hidden" id="image_url" name="Questionnaire[image_url]" value="">


<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
				<?php echo  MenuUtil::getMenuName($_SERVER['REQUEST_URI'])?>

			</div>
		<div class="actions">

        
            <input type="button" name="btnPrint" value="พิมพ์เอกสาร" onclick="redirectTo()" class="btn btn-warning btn-sm" />
			<?php echo CHtml::link('ย้อนกลับ',array('Questionnaire/'),array('class'=>'btn btn-default btn-sm'));?>
		</div>
	</div>
	<div class="portlet-body form">
		<div class="form-body">
			<!-- BEGIN FORM-->
			<h2>Plotting Score</h2>
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
    $index = 0;
    foreach ($rows as $row) {



            echo "<tr>
		    			<td style=\"width: 200px;text-align: center;\">{$row["type"]}</td>
		    			<td style=\"width: 100px;text-align: center;\" title=\"".DISCUtils::getDesc($row["type"], $row["M"])."\">{$row["M"]}</td>
		    			<td style=\"width: 100px;text-align: center;\" title=\"".DISCUtils::getDesc($row["type"], $row["L"])."\">{$row["L"]}</td>
		    			<td style=\"width: 100px;text-align: center;\" title=\"".DISCUtils::getDesc($row["type"], $row["A"])."\">{$row["A"]}</td>
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

			<!-- END FORM-->

		</div>
		<div id="container" style="height: 600px; width: 100%;"></div>

	</div>
</div>






<script
		src="<?php echo ConfigUtil::getAppName();?>/assets/global/plugins/jquery.min.js"
		type="text/javascript"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script>

    var exportUrl = 'http://export.highcharts.com/';
    var optionsStr = JSON.stringify(
		{
			"title": {
                text: 'DISC'
            },
			"xAxis": {
				"categories": [
					"D",
					"I",
					"S",
					"C"
				]
			},
			"series": <?php echo json_encode($charts, JSON_NUMERIC_CHECK) ?>
		});

        dataString = encodeURI('async=true&type=jpeg&width=600&options=' + optionsStr);

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
            window.location.href = 'http://localhost:90/disc/management/index.php/Questionnaire/Print/'+_image_url;
        }


</script>
</form>