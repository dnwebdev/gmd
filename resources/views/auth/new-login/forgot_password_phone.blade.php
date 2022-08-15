@extends('auth.new-login.auth_layout')

@section('title')
    {{ trans('landing.register.forgot_password') }}
@stop
@section('styles')
@stop

@section('content')
    {!! Form::open(['id'=>'regForm','autocomplete'=>'off', 'class' => 'login-form']) !!}
    <div class="card mb-0">
        <a id="backBtn" class="btn" href="{{ url('/') }}">
            <i class="icon-home2"></i>
        </a>
        <div class="card-body">
            <div class="text-center mb-3">
{{--                <i class="icon-spinner11 icon-2x text-primary border-primary border-3 rounded-round p-3 mb-3 mt-1"></i>--}}
                <img src="{{ asset('landing/img/Logo-Gomodo.png') }}" alt="" class="img-login p-3 mb-3 mt-1">
                <h5 class="mb-0">{{ trans('landing.register.forgot_password') }}</h5>
{{--                <span class="d-block text-muted">We'll send you instructions in email</span>--}}
            </div>
            <div class="form-group form-group-feedback form-group-feedback-right">
                <input type="tel" id="phone" class="form-control" placeholder="{{trans('landing.placeholder_phone')}}"
                       required>
                <div class="form-control-feedback">
                    <i class="icon-phone2 text-muted"></i>
                </div>
                <input type="hidden" name="phone">
            </div>
            <span for="mail" class="form-text text-danger error" id="mail-error"
                  data-validation="{{ trans('landing.login.phone_empty') }}"></span>
            <div class="form-group">
                <button class="btn bg-primary btn-block next" type="button" id="submitButton" onclick="sendMail()"
                        data-validation="{{trans('landing.link_sent')}}"><i
                            class="icon-spinner11 mr-2"></i> {{trans('landing.login.sent_link_phone')}}
                </button>
            </div>
            <span class="form-text text-center text-muted">{!! trans('landing.login.back_to') !!} <a href="{{ url('agent/login') }}">{!! trans('landing.login.title') !!}</a> {!! trans('landing.login.or_login') !!} <a href="{{ url('agent/register') }}">{!! trans('landing.login.sign_up') !!}</a></span>
        </div>
    </div>
    {!! Form::close() !!}
@stop

@section('script')
    <script src="{{asset('js/validation-hnd.js')}}"></script>
    <script>
        // var valid = true;
        $(document).keypress(
            function (event) {
                if (event.which == '13') {
                    event.preventDefault();
                }
            }
        );
        var inpuMail = $('input[name="mail"]'),
            loading = $('.loading');
        var mailLabel = $('#mail-error');
        var mailError = mailLabel.attr('data-validation');

        var inputPhoneNumber = document.querySelector('#phone');
        var itilNumber = window.intlTelInput(inputPhoneNumber, {
            preferredCountries: ['id'],
            separateDialCode: true,
            allowDropdown: false,
            utilsScript: "build/js/utils.js",
        });

        $(document).on('keyup keydown input cut copy paste change', '#phone', function (e) {
            let thisValue = $(this).val();
            finalPhone = itilNumber.getNumber().slice(1);
            console.log(finalPhone);
            $('input[name="phone"]').val(finalPhone);

            if (checkMinimumPhone(thisValue)) {
                $(this).addClass('filled');
                valid = false;
                // $('#submitButton').removeAttr('disabled');
            } else {
                $(this).removeClass('filled');
                // $('#submitButton').attr('disabled', true);
            }
        });

        function sendMail() {
            $('span.error').remove();
            loadingStart();
            let dataSuccess = $('.next').attr('data-validation');
            $.ajax({
                method: 'POST',
                url: '{{ url('password/request/auth') }}',
                data: $('#regForm').serialize(),
                dataType: 'json',
                success: function (data) {
                    loadingFinish();
                    swalInit({
                        type: 'success',
                        title: 'Success!',
                        text: dataSuccess
                    })
                    .then(
                        setTimeout(function () {
                            window.location.href = data.result.redirect
                        }, 1000)
                    )
                },
                error: function (e) {
                    loadingFinish();
                    if (e.status === 422) {
                        $.each(e.responseJSON.errors, function (i, e) {
                            $(document).find('input[name=' + i + ']').parent().append('<span class="form-text text-danger error">' + e[0] + '</span>');
                            toastr.error(e[0]);
                        })
                    } else {
                        $(document).find('input[name=phone]').parent().append('<span class="form-text text-danger error">' + e.responseJSON.message + '</span>');
                        toastr.error(e.responseJSON.message);
                    }
                    toastr.remove()
                }
            })
        }
    </script>
@stop