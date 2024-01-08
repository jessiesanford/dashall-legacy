<?php include("templates/html/transactions.html") ?>
<script type="text/javascript" src="js/transactions.js?v=<?php echo FILE_VERSION; ?>"></script>

<div class="section">

<div class="page_heading">
		<h1 class="page_title">Transactions</h1>
</div>

		<?php 
			require("admin.class.php");
			$trans_model = Admin::get_transactions_stats();
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
		<div id="trans_stats" class="row row_baseline">

			<div class="cell wid_30">
				<div>
					Revenue
					<div class="stat_big"><span id="rev_amount">$<?php echo $trans_model['trans_stats']['revenue'] ?></span></div>
				</div>
				<div class="push_top_20">
					Profit
					<div class="stat_big"><span id="profit_amount">$<?php echo $trans_model['trans_stats']['profit'] ?></span></div>
				</div>
			</div>
			<div class="cell wid_30">
				<div >
					FIRSTDASH
					<div class="stat_big"><span id="firstdash_count"><?php echo $trans_model['trans_stats']['promo_count'] ?></span> Times</div>
				</div>
				<div class="push_top_20">
					DASHCASH
					<div class="stat_big"><span id="dashcash_count"><?php echo $trans_model['trans_stats']['dashcash_count'] ?></span> Times</div>
				</div>
			</div>
			<div class="cell wid_30">
				<div>
					Total Orders
					<div class="stat_big"><span id="order_count"><?php echo $trans_model['trans_stats']['order_count'] ?></span> Orders</div>
				</div>
				<div class="push_top_20">
					Repeat Customer Orders
					<div class="stat_big"><span id="repeat_customer_count"><?php echo $trans_model['trans_stats']['repeat_customer_count'] ?></span> Orders</div>
				</div>
			</div>
			<div class="cell wid_30">
				<div>
					Average Order Cost
					<div class="stat_big"><span id="avg_order_cost">$<?php echo $trans_model['trans_stats']['avg_order_cost'] ?></span></div>
				</div>
				<div class="push_top_20">
					Average Profit
					<div class="stat_big"><span id="avg_profit">$<?php echo $trans_model['trans_stats']['avg_order_profit'] ?></span></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="cell cell_right align_right">
				<button id="reset_transactions">Reset</button>
			</div>
		</div>

<br>

<div id="transactions_wrapper">

<div class="row thead">
	<div class="cell wid_10">Order ID</div>
	<div class="cell wid_25">Date</div>
	<div class="cell wid_20">Customer</div>
	<div class="cell wid_20">Promo</div>
	<div class="cell wid_20 cell_right align_right">Revenue</div>
</div>

<div id="transactions_view">
	<?php 

		$i = 1;
		foreach($trans_model['trans'] as $trans) {
			if ($i == 100) break;
			$i++;

			echo '
				<div class="trans_row">
					<div class="row trans_summary" data="'. $trans['delivery_fee'] .'">
						<div class="cell wid_10">'. $trans['order_id'] .'</div>
						<div class="trans_date cell wid_25" data="'. date('Y-m-d H:i:s', strtotime($trans['init_time'])) .'">'. date('M d Y - g:ia', strtotime($trans['init_time'])) .'</div>
						<div class="trans_user cell wid_20" data-repeat_customer="'. $repeat_customer .'">
			';
						if ($trans['repeat_customer'] == true)
						{
							echo '<span class="push_right"><i class="fa fa-refresh"></i></span>';
						}
			echo '
						' . $trans['user_firstName'] .' '. $trans['user_lastName'] .'
						</div>
						<div class="trans_promo cell wid_20">' . $trans['promo'] .'</div>
						<div class="trans_amount cell cell_right align_right wid_20 strong">$'. $trans['profit'] .'</div>
					</div>
					<div class="trans_info">
						<div class="row no_border tcat">
							<div class="cell wid_20">Order Amount</div>
							<div class="cell wid_20">Delivery Fee</div>
							<div class="cell wid_20">Tip</div>
							<div class="cell wid_20">Discount</div>
							<div class="cell wid_20">Strip Cut</div>
							<div class="cell cell_right align_right wid_20">Revenue</div>
						</div>
						<div class="row row_alt">
							<div class="cell wid_20">$'. $trans['amount'] .'</div>
							<div class="cell wid_20">$'. $trans['delivery_fee'] .'</div>
							<div class="cell wid_20">$'. $trans['tip'] .'</div>
							<div class="cell wid_20">$'. $trans['discount_amount'] .'</div>
							<div class="cell wid_20">$'. $trans['stripe_cut'] .'</div>
							<div class="cell cell_right align_right wid_20">$'. $trans['revenue'] .'</div>
						</div>
					</div>
				</div>
			';
		}

?>
</div>
</div>

</div>