$('#sendpwd').on("pageshow", function() {
	//Formdaten an PHP-Datei senden
	$("form#sendpwd").submit(function() {
		// we want to store the values from the form input box, then send via ajax below
		//alert("function=user_login_check&u_pwd=");

		var p_email = $('#p_email').attr('value');

		//alert("php/sendmail.php?function=mail_pwd&p_email=" + p_email);

		//http://www.sitepoint.com/ajaxjquery-getjson-simple/
		$.getJSON('php/sendmail.php?function=mail_pwd&p_email=' + p_email, function(data) {
			$.each(data, function(counter, daten) {

			});
		});
		alert("Password was send to your e-mail: " + p_email + " Please login");

		$.mobile.changePage("#login", {
			transition : "flip"
		});
	});
});
