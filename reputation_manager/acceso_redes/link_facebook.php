<?php
session_start();

require_once 'src/Facebook/FacebookSession.php';
require_once 'src/Facebook/FacebookSDKException.php';
require_once 'src/Facebook/FacebookCanvasLoginHelper.php';
require_once 'src/Facebook/FacebookRedirectLoginHelper.php';
require_once 'src/Facebook/FacebookRequest.php';
require_once 'src/Facebook/FacebookResponse.php';
require_once 'src/Facebook/GraphObject.php';
require_once 'src/Facebook/GraphUser.php';

use Facebook\FacebookSession;
use Facebook\FacebookSDKException;
use Facebook\FacebookCanvasLoginHelper;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\GraphObject;
use Facebook\GraphUser;

$path = "../../adodb/adodb.inc.php";
include ("../../admin/var.php");
include ("../../conexion.php");
include ("../../admin/funciones.php");
	
$redirect_url = 'http://cloud-bdm.appspot.com/reputation_manager/acceso_redes/link_facebook.php';

FacebookSession::setDefaultApplication($var_facebook_app_id,$var_facebook_secret);

$session = false;

if(isset($_GET['code'])){
	$helper = new FacebookRedirectLoginHelper($redirect_url);
	try {
		$session = $helper->getSessionFromRedirect();
	} catch(FacebookRequestException $ex) {
		// When Facebook returns an error
	} catch(\Exception $ex) {
		// When validation fails or other local issues
	}
}

if ($session) {
try {
	$sessionLong = $session->getLongLivedSession();
	
	$me = (new FacebookRequest(
		$session, 'GET', '/me'
	))->execute()->getGraphObject(GraphUser::className());
	
	$picture = (new FacebookRequest(
		$session, 'GET', '/me/picture', array('redirect' => false, 'type' =>  'normal')
	))->execute()->getGraphObject(GraphUser::className());
	
	$friends = (new FacebookRequest(
		$session, 'GET', '/me/friends'
	))->execute()->getGraphObject(GraphUser::className());

	$user_access_token = $sessionLong->getToken();
		
	$idusuario 		   = $_SESSION['sess_usu_grupo'];
	$tipo              = 'FB';
	$nombre_usuario_fb = $me->getName();
	$imagen_fb         = $picture->getProperty('url');
	$cantidad_amg_fb   = 0;
	$fecha_generacion  = date('Y-m-d H:i:s');
	
	$sql = "INSERT INTO ic_redes_usuario (id_usuario,tipo,oauth_token_fb,nombre_usuario_fb,imagen_fb,cantidad_amg_fb,fecha_generacion) 
	        VALUES ('$idusuario','$tipo','$user_access_token','$nombre_usuario_fb','$imagen_fb','$cantidad_amg_fb','$fecha_generacion')";
	$result = $db->Execute($sql);
	
	echo '<script type="text/javascript">window.close();</script>';
} catch (FacebookRequestException $e) {
  // The Graph API returned an error
} catch (\Exception $e) {
  // Some other error occurred
}
}else{
	$helper = new FacebookRedirectLoginHelper($redirect_url, $var_facebook_app_id, $var_facebook_secret);
	//echo '<a href="' . $helper->getLoginUrl(array( 'public_profile', 'basic_info', 'email', 'user_birthday', 'user_friends')) . '">Login with Facebook</a>';
	echo'<META HTTP-EQUIV="Refresh" CONTENT="0;URL=' . $helper->getLoginUrl(array( 'public_profile', 'email', 'user_birthday', 'user_friends','publish_actions','manage_pages','read_stream','read_mailbox','read_friendlists')) . '">';
	//echo '<a href="' . $helper->getLoginUrl(array( 'public_profile','read_stream','read_mailbox','email','read_insights','manage_notifications','read_friendlists','publish_actions','user_location','user_likes','user_friends','user_status')) . '">Login with Facebook</a>';
}

?>