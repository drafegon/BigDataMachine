<?php
include "../cabecera.php";

$nombre = obtenerDescripcion("valor,paquete", " AND parametro='NOMBRE_PAQUETE' ", "ic_cuenta_usuarios", $db);
$almacenamiento = obtenerDescripcion("valor", " AND parametro='ALAMACENAMIENTO' ", "ic_cuenta_usuarios", $db);
$palabras = obtenerDescripcion("valor", " AND parametro='BUSQUEDAS' ", "ic_cuenta_usuarios", $db);
$cierre_mes = obtenerDescripcion("valor", " AND parametro='FECHA_CIERRE' ", "ic_cuenta_usuarios", $db);
$usuario_app = obtenerDescripcion("valor", " AND parametro='MULTIUSUARIO' ", "ic_cuenta_usuarios", $db);
$precision = obtenerDescripcion("valor", " AND parametro='PRECISION' ", "ic_cuenta_usuarios", $db);

$buscar_uni = obtenerDescripcion("valor", " AND parametro='BUSCAR_UNIVERSO' ", "ic_cuenta_usuarios", $db);
$buscar_red = obtenerDescripcion("valor", " AND parametro='BUSCAR_REDES' ", "ic_cuenta_usuarios", $db);
$menci_uni = obtenerDescripcion("valor", " AND parametro='MENCIONES_UNIVERSO' ", "ic_cuenta_usuarios", $db);
$acumulado_uni = obtenerDescripcion("valor", " AND parametro='ACUMULADO_UNIVERSO' ", "ic_cuenta_usuarios", $db);
$menci_red = obtenerDescripcion("valor", " AND parametro='MENCIONES_REDES' ", "ic_cuenta_usuarios", $db);

$url_perso = obtenerDescripcion("valor", " AND parametro='URL_PERSONALIZADA' ", "ic_cuenta_usuarios", $db);
$sentimiento = obtenerDescripcion("valor", " AND parametro='ANALISIS_SENTIMIENTO' ", "ic_cuenta_usuarios", $db);
$noti_uni = obtenerDescripcion("valor", " AND parametro='NOTIFICACIONES_UNIVERSO' ", "ic_cuenta_usuarios", $db);
$noti_red = obtenerDescripcion("valor", " AND parametro='NOTIFICACIONES_REDES' ", "ic_cuenta_usuarios", $db);

$total_busquedas = cantidad_busquedas_usuario($_SESSION['bdm_user']['gru_id'], $db);

///////////////////////////////////////////////////////////////////

$fecha_actual = strtotime(date("Y-m-d",time()));
$fecha_cierre = strtotime($cierre_mes->fields[0]);

$fechas = round((((($fecha_cierre - $fecha_actual) / 60) / 60) / 24), 0);

if($fechas<1){
	$fechas = ""._BDM_HACE." ".($fechas*-1)."";
}else{
	$fechas = ""._BDM_QUEDAN." ".$fechas."";
}

///////////////////////////////////////////////////////////////////

$sql="SELECT COUNT(*) FROM ic_etiquetas WHERE usuario_cliente='".$_SESSION['bdm_user']['gru_id']."' AND es_sinonimo <> 'S'";
$result=$db->Execute($sql);
list($cant_eti)=select_format($result->fields);

$sql="SELECT COUNT(*) FROM ic_usu_gru WHERE gru_id='".$_SESSION['bdm_user']['gru_id']."'";
$result=$db->Execute($sql);
list($cant_usu)=select_format($result->fields);

$sql="SELECT COUNT(*) FROM ic_etiquetas WHERE usuario_cliente='".$_SESSION['bdm_user']['gru_id']."' AND es_sinonimo='S'";
$result=$db->Execute($sql);
list($cant_sino)=select_format($result->fields);

$sql="SELECT COUNT(*) FROM ic_rss WHERE usuario='".$_SESSION['bdm_user']['gru_id']."'";
$result=$db->Execute($sql);
list($cant_url)=select_format($result->fields);

?>

<div class="mas_titulo" style="margin: 10px 0 10px 50px; color:#FA5225; font-size:35px;"><?=$nombre->fields[0]. " <span style='font-size: 15px; '>"._BDM_RESUMEN."</span>"?></div>
<br />
<form action="" method="post" name="cambio_datos" id="cambio_datos"  target="_self">
<div style="margin-left: 50px; padding:20px; width: 350px; font-size:14px;" class="redondeado sombra columnaInternas4">

    <div class="columna" style="width:330px; float:left;">
    	<!-- // -->
        
        <div class="columna">
            <div class="marginFormFilas">
                <?=((!$palabras->EOF)?"<b>"._BDM_CANTPAL.":</b> ".$cant_eti." "._BDM_DE." ".$palabras->fields[0]:"")?>
            </div> 
            <div class="marginFormFilas">
                <?=((!$almacenamiento->EOF)?"<b>"._BDM_TIEMALM.":</b> ".$almacenamiento->fields[0]." "._BDM_DIASALMA."":"")?>
            </div>   
            <div class="marginFormFilas">
                <?=((!$usuario_app->EOF)?"<b>"._BDMUSULIMI.":</b> ".$cant_usu." "._BDM_DE." ".(($usuario_app->fields[0]=="-1")?_BDM_ILIMI:$usuario_app->fields[0]):"")?>
            </div>  
            <div class="marginFormFilas">
                <?=((!$precision->EOF)?"<b>"._BDM_CANTSINO.":</b> ".$precision->fields[0]:"")?>
            </div> 
            
            <div class="marginFormFilas">
                <?=((!$buscar_uni->EOF)?"<b>"._BDM_MENUNI.":</b> ".$buscar_uni->fields[0]."(".$acumulado_uni->fields[0]." "._BDM_DE." ".$menci_uni->fields[0].")":"")?>
            </div> 
            <div class="marginFormFilas">
                <?=((!$buscar_red->EOF)?"<b>"._BDM_MENREDES.":</b> ".$buscar_red->fields[0]." (".(($menci_red->fields[0]=="-1")?_BDM_ILIMI:$menci_red->fields[0]).")":"")?>
            </div> 
            <div class="marginFormFilas">
                <?=((!$url_perso->EOF)?"<b>"._BDM_URLPER.":</b> ".$sentimiento->fields[0]." (".$cant_url." "._BDM_DE." ".$url_perso->fields[0].")":"")?>
            </div> 

            <div class="marginFormFilas">
                <?=((!$noti_uni->EOF)?"<b>"._BDM_NOTIWEB.":</b> ".$noti_uni->fields[0]:"")?>
            </div> 
            <div class="marginFormFilas">
                <?=((!$noti_red->EOF)?"<b>"._BDM_NOTIRED.":</b> ".$noti_red->fields[0]:"")?>
            </div> 
            
            <div class="marginFormFilas">
                <?=((!$cierre_mes->EOF)?"<b>"._BDM_FECHACIERR.": ".$cierre_mes->fields[0]."</b> (".$fechas." "._BDM_DIASALMA.")":"")?>
            </div>            
        </div>
        
       
    </div>   

</div>

<div style="margin-left: 50px; width: 390px; font-size:14px;" class="columna">

    <div style="padding:20px; width: 380px; font-size:14px;" class="redondeado sombra columnaInternas4">
        <div class="columna" style="width:200px; background:#ccc; height:22px; text-align:center; font-style:italic; color:#000"><?=_BDM_ILIMI?></div> <div class="columna"><?=_BDM_MENREDES?></div>
        <div style="width:100%; padding:10px 0;"></div>
        
        <div class="columna" style="width:200px; background:#CCC; height:22px; text-align:center; font-style:italic; color:#FFF">
            <div style="width: <?=(($acumulado_uni->fields[0]!="")?(number_format(((($acumulado_uni->fields[0])*100)/($menci_uni->fields[0])),0)*2):"0")?>px; background:#999; height:22px;"></div>
        </div>
        <div class="columna"><?=_BDM_MENUNI?> <?=$menci_uni->fields[0]?></div>
    </div>

<?php
	/*if($buscar_uni->fields[0]=="SI"){
?>
    <div class="columna"  style="width:100%; margin: 20px 0 0 0;">
    	<div style="margin: 0 0 5px 0;"><?=_BDM_CANTBUSUNI?> <?=$total_busquedas?></div>
        <div>
        	<input type="text" id="total_busquedas_universo" value="<?=$total_busquedas?>" style="width:50px;"/>
        	<a href="javascript:;" target="_self" class="boton" style="padding: 4px 8px 4px 8px;"><?=_BDM_ACTUA?></a>
            <br />
            <span style="font-size:10px;"><?=_BDM_CANTMSG?></span>
        </div>
    </div>
<?php
	}*/
?>
	<div class="columna" style="width:100%;"><br />
        <a href="reputation_manager/eliminar_cuenta.php" target="_self"><?=_REPUTA_BAJACUE?></a>
    </div>
</div>
<br /><br />

<div class="columna" style="width:100%;"></div>

<div style="margin-left: 50px; width: 700px; font-size:14px;">
	<div class="columna">
    	<?php if($nombre->fields[1]!="2"){ ?><a href="reputation_manager/actualizar_paquete.php" target="_self" class="botonNegro"><?=(($nombre->fields[1]<3)?_BDM_ACTPAQ:_BDM_SOLMEJOR)?></a><?php } ?>
    </div>
    
    <div class="columna">
    	<?php if($nombre->fields[1]!="0" && $nombre->fields[1]!="-1"){ ?><a href="reputation_manager/actualizar_paquete.php" target="_self" class="boton" style="padding: 4px 8px 4px 8px;"><?=_BDM_PAGARMEN?></a><?php } ?>
    </div>
    <!--<div style="margin:0 0 20px 0;">
    <div class="columna">
    	<a href="redireccionpago.php?user=true&paquete=100" target="_self" class="boton" style="font-size:16px; padding: 6px 15px; background:#D93600;">2 * 1: Paquete Moderna</a>
    </div>
    <div class="columna">
    	<a href="redireccionpago.php?user=true&paquete=1000" target="_self" class="boton" style="font-size:16px; padding: 6px 15px;">35% descuento Paquete Moderna (3 meses)</a>
    </div>
    </div>
    <div>
    <div class="columna">
    	<a href="redireccionpago.php?user=true&paquete=200" target="_self" class="boton" style="font-size:16px; padding: 6px 15px;">2 * 1: Paquete Futura&nbsp;&nbsp;&nbsp;&nbsp;</a>
    </div>
    
    <div class="columna">
    	<a href="redireccionpago.php?user=true&paquete=2000" target="_self" class="boton" style="font-size:16px; padding: 6px 15px; background:#D93600;">35% descuento Paquete Futura (3 meses)&nbsp;&nbsp;&nbsp;&nbsp;</a>
    </div>
    </div>-->
</div>

</form>
<br /><br />