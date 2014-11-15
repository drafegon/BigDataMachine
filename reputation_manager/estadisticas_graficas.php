<?php
include "../cabecera.php";
$variables_metodo = variables_metodo("etiqueta,versus,fecha_desde,fecha_hasta");
$id_etiqueta_mostrar= 	$variables_metodo[0];
$versus= 				$variables_metodo[1];
$fecha_desde= 			(($variables_metodo[2]!="" && $variables_metodo[2]!=_FECHA_DESDE)?date('Y-m-d',strtotime($variables_metodo[2])):"");
$fecha_hasta= 			(($variables_metodo[3]!="" && $variables_metodo[3]!=_FECHA_HASTA)?date('Y-m-d',strtotime($variables_metodo[3])):"");

?>
<div id="aviso_nuevos" style="width:300px; padding:20px 30px; background:#333; color:#FFF; text-align:center; position:absolute; top: 30px; cursor:pointer; left:38%; display:none;" class="redondeado sombra" onclick="window.location.reload();">
<?=_BDM_NEWRESULT?>
</div>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<div style="width:100%; height: 40px; background:url(images/feb-2014/fondo_listado.jpg); position:fixed; top:0; z-index:50000;">
	<div style="float:left;">
    	<h2 style="margin: 5px 0 0 30px; font-size: 18px; color:#FFF;">
		<?=_REPUTA_ESTESTADIS?>
        </h2>
    </div>
	<div style="float:right; width:360px;">
        <div class="columna"><img src="images/feb-2014/separador-listado.png" width="2" height="40" /></div><div class="columna menulistadoEsta" align="center">
            <a href="javascript:;" onclick="redireccionMenuInternas('estadisticas_resumen','<?=$id_etiqueta_mostrar?>');" class="RobotoCondensed" target="_self"><span class="textoEsta"><?=_REPUTA_ESTRESUMEN?></span></a>         
        </div><div class="columna"><img src="images/feb-2014/separador-listado.png" width="2" height="40" /></div><div class="columna menulistadoEstaSelec" align="center">
            <a href="javascript:;" onclick="redireccionMenuInternas('estadisticas_graficas','<?=$id_etiqueta_mostrar?>');" class="RobotoCondensed" target="_self"><span class="textoEsta"><?=_REPUTA_ESTESTADIS?></span></a>            
        </div><div class="columna"><img src="images/feb-2014/separador-listado.png" width="2" height="40" /></div><div class="columna menulistadoEsta" align="center">
            <a href="javascript:;" onclick="redireccionMenuInternas('estadisticas_graficas2','<?=$id_etiqueta_mostrar?>');" class="RobotoCondensed" target="_self"><span class="textoEsta"><?=_REPUTA_ESTANALIT?></span></a>          
        </div>
    </div>
</div>

<br /><br />

<div class="ttulosEstadisticas">
	<?=_RESULTADO_BUSQUEDA?>  
</div>

<script>
$(function() {
	$( "#fecha_desde" ).datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd-mm-yy",
		onSelect: function(dateText, inst) { 
			document.filtro_fechas.submit();
		}
	});
	$( "#fecha_hasta" ).datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd-mm-yy",
		onSelect: function(dateText, inst) { 
			document.filtro_fechas.submit();
		}
	});
});
</script>

<div style="display:block; float:right;  margin:-50px 10px 0 0;" align="right">
	<form action="" name="filtro_fechas" method="post" target="_self">
        <div style="background:url(images/fechas.fw.png); width:120px; height:39px;" class="columna">
        <input name="fecha_desde" id="fecha_desde" type="text" value="<?=(($variables_metodo[2]=="")?_FECHA_DESDE:$variables_metodo[2])?>" onclick="$(this).val('');" style="width:68px; float:left; margin:8px 0 0 7px; background:none; border:none;" />
        </div>
        <div style="background:url(images/fechas.fw.png); width:120px; height:39px;" class="columna">
        <input name="fecha_hasta" id="fecha_hasta" type="text" value="<?=(($variables_metodo[3]=="")?_FECHA_HASTA:$variables_metodo[3])?>" onclick="$(this).val('');" style="width:68px;; float:left; margin:8px 0 0 7px; background:none; border:none;"/>
        </div>
	</form>
</div>

<table border="0" cellspacing="0" cellpadding="0" style="margin:0 auto;">
  <tr>
    <td>
<script>

function getCheckboxValues() {
  var values = [];
  var etiproy = document.getElementsByName("etipro");

  for (var i=0; i<etiproy.length; i++) {
    if (etiproy[i].checked) {
      values.push(etiproy[i].value);
    }
  }
  
  window.location.href="reputation_manager/estadisticas_graficas.php?etiqueta=<?=$id_etiqueta_mostrar?>&versus="+values.join(',');
}

</script>
	<form name="versus" action="" method="post">
    <h3><?=_REPUTA_MSG4?> Vs</h3><br />
    <label for="etipro<?=$id_etiqueta_mostrar?>"><input type="checkbox" disabled="disabled" checked="checked" value="<?=$id_etiqueta_mostrar?>" /> <?=$_SESSION['bdm_etiquetas'][$id_etiqueta_mostrar]['etiqueta']?></label><br>
<?php
$sql="SELECT id, etiqueta FROM ic_etiquetas 
      WHERE id_proyecto = '".$_SESSION['bdm_etiquetas'][$id_etiqueta_mostrar]['id_proyecto']."'
	        AND id <> '".$id_etiqueta_mostrar."' AND usuario_cliente='".$_SESSION['bdm_user']['gru_id']."'
      ORDER BY fecha_creacion DESC";
$result_etiproy=$db->Execute($sql);

while(!$result_etiproy->EOF){
	list($id_etproy, $etiqueta_etproy)=$result_etiproy->fields;
	
	echo '<label for="etipro'.$id_etproy.'">
	      <input type="checkbox" '.((strstr($versus, $id_etproy))?'checked="checked"':"").' value="'.$id_etproy.'" id="etipro'.$id_etproy.'" name="etipro" onclick="getCheckboxValues();"/> 
		  '.$etiqueta_etproy.'
		  </label>
		  <br>';
	
	$result_etiproy->MoveNext();
}
?>
	</form>
    </td>
    <td valign="top">
        <div style="max-width:900px; max-height:400px; margin:30px auto;">
            <?php include("../graficas/graf_pun_eti_gen_seguimiento.php"); ?>
        </div>
    </td>
  </tr>
</table>


<div style="margin: 0 0 0 30px;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="50%">
        <div class="ttulosEstadisticas">
			<?=_REPUTA_VALCOM_MANUAL?>  
        </div>        
        </td>
        <td width="40">&nbsp;</td>
        <td width="50%">
        <div class="ttulosEstadisticas">
			<?=_REPUTA_VALCOM?>  
        </div>
        </td>
      </tr>
      <tr>
        <td><?php include("../graficas/graf_bar_eti_pnn_seguimiento_manual.php"); ?></td>
        <td>&nbsp;</td>
        <td><?php include("../graficas/graf_bar_eti_pnn_seguimiento_automatica.php"); ?></td>
      </tr>
    </table>
</div>

<div class="ttulosEstadisticas">
	<?=_REPUTA_CANT_ETI?>  
</div>
<div style="max-width:960px; max-height:650px; margin:30px auto;">
	<?php include("../graficas/graf_bar_gen_seguimiento.php"); ?>
</div>
