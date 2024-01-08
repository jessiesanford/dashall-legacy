<?php

class Manage 
{

	public static function update_order_status($values)
	{

		if ($_POST['order_status'] == "COM")
		{
			$sql = "UPDATE orders
					SET order_status = '" . mysql_real_escape_string($_POST['order_status']) . "', complete_time = '" . mysql_real_escape_string(TIMESTAMP) . "'
					WHERE order_id = " . mysql_real_escape_string($_POST['order_id']) . "
					LIMIT 1
					";
		}
		else 
		{
			$sql = "UPDATE orders
					SET order_status = '" . mysql_real_escape_string($_POST['order_status']) . "'
					WHERE order_id = " . mysql_real_escape_string($_POST['order_id']) . "
					LIMIT 1
					";
		}
		 
		$result = mysql_query($sql);

		echo "Order status set to " . $_POST['order_status'];
	}


	public static function update_order_status_ind($values)
	{
		echo '<a href="#" id="os_APP" class="update_order_status button large block align_center" data-order_id="' . $_POST['order_id'] . '"><i class="fa fa-lg fa-thumbs-up"></i>&nbsp; Approve</a><br />';
		echo '<a href="#" id="os_DEN" class="update_order_status button large block align_center" data-order_id="' . $_POST['order_id'] . '"><i class="fa fa-lg fa-times-circle"></i>&nbsp; Deny</a>';
	}


	public static function manage_order_cost($values)
	{
		$sql = "UPDATE order_costs
				SET 
				amount = ".mysql_real_escape_string($_POST['amount']).", 
				margin = ".mysql_real_escape_string((($_POST['margin']) / 100 + 1)).", 
				delivery_fee = ".mysql_real_escape_string($_POST['delivery_fee'])."
				WHERE order_id = ". mysql_real_escape_string($_POST['order_id']) ."
				";
		 
		$result = mysql_query($sql);	

		echo "Order cost has been updated.";
	}

	public static function delete_order($values)
	{
		$sql = "DELETE FROM orders
				WHERE order_id = " . $_POST['order_id'] ."";
		 
		$result = mysql_query($sql);

		echo "Order Deleted";
	}

	public static function update_order($values)
	{
		$sql = "UPDATE orders
				SET 
				order_desc = '". mysql_real_escape_string($_POST['order_desc']) ."',
				order_location = '". mysql_real_escape_string($_POST['order_location']) ."'
				WHERE order_id = ". mysql_real_escape_string($_POST['order_id']) ."
				";
		$result = mysql_query($sql);

		$sql = "UPDATE addresses
				SET 
				address_street = '". mysql_real_escape_string($_POST['order_address_street']) ."'
				WHERE address_order = ". mysql_real_escape_string($_POST['order_id']) ."
				";
		$result = mysql_query($sql);

		echo "Order Updated";
	}

	public static function assign_driver($values)
	{
		$sql = mysql_query("
			UPDATE orders
			SET 
			order_driver = ". mysql_real_escape_string($_POST['order_driver']) .",
			order_status = 'APP_S2'
			WHERE order_id = ". mysql_real_escape_string($_POST['order_id']) ."
		");

		$sql = mysql_query("
			SELECT 
				users.user_id, users.user_firstName, users.user_lastName, users.user_phone, 
				orders.order_driver, orders.order_id FROM drivers
			INNER JOIN users
			ON drivers.user = users.user_id
			INNER JOIN orders
			ON orders.order_driver = drivers.user
			WHERE orders.order_id = ". mysql_real_escape_string($_POST['order_id']) ."
		");

		$driver = mysql_fetch_assoc($sql); 

		$sql3 = "
			SELECT 
				orders.order_id, orders.order_desc, orders.order_location, users.user_firstName, 
				addresses.*,
				users.user_firstName, users.user_lastName, users.user_phone 
			FROM orders
			INNER JOIN users ON users.user_id = orders.order_user
			INNER JOIN addresses ON addresses.address_order = orders.order_id
			WHERE orders.order_id = ". mysql_real_escape_string($_POST['order_id']) ."
		";

		$result3 = mysql_query($sql3);

		$order = mysql_fetch_assoc($result3); 

		$twilio_message ="Order Desc:\n" . $order['order_desc'] . "\nOrder Location:\n" . $order['order_location'] . "\n\nName:\n" . $order['user_firstName'] . "\nPhone:\n" . $order['user_phone'] . "\n\nAddress:\n" . $order['address_street'];
		Twilio::send_text($driver['user_phone'], $twilio_message);

		echo ':'.$_POST['order_id'];
	}

	public static function unassign_driver($values)
	{
		$sql = mysql_query("UPDATE orders
				SET 
				order_driver = null,
				order_status = 'APP'
				WHERE order_id = ". mysql_real_escape_string($_POST['order_id']) ."
				");

		echo "Driver Unassigned";
	}

	public static function give_referral_credit($user_id)
	{
		$sql = mysql_query("SELECT * FROM referrals WHERE user_id = ". mysql_real_escape_string($user_id) ."");
		$referral = mysql_fetch_assoc($sql);

		DashCash::add_funds($referral['ref_by'], 2.00, "A user (". $referral['user_id'] .") you referred made an order.");
	}

	public static function promote_to_driver($values)
	{
		$sql = mysql_query("INSERT INTO drivers(user) VALUES (". mysql_real_escape_string($_POST['user_id']) .")");
		$sql = mysql_query("UPDATE users SET user_group = 2 WHERE user_id = ". mysql_real_escape_string($_POST['user_id']) ."");

		echo 'User Promoted.';	
	}

	public static function remove_driver($values)
	{
		$sql = mysql_query("DELETE FROM drivers WHERE user = ". mysql_real_escape_string($_POST['user_id']) ."");
		$sql = mysql_query("UPDATE users SET user_group = 1 WHERE user_id = ". mysql_real_escape_string($_POST['user_id']) ."");

		echo 'User Demoted.';	
	}

	public static function mark_complete($values)
	{
		$sql = mysql_query("
			SELECT orders.*, order_costs.*, users.user_phone FROM orders 
			INNER JOIN order_costs ON order_costs.order_id = orders.order_id 
			INNER JOIN users ON users.user_id = orders.order_user 
			WHERE orders.order_id = ". mysql_real_escape_string($_POST['order_id']) ." LIMIT 1
		");
		$order = mysql_fetch_assoc($sql);

		mysql_query("
			UPDATE orders
			SET order_status = 'COM', complete_time = '" . mysql_real_escape_string(TIMESTAMP) . "'
			WHERE order_id = " . mysql_real_escape_string($_POST['order_id']) . "
			LIMIT 1
		");

		mysql_query("
			INSERT INTO driver_payroll(order_id, driver_id, delivery_fee, tip, time)
			VALUES 
			(
				". mysql_real_escape_string($order['order_id']) .",
				". mysql_real_escape_string($order['order_driver']) .",
				". mysql_real_escape_string($order['delivery_fee'] * 0.9) .",
				". mysql_real_escape_string($order['tip']) .",
				'". TIMESTAMP ."'
			)
		");

		Twilio::send_text($order['user_phone'], "Thanks for using DashAll! Please let us know how we did by clicking on this link: www.dashall.ca");

		$alerts[] = "Order #". $_POST['order_id'] ." has been marked as complete";
		$return_arr['alert'] = $alerts[0];
		echo json_encode($return_arr);
	}

	public static function collect_payment($values)
	{
		$order_id = $_POST['order_id'];

		$sql = mysql_query("
			SELECT order_costs.*, orders.order_user FROM order_costs
            INNER JOIN orders ON orders.order_id = order_costs.order_id
			WHERE order_costs.order_id = ". mysql_real_escape_string($order_id) ."
		");

		$order = mysql_fetch_assoc($sql);

		if ($order['pay_capture'] == 0)
		{ 
			// determine if user already logged in stripe customer database
			$sql = mysql_query("SELECT * FROM stripe_customers WHERE user_id = ". mysql_real_escape_string($order['order_user']) ."");
			$stripe_customer = mysql_fetch_assoc($sql); 

			$sql = mysql_query("
				SELECT * FROM orders
				INNER JOIN users ON users.user_id = orders.order_user
	            INNER JOIN order_costs ON order_costs.order_id = orders.order_id
				WHERE orders.order_id = ". mysql_real_escape_string($order_id) ."
			");

			$order = mysql_fetch_assoc($sql);

			$calc_amount = Order::calculate_total($order);

			$stripe_call = Stripe::charge_customer($stripe_customer['stripe_id'], $order_id, $calc_amount);

			if ($stripe_call['error'] == true)
			{
				$return_arr['form_check'] = "error";
				$alerts[] = $stripe_call['alert'];
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

				DashCash::add_funds($order['order_user'], (-1 * $order['discount_amount']), "Redeemed DashCash for order.");
			}
		}

		$alerts[] = $stripe_call['alert'];
		$return_arr['alert'] = $alerts[0];
		echo json_encode($return_arr);
	}

	public static function add_dashcash($values)
	{
		DashCash::add_funds($_POST['user_id'], $_POST['amount'], "Funds added by an administrator.");
		$alerts[] = '$' . $_POST['amount'] .' added to this users account';
		$return_arr['alert'] = $alerts[0];
		echo json_encode($return_arr);
	}

	public function send_user_text($values)
    {
        Twilio::send_text($_POST['phone'], $_POST['message']);
        $alerts[] = 'Your message has been dispatched to the user.';
        $return_arr['alert'] = $alerts[0];
        echo json_encode($return_arr);
    }
}

?>