<?php
include "../cabecera.php";
include "../admin/funciones_start_session.php";

$variables_metodo = variables_metodo("funcion,id,nombre_etiqueta,usuario_cliente,fecha_creacion,idioma,quien_enlaza,busqueda_estricta,descartar_url,sinonimos,calificacion_posi,calificacion_nega,intervenciones,nuevos,total,mensaje,descartar_palabras,max_sino,max_cat,categorias,ecat_tag,paises,es_sinonimo,etiqueta,id_proyecto,estado,tipo_busqueda,mensaje,or_option");

$funcion= 			$variables_metodo[0];
$id= 				$variables_metodo[1];
$nombre_etiqueta= 	$variables_metodo[2];
$usuario_cliente= 	$variables_metodo[3];
$fecha_creacion= 	$variables_metodo[4];
$idioma= 			$variables_metodo[5];
$quien_enlaza= 		$variables_metodo[6];
$busqueda_estricta= $variables_metodo[7];
$descartar_url= 	$variables_metodo[8];
$sinonimos= 		$variables_metodo[9];
$calificacion_posi= $variables_metodo[10];
$calificacion_nega= $variables_metodo[11];
$intervenciones= 	$variables_metodo[12];
$nuevos= 			$variables_metodo[13];
$total= 			$variables_metodo[14];
$mensaje= 			$variables_metodo[15];
$descartar_palabras=$variables_metodo[16];
$max_sino= 			$variables_metodo[17];
$max_cat= 			$variables_metodo[18];
$categorias= 		$variables_metodo[19];
$ecat_tag= 			$variables_metodo[20];
$paises= 			$variables_metodo[21];
$es_sinonimo= 		$variables_metodo[22];
$id_etiqueta_mostrar=$variables_metodo[23];
$id_proyecto= 		$variables_metodo[24];
$estado= 			$variables_metodo[25];
$tipo_busqueda= 	$variables_metodo[26];
$mensaje= 			$variables_metodo[27];
$or_option= 		$variables_metodo[28];

if ($funcion == "guardar"){
	if($id==""){
	/*Funcion para guardar los datos del formulario*/	
		guardar($nombre_etiqueta,$usuario_cliente,$fecha_creacion,$idioma,$quien_enlaza,$busqueda_estricta,$descartar_url,
			    $sinonimos,$calificacion_posi,$calificacion_nega,$intervenciones,$nuevos,$total,$descartar_palabras,$max_sino,
				$max_cat,$categorias,$paises,$es_sinonimo,$id_proyecto,$estado,$tipo_busqueda,$or_option,$db);
	}elseif($id!=""){
	/*Funcion para modificar los datos del formulario*/	
		modificar($id,$nombre_etiqueta,$usuario_cliente,$fecha_creacion,$idioma,$quien_enlaza,$busqueda_estricta,$descartar_url,
				  $sinonimos,$calificacion_posi,$calificacion_nega,$intervenciones,$nuevos,$total,$descartar_palabras,$max_sino,
				  $max_cat,$categorias,$ecat_tag,$paises,$es_sinonimo,$id_proyecto,$tipo_busqueda,$or_option,$db);
	}	
}elseif(($funcion == "borrar")&&($id!="")){
	borrar($id, $db);
}elseif(($funcion == "reiniciar")&&($id!="")){
	reiniciar($id, $db);
}elseif(($funcion == "pausar")&&($id!="")){
	pausar($id, $estado, $db);
}

/****************************************************************************************************************************/

function borrar($id, $db)
{
	$sql="SELECT etiqueta,sinonimos FROM ic_etiquetas WHERE id = '".$id."'";
	$sino=$db->Execute($sql);
	list($etiqueta,$sinonimos)=$sino->fields;
	
	$result=$db->Execute("DELETE FROM ic_etiquetas WHERE id IN (".$sinonimos.",0)");
	
	$result=$db->Execute("DELETE FROM ic_rss_coincidencias WHERE id_etiqueta = '".$id."'");
	$result=$db->Execute("DELETE FROM ic_rss_usu_etiq WHERE id_etiqueta = '".$id."'");
	$result=$db->Execute("DELETE FROM ic_etiquetas_categoria WHERE id_etiqueta = '".$id."' AND id_usuario = '".$_SESSION['sess_usu_grupo']."' ");	
	$result=$db->Execute("DELETE FROM ic_etiquetas WHERE id = '".$id."' ");
	$result=$db->Execute("DELETE FROM ic_coincidencia_directorio WHERE id_coincidencia = '".$id."' ");
	$result=$db->Execute("DELETE FROM ic_estadisticas WHERE id_etiqueta = '".$id."' ");
	
	if ($result != false) $mensaje = "3";
	else $mensaje  = "0";
			
	//precarga info etiquetas
	$_SESSION['bdm_etiquetas']     = cargar_etiquetas_usuario( $_SESSION['sess_usu_grupo'], $db ); 
	
	//--------------
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=".$_SESSION['c_base_location']."reputation_manager/eliminando_informacion.php?etiqueta=0&nombre=".$etiqueta."'>";
	die();
}

/****************************************************************************************************************************/

function reiniciar($id, $db)
{
	$result=$db->Execute("DELETE FROM ic_rss_coincidencias WHERE id_etiqueta = '".$id."'");
	$result=$db->Execute("UPDATE ic_rss_usu_etiq SET nuevos = 0, total = 0 WHERE id_etiqueta = '".$id."'");
	$result=$db->Execute("UPDATE ic_etiquetas_categoria SET nuevos = 0, total = 0 WHERE id_etiqueta = '".$id."' AND id_usuario = '".$_SESSION['sess_usu_grupo']."' ");
	$result=$db->Execute("UPDATE ic_etiquetas SET nuevos=0, total=0, calificacion_posi=0, calificacion_nega=0,calificacion_neutra=0 WHERE id = '".$id."' ");
	$result=$db->Execute("DELETE FROM ic_estadisticas WHERE id_etiqueta = '".$id."' ");
	
	if ($result != false) $mensaje = "3";
	else $mensaje  = "0";
			
	//precarga info etiquetas
	$_SESSION['bdm_etiquetas']     = cargar_etiquetas_usuario( $_SESSION['sess_usu_grupo'], $db ); 
	
	//--------------
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=".$_SESSION['c_base_location']."reputation_manager/reiniciando_informacion.php?etiqueta=".$id."&reiniciar=true'>";
	die();
}

/****************************************************************************************************************************/

function pausar($id,$estado, $db)
{
	$result=$db->Execute("UPDATE ic_etiquetas SET estado='".(($estado!='A')?'A':'P')."' WHERE id='".$id."' ");

	if ($result != false) $mensaje = "3";
	else $mensaje  = "0";
			
	//--------------
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=".$_SESSION['c_base_location']."reputation_manager/crear_tags.php?etiqueta=".$id."&mensaje=pausar'>";
	die();
}

/****************************************************************************************************************************/

function guardar($nombre_etiqueta,$usuario_cliente,$fecha_creacion,$idioma,$quien_enlaza,$busqueda_estricta,$descartar_url,$sinonimos,
                 $calificacion_posi,$calificacion_nega,$intervenciones,$nuevos,$total,$descartar_palabras,$max_sino,$max_cat,$categorias,
				 $paises,$es_sinonimo,$id_proyecto,$estado,$tipo_busqueda,$or_option,$db)
{	
	$sino_select="";	
	for($i=1; $i<=$max_sino; $i++){
		if(isset($sinonimos[$i])){
			$sino_select.=$sinonimos[$i];
			
			if(($i+1) <= $max_sino){
				$sino_select.=",";
			}
		}
	}
	
	if($busqueda_estricta==""){
		$busqueda_estricta="N";
	}
	
	
	$listado_pises="";
	if($paises!=""){
		foreach ($paises as $pais) {
			$listado_pises .= "*".$pais."*";
		}
	}
	
	if($id_proyecto==""){
		$id_proyecto = 0;	
	}
	
	if($estado==""){
		$estado = "A";	
	}
	
	if($or_option=="and"){
		$or_option="";
	}elseif($or_option=="xor"){
		$or_option="X";
	}elseif($or_option=="or"){
		$or_option="S";
	}
	
	$result=insert_bd_format("etiqueta,usuario_cliente,fecha_creacion,idioma,quien_enlaza,busqueda_estricta,descartar_url,sinonimos,descartar_palabras,paises,es_sinonimo,id_proyecto,estado,tipo_busq,or_option", 
							"ic_etiquetas", 
							array($nombre_etiqueta,$usuario_cliente,$fecha_creacion,$idioma,$quien_enlaza,$busqueda_estricta,$descartar_url,$sino_select,$descartar_palabras, $listado_pises,$es_sinonimo,$id_proyecto,$estado,$tipo_busqueda,$or_option), 
							$db);

	//--------------
	
	$sql="SELECT MAX(id) FROM ic_etiquetas WHERE usuario_cliente='".$usuario_cliente."' AND fecha_creacion='".$fecha_creacion."'";
	$result=$db->Execute($sql);
	list($id)=select_format($result->fields);
	
	//----------------------------------------------
	
	$sino = explode(",",$sinonimos);
	if(count($sino)>0){
			
		for($i=0; $i<count($sino);$i++){
			if($sino[$i]!=""){
				$result_sin=insert_bd_format("etiqueta,usuario_cliente,fecha_creacion,idioma,quien_enlaza,busqueda_estricta,descartar_url,sinonimos,descartar_palabras,paises,es_sinonimo", 
										"ic_etiquetas", 
										array($sino[$i],$usuario_cliente,$fecha_creacion,'','','N','',$id,'','','S'), 
										$db);

			}	
		}

		$sql="SELECT id,etiqueta FROM ic_etiquetas WHERE sinonimos='".$id."'";
		$result_sino=$db->Execute($sql);
		
		$sino_select="";
		while(!$result_sino->EOF){
			list($id_sino,$etiqueta_sino)=$result_sino->fields;
			
			$sino_select.=$id_sino.",";
			
			$result_sino->MoveNext();
		}
		
		$sql="UPDATE ic_etiquetas SET sinonimos=NULL WHERE id IN (".$sino_select."0".")";
		$result_sinom=$db->Execute($sql);
		
		$sql="UPDATE ic_etiquetas SET sinonimos='".$sino_select."' WHERE id='".$id."'";
		$result_sinom=$db->Execute($sql);
	}
	
	//----------------------------------------------
	
	$result2=$db->Execute("DELETE FROM ic_etiquetas_categoria WHERE id_usuario = '".$_SESSION['sess_usu_grupo']."' AND id_etiqueta='".$id."' ");
	
	for($j=1; $j<=$max_cat; $j++){
		
		if(isset($categorias[$j])){
			$result3=insert_bd_format("id_usuario,id_etiqueta,id_categoria,nuevos,total", 
			                          "ic_etiquetas_categoria", 
									  array($_SESSION['sess_usu_grupo'],$id,$categorias[$j],"0","0"), 
									  $db);
			
			$sql="SELECT id_rss,nombre,url_rss,icono 
			      FROM ic_rss 
			      WHERE categoria='".$categorias[$j]."' AND (usuario='0' OR usuario='".$_SESSION['sess_usu_grupo']."') AND activo NOT IN ('N')";
			$result4=$db->Execute($sql);
			
			while(!$result4->EOF){				
				list($id_rss,$nombre,$url_rss,$icono)=select_format($result4->fields);
				
				$result5=insert_bd_format("id_usuario,id_etiqueta,id_rss,id_categoria,nombre_rss,url_rss,icono,nuevos,total,activo", 
										 "ic_rss_usu_etiq", 
										 array($_SESSION['sess_usu_grupo'], $id, $id_rss, $categorias[$j], $nombre, $url_rss, $icono,"0","0","S"), 
										 $db);
				
				$result4->MoveNext();
			}
		}
	}	
	
	//--------------
	
	if ($result != false) $mensaje = "1";
	else $mensaje  = "0";
	
	//--------------
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=".$_SESSION['c_base_location']."reputation_manager/cargando_informacion.php?etiqueta=".$id."&nuevo=true&estado=".$estado."'>";
	die();	
}

/****************************************************************************************************************************/

function modificar($id,$nombre_etiqueta,$usuario_cliente,$fecha_creacion,$idioma,$quien_enlaza,$busqueda_estricta,$descartar_url,$sinonimos,
                   $calificacion_posi,$calificacion_nega,$intervenciones,$nuevos,$total,$descartar_palabras,$max_sino,$max_cat,$categorias,
				   $ecat_tag,$paises,$es_sinonimo,$id_proyecto,$tipo_busqueda,$or_option,$db)
{
	if($busqueda_estricta==""){
		$busqueda_estricta="N";
	}
	
	//------------------------------------------
	
	if($quien_enlaza==""){
		$quien_enlaza="N";
	}
	
	//------------------------------------------
	
	$listado_pises="";
	$listado_where = "";
	
	if($paises!=""){
		foreach ($paises as $pais) {
			$listado_pises .= "*".$pais."*";
			$listado_where .= " OR paises LIKE '%".$pais."%'";
		}
		
		$listado_where = "AND ( 1=1 ".$listado_where.")";
	}
	
	//------------------------------------------
	
	$sino = explode(",",$sinonimos);
	if(count($sino)>0){
		
		////////////////////////////////////////////////////////////
		//Selecciono sinonimos de la etiqueta
		$sql = "SELECT sinonimos FROM ic_etiquetas WHERE id='".$id."'";
		$result=$db->Execute($sql);
		list($sinos)=$result->fields;
		//Eliminio sinonimos actuales
		$sql = "DELETE FROM ic_etiquetas WHERE id IN (".$sinos."0".") ";
		$result=$db->Execute($sql);
		
		////////////////////////////////////////////////////////////
		
		for($i=0; $i<count($sino);$i++){
			if(trim($sino[$i])!=""){
				$result_sin=insert_bd_format("etiqueta,usuario_cliente,fecha_creacion,idioma,quien_enlaza,busqueda_estricta,descartar_url,sinonimos,descartar_palabras,paises,es_sinonimo", 
										"ic_etiquetas", 
										array($sino[$i],$usuario_cliente,$fecha_creacion,'','','N','',$id,'','','S'), 
										$db);

			}	
		}
		
		//////////////////////////////////////////////////////////
		
		$sql="SELECT id,etiqueta FROM ic_etiquetas WHERE sinonimos='".$id."'";
		$result_sino=$db->Execute($sql);
		
		$sino_select="";
		while(!$result_sino->EOF){
			list($id_sino,$etiqueta_sino)=$result_sino->fields;
			
			$sino_select.=$id_sino.",";
			
			$result_sino->MoveNext();
		}
		
		$sql="UPDATE ic_etiquetas SET sinonimos=NULL WHERE id IN (".$sino_select."0".")";
		$result_sinom=$db->Execute($sql);
	}
	
	if($or_option=="and"){
		$or_option="";
	}elseif($or_option=="xor"){
		$or_option="X";
	}elseif($or_option=="or"){
		$or_option="S";
	}
	
	//------------------------------------------
	
	$result=update_bd_format(array("etiqueta","usuario_cliente","idioma","quien_enlaza","busqueda_estricta","descartar_url",
								   "sinonimos","descartar_palabras","paises","es_sinonimo","id_proyecto","tipo_busq","or_option"), 
	                         "ic_etiquetas", 
	                         array($nombre_etiqueta,$usuario_cliente,$idioma,$quien_enlaza,$busqueda_estricta,$descartar_url,
							       $sino_select,$descartar_palabras,$listado_pises,$es_sinonimo,$id_proyecto,$tipo_busqueda,$or_option), 
							 "WHERE id='".$id."'",
							 $db);
	//--------------

	$categorias_seleccionadas="";
	
	for($j=1; $j<=$max_cat; $j++){
		
		
		if(isset($categorias[$j])){
			
			//Concateno las categorias enviadas
			$categorias_seleccionadas.=$categorias[$j].",";

			//----------------------
			//verifico cuales existen o si son nuevas
			$cat_actuales = explode(",",$ecat_tag);
			$existe = false;
			
			for($t=0; $t<count($cat_actuales); $t++){
				
				if($cat_actuales[$t]==$categorias[$j]){
					$existe = true;
					break;
				}
			}
			
			//----------------------
			//Si existe calculo si hay nuevos link y los incluyo a la categoria
			if($existe){
				$sql="SELECT id_rss,nombre,url_rss,icono 
			      FROM ic_rss 
			      WHERE categoria='".$categorias[$j]."' AND (usuario ='0' OR usuario='".$_SESSION['sess_usu_grupo']."') AND activo!='N'
				  AND id_rss NOT IN (SELECT id_rss FROM ic_rss_usu_etiq 
				  				 	 WHERE id_usuario='".$_SESSION['sess_usu_grupo']."' AND id_etiqueta='".$id."' AND id_categoria='".$categorias[$j]."')
				  ".$listado_where."";
				$result4=$db->Execute($sql);
				
				while(!$result4->EOF){				
					list($id_rss,$nombre,$url_rss,$icono)=select_format($result4->fields);
					
					$result5=insert_bd_format("id_usuario,id_etiqueta,id_rss,id_categoria,nombre_rss,url_rss,icono,nuevos,total,activo", 
											 "ic_rss_usu_etiq", 
											 array($_SESSION['sess_usu_grupo'], $id, $id_rss, $categorias[$j], $nombre, $url_rss, $icono,"0","0","S"), 
											 $db);
					
					$result4->MoveNext();
				}
			}else{
				$result3=insert_bd_format("id_usuario,id_etiqueta,id_categoria,nuevos,total", 
				                          "ic_etiquetas_categoria", 
										  array($_SESSION['sess_usu_grupo'],$id,$categorias[$j],"0","0"), 
										  $db);
			
				$sql="SELECT id_rss,nombre,url_rss,icono 
					  FROM ic_rss 
					  WHERE categoria='".$categorias[$j]."' AND (usuario='0' OR usuario='".$_SESSION['sess_usu_grupo']."') 
					  AND activo NOT IN ('N') ".$listado_where."";
				$result4=$db->Execute($sql);
				
				while(!$result4->EOF){				
					list($id_rss,$nombre,$url_rss,$icono)=select_format($result4->fields);
					
					$result5=insert_bd_format("id_usuario,id_etiqueta,id_rss,id_categoria,nombre_rss,url_rss,icono,nuevos,total,activo", 
											 "ic_rss_usu_etiq", 
											 array($_SESSION['sess_usu_grupo'], $id, $id_rss, $categorias[$j], $nombre, $url_rss, $icono,"0","0","S"), 
											 $db);
					
					$result4->MoveNext();
				}
			}
			
		}
		
	}
	
	
	//Elimino todas las categorias que no se seleccionaron en la pantalla
	$sql="DELETE FROM ic_etiquetas_categoria 
	      WHERE id_usuario = '".$_SESSION['sess_usu_grupo']."' 
		        AND id_etiqueta='".$id."' 
				AND id_categoria NOT IN (".substr($categorias_seleccionadas, 0, (strlen($categorias_seleccionadas)-1)).")";
	$result_del=$db->Execute($sql);
	
	$sql = "DELETE FROM ic_rss_usu_etiq 
	        WHERE id_usuario = '".$_SESSION['sess_usu_grupo']."' 
			      AND id_etiqueta='".$id."' 
				  AND id_categoria NOT IN (".substr($categorias_seleccionadas, 0, (strlen($categorias_seleccionadas)-1)).")";
	$result_del=$db->Execute($sql);
				   
	//--------------
	
	if ($result != false) $mensaje = "2";
	else $mensaje  = "0";
	
	//--------------
	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=".$_SESSION['c_base_location']."reputation_manager/crear_tags.php?etiqueta=".$id."&mensaje=modificar'>";
	die();
}

/****************************************************************************************************************************/

$check2 = "";
$urlsagregadas = "";
$urlsmax = "0";
$palabrasagregadas = "";
$palabrasmax = "0";
$sino = "";
$$sinoagregadasand = "";
$$sinoagregadasxor = "";
$$sinoagregadasor = "";
$sinomax = "0";
$checkcat2="checked";
$checkcat3="checked";
$checkcat4="checked";
$checkcat5="checked";
$ecat_tag = "";
$checkOr1="checked";
$checkOr2="";
$checkOr3="";
$or_option="and";

$nombre_paquete = obtenerDescripcion("valor,paquete", " AND parametro='NOMBRE_PAQUETE' ", "ic_cuenta_usuarios", $db);
$palabras_paquete = obtenerDescripcion("valor", " AND parametro='BUSQUEDAS' ", "ic_cuenta_usuarios", $db);

$msq_boton = _REPUTA_CREAR;
$msq_tam = 170;
$msq_pad = 70;
$paisesUsuario = array();	

$sql="SELECT valor FROM ic_cuenta_usuarios WHERE parametro='PRECISION' AND id_grupo_usuario='".$_SESSION['bdm_user']['gru_id']."'";
$result_config=$db->Execute($sql);
list($precision)=$result_config->fields;

if($precision==""){
	$precision=0;
}
		
if ($id_etiqueta_mostrar != ""){
	$msq_boton = _REPUTA_MODIF;
	$msq_tam = 170;
	$msq_pad = 40;
	
	$sql="SELECT id,etiqueta,usuario_cliente,fecha_creacion,idioma,quien_enlaza,
				 busqueda_estricta,descartar_url,sinonimos,calificacion_posi,calificacion_nega,
				 intervenciones,nuevos,total,descartar_palabras,paises,es_sinonimo,nuevos,total,
				 id_proyecto,estado,tipo_busq,or_option
		  FROM ic_etiquetas WHERE id='".$id_etiqueta_mostrar."' ";
	$result_contenido=$db->Execute($sql);
	
	list($id,$nombre_etiqueta,$usuario_cliente,$fecha_creacion,$idioma,$quien_enlaza,$busqueda_estricta,
	     $descartar_url,$sinonimos,$calificacion_posi,$calificacion_nega,$intervenciones,$nuevos,$total,
		 $descartar_palabras,$paises,$es_sinonimo,$nuevos,$total,$id_proyecto,$estado,$tipo_busqueda,$or_option)=select_format($result_contenido->fields);
	
	////////////////////////////
	
	$sql="SELECT id,etiqueta FROM ic_etiquetas WHERE id IN (".$sinonimos."0)";
	$result_sino=$db->Execute($sql);
	$sinomax = 0;
	$sinonimos = "";
	
	while(!$result_sino->EOF){
		list($id_sino,$etiqueta_sino)=$result_sino->fields;
		$sinomax++;
		
		$sino .= "<div id='sinodes".$sinomax."' onclick='$( this ).remove(); refrescarSino();'><div style='cursor: pointer; background: url(images/admin/eliminar.png) no-repeat right;'><b>".$etiqueta_sino."</b></div><div class='separadorCasillas'></div></div>";
		
		$sinonimos .= "".$etiqueta_sino.",";
		
		$result_sino->MoveNext();
	}
		
	////////////////////////////
	
	if($or_option=="S"){
		$checkOr2 = "checked";
		$or_option="or";
		$sinoagregadasor = $sino;
	}elseif($or_option=="X"){
		$checkOr3 = "checked";
		$or_option="xor";
		$sinoagregadasxor = $sino;
	}else{
		$or_option = "and";
		$checkOr1 = "checked";
		$sinoagregadasand = $sino;
	}
	
	if($busqueda_estricta=="S"){
		$check2 = "checked";
	}
	////////////////////////////
	
	if($descartar_url!=""){
		$urls = explode(",",$descartar_url);		
		
		for($i=0; $i<=count($urls); $i++){
			if(array_key_exists($i, $urls) && $urls[$i]!=""){
				$urlsagregadas .= "<div id='urldes".$i."' onclick='$( this ).remove(); refrescarUrl();'><div style='cursor: pointer; background: url(images/admin/eliminar.png) no-repeat right;'><b>".$urls[$i]."</b></div><div class='separadorCasillas'></div></div>";
			}
		}
		
		$urlsmax = $i+1;
	}
	
	////////////////////////////
	
	if($descartar_palabras!=""){
		$palabras = explode(",",$descartar_palabras);		
		
		for($i=0; $i<=count($palabras); $i++){
			if(array_key_exists($i, $palabras) && $palabras[$i]!=""){
				$palabrasagregadas .= "<div id='palabrades".$i."' onclick='$( this ).remove(); refrescarUrl();'><div style='cursor: pointer; background: url(images/admin/eliminar.png) no-repeat right;'><b>".$palabras[$i]."</b></div><div class='separadorCasillas'></div></div>";
			}
		}
		
		$palabrasmax = $i+1;
	}
	
	////////////////////////////

	$paises_list= explode("**", $paises);	
	
	if(count($paises_list)>0){
		for($u=0; $u<count($paises_list); $u++){
			array_push($paisesUsuario, str_replace("*", "", $paises_list[$u]));
		}
	}else{
		array_push($paisesUsuario, str_replace("*", "", $paises));
	}

	////////////////////////////
	
	$sql="SELECT id_categoria FROM ic_etiquetas_categoria 
		  WHERE id_usuario='".$_SESSION['sess_usu_grupo']."' AND id_etiqueta='".$id."' AND id_categoria='2' ";
	$result_eticat2=$db->Execute($sql);		
	if(!$result_eticat2->EOF){
		$checkcat2="checked";
		$ecat_tag .= "2";
	}else{
		$checkcat2="";
	}
	$sql="SELECT id_categoria FROM ic_etiquetas_categoria 
		  WHERE id_usuario='".$_SESSION['sess_usu_grupo']."' AND id_etiqueta='".$id."' AND id_categoria='3' ";
	$result_eticat3=$db->Execute($sql);		
	if(!$result_eticat3->EOF){
		$checkcat3="checked";
		if($ecat_tag!=""){
			$ecat_tag .= ",";
		}
		$ecat_tag .= "3";
	}else{
		$checkcat3="";
	}
	$sql="SELECT id_categoria FROM ic_etiquetas_categoria 
		  WHERE id_usuario='".$_SESSION['sess_usu_grupo']."' AND id_etiqueta='".$id."' AND id_categoria='4' ";
	$result_eticat4=$db->Execute($sql);		
	if(!$result_eticat4->EOF){
		$checkcat4="checked";
		if($ecat_tag!=""){
			$ecat_tag .= ",";
		}
		$ecat_tag .= "4";
	}else{
		$checkcat4="";
	}
	$sql="SELECT id_categoria FROM ic_etiquetas_categoria 
		  WHERE id_usuario='".$_SESSION['sess_usu_grupo']."' AND id_etiqueta='".$id."' AND id_categoria='5' ";
	$result_eticat5=$db->Execute($sql);		
	if(!$result_eticat5->EOF){
		$checkcat5="checked";
		if($ecat_tag!=""){
			$ecat_tag .= ",";
		}
		$ecat_tag .= "5";
	}else{
		$checkcat5="";
	}
}


if(count($_SESSION['bdm_etiquetas'])<=0 && $_SESSION['bdm_mostrarinstruccion']==false){
	$_SESSION['bdm_mostrarinstruccion'] = true;
	
	include("introduccion.php");
}

if($mensaje!=""){
	echo '<div id="aviso_nuevos" style="width:300px; padding:20px 30px; background:#333; color:#FFF; text-align:center; position:absolute; top: 30px; cursor:pointer; left:50%; margin-left:-150px; display:inherit;" class="redondeado sombra" onclick="$(this).fadeOut();">';
	
	if($mensaje=="modificar"){ echo _REPUTA_BUSMOD; }elseif($mensaje=="pausar"){ echo _REPUTA_BUSPAU; } 
	
	echo '</div>
	<script language="javascript" type="text/javascript">
		$.ajax({
		  url: "reputation_manager/ajax_control_pantalla.php",
		  async:true,   
		  cache:false,  
		  dataType:"html",
		  type: "POST", 
		  data: { etiqueta: "'.$id.'", funcion: "UPD"},
		  success: function(datos_recibidos) {			 
			 if(!/NO/.test(datos_recibidos)){				 
				datos_recibidos = datos_recibidos.split("|");
				
				$("#htmlSelect", window.parent.document).hide();
				$("#htmlSelect", window.parent.document).html(datos_recibidos[0]);
				$("#htmlSelect", window.parent.document).fadeIn();
				
				construirMenu(\''.$id.'\');					
			 }
		  }
	   });
	setTimeout("$(\'#aviso_nuevos\').fadeOut();",4000);
	</script>';
}
?>
<!-- ///////////////////////////////////////////////////////////////////////////////////////////////////// -->

<form action="" name="eliminar" id="eliminar" method="post" target="_self">
<input type="hidden" name="funcion" value="borrar">
<input type="hidden" name="id" value="<?=$id?>">
</form>

<form action="" name="reiniciar" id="reiniciar" method="post" target="_self">
<input type="hidden" name="funcion" value="reiniciar">
<input type="hidden" name="id" value="<?=$id?>">
</form>

<form action="" name="pausar" id="pausar" method="post" target="_self">
<input type="hidden" name="funcion" value="pausar">
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="estado" value="<?=$estado?>">
</form>

<form action="" name="guardar" id="guardar" method="post" target="_self">
<input type="hidden" name="funcion" value="guardar">
<input type="hidden" name="usuario_cliente" value="<?=$_SESSION['sess_usu_grupo']?>">
<input type="hidden" name="fecha_creacion" value="<?=date('Y-m-d')?>">
<input type="hidden" name="id" value="<?=$id?>">

<!-- ///////////////////////////////////////////////////////////////////////////////////////////////////// -->

<div id="seccion1" style="width:410px; min-height:<?=$msq_tam?>px; margin: 140px auto; background:#F25630; z-index:50; position:relative;" class="redondeado sombra">
  <div style="padding: <?=$msq_pad?>px 0 0 0; position:relative;" align="center" >
    <div class="columna" style="width:240px; float:left; margin:0 15px 20px 20px;">
      <input name="nombre_etiqueta" type="text" id="nombre_etiqueta" value="<?=(($nombre_etiqueta=="")?_REPUTA_NEW_PALA:$nombre_etiqueta)?>" style="width:240px; height:22px; font-size:18px; color:#F25630;" title="<?=_REPUTA_KEY?>" onfocus="if(this.value!='<?=$nombre_etiqueta?>'){ this.value=''; }" onkeypress="return validarn(event);" onkeydown="return eatenter(event);"/>
    </div>
    <div class="columna" style="float:left; width:120px;">
      <?php
	$mostrar_boton = true;
	if($nombre_etiqueta==""){
		$total_etiquetas = count($_SESSION['bdm_etiquetas']);
		
		////////////////////////////////////////////////
		$sql="SELECT valor FROM ic_cuenta_usuarios WHERE parametro='BUSQUEDAS' AND id_grupo_usuario='".$_SESSION['bdm_user']['gru_id']."'";
		$result_config=$db->Execute($sql);
		list($valor)=$result_config->fields;
		////////////////////////////////////////////////
		
		if($valor<=$total_etiquetas){
			$mostrar_boton = false;
		}
	}
	
	if($mostrar_boton){
?>
      <input type="button" value="<?=$msq_boton?>" class="botonOscuro" onclick="validar_ir('nombre_etiqueta','seccion2','<?=_CAMPO?>','<?=_REPUTA_NEW_PALA?>','<?=_OBLIGATORIO?>');" style="width:120px;"/>
      <?php
	}else{	
	
		if($nombre_paquete->fields[1]>0 && $palabras_paquete->fields[0]=="0"){
			echo "<div style='position:absolute; margin: 40px 0 0 -265px; color: #fff; font-size: 14px;'>"._NOPAGOLISTO."</div>";
		}else{
			echo "<div style='position:absolute; margin: 40px 0 0 -265px; color: #fff;'>"._NOLIMITE."</div>";
		}
	}
?>
    </div>
    <?php
if ($id_etiqueta_mostrar != ""){
?>
    <div style="margin-top:10px; display:inline-block; width:100%">
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><a href="javascript:;" onclick="var reini = confirm('<?=_REPUTA_REINITAG?>'); if(reini){ $('#reiniciar').submit(); }" style="color:#FFF;"> <img src="images/bt-reiniciar.png" width="18" height="18" style="float:left; margin-right: 5px;"/>
            <?=_REPUTA_REINICIAR?>
          </a></td>
          <td width="30">&nbsp;</td>
          <td><a href="javascript:;" onclick="var eli = confirm('<?=_REPUTA_ELITAG?>'); if(eli){ $('#eliminar').submit(); }" style="color:#FFF;"> <img src="images/bt-eliminar.png" width="18" height="18" style="float:left; margin-right: 5px;"/>
            <?=_REPUTA_ELIMIN?>
          </a></td>
          <td width="30">&nbsp;</td>
          <td><a href="javascript:;" onclick="$('#pausar').submit();" style="color:#FFF;"> <img src="images/bt-play-pause.png" width="18" height="18" style="float:left; margin-right: 5px;"/>
            <?=(($estado=="A")?_REPUTA_PAUSARBUS:_REPUTA_ACTIVARBUS)?>
          </a></td>
        </tr>
      </table>
    </div>
    <?php
}
?>
  </div>
</div>

<!-- ///////////////////////////////////////////////////////////////////////////////////////////////////// -->

<div id="seccion2" style="margin: 20px auto 50px auto; display:none;">
	<div class="mas_titulo" style="margin: 0 0 20px 50px; color:#FA5225; font-size:35px;" id="tituloBusqueda"></div>

    <div style="display:block; float:left; width:402px; margin: 0 70px 0 0;">
    
        <div class="redondeado sombra columnaInternas">
            <?=_REPUTA_BUS_ESTRIC?>
            <div id="onoff" class="on_off">
                <input type="checkbox" name="busqueda_estricta" id="busqueda_estricta" value="S" <?=$check2?>/>
            </div>
        </div>
        
        <div class="redondeado sombra columnaInternas">
            <?=_BDM_SELPROY?>  
            <div class="separadorCasillas"></div>
            
            <select name="id_proyecto" id="id_proyecto" style="width:350px;">
                <?php cargar_lista("ic_proyectos","id_proyecto,nombre_proyecto","nombre_proyecto","1",$id_proyecto," WHERE id_usuario='".$_SESSION['sess_usu_grupo']."'",$db); ?>
            </select>
        </div>
            
        <div class="redondeado sombra columnaInternas">
            <?=_REPUTA_MSG2?>
            <div class="separadorCasillas"></div>
            <?=_REPUTA_IDIOMA_ALL?>
            <div id="idioall" class="on_off">
                <input type="radio" name="idioma" id="idiomatodos" value="" <?=($idioma=="")?'checked="checked"':""?>/>
            </div>
            <div class="separadorCasillas"></div>
            <?=_REPUTA_IDIOMA_ES?>
            <div id="idioes" class="on_off">
                <input type="radio" name="idioma" id="idiomaes" value="_es" <?=($idioma=="_es")?'checked="checked"':""?> />
            </div>
            <div class="separadorCasillas"></div>
            <?=_REPUTA_IDIOMA_EN?>
            <div id="idioen" class="on_off">
                <input type="radio" name="idioma" id="idiomaen" value="_eng" <?=($idioma=="_eng")?'checked="checked"':""?>/>
            </div>
        </div>
    
    </div>
    
    <div style="display:block; float:left; width:402px; margin: 0 70px 0 0;">
    	<div class="redondeado sombra columnaInternas">
			<?=_REPUTA_MSG1?>
            <div class="separadorCasillas"></div>   
            
            <div style="background:#DfDfDf; padding:9px 10px; color:#333; margin-bottom:10px;" class="redondeado">
                <input type="radio" name="oroption" id="and" value="and"  style="float: left; margin: 0 15px 25px 0;" onclick="checkOrOption();" <?=$checkOr1?>/>
                <?=_BDM_PRECI1?>
            </div>        
            <div style="position:relative; margin-bottom:15px;">
            	<div id="forexample1" style="cursor:pointer; text-align:right; text-decoration:underline;" onclick="$('#example1').fadeIn();"><?=_BDM_POREJEMPLO?></div>
            	<div id="example1" class="sombra" style="display:none; position:absolute; bottom:-15px; right:45px; border-radius:4px; background:#FFF; padding: 5px 10px;"><?=_BDM_PRECI11?></div>
            </div>                      
            <div id="sino_add_and"><?=$sinoagregadasand?></div>            
            <div align="right" style="margin-bottom:20px;"><img src="images/mas.fw.png" width="16" height="16" style="cursor:pointer;" id="create-sino1"/></div>
            
            
            <div style="background:#DfDfDf; padding:9px 10px; color:#333; margin-bottom:10px;" class="redondeado">
                <input type="radio" name="oroption" id="or" value="or"  style="float: left; margin-right: 15px;" onclick="checkOrOption();" <?=$checkOr2?>/>
                <?=_BDM_PRECI2?>
            </div>
            <div style="position:relative; margin-bottom:15px;">
            	<div id="forexample2" style="cursor:pointer; text-align:right; text-decoration:underline;" onclick="$('#example2').fadeIn();"><?=_BDM_POREJEMPLO?></div>
            	<div id="example2" class="sombra" style="display:none; position:absolute; bottom:-15px; right:45px; border-radius:4px; background:#FFF; padding: 5px 10px;"><?=_BDM_PRECI22?></div>
            </div>   
            <div id="sino_add_or"><?=$sinoagregadasor?></div>            
            <div align="right" style="margin-bottom:20px;"><img src="images/mas.fw.png" width="16" height="16" style="cursor:pointer;" id="create-sino2"/></div>

            
            <div style="background:#DfDfDf; padding:9px 10px; color:#333; margin-bottom:10px;" class="redondeado">
                <input type="radio" name="oroption" id="xor" value="xor" style="float: left; margin-right: 15px;" onclick="checkOrOption();" <?=$checkOr3?>/>
                <?=_BDM_PRECI3?>              
            </div>                
            <div style="position:relative; margin-bottom:15px;">
            	<div id="forexample3" style="cursor:pointer; text-align:right; text-decoration:underline;" onclick="$('#example3').fadeIn();"><?=_BDM_POREJEMPLO?></div>
            	<div id="example3" class="sombra" style="display:none; position:absolute; bottom:-15px; right:45px; border-radius:4px; background:#FFF; padding: 5px 10px;"><?=_BDM_PRECI33?></div>
            </div>   
            <div id="sino_add_xor"><?=$sinoagregadasxor?></div>            
            <div align="right"><img src="images/mas.fw.png" width="16" height="16" style="cursor:pointer;" id="create-sino3"/></div>
            
            <input type="hidden" id="maxsino" value="<?=$sinomax?>"/>
            <input type="hidden" id="totalsino" value="<?=$precision?>"/>
            <input type="hidden" id="or_option" name="or_option" value="<?=$or_option?>"/>
            <input type="hidden" name="sinonimos" id="sinonimos" value="<?=$sinonimos?>" />
        </div>
    </div>
    
    <div class="redondeado sombra columnaInternas">
    	<?=_REPUTA_DESCARTAR_PAL?>        
        <div class="separadorCasillas"></div>
        <div id="palabras_add"><?=$palabrasagregadas?></div>
        <div align="right"><img src="images/mas.fw.png" width="16" height="16" style="cursor:pointer;" id="create-palabra"/></div>
        <input type="hidden" id="maxpalabra" value="<?=$palabrasmax?>"/>
        <input type="hidden" name="descartar_palabras" id="descartar_palabras" value="<?=$descartar_palabras?>" />
    </div>
    
    <div class="redondeado sombra columnaInternas">
    	<?=_REPUTA_MSG3?>        
        <div class="separadorCasillas"></div>
        <div id="urls_add"><?=$urlsagregadas?></div>
        <div align="right"><img src="images/mas.fw.png" width="16" height="16" style="cursor:pointer;" id="create-url"/></div>
        <input type="hidden" id="maxurl" value="<?=$urlsmax?>"/>
        <input type="hidden" name="descartar_url" id="descartar_url" value="<?=$descartar_url?>" />
    </div>
    
    <div class="columnaInternas2">
    	<input type="button" value="<?=_REPUTA_VOL?>" class="botonOscuro" onclick="validar_ir('','seccion1','<?=_CAMPO?>','','<?=_OBLIGATORIO?>');"/>
        <input type="button" value="<?=_REPUTA_SIGUIENTE?>" class="botonOscuro" onclick="validar_ir('','seccion3','<?=_CAMPO?>','','<?=_OBLIGATORIO?>');"/>
	</div>
</div>

<!-- ///////////////////////////////////////////////////////////////////////////////////////////////////// -->

<div id="seccion3" style="margin: 50px auto; display:none;">
	<div class="mas_titulo" style="margin: 0 0 20px 50px; color:#FA5225; font-size:35px;" id="tituloBusqueda2"></div>
    
    <div class="redondeado sombra columnaInternas">
        <span style="font-size:20px;"><?=_REPUTA_MSG5?></span>
        <div class="separadorCasillas"></div>
<?php
    if(permiso_usuario("BUSCAR_REDES",$_SESSION['bdm_user']['gru_id'],$db)){
?>
        <span style="background: url(images/redes.fw.png) no-repeat; padding-left: 30px; padding-bottom:5px; color: #07A39A; font-size:14px;"><?=_REPUTA_RS?></span>
        <div id="onoff" class="on_off">
            <input type="checkbox" name="categorias[2]" id="categorias_2" <?=$checkcat2?> value="2"/>
        </div>
        <div class="separadorCasillas"></div>
<?php
    }

    if(permiso_usuario("BUSCAR_PREFERIDOS",$_SESSION['bdm_user']['gru_id'],$db)){
?>
        <span style="background: url(images/preferidos.fw.png) no-repeat; padding-left: 30px; padding-bottom:5px; color: #07A39A; font-size:14px;"><?=_REPUTA_PRE?></span>
        <div id="onoff" class="on_off">
            <input type="checkbox" name="categorias[3]" id="categorias_3" <?=$checkcat3?> value="3"/>
        </div>
        <div class="separadorCasillas"></div>
<?php
    }

    if(permiso_usuario("BUSCAR_UNIVERSO",$_SESSION['bdm_user']['gru_id'],$db)){
?>
        <span style="background: url(images/universo.fw.png) no-repeat; padding-left: 30px; padding-bottom:5px; color: #07A39A; font-size:14px;"><?=_REPUTA_UNIV?></span>
        <div id="onoff" class="on_off">
            <input type="checkbox" name="categorias[4]" id="categorias_4" <?=$checkcat4?> value="4"/>
        </div>
        <div class="separadorCasillas"></div>
<?php
    }

    if(permiso_usuario("BUSCAR_FUENTES",$_SESSION['bdm_user']['gru_id'],$db)){
?>
        <span style="background: url(images/elegidos.fw.png) no-repeat; padding-left: 30px; padding-bottom:5px; color: #07A39A; font-size:14px;"><?=_REPUTA_MELEG?></span>
        <div id="onoff" class="on_off">
            <input type="checkbox" name="categorias[5]" id="categorias_5" <?=$checkcat5?> value="5"/>
        </div>
<?php
    }
?>
        <input type="hidden" name="max_cat" id="max_cat" value="5" />
        <input type="hidden" name="ecat_tag" id="ecat_tag" value="<?=$ecat_tag?>" />
    </div>

    <div class="redondeado sombra columnaInternas">
    	<span style="font-size:20px;"><?=_PAIS?></span>
        <div><?=_REPUTA_NOTAPAIS?></div>
    	<div class="separadorCasillas"></div>
        
        <div class="paises_radio">
        <input type="checkbox" name="todospais" id="todospaises" onclick="seleccionarPaises();" <?=(($paises=="")?"checked":"")?>/>
        </div>
       	<?=_REPUTA_SELECT_ALLPAISES?>
        
        <div class="separadorCasillas"></div>
        
<?php	

$sql="SELECT DISTINCT continente FROM ic_paises ORDER BY continente ASC";
$result_continentes=$db->Execute($sql);

$u=0;
//$w=0;

while(!$result_continentes->EOF){
	list($continente)=select_format($result_continentes->fields);
?>
        <div style="font-weight:bold" class="continentes"><?=$continente?></div>
        <div class="separadorCasillas"></div>
        <div style="width: 360px; height: 200px; overflow:auto; display:block; float:left;" class="paises">
<?php	

	$sql="SELECT pais,capital FROM ic_paises WHERE continente='".$continente."' ORDER BY pais ASC";
	$result_cat=$db->Execute($sql);
		
	while(!$result_cat->EOF){
		list($nom_paises,$nom_capital)=select_format($result_cat->fields);
		
		$check_pais = "";
		if(count($paisesUsuario)>0){
			if(in_array($nom_paises,$paisesUsuario)){
					$check_pais="checked";
			}
		}

?>
			<div style="display:inline-block; width:330px; padding:2px;">				           
                <div class="paises_radio">
                <input type="checkbox" name="paises[<?=$u?>]" id="paises_<?=$u?>" value="<?=$nom_paises?>" <?=$check_pais?> onclick="seleccionarUno('<?=$u?>');"/>
                </div>
               <?=$nom_paises?> <!--(<?=$nom_capital?>)-->
            </div>
<?php
		$result_cat->MoveNext();
		$u++;
	}
?>
		</div>
<?php
	$result_continentes->MoveNext();
	//$w++;
}

?>
    </div>
    
    <div class="redondeado sombra columnaInternas">
		<span style="font-size:20px;"><?=_BDM_TIPOBUSQUEDA?></span>
        <div class="separadorCasillas"></div>
        
		<?=_BDM_TIPOBUSQUEDAT?>
        <div id="tipote" class="on_off">
            <input type="radio" name="tipo_busqueda" id="tipot" value="T" <?=($tipo_busqueda=="T" || $tipo_busqueda=="")?'checked="checked"':""?>/>
        </div>
        <div class="separadorCasillas"></div>
        
		<?=_BDM_TIPOBUSQUEDAI?>
        <div id="tipoim" class="on_off">
            <input type="radio" name="tipo_busqueda" id="tipoi" value="I" <?=($tipo_busqueda=="I")?'checked="checked"':""?> />
        </div>
        <div class="separadorCasillas"></div>
        
		<?=_BDM_TIPOBUSQUEDAV?>
        <div id="tipovi" class="on_off">
            <input type="radio" name="tipo_busqueda" id="tipov" value="V" <?=($tipo_busqueda=="V")?'checked="checked"':""?>/>
        </div>
    </div>
        
<?php
if ($id_etiqueta_mostrar == ""){
?>
    <div class="redondeado sombra columnaInternas">
    	<span style="font-size:20px;"><?=_REPUTA_ESTADOBUS?></span>
        <div><?=_REPUTA_ESTADOBUS2?></div>
    	<div class="separadorCasillas"></div>
        <?=_REPUTA_BUSQBORRADOR?>
        <div id="onoff" class="on_off">
            <input type="radio" name="estado" id="estado" value="P"/>
        </div>
    </div>
<?php
}
?>
    <div class="columnaInternas2">
    	<input type="button" value="<?=_REPUTA_VOL?>" class="botonOscuro" onclick="validar_ir('','seccion2','<?=_CAMPO?>','','<?=_OBLIGATORIO?>');"/>
        <input type="button" value="<?=$msq_boton?>" onclick="enviar('nombre_etiqueta','guardar','<?=_CAMPO?>','<?=_OBLIGATORIO?>');" style="cursor:pointer"/>        
    </div>
</div>

<!-- ///////////////////////////////////////////////////////////////////////////////////////////////////// -->

</form>

 

<link rel="stylesheet" href="<?=$_SESSION['c_base_location']?>js/jquery.switchButton.css" />
<script src="<?=$_SESSION['c_base_location']?>js/jquery.switchButton.js"></script>


<div id="dialog-form0" title="<?=_REPUTA_SINO?>">
        <label for="sino"><?=_REPUTA_SINO?></label>
        <input type="text" name="sino" id="sino" size="30" onkeypress="return validarn(event);"/>
</div>
<div id="dialog-form1" title="<?=_REPUTA_MSG3?>">
        <label for="url">URL</label>
        <input type="text" name="url" id="url" size="30" />
</div>
<div id="dialog-form2" title="<?=_REPUTA_DESCARTAR_PAL?>">
        <label for="palabra"><?=_REPUTA_DESCARTAR_PAL?></label>
        <input type="text" name="palabra" id="palabra" size="30" onkeypress="return validarn(event);"/>
</div>

<script type="text/javascript">
/*$("#seccion2").hide();
$("#seccion3").hide();*/
$(function() {
  $('#forexample1').hover(function() {
  	}, function(){
      $('#example1').stop().fadeOut();
  });
  $('#forexample2').hover(function() {
  	}, function(){
      $('#example2').stop().fadeOut();
  });
  $('#forexample3').hover(function() {
  	}, function(){
      $('#example3').stop().fadeOut();
  });
});

$(function() {
	$('#onoff input').switchButton();
	
	$('#idiomaes').switchButton().change(function(){
		var idioall = $("#idiomaes").prop( "checked" );
		if(idioall===true){
			$("#idiomatodos").switchButton({ checked: false });
			$("#idiomaen").switchButton({ checked: false });
		}
	});
	
	///////////////
	
	$('#idiomaen').switchButton().change(function(){
		var idioall = $("#idiomaen").prop( "checked" );
		if(idioall===true){
			$("#idiomatodos").switchButton({ checked: false });
			$("#idiomaes").switchButton({ checked: false });
		}
	});
	
	///////////////
	
	$('#idiomatodos').switchButton().change(function(){
		var idioall = $("#idiomatodos").prop( "checked" );
		if(idioall===true){
			$("#idiomaes").switchButton({ checked: false });
			$("#idiomaen").switchButton({ checked: false });
		}
	});
	
	////////////////////////////////////////////////////////////
	
	$('#tipot').switchButton().change(function(){
		var tipot = $("#tipot").prop( "checked" );
		if(tipot===true){
			$("#tipoi").switchButton({ checked: false });
			$("#tipov").switchButton({ checked: false });
		}
	});
	
	///////////////
	
	$('#tipoi').switchButton().change(function(){
		var tipoi = $("#tipoi").prop( "checked" );
		if(tipoi===true){
			$("#tipot").switchButton({ checked: false });
			$("#tipov").switchButton({ checked: false });
		}
	});
	
	///////////////
	
	$('#tipov').switchButton().change(function(){
		var tipov = $("#tipov").prop( "checked" );
		if(tipov===true){
			$("#tipot").switchButton({ checked: false });
			$("#tipoi").switchButton({ checked: false });
		}
	});
});

$(function() {
	var url = $( "#url" ),palabra = $( "#palabra" ),sino = $( "#sino" ),
		allFields = $( [] ).add( url ),
		tips = $( ".validateTips" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}
	
	$( "#dialog-form0" ).dialog({
		autoOpen: false,
		modal: true,
		buttons: {
			"<?=_REPUTA_MSG4?>": function() {	
				var maxid = $( "#maxsino" ).val();
				var sinonimos = $( "#sinonimos" ).val();
				
				var id = (parseInt(maxid)+1);
								
				$( "#sino_add_"+$( "#or_option" ).val() ).append( "<div id='sinodes"+id+"' onclick='$( this ).remove(); refrescarSino();'>"+
				"<div style='cursor: pointer; padding-left:20px; background: url(images/admin/eliminar.png) no-repeat right;'><b>" +
					sino.val() +
				"</b></div>" +
				"<div class='separadorCasillas'></div>"+
				"</div>");
				
				$( "#sinonimos" ).val( sinonimos + sino.val() + "," );
				document.getElementById("maxsino").value = id;
				
				$( this ).dialog( "close" );
				sino.val("");
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		},
		close: function() {
			allFields.val( "" ).removeClass( "ui-state-error" );
		}
	});
	
	////////////////////////////////////////////////////////////////
	
	$( "#dialog-form1" ).dialog({
		autoOpen: false,
		modal: true,
		buttons: {
			"<?=_REPUTA_MSG4?>": function() {	
				var maxid = $( "#maxurl" ).val();
				var descartar_url = $( "#descartar_url" ).val();
				
				var id = (parseInt(maxid)+1);
				
				$( "#urls_add" ).append( "<div id='urldes"+id+"' onclick='$( this ).remove(); refrescarUrl();'>"+
				"<div style='cursor: pointer; background: url(images/admin/eliminar.png) no-repeat right;'><b>" +
					url.val() +
				"</b></div>" +
				"<div class='separadorCasillas'></div>"+
				"</div>");
				
				$( "#descartar_url" ).val( descartar_url + url.val() + "," );
				document.getElementById("maxurl").value = id;
				
				$( this ).dialog( "close" );
				url.val("");
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		},
		close: function() {
			allFields.val( "" ).removeClass( "ui-state-error" );
		}
	});
	
	//////////////////////////////////////////////
	
	$( "#dialog-form2" ).dialog({
		autoOpen: false,
		modal: true,
		buttons: {
			"<?=_REPUTA_MSG4?>": function() {	
				var maxid = $( "#maxpalabra" ).val();
				var descartar_palabras = $( "#descartar_palabras" ).val();
				
				var id = (parseInt(maxid)+1);
				
				$( "#palabras_add" ).append( "<div id='palabrades"+id+"' onclick='$( this ).remove(); refrescarPalabra();'>"+
				"<div style='cursor: pointer; background: url(images/admin/eliminar.png) no-repeat right;'><b>" +
					palabra.val() +
				"</b></div>" +
				"<div class='separadorCasillas'></div>"+
				"</div>");
				
				$( "#descartar_palabras" ).val( descartar_palabras + palabra.val() + "," );
				document.getElementById("maxpalabra").value = id;
				
				$( this ).dialog( "close" );
				palabra.val("");
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		},
		close: function() {
			allFields.val( "" ).removeClass( "ui-state-error" );
		}
	});
	
	////////////////////////////////////////////////////////////////

	$( "#create-url" ).click(function() {
		$( "#dialog-form1" ).dialog( "open" );
	});
	
	$( "#create-palabra" ).click(function() {
		$( "#dialog-form2" ).dialog( "open" );
	});
		
	////////////////////////////////////////////////////////////////
	
	$( "#create-sino1" ).click(function() {
		var creados = $("#maxsino").val();
		var limite = $("#totalsino").val();
		
		if($( "#or_option" ).val()!="" && "and" != $( "#or_option" ).val()){
			if(confirm("<?=_BDM_ALERTPRECI?>")){
				$("#and").prop("checked", true);
				
				$( "#sino_add_"+$( "#or_option" ).val() ).children("div").remove();
				
				$( "#or_option" ).val("and");
				$( "#sinonimos" ).val("");
				$("#maxsino").val("0");
			}else{
				return;
			}
		}else{
			$( "#or_option" ).val("and");
		}
		
		if(creados<limite){
			$( "#dialog-form0" ).dialog( "open" );
		}else{
			alert('<?=_BDM_PRECISIONLIMITE?>');	
		}
	});
	
	/////////////
	
	$( "#create-sino3" ).click(function() {
		var creados = $("#maxsino").val();
		var limite = $("#totalsino").val();
		
		if($( "#or_option" ).val()!="" && "xor" != $( "#or_option" ).val()){
			if(confirm("<?=_BDM_ALERTPRECI?>")){
				$("#xor").prop("checked", true);
				
				$( "#sino_add_"+$( "#or_option" ).val() ).children("div").remove();
				
				$( "#or_option" ).val("xor");
				$( "#sinonimos" ).val("");
				$("#maxsino").val("0");
			}else{
				return;
			}
		}else{
			$( "#or_option" ).val("xor");
		}
		
		if(creados<limite){
			$( "#dialog-form0" ).dialog( "open" );
		}else{
			alert('<?=_BDM_PRECISIONLIMITE?>');	
		}
	});
	
	/////////////
	
	$( "#create-sino2" ).click(function() {
		var creados = $("#maxsino").val();
		var limite = $("#totalsino").val();
		
		if($( "#or_option" ).val()!="" && "or" != $( "#or_option" ).val()){
			if(confirm("<?=_BDM_ALERTPRECI?>")){
				$("#or").prop("checked", true);
				
				$( "#sino_add_"+$( "#or_option" ).val() ).children("div").remove();
				
				$( "#or_option" ).val("or");
				$( "#sinonimos" ).val("");
				$("#maxsino").val("0");
			}else{				
				return;
			}
		}else{
			$( "#or_option" ).val("or");
		}
		
		if(creados<limite){
			$( "#dialog-form0" ).dialog( "open" );
		}else{
			alert('<?=_BDM_PRECISIONLIMITE?>');	
		}
	});
});

function checkOrOption(){
		
	if($( "#or_option" ).val()!="" && $('input:radio[name=oroption]:checked').val() != $( "#or_option" ).val()){

		if(confirm("<?=_BDM_ALERTPRECI?>")){
							
			$( "#sino_add_"+$( "#or_option" ).val() ).children("div").remove();
			
			$( "#or_option" ).val($('input:radio[name=oroption]:checked').val());
			$( "#sinonimos" ).val("");
			$("#maxsino").val("0");
			
			$( "#dialog-form0" ).dialog( "open" );
				
		}else{
			$("#"+$( "#or_option" ).val()+"").prop("checked", true);
		}
	}else{
		$( "#or_option" ).val(principal);
	}
}

function refrescarUrl(){
	var maxid = document.getElementById("maxurl").value;
	var descartar = "";
	
	for(i=0; i<=maxid; i++){
		if(document.getElementById("urldes"+i)){
			descartar += $("#urldes"+i).text() + ",";
		}
	}
	document.getElementById("descartar_url").value = descartar;
}

function refrescarPalabra(){
	var maxid = document.getElementById("maxpalabra").value;
	var descartar = "";
	
	for(i=0; i<=maxid; i++){
		if(document.getElementById("palabrades"+i)){
			descartar += $("#palabrades"+i).text() + ",";
		}
	}
	document.getElementById("descartar_palabras").value = descartar;
}

function refrescarSino(){
	var maxid = document.getElementById("maxsino").value;
	var incluir = "";
	var count = 0;
	
	for(i=0; i<=maxid; i++){
		if(document.getElementById("sinodes"+i)){
			incluir += $("#sinodes"+i).text() + ",";
			count++;
		}
	}
	document.getElementById("maxsino").value = count;
	document.getElementById("sinonimos").value = incluir;
}
</script>

</body>
</html>