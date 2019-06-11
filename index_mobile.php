<?php

// wait 5 seconds and redirect :)
// echo "<meta http-equiv=\"refresh\" content=\"480;url=http://localhost:88/disc/timout.php\"/>";


/************************************
 FILENAME     : index.php
 AUTHOR       : CAHYA DSN
 CREATED DATE : 2015-01-11
 UPDATED DATE : 2018-04-27
 *************************************/
// -- database configuration
$dbhost = 'localhost';
$dbuser='salayateac_disc';
$dbpass='9bNMMbbwRke3';
// $dbuser = 'root';
// $dbpass = 'P@ssw0rd';
$dbname = 'salayateac_disc';
// -- database connection
$db = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
// -- query data from database
$sql = 'SELECT * FROM personalities ORDER BY no ASC';
$result = $db->query($sql);
$data = array();
while ($row = $result->fetch_object())
    $data[] = $row;

// $show_mark = 0; // <-- show 1 or hide 0 the marker
// $cols = 4; // <-- number of columns
// $rows = count($data) / (4 * $cols);

?>
<html>
<head>
<title>DISC Personality Test</title>
<style>

body, table {
	font-family: verdana, arial, sans-serif;
	font-size: 1em;width: 100%;
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
/* Double Border */
.tb6 {
	border: 3px double #CCCCCC;
	width: 230px;
}
</style>
</head>
<body>


	<form method='post' action='result.php'>
<div style="overflow-x:auto;">
		<table>
		<tr><td style="text-align: right;">เวลาที่ใช้ไป:<input type="text" name="time_value" id="time_value" style="width: 50px;text-align: right;" value="0 : 0 " readonly="readonly" />
		</td></tr>
			<tr>
				<td style="text-align: center;">DISC personality test questionnaires<br><span style="font-size: smaller;">** Modified from DISC Personality Test, ISC **</span></td>
			</tr>
			<tr>
				<td>
					<table>
						<tr><td style="font-size: smaller;">1. กรุณากรอกข้อมูลก่อนทำแบบทดสอบ</td></tr>
						<tr><td style="font-size: smaller;">2. แบบทดสอบมีทั้งหมด 28 ข้อ 4 ตัวเลือก</td></tr>
						<tr><td style="font-size: smaller;">3. ใน 1 ข้อสามารถเลือกได้ 2 ตัวเลือก โดยไม่ซ้ำกัน</td></tr>
						<tr><td style="font-size: smaller;">4. เลือกลักษณะที่ตรงกับตัวท่าน “มากที่สุด” โดยกดเลือกในช่อง “มากที่สุด”</td></tr>
						<tr><td style="font-size: smaller;">5. เลือกลัษณะที่ตรงกับตัวท่าน “น้อยที่สุด” โดยกดเลือกในช่อง “น้อยที่สุด”</td></tr>

					</table> 
					<br>
					<table>
						<tr>
							<td style="text-align:right">ชื่อ-นามสกุล:</td>
							<td><input type="text" id="person_name" name="person_name" class='tb6'
								required></td>
						</tr>
						<tr>
							<td style="text-align:right">เพศ:</td>
							<td><select id="person_sex" name="person_sex" class='tb6'>
									<option value="1">ชาย</option>
									<option value="2">หญิง</option>
							</select></td>
						</tr>
						<tr>
							<td style="text-align:right">อายุ:</td>
							<td><input type="text" id="person_age" name="person_age" class='tb6'
								maxlength="2" required>&nbsp;<span id="errmsg"></span></td>
						</tr>
						<tr>
							<td style="text-align:right">เบอร์โทรศัพท์:</td>
							<td><input type="text" id="person_phone_num" class='tb6'
								name="person_phone_num" required></td>
						</tr>
						<tr>
							<td style="text-align:right">อีเมล์:</td>
							<td><input type="text" id="person_email" name="person_email" class='tb6'
								required>&nbsp;<span id="errmsg2"></span></td>
						
						
						<tr>
						
						
						<tr>
							<td></td>
							<td><button id="start" class='btn' >เริ่มทำแบบทดสอบ</button></td>

						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>

            		<div id="ques">
            		<table>
            			<caption></caption>
            			<thead>
            				<tr>
                    <?//php for($i=0;$i<2;++$i):?>
                      			<th>No</th>
            					<th>ลักษณะของคุณ</th>
            					<th>มากที่สุด</th>
            					<th>น้อยที่สุด</th>
                    <?//php endfor;?>
                    </tr>
            			</thead>
            			<tbody>
                  <?php
                    for ($i = 0; $i < 112 ; $i++) {
                        echo '<tr class=\'dark\'>';
                        if($i==0){
                            echo '<th rowspan=\'4\'>'.(($i/4)+1).'</th>';
                        }
                        if ($i > 0 && $i % 4  == 0) {
                            echo '<th rowspan=\'4\' class=\'first\'>'.(($i/4)+1).'</th>';
                        }
                        echo '<td '.(($i > 0 && $i % 4  == 0)? 'class=\'first\'':'').' >'.$data[$i]->term.'</td>';
                        echo '<td '.(($i > 0 && $i % 4  == 0)? 'class=\'first\'':'').' ><input type=\'radio\' id=\'m_'.$data[$i]->no.'_'.($i%4).'\' name=\'m['.(((int)$data[$i]->no)-1).']\' value=\''.$data[$i]->most.'\' required /></td>';
                        echo '<td '.(($i > 0 && $i % 4  == 0)? 'class=\'first\'':'').' ><input type=\'radio\' id=\'l_'.$data[$i]->no.'_'.($i%4).'\' name=\'l['.(((int)$data[$i]->no)-1).']\' value=\''.$data[$i]->least.'\' required /></td>';
                        echo '</tr>';
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
				</td>
			</tr>
		</table>
</div>
	</form>


	
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
<script>
var count=0;
var timerVar=null;
// window.onload=function(){
//     timerVar=setTimeout("setTimer()",1000);
// }

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function setTimer(){
    clearTimeout(timerVar);
    count+=1;
    var item=document.getElementById("time_value");
    
    var totalSeconds = Math.floor(count);
    var minutes = Math.floor(count/60);
    var seconds = totalSeconds - minutes * 60;

    
    item.value= minutes + ' : ' + seconds+' ';
    
    timerVar=setTimeout("setTimer()",1000);
}

$('#start').on('click', function(){


	var person_name = $("#person_name").val();
	var person_age = $("#person_age").val();
	var person_phone_num = $("#person_phone_num").val();
	var person_email = $("#person_email").val();

	if(!validateEmail($("#person_email").val())){
		alert('รูปแบบอีเมล์ไม่ถูกต้อง');
		$("#person_email").focus();
		return false;
	}else{
		if(person_name.length > 0 && person_age.length > 0 && person_phone_num.length > 0 && person_email.length > 0){
			$("#ques").removeClass("disabledbutton");
			timerVar=setTimeout("setTimer()",1000);
		}
	}
});





$(document).ready(function(){
	$("#ques").addClass("disabledbutton");


	  //called when key is pressed in textbox
	  $("#person_age").keypress(function (e) {
	     //if the letter is not digit then display error and don't type anything
	     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
	        //display error message
	        $("#errmsg").html("ป้อนเฉพาะตัวเลข").show().fadeOut("slow");
	               return false;
	    }
	   });
	   
	
	$('input:radio').on('click',function(){
		
		  var m=$(this).attr('id');
		  //-- Check for 
		  if($('#'+(m.slice(0,1)=='m'?'l':'m')+'_'+m.slice(2)).is(':checked')){

		      alert('คุณไม่สามารถเลือกได้ทั้งแบบ "มากที่สุด" และ "น้อยที่สุด" ในคำเดียวกัน')
		      $('#'+m).prop('checked', false);
		      
		  }
		});
	
});


</script>
