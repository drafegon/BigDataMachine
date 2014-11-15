<?php
session_start();

$path = "../adodb/adodb.inc.php";
include ("../admin/var.php");
include ("../conexion.php");
	
include "../admin/funciones.php";
include("../admin/funciones_start_session.php");
include ("../language/language".$_SESSION['idioma'].".php");

//precarga info etiquetas
$_SESSION['bdm_etiquetas'] = cargar_etiquetas_usuario( $_SESSION['bdm_user']['gru_id'], $db ); 

echo "OK";	
	
?>