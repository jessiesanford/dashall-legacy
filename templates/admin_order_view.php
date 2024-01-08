<?php

	$sql = mysql_query("
		SELECT * FROM orders 
		INNER JOIN users ON orders.order_user = users.user_id 
		INNER JOIN order_costs ON orders.order_id = order_costs.order_id
		INNER JOIN addresses ON addresses.address_order = orders.order_id
		WHERE orders.order_id = ". $_GET['oid'] ."
		LIMIT 1"
	);

	$order = mysql_fetch_assoc($sql);

	echo '
		<div class="section">
			<h2 class="page_heading">
			Order #'. $order['order_id'] .' <span id="os_'.$order['order_status'].'" class="order_status">'.  $order['order_status'] .'</span></h2>
			<div class="row row_baseline">
				<div class="cell wid_30">
					<h2>Order Info</h2>
					<h4>Location</h4>
					'. $order['order_location'] .'

					<h4 class="push_top_20">Summary</h4>
					'. $order['order_desc'] .'

				</div>
				<div class="cell wid_30">
					<h2>User Info</h2>
					'. $order['user_firstName'] .' '. $order['user_lastName'] .'
				</div>
				<div class="cell wid_30">
					<h2>Options</h2>
				</div>
			</div>
		</div>
	';

?>