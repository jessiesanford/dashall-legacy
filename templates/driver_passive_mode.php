<?php
	$query_getOrders = mysql_query("
		SELECT * 
		FROM orders
		INNER JOIN order_status ON orders.order_status = order_status.order_status_id
		LEFT JOIN order_costs ON orders.order_id = order_costs.order_id
			WHERE orders.order_driver is NULL AND (order_status.order_status_id_num >= 0 AND order_status.order_status_id_num < 5) 
		ORDER BY orders.init_time
		LIMIT 12
	");

	if (!$query_getOrders)
	{
		echo "Something went wrong.";
	}
	else
	{
		if (mysql_num_rows($query_getOrders) == 0)
		{
			echo "There are no orders to be assigned.";
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

				echo'
					<div class="order_row" data-order_id="'. $order['order_id'] .'">

						<div class="heading">
						Order #'. $order['order_id'] .'
						</div>

						<div class="order_time cell">'. date("M d Y @ g:i:s a ", strtotime($order['init_time'])) .'</div>';

						echo '
						<div class="title">Order Description</div>
						<div class="order_desc">'. $order['order_desc'] .'</div>
						<br />

						<div class="title">Order Location</div>
						<div class="order_location">'. $order['order_location'] .'</div>
						<br />

						<div class="title">Delivery Address</div>
						<div class="order_address"><a target="_blank" href="https://www.google.ca/maps/search/'. str_replace(' ', '%20', $address['address_street']) .'">'. $address['address_street'] .'</a></div>
						<br />

						<button class="self_assign button_green button_lrg wid_100" data-order_id="'. $order['order_id'] .'"><i class="fa fa-car"></i>&nbsp; Assign Me</button>
						<button class="report_issue_init push_top" data-order_id="'. $order['order_id'] .'"><i class="fa fa-exclamation-circle"></i>&nbsp; Report Issue</button>
					</div>	
				';

			}
		}
	}


?>