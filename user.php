<?php
if (!isset($_GET['action']) || empty($_GET['action'])) 
{
?>

<html>

<?php
} 
else if (isset($_GET['action']) && !empty($_GET['action'])) {
	switch ($_GET['action']) 
	{
		// start login case
		case 'login':
		
		$pageTitle = 'Login - DashAll';
		$pageDesc = 'Local delivery from your favorite restaurants straight to your doorstep.';
		include 'connect.php';
		include 'include.php';

?>

<body>
<?php include('header.php');?>

<div id="container">
	<div class="wrap">
		<div class="section">
			<h2 class="page_heading">Sign in</h2>

			<?php if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)
			{
				echo 'You are already logged in, you can <a href="signout.php">log out</a> if you want.';
			}
			else
			{

					/*the form hasn't been posted yet, display it
					  note that the action="" will cause the form to post to the same page it is on */
					echo '
					<form method="post" id="login_form" class="sign_in align_center wid_50">
						<label class="block push_bottom">
							<input type="email" class="textbox textbox_lrg wid_100" name="user_email" autocorrect="off" autocapitalize="off" placeholder="Email Address">
						</label>
						<label class="block push_bottom">
							<input type="password" class="textbox textbox_lrg wid_100" name="user_pass" placeholder="Password">
						</label>
						<input type="submit" class="button button_lrg wid_100 push_top" value="Sign in">
						<hr />
						<p class="push_bottom"><a href="user?action=forgot_password"><i class="fa fa-key"></i>&nbsp; Forgot Password</a></p>
						<p>Don&rsquo;t have an account? <a href="user?action=register">Create One!</a></p>
					</form>
					';
			}

			 ?>

		</div>
	</div>
</div>

<?php include 'footer.php' ?>
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


			
<?php
break; 
// register 
case 'register':

include 'connect.php';

if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)
{
	header("Location: /");
	die();
}

$pageTitle = 'Register';
$pageDesc = 'Local delivery from your favorite restaurants straight to your doorstep.';


include 'include.php';
?>



<body>
<?php include('header.php');?>

<div id="container" 
	<div class="wrap">
		<div class="section">
			<h2 class="page_heading">Create Account</h2>
			<form method="post" id="register_form" class="register">
				<div class="row no_border">
					<label class="cell wid_100">
						<div>Email Address</div>
						<input type="email" class="textbox textbox_block" name="user_email" id="user_email" autocorrect="off" autocapitalize="off" placeholder="name@website.com">
					</label>
				</div>
				<div class="row row_collapse no_border">
					<label class="cell wid_100">
						<div>Phone Number</div>
						<input type="tel" class="textbox textbox_block" name="user_phone" id="user_phone" maxlength="12" placeholder="(709) 111-2222">
					</label>
				</div>
				<div class="row row_collapse no_border">
					<label class="cell wid_50">
						<div>Password</div>
						<input type="password" class="textbox textbox_block" name="user_pass" id="user_pass" placeholder="Minimum of 5 characters">
					</label>
					<label class="cell wid_50">
						<div>Password Again</div>
						<input type="password" class="textbox textbox_block" name="user_pass_check" id="user_pass_check" placeholder="Minimum of 5 characters">
					</label>
				</div>
				<div class="row row_collapse no_border">
					<label class="cell wid_50">
						<div>First Name</div>
						<input type="text" class="textbox textbox_block" name="user_firstName" id="user_firstName" autocapitalize="words" placeholder="First" />
					</label>
					<label class="cell wid_50">
						<div>Last Name</div>
						<input type="text" class="textbox textbox_block" name="user_lastName" id="user_lastName" autocapitalize="words" placeholder="Last" />
					</label>
				  </div>
				<label class="row no_border">
					<div class="cell wid_10">
						<input type="checkbox" name="user_survey" checked />
					</div>
					<div class="cell cell_grow">
						Would you like to be surveyed? (We will give you coupons for free delivery and bonus promotions!)
					</div>
				</label>
	            <label class="cell block">
	                <div>How did you hear about us?</div>
	                <select name="refer_source" class="block">
	                    <option selected disabled>Please choose one...</option>
	                    <option>Friends</option>
	                    <option>Posters</option>
	                    <option>Google</option>
	                    <option>Facebook (DashAll)</option>
	                    <option>Facebook (Poyo)</option>
	                    <option>Facebook (Smoke's Poutine)</option>
	                    <option>Facebook (Quesada)</option>
	                </select>
	            </label>

				<?php 
					if (isset($_GET['refer']))
					{
						echo '<input type="hidden" name="referral" id="referral" placeholder="Referral ID" value="'. $_GET['refer'] .'" />';
					}
					else 
					{
				?>
				<div class="row row_collapse no_border">
					<label class="cell wid_100">
						<div>Referral</div>
						<input type="textbox" class="textbox textbox_block" name="referral" id="referral" placeholder="Referral ID" value="<?php echo $_GET['refer'] ?>" />
					</label>
				</div>
				<?php 
					} 
				?>
				<div class="row row_collapse no_border">
					<label class="cell wid_100">
						<div>Competition Code</div>
						<input type="textbox" class="textbox textbox_block" name="competition_code" id="competition_code" value="" placeholder="If you have a competition code enter it here..." />
					</label>
				</div>
				<hr />
				<input type="submit" class="button button_lrg wid_100" value="Create Account" />
			</form>

		</div>
	</div>

<?php include 'footer.php'; ?>
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


<?php
break; 

// verify 
case 'verify':

include 'connect.php';

if(!isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == false)
{
	header("Location: index.php");
	die();
}

$pageTitle = 'Account Verification';
$pageDesc = 'Local delivery from your favorite restaurants straight to your doorstep.';


include 'include.php';
?>



<body>
<?php 
include('header.php');

$query_getUser = mysql_query("SELECT user_phone FROM users WHERE user_id = " . mysql_real_escape_string($_SESSION['user_id']) . " LIMIT 1");
$user = mysql_fetch_assoc($query_getUser);

?>

<div id="container">
	<div class="wrap">
		<div class="section">
			<h2 class="page_heading">Verify Account</h2>
			<form method="post" id="verif_form" class="align_center register">
				<p class="push_bottom">
					We sent a verification code to <strong><?php echo $user['user_phone'] ?></strong> please enter it in the box below to verify your account!
				</p>
				<label >
					<div>Verification Code</div>
					<input type="textbox" class="textbox textbox_block align_center" name="verif_code" id="verif_code" placeholder="5 Digits" maxlength="5">
				</label>
				<hr />
				<input type="submit" class="button button_lrg wid_100 push_bottom" value="Verify Account" />
				<p class="align_center">
					<a id="resend_verif_code" href="#"><i class="fa fa-refresh"></i>&nbsp; Resend Verification Code</a>
				</p>
				<hr>
				<p>
					If you need to verify your account using a different phone number you can change it in your <a href="account">account settings</a>
				</p>
			</form>

		</div>
	</div>
</div>

<?php include 'footer.php'; ?>
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





<?php
break; 
// forgot password 
case 'forgot_password':

$pageTitle = 'Forgot Password';
$pageDesc = 'Local delivery from your favorite restaurants straight to your doorstep.';

include 'connect.php';
include 'include.php';
include 'header.php'; 
?>

<div id="container">
	<div class="wrap">
		<div class="section">
			<h2 class="page_heading">Forgot Password</h2>

			<?php if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)
			{
				echo 'You are already logged in, you can <a href="account">Change Your Password</a> if you want.';
			}
			else
			{

					/*the form hasn't been posted yet, display it
					  note that the action="" will cause the form to post to the same page it is on */
					echo '
					<form method="post" id="forgot_password">
						<label class="block">
							<div>Email Address</div>
							<input type="email" class="textbox" name="user_email" autocorrect="off" autocapitalize="off" />
						</label>
						<input type="submit" class="button" value="Reset Password" />&nbsp;
					</form>
					';
			}

			 ?>

		</div>
	</div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>

<?php
			break;
			// default case	
			default:
			header('Location: /404.html');
		}
	}
?>			