<?php
	$sql = mysql_query("
		SELECT count(*) AS counter
		FROM orders
		INNER JOIN order_status ON orders.order_status = order_status.order_status_id
		LEFT JOIN order_costs ON orders.order_id = order_costs.order_id
			WHERE orders.order_driver is NULL AND (order_status.order_status_id_num >= 0 AND order_status.order_status_id_num < 5) 
		ORDER BY orders.init_time
		LIMIT 12
	");
	$unassigned_orders = mysql_fetch_assoc($sql);
?>

<ul class="tab_menu">
	<li class="selected" data="tab_1">Your Orders</li>
	<li data="tab_2">Unassigned (<?php echo $unassigned_orders['counter'] ?>)</li>
</ul>

<hr />

<div class="tab_panel tab_current" id="tab_1">

<?php
	$query_getOrders = mysql_query("
		SELECT * 
		FROM orders
		INNER JOIN order_status ON orders.order_status = order_status.order_status_id
		LEFT JOIN order_costs ON orders.order_id = order_costs.order_id
			WHERE (
		orders.order_driver = ". mysql_real_escape_string($_SESSION['user_id']) ."
		AND (orders.order_status !=  'COM' AND orders.order_status != 'CANC' AND orders.order_status != 'ARCH' AND orders.order_status != 'DEN')
		)
		ORDER BY orders.init_time
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
							echo '<button class="mark_complete button_lrg" data-order_id="' . $order['order_id'] . '"><i class="fa fa-check-circle"></i>&nbsp; Mark As Complete</button><br /><br />';
						}
						else if ($order['order_status'] == 'COM')
						{
							echo 'Complete: '. date("M d Y @ g:i a", strtotime($order['order_complete_time'])) .'<br />'. $elapsed;
						}

						echo '
								</div>';

						echo '
							<div class="title">Order Description</div>
							<pre class="order_desc">'. $order['order_desc'] .'</pre>
							<br />

							<div class="title">Order Location</div>
							<div class="order_location">'. $order['order_location'] .'</div>
							<br />
						';

						if ($order['order_status_id_num'] == 2)
						{
							echo '
								<div class="order_cost push_bottom_20">
									<input class="textbox align_center" type="textbox" placeholder="How much did you pay?" />
									<button class="update_cost" data-order_id="'. $order['order_id'] .'">Update Cost</button>
								</div>
							';
						}

						// order_status_id_num == APP_S3 / DASHING TO YOU
						if ($order['order_status_id_num'] == 3)
						{
							echo '<button class="button button_lrg push_bottom_20 send_arrival_status"><i class="fa fa-bell"></i>&nbsp; I have arrived!</button>';
						}

						echo '
							<div class="order_info">
								<div class="title">Customer Info</div>
								<div class="order_details">
									'. $user['user_firstName'] .' '. $user['user_lastName'] .'<br />
									<a class="order_phone" href="tel:'. $user['user_phone'] .'"><i class="fa fa-phone"></i>&nbsp; +'. $user['user_phone'] .'</a>
								</div>
								<br />
								<div class="title">Delivery Address</div>
								<div class="order_address"><a target="_blank" href="https://www.google.ca/maps/search/'. str_replace(' ', '%20', $address['address_street']) .'">'. $address['address_street'] .'</a></div>
							</div>
							<br />
						';

						if ($order['amount'] != 0.00)
						{
							echo '<div class="title">Cost of Order</div>
							<div class="order_cost_amount">$<span>' . 
							number_format($order['amount'], 2 )
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

<div class="tab_panel" id="tab_2">
	<?php require('driver_passive_mode.php'); ?>
</div>

</div>