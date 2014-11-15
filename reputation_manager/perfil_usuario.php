<?php
include "../cabecera.php";

$variables_metodo = variables_metodo("completar,actualizo");
$completar= 	$variables_metodo[0];
$actualizo= 	$variables_metodo[1];
		
$sql="SELECT 
		 us_id, us_codigo_ref, us_nombre, us_pais, us_telefono, us_email, us_direccion, us_ciudad,
		 us_terminos, us_notificaciones, us_descripcion, us_login, us_pass, us_imagen, us_idioma
	  FROM ic_usuarios
	  WHERE us_id ='".$_SESSION['sess_usu_id']."' ";
		
$result=$db->Execute($sql);

list($us_id, $us_codigo_ref, $us_nombre, $us_pais, $us_telefono, $us_email, $us_direccion, $us_ciudad,
	 $us_terminos, $us_notificaciones, $us_descripcion, $us_login, $us_pass, $us_imagen, $us_idioma)=select_format($result->fields);

if(!strstr($us_email,"twitter")){
	$completar=="1";
}

if($actualizo=="1"){
	echo '<div id="aviso_update" style="width:300px; padding:20px 30px; background:#333; color:#FFF; text-align:center; position:absolute; top: 30px; cursor:pointer; left:50%; margin-left:-150px; display:inherit;" class="redondeado sombra" onclick="$(this).fadeOut();">
	'._REPUTA_ACTUINFO.'
	</div>';
	
	echo '</div>
	<script language="javascript" type="text/javascript">
	setTimeout("$(\'#aviso_update\').fadeOut();",2000);
	</script>';
		
}

?>

<?php if($completar=="0"){ ?>
<div id="aviso_nuevos" style="width:300px; padding:20px 30px; background:#333; color:#FFF; text-align:center; position:absolute; top: 30px; cursor:pointer; left:50%; margin-left:-150px; display:inherit;" class="redondeado sombra" onclick="$(this).fadeOut();">
<?=_BDM_COMPLETARINFO?>
</div>
<?php } ?>

<script type="text/javascript">

function enviar(campos)
{
	var camposObligatorios = campos.split(",");
	
	for(i=0;i<camposObligatorios.length;i++)
	{	
		if(document.getElementById(camposObligatorios[i]).value==""){
			alert("<?=_CAMPO?> "+ document.getElementById(camposObligatorios[i]).title +" <?=_OBLIGATORIO?>");
			return;
		}
	}
	
	if(document.getElementById('us_pass').value!=document.getElementById('us_pass2').value)
	{
		alert("<?=_MSG_PASS_NO?>");
		return;
	}

	document.cambio_datos.submit();
}

</script>

<div class="mas_titulo" style="margin: 10px 0 10px 50px; color:#FA5225; font-size:35px;"><?=_REPUTA_MPERFIL?></div>
<br />
<div style="margin-left: 50px; padding:20px; width: 750px; font-size:14px;" class="redondeado sombra columnaInternas4">
<form action="" method="post" name="cambio_datos" id="cambio_datos" enctype="multipart/form-data" target="_self">
    <div class="columna" style="width:350px; float:left;">
    	<!-- // -->
        
        <div class="columna">
            <div class="marginFormFilas">
                <?=_NOMBRE_COMPLETO?>
            </div>
            <div class="marginFormFilas">
                <input name="us_nombre" type="text" id="us_nombre"  size="35" title="<?=_NOMBRE_COMPLETO?>" value="<?=$us_nombre?>" />
            </div>
        </div>
        
        <!-- // -->
        
        <div class="columna">
            <div class="marginFormFilas">
            	<?=_PAIS?>
            </div>
            <div class="marginFormFilas">
                <select name="us_pais" id="us_pais" title="<?=_PAIS?>">
                	<?php listado_paises($us_pais,"1");?>
                </select>
            </div>
        </div>
        
        <!-- // -->
        
        <div class="columna">
            <div class="marginFormFilas">
                <?=_TELEFONO?>
            </div>
            <div class="marginFormFilas">
                <input name="us_telefono" type="text" id="us_telefono"  title="<?=_TELEFONO?>" value="<?=$us_telefono?>" size="35"/>
            </div>
        </div>
        
        <!-- // -->
        
        <div class="columna" style="width:100%">
            <div class="marginFormFilas">
                <?=_REPUTA_IDIOMA?>
            </div>
            <div class="marginFormFilas">
                <select name="us_idioma" id="idioma" >
                    <?php cargar_lista("ic_language","lang_prefijo,lang_descripcion","lang_id","0",$us_idioma,"",$db); ?>
                </select>
            </div>
        </div>
        
        <!-- // -->
        
        <div class="columna" style="margin-right: 20px;">
            <div class="marginFormFilas">
                <?=_CIUDAD?>
            </div>
            <div class="marginFormFilas">
                <input name="us_ciudad" type="text" id="us_ciudad"  size="35" title="<?=_CIUDAD?>" value="<?=$us_ciudad?>" />
            </div>
        </div>
        
        <!-- // -->
        
        <div class="columna">
            <div class="marginFormFilas">
                <?=_DIRECCION?>
            </div>
            <div class="marginFormFilas">
               <input name="us_direccion" type="text" id="us_direccion" title="<?=_DIRECCION?>" value="<?=$us_direccion?>" size="35"/>
            </div>
        </div>
        
        <!-- // -->
    </div>
    
    <div class="columna" style="width:350px; float:left;">
        <div class="columna" style="width:100%">
            <div class="marginFormFilas">
                <?=_ENVI_ALERT?>
            </div>
            <div class="marginFormFilas">
                <select name="us_notificaciones" id="us_notificaciones" >
                    <?php cargar_lista_estatica("S,N",""._REPUTA_RSS_SI.","._REPUTA_RSS_NO."","0",$us_notificaciones,"",$db); ?>
                </select>
            </div>
        </div>
        
        <!-- // -->
        
        <div class="columna">
            <div class="marginFormFilas">
            <?=_EMAIL?>
            </div>
            <div class="marginFormFilas">
                <input name="us_email" type="text" id="us_email" title="<?=_EMAIL?>" value="<?=$us_email?>" size="35"/>
            </div>
        </div>
        
        <!-- // -->
        
        <div class="columna">
            <div class="marginFormFilas">
                <?=_CONTRASENA?>
            </div>
            <div class="marginFormFilas">
                <input name="us_pass" type="password" id="us_pass" title="<?=_CONTRASENA?>" value="<?=$us_pass?>" size="35"/>
            </div>
        </div>
        
        <!-- // -->
        
        <div class="columna">
            <div class="marginFormFilas">
                <?=_CONFIRM_PASS?>
            </div>
            <div class="marginFormFilas">
                <input name="us_pass2" type="password" id="us_pass2" title="<?=_CONFIRM_PASS?>" value="<?=$us_pass?>" size="35"/>
            </div>
        </div>
    	<div class="columna" style="width:100%; margin: 10px 0;">
            <input type="hidden" name="us_id" value="<?=$us_id?>" />
            <input type="hidden" name="funcion" value="modificar_usuario" />
            <input type="hidden" name="completo" value="<?=$completar?>" />
            <input type="button" onclick="enviar('us_nombre,us_email,us_pass,us_pass2,us_telefono,us_pais');" name="guardar" value="<?=_GUARDAR?>" />
        </div>
        
    </div>
    
</form>
</div>

<br /><br />