<?php

require('lib/Twilio/Twilio.php'); 
$account_sid = 'AC29dc2cc5560d931b886ea9f5e3d5ae07'; 
$auth_token = '0a35fe953a7820aff6509d206a7285a5'; 
$twilio = new Services_Twilio($account_sid, $auth_token); 
$twilio_dev_number = "+17094002314"; // dev
$twilio_number = "+17097003586"; // live

class Twilio 
{

	public static function send_verification($to, $verification)
	{
		global $twilio;
		global $twilio_dev_number;
		$message = "Hello! Your verification code is " . $verification . ".";

		$twilio->account->messages->create(array( 
			'To' => $to, 
			'From' => $twilio_dev_number, 
			'Body' => '(DASHALL) ' . $message
		));
	}

	public static function send_text($to, $message)
	{
		// twilio init
		global $twilio;
		global $twilio_number;

		$twilio->account->messages->create(array( 
			'To' => $to, 
			'From' => $twilio_number, 
			'Body' => '(DASHALL) ' . $message
		));
	}

	public static function notify_management($message)
	{
		self::send_text('7096878776', $message);
		self::send_text('9022376300', $message);
	}

}

?>
