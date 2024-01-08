<?php include("templates/html/orders.html") ?>

<link rel="stylesheet" type="text/css" href="../css/admin_orders.css" />
<script type="text/javascript" src="../js/admin_drivers.js"></script>

<div class="section orders_section">

	<div class="page_heading">
		<h1 class="page_title">Drivers</h1>
	</div>

	<h3>Mass Driver Notification</h3>

	<form id="mass_text_drivers">

		<div class="row row_baseline">
				<div class="cell">
						<textarea name="message" class="block" style="min-width: 400px; min-height: 200px;"></textarea>
						<button type="submit" class="push_top_20">Send Message</button>
				</div>
				<div class="cell">
					<select name="driver[]" style="min-height: 200px;" multiple>
						<?php 
							$sql = mysql_query("
								SELECT drivers.*, users.user_id, users.user_firstName, users.user_lastName FROM drivers INNER JOIN users ON users.user_id = drivers.user
								ORDER BY users.user_firstName
								");

							while ($driver = mysql_fetch_assoc($sql))
							{
								echo '<option value="'. $driver['user'] .'" >'. $driver['user_firstName'] .' '. $driver['user_lastName'] .'</option>';	
							}

			                $sql = mysql_query("
			                    SELECT settings.*, users.user_firstName, users.user_lastName, users.user_phone FROM settings
			                    INNER JOIN users ON users.user_id = settings.value
			                    WHERE settings.name = 'active_driver'
			                ");

			                $driver = mysql_fetch_assoc($sql);
						?>
					</select>
				</div>
		</div>

	</form>


</div>