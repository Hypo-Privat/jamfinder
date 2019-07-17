var UserMail = window.localStorage.getItem('UserMail');
var UserKey = window.localStorage.getItem('UserKey');
var UserFirstname = window.localStorage.getItem('UserFirstname');
var UserLastname = window.localStorage.getItem('UserLastname');

//alert(" #UserMail " + UserMail);
/*
 + '<li ><a href="#" data-icon="delete" class="ui-btn ui-mini ui-btn-b" data-rel="close">Close</a></li>'

 + '<li data-role="collapsible" data-inset="false" data-iconpos="right"  data-mini="true" data-theme="a"><h3>User Menue</h3>'
 + '<ul data-role="listview">'
 + '</ul></li><!-- /collapsible -->'
 */

//<!-- Piwik -->
/*<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(["setDomains", ["*.jamfinder.info"]]);
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//hypo-privat.com/piwik/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', '2']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
//</script>
//<noscript><p><img src="//hypo-privat.com/piwik/piwik.php?idsite=2" style="border:0;" alt="" /></p></noscript>
//<!-- End Piwik Code -->
*/


$('.leftpanel').html('<div data-role="panel" data-theme="b" id="leftpanel" data-display="push">' + '<ul data-role="listview">' 
+ '<li><a href="#Logout" class="ui-btn ui-mini ui-btn-a" data-transition="slide" >Logout</a></li>' 
+ '<li><a href="#Apps" class="ui-btn ui-mini ui-btn-b" data-transition="slide" >download the App</a></li>' 
+ '<li><a href="#UpdateUser" class="ui-btn ui-mini ui-btn-a"  data-transition="slide" >Profile update</a></li>' 
+ '<li><a href="#Termin" class="ui-btn ui-mini ui-btn-b" data-transition="slide" >Event add & update</a></li>' 
+ '<li><a href="#Location" class="ui-btn ui-mini ui-btn-a"  data-transition="slide" >Location add & update</a></li>' 
+ '<li><a href="#Actor" class="ui-btn ui-mini ui-btn-b" data-transition="slide">Actor add & update</a></li>' 
+ '<li><a href="#PwdUpdate" class="ui-btn ui-mini ui-btn-a" data-transition="slide">Password change</a></li>' 
+ '<li>	<a href="#Impressum" class="ui-btn ui-mini ui-btn-b" data-transition="slide" >Impressum</a></li>' 
+ '<li>	<a href="#AGB" class="ui-btn ui-mini ui-btn-a" data-transition="slide" >AGB</a></li>' 
+ '</ul> <!-- main struktur --> </div>');

if (UserMail === undefined || UserMail === null) {

	$('.header').html('	<a href="#leftpanel" data-role="button" data-icon="bars">Menue</a>' 
	+ '<h1>Welcome to Jamfinder</h1>' + '<a href="#login" data-role="button" data-icon="check" data-transition="fade" ">Login</a>');
} else {

	$('.header').html('	<a href="#leftpanel" data-role="button" data-icon="bars">Menue</a>' 
	+ '<h1>Welcome to Jamfinder</h1>' + '<a href="#login" data-role="button" data-icon="check" data-transition="fade" >Login</a>');
}

$('.footer').html('<ul><li><a href="#list" data-icon="grid" data-transition="fade">Event List</a></li>' 
+ '<li>	<a href="#map" data-icon="location" data-transition="fade">Event Map</a></li>' 
+ '<li><a href="#insertLOC" data-icon="action"	data-transition="fade">Add Event</a></li>' + '</ul>'
//+'<noscript><p><img src="//hypo-privat.com/piwik/piwik.php?idsite=2" style="border:0;" alt="" /></p></noscript>'
);

