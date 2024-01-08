<?php
	require 'connect.php';
	$page_title = "Quesada";
	$page_desc = "Local delivery from your favorite restaurants straight to your doorstep.";
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
						<div class="rest_logo push_right"><img src="images/rest_quesada.png" alt="Quesada"/></div>
					</div>
					<div class="cell">
						<h1 class="no_padd">Quesada</h1>
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
					<h3>Burrito / Burrito Bowl</h3>
					<div class="rest_item">
						<div class="rest_item_name">Roasted Veggie or Bean</div>
						<div class="rest_item_price">$5.29 / $7.39 /  $10.49</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Spicey Chicken or Ancho Pork</div>
						<div class="rest_item_price">$7.29 / $8.99 /  $12.99</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Flame-Grilled Chicken or Steak</div>
						<div class="rest_item_price">$7.49 / $9.29 /  $13.29</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Chile Lime Fish</div>
						<div class="rest_item_price">$7.89 / $9.69 /  $14.19</div>
					</div>
				</div>
				<div class="cell wid_30">
					<h3>Tacos</h3>
					<div class="rest_item">
						<div class="rest_item_name">Roasted Veggie or Bean</div>
						<div class="rest_item_price">(1) $2.79 / (3) $7.39</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Spicey Chicken or Ancho Pork</div>
						<div class="rest_item_price">(1) $3.59 / (3) $8.99</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Flame-Grilled Chicken or Steak</div>
						<div class="rest_item_price">(1) $3.69 / (3) $9.29</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Chile Lime Fish</div>
						<div class="rest_item_price">(1) $3.99 / (3) $9.69</div>
					</div>
				</div>
				<div class="cell wid_30">
					<h3>Quesadillas</h3>
					<div class="rest_item">
						<div class="rest_item_name">Roasted Veggie or Bean</div>
						<div class="rest_item_price">$7.39</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Spicey Chicken or Ancho Pork</div>
						<div class="rest_item_price">$8.99</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Flame-Grilled Chicken or Steak</div>
						<div class="rest_item_price">$9.29</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Chile Lime Fish</div>
						<div class="rest_item_price">$9.69</div>
					</div>
				</div>
			</div>
<br />
			<div class="row row_collapse row_baseline no_border">
				<div class="cell wid_30">
					<h3>Tortilla Salad</h3>
					<div class="rest_item">
						<div class="rest_item_name">Roasted Veggie or Bean</div>
						<div class="rest_item_price">$7.39</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Spicey Chicken or Ancho Pork</div>
						<div class="rest_item_price">$8.99</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Flame-Grilled Chicken or Steak</div>
						<div class="rest_item_price">$9.29</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Chile Lime Fish</div>
						<div class="rest_item_price">$9.69</div>
					</div>
				</div>
				<div class="cell wid_30">
					<h3>Nachos</h3>
					<div class="rest_item">
						<div class="rest_item_name">Veggie & Bean</div>
						<div class="rest_item_price">$6.79</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Chicken, steak, or pork</div>
						<div class="rest_item_price">$9.48</div>
					</div>
				</div>
			</div>
<br />
			<div class="row row_collapse row_baseline no_border">
				<div class="cell wid_30">
					<h3>Toppings</h3>
					<div class="rest_item">
						<div class="rest_item_name">Red Onion</div>
						<div class="rest_item_name">Cilantro</div>
						<div class="rest_item_name">Corn</div>
						<div class="rest_item_name">Jalapenos</div>
						<div class="rest_item_name">Lettuce</div>
						<div class="rest_item_name">Monterey Jack Cheese</div>
					</div>
				</div>
				<div class="cell wid_30">
					<h3>Sauces</h3>
					<div class="rest_item">
						<div class="rest_item_name">Source Cream</div>
						<div class="rest_item_name">Chipotle</div>
						<div class="rest_item_name">Habanero</div>
						<div class="rest_item_name">Paprika</div>
						<div class="rest_item_name">Cilantro-Lime</div>
						<div class="rest_item_name">No. 7 Habanero Garlic</div>
						<div class="rest_item_name">No. 7 Jalapeno</div>
						<div class="rest_item_name">No. 7 Habanero & Ghost</div>
					</div>
				</div>
				<div class="cell wid_30">
					<h3>Salsas</h3>
					<div class="rest_item">
						<div class="rest_item_name">Salsa Roja (Fiery Hot)</div>
						<div class="rest_item_name">Salsa Verde (Medium Heat)</div>
						<div class="rest_item_name">Chipotle Tomato (Mild/Medium Heat)</div>
						<div class="rest_item_name">Salsa Fresca (Mild & Refreshing)</div>
					</div>
				</div>
			</div>
<br />
			<div class="row row_collapse row_baseline no_border">
				<div class="cell wid_30">
					<h3>Add Ons</h3>
					<div class="rest_item">
						<div class="rest_item_name">Fresh Guacamole</div>
						<div class="rest_item_name">Roasted Veggies</div>
						<div class="rest_item_name">Extra Cheese</div>
						<div class="rest_item_name">($0.75 Each)</div>
					</div>
				</div>
				<div class="cell wid_30">
					<h3>Combo</h3>
					<div class="rest_item">
						<div class="rest_item_name">Add Chips & Salsa and a small fountain drink</div>
						<div class="rest_item_name">$2.49</div>
					</div>
				</div>
				<div class="cell wid_30">
					<h3>Chips, Salsa & Guacamole</h3>
					<div class="rest_item">
						<div class="rest_item_name">Chips & Salsa</div>
						<div class="rest_item_price">$1.79</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Chips & Guacamole</div>
						<div class="rest_item_price">$2.59</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Salsa</div>
						<div class="rest_item_price">(2oz) $0.69 / (4oz) $1.39</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Guacamole</div>
						<div class="rest_item_price">(2oz) $1.59 / (4oz) $2.99</div>
					</div>
				</div>
			</div>
<br />
			<div class="row row_collapse row_baseline no_border">
				<div class="cell wid_30">
					<h3>Kids Menu</h3>
					<div class="rest_item">
						<div class="rest_item_name">Any 2 Kids Items for $3.49</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Mini-Quesadillas</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Mini-Burritos</div>
					</div>
					<div class="rest_item">
						<div class="rest_item_name">Cheese Rollups</div>
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