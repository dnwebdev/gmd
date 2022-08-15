@extends('klhk.auth.new-login.auth_layout')

@section('title')
    {{ trans('landing.login.title') }}
@stop

@section('styles')
    <style>
        .border-slate-300 {
            border-color: #4caf50;
        }

        .text-slate-300 {
            color: #4caf50;
        }

        a {
            color: #4caf50;
        }

        .select2-results__option[aria-selected=true] {
            color: #fff;
            background-color: #4caf50;
        }

        .iti.iti--allow-dropdown, .iti.iti--allow-dropdown input {
            width: 100%;
        }

        #login_phone {
            margin-bottom: 1.2rem;
        }

        #phone {
            padding-left: 5.5rem !important;
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

        a:hover {
            color: green;
        }

        .nav-tabs-highlight .nav-link.active {
            border-top-color: #4caf50;
        }

        .nav-tabs-highlight .nav-link.active:before {
            background-color: #4caf50;
        }
    </style>
@stop
@section('content')
    <form id="regForm" class="login-form" method="POST" autocomplete="off">
        {{ csrf_field() }}
        <input type="hidden" value="KLHK" name="klhk">
        <div class="card mb-0">
            <a id="backBtn" class="btn" href="{{ url('/') }}">
                <i class="icon-home2"></i>
            </a>
            <div class="card-body">
                <div class="text-center mb-3">
{{--                    <i class="icon-user icon-2x text-slate-300 border-slate-300 border-3 rounded-round p-3 mb-3 mt-1"></i>--}}
                    <img src="{{ asset('explore-assets/images/logo-pesona.png') }}" alt="" class="img-login p-3 mb-3 mt-1">
                    <h5 class="mb-0">{{ trans('landing-klhk.login.title') }}</h5>
                    {{--								<span class="d-block text-muted">Your credentials</span>--}}
                </div>
                @if($errors)
                    @foreach ($errors->all() as $error)
                        <span class="form-text text-danger error">{{ $error }}</span>
                    @endforeach
                @endif
                <div id="error_message"></div>
                <input type="hidden" name="use" id="select-login" value="0" autocomplete="off">
                <ul class="nav nav-tabs nav-tabs-highlight nav-justified" id="nav-login">
                    <li class="nav-item"><a href="#top-justified-tab1" data-id="0" class="nav-link active"
                                            data-toggle="tab">{{ trans('landing.login.use_mail') }}</a></li>
                    <li class="nav-item"><a href="#top-justified-tab2" data-id="1" class="nav-link"
                                            data-toggle="tab">{{ trans('landing.login.use_phone') }}</a>
                    </li>
                </ul>
                <div id="email_append"></div>
                {{-- Login with username --}}
                <div class="form-group form-group-feedback form-group-feedback-left" id="login_mail">
                    <input type="email" class="form-control" name="email" id="email" autocomplete="off"
                           placeholder="{{trans('landing.login.your_mail')}}" required>
                    <div class="form-control-feedback">
                        <i class="icon-user text-muted"></i>
                    </div>
                </div>

                {{-- Login with telp --}}
                <div class="form-group form-group-feedback form-group-feedback-left" id="login_phone"
                     style="display: none">
                    <input type="tel" id="phone" name="phone" class="form-control" autocomplete="off"
                           placeholder="{{trans('landing.placeholder_phone')}}" required>
                </div>
                <div class="form-group form-group-feedback form-group-feedback-left" id="error_phone">
                    <input type="hidden" name="telphone" class="form-control" autocomplete="off" required>
                </div>

                {{-- Password --}}
                <div class="form-group form-group-feedback form-group-feedback-left input-group">
                    <input type="password" class="form-control" name="password" autocomplete="off"
                           placeholder="{{trans('landing.login.password')}}" required>
                    <div class="form-control-feedback">
                        <i class="icon-lock2 text-muted"></i>
                    </div>
                    <div class="input-group-append">
                        <span class="input-group-text eye-icon" id="eye"><i class="icon-eye text-muted"></i></span>
                    </div>
                </div>
                <div id="error_domain" class="pb-1"></div>

                <div class="form-group d-flex align-items-center">
{{--                    <a href="" class="ml-auto text-forgot" data-toggle="modal" data-target="#modal-login">--}}
{{--                        {{ trans('landing.login.forgot_password') }}--}}
{{--                    </a>--}}
                    <a href="{{ url('/password/reset') }}" class="ml-auto text-forgot">
                        {{ trans('landing.login.forgot_password') }}
                    </a>
                </div>
                <div class="form-group">
                    <button type="submit" id="submitButton"
                            class="btn btn-success btn-block">{{trans('landing.navbar.login')}}<i
                                class="icon-circle-right2 ml-2"></i></button>
                </div>

                <div class="form-group text-center text-muted content-divider">
                    <span class="px-2">{{ trans('landing.login.dont_have_account') }}</span>
                </div>

                <div class="form-group">
                    <a href="{{ url('/agent/register') }}"
                       class="btn btn-light btn-block">{!! trans('landing.login.sign_up') !!}</a>
                </div>

            </div>
        </div>
    </form>
    <div id="modal-login" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">{!! trans('landing.login.forgot_password') !!}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <a href="{{ url('/password/reset') }}" class="btn btn-outline-success waves-effect">
                            {!! trans('landing.login.use_mail') !!}
                        </a>
                    </div>
                    <div class="text-center">
                        <small id="or-text">{!! trans('landing.login.or') !!}</small>
                    </div>
                    <div class="text-center mb-3">
                        <a href="{{ url('/password/auth') }}" class="btn btn-outline-success waves-effect">
                            {!! trans('landing.login.use_phone') !!}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/demo_pages/form_layouts.js') }}"></script>
    {{--    <script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/demo_pages/login_validation.js') }}"></script>--}}
    <script src="{{asset('js/validation-hnd.js')}}"></script>
    <script>
        var valid = true;
        var inpuMail = $('input[name="user"]');
        var inputPassword = $('input[name="password"]');
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
            $('input[name="telphone"]').val(finalPhone);
        });

        $(document).ready(function () {
            $("#regForm").validate({
                ignore: ":disabled,:hidden",
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
                        error.insertBefore('#error_domain');
                    } else if (element.prop("type") === "tel" || element.prop("type") === "email") {
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

        $(document).on('click', '#nav-login', function () {
            let select = $(this).find('a.active').data('id');
            let html = '';
            $(document).find('#select-login').val(select);
            $(document).find('p.rm-error').remove();
            // $('input[name=email]').closest('.form-group').find('span.error').remove();
            if (select == 0) {
                $(document).find('#login_phone').hide();
                $(document).find('input[type=tel]').prop('disabled', true).val('');
                $(document).find('input[name="password"]').val('');
                $(document).find('#email').prop('disabled', false);

                html = '<div class="form-group form-group-feedback form-group-feedback-left" id="login_mail">' +
                    '       <input type="email" class="form-control" name="email" id="email" autocomplete="off" placeholder="{{trans('landing.login.your_mail')}}" required>' +
                    '       <div class="form-control-feedback">' +
                    '           <i class="icon-user text-muted"></i>' +
                    '       </div>' +
                    '   </div>';
            } else {
                $(document).find('#login_mail').remove();
                $(document).find('input[type=tel]').prop('disabled', false);
                $(document).find('#login_phone').show();
                $(document).find('input[name="password"]').val('');
            }
            $(document).find('#email_append').append(html);
        });

        $(document).on('keypress cut copy paste change input', 'input', function (e) {
            let passwordValue = $('input[name="password"]').val(),
                inputMail = $(document).find('input[name=email]').val(),
                phoneNumber = $(document).find('input[type=phone]').val(),
                selectLogin = $('input[name=use]').val();
            if (selectLogin == 0 && checkMail(inputMail) && passwordMinValue(passwordValue)) {
                $(this).addClass('filled');
                // $('#submitButton').removeAttr('disabled');
                // $('input[name="email"]').val(inputMail);
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
        $(document).on('click', '#eye i', function () {
            $(this).toggleClass('icon-eye-blocked text-muted');
            let input = $('input[name="password"]');
            if (input.attr('type') == 'password') {
                input.attr('type', 'text');
            } else {
                input.attr('type', 'password')
            }
        });

        $(document).on('keyup paste change', 'input, select, textarea', function () {
            $(this).closest('.form-group').find('span.error').remove();
            $('input[name=email]').closest('.form-group').find('span.error').remove();
            $('#error_domain').find('span.error').remove();
        });

        $(document).on('submit', '#regForm', function (e) {
            e.preventDefault();
            if ($("#regForm").valid()) {
                loadingStart();
                let t = $(this);
                t.closest('form').find('span.error').remove();
                $.ajax({
                    method: 'POST',
                    data: $('#regForm').serialize(),
                    dataType: 'json',
                    cache: false,
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
                                toastr.error(e.responseJSON.result.email)
                            }
                            $.each(e.responseJSON.errors, function (i, e) {
                                if (i == 'password') {
                                    $('#error_domain').append('<span class="form-text text-danger error">' + e[0] + '</span>');
                                } else {
                                    t.closest('form').find('input[name=' + i + ']').closest('.form-group').append('<span class="form-text text-danger error">' + e[0] + '</span>');

                                }
                                toastr.error(e[0]);
                            })
                        }
                    }
                })
            }
        });

    </script>
@stop
