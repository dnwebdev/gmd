<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gomodo | {{ trans('landing.register.forgot_password') }}</title>
    <link rel="stylesheet" href="landing/bootstrap/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="landing/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="landing/fonts/ionicons.min.css">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i">
    <link rel="stylesheet" href="landing/css/Article-List.css">
    <link rel="stylesheet" href="landing/css/Brands.css">
    <link rel="stylesheet" href="landing/css/Contact-Form-Clean.css">
    <link rel="stylesheet" href="landing/css/Features-Boxed.css">
    <link rel="stylesheet" href="landing/css/Footer-Dark.css">
    <link rel="stylesheet" href="landing/css/Header-Dark.css">
    <link rel="stylesheet" href="landing/css/Highlight-Blue.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css">
    <link rel="stylesheet" href="landing/css/Lightbox-Gallery-1.css">
    <link rel="stylesheet" href="landing/css/Lightbox-Gallery.css">
    <link rel="stylesheet" href="landing/css/styles.css">
    <link rel="stylesheet" href="landing/css/Testimonials.css">
    <link rel="stylesheet" href="landing/css/register.css">
    <link href="landing/css/smart_wizard.css" rel="stylesheet" type="text/css"/>
    <link rel="shortcut icon" href="landing/img/favicon.png" type="image/x-icon">
    <link rel="icon" href="landing/img/favicon.png" type="image/x-icon">
{{--    CUSTOM--}}
    <link rel="stylesheet" href="{{ asset('css/inline-fix.css') }}">
    <style>
        .toggle-password{
            cursor: pointer;
        }
        /* Disable spin button */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance:textfield;
        }
        .loading .loading-content{
            margin-top: 50vh!important;
        }
    </style>
</head>
<body>
{{-- Loading --}}
<div class="loading">
    <div class="loading-content">
        <div class="spin">
            <i class="fa fa-circle-o-notch fa-spin"></i>
        </div>
        <div class="loading-text">
            {{ trans("general.loading") }}
        </div>
    </div>
</div>
<div class="registration-form container-fluid d-flex justify-content-center">
    <div class="contact-clean box">
        <form id="regForm" autocomplete="off">
            <div class="form-group">
                <h2 class="text-center">
                    {{ trans('landing.register.new_password') }}
                </h2>
                <br>
                <div class="form-group">
                    <input class="form-control" type="password" name="password" autocomplete="off" required>
                    <label class="form-control-placeholder" for="password">Password</label>
                </div>
                <label for="password" class="error" id="password-length" data-validation="{{ trans('landing.register.password_min_length') }}"></label>
                <div class="form-group">
                    <input class="form-control" type="password" name="repassword" autocomplete="off" required>
                    <label class="form-control-placeholder" for="repassword">{{ trans('landing.register.password_retype') }}</label>
                </div>
                <label for="repassword" class="error" id="repassword-match" data-validation="{{ trans('landing.register.password_not_match') }}"></label>
                <div class="form-group">
                    <button class="next btn btn-primary" type="button" onclick="submitData()" disabled>{{trans('landing.register.save_password')}}</button>
                </div>
            </div>
        </form>

    </div>
</div>
</div>


<script src="landing/js/jquery.min.js"></script>
<script src="landing/bootstrap/js/bootstrap.min.js"></script>
<script src="landing/js/float-panel.js"></script>
<script src="landing/js/bs-animation.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

<script>
    var valid = false;

    function checkValue() {
        $(document).find('.form-group input').each(function (i, e) {
            let t = $(this);
            if (t.val() !== '') {
                t.addClass('filled')
            } else {
                t.removeClass('filled')
            }
        })
    }
    $(document).on('change', 'input', function () {
        console.log('asdasdasdafewerg4');
        
    })

    // $(document).on('keypress change search input paste cut', 'input, select', function (evt) {
    //     $(this).closest('.form-group').find('label.error').remove();
    //     let t = $(this);
    //     console.log('asdasd');
    //     if (t.val() !== '') {
    //         t.addClass('filled')
    //     } else {
    //         t.removeClass('filled')
    //     }
    //     if ($(this).attr('name') === 'domain') {
    //         evt = (evt) ? evt : window.event;
    //         var charCode = (evt.which) ? evt.which : evt.keyCode;
    //         // if (charCode > 31 && (charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122)) {
    //         //     $(this).closest('.form-group').append('<label class="error">Alphabhet Numeric Only</label>')
    //         //     return false;
    //         // }
    //         $(this).val($(this).val().toLowerCase().replace(/\s/g, ''))
    //         clearTimeout($(this).data('timer'));
    //         if (charCode === 13) {
    //             validateDomain();
    //         } else {
    //             $(this).data('timer', setTimeout(validateDomain, 500));
    //         }

    //     }
    //     if ($(this).attr('name') === 'email') {
    //         clearTimeout($(this).data('timer'));
    //         if (charCode === 13) {
    //             validateEmail();
    //         } else {
    //             $(this).data('timer', setTimeout(validateEmail, 500));
    //         }

    //     }
    // });

    let loading = $('.loading');

    // function nextPrev(n) {
    //     if (n === 1) {
    //         $('label.error').remove();
    //         // loading.show();
    //         $.ajax({
    //             url: "{{route('memoria.validate.one')}}",
    //             data: $('#regForm').serialize(),
    //             dataType: 'json',
    //             success: function (data) {
    //                 var x = document.getElementsByClassName("tab");
    //                 x[currentTab].style.display = "none";
    //                 currentTab = currentTab + n;
    //                 showTab(currentTab);
    //             },
    //             error: function (e) {
    //                 // loading.hide();
    //                 if (e.status === 422) {
    //                     $.each(e.responseJSON.errors, function (i, e) {
    //                         $(document).find('input[name=' + i + ']').closest('.form-group').append('<label class="error">' + e[0] + '</label>');
    //                     })
    //                 }
    //             }
    //         });
    //     } else {
    //         $('label.error').remove();
    //         loading.show();
    //         $.ajax({
    //             url: "{{route('memoria.do.register')}}",
    //             data: $('#regForm').serialize(),
    //             dataType: 'json',
    //             success: function (data) {
    //                 loading.hide();
    //                 Swal.fire({
    //                     type : 'success',
    //                     title: 'Success',
    //                     text: 'Check email for activation'
    //                 }).then(
    //                     setTimeout(function () {
    //                         window.location.href = '/agent/login'
    //                     }, 5000)
    //                 );
    //             },
    //             error: function (e) {
    //                 loading.hide();
    //                 if (e.status === 422) {
    //                     $.each(e.responseJSON.errors, function (i, e) {
    //                         $(document).find('input[name=' + i + ']').parent().append('<label class="error">' + e[0] + '</label>');
    //                         $(document).find('select[name="' + i + '[]"]').parent().append('<label class="error">' + e[0] + '</label>');
    //                     })
    //                 }
    //             }
    //         });
    //     }
    // }

    // function validateForm() {
    //     // This function deals with validation of the form fields
    //     var x, y, i, valid = true;
    //     x = document.getElementsByClassName("tab");
    //     y = x[currentTab].getElementsByTagName("input");
    //     // A loop that checks every input field in the current tab:
    //     for (i = 0; i < y.length; i++) {
    //         // If a field is empty...
    //         if (y[i].value == "") {
    //             // add an "invalid" class to the field:
    //             y[i].className += " invalid";
    //             // and set the current valid status to false:
    //             valid = false;
    //         }
    //     }
    //     // If the valid status is true, mark the step as finished and valid:
    //     if (valid) {
    //         document.getElementsByClassName("step")[currentTab].className += " finish";
    //     }
    //     return valid; // return the valid status
    // }

    // function fixStepIndicator(n) {
    //     // This function removes the "active" class of all steps...
    //     var x = document.getElementsByClassName("step");
    //     // for (i = 0; i < x.length; i++) {
    //     //     x[i].className = x[i].className.replace(" active", "");
    //     // }
    //     //... and adds the "active" class to the current step:
    //     x[n].className += " active";
    // }

    // function backToFirst() {
    //     currentTab = 0;
    //     showTab(currentTab);
    //     var i, x = document.getElementsByClassName("step");
    //     for (i = 0; i < x.length; i++) {
    //         x[i].className = x[i].className.replace(" active", "");
    //     }
    //     fixStepIndicator(currentTab);
    // }

    // $(".toggle-password").click(function () {
    //     $(this).toggleClass("fa-eye fa-eye-slash");
    //     var input = $($(this).attr("toggle"));
    //     if (input.attr("type") == "password") {
    //         input.attr("type", "text");
    //     } else {
    //         input.attr("type", "password");
    //     }
    // });

    // Prevent Char in Input Type Number
    $(document).on('change keydown', 'input[type="number"], input[type="tel"], .number', function onChange(e) {
        if (e.metaKey == false){ // Enable metakey
            if (e.keyCode > 13 && e.keyCode < 48 && e.keyCode != 39 && e.keyCode != 37 && e.keyCode != 118 || e.keyCode > 57) {
                e.preventDefault(); // Disable char. Enable arrow
            };
            if (e.shiftKey === true ){ // Disable symbols
                if (e.keyCode > 46 && e.keyCode < 65) {
                    e.preventDefault();
                }
            }
        }
    })

    $(document).on('change keydown cut input', 'input[name="password"], input[name="repassword"]', function (e) {
        let password = $('input[name="password"]').val();
        let rePassword = $('input[name="repassword"]').val();
        let passwordLabel = $('#password-length');
        let rePasswordLabel = $('#repassword-match');
        let validationPassword = passwordLabel.attr('data-validation');
        let validationRePassword = rePasswordLabel.attr('data-validation');
        let button = $('.next');
        if (password.length <= 4) {
            valid = false;
            passwordLabel.text(validationPassword);
        }else{
            valid = true;
            passwordLabel.text('');
            console.log('true');
            if (password === rePassword) {
                valid = true;
                rePasswordLabel.text('');
                console.log('pasword sah')  
                button.removeAttr('disabled');
            }else{
                valid = false;
                rePasswordLabel.text(validationRePassword);
                console.log('tercapaadsjklfherguiergf');
                button.attr('disabled', true);
            }
        }
    });

    $(document).on('copy paste', 'input[name="password"], input[name="repassword"]', function (e) {
        e.preventDefault;
    });
    
    $(document).on('change cut copy paste keydown ', '.input-otp', function (e) {
       let thisValue = $(this).val();
       if(thisValue.length >= 1){
           if (e.which >= 48 && e.which <= 57) {
               $(this).val('');
           }
           $(this).next('.input-otp').focus();
       }
    });
    // Disable Paste in input type number
    $(document).on('paste', 'input[type="number"], input[type="tel"], .number', function(e) {
        e.preventDefault();
    });
</script>
</body>

</html>