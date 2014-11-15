<?php
include "../cabecera.php";
$variables_metodo = variables_metodo("etiqueta");
$id_etiqueta_mostrar= 	$variables_metodo[0];

?>

<div style="width:100%; height: 40px; background:url(images/feb-2014/fondo_listado.jpg); position:fixed; top:0; z-index:50000;">
	<div style="float:left;">
    	<h2 style="margin: 5px 0 0 30px; font-size: 18px; color:#FFF;">
		<?=_REPODOCU1?>
        </h2>
    </div>
</div>

<div style="margin: 80px 0 0 50px; padding:20px; width: 700px; font-size:14px;" class="redondeado sombra columnaInternas4">
	<div style="margin: 0 0 35px 0;">
        <div class="columna" style="float:left; width:650px; font-size: 14px;">
        	<?=_REPODOCU2?>
        </div>
	</div>
<div class="separadorCasillas"></div>
<?php
	$sql="SELECT id,titulo,fecha,archivo FROM ic_archivos_usuarios WHERE grupo_usuario='".$_SESSION['sess_usu_grupo']."' ORDER BY fecha DESC";
	$result=$db->Execute($sql);
	
	echo '<table width="100%" border="0" cellspacing="2" cellpadding="4" align="center">';
  
	while(!$result->EOF){
		list($id,$titulo,$fecha,$archivo)=($result->fields);
		
		$tipo_archivo = strtolower ( substr( strstr ( basename ( $archivo ), "." ), 1 ) );
		$img = "";
		
		if($tipo_archivo=="pdf"){
			$img = "images/tipos_doc/pdf.png";
		}elseif($tipo_archivo=="xls" || $tipo_archivo=="xlsx" || $tipo_archivo=="csv"){
			$img = "images/tipos_doc/kchart_chrt.png";
		}elseif($tipo_archivo=="jpg" || $tipo_archivo=="png" || $tipo_archivo=="gif"){
			$img = "images/tipos_doc/log.png";
		}else{
			$img = "images/tipos_doc/doc.png";
		}
		
		echo '<tr>
				<td width="80" bgcolor="#f4f4f4" align="center">
				<a href="http://www.bigdatamachine.net/'.$archivo.'" target="_blank"  style="font-size: 16px;"><img src="'.$img.'" width="40" /></a>
				</td>
				<td bgcolor="#f4f4f4" style="font-size: 16px;">'.utf8_decode($titulo).'<br><span style="color: #ccc; font-size: 12px;">'.$fecha.'</span></td>
				<td bgcolor="#f4f4f4" width="100" align="center"><a href="http://www.bigdatamachine.net/'.$archivo.'" target="_blank"  style="font-size: 16px;">'._REPUTA_REPODESC.'</a></td>
			  </tr>';
		
		$result->MoveNext();
	}
	
	echo '</table>';
?>

</div>