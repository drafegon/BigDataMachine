<?php
session_start();

$path = "../adodb/adodb.inc.php";
include ("../admin/var.php");
include ("../conexion.php");
	
include "../admin/funciones.php";
include("../admin/funciones_start_session.php");

include ("../language/language".$_SESSION['idioma'].".php");

$variables_metodo = variables_metodo("etiqueta");
$etiqueta= 		$variables_metodo[0];
	
?>
<?=$_SESSION['bdm_etiquetas'][$etiqueta]['etiqueta'].
   " (".$_SESSION['bdm_etiquetas'][$etiqueta]['nuevos'].
   " "._BDM_PORREVI." "._BDM_DE." ".$_SESSION['bdm_etiquetas'][$etiqueta]['total'].
   " "._BDM_RESULT.")";?>