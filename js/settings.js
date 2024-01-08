// admin settings js

$(document).ready(function() {

	$(document).on('submit', "#update_settings", function (e)
	{
		var taking_orders_value = $('#taking_orders_select option:selected').val();
		form = $(this);

		$.ajax({
			type: "POST",
			url: "controllers/manage.php",
			data: form.serialize() + "&action=update_settings",
			dataType: "json",
			success: function(data)
			{
				popup(data.alert);
				$("#settings_section").load(location.href + " #settings_section>*","", function() {

				});	
			},
			error: function (xhr, ajaxOptions, thrownError) 
			{
	        	popup(xhr.status + thrownError);
	      	}
		});

		return false;
	});

});