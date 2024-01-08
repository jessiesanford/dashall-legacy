<div id="showcase_backdrop" class="resp_hide">
	<div class="overlay"></div>
</div>

<div id="order_flow" class="push_bottom_40">

	<?php 

	$sql = mysql_query("
		SELECT users.user_id, users.user_phone, user_addresses.user_id AS uid, user_addresses.street, user_addresses.postal
		FROM users
		LEFT JOIN user_addresses ON user_addresses.user_id = users.user_id
		WHERE users.user_id = " . mysql_real_escape_string($_SESSION['user_id']) . " LIMIT 1
	");
	$user = mysql_fetch_assoc($sql); 


	$sql = mysql_query("
		SELECT * FROM orders
		LEFT JOIN order_costs ON order_costs.order_id = orders.order_id 
		LEFT JOIN order_status ON orders.order_status = order_status.order_status_id
		WHERE order_user = " . mysql_real_escape_string($_SESSION['user_id']) . " AND order_active = 1 LIMIT 1"
	);
	$order = mysql_fetch_assoc($sql); 

	$sql = mysql_query("SELECT * FROM addresses WHERE address_order = " . $order['order_id'] . "");
	$address = mysql_fetch_assoc($sql); 

	if (!$order)
	{
		echo'
		<div id="dashbox_wrap" class="align_center">
			<div id="view_no_order">
				<div id="slogan_line1">
				 Get ​<strong>any</strong> meal delivered from ​<strong>any</strong>​ restaurant! 
				</div>
				<div id="slogan_line2">
					What can we Dash for you?
				</div>

				<div id="current_location" class="resp_hide">
					<i class="fa fa-map-marker"></i>&nbsp; St John&#39;s, Newfoundland
				</div>
				<form id="dashbox">
					<div class="dashbox_textbox_wrap">
						<textarea name="dashbox_location" id="dashbox_location" class="dashbox_textbox" placeholder="Where are you looking to get delivery from?" /></textarea>
					</div>
					<div class="dashbox_textbox_wrap">
						<textarea name="dashbox_desc" id="dashbox_desc" class="dashbox_textbox" placeholder="Describe what you would like (as specifically as possible)..." /></textarea>
					</div>						
					<button id="dashbox_button" href="#" type="submit">DASH IT</button>
				</form>
				<hr class="push_top_40 push_bottom_40" />
				<h4>Need Inspiration? Check out our featured restaurants.</h4>
				<a href="restaurants/freshii"><img src="images/rest_freshii.png" alt="Freshii" style="opacity: 0.5; max-height: 80px;" /></a>
				<a href="restaurants/quesada"><img src="images/rest_quesada.png" alt="Quesada" style="opacity: 0.5; max-height: 80px;" /></a>

			</div>
		</div>
			';
	}
	?>

	<div id="order_area_wrap" class="wrap">

	<?php 

		// if the user does not have an active order we will have to create one for them.
		if ($order)
		{
			echo '<div id="order_area" class="padd_y_40 align_center">';

			// order is initalized so we check if they set an address yet
			if (!$address)
			{
				echo '
					<h2>What is your address?</h2>
					<form method="POST" action="" id="submit_address" class="form_fill">

				    	<label class="block push_bottom">
							<div>Street Address</div>
				        	<input type="text" class="textbox wid_100 align_center" id="address_street" name="address_street" placeholder="Enter Street..." value="'. $user['street'] .'" />
				        </label>
				        <label class="block push_bottom">
							<div>City</div>
							<input type="text" class="textbox wid_100 align_center" id="address_city" name="address_city" maxlength="6" value="St. Johns" readonly>
						</label>
				        <br />
				        <br />
				        <input type="submit" class="button button_lrg resp_expand" id="button -order" name="submit" value="Next Step" />  

					</form>
					<br /><a href="#" class="confirm_action" data-action="order_cancel" data-desc="Are you sure you want to cancel your order?" data-button="Confirm Cancellation">Cancel Order</a>
				';
			}
			else if ($order['order_status'] == 'AWD_S2')
			{
				$sql = mysql_query("SELECT dashcash_balance FROM users WHERE user_id = ". $_SESSION['user_id'] ."");
				$dashcash = mysql_fetch_assoc($sql);

				echo '
					<h2>Have a coupon?</h2>
					<form id="order_promo">
						<div class="select_area push_bottom_40">
							<div class="select_box" id="competition">
								<div class="select_box_checkbox"></div>
								<div class="select_box_title">Competition Code</div>
								<div class="select_box_content">
									<input type="textbox" class="textbox" id="promo_data" />
								</div>
							</div>
							<div class="select_box" id="coupon_redeem">
								<div class="select_box_checkbox"></div>
								<div class="select_box_title">Coupon</div>
								<div class="select_box_content">
									<p>If you have a promo code, enter it here!</p>
									<input type="textbox" class="textbox" id="promo_data" />
								</div>
							</div>
							<div class="select_box" id="dashcash_redeem">
								<div class="select_box_checkbox"></div>
								<div class="select_box_title">DashCash</div>
								<div class="select_box_content">
									<p>Your DashCash balance: <strong>$'. number_format($dashcash['dashcash_balance'], 2) .'</strong></p>
									<input type="textbox" class="textbox" id="promo_data" placeholder="0.00" maxlength="5" />
								</div>
							</div>
							<div class="select_box selected">
								<div class="select_box_title">I do not have a coupon.</div>
							</div>
						</div>
						<button type="submit">Submit Order</button>
					</form>
					<br /><a href="#" class="confirm_action" data-action="order_cancel" data-desc="Are you sure you want to cancel your order?" data-button="Confirm Cancellation">Cancel Order</a>
				';
			}

			// if we have an address we can display the next step, processing credit card info
			// this line is unstable and needs to change it's a poor way to determine when to ask for credit card info
			else if ($order['pay_auth'] == 0 && $order['order_status'] == 'AWD_S3')
			{
				echo '
					<h2>Payment Information</h2>
					<p>
						DashAll accepts and processes VISA, VISA Debit, Mastercard, and American Express using Stripe, a secure credit card processing service.
						<br />
						Once your order total has been determined by the driver our system will charge amount to your card and you will be notified.
					</p>
					<p>
						Cost Breakdown = Order Cost * 1.13% Service Fee + $7 Delivery Fee
					</p>
					<br />
				';

				// determine if cc info is logged via stripe
				$sql = mysql_query("SELECT * FROM stripe_customers WHERE user_id = ". mysql_real_escape_string($_SESSION['user_id']) ."");
				$stripe_customer = mysql_fetch_assoc($sql); 

				if (!$stripe_customer)
				{
		        	echo'
		        		<form action="" method="POST" id="order_pay_auth" class="form_fill">
			                <label class="block push_bottom">
			                	<div><i class="fa fa-credit-card"></i>&nbsp; Card Number</div>
			                	<input type="text" size="16" maxlength="16" autocomplete="off" class="textbox wid_100 align_center card-number" placeholder="**** **** **** ****" />
			                </label>
			                <div class="push_bottom_40 row row_center no_border">
				                <label class="cell">
				                	<div>MM</div>
				                	<input type="text" size="2" maxlength="2" class="textbox card-expiry-month width_auto align_center" placeholder="01" />
				                </label>
				                <label class="cell">
				                	<div>YY</div>
				                	<input type="text" size="2" maxlength="2" class="textbox card-expiry-year width_auto align_center" placeholder="18" />
				                </label>
				                <label class="cell">
				                	<div><i class="fa fa-lock"></i>&nbsp; CVC</div>
				                	<input type="text" size="4" maxlength="3" autocomplete="off" class="textbox card-cvc align_center width_auto" placeholder="***" />
				                </label>
			                </div>
				            <button type="submit" class="button button_lrg resp_expand">Authorize Card</button>
				        </form>
					';
				}
				else 
				{

					require("stripe.class.php");
					$cc_data = Stripe::get_customer($stripe_customer['stripe_id']);

					echo 
					'
						<h3>Would you like to use the following credit card?</h3>
		        		<form action="" method="POST" id="order_pay_auth_logged">
							<i class="fa fa-credit-card"></i>&nbsp; '. $cc_data->brand .' <input type="text" class="textbox" value="**** **** **** '. $cc_data->last4 .'" disabled /><br />
							<div>Expires '. $cc_data->exp_month .' / '. $cc_data->exp_year .'</div>
							<br />

					        <button type="submit" class="button_lrg resp_expand push_bottom">Authorize Card</button>
					        <br />
					        <button class="confirm_action button_alt resp_expand" 
					        	data-action="delete_credit_card" 
					        	data-button="Confirm"
					        	data-desc="Are you sure you want to remove this card from your account?"
					        >Remove This Card</button>
				        </form>

					';
				}
				echo '<br /><a href="#" class="confirm_action" data-action="order_cancel" data-desc="Are you sure you want to cancel your order?" data-button="Confirm Cancellation">Cancel Order</a>';
			}
			else if ($order['order_status'] != 'COM')
			{
				echo '
					<h2>Your DashAll Order</h2>
					<ul class="tab_menu push_bottom">
						<li class="selected" data="tab_1"><i class="fa fa-send"></i>&nbsp; Status</li>
						<li data="tab_2"><i class="fa fa-tags"></i>&nbsp; Details</li>
					</ul>
					<hr />
				';

				echo'

					<div class="tab_panel tab_current" id="tab_1">
					<h2>Order Status</h2>
					<div class="order_status_wrap">
						<div class="order_status_resp push_bottom">'.$order['order_status_resp'].'</div>
					';

					if ($order['order_status_id'] == 'DEN')
					{
						echo '<div id="os_DEN" class="order_status">Could Not Complete</div>';
					}
					else 
					{
						if ($order['order_status_id'] == 'PDR')
						{
							echo '<div id="os_PDR" class="order_status active_status">Pending Review</div>';
						}
						else 
						{
							echo '<div id="os_PDR" class="order_status ">Pending Review</div>';
						}

						if ($order['order_status_id'] == 'APP')
						{
							echo '<div id="os_APP" class="order_status active_status">Awaiting Driver</div>';
						}
						else 
						{
							echo '<div id="os_APP" class="order_status ">Awaiting Driver</div>';
						}

						if ($order['order_status_id'] == 'APP_S2')
						{
							echo '<div id="os_APP_S2" class="order_status active_status">Out For Pickup</div>';
						}
						else 
						{
							echo '<div id="os_APP_S2" class="order_status ">Out For Pickup</div>';
						}

						if ($order['order_status_id'] == 'APP_S3')
						{
							echo '<div id="os_APP_S3" class="order_status active_status">Dashing To You</div>';
						}
						else 
						{
							echo '<div id="os_APP_S3" class="order_status">Dashing To You</div>';
						}

						if ($order['order_status_id'] == 'ARR')
						{
							echo '<div id="os_ARR" class="order_status active_status">At Your Door!</div>';
						}
						else 
						{
							echo '<div id="os_ARR" class="order_status ">At Your Door!</div>';
						}

						if ($order['order_status_id_num'] >= 3)
						{
							$calc_amount = number_format( ((( ($order['amount'] - $order['discount_amount']) * $order['margin']) + $order['delivery_fee'] + $order['tip']) * $order['stripe_margin']), 2 );

							echo '<div class=""><strong>Your order cost was $' . $calc_amount . '</strong></div>';
						}
					}



					if ($order['order_status_id_num'] >= 2)
					{


						$query_getDriver = mysql_query("
							SELECT users.user_id, users.user_firstName, users.user_phone FROM users
							WHERE user_id = " . $order['order_driver'] . ""
						);

						$driver = mysql_fetch_assoc($query_getDriver); 

						echo '
							<div>Your delivery driver is '. $driver['user_firstName'] .'!</div><br />
							<a class="button" href="tel:'. $driver['user_phone'] .'"><i class="fa fa-phone-square"></i>&nbsp; Contact Driver</a>
						';
					}

				echo '
					
					</div>
					</div>

					<div class="tab_panel" id="tab_2">
						<h2>Order Request Description</h2>
						<div class="push_bottom">'. $order['order_desc'] .'</div>
						- FROM - 
						<div class="push_top">'. $order['order_location'] .'</div>

						<hr />

						<h2>Delivery Address</h2>' 
						. $address['address_street'] . '<br />'
						. $address['address_city'] . '<br />'
						. $user['user_phone'] . '
						<hr />';

					if ($order['promo'] != "")
					{
						$sql = mysql_query("SELECT * FROM promos WHERE promo_code = '". $order['promo'] ."'");
						$promo = mysql_fetch_assoc($sql);


						echo '
						<h2>Order Promotions</h2>
						'. $promo['promo_code'] .'<br />
						'. $promo['promo_desc'] . '<br />
						Discount Amount: $'. $order['discount_amount'] . '<br />
						';
					}

				echo '
				</div>
				';



				
			if ($order['order_status_id_num'] <= 1)
			{
				echo '<br /><a href="#" class="confirm_action" data-action="order_cancel" data-desc="Are you sure you want to cancel your order?" data-button="Confirm Cancellation">Cancel Order</a>';
			}
			}
			else if ($order['order_status'] == "COM") 
			{
				echo '<h3>Your order has been completed!</h3>';
				echo '
				<p class="push_bottom">Please take a moment to review your completed order...</p>
				<form id="order_feedback" class="register align_center">
					<div class="push_bottom">
						<h4>How would you rate your order correctness?</h4>
						<div id="correctness_rating" name="correctness_rating" class="starrr" data-rating="0"></div>
					</div>
					<div class="psu_top push_bottom">
						<h4>How would you rate the delivery time?</h4>
						<div id="timing_rating" name="timing_rating" class="starrr" data-rating="0"></div>
					</div>
					<hr>
					<div class="push_bottom">
						<h4>How would you rate your driver?</h4>
						<div id="driver_rating" name="driver_rating" class="starrr" data-rating="0"></div>
					</div>
					<h4>Would you like to tip your driver?</h4>
					<p>Did your driver do a good job? Show your appreciation here!</p>
					<span>$</span> <input type="textbox" id="tip_amount" name="tip_amount" class="tip_box textbox align_center" placeholder="Enter a tip amount here..." value="2.00" />
					<hr>
					<label>
						<textarea id="order_feedback" name="order_feedback" class="textarea block wid_100" placeholder="Enter your service and driver feedback here..."></textarea>
					</label>
					<br />
					<button type="submit" >Submit</button>
				</form>
				';
			}
			echo '</div>';
		}
	?>

</div>
</div>
<!-- end #order_area_wrap -->

<div class="wrap how_blocks resp_hide push_bottom_40">
	<h1 class="align_center">And just how does this work?</h1>
	<div class="row row_collapse no_border ">
		<div class="cell wid_25 align_center">
			<h3>Fill out the requested order info and submit</h3>
			<img src="images/how_s1.png" alt="" />
		</div>
		<div class="cell wid_25 align_center">
			<h3>DashAll will review your order and assign a driver.</h3>
			<img src="images/how_s2.png" alt="" />
		</div>
		<div class="cell wid_25 align_center">
			<h3>A driver will pick up and pay for your order.</h3>
			<img src="images/how_s3.png" alt="" />
		</div>
		<div class="cell wid_25 align_center">
			<h3>Your order will be delivered to your address!</h3>
			<img src="images/how_s4.png" alt="" />
		</div>
	</div>
</div>


