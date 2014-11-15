<?php
include "../cabecera.php";
	
$variables_metodo = variables_metodo("etiqueta,pag,buscar_rss,fechaIni,fechaFin,categoria,semana_seleccionada,accion_seleccionada,fecha_seleccionada,web,fecha_desde,fecha_hasta,tipo_seleccionada,sitioweb_seleccionada,rss_id,paises,tematicas,origen,tipo_datos");
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
$fecha_desde= 			$variables_metodo[10];
$fecha_hasta= 			$variables_metodo[11];
$tipo_seleccionada= 	$variables_metodo[12];
$sitioweb_seleccionada= $variables_metodo[13];
$rss_id= 				$variables_metodo[14];
$paises= 				$variables_metodo[15];
$tematicas= 			$variables_metodo[16];
$origen= 				$variables_metodo[17];
$tipo_datos= 			$variables_metodo[18];

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

if($sitioweb_seleccionada!=""){
	$where .= " AND a.link LIKE '%".$sitioweb_seleccionada."%'";
}	

if($buscar_rss!="" && ($buscar_rss!="Filtra tu busqueda..." && $buscar_rss!="Narrow your search...")){
	$where .= " AND (a.contenido LIKE '%".$buscar_rss."%' OR a.titulo LIKE '%".$buscar_rss."%')";
}

if($fecha_desde!=""){
	$indice = " FORCE INDEX (indx_fechacargue)";
	$where .= " AND a.fecha_cargue >='".date('Y-m-d',strtotime($fecha_desde))."'";
}

if($fecha_hasta!=""){
	$indice = " FORCE INDEX (indx_fechacargue)";
	$where .= " AND a.fecha_cargue <='".date('Y-m-d',strtotime($fecha_hasta))."'";
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

$where_fuente = " datos IS NOT NULL ";

if($tipo_datos=="others"){
	$where_fuente = " datos IS NULL ";
}elseif($tipo_datos=="all"){
	$where_fuente = " 1 ";
}

$where_opciones = "";

if($paises!=""){
	$where_opciones = " AND datos LIKE '%".$paises."%'";
}
if($tematicas!=""){
	$where_opciones = " AND datos LIKE '%|".$tematicas."|%'";
}
if($origen!=""){
	$where_opciones = " AND datos LIKE '%|".$origen."|%'";
}

//Variables de la paginacion
$max_pagi = 10;
$count_1=$max_pagi;
$limit=1;
$count_2=0;

if($pag>1){
	$limit=$pag;
	$count_2=($count_1*$limit)-$count_1;
}
 
///////////////////////////////////////////////////////////////
$sql="SELECT id,id_rss,marcada,positivo,negativo,bloqueada,id_categoria,titulo,contenido,link,neutro,fecha_registro,media_url,info_adicional,sitios,datos FROM (
SELECT 
	a.id,a.id_rss,a.marcada,a.positivo,a.negativo,a.bloqueada,a.id_categoria,
	a.titulo,a.contenido,a.link,a.neutro,a.fecha_registro,a.media_url,a.info_adicional,
	CASE WHEN a.link RLIKE '^[http://|https://]' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(link, '/', 3), '/', -1) ELSE SUBSTRING_INDEX(link, '/', 1) END AS sitios,
	(SELECT CONCAT('|',b.tema,'||',b.paises,'||',b.origen_ciudad,'|') FROM ic_rss b 
	 WHERE b.nombre REGEXP (		
			CASE WHEN (sitios) LIKE 'www.' THEN SUBSTRING_INDEX(sitios,'www.',1) ELSE sitios END
		) LIMIT 0,1 ) AS datos
FROM 
	ic_rss_coincidencias a ".$indice."
WHERE
	a.id_etiqueta=".$id_etiqueta_mostrar." 
	".$where."
ORDER BY a.fecha_registro DESC) tabla 
WHERE ".$where_fuente."".$where_opciones." ORDER BY fecha_registro DESC LIMIT ".$count_2.",".$max_pagi."";
$result_coincidencias_finales	=$db->Execute($sql);


$sql="SELECT id,id_rss,marcada,positivo,negativo,bloqueada,id_categoria,titulo,contenido,link,neutro,fecha_registro,media_url,info_adicional,sitios,datos FROM (
SELECT 
	a.id,a.id_rss,a.marcada,a.positivo,a.negativo,a.bloqueada,a.id_categoria,
	a.titulo,a.contenido,a.link,a.neutro,a.fecha_registro,a.media_url,a.info_adicional,
	CASE WHEN a.link RLIKE '^[http://|https://]' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(link, '/', 3), '/', -1) ELSE SUBSTRING_INDEX(link, '/', 1) END AS sitios,
	(SELECT CONCAT('|',b.tema,'||',b.paises,'||',b.origen_ciudad,'|') FROM ic_rss b 
	 WHERE b.nombre REGEXP (		
			CASE WHEN (sitios) LIKE 'www.' THEN SUBSTRING_INDEX(sitios,'www.',1) ELSE sitios END
		) LIMIT 0,1 ) AS datos
FROM 
	ic_rss_coincidencias a ".$indice."
WHERE
	a.id_etiqueta=".$id_etiqueta_mostrar." 
ORDER BY a.fecha_registro DESC) tabla 
WHERE ".$where_fuente." ORDER BY fecha_registro DESC";
$result_totalcoincidencias		=$db->Execute($sql);

/////////////////////////////////////////////////////////////////


$sql_sitios1="(SELECT 
CASE WHEN a.link RLIKE '^[http://|https://]' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(link, '/', 3), '/', -1) ELSE SUBSTRING_INDEX(link, '/', 1) END AS sitios,
(SELECT CONCAT('|',b.tema,'||',b.paises,'||',b.origen_ciudad,'|') FROM ic_rss b 
 WHERE b.nombre REGEXP (		
		CASE WHEN (sitios) LIKE 'www.' THEN SUBSTRING_INDEX(sitios,'www.',1) ELSE sitios END
 ) LIMIT 0,1 ) AS datos
FROM 
ic_rss_coincidencias a ".$indice."
WHERE
a.id_etiqueta=".$id_etiqueta_mostrar." 
".$where."
ORDER BY a.fecha_registro) tabla";
$sql_sitios2="distinct REPLACE(sitios;'www.';'')";  
$sql_sitios3=$where_fuente; 


$sql_sitios11="(SELECT 
CASE WHEN a.link RLIKE '^[http://|https://]' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(link, '/', 3), '/', -1) ELSE SUBSTRING_INDEX(link, '/', 1) END AS sitios,
(SELECT b.tema FROM ic_rss b 
 WHERE b.nombre REGEXP (		
		CASE WHEN (sitios) LIKE 'www.' THEN SUBSTRING_INDEX(sitios,'www.',1) ELSE sitios END
 ) LIMIT 0,1 ) AS datos
FROM 
ic_rss_coincidencias a ".$indice."
WHERE
a.id_etiqueta=".$id_etiqueta_mostrar." 
".$where."
ORDER BY a.fecha_registro) tabla";
$sql_sitios22="distinct datos";  
$sql_sitios33=$where_fuente; 


/////////////////////////////////////////////////////////////////
$total_resultados=0;
$total_reg=0;
$temas_query=array();
$paises_query=array();
$ciudades_query=array();
$web_query=array();

while(!$result_totalcoincidencias->EOF){
	
	list($id,$id_rss,$marcada,$positivo,$negativo,$bloqueada,$id_categoria,$titulo,
	     $contenido,$link,$neutro,$fecha_registro,$media_url,$info_adicional,$sitios,
		 $datos)=select_format($result_totalcoincidencias->fields);
	
	$datos = explode("||",$datos);
	$tema_ = str_replace("*","",str_replace("|","",$datos[0]));
	$pais_ = str_replace("|","",$datos[1]);
	$pais_ = explode("**",$pais_);
	$ciudad_ = str_replace("*","",str_replace("|","",$datos[2]));
	
	array_push($temas_query, $tema_);
	
	for($i=0; $i<count($pais_);$i++){
		array_push($paises_query, str_replace("*","",$pais_[$i]));
	}
	
	array_push($ciudades_query, $ciudad_);
	array_push($web_query, $sitios);
	
	$total_resultados++;
	if($datos!=""){
		$total_reg++;
	}
	$result_totalcoincidencias->MoveNext();
}

$tema_ = array_unique($temas_query);
$paises_ = array_unique($paises_query);
$ciudad_ = array_unique($ciudades_query);
$web_ = array_unique($web_query);

$temasfiltro = "";
foreach($tema_ as $t){
	if(trim($t)!=""){
		$temasfiltro .= $t.",";
	}
}
$temasfiltro = rtrim($temasfiltro,",");

$paisesfiltro = "";
foreach($paises_ as $p){
	if(trim($p)!=""){
		$paisesfiltro .= $p.",";
	}
}
$paisesfiltro = rtrim($paisesfiltro,",");

$ciudadfiltro = "";
foreach($ciudad_ as $c){
	if(trim($c)!=""){
		$ciudadfiltro .= $c.",";
	}
}
$ciudadfiltro = rtrim($ciudadfiltro,",");

$webfiltro = "";
foreach($web_ as $w){	
	if(trim($w)!=""){
		$webfiltro .= $w.",";
	}
}
$webfiltro = rtrim($webfiltro,",");

//-------------------------------------------------------------------------------------

$checkcat2="";
$checkcat3="";
$checkcat4="";
$checkcat5="";

if($web=="2"){
	$checkcat2="checked";	
}
if($web=="3"){
	$checkcat3="checked";	
}
if($web=="4"){
	$checkcat4="checked";	
}
if($web=="5"){
	$checkcat5="checked";	
}

$checkInter1="";
$checkInter2="";
$checkInter3="";
$checkInter4="";
$checkInter5="";

if($accion_seleccionada=="1"){
	$checkInter1="checked";	
}
if($accion_seleccionada=="2"){
	$checkInter2="checked";	
}
if($accion_seleccionada=="10"){
	$checkInter3="checked";	
}
if($accion_seleccionada=="3"){
	$checkInter4="checked";	
}
if($accion_seleccionada=="8"){
	$checkInter5="checked";	
}

?>
<div id="aviso_nuevos" style="width:300px; padding:20px 30px; background:#333; color:#FFF; text-align:center; position:absolute; top: 30px; cursor:pointer; left:38%; display:none;" class="redondeado sombra" onclick="window.location.reload();">
<?=_BDM_NEWRESULT?>
</div>

<script>
	$(function() {
		$( document ).tooltip();
	});
	
$(function() {
	$( "#fecha_desde" ).datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd-mm-yy",
		onSelect: function(dateText, inst) { 
			document.buscarRss.submit();
		}
	});
	$( "#fecha_hasta" ).datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd-mm-yy",
		onSelect: function(dateText, inst) { 
			document.buscarRss.submit();
		}
	});
});

$(window).resize(function(e) {
    // Código de respuesta
	var ancho_ventana = $(window).width();
	var alto_ventana = $(window).height();
	
	$("#listado").css({"height": alto_ventana-220});
});

$(document).ready(function(){
	$("#listado").css({"height": $(window).height()-220});
});

</script>
<div style="width:100%; overflow:hidden;">


<div style="width:100%; height: 40px; background:url(images/feb-2014/fondo_listado.jpg); margin-bottom: 15px">
	<div style="float:left;">
    	<h2 style="margin: 5px 0 0 30px; font-size: 17px; color:#FFF;" id="cantidadNuevos">
		<?=$_SESSION['bdm_etiquetas'][$id_etiqueta_mostrar]['etiqueta']." (".$_SESSION['bdm_etiquetas'][$id_etiqueta_mostrar]['total']." "._BDM_RESULT.")"?>
        </h2>
    </div>
	<div style="float:right; width:200px;">
        <div class="columna"><img src="images/feb-2014/separador-listado.png" width="2" height="40" /></div><div class="columna menulistado" align="center" onClick="aplicaciones();" title="<?=_BDM_APLICA?>">
            <img src="images/feb-2014/aplicaciones.fw.png" width="22" height="17" style="margin-top:10px;"/>               
        </div><div class="columna"><img src="images/feb-2014/separador-listado.png" width="2" height="40" /></div><div class="columna menulistado" align="center" onClick="filtros_form();" title="<?=_BDM_FILTRO?>">
            <img src="images/feb-2014/filtros.fw.png" width="22" height="17" style="margin-top:10px;"/>               
        </div>
    </div>
</div>

<!-- ///////////////////////////////////////////////////////////// -->

<div id="aplicaciones" style="position:absolute; right:0; margin:-4px 5px 0 0; z-index: 999999; padding:2px; display:none;" class="casillaMenu sombra redondeado">   
    <div style="width:17px; position:absolute; left: 140px; margin:-15px 0 0 0;"><img src="images/punta.png" alt="" /></div> 
    <table style="display: inline-table;" border="0" cellpadding="0" cellspacing="0" width="294">
      <tr>
        <td>
        <div class="menuaplicaciones" onClick="marcaCompartir('facebook');"> 
        	<img name="aplicaciones_r1_c1" src="images/feb-2014/aplicaciones_r1_c1.png" width="71" height="73" id="aplicaciones_r1_c1" alt="" />
        </div> 
        </td>
        <td><img name="aplicaciones_r1_c2" src="images/feb-2014/aplicaciones_r1_c2.png" width="2" height="73" id="aplicaciones_r1_c2" alt="" /></td>
        <td>
        <div class="menuaplicaciones" onClick="marcaCompartir('twitter');"> 
        	<img name="aplicaciones_r1_c3" src="images/feb-2014/aplicaciones_r1_c3.png" width="74" height="73" id="aplicaciones_r1_c3" alt="" />
        </div> 
        </td>
        <td><img name="aplicaciones_r1_c4" src="images/feb-2014/aplicaciones_r1_c4.png" width="2" height="73" id="aplicaciones_r1_c4" alt="" /></td>
        <td>
        <div class="menuaplicaciones" onClick="marcasMultiplesEnviar();"> 
        	<img name="aplicaciones_r1_c5" src="images/feb-2014/aplicaciones_r1_c5.png" width="71" height="73" id="aplicaciones_r1_c5" alt="" />
        </div> 
        </td>
        <td><img name="aplicaciones_r1_c6" src="images/feb-2014/aplicaciones_r1_c6.png" width="2" height="73" id="aplicaciones_r1_c6" alt="" /></td>
        <td>
        <div class="menuaplicaciones"> 
        	<img name="aplicaciones_r1_c7" src="images/feb-2014/aplicaciones_r1_c7.png" width="72" height="73" id="aplicaciones_r1_c7" alt="" />
        </div> 
        </td>
      </tr>
    </table>
</div> 

<div id="filtros_form" style="position:absolute; padding-top: 20px; right:0; margin:-4px 5px 0 0; z-index: 999999; width: 211px; display:none;" class="casillaMenu sombra redondeado">   
    <div style="width:17px; position:absolute; right: 37px; margin:-33px 0 0 0;"><img src="images/punta.png" alt="" /></div>
    <form action="reputation_manager/listado_resultados_clipping.php?etiqueta=<?=$id_etiqueta_mostrar?>" method="post" name="buscarRss2" id="buscarRss2" target="_self">
    <input type="hidden" id="pag" value="1" />
    <input type="text" style="width:145px; height:22px; border:1px solid #73AFAA;  background:#E8E8E8;" name="buscar_rss" value="<?=$buscar_rss?>" id="buscar_rss" class="columna redondeado"/>
    <div class="columna">
        <a href="javascript:;" onclick="document.buscarRss2.submit();">
        	<img src="images/feb-2014/buscar.png" width="50" height="29" />
        </a>
    </div>
    
    
    <div style="margin:10px 0 10px 0; color:#FFF; font-weight:bold;"><?=_REPUTA_FILTRO0?></div>
    <div class="separadorCasillas0"></div>
    
    <div style="margin:10px 0 10px 0; color:#FFF; font-weight:bold;" class="filtrosListado textoEsta"><?=_REPUTA_FILTRO1?></div>
    <div class="separadorCasillas0"></div>
    <div id="contenidosFiltros" class="contenidosFiltros">    	
        <select name="fecha_seleccionada" id="fecha_seleccionada" style="width:214px; border:1px solid #6DADA7; background:#e7e7e7;" onchange="document.buscarRss2.submit();">
        	<option value=""></option>
            <?php cargar_lista_estatica("-1,1,2,3,4,5",""._REPUTA_FILTROFE0.","._REPUTA_FILTROFE1.","._REPUTA_FILTROFE2.","._REPUTA_FILTROFE3.","._REPUTA_FILTROFE4.","._REPUTA_FILTROFE5."",0,$fecha_seleccionada); ?>
        </select>
    </div>
    
    <div style="margin:10px 0 10px 0; color:#FFF; font-weight:bold;" class="filtrosListado textoEsta"><?=_REPUTA_FILTRO2?></div>
    <div class="separadorCasillas0"></div>
    <div id="contenidosFiltros" class="contenidosFiltros" style="color:#FFF">
<?php
$sql="SELECT categoria, b.cat_titulo, SUM(total) FROM ic_estadisticas a, ic_categoria b 
      WHERE a.categoria=b.cat_id AND a.id_etiqueta='".$id_etiqueta_mostrar."' GROUP BY a.categoria, b.cat_titulo";
$result_categorias=$db->Execute($sql);

while(!$result_categorias->EOF){
	list($categoria,$titulo,$total)=$result_categorias->fields;
	
	$total = formateo_numero($total);
	
	if($categoria=="2"){
		echo '<label for="web1"><input type="radio" name="web" id="web1" value="2" onclick="document.buscarRss2.submit();" '.$checkcat2.'/>  '._REPUTA_RS.' ('.$total.')</label><br />';
	}
	if($categoria=="3"){
		echo '<label for="web2"><input type="radio" name="web" id="web2" value="3" onclick="document.buscarRss2.submit();" '.$checkcat3.'/>  '._REPUTA_PRE.' ('.$total.')</label><br />';
	}
	if($categoria=="4"){
		echo '<label for="web3"><input type="radio" name="web" id="web3" value="4" onclick="document.buscarRss2.submit();" '.$checkcat4.'/>  '._REPUTA_UNIV.' ('.$total.')</label><br />';
	}
	if($categoria=="5"){
		echo '<label for="web4"><input type="radio" name="web" id="web4" value="5" onclick="document.buscarRss2.submit();" '.$checkcat5.'/>  '._REPUTA_MELEG.' ('.$total.')</label>';
	}
	
	$result_categorias->MoveNext();
}
?>
	</div>
    <div style="margin:10px 0 10px 0; color:#FFF; font-weight:bold;" class="filtrosListado textoEsta"><?=_REPUTA_FILTRO3?></div>
    <div class="separadorCasillas0"></div>
    <div id="contenidosFiltros" class="contenidosFiltros">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" style="color:#FFF">
                <label for="posi"><input type="radio" name="accion_seleccionada" id="posi" value="1" onclick="document.buscarRss.submit();" <?=$checkInter1?>/>  <?=_REPUTA_POSI?></label><br />
                <label for="nega"><input type="radio" name="accion_seleccionada" id="nega" value="2" onclick="document.buscarRss.submit();" <?=$checkInter2?>/>  <?=_REPUTA_NEGA?></label><br />
                <label for="neut"><input type="radio" name="accion_seleccionada" id="neut" value="10" onclick="document.buscarRss.submit();" <?=$checkInter3?>/>  <?=_REPUTA_NEUTRO?></label>
            </td>
            <td valign="top" style="color:#FFF">
            	<label for="nocal"><input type="radio" name="accion_seleccionada" id="nocal" value="3" onclick="document.buscarRss.submit();" <?=$checkInter4?>/>  <?=_REPUTA_NO_CALIF?></label><br />
				<label for="bloq"><input type="radio" name="accion_seleccionada" id="bloq" value="8" onclick="document.buscarRss.submit();" <?=$checkInter5?>/>  <?=_REPUTA_BLOQ?></label>
            </td>
          </tr>
        </table>
    </div>    
    
    <div style="margin:15px 0 0 0; text-align:right; cursor:pointer; width:100%;" onclick="$('#filtros_form').fadeOut();"><img src="images/feb-2014/flecha_arriba.png" alt="Close"/></div>
    </form>
</div>

<!-- ///////////////////////////////////////////////////////////// --> 

<div style="height:20px;">
<form action="" method="post" name="buscarRss" id="buscarRss" target="_self">
    <input type="hidden" name="tipo_datos" value="<?=$tipo_datos?>" />
    
    <!-- //////////////////////////////// -->
<?php
	$total_regionales = 0;
	$total_otros = 0;
	$fondo_color1 = "#fff";
	$fondo_color2 = "#fff";
	$fondo_color3 = "#fff";
	
	if($tipo_datos=="others"){
		$total_otros = $total_resultados;
		$total_regionales = $_SESSION['bdm_etiquetas'][$id_etiqueta_mostrar]['total'] - $total_resultados;
		$fondo_color2 = "#EBF3F3";
	}elseif($tipo_datos=="all"){
		$total_regionales = $total_reg;
		$total_otros = $_SESSION['bdm_etiquetas'][$id_etiqueta_mostrar]['total'] - $total_reg;
		$fondo_color3 = "#EBF3F3";
	}else{
		$total_regionales = $total_resultados;
		$total_otros = $_SESSION['bdm_etiquetas'][$id_etiqueta_mostrar]['total'] - $total_resultados;
		$fondo_color1 = "#EBF3F3";
	}

?>
    <div style="display:block; float:left; width:100%; overflow:hidden; margin:0 0 10px 0; padding:10px 0;">
        <div style="width:70%; border:1px solid #CCC; margin:0 auto; overflow:hidden; border-radius:5px;" class="sombra">
            <div style="width:33%; display:block; float:left; padding:10px 0; background:<?=$fondo_color1?>; border-right:1px solid #CCC; text-align:center"><a href="reputation_manager/listado_resultados_clipping.php?etiqueta=<?=$id_etiqueta_mostrar?>" target="_self" style="color:#4E9492; font-size:15px; font-weight:bold;">Medios de Comunicaci&oacute;n <span style="color:#000; font-weight:normal;"><?=formateo_numero($total_regionales)?></span></a></div>
            <div style="width:33%; display:block; float:left; padding:10px 0; background:<?=$fondo_color2?>; border-right:1px solid #CCC; text-align:center"><a href="reputation_manager/listado_resultados_clipping.php?etiqueta=<?=$id_etiqueta_mostrar?>&tipo_datos=others" target="_self" style="color:#4E9492; font-size:15px; font-weight:bold;">Otros sitios <span style="color:#000; font-weight:normal;"><?=formateo_numero($total_otros)?></span></a></div>
            <div style="width:33%; display:block; float:right; padding:10px 0; background:<?=$fondo_color3?>; text-align:center"><a href="reputation_manager/listado_resultados_clipping.php?etiqueta=<?=$id_etiqueta_mostrar?>&tipo_datos=all" target="_self" style="color:#4E9492; font-size:15px; font-weight:bold;">Todos</a></div>
        </div>
    </div>
    
    <!-- //////////////////////////////// -->
    <div style="display:block; float:left; width:100%; overflow:hidden; margin:0 auto 15px auto;">
        <div style="width:70%; margin:0 auto; overflow:hidden;">
        
            <!-- //////////////////////////////// -->
            
            <div style="display:block; width: 240px; float:left; margin:0 0 0 25px;">
                <div style="display:block; float:left; margin: 5px 10px 0 0;">Web</div>
                <select name="sitioweb_seleccionada" id="sitioweb_seleccionada" style="width:200px; border:1px solid #ccc; background:#e7e7e7;" onchange="document.buscarRss.submit();">
                    <option value=""></option>
                    <?php cargar_lista_estatica($webfiltro,$webfiltro,"0",$sitioweb_seleccionada); ?>
                 </select>
            </div>
            
            <!-- //////////////////////////////// -->
            
            <div style="display:block; width: 155px; float:left; margin:0 0 0 20px;">
                <div style="display:block; float:left; margin: 5px 10px 0 0;">T&oacute;pico</div>
                <select name="tematicas" id="tematicas" style="width:100px; border:1px solid #ccc; background:#e7e7e7;" onchange="document.buscarRss.submit();">
                    <option value=""></option>
                    <?php cargar_lista_estatica($temasfiltro,$temasfiltro,"0",$tematicas); ?>
                 </select>
            </div>
            
            <!-- //////////////////////////////// -->
            
            <div style="display:block; width: 155px; float:left; margin:0 0 0 20px;">
                <div style="display:block; float:left; margin: 5px 10px 0 0;">Paises</div>
                <select name="paises" id="paises" style="width:100px; border:1px solid #ccc; background:#e7e7e7;" onchange="document.buscarRss.submit();">
                    <option value=""></option>
                    <?php cargar_lista_estatica($paisesfiltro,$paisesfiltro,"0",$paises); ?>
                 </select>
            </div>
            
            <!-- //////////////////////////////// -->
            
            <div style="display:block; width: 155px; float:left; margin:0 0 0 20px;">
                <div style="display:block; float:left; margin: 5px 10px 0 0;">Origen</div>
                <select name="origen" id="origen" style="width:100px; border:1px solid #ccc; background:#e7e7e7;" onchange="document.buscarRss.submit();">
                    <option value=""></option>
                    <?php cargar_lista_estatica($ciudadfiltro,$ciudadfiltro,"0",$origen); ?>
                 </select>
            </div>
            
        </div>
    </div>
    
    <!-- //////////////////////////////// -->
    
    <div style="display:block; float:left; width:100%; overflow:hidden; padding:7px 0 15px 0;">
    
        <div style="display:block; width: 250px; float:left; margin:5px 0 0 20px;">
            <?=_SELECCIONAR?> <a href="javascript:;" onclick="seleccionar(true);"><?=_BDM_FORMLISTALL1?></a> | <a href="javascript:;" onclick="seleccionar(false);"><?=_BDM_FORMLISTALL2?></a>
            
            <span style="margin-left:15px;">[ <a href="javascript:;" onclick="eliminar('', false);"><?=_REPUTA_ELIMIN?></a> ]</span>
        </div>
        
        <div style="display:block; float:left; margin:0px 0 0 10px;">
            <div style="background:url(images/fechas-blanca.png) center right; width:100px; height:29px; margin-right:7px;" class="columna redondeadoLeve" id="fecha_desde2">
                <input name="fecha_desde" placeholder="<?=_FECHA_DESDE?>" id="fecha_desde" type="text" value="<?=$fecha_desde?>" style="width:100px; float:left; margin:2px 0 0 3px; font-size:12px; border:none; background:none;" />
            </div>
            <div style="background:url(images/fechas-blanca.png) center right; width:100px; height:29px;" class="columna redondeadoLeve" id="fecha_hasta2">
                <input name="fecha_hasta" placeholder="<?=_FECHA_HASTA?>" id="fecha_hasta" type="text" value="<?=$fecha_hasta?>" style="width:100px; float:left; margin:2px 0 0 3px; font-size:12px; border:none; background:none;"/>
            </div>
        </div>
        
        <div id="informacion" style="display:block; float:right;  margin:0 10px 0 0;" align="right" onclick="$('#resumen').fadeIn();">
            <div style="cursor:pointer; width:150px;"><?=_BDM_FORMLISTALL0?> <img src="images/info.png" style="float:right; margin-left:10px;" alt="" /></div>
            <div id="resumen" style="display:none; position:absolute; right:10px; width:250px; text-align:left; color:#FFF; background:#666; padding:10px; z-index:500000;" class="redondeado sombra">
            <?=$_SESSION['bdm_etiquetas'][$id_etiqueta_mostrar]['resumen']?>
            </div>
        </div>
    
    </div>
</form>
</div>
<br />

<div style="width:98%; padding: 0 0 0 20px; overflow:auto; display:block; float:left;" id="listado">
<?php

$cont=0;
$id = 0;

if($result_coincidencias_finales->EOF && $where=="" && $_SESSION['bdm_etiquetas'][$id_etiqueta_mostrar]["estado"]=="A"){
	echo '<div id="aviso_nuevos" class="alertas_internas redondeado sombra" onclick="$(this).fadeOut();">'._BDM_NORESULT.'</div>';
}

////////////////////////////////////////

while(!$result_coincidencias_finales->EOF){
	
	list($id,$id_rss,$marcada,$positivo,$negativo,$bloqueada,$id_categoria,$titulo,
	     $contenido,$link,$neutro,$fecha_registro,$media_url,$info_adicional,$sitios,
		 $datos)=select_format($result_coincidencias_finales->fields);
		
	/////////////////////////////////////////////////////////////////////////
	
	$datos = explode("||",$datos);
	$tema = str_replace("*","",str_replace("|","",$datos[0]));
	$pais = str_replace("*","",str_replace("|","",$datos[1]));
	$ciudad = str_replace("*","",str_replace("|","",$datos[2]));
	
	$fecha_publicacion = "";
	
	if($fecha_registro!="" && $fecha_registro!="0"){
		$fecha_publicacion = date('d/m/Y', $fecha_registro);
	}
	
	/////////////////////////////////////////////////////////////////////////
	
	$sql="SELECT b.nombre FROM ic_concidencia_directorio a, ic_directorios b WHERE a.id_directorio=b.id AND a.id_coincidencia='".$id."'";
	$result_dir=$db->Execute($sql);
	list($nombre_dir)=select_format($result_dir->fields);
	
	/////////////////////////////////////////////////////////////////////////
	
	if($cont==0){
		echo '<input type="hidden" name="inicio" id="inicio" value="1" />';
	}
	$cont++;
	
	//--------------------------------
	
	$contenido = substr(strip_tags($contenido), -0, 150)."...";
	
	//--------------------------------
	
	$clase_titulo="titulo";
	
	//--------------------------------
	
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
	
	//--------------------------------
	
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
	
	//--------------------------------
	
	$class_bloqueada="images/feb-2014/bloq-i.fw.png";
	
	if($bloqueada=="S"){
		$class_bloqueada="images/feb-2014/bloq-a.fw.png";
	}
	
	//--------------------------------
?>

<div class="tablaResultados" id="elemento<?=$id?>">

<table width="100%" border="0" cellspacing="3" cellpadding="5" style="margin-bottom:5px;" id="filaElemento<?=$cont?>">
  <tr>
    <td width="1" valign="top">
    <input type="checkbox" name="seleccion_<?=$id?>" id="seleccion_<?=$cont?>" value="<?=$id?>" onclick="seleccionarFila('<?=$cont?>');"/>
    <input type="hidden" id="tit_<?=$id?>" value="<?=str_replace("'","",$titulo)?>" />
    <input type="hidden" id="cont_<?=$id?>" value="<?=str_replace("'","",$contenido)?>" />
    <input type="hidden" id="lin_<?=$id?>" value="<?=$link?>" />
    <input type="hidden" id="rss_<?=$id?>" value="<?=$id_rss?>" />
    <input type="hidden" id="id_<?=$id?>" value="<?=$id?>" />
    <input type="hidden" value="<?=$bloqueada?>" id="valor_bloqueo_<?=$id?>" />
    </td>  
    <td valign="top" width="70" align="center"><?=$fecha_publicacion?></td>
    <td valign="top">
    	<a href="<?=$link?>" target="_blank"><span class="<?=$clase_titulo?>" style="font-size:15px;"><?=$titulo?></span></a>
        <div style="color:#999999; width:100%; display:block; float: left; margin-top:5px;"><?=$contenido?></div>
        <div style="margin:10px 0 0 0; display:block; float: left;">
            <div id="calificaciones<?=$id?>" class="columna" style="margin: 3px 10px 0 0; float:left">
                <div class="columna" style="cursor:pointer" onclick="calificar('P','<?=$id?>');" title="<?=_REPUTA_CALPOSI?>">
                    <?=$class_calif_pos?>
                </div>
                <div class="columna" style="border-left:1px solid #CBCBCB; height:18px; margin:0 10px 0 10px;"></div>
                <div class="columna" style="cursor:pointer" onclick="calificar('NE','<?=$id?>');" title="<?=_REPUTA_CALNEUTRO?>">
                    <?=$class_calif_neu?>
                </div>
                <div class="columna" style="border-left:1px solid #CBCBCB; height:18px; margin:0 10px 0 10px;"></div>
                <div class="columna" style="cursor:pointer" onclick="calificar('N','<?=$id?>');" title="<?=_REPUTA_CALNEGA?>">
                    <?=$class_calif_neg?>
                </div>
                <div class="columna" style="border-left:1px solid #CBCBCB; height:18px; margin:0 10px 0 10px;"></div>
                <div class="columna" style="cursor:pointer" onclick="bloqueada('<?=$id?>', true)" title="<?=_REPUTA_BLOQ?>">
                    <img src="<?=$class_bloqueada?>" id="imgbloq_<?=$id?>" border="0" />
                </div>
                <div class="columna" style="border-left:1px solid #CBCBCB; height:18px; margin:0 10px 0 10px;"></div>
                <div class="columna" style="cursor:pointer" onclick="eliminar('<?=$id?>', true);" title="<?=_REPUTA_ELIMIN?>">
                    <img src="images/feb-2014/elim.fw.png" border="0" />
                </div>
                <div class="columna" style="border-left:1px solid #CBCBCB; height:18px; margin:0 10px 0 10px;"></div>
            </div>
            <div id="dirteca<?=$id?>" class="dir_res columna" style="float:left"><?=$nombre_dir?></div> 
        </div>
    </td>     
    <td valign="top" width="50" align="center"> 
		<?=$tema?>
    </td>  
    <td valign="top" width="120" align="center"> 
		<?=$pais."<br>(".$ciudad.")"?>
    </td> 
    <td valign="top" width="150" align="center">
 		<div style="width:150px; overflow:hidden;" ><a href="<?=$link?>" target="_blank"><?=$sitios?></a></div>
    </td>
  </tr>
</table>
</div>
<div style="width:100%; display:block; background:#fafafa; height:5px;"></div>
<?php
		$result_coincidencias_finales->MoveNext();
	}
	
	echo '<input type="hidden" name="final" id="final" value="10" />';
	
	if($total_resultados>$max_pagi){
		
		echo '<div style="border-top: 1px solid #ccc; width: 95%; margin: 22px auto;"></div>';

		paginas($total_resultados, 
				$pag, 
				$max_pagi, 
				"reputation_manager/listado_resultados_clipping.php?etiqueta=".$id_etiqueta_mostrar."".
				                                         "&semana_seleccionada=".$semana_seleccionada."".
														 "&buscar_rss=".$buscar_rss."".
														 "&fecha_desde=".$fecha_desde."".
														 "&fecha_hasta=".$fecha_hasta."".
														 "&tipo_seleccionada=".$tipo_seleccionada."".
														 "&accion_seleccionada=".$accion_seleccionada."".
														 "&fecha_seleccionada=".$fecha_seleccionada."".
														 "&web=".$web."".
														 "&fechaIni=".$fechaIni."".
														 "&fechaFin=".$fechaFin."".
														 "&categoria=".$categoria."".
														 "&fecha_desde=".$fecha_desde."".
														 "&fecha_hasta=".$fecha_hasta."".
														 "&sitioweb_seleccionada=".$sitioweb_seleccionada."".
														 "&paises=".$paises."".
														 "&tematicas=".$tematicas."".
														 "&origen=".$origen."".
														 "&tipo_datos=".$tipo_datos."".
														 "&rss_id=".$rss_id."");
	}
?>

</div>

<form action="reputation_manager/enviar_a.php" method="post" name="enviara" id="enviara" target="_self">
    <input type="hidden" name="coincidenciasenviar" id="coincidenciasenviar" />
    <input type="hidden" name="etiqueta" id="etiqueta" value="<?=$id_etiqueta_mostrar?>" />
</form>

</body>
</html>