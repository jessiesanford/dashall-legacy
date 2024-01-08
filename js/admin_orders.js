$(document).ready(function() {


	getOrders();

	function orders_time_graph(hours) {

		google.charts.load('current', {packages: ['corechart', 'bar']});
		google.charts.setOnLoadCallback(drawBasic);


		function drawBasic() {

			var data = new google.visualization.DataTable();
			data.addColumn('timeofday', 'Time of Day');
			data.addColumn('number', 'Amount of Orders');

			for (var key in hours) {
				var hour = hours[key].hour;
				var count = hours[key].count;
				data.addRow([{v: [parseFloat(hour), 0, 0], f: parseFloat(hour) + ':00'}, parseFloat(count)]);
			}

			var options = {
			title: 'Orders Per Hour',
			hAxis: {
			  title: 'Time of Day',
			  format: 'h a',
			  viewWindow: {
			    min: [0, 30, 0],
			    max: [24, 30, 0]
			  }
			},
			vAxis: {
			  title: 'Amount Of Orders'
			}
			};

			var chart = new google.visualization.ColumnChart(
			document.getElementById('chart_div'));

			chart.draw(data, options);
		}
	}


	var today = moment();

	$(function() {
		$("#start_date, #end_date").datepicker({
			dateFormat: 'MM dd yy',
			maxDate: moment(today).format('MMMM DD YYYY'),
			onSelect: function(date) {
     			getOrders();
     		}
		});
	});

	function getOrders()
	{
			start_date = new Date($("#start_date").val());
		 	end_date = new Date($("#end_date").val());

		 	start_date = moment(start_date).subtract(1, 'days').format('YYYY-MM-DD');
		 	end_date = moment(end_date).add(1, 'days').format('YYYY-MM-DD');

		 	if (isNaN(Date.parse(start_date)) || isNaN(Date.parse(end_date))) {
		 		console.log("changing dates");
		 		start_date = "0000-00-00";
		 		end_date = "9999-12-31";
		 	}


			$('.order_row').removeClass('orders_row_selected');

			$.ajax({
				type: "POST",
				url: 'controllers/admin.php',
				data: {start_date: start_date, end_date: end_date, action: "get_orders_in_range"},
				dataType: "json",
				success: function(data) 
				{
					console.log(data);
					$('#orders_view').fadeOut(200);
					$('#orders_view').empty();

					if (data.orders.length == 0)
					{
						popup('No results.');
					}
					else 
					{
						var total_orders = 0;
						var total_complete_orders = 0;
						var repeat_customer_count = 0;
						var template = document.getElementById('order_row').innerHTML;

						orders_time_graph(data.order_stats.hot_hours);

						$.each(data.orders, function(index, order) 
						{			
							transaction = Mustache.render(template, order);
							$('#orders_view').append(transaction);

							total_orders++;

							if (order.order_status == "ARCH" || order.order_status == "COM") {
								total_complete_orders++;
							}

							if (order.repeat_customer == true) {
								repeat_customer_count++; 
							}
						}); 

						$('#total_orders').text(data.order_stats.total_orders);
						$('#total_complete_orders').text(data.order_stats.total_complete_orders);
						$('#repeat_customer_count').text(data.order_stats.repeat_customer_count);
						$('#repeat_customer_orders').text(data.order_stats.repeat_customer_orders);
						$('#avg_order_time').text(data.order_stats.avg_order_time);


						$('#orders_view').fadeIn(200);
					}

				},
				error: function (xhr, ajaxOptions, thrownError) 
				{
		        	popup(xhr.status + thrownError);
		        	console.warn(xhr.responseText);
		      	}
			});
			return false;
		
	}


	$(document).on('click', '#reset_orders', function() 
	{
		$("#start_date").val('');
		$("#end_date").val('');
		getOrders();
	});


	$(document).on('click', '.order_row', function() 
	{
		if (!$(this).children('.order_info').is(':visible'))
		{
			$(this).addClass('order_row_selected no_border');
			$(this).children('.order_info').stop(true, true).slideDown(200);
		}
		else 
		{
			$(this).removeClass('order_row_selected no_border');
			$(this).children('.order_info').stop(true, true).slideUp(200);
		}
	});

});