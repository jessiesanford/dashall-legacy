<?php 

require('twilio.class.php');
require('dashcash.class.php');
require('stripe.class.php');

class Order 
{
	// always letting the user know what's up on the fly with an alert array that gets returned via JSON/AJAX.
	public $return_arr = array();
	public $alerts = array();

	// when user fills out desc/location dash boxes and submits, we initialize them a new order
	public static function order_init($values)
	{
		if (empty($_POST['dashbox_location']))
		{
			$return_arr['form_check'] = "error";
			$return_arr['error_source'] = "dashbox_location";
			$alerts[] = "Please specify a location/establishment you want delivery from.";
		}
		else if (empty($_POST['dashbox_desc']))
		{
			$return_arr['form_check'] = "error";
			$return_arr['error_source'] = "dashbox_desc";
			$alerts[] = "Please specify what you would like delivered.";
		}
		else
		{
			$sql = mysql_query("SELECT * FROM orders WHERE order_user = " . mysql_real_escape_string($_SESSION['user_id']) . " AND order_active = 1 LIMIT 1");
			$result = mysql_fetch_assoc($sql); 

			if (!$result)
			{
				$alerts[] = "Please review our disclaimer.";

				if ($query === false)
				{
					$alerts[] = mysql_error(); //debugging purposes, uncomment when needed
				}

			}
			else {
				$return_arr['form_check'] = 'error';
				$alerts[] = "You have an ongoing order.";
			}
		}

		$return_arr['alert'] = $alerts[0];
		echo json_encode($return_arr);
	}

	public static function order_init_confirm($values)
	{
		if (empty($_POST['dashbox_desc']) || empty($_POST['dashbox_location']))
		{
			$return_arr['form_check'] = 'error';
			$alerts[] = "We're gonna need a little more information about your order...";
		}
		else
		{
			$sql = mysql_query("SELECT * FROM orders WHERE order_user = " . mysql_real_escape_string($_SESSION['user_id']) . " AND order_active = 1 LIMIT 1");
			$result = mysql_fetch_assoc($sql); 

			if (!$result)
			{
				$query = mysql_query("
						INSERT INTO orders(order_user, order_desc, order_location, order_status, order_active, init_time)
						VALUES (
							" . mysql_real_escape_string($_SESSION['user_id']) . ",
							'" . mysql_real_escape_string($_POST['dashbox_desc']) . "',
							'" . mysql_real_escape_string($_POST['dashbox_location']) . "',
							'AWD',
							1,
							'".TIMESTAMP."'
						)
				");
				if ($query === false)
				{
					$alerts[] = mysql_error(); //debugging purposes, uncomment when needed
				}
				else {
					$alerts[] = "We've created you a new order.<br />";
				}
			}
			else {
				$return_arr['form_check'] = 'error';
				$alerts[] = "You have an ongoing order.";
			}
		}

		$return_arr['alert'] = $alerts[0];
		echo json_encode($return_arr);
	}

	public static function submit_address($values)
	{

		if (empty($_POST['address_street']))
		{
			$return_arr['form_check'] = 'error';
			$alerts[] =  "Please provide a valid street address.";
		}

		else if ($_POST['address_city'] != "St. Johns")
		{
			$return_arr['form_check'] = 'error';
			$alerts[] =  "We currently only deliver to the St. John's municipality.";
		}

		if(!empty($return_arr['form_check'])) /*check for an empty array, if there are errors, they're in this array (note the ! operator)*/
		{

			$alerts[] = 'Something went wrong, please try again.';
		}
		else 
		{
			$sql = mysql_query("SELECT order_id, order_desc, order_location FROM orders WHERE order_user = " . mysql_real_escape_string($_SESSION['user_id']) . " AND order_active = 1 LIMIT 1");
			$order = mysql_fetch_assoc($sql); 

			// create addresses row
			mysql_query("
				INSERT INTO addresses(address_order, address_user, address_street, address_postal, address_city, address_province)
				VALUES(
					" . mysql_real_escape_string($order['order_id']) . ",
					" . mysql_real_escape_string($_SESSION['user_id']) . ",
					'" . mysql_real_escape_string($_POST['address_street']) ."',
					'',
					'" . mysql_real_escape_string($_POST['address_city']) . "',
					'NL'
				)
			");

			// create order_costs row
			mysql_query("
				INSERT INTO order_costs(order_id, amount, margin, stripe_margin, delivery_fee)	
				VALUES(
					" . mysql_real_escape_string($order['order_id'])  .",
					0.00, 
					1.10,
					1.03, 
					7
				)
			");

			// move the stage
			mysql_query("
				UPDATE orders SET order_status = 'AWD_S2' WHERE order_id = ". mysql_real_escape_string($order['order_id']) ."
			");

			$alerts[] = "You're order information has been updated.";
		}

		$return_arr['alert'] = $alerts[0];
		echo json_encode($return_arr);
	}

	public static function order_pay_auth($values)
	{
		// grab user/order info
		$sql = mysql_query("
			SELECT 
			orders.order_id, orders.order_user,
			order_costs.pay_auth,
			users.user_firstName, users.user_lastName, users.user_email, users.user_phone
			FROM orders
			INNER JOIN order_costs ON order_costs.order_id = orders.order_id
			INNER JOIN users ON users.user_id = orders.order_user
			WHERE (orders.order_user = " . mysql_real_escape_string($_SESSION['user_id']) . " AND orders.order_active = 1) LIMIT 1
		");
		$order = mysql_fetch_assoc($sql); 

		// determine if user already logged in stripe customer database
		$sql = mysql_query("SELECT * FROM stripe_customers WHERE user_id = ". mysql_real_escape_string($_SESSION['user_id']) ."");
		$stripe_customer = mysql_fetch_assoc($sql); 


		if (!$stripe_customer)
		{
			// create the customer since it does not exist. 
			$stripe_call = Stripe::create_customer($_POST['stripeToken'], $order);
		}

		// were we successful in creating or retrieving the customer?
		if ($stripe_call['error'] == true)
		{
			$return_arr['form_check'] = "error";
		}
		else 
		{
			$sql = mysql_query("UPDATE orders SET order_status = 'PDR' WHERE order_id = ". $order['order_id'] ."");
			$sql = mysql_query("UPDATE order_costs SET pay_auth = 1 WHERE order_id = ". $order['order_id'] ."");

			// DELEGATE ORDERS TO  SPECIFIC ACTIVE DRIVER (ACTIVE)
			if (MANAGEMENT_MODE == 1)
			{
				self::active_delegation($order['order_id']);
			}
			// DELEGATE ORDERS TO ANY ACTIVE DRIVERS (PASSIVE)
			else if (MANAGEMENT_MODE == 2)
			{
				self::passive_delegation($order['order_id']);
			}
			// DELEGATE ORDERS TO ANY ACTIVE DRIVERS (PASSIVE)
			else if (MANAGEMENT_MODE == 3)
			{
				self::scheduled_delegation($order['order_id']);
			}
			else
			{
				$sql =  mysql_query("
					SELECT 
						orders.order_id, orders.order_desc, orders.order_location, users.user_firstName, 
						addresses.*,
						users.user_firstName, users.user_lastName, users.user_phone 
					FROM orders
					INNER JOIN users ON users.user_id = orders.order_user
					INNER JOIN addresses ON addresses.address_order = orders.order_id
					WHERE orders.order_id = ". mysql_real_escape_string($order['order_id']) ."
				");

				$order = mysql_fetch_assoc($sql); 

				$twilio_message ="Order Desc:\n" . $order['order_desc'] . "\nOrder Location:\n" . $order['order_location'] . "\n\nName:\n" . $order['user_firstName'] . "\nPhone:\n" . $order['user_phone'] . "\n\nAddress:\n" . $order['address_street'];
			}
			// everything went through, message me and Jan
			Twilio::notify_management('A new order has arrived.');
		}


		$alerts[] = $stripe_call['alert'];
		$return_arr['alert'] = $alerts[0];
		echo json_encode($return_arr);

	}

	// if the users card is saved (via tokenization) then we can just grab that info here
	public static function order_pay_auth_logged($values)
	{
		// determine if user already logged in stripe customer database
		$sql = mysql_query("SELECT * FROM stripe_customers WHERE user_id = ". mysql_real_escape_string($_SESSION['user_id']) ."");
		$stripe_customer = mysql_fetch_assoc($sql); 

		// grab user/order info
		$sql = mysql_query("
			SELECT 
			orders.order_id, orders.order_user,
			order_costs.pay_auth,
			users.user_firstName, users.user_lastName, users.user_email, users.user_phone
			FROM orders
			INNER JOIN order_costs ON order_costs.order_id = orders.order_id
			INNER JOIN users ON users.user_id = orders.order_user
			WHERE (orders.order_user = " . mysql_real_escape_string($_SESSION['user_id']) . " AND orders.order_active = 1) LIMIT 1
		");
		$order = mysql_fetch_assoc($sql); 

		// just check and make sure it does exist first
		if ($stripe_customer)
		{
			$stripe_call = Stripe::verify_customer($stripe_customer['stripe_id']);
		}		

		if ($stripe_call['error'] == true)
		{
			$return_arr['form_check'] = "error";
		}
		else
		{
			// grab user/order info
			$sql = mysql_query("
				SELECT 
				orders.order_id, orders.order_user,
				order_costs.pay_auth,
				users.user_firstName, users.user_lastName, users.user_email, users.user_phone
				FROM orders
				INNER JOIN order_costs ON order_costs.order_id = orders.order_id
				INNER JOIN users ON users.user_id = orders.order_user
				WHERE (orders.order_user = " . mysql_real_escape_string($_SESSION['user_id']) . " AND orders.order_active = 1) LIMIT 1
			");
			$order = mysql_fetch_assoc($sql); 
			
			// update local db 
			$sql = mysql_query("UPDATE orders SET order_status = 'PDR' WHERE order_id = ". $order['order_id'] ."");
			$sql = mysql_query("UPDATE order_costs SET pay_auth = 1 WHERE order_id = ". $order['order_id'] ."");


			// DELEGATE ORDERS TO  SPECIFIC ACTIVE DRIVER (ACTIVE)
			if (MANAGEMENT_MODE == 1)
			{
				self::active_delegation($order['order_id']);
			}
			// DELEGATE ORDERS TO ANY ACTIVE DRIVERS (PASSIVE)
			else if (MANAGEMENT_MODE == 2)
			{
				self::passive_delegation($order['order_id']);
			}
			else if (MANAGEMENT_MODE == 3)
			{
				self::scheduled_delegation($order['order_id']);
			}
			else
			{
				$sql =  mysql_query("
					SELECT 
						orders.order_id, orders.order_desc, orders.order_location, users.user_firstName, 
						addresses.*,
						users.user_firstName, users.user_lastName, users.user_phone 
					FROM orders
					INNER JOIN users ON users.user_id = orders.order_user
					INNER JOIN addresses ON addresses.address_order = orders.order_id
					WHERE orders.order_id = ". mysql_real_escape_string($order['order_id']) ."
				");

				$order = mysql_fetch_assoc($sql); 

				$twilio_message ="Order Desc:\n" . $order['order_desc'] . "\nOrder Location:\n" . $order['order_location'] . "\n\nName:\n" . $order['user_firstName'] . "\nPhone:\n" . $order['user_phone'] . "\n\nAddress:\n" . $order['address_street'];
			}

			// everything went through, message me and Jan
			Twilio::notify_management('A new order has arrived.');
		}	


		$alerts[] = $stripe_call['alert'];
		$return_arr['alert'] = $alerts[0];
		echo json_encode($return_arr);
	}

	public static function delete_credit_card($values)
	{
		mysql_query("DELETE FROM stripe_customers WHERE user_id = " . mysql_real_escape_string($_SESSION['user_id']) ."");

		$alerts[] = "Your credit card information has been removed.";
		$return_arr['alert'] = $alerts[0];
		echo json_encode($return_arr);
	}

	// this needs to be done better
	public static function add_promo($values)
	{
		$sql = mysql_query("SELECT order_id FROM orders WHERE order_user = ". $_SESSION['user_id'] ." AND order_active = 1");
		$order = mysql_fetch_assoc($sql);

		$sql = mysql_query("SELECT dashcash_balance FROM users WHERE user_id = ". $_SESSION['user_id'] ."");
		$dashcash = mysql_fetch_assoc($sql);

		$sql = mysql_query("SELECT COUNT(*) as counter FROM orders WHERE order_user = ". $_SESSION['user_id'] ." AND order_status = 'ARCH'");
		$order_counter = mysql_fetch_assoc($sql);

		if ($_POST['promo_method'] == 'coupon_redeem')
		{
			$sql = mysql_query("SELECT * FROM promos WHERE promo_code = '". $_POST['promo_data'] ."' LIMIT 1");
			$rows = mysql_num_rows($sql);

			if ($rows > 0)
			{
				$promo = mysql_fetch_assoc($sql);

				if ($promo['promo_code'] == "FIRSTDASH" && $order_counter['counter'] == 0)
				{
					mysql_query("UPDATE orders SET order_status = 'AWD_S3', promo = '". $promo['promo_code'] ."' WHERE order_id = ". $order['order_id'] ."");
					mysql_query("UPDATE order_costs SET discount_amount = 5 WHERE order_id = ". $order['order_id'] ."");

					$alerts[] = "Promotion Added: " . $promo['promo_desc'];
				}
				else if ($promo['promo_code'] == "MUN16" && $order_counter['counter'] == 0)
				{
					mysql_query("UPDATE orders SET order_status = 'AWD_S3', promo = '". $promo['promo_code'] ."' WHERE order_id = ". $order['order_id'] ."");
					mysql_query("UPDATE order_costs SET discount_amount = 5 WHERE order_id = ". $order['order_id'] ."");

					$alerts[] = "Promotion Added: " . $promo['promo_desc'];
				}
				else 
				{
					$return_arr['form_check'] = "error";
					$alerts[] = "You are not eligible for this coupon or it has expired.";
				}
			}
			else 
			{
				$return_arr['form_check'] = "error";
				$alerts[] = "Coupon does not exist.";
			}

		}
		else if ($_POST['promo_method'] == 'competition') 
		{
			mysql_query("UPDATE orders SET order_status = 'AWD_S3' WHERE order_id = ". $order['order_id'] ."");
			mysql_query("INSERT INTO order_meta(order_id, competition_code) VALUES (". $order['order_id'] .", '". $_POST['promo_data'] ."')");

			$alerts[] = "Competition Code Added". $promo['promo_code'];
		}
		else if ($_POST['promo_method'] == 'dashcash_redeem')
		{
			if ($_POST['promo_data'] > $dashcash['dashcash_balance'])
			{
				$return_arr['form_check'] = "error";
				$alerts[] = "This amount exceeds your DashCash balance.";
			}
			else if ($_POST['promo_data'] > 25.00)
			{
				$return_arr['form_check'] = "error";
				$alerts[] = "Maximum redeemable amount per order is $25.";
			}
			else 
			{
				// potential security risk
				mysql_query("UPDATE orders SET order_status = 'AWD_S3', promo = 'DASHCASH' WHERE order_id = ". $order['order_id'] ."");
				mysql_query("UPDATE order_costs SET discount_amount = ". $_POST['promo_data'] ." WHERE order_id = ". $order['order_id'] ."");

				$alerts[] = 'Cool, we will apply $'. $_POST['promo_data'] .' DashCash towards your order.';
			}
		}
		else 
		{
			mysql_query("UPDATE orders SET order_status = 'AWD_S3' WHERE order_id = ". $order['order_id'] ."");
			$alerts[] = "We've updated your order.";
		}

		$return_arr['alert'] = $alerts[0];
		echo json_encode($return_arr);
	}

	public static function order_cancel($values)
	{
		$sql = mysql_query("
			SELECT orders.*, order_status.* FROM orders 
			LEFT JOIN order_status ON order_status.order_status_id = orders.order_status
			WHERE orders.order_user = ". mysql_real_escape_string($_SESSION['user_id']) ."
			AND orders.order_active = 1
		");
		$order = mysql_fetch_assoc($sql);

		if ($order['order_status_id_num'] > 1)
		{
			$alerts[] = "Your order has already been processed, you cannot cancel it.";
		}
		else 
		{
			mysql_query("
				UPDATE orders  
				SET order_active = 0, order_status = 'CANC'
				WHERE order_user = " . mysql_real_escape_string($_SESSION['user_id']) . " 
				AND order_active = 1
			");
			
			$alerts[] = "We've cancelled your order. ";
		}

		$return_arr['alert'] = $alerts[0];
		echo json_encode($return_arr);
	}

	// archives the users current completed order and reverts them back to the new order view
	public static function order_feedback($values)
	{
		$sql = mysql_query("SELECT order_id, order_driver FROM orders WHERE order_user = ". mysql_real_escape_string($_SESSION['user_id']) ." AND order_active = 1");
		$order = mysql_fetch_assoc($sql);

		// update the tip
		mysql_query("
			UPDATE order_costs  
			SET tip = ". $_POST['tip_amount'] ."
			WHERE order_id = " . mysql_real_escape_string($order['order_id']) . " 
		");

		// update the driver payroll tip
		mysql_query("
			UPDATE driver_payroll  
			SET tip = ". $_POST['tip_amount'] ."
			WHERE order_id = " . mysql_real_escape_string($order['order_id']) . " 
		");

		// insert the order rating
		mysql_query("
			INSERT INTO order_ratings(order_id, speed, correctness, driver, feedback)
			VALUES(
				". mysql_real_escape_string($order['order_id']) .",
				". mysql_real_escape_string($_POST['timing_rating']) .",
				". mysql_real_escape_string($_POST['correctness_rating']) .",
				". mysql_real_escape_string($_POST['driver_rating']) .",
				'". mysql_real_escape_string($_POST['order_feedback']) ."'
			)  
		");

		$alerts[] = self::order_process_payment($order['order_id']);

		$return_arr['alert'] = $alerts[0];
		echo json_encode($return_arr);
	}









	public static function order_process_payment($order_id)
	{
		$sql = mysql_query("
			SELECT * FROM order_costs
			WHERE order_costs.order_id = ". mysql_real_escape_string($order_id) ."
		");

		$order = mysql_fetch_assoc($sql);

		if ($order['pay_capture'] == 0)
		{ 
			$sql = mysql_query("
				SELECT * FROM orders
				INNER JOIN users ON users.user_id = orders.order_user
	            INNER JOIN order_costs ON order_costs.order_id = orders.order_id
				WHERE orders.order_id = ". mysql_real_escape_string($order_id) ."
			");

			$order = mysql_fetch_assoc($sql);

			$calc_amount = self::calculate_total($order);

			// determine if user already logged in stripe customer database
			$sql = mysql_query("SELECT * FROM stripe_customers WHERE user_id = ". $order['order_user'] ."");
			$stripe_customer = mysql_fetch_assoc($sql); 

			$stripe_call = Stripe::charge_customer($stripe_customer['stripe_id'], $order_id, $calc_amount);

			if ($stripe_call['error'] == true)
			{
				$return_arr['form_check'] = "error";
				return $stripe_call['alert'];
			}
			else 
			{
				mysql_query("
					UPDATE order_costs
					SET pay_capture = 1
					WHERE order_id = ". mysql_real_escape_string($order_id) ."
				");

				mysql_query("
					UPDATE orders  
					SET order_active = 0, order_status = 'ARCH'
					WHERE order_id = " . mysql_real_escape_string($order_id) . " 
					AND order_active = 1
				");



				// check for first order, if first give referral credit
				$sql = mysql_query("SELECT COUNT(*) as order_count FROM orders WHERE order_user = ". $order['order_user'] ."");
				$order_count = mysql_fetch_assoc($sql);

				if ($order_count['order_count'] == 1)
				{
					self::give_referral_credit($order['order_user']);
				}

				if ($order['promo'] == "DASHCASH")
				{
					DashCash::add_funds($order['order_user'], (-1 * $order['discount_amount']), "Redeemed DashCash for order.");
				}

				return "Your order review has been submitted, thank you!";
			}
		}

		else 
		{
			return 'Payment has already been processed';
		}


	}


	public static function calculate_total($order)
	{
		return number_format( ((( ($order['amount'] - $order['discount_amount']) * $order['margin']) + $order['delivery_fee'] + $order['tip']) * $order['stripe_margin']), 2 );
	}


	public static function give_referral_credit($user_id)
	{
		$sql = mysql_query("SELECT * FROM referrals WHERE user_id = ". mysql_real_escape_string($user_id) ."");
		$referral = mysql_fetch_assoc($sql);

		DashCash::add_funds($referral['ref_by'], 2.00, "A user (". $referral['user_id'] .") you referred made an order.");
	}

	public static function active_delegation($order_id)
	{
		$sql = mysql_query("
			SELECT settings.*, users.user_phone FROM settings
			INNER JOIN users ON settings.value = users.user_id
			WHERE settings.name = 'active_driver'
		");

		while ($driver = mysql_fetch_assoc($sql))
		{
			Twilio::send_text($driver['user_phone'], 'A new order is awaiting delivery, visit the DashAll orders page to accept it and make some money! http://dashall.ca/driver');
		}

	}

	public static function passive_delegation($order_id)
	{
		$sql = mysql_query("
			SELECT drivers.*, users.user_phone FROM drivers
			INNER JOIN users ON users.user_id = drivers.user
			WHERE drivers.notify_orders = 1
		");

		while ($driver = mysql_fetch_assoc($sql))
		{
			Twilio::send_text($driver['user_phone'], 'A new order is awaiting delivery, visit the DashAll orders page to accept it and make some money! http://dashall.ca/driver');
		}

	}

	public static function scheduled_delegation($order_id)
	{
		$sql = mysql_query("
			SELECT users.user_firstName, users.user_lastName, users.user_phone, driver_shifts. * 
			FROM driver_shifts
			INNER JOIN users ON users.user_id = driver_shifts.driver_id
			WHERE '". TIMESTAMP ."' BETWEEN start_datetime AND end_datetime

		");

		while ($driver = mysql_fetch_assoc($sql))
		{
			Twilio::send_text($driver['user_phone'], 'A new order is awaiting delivery, visit the DashAll orders page to accept it and make some money! http://dashall.ca/driver');
		}

	}

	public static function scheduled_delegation_old($order_id)
	{
		$sql = mysql_query("
			SELECT users.user_firstName, users.user_lastName, users.user_phone, driver_shifts. * 
			FROM driver_shifts
			INNER JOIN users ON users.user_id = driver_shifts.driver_id
			WHERE '". TIMESTAMP ."' BETWEEN start_datetime AND end_datetime
		");

        $drivers_arr = array(); 

        while ($driver = mysql_fetch_assoc($sql));
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

        $assignable = $drivers_arr[0];
        $phone = $assignable['phone'];

		foreach ($drivers as $driver)
		{
			Twilio::send_text($phone, $assignable['first_name'] . ', A new order is awaiting delivery, visit the DashAll orders page to accept it and make some money! http://dashall.ca/driver');
		}

		// Twilio::send_text('9022376300', 'A new order is awaiting delivery, visit the DashAll orders page to accept it and make some money! http://dashall.ca/driver');

	}



}

?>
