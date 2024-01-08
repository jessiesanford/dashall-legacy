<script type="text/javascript" src="./js/stats.js"></script>

<div class="section orders_section">

	<div class="page_heading">
		<h1 class="page_title">Users</h1>
	</div>

			<div class="section">

				<?php $sql = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total FROM users "));
				echo '<div class="count_block push_bottom_20">Registered Users <div class="counter_count">' . $sql[0] . '</div></div>'; ?>

				<form id="user_search" class="float_right">
					<input type="textbox" class="textbox" placeholder="Search..." />
					<button type="submit"><i class="fa fa-search"></i></button>
				</form>

				<br class="clear">

				<?php
					$limit = 40;

					$start = $_GET['page'] * $limit;
					if ($start == null)
					{
						$start = 0;
					}

					$sql = mysql_query("SELECT COUNT(*) as count FROM users");

					$user_count = mysql_fetch_assoc($sql);

					$query = "SELECT user_id, user_email, user_firstName, user_lastName, user_phone, user_date FROM users ORDER BY user_firstName LIMIT ". $limit ." OFFSET ". $start ."";
					$users = mysql_query($query);

					if($users)
					{ 
						echo '<div>';
						for ($i = 0; $i < ($user_count['count'] / $limit); $i++)
						{
							if ($_GET['page'] == $i) {
								echo '<a class="button button_alt small" href="admin?module=users&page='. $i .'">'. ($i + 1) .'</a> ';
							}
							else {
								echo '<a class="button small" href="admin?module=users&page='. $i .'">'. ($i + 1) .'</a> ';
							}
						}
						echo '</div><br />';
				?>

				<div id="user_table" class="table">
					<div class="row thead">
						<div class="cell wid_25">Email</div>
						<div class="cell wid_25">Name</div>
						<div class="cell wid_25">Phone</div>
						<div class="cell wid_25 resp_hide">Date</div>
					</div>
					<div class="rows">

					<?php
							if(mysql_num_rows($users) != 0)
							{
								// generate restaurants
								while($user = mysql_fetch_assoc($users))
								{


								echo '
									<div class="row row_collapse">
										<div class="cell wid_25">'. $user['user_email'] .'</div>
										<div class="cell wid_25"><a href="admin?module=customer&id='. $user['user_id'] .'">'. $user['user_firstName'] .' '. $user['user_lastName'] .'</a></div>
										<div class="cell wid_25">'. $user['user_phone'] .'</div>
										<div class="cell resp_hide wid_25">'. $user['user_date'] .'</div>
									</div>
								';

								}
							}
						}

					?>

				</div>
			</div>
		</div>

</div>