<?php
session_start ();
class UserLoginUtils {
	const ADMIN = "ADMIN";
	const STAFF = "STAFF";
	const USER = "USER";
	const EXCUTIVE = "EXCUTIVE";
	public static function isLogin() {
		return isset ( $_SESSION ['USER_LOGIN_ID'] );
	}
	public static function logout() {
		unset ( $_SESSION ['USER_LOGIN_ID'] );
		unset ( $_SESSION ['USER_INFO'] );
		unset ( $_SESSION ['USER_ROLE'] );
		unset ( $_SESSION ['FAIL_MESSAGE'] );
		unset ( $_SESSION ['MENU_IN_ROLE'] );
	}
	public static function authen($username, $password) {
		$criteria = new CDbCriteria ();
		$criteria->condition = "username = '" . $username . "' and password='" . md5 ( $password ) . "' and status='A'";
		$UsersLogin = UsersLogin::model ()->findAll ( $criteria );
		if (isset ( $UsersLogin [0] )) {
			$_SESSION ['USER_INFO'] = serialize ( $UsersLogin [0] );
			
			$_SESSION ['USER_LOGIN_ID'] = $UsersLogin [0]->id;
			$_SESSION ['USER_ROLE'] = $UsersLogin [0]->role_id;
			$_SESSION ['USER_ROLE_NAME'] = $UsersLogin [0]->users_role->ROLE_NAME;
			$_SESSION ['IS_FORCE_CHANGE_PASSWORD'] = ($UsersLogin [0]->is_force_change_password == "1" ? true : false);
			
			// Get Menu By Role
			$cri = new CDbCriteria ();
			$cri->condition = " ROLE_ID=" . $UsersLogin [0]->role_id . " AND IS_ACTIVE='1'";
			
			$mroles = MenuRole::model ()->findAll ( $cri );
			if (isset ( $mroles )) {
				$listOfMenuInRole = '';
				foreach ( $mroles as $mr ) {
					$listOfMenuInRole .= $mr->MENU_ID . ',';
				}
				if (strlen ( $listOfMenuInRole ) > 0) {
					$criMP = new CDbCriteria ();
					$criMP->condition = " MENU_ID in (" . rtrim ( $listOfMenuInRole, ',' ) . ")";
					$criMP->order = 'DISPLAY_ORDER ASC';
					$Menus = Menu::model ()->findAll ( $criMP );
					$_SESSION ['MENU_IN_ROLE'] = serialize ( $Menus );
				}
			}
			
			// -------------------
			return true;
		} else {
			$_SESSION ['FAIL_MESSAGE'] = 'Incorrect Username or Password!';
			return false;
		}
	}
	public static function isForceChangePassword() {
		if (isset ( $_SESSION ['IS_FORCE_CHANGE_PASSWORD'] )) {
			return $_SESSION ['IS_FORCE_CHANGE_PASSWORD'];
		} else {
			return false;
		}
	}
	public static function getUserRole() {
		if (isset ( $_SESSION ['USER_ROLE'] )) {
			return $_SESSION ['USER_ROLE'];
		} else {
			return - 1;
		}
	}
	public static function getUsersLoginId() {
		if (isset ( $_SESSION ['USER_LOGIN_ID'] )) {
			return $_SESSION ['USER_LOGIN_ID'];
		} else {
			return - 1;
		}
	}
	public static function getUserInfo() {
		if (isset ( $_SESSION ['USER_INFO'] )) {
			return unserialize ( $_SESSION ['USER_INFO'] );
		} else {
			return null;
		}
	}
	public static function getUserRoleName() {
		if (isset ( $_SESSION ['USER_ROLE_NAME'] )) {
			return $_SESSION ['USER_ROLE_NAME'];
		} else {
			return null;
		}
	}
	public static function getMenuInRole() {
		if (isset ( $_SESSION ['MENU_IN_ROLE'] )) {
			return unserialize ( $_SESSION ['MENU_IN_ROLE'] );
		} else {
			return null;
		}
	}
	public static function getLoginInfo() {
		if (self::isLogin ()) {
			$UsersLogin = self::getUserInfo ();
			return "<i class=\"fa fa-user\"></i>" . $UsersLogin->first_name . "  " . $UsersLogin->last_name . " <i class=\"fa fa-key\"></i>(" . strtolower ( $UsersLogin->users_role->ROLE_NAME ) . ")";
		} else {
			return '';
		}
	}
	public static function getDepartmentInfo(){
		if (self::isLogin ()) {
			$UsersLogin = self::getUserInfo ();
			return "<i class=\"fa fa-building\"></i><font color=\"orange\"> ".$UsersLogin->department->name."</font>";
		} else {
			return '';
		}
	}
	public static function getDepartmentInfoName(){
	    if (self::isLogin ()) {
	        $UsersLogin = self::getUserInfo ();
	        return $UsersLogin->department->name;
	    } else {
	        return '';
	    }
	}
	public static function canCreate($cur_page) {
		if (! self::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		$result = false;
		$cri = new CDbCriteria ();
		$currentPage = str_replace ( ConfigUtil::getAppName (), "", $cur_page );
		$link = explode ( "/", $currentPage );
		
		$cri->condition = "URL_NAVIGATE = '/" . $link [1] . '/' . $link [2] . "'";
		
		// echo "<font color='red'>".$link [1] . '/' . $link [2]."</font>";
		
		$menus = Menu::model ()->findAll ( $cri );
		if (isset ( $menus [0] )) {
			
			$cri1 = new CDbCriteria ();
			$cri1->condition = " MENU_ID = " . $menus [0]->MENU_ID . " AND ROLE_ID=" . self::getUserRole ();
			$menuRoles = MenuRole::model ()->findAll ( $cri1 );
			if (isset ( $menuRoles )) {
				
				$result = $menuRoles [0]->IS_CREATE;
			}
		}
		
		return $result;
	}
	public static function canUpdate($cur_page) {
		if (! self::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		
		$result = false;
		$cri = new CDbCriteria ();
		$currentPage = str_replace ( ConfigUtil::getAppName (), "", $cur_page );
		$link = explode ( "/", $currentPage );
		
		$cri->condition = "URL_NAVIGATE = '/" . $link [1] . '/' . $link [2] . "'";
		
		$menus = Menu::model ()->findAll ( $cri );
		if (isset ( $menus [0] )) {
			
			$cri1 = new CDbCriteria ();
			$cri1->condition = " MENU_ID = " . $menus [0]->MENU_ID . " AND ROLE_ID=" . self::getUserRole ();
			$menuRoles = MenuRole::model ()->findAll ( $cri1 );
			if (isset ( $menuRoles )) {
				
				$result = $menuRoles [0]->IS_EDIT;
			}
		}
		
		return $result;
	}
	public static function canDelete($cur_page) {
		if (! self::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		$result = false;
		$cri = new CDbCriteria ();
		$currentPage = str_replace ( ConfigUtil::getAppName (), "", $cur_page );
		$link = explode ( "/", $currentPage );
		
		$cri->condition = "URL_NAVIGATE = '/" . $link [1] . '/' . $link [2] . "'";
		
		$menus = Menu::model ()->findAll ( $cri );
		if (isset ( $menus [0] )) {
			
			$cri1 = new CDbCriteria ();
			$cri1->condition = " MENU_ID = " . $menus [0]->MENU_ID . " AND ROLE_ID=" . self::getUserRole ();
			$menuRoles = MenuRole::model ()->findAll ( $cri1 );
			if (isset ( $menuRoles )) {
				
				$result = $menuRoles [0]->IS_DELETE;
			}
		}
		
		return $result;
	}
	public static function getUsersLoginById($user_id) {
		$UsersLogin = UsersLogin::model ()->findByPk ( $user_id );
		return $UsersLogin;
	}
	public static function authorizePage($cur_page) {
		if (! self::isLogin ()) {
			$this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
		}
		$cur_page = str_replace ( ConfigUtil::getAppName (), "", $cur_page );
		$link = explode ( "/", $cur_page );
		
		// echo "<font color='red'>" . $cur_page . "</font>";
		
		$cri = new CDbCriteria ();
		$cri->condition = "URL_NAVIGATE = '/" . $link [1] . '/' . $link [2] . "'";
		
		$menus = Menu::model ()->findAll ( $cri );
		if (isset ( $menus [0] )) {
			
			$cri1 = new CDbCriteria ();
			$cri1->condition = " MENU_ID = " . $menus [0]->MENU_ID . " AND ROLE_ID=" . self::getUserRole () . " AND IS_ACTIVE=1";
			$menuRoles = MenuRole::model ()->findAll ( $cri1 );
			if (isset ( $menuRoles [0] )) {
				return $menuRoles [0]->IS_ACTIVE;
			} else {
				return null;
			}
		} else {
			return null;
		}
	}
}
?>