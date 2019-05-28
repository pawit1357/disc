<?php
class MDataDesc extends CActiveRecord {
	public static function model($className = __CLASS__) {
		return parent::model ( $className );
	}
	public function tableName() {
		return 'm_data_desc';
	}
	public function relations() {
		return array ();
	}
	public function rules() {
	    
		return array (array ('type,no,desc_short,desc','safe' ));
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
						'pageSize' => 15 
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