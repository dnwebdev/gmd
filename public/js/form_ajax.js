var submit = false;

function form_ajax(el, callback) {
    if (submit === false) {
        el.submit(function (e) {
            if (submit === true) {
                return false;
            }
            loadingStart();
            e.preventDefault();
            separator();
            // introJs().exit();
            formData = new FormData(jQuery(this)[0]);
            jQuery('label.error').remove();
            var method = jQuery("#_method").val();
            if (method == 'PUT') {
                jQuery('.remove_image').each(function (key, value) {
                    formData.append('remove_image[]', jQuery(this).val());
                });
            }
            //    if (typeof imageSave !== 'undefined') {
            // 	   $.each(imageSave, function( key, value ) {
            // 		   let s = key.split('-', 2);
            // 		   let name = s[0];
            // 		   if (s.length > 1) {
            // 			   name = name + '[]';
            // 		   }
            // 		   formData.append(name, value);
            // 	   });
            // 	}
            submit = true;
            setTimeout(function () {
                jQuery.ajax({
                    url: el.attr('action'),
                    type: "POST",
                    dataType: 'json',
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    enctype: 'multipart/form-data',
                    processData: false,
                    timeout: 0,
                    success: function (response) {
                        submit = false;
                        if (response.status) {
                            if (response.status == 200) {
                                loadingFinish();
                                toastr.remove();
                                toastr.success(response.message, 'Success!')
                                callback(response);
                            } else {
                                loadingFinish();
                                toastr.remove();
                                toastr.warning(response.message, errorMessage)
                                callback(response)
                            }
                        } else {
                            callback(response)
                        }

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        loadingFinish();
                        if (typeof jqXHR.responseJSON !== 'undefined' || jqXHR.responseJSON !== null) {
                            toastr.error(jqXHR.responseJSON.message, errorMessage)
                        }
                        // Intro JS start
                        // var multipage_test = RegExp('multipage', 'gi').test(window.location.search);
                        // if (RegExp('create', 'gi').test(window.location.pathname) && multipage_test) {
                        //     StartCreateIntro();
                        // } else if (RegExp('profile', 'gi').test(window.location.pathname) && multipage_test) {
                        //     StartSettingIntro();
                        // }

                        submit = false;
                        jQuery('#btn-submit').html(jQuery('#btn-submit').data('submit'));
                        jQuery("#btn-submit").attr('class', 'btn '+ (is_klhk ? 'bg-green-klhk' : 'btn-primary') + ' btn-cta step4 step2');
                        jQuery('#alert').hide();
                        if (textStatus == 'timeout' || jqXHR.status == 0) {
                            loadingFinish();
                            return swalInit({
                                title: $('#no_connection_title').attr('data-translate'),
                                type: 'warning',
                                text: $('#no_connection_desc').attr('data-translate'),
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: $('#try_again').attr('data-translate'),
                            }).then(function (result) {
                                if (result.value) {
                                    el.submit();
                                }
                            })
                        }
                        const error = jqXHR.responseJSON.errors;
                        let index = 0;
                        jQuery.each(error, function (key, value) {
                            if (index == 0) {
                                jQuery("#alert").attr('class', 'alert alert-danger');
                                jQuery('#alert').html(value[0]);
                                jQuery('#alert').show();
                            } else {
                                jQuery("#alert").attr('class', 'alert alert-danger');
                                jQuery('#alert').html(value[0]);
                                jQuery('#alert').show();
                            }
                            index++;
                        });

                       response = JSON.parse(jqXHR.responseText);
					   if (response.errors) {
						   callback(response)
						   jQuery('.rubberBand').each(function () {
							   jQuery(this).hide();
						   });
                           var errors = '';
                           let row = [];
						   jQuery.each(response.errors, function (key, value) {
                                if (key == "minimum_notice") {
                                    jQuery('input[name="minimum_notice"]').parent().append('<div class="animated rubberBand error right-align text-danger text-mute">' + value + '</div>');
                                } else {
                                    var inputVal = 'input[id="' + key + '"], input[name="' + key + '"] , select[name="' + key + '"] , textarea[name="' + key + '"]'
                                    if (el.find(inputVal).parent().hasClass('dropify-wrapper')){ //Add "if" because it for dropify in dom manipulation (company/profile page)
                                        el.find(inputVal).closest('.bank-document-container').append('<label class="error">' + value + '</label>');
                                    }
                                    else{
                                        if(el.find(inputVal).closest('.form-group').find('small.error').text() != '') {
                                            el.find(inputVal).closest('.form-group').find('small.error').remove();
                                            el.find(inputVal).parent().append('<label class="error">' + value + '</label>');	
                                        } else {
                                            el.find(inputVal).parent().append('<label class="error">' + value + '</label>');
                                        }
                                    }
                                }
                                let a = key.split('.');
                                if (a.length === 3) {
                                	if (!row.includes(a[0]+a[1])){
                                		row.push(a[0]+a[1]);
										errors = errors + value[0] + '<br>'
										jQuery('input[name="'+a[0]+'['+a[1]+'][]"]').closest('.col-12').find('label.error').remove();
										jQuery('input[name="'+a[0]+'['+a[1]+'][]"]').closest('.col-12').append('<label class="error">' + value + '</label>');
									}

                                } else {
                                    if (a.length !== 3) {
                                        errors = errors + value + '<br>'
                                    }

                                }

                            });
                            swalInit({
                                title: 'Error',
                                type: 'warning',
                                html: errors
                            })
                        }
                        unSeparator();
                    }
                });
            }, 500);
        });

        jQuery('#preview').click(function () {
            var product_link = jQuery('#sku').val();
            var gomodo_link = jQuery('#gomodo_domain').val();
            // var string_link =  JSON.parse(product_link);
            window.open('http://' + gomodo_link + '/product/detail/' + product_link, '_blank');
        });
    }
}

function loadingStart() {
    jQuery('.loading').addClass('show');
    $('button').attr('disabled', true);
    $('button').prop('disabled', true);
}

function loadingFinish() {
    jQuery('.loading').removeClass('show');
    $('button').attr('disabled', false);
    $('button').prop('disabled', false);
}

// function StartSettingIntro() {
//     $('.widget-collapse').each(function () {
//         $(this).next().addClass('show');
//     });
//     setTimeout(function () {
//         var intro = introJs();
//         intro.setOption('doneLabel', $('#intro_create_translate').attr('data-translate')).setOption('keyboardNavigation', false).setOptions({
//             steps: [
//                 {
//                     element: document.querySelector('.step1'),
//                     intro: "<p style='min-width: 600px;'>" + $('#intro_setting_translate1').attr('data-translate') + "</p>",
//                     position: 'left'
//                 }
//                 ,
//                 {
//                     element: document.querySelector('.step2'),
//                     intro: $('#intro_setting_translate2').attr('data-translate') + " <br> <p style='font-size: .8rem'>" + $('#intro_setting_translate3').attr('data-translate') + "</p>",
//                     position: 'right'
//                 }
//             ]
//         }).start().oncomplete(function () {
//             window.location.href = 'product/create?multipage=true';
//         });
//     }, 1000)
// }

// function StartCreateIntro() {
//     setTimeout(function () {
//         introJs().setOption('doneLabel', 'Skip').setOption('keyboardNavigation', false).setOptions({
//             steps: [
//                 {
//                     element: document.querySelector('.step3'),
//                     intro: "<p style='min-width: 600px;'>" + $('#intro_create_translate1').attr('data-translate') + "</p>",
//                     position: 'top'
//                 }
//                 ,
//                 {
//                     element: document.querySelector('.step4'),
//                     intro: $('#intro_create_translate2').attr('data-translate') + "<br> <p style='font-size: .8rem'>" + $('#intro_create_translate3').attr('data-translate') + "</p>",
//                 }
//             ]
//         }).start();
//     }, 1000)
// }
