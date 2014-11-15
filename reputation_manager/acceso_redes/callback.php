<?php
session_start();
ini_set('display_errors', '1');
date_default_timezone_set("America/Buenos_Aires");

include("../../admin/var.php");	
include("twitteroauth/twitteroauth.php");
	
if (!empty($_GET['oauth_verifier']) && !empty($_SESSION['oauth_token']) && !
    empty($_SESSION['oauth_token_secret'])) {

    $twitteroauth = new TwitterOAuth($var_twitter_app_id,$var_twitter_app_secret, 
									 $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

    $access_token = $twitteroauth->getAccessToken($_GET['oauth_verifier']);

    $_SESSION['access_token'] = $access_token;

    $user_info = $twitteroauth->get('account/verify_credentials');

    if (isset($user_info->error)) {

        session_destroy();
        unset($_SESSION);
        unset($_SESSION['oauth_token_secret']);
        unset($_SESSION['oauth_secret']);
        unset($_SESSION['oauth_token']);
        unset($_SESSION['access_token']);

        if ($user_info->error == "Rate limit exceeded. Clients may not make more than 350 requests per hour.") {
            echo "Demasiadas conexiones";
            exit();
        }

        header('Location: auth.php');

    } else {

        $path = "../../adodb/adodb.inc.php";
        include ("../../admin/var.php");
        include ("../../conexion.php");
        include ("../../admin/funciones.php");

        $idusuario 		       = $_SESSION['sess_usu_grupo'];
        $oauth_token_tw		   = $access_token['oauth_token'];
        $oauth_token_secret_tw = $access_token['oauth_token_secret'];
        $tipo 			       = "TW";
        $nombre_usuario_tw 	   = $user_info->name;
        $nickname_tw		   = $user_info->screen_name;
        $imagen_tw			   = $user_info->profile_image_url;
       	$cantidad_tw 		   = $user_info->statuses_count;
        $cantidad_sg_tw 	   = $user_info->followers_count;
        $fecha_generacion       = date('Y-m-d H:i:s');


        $sql = "INSERT INTO ic_redes_usuario (id_usuario,tipo,oauth_token_tw,oauth_token_secret_tw,nickname_tw,nombre_usuario_tw,imagen_tw,cantidad_tw,cantidad_sg_tw,fecha_generacion) VALUES ('$idusuario','$tipo','$oauth_token_tw', '$oauth_token_secret_tw','$nickname_tw','$nombre_usuario_tw','$imagen_tw','$cantidad_tw','$cantidad_sg_tw','$fecha_generacion')";

        $result = $db->Execute($sql);
		
		echo '<script type="text/javascript">window.close();</script>';
        
    } // fin else
} else {
	die("error");
    header('Location: auth.php');
}
?>
