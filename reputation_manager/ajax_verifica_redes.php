<?php
session_start();

$path = "../adodb/adodb.inc.php";
include ("../admin/var.php");
include ("../conexion.php");

include ("../language/language".$_SESSION['idioma'].".php");
	
include "../admin/funciones.php";
include("../admin/funciones_start_session.php");

$variables_metodo = variables_metodo("existeFb,existeTw");
$existeFb= 		$variables_metodo[0];
$existeTw= 		$variables_metodo[1];

//////////////////////////////////////////////////////////////

// Consulta los datos de la cuenta de Twitter del usuario
$sql_tw = "SELECT nickname_tw,nombre_usuario_tw,imagen_tw,cantidad_tw,cantidad_sg_tw FROM ic_redes_usuario 
	   	   WHERE id_usuario=".$_SESSION['sess_usu_grupo']." AND tipo='TW'";	
$result_tw = $db->Execute($sql_tw);

// Consulta los datos de la cuenta de FaceBook del usuario	
$sql_fb ="SELECT nombre_usuario_fb,imagen_fb,cantidad_amg_fb FROM ic_redes_usuario 
	      WHERE id_usuario=".$_SESSION['sess_usu_grupo']." AND tipo='FB'";  
$result_fb = $db->Execute($sql_fb);

// Consulta los datos de la cuenta de LinkedIn del usuario	
$sql_lk ="SELECT nickname,nombre_usuario,imagen,cantidad_tw,cantidad_sg FROM ic_redes_usuario
	      WHERE id_usuario=".$_SESSION['sess_usu_grupo']." AND tipo='LK'";  
$result_lk = $db->Execute($sql_lk);

///////////////////////////////////////////////////////////////

$refrescar = "";
	
if(($result_tw->EOF && $existeTw=="") || (!$result_tw->EOF && $existeTw!="")){
	//$refrescar = "";
}else{
	// Precarga de informacion de redes del usuario
	$_SESSION['bdm_redes']      = cargar_redes($_SESSION['sess_usu_grupo'], $db);
	$refrescar = "REFRESCAR";	
}


if(($result_fb->EOF && $existeFb=="") || (!$result_fb->EOF && $existeFb!="")){
	//$refrescar = "";
}else{
	$refrescar = "REFRESCAR";	
}

echo $refrescar;

?>