jQuery(document).ready(function () {
	var now = new Date();
	min_notice = parseInt(jQuery('#min_notice').val());

	now.setDate(now.getDate() + min_notice);
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var today = (month) + "/" + (day) + '/' + now.getFullYear();

	start_date = jQuery('#datepicker').parent().parent().parent().find('.range_schedule_select').find('option').eq(0).attr('start_date');
	end_date = jQuery('.range_schedule_select').find('option:selected').attr('end_date');

	// if (start_date > today) {
	// 	today = start_date;
	// }

	test_date_availability(today, end_date)

	jQuery('#datepicker').datepicker({
		format: 'mm/dd/yyyy',
		todayHighlight: true,
		autoclose: true,
		startDate: today,
		endDate: jQuery('#datepicker').parent().parent().parent().find('.range_schedule_select').find('option').eq(0).attr('end_date'),
		daysOfWeekDisabled: jQuery('#datepicker').parent().parent().parent().find('.range_schedule_select').find('option').eq(0).attr('disable_day'),
	});

	jQuery('.range_schedule_select').change(function () {
		now = new Date();
		min_notice = parseInt(jQuery('#min_notice').val());

		now.setDate(now.getDate() + min_notice);
		day = ("0" + now.getDate()).slice(-2);
		month = ("0" + (now.getMonth() + 1)).slice(-2);
		today = (month) + "/" + (day) + '/' + now.getFullYear();

		start_date = jQuery('.range_schedule_select').find('option:selected').attr('start_date');
		end_date = jQuery('.range_schedule_select').find('option:selected').attr('end_date');
		disable_day = jQuery('.range_schedule_select').find('option:selected').attr('disable_day');

		if (Date.parse(start_date) > Date.parse(today)) {
			today = start_date;
		}
		// console.log(today);

		jQuery('.datepicker').datepicker('setStartDate', today);
		jQuery('.datepicker').datepicker('setEndDate', end_date);
		jQuery('.datepicker').datepicker('setDaysOfWeekDisabled', disable_day);

		test_date_availability(today, end_date)
	});

	jQuery('.field input').change(function () {
		var empty = false;
		jQuery('.field input').each(function () {
			if (jQuery(this).val().length == 0) {
				empty = true;
			}
		});

		if (empty) {
			jQuery('#book-now').attr('disabled', 'disabled');
		} else {
			jQuery('#book-now').attr('disabled', false);
		}
	});

	jQuery("input[name='schedule']").change(function () {
		validate_selected_schedule();
	});

	jQuery("input[name='adult']").change(function () {
		validate_selected_schedule();
	});

	jQuery("input[name='children']").change(function () {
		validate_selected_schedule();
	});

	function test_date_availability(today, end_date) {
		if (Date.parse(get_current_date()) > Date.parse(end_date)) {
			jQuery('#info').hide();
			jQuery('#alert').html(jQuery('#alert').data('sorry'));
			jQuery('#alert').show();

			jQuery('#adult').attr('disabled', 'disabled');
			jQuery('#children').attr('disabled', 'disabled');
			// jQuery('#datepicker').attr('disabled', 'disabled');
            jQuery('#datepicker').val(today);
			jQuery('#book-now').attr('disabled', 'disabled');
		} else {
			jQuery('#info').hide();
			jQuery('#alert').hide();

			jQuery('#adult').removeAttr('disabled');
			jQuery('#children').removeAttr('disabled');
			// jQuery('#datepicker').removeAttr('disabled');
			jQuery('#book-now').removeAttr('disabled');

			jQuery('#datepicker').val(today);
			validate_selected_schedule();
		}
	}

	function get_current_date() {
		var now = new Date();

		now.setDate(now.getDate());
		day = ("0" + now.getDate()).slice(-2);
		month = ("0" + (now.getMonth() + 1)).slice(-2);
		today = (month) + "/" + (day) + '/' + now.getFullYear();
		return today;
	}

	function calculate_total_price() {
		var adult_total = 0;
		var child_total = 0;
		var total_price = 0;
		var general_price = jQuery('#general_price').text();

		if (!jQuery('#adult_price').length == 1) {
			adult_total = jQuery("input[name='adult']").val() * general_price;
		} else {
			adult_total = jQuery("input[name='adult']").val() * jQuery('#adult_price').text();
		}

		if (!jQuery('#children_price').length == 1) {
			child_total = jQuery("input[name='children']").val() * general_price;
		} else {
			child_total = jQuery("input[name='children']").val() * jQuery('#children_price').text();
		}

		total_price = adult_total + child_total;
		if (isNaN(total_price)){
			total_price = 0;
		}
		jQuery('#total_price').html(format_currency(total_price));
	}

	function format_currency(n) {
		return n.toFixed(0).replace(/./g, function (c, i, a) {
			return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
		});
	}

	function touch_spinner(stock) {
		jQuery("input[name='adult']").TouchSpin({
			min: jQuery('#adult').val(),
			max: stock ? stock : jQuery("#max_people").val()
		});

		if (jQuery('#child_available').val() == 0) {
			jQuery('#children').parent().find('.input-group-btn button').prop('disabled', true);
			jQuery('#children').attr('disabled', 'disabled');
			jQuery('#children').val(0);
		} else {
			jQuery("input[name='children']").TouchSpin({
				min: jQuery('#children').val(),
				max: stock ? stock : jQuery("#max_people").val()
			});
		}
	}

	function validate_selected_schedule() {
		// console.log(jQuery("input[name='schedule']").val());
		var adult_val = jQuery('#adult').val();
		var child_val = jQuery('#children').length > 0 ? jQuery('#children').val() : 0;
		var infant_val = jQuery('#infant').length > 0 ? jQuery('#infant').val() : 0;

		var dataSchedule = {
			_token: jQuery('input[name="_token"]').eq(0).val()
			, product: jQuery('#product').val()
			, schedule: jQuery("input[name='schedule']").val()
			, adult: adult_val
			, children: child_val
			, infant: infant_val
		}

		jQuery.ajax({
			url: jQuery('#url').val(),
			dataType: 'json',
			type: 'POST',
			data: dataSchedule,
			success: function (response) {
				if (response.status == 200) {
					let stock = response.stock ? response.stock : 'banyak'
					if (stock < 10) {
						jQuery('#info').html(jQuery('#info').data('info')+'&nbsp;' + stock + '&nbsp;'+ jQuery('#info').data('slot'));
						jQuery('#info').show();
					} 
					touch_spinner(response.stock);

					jQuery('#total_price').html(format_currency(response.data.total_amount));
					jQuery('#alert').hide();
					return true;
				}
				else {
					touch_spinner(jQuery("#max_people").val());
					calculate_total_price();
					if (response.message == 'Schedule is full') {
						jQuery('#info').hide();
						jQuery('#alert').html("Unfortunately, this activity is fully booked.");
						jQuery('#alert').show();
						jQuery('#datepicker').attr('disabled', 'disabled');
					} else {
						jQuery('#alert').html(response.message);
						jQuery('#alert').show();
					}
					jQuery('#book-now').attr('disabled', 'disabled');
					// jQuery("input[name='schedule']").val('');
					return false;
				}
			}, errors: function (e) {
				response = JSON.parse(e.responseText);
				jQuery('#book-now').attr('disabled', 'disabled');
				calculate_total_price();
				jQuery('#alert').html(response.message);
				jQuery('#alert').show();
				// jQuery("input[name='schedule']").val('');
				return false;
			}
		});
	}
});
