@extends('auth.new-login.auth_layout')

@section('title')
    {{ trans('landing.register.activation') }}
@stop

@section('styles')
    <style>
        .toggle-password {
            cursor: pointer;
        }

        /* Disable spin button */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
        .input-group-text {
            background-color: white;
            border: 0;
        }
    </style>

@stop
@section('content')
    <h3 class="text-center">{{ trans('landing.register.send_mail') }}</h3>
    <form id="regForm" autocomplete="off">
        {!! csrf_field() !!}
        {!! Form::hidden('token',$token) !!}
        <div class="form-group old-otp-section">
            <input class="form-control" type="email" value="{{$email}}" name="email" autocomplete="off" required
                   readonly placeholder="{{ trans('landing.login.your_mail') }}">
{{--            <label class="form-control-placeholder" for="email">{{ trans('landing.login.your_mail') }}</label>--}}
        </div>
        <div class="form-group old-otp-section input-group">
            <input class="form-control" type="password" name="password" autocomplete="off" required>
            <label class="form-control-placeholder" for="password">Password</label>
            <div class="input-group-append">
                <span class="input-group-text eye-icon" id="eye1"><i class="fa fa-eye"></i></span>
            </div>
        </div>
        <label for="password" class="error" id="password-length"
               data-validation="{{ trans('landing.register.password_min_length') }}"></label>
        <div class="form-group input-group">
            <input class="form-control" type="password" name="password_confirmation" autocomplete="off" required>
            <label class="form-control-placeholder"
                   for="password_confirmation">{{ trans('landing.register.password_retype') }}</label>
            <div class="input-group-append">
                <span class="input-group-text eye-icon" id="eye2"><i class="fa fa-eye"></i></span>
            </div>
        </div>
        <label for="repassword" class="error" id="repassword-match"
               data-validation="{{ trans('landing.register.password_not_match') }}"></label>
        <div class="form-group">
            <button class="next btn btn-primary" type="button" onclick="submitData()"
                    disabled>{{trans('landing.register.submit')}}</button>
        </div>
    </form>
@stop

@section('script')
    <script>
        var valid = false;

        // $(document).on('change keydown paste cut', 'input', function () {
        //     let inputOtp = $('.input-otp').map(function () {
        //         return $(this).val();
        //     }).get().join('');
        //     $('input[name="otp"]').val(inputOtp);
        //     let allInput = $('input').map(function () {
        //         let t = $(this);
        //         let buttonSend = $('.next');
        //         if (t.val() !== '') {
        //             buttonSend.removeAttr('disabled')
        //         }else{
        //             buttonSend.attr('disabled', true);
        //         }
        //     })
        // });
        // $(document).on('change input keydown cut paste keyup', 'input', function () {
        //     let emailValue = $('input[name="email"]').val(),
        //         passwordValue = $('input[name="password"]').val(),
        //         confirmationPassword = $('input[name="confirmation_password"]').val(),
        //         button = $('.next');
        //     let passwordLabel = $('#password-length');
        //     let rePasswordLabel = $('#repassword-match');
        //     let validationPassword = passwordLabel.attr('data-validation');
        //     let validationRePassword = rePasswordLabel.attr('data-validation');
        //         console.log(emailValue);

        //         if(checkMail(emailValue) && passwordMinValue(passwordValue)){
        //             passwordLabel.text('');
        //             if (passwordValue === confirmationPassword) {
        //                 valid = true;
        //                 rePasswordLabel.text('');
        //                 button.attr('disabled', true);
        //                 button.removeAttr('disabled');

        //             }else{
        //                 valid = false;
        //                 rePasswordLabel.text(validationRePassword);
        //                 button.attr('disabled', true);
        //             }
        //         }else{
        //             button.attr('disabled', true);
        //         }
        // });

        $(document).on('focus', '.input-otp', function () {
            $(this).val('');
        });

        $(document).on('change keydown keyup cut', 'input[name="email"], input[name="password"], input[name="password_confirmation"]', function (e) {
            let emailValue = $('input[name="email"]').val(),
                button = $('.next');
            let password = $('input[name="password"]').val();
            let rePassword = $('input[name="password_confirmation"]').val();
            let passwordLabel = $('#password-length');
            let rePasswordLabel = $('#repassword-match');
            let validationPassword = passwordLabel.attr('data-validation');
            let validationRePassword = rePasswordLabel.attr('data-validation');
            if (passwordMinValue(password)) {
                valid = false;
                passwordLabel.text(validationPassword);
                console.log(false);

            } else {

                valid = true;
                passwordLabel.text('');
                if (password === rePassword && checkMail(emailValue)) {
                    valid = true;
                    button.removeAttr('disabled');

                    rePasswordLabel.text('');
                } else {
                    valid = false;
                    rePasswordLabel.text(validationRePassword);
                    button.attr('disabled', true);
                }
            }
        });

        $(document).on('copy paste', 'input[name="password"], input[name="repassword"]', function (e) {
            e.preventDefault;
        });

        function submitData() {
            $('label.error').remove();
            loadingStart();
            $.ajax({
                method: 'POST',
                data: $('#regForm').serialize(),
                dataType: 'json',
                success: function (data) {
                    loadingFinish();
                    Swal.fire({
                        type: 'success',
                        title: 'Success',
                    }).then(setTimeout(function () {
                        window.location.href = '/company/dashboard'
                    }, 3000))
                },
                error: function (e) {
                    loadingFinish();
                    if (e.responseJSON.result.email) {
                        toastr.error(e.responseJSON.result.email);
                    }
                    if (e.status === 422) {
                        $.each(e.responseJSON.errors, function (i, e) {
                            $(document).find('input[name=' + i + ']').parent().append('<label class="error">' + e[0] + '</label>');
                            $(document).find('select[name="' + i + '[]"]').parent().append('<label class="error">' + e[0] + '</label>');
                        })
                    }
                }

            })
        }

        {{--function ResendOtp() {--}}
        {{--    $('label.error').remove();--}}
        {{--    loadingStart();--}}
        {{--    let inputPhone = $('input[name="phone"]'),--}}
        {{--        dataSent = inputPhone.attr('data-vaidation'),--}}
        {{--        dataSucceess = $('.next').attr('data-validation');--}}
        {{--    $.ajax({--}}
        {{--        method: 'POST',--}}
        {{--        url: '{{ url('api/resend-register') }}',--}}
        {{--        data: {--}}
        {{--            phone: inputPhone.val()--}}
        {{--        },--}}
        {{--        dataType: 'json',--}}
        {{--        success: function (data) {--}}
        {{--            loadingFinish();--}}
        {{--            Swal.fire({--}}
        {{--                type: 'success',--}}
        {{--                title: dataSent,--}}
        {{--                text: dataSucceess--}}
        {{--            }).then(--}}
        {{--                setTimeout(() => {--}}
        {{--                    console.log(data);--}}

        {{--                })--}}
        {{--            )--}}
        {{--        }--}}
        {{--    })--}}
        {{--}--}}

        $(".toggle-password").click(function () {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });

        // Prevent Char in Input Type Number
        $(document).on('change keydown', 'input[type="number"], input[type="tel"], .number', function onChange(e) {
            if (e.metaKey == false) { // Enable metakey
                if (e.keyCode > 13 && e.keyCode < 48 && e.keyCode != 39 && e.keyCode != 37 && e.keyCode != 118 || e.keyCode > 57) {
                    e.preventDefault(); // Disable char. Enable arrow
                }
                ;
                if (e.shiftKey === true) { // Disable symbols
                    if (e.keyCode > 46 && e.keyCode < 65) {
                        e.preventDefault();
                    }
                }
            }
        })


        // Disable Paste in input type number
        $(document).on('paste', 'input[type="number"], input[type="tel"], .number', function (e) {
            e.preventDefault();
        });

        $(document).on('click', '#eye1', function () {
            $(this).toggleClass('fa-eye fa-eye-slash');
            let input = $('input[name="password"]');
            if (input.attr('type') == 'password') {
                input.attr('type', 'text');
            } else {
                input.attr('type', 'password')
            }
        });

        $(document).on('click', '#eye2', function () {
            $(this).toggleClass('fa-eye fa-eye-slash');
            let input = $('input[name="password_confirmation"]');
            if (input.attr('type') == 'password') {
                input.attr('type', 'text');
            } else {
                input.attr('type', 'password')
            }
        });
    </script>
@stop

