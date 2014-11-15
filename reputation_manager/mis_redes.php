<?php
/**
 * @author Johann Stig Gravenhorst R.
 * @copyright 2013
 */
	include "../cabecera.php";
	
	//Permisos
	if(!permiso_usuario("CUENTAS_REDES",$_SESSION['bdm_user']['gru_id'],$db)){
		echo _NOPERMISOMODULO;
		die();
	}
	
	$variables_metodo = variables_metodo("funcion,redes");
	
	$funcion = $variables_metodo[0];
	$redes   = $variables_metodo[1];
	
	$tw = "";
	$fb = "";
	$lk = "";
	
	if( $funcion != "" && $funcion == "eliminar" ){		
		if($redes=="tw"){
			eliminar_tw($_SESSION['sess_usu_grupo'], $db);
		}elseif($redes=="fb"){
			eliminar_fb($_SESSION['sess_usu_grupo'], $db);
		}elseif($redes=="lk"){
			eliminar_lk($_SESSION['sess_usu_grupo'], $db);			
	    }
	}
	
	/////////////////////////////////////////////////////////////////////////////
  /**
   *  @Nombre eliminar_tw 
   *  @Parametros
   *        @param  $usuario
   *        @param  $db
   *  @Descripcion "Función que elimina los datos de la cuenta de Twitter del usuario" 
   */
	function eliminar_tw($usuario, $db){
	
		$sql_tw= "DELETE FROM ic_redes_usuario WHERE id_usuario='$usuario' AND tipo='TW'";
		  
		$result_tw = $db->Execute($sql_tw);
		
		echo"<script>alert('Cuenta de Twitter eliminada!')</script>";	
		echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=".$_SESSION['c_base_location']."reputation_manager/mis_redes.php'>";
		die();
	}

  /**
   *  @Nombre eliminar_fb
   *  @Parametros
   *        @param  $usuario
   *        @param  $db
   *  @Descripcion "Función que elimina los datos de la cuenta de Facebook del usuario" 
   */
	function eliminar_fb($usuario, $db){
		
		$sql_fb = "DELETE FROM ic_redes_usuario WHERE id_usuario='$usuario' AND tipo='FB'";
		  
		$result_fb = $db->Execute($sql_fb);
		
		echo"<script>alert('Cuenta de Facebook eliminada!')</script>";	
		echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=".$_SESSION['c_base_location']."reputation_manager/mis_redes.php'>";
		die();
	}
  
  /**
   *  @Nombre eliminar_lk
   *  @Parametros
   *        @param  $usuario
   *        @param  $db
   *  @Descripcion "Función que elimina los datos de la cuenta de LinkedIn del usuario" 
   */
	function eliminar_lk($usuario, $db){
		
		$sql_lk = "DELETE FROM ic_redes_usuario WHERE id_usuario='$usuario' AND tipo='LK'";
		  
		$result_lk = $db->Execute($sql_lk);
		
		echo"<script>alert('Cuenta de LinkedIn eliminada!')</script>";	
		echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=".$_SESSION['c_base_location']."reputation_manager/mis_redes.php'>";
		die();
	}
	
	/////////////////////////////////////////////////////////////////////////////
	
	
?>

<script type="text/javascript">
	function twitter(){
		window.open("reputation_manager/acceso_redes/link_twitter.php","Twitter","height=250,width=500,toolbar=no,resizable=no,scrollbars=no,location=no");	
	}
	
	function facebook(){
		window.open("reputation_manager/acceso_redes/link_facebook.php","Facebook","height=600,width=1100,toolbar=no,resizable=no,scrollbars=no,location=no");
	}
	
	function linkedIn(){
		window.open("reputation_manager/acceso_redes/link_linkedin.php","Linkedin","height=250,width=500,toolbar=no,resizable=no,scrollbars=no,location=no");	
	}
	
</script>

<div class="mas_titulo" style="margin: 10px 0 10px 50px; color:#FA5225; font-size:35px;"><?=_BDM_REDES?></div>
<br />
<div style="margin-left: 50px; padding:20px; width: 500px; font-size:14px;" class="redondeado sombra columnaInternas4">
<div style="margin: 0 0 35px 0;">
<?php

	// Consulta los datos de la cuenta de Twitter del usuario
	$sql_tw = "SELECT nickname_tw,nombre_usuario_tw,imagen_tw,cantidad_tw,cantidad_sg_tw FROM ic_redes_usuario 
	           WHERE id_usuario=".$_SESSION['sess_usu_grupo']." AND tipo='TW'";	
	$result_tw = $db->Execute($sql_tw);
	
   // Consulta los datos de la cuenta de FaceBook del usuario	
	$sql_fb ="SELECT nombre_usuario_fb,imagen_fb,cantidad_amg_fb FROM ic_redes_usuario 
	          WHERE id_usuario=".$_SESSION['sess_usu_grupo']." AND tipo='FB'";  
   $result_fb = $db->Execute($sql_fb);
   
   // Consulta los datos de la cuenta de LinkedIn del usuario	
	/*$sql_lk ="SELECT nickname,nombre_usuario,imagen,cantidad_tw,cantidad_sg FROM ic_redes_usuario
	          WHERE id_usuario=".$_SESSION['sess_usu_grupo']." AND tipo='LK'";  
   $result_lk = $db->Execute($sql_lk);*/

?>

	<form action="" name="btns_redes" method="post"  target="_self">
		<table cellpadding="0" cellspacing="0" border="0">
		  <tr>
		    <td colspan="8"><h2>Twitter</h2></td>
	      </tr>
		  <tr>
		    <td colspan="8">&nbsp;</td>
	      </tr>
		  <tr> <!-- Inicio cuenta Twitter -->
			  <?php
			      if ($result_tw->EOF) {
			  ?>
			    <td valign="top" colspan="8">
			        <input type="button" value="<?=_BDM_REDES2?>" id="bttw" onclick='twitter();'/>	
				</td>
			<?php	
			    }else {
			    	list($nickname_tw, $nombre_usuario_tw, $imagen_tw, $cantidad_tw, $cantidad_sg_tw) = select_format($result_tw->fields);
					$tw = "true";
			 ?>	 
			     <td width="1" valign="top">
                 	<img src="<?=$imagen_tw?>" id="imgRed" alt="<?=$nickname_tw?>" width="50" style="margin-right:10px"/>
                 </td>
                 <td valign="top" style="font-size:15px;">
				    <div style="font-weight:bold"><?=$nickname_tw?> </div>
				    <div><?=_BDM_REDESCANTTW." ".$cantidad_tw?> </div>
				    <div><?=_BDM_REDESCANSEG." ".$cantidad_sg_tw?></div>
				 </td>
                 <td width="150" valign="top">&nbsp;</td>
                 <td valign="top">
                 <div style="color:#FFF; white-space:nowrap; padding:10px 20px; background:#05A59B; cursor:pointer; font-weight:bold" class="sombra redondeado" onclick="document.location.href='<?=$_SESSION['c_base_location']?>redes_sociales/twitter.php';">
                 	<?=_REPUTA_GESTIONRED?>
                 </div>
                 <br />
				    <a href='reputation_manager/mis_redes.php?funcion=eliminar&redes=tw' target="_self" class="botonNegro"><?=_BDM_USUELI?></a>
                 </td>
			<?php
				  }
			?>
		 </tr> 	<!-- Fin cuenta Twitter -->
		 
		 <tr>
		   <td colspan="12">&nbsp;</td>
	      </tr>
		 <tr>
		   <td colspan="12"><div style="border-bottom:1px dashed #CCCCCC; margin: 15px 0;"></div></td>
	      </tr>
		 <tr>
		   <td colspan="12">&nbsp;</td>
	      </tr>
		 <tr>
		   <td colspan="12"><h2>Facebook</h2></td>
	      </tr>
		 <tr>
		   <td colspan="8">&nbsp;</td>
	      </tr>
		 <tr>   
			 <?php
			     if ($result_fb->EOF) {		 
			 ?>
			   <td valign="top" colspan="8">
			        <input type="button" value="<?=_BDM_REDES2?>" id="btfb" onclick='facebook();'/>	
			   </td>
			 <?php
			    }else{
			       list($nombre_usuario_fb,$imagen_fb,$cantidad_amg_fb) = select_format($result_fb->fields);
				   $fb = "true";
			 ?> 
			     <td width="1" valign="top">
                 	<img src="<?=$imagen_fb?>" id="imgRed" alt="<?=$nombre_usuario_fb?>" width="50" style="margin-right:10px"/>
                 </td>
                 <td valign="top" style="font-size:15px;">
                	<div style="font-weight:bold"><?=$nombre_usuario_fb?> </div>				    
				    <div><?=_BDM_REDESCANTAMIG." ".$cantidad_amg_fb?></div>
				 </td>
                 <td width="150" valign="top">&nbsp;</td>
                 <td valign="top">
                 <!--<div style="color:#FFF; padding:10px 20px; background:#05A59B; cursor:pointer; white-space:nowrap; font-weight:bold" class="sombra redondeado" onclick="document.location.href='<?=$_SESSION['c_base_location']?>redes_sociales/facebook.php';" align="center">
                 	<?=_REPUTA_GESTIONRED?>
                 </div>-->
                 <br />
				    <a href='reputation_manager/mis_redes.php?funcion=eliminar&redes=fb'target="_self" class="botonNegro"><?=_BDM_USUELI?></a>
                 </td>
			 <?php  	
			    } 	
			 ?>
		 </tr>  <!-- Fin cuenta Facebook -->
		 
		   <!--<tr>   Inicio cuenta LinkedIn
			 <?php
			     if ($result_lk->EOF) {		 
			 ?>
			   <td>
			        <input type="button" value="Linkedin" onclick='linkedin();' style="cursor:pointer; width:80px;"/>	
			   </td>
			 <?php
			    }else{
			       list($nickname, $nombre_usuario, $imagen, $cantidad_tw, $cantidad_sg) = select_format($result_lk->fields);
				   $lk = "true";
			 ?> 
			    <td>
				    <?=$nickname?> 
				    <img src="<?=$imagen?>" alt="<?=$nickname?>" width="100" />
				    <?=$cantidad_tw?>
				    <?=$cantidad_sg?>
				    <a href='mis_redes.php?funcion=eliminar&redes=lk'>Eliminar</a>
				 </td>
			 <?php  	
			    } 	
			 ?>
		 </tr>  --> <!-- Fin cuenta LinkedIn -->
		
	   </table>
			
	</form>	
  </div>
</div>

<script type="text/javascript">
	
	function verificar(tw, fb){
		verificarRedes(tw,fb);
		
		setTimeout('verificar(\''+tw+'\',\''+fb+'\');',3000);
	}

	verificar('<?=$tw?>', '<?=$fb?>');
</script>
