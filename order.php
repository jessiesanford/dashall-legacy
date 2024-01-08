<?php 
	if ($_SESSION['signed_in'] == true) {
		header("Location: /order");
		die();
	}
	require 'connect.php';
	require 'verify.php';
	$pageTitle = "Food delivery from any restaurant in St. John's, Newfoundland";
	$pageDesc = "Food delivery from any of your favorite restaurants in St. John’s, Newfoundland. Order online, and get it straight to your doorstep.";
?>

<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<?php require 'include.php'; ?>
<script type="text/javascript" src="js/order.js"></script>
<link rel="stylesheet" href="css/order_flow.css" />

</head>
<body>

<?php 
	require "header.php";
	include("templates/index_user.php");
 	include "footer.php" 
 ?>

</div>
<!-- end overview -->

<script type="text/javascript">
       var _mfq = _mfq || [];
       (function () {
       var mf = document.createElement("script"); mf.type = "text/javascript"; mf.async = true;
       mf.src = "//cdn.mouseflow.com/projects/be2ea7ec-b374-4f79-8d05-2b56c9509e60.js";
       document.getElementsByTagName("head")[0].appendChild(mf);
     })();
</script>
</body>
</html>