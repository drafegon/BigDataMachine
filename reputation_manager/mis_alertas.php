<?php
include "../cabecera.php";

$variables_metodo = variables_metodo("funcion,redes,universo,preferidos,fuentes,frecuencia");

$funcion= 			$variables_metodo[0];
$redes= 		$variables_metodo[1];
$universo= 		$variables_metodo[2];
$preferidos= 			$variables_metodo[3];
$fuentes= 		$variables_metodo[4];
$frecuencia= 		$variables_metodo[5];

if ($funcion == "guardar"){
	guardar($redes,$universo,$preferidos,$fuentes,$frecuencia,$db);
}

/****************************************************************************************************************************/

function guardar($redes,$universo,$preferidos,$fuentes,$frecuencia,$db)
{		
	$sql="DELETE FROM ic_alertas WHERE usuario_cliente='".$_SESSION['sess_usu_grupo']."'";
	$result=$db->Execute($sql);	
	
	$result=insert_bd_format("usuario_cliente,redes,universo,preferidos,fuentes,frecuencia",
	                         "ic_alertas",
							 array($_SESSION['sess_usu_grupo'],$redes,$universo,$preferidos,$fuentes,$frecuencia), 
							 $db);
	
	if ($result != false) $mensaje = "1";
	else $mensaje  = "0";
	
	echo"<script>alert('"._BDM_ALERTASOK."')</script>";
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=".$_SESSION['c_base_location']."reputation_manager/mis_alertas.php'>";
	die();	
}

/****************************************************************************************************************************/

	$sql="SELECT usuario_cliente,redes,universo,preferidos,fuentes,frecuencia FROM ic_alertas WHERE usuario_cliente='".$_SESSION['sess_usu_grupo']."'";
	$result=$db->Execute($sql);	
	if(!$result->EOF){
		list($usuario_cliente,$redes,$universo,$preferidos,$fuentes,$frecuencia)=select_format($result->fields);
	}
	
	$sql="SELECT tipo FROM ic_redes_usuario WHERE id_usuario='".$_SESSION['sess_usu_grupo']."'";
	$result=$db->Execute($sql);	
	list($tipo)=select_format($result->fields);

?>

<div class="mas_titulo" style="margin: 10px 0 10px 50px; color:#FA5225; font-size:35px;"><?=_BDM_ALERTAS?></div>
<br />
<div style="margin-left: 50px; padding:20px; width: 700px; font-size:14px;" class="redondeado sombra columnaInternas4">
<div style="margin: 0 0 35px 0;">
    <div class="columna" style="float:left; width:600px; font-size: 22px;">
    <?=_BDM_ALERTAS0?>
    </div>
</div>
<div class="separadorCasillas"></div>
<table border="0" cellspacing="0" cellpadding="3">
<form action="" name="guardar" method="post" target="_self">
<input type="hidden" name="funcion" value="guardar">
  <tr>
    <td width="1" nowrap="nowrap"><strong>
      <?=_BDM_ALERTAS1?>
      </strong></td>
    <td width="1">&nbsp;</td>
    <td valign="top"><strong>
      <?=_BDM_ALERTAS6?>
    </strong></td>
    </tr>
  <tr>
    <td><table border="0" cellspacing="3" cellpadding="0">
      <tr>
<?php
	if(permiso_usuario("BUSCAR_REDES",$_SESSION['bdm_user']['gru_id'],$db)){
?>
        <td nowrap="nowrap"><input type="checkbox" name="redes" id="redes" <?=(($redes!="")?'checked="checked"':"")?> value="S"/></td>
        <td nowrap="nowrap"><?=_BDM_ALERTAS2?></td>
        <td nowrap="nowrap">&nbsp;</td>
<?php
	}
	if(permiso_usuario("BUSCAR_UNIVERSO",$_SESSION['bdm_user']['gru_id'],$db)){
?>
        <td nowrap="nowrap"><input type="checkbox" name="universo" id="universo" <?=(($universo!="")?'checked="checked"':"")?> value="S" /></td>
        <td nowrap="nowrap"><?=_BDM_ALERTAS3?></td>
<?php
	}
?>
      </tr>
      <tr>
<?php
	if(permiso_usuario("BUSCAR_PREFERIDOS",$_SESSION['bdm_user']['gru_id'],$db)){
?>
        <td nowrap="nowrap"><input type="checkbox" name="preferidos" id="preferidos" <?=(($preferidos!="")?'checked="checked"':"")?> value="S" /></td>
        <td nowrap="nowrap"><?=_BDM_ALERTAS4?></td>
        <td nowrap="nowrap">&nbsp;</td>
<?php
	}
	if(permiso_usuario("BUSCAR_FUENTES",$_SESSION['bdm_user']['gru_id'],$db)){
?>
        <td nowrap="nowrap"><input type="checkbox" name="fuentes" id="fuentes" <?=(($fuentes!="")?'checked="checked"':"")?> value="S" /></td>
        <td nowrap="nowrap"><?=_BDM_ALERTAS5?></td>
<?php
	}
?>
      </tr>
    </table></td>
    <td>&nbsp;</td>
    <td valign="top">
    <div><?=(($tipo!="")?"SI":"NO")?></div>
    <div><i><?=_BDM_ALERTAS60?></i></div>
    </td>
    </tr>
  <tr>
    <td nowrap="nowrap">&nbsp;</td>
    <td>&nbsp;</td>
    <td valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td nowrap="nowrap"><?=_BDM_ALERTAS7?></td>
    <td>&nbsp;</td>
    <td valign="top">&nbsp;</td>
    </tr>
  <tr>
    <td>
<?php
$disabled = "";

//Permisos
if(!permiso_usuario("FRECUENCIAS_REDES",$_SESSION['bdm_user']['gru_id'],$db) || !permiso_usuario("FRECUENCIAS_WEB",$_SESSION['bdm_user']['gru_id'],$db)){
	$disabled = 'disabled="disabled"';
}
?>
      <select name="frecuencia" id="frecuencia" <?=$disabled?> >
        <?php cargar_lista_estatica("4,8,12,24,0",""._BDM_ALERTAS8.","._BDM_ALERTAS9.","._BDM_ALERTAS10.","._BDM_ALERTAS11.","._BDM_ALERTAS12."",1,$frecuencia); ?>
        </select>
    </td>
    <td><img src="images/spacer.gif" width="50" height="1" alt="" /></td>
    <td valign="top"><input type="submit" value="Guardar" style="cursor:pointer; width:80px;"/></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td valign="top">&nbsp;</td>
  </tr>
</form>
</table>

<form action="" id="eliminar_rss" method="post" name="eliminar" target="_self">
<input type="hidden" name="id_rss" value="<?=$id_rss?>">
<input type="hidden" name="etiqueta" value="<?=$id_etiqueta?>" />
<input type="hidden" name="funcion" value="borrar">
</form>
	
</div>