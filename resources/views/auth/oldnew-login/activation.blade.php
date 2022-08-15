@extends('auth.new-login.auth_layout')

@section('title')
    {{ trans('landing.register.activation') }}
@stop

@section('styles')
    <style>
        button:disabled {
            cursor: not-allowed;
            color: #b2b2b2;
        }
    </style>
@stop

@section('content')
    <h3 class="text-center">
        {{ trans('landing.register.register_otp') }}
    </h3>
    <form id="regForm" autocomplete="off">
        {!! csrf_field() !!}
        <div class="form-group">
            <div class="text-center mb-4 form-resend-otp">
                <button type="button" class="btn-resend-otp" onclick="ResendOtp()"
                        data-validation="{{trans('landing.register.otp_resend')}}">{{ trans('landing.register.resend_otp') }}</button>
                <br>
                <span id="timer"></span>
            </div>
            {{--                <input type="hidden" name="shortcode" value="{{$shortcode}}">--}}
            <input type="hidden" name="phone" data-validation="{{trans('landing.register.otp_resend')}}"
                   value="{{ $phone }}">
            <div class="row d-inline-flex mb-5 old-otp-section">
                <div class="col-3 parent-input-otp">
                    <input class="input-otp" type="number" maxlength="1">
                </div>
                <div class="col-3 parent-input-otp">
                    <input class="input-otp" type="number" maxlength="1">
                </div>
                <div class="col-3 parent-input-otp">
                    <input class="input-otp" type="number" maxlength="1">
                </div>
                <div class="col-3 parent-input-otp">
                    <input class="input-otp" type="number" maxlength="1">
                </div>
                <input type="hidden" name="otp">
            </div>
            <div class="form-group old-otp-section">
                <input class="form-control" type="password" name="password" autocomplete="off" required>
                <label class="form-control-placeholder" for="password">Password</label>
            </div>
            <label for="password" class="error" id="password-length"
                   data-validation="{{ trans('landing.register.password_min_length') }}"></label>
            <div class="form-group">
                <input class="form-control" type="password" name="password_confirmation" autocomplete="off" required>
                <label class="form-control-placeholder"
                       for="password_confirmation">{{ trans('landing.register.password_retype') }}</label>
            </div>
            <label for="repassword" class="error" id="repassword-match"
                   data-validation="{{ trans('landing.register.password_not_match') }}"></label>
            <div class="form-group">
                <button class="next btn btn-primary" type="button" onclick="submitData()"
                        data-validation="{{trans('landing.register.allready_active')}}"
                        disabled>{{trans('landing.register.activation')}}</button>
            </div>
        </div>
    </form>
@stop

@section('script')
    <script>
        var valid = false;
        let inputPhone = $('input[name="phone"]'),
            dataSent = inputPhone.attr('data-vaidation'),
            btnResend = $('.btn-resend-otp'),
            dataSucceess = btnResend.attr('data-validation');
        btnResend.attr('disabled', true);

        $(document).on('change keydown paste cut', 'input', function () {
            let inputOtp = $('.input-otp').map(function () {
                return $(this).val();
            }).get().join('');
            $('input[name="otp"]').val(inputOtp);
            // let allInput = $('input').map(function () {
            //     let t = $(this);
            //     let buttonSend = $('.next');
            //     if (t.val() !== '') {
            //         buttonSend.removeAttr('disabled')
            //     }else{
            //         buttonSend.attr('disabled', true);
            //     }
            // })
        });

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

        $(document).on('change cut copy paste keyup ', '.parent-input-otp input', function (e) {
            let thisValue = $(this).val();
            let t = $(this);
            let container = t.closest('.parent-input-otp');
            if (thisValue.length === 1) {
                $(this).closest('.row').next().find('input').focus();
                if (container.next().length > 0) {
                    container.next().find('input[type="number"]').focus();
                } else {
                    t.closest('.old-otp-section').next().find('input').focus();
                }
            } else {
                let finalValue = thisValue.slice(0, 1);
                t.val(finalValue);
            }
        });

        $(document).on('focus', '.input-otp', function () {
            $(this).val('');
        });

        $(document).on('keydown paste cut input change', 'input', function () {
            let otpVal = $('input[name="otp"]').val(),
                password = $('input[name="password"]').val(),
                rePassword = $('input[name="password_confirmation"]').val(),
                buttonNext = $('.next');
            console.log(otpVal);

            if (otpVal.length === 4 && password.length >= 6 && rePassword.length >= 6) {
                buttonNext.removeAttr('disabled');
            } else {
                buttonNext.attr('disabled', true);
            }
        });

        $(document).on('change keydown keyup cut', 'input[name="password"], input[name="password_confirmation"]', function (e) {
            let password = $('input[name="password"]').val();
            let rePassword = $('input[name="password_confirmation"]').val();
            let passwordLabel = $('#password-length');
            let rePasswordLabel = $('#repassword-match');
            let validationPassword = passwordLabel.attr('data-validation');
            let validationRePassword = rePasswordLabel.attr('data-validation');
            let otp = $('input[name="otp"]').val();
            if (passwordMinValue(password)) {
                valid = false;
                passwordLabel.text(validationPassword);
            } else {
                valid = true;
                passwordLabel.text('');
                if (password === rePassword && otp.length === 4) {
                    valid = true;
                    rePasswordLabel.text('');
                } else {
                    valid = false;
                    rePasswordLabel.text(validationRePassword);
                }
            }
        });

        $(document).on('copy paste', 'input[name="password"], input[name="repassword"]', function (e) {
            e.preventDefault;
        });

        function submitData() {
            $('label.error').remove();
            loadingStart();
            let dataSucceess = $('.next').attr('data-validation');
            $.ajax({
                method: 'POST',
                {{--url: '{{ url('otp/'.$shortcode) }}',--}}
                data: $('#regForm').serialize(),
                dataType: 'json',
                success: function (data) {
                    loadingFinish();
                    Swal.fire({
                        type: 'success',
                        title: 'Success',
                        text: dataSucceess
                    }).then(
                        setTimeout(function () {
                            window.location.href = '/company/dashboard'
                        }, 2000))
                },
                error: function (e) {
                    loadingFinish();
                    if (e.status === 422) {
                        $.each(e.responseJSON.errors, function (i, e) {
                            $(document).find('input[name=' + i + ']').parent().append('<label class="error">' + e[0] + '</label>');
                            $(document).find('select[name="' + i + '[]"]').parent().append('<label class="error">' + e[0] + '</label>');
                        })
                    }
                }

            })
        }

        checkLastSendOtp(btnResend);

        function ResendOtp() {
            $('label.error').remove();
            loadingStart();
            $.ajax({
                method: 'POST',
                url: '{{ url('api/resend-register') }}',
                data: {
                    phone: inputPhone.val()
                },
                dataType: 'json',
                success: function (data) {
                    loadingFinish();
                    window.localStorage.setItem('last_send_otp', new Date().valueOf());
                    Swal.fire({
                        type: 'success',
                        title: dataSent,
                        text: dataSucceess
                    })
                },
                error: function (e) {
                    loadingFinish();
                    if (e.status === 422) {
                        $.each(e.responseJSON.errors, function (i, e) {
                            $(document).find('input[name=' + i + ']').parent().append('<label class="error">' + e[0] + '</label>');
                            $(document).find('select[name="' + i + '[]"]').parent().append('<label class="error">' + e[0] + '</label>');
                        })
                    }
                }
            });
        }

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
        });
        // Disable Paste in input type number
        $(document).on('paste', 'input[type="number"], input[type="tel"], .number', function (e) {
            e.preventDefault();
        });
    </script>

@stop