<?php 
	require_once('connect.php');
	$pageTitle = "Orders";
	$pageDesc = 'Local delivery from your favorite restaurants straight to your doorstep.';
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<?php require 'include.php'; ?>
	<script type="text/javascript" src="js/driver.js"></script>
</head>
<body>

<?php require "header.php"; ?>

<div id="container">

	<div class="wrap">

		<div class="section orders_section">

		<?php 
			if ($user['user_group'] < 2)
			{
				echo "You don't have permission to access this page.";
			} 
			else 
			{
		?>

				<div class="page_heading">
					<h1 class="page_title">Your Assigned Orders</h1>
				</div>



					<?php
						$query_getOrders = mysql_query("
							SELECT * 
							FROM orders
							INNER JOIN order_status ON orders.order_status = order_status.order_status_id
							LEFT JOIN order_costs ON orders.order_id = order_costs.order_id
	 						WHERE (
							orders.order_driver = ". mysql_real_escape_string($_SESSION['user_id']) ."
							AND (orders.order_status !=  'COM' AND orders.order_status != 'CANC' AND orders.order_status != 'ARCH')
							)
							ORDER BY orders.assigned_time
							LIMIT 12
						");

						if(!$query_getOrders)
						{
							echo "Something went wrong.";
						}
						else
						{
							if (mysql_num_rows($query_getOrders) == 0)
							{
								echo "You have no assigned orders.";
							}
							else if (mysql_num_rows($query_getOrders) > 0)
							{
								// generate restaurants
								while($order = mysql_fetch_assoc($query_getOrders))
								{

									$query_getUser = mysql_query("SELECT user_email, user_firstName, user_lastName, user_phone   FROM users WHERE user_id = ". $order['order_user'] ." LIMIT 1");
									$query_getAddress = mysql_query("SELECT * FROM addresses WHERE address_order = ". $order['order_id'] ." LIMIT 1");
									$user = mysql_fetch_assoc($query_getUser);
									$address = mysql_fetch_assoc($query_getAddress); 

									$time1 = new DateTime($order['order_time']);
									$time2 = new DateTime($order['order_complete_time']);
									$interval = $time1->diff($time2);
									$elapsed = $interval->format('%h h %i m %S s');

									echo'
										<div class="order_row" data-order_id="'. $order['order_id'] .'">

											<div class="heading">
											Order #'. $order['order_id'] .'
											</div>

											<div class="order_time cell">'. date("M d Y @ g:i:s a ",strtotime($order['assigned_time'])) .'</div>';

											echo '<div class="cell">
												<div class="driver_order_status" id="ds_'. $order['order_status'] .'" data-order_id="'. $order['order_id'] .'">'. $order['order_status_name'] .'</div><br />';
												

											if ($order['order_status'] == 'ARR')
											{
												echo '<button class="mark_complete" data-order_id="' . $order['order_id'] . '"><i class="fa fa-check-circle"></i>&nbsp; Mark As Complete</button><br /><br />';
											}
											else if ($order['order_status'] == 'COM')
											{
												echo 'Complete: '. date("M d Y @ g:i a", strtotime($order['order_complete_time'])) .'<br />'. $elapsed;
											}

											echo '
													</div>';

											echo '
											<div class="title">Order Description</div>
											<div class="order_desc">'. $order['order_desc'] .'</div>
											<br />

											<div class="title">Order Location</div>
											<div class="order_location">'. $order['order_location'] .'</div>
											<br />

											<div class="title">Customer Info</div>
											<div class="order_details">
												'. $user['user_firstName'] .' '. $user['user_lastName'] .'<br />
												<a class="order_phone" href="tel:'. $user['user_phone'] .'"><i class="fa fa-phone"></i>&nbsp; +'. $user['user_phone'] .'</a>
												<button class="button send_arrival_status"><i class="fa fa-bell"></i>&nbsp; I have arrived!</button>
											</div>
											<br />

											<div class="title">Delivery Address</div>
											<div class="order_address"><a target="_blank" href="https://www.google.ca/maps/search/'. str_replace(' ', '%20', $address['address_street']) .'">'. $address['address_street'] .'</a></div>
											<br />';

											if ($order['order_status'] != 'COM')
											{
												echo '<div class="order_cost">
														<input class="textbox align_center" type="textbox" placeholder="How much did you pay?" />
														<button class="update_cost" data-order_id="'. $order['order_id'] .'">Update Cost</button>
													</div>
													<br />';
											}
											if ($order['amount'] != 0.00)
											{
											echo '<div class="title">Total Cost (All Fees Included)</div>
											<div class="order_cost_amount">$<span>' . 
											number_format( ((($order['amount'] * $order['margin']) + $order['delivery_fee']) * $order['stripe_margin']), 2 )
											. '</span></div>
											';
											}
										echo '</div>	
									';

								}
							}
						}
					

					?>

	</div>
<?php } ?>


</div>
	<!-- end #section, #wrap -->

</div> 
<!-- end #container -->

<?php include "footer.php" ?>

</div>
<!-- end overview -->


</body>
</html>