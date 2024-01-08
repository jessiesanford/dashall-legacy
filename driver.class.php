<?php 

require('order.class.php');

class Driver 
{
	// always letting the user know what's up on the fly with an alert array that gets returned via JSON/AJAX.
	public $return_arr = array();
	public $alerts = array();

	public static function self_assign($values)
	{
		$sql = mysql_query("
			SELECT order_driver FROM orders WHERE order_id = " . mysql_real_escape_string($_POST['order_id']) . "
		");
		$results = mysql_fetch_assoc($sql);
		$order_driver = $results['order_driver'];

		if ($order_driver != NULL)
		{
			$return_arr['form_check'] = 'error';
			$alerts[] = "This order has already been assigned.";
		}
		else 
		{
			$sql = mysql_query("
				UPDATE orders
				SET order_driver = " . $_SESSION['user_id'] . ", order_status = 'APP_S2'
				WHERE order_id = " . mysql_real_escape_string($_POST['order_id']) . "
			");

			if ($sql === false)
			{
				$return_arr['form_check'] = 'error';
				$alerts[] = mysql_error();
			}
			else 
			{
				$alerts[] = "You have been assigned to this order.";
			}
		}

		$return_arr['alert'] = $alerts[0];
		echo json_encode($return_arr);
	}



	public static function mark_complete($values)
	{
		echo '<div class="align_center"><p>Please verify that you are completing the delivery of this order.</p><br /><button class="markComplete_verify" data-order_id="' . $_POST['order_id'] . '">Verify Completion</button></a>';
	}

	public static function markComplete_verify($values)
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

		echo "Order status set to complete.";
	}






	// UPDATE ORDER COST
	public static function update_order_cost($values)
	{
		$sql = mysql_query("
			SELECT pay_capture FROM order_costs
			WHERE order_costs.order_id = ". mysql_real_escape_string($_POST['order_id']) ."
		");

		$order = mysql_fetch_assoc($sql);

		if ($order['pay_capture'] == 0)
		{ 
			$stripe_verified = false;

			mysql_query("
				UPDATE order_costs
				SET 
					amount = ".mysql_real_escape_string($_POST['order_cost'])."
				WHERE 
					order_id = ". mysql_real_escape_string($_POST['order_id']) ."
			");

			$sql = mysql_query("
				SELECT * FROM orders
				INNER JOIN users ON users.user_id = orders.order_user
	            INNER JOIN order_costs ON order_costs.order_id = orders.order_id
				WHERE orders.order_id = ". mysql_real_escape_string($_POST['order_id']) ."
			");

			$order = mysql_fetch_assoc($sql);

			$calc_amount = Order::calculate_total($order);

			mysql_query("
				UPDATE orders
				SET 
					order_status = 'APP_S3'
				WHERE 
					order_id = ". mysql_real_escape_string($_POST['order_id']) ."
			");

			$alerts[] = "Order cost set to $" . $_POST['order_cost'];
			$twilio_message = "Your order total is: $" . $calc_amount . "\n and is on it's way to you!";
			Twilio::send_text($order['user_phone'], $twilio_message);
		}
		else 
		{
			$alerts[] = "Cost already set!";
		}

		$return_arr['alert'] = $alerts[0];
		echo json_encode($return_arr);
	}

	public static function send_arrival_status($values)
	{
		$sql = mysql_query("
			SELECT 
				orders.order_id, orders.order_user, 
				users.user_phone
			FROM orders
			INNER JOIN users ON users.user_id = orders.order_user
			WHERE orders.order_id = ". mysql_real_escape_string($_POST['order_id']) ."
		");

		$order = mysql_fetch_assoc($sql);

		mysql_query("
			UPDATE orders
			SET 
				order_status = 'ARR'
			WHERE 
				order_id = ". mysql_real_escape_string($_POST['order_id']) ."
		");


		$twilio_message = "Your order has arrived!";
		Twilio::send_text($order['user_phone'], $twilio_message);

		$alerts[] = "Notifying the customer...";
		$return_arr['alert'] = $alerts[0];
		echo json_encode($return_arr);
	}


	public static function report_issue($values)
	{
		$sql = mysql_query("
			SELECT 
				orders.order_id, orders.order_user, 
				order_status.*,
				users.user_phone
			FROM orders
			INNER JOIN order_status ON order_status.order_status_id = orders.order_status
			INNER JOIN users ON users.user_id = orders.order_user
			WHERE orders.order_id = ". mysql_real_escape_string($_POST['order_id']) ."
		");
		$order = mysql_fetch_assoc($sql);

		if ($order['order_status_id_num'] > 0)
		{
			mysql_query("
				UPDATE orders
				SET 
					order_status = 'DEN'
				WHERE 
					order_id = ". mysql_real_escape_string($_POST['order_id']) ."
			");

			$twilio_message = "Your order could not be completed: " . $_POST['issue_text'];
			Twilio::send_text($order['user_phone'], $twilio_message);

			$alerts[] = "Order has been updated.";
		}
		else 
		{
			$alerts[] = "The order has already been marked.";
		}

		$return_arr['alert'] = $alerts[0];
		echo json_encode($return_arr);


	}

	public static function take_shift($values)
	{
		$sql = mysql_query("SELECT COUNT(*) as count FROM driver_shifts WHERE driver_shifts.start_datetime = '". $_POST['shift_start'] . "'");
		$count_shifted_drivers = mysql_fetch_assoc($sql)['count'];

		if($count_shifted_drivers > 0) 
		{
			$alerts[] = 'This shift has already been assigned.';
		}
		else 
		{
			mysql_query("
				INSERT INTO driver_shifts(driver_id, start_datetime, end_datetime) 
				VALUES(
				". $_SESSION['user_id'] .", 
				'". $_POST['shift_start'] . "',
				'". $_POST['shift_end'] . "'
				)
			");
			$alerts[] = 'Shift Assigned to you!';
		}

		$alerts[] = $shift['shift_id'];
		$return_arr['alert'] = $alerts[0];
		echo json_encode($return_arr);
	}

	public static function remove_shift($values)
	{
		//not safe
		mysql_query("
			DELETE FROM driver_shifts
			WHERE shift_id = ". $_POST['shift_id'] ."
		");

		$alerts[] = 'This shift has been unassigned.';
		$return_arr['alert'] = $alerts[0];
		echo json_encode($return_arr);
	}


	public static function request_unshift($values)
	{
		//not safe
		mysql_query("
			UPDATE driver_shifts
			SET req_unshift = 1
			WHERE shift_id = ". $_POST['shift_id'] ."
		");

		Twilio::notify_management('A shift removal has been requested!');

		$alerts[] = 'You have requested a shift change.';
		$return_arr['alert'] = $alerts[0];
		echo json_encode($return_arr);
	}

}

?>