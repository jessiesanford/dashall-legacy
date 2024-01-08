function popup(text)
{
	if ( $('.popup').is(':visible') )
	{
		$('.popup').hide();
	}
	$('.popup span').html(text);
	$('.popup').fadeIn(200);

	setTimeout(function(){
		$('.popup').fadeOut(200);
	}, 4000);
}

function model_create(content, frozen, classes)
{
	if ( $('#model').is(':visible') )
	{
		$('#model').removeClass('fade_in');
	}
	$('#model').html(content);
	$('#model').addClass(classes);
	$('#model').css("top", 0.5 * $(window).height() - ($('#model').height() / 2));
	$('#mask, #model').addClass('fade_in');
	if ( frozen == true)
	{
		$('#model').addClass('frozen');
	}
}

function model_destroy()
{
	$('#model, #mask').removeClass('fade_in');
}

function model_repos() 
{
	$('#model').css("top", 0.5 * $(window).height() - ($('#model').height() / 2));

}

function loadingStart()
{
	if (showLoad == true)
	{
		$('#loading').show();	
	}
	else {
		showLoad = true;
	}
}

function loadingEnd()
{
	$('#loading').fadeOut(200);	
}




// Stripe Publishable Key - TEST KEY
// Stripe.setPublishableKey('REDACTED');
Stripe.setPublishableKey('REDACTED');

// stripeReponseHandler - validates credit card info returns error(s) if found.
function stripeResponseHandler(status, response) 
{
    if (response.error) {
        // re-enable the submit button
        $('#order_pay_auth').find('input, button').removeAttr("disabled");
        // show the errors on the form
        popup(response.error.message);
    } 
    else 
    { 
		loadingStart();

        var form = $("#order_pay_auth");
        // token contains id, last4, and card type
        var token = response['id'];
        // insert the token into the form so it gets submitted to the server
        form.append("<input type='hidden' name='stripeToken' value='" + token + "' />");

		$.ajax({
			type: "POST",
			url: 'controllers/order.php',
			data: form.serialize() + "&action=order_pay_auth",
			dataType: "json",
			success: function(data)
			{
				if (data.form_check == 'error')
				{
					popup(data.alert);
					$('#order_pay_auth').find('input, button').removeAttr("disabled");
				}
				else 
				{
					popup(data.alert);
					$("#order_area_wrap").load(location.href + " #order_area_wrap>*","", function() {
						$('html, body').animate({
							scrollTop: $("#order_flow").offset().top
					    }, 500);
					});
				}  
			},
			error: function (xhr, ajaxOptions, thrownError) 
			{
				alert(xhr.status + thrownError);
			}
		})
		.always(function() {
			loadingEnd();
		});
    }
}



// stripeReponseHandler - validates credit card info returns error(s) if found.
function stripeResponseHandler_account(status, response) 
{
    if (response.error) {
        // re-enable the submit button
        $('#account_payment_setup').find('input, button').removeAttr("disabled");
        // show the errors on the form
        popup(response.error.message);
    } 
    else 
    { 
		loadingStart();

        var form = $("#account_payment_setup");
        // token contains id, last4, and card type
        var token = response['id'];
        // insert the token into the form so it gets submitted to the server
        form.append("<input type='hidden' name='stripeToken' value='" + token + "' />");

		$.ajax({
			type: "POST",
			url: 'controllers/user.php',
			data: form.serialize() + "&action=account_pay_auth",
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
				}  
			},
			error: function (xhr, ajaxOptions, thrownError) 
			{
				console.log(xhr);
			}
		})
		.always(function() {
			loadingEnd();
		});
    }
}



// JQUERY BEING
$(document).ready(function() {

showLoad = true;

// destroy model  if clicked outside of element
$(document).click(function(e) {
	if ($(e.target).closest('#model, .popup').length === 0) 
	{
		if (!$('#model').hasClass('frozen'))
		{
			model_destroy();
		}
		$('.popup').hide();
	}
});

// $.ajaxSetup({
//     'beforeSend' : function() {
// 		loadingStart();
//     },
// 'complete'   : function() {
// 		loadingEnd();
//     }
// });

$(document).ajaxStart(function() 
{
	loadingStart();
});
$(document).ajaxStop(function() 
{
	loadingEnd();
});

$(document).on('click', '.model_destroy_trigger', function() {
	model_destroy();
});



$(document).click(function(e) {
    if ( $(e.target).closest('#toggle_sidebar, #sidebar').length === 0 && $('body').hasClass('menu-visible')) {
	    slideMenu(e);
    }
});

(function($) {
 $('#toggle_sidebar').on('touchstart click', function(e) 
 {
  	e.preventDefault();
 
  	var $body = $('body');
  	var $page = $('#page');
  	var $menu = $('#sidebar');
 
    /* Cross browser support for CSS "transition end" event */
    var transitionEnd = 'transitionend webkitTransitionEnd otransitionend MSTransitionEnd';
 
  	/* When the toggle menu link is clicked, animation starts */
 	$body.addClass('animating');
 
	/***
	 * Determine the direction of the animation and
	 * add the correct direction class depending
	 * on whether the menu was already visible.
	*/
  	if ($body.hasClass('menu-visible')) 
  	{
		$body.addClass('right');
  	} 
  	else 
  	{
		$body.addClass('left');
  	}
  
  /***
   * When the animation (technically a CSS transition)
   * has finished, remove all animating classes and
   * either add or remove the "menu-visible" class 
   * depending whether it was visible or not previously.
   */
  	$page.on( transitionEnd, function() 
  	{
	   $body
	   .removeClass('animating left right')
	   .toggleClass('menu-visible');
	 
	   $page.off( transitionEnd );
  	});

 	});
})(jQuery);

function slideMenu(e) 
{
  	e.preventDefault();
 
  	var $body = $('body');
  	var $page = $('#page');
  	var $menu = $('#sidebar');
 
    /* Cross browser support for CSS "transition end" event */
    var transitionEnd = 'transitionend webkitTransitionEnd otransitionend MSTransitionEnd';
 
  	/* When the toggle menu link is clicked, animation starts */
 	$body.addClass('animating');
 
	/***
	 * Determine the direction of the animation and
	 * add the correct direction class depending
	 * on whether the menu was already visible.
	*/
  	if ($body.hasClass('menu-visible')) 
  	{
		$body.addClass('right');
  	} 
  
  /***
   * When the animation (technically a CSS transition)
   * has finished, remove all animating classes and
   * either add or remove the "menu-visible" class 
   * depending whether it was visible or not previously.
   */
  	$page.on( transitionEnd, function() {
	   $body
	   .removeClass('animating left right')
	   .toggleClass('menu-visible');
	 
	   $page.off( transitionEnd );
  	});
}




















$(document).on('submit', '#contact_form', function()
{
	form = $(this);

	$.ajax({
		type: "POST",
		url: "controllers/misc.php",
		data: form.serialize() + "&action=submit_contact",
		dataType: "json",
		success: function(data) 
		{
			$('#contact_form input[type=submit]').prop('disabled', true);
			if(data.form_check == 'error')
			{
				$('#contact_form input[type=submit]').prop('disabled', false);
				popup(data.alert);
			} 
			else 
			{	
				popup(data.alert);
				$('#contact_form')[0].reset();
				$(this).children('input[type=submit]').prop('disabled', true);
			}
		},
		error: function (xhr, ajaxOptions, thrownError) 
		{
        	popup(xhr.status + thrownError);
      	}
	});
	return false;

});

$(document).on('click', 'ul.tab_menu li', function(e) 
{
	$('ul.tab_menu li').removeClass('selected');
	$(this).addClass('selected');
	$('.tab_panel').removeClass('tab_current');
	$('#' + $(this).attr('data')).addClass('tab_current');
});

$('#login_form').submit(function(e)
{
	e.preventDefault();
	$.post('controllers/user.php', $('#login_form').serialize() + "&action=user_login",
	function(data)
	{
		if(data.form_check == 'error')
		{
			popup(data.alert);
		} 
		else 
		{	
	  		ga('send', 'event', 'functions', 'login', 'login', '1');
			window.location.href = document.referrer;
		}
	}, 'json');
	return false;
});




$(document).on('submit', '#forgot_password', function(e)
{
	e.preventDefault();
	$.post('controllers/user.php', $('#forgot_password').serialize() + '&action=user_forgot_password',
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
			window.location.href = 'user?action=login';
		}

	}, 'json');

	return false;
});



// registration scripts ---------------------------------------------

$('#user_phone').keydown(function(e) 
{
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
         // Allow: Ctrl+A
        (e.keyCode == 65 && e.ctrlKey === true) ||
         // Allow: Ctrl+C
        (e.keyCode == 67 && e.ctrlKey === true) ||
         // Allow: Ctrl+X
        (e.keyCode == 88 && e.ctrlKey === true) ||
         // Allow: home, end, left, right
        (e.keyCode >= 35 && e.keyCode <= 39)) 
    {
             // let it happen, don't do anything
             return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) 
    {
        e.preventDefault();
    }

  	var foo = $(this).val().split("-").join(""); // remove hyphens
	foo = foo.match(new RegExp('.{1,4}$|.{1,3}', 'g')).join("-");
  	$(this).val(foo);
});



$('#register_form').submit(function(e)
{
	e.preventDefault();


	$.post('controllers/user.php', $('#register_form').serialize() + "&action=user_register",
	function(data)
	{
		if (data.form_check == 'error')
		{
			popup(data.alert);
			$(data.error_source).addClass('form_error');
			console.log(data);
		} 
		else
		{	
	  		ga('send', 'event', 'functions', 'register', 'register', '1');
			$('.form_error').removeClass('form_error');
			$('#register_form')[0].reset();
			popup(data.alert);
			setTimeout(function() {
				window.location.href = 'user.php?action=verify';
			}, 1000);
		}

	}, 'json');
	return false;
});

$('#verif_form').submit(function(e)
{
	e.preventDefault();

	$.post('controllers/user.php', $('#verif_form').serialize() + "&action=user_verify",
	function(data)
	{
		if (data.form_check == 'error')
		{
			popup(data.alert);
			$(data.error_source).addClass('form_error');
		} 
		else
		{	
			$('.form_error').removeClass('form_error');
			$('#verif_form')[0].reset();
			popup(data.alert);
			window.location.href = '/';
		}

	}, 'json');
	return false;
});

$(document).on('click', '#resend_verif_code', function(e) 
{
	e.preventDefault();

	$.post('controllers/user.php', "action=user_resend_verif",
	function(data)
	{
		popup(data.alert);
	}, 'json');

	return false;
});

$(document).on('click', '.form_error', function(e){
	$(this).removeClass('form_error');
});
	
$(document).on('submit', 'form', function(e){
	$('form_error').removeClass('form_error');
});







$(document).on('click', "#user_logout", function (e)
{
	e.preventDefault();

	$.post('./controllers/user.php', "action=user_logout",
	function(data)
	{
		popup(data.alert);
		window.location.href = '/';

	}, 'json');

	return false;
});

$(document).on('click', ".button_model_destroy", function (e)
{
});



// driver app js submit
$(document).on('submit', '#driver_app', function(e)
{
	e.preventDefault();
	var form = $(this);
	var form_data = $(this).serialize();

	$.post('controllers/misc.php', form_data + '&action=submit_driver_app',
	function(data)
	{
		if (data.form_check == 'error')
		{
			$('input[name=' + data.error_source + ']').addClass('form_error');
			popup(data.alert);
		} 
		else
		{	
			form[0].reset();
			model_create(data.alert, false, "align_center");
		}

	}, 'json');

	return false;
});


$(document).on('click', '.confirm_action', function(e) 
{
	e.preventDefault();	

	content = '<div class="push_bottom_10">' + $(this).attr('data-desc') + '</div>' + 
			'<div class="push_top_20"><button class="' + $(this).attr('data-action') + '" data-value="' + $(this).attr('data-value') + '">' + $(this).attr('data-button') + '</button></div>';

	model_create(content, false, 'align_center');

	return false;
});

if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) 
{
	$("input.textbox, textarea").click(function() {
		element = $(this);
	    $('html, body').animate({
	        scrollTop: element.offset().top - 20
	    }, 400);
	});
}




});