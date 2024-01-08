<?php

require '../connect.php';
require '../order.class.php';

$return_arr = array();
$alerts = array();

$values = $_POST; 

try 
{
	switch($_POST['action'])
	{
		case 'order_confirm':
			Order::order_confirm($values);
			break;
	}
}

catch(Exception $e){
	// echo $e->getMessage();
	die("0");
}



?>