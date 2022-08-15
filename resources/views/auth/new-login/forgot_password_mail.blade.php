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
                <input type="email" name="email" class="form-control" placeholder="{{trans('landing.login.your_mail')}}" required>
                <div class="form-control-feedback">
                    <i class="icon-mail5 text-muted"></i>
                </div>
            </div>
            <span for="mail" class="form-text text-danger error" id="mail-error" data-validation="{{ trans('landing.login.mail_empty') }}"></span>
            <div class="form-group">
                <button class="btn bg-primary btn-block next" type="button" id="submitButton" onclick="sendMail()" data-validation="{{trans('landing.link_mail_sent')}}"><i class="icon-spinner11 mr-2"></i> {{trans('landing.register.send_mail')}}
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
        var valid = true;
        var inpuMail = $('input[name="mail"]');
        var mailLabel = $('#mail-error');
        var mailError = mailLabel.attr('data-validation');

        $(document).on('keyup keydown input cut copy paste change', 'input[name="email"]', function (e) {
            let thisValue = $(this).val();
            if (checkMail(thisValue)) {
                $(this).addClass('filled');
                // $('#submitButton').removeAttr('disabled');
            } else {
                $(this).removeClass('filled');
                // $('#submitButton').attr('disabled', true);
            }
        });

        $(document).keypress(
            function (event) {
                if (event.which == '13') {
                    event.preventDefault();
                }
            }
        );

        function sendMail() {
            $('span.error').remove();
            loadingStart();
            let dataSuccess = $('.next').attr('data-validation');
            $.ajax({
                method: 'POST',
                data: $('#regForm').serialize(),
                url: "/password/email",
                dataType: 'json',
                success: function (data) {
                    loadingFinish();
                    swalInit({
                        type: 'success',
                        title: 'Success!',
                        text: dataSuccess
                    }).then(
                        setTimeout(function () {
                            window.history.back()
                        }, 3000)
                    )
                },
                error: function (e) {
                    loadingFinish();
                    if (e.status === 422) {
                        $.each(e.responseJSON.errors, function (i, e) {
                            $(document).find('input[name=' + i + ']').parent().append('<span class="form-text text-danger error">' + e[0] + '</span>');
                            toastr.error(e[0])
                        });
                    } else if(e.status === 403){
                        $(document).find('input[name=email]').parent().append('<span class="form-text text-danger error">' + e.responseJSON.result.email + '</span>');
                        toastr.error(e.responseJSON.result.email);
                    } else {
                        $(document).find('input[name=email]').parent().append('<span class="form-text text-danger error">' + e.responseJSON.message + '</span>');
                        toastr.error(e.responseJSON.message);
                    }
                }
            })
        }


    </script>
@stop