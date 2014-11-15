<?php
include "../cabecera.php";

$variables_metodo = variables_metodo("etiqueta,pag,buscar_rss,fechaIni,fechaFin,categoria,semana_seleccionada,accion_seleccionada,fecha_seleccionada,web,fecha_desde,fecha_hasta,tipo_seleccionada,rss_id");
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

if(isset($_SESSION['bdm_etiquetas'][$id_etiqueta_mostrar]['clipping']) && $_SESSION['bdm_etiquetas'][$id_etiqueta_mostrar]['clipping']=="S"){
	echo "<META HTTP-EQUIV='Refresh' CONTENT='0;URL=".$_SESSION['c_base_location']."reputation_manager/listado_resultados_clipping.php?etiqueta=".$id_etiqueta_mostrar."'>";
	die();
}

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
$order_by = "ORDER BY a.fecha_registro DESC";

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
	$order_by = "ORDER BY a.marcada DESC, a.fecha_registro DESC";
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
		CASE WHEN a.link RLIKE '^[http://|https://]' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(link, '/', 3), '/', -1) ELSE SUBSTRING_INDEX(link, '/', 1) END AS sitios,
		id_categoria
FROM ic_rss_coincidencias a ".$indice."
WHERE a.id_etiqueta=".$id_etiqueta_mostrar." 
".$where."
".$order_by." 
LIMIT ".$count_2.",".$max_pagi." ";
$result_coincidencias_finales=$db->Execute($sql);

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
$checkInter6="";

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
if($accion_seleccionada=="4"){
	$checkInter6="checked";	
}

$checkTipo1="";
$checkTipo2="";
$checkTipo3="";

if($tipo_seleccionada=="photo"){
	$checkTipo1="checked";	
}
if($tipo_seleccionada=="video"){
	$checkTipo2="checked";	
}
if($tipo_seleccionada=="text"){
	$checkTipo3="checked";	
}

$checkRss1="";
$checkRss2="";
$checkRss3="";

if($rss_id=="438"){
	$checkRss1="checked";	
}
if($rss_id=="439"){
	$checkRss2="checked";	
}
if($rss_id=="433"){
	$checkRss3="checked";	
}
?>
<div id="aviso_nuevos" style="width:300px; padding:20px 30px; background:#333; color:#FFF; text-align:center; position:absolute; top: 30px; cursor:pointer; left:38%; display:none; z-index:999999999;" class="redondeado sombra" onclick="window.location.reload();">
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
	
	$("#listDir").mCustomScrollbar({
		axis:"y",
		live: true
	});
	
});
</script>
<div style="width:100%; overflow:hidden;">


<div style="width:100%; height: 40px; background:url(images/feb-2014/fondo_listado.jpg); margin-bottom: 15px">
	<div style="float:left;">
    	<h2 style="margin: 5px 0 0 30px; font-size: 17px; color:#FFF;" id="cantidadNuevos">
		<?=$_SESSION['bdm_etiquetas'][$id_etiqueta_mostrar]['etiqueta']." (".(($_SESSION['bdm_etiquetas'][$id_etiqueta_mostrar]['nuevos']=="")?"0":$_SESSION['bdm_etiquetas'][$id_etiqueta_mostrar]['nuevos'])." "._BDM_PORREVI." "._BDM_DE." ".$_SESSION['bdm_etiquetas'][$id_etiqueta_mostrar]['total']." "._BDM_RESULT.")"?>
        </h2>
    </div>
	<div style="float:right; width:300px;">
        <div class="columna"><img src="images/feb-2014/separador-listado.png" width="2" height="40" /></div><div class="columna menulistado" align="center" onClick="aplicaciones();" title="<?=_BDM_APLICA?>">
            <img src="images/feb-2014/aplicaciones.fw.png" width="22" height="17" style="margin-top:10px;"/>               
        </div><div class="columna"><img src="images/feb-2014/separador-listado.png" width="2" height="40" /></div><div class="columna menulistado" align="center" onClick="filtros_form();" title="<?=_BDM_FILTRO?>">
            <img src="images/feb-2014/filtros.fw.png" width="22" height="17" style="margin-top:10px;"/>               
        </div><div class="columna"><img src="images/feb-2014/separador-listado.png" width="2" height="40" /></div><div class="columna menulistado" align="center" onClick="mybigdateca();" title="<?=_BDM_BIGDATEK?>">
            <img src="images/feb-2014/bigdateca.fw.png" width="22" height="17" style="margin-top:10px;"/>              
        </div>
    </div>
</div>

<!-- ///////////////////////////////////////////////////////////// -->

<div id="aplicaciones" style="position:absolute; right:0; margin:-4px 5px 0 0; z-index: 999999; padding:2px; display:none;" class="casillaMenu sombra redondeado">   
    <div style="width:17px; position:absolute; left: 45px; margin:-15px 0 0 0;"><img src="images/punta.png" alt="" /></div> 
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
    <div style="width:17px; position:absolute; right: 135px; margin:-33px 0 0 0;"><img src="images/punta.png" alt="" /></div>
    <form action="reputation_manager/listado_resultados.php?etiqueta=<?=$id_etiqueta_mostrar?>" method="post" name="buscarRss" id="buscarRss" target="_self">
    <input type="hidden" id="pag" value="1" />
    <input type="text" style="width:145px; height:22px; border:1px solid #73AFAA;  background:#E8E8E8;" name="buscar_rss" value="<?=$buscar_rss?>" id="buscar_rss" class="columna redondeado"/>
    <div class="columna">
        <a href="javascript:;" onclick="document.buscarRss.submit();">
        	<img src="images/feb-2014/buscar.png" width="50" height="29" />
        </a>
    </div>
    
    
    <div style="margin:10px 0 10px 0; color:#FFF; font-weight:bold;"><?=_REPUTA_FILTRO0?></div>
    <div class="separadorCasillas0"></div>
    
    <div style="margin:10px 0 10px 0; color:#FFF; font-weight:bold;" class="filtrosListado textoEsta"><?=_REPUTA_FILTRO1?></div>
    <div class="separadorCasillas0"></div>
    <div id="contenidosFiltros" class="contenidosFiltros">
    	<div style="margin:10px 0 10px 0;">
            <div style="background:url(images/fechas.fw.png) center right; width:100px; height:29px; margin-right:7px;" class="columna redondeadoLeve">
                <input name="fecha_desde" id="fecha_desde" type="text" value="<?=(($variables_metodo[10]=="")?_FECHA_DESDE:$variables_metodo[10])?>" onclick="$(this).val('');" style="width:65px; float:left; margin:2px 0 0 3px; font-size:12px; border:none; background:none;" />
            </div>
            <div style="background:url(images/fechas.fw.png) center right; width:100px; height:29px;" class="columna redondeadoLeve">
                <input name="fecha_hasta" id="fecha_hasta" type="text" value="<?=(($variables_metodo[11]=="")?_FECHA_HASTA:$variables_metodo[11])?>" onclick="$(this).val('');" style="width:65px; float:left; margin:2px 0 0 3px; font-size:12px; border:none; background:none;"/>
            </div>
        </div>
        
        <select name="fecha_seleccionada" id="fecha_seleccionada" style="width:214px; border:1px solid #6DADA7; background:#e7e7e7;" onchange="document.buscarRss.submit();">
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
		echo '<label for="web1"><input type="radio" name="web" id="web1" value="2" onclick="document.buscarRss.submit();" '.$checkcat2.'/>  '._REPUTA_RS.' ('.$total.')</label><br />';
		
		echo '<label for="rss1" style="margin-left: 15px;"><input type="radio" name="rss_id" id="rss1" value="438" onclick="document.buscarRss.submit();" '.$checkRss1.'/>  '._BDM_TWITTER.'</label><br />';
		echo '<label for="rss2" style="margin-left: 15px;"><input type="radio" name="rss_id" id="rss2" value="439" onclick="document.buscarRss.submit();" '.$checkRss2.'/>  '._BDM_YOUTUBE.'</label><br />';
		echo '<label for="rss3" style="margin-left: 15px;"><input type="radio" name="rss_id" id="rss3" value="433" onclick="document.buscarRss.submit();" '.$checkRss3.'/>  '._BDM_FACEBOOK.'</label><br />';
	}
	if($categoria=="3"){
		echo '<label for="web2"><input type="radio" name="web" id="web2" value="3" onclick="document.buscarRss.submit();" '.$checkcat3.'/>  '._REPUTA_PRE.' ('.$total.')</label><br />';
	}
	if($categoria=="4"){
		echo '<label for="web3"><input type="radio" name="web" id="web3" value="4" onclick="document.buscarRss.submit();" '.$checkcat4.'/>  '._REPUTA_UNIV.' ('.$total.')</label><br />';
	}
	if($categoria=="5"){
		echo '<label for="web4"><input type="radio" name="web" id="web4" value="5" onclick="document.buscarRss.submit();" '.$checkcat5.'/>  '._REPUTA_MELEG.' ('.$total.')</label>';
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
				<label for="bloq"><input type="radio" name="accion_seleccionada" id="bloq" value="8" onclick="document.buscarRss.submit();" <?=$checkInter5?>/>  <?=_REPUTA_BLOQ?></label><br />
        		<label for="marca"><input type="radio" name="accion_seleccionada" id="marca" value="4" onclick="document.buscarRss.submit();" <?=$checkInter6?>/>  <?=_REPUTA_RANKEADA?></label>
            </td>
          </tr>
        </table>
    </div>
    
    <div style="margin:10px 0 10px 0; color:#FFF; font-weight:bold;" class="filtrosListado textoEsta"><?=_BDM_TIPOBUSQUEDA1?></div>
    <div class="separadorCasillas0"></div>
    <div id="contenidosFiltros" class="contenidosFiltros" style="color:#FFF">
    	<label for="tipo1"><input type="radio" name="tipo_seleccionada" id="tipo1" value="photo" onclick="document.buscarRss.submit();" <?=$checkTipo1?>/>  <?=_BDM_TIPOBUSQUEDAI?></label><br />
        <label for="tipo2"><input type="radio" name="tipo_seleccionada" id="tipo2" value="video" onclick="document.buscarRss.submit();" <?=$checkTipo2?>/>  <?=_BDM_TIPOBUSQUEDAV?></label><br />
        <label for="tipo3"><input type="radio" name="tipo_seleccionada" id="tipo3" value="text" onclick="document.buscarRss.submit();" <?=$checkTipo3?>/>  <?=_BDM_TIPOBUSQUEDATEX?></label>
    </div>
    
    <div style="margin:15px 0 0 0; text-align:right; cursor:pointer; width:100%;" onclick="$('#filtros_form').fadeOut();"><img src="images/feb-2014/flecha_arriba.png" alt="Close"/></div>
    </form>
</div>

<div id="mybigdateca" style="position:absolute; padding-top: 20px; right:0; margin:-4px 5px 0 0; z-index: 100; width: 230px; display:none;" class="casillaMenu3 sombra redondeado">   
    <div style="width:17px; position:absolute; right: 35px; margin:-33px 0 0 0;"><img src="images/punta.png" alt="" /></div>
    <div id="listDir" style="max-height:260px; width:230px; position:relative;">    
<?php
	if(isset($_SESSION['bdm_directorios'])){
        foreach($_SESSION['bdm_directorios'] as $direct){
            echo "<div id='dir".$direct['id']."' class='droppableDir' style='z-index: 200; width:190px;'>";
            echo '<div class="columna"><a href="javascript:;" onclick="directorioMultiples(\''.$direct['id'].'\')" style="color: #fff;">'.$direct['nombre'].'</a></div>';
            echo '<div class="columna" style="float: right;"><a href="javascript:;" onclick="eliminarDirectorio(\''.$direct['id'].'\');"><img src="images/menos.fw.png" alt="deldir"/></a></div>';
            echo "</div>";
			echo '<div class="separadorCasillas0"></div>';            
        }
	}
?>
    </div>
    <div style="margin:20px 0; padding-left:10px; color:#FFF;" align="center">
    	<img src="images/feb-2014/arrastrar.png" width="26" height="24" />
        <br />
		<?=_REPUTA_ARRAS?>
    </div>
    
  	<div style="margin:20px 0 7px 0; padding-left:10px; color:#FFF; font-weight:bold;"><?=_REPUTA_NUEVDIR?></div>
    <input type="text" style="width:150px; border:1px solid #73AFAA;  background:#E8E8E8;" name="new_dir" id="new_dir" class="columna redondeado"/>
    <div style="margin: 4px 0 0 4px;" class="columna">
        <a href="javascript:;" onClick="adicionarDirectorio();" style="padding:3px 13px; font-weight:bold; background:#E8E8E8; border:1px solid #73AFAA;" class="redondeado">+</a>
    </div>
</div>
            
<!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<script src="<?=$_SESSION['c_base_location']?>js/jquery.livequery.min.js"></script>
<script>
$(window).resize(function(e) {
    // Código de respuesta
	var ancho_ventana = $(window).width();
	var alto_ventana = $(window).height();
	
	$("#listado").css({"height": alto_ventana-90});
	$("#mini").css({"height": alto_ventana-90});
	$("#paginaWeb").css({"height": $(window).height()+90});
	$("#paginaWeb").css({"width": $(window).width()-460});
});

$(document).ready(function(){
	$("#listado").css({"height": $(window).height()-90});
	$("#mini").css({"height": $(window).height()-90});
	$("#paginaWeb").css({"height": $(window).height()+90});
	$("#paginaWeb").css({"width": $(window).width()-460});
	
	//$( ".resizable" ).resizable({ handles: "e, w" });
	var tam1 = $("#areaR").width();
	var tam2 = $("#mini").width()-20;
	
	$('#areaR').resizable({
        handles: 'e,w',
		ghost: true,
		start: function(){
			$("#mini").hide();
		},
		stop: function(){
			$("#mini").fadeIn();
			$("#mini").width( tam2 - ($("#areaR").width()-tam1) );
		}
    });
	
	//////////////////////////////////////////////////////////////////
	 
	 /*$('.dragDropListado').draggable({	  	
		iframeFix: true,
		cursor: "move",
		cursorAt: { top: 10, right: -3 },		
		helper: function( event ) {
			return $( "<div class='titulo' style='z-index:9999999; width:200px; text-align: right; overflow: hidden; white-space: nowrap;'>"+$("#tit_"+$(this).attr('id')).val()+"</div>" );
		},
	    stop: function( event, ui ) {
			$(this).fadeOut();
			$(this).fadeIn();
		},			
		opacity: 0.7,		
		revert: true
	 });
	 */
	 // lista directorios Bigdateca 
	 $('.droppableDir').droppable({		 
	  	drop: function(event, ui) {     
			var directorio = $(this).attr('id').replace("dir","");
			asignarDirectorios($(ui.draggable).attr('id'), directorio)
	  	},
		tolerance: "pointer",
		hoverClass: "droppableDirHover"
	 });
});
$(".dragDropListado") .livequery(function(){ $(this) .draggable({ 
		iframeFix: true,
		cursor: "move",
		cursorAt: { top: 10, right: -3 },	
		helper: function( event ) {
			return $( "<div class='titulo' style='width:200px; z-index:100000; text-align:right; overflow: hidden; white-space: nowrap;'>"+$("#tit_"+$(this).attr('id')).val()+"</div>" );
		},
	    stop: function( event, ui ) {
			$(this).fadeOut();
			$(this).fadeIn();
		},			
		opacity: 0.7,		
		revert: true 
	}); 
});
</script>

<!-- ///////////////////////////////////////////////////////////// --> 

<div style="height:10px;">
    <div style="display:block; width: 350px; float:left; margin:0 0 0 20px;">
    	<?=_SELECCIONAR?> <a href="javascript:;" onclick="seleccionar(true);"><?=_BDM_FORMLISTALL1?></a> | <a href="javascript:;" onclick="seleccionar(false);"><?=_BDM_FORMLISTALL2?></a>
        
        <span style="margin-left:15px;">[ <a href="javascript:;" onclick="eliminar('', false);"><?=_REPUTA_ELIMIN?></a> ]</span>
        <?php if($where!=""){ ?>
        <span style="margin-left:15px;">[ <a href="reputation_manager/listado_resultados.php?etiqueta=<?=$id_etiqueta_mostrar?>" target="_self"><?=_REPUTA_LIMFIL?></a> ]</span>
        <?php } ?>
    </div>
    <div id="informacion" style="display:block; float:right;  margin:0 10px 0 0;" align="right" onclick="$('#resumen').fadeIn();">
    	<div style="cursor:pointer; width:150px;"><?=_BDM_FORMLISTALL0?> <img src="images/info.png" style="float:right; margin-left:10px;" alt="" /></div>
        <div id="resumen" style="display:none; position:absolute; right:10px; width:250px; text-align:left; color:#FFF; background:#666; padding:10px; z-index:500000;" class="redondeado sombra">
        <?=$_SESSION['bdm_etiquetas'][$id_etiqueta_mostrar]['resumen']?>
        </div>
    </div>
</div>

<br />

<?php
if($result_coincidencias_finales->EOF && $where=="" && $_SESSION['bdm_etiquetas'][$id_etiqueta_mostrar]["estado"]=="A"){
	echo '<div id="aviso_nuevos" class="alertas_internas redondeado sombra" onclick="$(this).fadeOut();">'._BDM_NORESULT.'</div>';
}
?>
<div style="width:50%; display:block; float:left; position:relative; z-index:1000;" id="areaR">
<div style="width:100%; padding: 0 0 0 20px; overflow:auto;" id="listado">
<?php

$cont = 0;
$id = 0;
$resultados = 0;

////////////////////////////////////////

while(!$result_coincidencias_finales->EOF){
	
	list($id,$id_rss,$cargue,$marcada,$positivo,$negativo,$bloqueada,$intervenido,$cat_id,
	     $titulo,$contenido,$link,$semana,$neutro,$id_source,$detalle_intervenido,$fecha_registro,
		 $tipo_mencion,$usuario_medio,$media_url,$ubicacion,$info_adicional,$nom_rss,$categ)=select_format($result_coincidencias_finales->fields);
		
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
	
	$contenido = substr(strip_tags($contenido), -0, 200)."...";
	
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
	
	$class_intervenida="images/no_intervenidos.jpg";
	
	if($intervenido=="S"){
		$class_intervenida="images/intervenidos.jpg";
	}
	
	//--------------------------------
?>

<div class="tablaResultados" id="elemento<?=$id?>">

<table width="100%" border="0" cellspacing="0" cellpadding="1" style="margin-bottom:10px;" id="filaElemento<?=$cont?>">
  <tr>
    <td width="1" valign="top">
    <input type="checkbox" name="seleccion_<?=$id?>" id="seleccion_<?=$cont?>" value="<?=$id?>" />
    </td>
    <td width="5" valign="top">&nbsp;</td>
    <td valign="top">
    <input type="hidden" id="tit_<?=$id?>" value="<?=str_replace('"','',str_replace("'","",$titulo))?>" />
    <input type="hidden" id="cont_<?=$id?>" value="<?=str_replace('"','',str_replace("'","",$contenido))?>" />
    <input type="hidden" id="lin_<?=$id?>" value="<?=$link?>" />
    <input type="hidden" id="rss_<?=$id?>" value="<?=$id_rss?>" />
    <input type="hidden" id="id_<?=$id?>" value="<?=$id?>" />
    <input type="hidden" id="source_<?=$id?>" value="<?=$id_source?>" />
    <input type="hidden" id="media_<?=$id?>" value="<?=$media_enviar?>" />
    <input type="hidden" id="feed_<?=$id?>" value="<?=$detalle_intervenido?>" />
    <input type="hidden" id="cargue_<?=$id?>" value="<?=$cargue?>" />
    <input type="hidden" id="categ_<?=$id?>" value="<?=$categ?>" />
    <div class="columna">
        <a href="javascript:;" onclick="mostrarContenido('<?=$cat_id?>','<?=$id?>'); seleccionarFila('<?=$cont?>');" style="text-decoration:none">
            <li id="<?=$id?>" class="dragDropListado" style="list-style:none; padding:0; margin:0;">
            	<span class="<?=$clase_titulo?>" style="font-size:15px;"><?=$titulo?></span>
            </li>
        </a>    
    </div>
    <div class="columna" style="margin:2px 0 0 10px;">
    	<table border="0" cellspacing="0" cellpadding="1">
          <tr>
            <td><img src="<?=$class_marcada1?>" id="marca1_<?=$id?>" width="16" height="16" onclick="marcar('<?=$id?>', 1);" style="cursor:pointer" alt="<?=$marcaalt1?>"/></td>
            <td><img src="<?=$class_marcada2?>" id="marca2_<?=$id?>" width="16" height="16" onclick="marcar('<?=$id?>', 2);" style="cursor:pointer" alt="<?=$marcaalt2?>"/></td>
            <td><img src="<?=$class_marcada3?>" id="marca3_<?=$id?>" width="16" height="16" onclick="marcar('<?=$id?>', 3);" style="cursor:pointer" alt="<?=$marcaalt3?>"/></td>
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
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>
    	<div style="color:#999; font-size:13px;"><?=$fecha_publicacion?><b><?=$cat_titulo?></b> (<?=$nom_rss?>)</div>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>    
       <div style="color:#666; font-size:14px; line-height:19px; word-spacing:0.9pt;"><?=$contenido?></div>
       <div><?=$media?></div>
    	<input type="hidden" value="<?=$bloqueada?>" id="valor_bloqueo_<?=$id?>" />
    </td>
  </tr>
</table>
</div>

<?php
		$resultados++;
		$result_coincidencias_finales->MoveNext();
	}
?>
	<div id="elementosPag<?=$pag?>" align="center" style="display:none;"><img src="images/admin/cargando_admin.gif" alt="" /></div>
    
    <input type="hidden" name="final" id="final" value="<?=$max_pagi?>" />
    <input type="hidden" name="paginaActual" id="paginaActual" value="<?=$pag?>" />
<?php
	if($resultados>=$max_pagi){
?>
    <div style="cursor:pointer; background:#333333; padding:10px 35px; width:150px; color:#FFF; margin:20px auto;" id="moreResults" class="redondeado" onclick="masRegistros('<?=$id_etiqueta_mostrar?>','<?=$semana_seleccionada?>','<?=$buscar_rss?>','<?=$fecha_desde?>','<?=$fecha_hasta?>','<?=$tipo_seleccionada?>','<?=$accion_seleccionada?>','<?=$fecha_seleccionada?>','<?=$web?>','<?=$rss_id?>');" align="center"><?=_MASRESULTPAG?></div>
<?php
	}
?>
</div>
</div>

<form action="reputation_manager/enviar_a.php" method="post" name="enviara" id="enviara" target="_self">
    <input type="hidden" name="coincidenciasenviar" id="coincidenciasenviar" />
    <input type="hidden" name="etiqueta" id="etiqueta" value="<?=$id_etiqueta_mostrar?>" />
    <input type="hidden" name="usuario_cliente" id="usuario_cliente" value="<?=$_SESSION['sess_usu_grupo']?>" />
</form>


<div style="width:45%; padding: 0 0 0 30px; display:block; float:right; z-index:100;" id="mini">
	<div id="miniContenido" style="width:90%;"></div>
    <div id="linkContenido"></div>
	<iframe id="paginaWeb" name="coincidencia" frameborder="0" allowtransparency="true"></iframe>
</div>



<!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////// -->

</div>

</body>
</html>