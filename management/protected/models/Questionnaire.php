<?php
class Questionnaire extends CActiveRecord {
	public static function model($className = __CLASS__) {
		return parent::model ( $className );
	}
	public function tableName() {
		return 'questionnaire';
	}
	public function relations() {
	    return array ();
	}
	public function rules() {
		return array (array ('id,group_id,request_ip,person_name,person_sex,person_age,person_phone_num,person_email,start_date,end_date,image_url,create_date','safe' ));
	}
	public function attributeLabels() {
		return array ()

		;
	}
	public function getUrl($post = null) {
		if ($post === null)
			$post = $this->post;
		return $post->url . '#c' . $this->id;
	}
	protected function beforeSave() {
		return true;
	}
	public function search() {
		$criteria = new CDbCriteria ();
		return new CActiveDataProvider ( get_class ( $this ), array (
				'criteria' => $criteria,
				'sort' => array (
						'defaultOrder' => 't.id asc' 
				),
				'pagination' => array (
						'pageSize' => 1500 
				) // ConfigUtil::getDefaultPageSize()
 
		) );
	}
// 	public static function getMax() {
// 		$criteria = new CDbCriteria ();
// 		$criteria->order = 'id DESC';
// 		$row = self::model ()->find ( $criteria );
// 		$max = $row->id;
// 		return $max+1;
// 	}
}