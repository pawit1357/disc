<?php
class AjaxRequestController extends CController {
	public $layout = 'ajax';
	private $_model;
	public function actionIndex() {
	}
	public function actionGetAmphur($province_id) {
		
		// Create connection
		mysql_connect ( ConfigUtil::getHostName (), ConfigUtil::getUsername (), ConfigUtil::getPassword () );
		mysql_select_db ( ConfigUtil::getDbName () );
		
		$json = array ();
		$sql = "SELECT AMPHUR_ID,AMPHUR_NAME FROM tb_m_amphur WHERE PROVINCE_ID = " . $province_id;
		
		if ($result = mysql_query ( $sql )) {
			while ( $item = mysql_fetch_assoc ( $result ) ) {
				$json [] = $item;
			}
		} else {
			print mysql_error ();
		}
		echo json_encode ( $json );
	}
	public function actionGetTambon($amphur_id) {
		
		// Create connection
		mysql_connect ( ConfigUtil::getHostName (), ConfigUtil::getUsername (), ConfigUtil::getPassword () );
		mysql_select_db ( ConfigUtil::getDbName () );
		
		$json = array ();
		$sql = "SELECT DISTRICT_ID,DISTRICT_NAME FROM tb_m_district WHERE amphur_id = " . $amphur_id;
		
		if ($result = mysql_query ( $sql )) {
			while ( $item = mysql_fetch_assoc ( $result ) ) {
				$json [] = $item;
			}
		} else {
			print mysql_error ();
		}
		echo json_encode ( $json );
	}
	public function actionCheckEmail($email) {
		$criteria = new CDbCriteria ();
		$criteria->condition = "(email = :email)";
		$criteria->params = array (
				":email" => $email 
		);
		
		$users = UsersLogin::model ()->findAll ( $criteria );
		echo (empty($users [0]->id)? 'true' : 'false'); //
			                                           // echo CJSON::encode(CommonUtil::convertModelToArray($users));
	}
	public function actionCheckUser($user) {
		$criteria = new CDbCriteria ();
		$criteria->condition = "(username = :username)";
		$criteria->params = array (
				":username" => $user 
		);
		
		$users = UsersLogin::model ()->findAll ( $criteria );
		echo (empty($users [0]->id)? 'true' : 'false'); //
		
		// isset($users)? 'true':'false';//
// 		echo CJSON::encode ( CommonUtil::convertModelToArray ( $users ) );
	}
}