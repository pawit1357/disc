<?php
/**
 * SiteController is the default controller to handle user requests.
 */
class DashBoardController extends CController {
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
		$this->render ( '//dashboard/main' );
	}
	public function actionPermission() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		$this->render ( '//dashboard/permission' );
	}
	public function actionNotFoundData() {
		// Authen Login
		if (! UserLoginUtils::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
	
		$this->render ( '//dashboard/404' );
	}

	
}