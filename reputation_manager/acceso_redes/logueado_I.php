<?php
  
	/**
	 * @author Johann Stig Gravenhorst R.
	 * @copyright 2013
	 */
	 /**
	  * Retrieve User's Profile via the Graph API This example covers getting profile information 
	  * for the current user and printing their name, using the Graph API and Facebook SDK for PHP. 
	  */
	  ini_set('max_execution_time',1800); //tiempo limite de ejecucion de un escript en segundos.
      ini_set("memory_limit","1500M"); // aumentamos la memoria a 1,5GB
      ini_set("buffering ","0");
	 
	  // Remember to copy files from the SDK's src/ directory to a
	  // directory in your application on the server, such as php-sdk/
	  require_once('facebook-php-sdk/src/facebook.php');
	
	  $config = array(
	    'appId' => '166224536897349',                   // 'YOUR_APP_ID
	    'secret' => 'aecbc738d7a3067fedcf8f302e05e568',
		);

		$facebook = new Facebook($config);
	  
	   // Get user facebook
	  //$user_fb = $facebook->getUser();
	  
	  
	  // Get the application access token
	  $app_access_token = $facebook->getApplicationAccessToken();
	  
 	  $req =  array('access_token' => $app_access_token);
	
?>
<html>
  <head></head>
  <body>

  <?php
    //if($user_fb) {
       // We have a user ID, so probably a logged in user.
      // If not, we'll get an exception, which we handle below.
      try {
        
	   // $user_profile = $facebook->api('/me','GET',$req);
			      
	        
		//echo "User Access Token: " .$user_access_token;
       	$req =  array('access_token' => $app_access_token,'q'=>'Cristiano%Ronaldo');
        
	
		//$MEsearch = $facebook->api('/search?q='.$q.'&type=page&limit=100');
		
		//$search = $facebook->api('/search','GET',$req);
        
                
		//$sence = strtotime('5 December 2013');
		//2013-12-10 08:00:00
		$sence = strtotime('2013-12-10 10:00:00');
		
		$until = strtotime('2013-12-10 10:55:00');
		
		$type = 'post';
		
		$limit ='250';
		
		$_fecha = '2013-12-01';
		
		$fecha_=  '2013-12-04';
		
			 	
		$MEsearch = $facebook->api('/search?type='.$type.'&sence='.$sence.'&limit='.$limit,'GET',$req);
		    
		var_dump($MEsearch);
      
      
	  } catch(FacebookApiException $e) {
        // If the user is logged out, you can have a 
        // user ID even though the access token is invalid.
        // In this case, we'll get an exception, so we'll
        // just ask the user to login again here.
         
        echo $e->getType();
        echo $e->getMessage();
      }   
    //} else {

      // No user, print a link for the user to login
      //$login_url = $facebook->getLoginUrl();
      //echo 'Please <a href="' . $login_url . '">login.</a>';

    //}

  ?>

  </body>
</html>