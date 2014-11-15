<?php
session_start();
ini_set('display_errors', '1');
date_default_timezone_set("America/Buenos_Aires");

	include("../../admin/var.php");	
	include("twitteroauth/twitteroauth.php");
		
	$twitteroauth = new TwitterOAuth($var_twitter_app_id,$var_twitter_app_secret);
	
	//$content = $twitteroauth->get('account/verify_credentials');
	$request_token = $twitteroauth->getRequestToken('http://cloud-bdm.appspot.com/reputation_manager/acceso_redes/callback.php');
	                                                                          
	$_SESSION['oauth_token'] = $request_token['oauth_token'];  
	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
	/*
	echo "<pre>";
	var_dump($twitteroauth);
	echo "</pre>";
	*/
	//if($twitteroauth->http_code==200){
		$url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']); 
		header('Location: '. $url);
	//} else {
		//die('Something wrong happened.');
	//}
?>
