// jscript for driver.php

$(document).ready(function() {


$(document).on('click', ".self_assign", function (e)
{
	var element = $(this);
	var order_id = $(this).attr('data-order_id');

	$.ajax({
		type: "POST",
		url: 'controllers/driver.php',
		data: 'action=self_assign&order_id=' + order_id,
		dataType: "json",
		success: function(data) 
		{
			if (data.form_check == 'error')
			{
				popup(data.alert);
			}
			else 
			{
				popup(data.alert);
				location.reload();
			}	
		},
		error: function (xhr, ajaxOptions, thrownError) 
		{
        	alert(xhr.status + thrownError);
      	}
	});
	return false;

	return false;
});


$(document).on('click', ".report_issue_init", function (e)
{
	var order = $(this).closest('.order_row')
	var order_id = $(this).attr('data-order_id');

	$(this).hide();
	order.find('.self_assign').hide();

	$(this).closest('.order_row').append('<textarea class="block push_top wid_100" placeholder="What is wrong with the order?"></textarea><button class="report_issue push_top" data-order_id="'+order_id+'">Send Report</button>');

	return false;
});


$(document).on('click', ".report_issue", function (e)
{
	var order = $(this).closest('.order_row')
	var order_id = $(this).attr('data-order_id');
	var issue_text = order.find('textarea').val();

	$.ajax({
		type: "POST",
		url: 'controllers/driver.php',
		data: 'action=report_issue&order_id=' + order_id + '&issue_text=' + issue_text,
		success: function(data) 
		{
			if (data.form_check == 'error')
			{
				popup(data.alert);
			}
			else 
			{
				popup(data.alert);
				location.reload();
			}	
		},
		error: function (xhr, ajaxOptions, thrownError) 
		{
        	alert(xhr.status + thrownError);
      	}
	});

	return false;
});


$(document).on('click', ".mark_complete", function (e)
{
	var element = $(this);
	var order_id = $(this).attr('data-order_id');


	$.ajax({
		type: "POST",
		url: "controllers/driver.php",
		data:{
			"action": "mark_complete",
			"order_id": order_id,
		},
		success: function(data)
		{
			model_create(data);
		}
	});

	return false;
});

$(document).on('click', ".markComplete_verify", function (e)
{
	var element = $(this);
	var order_id = $(this).attr('data-order_id');

	$.ajax({
		type: "POST",
		url: "controllers/driver.php",
		data:{
			"action": "markComplete_verify",
			"order_id": order_id,
		},
		success: function(data)
		{
			model_destroy();
			popup(data);
			$('.orders_section').load(location.href + " .orders_section>*","");
		}
	});

	return false;
});

setInterval(function(){
	showLoad = false;
	$(".order_status_wrap").load(location.href + " .order_status_wrap","");
}, 10000);


$(document).on('click', ".update_cost", function (e)
{
	e.preventDefault();

	var order_id = $(this).attr('data-order_id');
	var order_cost = $(this).parent().children('input.textbox').val();

	if (order_cost == "")
	{
		popup("Please specify an amount that you paid.");
	}
	else if (isNaN(parseFloat(order_cost)))
	{
		popup("That is not a valid input.");
	}
	else 
	{
		order_cost = parseFloat(order_cost).toFixed(2);
		content = "Please confirm that you paid<br /><h2>$" + order_cost + "</h2>"; 
		content += '<div class="strong push_top push_bottom">Make sure you take a picture of the receipt!</div>'; 
		content += '<br /><button class="update_cost_confirm" data-order_id="' + order_id + '" data-order_cost="' + order_cost + '">Confirm</button>';

		model_create(content, false, 'align_center');

	}

	return false;
});

$(document).on('click', ".update_cost_confirm", function (e)
{
	var order_id = $(this).attr('data-order_id');
	var order_cost = $(this).attr('data-order_cost');

	$.ajax({
		type: "POST",
		url: "controllers/driver.php",
		data:{
			"action": "update_order_cost",
			"order_id": order_id,
			"order_cost": order_cost
		},
		dataType: 'json',
		success: function(data)
		{
			if (data.error != undefined)
			{
				popup(data.alert);
			}
			else 
			{
				popup(data.alert);
				model_destroy();
				$('.orders_section').load(location.href + " .orders_section>*","");
			}
		}
	});
});



$(document).on('click', ".send_arrival_status", function (e)
{
	var order_id = $(this).closest('.order_row').attr('data-order_id');

	$.ajax({
		type: "POST",
		url: "controllers/driver.php",
		data:{
			"action": "send_arrival_status",
			"order_id": order_id
		},
		dataType: 'json',
		success: function(data)
		{
			popup(data.alert);
			$('.orders_section').load(location.href + " .orders_section>*","");
		}
	});
});








});