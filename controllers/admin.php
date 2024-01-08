<?php

require '../connect.php';
require '../admin.class.php';

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
	$values = $_POST; 

	try 
	{
		switch($_POST['action'])
		{
			case 'update_settings':
				Admin::update_settings($values);
				break;
			case 'get_transactions_in_range':
				Admin::get_transactions_in_range($values);
				break;
			case 'get_orders_in_range':
				Admin::get_orders_in_range($values);
				break;
			case 'mass_text_drivers':
				Admin::mass_text_drivers($values);
				break;
			case 'remove_shift':
				Admin::remove_shift($values);
				break;
		}
	}

	catch(Exception $e){
		echo $e->getMessage();
		die("0");
	}
}


?>