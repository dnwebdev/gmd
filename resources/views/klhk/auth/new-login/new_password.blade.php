@extends('klhk.auth.new-login.auth_layout')

@section('title')
    {{ trans('landing.register.activation') }}
@stop
@section('styles')
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
    </style>
@stop

@section('content')
    {!! Form::open(['id'=>'regForm','autocomplete'=>'off', 'class' => 'login-form']) !!}
    <div class="card mb-0">
        <a id="backBtn" class="btn" href="{{ url('/') }}">
            <i class="icon-home2"></i>
        </a>
        <div class="card-body">
            <div class="text-center mb-3">
{{--                <i class="icon-spinner11 icon-2x text-success border-success border-3 rounded-round p-3 mb-3 mt-1"></i>--}}
                <img src="{{ asset('explore-assets/images/logo-pesona.png') }}" alt="" class="img-login p-3 mb-3 mt-1">
                <h5 class="mb-0">{{ trans('landing.register.send_mail') }}</h5>
            </div>
            {!! Form::hidden('token',$token) !!}
            <input type="hidden" name="klhk">

            <div class="form-group form-group-feedback form-group-feedback-right">
                <input type="email" name="email" value="{{ $email }}" class="form-control" placeholder="{{ trans('landing.login.your_mail') }}" required readonly>
                <div class="form-control-feedback">
                    <i class="icon-mail5 text-muted"></i>
                </div>
            </div>
            <div class="form-group form-group-feedback form-group-feedback-right">
                <input type="password" name="password" class="form-control"
                       placeholder="Password" required>
                <div class="form-control-feedback">
                    <i class="icon-lock2 text-muted"></i>
                </div>
            </div>
            <span class="form-text text-danger error" id="password-length"
                  data-validation="{{ trans('landing.register.password_min_length') }}"></span>
            <div class="form-group form-group-feedback form-group-feedback-right">
                <input type="password" name="password_confirmation" class="form-control"
                       placeholder="{{ trans('landing.register.password_retype') }}" required>
                <div class="form-control-feedback">
                    <i class="icon-lock2 text-muted"></i>
                </div>
            </div>
            <span class="form-text text-danger error" id="repassword-match"
                  data-validation="{{ trans('landing.register.password_not_match') }}"></span>
            <button class="btn bg-success btn-block next" type="button" id="submitButton" onclick="submitData()" disabled>
                <i class="icon-spinner11 mr-2"></i> {{trans('landing.register.save_password')}}
            </button>
        </div>
    </div>
    {!! Form::close() !!}
@stop

@section('script')
    <script src="{{asset('js/validation-hnd.js')}}"></script>
    <script>
        var valid = false;
        var inputPhone = $('input[name="phone"]'),
                dataSent = inputPhone.attr('data-vaidation'),
                dataSucceess = $('.next').attr('data-validation');

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
                    console.log(true);
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
            $('span.error').remove();
            loadingStart();
            $.ajax({
                method: 'POST',
                data: $('#regForm').serialize(),
                dataType: 'json',
                success: function (data) {
                    loadingFinish();
                    swalInit({
                        type: 'success',
                        title: 'Success',
                    }).then(setTimeout(function () {
                        window.location.href = '/company/dashboard'
                    }, 3000))
                },
                error: function (e) {
                    loadingFinish();
                    if (e.status === 422) {
                        if(e.responseJSON.result.email){
                            toastr.error(e.responseJSON.result.email);
                        }
                        $.each(e.responseJSON.errors, function (i, e) {
                            $(document).find('input[name=' + i + ']').parent().append('<span class="form-text text-danger error">' + e[0] + '</span>');
                            $(document).find('select[name="' + i + '[]"]').parent().append('<span class="form-text text-danger error">' + e[0] + '</span>');
                        })
                    }
                }

            })
        }

        function ResendOtp() {
            $('span.error').remove();
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
                    swalInit({
                        type: 'success',
                        title: dataSent,
                        text: dataSucceess
                    }).then(
                        setTimeout(() => {
                            console.log(data);

                        })
                    )
                }
            })
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