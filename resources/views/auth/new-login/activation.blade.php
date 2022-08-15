@extends('auth.new-login.auth_layout')

@section('title')
    {{ trans('landing.register.activation') }}
@stop

@section('styles')
    <link rel="stylesheet" href="{{asset('landing/css/Contact-Form-Clean.css')}}">
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
        button:disabled{
            cursor: not-allowed;
            color: #b2b2b2;
        }
        .input-otp {
            background-color: #3aa0f1;
            color: #fff;
            font-size: 1rem;
        }
        .btn-resend-otp {
            background-color: #2196f3;
            color: #fff;
        }
    </style>
@stop

@section('content')
    <form class="login-form" id="regForm" autocomplete="off">
        {!! csrf_field() !!}
        <div class="card mb-0">
            <a id="backBtn" class="btn" href="{{ url('/') }}">
                <i class="icon-home2"></i>
            </a>
            <div class="card-body">

                <div class="text-center mb-3">
{{--                    <i class="icon-spinner11 icon-2x text-primary border-primary border-3 rounded-round p-3 mb-3 mt-1"></i>--}}
                    <img src="{{ asset('landing/img/Logo-Gomodo.png') }}" alt="" class="img-login p-3 mb-3 mt-1">
                    <h5 class="mb-0">{{ trans('landing.register.register_otp') }}</h5>
{{--                    <span class="d-block text-muted">We'll send you instructions in email</span>--}}
                </div>
                <div class="text-center mb-4 form-resend-otp">
                    <button type="button" class="btn-resend-otp" onclick="ResendOtp()" data-validation="{{trans('landing.register.otp_resend')}}">{{ trans('landing.register.resend_otp') }}</button>
                    <br>
                    <span id="timer"></span>
                </div>
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
                <div class="form-group form-group-feedback form-group-feedback-right old-otp-section">
                    <input name="password" type="password" class="form-control" placeholder="Password" required>
                    <div class="form-control-feedback">
                        <i class="icon-user-lock text-muted"></i>
                    </div>
                </div>
                <span for="password" class="form-text text-danger error" id="password-length" data-validation="{{ trans('landing.register.password_min_length') }}"></span>
                <div class="form-group form-group-feedback form-group-feedback-right">
                    <input name="password_confirmation" type="password" class="form-control" placeholder="{{ trans('landing.register.password_retype') }}" required>
                    <div class="form-control-feedback">
                        <i class="icon-user-lock text-muted"></i>
                    </div>
                </div>
                <span for="repassword" class="form-text text-danger error" id="repassword-match" data-validation="{{ trans('landing.register.password_not_match') }}"></span>

                <button class="btn bg-primary btn-block mt-4 next" type="button" onclick="submitData()"
                        data-validation="{{trans('landing.register.allready_active')}}"
                        disabled><i class="icon-spinner11 mr-2"></i> {{trans('landing.register.activation')}}
                </button>
            </div>
        </div>
    </form>
@stop

@section('script')
    <script src="{{asset('js/validation-hnd.js')}}"></script>
    <script>
        var valid = false;

        $(document).on('change keydown paste cut', 'input', function () {
            let inputOtp = $('.input-otp').map(function () {
                return $(this).val();
            }).get().join('');
            $('input[name="otp"]').val(inputOtp);
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
                    t.closest('.old-otp-section').next().find('input, input[name=password]').focus();
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
            $('span.error').remove();
            loadingStart();
            let dataSucceess = $('.next').attr('data-validation');
            $.ajax({
                method: 'POST',
                {{--url: '{{ url('otp/'.$shortcode) }}',--}}
                data: $('#regForm').serialize(),
                dataType: 'json',
                success: function (data) {
                    loadingFinish();
                    swalInit({
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
                            $(document).find('input[name=' + i + ']').parent().append('<span class="form-text text-danger error">' + e[0] + '</span>');
                            $(document).find('select[name="' + i + '[]"]').parent().append('<span class="form-text text-danger error">' + e[0] + '</span>');
                        });
                    }
                }

            })
        }

        checkLastSendOtp($('.btn-resend-otp'));

        function ResendOtp() {
            $('span.error').remove();
            loadingStart();
            let inputPhone = $('input[name="phone"]'),
                dataSent = inputPhone.attr('data-vaidation'),
                btnResend = $('.btn-resend-otp'),
                dataSucceess = btnResend.attr('data-validation');
            btnResend.attr('disabled', true);

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
                    swalInit({
                        type: 'success',
                        title: dataSent,
                        text: dataSucceess
                    })
                },
                error: function (e) {
                    loadingFinish();
                    if (e.status === 422) {
                        if(e.responseJSON.errors.phone){
                           toastr.error(e.responseJSON.errors.phone)
                        }
                        $.each(e.responseJSON.errors, function (i, e) {
                            $(document).find('input[name=' + i + ']').parent().append('<span class="form-text text-danger error">' + e[0] + '</span>');
                            $(document).find('select[name="' + i + '[]"]').parent().append('<span class="form-text text-danger error">' + e[0] + '</span>');
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
        })

        // Disable Paste in input type number
        $(document).on('paste', 'input[type="number"], input[type="tel"], .number', function (e) {
            e.preventDefault();
        });
    </script>
@stop