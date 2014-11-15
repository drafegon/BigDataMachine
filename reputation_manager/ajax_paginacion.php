<?php
session_start();
date_default_timezone_set("America/Buenos_Aires");

$path = "../adodb/adodb.inc.php";
include ("../admin/var.php");
include ("../conexion.php");
	
include "../admin/funciones.php";
include("../admin/funciones_start_session.php");
include ("../language/language".$_SESSION['idioma'].".php");



$variables_metodo = variables_metodo("etiqueta,pag,buscarFuente,fechaIni,fechaFin,categoria,semana_seleccionada,accion_seleccionada,fecha_seleccionada,web,fecha_desde,fecha_hasta,tipo_seleccionada,rss_id");
$id_etiqueta_mostrar= 	$variables_metodo[0];
$pag= 					($variables_metodo[1]=="")?"1":$variables_metodo[1];
$buscar_rss=			$variables_metodo[2];
$fechaIni= 				$variables_metodo[3];
$fechaFin= 				$variables_metodo[4];
$categoria= 			$variables_metodo[5];
$semana_seleccionada= 	$variables_metodo[6];
$accion_seleccionada= 	$variables_metodo[7];
$fecha_seleccionada= 	$variables_metodo[8];
$web= 					$variables_metodo[9];
$fecha_desde= 			(($variables_metodo[10]!="" && $variables_metodo[10]!=_FECHA_DESDE)?date('Y-m-d',strtotime($variables_metodo[10])):"");
$fecha_hasta= 			(($variables_metodo[11]!="" && $variables_metodo[11]!=_FECHA_HASTA)?date('Y-m-d',strtotime($variables_metodo[11])):"");
$tipo_seleccionada= 	$variables_metodo[12];
$rss_id= 				$variables_metodo[13];

//-------------------------------------------------------------

if($categoria==""){
	$categoria=0;	
}

if($web==""){
	$web=0;	
}

//-------------------------------------------------------------

$where = "";
$indice = "";

if($buscar_rss!="" && ($buscar_rss!="Filtra tu busqueda..." && $buscar_rss!="Narrow your search...")){
	$where .= " AND (a.contenido LIKE '%".$buscar_rss."%' OR a.titulo LIKE '%".$buscar_rss."%')";
}

if($semana_seleccionada!=""){
	$where .= " AND a.semana = '".$semana_seleccionada."'";
} 


if($fecha_desde!=""){
	$indice = " FORCE INDEX (indx_fechacargue)";
	$where .= " AND a.fecha_cargue >='".$fecha_desde."'";
}

if($fecha_hasta!=""){
	$indice = " FORCE INDEX (indx_fechacargue)";
	$where .= " AND a.fecha_cargue <='".$fecha_hasta."'";
}

if($tipo_seleccionada=="photo"){
	$indice = " FORCE INDEX (indx_mencion)";
	$where .= " AND a.tipo_mencion = 'photo' ";
}elseif($tipo_seleccionada=="video"){
	$indice = " FORCE INDEX (indx_mencion)";
	$where .= " AND a.tipo_mencion = 'video' ";
}elseif($tipo_seleccionada=="text"){
	$indice = " FORCE INDEX (indx_mencion)";
	$where .= " AND a.tipo_mencion = 'text' ";
}

if($accion_seleccionada=="1"){
	$indice = " FORCE INDEX (indx_positivos)";
	$where .= " AND a.positivo = 'S' ";
}elseif($accion_seleccionada=="2"){
	$indice = " FORCE INDEX (indx_negativos)";
	$where .= " AND a.negativo = 'S' ";
}elseif($accion_seleccionada=="3"){
	$where .= " AND a.positivo != 'S' AND a.negativo != 'S' AND a.neutro != 'S' ";
}elseif($accion_seleccionada=="4"){
	$indice = " FORCE INDEX (indx_marcada)";
	$where .= " AND a.marcada != '' ";
}elseif($accion_seleccionada=="6"){
	$indice = " FORCE INDEX (indx_intervenidos)";
	$where .= " AND a.intervenido = 'S' ";
}elseif($accion_seleccionada=="7"){
	$indice = " FORCE INDEX (indx_intervenidos)";
	$where .= " AND a.intervenido != 'S' ";
}elseif($accion_seleccionada=="8"){
	$indice = " FORCE INDEX (indx_bloqueada)";
	$where .= " AND a.bloqueada = 'S' ";
}elseif($accion_seleccionada=="9"){
	$indice = " FORCE INDEX (indx_bloqueada)";
	$where .= " AND a.bloqueada != 'S' ";
}elseif($accion_seleccionada=="10"){
	$indice = " FORCE INDEX (indx_neutros)";
	$where .= " AND a.neutro = 'S' ";
}

if($fecha_seleccionada=="-1"){
	$indice = " FORCE INDEX (indx_fechacargue)";
	$where .= " AND a.fecha_cargue = '".date('Y-m-d')."'";
}elseif($fecha_seleccionada=="1"){
	$indice = " FORCE INDEX (indx_fechacargue)";
	$where .= " AND a.fecha_cargue >= '".date('Y-m-d',strtotime('-1 day', strtotime(date('Y-m-d'))))."'";
}elseif($fecha_seleccionada=="2"){
	$indice = " FORCE INDEX (indx_fechacargue)";
	$where .= " AND a.fecha_cargue >= '".date('Y-m-d',strtotime('-7 day', strtotime(date('Y-m-d'))))."'";
}elseif($fecha_seleccionada=="3"){
	$indice = " FORCE INDEX (indx_fechacargue)";
	$where .= " AND a.fecha_cargue >= '".date('Y-m-d',strtotime('-14 day', strtotime(date('Y-m-d'))))."'";
}elseif($fecha_seleccionada=="4"){
	$indice = " FORCE INDEX (indx_fechacargue)";
	$where .= " AND a.fecha_cargue >= '".date('Y-m-d',strtotime('-30 day', strtotime(date('Y-m-d'))))."'";
}elseif($fecha_seleccionada=="5"){
	$where .= "";
}

if($web!="" && $web!="0"){
	$indice = " FORCE INDEX (indx_categoria)";
	$where .= " AND a.id_categoria='".$web."'";
}

if($rss_id!="" && $rss_id!="0"){
	$where .= " AND a.id_rss='".$rss_id."'";
}

//Variables de la paginacion
$max_pagi = 20;
$count_1=$max_pagi;
$limit=1;
$count_2=0;

if($pag>1){
	$limit=$pag;
	$count_2=($count_1*$limit)-$count_1;
}


///////////////////////////////////////////////////////////////

$sql="SELECT a.id,a.id_rss,a.cargue,a.marcada,a.positivo,
		a.negativo,a.bloqueada,a.intervenido,a.id_categoria,
		a.titulo,a.contenido,a.link,a.semana,a.neutro,a.id_source,a.detalle_intervenido,
		a.fecha_registro,a.tipo_mencion,a.usuario_medio,a.media_url,a.ubicacion,a.info_adicional,
		CASE WHEN a.link RLIKE '^[http://|https://]' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(link, '/', 3), '/', -1) ELSE SUBSTRING_INDEX(link, '/', 1) END AS sitios
FROM ic_rss_coincidencias a ".$indice."
WHERE a.id_etiqueta=".$id_etiqueta_mostrar." 
".$where."
".$categoria_where."
ORDER BY a.fecha_registro DESC 
LIMIT ".$count_2.",".$max_pagi." ";
$result_coincidencias_finales=$db->Execute($sql);

$resutldos = 0;

if($pag=="2"){
	$cont=$max_pagi+1;
}else{
	$cont=(($max_pagi*$pag)-$max_pagi)+1;
}

if(!$result_coincidencias_finales->EOF){
		
	echo '<div style="border-top: 2px solid #666666; width: 100%; margin: 20px 0 5px 0;"></div>
		  <div style="padding: 0 0 15px 5px; font-size:14px; color: #666666" align="left">Pag '.$pag.'</div>';
		  
	while(!$result_coincidencias_finales->EOF){
		
		list($id,$id_rss,$cargue,$marcada,$positivo,$negativo,$bloqueada,$intervenido,$cat_id,
	     $titulo,$contenido,$link,$semana,$neutro,$id_source,$detalle_intervenido,$fecha_registro,
		 $tipo_mencion,$usuario_medio,$media_url,$ubicacion,$info_adicional,$nom_rss)=select_format($result_coincidencias_finales->fields);
				
		/////////////////////////////////////////////////////////////////////////
		
		$fecha_publicacion = "";
		
		if($fecha_registro!="" && $fecha_registro!="0"){
			$fecha_publicacion = date('d/m/Y H:i', $fecha_registro) . " - ";
		}
		
		/////////////////////////////////////////////////////////////////////////
		
		$media = "";
		$media_enviar = "";
		
		if($tipo_mencion=="video"){
			if($id_rss=="439"){
				$media_enviar = "<br><iframe width='370' height='240' src='//www.youtube.com/embed/".$id_source."' frameborder='0' allowfullscreen></iframe>";
			}else{
				$media_enviar = "<br><a href='".$link."' target='_blank'><img src='".$media_url."' alt='' width='150' /></a>";
			}
			
			$media = "<br><a href='".$link."' target='_blank'><img src='".$media_url."' alt='' width='150' /></a>";
		}
		
		if($tipo_mencion=="photo"){
			$media = "<br><img src='".$media_url."' alt='' width='150' />";
		}
		
		/////////////////////////////////////////////////////////////////////////
		
		$sql="SELECT cat_titulo FROM ic_categoria WHERE cat_id='".$cat_id."'";
		$result_categoria=$db->Execute($sql);
		list($cat_titulo)=select_format($result_categoria->fields);
		
		///////////////////////////////////////////////////////////////////////
				
		$sql="SELECT b.nombre FROM ic_concidencia_directorio a, ic_directorios b WHERE a.id_directorio=b.id AND a.id_coincidencia='".$id."'";
		$result_dir=$db->Execute($sql);
		list($nombre_dir)=select_format($result_dir->fields);
		
		/////////////////////////////////////////////////////////////////////////
			
		$contenido = substr(strip_tags($contenido), -0, 200)."...";
		
		/////////////////////////////////////////////////////////////////////////
		
		$clase_titulo="titulo";
		
		$class_calif_pos="<img src='images/feb-2014/posi-i.fw.png' id='P_".$id."' border='0' alt='posi-i'/>";
		$class_calif_neg="<img src='images/feb-2014/nega-i.fw.png' id='N_".$id."' border='0' alt='nega-i'/>";
		$class_calif_neu="<img src='images/feb-2014/neut-i.fw.png' id='NE_".$id."' border='0' alt='neut-i'/>";
		
		if($positivo=="S"){
			$class_calif_pos="<img src='images/feb-2014/posi-a.fw.png' id='P_".$id."' border='0' alt='posi-a'/>";
		}
		if($negativo=="S"){
			$class_calif_neg="<img src='images/feb-2014/nega-a.fw.png' id='N_".$id."' border='0' alt='nega-a'/>";
		}
		if($neutro=="S"){
			$class_calif_neu="<img src='images/feb-2014/neut-a.fw.png' id='NE_".$id."' border='0' alt='neut-a'/>";
		}
		
		/////////////////////////////////////////////////////////////////////////
		
		$class_marcada1="images/feb-2014/ranking-i.fw.png";
		$class_marcada2="images/feb-2014/ranking-i.fw.png";
		$class_marcada3="images/feb-2014/ranking-i.fw.png";
		$marcaalt1="ranking-i";
		$marcaalt2="ranking-i";
		$marcaalt3="ranking-i";
		
		if($marcada=="1"){
			$class_marcada1="images/feb-2014/ranking-a.fw.png";
			$marcaalt1="ranking-a";
		}
		if($marcada=="2"){
			$class_marcada1="images/feb-2014/ranking-a.fw.png";
			$class_marcada2="images/feb-2014/ranking-a.fw.png";
			$marcaalt1="ranking-a";
			$marcaalt2="ranking-a";
		}
		if($marcada=="3"){
			$class_marcada1="images/feb-2014/ranking-a.fw.png";
			$class_marcada2="images/feb-2014/ranking-a.fw.png";
			$class_marcada3="images/feb-2014/ranking-a.fw.png";
			$marcaalt1="ranking-a";
			$marcaalt2="ranking-a";
			$marcaalt3="ranking-a";
		}
		
		/////////////////////////////////////////////////////////////////////////
		
		$class_bloqueada="images/feb-2014/bloq-i.fw.png";
		
		if($bloqueada=="S"){
			$class_bloqueada="images/feb-2014/bloq-a.fw.png";
		}
		
		/////////////////////////////////////////////////////////////////////////
		
		$class_intervenida="images/no_intervenidos.jpg";
		
		if($intervenido=="S"){
			$class_intervenida="images/intervenidos.jpg";
		}
		
		/////////////////////////////////////////////////////////////////////////
		
	
		echo '<div class="tablaResultados" id="elemento'.$id.'">
	
	<table width="100%" border="0" cellspacing="0" cellpadding="1" style="margin-bottom:10px;" id="filaElemento'.$cont.'">
	  <tr>
		<td width="1" valign="top">
		<input type="checkbox" name="seleccion_'.$id.'" id="seleccion_'.$cont.'" value="'.$id.'" />
		</td>
		<td width="5" valign="top">&nbsp;</td>
		<td valign="top">
		<input type="hidden" id="tit_'.$id.'" value="'.str_replace('"','',str_replace("'","",$titulo)).'" />
		<input type="hidden" id="cont_'.$id.'" value="'.str_replace('"','',str_replace("'","",$contenido)).'" />
		<input type="hidden" id="lin_'.$id.'" value="'.$link.'" />
		<input type="hidden" id="rss_'.$id.'" value="'.$id_rss.'" />
		<input type="hidden" id="id_'.$id.'" value="'.$id.'" />
		<input type="hidden" id="source_'.$id.'" value="'.$id_source.'" />
		<input type="hidden" id="media_'.$id.'" value="'.$media_enviar.'" />
		<input type="hidden" id="feed_'.$id.'" value="'.$detalle_intervenido.'" />
		<div class="columna">
			<a href="javascript:;" onclick="mostrarContenido(\''.$cat_id.'\',\''.$id.'\'); seleccionarFila(\''.$cont.'\');" style="text-decoration:none">
				<li id="'.$id.'" class="dragDropListado" style="list-style:none; padding:0; margin:0;">
				<span class="'.$clase_titulo.'" style="font-size:15px;">'.$titulo.'</span>
				</li>
			</a>    
		</div>
		<div class="columna" style="margin:2px 0 0 10px;">
			<table border="0" cellspacing="0" cellpadding="1">
			  <tr>
				<td><img src="'.$class_marcada1.'" id="marca1_'.$id.'" width="16" height="16" onclick="marcar(\''.$id.'\', 1);" style="cursor:pointer" alt="'.$marcaalt1.'"/></td>
				<td><img src="'.$class_marcada2.'" id="marca2_'.$id.'" width="16" height="16" onclick="marcar(\''.$id.'\', 2);" style="cursor:pointer" alt="'.$marcaalt2.'"/></td>
				<td><img src="'.$class_marcada3.'" id="marca3_'.$id.'" width="16" height="16" onclick="marcar(\''.$id.'\', 3);" style="cursor:pointer" alt="'.$marcaalt3.'"/></td>
			  </tr>
			</table>
		</div>
		</td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>
		<div style="margin:1px 0 30px 0; z-index:5000000;">
			<div id="calificaciones'.$id.'" class="columna" style="margin: 3px 10px 0 0; float:left">
				<div class="columna" style="cursor:pointer" onclick="calificar(\'P\',\''.$id.'\');" title="'._REPUTA_CALPOSI.'">
					'.$class_calif_pos.'
				</div>
				<div class="columna" style="border-left:1px solid #CBCBCB; height:18px; margin:0 10px 0 10px;"></div>
				<div class="columna" style="cursor:pointer" onclick="calificar(\'NE\',\''.$id.'\');" title="'._REPUTA_CALNEUTRO.'">
					'.$class_calif_neu.'
				</div>
				<div class="columna" style="border-left:1px solid #CBCBCB; height:18px; margin:0 10px 0 10px;"></div>
				<div class="columna" style="cursor:pointer" onclick="calificar(\'N\',\''.$id.'\');" title="'._REPUTA_CALNEGA.'">
					'.$class_calif_neg.'
				</div>
				<div class="columna" style="border-left:1px solid #CBCBCB; height:18px; margin:0 10px 0 10px;"></div>
				<div class="columna" style="cursor:pointer" onclick="bloqueada(\''.$id.'\', true)" title="'._REPUTA_BLOQ.'">
					<img src="'.$class_bloqueada.'" id="imgbloq_'.$id.'" border="0" />
				</div>
				<div class="columna" style="border-left:1px solid #CBCBCB; height:18px; margin:0 10px 0 10px;"></div>
				<div class="columna" style="cursor:pointer" onclick="eliminar(\''.$id.'\', true);" title="'._REPUTA_ELIMIN.'">
					<img src="images/feb-2014/elim.fw.png" border="0" />
				</div>
				<div class="columna" style="border-left:1px solid #CBCBCB; height:18px; margin:0 10px 0 10px;"></div>
			</div>
			<div id="dirteca'.$id.'" class="dir_res columna" style="float:left">'.$nombre_dir.'</div> 
		</div>
		</td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>
			<div style="color:#999; font-size:13px;">'.$fecha_publicacion.'<b>'.$cat_titulo.'</b> '.(($nom_rss!="")?"(".$nom_rss.")":"").'</div>
		</td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>    
		   <div style="color:#666; font-size:14px; line-height:19px; word-spacing:0.9pt;">'.$contenido.'</div>
		   <div>'.$media.'</div>
			<input type="hidden" value="'.$bloqueada.'" id="valor_bloqueo_'.$id.'" />
		</td>
	  </tr>
	</table>
	</div>
	
	';
		
		/////////////////////////////////////////////////////////////////////////
		
		$cont++;
		$resultados++;
		$result_coincidencias_finales->MoveNext();
	}
	
	if($resultados<$max_pagi){
		echo '<div id="noMas'.$pag.'" align="center" style="display:inline;">'._MASRESULTPAGNOMORE.'</div>';
	}else{
		echo '<div id="elementosPag'.$pag.'" align="center" style="display:none;"><img src="images/admin/cargando_admin.gif" alt="" /></div>';
	}
}else{
	echo '<div id="noMas'.$pag.'" align="center" style="display:inline;">'._MASRESULTPAGNOMORE.'</div>';
}
?>