<?php
	session_start();
	
?>
<html>
<head>
<title>Mensaje Enviado Correctamente</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>

<?php

	if($_GET['id'] != session_id()){
		die("<META HTTP-EQUIV='Refresh' CONTENT='0;URL=ver.php/mod/recomendacion'>");
	}

	$path = "../adodb/adodb.inc.php";
	include ("../admin/var.php");
	include ("../conexion.php");
	include ("../admin/funciones.php");
	idioma_session($db);
	include ("../language/language".$_SESSION['idioma'].".php");

	$variablesPaginas = variables_metodo('nombre_completo,email,comentarios,etiqueta');
	$nombre_completo=		$variablesPaginas[0];
	$email	=				$variablesPaginas[1];
	$comentarios	=		$variablesPaginas[2];
	$etiqueta	=			$variablesPaginas[3];
	
	$sql="SELECT c_correo FROM ic_config";
	$result=$db->Execute($sql);
	list($MailTo)=select_format($result->fields);

	$cabeceras = "MIME-Version: 1.0\r\n"; 
	$cabeceras .= "Content-type: text/html; charset=UTF-8\r\n"; 
	$cabeceras .= "From: BigDataMachine <".$MailTo.">\r\n";
	
	$ini_msg=str_replace("[(NAME1)]", $nombre_completo, _REPUTA_RECOCOMENMSGINI);
	$ini_msg=str_replace("[(NAME2)]", $_SESSION['bdm_user']["us_nombre"], $ini_msg);
	$contenido= $ini_msg.nl2br($comentarios);
	
	$fichero_url = fopen ("../mails/enviar_a.htm", "r");
	$texto = "";

	while ($trozo = fgets($fichero_url, 1024)){
		$texto .= $trozo;
	}	
	
	$texto = str_replace("[(MENSAJE)]", utf8_decode($contenido), $texto );
	$db->close();
?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table width="500" border="1" cellspacing="0" cellpadding="20" align="center" bordercolor="#CCCCCC">
  <tr>
    <td align="center" valign="middle" bgcolor="#f4f4f4">

<script type="text/javascript">
function cargar()
{
	document.getElementById("cargando").style.display='none';
	document.getElementById("mensaje").style.display='inline';
}
</script>

<div id="cargando" style="margin:0 auto;">
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<img src="../images/admin/cargando_admin.gif" border="0" alt="Cargando" />
</div>

<?
	$datos = array("email"=>$email,
				   "subjet"=>_REPUTA_RECOCOMENSUB,
				   "texto"=>$texto,
				   "cabeceras"=>$cabeceras);
					   
	if(enviar_mails_bdm($datos, _SERVER_OPTION)){
?>
		<script type="text/javascript">
			setTimeout("cargar()",1000);
		</script>
		<div id="mensaje" class="titulo" style="display:none"><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
		<div align="center"><font color="#999999" size="4" face="Arial, Helvetica, sans-serif"><?=_MSG_CORREO_ENVIADO?></font></div>
		<META HTTP-EQUIV='Refresh' CONTENT='2;URL=listado_resultados.php?etiqueta=<?=$etiqueta?>'>
		</div>
<?
	}else{
?>
		<script type="text/javascript">
			setTimeout("cargar()",1000);
		</script>
		<div id="mensaje" class="titulo" style="display:none"><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
		<div align="center"><font color="#ff0000" size="4" face="Arial, Helvetica, sans-serif"><?=_MSG_CORREO_NOENVIADO?></font></div>
		<META HTTP-EQUIV='Refresh' CONTENT='2;URL=listado_resultados.php?etiqueta=<?=$etiqueta?>'>
		</div>
<?
}
?>
</td>
 </tr>
</table>

</body>
</html>
