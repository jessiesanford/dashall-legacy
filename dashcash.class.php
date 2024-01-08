<?php

class DashCash 
{


	public static function add_funds($user_id, $amount, $desc)
	{
		mysql_query("
			INSERT INTO dashcash_trans(user_id, amount, trans_desc, time)
			VALUES (
				". mysql_real_escape_string($user_id) .",
				". mysql_real_escape_string($amount) .",
				'". mysql_real_escape_string($desc) ."',
				'". TIMESTAMP ."'
			)
		");

		mysql_query("
			UPDATE users SET dashcash_balance = dashcash_balance + ". $amount ." WHERE user_id = ". $user_id ."
		");

		// $sql = mysql_query("SELECT user_phone FROM users WHERE user_id = ". $user_id ."");
		// $user = mysql_fetch_assoc($sql);
	}
}

?>