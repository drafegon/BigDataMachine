<?php
include "../cabecera.php";
$variables_metodo = variables_metodo("etiqueta");
$id_etiqueta_mostrar= 	$variables_metodo[0];

if($_SESSION['bdm_etiquetas'][$id_etiqueta_mostrar]['clipping']=="S"){
	echo "<META HTTP-EQUIV='Refresh' CONTENT='0;URL=".$_SESSION['c_base_location']."reputation_manager/estadisticas_reportes_clipping.php?etiqueta=".$id_etiqueta_mostrar."'>";
	die();
}

?>

<div style="width:100%; height: 40px; background:url(images/feb-2014/fondo_listado.jpg); position:fixed; top:0; z-index:50000;">
	<div style="float:left;">
    	<h2 style="margin: 5px 0 0 30px; font-size: 18px; color:#FFF;">
		<?=_REPUTA_ESTREPORTES?>
        </h2>
    </div>
</div>

<br /><br />

<div class="ttulosEstadisticas">
	<?=$_SESSION['bdm_etiquetas'][$id_etiqueta_mostrar]['etiqueta']?>
</div>

<div style="width:900px; margin:30px auto;">
	<table width="900" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top" width="400">
        <div class="cajaResumen" style="max-width:400px; margin:30px auto; padding:30px;">
        	<div class="tituRepo">
				<?=_REPUTA_REPOTI1?>  
            </div>
            <?=_REPUTA_REPODESCR1?> 
            
            <div style="margin-top:30px;">
                <div class="columna" style="font-size:20px; font-weight:bold;">
                    <?=_REPUTA_REPODESC?> 
                </div>
                
                <div class="columna" style="background:#e7e7e7; border:1px solid #989898; padding:5px 10px; float:right; cursor:pointer;" onclick="generar('1');">
                    EXCEL
                </div>
                <div class="columna" style="background:#e7e7e7; border:1px solid #989898; margin-right:7px; padding:5px 10px; float:right; cursor:pointer;" onclick="generar('3');">
                    CSV
                </div>
            </div>
        </div>
        </td>
        <td width="100">&nbsp;</td>
        <td valign="top" width="400">
        <div class="cajaResumen" style="max-width:400px; margin:30px auto; padding:30px;">
        	<div class="tituRepo">
				<?=_REPUTA_REPOTI2?>  
            </div>
            <?=_REPUTA_REPODESCR2?> 
            
            <div style="margin-top:30px;">
                <div class="columna" style="font-size:20px; font-weight:bold;">
                    <?=_REPUTA_REPODESC?> 
                </div>
                
                <div class="columna" style="background:#e7e7e7; border:1px solid #989898; padding:5px 10px; float:right; cursor:pointer;" onclick="generar('2');">
                    EXCEL
                </div>
                
                <div class="columna" style="background:#e7e7e7; border:1px solid #989898; margin-right:7px; padding:5px 10px; float:right; cursor:pointer;" onclick="generar('4');">
                    CSV
                </div>
            </div>
        </div>
        </td>
      </tr>
    </table>
</div>

<div style="width:900px; margin:0 auto;">
	<table width="900" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top" width="400">
        <div class="cajaResumen" style="max-width:400px; margin:20px auto; padding:30px;">
        	<div class="tituRepo">
				<?=_BDM_REPORESU?>  
            </div>
            <?=_BDM_REPOCONTE?> 
            
          <div style="margin-top:30px;">
                <div class="columna" style="font-size:20px; font-weight:bold;">
                    <?=_REPUTA_REPODESC?> 
                </div>
                
            <a href="http://www.bigdatamachine.net/reputation_manager/rtf/generando_documento.php?etiqueta=<?=$id_etiqueta_mostrar?>" class="columna" style="background:#e7e7e7; border:1px solid #989898; padding:5px 10px; float:right; cursor:pointer; color:#666"  rel="shadowbox[reporte];height=100;width=250;">
                    WORD
              </a>
            </div>
        </div>
        </td>
        <td width="100">&nbsp;</td>
        <td valign="top" width="400">
        <div class="cajaResumen" style="max-width:400px; margin:20px auto; padding:30px;">
        	<div class="tituRepo">
				<?=_REPODOCU1?>  
            </div>
            <?=_REPODOCU2?> 
            
            <div style="margin-top:30px;">
                <div class="columna">
                    <a href="reputation_manager/documentos_cliente.php?etiqueta=<?=$id_etiqueta_mostrar?>" target="_self" style="font-size:18px; color:#000">[ <?=_REPODOCU3?> ]</a>
                </div>
            </div>
        </div>
        </td>
      </tr>
    </table>
</div>

<script>
function generar(reporte){
	if(reporte=="1"){
		
		if(document.getElementById("desde_filtro")){
			document.getElementById("desde1").value=document.getElementById("desde_filtro").value;
		}
		if(document.getElementById("hasta_filtro")){
			document.getElementById("hasta1").value=document.getElementById("hasta_filtro").value;
		}
		
		document.reporte1.submit();
	}
	
	if(reporte=="2"){
		
		if(document.getElementById("desde_filtro")){
			document.getElementById("desde2").value=document.getElementById("desde_filtro").value;
		}
		if(document.getElementById("hasta_filtro")){
			document.getElementById("hasta2").value=document.getElementById("hasta_filtro").value;
		}
		
		document.reporte2.submit();
	}
	
	if(reporte=="3"){
		
		if(document.getElementById("desde_filtro")){
			document.getElementById("desde3").value=document.getElementById("desde_filtro").value;
		}
		if(document.getElementById("hasta_filtro")){
			document.getElementById("hasta3").value=document.getElementById("hasta_filtro").value;
		}
		
		document.reporte3.submit();
	}
	
	if(reporte=="4"){
		
		if(document.getElementById("desde_filtro")){
			document.getElementById("desde4").value=document.getElementById("desde_filtro").value;
		}
		if(document.getElementById("hasta_filtro")){
			document.getElementById("hasta4").value=document.getElementById("hasta_filtro").value;
		}
		
		document.reporte4.submit();
	}
}
</script>

<form action="reputation_manager/estadisticas_excel1.php" method="post" id="reporte1" name="reporte1" target="_blank">
	<input type="hidden" name="desde" id="desde1" value="" />
    <input type="hidden" name="hasta" id="hasta1" value="" />
    <input type="hidden" name="etiqueta" value="<?=$id_etiqueta_mostrar?>" />
</form>

<form action="reputation_manager/estadisticas_excel2.php" method="post" id="reporte2" name="reporte2" target="_blank">
	<input type="hidden" name="desde" id="desde2" value="" />
    <input type="hidden" name="hasta" id="hasta2" value="" />
    <input type="hidden" name="etiqueta" value="<?=$id_etiqueta_mostrar?>" />
</form>

<form action="reputation_manager/estadisticas_csv1.php" method="post" id="reporte3" name="reporte3" target="_blank">
	<input type="hidden" name="desde" id="desde3" value="" />
    <input type="hidden" name="hasta" id="hasta3" value="" />
    <input type="hidden" name="etiqueta" value="<?=$id_etiqueta_mostrar?>" />
</form>

<form action="reputation_manager/estadisticas_csv2.php" method="post" id="reporte4" name="reporte4" target="_blank">
	<input type="hidden" name="desde" id="desde4" value="" />
    <input type="hidden" name="hasta" id="hasta4" value="" />
    <input type="hidden" name="etiqueta" value="<?=$id_etiqueta_mostrar?>" />
</form>