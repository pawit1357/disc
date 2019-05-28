<?php
class MailUtil {
	public static function sendMail($to_email, $subject, $content) {
		
		$mail = Yii::app ()->Smtpmail;
		$mail->IsSMTP ();
		$mail->SetFrom ( 'pawit1357@17100532-20161115132017.webstarterz.com', 'MU-LABBASE' );
		$mail->Subject = $subject;
		$mail->MsgHTML ( $content );
		$mail->CharSet = 'utf-8';
		$mail->ClearAddresses ();
		$mail->AddAddress ( $to_email, "" );
		if ($mail->Send ()) {
			return true;
		} else {
			return false; 
		}
		return true;
	}
}
?>
