$('#login-ok').on("pageshow", function() {
  //$('.header').html('	<a href="#startseite" data-role="button" data-icon="home"	data-iconpos="notext">Dieser Text wird nicht angezeigt</a>' + '<h1>Welcome to Jamfinder</h1>' + '<a href="#login-ok" data-role="button" data-icon="check" data-transition="fade" class="ui-btn-active">Menue</a>');
alert('login-ok');
	var UserMail = window.localStorage.getItem('UserMail');
	var UserKey = window.localStorage.getItem('UserKey');
	var UserFirstname = window.localStorage.getItem('UserFirstname');
	var UserLastname = window.localStorage.getItem('UserLastname');
	var UserLogin = window.localStorage.getItem('login')
	
		//location.reload(true);
	//alert('first= ' . UserFirstname)
		
	// dann erst daten laden f�r die einzelnen task
	$user_detail = ('<a href="#UpdateUser" class="ui-btn ui-mini ui-btn-a"  data-transition="slide" >update your Profile</a>');
	$termin_detail = ('<a href="#Termin" class="ui-btn ui-mini ui-btn-b" data-transition="slide" >update or add Events</a>');
	$location_detail = ('<a href="#Location" class="ui-btn ui-mini ui-btn-a"  data-transition="slide" >update or add Location</a>');
	$actor_detail = ('<a href="#Actor" class="ui-btn ui-mini ui-btn-b" data-transition="slide">update or add Actor</a>');
	$logout = ('<a href="#Logout" class="ui-btn ui-mini ui-btn-a" data-transition="slide" >Logout</a>');
	$impressum = ('<a href="#Impressum" class="ui-btn ui-mini ui-btn-a" data-transition="slide" >Impressum</a>');
	$AGB = ('<a href="#AGB" class="ui-btn ui-mini ui-btn-a" data-transition="slide" >AGB</a>');
	$('#JamMenue').html('<h2 align="center" >Hello ' + UserFirstname + ' ' + UserLastname + '</h2> <p align="center" >Here you can update your user profile, organize your location s. <br> If you are an Actor , please insert your <B>Actor/Band Profile</B> <BR>and add the <b>dates</b> where you play next! <BR> ' + $user_detail + $termin_detail + $location_detail + $actor_detail + $logout +'</p>');
/*
 $logout = ('<a href="#Logout" class="ui-btn ui-inline ui-btn-b" data-transition="slide" >Logout</a>');
			
 */
});
