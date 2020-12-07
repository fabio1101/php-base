<?php

namespace CORE;

require_once 'libs/swift-mailer/lib/swift_required.php';

class Mail {

	public	$transport;	// SwiftTransport Agent
	public	$mailer;	// SwiftMailer Agent
	public 	$message;	// SwiftMessage
	
	public function __construct() {
		$this->transport = \Swift_SmtpTransport::newInstance( M_SERVER, M_PORT, M_CONN)
				->setUsername(M_USERNAME)
				->setPassword(M_PASSWORD);
		try{
			$this->transport->start();
		} catch (\Swift_SwiftException $e){
			@file_put_contents(APP_PATH . '/' . APP_PUBLIC.'/'.APP_LOGFILE, 
                date('Ymd_his') . ' - ' . $e->getMessage() . ' - ' . $e->getFile() . ' - ' . $e->getLine(). PHP_EOL, FILE_APPEND);
		}
	}

	public function sendMail($to, $subject, $contentHTML) {
		// @todo implement \Swift_Validate::email($to); => need to verifiy if only one mail or array
		$this->message = \Swift_Message::newInstance();
		$this->message->setFrom(array(M_EMAIL => M_NAME));
		try{
			if (is_array($to)){
				$this->message->setTo($to);
			} else {
				$this->message->setTo(array($to));
			}
		} catch (\Swift_SwiftException $e){
            @file_put_contents(APP_PATH . '/' . APP_PUBLIC.'/'.APP_LOGFILE, 
                date('Ymd_his') . ' - ' . $e->getMessage() . ' - ' . $e->getFile() . ' - ' . $e->getLine(). PHP_EOL, FILE_APPEND);
			return 0;
		}
		$failure = false;
		$this->message->setSubject($subject);
		$this->message->setBody($contentHTML,'text/html');
		$this->mailer	= \Swift_Mailer::newInstance($this->transport);
		try{
			if (!$this->mailer->send($this->message, $failure)) {
                @file_put_contents(APP_PATH.'/'.APP_PUBLIC.'/'.APP_LOGFILE, date('Ymdhis') . ' - ' . $failure.PHP_EOL, FILE_APPEND);
                return $failure;
            } else {
                return 1;
            }
		} catch (\Swift_SwiftException $e){
			@file_put_contents(APP_PATH . '/' . APP_PUBLIC.'/'.APP_LOGFILE, 
                date('Ymd_his') . ' - ' . $e->getMessage() . ' - ' . $e->getFile() . ' - ' . $e->getLine(). PHP_EOL, FILE_APPEND);
            return 0;
		}
	}
}