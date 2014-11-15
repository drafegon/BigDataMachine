<?php
session_start();

$path = "../adodb/adodb.inc.php";
include ("../admin/var.php");
include ("../conexion.php");

include ("../language/language".$_SESSION['idioma'].".php");
	
include "../admin/funciones.php";
include("../admin/funciones_start_session.php");

$variables_metodo = variables_metodo("validar,mensaje,id,source");
$validar= 		$variables_metodo[0];
$mensaje= 		$variables_metodo[1];
$id= 			$variables_metodo[2];
$source= 		$variables_metodo[3];

if(!permiso_usuario("FEEDBACK",$_SESSION['bdm_user']['gru_id'],$db)){
	echo _NOPERMISOMODULO;
	die();
}

if($validar=="1"){
	if(count($_SESSION['bdm_redes'])>0 && array_key_exists("TW",$_SESSION['bdm_redes'])){		
		echo "OK";	
	}else{
		echo "Debe configurar primero una cuenta de Twitter en mis redes para contestar el mensaje";
	}
}else{
	$sql="UPDATE ic_rss_coincidencias SET intervenido='S', detalle_intervenido='".utf8_decode($mensaje)."' WHERE id='".$id."'";
	$result=$db->Execute($sql);	
	
	require_once('../admin/twitteroauth/twitteroauth.php');

	function getConnectionWithAccessToken($var_twitter_app_id, $var_twitter_app_secret, $oauth_token, $oauth_token_secret) {
	  $connection = new TwitterOAuth($var_twitter_app_id, $var_twitter_app_secret, $oauth_token, $oauth_token_secret);
	  return $connection;
	}

	$connection = getConnectionWithAccessToken($var_twitter_app_id, $var_twitter_app_secret, $_SESSION['bdm_redes']["TW"]['oauth_token_tw'], $_SESSION['bdm_redes']["TW"]['oauth_token_secret_tw']);
	
    $object = $connection->get('statuses/show.json?',array('id' => ''.$source.''));
	
	if(isset($object->user)){
		$connection->post('statuses/update.json?', array('status' => '@'.$object->user->screen_name.' '.$mensaje.'', 'in_reply_to_status_id' => ''.$source.''));
	
		echo "OK";
	}else{		
		echo _BDM_TWTMSGNO;
	}
}
?>