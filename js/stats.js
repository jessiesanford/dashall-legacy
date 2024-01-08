// jscript for stats.php

$(document).ready(function() {

$(document).on('submit', "#user_search", function (e)
{	
	var query = $(this).find('input.textbox').val();

	$.ajax({
		type: "POST",
		url: "controllers/misc.php",
		data:{
			"action": "user_search",
			"query": query
		},
		dataType: "json",
		success: function(data)
		{
			$('#user_table .rows').empty();
			//console.log(data.results);
			var results = JSON.parse(data.results);
			for(var k in results)
			{
				$('#user_table .rows').append('<div class="row row_collapse"><div class="cell wid_25">' + results[k].user_email + '</div><div class="cell wid_25"><a href="admin?module=customer&id=' + results[k].user_id + '">' + results[k].user_firstName + ' ' + results[k].user_lastName + '</a></div><div class="cell wid_25">' + results[k].user_phone + '</div><div class="cell resp_hide wid_25">' + results[k].user_date + '</div></div>');
			}
		}
	});

	return false;
});


});