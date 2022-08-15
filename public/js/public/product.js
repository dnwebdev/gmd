$(document).ready(function(){
	var now = new Date();
	min_notice = parseInt($('#min_notice').val());

	now.setDate(now.getDate() + min_notice);
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = (month) + "/" + (day) + '/' + now.getFullYear() ;

    startDate = $('#datepicker').parent().parent().parent().find('.range_schedule_select').find('option').eq(0).attr('start_date');
    if(startDate > today){
    	today = startDate;
    }

	$('#datepicker').datepicker({
      format: 'mm/dd/yyyy',
      todayHighlight: true,
      autoclose: true,
      //startDate: $('#datepicker').parent().parent().parent().find('.range_schedule_select').find('option').eq(0).attr('start_date'),
      startDate:today,
      endDate: $('#datepicker').parent().parent().parent().find('.range_schedule_select').find('option').eq(0).attr('end_date'),
      daysOfWeekDisabled : $('#datepicker').parent().parent().parent().find('.range_schedule_select').find('option').eq(0).attr('disable_day'),
    });

    $('.range_schedule_select').change(function(){
      start_date = $('.range_schedule_select').find('option:selected').attr('start_date');
      end_date = $('.range_schedule_select').find('option:selected').attr('end_date');
      disable_day = $('.range_schedule_select').find('option:selected').attr('disable_day');

      $('.datepicker').datepicker('setStartDate',start_date);
      $('.datepicker').datepicker('setEndDate', end_date);
      $('.datepicker').datepicker('setDaysOfWeekDisabled',disable_day);

    });


    $(".adt").TouchSpin({
      min: parseInt($('.adt').val()),
      max: 100,
      step: 1,
      buttondown_class: 'btn btn-success',
      buttonup_class: 'btn btn-success'
    });


    $(".chd").TouchSpin({
      min: 0,
      max: 100,
      step: 1,
      buttondown_class: 'btn btn-success',
      buttonup_class: 'btn btn-success'
    });

    $(".inf").TouchSpin({
      min: 0,
      max: 100,
      step: 1,
      buttondown_class: 'btn btn-success',
      buttonup_class: 'btn btn-success'
    });

    $('.img-thumb').click(function(){
        src = $(this).find('img').attr('actual-image');
        $('#img-main').fadeOut('fast',function(){
            $(this).attr('src',src);
            $(this).fadeIn();
        })
    });


	$('#datepicker').on('changeDate', function() {
		that = $(this);
		$('.warning').remove();

		validate_selected_schedule();

	});


	$(".adt,.chd,.inf").unbind('change');
	$(".adt,.chd,.inf").change(function(){
		that = $(this);
		seldate = $('#datepicker').datepicker('getFormattedDate');
		if(seldate.length > 0){
		  validate_selected_schedule();
		}
	});



	function validate_selected_schedule(){
		seldate = $('#datepicker').datepicker('getFormattedDate');
		product = $('#product').val();

		$('.schedule').val('');
		adt = $('.adt').val();
		chd = $('.chd').length > 0 ? $('.chd').val() : 0;
		inf = $('.inf').length > 0 ? $('.inf').val() : 0;

		dataSchedule = 	{_token:$('input[name="_token"]').eq(0).val()
			              ,product:product,schedule:seldate
			              ,adult:adt
			              ,children:chd
			              ,infant:inf
			            }

		$('#schedule_message').html('');
		$.ajax({
			url:$('#datepicker').attr('url'),
			dataType:'json',
			type:'POST',
			data: dataSchedule,
			success:function(response){

			  if(response.status == 200){
			    $('.schedule').val(seldate);
			    $('.schedule_amount').html('<b>'+response.data.total_amount_text+'</b>');
			    return true;
			  }
			  else{
			    $('.schedule_amount').html('0');
			    $('#schedule_message').addClass('text-danger').html(response.message);
			    //Materialize.toast(response.message,4000,'red');
			    return false;
			  }
			},errors:function(e){
			  $('.schedule_amount').html('0');
			  response = JSON.parse(e.responseText);
			  $('#schedule_message').addClass('danger').html(response.message);
			  //alert(response.message);
			  //Materialize.toast(response.message,4000,'red');
			  return false;
			}
		});

    }
});
