<?php 
	// connect.php - sets up user connection and session
	require("db.php");
	require("global.php");

	session_start();

	if ($_SESSION['signed_in'] == true)
	{
		$sql = mysql_query("SELECT users.user_id, users.user_group, users.user_firstName, users.user_lastName FROM users WHERE user_id = ". $_SESSION['user_id'] ."");
		$user = mysql_fetch_assoc($sql);
	}

?>