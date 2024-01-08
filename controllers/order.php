<?php

require '../connect.php';
require '../order.class.php';

$query_getUser = mysql_query("SELECT user_firstName, user_lastName, user_group FROM users WHERE user_id = " . mysql_real_escape_string($_SESSION['user_id']) . " LIMIT 1");
$user = mysql_fetch_assoc($query_getUser);

$return_arr = array();
$alerts = array();

if ($_SESSION['signed_in'] == false)
{
		$return_arr['form_check'] = 'error';
		$alerts[] = "You need to be logged in to use this function.";
		$return_arr['alert'] = $alerts[0];
		echo json_encode($return_arr);
}
else 
{
	if ($takingOrders == false)
	{
		if ($user['user_group'] < 2)
		{
			$return_arr['form_check'] = 'error';
			$alerts[] = "We are not currently taking orders.";
			$return_arr['alert'] = $alerts[0];
			echo json_encode($return_arr);
			return;
		}
	}

	$values = $_POST; 

	try 
	{
		switch($_POST['action'])
		{
			case 'order_init':
				Order::order_init($values);
				break;

			case 'order_init_confirm':
				Order::order_init_confirm($values);
				break;

			case 'submit_address':
				Order::submit_address($values);
				break;

			case 'order_pay_auth':
				Order::order_pay_auth($values);
				break;

			case 'order_pay_auth_logged':
				Order::order_pay_auth_logged($values);
				break;

			case 'order_cancel':
				Order::order_cancel($values);
				break;

			case 'delete_credit_card':
				Order::delete_credit_card($values);
				break;

			case 'add_promo':
				Order::add_promo($values);
				break;

			case 'order_feedback':
				Order::order_feedback($values);
				break;

			default:
				echo "Error.";
		}
	}
	catch(Exception $e){
		echo $e->getMessage();
		die("0");
	}
}


?>

