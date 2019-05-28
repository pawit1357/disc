<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class UsersController extends CController {
	public $layout = '_main';
	private $_model;
	
	/**
	 * Index action is the default action in a controller.
	 */
	public function actionIndex() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}
		$model = new UsersLogin ();
		$this->render ( '//users/main', array (
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
		
		if (isset ( $_POST ['UsersLogin'] )) {
			
			$transaction = Yii::app ()->db->beginTransaction ();
			// Add Request
			$model = new UsersLogin ();
			$model->attributes = $_POST ['UsersLogin'];
			$model->password = md5 ( $model->password );
			$model->is_force_change_password = 1;
			$model->create_by = UserLoginUtils::getUsersLoginId ();
			$model->create_date = date ( "Y-m-d H:i:s" );
			$model->update_by = UserLoginUtils::getUsersLoginId ();
			$model->update_date = date ( "Y-m-d H:i:s" );
			$model->save ();
			// echo "SAVE";
			$transaction->commit ();
			
			// $transaction->rollback ();
			$this->redirect ( Yii::app ()->createUrl ( 'Users' ) );
		} else {
			// Render
			$this->render ( '//users/create' );
		}
	}
	public function actionDelete() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}
		$model = $this->loadModel ();
		$model->delete ();
		$this->redirect ( Yii::app ()->createUrl ( 'Users/' ) );
	}
	public function actionUpdate() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}
		$model = $this->loadModel ();
		if (isset ( $_POST ['UsersLogin'] )) {
			$transaction = Yii::app ()->db->beginTransaction ();
			$model->attributes = $_POST ['UsersLogin'];
			$model->password = md5 ( $model->password );
			$model->update_by = UserLoginUtils::getUsersLoginId ();
			$model->update_date = date ( "Y-m-d H:i:s" );
			$model->update ();
			$transaction->commit ();
			
			$this->redirect ( Yii::app ()->createUrl ( 'Users' ) );
		}
		$this->render ( '//users/update', array (
				'data' => $model 
		) );
	}
	public function loadModel() {
		if ($this->_model === null) {
			if (isset ( $_GET ['id'] )) {
				$id = addslashes ( $_GET ['id'] );
				$this->_model = UsersLogin::model ()->findbyPk ( $id );
			}
			if ($this->_model === null)
				throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
		return $this->_model;
	}
	public function actionMyProfile() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
// 		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
// 			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
// 		}
		if (isset ( $_POST ['UsersLogin'] )) {
			$transaction = Yii::app ()->db->beginTransaction ();
			
			$model = $this->loadModel ();
			$model->attributes = $_POST ['UsersLogin'];
			$model->password = md5 ( $model->password );
// 			$model->update_by = UserLoginUtils::getUsersLoginId ();
// 			$model->update_date = date ( "Y-m-d H:i:s" );
			$model->update ();
			$transaction->commit ();
			
			$this->redirect ( Yii::app ()->createUrl ( 'Users/UpdateResult' ) );
		} else {
			
			$model = $this->loadModel ();
			$this->render ( '//users/myProfile', array (
					'data' => $model 
			) );
		}
	}
	public function actionUpdateResult() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		if (! UserLoginUtils::authorizePage ( $_SERVER ['REQUEST_URI'] )) {
			$this->redirect ( Yii::app ()->createUrl ( 'DashBoard/Permission' ) );
		}
		$this->render ( '//users/result' );
	}
	public function actionForceChangePassword() {
		$model = $this->loadModel ();
		
		$transaction = Yii::app ()->db->beginTransaction ();
		$model->password = md5 ( '1234' );
		$model->is_force_change_password = 1;
// 		$model->update_by = UserLoginUtils::getUsersLoginId ();
// 		$model->update_date = date ( "Y-m-d H:i:s" );
		$model->update ();
		$transaction->commit ();
		
		$this->redirect ( Yii::app ()->createUrl ( 'Users/' ) );
	}
}