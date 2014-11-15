<?php
include "../cabecera.php";
$variables_metodo = variables_metodo("etiqueta,todas,fecha_desde,fecha_hasta,regionales");
$id_etiqueta_mostrar= 	$variables_metodo[0];
$todas= 				$variables_metodo[1];
$fecha_desde= 			(($variables_metodo[2]!="" && $variables_metodo[2]!=_FECHA_DESDE)?date('Y-m-d',strtotime($variables_metodo[2])):"");
$fecha_hasta= 			(($variables_metodo[3]!="" && $variables_metodo[3]!=_FECHA_HASTA)?date('Y-m-d',strtotime($variables_metodo[3])):"");
$regionales= 			$variables_metodo[4];

///////////////////////////////////

$sql="SELECT id,fecha_cargue,positivo,negativo,neutro,titulo,contenido,link,fecha_registro,sitios,datos FROM (
SELECT 
	a.id,a.fecha_cargue,a.positivo,a.negativo,a.neutro,a.titulo,a.contenido,a.link,a.fecha_registro,
	CASE WHEN a.link RLIKE '^[http://|https://]' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(link, '/', 3), '/', -1) ELSE SUBSTRING_INDEX(link, '/', 1) END AS sitios,
	(SELECT CONCAT('|',b.tema,'||',b.paises,'||',b.origen_ciudad,'|') FROM ic_rss b 
	 WHERE b.nombre REGEXP (		
			CASE WHEN (sitios) LIKE 'www.' THEN SUBSTRING_INDEX(sitios,'www.',1) ELSE sitios END
		) LIMIT 0,1 ) AS datos
FROM 
	ic_rss_coincidencias a
WHERE
	a.id_etiqueta=".$id_etiqueta_mostrar." 
ORDER BY a.fecha_registro DESC) tabla  ORDER BY fecha_registro DESC ";
$result_coincidencias_finales	=$db->Execute($sql);

$datos_fechas_regionales = array();
$datos_fechas_otros = array();

$fechas_todos = array();

$web_otros = array();
$web_regionales = array();

$tematicas = array();
$mapas = array();

$palabras_claves = "";

$positivos = 0;
$negativos = 0;
$neutros = 0;

while(!$result_coincidencias_finales->EOF){	
	list($id,$fecha_cargue,$positivo,$negativo,$neutro,$titulo,$contenido,$link,$fecha_registro,$sitios,$datos)=select_format($result_coincidencias_finales->fields);
	
	$palabras_claves .= $contenido;
	array_push($fechas_todos, $fecha_cargue);
	
	if($datos!=""){
		if(array_key_exists($fecha_cargue, $datos_fechas_regionales)){
			$datos_fechas_regionales["".$fecha_cargue.""] = $datos_fechas_regionales["".$fecha_cargue.""] + 1;
		}else{
			$datos_fechas_regionales["".$fecha_cargue.""]=1;
		}
		
		if(array_key_exists($sitios, $web_regionales)){
			$web_regionales["".$sitios.""] = $web_regionales["".$sitios.""] + 1;
		}else{
			$web_regionales["".$sitios.""]=1;
		}	
		
		$datos = explode("||",$datos);
		$tema_ = str_replace("*","",str_replace("|","",$datos[0]));
		$pais_ = str_replace("|","",$datos[1]);
		$pais_ = explode("**",$pais_);
		$pais_ = str_replace("*","",$pais_[0]);
		$ciudad_ = str_replace("*","",str_replace("|","",$datos[2]));	
		
		if(array_key_exists($tema_, $tematicas)){
			$tematicas["".$tema_.""] = $tematicas["".$tema_.""] + 1;
		}else{
			$tematicas["".$tema_.""]=1;
		}	
		
		if(array_key_exists("".$ciudad_.", ".$pais_."", $mapas)){
			$mapas["".$ciudad_.", ".$pais_.""] = $mapas["".$ciudad_.", ".$pais_.""] + 1;
		}else{
			$mapas["".$ciudad_.", ".$pais_.""]=1;
		}		
	}
	
	if($datos==""){
		if(array_key_exists($fecha_cargue, $datos_fechas_otros)){
			$datos_fechas_otros["".$fecha_cargue.""] = $datos_fechas_otros["".$fecha_cargue.""] + 1;
		}else{
			$datos_fechas_otros["".$fecha_cargue.""]=1;
		}
		
		if(array_key_exists($sitios, $web_otros)){
			$web_otros["".$sitios.""] = $web_otros["".$sitios.""] + 1;
		}else{
			$web_otros["".$sitios.""]=1;
		}
	}
	
	if($positivo=="S"){
		$positivos++;
	}
	if($negativo=="S"){
		$negativos++;
	}
	if($neutro=="S"){
		$neutros++;
	}
	
		
	$result_coincidencias_finales->MoveNext();
}

$fechas_todos = array_unique($fechas_todos);

///////////////////////////////////

arsort($web_regionales);
reset($web_regionales);

arsort($web_otros);
reset($web_otros);

///////////////////////////////////


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
</div>

<br /><br />

<div class="ttulosEstadisticas">
	<?=$_SESSION['bdm_etiquetas'][$id_etiqueta_mostrar]['etiqueta']?>
</div>

<script language="javascript">
	function enviarComplemento(){
		var todos = document.getElementById("todas");
		if(todos.checked==true){
			window.location.href="reputation_manager/estadisticas_graficas_clipping.php?etiqueta=<?=$id_etiqueta_mostrar?>&todas=1";
		}else{
			window.location.href="reputation_manager/estadisticas_graficas_clipping.php?etiqueta=<?=$id_etiqueta_mostrar?>&todas=0";
		}
	}
</script>

<table border="0" cellspacing="0" cellpadding="0" style="margin:0 auto;">
  <tr>
    <td>
	<form name="versus" action="" method="post">
    	<h3><?=_REPUTA_MSG4?> Vs</h3>
        <br />
        <label for="reg<?=$id_etiqueta_mostrar?>">
            <input type="checkbox" disabled="disabled" checked='checked' name="regionales" value="1" /> Medios
        </label>
        <br>
        <label for="otros<?=$id_etiqueta_mostrar?>">
            <input type="checkbox" name="todas" id="todas" <?=(($todas=="1")?"checked='checked'":"")?> value="1" onclick="enviarComplemento();"/> Otras fuentes
        </label>
	</form>
    </td>
    <td valign="top">
        <div style="max-width:900px; max-height:400px; margin:30px auto;">
<?php


/////////////////////////////////////////////////////////

$categorias = " 'Medios' ";

if($todas=="1"){
	$categorias .= ", 'Otros sitios' ";
}

/////////////////////////////////////////////////////////

$coincidencias_grafica = "";

foreach($fechas_todos as $fecha){
	$coinci_grafica = "['".date('d/m',strtotime($fecha))."', ".(($datos_fechas_regionales[$fecha]=="")?"0":$datos_fechas_regionales[$fecha])."";
	
	if($todas=="1"){
		$coinci_grafica .= ", ".(($datos_fechas_otros[$fecha]=="")?"0":$datos_fechas_otros[$fecha]);
	}
	
	$coinci_grafica .= "],";
	
	$coincidencias_grafica = $coinci_grafica.$coincidencias_grafica;
	
	if($cont==0){
		$final = date('d/m/Y',strtotime($fecha));
	}
	
	$inicio = date('d/m/Y',strtotime($fecha));
}

$coincidencias_grafica = rtrim($coincidencias_grafica,",");

?>
			<script type="text/javascript">
              google.load("visualization", "1", {packages:["corechart"]});
              google.setOnLoadCallback(drawChart_3);
              function drawChart_3() {
                var data = google.visualization.arrayToDataTable([
                  ['<?=_FECHA?>', <?=$categorias?>],
                  <?=$coincidencias_grafica?>
                ]);
            
                var options = {
                  //legend: 'none',
                  pointSize: 5,
                  hAxis: { title: '<?=_FECHA_DESDE." ".$inicio?> - <?=_FECHA_HASTA." ".$final?>', textPosition: 'none' }
                };
            
                var chart = new google.visualization.AreaChart(document.getElementById('general'));
                chart.draw(data, options);
              }
              
              
            
            </script>
            <div id="general" style="width: 900px; height: 400px;"></div>
        </div>
    </td>
  </tr>
</table>

<div style="margin: 0 0 0 30px;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="50%">
        <div class="ttulosEstadisticas">
			Top de Medios
        </div>        
        </td>
        <td width="40">&nbsp;</td>
        <td width="50%">
        <div class="ttulosEstadisticas">
			Top otras fuentes
        </div>
        </td>
      </tr>
      <tr>
        <td>
        <bR />
<?php

	echo '<table width="80%" border="0" cellspacing="2" cellpadding="2" style="margin:0 auto;" bgcolor="#fff">
	  <tr>
		<td bgcolor="#f1f1f1" width="70%" align="center">Medio</td>
		<td bgcolor="#f1f1f1" align="center">Publicaciones</td>
	  </tr>';
	
	$cantidad = 0;
	 
	foreach($web_regionales as $web=>$total){
	  
	  if($cantidad>4){
		break;  
	  }
	  echo '<tr>
		<td align="left">'.$web.'</td>
		<td align="center">'.$total.'</td>
	  </tr>
	  <tr>
		<td colspan="2"><div style="width:100%; display:block; background:#fafafa; height:5px;"></div></td>
	  </tr>';
	  
	  $cantidad++;
	}
	
	echo '</table>';

?>
        </td>
        <td>&nbsp;</td>
        <td>
        <bR />
<?php

	echo '<table width="80%" border="0" cellspacing="2" cellpadding="2" style="margin:0 auto;"  bgcolor="#fff">
	  <tr>
		<td bgcolor="#f1f1f1" width="70%" align="center">Medio</td>
		<td bgcolor="#f1f1f1" align="center">Publicaciones</td>
	  </tr>';
	  
	$cantidad = 0;
	
	foreach($web_otros as $web=>$total){
		
		if($cantidad>4){
			break;  
		}
		echo '<tr>
			<td align="left">'.$web.'</td>
			<td align="center">'.$total.'</td>
		</tr>
		<tr>
		<td colspan="2"><div style="width:100%; display:block; background:#fafafa; height:5px;"></div></td>
	  </tr>';
		
		$cantidad++;
	}
	
	echo '</table>';

?>
        </td>
      </tr>
    </table>
</div>

<div style="margin: 0 0 0 30px;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="50%">
        <div class="ttulosEstadisticas">
			Ciudades Origen
        </div>        
        </td>
        <td width="40">&nbsp;</td>
        <td width="50%">
        <div class="ttulosEstadisticas">
			Sentimiento
        </div>
        </td>
      </tr>
      <tr>
        <td>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
  
  $(window).load(function() {
	var map;
	var infowindow = new google.maps.InfoWindow();
	var marker;
	
	
	var mapOptions = {
      zoom: 5,
      center: new google.maps.LatLng(-34.602171,-58.430715),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
	 
	map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
	var limits = new google.maps.LatLngBounds();
	
<?php
foreach($mapas as $lugar=>$cant){
	$posicion = explode(",",$lugar);
	$ubi = "";
	
	if($posicion[0]=="" || $posicion[0]=="-"){
		$ubi = $posicion[1];
	}else{
		$ubi = $posicion[0].", ".$posicion[1];
	}
?>
	var address = '<?=$ubi?>';
	var geoCoder = new google.maps.Geocoder(address);
    geoCoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
        marker = new google.maps.Marker({
            map: map,
            position: results[0].geometry.location
        });
		
		google.maps.event.addListener(marker, 'click', (function(marker, i) {
			return function() {
			  infowindow.setContent("<div style='white-space:nowrap'><?=$ubi?> (<?=$cant?>)</div>");
			  infowindow.open(map, marker);
			}
		  })(marker, i));
		  limits.extend(results[0].geometry.location);
		  map.fitBounds(limits);
      }
    });
		
<?php
}
?>
		

		///////////////////////////////////////////////////////*/
	});
</script>

<div id="map_canvas" style="width:500px; height:280px; margin:20px 0 0 43px;"></div>
        </td>
        <td>&nbsp;</td>
        <td align="center">
<?php
	$marcaposa = "";
	$marcanega = "";
	$marcaneua = "";
	
	if($positivos > $negativos && $positivos > $neutros){
		$marcaposa = '<div style="width:15px; height:15px; background: #5FA69D; margin:0 auto;"></div>';
	}
	if($negativos > $positivos && $negativos > $neutros){
		$marcanega = '<div style="width:15px; height:15px; background: #5FA69D; margin:0 auto;"></div>';
	}
	if($neutros > $negativos && $neutros > $positivos){
		$marcaneua = '<div style="width:15px; height:15px; background: #5FA69D; margin:0 auto;"></div>';
	}
?>
            <div class="columna cajaResumen" style="width:350px; margin:30px auto; padding:0 30px;">  
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
                    <div style="margin:10px auto; width:15px; text-align:center"><?=$positivos?></div>
                    </td>
                    <td>&nbsp;</td>
                    <td>
                    <div style="width:15px; height:15px; border:2px solid #979797; padding:5px; margin:15px auto;">
                        <?=$marcaneua?>
                    </div>
                    <div style="margin:10px auto; width:15px; text-align:center"><?=$negativos?></div>
                    </td>
                    <td>&nbsp;</td>
                    <td>
                    <div style="width:15px; height:15px; border:2px solid #979797; padding:5px; margin:15px auto;">
                        <?=$marcanega?>
                    </div>
                    <div style="margin:10px auto; width:15px; text-align:center"><?=$neutros?></div>
                    </td>
                  </tr>
                </table>
            </div> 
        </td>
      </tr>
    </table>
</div>

<div style="margin: 0 0 0 30px;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="50%">
        <div class="ttulosEstadisticas">
			Tem&aacute;ticas
        </div>        
        </td>
        <td width="40">&nbsp;</td>
        <td width="50%">
        <div class="ttulosEstadisticas">
			Palabras importantes
        </div>
        </td>
      </tr>
      <tr>
        <td>
<?php
$categorias_grafica="";

foreach($tematicas as $tema=>$cant){		
	$categorias_grafica .= "['".$tema."',     ".$cant."],";	
}

$categorias_grafica = rtrim($categorias_grafica,",");

?>
<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart_1);
  function drawChart_1() {
	var data = google.visualization.arrayToDataTable([
	  ['Tematicas', '<?=_REPUTA_COINCI?>'],
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
        <td align="center">
        <bR />
<script type="text/javascript" src="js/word-cloud.js"></script>
<?php


$contenidos =$palabras_claves;


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
