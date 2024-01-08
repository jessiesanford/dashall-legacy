<?php

require '../connect.php';
require '../misc.class.php';

$return_arr = array();
$alerts = array();

if ($_SESSION['signed_in'] == false  && $_POST['action'] != 'submit_contact')
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
			case 'submit_driver_app':
				Misc::submit_driver_app($values);
				break;
			case 'submit_contact':
				Misc::submit_contact($values);
				break;
			case 'user_search':
				Misc::user_search($values);
				break;
		}
	}

	catch(Exception $e){
		// echo $e->getMessage();
		die("0");
	}
}


?>