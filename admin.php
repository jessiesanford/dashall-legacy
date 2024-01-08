<?php 
	require 'connect.php';
	require 'verify.php';
	$pageTitle = "DashAll Administration";
	$pageDesc = "DashAll Administration";

	if (!$_SESSION['signed_in']) 
	{
		header('Location: user/login');
		die();
	}
	else if ($user['user_group'] < 3)
	{
		echo 'You do not have permission to access this page';
	}
	else 
	{
?>

<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<?php require 'include.php'; ?>
<link rel="stylesheet" type="text/css" href="css/admin.css" />
</head>
<body>

<?php require "templates/admin_header.php"; ?>

<?php

	if (!isset($_GET['module']))
	{
		include("templates/admin_settings.php");
	}
	else {
		include("templates/admin_". $_GET['module'] .".php");
	}
?>

</div>

</body>
</html>

<?php 
}
?>