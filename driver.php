<?php 
	require_once('connect.php');
	$pageTitle = "Orders";
	$pageDesc = 'Local delivery from your favorite restaurants straight to your doorstep.';
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<?php require 'include.php'; ?>
	<script type="text/javascript" src="js/driver.js"></script>
</head>
<body>

<?php require "header.php"; ?>

<div id="container">

	<div class="wrap">

		<div class="section orders_section push_bottom_40">

		<div class="page_heading">
			<h1 class="page_title">Ongoing Orders</h1>
		</div>

		<?php 
			if ($user['user_group'] < 2)
			{
				echo "You don't have permission to access this page.";
			} 
			else {

				if ($management_mode['value'] == 1 || $management_mode['value'] == 2 || $management_mode['value'] == 3)
				{
					$sql = mysql_query("
						SELECT * FROM orders 
						INNER JOIN order_status ON order_status.order_status_id = orders.order_status
						WHERE orders.order_driver = ". $_SESSION['user_id'] ." AND order_status.order_status_id_num > 0 AND order_status.order_status_id_num < 5  
					");

					if (mysql_num_rows($sql) > 0)
					{
						include("templates/driver_active_mode.php");
					}
					else 
					{
						include("templates/driver_passive_mode.php");
					}
				}
				else 
				{
					include("templates/driver_active_mode.php");
				}
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