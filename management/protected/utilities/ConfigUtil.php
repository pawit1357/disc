<?php
class ConfigUtil {
	private static $emailSubject = 'DISC MANAGEMENT';
// 	private static $hostName = 'http://localhost:88/';
// 	private static $siteName = 'http://localhost:88/disc/management/index.php/Site/LogOut';
	
	private static $hostName = 'https://www.salayatea.com/';
	private static $siteName = 'https://www.salayatea.com/disc/index.php/Site/LogOut';
	
	
	
	private static $ApplicationTitle = 'DISC | SALAYA TEA DISC';
	private static $ApplicationCopyRight = '2012 &copy;';
	private static $ApplicationAddress = '';
	private static $ApplicationUpdateVersion = '<li class="fa fa-clock-o"></li><span> &nbsp;Lasted Update 2017-04-22</span>';
// 	private static $AppName = '';	
	private static $AppName = '/disc/management';
	
	private static $btnAddButton = 'เพิ่มข้อมูล';
	private static $btnSaveButton = 'บันทึก';
	private static $btnCancelButton = 'ยกเลิก';
	private static $btnCloseButton = 'ปิด';
	private static $portletTheme ='portlet box blue-hoki';
	

	private static $defaultPageSize = 200;

	public static function getDbName() {
		$str = Yii::app()->db->connectionString;
		list($host, $db) = explode(';', $str);
		list($xx, $dbName) = explode('=', $db);
		return $dbName;
	}
	public static function getHostName() {
		$str = Yii::app()->db->connectionString;
		list($host, $db) = explode(';', $str);
		list($xx, $hostName) = explode('=', $host);
		return $hostName;
	}
	public static function getPortletTheme()
	{
	    return self::$portletTheme;
	}
	public static function getBtnAddName(){
		return self::$btnAddButton;	
	}
	public static function getBtnCloseName(){
		return self::$btnCloseButton;
	}
	public static function getBtnSaveButton(){
		return self::$btnSaveButton;
	}
	public static function getBtnCancelButton(){
		return self::$btnCancelButton;
	}
	
	public static function getUsername() {
		return Yii::app()->db->username;
	}
	public static function getPassword() {
		return Yii::app()->db->password;
	}
	public static function getSiteName() {
		return self::$siteName;
	}

	public static function getAppName() {
		return self::$AppName;
	}
	public static function getApplicationTitle() {
		return self::$ApplicationTitle;
	}
	public static function getApplicationCopyRight() {
		return self::$ApplicationCopyRight;
	}
	public static function getApplicationAddress() {
		return self::$ApplicationAddress;
	}
	
	public static function getApplicationUpdateVersion() {
		return self::$ApplicationUpdateVersion;
	}
	
	public static function getDefaultPageSize() {
		return self::$defaultPageSize;
	}
	public static function getUrlHostName() {
		return self::$hostName;
	}
	
	/* EMAIL */
	public static function getEmailSubject() {
		return self::$emailSubject;
	}
	public static function getEmailTemplatePath() {
		return self::$hostName.''.self::$AppName.'/template/email_template.php';
	}
	public static function getEmailForgotTemplatePath() {
		return self::$hostName.''.self::$AppName.'/template/email_forgot_template.php';
	}
}
?>