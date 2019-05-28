<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class SiteController extends CController
{

    public $layout = '_login';

    private $_model;

    public function actionIndex()
    {
        // Authen Login
        if (! UserLoginUtils::isLogin ()) {
        $this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
        }
        // Render
        $this->redirect ( Yii::app ()->createUrl ( 'DashBoard/' ) );
//         $this->render('//site/login');
    }

    /**
     * Login Page
     */
    public function actionLogin()
    {
        // if login redirect to index
        // if (UserLoginUtils::isLogin ()) {
        // $this->redirect ( Yii::app ()->createUrl ( '' ) );
        // }
        if (isset($_POST['UsersLogin']['username']) && isset($_POST['UsersLogin']['password'])) {
            
            $username = addslashes($_POST['UsersLogin']['username']);
            $password = addslashes($_POST['UsersLogin']['password']);
            
            // Authen
            if (UserLoginUtils::authen($username, $password)) {
                if (UserLoginUtils::isForceChangePassword()) {
                    $this->redirect(Yii::app()->createUrl('Site/ChangePassword/'));
                } else {
                    $this->redirect(Yii::app()->createUrl('DashBoard/'));
                }
            } else {
                $this->redirect(Yii::app()->createUrl('Site/Login'));
            }
        }
        $this->render('//site/login');
    }

    public function actionReg()
    {
        // if login redirect to index
        // if (UserLoginUtils::isLogin ()) {
        // $this->redirect ( Yii::app ()->createUrl ( '' ) );
        // }
        if (isset($_POST['UsersLogin'])) {
            $transaction = Yii::app()->db->beginTransaction();
            $rowpassword = '';
            // Add Request
            $model = new UsersLogin();
            $model->attributes = $_POST['UsersLogin'];
            $model->password = md5($model->password);
            $model->role_id = 5;
            $model->email = $model->username;
            $model->is_force_change_password = 0;
            $model->create_by = UserLoginUtils::getUsersLoginId();
            $model->create_date = date("Y-m-d H:i:s");
            $model->update_by = UserLoginUtils::getUsersLoginId();
            $model->update_date = date("Y-m-d H:i:s");
            $model->save();
            
            $mailBody = "Hello " . $model->first_name . '  ' . $model->last_name . ",<br>
You have a new account at http://myapps1357.com/muirs <br>
Account details: <br><br>
Username <br>
" . $model->email . " 
<br>
<br>
Password <br>
" . $rowpassword;
            $strSubject = "=?UTF-8?B?" . base64_encode(ConfigUtil::getEmailSubject()) . "?=";
            MailUtil::sendMail($model->username, $strSubject, $mailBody);
            
            // echo "SAVE";
            $transaction->commit();
            
            // $username = addslashes ( $_POST ['UsersLogin'] ['username'] );
            // $password = addslashes ( $_POST ['UsersLogin'] ['password'] );
            
            // Authen
            // if (UserLoginUtils::authen ( $username, $password )) {
            // if (UserLoginUtils::isForceChangePassword ()) {
            // $this->redirect ( Yii::app ()->createUrl ( 'Site/ChangePassword/' ) );
            // } else {
            // $this->redirect ( Yii::app ()->createUrl ( 'DashBoard/' ) );
            // }
            // } else {
            $this->redirect(Yii::app()->createUrl('Site/Login'));
            // }
        }
        $this->render('//site/reg');
    }

    /**
     * Logout
     */
    public function actionLogout()
    {
        UserLoginUtils::logout();
        $this->redirect(Yii::app()->createUrl('Site/Login'));
    }

    public function actionChangePassword()
    {
        
        // Authen Login
        // if (! UserLoginUtils::isLogin ()) {
        // $this->redirect ( Yii::app ()->createUrl ( 'Site/login' ) );
        // }
        if (isset($_POST['UsersLogin'])) {
            $update_model = UsersLogin::model()->findbyPk(UserLoginUtils::getUsersLoginId());
            
            $model = UsersLogin::model();
            $transaction = Yii::app()->db->beginTransaction();
            $model->attributes = $_POST['UsersLogin'];
            $update_model->password = md5($model->password);
            // $update_model->update_by = UserLoginUtils::getUsersLoginId ();
            // $update_model->update_date = date ( "Y-m-d H:i:s" );
            $update_model->is_force_change_password = 0;
            
            $update_model->update();
            $transaction->commit();
            
            $this->redirect(Yii::app()->createUrl('Site/LogOut'));
        } else {
            
            // $model = $this->loadModel ();
            $this->render('//site/change_password');
        }
    }

    public function actionForgotPassword()
    {
        if (isset($_POST['UsersLogin'])) {
            
            $model = UsersLogin::model();
            $transaction = Yii::app()->db->beginTransaction();
            $model->attributes = $_POST['UsersLogin'];
            
            if (CommonUtil::IsNullOrEmptyString($model->email)) {
                $_SESSION['FAIL_MESSAGE'] = "เน�เธ�เธฃเธ”เธ�เน�เธญเธ�เธญเธตเน€เธกเธฅเน�";
                $this->render('//site/forgot_password');
            } else {
                
                $cri = new CDbCriteria();
                $cri->condition = " t.email = '" . $model->email . "'";
                $user = UsersLogin::model()->findAll($cri);
                
                if (isset($user[0])) {
                    $user[0]->password = md5('1234');
                    $user[0]->is_force_change_password = 1;
                    $user[0]->update();
                    
                    //
                    $mailBody = "Hello " . $model->first_name . '  ' . $model->last_name . ",<br>
Now the system initial your new password to 1234 <br>
Please login with initial password and change to the new one";
                    
                    $strSubject = "=?UTF-8?B?" . base64_encode(ConfigUtil::getEmailSubject()) . "?=";
                    MailUtil::sendMail($model->username, $strSubject, $mailBody);
                    
                    // $strSubject = "=?UTF-8?B?" . base64_encode ( ConfigUtil::getEmailSubject () ) . "?=";
                    // MailUtil::sendMail ( $model->email, $strSubject, self::GetForgotEmailTemplate ( '1234', $model->email ) );
                    
                    $transaction->commit();
                }
                
                // เธชเน�เธ�เน€เธกเธฅเน� เธฃเธซเธฑเธชเธ�เน�เธฒเธ�เน�เธซเธกเน�
                $this->render('//site/sendemail_success');
            }
        } else {
            
            // $model = $this->loadModel ();
            $this->render('//site/forgot_password');
        }
    }

}