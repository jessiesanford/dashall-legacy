$(document).ready(function() {


$(document).on('click', '.confirm_shift', function(e) 
{
	e.preventDefault();	

	var start_datetime = new Date($(this).attr('data-start'));
	var end_datetime = new Date($(this).attr('data-end'));

	model_content = '<form id="confirm_shift_action"><div>' +
		'<input id="shift_start" name="shift_start" type="hidden" value="' + $(this).attr('data-start') + '">' +
		'<input id="shift_end" name="shift_end" type="hidden" value="' + $(this).attr('data-end') + '">' +
		'<i class="fa fa-3x fa-clock-o"></i></div>' +
		'<div class="push_bottom_20">Please confirm that you wish to take the following shift:</div>' + 
		'Start: <strong>' + moment($(this).attr('data-start')).format('ddd, MMM Do @ h:mm a') + '</strong><br>' +
		'End: <strong>' + moment($(this).attr('data-end')).format('ddd, MMM Do @ h:mm a') + '</strong>' +
		'<div class="push_top_20"><button type="submit">' + $(this).data('button') + '</button></div></form>';

	model_create(model_content, false, 'align_center');
	return false;
});



// order.php - submitting the order
$(document).on('submit', '#confirm_shift_action', function (e)
{
	e.preventDefault();
	console.log($(this).serialize());
	if ($(this).hasClass('shift_self'))
	{
		$(this).removeClass('shift_self');
		$(this).addClass('unassigned');
	}
	else if ($(this).hasClass('unassigned'))
	{
		$(this).removeClass('unassigned');
		$(this).addClass('shift_self');
	}

	$.ajax({
		type: "POST",
		url: 'controllers/driver.php',
		data: $(this).serialize() + "&action=take_shift",
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
				$("#shifts_table").load(location.href + " #shifts_table>*","");
				$("#calendar_weeks_view").load(location.href + " #calendar_weeks_view>*","");
			}	
		}
	});

	model_destroy();
	
	return false;
});




// order.php - submitting the order
$(document).on('click', '.remove_shift', function (e)
{
	e.preventDefault();

	var shift_id = $(this).closest('.driver_shift').attr('id');

	$.ajax({
		type: "POST",
		url: 'controllers/driver.php',
		data: "shift_id=" + shift_id + "&action=remove_shift",
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
				$("#shifts_table").load(location.href + " #shifts_table>*","");
				$("#calendar_weeks_view").load(location.href + " #calendar_weeks_view>*","");
			}	
		}
	});
	return false;
});

});