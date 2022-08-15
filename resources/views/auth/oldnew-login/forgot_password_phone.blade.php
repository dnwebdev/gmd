@extends('auth.new-login.auth_layout')

@section('title')
    {{ trans('landing.register.forgot_password') }}
@stop

@section('styles')
    <style>
        .btn {
            width: 100%;
        }

        .iti {
            width: 100%;
        }

        .iti.iti--allow-dropdown, .iti.iti--allow-dropdown input {
            width: 100%;
        }

        #phone {
            width: 100%;
        }
    </style>
@stop

@section('content')
    <h3 class="text-center"><strong>{{ trans('landing.register.forgot_password') }}</strong></h3>
    {!! Form::open(['id'=>'regForm','autocomplete'=>'off']) !!}
    <div class="form-group">
        <input id="phone" class="form-group" type="tel" autocomplete="off"
               placeholder="{{trans('landing.placeholder_phone')}}" required>
        <input type="hidden" name="phone">
        <label for="mail" class="error" id="mail-error"
               data-validation="{{ trans('landing.login.mail_empty') }}"></label>
    </div>
    <button class="next btn btn-primary" type="button" id="submitButton" onclick="sendMail()"
            data-validation="{{trans('landing.link_sent')}}">{{trans('landing.login.sent_link_phone')}}</button>
    {!! Form::close() !!}

@stop

@section('script')
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
            $('label.error').remove();
            loadingStart();
            let dataSuccess = $('.next').attr('data-validation');
            $.ajax({
                method: 'POST',
                url: '{{ url('password/request/auth') }}',
                data: $('#regForm').serialize(),
                dataType: 'json',
                success: function (data) {
                    loadingFinish();
                    Swal.fire({
                        type: 'success',
                        title: 'Success!',
                        text: dataSuccess
                    }).then(
                        window.location.href = data.result.redirect
                    )
                },
                error: function (e) {
                    loadingFinish();
                    if (e.status === 422) {
                        $.each(e.responseJSON.errors, function (i, e) {
                            $(document).find('input[name=' + i + ']').parent().append('<label class="error">' + e[0] + '</label>');
                            toastr.error(e[0]);
                        })
                    } else {
                        $(document).find('input[name=phone]').parent().append('<label class="error">' + e.responseJSON.message + '</label>');
                        toastr.error(e.responseJSON.message);
                    }
                }
            })
        }


    </script>

@stop