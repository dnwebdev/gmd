// Start/Until auto set by Min/Max
window.$ = jQuery;
jQuery(document).on('change', '#max_people', function(){
    $(this).val(parseInt($(this).val()));
    var max_people = $(this).val();
    // Disable type "0" and "1"
    if (max_people == 0 || max_people == 1){
        $(this).val(2);
        max_people = 2;
    }
    jQuery('.price-until:last').val(max_people);
    priceList_validation();
    priceamount_validation()
    minMaxPeople_validation('#max_people');
})
jQuery(document).on('change', '#min_people', function(){
    $(this).val(parseInt($(this).val()));
    var min_people = $(this).val();

    // Disable type "0"
    if (min_people == 0){
        $(this).val(1);
        min_people = 1;
    }
    jQuery('.price-from:first').val(min_people);
    priceList_validation();
    priceamount_validation()
    minMaxPeople_validation('#min_people');
})

function minMaxPeople_validation(e) {
    var valid = true,
        el = $(e);
        max_people = $('#max_people'),
        min_people = $('#min_people'),
        text_max = max_people.attr('data-validation-max'),
        text_min = min_people.attr('data-validation-min'),
        max_error_loc = max_people.closest('.form-group').find('small.error'),
        min_error_loc = min_people.closest('.form-group').find('small.error');
    max_error_loc.text('');
    min_error_loc.text('');

    if (parseInt(max_people.val()) <= parseInt(min_people.val())) {
        if(el.prop('id') == 'max_people'){
            el.closest('.form-group').find('small.error').text(text_max);
            valid = false;
        } else if (el.prop('id') == 'min_people') {
            el.closest('.form-group').find('small.error').text(text_min);
            valid = false;
        } else {
            max_error_loc.text(text_max);
            min_error_loc.text(text_min);
            valid = false;
        }
    }
    if (max_people.val() == '') {
        max_error_loc.text(max_people.attr('data-validation'));
        valid = false;
    }
    if (min_people.val() == '') {
        min_error_loc.text(min_people.attr('data-validation'));
        valid = false;
    }
    return valid
}

// Start_from_now = Until_before+1
// jQuery(document).on('change', '.price-until', function(){
//     var index = $('.price-until').index(this);
//     var price_until_val = parseInt(document.getElementsByClassName('price-until')[index].value);
//     var pricelist_length = $('.pricelist').length;
//     if (index != pricelist_length-1){
//         var price_from = document.getElementsByClassName('price-from')[index+1];
//         price_from.value = price_until_val+1;
//         // Color red temporary for attention
//         price_from.classList.add('red-temp');
//         setTimeout(function(){
//             price_from.classList.remove('red-temp');
//         },500)
//     }
//     priceList_validation();
// })
// Min pax validation
jQuery(document).on('change', '.price-from', function(){
    // var min_people = $('#min_people').val();
    var index = $('.price-from').index(this);
    var price_from_val = parseInt(document.getElementsByClassName('price-from')[index].value);

    // // Disable type "0"
    // if (price_from_val == 0){
    //     document.getElementsByClassName('price-from')[index].value = 1;
    //     price_from_val = 1;
    // }

    // if(index == 0){
    //     if(price_from_val!=min_people){
    //         $(this).parent().find('#alert-price-from').text('The first start from value must be equal to the min price value');
    //     } else {
    //         $(this).parent().find('#alert-price-from').text('');
    //     }
    // }
    // else {
    if(index != 0){
        var price_until_before = document.getElementsByClassName('price-until')[index-1]
        price_until_before.value = price_from_val-1;
        // Color red temporary for attention
        price_until_before.classList.add('red-temp');
        setTimeout(function(){
            price_until_before.classList.remove('red-temp');
        },500)
    }
    priceList_validation();
});

function priceUntilClear() {
    $('.price-from').each(function(i){
        var price_from = $('.price-from');
        var price_until = $('.price-until');
        if (i != 0) {
            price_until.eq( i - 1 ).val(price_from.eq(i).val() - 1);
        }
    });
    priceList_validation();
}

// No empty val
$(document).on('change', '.priceamount', function(){
    priceamount_validation();
});
function priceamount_validation() {
    var valid = true;
    $('.priceamount').each(function(i, e){
        var el = $(e),
            warning_label = el.closest('.form-group').find('.warning_label'),
            price_amount_alert = el.closest('.form-group').find('small.error'),
            value = el.val(),
            index = $('.priceamount').index(this);
        warning_label.addClass('d-none');
        warning_label.removeClass('text-secondary');
        price_amount_alert.text('');
        price_amount_alert.removeClass('text-secondary');
        el.attr('data-error', 'false');
        if (value == '') {
            price_amount_alert.css('display', 'inline-block');
            price_amount_alert.text(price_amount_alert.attr('data-validation-no-zero'));
            warning_label.removeClass('d-none');
            el.attr('data-error', 'true');
            valid = false;
        } else if (value < 100) {
            price_amount_alert.css('display', 'inline-block');
            price_amount_alert.text(price_amount_alert.attr('data-min-100'));
            warning_label.removeClass('d-none');
            el.attr('data-error', 'true');
            valid = false;
        }

        separator();
        var valueInt = parseInt(el.val());
        if (index != 0) {
            if (valueInt >= $('.priceamount').eq(index - 1).val()) {
                price_amount_alert.css('display', 'inline-block');
                price_amount_alert.text(price_amount_alert.attr('data-ideal'));
                warning_label.removeClass('d-none');
                warning_label.addClass('text-secondary');
                price_amount_alert.addClass('text-secondary');
            }
        }
        unSeparator();

        if (index != 0) {
            if ($('.priceamount').eq(index - 1).attr('data-error') == 'true') {
                price_amount_alert.text(price_amount_alert.attr('data-error-above'));
                warning_label.removeClass('text-secondary');
                price_amount_alert.removeClass('text-secondary');
                el.attr('data-error', 'true');
            }
        }
    })
    return valid
}

function priceList_validation(){
    var valid = true;
    $('.pricelist').each(function(i, e){
        var price_from = $(this).find('.price-from');
        var warning_label = $(this).find('.warning_label');
        // var price_until_val = parseInt(price_until.val());
        var max_people = $('#max_people');
        var price_from_alert = price_from.closest('.pricelist').find('#alert-price-from');
        price_from_alert.text('');
        warning_label.addClass('d-none');
        // if(index > 0) {
        //     price_from.prop('readonly', false);
        // }

        var index = price_from.index('.price-from'),
            val_before = parseInt($('.price-from').eq(index - 1).val()),
            error_before = $('.price-from').eq(index - 1).next('.error').text();
        // var val_after = parseInt($('.price-from').eq(index + 1).val());

        if(index > 0) {
            price_from.prop('readonly', false);
            if (price_from.val() == ''){
                price_from_alert.text(price_from_alert.attr('data-no-empty'));
                price_from.prev('.warning_label').removeClass('d-none');
                valid = false;
            }
            // if ( val_after < parseInt(price_from.val()) ) {
            //     price_from_alert.text('Jumlah harus lebih kecil dari setelahnya');
            //     price_from.prev('.warning_label').removeClass('d-none');
            //     valid = false;
            // }
            if ( val_before >= parseInt(price_from.val()) ) {
                price_from_alert.text(price_from_alert.attr('data-more-than-before'));
                price_from.prev('.warning_label').removeClass('d-none');
                valid = false;
            }
            if(parseInt(price_from.val()) > parseInt(max_people.val())){
                price_from_alert.text(price_from_alert.attr('data-more-than-max'));
                warning_label.removeClass('d-none');
                max_people.addClass('red-temp');
                setTimeout(function(){
                    max_people.removeClass('red-temp');
                },500);
                valid = false;
            }
            if (error_before != '') {
                price_from_alert.text(price_from_alert.attr('data-error-above'));
                price_from.prev('.warning_label').removeClass('d-none');
                valid = false;
            } 
        }
    });
    return valid
}

$(document).on('keydown', '.price-from, .priceamount', function(){
    $(this).prev('.warning_label').addClass('d-none');
})

// Total people prevent char more than 3
jQuery(document).on('keydown', '.totalpeople, #min_people, #max_people', function(e){
    $(this).val(parseInt($(this).val()));
    var char_length = $(this).val().length;
    if (char_length > 4 && e.keyCode > 47 && e.keyCode < 58){
        e.preventDefault();
    }
})

// Hide error after keydown start
$(document).on('keydown', '.form-control', function(){
    $(this).closest('.form-group').find('.error').text('');
})
$(document).on('click', '.select2-container--focus', function(){
    $(this).closest('.form-group').find('.error').text('');
})


// Discount Amount
discountType();
function discountType(){
    var discount_type = $('#discount_amount_type').val(),
        label = $('.discount_amount_label');
    if(discount_type === '1'){
        label.text('%').show(100);
        label.next().prop('disabled',false);
    } else if(discount_type === '0') {
        label.text('IDR').show(100);
        label.next().prop('disabled',false);
    } else if(!discount_type){
        label.hide(100);
        label.next().prop('disabled',true);
    }
}
$(document).on('change', '#discount_amount_type', function(){
    $('#discount_amount').val('');
    discountType();
})
// Disable more than 100%
$(document).on('keydown', '#discount_amount', function(e){
    var discount_type = $('#discount_amount_type').val();
    if (discount_type == 1){
        var char_length = $(this).val().length;
        if (char_length > 2 && e.keyCode > 47 && e.keyCode < 58){
            e.preventDefault();
        }
    }
})
$(document).on('keyup', '#discount_amount', function(e){
    var discount_value = parseInt($(this).val()),
        discount_type = $('#discount_amount_type').val(),
        it = $(this);
    if (discount_type == 1){
        if (discount_value > 100){
            it.val(100);
            it.after('<label class="error">if the Discount Amount Type is a percentage, the value cannot be more than 100<label>');
            it.focusout(function(){
                $(this).next().remove();
            })
        }
    }
})
$(document).ready(function(){
    var discount_val = $('#discount_amount').val();
    if(discount_val == 0){
        $('#discount_amount').val('');
    }
})

// Capitalize
$(document).on('keyup', 'input[name="product_name"]', function(){
    $(this).val(toCapitalize($(this).val()))
})

function toCapitalize(str){
    return str.replace(/(^\w{1})|(\s{1}\w{1})/g, function(match){return match.toUpperCase()});
}

//Change Duration Rule
$(document).on('change', 'select[name="duration_type"]', function(){
    changeDurationRule($(this).val(), 1);
})
function changeDurationRule(e, status){
    var duration = $('input[name="duration"]');
    var duration_value = duration.val();
    if(e == 0){ // Hours
        if(status == 1 && duration_value > 24){
            duration.val(24);
        }
        duration.addClass('max-hours');
        duration.removeClass('max-3');
    } else { // Day
        duration.addClass('max-3');
        duration.removeClass('max-hours');
    }
}

// Max 24 Hours
$(document).on('keyup', '.max-hours', function(){
    var value = $(this).val();
    var error = $(this).parent().find('#max_hours');
    if (value > 24) {
        $(this).val(24);
        error.text(error.attr('data-translate')).css('display', 'block');
        setTimeout(function(){
            error.css('display', 'none');
        }, 3000)
    }
})

// On Ready!
jQuery(document).ready(function () {
    changeDurationRule($('select[name="duration_type"]').val());

    var day = jQuery('.box_day').length;
    jQuery('input[name="product_name"]').change(function () {
        str = jQuery(this).val().toLowerCase().trim();
        str = str.replace(/\W+/g, "-")
        jQuery('input[name="permalink"]').val(str);
    });

    init_pickadate();
    function init_pickadate() {
        jQuery('input.datedrop').dateDropper();
        jQuery('input.timepicker').timepicker();
    }

    // Price type val check II
    initPriceTypeVal();
    function initPriceTypeVal() {
        var price_label = $('.pricelist').find('.price-label');
        if($('.pricelist').length == 1){
            $('#fixed_price').prop('checked', true);
            $('.add_price').addClass('d-none');
            $('.price-type-d-none').addClass('d-none');
            price_label.text(price_label.attr('data-price-fix'));
            price_label.next('.dynamic_pricing').removeClass('d-none');
        } else {
            $('#price_tier').prop('checked', true);
            $('.add_price').removeClass('d-none');
            $('.price-type-d-none').removeClass('d-none');
            price_label.text(price_label.attr('data-price-group'));
            price_label.next('.dynamic_pricing').addClass('d-none');
        }
    }

    init_pricing();
    function init_pricing() {
        jQuery('.add_price').unbind('click');
        // jQuery('.pricelist').each(function (i) {
        jQuery('.add_price').on('click', function () {
            var max_people = jQuery(document).find('#max_people').val();
            newprice = jQuery('#pricing').clone();
            newprice.removeAttr('id');
            var jQuerydata = newprice.find('.priceamount');

            // Empty val if max_people == last_value
            var last_value = parseInt(jQuery(document).find('.price-until:last').val());
            if (max_people == last_value){
                jQuery(document).find('.price-until:last').val('');
            }

            jQuerydata.attr('id', 'price.' + (jQuery('.pricelist').length));
            // newprice.find('.display').prop('disabled', true).attr('disabled', false);

            newprice.find('.clone-display-control').addClass('d-md-none d-lg-block');
            newprice.find('.price-from').prop('readonly', false);

            newprice.find('.display').html('<label for="pricetype">'+jQuery('#pricing').data('pricetype')+'</label><input type="text" name="display_name[]" readonly value="'+jQuery('#pricing').find('.display_name').val()+'" class="form-control result-clone">');
            newprice.find('.priceamount').val('');
            newprice.find('.totalpeople').val('');
            // newprice.find('.new_price').val('');
            if (max_people != last_value){
                newprice.find('.price-from').val(last_value+1);
            }
            newprice.find('.price-until').val(max_people);
            newprice.find('.delete-container').append('<button type="button" class="btn-danger btn-sm btn-add remove_price mt-36 float-right float-xl-left"><i class="fa fa-trash"></i><span class="d-md-inline-block d-lg-none">'+jQuery('#pricing').data('delete')+'</span></button>');
            // newprice.find('.add_price').remove();
            newprice.find('#alert-price-until').text('');
            newprice.find('#alert-price-from').text('');
            jQuery('.box-clone').append(newprice);
            jQuery('.box-clone').find('.fa-info-circle').remove();
            init_pricing();
        });

        let result_clone = jQuery('.result-clone')
        result_clone.closest('.clone-display-control').addClass('d-md-none d-lg-block');
        result_clone.closest('.display').addClass('d-none');
        // });

        // Price type val check I
        $('.price-from:first').prop('readonly', true);
        $('input[name="price_type"]').on('click', function(){
            var price_label = $('.pricelist').find('.price-label');
            if ($(this).prop('id') == 'fixed_price') {
                $('.pricelist').not(':first').remove();
                $('.add_price').addClass('d-none');
                $('.price-until:first').val($('#max_people').val());
                $('.price-type-d-none').addClass('d-none');
                price_label.text(price_label.attr('data-price-fix'));
                price_label.next('.dynamic_pricing').removeClass('d-none');
            } else {
                $('.add_price').removeClass('d-none');
                $('.price-type-d-none').removeClass('d-none');
                price_label.text(price_label.attr('data-price-group'));
                price_label.next('.dynamic_pricing').addClass('d-none');
            }
        });

        // Before change
        dynamic_value();
        function dynamic_value(){
            $('.dynamic_pricing').html(function(){
                var dataSelected = $('#pricing option:selected').text();
                $('.dynamic_pricing').html(dataSelected.replace(/ *\([^)]*\) */g, ''));
                $('#dynamic_from').html(dataSelected + '&nbsp;');
                $('#dynamic_from').css('margin', '0 0 .5rem');
                $('#dynamic_until').html(dataSelected + '&nbsp;');
                $('#dynamic_until').css('margin]', '0 0 .5rem');
                $('.result-clone').val(dataSelected);
            });
        }

        jQuery(document).on('change','#pricing select',function(){
            dynamic_value();
        });

        jQuery('.remove_price').unbind('click');
        jQuery('.remove_price').each(function (j) {
            jQuery('.remove_price').eq(j).click(function () {
                jQuery('.remove_price').eq(j).parent().parent().fadeOut('fast', function () {
                    jQuery(this).remove();
                    init_pricing();
                    jQuery(document).find('.price-until:last').val(jQuery(document).find('#max_people').val());
                    priceUntilClear();
                });
            });
        });
    }

    init_custom_input();
    function init_custom_input() {
        jQuery('.add_custom_input').unbind('click');
        jQuery('.add_custom_input').on('click', function () {
            $('.add_custom_input').hide().removeClass('d-block');
            $('.remove_custom_input').show();
            let newCustomInput = jQuery('.custom-input-clone:first').clone(true);
            let lastIndex = parseInt(jQuery('.custom-input-clone:last').data('custom-index'));
            let newIndex = lastIndex + 1;

            // untuk menyamakan index
            newCustomInput.find('.custom-fill-type').prop('name', 'custom_fill_type['+newIndex+']');
            newCustomInput.find('.custom-type').prop('name', 'custom_type['+newIndex+']');
            newCustomInput.find('.custom-label').prop('name', 'custom_label['+newIndex+']');
            newCustomInput.find('.custom-description').prop('name', 'custom_description['+newIndex+']');
            newCustomInput.find('.custom-value-input').prop('name', 'custom_values['+newIndex+'][]');

            newCustomInput.find('.add_custom_input').show();
            newCustomInput.find('.custom-id').remove();
            newCustomInput.find('.remove_custom_input').hide();
            newCustomInput.find('.custom-values').hide();
            newCustomInput.find('.custom-values-clone:not(:first)').remove();
            newCustomInput.find('input[type=text]').val('');
            newCustomInput.data('custom-index', newIndex);
            newCustomInput.find('.custom-type').val('text');
            newCustomInput.find('.custom-desc').hide();
            newCustomInput.find('.custom-desc-text').show();
            newCustomInput.find('.remove_custom_input').removeAttr('data-id');
            newCustomInput.find('.tooltips').unbind();
            newCustomInput.find('.tooltips').removeClass('tooltips').removeClass('tooltipstered');
            newCustomInput.find('.fa-info-circle').remove();

            newCustomInput.insertAfter('.custom-input-clone:last');
        });

        jQuery('.remove_custom_input').unbind('click');
        jQuery('.remove_custom_input').on('click', function () {
            $(this).closest('.custom-input-clone').remove();
            let id = $(this).data('id');
            if (id !== undefined) {
                $('<input>').attr({
                    type: 'hidden',
                    name: 'custom_remove_id[]',
                    value: id
                }).appendTo('#form_ajax');
            }
        });

        jQuery('.custom-type').on('change', function () {
            let value = $(this).val();
            let p = $(this).parents('.custom-input-clone');
            p.find('.custom-desc').hide();
            p.find('.custom-desc-' + value).show();

            if (['checkbox', 'choices', 'dropdown'].indexOf(value) != -1) {
                p.find('.custom-values').show();
            } else {
                p.find('.custom-values').hide();
                p.find('.custom-values-clone:not(:first)').remove();
                p.find('.custom-value-input').val('');
            }
        });

        jQuery('.add-custom-values').on('click', function () {
            let p = $(this).closest('.row');
            let clone = p.find('.custom-values-clone:first').clone(true);
            clone.find('.custom-values-n').val(p.find('.custom-values-clone').length);
            clone.find('.custom-value-show-remove').removeClass('d-none').addClass('d-flex');
            clone.find('input').val('');
            clone.insertAfter(p.find('.custom-values-clone:last'));
        });

        jQuery('.remove-custom-values').on('click', function () {
            $(this).closest('.custom-values-clone').remove();
        });
    }

    // caseOne();
    // function caseOne(){
    //   jQuery('input[name^="sun"]').prop('checked', true);

    //   jQuery('input[name^="mon"]').prop('checked', true);

    //   jQuery('input[name^="tue"]').prop('checked', true);

    //   jQuery('input[name^="wed"]').prop('checked', true);

    //   jQuery('input[name^="thru"]').prop('checked', true);

    //   jQuery('input[name^="fri"]').prop('checked', true);

    //   jQuery('input[name^="sat"]').prop('checked', true);
    // }

    // Disable Itinerary Delete Button
    $(document).on('click', '#add_day, #remove-day', function(){
        disableDeleteButton();
    })
    disableDeleteButton();
    function disableDeleteButton(){
        if($('.box_day').length == 1){
            $('#remove-day').prop('disabled', true)
        } else {
            $('#remove-day').prop('disabled', false)
        }
    }

    init_itinerary();
    appendTinyMce();
    function appendTinyMce() {
        let dayItinerary = 1;
        let addDaysBtn = $('#add_day');
        let removeDaysBtn = $('#remove-day');
        let containerDay = $('.itinerary_box');
        // let tinyLength = $('.itinerary_box').length;
        let languageDay = containerDay.attr('data-translate');
        // addDaysBtn.on('click', function () {.
        //     dayItinerary += 1;
        //     console.log(dayItinerary);
        //     $('.clone-tiny-mce').clone()
        //     // let appendTinyMce = '<div class="itinerary_box box_day" id="itinerarylist">' +
        //     //     '                                    <div class="form-group box_itin" id="box_itin.0">' +
        //     //     '                                        <label id="day-0" class="itin_day">'+languageDay+' ' + ' '+dayItinerary+' </label>' +
        //     //     '                                        <textarea id="itinerary_0" class="form-control itin_input" rows="4"' +
        //     //     '                                                  name="itinerary[]" length="300"></textarea>' +
        //     //     '                                        <button type="button" class="btn-success btn-sm btn-add display-none"' +
        //     //     '                                                id="add_days"></button>' +
        //     //     '                                    </div>\n' +
        //     //     '                                </div>';
        //     // $('#container_day').append(appendTinyMce);
        //     // tinymce.init({
        //     //     selector: '.itin_input',
        //     //     menubar: false,
        //     //     content_style: "p {font-size: 1rem; }",
        //     //     plugins: ["paste", "charactercount"],
        //     //     paste_as_text: true,
        //     //     elementpath: false,
        //     //     max_chars: 1000, // max. allowed chars
        //     //     setup: function (ed) {
        //     //         var allowedKeys = [8, 37, 38, 39, 40, 46]; // backspace, delete and cursor keys
        //     //         ed.on('keydown', function (e) {
        //     //             if (allowedKeys.indexOf(e.keyCode) != -1) return true;
        //     //             if (tinymce_getContentLength() + 1 > this.settings.max_chars) {
        //     //                 e.preventDefault();
        //     //                 e.stopPropagation();
        //     //                 return false;
        //     //             }
        //     //             return true;
        //     //         });
        //     //         ed.on('keyup', function (e) {
        //     //             tinymce_updateCharCounter(this, tinymce_getContentLength());
        //     //         });
        //     //     },
        //     //     init_instance_callback: function () { // initialize counter div
        //     //         $('#' + this.id).prev().append('<div class="char_count" style="text-align:right"></div>');
        //     //         tinymce_updateCharCounter(this, tinymce_getContentLength());
        //     //     },
        //     //     paste_preprocess: function (plugin, args) {
        //     //         var editor = tinymce.get(tinymce.activeEditor.id);
        //     //         var len = editor.contentDocument.body.innerText.length;
        //     //         var OriginalString = args.content;
        //     //         var text = OriginalString.replace(/(<([^>]+)>)/ig,"");
        //     //         if (len + text.length > editor.settings.max_chars) {
        //     //             alert('Pasting this exceeds the maximum allowed number of ' + editor.settings.max_chars + ' characters.');
        //     //             args.content = text.slice(0, editor.settings.max_chars);
        //     //         } else {
        //     //             tinymce_updateCharCounter(editor, len + text.length);
        //     //         }
        //     //     }
        //     // });
        // });
        removeDaysBtn.on('click', function () {
            // dayItinerary -= 1;
            $('.remove-itinerary-box').last().remove();
        });
    }
    function init_itinerary() {
        jQuery('#add_day').unbind('click');
        jQuery('.itinerary_box').each(function (i) {
            jQuery('#add_day').eq(i).on('click', function () {
                tinymce.remove('.itinerary-input');
                newitin = jQuery('#itinerarylist').clone();
                newitin.addClass('remove-itinerary-box');
                newitin.removeAttr('id');
                var jQuerydata = newitin.find('.itinerary-input');
                var jQuerylabel = newitin.find('.itin_day');
                var jQuerybox = newitin.find('.box_itin');

                jQuerydata.attr('id', 'itinerary_' + (jQuery('.itinerary_box').length));
                jQuerylabel.attr('id', 'day-' + (jQuery('.itinerary_box').length));
                jQuerybox.attr('id', 'box_itin.' + (jQuery('.box_itin').length));
                newitin.find('#day-' + (jQuery('.itinerary_box').length)).val('');
                newitin.find('#day-' + (jQuery('.itinerary_box').length)).html(jQuery('#itinerarylist').data('translate')+'&nbsp' + (jQuery('.itinerary_box').length + 1));
                newitin.find('.itinerary-input'  ).val('');
                // newitin.find('#add_days').parent().append('<button type="button" class="btn-danger btn-sm btn-add remove_itinerary">'+jQuery('#itinerarylist').data('delete')+'</button>');
                jQuery('div.itinerary_box:last').eq(i).after(newitin);
                tinymce.remove('.itinerary-input');
                tinymce.init({
                    selector: '.itinerary-input',
                    menubar: false,
                    content_style: "p {font-size: 1rem; }",
                    plugins: ["paste", "charactercount"],
                    paste_as_text: true,
                    elementpath: false,
                    max_chars: 2000, // max. allowed chars
                    setup: function (ed) {
                        var allowedKeys = [8, 37, 38, 39, 40, 46]; // backspace, delete and cursor keys
                        ed.on('keydown', function (e) {
                            if (allowedKeys.indexOf(e.keyCode) != -1) return true;
                            if (tinymce_getContentLength() + 1 > this.settings.max_chars) {
                                e.preventDefault();
                                e.stopPropagation();
                                return false;
                            }
                            return true;
                        });
                        ed.on('keyup', function (e) {
                            tinymce_updateCharCounter(this, tinymce_getContentLength());
                        });
                    },
                    init_instance_callback: function () { // initialize counter div
                        $('#' + this.id).prev().append('<div class="char_count" style="text-align:right"></div>');
                        tinymce_updateCharCounter(this, tinymce_getContentLength());
                    },
                    paste_preprocess: function (plugin, args) {
                        var editor = tinymce.get(tinymce.activeEditor.id);
                        var len = editor.contentDocument.body.innerText.length;
                        var OriginalString = args.content;
                        var text = OriginalString.replace(/(<([^>]+)>)/ig,"");
                        if (len + text.length > editor.settings.max_chars) {
                            alert('Pasting this exceeds the maximum allowed number of ' + editor.settings.max_chars + ' characters.');
                            args.content = text.slice(0, editor.settings.max_chars);
                        } else {
                            tinymce_updateCharCounter(editor, len + text.length);
                        }
                    }
                });
                init_itinerary();
            });
        });

        jQuery('.remove_itinerary').unbind('click');
        jQuery('.remove_itinerary').each(function (j) {
            jQuery('.remove_itinerary').eq(j).click(function () {
                jQuery('.remove_itinerary').eq(j).parent().parent().fadeOut('fast', function () {
                    jQuery(this).remove();
                    init_itinerary();
                });
            });
        });


    }

    init_date();
    function init_date() {
        jQuery('.add_date').unbind('click');
        jQuery('.add_date').each(function (i) {
            jQuery('.add_date').eq(i).click(function () {

                newrow = jQuery('#dates').clone();
                newrow.removeAttr('id');
                // newrow.find('.add_date').parent().append('<button type="button" class="btn-danger btn-sm btn-add remove_date" style="margin-top:-10px;">'+jQuery('#dates').data('delete')+'</button>');
                newrow.find('.add_date').remove();
                newrow.find('.start_date').val('');
                newrow.find('.end_date').val('');

                jQuery('.listdate').eq(jQuery('.listdate').length - 1).after(newrow);
                newrow.find('.start_date').attr('id', 'start_date.' + (jQuery('.listdate').length - 1));
                newrow.find('.start_date').attr('name', 'start_date[' + (jQuery('.listdate').length - 1) + ']');

                newrow.find('.end_date').attr('id', 'end_date.' + (jQuery('.listdate').length - 1));
                newrow.find('.end_date').attr('name', 'end_date[' + (jQuery('.listdate').length - 1) + ']');

                newrow.find('.start_time').attr('id', 'start_time.' + (jQuery('.listdate').length - 1));
                newrow.find('.start_time').attr('name', 'start_time[' + (jQuery('.listdate').length - 1) + ']');

                newrow.find('.end_time').attr('id', 'end_time.' + (jQuery('.listdate').length - 1));
                newrow.find('.end_time').attr('name', 'end_time[' + (jQuery('.listdate').length - 1) + ']');

                newrow.find('.start_date picker__input').attr('id', 'start_date.' + (jQuery('.listdate').length - 1));
                newrow.find('.end_date picker__input').attr('id', 'end_date.' + (jQuery('.listdate').length - 1));

                newrow.find('input[name^="sun"]').attr('name', 'sun[' + (jQuery('.listdate').length - 1) + ']');
                newrow.find('input[name^="mon"]').attr('name', 'mon[' + (jQuery('.listdate').length - 1) + ']');
                newrow.find('input[name^="tue"]').attr('name', 'tue[' + (jQuery('.listdate').length - 1) + ']');
                newrow.find('input[name^="wed"]').attr('name', 'wed[' + (jQuery('.listdate').length - 1) + ']');
                newrow.find('input[name^="thu"]').attr('name', 'thu[' + (jQuery('.listdate').length - 1) + ']');
                newrow.find('input[name^="fri"]').attr('name', 'fri[' + (jQuery('.listdate').length - 1) + ']');
                newrow.find('input[name^="sat"]').attr('name', 'sat[' + (jQuery('.listdate').length - 1) + ']');

                newrow.find('input[name^="day_type"]').attr('name', 'day_type[' + (jQuery('.listdate').length - 1) + ']');
                // newrow.find('input[name^="opt2"]').attr('name', 'opt2[' + (jQuery('.listdate').length - 1) + ']');
                // newrow.find('input[name^="opt3"]').attr('name', 'opt3[' + (jQuery('.listdate').length - 1) + ']');
                // newrow.find('input[name^="from_calendar"]').attr('name', 'from_calendar[' + (jQuery('.listdate').length - 1) + ']');

                newrow.find('input[name^="sun"]').attr('id', 'sun.' + (jQuery('.listdate').length - 1));
                newrow.find('input[name^="mon"]').attr('id', 'mon.' + (jQuery('.listdate').length - 1));
                newrow.find('input[name^="tue"]').attr('id', 'tue.' + (jQuery('.listdate').length - 1));
                newrow.find('input[name^="wed"]').attr('id', 'wed.' + (jQuery('.listdate').length - 1));
                newrow.find('input[name^="thu"]').attr('id', 'thu.' + (jQuery('.listdate').length - 1));
                newrow.find('input[name^="fri"]').attr('id', 'fri.' + (jQuery('.listdate').length - 1));
                newrow.find('input[name^="sat"]').attr('id', 'sat.' + (jQuery('.listdate').length - 1));

                newrow.find('.everyday').attr('id', 'opt1.' + (jQuery('.listdate').length - 1));
                newrow.find('.opt2').attr('id', 'opt2.' + (jQuery('.listdate').length - 1));
                newrow.find('.opt3').attr('id', 'opt3.' + (jQuery('.listdate').length - 1));
                newrow.find('.pick_calendar').attr('id', 'pick_from_calendar.' + (jQuery('.listdate').length - 1));
                newrow.find('.from_calendar').attr('id', 'from_calendar.' + (jQuery('.listdate').length - 1));


                newrow.find('label[for^="sun"]').attr('for', 'sun.' + (jQuery('.listdate').length - 1));
                newrow.find('label[for^="mon"]').attr('for', 'mon.' + (jQuery('.listdate').length - 1));
                newrow.find('label[for^="tue"]').attr('for', 'tue.' + (jQuery('.listdate').length - 1));
                newrow.find('label[for^="wed"]').attr('for', 'wed.' + (jQuery('.listdate').length - 1));
                newrow.find('label[for^="thu"]').attr('for', 'thu.' + (jQuery('.listdate').length - 1));
                newrow.find('label[for^="fri"]').attr('for', 'fri.' + (jQuery('.listdate').length - 1));
                newrow.find('label[for^="sat"]').attr('for', 'sat.' + (jQuery('.listdate').length - 1));

                newrow.find('label[for^="opt1"]').attr('for', 'opt1.' + (jQuery('.listdate').length - 1));
                newrow.find('label[for^="opt2"]').attr('for', 'opt2.' + (jQuery('.listdate').length - 1));
                newrow.find('label[for^="opt3"]').attr('for', 'opt3.' + (jQuery('.listdate').length - 1));
                newrow.find('label[for^="from_calendar"]').attr('for', 'from_calendar.' + (jQuery('.listdate').length - 1));


                var radioButton= function(){
                    jQuery('input[name^="sun"]').prop('checked', true);

                    jQuery('input[name^="mon"]').prop('checked', true);

                    jQuery('input[name^="tue"]').prop('checked', true);

                    jQuery('input[name^="wed"]').prop('checked', true);

                    jQuery('input[name^="thru"]').prop('checked', true);

                    jQuery('input[name^="fri"]').prop('checked', true);

                    jQuery('input[name^="sat"]').prop('checked', true);


                }



                // jQuery('input[name^="day_type"]:checked');


                init_date();
                init_pickadate();
                // var list_length =jQuery('.listdate').length;
                // console.log(list_length);
            });
        });

        // jQuery('.remove_date').unbind('click');
        // jQuery('.remove_date').each(function (j) {
        //     jQuery('.remove_date').eq(j).click(function () {
        //         jQuery('.remove_date').eq(j).parent().parent().fadeOut('fast', function () {
        //             jQuery(this).remove();
        //
        //             init_date();
        //             init_pickadate();
        //         });
        //     });
        // });
    }

    init_tax();
    function init_tax() {
        jQuery('.add_tax').unbind('click');
        jQuery('.add_tax').each(function (i) {
            jQuery('.add_tax').eq(i).click(function () {
                newrow = jQuery('#taxes').clone();
                newrow.removeAttr('id');
                jQuery('#taxes').after(newrow);
                init_tax();
            });
        });

        jQuery('.remove_tax').unbind('click');
        jQuery('.remove_tax').each(function (j) {
            jQuery('.remove_tax').eq(j).click(function () {
                jQuery('.remove_tax').eq(j).parent().parent().fadeOut('fast', function () {
                    jQuery(this).remove();

                    init_tax();
                });
            });
        });
    }

    tinymce.init({
        selector: ".tinymce",
        theme: "modern",
        height: 300,
        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "save table contextmenu directionality emoticons template paste textcolor",
            "charactercount",
        ],
        content_style: "p {font-size: 1rem; }",
        plugins: "paste",
        paste_as_text: true,
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
        style_formats: [
            { title: 'Bold text', inline: 'b' },
            { title: 'Red text', inline: 'span', styles: { color: '#ff0000' } },
            { title: 'Red header', block: 'h1', styles: { color: '#ff0000' } },
            { title: 'Example 1', inline: 'span', classes: 'example1' },
            { title: 'Example 2', inline: 'span', classes: 'example2' },
            { title: 'Table styles' },
            { title: 'Table row 1', selector: 'tr', classes: 'tablerow1' }
        ],
        elementpath: false,
        setup: function (editor) {
            editor.on('change', function () {
                tinymce.triggerSave();
            });
        }
    });

    var multipage_test = RegExp('multipage', 'gi').test(window.location.search); // intro js
    var data;
    var preview_now = "";
    form_ajax(jQuery('#form_ajax'), function (e) {
        // var product_link = jQuery('#sku').val();
        // var gomodo_link = jQuery('#gomodo_domain').val();
        // var link = 'http://' + gomodo_link + '/product/detail/' + product_link;
        if (e['data'] != undefined && e['data']['preview'] != undefined) {
            preview_now = "! <a target='_blank' href='" + e.data.preview + "'>" + $('#preview_now_trans').attr('data-translate') + "</a>";
        }
        // if (product_link) {
        //     preview_now = "! <a target='_blank' href='" + link + "'>" + $('#preview_now_trans').attr('data-translate') + "</a>"; 
        // }
        if (e.status == 200) {
            toastr.remove() // for show modal
            if (jQuery('#ota-modal').length != 0) {
                jQuery('#btn-show-ota').click();
                data = e;
            } else {
                swal({
                    title: e.success,
                    html: e.message + preview_now,
                    type: "success",
                }).then(function () {
                    if(multipage_test){
                        window.location.href = e.data.url + '?multipage=true' // intro js
                    } else {
                        window.location.href = e.data.url
                    }
                });
            }
        } else {
            toastr.remove() // for show modal
            swal({
                title: e.oops,
                text: e.message,
                type: "error",
            }).then(function () {
            });
        }
    });

    jQuery('.city_autocomplete').autocomplete({
        data: {
            "Apple": null,
            "Anyong": null,
            "Aseo": null,
            "Auto": null,
            "Microsoft": null,
            "Google": 'https://placehold.it/250x250'
        },
        limit: 20, // The max amount of results that can be shown at once. Default: Infinity.
        onAutocomplete: function (val) {
            // Callback function when value is autcompleted.
        },
        minLength: 1, // The minimum length of the input for the autocomplete to start. Default: 1.
    });

    jQuery('#ota-modal #btn-submit').click(function() {
        $.ajax({
          type: 'POST',
          url: $('#btn-show-ota').data('url'),
          data: {
            id: data.data.id,
            ota: $('.ota-value:checked').map(function() {
              return $(this).val();
            }).get(),
            '_token': '{{csrf_token()}}'
          },
          success: function () {
            $('#ota-modal').hide();
            successSwal();
          },
          error: function (e) {
            console.log(e);
          }
        })
    });

    $('#ota-modal').on('hidden.bs.modal', function () {
        successSwal();
    })

    function successSwal() {
        swal({
            title: data.success,
            html: data.message + preview_now,
            type: "success",
        }).then(function () {
            if(multipage_test){
                window.location.href = data.data.url + '?multipage=true' // intro js
            } else {
                window.location.href = data.data.url
            }
        });
    }

    function quotaType(val) {
        let quotaType = $('#quota_type');
        if (val === '0') {
            quotaType.attr('disabled', true);
        } else {
            quotaType.removeAttr('disabled')
        }

    }
    quotaType($('#availability').val());
    jQuery(document).on('change', '#availability', function(){
        quotaType($(this).val());
    })

});
