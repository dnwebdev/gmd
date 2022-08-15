@extends('auth.new-login.auth_layout')

@section('title')
    {{ trans('landing.login.title') }}
@stop

@section('styles')
    <style>
        .btn {
            width: 100%;
        }

        .text-forgot {
            font-weight: bold;
            color: #2999fb;
            float: right;
        }

        .text-forgot:hover {
            text-decoration: none;
        }

        .text-register {
            color: #2699fb;
            font-weight: bold;
        }

        .text-register:hover {
            text-decoration: none;
        }

        .eye-icon {
            padding: 0.77rem .75rem;
            color: #9c9c9c;
        }

        .modal-body {
            padding: 1rem 1rem 5rem;
        }

        .modal-body .btn {
            margin-top: 2rem;
        }

        .modal-body hr {
            margin: 0;
        }

        #or-text {
            color: #9c9c9c;

        }

        .iti.iti--allow-dropdown, .iti.iti--allow-dropdown input {
            width: 100%;
        }

        .iti {
            width: 100%;
        }

        #login_phone {
            margin-bottom: 1.2rem;
        }

        #phone {
            padding-left: 5.5rem !important;
            width: 100%;
        }
    </style>
@stop


@section('content')
    <h3 class="text-center">{{ trans('landing.login.title') }}</h3>
    <form id="regForm" autocomplete="off" method="POST" name="sentMessage" action="" class="mb-5"
          novalidate="novalidate" novalidate>
        {{ csrf_field() }}
        <div class="form-group">
            <div class="form-group">
                <select id="select-login" name="use" class="browser-default custom-select">
                    <option value="0" selected>{{ trans('landing.login.use_mail') }}</option>
                    <option value="1">{{ trans('landing.login.use_phone') }}</option>
                </select>
            </div>
            <div class="form-group" id="login_mail">
                <input type="email" class="form-control" name="email" id="email" required>
                <label class="form-control-placeholder"
                       for="user">{{trans('landing.login.your_mail')}}</label>
            </div>
            <div id="login_phone" class="d-none">
                <input id="phone" name="phone" class="form-group" type="tel"
                       placeholder="{{trans('landing.placeholder_phone')}}"
                       autocomplete="off" required>
            </div>
            <div class="form-group" id="error_phone">
                <input type="hidden" name="email" class="form-control">
            </div>
            <div class="form-group input-group">
                <input type="password" class="form-control" id="password" name="password" required>
                <label class="form-control-placeholder"
                       for="password">{{trans('landing.login.password')}}</label>
                <div class="input-group-append">
                    <span class-="input-group-text eye-icon" id="eye"><i class="fa fa-eye"></i></span>
                </div>
            </div>
            <div class="form-group" id="error_domain"></div>
            <label for="mail" class="error" id="mail-error"
                   data-validation="{{ trans('landing.login.mail_empty') }}"></label>
            <a href="#" class="text-forgot" data-toggle="modal" data-target="#modal">
                {{ trans('landing.login.forgot_password') }}
            </a>
            <button type="submit" class="next btn btn-primary waves-effect waves-light" id="submitButton"
                    type="button">{{trans('landing.navbar.login')}}</button>
            <div class="text-center mt-5">
                <a href="{{ url('/agent/register') }}" class="text-register">
                    {{ trans('landing.login.dont_have_account') }}
                </a>
            </div>
            <div class="form-group">
                {{-- <button type="button" class="btn-link-forget">{{ trans('landing.login.back') }}</button> --}}
            </div>
        </div>
    </form>
    <div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalForgotPassword"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <h5 class="modal-title" id="exampleModalLabel">{{ trans('landing.login.forgot_password') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </h5>
                </div>
                <div class="modal-body">
                    <a href="{{url('/password/reset')}}">
                        <button type="button"
                                class="btn btn-outline-primary waves-effect">{{ trans('landing.login.use_mail') }}</button>
                    </a>
                    <div class="text-center mt-4"><small id="or-text">{{ trans('landing.login.or') }}</small></div>
                    <a href="{{url('/password/auth')}}">
                        <button type="button"
                                class="btn btn-outline-success waves-effect">{{ trans('landing.login.use_phone') }}</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#regForm").validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    phone: {
                        required: true,
                        number: true,
                        minlength: 9
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },

                },
                messages: {
                    email: {
                        required: "{!! trans('auth.validation.email.required') !!}",
                        email: "{!! trans('auth.validation.email.email') !!}",
                    },
                    password: {
                        required: "{!! trans('auth.validation.password.required') !!}",
                        minlength: "{!! trans('auth.validation.password.minlength') !!}",
                        maxlength: "{!! trans('auth.validation.password.maxlength') !!}"
                    },
                    phone: {
                        required: "{!! trans('auth.validation.telp.required') !!}",
                        number: "{!! trans('auth.validation.telp.number') !!}",
{{--                        minlength: "{!! trans('auth.validation.telp.minlength') !!}"--}}
                    },
                },
                errorElement: "p",
                errorPlacement: function (error, element) {
                    // Add the `help-block` class to the error element
                    error.addClass("help-block text-danger rm-error");

                    if (element.prop("type") === "password") {
                        error.insertAfter('#error_domain');
                    } else if(element.prop("type") === "tel"){
                        error.insertAfter('#error_phone');
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).parents(".col-sm-5").addClass("has-error").removeClass("has-success");
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).parents(".col-sm-5").addClass("has-success").removeClass("has-error");
                }
            });
        });


        var valid = true;
        var inpuMail = $('input[name="user"]');
        var inputPassword = $('input[name="password"]');
        // if(inpuMail.val() === '' && inputPassword.val() === ''){
        //     valid = false;
        //     $('#submitButton').attr('disabled', true);
        // }
        var mailLabel = $('#mail-error');
        var mailError = mailLabel.attr('data-validation');

        var inputPhoneNumber = document.querySelector('#phone');
        var itilNumber = window.intlTelInput(inputPhoneNumber, {
            preferredCountries: ['id'],
            separateDialCode: true,
            allowDropdown: false,
            utilsScript: "build/js/utils.js",
        });

        $(document).on('change copy paste cut keydown input', '#phone', function (e) {
            let thisValue = $(this).val(),
                finalPhone = itilNumber.getNumber().slice(1);
            $('input[name="email"]').val(finalPhone);
        });


        // $(document).on('change copy paste cut keydown input', '#mail', function () {
        //     let thisValue = $(this).val();
        //     if()
        // });

        var selectLogin = 0;

        $(document).on('change', '#select-login', function () {
            selectLogin = $(this).val();

            if (selectLogin == 0) {
                $('#login_phone').addClass('d-none');
                $('#login_mail').removeClass('d-none');
                $('#phone').val('');
                $('input[name="email"]').val('');
                $('input[name="password"]').val('');
                $('.rm-error').remove();
                // $('#submitButton').attr('disabled', true);
            } else {
                $('#login_phone').removeClass('d-none');
                $('#login_mail').addClass('d-none');
                $('#email').val('');
                $('input[name="password"]').val('');
                // $('#submitButton').attr('disabled', true);
                $('input[name="email"]').val('');
                $('.rm-error').remove();
            }
        });

        $(document).on('keypress cut copy paste change input', 'input', function (e) {
            let idValue = $('input[name="user"]').val(),
                passwordValue = $('input[name="password"]').val(),
                inputMail = $('#email').val(),
                phoneNumber = $('#phone').val();
            if (selectLogin == 0 && checkMail(inputMail) && passwordMinValue(passwordValue)) {
                $(this).addClass('filled');
                // $('#submitButton').removeAttr('disabled');
                $('input[name="email"]').val(inputMail);
            } else if (selectLogin == 1 && checkMinimumPhone(phoneNumber) && passwordMinValue(passwordValue)) {
                $(this).addClass('filled');
                // $('#submitButton').removeAttr('disabled');
            } else if ($(this).val() !== '') {
                $(this).addClass('filled');
            } else {
                $(this).removeClass('filled');
                // $('#submitButton').attr('disabled', true);
            }
        });
        $('#eye i').click(function () {
            $(this).toggleClass('fa-eye fa-eye-slash');
            let input = $('input[name="password"]');
            if (input.attr('type') == 'password') {
                input.attr('type', 'text');
            } else {
                input.attr('type', 'password')
            }
        });

        $(document).on('click', '#submitButton', function () {
            if($('input[name="password"]').val() == '' || $('input[name=email]').val() == ''){
                $("#regForm").valid();
            }else{
                let t = $(this);
                loadingStart();
                t.closest('form').find('label.error').remove();
                $.ajax({
                    method: 'POST',
                    data: $('#regForm').serialize(),
                    dataType: 'json',
                    success: function (data) {
                        setTimeout(() => {
                            loadingFinish();
                            window.location.href = '/company/dashboard'
                        }, 1000)
                    },
                    error: function (e) {
                        loadingFinish();
                        inputPassword.val('');
                        if (e.status === 422) {
                            if (e.responseJSON.result) {
                                toastr.error(e.responseJSON.result.email);
                            }
                            $.each(e.responseJSON.errors, function (i, e) {
                                if (i == 'password') {
                                    $('#error_domain').append('<label class="error">' + e[0] + '</label>');
                                } else {
                                    t.closest('form').find('input[name=' + i + ']').closest('.form-group').append('<label class="error">' + e[0] + '</label>');
                                }
                            })
                        }
                    }
                })
            }
        });

    </script>
@stop