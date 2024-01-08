<?php include("templates/html/orders.html") ?>

<link rel="stylesheet" type="text/css" href="../css/admin_orders.css" />
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="./js/admin_orders.js"></script>


<div class="section orders_section">

	<div class="page_heading">
		<h1 class="page_title">Orders</h1>
	</div>


	<?php 
		// require("admin.class.php");
		// $orders_arr = Admin::get_order_stats();
	?>

	<div class="row thead">
		<div class="cell">Operations</div>
	</div>

	<div class="row">
		<div class="cell">
			<label class="block">
				<div>Start Date</div>
				<input type="text" class="textbox" id="start_date" placeholder="Beginning of time" readonly>
			</label>
		</div>
		<div class="cell">
			<label class="block">
				<div>End Date</div>
				<input type="text" class="textbox" id="end_date" placeholder="End of time" readonly>
			</label>
		</div>
	</div>

	<div id="order_stats" class="row row_baseline">
		<div class="cell wid_30">
			<div>
				Total Orders
				<div class="stat_big">
					<span id="total_orders"><?php echo $orders_arr['order_stats']['total_orders'] ?></span> Orders
				</div>
			</div>
			<div class="push_top_20">
				Total Complete Orders
				<div class="stat_big">
					<span id="total_complete_orders"><?php echo $orders_arr['order_stats']['total_complete_orders'] ?></span> Orders
				</div>
			</div>
		</div>
		<div class="cell wid_30">
			<div>
				Average Order Time
				<div class="stat_big">
					<span id="avg_order_time"><?php echo $orders_arr['order_stats']['avg_order_time'] ?></span> Minutes
				</div>
			</div>
		</div>
		<div class="cell wid_30">
			<div class="push_top_20">
				Repeat Customer Orders
				<div class="stat_big">
					<span id="repeat_customer_orders"><?php echo $orders_arr['order_stats']['repeat_customer_orders'] ?></span> Repeat Orders
				</div>
			</div>
			<div class="push_top_20">
				Repeat Customer Count
				<div class="stat_big">
					<span id="repeat_customer_count"><?php echo $orders_arr['order_stats']['repeat_customer_count'] ?></span> Repeat Customers
				</div>
			</div>
		</div>
	</div>

	  <div id="chart_div"></div>


	<div class="row">
		<div class="cell cell_right align_right">
			<button id="reset_orders">Reset</button>
		</div>
	</div>

	<br />

	<div class="row thead">
		<div class="cell wid_10">Order ID</div>
		<div class="cell wid_10">Status</div>
		<div class="cell wid_10">Location</div>
		<div class="cell wid_20">Time</div>
		<div class="cell wid_20">User</div>
		<div class="cell wid_20">Address</div>
	</div>

	<div id="orders_view">

	<?php
		// while($order = mysql_fetch_assoc($orders_arr['orders'])) {

		// 	echo'
		// 		<div class="order_row">
		// 			<div class="row order_summary" data="'.  $order['order_id'] .'">

		// 				<div class="cell wid_10">'.  $order['order_id'] .'</div>

		// 				<div class="cell wid_10" data="">
		// 					<span id="os_'. $order['order_status'] .'" class="order_status">'.  $order['order_status'] .'</span>
		// 				</div>

		// 				<div class="cell wid_10">
		// 					'. $order['order_location'] .'
		// 				</div>

		// 				<div class="cell wid_20">
		// 					'. date("M d Y - g:i a",strtotime($order['init_time'])) .'
		// 				</div>
		// 				<div class="order_customer cell wid_20" data-repeat_customer="">
		// 					<a href="admin?module=customer&id='. $order['user_id'] .'">'. $order['user_firstName'] .' '. $order['user_lastName'] .'</a>
		// 				</div>
		// 				<div class="order_address cell wid_20" data-repeat_customer="">
		// 				'. $order['address_street'] .'
		// 				</div>
		// 			</div>
		// 			<div class="order_info">
		// 				<div class="row no_border tcat">
		// 					<div class="cell wid_30">Order Desc</div>
		// 					<div class="cell cell_right align_right wid_20">Complete Time</div>
		// 				</div>
		// 				<div class="row row_alt">
		// 					<div class="cell wid_30">
		// 						'. $order['order_desc'] .'
		// 					</div>
		// 					<div class="cell cell_right align_right wid_20">'. $order['time_transpired'] .'</div>
		// 				</div>
		// 			</div>
		// 		</div>
		// 	';
		// }
	?>



		</div>
	</div>

</div>




</div>