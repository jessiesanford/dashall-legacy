<script type="text/javascript" src="js/payroll.js"></script>

<div class="section">

<div class="page_heading">
		<h1 class="page_title">Payroll</h1>
</div>

<div id="trans_sidebar">
	<div id="trans_sidebar_content">
		<div class="row thead">
			<div class="cell">Cash Out</div>
		</div>
		<div class="row">
			<div class="cell xlrg_text"><strong>$<span id="cashout_value">0.00</span></strong></div>
		</div>
		<div class="row">
			<div class="cell"><button id="clear_trans_selection">Clear Selection</button></div>
		</div>
	</div>
</div>

		<div id="trans_sidebar_inverse">

			<div class="row thead">
				<div class="cell wid_15">Order ID</div>
				<div class="cell wid_25">Date</div>
				<div class="cell wid_20">Driver</div>
				<div class="cell wid_25 cell_right">Breakdown</div>
				<div class="cell wid_20 cell_right align_right">Amount</div>
			</div>


	<?php 

		$sql = mysql_query("
			SELECT driver_payroll.*, orders.*, order_costs.amount, users.user_firstName, users.user_lastName FROM driver_payroll
			INNER JOIN orders
			ON orders.order_id = driver_payroll.order_id
			INNER JOIN order_costs
			ON order_costs.order_id = orders.order_id
			INNER JOIN users
			ON users.user_id = orders.order_driver
			ORDER by orders.init_time
			DESC
		");

		while ($trans = mysql_fetch_assoc($sql))
		{
			echo '
				<div class="row payroll_row" data="'. $trans['delivery_fee'] .'">
					<div class="cell wid_15">'. $trans['order_id'] .'</div>
					<div class="cell wid_25">'. date('M d Y g:ia', strtotime($trans['init_time'])) .'</div>
					<div class="cell wid_20">' . $trans['user_firstName'] .' '. $trans['user_lastName'] .'</div>
					<div class="cell cell_right wid_25">'. $trans['delivery_fee']  .' + '. $trans['amount'] .' + '. $trans['tip'] .'</div>
					<div class="cell cell_right align_right wid_20 payroll_amount strong" data="'. number_format(($trans['delivery_fee']  + $trans['amount'] + $trans['tip']), 2) .'">$'. number_format(($trans['delivery_fee']  + $trans['amount'] + $trans['tip']), 2) .'</div>
				</div>
			';
		}

	echo '</div><br class="clear"/>';
	

?>

</div>