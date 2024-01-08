$(document).ready(function() {

	$(document).on('submit', '#mass_text_drivers', function(e) 
	{
		e.preventDefault();
		var form_data = $(this).serialize();
		console.log(form_data);

		$.ajax({
			type: "POST",
			url: 'controllers/admin.php',
			data: form_data + '&action=mass_text_drivers',
			dataType: "json",
			success: function(data) 
			{
				if (!data.error) {
					popup("Successfully Sent!");
				}
				console.log(data);
			},
			error: function (xhr, ajaxOptions, thrownError) 
			{
	        	popup(xhr.status + thrownError);
	        	console.warn(xhr.responseText);
	      	}
		});
		return false;
	});
});