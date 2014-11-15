<?php
include "../cabecera.php";
$variables_metodo = variables_metodo("etiqueta");
$id_etiqueta_mostrar= 	$variables_metodo[0];

?>
<div id="aviso_nuevos" style="width:300px; padding:20px 30px; background:#333; color:#FFF; text-align:center; position:absolute; top: 30px; cursor:pointer; left:38%; display:none;" class="redondeado sombra" onclick="window.location.reload();">
<?=_BDM_NEWRESULT?>
</div>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<div style="width:100%; height: 40px; background:url(images/feb-2014/fondo_listado.jpg); position:fixed; top:0; z-index:50000;">
	<div style="float:left;">
    	<h2 style="margin: 5px 0 0 30px; font-size: 18px; color:#FFF;">
		<?=_REPUTA_ESTANALIT?>
        </h2>
    </div>
	<div style="float:right; width:360px;">
        <div class="columna"><img src="images/feb-2014/separador-listado.png" width="2" height="40" /></div><div class="columna menulistadoEsta" align="center">
            <a href="javascript:;" onclick="redireccionMenuInternas('estadisticas_resumen','<?=$id_etiqueta_mostrar?>');" class="RobotoCondensed" target="_self"><span class="textoEsta"><?=_REPUTA_ESTRESUMEN?></span></a>         
        </div><div class="columna"><img src="images/feb-2014/separador-listado.png" width="2" height="40" /></div><div class="columna menulistadoEsta" align="center">
            <a href="javascript:;" onclick="redireccionMenuInternas('estadisticas_graficas','<?=$id_etiqueta_mostrar?>');" class="RobotoCondensed" target="_self"><span class="textoEsta"><?=_REPUTA_ESTESTADIS?></span></a>            
        </div><div class="columna"><img src="images/feb-2014/separador-listado.png" width="2" height="40" /></div><div class="columna menulistadoEstaSelec" align="center">
            <a href="javascript:;" onclick="redireccionMenuInternas('estadisticas_graficas2','<?=$id_etiqueta_mostrar?>');" class="RobotoCondensed" target="_self"><span class="textoEsta"><?=_REPUTA_ESTANALIT?></span></a>          
        </div>
    </div>
</div>

<br /><br />

<div class="ttulosEstadisticas">
	<?=_BDM_GRAPHADI?>  
</div>


<div style="max-width:900px; max-height:400px; margin:30px auto;" align="center">
<?php
	$sql="SELECT  prepararPaises ('".$id_etiqueta_mostrar."','5000') AS ` prepararPaises` ;";
	$rs_inter=$db->Execute($sql);
	
	$sql="SELECT pais,codigo,COUNT(*) FROM tmp_paisesmapa WHERE etiqueta = ".$id_etiqueta_mostrar." GROUP BY pais,codigo ";
	$result_eti=$db->Execute($sql);
	
	$coordenadas = "";
	
	while(!$result_eti->EOF){
		list($pais,$cod_google,$total)=$result_eti->fields;
				
		$coordenadas .= "['".str_replace("country","",$cod_google)."', ".$total."],";	
		
		$result_eti->MoveNext();
	}
	
	if($coordenadas==""){
		$coordenadas="['AR', 0]";
	}
?>
<script type='text/javascript'>
   google.load('visualization', '1', {'packages': ['geomap']});
   google.setOnLoadCallback(drawMap);

    function drawMap() {
      var data = google.visualization.arrayToDataTable([
        ['Country', 'Comentarios'],
        <?=$coordenadas?>
      ]);

      var options = {};
      options['dataMode'] = 'regions';
	  options['width'] = '700px';
	  
      var container = document.getElementById('ubicaciones_comentarios');
      var geomap = new google.visualization.GeoMap(container);
      geomap.draw(data, options);
  };
</script>
    <div id="ubicaciones_comentarios" style=""></div>
    <?=_BDM_GRAPHADI1?>
</div>


<div style="margin: 0 0 0 30px;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="50%">
        <div class="ttulosEstadisticas">
			<?=_BDM_GRAPHADTIP?>  
        </div>        
        </td>
        <td width="40">&nbsp;</td>
        <td width="50%">
        <div class="ttulosEstadisticas">
			<?=_BDM_GRAPHADNUV?>  
        </div>
        </td>
      </tr>
      <tr>
        <td>
<?php
$sql="SELECT tipo_mencion,count(*) FROM ic_rss_coincidencias FORCE INDEX (indx_mencion) WHERE id_etiqueta=".$id_etiqueta_mostrar." GROUP BY tipo_mencion ORDER BY fecha_registro DESC";
$result2=$db->Execute($sql);

$categorias_grafica = "";

while(!$result2->EOF){
	list($tipo_mencion,$total)=$result2->fields;
	
	if($tipo_mencion!=""){
		if($total==""){ $total=0; }
		
		$categorias_grafica .= "['".$tipo_mencion."',     ".$total."]";	
	}
	
	$result2->MoveNext();
	
	if($tipo_mencion!=""){
		if(!$result2->EOF){
			$categorias_grafica .= ",";	
		}
	}
}
?>
<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart_1);
  function drawChart_1() {
	var data = google.visualization.arrayToDataTable([
	  ['<?=_REPUTA_TIPOWEB?>', '<?=_REPUTA_COINCI?>'],
	  <?=$categorias_grafica?>
	]);

	var options = {
	  pieHole: 0.4,
	};

	var chart = new google.visualization.PieChart(document.getElementById('graf_fuentes'));
	chart.draw(data, options);
  }
</script>
 
<div id="graf_fuentes" style="width:100%; height: 250px;"></div>
        </td>
        <td>&nbsp;</td>
        <td>
<script type="text/javascript" src="js/word-cloud.js"></script>
<?php

$sql="SELECT contenido FROM ic_rss_coincidencias WHERE id_etiqueta='".$id_etiqueta_mostrar."' ORDER BY fecha_registro DESC LIMIT 0,1000";
$rs_cargue=$db->Execute($sql);
  
$contenidos = "";

while(!$rs_cargue->EOF){
	list($contenido)=select_format($rs_cargue->fields);	
	$contenidos .= " ".$contenido;		   
	$rs_cargue->MoveNext();
}

$variables = array_count_values(preg_split('/[\s\,\.\:\"\-\!\?\(\)\]\[\{\}\_\&]/', strtolower($contenidos)));
arsort ($variables);
reset($variables);

$conectores = array("de","del","no","te","mi","se","me","lo","las","tu","yo","mas","más","es","con","a","para","por","el","la","los","nos","y","o",
                    "u","un","una","ya","e","ni","que","en","si","http","https","rt","//t","|","1","2","3","4","5","6","7","8","9","0","hoy","mais",
					"hubo","of","the","es","...","su","este","le","hizo","todos","todo","fue","ha","sin","get","al","tiene","on","ans","at","or","to",
					"an","as","that","it","como","va","ir","hay","so","in","was","when","them","you","be","how","for","if","and","we","our",
					"but","they","who","i","this","your","by","from","are","all","my","yours","desde","das","os","esta","sua","um","uma","ver",
					"pero","fin","q","porque","que","...","tengo","cuando","estoy","sus","tus","antes","ad","of","in","is","your","with",
					"mas","di","in","and","muy","com","as","d","y","&","us");
$importantes = array();

$grafica = "";
$count = 0 ;

foreach($variables as $key=>$val){	
	if(array_search($key,$conectores)===false){
		if(trim($key)!=""){
			$total=1;
			if($val<=10){
				$total=$val;
			}elseif($val>10 && $val<=100){
				$total=round($val/10);
			}elseif($val>100){
				$total=round($val/100);
				
				if($total>10){
					$total = 9;
				}
			}
			
			$grafica .= '{text: "'.$key.'", weight: '.$total.'},';
			$count++;
		}
	}
	
	if($count>60){
		break;	
	}
}

$grafica = substr($grafica, 0, -1);
?>
<style>

div#wordcloud {
  font-family: "Helvetica", "Arial", sans-serif;
  color: #09f;
  overflow: hidden;
  position: relative;
}
div#wordcloud a {
  color: inherit;
  text-decoration: none;
}
div#wordcloud a:hover {
  color: #0df;
}
div#wordcloud a:hover {
  color: #0cf;
}
div#wordcloud span {
  padding: 0;
}
div#wordcloud span.w10 {
  font-size: 54px;
  color: #0cf;
}
div#wordcloud span.w9 {
  font-size: 50px;
  color: #0cf;
}
div#wordcloud span.w8 {
  font-size: 44px;
  color: #0cf;
}
div#wordcloud span.w7 {
  font-size: 40px;
  color: #39d;
}
div#wordcloud span.w6 {
  font-size: 34px;
  color: #90c5f0;
}
div#wordcloud span.w5 {
  font-size: 30px;
  color: #90a0dd;
}
div#wordcloud span.w4 {
  font-size: 24px;
  color: #90c5f0;
}
div#wordcloud span.w3 {
  font-size: 20px;
  color: #a0ddff;
}
div#wordcloud span.w2 {
  font-size: 14px;
  color: #99ccee;
}
div#wordcloud span.w1 {
  font-size: 10px;
  color: #aab5f0;
}

</style>
<script type="text/javascript">
      var word_list = new Array(
        <?=$grafica?>
      );
      $(document).ready(function() {
        $("#wordcloud").jQCloud(word_list);
      });
    </script>
<div id="wordcloud" style="width:100%; height: 300px;"></div>
        </td>
      </tr>
    </table>
</div>
