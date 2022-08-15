jQuery(document).ready(function(){    

   jQuery("#adult").TouchSpin({
          min: 0,
          max: 100,
      });

   jQuery("#children").TouchSpin({
          min: 0,
          max: 100,
      });

   

	//Search Country
    jQuery("#country_search").devbridgeAutocomplete({
      onSearchStart:function(){
        jQuery('#country').val('');
      },
      serviceUrl:jQuery('#country').attr('url_search_country'), //tell the script where to send requests
        type:'GET',
        //callback just to show it's working
        onSelect: function (suggestion) {
          //jQuery('#selection').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
          jQuery('#country').val(suggestion.data);
          jQuery('#city').val('');
          jQuery('#city_search').val('');
        },
      minChars:2,
      showNoSuggestionNotice: true,
      noSuggestionNotice: 'Sorry, no matching results',
    
    
    });

    jQuery("#products_search").devbridgeAutocomplete({
      onSearchStart:function(){
        jQuery('#product').val('');
      },
      serviceUrl:jQuery('#product').attr('url_search_product'), //tell the script where to send requests
        type:'GET',
        //callback just to show it's working
        onSelect: function (suggestion) {
          //jQuery('#selection').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
          jQuery('#product').val(suggestion.data);
        },
      minChars:2,
      showNoSuggestionNotice: true,
      noSuggestionNotice: 'Sorry, no matching results',
    
    
    });

    //Search City
    jQuery("#city_search").devbridgeAutocomplete({
      onSearchStart:function(params){
        jQuery('#city').val('');
      },
      serviceUrl:jQuery('#city').attr('url_search_city'), //tell the script where to 
      //params:{country:jQuery('#country').val()},
      params: {
                country: function(){
                  return jQuery("#country").val();
                }
              },
      type:'GET',
      //callback just to show it's working
      onSelect: function (suggestion) {
        //jQuery('#selection').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
        jQuery('#city').val(suggestion.data);

      },
      minChars:2,
      showNoSuggestionNotice: true,
      noSuggestionNotice: 'Sorry, no matching results',
    
    
    });

    /////////////////////// PRODUCT
    var active = true;
    

    jQuery('#modalProduct').modal({
      dismissible: true, // Modal can be dismissed by clicking outside of the modal
      opacity: .5, // Opacity of modal background
      inDuration: 300, // Transition in duration
      outDuration: 200, // Transition out duration
      startingTop: '4%', // Starting top style attribute
      endingTop: '10%', // Ending top style attribute
      ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
        if(jQuery('#search_result .product').length == 0){
          load_product();

        }
      },
      complete: function() { 
        restore_product(); 
      } // Callback for Modal close
    
    });

    var processing = false;
    function load_product(){
    	if(processing){
    		return false;
    	}
    	processing = true;
    	jQuery('#modalProduct').find('.preloader-wrapper').addClass('active').removeClass('hide');
        jQuery.ajax({
            url:jQuery('#search_result').attr('url_search_product'),
            dataType:'json',
            type:'POST',
            data:{_token:jQuery('input[name="_token"]').eq(0).val()},
            success:function(response){
              jQuery('#modalProduct').find('.preloader-wrapper').addClass('hide').removeClass('active');
              if(response.data.length > 0){
              	jQuery('#load_more_product').remove();
              }
              jQuery('#search_result').append(response.data);

              jQuery('.datepicker').unbind('datepicker');
              jQuery('.datepicker').each(function(d){

              	var now = new Date();
        				min_notice = parseInt(jQuery('#datepicker_'+d).parent().parent().parent().find('.range_schedule_select').find('option').eq(0).attr('min_notice'));
        				startDate = jQuery('#datepicker_'+d).parent().parent().parent().find('.range_schedule_select').find('option').eq(0).attr('start_date'); 
        				now.setDate(now.getDate() + min_notice);
      			    var day = ("0" + now.getDate()).slice(-2);
      			    var month = ("0" + (now.getMonth() + 1)).slice(-2);
      			    var today = (month) + "/" + (day) + '/' + now.getFullYear() ;
			    
      			    if(startDate > today){
      			    	today = startDate;
      			    }

                jQuery('#datepicker_'+d).datepicker({
                  format: 'mm/dd/yyyy',
                  todayHighlight: true,
                  autoclose: true,
                  startDate: today,
                  endDate: jQuery('#datepicker_'+d).parent().parent().parent().find('.range_schedule_select').find('option').eq(0).attr('end_date'),
                  daysOfWeekDisabled : jQuery('#datepicker_'+d).parent().parent().parent().find('.range_schedule_select').find('option').eq(0).attr('disable_day'),
                });


              });
              
              
              jQuery('.range_schedule_select').unbind('change');
              jQuery('.range_schedule_select').each(function(r){
                jQuery('.range_schedule_select').eq(r).change(function(){
                  start_date = jQuery('.range_schedule_select').eq(r).find('option:selected').attr('start_date');
                  end_date = jQuery('.range_schedule_select').eq(r).find('option:selected').attr('end_date');
                  disable_day = jQuery('.range_schedule_select').eq(r).find('option:selected').attr('disable_day');

                  jQuery('.range_schedule_select').eq(r).parent().parent().parent().find('.datepicker').datepicker('setStartDate',start_date);
                  jQuery('.range_schedule_select').eq(r).parent().parent().parent().find('.datepicker').datepicker('setEndDate', end_date);
                  jQuery('.range_schedule_select').eq(r).parent().parent().parent().find('.datepicker').datepicker('setDaysOfWeekDisabled',disable_day);

                });
              });
              

              
              jQuery('.datepicker').on('changeDate', function() {
                that = jQuery(this);
                seldate = that.datepicker('getFormattedDate');
                product = that.parent().parent().parent().parent().parent().find('.product_id').val();

                validate_selected_schedule(product,seldate,that.siblings('.schedule'));

              });
              
              

              jQuery(".adt").TouchSpin({
                  min: jQuery(this).attr('min_order'),
                  max: 100,
                  step: 1,
                  buttondown_class: 'btn blue',
                  buttonup_class: 'btn blue'
              });

              jQuery(".chd").TouchSpin({
                  min: 0,
                  max: 100,
                  step: 1,
                  buttondown_class: 'btn blue',
                  buttonup_class: 'btn blue'
              });

              jQuery(".inf").TouchSpin({
                  min: 0,
                  max: 100,
                  step: 1,
                  buttondown_class: 'btn blue',
                  buttonup_class: 'btn blue'
              });

              jQuery(".adt,.chd,.inf").unbind('change');
              jQuery(".adt,.chd,.inf").change(function(){
                that = jQuery(this);
                seldate = that.parent().parent().parent().parent().find('.datepicker').datepicker('getFormattedDate');
                product = that.parent().parent().parent().parent().parent().parent().find('.product_id').val();
                
                if(seldate.length > 0){
                  validate_selected_schedule(product,seldate,that.parent().parent().parent().parent().find('.schedule'));
                }
              });


              initproductdetail();
              processing = false;
            },
            error:function(m){
            	processing = false;
            }
        });
    }

    function validate_selected_schedule(product,schedule,el){
      jQuery('#toast-container .toast').remove();
      el.val('');
      adt = el.parent().parent().parent().find('.adt').val();
      chd = el.parent().parent().parent().find('.chd').length > 0 ? el.parent().parent().parent().find('.chd').val() : 0;
      inf = el.parent().parent().parent().find('.inf').length > 0 ? el.parent().parent().parent().find('.inf').val() : 0;

      dataSchedule = {_token:jQuery('input[name="_token"]').eq(0).val()
                      ,product:product,schedule:schedule
                      ,adult:adt
                      ,children:chd
                      ,infant:inf
                    }
      jQuery.ajax({
        url:jQuery('#search_result').attr('url_validate_schedule'),
        dataType:'json',
        type:'POST',
        data: dataSchedule,
        success:function(response){
          
          if(response.status == 200){
            el.val(seldate);
            el.parent().parent().parent().find('.schedule_amount').html('<b>'+response.data.total_amount_text+'</b>');
            return true;
          }
          else{
            el.parent().parent().parent().find('.schedule_amount').html('0');
            // Materialize.toast(response.message,4000,'red');
            return false;
          }
        },errors:function(e){
          el.parent().parent().parent().find('.schedule_amount').html('0');
          response = JSON.parse(e.responseText);
          // Materialize.toast(response.message,4000,'red');
          return false;
        }
      });
      
    }

    function initproductdetail(){
      
      jQuery('#modalProduct').find('.product').unbind('click');
      jQuery('#modalProduct').find('.product').each(function(i){

        jQuery('#modalProduct').find('.product').eq(i).click(function(e){
          jQuery('#modalProduct').find('.product').unbind('click');
          e.preventDefault();
          if(active){
            jQuery('#modalProduct').find(".modal-content").scrollTop(0);
            active = false;
            jQuery('#modalProduct').find('.search_header').fadeOut('fast',function(){ jQuery(this).hide() });
            jQuery('#modalProduct').find('.product').not(':eq('+i+')').addClass('animated zoomOut');
            jQuery('#modalProduct').find('.product').eq(i).css({position:'absolute',top:'5%'}).animate({left:0 },1000,'swing',function(){
              active = true;
              jQuery('#modalProduct').find('.product').not(':eq('+i+')').css({display:'none'});
              jQuery('#modalProduct').find('.product').eq(i).addClass('l12');
              jQuery('#modalProduct').find('.product').eq(i).find('.card').addClass('col l3');
              jQuery('#modalProduct').find('.product').eq(i).find('.product-detail').removeClass('hide');
              //jQuery('.modal-footer').find('.select_product_wrap').html('<button class="select_product btn blue col s2">Select</button>');
              init_select_product();
            });
          }
        });
      });

      jQuery('#modalProduct').find('.close-detail-product').unbind('click');
      jQuery('#modalProduct').find('.close-detail-product').click(function(e){
        e.stopPropagation();
        restore_product();
      });

    	jQuery('#load_more_product').unbind('click');
    	jQuery('#load_more_product').click(function(e){
    
		    // run a function called doSomething
		    load_product();
		    

		  });
      
    }

    function restore_product(){
      jQuery('.product .card').removeClass('col l3');
      jQuery('.product .product-detail').addClass('hide');
      jQuery('.product').removeAttr('style');
      jQuery('.search_header').fadeIn();
      jQuery('.product').removeClass('zoomOut l12');
      //jQuery('.search_header').addClass('animated zoomIn');
      jQuery('.product').addClass('animated zoomIn l3');
      initproductdetail();
    }

    function init_select_product(){
      jQuery('#modalProduct').find('.select_product').unbind('click');
      jQuery('#modalProduct').find('.select_product').each(function(s){
        jQuery('#modalProduct').find('.select_product').eq(s).click(function(){
          product_id = jQuery('#modalProduct').find('.product_id').eq(s).val();
          product_name = jQuery('#modalProduct').find('.product_name').eq(s).html();
          schedule = jQuery('#modalProduct').find('.product').eq(s).find('.schedule').val();
          adult = parseInt(jQuery('#modalProduct').find('.product').eq(s).find('.adt').val());
          
          child = 0;
          if(jQuery('#modalProduct').find('.product').eq(s).find('.chd').length){
            child = parseInt(jQuery('#modalProduct').find('.product').eq(s).find('.chd').val());

          }

          infant = 0;
          if(jQuery('#modalProduct').find('.product').eq(s).find('.inf').length){
            infant = parseInt(jQuery('#modalProduct').find('.product').eq(s).find('.inf').val());
            
          }

          if(product_id.length == 0){
            // Materialize.toast('Product is required',4000,'red');
            return false;
          }
          else if(schedule.length == 0){
            // Materialize.toast('schedule is required',4000,'red');
            return false;
          }
          else if(adult < 1){
            // Materialize.toast('Customer qty is not valid',4000,'red');
            return false;
          }


          val = '<input type="hidden" name="schedule" value="'+schedule+'"/>'+
                '<input type="hidden" name="product" value="'+product_id+'"/>'+
                '<input type="hidden" name="adult" value="'+adult+'"/>';
          
          chdinf = '';
          if(child > 0){
            val += '<input type="hidden" name="children" value="'+child+'"/>';
            chdinf += '<br>Children : '+child;
          }
          
          if(infant > 0){
            val += '<input type="hidden" name="infant" value="'+infant+'"/>';
            chdinf += '<br>Infant: '+infant+'</td>';
          }
                

          tr = '<tr class="animated zoomIn">'+
                '<td><img height="50" src="'+jQuery('.product_image').eq(s).attr('src')+'" /></td>'+
                '<td>'+val+product_name+'<br>'+schedule+'<br>Adult : '+adult+chdinf+
                '<td class="center-align">'+jQuery('.product_currency').eq(s).val()+'</td>'+
                '<td class="right-align product_price"></td>'+
                '<td class="right-align total_tax_amount">0</td>'+
                '<td class="right-align">'+jQuery('.product_fee').eq(s).val()+'</td>'+
                '<td class="right-align">'+jQuery('.product_discount').eq(s).val()+'</td>'+
                '<td class="right-align total_product_price"></td>'+
                '<td class="right-align"><button type="button" class="btn btn-floating danger remove_price"><i >clear</i></button></td></tr>';

          
          jQuery('#modalProduct').modal('close');
          jQuery('#list_selected_product').append(tr);
          jQuery('#row_product').find('tfoot').addClass('hide');
          jQuery('#row_extra').fadeIn('fast');
          init_remove_price();
          get_total_price();
        });  
      });
      
    }

    function init_remove_price(){
      jQuery('.remove_price').unbind('click');
      jQuery('.remove_price').click(function(){
        tr = jQuery(this).parent().parent();
        tr.addClass('zoomOut animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
          tr.remove();
          jQuery('#row_product').find('tfoot').removeClass('hide');
          jQuery('#row_extra').fadeOut('fast');
          jQuery('#list_selected_extras').html('');
          
          jQuery('#total_amount_bar').html('0');
        });
      });
    }

    init_remove_price();

    //Search Customer
    jQuery("#search_customer").devbridgeAutocomplete({
      onSearchStart:function(){
        jQuery('#customer').val('');
      },
      serviceUrl:jQuery('#customer').attr('url_search_customer'), //tell the script where to send requests
        type:'GET',
        //callback just to show it's working
        onSelect: function (suggestion) {
          //jQuery('#selection').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
          jQuery('#customer').val(suggestion.data);
          jQuery('#first_name').val(suggestion.first_name);
          jQuery('#last_name').val(suggestion.last_name);
          jQuery('#phone').val(suggestion.phone);
          jQuery('#city').val(suggestion.id_city);
          jQuery('#city_search').val(suggestion.city_name);
          jQuery('#country').val(suggestion.id_country);
          jQuery('#country_search').val(suggestion.country_name);
          jQuery('#address').val(suggestion.address);
          jQuery('#address').val(suggestion.address);

          // Materialize.updateTextFields();
          if(jQuery('input[name="product"]').length > 0 && jQuery('input[name="product"]').eq(0).val().length > 0){
            get_total_price();
            
          }
        },
      minChars:2,
      //showNoSuggestionNotice: true,
      noSuggestionNotice: 'Sorry, no matching results',
    
    
    });

    jQuery('#row_extra').hide();
    jQuery('#modalExtra').modal({
      dismissible: true, // Modal can be dismissed by clicking outside of the modal
      opacity: .5, // Opacity of modal background
      inDuration: 300, // Transition in duration
      outDuration: 200, // Transition out duration
      startingTop: '4%', // Starting top style attribute
      endingTop: '10%', // Ending top style attribute
      ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
        if(jQuery('#search_extra_result .selextra').length == 0){
          jQuery('#modalExtra').find('.preloader-wrapper').addClass('active').removeClass('hide');
          jQuery.ajax({
            url:jQuery('#search_extra_result').attr('url_search_extra'),
            dataType:'json',
            type:'POST',
            data:{_token:jQuery('input[name="_token"]').eq(0).val()},
            success:function(response){
              jQuery('#modalExtra').find('.preloader-wrapper').addClass('hide').removeClass('active');
              jQuery('#search_extra_result').html(response.data);
              init_select_extra();
            }
          });
        }
      },
      complete: function() { 
        restore_extra(); 
      } // Callback for Modal close
    });

    function restore_extra(){
      jQuery('modalExtra').find('.product .card').removeClass('col l3');
      jQuery('modalExtra').find('.product .product-detail').addClass('hide');
      jQuery('modalExtra').find('.product').removeAttr('style');
      jQuery('modalExtra').find('.product').removeClass('zoomOut l12');
      jQuery('modalExtra').find('.product').addClass('animated zoomIn l3');
    }

    function init_select_extra(){
      
      jQuery('#modalExtra').find('.selextra').unbind('change');
      jQuery('#modalExtra').find('.selextra').each(function(s){
        jQuery('#modalExtra').find('.selextra').eq(s).change(function(){
          
          id_extra = jQuery('#modalExtra').find('.selextra').eq(s).val();
          if(jQuery('#modalExtra').find('.selextra').eq(s).prop('checked')){
            tr = jQuery('#search_extra_result').find('tbody tr').eq(s).clone();
            tr.find('.selextra').parent().parent().remove();

            extra = jQuery('#modalExtra').find('.selextra').eq(s).prop('checked');
            extra_id = jQuery('#modalExtra').find('.extra_id').eq(s).val();
            extra_currency = jQuery('#modalExtra').find('.extra_currency').eq(s).html();
            extra_price_type = jQuery('#modalExtra').find('.extra_price_type').eq(s).val();
            extra_amount = jQuery('#modalExtra').find('.extra_amount').eq(s).val();
            extra_amount_text = jQuery('#modalExtra').find('.extra_amount_text').eq(s).html();



            val = '<input type="hidden" name="extra[]" value="'+extra_id+'" />'+
                  '<input type="hideden" class="extra_amount" value="'+extra_amount+'" />';
            //alert(extra_price_type);
            extra_html = '';
            if(extra_price_type == 1){
              extra_html = '<input type="text" value="1" name="extra_qty[]" class="browser-default center extra_qty" maxlength="4">';
            }
            else{
              extra_html = '<input type="text" value="1"  name="extra_qty[]" readonly class="browser-default center extra_qty" maxlength="4">';
            }
            

            td = '<td class="center">'+extra_html+'</td>'+
                  '<td class="right-align sel_extra_amount_text" >'+extra_amount_text+'</td>'+
                  '<td class="right-align">'+
                  '<button type="button" class="btn btn-floating danger remove_extra"><i>clear</i></button>'+
                  '</td>';
            tr.append(td);
            
            jQuery('#list_selected_extras').append(tr);
            init_change_qty_extra();
            get_total_price();

          }
          else{
            jQuery('#list_selected_extras').find('.extraval[value="'+id_extra+'"]').parent().parent().remove();
          }
          
          
          init_remove_extra();
        });  
      });
      
    }

    function init_remove_extra(){
      jQuery('.remove_extra').unbind('click');
      jQuery('.remove_extra').click(function(){
        tr = jQuery(this).parent().parent();
        jQuery('#modalExtra').find('.selextra[value="'+tr.find('.extraval').val()+'"]').prop('checked',false);
        tr.addClass('zoomOut animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
          
          tr.remove();
          get_total_price();
        });
      });
    }

    init_remove_extra();

    function init_change_qty_extra(){
      jQuery('.extra_qty').unbind('change');
      jQuery('.extra_qty').each(function(x){
        jQuery('.extra_qty').eq(x).change(function(){
          calculate(x);
          get_total_price();
        });
      });   
    }
 
    function calculate(x){
      total_extra = parseFloat(jQuery('.extra_amount').eq(x).val()) * parseFloat(jQuery('.extra_qty').eq(x).val());
      jQuery('.sel_extra_amount_text').eq(x).html(total_extra.toLocaleString());
    }

    function get_total_price(){
      jQuery('#toast-container .toast').remove();
      formData = new FormData(jQuery('#form_ajax')[0]);
      formData.delete('_method');
      jQuery.ajax({
            url: jQuery('#total_amount_column').attr('url'),
            type: "POST",
            //data: jQuery('.form_ajax').eq(i).serialize(),
            dataType :'json',
            data:formData,
            async: false,
            cache: false,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
          
            success: function (response) {
                if(response.status){
                  if(response.status == 200){
                    //callback(response);
                    jQuery('.product_price').html(response.data.amount_text);
                    jQuery('.total_product_price').html(response.data.total_product_price);
                    jQuery('.total_tax_amount').html(response.data.total_tax_amount_text);
                    jQuery('#total_amount_bar').html(response.data.total_amount_text);
                    total_voucher_amount = 0;
                    if(response.data.total_voucher_amount){
                      total_voucher_amount = response.data.total_voucher_amount.toLocaleString();
                    }
                    jQuery('#total_voucher_amount_bar').html(total_voucher_amount);
                 }
                 else{
                    // Materialize.toast(response.message,4000,'amber');
                 }
                }
                else{
                 alert('Invalid Reponse');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                //alert('jancl');
                //alert(jqXHR.message);
                response = JSON.parse(jqXHR.responseText);
                if(response.errors){
                  
                  jQuery.each(response.errors, function (key, value) {
                    // Materialize.toast(value,4000,'red');
                  });
                  
                }
                

            }


       });
    }

    jQuery('#voucher_code').change(function(){
      get_total_price();
    });

    jQuery('#status').change(function(){
      status = jQuery('#status').val();
      
      if(status == '99'){
        jQuery('#void_reason').slideDown('fast');
      }
      else{
        jQuery('#void_reason').hide();
      }
    });
});

