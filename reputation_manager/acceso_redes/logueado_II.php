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
	
	  /*$config = array(
	    'appId' => '166224536897349',                   // 'YOUR_APP_ID
	    'secret' => 'aecbc738d7a3067fedcf8f302e05e568',  //'YOUR_APP_SECRET'
	    'allowSignedRequest' => false // optional but should be set to false for non-canvas apps
	  );

	  $facebook = new Facebook($config);
	  
	  // Get user facebook
	  $user_fb = $facebook->getUser();
	  
	  $user_access_token = $facebook->getAccessToken();*/

	/////////////////////////////////////////////////////////////////////////
	$var_facebook_app_id = "437719036358643";//"437719036358643";//"166224536897349";
	$var_facebook_secret = "8cffbe910d01ffad7960c54860568752";//"8cffbe910d01ffad7960c54860568752";//"aecbc738d7a3067fedcf8f302e05e568";
 
	$config_fb = array('appId' => $var_facebook_app_id,                  
					'secret' => $var_facebook_secret,  
					'scope' => 'public_profile,email'
					//'scope' => 'public_profile, basic_info, read_stream, read_mailbox, publish_checkins, status_update, photo_upload, video_upload, email, create_note, share_item, export_stream, publish_stream, ads_management, ads_read, read_insights, read_requests, manage_notifications, publish_actions, user_notes, user_friends, user_status' 
					);
	
	$facebook = new Facebook($config_fb);
	$access = $facebook->getAccessToken();
 ?>
<html>
  <head></head>
  <body>
<?php
 //if($user_fb) {
       // We have a user ID, so probably a logged in user.
      // If not, we'll get an exception, which we handle below.
      try {
        
	   //$user_profile = $facebook->api('/me','GET');
        //echo "Nombre: " . $user_profile['name'];
        //echo "   --   ";
		//echo "Ubicacion " . $user_profile['locale']; 
        //echo "<br><br> ";
        
		
		/*$url .= "https://graph.facebook.com/oauth/access_token?".  
				"grant_type=fb_exchange_token&".         
				"client_id=166224536897349&". 
				"client_secret=aecbc738d7a3067fedcf8f302e05e568&". 
				"fb_exchange_token=".$user_access_token;
			
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_REFERER, "https://www.googleapis.com/");
		$body = curl_exec($ch); 
		curl_close($ch);
		
		$access = explode("&", $body);
		echo $access = str_replace("access_token=","",$access[0]);
	
		echo "<br><br>";*/
       //echo "Secret: " .$secret;
       // echo "   --   ";
        //echo "User Access Token: " .$user_access_token;
        $req =  array('access_token' => $access);
		
		
		$fecha = date('Y-m-d');
		$hora = date('H:i:s');
	
		$sence = strtotime ( '-4 hour' , strtotime ( $fecha . " ". $hora ) ) ;		//&until='.$until.'
		//$until = strtotime('2013-12-12 12:00:00');	///&sence='.$sence.'	
		$type = 'post';		
		$limit ='150';		//
		$locale = 'af_ZA,ar_AR,az_AZ,be_BY,bg_BG,bn_IN,bs_BA,ca_ES,cs_CZ,cy_GB,da_DK,de_DE,el_GR,eo_EO,et_EE,eu_ES,fa_IR,fb_LT,fi_FI,fo_FO,fr_CA,fr_FR,fy_NL,ga_IE,gl_ES,he_IL,hi_IN,hr_HR,hu_HU,hy_AM,id_ID,it_IT,is_IS,ja_JP,ka_GE,km_KH,ko_KR,ku_TR,la_VA,lt_LT,lv_LV,mk_MK,ml_IN,ms_MY,nb_NO,ne_NP,nl_NL,nn_NO,pa_IN,pl_PL,ps_AF,pt_PT,ro_RO,ru_RU,sk_SK,sl_SI,sq_AL,sr_RS,sv_SE,sw_KE,ta_IN,te_IN,th_TH,tl_PH,tr_TR,uk_UA,vi_VN,zh_CN,zh_HK,zh_TW,pt_BR';	// 
		//en_US  en_GB en_PI en_UD es_LA es_ES pt_BR es_ES,es_LA
		//$url = '/search?q=nike%20mundial%20de%20futbol&type='.$type.'&sence='.$sence.'&limit='.$limit.'&fields=id,from.fields(name),name,type,description,caption';
		//$url = '/search?q=nike+mercurial&type=post&sence=1386863172&limit=100';
		
		
		$url .= '&type='.$type.'';
		$url  = '/search?q=papas%20fritas';
		//$url .= '&since='.$sence.'';
		$url .= '&limit='.$limit.'';
		//$url .= '&locale='.$locale.'';
		//$url .= '&center=-38.959,-64.248&distance=1000';
		//$url .= '&fields=id,from.fields(name),name,type,description,caption';
				
		$conta = 0;
		//while($url!=""){
			$MEsearch = $facebook->api($url,'GET',$req);
						
			/*echo $url;
			echo "<br>";
			echo count($MEsearch["data"]);
			echo "<br>";
			echo $conta++;
			echo "<br><br>";
			*/
			echo "<pre>";
			var_dump($MEsearch);
			echo "</pre>";
			//echo "<br><br>";
			
			/*$url="";
			
			if(count($MEsearch["paging"])>1){
				if($MEsearch["paging"]["next"]){
					$url=str_replace("https://graph.facebook.com","",$MEsearch["paging"]["next"]);
					
				}else{
					$url="";
				}
			}else{
				$url="";
			}	
			*/
			/*if($conta>3){
				break;
			}*/
		//}
      } catch(FacebookApiException $e) {
        // If the user is logged out, you can have a 
        // user ID even though the access token is invalid.
        // In this case, we'll get an exception, so we'll
        // just ask the user to login again here.
        echo $e->getType();
        echo $e->getMessage();
      }   
    /*} else {

      // No user, print a link for the user to login
      $login_url = $facebook->getLoginUrl();
      echo 'Please <a href="' . $login_url . '">login.</a>';

    }*/

  ?> 

  </body>
</html>