<script type="text/javascript" src="js/manage.js"></script>

<?php
			$query_getUser = mysql_query("SELECT user_group FROM users WHERE user_id = " . mysql_real_escape_string($_SESSION['user_id']) . " LIMIT 1");
			$user = mysql_fetch_assoc($query_getUser);

?>

		<div class="section orders_section">

				<div class="page_heading">
					<h1 class="page_title">Customer Profile</h1>
				</div>

					<?php
						$query_getUser = mysql_query("
							SELECT user_id, user_group, user_email, user_firstName, user_lastName, user_date, user_phone, dashcash_balance
							FROM users
							WHERE users.user_id = ". $_GET['id'] ."
						");

						if($query_getUser)
						{
							if(mysql_num_rows($query_getUser) != 0)
							{
								$user = mysql_fetch_assoc($query_getUser);

								$query_getOrders = mysql_query("
									SELECT orders.*, order_costs.*, addresses.*, orders.order_id AS ref_id FROM orders 
									INNER JOIN users ON orders.order_user = users.user_id 
									LEFT JOIN order_costs ON orders.order_id = order_costs.order_id
									LEFT JOIN addresses ON orders.order_id = addresses.address_order
									WHERE orders.order_user = ". $_GET['id'] ."
									ORDER BY orders.order_id DESC
								");

								echo '
									<label> 
										<div>Name</div>
										'. $user['user_firstName'] .' '. $user['user_lastName'] .'
									</label>
									<label> 
										<div>Email</div>
										'. $user['user_email'] .'
									</label>
									<label> 
										<div>Phone</div>
										'. $user['user_phone'] .'
									</label>
									<label> 
										<div>Registration Date</div>
										'. $user['user_date'] .'
									</label>

									<br />

								';

								if ($user['user_group'] < 2)
								{
									echo '
										<br />
										<button id="promote_to_driver" data-user_id='. $user['user_id'] .'><i class="fa fa-car"></i>&nbsp; Promote To Driver</button>
									';
								}
								if ($user['user_group'] == 2)
								{
									echo '
										<br />
										<button id="remove_driver" data-user_id='. $user['user_id'] .'><i class="fa fa-times"></i> Remove Driver</button>
									';
								}

								echo '
									<hr />
									<h2>Customer Orders</h2>
									<div class="table">
										<div class="thead row">
											<div class="cell wid_10">Status</div>
											<div class="cell wid_20">Desc</div>
											<div class="cell wid_20">Location</div>
											<div class="cell wid_10">Cost</div>
											<div class="cell wid_20">Time</div>
											<div class="cell wid_10">Address</div>
										</div>
								';

								while($order = mysql_fetch_assoc($query_getOrders))
								{
								echo'
									<div class="row">
										<div class="trow cell wid_10">'. $order['order_status'] .'</div>
										<div class="trow cell wid_20">';
										if(strlen($order['order_desc']) > 100) {
										    echo substr($order['order_desc'], 0, 100). '...';
										}
										else {
										    echo $order['order_desc'];
										}
										echo '
										</div>
										<div class="trow cell wid_20">'.$offset.' '. $order['order_location'] .'</div>
										<div class="trow cell wid_10">$'. number_format( ( ($order['amount'] * $order['margin'] + $order['delivery_fee'] )), 2 ) .'</div>
										<div class="trow cell wid_20">
											'. date("M d Y @ g:i a",strtotime($order['init_time'])) .'<br />
										</div>
										<div class="trow cell">'. $order['address_street'] .'</div>
									</div>	
									';
								}
								echo '</div>';

							}
						}
					
						echo '
							<hr/>

							<form id="add_dashcash">
								<h3>DashCash Balance</h3>
								<h3>$'. $user['dashcash_balance'] .'</h3>
								<input type="text" class="textbox" name="amount" />
								<input type="hidden" value="'. $_GET['id'] .'" name="user_id" />
								<button type="submit">Add</button>
							</form>

						';

					?>

	</div>

