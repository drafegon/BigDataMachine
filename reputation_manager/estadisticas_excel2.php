<?php
session_start();

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Result_BDM_".$_SESSION['bdm_etiquetas'][$id_etiqueta_mostrar]['id']."".date('siHdmy').".xls");

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
		a.fecha,a.categoria,a.id_rss,a.semana,b.cat_titulo,c.nombre,c.url_rss,d.etiqueta,sum(a.total)
	  FROM ic_estadisticas a, ic_categoria b, ic_rss c, ic_etiquetas d
	  WHERE
	  	a.categoria=b.cat_id AND a.id_rss=c.id_rss AND a.id_etiqueta=d.id AND
		a.id_etiqueta=".$id_etiqueta_mostrar."
      GROUP BY a.fecha,a.categoria,a.id_rss,a.semana,b.cat_titulo,c.nombre,c.url_rss,d.etiqueta
	  ORDER BY a.fecha DESC ";
$result_coincidencias_finales=$db->Execute($sql);


//------------------------------------------------

?>
<table border="1" cellspacing="0" cellpadding="0">
  <tr>
    <th bgcolor="#CCCCCC">Fecha</th>
    <th bgcolor="#CCCCCC">Semana</th>
    <th bgcolor="#CCCCCC">Etiqueta</th>
    <th bgcolor="#CCCCCC">Categoria</th>
    <th bgcolor="#CCCCCC">Origen</th>
    <th bgcolor="#CCCCCC">Total</th>    
  </tr>
<?php
while(!$result_coincidencias_finales->EOF){
	
	list($fecha,$categoria,$id_rss,$semana,$cat_titulo,$nombre,$link,$nom_eti,$total)=$result_coincidencias_finales->fields;

?>
  <tr>
    <td><?=$fecha?></td>
    <td><?=$semana?></td>
    <td><?=utf8_decode($nom_eti)?></td>
    <td><?=utf8_decode($cat_titulo)?></td>
    <td><?=(($nombre=="")?utf8_decode($link):utf8_decode($nombre))?></td>
    <td><?=$total?></td>    
  </tr>  
<?php
	$result_coincidencias_finales->MoveNext();
}
?>
</table>
