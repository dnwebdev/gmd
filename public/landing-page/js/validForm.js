$(function() {

	$("#username_error_message").hide();
	$("#lastname_error_message").hide();
	$("#password_error_message").hide();
	$("#retype_password_error_message").hide();
	$("#email_error_message").hide();
	$("#phone_error_message").hide();
	$("#error_email").hide();
	
	var error_repeat_email =false;
	var error_username = false;
	var error_lastname = false;
	var error_password = false;
	var error_retype_password = false;
	var error_email = false;
	var error_phone = false;

	$("#form_first_name").keyup(function() {

		check_username();
		
	});
	$("#form_last_name").keyup(function() {

		check_lastname();
		
	});

	$("#password").keyup(function() {

		check_password();
		
	});

	$("#password_confirmation").keyup(function() {

		check_retype_password();
		
	});
	$("#form_phone").keyup(function() {

		check_phone();
		
	});

	$("#form_email").keyup(function() {

		check_email();
		
	});

	function check_username() {
	
		var username_pattern = new RegExp(/^[A-Za-z]+$/);
	
		if(username_pattern.test($("#form_first_name").val())) {
			$("#username_error_message").hide();
			$("#first_danger").hide();
			$("#form_first_name").removeClass('invalid');
			$('#nextBtn').attr('disabled', false);
		} else {
			$("#username_error_message").html("Alphabet only");
			$("#username_error_message").show();
			$("#first_danger").hide();
			$("#form_first_name").removeClass('invalid');
			$('#nextBtn').attr('disabled', 'disabled');
			error_username = true;
		}
	
	}
	function check_lastname() {
	
		var lastname_pattern = new RegExp(/^[A-Za-z]+$/);
	
		if(lastname_pattern.test($("#form_last_name").val())) {
			$("#lastname_error_message").hide();
			$("#lastName_danger").hide();
			$("#form_last_name").removeClass('invalid');
			$('#nextBtn').attr('disabled', false);
		} else {
			$("#lastname_error_message").html("Alphabet only");
			$("#lastname_error_message").show();
			$("#lastName_danger").hide();
			$("#form_first_name").removeClass('invalid');
			$('#nextBtn').attr('disabled', 'disabled');
			error_lastname = true;
		}
	
	}

	function check_password() {
	
		var password_length = $("#password").val().length;
		
		if(password_length < 6) {
			$("#password_error_message").html("At least 6 characters");
			$("#password_error_message").show();
			$("#password_danger").hide();
			$("#password").removeClass('invalid');
			error_password = true;
		} else {
			$("#password_error_message").hide();
			$("#password_danger").hide();
			$("#password").removeClass('invalid');
		}
	
	}

	function check_retype_password() {
	
		var password = $("#password").val();
		var retype_password = $("#password_confirmation").val();
		
		if(password !=  retype_password) {
			$("#retype_password_error_message").html("Passwords don't match");
			$("#retype_password_error_message").show();
			$("#confirm_danger").hide();
			$("#password_confirmation").removeClass('invalid');
			$('#nextBtn').attr('disabled', 'disabled');
			error_retype_password = true;
		} else {
			$("#retype_password_error_message").hide();
			$("#confirm_danger").hide();
			$("#password_confirmation").removeClass('invalid');
			$('#nextBtn').attr('disabled', false);
		}
	
	}
	function check_phone() {
	
		var phone_pattern = new RegExp(/^\s*(?:\+?(\d{1,3}))?[- (]*(\d{3})[- )]*(\d{3})[- ]*(\d{4})(?: *[x/#]{1}(\d+))?\s*$/);
	
		if(phone_pattern.test($("#form_phone").val())) {
			$("#phone_error_message").hide();
			$("#phone_danger").hide();
			$("#form_phone").removeClass('invalid');
			$('#nextBtn').attr('disabled', false);
		} else {
			$("#phone_error_message").html("Invalid Phone Number");
			$("#phone_error_message").show();
			$("#phone_danger").hide();
			$("#form_phone").removeClass('invalid');
			$('#nextBtn').attr('disabled', 'disabled');
			error_phone = true;
		}
	
	}

	function check_email() {
		var error_repeat_email='';
		var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
		if(pattern.test($("#form_email").val())) {
			$("#email_error_message").hide();
			$("#email_danger").hide();
			$("#form_email").removeClass('invalid');
			$('#nextBtn').attr('disabled', false);
			/*$.ajax({
				url:"{{ route('agent.register.checkEmail') }}",
				type:"POST",
				data:{email:email, _token:_token},
				success:function(result)
				{
				 if(!result == 'unique')
				 {
				  $('#error_repeat_email').html('<label class="text-success">Email Available</label>');
				  $('#error_repeat_email').show();
				  $('#form_email').removeClass('invalid');
				  $('#nextBtn').attr('disabled', false);
				  
				 }
				 else
				 {
				  $('#error_repeat_email').html('Email not Available');
				  $('#error_repeat_email').show();
				  $('#error_repeat_email').addClass('invalid');
				  $('#nextBtn').attr('disabled', 'disabled');
				  error_repeat_email= true;
				 }
				}
			   })*/
		} 
		else {
			$("#email_error_message").html("Invalid Email");
			$("#error_email").hide();
			$("#email_danger").hide();
			$("#email_error_message").show();
			$('#form_email').addClass('invalid');
			$('#nextBtn').attr('disabled', 'disabled');
			error_repeat_email =true;
		}
	
	}

	$("#registForm").submit(function() {
											
		error_username = false;
		error_lastname = false;
		error_password = false;
		error_retype_password = false;
		error_email = false;
		error_phone = false;
		error_repeat_email = false;
											
		check_username();
		check_lastname();
		check_password();
		check_retype_password();
		check_email();
		check_phone();
		
		
		if(error_username == false && error_lastname == false && error_password == false && error_retype_password == false && error_email == false && error_phone == false && error_repeat_email ==false)
		{
			return true;
		} else {
			return false;	
		}

	});

});