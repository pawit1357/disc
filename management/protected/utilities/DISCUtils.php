<?php

class DISCUtils
{

    public static function getDesc($type, $no)
    {
        $desc = '';
        $criteria = new CDbCriteria();
        $criteria->condition = "type = '" . $type . "' and no between " . ($no - 2) . " and " . ($no + 2);
        $result = MDataDesc::model()->findAll($criteria);
        $seq = 1;
        foreach ($result as $item) {
            $desc = $desc . PHP_EOL . $seq . '.' . $item->desc;
            $seq ++;
        }
        return $desc;
    }
    public static function getDesc2($type, $no)
    {
        $desc = '';
        $criteria = new CDbCriteria();
        $criteria->condition = "type = '" . $type . "' and no between " . ($no - 2) . " and " . ($no + 2);
        $result = MDataDesc::model()->findAll($criteria);
        $seq = 1;
        foreach ($result as $item) {
            
            $desc = $desc . "#" .$clean_code = preg_replace('/[^a-zA-Z]/', '', $item->desc_short).','. $item->desc;
            $seq ++;
        }
        return substr($desc, 1);
    }
}