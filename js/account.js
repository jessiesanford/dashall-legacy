$(document).ready(function() 
{

	$(document).on('submit', '#update_settings', function(e)
	{
		e.preventDefault();

		$.post('controllers/user.php', $('#update_settings').serialize() + '&action=user_update_settings',
		function(data)
		{
			if (data.form_check == 'error')
			{
				console.log(data.error_source);
				popup(data.alert);
				$('input[name=' + data.error_source + ']').addClass('form_error');
			} 
			else
			{	
				popup(data.alert);
				window.location.href = 'account';
			}

		}, 'json');

		return false;
	});


	$(document).on('submit', '#change_email', function(e)
	{
		e.preventDefault();

		$.post('controllers/user.php', $('#change_email').serialize() + '&action=user_change_email',
		function(data)
		{
			if (data.form_check == 'error')
			{
				$('input[name=' + data.error_source + ']').addClass('form_error');
				popup(data.alert);
			} 
			else
			{	
				popup(data.alert);
				window.location.href = 'account';
			}

		}, 'json');

		return false;
	});


	$(document).on('submit', '#change_phone_number', function(e)
	{
		e.preventDefault();

		$.post('controllers/user.php', $('#change_phone_number').serialize() + '&action=user_change_phone_number',
		function(data)
		{
			if (data.form_check == 'error')
			{
				$('input[name=' + data.error_source + ']').addClass('form_error');
				popup(data.alert);
			} 
			else
			{	
				popup(data.alert);
				window.location.href = 'account';
			}

		}, 'json');

		return false;
	});


	$(document).on('submit', '#change_password', function(e)
	{
		e.preventDefault();

		$.post('controllers/user.php', $('#change_password').serialize() + '&action=user_change_password',
		function(data)
		{
			if (data.form_check == 'error')
			{
				console.log(data);
				$('input[name=' + data.error_source + ']').addClass('form_error');
				popup(data.alert);
			} 
			else
			{	
				popup(data.alert);
				$.post('controllers/user.php', 'action=user_logout',
				function(data)
				{
					window.location.href = '/';
				}, 'json');
		}

		}, 'json');

		return false;
	});


	$(document).on('submit', '#change_address', function(e)
	{
		e.preventDefault();

		$.post('controllers/user.php', $('#change_address').serialize() + '&action=user_change_address',
		function(data)
		{
			if (data.form_check == 'error')
			{
				$('input[name=' + data.error_source + ']').addClass('form_error');
				popup(data.alert);
			} 
			else
			{	
				popup(data.alert);
			}
		}, 'json');

		return false;
	});

	$(document).on('submit', '#remove_payment_method', function(e)
	{
		e.preventDefault();

		$.post('controllers/user.php', $('#remove_payment_method').serialize() + '&action=remove_payment_method',
		function(data)
		{
			if (data.form_check == 'error')
			{
				popup(data.alert);
			} 
			else
			{	
				popup(data.alert);
				window.location.href = 'account';
			}

		}, 'json');

		return false;
	});


	$(document).on('submit', '#driver_settings', function(e)
	{
		e.preventDefault();

		$.post('controllers/user.php', $('#driver_settings').serialize() + '&action=driver_settings',
		function(data)
		{
			if (data.form_check == 'error')
			{
				popup(data.alert);
			} 
			else
			{	
				popup(data.alert);
				window.location.href = 'account';
			}

		}, 'json');

		return false;
	});


	// order_pay_auth - Runs user provided credit card information through stripeResponseHandler to check for validity.
$(document).on('submit', "#account_payment_setup", function (e)
{
	loadingStart();
	e.preventDefault();

	$(this).find('input, button').attr("disabled", "disabled");

	Stripe.createToken(
	{
	    number: $('.card-number').val(),
	    cvc: $('.card-cvc').val(),
	    exp_month: $('.card-expiry-month').val(),
	    exp_year: $('.card-expiry-year').val()
	}, stripeResponseHandler_account);


	loadingEnd();
	return false; 
});

});