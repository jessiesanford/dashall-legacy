<?php

require '../connect.php';
require '../order.class.php';
require '../manage.class.php';

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
			case 'updateOrderStatus':
				Manage::update_order_status($values);
				break;
			case 'updateOrderStatus_ind':
				Manage::update_order_status_ind($values);
				break;
				
			case 'delete_order':
				Manage::delete_order($values);
				break;

			case 'update_order':
				Manage::update_order($values);
				break;

			case 'assign_driver':
				Manage::assign_driver($values);
				break;

			case 'unassign_driver':
				Manage::unassign_driver($values);
				break;

			case 'manage_order_cost':
				Manage::manage_order_cost($values);
				break;

			case 'promote_to_driver':
				Manage::promote_to_driver($values);
				break;

			case 'remove_driver':
				Manage::remove_driver($values);
				break;

			case 'mark_complete':
				Manage::mark_complete($values);
				break;

			case 'collect_payment':
				Manage::collect_payment($values);
				break;

			case 'add_dashcash':
				Manage::add_dashcash($values);
				break;
				
            case 'send_user_text':
                Manage::send_user_text($values);
                break;
		}
	}

	catch(Exception $e){
		echo $e->getMessage();
		die("0");
	}
}


?>