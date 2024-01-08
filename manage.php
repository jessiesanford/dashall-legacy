<?php 
	require_once('connect.php');
	$pageTitle = "Orders";
	$pageDesc = 'Local delivery from your favorite restaurants straight to your doorstep.';
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<?php require 'include.php'; ?>
	<script type="text/javascript" src="js/manage.js?v=<?php echo FILE_VERSION; ?>"></script>
</head>
<body>

<?php require "header.php"; ?>

<div id="container">

	<div class="wrap">

		<?php

			$query_getUser = mysql_query("SELECT user_group FROM users WHERE user_id = " . mysql_real_escape_string($_SESSION['user_id']) . " LIMIT 1");
			$user = mysql_fetch_assoc($query_getUser);

			if ($user['user_group'] < 2)
			{
				echo '<div class="section">You do not have permission to access this page.</div>';
			}
			else 
            {

		?>

		<div class="section" id="manage_section">

				<div class="page_heading">
					<h1 class="page_title">Manage Orders</h1>
				</div>

					<?php


                    if (MANAGEMENT_MODE == 1) 
                    {
                        echo '<p><strong>Active Delegation is currently enabled.</strong> When an order comes in a notification will be sent to the specified driver:</p>';

                        $sql = mysql_query("
                            SELECT settings.*, users.user_firstName, users.user_lastName, users.user_phone FROM settings
                            INNER JOIN users ON users.user_id = settings.value
                            WHERE settings.name = 'active_driver'
                        ");

                        $driver = mysql_fetch_assoc($sql);

                        echo '
                            <div class="row">
                                <div class="cell wid_30">
                                '. $driver['user_firstName'] . ' ' . $driver['user_lastName'] .'
                                </div>
                                <div class="cell">
                                '. $driver['user_phone'] .'
                                </div>
                            </div>
                        ';

                        echo '<br />';
                    }

                    else if (MANAGEMENT_MODE == 2) 
                    {
                        echo '<p><strong>Passive Delegation is currently enabled.</strong> When an order comes in a notification will be sent to the following drivers:</p>';

                        $sql = mysql_query("
                            SELECT drivers.*, users.user_firstName, users.user_lastName, users.user_phone FROM drivers
                            INNER JOIN users ON users.user_id = drivers.user
                            WHERE drivers.notify_orders = 1
                        ");

                        while ($driver = mysql_fetch_assoc($sql))
                        {
                            echo '
                            <div class="row">
                                <div class="cell wid_30">
                                '. $driver['user_firstName'] . ' ' . $driver['user_lastName'] .'
                                </div>
                                <div class="cell">
                                '. $driver['user_phone'] .'
                                </div>
                            </div>
                            ';
                        }

                        echo '<br />';
                    }

                    else if (MANAGEMENT_MODE == 3) 
                    {
                        echo '<p><strong>Scheduled Delegation is currently enabled.</strong> When an order comes in a notification will be sent to the following scheduled drivers:</p>';

                        $sql = mysql_query("
                            SELECT users.user_firstName, users.user_lastName, driver_shifts. * 
                            FROM driver_shifts
                            INNER JOIN users ON users.user_id = driver_shifts.driver_id
                            WHERE '". TIMESTAMP ."' BETWEEN start_datetime AND end_datetime
                        ");


                        $drivers_arr = array();

                        while ($driver = mysql_fetch_assoc($sql))
                        {
                            $sql_ = mysql_query("            
                                SELECT COUNT(*) as count
                                FROM orders
                                INNER JOIN order_status ON orders.order_status = order_status.order_status_id
                                INNER JOIN users ON users.user_id = orders.order_user
                                WHERE (
                                    orders.order_driver = ". $driver['driver_id'] ."
                                    AND (orders.order_status !=  'COM' AND orders.order_status != 'CANC' AND orders.order_status != 'ARCH' AND orders.order_status != 'DEN')
                                )
                            ");
                            $count = mysql_fetch_assoc($sql_)['count'];
                            $driver['count'] = $count;
                            $drivers_arr[] = $driver;
                        }

                        $count = array();
                        foreach ($drivers_arr as $driver => $row)
                        {
                            $count[$driver] = $row['count'];
                        }
                        array_multisort($count, SORT_ASC, $drivers_arr);


        foreach ($drivers_arr as $driver_index => $driver) {
            if ($driver_index == 0) {
                $class = "row_highlight";
                $text = '<i class="fa fa-share-square"></i>&nbsp; ';
            }
            else {
                $class = "";
                $text = "";
            }
            echo '
                <div class="row '. $class .'">
                    <div class="cell wid_30">
                    '. $text .'
                    '. $driver['user_firstName'] . ' ' . $driver['user_lastName'] .'
                    </div>
                    <div class="cell">
                    '. $driver['phone'] .'
                    </div>
                    <div class="cell cell_right">
                        <strong>'. $driver['count'] .' Ongoing Orders</strong>
                    </div>
                </div>
            ';
        }

                        echo '<br />';
                    }


						$query_getOrders = mysql_query("
                            SELECT 
                                orders.order_id AS ref_id, orders.*, 
                                order_status.*, 
                                order_costs.*, 
                                users.user_id,
                                users.user_firstName, 
                                users.user_lastName,
                                users.user_phone
                            FROM orders 
                            LEFT JOIN users ON users.user_id = orders.order_user
                            LEFT JOIN order_status ON order_status.order_status_id = orders.order_status
                            LEFT JOIN order_costs ON order_costs.order_id = orders.order_id
                            LEFT JOIN addresses ON addresses.address_order = orders.order_id
                            ORDER BY orders.order_id DESC LIMIT 100
						");

						if($query_getOrders)
						{
                            if (mysql_num_rows($query_getOrders) == 0)
                            {
                                echo "There are no orders.";
                            }
							else
							{
								while($order = mysql_fetch_assoc($query_getOrders))
								{                                    
									$query_getAddress = mysql_query("SELECT * FROM addresses WHERE address_order = ". $order['ref_id'] ." LIMIT 1");
                                    
                                    $query_getDriver = mysql_query("
                                        SELECT users.user_id, users.user_firstName, users.user_lastName, orders.order_id FROM drivers
                                        LEFT JOIN users
                                        ON drivers.user = users.user_id
                                        LEFT JOIN orders
                                        ON orders.order_driver = drivers.user
                                        WHERE orders.order_id = ". $order['ref_id'] ."

                                    ");
                                    
									$user = mysql_fetch_assoc($query_getUser);
                                    $address = mysql_fetch_assoc($query_getAddress); 
									$driver = mysql_fetch_assoc($query_getDriver); 

									$time1 = new DateTime($order['init_time']);
									$time2 = new DateTime($order['complete_time']);
									$interval = $time1->diff($time2);
									$elapsed = $interval->format('%h h %i m %S s');

									echo'
										<form class="manage_order push_bottom_40" id="'. $order['ref_id'] .'">
                                        
                                            <div class="manage_order_heading heading">
                                                <div class="float_right">
                                                    <button class="edit_order"><i class="fa fa-pencil"></i></button>
                                                    <button class="confirm_action" data-desc="Are you sure you want to delete this order?" data-button="Delete Order" data-action="delete_order" data-value="'. $order['ref_id'] .'"><i class="fa fa-times"></i></button>
                                                </div>
                                                Order #'. $order['ref_id'] .' ('. date("M d Y @ g:i a",strtotime($order['init_time'])) .')
                                            </div>
                                    ';

										echo '
                                            <div class="push_top">
                                                <a class="manage_order_status" id="ms_'. $order['order_status'] .'" data-order_id="'. $order['ref_id'] .'">
                                                '. $order['order_status_name'] .'
                                        ';
                                        if ($order['order_status'] == 'COM' || $order['order_status'] == 'ARCH')
                                        {
                                            echo ' ('. $elapsed .')';
                                        }
                                        echo '
                                            </a>
                                        ';

                                                if ($order['order_driver'] == null && $order['order_status'] == 'APP')
                                                {
                                                    echo '<div class="notice">ASSIGN A DRIVER</div>';
                                                }




											echo '
											</div>
                                            <div class="manage_order_toggle">

                                            <div class="manage_order_info">
                                                <div class="row row_baseline row_collapse">
                                                    <div class="cell wid_50">
                                                        <div class="title">Order Description</div>
                                                        <div class="desc"><pre>'. $order['order_desc'] .'</pre></div>
                                                    </div>
                                                    <div class="cell wid_50">
                                                        <div class="title">Order Location</div>
                                                        <div class="location">'. $order['order_location'] .'</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="manage_order_customer">
                                                <div class="row row_baseline row_collapse">
                                                    <div class="cell wid_50">
                                                        <div class="title">Customer Info</div>
                                                        <div class="info">
                                                            <a class="customer_name" href="customer?id='. $order['user_id'] .'">'. $order['user_firstName'] .' '. $order['user_lastName'] .'</a><br />
                                                            <button class="order_text" data-phone="'.$order['user_phone'].'"><i class="fa fa-envelope"></i></button>
                                                            <a class="order_phone" href="tel:'. $order['user_phone'] .'"><i class="fa fa-phone"></i>&nbsp; +'. $order['user_phone'] .'</a>
                                                        </div>
                                                    </div>
                                                    <div class="cell wid_50">
                                                        <div class="title">Delivery Address</div>
                                                        <div class="address">
                                                            <div class="address_street">'. $address['address_street'] .'</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="manage_order_misc">
                                                <div class="row row_baseline row_collapse">
                                                    <div class="cell wid_50">
                                                        <div class="title">Payment Info &nbsp; <button class="edit_order_cost"><i class="fa fa-pencil"></i></button></div>
                                                        <div class="cell">
                                                            <div class="cost_amount">Amount Paid: $<span>'. $order['amount'] .'</span></div>
                                                            <div class="cost_margin">Margin: <span>'. ($order['margin'] * 100 - 100) .'</span>%</div>
                                                            <div class="cost_delivery_fee">Delivery Fee: $<span>'. $order['delivery_fee'] .'</span></div>
                                                            <div class="cost">Promo: <span>'. $order['promo'] .'</span></div>
                                                            <div class="cost">Discount: $<span>'. number_format($order['discount_amount'], 2) .'</span></div>
                                                            <div class="cost">Driver Tip: $<span>'. number_format($order['tip'], 2) .'</span></div>
                                                            <div class="cost">Total: $<span>'. number_format( ((( ($order['amount'] - $order['discount_amount']) * $order['margin']) + $order['delivery_fee'] + $order['tip']) * $order['stripe_margin']), 2 ) .'</span></div>
                                            ';
                                            if ($order['order_status'] == 'COM')
                                            {
                                                echo '<button class="collect_payment">Collect Payment</button>';
                                            }
                                            echo '
                                                        </div>
                                                    </div>
                                                    <div class="cell wid_50">
                                                        <div class="title">Driver Management</div>
                                                        <div class="cell">';
                                                        if ($order['order_driver'] == 0)
                                                        {
                                                        
                                                            echo'
                                                            <select class="select_driver">
                                                            ';

                                                            $query_getDrivers = mysql_query("
                                                                SELECT * FROM drivers 
                                                                INNER JOIN users 
                                                                ON drivers.user = users.user_id
                                                            ");

                                        				while($driver = mysql_fetch_assoc($query_getDrivers))
                                                        {
                                                            if ($order['order_driver'] == 0)
                                                            {
                                                                
                                                            }
                                                            echo '<option value="'. $driver['user'] .'">'.$driver['user_firstName'].' '. $driver['user_lastName'] .'</option>';
                                                        }
                                                            echo ' 
                                                            </select>
                                                            <button class="assign_driver" data-order_id="'. $order['ref_id'] .'">Assign Driver</button>';
                                                        }
                                                        else 
                                                        {
                                                            echo 'Assigned Driver<br />'. $driver['user_firstName'] . ' ' . $driver['user_lastName'];
                                                            echo '&nbsp; (<a class="unassign_driver" href="#">Unassign</a>)<br />';
                                                        }    
                                                        if ($order['order_status'] == 'ARR')
                                                        {
                                                            echo '<button class="confirm_action" data-desc="Mark order #'. $order['order_id'] .' as complete?" data-button="Mark Complete" data-value="' . $order['order_id'] . '" data-action="mark_complete"><i class="fa fa-check-circle"></i>&nbsp; Mark As Complete</button><br /><br />';
                                                        }

                                                        echo '
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            </div>

										</form>	
									';

								}
							}
						}
					

					?>

	</div>

<?php 
	}
?>



</div>
	<!-- end #section, #wrap -->

</div> 
<!-- end #container -->

<?php include "footer.php" ?>

</div>
<!-- end overview -->


</body>
</html>