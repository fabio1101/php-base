<?php

namespace CORE;

require 'libs/PHPMailer/PHPMailerAutoload.php';
class Mail_old {
	public $mail;
	public $error;
	public function __construct() {
		$this->mail = new \PHPMailer ();
		
		$this->mail->SMTPDebug = false; // Enable verbose debug output
		
		$this->mail->isSMTP (); // Set mailer to use SMTP
		$this->mail->Host = M_SERVER; // Specify main and backup SMTP servers
		$this->mail->SMTPAuth = true; // Enable SMTP authentication
		$this->mail->Username = M_USERNAME; // SMTP username
		$this->mail->Password = M_PASSWORD; // SMTP password
		$this->mail->SMTPSecure = M_CONN; // Enable TLS encryption, `ssl` also accepted
		$this->mail->Port = M_PORT; // TCP port to connect to
		$this->mail->From = M_EMAIL;
		$this->mail->FromName = M_NAME;
	}
	
	/**
	 *
	 * @param :: $to
	 *        	(correo destino), $subject (asunto), $content (contenido del mensaje)
	 * @method crearMensaje :: crea el mensaje con los parametros de configuracion creando un mailer que la variable global transport y luego envia el correo
	 *        
	 */
	public function sendMail($to, $subject, $contentHTML) {
		
		if (is_array($to)){
			foreach ($to as $mail){
				$this->mail->addAddress ( $mail ); // Add a recipient
			}
		} else {
			$this->mail->addAddress ( $to ); // Add a recipient
		}
		
		$this->mail->isHTML ( true ); // Set email format to HTML
		
		$this->mail->Subject = $subject;
		$this->mail->Body = $contentHTML;
		// $this->mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
		
		if (! $this->mail->send ()) {
			$this->error = 'Mailer Error: ' . $this->mail->ErrorInfo;
			echo $this->error;
			return false;
		} else {
			return true;
		}
	}
}