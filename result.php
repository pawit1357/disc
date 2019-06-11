<?php
date_default_timezone_set("Asia/Bangkok");

// -- database configuration
$dbhost = 'localhost';
// $dbuser = 'salayateac_disc';
// $dbpass = '9bNMMbbwRke3';
$dbuser = 'root';
$dbpass = 'P@ssw0rd';
$dbname = 'salayateac_disc';
// -- database connection
$db = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
?>

<?php
if (isset($_POST['m']) && isset($_POST['l'])) {
    $most = array_count_values($_POST['m']);
    $least = array_count_values($_POST['l']);
    $result = array();
    $aspect = array(
        'D',
        'I',
        'S',
        'C',
        '#'
    );

    $time_value = $_POST['time_value'];
    if (isset($time_value)) {

        $group_id = 1;

        $groups = $db->query("select * from tb_management_group where is_active ='A' order by id desc");
        if (isset($groups)) {
            while ($row = $groups->fetch_array(MYSQLI_BOTH)) {
                $group_id = $row['id'];
            }
        }

        list ($min, $secound) = split(':', $time_value);

        $endDate = date('Y-m-d H:i:s');
        $startDate = date('Y-m-d H:i:s', strtotime('-' . (($min * 60) + $secound) . ' seconds', strtotime($endDate)));

        // echo 'start'.$startDate.','.$endDate;
        foreach ($aspect as $a) {
            $result[$a]['most'] = isset($most[$a]) ? $most[$a] : 0;
            $result[$a]['least'] = isset($least[$a]) ? $least[$a] : 0;
            $result[$a]['change'] = ($a != '#' ? $result[$a]['most'] - $result[$a]['least'] : 0);
        }

        $deleteSql2 = "DELETE FROM questionnaire WHERE person_phone_num='" . $_POST['person_phone_num'] . "'";
        if ($db->query($deleteSql2) === TRUE) {}

        $deleteSql = "DELETE FROM questionnaire_result WHERE person_phone_num='" . $_POST['person_phone_num'] . "'";
        if ($db->query($deleteSql) === TRUE) {}

        $sqlInsert = "INSERT INTO questionnaire(request_ip,person_name,person_sex,person_age,person_phone_num,person_email,create_date,start_date,end_date,group_id) VALUES('" . $_SERVER['REMOTE_ADDR'] . "','" . $_POST['person_name'] . "','" . $_POST['person_sex'] . "','" . $_POST['person_age'] . "','" . $_POST['person_phone_num'] . "','" . $_POST['person_email'] . "',NOW(),'" . $startDate . "','" . $endDate . "','" . $group_id . "');";
        if ($db->query($sqlInsert) === TRUE) {
            foreach ($aspect as $a) {
                $sql = "INSERT INTO questionnaire_result(person_phone_num,type,M,L,A,create_date) VALUES('" . $_POST['person_phone_num'] . "','{$a}','{$result[$a]['most']}','{$result[$a]['least']}','{$result[$a]['change']}',NOW());";

                if ($db->query($sql) === TRUE) {}
            }
        }
        echo '<h1>ท่านตอบแบบสอบถามเรียบร้อยแล้ว</h1><br><a href="https://www.salayatea.com/disc">ย้อนกลับ</a>';
    }
}
?>


