$('#PwdUpdate').on("pageshow", function() {
	var UserMail = window.localStorage.getItem('UserMail');
	var UserKey = window.localStorage.getItem('UserKey');
	var UserFirstname = window.localStorage.getItem('UserFirstname');
	var UserLastname = window.localStorage.getItem('UserLastname');

		//alert('UserMail ' , UserMail , ' Key  ' , UserKey);

	// http://www.the-art-of-web.com/javascript/validate-password/

	var pwdData = '<h2>Hello ' + UserFirstname + '  ' + UserLastname + ' your logged in as ' + UserMail + '</h2> <p> Here you can change your password</p>';
	$(pwdData).appendTo('#pwdHead');

	$('#pwdForm').html('<label for="pwd1">Password:</label><input id="pwd1" title="Password must contain at least 8 characters, including UPPER/lowercase and numbers." type="password"  name="pwd2">' + '<label for="pwd2">Confirm Password:</label><input id="pwd2" title="Please enter the same Password as above." type="password"  name="pwd2">');

	// Formdaten an PHP-Datei senden
	$("form#pwdcheck").submit(function() {
		// we want to store the values from the form input box, then send via
		// ajax below
		var p_email = UserMail;
		var p_pwd = $('#pwd2').attr('value');

		//alert("Both username and password are VALID!", UserMail , "  p_email  ", p_email, " p_pwd  ", p_pwd);

		$.ajax({
			type : 'POST',
			url : 'php/tJamContacts.php',
			data : 'function=pwdupdate&p_pwd=' + p_pwd + '&p_email=' + p_email,
			dataType : 'json',
			//processData : false, // Don't process the files
			//contentType : false, // Set content type to false as jQuery will tell the server its a query string request

			success : function() {
				$.mobile.changePage("#map", {
					transition : "flip"
				});
				//     error:  alert('login error you have an account ? Register NEW ; ');
			},
			error : function(data, req, status, err) {
				alert('something went wrong !!! Login again or Register first');
				//	console.log('something went wrong', status, err);
				$.mobile.changePage("#register", {
					transition : "flip"
				});
			}
		});
		return false;
	});

});


// Schliessen Detail Ausgabe
$('#PwdUpdate').on("pagehide", function() {
	$('#pwdHead').empty();
	$('#pwdForm').empty();
});

