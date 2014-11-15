<?php
include "../cabecera.php";

//Permisos
if(!permiso_usuario("MULTIUSUARIO",$_SESSION['bdm_user']['gru_id'],$db)){
	echo _NOPERMISOMODULO;
	die();
}

		
$sql="SELECT id_invitacion,nombre,email,fecha FROM ic_invitaciones_usuarios WHERE usuario='".$_SESSION['bdm_user']['gru_id']."'";
$result_invitados=$db->Execute($sql);

$sql="SELECT us_id,us_nombre,us_email,us_fch_crea,us_tipo,us_imagen FROM ic_usuarios 
      WHERE us_id IN (SELECT id FROM ic_usu_gru WHERE gru_id='".$_SESSION['bdm_user']['gru_id']."') 
	        AND us_id!='".$_SESSION['bdm_user']['us_id']."' 
			AND us_invitado='S'";
$result_confirmados=$db->Execute($sql);

$usuarios_totales = $result_confirmados->RecordCount() + 1;

////////////////////////////////////////////////

$sql="SELECT valor FROM ic_cuenta_usuarios WHERE parametro='MULTIUSUARIO' AND id_grupo_usuario='".$_SESSION['bdm_user']['gru_id']."'";
$result_config=$db->Execute($sql);

////////////////////////////////////////////////
?>

<div class="mas_titulo" style="margin: 10px 0 10px 50px; color:#FA5225; font-size:35px;">
	<?=_BDMUSULIMI?>
</div>
<br />

<div style="margin-left: 50px; padding:20px; width: 850px; font-size:14px;" class="redondeado sombra columnaInternas4">

<span class="titulo"><?=_BDM_GRUPOMAESTRO?> <?=$_SESSION['bdm_user']['gru_titulo']?></span>
<?php
	if(!$result_config->EOF){
		list($valor)=$result_config->fields;
		
		if($valor<0 || $valor>$usuarios_totales){
?>
    <span style="float:right;">
    	<a href="reputation_manager/invitar.php" rel="shadowbox;height=320;width=500" class="boton"><?=_BDM_INVIUSU?></a>
    </span>
<?php
		}
	}
?>
    <hr color="#CCCCCC" size="1" width="100%" />
    <br />
<?php
	
	
	while(!$result_invitados->EOF){
		list($id_invitacion,$nombre,$email,$fecha)=select_format($result_invitados->fields);
		
		echo '<div style="margin: 5px 0;">
		      <table width="100%" border="0" cellspacing="2" cellpadding="6">
			  <tr>
				<td width="60" bgcolor="#f4f4f4" align="center"><img src="images/no-foto.jpg" width="50" /></td>
				<td width="220" bgcolor="#f4f4f4"><span style="font-size:18px; font-weight:bold">'.$nombre.'</span><br>'._BDM_INVIT." ".$fecha.'</td>
				<td bgcolor="#f4f4f4" align="center"><a href="reputation_manager/invitar.php?nombre='.$nombre.'&email='.$email.'" rel="shadowbox;height=320;width=500" >'.$email.'</a></td>
				<td bgcolor="#f4f4f4" align="center" width="110"><span style="font-size:16px;">'._BDM_PENDICREA.'</span></td>
				<td bgcolor="#f4f4f4" align="center" width="150">
				[ <a href="javascript:;" onclick="eliminarInvitacion(\''.$id_invitacion.'\')">'._REPUTA_DELINVI.'</a> ] [ <a href="reputation_manager/invitar.php?nombre='.$nombre.'&email='.$email.'" rel="shadowbox;height=320;width=500" >'._REPUTA_REENINVI.'</a> ]
				</td>
			  </tr>
			  <tr>
				<td colspan="6"><div style="border-bottom:1px dashed #CCCCCC; margin: 2px 0;"></div></td>
			  </tr>
			</table>
			</div>';
		
		$result_invitados->MoveNext();
	}
	
	////////////////////////////////////////////////////////////
	
	while(!$result_confirmados->EOF){
		list($us_id,$nombre,$email,$fecha,$tipo,$us_imagen)=select_format($result_confirmados->fields);
		
		$imagen = "";
		
		if($us_imagen!=""){
			$imagen = $us_imagen;
		}
		
		echo '<div style="margin: 5px 0;">
		      <table width="100%" border="0" cellspacing="2" cellpadding="6">
			  <tr>
				<td width="60" bgcolor="#f4f4f4" align="center"><img src="'.$imagen.'" width="50" /></td>
				<td width="220" bgcolor="#f4f4f4"><span style="font-size:18px; font-weight:bold">'.$nombre.'</span><br>'._BDM_CREA." ".$fecha.'</td>
				<td bgcolor="#f4f4f4" align="center">'.$email.'</td>
				<td bgcolor="#f4f4f4" align="center" width="110"><span style="font-size:16px;">'.(($tipo=="ADM_ETI")?_BDM_PERADM:_BDM_PERLECT).'</span></td>
				<td bgcolor="#f4f4f4" align="center" width="150">
				[ <a href="javascript:;" onclick="eliminarUsuarioCuenta(\''.$us_id.'\')">'._BDM_USUELI.'</a> ] ';
				
		if($tipo=="ADM_ETI"){
			echo '[ <a href="javascript:;" onclick="permisoUsuarioCuenta(\'USU_ETI\',\''.$us_id.'\')">'._BDM_PERLECT.'</a> ] ';
		}else{
			echo '[ <a href="javascript:;" onclick="permisoUsuarioCuenta(\'ADM_ETI\',\''.$us_id.'\')">'._BDM_PERADM.'</a> ] ';
		}
		
		echo '  </td>
			  </tr>
			  <tr>
				<td colspan="6"><div style="border-bottom:1px dashed #CCCCCC; margin: 2px 0;"></div></td>
			  </tr>
			</table>
			</div>';
		
		$result_confirmados->MoveNext();
	}
?>
</div>