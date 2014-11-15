<?php

include("../cabecera.php");

$variables_metodo = variables_metodo("funcion,nombre,email,comentario");

$funcion= 			$variables_metodo[0];
$nombre= 			$variables_metodo[1];
$email_invitado= 	$variables_metodo[2];
$comentario= 		$variables_metodo[3];
?>
<style>
body{ background:#FFF; }
</style>
<?php
if($funcion=="guardar"){
	
	$sql="DELETE FROM ic_invitaciones_usuarios WHERE usuario='".$_SESSION['bdm_user']['gru_id']."' AND email='".$email_invitado."'";
	$result=$db->Execute($sql);

	$result=insert_bd_format("nombre,email,fecha,usuario", 
							 "ic_invitaciones_usuarios", 
							 array($nombre,$email_invitado,date('Y-m-d'),$_SESSION['bdm_user']['gru_id']), 
							 $db);	
	
	$sql="SELECT c_correo FROM ic_config";
	$result=$db->Execute($sql);
	list($MailTo)=select_format($result->fields);
	
	///////////////////////////////////
	
	$sql="SELECT id_invitacion FROM ic_invitaciones_usuarios WHERE usuario='".$_SESSION['bdm_user']['gru_id']."' AND email='".$email_invitado."'";
	$result=$db->Execute($sql);
	list($id_invitacion)=select_format($result->fields);
	
	///////////////////////////////////
	
	$fichero_url = fopen ("../mails/invitar.htm", "r");
	$texto = "";

	while ($trozo = fgets($fichero_url, 1024)){
		$texto .= $trozo;
	}	
	
	$codigo = "http://www.bigdatamachine.net/user.php?op_usu=nuevo_usuario&lang=_es&invitacion=" . rand() . "-" . $_SESSION['bdm_user']['gru_id'] . "-" . $id_invitacion . "-" . rand();
	
	$texto = str_replace("[(NAME)]", $nombre, $texto );
	$texto = str_replace("[(NAME2)]", $_SESSION['bdm_user']['us_nombre'], $texto );
	$texto = str_replace("[(MENSAJE)]", $comentario, $texto );
	$texto = str_replace("[(CODIGO)]", $codigo, $texto );
	
	/////////////////////////////////////////////////////////////
				
	$cabeceras = "MIME-Version: 1.0\r\n"; 
	$cabeceras .= "Content-type: text/html; charset=UTF-8\r\n"; 
	$cabeceras .= "From: "._BDM_INVIUSUSUBJET." <".$MailTo.">\r\n";
			
	$datos2 = array("email"=>$email_invitado,
				   "subjet"=>_BDM_INVIUSUSUBJET,
				   "texto"=>$texto,
				   "cabeceras"=>$cabeceras);
				   
	enviar_mails_bdm($datos2, _SERVER_OPTION);
		
	echo "<script> parent.window.location.reload();</script>";
	die();
}
?>

<script language="javascript">

function enviar(campos){

	var camposObligatorios = campos.split(",");

	for(i=0;i<camposObligatorios.length;i++)
	{
		if(document.getElementById(camposObligatorios[i]).value==""){
			alert("<?=_CAMPO?> "+ document.getElementById(camposObligatorios[i]).title +" <?=_OBLIGATORIO?>");
			return;
		}
	}
	document.recomendacion.submit();
}
</script>
<br>
<div class="mas_titulo" style="margin: 0 0 0 30px;"><?=_BDM_INVIUSU?></div>
<br />
<div>
	<div class="columna" style="margin-left: 30px;">
      <table  border="0" cellpadding="4" cellspacing="0">
        <form action="" name="recomendacion" method="post" target="_self">
        <input type="hidden" name="funcion" value="guardar" />
            <tr>
              <td valign="top"><strong><?=_NOMBRE_COMPLETO?> (*)</strong></td>
              <td valign="top"><strong>
                <?=_EMAIL?>
                (*)</strong></td>
            </tr>
            <tr>
              <td valign="top"><input name="nombre" type="text" class="campotexto" id="nombre" style="width: 180px; " value="<?=$nombre?>" title="<?=_NOMBRE_COMPLETO?>" /></td>
              <td valign="top"><input name="email" type="text" class="campotexto" id="email" style="width: 180px; " value="<?=$email_invitado?>" title="<?=_EMAIL?>" /></td>
            </tr>
            <tr>
              <td valign="top"><strong><?=_MSGAMIGO?> (*)<br>
              </strong></td>
              <td valign="top">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2" valign="top">
                <textarea name="comentario" cols="30" rows="4" id="comentario" style="width: 430px; " title="<?=_COMENTARIOS?>"></textarea>
              </td>
            </tr>
            <tr>
              <td><input type="button" class="boton_verde sombra" onclick="enviar('nombre,email,comentario');" name="Submit" value="<?=_ENVIAR_FORM?>" /></td>
              <td><div align="left"><font size="1"> <i>
                <?=_MSG_CAMPOS_OBLIGATORIOS?>
              </i></font></div></td>
            </tr>
        </form>
      </table>
	</div>
</div>
</body>
</html>