<?php

class MenuRole extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'menu_role';
	}

	public function relations()
	{
		return array(

		);
	}

	public function rules() {
		return array(
				array(
						'ROLE_ID,
						MENU_ID,
						IS_REQUIRED_ACTION,
						IS_ACTIVE
						IS_CREATE,
						IS_EDIT,
						IS_DELETE,
						UPDATE_BY,
						CREATE_DATE,
						UPDATE_DATE',
						 'safe'),
		);
	}

	public function attributeLabels()
	{
		return array(
				
		);
	}

	public function getUrl($post=null)
	{
		if($post===null)
			$post=$this->post;
		return $post->url.'#c'.$this->id;
	}

	protected function beforeSave()
	{
		return true;
	}

	public function search()
	{
		$criteria = new CDbCriteria;
		return new CActiveDataProvider(get_class($this), array(
				'criteria' => $criteria,
// 				'sort' => array(
// 						'defaultOrder' => 't.DISPLAY_ORDER asc',
// 				),
				'pagination' => array(
						'pageSize' => ConfigUtil::getDefaultPageSize()
				),
		));
	}
}