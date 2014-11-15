<?php
include "../cabecera.php";
include("../admin/funciones_start_session.php");

$variables_metodo = variables_metodo("funcion,nombre_proyecto_new,id_proyecto_new,id_proyecto,nombre_proyecto,id_max,etiqueta");

$funcion= 			$variables_metodo[0];
$nombre_proyecto_new= 			$variables_metodo[1];
$id_proyecto_new= 		$variables_metodo[2];
$id_proyecto= 			$variables_metodo[3];
$nombre_proyecto= 		$variables_metodo[4];
$id_max= 		$variables_metodo[5];
$etiqueta= 		$variables_metodo[6];

if ($funcion == "guardar"){
	/*Funcion para guardar los datos del formulario*/	
	guardar($nombre_proyecto_new,$id_proyecto_new,$id_proyecto,$nombre_proyecto,$id_max,$db);
}elseif(($funcion == "borrar")&&($id_proyecto!="")){
	borrar($id_proyecto,$db);
}

/****************************************************************************************************************************/

function borrar($id_proyecto,$db){		
	$result=$db->Execute("UPDATE ic_etiquetas SET id_proyecto=NULL WHERE id_proyecto='".$id_proyecto."' ");
	$result=$db->Execute("DELETE FROM ic_proyectos WHERE id_proyecto='".$id_proyecto."' ");	
	
	// Precarga de directorios
	$_SESSION['bdm_directorios']   = cargar_directorios( $_SESSION['sess_usu_grupo'], $db );
}

/****************************************************************************************************************************/

function guardar($nombre_proyecto_new,$id_proyecto_new,$id_proyecto,$nombre_proyecto,$id_max,$db){
	if (($nombre_proyecto_new!="")){	
		$result_insertar=insert_bd_format("nombre_proyecto,fecha_creacion,id_usuario", 
		                                  "ic_proyectos", 
										  array($nombre_proyecto_new,date('Y-m-d'),$_SESSION['sess_usu_grupo']), 
										  $db);
		
		//Agrego un directorio
		/*$result_dir=insert_bd_format("id_usuario,nombre,fecha_creacion,total_registros", 
									  "ic_directorios", 
									  array($_SESSION['sess_usu_grupo'],$nombre_proyecto_new,date('Y-m-d'),"0"), 
									  $db);*/
	}
	
	////////////////////////////////////////////////////////////////////////
	
	if(($id_proyecto != "")&&($nombre_proyecto != "")){	
		for ($i=1;$i<=$id_max;$i++){	
			if(isset($id_proyecto[$i])){
				$result_modificar=update_bd_format(array("nombre_proyecto"), 
				                                   "ic_proyectos",
												   array($nombre_proyecto[$i]), 
												   "WHERE id_proyecto='".$id_proyecto[$i]."'", 
												   $db);
			}
		}
	}
	
	// Precarga de directorios
	$_SESSION['bdm_directorios']   = cargar_directorios( $_SESSION['sess_usu_grupo'], $db );
}

if($funcion=="guardar" || $funcion=="borrar"){
	echo '<div id="aviso_nuevos" style="width:300px; padding:20px 30px; background:#333; color:#FFF; text-align:center; position:absolute; top: 30px; cursor:pointer; left:50%; margin-left:-150px; display:inherit;" class="redondeado sombra" onclick="$(this).fadeOut();">';
	
	echo _REPUTA_PROYEACTUA;
	
	echo '</div>
	<script language="javascript" type="text/javascript">
		$.ajax({
		  url: "reputation_manager/ajax_control_pantalla.php",
		  async:true,   
		  cache:false,  
		  dataType:"html",
		  type: "POST", 
		  data: { etiqueta: "'.$etiqueta.'", funcion: "UPD"},
		  success: function(datos_recibidos) {			 
			 if(!/NO/.test(datos_recibidos)){				 
				datos_recibidos = datos_recibidos.split("|");
				
				$("#htmlSelect", window.parent.document).hide();
				$("#htmlSelect", window.parent.document).html(datos_recibidos[0]);
				$("#htmlSelect", window.parent.document).fadeIn();
				
				construirMenu("'.$etiqueta.'");			
			 }
		  }
	   });
	
		setTimeout("$(\'#aviso_nuevos\').fadeOut();",4000);
	</script>';
}
/****************************************************************************************************************************/
?>
<div class="mas_titulo" style="margin: 10px 0 10px 50px; color:#FA5225; font-size:35px;"><?=_BDM_MISPROYE?></div>
<br />
<div style="margin-left: 50px; padding:20px; width: 600px; font-size:14px;" class="redondeado sombra columnaInternas4">

<div style="margin: 0 0 35px 0;">
    <div style="float:left;">
    <?=_BDM_INFOPROY?>
    </div>
</div>

<table border="0" cellpadding="7" cellspacing="7">
<form action="" name="guardar" method="post" target="_self">
<input type="hidden" name="funcion" value="guardar">
<?php

$sql="SELECT id_proyecto,nombre_proyecto,fecha_creacion FROM ic_proyectos WHERE id_usuario='".$_SESSION['sess_usu_grupo']."' ORDER BY fecha_creacion ";
$result=$db->Execute($sql);
		
$sql_max="SELECT MAX(id_proyecto) FROM ic_proyectos WHERE id_usuario='".$_SESSION['sess_usu_grupo']."'";
$result_max=$db->Execute($sql_max);
list($id_max)=select_format($result_max->fields);

?>  
  <tr>
    <td align="center"><div align="center"><strong><?=_BDM_PROYFECHA?></strong></div></td>
    <td align="center"><div align="center"><strong><?=_BDM_PROYNOM?></strong></div></td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" bgcolor="#CCCCCC"><?=date('Y-m-d')?></td>
	<td bgcolor="#CCCCCC"><input name="nombre_proyecto_new" type="text" id="nombre_proyecto_new" value="" size="40"/></td>
    <td bgcolor="#CCCCCC">
    <input type="submit" name="Enviar" value="<?=_BDM_PROYGUARDAR1?>" />  
    </td>
    </tr>
    <tr>
    <td colspan="3" valign="top">
    <div style="border-bottom:1px dashed #CCCCCC; margin: 15px 0;"></div>
    </td>
    </tr>
  
<?php
while (!$result->EOF){
	list($id_proyecto,$nombre_proyecto,$fecha_creacion)=select_format($result->fields);
?>
  <tr>
  	<td align="center" valign="top">
		<?=$fecha_creacion?>
    </td>
    <td valign="top">
        <input type="hidden" name="id_proyecto[<?=$id_proyecto?>]" value="<?=$id_proyecto?>">
        <input type="text" name="nombre_proyecto[<?=$id_proyecto?>]" size="40" value="<?=$nombre_proyecto?>" /></td>
    <td>
        <a href="javascript:;" onclick="validarEliminar('<?=$id_proyecto?>');" title="Eliminar" target="_self">
        	<img src="images/menos.fw.png" border="0" />
        </a>
    </td>
    </tr>
<?php
	$result->MoveNext();
}
?>  
   <tr>
    <td colspan="3" valign="top">
    <div style="border-bottom:1px dashed #CCCCCC; margin: 15px 0;"></div>
    </td>
    </tr>
  <tr>
    <td colspan="3" valign="top">
     <input type="hidden" name="id_max" value="<?=$id_max?>" />
	 <input type="submit" name="Enviar" value="<?=_BDM_PROYGUARDAR2?>" />  
    </td>
  </tr>
</form>
</table>
</div>
<script type="text/javascript">
	function validarEliminar(id_registro){
		if(confirm('<?=_BDM_ELIPROY?>')){
			window.location.href="reputation_manager/mis_proyectos.php?funcion=borrar&id_proyecto="+id_registro;
		}else{
			return;
		}		
	}
</script>