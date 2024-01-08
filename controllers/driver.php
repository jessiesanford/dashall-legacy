<?php

require '../connect.php';
require '../driver.class.php';

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
			case 'self_assign':
				Driver::self_assign($values);
				break;

			case 'report_issue':
				Driver::report_issue($values);
				break;
				
			case 'mark_complete':
				Driver::mark_complete($values);
				break;

			case 'markComplete_verify':
				Driver::markComplete_verify($values);
				break;

			case 'send_arrival_status':
				Driver::send_arrival_status($values);
				break;
				
			case 'update_order_cost':
				Driver::update_order_cost($values);
				break;

			case 'take_shift':
				Driver::take_shift($values);
				break;

			case 'request_unshift':
				Driver::request_unshift($values);
				break;
		}
	}

	catch(Exception $e){
		echo $e->getMessage();
		die("0");
	}
}


?>