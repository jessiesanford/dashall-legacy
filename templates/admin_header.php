<div id="admin_sidebar">
	<div class="sidebar_heading">DA<span class="resp_hide">SHALL</span></div>
	<p class="resp_hide">Administration</p>
		<ul class="sidebar_menu">
			<li><a href="./admin?module=settings"><i class="fa fa-cog"></i>&nbsp; <span class="resp_hide">Settings</span></a></li>  
			<li><a href="./admin?module=orders"><i class="fa fa-shopping-cart"></i>&nbsp; <span class="resp_hide">Orders</span></a></li>
			<li><a href="./admin?module=transactions"><i class="fa fa-credit-card"></i>&nbsp; <span class="resp_hide">Transactions</span></a></li>
			<li><a href="./admin?module=payroll"><i class="fa fa-dollar"></i>&nbsp; <span class="resp_hide">Payroll</span></a></li>
			<li><a href="./admin?module=schedule"><i class="fa fa-clock-o"></i>&nbsp; <span class="resp_hide">Schedule</span></a></li>
			<li><a href="./admin?module=drivers"><i class="fa fa-car"></i>&nbsp; <span class="resp_hide">Drivers</span></a></li>
			<li><a href="./admin?module=users"><i class="fa fa-users"></i>&nbsp; <span class="resp_hide">Users</span></a></li>
		</ul>
</div>

<div class="popup">
	<span></span>
</div>

<div id="mask"></div>
<div id="model"></div>

<div id="loading">
	<img src="images/loading.gif" alt="Loading..." />
</div>

<div id="admin_sidebar_push">

<div id="user_panel">
<?php
	$query_getUser = mysql_query("SELECT user_firstName, user_lastName, user_group FROM users WHERE user_id = " . mysql_real_escape_string($_SESSION['user_id']) . " LIMIT 1");
	$user = mysql_fetch_assoc($query_getUser);
?>
	<div class="welcome">
		<div class="username"><?php echo htmlentities($user['user_firstName'] .' '. $user['user_lastName']); ?></div>
		<a class="item" href="#" id="user_logout"><i class="fa fa-sign-out"></i>&nbsp; Sign Out</a>
		<a href="./" target="_blank"><i class="fa fa-arrow-left"></i>&nbsp; DashAll</a>
	</div>
</div>

<div id="admin_content">