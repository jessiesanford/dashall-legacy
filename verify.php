<?php
	if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true && $user['user_group'] < 1)
	{
		header("Location: user?action=verify");
		die();
	}

?>