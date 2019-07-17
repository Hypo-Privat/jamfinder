$('#login').on("pageshow", function() {

	//Formdaten an PHP-Datei senden
	$("form#loginform").submit(function() {
		// we want to store the values from the form input box, then send via ajax below
		var l_email = $('#l_email').attr('value');
		var l_pwd = $('#l_pwd').attr('value');
		//$('#JamContacts').height($(window).height());
		//alert("form#loginform=" + l_pwd + "&l_email=" + l_email);

		//http://milesj.me/blog/read/cakephp-ajax-json-response
		//var string = 'function=user_login_check&l_pwd=' + l_pwd + '&l_email=' + l_email;
		dataString = encodeURI("function=user_login_check&l_pwd=" + l_pwd + '&l_email=' + l_email);
		$.ajax({
			type : 'get',
			url : 'php/tJamContacts.php',			
			data : dataString,
			contentType : "application/json", // charset=utf-8", // Set
			dataType : 'json',
			jsonCallback : 'getJson',
		    async : false,
			success : function(data) {
				$.each(data, function(counter, daten) {
					//alert('succsess', daten.INDEXKEY);
				
					if ( typeof (window.localStorage) != 'undefined') {
						//alert(" window.localStorage" + (daten.INDEXKEY));
						window.localStorage.setItem('UserKey', daten.INDEXKEY);
						window.localStorage.setItem('UserMail', daten.EMAILADDRESS);
						window.localStorage.setItem('UserFirstname', daten.FIRSTNAME);
						window.localStorage.setItem('UserLastname', daten.LASTNAME);
					} else {
						alert(" window.localStorage, not defined" + (daten.INDEXKEY));
						throw "window.localStorage, not defined";
					}
					$.mobile.changePage("#map", {
						transition : "flip"
					});
				});
			},
			error : function(data, req, status, err) {
				alert('something went wrong !!! Login again or Register first');
				//	console.log('something went wrong', status, err);
				$.mobile.changePage("#register", {
					transition : "flip"
				});
			}
		});
		// end ajax

		return false;

	});

});
