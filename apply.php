<?php 
	require 'connect.php';
	$pageTitle = "Apply to Drive";
	$pageDesc = 'Dash for us, and make some cash.';
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<?php require 'include.php'; ?>
<?php require 'header.php'; ?>

<body>

<div id="container">
	<div class="wrap">

		<div class="section">
			<div class="page_heading">
				<h1 class="page_title">Driver Application</h1>
			</div>

			<p>
				Got wheels? Why not Dash for DashAll!<br />We are currently accepting applications for delivery drivers.
			</p>

			<br />

			<h2>We aren't a huge fan of resumes.</h2>

			<p>
 				Don't worry about dusting off that old resume PDF, we don't want it. 
 				Instead we just want you to answer a couple questions and honestly rate yourself on a few lifestyle metrics. 
 				We believe that this tells us more than what year you graduated high school or what skills you copied from that resume building website - errr, acquired... from your past employment experiences.
			</p>

			<br />

	    	<form method="POST" action="" id="driver_app">
				<fieldset>
					<?php 
						if($_SESSION['signed_in'])
						{
					?>

				<h3>Introduce yourself!</h3>
				<textarea name="driver_desc" id="driver_desc" class="textarea block width_full" placeholder="Tell us about yourself..."></textarea>

				<h3 class="push_top_20">How did you hear about us?</h3>
				<textarea name="driver_refer" class="textarea block width_full" placeholder="Enter response..."></textarea>

				<h3 class="push_top_20">Explain in one sentence, what DashAll does.</h3>
				<textarea name="dashall_summary" class="textarea block width_full" placeholder="Enter response..."></textarea>

				<h3 class="push_top_20">What are your expectations from DashAll?</h3>
				<textarea name="expectations" class="textarea block width_full" placeholder="Enter response..."></textarea>

				<h3 class="push_top_20">What times would you like to work for DashAll?</h3>
				<textarea name="availability" class="textarea block width_full" placeholder="Enter response..."></textarea>
				<p><em>Note: Check our FAQ for our hours of operation to give yourself an idea of available times!</em></p>

				<h3 class="push_top_20">What kind of car do you have? (Year/Make/Model)</h3>
				<input type="text" class="textbox block" name="car" placeholder="2007 Honda Civic, etc." />

				<h3 class="push_top_20">What kind of coverage do you have on your car?</h3>
				<input type="text" class="textbox block" name="coverage" placeholder="Collision, collateral, etc." />

				<h3 class="push_top_20">What's your current occupation?</h3>
				<select name="occupation" class="block">
					<option>Student</option>
					<option>Employed (Full-Time)</option> 
					<option>Employed (Part-Time)</option>
					<option>Unemployed</option>
				</select>

				<h3 class="push_top_20">What phone do you have? (Does it have a data plan?)</h3>
				<input type="text" class="textbox block" id="phone" name="phone" placeholder="Enter phone model..." />

				<h3 class="push_top_40">
					<strong>Step 4. Agree to the disclaimer and submit your application!</strong>
				</h3>
				<label class="block" for="disclaimer_cb">
					<input name="disclaimer_cb" id="disclaimer_cb" type="checkbox" /> I agree that I am a real person and am qualified for the position of being a DashAll delivery driver.
				</label>
				<label class="block" for="abstract_cb">
					<input name="abstract_cb" id="abstract_cb" type="checkbox" /> I will provide a driver abstract if requested by DashAll.
				</label>
				<button class="push_top_20" type="submit" id="driver_app_submit">Submit Application</button>
				<?php 
					} else {
						echo 'Please <a href="user?action=login">Login</a> or <a href="user?action=register">Register</a> to submit an application. It only takes a moment of your time!';
					}
				?>
			</fieldset>
			</form>

		</div>

	</div>
</div>

<?php include "footer.php" ?>

</div>

</body>
</html>