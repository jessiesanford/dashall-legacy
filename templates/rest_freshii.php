<?php
	require 'connect.php';
	$pageTitle = "Freshii";
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
						<div class="rest_logo push_right"><img src="images/rest_freshii.png" alt="Freshii"/></div>
					</div>
					<div class="cell">
						<h1 class="no_padd">Freshii</h1>
					</div>
					<div class="cell cell_right wid_50">
						<div class="thead row resp_hide">
							<div class="cell wid_25">Thursday</div>
							<div class="cell wid_25">Friday</div>
							<div class="cell wid_25">Saturday</div>
							<div class="cell wid_25">Sunday</div>
						</div>
						<div class="row resp_hide">
							<div class="cell wid_25">5pm-9pm</div>
							<div class="cell wid_25">5pm-9pm</div>
							<div class="cell wid_25">5pm-9pm</div>
							<div class="cell wid_25">5pm-8pm</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row row_collapse row_baseline no_border">
				<div class="cell wid_30">
					<h3>Salads & Wraps</h3>
					<div class="rest_item">
						<div class="rest_item_name">Metaboost</div>
						<div class="rest_item_price">Salad $9.49 / Wrap $8.49</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Cobb</div>
						<div class="rest_item_price">Salad $9.79 / Wrap $8.79</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Fiesta</div>
						<div class="rest_item_price">Salad $8.99 / Wrap $7.99</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Market</div>
						<div class="rest_item_price">Salad $9.49 / Wrap $8.49</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Buffalo</div>
						<div class="rest_item_price">Salad $8.49 / Wrap $7.49</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Zen</div>
						<div class="rest_item_price">Salad $8.29 / Wrap $7.29</div>
					</div>
				</div>
				<div class="cell wid_30">
					<h3>Bowls</h3>
					<div class="rest_item">
						<div class="rest_item_name">Pangoa</div>
						<div class="rest_item_price">$8.29</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Teriyaki Twist</div>
						<div class="rest_item_price">$7.29</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Oaxaca</div>
						<div class="rest_item_price">$8.29</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Mediterranean</div>
						<div class="rest_item_price">$8.99</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Buddha's Satay</div>
						<div class="rest_item_price">$7.49</div>
					</div>					
				</div>
				<div class="cell wid_30">
					<h3>Burritos</h3>
					<div class="rest_item">
						<div class="rest_item_name">Tex Mex</div>
						<div class="rest_item_price">$8.29</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Khao San</div>
						<div class="rest_item_price">$7.49</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Smokehouse</div>
						<div class="rest_item_price">$7.29</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Baja</div>
						<div class="rest_item_price">$8.29</div>
					</div>
				</div>
			</div>

			<br />

			<div class="row row_collapse row_baseline no_border">
				<div class="cell wid_30">
					<h3>Soups</h3>
					<div class="rest_item">
						<div class="rest_item_name">Spicey Lemongrass</div>
						<div class="rest_item_price">$7.49</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Superfood</div>
						<div class="rest_item_price">$7.49</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Southwestern</div>
						<div class="rest_item_price">$7.49</div>
					</div>
				</div>
				<div class="cell wid_30">
					<h3>Juices</h3>
					<div class="rest_item">
						<div class="rest_item_name">Green Energy</div>
						<div class="rest_item_price">$5.99</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Mighty Detox</div>
						<div class="rest_item_price">$5.99</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Carrot Zinger</div>
						<div class="rest_item_price">$5.99</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Red Power</div>
						<div class="rest_item_price">$5.99</div>
					</div>
				</div>
				<div class="cell wid_30">
					<h3>Smoothies</h3>
					<div class="rest_item">
						<div class="rest_item_name">Freshii Green</div>
						<div class="rest_item_price">$5.99</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Banana Nut Crunch</div>
						<div class="rest_item_price">$5.99</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Powerhouse</div>
						<div class="rest_item_price">$5.99</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Strawberry Banana</div>
						<div class="rest_item_price">$5.99</div>
					</div>
				</div>
			</div>

			<br />

			<div class="row row_collapse row_baseline no_border">
				<div class="cell wid_30">
					<h3>Frozen Yogurt</h3>
					<div class="rest_item">
						<div class="rest_item_name">Low-Fat Frozen Yogurt</div>
						<div class="rest_item_price">$4.99</div>
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