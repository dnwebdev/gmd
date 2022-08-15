@extends('public.memoria.base_layout')
@section('content')

<div class="container">
      <div class="row" style="padding-top: 90px;">
          <div class="col-sm-12 col-lg-7 text">
              <h1 class="wow fadeInLeftBig animated">Designed Specifically for Tour and Activities Providers </h1>
              <div class="description wow fadeInLeftBig animated" >
                <p>
                  

It comes built in with all the modern features and functions that are critical for success in the industry today,including an instant booking engine, live chat, live availability calendar, a distribution system to connect to international distributors, and many more!
                </p>
              </div>
              
          </div>
          <div class="panel panel-default col-sm-12 col-lg-5 form-login">
            <div class="form-top-left">
                <h3>Sign up now</h3>
                <p>Fill in the form below to get instant access:</p>
            </div>
            <div class="panel-body">
              <form class="form-horizontal" id="registForm" data-toggle="validator" role="form" method="POST" action="{{Route('agent.register.submit')}}">
                
                {{ csrf_field() }}

                <div class="tab">
                
                    <div class="form-group">
                        <label class="sr-only" for="form_first_name">First Name</label>
                        <input type="text" name="first_name" placeholder="First Name" class="form-control" id="form_first_name" value="{{ old('first_name') }}" required>
                        <span class="text-danger" id="username_error_message"></span>
                        @if ($errors->has('first_name'))
                            <span class="text-danger" id="name_danger">
                                <strong>{{ $errors->first('first_name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="form_last_name">Last Name</label>
                        <input type="text" name="last_name" placeholder="Last Name" class="form-control" id="form_last_name" value="{{ old('last_name') }}">
                        <span class="text-danger" id="lastname_error_message"></span>
                        @if ($errors->has('last_name'))
                            <span class="text-danger" id="lastName_danger">
                                <strong>{{ $errors->first('last_name') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="sr-only" for="form_phone">Phone</label>
                        <input type="number" name="phone" placeholder="Phone" class="form-control" id="form_phone" value="{{ old('phone') }}">
                        <span class="text-danger" id="phone_error_message"></span>
                        @if ($errors->has('phone'))
                            <span class="text-danger" id="phone_danger">
                                <strong>{{ $errors->first('phone') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                    <label class="sr-only" for="form_email">Email</label>
                        <input type="text" name="email" placeholder="Email" class="form-control" id="form_email" value="{{ old('email') }}">
                        <span class="text-danger" id="email_error_message"></span>
                        <span class="text-danger" id="error_email"></span>
                        @if ($errors->has('email'))
                            <span class="text-danger" id="email_danger">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="tab">
                    <div class="form-group">
                        <label class="sr-only" for="company_name">Company name</label>
                        <input type="text" id="company_name" name="company_name" placeholder="Company Name" class="form-control" value="{{ old('company_name') }}">
                        @if ($errors->has('company_name'))
                            <span class="text-danger" id="companyName_danger">
                                <strong>{{ $errors->first('company_name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="domain_memoria">Gomodo URL</label>
                        <input type="text" id="domain_memoria" name="domain_memoria" placeholder="Gomodo URL" class="form-control" value="{{ old('domain_memoria') }}" readonly>
                        <span class="text-danger" id="error_domain"></span>
                        @if ($errors->has('domain_memoria'))
                            <span class="text-danger" id="domain_danger">
                                <strong>{{ $errors->first('domain_memoria') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="tab">
                    <div class="form-group">
                        <label class="sr-only" for="password">Password</label>
                        <input type="password" data-minlength="6" class="form-control" id="password" placeholder="Password" name="password" required>
                        <span class="text-danger" id="password_error_message">Minimum of 6 Characters</span>
                        <!--<div class="help-block">Minimum of 6 characters </div>-->
                        @if ($errors->has('password'))
                            <span class="text-danger" id="password_danger">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                       @endif
                    </div>

                    <div class="form-group">
                        <label class="sr-only" for="password_confirmation">Retype Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm" required>
                        <span class="text-danger" id="retype_password_error_message"></span>
                        @if ($errors->has('password_confirmation'))
                            <span class="text-danger" id="confirm_danger">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                
                <div style="overflow:auto;">
                    <div style="float:right;">
                        <button type="button" id="prevBtn" onclick="nextPrev(-1)" class="btn btn-secondary">Previous</button>
                        <button type="button" id="nextBtn" onclick="nextPrev(1)" class="btn btn-success">Next</button>
                    </div>
                </div>

                <!-- Circles which indicates the steps of the form: -->
                <div style="text-align:center;" class="m-2">
                    <span class="step"></span>
                    <span class="step"></span>
                    <span class="step"></span>
                </div>

                <!-- <div class="form-group">
                  <label class="sr-only" for="form-first-name">Your Website</label>
                  <input type="text" name="domain" placeholder="Your Website" class="form-first-name form-control" id="form-first-name" value="{{ old('domain') }}">
                  @if ($errors->has('domain'))
                      <span class="text-danger">
                          <strong>{{ $errors->first('domain') }}</strong>
                      </span>
                  @endif
                </div> -->
              </form>
            </div>
          </div>
      </div>
  </div>


@endsection


@section('additionalScript')

<script type="text/javascript">

  $(document).ready(function(){
    // $('#domain_memoria').attr('disabled','disabled');
    $('#company_name').keyup(function(){
    
      str = $(this).val().toLowerCase().trim();
      str = str.replace(/\W+/g, "")
      if(str.length > 0)
      {
        //var envDomain = {{env('APP_URL')}};
        $('#domain_memoria').val(str+".{{env('APP_URL')}}");
        var error_domain = '';
        var domain_memoria = $('#domain_memoria').val();
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url:"{{ route('agent.register.checkDomain') }}",
            method:"POST",
            data:{domain_memoria:domain_memoria, _token:_token},
            success:function(result)
            {
                if(result == 'unique')
                {
                    $('#error_domain').html('<label class="text-success">Available</label>');
                    $("#error_domain").show();
                    $('#nextBtn').attr('disabled', false);
                }
                else
                {
                    $('#error_domain').html('<label class="text-danger">Already Taken</label>');
                    $("#error_domain").show();
                    $('#nextBtn').attr('disabled', 'disabled');
                }
            }
            })   
        }

        else
        {
            $('#domain_memoria').val('');
            $("#error_domain").hide(); 
        }
    });
  });

  $(document).ready(function(){
    $('#form_email').keyup(function(){
    var error_email = '';
    var email = $('#form_email').val();
    var _token = $('input[name="_token"]').val();
    var filter = /^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i;
    if(!filter.test(email))
    {    $("#error_email").hide();  
    }

    else
    {
        $.ajax({

            url:"{{ route('agent.register.checkEmail') }}",
            method:"POST",
            data:{email:email, _token:_token},
            success:function(result)
            {
                if(result == 'unique')
                {
                    $('#error_email').html('<label class="text-success"></label>');
                    $("#error_email").show();
                    $('#nextBtn').attr('disabled', false);
                }
                else
                {
                    $('#error_email').html('<label class="text-danger">This e-mail has been already registered.</label> <a href ="{{ Route('login') }}">Login?</a>');
                    $("#error_email").show();
                    $('#nextBtn').attr('disabled', 'disabled');
                }
            }
            })
 }
});

});

</script>

<script>
    var currentTab = 0; // Current tab is set to be the first tab (0)
    showTab(currentTab); // Display the current tab

    function showTab(n) {
    // This function will display the specified tab of the form ...
    var x = document.getElementsByClassName("tab");
    x[n].style.display = "block";
    // ... and fix the Previous/Next buttons:
    if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
    } else {
        document.getElementById("prevBtn").style.display = "inline";
    }
    if (n == (x.length - 1)) {
        document.getElementById("nextBtn").innerHTML = "Submit";
        setTimeout(submitButton, 1000);
    } else {
        document.getElementById("nextBtn").innerHTML = "Next";
        document.getElementById("nextBtn").setAttribute('type', 'button');
    }
    // ... and run a function that displays the correct step indicator:
    fixStepIndicator(n)
    }

    function submitButton() {
        document.getElementById("nextBtn").setAttribute('type', 'submit');
    }

    function nextPrev(n) {
    // This function will figure out which tab to display
    var x = document.getElementsByClassName("tab");
    // Exit the function if any field in the current tab is invalid:
    if (n == 1 && !validateForm()) return false;
    // Hide the current tab:
    x[currentTab].style.display = "none";
    // Increase or decrease the current tab by 1:
    currentTab = currentTab + n;
    // if you have reached the end of the form... :
    if (currentTab >= x.length) {
        //...the form gets submitted:
        document.getElementById("registForm").submit();
        return false;
    }
    // Otherwise, display the correct tab:
    showTab(currentTab);
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
    var i, x = document.getElementsByClassName("step");
    for (i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "");
    }
    //... and adds the "active" class to the current step:
    x[n].className += " active";
    }
</script>
@endsection