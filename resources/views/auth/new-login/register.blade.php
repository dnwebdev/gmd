@extends('auth.new-login.auth_layout')

@section('title')
    {{ trans('landing.register.easy') }}
@stop

@section('styles')
    <style>
        .login-form {
            width: 30rem;
        }

        form.otp {
            width: 20rem;
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
            background-color: #2196f3;
        }

        .term-and-condition {
            color: #2196f3;
        }

        .term-and-condition:hover {
            color: darkblue;
        }

        .nav{
            padding-right: 0;
        }

        .form-group {
            position: relative;
        }
        .form-group .error {
            position: absolute;
            bottom: -20px;
            left: 33px;
        }

        .form-group-feedback-left .select2-selection--single {
            padding-left: 2rem;
            color: #999999!important
        }

        /* OTP start */
        #otp_container {
            max-width: 350px;
        }

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
    {!! Form::open(['id'=>'regForm','autocomplete'=>'off', 'class' => 'login-form', 'novalidate']) !!}
    <div class="card mb-0">
        <a id="backBtn" class="btn" href="{{ url('/') }}">
            <i class="icon-home2"></i>
        </a>
        <div class="card-body">
            <div class="text-center mb-3">
{{--                <i class="icon-user-plus icon-2x text-primary border-primary border-3 rounded-round p-3 mb-3 mt-1"></i>--}}
                <img src="{{ asset('landing/img/Logo-Gomodo.png') }}" alt="" class="img-login p-3 mb-3 mt-1">
                <h5 class="mb-0 hide-for-otp">{{ trans('landing.register.easy') }}</h5>
                <h5 class="mb-0 show-for-otp" style="display: none">{!! trans('landing.login.verification_title') !!}</h5>
                <div class="show-for-otp text-muted" style="display: none">{!! trans('landing.login.verification_desc') !!}</div>
            </div>

            <div class="content-register hide-for-otp">
                <input type="hidden" name="use" id="select-register" value="0" autocomplete="off">
                <div class="form-group form-group-feedback form-group-feedback-left">
                    <input type="text" name="name" class="form-control"
                        placeholder="{{trans('landing.register.your_company_name')}}" required>
                    <div class="form-control-feedback">
                        <i class="icon-office text-muted"></i>
                    </div>

                </div>

                <div class="form-group form-group-feedback form-group-feedback-left input-group mb-1">
                    <input type="text" class="form-control alpha-num-only"
                        placeholder="{{trans('landing.register.name_on_url')}}" name="domain" required>
                    <div class="form-control-feedback">
                        <i class="icon-sphere text-muted"></i>
                    </div>
                    <div class="input-group-append">
                        <span class="input-group-text url-last" id="basic-addon2">.{{env('APP_URL')}}</span>
                    </div>
                </div>
                <div id="error_domain" class="form-group"></div>
                <div id="email_append"></div>

                <div class="form-group form-group-feedback form-group-feedback-left" id="telephone" style="display: none">
                    <input type="tel" id="phone" class="form-control"
                        placeholder="{{trans('landing.placeholder_phone')}}" autocomplete="off" required disabled>
                    <input type="hidden" name="phone">
                </div>

                <div class="form-group form-group-feedback form-group-feedback-left">
                    <select class="form-control select2-city required" id="city_id" name="city_id" data-placeholder="{{trans('landing.register.city')}}" required>
                        <option selected disabled>{{trans('landing.register.city')}}</option>
                    </select>
                    <div class="form-control-feedback">
                        <i class="icon-city text-muted"></i>
                    </div>
                </div>

                <div class="form-group form-group-feedback form-group-feedback-left">
                    <input type="text" class="form-control number" name="whatsapp" autocomplete="off"
                            placeholder="{{trans('landing.register.whatsapp')}}" required>
                    <div class="form-control-feedback">
                        <i class="icon-phone2 text-muted"></i>
                    </div>
                </div>

                <div class="form-group form-group-feedback form-group-feedback-left">
                    <input type="email" class="form-control" name="email" id="email" autocomplete="off"
                            placeholder="{{trans('landing.register.your_mail')}}">
                    <div class="form-control-feedback">
                        <i class="icon-user text-muted"></i>
                    </div>
                </div>

                <div class="form-group form-group-feedback form-group-feedback-left">
                    <select class="form-control select2-without-search required" name="ownership_status" data-placeholder="{!! trans('gamification.business-type.type') !!}" required>
                        <option selected disabled>{!! trans('gamification.business-type.type') !!}</option>
                        <option value="corporate">{{trans('landing.register.corporate')}}</option>
                        <option value="personal">{{trans('landing.register.personal')}}</option>
                    </select>
                    <div class="form-control-feedback">
                        <i class="icon-briefcase text-muted"></i>
                    </div>
                </div>

                {{-- <div class="form-group form-group-feedback form-group-feedback-left input-group">
                    <input type="password" class="form-control" name="password" autocomplete="off"
                            placeholder="{{trans('landing.login.password')}}" required>
                    <div class="form-control-feedback">
                        <i class="icon-lock2 text-muted"></i>
                    </div>
                    <div class="input-group-append">
                        <span class="input-group-text eye-icon" id="eye"><i class="icon-eye text-muted"></i></span>
                    </div>
                </div> --}}
                {{-- <input type="hidden" name="countryCode"> --}}
                <div id="error_password" class="form-group pb-1"></div>

                {{--                <div class="form-group text-center text-muted content-divider">--}}
                {{--                    <span class="px-2">Additions</span>--}}
                {{--                </div>--}}

                <div class="form-group">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" id="invalidCheck" required name="agreement"
                                class="form-input-styled" data-fouc required>
                            {{trans('landing.register.agree')}} <a href="#" class="term-and-condition"
                                                                data-toggle="modal"
                                                                data-target="#modalTermConditionSignUp">{{trans('landing.register.terms')}}</a>.
                        </label>
                    </div>
                </div>

                {{--                        <button type="submit" class="btn bg-teal-400 btn-block">Register <i--}}
                {{--                                    class="icon-circle-right2 ml-2"></i></button>--}}
                <button class="btn bg-teal-400 btn-block next" type="submit"
                        id="btn-register">{{trans('landing.register.submit')}}</button>
                <div class="form-group text-center text-muted content-divider mt-3">
                    <span class="px-2">{{ trans('landing.login.have_login') }}</span>
                </div>
                <div class="form-group">
                    <a href="{{ url('/agent/login') }}"
                    class="btn btn-light btn-block">{!! trans('landing.navbar.login') !!}</a>
                </div>
            </div>
            <div class="content-otp show-for-otp">
                <div class="form-group form-group-feedback show-for-otp mx-auto" id="otp_container" style="display: none">
                    <div method="get" class="digit-group row mx-4" data-group-name="digits" data-autosubmit="true" autocomplete="off">
                        <div class="col-3">
                            <input type="number" class="form-control digit-otp mx-auto" id="digit-1" name="digit-1" data-next="digit-2" />
                        </div>
                        <div class="col-3">
                            <input type="number" class="form-control digit-otp mx-auto" id="digit-2" name="digit-2" data-next="digit-3" data-previous="digit-1" />
                        </div>
                        <div class="col-3">
                            <input type="number" class="form-control digit-otp mx-auto" id="digit-3" name="digit-3" data-next="digit-4" data-previous="digit-2" />
                        </div>
                        <div class="col-3">
                            <input type="number" class="form-control digit-otp mx-auto" id="digit-4" name="digit-4" data-next="digit-5" data-previous="digit-3" />
                        </div>
                    </div>
                </div>
                <div class="form-group show-for-otp" style="display: none">
                    <button id="submit_otp"
                            class="btn btn-primary btn-block">{!! trans('landing.login.verify') !!}</button>
                </div>
                <div class="form-group text-center text-muted show-for-otp" style="display: none">
                    <div class="px-2 timer-otp">{!! trans('landing.login.wait_for_resend') !!}</div>
                    <div class="px-2 resend-otp" style="display: none">{{ trans('landing.login.not_recieve_code') }}</div>
                    <a href="#" id="resend_otp" class="resend-otp" style="display: none">{{ trans('landing.login.resend') }}</a>
                </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="{{asset('js/validation-hnd.js')}}"></script>
    <script>

        // Init -------------------------------------------------------------------------------------------
        digit_filled_check()
        checkValue()
        
        $(".select2-without-search").select2({
            minimumResultsForSearch: -1
        })
        $(".select2-city").select2({
            ajax: {
                url: "{{ url('api/search-city?id_country=102') }}",
                dataType: 'json',
                delay: 250,
                type: 'GET',
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page || 1
                    }
                },
                processResults: function(response, params) {
                    params.page = params.page || 1

                    let data = response.data.map(function(data){
                        return {
                            id: data.id_city,
                            text: data.city_name
                        }
                    })
                    return {
                        results: data,
                        pagination: {
                            more: response.hasNext
                        }
                    }
                },
                cache: true
            },
            minimumInputLength: 3,
            language: {
                searching: function () {
                    return '{{ trans("general.select2.searching") }}';
                },
                noResults: function () {
                    return '{{ trans("general.select2.no_result") }}';
                },
                inputTooShort: function(e) {
                    // var t = e.minimum - e.input.length;
                    // return "Masukkan " + t + " huruf lagi";
                    return '{{ trans("general.select2.searching") }}'
                },
                errorLoading: function() {
                    return '{{ trans("general.select2.no_result") }}';
                },
            }
        });

        // Utils.js start
        // var valid = false;
        // var inputPhoneNumber = document.querySelector('#phone');
        // var itilNumber = window.intlTelInput(inputPhoneNumber, {
        //     preferredCountries: ['id'],
        //     separateDialCode: true,
        //     allowDropdown: false,
        //     utilsScript: "build/js/utils.js",
        // });
        // var companyName = $('input[name="company-name"]'),
        //     domainName = $('input[name="domain"]'),
        //     phoneNumber = $('#phone');
        // Utils.js end

        // Event -------------------------------------------------------------------------------------------

        $(document).on('change keydown', 'input[name="email"]', function () {
            checkMail($('input[name=email]').val());
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

        $(document).on('keyup', '[name=whatsapp]', function() {
            let this_val = $(this).val();
            let this_val_length = this_val.length;

            // 0 to +62 for numeric val
            if ($.isNumeric(this_val) && this_val.charAt(0) == 0) {
                $(this).val('+62' + this_val.slice(1, this_val_length))
            }
        })

        $(document).on('submit', 'form#regForm', function (e) {
            e.preventDefault()
            let form = $(this);
            form.find('span.error').remove();

            // Utils.js start
            // let codeCountry = itilNumber.getSelectedCountryData().iso2;
            // $('input[name="countryCode"]').val(codeCountry);
            // Utils.js end
            // let dataSucceess = $('.next').attr('data-validation');

            let token_id = $(document).find('[name=_token]').val(), 
                whatsapp_val = $(document).find('[name=whatsapp]').val(),
                agreement = $(document).find('[name=agreement]'),
                indexOf_id_city = whatsapp_val.indexOf('62');
            var base_form_data1 = {
                _token: token_id
            };

            if (indexOf_id_city !== -1 && indexOf_id_city < 2) {
                base_form_data1.phone_code = whatsapp_val.slice(indexOf_id_city, indexOf_id_city + 2);
                base_form_data1.phone = whatsapp_val.slice(indexOf_id_city + 2);
            } else {
                base_form_data1.phone_code = '';
                base_form_data1.phone = whatsapp_val;
            }
            
            if ($(this).hasClass('otp')) {
                // Ajax for otp
                var otp_val = '';
                $(document).find('.digit-otp').each( function (i, e) {
                    otp_val += $(e).val();
                })

                var base_form_data2 = {
                    otp: otp_val,
                    userLang:"{{$local}}"
                }
                let form_data = Object.assign(base_form_data1, base_form_data2)
                register_ajax('{{ url("api/validate-otp") }}', form_data, 'otp')
            } else {
                // Ajax for register
                var base_form_data2 = {
                    name: $(document).find('[name=name]').val(),
                    domain: $(document).find('[name=domain]').val(),
                    email: $(document).find('[name=email]').val(),
                    city_id: $(document).find('[name=city_id]').val(),
                    agreement: agreement.is(':checked') ? 1 : null,
                    ownership_status: $(document).find('[name=ownership_status]').val(),
                    userLang:"{{$local}}"
                }
                let form_data = Object.assign(base_form_data1, base_form_data2)
                register_ajax('{{ url("api/new-register") }}', form_data)
            }

        });


        // Prevent Char in Input Type Number when typing
        // $(document).on('change keydown input', 'input[type="number"], input[type="tel"], input[type="checkbox"], .number', function onChange(e) {
        //     // Utils.js start
        //     // let inputName = $('input[name="company-name"]').val(),
        //         // domainInput = $('input[name="domain"]').val(),
        //         // phoneInput = $('#phone').val(),
        //         // checkboxAgreement = $('input[type="checkbox"]'),
        //         // finalPhone = itilNumber.getNumber().slice(1);
                
        //     // $('input[name="phone"]').val(finalPhone);
        //     // Utils.js end

        //     // phoneValue = $("#phone").intlTelInput("getNumber");;

        //     if (e.metaKey == false) { // Enable metakey
        //         if (e.keyCode > 13 && e.keyCode < 48 && e.keyCode != 39 && e.keyCode != 37 && e.keyCode != 118 || e.keyCode > 57) {
        //             e.preventDefault(); // Disable char. Enable arrow
        //         }
        //         ;
        //         if (e.shiftKey === true) { // Disable symbols
        //             if (e.keyCode > 46 && e.keyCode < 65) {
        //                 e.preventDefault();
        //             }
        //         }
        //     }
        // });

        $(document).on('click', '#resend_otp', function(){
            let token_id = $(document).find('[name=_token]').val(), 
                whatsapp_val = $(document).find('[name=whatsapp]').val(),
                indexOf_id_city = whatsapp_val.indexOf('62');

            var form_data = {
                _token: token_id,
                phone_code: whatsapp_val.slice(indexOf_id_city, indexOf_id_city + 2),
                phone: whatsapp_val.slice(indexOf_id_city + 2)
            }

            register_ajax('{{ url("api/send-otp") }}', form_data, 'resend-otp')
            $(document).find('.digit-otp').val('').removeClass('filled')
            digit_filled_check()
        })
        
        // Disable Paste in input type number
        $(document).on('paste', 'input[type="number"], input[type="tel"], .number', function (e) {
            e.preventDefault();
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

        function validateDomain() {
            $(document).find('#error_domain span.error').remove();
            let t = $('input[name=domain]');
            $.ajax({
                url: '{{route('memoria.validate.domain')}}',
                data: {domain: t.val(),userLang:"{{$local}}"},
                success: function (data) {

                },
                error: function (e) {
                    if (e.status === 422) {
                        $.each(e.responseJSON.errors, function (i, e) {
                            if (i == 'domain') {
                                $(document).find('#error_domain').closest('.form-group').append('<span class="form-text text-danger error">' + e[0] + '</span>');
                            }else{
                                $(document).find('input[name=' + i + ']').closest('.form-group').append('<span class="form-text text-danger error">' + e[0] + '</span>');
                            }
                        })
                    }
                }
            })
        }

        function validateEmail() {
            let t = $('input[name=email]');
            if (t.val() !== '') { // Email not mandatory
                $.ajax({
                    url: '{{route('memoria.validate.email')}}',
                    data: {email: t.val(),userLang:"{{$local}}"},
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
        }

        // Validate phone number start
        // function validatePhone(){
        //     let t = $('input[name=phone]');
        //     $.ajax({
        //         url: '{{route('memoria.validate.phone')}}',
        //         data: {phone: t.val()},
        //         success: function (data) {

        //         },
        //         error: function (e) {
        //             t.closest('.form-group').find('span.error').remove();
        //             if (e.status === 422) {
        //                 $.each(e.responseJSON.errors, function (i, e) {
        //                     $(document).find('input[name=' + i + ']').closest('.form-group').append('<span class="form-text text-danger error">' + e[0] + '</span>');
        //                 })
        //             }
        //         }
        //     })
        // }
        // Validate phone number end

        // General Function -------------------------------------------------------------------------------------------

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

        function register_ajax(url, form_data, type) {
            loadingStart();
            $.ajax({
                method: 'POST',
                url: url,
                data: form_data,
                dataType: 'json',
                success: function (data) {
                    loadingFinish();
                    console.log(data)
                    if (type === 'otp') {
                        window.location.href = '/company/dashboard'
                    } else if (type === 'resend-otp') {
                        countingdown(22)
                    } else {
                        swalInit({
                            type: 'success',
                            title: '{{ trans("landing.register.success_title") }}',
                            text: '{{ trans("landing.register.success_desc") }}',
                        })
                        .then(() => {
                                $(document).find('.show-for-otp').show()
                                $(document).find('.hide-for-otp').hide()
                                $(document).find('#regForm').addClass('otp')
                                $(document).find('#phone_number_otp').text( '+' + form_data.phone_code + form_data.phone)
                                countingdown(22)
                            }
                        )
                    }
                },
                error: function (e) {
                    if (e.status !== undefined && e.status === 422) {
                        let errors = e.responseJSON.errors;
                        $.each(errors, function (i, el) {
                            if (i == 'domain') {
                                $(document).find('#error_domain').append('<span class="form-text text-danger error">' + el[0] + '</span>');
                            } else if(i == 'password'){
                                $(document).find('#error_password').append('<span class="form-text text-danger error">' + el[0] + '</span>');
                            } else if (i == 'phone' || i == 'phone_code') {
                                let errorNumberEl = $(document).find('input[name=whatsapp]').closest('.form-group').find('.error');
                                if(errorNumberEl.length > 0) {
                                    if (i === 'phone') {
                                        errorNumberEl.remove()
                                        $(document).find('input[name=whatsapp]').closest('.form-group').append('<span class="form-text text-danger error">' + el[0] + '</span>');
                                    }
                                } else {
                                    $(document).find('input[name=whatsapp]').closest('.form-group').append('<span class="form-text text-danger error">' + el[0] + '</span>');
                                }
                            } else {
                                $(document).find('form').find('[name=' + i + ']').closest('.form-group').append('<span class="form-text text-danger error">' + el[0] + '</span>');
                            }
                        })
                    } else {
                        console.log(e)
                    }
                    loadingFinish();
                    toastr.error(e.responseJSON.message, '{{__('general.whoops')}}')
                }
            });
        }

        function checkValue() {
            $(document).find('.form-group input').each(function (i, e) {
                let t = $(this);
                if (t.val() !== '') {
                    t.addClass('filled')
                    // loadingStart()
                } else {
                    // e.preventDefault();
                    t.removeClass('filled')
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
    </script>
@stop
