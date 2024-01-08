<?php 
	require 'connect.php';
	require 'verify.php';
	$pageTitle = "Food delivery from any restaurant in St. John's, Newfoundland";
	$pageDesc = "Order food from your favorite restaurants in St. John's, Newfoundland and get it delivered straight to your doorstep.";
?>

<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<?php require 'include.php'; ?>

</head>
<body>

<?php require "header.php"; ?>

<div class="container">
	<div class="wrap">
		<div class="section push_top_40">
			<?php
			if ($user['user_group'] < 2)
			{
				echo "You don't have permission to access this page.";
			} 
			else {

echo '
	<div class="row thead">
		<div class="cell wid_30">Referrer</div>
		<div class="cell wid_30">User Referred</div>
		<div class="cell cell_right wid_30">Date Referred</div>
	</div>
';

$sql = mysql_query("SELECT referrals.user_id AS ref_user, referrals.time, users.* FROM referrals INNER JOIN users ON users.user_id = referrals.ref_by ORDER BY referrals.time DESC");
while($refer = mysql_fetch_assoc($sql))
{
	echo ' 
		<div class="row">
			<div class="cell wid_30"><a href="customer?id='. $refer['user_id'] .'">'. $refer['user_firstName'] .'</a></div>
			<div class="cell wid_30"><a href="customer?id='. $refer['ref_user'] .'">'. $refer['ref_user'] .'</a></div>
			<div class="cell cell_right wid_30">'. date('M d y g:i:s a', strtotime($refer['time'])) .'</div>
		</div>
	';
}

}
			?>
		</div>
	</div>
</div>




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