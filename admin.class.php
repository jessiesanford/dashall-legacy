<?php

require_once("twilio.class.php");

class Admin 
{

	public static function update_settings($values)
	{
		$sql = mysql_query("
			UPDATE settings
			SET value = '" . ($_POST['taking_orders']) ."'
			WHERE name = 'taking_orders'
			LIMIT 1
		");
		$sql = mysql_query("
			UPDATE settings
			SET value = '" . ($_POST['force_operation']) ."'
			WHERE name = 'force_operation'
			LIMIT 1
		");
		$sql = mysql_query("
			UPDATE settings
			SET value = '" . ($_POST['open_notice']) ."'
			WHERE name = 'open_notice'
			LIMIT 1
		");
		$sql = mysql_query("
			UPDATE settings
			SET value = '" . ($_POST['closed_notice']) ."'
			WHERE name = 'closed_notice'
			LIMIT 1
		");
		$sql = mysql_query("
			UPDATE settings
			SET value = '" . ($_POST['management_mode']) ."'
			WHERE name = 'management_mode'
			LIMIT 1
		");
		$sql = mysql_query("
			UPDATE settings
			SET value = '" . ($_POST['active_driver']) ."'
			WHERE name = 'active_driver'
			LIMIT 1
		");
		 
		$alerts[] = "Settings updated.";
		$return_arr['alert'] = $alerts[0];
		echo json_encode($return_arr);
	}



	

	public static function get_order_stats($from_date = '0000-00-00', $to_date = '9999-12-31', $limit = 100) {

		$orders = mysql_query("
			SELECT orders.*, order_costs.*, users.*, addresses.*, TIMEDIFF(orders.complete_time, orders.init_time) AS time_transpired
			FROM orders
			INNER JOIN order_costs ON order_costs.order_id = orders.order_id
			INNER JOIN addresses ON addresses.address_order = orders.order_id
			INNER JOIN users ON users.user_id = orders.order_user
            WHERE orders.init_time BETWEEN CAST('". mysql_escape_string($from_date) ."' AS DATE) AND CAST('". mysql_escape_string($to_date) ."' AS DATE) 
			ORDER by orders.init_time DESC
			LIMIT ". $limit ."
		");


		$sql = mysql_fetch_array(mysql_query("
			SELECT COUNT(*), SUM(counter) FROM (
				SELECT orders.order_user, COUNT(*) AS counter FROM orders
				WHERE orders.init_time BETWEEN CAST('". mysql_escape_string($from_date) ."' AS DATE) AND CAST('". mysql_escape_string($to_date) ."' AS DATE) AND orders.order_status = 'ARCH'
			    GROUP BY orders.order_user 
			    HAVING COUNT(*) > 1
			) AS T
		"));
		$repeat_customers = $sql[0];
		$repeat_customer_orders = $sql[1];


		$sql = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total FROM orders "));
		$total_orders = $sql[0];


		$sql = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total FROM orders WHERE order_status = 'COM' OR order_status = 'ARCH'"));
		$total_complete_orders = $sql[0];


		$sql = mysql_fetch_array(mysql_query("
			SELECT AVG(TIMESTAMPDIFF(MINUTE, orders.init_time, orders.complete_time))
			FROM orders 
			WHERE orders.init_time BETWEEN CAST('". mysql_escape_string($from_date) ."' AS DATE) AND CAST('". mysql_escape_string($to_date) ."' AS DATE) AND orders.order_status = 'ARCH'
			AND TIMESTAMPDIFF(MINUTE, orders.init_time, orders.complete_time) > 0 
			AND TIMESTAMPDIFF(MINUTE, orders.init_time, orders.complete_time) < 120
			"));

		$avg_order_time = number_format($sql[0], 1, '.', '');


		$sql = mysql_query("
			select hour(init_time) as hour, count(*) as count
			from orders
			WHERE orders.init_time BETWEEN CAST('". mysql_escape_string($from_date) ."' AS DATE) AND CAST('". mysql_escape_string($to_date) ."' AS DATE) AND orders.order_status = 'ARCH'
			group by hour(init_time)
		");

		$hot_hours_arr = array();
		while($hot_hours = mysql_fetch_assoc($sql)) 
		{
			$new_row = array();

			if ($hot_hours['hour'] == "0") 
			{
				$new_row['hour'] = "24";
			}
			else {
				$new_row['hour'] = $hot_hours['hour'];
			}

			$new_row['count'] = $hot_hours['count'];
			$hot_hours_arr[] = $new_row;
		}

		$order_stats = array(
			'total_orders' => $total_orders,
			'total_complete_orders' => $total_complete_orders,
			'avg_order_time' => $avg_order_time,
			'repeat_customer_count' => $repeat_customers,
			'repeat_customer_orders' => $repeat_customer_orders,
			'hot_hours' => $hot_hours_arr
		);

		$return_array = array(
			'orders' => $orders,
			'order_stats' => $order_stats
		 );

		return $return_array;
	}

	public static function get_transactions_stats($from_date = '0000-00-00', $to_date = '9999-12-31', $limit = 100) {

		$sql = mysql_query("
			SELECT order_costs.*, orders.order_id, orders.order_user, orders.promo, orders.init_time, users.user_id, users.user_firstName, users.user_lastName
			FROM order_costs
			INNER JOIN orders ON orders.order_id = order_costs.order_id
			INNER JOIN users ON users.user_id = orders.order_user
            WHERE orders.init_time BETWEEN CAST('". mysql_escape_string($from_date) ."' AS DATE) AND CAST('". mysql_escape_string($to_date) ."' AS DATE) AND orders.order_status = 'ARCH'
			ORDER by orders.init_time DESC
		");		
		
		$revenue = 0.00;
		$profit = 0.00;
		$order_count = 0;
		$promo_count = 0;
		$dashcash_count = 0;
		$repeat_customer_count = 0;


		$trans_arr = array();

		while($order = mysql_fetch_assoc($sql))
		{
			$order_count++;

			if ($order['stripe_margin'] == 0.00) {
				$stripe_margin = 1;
			} 
			else {
				$stripe_margin = $order['stripe_margin'];
			}

			$charged = number_format((($order['amount'] * $order['margin']) + $order['delivery_fee']) * $stripe_margin, 2);

			$order['stripe_cut'] = $stripe_cut;
			$stripe_cut = number_format(($charged + $order['tip']) * 0.029 + 0.3, 2);

			$order['revenue'] = number_format(($order['amount'] * 1.1 + $order['delivery_fee'] + $order['tip']) * 1.03, 2);
			$revenue += number_format(($order['amount'] * 1.1 + $order['delivery_fee'] + $order['tip']) * 1.03, 2);

			$order['profit'] = number_format($charged - (($order['delivery_fee'] * 0.9) + $order['amount'] + $stripe_cut), 2);
			$profit += number_format($charged - (($order['delivery_fee'] * 0.9) + $order['amount'] + $stripe_cut), 2);

			if ($order['promo'] != '')
			{
				if ($order['promo'] == 'FIRSTDASH') {
					$promo_count++;
				}
				else if ($order['promo'] == 'DASHCASH') {
					$dashcash_count++;
				}
		
				$profit -= $order['discount_amount'];
			} 
			else if ($order['delivery_fee'] != 7.00)
			{
				$profit = number_format($profit + ($order['delivery_fee'] - 6.30), 2);
			}

			$sql_return_customer = mysql_query("SELECT * FROM orders WHERE order_user = ". $order['order_user'] ."");

			if (mysql_num_rows($sql_return_customer) > 1)
			{
				$order['repeat_customer'] = true;
				$repeat_customer_count++;
			}


			$trans_arr[] = $order;
		}

		$sql = mysql_fetch_array(mysql_query("
		SELECT AVG(amount)
				FROM (
					SELECT amount
				FROM order_costs
				INNER JOIN orders ON orders.order_id = order_costs.order_id
				WHERE orders.init_time BETWEEN CAST('". mysql_escape_string($from_date) ."' AS DATE) AND CAST('". mysql_escape_string($to_date) ."' AS DATE) AND orders.order_status = 'ARCH'
				AND amount > 0 
			) items
		"));

		$avg_order_cost = number_format($sql[0], 2);

		$avg_order_profit = number_format($profit / $order_count, 2);

		$trans_stats = array(
			'order_count' => $order_count,
			'revenue' => $revenue,
			'profit' => $profit,
			'avg_order_cost' => $avg_order_cost,
			'avg_order_profit' => $avg_order_profit,
			'promo_count' => $promo_count, 
			'dashcash_count' => $dashcash_count, 
			'repeat_customer_count' => $repeat_customer_count 
			);


		$return_array = array(
			'trans' => $trans_arr,
			'trans_stats' => $trans_stats
		 );

		return $return_array;
	}

	public static function get_transactions_in_range($values)
	{
		$orders_model = self::get_transactions_stats($_POST['start_date'], $_POST['end_date'], 500);
		$orders_arr = array();

		$return_arr['order_stats'] = $orders_model['trans_stats'];
		$return_arr['orders'] = $orders_model['trans'];
		echo json_encode($return_arr);
	}

	public static function get_orders_in_range($values)
	{
		$orders_model = self::get_order_stats($_POST['start_date'], $_POST['end_date'], 500);
		$orders_arr = array();

		while($order = mysql_fetch_assoc($orders_model['orders'])) {
			$orders_arr[] = $order;
		}

		$return_arr['order_stats'] = $orders_model['order_stats'];
		$return_arr['orders'] = $orders_arr;
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

	public static function mass_text_drivers($values) {

		foreach ($_POST['driver'] as $key => $value) 
		{
			$sql = mysql_query("
				SELECT drivers.*, users.user_id, users.user_firstName, users.user_phone FROM drivers INNER JOIN users ON users.user_id = drivers.user
				WHERE users.user_id = ". $value ." 
			");

			$driver = mysql_fetch_assoc($sql);
			Twilio::send_text($driver['user_phone'], $_POST['message']);
		}

		$return_arr['alerts'] = "Message Sent.";
		echo json_encode($return_arr);
	}
}

?>