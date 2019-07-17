$('#Logout').on("pageshow", function() {
	//Falls user sich unter anderer Id einloggen will
	window.localStorage.setItem('UserKey', '');
	window.localStorage.setItem('UserMail', '');
	window.localStorage.setItem('UserFirstname', '');
	window.localStorage.setItem('UserLastname', '');

	window.localStorage.clear();

	//$('.header').html('	<a href="#startseite" data-role="button" data-icon="home"	data-iconpos="notext">Dieser Text wird nicht angezeigt</a>' + '<h1>Welcome to Jamfinder</h1>' + '<a href="#login" data-role="button" data-icon="check" data-transition="fade" class="ui-btn-active">Login</a>');
	//location.reload(true);
	$.mobile.changePage("#login", {		
		transition : "flip"
	});

});

