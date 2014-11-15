<?php
include "../cabecera.php";
$variables_metodo = variables_metodo("etiqueta");
$id_etiqueta_mostrar= 	$variables_metodo[0];

if($_SESSION['bdm_etiquetas'][$id_etiqueta_mostrar]['clipping']=="S"){
	die("<META HTTP-EQUIV='Refresh' CONTENT='0;URL=".$_SESSION['c_base_location']."reputation_manager/estadisticas_graficas_clipping.php?etiqueta=".$id_etiqueta_mostrar."'>");
}

$sql="SELECT totalesInterSenti ('".$id_etiqueta_mostrar."') AS `totalesInterSenti` ;";
$rs_inter=$db->Execute($sql);
list($resultado_totales)=select_format($rs_inter->fields);

list($total_posi,$total_nega,$total_neutro,$total_marc,$total_bloq,$total_feedback,$total_posim,$total_negam,$total_neutrom,$total_posia,$total_negaa,$total_neutroa)=explode("|", $resultado_totales);

?>
<!-- ///////////////////////////////// -->

<div id="aviso_nuevos" style="width:300px; padding:20px 30px; background:#333; color:#FFF; text-align:center; position:absolute; top: 30px; cursor:pointer; left:38%; display:none;" class="redondeado sombra" onclick="window.location.reload();">
<?=_BDM_NEWRESULT?>
</div>

<!-- ///////////////////////////////// -->

<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<div style="width:100%; height: 40px; background:url(images/feb-2014/fondo_listado.jpg); position:fixed; top:0; z-index:50000;">
	<div style="float:left;">
    	<h2 style="margin: 5px 0 0 30px; font-size: 18px; color:#FFF;">
		<?=_REPUTA_ESTRESUMEN?>
        </h2>
    </div>
	<?php if($_SESSION['bdm_user']['gru_id']=="2"){ ?><div style="float:right; width:480px;"><?php }else{ ?><div style="float:right; width:360px;"><?php } ?>
        <div class="columna"><img src="images/feb-2014/separador-listado.png" width="2" height="40" /></div><div class="columna menulistadoEstaSelec" align="center">
            <a href="javascript:;" onclick="redireccionMenuInternas('estadisticas_resumen','<?=$id_etiqueta_mostrar?>');" class="RobotoCondensed" target="_self"><span class="textoEsta"><?=_REPUTA_ESTRESUMEN?></span></a>         
        </div><div class="columna"><img src="images/feb-2014/separador-listado.png" width="2" height="40" /></div><div class="columna menulistadoEsta" align="center">
            <a href="javascript:;" onclick="redireccionMenuInternas('estadisticas_graficas','<?=$id_etiqueta_mostrar?>');" class="RobotoCondensed" target="_self"><span class="textoEsta"><?=_REPUTA_ESTESTADIS?></span></a>            
        </div><div class="columna"><img src="images/feb-2014/separador-listado.png" width="2" height="40" /></div><div class="columna menulistadoEsta" align="center">
            <a href="javascript:;" onclick="redireccionMenuInternas('estadisticas_graficas2','<?=$id_etiqueta_mostrar?>');" class="RobotoCondensed" target="_self"><span class="textoEsta"><?=_REPUTA_ESTANALIT?></span></a>          
        </div><?php if($_SESSION['bdm_user']['gru_id']=="2"){ ?><div class="columna"><img src="images/feb-2014/separador-listado.png" width="2" height="40" /></div><div class="columna menulistadoEsta" align="center">
            <a href="javascript:;" onclick="redireccionMenuInternas('estadisticas_publicidad','<?=$id_etiqueta_mostrar?>');" class="RobotoCondensed" target="_self"><span class="textoEsta">Publicidad</span></a>          
        </div><?php } ?>
    </div>
</div>

<!-- ///////////////////////////////// -->

<br /><br />

<!-- ///////////////////////////////// -->

<div class="ttulosEstadisticas">
	<?=$_SESSION['bdm_etiquetas'][$id_etiqueta_mostrar]['etiqueta']?>  
</div>

<!-- ///////////////////////////////// -->

<div id="informacion" style="display:block; float:right;  margin:-40px 10px 0 0;" align="right" onclick="$('#resumen').fadeIn();">
    <div style="cursor:pointer; width:150px;"><?=_BDM_FORMLISTALL0?> <img src="images/info.png" style="float:right; margin-left:10px;" alt="" /></div>
    <div id="resumen" style="display:none; position:absolute; right:10px; width:250px; text-align:left; color:#FFF; background:#666; padding:10px;" class="redondeado sombra">
    <?=$_SESSION['bdm_etiquetas'][$id_etiqueta_mostrar]['resumen']?>
    </div>
</div>

<!-- ///////////////////////////////// -->

<div class="cajaResumen" style="max-width:900px; margin:30px auto; padding:10px;">
	<table border="0" cellspacing="0" cellpadding="0" align="center">
      <tr>
        <td width="120" align="center"><strong><?=_REPUTA_ESTMEN?></strong></td>
        <td width="150" align="center"><span style="font-size:30px; font-weight:bold; color:#569898;"><?=formateo_numero($_SESSION['bdm_etiquetas'][$id_etiqueta_mostrar]['total'])?></span></td>
        <td width="1" align="center"><div style="border-left:1px solid #E0E0E0; height:48px;"></div></td>
        <td width="120" align="center"><strong><?=_REPUTA_ESTINT?></strong></td>
        <td width="150" align="center"><span style="font-size:30px; font-weight:bold; color:#569898;"><?=formateo_numero($total_posi+$total_nega+$total_neutro+$total_marc+$total_bloq)?></span></td>
        <td width="1" align="center"><div style="border-left:1px solid #E0E0E0; height:48px;"></div></td>
        <td width="120" align="center"><strong><?=_REPUTA_ESTFEE?></strong></td>
        <td width="150" align="center"><span style="font-size:30px; font-weight:bold; color:#569898;"><?=formateo_numero($total_feedback)?></span></td>
      </tr>
    </table>
</div>

<div style="margin: 0 0 0 30px;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="50%">
        <div class="ttulosEstadisticas">
			<?=_REPUTA_TIPOWEB?>  
        </div>        
        </td>
        <td width="40">&nbsp;</td>
        <td width="50%">
        <div class="ttulosEstadisticas">
			<?=_REPUTA_WEBSITE?>  
        </div>
        </td>
      </tr>
      <tr>
        <td><?php include("../graficas/graf_pun_cat_gen_general.php"); ?></td>
        <td>&nbsp;</td>
        <td><?php include("../graficas/graf_pie_web_gen_general.php"); ?></td>
      </tr>
    </table>
</div>

<div class="ttulosEstadisticas">
	<?=_REPUTA_ULTIMOS?>  
</div>    
<div class="cajaResumen" style="max-width:900px; margin:30px auto; padding:10px;">
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <th align="center" style="font-size:18px;"><?=_REPUTA_RESUM1?></th>
        <th align="center" style="font-size:18px;"><div style="border-left:1px solid #E0E0E0; margin:0 5px; height:38px;"></div></th>
        <th align="center" style="font-size:18px;"><?=_REPUTA_RESUM2?></th>
        <th align="center" style="font-size:18px;"><div style="border-left:1px solid #E0E0E0; margin:0 5px; height:38px;"></div></th>
        <th align="center" style="font-size:18px;"><?=_REPUTA_RESUM3?></th>
        <th align="center" style="font-size:18px;"><div style="border-left:1px solid #E0E0E0; margin:0 5px; height:38px;"></div></th>
        <th align="center" style="font-size:18px;"><?=_REPUTA_RESUM4?></th>
      </tr>
      <tr>
        <td colspan="7" align="center" valign="top"><div style="border-bottom:1px solid #E0E0E0; margin:10px 0;"></div></td>
      </tr>
<?php
$sql="SELECT 
		a.id,a.id_rss,a.cargue,a.marcada,a.positivo,
		a.negativo,a.bloqueada,a.intervenido,a.detalle_intervenido,a.id_categoria,
		a.titulo,a.contenido,a.link,a.semana,a.neutro,a.id_source,a.fecha_registro,
		CASE WHEN a.link RLIKE '^[http://|https://]' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(link, '/', 3), '/', -1) ELSE SUBSTRING_INDEX(link, '/', 1) END AS sitios
	  FROM ic_rss_coincidencias a FORCE INDEX (id_etiqueta)
	  WHERE
		a.id_etiqueta=".$id_etiqueta_mostrar." 		
	  ORDER BY a.fecha_registro DESC  LIMIT 0,5";
$result_coincidencias_finales=$db->Execute($sql);

$cont=1;

while(!$result_coincidencias_finales->EOF){
	
	list($id,$id_rss,$cargue,$marcada,$positivo,$negativo,$bloqueada,$intervenido,$detalle_intervenido,
	     $cat_id,$titulo,$contenido,$link,$semana,$neutro,$id_source,$fecha_registro,$nombre_sitio)=select_format($result_coincidencias_finales->fields);
		
	if($fecha_registro!="" && $fecha_registro!="0"){
		$fecha_registro = date('d/m/Y', $fecha_registro);
	}
	
?>
      
      <tr>
        <td align="center" valign="top"><div class="marcaRanking"><?=$cont?></div></td>
        <td align="center" valign="top">&nbsp;</td>
        <td align="center" valign="top"><div><?=$fecha_registro?></div></td>
        <td valign="top">&nbsp;</td>
        <td valign="top">
        <div class="titulo"><?=$titulo?></div>
        <div><?=substr($contenido, 0, 200)?>...</div>
        </td>
        <td align="center" valign="top">&nbsp;</td>
        <td align="center" valign="top"><div><?=$nombre_sitio?></div></td>
      </tr>
<?php
	$cont++;
	$result_coincidencias_finales->MoveNext();
}
?>
    </table>
</div> 

<div class="ttulosEstadisticas">
	<?=_REPUTA_RELEVANTES?>  
</div>   
<div class="cajaResumen" style="max-width:900px; margin:30px auto; padding:10px;">
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <th align="center" style="font-size:18px;"><?=_REPUTA_RESUM1?></th>
        <th align="center" style="font-size:18px;"><div style="border-left:1px solid #E0E0E0; margin:0 5px; height:38px;"></div></th>
        <th align="center" style="font-size:18px;"><?=_REPUTA_RESUM2?></th>
        <th align="center" style="font-size:18px;"><div style="border-left:1px solid #E0E0E0; margin:0 5px; height:38px;"></div></th>
        <th align="center" style="font-size:18px;"><?=_REPUTA_RESUM3?></th>
        <th align="center" style="font-size:18px;"><div style="border-left:1px solid #E0E0E0; margin:0 5px; height:38px;"></div></th>
        <th align="center" style="font-size:18px;"><?=_REPUTA_RESUM4?></th>
      </tr>
      <tr>
        <td colspan="7" align="center" valign="top"><div style="border-bottom:1px solid #E0E0E0; margin:10px 0;"></div></td>
      </tr>
<?php
$sql="SELECT 
		a.id,a.id_rss,a.cargue,a.marcada,a.positivo,
		a.negativo,a.bloqueada,a.intervenido,a.detalle_intervenido,a.id_categoria,
		a.titulo,a.contenido,a.link,a.semana,a.neutro,a.id_source,a.fecha_registro,
		CASE WHEN a.link RLIKE '^[http://|https://]' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(link, '/', 3), '/', -1) ELSE SUBSTRING_INDEX(link, '/', 1) END AS sitios
	  FROM ic_rss_coincidencias a  FORCE INDEX (indx_marcada)
	  WHERE
		a.id_etiqueta=".$id_etiqueta_mostrar." 		
	  ORDER BY a.marcada DESC LIMIT 0,5";
$result_coincidencias_finales=$db->Execute($sql);

$cont=1;

while(!$result_coincidencias_finales->EOF){
	
	list($id,$id_rss,$cargue,$marcada,$positivo,$negativo,$bloqueada,$intervenido,$detalle_intervenido,
	     $cat_id,$titulo,$contenido,$link,$semana,$neutro,$id_source,$fecha_registro,$nombre_sitio)=select_format($result_coincidencias_finales->fields);
	
	if($fecha_registro!="" && $fecha_registro!="0"){
		$fecha_registro = date('d/m/Y', $fecha_registro);
	}
?>
      
      <tr>
        <td align="center" valign="top"><div class="marcaRanking"><?=$cont?></div></td>
        <td align="center" valign="top">&nbsp;</td>
        <td align="center" valign="top"><div><?=$fecha_registro?></div></td>
        <td valign="top">&nbsp;</td>
        <td valign="top">
        <div class="titulo"><?=$titulo?></div>
        <div><?=substr($contenido, 0, 200)?>...</div>
        </td>
        <td align="center" valign="top">&nbsp;</td>
        <td align="center" valign="top"><div><?=$nombre_sitio?></div></td>
      </tr>
<?php
	$cont++;
	$result_coincidencias_finales->MoveNext();
}
?>
    </table>
</div> 

<?php
	$marcaposm = "";
	$marcanegm = "";
	$marcaneum = "";
		
	if($total_posim > $total_negam && $total_posim > $total_neutrom){
		$marcaposm = '<div style="width:15px; height:15px; background: #5FA69D; margin:0 auto;"></div>';
	}
	if($total_negam > $total_posim && $total_negam > $total_neutrom){
		$marcanegm = '<div style="width:15px; height:15px; background: #5FA69D; margin:0 auto;"></div>';
	}
	if($total_neutrom > $total_negam && $total_neutrom > $total_posim){
		$marcaneum = '<div style="width:15px; height:15px; background: #5FA69D; margin:0 auto;"></div>';
	}
	
	/////////////////////////////////////////////////////////////////////
	
	$marcaposa = "";
	$marcanega = "";
	$marcaneua = "";
	
	if($total_posia > $total_negaa && $total_posia > $total_neutroa){
		$marcaposa = '<div style="width:15px; height:15px; background: #5FA69D; margin:0 auto;"></div>';
	}
	if($total_negaa > $total_posia && $total_negaa > $total_neutroa){
		$marcanega = '<div style="width:15px; height:15px; background: #5FA69D; margin:0 auto;"></div>';
	}
	if($total_neutroa > $total_negaa && $total_neutroa > $total_posia){
		$marcaneua = '<div style="width:15px; height:15px; background: #5FA69D; margin:0 auto;"></div>';
	}
?>
<div class="ttulosEstadisticas">
	<?=_REPUTA_SENTIM?>  
</div>   

<div style="max-width:900px; padding:10px; margin:0 auto;">
    <div class="columna cajaResumen" style="max-width:350px; margin:30px auto; padding:0 30px;">
        <div class="tituSenti">
            <?=_REPUTA_VALCOM_MANUAL?>  
        </div>  
        <br>
        <table border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td><img src="images/feb-2014/senti-pos.fw.png" width="77" height="74" /></td>
            <td width="30">&nbsp;</td>
            <td><img src="images/feb-2014/senti-neu.fw.png" width="77" height="74" /></td>
            <td width="30">&nbsp;</td>
            <td><img src="images/feb-2014/senti-neg.fw.png" width="77" height="74" /></td>
          </tr>
          <tr>
            <td>
            <div style="width:15px; height:15px; border:2px solid #979797; padding:5px; margin:15px auto;">
            	<?=$marcaposm?>
            </div>
            </td>
            <td>&nbsp;</td>
            <td>
            <div style="width:15px; height:15px; border:2px solid #979797; padding:5px; margin:15px auto;">
            	<?=$marcaneum?>
            </div>
            </td>
            <td>&nbsp;</td>
            <td>
            <div style="width:15px; height:15px; border:2px solid #979797; padding:5px; margin:15px auto;">
            	<?=$marcanegm?>
            </div>
            </td>
          </tr>
        </table>
    </div> 
    
    <div class="columna cajaResumen" style="max-width:350px; margin:30px auto; padding:0 30px; float:right">
        <div class="tituSenti">
            <?=_REPUTA_VALCOM?>  
        </div>  
        <br>
        <table border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td><img src="images/feb-2014/senti-pos.fw.png" width="77" height="74" /></td>
            <td width="30">&nbsp;</td>
            <td><img src="images/feb-2014/senti-neu.fw.png" width="77" height="74" /></td>
            <td width="30">&nbsp;</td>
            <td><img src="images/feb-2014/senti-neg.fw.png" width="77" height="74" /></td>
          </tr>
          <tr>
            <td>
            <div style="width:15px; height:15px; border:2px solid #979797; padding:5px; margin:15px auto;">
            	<?=$marcaposa?>
            </div>
            </td>
            <td>&nbsp;</td>
            <td>
            <div style="width:15px; height:15px;; border:2px solid #979797; padding:5px; margin:15px auto;">
            	<?=$marcaneua?>
            </div>
            </td>
            <td>&nbsp;</td>
            <td>
            <div style="width:15px; height:15px; border:2px solid #979797; padding:5px; margin:15px auto;">
            	<?=$marcanega?>
            </div>
            </td>
          </tr>
        </table>
    </div>
</div> 