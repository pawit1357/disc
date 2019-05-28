<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class UsersRoleController extends CController {
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
		
		$model = new UsersRole ();
		$this->render ( '//usersrole/main', array (
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
		if (isset ( $_POST ['UsersRole'] )) {
			
			$transaction = Yii::app ()->db->beginTransaction ();
			$listOfActive = isset ( $_POST ['listOfActive'] ) ? $_POST ['listOfActive'] : NULL;
			$listOfCreate = isset ( $_POST ['listOfCreate'] ) ? $_POST ['listOfCreate'] : NULL;
			$listOfEdit = isset ( $_POST ['listOfEdit'] ) ? $_POST ['listOfEdit'] : NULL;
			$listOfDelete = isset ( $_POST ['listOfDelete'] ) ? $_POST ['listOfDelete'] : NULL;
			
			$model = new UsersRole ();
			$model->attributes = $_POST ['UsersRole'];
			$model->UPDATE_BY = UserLoginUtils::getUsersLoginId ();
			$model->CREATE_DATE = date ( "Y-m-d H:i:s" );
			$model->UPDATE_DATE = date ( "Y-m-d H:i:s" );
			$model->save ();
			
// 			$role_id = UsersRole::getMax();
// 			// // ADD NEW
			$dataProvider = Menu::model ()->search ();
			// $index = 1;
// 			$rid = MenuRole::getMax();
			foreach ( $dataProvider->data as $menu ) {
				
				$menu_role = new MenuRole ();
				$menu_role->ROLE_ID =$model->ROLE_ID;
				$menu_role->MENU_ID = $menu->MENU_ID;
				$menu_role->IS_ACTIVE = false;
				$menu_role->IS_REQUIRED_ACTION = false;
				$menu_role->IS_CREATE = false;
				$menu_role->IS_EDIT = false;
				$menu_role->IS_DELETE = false;
				
				if (isset ( $listOfActive )) {
					foreach ( $listOfActive as $key => $value1 ) {
						if ($key == $menu->MENU_ID) {
							$menu_role->IS_ACTIVE = true;
							break;
						}
					}
				}
// 				if ($listOfCreate != NULL) {
					if (isset ( $listOfCreate )) {
						foreach ( $listOfCreate as $key => $value1 ) {
							if ($key == $menu->MENU_ID) {
								$menu_role->IS_CREATE = true;
								break;
							}
						}
					}
// 				}
				if (isset ( $listOfEdit )) {
					foreach ( $listOfEdit as $key => $value1 ) {
						if ($key == $menu->MENU_ID) {
							$menu_role->IS_EDIT = true;
							break;
						}
					}
				}
				if (isset ( $listOfDelete )) {
					foreach ( $listOfDelete as $key => $value1 ) {
						if ($key == $menu->MENU_ID) {
							$menu_role->IS_DELETE = true;
							break;
						}
					}
				}
				$menu_role->UPDATE_BY = UserLoginUtils::getUsersLoginId ();
				$menu_role->CREATE_DATE = date ( "Y-m-d H:i:s" );
				$menu_role->UPDATE_DATE = date ( "Y-m-d H:i:s" );
				$menu_role->save ();
			}
			
			$transaction->commit ();
			
			$this->redirect ( Yii::app ()->createUrl ( 'UsersRole' ) );
		} else {
			$model = new UsersRole ();
			$this->render ( '//usersrole/create', array (
					'data' => $model 
			) );
		}
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
		
		if (isset ( $_POST ['UsersRole'] )) {
			
			$transaction = Yii::app ()->db->beginTransaction ();
			$listOfActive = isset ( $_POST ['listOfActive'] ) ? $_POST ['listOfActive'] : NULL;
			$listOfCreate = isset ( $_POST ['listOfCreate'] ) ? $_POST ['listOfCreate'] : NULL;
			$listOfEdit = isset ( $_POST ['listOfEdit'] ) ? $_POST ['listOfEdit'] : NULL;
			$listOfDelete = isset ( $_POST ['listOfDelete'] ) ? $_POST ['listOfDelete'] : NULL;
			
			$model->attributes = $_POST ['UsersRole'];
			$model->UPDATE_BY = UserLoginUtils::getUsersLoginId ();
			$model->CREATE_DATE = date ( "Y-m-d H:i:s" );
			$model->UPDATE_DATE = date ( "Y-m-d H:i:s" );
			$model->update ();
			
			// Delete old role
			$cri = new CDbCriteria ();
			$cri->condition = " ROLE_ID=" . $model->ROLE_ID;
			MenuRole::model ()->deleteAll ( $cri );
			// ADD NEW
			$dataProvider = Menu::model ()->search ();
			$index = 1;
			foreach ( $dataProvider->data as $menu ) {
				
				$menu_role = new MenuRole ();
				$menu_role->ROLE_ID = $model->ROLE_ID;
				$menu_role->MENU_ID = $menu->MENU_ID;
				$menu_role->IS_ACTIVE = false;
				$menu_role->IS_REQUIRED_ACTION = false;
				$menu_role->IS_CREATE = false;
				$menu_role->IS_EDIT = false;
				$menu_role->IS_DELETE = false;
				
				//
				
				if (isset ( $listOfActive )) {
					foreach ( $listOfActive as $key => $value1 ) {
						// echo "-------#" . $key . "," . $value1 . "<br>";
						// echo 'ACITVE:' . $key . ':' . $menu->MENU_ID . '';
						
						if ($key == $menu->MENU_ID) {
							$menu_role->IS_ACTIVE = true;
							// echo 'ACITVE:' . $key . ':' . $menu->MENU_ID . '';
							break;
						}
					}
				}
				
				if (isset ( $listOfCreate )) {
					foreach ( $listOfCreate as $key => $value1 ) {
						if ($key == $menu->MENU_ID) {
							// echo 'ACITVE:'.$item->MENU_ID.':'.$menu_role->IS_ACTIVE.'<br>'; if ($item == $menu->MENU_ID) {
							$menu_role->IS_CREATE = true;
							break;
						}
					}
				}
				if (isset ( $listOfEdit )) {
					foreach ( $listOfEdit as $key => $value1 ) {
						if ($key == $menu->MENU_ID) {
							// echo 'ACITVE:'.$item->MENU_ID.':'.$menu_role->IS_ACTIVE.'<br>'; if ($item == $menu->MENU_ID) {
							$menu_role->IS_EDIT = true;
							break;
						}
					}
				}
				if (isset ( $listOfDelete )) {
					foreach ( $listOfDelete as $key => $value1 ) {
						if ($key == $menu->MENU_ID) {
							// echo 'ACITVE:'.$item->MENU_ID.':'.$menu_role->IS_ACTIVE.'<br>'; if ($item == $menu->MENU_ID) {
							$menu_role->IS_DELETE = true;
							break;
						}
					}
				}
				$menu_role->UPDATE_BY = UserLoginUtils::getUsersLoginId ();
				$menu_role->CREATE_DATE = date ( "Y-m-d H:i:s" );
				$menu_role->UPDATE_DATE = date ( "Y-m-d H:i:s" );
				$menu_role->save ();
			}
			
			$transaction->commit ();
			
			$this->redirect ( Yii::app ()->createUrl ( 'UsersRole' ) );
		} else {
			$this->render ( '//usersrole/update', array (
					'data' => $model 
			) );
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
		$transaction = Yii::app ()->db->beginTransaction ();
		$model = $this->loadModel ();
		
		// delete fk
		$cri = new CDbCriteria ();
		$cri->condition = " ROLE_ID=" . $model->ROLE_ID;
		MenuRole::model ()->deleteAll ( $cri );
		// delete pk
		$model->delete ();
		$transaction->commit ();
		
		$this->redirect ( Yii::app ()->createUrl ( 'UsersRole/' ) );
	}
	public function loadModel() {
		if ($this->_model === null) {
			if (isset ( $_GET ['id'] )) {
				$id = addslashes ( $_GET ['id'] );
				$this->_model = UsersRole::model ()->findbyPk ( $id );
			}
			if ($this->_model === null)
				throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
		return $this->_model;
	}
}