<?php
session_start();

header("Content-Type: application/CSV");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Result_Mentions_BDM_".$_SESSION['bdm_etiquetas'][$id_etiqueta_mostrar]['id']."".date('siHdmy').".csv");


$path = "../adodb/adodb.inc.php";
include ("../admin/var.php");
include ("../conexion.php");
include ("../admin/funciones.php");
idioma_session($db);
include ("../language/language".$_SESSION['idioma'].".php");

$variables_metodo = variables_metodo("etiqueta,desde,hasta");
$id_etiqueta_mostrar= 	$variables_metodo[0];
$desde= 	$variables_metodo[1];
$hasta= 	$variables_metodo[2];

$where = "";

if($hasta==""){
	$where .= " AND a.fecha_cargue <='".$hasta."'";
}
if($desde==""){
	$where .= " AND a.fecha_cargue >='".$desde."'";
}

//------------------------------------------------

$sql="SELECT 
		a.id,a.id_rss,a.cargue,a.marcada,a.positivo,
		a.negativo,a.bloqueada,a.intervenido,a.detalle_intervenido,a.id_categoria,
		a.titulo,a.contenido,a.link,a.semana,a.neutro,a.id_source,a.fecha_cargue
	  FROM ic_rss_coincidencias a
	  WHERE
		a.id_etiqueta=".$id_etiqueta_mostrar." 
	  ORDER BY a.id DESC ";
$result_coincidencias_finales=$db->Execute($sql);


//------------------------------------------------

while(!$result_coincidencias_finales->EOF){
	
	list($id,$id_rss,$cargue,$marcada,$positivo,$negativo,$bloqueada,$intervenido,$detalle_intervenido,
	     $cat_id,$titulo,$contenido,$link,$semana,$neutro,$id_source,$fecha_cargue)=$result_coincidencias_finales->fields;

	echo $fecha_cargue.";".$semana.";".utf8_decode($titulo).";".utf8_decode($contenido).";".$link.";".$positivo.";".$neutro.";".$negativo.";".$marcada.";".$intervenido.";".$bloqueada."\n";

	$result_coincidencias_finales->MoveNext();
}
?>