<?php 
	require 'connect.php';
	$pageTitle = 'My Account';
	$pageDesc = 'Details and settings pertaining to your DashAll account.';
?>

<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<?php require 'include.php'; ?>
<script type="text/javascript" src="./js/account.js?v=<?php echo FILE_VERSION; ?>"></script>
<body>
<?php require "header.php"; ?>

<div id="container">
	<div class="wrap">
		<div class="section">
			<div class="page_heading">
				<h1 class="page_title">Account Settings</h1>
				<div class="page_desc">Details and settings pertaining to your DashAll account.</div>
			</div>

<?php 

if ($_SESSION['signed_in'] == false)
{
	//the user is not logged in
	echo 'You need to be logged in to access to page.';
}

else 
{
	$query = mysql_query("
		SELECT *, users.user_id AS uid FROM users 
		LEFT JOIN user_addresses ON user_addresses.user_id = users.user_id
		WHERE users.user_id = " . $_SESSION['user_id'] . "
	"); 
	$row = mysql_fetch_assoc($query);

	echo '
	<ul class="tab_menu push_bottom">
		<li class="selected" data="tab_personal"><i class="fa fa-user"></i>&nbsp; Personal</li>
		<li data="tab_email"><i class="fa fa-envelope"></i>&nbsp; Email</li>
		<li data="tab_phone"><i class="fa fa-phone"></i>&nbsp; Phone</li>
		<li data="tab_password"><i class="fa fa-key"></i>&nbsp; Password</li>
		<li data="tab_address"><i class="fa fa-home"></i>&nbsp; Address</li>
		<li data="tab_payment"><i class="fa fa-dollar"></i>&nbsp; Payment</li>
	';
	echo '
	</ul>

	<hr />

	<div class="tab_panel tab_current" id="tab_personal">
		<form id="update_settings" method="POST">
			<label class="block push_bottom">
				<div>First Name</div>
				<input type="text" class="textbox" value="' . $row['user_firstName'] . '" name="user_firstName" />
			</label>
			<label class="block push_bottom">
				<div>Last Name</div>
				<input type="text" class="textbox" value="' . $row['user_lastName'] . '" name="user_lastName" />
			</label>
			<br />
			<button type="submit">Update Info</button>
		</form>
	</div>

	<div class="tab_panel" id="tab_email">
		<div class="push_bottom">Current Email Address: <strong>'. $row['user_email'] . '</strong></div>
		<form id="change_email" method="POST">
			<label class="block push_bottom">
				<div>Password</div>
				<input type="password" class="textbox" name="user_email_pass" />
			</label>
			<label class="block push_bottom">
				<div>New Email</div>
				<input type="text" class="textbox" name="user_email" />
			</label>
			<label class="block push_bottom">
				<div>Confirm New Email</div>
				<input type="text" class="textbox" name="user_email_confirm" />
			</label>
			<br />
			<button type="submit">Change Email</button>
		</form>
	</div>

	<div class="tab_panel" id="tab_phone">
		<div class="push_bottom">Current Phone Number: <strong>'. $row['user_phone'] . '</strong></div>
		<form id="change_phone_number" method="POST">
			<label class="block push_bottom">
				<div>New Phone Number</div>
				<input type="text" class="textbox" name="user_phone" maxlength="10" />
			</label>
			<p><strong>If you change your phone number you will have to reverify your account!</strong></p>
			<button type="submit">Change Phone Number</button>
		</form>
	</div>

	<div class="tab_panel" id="tab_password">
		<form id="change_password" method="POST">
			<label class="block push_bottom">
				<div>Current Password</div>
				<input type="password" class="textbox" name="user_pass" />
			</label>
			<label class="block push_bottom">
				<div>New Password</div>
				<input type="password" class="textbox" name="user_new_pass" />
			</label>
			<label class="block push_bottom">
				<div>Confirm New Password</div>
				<input type="password" class="textbox" name="user_new_pass_confirm" />
			</label>
			<br />
			<button type="submit">Change Password</button>
		</form>
	</div>

	<div class="tab_panel" id="tab_address">
		<form id="change_address" method="POST">
			<label class="block push_bottom">
				<div>Street</div>
				<input type="textbox" class="textbox" name="address_street" value="'. $row['street'] .'" />
			</label>
			<label class="block push_bottom">
				<div>City</div>
				<input type="textbox" class="textbox" name="address_city" value="St. Johns" disabled />
			</label>
			<label class="block push_bottom">
				<div>Province</div>
				<input type="textbox" class="textbox" name="address_city" value="NL" disabled />
			</label>
			<label class="block push_bottom">
				<div>Postal Code</div>
				<input type="textbox" class="textbox" name="address_postal" value="'. $row['postal'] .'" />
			</label>
			<br />
			<button type="submit">Change Address</button>
		</form>
	</div>

	<div class="tab_panel" id="tab_payment">
		<h3>Payment Methods</h3>
	';
		$sql = mysql_query("SELECT * FROM stripe_customers WHERE user_id = ". mysql_real_escape_string($_SESSION['user_id']) ."");
		$stripe_customer = mysql_fetch_assoc($sql); 

		if (!$stripe_customer)
		{
			echo '<div class="push_bottom">You do not have any payment methods set up. You can add one if you would like:</div>';
			echo '
				<form action="" method="POST" id="account_payment_setup">
					<div class=" row no_border">
		                <label class="block cell">
		                	<div><i class="fa fa-credit-card"></i>&nbsp; Card Number</div>
		                	<input type="text" size="16" maxlength="16" autocomplete="off" class="textbox align_center card-number" style="min-width: 200px;" placeholder="**** **** **** ****" />
		                </label>
	                </div>
	                <div class="push_bottom_40 row no_border">
		                <label class="cell">
		                	<div>MM</div>
		                	<input type="text" size="2" maxlength="2" class="textbox card-expiry-month width_auto align_center" placeholder="01" />
		                </label>
		                <label class="cell">
		                	<div>YY</div>
		                	<input type="text" size="2" maxlength="2" class="textbox card-expiry-year width_auto align_center" placeholder="18" />
		                </label>
		                <label class="cell">
		                	<div><i class="fa fa-lock"></i>&nbsp; CVC</div>
		                	<input type="text" size="4" maxlength="3" autocomplete="off" class="textbox card-cvc align_center width_auto" placeholder="***" />
		                </label>
	                </div>
		            <button type="submit" class="button button_lrg resp_expand">Authorize Card</button>
		        </form>
			';
		}
		else 
		{
			require("stripe.class.php");
			$cc_data = Stripe::get_customer($stripe_customer['stripe_id']);
			echo '    
				<form action="" method="POST" id="remove_payment_method">
					<div class="row row_collapse">
						<div class="cell">
							<i class="fa fa-cc-'. strtolower($cc_data->brand) .'"></i>&nbsp; '. $cc_data->brand .' (**** **** **** ' . $cc_data->last4 .')
						</div>
						<div class="cell">Expires '. $cc_data->exp_month .' / '. $cc_data->exp_year .'</div>
				        <div class="cell">
					        <button class="button" type="submit">Remove This Card</button>
				        </div>
					</div>
		    	</form>
			';
		}

	echo '<hr />
			<br />
			<h3>DashCash (Current Balance: $'. $row['dashcash_balance'] .')</h3>
	';
	
	$sql = mysql_query("SELECT * FROM dashcash_trans WHERE user_id = ". mysql_real_escape_string($_SESSION['user_id']) ."");
	$dashcash_trans = mysql_fetch_assoc($sql);

	if ($dashcash_trans)
	{
		$sql = mysql_query("
			SELECT * FROM dashcash_trans
			WHERE dashcash_trans.user_id = ". mysql_real_escape_string($_SESSION['user_id']) ."
		");

		echo '

			<div class="thead row">
				<div class="cell wid_25">
					Trans ID
				</div>
				<div class="cell wid_25">
					Amount
				</div>
				<div class="cell wid_25">
					Description
				</div>
				<div class="cell cell_grow align_right">
					Date
				</div>

			</div>

		';

		while($trans = mysql_fetch_assoc($sql))
		{
			echo '
				<div class="row">
					<div class="cell wid_25">
						'. $trans['trans_id'] .'
					</div>
					<div class="cell wid_25">
						$'. $trans['amount'] .'
					</div>
					<div class="cell wid_25">
						'. $trans['trans_desc'] .'
					</div>
					<div class="cell cell_grow align_right">
						'. date("M d Y @ g:i:s a ",strtotime($trans['time'])) .'
					</div>
				</div>
			';
		}
	}
	else 
	{
		echo '<div>You have not referred anyone yet!</div>';
	}

	echo '
			<br />
			<h3>Referrals</h3>
			<p>Each time you refer a user to DashAll they are listed here, each referral that makes an order earns you $2 in DashCash to use towards future orders you make!</p>
			<p>Use this link to refer friends!</p>
			<input type="textbox" class="textbox block wid_100 push_bottom" style="font-weight: 500; border-color: #000" value="http://dashall.ca/user/refer/'. $row['uid'] .'" readonly />
	';

	$sql = mysql_query("SELECT * FROM referrals WHERE ref_by = ". mysql_real_escape_string($_SESSION['user_id']) ."");
	$referrals = mysql_fetch_assoc($sql);

	if ($referrals)
	{
		$sql = mysql_query("
			SELECT referrals.*, users.user_firstName, users.user_lastName FROM referrals 
			LEFT JOIN users ON users.user_id = referrals.user_id
			WHERE ref_by = ". mysql_real_escape_string($_SESSION['user_id']) ."
		");

		echo '

			<div class="thead row">
				<div class="cell wid_25">
					Username
				</div>
				<div class="cell wid_25">
					Payout
				</div>
				<div class="cell cell_grow align_right">
					Date
				</div>

			</div>

		';

		while($referral = mysql_fetch_assoc($sql))
		{
			echo '
				<div class="row">
					<div class="cell wid_25">
						'. $referral['user_firstName'] .' '. $referral['user_lastName'] .'
					</div>
					<div class="cell wid_25">
						$2.00
					</div>
					<div class="cell cell_grow align_right">
						'. $referral['time'] .'
					</div>
				</div>
			';
		}
	}
	else 
	{
		echo '';
	}

	echo '
		</div>
	';

}

?>

		</div>
	</div>
</div>

<?php include 'footer.php' ?>