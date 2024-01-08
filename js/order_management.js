// jscript for orders.php

$(document).ready(function() {

$(document).on('click', ".update_order_status", function (e)
{
	var element = $(this);
	var order_id = $(this).attr('data-order_id');
	var order_status = ($(this).attr('id')).substring(3);

	$.ajax({
		type: "POST",
		url: "controllers/manage.php",
		data:{
			"action": "updateOrderStatus",
			"order_id": order_id,
			"order_status": order_status
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

$(document).on('click', ".order_status_ind", function (e)
{
	var element = $(this);
	var order_id = $(this).attr('data-order_id');

	if ($(this).attr('id') == 'os_COM')
	{
		return false;
	}

	$.ajax({
		type: "POST",
		url: "controllers/manage.php",
		data:{
			"action": "updateOrderStatus_ind",
			"order_id": order_id,
		},
		success: function(data)
		{
			model_create(data);
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
		url: "controllers/manage.php",
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
		url: "controllers/manage.php",
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

$(".rotate").textrotator({
		animation: "flipUp",
		separator: ",",
	speed: 2500
});


$(document).on('click', ".update_cost", function (e)
{
	e.preventDefault();

	if ($(this).parent().children('input.textbox').val() == "")
	{
		popup("Please specify an amount that you paid.");
	}
	else 
	{
		var order_id = $(this).attr('data-order_id');
		var order_cost = $(this).parent().children('input.textbox').val();

		content = "Please confirm that you paid<br /><h2>$" + order_cost + "</h2>"; 
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
		url: "controllers/manage.php",
		data:{
			"action": "updateOrderCost",
			"order_id": order_id,
			"order_cost": order_cost
		},
		success: function(data)
		{
			popup(data);
			model_destroy();
			$('.orders_section').load(location.href + " .orders_section>*","");
		}
	});
});



// this is a global switch for the system
$(document).on('click', "#taking_orders_update", function (e)
{
	var taking_orders_value = $('#taking_orders_select option:selected').val();

	$.ajax({
		type: "POST",
		url: "controllers/manage.php",
		data:{
			"action": "takingOrdersUpdate",
			"taking_orders_value": taking_orders_value
		},
		success: function(data)
		{
			popup(data);
		}
	});

	return false;
});

// this is a global switch for the system
$(document).on('click', ".edit_order", function (e)
{
	popup('clicked');

	$.ajax({
		type: "POST",
		url: "controllers/manage.php",
		data:{
			"action": "promote",
			"order_phone": order_phone
		},
		success: function(data)
		{
			popup(data);
		}
	});

	return false;
});





});