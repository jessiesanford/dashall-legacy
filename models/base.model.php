<?php

class base_model
{
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function get_driver_by_user_id($user_id) {
        $this->db->query("
            SELECT * FROM drivers INNER JOIN users ON drivers.user = users.user_id WHERE drivers.user = ". $user_id ."
        ");
        return $this->db->single();
    }

    public function get_shift_by_start_time($time)
    {
        $this->db->query("
            SELECT shift_id, req_unshift, user_id, first_name, last_name FROM driver_shifts 
            INNER JOIN users ON users.user_id = driver_shifts.driver_id
            WHERE driver_shifts.start_datetime = '" . $time . "'
        ");
        return $this->db->single();
    }

    public function check_self_shift($time, $user_id)
    {
        $this->db->query("
            SELECT shift_id, driver_id, req_unshift, user_id, first_name, last_name FROM driver_shifts 
            INNER JOIN users ON users.user_id = driver_shifts.driver_id
            WHERE driver_shifts.start_datetime = '" . $time . "' AND driver_id = ". $user_id ."
        ");
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function get_drivers_on_shift($time)
    {
        $this->db->query("
            SELECT shift_id, req_unshift, user_id, first_name, last_name FROM driver_shifts 
            INNER JOIN users ON users.user_id = driver_shifts.driver_id
            WHERE driver_shifts.start_datetime = '" . $time . "'
        ");
        $this->db->execute();
        return $this->db->resultset();
    }

    public function count_shift_drivers($time)
    {
        $this->db->query("
            SELECT shift_id, req_unshift, user_id, first_name, last_name FROM driver_shifts 
            INNER JOIN users ON users.user_id = driver_shifts.driver_id
            WHERE driver_shifts.start_datetime = '" . $time . "'
        ");
        $this->db->execute();
        return $this->db->rowCount();
    }



    public function get_shifts_by_user_id($user_id) {
        $this->db->query("SELECT * FROM driver_shifts WHERE driver_id = ". $user_id ." ORDER BY start_datetime");
        $this->db->execute();
        return $this->db->resultset();
    }

    public function take_shift($user_id, $start, $end) {
        $this->db->query("
            INSERT INTO driver_shifts(driver_id, start_datetime, end_datetime) 
            VALUES(
            ". $user_id .", 
            '". $start . "',
            '". $end . "'
            )
        ");
        $this->db->execute();
    }

    public function request_unshift($shift_id) {
        $this->db->query("
			UPDATE driver_shifts
			SET req_unshift = 1
			WHERE shift_id = ". $shift_id ."
		");
        $this->db->execute();
    }

    public function count_unassigned_orders() {
        $this->db->query("
            SELECT count(*) AS count
            FROM orders
            INNER JOIN order_status ON orders.status = order_status.order_status_id
            LEFT JOIN order_costs ON orders.order_id = order_costs.order_id
            WHERE orders.driver_id is NULL AND (order_status.order_status_id_num >= 0 AND order_status.order_status_id_num < 5) 
        ");
        $this->db->execute();
        return $this->db->single();
    }

    public function get_assigned_orders($driver_id) {
        $this->db->query("
            SELECT * 
            FROM orders
            INNER JOIN order_status ON orders.status = order_status.order_status_id
            LEFT JOIN order_costs ON orders.order_id = order_costs.order_id
            LEFT JOIN order_addresses ON order_addresses.order_id = orders.order_id
            INNER JOIN users ON users.user_id = orders.user_id
            WHERE (
                orders.driver_id = ". $driver_id ."
                AND (orders.status !=  'COM' AND orders.status != 'CANC' AND orders.status != 'ARCH' AND orders.status != 'DEN')
            )
            ORDER BY orders.assigned_time
            LIMIT 12
        ");
        $this->db->execute();
        return $this->db->resultset();
    }

    public function count_assigned_orders($driver_id) {
        $this->db->query("
            SELECT COUNT(*) as count
            FROM orders
            INNER JOIN order_status ON orders.status = order_status.order_status_id
            LEFT JOIN order_costs ON orders.order_id = order_costs.order_id
            LEFT JOIN order_addresses ON order_addresses.order_id = orders.order_id
            INNER JOIN users ON users.user_id = orders.user_id
            WHERE (
                orders.driver_id = ". $driver_id ."
                AND (orders.status !=  'COM' AND orders.status != 'CANC' AND orders.status != 'ARCH' AND orders.status != 'DEN')
            )
        ");
        $this->db->execute();
        return $this->db->single();
    }

    public function get_unassigned_orders() {
        $this->db->query("
            SELECT * 
            FROM orders
            INNER JOIN order_status ON orders.status = order_status.order_status_id
            LEFT JOIN order_costs ON orders.order_id = order_costs.order_id
            INNER JOIN users ON users.user_id = orders.user_id
            LEFT JOIN order_addresses ON order_addresses.order_id = orders.order_id
            WHERE orders.driver_id is NULL AND (order_status.order_status_id_num >= 0 AND order_status.order_status_id_num < 5) 
        ");
        $this->db->execute();
        return $this->db->resultset();
    }

    public function self_assign_order($order_id, $driver_id) {
        $this->db->query("
            UPDATE orders
            SET driver_id = " . $driver_id . ", status = 'APP_S2', assigned_time = '". TIMESTAMP ."'
            WHERE order_id = " . $order_id . "
        ");
        return $this->db->execute();
    }

    public function update_driver_settings($driver_id, $notify_orders) {
        $this->db->query("
			UPDATE drivers  
			SET notify_orders = " . $notify_orders . "
			WHERE user = " . $driver_id . "
		");
        return $this->db->execute();
    }

}

?>