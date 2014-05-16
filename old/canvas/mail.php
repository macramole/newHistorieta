<?php
include_once 'class.phpmailer.php';
include_once 'class.smtp.php';

class mail {
	public static function sendMessage($to, $subject, $body, $attachments = array()) {
		
		// Inicio un mensaje nuevo
		$mail = new PHPMailer();
		$mail->IsSMTP(); 	// telling the class to use SMTP
		$mail->Host 		= config::$mailServer;
		if(config::$mailUser!="")
		{
			$mail->SMTPAuth 	= true;
			$mail->Username 	= config::$mailUser;
			$mail->Password 	= config::$mailPassword;
		}
		$mail->From       	= config::$mailAccount;
		$mail->FromName   	= utf8_decode( config::$mailAccountName );
		$mail->SMTPDebug 	= false;
		
		//No acepto mas de un destinatario
		$destinatarios = explode(";", str_replace(array(","," "), array(",",""), $to));
		$partes_mail = explode("<",$destinatarios[0]);
		if(count($partes_mail)>1)
		{
			//Al email le quito el > del final
			$mail->AddAddress(substr($partes_mail[1],0,strlen($partes_mail[1])-1),$partes_mail[0]);
		}
		else
		{
			$mail->AddAddress($partes_mail[0]);
		}
				
		//Declaro el contenido
		$base_path = pathinfo(__FILE__,PATHINFO_DIRNAME);
		$base_path = substr($base_path, 0, strrpos($base_path, "/"));
		$mail->MsgHTML(utf8_decode($body), $base_path);
		$mail->Subject    	= utf8_decode($subject);
		
		//Envio el mensaje
		$ret_val = $mail->Send();
		return $ret_val;
	}
}