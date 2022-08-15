<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Basic Info</title>
    <link rel="stylesheet" href="landing/bootstrap/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="landing/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="landing/fonts/ionicons.min.css">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i">
    <link rel="stylesheet" href="landing/css/Article-List.css">
    <link rel="stylesheet" href="landing/css/Brands.css">
    <link rel="stylesheet" href="landing/css/Contact-Form-Clean.css">
    <link rel="stylesheet" href="landing/css/Features-Boxed.css">
    <link rel="stylesheet" href="landing/css/Footer-Dark.css">
    <link rel="stylesheet" href="landing/css/Header-Dark.css">
    <link rel="stylesheet" href="landing/css/Highlight-Blue.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css">
    <link rel="stylesheet" href="landing/css/Lightbox-Gallery-1.css">
    <link rel="stylesheet" href="landing/css/Lightbox-Gallery.css">
    <link rel="stylesheet" href="landing/css/styles.css">
    <link rel="stylesheet" href="landing/css/Testimonials.css">
    <link rel="stylesheet" href="landing/css/register.css">
    <link href="landing/css/smart_wizard.css" rel="stylesheet" type="text/css"/>
    <link rel="shortcut icon" href="landing/img/favicon.png" type="image/x-icon">
    <link rel="icon" href="landing/img/favicon.png" type="image/x-icon">
    {{--    CUSTOM--}}
    <link rel="stylesheet" href="{{ asset('css/inline-fix.css') }}">
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

        .loading .loading-content {
            margin-top: 50vh !important;
        }
    </style>
</head>
<body>
{{-- Loading --}}
<div class="loading">
    <div class="loading-content">
        <div class="spin">
            <i class="fa fa-circle-o-notch fa-spin"></i>
        </div>
        <div class="loading-text">
            {{ trans("general.loading") }}
        </div>
    </div>
</div>
<div class="registration-form container-fluid d-flex justify-content-center">
    <div class="contact-clean box">
        <a id="backBtn" class="btn" href="{{request()->isSecure()?'https://':'http://'}}{{env('B2B_DOMAIN')}}">
            <i class="fa fa-home"></i>
        </a>
        <button type="button" id="prevBtn" onclick="backToFirst()" class="btn" style="display:none">
            <span class=""><i class="fa fa-chevron-left"></i></span> {{trans('landing.register.previous_step')}}
        </button>
        <div class="d-flex justify-content-center progress-step">
            <div class="step circle active" id="step-1"><span class="counter">1</span></div>
            <div class="step circle" id="step-2"><span class="counter">2</span></div>
        </div>
        <form id="regForm" autocomplete="off">
            <div class="tab form-group">
                <h2 class="text-center">{{trans('landing.register.your_basic_info')}}</h2>
                <div class="form-group">
                    <input class="form-control" id="first-name" type="text" name="first_name" required>
                    <label class="form-control-placeholder"
                           for="first-name">{{trans('landing.register.first_name')}}</label>
                </div>
                <div class="form-group">
                    <input class="form-control" id="last-name" type="text" name="last_name" required>
                    <label class="form-control-placeholder"
                           for="last-name">{{trans('landing.register.last_name')}}</label>
                </div>
                <div class="form-group">
                    <input class="form-control" value="" id="email" type="text" autocomplete="new-password"
                           name="email" required>
                    <label class="form-control-placeholder" for="email">Email</label>
                </div>
                <div class="form-group">
                    <input class="form-control number" id="phone" type="number" autocomplete="off"
                           name="phone" required>
                    <label class="form-control-placeholder" for="phone">Phone</label>
                </div>
                <div class="form-group">
                    <input class="form-control" id="password" autocomplete="new-password" type="password"
                           name="password" required maxlength="16">
                    <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                    <label class="form-control-placeholder"
                           for="password">{{trans('landing.register.password')}}</label>
                </div>
                <button type="button" class="next btn btn-primary" id="nextBtn"
                        onclick="nextPrev(1)">{{trans('landing.register.next_step')}}</button>
                <small class="form-text text-center">
                    {{trans('landing.register.already_have_account')}}
                    <a href="{{route('login')}}">{{trans('landing.register.login_here')}}</a>
                </small>

            </div>

            <div class="tab form-group">

                <h2 class="text-center">{{trans('landing.register.your_company_info')}}</h2>
                <div class="form-group">
                    <select class="js-example-basic-multiple form-control" name="business_category[]" id="sssss"
                            multiple="multiple">
                        @if(app()->getLocale() == 'id')
                            @foreach(\App\Models\BusinessCategory::orderBy('business_category_name_id')->get() as $business)
                                <option value="{{$business->id}}">{{$business->business_category_name_id}}</option>
                            @endforeach
                        @else
                            @foreach(\App\Models\BusinessCategory::orderBy('business_category_name')->get() as $business)
                                <option value="{{$business->id}}">{{$business->business_category_name}}</option>
                            @endforeach
                        @endif

                    </select>
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" name="company_name" required>
                    <label class="form-control-placeholder"
                           for="company">{{trans('landing.register.your_company_name')}}</label>
                </div>
                <div class="form-group">
                    <select class="js-example-basic-multiple2 form-control" id="company_type" name="ownership_status">
                        <option value="personal">Personal</option>
                        <option value="corporate">Corporate</option>
                    </select>
                </div>
                <div class="form-group input-group mb-3">
                    <input class="form-control" type="text" name="domain">
                    <label class="form-control-placeholder" for="url">{{trans('landing.register.name_on_url')}}</label>
                    <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">.{{env('APP_URL')}}</span>
                    </div>
                    <small class="form-text" style="display: block;width: 100%"></small>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="invalidCheck" required name="agreement">
                        <label class="custom-control-label" for="invalidCheck"
                               id="agreement">{{trans('landing.register.agree')}}
                            <a href="#" class="term-and-condition" data-toggle="modal"
                               data-target="#modalTermConditionSignUp">{{trans('landing.register.terms')}}</a>
                        </label>
                        @include('landing.term_and_condition')
                        {{--<label class="small" style="cursor: pointer;">[view]</label>--}}
                        <div class="invalid-feedback">
                            {{trans('landing.register.you_must_agree')}}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button class="next btn btn-primary" type="button"
                            onclick="nextPrev(2)">{{trans('landing.register.done')}}</button>
                </div>
            </div>
        </form>

    </div>
</div>
</div>


<script src="landing/js/jquery.min.js"></script>
<script src="landing/bootstrap/js/bootstrap.min.js"></script>
<script src="landing/js/float-panel.js"></script>
<script src="landing/js/bs-animation.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

<script>
    $('#backBtn').on('click', function () {
        window.location.href = "index.html"
    });

    $(document).ready(function () {
        $('.js-example-basic-multiple').select2({
            placeholder: 'Business Category',
            width: '100%'
        });
        $('.js-example-basic-multiple2').select2({
            width: '100%'
        });
    });

    var currentTab = 0; // Current tab is set to be the first tab (0)
    showTab(currentTab); // Display the current tab
    $(document).ready(function () {
        checkValue();
    });

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

    $(document).on('keypress change search input paste cut', 'input, select', function (evt) {
        $(this).closest('.form-group').find('label.error').remove();
        let t = $(this);
        if (t.val() !== '') {
            t.addClass('filled')
        } else {
            t.removeClass('filled')
        }
        if ($(this).attr('name') === 'domain') {
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
    });

    function validateDomain() {
        let t = $('input[name=domain]');
        $.ajax({
            url: '{{route('memoria.validate.domain')}}',
            data: {domain: t.val()},
            success: function (data) {

            },
            error: function (e) {
                if (e.status === 422) {
                    $.each(e.responseJSON.errors, function (i, e) {
                        $(document).find('input[name=' + i + ']').closest('.form-group').append('<label class="error">' + e[0] + '</label>');
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
                t.closest('.form-group').find('label.error').remove();
                if (e.status === 422) {
                    $.each(e.responseJSON.errors, function (i, e) {
                        $(document).find('input[name=' + i + ']').closest('.form-group').append('<label class="error">' + e[0] + '</label>');
                    })
                }
            }
        })
    }

    function showTab(n) {
        var x = document.getElementsByClassName("tab");
        $('.tab').css({display: 'none'});
        x[n].style.display = "block";
        if (n === 0) {
            document.getElementById("prevBtn").style.display = "none";
        } else {
            document.getElementById("prevBtn").style.display = "inline";
        }

        fixStepIndicator(n)
    }

    let loading = $('.loading');

    function nextPrev(n) {
        if (n === 1) {
            $('label.error').remove();
            // loading.show();
            $.ajax({
                url: "{{route('memoria.validate.one')}}",
                data: $('#regForm').serialize(),
                dataType: 'json',
                success: function (data) {
                    var x = document.getElementsByClassName("tab");
                    x[currentTab].style.display = "none";
                    currentTab = currentTab + n;
                    showTab(currentTab);
                },
                error: function (e) {
                    // loading.hide();
                    if (e.status === 422) {
                        $.each(e.responseJSON.errors, function (i, e) {
                            $(document).find('input[name=' + i + ']').closest('.form-group').append('<label class="error">' + e[0] + '</label>');
                        })
                    }
                }
            });


        } else {
            $('label.error').remove();
            loading.show();
            $.ajax({
                url: "{{route('memoria.do.register')}}",
                data: $('#regForm').serialize(),
                dataType: 'json',
                success: function (data) {
                    loading.hide();
                    Swal.fire({
                        type: 'success',
                        title: 'Success',
                        text: 'Check email for activation'
                    }).then(
                        setTimeout(function () {
                            window.location.href = '/agent/login'
                        }, 5000)
                    );
                },
                error: function (e) {
                    loading.hide();
                    if (e.status === 422) {
                        $.each(e.responseJSON.errors, function (i, e) {
                            $(document).find('input[name=' + i + ']').parent().append('<label class="error">' + e[0] + '</label>');
                            $(document).find('select[name="' + i + '[]"]').parent().append('<label class="error">' + e[0] + '</label>');
                        })
                    }
                }
            });
        }
    }

    function validateForm() {
        // This function deals with validation of the form fields
        var x, y, i, valid = true;
        x = document.getElementsByClassName("tab");
        y = x[currentTab].getElementsByTagName("input");
        // A loop that checks every input field in the current tab:
        for (i = 0; i < y.length; i++) {
            // If a field is empty...
            if (y[i].value == "") {
                // add an "invalid" class to the field:
                y[i].className += " invalid";
                // and set the current valid status to false:
                valid = false;
            }
        }
        // If the valid status is true, mark the step as finished and valid:
        if (valid) {
            document.getElementsByClassName("step")[currentTab].className += " finish";
        }
        return valid; // return the valid status
    }

    function fixStepIndicator(n) {
        // This function removes the "active" class of all steps...
        var x = document.getElementsByClassName("step");
        // for (i = 0; i < x.length; i++) {
        //     x[i].className = x[i].className.replace(" active", "");
        // }
        //... and adds the "active" class to the current step:
        x[n].className += " active";
    }

    function backToFirst() {
        currentTab = 0;
        showTab(currentTab);
        var i, x = document.getElementsByClassName("step");
        for (i = 0; i < x.length; i++) {
            x[i].className = x[i].className.replace(" active", "");
        }
        fixStepIndicator(currentTab);
    }

    $(".toggle-password").click(function () {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

    // Prevent Char in Input Type Number
    $(document).on('change keydown', 'input[type="number"], input[type="tel"], .number', function onChange(e) {
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
    })
    // Disable Paste in input type number
    $(document).on('paste', 'input[type="number"], input[type="tel"], .number', function (e) {
        e.preventDefault();
    });
</script>
</body>

</html>