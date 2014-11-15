<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Login Facebook</title>
</head>
<body>
<div id="fb-root"></div>
<script type="text/javascript">
function nuevoAjax()
{ 
	/* Crea el objeto AJAX. Esta funcion es generica para cualquier utilidad de este tipo */
	var xmlhttp=false; 
	try 
	{ 
		// Creacion del objeto AJAX para navegadores no IE
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP"); 
	}
	catch(e)
	{ 
		try
		{ 
			// Creacion del objet AJAX para IE 
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); 
		} 
		catch(E) { xmlhttp=false; }
	}
	if (!xmlhttp && typeof XMLHttpRequest!="undefined") { xmlhttp=new XMLHttpRequest(); } 

	return xmlhttp; 
}

///////////////////////////////////////////////////////////////////////////////////////////////

function registrarUsuario(fb_id,fb_nombre,fb_pais,fb_mail){
	var ajax=nuevoAjax();
	ajax.open("POST", "registrar_fb.php?", false);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("opcion=validar&fb_id="+fb_id+"&fb_nombre="+fb_nombre+"&fb_pais="+fb_pais+"&fb_mail="+fb_mail+"");
	
	if (ajax.readyState==4)
	{
		if(ajax.responseText)
		{
			if(ajax.responseText=="OK-0"){
				alert("Gracias por ingresar, est\u00e1 siendo redireccionado a la aplicaci\u00f3n.");
				window.opener.location.href="http://cloud-bdm.appspot.com/start_session.php";
				window.close();
			}else if(ajax.responseText=="OK-1"){
				alert("Gracias por Registrase, ahora est\u00e1 ingresando a la aplicaci\u00f3n.");
				window.opener.location.href="http://cloud-bdm.appspot.com/start_session.php";
				window.close();
			}else{
				alert("ERROR: "+ajax.responseText);
				window.close();
			}
		}else{
			alert("Error en el registro");
		}
	}	
}

///////////////////////////////////////////////////////////////////////////////////////////////

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '1529290933958757', // App ID
    channelUrl : 'http://cloud-bdm.appspot.com/reputation_manager/acceso_redes/', // Channel File
    status     : true, // check login status
    cookie     : true, // enable cookies to allow the server to access the session
    xfbml      : true  // parse XFBML
  });

  // Here we subscribe to the auth.authResponseChange JavaScript event. This event is fired
  // for any authentication related change, such as login, logout or session refresh. This means that
  // whenever someone who was previously logged out tries to log in again, the correct case below 
  // will be handled. 
  FB.Event.subscribe('auth.authResponseChange', function(response) {
    // Here we specify what we do with the response anytime this event occurs. 
    if (response.status === 'connected') {
      // The response object is returned with a status field that lets the app know the current
      // login status of the person. In this case, we're handling the situation where they 
      // have logged in to the app.
      testAPI();
    } else if (response.status === 'not_authorized') {
      // In this case, the person is logged into Facebook, but not into the app, so we call
      // FB.login() to prompt them to do so. 
      // In real-life usage, you wouldn't want to immediately prompt someone to login 
      // like this, for two reasons:
      // (1) JavaScript created popup windows are blocked by most browsers unless they 
      // result from direct interaction from people using the app (such as a mouse click)
      // (2) it is a bad experience to be continually prompted to login upon page load.
      FB.login();
    } else {
      // In this case, the person is not logged into Facebook, so we call the login() 
      // function to prompt them to do so. Note that at this stage there is no indication
      // of whether they are logged into the app. If they aren't then they'll see the Login
      // dialog right after they log in to Facebook. 
      // The same caveats as above apply to the FB.login() call here.
      FB.login();
    }
  });
  };

  // Load the SDK asynchronously
  (function(d){
   var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement('script'); js.id = id; js.async = true;
   js.src = "//connect.facebook.net/en_US/all.js";
   ref.parentNode.insertBefore(js, ref);
  }(document));

  // Here we run a very simple test of the Graph API after login is successful. 
  // This testAPI() function is only called in those cases. 
  function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
	  
	  //Llamo al registro de BDM
	  registrarUsuario(response.id,response.name,response.locale,response.email);
	  
      console.log('Good to see you, ' + response.name + '.');
    });
  }
</script>
</body>
</html>