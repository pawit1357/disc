<?php
class QuestionnaireController extends CController {
	public $layout = '_main';
	private $_model;
	public function actionIndex() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}
		$model = new Questionnaire();
		$this->render ( '//questionnaire/main', array (
				'data' => $model 
		) );
	}
	public function actionCreate() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}
		
		if (isset ( $_POST ['Questionnaire'] )) {
			
			$transaction = Yii::app ()->db->beginTransaction ();
			// Add Request
			$model = new Questionnaire();
			$model->attributes = $_POST ['Questionnaire'];
			
			$model->save ();
			// echo "SAVE";
			$transaction->commit ();
			
			// $transaction->rollback ();
			$this->redirect ( Yii::app ()->createUrl ( 'Questionnaire' ) );
		} else {
			// Render
			$this->render ( '//questionnaire/create' );
		}
	}
	public function actionResult() {
	    // Authen Login
	    if (! UserLoginUtils::isLogin ()) {
	        $this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
	    }
	    if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
	        $this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
	    }
	    
	    ///
	    $person_phone_num = addslashes ( $_GET ['person_phone_num'] );
	    
	    $criteria = new CDbCriteria();
	    $criteria->condition = "person_phone_num='".$person_phone_num."'";
	    
	    $result = QuestionnaireResult::model()->findAll($criteria);
	    
	    
	    $this->render ( '//questionnaire/result', array (
	        'data' => $result
	    ) );
	}
	
	public function loadModel() {
		if ($this->_model === null) {
			if (isset ( $_GET ['id'] )) {
				$id = addslashes ( $_GET ['id'] );
				$this->_model = MTitle::model ()->findbyPk ( $id );
			}
			if ($this->_model === null)
				throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
		return $this->_model;
	}
}