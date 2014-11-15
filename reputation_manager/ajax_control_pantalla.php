<?php
session_start();

$path = "../adodb/adodb.inc.php";
include ("../admin/var.php");
include ("../conexion.php");

include ("../language/language".$_SESSION['idioma'].".php");
	
include "../admin/funciones.php";
include("../admin/funciones_start_session.php");

$variables_metodo = variables_metodo("funcion,etiqueta");
$funcion= 		$variables_metodo[0];
$etiqueta= 		$variables_metodo[1];

$idusuario = $_SESSION['sess_usu_id'];
$idgrupo = $_SESSION['sess_usu_grupo'];

$_SESSION['actuales'] = $_SESSION['bdm_etiquetas'];
	
//precarga info etiquetas
$_SESSION['bdm_etiquetas']     = cargar_etiquetas_usuario( $idgrupo,$db ); 

///////////////////////////////////////////////////////////////////////////////////
	

$cambios = false;

foreach($_SESSION['bdm_etiquetas'] as $dato){
	if($_SESSION['actuales'][''.$dato['id'].'']['total'] != $dato['total']){
		$cambios = true;
	}
}

if($cambios || $funcion=="UPD"){
	////////////////////////////////////////////////////////////////////////////////////////////.
	
	echo '<div id="busquedasPrincipal" class="mElementoPrincipal" onclick="menuBusquedasGeneral();">'.(($etiqueta=="" || $etiqueta=="0")?_REPUTA_CREAR:$_SESSION['bdm_etiquetas'][$etiqueta]['etiqueta']).' ('.formateo_numero($_SESSION['bdm_etiquetas'][$etiqueta]['total']).')</div>
                        
            <div id="areaMenuBusqueda" class="mAreaMenu1 sombra">
            	<div id="menuBusquedas" class="mAreaMenu2">
					<div class="mTituBusqueda"><h3 style="color: #D35A31;">'._REPUTA_MISBUS.'</h3></div>
					<div id="elementosMenuJson"></div>
                </div>  
                
                <div style="padding:0 10px 3px 0; cursor:pointer;" onclick="$(\'#areaMenuBusqueda\').hide();" align="right"><img src="images/feb-2014/flecha_arriba.png" alt="ocultar" /></div>         
            
            	<input type="hidden" id="navegacion" value="reputation_manager/listado_resultados.php" />
            </div>';
		
	////////////////////////////////////
			
	echo "|".$_SESSION['bdm_etiquetas'][$etiqueta]['etiqueta'].
	       " (".$_SESSION['bdm_etiquetas'][$etiqueta]['nuevos'].
		   " "._BDM_PORREVI." "._BDM_DE." ".$_SESSION['bdm_etiquetas'][$etiqueta]['total'].
		   " "._BDM_RESULT.")";
}else{
	echo "NO";	
}
?>