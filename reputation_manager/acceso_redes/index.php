<?php
  
	/**
	 * @author Johann Stig Gravenhorst R.
	 * @copyright 2013
	 */
	 /**
	  * Retrieve User's Profile via the Graph API This example covers getting profile information 
	  * for the current user and printing their name, using the Graph API and Facebook SDK for PHP. 
	  */
	 
	  // Remember to copy files from the SDK's src/ directory to a
	  // directory in your application on the server, such as php-sdk/
	  require_once('facebook-php-sdk/src/facebook.php');
	
	  $config = array(
	    'appId' => '166224536897349',                   // 'YOUR_APP_ID
	    'secret' => 'aecbc738d7a3067fedcf8f302e05e568',  //'YOUR_APP_SECRET'
	    'allowSignedRequest' => false // optional but should be set to false for non-canvas apps
	  );

	
	  $facebook = new Facebook($config);
	  
	  // Get user facebook
	  $user_fb = $facebook->getUser();

	  // Get the application access token
	   $app_access_token = $facebook->getApplicationAccessToken();
	  
	  // Get current App secret
       $secret = $facebook->getAppSecret();
       
       // Get user AccesToken
       $access_token = $facebook->getAccessToken();
       
 ?>
<html>
  <head></head>
  <body>

  <?php
    if($user_fb) {
       // We have a user ID, so probably a logged in user.
      // If not, we'll get an exception, which we handle below.
      try {
        
	    $user_profile = $facebook->api('/me','GET');
        echo "Nombre: " . $user_profile['name'];
        //echo "   --   ";
		//echo "Ubicacion " . $user_profile['locale']; 
        echo "   --   ";
        echo "Access token: " . $access_token;
        echo "   --   ";
       //echo "Secret: " .$secret;
       // echo "   --   ";
        //echo "User Access Token: " .$user_access_token;
        
        
      } catch(FacebookApiException $e) {
        // If the user is logged out, you can have a 
        // user ID even though the access token is invalid.
        // In this case, we'll get an exception, so we'll
        // just ask the user to login again here.
        $login_url = $facebook->getLoginUrl(); 
        echo 'Please <a href="' . $login_url . '">login.</a>';
        error_log($e->getType());
        error_log($e->getMessage());
      }   
    } else {

      // No user, print a link for the user to login
      $login_url = $facebook->getLoginUrl();
      echo 'Please <a href="' . $login_url . '">login.</a>';

    }

  ?>

  </body>
</html>