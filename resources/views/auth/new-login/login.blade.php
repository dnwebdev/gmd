@extends('auth.new-login.auth_layout')

@section('title')
    {{ trans('landing.login.title') }}
@stop

@section('styles')
    <style>
        .border-slate-300 {
            border-color: #2196f3;
        }

        .text-slate-300 {
            color: #2196f3;
        }

        a {
            color: #2196f3;
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
            color: darkblue;
        }

        .input-group-append {
            margin-left: .75rem
        }

        /* .form-group {
            position: relative;
        }
        .form-group .error {
            position: absolute;
            bottom: -20px;
            left: 33px;
        } */

        /* OTP start */
        .digit-group {
            text-align: center
        }

        .digit-group input {
            width: 30px;
            height: 50px;
            line-height: 50px;
            text-align: center;
            font-size: 24px;
            font-family: 'Raleway', sans-serif;
            font-weight: 200;
            color: #333;
            margin: 0 2px;
        }

        .digit-group .splitter {
            padding: 0 5px;
            color: white;
            font-size: 24px;
        }
        .prompt {
            margin-bottom: 20px;
            font-size: 20px;
            color: white;
        }

        .digit-group .filled {
            box-shadow: 0 0 0 0 transparent, 0 1px 0 #2196f3;
            border-bottom: 1.25px solid #2196f3;
        }
        /* OTP end */

        /* Input type number hide arrow :start */
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }

        /* Firefox */
        input[type=number] {
        -moz-appearance: textfield;
        }
        /* Input type number hide arrow :end */
    </style>
@stop
@section('content')
    <form id="regForm" class="login-form" method="POST" autocomplete="off" novalidate>
        {{ csrf_field() }}
        <div class="card mb-0">
            <a id="backBtn" class="btn" href="{{ url('/') }}">
                <i class="icon-home2"></i>
            </a>
            <div class="card-body">
                <div class="text-center mb-3">
{{--                    <i class="icon-user icon-2x text-slate-300 border-slate-300 border-3 rounded-round p-3 mb-3 mt-1"></i>--}}
                    <img src="{{ asset('landing/img/Logo-Gomodo.png') }}" alt="" class="img-login p-3 mb-3 mt-1">
                    <h5 class="mb-0 hide-for-otp">{{ trans('landing.login.title') }}</h5>
                    <h5 class="mb-0 show-for-otp" style="display: none"> {!! trans('landing.login.verification_title') !!}</h5>
                    <div class="show-for-otp text-muted" style="display: none"> {!! trans('landing.login.verification_desc') !!}</div>
                    {{--								<span class="d-block text-muted">Your credentials</span>--}}
                </div>
                @if($errors)
                    @foreach ($errors->all() as $error)
                        <span class="form-text text-danger error">{{ $error }}</span>
                    @endforeach
                @endif
                {{-- <div id="error_message"></div> --}}
                {{-- <input type="hidden" name="use" id="select-login" value="0" autocomplete="off"> --}}
                {{-- <ul class="nav nav-tabs nav-tabs-highlight nav-justified" id="nav-login">
                    <li class="nav-item"><a href="#top-justified-tab1" data-id="0" class="nav-link active"
                                            data-toggle="tab">{{ trans('landing.login.use_mail') }}</a></li>
                    <li class="nav-item"><a href="#top-justified-tab2" data-id="1" class="nav-link"
                                            data-toggle="tab">{{ trans('landing.login.use_phone') }}</a>
                    </li>
                </ul> --}}
                {{-- <div id="email_append"></div> --}}
                {{-- Login with username --}}
                <div class="form-group form-group-feedback form-group-feedback-left change-email hide-for-otp" id="login_id_container">
                    <input type="text" class="form-control" name="login_id" autocomplete="off"
                           placeholder="{{trans('landing.login.your_wa_mail')}}" required>
                    <div class="form-control-feedback">
                        <i class="icon-user text-muted"></i>
                    </div>
                    <div class="input-group-append show-for-email" style="display: none">
                        <a id="backtoemail" href="#" class="my-auto">{{ trans('landing.login.change_email') }}</a>
                    </div>
                </div>

                <div class="form-group form-group-feedback show-for-otp" id="otp_container" style="display: none">
                    <div method="get" class="digit-group row mx-4" data-group-name="digits" data-autosubmit="true" autocomplete="off">
                        <div class="col-3">
                            <input type="number" class="form-control digit-otp" id="digit-1" name="digit-1" data-next="digit-2" />
                        </div>
                        <div class="col-3">
                            <input type="number" class="form-control digit-otp" id="digit-2" name="digit-2" data-next="digit-3" data-previous="digit-1" />
                        </div>
                        <div class="col-3">
                            <input type="number" class="form-control digit-otp" id="digit-3" name="digit-3" data-next="digit-4" data-previous="digit-2" />
                        </div>
                        <div class="col-3">
                            <input type="number" class="form-control digit-otp" id="digit-4" name="digit-4" data-next="digit-5" data-previous="digit-3" />
                        </div>
                    </div>
                </div>

                {{-- Login with telp --}}
                {{-- <div class="form-group form-group-feedback form-group-feedback-left" id="login_phone"
                     style="display: none">
                    <input type="tel" id="phone" name="phone" class="form-control" autocomplete="off"
                           placeholder="{{trans('landing.placeholder_phone')}}" required>
                </div> --}}
                {{-- <div class="form-group form-group-feedback form-group-feedback-left" id="error_phone">
                    <input type="hidden" name="telphone" class="form-control" autocomplete="off" required>
                </div> --}}

                {{-- Password --}}
                <div class="form-group form-group-feedback form-group-feedback-left input-group show-for-email" style="display: none">
                    <input type="password" class="form-control" name="password" autocomplete="off"
                           placeholder="{{trans('landing.login.password')}}" required>
                    <div class="form-control-feedback">
                        <i class="icon-lock2 text-muted"></i>
                    </div>
                    <div class="input-group-append">
                        <span class="input-group-text eye-icon" id="eye"><i class="icon-eye text-muted"></i></span>
                    </div>
                </div>
                <div id="error_domain" class="pb-3 text-center"></div>

                <div class="form-group align-items-center show-for-email text-right" style="display: none">
                   {{-- <a href="" class="ml-auto text-forgot" data-toggle="modal" data-target="#modal-login">
                       {{ trans('landing.login.forgot_password') }}
                   </a> --}}
                    <a href="{{ url('/password/reset') }}" class="ml-auto text-forgot">
                        {{ trans('landing.login.forgot_password') }}
                    </a>
                </div>
                <div class="form-group hide-for-otp">
                    <button type="submit" id="submitButton"
                            class="btn btn-primary btn-block">{{trans('landing.navbar.login')}}<i
                                class="icon-circle-right2 ml-2"></i></button>
                </div>

                <div class="form-group text-center text-muted content-divider hide-for-otp">
                    <span class="px-2">{{ trans('landing.login.dont_have_account') }}</span>
                </div>

                <div class="form-group hide-for-otp">
                    <a href="{{ url('/agent/register') }}"
                       class="btn btn-light btn-block">{!! trans('landing.login.sign_up') !!}</a>
                </div>

                <div class="form-group show-for-otp" style="display: none">
                    <button type="submit" id="submit_otp"
                            class="btn btn-primary btn-block" disabled>{!! trans('landing.login.verify') !!}</button>
                </div>
                <div class="form-group text-center text-muted show-for-otp" style="display: none">
                    <div class="px-2 timer-otp">{!! trans('landing.login.wait_for_resend') !!}</div>
                    <div class="px-2 resend-otp" style="display: none">{{ trans('landing.login.not_recieve_code') }}</div>
                    <a href="#" id="resend_otp" class="resend-otp" style="display: none">{{ trans('landing.login.resend') }}</a>
                </div>

            </div>
        </div>
    </form>
    <div id="modal-login" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">{!! trans('landing.login.forgot_password') !!}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <a href="{{ url('/password/reset') }}" class="btn btn-outline-primary waves-effect">
                            {!! trans('landing.login.use_mail') !!}
                        </a>
                    </div>
                    <div class="text-center">
                        <small id="or-text">{!! trans('landing.login.or') !!}</small>
                    </div>
                    <div class="text-center mb-3">
                        <a href="{{ url('/password/auth') }}" class="btn btn-outline-primary waves-effect">
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
        // Utils.js start
        // var inputPhoneNumber = document.querySelector('#phone');
        // var itilNumber = window.intlTelInput(inputPhoneNumber, {
        //     preferredCountries: ['id'],
        //     separateDialCode: true,
        //     allowDropdown: false,
        //     utilsScript: "build/js/utils.js",
        // });

        // $(document).on('change copy paste cut keydown input', '#phone', function (e) {
        //     let thisValue = $(this).val(),
        //         finalPhone = itilNumber.getNumber().slice(1);
        //     $('input[name="telphone"]').val(finalPhone);
        // });
        // Utils.js end

        // Init -------------------------------------------------------------------------------------------

        digit_filled_check()

        // Event -------------------------------------------------------------------------------------------

        $(document).on('click', '#backtoemail', function() {
            $(document).find('.show-for-email').hide()
            $(document).find('.change-email').removeClass('input-group')
            $(document).find('#regForm').removeClass('email')
            $(document).find('[name=login_id]').prop('disabled', false)
        })

        $(document).on('keyup', '[name=login_id]', function() {
            let this_val = $(this).val();
            let this_val_length = this_val.length;

            // 0 to +62 for numeric val
            if ($.isNumeric(this_val) && this_val.charAt(0) == 0) {
                $(this).val('+62' + this_val.slice(1, this_val_length))
            }
        })

        $(document).on('click', '#resend_otp', function(){
            $(document).find('#regForm').removeClass('otp');
            $(document).find('.timer-otp').show()
            $(document).find('.resend-otp').hide()
            $('#regForm').trigger('submit')
            $(document).find('.digit-otp').val('').removeClass('filled')
            digit_filled_check()
        })

        $(document).on('submit', '#regForm', function(e) {
            e.preventDefault()
            let login_id_value = $(document).find('[name=login_id]').val(),
                type_data_numeric = $.isNumeric(login_id_value),
                token_id = $(document).find('[name=_token]').val(),
                indexOf_id_city = login_id_value.indexOf('62');

            if (validate($(this))) {
                if (type_data_numeric) {
                    // Ajax request for phone number login
                    let phone_code_value = login_id_value.slice(indexOf_id_city,indexOf_id_city + 2);
                    let phone_value = login_id_value.slice(indexOf_id_city + 2);
                    

                    var form_data = {
                        _token: token_id,
                        phone_code: phone_code_value,
                        phone: phone_value,
                    }

                    if ($(this).hasClass('otp')) {
                        var otp_val = '';
                        $(document).find('.digit-otp').each( function (i, e) {
                            otp_val += $(e).val();
                        })

                        form_data.otp = otp_val;
                        login_ajax('{{ url("/api/validate-otp") }}', form_data, 'otp')
                    } else {
                        login_ajax('{{ url("/api/send-otp") }}', form_data, 'phone')
                    }
                } else {
                    // Ajax request for email login
                    let email_value = login_id_value;
                    let password_value = $(document).find('[name=password]').val()

                    var form_data = {
                        _token: token_id,
                        email: email_value,
                        password: password_value
                    }

                    if ($(this).hasClass('email')) {
                        login_ajax('{{ url("/agent/login") }}', form_data, 'email')
                    } else {
                        $(document).find('.show-for-email').show()
                        $(document).find('.change-email').addClass('input-group')
                        $(this).addClass('email')
                        $(document).find('[name=login_id]').prop('disabled', true)
                    }
                }
            }
        })

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

        $(document).on('paste', '.digit-group input', function(e) {
            var parent = $($(this).closest('.digit-group'));

            catchPaste(e, this, function(clipData) {
                var number = clipData.replace(/[^0-9]/g, ''), // replace to number
                    four_digit = number.slice(0, 4),
                    splitted = four_digit.split(''),
                    digit_otp = parent.find('.digit-otp');

                digit_otp.val('');
                digit_otp.addClass('filled');
                splitted.forEach(function(e, i) {
                    digit_otp.eq(i).val(e)
                })
                $('#regForm').trigger('submit')
            });
        })

        // Validation -------------------------------------------------------------------------------------------

        function validate(element) {
            let el = element;
                login_id_val = el.find('[name=login_id]').val(),
                input_text = el.find('input[type=text]'),
                valid = true;

            let empty_test = input_text.attr('required') !== undefined && input_text.val() == '';
                email_regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
                numeric_test = $.isNumeric(login_id_val),
                indexOf_id_city = login_id_val.indexOf('62');

            $('.error').remove();

            // Empty value validation
            if (empty_test) {
                input_text.parent('.form-group').append('<span class="form-text text-danger error">{{ trans("landing.validation.required") }}</span>')
                valid = false
            }

            // Email value validation
            if (!email_regex.test(login_id_val) && !numeric_test && !empty_test) {
                input_text.parent('.form-group').append('<span class="form-text text-danger error">{{ trans("landing.validation.wrong_email") }}</span>')
                valid = false
            }

            // Phone value validation
            if (numeric_test && !(indexOf_id_city === 0 || indexOf_id_city === 1)) {
                input_text.parent('.form-group').append('<span class="form-text text-danger error">{{ trans("landing.validation.wrong_phone") }}</span>')
                valid = false
            }

            return valid
        }

        // General Function -------------------------------------------------------------------------------------------

        function login_ajax(url, form_data, type) {
            loadingStart();
            $.ajax({
                url: url,
                method: 'POST',
                data: form_data,
                dataType: 'json',
                success: function (data) {
                    loadingFinish();
                    if (type === 'phone') {
                        $(document).find('.show-for-otp').show()
                        $(document).find('.hide-for-otp').hide()
                        $(document).find('#regForm').addClass('otp')
                        $(document).find('#phone_number_otp').text( '+' + form_data.phone_code + form_data.phone)
                        countingdown(22)
                    } else if (type === 'otp') {
                        window.location.href = '/company/dashboard'
                    } else if (type === 'email') {
                        window.location.href = '/company/dashboard'
                    }
                },
                error: function (e) {
                    let error_domain = $('#error_domain'),
                        form_group = $('.form-group');
                    loadingFinish();
                    form_group.find('span.error').remove()
                    error_domain.find('span.error').remove()
                    if(e.status !== undefined && e.status === 422) {
                        let result = e.responseJSON.result,
                            errors = e.responseJSON.errors;

                        if (result !== undefined) {
                            $.each(result, function(i, el) {
                                if (i == 'email') {
                                    error_domain.append('<span class="form-text text-danger error">'+ el[0] +'</span>')
                                    toastr.error(el[0], '{{__('general.whoops')}}')
                                } else {
                                    error_domain.append('<span class="form-text text-danger error">'+ e.responseJSON.message +'</span>')
                                    toastr.error(e.responseJSON.message, '{{__('general.whoops')}}')
                                }
                            })
                        }

                        if (errors !== undefined) {
                            $.each(errors, function(i, el) {
                                if (i === 'phone') {
                                    error_domain.append('<span class="form-text text-danger error">'+ el[0] +'</span>')
                                    toastr.error(el[0], '{{__('general.whoops')}}')
                                } else if (i === 'otp') {
                                    error_domain.append('<span class="form-text text-danger error">'+ el[0] +'</span>')
                                    toastr.error(el[0], '{{__('general.whoops')}}')
                                } else if (i === 'password') {
                                    $(document).find('[name=password]').closest('form-group').append('<span class="form-text text-danger error">'+ el[0] +'</span>')
                                    toastr.error(el[0], '{{__('general.whoops')}}')
                                } else {
                                    error_domain.append('<span class="form-text text-danger error">'+ e.responseJSON.message +'</span>')
                                    toastr.error(e.responseJSON.message, '{{__('general.whoops')}}')
                                }
                            })
                        }
                    } else if (e.status !== undefined && e.status === 400) {
                        let message = e.responseJSON.message;
                        if (message !== undefined) {
                            error_domain.append('<span class="form-text text-danger error">'+ message +'</span>')
                            toastr.error(message, '{{__('general.whoops')}}')
                        }
                    } else {
                        console.log(e.status, e)
                    }
                }
            })
        }

        // OTP
        $('.digit-group').find('input').each(function() {
            $(this).on('keyup', function(e) {
                $(this).val($(this).val().slice(0, 1))
                if(digitOnly(e)) {
                    var parent = $($(this).closest('.digit-group'));
                    digit_filled_check()
                    let left = ['Backspace', 'ArrowLeft'];
                    let right = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'Delete', 'ArrowRight', 'Tab', 'MetaLeft', 'MetaRight', 'v'];
                    
                    if(left.includes(e.key)) { // Backspace or left arrow
                        var prev = parent.find('input#' + $(this).data('previous'));
                        if(prev.length) {
                            $(prev).select();
                        }
                    } 
                    
                    if(right.includes(e.key)) {
                        var next = parent.find('input#' + $(this).data('next'));
                        if(next.length) {
                            $(next).select();
                        } else {
                            if(parent.data('autosubmit')) {
                                parent.submit();
                            }
                        }
                    }
                } else {
                    console.log('digit only')
                }
            });
        });

        // Digit filled all check
        function digit_filled_check() {
            var otp_button = $(document).find('#submit_otp');
            let valid = true;

            $(document).find('.digit-otp').each(function(i, e) {
                let value = $(e).val();
                if(value === '') {
                    valid = false
                }
            })

            otp_button.prop('disabled', !valid);
        }

        // Counting down resend otp
        function countingdown(digit) {
            let interval = setInterval(function(){
                digit = digit - 1;
                $('#digit_backwards').text(digit)
                
                if ( digit == 0 ) {
                    clearInterval(interval)
                    $(document).find('.resend-otp').show()
                    $(document).find('.timer-otp').hide()
                }
            }, 1000)
        }
    </script>
@stop
