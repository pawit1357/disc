<?php
return array(
    'name' => 'disc',
    'defaultController' => 'site',
    'import' => array(
        'application.models.*',
        'application.components.*'
    ),
    'components' => array(
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => true
        ),
        'db' => array(
            'class' => 'CDbConnection',
            'connectionString' => 'mysql:host=localhost;dbname=salayateac_disc',
            'emulatePrepare' => true,
//             'username' => 'salayateac_disc',
//             'password' => '9bNMMbbwRke3',
            'username' => 'root',
            'password' => 'P@ssw0rd',
            'charset' => 'utf8'
        ),
        'Smtpmail' => array(
            'class' => 'application.extensions.smtpmail.PHPMailer',
            'Host' => "",
            'Username' => '',
            'Password' => '',
            'Mailer' => 'smtp',
            'Port' => 465,
            'SMTPAuth' => true,
            'SMTPSecure' => 'ssl',
            'SMTPDebug' => 1
        )
    )

);
?>