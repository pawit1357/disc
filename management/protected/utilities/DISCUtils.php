<?php

class DISCUtils
{

    public static function getDesc($type,$no) {

        $desc ='';
        $criteria = new CDbCriteria();
        $criteria->condition = "type = '".$type."' and no between ".($no-2)." and ".($no+2);
        $result = MDataDesc::model()->findAll($criteria);
        $seq = 1;
        foreach ($result as $item){
            $desc = $desc.' '.$seq.'.'.$item->desc.'&#013;';
            $seq++;
        }
        return $desc;
    }
}