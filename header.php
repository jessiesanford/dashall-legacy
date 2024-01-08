<div id="sidebar">
	<div class="sidebar_heading">DASHALL</div>
		<?php

			if($_SESSION['signed_in'])
			{
				$query_getUser = mysql_query("SELECT user_firstName, user_lastName, user_group FROM users WHERE user_id = " . mysql_real_escape_string($_SESSION['user_id']) . " LIMIT 1");
				$user = mysql_fetch_assoc($query_getUser);
				echo '
					<div class="welcome push_bottom">
						<div class="greeting">Hello,</div><div class="username">' . htmlentities($user['user_firstName']) . '</div>
					</div>
				';
				echo '
					<ul class="sidebar_menu">
						<li><a href="./account"><i class="fa fa-user"></i>&nbsp; Account</a></li>
						<li><a href="./restaurants"><i class="fa fa-cutlery"></i>&nbsp; Restaurants</a></li>
				';

				if ($user['user_group'] > 1) 
				{
					echo'
						<li><a href="./driver"><i class="fa fa-shopping-cart"></i>&nbsp; Orders</a></li>
						<li><a href="./schedule"><i class="fa fa-clock-o"></i>&nbsp; Schedule</a></li>
					';
				}

				if ($user['user_group'] > 2) 
				{
					echo '
						<li><a href="./manage"><i class="fa fa-flag"></i>&nbsp; Manage</a></li>
						<li><a href="./admin"><i class="fa fa-cogs"></i>&nbsp; Admin</a></li>  
					';   
				}
					
				echo'
					<li><a class="item" href="#" id="user_logout"><i class="fa fa-sign-out"></i>&nbsp; Sign Out</a></li>
					</div>
				';
			}
			else
			{
				echo '
					<div class="welcome push_bottom">
						<div class="greeting">Welcome guest!</div>
					</div>
					<a class="button block wid_100 push_bottom" href="./user/login"><i class="fa fa-sign-in"></i>&nbsp; Login</a>
					<a class="button block wid_100" href="./user/register"><i class="fa fa-user"></i>&nbsp; Create Account</a>
				';
			}
		?>
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

<div id="page">

<?php
	if($_SESSION['signed_in'] )
	{
		echo '
		<div id="top_notice">
			<div class="wrap align_center">

				Get $2 in DashCash when you refer a friend that makes an order! &nbsp;
				<span 
					class="fb-share-button" 
					data-href="http://dashall.ca/user/refer/'. $_SESSION['user_id'] .'" 
					data-layout="button" data-size="small" data-mobile-iframe="true">
					<a class="fb-xfbml-parse-ignore" 
						target="_blank" 
						href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fdashall.ca%2Fuser%2Frefer%2F'. $_SESSION['user_id'] .'&amp;src=sdkpreparse">
						Share
					</a>
				</span>
			</div>
		</div>
		';
	}
?>

<div id="bridge">
	<div class="wrap">
		<div class="row no_border">
			<div class="cell">
				<?php URL_ROOT ?> 
				<a href="<?php echo URL_ROOT ?>" id="bridge_logo"><img src="images/logo.png" alt="DashAll" /></a>
			</div>
			<div class="cell cell_grow align_right">

		<div id="user_panel">
		<?php
			if($_SESSION['signed_in'])
			{
				$query_getUser = mysql_query("SELECT user_firstName, user_lastName, user_group FROM users WHERE user_id = " . mysql_real_escape_string($_SESSION['user_id']) . " LIMIT 1");
				$user = mysql_fetch_assoc($query_getUser);

				echo '
					<div class="welcome resp_hide">
						<div class="greeting">Hello,</div><div class="username">' . htmlentities($user['user_firstName']) . '</div>
					</div>
				';
				echo '
					<div class="user_menu">
						<a class="resp_hide" href="./account"><span><fa class="fa fa-2x fa-user"></i></span></a>
						<a class="resp_hide" href="./restaurants"><span><fa class="fa fa-2x fa-cutlery"></i></span></a>
					';
					if ($user['user_group'] > 1) 
					{
						echo'
							<a class="resp_hide" href="./driver"><fa class="fa fa-2x fa-shopping-cart"></i></a>
							<a class="resp_hide" href="./schedule"><fa class="fa fa-2x fa-clock-o"></i></a>
						';
					}

					if ($user['user_group'] > 2) 
					{
						echo'
							<a class="resp_hide" href="./manage.php"><i class="fa fa-2x fa-flag"></i></a>
                        	<a class="resp_hide" href="./admin"><i class="fa fa-2x fa-cogs"></i></a>
                        ';  
					}
					
					echo '
							<a id="toggle_sidebar" href="#"><i class="fa fa-2x fa-bars"></i></a>
						</div>
					';
			}
			else
			{
				echo '
					<div class="resp_hide">
						<a href="./user/login" style="margin-right: 5px;">Login</a> or &nbsp;<a class="button" href="./user/register">Create Account</a>
					</div>
					<a id="toggle_sidebar" class="resp_show" href="#"><i class="fa fa-2x fa-bars"></i></a>
				';
			}
		?>
		</div>
			</div>
		</div>
	</div>
</div>

<div class="notice <?php if ($takingOrders == true) { echo "online"; } ?>">
	<?php 
		if ($takingOrders == false) {
			echo $notice;
		}
		else {
			echo $store_hours->render() . $notice;
		}
	?>
</div>

