$(document).ready(function() {

	var today = moment();

	$(function() {
		$("#start_date, #end_date").datepicker({
			dateFormat: 'MM dd yy',
			maxDate: moment(today).format('MMMM DD YYYY'),
			onSelect: function(date) {
     			getTransactions();
     		}
		});
		// $('#start_date, #end_date').val(moment(today).format('MMMM DD YYYY'));
	});

	function getTransactions()
	{
		if ($("#end_date").val() == "" || $("#start_date").val() == "")
		{
			popup('Please specify two dates.');
		}
		else 
		{
			start_date = new Date($("#start_date").val());
		 	end_date = new Date($("#end_date").val());

		 	// hella convoluted
		 	start_date = moment(start_date).subtract(1, 'days').format('YYYY-MM-DD HH:mm:ss');
		 	end_date = moment(end_date).add(1, 'days').format('YYYY-MM-DD HH:mm:ss');

			$('.trans_row').removeClass('trans_row_selected');

			$.ajax({
				type: "POST",
				url: 'controllers/admin.php',
				data: {start_date: start_date, end_date: end_date, action: "get_transactions_in_range"},
				dataType: "json",
				success: function(data) 
				{
					console.log(data);
					$('#transactions_view').fadeOut(200);
					$('#transactions_view').empty();

					if (data.orders.length == 0)
					{
						popup('No results.');
					}
					else 
					{
						var template = document.getElementById('transaction_row').innerHTML;

						$.each(data.orders, function(index, order) 
						{							
							transaction = Mustache.render(template, order);

							$('#transactions_view').append(transaction);
						}); 

						var order_stats = data.order_stats;

		 				$('#rev_amount').text('$' + order_stats.revenue.toFixed(2));
		 				$('#profit_amount').text('$' + order_stats.profit.toFixed(2));
						$('#firstdash_count').text(order_stats.promo_count);
						$('#dashcash_count').text(order_stats.dashcash_count);
						$('#order_count').text(order_stats.order_count);
						$('#repeat_customer_count').text(order_stats.repeat_customer_count);
						$('#avg_order_cost').text('$' + order_stats.avg_order_cost);
						$('#avg_profit').text('$' + order_stats.avg_order_profit);

						$('#transactions_view').fadeIn(200);
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
	}


	$(document).on('click', '#reset_transactions', function() 
	{
		$("#transactions_wrapper").load(location.href + " #transactions_wrapper>*","");
		$('#trans_stats').load(location.href + " #trans_stats>*","");
	});


	$(document).on('click', '.trans_row', function() 
	{
		if (!$(this).children('.trans_info').is(':visible'))
		{
			$(this).addClass('trans_row_selected no_border');
			$(this).children('.trans_info').stop(true, true).slideDown(200);
		}
		else 
		{
			$(this).removeClass('trans_row_selected no_border');
			$(this).children('.trans_info').stop(true, true).slideUp(200);
		}
	});

});