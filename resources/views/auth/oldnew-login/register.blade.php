@extends('auth.new-login.auth_layout')

@section('title')
    {{ trans('landing.register.easy') }}
@stop

@section('styles')
    <style>
        .iti.iti--allow-dropdown, .iti.iti--allow-dropdown input {
            width: 100%;
        }

        .form-control:focus ~ label, .form-control:valid ~ label {
            opacity: 1.5;
        }

        #phone {
            width: 100%;
        }

        .iti {
            width: 100%;
        }

        .modal-dialog,
        .modal-content {
            /*height: 80%;*/
            max-height: calc(100vh - 3.5rem);
        }

        .modal-body {
            /* 100% = dialog height, 120px = header + footer */
            max-height: calc(100% - 120px);
            overflow-y: scroll;
            font-size: 13px;
            text-align: justify;
        }

        .text-login {
            font-weight: bold;
            color: #2999fb;
        }

        .text-login:hover {
            text-decoration: none;
        }
    </style>
@stop

@section('content')
    <h3 class="text-center">{{ trans('landing.register.easy') }}</h3>
    <form id="regForm" autocomplete="off">
        <div class="form-group">
            <div class="form-group">
                <input type="text" class="form-control" name="name" required>
                <label class="form-control-placeholder"
                       for="company">{{trans('landing.register.your_company_name')}}</label>
            </div>
            <div class="form-group input-group mb-3">
                <input class="form-control" type="text" name="domain">
                <label class="form-control-placeholder"
                       for="url">{{trans('landing.register.name_on_url')}}</label>
                <div class="input-group-append">
                    <span class="input-group-text url-last" id="basic-addon2">.{{env('APP_URL')}}</span>
                </div>
                <small class="form-text" style="display: block;width: 100%"></small>
            </div>
            <div class="form-group" id="error_phone">
                <input id="phone" class="form-group" type="tel"
                       placeholder="{{trans('landing.placeholder_phone')}}"
                       autocomplete="off" required>
                <input type="hidden" name="phone">
            </div>
            <input type="hidden" name="countryCode">
            <div class="form-group mt-3">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="invalidCheck" required
                           name="agreement">
                    <label class="custom-control-label" for="invalidCheck"
                           id="agreement">{{trans('landing.register.agree')}}
                        <a href="#" class="term-and-condition" data-toggle="modal"
                           data-target="#modalTermConditionSignUp">{{trans('landing.register.terms')}}</a>
                    </label>
                </div>
            </div>
            {{-- MODAL HERE --}}
            <div class="form-group">
                <button class="next btn btn-primary" id="btn-register"
                        data-validation="{{ trans('landing.register.success') }}"
                        type="button">{{trans('landing.register.submit')}}</button>
            </div>
            <div class="form-group text-center">
                <label for="">{{ trans('landing.login.have_login') }}</label> <br> <br>
                <a href="{{ url('/agent/login') }}" class="text-login">{{trans('landing.navbar.login')}}</a>
            </div>
        </div>
    </form>

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
                <div class="modal-body">
                    <ol>
                        <h6>{!! trans('tnc_provider.title') !!}</h6>
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
                    t.addClass('filled')
                } else {
                    t.removeClass('filled')
                }
            })
        }

        $(document).on('change cut keydown paste input keypress', 'input[name="domain"]', function (e) {
            $(this).closest('.form-group').find('label.error').remove();
            // validateDomain();
            let t = $(this);
            if (t.val() !== '') {
                t.addClass('filled');
            } else {
                t.removeClass('filled');
            }
            e = (e) ? e : window.event;
            var charCode = (e.which) ? e.which : e.keyCode;
            // if (charCode === 13) {
            // }else{
            //     t.data('timer', setTimeout(validateDomain, 500))
            // }
        });
        $('input[name="domain"]').on({
            keydown: function (e) {
                if (e.which === 32)
                    return false;
            },
            change: function () {
                this.value = this.value.replace(/\s/g, "");
            }
        });


        // function validateDomain() {
        //     let t = $('input[name=domain]');
        //     $.ajax({
        //         url: '{{route('memoria.validate.domain')}}',
        //         data: {domain: t.val()},
        //         success: function (data) {
        //         },
        //         error: function (e) {
        //             if (e.status === 422) {
        //                 $.each(e.responseJSON.errors, function (i, e) {
        //                     $(document).find('input[name=' + i + ']').closest('.form-group').append('<label class="error">' + e[0] + '</label>');
        //                 })
        //             }
        //         }
        //     })
        // }


        $(document).on('click', '#btn-register', function () {
            $('label.error').remove();
            loadingStart();
            let codeCountry = itilNumber.getSelectedCountryData().iso2;
            $('input[name="countryCode"]').val(codeCountry);
            let dataSucceess = $('.next').attr('data-validation');
            // validateDomain();
            $.ajax({
                method: 'POST',
                url: '{{url('api/register')}}',
                data: $('#regForm').serialize(),
                dataType: 'json',
                success: function (data) {
                    loadingFinish();
                    Swal.fire({
                        type: 'success',
                        title: 'Success',
                        text: dataSucceess
                    }).then(function () {
                        setTimeout(function () {
                            window.location.href = data.result.redirect
                        }, 2000)
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
            })
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
            // if (inputName === '' || domainInput === '' || phoneInput === '' && finalPhone.length <= 11 || checkboxAgreement.prop('checked') === false) {
            //     $('.next').attr('disabled', true);
            // } else {
            //     $('.next').removeAttr('disabled');
            // }
        })
        // Disable Paste in input type number
        $(document).on('paste', 'input[type="number"], input[type="tel"], .number', function (e) {
            e.preventDefault();
        });
    </script>

@stop
