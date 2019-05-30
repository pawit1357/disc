<?php
ini_set('max_execution_time', 0);

class CommonUtil
{

    public static function getShortThaiMonth($m)
    {
        $q_m = array(
            "01" => "ม.ค.",
            "02" => "ก.พ.",
            "03" => "มี.ค.",
            "04" => "เม.ย.",
            "05" => "พ.ค.",
            "06" => "มิ.ย.",
            "07" => "ก.ค.",
            "08" => "ส.ค.",
            "09" => "ก.ย.",
            "10" => "ต.ค.",
            "11" => "พ.ย.",
            "12" => "ธ.ค."
        );
        return $q_m[$m];
    }

    public static function convertModelToArray($models)
    {
        if (is_array($models))
            $arrayMode = TRUE;
        else {
            $models = array(
                $models
            );
            $arrayMode = FALSE;
        }

        $result = array();
        foreach ($models as $model) {
            $attributes = $model->getAttributes();
            $relations = array();
            foreach ($model->relations() as $key => $related) {
                if ($model->hasRelated($key)) {
                    $relations[$key] = convertModelToArray($model->$key);
                }
            }
            $all = array_merge($attributes, $relations);

            if ($arrayMode)
                array_push($result, $all);
            else
                $result = $all;
        }
        return $result;
    }

    public static function IsNullOrEmptyString($question)
    {
        return (! isset($question) || trim($question) === '');
    }

    public static function deleteDirectory($dirPath)
    {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDirectory($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    function clean($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

    public static function endsWith($FullStr, $needle)
    {
        $StrLen = strlen($needle);
        $FullStrEnd = substr($FullStr, strlen($FullStr) - $StrLen);
        return $FullStrEnd == $needle;
    }

    public static function dateDiff($date1, $date2)
    {
        // $datetime1 = new DateTime ( $date1 );
        // $datetime2 = new DateTime ( $date2 );
        // $interval = $datetime1->diff ( $datetime2 );
        // return $interval->format ( '%a' );
        $unixOriginalDate = strtotime($date1);
        $unixNowDate = strtotime($date2);
        $difference = $unixNowDate - $unixOriginalDate;
        $days = (int) ($difference / 86400);
        $hours = (int) ($difference / 3600);
        $minutes = (int) ($difference / 60);
        $seconds = $difference;
        return $days;
    }

    // end function dateDiff
    public static function getDateThai($date)
    {
        list ($year, $month, $day) = explode("-", $date);
        return $day . '/' . $month . '/' . (((int) $year) + 543);
    }

    public static function getDate($date)
    {
        list ($day, $month, $year) = explode("/", $date);

        return (((int) $year) - 543) . '-' . $month . '-' . $day;
    }

    public static function getCurDate()
    {
        list ($day, $month, $year) = explode("/", date("d/m/Y"));

        return $day . '/' . $month . '/' . (((int) $year) + 543);
    }

    public function upload($file)
    {
        $currentdir = getcwd();

        $upload_dir = $currentdir . "\\uploads\\" . date("Ymd") . "\\";
        $dest_dir = "/uploads/" . date("Ymd") . "/";
        if (! file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = strtolower(self::random_string(10) . '.' . self::f_extension($file['name']));
        $file_dir = $upload_dir . $file_name;

        $move = move_uploaded_file($file["tmp_name"], $file_dir);
        return $dest_dir . $file_name;
    }

    public static function f_extension($fn)
    {
        $str = explode('/', $fn);
        $len = count($str);
        $str2 = explode('.', $str[($len - 1)]);
        $len2 = count($str2);
        $ext = $str2[($len2 - 1)];
        return $ext;
    }

    public static function random_string($length)
    {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i ++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }

    public static function reArrayFiles($file_post)
    {
        $file_ary = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);

        for ($i = 0; $i < $file_count; $i ++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }

        return $file_ary;
    }

    function dateDifference($date1, $date2)
    {
        $date1 = strtotime($date1);
        $date2 = strtotime($date2);
        $diff = abs($date1 - $date2);

        $day = $diff / (60 * 60 * 24); // in day
        $dayFix = floor($day);
        $dayPen = $day - $dayFix;
        if ($dayPen > 0) {
            $hour = $dayPen * (24); // in hour (1 day = 24 hour)
            $hourFix = floor($hour);
            $hourPen = $hour - $hourFix;
            if ($hourPen > 0) {
                $min = $hourPen * (60); // in hour (1 hour = 60 min)
                $minFix = floor($min);
                $minPen = $min - $minFix;
                if ($minPen > 0) {
                    $sec = $minPen * (60); // in sec (1 min = 60 sec)
                    $secFix = floor($sec);
                }
            }
        }
        $str = "";
        if ($dayFix > 0)
            $str .= $dayFix . " วัน ";
        if ($hourFix > 0)
            $str .= $hourFix . " ชั่วโมง ";
        if ($minFix > 0)
            $str .= $minFix . " นาที ";
        if ($secFix > 0)
            $str .= $secFix . " วินาที ";
        return $str;
    }

    /* #MASTER# */
    const CHECKBOX_TYPE = "1";

    const TEXT_TYPE = "2";

    const TABLE_TYPE = "3";

    const FILE_TYPE = "4";

    /* QUERY */
    const SURVEY_BIO = "1";

    const SURVEY_CHEM = "2";

    const SURVEY_OCC = "3";

    const SURVEY_RADBASE = "4";
}
?>