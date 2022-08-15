@extends('klhk.auth.new-login.auth_layout')

@section('title')
    {{ trans('landing.register.easy') }}
@stop

@section('styles')
    <style>
        .login-form {
            width: 30rem;
        }

        dl, ol, ul {
            padding-right: 1.5rem;
        }

        .modal-body {
            padding: 1.25rem 1rem;
        }

        .iti {
            width: 100%;
        }

        .next {
            background-color: green;
        }

        .term-and-condition {
            color: green;
        }

        .term-and-condition:hover {
            color: #036103;
        }

        #phone {
            padding-left: 68px !important;
        }

        .nav-tabs-highlight .nav-link.active:before {
            background-color: green;
        }

        .nav{
            padding-right: 0;
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
{{--                <i class="icon-user-plus icon-2x text-success border-success border-3 rounded-round p-3 mb-3 mt-1"></i>--}}
                <img src="{{ asset('explore-assets/images/logo-pesona.png') }}" alt="" class="img-login p-3 mb-3">
                <h5 class="mb-0">{{ trans('landing.register.easy') }}</h5>
            </div>
            <input type="hidden" name="use" id="select-register" value="0" autocomplete="off">
{{--            <ul class="nav nav-tabs nav-tabs-highlight nav-justified" id="nav-login">--}}
{{--                <li class="nav-item"><a href="#top-justified-tab1" data-id="0" class="nav-link active"--}}
{{--                                        data-toggle="tab">{{ trans('landing.login.use_mail') }}</a></li>--}}
{{--                <li class="nav-item"><a href="#top-justified-tab2" data-id="1" class="nav-link"--}}
{{--                                        data-toggle="tab">{{ trans('landing.login.use_phone') }}</a>--}}
{{--                </li>--}}
{{--            </ul>--}}
            <div class="form-group form-group-feedback form-group-feedback-left">
                <input type="text" name="name" class="form-control"
                       placeholder="{{trans('landing.register.your_company_name')}}" required>
                <div class="form-control-feedback">
                    <i class="icon-office text-muted"></i>
                </div>
            </div>

            <div class="form-group form-group-feedback form-group-feedback-left input-group mb-1">
                <input type="text" class="form-control"
                       placeholder="{{trans('landing.register.name_on_url')}}" name="domain">
                <div class="form-control-feedback">
                    <i class="icon-sphere text-muted"></i>
                </div>
                <div class="input-group-append">
                    <span class="input-group-text url-last" id="basic-addon2">.{{env('APP_URL')}}</span>
                </div>
            </div>
            <div id="error_domain" class="form-group pb-1"></div>
            <div id="email_append"></div>

            <div class="form-group form-group-feedback form-group-feedback-left" id="telephone" style="display: none">
                <input type="tel" id="phone" class="form-control"
                       placeholder="{{trans('landing.placeholder_phone')}}" autocomplete="off" required disabled>
                <input type="hidden" name="phone">
            </div>

            <div id="e_mail">
                <div class="form-group form-group-feedback form-group-feedback-left">
                    <input type="email" class="form-control" name="email" id="email" autocomplete="off"
                           placeholder="{{trans('landing.login.your_mail')}}" required>
                    <div class="form-control-feedback">
                        <i class="icon-user text-muted"></i>
                    </div>
                </div>
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
            </div>
            <input type="hidden" name="countryCode">
            <div id="error_password" class="form-group pb-1"></div>

            {{--                <div class="form-group text-center text-muted content-divider">--}}
            {{--                    <span class="px-2">Additions</span>--}}
            {{--                </div>--}}

            <div class="form-group">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" id="invalidCheck" required name="agreement"
                               class="form-input-styled" data-fouc>
                        {{trans('landing.register.agree')}} <a href="#" class="term-and-condition"
                                                               data-toggle="modal"
                                                               data-target="#modalTermConditionSignUp">{{trans('landing.register.terms')}}</a>
                    </label>
                </div>
            </div>

            {{--                        <button type="submit" class="btn bg-teal-400 btn-block">Register <i--}}
            {{--                                    class="icon-circle-right2 ml-2"></i></button>--}}
            <button class="btn bg-teal-400 btn-block next" type="button"
                    id="btn-register">{{trans('landing.register.submit')}}</button>
            <div class="form-group text-center text-muted content-divider mt-3">
                <span class="px-2">{{ trans('landing.login.have_login') }}</span>
            </div>
            <div class="form-group">
                <a href="{{ url('/agent/login') }}"
                   class="btn btn-light btn-block">{!! trans('landing.navbar.login') !!}</a>
            </div>
        </div>
    </div>
    {!! Form::close() !!}


    <div class="modal fade modal-faq-premium" id="modalTermConditionSignUp" tabindex="-1" role="dialog"
         aria-labelledby="modalFaqForPremium" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="text-center modal-title">{!! trans('tnc_provider.caption') !!}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="text-align: justify">
                    <ol>
                        <h5>{!! trans('tnc_provider.title') !!}</h5>
                        {!! trans('tnc_provider.description') !!}
                        <ol>
                            @foreach (trans('tnc_provider.points') as $points)
                                <b>
                                    <li>{!! $points['parent'] !!}</li>
                                </b>
                                @if (isset($points['child']))
                                    <ol>
                                        @foreach ($points['child'] as $child)
                                            <li>{!! $child['children'] !!}</li>
                                            @if (isset($child['grandchild']))
                                                <ol type="a">
                                                    @foreach ($child['grandchild'] as $grandchild)
                                                        <li>{!! $grandchild !!}</li>
                                                    @endforeach
                                                </ol>
                                            @endif
                                        @endforeach
                                    </ol>
                                @endif
                            @endforeach
                        </ol>
                        <p>
                            {!! trans('tnc_provider.agreement') !!}
                        </p>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/demo_pages/login.js') }}"></script>
    <script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/forms/styling/switchery.min.js') }}"></script>
    {{--    <script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/demo_pages/extra_sweetalert.js') }}"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="{{asset('js/validation-hnd.js')}}"></script>
    <script>

        var valid = false;
        var inputPhoneNumber = document.querySelector('#phone');
        var itilNumber = window.intlTelInput(inputPhoneNumber, {
            preferredCountries: ['id'],
            separateDialCode: true,
            allowDropdown: false,
            utilsScript: "build/js/utils.js",
        });
        var companyName = $('input[name="company-name"]'),
            domainName = $('input[name="domain"]'),
            phoneNumber = $('#phone');

        function checkValue() {
            $(document).find('.form-group input').each(function (i, e) {
                let t = $(this);
                if (t.val() !== '') {
                    // t.addClass('filled')
                    loadingStart()
                } else {
                    e.preventDefault();
                    // t.removeClass('filled')
                }
            })
        }

        $(document).on('change keydown', 'input[name="email"]', function () {
            checkMail($('input[name=email]').val());
        });

        $(document).on('click', '#nav-login', function () {
            let select = $(this).find('a.active').data('id');
            $('#select-register').val(select);
            $(document).find('span.error').remove();
            let html = '';
            // $(document).find('p.rm-error').remove();
            if (select == 0) {
                html = '<div id="e_mail">' +
                    '       <div class="form-group form-group-feedback form-group-feedback-left">' +
                    '           <input type="email" class="form-control" name="email" id="email" autocomplete="off" placeholder="{{trans('landing.login.your_mail')}}" required>' +
                    '           <div class="form-control-feedback">' +
                    '               <i class="icon-user text-muted"></i>' +
                    '           </div>' +
                    '       </div>' +
                    '       <div class="form-group form-group-feedback form-group-feedback-left input-group">' +
                    '           <input type="password" class="form-control" name="password" autocomplete="off" placeholder="{{trans('landing.login.password')}}" required>' +
                    '           <div class="form-control-feedback">' +
                    '               <i class="icon-lock2 text-muted"></i>' +
                    '           </div>' +
                    '           <div class="input-group-append">' +
                    '               <span class="input-group-text eye-icon" id="eye"><i class="icon-eye text-muted"></i></span>' +
                    '           </div>' +
                    '       </div>' +
                    '   </div>';
                $(document).find('#telephone').hide();
                $(document).find('input[type=tel]').prop('disabled', true);
            } else {

                $(document).find('#telephone').show();
                $(document).find('input[type=tel]').prop('disabled', false).val('');
                $(document).find('#e_mail').remove();
            }
            $('#email_append').append(html);
        });

        // $(document).on('change cut keydown paste input keypress', 'input[name="domain"]', function (e) {
        //     $(this).closest('.form-group').find('span.error').remove();
        //     // validateDomain();
        //     let t = $(this);
        //     e = (e) ? e : window.event;
        //     var charCode = (e.which) ? e.which : e.keyCode;
        //     // if (charCode === 13) {
        //     // }else{
        //     //     t.data('timer', setTimeout(validateDomain, 500))
        //     // }
        // });
        // $('input[name="domain"]').on({
        //     keydown: function (e) {
        //         if (e.which === 32)
        //             return false;
        //     },
        //     change: function () {
        //         this.value = this.value.replace(/\s/g, "");
        //     }
        // });

        $(document).on('keypress change search input paste cut', 'input, select', function (evt) {
            $(this).closest('.form-group').find('span.error').remove();
            $(document).find('span.error').remove();
            let t = $(this);
            if ($(this).attr('name') === 'domain') {
                $('#error_domain').find('span.error').remove();
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                // if (charCode > 31 && (charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122)) {
                //     $(this).closest('.form-group').append('<label class="error">Alphabhet Numeric Only</label>')
                //     return false;
                // }
                $(this).val($(this).val().toLowerCase().replace(/\s/g, ''))
                clearTimeout($(this).data('timer'));
                if (charCode === 13) {
                    validateDomain();
                } else {
                    $(this).data('timer', setTimeout(validateDomain, 500));
                }

            }
            if ($(this).attr('name') === 'email') {
                clearTimeout($(this).data('timer'));
                if (charCode === 13) {
                    validateEmail();
                } else {
                    $(this).data('timer', setTimeout(validateEmail, 500));
                }
            }
            if ($(this).attr('type') === 'tel') {
                clearTimeout($(this).data('timer'));
                if (charCode === 13) {
                    validatePhone();
                } else {
                    $(this).data('timer', setTimeout(validatePhone, 500));
                }
            }
        });

        function validateDomain() {
            $(document).find('#error_domain span.error').remove();
            let t = $('input[name=domain]');
            $.ajax({
                url: '{{route('memoria.validate.domain')}}',
                data: {domain: t.val()},
                success: function (data) {

                },
                error: function (e) {
                    if (e.status === 422) {
                        $.each(e.responseJSON.errors, function (i, e) {
                            if (i == 'domain') {
                                $(document).find('#error_domain').closest('.form-group').append('<span class="form-text text-danger error">' + e[0] + '</span>');
                            } else {
                                $(document).find('input[name=' + i + ']').closest('.form-group').append('<span class="form-text text-danger error">' + e[0] + '</span>');
                            }
                        })
                    }
                }
            })
        }

        function validateEmail() {
            let t = $('input[name=email]');
            $.ajax({
                url: '{{route('memoria.validate.email')}}',
                data: {email: t.val()},
                success: function (data) {

                },
                error: function (e) {
                    t.closest('.form-group').find('span.error').remove();
                    if (e.status === 422) {
                        $.each(e.responseJSON.errors, function (i, e) {
                            $(document).find('input[name=' + i + ']').closest('.form-group').append('<span class="form-text text-danger error">' + e[0] + '</span>');
                        })
                    }
                }
            })
        }

        function validatePhone() {
            let t = $('input[name=phone]');
            $.ajax({
                url: '{{route('memoria.validate.phone')}}',
                data: {phone: t.val()},
                success: function (data) {

                },
                error: function (e) {
                    t.closest('.form-group').find('span.error').remove();
                    if (e.status === 422) {
                        $.each(e.responseJSON.errors, function (i, e) {
                            $(document).find('input[name=' + i + ']').closest('.form-group').append('<span class="form-text text-danger error">' + e[0] + '</span>');
                        })
                    }
                }
            })
        }

        $(document).on('click', '#btn-register', function () {
            loadingStart();
            let t = $(this);
            t.closest('form').find('span.error').remove();
            let codeCountry = itilNumber.getSelectedCountryData().iso2;
            $('input[name="countryCode"]').val(codeCountry);
            // let dataSucceess = $('.next').attr('data-validation');
            $.ajax({
                method: 'POST',
                url: '{{ url('api/register') }}',
                data: $('#regForm').serialize(),
                dataType: 'json',
                success: function (data) {
                    loadingFinish();
                    swalInit({
                        type: 'success',
                        title: 'Success',
                        text: data.result.message,
                    })
                        .then(
                            setTimeout(function () {
                                window.location.href = data.result.redirect
                            }, 2000)
                        )
                },
                error: function (e) {
                    if (e.status !== undefined && e.status === 422) {
                        let errors = e.responseJSON.errors;
                        $.each(errors, function (i, el) {
                            if (i == 'domain') {
                                $(document).find('#error_domain').append('<span class="form-text text-danger error">' + el[0] + '</span>');
                            } else if (i == 'password') {
                                $('#error_password').append('<span class="form-text text-danger error">' + el[0] + '</span>');
                            } else {
                                t.closest('form').find('input[name=' + i + ']').closest('.form-group').append('<span class="form-text text-danger error">' + el[0] + '</span>');

                            }
                        })
                    }
                    loadingFinish();
                    toastr.error(e.responseJSON.message, '{{__('general.whoops')}}')
                }
            });
        });


        // Prevent Char in Input Type Number
        $(document).on('change keydown input', 'input[type="number"], input[type="tel"], input[type="checkbox"], .number', function onChange(e) {
            let inputName = $('input[name="company-name"]').val(),
                domainInput = $('input[name="domain"]').val(),
                phoneInput = $('#phone').val(),
                checkboxAgreement = $('input[type="checkbox"]'),
                finalPhone = itilNumber.getNumber().slice(1);
            $('input[name="phone"]').val(finalPhone);
            // phoneValue = $("#phone").intlTelInput("getNumber");;

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

        $(document).on('click', '#eye i', function () {
            $(this).toggleClass('icon-eye-blocked text-muted');
            let input = $('input[name="password"]');
            if (input.attr('type') == 'password') {
                input.attr('type', 'text');
            } else {
                input.attr('type', 'password')
            }
        });
    </script>
@stop
