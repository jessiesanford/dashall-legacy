$(document).ready(function() {

	cashout = 0.00;
	driver = '';

	// flag zero'd out delivery fees for jan
	$('.payroll_row').each(function() {
		console.log($(this).attr('data'));
		if ($(this).attr('data') == 0.00)
		{
			$(this).addClass('payroll_row_flag');
		}
	});

	$(document).on('click', '#clear_payroll_selection', function() {
		$('.payroll_row_selected').removeClass('payroll_row_selected');
		calc_cashout();
	});


	$(document).on('click', '.payroll_row', function() {
		if ($(this).hasClass('payroll_row_flag'))
		{
			return;
		}
		else if (!$(this).hasClass('payroll_row_selected'))
		{
			$(this).addClass('payroll_row_selected');
			calc_cashout();
		}
		else if ($(this).hasClass('payroll_row_selected'))
		{
			$(this).removeClass('payroll_row_selected');
			calc_cashout();
		}
	});

	function calc_cashout() 
	{
		var amount = 0.00;

	 	$('.payroll_row_selected').each(function() {
	 		amount += parseFloat($(this).find('.payroll_amount').attr('data'));
	 	});

	 	$('#cashout_value').text(amount.toFixed(2));
		
	}

    var $sidebar   = $("#payroll_sidebar_content"), 
        $window    = $(window),
        offset     = $sidebar.offset(),
        topPadding = 40;

    $window.scroll(function() {
        if ($window.scrollTop() > offset.top) {
            $sidebar.stop().animate({
                marginTop: $window.scrollTop() - offset.top + topPadding
            }, 200);
        } else {
            $sidebar.stop().animate({
                marginTop: 0
            }, 200);
        }
    });

});