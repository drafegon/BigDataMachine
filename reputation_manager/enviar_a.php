<?php
include "../cabecera.php";

$variables_metodo = variables_metodo("coincidenciasenviar,etiqueta");
$coincidenciasenviar= 	$variables_metodo[0];
$etiqueta= 				$variables_metodo[1];


$sql="SELECT 
		a.id,a.id_rss,a.cargue,a.marcada,a.positivo,
		a.negativo,a.bloqueada,a.intervenido,a.detalle_intervenido,a.id_categoria,
		a.titulo,a.contenido,a.link,a.semana,a.neutro
	  FROM ic_rss_coincidencias a
	  WHERE
		a.id IN (".$coincidenciasenviar."0)";
$result_coincidencias=$db->Execute($sql);

$comentarios = utf8_encode(_REPUTA_HOLARECO);

while(!$result_coincidencias->EOF){
	
	list($id,$id_rss,$cargue,$marcada,$positivo,$negativo,$bloqueada,$intervenido,$detalle_intervenido,
	     $cat_titulo,$titulo,$contenido,$link,$semana,$neutro)=select_format($result_coincidencias->fields);
		
	$comentarios .= $titulo."\n".$contenido."\n".$link."\n\n";
	
	$result_coincidencias->MoveNext();
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

<div class="mas_titulo" style="margin: 10px 0 0 50px;"><?=_REPUTA_RECOCOMEN?></div>
<br />
<div style="margin-left: 50px; padding:20px;" class="redondeado sombra columnaInternas4">
    <div class="columna">
      <table  border="0" cellpadding="5" cellspacing="0">
        <form action="reputation_manager/enviar_a_enviar.php?id=<?=session_id()?>" name="recomendacion" method="post" target="_self">
        <input type="hidden" name="etiqueta" id="etiqueta" value="<?=$etiqueta?>" />
            <tr>
              <td valign="top"><strong><?=_NOMBRE_COMPLETO?> (*)</strong></td>
              <td valign="top"><strong>
                <?=_EMAIL?>
                (*)</strong></td>
            </tr>
            <tr>
              <td valign="top"><input name="nombre_completo" type="text" class="campotexto" id="nombre_completo" style="width: 180px; " title="<?=_NOMBRE_COMPLETO?>" /></td>
              <td valign="top"><input name="email" type="text" class="campotexto" id="e_mail" style="width: 180px; " title="<?=_EMAIL?>" /></td>
            </tr>
            <tr>
              <td valign="top"><strong><?=_MSGAMIGO?> (*)<br>
              </strong></td>
              <td valign="top">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2" valign="top">
                <textarea name="comentarios" cols="30" rows="6" id="comentarios" style="width: 600px; " title="<?=_COMENTARIOS?>"><?=$comentarios?></textarea>
              </td>
            </tr>
            <tr>
              <td><input type="button" class="boton_verde sombra" onclick="enviar('nombre_completo,e_mail,comentarios');" name="Submit" value="<?=_ENVIAR_FORM?>" /></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td align="right"><div align="left"><font size="1">
                <i>
                <?=_MSG_CAMPOS_OBLIGATORIOS?>
                </i></font></div></td>
              <td align="right">&nbsp;</td>
            </tr>
        </form>
      </table>
	</div>
</div>