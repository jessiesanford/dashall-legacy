// jscript for manage.php

$(document).ready(function() {

$(document).on('click', ".update_order_status", function (e)
{
	var element = $(this);
	var order_id = $(this).attr('data-order_id');
	var order_status = ($(this).attr('id')).substring(3);

	$.ajax({
		type: "POST",
		url: "./controllers/manage.php",
		data:{
			"action": "updateOrderStatus",
			"order_id": order_id,
			"order_status": order_status
		},
		success: function(data)
		{
			model_destroy();
			popup(data);
			$('#manage_section').load(location.href + " #manage_section>*","");
		}
	});

	return false;
});

$(document).on('click', ".manage_order_status", function (e)
{
	var element = $(this);
	var order_id = $(this).attr('data-order_id');

	if ($(this).attr('id') == 'ms_COM' || $(this).attr('id') == 'ms_CANC')
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
	var order_id = $(this).attr('data-value');

	$.ajax({
		type: "POST",
		url: "./controllers/manage.php",
		data:{
			"action": "mark_complete",
			"order_id": order_id,
		},
		dataType: "json",
		success: function(data)
		{
			console.log(data);
			model_destroy();
			popup(data.alert);
			$('.orders_section').load(location.href + " .orders_section>*","");
		}
	});

	return false;
});


setInterval(function(){
	showLoad = false;
	$(".order_status_wrap").load(location.href + " .order_status_wrap","");
}, 10000);

$(document).on('click', "#update_cost", function (e)
{
	var order_id = $(this).attr('data-order_id');
	var order_cost = $(this).parent().children('input.textbox').val();

	$.ajax({
		type: "POST",
		url: "./controllers/manage.php",
		data:{
			"action": "updateOrderCost",
			"order_id": order_id,
			"order_cost": order_cost
		},
		success: function(data)
		{
			popup(data);
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
		url: "./controllers/manage.php",
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


















$(document).on('click', ".delete_order", function (e)
{
	$.ajax({
		type: "POST",
		url: "./controllers/manage.php",
		data:{
			"action": "delete_order",
			"order_id": $(this).attr('data-value')
		},
		success: function(data)
		{
			model_destroy();
			popup(data);
			$('#manage_section').load(location.href + " #manage_section>*","");
		}
	});

	return false;
});

$(document).on('click', ".manage_order_heading", function (e)
{
	order = $(this).closest('.manage_order').find('.manage_order_toggle');


	if (order.is(":visible"))
	{
		order.hide();
	}
	else {
		order.show();
	}
	
	return false;
});

$(document).on('click', ".edit_order", function (e)
{
	order = $(this).closest('.manage_order');
	order_id = order.attr('id');
	order_desc = order.find('.desc').text();
	order_location = order.find('.location').text();
	order_address_street = order.find('.address_street').text();

	$(this).text('Submit Order Changes').removeClass().addClass('submit_order_changes')
	.attr('data_action', 'submit_order_changes')
	.attr('data_desc', 'Confirm Order Changes')
	.attr('data-order_id', order_id);

	$(order).find('.desc').empty().html('<textarea name="order_desc" class="textarea" rows="4">'+ order_desc +'</textarea>');
	$(order).find('.location').empty().html('<input type="text" name="order_location" class="textbox" value="'+ order_location +'" />');
	$(order).find('.address').empty().html('<input type="text" name="order_address_street" class="textbox" value="'+ order_address_street +'" />');
	
	return false;
});

$(document).on('click', ".submit_order_changes", function (e)
{
	$.ajax({
		type: "POST",
		url: "./controllers/manage.php",
		data: $('#'+ $(this).attr('data-order_id') +'.manage_order').serialize() + "&order_id=" + $(this).attr('data-order_id') + "&action=update_order",
		success: function(data)
		{
			popup(data);
			$('.manage_order').load(location.href + " .manage_order","");
		}
	});
	return false;
});



$(document).on('click', ".edit_order_cost", function (e)
{
	order = $(this).closest('.manage_order');
	order_id = order.attr('id');
	amount = order.find('.cost_amount span');
	margin = order.find('.cost_margin span');
	delivery_fee = order.find('.cost_delivery_fee span');


	model_create();
	$('#model').load('templates/edit_order_cost.html', function() {
		$("form.edit_order_cost_form input[name='order_id']" ).val(order_id);
		$("form.edit_order_cost_form input[name='amount']" ).val(amount.text());
		$("form.edit_order_cost_form input[name='margin']" ).val(margin.text());
		$("form.edit_order_cost_form input[name='delivery_fee']" ).val(delivery_fee.text());
		model_repos();
	});


	return false;
});

$(document).on('submit', ".edit_order_cost_form", function (e)
{
	$.ajax({
		type: "POST",
		url: "./controllers/manage.php",
		data: $(this).serialize() + "&action=manage_order_cost",
		success: function(data)
		{
			popup(data);
			model_destroy();
			$('.manage_order').load(location.href + " .manage_order","");
		}
	});
	return false;
});


$(document).on('click', ".assign_driver", function (e)
{
	order = $(this).closest('.manage_order');
	order_id = order.attr('id');
	driver_id = $(order).find('.select_driver option:selected').val();

	$.ajax({
		type: "POST",
		url: "controllers/manage.php",
		data: {"action": "assign_driver","order_id": order_id, "order_driver": driver_id},
		success: function(data)
		{
			popup(data);
			$('form#' + order_id + '.manage_order').load(location.href + " " + ('form#' + order_id + '.manage_order') + ">*","");
		}
	});
	return false;
});

$(document).on('click', ".unassign_driver", function (e)
{
	order = $(this).closest('.manage_order');
	order_id = order.attr('id');

	$.ajax({
		type: "POST",
		url: "controllers/manage.php",
		data: {"action": "unassign_driver","order_id": order_id},
		success: function(data)
		{
			popup(data);
			$('form#' + order_id + '.manage_order').load(location.href + " " + ('form#' + order_id + '.manage_order') + ">*","");
		}
	});
	return false;
});



$(document).on('click', ".collect_payment", function (e)
{
	var order = $(this).closest('.manage_order');
	var order_id = order.attr('id');

	$.ajax({
		type: "POST",
		url: "./controllers/manage.php",
		data:{
			"action": "collect_payment",
			"order_id": order_id
		},
		dataType: "json",
		success: function(data) 
		{
			if (data.form_check == 'error')
			{
				popup('ERRO:' + data.alert);
				$('textarea[name=' + data.error_source + ']').addClass('form_error');
			}
			else 
			{
				popup(data.alert);
				$('form#' + order_id + '.manage_order').load(location.href + " " + ('form#' + order_id + '.manage_order') + ">*","");
			}	
		},
		error: function (xhr, ajaxOptions, thrownError) 
		{
        	alert(xhr.status + thrownError);
      	}
	});

	return false;
});




$(document).on('click', "#promote_to_driver", function (e)
{
	var element = $(this);
	var user_id = $(this).attr('data-user_id');

	$.ajax({
		type: "POST",
		url: "./controllers/manage.php",
		data:{
			"action": "promote_to_driver",
			"user_id": user_id,
		},
		success: function(data)
		{
			popup(data);
			location.reload();
		}
	});

	return false;
});



$(document).on('click', "#remove_driver", function (e)
{
	var element = $(this);
	var user_id = $(this).attr('data-user_id');

	$.ajax({
		type: "POST",
		url: "./controllers/manage.php",
		data:{
			"action": "remove_driver",
			"user_id": user_id,
		},
		success: function(data)
		{
			popup(data);
			location.reload();
		}
	});

	return false;
});




$(document).on('submit', "#add_dashcash", function (e)
{
	form = $(this);
	console.log("swag");

	$.ajax({
		type: "POST",
		url: './controllers/manage.php',
		data: form.serialize() + "&action=add_dashcash",
		dataType: "json",
		success: function(data)
		{
			popup(data.alert);
			$('.section').load(location.href + " .section>*","");
		}
	});
	return false;
});




	$(document).on('click touchstart', '.order_text', function (e) 
	{
		e.preventDefault();
		e.stopPropagation();
		user = $(this).closest('.info').find('.customer_name').text();
		phone = $(this).data('phone');
		content = '<form id="send_user_text">Send a text to ' + user + ' (+' + phone + ')';
		content += '<input name="phone" type="hidden" value="' + phone + '"/>';
		content += '<textarea name="message" class="width_full block push_top" placeholder="Write your message..."></textarea>';
		content += '<button class="push_top" type="submit">Send Text</button></form>';
		model_create(content, false, 'align_center stick_top');
		return false;
	});

	$(document).on('submit', '#send_user_text', function(e) {
		e.preventDefault();
		form_data = $(this).serialize();
		$.ajax({
			type: "POST",
			url: './controllers/manage.php',
			data: form_data + "&action=send_user_text",
			dataType: "json",
			success: function(data)
			{
				popup(data.alert);
				model_destroy();
			},
			error: function (xhr, ajaxOptions, thrownError)
			{
				alert(xhr.responseText + thrownError);
			}
		});
	});

});