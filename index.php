<?php

// wait 5 seconds and redirect :)
echo "<meta http-equiv=\"refresh\" content=\"480;url=http://localhost:88/disc/timout.php\"/>";


/************************************
 FILENAME     : index.php
 AUTHOR       : CAHYA DSN
 CREATED DATE : 2015-01-11
 UPDATED DATE : 2018-04-27
 *************************************/
// -- database configuration
$dbhost = 'localhost';
// $dbuser='salayateac_disc';
// $dbpass='9bNMMbbwRke3';
$dbuser = 'root';
$dbpass = 'P@ssw0rd';
$dbname = 'salayateac_disc';
// -- database connection
$db = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
// -- query data from database
$sql = 'SELECT * FROM personalities ORDER BY no ASC';
$result = $db->query($sql);
$data = array();
while ($row = $result->fetch_object())
    $data[] = $row;
$terms = json_encode($data);
$show_mark = 0; // <-- show 1 or hide 0 the marker
$cols = 4; // <-- number of columns
$rows = count($data) / (4 * $cols);
?>
<html>
<head>
<title>DISC Personality Test</title>
<style>
body, table {
	font-family: verdana, arial, sans-serif;
	font-size: 1em;
}

input {
	background-color: #eee;
	line-height: 1.5em;
}

thead {
	background-color: #666;
	color: #fff;
	line-height: 2em;
	padding: 0.2em;
}

tfoot {
	background-color: #999;
	color: #fff;
}

td {
	padding: 0.2em;
}

caption {
	font-size: 2em;
}

input[type=radio] {
	border-radius: 0;
	width: 2.2em;
	height: 2.2em;
}

.btn {
	background-color: #eee;
	line-height: 2em;
	padding: 0.1em 0.6em;
	margin: 0.2em;
	font-size: 1.5em;
	font-weight: bold;
	border-radius: 0.3em;
}

.dark {
	background-color: #eee;
}

.first {
	border-top: solid 0.2em #000;
}

.badge {
	position: relative;
	line-height: 3em;
	border: solid #999 1px;
	text-align: center;
	font-size: 2em;
}

.badge[data-badge]:after {
	content: attr(data-badge);
	position: absolute;
	top: 1px;
	left: 1px;
	font-size: .7em;
	background: #9af;
	color: white;
	width: 18px;
	height: 18px;
	text-align: center;
	line-height: 18px;
	box-shadow: 0 0 1px #333;
}

.disabledbutton {
    pointer-events: none;
    opacity: 0.4;
}

</style>
</head>
<body>


	<form method='post' action='result.php'>
		<div style="text-align: center;">
			DISC personality test questionnaires<br> <span
				style="font-size: smaller;">** Modified from DISC Personality Test,
				ISC **</span>
		</div>
		<table style="font-size: smaller;">
			<tr>
				<td>1) เลือกลักษณะที่ตรงกับตัวท่าน "มากที่สุด" จาก 4
					ตัวเลือกในแต่ละข้อ โดยใส่เลข 1 ในช่อง "มากที่สุด"</td>
			</tr>
			<tr>
				<td>2) เลือกลักษณะที่ตรงกับตัวท่าน "น้อยที่สุด" จาก 4
					ตัวเลือกในแต่ละข้อ โดยใส่เลข 1 ในช่อง "น้อยที่สุด"</td>
			</tr>
			<tr>
				<td>3) กรุณาจับเวลาในการทำบทดสอบนี้ และกรอกเวลาลงในช่อง
					"ระยะเวลาในการทำแบบทดสอบ" ในท้ายข้อ 28</td>
			</tr>
			<tr>
				<td>4) กรุณากรอกข้อมูลเพิ่มเติมท้ายแบบทดสอบ</td>
			</tr>
		</table>
		<br>
		<table>
			<tr>
				<td>ชื่อ-นามสกุล:</td>
				<td><input type="text" id="person_name" name="person_name" required ></td>
				<td>เพศ:</td>
				<td><select id="person_sex" name="person_sex">
						<option value="1">ชาย</option>
						<option value="2">หญิง</option>
				</select></td>
				<td>อายุ:</td>
				<td><input type="text" id="person_age" name="person_age"required ></td>
				<td>เบอร์โทรศัพท์:</td>
				<td><input type="text" id="person_phone_num" name="person_phone_num"required ></td>
				<td>อีเมล์:</td>
				<td><input type="text" id="person_email" name="person_email"required ></td>
<td>	    <button id="start">เริ่มทำแบบทดสอบ</button></td>
			</tr>
		</table>

		<br>
<div id="ques">
		<table>
			<caption></caption>
			<thead>
				<tr>
        <?php for($i=0;$i<$cols;++$i):?>
          <th>No</th>
					<th>term</th>
					<th>Most</th>
					<th>Least</th>
        <?php endfor;?>
        </tr>
			</thead>
			<tbody>





      <?php
    for ($i = 0; $i < $rows; ++ $i) {
        echo "<tr" . ($i % 2 == 0 ? " class='dark'" : "") . ">";
        for ($j = 0; $j < $cols; ++ $j) {
            for ($n = 0; $n < 4; ++ $n) {
                if ($j > 0 && $n == 0) {
                    echo "<tr" . ($i % 2 == 0 ? " class='dark'" : "") . ">";
                } elseif ($j == 0) {
                    echo "<th rowspan='$cols'" . ($j == 0 ? " class='first'" : "") . ">" . ($i + $n * $rows + 1) . "</th>";
                }
                echo "<td" . ($j == 0 ? " class='first'" : "") . ">
		          		{$data[$cols*($i+$n*$rows)+$j]->term}
		          	  </td>
		          	  <td" . ($j == 0 ? " class='first'" : "") . ">
		        		<input type='radio' id='m_".(($i + $n * $rows)+1) ."_".($j)."' 
		        		       name='m[" . ($i + $n * $rows) . "]' 
		        			   value='{$data[$cols*($i+$n*$rows)+$j]->most}' 
		        			   required />" . ($show_mark ? $data[$cols * ($i + $n * $rows) + $j]->most : '') . "</td>
		          	  <td" . ($j == 0 ? " class='first'" : "") . ">
		          		<input type='radio' id='l_".(($i + $n * $rows)+1) ."_".($j)."'
		          		       name='l[" . ($i + $n * $rows) . "]' 
		          		       value='{$data[$cols*($i+$n*$rows)+$j]->least}' 
		          		       required />" . ($show_mark ? $data[$cols * ($i + $n * $rows) + $j]->least : '') . "</td>";
            }
            echo "</tr>";
        }
    }
    ?>
      </tbody>
			<tfoot>
				<tr>
					<th colspan='16'><input type='submit' value='process' class='btn' />
					</th>
				</tr>
			</tfoot>
		</table>
		</div>
	</form>
	<progress value="0" max="480" id="progressBar"></progress>

	
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
<script>
var timeleft = 480;
function quesTimer(){
    var downloadTimer = setInterval(function(){
        console.log('x');
      document.getElementById("progressBar").value = 480 - --timeleft;
      if(timeleft <= 0)
        clearInterval(downloadTimer);
    },1000);
}
$('#start').on('click', function(){
	$("#ques").removeClass("disabledbutton");
	
    quesTimer();
});

$(document).ready(function(){
	$("#ques").addClass("disabledbutton");

	$('input:radio').on('click',function(){
		
		  var m=$(this).attr('id');
		  //-- Check for 
		  if($('#'+(m.slice(0,1)=='m'?'l':'m')+'_'+m.slice(2)).is(':checked')){

		      alert('You cannot select both of `most` and `least` choice in the same term')
		      $('#'+m).prop('checked', false);
		      
		  }
		});
	
});


</script>
