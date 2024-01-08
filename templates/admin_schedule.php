<link rel="stylesheet" href="css/schedule.css">
<script type="text/javascript" src="js/schedule.js"></script>


<div class="section">

	<?php 
			$current_day = new DateTime(date('Y-m-d', strtotime(TIMESTAMP)));

			$current_week = array();
			$next_week= array();

			// if sunday
			if (date('w', strtotime('Today')) == 0)
			{
				$current_week[0] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday this week')));
				$current_week[1] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday this week +1 days')));
				$current_week[2] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday this week +2 days')));
				$current_week[3] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday this week +3 days')));
				$current_week[4] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday this week +4 days')));
				$current_week[5] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday this week +5 days')));
				$current_week[6] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday this week +6 days')));

				$next_week[0] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday next week')));
				$next_week[1] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday next week +1 days')));
				$next_week[2] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday next week +2 days')));
				$next_week[3] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday next week +3 days')));
				$next_week[4] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday next week +4 days')));
				$next_week[5] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday next week +5 days')));
				$next_week[6] = new DateTime(date('Y-m-d H:i:s', strtotime('Sunday next week +6 days')));
			}
			else 
			{
				$current_week[0] = new DateTime(date('Y-m-d H:i:s', strtotime('last Sunday this week')));
				$current_week[1] = new DateTime(date('Y-m-d H:i:s', strtotime('Monday this week')));
				$current_week[2] = new DateTime(date('Y-m-d H:i:s', strtotime('Tuesday this week')));
				$current_week[3] = new DateTime(date('Y-m-d H:i:s', strtotime('Wednesday this week')));
				$current_week[4] = new DateTime(date('Y-m-d H:i:s', strtotime('Thursday this week')));
				$current_week[5] = new DateTime(date('Y-m-d H:i:s', strtotime('Friday this week')));
				$current_week[6] = new DateTime(date('Y-m-d H:i:s', strtotime('Saturday this week')));

				$next_week[0] = new DateTime(date('Y-m-d H:i:s', strtotime('last Sunday +7 days')));
				$next_week[1] = new DateTime(date('Y-m-d H:i:s', strtotime('Monday this week +7 days')));
				$next_week[2] = new DateTime(date('Y-m-d H:i:s', strtotime('Tuesday this week +7 days')));
				$next_week[3] = new DateTime(date('Y-m-d H:i:s', strtotime('Wednesday this week +7 days')));
				$next_week[4] = new DateTime(date('Y-m-d H:i:s', strtotime('Thursday this week +7 days')));
				$next_week[5] = new DateTime(date('Y-m-d H:i:s', strtotime('Friday this week +7 days')));
				$next_week[6] = new DateTime(date('Y-m-d H:i:s', strtotime('Saturday this week +7 days')));
			}

			$week_arr = array();
			$week_arr[1] = $current_week;
			$week_arr[2] = $next_week;

			$sql = mysql_query("
				SELECT * FROM driver_shifts
			");

			echo '
				<div class="page_heading">
					<h1 class="page_title">Schedule</h1>
				</div>
			';

			echo '<div id="calendar_weeks_view">';

			foreach ($week_arr as $week) {
				
				echo '
					<div class="calendar_row thead resp_hide">
				';

				for ($i = 0; $i < 7; $i++)
				{
					if ($week[$i]->format('Y-m-d') == $current_day->format('Y-m-d'))
					{
						$active_day = 'active_day this week';
					}
					else 
					{
						$active_day = '';
					}

						echo '<div class="calendar_head_cell align_center '. $active_day .'">'. $week[$i]->format('D, M d') .'</div>';
				}

				echo '
					</div>

					<div class="calendar_row row_collapse" id="calendar_row">

					';


						for ($i = 0; $i < 7; $i++)
						{

							echo '<div class="calendar_day">';
							// friday saturday shifs
							if ($i == 5 || $i == 6)
							{
								$shifts = array(
									array($week[$i]->setTime(17, 00)->format('Y-m-d H:i:s'), $week[$i]->setTime(23, 00)->format('Y-m-d H:i:s')),
									array($week[$i]->setTime(18, 00)->format('Y-m-d H:i:s'), $week[$i]->setTime(21, 00)->modify('+1 day')->format('Y-m-d H:i:s')),
									array($week[$i]->setTime(23, 00)->format('Y-m-d H:i:s'), $week[$i]->setTime(03, 00)->modify('+1 day')->format('Y-m-d H:i:s'))
								);
							}
							else 
							{
								$shifts = array(
									array($week[$i]->setTime(17, 00)->format('Y-m-d H:i:s'), $week[$i]->setTime(21, 00)->format('Y-m-d H:i:s')),
									array($week[$i]->setTime(21, 00)->format('Y-m-d H:i:s'), $week[$i]->setTime(00, 00)->modify('+1 day')->format('Y-m-d H:i:s')),
								);
							}

							$day_heading = new DateTime(date('Y-m-d H:i:s', strtotime($shifts[0][0])));
							echo '<div class="cell thead resp_show">'. $day_heading->format('D, M d') .'</div>';

							// two shifts per night
							for ($j = 0; $j < 3; $j++)
							{
								$sql = mysql_query("
						            SELECT shift_id, req_unshift, user_id, first_name, last_name FROM driver_shifts 
						            INNER JOIN users ON users.user_id = driver_shifts.driver_id
						            WHERE driver_shifts.start_datetime = '" . $shifts[$j][0] . "'
        						");
        						$shift = mysql_fetch_assoc($sql);


								$sql = mysql_query("
						            SELECT COUNT(*) as count FROM driver_shifts 
						            INNER JOIN users ON users.user_id = driver_shifts.driver_id
						            WHERE driver_shifts.start_datetime = '" . $shifts[$j][0] . "'
									");
								$count_drivers_on_shift = mysql_fetch_assoc($sql)['count'];


								$sql = mysql_query("
						            SELECT COUNT(*) as count FROM driver_shifts 
						            INNER JOIN users ON users.user_id = driver_shifts.driver_id
						            WHERE driver_shifts.start_datetime = '" . $shifts[$j][0] . "' AND driver_id = ". $_SESSION['user_id'] ."
									");
								$check_self_shift = mysql_fetch_assoc($sql)['count'];

								// if I own a shift, disable it
								if ($check_self_shift > 0) {
									$shift_classes = "shift_assigned shift_self";
									if ($shift['req_unshift'] == 1)
									{
										$shift_classes = $shift_classes . " shift_pending";
									}
								}
								// for each shift we check if there is a slot open first
								else if ($count_drivers_on_shift < 1)
								{
									$shift_classes = "confirm_shift";
								}
								else {
									$shift_classes = "shift_assigned";
								}

								if ($i < 5 && $j == 2) {
									$shift_classes = "shift_unavailable";
								}


								echo '
									<div class="calendar_shift '. $shift_classes .'"  
										data-action="assign_shift" 
										data-desc="'. date('D, m @ g:ia', strtotime($shifts[$j][0])) .' - '. date('D, m @ g:ia', strtotime($shifts[$j][1])) .'" 
										data-button="Shift Me"
										data-start="'. $shifts[$j][0] .'"
										data-end="'. $shifts[$j][1] .'">
										'. date('g:ia', strtotime($shifts[$j][0])) .' - '. date('g:ia', strtotime($shifts[$j][1])) .'<br/>';


										$sql = mysql_query("
								            SELECT shift_id, req_unshift, user_id, user_firstName, user_lastName FROM driver_shifts 
								            INNER JOIN users ON users.user_id = driver_shifts.driver_id
								            WHERE driver_shifts.start_datetime = '" . $shifts[$j][0] . "'
											");

										while($shift_driver = mysql_fetch_assoc($sql)) {
											echo '<strong>'. $shift_driver['user_firstName'] . ' ' . substr($shift_driver['user_lastName'], 0, 1) . '.</strong><br>';
										}
									echo '</div>
								';

							}

							echo '</div>';

						}
						

				echo '</div><br>';
			}

			// end calendar weeks view
			echo '</div>';
			
			echo '



				<br />
				<h3>Legend</h3>
				<div class="row no_border">
					<div>
						<div class="calendar_shift calendar_legend inline_block">Shift Available</div>
						<div class="calendar_shift calendar_legend shift_assigned inline_block">Shift Unavailable</div>
						<div class="calendar_shift calendar_legend shift_self inline_block">Your Shift</div>
					</div>
				</div>

				<hr>
				<h3>Manage Shifts</h3>
				<div id="shifts_table">
					<div class="row thead">
						<div class="cell wid_20">Driver</div>
						<div class="cell wid_15">Date</div>
						<div class="cell wid_15">Start</div>
						<div class="cell wid_15">End</div>
					</div>
				';

			$sql = mysql_query("SELECT * FROM driver_shifts INNER JOIN users ON users.user_id = driver_shifts.driver_id ORDER BY start_datetime");
			while ($shift = mysql_fetch_assoc($sql))
			{
				$row_class = "";
				if ($shift['req_unshift'] == 1) 
				{
					$row_class = "row_error";
				} 
				echo '
					<div class="row driver_shift '. $row_class .'" id="'. $shift['shift_id'] .'">
						<div class="cell wid_20">'. $shift['user_firstName'] .' '. $shift['user_lastName'] .'</div>
						<div class="cell wid_15">'. date('M d Y', strtotime($shift['start_datetime'])) .'</div>
						<div class="cell wid_15">'. date('h:i a', strtotime($shift['start_datetime'])) .'</div>
						<div class="cell wid_15">'. date('h:i a', strtotime($shift['end_datetime'])) .'</div>
						<div class="cell cell_right align_right">
					';

					if ($shift['req_unshift'] == 1)  
					{
						echo '
							<strong>Unshift Requested</strong>&nbsp; 
							<button class="remove_shift"><i class="fa fa-times"></i></button>
						';
					} 
					else {
						echo '
							<button class="remove_shift"><i class="fa fa-times"></i></button>
						';
					}

				echo '
						</div>
					</div>
				';
			}
			echo '</div>';

	?>

</div>