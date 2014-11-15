<?php
session_start();
header('Content-Type: application/json');

$path = "../adodb/adodb.inc.php";
include ("../admin/var.php");
include ("../conexion.php");

include ("../language/language".$_SESSION['idioma'].".php");
	
include "../admin/funciones.php";

$variables_metodo = variables_metodo("funcion,etiqueta");
$funcion= 		$variables_metodo[0];
$etiqueta= 		$variables_metodo[1];

$array_menu = array();
$array_busquedas = array();
$array_proyectos = array();

$separador = "";
$sin_proyecto = false;

foreach($_SESSION['bdm_etiquetas'] as $dato){
	
	if($dato['nombre_proyecto']==""){
		$proyecto=0;
		$sin_proyecto = true;
	}else{
		$proyecto=$dato['id_proyecto'];
	}
	
	$busqueda = array("".$dato['id']."" => array("id"=>$dato['id'],
	                                             "etiqueta"=>$dato['etiqueta'],
												 "total"=>formateo_numero($dato['total']),
												 "id_proyecto"=>$proyecto,	
												 "estado"=>$dato['estado'],											 
												 "detalle"=>'<i>('.$dato['fecha_creacion'].') '.(($dato['estado']=="A")?""._BDM_ACTISEARCH."":""._BDM_INACTISEARCH."").' '.(($dato['clipping']=="S")?"Clipping":"").'</i>',
												 "modificar"=>_REPUTA_MODIF));
	
	array_push($array_busquedas, $busqueda);
	
	///////////////////////////////////////
	
	if($dato['nombre_proyecto']!="" && $dato['nombre_proyecto']!=$separador){
		array_push($array_proyectos, array("".$dato['id_proyecto']."" => array("id_proyecto"=>$dato['id_proyecto'],
		                                                                       "nombre_proyecto"=>_BDM_PROYECTO . ' ' . $dato['nombre_proyecto'],
																			   "eti_protecto"=>formateo_numero($dato['eti_protecto'])) ) );
		$separador = $dato['nombre_proyecto'];
	}
}

if($sin_proyecto){
	array_unshift($array_proyectos, array("0"=>array("id_proyecto"=>"0",
													 "nombre_proyecto"=>_REPUTA_MISBUS,
													 "eti_protecto"=>"")));
}

array_push($array_menu, array("etiquetas_usuario"=>$array_busquedas));
array_push($array_menu, array("proyectos_usuario"=>$array_proyectos));

echo json_encode($array_menu);
?>