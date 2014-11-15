<?php

function bloque_opciones(){
	$path = "adodb/adodb.inc.php";
	include "admin/var.php";
	include "conexion.php";
	
	echo '<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
    <td width="10" align="center">&bull;</td>
    <td><a href="ver.php/mod/lista_etiquetas">Resumen Etiquetas</a></td>
  </tr>
  <tr>
    <td>&bull;</td>
    <td><a href="ver.php/mod/adm_etiquetas">Administrar Tag\'s</a></td>
  </tr>
  <tr>
    <td>&bull;</td>
    <td><a href="ver.php/mod/manage_rss">Administrar Mis Fuentes</a></td>
  </tr>
  </table>';
	
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function cargar_lista_rm($tabla,$campos,$orden,$defecto,$selected,$where,$db)
{

$sql="SELECT ".str_replace(";",",",$campos)." FROM ".$tabla." ";

if($where!=""){	$sql .= $where; }

if($orden!=""){ $sql .= " ORDER BY ".$orden." "; }

	$result=$db->Execute($sql);
	
	if ($defecto == "1") { echo "<option value='' >"._SELECCIONAR."...</option>"; }

    while(! $result->EOF)
	{
		$datos=$result->fields;
		
		if ($selected==$datos[0])
		{ 
			$select="selected='selected'"; 
		}
		else
		{
			$select=""; 
		}
		
		echo "<option value='".$datos[0]."' ".$select." >";
		
		$largo = substr_count ( $campos, ",");
		
		for ($i=1; $i<=$largo; $i++)
		{
			echo utf8_encode($datos[$i])." ";
		}
		echo "</option>";
	
	$result->MoveNext();
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function categoriasEtiquetas($etiqueta, $categoria, $url, $seleccionada, $db){
	$sql="SELECT a.cat_id,a.cat_titulo, b.nuevos, b.total 
	      FROM ic_categoria a, ic_etiquetas_categoria b 
		  WHERE b.id_categoria=a.cat_id AND b.id_etiqueta='".$etiqueta."' ";
	$result=$db->Execute($sql);
	
	echo '<select name="category_list" id="category_list" class="category_list" onchange="javascript:window.location.href=this.value;">';
	echo '<option value="'.$url.'">-- '._REPUTA_CATEGORIA.' -- </option>';
		
	while(!$result->EOF){
		list($cat_id,$cat_titulo,$nuevos,$total)=select_format($result->fields);
		
		if($cat_id==$categoria && $seleccionada==$etiqueta){
			echo '<option selected="selected" value="'.$url.'/categoria/'.$cat_id.'">'.$cat_titulo.' ('.$total.')</option>';
		}else{
			echo '<option value="'.$url.'/categoria/'.$cat_id.'">'.$cat_titulo.' ('.$total.') </option>';
		}
		$result->MoveNext();
	}
	
	echo '</select>';
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function filtro_fechas($dia1,$mes1,$ano1,$dia2,$mes2,$ano2){
	
	echo '<table border="0" cellspacing="0" cellpadding="1">
<form action="" method="post" name="filtroFechas" id="filtroFechas" target="_self">
  <tr>
    <td nowrap>'._REPUTA_FILTBUS.': </td>
    <td><em>('._FECHA_DESDE.')</em></td>
    <td>
<select size="1" name="dia1" id="dia1">
<option value="">-- --</option>
';
for($i = 1; $i <= 31; $i++){
  echo '      <option ';
  if($dia1 == $i)echo 'selected="selected"';
  echo 'value="'.$i.'">'.$i."</option>";
}
echo '
</select>
    </td>
    <td>
<select size="1" name="mes1" id="mes1">
<option value="">-- --</option>
';
$meses = array(_REPUTA_ENE,_REPUTA_FEB,_REPUTA_MAR,_REPUTA_ABR,_REPUTA_MAY,_REPUTA_JUN,_REPUTA_JUL,_REPUTA_AGO,_REPUTA_SEP,_REPUTA_OCT,_REPUTA_NOV,_REPUTA_DEC);
for($i = 1; $i <= 12; $i++){
  echo '      <option ';
  if($mes1 == $i)echo 'selected="selected"';
  echo 'value="'.$i.'">'.$meses[$i-1]."</option>";
}
echo '
</select>
    </td>
    <td>
<select size="1" name="ano1" id="ano1">
<option value="">-- --</option>
';
$anoInicial = date('Y');

for ($i = ($anoInicial-1); $i <= ($anoInicial); $i++){
  echo '      <option ';
  if($ano1 == $i)echo 'selected="selected" ';
  echo 'value="'.$i.'">'.$i."</option>";
}
echo '
</select>
    </td>
    <td><img src="images/spacer.gif" width="10" height="1" alt="" /></td>
    <td><em>('._FECHA_HASTA.')</em></td>
    <td>
<select size="1" name="dia2" id="dia2">
<option value="">-- --</option>
';
for($i = 1; $i <= 31; $i++){
  echo '      <option ';
  if($dia2 == $i)echo 'selected="selected"';
  echo 'value="'.$i.'">'.$i."</option>";
}
echo '
</select>
    </td>
    <td>
<select size="1" name="mes2" id="mes2">
<option value="">-- --</option>
';
$meses = array(_REPUTA_ENE,_REPUTA_FEB,_REPUTA_MAR,_REPUTA_ABR,_REPUTA_MAY,_REPUTA_JUN,_REPUTA_JUL,_REPUTA_AGO,_REPUTA_SEP,_REPUTA_OCT,_REPUTA_NOV,_REPUTA_DEC);
for($i = 1; $i <= 12; $i++){
  echo '      <option ';
  if($mes2 == $i)echo 'selected="selected"';
  echo 'value="'.$i.'">'.$meses[$i-1]."</option>";
}
echo '
</select>
    </td>
    <td>
<select size="1" name="ano2" id="ano2">
<option value="">-- --</option>
';
$anoInicial = date('Y');

for ($i = ($anoInicial-1); $i <= ($anoInicial); $i++){
  echo '      <option ';
  if($ano2 == $i)echo 'selected="selected" ';
  echo 'value="'.$i.'">'.$i."</option>";
}
echo '
</select>
    </td>
    <td><img src="images/spacer.gif" width="10" height="1" alt="" /></td>
    <td><input type="submit" value="'._REPUTA_FILTRAR.'" /></td>
	<td><img src="images/spacer.gif" width="5" height="1" alt="" /></td>
	<td><input type="button" value="'._REPUTA_LIMPIAR.'" onclick="reset_fechas();"/></td>
  </tr>
</form>
</table>';
	
}
?>