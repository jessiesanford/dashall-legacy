<?php 
	if (isset($_GET['oid'])) {
		include("admin_order_view.php"); 
	}
	else {
		include("admin_orders_all.php"); 
	}
?>

