<script type="text/javascript" src="./js/admin_settings.js"></script>
<link rel="stylesheet" href="./css/admin_settings.css" />

<div class="section" id="settings_section">

	<div class="page_heading">
		<h1 class="page_title">Global Settings</h1>
	</div>

	<?php 
		if ($store_hours->is_open()) {
			$class = "settings_heading_open";
		} 

		echo '
		<div class="row thead '. $class .'">
			<div class="cell">';
				$store_hours->render(); 
		echo '
			</div>
		</div>'; 

		$sql = mysql_query("SELECT * FROM settings WHERE settings.name = 'taking_orders'");
		$taking_orders = mysql_fetch_assoc($sql);

		$sql = mysql_query("SELECT * FROM settings WHERE settings.name = 'force_operation'");
		$force_operation = mysql_fetch_assoc($sql);

		$sql = mysql_query("SELECT * FROM settings WHERE settings.name = 'open_notice'");
		$open_notice = mysql_fetch_assoc($sql);

		$sql = mysql_query("SELECT * FROM settings WHERE settings.name = 'closed_notice'");
		$closed_notice = mysql_fetch_assoc($sql);

		$sql = mysql_query("SELECT * FROM settings WHERE settings.name = 'management_mode'");
		$management_mode = mysql_fetch_assoc($sql);


	?>

			<form id="update_settings">

				<div class="row">
					<div class="cell wid_50">
						<div>Enable Automated Operations (Set to "No" to close DashAll)</div>
					</div>	
					<div class="cell wid_50">				
						<select id="taking_orders" name="taking_orders">
							<?php 
								if ($taking_orders['value'] == 1) 
								{
									echo '
										<option value="1" selected>Yes</option>
										<option value="0">No</option>
									';
								}
								else 
								{
									echo '
										<option value="1">Yes</option>
										<option value="0" selected>No</option>
									';
								}
							?>
						</select>
					</div>
				</div>

				<div class="row">
					<div class="cell wid_50">
						<div>Force Open Status <strong>[Overrides Automation]</strong> (Set to "Yes" to force open DashAll)</div>
					</div>	
					<div class="cell wid_50">				
						<select id="force_operation" name="force_operation">
							<?php 
								if ($force_operation['value'] == 1) 
								{
									echo '
										<option value="1" selected>Yes</option>
										<option value="0">No</option>
									';
								}
								else 
								{
									echo '
										<option value="1">Yes</option>
										<option value="0" selected>No</option>
									';
								}
							?>
						</select>
					</div>
				</div>

				<div class="row">
					<div class="cell wid_50">
						<div>Management Mode</div>
					</div>
					<div class="cell wid_50">
						<select id="management_mode" name="management_mode">
							<?php 
								// manuel
								if ($management_mode['value'] == 0) 
								{
									echo '
										<option value="0" selected>Manual (does not automatically delegate orders)</option>
										<option value="1">Active (delegates orders to specific driver(s))</option>
										<option value="2">Passive (delegates orders to any drivers that are active)</option>
										<option value="3">Scheduled (delegate orders to scheduled driver)</option>
									';
								}
								// Delegation (Active Driver)
								else if ($management_mode['value'] == 1) 
								{
									echo '
										<option value="0">Manual (does not automatically delegate orders)</option>
										<option value="1" selected>Active (delegates orders to specific driver(s))</option>
										<option value="2">Passive (delegates orders to any drivers that are active)</option>
										<option value="3">Scheduled (delegate orders to scheduled driver)</option>
									';
								}
								// Delegate (Any Available Drivers)
								else if ($management_mode['value'] == 2)
								{
									echo '
										<option value="0">Manual (does not automatically delegate orders)</option>
										<option value="1">Active (delegates orders to specific driver(s))</option>
										<option value="2" selected>Passive (delegates orders to any drivers that are active)</option>
										<option value="3">Scheduled (delegate orders to scheduled driver)</option>
									';
								}
								// Delegate (Scheduled Driver)
								else 
								{
									echo '
										<option value="0">Manual (does not automatically delegate orders)</option>
										<option value="1">Active (delegates orders to specific driver(s))</option>
										<option value="2">Passive (delegates orders to any drivers that are active)</option>
										<option value="3" selected>Scheduled (delegate orders to scheduled driver)</option>
									';
								}
							?>
						</select>
					</div>
				</div>


				<?php
					if ($management_mode['value'] == 1) 
					{
						echo '
							<div class="row">
								<div class="cell wid_50">
									Active Driver Selection
								</div>
								<div class="cell wid_50">
									<select id="active_driver" name="active_driver">';

									$sql = mysql_query("SELECT drivers.*, users.user_id, users.user_firstName, users.user_lastName FROM drivers INNER JOIN users ON users.user_id = drivers.user");

									while ($driver = mysql_fetch_assoc($sql))
									{
										echo '<option value="'. $driver['user'] .'" >'. $driver['user_firstName'] .' '. $driver['user_lastName'] .'</option>';	
									}

									echo '</select>';

			                        $sql = mysql_query("
			                            SELECT settings.*, users.user_firstName, users.user_lastName, users.user_phone FROM settings
			                            INNER JOIN users ON users.user_id = settings.value
			                            WHERE settings.name = 'active_driver'
			                        ");

			                        $driver = mysql_fetch_assoc($sql);

						echo '
								</div>
							</div>
							<div class="row">
								<div class="cell wid_50">
									Current Active Driver
								</div>
								<div class="cell wid_50">
								<strong>' . $driver['user_firstName'] .' '. $driver['user_lastName'] . '</strong>
								</div>
							</div>


						';			
					}
					else if ($management_mode['value'] == 3) 
					{
						$sql = mysql_query("
							SELECT users.user_firstName, users.user_lastName, driver_shifts.* 
							FROM driver_shifts
							INNER JOIN users ON users.user_id = driver_shifts.driver_id
							WHERE DATE('". TIMESTAMP ."') = DATE(driver_shifts.start_datetime)
							ORDER BY driver_shifts.start_datetime
						");
						echo '
						<div class="row">
							<div class="cell wid_50">Scheduled Drivers</div>
							<div class="cell wid_50">
						';

						while($shift = mysql_fetch_assoc($sql)) {

							$class = "";
							if (   date('h:i', strtotime($shift['start_datetime'])) < date('h:i', strtotime(TIMESTAMP)) &&  date('h:i', strtotime($shift['end_datetime'])) > date('h:i', strtotime(TIMESTAMP)) ) {
								$class = "scheduled_time_active";
							}

							echo '
								<div class="padd_5"><span class="scheduled_time '. $class .'">'. date('h:i', strtotime($shift['start_datetime'])) .' - '. date('h:i', strtotime($shift['end_datetime'])) .'</span> <strong>'. $shift['user_firstName'] . ' ' . $shift['user_lastName'] .'</strong></div>
							';
						}

						echo '</div></div>';


					}
				?>

				<div class="row">
					<div class="cell wid_50">
						Open Notice
					</div>
					<div class="cell wid_50">
						<textarea class="textarea" id="open_notice" name="open_notice" placeholder="Notice goes here..." style="width: 300px; height: 100px;"><?php echo $open_notice['value'] ?></textarea>
					</div>
				</div>
				<div class="row">
					<div class="cell wid_50">
						Closed Notice
					</div>
					<div class="cell wid_50">
						<textarea class="textarea" id="closed_notice" name="closed_notice" placeholder="Notice goes here..." style="width: 300px; height: 100px;"><?php echo $closed_notice['value'] ?></textarea>
					</div>
				</div>
				<button class="push_top_20 button">Update Settings</button>
			</form>

</div>