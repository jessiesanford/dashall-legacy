// index page functions

$(document).ready(function() {

meal_prompt_read = false;

$(function() {
var availableTags = [
  "Mcdonald's",
  "Tim Horton's",
  "Wendy's",
  "KFC",
  "A&W",
  "Dairy Queen",
  "Dominos",
  "Subway",
  "Poyo Tacos",
  "Celtic Hearth",
  "The Big's Ultimate",
  "Jungle Jim's",
  "Jack Astor's",
  "Sushi Island",
  "Sun Sushi",
  "SushiNami Royale",
  "Starbucks",
  "Swiss Chalet",
  "Mr. Sub"
];
$( "#dashbox_location" ).autocomplete({
  source: availableTags
});
});

// order.php - submitting the order
$(document).on('submit', "#dashbox", function(e)
{
	e.preventDefault();
	ga('send', 'event', 'functions', 'init_order', 'init_order', '0');

	var content = "";

	if (/meal|Meal/.test($(this).find("#dashbox_desc").val()) && meal_prompt_read == false) 
	{
		content += "Hey! We noticed you specified a meal in your order description. Please specfiy any drink or additional details about the meal(s) if you haven't already!"
		content += '<br /><br /><a href="#" class="button model_destroy_trigger">Got It</a>'
		model_create(content, false, 'align_center');
		meal_prompt_read = true;
	}
	else 
	{
		var form = $(this);
		var content = '<div id="order_area"><div class="align_center"><h3>Disclaimer</h3> ';
		content += "Please ensure your explaination of the delivery request is as accurate and as detailed as possible. If it is not, please cancel your order and redo the request. We have the right to refuse any delivery. Delivery from more then one establishment/location will increase the delivery fee. We can only deliver objects that are legal under Canadian law and objects under 50 cm x 50 cm and under 10 kg. We are only able to deliver from stores and shops in St John’s that are open at the time of delivery. With submitting this order you agree to our terms and conditions."
		content += '<br /><br />Delivery Fee is $7 (Flat Fee)';
		content += '<br /><br /><a href="#" id="dashbox_button_confirm" class="button button_model_destroy">Got it</a>';
		content += '<br /><br /><a href="#" class="confirm_action" data-action="order_cancel" data-desc="Are you sure you want to cancel your order?" data-button="Confirm Cancellation">Cancel Order</a>';
		content += '</div></div>';

		$.ajax({
			type: "POST",
			url: 'controllers/order.php',
			data: form.serialize() + "&action=order_init",
			dataType: "json",
			success: function(data) 
			{
				if (data.form_check == 'error')
				{
					popup(data.alert);
					$('textarea[name=' + data.error_source + ']').addClass('form_error');
				}
				else 
				{
					//model_create(content);
					$("#dashbox_wrap").slideUp(200);
					$("#order_area_wrap").hide(0);
					$("#order_area_wrap").html(content);
					$("#order_area_wrap").fadeIn(200);	
					popup(data.alert);

					$('html, body').animate({
						scrollTop: $("#order_flow").offset().top
				    }, 500);
				}	
			},
			error: function (xhr, ajaxOptions, thrownError) 
			{
	        	alert(xhr.status + thrownError);
	        	console.warn(xhr.responseText)
	      	}
		});
		return false;
	}
});

// order.php - submitting the order
$(document).on('click', "#dashbox_button_confirm", function (e)
{
	e.preventDefault();

	form = $('#dashbox');

	$.ajax({
		type: "POST",
		url: 'controllers/order.php',
		data: form.serialize() + "&action=order_init_confirm",
		dataType: "json",
		success: function(data) 
		{
			if (data.form_check == 'error')
			{
				popup(data.alert);
			}
			else 
			{
				$("#order_flow").fadeOut(200);
				$("#order_flow").load(location.href + " #order_flow>*","");
				$("#order_flow").fadeIn(200);	
				model_destroy();

				$('html, body').animate({
					scrollTop: $("#order_flow").offset().top
			    }, 500);
			}	
		},
		error: function (xhr, ajaxOptions, thrownError) 
		{
			alert(xhr.status + thrownError);
			console.warn(xhr.responseText)
		}
	});
	return false;
});



// order.php - cancel the order
$(document).on('click', ".order_cancel", function (e)
{
	e.preventDefault();

	$.ajax({
		type: "POST",
		url: 'controllers/order.php',
		data:{action: "order_cancel"},
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
				$("#order_flow").hide(0);
				$("#order_flow").load(location.href + " #order_flow>*","", function(){
					$("#order_flow").fadeIn(200);
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
	});

	model_destroy();

	return false;
});



// order.php - submitting the order
$(document).on('submit', "#submit_address", function (e)
{
	e.preventDefault();

	var form_data = $(this).serialize();

	// DISABLE THE BUTTON SO PEOPLE STOP SPAMMING THE FRIGGIN THING
	$('#submit_address').children('input[type=submit]').prop('disabled', true);

	$.ajax({
		type: "POST",
		url: 'controllers/order.php',
		data: form_data + "&action=submit_address",
		dataType: "json",
		success: function(data)
		{
			if (data.form_check == 'error')
			{
				popup(data.alert);
				$('#submit_address').children('input[type=submit]').prop('disabled', false);
			}
			else 
			{
	  			ga('send', 'event', 'functions', 'submit_order', 'submit_order', '2');
				popup(data.alert);
				$("#order_area_wrap").hide(0);
				$("#order_area_wrap").load(location.href + " #order_area_wrap>*","", function(){
					$("#order_area_wrap").fadeIn(200);	
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
	});
	return false;
});



setInterval(function(){
	showLoad = false;
	$(".order_status_wrap").load(location.href + " .order_status_wrap>*","");
}, 7000);




// order_pay_auth - Runs user provided credit card information through stripeResponseHandler to check for validity.
$(document).on('submit', "#order_pay_auth", function (e)
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
	}, stripeResponseHandler);

	loadingEnd();
	return false; 
});

// order_pay_auth_logged - If we have a Stripe Customer ID for the account we process the pre-auth using this method.
$(document).on('submit', "#order_pay_auth_logged", function (e)
{
	e.preventDefault();
	loadingStart();

	$(this).children('input, button').attr("disabled", "disabled");
	$.ajax({
		type: "POST",
		url: 'controllers/order.php',
		data: {"action": "order_pay_auth_logged"},
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
				$("#order_area_wrap").load(location.href + " #order_area_wrap>*","", function() {
					$('html, body').animate({
						scrollTop: $("#order_flow").offset().top
				    }, 500);
				});
			}  
		}
	})
	.always(function() {
		loadingEnd();
	});

	return false; 
});

$(document).on('click', "#delete_credit_card", function (e)
{
	e.preventDefault();
	model_destroy();
	$.ajax({
		type: "POST",
		url: 'controllers/order.php',
		data: {"action": "delete_credit_card"},
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
				$("#order_area_wrap").load(location.href + " #order_area_wrap>*","", function() {
					$('html, body').animate({
						scrollTop: $("#order_flow").offset().top
				    }, 500);
				});
			}  
		}
	});
	return false;
});

// part of order promo
$(document).on('click', '.select_box', function() {
	$(this).closest('.select_area').find('.select_box.selected').removeClass('selected');
	$(this).addClass('selected');
});

// order.php - cancel the order
$(document).on('submit', "#order_promo", function (e)
{
	e.preventDefault();

	var promo_method = $(this).find('.select_box.selected').attr('id');
	var promo_data = $(this).find('.select_box.selected').find('#promo_data').val();

	$.ajax({
		type: "POST",
		url: 'controllers/order.php',
		data: {action: "add_promo", promo_method: promo_method, promo_data: promo_data},
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
				$("#order_flow").hide(0);
				$("#order_flow").load(location.href + " #order_flow>*","", function(){
					$("#order_flow").fadeIn(200);
					$('html, body').animate({
						scrollTop: $("#order_flow").offset().top
				    }, 500);
				});	
			} 
		}
	});

	return false;
});






// Starrr plugin (https://github.com/dobtco/starrr)
var __slice = [].slice;

(function($, window) {
    var Starrr;

    Starrr = (function() {
        Starrr.prototype.defaults = {
            rating: void 0,
            numStars: 5,
            change: function(e, value) {}
        };

        function Starrr($el, options) {
            var i, _, _ref,
                _this = this;

            this.options = $.extend({}, this.defaults, options);
            this.$el = $el;
            _ref = this.defaults;
            for (i in _ref) {
                _ = _ref[i];
                if (this.$el.data(i) != null) {
                    this.options[i] = this.$el.data(i);
                }
            }
            this.createStars();
            this.syncRating();
            this.$el.on('mouseover.starrr', 'i', function(e) {
                return _this.syncRating(_this.$el.find('i').index(e.currentTarget) + 1);
            });
            this.$el.on('mouseout.starrr', function() {
                return _this.syncRating();
            });
            this.$el.on('click.starrr', 'i', function(e) {
                return _this.setRating(_this.$el.find('i').index(e.currentTarget) + 1);
            });
            this.$el.on('starrr:change', this.options.change);
        }

        Starrr.prototype.createStars = function() {
            var _i, _ref, _results;

            _results = [];
            for (_i = 1, _ref = this.options.numStars; 1 <= _ref ? _i <= _ref : _i >= _ref; 1 <= _ref ? _i++ : _i--) {
                _results.push(this.$el.append("<i class='fa fa-2x fa-star-o'></i>"));
            }
            return _results;
        };

        Starrr.prototype.setRating = function(rating) {
            if (this.options.rating === rating) {
                rating = void 0;
            }
            this.options.rating = rating;
            this.syncRating();
            return this.$el.trigger('starrr:change', rating);
        };

        Starrr.prototype.syncRating = function(rating) {
            var i, _i, _j, _ref;

            rating || (rating = this.options.rating);
            if (rating) {
                for (i = _i = 0, _ref = rating - 1; 0 <= _ref ? _i <= _ref : _i >= _ref; i = 0 <= _ref ? ++_i : --_i) {
                    this.$el.find('i').eq(i).removeClass('fa-star-o').addClass('fa-star').closest('.starrr').attr('data-rating', rating);
                }
            }
            if (rating && rating < 5) {
                for (i = _j = rating; rating <= 4 ? _j <= 4 : _j >= 4; i = rating <= 4 ? ++_j : --_j) {
                    this.$el.find('i').eq(i).removeClass('fa-star').addClass('fa-star-o').closest('.starrr').attr('data-rating', rating);
                }
            }
            if (!rating) {
                return this.$el.find('i').removeClass('fa-star').addClass('fa-star-o').closest('.starrr').attr('data-rating', rating);
            }
        };

        return Starrr;

    })();
    return $.fn.extend({
        starrr: function() {
            var args, option;

            option = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
            return this.each(function() {
                var data;

                data = $(this).data('star-rating');
                if (!data) {
                    $(this).data('star-rating', (data = new Starrr($(this), option)));
                }
                if (typeof option === 'string') {
                    return data[option].apply(data, args);
                }
            });
        }
    });
})(window.jQuery, window);

$(function() {
    return $(".starrr").starrr();
});




// order_pay_auth_logged - If we have a Stripe Customer ID for the account we process the pre-auth using this method.
$(document).on('submit', "#order_feedback", function (e)
{
	e.preventDefault();

	var form = $(this); 
	var correctness_rating = $(this).find('#correctness_rating').attr('data-rating');
	var timing_rating = $(this).find('#timing_rating').attr('data-rating');
	var driver_rating = $(this).find('#driver_rating').attr('data-rating');

	$.ajax({
		type: "POST",
		url: 'controllers/order.php',
		data: form.serialize() + "&action=order_feedback&correctness_rating=" + correctness_rating + "&timing_rating=" + timing_rating + "&driver_rating=" + driver_rating,
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
				$("#order_flow").fadeOut(200);
				$("#order_flow").load(location.href + " #order_flow>*","", function() 
				{
					$("#order_flow").fadeIn(200);
				});

				$('html, body').animate({
					scrollTop: $("#order_flow").offset().top
				}, 500);
			}  
		}, error: function (xhr, ajaxOptions, thrownError) 
		{
			alert(xhr.status + thrownError);
			console.warn(xhr.responseText);
		}
	});

	return false; 
});




});