$('#PwdCheck').on("pageshow", function() {
	var UserMail = window.localStorage.getItem('UserMail');
	var UserKey = window.localStorage.getItem('UserKey');
	var UserFirstname = window.localStorage.getItem('UserFirstname');
	var UserLastname = window.localStorage.getItem('UserLastname');
	
	//	alert('UserMail ' , UserMail , ' Key  ' , UserKey);

	// http://www.the-art-of-web.com/javascript/validate-password/
	function checkPassword(str) {
		var re = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/;
		return re.test(str);
	}

	function checkForm(form) {
		if (form.username.value == "") {
			alert("Error: Username cannot be blank!");
			form.username.focus();
			return false;
		}
		re = /^\w+$/;
		if (!re.test(form.username.value)) {
			alert("Error: Username must contain only letters, numbers and underscores!");
			form.username.focus();
			return false;
		}
		if (form.pwd1.value != "" && form.pwd1.value == form.pwd2.value) {
			if (!checkPassword(form.pwd1.value)) {
				alert("The password you have entered is not valid!");
				form.pwd1.focus();
				return false;
			}
		} else {
			alert("Error: Please check that you've entered and confirmed your password!");
			form.pwd1.focus();
			return false;
		}
		return true;
	}

	var pwdData = '<h2>Hello ' + UserFirstname + '  ' + UserLastname + ' your logged in as ' + UserMail + '</h2> <p> Here you can change your password</p>';
	$(pwdData).appendTo('#pwdHead');
	
	
	$('#pwdForm').html('<label for="pwd1">Password:</label><input id="pwd1" title="Password must contain at least 8 characters, including UPPER/lowercase and numbers." type="password"  name="pwd2">'
	+ '<label for="pwd2">Confirm Password:</label><input id="pwd2" title="Please enter the same Password as above." type="password"  name="pwd2">');

	// Formdaten an PHP-Datei senden
	$("form#pwdcheck").submit(function() {
		// we want to store the values from the form input box, then send via
		// ajax below
		var p_email = UserMail;
		var p_pwd = $('#pwd2').attr('value');
		
		//alert("Both username and password are VALID!", UserMail , "  p_email  ", p_email, " p_pwd  ", p_pwd);
	
		$.ajax({
			type : 'GET',
			url : 'php/tJamContacts.php',
			data : 'function=pwupdate&p_pwd=' + p_pwd + '&p_email=' + p_email,
			dataType : 'json',
			processData : false, // Don't process the files
			contentType : false, // Set content type to false as jQuery will tell the server its a query string request

			success : function() {
				$.mobile.changePage("#map", {
					transition : "flip"
				});
				//     error:  alert('login error you have an account ? Register NEW ; ');
			}
		});
		return false;
	});

});
