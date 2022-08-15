@extends('auth.new-login.auth_layout')

@section('title')
    {{ trans('landing.register.forgot_password') }}
@stop

@section('styles')
    <style>
        .toggle-password {
            cursor: pointer;
        }

        /* Disable spin button */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        .btn {
            width: 100%;
        }
    </style>
@stop

@section('content')
    <h3 class="text-center">
        <strong>{{ trans('landing.register.forgot_password') }}</strong>
    </h3>
    {!! Form::open(['id'=>'regForm','autocomplete'=>'off']) !!}
    {{--        <form id="regForm" autocomplete="off">--}}
    <div class="form-group">
        <div class="form-group">
            <input type="text" class="form-control" name="email" required>
            <label class="form-control-placeholder"
                   for="mail">{{trans('landing.login.your_mail')}}</label>
        </div>
        <label for="mail" class="error" id="mail-error"
               data-validation="{{ trans('landing.login.mail_empty') }}"></label>
        <div class="form-group">
            <button class="next btn btn-primary" type="button" id="submitButton" onclick="sendMail()"
                    data-validation="{{trans('landing.link_mail_sent')}}">{{trans('landing.register.send_mail')}}
            </button>
        </div>
    </div>
    {!! Form::close() !!}
@stop

@section('script')
    <script>
        var valid = true;
        var inpuMail = $('input[name="mail"]'),
            loading = $('.loading');
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
            $('label.error').remove();
            loadingStart();
            let dataSuccess = $('.next').attr('data-validation');
            $.ajax({
                method: 'POST',
                data: $('#regForm').serialize(),
                url: "/password/email",
                dataType: 'json',
                success: function (data) {
                    loadingFinish();
                    Swal.fire({
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
                            $(document).find('input[name=' + i + ']').parent().append('<label class="error">' + e[0] + '</label>');
                        })
                    } else if (e.status === 403) {
                        $(document).find('input[name=email]').parent().append('<label class="error">' + e.responseJSON.result.email + '</label>');
                        toastr.error(e.responseJSON.result.email);
                    } else {
                        $(document).find('input[name=email]').parent().append('<label class="error">' + e.responseJSON.message + '</label>');
                        toastr.error(e.responseJSON.message);
                    }
                }
            })
        }
    </script>
@stop