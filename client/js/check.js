$(document).ready(function() {
	$('input[name=username]').focus(function() {
		$('#usrerror').remove();
		$('#usrok').remove();
		$("#username").after("<span class='info' id='usrinfo'>The username field must contain atleast 6 alphanumeric characters</span>");
	}).blur(function() {			
		var regex_username = /^[a-zA-Z0-9]+$/;
		if(regex_username.test($('#username').val()) && $('#username').val().length > 5) {
			$('#usrinfo').remove();
			$('#usrerror').remove();
			var username = $('#username').val().toLowerCase();
			$.ajax({
				url: '../server/registercheck.php',
				data: {
					"username": username,
					"purpose": "checkuser",
				},
				type: 'post',					
				success: function(result){
					if(result) {
						$("#username").after("<span class='error' id='usrerror'>Username already exists</span>");
					}
					else {							
						$("#username").after("<img id='usrok' class='usrok' src='../public/img/accept.png'>");
					}
				}
			});				
		}
		else if($('#username').val() == "") {
			$('#usrinfo').remove();
			$('#usrok').remove();
			$('#username').after("<span class='error' id='usrerror'>Username is required</span>");
		}
		else {
			$('#usrinfo').remove();
			$('#usrok').remove();
			$("#username").after("<span class='error' id='usrerror'>Invalid Username</span>");
		}
	});
	
	$('input[name=email]').focus(function() {
		$('#emailerror').remove();
		$('#emailok').remove();
		$("#email").after("<span class='info' id='emailinfo'>The email field should be a valid email address (local-part@domain.com)</span>");
	}).blur(function() {
		var regex_email = /^\w+([-+.'][^\s]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/g; 
		if(regex_email.test($("#email").val())) {
			$('#emailinfo').remove();
			$('#emailerror').remove();
			var email = $('#email').val().toLowerCase();
			$.ajax({
				url: '../server/registercheck.php',
				data: {
					"email": email,
					"purpose": "checkusermail",
				},
				type: 'post',					
				success: function(result){
					if(result) {
						$("#email").after("<span class='error' id='emailerror'>Email address already exists</span>");
					}
					else {							
						$("#email").after("<img id='emailok' class='emailok' src='../public/img/accept.png'>");
					}
				}
			});	
			
		}
		else if($("#email").val() == "") {
			$('#emailinfo').remove();
			$('#emailok').remove();
			$('#email').after("<span class='error' id='emailerror'>Email is required</span>");
		}
		else {
			$('#emailinfo').remove();
			$('#emailok').remove();
			$("#email").after("<span class='error' id='emailerror'>Invalid Email Address</span>");
		}
	});
	
	$('input[name=fname]').focus(function() {
		$('#fnameerror').remove();
		$('#fnameok').remove();
	}).blur(function() {	
		var regex_username = /^[a-zA-Z]+$/;
		if(regex_username.test($("#fname").val())) {
			$('#fnameerror').remove();
			$("#fname").after("<img id='fnameok' class='fnameok' src='../public/img/accept.png'>");
		}
		else if($("#fname").val() == '') {
			$('#fnameok').remove();
			$('#fname').after("<span class='error' id='fnameerror'>First name is required</span>");
		}
		else {	
			$('#fnameok').remove();
			$("#fname").after("<span class='error' id='fnameerror'>Invalid First Name</span>");
		}
	});
	
	$('input[name=lname]').focus(function() {
		$('#lnameerror').remove();
		$('#lnameok').remove();	
	}).blur(function() {	
		var regex_username = /^[a-zA-Z]+$/;
		if(regex_username.test($("#lname").val())) {	
			$('#lnameerror').remove();
			$("#lname").after("<img id='lnameok' class='lnameok' src='../public/img/accept.png'>");
		}
		else if($("#lname").val() == '') {
			$('#lnameok').remove();
			$('#lname').after("<span class='error' id='lnameerror'>Last name is required</span>");
		}
		else {	
			$('#lnameok').remove();
			$("#lname").after("<span class='error' id='lnameerror'>Invalid Last Name</span>");
		}
	});
	$('#password').keyup(function() {
		var password = $(this).val();
		//validate the length
		if ( password.length < 8 ) {
			$('#length').removeClass('valid').addClass('invalid');
		} else {
			$('#length').removeClass('invalid').addClass('valid');
		}
		//validate letter
		if ( password.match(/[A-z]/) ) {
			$('#letter').removeClass('invalid').addClass('valid');
		} else {
			$('#letter').removeClass('valid').addClass('invalid');
		}
		
		//validate capital letter
		if ( password.match(/[A-Z]/) ) {
			$('#capital').removeClass('invalid').addClass('valid');
		} else {
			$('#capital').removeClass('valid').addClass('invalid');
		}
		
		//validate number
		if ( password.match(/\d/) ) {
			$('#number').removeClass('invalid').addClass('valid');
		} else {
			$('#number').removeClass('valid').addClass('invalid');
		}
		
		//validate special characters
		if ( password.match(/[!@#$%^&*()_=\[\]{};':"\\|,.<>\/?+-]+/) ) {
			$('#special').removeClass('invalid').addClass('valid');
		} else {
			$('#special').removeClass('valid').addClass('invalid');
		}
	}).focus(function() {		
		$('#passwordok').remove();
		$('#passworderror').remove();
		$('#passwordrules').show();
	}).blur(function() {
		$('#passwordrules').hide();
		if($('#length').hasClass("invalid") || $('#letter').hasClass("invalid") || $('#capital').hasClass("invalid") || $('#number').hasClass("invalid") || $('#special').hasClass("invalid")) {
			$('#passwordok').remove();
			if($('#password').val() != "") {
				$("#password").after("<span class='error' id='passworderror'>Invalid Password</span>");
			}
			else {
				$("#password").after("<span class='error' id='passworderror'>Password can't be null</span>");
			}
		}
		else {				
			$('#passworderror').remove();
			$("#password").after("<img id='passwordok' class='passwordok' src='../public/img/accept.png'>");
		}
		if($("#repassword").val() != ""){
			$("#repassword").val('');
		}
	});
	$('input[name=repassword]').focus(function() {		
		$('#repwdok').remove();
		$('#repwderror').remove();
	}).blur(function() {
		if(($('#password').val() != "" && $("#repassword").val() != "") && $('#password').val() == $("#repassword").val()) {
			$('#repwderror').remove();
			$("#repassword").after("<img id ='repwdok' class='repwdok' src='../public/img/accept.png'>");
			
		}
		else if(($('#password').val() != "" && $("#repassword").val() == "") || ($('#password').val() != $("#repassword").val())){			
			$('#repwdok').remove();
			$('#repassword').after("<span class='error' id='repwderror'>Password mismatch</span>");				
		}
		else if($("#repassword").val() == "") {			
			$('#repwdok').remove();
			$('#repassword').after("<span class='error' id='repwderror'>Password can't be null</span>");	
		}
	});
		
	
	$('button[name="signupbutton"]').click(function(){
		if($('#usrok').hasClass("usrok") && $('#emailok').hasClass("emailok") && $('#fnameok').hasClass("fnameok") && $('#lnameok').hasClass("lnameok") && $('#passwordok').hasClass("passwordok") && $('#repwdok').hasClass("repwdok")){
			$('#registererror').hide();				
			var username = $('#username').val();
			var password = $('#password').val();
			var email = $("#email").val();
			var fname = $("#fname").val();
			var lname = $("#lname").val();
			$.ajax({
				url: '../server/registercheck.php',
				data: {
					"username": username,
					"password": password,
					"email": email,
					"fname": fname,
					"lname": lname,
					"purpose": "registeruser",
				},
				type: 'post',					
				success: function(result){
					$('#loginbox').show();
					$('#signupbox').hide();
					/*if(result) {
						$("#username").after("<span class='error' id='usrerror'>Username already exists</span>");
					}
					else {							
						$("#username").after("<img id='usrok' class='usrok' src='../public/img/accept.png'>");
					}*/
				},
				error: function(jqXHR, textStatus, errorThrown) {
					alert('An error occurred... Look at the console (F12 or Ctrl+Shift+I, Console tab) for more information!');

					$('#username').after('<p>status code: '+jqXHR.status+'</p><p>errorThrown: ' + errorThrown + '</p><p>jqXHR.responseText:</p><div>'+jqXHR.responseText + '</div>');
					alert('jqXHR:');
					alert(jqXHR.responseText);
					alert(jqXHR.status);
					alert('textStatus:');
					alert(textStatus);
					alert(errorThrown);
				}
			});
		}
		else {
			$('#registererror').remove();
			$('#btn-signup').after("<br/><br/><span class='error' id='registererror' class='registererror'>Please fill all the fields without any errors</span>");
		}			
	});
	
	$('button[name="signinbutton"]').click(function(){
		$('#loginusrerror').remove();
		$('#loginpwderror').remove();
		$('#loginok').remove();
		$('#loginerror').remove();
		if($('#login-username').val() =='' && $('#login-password').val() =='') {
			$('#login-username').after("<span class='error' id='loginusrerror'>Username is required</span>");
			$('#login-password').after("<span class='error' id='loginpwderror'>Password is required</span>");
		}
		else if($('#login-username').val() ==''){
			$('#login-username').after("<span class='error' id='loginusrerror'>Username is required</span>");
		}
		else if($('#login-password').val() ==''){
			$('#login-password').after("<span class='error' id='loginpwderror'>Password is required</span>");
		}
		else {							
			var username = $('#login-username').val();
			var password = $('#login-password').val();
			$.ajax({
				url: '../server/registercheck.php',
				data: {
					"username": username,
					"password": password,
					"purpose": "loginuser",
				},
				type: 'post',					
				success: function(result){
					if(result) {
						//$("#btn-login").after("<span class='ok' id='loginok'>Login Sucessfull</span>");
						$(".logIn").hide();
						$('#loginModal').modal('hide');
						$('#logged-username').html(username);
						$(".loggedIn").show();
						location.reload();
					}
					else {
						$('#btn-login').after("<span class='error' id='loginerror'>Invalid Username/Password</span>");
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					alert('An error occurred... Look at the console (F12 or Ctrl+Shift+I, Console tab) for more information!');
					$('#username').after('<p>status code: '+jqXHR.status+'</p><p>errorThrown: ' + errorThrown + '</p><p>jqXHR.responseText:</p><div>'+jqXHR.responseText + '</div>');
					alert('jqXHR:');
					alert(jqXHR.responseText);
					alert(jqXHR.status);
					alert('textStatus:');
					alert(textStatus);
					alert(errorThrown);
				}
			});
		}			
	});
	$('.logout').click(function(){
		$(".loggedIn").hide();
		$('#logged-username').html('');
		$(".logIn").show();
		document.cookie = "username=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
		document.cookie = "admin=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
		$.ajax({
        url: '../server/session.php',
        type: 'delete',
        data:{},
        success: function(result){
        	location.reload();
        },
        error: function() { alert("error loading PHP file");  }
      });
	});
});