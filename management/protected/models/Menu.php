<?php

class Menu extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'menu';
	}

	public function relations()
	{
		return array(

		);
	}

	public function rules() {
		return array(
				array(
						'MENU_ID,
						MENU_ICON,
						MENU_NAME,
						URL_NAVIGATE,
						MENU_TAG,
						PREVIOUS_MENU_ID,
						DISPLAY_ORDER,
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
				'sort' => array(
						'defaultOrder' => 'PREVIOUS_MENU_ID,DISPLAY_ORDER',
				),
				'pagination' => array(
						'pageSize' => 50//ConfigUtil::getDefaultPageSize()
				),
		));
	}
}