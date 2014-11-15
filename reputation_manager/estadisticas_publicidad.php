<?php
include "../cabecera.php";
$variables_metodo = variables_metodo("etiqueta,anunciante");
$etiqueta= 	$variables_metodo[0];
$anunciante= 	$variables_metodo[1];

?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<div style="width:100%; height: 40px; background:url(images/feb-2014/fondo_listado.jpg); position:fixed; top:0; z-index:50000;">
	<div style="float:left;">
    	<h2 style="margin: 5px 0 0 30px; font-size: 18px; color:#FFF;">
		Publicidad
        </h2>
    </div>
	<div style="float:right; width:360px;">
        <div class="columna"><img src="images/feb-2014/separador-listado.png" width="2" height="40" /></div><div class="columna menulistadoEsta" align="center">
            <a href="reputation_manager/estadisticas_resumen.php?etiqueta=<?=$id_etiqueta_mostrar?>" class="RobotoCondensed" target="_self"><span class="textoEsta"><?=_REPUTA_ESTRESUMEN?></span></a>         
        </div><div class="columna"><img src="images/feb-2014/separador-listado.png" width="2" height="40" /></div><div class="columna menulistadoEsta" align="center">
            <a href="reputation_manager/estadisticas_graficas.php?etiqueta=<?=$id_etiqueta_mostrar?>" class="RobotoCondensed" target="_self"><span class="textoEsta"><?=_REPUTA_ESTESTADIS?></span></a>            
        </div><div class="columna"><img src="images/feb-2014/separador-listado.png" width="2" height="40" /></div><div class="columna menulistadoEsta" align="center">
            <a href="reputation_manager/estadisticas_reportes.php?etiqueta=<?=$id_etiqueta_mostrar?>" class="RobotoCondensed" target="_self"><span class="textoEsta"><?=_REPUTA_ESTREPORTES?></span></a>          
        </div>
    </div>
</div>

<br /><br /><br />
<div align="center">
<form action="" method="post" name="anuncianteform" target="_self">
<select name="anunciante" id="anunciante" onchange="document.anuncianteform.submit();">
 <?php cargar_lista("ic_publicidad","distinct anunciante, anunciante","anunciante","1",$anunciante,"",$db); ?>
</select>
</form>
</div>

<div class="ttulosEstadisticas">
	<?=_RESULTADO_BUSQUEDA?>  
</div>

<div style="max-width:1100px; max-height:450px; margin:30px auto;">
	<!-- //////////////////////////////////////////////////////////////////////////////// -->
<?php
$sql="SELECT fecha, count( * ) as total FROM ic_publicidad WHERE anunciante = '".$anunciante."' GROUP BY fecha";
$result_eti=$db->Execute($sql);

$coincidencias_grafica = "";

while(!$result_eti->EOF){
	list($fecha, $total)=$result_eti->fields;
	
	$coincidencias_grafica = "['".$fecha."',     ".$total."]".$coincidencias_grafica;
	
	$result_eti->MoveNext();
	
	if(!$result_eti->EOF){
		$coincidencias_grafica = ",".$coincidencias_grafica;	
	}
}
?>
	<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart_3);
      function drawChart_3() {
        var data = google.visualization.arrayToDataTable([
          ['Fecha', 'Impresiones'],
          <?=$coincidencias_grafica?>
        ]);
    
        var options = {
          legend: 'none',
        };
    
        var chart = new google.visualization.AreaChart(document.getElementById('general'));
        chart.draw(data, options);
      }
      
      
    
    </script>
    <div id="general" style="width: 1100px; height: 400px;"></div>
    <!-- //////////////////////////////////////////////////////////////////////////////// -->
</div>

<div style="margin: 0 0 0 30px;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="50%">
        <div class="ttulosEstadisticas">
			Medios  
        </div>        
        </td>
        <td width="40">&nbsp;</td>
        <td width="50%">
        <div class="ttulosEstadisticas">
			Productos
        </div>
        </td>
      </tr>
      <tr>
        <td>
<?php
$sql="SELECT medio, count( * ) as total FROM ic_publicidad WHERE anunciante = '".$anunciante."' GROUP BY medio";
$result2=$db->Execute($sql);

$categorias_grafica = "";


while(!$result2->EOF){
	list($medios,$total)=$result2->fields;
	
	if($total==""){ $total=0; }
	
	$categorias_grafica .= "['".$medios."',     ".$total."]";
	
	$result2->MoveNext();
	
	if(!$result2->EOF){
		$categorias_grafica .= ",";	
	}
}
?>

<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart_1);
  function drawChart_1() {
	var data = google.visualization.arrayToDataTable([
	  ['Medios', 'Impresiones'],
	  <?=$categorias_grafica?>
	]);

	var options = {
	  pieHole: 0.4,
	};

	var chart = new google.visualization.PieChart(document.getElementById('graf_medios'));
	chart.draw(data, options);
  }
</script>
 
<div id="graf_medios" style="width:100%; height: 300px;"></div>
        </td>
        <td>&nbsp;</td>
        <td>
<?php
$sql="SELECT producto, count( * ) as total FROM ic_publicidad WHERE anunciante = '".$anunciante."' GROUP BY producto";
$result3=$db->Execute($sql);

$categorias_grafica = "";


while(!$result3->EOF){
	list($producto,$total)=$result3->fields;
	
	if($total==""){ $total=0; }
	
	$categorias_grafica .= "['".$producto."',     ".$total."]";
	
	$result3->MoveNext();
	
	if(!$result3->EOF){
		$categorias_grafica .= ",";	
	}
}
?>

<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart_1);
  function drawChart_1() {
	var data = google.visualization.arrayToDataTable([
	  ['Productos', 'Impresiones'],
	  <?=$categorias_grafica?>
	]);

	var options = {
	  pieHole: 0.4,
	};

	var chart = new google.visualization.PieChart(document.getElementById('graf_producto'));
	chart.draw(data, options);
  }
</script>
 
<div id="graf_producto" style="width:100%; height: 300px;"></div>
        </td>
      </tr>
    </table>
</div>


<div class="ttulosEstadisticas">
	Últimos
</div>   
<div class="cajaResumen" style="max-width:900px; margin:30px auto; padding:10px;">
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
<?php
$sql="SELECT pais, fecha, anunciante, plataforma, medio, categoria, formato, producto, industria, url   
      FROM ic_publicidad
	  WHERE
		anunciante = '".$anunciante."'	
	  ORDER BY fecha DESC LIMIT 0,5";
$result_coincidencias_finales=$db->Execute($sql);

$cont=1;

while(!$result_coincidencias_finales->EOF){
	
	list($pais,$fecha,$anunciante,$plataforma,$medio,$categoria,$formato,$producto,$industria,$url)=select_format($result_coincidencias_finales->fields);
	
	$url = explode(",", $url);
?>
      
      <tr>
        <td align="center" valign="top"><div class="marcaRanking"><?=$cont?></div></td>
        <td align="center" valign="top">&nbsp;</td>
        <td align="center" valign="top"><div><?=$fecha?></div></td>
        <td valign="top">&nbsp;</td>
        <td valign="top">
        <a href="<?=$url[1]?>" target="_blank"><img src="<?=$url[0]?>" width="100" style="float:left; margin:0 10px 0 0;" /></a>        
        PRODUCTO: <?=$producto?>
        - PLATAFORMA: <?=$plataforma?>
        - MEDIO: <?=$medio?>
        - CATEGORIA: <?=$categoria?>
        - FORMATO: <?=$formato?>        
        - INDUSTRIA: <?=$industria?>
        </td>
      </tr>
<?php
	$cont++;
	$result_coincidencias_finales->MoveNext();
}
?>
    </table>
</div> 
