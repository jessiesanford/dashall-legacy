<?php
	require 'connect.php';
	$pageTitle = "Mcdonald's";
	$pageDesc = "Local delivery from your favorite restaurants straight to your doorstep.";
?>

<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">

<?php require 'include.php'; ?>

<body>

<?php require "header.php"; ?>

<div id="container">
	<div class="wrap">

		<div class="section">
			<div class="page_heading">
				<div class="row row_collapse no_border">
					<div class="cell">
						<div class="rest_logo push_right"><img src="images/rest_mcdonalds.png" alt="Mcdonald's"/></div>
					</div>
					<div class="cell">
						<h1 class="no_padd">Mcdonald's</h1>
					</div>
					<div class="cell cell_right wid_50">
						<div class="thead row resp_hide">
							<div class="cell wid_25">Thursday</div>
							<div class="cell wid_25">Friday</div>
							<div class="cell wid_25">Saturday</div>
							<div class="cell wid_25">Sunday</div>
						</div>
						<div class="row resp_hide">
							<div class="cell wid_25">5pm-12am</div>
							<div class="cell wid_25">5pm-3am</div>
							<div class="cell wid_25">5pm-3am</div>
							<div class="cell wid_25">5pm-12am</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row row_collapse row_baseline no_border">
				<div class="cell wid_30">
					<h3>Meals</h3>
					<div class="rest_item">
						<div class="rest_item_name">Big Mac Meal</div>
						<div class="rest_item_price">$7.19</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Double Big Mac Meal</div>
						<div class="rest_item_price">$7.19</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Quarter Pounder Meal</div>
						<div class="rest_item_price">$7.19</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Sweet Chili McWrap</div>
						<div class="rest_item_price">$7.19</div>
					</div>
				</div>
				<div class="cell wid_30">
					<h3>Sides</h3>
					<div class="rest_item">
						<div class="rest_item_name">World Famous Fries</div>
						<div class="rest_item_price">$1.49 / $2.49 / $3.49</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">McDouble</div>
						<div class="rest_item_price">$1.49</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Jr. Chicken</div>
						<div class="rest_item_price">$1.49</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Snack Wrap</div>
						<div class="rest_item_price">$1.49</div>
					</div>
				</div>
				<div class="cell wid_30">
					<h3>Other</h3>
					<div class="rest_item">
						<div class="rest_item_name">McFlurry</div>
						<div class="rest_item_price">$1.99 / $2.99 / $3.99</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Milkshake</div>
						<div class="rest_item_price">$1.99 / $2.99 / $3.99</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Ice Cream</div>
						<div class="rest_item_price">$1.49 / $2.49</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Smoothie</div>
						<div class="rest_item_price">$1.49 / $2.49 / $3.49</div>
					</div>
				</div>
			</div>

		</div>

	</div>
</div>

<?php include "footer.php" ?>

</div>

</body>
</html>