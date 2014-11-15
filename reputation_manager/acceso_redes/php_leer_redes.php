<?php
set_time_limit (30);
/*
ini_set('max_execution_time',9000); //tiempo limite de ejecucion de un escript en segundos.
ini_set("memory_limit","1500M"); // aumentamos la memoria a 1,5GB
ini_set("buffering ","0");
*/



	$path = "../../adodb/adodb.inc.php";
    include ("../../admin/var.php");
	include ("../../conexion.php");
	include ("../../admin/funciones.php");
	include ("../../admin/funciones_busqueda.php");
	
//////////////////////////////////////////////////////

$variables_metodo = variables_metodo("etiqueta");
$etiqueta_nueva	=	$variables_metodo[0];

//////////////////////////////////////////////////////

//inclusion para codigo de twitter
include("twitter.php");

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



?>