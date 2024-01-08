<?php 
	require 'connect.php';
	$pageTitle = "About Us";
	$pageDesc = 'A little about what DashAll is, the people behind it, and what we do.';
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<?php require 'include.php'; ?>
<body>
<?php require "header.php"; ?>

<div class="container">
	<div class="wrap">

		<div class="section align_center">
			<div class="page_heading align_left">
				<h1 class="page_title">About Us</h1>
				<h2 class="page_desc">A little about who we are and what we do.</h2>
			</div>

			<h3>What is DashAll?</h3>
			<p>
				DashAll is an urban delivery logistics platform (a mouthful, we know) founded in St. John's, Newfoundland that connects delivery drivers to customers. 
			</p>
			<hr />
			<h3>Wait, what does that mean exactly?</h3>
			<p>
				Well, with DashAll you now have the option of getting anything delivered, from anywhere (within reason of course).<br />
				This allows anyone to place an order for delivery from any restaurant or business in St. John's.
			</p>
			<hr />
			<h3>Who created this thing?</h3>
			<p>
				It was founded by two Memorial University students who were afflicted by things: <br />
				1. Disappointment by the lack of variety for deliverable meals.<br />
				2. An itch to create something that bounded their passion for technology and logistics alike.
			</p>
			<hr />
			<h3>The Team</h3>

			<div class="row row_collapse">
				<div class="cell wid_50 align_center">
					<h2>Jan Mertlík</h2>
					<h3>CEO / Co-Founder</h3>
					<div class="about_picture">
						<img src="images/jan.gif" />
					</div>
					<br />
				</div>
				<div class="cell wid_50 align_center">
					<h2>Jessie Slobogian-Sanford</h2>
					<h3>CTO / Co-Founder</h3>
					<div class="about_picture">
						<img src="images/jessie.gif" />
					</div>
					<br />
				</div>
			</div>
		</div>

	</div>
</div>

<?php include "footer.php" ?>

</div>

</body>
</html>