<?php

require '../connect.php';
require '../user.class.php';

$values = $_POST; 

try 
{
	switch($_POST['action'])
	{
		case 'user_login':
			User::user_login($values);
			break;

		case 'user_forgot_password':
			User::user_forgot_password($values);
			break;

		case 'user_register':
			User::user_register($values);
			break;

		case 'user_verify':
			User::user_verify($values);
			break;

		case 'user_resend_verif':
			User::user_resend_verif($values);
			break;

		case 'user_logout':
			User::user_logout($values);
			break;

		case 'user_update_settings':
			User::user_update_settings($values);
			break;

		case 'user_change_email':
			User::user_change_email($values);
			break;

		case 'user_change_phone_number':
			User::user_change_phone_number($values);
			break;

		case 'user_change_password':
			User::user_change_password($values);
			break;

		case 'user_change_address':
			User::user_change_address($values);
			break;

		case 'remove_payment_method':
			User::remove_payment_method($values);
			break;

		case 'driver_settings':
			User::driver_settings($values);
			break;
			
		case 'account_pay_auth':
			User::account_pay_auth($values);
			break;
	}
}

catch(Exception $e){
	echo $e->getMessage();
	die("0");
}

?>