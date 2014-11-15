<?php
include "../cabecera.php";

//Permisos
if(!permiso_usuario("URL_PERSONALIZADA",$_SESSION['bdm_user']['gru_id'],$db)){
	echo _NOPERMISOMODULO;
	die();
}

$variables_metodo = variables_metodo("id_rss,fecha_creacion,nombre,url_rss,funcion,categoria,activo,icono,mensaje,no_es_rss,etiqueta");

$id_rss= 			$variables_metodo[0];
$fecha_creacion= 		$variables_metodo[1];
$nombre= 		$variables_metodo[2];
$url_rss= 			$variables_metodo[3];
$funcion= 		$variables_metodo[4];
$categoria= 		$variables_metodo[5];
$activo= 		$variables_metodo[6];
$icono= 		$variables_metodo[7];
$mensaje= 		$variables_metodo[8];
$no_es_rss= 		$variables_metodo[9];
$id_etiqueta= 		$variables_metodo[10];

if ($funcion == "guardar"){
	if($id_rss==""){
	/*Funcion para guardar los datos del formulario*/	
		guardar($fecha_creacion,$nombre,$url_rss,$categoria,$activo,$icono,$no_es_rss,$id_etiqueta,$db);
	}elseif($id_rss!=""){
	/*Funcion para modificar los datos del formulario*/	
		modificar($id_rss,$fecha_creacion,$nombre,$url_rss,$categoria,$activo,$icono,$no_es_rss,$id_etiqueta,$db);
	}	
}elseif(($funcion == "borrar")&&($id_rss!="")){
	borrar($id_rss,$id_etiqueta,$db);
}
/****************************************************************************************************************************/
function borrar($id_rss,$id_etiqueta,$db)
{	
	$result=$db->Execute("DELETE FROM ic_rss_usu_etiq WHERE id_rss = '".$id_rss."' AND id_usuario='".$_SESSION['sess_usu_grupo']."' AND id_etiqueta='".$id_etiqueta."'");
	
	$result=$db->Execute("DELETE FROM ic_rss WHERE id_rss = '".$id_rss."' AND usuario='".$_SESSION['sess_usu_grupo']."'");
	$result=$db->Execute("DELETE FROM ic_rss_coincidencias WHERE id_rss = '".$id_rss."' AND id_etiqueta='".$id_etiqueta."'");
	
	if ($result != false) $mensaje = "3";
	else $mensaje  = "0";
	
	echo"<script>alert('"._REPUTA_MISFUENTELIM_OK."')</script>";	
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=".$_SESSION['c_base_location']."reputation_manager/mis_fuentes.php?mensaje=".$mensaje."&etiqueta=".$id_etiqueta."'>";
	die();
}

/****************************************************************************************************************************/

function guardar($fecha_creacion,$nombre,$url_rss,$categoria,$activo,$icono,$no_es_rss,$id_etiqueta,$db)
{	
	$dir = "images/rss/";
	/*$url_full = $_FILES['icono']['tmp_name'];
	$imagen_full = $_FILES['icono']['name']; 
	
	if($url_full!="" && $imagen_full!=""){
		if(subir_imagen($imagen_full, $url_full, $dir)){
			$icono = $dir.$imagen_full;	
		}			
	}*/
	
	$paises = "*Afganistan**Albania**Alemania**Andorra**Angola**Anguila**Antartida**Antigua y Barbuda**Antillas Neerlandesas**Arabia Saudi**Arctic Ocean**Argelia**Argentina**Armenia**Aruba**Ashmore and Cartier Islands**Atlantic Ocean**Australia**Austria**Azerbaiyan**Bahamas**Bahrain**Baker Island**Bangladesh**Barbados**Bassas da India**Belgica**Belice**Benin**Bermudas**Bielorrusia**Birmania; Myanmar**Bolivia**Bosnia y Hercegovina**Botsuana**Brasil**Brunei**Bulgaria**Burkina Faso**Burundi**Butan**Cabo Verde**Camboya**Camerun**Canada**Chad**Chile**China**Chipre**Clipperton Island**Colombia**Comoras**Congo**Coral Sea Islands**Corea del Norte**Corea del Sur**Costa de Marfil**Costa Rica**Croacia**Cuba**Dinamarca**Dominica**Ecuador**Egipto**El Salvador**El Vaticano**Emiratos arabes Unidos**Eritrea**Eslovaquia**Eslovenia**Espana**Estados Unidos**Estonia**Etiopia**Europa Island**Filipinas**Finlandia**Fiyi**Francia**Gabon**Gambia**Gaza Strip**Georgia**Ghana**Gibraltar**Glorioso Islands**Granada**Grecia**Groenlandia**Guadalupe**Guam**Guatemala**Guayana Francesa**Guernsey**Guinea**Guinea Ecuatorial**Guinea-Bissau**Guyana**Haiti**Honduras**Hong Kong**Howland Island**Hungria**India**Indian Ocean**Indonesia**Iran**Iraq**Irlanda**Isla Bouvet**Isla Christmas**Isla Norfolk**Islandia**Islas Caiman**Islas Cocos**Islas Cook**Islas Feroe**Islas Georgia del Sur y Sandwich del Sur**Islas Heard y McDonald**Islas Malvinas**Islas Marianas del Norte**Islas Marshall**Islas Pitcairn**Islas Salomon**Islas Turcas y Caicos**Islas Virgenes Americanas**Islas Virgenes Britanicas**Israel**Italia**Jamaica**Jan Mayen**Japon**Jarvis Island**Jersey**Johnston Atoll**Jordania**Juan de Nova Island**Kazajistan**Kenia**Kingman Reef**Kirguizistan**Kiribati**Kuwait**Laos**Lesoto**Letonia**Libano**Liberia**Libia**Liechtenstein**Lituania**Luxemburgo**Macao**Macedonia**Madagascar**Malasia**Malaui**Maldivas**Mali**Malta**Man Isle of**Marruecos**Martinica**Mauricio**Mauritania**Mayotte**Mexico**Micronesia**Midway Islands**Moldavia**Monaco**Mongolia**Montserrat**Mozambique**Namibia**Nauru**Navassa Island**Nepal**Nicaragua**Niger**Nigeria**Niue**Noruega**Nueva Caledonia**Nueva Zelanda**Oman**Pacific Ocean**Paises Bajos**Pakistan**Palaos**Palmyra Atoll**Panama**Papua-Nueva Guinea**Paracel Islands**Paraguay**Peru**Polinesia Francesa**Polonia**Portugal**Puerto Rico**Qatar**Reino Unido**Republica Centroafricana**Republica Checa**Republica Democratica del Congo**Republica Dominicana**Reunion**Ruanda**Rumania**Rusia**Sahara Occidental**Samoa**Samoa Americana**San Cristobal y Nieves**San Marino**San Pedro y Miquelon**San Vicente y las Granadinas**Santa Helena**Santa Lucia**Santo Tome y Principe**Senegal**Serbia and Montenegro**Seychelles**Sierra Leona**Singapur**Siria**Somalia**Southern Ocean**Spratly Islands**Sri Lanka**Suazilandia**Sudafrica**Sudan**Suecia**Suiza**Surinam**Svalbard y Jan Mayen**Tailandia**Taiwan**Tanzania**Tayikistan**Territorio Britanico del Oceano Indico**Territorios Australes Franceses**Timor Oriental**Togo**Tokelau**Tonga**Trinidad y Tobago**Tromelin Island**Tunez**Turkmenistan**Turquia**Tuvalu**Ucrania**Uganda**Uruguay**Uzbekistan**Vanuatu**Venezuela**Vietnam**Wake Island**Wallis y Futuna**West Bank**World**Yemen**Yibuti**Zambia**Zimbabue*";
	
	$result=insert_bd_format("fecha_creacion,nombre,url_rss,categoria,activo,icono,fecha_ult_mod,usuario,no_es_rss,paises", "ic_rss", array($fecha_creacion,$nombre,$url_rss,"5",$activo,$icono,date('Y-m-d'),$_SESSION['sess_usu_grupo'],$no_es_rss,$paises), $db);
	
	//--------------------------------------------------------------------------------------------
	//Asigno el nuevo RSS ademas de la tabla CATALOGO al CATALOGO POR USUARIO para incluirlo en las etiquetas que ya manejen la categoria
	
	$sql="SELECT id_rss FROM ic_rss WHERE url_rss='".$url_rss."' AND fecha_creacion='".$fecha_creacion."'";
	$result=$db->Execute($sql);	
	list($id_rss)=select_format($result->fields);
	
	$result=insert_bd_format("id_usuario,id_etiqueta,id_rss,id_categoria,nombre_rss,url_rss,icono,nuevos,total,activo", 
							 "ic_rss_usu_etiq", 
							 array($_SESSION['sess_usu_grupo'], $id_etiqueta, $id_rss, "5", $nombre, $url_rss, $icono,"0","0",$activo), 
							 $db);

	//--------------------------------------------------------------------------------------------
	
	if ($result != false) $mensaje = "1";
	else $mensaje  = "0";
	
	echo"<script>alert('"._REPUTA_MISFUENTINSERT_OK."')</script>";
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=".$_SESSION['c_base_location']."reputation_manager/mis_fuentes.php?mensaje=".$mensaje."&etiqueta=".$id_etiqueta."'>";
	die();	
}

/****************************************************************************************************************************/

function modificar($id_rss,$fecha_creacion,$nombre,$url_rss,$categoria,$activo,$icono,$no_es_rss,$id_etiqueta,$db)
{	
	$dir = "images/rss/";
	/*$url_full = $_FILES['icono']['tmp_name'];
	$imagen_full = $_FILES['icono']['name']; 
	$icono2="";
	
	if($url_full!="" && $imagen_full!=""){
		if(subir_imagen($imagen_full, $url_full, $dir)){
			$icono2="icono";
			$icono = $dir.$imagen_full;	
		}			
	}*/
	
	$result=update_bd_format(array("no_es_rss"), "ic_rss", array($no_es_rss), "WHERE id_rss='".$id_rss."'", $db);
	
	//-----------------------------------------------------------------------------------
		
	$result=update_bd_format(array("nombre_rss","url_rss","icono","activo"), 
							 "ic_rss_usu_etiq", 
							 array($nombre, $url_rss, $icono, $activo),
							 "WHERE id_usuario='".$_SESSION['sess_usu_grupo']."' AND id_etiqueta='".$id_etiqueta."' AND id_rss='".$id_rss."'", 
							 $db);
	
	//-----------------------------------------------------------------------------------
	
	if ($result != false) $mensaje = "2";
	else $mensaje  = "0";	
	
	echo"<script>alert('"._REPUTA_MISFUENTMODIF_OK."')</script>";
	echo"<META HTTP-EQUIV='Refresh' CONTENT='0;URL=".$_SESSION['c_base_location']."reputation_manager/mis_fuentes.php?mensaje=".$mensaje."&id_rss=".$id_rss."&etiqueta=".$id_etiqueta."'>";
	die();
}
/****************************************************************************************************************************/

?>
<link rel="stylesheet" href="<?=$_SESSION['c_base_location']?>js/jquery.switchButton.css" />
<script src="<?=$_SESSION['c_base_location']?>js/jquery.switchButton.js"></script>

<form action="" name="cargarModificar" id="cargarModificar" method="post" target="_self">
<input type="hidden" name="id_rss" id="id_rss" value="" />
</form>

<script language="javascript">	
$(function() {
        $('#onoff input').switchButton();
});


function enviar(campos){

	var camposObligatorios = campos.split(",");
	
	for(i=0;i<camposObligatorios.length;i++)
	{	
		if(document.getElementById(camposObligatorios[i]).value==""){
			alert("<?=_CAMPO?> "+ document.getElementById(camposObligatorios[i]).title +" <?=_OBLIGATORIO?>");
			return;
		}
	}	
	document.guardar.submit();
}

function cargar(campo){
	var id = campo.value;
	
	document.getElementById("id_rss").value=id;
	
	document.getElementById("cargarModificar").submit();
}
</script>
<?php
$fecha_ult_mod="";
$usuario_creacion = "";

if ($id_rss != ""){
	$sql="SELECT id_rss,id_categoria,nombre_rss,url_rss,icono,nuevos,total,activo FROM ic_rss_usu_etiq WHERE id_usuario='".$_SESSION['sess_usu_grupo']."' AND id_etiqueta='".$id_etiqueta."' AND id_rss='".$id_rss."' ";
	$result=$db->Execute($sql);	
	list($id_rss,$categoria,$nombre,$url_rss,$icono,$nuevos,$total,$activo)=select_format($result->fields);
	
	$sql="SELECT no_es_rss,fecha_creacion,usuario FROM ic_rss WHERE id_rss='".$id_rss."' ";
	$result=$db->Execute($sql);	
	list($no_es_rss,$fecha_creacion,$usuario_creacion)=select_format($result->fields);
		
	echo '<input type="hidden" name="id_rss" value="'.$id_rss.'">';
}
?>

<div class="mas_titulo" style="margin: 10px 0 10px 50px; color:#FA5225; font-size:35px;"><?=_REPUTA_MELEG?></div>
<br />
<div style="margin-left: 50px; padding:20px; width: 600px; font-size:14px;" class="redondeado sombra columnaInternas4">
<div style="margin: 0 0 35px 0;">
    <div class="columna" style="float:left; width:200px; font-size: 22px;">
    <?=_REPUTA_INTERES?>
    </div>
    <div class="columna" style="float:right; width:300px; text-align:right; margin-top: 9px;">
        <?php if ($fecha_creacion!=""){ echo _REPUTA_CATEGO_FECH . "." . strftime("%d-%m-%Y", strtotime($fecha_creacion)); }?>
    </div>
</div>
<div class="separadorCasillas"></div>
<table border="0" cellspacing="0" cellpadding="3">
<form action="" name="guardar" method="post" enctype="multipart/form-data" target="_self">
<input type="hidden" name="funcion" value="guardar">
<input type="hidden" name="fecha_creacion" value="<?=date('Y-m-d')?>">
<input type="hidden" name="icono" value="" />
<input type="hidden" name="etiqueta" value="<?=$id_etiqueta?>" />
<input type="hidden" name="categoria" value="<?=$categoria?>" />
  <tr>
    <td><strong>
      <?=_REPUTA_FUENTESCAR?>
    </strong></td>
    <td width="1">&nbsp;</td>
    <td valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td><strong>
      <select name="id_rss2" onchange="cargar(this);" title="<?=_REPUTA_RSS_MIS?>" style="width:300px;">
        <?php cargar_lista_rm("ic_rss_usu_etiq","id_rss,nombre_rss,if(activo='S'; ' - (Activo)'; ' - (Inactivo)')","nombre_rss","1",$id_rss," WHERE id_etiqueta='".$id_etiqueta."' AND id_rss IN (SELECT id_rss FROM ic_rss WHERE usuario='".$_SESSION['sess_usu_grupo']."')",$db); ?>
      </select>
    </strong></td>
    <td>&nbsp;</td>
    <td valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td>
      <div class="separadorCasillas"></div>
      </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="1"><strong>
      <?=_REPUTA_CATEGO_NOM?>
      </strong></td>
    <td>&nbsp;</td>
    <td valign="top"><?=_REPUTA_TIPO_LINK?></td>
  </tr>
  <tr>
    <td><input name="nombre" type="text" id="nombre" value="<?=$nombre?>" style="width:300px;" title="<?=_REPUTA_CATEGO_NOM?>" /></td>
    <td>&nbsp;</td>
    <td valign="top"><select name="no_es_rss" id="no_es_rss" >
      <?php cargar_lista_estatica("S,N","Si,No",0,$no_es_rss); ?>
    </select></td>
    </tr>
  <tr>
    <td><strong>RSS/URL</strong></td>
    <td>&nbsp;</td>
    <td valign="top"><b>
      <?=_REPUTA_RSS_ACT?>
    </b></td>
    </tr>
  <tr>
    <td><input name="url_rss" type="text" value="<?=$url_rss?>" style="width:300px;" id="url_rss" title="RSS" /></td>
    <td><img src="images/spacer.gif" width="50" height="1" alt="" /></td>
    <td valign="top"><div id="onoff" class="on_off" style="float:left">
      <input type="radio" name="activo" value="S" <?=($activo=="S")?'checked="checked"':""?>/>
    </div></td>
    </tr>
  <tr>
    <td><br /></td>
    <td>&nbsp;</td>
    <td valign="top">
  <?php
	$valor = "1";
	$total = "0";
	
	if($id_rss==""){
		////////////////////////////////////////////////
		$sql="SELECT valor FROM ic_cuenta_usuarios WHERE parametro='URL_PERSONALIZADA' AND id_grupo_usuario='".$_SESSION['bdm_user']['gru_id']."'";
		$result_config=$db->Execute($sql);
		list($valor)=$result_config->fields;
		////////////////////////////////////////////////
		
		$sql="SELECT COUNT(*) FROM ic_rss WHERE usuario='".$_SESSION['bdm_user']['gru_id']."'";
		$result_config=$db->Execute($sql);
		list($total)=$result_config->fields;
	}
	
	if($valor>$total){
?>
      <input type="button" value="Guardar" onclick="enviar('nombre,url_rss');" style="cursor:pointer; width:80px;"/>
  <?php   
	}
?>   
      </td>
  </tr>
  <tr>
    <td><?=_REPUTA_MSGRSS?></td>
    <td>&nbsp;</td>
    <td valign="top"><?php if($usuario_creacion!="" && $usuario_creacion!="0"){ ?>
      <input type="button" value="Eliminar" onclick="javascript:if(confirm('<?=_REPUTA_MISFUENTELIM?>')){ document.getElementById('eliminar_rss').submit(); }" class="botonNegro" style="cursor:pointer; width:80px;"/>
      <?php
	}
	?></td>
  </tr>
</form>
</table>

<form action="" id="eliminar_rss" method="post" name="eliminar" target="_self">
<input type="hidden" name="id_rss" value="<?=$id_rss?>">
<input type="hidden" name="etiqueta" value="<?=$id_etiqueta?>" />
<input type="hidden" name="funcion" value="borrar">
</form>
	
</div>