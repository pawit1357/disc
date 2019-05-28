<?php
$posM = array();
$posL = array();
$posA = array();

$sql="
SELECT 
    (CASE
        WHEN `questionnaire_result`.`type` = 'D' THEN 1
        WHEN `questionnaire_result`.`type` = 'I' THEN 2
        WHEN `questionnaire_result`.`type` = 'S' THEN 3
        WHEN `questionnaire_result`.`type` = 'C' THEN 4
    END) AS SEQ,
    `questionnaire_result`.`type`,
    (CASE
        WHEN
            type = 'D'
        THEN
            (SELECT 
                    IFNULL(MAX(seq), 0)
                FROM
                    `m_data`
                WHERE
                    `m_data`.`type` = 'M' AND D <> ''
                        AND D <= `questionnaire_result`.`M`)
        WHEN
            type = 'I'
        THEN
            (SELECT 
                    IFNULL(MAX(seq), 0)
                FROM
                    `m_data`
                WHERE
                    `m_data`.`type` = 'M' AND I <> ''
                        AND I <= `questionnaire_result`.`M`)
        WHEN
            type = 'S'
        THEN
            (SELECT 
                    IFNULL(MAX(seq), 0)
                FROM
                    `m_data`
                WHERE
                    `m_data`.`type` = 'M' AND S <> ''
                        AND S <= `questionnaire_result`.`M`)
        WHEN
            type = 'C'
        THEN
            (SELECT 
                    IFNULL(MAX(seq), 0)
                FROM
                    `m_data`
                WHERE
                    `m_data`.`type` = 'M' AND C <> ''
                        AND C <= `questionnaire_result`.`M`)
    END) AS M,
    (CASE
        WHEN
            type = 'D'
        THEN
            28 - (SELECT 
                    IFNULL(MAX(seq), 0)
                FROM
                    `m_data`
                WHERE
                    `m_data`.`type` = 'L' AND D <> ''
                        AND D <= `questionnaire_result`.`L`)
        WHEN
            type = 'I'
        THEN
            28 - (SELECT 
                    IFNULL(MAX(seq), 0)
                FROM
                    `m_data`
                WHERE
                    `m_data`.`type` = 'L' AND I <> ''
                        AND I <= `questionnaire_result`.`L`)
        WHEN
            type = 'S'
        THEN
            28 - (SELECT 
                    IFNULL(MAX(seq), 0)
                FROM
                    `m_data`
                WHERE
                    `m_data`.`type` = 'L' AND S <> ''
                        AND S <= `questionnaire_result`.`L`)
        WHEN
            type = 'C'
        THEN
            28 - (SELECT 
                    IFNULL(MAX(seq), 0)
                FROM
                    `m_data`
                WHERE
                    `m_data`.`type` = 'L' AND C <> ''
                        AND C <= `questionnaire_result`.`L`)
    END) AS L,
    (CASE
        WHEN
            type = 'D'
        THEN
            (SELECT 
                    IFNULL(MAX(seq), 0)
                FROM
                    `m_data`
                WHERE
                    `m_data`.`type` = 'O' AND D <> ''
                        AND D <= `questionnaire_result`.`A`)
        WHEN
            type = 'I'
        THEN
            (SELECT 
                    IFNULL(MAX(seq), 0)
                FROM
                    `m_data`
                WHERE
                    `m_data`.`type` = 'O' AND I <> ''
                        AND I <= `questionnaire_result`.`A`)
        WHEN
            type = 'S'
        THEN
            (SELECT 
                    IFNULL(MAX(seq), 0)
                FROM
                    `m_data`
                WHERE
                    `m_data`.`type` = 'O' AND S <> ''
                        AND S <= `questionnaire_result`.`A`)
        WHEN
            type = 'C'
        THEN
            (SELECT 
                    IFNULL(MAX(seq), 0)
                FROM
                    `m_data`
                WHERE
                    `m_data`.`type` = 'O' AND C <> ''
                        AND C <= `questionnaire_result`.`A`)
    END) AS A
FROM
    `questionnaire_result`
WHERE
    `questionnaire_result`.`person_phone_num` = '" . $data[0]->person_phone_num . "' and type <>'#' order by SEQ;";

$rows = Yii::app()->db->createCommand($sql)->queryAll();
?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption">
				<?php echo  MenuUtil::getMenuName($_SERVER['REQUEST_URI'])?>

			</div>
		<div class="actions">
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
		    
    $posM = array();
    $posL = array();
    $posA = array();
    
    $index = 0;
    foreach ($rows as $row) {

//         if ($row["DATA_GROUP"] == 'Plotting Score') {
            
            
            echo "<tr>
		    			<td style=\"width: 200px;text-align: center;\">{$row["type"]}</td>
		    			<td style=\"width: 100px;text-align: center;\" title=\"".DISCUtils::getDesc($row["type"], $row["M"])."\">{$row["M"]}</td>
		    			<td style=\"width: 100px;text-align: center;\" title=\"".DISCUtils::getDesc($row["type"], $row["L"])."\">{$row["L"]}</td>
		    			<td style=\"width: 100px;text-align: center;\" title=\"".DISCUtils::getDesc($row["type"], $row["A"])."\">{$row["A"]}</td>
		    		  </tr>";
            switch ($index) {
                case 0:
                    array_push($posM, array("y" => (int) $row["M"], "label" => "D"));
                    array_push($posL, array("y" => (int) $row["L"], "label" => "D"));
                    array_push($posA, array("y" => (int) $row["A"], "label" => "D"));
                    
                    break;
                case 1:
                    array_push($posM, array("y" => (int) $row["M"], "label" => "I"));
                    array_push($posL, array("y" => (int) $row["L"], "label" => "I"));
                    array_push($posA, array("y" => (int) $row["A"], "label" => "I"));
                    break;
                case 2:
                    array_push($posM, array("y" => (int) $row["M"], "label" => "S"));
                    array_push($posL, array("y" => (int) $row["L"], "label" => "S"));
                    array_push($posA, array("y" => (int) $row["A"], "label" => "S"));
                    break;
                case 3:
                    array_push($posM, array("y" => (int) $row["M"], "label" => "C"));
                    array_push($posL, array("y" => (int) $row["L"], "label" => "C" ));
                    array_push($posA, array("y" => (int) $row["A"], "label" => "C"));
                    break;
            }
            $index ++;
//         }
    }
    
    ?>
	    </table>

			<!-- END FORM-->

		</div>
		<div id="chartContainer" style="height: 370px; width: 100%;"></div>

	</div>
</div>


<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<!-- <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script> -->
<script>

EXPORT_WIDTH = 1000;
function save_chart(chart, filename) {
		var render_width = EXPORT_WIDTH;
		var render_height = render_width * chart.chartHeight / chart.chartWidth

		var svg = chart.getSVG({
			exporting: {
				sourceWidth: chart.chartWidth,
				sourceHeight: chart.chartHeight
			}
		});

		var canvas = document.createElement('canvas');
		canvas.height = render_height;
		canvas.width = render_width;

		var image = new Image;
		image.onload = function() {
			canvas.getContext('2d').drawImage(this, 0, 0, render_width, render_height);
			var data = canvas.toDataURL("image/png")
			download(data, filename + '.png');
		};
		image.src = 'data:image/svg+xml;base64,' + window.btoa(svg);
}

function download(data, filename) {
    var a = document.createElement('a');
    a.download = filename;
    a.href = data
    document.body.appendChild(a);
    a.click();
    a.remove();
}


// window.onload = function () {
 
// var chart = new CanvasJS.Chart("chartContainer", {
// 	title: {
// 		text: "Result DISC"
// 	},
// 	axisY: {
// 		title: "Value"
// 	},
// 	data: [{
// 		type: "line",
// 		showInLegend: true, 
// 		legendText: "Most",
// 		dataPoints: <?php echo json_encode($posM, $JSON_NUMERIC_CHECK); ?>
// 	},{
// 		type: "line",
// 		showInLegend: true, 
// 		legendText: "Least",
// 		dataPoints: <?php echo json_encode($posL, $JSON_NUMERIC_CHECK); ?>
// 	},{
// 		type: "line",
// 		showInLegend: true, 
// 		legendText: "Actual",
// 		dataPoints: <?php echo json_encode($posA, $JSON_NUMERIC_CHECK); ?>
// 	}
	

	
// 	]
// });
// chart.render();
 
// }
</script>